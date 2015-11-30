<?php
/**
 * 订单API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'add_to_cart|empty_cart|delete_from_cart|modify_cart|add_order|list|detail';
$opera = check_action($operation, getPOST('opera'));

$response = array('errno' => 1, 'errmsg' => '', 'errcontent' => array());

//加入购物车
if('add_to_cart' == $opera)
{
}

//清空购物车
if('empty_cart' == $opera)
{
}

//删除购物车
if('delete_from_cart' == $opera)
{
}

//修改购物车
if('modify_cart' == $opera)
{
}

//提交订单
if('add_order' == $opera)
{
}

//订单列表
if('list' == $opera)
{
}

//订单详情
if('detail' == $opera)
{
}

echo json_encode($response);
exit;
