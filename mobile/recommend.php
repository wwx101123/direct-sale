<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:11
 */
include 'library/init.inc.php';

//获取用户信息
$get_member_info = 'select `account`,`reward`,`reward_await`,`integral`,`integral_await`,`balance`,`level_id`,`level_expired`,'.
    '`add_time`,`name`,`wx_openid`,`recommend_path`,`status` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$member_info = $db->fetchRow($get_member_info);
assign('member_info', $member_info);

$level_fee = array(
    2 => floatval($config['join_fee_2']),
    3 => floatval($config['join_fee_3']),
    4 => floatval($config['join_fee_4']),
);

$operation = 'submit_order';
$opera = check_action($operation, getPOST('opera'));

if('submit_order' == $opera)
{
    $response = array('error' => 1, 'msg' => '');

    $use_balance = getPOST('use_balance') == "true" ? 1 : 0;
    $use_reward = getPOST('use_reward') == "true" ? 1 : 0;
    $level_id = intval(getPOST('level_id'));
    $payment_id = intval(getPOST('payment_id'));
    $name = trim(getPOST('name'));
    $mobile = trim(getPOST('mobile'));
    $recommend = trim(getPOST('recommend'));
    $recommend_info = null;

    if($level_id <= 1 || $level_id > 4)
    {
        $response['msg'] .= '-请选择会员等级<br/>';
    }

    if(empty($name))
    {
        $response['msg'] .= '-请填写真实姓名<br/>';
    } else {
        $name = $db->escape($name);
    }

    if(!is_mobile($mobile))
    {
        $response['msg'] .= '-请填写手机号码<br/>';
    } else {
        $mobile = $db->escape($mobile);

        $check_mobile = 'select `mobile` from '.$db->table('member').' where `mobile`=\''.$mobile.'\'';

        $flag = $db->fetchOne($check_mobile);

        if($flag)
        {
            $response['msg'] .= '-手机号码已被使用<br/>';
        }
    }

    //验证推荐人
    if(empty($recommend))
    {
        $response['msg'] .= '-请填写推荐人账号/手机号码<br/>';
    } else {
        $recommend = $db->escape($recommend);

        $field = 'account';
        if(is_mobile($recommend))
        {
            $field = 'mobile';
        }

        if(is_email($recommend))
        {
            $field = 'email';
        }

        $get_recommend_info = 'select `id`,`account`,`recommend_path` from '.$db->table('member').' where `'.$field.'`=\''.$recommend.'\'';
        $log->record($get_recommend_info);
        $recommend_info = $db->fetchRow($get_recommend_info);

        if($recommend_info)
        {
            $search_str = $recommend_info['recommend_path'];
            $needle = $member_info['recommend_path'];

            if(strlen($search_str) < strlen($needle))
            {
                $search_str = $member_info['recommend_path'];
                $needle = $recommend_info['recommend_path'];
            }

            if(strpos($search_str, $needle) === false)
            {
                $response['msg'] .= '-推荐人必须在同一推荐线<br/>';
            }
        } else {
            $response['msg'] .= '-推荐人不存在<br/>';
        }
    }

    if($response['msg'] == '')
    {
        //计算费用
        $total_amount = $level_fee[$level_id];
        $real_amount = $total_amount;
        $reward_paid = 0;
        $balance_paid = 0;

        //优先使用奖金
        $log->record('user reward = '.$use_reward);
        $log->record('user balance = '.$use_balance);
        if($real_amount > 0 && $use_reward)
        {
            $log->record('use reward');
            if($real_amount > $member_info['reward'])
            {
                $reward_paid = $member_info['reward'];
                $real_amount -= $member_info['reward'];
            } else {
                $reward_paid = $total_amount;
                $real_amount = 0;
            }
        }

        //使用余额
        if($real_amount > 0 && $use_balance)
        {
            $log->record('use balance');
            if($real_amount > $member_info['balance'])
            {
                $balance_paid = $member_info['balance'];
                $real_amount -= $member_info['balance'];
            } else {
                $balance_paid = $total_amount;
                $real_amount = 0;
            }
        }

        $db->begin();

        $recommend_info = $db->fetchRow($get_recommend_info);
        $recommend = $recommend_info['account'];
        //注册会员
        $register_data = array(
            'name' => $name,
            'level_id' => $level_id,
            'mobile' => $mobile,
            'recommend' => $recommend,
            'add_time' => time(),
            'account' => get_account(),
            'recommend_id' => $recommend_info['id'],
            'from' => 'wechat',
            'status' => 1
        );

        // 365会员身份将在365天后过期
        if($level_id == 2) {
            $register_data['level_expired'] = time();
        }

        if($db->autoInsert('member', array($register_data)))
        {
            $account = $register_data['account'];
            $register_id = $db->get_last_id();

            if($db->autoUpdate('member', array('recommend_path'=>$recommend_info['recommend_path'].$register_id.','), '`account`=\''.$account.'\''))
            {
                //插入订单
                $pay_order_data = array(
                    'add_time' => time(),
                    'total_amount' => $total_amount,
                    'real_amount' => $real_amount,
                    'integral_amount' => 0,
                    'integral_given_amount' => 0,
                    'item_name' => $lang['level'][$level_id].'注册',
                    'recommend' => $recommend,
                    'recommend_id' => $recommend_info['id'],
                    'payment_id' => 1,
                    'payment_name' => '微信支付',
                    'payment_code' => 'Wechat',
                    'type' => 1,
                    'balance_paid' => $balance_paid,
                    'reward_paid' => $reward_paid,
                    'account' => $account
                );

                $response['status'] = 1;
                if($real_amount == 0)
                {
                    $response['status'] = 3;
                    $pay_order_data['status'] = 3;
                    $pay_order_data['pay_time'] = time();
                }

                $pay_order_sn = '';
                do
                {
                    $pay_order_sn = time().rand(1000, 9999);
                    $check_pay_order_sn = 'select `order_sn` from '.$db->table('pay_order').' where `order_sn`=\''.$pay_order_sn.'\'';
                } while($db->fetchOne($check_pay_order_sn));

                $pay_order_data['order_sn'] = $pay_order_sn;

                if($db->autoInsert('pay_order', array($pay_order_data)))
                {
                    if($balance_paid || $reward_paid)
                    {
                        member_account_change($_SESSION['account'], -1*$balance_paid, -1*$reward_paid, 0, 0, 0, 0, $_SESSION['account'], 2, $pay_order_sn);
                    }
                    //根据订单状态判断是否需要发起支付
                    if($response['status'] == 3)
                    {
                        //解冻会员
                        if($level_id == 2) {
                            $db->autoUpdate('member', array('status' => 2, 'level_expired' => (time() + 365 * 24 * 3600)), '`account`=\'' . $account . '\'');
                        } else {
                            $db->autoUpdate('member', array('status' => 2), '`account`=\'' . $account . '\'');
                        }
                        //结算
                        settle($recommend_info['recommend_path'].$register_id.',', $total_amount, $pay_order_sn);
                    } else {
                        $res = create_prepay($config['appid'], $config['mch_id'], $config['mch_key'], $_SESSION['openid'], $real_amount, $config['site_name'], $pay_order_sn, $pay_order_sn);
                        $res = simplexml_load_string($res);

                        if($res->prepay_id)
                        {
                            $response['error'] = 0;
                        } else {
                            $response['msg'] = $res->return_code.','.$res->return_msg;
                        }

                        $nonce_str = get_nonce_str();
                        $pay_params = array();
                        $pay_params['nonce_str'] = $nonce_str;
                        $time_stamp = time();

                        //最后参与签名的参数有appId, timeStamp, nonceStr, package, signType。
                        $sign = 'appId='.$config['appid'].'&nonceStr='.$nonce_str.'&package=prepay_id='.$res->prepay_id.'&signType=MD5&timeStamp='.$time_stamp.'&key='.$config['mch_key'];
                        $sign = md5($sign);
                        $sign = strtoupper($sign);
                        $pay_params['timestamp'] = $time_stamp;
                        $pay_params['sign'] = $sign;
                        $pay_params['prepay_id'] = "".$res->prepay_id;
                        $response['pay_params'] = $pay_params;
                    }

                    $db->commit();
                    $response['error'] = 0;
                    $response['msg'] = '会员注册成功!<br/>会员编号:'.$account.'<br/>登录密码:123456<br/>订单编号:'.$pay_order_sn;
                } else {
                    $response['msg'] = '提交订单失败';
                }
            } else {
                $db->rollback();
                $response['msg'] = '更新会员信息失败';
            }
        } else {
            $db->rollback();
            $response['msg'] = '注册会员失败';
        }
    }

    echo json_encode($response);
    exit;
}

assign('level_fee', json_encode($level_fee));
$smarty->display('recommend.phtml');