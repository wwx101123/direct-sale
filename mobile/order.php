<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/15
 * Time: 下午4:47
 */
include 'library/init.inc.php';
global $db, $log, $smarty, $config, $lang;

$template = 'order-list.phtml';
$action = 'list|detail|comment|express_info|product_comment';
$act = check_action($action, getGET('act'));
$operation = 'pay_now|cancel|rollback|receive|comment|product_comment|sort';
$opera = check_action($operation, getPOST('opera'));

if('' == $act)
{
    $act = 'list';
}

//确认收货
if('receive' == $opera)
{
    $order_sn = getPOST('order_sn');

    $response = array('error'=>1, 'msg'=>'');

    if($order_sn == '')
    {
        $response['msg'] = '订单编号为空';
    } else {
        $order_sn = $db->escape($order_sn);
        $check_order_sn = 'select `order_sn` from ' . $db->table('order') . ' where `order_sn`=\'' . $order_sn . '\' and `account`=\''.$_SESSION['account'].'\'';
        $order_sn = $db->fetchOne($check_order_sn);

        if($order_sn)
        {
            $data = array('status'=>7);

            if($db->autoUpdate('order', $data, '`order_sn`=\''.$order_sn.'\''))
            {
                $response['error'] = 0;
                $response['msg'] = '确认收货成功';
                //奖金发放
            } else {
                $response['msg'] = '系统繁忙，请稍后再试';
            }
        } else {
            $response['msg'] = '订单错误';
        }
    }

    echo json_encode($response);
    exit;
}

//订单取消
if('cancel' == $opera)
{
    $order_sn = getPOST('order_sn');

    $response = array('error'=>1, 'msg'=>'');

    if($order_sn == '')
    {
        $response['msg'] = '订单编号为空';
    } else {
        $order_sn = $db->escape($order_sn);
        $check_order_sn = 'select `order_sn` from ' . $db->table('order') . ' where `order_sn`=\'' . $order_sn . '\' and `account`=\''.$_SESSION['account'].'\'';
        $order_sn = $db->fetchOne($check_order_sn);

        if($order_sn)
        {
            $db->begin();
            //回退库存
            //回退积分/佣金/余额
            //删除订单
            if($db->autoDelete('order', '`order_sn`=\''.$order_sn.'\''))
            {
                if($db->autoDelete('order_detail', '`order_sn`=\''.$order_sn.'\''))
                {
                    $response['error'] = 0;
                    $response['msg'] = '订单取消成功';
                    $db->commit();
                } else {
                    $db->rollback();
                    $response['msg'] = '002:取消订单失败';
                }
            } else {
                $response['msg'] = '001:取消订单失败';
                $db->rollback();
            }
        } else {
            $response['msg'] = '订单错误';
        }
    }

    echo json_encode($response);
    exit;
}

if('pay_now' == $opera)
{
    $order_sn = getPOST('order_sn');

    $response = array('error'=>1, 'msg'=>'');

    if($order_sn == '')
    {
        $response['msg'] = '订单编号为空';
    } else {
        $order_sn = $db->escape($order_sn);
        $check_order_sn = 'select `order_sn` from ' . $db->table('order') . ' where `order_sn`=\'' . $order_sn . '\' and `account`=\''.$_SESSION['account'].'\'';
        $order_sn = $db->fetchOne($check_order_sn);

        if($order_sn)
        {
            $_SESSION['order_sn'] = $order_sn;
            $response['error'] = 0;
            $response['message'] = '前往支付页面';
            /*
            $mch_id = $config['mch_id'];
            $mch_key = $config['mch_key'];

            $_SESSION['payment'] = 'Wechat';

            $get_order_info = 'select * from '.$db->table('order').' where `order_sn`=\''.$order_sn.'\' and `account`=\''.$_SESSION['account'].'\'';

            $order = $db->fetchRow($get_order_info);

            $total_fee = $order['real_amount'];
            $detail = '订单编号:'.$order_sn;

            $response['price'] = '￥'.sprintf('%.2f', $total_fee);

            $body = $config['site_name'].'订单收款';
            $body = $detail;
            $out_trade_no = $order_sn;

            $res = create_prepay($config['appid'], $mch_id, $mch_key, $_SESSION['openid'], $total_fee, $body, $detail, $out_trade_no);

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
            */
        } else {
            $response['msg'] = '订单错误';
        }
    }

    echo json_encode($response);
    exit;
}


if('express_info' == $act)
{
    $express_state = array(
        0 => '在途',
        1 => '揽件',
        2 => '疑难',
        3 => '签收',
        4 => '退签',
        5 => '派件',
        6 => '退回'
    );
    assign('express_state', $express_state);

    $order_sn = getGET('order_sn');

    if($order_sn == '')
    {
        $response['msg'] = '参数错误';
    } else {
        $order_sn = $db->escape($order_sn);
    }

    $get_order_info = 'select * from '.$db->table('order').' where `order_sn`=\''.$order_sn.'\'';
    $order = $db->fetchRow($get_order_info);

    if($order && $order['status'] == 6)
    {
        $get_express_info = 'select `code`,`name` from '.$db->table('express').' where `code`='.$order['delivery_code'];
        $express_info = $db->fetchRow($get_express_info);
        $express_flow = query_express($order['delivery_code'], $order['delivery_sn']);
        $express_flow = json_decode($express_flow, true);
        assign('express_flow', $express_flow);
        assign('express_info', $express_info);
    }
    assign('order', $order);

    $get_order_detail = 'select p.`img` from '.$db->table('order_detail').' as od join '.$db->table('product').
        ' as p using(`product_sn`) where od.`order_sn`=\''.$order_sn.'\'';
    assign('product_img', $db->fetchOne($get_order_detail));

    $template = 'track.phtml';
}

if('detail' == $act)
{
    $order_sn = getGET('sn');

    if($order_sn == '')
    {
        redirect('order.php');
    }
    $order_sn = $db->escape($order_sn);

    $get_order = 'select * from '.$db->table('order').
                 ' where `account`=\''.$_SESSION['account'].'\' and `order_sn`=\''.$order_sn.'\'';

    $order = $db->fetchRow($get_order);

    $get_order_detail = 'select `od`.`integral`,od.`price`,od.`name`,od.`product_sn`,p.`id`,p.`img`,od.`number` from '.$db->table('order_detail').' as od '.
        ' join '.$db->table('product').' as p using(`product_sn`) where od.`order_sn`=\''.$order_sn.'\'';

    $order['order_detail'] = $db->fetchAll($get_order_detail);
    $order['show_status'] = $lang['order_status'][$order['status']];

    assign('order', $order);
    $template = 'order-detail.phtml';
    $_SESSION['order_sn'] = $order_sn;
}

if('list' == $act)
{
    $status = intval(getGET('status'));

    assign('status', $status);

    $get_order_list = 'select * from '.$db->table('order').' where `account`=\''.$_SESSION['account'].'\' order by `add_time` DESC';

    $log->record($get_order_list);

    $order_list = $db->fetchAll($get_order_list);

    if($order_list)
    {
        foreach ($order_list as $key => $ol)
        {
            $get_order_detail = 'select od.`name`,od.`product_sn`,p.`id`,p.`img`,od.`number` from ' . $db->table('order_detail') . ' as od ' .
                ' join ' . $db->table('product') . ' as p using(`product_sn`) where od.`order_sn`=\'' . $ol['order_sn'] . '\'';
            $order_list[$key]['order_detail'] = $db->fetchAll($get_order_detail);
            $order_list[$key]['show_status'] = $lang['order_status'][$ol['status']];
        }
    }

    assign('order_list', $order_list);
}

$smarty->display($template);