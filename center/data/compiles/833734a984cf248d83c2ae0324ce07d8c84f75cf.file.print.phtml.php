<?php /* Smarty version Smarty-3.1.13, created on 2015-11-17 14:14:34
         compiled from "/Library/WebServer/Documents/facm/control/themes/order/print.phtml" */ ?>
<?php /*%%SmartyHeaderCode:1572294146564ac5ca7adeb4-19664275%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '833734a984cf248d83c2ae0324ce07d8c84f75cf' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/order/print.phtml',
      1 => 1447734742,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1572294146564ac5ca7adeb4-19664275',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pageTitle' => 0,
    'order' => 0,
    'order_detail' => 0,
    'product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_564ac5ca8a90c4_05576737',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564ac5ca8a90c4_05576737')) {function content_564ac5ca8a90c4_05576737($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <style type="text/css">
        <!--
        .print-table {
            text-align: center;
            line-height: 26px;
            font-size: 14px;
            background: #333;
        }
        .print-table-img {
            position: absolute;
            left: 0;
            top: 0;
            height: 40px;
        }
        .print-title {
            font-size: 20px;
            text-align: center;
            line-height: 40px;
            margin: 0;
            padding: 0;
            margin-bottom: 10px;
        }
        tr {
            background: #fff;
        }
        -->
    </style>
</head>
<body>
<!-- 打印发货单 -->
<h1 class="print-title">
    <img src="images/tb_logo.jpg" class="print-table-img"/>
    发货单
</h1>
<table class="print-table" border="0" cellpadding="3" cellspacing="1" width="100%" align="center">
    <tr>
        <td colspan="5" align="left">客户收货地址：<?php echo $_smarty_tpl->tpl_vars['order']->value['address'];?>
</td>
        <td colspan="2" align="left">邮编：<?php echo $_smarty_tpl->tpl_vars['order']->value['zipcode'];?>
</td>
        <td colspan="2" align="left">电话：<?php echo $_smarty_tpl->tpl_vars['order']->value['mobile'];?>
</td>
    </tr>
    <tr>
        <td colspan="5" align="left">收货人：<?php echo $_smarty_tpl->tpl_vars['order']->value['consignee'];?>
</td>
        <td colspan="2" align="left">单号：<?php echo $_smarty_tpl->tpl_vars['order']->value['order_sn'];?>
</td>
        <td colspan="2" align="left">日期：<?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['order']->value['add_time']);?>
</td>
    </tr>
    <tr>
        <td>序号</td>
        <td>商品名称</td>
        <td>规格</td>
        <td>单位</td>
        <td>数量</td>
        <td>单价（元）</td>
        <td>金额（元）</td>
        <td>PV值</td>
        <td>PV值合计</td>
    </tr>
    <?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_detail']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['order_detail']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['order_detail']['iteration']++;
?>
    <tr>
        <td><?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['order_detail']['iteration'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
</td>
        <td></td>
        <td></td>
        <td><?php echo $_smarty_tpl->tpl_vars['product']->value['number'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['product']->value['price'];?>
</td>
        <td><?php echo sprintf('%.2f',$_smarty_tpl->tpl_vars['product']->value['price']*$_smarty_tpl->tpl_vars['product']->value['number']);?>
</td>
        <td></td>
        <td></td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="4">合计</td>
        <td><?php echo count($_smarty_tpl->tpl_vars['order_detail']->value);?>
</td>
        <td></td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="9" align="left"><strong>合计</strong>（大写）：<?php echo $_smarty_tpl->tpl_vars['order']->value['amount_upper'];?>
</td>
    </tr>
    <tr>
        <td colspan="9" align="left">备注：<?php echo $_smarty_tpl->tpl_vars['order']->value['remark'];?>
</td>
    </tr>
    <tr>
        <td colspan="9" align="left">注：第一联：白色单 - 财务部 第二联：红色单 - 客户 第三联：蓝色单 - 物流部 第四联：黄色单 - 仓库</td>
    </tr>
    <tr align="left">
        <td colspan="2">发货人：杨宽</td>
        <td colspan="2">部门：物流部</td>
        <td colspan="2">公司电话：0398-2513666</td>
        <td colspan="3">公司传真：0398-2513669</td>
    </tr>
    <tr align="left">
        <td colspan="9">发货公司：三门峡九健电子商务有限公司</td>
    </tr>
    <tr align="left">
        <td colspan="9">地址：三门峡市湖滨区五原西路商会大厦A座17层 邮编：472000</td>
    </tr>
    <tr align="left">
        <td colspan="2">财务：</td>
        <td colspan="2">发货人：</td>
        <td colspan="2">制单人：</td>
        <td colspan="3"></td>
    </tr>
</table>
</body>
</html><?php }} ?>