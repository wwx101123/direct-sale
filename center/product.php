<?php
/**
 * 会员管理
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:04
 */
include 'library/init.inc.php';
back_base_init();

$template = 'product/';
assign('subTitle', '产品管理');

$action = 'edit|add|view|delete|detail';
$operation = 'edit|add';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));

$get_category_list = 'select `id`,`name` from '.$db->table('category');
$category_list = $db->fetchAll($get_category_list);
assign('category_list', $category_list);

$cat_json = array();
foreach($category_list as $c)
{
    $cat_json[$c['id']] = $c['name'];
}

assign('cat_json', $cat_json);
//===========================================================================
if($opera == 'add')
{
    $name = getPOST('name');
    $price = floatval(getPOST('price'));
    $integral = floatval(getPOST('integral'));
    $img = getPOST('img');
    $integral_given = floatval(getPOST('integral_given'));
    $category_id = intval(getPOST('category_id'));
    $desc = getPOST('desc');
    $status = intval(getPOST('status'));
    $inventory = intval(getPOST('inventory'));

    if($name == '')
    {
        show_system_message('请填写产品名称');
    } else {
        $name = $db->escape($name);
    }

    if($img == '')
    {
        show_system_message('请上传产品图片');
    } else {
        $img = $db->escape($img);
    }

    if($price < 0)
    {
        show_system_message('产品价格不能为负数');
    }

    if($integral < 0)
    {
        show_system_message('产品积分不能为负数');
    }

    if($integral_given < 0)
    {
        show_system_message('赠送积分不能为负数');
    }

    $desc = $db->escape($desc);

    $data = array(
        'name' => $name,
        'price' => $price,
        'integral' => $integral,
        'integral_given' => $integral_given,
        'status' => $status,
        'desc' => $desc,
        'category_id' => $category_id,
        'inventory' => $inventory,
        'img' => $img
    );

    $product_sn = '';
    do
    {
        $product_sn = 'FC'.rand(10000, 99999);
        $check_product = 'select `product_sn` from '.$db->table('product').' where `product_sn`=\''.$product_sn.'\'';
    } while($db->fetchOne($check_product));

    $data['product_sn'] = $product_sn;

    if($db->autoInsert('product', array($data)))
    {
        show_system_message('新增产品成功', array(array('link'=>'product.php', 'alt'=>'产品列表')));
    } else {
        show_system_message('系统繁忙');
    }
}

if($opera == 'edit')
{
    $product_sn = getPOST('eproduct_sn');
    $name = getPOST('name');
    $price = floatval(getPOST('price'));
    $integral = floatval(getPOST('integral'));
    $img = getPOST('img');
    $integral_given = floatval(getPOST('integral_given'));
    $category_id = intval(getPOST('category_id'));
    $desc = getPOST('desc');
    $status = intval(getPOST('status'));
    $inventory = intval(getPOST('inventory'));

    if($product_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $product_sn = $db->escape($product_sn);
    }

    if($name == '')
    {
        show_system_message('请填写产品名称');
    } else {
        $name = $db->escape($name);
    }

    if($price < 0)
    {
        show_system_message('产品价格不能为负数');
    }

    if($integral < 0)
    {
        show_system_message('产品积分不能为负数');
    }

    if($integral_given < 0)
    {
        show_system_message('赠送积分不能为负数');
    }

    $desc = $db->escape($desc);

    $data = array(
        'name' => $name,
        'price' => $price,
        'integral' => $integral,
        'integral_given' => $integral_given,
        'status' => $status,
        'desc' => $desc,
        'category_id' => $category_id,
        'inventory' => $inventory
    );

    if($img != '')
    {
        $data['img'] = $img;
    }

    if($db->autoUpdate('product', $data, '`product_sn`=\''.$product_sn.'\''))
    {
        show_system_message('编辑产品信息成功', array(array('link'=>'product.php', 'alt'=>'产品列表')));
    } else {
        show_system_message('系统繁忙');
    }
}

if('edit' == $act || 'detail' == $act)
{
    $product_sn = getGET('sn');

    if($product_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $product_sn = $db->escape($product_sn);
    }

    $get_product = 'select * from '.$db->table('product').' where `product_sn`=\''.$product_sn.'\'';
    $product = $db->fetchRow($get_product);

    assign('product', $product);
}

if('view' == $act)
{
    $page = getGET('page');
    $count = getGET('count');
    $status = getGET('status');
    $product_sn = getGET('product_sn');

    $where = ' where 1 ';

    if($status != '' and $status >= 0) {
        $where .= ' and `status`='.intval($status);
    } else {
        $status = -1;
    }

    if($product_sn != '')
    {
        $product_sn = $db->escape($product_sn);
        $where .= ' and `product_sn`=\''.$product_sn.'\'';
    }

    $get_total = 'select count(*) from '.$db->table('product').$where;
    $total = $db->fetchOne($get_total);

    $count_expected = array(10, 25, 50, 100);
    $page = intval($page);
    $count = intval($count);
    if( !in_array($count, $count_expected) )
    {
        $count = 10;
    }

    $total_page = ceil($total / $count);

    $page = ( $page > $total_page ) ? $total_page : $page;
    $page = ( $page <= 0 ) ? 1 : $page;

    $offset = ($page - 1) * $count;

    create_pager($page, $total_page, $total);
    assign('count', $count);
    assign('product_sn', $product_sn);
    assign('status', $status);

    $get_product_list = 'select * from '.$db->table('product').$where;

    $product_list = $db->fetchAll($get_product_list);

    if($product_list)
    {
        foreach ($product_list as $k => $r)
        {
            if (check_purview('pur_product_edit', $_SESSION['purview']))
            {
                $product_list[$k]['operation'] = '<a href="product.php?act=edit&sn=' . $r['product_sn'] . '">编辑</a>';
            } else {
                $product_list[$k]['operation'] = '';
            }
        }
    }

    assign('product_list', $product_list);
}

assign('act', $act);
$smarty->display($template.'product.phtml');