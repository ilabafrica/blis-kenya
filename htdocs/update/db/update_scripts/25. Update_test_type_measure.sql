/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

RENAME TABLE `test_type_measure` TO `tmp_test_type_measure`;

CREATE TABLE `test_type_measure` (
  `ttm_id` int(11) NOT NULL AUTO_INCREMENT,
  `test_type_id` int(11) unsigned NOT NULL DEFAULT '0',
  `measure_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nesting` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ttm_id`),
  KEY `test_type_id` (`test_type_id`),
  KEY `measure_id` (`measure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=280 DEFAULT CHARSET=latin1;

INSERT IGNORE INTO `test_type_measure` (`test_type_id`, `measure_id`, `ts`)
SELECT `test_type_id`, `measure_id`, `ts` FROM `tmp_test_type_measure` GROUP BY `test_type_id`, `measure_id`;

DROP TABLE `tmp_test_type_measure`;

/*!40101 SET character_set_client = @saved_cs_client */;


