<?php
/**
 * 移动端初始化程序
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';
define('MODULE_PATH', ROOT_PATH.'mobile');

$class_list = array('Smarty');
$loader->includeClass($class_list);

$script_list = array();
$loader->includeScript($script_list);

//初始化Smarty
global $smarty;
$smarty = new Smarty();
$smarty->setTemplateDir('themes');
$smarty->setCompileDir('data/compile');
$smarty->setCacheDir('data/cache');

//设置模板路径
assign('template_dir', 'themes/');

$config = array();
$config['site_name'] = '基于API的业务系统';
assign('config', $config);

$active_script = str_replace(MODULE_PATH.'/', '', $_SERVER['SCRIPT_FILENAME']);
assign('active_script', $active_script);
