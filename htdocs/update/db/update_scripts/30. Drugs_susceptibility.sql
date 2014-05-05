
--
-- Table structure for table `drugs`
--

CREATE TABLE IF NOT EXISTS `drugs` (
  `drug_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `disabled` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`drug_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


--
-- Table structure for table `drug_susceptibility`
--

CREATE TABLE IF NOT EXISTS `drug_susceptibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `testId` int(11) NOT NULL,
  `drugId` int(11) NOT NULL,
  `zone` int(11) NOT NULL,
  `interpretation` varchar(1) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_test`
--

CREATE TABLE IF NOT EXISTS `drug_test` (
  `test_type_id` int(11) unsigned NOT NULL DEFAULT '0',
  `drug_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `test_type_id` (`test_type_id`),
  KEY `drug_id` (`drug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

