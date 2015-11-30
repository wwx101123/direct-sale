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

$template = 'member/';
assign('subTitle', '会员管理');

$action = 'edit|add|view|delete|recharge|network|get_node|move';
$operation = 'edit|add|recharge|reset|move|check_node';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
//会员移网
if('move' == $opera)
{
    $account = getPOST('eaccount');
    $recommend = trim(getPOST('recommend'));

    $msg = '';

    if($account == '')
    {
        show_system_message('参数错误');
        exit;
    } else {
        $account = $db->escape($account);
    }

    if($recommend == '')
    {
        $msg .= '-请填写目标推荐人<br/>';
    } else {
        $recommend = $db->escape($recommend);
    }

    if($msg == '')
    {
        $get_member = 'select `id`,`parent_id`,`path` from '.$db->table('user').' where `account`=\''.$account.'\'';
        $member = $db->fetchRow($get_member);

        $get_parent_info = 'select `id`,`path` from '.$db->table('user').' where `account`=\''.$recommend.'\'';
        $parent_info = $db->fetchRow($get_parent_info);

        if(!$parent_info)
        {
            show_system_message('推荐人不存在');
        } else {
            if($parent_info['id'] == $member['parent_id'])
            {
                show_system_message('移网成功');
            } else {
                $member_data = array(
                    'parent_id' => $parent_info['id']
                );

                if($db->autoUpdate('user', $member_data, '`account`=\''.$account.'\''))
                {
                    $new_path = $parent_info['path'] . $member['id'] . ',';
                    $sql = 'update ' . $db->table('user') . ' set `path`=replace(`path`,\'' . $member['path'] . '\', \'' . $new_path . '\') where `path` like \'' . $member['path'] . '%\'';
                    $db->update($sql);
                    show_system_message('移网成功');
                } else {
                    show_system_message('系统繁忙，请稍后再试');
                }
            }
        }
    } else {
        show_system_message($msg);
    }
}
//检查结点
if('check_node' == $opera)
{
    $response = array('error' => 1, 'msg' => '');

    $account = trim(getPOST('account'));

    if($account == '')
    {
        $response['msg'] = '参数错误';
    } else {
        $account = $db->escape($account);
    }

    if($response['msg'] == '')
    {
        $check_account = 'select `account`,`nickname` from '.$db->table('user').' where `account`=\''.$account.'\'';

        $member = $db->fetchRow($check_account);

        if($db->fetchRow($check_account))
        {
            $response['error'] = 0;
            $response['msg'] = $member;
        } else {
            $response['msg'] = '推荐人不存在';
        }
    }

    echo json_encode($response);
    exit;
}
//重置密码
if('reset' == $opera)
{
    $response = array('error' => 1, 'msg' => '');

    $account = getPOST('account');

    if($account == '')
    {
        $response['msg'] = '参数错误';
    } else {
        $account = $db->escape($account);
    }

    if($response['msg'] == '')
    {
        $data = array(
            'password' => md5('123456'.PASSWORD_END)
        );

        if($db->autoUpdate('user', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '重置密码成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}
//会员充值
if('recharge' == $opera)
{
    $account = getPOST('eaccount');
    $integral = floatval(getPOST('integral'));
    $emoney = floatval(getPOST('emoney'));
    $remark = getPOST('remark');

    $msg = '';

    if($account == '')
    {
        show_system_message('参数错误');
        exit;
    } else {
        $account = $db->escape($account);
    }

    if($integral <= 0 || $emoney <= 0)
    {
        $msg .= '-请填写充值金额/充值积分<br/>';
    }

    if($remark == '')
    {
        $msg .= '-请填写备注信息<br/>';
    }

    if($msg == '')
    {
        if (add_member_account($account, $_SESSION['account'], 4, $integral, 0, 0, 0, $emoney, $remark))
        {
            show_system_message('充值成功');
        } else {
            show_system_message('系统繁忙');
        }
    } else {
        show_system_message($msg);
    }
}

//修改会员信息
if('edit' == $opera)
{
}

//锁定账户
if('lock' == $opera)
{
}

//解锁账户
if('unlock' == $opera)
{
}

//删除会员
if('delete' == $act)
{
    $account = getGET('account');

    $get_member_info = 'select * from '.$db->table('user').' where `account`=\''.$account.'\'';

    $member_info = $db->fetchRow($get_member_info);

    if(check_purview('pur_member_edit', $_SESSION['purview']))
    {
        //检查有没有订单
        $check_order = 'select count(*) from '.$db->table('order').' where `account`=\''.$account.'\' and `status`>0';
        $has_order = $db->fetchOne($check_order);

        //检查有没有下线
        $check_member = 'select count(*) from '.$db->table('user').' where `path` like \''.$member_info['path'].'%\' and `account`<>\''.$account.'\'';
        $has_member = $db->fetchOne($check_member);

        if($has_member || $has_order)
        {
            show_system_message('该会员有已支付的订单或有推荐下线，不能删除');
        } else {
            $db->autoDelete('user', '`account`=\''.$account.'\'');
            $sql = 'delete from `oshop`.`os_user` where `openid`=\''.$member_info['openid'].'\'';
            $db->delete($sql);

            show_system_message('删除会员成功');
        }
    } else {
        show_system_message('权限不足');
    }
}

if('get_node' == $act)
{
    $id = intval(getPOST('id'));

    $get_nodes = 'select `id`,`parent_id` as `pid`,`nickname` as `name`,`account` from '.$db->table('user').' where `parent_id`='.$id;

    $nodes = $db->fetchAll($get_nodes);
    $tree_nodes = array();
    foreach($nodes as $node)
    {
        $check_children = 'select count(*) from '.$db->table('user').' where `parent_id`='.$node['id'];

        $node['name'] .= '['.$node['account'].']';
        $node['isParent'] = $db->fetchOne($check_children) ? true : false;
        $tree_nodes[] = $node;
    }

    echo json_encode($tree_nodes);
    exit;
}

if('view' == $act)
{
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $nickname = getGET('nickname');

    $where = ' where 1 ';

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
    }

    if ($nickname != '')
    {
        $nickname = $db->escape($nickname);
        $where .= ' and `nickname`=\''.$nickname.'\' ';
    }

    $get_total = 'select count(*) from '.$db->table('user').$where;
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
    assign('nickname', $nickname);

    $get_member = 'select * from '.$db->table('user').$where.' order by `add_time` DESC limit '.$offset.','.$count;

    $member_list = $db->fetchAll($get_member);

    foreach($member_list as $k=>$member)
    {
        $tmp = '';

        if(check_purview('pur_member_edit', $_SESSION['purview']))
        {
            $member_list[$k]['operation'] = '<a href="member.php?act=recharge&account='.$member['account'].'">充值</a>';
            if($member['id'] > 1)
            {
                $member_list[$k]['operation'] .= ' | <a href="member.php?act=move&account=' . $member['account'] . '">移网</a>';
            }
            $member_list[$k]['operation'] .= ' | <a href="member.php?act=delete&account='.$member['account'].'" onclick="return confirm(\'您确定要删除该会员？\');">删除</a>';
            $member_list[$k]['operation'] .= ' | <a href="javascript:reset_password(\''.$member['account'].'\');">重置密码</a>';
        }
    }

    assign('member_list', $member_list);
}

if('recharge' == $act)
{
    $account = getGET('account');

    $get_member_info = 'select * from '.$db->table('user').' where `account`=\''.$account.'\'';

    $member_info = $db->fetchRow($get_member_info);

    assign('member_info', $member_info);
}

if('move' == $act)
{
    $account = getGET('account');

    $get_member_info = 'select * from '.$db->table('user').' where `account`=\''.$account.'\'';

    $member_info = $db->fetchRow($get_member_info);

    $get_recommend = 'select * from '.$db->table('user').' where `id`='.$member_info['parent_id'];
    $recommend = $db->fetchRow($get_recommend);
    $member_info['recommend'] = $recommend;

    assign('member_info', $member_info);

    $get_path = 'select `nickname`,`account` from '.$db->table('user').' where `id` in ('.$member_info['path'].'0) order by find_in_set(`id`,\''.$member_info['path'].'0\')';
    assign('path', $db->fetchAll($get_path));
}

assign('subTitle', '会员管理');
assign('act', $act);
$smarty->display($template.'member.phtml');