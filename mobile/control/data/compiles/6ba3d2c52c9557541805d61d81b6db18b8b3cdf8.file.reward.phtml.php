<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:34:22
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/reward/reward.phtml" */ ?>
<?php /*%%SmartyHeaderCode:91345725671a09e19bb39-19538884%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6ba3d2c52c9557541805d61d81b6db18b8b3cdf8' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/reward/reward.phtml',
      1 => 1450287046,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '91345725671a09e19bb39-19538884',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'act' => 0,
    'count' => 0,
    'account' => 0,
    'status' => 0,
    'begin_time' => 0,
    'end_time' => 0,
    'reward_list' => 0,
    'reward' => 0,
    'total_reward' => 0,
    'total' => 0,
    'totalPage' => 0,
    'go_first' => 0,
    'has_prev' => 0,
    'page' => 0,
    'has_many_prev' => 0,
    'show_page' => 0,
    'pageNum' => 0,
    'has_many_next' => 0,
    'go_last' => 0,
    'has_next' => 0,
    'recommend_reward' => 0,
    'manage_reward' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a09e3eceb2_39942029',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a09e3eceb2_39942029')) {function content_5671a09e3eceb2_39942029($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

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
                    <label>会员编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="account" value="<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
">&nbsp;
                    <label>奖金类型：</label>
                    <select name="type">
                        <option value="0">全部</option>
                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected="selected"<?php }?>>推荐奖</option>
                        <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value==2){?>selected="selected"<?php }?>>管理奖</option>
                    </select>
                    <label>状态：</label>
                    <select name="status">
                        <option value="-1">全部</option>
                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected="selected"<?php }?>>待发放</option>
                        <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value==2){?>selected="selected"<?php }?>>已发放</option>
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
                    <a class="btn btn-primary" href="javascript:order_export();">导出全部奖金</a>
                    <a class="btn btn-primary" href="javascript:order_selected_export();">导出选中奖金</a>
                    <a class="btn btn-primary" href="javascript:reward_send();">发放所有奖金</a>
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
                <th>会员账号</th>
                <th>奖金</th>
                <th>奖金状态</th>
                <th>结算时间</th>
                <th>发放时间</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['reward'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['reward']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['reward_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['reward']->key => $_smarty_tpl->tpl_vars['reward']->value){
$_smarty_tpl->tpl_vars['reward']->_loop = true;
?>
            <tr>
                <td><input type="checkbox" name="id[]" value="<?php echo $_smarty_tpl->tpl_vars['reward']->value['id'];?>
"/></td>
                <td><?php echo $_smarty_tpl->tpl_vars['reward']->value['account'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['reward']->value['reward'];?>
</td>
                <td><?php if ($_smarty_tpl->tpl_vars['reward']->value['status']==1){?>待发放<?php }else{ ?>已发放<?php }?></td>
                <td><?php echo date('Y-m-d H:i:s',$_smarty_tpl->tpl_vars['reward']->value['add_time']);?>
</td>
                <td><?php if ($_smarty_tpl->tpl_vars['reward']->value['solve_time']){?><?php echo date('Y-m-d H:i:s',$_smarty_tpl->tpl_vars['reward']->value['solve_time']);?>
<?php }else{ ?>未发放<?php }?></td>
                <td><?php echo $_smarty_tpl->tpl_vars['reward']->value['remark'];?>
</td>
            </tr>
            <?php } ?>
            <tr>
                <td></td>
                <td>小计:</td>
                <td><?php echo $_smarty_tpl->tpl_vars['total_reward']->value;?>
</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="pull-right">
                        <div class="pages">
                            <span>共有<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
条，<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
页，每页显示：<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
条</span>
                            <?php if ($_smarty_tpl->tpl_vars['go_first']->value){?>
                            <a href="?page=1&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
">首页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_prev']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value-1;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
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
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['pageNum']->value;?>
</a>
                            <?php }?>
                            <?php } ?>
                            <?php if ($_smarty_tpl->tpl_vars['has_many_next']->value){?>
                            ...
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['go_last']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
">末页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_next']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
">下一页</a>
                            <?php }?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>奖金总计:</td>
                <td><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['recommend_reward']->value+$_smarty_tpl->tpl_vars['manage_reward']->value);?>
</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
            function reward_send() {
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
                    "value": "send"
                });

                form.append(input);
                $("html").append(form);
                form.submit();
            }

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
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>