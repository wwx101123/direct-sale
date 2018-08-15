<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/21
 * Time: 上午11:39
 */
include 'library/init.inc.php';

$get_user_info = 'select * from '.$db->table('user').' where `account`=\''.$_SESSION['account'].'\'';
$user_info = $db->fetchRow($get_user_info);

$operation = 'change_pwd';
$opera = check_action($operation, getPOST('opera'));

if('change_pwd' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');

    $password = getPOST('password');
    $new_password = getPOST('new_password');

    if($password == '')
    {
        $response['msg'] .= "-请输入原密码\n";
    }

    if($new_password == '')
    {
        $response['msg'] .= "-请输入新密码\n";
    }

    if($response['msg'] == '')
    {
        $password = md5($password.PASSWORD_END);
        $new_password = md5($new_password.PASSWORD_END);

        if($password == $user_info['password'])
        {
            $data = array('password'=>$new_password);

            if($db->autoUpdate('user', $data, '`account`=\''.$_SESSION['account'].'\''))
            {
                $response['msg'] = '修改密码成功，请牢记您的新密码';
                $response['error'] = 0;
            } else {
                $response['msg'] = '系统繁忙，请稍后再试';
            }
        } else {
            $response['msg'] = '原密码错误';
        }
    }
    echo json_encode($response);
    exit;
}

assign('user_info', $user_info);
$smarty->display('info.phtml');