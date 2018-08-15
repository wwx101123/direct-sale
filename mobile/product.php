<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/9/7
 * Time: 上午11:22
 */
include 'library/init.inc.php';

$operation = 'collection|distribution|delete_history|discount';

$opera = check_action($operation, getPOST('opera'));

//产品砍价
if('discount' == $opera)
{
    $get_parent_account = 'select `account` from '.$db->table('member').' where `id`='.$_SESSION['parent_id'];
    $parent_account = $db->fetchOne($get_parent_account);

    $product_sn = getPOST('product_sn');

    $response = array('error'=>1, 'msg'=>'');

    if(!check_cross_domain() && !empty($_SESSION['account']))
    {
        if($product_sn != '' && $_SESSION['account'] != $parent_account)
        {
            $product_sn = $db->escape($product_sn);

            $get_product = 'select `lowest_price`,`price` from '.$db->table('product').' where `product_sn`=\''.$product_sn.'\'';
            $product = $db->fetchRow($get_product);

            //获取产品砍价总额
            $get_product_discount = 'select sum(`reduce`) from '.$db->table('discount').
                ' where `product_sn`=\''.$product_sn.'\' and `owner`=\''.$parent_account.'\'';

            $discount = $db->fetchOne($get_product_discount);
            $product['price'] -= $discount;

            $reduce = rand(0, $product['price'] - $product['lowest_price']);

            if($product['price'] - $product['lowest_price'])
            {
                $discount_data = array(
                    'owner' => $parent_account,
                    'account' => $_SESSION['account'],
                    'reduce' => $reduce,
                    'add_time' => time(),
                    'product_sn' => $product_sn
                );

                $db->autoInsert('discount', array($discount_data));
                $response['error'] = 0;
                $response['msg'] = '砍价成功';
            } else {
                $response['msg'] = '店家已经血本无归了，请不要再砍价了';
            }
        } else {
            if($_SESSION['account'] == $parent_account)
            {
                $response['msg'] = '自己不能给自己砍价';
            } else {
                $response['msg'] = '000:参数错误';
            }
        }
    } else {
        if(empty($_SESSION['account']))
        {
            $response['msg'] = '请先登录';
            $response['error'] = 2;
        } else {
            $response['msg'] = '404:参数错误';
        }
    }

    echo json_encode($response);
    exit;
}
//我的足迹
if('delete_history' == $opera)
{
    $product_sn = getPOST('product_sn');

    $response = array('error'=>1, 'msg'=>'');

    if(!check_cross_domain() && !empty($_SESSION['account']))
    {
        if($product_sn != '')
        {
            $product_sn = $db->escape($product_sn);

            $delete_history = 'delete from '.$db->table('history').' where `account`=\''.$_SESSION['account'].'\' and `product_sn`=\''.$product_sn.'\'';
            if($db->delete($delete_history))
            {
                $response['error'] = 0;
                $response['msg'] = '删除足迹成功';
            } else {
                $response['msg'] = '001:系统繁忙，请稍后再试';
            }
        } else {
            $response['msg'] = '000:参数错误';
        }
    } else {
        if(empty($_SESSION['account']))
        {
            $response['msg'] = '请先登录';
            $response['error'] = 2;
        } else {
            $response['msg'] = '404:参数错误';
        }
    }

    echo json_encode($response);
    exit;
}
//产品分销
if('distribution' == $opera)
{
    $product_sn = getPOST('product_sn');

    $response = array('error'=>1, 'msg'=>'');

    if(!check_cross_domain() && !empty($_SESSION['account']))
    {
        if($product_sn != '')
        {
            $product_sn = $db->escape($product_sn);
            //检查产品的收藏状态
            $get_distribution = 'select `product_sn` from '.$db->table('distribution').
                ' where `account`=\''.$_SESSION['account'].'\' and `product_sn`=\''.$product_sn.'\'';
            $distribution_flag = $db->fetchOne($get_distribution) ? true : false;

            if($distribution_flag)
            {
                if(cancel_distribution_product($_SESSION['account'], $product_sn))
                {
                    $response['error'] = 0;
                    $response['status'] = !$distribution_flag;
                    $response['msg'] = '取消分销成功';
                } else {
                    $response['msg'] = '001:系统繁忙，请稍后再试';
                }
            } else {
                if(distribution_product($_SESSION['account'], $product_sn))
                {
                    $response['error'] = 0;
                    $response['status'] = !$distribution_flag;
                    $response['msg'] = '分销产品成功';
                } else {
                    $response['msg'] = '001:系统繁忙，请稍后再试';
                }
            }
        } else {
            $response['msg'] = '000:参数错误';
        }
    } else {
        if(empty($_SESSION['account']))
        {
            $response['msg'] = '请先登录';
            $response['error'] = 2;
        } else {
            $response['msg'] = '404:参数错误';
        }
    }

    echo json_encode($response);
    exit;
}
//产品收藏
if('collection' == $opera)
{
    $product_sn = getPOST('product_sn');

    $response = array('error'=>1, 'msg'=>'');

    if(!check_cross_domain() && !empty($_SESSION['account']))
    {
        if($product_sn != '')
        {
            $product_sn = $db->escape($product_sn);
            //检查产品的收藏状态
            $get_collection = 'select `product_sn` from '.$db->table('collection').
                              ' where `account`=\''.$_SESSION['account'].'\' and `product_sn`=\''.$product_sn.'\'';
            $collection_flag = $db->fetchOne($get_collection) ? true : false;

            if($collection_flag)
            {
                if(cancel_collection_product($_SESSION['account'], $product_sn))
                {
                    $response['error'] = 0;
                    $response['status'] = !$collection_flag;
                    $response['product_sn'] = $product_sn;
                    $response['msg'] = '取消收藏成功';
                } else {
                    $response['msg'] = '001:系统繁忙，请稍后再试';
                }
            } else {
                if(collection_product($_SESSION['account'], $product_sn))
                {
                    $response['error'] = 0;
                    $response['status'] = !$collection_flag;
                    $response['product_sn'] = $product_sn;
                    $response['msg'] = '收藏产品成功';
                } else {
                    $response['msg'] = '001:系统繁忙，请稍后再试';
                }
            }
        } else {
            $response['msg'] = '000:参数错误';
        }
    } else {
        if(empty($_SESSION['account']))
        {
            $response['msg'] = '请先登录';
            $response['error'] = 2;
        } else {
            $response['msg'] = '404:参数错误';
        }
    }

    echo json_encode($response);
    exit;
}

$id = intval(getGET('id'));

if($id <= 0)
{
    redirect('index.php');
}

$get_member_info = 'select * from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
$member_info = $db->fetchRow($get_member_info);

$get_product = 'select * from '.$db->table('product').' where `status`=1 and `id`='.$id;

$product = $db->fetchRow($get_product);

if($product)
{
    $product_sn = $product['product_sn'];
    $product['gallery'] = array($product['img']);

    $get_price_list = 'select `price`,`level_id`,`min_number` from '.$db->table('price_list').' where `product_sn`=\''.$product_sn.'\'';

    $price_list = $db->fetchAll($get_price_list);

    $price_list_json = array();
    $price = $product['price'];
    if($price_list)
    {
        foreach($price_list as $pc)
        {
            if($pc['level_id'] >= $member_info['level_id'])
            {
                $pc['price'] = floatval($pc['price']);
                $pc['min_number'] = intval($pc['min_number']);
                $price_list_json[$pc['level_id']] = $pc;
            }

            if($pc['level_id'] == $member_info['level_id'])
            {
                $price = $pc['price'];
            }
        }
    }

    $product_list_json[intval($product['id'])] = array(
        'product_sn' => $product_sn,
        'name' => $product['name'],
        'price' => floatval($price),
        'pv' => floatval($product['pv']),
        'number' => 0,
        'img' => $product['img'],
        'price_list' => $price_list_json
    );

    $product_list[$product['id']]['price_list'] = $price_list_json;
    $product_list[$product['id']]['price'] = $price;
    assign('product_list', $product_list);
    assign('product_list_json', json_encode($product_list_json));

    //分享链接
    $recommend_url = 'http://'.$config['mobile_domain'].'/product.php?id='.$id;
    if(isset($_SESSION['account'])) {
        $recommend_url .= '&ukey='.$member_info['id'];
    }
    assign('recommend_url', $recommend_url);
} else {
    redirect('index.php');
}

assign('product', $product);
$smarty->display('product.phtml');