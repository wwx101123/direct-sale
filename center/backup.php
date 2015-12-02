<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/10/27
 * Time: 下午11:15
 */
include 'library/init.inc.php';
back_base_init();

$template = 'backup/';
assign('subTitle', '数据备份');

$action = 'edit|add|view|delete';
$operation = 'backup';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));

if('backup' == $opera)
{
    $respone = array('error'=>1, 'msg'=>'');

    $file = backup();

    if($file)
    {
        $response['error'] = 0;
        $response['msg'] = '备份成功<a href="'.$file.'">备份文件</a>';
        show_system_message('备份成功');
    } else {
        $response['msg'] = '系统繁忙，请稍后再试';
        show_system_message($response['msg']);
    }

    echo json_encode($response);
    exit;
}

$files = array();
$dir = dir('backup');
$pattern = '/db-backup-\d+/';
while($path = $dir->read())
{
    if(preg_match($pattern, $path))
    {
        $year = substr($path, 10, 4);
        $month = substr($path, 14, 2);
        $day = substr($path, 16, 2);

        $date = $year.'-'.$month.'-'.$day;
        $files[] = array(
            'date' => $date,
            'url' => $path
        );
    }
}

assign('files', $files);

$template .= $act.'.phtml';
$smarty->display($template);