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

$action = 'edit|add|view|delete|detail|price';
$operation = 'edit|add|delete|price';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));

//===========================================================================
if($opera == 'price')
{
    $response = array('error' => 1, 'msg' => '', 'errmsg' => array());

    $product_sn = trim(getPOST('sn'));
    $eid = intval(getPOST('id'));
    $number = intval(getPOST('number'));
    $level_id = intval(getPOST('level_id'));
    $price = floatval(getPOST('price'));

    if(empty($product_sn))
    {
        $response['msg'] = '参数错误';
    } else {
        $product_sn = $db->escape($product_sn);
    }

    if($level_id <= 0)
    {
        $response['msg'] = '参数错误';
    }

    if($price <= 0)
    {
        $response['errmsg']['price-'.$level_id] = '-价格不能小于0';
    }

    if($response['msg'] == '' && count($response['errmsg']) == 0)
    {
        if($eid)
        {
            $price_list_data = array(
                'price' => $price,
                'min_number' => $number
            );

            if($db->autoUpdate('price_list', $price_list_data, '`id`='.$eid))
            {
                $response['error'] = 0;
                $response['msg'] = '修改价格表成功';
            } else {
                $response['msg'] = '系统繁忙，请稍后再试';
            }
        } else {
            $get_product_id = 'select `id` from '.$db->table('product').' where `product_sn`=\''.$product_sn.'\'';
            $price_list_data = array(
                'product_sn' => $product_sn,
                'product_id' => $db->fetchOne($get_product_id),
                'price' => $price,
                'min_number' => $number,
                'level_id' => $level_id
            );

            if($db->autoInsert('price_list', array($price_list_data)))
            {
                $response['error'] = 0;
                $response['msg'] = '新增价格成功';
            } else {
                $response['msg'] = '系统繁忙，请稍后再试';
            }
        }
    }

    echo json_encode($response);
    exit;
}

if($opera == 'add')
{
    $name = getPOST('name');
    $price = floatval(getPOST('price'));
    $pv = floatval(getPOST('pv'));
    $img = getPOST('img');
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

    if($pv < 0)
    {
        show_system_message('产品pv不能为负数');
    }

    $desc = $db->escape($desc);

    $data = array(
        'name' => $name,
        'price' => $price,
        'pv' => $pv,
        'status' => $status,
        'desc' => $desc,
        'category_id' => $category_id,
        'inventory' => $inventory,
        'img' => $img
    );

    $product_sn = '';
    do
    {
        $product_sn = 'DS'.rand(10000, 99999);
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
    $pv = floatval(getPOST('pv'));
    $img = getPOST('img');
    $pv_given = floatval(getPOST('pv_given'));
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

    if($pv < 0)
    {
        show_system_message('产品积分不能为负数');
    }

    if($pv_given < 0)
    {
        show_system_message('赠送积分不能为负数');
    }

    $desc = $db->escape($desc);

    $data = array(
        'name' => $name,
        'price' => $price,
        'pv' => $pv,
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

if('delete' == $act)
{
    $product_sn = getGET('sn');

    if($product_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $product_sn = $db->escape($product_sn);
    }

    if($db->autoDelete('product', '`product_sn`=\''.$product_sn.'\''))
    {
        show_system_message('产品删除成功');
    } else {
        show_system_message('系统繁忙，请稍后再试');
    }
}

if('price' == $act)
{
    $product_sn = getGET('sn');

    if($product_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $product_sn = $db->escape($product_sn);
    }

    $get_price_list = 'select * from '.$db->table('price_list').' where `product_sn`=\''.$product_sn.'\' order by `level_id`';
    $price_list = $db->fetchAll($get_price_list);

    $price_list_json = array();
    if($price_list)
    {
        foreach($price_list as $index=>$price)
        {
            $price_list_json[$price['level_id']] = $price;
        }
    }

    assign('price_list', $price_list_json);
    assign('price_list_json', json_encode($price_list_json));
    assign('product_sn', $product_sn);
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
                $product_list[$k]['operation'] .= ' | <a href="product.php?act=price&sn=' . $r['product_sn'] . '">价格表</a>';
                $product_list[$k]['operation'] .= ' | <a href="product.php?act=delete&sn=' . $r['product_sn'] . '" onclick="return confirm(\'您确定要删除该产品？\');">删除</a>';
            } else {
                $product_list[$k]['operation'] = '';
            }
        }
    }

    assign('product_list', $product_list);
}

assign('act', $act);
$smarty->display($template.'product.phtml');