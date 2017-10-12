/*
SQLyog Ultimate v11.3 (64 bit)
MySQL - 5.6.21 : Database - hroptmc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hroptmc` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hroptmc`;

/*Table structure for table `contestmetadata` */

DROP TABLE IF EXISTS `contestmetadata`;

CREATE TABLE `contestmetadata` (
  `ContestIndex` int(11) NOT NULL AUTO_INCREMENT,
  `ContestTitle` varchar(150) CHARACTER SET utf8 NOT NULL,
  `Language` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ContestIndex`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `contestmetadata` */

insert  into `contestmetadata`(`ContestIndex`,`ContestTitle`,`Language`) values (1,'English Speech Contest','English'),(2,'Chinese Speech Contest','Chinese'),(3,'Table Topic Speech Contest','English'),(4,'English Humorous Speech Contest','English'),(5,'Chinese Humorous Speech Contest','Chinese'),(6,'Individual Evaluation Contest','English');

/*Table structure for table `contestpublish` */

DROP TABLE IF EXISTS `contestpublish`;

CREATE TABLE `contestpublish` (
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `ContestIndex` int(11) NOT NULL,
  `ContestDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contestpublish` */

insert  into `contestpublish`(`StartDate`,`EndDate`,`ContestIndex`,`ContestDate`) values ('2015-03-10','2015-04-02',1,'2015-03-26'),('2015-03-10','2015-04-02',2,'2015-04-02'),('2015-03-10','2015-04-02',3,'2015-04-02');

/*Table structure for table `contestrecords` */

DROP TABLE IF EXISTS `contestrecords`;

CREATE TABLE `contestrecords` (
  `ClubID` int(11) NOT NULL,
  `ContestIndex` int(11) NOT NULL,
  `ContestDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contestrecords` */

insert  into `contestrecords`(`ClubID`,`ContestIndex`,`ContestDate`) values (4,1,'2015-03-26'),(2,1,'2015-03-26');

/*Table structure for table `exceptiondate` */

DROP TABLE IF EXISTS `exceptiondate`;

CREATE TABLE `exceptiondate` (
  `Date` date NOT NULL,
  `NotRegularKey` tinyint(4) NOT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `exceptiondate` */

insert  into `exceptiondate`(`Date`,`NotRegularKey`,`Reason`) values ('2013-02-07',1,'Spring Festival'),('2013-02-14',1,'Spring Festival'),('2013-03-07',2,'Speech Contest'),('2013-04-04',1,'Qingming Festival'),('2013-08-22',2,'Speech Contest (1)'),('2013-08-29',2,'Speech Contest (2)'),('2013-09-19',1,'Mid-Autumn Festival'),('2013-10-03',1,'National Day'),('2014-01-02',1,'New Year Holiday'),('2014-01-30',1,'Spring Festival Holiday'),('2014-02-06',1,'Spring Festival Holiday'),('2014-02-27',2,'International<br> Speech Contest<br> (Club Level)'),('2014-03-06',2,'International<br> Speech Contest<br> (Club Level)'),('2014-03-20',2,'DNA&HROP<br>Joint Meeting<br><br>Time: 19:00<br>Venue: Room 101, Bangning Science Park, NO.2, Yuhua Road, Nanjing.<br> (Huashenmiao Metro Station, exit 2. walk in 8 minutes) '),('2014-05-01',1,'Labor\'s Day'),('2014-08-21',2,'International<br> Speech Contest<br> (Club Level)'),('2014-08-28',2,'International<br> Speech Contest<br> (Club Level)'),('2015-02-19',1,'Spring Festival'),('2015-02-26',1,'Spring Festival'),('2015-08-13',2,'Speech Contest (1)'),('2015-08-20',2,'Speech Contest (2)');

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
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
  `date` varchar(20) COLLATE utf8_bin NOT NULL,
  `president` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `theme` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `tme` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `timer` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `ahcounter` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `grammarian` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `ttm` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `ge` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `saa` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `joke` varchar(100) COLLATE utf8_bin NOT NULL,
  `motivator` varchar(100) COLLATE utf8_bin NOT NULL,
  `speaker1` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `speaker2` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `speaker3` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `speaker4` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `evaluator1` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `evaluator2` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `evaluator3` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `evaluator4` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `workshop` varchar(100) COLLATE utf8_bin NOT NULL,
  `isclosed` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `meetingagendaroles` */

insert  into `meetingagendaroles`(`date`,`president`,`theme`,`tme`,`timer`,`ahcounter`,`grammarian`,`ttm`,`ge`,`saa`,`joke`,`motivator`,`speaker1`,`speaker2`,`speaker3`,`speaker4`,`evaluator1`,`evaluator2`,`evaluator3`,`evaluator4`,`workshop`,`isclosed`) values ('2015-1-15','m/57/Lila Duanmu/ACB','manage up-work report','m/57/Lila Duanmu/ACB/CL/10','n/62/Ellan/TM/CL/1/0','m/88/Amy Wang/TM/CL/2','n/47/Lewis Jin/TM/CL/1/0','m/71/July Gao/TM/CL/1','m/59/Carol Shi/TM/CL/1','m/16/Aron Jia/CC','','','n/64/Makai/TM/CC/1/0','m/4/Jerry Jiang/TM/CC/10','m/66/Marrie Ma/TM/CC/9',NULL,'n/63/Audrey/TM/CL/10/0','m/58/Lulu Sheng/ACB, CL/CL/10','m/22/Don Tang/CC/CL/9',NULL,'n/49/Lewis Jin/TM/0',0),('2015-1-8','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-1-1','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-12-25','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/85/Momi Liu/TM/CC/4','m/16/Aron Jia/CC/ACB/1','n/38/alice wei/TM/CC/10/3',NULL,NULL,NULL,NULL,NULL,'',0),('2014-12-18','m/57/Lila Duanmu/ACB','','m/66/Marrie Ma/TM/CL/1','m/78/Karen Cheng/TM/CL/1','n/60/Snow Min/TM/CL/1/0','m/58/Lulu Sheng/ACB, CL/CL/1','m/71/July Gao/TM/CL/1','n/61/Alex/TM/CL/1/6',NULL,'','','m/88/Amy Wang/TM/CC/3','n/38/alice wei/TM/CC/9/2','m/4/Jerry Jiang/TM/CC/10',NULL,'m/14/James Ma/TM/CL/1','m/16/Aron Jia/CC/CL/1','m/57/Lila Duanmu/ACB/CL/1',NULL,'',0),('2014-12-11','m/57/Lila Duanmu/ACB','direct benefits and indirect benefits','m/85/Momi Liu/TM/CL/3','m/88/Amy Wang/TM/CL/1',NULL,NULL,'m/71/July Gao/TM/CL/1','m/57/Lila Duanmu/ACB/CL/1',NULL,'','','m/64/Daisy Du/TM/CC/4','n/38/alice wei/TM/CC/8/3','m/66/Marrie Ma/TM/CC/8',NULL,NULL,NULL,'m/4/Jerry Jiang/TM/CL/1',NULL,'n/38/Lewis/TM/1',0),('2014-12-4','m/57/Lila Duanmu/ACB',NULL,NULL,'n/38/Mandy Hu/TM/CL/1/0',NULL,'n/58/Alex Wang/TM/CL/1/6','m/57/Lila Duanmu/ACB/CL/1','n/59/Ben Wang/TM/CL/1/2',NULL,'','','m/89/Lily Liu/TM/CC/4','m/14/James Ma/TM/CC/7','m/71/July Gao/TM/CC/6',NULL,NULL,NULL,NULL,NULL,'',0),('2014-11-27','m/57/Lila Duanmu/ACB','Interpret Alibaba--Internationalization of E-commerce','m/57/Lila Duanmu/ACB/CL/10','m/85/Momi Liu/TM/CL/1','n/49/Amy Wang/TM/CL/1/2','n/47/Lewis/TM/CL/1/0','m/71/July Gao/TM/CL/1',NULL,NULL,'','','n/48/seraph/TM/CC/1/0','m/89/Lily Liu/TM/CC/3','m/58/Lulu Sheng/ACB, CL/ACS/4',NULL,'m/64/Daisy Du/TM/CL/1','m/59/Carol Shi/TM/CL/1','m/66/Marrie Ma/TM/CL/1',NULL,'',0),('2014-11-20','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,'m/71/July Gao/TM/CL/1',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-11-13','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,'m/16/Aron Jia/CC/CL/1',NULL,NULL,'','',NULL,'m/71/July Gao/TM/CC/6','n/48/Darren Liao/CC/CC/7/2',NULL,'m/14/James Ma/TM/CL/2',NULL,NULL,NULL,'',0),('2014-11-6','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,'n/49/Alice Yan/TM/CL/10/7','m/16/Aron Jia/CC/CL/10','m/71/July Gao/TM/CL/6',NULL,'','','n/57/Alice Wei/TM/CC/5/3','m/88/Amy Wang/TM/CC/2','n/48/Arthur/TM/CC/9/0',NULL,NULL,'m/14/James Ma/TM/CL/2','m/58/Lulu Sheng/ACB, CL/CL/10',NULL,'',0),('2014-10-30','m/57/Lila Duanmu/ACB','the bridge between Trust and delegate','m/71/July Gao/TM/CL/1','n/49/Mandy Hu/TM/CL/1/5','m/16/Aron Jia/CC/CL/1','m/85/Momi Liu/TM/CL/1','m/89/Lily Liu/TM/CL/1','m/66/Marrie Ma/TM/CL/1',NULL,'','','m/88/Amy Wang/TM/CC/3','m/82/Arthur Chen/TM/CC/9','m/4/Jerry Jiang/TM/CC/9',NULL,'m/14/James Ma/TM/CL/1','m/57/Lila Duanmu/ACB/CL/1','m/22/Don Tang/CC/CL/1',NULL,'m/51/Wenbin Liang/CC, CL',0),('2014-10-23','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','n/49/Alice Wei/TM/CC/3/null',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'m/51/Wenbin Liang/CC, CL',0),('2014-10-2','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-10-9','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-10-16','m/57/Lila Duanmu/ACB','Leadership - Overcome Team Conflicts','m/71/July Gao/TM/CL/1','m/89/Lily Liu/TM/CL/1','m/88/Amy Wang/TM/CL/1','n/52/Alex Wang/TM/CL/1/6','m/22/Don Tang/CC/CL/1','m/59/Carol Shi/TM/CL/1',NULL,'','','m/87/Ebull Wang/TM/CC/2','m/82/Arthur Chen/TM/CC/8','n/46/Alice Yan/TM/CC/10/5',NULL,'m/64/Daisy Du/TM/CL/1',NULL,'m/14/James Ma/TM/CL/1',NULL,'m/66/Marrie Ma/TM',0),('2014-9-18','m/57/Lila Duanmu/ACB','','m/66/Marrie Ma/TM/CL/6',NULL,NULL,NULL,NULL,NULL,NULL,'','','m/89/Lily Liu/TM/CC/1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-9-25','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,'m/89/Lily Liu/TM/CL/1',NULL,'m/16/Aron Jia/CC/ALB/3',NULL,NULL,'m/88/Amy Wang/TM','','n/55/grace/TM/CC/2/6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-8-28','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-9-4','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/85/Momi Liu/TM/CC/2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-9-11','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/58/Lulu Sheng/ACB, CL/ACS/3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-8-21','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-8-14','m/57/Lila Duanmu/ACB','','m/57/Lila Duanmu/ACB/CL/10','n/48/Amy Wang/TM/CL/1/0','n/51/Ebull Wang/TM/CL/1/1','n/52/Alex Wang/TM/CL/1/6',NULL,NULL,NULL,'n/50/Herny/TM/2','n/53/Coco Yao/TM/6','m/71/July Gao/TM/CC/5','n/49/Momi/TM/CC/1/1',NULL,NULL,'m/14/James Ma/TM/CL/1','n/46/Alice Yan/TM/CL/1/5',NULL,NULL,'',0),('2014-8-7','m/57/Lila Duanmu/ACB','','m/57/Lila Duanmu/ACB/CL/1','m/14/James Ma/TM/CL/1',NULL,'m/63/Agile Song/TM/CL/1','m/46/Ivy Wu/CC/CL/1','m/66/Marrie Ma/TM/CL/1',NULL,'n/48/Alex/TM/6','n/47/Darren/TM/6','m/87/Ebull Wang/TM/CC/2','m/58/Lulu Sheng/ACB, CL/ACS/2',NULL,NULL,'n/46/Alice Yan/TM/CL/1/5',NULL,NULL,NULL,'',0),('2014-7-17','m/57/Lila Duanmu/ACB','Probation Period-Fill Into The Team','m/46/Ivy Wu/CC/CL/1','m/86/Cathy Xiang/TM/CL/1','m/77/Joan Jiang/TM/CL/1','m/64/Daisy Du/TM/CL/1','m/58/Lulu Sheng/ACB, CL/ALB/1','m/63/Agile Song/TM/CL/1','m/14/James Ma/TM','m/71/July Gao/TM','m/14/James Ma/TM','m/82/Arthur Chen/TM/CC/6','m/66/Marrie Ma/TM/CC/5',NULL,NULL,NULL,'m/19/Anson Chang/TM/CL/1',NULL,NULL,'',0),('2014-7-24','m/57/Lila Duanmu/ACB','Probation Period-Good end Or Bad end?','m/64/Daisy Du/TM/CL/1','m/86/Cathy Xiang/TM/CL/1','m/14/James Ma/TM/CL/1','m/19/Anson Chang/TM/CL/1','m/16/Aron Jia/CC/CL/10','m/4/Jerry Jiang/TM/CL/1','n/43/Scarlley Wu/TM/6','m/79/Sharp Feng/TM','n/42/Amy Wang/TM/1','m/77/Joan Jiang/TM/CC/3','m/58/Lulu Sheng/ACB, CL/ACB/1',NULL,NULL,'m/66/Marrie Ma/TM/CL/1','m/57/Lila Duanmu/ACB/CL/10',NULL,NULL,'',0),('2014-7-31','m/57/Lila Duanmu/ACB','','m/66/Marrie Ma/TM/CL/5',NULL,'n/44/Amy Wang/TM/CL/1/1',NULL,NULL,'m/58/Lulu Sheng/ACB, CL/ALB/1',NULL,'m/64/Daisy Du/TM','','m/14/James Ma/TM/CC/5','m/16/Aron Jia/CC/ACB/1',NULL,NULL,NULL,'m/87/Ebull Wang/TM/CL/2',NULL,NULL,'',0),('2014-7-3','m/57/Lila Duanmu/ACB','','m/58/Lulu Sheng/ACB, CL/ALB/1',NULL,NULL,'m/19/Anson Chang/TM/CL/1','m/57/Lila Duanmu/ACB/ALB/1','n/38/alice yan/TM/CL/1/5',NULL,'m/71/July Gao/TM','m/14/James Ma/TM','m/59/Carol Shi/TM/CC/4','m/66/Marrie Ma/TM/CC/3',NULL,NULL,'m/46/Ivy Wu/CC/CL/1','m/16/Aron Jia/CC/CL/1',NULL,NULL,'',0),('2014-6-26','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/16/Aron Jia/CC/ACB/5',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-7-10','m/57/Lila Duanmu/ACB','Probation Period','m/4/Jerry Jiang/TM/CL/1','m/86/Cathy Xiang/TM/CL/1','n/41/Scaller Wu/TM/CL/1/6','m/71/July Gao/TM/CL/1','m/63/Agile Song/TM/CL/1','m/57/Lila Duanmu/ACB/ALB/1',NULL,'','m/58/Lulu Sheng/ACB, CL','m/66/Marrie Ma/TM/CC/4','n/40/Mandy Hu/TM/CC/2/5','m/46/Ivy Wu/CC/ACB/2',NULL,'m/19/Anson Chang/TM/CL/1','m/64/Daisy Du/TM/CL/1','m/14/James Ma/TM/CL/1',NULL,'n/38/Scott/TM/1',0),('2014-6-12','m/Wenbin Liang/CC, CL','','m/14/James Ma/TM/CL/1',NULL,NULL,NULL,NULL,NULL,NULL,'','','n/37/JIN/TM/CC/1/5','m/82/Arthur Chen/TM/CC/5',NULL,NULL,'m/66/Marrie Ma/TM/CL/2',NULL,NULL,NULL,'',0),('2014-6-19','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-6-5','m/Wenbin Liang/CC, CL','Career Season-Career Movie','m/71/July Gao/TM/CL/1','n/30/Joy Jing/TM/CL/1/5','n/31/Moli Liu/TM/CL/1/1','m/19/Anson Chang/TM/CL/1','n/32/Terry Xiong/CC/CL/1/5','m/4/Jerry Jiang/TM/CL/1','n/34/Alice Wei/TM/3','m/59/Carol Shi/TM','m/58/Lulu Sheng/ACB, CL','m/Stone Xin/TM/CC/3','m/Daisy Du/TM/CC/3','m/77/Joan Jiang/TM/CC/3',NULL,'m/46/Ivy Wu/CC/CL/1','m/22/Don Tang/CC/CL/1','n/33/Marrie Ma/TM/CL/1/0',NULL,'',0),('2014-5-29','m/Wenbin Liang/CC, CL','Career Season-Job Burnout','m/Ivy Wu/CC','m/Hannah Zhao/TM','m/Stone Xin/TM','n/Marrie Ma/TM/OTHER','m/Carol Shi/TM','m/July Gao/TM','n/Alice Wei/TM/ALU','m/Karen Cheng/TM','m/Vicky Wang/TM','m/Arthur Chen/TM/CC/4','m/Joan Jiang/TM/CC/2','n/Terry Xiong/CC/Mandarin/ACB/1',NULL,'n/Lesley Chen/TM/ALU','n/Yoyo Yao/TM/ALU','m/Lulu Sheng/ACB, CL',NULL,'',0),('2014-5-22','m/Wenbin Liang/CC, CL','Career Season - Boss Story','m/Terry Xiong/CC','m/Hannah Zhao/TM','m/Stone Xin/TM','n/Lesley Chen/TM/ALU','m/Agile Song/TM','m/Lulu Sheng/ACB, CL','n/Alice Wei/TM/ALU','m/Karen Cheng/TM','m/Wendy Tang/TM','m/July Gao/TM/CC/4','m/Daisy Du/TM/CC/3','m/Ivy Wu/CC/ACB/2',NULL,'m/Carol Shi/TM','m/Wenbin Liang/CC, CL','n/Yoyo Yao/TM/ALU',NULL,'',0),('2014-5-15','m/Wenbin Liang/CC, CL','Career Prejudice','m/Aron Jia/CC','n/Gordan/TM/ALU','n/Alice Yan/TM/Mandarin','n/Alice Wei/TM/ALU','m/Wenbin Liang/CC, CL','m/Lulu Sheng/ACB, CL','m/Carol Shi/TM','m/Anson Chang/TM','m/Ivy Wu/CC','m/Agile Song/TM/CC/1','n/Chloe/TM/Guest/CC/1','m/Lila Duanmu/CC/ACB/3',NULL,'m/Jerry Jiang/TM','m/July Gao/TM','n/Yoyo/TM/ALU',NULL,'',0),('2014-5-8','m/Wenbin Liang/CC, CL','Generation Gap in career','m/Anson Chang/TM','n/Mandy Hu/TM/Mandarin','m/Sharp Feng/TM','n/Lesley Chen/TM/NO1','m/July Gao/TM','m/Carol Shi/TM',NULL,'m/James Ma/TM','m/Daisy Du/TM','m/Vicky Wang/TM/CC/1','m/Arthur Chen/TM/CC/1','m/Lila Duanmu/CC/ACB/2',NULL,'m/Aron Jia/CC','n/Albert Peng/TM/Guest','m/Wenbin Liang/CC, CL',NULL,'',0),('2014-5-1','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-4-24','m/Wenbin Liang/CC, CL',NULL,'m/Lulu Sheng/ACB, CL','n/Huang Jin/TM/Mandarin','m/Aron Jia/CC','n/albert peng/TM/Guest','m/July Gao/TM','m/Carol Shi/TM',NULL,'m/Agile Song/TM','m/Anson Chang/TM','n/Mandy Hu/TM/Guest/CC/1','m/Joan Jiang/TM/CC/1','m/Daisy Du/TM/CC/2',NULL,'n/chole/TM/Guest','n/Emma/TM/OTHER','m/James Ma/TM',NULL,'',0),('2014-4-17','m/Wenbin Liang/CC, CL','The Embarrassment of My Eye','m/Agile Song/TM','n/Alice Yan/TM/Mandarin','m/Anson Chang/TM','n/AA Wang/TM/Guest','m/Lulu Sheng/ACB, CL','m/Carol Shi/TM','n/Alice Wei/TM/ALU','m/Joan Jiang/TM','m/James Ma/TM','m/Jerry Jiang/TM/CC/7','m/Lila Duanmu/CC/ACB/4','m/Sharp Feng/TM/CC/1',NULL,'m/Sunny Lee/TM','m/Wenbin Liang/CC, CL','m/Arthur Chen/TM',NULL,'',0),('2014-4-10','m/Wenbin Liang/CC, CL','The art of layoff','m/Wenbin Liang/CC, CL','m/James Ma/TM','m/Davy Zhang/TM','m/Agile Song/TM','n/Gordon Zhu/TM/ALU','m/Lulu Sheng/ACB, CL','n/Alice Wei/TM/ALU','m/Carol Shi/TM','m/Joan Jiang/TM','m/Lila Duanmu/CC/ACB/2','m/Arthur Chen/TM/CC/2','m/Wendy Tang/TM/CC/1',NULL,'n/Lesley Chen/TM/ALU','n/Vincent Qiu/TM/NO1','m/July Gao/TM',NULL,'',0),('2014-4-3','m/Wenbin Liang/CC, CL','Exit interviews','m/Carol Shi/TM','n/Gordon Zhu/TM/ALU','m/Arthur Chen/TM','m/Anson Chang/TM','m/Davy Zhang/TM','n/Lesley Chen/CC/ALU',NULL,'n/Alice wei/TM/ALU','m/Candy Li/TM','m/Ivy Wu/CC/ACB/1','m/James Ma/TM/CC/6','m/Joan Jiang/TM/CC/1',NULL,'m/Agile Song/TM','n/Yoyo Yao/TM/ALU','n/Kaya wei/TM/NO1',NULL,'',0),('2014-3-6','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-3-20','m/Wenbin Liang/CC, CL',NULL,'m/Jerry Jiang/TM','n/Tianxiang Huang/TM/DNA',NULL,NULL,NULL,NULL,NULL,'','m/Joan Jiang/TM','m/Lila Duanmu/CC/ACB/2','m/James Ma/TM/CC/5','m/Arthur Chen/TM/CC/1',NULL,NULL,NULL,NULL,NULL,'',0),('2014-3-27','m/Wenbin Liang/CC, CL','Career Planning Season----Analysi of Job Approach Trends','m/Jerry Jiang/TM','n/Alex Huang/TM/DNA','m/Daisy Du/TM','m/Arthur Chen/TM','m/Aron Jia/CC','m/July Gao/TM',NULL,'m/Sharp Feng/TM','m/Wenbin Liang/CC, CL','m/Lila Duanmu/CC/ACB/2','m/James Ma/TM/CC/5','m/Candy Li/TM/CC/1',NULL,'m/Terry Xiong/CC','n/Lesley Chen/TM/ALU','m/Agile Song/TM',NULL,'m/Stone Xin/TM',0),('2014-2-27','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2014-2-20','m/Wenbin Liang/CC, CL','To quit or not to quit','m/Ivy Wu/CC','n/Alice Wei/TM/ALU',NULL,'n/Anson Chang/TM/OTHER','m/Carol Shi/TM','n/Emma Wen/TM/OTHER','n/Alice Wei/TM/ALU','m/Neil Dai/TM','m/Daisy Du/TM','m/Stone Xin/TM/CC/2','m/Sophie Zhou/TM/CC/4','m/Aron Jia/CC/ACB/3',NULL,'m/James Ma/TM','m/Lila Duanmu/CC','m/Lulu Sheng/ACB, CL',NULL,'m/Carol Shi/TM',0),('2014-2-13','m/Wenbin Liang/CC, CL',NULL,NULL,'m/Agile Song/TM','m/July Gao/TM',NULL,'m/Ivy Wu/CC','m/Lulu Sheng/ACB, CL',NULL,'m/Daisy Du/TM','','m/Casey Liu/TM/CC/3','m/Lila Duanmu/CC/ACB/1','m/Aron Jia/CC/ACB/2',NULL,NULL,'m/Don Tang/CC',NULL,NULL,'',0),('2014-3-13','m/Wenbin Liang/CC, CL','Career Planning Season----Career Positioning','m/Lila Duanmu/CC',NULL,'m/Carol Shi/TM','n/Alice Yan/TM/Mandarin','m/Davy Zhang/TM','n/Anson Chang/TM/OTHER','n/Alice Wei/TM/ALU','m/Lulu Sheng/ACB, CL','m/Agile Song/TM','m/James Ma/TM/CC/4','n/Emma Wen/TM/NO1/CC/4','m/Aron Jia/CC/ACB/4',NULL,'m/Jerry Jiang/TM',NULL,NULL,NULL,'',0),('2014-1-23','m/Wenbin Liang/CC, CL',NULL,'m/July Gao/TM',NULL,'m/Sunny Lee/TM','n/Alice Wei/TM/ALU','m/Davy Zhang/TM','m/Carol Shi/TM',NULL,'n/Anson Chang/TM/OTHER','m/Daisy Du/TM',NULL,'m/Lila Duanmu/CC/ACB/2','m/Aron Jia/CC/ACB/1',NULL,'m/Ivy Wu/CC','m/Lulu Sheng/ACB, CL',NULL,NULL,'m/Lila Duanmu/CC',0),('2014-1-16','m/Wenbin Liang/CC, CL',NULL,'m/Davy Zhang/TM','n/Rongrong Dou/TM/ALU','m/Carol Shi/TM','n/Alice Wei/TM/OTHER','m/Ivy Wu/TM','m/Marrie Ma/TM',NULL,'n/Feifei Chen/TM/DNA','m/Agile Song/TM','m/James Ma/TM/CC/4','m/July Gao/TM/CC/2','n/Anson Chang/TM/OTHER/CC/5',NULL,'m/Jerry Jiang/TM','m/Aron Jia/CC','m/Lulu Sheng/ACB, CL',NULL,'m/Lila Duanmu/CC',0),('2014-1-9','m/Wenbin Liang/CC, CL','Salary:Fairness','m/Ivy Wu/TM','m/Daisy Du/TM','m/Sunny Lee/TM','n/Alice Wei/TM/OTHER','n/Anson Chang/TM/OTHER','m/Carol Shi/TM','n/Alice Wei/TM/OTHER','n/Chen Fei Fei/TM/DNA','m/Agile Song/TM','m/Lila Duanmu/CC/ACB/1','m/James Ma/TM/CC/4','m/Davy Zhang/TM/CC/4',NULL,'m/Lulu Sheng/CC, CL','m/Jerry Jiang/TM','n/Yao Yao/TM/ALU',NULL,'m/Wenbin Liang/CC, CL',0),('2013-12-26','m/Wenbin Liang/CC, CL','Promotion','n/Leinie Zhang/TM/NO1','m/Aron Jia/CC','m/July Gao/TM','n/Zach He/TM/Mandarin','m/Wenbin Liang/CC, CL','m/Lila Duanmu/CC',NULL,'m/Sunny Lee/TM','m/Linda Zhou/TM','n/Kaya Wai/TM/NO1/CC/2','n/Annie Zhang/TM/NO1/CC/4','m/Neil Dai/TM/CC/1',NULL,'m/Don Tang/TM','m/Jerry Jiang/TM','m/Ivy Wu/TM',NULL,'',0),('2013-12-19','m/Wenbin Liang/CC, CL','Salary','m/Jerry Jiang/TM','m/Candy Li/TM','m/Casey Liu/TM','m/Marrie Ma/TM','m/Carol Shi/TM','m/Aron Jia/CC','n/Alice Wei/TM/OTHER','m/Agile Song/TM','m/Lila Duanmu/CC','m/July Gao/TM/CC/1','m/Ivy Wu/TM/CC/10',NULL,NULL,'m/Daisy Du/TM','m/Lila Duanmu/CC','',NULL,'m/Aron Jia/CC',0),('2013-12-12','m/Wenbin Liang/CC, CL',NULL,NULL,'m/July Gao/TM','m/Candy Li/TM','m/Sunny Lee/TM','m/Ivy Wu/TM','m/Aron Jia/CC',NULL,'','m/Neil Dai/TM','m/Jerry Jiang/TM/CC/7','m/Lulu Sheng/CC, CL/ACB/4','m/Carol Shi/TM/CC/3',NULL,'m/Agile Song/TM','m/Marrie Ma/TM','m/Lila Duanmu/CC',NULL,'',0),('2013-9-27','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-9-20','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-12-5','m/Wenbin Liang/CC, CL',NULL,'m/Lila Duanmu/CC','m/Candy Li/TM','m/Casey Liu/TM','m/Agile Song/TM','m/Ivy Wu/TM','m/Carol Shi/TM','m/James Ma/TM','','','m/Marrie Ma/TM/CC/2','m/Daisy Du  /TM/CC/1','m/Don Tang/TM/CC/10',NULL,'m/Jerry Jiang/TM','m/Sunny Lee/TM','m/Wenbin Liang/CC, CL',NULL,'',0),('2013-11-21','m/Wenbin Liang/CC, CL','Performance Measurement','m/Lulu Sheng/CC, CL','m/Linda Zhou/TM','m/Davy Zhang/TM','m/Marrie Ma/TM','m/Wenbin Liang/CC, CL','m/Don Tang/TM','m/Agile Song/TM','','','n/Casey Liu/TM/OTHER/CC/1','m/Ivy Wu/TM/CC/10',NULL,NULL,'n/Sunny Lee/TM/OTHER','m/Carol Shi/TM',NULL,NULL,'',0),('2013-11-28','m/Wenbin Liang/CC, CL','Organizational ','m/Ivy Wu/TM','m/Candy Li/TM','m/Marrie Ma/TM','m/Linda Zhou/TM','m/Don Tang/TM','m/Terry Xiong/TM','m/Marrie Ma/TM','','','n/Rongrong/TM/ALU/CC/3','m/Aron Jia/TM/CC/6','m/Lulu Sheng/CC, CL/ACB/4',NULL,'m/James Ma/TM','m/Carol Shi/TM','n/Alisa/CC/NO1',NULL,'',0),('2013-11-14','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/Ivy Wu/TM/CC/9','m/James Ma/TM/CC/5',NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-10-31','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-11-7','m/Wenbin Liang/CC, CL','Performance Goal Setting','m/Davy Zhang/TM','m/Wenbin Liang/CC, CL','n/Cindy/TM/OTHER','m/Linda Zhou/TM','m/Lila Duanmu/TM','m/Lulu Sheng/CC, CL','n/Nana/TM/OTHER','','','m/Carol Shi/TM/CC/2','m/David Chen/TM/CC/3','n/Terry/TM/OTHER/CC/10',NULL,'m/Don Tang/TM','n/Agile/TM/OTHER','m/Ivy Wu/TM',NULL,'',0),('2013-10-17','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-10-24','m/Wenbin Liang/CC, CL','on the job training','m/Linda Zhou/TM','m/Aron Jia/TM',NULL,'n/Andreas Luo/TM/NO1','m/Wenbin Liang/CC, CL','m/Lila Duanmu/TM',NULL,'','','m/Davy Zhang/TM/CC/3','m/Ivy Wu/TM/CC/8',NULL,NULL,'m/James Ma/TM','m/Lulu Sheng/CC, CL',NULL,NULL,'',0),('2013-10-10','m/Wenbin Liang/CC, CL','Orientation Training','m/Wenbin Liang/CC, CL','m/Bright Shen/TM','m/Carol Shi/TM',NULL,'m/Aron Jia/TM','n/Terry xiong/TM/OTHER',NULL,'','','m/Davy Zhang/TM/CC/2','m/Lila Duanmu/TM/CC/8',NULL,NULL,'m/Linda Zhou/TM','m/Lulu Sheng/CC, CL',NULL,NULL,'',0),('2013-10-3','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-9-26','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-9-19','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-9-12','m/Wenbin Liang/CC, CL','Career Planning','m/Lila Duanmu/TM','m/Shining Wang/TM',NULL,'n/Agile Song/TM/OTHER','n/William/TM/ALU','n/Terry Xiong/TM/OTHER','n/Alice wei/TM/OTHER','','','m/Don Tang/TM/CC/6',NULL,NULL,NULL,'m/Linda Zhou/TM',NULL,NULL,NULL,'',0),('2013-8-29','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-9-5','m/Wenbin Liang/CC, CL','Negotiation','m/Davy Zhang/TM','m/David Chen/TM','m/Jerry Jiang/TM','m/Carol Shi/TM','m/Wenbin Liang/CC, CL','m/Aron Jia/TM','m/Stone Xin/TM','','','m/Lila Duanmu/TM/CC/10','n/Richard Han/TM/OTHER/CC/1','m/Ivy Wu/TM/CC/7',NULL,'m/Lulu Sheng/CC, CL','n/Cuckoo Gu/TM/OTHER','n/William/TM/OTHER',NULL,'',0),('2013-8-15','m/Wenbin Liang/CC, CL','Internship','m/Aron Jia/TM','n/Tom Xu/TM/OTHER','n/Nancy/TM/NO1','m/Carol Shi/TM','n/Susie Liu/TM/OTHER','m/Lulu Sheng/CC, CL','m/David Chen/TM','','','n/Jane Wang/TM/NO1/CC/8','m/Lila Duanmu/TM/CC/10','m/Don Tang/TM/CC/6',NULL,'n/Yoyo/TM/ALU','n/Cuckoo Gu/TM/ALU','n/Richard/TM/OTHER',NULL,'',0),('2013-8-22','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-8-8','m/Wenbin Liang/CC, CL',' The Executive Power','m/Lila Duanmu/TM','m/Jerry Jiang/TM','n/Tom Xu/TM/OTHER','n/Susie Liu/TM/OTHER','m/Davy Zhang/TM','m/Ivy Wu/TM','m/Stone Xin/TM','','','m/Shining Wang/TM/CC/6','m/Carol Shi/TM/CC/1','m/Lulu Sheng/CC, CL/ACB/1',NULL,'n/Jane Wang/TM/NO1','m/Aron Jia/TM','n/Cuckoo Gu/TM/ALU',NULL,'',0),('2013-8-1','m/Lila Duanmu/TM','Business Ethics','m/Jerry Jiang/TM','n/Nancy Zhang/TM/NO1','m/Davy Zhang/TM','n/Freda Liang/TM/NO1','m/Don Tang/TM','m/Ivy Wu/TM','m/Jerry Jiang/TM','','','m/David Chen/TM/CC/4','n/Susie Liu/TM/OTHER/CC/1','m/Aron Jia/TM/CC/10',NULL,'m/Bright Shen/TM','n/Andrew Luo/TM/NO1','m/Lulu Sheng/CC, CL',NULL,'',0),('2013-7-25','m/Wenbin Liang/CC, CL','Business Trip','m/Lulu Sheng/CC, CL','m/Carol Shi/TM','m/James Ma/TM','n/Freda Liang/TM/NO1','m/Ivy Wu/TM','n/Cuckoo Gu/CC, CL/ALU','m/Stone Xin/TM','','','n/Angelia/TM/NO1/CC/1','m/Shining Wang/TM/CC/6','m/Bright Shen/TM/CC/5','n/Aron Jia/TM/OTHER/CC/9','m/Sherry Fu/TM','m/Lila Duanmu/TM','m/Don Tang/TM','n/Yoyo Yao/TM/ALU','',0),('2013-7-18','m/Wenbin Liang/CC, CL','Mentor','m/Ivy Wu/TM','m/Jerry Jiang/TM','m/Shining Wang/TM','m/Lulu Sheng/CC, CL','n/Cuckoo.G/CL/ALU','n/Kobe Zhao/TM/OTHER','m/Shining Wang/TM','','','m/David Chen/TM/CC/2','n/Aron Jia/TM/OTHER/CC/7','m/Bright Shen/TM/CC/5',NULL,'m/Lila Duanmu/TM','m/Carol Shi/TM','m/Wenbin Liang/CC, CL',NULL,'',0),('2013-7-11','m/Wenbin Liang/CC, CL','The Office Conflicts','m/Lila Duanmu/TM','m/Jeff Sun/TM','m/Shining Wang/TM','m/Jerry Jiang/TM','m/James Ma/TM','n/William Zhao/CC, CL/ALU',NULL,'','','m/Wenbin Liang/CC, CL/CC/3','n/Aron Jia/TM/OTHER/CC/5','m/Ivy Wu/TM/CC/5',NULL,'n/Jane Lee/TM/OTHER','m/Bright Shen/TM','m/Carol Shi/TM',NULL,'',0),('2013-6-20','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-6-27','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-6-6','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-5-30','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-6-13','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-7-4','m/Wenbin Liang/CC, CL',NULL,NULL,'m/James Ma/TM',NULL,NULL,'m/Jerry Jiang/TM',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-3-25','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-3-18','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-5-9','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,'m/James Ma/TM',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-5-16','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,'m/James Ma/TM',NULL,NULL,'','',NULL,'m/Ivy Wu/TM/CC/3',NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-5-23','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-1-24','m/Wenbin Liang/CC, CL',NULL,'m/Sherry Fu/TM','m/Brand Lin/TM','m/Jerry Jiang/TM','m/Kobe Zhao/TM','m/Cathy Wen/TM','m/Sharon Yong/ACB, CL',NULL,'','',NULL,'m/Shining Wang/TM/CC/4',NULL,NULL,NULL,'m/James Ma/TM',NULL,NULL,'',0),('2013-1-17','m/Wenbin Liang/CC, CL','Advantage','m/Shining Wang/TM','m/Vera Pang/TM','m/Bright Shen/TM','m/Jerry Jiang/TM','m/Ivy Wu/TM',NULL,NULL,'','','m/Sherry Fu/TM/CC/1',NULL,NULL,NULL,'m/Bill Shen/CC',NULL,NULL,NULL,'',0),('2013-1-31','m/Wenbin Liang/CC, CL','Positive Energy','m/Steven Zhao/TM','m/Ivy Wu/TM','m/Stone Xin/TM','m/Bill Shen/CC','m/Sherry Fu/TM','m/Sharon Yong/ACB, CL',NULL,'','','m/Shining Wang/TM/CC/5','m/Jerry Jiang/TM/CC/8','m/James Ma/TM/CC/5',NULL,'m/Kobe Zhao/TM',NULL,'m/Wenbin Liang/CC, CL',NULL,'',0),('2013-2-21','m/Wenbin Liang/CC, CL','Spring Festival in Your Hometown','m/Ivy Wu/TM','m/James Ma/TM','m/Kobe Zhao/TM','n/Jenny Li/TM/NO1','m/Shining Wang/TM','n/Katie Zhang/ACB, CL/NO1',NULL,'','','m/Don Tang/TM/CC/6','m/Sophie Zhou/TM/CC/1','m/Bright Shen/TM/CC/1',NULL,'m/Sharon Yong/ACB, CL','m/Jerry Jiang/TM','m/Stone Xin/TM',NULL,'',0),('2013-2-28','m/Wenbin Liang/CC, CL','Colours of Life ','m/Jerry Jiang/TM','m/Walle Xia/TM','m/Ivy Wu/TM','n/Victor/TM/NO1','m/Sharon Yong/ACB, CL','m/Maple Huang/TM',NULL,'','','m/Vera Pang/TM/CC/1',NULL,'m/Brand Lin/TM/CC/1',NULL,'m/James Ma/TM',NULL,'m/Bill Shen/CC',NULL,'',0),('2013-3-28','m/Wenbin Liang/CC, CL','We have seen the films in those years',NULL,NULL,NULL,'m/Jerry Jiang/TM','m/Bright Shen/TM',NULL,NULL,'','',NULL,NULL,NULL,NULL,'m/Ivy Wu/TM',NULL,NULL,NULL,'',0),('2013-3-21','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-3-14','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,'m/Bill Shen/CC',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-3-7','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-4-11','m/Wenbin Liang/CC, CL',NULL,'m/Maple Huang/TM','m/Walle Xia/TM',NULL,'m/Sherry Fu/TM','m/Wenbin Liang/CC, CL',NULL,NULL,'','','m/Ivy Wu/TM/CC/3',NULL,'m/James Ma/TM/CC/5',NULL,NULL,'m/Sharon Yong/ACB, CL',NULL,NULL,'',0),('2013-4-4','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-4-18','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,'m/Jerry Jiang/TM',NULL,'','',NULL,NULL,NULL,NULL,NULL,'m/James Ma/TM',NULL,NULL,'',0),('2013-5-2','m/Wenbin Liang/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2013-4-25','m/Wenbin Liang/CC, CL',NULL,'m/Bright Shen/TM','m/Sophie Zhou/TM',NULL,'m/Ivy Wu/TM','m/James Ma/TM',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-1-22','m/57/Lila Duanmu/ACB','','m/58/Lulu Sheng/ACB, CL/CL/1','n/63/AA/TM/CL/1/0','n/54/Ma kai/TM/CL/1/0','n/66/Lewis Jing/TM/CL/1/0','m/57/Lila Duanmu/ACB/CL/10','n/38/Ben Wang/TM/CL/1/2',NULL,'','','n/47/Ellan /TM/CC/1/0','m/82/Arthur Chen/TM/CC/9','m/66/Marrie Ma/TM/CC/10',NULL,'m/71/July Gao/TM/CL/1','m/22/Don Tang/CC/CL/1','m/59/Carol Shi/TM/CL/1',NULL,'n/66/Lewis Jing/TM/0',0),('2015-1-29','m/57/Lila Duanmu/ACB',NULL,NULL,'m/95/Linda Jin/TM/CL/1','m/16/Aron Jia/CC/CL/10','n/52/Alex Wang/CC/CL/1/6','n/64/AA/TM/CL/1/0','m/57/Lila Duanmu/ACB/CL/10','m/88/Amy Wang/TM','','','m/97/Ellan Zhang/TM/CC/2','n/65/Sharon Yong/ACS, CL/ACS/2/null','n/67/Lewis Jing/TM/CC/1/0',NULL,'n/69/Emma Wen/CC/CL/4/2','m/58/Lulu Sheng/ACB, CL/CL/1','n/38/Fransisca/TM/CL/1/2',NULL,'m/58/Lulu Sheng/ACB, CL',0),('2015-2-5','m/57/Lila Duanmu/ACB','Manage Down Series-How to nurture your subordinates','m/94/AA Wang/TM/CL/1','n/38/Aileen Jiang/TM/CL/1/1','n/70/Sherry Xue/CC/CL/1/3','m/58/Lulu Sheng/ACB, CL/CL/1','m/96/Kai Ma/TM/CL/1','n/71/Alice Wei/TM/CL/1/3','m/16/Aron Jia/CC','','','m/98/Lewis Jing/TM/CC/2','m/4/Jerry Jiang/TM/CC/10','m/71/July Gao/TM/CC/7',NULL,'n/52/Alex/CC/CL/5/6','m/22/Don Tang/CC/CL/1','m/57/Lila Duanmu/ACB/CL/1',NULL,'m/58/Lulu Sheng/ACB, CL',0),('2015-2-12','m/57/Lila Duanmu/ACB','Great HROP in the Horse Year',NULL,'n/54/Alieen Jiang/TM/CL/1/0','m/64/Daisy Du/TM/CL/4','n/73/Echo Wang/TM/CL/1/0','m/57/Lila Duanmu/ACB/CL/10','n/72/Fransisico/TM/CL/1/2','m/4/Jerry Jiang/TM','','','m/96/Kai Ma/TM/CC/2','m/82/Arthur Chen/TM/CC/10','m/16/Aron Jia/CC/ACB/4',NULL,'m/59/Carol Shi/TM/CL/1','m/66/Marrie Ma/TM/CL/1','m/98/Lewis Jing/TM/CL/1',NULL,'m/57/Lila Duanmu/ACB',0),('2015-2-19','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-2-26','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-3-5','m/57/Lila Duanmu/ACB','Lantern\'s Day','m/57/Lila Duanmu/ACB/CL/10','n/76/Steven Zhao/TM/CL/1/7','n/72/Echo Wang/TM/CL/1/0','n/75/James /TM/CL/1/0','m/66/Marrie Ma/TM/CL/1','n/47/Alisa Che/TM/CL/1/2','n/54/Alice Wei/TM/3','','','m/94/AA Wang/TM/CC/1','n/74/lenong  an/TM/CC/1/7','m/97/Ellan Zhang/TM/CC/3',NULL,'m/96/Kai Ma/TM/CL/2','n/67/Ben Wang/TM/CL/1/2','m/71/July Gao/TM/CL/1',NULL,'',0),('2015-3-12','m/57/Lila Duanmu/ACB','Tree Planting Day','m/71/July Gao/TM/CL/1','n/49/Ellie Tian/TM/CL/1/1','m/97/Ellan Zhang/TM/CL/2','m/58/Lulu Sheng/ACB, CL/CL/1','n/47/Hui Wang/TM/CL/1/1','m/98/Lewis Jing/TM/CL/1','m/96/Kai Ma/TM','','','n/62/Echo Wang/TM/CC/1/1','m/88/Amy Wang/TM/CC/4','m/14/James Ma/TM/CC/6',NULL,'m/64/Daisy Du/TM/CL/1','m/59/Carol Shi/TM/CL/1','m/66/Marrie Ma/TM/CL/1',NULL,'m/98/Lewis Jing/TM',0),('1969-12-31','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-4-9','m/57/Lila Duanmu/ACB','','m/57/Lila Duanmu/ACB/CL/10','m/99/Ellie Tian/TM/CL/1','m/82/Arthur Chen/TM/CL/1','m/98/Lewis Jing/TM/CL/1','m/4/Jerry Jiang/CC, CL/CL/1',NULL,NULL,'','','m/14/James Ma/TM/CC/6','m/88/Amy Wang/TM/CC/4','m/71/July Gao/TM/CC/8',NULL,'m/53/Sophie Zhou/TM/CL/1','n/77/Fransisico/TM/CL/1/2',NULL,NULL,'m/98/Lewis Jing/TM',0),('2015-3-19','m/57/Lila Duanmu/ACB','','m/96/Kai Ma/TM/CL/1',NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,'m/98/Lewis Jing/TM/CC/3','m/97/Ellan Zhang/TM/CC/4',NULL,'m/71/July Gao/TM/CL/1',NULL,'m/4/Jerry Jiang/CC, CL/CL/1',NULL,'',0),('2015-5-21','m/57/Lila Duanmu/ACB','How to build mentor system','m/64/Daisy Du/TM/CL/1','m/104/Jin Huang/TM/CL/1','m/16/Aron Jia/CC/CL/10','n/54/Kathleen/TM/CL/1/8','m/57/Lila Duanmu/ACB/CL/10','m/71/July Gao/TM/CL/1','m/99/Ellie Tian/TM','','','m/103/Aileen Jiang/TM/CC/2','m/97/Ellan Zhang/TM/CC/5','m/58/Lulu Sheng/ACB, CL/ACS/7',NULL,'n/67/Ben Wang/TM/CL/1/2','m/96/Kai Ma/TM/CL/4','m/22/Don Tang/CC/CL/1',NULL,'n/63/Kathleen Zhu/TM/8',0),('2015-4-2','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-5-14','m/57/Lila Duanmu/ACB','How to organize staff training','m/79/Sharp Feng/TM/CL/1','m/99/Ellie Tian/TM/CL/1','m/101/Echo Wang/TM/CL/1','m/58/Lulu Sheng/ACB, CL/CL/10','m/97/Ellan Zhang/TM/CL/1','n/81/Jenny Yang/TM/CL/1/6',NULL,'','','m/96/Kai Ma/TM/CC/4','m/88/Amy Wang/TM/CC/5','m/71/July Gao/TM/CC/1',NULL,'m/82/Arthur Chen/TM/CL/10','m/14/James Ma/TM/CL/5','m/22/Don Tang/CC/CL/1',NULL,'m/58/Lulu Sheng/ACB, CL',0),('2015-4-16','m/57/Lila Duanmu/ACB','','m/64/Daisy Du/TM/CL/1','n/57/Audrey/TM/CL/1/6','n/47/Aileen Jiang/TM/CL/1/1',NULL,'m/97/Ellan Zhang/TM/CL/2','m/58/Lulu Sheng/ACB, CL/CL/1',NULL,'','','m/71/July Gao/TM/CC/9','m/99/Ellie Tian/TM/CC/1','m/14/James Ma/TM/CC/7',NULL,'m/96/Kai Ma/TM/CL/1','n/49/Jenny Yang/TM/CL/1/6','m/82/Arthur Chen/TM/CL/1',NULL,'m/58/Lulu Sheng/ACB, CL',0),('2015-4-23','m/57/Lila Duanmu/ACB','','m/58/Lulu Sheng/ACB, CL/ALB/1',NULL,'m/99/Ellie Tian/TM/CL/1','n/79/Fransisco Chandra/TM/CL/1/2','m/96/Kai Ma/TM/CL/1','n/78/Ben Wang/TM/CL/1/2',NULL,'','','m/14/James Ma/TM/CC/8','m/102/Wendy Wang/TM/CC/1','n/39/Sharon Yong/ACB/CC/1/2',NULL,'m/4/Jerry Jiang/CC, CL/CL/1','m/82/Arthur Chen/TM/CL/1','m/57/Lila Duanmu/ACB/ALB/1',NULL,'',0),('2015-4-30','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/71/July Gao/TM/CC/10','m/88/Amy Wang/TM/CC/5','m/96/Kai Ma/TM/CC/4',NULL,NULL,'m/82/Arthur Chen/TM/CL/4',NULL,NULL,'',0),('2015-3-26','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-5-28','m/57/Lila Duanmu/ACB','International Children\'s Day','m/16/Aron Jia/CC/CL/10','m/100/Jane Zhou/TM/CL/1','m/82/Arthur Chen/TM/CL/1','m/98/Lewis Jing/TM/CL/1','m/58/Lulu Sheng/ACB, CL/CL/1','n/84/Audrey Pan/TM/CL/1/6',NULL,'','','m/79/Sharp Feng/TM/CC/1','m/104/Jin Huang/TM/CC/3','m/99/Ellie Tian/TM/CC/3',NULL,'n/64/Jenny Yang/TM/CL/1/6','m/14/James Ma/TM/CL/1','m/96/Kai Ma/TM/CL/1',NULL,'m/98/Lewis Jing/TM',0),('2015-5-7','m/57/Lila Duanmu/ACB','When recruiting ends','m/57/Lila Duanmu/ACB/CL/10',NULL,'m/53/Sophie Zhou/TM/CL/1','n/80/Elaine Tang/TM/CL/1/6','n/49/Alieen Jiang/TM/CL/1/0','m/71/July Gao/TM/CL/1',NULL,'','','m/99/Ellie Tian/TM/CC/2','n/67/Meiling/TM/CC/5/6','m/58/Lulu Sheng/ACB, CL/ACS/7',NULL,'m/88/Amy Wang/TM/CL/1','n/62/Audrey Pan/TM/CL/1/6','m/66/Marrie Ma/TM/CL/10',NULL,'m/57/Lila Duanmu/ACB',0),('2015-6-4','m/57/Lila Duanmu/ACB','how to make training effective','m/99/Ellie Tian/TM/CL/1','m/98/Lewis Jing/TM/CL/4','m/104/Jin Huang/TM/CL/1','n/86/Kathleen Zhu/TM/CL/1/8','m/4/Jerry Jiang/CC, CL/CL/1','m/71/July Gao/TM/CL/1','m/66/Marrie Ma/TM','','','m/14/James Ma/TM/CC/2','m/101/Echo Wang/TM/CC/2','n/87/Meiling/TM/CC/10/6',NULL,'m/22/Don Tang/CC/CL/1','n/88/Audrey Pan/TM/CL/1/6','m/58/Lulu Sheng/ACB, CL/CL/1',NULL,'m/22/Don Tang/CC',0),('2015-6-11','m/57/Lila Duanmu/ACB','How to build a team?','m/97/Ellan Zhang/TM/CL/1','n/89/Michael Qian/CL/CL/4/0','m/99/Ellie Tian/TM/CL/1','n/90/Alex Han/TM/CL/5/6','m/104/Jin Huang/TM/CL/1','m/96/Kai Ma/TM/CL/4','m/14/James Ma/TM','','','m/102/Wendy Wang/TM/CC/1','n/85/Nickey/CC/CC/1/0','m/58/Lulu Sheng/ACB, CL/ACS/8',NULL,'m/98/Lewis Jing/TM/CL/8','m/71/July Gao/TM/CL/1','m/66/Marrie Ma/TM/CL/10',NULL,'m/57/Lila Duanmu/ACB',0),('2015-6-18','m/57/Lila Duanmu/ACB',NULL,NULL,NULL,NULL,NULL,'m/57/Lila Duanmu/ACB/CL/10',NULL,NULL,'','','m/100/Jane Zhou/TM/CC/3','n/88/Audrey/TM/CC/3/6','m/58/Lulu Sheng/ACB, CL/ACS/9',NULL,NULL,NULL,NULL,NULL,'',0),('2015-6-25','m/57/Lila Duanmu/ACB','how to manage risks','m/99/Ellie Tian/TM/CL/1','n/62/Summer/TM/CL/1/1','n/92/Anna /TM/CL/1/0','m/82/Arthur Chen/TM/CL/5','m/4/Jerry Jiang/CC, CL/CL/1','m/98/Lewis Jing/TM/CL/3',NULL,'','','n/91/Ben Wang/CC/CC/7/2','m/102/Wendy Wang/TM/CC/2','m/58/Lulu Sheng/ACB, CL/ACS/10',NULL,'m/71/July Gao/TM/CL/1','m/97/Ellan Zhang/TM/CL/1','m/57/Lila Duanmu/ACB/CL/1',NULL,'m/16/Aron Jia/CC',0),('2015-7-2','m/71/July Gao/CC,CL','','m/82/Arthur Chen/TM/CL/1','m/98/Lewis Jing/TM/CL/4','n/95/Victoria xu/TM/CL/1/2','n/94/Alex han/TM/CL/1/6','m/97/Ellan Zhang/TM/CL/1','n/49/Laughing/TM/CL/1/6','m/99/Ellie Tian/TM','','','m/101/Echo Wang/TM/CC/3','n/88/Audrey/TM/CC/3/6','m/71/July Gao/CC, CL/CC/10',NULL,'n/62/Edison Shao/TM/CL/1/2','n/93/jenny yang/TM/CL/6/6','m/57/Lila Duanmu/ACB/CL/10',NULL,'m/58/Lulu Sheng/ACB, CL',0),('2015-7-9','m/71/July Gao/CC,CL','','m/64/Daisy Du/TM/CL/1','m/106/Anna Fang/TM/CL/1','m/103/Aileen Jiang/TM/CL/1','m/99/Ellie Tian/TM/CL/1','m/108/Nickey Hu/TM/CL/1','n/96/Sharon/TM/CL/1/2',NULL,'','','m/109/Victoria Xu/TM/CC/1','m/59/Carol Shi/TM/CC/6','m/98/Lewis Jing/TM/CC/4',NULL,'m/97/Ellan Zhang/TM/CL/4','m/22/Don Tang/CC/CL/1','m/4/Jerry Jiang/CC, CL/CL/1',NULL,'m/82/Arthur Chen/CC',0),('2015-7-16','m/71/July Gao/CC,CL',NULL,NULL,NULL,'n/97/Meiling/CC, CL/CL/1/3',NULL,'m/98/Lewis Jing/TM/CL/5',NULL,NULL,'','','m/106/Anna Fang/TM/CC/1','m/99/Ellie Tian/TM/CC/4','m/97/Ellan Zhang/TM/CC/6',NULL,NULL,NULL,NULL,NULL,'m/57/Lila Duanmu/ACB, CL',0),('2015-7-23','m/71/July Gao/CC,CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,'m/103/Aileen Jiang/TM/CC/3','m/98/Lewis Jing/TM/CC/5',NULL,NULL,NULL,NULL,NULL,'',0),('2015-7-30','m/71/July Gao/CC,CL',NULL,NULL,NULL,NULL,NULL,NULL,'m/98/Lewis Jing/TM/CL/5',NULL,'','',NULL,'m/88/Amy Wang/TM/CC/5','m/71/July Gao/CC, CL/ACB/1',NULL,NULL,NULL,NULL,NULL,'',0),('2015-8-6','m/71/July Gao/CC,CL',NULL,NULL,NULL,NULL,NULL,'m/98/Lewis Jing/TM/CL/7',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-9-3','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-24','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-10','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,'m/4/Jerry Jiang/CC, CL/CL/1',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-8-27','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-17','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','m/4/Jerry Jiang/CC, CL/CC/1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-31','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-3','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2016-1-7','m/1/Stone Xin/TM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2016-1-14','m/1/Stone Xin/TM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2016-1-21','m/1/Stone Xin/TM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-16','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-23','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2015-12-30','m/71/July Gao/CC, CL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2016-1-6','m/1/Stone Xin/TM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2016-1-28','m/1/Stone Xin/TM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-10-12','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-10-19','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-10-26','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0),('2017-11-2','NULL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0);

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

insert  into `officers`(`ValidDate`,`ExpireDate`,`President`,`VPE`,`VPM`,`VPPR`,`SAA`,`Treasurer`,`Secretary`) values ('2015-01-01','2015-06-30',57,58,66,71,4,59,63),('2014-07-01','2014-12-31',57,58,66,71,4,59,63),('2013-01-01','2014-06-30',51,57,4,46,1,22,38),('2015-07-01','2015-12-31',71,98,99,82,107,4,106),('2016-01-01','2016-06-30',1,2,3,4,5,6,7),('2016-07-01','2016-12-31',4,4,4,4,4,4,4),('0000-00-00','0000-00-00',4,4,4,4,4,4,4),('2015-07-03','0000-00-00',4,4,4,4,4,4,4);

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
  `RoleKey` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `RoleName` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `CCorCLOld` enum('CC','NOCCCL') COLLATE utf8_bin NOT NULL,
  `CCorCLMar6` enum('CC','CL','NOCCCL') COLLATE utf8_bin NOT NULL,
  `AllowDuplicated` tinyint(4) DEFAULT NULL,
  `ShowOrder` int(11) DEFAULT NULL,
  `IsShown` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Number`)
) ENGINE=MyISAM AUTO_INCREMENT=224 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `rolenames` */

insert  into `rolenames`(`Number`,`RoleKey`,`RoleName`,`CCorCLOld`,`CCorCLMar6`,`AllowDuplicated`,`ShowOrder`,`IsShown`) values (3,'tme','Toastmaster of The Evening','NOCCCL','CL',0,3,1),(4,'timer','Timer','NOCCCL','CL',0,4,1),(5,'ahcounter','Ah Counter','NOCCCL','CL',0,5,1),(6,'grammarian','Grammarian','NOCCCL','CL',0,6,1),(7,'ttm','Table Topic Master','NOCCCL','CL',0,7,1),(8,'ge','General Evaluator','NOCCCL','CL',0,8,1),(9,'saa','SAA','NOCCCL','NOCCCL',1,9,1),(10,'joke','Joke Master','NOCCCL','NOCCCL',0,10,1),(11,'motivator','Motivation Quote','NOCCCL','NOCCCL',1,11,1),(12,'speaker1','Speaker 1','CC','CC',0,12,1),(13,'speaker2','Speaker 2','CC','CC',0,13,1),(14,'speaker3','Speaker 3','CC','CC',0,14,1),(15,'speaker4','Speaker 4','CC','CC',0,15,0),(16,'evaluator1','Evaluator 1','NOCCCL','CL',0,16,1),(17,'evaluator2','Evaluator 2','NOCCCL','CL',0,17,1),(18,'evaluator3','Evaluator 3','NOCCCL','CL',0,18,1),(19,'evaluator4','Evaluator 4','NOCCCL','CL',0,19,0),(20,'workshop','Mini Workshop','NOCCCL','NOCCCL',1,20,1),(2,'theme','Theme','NOCCCL','NOCCCL',1,2,1),(1,'president','President','NOCCCL','NOCCCL',1,1,1);

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `tmcclubs` */

insert  into `tmcclubs`(`tmcclubindex`,`tmcclubname`,`tmcclubshortname`,`valid`,`validdate`,`expiredate`,`tmcclubdescription`) values (0,'Another TMC...','Other',1,'2013-01-01',NULL,NULL),(1,'Invited Guest','GUEST',1,'2013-01-01',NULL,NULL),(2,'Nanjing NO.1 TMC','NO1',1,'2013-01-01',NULL,NULL),(3,'Nanjing Smart Speaker TMC','SSTMC',1,'2013-01-01',NULL,NULL),(4,'Nanjing Ericsson TMC','Ericsson',0,'2013-01-01','2013-12-31',NULL),(5,'Nanjing NO.1 Mandarin TMC','Mandarin',1,'2013-01-01',NULL,NULL),(6,'Nanjing ET TMC','ET',1,'2013-01-01',NULL,NULL),(7,'Nanjing DNA TMC','DNA',1,'2013-01-01',NULL,NULL),(8,'Nanjing Ford TMC','Ford',1,'2013-01-01',NULL,NULL);

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
