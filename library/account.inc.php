<?php
/**
 * 账户相关操作封装
 */

/**
 * 增加会员奖金
 * @param $account
 * @param $reward
 * @param $integral
 * @param $assoc
 * @param $remark
 * @return resource
 */
function add_reward($account, $reward, $integral, $assoc, $remark)
{
    global $db;

    $reward_data = array(
        'account' => $account,
        'reward' => $reward,
        'integral' => $integral,
        'assoc' => $assoc,
        'remark' => $remark,
        'add_time' => time(),
        'status' => 1
    );

    return $db->autoInsert('reward', array($reward_data));
}

/**
 * 增加会员业绩
 * @param $account
 * @param $amount
 * @param $lamount
 * @param $ramount
 * @param $pv_amount
 * @param $number
 * @param int $level_up
 * @return resource
 */
function add_achievement($account, $amount, $lamount, $ramount, $pv_amount, $number, $level_up = 0)
{
    global $db;

    $year = date('Y');
    $month = date('m');

    //如果不存在当月记录，则新增，否则更新
    $get_id = 'select `id` from '.$db->table('achievement').' where `year`='.$year.' and `month`='.$month.' and `account`=\''.$account.'\'';
    $id = $db->fetchOne($get_id);

    if($id)
    {
        $update_achievement = 'update '.$db->table('achievement').' set `amount`=`amount`+'.$amount.',`lamount`=`lamount`+'.$lamount.','.
                              '`ramount`=`ramount`+'.$ramount.',`pv_amount`=`pv_amount`+'.$pv_amount.',`number`=`number`+'.$number;
        if($level_up)
        {
            $update_achievement .= '`level_up`='.$level_up;
        }

        $update_achievement .= ' where `id`='.$id;

        return $db->update($update_achievement);
    } else {
        $achievement_data = array(
            'year' => $year,
            'month' => $month,
            'amount' => $amount,
            'lamount' => $lamount,
            'ramount' => $ramount,
            'pv_amount' => $pv_amount,
            'number' => $number,
            'account' => $account,
            'level_up' => $level_up
        );

        return $db->autoInsert('achievement', array($achievement_data));
    }
}

/**
 * 会员账户变动
 * @param $account
 * @param $balance
 * @param $reward
 * @param $reward_await
 * @param $integral
 * @param $integral_await
 * @param $shopping_icon
 * @param $operator
 * @param $type
 * @param string $remark
 * @return bool
 */
function member_account_change($account, $balance, $reward, $reward_await, $integral, $integral_await, $shopping_icon, $operator, $type, $remark = '')
{
    global $db;

    $update_user = 'update '.$db->table('member').' set `balance`=`balance`+'.$balance.',`reward`=`reward`+'.$reward.','.
                   '`reward_await`=`reward_await`+'.$reward_await.',`integral`=`integral`+'.$integral.','.
                   '`integral_await`=`integral_await`+'.$integral_await.',`shopping_icon`=`shopping_icon`+'.$shopping_icon.
                   ' where `account`=\''.$account.'\'';

    if($db->update($update_user))
    {
        $account_data = array(
            'account' => $account,
            'balance' => $balance,
            'reward' => $reward,
            'reward_await' => $reward_await,
            'integral' => $integral,
            'integral_await' => $integral_await,
            'shopping_icon' => $shopping_icon,
            'add_time' => time(),
            'remark' => $remark,
            'operator' => $operator,
            'type' => $type
        );

        $db->autoInsert('account', array($account_data));
        return true;
    } else {
        return false;
    }
}

/**
 * 新增充值申请
 * @param $account
 * @param $amount
 * @param $payment_id
 * @param $payment_name
 * @param $payment_code
 * @param string $remark
 * @return bool|string
 */
function add_recharge($account, $amount, $payment_id, $payment_name, $payment_code, $remark = '')
{
    global $db;
    global $config;

    $recharge_sn = '';

    $recharge_data = array(
        'account' => $account,
        'amount' => $amount,
        'real_amount' => $amount,
        'status' => 1,
        'remark' => $remark,
        'add_time' => time(),
        'payment_id' => $payment_id,
        'payment_name' => $payment_name,
        'payment_code' => $payment_code
    );

    do
    {
        $recharge_sn = 'R'.time().rand(100, 999);
        $check_recharge = 'select `recharge_sn` from '.$db->table('withdraw').' where `recharge_sn`=\''.$recharge_sn.'\'';
    } while($db->fetchOne($check_recharge));

    $recharge_data['recharge_sn'] = $recharge_sn;

    if($db->autoInsert('recharge', array($recharge_data)))
    {
        add_recharge_log($recharge_sn, $account, 0, 1, '会员申请充值');
        return $recharge_sn;
    } else {
        return false;
    }
}

/**
 * 添加充值日志
 * @param $recharge_sn
 * @param $operator
 * @param $from
 * @param $to
 * @param string $remark
 * @return bool
 */
function add_recharge_log($recharge_sn, $operator, $from, $to, $remark = '')
{
    global $db;

    $log_data = array(
        'recharge_sn' => $recharge_sn,
        'operator' => $operator,
        'from' => $from,
        'to' => $to,
        'add_time' => time(),
        'remark' => $remark
    );

    return $db->autoInsert('recharge_log', array($log_data));
}

/**
 * 新增提现记录
 * @param $account
 * @param $amount
 * @param $bank_id
 * @param string $remark
 * @return bool|string
 */
function add_withdraw($account, $amount, $bank_id, $remark = '')
{
    global $db;
    global $config;

    $withdraw_sn = '';

    $fee = $amount * $config['fee_rate'];
    $withdraw_data = array(
        'account' => $account,
        'amount' => $amount,
        'fee' => $fee,
        'real_amount' => $amount - $fee,
        'status' => 1,
        'remark' => $remark,
        'bank_id' => $bank_id,
        'add_time' => time()
    );

    $get_bank_info = 'select `bank_name`,`bank_account`,`bank_card` from '.$db->table('bank').' where `id`='.$bank_id;
    $bank_info = $db->fetchRow($get_bank_info);

    if($bank_info)
    {
        $withdraw_data = array_merge($withdraw_data, $bank_info);
    } else {
        return false;
    }

    do
    {
        $withdraw_sn = 'W'.time().rand(100, 999);
        $check_withdraw = 'select `withdraw_sn` from '.$db->table('withdraw').' where `withdraw_sn`=\''.$withdraw_sn.'\'';
    } while($db->fetchOne($check_withdraw));

    $withdraw_data['withdraw_sn'] = $withdraw_sn;

    if($db->autoInsert('withdraw', array($withdraw_data)))
    {
        add_withdraw_log($withdraw_sn, $account, 0, 1, '会员申请提现');
        return $withdraw_sn;
    } else {
        return false;
    }
}

/**
 * 添加提现日志
 * @param $withdraw_sn
 * @param $operator
 * @param $from
 * @param $to
 * @param string $remark
 * @return bool
 */
function add_withdraw_log($withdraw_sn, $operator, $from, $to, $remark = '')
{
    global $db;

    $log_data = array(
        'withdraw_sn' => $withdraw_sn,
        'operator' => $operator,
        'from' => $from,
        'to' => $to,
        'add_time' => time(),
        'remark' => $remark
    );

    return $db->autoInsert('withdraw_log', array($log_data));
}