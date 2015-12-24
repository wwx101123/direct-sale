<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/8/20
 * Time: 下午3:52
 */
include 'library/init.inc.php';

$get_recharge_list = 'select * from '.$db->table('recharge').' where `account`=\''.$_SESSION['account'].'\' order by `recharge_sn` DESC';

$recharge_list = $db->fetchAll($get_recharge_list);

if($recharge_list) {
    foreach ($recharge_list as $k => $r) {
        $recharge_list[$k]['show_status'] = $lang['recharge']['status_' . $r['status']];
    }
}

assign('recharge_list', $recharge_list);
$smarty->display('recharge_detail.phtml');