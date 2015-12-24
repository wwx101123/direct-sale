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
    array('key'=>'site_name', 'name'=>'站点名称', 'value'=>'有道理', 'type'=>'text', 'group'=>'config'),
    array('key'=>'themes', 'name'=>'模板', 'value'=>'youdaoli', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'fee_rate', 'name'=>'提现手续费', 'value'=>'0.1', 'type'=>'text', 'group'=>'config'),
    array('key'=>'public_account', 'name'=>'公众号原始ID', 'value'=>'gh_641969c615c1', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'token', 'name'=>'公众号接入口令', 'value'=>'youdaoli', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'wechat_account', 'name'=>'微信号', 'value'=>'youdaoli_', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'appid', 'name'=>'appid', 'value'=>'wx7a6dfbebfce9f745', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'appsecret', 'name'=>'appsecret', 'value'=>'c23657c63b980c5f83f37c22064302bf', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'EncodingAESKey', 'name'=>'EncodingAESKey', 'value'=>'', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'access_token', 'name'=>'access_token', 'value'=>'', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'expired', 'name'=>'expired', 'value'=>'0', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'mch_id', 'name'=>'商户ID', 'value'=>'1272320201', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'mch_key', 'name'=>'商户API KEY', 'value'=>'youdaoli400800380540080038054008', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'join_fee_2', 'name'=>'365会员年费', 'value'=>'365', 'type'=>'text', 'group'=>'config'),
    array('key'=>'join_fee_3', 'name'=>'空间代理商加盟费', 'value'=>'20000', 'type'=>'text', 'group'=>'config'),
    array('key'=>'join_fee_4', 'name'=>'平台代理商加盟费', 'value'=>'100000', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_4_2', 'name'=>'平台推365会员奖金', 'value'=>'100', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_4_3', 'name'=>'平台推空间奖金比例', 'value'=>'0.5', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_4_4', 'name'=>'平台推平台奖金比例', 'value'=>'0.3', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_3_2', 'name'=>'空间推365会员奖金', 'value'=>'50', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_3_3', 'name'=>'空间推空间奖金比例', 'value'=>'0.35', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_3_4', 'name'=>'空间推平台奖金比例', 'value'=>'0.2', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_2_2', 'name'=>'365推365会员奖金', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_2_3', 'name'=>'365推空间奖金比例', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_2_4', 'name'=>'365推平台奖金比例', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mmanager_4', 'name'=>'平台管理费比例', 'value'=>'0.1', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mmanager_3', 'name'=>'空间管理费比例', 'value'=>'0.1', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mmanager_2', 'name'=>'365管理费比例', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mmanager_reward_4', 'name'=>'平台管理奖比例', 'value'=>'0.5', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mmanager_reward_3', 'name'=>'空间管理奖比例', 'value'=>'0.3', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mmanager_reward_2', 'name'=>'365管理奖比例', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'withdraw_min', 'name'=>'最小提现额', 'value'=>'100', 'type'=>'text', 'group'=>'config')
);

//管理员
$table[] = 'admin';
$data[] = array(
    array('account'=>'youdaoli', 'password'=>md5('youdaoli'.PASSWORD_END), 'email'=>'support@youdaoli.cn', 'name'=>'有道理',
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

//$table[] = 'card_pool';
//$data[] = array(
//    array('id'=>1, 'account'=>'AH000001')
//);
//
//$table[] = 'bank_info';
//$data[] = array(
//    array('bank_name'=>'招商银行', 'bank_account'=>'查良镛', 'bank_card'=>'6225998209856748')
//);

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