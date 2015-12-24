<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/24
 * Time: 下午2:30
 */
include 'library/init.inc.php';

set_time_limit(0);

while(1)
{
    $now = time();
    $now -= 7*24*3600;
    $ids = '';
    if(date('H') == 0)
    {
        $get_reward_await = 'select `id`,`account`,`reward_await`,`integral_await` from '.$db->table('reward').' where `add_time`<='.$now.' and `status`=0';
        $reward_list = $db->fetchAll($get_reward_await);
        $log->record('发放'.date('Y-m-d', $now).'奖金');
        $log->record_array($reward_list);
        foreach($reward_list as $reward)
        {
            $flag = add_member_account($reward['account'], 'settle', 3, $reward['integral_await'], -1*$reward['integral_await'],
                                       $reward['reward_await'], -1*$reward['reward_await'], 0, '奖金发放');

            if($flag)
            {
                $ids .= $reward['id'].',';
            }
        }

        $ids = substr($ids, 0, strlen($ids)-1);

        $status_data = array(
            'status' => 1,
            'reach_time' => time()
        );
        $db->autoUpdate('reward', $status_data, '`id` in ('.$ids.')');
    }

    sleep(3600);
}