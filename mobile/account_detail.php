<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 上午11:49
 */
include 'library/init.inc.php';

$get_account_detail = 'select * from '.$db->table('account').' where `account`=\''.$_SESSION['account'].'\' order by `add_time` DESC';

$account_detail = $db->fetchAll($get_account_detail);
if($account_detail) {
    foreach ($account_detail as $k => $ad) {
        $account_detail[$k]['show_type'] = $lang['account']['type_' . $ad['type']];
        $account_detail[$k]['add_time'] = date('Y-m-d H:i:s', $ad['add_time']);
    }
}

assign('account_detail', $account_detail);
$smarty->display('account_detail.phtml');
