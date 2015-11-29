<?php
/**
 * 会员操作封装
 * @author winsen
 * @version 1.0.0
 */


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
function get_account($prefix = 'DS', $begin = 1, $max = 999999, $step = 500)
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

        $db->autoUpdate('card_pool', $card_pool_data, '`account`=\''.$account.'\'');
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
        foreach($range_data as $account)
        {
            $account = account_fill_zero($account, strlen($max));
            $card_fill_data[] = array('account'=>$prefix.$account);
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
