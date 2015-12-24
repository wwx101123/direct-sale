<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:16:44
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/member/member.phtml" */ ?>
<?php /*%%SmartyHeaderCode:24431165556719c7cea4f66-58776267%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '177d8cef9095fb7977c8f9b38831861f7531f0f8' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/member/member.phtml',
      1 => 1449994621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24431165556719c7cea4f66-58776267',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'act' => 0,
    'count' => 0,
    'account' => 0,
    'member_list' => 0,
    'member' => 0,
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
    'member_info' => 0,
    'member_level' => 0,
    'id' => 0,
    'level' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56719c7d1c1571_43193402',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56719c7d1c1571_43193402')) {function content_56719c7d1c1571_43193402($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

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
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
                </p>
            </fieldset>
            </form>
            <div class="clear"></div>
            <fieldset>
                <div class="pull-left">
                    <a class="btn btn-primary" href="?act=network">查看网络图</a>
                </div>
                <div class="clear"></div>
            </fieldset>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>会员账号</th>
                <th>会员等级</th>
                <th>真实姓名</th>
                <th>账户余额</th>
                <th>奖金余额</th>
                <th>待发奖金</th>
                <th>注册时间</th>
                <th class="text-right">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['member'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['member']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['member_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['member']->key => $_smarty_tpl->tpl_vars['member']->value){
$_smarty_tpl->tpl_vars['member']->_loop = true;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['account'];?>
</td>
                <td><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['member']->value['level_id'];?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['lang']->value['level'][$_tmp1];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['balance'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['reward'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['reward_await'];?>
</td>
                <td><?php echo date('Y-m-d H:i:s',$_smarty_tpl->tpl_vars['member']->value['add_time']);?>
</td>
                <td>
                    <?php echo $_smarty_tpl->tpl_vars['member']->value['operation'];?>

                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="8">
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
        <script type="text/javascript">
            function open_network(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要允许该会员查看网络图？")) {
                    $.post('member.php', { "opera":"open_network", "account":account }, ajax_handler, "json");
                    return true;
                }
                return false;
            }

            function close_network(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要禁止该会员查看网络图？")) {
                    $.post('member.php', { "opera":"close_network", "account":account }, ajax_handler, "json");
                    return true;
                }
                return false;
            }

            function frozen(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要冻结该会员？")) {
                    $.post('member.php', { "opera":"frozen", "account":account }, ajax_handler, "json");
                    return true;
                }
                return false;
            }

            function release(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要解冻该会员？")) {
                    $.post('member.php', { "opera":"release", "account":account }, ajax_handler, "json");
                    return true;
                }
                return false;
            }

            function unlock(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要解锁该会员？")) {
                    $.post('member.php', { "opera":"unlock", "account":account }, ajax_handler, "json");
                    return true;
                }
                return false;
            }
            //锁定用户
            function lock(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要锁定该会员？")) {
                    $.post('member.php', { "opera":"lock", "account":account }, ajax_handler, "json");
                    return true;
                }
                return false;
            }

            function ajax_handler(response) {
                alert(response.msg);
                window.location.reload();
            }
            //重置密码
            function reset_password(account) {
                if(account == '') {
                    return false;
                }

                if(confirm("您确定要重置该会员的密码？")) {
                    $.post('member.php', { "opera":"reset", "account":account }, reset_password_handler, "json");
                    return true;
                }
                return false;
            }

            function reset_password_handler(response) {
                alert(response.msg);
            }
        </script>
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['act']->value=='network'){?>
        <fieldset>
            <div class="pull-left">
                <a class="btn btn-primary" href="javascript:history.back();">返回</a>
            </div>
            <div class="clear"></div>
        </fieldset>
        <div id="ztree" class="ztree"></div>
        <link type="text/css" rel="stylesheet" href="css/zTreeStyle.css"/>
        <script type="text/javascript" src="js/jquery.ztree.core-3.5.min.js"></script>
        <script type="text/javascript">
            var setting = {
                async: {
                    enable: true,
                    url: "member.php?act=get_node",
                    autoParam: ["id"]
                }
            };

            $(function(){
                var t = $("#ztree");
                t = $.fn.zTree.init(t, setting);
            });
        </script>
        <?php }?>

        <!-- 会员充值 -->
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='recharge'){?>
        <form method="post">
            <fieldset>
                <p>
                    <label class="l-title">会员账号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['account'];?>

                </p>
                <p>
                    <label class="l-title">真实姓名：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['name'];?>

                </p>
                <p>
                    <label class="l-title">账户余额：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['emoney'];?>

                </p>
                <p>
                    <label class="l-title">充值金额：</label>
                    <input class="text-input w300" type="text" id="emoney" name="emoney" placeholder="请输入充值金额" value="">
                </p>
                <p>
                    <label class="l-title">备注：</label>
                    <input class="text-input w300" type="text" id="remark" name="remark" placeholder="请输入备注" value="">
                </p>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="recharge"/>
                    <input type="hidden" name="eaccount" value="<?php echo $_smarty_tpl->tpl_vars['member_info']->value['account'];?>
">
                    <button class="btn btn-primary" type="submit">提交</button> &nbsp;<a href="member.php" class="btn btn-primary">返回</a>
                </p>
            </fieldset>
        </form>
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
        <form method="post">
            <fieldset>
                <p>
                    <label class="l-title">会员账号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['account'];?>

                </p>
                <p>
                    <label class="l-title">真实姓名：</label>
                    <input type="text" name="name" value="<?php echo $_smarty_tpl->tpl_vars['member_info']->value['name'];?>
"/>
                </p>
                <p>
                    <label class="l-title">会员等级：</label>
                    <select name="level_id">
                        <?php  $_smarty_tpl->tpl_vars['level'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['level']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['member_level']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['level']->key => $_smarty_tpl->tpl_vars['level']->value){
$_smarty_tpl->tpl_vars['level']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['level']->key;
?>
                        <?php if ($_smarty_tpl->tpl_vars['id']->value>=$_smarty_tpl->tpl_vars['member_info']->value['level_id']){?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['id']->value==$_smarty_tpl->tpl_vars['member_info']->value['level_id']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['level']->value;?>
</option>
                        <?php }?>
                        <?php } ?>
                    </select>
                </p>
                <p>
                    <label class="l-title">手机号码：</label>
                    <input class="text-input w300" type="text" id="mobile" name="mobile" placeholder="" value="<?php echo $_smarty_tpl->tpl_vars['member_info']->value['mobile'];?>
">
                </p>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="edit"/>
                    <input type="hidden" name="eaccount" value="<?php echo $_smarty_tpl->tpl_vars['member_info']->value['account'];?>
">
                    <button class="btn btn-primary" type="submit">提交</button> &nbsp;<a href="member.php" class="btn btn-primary">返回</a>
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