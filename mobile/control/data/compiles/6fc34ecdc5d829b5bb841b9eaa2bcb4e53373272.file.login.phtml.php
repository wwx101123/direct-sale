<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:04
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/index/login.phtml" */ ?>
<?php /*%%SmartyHeaderCode:4962383355671a140676052-85494659%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6fc34ecdc5d829b5bb841b9eaa2bcb4e53373272' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/index/login.phtml',
      1 => 1449995832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4962383355671a140676052-85494659',
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
  'unifunc' => 'content_5671a1406fadb3_74793168',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a1406fadb3_74793168')) {function content_5671a1406fadb3_74793168($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
        body{ background: #fff;font-size:14px;font-family: "Helvetica Neue", "Luxi Sans", "DejaVu Sans", Tahoma, "Hiragino Sans GB", STHeiti, "Microsoft YaHei"; color:#333;line-height:22px; margin: 0; padding: 0;}
        .clear{
            clear:both;
        }
        .clearfix {
            clear:both;
            overflow: hidden;
        }
        body,html {
            height: 100%; width: 100%;
            background: #ededed;
        }
        a{
            color:#06C;cursor:pointer;text-decoration: none;
        }

        input,select,textarea{ outline:none}
        .fl { float: left;}
        .fr { float: right;}
        .block { width: 1000px; margin: 0 auto;}
        .input-wrapper { clear:both; overflow:hidden; margin-bottom: 10px; margin-top: 0;}
        .input-label
        {
            width: 100px;
            text-align: right;
        }

        .input-text, .input-code, .input-date
        {
            line-height: 20px; font-size: 14px; padding: 3px 5px; width: 220px; border:1px solid #cecece;
        }
        .input-search
        {
            width: 110px;
        }
        .input-code
        {
            width: 110px;
        }
        .code {
            height: 27px;
            line-height: 28px;
            margin-left: 10px;
            width: 100px;
        }
        .input-error
        {
            color: #ff0000;
        }
        /** 登录页面 **/
        .login-form { background:url(images/logo_bg.png) no-repeat #ffffff;display:block; width: 700px; height: 280px;margin: 120px auto 0; }
        .admin-login-form { margin-left: 285px;padding: 10px;font-size: 14px;height:260px;border-left: 1px solid #ededed; }
        h1 {  font-size: 22px;  text-align: center;  line-height: 50px;  }
        .input-wrapper span { color: #ff0000; line-height: 24px; display: inline-block; margin-left: 5px; }
        .btn { margin-left: 10px; background: #06C; border: 1px solid #06C; color: #fff; padding: 6px 20px;cursor:pointer; border-radius: 5px; display: inline-block;}
        .btn-disabled { background: #8C8C8C; color: #fff; border-color: #8C8C8C;}
        .btn-loading-img {
            /* position: absolute; */
            vertical-align: middle;
            line-height: 16px;
            height: 16px;
            margin-left: 3px;
            margin-top: 0px;
            display: none;
        }
    </style>
</head>
<body>
<div class="login-form">
    <form class="admin-login-form" method="post" >
        <h1>管理后台</h1>
        <p class="input-wrapper">
            <label class="input-label fl" for="account">帐号：</label>
            <input type="text" name="account" id="account" class="input-text fl"/>
            <span>&nbsp;<?php if (isset($_smarty_tpl->tpl_vars['error']->value['account'])){?><?php echo $_smarty_tpl->tpl_vars['error']->value['account'];?>
<?php }?></span>
        </p>
        <p class="input-wrapper">
            <label class="input-label fl" for="password">密码：</label>
            <input type="password" name="password" id="password" class="input-text fl"/>
            <span>&nbsp;<?php if (isset($_smarty_tpl->tpl_vars['error']->value['password'])){?><?php echo $_smarty_tpl->tpl_vars['error']->value['password'];?>
<?php }?></span>
        </p>
        <p class="input-wrapper">
            <label class="input-label fl" for="code">验证码：</label>
            <input type="text" name="code" class="input-code fl" id="code"/>
            <img src="code.php" onclick="refresh_code();" id="verify_code" class="code fl"/>
            <span>&nbsp;<?php if (isset($_smarty_tpl->tpl_vars['error']->value['code'])){?><?php echo $_smarty_tpl->tpl_vars['error']->value['code'];?>
<?php }?></span>
        </p>
        <p class="input-wrapper" style="text-align: center">
            <input type="submit" name="login" value="登&nbsp;&nbsp;&nbsp;&nbsp;陆" class="login-btn btn"/>
            <input type="hidden" name="opera" value="login" />
        </p>
    </form>
</div>
<script type="text/javascript">
    function refresh_code() {
        $("#verify_code").attr("src", "code.php");
    }
</script>
</body>
</html><?php }} ?>