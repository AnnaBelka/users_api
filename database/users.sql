CREATE TABLE `u_settings` ( `name` VARCHAR(255) NOT NULL , `value` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;
INSERT INTO `u_settings` (`name`, `value`) VALUES ('api_url', 'users-show');
INSERT INTO `u_settings` (`name`, `value`) VALUES ('AccessToken', 'gmkj^Ynv-chrynnctser55vfynckd4jf_kvj22');
CREATE TABLE `u_users` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`name` VARCHAR(255) NOT NULL ,
`surname` VARCHAR(255) NOT NULL ,
`email` VARCHAR(255) NOT NULL ,
`login` VARCHAR(255) NOT NULL ,
`password` VARCHAR(255) NOT NULL ,
PRIMARY KEY (`id`),
UNIQUE (`email`),
UNIQUE `login` (`login`)
) ENGINE = InnoDB;