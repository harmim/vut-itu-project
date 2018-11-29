SET NAMES utf8mb4;

ALTER DATABASE `vut_itu_project`
	DEFAULT CHARACTER SET utf8mb4
	COLLATE utf8mb4_unicode_520_ci;

USE `vut_itu_project`;


DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `todo_list`;
DROP TABLE IF EXISTS `todo_list_item`;
DROP TABLE IF EXISTS `todo_list_global_item`;


CREATE TABLE `user` (
	`id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`email` VARCHAR(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
	`password` VARCHAR(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
	`role` ENUM('admin', 'user') COLLATE utf8mb4_unicode_520_ci DEFAULT 'user' NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`)
)
	ENGINE = InnoDB
	DEFAULT CHARSET = utf8mb4
	COLLATE = utf8mb4_unicode_520_ci;


CREATE TABLE `todo_list` (
	`id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`name` VARCHAR(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
	`user_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	CONSTRAINT `todo_list_fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)
	ENGINE = InnoDB
	DEFAULT CHARSET = utf8mb4
	COLLATE = utf8mb4_unicode_520_ci;


CREATE TABLE `todo_list_item` (
	`id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`name` LONGTEXT COLLATE utf8mb4_unicode_520_ci NOT NULL,
	`done` TINYINT(1) DEFAULT 0 NOT NULL,
	`todo_list_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	KEY `todo_list_id` (`todo_list_id`),
	CONSTRAINT `todo_list_item_fk_todo_list` FOREIGN KEY (`todo_list_id`) REFERENCES `todo_list` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)
	ENGINE = InnoDB
	DEFAULT CHARSET = utf8mb4
	COLLATE = utf8mb4_unicode_520_ci;


CREATE TABLE `todo_list_global_item` (
	`id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`name` LONGTEXT COLLATE utf8mb4_unicode_520_ci NOT NULL,
	`position` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE = InnoDB
	DEFAULT CHARSET = utf8mb4
	COLLATE = utf8mb4_unicode_520_ci;


CREATE TABLE `todo_list_global_item_done` (
	`todo_list_global_item_id` INT UNSIGNED NOT NULL,
	`todo_list_id` INT UNSIGNED NOT NULL,
	`done` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`todo_list_global_item_id`, `todo_list_id`),
	KEY `todo_list_global_item_id` (`todo_list_global_item_id`),
	KEY `todo_list_id` (`todo_list_id`),
	CONSTRAINT `todo_list_global_item_done_fk_todo_list_global_item` FOREIGN KEY (`todo_list_global_item_id`) REFERENCES `todo_list_global_item`(`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `todo_list_global_item_done_fk_todo_list` FOREIGN KEY (`todo_list_id`) REFERENCES `todo_list`(`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)
	ENGINE = InnoDB
	DEFAULT CHARSET = utf8mb4
	COLLATE = utf8mb4_unicode_520_ci;


INSERT INTO `user` (`id`, `email`, `password`, `role`)
VALUES
	(1, 'admin@gmail.com', '$2a$10$qI4zamAo6tXFl0SOTa8Mm.7YdDpYoM6t2ikEoUK0LjQrRcM3v1WZS', 'admin'),
	(2, 'user@gmail.com', '$2a$10$qI4zamAo6tXFl0SOTa8Mm.7YdDpYoM6t2ikEoUK0LjQrRcM3v1WZS', 'user');


INSERT INTO `todo_list` (`id`, `name`, `user_id`)
VALUES
	(1, 'Sebastián San Martín', 2),
	(2, 'Carlos Escribano', 2),
	(3, 'Bernabé Villa', 2),
	(4, 'Estefania Luque', 2),
	(5, 'Benito Aparicio', 2),
	(6, 'Leonardo Gutiérrez', 2),
	(7, 'Alexander Quesada', 2),
	(8, 'Xavier Ordóñez', 2);


INSERT INTO `todo_list_item` (`name`, `done`, `todo_list_id`)
VALUES
	('Nam suscipit tempor urna.', 1, 1),
	('Lorem ipsum dolor sit amet.', 0, 1),
	('Nam suscipit tempor urna.', 0, 1);


INSERT INTO `todo_list_global_item` (`id`, `name`, `position`)
VALUES
	(1, 'Lorem ipsum dolor sit amet.', 1),
	(2, 'Nam suscipit tempor urna.', 2),
	(3, 'Lorem ipsum dolor sit amet.', 3),
	(4, 'Nam suscipit tempor urna.', 4),
	(5, 'Lorem ipsum dolor sit amet.', 5),
	(6, 'Nam suscipit tempor urna.', 6);


INSERT INTO `todo_list_global_item_done` (`done`, `todo_list_global_item_id`, `todo_list_id`)
VALUES
	(1, 1, 1),
	(0, 2, 1),
	(0, 3, 1),
	(0, 4, 1),
	(1, 5, 1),
	(0, 6, 1),
	(0, 1, 2),
	(0, 2, 2),
	(0, 3, 2),
	(0, 4, 2),
	(0, 5, 2),
	(0, 6, 2),
	(0, 1, 3),
	(0, 2, 3),
	(0, 3, 3),
	(0, 4, 3),
	(0, 5, 3),
	(0, 6, 3),
	(0, 1, 4),
	(0, 2, 4),
	(0, 3, 4),
	(0, 4, 4),
	(0, 5, 4),
	(0, 6, 4),
	(0, 1, 5),
	(0, 2, 5),
	(0, 3, 5),
	(0, 4, 5),
	(0, 5, 5),
	(0, 6, 5),
	(0, 1, 6),
	(0, 2, 6),
	(0, 3, 6),
	(0, 4, 6),
	(0, 5, 6),
	(0, 6, 6),
	(0, 1, 7),
	(0, 2, 7),
	(0, 3, 7),
	(0, 4, 7),
	(0, 5, 7),
	(0, 6, 7),
	(0, 1, 8),
	(0, 2, 8),
	(0, 3, 8),
	(0, 4, 8),
	(0, 5, 8),
	(0, 6, 8);
