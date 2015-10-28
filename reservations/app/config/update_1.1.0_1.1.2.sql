INSERT INTO `car_rental_options` (`id`, `key`, `tab_id`, `group`, `value`, `description`, `label`, `type`, `order`)
	VALUES (NULL, 'db_version', 99, NULL, '1.1.2', 'Database version', NULL, 'string', NULL)
	ON DUPLICATE KEY UPDATE `value` = '1.1.2';