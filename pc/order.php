<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = '';
$action = 'view|detail';
$act = check_action($action, getGET('act'), 'view');
$opera = check_action($operation, getPOST('opera'));

if('view' == $act)
{
    assign('sub_title', '我的订单');

    $param_list = '';
    $where = '';

    $order_sn = trim(getGET('order_sn'));
    if($order_sn != '')
    {
        $where .= ' and `order_sn`=\''.$order_sn.'\'';
        $param_list .= '&order_sn='.$order_sn;
    }

    $type = intval(getGET('type'));
    if($type > 0)
    {
        $where .= ' and `type`=\''.$type.'\'';
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
        $where .= ' and `end_time`<='.$end_time;
        $param_list .= '&end_time='.date('Y-m-d', $end_time);
    }


    $get_count = 'select count(*) from '.$db->table('order').
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
    assign('order_sn', $order_sn);
    assign('type', $type);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

    //分页结束
    $get_order_list = 'select * from '.$db->table('order').
        ' where `account`=\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC'.$limit;

    $order_list = $db->fetchAll($get_order_list);

    assign('order_list', $order_list);
}

if('detail' == $act)
{
    assign('sub_title', '订单详情');

    $order_sn = trim(getGET('sn'));

    if($order_sn == '')
    {
        header('Location: index.php');
        exit;
    }
    $order_sn = $db->escape($order_sn);

    $get_order = 'select * from '.$db->table('order').' where `order_sn`=\''.$order_sn.'\'';
    $order = $db->fetchRow($get_order);

    $order['add_time'] = date('Y-m-d H:i:s', $order['add_time']);
    assign('order', $order);

    $get_order_detail = 'select * from '.
        $db->table('order_detail').
        'where `order_sn`=\''.$order_sn.'\'';
    $order_detail = $db->fetchAll($get_order_detail);
    assign('order_detail', $order_detail);
}

assign('act', $act);
$smarty->display('order.phtml');
