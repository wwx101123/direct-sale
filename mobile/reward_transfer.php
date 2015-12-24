<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/21
 * Time: 上午10:29
 */
include 'library/init.inc.php';

$operation = 'transfer';
$opera = check_action($operation, getPOST('opera'));

$get_user_info = 'select * from '.$db->table('user').' where `openid`=\''.$_SESSION['openid'].'\'';
$user_info = $db->fetchRow($get_user_info);

$get_recharge_amount = 'select sum(amount+fee) from '.$db->table('withdraw').' where `status`<2 and `account`=\''.$_SESSION['account'].'\'';
$recharge_amount = $db->fetchOne($get_recharge_amount);

$user_info['reward'] -= $recharge_amount;

if('transfer' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');
    $reward = floatval(getPOST('reward'));
    $password = getPOST('password');
    $remark = getPOST('remark');

    if($reward <= 0)
    {
        $response['msg'] .= "-转换奖励必须大于0\n";
    } else {
        if($reward > $user_info['reward'])
        {
            $response['msg'] .= "-积分不足\n";
        }
    }

    if($password == '')
    {
        $response['msg'] .= "-请输入账户密码\n";
    } else {
        $password = md5($password.PASSWORD_END);
        if($password != $user_info['password'])
        {
            $response['msg'] .= "-账户密码错误\n";
        }
    }

    $remark = $db->escape($remark);

    if($response['msg'] == '')
    {
        if(reward_exchange($_SESSION['account'], $reward, $remark))
        {
            $response['msg'] = '奖励转换成功';
            $response['error'] = 0;
        } else {
            $response['msg'] = '转入账号不存在';
        }

    }

    echo json_encode($response);
    exit;
}

assign('user_info', $user_info);
$smarty->display('reward_transfer.phtml');