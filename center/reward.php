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

$template = 'reward/';
assign('subTitle', '奖金管理');

$action = 'edit|add|view|delete|detail';
$operation = 'edit|add|export|settle|stastics';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'settle')
{
    $get_reward_await = 'select `id`,`account`,`reward_await`,`integral_await` from '.$db->table('reward').' where `add_time`<='.$now.' and `status`=0';
    $reward_list = $db->fetchAll($get_reward_await);
    $log->record('发放'.date('Y-m-d', $now).'奖金');
    $log->record_array($reward_list);
    foreach($reward_list as $reward)
    {
        $flag = add_member_account($reward['account'], 'settle', 3, $reward['integral_await'], -1*$reward['integral_await'],
            $reward['reward_await'], -1*$reward['reward_await'], 0, '奖金发放');

        if($flag)
        {
            $ids .= $reward['id'].',';
        }
    }

    $ids = substr($ids, 0, strlen($ids)-1);

    $status_data = array(
        'status' => 1,
        'reach_time' => time()
    );
    $db->autoUpdate('reward', $status_data, '`id` in ('.$ids.')');

    show_system_message('奖金发放成功');
}

if($opera == 'stastics')
{
    $reward_id = getPOST('reward_id');
    $account = getGET('account');
    $status = intval(getGET('status'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

    $sql = 'select sum(`reward_await`) as `reward`,`account`,\'\' as `remark` from '.$db->table('reward');
    $where = ' where 1';

    if($reward_id != '')
    {
        $reward_id_str = substr($reward_id, 0, strlen($reward_id)-1);

        $reward_id_str = $db->escape($reward_id_str);

        $where .= ' and `id` in ('.$reward_id_str.')';
    } else {
        if($account != '')
        {
            $account = $db->escape($account);
            $where .= ' and `account`=\''.$account.'\'';
        }

        if($status >= 0)
        {
            $where .= ' and `status`='.$status;
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

    $reward_list = $db->fetchAll($sql.$where.' group by `account`');

    if(!$reward_list)
    {
        echo '<script>alert("没有可以导出的数据");window.history.go(-1);</script>';
        exit;
    }

    $loader->includeClass('PHPExcel');

    $excel = new PHPExcel();

    $excel->getActiveSheet(0)->getColumnDimension('B')->setWidth(20);
//    $excel->getActiveSheet(0)->getColumnDimension('D')->setWidth(20);
//    $excel->getActiveSheet(0)->getColumnDimension('F')->setWidth(20);

    $sheet = $excel->getActiveSheet(0);
    $row = 1;
    //第一行
    $sheet->setCellValue('A'.$row, '会员编号');
    $sheet->setCellValue('B'.$row, '待发奖金总额');
    $sheet->setCellValue('C'.$row, '备注');
    $row++;

    $reward_amount = 0;
    foreach($reward_list as $order)
    {
        //第二行
        $sheet->setCellValueExplicit('A'.$row, $order['account']);
        $sheet->setCellValue('B'.$row, sprintf('%.2f', $order['reward']));
        $sheet->setCellValue('C'.$row, $order['remark']);
        $reward_amount += $order['reward'];
        $row++;
    }

    $sheet->setCellValueExplicit('A'.$row, '合计');
    $sheet->setCellValue('B'.$row, sprintf('%.2f', $reward_amount));

    //输出
    $filename = date('YmdHis').'奖金汇总表';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

if($opera == 'export')
{
    $reward_id = getPOST('reward_id');
    $account = getGET('account');
    $status = intval(getGET('status'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

    $sql = 'select * from '.$db->table('reward');
    $where = ' where 1';

    if($reward_id != '')
    {
        $reward_id_str = substr($reward_id, 0, strlen($reward_id)-1);

        $reward_id_str = $db->escape($reward_id_str);

        $where .= ' and `id` in ('.$reward_id_str.')';
    } else {
        if($account != '')
        {
            $account = $db->escape($account);
            $where .= ' and `account`=\''.$account.'\'';
        }

        if($status >= 0)
        {
            $where .= ' and `status`='.$status;
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

    $reward_list = $db->fetchAll($sql.$where);

    if(!$reward_list)
    {
        echo '<script>alert("没有可以导出的数据");window.history.go(-1);</script>';
        exit;
    }

    $loader->includeClass('PHPExcel');

    $excel = new PHPExcel();

    $excel->getActiveSheet(0)->getColumnDimension('B')->setWidth(20);
//    $excel->getActiveSheet(0)->getColumnDimension('D')->setWidth(20);
//    $excel->getActiveSheet(0)->getColumnDimension('F')->setWidth(20);

    $sheet = $excel->getActiveSheet(0);
    $row = 1;
    //第一行
    $sheet->setCellValue('A'.$row, '会员编号');
    $sheet->setCellValue('B'.$row, '奖金');
    $sheet->setCellValue('C'.$row, '奖金状态');
    $sheet->setCellValue('D'.$row, '结算时间');
    $sheet->setCellValue('E'.$row, '发放时间');
    $sheet->setCellValue('F'.$row, '备注');
    $row++;

    foreach($reward_list as $order)
    {
        //第二行
        $sheet->setCellValueExplicit('A'.$row, $order['account']);
        $sheet->setCellValue('B'.$row, sprintf('%.2f', $order['reward_await']));
        $sheet->setCellValue('C'.$row, $order['status'] == 0 ? '待发放' : '已发放');
        $sheet->setCellValue('D'.$row, date('Y-m-d H:i:s', $order['add_time']));
        $sheet->setCellValue('E'.$row, $order['reach_time'] > 0 ? date('Y-m-d H:i:s', $order['reach_time']) : '未发放');
        $sheet->setCellValue('F'.$row, $order['remark']);
        $row++;
    }

    //输出
    $filename = date('YmdHis').'奖金列表';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

if('view' == $act) {
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $status = getGET('status');
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');
    if($status == '')
    {
        $status = -1;
    }
    assign('status', $status);

    $status = intval(getGET('status'));

    $where = ' where 1 ';

    if($status >= 0)
    {
        $status = intval($status);
        $where .= ' and `status`='.$status;
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

    $get_total = 'select count(*) from '.$db->table('reward').$where;
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
    assign('begin_time', date('Y-m-d', intval($begin_time)));
    assign('end_time', date('Y-m-d', intval($end_time)));


    $get_reward_list = 'select * from '.$db->table('reward').$where.' order by `add_time` DESC limit '.$offset.','.$count;

    $reward_list = $db->fetchAll($get_reward_list);

    assign('reward_list', $reward_list);
}

assign('act', $act);
$smarty->display($template.'reward.phtml');