DROP DATABASE IF EXISTS taskforce;

CREATE DATABASE `taskforce`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE taskforce;

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(60) NOT NULL,
  `category_code` VARCHAR(60) UNIQUE NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(60) NOT NULL,
  `role_code` VARCHAR(60) UNIQUE NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_name` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `task_statuses`;

CREATE TABLE `task_statuses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status_name` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(68),
  `phone` VARCHAR(11),
  `telegram` VARCHAR(64) 
  `user_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_registration` DATETIME NOT NULL,
  `email` VARCHAR(68) NOT NULL UNIQUE,
  `user_name` VARCHAR(128) NOT NULL,
  `password` CHAR(255) NOT NULL,
  `city_id` INT(11) UNSIGNED NOT NULL,
  `role_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `users_1bfk_1`FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_1bfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user_settings`;

CREATE TABLE `user_settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_birth` DATETIME,
  `avatar` VARCHAR(255),
  `about` TEXT,
  `category_id` INT(11) UNSIGNED NOT NULL,
  `user_id`INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `user_settings_1bfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_settings_1bfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`) 
);

DROP TABLE IF EXISTS `user_reviews`;

CREATE TABLE `user_reviews` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_creation` DATETIME NOT NULL,
  `description` TEXT NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `client_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `user_reviews_1bfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_reviews_1bfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_creation` DATETIME NOT NULL,
  `title` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `file` VARCHAR(255),
  `location` VARCHAR(128),
  `price` INT,
  `date_end` DATETIME,
  `category_id` INT(11) UNSIGNED NOT NULL,
  `client_id` INT(11) UNSIGNED NOT NULL,
  `performer_id` INT(11) UNSIGNED NOT NULL,
  `status_id` INT(11) UNSIGNED NOT NULL,
  CONSTRAINT `tasks_1bfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tasks_1bfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tasks_1bfk_3` FOREIGN KEY (`performer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tasks_1bfk_4` FOREIGN KEY (`status_id`) REFERENCES `task_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `task_responses`;

CREATE TABLE `task_responses` (
  `id` INT(11) UNSIGNED NOT NUll AUTO_INCREMENT,
  `date_creation` DATETIME NOT NULL,
  `comment` TEXT NOT NULL,
  `price` INT NOT NULL,
  `task_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NUll,
  CONSTRAINT `task_responses_1bfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `task_responses_1bfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`id`)
);