<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 上午10:40
 */
include 'library/init.inc.php';

$get_user_info = 'select * from '.$db->table('user').' where `openid`=\''.$_SESSION['openid'].'\'';

$user_info = $db->fetchRow($get_user_info);

assign('user_info', $user_info);
$smarty->display('account.phtml');