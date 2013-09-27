
CREATE TABLE `quality_controls` (
  `qc_id` int(11) NOT NULL AUTO_INCREMENT,
  `qcc_id` int(11) NOT NULL,
  `irl_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL,
  `ts` datetime NOT NULL,
  PRIMARY KEY (`qc_id`),
  KEY `qc_1` (`qcc_id`),
  KEY `qc_2` (`created_by`)
) ;


CREATE TABLE `quality_control_category` (
  `qcc_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qcc_id`),
  KEY `qcc_user_fk` (`created_by`)
);


CREATE TABLE `quality_control_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `qc_id` int(11) NOT NULL,
  `field_name` varchar(200) NOT NULL,
  `field_type` varchar(25) NOT NULL,
  `field_size` int(11) NOT NULL,
  `required` int(11) NOT NULL,
  `options` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL,
  `ts` datetime NOT NULL,
  PRIMARY KEY (`field_id`),
  KEY `qcf_1` (`qc_id`),
  KEY `qcf_3` (`created_by`)
);


CREATE TABLE `quality_control_field_results` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `response_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(250) NOT NULL,
  `ts` datetime NOT NULL,
  PRIMARY KEY (`result_id`),
  KEY `qcfr_1` (`response_id`),
  KEY `qcfr_2` (`field_id`)
);


CREATE TABLE `quality_control_field_responses` (
  `response_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) NOT NULL,
  `qc_id` int(11) NOT NULL,
  `ts` datetime NOT NULL,
  PRIMARY KEY (`response_id`),
  KEY `qcfrs_1` (`created_by`),
  KEY `qcfrs_2` (`qc_id`)
);








