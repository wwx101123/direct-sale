<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:00
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/role/view.phtml" */ ?>
<?php /*%%SmartyHeaderCode:15388618685671a13c7d6311-59443021%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '190d5b30ccf0023fa0c3e41e66b221c9d1fb6932' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/role/view.phtml',
      1 => 1449994621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15388618685671a13c7d6311-59443021',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'roleList' => 0,
    'role' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a13c845c42_00724620',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a13c845c42_00724620')) {function content_5671a13c845c42_00724620($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<!-- content -->
<div id="content">
    <div class="content-title">
        <h5 class="pull-left"><?php echo $_smarty_tpl->tpl_vars['subTitle']->value;?>
</h5>
        <div class="pull-right"><a class="btn btn-primary" href="?act=add">添加管理员角色</a></div>
        <div class="clear"></div>
    </div>
    <div class="adminGroup-main">
        <table class="table">
            <thead>
            <tr>
                <th>角色名称</th>
                <th class="text-right">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['role'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['role']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['roleList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['role']->key => $_smarty_tpl->tpl_vars['role']->value){
$_smarty_tpl->tpl_vars['role']->_loop = true;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['role']->value['name'];?>
</td>
                <?php if ($_smarty_tpl->tpl_vars['role']->value['id']==1){?>
                <td class="text-right"></td>
                <?php }else{ ?>
                <td class="text-right"><a href="?act=edit&id=<?php echo $_smarty_tpl->tpl_vars['role']->value['id'];?>
">编辑</a> | <a onclick="javascript:if(confirm('确认要删除？')) return true; else return false;" href="?act=delete&id=<?php echo $_smarty_tpl->tpl_vars['role']->value['id'];?>
">删除</a></td>
                <?php }?>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>