<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/10/20
 * Time: 下午12:04
 */
include 'library/init.inc.php';

$mch_key = $config['mch_key'];
//仅对微信支付的异步通知
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$log->record($xml);
$data = simplexml_load_string($xml);
$success_response =<<<XML
<xml>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <return_msg><![CDATA[OK]]></return_msg>
</xml>
XML;


if($data)
{
    if($data->return_code == 'SUCCESS')
    {
        //支付成功
        $sn = $data->out_trade_no;
        $sn = $db->escape($sn);

        $pattern = '/R.*/';
        $data->sign = strtolower($data->sign);

        if(preg_match($pattern, $sn))
        {
            //充值订单
            $get_recharge_info = 'select `account`,`amount` from '.$db->table('recharge').' where `recharge_sn`=\''.$sn.'\'';
            $recharge = $db->fetchRow($get_recharge_info);

            if($recharge && $recharge['amount']*100 == $data->total_fee && $data->sign == tenpay_sign($data, $mch_key))
            {
                $log->record($sn.'支付成功');
                $recharge_data = array(
                    'status' => 3
                );
                //验证充值金额正确
                $flag = $db->autoUpdate('recharge', $recharge_data, '`recharge_sn`=\''.$sn.'\' and `status`<3');
                if($flag && $db->get_affect_rows())
                {
                    update_recharge($sn, 3, $recharge['account'], '在线充值');
                    $log->record('充值成功,成功更新充值记录');
                }
            } else {
                //充值金额不正确或返回不正确
            }
        } else {
            //支付成功
            $sn = $data->out_trade_no;
            $sn = $db->escape($sn);
            //产品订单
            $get_order_info = 'select `total_amount`,`account`,`integral_amount`,`integral_given_amount` from '.$db->table('order').' where `order_sn`=\''.$sn.'\'';

            $order = $db->fetchRow($get_order_info);

            if($order && $order['amount']*100 == $data->total_fee && $data->sign == tenpay_sign($data, $mch_key))
            {
                //验证订单金额正确
                //1. 设置订单为已付款
                $order_data = array(
                    'status' => 3
                );

                $flag = $db->autoUpdate('order', $order_data, '`order_sn`=\''.$sn.'\' and `status`<3');
                if($flag && $db->get_affect_rows()) {
                    $log->record($sn . '支付成功');
                    //2. 订单结算
                    //订单添加完成，开始结算
                    $db->begin();

                    //结算
                    settle($sn);

                    $db->commit();
                }
            } else {
                //金额不正确
            }
        }
    } else {
        //支付失败
    }
} else {
    //没有接收结果
}

echo $success_response;