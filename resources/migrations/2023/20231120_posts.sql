ALTER TABLE `post` ADD `tema_id` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `estado_comentarios`, ADD `area_id` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `tema_id`, ADD `nivel` TINYINT NOT NULL DEFAULT '0' AFTER `area_id`, ADD INDEX (`tema_id`), ADD INDEX (`area_id`);

ALTER TABLE `quiz` ADD `opciones` VARCHAR(1000) NOT NULL AFTER `tipo_quiz_id`;