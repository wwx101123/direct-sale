<?php
/**
 * 账户相关操作封装
 */

/**
 * 结算
 * @param $order_sn
 * @return void
 */
function settle($order_sn) {
    global $db, $log, $config;

    $increment = 100; // 新增业绩
    $account = ''; // 新增用户账号
    $order_sn = ''; // 订单编号

    //获取下单用户信息
    $member = DB::table('member')->where('account', $account)->first();
    $recommend_path = explode(',', $member['recommend_path']);
    array_pop($recommend_path);

    //获取推荐线上所有用户(含自己)
    $parents = DB::table('member')->whereExists(function($query) use ($recommend_path) {
        $query->select('m.id')
            ->from('member as m')
            ->whereRaw('m.account = member.account')
            ->whereIn('m.id', $recommend_path);
    })->orderBy('recommend_path', 'desc');

    //如推荐线上用户不存在当月业绩则创建
    foreach($parents as $node) {
        $achievement = DB::table('achievement')
            ->select()
            ->where('account', $node['account'])
            ->where('monthly', date('Ym'))
            ->first();

        //如业绩不存在则新建
        if (empty($achievement)) {
            $achievement = [
                'member_id' => $node['id'], // 会员ID
                'monthly' => date('Ym'), // 年月
                'account' => $node['account'], // 会员账号
                'increment' => 0, // 当月新增业绩
                'children' => $node['children'], // 直推结点数
                'dividend_gold' => 0, // 分红达标点数
                'sub_dividend_gold' => 0, // 推荐人当前市场已达到分红标准
                'sub_increment' => 0, // 推荐人小市场业绩新增
                'recommend_path' => $node['recommend_path'], // 推荐关系
                'recommend_id' => $node['recommend_id'] // 直推人
            ];

            DB::insert($achievement);
        }
    }
    //移除当前用户
    array_shift($parents);

    $layer = 0; // 当前代数
    $reach_service_node = false; // 是否已到达服务点
    $reward_list = [];
    $member_reward_await = [];
    while($node = array_shift($parents)) {
        if(!isset($member_reward_await[$node['account']])) {
            $member_reward_await[$node['account']] = 0;
        }

        $reward = 0;
        $reward_type = 0;
        $reward_rate = 0;

        $layer++;
        if(!$reach_service_node && $node['level_id'] == 5) {
            //服务点业绩提成
            $reward = round($increment * $this->server_reward_rate, 2);
            $reward_type = 3; // 业绩提成
            $reward_rate = $this->server_reward_rate;

            $reach_service_node = true;
        }

        //金星、银星享受三代内推荐奖
        $can_get_recommend_reward = $layer <= 3 && $node['level_id'] >= 3 && $node['level_id'] <= 4;
        //铜星享受二代内推荐奖
        $can_get_recommend_reward |= $layer <= 2 && $node['level_id'] == 2;
        //普通会员享受一代推荐奖
        $can_get_recommend_reward |= $layer == 1 && $node['level_id'] == 1;

        if($can_get_recommend_reward) {
            //推荐奖计算
            $reward = round($increment * $this->recommend_reward_rate, 2);
            $reward_type = 1; // 推荐奖
            $reward_rate = $this->recommend_reward_rate;
        }

        //金星、银星享受三代内管理奖
        $can_get_manager_reward = $layer <= 3 && $node['level_id'] >= 3 && $node['level_id'] <= 4;
        //铜星享受二代内管理奖
        $can_get_manager_reward |= $layer <= 2 && $node['level_id'] == 2;

        if($can_get_manager_reward) {
            //管理奖计算
            $reward = round($increment * $this->manager_reward_rate, 2);
            $reward_type = 2; // 管理奖
            $reward_rate = $this->manager_reward_rate;
        }

        if($reward_type > 0) {
            $member_reward_await[$node['account']] += $reward;

            array_push($reward_list, [
                'account' => $node['account'], // 会员账号
                'rate' => $reward_rate, // 系数
                'reward_base' => $increment, // 业绩
                'reward' => $reward, // 金额
                'settle_time' => time(), // 结算时间
                'assoc_order_sn' => $order_sn, // 关联订单
                'status' => 0, // 状态： 0 - 待发，1 - 已发，2 - 完成，3 - 回退
                'daily_reward' => round($reward * $this->daily_reward_rate, 2), // 日发奖金金额,
                'day_await' => 50, // 待发天数
                'type' => $reward_type, // 奖金类型
            ]);
        }

        if($layer > 3 && $reach_service_node) {
            break;
        }
    }

    //插入奖金表
    if(count($reward_list)) {
        if(DB::insert($reward_list)) {
            Log::debug('settlement success');
            //更新用户待发奖金
            $member_trade_log = []; // 用户流水记录
            foreach($member_reward_await as $member_account => $reward_await) {
                if($reward_await > 0) {
                    $flag = DB::table('member')->where('account', $member_account)->increment('reward_await', $reward_await);

                    if($flag) {
                        foreach($reward_list as $reward_record) {
                            if($reward_record['account'] == $member_account) {
                                array_push($member_trade_log, [
                                    'account' => $member_account,
                                    'reward_await' => $reward_record['reward'],
                                    'add_time' => time(),
                                    'remark' => $this->reward_type[$reward_record['type']].'奖金结算',
                                    'assoc' => $order_sn,
                                    'assoc_type' => 'order'
                                ]);
                            }
                        }
                    }
                }
            }

            //记录用户流水
            if(count($member_trade_log)) {
                DB::table('member_trade_log')->insert($member_trade_log);
            }
        } else {
            Log::debug('settlement failure');
        }
    }

    //更新用户的业绩
    DB::table('achievement')->whereExists(function($query) use ($recommend_path) {
        $query->select('m.id')
            ->from('member as m')
            ->whereRaw('m.account = achievement.account')
            ->whereIn('m.id', $recommend_path);
    })->where('monthly', date('Ym'))->increment('increment', $increment);

    //更新三代小市场业绩
    $sub_recommend_path = $recommend_path;
    array_push($sub_recommend_path, $member['id']);
    while(count($sub_recommend_path) > 3) {
        array_shift($sub_recommend_path);
    }
    DB::table('achievement')->whereExists(function($query) use ($sub_recommend_path) {
        $query->select('m.id')
            ->from('member as m')
            ->whereRaw('m.account = achievement.account')
            ->whereIn('m.id', $sub_recommend_path);
    })->where('monthly', date('Ym'))->increment('sub_increment', $increment);

    $member_achievement = [
        'sub_increment' => $increment,
        'sub_dividend_gold' => 0
    ];

    $leave_node_account = $account; // 查看分红标准的结点账号

    //如新增业绩少于分红标准，则看推荐人的业绩是否达到分红标准
    if($increment < $this->dividend_gold_limit) {
        $leave_node_account = DB::table('member')->select('account')->where('id', $member['recommend_id']);
        $member_achievement = DB::table('achievement')->select('sub_increment', 'sub_dividend_gold')
            ->where('monthly', date('Ym'))
            ->where('account', $leave_node_account)
            ->first();

        //剔除当前用户结点
        array_pop($recommend_path);
    }

    while(count($recommend_path) > 3) {
        //剔除三代以外的结点，小市场仅计算三代
        array_shift($recommend_path);
    }

    if($member_achievement['sub_increment'] >= $this->dividend_gold_limit && $member_achievement['sub_dividend_gold'] == 0) {
        DB::beginTransaction();

        DB::table('achievement')->whereExists(function($query) use ($recommend_path) {
            $query->select('m.id')
                ->from('member as m')
                ->whereRaw('m.account = achievement.account')
                ->whereIn('m.id', $recommend_path);
        })->where('monthly', date('Ym'))->increment('dividend_gold', 1)->update(['sub_dividend_gold', 1]);

        DB::table('achievement')->where('account', $leave_node_account)->where('monthly', date('Ym'))
            ->update('sub_dividend_gold', 1);

        DB::commit();
    }
}

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
 * 更新提现状态
 * @param $withdraw_sn
 * @param $status
 * @param $operator
 * @param $remark
 */
function update_withdraw($withdraw_sn, $status, $operator, $remark)
{
    global $db;

    $get_recharge = 'select * from '.$db->table('withdraw').' where `withdraw_sn`=\''.$withdraw_sn.'\'';
    $recharge = $db->fetchRow($get_recharge);

    $recharge_status = array('status'=>$status);

    if($status == 2)
    {
        if(!member_account_change($recharge['account'], 0, -1*$recharge['amount'], 0, 0, 0, 0, $operator, 7, $remark))
        {
            return false;
        }
    }

    if($db->autoUpdate('withdraw', $recharge_status, '`withdraw_sn`=\''.$withdraw_sn.'\''))
    {
        add_withdraw_log($withdraw_sn, $operator, $recharge['status'], $status, $remark);
        return true;
    }

    return false;
}

/**
 * 更新充值状态
 * @param $recharge_sn
 * @param $status
 * @param $operator
 * @param $remark
 */
function update_recharge($recharge_sn, $status, $operator, $remark)
{
    global $db;

    $get_recharge = 'select * from '.$db->table('recharge').' where `recharge_sn`=\''.$recharge_sn.'\'';
    $recharge = $db->fetchRow($get_recharge);

    $recharge_status = array('status'=>$status);

    if($status == 3)
    {
        if(!member_account_change($recharge['account'], $recharge['amount'], 0, 0, 0, 0, 0, $operator, 1, $remark))
        {
            return false;
        }

        $recharge_status['pay_time'] = time();
    }

    if($db->autoUpdate('recharge', $recharge_status, '`recharge_sn`=\''.$recharge_sn.'\''))
    {
        add_recharge_log($recharge_sn, $operator, $recharge['status'], $status, $remark);
        return true;
    }

    return false;
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
 * @return resource
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
 * @return resource
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