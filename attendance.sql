
CREATE DATABASE /*!32312 IF NOT EXISTS*/`attendance` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `attendance`;

DROP TABLE IF EXISTS `tbl_admin`;

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user_name` varchar(100) NOT NULL,
  `admin_password` varchar(150) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


insert  into `tbl_admin`(`admin_id`,`admin_user_name`,`admin_password`) values 
(1,'admin','$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u');


DROP TABLE IF EXISTS `tbl_attendance`;

CREATE TABLE `tbl_attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `dupmember_id` int(11) NOT NULL,
  `attendance_status` enum('Present','Absent') NOT NULL,
  `attendance_date` date NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`attendance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


insert  into `tbl_attendance`(`attendance_id`,`dupmember_id`,`attendance_status`,`attendance_date`,`member_id`) values 
(1,6,'Present','2022-08-01',6),
(2,6,'Present','2022-08-02',6),
(3,6,'Absent','2022-08-03',6),
(4,6,'Present','2022-08-04',6),
(5,6,'Present','2022-08-05',6);


DROP TABLE IF EXISTS `tbl_attendancepriv`;

CREATE TABLE `tbl_attendancepriv` (
  `attendancepriv_id` int(11) NOT NULL AUTO_INCREMENT,
  `dupmemberpriv_id` int(11) NOT NULL,
  `attendancepriv_status` enum('Present','Absent') NOT NULL,
  `attendancepriv_date` date NOT NULL,
  `memberpriv_id` int(11) NOT NULL,
  PRIMARY KEY (`attendancepriv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


insert  into `tbl_attendancepriv`(`attendancepriv_id`,`dupmemberpriv_id`,`attendancepriv_status`,`attendancepriv_date`,`memberpriv_id`) values 
(1,4,'Present','2022-08-01',4),
(2,4,'Present','2022-08-02',4),
(3,4,'Absent','2022-08-03',4),
(4,4,'Present','2022-08-04',4),
(5,4,'Present','2022-08-05',4);


DROP TABLE IF EXISTS `tbl_svs`;

CREATE TABLE `tbl_svs` (
  `svs_id` int(11) NOT NULL AUTO_INCREMENT,
  `svs_name` char(20) NOT NULL,
  PRIMARY KEY (`svs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


insert  into `tbl_svs`(`svs_id`,`svs_name`) values 
(1,'SVSA-001'),
(2,'SVSA-002'),
(3,'SVST-001'),
(4,'SVSX-001'),
(5,'SVSL-001'),
(6,'SVSG-001');

DROP TABLE IF EXISTS `tbl_dupmember`;

CREATE TABLE `tbl_dupmember` (
  `dupmember_id` int(11) NOT NULL AUTO_INCREMENT,
  `dupmember_name` varchar(150) NOT NULL,
  `dupmember_dob` date NOT NULL,
  `dupmember_svs_id` int(11) NOT NULL,
  PRIMARY KEY (`dupmember_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


insert  into `tbl_dupmember`(`dupmember_id`,`dupmember_name`,`dupmember_dob`,`dupmember_svs_id`) values 
(1,'Felix Roque','2021-03-04',6);


DROP TABLE IF EXISTS `tbl_member`;

CREATE TABLE `tbl_member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_name` varchar(150) NOT NULL,
  `member_emailid` varchar(100) NOT NULL,
  `member_password` varchar(100) NOT NULL,
  `member_doj` date NOT NULL,
  `member_image` varchar(100) NOT NULL,
  `member_svs_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

insert  into `tbl_member`(`member_id`,`member_name`,`member_emailid`,`member_password`,`member_doj`,`member_image`,`member_svs_id`) values 
(1,'Felix Roque','felixroque@gmail.com','$2y$10$s2MmR/Ml6ohRRrrFY0SRQ.vWohGvthVsKe59zgLOIvm3Qd0PzavD2','2019-05-01','63047b9b24fe4.jpg',6);


DROP TABLE IF EXISTS `tbl_dupmember_priv`;

CREATE TABLE `tbl_dupmember_priv` (
  `dupmemberpriv_id` int(11) NOT NULL AUTO_INCREMENT,
  `dupmemberpriv_name` varchar(150) NOT NULL,
  `dupmemberpriv_dob` date NOT NULL,
  `dupmemberpriv_svs_id` int(11) NOT NULL,
  PRIMARY KEY (`dupmemberpriv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


insert  into `tbl_dupmember_priv`(`dupmemberpriv_id`,`dupmemberpriv_name`,`dupmemberpriv_dob`,`dupmemberpriv_svs_id`) values 
(1,'Hyunjin Abunjing','2021-03-04',4);


DROP TABLE IF EXISTS `tbl_memberpriv`;

CREATE TABLE `tbl_memberpriv` (
  `memberpriv_id` int(11) NOT NULL AUTO_INCREMENT,
  `memberpriv_name` varchar(150) NOT NULL,
  `memberpriv_emailid` varchar(100) NOT NULL,
  `memberpriv_password` varchar(100) NOT NULL,
  `memberpriv_doj` date NOT NULL,
  `memberpriv_image` varchar(100) NOT NULL,
  `memberpriv_svs_id` int(11) NOT NULL,
  PRIMARY KEY (`memberpriv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

insert  into `tbl_memberpriv`(`memberpriv_id`,`memberpriv_name`,`memberpriv_emailid`,`memberpriv_password`,`memberpriv_doj`,`memberpriv_image`,`memberpriv_svs_id`) values 
(1,'Hyunjin Abunjing','hyunbunjing@gmail.com','$2y$10$s2MmR/Ml6ohRRrrFY0SRQ.vWohGvthVsKe59zgLOIvm3Qd0PzavD2','2019-05-01','63047b9b24fe4.jpg',4);
