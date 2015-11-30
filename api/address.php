<?php
/**
 * 地址管理API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'add|edit|delete|get_info|select';
$opera = check_action($operation, getPOST('opera'));

$response = array('errno'=>1, 'errmsg'=>'', 'errcontent'=>array());

if('select' == $opera)
{
    $address_id = intval(getPOST('address_id'));

    if(!check_cross_domain())
    {
        if($address_id > 0)
        {
            $_SESSION['address_id'] = $address_id;
            $response['errno'] = 0;
        }
    } else {
        $response['errmsg'] = '404:参数错误';
    }
}

if('delete' == $opera)
{
    $id = getPOST('eid');

    $id = intval($id);
    if($id <= 0)
    {
        $response['errmsg'] = '-参数错误<br/>';
    }

    if($response['errmsg'] == '')
    {
        //检查如果地址是默认地址，则修改默认地址到下一个地址
        $check_default = 'select `is_default` from '.$db->table('address').' where `id`='.$id.' and `account`=\''.$_SESSION['account'].'\'';

        $is_default = $db->fetchOne($check_default);

        if($db->autoDelete('address', '`id`='.$id.' and `account`=\''.$_SESSION['account'].'\''))
        {
            if($is_default)
            {
                $data = array(
                    'is_default' => 1
                );

                $db->autoUpdate('address', $data, '`account`=\''.$_SESSION['account'].'\'', '', 1);
            }
            $response['errno'] = 0;
            $response['errmsg'] = '删除收货地址成功';
        } else {
            $response['errmsg'] = '001:系统繁忙，请稍后再试';
        }
    }
}

if('add' == $opera)
{
    $province = intval(getPOST('province'));
    $city = intval(getPOST('city'));
    $district = intval(getPOST('district'));
    $address = getPOST('address');
    $is_default = getPOST('is_default');
    $mobile = getPOST('phone');
    $consignee = getPOST('consignee');

    if(!check_cross_domain())
    {
        if($province <= 0)
        {
            $response['errmsg'] .= "-请选择省份\n";
        }

        if($city <= 0)
        {
            $response['errmsg'] .= "-请选择城市\n";
        }

        if($district <= 0)
        {
            $response['errmsg'] .= "-请选择地区\n";
        }

        if($address == '')
        {
            $response['errmsg'] .= "-请填写街道地址\n";
        } else {
            $address = $db->escape($address);
        }

        if(!is_mobile($mobile))
        {
            $response['errmsg'] .= "-手机号码格式错误";
        } else {
            $mobile = $db->escape($mobile);
        }

        if($is_default == 'true')
        {
            $is_default = 1;
        } else {
            $is_default = 0;
        }

        if($response['errmsg'] == '')
        {
            if($is_default)
            {
                $db->autoUpdate('address', array('is_default'=>0), '`account`=\''.$_SESSION['account'].'\'');
            } else {
                //检查用户地址如果为空则默认为默认地址
                $check_address = 'select count(*) from '.$db->table('address').' where `account`=\''.$_SESSION['account'].'\'';

                $address_count = intval($db->fetchOne($check_address));

                if($address_count == 0)
                {
                    $is_default = 1;
                }
            }

            $address_data = array(
                'province' => $province,
                'city' => $city,
                'district' => $district,
                'address' => $address,
                'phone' => $mobile,
                'consignee' => $consignee,
                'is_default' => $is_default,
                'account' => $_SESSION['account']
            );

            if($db->autoInsert('address', array($address_data)))
            {
                $response['errno'] = 0;
                $response['errmsg'] = '新增收货地址成功';
                $response['id'] = $db->get_last_id();
                if($is_default)
                {
                    $_SESSION['address_id'] = $response['id'];
                }
            } else {
                $response['errmsg'] = '001:系统繁忙，请稍后再试';
            }
        }
    } else {
        $response['errmsg'] = '404:参数错误';
    }
}

if('edit' == $opera)
{
    $province = intval(getPOST('province'));
    $city = intval(getPOST('city'));
    $district = intval(getPOST('district'));
    $address = getPOST('address');
    $is_default = getPOST('is_default');
    $mobile = getPOST('phone');
    $consignee = getPOST('consignee');
    $id = intval(getPOST('address_id'));

    if(!check_cross_domain())
    {
        if($id <= 0)
        {
            $response['errmsg'] .= "403:参数错误\n";
        }

        if($province <= 0)
        {
            $response['errmsg'] .= "-请选择省份\n";
        }

        if($city <= 0)
        {
            $response['errmsg'] .= "-请选择城市\n";
        }

        if($district <= 0)
        {
            $response['errmsg'] .= "-请选择地区\n";
        }

        if($address == '')
        {
            $response['errmsg'] .= "-请填写街道地址\n";
        } else {
            $address = $db->escape($address);
        }

        if(!is_mobile($mobile))
        {
            $response['errmsg'] .= "-手机号码格式错误";
        } else {
            $mobile = $db->escape($mobile);
        }

        if($is_default == 'true')
        {
            $is_default = 1;
        } else {
            $is_default = 0;
        }

        if($response['errmsg'] == '')
        {
            if($is_default)
            {
                $db->autoUpdate('address', array('is_default'=>0), '`account`=\''.$_SESSION['account'].'\'');
            } else {
                //检查用户地址如果为空则默认为默认地址
                $check_address = 'select count(*) from '.$db->table('address').' where `account`=\''.$_SESSION['account'].'\'';

                $address_count = $db->fetchOne($check_address);
                $address_count = intval($address_count);

                if($address_count == 1)
                {
                    $is_default = 1;
                }
            }

            $address_data = array(
                'province' => $province,
                'city' => $city,
                'district' => $district,
                'address' => $address,
                'phone' => $mobile,
                'consignee' => $consignee,
                'is_default' => $is_default
            );

            if($db->autoUpdate('address', $address_data, '`id`='.$id.' and `account`=\''.$_SESSION['account'].'\''))
            {
                $response['errno'] = 0;
                $response['errmsg'] = '收货地址修改成功';
                $response['id'] = $db->get_last_id();
                if($is_default)
                {
                    $_SESSION['address_id'] = $response['id'];
                }
            } else {
                $response['errmsg'] = '001:系统繁忙，请稍后再试';
            }
        }
    } else {
        $response['errmsg'] = '404:参数错误';
    }
}

if('get_info' == $opera)
{
    $id = intval(getPOST('address_id'));

    if(!check_cross_domain())
    {
        $get_address_detail = 'select p.`province_name`,c.`city_name`,d.`district_name`,a.`address`,a.`consignee`,'.
            'a.`phone`,a.`zipcode`,a.`id` from '.$db->table('address').' as a, '.$db->table('province').' as p, '.
            $db->table('city').' as c, '.$db->table('district').' as d where '.
            'a.`province`=p.`id` and a.`city`=c.`id` and a.`district`=d.`id` and a.`id`='.$id.
            ' and a.`account`=\''.$_SESSION['account'].'\'';

        $address_info = $db->fetchRow($get_address_detail);

        if($address_info)
        {
            $response['errno'] = 0;
            $response['address'] = $address_info['province_name'].' '.$address_info['city_name'].' '.$address_info['district_name'].' '.
                                   ' '.$address_info['address'];
            $response['consignee'] = $address_info['consignee'];
            $response['phone'] = $address_info['phone'];
            $response['zipcode'] = $address_info['zipcode'];
            $response['id'] = $address_info['id'];
        } else {
            $response['errmsg'] = '000:参数错误';
        }
    } else {
        $response['errmsg'] = '404:参数错误';
    }
}

echo json_encode($response);
exit;
