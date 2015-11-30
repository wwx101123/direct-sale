<?php /* Smarty version Smarty-3.1.13, created on 2015-11-17 12:34:04
         compiled from "/Library/WebServer/Documents/facm/control/themes/order/order.phtml" */ ?>
<?php /*%%SmartyHeaderCode:273299416564aae3c48c208-22743505%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2ca4111b4b1c6e8d8c66d226f37bb92d339edf32' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/order/order.phtml',
      1 => 1447732488,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '273299416564aae3c48c208-22743505',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'act' => 0,
    'count' => 0,
    'order_sn' => 0,
    'account' => 0,
    'status' => 0,
    'order_list' => 0,
    'order' => 0,
    'LANG' => 0,
    'total' => 0,
    'totalPage' => 0,
    'go_first' => 0,
    'stauts' => 0,
    'has_prev' => 0,
    'page' => 0,
    'has_many_prev' => 0,
    'show_page' => 0,
    'pageNum' => 0,
    'has_many_next' => 0,
    'go_last' => 0,
    'has_next' => 0,
    'order_detail' => 0,
    'product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_564aae3c6fffe1_98474100',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564aae3c6fffe1_98474100')) {function content_564aae3c6fffe1_98474100($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<!-- content -->
<div id="content">
    <div class="content-title">
        <h5 class="pull-left"><?php echo $_smarty_tpl->tpl_vars['subTitle']->value;?>
</h5>
        <div class="clear"></div>
    </div>
    <div class="adminGroup-main">
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='view'){?>
        <div class="article-main-header">
            <form action="" method="get" />
            <fieldset>
                <p>
                <div class="pull-left">
                    显示
                    <select name="count" class="w75">
                        <option value="10" <?php if ($_smarty_tpl->tpl_vars['count']->value==10){?>selected="selected"<?php }?>>10</option>
                        <option value="25" <?php if ($_smarty_tpl->tpl_vars['count']->value==25){?>selected="selected"<?php }?>>25</option>
                        <option value="50" <?php if ($_smarty_tpl->tpl_vars['count']->value==50){?>selected="selected"<?php }?>>50</option>
                        <option value="100" <?php if ($_smarty_tpl->tpl_vars['count']->value==100){?>selected="selected"<?php }?>>100</option>
                    </select>
                    项结果
                </div>
                <div class="pull-right">
                    <label>订单编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="order_sn" value="<?php echo $_smarty_tpl->tpl_vars['order_sn']->value;?>
">&nbsp;
                    <label>会员编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="account" value="<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
">&nbsp;
                    <label>明细类型：</label>
                    <select name="status">
                        <option value="0">全部</option>
                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected="selected"<?php }?>>待发货</option>
                        <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value==2){?>selected="selected"<?php }?>>已发货</option>
                    </select>
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
                </p>
            </fieldset>
            </form>
            <div class="clear"></div>
            <fieldset>
                <div class="pull-left">
                    <a class="btn btn-primary" href="javascript:order_export();">导出全部订单</a>
                    <a class="btn btn-primary" href="javascript:order_selected_export();">导出选中订单</a>
                </div>
                <div class="clear"></div>
            </fieldset>
        </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='view'){?>
        <table class="table">
            <thead>
            <tr>
                <th><a href="javascript:select_all();">全选</a>/<a href="javascript:deselect();">反选</a></th>
                <th>订单编号</th>
                <th>会员账号</th>
                <th>订单总额</th>
<!--                <th>积分总额</th>-->
<!--                <th>赠送积分</th>-->
                <th>订单状态</th>
                <th>下单时间</th>
                <th>备注</th>
                <th class="text-right">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
            <tr>
                <td><input type="checkbox" name="id[]" value="<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
"/></td>
                <td><?php echo $_smarty_tpl->tpl_vars['order']->value['order_sn'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['order']->value['account'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
<!--                <td><?php echo $_smarty_tpl->tpl_vars['order']->value['integral_amount'];?>
</td>-->
<!--                <td><?php echo $_smarty_tpl->tpl_vars['order']->value['integral_given_amount'];?>
</td>-->
                <td><?php echo $_smarty_tpl->tpl_vars['LANG']->value['order']["status_".((string)$_smarty_tpl->tpl_vars['order']->value['status'])];?>
</td>
                <td><?php echo date('Y-m-d H:i:s',$_smarty_tpl->tpl_vars['order']->value['add_time']);?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['order']->value['remark'];?>
</td>
                <td>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['operation'];?>

                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="10">
                    <div class="pull-right">
                        <div class="pages">
                            <span>共有<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
条，<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
页，每页显示：<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
条</span>
                            <?php if ($_smarty_tpl->tpl_vars['go_first']->value){?>
                            <a href="?page=1&status=<?php echo $_smarty_tpl->tpl_vars['stauts']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">首页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_prev']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value-1;?>
&status=<?php echo $_smarty_tpl->tpl_vars['stauts']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">上一页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_many_prev']->value){?>
                            ...
                            <?php }?>
                            <?php  $_smarty_tpl->tpl_vars['pageNum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pageNum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['show_page']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pageNum']->key => $_smarty_tpl->tpl_vars['pageNum']->value){
$_smarty_tpl->tpl_vars['pageNum']->_loop = true;
?>
                            <?php if ($_smarty_tpl->tpl_vars['pageNum']->value==$_smarty_tpl->tpl_vars['page']->value){?>
                            <b><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</b>
                            <?php }else{ ?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['pageNum']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['stauts']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['pageNum']->value;?>
</a>
                            <?php }?>
                            <?php } ?>
                            <?php if ($_smarty_tpl->tpl_vars['has_many_next']->value){?>
                            ...
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['go_last']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['stauts']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">末页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_next']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
&status=<?php echo $_smarty_tpl->tpl_vars['stauts']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">下一页</a>
                            <?php }?>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">
            function order_export() {
                var form = $("<form>");
                form.attr({
                    "style":"display:none;",
                    "method": "post",
                    "action": window.location.href
                });

                var input = $("<input>");
                input.attr({
                    "type": "hidden",
                    "name": "opera",
                    "value": "export"
                });

                form.append(input);
                $("html").append(form);
                form.submit();
            }

            function order_selected_export() {
                var order_id = "";

                $("input[name='id[]']").each(function(i, e){
                    if(e.checked) {
                        order_id += e.value+",";
                    }
                });

                if(order_id == "") return false;

                var form = $("<form>");
                form.attr({
                    "style":"display:none;",
                    "method": "post",
                    "action": "order.php"
                });

                var input = $("<input>");
                input.attr({
                    "type": "hidden",
                    "name": "opera",
                    "value": "export"
                });

                form.append(input);

                var input = $("<input>");
                input.attr({
                    "type": "hidden",
                    "name": "order_id",
                    "value": order_id
                });

                form.append(input);

                $("html").append(form);
                form.submit();
            }

            function select_all() {
                $("input[name='id[]']").each(function(i, e){
                    e.checked = true;
                });
            }

            function deselect() {
                $("input[name='id[]']").each(function(i, e){
                    if(e.checked) {
                        e.checked = false;
                    } else {
                        e.checked = true;
                    }
                });
            }
        </script>
        <?php }?>

        <!-- 充值处理 -->
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'||$_smarty_tpl->tpl_vars['act']->value=='detail'){?>
        <form method="post">
            <fieldset>
                <p>
                    <label class="l-title">订单编号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['order_sn'];?>

                </p>
                <p>
                    <label class="l-title">会员账号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['account'];?>

                </p>
                <p>
                    <label class="l-title">订单总额：</label>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>

                </p>
                <p>
                    <label class="l-title">积分总额：</label>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['integral_amount'];?>

                </p>
                <p>
                    <label class="l-title">赠送积分：</label>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['integral_given_amount'];?>

                </p>
                <p>
                    <label class="l-title">备注：</label>
                    <?php echo $_smarty_tpl->tpl_vars['order']->value['remark'];?>

                </p>
                <p>
                    <label class="l-title">收货信息：</label>
                    收货人：<?php echo $_smarty_tpl->tpl_vars['order']->value['consignee'];?>

                    联系电话：<?php echo $_smarty_tpl->tpl_vars['order']->value['mobile'];?>

                    收货地址：<?php echo $_smarty_tpl->tpl_vars['order']->value['address'];?>

                    邮政编码：<?php echo $_smarty_tpl->tpl_vars['order']->value['zipcode'];?>

                </p>
                <?php if ($_smarty_tpl->tpl_vars['order']->value['status']==2&&$_smarty_tpl->tpl_vars['act']->value=='detail'){?>
                <p>
                    <label class="l-title">物流信息：</label>
                    物流公司：<?php echo $_smarty_tpl->tpl_vars['order']->value['delivery_company'];?>

                    物流单号：<?php echo $_smarty_tpl->tpl_vars['order']->value['delivery_sn'];?>

                </p>
                <?php }?>
                <p>
                    <label class="l-title">订单详情:</label>
                    <table class="table">
                        <thead>
                        <tr><th>产品编号</th><th>产品名称</th><th>产品价格</th><th>积分</th><th>赠送积分</th><th>购买数量</th></tr>
                        </thead>
                        <?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_detail']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
                        <tr>
                            <td><?php echo $_smarty_tpl->tpl_vars['product']->value['product_sn'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['product']->value['price'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['product']->value['integral'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['product']->value['integral_given'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['product']->value['number'];?>
</td>
                        </tr>
                        <?php } ?>
                    </table>
                </p>
                <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
                <p>
                    <label class="l-title">物流公司：</label>
                    <input class="text-input w300" type="text" id="delivery_company" name="delivery_company" placeholder="请输入物流公司" value="">
                </p>
                <p>
                    <label class="l-title">物流单号：</label>
                    <input class="text-input w300" type="text" id="delivery_sn" name="delivery_sn" placeholder="请输入物流单号" value="">
                </p>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="edit"/>
                    <input type="hidden" name="eorder_sn" value="<?php echo $_smarty_tpl->tpl_vars['order']->value['order_sn'];?>
">
                    <button class="btn btn-primary" type="submit">设为已发货</button> &nbsp;<a href="order.php" class="btn btn-primary">返回</a>
                </p>
                <?php }?>
            </fieldset>
        </form>
        <?php }?>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>