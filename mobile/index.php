<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:11
 */
include 'library/init.inc.php';

//获取用户信息
$get_member_info = 'select `account`,`reward`,`reward_await`,`integral`,`integral_await`,`balance`,`level_id`,`level_expired`,'.
                   '`add_time`,`name`,`wx_openid`,`wx_headimg` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$member_info = $db->fetchRow($get_member_info);
assign('member_info', $member_info);

//获取公告
$get_notice = 'select `id`,`title` from '.$db->table('content').' where `section_id`=1 order by `add_time` DESC limit 5';
$notice = $db->fetchAll($get_notice);
assign('notice', $notice);

//获取轮播广告
$get_cycle_ad = 'select `img`,`url` from '.$db->table('ad').' where `ad_pos_id`=1 order by `order_view`';
$cycle_ad = $db->fetchAll($get_cycle_ad);
assign('cycle_ad', $cycle_ad);

//获取展示广告
$get_perform_ad = 'select `img`,`url`,`alt` from '.$db->table('ad').' where `ad_pos_id`=3 order by `order_view`';
$perform_ad = $db->fetchAll($get_perform_ad);
assign('perform_ad_4', $perform_ad);

$smarty->display('index.phtml');