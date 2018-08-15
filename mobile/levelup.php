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
    $level_id = intval(getPOST('level_id'));
    $payment_id = intval(getPOST('payment_id'));
    $name = trim(getPOST('name'));
    $mobile = trim(getPOST('mobile'));
    $product = null;
    $product_sn = trim(getPOST('product_sn'));
    $consignee = trim(getPOST('consignee'));
    $zipcode = trim(getPOST('zipcode'));
    $cmobile = trim(getPOST('cmobile'));
    $address = trim(getPOST('address'));


    if($member_info['level_id'] > 1) {
        $response['msg'] .= '-当前等级不满足报单条件<br/>';
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

        $check_mobile = 'select `mobile` from '.$db->table('member').' where `mobile`=\''.$mobile.'\' and `account`<>\''.$_SESSION['account'].'\'';

        $flag = $db->fetchOne($check_mobile);

        if($flag)
        {
            $response['msg'] .= '-手机号码已被使用<br/>';
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

        //更新会员信息
        $register_data = array(
            'name' => $name,
            'mobile' => $mobile,
            'add_time' => time()
        );

        if($db->autoUpdate('member', $register_data, '`account`=\''.$_SESSION['account'].'\''))
        {
            $account = $_SESSION['account'];
            $register_id = $member_info['id'];

            //插入订单
            $pay_order_data = array(
                'add_time' => time(),
                'total_amount' => $total_amount,
                'real_amount' => $real_amount,
                'integral_amount' => 0,
                'integral_given_amount' => 0,
                'product_amount' => $real_amount,
                'recommend' => $member_info['recommend'],
                'recommend_id' => $member_info['recommend_id'],
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
                $response['msg'] = '会员注册成功!<br/>报单等级:'.$lang['level'][$pay_order_data['target_level']].'<br/>订单编号:'.$pay_order_sn.'<br/>请尽快支付订单以生效.';
            } else {
                $response['msg'] = '提交订单失败';
            }
        } else {
            $db->rollback();
            $response['msg'] = '注册会员失败';
        }
    }

    echo json_encode($response);
    exit;
}

//只有游客能注册
if($member_info['level_id'] > 1) {
    redirect('index.php');
}

$smarty->display('levelup.phtml');