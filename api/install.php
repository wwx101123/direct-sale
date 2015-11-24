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

$table[] = '删除会员表';
$sql[] = 'drop table if exists '.$db->table('member');

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
