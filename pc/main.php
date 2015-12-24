<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

//获取最新公告、未读留言、团队新增人数、团队新增业绩
$get_contents = 'select `title`,`add_time`,`id` from '.$db->table('content').' where `section_id`=1 order by `add_time` DESC limit 10';
$contents = $db->fetchAll($get_contents);
assign('contents', $contents);

$get_unread_message = 'select count(*) from '.$db->table('message').' where `to`=\''.$_SESSION['account'].'\' and `status`=0';
$message_count = $db->fetchOne($get_unread_message);
assign('message_count', $message_count);

$nearby = time()-7*24*3600;
$get_group_count = 'select count(*) from '.$db->table('member').
    ' where `recommend_path` like \''.$member_info['recommend_path'].'%\' and `account`<>\''.$_SESSION['account'].'\' and `add_time`>='.$nearby;
$group_count = $db->fetchOne($get_group_count);
assign('group_count', $group_count);

$year = date('Y');
$month = date('m');
$get_achievement_count = 'select sum(`amount`) from '.$db->table('achievement').' where `year`='.$year.' and `month`='.$month.' and `account`=\''.$_SESSION['account'].'\'';
$achievement_count = $db->fetchOne($get_achievement_count);
assign('achievement_count', $achievement_count);

//获取轮播广告
$get_ads = 'select `img` from '.$db->table('ad').' where `ad_pos_id`=1 order by `order_view` ASC';
$ads = $db->fetchAll($get_ads);
assign('ads', $ads);

$smarty->display('main.phtml');
