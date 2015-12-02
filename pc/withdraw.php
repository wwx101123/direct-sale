<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$action = 'add|view';
$act = check_action($action, getGET('act'), 'view');

if('add' == $act)
{
    assign('sub_title', '提现申请');

    //计算待提现的金额
    $get_withdraw = 'select sum(`amount`) from '.$db->table('withdraw').' where `status`<2 and `account`=\''.$_SESSION['account'].'\'';

    $withdraw = $db->fetchOne($get_withdraw);

    assign('amount', $member_info['reward'] - $withdraw);
}

if('view' == $act)
{
    assign('sub_title', '提现记录');

    $param_list = '';
    $where = '';

    $status = intval(getGET('status'));
    if($status > 0)
    {
        $where .= ' and `status`=\''.$status.'\'';
        $param_list .= '&status='.$status;
    }

    $withdraw_sn = trim(getGET('withdraw_sn'));
    if($withdraw_sn != '')
    {
        $withdraw_sn = $db->escape($withdraw_sn);
        $where .= ' and `withdraw_sn`=\''.$withdraw_sn.'\'';
        $param_list .= '&withdraw_sn='.$withdraw_sn;
    }

    $begin_time = trim(getGET('begin_time'));
    if($begin_time != '' && $begin_time = strtotime($begin_time.' 00:00:00'))
    {
        $where .= ' and `add_time`>='.$begin_time;
        $param_list .= '&begin_time='.date('Y-m-d', $begin_time);
    }

    $end_time = trim(getGET('end_time'));
    if($end_time != '' && $end_time = strtotime($end_time.' 23:59:59'))
    {
        $where .= ' and `add_time`<='.$end_time;
        $param_list .= '&end_time='.date('Y-m-d', $end_time);
    }


    $get_count = 'select count(*) from '.$db->table('withdraw').
        ' where `account`=\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC';
//分页
    $page = intval(getGET('page'));

    if($page == 0)
    {
        $page = 1;
    }

    $step = 20;//每页显示20条记录
    $limit = ($page - 1) * $step;

    $limit = ' limit '.$limit.','.$step;

    $total_count = intval($db->fetchOne($get_count));

    $total_page = intval($total_count/$step);
    if($total_count%$step)
    {
        $total_page++;
    }

    assign('total_page', $total_page);
    assign('total_count', $total_count);
    assign('page', $page);
//其他参数
    assign('param_list', $param_list);
    assign('withdraw_sn', $withdraw_sn);
    assign('status', $status);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

//分页结束
    $get_withdraw_list = 'select * from '.$db->table('withdraw').
        ' where `account`=\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC'.$limit;

    $withdraw_list = $db->fetchAll($get_withdraw_list);

    assign('withdraw_list', $withdraw_list);
}

assign('act', $act);
$smarty->display('withdraw.phtml');
