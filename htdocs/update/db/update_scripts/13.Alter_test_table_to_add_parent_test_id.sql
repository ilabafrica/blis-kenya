ALTER TABLE `test_type` ADD COLUMN `parent_test_type_id` INT(10) NULL DEFAULT 0  AFTER `test_type_id` , CHANGE COLUMN `target_tat` `target_tat` INT(3) UNSIGNED NULL DEFAULT NULL  ;
