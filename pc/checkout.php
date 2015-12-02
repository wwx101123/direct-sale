<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'consume';
$opera = check_action($operation, getPOST('opera'));

if('consume' == $opera)
{
    $response = array('error'=>1, 'msg'=>'', 'errmsg'=>array());

    $password = trim(getPOST('password'));
    $product_list = getPOST('product_list');
    $consignee = trim(getPOST('consignee'));
    $zipcode = trim(getPOST('zipcode'));
    $cmobile = trim(getPOST('cmobile'));
    $address = trim(getPOST('address'));

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
        $response['errmsg']['consignee'] = '请填写收货人';
    } else {
        $consignee = $db->escape($consignee);
    }

    if($cmobile == '')
    {
        $response['errmsg']['cmobile'] = '请填写联系电话';
    } else {
        $cmobile = $db->escape($cmobile);
    }

    if($address == '')
    {
        $response['errmsg']['address'] = '请填写收货地址';
    } else {
        $address = $db->escape($address);
    }

    $zipcode = $db->escape($zipcode);

    $cart_list = array();
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
                foreach($price_list_json as $level_id=>$pc)
                {
                    if($_number > $pc['min_number'])
                    {
                        $product['number'] = $pc['min_number'];
                        $_number -= $pc['min_number'];
                    } else {
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

    if($amount > $member_info['balance'])
    {
        $response['msg'] = '账户余额不足';
    }

    if(count($response['errmsg']) == 0 && $response['msg'] == '') {
        $db->begin();
        //2、提交订单
        $order_sn = '';
        do {
            $order_sn = time() . rand(1000, 9999);

            $check_order = 'select `order_sn` from ' . $db->table('order') . ' where `order_sn`=\'' . $order_sn . '\'';
        } while ($db->fetchOne($check_order));

        $order_data = array(
            'order_sn' => $order_sn,
            'add_time' => time(),
            'total_amount' => $amount,
            'real_amount' => 0,
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
            'status' => 3,
            'type' => 2, //重消订单
            'payment_id' => 1,
            'payment_name' => '余额支付',
            'payment_code' => 'Balance'
        );

        if ($db->autoInsert('order', array($order_data))) {
            foreach ($cart_list as $k => $v) {
                $cart_list[$k]['order_sn'] = $order_sn;
            }

            $db->autoInsert('order_detail', $cart_list);

            member_account_change($_SESSION['account'], -1*$amount, 0, 0, 0, 0, 0, $_SESSION['account'], 2, $order_sn.'订单支付');
            //3、结算、累计业绩
            add_achievement($member_info['account'], $amount, 0, 0, $total_pv, $number, $level_up);
            //依据订单产品进行结算
            $path = $member_info['recommend_path'];
            foreach($cart_list as $c)
            {
                $get_price_list = 'select `price`,`level_id`,`min_number` from '.$db->table('price_list').' where `product_sn`=\''.$c['product_sn'].'\' order by `level_id`';

                $price_list = $db->fetchAll($get_price_list);

                $price_list_json = array();

                if($price_list)
                {
                    foreach($price_list as $pc)
                    {
                        $price_list_json[$pc['level_id']] = $pc;
                    }
                }

                $get_member_list = 'select `account`,`level_id` from '.$db->table('member').' where `id` in ('.$path.'0) order by find_in_set(`id`,\''.$path.'0\')';
                $level_equal = 1;
                $current_level = $member_info['level_id'];
                $refund = 0;
                $group_reward = 1;
                $member_list = $db->fetchAll($get_member_list);

                array_pop($member_list);
                while($node = array_pop($member_list))
                {
                    //级差奖、平级奖
                    if($current_level < $node['level_id'])
                    {
                        $parent_price = $price_list_json[$node['level_id']]['price'];
                        $reward = $c['price'] - $parent_price;

                        if($reward > 0)
                        {
                            add_reward($node['account'], $reward*$c['number'] - $refund, 0, $order_sn, '级差奖');
                            member_account_change($node['account'], 0, 0, $reward*$c['number'] - $refund,0,0,0,$_SESSION['account'], 3, $order_sn.'奖金');
                            $c['price'] = $parent_price;
                            $refund = 0;
                        }

                        if($node['level_id'] == 6)
                        {
                            $level_equal = 3;
                        } else {
                            $level_equal = 1;
                        }
                        $current_level = $node['level_id'];
                    } else if($current_level == $node['level_id'] && $level_equal--) {
                        $log->record('平级奖'.$current_level);
                        if($current_level == 6)
                        {
                            $_refund = $config['level_'.$current_level.(4-$level_equal)] * $c['number'];

                            if($_refund > 0)
                            {
                                add_reward($node['account'], $_refund, 0, $order_sn, '平级奖');
                                member_account_change($node['account'], 0, 0, $_refund,0,0,0,$_SESSION['account'], 3, $order_sn.'奖金');
                            }
                        } else if(isset($config['level_'.$current_level])) {
                            $refund = $config['level_'.$current_level] * $c['number'];

                            if($refund > 0)
                            {
                                add_reward($node['account'], $refund, 0, $order_sn, '平级奖');
                                member_account_change($node['account'], 0, 0, $refund,0,0,0,$_SESSION['account'], 3, $order_sn.'奖金');
                            }
                        }
                    }
                }

                //升级判断
                if($c['number'] == $price_list_json[$member_info['level_id']]['min_number'] && $member_info['level_id'] < 5)
                {
                    $member_data = array(
                        'level_id' => ($member_info['level_id'] + 1)
                    );

                    $can_level_up = 1;

                    if($member_data['level_id'] == 5)
                    {
                        $check_group = 'select `account` from '.$db->table('member').' where `recommend_id`='.$member_info['id'].' and `level_id`=4';

                        if($db->fetchOne($check_group))
                        {
                            $can_level_up = 1;
                        } else {
                            $can_level_up = 0;
                        }
                    }

                    if($can_level_up && $db->autoUpdate('member', $member_data, '`account`=\''.$_SESSION['account'].'\''))
                    {
                        $member_info['level_id'] = $member_data['level_id'];
                    }
                }
                //皇冠代理升级
            }

            //皇冠团队奖
            //结算结束

            $response['msg'] = '报单成功';
            $response['content'] = <<<HTML
<p>订单提交成功。</p>
<p>订单编号:%s</p>
HTML;
            $response['content'] = sprintf($response['content'], $order_sn);
            $db->commit();
            $response['error'] = 0;
        } else {
            $response['msg'] = '提交订单失败，请稍后再试';
            $db->rollback();
        }
    }

    echo json_encode($response);
    exit;
}

//产品列表
$get_product_list = 'select * from '.$db->table('product').' where `status`=1';
$product_list = $db->fetchAll($get_product_list);

$product_list_json = array();
if($product_list)
{
    foreach($product_list as $index=>$p)
    {
        $get_price_list = 'select `price`,`level_id`,`min_number` from '.$db->table('price_list').' where `product_sn`=\''.$p['product_sn'].'\'';

        $price_list = $db->fetchAll($get_price_list);

        $price_list_json = array();
        $price = $p['price'];
        if($price_list)
        {
            foreach($price_list as $pc)
            {
                if($pc['level_id'] >= $member_info['level_id'])
                {
                    $pc['price'] = floatval($pc['price']);
                    $pc['min_number'] = intval($pc['min_number']);
                    $price_list_json[$pc['level_id']] = $pc;
                }

                if($pc['level_id'] == $member_info['level_id'])
                {
                    $price = $pc['price'];
                }
            }
        }

        $product_list_json[intval($p['id'])] = array(
            'product_sn' => $p['product_sn'],
            'name' => $p['name'],
            'price' => floatval($price),
            'pv' => floatval($p['pv']),
            'number' => 0,
            'img' => $p['img'],
            'price_list' => $price_list_json
        );

        $product_list[$index]['price_list'] = $price_list_json;
        $product_list[$index]['price'] = $price;
    }
}

assign('product_list', $product_list);
assign('product_list_json', json_encode($product_list_json));

$smarty->display('checkout.phtml');
