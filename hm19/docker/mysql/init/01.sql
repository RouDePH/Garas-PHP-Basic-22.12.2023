CREATE TABLE IF NOT EXISTS `users`
(
    `id`         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `full_name`  CHAR(100)                         NOT NULL,
    `email`      CHAR(100) UNIQUE                  NOT NULL,
    `password`   CHAR(60)                          NOT NULL,
    `active`     BOOLEAN                                    DEFAULT TRUE,
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

INSERT INTO `users` (`full_name`, `email`, `password`, `role`, `active`)
VALUES ('John Doe', 'john@example.com', 'hashed_password', 'admin', true),
       ('Erik RouD', 'roud@example.com', 'hashed_password', 'partner', true);

