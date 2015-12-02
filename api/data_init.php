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
    array('key'=>'site_name', 'name'=>'站点名称', 'value'=>'嘟喷哒量子水', 'type'=>'text', 'group'=>'config'),
    array('key'=>'themes', 'name'=>'模板', 'value'=>'', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'fee_rate', 'name'=>'提现手续费', 'value'=>'0.1', 'type'=>'text', 'group'=>'config'),
    array('key'=>'public_account', 'name'=>'公众号原始ID', 'value'=>'gh_38f2a6168ab7', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'token', 'name'=>'公众号接入口令', 'value'=>'dupenda', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'wechat_account', 'name'=>'微信号', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'appid', 'name'=>'appid', 'value'=>'wxd7431ab127ef9494', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'appsecret', 'name'=>'appsecret', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'EncodingAESKey', 'name'=>'EncodingAESKey', 'value'=>'w150FJFqk7N1kQK8EHlsH0CmtltCPewRYboP7903e1Q', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'access_token', 'name'=>'access_token', 'value'=>'', 'type'=>'text', 'group'=>'config'),
    array('key'=>'expired', 'name'=>'expired', 'value'=>'0', 'type'=>'text', 'group'=>'config'),
    array('key'=>'mch_id', 'name'=>'expired', 'value'=>'1289510101', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'mch_key', 'name'=>'expired', 'value'=>'keruihuanbaoderuierweiliangzisui', 'type'=>'text', 'group'=>'themes'),
    array('key'=>'level_4', 'name'=>'金级平级奖', 'value'=>'10', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_5', 'name'=>'钻级平级奖', 'value'=>'5', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_61', 'name'=>'皇冠平级奖1', 'value'=>'5', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_62', 'name'=>'皇冠平级奖2', 'value'=>'3', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_63', 'name'=>'皇冠平级奖3', 'value'=>'2', 'type'=>'text', 'group'=>'config'),
    array('key'=>'level_6_else', 'name'=>'皇冠团队奖', 'value'=>'10', 'type'=>'text', 'group'=>'config')
);

//管理员
$table[] = 'admin';
$data[] = array(
    array('account'=>'dupenda', 'password'=>md5('dupenda'.PASSWORD_END), 'email'=>'support@dupenda.cn', 'name'=>'德睿尔',
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

//register_mobile('13900000000', '嘟喷哒公司', '123456');
