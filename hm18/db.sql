-- Створення таблиці користувачів
CREATE TABLE `users`
(
    `id`         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `fullname`   CHAR(100)        NOT NULL,
    `email`      CHAR(100) UNIQUE NOT NULL,
    `password`   CHAR(60)         NOT NULL,
    `active`     BOOLEAN   DEFAULT FALSE,
    `role`       ENUM('user', 'partner', 'admin') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP DEFAULT NULL
);

-- Створення таблиці токенів входу
CREATE TABLE `sign_in_tokens`
(
    `id`            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `user_id`       INT UNSIGNED,
    `sign_in_token` CHAR(255) NOT NULL,
    `token_expires` TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

-- Створення таблиці токенів оновлення
CREATE TABLE `refresh_tokens`
(
    `id`            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `refresh_token` CHAR(255) NOT NULL,
    `user_id`       INT UNSIGNED NOT NULL,
    `fingerprint`   CHAR(255) NOT NULL,
    `expires_in`    TIMESTAMP NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

-- Створення таблиці послуг
CREATE TABLE `service`
(
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `name`        TEXT NOT NULL,
    `type`        ENUM('one-time', 'recur') DEFAULT 'one-time' NOT NULL,
    `active`      BOOLEAN DEFAULT TRUE,
    `price`       INT UNSIGNED NOT NULL,
    `description` TEXT NOT NULL,
    `provider_id` INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`)
);

-- Створення таблиці налаштувань балансування
CREATE TABLE `balancing_settings`
(
    `id`         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `priority`   INT UNSIGNED NOT NULL,
    `max_orders` INT UNSIGNED NOT NULL,
    `service_id` INT UNSIGNED UNIQUE NOT NULL,
    FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
);

-- Створення таблиці категорій послуг
CREATE TABLE `service_categories`
(
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title`       CHAR(255) NOT NULL,
    `active`      BOOLEAN DEFAULT TRUE,
    `description` TEXT      NOT NULL
);

-- Створення таблиці постачальників послуг
CREATE TABLE `service_providers`
(
    `id`     INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title`  TEXT    NOT NULL,
    `active` BOOLEAN NOT NULL
);

-- Додавання даних до деяких таблиць (приклади INSERT)

INSERT INTO `users` (`fullname`, `email`, `password`, `role`)
VALUES ('John Doe', 'john@example.com', 'hashed_password', 'admin');

INSERT INTO `service_categories` (`title`, `description`)
VALUES ('Category 1', 'Description of category 1');

INSERT INTO `service_providers` (`title`, `active`)
VALUES ('Provider 1', true);

-- Вибірка даних з декількох таблиць (приклад SELECT)
SELECT *
FROM `users`;
SELECT *
FROM `service_categories`;

-- Оновлення даних (приклад UPDATE)
UPDATE `users`
SET `active` = true
WHERE `id` = 1;

-- Видалення даних (приклад DELETE)
DELETE
FROM `service_categories`
WHERE `id` = 1;