<?php
/**
 * 会员充值API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'wechat|alipay|add|delete|list';
$opera = check_action($operation, getPOST('opera'));

$response = array('errno'=>1, 'errmsg'=>'', 'errcontent'=>array());

//微信支付方式提交充值申请
if('wechat' == $opera)
{
    $amount = getPOST('amount');
    $amount = floatval($amount);

    $mch_id = $config['mch_id'];//商户编号
    $mch_key = $config['mch_key'];//商户API密钥

    $_SESSION['payment'] = 'wechat';

    if($amount <= 0)
    {
        $response['errmsg'] = '充值金额必须大于0';
    } else {
        $total_fee = $amount;

        $recharge_sn = add_recharge($_SESSION['account'], $total_fee, 0, '微信支付', 'wechat');
        if($recharge_sn) {
            $response['price'] = '￥' . sprintf('%.2f', $total_fee);
            $detail = '充值流水号:' . $recharge_sn;

            $body = $config['site_name'] . '充值';
            $body = $detail;
            $out_trade_no = $recharge_sn;

            $res = create_prepay($config['appid'], $mch_id, $mch_key, $_SESSION['openid'], $total_fee, $body, $detail, $out_trade_no);

            $res = simplexml_load_string($res);

            if ($res->prepay_id) {
                $response['errno'] = 0;
            } else {
                $response['errmsg'] = $res->return_code . ',' . $res->return_msg;
            }

            $nonce_str = get_nonce_str();
            $response['nonce_str'] = $nonce_str;
            $time_stamp = time();

            //最后参与签名的参数有appId, timeStamp, nonceStr, package, signType。
            $sign = 'appId=' . $config['appid'] . '&nonceStr=' . $nonce_str . '&package=prepay_id=' . $res->prepay_id . '&signType=MD5&timeStamp=' . $time_stamp . '&key=' . $mch_key;
            $sign = md5($sign);
            $sign = strtoupper($sign);
            $response['timestamp'] = $time_stamp;
            $response['sign'] = $sign;
            $response['prepay_id'] = "" . $res->prepay_id;
        } else {
            $response['errmsg'] = '系统繁忙，请稍后再试';
        }
    }
}

echo json_encode($response);
exit;
