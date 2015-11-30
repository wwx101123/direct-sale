<?php /* Smarty version Smarty-3.1.13, created on 2015-11-30 14:18:31
         compiled from "themes/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:15892059035656ce97002fc7-18309712%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a73117c7bc791a162d0449c049d0c7927d14b94d' => 
    array (
      0 => 'themes/index.phtml',
      1 => 1448857061,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15892059035656ce97002fc7-18309712',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5656ce9706c355_65233174',
  'variables' => 
  array (
    'config' => 0,
    'template_dir' => 0,
    'cycle_ad' => 0,
    'ad' => 0,
    'notice' => 0,
    'n' => 0,
    'perform_ad' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5656ce9706c355_65233174')) {function content_5656ce9706c355_65233174($_smarty_tpl) {?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="application-name" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="www.kwanson.com">
    <meta name="version" content="v.1.0.0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="format-detection" content="telephone=no, address=no">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
css/common.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
css/app.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
css/fonts.css">
</head>
<body id="index">
<!-- 首页头部 -->
<header class="header">
    <div class="logo">
        <a href="#">嘟喷哒</a>
    </div>
    <div class="header_service">
        <a href="#"><span class="icon">&#xe600;</span><em>客服</em></a>
    </div>
</header>
<!-- 广告轮拨区 -->
<section id="focus" class="focus">
    <div class="hd">
        <ul></ul>
    </div>
    <div class="bd">
        <ul>
            <?php  $_smarty_tpl->tpl_vars['ad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ad']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cycle_ad']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ad']->key => $_smarty_tpl->tpl_vars['ad']->value){
$_smarty_tpl->tpl_vars['ad']->_loop = true;
?>
            <li><a href="<?php echo $_smarty_tpl->tpl_vars['ad']->value['url'];?>
"><img _src="<?php echo $_smarty_tpl->tpl_vars['ad']->value['img'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/blank.png" /></a></li>
            <?php } ?>
        </ul>
    </div>
</section>
<!-- 首页公告区 -->
<section class="index-news clearfix">
    <div class="index-news-l fl"></div>
    <div id="myscroll" class="index-news-r fr">
        <ul id="notice" class="news-title">
            <?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['notice']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?>
            <li><a href="article_list.php?id=1"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a></li>
            <?php } ?>
        </ul>
    </div>
</section>

<!-- 专区 -->
<?php  $_smarty_tpl->tpl_vars['ad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ad']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['perform_ad']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ad']->key => $_smarty_tpl->tpl_vars['ad']->value){
$_smarty_tpl->tpl_vars['ad']->_loop = true;
?>
<section class="index-floor">
    <div class="floor-con">
        <a href="<?php echo $_smarty_tpl->tpl_vars['ad']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['ad']->value['img'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['ad']->value['alt'];?>
"></a>
    </div>
</section>
<?php } ?>

<div style="height:1rem; "></div>
<!-- 底部菜单 -->
<section class="menu">
    <ul>
        <li><a class="active" href="index.php"><em class="icon">&#xe601;</em><span>首页</span></a></li>
        <li><a href="article_list.php"><em class="icon">&#xe603;</em><span>资讯列表</span></a></li>
        <li><a href="cart.php"><em class="icon">&#xe602;</em><span>购物车</span></a></li>
        <li><a href="user.php"><em class="icon">&#xe604;</em><span>我</span></a></li>
    </ul>
</section>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container" id="message_dialog" style="display: none;">
        <p id="dialog-message"></p>
        <ul class="cd-buttons">
            <li class="cd-signle-button"><a href="javascript:close_message_dialog();" id="dialog_close_btn">确认</a></li>
        </ul>
        <a href="javascript:close_message_dialog_cancel();" class="cd-popup-close img-replace" id="dialog_close">X</a>
    </div>

    <div class="progressbar">
        <img src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/loading.gif"/>
    </div>
</div>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/jquery.lazyload.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("img.lazy").lazyload({
            effect : "fadeIn"
        });
    });
</script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/TouchSlide.1.1.js"></script>
<script type="text/javascript">
    TouchSlide({
        slideCell:"#focus",
        titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
        mainCell:".bd ul",
        effect:"left",
        autoPlay:true,//自动拨放
        autoPage:true, //自动分页
        switchLoad:"_src" //切换加载，真实图片路径为"_src"
    });
</script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/scrolltext.js"></script>
<script type="text/javascript">
    var newsHeight = $(".index-news").height();
    if(document.getElementById("notice")){
        var scrollup = new ScrollText("notice");
        scrollup.LineHeight =  (newsHeight ==120)?40:20;//单排文字滚动的高度
        scrollup.Amount = 1;            //注意:子模块(LineHeight)一定要能整除Amount.
        scrollup.Delay = 35;           //延时
        scrollup.Start();             //文字自动滚动
        scrollup.Direction = "down"; //文字向下滚动
    }
</script>
<script type="text/javascript">
    function change_fav() {
        var url = "data_center.php";
        var data = { "opera":"get_fav" };
        $.post(url, data, change_fav_handler, "json");
    }

    function change_fav_handler(response) {
        if(response.error == 0) {
            $("#fav_list").html(response.content);
        }
    }
    function show_message_dialog(message) {
        $(".cd-popup").addClass("is-visible");
        $(".progressbar").hide();
        $("#dialog-message").empty();
        $("#dialog-message").append(message);
        $("#message_dialog").show();
    }

    function close_message_dialog() {
        $(".cd-popup").removeClass("is-visible");
        $("#message_dialog").hide();
        var phone = $('#message_dialog').find('a:eq(0)').text();
        window.location.href="tel://"+phone;
    }

    function close_message_dialog_cancel() {
        $(".cd-popup").removeClass("is-visible");
        $("#message_dialog").hide();
    }
</script>
</body>
</html>
<?php }} ?>