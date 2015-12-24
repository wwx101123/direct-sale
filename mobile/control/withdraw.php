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

$template = 'withdraw/';
assign('subTitle', '提现管理');

$action = 'edit|add|view|delete';
$operation = 'edit|add|export';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'export')
{
    $withdraw_id = getPOST('withdraw_id');
    $withdraw_sn = getGET('withdraw_sn');
    $account = getGET('account');
    $status = intval(getGET('status'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

    $sql = 'select * from '.$db->table('withdraw');
    $where = ' where 1';

    if($withdraw_id != '')
    {
        $withdraw_id = substr($withdraw_id, 0, strlen($withdraw_id)-1);

        $withdraw_id = $db->escape($withdraw_id);

        $where .= ' and `id` in ('.$withdraw_id.')';
    } else {
        if($account != '')
        {
            $account = $db->escape($account);
            $where .= ' and `account`=\''.$account.'\'';
        }

        if($status > 0)
        {
            $where .= ' and `status`='.$status;
        }

        if($withdraw_sn != '')
        {
            $withdraw_sn = $db->escape($withdraw_sn);
            $where .= ' and `withdraw_sn`=\''.$withdraw_sn.'\'';
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

    $withdraw_list = $db->fetchAll($sql.$where);
    $log->record($sql.$where);

    if(!$withdraw_list)
    {
        echo '<script>alert("没有可以导出的数据");window.history.go(-1);</script>';
        exit;
    }

    $loader->includeClass('PHPExcel');

    $excel = new PHPExcel();
    $sheet = $excel->getActiveSheet(0);

    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(20);
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(20);

    $row = 1;

    $sheet->setCellValue('A'.$row, '申请编号');
    $sheet->setCellValue('B'.$row, '会员账号');
    $sheet->setCellValue('C'.$row, '提现金额');
    $sheet->setCellValue('D'.$row, '手续费');
    $sheet->setCellValue('E'.$row, '申请时间');
    $sheet->setCellValue('F'.$row, '开户银行');
    $sheet->setCellValue('G'.$row, '银行卡号');
    $sheet->setCellValue('H'.$row, '开户人');
    $sheet->setCellValue('I'.$row, '状态');
    $sheet->setCellValue('J'.$row, '处理时间');
    $sheet->setCellValue('K'.$row, '备注');

    $row++;

    foreach($withdraw_list as $withdraw)
    {
        //第一行
        $sheet->setCellValueExplicit('A'.$row, $withdraw['withdraw_sn']);
        $sheet->setCellValue('B'.$row, $withdraw['account']);
        $sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue('C'.$row, $withdraw['amount']);
        $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue('D'.$row, $withdraw['fee']);
        $sheet->setCellValue('E'.$row, date('Y-m-d H:i:s', $withdraw['add_time']));
        $sheet->setCellValue('F'.$row, $withdraw['bank_name']);
        $sheet->setCellValueExplicit('G'.$row, $withdraw['bank_card'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->setCellValue('H'.$row, $withdraw['bank_account']);
        $sheet->setCellValue('I'.$row, $lang['withdraw_status'][$withdraw['status']]);
        if($withdraw['status'] == 2)
        {
            $sheet->setCellValue('J' . $row, date('Y-m-d H:i:s', $withdraw['solve_time']));
        } else {
            $sheet->setCellValue('J' . $row, '');
        }
        $sheet->setCellValue('K'.$row, $withdraw['remark']);

        $row++;
    }

    //输出
    $filename = date('YmdHis').'提现申请列表';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

if($opera == 'edit')
{
    $withdraw_sn = getPOST('ewithdraw_sn');
    $remark = getPOST('remark');

    if($withdraw_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $withdraw_sn = $db->escape($withdraw_sn);
    }

    if($remark == '')
    {
        show_system_message('请填写备注信息');
    } else {
        $remark = $db->escape($remark);
    }

    if(update_withdraw($withdraw_sn, 2, $_SESSION['account'], $remark))
    {
        show_system_message('提现申请已处理', array(array('link'=>'withdraw.php', 'alt'=>'提现列表')));
    } else {
        show_system_message('系统繁忙');
    }
}

if('edit' == $act)
{
    $withdraw_sn = getGET('sn');

    if($withdraw_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $withdraw_sn = $db->escape($withdraw_sn);
    }

    $get_withdraw = 'select * from '.$db->table('withdraw').' where `withdraw_sn`=\''.$withdraw_sn.'\'';
    $withdraw = $db->fetchRow($get_withdraw);

    assign('withdraw', $withdraw);
}

if('view' == $act)
{
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');
    $status = getGET('status');
    $apply_sn = getGET('withdraw_sn');

    $where = ' where 1 ';

    if($status != '' and $status >= 0) {
        $where .= ' and `status`='.intval($status);
    } else {
        $status = -1;
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

    if($apply_sn != '')
    {
        $apply_sn = $db->escape($apply_sn);
        $where .= ' and `withdraw_sn`=\''.$apply_sn.'\'';
    }

    $get_total = 'select count(*) from '.$db->table('withdraw').$where;
    $total = $db->fetchOne($get_total);

    $count_expected = array(10, 25, 50, 100);
    $page = intval($page);
    $count = intval($count);
    if( !in_array($count, $count_expected) )
    {
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
    assign('apply_sn', $apply_sn);
    assign('status', $status);

    $get_withdraw_list = 'select * from '.$db->table('withdraw').$where.' order by `add_time` DESC limit '.$offset.','.$count;

    $withdraw_list = $db->fetchAll($get_withdraw_list);

    if($withdraw_list)
    {
        foreach ($withdraw_list as $k => $r)
        {
            if (check_purview('pur_withdraw_edit', $_SESSION['purview']) && $r['status'] == 1)
            {
                $withdraw_list[$k]['operation'] = '<a href="withdraw.php?act=edit&sn=' . $r['withdraw_sn'] . '">处理</a>';
            } else {
                $withdraw_list[$k]['operation'] = '';
            }
        }
    }

    assign('withdraw_list', $withdraw_list);
}

assign('act', $act);
$smarty->display($template.'withdraw.phtml');