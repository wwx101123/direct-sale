<?php
/**
 * 银行卡管理API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'add|edit|delete';

$opera = check_action($operation, getPOST('opera'));

$response = array('errno'=>1, 'errmsg'=>'', 'errcontent'=>array());

if('delete' == $opera)
{
    $id = getPOST('eid');

    $id = intval($id);
    if($id <= 0)
    {
        $response['errmsg'] = '-参数错误<br/>';
    }

    if($response['errmsg'] == '')
    {
        if($db->autoDelete('bank', '`id`='.$id.' and `account`=\''.$_SESSION['account'].'\''))
        {
            $response['errno'] = 0;
            $response['errmsg'] = '删除银行卡成功';
        } else {
            $response['errmsg'] = '001:系统繁忙，请稍后再试';
        }
    }
}

if('edit' == $opera)
{
    $bank = getPOST('bank_name');
    $bank_account = getPOST('bank_account');
    $bank_card = getPOST('bank_card');
    $password = getPOST('password');
    $id = getPOST('eid');

    $id = intval($id);
    if($id <= 0)
    {
        $response['errmsg'] = '-参数错误<br/>';
    }

    if($bank == '')
    {
        $response['errmsg'] .= '-请填写开户银行<br/>';
    } else {
        $bank = $db->escape($bank);
    }

    if($bank_account == '')
    {
        $response['errmsg'] .= '-请填写开户人姓名<br/>';
    } else {
        $bank_account = $db->escape($bank_account);
    }

    if($bank_card == '')
    {
        $response['errmsg'] .= '-请填写银行卡号<br/>';
    } else {
        $bank_card = $db->escape($bank_card);
    }

    if($password == '')
    {
        $response['errmsg'] .= '-请填写账户密码<br/>';
    } else {
        if (!verify_password($_SESSION['account'], $password))
        {
            $response['errmsg'] .= '-账户密码错误<br/>';
        }
    }

    if($response['errmsg'] == '')
    {
        $bank_card_data = array(
            'account' => $_SESSION['account'],
            'bank_name' => $bank,
            'bank_card' => $bank_card,
            'bank_account' => $bank_account
        );

        if($db->autoUpdate('bank', $bank_card_data, '`id`='.$id.' and `account`=\''.$_SESSION['account'].'\''))
        {
            $response['errno'] = 0;
            $response['errmsg'] = '修改银行卡成功';
        } else {
            $response['errmsg'] = '001:系统繁忙，请稍后再试';
        }
    }
}

if('add' == $opera)
{
    $bank = getPOST('bank_name');
    $bank_account = getPOST('bank_account');
    $bank_card = getPOST('bank_card');
    $password = getPOST('password');

    if($bank == '')
    {
        $response['errmsg'] .= '-请填写开户银行<br/>';
    } else {
        $bank = $db->escape($bank);
    }

    if($bank_account == '')
    {
        $response['errmsg'] .= '-请填写开户人姓名<br/>';
    } else {
        $bank_account = $db->escape($bank_account);
    }

    if($bank_card == '')
    {
        $response['errmsg'] .= '-请填写银行卡号<br/>';
    } else {
        $bank_card = $db->escape($bank_card);
    }

    if($password == '')
    {
        $response['errmsg'] .= '-请填写账户密码<br/>';
    } else {
        if (!verify_password($_SESSION['account'], $password))
        {
            $response['errmsg'] .= '-账户密码错误<br/>';
        }
    }

    if($response['errmsg'] == '')
    {
        $bank_card_data = array(
            'account' => $_SESSION['account'],
            'bank_name' => $bank,
            'bank_card' => $bank_card,
            'bank_account' => $bank_account
        );

        if($db->autoInsert('bank_card', array($bank_card_data)))
        {
            $response['errno'] = 0;
            $response['errmsg'] = '添加银行卡成功';
        } else {
            $response['errmsg'] = '001:系统繁忙，请稍后再试';
        }
    }
}

echo json_encode($response);
exit;