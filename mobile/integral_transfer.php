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

if('transfer' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');
    $integral = floatval(getPOST('integral'));
    $account = getPOST('account');
    $password = getPOST('password');
    $remark = getPOST('remark');

    if($integral <= 0)
    {
        $response['msg'] .= "-转出积分必须大于0\n";
    } else {
        if($integral > $user_info['integral'])
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

    if($account == '')
    {
        $response['msg'] .= "-请填写转入账号\n";
    } else {
        $account = $db->escape($account);
    }

    $remark = $db->escape($remark);

    if($response['msg'] == '')
    {
        $check_target = 'select `account` from '.$db->table('user').' where `account`=\''.$account.'\'';

        $target = $db->fetchOne($check_target);

        if($target)
        {
            if(integral_transfer($_SESSION['account'], $account, $integral, $remark))
            {
                $response['msg'] = '积分互换成功';
                $response['error'] = 0;
            } else {
                $response['msg'] = '系统繁忙，请稍后再试';
            }
        } else {
            $response['msg'] = '转入账号不存在';
        }

    }

    echo json_encode($response);
    exit;
}

assign('user_info', $user_info);
$smarty->display('integral_transfer.phtml');