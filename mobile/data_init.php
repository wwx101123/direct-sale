<?php
/**
 * 初始化数据
 */
include 'library/init.inc.php';
$loader->includeScript('purview');

header('Content-Type: text/html;charset=utf-8');
$table = array();
$data = array();

//站点参数
$table[] = 'sysconf';
$data[] = array(
    array('key'=>'site_name', 'name'=>'站点名称', 'value'=>'DS', 'type'=>'text', 'group'=>'config'),
    array('key'=>'themes', 'name'=>'模板', 'value'=>'', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'fee_rate', 'name'=>'提现手续费', 'value'=>'0.1', 'type'=>'text', 'group'=>'config'),
    array('key'=>'public_account', 'name'=>'公众号原始ID', 'value'=>'gh_2a6295362e13', 'type'=>'text', 'group'=>'config'),
    array('key'=>'token', 'name'=>'公众号接入口令', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'wechat_account', 'name'=>'微信号', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'appid', 'name'=>'appid', 'value'=>'wx6f3f6df0a8063128', 'type'=>'text', 'group'=>'config'),
    array('key'=>'appsecret', 'name'=>'appsecret', 'value'=>'a8fa2d4e95326d054b03b4022084a147', 'type'=>'text', 'group'=>'config'),
    array('key'=>'EncodingAESKey', 'name'=>'EncodingAESKey', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'access_token', 'name'=>'access_token', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'expired', 'name'=>'expired', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mch_id', 'name'=>'商户ID', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mch_key', 'name'=>'商户API KEY', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'withdraw_min', 'name'=>'最小提现额', 'value'=>'100', 'type'=>'text', 'group'=>'config'),
    array('key'=>'jssdk_ticket', 'name'=>'微信JSSDK Ticket', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'jssdk_expired', 'name'=>'微信JSSDK过期时间', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mobile_domain', 'name'=>'移动站点域名', 'value'=>'m.directsale.com', 'type'=>'text', 'group'=>'config'),
    //====================================  奖金比例参数 ================================
    array('key'=>'recommend_reward_rate', 'name'=>'推荐奖系数', 'value'=>'0.1', 'type'=>'text', 'group'=>'config'),
    array('key'=>'manager_reward_rate', 'name'=>'管理奖系数', 'value'=>'0.05', 'type'=>'text', 'group'=>'config'),
    array('key'=>'manager_reward_up_rate', 'name'=>'线上管理奖系数', 'value'=>'0.05', 'type'=>'text', 'group'=>'config'),
    array('key'=>'server_reward_rate', 'name'=>'业务部业绩提成', 'value'=>'0.02', 'type'=>'text', 'group'=>'config'),
    array('key'=>'dividend_gold_limit', 'name'=>'分红标准', 'value'=>'9000', 'type'=>'text', 'group'=>'config'),
);

//管理员
$table[] = 'admin';
$data[] = array(
    array('account'=>'admin', 'password'=>md5('admin'.PASSWORD_END), 'email'=>'support@aaa.cn', 'name'=>'直销',
        'sex'=>'M', 'role_id'=>1)
);

//角色
$table[] = 'role';
$data[] = array(
    array('id'=>1, 'name'=>'超级管理员', 'purview'=>json_encode($purview))
);

////产品分类
//$table[] = 'category';
//$data[] = array(
//    array('id'=>1, 'name'=>'会员专区')
//);

/*
$table[] = 'product';
$data[] = array(
);
*/

//$table[] = 'user';
//$data[] = array(
//    array('openid'=>'01234567890X', 'account'=>'AH000000', 'id'=>1, 'nickname'=>'九健电子商务', 'path'=>'1,', 'add_time'=>time(), 'is_shop'=>1)
//);

$table[] = 'card_pool';
$data[] = array(
    array('id'=>1, 'account'=>'DS000001')
);

$table[] = 'bank_info';
$data[] = array(
    array('bank_name'=>'招商银行', 'bank_account'=>'查良镛', 'bank_card'=>'6225998209856748')
);

echo '初始化数据:<br/>';
foreach($table as $key=>$name)
{
    $db->query('truncate table '.$db->table($name).';');
    echo $name;

    $dot_count = 30 - strlen($name);

    while($dot_count--)
    {
        echo '-';
    }

    if($db->autoInsert($name, $data[$key]))
    {
        echo ' <font color="green">success</font><br/>';
    } else {
        echo ' <font color="red">fail</font>:'.$db->errmsg().'<br/>';
    }
}

//register_mobile('13900000000', '有道理', '123456');