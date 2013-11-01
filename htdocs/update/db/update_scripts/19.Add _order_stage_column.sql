ALTER TABLE `external_lab_request` ADD COLUMN `orderStage` VARCHAR(45) NULL  AFTER `requestDate` ;
ALTER TABLE `external_lab_request` CHANGE COLUMN `comments` `comments` VARCHAR(100) NULL DEFAULT NULL  AFTER `result` ;

