<?php
/**
 * 移动端初始化程序
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';
define('MODULE_PATH', ROOT_PATH.'pc');

$class_list = array('Smarty', 'WechatResponse', 'Code');
$loader->includeClass($class_list);

$script_list = array('wechat');
$loader->includeScript($script_list);

//初始化Smarty
global $smarty;
$smarty = new Smarty();
$smarty->setTemplateDir('themes');
$smarty->setCompileDir('data/compile');
$smarty->setCacheDir('data/cache');

//设置模板路径
assign('template_dir', 'themes/');

$active_script = str_replace(MODULE_PATH.'/', '', $_SERVER['SCRIPT_FILENAME']);
assign('active_script', $active_script);

//设置语言包
assign('lang', $lang);
//设置网站参数
assign('config', $config);

//读取ukey参数，记录推荐人信息
$ukey = getGET('ukey');
if($ukey != '')
{
    $ukey = intval($ukey);

    if($ukey > 0)
    {
        $get_member_id = 'select `id` from '.$db->table('member').' where `id`='.$ukey;

        if($member_id = $db->fetchOne($get_member_id))
        {
            $_SESSION['parent_id'] = $member_id;
        }
    }
}

if(!check_member_login())
{
    $no_login_script = 'index.php|register.php|code.php';
    $script_name = str_replace(MODULE_PATH.'/', '', $_SERVER['SCRIPT_FILENAME']);

    $flag = check_action($no_login_script, $script_name);
    if($flag == '')
    {
        redirect('index.php');
        exit;
    }
} else {
    $get_member_info = 'select * from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
    $member_info = $db->fetchRow($get_member_info);

    assign('member_info', $member_info);
}