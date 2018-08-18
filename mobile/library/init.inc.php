<?php
/**
 * 移动端初始化程序
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';
global $db, $config, $log, $lang;

define('MODULE_PATH', ROOT_PATH.'mobile');

$class_list = array('Smarty', 'WechatResponse', 'Code', 'JSSDK');
$loader->includeClass($class_list);

$script_list = array('wechat');
$loader->includeScript($script_list);

//初始化Smarty
Smarty::muteExpectedErrors();

global $smarty;
$smarty = new Smarty();

$smarty->setTemplateDir(MODULE_PATH.'/themes');
$smarty->setCompileDir(MODULE_PATH.'/data/compile');
$smarty->setCacheDir(MODULE_PATH.'/data/cache');

//设置模板路径
assign('template_dir', 'themes/');

$active_script = str_replace(MODULE_PATH.'/', '', $_SERVER['SCRIPT_FILENAME']);
assign('active_script', $active_script);

//设置语言包
assign('lang', $lang);
//设置网站参数
assign('config', $config);

if($config['close']) {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST') {
        echo json_encode([
            'error' => 1,
            'msg' => '站点已关闭',
            'message' => '站点已关闭'
        ]);
    } else {
        echo '站点已关闭';
    }

    exit;
}

//读取ukey参数，记录推荐人信息
$ukey = getGET('ukey');
if($ukey != '')
{
    $ukey = intval($ukey);

    if($ukey > 0)
    {
        $get_member_id = 'select `id` from '.$db->table('member').' where `id`='.$ukey;

        if($member_id = $db->fetchOne($get_member_id))
        {
            $_SESSION['parent_id'] = $member_id;
        }
    }
}

//皇冠代理升级
$now = time();
$now = strtotime('2015-12-01');
$day = date('d', $now);
$day = 1;

//模拟数据
//$_SESSION['openid'] = '01234567890X';
//$_SESSION['account'] = 'DS000440';

if(!isset($_SESSION['openid']))
{
    $_SESSION['openid'] = '';
}

if(!isset($_SESSION['account']))
{
    $_SESSION['account'] = '';
}

$code = getGET('code');
$state = getGET('state');
$member_info = null;
if($_SESSION['openid'] == '' && $code != '' && $state == 2048)
{
    $wechat_user = get_user_info($code, $config['appid'], $config['appsecret'], 'userinfo');

    if($wechat_user)
    {
        $_SESSION['openid'] = $wechat_user->openid;

        $get_account = 'select `account` from '.$db->table('member').' where `wx_openid`=\''.$wechat_user->openid.'\'';
        if(!$db->fetchOne($get_account))
        {
            $_SESSION['account'] = register_openid($wechat_user->openid, $wechat_user->nickname, 1);
        } else {
            $_SESSION['account'] = $db->fetchOne($get_account);
        }

        $data = array(
            'wx_headimg' => $wechat_user->headimgurl,
            'wx_nickname' => $wechat_user->nickname
        );

        $db->autoUpdate('member', $data, '`wx_openid`=\''.$wechat_user->openid.'\'');
    } else {
        echo 'ERROR 2048: 获取授权信息失败';
        exit;
    }
}


if($_SESSION['openid'] == '' || $_SESSION['account'] == '')
{
    $no_login_script = 'notify.php|wechat.php|qrcode.php|login.php|bind.php|data_init.php|index.php|install.php';
    $script_name = str_replace(ROOT_PATH.'mobile/', '', $_SERVER['SCRIPT_FILENAME']);

    $flag = check_action($no_login_script, $script_name);
    if($flag == '')
    {
        if(!is_weixin())
        {
            echo '该系统仅支持通过微信登陆';
            exit;
        } else {
            $current_url = 'http://'.$config['mobile_domain'].'/'.$script_name;
            $log->record('wechat grant privilege login: '.$current_url);
            $current_url = urlencode($current_url);
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$config['appid'].'&redirect_uri='.$current_url.'&response_type=code&scope=snsapi_userinfo&state=2048#wechat_redirect';
            redirect($url);
            exit;
        }
    }
} else {
    $get_user_info = 'select * from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
    $member_info = $db->fetchRow($get_user_info);
    assign('member_info', $member_info);
}

if(is_weixin()) {
    $jssdk = new JSSDK($config['appid'], $config['appsecret']);
    $signPackage = $jssdk->GetSignPackage();
    assign('signPackage', $signPackage);
}
