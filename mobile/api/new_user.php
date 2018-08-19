<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/24
 * Time: 下午2:30
 */
include 'library/init.inc.php';

$log->record_array($_POST);
$response = array('error'=>1, 'msg'=>'');
$openid = getPOST('openid');
$scene_id = trim(getPOST('scene_id'));

$parent_id = 0;
$account = '';
if(!empty($scene_id))
{
    $get_user = 'select `id`,`account` from ' . $db->table('member') . ' where `scene_id`=' . $scene_id;
    $log->record($get_user);

    $user = $db->fetchRow($get_user);

    if($user)
    {
        $log->record_array($user);
        $parent_id = $user['id'];
        $account = $user['account'];
    }
}

if(register_openid($openid, '', $parent_id))
{
    $access_token = get_access_token($config['appid'], $config['appsecret']);
    if(get_user_wechat_info($access_token, $openid))
    {
        $log->record('update user info success');
    }
    $log->record('async user list, add new user with: openid='.$openid.',scene_id='.$scene_id);
    //发放推广奖励
    if($account != '' && isset($config['recommend_integral']) && $config['recommend_integral'] > 0)
    {
        /*
        if(add_recommend_integral($account, $config['recommend_integral'], '推荐新用户奖励'))
        {
            $log->record('add recommend integral success:'.$account.' increment '.$config['recommend_integral']);
        } else {
            $log->record('add recommend integral fail:'.$account.' increment '.$config['recommend_integral']);
        }
        */
    } else {
        $log->record('account is empty');
    }
} else {
    $log->record('async user list fail. openid='.$openid.',scene_id='.$scene_id);
}

echo json_encode($response);
exit;
