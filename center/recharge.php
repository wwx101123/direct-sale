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

$template = 'recharge/';
assign('subTitle', '充值管理');

$action = 'edit|add|view|delete';
$operation = 'edit|add';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'edit')
{
    $recharge_sn = getPOST('erecharge_sn');
    $remark = getPOST('remark');

    if($recharge_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $recharge_sn = $db->escape($recharge_sn);
    }

    if($remark == '')
    {
        show_system_message('请填写备注信息');
    } else {
        $remark = $db->escape($remark);
    }

    if(update_recharge($recharge_sn, 3, $_SESSION['account'], $remark))
    {
        show_system_message('充值记录已处理', array(array('link'=>'recharge.php', 'alt'=>'充值列表')));
    } else {
        show_system_message('系统繁忙');
    }
}

if('edit' == $act)
{
    $recharge_sn = getGET('sn');

    if($recharge_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $recharge_sn = $db->escape($recharge_sn);
    }

    $get_recharge = 'select * from '.$db->table('recharge').' where `recharge_sn`=\''.$recharge_sn.'\'';
    $recharge = $db->fetchRow($get_recharge);

    assign('recharge', $recharge);
}

if('view' == $act)
{
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');
    $status = getGET('status');
    $recharge_sn = getGET('recharge_sn');

    $where = ' where 1 ';

    if($status != '' and $status >= 0) {
        $where .= ' and `status`='.intval($status);
    } else {
        $status = -1;
    }

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
    }

    if($begin_time != '')
    {
        $begin_time = strtotime($begin_time.' 00:00:00');
        if($begin_time)
        {
            $where .= ' and `recharge_sn`>=\'R'.intval($begin_time).'000\'';
        }
    }

    if($end_time != '')
    {
        $end_time = strtotime($end_time.' 23:59:59');
        if($end_time)
        {
            $where .= ' and `recharge_sn`<=\'R'.intval($end_time).'999\'';
        }
    }

    if($recharge_sn != '')
    {
        $recharge_sn = $db->escape($recharge_sn);
        $where .= ' and `recharge_sn`=\''.$recharge_sn.'\'';
    }

    $get_total = 'select count(*) from '.$db->table('recharge').$where;
    $total = $db->fetchOne($get_total);

    $count_expected = array(10, 25, 50, 100);
    $page = intval($page);
    $count = intval($count);
    if( !in_array($count, $count_expected) )
    {
        $count = 10;
    }

    $total_page = ceil($total / $count);

    $page = ( $page > $total_page ) ? $total_page : $page;
    $page = ( $page <= 0 ) ? 1 : $page;

    $offset = ($page - 1) * $count;

    create_pager($page, $total_page, $total);
    assign('count', $count);
    assign('account', $account);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time): '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time): '');
    assign('recharge_sn', $recharge_sn);
    assign('status', $status);

    $get_recharge_list = 'select * from '.$db->table('recharge').$where.' order by `recharge_sn` DESC limit '.$offset.','.$count;

    $recharge_list = $db->fetchAll($get_recharge_list);

    if($recharge_list)
    {
        foreach ($recharge_list as $k => $r)
        {
            if (check_purview('pur_recharge_edit', $_SESSION['purview']) && $r['status'] == 1)
            {
                $recharge_list[$k]['operation'] = '<a href="recharge.php?act=edit&sn=' . $r['recharge_sn'] . '">处理</a>';
            } else {
                $recharge_list[$k]['operation'] = '';
            }
        }
    }

    assign('recharge_list', $recharge_list);
}

assign('act', $act);
$smarty->display($template.'recharge.phtml');