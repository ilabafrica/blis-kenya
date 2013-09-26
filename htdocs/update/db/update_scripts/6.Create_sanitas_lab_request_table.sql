CREATE  TABLE `blis_revamp`.`sanitas_lab_request` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `labNo` VARCHAR(100) NULL ,
  `parentLabNo` VARCHAR(100) NULL ,
  `requestingClinician` VARCHAR(100) NULL ,
  `investigation` VARCHAR(100) NULL ,
  `requestDate` DATETIME NULL ,
  `patient_id` VARCHAR(100) NULL ,
  `full_name` VARCHAR(100) NULL ,
  `dateOfBirth` DATETIME NULL ,
  `gender` VARCHAR(45) NULL ,
  `address` VARCHAR(45) NULL ,
  `postalCode` VARCHAR(45) NULL ,
  `phoneNumber` VARCHAR(45) NULL ,
  `city` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) );
  
ALTER TABLE `blis_revamp`.`sanitas_lab_request` ADD COLUMN `test_completed` BIT NULL DEFAULT 0  AFTER `city` , ADD COLUMN `result` VARCHAR(45) NULL DEFAULT NULL  AFTER `test_completed` , ADD COLUMN `result_returned` BIT NULL DEFAULT 0  AFTER `result` ;