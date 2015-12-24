<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:48
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/library/navbar.lbi" */ ?>
<?php /*%%SmartyHeaderCode:6743493815671a16cc13fe3-10907920%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64400bdd76436f404a9f2bbeda5cb7ad7a358ecb' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/library/navbar.lbi',
      1 => 1449995887,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6743493815671a16cc13fe3-10907920',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'currentAdmin' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a16cc1dd72_52520338',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a16cc1dd72_52520338')) {function content_5671a16cc1dd72_52520338($_smarty_tpl) {?><!-- navbar -->
<div class="navbar">
    <div class="navbar-inner">
        <div class="navbar-left"><a href="#"><img class="png" src="images/top_logo.png" alt="logo"/></a></div>
        <div class="navbar-right">
            <a href="#" class="navbar-member"><em><?php echo $_smarty_tpl->tpl_vars['currentAdmin']->value;?>
</em><span class="icon">&#xe609;</span></a>
            <ul id="dropdown-menu" style="display:none;">
                <li class="topbar-info-btn"><a href="index.php?act=logout">注销</a></li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</div>
<!-- END navbar --><?php }} ?>