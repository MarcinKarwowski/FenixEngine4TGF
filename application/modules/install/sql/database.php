<?php

$sql = [];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'users_groups' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users_groups` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `users_groups` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `users_groups` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SELECT `id` FROM `users_groups` WHERE `id`=1;",
    'make' => "INSERT INTO `users_groups` (`id`, `title`) VALUES (1, 'User');"
];
$sql[] = [
    'check' => "SELECT `id` FROM `users_groups` WHERE `id`=2;",
    'make' => "INSERT INTO `users_groups` (`id`, `title`) VALUES (2, 'Admin');"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'users' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(61) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `registerdate` bigint(20) DEFAULT NULL,
  `premiumdate` bigint(20) DEFAULT '0' COMMENT 'Premium end date',
  `shopmoney` int(10) DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `lpv` bigint(20) UNSIGNED DEFAULT '0',
  `options` varchar(255) DEFAULT NULL COMMENT '- Styl strony\n- GG',
  `active_characters_id` int(10) DEFAULT '0',
  `template` varchar(45) NOT NULL DEFAULT 'default',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `users` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users` WHERE Column_name = 'group_id';",
    'make' => "ALTER TABLE `users` ADD KEY `users_group` (`group_id`);"
];
$sql[] = [
    'make' => "ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'users_group'",
    'make' => "ALTER TABLE `users` ADD CONSTRAINT `users_group` FOREIGN KEY (`group_id`) REFERENCES `users_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'characters' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(10) NOT NULL,
  `users_id` int(10) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(50) DEFAULT NULL,
  `gender` char(1) NOT NULL DEFAULT 'M',
  `avatar` varchar(40) DEFAULT NULL,
  `level` int(10) NOT NULL DEFAULT '1',
  `hp` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `max_hp` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `gold` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `location_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ego_data` varchar(255) DEFAULT NULL COMMENT 'Character characteristics get from EGO',
  `newlogs` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'New anouncements',
  `newmsg` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'New messages',
  `chatroom` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` varchar(2000) DEFAULT NULL COMMENT 'Mood desribe',
  `pd` int(10) NOT NULL DEFAULT '0',
  `pu` int(4) NOT NULL DEFAULT '0',
  `pc` int(10) NOT NULL DEFAULT '0',
  `equipment` text,
  `spells` text,
  `events` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `characters` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `characters` ADD KEY `users_idx` (`users_id`);"
];
$sql[] = [
    'make' => "ALTER TABLE `characters` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'characters_history' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `characters_history` (
  `id` int(10) NOT NULL,
  `character_id` int(10) NOT NULL,
  `place` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT 'PROFILE',
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `date` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters_history` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `characters_history` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `characters_history` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters_history` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `characters_history` ADD KEY `character_id` (`character_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'characters_history_ibfk_1'",
    'make' => "ALTER TABLE `characters_history` ADD CONSTRAINT `characters_history_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_notifications' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `type` varchar(15) NOT NULL,
  `item_id` int(10) NOT NULL DEFAULT '0' COMMENT 'ID elementu którego dotyczy notify',
  `popup` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `globals` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_notifications` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_notifications` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_notifications` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'characters_notifications' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `characters_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) NOT NULL DEFAULT '0',
  `game_notifications_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `readed` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `expiry` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters_notifications` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `characters_notifications` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `characters_notifications` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters_notifications` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `characters_notifications` ADD KEY `FK__characters` (`character_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `characters_notifications` WHERE Column_name = 'game_notifications_id';",
    'make' => "ALTER TABLE `characters_notifications` ADD KEY `FK__game_notifications` (`game_notifications_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'FK__characters'",
    'make' => "ALTER TABLE `characters_notifications` ADD CONSTRAINT `FK__characters` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'FK__game_notifications'",
    'make' => "ALTER TABLE `characters_notifications` ADD CONSTRAINT `FK__game_notifications` FOREIGN KEY (`game_notifications_id`) REFERENCES `game_notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'email_jobs' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `email_jobs` (
  `id` int(10) NOT NULL,
  `users_id` int(10) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT 'CONFIRM, REMIND',
  `createdAt` int(10) DEFAULT NULL,
  `modifiedAt` int(10) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `email_jobs` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `email_jobs` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `email_jobs` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `email_jobs` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `email_jobs` ADD KEY `uid_idx` (`users_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'email_jobs_uid'",
    'make' => "ALTER TABLE `email_jobs` ADD CONSTRAINT `email_jobs_uid` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'failed_logins' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `failed_logins` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) DEFAULT NULL,
  `ip` char(15) NOT NULL,
  `attempted` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `failed_logins` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `failed_logins` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `failed_logins` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `failed_logins` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `failed_logins` ADD KEY `failed_logins_user_id_idx` (`users_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'failed_logins_user_id'",
    'make' => "ALTER TABLE `failed_logins` ADD CONSTRAINT `failed_logins_user_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_characters_achivements' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_characters_achivements` (
  `id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) NOT NULL,
  `gain` int(10) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'PD',
  `text` varchar(2000) NOT NULL DEFAULT '',
  `date` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_characters_achivements` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_characters_achivements` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_characters_achivements` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_characters_achivements` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `game_characters_achivements` ADD KEY `achive_char_id` (`character_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'achive_char_id'",
    'make' => "ALTER TABLE `game_characters_achivements` ADD CONSTRAINT `achive_char_id` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'articles' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `users_id` int(10) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `publishdate` bigint(20) UNSIGNED DEFAULT NULL,
  `editdate` bigint(20) UNSIGNED DEFAULT NULL,
  `sortorder` int(5) DEFAULT '0',
  `views` int(10) DEFAULT '0',
  `votessum` int(7) DEFAULT '0',
  `votescount` int(7) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `articles` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `articles` ADD KEY `users_id` (`users_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles` WHERE Column_name = 'parent_id';",
    'make' => "ALTER TABLE `articles` ADD KEY `parent_id` (`parent_id`);"
];
$sql[] = [
    'make' => "ALTER TABLE `articles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'users'",
    'make' => "ALTER TABLE `articles` ADD CONSTRAINT `users` FOREIGN KEY (`users_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'articles_comments' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `articles_comments` (
  `id` int(11) NOT NULL,
  `articles_id` int(10) DEFAULT NULL,
  `character_id` int(10) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '1',
  `text` text,
  `publishdate` bigint(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles_comments` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `articles_comments` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles_comments` WHERE Column_name = 'articles_id';",
    'make' => "ALTER TABLE `articles_comments` ADD KEY `articles_idx` (`articles_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles_comments` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `articles_comments` ADD KEY `characters_idx` (`character_id`);"
];
$sql[] = [
    'make' => "ALTER TABLE `articles_comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'articles_comments_articles'",
    'make' => "ALTER TABLE `articles_comments` ADD CONSTRAINT `articles_comments_articles` FOREIGN KEY (`articles_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'articles_comments_characters'",
    'make' => "ALTER TABLE `articles_comments` ADD CONSTRAINT `articles_comments_characters` FOREIGN KEY (`character_id`) REFERENCES `characters`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'articles_text' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `articles_text` (
  `articles_id` int(11) NOT NULL,
  `text` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `articles_text` WHERE Column_name = 'articles_id';",
    'make' => "ALTER TABLE `articles_text` ADD PRIMARY KEY (articles_id);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM articles_text WHERE Column_name = 'articles_id';",
    'make' => "ALTER TABLE `articles_text` ADD UNIQUE KEY `articles_id_UNIQUE` (`articles_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'articles_desc_articles'",
    'make' => "ALTER TABLE `articles_text` ADD CONSTRAINT `articles_desc_articles` FOREIGN KEY (`articles_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_chats' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `showinn` int(10) NOT NULL DEFAULT '0',
  `owner_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `desc` text NOT NULL,
  `days` int(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Liczba dni do usunięcia pokoju',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `archived` int(10) NOT NULL DEFAULT '0',
  `last_msg_id` int(10) NOT NULL DEFAULT '1000000',
  `priv` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_chats` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_chats` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_chats_chars' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_chats_chars` (
  `id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `modified_at` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_chars` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_chats_chars` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_chats_chars` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_chars` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `game_chats_chars` ADD KEY `chat` (`character_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_chars` WHERE Column_name = 'room_id';",
    'make' => "ALTER TABLE `game_chats_chars` ADD KEY `rooms` (`room_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_chars` WHERE Column_name IN (\"room_id\", \"character_id\");",
    'make' => "ALTER TABLE `game_chats_chars` ADD KEY `character_id_room_id` (`character_id`,`room_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'chat_members'",
    'make' => "ALTER TABLE `game_chats_chars` ADD CONSTRAINT `chat_members` FOREIGN KEY (`room_id`) REFERENCES `game_chats`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_chats_msg' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_chats_msg` (
  `id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) DEFAULT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `date` bigint(20) DEFAULT NULL,
  `msg` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_msg` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_chats_msg` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_chats_msg` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_msg` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `game_chats_msg` ADD KEY `cham_msg_char` (`character_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_chats_msg` WHERE Column_name = 'room_id';",
    'make' => "ALTER TABLE `game_chats_msg` ADD KEY `chats_rooms` (`room_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'cham_msg_char'",
    'make' => "ALTER TABLE `game_chats_msg` ADD CONSTRAINT `cham_msg_char` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'chats_rooms'",
    'make' => "ALTER TABLE `game_chats_msg` ADD CONSTRAINT `chats_rooms` FOREIGN KEY (`room_id`) REFERENCES `game_chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_cr_categories' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_cr_categories` (
  `id` int(10) NOT NULL,
  `name` varchar(70) NOT NULL,
  `text` varchar(2000) NOT NULL,
  `orderid` int(10) NOT NULL,
  `showinprofile` int(1) NOT NULL DEFAULT '1',
  `showincreator` int(1) NOT NULL DEFAULT '1',
  `type` varchar(10) NOT NULL DEFAULT 'list',
  `params` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_categories` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_cr_categories` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_cr_categories` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_cr_pages' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_cr_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(70) NOT NULL,
  `category_id` int(10) NOT NULL,
  `wiki_id` int(10) NOT NULL DEFAULT '0',
  `text` varchar(2000) NOT NULL DEFAULT '',
  `params` varchar(1000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_pages` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_cr_pages` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_cr_pages` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_pages` WHERE Column_name = 'category_id';",
    'make' => "ALTER TABLE `game_cr_pages` ADD KEY `page_category_id` (`category_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'page_category_id'",
    'make' => "ALTER TABLE `game_cr_pages` ADD CONSTRAINT `page_category_id` FOREIGN KEY (`category_id`) REFERENCES `game_cr_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_cr_players' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_cr_players` (
  `id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `value` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_players` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_cr_players` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_cr_players` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_players` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `game_cr_players` ADD KEY `page_char_id` (`character_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_players` WHERE Column_name = 'page_id';",
    'make' => "ALTER TABLE `game_cr_players` ADD KEY `page_page_id` (`page_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'page_char_id'",
    'make' => "ALTER TABLE `game_cr_players` ADD CONSTRAINT `page_char_id` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'page_page_id'",
    'make' => "ALTER TABLE `game_cr_players` ADD CONSTRAINT `page_page_id` FOREIGN KEY (`page_id`) REFERENCES `game_cr_pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_cr_relations' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_cr_relations` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `link_page_id` int(10) NOT NULL,
  `value` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_relations` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_cr_relations` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_cr_relations` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_relations` WHERE Column_name = 'page_id';",
    'make' => "ALTER TABLE `game_cr_relations` ADD KEY `page_id` (`page_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_cr_relations` WHERE Column_name = 'link_page_id';",
    'make' => "ALTER TABLE `game_cr_relations` ADD KEY `link_page_id` (`link_page_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'page_link_id'",
    'make' => "ALTER TABLE `game_cr_relations` ADD CONSTRAINT `page_link_id` FOREIGN KEY (`page_id`) REFERENCES `game_cr_pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_items_cat' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_items_cat` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT 'Lokacja',
  `text` varchar(2000) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT 'CONTENT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_items_cat` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_items_cat` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_items_cat` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_items' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT 'Przedmiot',
  `text` varchar(2000) NOT NULL DEFAULT '',
  `price` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_items` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_items` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_items` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_items` WHERE Column_name = 'category_id';",
    'make' => "ALTER TABLE `game_items` ADD KEY `item_cat_id` (`category_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'item_cat_id'",
    'make' => "ALTER TABLE `game_items` ADD CONSTRAINT `item_cat_id` FOREIGN KEY (`category_id`) REFERENCES `game_items_cat` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_items_players' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_items_players` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_items_players` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_items_players` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_items_players` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_items_players` WHERE Column_name = 'item_id';",
    'make' => "ALTER TABLE `game_items_players` ADD KEY `item_item_id` (`item_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_items_players` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `game_items_players` ADD KEY `item_char_id` (`character_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'item_char_id'",
    'make' => "ALTER TABLE `game_items_players` ADD CONSTRAINT `item_char_id` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'item_item_id'",
    'make' => "ALTER TABLE `game_items_players` ADD CONSTRAINT `item_item_id` FOREIGN KEY (`item_id`) REFERENCES `game_items` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_locations' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT 'Lokacja',
  `text` varchar(2000) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT 'CONTENT',
  `coords` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_locations` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_locations` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_locations` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_locations` WHERE Column_name = 'parent_id';",
    'make' => "ALTER TABLE `game_locations` ADD KEY `parent_id` (`parent_id`);"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_locations_items' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_locations_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_locations_items` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_locations_items` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_locations_items` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_locations_items` WHERE Column_name = 'item_id';",
    'make' => "ALTER TABLE `game_locations_items` ADD KEY `item_location_id` (`item_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_locations_items` WHERE Column_name = 'location_id';",
    'make' => "ALTER TABLE `game_locations_items` ADD KEY `location_node_id` (`location_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'item_location_id'",
    'make' => "ALTER TABLE `game_locations_items` ADD CONSTRAINT `item_location_id` FOREIGN KEY (`item_id`) REFERENCES `game_items` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'location_node_id'",
    'make' => "ALTER TABLE `game_locations_items` ADD CONSTRAINT `location_node_id` FOREIGN KEY (`location_id`) REFERENCES `game_locations` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_messages' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `character_id` int(10) NOT NULL DEFAULT '0',
  `sender_id` int(10) NOT NULL DEFAULT '0',
  `date` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `topic` varchar(100) NOT NULL DEFAULT '0',
  `readed` tinyint(1) UNSIGNED ZEROFILL DEFAULT '0',
  `sended` tinyint(1) UNSIGNED DEFAULT '0',
  `saved` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_messages` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_messages` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_messages` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_messages` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `game_messages` ADD KEY `charmess` (`character_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'charmess'",
    'make' => "ALTER TABLE `game_messages` ADD CONSTRAINT `charmess` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_messages_desc' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_messages_desc` (
  `message_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_messages_desc` WHERE Column_name = 'message_id';",
    'make' => "ALTER TABLE `game_messages_desc` ADD KEY `mess_desc_text` (`message_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'mess_desc_text'",
    'make' => "ALTER TABLE `game_messages_desc` ADD CONSTRAINT `mess_desc_text` FOREIGN KEY (`message_id`) REFERENCES `game_messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'game_npcs' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `game_npcs` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ID ewentualnego właściciela',
  `name` varchar(50) NOT NULL DEFAULT '0',
  `gender` char(1) NOT NULL,
  `avatar` varchar(40) NOT NULL,
  `profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `game_npcs` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `game_npcs` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `game_npcs` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'media' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'IMG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `media` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `media` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `media` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'permissions' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) NOT NULL,
  `users_id` int(10) DEFAULT NULL,
  `resource` varchar(20) DEFAULT NULL,
  `action` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `permissions` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `permissions` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `permissions` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `permissions` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `permissions` ADD KEY `uid_idx` (`users_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'permissions_uid'",
    'make' => "ALTER TABLE `permissions` ADD CONSTRAINT `permissions_uid` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'polls' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `expiry` bigint(20) DEFAULT NULL COMMENT 'Timestamp of poll ending',
  `users_voteslimit` int(3) DEFAULT '1' COMMENT 'Limit of votes that every player can make',
  `character_minlevel` int(4) DEFAULT NULL COMMENT 'Character min level',
  `showvoters` tinyint(1) DEFAULT '0',
  `papremium` int(5) DEFAULT '0' COMMENT 'Premium points for vote',
  `published` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `polls` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `polls` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'polls_options' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `polls_options` (
  `id` int(11) NOT NULL,
  `polls_id` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls_options` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `polls_options` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `polls_options` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls_options` WHERE Column_name = 'polls_id';",
    'make' => "ALTER TABLE `polls_options` ADD KEY `poll_idx` (`polls_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'polls_options_poll'",
    'make' => "ALTER TABLE `polls_options` ADD CONSTRAINT `polls_options_poll` FOREIGN KEY (`polls_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'polls_votes' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `polls_votes` (
  `id` int(11) NOT NULL,
  `polls_id` int(10) DEFAULT NULL,
  `polls_option_id` int(10) DEFAULT NULL,
  `users_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls_votes` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `polls_votes` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `polls_votes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls_votes` WHERE Column_name = 'polls_id';",
    'make' => "ALTER TABLE `polls_votes` ADD KEY `poll_idx` (`polls_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls_votes` WHERE Column_name = 'polls_option_id';",
    'make' => "ALTER TABLE `polls_votes` ADD KEY `option_idx` (`polls_option_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `polls_votes` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `polls_votes` ADD KEY `users_idx` (`users_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'poll_votes_poll'",
    'make' => "ALTER TABLE `polls_votes` ADD CONSTRAINT `poll_votes_poll` FOREIGN KEY (`polls_id`) REFERENCES `polls` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'polls_votes_option'",
    'make' => "ALTER TABLE `polls_votes` ADD CONSTRAINT `polls_votes_option` FOREIGN KEY (`polls_option_id`) REFERENCES `polls_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'polls_votes_users'",
    'make' => "ALTER TABLE `polls_votes` ADD CONSTRAINT `polls_votes_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'remember_tokens' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `remember_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) NOT NULL,
  `token` varchar(32) NOT NULL,
  `userAgent` varchar(100) NOT NULL,
  `created_at` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `remember_tokens` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `remember_tokens` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `remember_tokens` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `remember_tokens` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `remember_tokens` ADD KEY `user_token` (`users_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'user_token'",
    'make' => "ALTER TABLE `remember_tokens` ADD CONSTRAINT `user_token` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'reset_passwords' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `reset_passwords` (
  `id` int(10) UNSIGNED NOT NULL,
  `usersId` int(10) UNSIGNED NOT NULL,
  `code` varchar(48) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL,
  `modifiedAt` int(10) UNSIGNED DEFAULT NULL,
  `reset` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `reset_passwords` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `reset_passwords` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `reset_passwords` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `reset_passwords` WHERE Column_name = 'usersId';",
    'make' => "ALTER TABLE `reset_passwords` ADD KEY `usersId` (`usersId`);"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'session' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(35) NOT NULL,
  `data` text,
  `created_at` int(15) UNSIGNED NOT NULL,
  `modified_at` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `session` WHERE Column_name = 'session_id';",
    'make' => "ALTER TABLE `session` ADD PRIMARY KEY (`session_id`);"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'success_logins' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `success_logins` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `ip` char(15) NOT NULL,
  `userAgent` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `success_logins` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `success_logins` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `success_logins` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `success_logins` WHERE Column_name = 'user_id';",
    'make' => "ALTER TABLE `success_logins` ADD KEY `succes_logins_user_id_idx` (`user_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'succes_logins_user_id'",
    'make' => "ALTER TABLE `success_logins` ADD CONSTRAINT `succes_logins_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'users_activity' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `users_activity` (
  `id` int(11) NOT NULL,
  `users_id` int(10) DEFAULT NULL,
  `character_id` int(10) DEFAULT NULL,
  `actionmodule` varchar(50) DEFAULT NULL,
  `actiontype` varchar(50) DEFAULT NULL,
  `actionvalue` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users_activity` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `users_activity` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `users_activity` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users_activity` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `users_activity` ADD KEY `users_idx` (`users_id`);"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users_activity` WHERE Column_name = 'character_id';",
    'make' => "ALTER TABLE `users_activity` ADD KEY `characters_idx` (`character_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'users_activity_characters'",
    'make' => "ALTER TABLE `users_activity` ADD CONSTRAINT `users_activity_characters` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'users_activity_users'",
    'make' => "ALTER TABLE `users_activity` ADD CONSTRAINT `users_activity_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'users_permissions' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `users_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `permission_name` varchar(50) NOT NULL,
  `permission_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Uprawnienia użytkowników niezależne od grup';"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users_permissions` WHERE Column_name = 'id';",
    'make' => "ALTER TABLE `users_permissions` ADD PRIMARY KEY (id);"
];
$sql[] = [
    'make' => "ALTER TABLE `users_permissions` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM `users_permissions` WHERE Column_name = 'users_id';",
    'make' => "ALTER TABLE `users_permissions` ADD KEY `perrmission_user` (`users_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'perrmission_user'",
    'make' => "ALTER TABLE `users_permissions` ADD CONSTRAINT `perrmission_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'wikipedia' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `wikipedia` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `orderid` int(10) NOT NULL DEFAULT '0',
  `users_id` int(10) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `publishdate` bigint(20) unsigned DEFAULT NULL,
  `editdate` bigint(20) unsigned DEFAULT NULL,
  `sortorder` int(5) DEFAULT '0',
  `views` int(10) DEFAULT '0',
  `votessum` int(7) DEFAULT '0',
  `votescount` int(7) DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB CHARSET=utf8;"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.tables WHERE table_schema = '".$parameters['db']['schema']."' AND table_name = 'wikipedia_text' LIMIT 1;",
    'make' => "CREATE TABLE IF NOT EXISTS `wikipedia_text` (`articles_id` int(11) NOT NULL, `text` mediumtext) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
$sql[] = [
    'check' => "SHOW INDEX FROM wikipedia_text WHERE Column_name = 'articles_id';",
    'make' => "ALTER TABLE `wikipedia_text` ADD UNIQUE KEY `articles_id` (`articles_id`);"
];
$sql[] = [
    'check' => "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_NAME = 'wikipedia_desc_articles'",
    'make' => "ALTER TABLE `articles_text` ADD CONSTRAINT `wikipedia_desc_articles` FOREIGN KEY (`articles_id`) REFERENCES `wikipedia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;"
];

return $sql;
