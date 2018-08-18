<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/10/10
 * Time: 上午10:06
 */
include 'library/init.inc.php';
$id = intval(getGET('id'));

if($id <= 0)
{
    redirect('index.php');
}

$get_article = 'select `content`,`title`,`add_time`,`author`,`section_id`,`original`,`description` from '.$db->table('content').' where `id`='.$id;
$article = $db->fetchRow($get_article);

assign('article', $article);

$get_section_name = 'select `section_name` from '.$db->table('section').' where `id`='.$article['section_id'];
assign('section_name', $db->fetchOne($get_section_name));

//分享链接
$recommend_url = 'http://'.$config['mobile_domain'].'/article.php?id='.$id;
if(isset($_SESSION['account'])) {
    $recommend_url .= '&ukey='.$member_info['id'];
}
assign('recommend_url', $recommend_url);

$smarty->display('article.phtml');