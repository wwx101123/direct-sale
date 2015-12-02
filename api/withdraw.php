<?php
/**
 * 提现管理API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';
$operation = 'add|delete|cancel';
$opera = check_action($operation, getPOST('opera'));

$response = array('errno'=>1, 'errmsg'=>'', 'errcontent'=>array());

if('cancel' == $opera)
{
    $response = array('errno'=>0, 'errmsg'=>'');

    $withdraw_sn = getPOST('withdraw_sn');

    if($withdraw_sn == '')
    {
        $response['errmsg'] = '000:参数错误';
    } else {
        $withdraw_sn = $db->escape($withdraw_sn);
    }

    if($response['errmsg'] == '')
    {
        $db->begin();
        $check_withdraw = 'select `withdraw_sn` from '.$db->table('withdraw').' where `account`=\''.$_SESSION['account'].'\' and '.
            ' `withdraw_sn`=\''.$withdraw_sn.'\' and `status`=0 for update;';

        if($db->fetchOne($check_withdraw))
        {
            $db->autoDelete('withdraw', '`withdraw_sn`=\''.$withdraw_sn.'\'');
            $response['errno'] = 0;
            $response['errmsg'] = '取消申请成功';
        } else {
            $response['errmsg'] = '该申请已处理或不存在';
        }

        $db->commit();
    }
}

if('add' == $opera)
{
    $get_user_info = 'select `balance`,`reward`,`password` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';

    $user_info = $db->fetchRow($get_user_info);

    //获取未提现的金额
    $get_withdraw_await = 'select sum(`amount`+`fee`) from '.$db->table('withdraw').' where `account`=\''.$_SESSION['account'].'\'';
    $withdraw_await = $db->fetchOne($get_withdraw_await);

    $withdraw_await = $user_info['balance'] + $user_info['reward'] - $withdraw_await;

    if(!check_cross_domain())
    {
        $bank_id = intval(getPOST('bank_id'));
        $amount = floatval(getPOST('amount'));
        $password = getPOST('password');
        $bank_name = getPOST('bank_name');
        $bank_account = getPOST('bank_account');
        $bank_card = getPOST('bank_card');

//        if($bank_id <= 0)
//        {
//            $response['errmsg'] .= '-请选择银行卡<br/>';
//        } else {
//            $check_bank_info = 'select `account` from '.$db->table('bank_card').' where `id`='.$bank_id;
//
//            $card_account = $db->fetchOne($check_bank_info);
//
//            if($card_account != $_SESSION['account'])
//            {
//                $response['errmsg'] .= '-参数错误<br/>';
//            }
//        }

        if($amount <= 0)
        {
            $response['errmsg'] .= '-请填写提现金额<br/>';
        } else {
            $total_amount = $amount +$config['fee_rate'] * $amount;

            if($withdraw_await < $total_amount)
            {
                $response['errmsg'] .= '-可提现金额不足<br/>';
            }
        }

        if($password == '')
        {
            $response['errmsg'] .= '-请填写超级密码<br/>';
        } else {
            if(!verify_super_password($_SESSION['account'], $password))
            {
                $response['errmsg'] .= '-超级密码错误<br/>';
            }
        }

        if($bank_card == '')
        {
            $response['errmsg'] .= "-请输入银行卡号\n";
        } else {
            $bank_card = $db->escape($bank_card);
        }

        if($bank_account == '')
        {
            $response['errmsg'] .= "-请输入开户人\n";
        } else {
            $bank_account = $db->escape($bank_account);
        }

        if($bank_name == '')
        {
            $response['errmsg'] .= "-请输入开户银行\n";
        } else {
            $bank_name = $db->escape($bank_name);
        }

        if($response['errmsg'] == '')
        {
            $bank_data = array(
                'account' => $_SESSION['account'],
                'bank_name' => $bank_name,
                'bank_card' => $bank_card,
                'bank_account' => $bank_account
            );

            $db->autoInsert('bank', array($bank_data));
            $bank_id = $db->get_last_id();

            if($withdraw_sn = add_withdraw($_SESSION['account'], $amount, $bank_id))
            {
                $response['errno'] = 0;
                $response['errmsg'] = '提现申请提交成功';
                $response['withdraw_sn'] = $withdraw_sn;
            } else {
                $response['errmsg'] = '001:系统繁忙，请稍后再试';
            }
        }
    } else {
        $response['errmsg'] = '404:参数错误';
    }
}

echo json_encode($response);
exit;
