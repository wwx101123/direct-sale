<?php
/**
 * 会员管理
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:04
 */
include 'library/init.inc.php';
back_base_init();

$template = 'account/';
assign('subTitle', '账户明细');

$action = 'edit|add|view|delete';
$operation = 'edit|add|export';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'export')
{
    $account_id = getPOST('account_id');
    $account = getGET('account');
    $type = intval(getGET('type'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

    $sql = 'select * from '.$db->table('account');
    $where = ' where 1';

    if($account_id != '')
    {
        $account_id = substr($account_id, 0, strlen($account_id)-1);

        $account_id = $db->escape($account_id);

        $where .= ' and `id` in ('.$account_id.')';
    } else {
        if($account != '')
        {
            $account = $db->escape($account);
            $where .= ' and `account`=\''.$account.'\'';
        }

        if($type > 0)
        {
            $where .= ' and `type`='.$type;
        }

        if($begin_time != '')
        {
            $begin_time = strtotime($begin_time.' 00:00:00');
            if($begin_time)
            {
                $where .= ' and `add_time`>='.intval($begin_time);
            }
        }

        if($end_time != '')
        {
            $end_time = strtotime($end_time.' 23:59:59');
            if($end_time)
            {
                $where .= ' and `add_time`<='.intval($end_time);
            }
        }
    }

    $account_list = $db->fetchAll($sql.$where);
    $log->record($sql.$where);

    if(!$account_list)
    {
        echo '<script>alert("没有可以导出的数据");window.history.go(-1);</script>';
        exit;
    }

    $loader->includeClass('PHPExcel');

    $excel = new PHPExcel();
    $sheet = $excel->getActiveSheet(0);

    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getColumnDimension('F')->setWidth(40);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(20);

    $row = 1;

    $sheet->setCellValue('A'.$row, '记录ID');
    $sheet->setCellValue('B'.$row, '会员账号');
    $sheet->setCellValue('C'.$row, '账户余额');
    $sheet->setCellValue('D'.$row, '奖金余额');
    $sheet->setCellValue('E'.$row, '待发奖金');
    $sheet->setCellValue('F'.$row, '操作时间');
    $sheet->setCellValue('G'.$row, '交易类型');
    $sheet->setCellValue('H'.$row, '备注');

    $row++;

    foreach($account_list as $account)
    {
        //第一行
        $sheet->setCellValueExplicit('A'.$row, $account['id']);
        $sheet->setCellValue('B'.$row, $account['account']);
        $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        $sheet->setCellValue('C'.$row, $account['balance'] >= 0 ? '+'.$account['balance'] : $account['balance']);
        $sheet->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        $sheet->setCellValue('D'.$row, $account['reward'] >= 0 ? '+'.$account['reward'] : $account['reward']);
        $sheet->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        $sheet->setCellValue('E'.$row, $account['reward_await'] >= 0 ? '+'.$account['reward_await'] : $account['reward_await']);
        $sheet->setCellValue('F' . $row, date('Y-m-d H:i:s', $account['add_time']));
        $sheet->setCellValue('G'.$row, $lang['account_type'][$account['type']]);
        $sheet->setCellValueExplicit('H'.$row, $account['remark'], PHPExcel_Cell_DataType::TYPE_STRING);

        $row++;
    }

    //输出
    $filename = date('YmdHis').'账户明细列表';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

//============================================================
if('view' == $act)
{
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');
    $type = intval(getGET('type'));

    $where = ' where 1 ';

    if($type > 0)
    {
        $where .= ' and `type`='.$type;
    }

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
    }

    if($begin_time != '')
    {
        $begin_time = strtotime($begin_time.' 00:00:00');
        if($begin_time)
        {
            $where .= ' and `add_time`>='.intval($begin_time);
        }
    }

    if($end_time != '')
    {
        $end_time = strtotime($end_time.' 23:59:59');
        if($end_time)
        {
            $where .= ' and `add_time`<='.intval($end_time);
        }
    }

    $get_total = 'select count(*) from '.$db->table('account').$where;
    $total = $db->fetchOne($get_total);

    $count_expected = array(10, 25, 50, 100);
    $page = intval($page);
    $count = intval($count);
    if( !in_array($count, $count_expected) ) {
        $count = 10;
    }

    $total_page = ceil($total / $count);

    $page = ( $page > $total_page ) ? $total_page : $page;
    $page = ( $page <= 0 ) ? 1 : $page;

    $offset = ($page - 1) * $count;

    create_pager($page, $total_page, $total);
    assign('count', $count);
    assign('account', $account);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time): '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time): '');
    assign('type', $type);

    $get_account_list = 'select * from '.$db->table('account').$where.' order by `add_time` DESC limit '.$offset.','.$count;

    $account_list = $db->fetchAll($get_account_list);

    assign('account_list', $account_list);
}

assign('act', $act);
$smarty->display($template.'account.phtml');