<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/15
 * Time: 下午4:48
 */
include 'library/init.inc.php';

$get_member_list = 'select `name` as `wx_nickname`,`level_id`,`account`,`mobile`,`wx_headimg` from '.$db->table('member').' where `recommend`=\''.$_SESSION['account'].'\' order by (`reward`+`reward_await`) DESC';

$member_list_tmp = $db->fetchAll($get_member_list);

$member_list = array();
if($member_list_tmp)
{
    foreach ($member_list_tmp as $member)
    {
        $mobile = $member['mobile'];
        $mobile = substr($mobile, 0, 3) . '****' . substr($mobile, -4);

        $member['mobile'] = $mobile;
        $member_list[] = $member;
    }
}

assign('member_list', $member_list);
assign('title', '我的团队');

$smarty->display('team.phtml');