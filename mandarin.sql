/*
SQLyog Ultimate v11.3 (64 bit)
MySQL - 5.6.21 : Database - mandarin
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mandarin` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `mandarin`;

/*Table structure for table `contestmetadata` */

DROP TABLE IF EXISTS `contestmetadata`;

CREATE TABLE `contestmetadata` (
  `ContestIndex` int(11) NOT NULL AUTO_INCREMENT,
  `ContestTitle` varchar(150) CHARACTER SET utf8 NOT NULL,
  `Language` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ContestIndex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contestmetadata` */

/*Table structure for table `contestpublish` */

DROP TABLE IF EXISTS `contestpublish`;

CREATE TABLE `contestpublish` (
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `ContestIndex` int(11) NOT NULL,
  `ContestDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contestpublish` */

/*Table structure for table `contestrecords` */

DROP TABLE IF EXISTS `contestrecords`;

CREATE TABLE `contestrecords` (
  `ClubID` int(11) NOT NULL,
  `ContestIndex` int(11) NOT NULL,
  `ContestDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contestrecords` */

/*Table structure for table `exceptiondate` */

DROP TABLE IF EXISTS `exceptiondate`;

CREATE TABLE `exceptiondate` (
  `Date` date NOT NULL,
  `NotRegularKey` tinyint(4) NOT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `exceptiondate` */

/*Table structure for table `guests` */

DROP TABLE IF EXISTS `guests`;

CREATE TABLE `guests` (
  `guestid` int(11) NOT NULL AUTO_INCREMENT,
  `guestname` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `tmcclubindex` int(11) DEFAULT NULL,
  `phonenumber` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `firstregdate` date DEFAULT NULL,
  `lastregdate` date DEFAULT NULL,
  `lastactdate` date DEFAULT NULL,
  KEY `guestid` (`guestid`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `guests` */

/*Table structure for table `loginrecords` */

DROP TABLE IF EXISTS `loginrecords`;

CREATE TABLE `loginrecords` (
  `DateTime` datetime DEFAULT NULL,
  `UserName` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `IpAddress` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `LoginType` varchar(6) COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `loginrecords` */



/*Table structure for table `meetingagendaroles` */

DROP TABLE IF EXISTS `meetingagendaroles`;

CREATE TABLE `meetingagendaroles` (
  `date` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `president` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `theme` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `tme` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `timer` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `ahcounter` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `grammarian` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `ttm` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `ge` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `saa` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `joke` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `motivator` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `speaker1` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `speaker2` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `speaker3` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `speaker4` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `evaluator1` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `evaluator2` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `evaluator3` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `evaluator4` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `workshop` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `isclosed` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `meetingagendaroles` */

insert  into `meetingagendaroles`(`date`,`president`,`theme`,`tme`,`timer`,`ahcounter`,`grammarian`,`ttm`,`ge`,`saa`,`joke`,`motivator`,`speaker1`,`speaker2`,`speaker3`,`speaker4`,`evaluator1`,`evaluator2`,`evaluator3`,`evaluator4`,`workshop`,`isclosed`) values ('2015-7-9','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-16','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-23','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-30','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-11','NULL','主题是什么',NULL,NULL,NULL,'m/1/黄金/TM/CL/1',NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-18','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-25','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-8-1','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-8-8','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-10-14','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-10-21','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-10-28','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-11-4','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0);

/*Table structure for table `members` */

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `ClubID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` varchar(255) DEFAULT NULL,
  `MemberName` varchar(30) NOT NULL,
  `ValidStatus` tinyint(1) NOT NULL,
  `LevelCC` enum('DTM','ACG','ACS','ACB','CC','TM') NOT NULL DEFAULT 'TM',
  `LevelCL` enum('DTM','ALS','ALB','CL','TM') NOT NULL DEFAULT 'TM',
  `ChineseName` varchar(30) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PhoneNo` varchar(20) DEFAULT NULL,
  `QQNumber` varchar(20) DEFAULT NULL,
  `WeiboID` varchar(50) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `CurrentCC` enum('10','9','8','7','6','5','4','3','2','1') NOT NULL,
  `CurrentCL` enum('10','9','8','7','6','5','4','3','2','1') NOT NULL,
  PRIMARY KEY (`ClubID`)
) ENGINE=MyISAM AUTO_INCREMENT=169 DEFAULT CHARSET=utf8;

/*Data for the table `members` */


/*Table structure for table `officers` */

DROP TABLE IF EXISTS `officers`;

CREATE TABLE `officers` (
  `ValidDate` date NOT NULL DEFAULT '0000-00-00',
  `ExpireDate` date DEFAULT NULL,
  `President` int(11) DEFAULT NULL,
  `VPE` int(11) DEFAULT NULL,
  `VPM` int(11) DEFAULT NULL,
  `VPPR` int(11) DEFAULT NULL,
  `SAA` int(11) DEFAULT NULL,
  `Treasurer` int(11) DEFAULT NULL,
  `Secretary` int(11) DEFAULT NULL,
  PRIMARY KEY (`ValidDate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `officers` */

/*Table structure for table `projectsrecords` */

DROP TABLE IF EXISTS `projectsrecords`;

CREATE TABLE `projectsrecords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meetingdate` date DEFAULT NULL,
  `clubid` int(11) DEFAULT NULL,
  `guestid` int(11) DEFAULT NULL,
  `ismember` tinyint(4) DEFAULT NULL,
  `inwhichclubid` int(11) DEFAULT NULL,
  `role` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `ccorcl` enum('CC','CL','NOCCCL') COLLATE utf8_bin DEFAULT NULL,
  `project` int(11) DEFAULT NULL,
  `content` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `status` enum('PASSED','UNPASSED','UNVALID','REGING') COLLATE utf8_bin DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `projectsrecords` */

/*Table structure for table `rolenames` */

DROP TABLE IF EXISTS `rolenames`;

CREATE TABLE `rolenames` (
  `Number` int(11) NOT NULL AUTO_INCREMENT,
  `RoleKey` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `RoleName` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `CCorCLOld` enum('CC','NOCCCL') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CCorCLMar6` enum('CC','CL','NOCCCL') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `AllowDuplicated` tinyint(4) DEFAULT NULL,
  `ShowOrder` int(11) DEFAULT NULL,
  `IsShown` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Number`)
) ENGINE=MyISAM AUTO_INCREMENT=224 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `rolenames` */

insert  into `rolenames`(`Number`,`RoleKey`,`RoleName`,`CCorCLOld`,`CCorCLMar6`,`AllowDuplicated`,`ShowOrder`,`IsShown`) values (3,'tme','总主持人','NOCCCL','CL',0,3,1),(4,'timer','时间官','NOCCCL','CL',0,4,1),(5,'ahcounter','哼哈官','NOCCCL','CL',0,5,1),(6,'grammarian','语法官','NOCCCL','CL',0,6,1),(7,'ttm','即兴演讲主持人','NOCCCL','CL',0,7,1),(8,'ge','总评官','NOCCCL','CL',0,8,1),(9,'saa','接待官','NOCCCL','NOCCCL',1,9,1),(10,'joke','摄影师','NOCCCL','NOCCCL',0,10,1),(11,'motivator','Motivation Quote','NOCCCL','NOCCCL',1,11,0),(12,'speaker1','备稿演讲者1','CC','CC',0,12,1),(13,'speaker2','备稿演讲者2','CC','CC',0,13,1),(14,'speaker3','备稿演讲者3','CC','CC',0,14,1),(15,'speaker4','备稿演讲者4','CC','CC',0,15,1),(16,'evaluator1','个体评估人1','NOCCCL','CL',0,16,1),(17,'evaluator2','个体评估人2','NOCCCL','CL',0,17,1),(18,'evaluator3','个体评估人3','NOCCCL','CL',0,18,1),(19,'evaluator4','个体评估人4','NOCCCL','CL',0,19,1),(20,'workshop','专题讨论会','NOCCCL','NOCCCL',1,20,1),(2,'theme','主题','NOCCCL','NOCCCL',1,2,1),(1,'president','主席','NOCCCL','NOCCCL',1,1,1);

/*Table structure for table `tmcclubs` */

DROP TABLE IF EXISTS `tmcclubs`;

CREATE TABLE `tmcclubs` (
  `tmcclubindex` int(11) NOT NULL AUTO_INCREMENT,
  `tmcclubname` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `tmcclubshortname` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `validdate` date DEFAULT NULL,
  `expiredate` date DEFAULT NULL,
  `tmcclubdescription` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  KEY `tmcclubindex` (`tmcclubindex`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `tmcclubs` */

insert  into `tmcclubs`(`tmcclubindex`,`tmcclubname`,`tmcclubshortname`,`valid`,`validdate`,`expiredate`,`tmcclubdescription`) values (0,'Another TMC...','Other',1,'2013-01-01',NULL,NULL),(1,'Invited Guest','GUEST',1,'2013-01-01',NULL,NULL),(2,'Nanjing NO.1 TMC','NO1',1,'2013-01-01',NULL,NULL),(3,'Nanjing HROP TMC','HROP',1,'2013-01-01',NULL,NULL),(4,'Nanjing Smart Speaker TMC','SSTMC',1,'2013-01-01',NULL,NULL),(5,'Nanjing Ericsson TMC','Ericsson',0,'2013-01-01','2013-12-31',NULL),(6,'Nanjing ET TMC','Mandarin',1,'2013-01-01',NULL,NULL),(7,'Nanjing DNA TMC','DNA',1,'2013-01-01',NULL,NULL),(8,'Nanjing Ford TMC','Ford',1,'2013-01-01',NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `clubid` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `pswd` varchar(100) NOT NULL,
  `adminlevel` tinyint(1) NOT NULL DEFAULT '0',
  `onetimeurl` varchar(200) DEFAULT NULL,
  `specialassigned` tinyint(1) NOT NULL DEFAULT '0',
  `toadminlevel` tinyint(1) DEFAULT '0',
  `validdate` date DEFAULT NULL,
  `expiredate` date DEFAULT NULL,
  PRIMARY KEY (`clubid`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

/*Data for the table `users` */


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
