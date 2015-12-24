<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:11
 */
include 'library/init.inc.php';

$operation = 'wechat|alipay|bank|cancel';
$opera = check_action($operation, getPOST('opera'));
$action = 'add|list';
$act = check_action($action, getGET('act'), 'add');

$template = 'recharge.phtml';

if('cancel' == $opera)
{
    $response = array('error'=>0, 'msg'=>'');

    $withdraw_sn = getPOST('withdraw_sn');

    if($withdraw_sn == '')
    {
        $response['msg'] = '000:参数错误';
    } else {
        $withdraw_sn = $db->escape($withdraw_sn);
    }

    if($response['msg'] == '')
    {
        $db->begin();
        $check_withdraw = 'select * from '.$db->table('recharge').' where `account`=\''.$_SESSION['account'].'\' and '.
                          ' `recharge_sn`=\''.$withdraw_sn.'\' and `status`=1 for update;';

        if($withdraw = $db->fetchRow($check_withdraw))
        {
            $db->autoDelete('recharge', '`recharge_sn`=\''.$withdraw_sn.'\'');
            $response['error'] = 0;
            $response['msg'] = '取消申请成功';
        } else {
            $response['msg'] = '该申请已处理或不存在';
        }

        $db->commit();
    }

    echo json_encode($response);
    exit;
}

//支付方式变更时生成支付代码
if('bank' == $opera)
{
    $amount = getPOST('amount');
    $amount = floatval($amount);

    $response = array('error'=>1, 'msg'=>'');

    $_SESSION['payment'] = 'wechat';

    if($amount <= 0)
    {
        $response['msg'] = '充值金额必须大于0';
    } else {
        $total_fee = $amount;

        $plugin_path = ROOT_PATH.'plugins/payment/';

        $get_payment = 'select * from '.$db->table('payment').' where `plugins`=\'Bank\'';
        $payment = $db->fetchRow($get_payment);
        $payment['detail'] = '收款账号:<br/>';

        include $plugin_path.'Bank'.'.class.php';
        $configure = $plugins[0]['configure'];
        $plugin_configure = unserialize($payment['configure']);
        foreach($configure as $item)
        {
            $payment['detail'] .= $item['name'].'：'.$plugin_configure[$item['key']].'<br/>';
        }

        $recharge_sn = add_recharge($_SESSION['account'], $total_fee, $payment['id'], $payment['name'], $payment['plugins'], $payment['detail']);;
        if($recharge_sn) {
            $response['price'] = '￥' . sprintf('%.2f', $total_fee);
            $detail = '充值流水号:' . $recharge_sn;
            $response['error'] = 0;

            $response['msg'] = '充值申请提交成功!<br/>';
            $response['msg'] .= '充值流水号:'.$recharge_sn.'<br/>';
            $response['msg'] .= '充值金额:￥' . sprintf('%.2f', $total_fee).'<br/>';
            $response['msg'] .= '请尽快安排转账.';

        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

if('wechat' == $opera)
{
    $amount = getPOST('amount');
    $amount = floatval($amount);

    $mch_id = $config['mch_id'];//商户编号
    $mch_key = $config['mch_key'];//商户API密钥

    $response = array('error'=>1, 'msg'=>'');

    $_SESSION['payment'] = 'wechat';

    if($amount <= 0)
    {
        $response['msg'] = '充值金额必须大于0';
    } else {
        $total_fee = $amount;

        $recharge_sn = add_recharge($_SESSION['account'], $total_fee, 0, '微信支付', 'wechat', '微信支付');;
        if($recharge_sn) {
            $response['price'] = '￥' . sprintf('%.2f', $total_fee);
            $detail = '充值流水号:' . $recharge_sn;

            $body = $config['site_name'] . '充值';
            $body = $detail;
            $out_trade_no = $recharge_sn;

            $res = create_prepay($config['appid'], $mch_id, $mch_key, $_SESSION['openid'], $total_fee, $body, $detail, $out_trade_no);

            $res = simplexml_load_string($res);

            if ($res->prepay_id) {
                $response['error'] = 0;
            } else {
                $response['msg'] = $res->return_code . ',' . $res->return_msg;
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
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

if('add' == $act) {
//获取用户信息
    $get_user_info = 'select * from ' . $db->table('member') . ' where `account`=\'' . $_SESSION['account'] . '\'';
    $user_info = $db->fetchRow($get_user_info);
    assign('user_info', $user_info);

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

                    $plugin_path = ROOT_PATH . 'plugins/payment/';

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
}

if('list' == $act)
{
    $template = 'recharge_list.phtml';

    $get_recharge_list = 'select `recharge_sn`,`amount`,`status`,`payment_name`,`remark`,`add_time`,`pay_time` from '.
        $db->table('recharge').' where `account`=\''.$_SESSION['account'].'\' order by `add_time` DESC';
    $recharge_list = $db->fetchAll($get_recharge_list);

    assign('recharge_list', $recharge_list);
}

$smarty->display($template);