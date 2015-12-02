<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 上午11:52
 */
include 'library/init.inc.php';

$param_list = '';
$where = '';

$begin_time = trim(getGET('begin_time'));
if($begin_time != '' && $begin_time = strtotime($begin_time.' 00:00:00'))
{
    $where .= ' and `add_time`>='.$begin_time;
    $param_list .= '&begin_time='.date('Y-m-d', $begin_time);
}

$end_time = trim(getGET('end_time'));
if($end_time != '' && $end_time = strtotime($end_time.' 23:59:59'))
{
    $where .= ' and `end_time`<='.$end_time;
    $param_list .= '&end_time='.date('Y-m-d', $end_time);
}


$get_count = 'select count(*) from '.$db->table('message').
    ' where `to`=\''.$_SESSION['account'].'\' or `from`=\''.$_SESSION['account'].'\' '.$where.' order by `add_time` DESC';

//分页
$page = intval(getGET('page'));

if($page == 0)
{
    $page = 1;
}

$step = 7;//每页显示20条记录
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
assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time) : '');
assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time) : '');

//分页结束
$get_message_list = 'select * from '.$db->table('message').
    ' where `to`=\''.$_SESSION['account'].'\' or `from`=\''.$_SESSION['account'].'\' '.$where.' order by `path` ASC,`add_time` DESC'.$limit;

$message_list = $db->fetchAll($get_message_list);

$message_ids = '';
if($message_list)
{
    foreach ($message_list as $m)
    {
        $message_ids .= $m['id'] . ',';
    }
}
$message_ids = substr($message_ids, 0, strlen($message_ids)-1);
$sql = 'update '.$db->table('message').' set `status`=1 where `id` in ('.$message_ids.')';
$db->update($sql);

assign('message_list', $message_list);

$smarty->display('message.phtml');