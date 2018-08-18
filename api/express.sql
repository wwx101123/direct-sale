-- MySQL dump 10.13  Distrib 5.6.21, for osx10.8 (x86_64)
--
-- Host: localhost    Database: sbx
-- ------------------------------------------------------
-- Server version	5.6.21-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ds_express`
--

DROP TABLE IF EXISTS `ds_express`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ds_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `first_letter` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ds_express`
--

LOCK TABLES `ds_express` WRITE;
/*!40000 ALTER TABLE `ds_express` DISABLE KEYS */;
INSERT INTO `ds_express` VALUES (1,'安能物流','annengwuliu','A'),(2,'安迅物流','anxl','A'),(3,'包裹/平邮/挂号信','youzhengguonei','B'),(4,'巴伦支快递','balunzhi','B'),(5,'北青小红帽','xiaohongmao','B'),(6,'百世汇通','huitongkuaidi','B'),(7,'百福东方物流','baifudongfang','B'),(8,'邦送物流','bangsongwuliu','B'),(9,'宝凯物流','lbbk','B'),(10,'百千诚物流','bqcwl','B'),(11,'博源恒通','byht','B'),(12,'百成大达物流','idada','B'),(13,'百世快运','baishiwuliu','B'),(14,'百腾物流','baitengwuliu','B'),(15,'COE（东方快递）','coe','C'),(16,'城市100','city100','C'),(17,'传喜物流','chuanxiwuliu','C'),(18,'城际速递','chengjisudi','C'),(19,'成都立即送','lijisong','C'),(20,'出口易','chukou1','C'),(21,'晟邦物流','nanjingshengbang','C'),(22,'DHL快递（中国件）','dhl','D'),(23,'DHL（国际件）','dhlen','D'),(24,'DHL（德国件）','dhlde','D'),(25,'德邦','debangwuliu','D'),(26,'大田物流','datianwuliu','D'),(27,'东方快递','coe','D'),(28,'递四方','disifang','D'),(29,'大洋物流','dayangwuliu','D'),(30,'店通快递','diantongkuaidi','D'),(31,'德创物流','dechuangwuliu','D'),(32,'东红物流','donghong','D'),(33,'D速物流','dsukuaidi','D'),(34,'东瀚物流','donghanwl','D'),(35,'达方物流','dfpost','D'),(36,'EMS快递查询','ems','E'),(37,'EMS国际快递查询','emsguoji','E'),(38,'俄顺达','eshunda','E'),(39,'FedEx快递查询','fedex','F'),(40,'FedEx国际件','fedex','F'),(41,'FedEx（美国）','fedexus','F'),(42,'凡客如风达','rufengda','F'),(43,'飞康达物流','feikangda','F'),(44,'飞豹快递','feibaokuaidi','F'),(45,'飞狐快递','feihukuaidi','F'),(46,'凡宇速递','fanyukuaidi','F'),(47,'颿达国际','fandaguoji','F'),(48,'飞远配送','feiyuanvipshop','F'),(49,'飞鹰物流','hnfy','F'),(50,'风行天下','fengxingtianxia','F'),(51,'GATI快递','gaticn','G'),(52,'国通快递','guotongkuaidi','G'),(53,'国际邮件查询','youzhengguoji','G'),(54,'港中能达物流','ganzhongnengda','G'),(55,'挂号信/国内邮件','youzhengguonei','G'),(56,'共速达','gongsuda','G'),(57,'广通速递（山东）','gtongsudi','G'),(58,'广东速腾物流','suteng','G'),(59,'港快速递','gdkd','G'),(60,'高铁速递','hre','G'),(61,'冠达快递','gda','G'),(62,'华宇物流','tiandihuayu','H'),(63,'恒路物流','hengluwuliu','H'),(64,'好来运快递','hlyex','H'),(65,'华夏龙物流','huaxialongwuliu','H'),(66,'海航天天','tiantian','H'),(67,'河北建华','hebeijianhua','H'),(68,'海盟速递','haimengsudi','H'),(69,'华企快运','huaqikuaiyun','H'),(70,'昊盛物流','haoshengwuliu','H'),(71,'户通物流','hutongwuliu','H'),(72,'华航快递','hzpl','H'),(73,'黄马甲快递','huangmajia','H'),(74,'合众速递（UCS）','ucs','H'),(75,'韩润物流','hanrun','H'),(76,'皇家物流','pfcds_express','H'),(77,'伙伴物流','huoban','H'),(78,'红马速递','nedahm','H'),(79,'汇文配送','huiwen','H'),(80,'华赫物流','nmhuahe','H'),(81,'i-parcel','iparcel','I'),(82,'佳吉物流','jiajiwuliu','J'),(83,'佳怡物流','jiayiwuliu','J'),(84,'加运美快递','jiayunmeiwuliu','J'),(85,'急先达物流','jixianda','J'),(86,'京广速递快件','jinguangsudikuaijian','J'),(87,'晋越快递','jinyuekuaidi','J'),(88,'京东快递','jd','J'),(89,'捷特快递','jietekuaidi','J'),(90,'久易快递','jiuyicn','J'),(91,'快捷快递','kuaijiesudi','K'),(92,'康力物流','kangliwuliu','K'),(93,'跨越速运','kuayue','K'),(94,'快优达速递','kuaiyouda','K'),(95,'快淘快递','kuaitao','K'),(96,'联邦快递（国内）','lianbangkuaidi','L'),(97,'联昊通物流','lianhaowuliu','L'),(98,'龙邦速递','longbanwuliu','L'),(99,'乐捷递','lejiedi','L'),(100,'立即送','lijisong','L'),(101,'蓝弧快递','lanhukuaidi','L'),(102,'乐天速递','ltexp','L'),(103,'民航快递','minghangkuaidi','M'),(104,'美国快递','meiguokuaidi','M'),(105,'门对门','menduimen','M'),(106,'明亮物流','mingliangwuliu','M'),(107,'民邦速递','minbangsudi','M'),(108,'闽盛快递','minshengkuaidi','M'),(109,'麦力快递','mailikuaidi','M'),(110,'美国韵达','yundaexus','M'),(111,'能达速递','ganzhongnengda','N'),(112,'偌亚奥国际','nuoyaao','N'),(113,'平安达腾飞','pingandatengfei','P'),(114,'陪行物流','peixingwuliu','P'),(115,'全峰快递','quanfengkuaidi','Q'),(116,'全一快递','quanyikuaidi','Q'),(117,'全日通快递','quanritongkuaidi','Q'),(118,'全晨快递','quanchenkuaidi','Q'),(119,'7天连锁物流','sevendays','Q'),(120,'秦邦快运','qbds_express','Q'),(121,'如风达快递','rufengda','R'),(122,'日昱物流','riyuwuliu','R'),(123,'瑞丰速递','rfsd','R'),(124,'申通快递','shentong','S'),(125,'顺丰速运','shunfeng','S'),(126,'速尔快递','suer','S'),(127,'山东海红','haihongwangsong','S'),(128,'盛辉物流','shenghuiwuliu','S'),(129,'世运快递','shiyunkuaidi','S'),(130,'盛丰物流','shengfengwuliu','S'),(131,'上大物流','shangda','S'),(132,'三态速递','santaisudi','S'),(133,'赛澳递','saiaodi','S'),(134,'申通E物流','shentong','S'),(135,'圣安物流','shenganwuliu','S'),(136,'山西红马甲','sxhongmajia','S'),(137,'穗佳物流','suijiawuliu','S'),(138,'沈阳佳惠尔','syjiahuier','S'),(139,'上海林道货运','shlindao','S'),(140,'十方通物流','sfift','S'),(141,'山东广通速递','gtongsudi','S'),(142,'顺捷丰达','shunjiefengda','S'),(143,'TNT快递','tnt','T'),(144,'天天快递','tiantian','T'),(145,'天地华宇','tiandihuayu','T'),(146,'通和天下','tonghetianxia','T'),(147,'天纵物流','tianzong','T'),(148,'同舟行物流','chinatzx','T'),(149,'腾达速递','nntengda','T'),(150,'UPS快递查询','ups','U'),(151,'UPS国际快递','ups','U'),(152,'UC优速快递','youshuwuliu','U'),(153,'USPS美国邮政','usps','U'),(154,'万象物流','wanxiangwuliu','W'),(155,'微特派','weitepai','W'),(156,'万家物流','wanjiawuliu','W'),(157,'万博快递','wanboex','W'),(158,'希优特快递','xiyoutekuaidi','X'),(159,'新邦物流','xinbangwuliu','X'),(160,'信丰物流','xinfengwuliu','X'),(161,'新蛋物流','neweggozzo','X'),(162,'祥龙运通物流','xianglongyuntong','X'),(163,'西安城联速递','xianchengliansudi','X'),(164,'西安喜来快递','xilaikd','X'),(165,'鑫世锐达','xsrd','X'),(166,'鑫通宝物流','xtb','X'),(167,'圆通速递','yuantong','Y'),(168,'韵达快运','yunda','Y'),(169,'运通快递','yuntongkuaidi','Y'),(170,'邮政国内','youzhengguonei','Y'),(171,'邮政国际','youzhengguoji','Y'),(172,'远成物流','yuanchengwuliu','Y'),(173,'亚风速递','yafengsudi','Y'),(174,'优速快递','youshuwuliu','Y'),(175,'亿顺航','yishunhang','Y'),(176,'越丰物流','yuefengwuliu','Y'),(177,'源安达快递','yuananda','Y'),(178,'原飞航物流','yuanfeihangwuliu','Y'),(179,'邮政EMS速递','ems','Y'),(180,'银捷速递','yinjiesudi','Y'),(181,'一统飞鸿','yitongfeihong','Y'),(182,'宇鑫物流','yuxinwuliu','Y'),(183,'易通达','yitongda','Y'),(184,'邮必佳','youbijia','Y'),(185,'一柒物流','yiqiguojiwuliu','Y'),(186,'音素快运','yinsu','Y'),(187,'亿领速运','yilingsuyun','Y'),(188,'煜嘉物流','yujiawuliu','Y'),(189,'英脉物流','gml','Y'),(190,'云豹国际货运','leopard','Y'),(191,'云南中诚','czwlyn','Y'),(192,'中通快递','zhongtong','Z'),(193,'宅急送','zhaijisong','Z'),(194,'中铁快运','zhongtiewuliu','Z'),(195,'中铁物流','ztky','Z'),(196,'中邮物流','zhongyouwuliu','Z'),(197,'中国东方(COE)','coe','Z'),(198,'芝麻开门','zhimakaimen','Z'),(199,'中国邮政快递','youzhengguonei','Z'),(200,'郑州建华','zhengzhoujianhua','Z'),(201,'中速快件','zhongsukuaidi','Z'),(202,'中天万运','zhongtianwanyun','Z'),(203,'中睿速递','zhongruisudi','Z'),(204,'中外运速递','zhongwaiyun','Z'),(205,'增益速递','zengyisudi','Z'),(206,'郑州速捷','sujievip','Z'),(207,'智通物流','ztong','Z');
/*!40000 ALTER TABLE `ds_express` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-18 22:59:53
