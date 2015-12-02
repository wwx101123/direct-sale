DROP TABLE IF EXISTS `sh_account`;
CREATE TABLE `sh_account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `emoney` decimal(18,2) NOT NULL DEFAULT '0.00',
  `reward` decimal(18,2) NOT NULL DEFAULT '0.00',
  `reward_await` decimal(18,2) NOT NULL DEFAULT '0.00',
  `operator` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `shop_icon` decimal(18,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_ad`;
CREATE TABLE `sh_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  `begin_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `forever` tinyint(1) NOT NULL DEFAULT '0',
  `click_time` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `order_view` int(11) NOT NULL DEFAULT '50',
  `ad_pos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `sh_ad` VALUES ('1','/shanghai/upload/image/20151025/20151025184749_58140.jpg','轮播广告一','1445770084','1445770084','-1','1','0','轮播广告','50','1'),
 ('2','/shanghai/upload/image/20151025/20151025184749_58140.jpg','2','1445770095','1445770095','-1','1','0','2','50','1'),
 ('3','/shanghai/upload/image/20151025/20151025184749_58140.jpg','3','1445770104','1445770104','-1','1','0','3','50','1');



DROP TABLE IF EXISTS `sh_ad_position`;
CREATE TABLE `sh_ad_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(255) NOT NULL,
  `width` decimal(18,2) NOT NULL,
  `height` decimal(18,2) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '1',
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `sh_ad_position` VALUES ('1','轮播广告','1000.00','300.00','5','');



DROP TABLE IF EXISTS `sh_admin`;
CREATE TABLE `sh_admin` (
  `account` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sex` char(2) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sh_admin` VALUES ('admin','78a5c4b49d8f1eff66b2bf2e6b6af1ac','support@shanghai.com','财富系统','M','1');



DROP TABLE IF EXISTS `sh_bank_info`;
CREATE TABLE `sh_bank_info` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `bank_card` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `sh_bank_info` VALUES ('1','招商银行','莫言','6225998209856748');



DROP TABLE IF EXISTS `sh_card_pool`;
CREATE TABLE `sh_card_pool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `sh_card_pool` VALUES ('1','SH000001','1');



DROP TABLE IF EXISTS `sh_cart`;
CREATE TABLE `sh_cart` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_sn` varchar(255) NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `pv` decimal(18,2) NOT NULL,
  `number` int(11) NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  `account` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_category`;
CREATE TABLE `sh_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `sh_category` VALUES ('1','报单产品'),
 ('2','升级/重消产品');



DROP TABLE IF EXISTS `sh_content`;
CREATE TABLE `sh_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `content` text,
  `wap_content` text,
  `last_modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `original` varchar(255) DEFAULT NULL,
  `order_view` int(11) NOT NULL DEFAULT '50',
  `original_url` varchar(255) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `sh_content` VALUES ('1','这是一篇系统公告','财富系统','1445770175','这是一篇系统公告','这是一篇系统公告','2015-10-25 18:49:35','这是一篇系统公告','这是一篇系统公告','','','50','','1','1'),
 ('2','这是一篇系统公告1','财富系统','1445770188','这是一篇系统公告1','这是一篇系统公告1','2015-10-25 18:49:48','这是一篇系统公告','这是一篇系统公告','','','50','','1','1'),
 ('3','资料下载','财富系统','1445770260','资料下载<a class=\"ke-insertfile\" href=\"/shanghai/upload/file/20151025/20151025115033_26065.txt\" target=\"_blank\">下载测试</a>','资料下载<a class=\"ke-insertfile\" href=\"/shanghai/upload/file/20151025/20151025115033_26065.txt\" target=\"_blank\">下载测试</a>','2015-10-25 18:51:00','资料下载','资料下载','','','50','','2','1');



DROP TABLE IF EXISTS `sh_gallery`;
CREATE TABLE `sh_gallery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `original` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `product_sn` varchar(255) NOT NULL,
  `order_view` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_inventory_detail`;
CREATE TABLE `sh_inventory_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_sn` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `inventory_sn` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_inventory_order`;
CREATE TABLE `sh_inventory_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `inventory_sn` varchar(255) NOT NULL COMMENT '库存单',
  `add_time` int(11) NOT NULL COMMENT '下单时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `solve_time` int(11) NOT NULL,
  `order_sn` varchar(255) DEFAULT NULL COMMENT '关联订单',
  `operator` varchar(255) NOT NULL,
  PRIMARY KEY (`inventory_sn`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_member`;
CREATE TABLE `sh_member` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL COMMENT '直销系统账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `super_password` varchar(255) NOT NULL COMMENT '二级密码',
  `name` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(255) NOT NULL COMMENT '联系电话',
  `level_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `email` varchar(255) DEFAULT NULL COMMENT '电子邮箱',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '会员状态',
  `view_network` tinyint(1) NOT NULL DEFAULT '1' COMMENT '允许查看网络图',
  `bank` varchar(255) DEFAULT NULL COMMENT '开户银行',
  `bank_branch` varchar(255) DEFAULT NULL COMMENT '开户行支行',
  `bank_account` varchar(255) DEFAULT NULL COMMENT '开户姓名',
  `bank_card` varchar(255) DEFAULT NULL COMMENT '银行卡号',
  `parent_id` varchar(255) NOT NULL DEFAULT '1' COMMENT '推荐人ID',
  `path` text COMMENT '推荐关系',
  `emoney` decimal(18,3) NOT NULL DEFAULT '0.000' COMMENT '报单币',
  `reward` decimal(18,3) NOT NULL DEFAULT '0.000' COMMENT '奖金',
  `reward_await` decimal(18,3) NOT NULL DEFAULT '0.000' COMMENT '待发奖金',
  `achievement` decimal(18,3) NOT NULL DEFAULT '0.000' COMMENT '累计业绩',
  `shop_icon` decimal(18,3) NOT NULL DEFAULT '0.000',
  `add_time` int(11) NOT NULL,
  `is_frozen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `sh_member` VALUES ('1','SH000000','c2e5c1ee6f96683137edd08f10fa7b19','3981875d87cbd0986cf5b97b0aa106d3','财富系统1','13900000000','3','support@sh.com','1','1','中国人民银行','','李想','5655555555555','0','1,','0.000','0.000','0.000','0.000','0.000','0','0');



DROP TABLE IF EXISTS `sh_message`;
CREATE TABLE `sh_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL COMMENT '发信人',
  `to` varchar(255) NOT NULL COMMENT '收信人',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '留言回复',
  `path` text COMMENT '留言路径',
  `content` text NOT NULL COMMENT '留言内容',
  `add_time` int(11) NOT NULL COMMENT '留言时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '留言状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_order`;
CREATE TABLE `sh_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `pv` decimal(18,2) NOT NULL,
  `consignee` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `delivery_time` int(11) DEFAULT NULL,
  `delivery_company` varchar(255) DEFAULT NULL,
  `delivery_sn` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `recommend_account` varchar(255) DEFAULT NULL COMMENT '报单人账号',
  `account` varchar(255) NOT NULL COMMENT '会员账号',
  `type` int(11) NOT NULL COMMENT '订单类型',
  PRIMARY KEY (`order_sn`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_order_detail`;
CREATE TABLE `sh_order_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(255) NOT NULL,
  `product_sn` varchar(255) NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `pv` decimal(18,2) NOT NULL,
  `number` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_product`;
CREATE TABLE `sh_product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_sn` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `pv` decimal(18,2) NOT NULL,
  `detail` text,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL,
  `inventory` int(11) NOT NULL DEFAULT '0',
  `desc` text,
  PRIMARY KEY (`product_sn`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `sh_product` VALUES ('6','FC12693','重消产品二','1000.00','900.00','','2','1','/shanghai/upload/image/20151027/20151027214220_47071.jpg','10',''),
 ('5','FC51126','重消产品','1000.00','100.00','','2','1','/shanghai/upload/image/20151027/20151027214220_47071.jpg','2',''),
 ('4','FC62989','新的报单产品','100.00','100.00','','1','1','/shanghai/upload/image/20151027/20151027214220_47071.jpg','100',''),
 ('1','PD000000','合伙人礼包','20000.00','20000.00','','1','1','images/product.jpg','1000',''),
 ('2','PD000001','高级合伙人礼包','100000.00','60000.00','','1','1','images/product.jpg','10',''),
 ('3','PD000002','依文时尚项目礼包','198000.00','120000.00','','1','1','images/product.jpg','2','');



DROP TABLE IF EXISTS `sh_recharge`;
CREATE TABLE `sh_recharge` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `recharge_sn` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `real_amount` decimal(18,2) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `bank_name` varchar(255) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `bank_card` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `solve_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`recharge_sn`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_recharge_log`;
CREATE TABLE `sh_recharge_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `recharge_sn` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `operator` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_reward`;
CREATE TABLE `sh_reward` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL,
  `reward` decimal(18,2) DEFAULT '0.00',
  `add_time` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `send_time` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_role`;
CREATE TABLE `sh_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purview` text NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `sh_role` VALUES ('1','{\"pur_sysconf\":[\"pur_sysconf_add\",\"pur_sysconf_view\",\"pur_sysconf_edit\",\"pur_sysconf_del\"],\"pur_member\":[\"pur_member_view\",\"pur_member_edit\",\"pur_member_del\"],\"pur_recharge\":[\"pur_recharge_view\",\"pur_recharge_edit\",\"pur_recharge_del\"],\"pur_withdraw\":[\"pur_withdraw_view\",\"pur_withdraw_edit\",\"pur_withdraw_del\"],\"pur_account\":[\"pur_account_view\"],\"pur_admin\":[\"pur_admin_add\",\"pur_admin_view\",\"pur_admin_edit\",\"pur_admin_del\"],\"pur_role\":[\"pur_role_add\",\"pur_role_view\",\"pur_role_edit\",\"pur_role_del\"],\"pur_self\":[\"pur_self_edit\"],\"pur_order\":[\"pur_order_view\",\"pur_order_del\",\"pur_order_edit\"],\"pur_product\":[\"pur_product_view\",\"pur_product_add\",\"pur_product_edit\",\"pur_product_del\"],\"pur_bank\":[\"pur_bank_view\"],\"pur_reward\":[\"pur_reward_view\"],\"pur_section\":[\"pur_section_add\",\"pur_section_view\",\"pur_section_edit\",\"pur_section_del\"],\"pur_content\":[\"pur_content_add\",\"pur_content_view\",\"pur_content_edit\",\"pur_content_del\"],\"pur_adpos\":[\"pur_adpos_view\",\"pur_adpos_add\",\"pur_adpos_edit\",\"pur_adpos_del\"],\"pur_ad\":[\"pur_ad_view\",\"pur_ad_add\",\"pur_ad_edit\",\"pur_ad_del\"],\"pur_message\":[\"pur_message_view\"],\"pur_backup\":[\"pur_backup_view\"]}','超级管理员');



DROP TABLE IF EXISTS `sh_section`;
CREATE TABLE `sh_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_view` int(11) NOT NULL DEFAULT '50',
  `thumb` varchar(255) DEFAULT NULL,
  `original` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `sh_section` VALUES ('1','系统公告','0','1,','系统公告','系统公告','50','',''),
 ('2','资料下载','0','2,','资料下载','资料下载','50','','');



DROP TABLE IF EXISTS `sh_sysconf`;
CREATE TABLE `sh_sysconf` (
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(255) NOT NULL,
  `options` text,
  `group` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sh_sysconf` VALUES ('access_token','access_token','','text','','wechat',''),
 ('appid','appid','','text','','wechat',''),
 ('appsecret','appsecret','','text','','wechat',''),
 ('EncodingAESKey','EncodingAESKey','','text','','wechat',''),
 ('expired','expired','0','text','','wechat',''),
 ('fee_rate','提现手续费','0.1','text','','config',''),
 ('level_1_1','合伙人管理奖一代','0','text','','config',''),
 ('level_1_2','合伙人管理奖二代','0','text','','config',''),
 ('level_1_3','合伙人管理奖三代','0','text','','config',''),
 ('level_2_1','高级合伙人管理奖一代','0.10','text','','config',''),
 ('level_2_2','高级合伙人管理奖二代','0.05','text','','config',''),
 ('level_2_3','高级合伙人管理奖三代','0.05','text','','config',''),
 ('level_3_1','时尚项目包管理奖一代','0.10','text','','config',''),
 ('level_3_2','时尚项目包管理奖二代','0.05','text','','config',''),
 ('level_3_3','时尚项目包管理奖三代','0.05','text','','config',''),
 ('public_account','公众号原始ID','','text','','wechat',''),
 ('recommend_1','合伙人直推奖','0.20','text','','config',''),
 ('recommend_2','高级合伙人直推奖','0.35','text','','config',''),
 ('recommend_3','时尚项目包直推奖','0.35','text','','config',''),
 ('recommend_integral','推荐送积分','0','text','','themes',''),
 ('recommend_rate','报单服务费','0.09','text','','themes',''),
 ('site_name','站点名称','上海财富系统','text','','config',''),
 ('themes','模板','shanghai','text','','themes',''),
 ('token','公众号接入口令','','text','','wechat',''),
 ('wechat_account','微信号','','text','','wechat','');



DROP TABLE IF EXISTS `sh_user`;
CREATE TABLE `sh_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL COMMENT '直销系统账号',
  `unionid` varchar(255) DEFAULT NULL,
  `subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `expired` int(11) NOT NULL DEFAULT '0',
  `scene_id` int(11) NOT NULL DEFAULT '0',
  `img` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `path` text,
  PRIMARY KEY (`openid`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_withdraw`;
CREATE TABLE `sh_withdraw` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `apply_sn` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `real_amount` decimal(18,2) NOT NULL,
  `fee` decimal(18,2) NOT NULL,
  `status` int(11) NOT NULL,
  `apply_time` int(11) NOT NULL,
  `solve_time` int(11) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `bank` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `bank_card` varchar(255) NOT NULL,
  PRIMARY KEY (`apply_sn`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sh_withdraw_log`;
CREATE TABLE `sh_withdraw_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `apply_sn` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `operator` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




