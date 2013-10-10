ALTER TABLE `external_lab_request` CHANGE COLUMN `test_completed` `test_status` INT NULL DEFAULT b'0'  ;
ALTER TABLE `blis_127`.`specimen` ADD COLUMN `external_lab_no` VARCHAR(45) NULL  AFTER `daily_num` ;

