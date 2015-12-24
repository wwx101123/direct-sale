<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/21
 * Time: 下午12:30
 */
include 'library/init.inc.php';

$operation = 'set';
$opera = check_action($operation, getPOST('opera'));

if('set' == $opera)
{
    $response = array('error' => 1, 'msg' => '');

    $password = getPOST('password');

    if($password == '')
    {
        $response['msg'] .= "-请输入账户密码\n";
    }

    //检查如果没有设置密码先设置密码
    $get_password = 'select `password` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';

    $user_password = $db->fetchOne($get_password);

    if($user_password != '')
    {
        $response['msg'] = '您已设置过账户密码';
    }

    if($response['msg'] == '')
    {
        $data = array('password'=>md5($password.PASSWORD_END));

        if($db->autoUpdate('member', $data, '`account`=\''.$_SESSION['account'].'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '设置密码成功，请牢记您的密码';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

$get_mobile = 'select `mobile` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$mobile = $db->fetchOne($get_mobile);
assign('mobile', $mobile);

$smarty->display('password.phtml');