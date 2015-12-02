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
    assign('sub_title', '账户充值');

    $get_bank_list = 'select * from '.$db->table('bank_info');
    $bank_list = $db->fetchAll($get_bank_list);
    assign('bank_info', $bank_list);
}

if('view' == $act)
{
    assign('sub_title', '充值记录');

    $param_list = '';
    $where = '';

    $status = intval(getGET('status'));
    if($status > 0)
    {
        $where .= ' and `status`=\''.$status.'\'';
        $param_list .= '&status='.$status;
    }

    $recharge_sn = trim(getGET('recharge_sn'));
    if($recharge_sn != '')
    {
        $recharge_sn = $db->escape($recharge_sn);
        $where .= ' and `recharge_sn`=\''.$recharge_sn.'\'';
        $param_list .= '&recharge_sn='.$recharge_sn;
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


    $get_count = 'select count(*) from '.$db->table('recharge').
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
    assign('recharge_sn', $recharge_sn);
    assign('status', $status);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

//分页结束
    $get_recharge_list = 'select * from '.$db->table('recharge').
        ' where `account`=\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC'.$limit;

    $recharge_list = $db->fetchAll($get_recharge_list);

    assign('recharge_list', $recharge_list);
}

assign('act', $act);
$smarty->display('recharge.phtml');
