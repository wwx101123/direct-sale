<?php
/**
 * 移动端初始化程序
 * @author winsen
 * @version 1.0.0
 */
include '../library/bootstrap.inc.php';
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

//每个月1号进行升级
if($day == 1)
{
    $last_month = $now - 24*3600;
    $year = date('Y', $last_month);
    $month = date('m', $last_month);

    $three_month_ago = $now - 24*30*3600*3;
    $tyear = date('Y', $three_month_ago);
    $tmonth = date('m', $three_month_ago);

    $second_month = $tmonth + 1;
    $second_year = $tyear;
    if($second_month == 1)
    {
        $second_year++;
    }

    $get_member_list = 'select da.`account`,sum(da.`number`) as total_number ,(select max(db.`level_up`) as level_up from '.
        $db->table('achievement').' as db where db.`account`=da.`account` and ('.
        '(db.`year`='.$tyear.' and db.`month`='.$tmonth.') or '.
        '(db.`year`='.$year.' and db.`month`='.$month.') or '.
        '(db.`year`='.$second_year.' and db.`month`='.$second_month.')'.
        ')) as level_up from '.$db->table('achievement').' as da group by da.`account`';

    $member_list = $db->fetchAll($get_member_list);

    if($member_list)
    {
        foreach($member_list as $node)
        {
            if($node['level_up'] && $node['total_number'] >= 3000)
            {
                $check_level_up = 'select max(`level_up`) as level_up from '.
                    $db->table('achievement').' where `account`= \''.$node['account'].'\' and ('.
                    '(`year`='.$year.' and `month`='.$month.') or '.
                    '(`year`='.$second_year.' and `month`='.$second_month.'))';

                $check_recommend = 'select count(*) from '.$db->table('member').' where `recommend`=\''.$node['account'].'\' and `level_id`=5';
                $recommend_count = $db->fetchOne($check_recommend);

                if($db->fetchOne($check_level_up) && $recommend_count >= 4)
                {
                    $node_data = array('level_id'=>6);

                    $db->autoUpdate('member', $node_data, '`account`=\''.$node['account'].'\'');
                }
            }
        }
    }
}

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
        //$get_account = 'select `account` from '.$db->table('member').' where `wx_openid`=\''.$wechat_user->openid.'\'';
//        $_SESSION['account'] = $db->fetchOne($get_account);
        $get_user_info = 'select * from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
        $member_info = $db->fetchRow($get_user_info);
        assign('member_info', $member_info);
    } else {
        echo 'ERROR 2048: 获取授权信息失败';
        exit;
    }
}


if($_SESSION['openid'] == '' || $_SESSION['account'] == '')
{
    $no_login_script = 'notify.php|wechat.php|qrcode.php|login.php|bind.php';
    $script_name = str_replace(ROOT_PATH.'mobile/', '', $_SERVER['SCRIPT_FILENAME']);

    $flag = check_action($no_login_script, $script_name);
    if($flag == '')
    {
        if(!is_weixin())
        {
            echo '该系统仅支持通过微信登陆';
            exit;
        }

        echo '获取用户信息失败，请联系管理员';
        exit;
    }
}

if(is_weixin()) {
    $jssdk = new JSSDK($config['appid'], $config['appsecret']);
    $signPackage = $jssdk->GetSignPackage();
    assign('signPackage', $signPackage);
}
