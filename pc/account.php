<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$param_list = '';
$where = '';

$type = intval(getGET('type'));
if($type > 0)
{
    $where .= ' and `type`='.$type;
    $param_list .= '&type='.$type;
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

$get_count = 'select count(*) from '.$db->table('account').
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
assign('type', $type);
assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

//分页结束
$get_account_list = 'select * from '.$db->table('account').
    ' where `account`=\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC'.$limit;

$account_list = $db->fetchAll($get_account_list);

assign('account_list', $account_list);

$smarty->display('account.phtml');
