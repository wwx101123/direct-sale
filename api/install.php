<?php
/**
 * 数据库安装程序
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';
header('Content-Type: text/html;charset=utf-8');

$table = array();
$sql = array();

$table[] = '系统配置';
$sql[] = 'create table if not exists '.$db->table('sysconf').' (
    `key` varchar(255) not null primary key,
    `name` varchar(255) not null,
    `value` text,
    `group` varchar(255) not null,
    `options` text,
    `type` varchar(255) not null default \'text\',
    `remark` varchar(255)
) default charset=utf8;';

$table[] = '会员卡号池';
$sql[] = 'create table if not exists '.$db->table('card_pool').' (
    `id` bigint not null auto_increment primary key,
    `account` varchar(255) not null unique,
    `status` tinyint(1) not null default \'1\'
) default charset=utf8;';

$table[] = '会员';
$sql[] = 'create table if not exists '.$db->table('member').' (
    `id` bigint not null auto_increment unique,
    `account` varchar(255) not null primary key,
    `password` varchar(255),
    `super_password` varchar(255),
    `name` varchar(255),
    `mobile` varchar(255),
    `email` varchar(255),
    `sex` char(2) not null default \'N\',
    `add_time` int not null,
    `balance` decimal(18,2) not null default \'0\',
    `integral` decimal(18,2) not null default \'0\',
    `integral_await` decimal(18,2) not null default \'0\',
    `reward` decimal(18,2) not null default \'0\',
    `reward_await` decimal(18,2) not null default \'0\',
    `shopping_icon` decimal(18,2) not null default \'0\',
    `status` int not null default \'2\',
    `from` varchar(255),
    `recommend` varchar(255),
    `recommend_id` int not null default \'0\',
    `recommend_path` text,
    `place` varchar(255),
    `place_id` int not null default \'0\',
    `place_path` text,
    `wx_nickname` varchar(255),
    `wx_openid` varchar(255),
    `wx_unionid` varchar(255),
    `wx_headimg` varchar(255),
    `level_id` int not null default \'0\',
    `lchild` varchar(255),
    `rchild` varchar(255),
    `scene_id` int not null default \'0\',
    `expired` int not null default \'0\',
    `ticket` varchar(255),
    index(`mobile`),
    index(`email`),
    index(`wx_openid`),
    index(`wx_unionid`)
) default charset=utf8;';

$table[] = '会员登录记录';
$sql[] = 'create table if not exists '.$db->table('member_login_logs').' (
    `id` bigint not null auto_increment unique,
    `account` varchar(255) not null,
    `token` varchar(255) not null primary key,
    `add_time` int not null
) default charset=utf8;';

$table[] = '账户明细';
$sql[] = 'create table if not exists '.$db->table('account').' (
    `id` bigint not null auto_increment primary key,
    `account` varchar(255) not null,
    `add_time` int not null,
    `balance` decimal(18,2) not null default \'0\',
    `integral` decimal(18,2) not null default \'0\',
    `integral_await` decimal(18,2) not null default \'0\',
    `reward` decimal(18,2) not null default \'0\',
    `reward_await` decimal(18,2) not null default \'0\',
    `shopping_icon` decimal(18,2) not null default \'0\',
    `operator` varchar(255) not null,
    `remark` varchar(255),
    `type` int not null,
    index(`account`)
) default charset utf8;';

$table[] = '购物车';
$sql[] = 'create table if not exists '.$db->table('cart'). ' (
    `id` bigint not null auto_increment primary key,
    `product_sn` varchar(255) not null,
    `product_id` int not null,
    `price` decimal(18,2) not null,
    `integral` decimal(18,2) not null default \'0\',
    `number` int not null,
    `add_time` int,
    `account` varchar(255) not null,
    `attributes` varchar(255),
    `attributes_str` varchar(255),
    `checked` tinyint(1) not null default \'1\',
    index(`account`)
) default charset=utf8;';

$table[] = '产品';
$sql[] = 'create table if not exists '.$db->table('product').' (
    `id` bigint not null auto_increment unique,
    `product_sn` varchar(255) not null primary key,
    `name` varchar(255) not null,
    `price` decimal(18,2) not null,
    `integral` decimal(18,2) not null,
    `integral_given` decimal(18,2) not null default \'0\',
    `product_desc` text,
    `category_id` int not null,
    `status` int not null default \'0\',
    `img` varchar(255) not null,
    `inventory` int not null default \'0\',
    `desc` text,
    `pv` decimal(18,2) not null default \'0\'
) default charset=utf8;';

$table[] = '提现申请';
$sql[] = 'create table if not exists '.$db->table('withdraw').' (
    `id` bigint not null auto_increment unique,
    `withdraw_sn` varchar(255) not null primary key,
    `account` varchar(255) not null,
    `amount` decimal(18,2) not null,
    `fee` decimal(18,2) not null,
    `real_amount` decimal(18,2) not null,
    `status` int not null,
    `add_time` int not null,
    `solve_time` int,
    `remark` varchar(255),
    `bank_id` int not null default \'0\',
    `bank_name` varchar(255) not null,
    `bank_account` varchar(255) not null,
    `bank_card` varchar(255) not null,
    index(`account`)
) default charset=utf8;';

$table[] = '提现日志';
$sql[] = 'create table if not exists '.$db->table('withdraw_log').' (
    `id` bigint not null auto_increment primary key,
    `withdraw_sn` varchar(255) not null,
    `from` int not null,
    `to` int not null,
    `add_time` int not null,
    `operator` varchar(255) not null,
    `remark` varchar(255) not null,
    index(`withdraw_sn`)
) default charset=utf8;';

$table[] = '充值申请';
$sql[] = 'create table if not exists '.$db->table('recharge').' (
    `id` bigint not null auto_increment unique,
    `recharge_sn` varchar(255) not null primary key,
    `account` varchar(255) not null,
    `amount` decimal(18,2) not null,
    `real_amount` decimal(18,2) not null,
    `status` int not null,
    `add_time` int not null,
    `pay_time` int,
    `remark` varchar(255),
    `payment_id` int not null,
    `payment_name` varchar(255) not null,
    `payment_code` varchar(255) not null,
    index(`account`)
) default charset=utf8;';

$table[] = '充值日志';
$sql[] = 'create table if not exists '.$db->table('recharge_log').' (
    `id` bigint not null auto_increment primary key,
    `recharge_sn` varchar(255) not null,
    `from` int not null,
    `to` int not null,
    `add_time` int not null,
    `operator` varchar(255) not null,
    `remark` varchar(255) not null
) default charset=utf8;';

$table[] = '广告位置';
$sql[] = 'create table if not exists '.$db->table('ad_position').' (
    `id` int not null auto_increment primary key,
    `pos_name` varchar(255) not null,
    `width` decimal(18,2) not null,
    `height` decimal(18,2) not null,
    `number` int not null default \'1\',
    `code` text
) default charset=utf8;';

$table[] = '广告';
$sql[] = 'create table if not exists '.$db->table('ad').' (
    `id` int not null auto_increment primary key,
    `img` varchar(255) not null,
    `alt` varchar(255),
    `add_time` int not null,
    `begin_time` int,
    `end_time` int,
    `forever` tinyint(1) not null default \'0\',
    `click_time` int not null default \'0\',
    `url` varchar(255) not null,
    `order_view` int not null default \'50\',
    `ad_pos_id` int not null
) default charset=utf8;';

$table[] = '角色';
$sql[] = 'create table if not exists '.$db->table('role').' (
    `id` int not null auto_increment primary key,
    `purview` text not null,
    `name` varchar(255) not null
) default charset=utf8;';

$table[] = '管理员';
$sql[] = 'create table if not exists '.$db->table('admin').' (
    `account` varchar(255) not null primary key,
    `password` varchar(255) not null,
    `email` varchar(255) not null,
    `name` varchar(255) not null,
    `sex` char(2) not null,
    `role_id` int not null
) default charset=utf8;';

$table[] = '订单';
$sql[] = 'create table if not exists '.$db->table('order').' (
    `id` bigint not null auto_increment unique,
    `order_sn` varchar(255) not null primary key,
    `add_time` int not null,
    `total_amount` decimal(18,2) not null,
    `real_amount` decimal(18,2) not null,
    `product_amount` decimal(18,2) not null,
    `pv_amount` decimal(18,2) not null default \'0\',
    `delivery_fee` decimal(18,2) not null default \'0\',
    `integral_amount` decimal(18,2) not null,
    `integral_given_amount` decimal(18,2) not null,
    `address_id` int not null default \'0\',
    `consignee` varchar(255) not null,
    `address` varchar(255) not null,
    `phone` varchar(255) not null,
    `zipcode` varchar(255),
    `status` int not null default \'1\',
    `pay_time` int,
    `delivery_time` int,
    `delivery_name` varchar(255),
    `delivery_code` varchar(255),
    `delivery_sn` varchar(255),
    `remark` varchar(255),
    `account` varchar(255) not null,
    `recommend` varchar(255),
    `place` varchar(255),
    `recommend_id` int not null default \'0\',
    `place_id` int not null default \'0\',
    `payment_id` int not null,
    `payment_name` varchar(255) not null,
    `payment_code` varchar(255) not null,
    `type` int not null,
    `balance_paid` decimal(18,2) not null default \'0\',
    `integral_paid` decimal(18,2) not null default \'0\',
    `shopping_icon_paid` decimal(18,2) not null default \'0\',
    `reward_paid` decimal(18,2) not null default \'0\'
) default charset=utf8;';

$table[] = '订单详情';
$sql[] = 'create table if not exists '.$db->table('order_detail').' (
    `id` bigint not null auto_increment unique,
    `order_sn` varchar(255) not null,
    `product_sn` varchar(255) not null,
    `product_id` int not null,
    `name` varchar(255) not null,
    `img` varchar(255),
    `price` decimal(18,2) not null,
    `pv` decimal(18,2) not null,
    `integral` decimal(18,2) not null,
    `integral_given` decimal(18,2) not null default \'0\',
    `number` int not null
) default charset=utf8;';

$table[] = '等级价格';
$sql[] = 'create table if not exists '.$db->table('price_list').' (
    `id` bigint not null auto_increment primary key,
    `product_sn` varchar(255) not null,
    `level_id` int not null,
    `product_id` int not null,
    `price` decimal(18,2) not null,
    `min_number` int not null,
    index(`product_sn`),
    index(`product_id`)
) default charset=utf8;';

$table[] = '订单日志';
$sql[] = 'create table if not exists '.$db->table('order_log').' (
    `id` bigint not null auto_increment primary key,
    `order_sn` varchar(255) not null,
    `operator` varchar(255) not null,
    `from` int not null,
    `to` int not null,
    `add_time` int not null,
    `remark` varchar(255),
    index(`order_sn`)
) default charset=utf8;';

$table[] = '会员地址';
$sql[] = 'create table if not exists '.$db->table('address').' (
    `id` bigint not null primary key auto_increment,
    `account` varchar(255) not null,
    `province` int,
    `city` int,
    `district` int,
    `address` varchar(255) not null,
    `is_default` tinyint(1) not null default \'0\',
    `zipcode` varchar(255),
    `consignee` varchar(255) not null,
    `phone` varchar(255) not null,
    index(`account`)
) default charset=utf8;';

$table[] = '银行卡号';
$sql[] = 'create table if not exists '.$db->table('bank').' (
    `id` bigint not null auto_increment primary key,
    `account` varchar(255) not null,
    `bank_name` varchar(255) not null,
    `bank_account` varchar(255) not null,
    `bank_card` varchar(255) not null,
    `is_default` tinyint(1) not null default \'0\',
    index(`account`)
) default charset=utf8;';

$table[] = '账户流水';
$sql[] = 'create table if not exists '.$db->table('account').' (
    `id` bigint not null auto_increment primary key,
    `account` varchar(255) not null,
    `balance` decimal(18,2) not null default \'0\',
    `reward` decimal(18,2) not null default \'0\',
    `reward_await` decimal(18,2) not null default \'0\',
    `integral` decimal(18,2) not null default \'0\',
    `integral_await` decimal(18,2) not null default \'0\',
    `shopping_icon` decimal(18,2) not null default \'0\',
    `add_time` int not null,
    `remark` varchar(255),
    `operator` varchar(255) not null,
    `type` int not null,
    index(`account`)
) default charset=utf8;';

$table[] = '会员业绩';
$sql[] = 'create table if not exists '.$db->table('achievement').' (
    `id` bigint not null auto_increment primary key,
    `year` int not null,
    `month` int not null,
    `amount` decimal(18,2) not null default \'0\',
    `lamount` decimal(18,2) not null default \'0\',
    `ramount` decimal(18,2) not null default \'0\',
    `pv_amount` decimal(18,2) not null default \'0\',
    `level_up` tinyint(1) not null default \'0\',
    `number` int not null default \'0\',
    `account` varchar(255) not null,
    index(`year`,`month`,`account`)
) default charset=utf8;';

$table[] = '会员奖金';
$sql[] = 'create table if not exists '.$db->table('reward').' (
    `id` bigint not null auto_increment primary key,
    `account` varchar(255) not null,
    `reward` decimal(18,2) not null default \'0\',
    `integral` decimal(18,2) not null default \'0\',
    `remark` varchar(255),
    `assoc` varchar(255),
    `status` int not null default \'1\',
    `add_time` int not null,
    `solve_time` int
) default charset=utf8;';

foreach($table as $index=>$tb_name)
{
    echo 'create table '.$tb_name;

    $dot_count = 24 - mb_strlen($tb_name);
    while($dot_count--)
    {
        echo '..';
    }

    if($db->query($sql[$index]))
    {
        echo '<font color="green">success</font><br/>';
    } else {
        echo '<font color="red">failure</font><br/>';
        echo $db->errmsg().'<br/>';
    }
}
