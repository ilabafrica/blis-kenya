ALTER TABLE `test` ADD COLUMN `status_code_id` INT UNSIGNED NULL DEFAULT 0  AFTER `date_verified` ;
UPDATE test t SET 
t.status_code_id = (SELECT s.status_code_id FROM specimen s WHERE t.specimen_id = s.specimen_id);
ALTER TABLE `test` ADD COLUMN `date_result_entered` DATETIME NULL  AFTER `status_code_id` ;
