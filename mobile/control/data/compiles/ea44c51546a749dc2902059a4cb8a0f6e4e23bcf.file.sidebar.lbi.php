<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:48
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/library/sidebar.lbi" */ ?>
<?php /*%%SmartyHeaderCode:13135373825671a16cc21771-39995875%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ea44c51546a749dc2902059a4cb8a0f6e4e23bcf' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/library/sidebar.lbi',
      1 => 1450021349,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13135373825671a16cc21771-39995875',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'menus' => 0,
    'key' => 0,
    'menu' => 0,
    'child' => 0,
    'active_nav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a16cc90fd9_37746097',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a16cc90fd9_37746097')) {function content_5671a16cc90fd9_37746097($_smarty_tpl) {?><div id="sidebar">
    <div id="sidebar-wrapper">
        <ul id="main-nav">
            <li class="business_menu_index">
                <a href="main.php" class="nav-top-item">
                    <em class="icon">&#xe606;</em>首页
                </a>
            </li>
            <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['menu']->key;
?>
            <li class="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
                <a href="<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])){?>javascript:void(0);<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
<?php }?>" class="nav-top-item" >
                    <em class="icon"><?php echo $_smarty_tpl->tpl_vars['menu']->value['icon'];?>
</em><?php echo $_smarty_tpl->tpl_vars['menu']->value['title'];?>

                </a>
                <?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])){?>
                <ul class="submenu" id="id_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" style="display: none">
                <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['child']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
$_smarty_tpl->tpl_vars['child']->_loop = true;
?>
                    <li><a href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['title'];?>
</a></li>
                <?php } ?>
                </ul>
                <?php }?>
            </li>
            <?php } ?>
            <li class="business_menu_index">
                <a href="index.php?act=logout" class="nav-top-item">
                    <em class="icon"></em>退出登录
                </a>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript">
<?php if ($_smarty_tpl->tpl_vars['active_nav']->value=='main'){?>
    $(function () {
        menu1("", "", ".menu_index a.nav-top-item");
    });
<?php }?>
</script>

<script type="text/javascript" >
$(function(){
    $('#main-nav').children().click(function(){
        var sub_menu = $(this).children('ul');
        $('.select-hover').removeClass('select-hover');
        if( sub_menu ) {
            if( sub_menu.is(':visible') ) {
                sub_menu.slideUp();
                sub_menu.siblings('a').removeClass('select-hover');
            } else {
                sub_menu.slideDown();
                sub_menu.siblings('a').addClass('select-hover');
            }
        }
    });
});
</script><?php }} ?>