<?php
/**
 * 账户相关操作封装
 */

/**
 * 结算
 * @param $order_sn
 * @return bool
 */
function settle($order_sn) {
    global $db, $log, $config, $lang;

    $order_sn = $db->escape($order_sn);

    $get_order = 'select * from '.$db->table('order').' where `order_sn`=\''.$order_sn.'\'';
    $order = $db->fetchRow($get_order);

    if(empty($order)) {
        return false;
    }

    if($order['type'] != 1) {
        //计算重消
        return consume_settle($order);
    }

    $increment = $order['pv_amount']; // 新增业绩
    $account = $order['account']; // 新增用户账号

    //获取下单用户信息
    $get_member = 'select * from '.$db->table('member').' where `account`=\''.$account.'\'';
    $member = $db->fetchRow($get_member);
    $recommend_path = explode(',', $member['recommend_path']);
    array_pop($recommend_path);

    //获取推荐线上所有用户(含自己)
    $get_parents = 'select * from '.$db->table('member').' where exists ('.
        'select m.id from '.$db->table('member').' as m '.
        'where m.`account`='.$db->table('member').'.`account` and m.`id` in ('.implode(',', $recommend_path).')'.
        ') order by `recommend_path` desc';

    $parents = $db->fetchAll($get_parents);

    $year = date('Y', $order['pay_time']);
    $month = date('n', $order['pay_time']);

    //如不存在总业绩则创建
    $get_achievement_summary = 'select `id` from '.$db->table('achievement').
                                ' where `account`=\'\' and `year`='.$year.' and `month`='.$month;
    $achievement_summary = $db->fetchOne($get_achievement_summary);

    if(empty($achievement_summary)) {
        $achievement = [
            'member_id' => 0, // 会员ID
            'year' => $year, // 年
            'month' => $month, // 月
            'account' => '', // 会员账号
            'increment' => 0, // 当月新增业绩
            'children' => 0, // 直推结点数
            'dividend_gold' => 0, // 分红达标点数
            'sub_dividend_gold' => 0, // 推荐人当前市场已达到分红标准
            'sub_increment' => 0, // 推荐人小市场业绩新增
            'recommend_path' => '', // 推荐关系
            'recommend_id' => 0 // 直推人
        ];

        $db->autoInsert('achievement', [$achievement]);
    }

    //如推荐线上用户不存在当月业绩则创建
    foreach($parents as $node) {
        $get_achievement = 'select `id` from '.$db->table('achievement').
                            ' where `account`=\''.$node['account'].'\' and `year`='.$year.' and `month`='.$month;

        $achievement = $db->fetchOne($get_achievement);

        //如业绩不存在则新建
        if (empty($achievement)) {
            $achievement = [
                'member_id' => $node['id'], // 会员ID
                'year' => $year, // 年
                'month' => $month, // 月
                'account' => $node['account'], // 会员账号
                'increment' => 0, // 当月新增业绩
                'children' => $node['actived_children'], // 直推结点数
                'dividend_gold' => 0, // 分红达标点数
                'sub_dividend_gold' => 0, // 推荐人当前市场已达到分红标准
                'sub_increment' => 0, // 推荐人小市场业绩新增
                'recommend_path' => $node['recommend_path'], // 推荐关系
                'recommend_id' => $node['recommend_id'] // 直推人
            ];

            $db->autoInsert('achievement', [$achievement]);
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
        if(!$reach_service_node && $node['level_id'] == 6) {
            //服务点业绩提成
            $reward = round($increment * $config['server_reward_rate'], 2);
            $reward_type = 3; // 业绩提成
            $reward_rate = $config['server_reward_rate'];

            $reach_service_node = true;
        }

        //金星、银星享受三代内推荐奖
        $can_get_recommend_reward = $layer <= 3 && $node['level_id'] >= 4 && $node['level_id'] <= 5;
        //铜星享受二代内推荐奖
        $can_get_recommend_reward |= $layer <= 2 && $node['level_id'] == 3;
        //普通会员享受一代推荐奖
        $can_get_recommend_reward |= $layer == 1 && $node['level_id'] == 2;

        if($can_get_recommend_reward) {
            //推荐奖计算
            $reward = round($increment * $config['recommend_reward_rate'], 2);
            $reward_type = 1; // 推荐奖
            $reward_rate = $config['recommend_reward_rate'];
        }

        //金星、银星享受三代内管理奖
        $can_get_manager_reward = $layer <= 3 && $node['level_id'] >= 3 && $node['level_id'] <= 4;
        //铜星享受二代内管理奖
        $can_get_manager_reward |= $layer <= 2 && $node['level_id'] == 2;

        if($can_get_manager_reward) {
            //管理奖计算
            $reward = round($increment * $config['manager_reward_rate'], 2);
            $reward_type = 2; // 管理奖
            $reward_rate = $config['manager_reward_rate'];
        }

        if($reward_type > 0) {
            $member_reward_await[$node['account']] += $reward;

            array_push($reward_list, [
                'account' => $node['account'], // 会员账号
                'rate' => $reward_rate, // 系数
                'reward_base' => $increment, // 业绩
                'reward' => $reward, // 金额
                'settle_time' => time(), // 结算时间
                'assoc' => $order_sn, // 关联订单
                'status' => 0, // 状态： 0 - 待发，1 - 已发，2 - 完成，3 - 回退
                'type' => $reward_type, // 奖金类型
                'remark' => $order_sn.' '.$lang['reward_type'][$reward_type]
            ]);
        }

        if($layer > 3 && $reach_service_node) {
            break;
        }
    }

    //获取直推一代的金星会员
    if($config['manager_reward_up_rate']) {
        $get_siblings = 'SELECT `account` FROM ' . $db->table('member') . ' WHERE '.
                        '`recommend`=\'' . $member['recommend'] . '\' AND `level_id`=5 and `account`<>\''.$account.'\'';
        $siblings = $db->fetchAll($get_siblings);

        if($siblings) {
            while ($sibling = array_shift($siblings)) {
                if(!isset($member_reward_await[$sibling['account']])) {
                    $member_reward_await[$sibling['account']] = 0;
                }

                $reward = round($increment * $config['manager_reward_up_rate'], 2);
                $reward_rate = $config['manager_reward_up_rate'];

                $member_reward_await[$sibling['account']] += $reward;

                array_push($reward_list, [
                    'account' => $sibling['account'], // 会员账号
                    'rate' => $reward_rate, // 系数
                    'reward_base' => $increment, // 业绩
                    'reward' => $reward, // 金额
                    'settle_time' => time(), // 结算时间
                    'assoc' => $order_sn, // 关联订单
                    'status' => 0, // 状态： 0 - 待发，1 - 已发，2 - 完成，3 - 回退
                    'type' => 5, // 奖金类型 线上管理奖
                    'remark' => $order_sn.' '.$lang['reward_type'][5]
                ]);
            }
        }
    }

    //插入奖金表
    if(count($reward_list)) {
        if($db->autoInsert('reward', $reward_list)) {
            $log->record('settlement success');
            //更新用户待发奖金
            $member_trade_log = []; // 用户流水记录
            foreach($member_reward_await as $member_account => $reward_await) {
                if($reward_await > 0) {
                    $update_member_reward_await = 'update '.$db->table('member').' set `reward_await`=`reward_await`+'.$reward_await.
                        ' where `account`=\''.$member_account.'\'';

                    $flag = $db->update($update_member_reward_await);

                    if($flag) {
                        foreach($reward_list as $reward_record) {
                            if($reward_record['account'] == $member_account) {
                                array_push($member_trade_log, [
                                    'account' => $member_account,
                                    'reward_await' => $reward_record['reward'],
                                    'add_time' => time(),
                                    'remark' => $order_sn.' '.$lang['reward_type'][$reward_record['type']].'结算',
                                    'assoc' => $order_sn,
                                    'assoc_type' => 'order',
                                    'operator' => 'settle',
                                    'type' => 3 //奖金结算
                                ]);
                            }
                        }
                    }
                }
            }

            //记录用户流水
            if(count($member_trade_log)) {
                $db->autoInsert('account', $member_trade_log);
            }
        } else {
            $log->record('settlement failure');
        }
    }

    //剔除自己
    array_pop($recommend_path);
    //更新总业绩
    $update_achievement_summary = 'update '.$db->table('achievement').' set `increment`=`increment`+'.$increment.
        ' where `account`=\'\' and `year`='.$year.' and `month`='.$month;
    $db->update($update_achievement_summary);

    //更新上级用户的业绩
    $update_achievement = 'update '.$db->table('achievement').' set `increment`=`increment`+'.$increment.','.
                          '`self_increment`=`self_increment`+'.$increment.
                          ' where `member_id` in ('.implode(',', $recommend_path).')'.
                          ' and `year`='.$year.' and `month`='.$month;

    $db->update($update_achievement);

    //更新三代小市场业绩
    $sub_recommend_path = $recommend_path;
    array_push($sub_recommend_path, $member['id']);
    while(count($sub_recommend_path) > 3) {
        array_shift($sub_recommend_path);
    }

    $update_sub_achievement = 'update '.$db->table('achievement').' set `sub_increment`=`sub_increment`+'.$increment.
        ' where `member_id` in ('.implode(',', $sub_recommend_path).')'.
        ' and `year`='.$year.' and `month`='.$month;

    $db->update($update_sub_achievement);

    $member_achievement = [
        'sub_increment' => $increment,
        'sub_dividend_gold' => 0
    ];

    $leave_node_account = $account; // 查看分红标准的结点账号

    //如新增业绩少于分红标准，则看推荐人的业绩是否达到分红标准
    if($increment < $config['dividend_gold_limit']) {
        $leave_node_account = $member['recommend'];
        $get_member_achievement = 'select `sub_increment`,`sub_dividend_gold` from '.$db->table('achievement').
                                  ' where `account`=\''.$leave_node_account.'\' and `year`='.$year.' and `month`='.$month;
        $member_achievement = $db->fetchRow($get_member_achievement);

        //剔除当前用户结点
        array_pop($recommend_path);
    }

    while(count($recommend_path) > 3) {
        //剔除三代以外的结点，小市场仅计算三代
        array_shift($recommend_path);
    }

    $log->record_array($member_achievement);
    if($member_achievement['sub_increment'] >= $config['dividend_gold_limit'] && $member_achievement['sub_dividend_gold'] == 0) {
        $update_dividend_gold = 'update '.$db->table('achievement').' set `dividend_gold`=`dividend_gold`+1, `sub_dividend_gold`=1 '.
                                ' where `member_id` in ('.implode(',', $recommend_path).') and `year`='.$year.' and `month`='.$month;

        $log->record($update_dividend_gold);
        $db->update($update_dividend_gold);

        $update_leave_gold = 'update '.$db->table('achievement').' set `sub_dividend_gold`=1 '.
                             ' where `account`=\''.$leave_node_account.'\' and `year`='.$year.' and `month`='.$month;

        $log->record($update_leave_gold);
        $db->update($update_leave_gold);
    }

    //结算后升级
    $update_member_data = 'update '.$db->table('member').' set ';
    $update_member_data_field = [];

    if($order['target_level']) {
        $update_member_data_field[] = '`level_id`='.$order['target_level'].',`status`=2';
    }

    if($order['stock_given_amount']) {
        $update_member_data_field []= '`stock`=`stock`+'.$order['stock_given_amount'];
    }

    $update_member_data .= implode(',', $update_member_data_field).' where `account`=\''.$account.'\' and `level_id`<'.$order['target_level'];

    $log->record($update_member_data);
    if($db->update($update_member_data)) {
        $update_actived_children = 'update '.$db->table('member').' set `actived_children`=`actived_children`+1'.
                                    ' where `account`=\''.$member['recommend'].'\'';

        $db->update($update_actived_children);

        $update_recommend_children = 'update '.$db->table('achievement').' set `children`=`children`+1 where '.
                                     '`account`=\''.$member['recommend'].'\' and `month`='.$month.' and `year`='.$year;

        $db->update($update_recommend_children);
    }

    return true;
}

/**
 * 累计重消业绩
 * @param $order
 * @return bool
 */
function consume_settle($order) {
    global $db, $log, $config;

    $year = date('Y', $order['pay_time']);
    $month = date('n', $order['pay_time']);

    //如不存在总业绩则创建
    $get_achievement_summary = 'select `id` from '.$db->table('achievement').
        ' where `account`=\'\' and `year`='.$year.' and `month`='.$month;
    $achievement_summary = $db->fetchOne($get_achievement_summary);

    if(empty($achievement_summary)) {
        $achievement = [
            'member_id' => 0, // 会员ID
            'year' => $year, // 年
            'month' => $month, // 月
            'account' => '', // 会员账号
            'increment' => 0, // 当月新增业绩
            'children' => 0, // 直推结点数
            'dividend_gold' => 0, // 分红达标点数
            'sub_dividend_gold' => 0, // 推荐人当前市场已达到分红标准
            'sub_increment' => 0, // 推荐人小市场业绩新增
            'consume_increment' => 0, // 当前结点当月新增重消业绩
            'recommend_path' => '', // 推荐关系
            'recommend_id' => 0 // 直推人
        ];

        $db->autoInsert('achievement', [$achievement]);
    }

    //如不存在当前用户的业绩则创建
    $get_achievement = 'select `id` from '.$db->table('achievement').
        ' where `account`=\''.$order['account'].'\' and `year`='.$year.' and `month`='.$month;
    $achievement = $db->fetchOne($get_achievement);

    $get_member = 'select * from '.$db->table('member').' where `account`=\''.$order['account'].'\'';
    $member = $db->fetchRow($get_member);

    if(empty($achievement)) {
        $achievement = [
            'member_id' => $member['id'], // 会员ID
            'year' => $year, // 年
            'month' => $month, // 月
            'account' => $member['account'], // 会员账号
            'increment' => 0, // 当月新增业绩
            'children' => $member['actived_children'], // 直推结点数
            'dividend_gold' => 0, // 分红达标点数
            'sub_dividend_gold' => 0, // 推荐人当前市场已达到分红标准
            'sub_increment' => 0, // 推荐人小市场业绩新增
            'consume_increment' => 0, // 当前结点当月新增重消业绩
            'recommend_path' => $member['recommend_path'], // 推荐关系
            'recommend_id' => $member['recommend_id'] // 直推人
        ];

        $db->autoInsert('achievement', [$achievement]);
    }

    //更新当前用户的重消业绩和总重消业绩
    $update_consume_increment = 'update '.$db->table('achievement').' set `consume_increment`=`consume_increment`+'.$order['pv_amount'].
                                        ' where (`account`=\'\' or `account`=\''.$member['account'].'\') and `year`='.$year.' and `month`='.$month;

    $log->record($update_consume_increment);
    $db->update($update_consume_increment);

    return true;
}

/**
 * 结算指定月份的分红
 * @param $year
 * @param $month
 * @return bool
 */
function dividend_settle($year, $month) {
    global $log, $config, $db, $lang;

    //读取总业绩
    $get_achievement_summary = 'select * from '.$db->table('achievement').' where `year`='.$year.' and `month`='.$month.' and `account`=\'\'';
    $achievement_summary = $db->fetchRow($get_achievement_summary);

    if(empty($achievement_summary)) {
        //没有总业绩，当月没有产生任何业绩
        $log->record($year.$month.'没有产生任何业绩');
        return false;
    }

    $get_reward = 'select 1 from '.$db->table('reward').' where `assoc`=\''.$year.$month.'\' and `type`=4 limit 1';
    if($db->fetchOne($get_reward)) {
        //当月分红已结算过
        $log->record($year.$month.'分红已结算过');
        return false;
    }

    //分红基数 = 重消业绩 * 重消业绩计入分红比例 * 分红比例
    $dividend_reward_base = $achievement_summary['consume_increment'] * $config['consume_dividend_rate'] * $config['dividend_rate'];
    $dividend_reward_base = round($dividend_reward_base, 2);

    if($dividend_reward_base <= 0) {
        //分红基数为0
        $log->record($year.$month.'分红业绩为0');
        return false;
    }

    //读取可参与分红的结点
    $get_dividend_nodes = 'select a.`account`,m.`stock` from '.$db->table('achievement').' as a '.
        'left join '.$db->table('member').' as m using(`account`) '.
        ' where `year`='.$year.' and `month`='.$month.' and `children`=`dividend_gold` and `dividend_gold`>0';
    $log->record($get_dividend_nodes);
    $dividend_nodes = $db->fetchAll($get_dividend_nodes);

    if(empty($dividend_nodes)) {
        //没有达到分红条件的结点
        $log->record($year.$month.'没有达到分红条件的结点');
        return false;
    }

    $total_stock = 0;
    foreach($dividend_nodes as $node) {
        $total_stock += $node['stock'];
    }

    $reward_amount = $dividend_reward_base;

    $reward_list = [];
    while($node = array_shift($dividend_nodes)) {
        $reward_rate = round($node['stock']/$total_stock, 2);
        $reward = round($dividend_reward_base * $reward_rate, 2);

        $reward = min($reward_amount, $reward);
        $reward_amount -= $reward;

        array_push($reward_list, [
            'account' => $node['account'], // 会员账号
            'rate' => $reward_rate, // 系数
            'reward_base' => $dividend_reward_base, // 业绩
            'reward' => $reward, // 金额
            'settle_time' => time(), // 结算时间
            'assoc' => $year.$month, // 关联订单
            'status' => 0, // 状态： 0 - 待发，1 - 已发，2 - 完成，3 - 回退
            'type' => 4, // 分红
            'remark' => $year.'-'.$month.'分红'
        ]);
    }

    //插入奖金表
    if(count($reward_list)) {
        if($db->autoInsert('reward', $reward_list)) {
            $log->record('settlement success');
            //更新用户待发奖金
            $member_trade_log = []; // 用户流水记录
            foreach($reward_list as $reward) {
                if($reward['reward'] > 0) {
                    $update_member_reward_await = 'update '.$db->table('member').' set `reward_await`=`reward_await`+'.$reward['reward'].
                        ' where `account`=\''.$reward['account'].'\'';

                    $flag = $db->update($update_member_reward_await);

                    if($flag) {
                        array_push($member_trade_log, [
                            'account' => $reward['account'],
                            'reward_await' => $reward['reward'],
                            'add_time' => time(),
                            'remark' => $year.'-'.$month.$lang['reward_type'][$reward['type']].'结算',
                            'assoc' => $year.$month,
                            'assoc_type' => 'dividend',
                            'operator' => 'settle',
                            'type' => 3 //奖金结算
                        ]);
                    }
                }
            }

            //记录用户流水
            if(count($member_trade_log)) {
                $db->autoInsert('account', $member_trade_log);
            }
        } else {
            $log->record('settlement failure');
        }
    }

    return true;
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

    $fee = round($amount * $config['fee_rate'], 2);
    $amount -= $fee;
    $withdraw_data = array(
        'account' => $account,
        'amount' => $amount + $fee,
        'fee' => $fee,
        'real_amount' => $amount,
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