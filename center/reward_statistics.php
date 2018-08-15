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
assign('subTitle', '奖金汇总');

$action = 'edit|add|view|delete|detail';
$operation = 'edit|add|export|send';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
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

    foreach($reward_list as $reward)
    {
        $row_data = [
            $reward['account'],
            $reward['reward'],
            $reward['status'] ? '已发放' : '待发放',
            date('Y-m-d H:i:s', $reward['add_time']),
            $reward['solve_time'] ? date('Y-m-d H:i:s', $reward['solve_time']) : '未发放',
            $reward['remark']
        ];

        array_push($export_data, $row_data);
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

if('view' == $act) {
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $type = intval(getGET('type'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

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

    $get_total = 'select `account`,sum(`reward`) as reward,`type` from '.$db->table('reward').$where.' group by `account`,`type` DESC';
    $total = $db->fetchAll($get_total);
    $total = count($total);

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
    assign('type', $type);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time): '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time): '');


    $get_reward_list = 'select `account`,sum(`reward`) as reward,`type` from '.$db->table('reward').$where.' group by `account`,`type` DESC limit '.$offset.','.$count;

    $total_reward = 0;

    $reward_list = $db->fetchAll($get_reward_list);
    if($reward_list) {
        foreach ($reward_list as $r) {
            $total_reward += $r['reward'];
        }
    }

    $get_total = 'select sum(reward) from '.$db->table('reward').$where.' and `type`=1';
    $recommend_reward = $db->fetchOne($get_total);

    $get_total = 'select sum(reward) from '.$db->table('reward').$where.' and `type`=2';
    $manage_reward = $db->fetchOne($get_total);

    assign('recommend_reward', $recommend_reward);
    assign('manage_reward', $manage_reward);
    assign('total_reward', $total_reward);
    assign('reward_list', $reward_list);
}

assign('act', $act);
$smarty->display($template.'reward_statistics.phtml');