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
$operation = 'edit|add|export|send';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'send')
{
    $get_reward_list = 'select `account`,`reward`,`remark`,`type`,`id` from '.$db->table('reward').' where `status`=0';

    $reward_list = $db->fetchAll($get_reward_list);

    foreach($reward_list as $reward)
    {
        if(add_member_account($reward['account'], 'settle', 6, $reward['reward'], -1*$reward['reward'], 0, 0, $reward['remark'].$prize_type[$reward['type']].'发放'))
        {
            $reward_status = array(
                'status' => 1,
                'send_time' => time()
            );

            $db->autoUpdate('reward', $reward_status, '`id`='.$reward['id']);
        }
    }

    show_system_message('奖金发放完毕');
}

if($opera == 'export')
{
    $reward_id = getPOST('reward_id');
    $account = getGET('account');
    $status = intval(getGET('status'));
    $type = intval(getGET('type'));

    $sql = 'select * from '.$db->table('reward');
    $where = ' where 1';

    if($reward_id != '')
    {
        $reward_id = substr($reward_id, 0, strlen($reward_id)-1);

        $reward_id = $db->escape($reward_id);

        $where .= ' and `id` in ('.$reward_id.')';
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

        if($type > 0)
        {
            $where .= ' and `type`='.$type;
        }

        if($reward_sn != '')
        {
            $reward_sn = $db->escape($reward_sn);
            $where .= ' and `reward_sn`=\''.$reward_sn.'\'';
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
        $sheet->setCellValue('B'.$row, sprintf('%.2f', $order['reward']));
        $sheet->setCellValue('C'.$row, $order['status'] == 0 ? '待发放' : '已发放');
        $sheet->setCellValue('D'.$row, date('Y-m-d H:i:s', $order['add_time']));
        $sheet->setCellValue('E'.$row, $order['reach_time'] >= 0 ? date('Y-m-d H:i:s', $order['reach_time']) : '未发放');
        $sheet->setCellValue('F'.$row, $order['remark']);
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
    $type = intval(getGET('type'));
    $status = getGET('status');
    if($status == '')
    {
        $status = -1;
    }

    $status = intval(getGET('status'));

    $where = ' where 1 ';

    if($status > 0)
    {
        $status = intval($status);
        $where .= ' and `status`='.$status;
    }

    if($type > 0)
    {
        $where .= ' and `type`='.$type;
    }

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
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
    assign('status', $status);
    assign('type', $type);


    $get_reward_list = 'select * from '.$db->table('reward').$where.' order by `add_time` DESC limit '.$offset.','.$count;

    $total_reward = 0;

    $reward_list = $db->fetchAll($get_reward_list);
    if($reward_list) {
        foreach ($reward_list as $r) {
            $total_reward += $r['reward'];
        }
    }

    $get_total = 'select sum(reward) from '.$db->table('reward').' where `type`=1';
    $recommend_reward = $db->fetchOne($get_total);

    $get_total = 'select sum(reward) from '.$db->table('reward').' where `type`=2';
    $manage_reward = $db->fetchOne($get_total);

    assign('recommend_reward', $recommend_reward);
    assign('manage_reward', $manage_reward);
    assign('total_reward', $total_reward);
    assign('reward_list', $reward_list);
}

assign('act', $act);
$smarty->display($template.'reward.phtml');