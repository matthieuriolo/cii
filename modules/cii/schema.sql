
-- -----------------------------------------------------
-- Table `Cii_Class`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Class` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `classname` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `classname_UNIQUE` (`classname` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Content`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Content` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `created` DATETIME NOT NULL,
  `class_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Cii_Content_Cii_Class1_idx` (`class_id` ASC),
  CONSTRAINT `fk_Cii_Content_Cii_Class1`
    FOREIGN KEY (`class_id`)
    REFERENCES `Cii_Class` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Language`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Language` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `code` VARCHAR(6) NOT NULL,
  `shortCode` VARCHAR(2) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Route`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Route` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(45) NOT NULL,
  `language_id` INT UNSIGNED NULL,
  `parent_id` INT UNSIGNED NULL,
  `class_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreSitemap_CoreLanguage1_idx` (`language_id` ASC),
  INDEX `fk_CoreSitemap_CoreSitemap1_idx` (`parent_id` ASC),
  INDEX `fk_Cii_Route_Cii_Class1_idx` (`class_id` ASC),
  CONSTRAINT `fk_CoreSitemap_CoreLanguage1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CoreSitemap_CoreSitemap1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `Cii_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cii_Route_Cii_Class1`
    FOREIGN KEY (`class_id`)
    REFERENCES `Cii_Class` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Menu_Type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Menu_Type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Menu_Item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Menu_Item` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `type_id` INT UNSIGNED NOT NULL,
  `route_id` INT NULL,
  `parent_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_MenuItem_MenuType1_idx` (`type_id` ASC),
  INDEX `fk_MenuItem_CoreSitemap1_idx` (`route_id` ASC),
  INDEX `fk_MenuItem_MenuItem1_idx` (`parent_id` ASC),
  CONSTRAINT `fk_MenuItem_MenuType1`
    FOREIGN KEY (`type_id`)
    REFERENCES `Menu_Type` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_MenuItem_CoreSitemap1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Route` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_MenuItem_MenuItem1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `Menu_Item` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Menu_TypeContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Menu_TypeContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `type_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_MenuSnippet_CoreSnippet1_idx` (`content_id` ASC),
  INDEX `fk_MenuTypeSnippet_MenuType1_idx` (`type_id` ASC),
  CONSTRAINT `fk_MenuSnippet_CoreSnippet1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_MenuTypeSnippet_MenuType1`
    FOREIGN KEY (`type_id`)
    REFERENCES `Menu_Type` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Extension`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Extension` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `installed` DATETIME NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `class_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_Extension_Core_Class1_idx` (`class_id` ASC),
  CONSTRAINT `fk_Core_Extension_Core_Class1`
    FOREIGN KEY (`class_id`)
    REFERENCES `Cii_Class` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Configuration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Configuration` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(45) NULL,
  `extension_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_Core_Configuration_Core_Extension1_idx` (`extension_id` ASC),
  CONSTRAINT `fk_Core_Configuration_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Extension` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
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
    REFERENCES `Cii_Extension` (`id`)
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
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(64) NULL,
  `enabled` TINYINT(1) NOT NULL,
  `language_id` INT UNSIGNED NULL,
  `layout_id` INT UNSIGNED NULL,
  `reset_token` VARCHAR(64) NULL,
  `activation_token` VARCHAR(64) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreUser_CoreLanguage1_idx` (`language_id` ASC),
  INDEX `fk_Core_User_Core_Layout1_idx` (`layout_id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  CONSTRAINT `fk_CoreUser_CoreLanguage1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Core_User_Core_Layout1`
    FOREIGN KEY (`layout_id`)
    REFERENCES `Cii_Layout` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Group` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_GroupMembers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_GroupMembers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `group_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreUserGroup_CoreUser1_idx` (`user_id` ASC),
  INDEX `fk_CoreUserGroup_CoreGroup1_idx` (`group_id` ASC),
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
-- Table `Cii_Package`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Package` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `extension_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Core_Module_Core_Extension1_idx` (`extension_id` ASC),
  CONSTRAINT `fk_Core_Module_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Extension` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_Permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_Permission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `group_id` INT UNSIGNED NOT NULL,
  `module_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Permission_CoreGroup1_idx` (`group_id` ASC),
  INDEX `fk_CorePermission_CoreModule1_idx` (`module_id` ASC),
  CONSTRAINT `fk_Permission_CoreGroup1`
    FOREIGN KEY (`group_id`)
    REFERENCES `Cii_Group` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CorePermission_CoreModule1`
    FOREIGN KEY (`module_id`)
    REFERENCES `Cii_Package` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_AuthContent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_AuthContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(10) NOT NULL,
  `content_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreAuthView_CoreView1_idx` (`content_id` ASC),
  CONSTRAINT `fk_CoreAuthView_CoreView1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Cii_ContentVisibilities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cii_ContentVisibilities` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` INT UNSIGNED NOT NULL,
  `route_id` INT UNSIGNED NULL,
  `language_id` INT UNSIGNED NULL,
  `ordering` INT UNSIGNED NOT NULL,
  `position` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CoreViewVisibility_CoreView1_idx` (`content_id` ASC),
  INDEX `fk_CoreViewVisibility_CoreSitemap1_idx` (`route_id` ASC),
  INDEX `fk_CoreViewVisibility_CoreLanguage1_idx` (`language_id` ASC),
  CONSTRAINT `fk_CoreViewVisibility_CoreView1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_CoreViewVisibility_CoreSitemap1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Route` (`id`)
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
    REFERENCES `Cii_Route` (`id`)
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
  PRIMARY KEY (`id`),
  INDEX `fk_Core_PositionRoute_Core_Route1_idx` (`route_id` ASC),
  INDEX `fk_Core_ContentRoute_Core_Content1_idx` (`content_id` ASC),
  CONSTRAINT `fk_Core_PositionRoute_Core_Route1`
    FOREIGN KEY (`route_id`)
    REFERENCES `Cii_Route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Core_ContentRoute_Core_Content1`
    FOREIGN KEY (`content_id`)
    REFERENCES `Cii_Content` (`id`)
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
    REFERENCES `Cii_Route` (`id`)
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
    REFERENCES `Cii_Route` (`id`)
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
  PRIMARY KEY (`id`),
  INDEX `fk_Core_LanguageMessages_Core_Language1_idx` (`language_id` ASC),
  INDEX `fk_Core_LanguageMessages_Core_Extension1_idx` (`extension_id` ASC),
  CONSTRAINT `fk_Core_LanguageMessages_Core_Language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `Cii_Language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Core_LanguageMessages_Core_Extension1`
    FOREIGN KEY (`extension_id`)
    REFERENCES `Cii_Extension` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

