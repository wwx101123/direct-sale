<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/21
 * Time: 上午11:07
 */
global $plugins;

$i = isset($plugins) ? count($plugins) : 0;
$plugins[$i]['name'] = '微信支付';//插件名称
$plugins[$i]['desc'] = '使用微信支付接口进行支付';//插件描述
$plugins[$i]['plugins'] = 'Wechat';
$plugins[$i]['configure'] = array();
$plugins[$i]['configure'] = array(
    array('name'=>'商户号', 'key'=>'mch_id'),
    array('name'=>'商户API密钥', 'key'=>'mch_key'),
    array('name'=>'appid', 'key'=>'appid')
);

class Wechat
{

}