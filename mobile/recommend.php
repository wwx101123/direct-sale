<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:11
 */
include 'library/init.inc.php';
global $db, $config, $log, $smarty;

//获取用户信息
$get_member_info = 'select `id`,`account`,`reward`,`reward_await`,`integral`,`integral_await`,`balance`,`level_id`,`level_expired`,'.
    '`add_time`,`name`,`wx_openid`,`recommend_path`,`recommend`,`mobile`,`recommend_id`,`status` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$member_info = $db->fetchRow($get_member_info);
assign('member_info', $member_info);

//获取报单产品
$get_product_list = 'select * from '.$db->table('product').' where `status`=1 and `special`=1 order by `target_level` ASC';
$product_list = $db->fetchAll($get_product_list);
assign('product_list', $product_list);

$operation = 'submit_order';
$opera = check_action($operation, getPOST('opera'));

if('submit_order' == $opera)
{
    $response = array('error' => 1, 'msg' => '');

    $use_balance = getPOST('use_balance') == "true" ? 1 : 0;
    $use_reward = getPOST('use_reward') == "true" ? 1 : 0;
    $name = trim(getPOST('name'));
    $mobile = trim(getPOST('mobile'));
    $recommend = trim(getPOST('recommend'));
    $recommend_info = null;
    $product = null;
    $product_sn = trim(getPOST('product_sn'));
    $consignee = trim(getPOST('consignee'));
    $zipcode = trim(getPOST('zipcode'));
    $cmobile = trim(getPOST('cmobile'));
    $address = trim(getPOST('address'));

    if($member_info['level_id'] <= 1) {
        $response['msg'] .= '-当前会员等级不满足要求<br/>';
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

    foreach($product_list as $_product) {
        if($_product['product_sn'] == $product_sn) {
            $product = $_product;
            break;
        }
    }

    if(empty($product)) {
        $response['msg'] .= '-请选择报单产品';
    }

    //订单相关
    if($consignee == '')
    {
        $response['msg'] .= '-请填写收货人<br/>';
    } else {
        $consignee = $db->escape($consignee);
    }

    if($cmobile == '')
    {
        $response['msg'] .= '-请填写联系电话<br/>';
    } else {
        $cmobile = $db->escape($cmobile);
    }

    if($address == '')
    {
        $response['msg'] .= '-请填写收货地址<br/>';
    } else {
        $address = $db->escape($address);
    }

    $zipcode = $db->escape($zipcode);

    if($response['msg'] == '')
    {
        //计算费用
        $total_amount = $product['price'];
        $real_amount = $total_amount;
        $reward_paid = 0;
        $balance_paid = 0;
        $pv_amount = $product['pv'];

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

        $recommend = $recommend_info['account'];
        //注册会员
        $register_data = array(
            'name' => $name,
            'mobile' => $mobile,
            'recommend' => $recommend,
            'add_time' => time(),
            'account' => get_account(),
            'recommend_id' => $recommend_info['id'],
            'from' => 'wechat',
            'status' => 1,
            'password' => md5('123456'.PASSWORD_END),
            'level_id' => 1
        );

        if(empty($register_data['account'])) {
            $response['msg'] = '注册会员失败';
            $db->rollback();

            echo json_encode($response);
            exit;
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
                    'product_amount' => $real_amount,
                    'integral_amount' => 0,
                    'integral_given_amount' => 0,
                    'recommend' => $recommend,
                    'recommend_id' => $recommend_info['id'],
                    'payment_id' => 1,
                    'payment_name' => '微信支付',
                    'payment_code' => 'Wechat',
                    'type' => 1, //会员报单
                    'balance_paid' => $balance_paid,
                    'reward_paid' => $reward_paid,
                    'account' => $account,
                    'pv_amount' => $pv_amount,
                    'consignee' => $consignee,
                    'address' => $address,
                    'zipcode' => $zipcode,
                    'phone' => $cmobile,
                    'stock_given_amount' => $product['stock_given'],
                    'target_level' => $product['target_level']
                );

                $response['status'] = 1;
                if($real_amount == 0)
                {
                    $response['status'] = 3;
                    $pay_order_data['status'] = 3;
                    $pay_order_data['pay_time'] = time();
                }

                $pay_order_sn = '';
                $flag = false;
                $cnt = 10; // 重试次数
                do
                {
                    $pay_order_sn = date('YmdHis').rand(1000, 9999);
                    $pay_order_data['order_sn'] = $pay_order_sn;
                    $flag = $db->autoInsert('order', [$pay_order_data]);
                } while(!$flag && $cnt--);

                if($flag)
                {
                    if($balance_paid || $reward_paid)
                    {
                        member_account_change($_SESSION['account'], -1*$balance_paid, -1*$reward_paid, 0, 0, 0, 0, $_SESSION['account'], 2, $pay_order_sn);
                    }

                    //插入订单详情
                    $order_detail = [
                        [
                            'order_sn' => $pay_order_sn,
                            'product_sn' => $product['product_sn'],
                            'product_id' => $product['id'],
                            'name' => $product['name'],
                            'img' => $product['img'],
                            'price' => $product['price'],
                            'pv' => $product['pv'],
                            'integral' => 0,
                            'integral_given' => 0,
                            'number' => 1,
                            'stock_given' => $product['stock_given'],
                            'target_level' => $product['target_level']
                        ]
                    ];
                    $db->autoInsert('order_detail', $order_detail);

                    //根据订单状态判断是否需要发起支付
                    if($response['status'] == 3)
                    {
                        //订单已支付
                        //结算
                        settle($pay_order_sn);
                    } else {
                        $_SESSION['order_sn'] = $pay_order_sn;
                    }

                    $db->commit();
                    $response['error'] = 0;
                    $response['msg'] = '会员注册成功!<br/>会员编号:'.$account.'<br/>登录密码:123456<br/>订单编号:'.$pay_order_sn;
                    if($response['status'] != 3) {
                        $response['msg'] .= '<br/>请尽快支付以激活会员身份';
                    }
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

//只有普通会员以上等级才能报单
if($member_info['level_id'] <= 1) {
    redirect('index.php');
}

$smarty->display('recommend.phtml');