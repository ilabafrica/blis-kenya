/*
 * Changed column name range to measure_range because range is a MySQL reserved word
 * http://dev.mysql.com/doc/mysqld-version-reference/en/mysqld-version-reference-reservedwords-5-5.html
 */
ALTER TABLE `measure` CHANGE COLUMN `range` `measure_range` VARCHAR(500) NULL DEFAULT NULL  ;
