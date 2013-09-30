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
ALTER TABLE `blis_revamp`.`sanitas_lab_request` RENAME TO  `blis_revamp`.`external_lab_request` ;
ALTER TABLE `blis_revamp`.`external_lab_request` ADD COLUMN `age` INT(11) NULL DEFAULT NULL  AFTER `dateOfBirth` , ADD COLUMN `revisitNumber` INT(11) NULL DEFAULT NULL  AFTER `result_returned` , ADD COLUMN `patientContact` VARCHAR(45) NULL DEFAULT NULL  AFTER `revisitNumber` , ADD COLUMN `receiptNumber` VARCHAR(45) NULL DEFAULT NULL  AFTER `patientContact` , ADD COLUMN `waiverNo` VARCHAR(45) NULL DEFAULT NULL  AFTER `receiptNumber` , ADD COLUMN `comments` VARCHAR(200) NULL DEFAULT NULL  AFTER `waiverNo` , ADD COLUMN `provisionalDiagnosis` VARCHAR(45) NULL DEFAULT NULL  AFTER `comments` ;
ALTER TABLE `blis_revamp`.`external_lab_request` ADD COLUMN `system_id` VARCHAR(45) NULL DEFAULT NULL  AFTER `provisionalDiagnosis` ;
