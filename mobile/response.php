<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 下午2:08
 */
include 'library/init.inc.php';

$sn = trim(getGET('sn'));

if($sn == '')
{
    header('Location: index.php');
    exit;
}

$get_order_info = 'select * from '.$db->table('order').' where `order_sn`=\''.$sn.'\'';
$order_info = $db->fetchRow($get_order_info);

$order_info['add_time'] = date('Y-m-d H:i:s', $order_info['add_time']);
$order_info['show_status'] = $lang['order']['status_'.$order_info['status']];

assign('order', $order_info);
$smarty->display('response.phtml');