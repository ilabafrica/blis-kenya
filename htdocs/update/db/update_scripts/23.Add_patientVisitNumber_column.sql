ALTER TABLE `external_lab_request` ADD COLUMN `patientVisitNumber` VARCHAR(45) NULL DEFAULT NULL  AFTER `orderStage` ;
ALTER TABLE `test` ADD COLUMN `patientVisitNumber` VARCHAR(45) NULL DEFAULT NULL  AFTER `external_parent_lab_no` ;
