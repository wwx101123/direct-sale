<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'reward_transfer';
$opera = check_action($operation, getPOST('opera'));

if('reward_transfer' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');

    $password = getPOST('password');
    $remark = getPOST('remark');
    $reward = floatval(getPOST('reward'));
    $target = getPOST('target');

    if(empty($password))
    {
        $response['msg'] .= "-请填写超级密码\n";
    } else {
        $password = $db->escape($password);
        if(!verify_super_password($_SESSION['account'], $password))
        {
            $response['msg'] .= "-超级密码错误\n";
        }
    }

    if($reward <= 0)
    {
        $response['msg'] .= "-请填写转出金额\n";
    } else {
        if($member_info['reward'] < $reward)
        {
            $response['msg'] .= "-奖金余额不足\n";
        }
    }

    if(empty($target))
    {
        $response['msg'] .= "-请填写转入账号\n";
    } else {
        $target = $db->escape($target);
        $field = 'account';

        if(is_mobile($target))
        {
            $field = 'mobile';
        }

        if(is_email($target))
        {
            $field = 'email';
        }

        $check_target = 'select `account` from '.$db->table('member').' where `'.$field.'`=\''.$target.'\'';
        if(!$target = $db->fetchOne($check_target))
        {
            $response['msg'] .= "-转入账号不存在\n";
        }
    }

    $remark = $db->escape($remark);

    if($response['msg'] == '')
    {
        $db->begin();

        if(member_account_change($_SESSION['account'], 0, -1*$reward, 0,0,0,0, $_SESSION['account'], 5, $remark))
        {
            if(member_account_change($target, 0, $reward, 0, 0, 0, 0, $_SESSION['account'], 5, $remark))
            {
                $db->commit();
                $response['error'] = 0;
                $response['msg'] = '奖金转出成功';
            } else {
                $db->rollback();
                $response['msg'] = '奖金转出失败，请稍后再试';
            }
        } else {
            $db->rollback();
            $response['msg'] = '奖金转出失败，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

$smarty->display('reward_transfer.phtml');
