<?php
/**
 * 会员API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'login|detail|register|modify|list';
$opera = check_action($operation, getPOST('opera'));

$response = array('errno' => 1, 'errmsg' => '', 'errcontent' => array());

//会员登录
if('login' == $opera)
{
    $account = trim(getPOST('account'));
    $password = trim(getPOST('password'));

    if(empty($account))
    {
        $response['errcontent']['account'] = '请填写会员卡号/手机号码/邮箱';
    } else {
        $account = $db->escape($account);
    }

    if(empty($password))
    {
        $response['errcontent']['password'] = '请填写登录密码';
    } else {
        $password = md5($password.PASSWORD_END);
    }

    if(count($response['errcontent']) == 0 && $response['errmsg'] == '')
    {
        $field = 'account';
        if(is_mobile($account))
        {
            $field = 'mobile';
        } else if(is_email($account)) {
            $field = 'email';
        }

        $get_member = 'select `account`,`password` from '.$db->table('member').' where `'.$field.'`=\''.$account.'\'';
        $member = $db->fetchRow($get_member);

        if($member)
        {
            if($member['password'] == $password)
            {
                $token = '';
                do {
                    $token = md5($member['account'].time());
                    $check_token = 'select `account` from '.$db->table('member_login_logs').' where `token`=\''.$token.'\'';
                } while($db->fetchOne($check_token));

                $member_login_log = array(
                    'account' => $member['account'],
                    'add_time' => time(),
                    'token' => $token
                );

                if($db->autoInsert('member_login_logs', array($member_login_log)))
                {
                    $response['token'] = $token;
                } else {
                    $response['errmsg'] = '系统繁忙，请稍后再试';
                }
            } else {
                $response['errcontent']['password'] = '登录密码错误';
            }
        } else {
            $response['errcode'] = 1;
            $response['errmsg'] = '会员不存在';
        }
    }
}

//会员信息
if('detail' == $opera)
{
}

//会员注册
if('register' == $opera)
{
}

//会员信息修改
if('modify' == $opera)
{
}

//会员列表
if('list' == $opera)
{
}

echo json_encode($response);
exit;
