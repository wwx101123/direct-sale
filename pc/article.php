<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/10/25
 * Time: 下午8:08
 */
include 'library/init.inc.php';

$id = intval(getGET('id'));

$get_content = 'select `title`,`add_time`,`content` from '.$db->table('content').' where `id`='.$id.' order by `add_time` DESC limit 10';
$content = $db->fetchRow($get_content);
assign('content', $content);

//获取轮播广告
$get_ads = 'select `img` from '.$db->table('ad').' where `ad_pos_id`=1 order by `order_view` ASC';
$ads = $db->fetchAll($get_ads);
assign('ads', $ads);

$get_contents = 'select `title`,`add_time`,`id` from '.$db->table('content').' where `section_id`=1 order by `add_time` DESC limit 10';
$contents = $db->fetchAll($get_contents);
assign('contents', $contents);

$smarty->display('article.phtml');