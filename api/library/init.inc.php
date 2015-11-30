<?php
/**
 * API的预加载页面
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';

$script_list = array();
$loader->includeScript($script_list);

$class_list = array();
$loader->includeClass($class_list);

$token = getPOST('token');

if($token)
{
    $token = $db->escape($token);
    $get_account = 'select `account` from '.$db->table('member_login_logs').' where `token`=\''.$token.'\'';

    $account = $db->fetchOne($get_account);
    if($account)
    {
        $_SESSION['account'] = $account;
    }
}
