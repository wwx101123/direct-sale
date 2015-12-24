<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/19
 * Time: 下午6:06
 */
include 'library/init.inc.php';

$operation = 'checkout';
$opera = check_action($operation, getPOST('opera'));

//检查如果没有设置密码先设置密码
//$get_password = 'select `password` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';

//$user_password = $db->fetchOne($get_password);

//if($user_password == '')
//{
//    header('Location: password.php');
//    exit;
//}

assign('user_info', $member_info);

if('checkout' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');

    $consignee = trim(getPOST('consignee'));
    $zipcode = trim(getPOST('zipcode'));
    $cmobile = trim(getPOST('cmobile'));
    $address = trim(getPOST('address'));
    $use_reward = getPOST('use_reward');
    $use_reward = $use_reward == 'true' ? true : false;
    $use_balance = getPOST('use_balance') == 'true' ? true : false;
    $payment = trim(getPOST('payment'));

    $target_pv = 0;
    $total_pv = 0;
    $total_integral = 0;
    $total_integral_given = 0;
    $amount = 0;
    $level_up = 0;

    $get_user_info = 'select * from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
    $user = $db->fetchRow($get_user_info);

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

    $cart_list = array();

    $get_product_list = 'select * from '.$db->table('cart').' where `checked`=1 and `account`=\''.$_SESSION['account'].'\'';
    $product_list = $db->fetchAll($get_product_list);

    if($product_list != '')
    {
        foreach($product_list as $p)
        {
            $number = intval($p['number']);

            if($number > 0)
            {
                $product_sn = $db->escape($p['product_sn']);

                $get_product_info = 'select `price`,`id` as `product_id`,`product_sn`,`img`,`name`,`integral`,`integral_given`,`pv` from ' .
                    $db->table('product') . ' where `product_sn`=\'' . $product_sn . '\'';
                $product = $db->fetchRow($get_product_info);

                $get_price_list = 'select `price`,`level_id`,`min_number` from '.$db->table('price_list').' where `product_sn`=\''.$p['product_sn'].'\' order by `level_id`';

                $price_list = $db->fetchAll($get_price_list);

                $price_list_json = array();
                $price = $p['price'];
                if($price_list)
                {
                    foreach($price_list as $pc)
                    {
                        if($pc['level_id'] >= $member_info['level_id'])
                        {
                            $price_list_json[$pc['level_id']] = $pc;
                        }

                        if($pc['level_id'] == $member_info['level_id'])
                        {
                            $price = $pc['price'];
                        }
                    }
                }

                $_number = $number;
                $total_pv += $number * $product['pv'];

                if($member_info['level_id'] == 1)
                {
                    $_number--;
                    $product['price'] = $price_list_json[1]['price'];
                    $product['number'] = $price_list_json[1]['min_number'];
                    $amount += $product['number']*$product['price'];

                    $cart_list[] = $product;
                }

                foreach($price_list_json as $level_id=>$pc)
                {
                    $greater = $level_id+1;

                    if(isset($price_list_json[$greater]) && $_number >= $price_list_json[$greater]['min_number'])
                    {
                        $log->record('ignore level '.$level_id);
                        continue;
                    } else {
                        $log->record('current level '.$level_id);
                        $log->record_array($pc);
                        $product['number'] = $_number;
                        $_number = 0;
                    }

                    if($level_id == 6 && $product['number'] >= $pc['min_number'])
                    {
                        $level_up = 1;
                    }

                    $product['price'] = $pc['price'];
                    $amount += $product['number'] * $product['price'];

                    $cart_list[] = $product;

                    if($_number == 0)
                    {
                        break;
                    }
                }
            }
        }
    }

    if($response['msg'] == '') {
        $db->begin();
        //2、提交订单
        $order_sn = '';
        do {
            $order_sn = time() . rand(1000, 9999);

            $check_order = 'select `order_sn` from ' . $db->table('order') . ' where `order_sn`=\'' . $order_sn . '\'';
        } while ($db->fetchOne($check_order));

        $reward_paid = 0;
        $balance_paid = 0;
        $real_amount = $amount;

        if($use_reward)
        {
            if($member_info['reward'] > $real_amount)
            {
                $real_amount = 0;
                $reward_paid = $real_amount;
            } else {
                $reward_paid = $member_info['reward'];
                $real_amount -= $reward_paid;
            }
        }

        if($use_balance)
        {
            if($member_info['balance'] > $real_amount)
            {
                $real_amount = 0;
                $balance_paid = $real_amount;
            } else {
                $balance_paid = $member_info['balance'];
                $real_amount -= $balance_paid;
            }
        }

        $status = 1;
        if($real_amount == 0)
        {
            $status = 3;
        }

        $order_data = array(
            'order_sn' => $order_sn,
            'add_time' => time(),
            'total_amount' => $amount,
            'real_amount' => $real_amount,
            'product_amount' => $amount,
            'integral_amount' => $total_integral,
            'integral_given_amount' => $total_integral_given,
            'pv_amount' => $total_pv,
            'balance_paid' => $amount,
            'consignee' => $consignee,
            'address' => $address,
            'phone' => $cmobile,
            'zipcode' => $zipcode,
            'recommend' => '',
            'account' => $_SESSION['account'],
            'status' => $status,
            'type' => 2, //重消订单
            'payment_id' => 2,
            'payment_name' => '微信支付',
            'payment_code' => 'Wechat',
            'reward_paid' => $reward_paid,
            'balance_paid' => $balance_paid
        );

        if ($db->autoInsert('order', array($order_data))) {
            foreach ($cart_list as $k => $v) {
                $cart_list[$k]['order_sn'] = $order_sn;
            }

            $response['status'] = $status;
            $db->autoInsert('order_detail', $cart_list);

            $db->autoDelete('cart', '`account`=\''.$_SESSION['account'].'\' and `checked`=1');

            member_account_change($_SESSION['account'], -1*$balance_paid, -1*$reward_paid, 0, 0, 0, 0, $_SESSION['account'], 2, $order_sn.'订单支付');
            if($status == 3) {
                //3、结算、累计业绩
                //依据订单产品进行结算
                $path = $member_info['recommend_path'];
                $total_number = 0;
                foreach ($cart_list as $c) {
                    $total_number += $c['number'];
                    add_achievement($member_info['account'], $amount, 0, 0, $total_pv, $c['number'], $level_up);
                    $get_price_list = 'select `price`,`level_id`,`min_number` from ' . $db->table('price_list') . ' where `product_sn`=\'' . $c['product_sn'] . '\' order by `level_id`';

                    $price_list = $db->fetchAll($get_price_list);

                    $price_list_json = array();

                    if ($price_list) {
                        foreach ($price_list as $pc) {
                            $price_list_json[$pc['level_id']] = $pc;
                        }
                    }

                    $get_member_list = 'select `account`,`level_id` from ' . $db->table('member') . ' where `id` in (' . $path . '0) order by find_in_set(`id`,\'' . $path . '0\')';
                    $level_equal = 1;
                    $current_level = $member_info['level_id'];
                    $refund = 0;
                    $group_reward = 1;
                    $add_achievement = 1;
                    $member_list = $db->fetchAll($get_member_list);

                    array_pop($member_list);
                    while ($node = array_pop($member_list)) {
                        if ($add_achievement) {
                            add_achievement($node['account'], $amount, 0, 0, $total_pv, $c['number']);
                            if ($node['level_id'] == 6) {
                                $add_achievement = 0;
                            }
                        }
                        //级差奖、平级奖
                        if ($current_level < $node['level_id']) {
                            $parent_price = $price_list_json[$node['level_id']]['price'];
                            $reward = $c['price'] - $parent_price;

                            if ($reward > 0) {
                                add_reward($node['account'], $reward * $c['number'] - $refund, 0, $order_sn, '级差奖');
                                member_account_change($node['account'], 0, 0, $reward * $c['number'] - $refund, 0, 0, 0, $_SESSION['account'], 3, $order_sn . '奖金');
                                $c['price'] = $parent_price;
                                $refund = 0;
                            }

                            if ($node['level_id'] == 6) {
                                $level_equal = 3;
                            } else {
                                $level_equal = 1;
                            }
                            $current_level = $node['level_id'];
                        } else if ($current_level == $node['level_id'] && $level_equal--) {
                            $log->record('平级奖' . $current_level);
                            if ($current_level == 6) {
                                $_refund = $config['level_' . $current_level . (4 - $level_equal)] * $c['number'];

                                if ($_refund > 0) {
                                    add_reward($node['account'], $_refund, 0, $order_sn, '平级奖');
                                    member_account_change($node['account'], 0, 0, $_refund, 0, 0, 0, $_SESSION['account'], 3, $order_sn . '奖金');
                                }
                            } else if (isset($config['level_' . $current_level])) {
                                $refund = $config['level_' . $current_level] * $c['number'];

                                if ($refund > 0) {
                                    add_reward($node['account'], $refund, 0, $order_sn, '平级奖');
                                    member_account_change($node['account'], 0, 0, $refund, 0, 0, 0, $_SESSION['account'], 3, $order_sn . '奖金');
                                }
                            }
                        }
                    }

                    //升级判断
                    if ($member_info['level_id'] < 5 && $c['number'] == $price_list_json[$member_info['level_id'] + 1]['min_number']) {
                        $member_data = array(
                            'level_id' => ($member_info['level_id'] + 1)
                        );

                        $can_level_up = 1;

                        if ($member_data['level_id'] == 5) {
                            $check_group = 'select `account` from ' . $db->table('member') . ' where `recommend_id`=' . $member_info['id'] . ' and `level_id`=4';

                            if ($db->fetchOne($check_group)) {
                                $can_level_up = 1;
                            } else {
                                $can_level_up = 0;
                            }
                        }

                        if ($can_level_up && $db->autoUpdate('member', $member_data, '`account`=\'' . $_SESSION['account'] . '\'')) {
                            $member_info['level_id'] = $member_data['level_id'];
                        }
                    }
                }

                //皇冠团队奖
                $get_member_list = 'select `account` from ' . $db->table('member') . ' where `level_id`=6 and `id` in (' . $path . '0) order by find_in_set(`id`,\'' . $path . '0\')';
                $member_list = $db->fetchAll($get_member_list);

                while ($account = array_pop($member_list)) {
                    $account = $account['account'];
                    $check_total_number = 'select sum(`number`) from ' . $db->table('achievement') . ' where `account`=\'' . $account . '\'';
                    $_total_number = $db->fetchOne($check_total_number);

                    if ($_total_number > 5000) {
                        if ($_total_number - $total_number > 5000) {
                            $reward = $total_number * 10;
                            add_reward($account, $reward, 0, '', '团队奖');
                        } else {
                            $total_number = $_total_number - 5000 + $total_number;
                            $reward = $total_number * 10;
                            add_reward($account, $reward, 0, '', '团队奖');
                        }
                    }
                }
                //结算结束
                $response['error'] = 0;
            } else {
                $mch_id = $config['mch_id'];
                $mch_key = $config['mch_key'];
                $total_fee = $real_amount;
                $body = $config['site_name'].'订单收款';
                $out_trade_no = $order_sn;
                $detail = '订单编号:'.$order_sn;

                $res = create_prepay($config['appid'], $mch_id, $mch_key, $_SESSION['openid'], $total_fee, $body, $detail, $out_trade_no);

                $res = simplexml_load_string($res);

                if($res->prepay_id)
                {
                    $response['error'] = 0;
                } else {
                    $response['msg'] = $res->return_code.','.$res->return_msg;
                }

                $nonce_str = get_nonce_str();
                $response['pay_params']['nonce_str'] = $nonce_str;
                $time_stamp = time();

                //最后参与签名的参数有appId, timeStamp, nonceStr, package, signType。
                $sign = 'appId='.$config['appid'].'&nonceStr='.$nonce_str.'&package=prepay_id='.$res->prepay_id.'&signType=MD5&timeStamp='.$time_stamp.'&key='.$mch_key;
                $sign = md5($sign);
                $sign = strtoupper($sign);
                $response['pay_params']['timestamp'] = $time_stamp;
                $response['pay_params']['sign'] = $sign;
                $response['pay_params']['prepay_id'] = "".$res->prepay_id;
            }

            $response['content'] = <<<HTML
订单提交成功。<br/>
订单编号:%s<br/>
HTML;
            $response['msg'] = sprintf($response['content'], $order_sn);
            $response['error'] = 0;
            $db->commit();
        } else {
            $response['msg'] = '提交订单失败，请稍后再试';
            $db->rollback();
        }
    }

    echo json_encode($response);
    exit;
}

$get_product_list = 'select c.`attributes`,c.`product_sn`,c.`price`,c.`integral`,p.`id`,p.`name`,p.`img`,p.`inventory`,c.`number`,p.`integral_given` from '.
                    $db->table('cart').' as c join '.$db->table('product').' as p using(`product_sn`) where c.`account`=\''.$_SESSION['account'].'\'';
$product_list = $db->fetchAll($get_product_list);
assign('product_list', $product_list);

if(count($product_list) == 0)
{
    header('Location: cart.php');
    exit;
}

$amount = 0;
$integral_amount = 0;
$integral_given_amount = 0;
$total_number = 0;

foreach($product_list as $p)
{
    $amount += $p['price'] * $p['number'];
    $integral_amount += $p['integral'] * $p['number'];
    $integral_given_amount += $p['integral_given'] * $p['number'];
    $total_number += $p['number'];
}

assign('total_amount', sprintf('%.2f', $amount));
assign('integral_amount', sprintf('%.2f', $integral_amount));
assign('integral_given_amount', sprintf('%.2f', $integral_given_amount));
assign('total_number', $total_number);

//获取默认地址
$get_address_info = '';
$smarty->display("checkout.phtml");
