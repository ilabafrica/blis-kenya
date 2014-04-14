CREATE  TABLE `abbreviations` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `abbreviation` VARCHAR(45) NULL ,
  `Word` VARCHAR(250) NULL ,
  PRIMARY KEY (`id`))
COMMENT = 'Table that stores abbreviations and their full words';