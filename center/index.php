<?php
/**
 * 管理后台登陆页
 * @author 王仁欢
 * @email wrh4285@163.com
 * @date 2015-8-5
 * @version 1.0.0
 */

include 'library/init.inc.php';
//global $purview;
//var_dump(json_encode($purview));exit;


$template = 'index/';

$action = 'login|forget|logout';
$operation = 'login|forget';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'login' : $act;

$opera = check_action($operation, getPOST('opera'));

$error = array();

//登陆
if( 'login' == $opera ) {
    $account = trim(getPOST('account'));
    $password = trim(getPOST('password'));
    $code = getPOST('code');

    if($code != $_SESSION['code'])
    {
        $error['code'] = '验证码错误';
    }

    if('' == $account)
    {
        $error['account'] = '账号不能为空';
    } else {
        $account = $db->escape(htmlspecialchars($account));
    }

    if('' == $password)
    {
        $error['password'] = '密码不能为空';
    } else {
        $password = md5($password.PASSWORD_END);
    }

    $checkAccount = 'select `password`,`role_id`,`name` from '.$db->table('admin').' where `account`=\''.$account.'\' limit 1';
    $admin = $db->fetchRow($checkAccount);

    if($admin && count($error) == 0)
    {
        if($password == $admin['password'])
        {
            $getRole = 'select `purview` from '.$db->table('role').' where `id`='.$admin['role_id'].' limit 1';
            if($role = $db->fetchRow($getRole))
            {
                $_SESSION['purview'] = $role['purview'];
                $_SESSION['name'] = $admin['name'];
                $_SESSION['admin_account'] = $account;
                $_SESSION['card_no'] = $account;
                $_SESSION['token'] = $account;

                show_system_message('登录成功', array(array('alt'=>'进入管理后台', 'link'=>'main.php')));
                exit;
            } else {
                $error['account'] = '账号错误';
            }
        } else {
            $error['password'] = '密码错误';
        }
    } else {
        $error['account'] = '账号不存在';
    }
}

//忘记密码
if( 'forget' == $opera ) {

}

//登陆，默认
if( 'login' == $act ) {
    //如果已登陆
    if( check_admin_login() ) {
        redirect('main.php');
    }
}
//忘记密码
if( 'forget' == $act ) {
    //如果已登陆
    if( check_admin_login() ) {
        redirect('self.php');
    }
}

//注销
if( 'logout' == $act ) {
    unset($_SESSION['purview']);
    unset($_SESSION['name']);
    unset($_SESSION['admin_account']);

    redirect('index.php');
}

assign('error', $error);

assign('pageTitle', $config['site_name']);
$template .= $act.'.phtml';
$smarty->display($template);

