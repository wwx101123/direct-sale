<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/21
 * Time: 上午11:07
 */
global $plugins;

$i = isset($plugins) ? count($plugins) : 0;
$plugins[$i]['name'] = '银行转账';//插件名称
$plugins[$i]['desc'] = '通过线下转账到指定的银行卡号';//插件描述
$plugins[$i]['plugins'] = 'Bank';
$plugins[$i]['configure'] = array(
    array('name'=>'开户银行', 'key'=>'bank_name'),
    array('name'=>'银行卡号', 'key'=>'bank_card'),
    array('name'=>'开户人', 'key'=>'bank_account')
);