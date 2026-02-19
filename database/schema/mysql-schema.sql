/*M!999999\- enable the sandbox mode */ 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `abouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `abouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anchor` varchar(255) NOT NULL,
  `nav_title` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `short_body` text DEFAULT NULL,
  `long_body` text DEFAULT NULL,
  `list_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`list_items`)),
  `link_text` varchar(255) DEFAULT NULL,
  `testimonial_quote` text DEFAULT NULL,
  `testimonial_author` varchar(255) DEFAULT NULL,
  `testimonial_company` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_position` enum('left','right') NOT NULL DEFAULT 'right',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `slug` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abouts_anchor_unique` (`anchor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `academy_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `academy_chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_chapters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `academy_category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_chapters_academy_category_id_sort_order_index` (`academy_category_id`,`sort_order`),
  CONSTRAINT `academy_chapters_academy_category_id_foreign` FOREIGN KEY (`academy_category_id`) REFERENCES `academy_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `academy_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `academy_category_id` bigint(20) unsigned NOT NULL,
  `academy_chapter_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL COMMENT 'Uploaded video file path in storage',
  `video_url` varchar(255) DEFAULT NULL COMMENT 'External video URL (YouTube, Vimeo, etc.)',
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `duration_seconds` int(10) unsigned DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_videos_slug_unique` (`slug`),
  KEY `academy_videos_academy_category_id_is_active_sort_order_index` (`academy_category_id`,`is_active`,`sort_order`),
  KEY `academy_videos_academy_chapter_id_foreign` (`academy_chapter_id`),
  CONSTRAINT `academy_videos_academy_category_id_foreign` FOREIGN KEY (`academy_category_id`) REFERENCES `academy_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `academy_videos_academy_chapter_id_foreign` FOREIGN KEY (`academy_chapter_id`) REFERENCES `academy_chapters` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `description` text NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `performed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_index` (`user_id`),
  KEY `activity_logs_user_type_index` (`user_type`),
  KEY `activity_logs_subject_id_subject_type_index` (`subject_id`,`subject_type`),
  KEY `activity_logs_performed_at_index` (`performed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admin_theme_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_theme_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `base_color` varchar(255) NOT NULL DEFAULT 'zinc',
  `accent_color` varchar(255) NOT NULL DEFAULT 'indigo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ai_service_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_service_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service` varchar(255) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `model` varchar(255) NOT NULL DEFAULT 'default',
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ai_service_settings_service_unique` (`service`),
  KEY `ai_service_settings_service_index` (`service`),
  KEY `ai_service_settings_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(16) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_plan_id` bigint(20) unsigned DEFAULT NULL,
  `autopilot_mode` enum('assisted','guided','full_autopilot') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `blog_category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_body` text DEFAULT NULL,
  `long_body` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `author_id` bigint(20) unsigned DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `seo_score` int(11) DEFAULT NULL,
  `seo_status` enum('google-friendly','needs-improvement','high-potential') DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogs_blog_category_id_foreign` (`blog_category_id`),
  KEY `blogs_author_id_foreign` (`author_id`),
  KEY `blogs_content_plan_id_foreign` (`content_plan_id`),
  CONSTRAINT `blogs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blogs_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blogs_content_plan_id_foreign` FOREIGN KEY (`content_plan_id`) REFERENCES `content_plans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `carousel_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carousel_widgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data_source` varchar(255) NOT NULL DEFAULT 'blog',
  `blog_category_id` bigint(20) unsigned DEFAULT NULL,
  `items_per_row` int(11) NOT NULL DEFAULT 3,
  `total_items` int(11) NOT NULL DEFAULT 6,
  `show_arrows` tinyint(1) NOT NULL DEFAULT 1,
  `show_dots` tinyint(1) NOT NULL DEFAULT 1,
  `show_author` tinyint(1) NOT NULL DEFAULT 1,
  `autoplay` tinyint(1) NOT NULL DEFAULT 0,
  `autoplay_speed` int(11) NOT NULL DEFAULT 3000,
  `infinite_loop` tinyint(1) NOT NULL DEFAULT 1,
  `show_view_all_button` tinyint(1) NOT NULL DEFAULT 0,
  `view_all_title` varchar(255) DEFAULT NULL,
  `view_all_description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carousel_widgets_identifier_unique` (`identifier`),
  KEY `carousel_widgets_blog_category_id_foreign` (`blog_category_id`),
  KEY `carousel_widgets_identifier_index` (`identifier`),
  KEY `carousel_widgets_is_active_index` (`is_active`),
  KEY `carousel_widgets_sort_order_index` (`sort_order`),
  CONSTRAINT `carousel_widgets_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `case_studies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `case_studies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_company` varchar(255) NOT NULL,
  `client_industry` varchar(255) DEFAULT NULL,
  `client_logo` varchar(255) DEFAULT NULL,
  `challenge` text NOT NULL,
  `solution` text NOT NULL,
  `results` text NOT NULL,
  `metrics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metrics`)),
  `key_quote` text NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `product_feature_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_feature_ids`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_studies_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `changelogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `changelogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('new','improved','fixed','api') NOT NULL DEFAULT 'new',
  `slug` varchar(255) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `steps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`steps`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `changelogs_slug_unique` (`slug`),
  KEY `changelogs_status_index` (`status`),
  KEY `changelogs_date_index` (`date`),
  KEY `changelogs_is_active_index` (`is_active`),
  KEY `changelogs_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comment_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment_votes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `type` enum('like','dislike') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_votes_comment_id_ip_address_unique` (`comment_id`,`ip_address`),
  KEY `comment_votes_user_id_foreign` (`user_id`),
  CONSTRAINT `comment_votes_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `rating` tinyint(3) unsigned DEFAULT NULL,
  `entity_type` varchar(255) DEFAULT NULL,
  `entity_id` bigint(20) unsigned DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `dislikes` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  KEY `comments_parent_id_foreign` (`parent_id`),
  KEY `comments_is_approved_index` (`is_approved`),
  CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contact_form_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_form_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_form_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `direction` enum('inbound','outbound') NOT NULL DEFAULT 'outbound',
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_form_messages_contact_form_id_index` (`contact_form_id`),
  KEY `contact_form_messages_user_id_index` (`user_id`),
  KEY `contact_form_messages_direction_index` (`direction`),
  CONSTRAINT `contact_form_messages_contact_form_id_foreign` FOREIGN KEY (`contact_form_id`) REFERENCES `contact_forms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contact_form_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contact_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) DEFAULT NULL,
  `reden` varchar(255) NOT NULL,
  `bericht` text NOT NULL,
  `bijlage` varchar(255) DEFAULT NULL,
  `contact_preference` varchar(255) NOT NULL,
  `avg_optin` tinyint(1) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `chamber_of_commerce` varchar(255) DEFAULT NULL,
  `tax_number` varchar(255) DEFAULT NULL,
  `invoice_email` varchar(255) DEFAULT NULL,
  `invoice_email_cc` varchar(255) DEFAULT NULL,
  `invoice_email_bcc` varchar(255) DEFAULT NULL,
  `payment_due_days` smallint(5) unsigned NOT NULL DEFAULT 14,
  `currency` varchar(3) NOT NULL DEFAULT 'EUR',
  `preferred_language` varchar(5) NOT NULL DEFAULT 'nl',
  `billing_attention` varchar(255) DEFAULT NULL,
  `billing_street` varchar(255) DEFAULT NULL,
  `billing_house_number` varchar(255) DEFAULT NULL,
  `billing_zipcode` varchar(32) DEFAULT NULL,
  `billing_city` varchar(255) DEFAULT NULL,
  `billing_region` varchar(255) DEFAULT NULL,
  `billing_country` varchar(2) DEFAULT NULL,
  `shipping_attention` varchar(255) DEFAULT NULL,
  `shipping_street` varchar(255) DEFAULT NULL,
  `shipping_house_number` varchar(255) DEFAULT NULL,
  `shipping_zipcode` varchar(32) DEFAULT NULL,
  `shipping_city` varchar(255) DEFAULT NULL,
  `shipping_region` varchar(255) DEFAULT NULL,
  `shipping_country` varchar(2) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `bic` varchar(255) DEFAULT NULL,
  `is_customer` tinyint(1) NOT NULL DEFAULT 1,
  `is_supplier` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `content_performances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_performances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contentable_type` varchar(255) NOT NULL,
  `contentable_id` bigint(20) unsigned NOT NULL,
  `ctr` decimal(5,4) DEFAULT NULL,
  `impressions` int(11) DEFAULT NULL,
  `engagement` decimal(5,2) DEFAULT NULL,
  `ranking_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ranking_data`)),
  `measured_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_performances_contentable_type_contentable_id_index` (`contentable_type`,`contentable_id`),
  KEY `content_perf_poly_measured_idx` (`contentable_type`,`contentable_id`,`measured_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `content_plan_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_plan_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_plan_id` bigint(20) unsigned NOT NULL,
  `item_type` enum('pillar','supporting','social','evergreen') NOT NULL DEFAULT 'supporting',
  `status` enum('planned','generating','draft','scheduled','published','failed') NOT NULL DEFAULT 'planned',
  `priority` int(11) NOT NULL DEFAULT 0,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `content_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`content_data`)),
  `related_content_id` bigint(20) unsigned DEFAULT NULL,
  `related_content_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_plan_items_content_plan_id_status_index` (`content_plan_id`,`status`),
  KEY `content_plan_items_scheduled_at_status_index` (`scheduled_at`,`status`),
  KEY `content_plan_items_item_type_status_index` (`item_type`,`status`),
  CONSTRAINT `content_plan_items_content_plan_id_foreign` FOREIGN KEY (`content_plan_id`) REFERENCES `content_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `content_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `intent_brief_id` bigint(20) unsigned NOT NULL,
  `status` enum('draft','pending_approval','approved','active','completed','cancelled') NOT NULL DEFAULT 'draft',
  `autopilot_mode` enum('assisted','guided','full_autopilot') NOT NULL DEFAULT 'assisted',
  `approved_at` timestamp NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `strategy_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`strategy_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_plans_intent_brief_id_status_index` (`intent_brief_id`,`status`),
  KEY `content_plans_start_date_end_date_index` (`start_date`,`end_date`),
  CONSTRAINT `content_plans_intent_brief_id_foreign` FOREIGN KEY (`intent_brief_id`) REFERENCES `intent_briefs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `content_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#6366f1',
  `applicable_models` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_models`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `doc_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doc_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doc_section_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_pages_doc_section_id_slug_index` (`doc_section_id`,`slug`),
  CONSTRAINT `doc_pages_doc_section_id_foreign` FOREIGN KEY (`doc_section_id`) REFERENCES `doc_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `doc_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doc_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doc_version_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_sections_doc_version_id_slug_index` (`doc_version_id`,`slug`),
  CONSTRAINT `doc_sections_doc_version_id_foreign` FOREIGN KEY (`doc_version_id`) REFERENCES `doc_versions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `doc_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doc_versions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doc_versions_version_unique` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `to_email` varchar(255) NOT NULL,
  `to_name` varchar(255) DEFAULT NULL,
  `from_email` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `body_html` text DEFAULT NULL,
  `body_text` text DEFAULT NULL,
  `mail_class` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `error_message` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `failed_at` timestamp NULL DEFAULT NULL,
  `related_type` varchar(255) DEFAULT NULL,
  `related_id` bigint(20) unsigned DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  PRIMARY KEY (`id`),
  KEY `email_logs_related_type_related_id_index` (`related_type`,`related_id`),
  KEY `email_logs_to_email_created_at_index` (`to_email`,`created_at`),
  KEY `email_logs_status_created_at_index` (`status`,`created_at`),
  KEY `email_logs_mail_class_created_at_index` (`mail_class`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cover_image` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_body` text DEFAULT NULL,
  `long_body` longtext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `registration_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_is_active_start_date_index` (`is_active`,`start_date`),
  KEY `events_start_date_index` (`start_date`),
  KEY `events_user_id_index` (`user_id`),
  CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `external_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `before_header` tinyint(1) NOT NULL DEFAULT 0,
  `before_body` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `external_codes_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) DEFAULT NULL COMMENT 'Identifier for page builder selection (e.g., homepage_faq, pricing_faq)',
  `title` varchar(255) DEFAULT NULL COMMENT 'Group title for FAQ section',
  `subtitle` varchar(255) DEFAULT NULL COMMENT 'Group subtitle for FAQ section',
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array of FAQ items with question and answer' CHECK (json_valid(`items`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_identifier_index` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `footer_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `column` tinyint(3) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `help_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `help_articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `product_feature_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_feature_ids`)),
  `difficulty_level` varchar(255) NOT NULL DEFAULT 'beginner',
  `estimated_read_time` int(11) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `related_articles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`related_articles`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `helpful_votes` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `help_articles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `homepage_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `homepage_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) NOT NULL,
  `module_type` varchar(255) NOT NULL,
  `identifier` varchar(255) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `intent_briefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `intent_briefs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `business_goal` varchar(255) NOT NULL,
  `audience` text NOT NULL,
  `topic` text NOT NULL,
  `tone` varchar(255) NOT NULL DEFAULT 'expert',
  `approval_level` enum('manual','auto_approve') NOT NULL DEFAULT 'manual',
  `status` enum('draft','processing','completed','failed') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `intent_briefs_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `intent_briefs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vacancy_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `repo_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','reviewed','shortlisted','rejected','hired') NOT NULL DEFAULT 'pending',
  `is_processed` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_applications_vacancy_id_foreign` (`vacancy_id`),
  CONSTRAINT `job_applications_vacancy_id_foreign` FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `knowledge_grids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `knowledge_grids` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_identifier` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cards`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `knowledge_grids_section_identifier_unique` (`section_identifier`),
  KEY `knowledge_grids_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `legal_page_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_page_versions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `legal_page_id` bigint(20) unsigned NOT NULL,
  `version_number` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `version_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `legal_page_versions_legal_page_id_version_number_unique` (`legal_page_id`,`version_number`),
  KEY `legal_page_versions_created_by_foreign` (`created_by`),
  KEY `legal_page_versions_legal_page_id_index` (`legal_page_id`),
  KEY `legal_page_versions_version_number_index` (`version_number`),
  CONSTRAINT `legal_page_versions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `legal_page_versions_legal_page_id_foreign` FOREIGN KEY (`legal_page_id`) REFERENCES `legal_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `legal_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `current_version` int(11) NOT NULL DEFAULT 1,
  `versioning_enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `legacy_pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `live_session_presenter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `live_session_presenter` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `live_session_id` bigint(20) unsigned NOT NULL,
  `presenter_id` bigint(20) unsigned NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `live_session_presenter_live_session_id_presenter_id_unique` (`live_session_id`,`presenter_id`),
  KEY `live_session_presenter_presenter_id_foreign` (`presenter_id`),
  KEY `live_session_presenter_live_session_id_is_primary_index` (`live_session_id`,`is_primary`),
  CONSTRAINT `live_session_presenter_live_session_id_foreign` FOREIGN KEY (`live_session_id`) REFERENCES `live_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `live_session_presenter_presenter_id_foreign` FOREIGN KEY (`presenter_id`) REFERENCES `presenters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `live_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `live_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `session_date` datetime NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `max_participants` int(11) NOT NULL DEFAULT 50,
  `status` enum('upcoming','live','completed','cancelled') NOT NULL DEFAULT 'upcoming',
  `type` enum('introduction','webinar','workshop','qa') NOT NULL DEFAULT 'webinar',
  `meeting_url` varchar(255) DEFAULT NULL,
  `recording_url` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT 'primary',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `live_sessions_slug_unique` (`slug`),
  KEY `live_sessions_status_session_date_index` (`status`,`session_date`),
  KEY `live_sessions_is_active_is_featured_index` (`is_active`,`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `magic_builders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `magic_builders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `blocks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`blocks`)),
  `content` longtext DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `magic_builders_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mail_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mail_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mail_mailer` varchar(255) NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` int(11) NOT NULL DEFAULT 587,
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` varchar(255) DEFAULT NULL,
  `mail_encryption` varchar(255) DEFAULT NULL,
  `mail_from_address` varchar(255) DEFAULT NULL,
  `mail_from_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `marketing_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'webinar',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `timezone` varchar(255) NOT NULL DEFAULT 'Europe/Amsterdam',
  `location` varchar(255) DEFAULT NULL,
  `meeting_url` varchar(255) DEFAULT NULL,
  `agenda` text DEFAULT NULL,
  `speakers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`speakers`)),
  `max_attendees` int(11) DEFAULT NULL,
  `registered_count` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `featured_image` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `registration_open` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marketing_events_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `marketing_personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_personas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `demographics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`demographics`)),
  `pain_points` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pain_points`)),
  `goals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`goals`)),
  `preferred_channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferred_channels`)),
  `avatar_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marketing_personas_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `marketing_testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mega_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mega_menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_bg_color` varchar(255) DEFAULT NULL,
  `is_mega_menu` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `page_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mega_menu_items_parent_id_order_index` (`parent_id`,`order`),
  KEY `mega_menu_items_is_active_index` (`is_active`),
  KEY `mega_menu_items_page_id_index` (`page_id`),
  CONSTRAINT `mega_menu_items_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mega_menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `mega_menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `module_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_feature` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `module_id` bigint(20) unsigned NOT NULL,
  `feature_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_feature_module_id_feature_id_unique` (`module_id`,`feature_id`),
  KEY `module_feature_feature_id_foreign` (`feature_id`),
  CONSTRAINT `module_feature_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_feature_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `module_solution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_solution` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `module_id` bigint(20) unsigned NOT NULL,
  `solution_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_solution_module_id_solution_id_unique` (`module_id`,`solution_id`),
  KEY `module_solution_solution_id_foreign` (`solution_id`),
  CONSTRAINT `module_solution_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_solution_solution_id_foreign` FOREIGN KEY (`solution_id`) REFERENCES `solutions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `short_body` text DEFAULT NULL,
  `long_body` longtext DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(60) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `anchor` varchar(255) DEFAULT NULL,
  `nav_title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `list_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`list_items`)),
  `link_text` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `testimonial_quote` text DEFAULT NULL,
  `testimonial_author` varchar(255) DEFAULT NULL,
  `testimonial_company` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_position` enum('left','right') NOT NULL DEFAULT 'right',
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `organization_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `organization_names` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `abbreviation` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_names_is_active_sort_order_index` (`is_active`,`sort_order`),
  KEY `organization_names_name_index` (`name`),
  KEY `organization_names_abbreviation_index` (`abbreviation`),
  KEY `organization_names_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `short_body` text DEFAULT NULL,
  `long_body` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_body` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `funnel_fase` enum('interesseer','overtuig','activeer','inspireer') DEFAULT NULL,
  `marketing_persona_id` bigint(20) unsigned DEFAULT NULL,
  `content_type_id` bigint(20) unsigned DEFAULT NULL,
  `primary_keyword` varchar(255) DEFAULT NULL,
  `secondary_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`secondary_keywords`)),
  `ai_briefing` text DEFAULT NULL,
  `seo_analysis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_analysis`)),
  PRIMARY KEY (`id`),
  KEY `pages_marketing_persona_id_foreign` (`marketing_persona_id`),
  KEY `pages_content_type_id_foreign` (`content_type_id`),
  CONSTRAINT `pages_content_type_id_foreign` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_marketing_persona_id_foreign` FOREIGN KEY (`marketing_persona_id`) REFERENCES `marketing_personas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `presenters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `presenters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presenters_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pricing_boosters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_boosters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `link_text` varchar(255) NOT NULL DEFAULT 'Read more →',
  `link_url` varchar(255) NOT NULL DEFAULT '/trial',
  `footnote` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pricing_boosters_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pricing_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `available_in_plans` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`available_in_plans`)),
  `badge` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pricing_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `organization_category` varchar(255) DEFAULT NULL,
  `organization_category_description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `button_text` varchar(255) NOT NULL DEFAULT 'Start today →',
  `button_url` varchar(255) NOT NULL DEFAULT '/trial',
  `footnote` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pricing_plans_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`benefits`)),
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_features_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `robots_txts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `robots_txts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `search_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_queries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `results_count` int(11) NOT NULL DEFAULT 0,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search_queries_query_index` (`query`),
  KEY `search_queries_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sent_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sent_emails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `to_email` varchar(255) NOT NULL,
  `cc_emails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cc_emails`)),
  `bcc_emails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bcc_emails`)),
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `attachments_count` int(11) NOT NULL DEFAULT 0,
  `related_type` varchar(255) DEFAULT NULL,
  `related_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('sent','failed','pending') NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sent_emails_related_type_related_id_index` (`related_type`,`related_id`),
  KEY `sent_emails_user_id_sent_at_index` (`user_id`,`sent_at`),
  KEY `sent_emails_to_email_sent_at_index` (`to_email`,`sent_at`),
  KEY `sent_emails_status_index` (`status`),
  CONSTRAINT `sent_emails_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(16) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `service_grids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_grids` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_identifier` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cards`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_grids_section_identifier_unique` (`section_identifier`),
  KEY `service_grids_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_body` text NOT NULL,
  `long_body` longtext NOT NULL,
  `funnel_fase` enum('interesseer','overtuig','activeer','inspireer') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_canonical_url` varchar(255) DEFAULT NULL,
  `meta_robots` varchar(255) NOT NULL DEFAULT 'index,follow',
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `og_type` varchar(255) NOT NULL DEFAULT 'article',
  `twitter_card` varchar(255) NOT NULL DEFAULT 'summary_large_image',
  `twitter_title` varchar(255) DEFAULT NULL,
  `twitter_description` text DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `price_type` varchar(255) DEFAULT NULL,
  `price_description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `marketing_persona_id` bigint(20) unsigned DEFAULT NULL,
  `content_type_id` bigint(20) unsigned DEFAULT NULL,
  `primary_keyword` varchar(255) DEFAULT NULL,
  `secondary_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`secondary_keywords`)),
  `ai_briefing` text DEFAULT NULL,
  `seo_analysis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_analysis`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_service_category_id_foreign` (`service_category_id`),
  KEY `services_marketing_persona_id_foreign` (`marketing_persona_id`),
  KEY `services_content_type_id_foreign` (`content_type_id`),
  CONSTRAINT `services_content_type_id_foreign` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`),
  CONSTRAINT `services_marketing_persona_id_foreign` FOREIGN KEY (`marketing_persona_id`) REFERENCES `marketing_personas` (`id`),
  CONSTRAINT `services_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `session_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `live_session_id` bigint(20) unsigned NOT NULL,
  `organization` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `marketing_consent` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('registered','attended','no_show','cancelled') NOT NULL DEFAULT 'registered',
  `registered_at` timestamp NOT NULL,
  `attended_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_registrations_live_session_id_email_unique` (`live_session_id`,`email`),
  KEY `session_registrations_live_session_id_status_index` (`live_session_id`,`status`),
  KEY `session_registrations_email_index` (`email`),
  CONSTRAINT `session_registrations_live_session_id_foreign` FOREIGN KEY (`live_session_id`) REFERENCES `live_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `social_media_platforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_media_platforms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#000000',
  `api_credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`api_credentials`)),
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_media_platforms_slug_unique` (`slug`),
  KEY `social_media_platforms_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `social_media_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_media_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `social_media_platform_id` bigint(20) unsigned NOT NULL,
  `postable_type` varchar(255) NOT NULL,
  `postable_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `media_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`media_urls`)),
  `hashtags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hashtags`)),
  `external_post_id` varchar(255) DEFAULT NULL,
  `external_post_url` varchar(255) DEFAULT NULL,
  `status` enum('draft','scheduled','posted','failed') NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `error_message` text DEFAULT NULL,
  `engagement_stats` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`engagement_stats`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_media_posts_social_media_platform_id_foreign` (`social_media_platform_id`),
  KEY `social_media_posts_postable_type_postable_id_index` (`postable_type`,`postable_id`),
  KEY `social_media_posts_status_scheduled_at_index` (`status`,`scheduled_at`),
  CONSTRAINT `social_media_posts_social_media_platform_id_foreign` FOREIGN KEY (`social_media_platform_id`) REFERENCES `social_media_platforms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `social_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `solutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `solutions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anchor` varchar(255) NOT NULL,
  `nav_title` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `list_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`list_items`)),
  `link_text` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `testimonial_quote` text DEFAULT NULL,
  `testimonial_author` varchar(255) DEFAULT NULL,
  `testimonial_company` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_position` enum('left','right') NOT NULL DEFAULT 'right',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `slug` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `short_body` text DEFAULT NULL,
  `long_body` text DEFAULT NULL,
  `show_buttons` tinyint(1) NOT NULL DEFAULT 1,
  `button1_text` varchar(255) DEFAULT 'Start Gratis Proefperiode',
  `button1_url` varchar(255) DEFAULT '#',
  `button2_text` varchar(255) DEFAULT 'Neem Contact Op',
  `button2_url` varchar(255) DEFAULT '#',
  `show_knowledge_grid` tinyint(1) NOT NULL DEFAULT 0,
  `show_news_articles` tinyint(1) NOT NULL DEFAULT 0,
  `show_modules_header` tinyint(1) NOT NULL DEFAULT 0,
  `show_cta` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `solutions_anchor_unique` (`anchor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `static_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `static_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `body` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `static_pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sticky_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sticky_menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `link_type` enum('internal','external') NOT NULL DEFAULT 'internal',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sticky_menu_items_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscription_trials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_trials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_trials_subscription_id_foreign` (`subscription_id`),
  KEY `subscription_trials_user_id_foreign` (`user_id`),
  CONSTRAINT `subscription_trials_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_trials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `product_interest` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `preferred_contact_method` varchar(255) NOT NULL DEFAULT 'email',
  `preferred_demo_date` date DEFAULT NULL,
  `preferred_demo_time` varchar(255) DEFAULT NULL,
  `company_size` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `topic` text DEFAULT NULL,
  `status` enum('new','contacted','demo_scheduled','demo_completed','converted','rejected') NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `contacted_at` timestamp NULL DEFAULT NULL,
  `demo_scheduled_at` timestamp NULL DEFAULT NULL,
  `demo_completed_at` timestamp NULL DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'website',
  `utm_source` varchar(255) DEFAULT NULL,
  `utm_medium` varchar(255) DEFAULT NULL,
  `utm_campaign` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `newsletter_consent` tinyint(1) NOT NULL DEFAULT 0,
  `marketing_consent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demo_applications_status_created_at_index` (`status`,`created_at`),
  KEY `demo_applications_email_index` (`email`),
  KEY `demo_applications_company_name_index` (`company_name`),
  KEY `demo_applications_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `translation_key` varchar(255) NOT NULL,
  `locale` varchar(10) NOT NULL,
  `translation_value` text NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_translation_key_locale_group_name_unique` (`translation_key`,`locale`,`group_name`),
  KEY `translations_locale_is_active_index` (`locale`,`is_active`),
  KEY `translations_group_name_locale_index` (`group_name`,`locale`),
  KEY `translations_translation_key_index` (`translation_key`),
  KEY `translations_locale_index` (`locale`),
  KEY `translations_group_name_index` (`group_name`),
  KEY `translations_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `secondary_email` varchar(80) DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vacancies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacancies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `short_code` enum('BE','FE','MM','DO','QA','AI','HR','IT','PM') NOT NULL DEFAULT 'BE',
  `type` enum('full-time','part-time','contract','remote','project-based') NOT NULL DEFAULT 'full-time',
  `hours_per_week` varchar(255) DEFAULT NULL,
  `experience_level` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `salary_range` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `closing_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vacancies_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*M!999999\- enable the sandbox mode */ 
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_01_11_000000_create_translations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_06_04_235649_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_07_12_151218_create_blog_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_09_11_232319_create_social_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_09_14_020221_create_guests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_09_15_045644_create_footer_links_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_09_20_125257_create_activity_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_09_22_075432_create_sent_emails_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_09_22_092443_create_daily_page_views_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_09_22_092450_create_daily_stats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_09_27_062929_create_blogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_09_27_155731_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_09_28_000001_add_is_active_to_blog_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_09_28_210930_create_contacts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_09_28_232500_create_service_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_09_28_232501_create_services_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_09_28_233400_create_solutions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_09_29_000101_create_mail_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_09_29_040702_create_demo_applications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_09_29_045804_add_two_factor_authentication_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_09_30_035842_create_solutions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_09_30_043214_recreate_solutions_table_fresh',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_09_30_044501_create_pricing_plans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_09_30_044502_create_pricing_boosters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_09_30_044503_create_pricing_features_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_09_30_061309_create_email_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_10_01_113304_add_organization_categories_to_pricing_plans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_10_02_111051_create_faqs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_10_02_200132_rename_demo_applications_to_subscriptions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_10_02_200143_create_subscription_trials_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_10_02_215411_add_meta_keywords_to_content_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_10_03_090218_create_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_10_03_105500_add_meta_fields_to_solutions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_10_03_152633_create_marketing_personas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_10_03_152642_create_content_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_10_03_152646_create_marketing_testimonials_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_10_03_152702_create_product_features_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_10_03_152707_create_help_articles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_10_03_152711_create_case_studies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_10_03_152715_create_marketing_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_10_03_152720_add_marketing_fields_to_content_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_10_03_154125_cleanup_marketing_fields_from_blogs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_10_04_000000_create_pages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_10_05_012928_create_modules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_10_05_020937_create_features_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_10_05_020948_create_module_feature_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_10_05_023152_create_module_solution_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_10_05_185832_create_organization_names_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_10_05_235115_create_abouts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_10_10_030324_create_changelogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_10_10_033034_create_live_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_10_10_033040_create_presenters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_10_10_033045_create_session_registrations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_10_10_033051_create_live_session_presenter_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2025_10_10_041605_create_sticky_menu_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_10_10_050857_create_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2025_10_10_150905_add_video_to_changelogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2025_10_11_113154_create_social_media_platforms_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2025_10_11_113158_create_social_media_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2025_10_11_172032_create_section_headers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2025_10_13_010005_create_homepage_sections_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2025_10_13_022823_drop_section_headers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2025_10_13_024835_create_robots_txts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_10_13_120000_create_external_codes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2025_10_15_000000_create_legacy_pages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2025_10_15_071822_create_vacancies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2025_10_15_074825_create_job_applications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2025_10_16_064949_create_static_pages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2025_10_16_073437_add_api_status_to_changelogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2025_10_17_015516_create_service_grids_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2025_10_17_094219_create_knowledge_grids_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2025_10_18_055312_add_header_and_module_fields_to_solutions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2025_10_20_025924_add_topic_and_country_code_to_subscriptions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2025_10_22_023354_create_contact_forms_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2025_10_28_232249_create_search_queries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2025_11_17_012453_create_carousel_widgets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2025_11_25_143751_create_magic_builders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2025_12_01_222300_create_admin_theme_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2025_12_02_221546_create_doc_versions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2026_01_07_173945_create_contact_form_messages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2026_01_07_180119_create_intent_briefs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2026_01_07_180120_create_content_plans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2026_01_07_180121_create_content_plan_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2026_01_07_180122_create_content_performances_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2026_01_07_180123_add_content_plan_fields_to_blogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2026_01_07_181230_create_ai_service_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2026_02_04_152721_create_academy_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2026_02_04_152746_add_thumbnail_to_live_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2026_02_13_134314_add_meta_title_and_meta_description_to_blogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2026_02_18_120000_create_academy_chapters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2026_02_19_150000_drop_page_block_presets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2026_02_19_155900_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2026_02_19_160000_create_mega_menu_items_table',1);
