CREATE TABLE `car_rental_i18n`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`foreign_id` int(10) unsigned NULL  , 
	`model` varchar(255) COLLATE utf8_general_ci NULL  , 
	`locale` tinyint(3) unsigned NULL  , 
	`field` varchar(255) COLLATE utf8_general_ci NULL  , 
	`content` text COLLATE utf8_general_ci NULL  , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `foreign_id`(`foreign_id`,`model`,`locale`,`field`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Car', '1', 'make', `make` 
FROM `car_rental_cars`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Car', '1', 'model', `model` 
FROM `car_rental_cars`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'extra_title', `extra_title` 
FROM `car_rental_extras`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'name', `name` 
FROM `car_rental_locations`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'state', `state` 
FROM `car_rental_locations`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'city', `city` 
FROM `car_rental_locations`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'address_1', `address_1` 
FROM `car_rental_locations`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'address_2', `address_2` 
FROM `car_rental_locations`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'opening_time', `opening_time` 
FROM `car_rental_locations`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'name', `name` 
FROM `car_rental_types`
WHERE 1;

INSERT INTO `car_rental_i18n` (`foreign_id`, `model`, `locale`, `field`, `content`)
SELECT `id`, 'Extra', '1', 'description', `description` 
FROM `car_rental_types`
WHERE 1;

ALTER TABLE `car_rental_bookings` 
	ADD COLUMN `uuid` int(10) unsigned   NULL after `id`, 
	CHANGE `type_id` `type_id` int(10) unsigned   NULL after `uuid`, 
	CHANGE `car_id` `car_id` int(10) unsigned   NULL after `type_id`, 
	CHANGE `pickup_id` `pickup_id` int(10) unsigned   NULL COMMENT 'Location ID' after `car_id`, 
	CHANGE `return_id` `return_id` int(10) unsigned   NULL COMMENT 'Location ID' after `pickup_id`, 
	CHANGE `from` `from` datetime   NULL after `return_id`, 
	CHANGE `to` `to` datetime   NULL after `from`, 
	CHANGE `total` `total` decimal(9,2) unsigned   NULL after `to`, 
	CHANGE `deposit` `deposit` decimal(9,2) unsigned   NULL after `total`, 
	CHANGE `tax` `tax` decimal(9,2) unsigned   NULL after `deposit`, 
	CHANGE `payment_method` `payment_method` enum('paypal','authorize','creditcard')  COLLATE utf8_general_ci NULL after `tax`, 
	CHANGE `status` `status` enum('confirmed','cancelled','pending')  COLLATE utf8_general_ci NULL DEFAULT 'pending' after `payment_method`, 
	CHANGE `txn_id` `txn_id` varchar(255)  COLLATE utf8_general_ci NULL after `status`, 
	CHANGE `processed_on` `processed_on` datetime   NULL after `txn_id`, 
	CHANGE `created` `created` datetime   NULL after `processed_on`, 
	CHANGE `c_title` `c_title` varchar(255)  COLLATE utf8_general_ci NULL after `created`, 
	CHANGE `c_fname` `c_fname` varchar(255)  COLLATE utf8_general_ci NULL after `c_title`, 
	CHANGE `c_lname` `c_lname` varchar(255)  COLLATE utf8_general_ci NULL after `c_fname`, 
	CHANGE `c_phone` `c_phone` varchar(255)  COLLATE utf8_general_ci NULL after `c_lname`, 
	CHANGE `c_email` `c_email` varchar(255)  COLLATE utf8_general_ci NULL after `c_phone`, 
	CHANGE `c_company` `c_company` varchar(255)  COLLATE utf8_general_ci NULL after `c_email`, 
	CHANGE `c_address_1` `c_address_1` varchar(255)  COLLATE utf8_general_ci NULL after `c_company`, 
	CHANGE `c_address_2` `c_address_2` varchar(255)  COLLATE utf8_general_ci NULL after `c_address_1`, 
	CHANGE `c_address_3` `c_address_3` varchar(255)  COLLATE utf8_general_ci NULL after `c_address_2`, 
	CHANGE `c_city` `c_city` varchar(255)  COLLATE utf8_general_ci NULL after `c_address_3`, 
	CHANGE `c_state` `c_state` varchar(255)  COLLATE utf8_general_ci NULL after `c_city`, 
	CHANGE `c_zip` `c_zip` varchar(255)  COLLATE utf8_general_ci NULL after `c_state`, 
	CHANGE `c_country` `c_country` int(10) unsigned   NULL after `c_zip`, 
	CHANGE `cc_type` `cc_type` varchar(255)  COLLATE utf8_general_ci NULL after `c_country`, 
	CHANGE `cc_num` `cc_num` varchar(255)  COLLATE utf8_general_ci NULL after `cc_type`, 
	CHANGE `cc_exp` `cc_exp` varchar(255)  COLLATE utf8_general_ci NULL after `cc_num`, 
	CHANGE `cc_code` `cc_code` varchar(255)  COLLATE utf8_general_ci NULL after `cc_exp`, 
	ADD COLUMN `locale_id` tinyint(3) unsigned   NULL after `cc_code`, 
	ADD UNIQUE KEY `uuid`(`uuid`), COMMENT='';

ALTER TABLE `car_rental_cars` 
	CHANGE `registration_number` `registration_number` varchar(255)  COLLATE utf8_general_ci NULL after `location_id`, 
	DROP COLUMN `make`, 
	DROP COLUMN `model`, COMMENT='';

ALTER TABLE `car_rental_extras` 
	CHANGE `price` `price` decimal(9,2) unsigned   NULL after `id`, 
	CHANGE `per` `per` enum('booking','day')  COLLATE utf8_general_ci NULL after `price`, 
	CHANGE `count` `count` smallint(5) unsigned   NULL after `per`, 
	DROP COLUMN `extra_title`, COMMENT='';

ALTER TABLE `car_rental_locations` 
	CHANGE `zip` `zip` varchar(255)  COLLATE utf8_general_ci NULL after `country_id`, 
	CHANGE `email` `email` varchar(255)  COLLATE utf8_general_ci NULL after `zip`, 
	CHANGE `lat` `lat` varchar(255)  COLLATE utf8_general_ci NULL after `phone`, 
	DROP COLUMN `name`, 
	DROP COLUMN `state`, 
	DROP COLUMN `city`, 
	DROP COLUMN `address_1`, 
	DROP COLUMN `address_2`, 
	DROP COLUMN `opening_time`, COMMENT='';

ALTER TABLE `car_rental_types` 
	CHANGE `passengers` `passengers` smallint(5) unsigned   NULL after `id`, 
	CHANGE `doors` `doors` tinyint(3) unsigned   NULL after `luggages`, 
	CHANGE `size` `size` enum('small','medium','large')  COLLATE utf8_general_ci NULL after `doors`, 
	CHANGE `transmission` `transmission` enum('manual','automatic','semi-automatic')  COLLATE utf8_general_ci NULL after `size`, 
	CHANGE `thumb_path` `thumb_path` varchar(255)  COLLATE utf8_general_ci NULL after `transmission`, 
	DROP COLUMN `name`, 
	DROP COLUMN `description`, COMMENT='';

INSERT INTO `car_rental_options` (`id`, `key`, `tab_id`, `group`, `value`, `description`, `label`, `type`, `order`)
	VALUES (NULL, 'db_version', 99, NULL, '1.1.2', 'Database version', NULL, 'string', NULL)
	ON DUPLICATE KEY UPDATE `value` = '1.1.2';