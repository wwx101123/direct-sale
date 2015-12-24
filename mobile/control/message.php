<?php
/**
 * 会员管理
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/14
 * Time: 下午10:04
 */
include 'library/init.inc.php';
back_base_init();

$template = 'message/';
assign('subTitle', '留言管理');

$action = 'edit|add|view|delete|detail|reply';
$operation = 'edit|add|export|reply';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if('reply' == $opera)
{
    $id = intval(getPOST('id'));
    $content = trim(getPOST('rcontent'));

    if($id <= 0)
    {
        show_system_message('参数错误');
    }

    if($content == '')
    {
        show_system_message('请填写回复内容');
    } else {
        $content = $db->escape($content);
    }

    $get_account = 'select `from` from '.$db->table('message').' where `id`='.$id;
    $account = $db->fetchOne($get_account);

    if(add_message('系统管理员', $account, $content, $id))
    {
        show_system_message('回复成功', array(array('link'=>'message.php', 'alt'=>'留言管理')));
    } else {
        show_system_message('系统繁忙，请稍后再试');
    }
}

if('reply' == $act || 'detail' == $act)
{
    $id = intval(getGET('id'));

    $get_message = 'select * from '.$db->table('message').' where `id`='.$id;

    $message = $db->fetchRow($get_message);
    assign('message', $message);

    $get_reply = 'select * from '.$db->table('message').' where `parent_id`='.$id;
    $reply = $db->fetchRow($get_reply);

    assign('reply', $reply);
}

if('view' == $act) {
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');

    $where = ' where 1 ';

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `from`=\''.$account.'\' or `to`=\''.$account.'\'';
    }

    $get_total = 'select count(*) from '.$db->table('message').$where;
    $total = $db->fetchOne($get_total);

    $count_expected = array(10, 25, 50, 100);
    $page = intval($page);
    $count = intval($count);
    if( !in_array($count, $count_expected) ) {
        $count = 10;
    }

    $total_page = ceil($total / $count);

    $page = ( $page > $total_page ) ? $total_page : $page;
    $page = ( $page <= 0 ) ? 1 : $page;

    $offset = ($page - 1) * $count;

    create_pager($page, $total_page, $total);
    assign('count', $count);
    assign('account', $account);

    $get_reward_list = 'select s.*,(select `id` from '.$db->table('message').' where `parent_id`=s.`id` limit 1) as `reply` from '.$db->table('message').' as s '.$where.' order by `path` ASC,`add_time` DESC limit '.$offset.','.$count;

    $reward_list = $db->fetchAll($get_reward_list);

    if($reward_list) {
        foreach ($reward_list as $k => $message) {
            if ($message['from'] != '系统管理员') {
                $reward_list[$k]['operation'] = '<a href="message.php?act=detail&id=' . $message['id'] . '">查看</a>';
                if (!$message['reply']) {
                    $reward_list[$k]['operation'] .= ' | <a href="message.php?act=reply&id=' . $message['id'] . '">回复</a>';
                }
            } else {
                $reward_list[$k]['operation'] = '';
            }
        }
    }
    assign('message_list', $reward_list);
}

assign('act', $act);
$smarty->display($template.'message.phtml');