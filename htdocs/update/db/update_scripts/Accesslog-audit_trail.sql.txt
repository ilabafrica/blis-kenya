delimiter $$

CREATE TABLE `access_log` (
  `idaccess_log` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `access_log_ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `access_type` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_name` varchar(45) DEFAULT NULL,
  `access_sessionid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idaccess_log`),
  KEY `access_type_idx` (`access_type`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1$$


delimiter $$

CREATE TABLE `access_type` (
  `idaccess_type` int(11) NOT NULL AUTO_INCREMENT,
  `access_typename` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idaccess_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1$$


delimiter $$

CREATE TABLE `audit_trail` (
  `audit_trail_id` int(11) NOT NULL AUTO_INCREMENT,
  `at_userid` int(11) DEFAULT NULL,
  `at_operationtype` varchar(45) DEFAULT NULL,
  `at_ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `at_dbname` varchar(45) DEFAULT NULL,
  `at_tablename` varchar(45) DEFAULT NULL,
  `at_objectid` int(11) DEFAULT NULL,
  `at_fieldname` varchar(45) DEFAULT NULL,
  `at_oldvalue` varchar(200) DEFAULT NULL,
  `at_newvalue` varchar(200) DEFAULT NULL,
  `at_sessionid` varchar(100) DEFAULT NULL,
  `at_descript` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`audit_trail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1$$


