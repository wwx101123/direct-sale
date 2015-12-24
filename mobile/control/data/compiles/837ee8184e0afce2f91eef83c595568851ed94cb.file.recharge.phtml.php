<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:19:31
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/recharge/recharge.phtml" */ ?>
<?php /*%%SmartyHeaderCode:176415531056719d236c3ed5-75935524%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '837ee8184e0afce2f91eef83c595568851ed94cb' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/recharge/recharge.phtml',
      1 => 1450281093,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '176415531056719d236c3ed5-75935524',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'act' => 0,
    'count' => 0,
    'recharge_sn' => 0,
    'account' => 0,
    'status' => 0,
    'begin_time' => 0,
    'end_time' => 0,
    'recharge_list' => 0,
    'recharge' => 0,
    'lang' => 0,
    'total' => 0,
    'totalPage' => 0,
    'go_first' => 0,
    'keyword' => 0,
    'has_prev' => 0,
    'page' => 0,
    'has_many_prev' => 0,
    'show_page' => 0,
    'pageNum' => 0,
    'has_many_next' => 0,
    'go_last' => 0,
    'has_next' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56719d23889427_46022845',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56719d23889427_46022845')) {function content_56719d23889427_46022845($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

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
                    <label>充值编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="recharge_sn" value="<?php echo $_smarty_tpl->tpl_vars['recharge_sn']->value;?>
">&nbsp;
                    <label>会员编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="account" value="<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
">&nbsp;
                    <label>充值状态：</label>
                    <select name="status">
                        <option value="-1">全部</option>
                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected="selected"<?php }?>>待支付</option>
                        <option value="3" <?php if ($_smarty_tpl->tpl_vars['status']->value==3){?>selected="selected"<?php }?>>已支付</option>
                    </select><br/><br/>
                    <label>起止时间：</label>
                    <input class="text-input w150" type="text" id="begin_time" name="begin_time" value="<?php echo $_smarty_tpl->tpl_vars['begin_time']->value;?>
">&nbsp;
                    至
                    <input class="text-input w150" type="text" id="end_time" name="end_time" value="<?php echo $_smarty_tpl->tpl_vars['end_time']->value;?>
">&nbsp;
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
                </p>
            </fieldset>
            </form>
            <div class="clear"></div>
            <fieldset>
                <div class="pull-left">
                    <a class="btn btn-primary" href="javascript:order_export();">导出全部申请</a>
                    <a class="btn btn-primary" href="javascript:order_selected_export();">导出选中申请</a>
                </div>
                <div class="clear"></div>
            </fieldset>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th><a href="javascript:select_all();">全选</a>/<a href="javascript:deselect();">反选</a></th>
                <th>充值编号</th>
                <th>会员账号</th>
                <th>充值金额</th>
                <th>备注</th>
                <th>状态</th>
                <th class="text-right">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['recharge'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['recharge']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['recharge_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['recharge']->key => $_smarty_tpl->tpl_vars['recharge']->value){
$_smarty_tpl->tpl_vars['recharge']->_loop = true;
?>
            <tr>
                <td><input type="checkbox" name="id[]" value="<?php echo $_smarty_tpl->tpl_vars['recharge']->value['id'];?>
"/></td>
                <td><?php echo $_smarty_tpl->tpl_vars['recharge']->value['recharge_sn'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['recharge']->value['account'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['recharge']->value['real_amount'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['recharge']->value['remark'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['lang']->value['recharge']["status_".((string)$_smarty_tpl->tpl_vars['recharge']->value['status'])];?>
</td>
                <td>
                    <?php echo $_smarty_tpl->tpl_vars['recharge']->value['operation'];?>

                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="7">
                    <div class="pull-right">
                        <div class="pages">
                            <span>共有<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
条，<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
页，每页显示：<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
条</span>
                            <?php if ($_smarty_tpl->tpl_vars['go_first']->value){?>
                            <a href="?page=1&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">首页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_prev']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value-1;?>
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
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
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
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
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">末页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_next']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">下一页</a>
                            <?php }?>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript" src="laydate/laydate.js"></script>
        <script type="text/javascript">
            var start = {
                elem: '#begin_time',
                format: 'YYYY-MM-DD',
                max: '9999-12-31 23:59:59', //最大日期
                istime: false,
                istoday: true,
                choose: function(datas){
                    end.min = datas; //开始日选好后，重置结束日的最小日期
                    end.start = datas //将结束日的初始值设定为开始日
                    end.click;
                }
            };

            var end = {
                elem: '#end_time',
                format: 'YYYY-MM-DD',
                max: '9999-12-31 23:59:59',
                istime: false,
                istoday: true,
                choose: function(datas){
                    start.max = datas; //结束日选好后，重置开始日的最大日期
                }
            };

            laydate(start);
            laydate(end);
        </script>
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
                    "action": window.location.href
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
                    "name": "recharge_id",
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
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
        <form method="post">
            <fieldset>
                <p>
                    <label class="l-title">会员账号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['recharge']->value['account'];?>

                </p>
                <p>
                    <label class="l-title">充值金额：</label>
                    <?php echo $_smarty_tpl->tpl_vars['recharge']->value['real_amount'];?>

                </p>
                <p>
                    <label class="l-title">备注：</label>
                    <?php echo $_smarty_tpl->tpl_vars['recharge']->value['remark'];?>

                </p>
                <p>
                    <label class="l-title">汇入账户：</label>
                    <?php echo str_replace('<br/>',"\n",$_smarty_tpl->tpl_vars['recharge']->value['payment_name']);?>

                </p>
                <p>
                    <label class="l-title">备注：</label>
                    <input class="text-input w300" type="text" id="remark" name="remark" placeholder="请输入备注" value="">
                </p>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="edit"/>
                    <input type="hidden" name="erecharge_sn" value="<?php echo $_smarty_tpl->tpl_vars['recharge']->value['recharge_sn'];?>
">
                    <button class="btn btn-primary" type="submit">设为已处理</button> &nbsp;<a href="recharge.php" class="btn btn-primary">返回</a>
                </p>
            </fieldset>
        </form>
        <?php }?>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>