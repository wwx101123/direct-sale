<?php
/**
 * 物流方式设置
 * @author 王仁欢
 * @email wrh4285@163.com
 * @date 2015-08-19
 * @version 1.0.0
 */

include 'library/init.inc.php';

global $plugins;

$plugins = array();
//商户管理后台初始化
back_base_init();
$template = 'payment/';

$action = 'view|edit|delete|install|add|uninstall|delivery_area_set|delivery_area|delivery_area_edit|delivery_area_delete';
$operation = 'edit|area_add|area_edit';
$act = check_action($action, getGET('act'));
$opera = check_action($operation, getPOST('opera'));
$act = ( $act == '' ) ? 'view' : $act;

$delivery_status = array(
    -1 => '未安装',
    0  => '停用',
    1  => '启用'
);
//===============================================================================
if('edit' == $opera)
{
    $payment_id = intval(getPOST('eid'));
    $configure = getPOST('configure');


    if($payment_id <= 0)
    {
        show_system_message('参数错误');
    }

    if(is_array($configure))
    {
        foreach($configure as $key=>$value)
        {
            $configure[$key] = $db->escape($value);
        }

    } else {
        $configure = $db->escape($configure);
    }
    $configure = serialize($configure);

    $payment_data = array(
        'configure' => $configure
    );

    $db->autoUpdate('payment', $payment_data, '`id`='.$payment_id);

    show_system_message('设置参数成功');
}
//===============================================================================
if('install' == $act)
{
    $plugin = getGET('plugin');

    if($plugin == '')
    {
        show_system_message('参数错误');
    }

    $plugin = $db->escape($plugin);
    $plugin_path = ROOT_PATH.'/center/plugins/payment/';

    include $plugin_path.$plugin.'.class.php';

    unset($plugins[0]['configure']);
    $delivery_data = $plugins[0];

    $delivery_data['status'] = 1;

    if($db->autoInsert('payment', array($delivery_data)))
    {
        $delivery_id = $db->get_last_id();
        $links = array(
            array('alt'=>'设置参数', 'link'=>'payment.php?act=editt&payment_id='.$delivery_id)
        );
        show_system_message('插件安装成功，请设置参数', $links);
    } else {
        show_system_message('系统繁忙，请稍后再试');
    }
    exit;
}

if('uninstall' == $act)
{
    $plugin = getGET('plugin');

    if($plugin == '')
    {
        show_system_message('参数错误');
    }

    $plugin = $db->escape($plugin);

    //读取物流方式信息
    $get_delivery_id = 'select `id` from '.$db->table('payment').
                       ' where `plugins`=\''.$plugin.'\'';
    $delivery_id = $db->fetchOne($get_delivery_id);

    if($delivery_id)
    {
        if($db->autoDelete('payment', '`id`='.$delivery_id))
        {
            show_system_message('卸载支付插件成功');
        } else {
            show_system_message('系统繁忙，请稍后再试');
        }
    } else {
        show_system_message('插件已删除或不存在');
    }
    exit;
}

if('edit' == $act)
{
    $payment_id = intval(getGET('payment_id'));
    if($payment_id <= 0)
    {
        show_system_message('参数错误');
    }

    $get_payment_plugins = 'select * from '.$db->table('payment').' where `id`='.$payment_id;
    $payment_plugins = $db->fetchRow($get_payment_plugins);

    if($payment_plugins['configure'])
    {
        $payment_plugins['configure'] = unserialize($payment_plugins['configure']);
    }

    $plugin_path = ROOT_PATH.'/center/plugins/payment/';

    include $plugin_path.$payment_plugins['plugins'].'.class.php';
    $payment_plugins['configures'] = $plugins[0]['configure'];

    assign('payment_plugins', $payment_plugins);
}

if('view' == $act)
{
    $plugin_path = ROOT_PATH.'/center/plugins/payment/';

    $dir = dir($plugin_path);

    $pattern = '/^[a-zA-Z]{1}[a-zA-Z0-9].*?\.class\.php$/';
    $files = array();
    while($file = $dir->read())
    {
        if(preg_match($pattern, $file))
        {
            $files[] = $file;
        }
    }

    foreach($files as $file)
    {
        include $plugin_path.$file;
    }

    $express_list = array();

    foreach($plugins as $plugin)
    {
        //检查该插件是否已经安装
        $check_plugin_status = 'select `id`,`name`,`status`,`desc`,`plugins` from '.$db->table('payment').
                               ' where `plugins`=\''.$plugin['plugins'].'\'';

        $delivery_plugin = $db->fetchRow($check_plugin_status);
        if($delivery_plugin)
        {
            $delivery_plugin['show_status'] = $delivery_status[$delivery_plugin['status']];
            $express_list[] = $delivery_plugin;
        } else {
            $plugin['id'] = 0;
            $plugin['status'] = -1;
            $plugin['show_status'] = $delivery_status[-1];
            $express_list[] = $plugin;
        }
    }

    assign('express_list', $express_list);
}

$template .= $act.'.phtml';
$smarty->display($template);