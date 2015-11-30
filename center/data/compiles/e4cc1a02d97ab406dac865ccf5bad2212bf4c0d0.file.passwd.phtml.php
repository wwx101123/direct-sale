<?php /* Smarty version Smarty-3.1.13, created on 2015-10-18 11:28:13
         compiled from "/Library/WebServer/Documents/facm/control/themes/profile/passwd.phtml" */ ?>
<?php /*%%SmartyHeaderCode:1985968080562311cdae4102-63758795%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e4cc1a02d97ab406dac865ccf5bad2212bf4c0d0' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/profile/passwd.phtml',
      1 => 1445138706,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1985968080562311cdae4102-63758795',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_562311cdbcc1c8_57270182',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_562311cdbcc1c8_57270182')) {function content_562311cdbcc1c8_57270182($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<div id="content">
    <div class="content-title">
        <h5 class="pull-left">修改密码</h5>
        <div class="pull-right"><a class="btn btn-primary" href="?act=info">返回</a></div>
        <div class="clear"></div>
    </div>
    <div class="basicInfo-main">
        <form id="userSettingForm" method="post" action="">
            <fieldset>
                <p>
                    <label class="l-title">原密码：</label>
                    <input class="text-input w300" type="password" maxlength="64" id="old-password" name="old-password" value="" placeholder="">
                </p>
                <p>
                    <label class="l-title">新密码：</label>
                    <input class="text-input w300" type="password" id="new-password" name="new-password" value="">
                </p>
                <p class="clear">
                    <label class="l-title">再输入一次新密码：</label>
                    <input class="text-input w300" type="password" id="confirm-password" name="confirm-password" value="">
                </p>
                <p>
                    <label class="l-title"></label>
                    <button class="btn btn-primary" type="submit">保存</button>
                    <input type="hidden" name="opera" value="passwd" />
                </p>
            </fieldset>
        </form>
    </div>
</div>

<!-- END content -->
<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>