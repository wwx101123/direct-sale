<?php /* Smarty version Smarty-3.1.13, created on 2015-11-17 11:28:21
         compiled from "/Library/WebServer/Documents/facm/control/themes/product/product.phtml" */ ?>
<?php /*%%SmartyHeaderCode:1493391047564a9ed5597f10-28439760%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40fd0ad8d2f531f6b710bd6641e9357ead68c550' => 
    array (
      0 => '/Library/WebServer/Documents/facm/control/themes/product/product.phtml',
      1 => 1443378977,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1493391047564a9ed5597f10-28439760',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'subTitle' => 0,
    'act' => 0,
    'count' => 0,
    'product_sn' => 0,
    'status' => 0,
    'product_list' => 0,
    'product' => 0,
    'cat_json' => 0,
    'LANG' => 0,
    'total' => 0,
    'totalPage' => 0,
    'go_first' => 0,
    'keyword' => 0,
    'has_prev' => 0,
    'page' => 0,
    'has_many_prev' => 0,
    'show_page' => 0,
    'pageNum' => 0,
    'has_many_next' => 0,
    'go_last' => 0,
    'has_next' => 0,
    'category_list' => 0,
    'cat' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_564a9ed57f59d5_96339441',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564a9ed57f59d5_96339441')) {function content_564a9ed57f59d5_96339441($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/header.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body xmlns="http://www.w3.org/1999/html">
<?php echo $_smarty_tpl->getSubTemplate ("library/navbar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("library/sidebar.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<!-- content -->
<div id="content">
    <div class="content-title">
        <h5 class="pull-left"><?php echo $_smarty_tpl->tpl_vars['subTitle']->value;?>
</h5>
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='view'){?>
        <div class="pull-right"><a class="btn btn-primary" href="?act=add">新增产品</a></div>
        <?php }?>
        <div class="clear"></div>
    </div>
    <div class="adminGroup-main">
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='view'){?>
        <div class="article-main-header">
            <form action="" method="get" />
            <fieldset>
                <p>
                <div class="pull-left">
                    显示
                    <select name="count" class="w75">
                        <option value="10" <?php if ($_smarty_tpl->tpl_vars['count']->value==10){?>selected="selected"<?php }?>>10</option>
                        <option value="25" <?php if ($_smarty_tpl->tpl_vars['count']->value==25){?>selected="selected"<?php }?>>25</option>
                        <option value="50" <?php if ($_smarty_tpl->tpl_vars['count']->value==50){?>selected="selected"<?php }?>>50</option>
                        <option value="100" <?php if ($_smarty_tpl->tpl_vars['count']->value==100){?>selected="selected"<?php }?>>100</option>
                    </select>
                    项结果
                </div>
                <div class="pull-right">
                    <label>产品编号：</label>
                    <input class="text-input w150" type="text" id="small-input" name="product_sn" value="<?php echo $_smarty_tpl->tpl_vars['product_sn']->value;?>
">&nbsp;
                    <label>状态：</label>
                    <select name="status">
                        <option value="-1">全部</option>
                        <option value="0" <?php if ($_smarty_tpl->tpl_vars['status']->value==0){?>selected="selected"<?php }?>>下架</option>
                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected="selected"<?php }?>>上架</option>
                    </select>&nbsp;
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
                </p>
            </fieldset>
            </form>
            <div class="clear"></div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>产品编号</th>
                <th>产品名称</th>
                <th>价格</th>
                <!--
                <th>购买积分</th>
                <th>赠送积分</th>
                -->
                <th>产品分类</th>
                <th>库存</th>
                <th>状态</th>
                <th class="text-right">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['product_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['product']->value['product_sn'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['product']->value['price'];?>
</td>
                <!--
                <td><?php echo $_smarty_tpl->tpl_vars['product']->value['integral'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['product']->value['integral_given'];?>
</td>
                -->
                <td><?php echo $_smarty_tpl->tpl_vars['cat_json']->value[$_smarty_tpl->tpl_vars['product']->value['category_id']];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['product']->value['inventory'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['LANG']->value['product']["status_".((string)$_smarty_tpl->tpl_vars['product']->value['status'])];?>
</td>
                <td>
                    <?php echo $_smarty_tpl->tpl_vars['product']->value['operation'];?>

                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="9">
                    <div class="pull-right">
                        <div class="pages">
                            <span>共有<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
条，<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
页，每页显示：<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
条</span>
                            <?php if ($_smarty_tpl->tpl_vars['go_first']->value){?>
                            <a href="?page=1&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">首页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_prev']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value-1;?>
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">上一页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_many_prev']->value){?>
                            ...
                            <?php }?>
                            <?php  $_smarty_tpl->tpl_vars['pageNum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pageNum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['show_page']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pageNum']->key => $_smarty_tpl->tpl_vars['pageNum']->value){
$_smarty_tpl->tpl_vars['pageNum']->_loop = true;
?>
                            <?php if ($_smarty_tpl->tpl_vars['pageNum']->value==$_smarty_tpl->tpl_vars['page']->value){?>
                            <b><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</b>
                            <?php }else{ ?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['pageNum']->value;?>
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['pageNum']->value;?>
</a>
                            <?php }?>
                            <?php } ?>
                            <?php if ($_smarty_tpl->tpl_vars['has_many_next']->value){?>
                            ...
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['go_last']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">末页</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['has_next']->value){?>
                            <a href="?page=<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
&keyword=<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
&count=<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
">下一页</a>
                            <?php }?>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?php }?>

        <!-- 产品编辑 -->
        <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'||$_smarty_tpl->tpl_vars['act']->value=='add'){?>
        <script type="text/javascript" src="../plugins/kindeditor/kindeditor-all-min.js"></script>
        <form method="post" name="product">
            <fieldset>
                <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
                <p>
                    <label class="l-title">产品编号：</label>
                    <?php echo $_smarty_tpl->tpl_vars['product']->value['product_sn'];?>

                </p>
                <?php }?>
                <p class="clear">
                    <label class="l-title">产品分类：</label>
                    <select name="category_id" id="category_id" class="w150">
                        <?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['category_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value){
$_smarty_tpl->tpl_vars['cat']->_loop = true;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['cat']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['cat']->value['name'];?>
</option>
                        <?php } ?>
                    </select>
                </p>
                <p>
                    <label class="l-title">产品名称：</label>
                    <input class="text-input w300" type="text" id="name" name="name" placeholder="请输入产品名称" value="<?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
<?php }?>">
                </p>

                <p>
                    <label class="l-title">产品价格：</label>
                    <input class="text-input w300" type="text" id="price" name="price" placeholder="请输入产品价格" value="<?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['price'];?>
<?php }?>">
                </p>

                <p style="display: none;">
                    <label class="l-title">产品积分：</label>
                    <input class="text-input w300" type="text" id="integral" name="integral" placeholder="请输入购买积分" value="<?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['integral'];?>
<?php }?>0">
                </p>

                <p style="display: none;">
                    <label class="l-title">赠送积分：</label>
                    <input class="text-input w300" type="text" id="price_integral" name="integral_given" placeholder="请输入赠送积分" value="<?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['integral_given'];?>
<?php }?>0">
                </p>

                <p>
                    <label class="l-title">产品库存：</label>
                    <input class="text-input w300" type="text" id="inventory" name="inventory" placeholder="请输入库存" value="<?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['inventory'];?>
<?php }?>">
                </p>
                <p class="clear">
                    <label class="l-title">产品状态：</label>
                    <select name="status" id="status" class="w150">
                        <option value="1">上架</option>
                        <option value="0">下架</option>
                    </select>
                </p>
                <p class="clear" id="img-input">
                    <label class="l-title">产品图片:</label>
                    <input type="button" class="text-input" id="select-image" value="请选择产品图片" style="cursor:pointer">
                    <input type="hidden" name="img" value="" id="img"/>
                </p>
                <p>
                    <label class="l-title"></label>
                    <img id="show-image" alt="" src="<?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['img'];?>
<?php }else{ ?>about:blank<?php }?>">
                </p>
                <h5>产品详情：</h5>
                <p>
                    <textarea name="desc" id="desc" class="rn w-90" rows="20" placeholder="这里是编辑器"><?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?><?php echo $_smarty_tpl->tpl_vars['product']->value['desc'];?>
<?php }?></textarea>
                </p>
                <p>
                    <label class="l-title"></label>
                    <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
                    <input type="hidden" name="opera" value="edit"/>
                    <input type="hidden" name="eproduct_sn" value="<?php echo $_smarty_tpl->tpl_vars['product']->value['product_sn'];?>
">
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['act']->value=='add'){?>
                    <input type="hidden" name="opera" value="add"/>
                    <?php }?>

                    <button class="btn btn-primary" type="submit"><?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>修改<?php }else{ ?>新增<?php }?></button> &nbsp;<a href="product.php" class="btn btn-primary">返回</a>
                </p>
            </fieldset>
        </form>
        <script type="text/javascript">
            $(function() {
                <?php if ($_smarty_tpl->tpl_vars['act']->value=='edit'){?>
                $("#category_id").val(<?php echo $_smarty_tpl->tpl_vars['product']->value['category_id'];?>
);
                $("#status").val(<?php echo $_smarty_tpl->tpl_vars['product']->value['status'];?>
);
                <?php }?>
            });

            KindEditor.ready(function(K) {
                var editor1 = K.create("textarea[name='desc']", {
                    height: '400px',
                    width: '93%',
                    cssPath: '../plugins/kindeditor/plugins/code/prettify.css',
                    uploadJson: '../plugins/kindeditor/upload_json.php',
                    fileManagerJson: '../plugins/kindeditor/file_manager_json.php',
                    allowFileManager: true,
                    afterCreate: function () {
                        var self = this;
                        K.ctrl(document, 13, function () {
                            self.sync();
                            K('form[name=product]')[0].submit();
                        });
                        K.ctrl(self.edit.doc, 13, function () {
                            self.sync();
                            K('form[name=product]')[0].submit();
                        });
                    }
                });

                var editor = K.editor({
                    allowFileManager : true,
                    uploadJson : '../plugins/kindeditor/section_upload_json.php',
                    fileManagerJson : '../plugins/kindeditor/file_manager_json.php'
                });

                K('#select-image').click(function() {
                    editor.loadPlugin('image', function() {
                        editor.plugin.imageDialog({
                            imageUrl : '',
                            clickFn : function(url, title, width, height, border, align) {
                                if( !width ) {
                                    width = '300';
                                    width = (width == '') ? '75px' : width + 'px'
                                }
                                if( !height ) {
                                    height = '300';
                                    height = (height == '') ? '75px' : height + 'px'
                                }

                                K('#img').val(url);
//                        K('#select-image').val('');
                                K('#show-image').attr('src', url);
                                K('#show-image').css('width', width);
                                K('#show-image').css('height', height);
                                K('#show-image').css('display', 'block');
//                        K('#show-image').css('display', 'block');
                                editor.hideDialog();
                            }
                        });
                    });
                });
            });
        </script>
        <?php }?>
    </div>
</div>
<!-- END content -->

<?php echo $_smarty_tpl->getSubTemplate ("library/footer.lbi", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>