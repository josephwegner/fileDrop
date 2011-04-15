DROP TABLE `files`;
DROP TABLE `groups`;
DROP TABLE `users`;

CREATE TABLE IF NOT EXISTS `files` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `file_name` text NOT NULL,
  `file_size` int(11) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(17) NOT NULL,
  `upload_by` text NOT NULL,
  `is_downloaded` int(11) NOT NULL DEFAULT '0',
  `detail` text NOT NULL,
  `os` varchar(10) NOT NULL,
  `is_revision` int(11) NOT NULL DEFAULT '0',
  `has_preview` int(11) NOT NULL DEFAULT '0'
);


CREATE TABLE IF NOT EXISTS `groups` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(7) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `open_register` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `can_download` int(11) NOT NULL,
  `phone` varchar(17) NOT NULL,
  `email` text NOT NULL,
  `name` text NOT NULL
);



INSERT INTO `groups` (`name`, `code`, `open_register`, `admin_id`) VALUES ('administrators', 'admin', 1, 1);
