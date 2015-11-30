<?php /* Smarty version Smarty-3.1.13, created on 2015-11-24 16:20:49
         compiled from "/Library/WebServer/Documents/facm/control/themes/library/navbar.lbi" */ ?>
<?php /*%%SmartyHeaderCode:98916321956541de19c39d3-97730524%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61e2b3b2646d968b38b41c41f98d5198976d90b9' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/library/navbar.lbi',
      1 => 1439373452,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98916321956541de19c39d3-97730524',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'currentAdmin' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56541de19cd807_42027250',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56541de19cd807_42027250')) {function content_56541de19cd807_42027250($_smarty_tpl) {?><!-- navbar -->
<div class="navbar">
    <div class="navbar-inner">
        <div class="navbar-left"><a href="#"><img class="png" src="images/logo.png" alt="logo"/></a></div>
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