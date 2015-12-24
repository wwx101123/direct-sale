<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/17
 * Time: 下午4:56
 */
include 'library/init.inc.php';

//获取用户信息
$get_user_info = 'select `reward` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$user_info = $db->fetchRow($get_user_info);
assign('user_info', $user_info);

//获取明细
$get_member_exchange = 'select `reward`,`add_time`,`remark` from '.$db->table('account').' where `account`=\''.$_SESSION['account'].
    '\' and `reward`<>0 order by `add_time` DESC';

$member_exchange = $db->fetchAll($get_member_exchange);
assign('member_exchange', $member_exchange);

assign('mode', 'reward');
assign('unit', '元');
assign('notice', '快来推广赚奖金');
assign('title', '我的奖金');
$smarty->display('points.phtml');