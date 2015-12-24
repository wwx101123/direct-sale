<?php
/**
 * 管理员角色
 * @author 王仁欢
 * @email wrh4285@163.com
 * @date 2015-08-07
 * @version 1.0.0
 */

include 'library/init.inc.php';
back_base_init();

$template = 'role/';
assign('subTitle', '银行卡设置');

$action = 'edit|add|view|delete';
$operation = 'edit|add';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;
$opera = check_action($operation, getPOST('opera'));

if('edit' == $opera)
{
    $bank_name = getPOST('bank_name');
    $bank_card = getPOST('bank_card');
    $bank_account = getPOST('bank_account');
    $id = intval(getPOST('eid'));

    if($id <= 0)
    {
        show_system_message('参数错误');
    }

    if($bank_name == '')
    {
        show_system_message('开户银行不能为空');
    } else {
        $bank_name = $db->escape($bank_name);
    }

    if($bank_card == '')
    {
        show_system_message('银行卡号不能为空');
    } else {
        $bank_card = $db->escape($bank_card);
    }

    if($bank_account == '')
    {
        show_system_message('开户人不能为空');
    } else {
        $bank_account = $db->escape($bank_account);
    }

    $bank_data = array(
        'bank_name' => $bank_name,
        'bank_account' => $bank_account,
        'bank_card' => $bank_card
    );

    if($db->autoUpdate('bank_info', $bank_data, '`id`='.$id))
    {
        show_system_message('修改银行卡成功', array(array('link'=>'bank.php', 'alt'=>'银行卡设置')));
    } else {
        show_system_message('系统繁忙，请稍后再试');
    }
}

if('add' == $opera)
{
    $bank_name = getPOST('bank_name');
    $bank_card = getPOST('bank_card');
    $bank_account = getPOST('bank_account');

    if($bank_name == '')
    {
        show_system_message('开户银行不能为空');
    } else {
        $bank_name = $db->escape($bank_name);
    }

    if($bank_card == '')
    {
        show_system_message('银行卡号不能为空');
    } else {
        $bank_card = $db->escape($bank_card);
    }

    if($bank_account == '')
    {
        show_system_message('开户人不能为空');
    } else {
        $bank_account = $db->escape($bank_account);
    }

    $bank_data = array(
        'bank_name' => $bank_name,
        'bank_account' => $bank_account,
        'bank_card' => $bank_card
    );

    if($db->autoInsert('bank_info', array($bank_data)))
    {
        show_system_message('添加银行卡成功', array(array('link'=>'bank.php', 'alt'=>'银行卡设置')));
    } else {
        show_system_message('系统繁忙，请稍后再试');
    }
}

if('delete' == $act)
{
    $id = intval(getGET('id'));

    if($db->autoDelete('bank_info', '`id`='.$id))
    {
        show_system_message('删除银行卡成功');
    } else {
        show_system_message('系统繁忙，请稍后再试');
    }
}

if('edit' == $act)
{
    $id = intval(getGET('id'));

    $get_bank = 'select * from '.$db->table('bank_info').' where `id`='.$id;
    $bank = $db->fetchRow($get_bank);
    assign('bank', $bank);
}

if('view' == $act)
{
    $get_bank_list = 'select * from '.$db->table('bank_info');
    $bank_list = $db->fetchAll($get_bank_list);

    assign('bank_list', $bank_list);
}
assign('act', $act);
$smarty->display('bank/bank.phtml');