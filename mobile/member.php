<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 上午11:52
 */
include 'library/init.inc.php';

$get_id = 'select `id` from '.$db->table('user').' where `openid`=\''.$_SESSION['openid'].'\'';

$id = $db->fetchOne($get_id);
$get_user_list = 'select `account`,`nickname`,`phone`,`add_time` from '.$db->table('user').' where `parent_id`='.$id.' order by `add_time` DESC';

$user_list = $db->fetchAll($get_user_list);
if($user_list)
{
    foreach ($user_list as $k => $u)
    {
        $user_list[$k]['phone'] = substr($u['phone'], 0, 3) . '****' . substr($u['phone'], strlen($u['phone']), -4);
        $user_list[$k]['add_time'] = date('Y-m-d H:i:s', $u['add_time']);
    }
}

assign('user_list', $user_list);
$smarty->display('member.phtml');