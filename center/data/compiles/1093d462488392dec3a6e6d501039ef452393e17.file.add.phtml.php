<?php /* Smarty version Smarty-3.1.13, created on 2015-10-15 09:42:33
         compiled from "/Library/WebServer/Documents/facm/control/themes/role/add.phtml" */ ?>
<?php /*%%SmartyHeaderCode:313327269561f04897f72f3-65020118%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1093d462488392dec3a6e6d501039ef452393e17' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/role/add.phtml',
      1 => 1439373452,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '313327269561f04897f72f3-65020118',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'purviews' => 0,
    'key' => 0,
    'purviewValue' => 0,
    'purview' => 0,
    'subPurview' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_561f04899e5658_74103112',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561f04899e5658_74103112')) {function content_561f04899e5658_74103112($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
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
                    <input class="text-input w300" type="text" id="small-input" name="name" placeholder="请输入用户组名称" maxlength="32">
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
">
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
"><em><?php echo $_smarty_tpl->tpl_vars['purviewValue']->value[$_smarty_tpl->tpl_vars['subPurview']->value];?>
</em>&nbsp;&nbsp;
                    <?php } ?>
                </p>
            </fieldset>
            <?php } ?>
            <fieldset>
                <p>
                    <label class="l-title"></label>
                    <input type="hidden" name="opera" value="add" />
                    <button class="btn btn-primary" type="submit">添加</button> &nbsp;<a href="role.php" class="btn btn-primary">返回</a>
                </p>
            </fieldset>
        </form>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>