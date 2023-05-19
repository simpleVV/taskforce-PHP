DROP DATABASE IF EXISTS taskforce;

CREATE DATABASE `taskforce`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE taskforce;

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  `code` VARCHAR(60) UNIQUE NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  `code` VARCHAR(60) UNIQUE NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `lat` DECIMAL,
  `lon` DECIMAL
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `statuses`;

CREATE TABLE `statuses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL UNIQUE,
  `code` VARCHAR(60) UNIQUE NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dt_registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` VARCHAR(68) NOT NULL UNIQUE,
  `name` VARCHAR(128) NOT NULL,
  `password` CHAR(255) NOT NULL,
  `city_id` INT(11) UNSIGNED NOT NULL,
  `role_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `users_1bfk_1`FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_1bfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(68),
  `phone` VARCHAR(11),
  `telegram` VARCHAR(64), 
  `user_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user_settings`;

CREATE TABLE `user_settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_birth` DATETIME,
  `avatar_path` VARCHAR(255) DEFAULT NULL,
  `about` TEXT,
  `is_performer` tinyint(1) unsigned DEFAULT '0',
  `hide_profile` tinyint(1) unsigned DEFAULT '0',
  `hide_contacts` tinyint(1) unsigned DEFAULT '0',
  `contacts_id` INT(11) UNSIGNED NULL,
  `user_id`INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `user_settings_1bfk_2` FOREIGN KEY (`contacts_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_settings_1bfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`) 
);

DROP TABLE IF EXISTS `user_categories`;

CREATE TABLE `user_categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `category_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `user_categories_1bfr_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_categories_1bfr_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY(`id`)
)

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dt_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` TEXT NOT NULL,
  `rate` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `client_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `reviews_1bfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reviews_1bfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dt_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `location` VARCHAR(128),
  `price` INT,
  `dt_expire` datetime DEFAULT NULL,
  `category_id` INT(11) UNSIGNED NOT NULL,
  `client_id` INT(11) UNSIGNED NOT NULL,
  `performer_id` INT(11) UNSIGNED,
  `status_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `tasks_1bfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tasks_1bfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tasks_1bfk_3` FOREIGN KEY (`performer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tasks_1bfk_4` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`),
);

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dt_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(60) NOT NULL,
  `path` varchar(255) NOT NULL UNIQUE,
  `task_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
   CONSTRAINT `files_1bfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `files_1bfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `responses`;

CREATE TABLE `responses` (
  `id` INT(11) UNSIGNED NOT NUll AUTO_INCREMENT,
  `dt_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` TEXT NOT NULL,
  `price` INT NOT NULL,
  `is_approved` tinyint(1) unsigned DEFAULT '0',
  `task_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NUll,
  CONSTRAINT `responses_1bfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `responses_1bfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);