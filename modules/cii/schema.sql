-- MySQL Script generated by MySQL Workbench
-- Sun Oct  2 22:04:14 2016
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `Cii_Abstract_Extension`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Abstract_Extension` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `installed` DATETIME NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `classname_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_Extension_Core_Class1_idx` (`classname_id` ASC),
  INDEX `installed_idx` (`installed` ASC),
  INDEX `enabled_idx` (`enabled` ASC),
  FULLTEXT INDEX `name_fulltext` (`name` ASC),
  CONSTRAINT `fk_Core_Extension_Core_Class1`
    FOREIGN KEY (`classname_id`)
    REFERENCES `Cii_Classname` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Package`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Package` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `extension_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_Module_Core_Extension1_idx` (`extension_id` ASC),
  CONSTRAINT `fk_Core_Module_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Abstract_Extension` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Classname`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Classname` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(255) NOT NULL,
  `package_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `classname_UNIQUE` (`path` ASC),
  INDEX `fk_Cii_Classname_Cii_Package1_idx` (`package_id` ASC),
  CONSTRAINT `fk_Cii_Classname_Cii_Package1`
    FOREIGN KEY (`package_id`)
    REFERENCES `Cii_Package` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Mandate`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Mandate` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Abstract_Content`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Abstract_Content` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NULL,
  `enabled` TINYINT(1) NOT NULL,
  `show_name` TINYINT(1) NOT NULL,
  `columns_count` INT UNSIGNED NULL,
  `created` DATETIME NOT NULL,
  `classname_id` INT UNSIGNED NOT NULL,
  `mandate_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Cii_Content_Cii_Class1_idx` (`classname_id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `enabled_idx` (`enabled` ASC),
  INDEX `created_idx` (`created` ASC),
  FULLTEXT INDEX `name_fulltext` (`name` ASC),
  INDEX `fk_Cii_Abstract_Content_Cii_Mandate1_idx` (`mandate_id` ASC),
  CONSTRAINT `fk_Cii_Content_Cii_Class1`
    FOREIGN KEY (`classname_id`)
    REFERENCES `Cii_Classname` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_Abstract_Content_Cii_Mandate1`
    FOREIGN KEY (`mandate_id`)
    REFERENCES `Cii_Mandate` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Language`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Language` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `created` DATETIME NOT NULL,
  `code` VARCHAR(6) NOT NULL,
  `shortcode` VARCHAR(2) NULL,
  `date` VARCHAR(12) NULL,
  `time` VARCHAR(8) NULL,
  `datetime` VARCHAR(12) NULL,
  `decimalSeparator` VARCHAR(8) NULL,
  `thousandSeparator` VARCHAR(8) NULL,
  `decimals` SMALLINT(1) NULL,
  `removeZeros` TINYINT(1) NULL,
  `currencySymbol` VARCHAR(8) NULL,
  `currencySymbolPlace` TINYINT(1) NULL,
  `currencySmallestUnit` FLOAT NULL,
  `currencyRemoveZeros` TINYINT(1) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC),
  INDEX `enabled_idx` (`enabled` ASC),
  FULLTEXT INDEX `name_fulltext` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Abstract_Route`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Abstract_Route` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NULL,
  `enabled` TINYINT(1) NOT NULL,
  `created` DATETIME NOT NULL,
  `language_id` INT UNSIGNED NULL,
  `parent_id` INT UNSIGNED NULL,
  `classname_id` INT UNSIGNED NOT NULL,
  `hits` INT NOT NULL DEFAULT 0,
  `mandate_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreSitemap_CoreLanguage1_idx` (`language_id` ASC),
  INDEX `fk_CoreSitemap_CoreSitemap1_idx` (`parent_id` ASC),
  INDEX `fk_Cii_Route_Cii_Class1_idx` (`classname_id` ASC),
  INDEX `slug_idx` (`slug` ASC),
  UNIQUE INDEX `slug_parent_language_unique` (`slug` ASC, `parent_id` ASC, `language_id` ASC),
  INDEX `created_idx` (`created` ASC),
  INDEX `enabled_idx` (`enabled` ASC),
  INDEX `title_idx` (`title` ASC),
  INDEX `hits_idx` (`hits` ASC),
  FULLTEXT INDEX `slug_fulltext` (`slug` ASC),
  INDEX `fk_Cii_Abstract_Route_Cii_Mandate1_idx` (`mandate_id` ASC),
  CONSTRAINT `fk_CoreSitemap_CoreLanguage1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CoreSitemap_CoreSitemap1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_Route_Cii_Class1`
    FOREIGN KEY (`classname_id`)
    REFERENCES `Cii_Classname` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_Abstract_Route_Cii_Mandate1`
    FOREIGN KEY (`mandate_id`)
    REFERENCES `Cii_Mandate` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Configuration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Configuration` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(45) NULL,
  `extension_id` INT UNSIGNED NULL,
  `mandate_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_Configuration_Core_Extension1_idx` (`extension_id` ASC),
  UNIQUE INDEX `name_extension_unique` (`name` ASC, `extension_id` ASC, `mandate_id` ASC),
  INDEX `fk_Cii_Configuration_Cii_Mandate1_idx` (`mandate_id` ASC),
  CONSTRAINT `fk_Core_Configuration_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Abstract_Extension` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cii_Configuration_Cii_Mandate1`
    FOREIGN KEY (`mandate_id`)
    REFERENCES `Cii_Mandate` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Layout`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Layout` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `extension_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_Layout_Core_Extension1_idx` (`extension_id` ASC),
  CONSTRAINT `fk_Core_Layout_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Abstract_Extension` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_User` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created` DATETIME NOT NULL,
  `activated` DATETIME NULL,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(64) NULL,
  `superadmin` TINYINT(1) NOT NULL DEFAULT 0,
  `enabled` TINYINT(1) NOT NULL,
  `timezone` VARCHAR(45) NULL,
  `language_id` INT UNSIGNED NULL,
  `layout_id` INT UNSIGNED NULL,
  `token` VARCHAR(64) NULL,
  `mandate_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreUser_CoreLanguage1_idx` (`language_id` ASC),
  INDEX `fk_Core_User_Core_Layout1_idx` (`layout_id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `enabled_idx` (`enabled` ASC),
  INDEX `created_idx` (`created` ASC),
  INDEX `activated_idx` (`activated` ASC),
  FULLTEXT INDEX `username__fulltext` (`username` ASC),
  INDEX `superadmin_idx` (`superadmin` ASC),
  FULLTEXT INDEX `email_fulltext` (`email` ASC),
  INDEX `fk_Cii_User_Cii_Mandate1_idx` (`mandate_id` ASC),
  CONSTRAINT `fk_CoreUser_CoreLanguage1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Core_User_Core_Layout1`
    FOREIGN KEY (`layout_id`)
    REFERENCES `Cii_Layout` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_User_Cii_Mandate1`
    FOREIGN KEY (`mandate_id`)
    REFERENCES `Cii_Mandate` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Group` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `created` DATETIME NOT NULL,
  `mandate_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `created_idx` (`created` ASC),
  INDEX `enabled_idx` (`enabled` ASC),
  FULLTEXT INDEX `name_fulltext` (`name` ASC),
  INDEX `fk_Cii_Group_Cii_Mandate1_idx` (`mandate_id` ASC),
  CONSTRAINT `fk_Cii_Group_Cii_Mandate1`
    FOREIGN KEY (`mandate_id`)
    REFERENCES `Cii_Mandate` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_GroupMembers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_GroupMembers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `group_id` INT UNSIGNED NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreUserGroup_CoreUser1_idx` (`user_id` ASC),
  INDEX `fk_CoreUserGroup_CoreGroup1_idx` (`group_id` ASC),
  INDEX `created_idx` (`created` ASC),
  CONSTRAINT `fk_CoreUserGroup_CoreUser1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Cii_User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CoreUserGroup_CoreGroup1`
    FOREIGN KEY (`group_id`)
    REFERENCES `Cii_Group` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Permission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` INT UNSIGNED NOT NULL,
  `group_id` INT UNSIGNED NOT NULL,
  `package_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Permission_CoreGroup1_idx` (`group_id` ASC),
  INDEX `fk_CorePermission_CoreModule1_idx` (`package_id` ASC),
  INDEX `fk_permission` (`permission_id` ASC),
  UNIQUE INDEX `unique_permission_module_group` (`permission_id` ASC, `group_id` ASC, `package_id` ASC),
  CONSTRAINT `fk_Permission_CoreGroup1`
    FOREIGN KEY (`group_id`)
    REFERENCES `Cii_Group` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CorePermission_CoreModule1`
    FOREIGN KEY (`package_id`)
    REFERENCES `Cii_Package` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_UserLoginContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_UserLoginContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `redirect_id` INT UNSIGNED NULL,
  `register_id` INT UNSIGNED NULL,
  `forgot_id` INT UNSIGNED NULL,
  `captcha_id` INT UNSIGNED NULL,
  `remember_visible` TINYINT(1) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreAuthView_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route1_idx` (`redirect_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route2_idx` (`register_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route3_idx` (`forgot_id` ASC),
  INDEX `fk_Cii_UserLoginContent_Cii_Route1_idx` (`captcha_id` ASC),
  CONSTRAINT `fk_CoreAuthView_CoreView1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route1`
    FOREIGN KEY (`redirect_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route2`
    FOREIGN KEY (`register_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route3`
    FOREIGN KEY (`forgot_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_UserLoginContent_Cii_Route1`
    FOREIGN KEY (`captcha_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_ContentVisibilities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_ContentVisibilities` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `route_id` INT UNSIGNED NULL,
  `show_layout` TINYINT(1) NOT NULL,
  `language_id` INT UNSIGNED NULL,
  `ordering` INT UNSIGNED NOT NULL,
  `position` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreViewVisibility_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_CoreViewVisibility_CoreSitemap1_idx` (`route_id` ASC),
  INDEX `fk_CoreViewVisibility_CoreLanguage1_idx` (`language_id` ASC),
  INDEX `ordering_idx` (`ordering` ASC),
  INDEX `position_idx` (`position` ASC),
  CONSTRAINT `fk_CoreViewVisibility_CoreView1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CoreViewVisibility_CoreSitemap1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CoreViewVisibility_CoreLanguage1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_BackendRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_BackendRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_BackendRoute_Core_Route1_idx` (`route_id` ASC),
  CONSTRAINT `fk_Core_BackendRoute_Core_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_ContentRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_ContentRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  `content_id` INT UNSIGNED NOT NULL,
  `keys` VARCHAR(255) NULL,
  `description` VARCHAR(255) NULL,
  `robots` VARCHAR(16) NULL,
  `type` VARCHAR(24) NULL,
  `image` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_PositionRoute_Core_Route1_idx` (`route_id` ASC),
  INDEX `fk_Core_ContentRoute_Core_Content1_idx` (`content_id` ASC),
  CONSTRAINT `fk_Core_PositionRoute_Core_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Core_ContentRoute_Core_Content1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_GiiRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_GiiRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_GiiRoute_Core_Route1_idx` (`route_id` ASC),
  CONSTRAINT `fk_Core_GiiRoute_Core_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_DocRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_DocRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_DocRoute_Core_Route1_idx` (`route_id` ASC),
  CONSTRAINT `fk_Core_DocRoute_Core_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_LanguageMessages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_LanguageMessages` (
  `id` INT NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `extension_id` INT UNSIGNED NOT NULL,
  `translatedExtension_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_LanguageMessages_Core_Language1_idx` (`language_id` ASC),
  INDEX `fk_Core_LanguageMessages_Core_Extension1_idx` (`extension_id` ASC),
  INDEX `fk_Cii_LanguageMessages_Cii_Extension1_idx` (`translatedExtension_id` ASC),
  CONSTRAINT `fk_Core_LanguageMessages_Core_Language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Core_LanguageMessages_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Abstract_Extension` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_LanguageMessages_Cii_Extension1`
    FOREIGN KEY (`translatedExtension_id`)
    REFERENCES `Cii_Abstract_Extension` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `Cii_UserLogoutContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_UserLogoutContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `redirect_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreAuthView_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route1_idx` (`redirect_id` ASC),
  CONSTRAINT `fk_CoreAuthView_CoreView10`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route10`
    FOREIGN KEY (`redirect_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_UserRegisterContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_UserRegisterContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `activate_id` INT UNSIGNED NOT NULL,
  `redirect_id` INT UNSIGNED NULL,
  `login_id` INT UNSIGNED NULL,
  `forgot_id` INT UNSIGNED NULL,
  `captcha_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreAuthView_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route1_idx` (`redirect_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route2_idx` (`login_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route3_idx` (`forgot_id` ASC),
  INDEX `fk_Cii_UserRegisterContent_Cii_Route1_idx` (`activate_id` ASC),
  INDEX `fk_Cii_UserRegisterContent_Cii_Route2_idx` (`captcha_id` ASC),
  CONSTRAINT `fk_CoreAuthView_CoreView11`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route11`
    FOREIGN KEY (`redirect_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route20`
    FOREIGN KEY (`login_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route30`
    FOREIGN KEY (`forgot_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_UserRegisterContent_Cii_Route1`
    FOREIGN KEY (`activate_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_UserRegisterContent_Cii_Route2`
    FOREIGN KEY (`captcha_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_UserActivateContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_UserActivateContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `redirect_id` INT UNSIGNED NULL,
  `login_id` INT UNSIGNED NULL,
  `register_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreAuthView_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route1_idx` (`redirect_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route2_idx` (`login_id` ASC),
  INDEX `fk_Cii_UserRegisterContent_Cii_Route1_idx` (`register_id` ASC),
  CONSTRAINT `fk_CoreAuthView_CoreView110`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route110`
    FOREIGN KEY (`redirect_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route200`
    FOREIGN KEY (`login_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_UserRegisterContent_Cii_Route10`
    FOREIGN KEY (`register_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_UserForgotContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_UserForgotContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `redirect_id` INT UNSIGNED NULL,
  `login_id` INT UNSIGNED NULL,
  `register_id` INT UNSIGNED NULL,
  `captcha_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreAuthView_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route1_idx` (`redirect_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route2_idx` (`register_id` ASC),
  INDEX `fk_Cii_AuthContent_Cii_Route3_idx` (`login_id` ASC),
  INDEX `fk_Cii_UserForgotContent_Cii_Route1_idx` (`captcha_id` ASC),
  CONSTRAINT `fk_CoreAuthView_CoreView12`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Abstract_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route12`
    FOREIGN KEY (`redirect_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route21`
    FOREIGN KEY (`register_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_AuthContent_Cii_Route31`
    FOREIGN KEY (`login_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_UserForgotContent_Cii_Route1`
    FOREIGN KEY (`captcha_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_MailTemplate`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_MailTemplate` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `package_id` INT UNSIGNED NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `content_text` TEXT NOT NULL,
  `content_html` TEXT NOT NULL,
  `language_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Cii_MailTemplate_Cii_Package1_idx` (`package_id` ASC),
  INDEX `fk_Cii_MailTemplate_Cii_Language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_Cii_MailTemplate_Cii_Package1`
    FOREIGN KEY (`package_id`)
    REFERENCES `Cii_Package` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cii_MailTemplate_Cii_Language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_ProfileRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_ProfileRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  `show_groups` TINYINT(1) NOT NULL,
  `can_change_layout` TINYINT(1) NOT NULL,
  `can_change_language` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Cii_UserProfileRoute_Cii_Route1_idx` (`route_id` ASC),
  CONSTRAINT `fk_Cii_UserProfileRoute_Cii_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_CountAccess`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_CountAccess` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hits` INT UNSIGNED NOT NULL DEFAULT 0,
  `created` DATE NOT NULL,
  `route_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `created_idx` (`created` ASC),
  INDEX `fk_Cii_CountAccess_Cii_Route1_idx` (`route_id` ASC),
  CONSTRAINT `fk_Cii_CountAccess_Cii_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_RedirectRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_RedirectRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  `redirect_id` INT UNSIGNED NULL,
  `type` INT UNSIGNED NOT NULL,
  `url` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Cii_Redirect_Cii_Route1_idx` (`route_id` ASC),
  INDEX `fk_Cii_Redirect_Cii_Route2_idx` (`redirect_id` ASC),
  CONSTRAINT `fk_Cii_Redirect_Cii_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_Redirect_Cii_Route2`
    FOREIGN KEY (`redirect_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `Cii_CaptchaRoute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_CaptchaRoute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` INT UNSIGNED NOT NULL,
  `length_min` INT NULL,
  `length_max` INT NULL,
  `font_color` VARCHAR(26) NULL,
  `limit` INT NULL,
  `width` INT NULL,
  `height` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Cii_Captcha_Cii_Route1_idx` (`route_id` ASC),
  CONSTRAINT `fk_Cii_Captcha_Cii_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Abstract_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_MandateMembers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_MandateMembers` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `mandate_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  INDEX `fk_Cii_MandateMembers_Cii_Mandate1_idx` (`mandate_id` ASC),
  INDEX `fk_Cii_MandateMembers_Cii_User1_idx` (`user_id` ASC),
  CONSTRAINT `fk_Cii_MandateMembers_Cii_Mandate1`
    FOREIGN KEY (`mandate_id`)
    REFERENCES `Cii_Mandate` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cii_MandateMembers_Cii_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Cii_User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
