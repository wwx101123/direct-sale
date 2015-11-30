<?php /* Smarty version Smarty-3.1.13, created on 2015-10-15 10:24:54
         compiled from "/Library/WebServer/Documents/facm/control/themes/bank/bank.phtml" */ ?>
<?php /*%%SmartyHeaderCode:411933687561f0e76adee57-36843403%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97f9df0020757d6799ba94fc843a43cc0fc85a57' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/bank/bank.phtml',
      1 => 1444875325,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '411933687561f0e76adee57-36843403',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'act' => 0,
    'subTitle' => 0,
    'bank_list' => 0,
    'bank' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_561f0e76bba193_75958643',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561f0e76bba193_75958643')) {function content_561f0e76bba193_75958643($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<?php if ($_smarty_tpl->tpl_vars['act']->value=='view'){?>
<div id="content">
    <div class="content-title">
        <h5 class="pull-left"><?php echo $_smarty_tpl->tpl_vars['subTitle']->value;?>
</h5>
        <div class="pull-right"><a class="btn btn-primary" href="?act=add">添加银行卡</a></div>
        <div class="clear"></div>
    </div>
    <div class="nav-main">
        <div class="tab_container">
            <div id="tab1" class="tab_content" style="display: block; ">
                <table class="table">
                    <thead>
                    <tr>
                        <th>开户银行</th>
                        <th>开户人</th>
                        <th>银行卡号</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['bank'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bank']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['bank_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bank']->key => $_smarty_tpl->tpl_vars['bank']->value){
$_smarty_tpl->tpl_vars['bank']->_loop = true;
?>
                    <tr>
                        <td><?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_name'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_account'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_card'];?>
</td>
                        <td>
                            <a href="bank.php?act=edit&id=<?php echo $_smarty_tpl->tpl_vars['bank']->value['id'];?>
">编辑</a> |
                            <a href="bank.php?act=delete&id=<?php echo $_smarty_tpl->tpl_vars['bank']->value['id'];?>
" onclick="return confirm_delete();">删除</a>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function confirm_delete() {
        if(confirm("您确定要删除该银行卡？")) {
            return true;
        } else {
            return false;
        }
    }
</script>
<!-- END content -->
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['act']->value=='add'||$_smarty_tpl->tpl_vars['act']->value=='edit'){?>
<div id="content">
    <div class="content-title">
        <h5 class="pull-left"><?php if ($_smarty_tpl->tpl_vars['act']->value=='add'){?>添加<?php }else{ ?>修改<?php }?>银行卡</h5>
        <div class="clear"></div>
    </div>
    <div class="nav-main">
        <form id="navForm" name="navAddForm" method="post">
            <fieldset>
                <p>
                    <label class="l-title">开户银行：</label>
                    <input class="text-input w300" type="text" id="bank_name" name="bank_name" placeholder="请输入开户银行" <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>value="<?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_name'];?>
"<?php }?>>
                </p>
                <p>
                    <label class="l-title">开户人：</label>
                    <input class="text-input w300" type="text" id="bank_account" name="bank_account" placeholder="请输入开户人" <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>value="<?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_account'];?>
"<?php }?>>
                </p>
                <p>
                    <label class="l-title">银行卡号：</label>
                    <input class="text-input w300" type="text" id="bank_card" name="bank_card" placeholder="请输入银行卡号" <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>value="<?php echo $_smarty_tpl->tpl_vars['bank']->value['bank_card'];?>
"<?php }?>>
                </p>
                <p>
                    <label class="l-title"></label>
                    <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
                    <input type="hidden" name="eid" value="<?php echo $_smarty_tpl->tpl_vars['bank']->value['id'];?>
"/>
                    <?php }?>
                    <input type="hidden" name="opera" value="<?php echo $_smarty_tpl->tpl_vars['act']->value;?>
"/>
                    <button class="btn btn-primary" type="submit"><?php if ($_smarty_tpl->tpl_vars['act']->value=='add'){?>添加<?php }else{ ?>修改<?php }?></button>
                    &nbsp;<a href="bank.php" class="btn btn-primary">返回</a>
                </p>
            </fieldset>
        </form>
    </div>
</div>
<?php }?>
<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html>
<?php }} ?>