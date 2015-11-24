<?php
/**
 * 初始化程序
 * @author winsen
 * @version 1.0.0
 * @date 2015-01-09
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

//设置系统相关参数
date_default_timezone_set('Asia/Shanghai');
define('ROOT_PATH', str_replace('library/bootstrap.inc.php', '',str_replace('\\', '/', __FILE__)));
define('BASE_DIR', str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_PATH));

if(!class_exists('AutoLoader'))
{
    include('AutoLoader.class.php');
}

$loader = AutoLoader::getInstance();
$configs = array('script_path'=>ROOT_PATH.'library/', 'class_path'=>ROOT_PATH.'library/');
$loader->setConfigs($configs);

$class_list = array('Logs', 'MySQL');
$loader->includeClass($class_list);
$script_list = array('configs','functions','lang');
$loader->includeScript($script_list);

$debug_mode = true;//开启此项将关闭Smarty缓存模式，并开启日志跟踪
//初始化日志对象
global $log;
$log_file = date('Ymd').'.log';
$log = new Logs($debug_mode, $log_file);

//注册错误处理机制
register_shutdown_function('shutdown_handler');
set_error_handler('error_handler', E_ALL);

//初始化数据库链接
global $db;
$db = new MySQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DBNAME, DB_PREFIX);

//读取网站设置
$get_sysconf = 'select `key`,`value` from '.$db->table('sysconf');
global $config;
$config_tmp = $db->fetchAll($get_sysconf);
if($config_tmp)
{
    foreach($config_tmp as $tmp)
    {
        $config[$tmp['key']] = $tmp['value'];
    }
}
