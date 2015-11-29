<?php
/**
 * 会员API
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'login|detail|register|modify|list';
$opera = check_action($operation, getPOST('opera'));

$response = array('errno' => 1, 'errmsg' => '', 'errcontent' => array());

//会员登录
if('login' == $opera)
{
    $account = trim(getPOST('account'));
    $password = trim(getPOST('password'));

    if(empty($account))
    {
        $response['errcontent']['account'] = '请填写会员卡号/手机号码/邮箱';
    } else {
        $account = $db->escape($account);
    }

    if(empty($password))
    {
        $response['errcontent']['password'] = '请填写登录密码';
    } else {
        $password = md5($password.PASSWORD_END);
    }

    if(count($response['errcontent']) == 0 && $response['errmsg'] == '')
    {
        $field = 'account';
        if(is_mobile($account))
        {
            $field = 'mobile';
        } else if(is_email($account)) {
            $field = 'email';
        }

        $get_member = 'select `account`,`password` from '.$db->table('member').' where `'.$field.'`=\''.$account.'\'';
        $member = $db->fetchRow($get_member);

        if($member)
        {
            if($member['password'] == $password)
            {
                $token = '';
                do {
                    $token = md5($member['account'].time());
                    $check_token = 'select `account` from '.$db->table('member_login_logs').' where `token`=\''.$token.'\'';
                } while($db->fetchOne($check_token));

                $member_login_log = array(
                    'account' => $member['account'],
                    'add_time' => time(),
                    'token' => $token
                );

                if($db->autoInsert('member_login_logs', array($member_login_log)))
                {
                    $response['errno'] = 0;
                    $response['token'] = $token;
                } else {
                    $response['errmsg'] = '系统繁忙，请稍后再试';
                }
            } else {
                $response['errcontent']['password'] = '登录密码错误';
            }
        } else {
            $response['errmsg'] = '会员不存在';
        }
    }
}

//会员信息
if('detail' == $opera)
{
    if(check_member_login())
    {
        $account = getPOST('account');
        $with_path = intval(getPOST('with_path'));
        $extends = intval(getPOST('extends'));

        if(empty($account))
        {
            $response['errcontent']['account'] = '账号无效';
        } else {
            $account = $db->escape($account);
        }

        if($response['errmsg'] == '' && count($response['errcontent']) == 0)
        {
            $get_member = 'select `account`,`name`,`wx_nickname`,`mobile`,`email`,`sex`,`add_time`,`balance`,`integral`,`status`,'.
                          '`from`,`integral_await`,`reward`,`reward_await`,`shopping_icon`,`wx_openid`,`wx_unionid`,`wx_headimg`';
            if($with_path)
            {
                $get_member .= ',`recommend_path`,`place_path`';
            }

            if($extends)
            {
                $get_member .= ',`recommend`,`recommend_id`,`place`,`place_id`';
            }

            $get_member .= ',`level_id` from '.$db->table('member').' where `account`=\''.$account.'\'';

            $member = $db->fetchRow($get_member);

            if($member)
            {
                $response['errno'] = 0;
                $response['member'] = $member;
            } else {
                $response['errmsg'] = '会员不存在';
            }
        }
    } else {
        $response['errmsg'] = '请先登录';
    }
}

//会员注册
if('register' == $opera)
{
    $name = getPOST('name');
    $mobile = getPOST('mobile');
    $recommend = getPOST('recommend');
    $place = getPOST('place');
    $wx_openid = getPOST('wx_openid');
    $wx_unionid = getPOST('wx_unionid');
    $level_id = intval(getPOST('level_id'));
    $status = intval(getPOST('status'));
    $password = trim(getPOST('password'));
    $email = getPOST('email');
    $from = getPOST('from');
    $place_area = getPOST('place_area');

    $recommend_info = null;
    $place_info = null;

    if($status <= 0)
    {
        $status = 2;
    }

    if($level_id <= 0)
    {
        $level_id = 1;
    }

    $from = $db->escape($from);

    //会员姓名
    if(empty($name))
    {
        $response['errcontent']['name'] = '请填写会员姓名';
    } else {
        $name = $db->escape($name);
    }

    //手机号码
    if(empty($mobile))
    {
        $mobile = '';
    } else {
        if(is_mobile($mobile))
        {
            $mobile = $db->escape($mobile);
            $check_mobile = 'select `mobile` from '.$db->table('member').' where `mobile`=\''.$mobile.'\'';

            if($db->fetchOne($check_mobile))
            {
                $response['errcontent']['mobile'] = '手机号码已被使用';
            }
        } else {
            $response['errcontent']['mobile'] = '手机号码格式错误';
        }
    }

    //邮箱
    if(empty($email))
    {
        $email = '';
    } else {
        if(is_email($email))
        {
            $email = $db->escape($email);
            $check_email = 'select `email` from '.$db->table('member').' where `email`=\''.$email.'\'';

            if($db->fetchOne($check_email))
            {
                $response['errcontent']['email'] = '邮箱已被使用';
            }
        } else {
            $response['errcontent']['email'] = '邮箱格式错误';
        }
    }

    $member = null;
    if(check_member_login())
    {
        $get_member = 'select `account`,`recommend_path`,`place_path` from '.$db->table('member').' where `account`=\''.$_SESSION['account'].'\'';
        $member = $db->fetchRow($get_member); 
    }

    //推荐人
    if(empty($recommend))
    {
        $recommend = '';
    } else {
        $recommend = $db->escape($recommend);

        $field = 'account';
        if(is_mobile($recommend))
        {
            $field = 'mobile';
        }

        if(is_email($recommend))
        {
            $field = 'email';
        }

        $get_recommend_info = 'select `id`,`account`,`recommend_path` from '.$db->table('member').' where `'.$field.'\'=\''.$recommend.'\'';
        $recommend_info = $db->fetchRow($get_recommend_info);

        if($recommend_info)
        {
            //如果会员已登录，检查是否属于同一个推荐线
            if($member)
            {
                $search_str = $recommend_info['recommend_path'];
                $needle = $member['recommend_path'];

                if(strlen($search_str) < strlen($needle))
                {
                    $search_str = $member['recommend_path'];
                    $needle = $recommend_info['recommend_path'];
                }

                if(strpos($search_str, $needle) === false)
                {
                    $response['errcontent']['recommend'] = '推荐人必须在同一推荐线';
                }
            }
        } else {
            $response['errcontent']['recommend'] = '推荐人不存在';
        }
    }

    //安置人
    if(empty($place))
    {
        $place = '';
    } else {
        $place = $db->escape($place);

        $field = 'account';
        if(is_mobile($place))
        {
            $field = 'mobile';
        }

        if(is_email($place))
        {
            $field = 'email';
        }

        $get_place_info = 'select `id`,`account`,`place_path`,`lchild`,`rchild` from '.$db->table('member').
                          ' where `'.$field.'\'=\''.$place.'\'';
        $place_info = $db->fetchRow($get_place_info);

        if($place_info)
        {
            //如果会员已登录，检查是否属于同一个推荐线
            if($member)
            {
                $search_str = $place_info['place_path'];
                $needle = $member['place_path'];

                if(strlen($search_str) < strlen($needle))
                {
                    $search_str = $member['place_path'];
                    $needle = $recommend_info['place_path'];
                }

                if(strpos($search_str, $needle) === false)
                {
                    $response['errcontent']['place'] = '安置人必须在同一推荐线';
                } else {
                    //检查推荐位置
                    $place_area_list = 'l|r';
                    $place_area = check_action($place_area_list, $place_area, 'l');

                    if(!$place_info[$place_area.'child'] != '')
                    {
                        $response['errcontent']['place'] = '安置位置已满人';
                    }
                }
            }
        } else {
            $response['errcontent']['recommend'] = '安置人不存在';
        }
    }

    //微信opneid
    if(empty($wx_openid))
    {
        $wx_openid = '';
    } else {
        $wx_openid = $db->escape($wx_openid);
    }

    //微信unionid
    if(empty($wx_unionid))
    {
        $wx_unionid = '';
    } else {
        $wx_unionid = $db->escape($wx_unionid);
    }

    //密码
    if(empty($password))
    {
        $password = '';
    } else {
        $password = md5($password.PASSWORD_END);
    }

    if(count($response['errcontent']) == 0 && $response['errmsg'] == '')
    {
        $account = get_account();

        if($account)
        {
            $member_data = array(
                'account' => $account,
                'add_time' => time(),
                'name' => $name,
                'mobile' => $mobile,
                'email' => $email,
                'wx_openid' => $wx_openid,
                'wx_unionid' => $wx_unionid,
                'status' => $status,
                'level_id' => $level_id,
                'from' => $from,
                'password' => $password
            );

            if($recommend_info)
            {
                $member_data['recommend'] = $recommend_info['account'];
                $member_data['recommend_id'] = $recommend_info['id'];
            }

            if($place_info)
            {
                $member_data['place'] = $place_info['account'];
                $member_data['place_id'] = $place_info['id'];
            }

            if($db->autoInsert('member', array($member_data)))
            {
                $member_id = $db->get_last_id();

                $member_path = array();

                if($recommend_info)
                {
                    $member_path['recommend_path'] = $recommend_info['recommend_path'].$member_id.',';
                } else {
                    $member_path['recommend_path'] = $member_id.',';
                }

                if($place_info)
                {
                    $member_path['place_path'] = $place_info['place_path'].$member_id.',';

                    //更新上级的安置位置
                    $place_data = array(
                        $place_area.'child' => $account
                    );

                    $db->autoUpdate('member', $place_data, '`account`=\''.$place_info['account'].'\'');
                } else {
                    $member_path['place_path'] = $member_id.',';
                }

                $db->autoUpdate('member', $member_path, '`account`=\''.$account.'\'');

                $response['account'] = $account;
                $response['errno'] = 0;
            } else {
                //退回卡号
                $account_status = array('status' => 1);
                $db->autoUpdate('card_pool', $account_status, '`account`=\''.$account.'\'');

                $response['errmsg'] = '注册会员失败，请稍后再试';
            }
        } else {
            $response['errmsg'] = '获取会员卡失败，请稍后再试';
        }
    }
}

//会员信息修改
if('modify' == $opera)
{
    $account = getPOST('account');
    $name = getPOST('name');
    $mobile = getPOST('mobile');
    $email = getPOST('email');
    $sex = getPOST('sex');
    $level_id = intval(getPOST('level_id'));
    $status = intval(getPOST('status'));
    $password = trim(getPOST('password'));
    $super_password = trim(getPOST('super_password'));

    $member = array();

    if(empty($account))
    {
        $account = '';
        $response['errcontent']['account'] = '会员账号为空';
    } else {
        $account = $db->escape($account);
    }

    if(!empty($name))
    {
        $name = $db->escape($name);
        $member['name'] = $name;
    }

    if(!empty($mobile))
    {
        if(is_mobile($mobile))
        {
            //检查手机号码是否唯一
            $check_mobile = 'select `account` from '.$db->table('member').' where `mobile`=\''.$mobile.'\' and `account`<>\''.$account.'\'';
            if($db->fetchOne($check_mobile))
            {
                $response['errcontent']['mobile'] = '手机号码已被使用';
            } else {
                $mobile = $db->escape($mobile);
                $member['mobile'] = $mobile;
            }
        } else {
            $response['errcontent']['mobile'] = '手机号码格式错误';
        }
    }

    if(!empty($email))
    {
        if(is_email($email))
        {
            //检查油箱唯一性
            $check_email = 'select `account` from '.$db->table('member').' where `email`=\''.$email.'\' and `account`<>\''.$account.'\'';
            if($db->fetchOne($check_email))
            {
                $response['errcontent']['email'] = '邮箱已被使用';
            } else {
                $email = $db->escape($email);
                $member['email'] = $email;
            }
        }
    }

    if(!empty($sex))
    {
        $sex_list = 'M|F|N';
        $sex = check_action($sex_list, $sex, 'N');

        $member['sex'] = $sex;
    }

    if($level_id > 0)
    {
        $member['level_id'] = $level_id;
    }

    if($status > 0)
    {
        $member['status'] = $status;
    }

    if(!empty($password))
    {
        $member['password'] = md5($password.PASSWORD_END);
    }

    if(!empty($super_password))
    {
        $member['super_password'] = md5($super_password.PASSWORD_END);
    }

    if(count($response['errcontent']) == 0 && $response['errmsg'] == '')
    {
        if(count($member) == 0)
        {
            $response['errmsg'] = '没有信息更新';
        } else {
            if($db->autoUpdate('member', $member, '`account`=\''.$account.'\''))
            {
                $response['errno'] = 0;
                $response['errmsg'] = '会员信息已更新';
            } else {
                $response['errmsg'] = '更新会员信息失败，请稍后再试';
            }
        }
    }
}

//会员列表
if('list' == $opera)
{
    $page = intval(getPOST('page'));
    $step = intval(getPOST('step'));
    $order_by = trim(getPOST('order_by'));
    $order_mode_list = 'ASC|DESC';
    $order_mode = check_action($order_mode_list, getPOST('order_mode'), 'ASC');
    $account = trim(getPOST('account'));
    $begin_time = getPOST('begin_time');
    $end_time = getPOST('end_time');
    $filter = getPOST('filter');

    if($page <= 0)
    {
        $page = 1;
    }

    if($step <= 0)
    {
        $step = 10;
    }

    $where = ' 1 ';
    $order = '';

    if(!empty($account))
    {
        $field = 'account';
        $account = $db->escape($account);

        if(is_mobile($account))
        {
            $field = 'mobile';
        }

        if(is_email($account))
        {
            $field = 'email';
        }

        $where .= ' and `'.$field.'`=\''.$account.'\'';
    }

    if(!empty($begin_time))
    {
        $begin_time = strtotime($begin_time);

        if($begin_time)
        {
            $where .= ' and `add_time`>='.$begin_time;
        } else {
            $response['errcontent']['begin_time'] = '日期格式错误';
        }
    }

    if(!empty($end_time))
    {
        $end_time = strtotime($end_time);

        if($end_time)
        {
            $where .= ' and `end_time`<='.$end_time;
        } else {
            $response['errcontent']['end_time'] = '日期格式错误';
        }
    }

    if(count($filter))
    {
        foreach($filter as $key=>$val)
        {
            $where_tmp = ' and `'.$db->escape($key).'`';
            $values = '';
            if(is_array($val) || is_object($val))
            {
                $where_tmp .= ' in (';
                foreach($val as $v)
                {
                    if(is_array($v) || is_object($v))
                    {
                        continue;
                    }

                    $values .= '\''.$db->escape($v).'\',';
                }
                $values = substr($values, 0, strlen($values)-1);
                $where_tmp .= $values.')';

            } else {
                $values = '\''.$db->escape($val).'\'';
                $where_tmp .= '='.$values;
            }

            $where .= $where_tmp;
        }
    }

    $get_total = 'select count(*) from '.$db->table('member').' where '.$where;
    $total = $db->fetchOne($get_total);

    $total_page = intval($total/$step);
    if($total%$step)
    {
        $total_page++;
    }
    $response['total_page'] = $total_page;

    if($page > $total_page)
    {
        $page = $total_page;
    }

    $limit = ($page - 1)*$step;

    $get_member_list = 'select `id`,`account`,`name`,`wx_nickname`,`add_time`,`level_id`,`balance`,`integral`,`integral_await`,'.
                       '`reward`,`reward_await`,shopping_icon`,`status`,`from`,`mobile`,`email`,`sex`,`recommend`,`place`,`wx_headimg`'.
                       ' from '.$db->table('member').' where '.$where.' order by '.$order.' limit '.$limit.','.$step;

    $member_list = $db->fetchAll($get_member_list);

    if($member_list)
    {
        foreach($member_list as $index=>$member)
        {
            $member_item = array(
                'id' => $member['id'],
                'account' => $member['account'],
                'name' => $member['name'],
                'wx_nickname' => $member['nickname'],
                'add_time' => date('Y-m-d H:i:s', $member['add_time']),
                'level_id' => $member['level_id'],
                'level' => $lang['level'][$member['level_id']],
                'balance' => $member['balance'],
                'integral' => $member['integral'],
                'integral_await' => $member['integral_await'],
                'reward' => $member['reward'],
                'reward_await' => $member['reward_await'],
                'shopping_icon' => $member['shopping_icon'],
                'status' => $lang['member_status'][$member['status']],
                'from' => $member['from'],
                'mobile' => $member['mobile'],
                'email' => $member['email'],
                'sex' => $lang['sex'][$member['sex']],
                'recommend' => $member['recommend'],
                'place' => $member['place'],
                'wx_headimg' => $member['wx_headimg']
            );

            $member_list[$index] = $member_item;
        }

        $response['errno'] = 0;
        $response['member_list'] = $member_list;
    } else {
        $response['errmsg'] = '没有任何记录';
    }
}

echo json_encode($response);
exit;
