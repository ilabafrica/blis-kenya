ALTER TABLE `test` ADD COLUMN `ts_started` DATETIME NULL  AFTER `result` , CHANGE COLUMN `date_result_entered` `ts_result_entered` DATETIME NULL DEFAULT NULL  ;
