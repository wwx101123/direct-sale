<?php
/**
 * API的预加载页面
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';

$script_list = array('member');
$loader->includeScript($script_list);

$class_list = array();
$loader->includeClass($class_list);
