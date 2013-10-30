CREATE TABLE IF NOT EXISTS `rejection_phases` (
  `rejection_phase_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rejection_phase_id`)
);

CREATE TABLE IF NOT EXISTS `rejection_reasons` (
  `rejection_reason_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rejection_phase` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rejection_reason_id`)
);


CREATE TABLE IF NOT EXISTS `rejected_specimen` (
  `specimen_id` int(11) unsigned NOT NULL DEFAULT '0',
  `reason_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `specimen_id` (`specimen_id`),
  KEY `reason_id` (`reason_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;