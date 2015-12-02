<?php
/**
 * PC端首页
 * @author winsen
 * @version 1.0.0
 */
include 'library/init.inc.php';

$operation = 'node';
$action = 'view|network|order';
$act = check_action($action, getGET('act'), 'view');
$opera = check_action($operation, getPOST('opera'));

//查看网络图
if('node' == $opera)
{
    $response = array('error'=>0, 'msg'=>'');

    if(empty($_SESSION['account']))
    {
        $response['msg'] .= "-请先登录\n";
    }

    if($response['msg'] == '')
    {
        $id = intval(getPOST('id'));

        $get_nodes = '';

        if($id == 0)
        {
            $get_nodes = 'select `id`,`recommend_id` as `pid`,`name`,`account` from ' . $db->table('member') . ' where `account`=\'' . $_SESSION['account'].'\'';
        } else {
            $get_nodes = 'select `id`,`recommend_id` as `pid`,`name`,`account` from ' . $db->table('member') . ' where `recommend_id`=' . $id;
        }

        $nodes = $db->fetchAll($get_nodes);
        $tree_nodes = array();
        if($nodes)
        {
            foreach ($nodes as $node) {
                $node['name'] = $node['name'].'['.$node['account'].']';
                $check_children = 'select count(*) from ' . $db->table('member') . ' where `recommend_id`=' . $node['id'];

                $node['isParent'] = $db->fetchOne($check_children) ? true : false;
                $tree_nodes[] = $node;
            }
        }
    }

    echo json_encode($tree_nodes);
    exit;
}
//================================================

if('view' == $act)
{
    assign('sub_title', '我的团队');

    $path = $member_info['recommend_path'];

    $param_list = '';
    $where = '';

    $account = trim(getGET('account'));
    if($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\'';
        $param_list .= '&account='.$account;
    }

    $level_id = intval(getGET('level_id'));
    if($level_id > 0)
    {
        $where .= ' and `level_id`='.$level_id;
        $param_list .= '&level_id='.$level_id;
    }

    $begin_time = trim(getGET('begin_time'));
    if($begin_time != '' && $begin_time = strtotime($begin_time.' 00:00:00'))
    {
        $where .= ' and `add_time`>='.$begin_time;
        $param_list .= '&begin_time='.date('Y-m-d', $begin_time);
    }

    $end_time = trim(getGET('end_time'));
    if($end_time != '' && $end_time = strtotime($end_time.' 23:59:59'))
    {
        $where .= ' and `add_time`<='.$end_time;
        $param_list .= '&end_time='.date('Y-m-d', $end_time);
    }


    $get_count = 'select count(*) from '.$db->table('member').
        ' where `recommend_path` like \''.$path.'%\' and `account`<>\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC';

    //分页
    $page = intval(getGET('page'));

    if($page == 0)
    {
        $page = 1;
    }

    $step = 20;//每页显示20条记录
    $limit = ($page - 1) * $step;

    $limit = ' limit '.$limit.','.$step;

    $total_count = intval($db->fetchOne($get_count));

    $total_page = intval($total_count/$step);
    if($total_count%$step)
    {
        $total_page++;
    }

    assign('total_page', $total_page);
    assign('total_count', $total_count);
    assign('page', $page);
    //其他参数
    assign('param_list', $param_list);
    assign('account', $account);
    assign('level_id', $level_id);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

    //分页结束
    $get_user_list = 'select `account`,`name`,`mobile`,`add_time`,`level_id`,`status` from '.$db->table('member').
        ' where `recommend_path` like \''.$path.'%\' and `account`<>\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC'.$limit;

    $user_list = $db->fetchAll($get_user_list);

    assign('user_list', $user_list);
}

if('network' == $act)
{
    assign('sub_title', '团队网络');
}

if('order' == $act)
{
    assign('sub_title', '团队业绩');

    $param_list = '';
    $where = '';

    $path = $member_info['recommend_path'];

    $get_accounts = 'select `account` from '.$db->table('member').' where `recommend_path` like \''.$path.'%\' and `account`<>\''.$_SESSION['account'].'\'';
    $accounts = $db->fetchAll($get_accounts);

    $account_str = '';
    foreach($accounts as $ac)
    {
        $account_str .= $ac['account'].'\',\'';
    }
    $account_str = substr($account_str, 0, strlen($account_str)-3);

    $order_sn = trim(getGET('order_sn'));
    if($order_sn != '')
    {
        $where .= ' and `order_sn`=\''.$order_sn.'\'';
        $param_list .= '&order_sn='.$order_sn;
    }

    $type = intval(getGET('type'));
    if($type > 0)
    {
        $where .= ' and `type`=\''.$type.'\'';
        $param_list .= '&type='.$type;
    }

    $begin_time = trim(getGET('begin_time'));
    if($begin_time != '' && $begin_time = strtotime($begin_time.' 00:00:00'))
    {
        $where .= ' and `add_time`>='.$begin_time;
        $param_list .= '&begin_time='.date('Y-m-d', $begin_time);
    }

    $end_time = trim(getGET('end_time'));
    if($end_time != '' && $end_time = strtotime($end_time.' 23:59:59'))
    {
        $where .= ' and `add_time`<='.$end_time;
        $param_list .= '&end_time='.date('Y-m-d', $end_time);
    }


    $get_count = 'select count(*) from '.$db->table('order').
        ' where `account` in (\''.$account_str.'\') '.$where.' order by `add_time` DESC';
    //分页
    $page = intval(getGET('page'));

    if($page == 0)
    {
        $page = 1;
    }

    $step = 20;//每页显示20条记录
    $limit = ($page - 1) * $step;

    $limit = ' limit '.$limit.','.$step;

    $total_count = intval($db->fetchOne($get_count));

    $total_page = intval($total_count/$step);
    if($total_count%$step)
    {
        $total_page++;
    }

    assign('total_page', $total_page);
    assign('total_count', $total_count);
    assign('page', $page);
    //其他参数
    assign('param_list', $param_list);
    assign('order_sn', $order_sn);
    assign('type', $type);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

    //分页结束
    $get_order_list = 'select * from '.$db->table('order').
                      ' where `account` in (\''.$account_str.'\') '.$where.' order by `add_time` DESC'.$limit;

    $order_list = $db->fetchAll($get_order_list);

    assign('order_list', $order_list);
}

//获取轮播广告
$get_ads = 'select `img` from '.$db->table('ad').' where `ad_pos_id`=1 order by `order_view` ASC';
$ads = $db->fetchAll($get_ads);
assign('ads', $ads);

assign('act', $act);
$smarty->display('group.phtml');
