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

assign('user_info', $member_info);

if('checkout' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');

    $consignee = trim(getPOST('consignee'));
    $zipcode = trim(getPOST('zipcode'));
    $cmobile = trim(getPOST('cmobile'));
    $address = trim(getPOST('address'));
    $use_reward = getPOST('use_reward') == 'true' ? true : false;
    $use_balance = getPOST('use_balance') == 'true' ? true : false;
    $payment_id = intval(getPOST('payment_id'));

    $payment = null;
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

                $price = $p['price'];

                $total_pv += $number * $product['pv'];
                $product['number'] = $number;

                $amount += $product['number']*$price;

                $cart_list[] = $product;
            }
        }
    }

    if($payment_id <= 0) {
        $response['msg'] .= '-请选择支付方式<br/>';
    } else {
        $get_payment = 'select `id`,`plugins`,`name` from '.$db->table('payment').' where `status`=1 and `id`='.$payment_id;
        $payment = $db->fetchRow($get_payment);

        if(empty($payment)) {
            $response['msg'] .= '-请选择有效的支付方式<br/>';
        }
    }

    if($response['msg'] == '') {
        $db->begin();
        //2、提交订单
        $reward_paid = 0;
        $balance_paid = 0;
        $real_amount = $amount;

        if($use_reward)
        {
            if($member_info['reward'] > $real_amount)
            {
                $reward_paid = $real_amount;
                $real_amount = 0;
            } else {
                $reward_paid = $member_info['reward'];
                $real_amount -= $reward_paid;
            }
        }

        if($use_balance)
        {
            if($member_info['balance'] > $real_amount)
            {
                $balance_paid = $real_amount;
                $real_amount = 0;
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
            'add_time' => time(),
            'total_amount' => $amount,
            'real_amount' => $real_amount,
            'product_amount' => $amount,
            'integral_amount' => $total_integral,
            'integral_given_amount' => $total_integral_given,
            'pv_amount' => $total_pv,
            'consignee' => $consignee,
            'address' => $address,
            'phone' => $cmobile,
            'zipcode' => $zipcode,
            'recommend' => '',
            'account' => $_SESSION['account'],
            'status' => $status,
            'type' => 2, //重消订单
            'payment_id' => $payment['id'],
            'payment_name' => $payment['name'],
            'payment_code' => $payment['plugins'],
            'reward_paid' => $reward_paid,
            'balance_paid' => $balance_paid
        );

        if($status == 3) {
            $order_data['pay_time'] = time();
        }

        $order_sn = '';
        $flag = false;
        $cnt = 10;
        do {
            $order_sn = 'C'.date('YmdHis') . rand(1000, 9999);

            $order_data['order_sn'] = $order_sn;

            $flag = $db->autoInsert('order', array($order_data));
        } while (!$flag && $cnt--);

        if ($flag) {
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
                settle($order_sn);
                //结算结束
                $response['error'] = 0;
            } else {
                $_SESSION['order_sn'] = $order_sn;
            }
            $response['order_sn'] = $order_sn;
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

//获取支付插件
$get_payment_list = 'select * from ' . $db->table('payment') . ' where `status`=1';
$payment_list = $db->fetchAll($get_payment_list);
$payment_list_json = array();
if($payment_list)
{
    foreach ($payment_list as $key => $payment) {
        switch ($payment['plugins']) {
            case 'Bank':
                $payment['detail'] = '';

                $plugin_path = ROOT_PATH . '/center/plugins/payment/';

                include $plugin_path . $payment['plugins'] . '.class.php';
                $configure = $plugins[0]['configure'];
                $plugin_configure = unserialize($payment['configure']);
                foreach ($configure as $item) {
                    $payment['detail'] .= '<label><span>' . $item['name'] . '：</span>' . $plugin_configure[$item['key']] . '</label>';
                }
                break;
            default:
                $payment['detail'] = '';
        }

        $payment_list[$key] = $payment;
        $payment_list_json[$payment['id']] = $payment;
    }
}
assign('payment_list', $payment_list);
assign('payment_list_json', json_encode($payment_list_json));

$smarty->display("checkout.phtml");
