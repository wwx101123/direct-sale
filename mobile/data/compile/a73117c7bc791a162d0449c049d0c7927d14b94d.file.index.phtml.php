<?php /* Smarty version Smarty-3.1.13, created on 2015-12-23 13:22:04
         compiled from "themes/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:15892059035656ce97002fc7-18309712%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a73117c7bc791a162d0449c049d0c7927d14b94d' => 
    array (
      0 => 'themes/index.phtml',
      1 => 1450842785,
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
    'content' => 0,
    'perform_ad_4' => 0,
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
    <link rel="apple-touch-icon-precomposed" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/touch/touch-icon-iphone.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/touch/touch-icon-ipad.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/touch/touch-icon-iphone4.png">
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
css/index.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
css/fonts.css">
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/jquery-1.11.0.min.js"></script>
    <?php if (is_weixin()){?>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <?php }?>
</head>
<body>

    <!-- 首页头部 -->
    <header class="header">
        <div class="logo">
            <a href="#"><?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
</a>
        </div>
        <div class="search_box" style="text-align: center">
            <?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>

            <!--
            <a href="#" >
                <div class="search">
                    <form method="get" action="">
                        <div class="text_box">
                            <input id="keyword" name="keyword" type="text" placeholder="搜索商品/店铺" class="keyword text" onkeydown="this.style.color='#404040'" autocomplete="off">
                        </div>
                        <input type="submit" value="" class="submit" dd_name="搜索">
                    </form>
                </div>
            </a>
            -->
        </div>
    </header>


    <!-- 广告轮播区 -->
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
                <li><a href="#"><img _src="<?php echo $_smarty_tpl->tpl_vars['ad']->value['img'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/blank.png" /></a></li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
js/TouchSlide.1.1.js"></script>
    <script type="text/javascript">
    TouchSlide({
        slideCell:"#focus",
        titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
        mainCell:".bd ul",
        effect:"left",
        autoPlay:true,//自动播放
        autoPage:true, //自动分页
        switchLoad:"_src" //切换加载，真实图片路径为"_src"
    });
    </script>
    <!-- 广告轮播区 end -->

    <!-- 首页公告区 -->
    <section class="index-news clearfix">
        <div class="index-news-l fl">
            <span class="icon">&#xe613;</span>
        </div>
        <div id="myscroll" class="index-news-r">
            <ul id="notice" class="news-title">
                <?php  $_smarty_tpl->tpl_vars['content'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['content']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['notice']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['content']->key => $_smarty_tpl->tpl_vars['content']->value){
$_smarty_tpl->tpl_vars['content']->_loop = true;
?>
                <li><a href="article.php?id=<?php echo $_smarty_tpl->tpl_vars['content']->value['id'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['content']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a></li>
                <?php } ?>
            </ul>
        </div>
    </section>
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

    <!-- 首页功能区 -->
    <ul class="index-nav">
        <li><a href="reward.php" title="我的奖金"><img class="" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/jifen.png"><span>我的奖金</span></a></li>
        <li><a href="withdraw.php" title="账户提现"><img class="" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/tixian.png"><span>账户提现</span></a></li>
        <li><a href="order.php" title="我的订单"><img class="" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/dingdan.png"><span>我的订单</span></a></li>
        <li><a href="address.php" title="收货地址"><img class="" src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/dizhi.png"><span>收货地址</span></a></li>
    </ul>

    <!-- 商家推荐区 -->
    <section class="floor">
    <h2>
        <a class="title"><i class="icon">&#xe610;</i>商家推荐</a>
    </h2>
    </section>
    <?php  $_smarty_tpl->tpl_vars['ad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ad']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['perform_ad_4']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ad']->key => $_smarty_tpl->tpl_vars['ad']->value){
$_smarty_tpl->tpl_vars['ad']->_loop = true;
?>
    <section class="index-floor">
        <div class="floor-con">
            <a href="<?php echo $_smarty_tpl->tpl_vars['ad']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['ad']->value['img'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['ad']->value['alt'];?>
" width="100%"></a>
        </div>
    </section>
    <?php } ?>

    <div style="height:2.2rem; "></div>


<section class="menu">
    <ul>
        <li><a class="active" href="index.php"><em class="icon">&#xe601;</em><span>首页</span></a></li>
        <li><a href="article_list.php"><em class="icon">&#xe603;</em><span>资讯</span></a></li>
        <li><a href="cart.php"><em class="icon">&#xe602;</em><span>购物车</span></a></li>
        <li><a href="user.php"><em class="icon">&#xe600;</em><span>我</span></a></li>
    </ul>
</section>


<div class="cd-popup" role="alert">
    <div class="cd-popup-container" id="confirm_dialog" style="display: none;">
        <p id="confirm-message"></p>
        <ul class="cd-buttons">
            <li><a id="dialog_confirm_btn" href="javascript:void(0);"></a></li>
            <li><a id="dialog_confirm_cancel" href="javascript:close_operation_dialog('confirm_dialog');"></a></li>
        </ul>
        <a href="javascript:close_operation_dialog('confirm_dialog');" class="cd-popup-close img-replace" id="dialog_confirm_close">X</a>
    </div>

    <div class="cd-popup-container" id="message_dialog" style="display: none;">
        <p id="dialog-message"></p>
        <ul class="cd-buttons">
            <li class="cd-signle-button"><a href="javascript:close_message_dialog();" id="dialog_close_btn">确认</a></li>
        </ul>
        <a href="javascript:close_message_dialog();" class="cd-popup-close img-replace" id="dialog_close">X</a>
    </div>

    <div class="progressbar">
        <img src="<?php echo $_smarty_tpl->tpl_vars['template_dir']->value;?>
images/loading.gif"/>
    </div>
</div>
<script type="text/javascript">
    function show_message_dialog(message) {
        $(".cd-popup").addClass("is-visible");
        $(".progressbar").hide();
        $("#dialog-message").html(message);
        $("#message_dialog").show();
    }

    function close_message_dialog() {
        $(".cd-popup").removeClass("is-visible");
        $("#message_dialog").hide();
    }

    function show_mask() {
        $(".cd-popup").addClass("is-visible");
        $(".progressbar").show();
    }

    function hide_mask() {
        $(".cd-popup").removeClass("is-visible");
        $(".progressbar").hide();
    }

    function show_operation_dialog() {
        $(".cd-popup").addClass("is-visible");
        $(".progressbar").hide();
        $("#confirm_dialog").show();
    }

    function close_operation_dialog() {
        $(".cd-popup").removeClass("is-visible");
        $("#confirm_dialog").hide();
    }
</script>

</body>
</html>
<?php }} ?>