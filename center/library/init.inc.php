<?php
/**
 * 管理后台初始化程序
 * @author 王仁欢
 * @version 1.0.0
 * @date 2015-01-09
 */

//设置系统相关参数
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
date_default_timezone_set('Asia/Shanghai');
define('ROOT_PATH', str_replace('center/library/init.inc.php', '',str_replace('\\', '/', __FILE__)));
if(!class_exists('AutoLoader'))
{
    include(ROOT_PATH.'library/AutoLoader.class.php');
}

$loader = AutoLoader::getInstance();
$configs = array('script_path'=>ROOT_PATH.'library/', 'class_path'=>ROOT_PATH.'library/');
$loader->setConfigs($configs);

$class_list = array('Smarty', 'Logs', 'MySQL', 'Code', 'PHPExcel');
$loader->includeClass($class_list);
$script_list = array('configs','functions','lang', 'purview', 'member', 'account');
$loader->includeScript($script_list);
//初始化数据库链接
global $db;
$db = new MySQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DBNAME, DB_PREFIX);

$debug_mode = true;//开启此项将关闭Smarty缓存模式，并开启日志跟踪
//初始化日志对象
global $log;
$log_file = date('Ymd').'.log';
$log = new Logs($debug_mode, $log_file);
//读取网站设置
$get_sysconf = 'select `key`,`value` from '.$db->table('sysconf');
global $config;
$config_tmp = $db->fetchAll($get_sysconf);
foreach($config_tmp as $tmp)
{
    $config[$tmp['key']] = $tmp['value'];
}

//初始化smarty对象
global $smarty;
$smarty = new Smarty();
$smarty->setCompileDir(ROOT_PATH.'center/data/compiles');
$smarty->setTemplateDir(ROOT_PATH.'center/themes/');
$smarty->setCacheDir(ROOT_PATH.'center/data/caches');
$smarty->setCacheLifetime(1800);//设置缓存文件超时时间为1800秒

//Debug模式下每次都强制编译输出
if($debug_mode)
{
    //$smarty->clearAllCache();
    //$smarty->clearCompiledTemplate();
    $smarty->force_compile = true;
}

//设置系统设置
assign('config', $config);
//设置语言包
assign('lang', $lang);
//设置网站参数
assign('config', $config);
//设置模板路径
assign('template_dir', 'themes/'.$config['themes'].'/');