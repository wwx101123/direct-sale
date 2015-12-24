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

$template = 'virtual_order/';
assign('subTitle', '订单管理');

$action = 'edit|add|view|delete|detail';
$operation = 'edit|add|export';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================
if($opera == 'export')
{
    $order_id = getPOST('order_id');
    $order_sn = getGET('order_sn');
    $account = getGET('account');
    $status = intval(getGET('status'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

    $sql = 'select * from '.$db->table('pay_order');
    $where = ' where 1';

    if($order_id != '')
    {
        $order_id = substr($order_id, 0, strlen($order_id)-1);

        $order_id = $db->escape($order_id);

        $where .= ' and `id` in ('.$order_id.')';
    } else {
        if($account != '')
        {
            $account = $db->escape($account);
            $where .= ' and `account`=\''.$account.'\'';
        }

        if($status > 0)
        {
            $where .= ' and `status`='.$status;
        }

        if($order_sn != '')
        {
            $order_sn = $db->escape($order_sn);
            $where .= ' and `order_sn`=\''.$order_sn.'\'';
        }

        if($begin_time != '')
        {
            $begin_time = strtotime($begin_time.' 00:00:00');
            if($begin_time)
            {
                $where .= ' and `add_time`>='.intval($begin_time);
            }
        }

        if($end_time != '')
        {
            $end_time = strtotime($end_time.' 23:59:59');
            if($end_time)
            {
                $where .= ' and `add_time`<='.intval($end_time);
            }
        }
    }

    $order_list = $db->fetchAll($sql.$where);
    $log->record($sql.$where);

    if(!$order_list)
    {
        echo '<script>alert("没有可以导出的数据");window.history.go(-1);</script>';
        exit;
    }

    $loader->includeClass('PHPExcel');

    $excel = new PHPExcel();
    $sheet = $excel->getActiveSheet(0);

    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(20);

    $row = 1;

    $sheet->setCellValue('A'.$row, '订单编号');
    $sheet->setCellValue('B'.$row, '会员账号');
    $sheet->setCellValue('C'.$row, '订单项目');
    $sheet->setCellValue('D'.$row, '推荐人');
    $sheet->setCellValue('E'.$row, '订单总额');
    $sheet->setCellValue('F'.$row, '订单状态');
    $sheet->setCellValue('G'.$row, '下单时间');
    $sheet->setCellValue('H'.$row, '支付时间');

    $row++;

    foreach($order_list as $order)
    {
        //第一行
        $sheet->setCellValueExplicit('A'.$row, $order['order_sn']);
        $sheet->setCellValue('B'.$row, $order['account']);
        $sheet->setCellValue('C'.$row, $order['item_name']);
        $sheet->setCellValue('D'.$row, $order['recommend']);
        $sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue('E'.$row, $order['total_amount']);
        $sheet->setCellValue('F'.$row, $lang['pay_order_status'][$order['status']]);
        $sheet->setCellValue('G'.$row, date('Y-m-d H:i:s', $order['add_time']));
        if($order['status'] == 3)
        {
            $sheet->setCellValue('H' . $row, date('Y-m-d H:i:s', $order['pay_time']));
        } else {
            $sheet->setCellValue('H' . $row, '');
        }

        $row++;
    }

    //输出
    $filename = date('YmdHis').'订单列表';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

if($opera == 'edit')
{
    $order_sn = getPOST('eorder_sn');
    $delivery_sn = getPOST('delivery_sn');
    $delivery_company = getPOST('delivery_company');

    if($order_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $order_sn = $db->escape($order_sn);
    }

    if($delivery_company == '')
    {
        show_system_message('请填写物流公司');
    } else {
        $delivery_company = $db->escape($delivery_company);
    }

    if($delivery_sn == '')
    {
        show_system_message('请填写物流单号');
    } else {
        $delivery_sn = $db->escape($delivery_sn);
    }

    $data = array(
        'delivery_time' => time(),
        'delivery_sn' => $delivery_sn,
        'delivery_name' => $delivery_company,
        'status' => 6
    );

    if($db->autoUpdate('order', $data, '`order_sn`=\''.$order_sn.'\''))
    {
        show_system_message('订单已发货', array(array('link'=>'order.php', 'alt'=>'订单列表')));
    } else {
        show_system_message('系统繁忙');
    }
}
//=============================================================================================
if('edit' == $act || 'detail' == $act)
{
    if('edit' == $act)
    {
        assign('subTitle', '订单发货');
    } else {
        assign('subTitle', '订单详情');
    }
    $order_sn = getGET('sn');

    if($order_sn == '')
    {
        show_system_message('参数错误');
    } else {
        $order_sn = $db->escape($order_sn);
    }

    $get_order = 'select * from '.$db->table('pay_order').' where `order_sn`=\''.$order_sn.'\'';
    $order = $db->fetchRow($get_order);

    $get_order_detail = 'select od.`product_sn`,p.`name`,od.`price`,od.`number` from '
                        .$db->table('order_detail').' as od join '.$db->table('product').' as p using(`product_sn`) '.
                        'where od.`order_sn`=\''.$order_sn.'\'';
    $order_detail = $db->fetchAll($get_order_detail);

    assign('order_detail', $order_detail);
    assign('order', $order);
}

if('view' == $act) {
    $page = getGET('page');
    $count = getGET('count');
    $account = getGET('account');
    $order_sn = getGET('order_sn');
    $status = intval(getGET('status'));
    $begin_time = getGET('begin_time');
    $end_time = getGET('end_time');

    $where = ' where 1 ';

    if($status > 0)
    {
        $status = intval($status);
        $where .= ' and `status`='.$status;
    }

    if ($account != '')
    {
        $account = $db->escape($account);
        $where .= ' and `account`=\''.$account.'\' ';
    }

    if($order_sn != '')
    {
        $order_sn = $db->escape($order_sn);
        $where .= ' and `order_sn`=\''.$order_sn.'\' ';
    }

    if($begin_time != '')
    {
        $begin_time = strtotime($begin_time.' 00:00:00');
        if($begin_time)
        {
            $where .= ' and `add_time`>='.intval($begin_time);
        }
    }

    if($end_time != '')
    {
        $end_time = strtotime($end_time.' 23:59:59');
        if($end_time)
        {
            $where .= ' and `add_time`<='.intval($end_time);
        }
    }

    $get_total = 'select count(*) from '.$db->table('pay_order').$where;
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
    assign('order_sn', $order_sn);
    assign('status', $status);
    assign('begin_time', $begin_time > 0 ? date('Y-m-d', $begin_time): '');
    assign('end_time', $end_time > 0 ? date('Y-m-d', $end_time): '');

    $get_order_list = 'select * from '.$db->table('pay_order').$where.' order by `order_sn` DESC limit '.$offset.','.$count;

    $order_list = $db->fetchAll($get_order_list);

    /*
    if($order_list)
    {
        foreach ($order_list as $k => $r)
        {
            if (check_purview('pur_order_edit', $_SESSION['purview']) && $r['status'] == 3)
            {
                $order_list[$k]['operation'] = '<a href="order.php?act=edit&sn=' . $r['order_sn'] . '">发货</a> | ';
            } else {
                $order_list[$k]['operation'] = '';
            }

            $order_list[$k]['operation'] .= '<a href="order.php?act=detail&sn=' . $r['order_sn'] . '">查看</a>';
        }
    }
    */
    assign('order_list', $order_list);
}

assign('act', $act);
$smarty->display($template.'order.phtml');