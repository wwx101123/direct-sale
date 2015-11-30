<?php /* Smarty version Smarty-3.1.13, created on 2015-11-24 16:09:34
         compiled from "/Library/WebServer/Documents/facm/control/themes/public/message.phtml" */ ?>
<?php /*%%SmartyHeaderCode:60837789756541b3edc5a36-68007137%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9450ccbd0a9b82f769f2948b184ff05dc380c1c8' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/public/message.phtml',
      1 => 1440394499,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '60837789756541b3edc5a36-68007137',
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
  'unifunc' => 'content_56541b3ee26e36_52920236',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56541b3ee26e36_52920236')) {function content_56541b3ee26e36_52920236($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml-transitional.dtd"><html><head>    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	<meta http-equiv="Refresh" content="99999<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
; url=<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
"/>	<title><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</title>    <link rel="stylesheet" type="text/css" href="css/style.css"/></head><body><div class="message">    <?php echo $_smarty_tpl->tpl_vars['message']->value;?>
    <div class="message-links">    <?php if ($_smarty_tpl->tpl_vars['links']->value){?>    <?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['links']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>        <a href="<?php echo $_smarty_tpl->tpl_vars['a']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['a']->value['alt'];?>
"><?php echo $_smarty_tpl->tpl_vars['a']->value['alt'];?>
</a>    <?php } ?>    <?php }?>    </div></div><div class="mask" style="z-index: 1;display: block;"></div></body></html><?php }} ?>