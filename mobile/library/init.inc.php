<?php
/**
 * 移动端初始化程序
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';
define('MODULE_PATH', ROOT_PATH.'mobile');

$class_list = array('Smarty', 'WechatResponse', 'Code');
$loader->includeClass($class_list);

$script_list = array('wechat');
$loader->includeScript($script_list);

//初始化Smarty
global $smarty;
$smarty = new Smarty();
$smarty->setTemplateDir('themes');
$smarty->setCompileDir('data/compile');
$smarty->setCacheDir('data/cache');

//设置模板路径
assign('template_dir', 'themes/');

$active_script = str_replace(MODULE_PATH.'/', '', $_SERVER['SCRIPT_FILENAME']);
assign('active_script', $active_script);

//设置语言包
assign('lang', $lang);
//设置网站参数
assign('config', $config);

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

if(!isset($_SESSION['parent_id'])){
    $_SESSION['parent_id'] = 0;
}

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
//微信获取和同步用户信息
if($_SESSION['openid'] == '' && $code != '' && $state == 2048 && is_weixin())
{
    $wechat_user = get_user_info($code, $config['appid'], $config['appsecret'], 'userinfo');

    if($wechat_user)
    {
        $log->record("get user openid:".$wechat_user->openid);
        $_SESSION['openid'] = $wechat_user->openid;

        $get_account = 'select `account` from '.$db->table('member').' where `openid`=\''.$wechat_user->openid.'\'';
        $account = $db->fetchOne($get_account);

        if(!$account)
        {
            //如果用户不存在，则直接新注册用户
            $log->record("register new member");
            register_member($_SESSION['openid'], $_SESSION['parent_id']);
        }

        $member_data = array(
            'sex' => $wechat_user->sex,
            'nickname' => $wechat_user->nickname,
            'province' => $wechat_user->province,
            'city' => $wechat_user->city,
            'headimg' => $wechat_user->headimgurl
        );

        $db->autoUpdate('member', $member_data, '`openid`=\''.$wechat_user->openid.'\'');
        $get_account = 'select `account` from '.$db->table('member').' where `openid`=\''.$wechat_user->openid.'\'';
        $_SESSION['account'] = $db->fetchOne($get_account);
    } else {
        echo 'ERROR 2048: 获取授权信息失败';
        exit;
    }
}

if($_SESSION['openid'] == '' && $_SESSION['account'] == '')
{
    $no_login_script = 'code.php|login.php|register.php|forgot.php|data_center.php|index.php|article.php|article_list.php|install.php|integral_product_list.php|';
    $no_login_script .= 'category.php|product.php|cart.php|product_list.php|search.php|shop.php|distribution_shop.php|notify.php|wechat.php';
    $script_name = str_replace(ROOT_PATH, '', $_SERVER['SCRIPT_FILENAME']);

    $flag = check_action($no_login_script, $script_name);
    if($flag == '')
    {
        redirect('login.php');
        exit;
    }
}

//微信JS调用参数
if(is_weixin())
{
    $jssdk = new JSSDK($config['appid'], $config['appsecret']);
    $signPackage = $jssdk->GetSignPackage();
    assign('signPackage', $signPackage);
}