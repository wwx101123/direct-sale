<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:47
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/public/message.phtml" */ ?>
<?php /*%%SmartyHeaderCode:18443353275671a16b53a5e7-82109526%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cbb021424c35ac35655274257fdf6faaff694c56' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/public/message.phtml',
      1 => 1449994621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18443353275671a16b53a5e7-82109526',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'time' => 0,
    'link' => 0,
    'page_title' => 0,
    'message' => 0,
    'links' => 0,
    'a' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a16b5a1468_82862655',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a16b5a1468_82862655')) {function content_5671a16b5a1468_82862655($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml-transitional.dtd">
; url=<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
"/>
</title>

 $_from = $_smarty_tpl->tpl_vars['links']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
" title="<?php echo $_smarty_tpl->tpl_vars['a']->value['alt'];?>
"><?php echo $_smarty_tpl->tpl_vars['a']->value['alt'];?>
</a>