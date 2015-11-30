<?php
/**
 * 管理后台首页
 * @author 王仁欢
 * @email wrh4285@163.com
 * @date 2015-8-5
 * @version 1.0.0
 */

include 'library/init.inc.php';

//管理后台初始化
back_base_init();
$template = 'main/';

$action = 'view';
$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$today = date('Y-m-d 00:00:00');
$today = strtotime($today);
//新增用户
$get_member_amount = 'select count(*) from '.$db->table('user').' where `add_time`>='.$today;
$member_amount = $db->fetchOne($get_member_amount);

//订单数量
$get_order_amount = 'select count(*) from '.$db->table('order').' where `add_time`>='.$today;
$order_amount = $db->fetchOne($get_order_amount);

assign('member_amount', $member_amount);
assign('order_amount', $order_amount);
$template .= $act.'.phtml';
$smarty->display($template);




