<?php
/*
 * 系统全局配置文件
 * @author winsen
 * @date 2015-01-09
 * @version 1.0.0
 */
global $charset;
$charset = 'utf8'; //数据库采用编码

//数据库配置
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DBNAME', 'directsale');
define('DB_PREFIX', 'ds_');
define('DB_CHARSET', 'utf8mb4');

define('PASSWORD_END', '_kwanson');
