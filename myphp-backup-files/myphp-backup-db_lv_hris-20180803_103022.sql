CREATE DATABASE IF NOT EXISTS `db_lv_hris`;

USE `db_lv_hris`;

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES("1","2014_10_12_000000_create_users_table","1");
INSERT INTO `migrations` VALUES("2","2014_10_12_100000_create_password_resets_table","1");
INSERT INTO `migrations` VALUES("3","2018_08_03_093807_create_user_personal_information_table","2");
INSERT INTO `migrations` VALUES("4","2018_08_03_093849_create_user_educational_background_table","2");
INSERT INTO `migrations` VALUES("5","2018_08_03_093928_create_user_work_information_table","2");
INSERT INTO `migrations` VALUES("6","2018_08_03_094002_create_user_files","2");



DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `pr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pr_id`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS `user_educ_bckgrnd`;

CREATE TABLE `user_educ_bckgrnd` (
  `eb_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school` text COLLATE utf8mb4_unicode_ci,
  `course` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`eb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS `user_files`;

CREATE TABLE `user_files` (
  `uf_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_table_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_table_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS `user_prsnl_info`;

CREATE TABLE `user_prsnl_info` (
  `pi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middlename` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `birthdate` text COLLATE utf8mb4_unicode_ci,
  `birthplace` text COLLATE utf8mb4_unicode_ci,
  `age` int(11) DEFAULT NULL,
  `citizenship` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civilstatus` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `religion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contactnumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS `user_wrk_info`;

CREATE TABLE `user_wrk_info` (
  `wi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` text COLLATE utf8mb4_unicode_ci,
  `position` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wd_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wd_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsibilities` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`wi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `u_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

