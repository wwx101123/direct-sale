<?php /* Smarty version Smarty-3.1.13, created on 2015-11-24 16:20:51
         compiled from "/Library/WebServer/Documents/facm/control/themes/index/login.phtml" */ ?>
<?php /*%%SmartyHeaderCode:126964621856541de3b03ec1-10857028%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b651ef87a45c29677969088557276eb3ae2258e' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/index/login.phtml',
      1 => 1441079268,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '126964621856541de3b03ec1-10857028',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pageTitle' => 0,
    'error' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56541de3b59ad7_02662360',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56541de3b59ad7_02662360')) {function content_56541de3b59ad7_02662360($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <!--[if IE 6]><script src="js/DDbelatedPNG.js"></script><script>DD_belatedPNG.fix('.png');</script><![endif]-->
    <script type="text/javascript" src="js/jquery.js"></script>
    <style>
        html { background: #f8f8f8; }
        .login-form { background:url("images/logo.jpg") no-repeat #ededed;display:block; width: 600px; height: 280px;margin: 120px auto 0; }
        .admin-login-form { margin-left: 285px;padding: 10px;font-size: 14px;height:260px;border-left: 1px solid #fff; }
        h1 {  font-size: 22px;  text-align: center;  line-height: 50px;  }
        .input-wrapper { clear:both; overflow:hidden; margin-bottom: 10px; }
        .input-wrapper input { line-height: 20px; font-size: 14px; padding: 3px 5px; width: 220px; border:1px solid #cecece;}
        .input-wrapper span { color: #ff0000; line-height: 24px; display: block; }
        .login-btn { background: #004A9F; border: 1px solid #004A9F; color: #fff; padding: 8px 20px;cursor:pointer; }
    </style>
</head>
<body style="background: #f8f8f8;">
<div class="login-form">
    <form class="admin-login-form" method="post" >
        <h1>管理后台</h1>
        <p class="input-wrapper">
            帐号：<input type="text" name="account" />
            <span>&nbsp;<?php if (isset($_smarty_tpl->tpl_vars['error']->value['account'])){?><?php echo $_smarty_tpl->tpl_vars['error']->value['account'];?>
<?php }?></span>
        </p>
        <p class="input-wrapper">
            密码：<input type="password" name="password" />
            <span>&nbsp;<?php if (isset($_smarty_tpl->tpl_vars['error']->value['password'])){?><?php echo $_smarty_tpl->tpl_vars['error']->value['password'];?>
<?php }?></span>
        </p>
        <input type="submit" name="login" value="登&nbsp;&nbsp;&nbsp;&nbsp;陆" class="login-btn"/>
        <input type="hidden" name="opera" value="login" />
    </form>
</div>
</body>
</html><?php }} ?>