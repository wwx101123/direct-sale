<?php /* Smarty version Smarty-3.1.13, created on 2015-12-17 01:37:48
         compiled from "/Library/WebServer/Documents/youdaoli/control/themes/backup/view.phtml" */ ?>
<?php /*%%SmartyHeaderCode:15416606935671a16cba6c79-58875937%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '94b2312d9dd0d66281673adfbdce5b8039612f0f' => 
    array (
      0 => '/Library/WebServer/Documents/youdaoli/control/themes/backup/view.phtml',
      1 => 1449994621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15416606935671a16cba6c79-58875937',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'files' => 0,
    'file' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5671a16cc02ad8_66171652',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5671a16cc02ad8_66171652')) {function content_5671a16cc02ad8_66171652($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<!-- content -->
<div id="content">
    <div class="content-title">
        <h5 class="pull-left"><?php echo $_smarty_tpl->tpl_vars['subTitle']->value;?>
</h5>
        <div class="clear"></div>
    </div>
    <div class="article-main">
        <div class="article-main-header">
            <form action="" method="post" />
            <fieldset>
                <p>
                <div class="pull-left">
                    <input type="hidden" name="opera" value="backup"/>
                    <button type="submit" class="btn btn-default">备份</button>
                </div>
                </p>
            </fieldset>
            </form>
            <div class="clear"></div>
        </div>
    </div>
    <div class="adminUser-main">
        <table class="table">
            <thead>
            <tr>
                <th>备份日期</th>
                <th>下载链接</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value){
$_smarty_tpl->tpl_vars['file']->_loop = true;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['file']->value['date'];?>
</td>
                <td><a href="<?php echo $_smarty_tpl->tpl_vars['file']->value['url'];?>
">下载</a></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- END content -->
<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>