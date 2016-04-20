
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- documents_sharing
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `documents_sharing`;

CREATE TABLE `documents_sharing`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `share_key` VARCHAR(64) NOT NULL,
    `document_id` VARCHAR(255) NOT NULL,
    `customer_id` INTEGER,
    `groupe_id` INTEGER,
    `date_end_share` DATETIME NOT NULL,
    `date_last_download` DATETIME,
    `delete_file_after` TINYINT DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI_ds_customer_id` (`customer_id`),
    INDEX `FI_ds_groupe_id` (`groupe_id`),
    CONSTRAINT `fk_ds_customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_ds_groupe_id`
        FOREIGN KEY (`groupe_id`)
        REFERENCES `documents_sharing_groupe` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- documents_sharing_document
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `documents_sharing_document`;

CREATE TABLE `documents_sharing_document`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `file` VARCHAR(255) NOT NULL,
    `file_key` VARCHAR(64) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- documents_sharing_groupe
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `documents_sharing_groupe`;

CREATE TABLE `documents_sharing_groupe`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `customer_id` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- documents_sharing_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `documents_sharing_i18n`;

CREATE TABLE `documents_sharing_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255) DEFAULT '' NOT NULL,
    `description` LONGTEXT,
    `chapo` TEXT,
    `postscriptum` TEXT,
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `documents_sharing_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `documents_sharing` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- documents_sharing_document_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `documents_sharing_document_i18n`;

CREATE TABLE `documents_sharing_document_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    `description` LONGTEXT,
    `chapo` TEXT,
    `postscriptum` TEXT,
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `documents_sharing_document_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `documents_sharing_document` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
