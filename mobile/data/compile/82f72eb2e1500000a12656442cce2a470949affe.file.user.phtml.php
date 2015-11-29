<?php /* Smarty version Smarty-3.1.13, created on 2015-11-26 18:20:37
         compiled from "themes/user.phtml" */ ?>
<?php /*%%SmartyHeaderCode:20326115825656cf0aac98c6-43767514%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82f72eb2e1500000a12656442cce2a470949affe' => 
    array (
      0 => 'themes/user.phtml',
      1 => 1448532953,
      2 => 'file',
    ),
    'a73117c7bc791a162d0449c049d0c7927d14b94d' => 
    array (
      0 => 'themes/index.phtml',
      1 => 1448532984,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20326115825656cf0aac98c6-43767514',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5656cf0ab2fae2_38651874',
  'variables' => 
  array (
    'config' => 0,
    'template_dir' => 0,
    'active_script' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5656cf0ab2fae2_38651874')) {function content_5656cf0ab2fae2_38651874($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
</title>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/jquery.min.js"></script>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="application-name" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/touch/touch-icon-iphone.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/touch/touch-icon-ipad.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/touch/touch-icon-iphone4.png">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="www.kwanson.com">
    <meta name="version" content="v.1.0.0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="format-detection" content="telephone=no, address=no">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
css/app.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
css/fonts.css">
</head>
<body>

<div class="mask" id="mask">
    <div class="progress" id="progress">
        <img src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/loading.gif"/>
    </div>
</div>


This is index.phtml header

<section class="container">

This is index.phtml content

</section>

<div style="height:1rem; "></div>
<!-- 底部菜单 -->
<section class="menu">
    <ul>
        <li>
            <a <?php if ($_smarty_tpl->tpl_vars['active_script']->value=='index.php'){?>class="active"<?php }?> href="index.php">
                <em class="icon">&#xe601;</em><span>首页</span>
            </a>
        </li>
        <li>
            <a <?php if ($_smarty_tpl->tpl_vars['active_script']->value=='product_list.php'){?>class="active"<?php }?> href="product_list.php">
                <em class="icon">&#xe603;</em><span>产品列表</span>
            </a>
        </li>
        <li>
            <a <?php if ($_smarty_tpl->tpl_vars['active_script']->value=='cart.php'){?>class="active"<?php }?> href="cart.php">
                <em class="icon">&#xe602;</em><span>购物车</span>
            </a>
        </li>
        <li>
            <a <?php if ($_smarty_tpl->tpl_vars['active_script']->value=='user.php'){?>class="active"<?php }?>  href="user.php">
                <em class="icon">&#xe604;</em><span>会员中心</span>
            </a>
        </li>
    </ul>
</section>

<script type="text/javascript">
window.onload = function() {
    bootstrap();
}

function hide_mask() {
    $("#mask").hide();
    $("#progress").hide();
}

function show_mask() {
    $("#mask").show();
    $("#progress").show();
}
</script>
</body>
</html>
<?php }} ?>