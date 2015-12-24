<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/24
 * Time: 下午3:32
 */
include 'library/init.inc.php';

$log->record_array($_POST);

$response = array('error'=>1, 'msg'=>'');

$access_token = get_access_token($config['appid'], $config['appsecret']);
$openid = getPOST('openid');
$openid = $db->escape($openid);

if($access_token)
{
    if ($ticket = get_qrcode($openid, $access_token))
    {
        $response['url'] = 'http://wechat.wzcy188.com/facm/api/recommend.php?ticket='.urlencode($openid);
        $response['error'] = 0;
    } else {
        $response['msg'] = '服务器繁忙，请稍后再次获取';
    }
} else {
    $response['msg'] = '获取access_token失败';
}

echo json_encode($response);
exit;
