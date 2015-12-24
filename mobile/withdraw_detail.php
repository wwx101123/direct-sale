<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/21
 * Time: 上午10:17
 */
include 'library/init.inc.php';

$get_withdraw_list = 'select * from '.$db->table('withdraw').' where `account`=\''.$_SESSION['account'].'\' order by `apply_time` DESC';

$withdraw_list = $db->fetchAll($get_withdraw_list);
assign('withdraw_list', $withdraw_list);
$smarty->display('withdraw_detail.phtml');