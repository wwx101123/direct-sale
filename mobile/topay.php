<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/15
 * Time: 上午8:49
 */
include 'library/init.inc.php';

$operation = 'wechat|alipay';
$opera = check_action($operation, getPOST('opera'));

//支付方式变更时生成支付代码
if('wechat' == $opera)
{
    $response = array('error'=>1, 'msg'=>'');

    $get_payment = 'select * from '.$db->table('payment').' where `plugins`=\'Wechat\' and `status`=1';
    $payment = $db->fetchRow($get_payment);

    if(empty($payment)) {
        $response['msg'] = '尚未开通微信支付';

        return json_encode($response);
    }

    $payment['configure'] = unserialize($payment['configure']);
    $mch_id = $payment['configure']['mch_id'];
    $mch_key = $payment['configure']['mch_key'];
    $mch_app_id = $payment['configure']['appid'];
    $log->record_array($payment['configure']);

    $_SESSION['payment'] = 'wechat';

    $order_sn = $_SESSION['order_sn'];

    $get_order_info = 'select * from '.$db->table('order').' where `order_sn`=\''.$order_sn.'\' and `account`=\''.$_SESSION['account'].'\'';

    $order = $db->fetchRow($get_order_info);

    $total_fee = $order['total_amount'];
    $detail = '订单编号:'.$order_sn;

    $response['price'] = '￥'.sprintf('%.2f', $total_fee);

    $body = $config['site_name'].'订单收款';
    $body = $detail;
    $out_trade_no = $order_sn;

    $res = create_prepay($mch_app_id, $mch_id, $mch_key, $_SESSION['openid'], $total_fee, $body, $detail, $out_trade_no);

    $res = simplexml_load_string($res);

    if($res->prepay_id)
    {
        $response['error'] = 0;
    } else {
        $response['msg'] = $res->return_code.','.$res->return_msg;
    }

    $nonce_str = get_nonce_str();
    $response['nonce_str'] = $nonce_str;
    $time_stamp = time();

    //最后参与签名的参数有appId, timeStamp, nonceStr, package, signType。
    $sign = 'appId='.$config['appid'].'&nonceStr='.$nonce_str.'&package=prepay_id='.$res->prepay_id.'&signType=MD5&timeStamp='.$time_stamp.'&key='.$mch_key;
    $sign = md5($sign);
    $sign = strtoupper($sign);
    $response['timestamp'] = $time_stamp;
    $response['sign'] = $sign;
    $response['prepay_id'] = "".$res->prepay_id;

    echo json_encode($response);
    exit;
}

$order_sn = $_SESSION['order_sn'];

$get_order_info = 'select * from '.$db->table('order').' where `order_sn`=\''.$order_sn.'\'';

$order = $db->fetchRow($get_order_info);
assign('order', $order);

$get_order_detail = 'select `name`,`number` from '.$db->table('order_detail'). 'where `order_sn`=\''.$order_sn.'\'';
$order_detail = $db->fetchAll($get_order_detail);
assign('order_detail', $order_detail);

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
        unset($payment['configure']);
        $payment_list_json[$payment['id']] = $payment;
    }
}
assign('payment_list', $payment_list);
assign('payment_list_json', json_encode($payment_list_json));

$smarty->display('topay.phtml');