<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:14
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/main/view.phtml" */ ?>
<?php /*%%SmartyHeaderCode:8965967735671a14a83de55-53822641%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '937324dd1ed25a37c1b09b87593172abe2bd9966' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/main/view.phtml',
      1 => 1449994621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8965967735671a14a83de55-53822641',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'member_amount' => 0,
    'order_amount' => 0,
    'message_count' => 0,
    'achievement_amount' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a14a893c32_32506642',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a14a893c32_32506642')) {function content_5671a14a893c32_32506642($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<div id="content">
    <div class="content-title">
        <h5 class="pull-left">首页</h5>
        <div class="clear"></div>
    </div>
    <div class="start-main">
        <h3>网站概要</h3>
        <p>当日新增<i><?php echo $_smarty_tpl->tpl_vars['member_amount']->value;?>
</i> 个用户，新增<i><?php echo $_smarty_tpl->tpl_vars['order_amount']->value;?>
</i> 张订单</p>
        <p>当日新增留言<i><?php echo $_smarty_tpl->tpl_vars['message_count']->value;?>
</i> 条，新增业绩<i><?php echo $_smarty_tpl->tpl_vars['achievement_amount']->value;?>
</i></p>
        <p>点击下面的链接快速开始:</p>
        <ul class="start-link">
            <li><a href="product.php?act=add" target="_blank">添加产品</a></li>
            <li><a href="member.php" target="_blank">用户管理</a></li>
            <li><a href="sysconf.php" target="_blank">系统设置</a></li>
        </ul>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html>
<?php }} ?>