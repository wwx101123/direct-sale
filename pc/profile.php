<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$action = 'info|password|super_password';
$act = check_action($action, getGET('act'), 'info');

if('super_password' == $act)
{
    assign('sub_title', '超级密码修改');
}

if('password' == $act)
{
    assign('sub_title', '密码修改');
}

if('info' == $act)
{
    assign('sub_title', '信息修改');
}

assign('act', $act);
$smarty->display('profile.phtml');
