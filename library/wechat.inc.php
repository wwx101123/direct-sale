<?php
/**
 * 微信操作
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/15
 * Time: 上午1:07
 */

/**
 * 判断数据源是否是微信
 * @return bool
 * @author winsen
 * @date 2014-10-24
 */
function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
    {
        return true;
	}
	return false;
}

/*
 * 非授权的方式获取用户信息
 */
function get_user_wechat_info($access_token, $openid)
{
    global $db;

    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN';
    $url = sprintf($url, $access_token, $openid);

    $user_info = get($url);

    $user_info = json_decode($user_info);
    $data = array(
        'nickname' => $user_info->nickname,
        'headimg' => $user_info->headimgurl,
        'unionid' => $user_info->unionid
    );

    return $db->autoUpdate('member', $data, '`openid`=\''.$openid.'\'');
}

 //获取用户信息
 /**
 * @param string code
 * @param string public_account
 * @return mixed
 * @author winsen
 * @date 2015-03-30
 */
function get_user_info($code, $appid, $appsecret, $mode = 'base')
{
    //获取access_token
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
    $url = sprintf($url, $appid, $appsecret, $code);

    $response = get($url);
    $response = json_decode($response);

    if(isset($response->errcode))
    {
        echo $response->errcode.':'.$response->errmsg;
        return false;
    } else {
        switch($mode)
        {
        case 'base':
            return $response;
        case 'userinfo':
            //获取用户信息
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
            $url = sprintf($url, $response->access_token, $response->openid);
            $response = get($url);
            $response = json_decode($response);
            if(!isset($response->error))
            {
                return $response;
            } else {
                return false;
            }
            return $response;
        }
    }
}

/**
 * 获取用户推广二维码
 */
function get_qrcode($openid, $access_token)
{
    global $db;
    global $log;

    $get_ticket = 'select `ticket` from '.$db->table('member').' where `openid`=\''.$openid.'\' and `expired`>'.time();
    $ticket = $db->fetchOne($get_ticket);

    if($ticket)
    {
        $qrcode = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
        $log->record('ticket is avaiable.');
        return $qrcode;
    }

    $update_user = 'update '.$db->table('member').' set `scene_id`=0 where `expired`>'.time();
    $db->update($update_user);

    $scene_arr = range(1, 100000);

    $scene_id = 0;
    foreach($scene_arr as $id)
    {
        $check_scene_id = 'select count(*) from '.$db->table('member').' where `scene_id`='.$id.' and `expired`<'.time();
        $log->record($check_scene_id);

        if(!$db->fetchOne($check_scene_id))
        {
            $scene_id = $id;
            break;
        }
    }
    //scene_id已满
    if($scene_id == 0)
    {
        return false;
    }
    //临时二维码申请
    $data = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
    $response = post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token, $data, false);

    $response = json_decode($response);

    if(isset($response->errcode))
    {
        $log->record('get qrcode fail:'.$response->errcode.':'.$response->errmsg);
        return false;
    } else {
        $data = array(
            'scene_id' => $scene_id,
            'ticket' => $response->ticket,
            'expired' => time()+1800
        );

        $db->autoUpdate('member', $data, '`wx_openid`=\''.$openid.'\'');
        $qrcode = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$response->ticket;
        return $qrcode;
    }
}

/**
 * 获取access_token
 * @param string $appid 公众号appid
 * @param string $secretKey 公众号密钥appsecret
 * @return string 成功时返回获取的access_token,失败时返回false
 * @author winsen
 * @date 2014-10-24
 */
function get_access_token($appid, $secretkey)
{
    global $errors;
    global $db;
    global $log;

    $check_access_token = 'select `value` from '.$db->table('sysconf').' where `key`=\'expired\'';
    $expired = $db->fetchOne($check_access_token);
    if($expired > time())
    {
        $get_access_token = 'select `value` from '.$db->table('sysconf').' where `key`=\'access_token\'';

        $log->record('access_token is not expired, expired in '.date('Y-m-d H:i:s', $expired));
        return $db->fetchOne($get_access_token);
    }
    $log->record('access_token is expired, refresh.');
    //对于access_token超时，则重新获取access_token
    $request_time = time();
    $url_get_access_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
    $url = sprintf($url_get_access_token, $appid, $secretkey);

    $data = get($url, null);
    $response = json_decode($data);

    if(!empty($response->errcode))
    {
        $log->record('get access_token '.$response->errmsg.':'.$errors[$response->errcode]);
        return false;
    } else {
        $data = array('value'=>($request_time + ($response->expires_in/2)));

        $db->autoUpdate('sysconf', $data, '`key`=\'expired\'');

        $data = array('value'=>$response->access_token);

        $db->autoUpdate('sysconf', $data, '`key`=\'access_token\'');
        $log->record('access_token expired in '.date('Y-m-d H:i:s', ($request_time + $response->expires_in)));
        return $response->access_token;
    }
}

/**
 * 微信接入开发者模式验证URL以及接收用户信息时使用
 * @param string $signature 微信加密签名
 * @param string $timestamp 时间戳
 * @param string $nonce 随机数
 * @param string $token 公众号设置的Token
 * @return bool
 * @author winsen
 * @date 2014-10-24
 */
function check_signature($signature, $timestamp, $nonce, $token)
{
	$token = $token;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode($tmpArr);
	$tmpStr = sha1($tmpStr);
	
    if( $tmpStr == $signature )
    {
		return true;
	} else {
		return false;
	}
}

/**
 * 生成预支付交易单
 */
function create_prepay($appid, $mch_id, $mch_key, $openid, $total_fee, $body, $detail, $out_trade_no, $params = array())
{
    global $config;

    $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    $now = time();
    $data = array(
        'appid' => $appid,
        'mch_id' => $mch_id,
        'openid' => $openid,
        'total_fee' => $total_fee*100,
        'nonce_str' => get_nonce_str(),
        'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
        'fee_type' => 'CNY',
        'time_start' => date('YmdHis', $now),
        'time_expire' => date('YmdHis', ($now+3600*24*7)),//支付链接7天后无效
        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].'/notify.php',//完成支付后的回调地址
        'trade_type' => 'JSAPI',//交易类型，可选：JSAPI, NATIVE, APP, WAP
        //  'limit_pay' => 'no_credit', //此项将不允许使用信用卡支付
        'body' => $body,
        'detail' => $detail,
        'out_trade_no' => $out_trade_no//订单编号
    );

    $data = array_merge($data, $params);

    ksort($data);
    $param_str = '';
    $xml = new SimpleXMLElement('<xml></xml>');

    foreach($data as $key=>$value)
    {
        if(empty($value))
        {
            unset($data[$key]);
        } else {
            $param_str .= $key.'='.$value.'&';
            $xml->addChild($key, $value);
        }
    }

    $param_str .= 'key='.$mch_key;

    $sign = md5($param_str);
    $sign = strtoupper($sign);
    $xml->addChild('sign', $sign);

    $response = post($url, $xml->asXML(), false);
    return $response;
}

function tenpay_sign($data, $mch_key)
{
    $data_ = array();
    foreach($data as $key=>$value)
    {
        $data_[$key] = $value;
    }
    $data = $data_;

    ksort($data);
    $param_str = '';
    foreach($data as $key=>$value)
    {
        if($key != 'sign')
        {
            $param_str .= $key . '=' . $value . '&';
        }
    }
    $param_str .= 'key='.$mch_key;

    $sign = md5($param_str);
    return strtolower($sign);
}

/**
 * 生成随机字符串
 */
function get_nonce_str()
{
    $seed = '01234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';

    $strlen = 32;

    $nonce_str = '';
    while($strlen--)
    {
        $nonce_str .= $seed[rand(0, strlen($seed)-1)];
    }

    return $nonce_str;
}

/**
 * 发送客服信息
 * @param $message
 * @param $access_token
 */
function send_kf_message($message, $access_token)
{
    global $log;

    $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;

    $log->record($message);
    $response = post($url, $message, false);

    $log->record($response);
}
