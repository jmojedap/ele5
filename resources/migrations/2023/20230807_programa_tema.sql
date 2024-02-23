ALTER TABLE `programa` ADD `cantidad_unidades` TINYINT UNSIGNED NOT NULL DEFAULT '1' AFTER `nivel`;
ALTER TABLE `programa_tema` ADD `unidad` TINYINT UNSIGNED NOT NULL DEFAULT '1' AFTER `tema_id`;