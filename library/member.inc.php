<?php
/**
 * 会员操作封装
 * @author winsen
 * @version 1.0.0
 */

/**
 * 注册会员信息
 * @param string $mobile
 * @param string $name
 * @param string $password
 * @param int $parent_id
 * @return string 会员账号
 */
function register_mobile($mobile, $name, $password, $parent_id = 0)
{
    global $db;
    global $log;

    $db->begin();
    $member_data = array(
        'account' => get_account(),
        'mobile' => $mobile,
        'password' => md5($password.PASSWORD_END),
        'super_password' => md5($password.PASSWORD_END),
        'add_time' => time(),
        'recommend_id' => $parent_id,
        'name' => $name,
        'level_id' => 1
    );

    if($db->autoInsert('member', array($member_data)))
    {
        $update_data = array();

        $path = '';
        $id = $db->get_last_id();

        if($parent_id)
        {
            $get_parent = 'select `recommend_path`,`account` from '.$db->table('member').' where `id`='.$parent_id;
            $parent = $db->fetchRow($get_parent);
            $path = $parent['recommend_path'];
            $update_data['recommend'] = $parent['account'];
        }

        $path .= $id.',';

        $update_data['recommend_path'] = $path;

        if($db->autoUpdate('member', $update_data, '`id`='.$id))
        {
            $log->record_array($member_data);
            $db->commit();
            return $member_data['account'];
        }
    }

    $db->rollback();
    return false;
}

/**
 * 注册会员
 * @param string $openid
 * @param string $nickname
 * @return string 会员账号
 */
function register_openid($openid, $nickname, $parent_id = 0)
{
    global $db;
    global $log;

    $db->begin();
    $member_data = array(
        'account' => get_account(),
        'wx_openid' => $openid,
        'wx_nickname' => $nickname,
        'name' => $nickname,
        'add_time' => time(),
        'recommend_id' => $parent_id,
        'level_id' => 1
    );

    if($db->autoInsert('member', array($member_data)))
    {
        $update_data = array();

        $path = '';
        $id = $db->get_last_id();

        if($parent_id)
        {
            $get_parent = 'select `recommend_path`,`account` from '.$db->table('member').' where `id`='.$parent_id;
            $parent = $db->fetchRow($get_parent);
            $path = $parent['recommend_path'];
            $update_data['recommend'] = $parent['account'];
        }

        $path .= $id.',';

        $update_data['recommend_path'] = $path;

        if($db->autoUpdate('member', $update_data, '`id`='.$id))
        {
            $log->record_array($member_data);
            $db->commit();
            return $member_data['account'];
        }
    } else {
        $log->record('register member fail');
    }

    $db->rollback();
    return false;
}

/**
 * 验证密码
 * @param $account
 * @param $password
 * @return bool
 */
function verify_password($account, $password)
{
    global $db;

    $get_user_password = 'select `password` from '.$db->table('member').' where `account`=\''.$account.'\'';
    $user_password = $db->fetchOne($get_user_password);

    $password = md5($password.PASSWORD_END);

    return $password == $user_password;
}

/**
 * 验证超级密码
 * @param $account
 * @param $password
 * @return bool
 */
function verify_super_password($account, $password)
{
    global $db;

    $get_user_password = 'select `super_password` from '.$db->table('member').' where `account`=\''.$account.'\'';
    $user_password = $db->fetchOne($get_user_password);

    $password = md5($password.PASSWORD_END);

    return $password == $user_password;
}

/**
 * 判断会员是否已登录
 * @return boolean
 */
function check_member_login()
{
    return isset($_SESSION['account']) && !empty($_SESSION['account']);
}

/**
 * 获取会员卡号
 * @param string $prefix
 * @param int $begin
 * @param int $max
 * @param int $step
 * @return string
 */
function get_account($prefix = 'DS', $begin = 1, $max = 999999, $step = 100)
{
    global $db;
    global $log;

    //1、如果卡号池为空，则从$begin开始填充卡号池，每次填充步长为$step，最大不超过$max
    $check_card_pool = 'select count(*) from '.$db->table('card_pool');
    if(!$db->fetchOne($check_card_pool))
    {
        $range_max = ($begin + $step) < $max ? ($begin + $step) : $max;

        $range_data = range($begin, $range_max);
        shuffle($range_data);

        $card_fill_data = array();
        foreach($range_data as $account)
        {
            $account = account_fill_zero($account, strlen($max));
            $card_fill_data[] = array('account'=>$prefix.$account);
        }

        $db->autoInsert('card_pool', $card_fill_data);
    }

    //2、取一个可用的卡号
    $get_account = 'select `account` from '.$db->table('card_pool').' where `status`=1 order by `id`';
    $account = $db->fetchOne($get_account);

    if($account)
    {
        //将取到的卡号状态设置为不可用
        $card_pool_data = array('status'=>0);

        if(!$db->autoUpdate('card_pool', $card_pool_data, '`account`=\''.$account.'\' and `status`=1')) {
            return '';
        }
    }

    //3、检查剩余卡号池，如果少于$step，则从当前卡号池最大数+1开始，按步长$step，填充卡号池
    $get_card_left = 'select count(*) from '.$db->table('card_pool').' where `status`=1';
    $card_left = $db->fetchOne($get_card_left);

    if($card_left < $step)
    {
        $get_current_max = 'select max(`account`) from '.$db->table('card_pool');
        $current_max = $db->fetchOne($get_current_max);

        $current_max = str_replace($prefix, '', $current_max);
        $current_max = intval($current_max);
        $current_max++;

        $range_max = ($current_max + $step) < $max ? ($current_max + $step) : $max;

        $range_data = range($current_max, $range_max);
        shuffle($range_data);

        $card_fill_data = array();
        foreach($range_data as $_account)
        {
            $_account = account_fill_zero($_account, strlen($max));
            $card_fill_data[] = array('account'=>$prefix.$_account);
        }

        $db->autoInsert('card_pool', $card_fill_data);
    }

    return $account;
}

/**
 * 卡号补零
 * @param int $account
 * @param int $length
 * @return string
 */
function account_fill_zero($account, $length)
{
    $length -= strlen($account);

    while($length--)
    {
        $account = '0'.$account;
    }

    return $account;
}
