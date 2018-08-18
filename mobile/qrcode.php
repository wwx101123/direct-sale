<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:11
 */
include 'library/init.inc.php';

$ticket = trim(getGET('ticket'));
$account = isset($_SESSION['account']) ? $_SESSION['account'] : '';

if($ticket)
{
    $openid = base64_decode($ticket);

    $openid = $db->escape($openid);

    $get_account = 'select `account` from '.$db->table('member').' where `wx_openid`=\''.$openid.'\'';
    $account = $db->fetchOne($get_account);
}

if(!$account)
{
    die('参数错误');
}
//获取用户信息
$get_member_info = 'select * from '.$db->table('member').' where `account`=\''.$account.'\'';
$member_info = $db->fetchRow($get_member_info);
assign('member_info', $member_info);

$qrcode = null;
if($member_info['wx_openid'])
{
    $access_token = get_access_token($config['appid'], $config['appsecret']);
    $qrcode = get_qrcode($member_info['wx_openid'], $access_token);
}

//分享链接
$recommend_url = 'http://'.$config['mobile_domain'].'/qrcode.php?ticket='.base64_encode($member_info['wx_openid']);
if(isset($_SESSION['account'])) {
    $recommend_url .= '&ukey='.$member_info['id'];
}
assign('recommend_url', $recommend_url);

assign('qrcode', $qrcode);

$smarty->display('qrcode.phtml');