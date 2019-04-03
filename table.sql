CREATE TABLE `zip` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`zip` INT(4) NOT NULL,
	`city` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`district` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;