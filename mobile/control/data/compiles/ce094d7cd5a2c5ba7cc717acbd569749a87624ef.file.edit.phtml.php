<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:36:54
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/role/edit.phtml" */ ?>
<?php /*%%SmartyHeaderCode:3315675245671a136aceae0-57736972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce094d7cd5a2c5ba7cc717acbd569749a87624ef' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/role/edit.phtml',
      1 => 1449994621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3315675245671a136aceae0-57736972',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'role' => 0,
    'purviews' => 0,
    'key' => 0,
    'purviewValue' => 0,
    'purviewC' => 0,
    'purview' => 0,
    'subPurview' => 0,
    'sub_purviewC' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a136b9c924_69527125',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a136b9c924_69527125')) {function content_5671a136b9c924_69527125($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div id="content">
    <div class="content-title">
        <h5 class="pull-left">添加用户组</h5>
        <div class="clear"></div>
    </div>
    <div class="adminGroup-main">
        <form id="roleForm" name="roleForm" method="post">
            <fieldset>
                <p>
                    <label class="l-title">用户组名称：</label>
                    <input class="text-input w300" type="text" id="small-input" name="name" placeholder="请输入用户组名称" maxlength="32" value="<?php echo $_smarty_tpl->tpl_vars['role']->value['name'];?>
" />
                </p>
            </fieldset>
            <fieldset>
                <p>
                    <label class="l-title">权限管理：</label>
                    <input type="checkbox" id="all"><em>全选</em>
                </p>
                <div class="clear"></div>
            </fieldset>
            <?php  $_smarty_tpl->tpl_vars['purview'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['purview']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['purviews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['purview']->key => $_smarty_tpl->tpl_vars['purview']->value){
$_smarty_tpl->tpl_vars['purview']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['purview']->key;
?>
            <fieldset>
                <p class="clear">
                    <label class="l-title"><?php echo $_smarty_tpl->tpl_vars['purviewValue']->value[$_smarty_tpl->tpl_vars['key']->value];?>
：</label>
                    <input type="checkbox" name="purviews[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="purview" id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['purviewC']->value[$_smarty_tpl->tpl_vars['key']->value]==true){?>checked="checked"<?php }?>/>
                </p>
                <p class="clear">
                    <label class="l-title"></label>
                    <?php  $_smarty_tpl->tpl_vars['subPurview'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subPurview']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['purview']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subPurview']->key => $_smarty_tpl->tpl_vars['subPurview']->value){
$_smarty_tpl->tpl_vars['subPurview']->_loop = true;
?>
                    <input type="checkbox" name="purviews[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][]" value="<?php echo $_smarty_tpl->tpl_vars['subPurview']->value;?>
" class="sub-purview <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-parent="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['sub_purviewC']->value[$_smarty_tpl->tpl_vars['key']->value][$_smarty_tpl->tpl_vars['subPurview']->value]==true){?>checked="checked"<?php }?>/><em><?php echo $_smarty_tpl->tpl_vars['purviewValue']->value[$_smarty_tpl->tpl_vars['subPurview']->value];?>
</em>&nbsp;&nbsp;
                    <?php } ?>
                </p>
            </fieldset>
            <?php } ?>
            <fieldset>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="edit" />
                    <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['role']->value['id'];?>
" />
                    <button class="btn btn-primary" type="submit">提交</button> &nbsp;<a href="role.php" class="btn btn-primary">返回</a>
                </p>
            </fieldset>
        </form>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>