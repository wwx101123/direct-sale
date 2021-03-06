<?php
/**
 * 会员管理
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:04
 */
include 'library/init.inc.php';
global $config, $db, $log, $lang, $config, $smarty;

back_base_init();

$template = 'reward/';
assign('subTitle', '奖金管理');

$action = 'edit|add|view|delete|detail|dividend|level_up';
$operation = 'edit|add|export|send|send_dividend|level_up';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'send')
{
    $get_reward_list = 'select `account`,`reward`,`remark`,`type`,`id` from '.$db->table('reward').' where `status`=0 and `type`<>4';

    $reward_list = $db->fetchAll($get_reward_list);

    foreach($reward_list as $reward)
    {
        if(member_account_change($reward['account'], 0, $reward['reward'], -1*$reward['reward'],0,0,0,$_SESSION['admin_account'], 4, $reward['remark']))
        {
            $reward_status = array(
                'status' => 1,
                'solve_time' => time()
            );

            $db->autoUpdate('reward', $reward_status, '`id`='.$reward['id']);
        }
    }

    show_system_message('奖金发放完毕');
}

if($opera == 'send_dividend')
{
    $settle_time = trim(getPOST('settle_time'));
    $settle_time = strtotime($settle_time.'-01');

    if(empty($settle_time)) {
        show_system_message('结算月份无效');
    }

    $year = date('Y', $settle_time);
    $month = date('n', $settle_time);

    $get_reward = 'select 1 from '.$db->table('reward').' where `assoc`=\''.$year.$month.'\' and `type`=4 limit 1';
    if($db->fetchOne($get_reward)) {
        //当月分红已结算过
        show_system_message(date('Y-m', $settle_time).'分红已结算过');
    }

    if(dividend_settle($year, $month)) {
        $get_reward_list = 'SELECT `account`,`reward`,`remark`,`type`,`id` FROM ' . $db->table('reward') . ' WHERE `status`=0 AND `type`=4';

        $reward_list = $db->fetchAll($get_reward_list);

        foreach ($reward_list as $reward) {
            if (member_account_change($reward['account'], 0, $reward['reward'], -1 * $reward['reward'], 0, 0, 0, $_SESSION['admin_account'], 4, $reward['remark'])) {
                $reward_status = array(
                    'status' => 1,
                    'solve_time' => time()
                );

                $db->autoUpdate('reward', $reward_status, '`id`=' . $reward['id']);
            }
        }

        show_system_message('分红发放完毕');
    } else {
        show_system_message('可分红金额不足或达到分红指标的结点数为0');
    }

}

if($opera == 'level_up')
{
    $settle_time = trim(getPOST('settle_time'));
    $settle_time = strtotime($settle_time.'-01');

    if(empty($settle_time)) {
        show_system_message('月份无效');
    }

    $year = date('Y', $settle_time);
    $month = date('n', $settle_time);

    if(level_up($year, $month)) {
        show_system_message('会员升级完成');
    } else {
        show_system_message('升级失败');
    }

}

//=======================================================================================================

if($opera == 'export')
{
    $reward_id = getPOST('order_id');
    $account = getGET('account');
    $status = intval(getGET('status'));
    $type = intval(getGET('type'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

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

        if($status > 0)
        {
            $where .= ' and `status`='.$status;
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
                $where .= ' and `settle_time`>='.intval($begin_time);
            }
        }

        if($end_time != '')
        {
            $end_time = strtotime($end_time.' 23:59:59');
            if($end_time)
            {
                $where .= ' and `settle_time`<='.intval($end_time);
            }
        }
    }

    $reward_list = $db->fetchAll($sql.$where);
    $log->record($sql.$where);

    if(!$reward_list)
    {
        echo '<script>alert("没有可以导出的数据");window.history.go(-1);</script>';
        exit;
    }

    $export_data = [
        [
            '会员编号',
            '奖金',
            '奖金状态',
            '结算时间',
            '发放时间',
            '备注'
        ]
    ];

    if($reward_list) {
        while ($reward = array_shift($reward_list)) {
            $row_data = [
                $reward['account'],
                $reward['reward'],
                $reward['status'] ? '已发放' : '待发放',
                date('Y-m-d H:i:s', $reward['settle_time']),
                $reward['solve_time'] ? date('Y-m-d H:i:s', $reward['solve_time']) : '未发放',
                $reward['remark']
            ];

            array_push($export_data, $row_data);
        }
    }

    //输出
    $filename = date('YmdHis').'奖金列表';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
    header('Cache-Control: max-age=0');

    $f = fopen('php://output', 'w');
    fwrite($f, "\xEF\xBB\xBF");
    while($row = array_shift($export_data)) {
        fputcsv($f, $row);
    }
    fclose($f);
    exit;
}

if('dividend' == $act)
{
    $smarty->display($template.'dividend.phtml');
    exit;
}

if('level_up' == $act)
{
    $smarty->display($template.'level_up.phtml');
    exit;
}

if('view' == $act) {
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $type = intval(getGET('type'));
    $status = getGET('status');
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

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

    if($begin_time != '')
    {
        $begin_time = strtotime($begin_time.' 00:00:00');
        if($begin_time)
        {
            $where .= ' and `settle_time`>='.intval($begin_time);
        }
    }

    if($end_time != '')
    {
        $end_time = strtotime($end_time.' 23:59:59');
        if($end_time)
        {
            $where .= ' and `settle_time`<='.intval($end_time);
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
    assign('status', $status);
    assign('type', $type);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time): '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time): '');


    $get_reward_list = 'select * from '.$db->table('reward').$where.' order by `settle_time` DESC limit '.$offset.','.$count;

    $log->record($get_reward_list);
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