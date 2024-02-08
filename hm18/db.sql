CREATE TABLE IF NOT EXISTS `users`
(
    `id`         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `full_name`  CHAR(100)                         NOT NULL,
    `email`      CHAR(100) UNIQUE                  NOT NULL,
    `password`   CHAR(60)                          NOT NULL,
    `active`     BOOLEAN                                    DEFAULT FALSE,
    `role`       ENUM ('user', 'partner', 'admin') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP                                  DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP                                  DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP                                  DEFAULT NULL
    );

CREATE TABLE IF NOT EXISTS `sign_in_tokens`
(
    `id`            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `user_id`       INT UNSIGNED,
    `sign_in_token` CHAR(255) NOT NULL,
    `token_expires` TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    );

CREATE TABLE IF NOT EXISTS `refresh_tokens`
(
    `id`            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `refresh_token` CHAR(255)    NOT NULL,
    `user_id`       INT UNSIGNED NOT NULL,
    `fingerprint`   CHAR(255)    NOT NULL,
    `expires_in`    TIMESTAMP    NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    );

CREATE TABLE IF NOT EXISTS `service_categories`
(
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title`       CHAR(255) NOT NULL,
    `active`      BOOLEAN DEFAULT TRUE,
    `description` TEXT      NOT NULL
    );

CREATE TABLE IF NOT EXISTS `service_providers`
(
    `id`     INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title`  TEXT    NOT NULL,
    `active` BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS `service`
(
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `name`        TEXT                                          NOT NULL,
    `type`        ENUM ('one-time', 'recur') DEFAULT 'one-time' NOT NULL,
    `active`      BOOLEAN                    DEFAULT TRUE,
    `price`       INT UNSIGNED                                  NOT NULL,
    `description` TEXT                                          NOT NULL,
    `provider_id` INT UNSIGNED                                  NOT NULL,
    `category_id` INT UNSIGNED                                  NOT NULL,
    FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`)
    );

CREATE TABLE IF NOT EXISTS `balancing_settings`
(
    `id`         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `priority`   INT UNSIGNED        NOT NULL,
    `max_orders` INT UNSIGNED        NOT NULL,
    `service_id` INT UNSIGNED UNIQUE NOT NULL,
    FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
    );

INSERT INTO `users` (`full_name`, `email`, `password`, `role`)
VALUES ('John Doe', 'john@example.com', 'hashed_password', 'admin'),
       ('Erik RouD', 'roud@example.com', 'hashed_password', 'partner');

INSERT INTO `service_categories` (`title`, `active`, `description`)
VALUES ('Category 1', TRUE, 'Description of Category 1'),
       ('Category 2', TRUE, 'Description of Category 2'),
       ('Category 3', TRUE, 'Description of Category 3');

INSERT INTO `service` (`name`, `type`, `active`, `price`, `description`, `provider_id`, `category_id`)
VALUES ('Service 1', 'one-time', TRUE, 100, 'Description of Service 1', 1, 1),
       ('Service 2', 'recur', TRUE, 150, 'Description of Service 2', 2, 2),
       ('Service 3', 'one-time', TRUE, 200, 'Description of Service 3', 1, 3);

INSERT INTO `service_providers` (`title`, `active`)
VALUES
    ('Provider 1', TRUE),
    ('Provider 2', FALSE);

INSERT INTO `sign_in_tokens` (`user_id`, `sign_in_token`, `token_expires`)
VALUES (1, UUID(), NOW() + INTERVAL 1 HOUR);

INSERT INTO `refresh_tokens` (`refresh_token`, `user_id`, `fingerprint`, `expires_in`)
VALUES ('random_refresh_token', 1, 'random_fingerprint', NOW() + INTERVAL 1 DAY);

SELECT *
FROM `service_categories`;

SELECT *
FROM `users`
WHERE `role` = 'admin';

SELECT *
FROM `service`
WHERE `active` = TRUE;

SELECT u.`email`, s.`sign_in_token`, s.`token_expires`
FROM `users` AS u
         INNER JOIN `sign_in_tokens` AS s ON u.id = s.`user_id`
WHERE u.`email` = 'john@example.com';

SELECT s.*, c.`description` as `category_description`
FROM `service` AS s
         JOIN `service_categories` AS c ON s.`category_id` = c.`id`;

UPDATE `users`
SET `active` = true
WHERE `id` = 1;

UPDATE `service_providers`
SET `title` = 'Updated Provider Title'
WHERE `id` = 1;

DELETE
FROM `sign_in_tokens`
WHERE `user_id` = 1;

DELETE
FROM `service_categories`
WHERE `id` = 1;

DELETE
FROM `sign_in_tokens`;