<?php /* Smarty version Smarty-3.1.13, created on 2015-11-24 16:10:39
         compiled from "/Library/WebServer/Documents/facm/control/themes/member/member.phtml" */ ?>
<?php /*%%SmartyHeaderCode:142579263456541b7f2e58a8-43794654%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '95369e5968b9d3292170bdd9b0651b67f2c1b4be' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/member/member.phtml',
      1 => 1448351282,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '142579263456541b7f2e58a8-43794654',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'act' => 0,
    'count' => 0,
    'nickname' => 0,
    'account' => 0,
    'member_list' => 0,
    'member' => 0,
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
    'member_info' => 0,
    'path' => 0,
    'node' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56541b7f50e887_48742886',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56541b7f50e887_48742886')) {function content_56541b7f50e887_48742886($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

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
                    <label>微信昵称：</label>
                    <input class="text-input w150" type="text" id="small-input" name="nickname" value="<?php echo $_smarty_tpl->tpl_vars['nickname']->value;?>
">&nbsp;
                    <label>会员编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="account" value="<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
">&nbsp;
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
                </p>
            </fieldset>
            </form>
            <div class="clear"></div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>会员账号</th>
                <th>微信昵称</th>
                <th>绑定账号</th>
                <th>余额</th>
                <th>推广奖励</th>
                <th>待发奖励</th>
<!--                <th>积分</th>-->
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
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['nickname'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['bind_account'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['emoney'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['reward'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['reward_await'];?>
</td>
<!--                <td><?php echo $_smarty_tpl->tpl_vars['member']->value['integral'];?>
</td>-->
                <td>
                    <?php echo $_smarty_tpl->tpl_vars['member']->value['operation'];?>

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
                            <a href="?page=1&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">首页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_prev']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value-1;?>
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
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">末页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_next']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
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
                    <label class="l-title">微信昵称：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['nickname'];?>

                </p>
                <p>
                    <label class="l-title">账户余额：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['emoney'];?>

                </p>
                <p>
                    <label class="l-title">账户积分：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['integral'];?>

                </p>
                <p>
                    <label class="l-title">充值金额：</label>
                    <input class="text-input w300" type="text" id="emoney" name="emoney" placeholder="请输入充值金额" value="">
                </p>
                <p>
                    <label class="l-title">充值积分：</label>
                    <input class="text-input w300" type="text" id="integral" name="integral" placeholder="请输入充值积分" value="">
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

        <!-- 会员移网 -->
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='move'){?>
        <form method="post" onsubmit="return flag;">
            <fieldset>
                <p>
                    <label class="l-title">会员账号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['account'];?>

                </p>
                <p>
                    <label class="l-title">微信昵称：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['nickname'];?>

                </p>
                <p>
                    <label class="l-title">账户余额：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['emoney'];?>

                </p>
                <p>
                    <label class="l-title">账户积分：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['integral'];?>

                </p>
                <p>
                    <label class="l-title">当前推荐人：</label>
                    <?php echo $_smarty_tpl->tpl_vars['member_info']->value['recommend']['account'];?>

                </p>
                <p>
                    <label class="l-title">目标推荐人：</label>
                    <input class="text-input w300" type="text" id="recommend" name="recommend" placeholder="目标推荐人" value="<?php echo $_smarty_tpl->tpl_vars['member_info']->value['recommend']['account'];?>
"/>
                    <span class="red" id="recommend-error"></span>
                    <span id="recommend-info"></span>
                </p>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="move"/>
                    <input type="hidden" name="eaccount" value="<?php echo $_smarty_tpl->tpl_vars['member_info']->value['account'];?>
">
                    <button class="btn btn-primary" type="submit">移网</button> &nbsp;<a href="member.php" class="btn btn-primary">返回</a>
                </p>
                <p>
                    当前推荐网络:
                    <?php  $_smarty_tpl->tpl_vars['node'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['node']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['path']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['node']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['node']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['node']->key => $_smarty_tpl->tpl_vars['node']->value){
$_smarty_tpl->tpl_vars['node']->_loop = true;
 $_smarty_tpl->tpl_vars['node']->iteration++;
 $_smarty_tpl->tpl_vars['node']->last = $_smarty_tpl->tpl_vars['node']->iteration === $_smarty_tpl->tpl_vars['node']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['path']['last'] = $_smarty_tpl->tpl_vars['node']->last;
?>
                    <?php echo $_smarty_tpl->tpl_vars['node']->value['nickname'];?>
[<?php echo $_smarty_tpl->tpl_vars['node']->value['account'];?>
]
                    <?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['path']['last']){?>
                     -
                    <?php }?>
                    <?php } ?>
                </p>
            </fieldset>
        </form>
        <script type="text/javascript">
            var flag = true;
            $(function(){
                $("#recommend").blur(function() {
                    check_account();
                });
            });
            function check_account() {
                var recommend = $("#recommend").val();

                if(recommend == "") {
                    $("#recommend-error").text("请填写目标推荐人编号");
                    flag = false;
                    return false;
                }

                flag = false;
                $.post("member.php", { "opera":"check_node", "account": recommend }, check_account_handler, "json");
            }

            function check_account_handler(response) {
                if(response.error == 0) {
                    $("#recommend-error").text("");
                    $("#recommend-info").text(response.msg.nickname+"["+response.msg.account+"]");
                    flag = true;
                } else {
                    alert(response.msg);
                    flag = false;
                }
            }
        </script>
        <?php }?>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>