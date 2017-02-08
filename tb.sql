/*
SQLyog Ultimate v11.42 (64 bit)
MySQL - 5.6.17 : Database - zy_tobacco_legend
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `zy_attention` */

DROP TABLE IF EXISTS `zy_attention`;

CREATE TABLE `zy_attention` (
  `aid` int(32) DEFAULT NULL COMMENT '关注者id',
  `bid` int(32) DEFAULT NULL COMMENT '被关注者id',
  `addTime` int(11) DEFAULT NULL COMMENT '关注的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_attention` */

/*Table structure for table `zy_booth` */

DROP TABLE IF EXISTS `zy_booth`;

CREATE TABLE `zy_booth` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '摊位物品id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `type` int(4) DEFAULT NULL COMMENT '物品类型',
  `name` varchar(32) DEFAULT NULL COMMENT '物品名称',
  `price` int(10) DEFAULT NULL COMMENT '物品价格',
  `num` int(10) DEFAULT NULL COMMENT '物品数量',
  `addTime` int(11) DEFAULT NULL COMMENT '物品上架时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_booth` */

/*Table structure for table `zy_breed_record` */

DROP TABLE IF EXISTS `zy_breed_record`;

CREATE TABLE `zy_breed_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '种子培育记录id',
  `goodsId` int(32) DEFAULT '0' COMMENT '所要培育种子对应goods表的id',
  `uId` int(32) DEFAULT '0' COMMENT '玩家id',
  `startBreedTime` int(11) DEFAULT NULL COMMENT '开始培育的时间',
  `endBreedTime` int(11) DEFAULT NULL COMMENT '结束培育时间',
  `status` int(4) DEFAULT '0' COMMENT '培育状态：0未培育，1在培育，2培育完成，3已收入仓库',
  `payType` int(4) DEFAULT '0' COMMENT '支付方式：0乐豆，1金币',
  `breedMoney` int(4) DEFAULT '0' COMMENT '培育消耗的金额',
  `breedTime` int(4) DEFAULT '0' COMMENT '培育消耗的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zy_breed_record` */

insert  into `zy_breed_record`(`id`,`goodsId`,`uId`,`startBreedTime`,`endBreedTime`,`status`,`payType`,`breedMoney`,`breedTime`) values (1,3,186,1486546410,1486547483,3,0,2,72);

/*Table structure for table `zy_buy_record` */

DROP TABLE IF EXISTS `zy_buy_record`;

CREATE TABLE `zy_buy_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '购买记录id',
  `goodsId` int(32) DEFAULT NULL COMMENT '购买物品对应goods表的id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `singlePrice` int(10) DEFAULT NULL COMMENT '物品单价',
  `totalNum` int(4) DEFAULT NULL COMMENT '购买物品的总数',
  `totalPrice` int(10) DEFAULT NULL COMMENT '所有物品总价',
  `addTime` int(11) DEFAULT NULL COMMENT '购买时间',
  `source` int(4) DEFAULT '0' COMMENT '购买的东西的来源(0:商城,1其他)',
  `payType` int(4) DEFAULT '1' COMMENT '支付方式（0:乐豆支付,1:金币支付）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `zy_buy_record` */

insert  into `zy_buy_record`(`id`,`goodsId`,`uId`,`singlePrice`,`totalNum`,`totalPrice`,`addTime`,`source`,`payType`) values (17,3,186,30,NULL,270,1484625260,0,NULL),(16,2,186,20,NULL,180,1484625256,0,NULL),(15,1,186,10,NULL,90,1484625252,0,NULL),(13,4,186,50,6,300,1484620940,0,NULL),(18,4,186,50,3,150,1485051879,0,NULL),(20,1,186,10,100,1000,1485052225,0,NULL),(21,1,186,10,100,1000,1485053622,0,NULL),(22,3,186,30,100,3000,1486459785,0,0),(23,3,186,60,100,6000,1486459910,0,1),(24,8,186,30,1,30,1486460150,0,1),(25,4,186,100,5,500,1486460607,0,1);

/*Table structure for table `zy_game_log` */

DROP TABLE IF EXISTS `zy_game_log`;

CREATE TABLE `zy_game_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Openid` varchar(50) DEFAULT NULL COMMENT 'openid',
  `ChannelID` int(11) unsigned DEFAULT NULL,
  `ActiveID` int(11) unsigned DEFAULT NULL,
  `RoomID` int(11) unsigned DEFAULT NULL,
  `GameID` int(11) unsigned DEFAULT NULL,
  `AddTime` varchar(15) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3471 DEFAULT CHARSET=utf8 COMMENT='游戏访问记录表';

/*Data for the table `zy_game_log` */

insert  into `zy_game_log`(`id`,`Openid`,`ChannelID`,`ActiveID`,`RoomID`,`GameID`,`AddTime`) values (3153,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484277851'),(3154,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484277942'),(3155,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484278068'),(3156,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484278070'),(3157,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484278093'),(3158,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484278764'),(3159,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484278793'),(3160,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484278841'),(3161,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484279484'),(3162,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484288110'),(3163,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484288479'),(3164,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484288496'),(3165,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484288794'),(3166,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484288797'),(3167,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484288887'),(3168,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484288893'),(3169,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289145'),(3170,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289156'),(3171,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289195'),(3172,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484289199'),(3173,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289226'),(3174,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289355'),(3175,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289377'),(3176,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289513'),(3177,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289577'),(3178,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289610'),(3179,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289612'),(3180,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289642'),(3181,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289681'),(3182,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289738'),(3183,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484289744'),(3184,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289878'),(3185,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484289933'),(3186,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484290118'),(3187,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484290800'),(3188,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291043'),(3189,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291057'),(3190,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291333'),(3191,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291368'),(3192,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291370'),(3193,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291383'),(3194,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291456'),(3195,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291468'),(3196,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291507'),(3197,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291524'),(3198,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291760'),(3199,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484291781'),(3200,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484291783'),(3201,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484292145'),(3202,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484292148'),(3203,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484297968'),(3204,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484298828'),(3205,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484298897'),(3206,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484298920'),(3207,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299018'),(3208,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299184'),(3209,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299277'),(3210,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299334'),(3211,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299408'),(3212,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299447'),(3213,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299489'),(3214,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299526'),(3215,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299584'),(3216,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299586'),(3217,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299775'),(3218,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299853'),(3219,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299871'),(3220,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484299996'),(3221,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300026'),(3222,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300067'),(3223,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300118'),(3224,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300136'),(3225,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300174'),(3226,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300194'),(3227,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300283'),(3228,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300340'),(3229,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300432'),(3230,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300438'),(3231,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300468'),(3232,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300481'),(3233,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484300526'),(3234,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484300574'),(3235,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484300615'),(3236,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484300698'),(3237,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484488719'),(3238,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484488870'),(3239,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484488932'),(3240,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484488934'),(3241,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484489026'),(3242,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484489055'),(3243,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484489076'),(3244,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484489102'),(3245,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484528866'),(3246,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484529523'),(3247,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484531612'),(3248,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484531706'),(3249,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484532751'),(3250,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484532912'),(3251,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484532959'),(3252,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533144'),(3253,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533197'),(3254,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533333'),(3255,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533394'),(3256,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533430'),(3257,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533447'),(3258,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533743'),(3259,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533764'),(3260,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533776'),(3261,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484533825'),(3262,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484535248'),(3263,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484535414'),(3264,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484550252'),(3265,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484550355'),(3266,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484552799'),(3267,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484554512'),(3268,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484554648'),(3269,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484554894'),(3270,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484557068'),(3271,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484559235'),(3272,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484559472'),(3273,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484559490'),(3274,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484559891'),(3275,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484559984'),(3276,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484560074'),(3277,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484575660'),(3278,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484576019'),(3279,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484579747'),(3280,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484579767'),(3281,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484579823'),(3282,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484580022'),(3283,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484580272'),(3284,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484580308'),(3285,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484580309'),(3286,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484615776'),(3287,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617552'),(3288,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617856'),(3289,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617858'),(3290,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617860'),(3291,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617965'),(3292,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617966'),(3293,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484617968'),(3294,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484618032'),(3295,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484618048'),(3296,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619641'),(3297,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619643'),(3298,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619644'),(3299,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619652'),(3300,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619682'),(3301,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619683'),(3302,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619684'),(3303,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619697'),(3304,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484619699'),(3305,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484620413'),(3306,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484620513'),(3307,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484620661'),(3308,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484620802'),(3309,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484620915'),(3310,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484620936'),(3311,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484625242'),(3312,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484811246'),(3313,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484811295'),(3314,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484811351'),(3315,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484811786'),(3316,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484811791'),(3317,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484811846'),(3318,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484811860'),(3319,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484812045'),(3320,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484812047'),(3321,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484812162'),(3322,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484812169'),(3323,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484812173'),(3324,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484812290'),(3325,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484812356'),(3326,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484812388'),(3327,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484814042'),(3328,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815134'),(3329,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815173'),(3330,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815203'),(3331,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815236'),(3332,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815272'),(3333,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815296'),(3334,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815385'),(3335,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815388'),(3336,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815557'),(3337,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815696'),(3338,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815749'),(3339,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815751'),(3340,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484815901'),(3341,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816034'),(3342,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816070'),(3343,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816139'),(3344,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816141'),(3345,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816142'),(3346,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816159'),(3347,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816188'),(3348,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816247'),(3349,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816295'),(3350,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484816496'),(3351,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484816694'),(3352,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484816774'),(3353,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484816814'),(3354,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484816855'),(3355,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484817888'),(3356,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484817920'),(3357,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484817944'),(3358,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1484817997'),(3359,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1484874839'),(3360,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485051368'),(3361,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485051408'),(3362,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485077288'),(3363,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485077359'),(3364,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485077725'),(3365,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485077746'),(3366,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485077988'),(3367,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485078881'),(3368,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485078967'),(3369,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485078973'),(3370,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485136008'),(3371,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485136287'),(3372,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485136301'),(3373,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485136412'),(3374,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485136613'),(3375,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485137286'),(3376,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485137403'),(3377,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485137426'),(3378,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485137431'),(3379,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485137716'),(3380,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485137802'),(3381,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485140870'),(3382,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485140876'),(3383,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485140920'),(3384,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485140997'),(3385,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141009'),(3386,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141027'),(3387,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141054'),(3388,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141063'),(3389,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485141065'),(3390,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141307'),(3391,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485141310'),(3392,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141441'),(3393,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141520'),(3394,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485141525'),(3395,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141585'),(3396,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485141587'),(3397,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141669'),(3398,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485141678'),(3399,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141686'),(3400,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141718'),(3401,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485141719'),(3402,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141737'),(3403,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141767'),(3404,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141820'),(3405,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141924'),(3406,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485141944'),(3407,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142047'),(3408,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485142052'),(3409,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142068'),(3410,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485142072'),(3411,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142341'),(3412,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142610'),(3413,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485142626'),(3414,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485142646'),(3415,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142656'),(3416,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142687'),(3417,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485142702'),(3418,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142807'),(3419,'oM0MxsxV0Tb0DTjl80N8VP0Brazw',1,25,7,NULL,'1485142826'),(3420,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142872'),(3421,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485142896'),(3422,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485144552'),(3423,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485156164'),(3424,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1485231541'),(3425,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486173719'),(3426,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486192572'),(3427,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486193019'),(3428,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486193036'),(3429,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486341738'),(3430,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486348533'),(3431,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486348703'),(3432,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486349647'),(3433,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486350584'),(3434,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486366249'),(3435,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486366304'),(3436,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486366567'),(3437,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486368249'),(3438,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486368522'),(3439,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486369874'),(3440,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486369890'),(3441,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486369916'),(3442,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486370873'),(3443,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486370899'),(3444,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486370921'),(3445,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486370951'),(3446,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371005'),(3447,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371027'),(3448,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371036'),(3449,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371075'),(3450,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371097'),(3451,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371098'),(3452,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486371125'),(3453,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486373381'),(3454,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486428433'),(3455,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486433628'),(3456,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435109'),(3457,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435238'),(3458,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435600'),(3459,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435616'),(3460,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435757'),(3461,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435936'),(3462,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486435938'),(3463,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486436034'),(3464,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486436048'),(3465,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486436134'),(3466,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486436208'),(3467,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486437868'),(3468,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486438160'),(3469,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486438212'),(3470,'oM0MxsyO2H_CsGnGJ5TkejcsLTzE',1,25,7,NULL,'1486514910');

/*Table structure for table `zy_game_room` */

DROP TABLE IF EXISTS `zy_game_room`;

CREATE TABLE `zy_game_room` (
  `RoomID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '游戏库ID',
  `GameName` varchar(50) DEFAULT NULL COMMENT '游戏名称',
  `GameType` tinyint(1) DEFAULT '0' COMMENT '游戏类型',
  `GameResume` text COMMENT '游戏介绍',
  `ScreenImages` text COMMENT '游戏截图',
  `UID` int(11) unsigned DEFAULT NULL COMMENT '最近更新用户',
  `UpdateTime` int(11) unsigned DEFAULT NULL COMMENT '最近更新时间',
  `Version` varchar(20) DEFAULT NULL COMMENT '最新游戏版本',
  `ActiveUseNum` int(11) unsigned DEFAULT '0' COMMENT '活动使用总数',
  `VistNum` int(11) unsigned DEFAULT '0' COMMENT '游戏被访问总数',
  `Status` tinyint(1) DEFAULT '0' COMMENT '状态(0测试,1开放,2停用,3维护中)',
  `Remark` varchar(255) DEFAULT NULL COMMENT '游戏备注',
  `Folder` varchar(20) DEFAULT NULL COMMENT '游戏资源的文件夹名称',
  `ZipUrl` varchar(200) NOT NULL COMMENT '压缩包路径',
  `ScoketPort` varchar(8) NOT NULL DEFAULT '' COMMENT 'scoket服务器的端口',
  PRIMARY KEY (`RoomID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='游戏库';

/*Data for the table `zy_game_room` */

/*Table structure for table `zy_goods` */

DROP TABLE IF EXISTS `zy_goods`;

CREATE TABLE `zy_goods` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '物品id',
  `goodsName` varchar(32) DEFAULT NULL COMMENT '物品名称',
  `goodsClass` int(4) DEFAULT '0' COMMENT '物品大类',
  `goodsType` int(4) DEFAULT '0' COMMENT '物品小类',
  `priceByLD` int(8) DEFAULT '0' COMMENT '用乐豆购买物品的价格',
  `priceByJB` int(4) DEFAULT '0' COMMENT '用金币购买物品的价格',
  `needTime` int(11) DEFAULT '0' COMMENT '成长所需要的时间',
  `becomeGoodsId` int(4) DEFAULT '0' COMMENT '加工或者包装之后产品的id',
  `yanyeId` int(4) DEFAULT '0' COMMENT '配方搭配的烟叶id',
  `yanyeNum` int(4) DEFAULT '0' COMMENT '配方搭配的烟叶数量',
  `spiceId` int(4) DEFAULT '0' COMMENT '配方搭配的香料id',
  `spiceNum` int(4) DEFAULT '0' COMMENT '配方搭配的香料数量',
  `filterId` int(4) DEFAULT '0' COMMENT '配方搭配的滤嘴id',
  `filterNum` int(4) DEFAULT '0' COMMENT '配方搭配的滤嘴数量',
  `cigaretteId` int(4) DEFAULT '0' COMMENT '包装所使用的烟的id',
  `cigaretteNum` int(4) DEFAULT '0' COMMENT '包装所使用的烟的数量',
  `breedLD` int(4) DEFAULT '0' COMMENT '培育，研发需要的乐豆',
  `breedJB` int(4) DEFAULT '0' COMMENT '培育，研发需要的金币',
  `breedTime` int(4) DEFAULT '0' COMMENT '培育，研发需要的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `zy_goods` */

insert  into `zy_goods`(`id`,`goodsName`,`goodsClass`,`goodsType`,`priceByLD`,`priceByJB`,`needTime`,`becomeGoodsId`,`yanyeId`,`yanyeNum`,`spiceId`,`spiceNum`,`filterId`,`filterNum`,`cigaretteId`,`cigaretteNum`,`breedLD`,`breedJB`,`breedTime`) values (1,'a种子',1,0,10,20,20,5,0,0,0,0,0,0,0,0,1,10,72),(2,'b种子',1,1,20,40,20,5,0,0,0,0,0,0,0,0,1,10,108),(3,'c种子',1,2,30,60,3200,5,0,0,0,0,0,0,0,0,2,20,72),(4,'土地',2,0,50,100,0,0,0,0,0,0,0,0,0,0,0,0,0),(5,'粗质烟叶',3,0,15,30,0,0,0,0,0,0,0,0,0,0,0,0,0),(6,'良好烟叶',3,1,20,40,0,0,0,0,0,0,0,0,0,0,0,0,0),(7,'优质烟叶',3,2,35,70,0,0,0,0,0,0,0,0,0,0,0,0,0),(8,'a基础配方',4,0,15,30,15,17,5,2,11,1,14,1,0,0,1,10,72),(9,'b改良配方',4,1,30,60,20,18,5,2,12,1,15,1,0,0,1,10,108),(10,'c经典配方',4,2,45,90,25,19,5,2,13,1,16,1,0,0,2,20,72),(11,'a香料',5,0,5,10,0,0,0,0,0,0,0,0,0,0,0,0,0),(12,'b香料',5,1,10,20,0,0,0,0,0,0,0,0,0,0,0,0,0),(13,'c香料',5,2,15,30,0,0,0,0,0,0,0,0,0,0,0,0,0),(14,'a滤嘴',6,0,5,10,0,0,0,0,0,0,0,0,0,0,0,0,0),(15,'b滤嘴',6,1,10,20,0,0,0,0,0,0,0,0,0,0,0,0,0),(16,'c滤嘴',6,2,15,30,0,0,0,0,0,0,0,0,0,0,0,0,0),(17,'海韵烟支',7,0,10,20,0,0,0,0,0,0,0,0,0,0,0,0,0),(18,'鸿韵烟支',7,1,15,30,0,0,0,0,0,0,0,0,0,0,0,0,0),(19,'珍品烟支',7,2,20,40,0,0,0,0,0,0,0,0,0,0,0,0,0),(20,'海韵包装盒',8,0,5,10,10,23,0,0,0,0,0,0,17,20,0,0,0),(21,'鸿韵包装盒',8,1,15,30,20,24,0,0,0,0,0,0,18,20,0,0,0),(22,'珍品包装盒',8,2,25,50,30,25,0,0,0,0,0,0,19,20,0,0,0),(23,'海韵盒装烟',9,0,300,600,0,0,0,0,0,0,0,0,0,0,0,0,0),(24,'鸿韵盒装烟',9,1,400,800,0,0,0,0,0,0,0,0,0,0,0,0,0),(25,'珍品盒装烟',9,2,500,1000,0,0,0,0,0,0,0,0,0,0,0,0,0);

/*Table structure for table `zy_grow_record` */

DROP TABLE IF EXISTS `zy_grow_record`;

CREATE TABLE `zy_grow_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '种子培育记录id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `status` int(4) DEFAULT '0' COMMENT '培育状态：0正在培育，1培育完成',
  `startTime` int(11) DEFAULT NULL COMMENT '开始培育时间',
  `endTime` int(11) DEFAULT NULL COMMENT '结束培育时间',
  `result` int(4) DEFAULT '0' COMMENT '种子培育结果：',
  `addTime` int(11) DEFAULT NULL COMMENT '记录添加时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '记录更新时间',
  `spending` int(4) DEFAULT NULL COMMENT '培育开销',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_grow_record` */

/*Table structure for table `zy_house` */

DROP TABLE IF EXISTS `zy_house`;

CREATE TABLE `zy_house` (
  `houseId` int(32) NOT NULL AUTO_INCREMENT COMMENT '玩家房屋id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `houseName` varchar(64) DEFAULT NULL COMMENT '房屋名称',
  `houseGrade` int(4) DEFAULT NULL COMMENT '房屋等级',
  `houseType` int(4) DEFAULT NULL COMMENT '房屋类型:0农场小屋,1配方研究所,2种子研究中心,3真龙商行,4路边小摊,5制烟工厂,6仓库,7土地',
  `addTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `xPosition` int(10) DEFAULT NULL COMMENT '房屋X坐标',
  `yPosition` int(10) DEFAULT NULL COMMENT '房屋Y坐标',
  PRIMARY KEY (`houseId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_house` */

/*Table structure for table `zy_in_storage_record` */

DROP TABLE IF EXISTS `zy_in_storage_record`;

CREATE TABLE `zy_in_storage_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '物品入库记录id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `name` varchar(32) DEFAULT NULL COMMENT '物品入库名称',
  `type` int(4) DEFAULT NULL COMMENT '物品入库类型',
  `source` varchar(32) DEFAULT NULL COMMENT '物品入库来源',
  `num` int(10) DEFAULT NULL COMMENT '物品入库总数',
  `price` int(10) DEFAULT NULL COMMENT '物品入库总价',
  `addTime` int(11) DEFAULT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_in_storage_record` */

/*Table structure for table `zy_land` */

DROP TABLE IF EXISTS `zy_land`;

CREATE TABLE `zy_land` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '土地id',
  `buyRecordId` int(32) DEFAULT '0' COMMENT '购买记录的主键',
  `uId` int(32) DEFAULT '0' COMMENT '玩家id',
  `landStatus` int(4) DEFAULT '0' COMMENT '该块土地种植状态:0空闲状态,1种植状态，2成熟但未收割状态',
  `addTime` int(11) DEFAULT NULL COMMENT '该块土地购买的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

/*Data for the table `zy_land` */

insert  into `zy_land`(`id`,`buyRecordId`,`uId`,`landStatus`,`addTime`) values (2,13,186,0,1484620940),(1,13,186,0,1484620940),(0,13,186,0,1484620940),(3,13,186,0,1484620940),(4,13,186,0,1484620940),(5,13,186,0,1484620940),(54,18,186,0,1485051879),(55,18,186,2,1485051879),(56,18,186,2,1485051879),(57,25,186,0,1486460607),(58,25,186,0,1486460607),(59,25,186,0,1486460607),(60,25,186,0,1486460607),(61,25,186,0,1486460607);

/*Table structure for table `zy_machining_record` */

DROP TABLE IF EXISTS `zy_machining_record`;

CREATE TABLE `zy_machining_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '加工记录id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `status` int(4) DEFAULT NULL COMMENT '加工状态',
  `startTime` int(11) DEFAULT NULL COMMENT '开始加工时间',
  `endTime` int(11) DEFAULT NULL COMMENT '加工结束时间',
  `type` int(4) DEFAULT NULL COMMENT '加工类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_machining_record` */

/*Table structure for table `zy_out_storage_record` */

DROP TABLE IF EXISTS `zy_out_storage_record`;

CREATE TABLE `zy_out_storage_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '物品出库记录id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `name` varchar(32) DEFAULT NULL COMMENT '物品名称',
  `type` int(4) DEFAULT NULL COMMENT '物品类型',
  `num` int(10) DEFAULT NULL COMMENT '物品总数',
  `price` int(10) DEFAULT NULL COMMENT '物品总价',
  `addTime` int(11) DEFAULT NULL COMMENT '出库时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_out_storage_record` */

/*Table structure for table `zy_packing_record` */

DROP TABLE IF EXISTS `zy_packing_record`;

CREATE TABLE `zy_packing_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '包装记录id',
  `goodsId` int(32) DEFAULT '0' COMMENT '包装成产品对应goods表的id',
  `uId` int(32) DEFAULT '0' COMMENT '玩家id',
  `packingId` int(32) DEFAULT '0' COMMENT '包装id',
  `startPackingTime` int(11) DEFAULT NULL COMMENT '开始包装的时间',
  `endPackingTime` int(11) DEFAULT NULL COMMENT '结束包装时间',
  `status` int(4) DEFAULT '0' COMMENT '包装状态：0未包装，1在包装，2包装完成，3已收入仓库',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `zy_packing_record` */

insert  into `zy_packing_record`(`id`,`goodsId`,`uId`,`packingId`,`startPackingTime`,`endPackingTime`,`status`) values (1,23,186,20,1486433655,1486433666,3),(2,23,186,20,1486433747,1486433758,3),(3,24,186,21,1486435946,1486435968,3),(4,25,186,22,1486436025,1486525967,2),(5,25,186,22,1486436052,1486436083,3),(6,25,186,22,1486436139,1486436170,3),(7,24,186,21,1486451641,1486451776,3);

/*Table structure for table `zy_pick_record` */

DROP TABLE IF EXISTS `zy_pick_record`;

CREATE TABLE `zy_pick_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '采摘记录id',
  `plantId` int(32) DEFAULT NULL COMMENT '种植id(明确采摘的是哪次种植的东西)',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `plantType` int(4) DEFAULT NULL COMMENT '采摘物品类型',
  `plantName` varchar(16) DEFAULT NULL COMMENT '采摘物品名称',
  `pickTime` int(11) DEFAULT NULL COMMENT '采摘的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_pick_record` */

/*Table structure for table `zy_plant_record` */

DROP TABLE IF EXISTS `zy_plant_record`;

CREATE TABLE `zy_plant_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '土地种植记录id',
  `goodsId` int(32) DEFAULT '0' COMMENT '物品对应goods表的id',
  `uId` int(32) DEFAULT '0' COMMENT '玩家id',
  `landId` int(32) DEFAULT '0' COMMENT '土地id',
  `startPlantTime` int(11) DEFAULT NULL COMMENT '开始种植的时间',
  `endPlantTime` int(11) DEFAULT NULL COMMENT '结束种植时间',
  `status` int(4) DEFAULT '0' COMMENT '成长状态：0未种植，1在种植，2成熟状态，3已收割',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=609 DEFAULT CHARSET=utf8;

/*Data for the table `zy_plant_record` */

insert  into `zy_plant_record`(`id`,`goodsId`,`uId`,`landId`,`startPlantTime`,`endPlantTime`,`status`) values (608,1,186,0,1486545540,1486545561,3),(607,1,186,3,1486545540,1486545561,3),(606,1,186,1,1486545540,1486545560,3),(605,1,186,4,1486545539,1486545559,3),(604,1,186,5,1486545539,1486545559,3),(603,1,186,2,1486545539,1486545559,3),(602,1,186,2,1486537965,1486537985,3),(601,1,186,5,1486537965,1486537985,3),(600,1,186,4,1486537964,1486537984,3),(599,1,186,3,1486537964,1486537984,3),(598,1,186,1,1486537964,1486537984,3),(597,1,186,0,1486537964,1486537984,3),(596,3,186,5,1486535847,NULL,1),(595,1,186,0,1486525348,1486525531,2),(594,2,186,2,1486459252,1486459262,2),(593,1,186,1,1486459248,1486522696,2);

/*Table structure for table `zy_research_record` */

DROP TABLE IF EXISTS `zy_research_record`;

CREATE TABLE `zy_research_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '配方研究记录id',
  `goodsId` int(32) DEFAULT '0' COMMENT '所要研究的配方对应goods表的id',
  `uId` int(32) DEFAULT '0' COMMENT '玩家id',
  `startResearchTime` int(11) DEFAULT NULL COMMENT '开始研究的时间',
  `endResearchTime` int(11) DEFAULT NULL COMMENT '结束研究时间',
  `status` int(4) DEFAULT '0' COMMENT '研究状态：0未研究，1在研究，2研究完成，3已收入仓库',
  `payType` int(4) DEFAULT '0' COMMENT '支付方式：0乐豆，1金币',
  `researchMoney` int(4) DEFAULT '0' COMMENT '研究消耗的金额',
  `researchTime` int(4) DEFAULT '0' COMMENT '研究消耗的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `zy_research_record` */

insert  into `zy_research_record`(`id`,`goodsId`,`uId`,`startResearchTime`,`endResearchTime`,`status`,`payType`,`researchMoney`,`researchTime`) values (1,10,186,1486551469,1486551809,3,0,2,72),(2,10,186,1486552170,1486552264,3,0,2,72);

/*Table structure for table `zy_review_record` */

DROP TABLE IF EXISTS `zy_review_record`;

CREATE TABLE `zy_review_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '配方研究记录id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `status` int(4) DEFAULT '0' COMMENT '研究状态：0正在研究，1研究完成',
  `startTime` int(11) DEFAULT NULL COMMENT '开始研究时间',
  `endTime` int(11) DEFAULT NULL COMMENT '结束研究时间',
  `result` int(4) DEFAULT '0' COMMENT '配方研究结果：0基础配方，1经典配方，2改良配方',
  `addTime` int(11) DEFAULT NULL COMMENT '记录添加时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '记录更新时间',
  `spending` int(4) DEFAULT NULL COMMENT '研究开销',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_review_record` */

/*Table structure for table `zy_sale_record` */

DROP TABLE IF EXISTS `zy_sale_record`;

CREATE TABLE `zy_sale_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '玩家卖出物品记录id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `saleType` int(4) DEFAULT NULL COMMENT '卖出物品的类型：',
  `saleNum` int(10) DEFAULT NULL COMMENT '卖出数量',
  `salePrice` int(10) DEFAULT NULL COMMENT '卖出价钱',
  `addTime` int(11) DEFAULT NULL COMMENT '卖出时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_sale_record` */

/*Table structure for table `zy_seed` */

DROP TABLE IF EXISTS `zy_seed`;

CREATE TABLE `zy_seed` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '种子库id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `seed1Num` int(10) DEFAULT '0' COMMENT '种子1数量',
  `seed1UpdateTime` int(11) DEFAULT NULL COMMENT '种子1数量最近更新时间',
  `seed2Num` int(10) DEFAULT '0' COMMENT '种子2数量',
  `seed2UpdateTime` int(11) DEFAULT NULL COMMENT '种子2数量最近更新时间',
  `seed3Num` int(10) DEFAULT '0' COMMENT '种子3数量',
  `seed3UpdateTime` int(11) DEFAULT NULL COMMENT '种子3数量最近更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_seed` */

/*Table structure for table `zy_store_house` */

DROP TABLE IF EXISTS `zy_store_house`;

CREATE TABLE `zy_store_house` (
  `id` int(128) NOT NULL AUTO_INCREMENT COMMENT '仓库',
  `goodsId` int(32) DEFAULT NULL COMMENT '购买物品对应goods表的id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `num` int(4) DEFAULT '0' COMMENT '数量',
  `updateTime` int(11) DEFAULT NULL COMMENT '最新更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `zy_store_house` */

insert  into `zy_store_house`(`id`,`goodsId`,`uId`,`num`,`updateTime`) values (10,5,186,14,1486546478),(9,3,186,5200,1486548058),(8,2,186,9000,1484625256),(7,1,186,8501,1484625252),(13,8,186,11,1486460150),(14,9,186,10,1486199034),(15,10,186,12,1486552287),(16,11,186,938,1486546478),(17,12,186,993,1486523495),(18,13,186,997,1486519989),(19,14,186,938,1486546478),(20,15,186,993,1486523495),(21,16,186,997,1486519989),(22,17,186,858,1486546508),(23,20,186,998,1486433747),(24,23,186,2,1486433784),(25,18,186,862,1486451641),(26,19,186,741,1486436139),(27,21,186,998,1486451641),(28,22,186,997,1486436139),(29,24,186,2,1486451826),(30,25,186,2,1486436193);

/*Table structure for table `zy_store_manage` */

DROP TABLE IF EXISTS `zy_store_manage`;

CREATE TABLE `zy_store_manage` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '仓库管理id',
  `uId` int(32) DEFAULT NULL COMMENT '玩家id',
  `name` varchar(32) DEFAULT NULL COMMENT '物品名称',
  `type` int(4) DEFAULT NULL COMMENT '物品类型',
  `source` varchar(32) DEFAULT NULL COMMENT '物品来源',
  `num` int(10) DEFAULT NULL COMMENT '物品总数',
  `price` int(10) DEFAULT NULL COMMENT '物品总价',
  `addTime` int(11) DEFAULT NULL COMMENT '添加时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '最近更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_store_manage` */

/*Table structure for table `zy_user` */

DROP TABLE IF EXISTS `zy_user`;

CREATE TABLE `zy_user` (
  `userId` int(32) NOT NULL AUTO_INCREMENT COMMENT '玩家ID',
  `md5Uid` varchar(32) DEFAULT NULL,
  `openId` varchar(50) DEFAULT NULL COMMENT '微信用户openid',
  `nickName` varchar(50) DEFAULT NULL COMMENT '微信昵称',
  `experienceValue` int(8) DEFAULT '0' COMMENT '经验值',
  `gameGrade` int(8) NOT NULL DEFAULT '0' COMMENT '游戏等级',
  `storeTotalCap` int(10) DEFAULT '0' COMMENT '玩家仓库总容量',
  `leDouNum` int(8) DEFAULT '0' COMMENT '乐豆数量',
  `goldNum` int(8) DEFAULT '0' COMMENT '金币数量',
  `headImg` varchar(500) DEFAULT NULL COMMENT '头像微信地址',
  `localImg` varchar(200) DEFAULT NULL COMMENT '头像本地地址',
  `allowMusic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许开启音乐，0允许，1禁止',
  `sessionId` varchar(100) DEFAULT NULL,
  `phoneOs` varchar(1000) DEFAULT NULL COMMENT '浏览器信息',
  `updateNumTime` int(11) DEFAULT NULL COMMENT '更新最新记录时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '最近活跃时间',
  `addTime` int(11) DEFAULT NULL COMMENT '游戏参与时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '玩家是否正常，0正常，1被拉黑',
  `baseStrRandCode` varchar(100) DEFAULT NULL COMMENT '校验串,玩家进入游戏时发给客户端',
  `baseStrRandNumber` varchar(10) DEFAULT NULL COMMENT '截取位，点击开始游戏时返回客户端，客户端按截取位截取字符串',
  `baseStrRandSubstr` varchar(100) DEFAULT NULL COMMENT '客户端应截取的编码字符串，后台用此编码字符串解码',
  `GameID` int(11) DEFAULT NULL COMMENT '游戏ID',
  `RoomID` int(11) DEFAULT NULL COMMENT '游戏库ID',
  `ChannelID` int(11) DEFAULT NULL COMMENT '玩家所属渠道id',
  `ActiveID` int(11) DEFAULT NULL COMMENT '玩家所属活动ID',
  PRIMARY KEY (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;

/*Data for the table `zy_user` */

insert  into `zy_user`(`userId`,`md5Uid`,`openId`,`nickName`,`experienceValue`,`gameGrade`,`storeTotalCap`,`leDouNum`,`goldNum`,`headImg`,`localImg`,`allowMusic`,`sessionId`,`phoneOs`,`updateNumTime`,`updateTime`,`addTime`,`status`,`baseStrRandCode`,`baseStrRandNumber`,`baseStrRandSubstr`,`GameID`,`RoomID`,`ChannelID`,`ActiveID`) values (186,'66e16d4c71fe0616c864c5d591ab0be7','oM0MxsyO2H_CsGnGJ5TkejcsLTzE','方方',0,0,100000,51694,33470,'http://wx.qlogo.cn/mmopen/FaYC3jcvGMMJTjnicMTEhsspRAvtLmhCBNBSjHYMicm69pwpGJ6oFjIqWddWmgjb24ibFluDJPw9dpkmFg7ZdtF2sKcYTvEguLM/0','http://192.168.1.217/tobacco/static/wxheadimg/tobacco/oM0MxsyO2H_CsGnGJ5TkejcsLTzE.png',0,NULL,'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0',NULL,1486514909,1482906426,0,'xaQwkJgPYEOhlVirbvUGeDfSpNtyqdjsZHALzCnTomRWMXucIBKF',NULL,NULL,0,7,1,25),(189,'5c4948ab48aaf1f4d216c19777b4daa2','oM0MxsxV0Tb0DTjl80N8VP0Brazw','童话',0,0,100000,50000,60000,'http://wx.qlogo.cn/mmopen/FaYC3jcvGMMJTjnicMTEhsspRAvtLmhCBNBSjHYMicm69pwpGJ6oFjIqWddWmgjb24ibFluDJPw9dpkmFg7ZdtF2sKcYTvEguLM/0','http://192.168.1.217/tobacco/static/wxheadimg/tobacco/oM0MxsxV0Tb0DTjl80N8VP0Brazw.png',0,NULL,'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0',NULL,1485142826,1484278841,0,'FZiVSklCRYMuIGcAOfndqoxhHXUgQpzDNyWjsJPbLatTvewrBEKm',NULL,NULL,0,7,1,25);

/*Table structure for table `zy_visit` */

DROP TABLE IF EXISTS `zy_visit`;

CREATE TABLE `zy_visit` (
  `aid` int(32) DEFAULT NULL COMMENT '关注者id',
  `bid` int(32) DEFAULT NULL COMMENT '被关注者id',
  `addTime` int(11) DEFAULT NULL COMMENT '关注的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zy_visit` */

/*Table structure for table `zy_working_record` */

DROP TABLE IF EXISTS `zy_working_record`;

CREATE TABLE `zy_working_record` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '加工记录id',
  `goodsId` int(32) DEFAULT '0' COMMENT '加工成产品对应goods表的id',
  `uId` int(32) DEFAULT '0' COMMENT '玩家id',
  `peifangId` int(32) DEFAULT '0' COMMENT '配方id',
  `startWorkingTime` int(11) DEFAULT NULL COMMENT '开始加工的时间',
  `endWorkingTime` int(11) DEFAULT NULL COMMENT '结束加工时间',
  `status` int(4) DEFAULT '0' COMMENT '加工状态：0未加工，1在加工，2加工完成，3已收入仓库',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

/*Data for the table `zy_working_record` */

insert  into `zy_working_record`(`id`,`goodsId`,`uId`,`peifangId`,`startWorkingTime`,`endWorkingTime`,`status`) values (61,17,186,8,1486526770,1486526786,3),(60,17,186,8,1486526725,1486526741,3),(59,17,186,8,1486526675,1486526691,2),(58,17,186,8,1486526267,1486526283,2),(73,17,186,8,1486546478,1486546494,3),(72,17,186,8,1486545158,1486545189,3),(71,17,186,8,1486545157,1486545173,3),(70,17,186,8,1486541307,1486541323,3),(69,17,186,8,1486538903,1486538919,3),(68,17,186,8,1486538292,1486538308,3),(67,17,186,8,1486536913,1486536944,3),(66,17,186,8,1486536912,1486536928,3),(65,17,186,8,1486536890,1486536906,3),(64,17,186,8,1486536279,1486536302,2),(63,17,186,8,1486536270,1486536286,2),(62,17,186,8,1486536099,1486536115,3),(56,17,186,8,1486523933,1486523949,2),(57,17,186,8,1486524452,1486524468,2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;