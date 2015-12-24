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

$action = 'edit|add|view|delete|recharge|network|get_node';
$operation = 'edit|add|reset|lock|unlock|frozen|release|open_network|close_network';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
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
            'password' => md5('123456'.PASSWORD_END),
            'super_password' => md5('123456'.PASSWORD_END)
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '密码已重置为123456';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//修改会员信息
if('edit' == $opera)
{
    $account = trim(getPOST('eaccount'));
    $name = trim(getPOST('name'));
    $mobile = trim(getPOST('mobile'));
    $level_id = intval(getPOST('level_id'));

    $msg = '';

    if($account == '')
    {
        $msg .= '-参数错误<br/>';
    } else {
        $account = $db->escape($account);
    }

    if($mobile == '')
    {
        $msg .= '-请填写会员手机<br/>';
    } else {
        $mobile = $db->escape($mobile);
    }

    if($name == '')
    {
        $msg .= '-请填写会员姓名<br/>';
    } else {
        $name = $db->escape($name);
    }

    if($level_id <= 0)
    {
        $msg .= '-请选择会员等级<br/>';
    } else {
        $get_member_info = 'select `level_id` from '.$db->table('member').' where `account`=\''.$account.'\'';
        $m_level_id = $db->fetchOne($get_member_info);

        if($m_level_id > $level_id)
        {
            $msg .= '-会员不能降级<br/>';
        }
    }

    if($msg == '')
    {
        $member_data = array(
            'name' => $name,
            'level_id' => $level_id,
            'mobile' => $mobile
        );

        if($db->autoUpdate('member', $member_data, '`account`=\''.$account.'\''))
        {
            $log->record('会员'.$account.'修改级别:'.$m_level_id.'=>'.$level_id);
            show_system_message('修改会员信息成功');
        }
    } else {
        show_system_message($msg);
    }
}

//开启查看网络
if('open_network' == $opera)
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
            'view_network' => 1
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '开启网络图成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//关闭查看网络
if('close_network' == $opera)
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
            'view_network' => 0
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '禁止网络图成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//冻结账户
if('frozen' == $opera)
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
            'status' => 4
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '冻结账户成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//解冻账户
if('release' == $opera)
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
            'status' => 2
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '解冻账户成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//锁定账户
if('lock' == $opera)
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
            'status' => 1
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '锁定账户成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//解锁账户
if('unlock' == $opera)
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
            'status' => 2
        );

        if($db->autoUpdate('member', $data, '`account`=\''.$account.'\''))
        {
            $response['error'] = 0;
            $response['msg'] = '解锁账户成功';
        } else {
            $response['msg'] = '系统繁忙，请稍后再试';
        }
    }

    echo json_encode($response);
    exit;
}

//删除会员
if('delete' == $opera)
{
}

if('get_node' == $act)
{
    $id = intval(getPOST('id'));

    $tree_nodes = array();
    $get_nodes = 'select `id`,`recommend_id` as `pid`,`name`,`account`,`level_id`,`add_time`,`balance`,`reward`,`reward_await` from ' . $db->table('member') . ' where `recommend_id`=' . $id;

    $nodes = $db->fetchAll($get_nodes);
    if($nodes)
    {
        foreach ($nodes as $node) {
            $check_children = 'select count(*) from ' . $db->table('member') . ' where `recommend_id`=' . $node['id'];

            $node['isParent'] = $db->fetchOne($check_children) ? true : false;

            $node['name'] .= '[会员卡号:' . $node['account'] . ', 会员等级:' . $lang['level'][$node['level_id']] . ', 报单时间:' . date('Y-m-d H:i:s', $node['add_time']) . ']';
            $tree_nodes[] = $node;
        }
    }

    echo json_encode($tree_nodes);
    exit;
}

if('edit' == $act)
{
    $account = trim(getGET('account'));

    if($account == '')
    {
        show_system_message('参数错误');
    }

    $account = $db->escape($account);

    $get_user_info = 'select * from '.$db->table('member').' where `account`=\''.$account.'\'';
    $user_info = $db->fetchRow($get_user_info);

    assign('member_info', $user_info);
}

if('view' == $act)
{
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');

    $where = ' where 1 ';

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
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

    $get_member = 'select * from '.$db->table('member').$where.' order by `add_time` DESC limit '.$offset.','.$count;

    $member_list = $db->fetchAll($get_member);

    if($member_list) {
        foreach ($member_list as $k => $member) {
            $tmp = '';

            if (check_purview('pur_member_edit', $_SESSION['purview'])) {
                $member_list[$k]['operation'] = '<a href="member.php?act=edit&account=' . $member['account'] . '">编辑</a>';

                $member_list[$k]['operation'] .= ' | <a href="javascript:reset_password(\'' . $member['account'] . '\');">重置密码</a>';
                if ($member['status'] == 2) {
                    $member_list[$k]['operation'] .= ' | <a href="javascript:lock(\'' . $member['account'] . '\');">锁定用户</a>';
                } else {
                    $member_list[$k]['operation'] .= ' | <a href="javascript:unlock(\'' . $member['account'] . '\');">解锁用户</a>';
                }

                if ($member['status'] == 4) {
                    $member_list[$k]['operation'] .= ' | <a href="javascript:release(\'' . $member['account'] . '\');">解冻用户</a>';
                } else {
                    $member_list[$k]['operation'] .= ' | <a href="javascript:frozen(\'' . $member['account'] . '\');">冻结用户</a>';
                }

                if ($member['view_network'] == 0) {
                    $member_list[$k]['operation'] .= ' | <a href="javascript:open_network(\'' . $member['account'] . '\');">开启网络</a>';
                } else {
                    $member_list[$k]['operation'] .= ' | <a href="javascript:close_network(\'' . $member['account'] . '\');">关闭网络</a>';
                }
            }
        }
    }
    assign('member_list', $member_list);
}

assign('subTitle', '会员管理');
assign('act', $act);
$smarty->display($template.'member.phtml');