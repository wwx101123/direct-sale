<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 上午9:29
 */
include 'library/init.inc.php';

$id = intval(getGET('id'));

if($id <= 0)
{
    header('Location: index.php');
    exit;
}

$get_category = 'select * from '.$db->table('category').' where `id`='.$id;

$category = $db->fetchRow($get_category);

assign('category', $category);

$get_product_list = 'select * from '.$db->table('product').' where `category_id`='.$id;

$product_list = $db->fetchAll($get_product_list);
assign('product_list', $product_list);
$smarty->display('category.phtml');