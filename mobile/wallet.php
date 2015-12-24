<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:11
 */
include 'library/init.inc.php';

//获取用户信息
$get_member_info = 'select `account`,`reward`,`reward_await`,`integral`,`integral_await`,`balance`,`level_id`,'.
    '`add_time`,`name` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$member_info = $db->fetchRow($get_member_info);
assign('member_info', $member_info);

$smarty->display('wallet.phtml');