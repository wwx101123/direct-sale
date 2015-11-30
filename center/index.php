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

    $checkAccount = 'select `password`,`role_id`,`name` from `'.DB_PREFIX.'admin` where `account`=\''.$account.'\' limit 1';
    $admin = $db->fetchRow($checkAccount);

    if($admin)
    {
        if($password == $admin['password'])
        {
            $getRole = 'select `purview` from `'.DB_PREFIX.'role` where `id`='.$admin['role_id'].' limit 1';
            if($role = $db->fetchRow($getRole))
            {
                $_SESSION['purview'] = $role['purview'];
                $_SESSION['name'] = $admin['name'];
                $_SESSION['account'] = $account;
                $_SESSION['card_no'] = $account;
                $_SESSION['token'] = $account;
                /**
                 * 是否有微信管理权限
                 */
                if( check_purview('pur_wechat_manager', $role['purview']) ) {
                    $getPublicAccount = 'select `account` from `oshop`.`wx_publicAccount` where adminUserAccount = \''.$account.'\' limit 1;';
                    $publicAccount = $db->fetchOne($getPublicAccount);
                    if( $publicAccount ) {
                        $_SESSION['public_account'] = $publicAccount;
                    }
                }

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
    unset($_SESSION['account']);

    redirect('index.php');
}

assign('error', $error);

assign('pageTitle', $config['site_name']);
$template .= $act.'.phtml';
$smarty->display($template);

