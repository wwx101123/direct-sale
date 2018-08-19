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

$template = 'achievement/';
assign('subTitle', '业绩查看');

$action = 'view';
$operation = 'export';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
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

if('view' == $act) {
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $begin_time = getGET('begin_time');

    $where = ' where 1 ';

    if($begin_time != '')
    {
        $begin_time = strtotime($begin_time.'-01 00:00:00');
        if($begin_time)
        {
            $where .= ' and `year`='.date('Y', $begin_time).' and `month`='.date('n', $begin_time);
        }
    }

    $get_achievement_summary = 'select sum(`increment`) as increment,sum(`consume_increment`) as consume_increment from '.$db->table('achievement').$where;

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
        $get_achievement_summary .= ' and `account`=\''.$account.'\'';
    } else {
        $where .= ' and `account`<>\'\' ';
        $get_achievement_summary .= ' and `account`=\'\'';
    }

    $achievement_summary = $db->fetchRow($get_achievement_summary);
    assign('achievement_summary', $achievement_summary);

    $get_total = 'select count(*) from '.$db->table('achievement').$where;
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
    assign('begin_time', $begin_time > 0 ? date('Y-m', $begin_time): '');


    $get_achievement_list = 'select * from '.$db->table('achievement').$where.' order by `recommend_path` ASC limit '.$offset.','.$count;

    $log->record($get_achievement_list);
    $summary = [
        'self_increment' => 0,
        'consume_increment' => 0
    ];

    $achievement_list = $db->fetchAll($get_achievement_list);
    if($achievement_list) {
        foreach ($achievement_list as $r) {
            $summary['self_increment'] += $r['self_increment'];
            $summary['consume_increment'] += $r['consume_increment'];
        }
    }

    assign('summary', $summary);
    assign('achievement_list', $achievement_list);
}

assign('act', $act);
$smarty->display($template.'achievement.phtml');