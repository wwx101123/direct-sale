<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/19
 * Time: 下午2:41
 */
include 'library/init.inc.php';

$operation = 'add|update|clearup|delete|checkout';
$opera = check_action($operation, getPOST('opera'));

if('checkout' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');
    $cart = getPOST('cart');

    $get_product_list = 'select c.`id` as `c_id`,c.`product_sn`,c.`price`,c.`integral`,p.`id`,p.`name`,p.`img`,p.`inventory` from '.
                        $db->table('cart').' as c join '.$db->table('product').' as p using(`product_sn`) where c.`account`=\''.$_SESSION['account'].'\'';
    $product_list = $db->fetchAll($get_product_list);

    $cart_tmp = array();
    if($product_list)
    {
        foreach ($product_list as $p)
        {
            $cart_tmp[$p['c_id']] = array(
                'inventory' => intval($p['inventory']),
                'price' => floatval($p['price']),
                'integral' => floatval($p['integral']),
                'product_sn' => $p['product_sn']
            );
        }
    }

    $amount = 0;
    $integral_amount = 0;
    foreach($cart as $id=>$p)
    {
        if($cart_tmp[$id]['inventory'] >= $p['number'])
        {
            $cart_tmp[$id]['number'] = intval($p['number']);
            $amount += $p['number'] * $cart_tmp[$id]['price'];
            $integral_amount += $p['number'] * $cart_tmp[$id]['integral'];
        } else {
            $response['msg'] = '库存不足';
            break;
        }
    }

    if($response['msg'] == '')
    {
        foreach($cart_tmp as $p)
        {
            $data = array('number'=>$p['number']);

            $db->autoUpdate('cart', $data, '`account`=\''.$_SESSION['account'].'\' and `product_sn`=\''.$p['product_sn'].'\'');
        }
        $response['error'] = 0;
    }

    echo json_encode($response);
    exit;
}

if('delete' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');
    $cart_id = intval(getPOST('cid'));

    if($db->autoDelete('cart', '`id`=\''.$cart_id.'\' and `account`=\''.$_SESSION['account'].'\''))
    {
        $response['error'] = 0;
        $response['msg'] = '移除产品成功';
    } else {
        $response['msg'] = '系统繁忙';
    }

    echo json_encode($response);
    exit;
}

if('add' == $opera)
{
    $product_sn = trim(getPOST('product_sn'));
    $number = intval(getPOST('number'));

    $response = array('error'=>1, 'msg'=>'');

    $account = $_SESSION['account'];

    if($product_sn == '')
    {
        $response['msg'] .= "-参数错误\n";
    } else {
        $product_sn = $db->escape($product_sn);
    }

    if($number <= 0)
    {
        $response['msg'] .= "-购买数量必须大于0";
    }

    if($response['msg'] == '') {
        $get_product = 'select * from ' . $db->table('product') . ' where `product_sn`=\'' . $product_sn . '\'';
        $product = $db->fetchRow($get_product);
        $inventory = $product['inventory'];

        if ($inventory >= $number) {
            $get_id = 'select `id` from '.$db->table('cart').' where `account`=\''.$account.'\' and `product_sn`=\''.$product_sn.'\'';
            $id = $db->fetchOne($get_id);

            if(!$id)
            {
                $data = array(
                    'number' => $number,
                    'account' => $account,
                    'product_sn' => $product_sn,
                    'product_id' => $product['id'],
                    'price' => $product['price'],
                    'integral' => $product['integral'],
                    'add_time' => time()
                );

                if($db->autoInsert('cart', array($data)))
                {
                    $response['error'] = 0;
                    $response['msg'] = '加入购物车成功';
                } else {
                    $response['msg'] = '系统繁忙';
                }
            } else {
                $response['error'] = 0;
                $response['msg'] = "该产品已加入购物车";
            }
        } else {
            $response['msg'] .= "库存不足";
        }
    }

    echo json_encode($response);
    exit;
}

if('clearup' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');

    if($db->autoDelete('cart', '`account`=\''.$_SESSION['account'].'\''))
    {
        $response['msg'] = '购物车已清空';
        $response['error'] = 0;
    } else {
        $response['msg'] = '系统繁忙';
    }

    echo json_encode($response);
    exit;
}

$get_product_list = 'select c.`checked`,c.`attributes`,c.`id` as c_id,c.`product_sn`,c.`price`,c.`integral`,c.`number`,p.`id`,p.`name`,p.`img`,p.`inventory` from '.
                    $db->table('cart').' as c join '.$db->table('product').' as p using(`product_sn`) where c.`account`=\''.$_SESSION['account'].'\'';

$product_list = $db->fetchAll($get_product_list);
assign('product_list', $product_list);

$amount = 0;
$total_number = 0;
$integral_amount = 0;
$cart = array();
if($product_list)
{
    foreach ($product_list as $p)
    {
        $cart[$p['c_id']] = array(
            'id' => $p['id'],
            'product_sn' => $p['product_sn'],
            'inventory' => intval($p['inventory']),
            'number' => intval($p['number']),
            'price' => floatval($p['price']),
            'integral' => floatval($p['integral']),
            'checked' => $p['checked'] ? true : false
        );

        $total_number += $p['number'];
        $amount += $p['number'] * $p['price'];
        $integral_amount += $p['number'] * $p['integral'];
    }
}

assign('cart_json', json_encode($cart));
assign('total_amount', sprintf('%.2f', $amount));
assign('total_number', sprintf('%.2f', $total_number));
assign('total_integral', $integral_amount);
$smarty->display('cart.phtml');