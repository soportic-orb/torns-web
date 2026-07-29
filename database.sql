-- MySQL dump 10.13  Distrib 8.4.4, for macos15 (arm64)
--
-- Host: 127.0.0.1    Database: stories
-- ------------------------------------------------------
-- Server version	8.4.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activations_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activations`
--

LOCK TABLES `activations` WRITE;
/*!40000 ALTER TABLE `activations` DISABLE KEYS */;
INSERT INTO `activations` VALUES (1,1,'bkhHvWaBGvZIikJGD598uAqlN3EjwJWB',1,'2026-07-22 00:26:38','2026-07-22 00:26:38','2026-07-22 00:26:38');
/*!40000 ALTER TABLE `activations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` datetime DEFAULT NULL,
  `location` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clicked` bigint NOT NULL DEFAULT '0',
  `order` int DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT '1',
  `tablet_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ads_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_adsense_slot_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ads_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
INSERT INTO `ads` VALUES (1,'Panel Ads','2031-07-22 00:00:00','panel-ads','4L0YESMK5YUL','banners/1.jpg','https://botble.com',0,1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40',1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads_translations`
--

DROP TABLE IF EXISTS `ads_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads_translations` (
  `lang_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ads_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tablet_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ads_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads_translations`
--

LOCK TABLES `ads_translations` WRITE;
/*!40000 ALTER TABLE `ads_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_action` tinyint(1) NOT NULL DEFAULT '0',
  `action_label` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_open_new_tab` tinyint(1) NOT NULL DEFAULT '0',
  `dismissible` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'Announcement 1','Cyber Monday: Save big on the Creative Cloud All Apps plan for individuals through 2 Dec',0,NULL,NULL,0,1,'2026-07-22 07:26:40',NULL,1,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(2,'Announcement 2','Students and teachers save a massive 71% on Creative Cloud All Apps',0,NULL,NULL,0,1,'2026-07-22 07:26:40',NULL,1,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(3,'Announcement 3','Black Friday and Cyber Monday 2023 Deals for Motion Designers, grab it now!',0,NULL,NULL,0,1,'2026-07-22 07:26:40',NULL,1,'2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements_translations`
--

DROP TABLE IF EXISTS `announcements_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements_translations` (
  `lang_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `announcements_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `action_label` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`announcements_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements_translations`
--

LOCK TABLES `announcements_translations` WRITE;
/*!40000 ALTER TABLE `announcements_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_histories`
--

DROP TABLE IF EXISTS `audit_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Botble\\ACL\\Models\\User',
  `module` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` longtext COLLATE utf8mb4_unicode_ci,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actor_id` bigint unsigned NOT NULL,
  `actor_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Botble\\ACL\\Models\\User',
  `reference_id` bigint unsigned NOT NULL,
  `reference_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_histories_user_id_index` (`user_id`),
  KEY `audit_histories_module_index` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_histories`
--

LOCK TABLES `audit_histories` WRITE;
/*!40000 ALTER TABLE `audit_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Botble\\ACL\\Models\\User',
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int unsigned NOT NULL DEFAULT '0',
  `is_featured` tinyint NOT NULL DEFAULT '0',
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_index` (`parent_id`),
  KEY `categories_status_index` (`status`),
  KEY `categories_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Uncategorized',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,0,1,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(2,'Travel',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(3,'Guides',2,NULL,'published',1,'Botble\\ACL\\Models\\User',NULL,0,0,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(4,'Destination',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(5,'Food',4,NULL,'published',1,'Botble\\ACL\\Models\\User',NULL,0,0,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(6,'Hotels',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(7,'Review',6,NULL,'published',1,'Botble\\ACL\\Models\\User',NULL,0,0,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(8,'Healthy',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2026-07-22 00:26:40','2026-07-22 00:26:40'),(9,'Lifestyle',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_translations`
--

DROP TABLE IF EXISTS `categories_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`categories_id`),
  KEY `idx_categories_trans_categories_id` (`categories_id`),
  KEY `idx_categories_trans_category_lang` (`categories_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_translations`
--

LOCK TABLES `categories_translations` WRITE;
/*!40000 ALTER TABLE `categories_translations` DISABLE KEYS */;
INSERT INTO `categories_translations` VALUES ('ar',1,'غير مصنف','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',2,'سفر','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',3,'أدلة','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',4,'وجهات','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',5,'طعام','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',6,'فنادق','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',7,'مراجعات','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',8,'صحة','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('ar',9,'نمط الحياة','استكشف مجموعتنا من المقالات والرؤى في هذه الفئة.'),('fr',1,'Non classé','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',2,'Voyage','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',3,'Guides','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',4,'Destination','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',5,'Gastronomie','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',6,'Hôtels','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',7,'Avis','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',8,'Santé','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('fr',9,'Mode de vie','Explorez notre collection d\'articles et de perspectives dans cette catégorie.'),('id',1,'Tidak Berkategori','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',2,'Perjalanan','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',3,'Panduan','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',4,'Destinasi','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',5,'Makanan','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',6,'Hotel','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',7,'Ulasan','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',8,'Kesehatan','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('id',9,'Gaya Hidup','Jelajahi koleksi artikel dan wawasan kami dalam kategori ini.'),('tr',1,'Kategorisiz','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',2,'Seyahat','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',3,'Rehberler','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',4,'Destinasyon','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',5,'Yemek','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',6,'Oteller','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',7,'İnceleme','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',8,'Sağlık','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('tr',9,'Yaşam Tarzı','Bu kategorideki makale ve içgörü koleksiyonumuzu keşfedin.'),('vi',1,'Chưa phân loại','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',2,'Du lịch','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',3,'Hướng dẫn','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',4,'Điểm đến','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',5,'Ẩm thực','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',6,'Khách sạn','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',7,'Đánh giá','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',8,'Sức khỏe','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.'),('vi',9,'Phong cách sống','Khám phá bộ sưu tập bài viết và thông tin chi tiết trong danh mục này.');
/*!40000 ALTER TABLE `categories_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_field_options`
--

DROP TABLE IF EXISTS `contact_custom_field_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_field_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `custom_field_id` bigint unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_field_options`
--

LOCK TABLES `contact_custom_field_options` WRITE;
/*!40000 ALTER TABLE `contact_custom_field_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_field_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_field_options_translations`
--

DROP TABLE IF EXISTS `contact_custom_field_options_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_field_options_translations` (
  `contact_custom_field_options_id` bigint unsigned NOT NULL,
  `lang_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`contact_custom_field_options_id`),
  KEY `idx_contact_cfo_trans_cfo_id` (`contact_custom_field_options_id`),
  KEY `idx_contact_cfo_trans_cfo_lang` (`contact_custom_field_options_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_field_options_translations`
--

LOCK TABLES `contact_custom_field_options_translations` WRITE;
/*!40000 ALTER TABLE `contact_custom_field_options_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_field_options_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_fields`
--

DROP TABLE IF EXISTS `contact_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '999',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_fields`
--

LOCK TABLES `contact_custom_fields` WRITE;
/*!40000 ALTER TABLE `contact_custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_fields_translations`
--

DROP TABLE IF EXISTS `contact_custom_fields_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_fields_translations` (
  `contact_custom_fields_id` bigint unsigned NOT NULL,
  `lang_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`contact_custom_fields_id`),
  KEY `idx_contact_cf_trans_cf_id` (`contact_custom_fields_id`),
  KEY `idx_contact_cf_trans_cf_lang` (`contact_custom_fields_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_fields_translations`
--

LOCK TABLES `contact_custom_fields_translations` WRITE;
/*!40000 ALTER TABLE `contact_custom_fields_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_fields_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_replies`
--

DROP TABLE IF EXISTS `contact_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_replies`
--

LOCK TABLES `contact_replies` WRITE;
/*!40000 ALTER TABLE `contact_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_fields` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'John Smith','john.smith@example.com','(555) 123-4567','123 Main Street, New York, NY 10001','Question about your services','I would like to learn more about your services and how they can benefit my business. Could you please send me additional information or schedule a call to discuss further?',NULL,'read','2026-07-22 00:26:39','2026-07-22 00:26:39'),(2,'Emily Johnson','emily.johnson@example.com','(555) 234-5678','456 Oak Avenue, Los Angeles, CA 90001','Partnership inquiry','We are interested in exploring a potential partnership with your company. Our organization has been looking for reliable partners in this industry, and we believe there could be mutual benefits.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39'),(3,'Michael Brown','michael.brown@example.com','(555) 345-6789','789 Pine Road, Chicago, IL 60601','Request for more information','I recently came across your website and was impressed by what I saw. I have a few questions about your offerings and would appreciate if someone could get back to me at their earliest convenience.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39'),(4,'Sarah Davis','sarah.davis@example.com','(555) 456-7890','321 Elm Boulevard, Houston, TX 77001','Feedback on recent experience','Thank you for the excellent service I received recently. The team was professional and helpful throughout the entire process. I wanted to share my positive experience.',NULL,'read','2026-07-22 00:26:39','2026-07-22 00:26:39'),(5,'David Wilson','david.wilson@example.com','(555) 567-8901','654 Maple Drive, Phoenix, AZ 85001','Technical support needed','I am experiencing some issues with the product I purchased and would like to request technical assistance. The problem started a few days ago and I have tried several troubleshooting steps.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39'),(6,'Jessica Taylor','jessica.taylor@example.com','(555) 678-9012','987 Cedar Lane, Philadelphia, PA 19101','Pricing and availability','Could you please provide me with detailed pricing information for your premium services? I am comparing different options and would like to make an informed decision.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39'),(7,'Christopher Anderson','chris.anderson@example.com','(555) 789-0123','147 Birch Court, San Antonio, TX 78201','General inquiry','I have a general question about your company policies and procedures. It would be great if you could clarify some points that I found on your website.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39'),(8,'Amanda Thomas','amanda.thomas@example.com','(555) 890-1234','258 Walnut Street, San Diego, CA 92101','Collaboration opportunity','Our team is looking for collaboration opportunities in this field. We believe that working together could lead to innovative solutions and mutual growth.',NULL,'read','2026-07-22 00:26:39','2026-07-22 00:26:39'),(9,'Matthew Martinez','matt.martinez@example.com','(555) 901-2345','369 Spruce Way, Dallas, TX 75201','Product question','I purchased one of your products last month and have a question about its features. The documentation was helpful but I need some clarification on a specific functionality.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39'),(10,'Ashley Garcia','ashley.garcia@example.com','(555) 012-3456','741 Willow Place, San Jose, CA 95101','Service feedback','I wanted to provide some feedback on the service I received. Overall, it was a good experience, but there are a few areas where I think improvements could be made.',NULL,'unread','2026-07-22 00:26:39','2026-07-22 00:26:39');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_widget_settings`
--

DROP TABLE IF EXISTS `dashboard_widget_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_widget_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `widget_id` bigint unsigned NOT NULL,
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dashboard_widget_settings_user_id_index` (`user_id`),
  KEY `dashboard_widget_settings_widget_id_index` (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_widget_settings`
--

LOCK TABLES `dashboard_widget_settings` WRITE;
/*!40000 ALTER TABLE `dashboard_widget_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashboard_widget_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_widgets`
--

DROP TABLE IF EXISTS `dashboard_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_widgets`
--

LOCK TABLES `dashboard_widgets` WRITE;
/*!40000 ALTER TABLE `dashboard_widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashboard_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_tokens`
--

DROP TABLE IF EXISTS `device_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_tokens_token_unique` (`token`),
  KEY `device_tokens_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `device_tokens_platform_is_active_index` (`platform`,`is_active`),
  KEY `device_tokens_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_tokens`
--

LOCK TABLES `device_tokens` WRITE;
/*!40000 ALTER TABLE `device_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fob_comments`
--

DROP TABLE IF EXISTS `fob_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fob_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reply_to` bigint unsigned DEFAULT NULL,
  `author_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fob_comments_author_type_author_id_index` (`author_type`,`author_id`),
  KEY `fob_comments_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `fob_comments_reply_to_index` (`reply_to`),
  KEY `fob_comments_reference_url_index` (`reference_url`),
  KEY `fob_comments_status_index` (`status`),
  KEY `fob_comments_reference_status_index` (`reference_type`,`reference_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fob_comments`
--

LOCK TABLES `fob_comments` WRITE;
/*!40000 ALTER TABLE `fob_comments` DISABLE KEYS */;
INSERT INTO `fob_comments` VALUES (1,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','This is really helpful, thank you!','approved','192.168.1.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-14 00:26:40','2026-07-22 00:26:40'),(2,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I found this article to be quite informative.','approved','192.168.1.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-18 00:26:40','2026-07-22 00:26:40'),(3,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',13,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','Wow, I never knew about this before!','approved','192.168.1.3','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-30 00:26:40','2026-07-22 00:26:40'),(4,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',12,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','Great job on explaining such a complex topic.','approved','192.168.1.4','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-06 00:26:40','2026-07-22 00:26:40'),(5,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','I have a question about the third paragraph.','approved','192.168.1.5','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-28 00:26:40','2026-07-22 00:26:40'),(6,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','This article changed my perspective entirely.','approved','192.168.1.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-10 00:26:40','2026-07-22 00:26:40'),(7,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I appreciate the effort you put into this.','approved','192.168.1.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-23 00:26:40','2026-07-22 00:26:40'),(8,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',6,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','This is exactly what I was looking for, thank you!','approved','192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-15 00:26:40','2026-07-22 00:26:40'),(9,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','I disagree with some points mentioned here, though.','approved','192.168.1.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-14 00:26:40','2026-07-22 00:26:40'),(10,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','Could you provide more examples to illustrate your point?','approved','192.168.1.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-15 00:26:40','2026-07-22 00:26:40'),(11,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',7,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','I wish there were more articles like this out there.','approved','192.168.1.11','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-08 00:26:40','2026-07-22 00:26:40'),(12,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'m bookmarking this for future reference.','approved','192.168.1.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-06 00:26:40','2026-07-22 00:26:40'),(13,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','I\'ve shared this with my friends, they loved it!','approved','192.168.1.13','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-02 00:26:40','2026-07-22 00:26:40'),(14,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','This article is a must-read for everyone interested in the topic.','approved','192.168.1.14','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-06 00:26:40','2026-07-22 00:26:40'),(15,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','Thank you for shedding light on this important issue.','approved','192.168.1.15','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-09 00:26:40','2026-07-22 00:26:40'),(16,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',4,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','I\'ve been searching for information on this topic, glad I found this article.','approved','192.168.1.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-09 00:26:40','2026-07-22 00:26:40'),(17,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I\'m blown away by the insights shared in this article.','approved','192.168.1.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-17 00:26:40','2026-07-22 00:26:40'),(18,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','This article tackles a complex topic with clarity.','approved','192.168.1.18','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-08 00:26:40','2026-07-22 00:26:40'),(19,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','I\'m going to reflect on the ideas presented in this article.','approved','192.168.1.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-12 00:26:40','2026-07-22 00:26:40'),(20,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','The author\'s passion for the subject shines through in this article.','approved','192.168.1.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-11 00:26:40','2026-07-22 00:26:40'),(21,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','This article challenged my preconceptions in a thought-provoking way.','approved','192.168.1.21','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-09 00:26:40','2026-07-22 00:26:40'),(22,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'ve added this article to my reading list, it\'s worth revisiting.','approved','192.168.1.22','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-17 00:26:40','2026-07-22 00:26:40'),(23,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','This article offers practical advice that I can apply in real life.','approved','192.168.1.23','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-21 00:26:40','2026-07-22 00:26:40'),(24,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','I\'m going to recommend this article to my study group.','approved','192.168.1.24','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-30 00:26:40','2026-07-22 00:26:40'),(25,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','The examples provided really helped me understand the concept better.','approved','192.168.1.25','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-30 00:26:40','2026-07-22 00:26:40'),(26,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','I resonate with the ideas presented here.','approved','192.168.1.26','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-30 00:26:40','2026-07-22 00:26:40'),(27,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','This article made me think critically about the topic.','approved','192.168.1.27','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-05 00:26:40','2026-07-22 00:26:40'),(28,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','I\'ll definitely come back to this article for reference.','approved','192.168.1.28','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-04 00:26:40','2026-07-22 00:26:40'),(29,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','I\'ve shared this on social media, it\'s too good not to share.','approved','192.168.1.29','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-12 00:26:40','2026-07-22 00:26:40'),(30,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','This article presents a balanced view on a controversial topic.','approved','192.168.1.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-16 00:26:40','2026-07-22 00:26:40'),(31,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',6,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','I\'m glad I stumbled upon this article, it\'s a gem.','approved','192.168.1.31','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-06 00:26:40','2026-07-22 00:26:40'),(32,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',13,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'ve been struggling with this, your article helped a lot.','approved','192.168.1.32','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-23 00:26:40','2026-07-22 00:26:40'),(33,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','I\'ve learned something new today, thanks to this article.','approved','192.168.1.33','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-21 00:26:40','2026-07-22 00:26:40'),(34,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','Kudos to the author for a well-researched piece.','approved','192.168.1.34','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-24 00:26:40','2026-07-22 00:26:40'),(35,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','I\'m impressed by the depth of knowledge demonstrated here.','approved','192.168.1.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-16 00:26:40','2026-07-22 00:26:40'),(36,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','This article challenged my assumptions in a good way.','approved','192.168.1.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-09 00:26:40','2026-07-22 00:26:40'),(37,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',12,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I\'ve shared this with my colleagues, it\'s worth discussing.','approved','192.168.1.37','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-09 00:26:40','2026-07-22 00:26:40'),(38,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','The information presented here is very valuable.','approved','192.168.1.38','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-06 00:26:40','2026-07-22 00:26:40'),(39,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','You have a talent for explaining complex topics clearly.','approved','192.168.1.39','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-28 00:26:40','2026-07-22 00:26:40'),(40,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','I\'m inspired to learn more about this after reading your article.','approved','192.168.1.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-14 00:26:40','2026-07-22 00:26:40'),(41,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','This article deserves wider recognition.','approved','192.168.1.41','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-28 00:26:40','2026-07-22 00:26:40'),(42,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',2,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'m grateful for the insights shared in this piece.','approved','192.168.1.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-25 00:26:40','2026-07-22 00:26:40'),(43,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','The author presents a balanced view on a controversial topic.','approved','192.168.1.43','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-06-26 00:26:40','2026-07-22 00:26:40'),(44,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','I\'m glad I stumbled upon this article, it\'s','approved','192.168.1.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-07 00:26:40','2026-07-22 00:26:40'),(45,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','I\'ve been searching for information on this topic, glad I found this article. It\'s incredibly insightful and provides a comprehensive overview of the subject matter. I appreciate the effort put into researching and writing this piece. It\'s truly eye-opening and has given me a new perspective. Thank you for sharing your knowledge with us!','approved','192.168.1.45','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-21 00:26:40','2026-07-22 00:26:40'),(46,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','This article is a masterpiece! It dives deep into the topic and offers valuable insights that are both thought-provoking and enlightening. The author\'s expertise is evident throughout, making it a compelling read from start to finish. I\'ll definitely be coming back to this for reference in the future.','approved','192.168.1.46','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-06 00:26:40','2026-07-22 00:26:40'),(47,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I\'m amazed by the depth of analysis in this article. It covers a wide range of aspects related to the topic, providing a comprehensive understanding. The clarity of explanation is commendable, making complex concepts easy to grasp. This article has enriched my understanding and sparked further curiosity. Kudos to the author!','approved','192.168.1.47','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2026-07-02 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `fob_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (1,'Perfect','A curated selection of photographs that evoke emotion and spark inspiration.',1,0,'galleries/1.jpg',1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(2,'New Day','A curated selection of photographs that evoke emotion and spark inspiration.',1,0,'galleries/2.jpg',1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(3,'Happy Day','A stunning collection of carefully curated images that capture the essence of beauty and creativity.',1,0,'galleries/3.jpg',1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(4,'Nature','An inspiring gallery featuring exceptional photography and visual storytelling.',1,0,'galleries/4.jpg',1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(5,'Morning','A stunning collection of carefully curated images that capture the essence of beauty and creativity.',1,0,'galleries/5.jpg',1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(6,'Photography','Witness the beauty of the world through these carefully selected images.',1,0,'galleries/6.jpg',1,'published','2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries_translations`
--

DROP TABLE IF EXISTS `galleries_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `galleries_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`galleries_id`),
  KEY `idx_galleries_trans_galleries_id` (`galleries_id`),
  KEY `idx_galleries_trans_gallery_lang` (`galleries_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries_translations`
--

LOCK TABLES `galleries_translations` WRITE;
/*!40000 ALTER TABLE `galleries_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_meta`
--

DROP TABLE IF EXISTS `gallery_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `images` text COLLATE utf8mb4_unicode_ci,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_meta_reference_id_index` (`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_meta`
--

LOCK TABLES `gallery_meta` WRITE;
/*!40000 ALTER TABLE `gallery_meta` DISABLE KEYS */;
INSERT INTO `gallery_meta` VALUES (1,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"A stunning collection of carefully curated images that capture the essence of beauty and creativity.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Explore breathtaking visuals that tell a story and inspire the imagination.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"A vibrant showcase of artistry and passion through captivating photography.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Discover moments frozen in time, each image revealing a unique perspective.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"An inspiring gallery featuring exceptional photography and visual storytelling.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Beautiful imagery that celebrates the art of photography and creative expression.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"A curated selection of photographs that evoke emotion and spark inspiration.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Witness the beauty of the world through these carefully selected images.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"An artistic journey through light, color, and composition in visual form.\"}]',1,'Botble\\Gallery\\Models\\Gallery','2026-07-22 00:26:40','2026-07-22 00:26:40'),(2,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"A stunning collection of carefully curated images that capture the essence of beauty and creativity.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Explore breathtaking visuals that tell a story and inspire the imagination.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"A vibrant showcase of artistry and passion through captivating photography.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Discover moments frozen in time, each image revealing a unique perspective.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"An inspiring gallery featuring exceptional photography and visual storytelling.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Beautiful imagery that celebrates the art of photography and creative expression.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"A curated selection of photographs that evoke emotion and spark inspiration.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Witness the beauty of the world through these carefully selected images.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"An artistic journey through light, color, and composition in visual form.\"}]',2,'Botble\\Gallery\\Models\\Gallery','2026-07-22 00:26:40','2026-07-22 00:26:40'),(3,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"A stunning collection of carefully curated images that capture the essence of beauty and creativity.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Explore breathtaking visuals that tell a story and inspire the imagination.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"A vibrant showcase of artistry and passion through captivating photography.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Discover moments frozen in time, each image revealing a unique perspective.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"An inspiring gallery featuring exceptional photography and visual storytelling.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Beautiful imagery that celebrates the art of photography and creative expression.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"A curated selection of photographs that evoke emotion and spark inspiration.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Witness the beauty of the world through these carefully selected images.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"An artistic journey through light, color, and composition in visual form.\"}]',3,'Botble\\Gallery\\Models\\Gallery','2026-07-22 00:26:40','2026-07-22 00:26:40'),(4,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"A stunning collection of carefully curated images that capture the essence of beauty and creativity.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Explore breathtaking visuals that tell a story and inspire the imagination.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"A vibrant showcase of artistry and passion through captivating photography.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Discover moments frozen in time, each image revealing a unique perspective.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"An inspiring gallery featuring exceptional photography and visual storytelling.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Beautiful imagery that celebrates the art of photography and creative expression.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"A curated selection of photographs that evoke emotion and spark inspiration.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Witness the beauty of the world through these carefully selected images.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"An artistic journey through light, color, and composition in visual form.\"}]',4,'Botble\\Gallery\\Models\\Gallery','2026-07-22 00:26:40','2026-07-22 00:26:40'),(5,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"A stunning collection of carefully curated images that capture the essence of beauty and creativity.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Explore breathtaking visuals that tell a story and inspire the imagination.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"A vibrant showcase of artistry and passion through captivating photography.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Discover moments frozen in time, each image revealing a unique perspective.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"An inspiring gallery featuring exceptional photography and visual storytelling.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Beautiful imagery that celebrates the art of photography and creative expression.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"A curated selection of photographs that evoke emotion and spark inspiration.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Witness the beauty of the world through these carefully selected images.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"An artistic journey through light, color, and composition in visual form.\"}]',5,'Botble\\Gallery\\Models\\Gallery','2026-07-22 00:26:40','2026-07-22 00:26:40'),(6,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"A stunning collection of carefully curated images that capture the essence of beauty and creativity.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Explore breathtaking visuals that tell a story and inspire the imagination.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"A vibrant showcase of artistry and passion through captivating photography.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Discover moments frozen in time, each image revealing a unique perspective.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"An inspiring gallery featuring exceptional photography and visual storytelling.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Beautiful imagery that celebrates the art of photography and creative expression.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"A curated selection of photographs that evoke emotion and spark inspiration.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Witness the beauty of the world through these carefully selected images.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"An artistic journey through light, color, and composition in visual form.\"}]',6,'Botble\\Gallery\\Models\\Gallery','2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `gallery_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_meta_translations`
--

DROP TABLE IF EXISTS `gallery_meta_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_meta_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_meta_id` bigint unsigned NOT NULL,
  `images` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`gallery_meta_id`),
  KEY `idx_gallery_meta_trans_gm_id` (`gallery_meta_id`),
  KEY `idx_gallery_meta_trans_gm_lang` (`gallery_meta_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_meta_translations`
--

LOCK TABLES `gallery_meta_translations` WRITE;
/*!40000 ALTER TABLE `gallery_meta_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_meta_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language_meta`
--

DROP TABLE IF EXISTS `language_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language_meta` (
  `lang_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lang_meta_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_meta_origin` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`lang_meta_id`),
  KEY `language_meta_reference_id_index` (`reference_id`),
  KEY `meta_code_index` (`lang_meta_code`),
  KEY `meta_origin_index` (`lang_meta_origin`),
  KEY `meta_reference_type_index` (`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language_meta`
--

LOCK TABLES `language_meta` WRITE;
/*!40000 ALTER TABLE `language_meta` DISABLE KEYS */;
INSERT INTO `language_meta` VALUES (1,'en_US','ac80a22b54c1befcd0869ff7cfaaeea5',1,'Botble\\Menu\\Models\\MenuLocation'),(2,'en_US','f93fa157af0074d346abd75dd549cfb2',1,'Botble\\Menu\\Models\\Menu'),(3,'ar','ac80a22b54c1befcd0869ff7cfaaeea5',2,'Botble\\Menu\\Models\\MenuLocation'),(4,'ar','f93fa157af0074d346abd75dd549cfb2',2,'Botble\\Menu\\Models\\Menu'),(5,'vi','ac80a22b54c1befcd0869ff7cfaaeea5',3,'Botble\\Menu\\Models\\MenuLocation'),(6,'vi','f93fa157af0074d346abd75dd549cfb2',3,'Botble\\Menu\\Models\\Menu'),(7,'fr','ac80a22b54c1befcd0869ff7cfaaeea5',4,'Botble\\Menu\\Models\\MenuLocation'),(8,'fr','f93fa157af0074d346abd75dd549cfb2',4,'Botble\\Menu\\Models\\Menu'),(9,'id','ac80a22b54c1befcd0869ff7cfaaeea5',5,'Botble\\Menu\\Models\\MenuLocation'),(10,'id','f93fa157af0074d346abd75dd549cfb2',5,'Botble\\Menu\\Models\\Menu'),(11,'tr','ac80a22b54c1befcd0869ff7cfaaeea5',6,'Botble\\Menu\\Models\\MenuLocation'),(12,'tr','f93fa157af0074d346abd75dd549cfb2',6,'Botble\\Menu\\Models\\Menu'),(13,'en_US','018fa61982b005f94ceff492b6445b1d',7,'Botble\\Menu\\Models\\Menu'),(14,'ar','018fa61982b005f94ceff492b6445b1d',8,'Botble\\Menu\\Models\\Menu'),(15,'vi','018fa61982b005f94ceff492b6445b1d',9,'Botble\\Menu\\Models\\Menu'),(16,'fr','018fa61982b005f94ceff492b6445b1d',10,'Botble\\Menu\\Models\\Menu'),(17,'id','018fa61982b005f94ceff492b6445b1d',11,'Botble\\Menu\\Models\\Menu'),(18,'tr','018fa61982b005f94ceff492b6445b1d',12,'Botble\\Menu\\Models\\Menu');
/*!40000 ALTER TABLE `language_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `lang_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_locale` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_flag` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `lang_order` int NOT NULL DEFAULT '0',
  `lang_is_rtl` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  KEY `lang_locale_index` (`lang_locale`),
  KEY `lang_code_index` (`lang_code`),
  KEY `lang_is_default_index` (`lang_is_default`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'English','en','en_US','us',1,0,0),(2,'Arabic','ar','ar','sa',0,1,1),(3,'Tiếng Việt','vi','vi','vn',0,2,0),(4,'Français','fr','fr','fr',0,3,0),(5,'Bahasa Indonesia','id','id','id',0,4,0),(6,'Türkçe','tr','tr','tr',0,5,0);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_files`
--

DROP TABLE IF EXISTS `media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `folder_id` bigint unsigned NOT NULL DEFAULT '0',
  `mime_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `visibility` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `media_files_user_id_index` (`user_id`),
  KEY `media_files_index` (`folder_id`,`user_id`,`created_at`),
  KEY `media_files_folder_deleted_name` (`folder_id`,`deleted_at`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_files`
--

LOCK TABLES `media_files` WRITE;
/*!40000 ALTER TABLE `media_files` DISABLE KEYS */;
INSERT INTO `media_files` VALUES (2,0,'default','default',2,'image/jpeg',1585,'users/default.jpg','[]','2026-07-22 00:26:38','2026-07-22 00:26:38',NULL,'public'),(3,0,'author','author',3,'image/jpeg',17163,'general/author.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(4,0,'favicon','favicon',3,'image/png',734,'general/favicon.png','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(5,0,'featured','featured',3,'image/png',7878,'general/featured.png','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(6,0,'logo-white','logo-white',3,'image/png',1620,'general/logo-white.png','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(7,0,'logo','logo',3,'image/png',1732,'general/logo.png','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(8,0,'1','1',4,'image/jpeg',9670,'news/1.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(9,0,'10','10',4,'image/jpeg',9670,'news/10.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(10,0,'11','11',4,'image/jpeg',9670,'news/11.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(11,0,'12','12',4,'image/jpeg',9670,'news/12.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(12,0,'13','13',4,'image/jpeg',9670,'news/13.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(13,0,'14','14',4,'image/jpeg',9670,'news/14.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(14,0,'15','15',4,'image/jpeg',9670,'news/15.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(15,0,'16','16',4,'image/jpeg',9670,'news/16.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(16,0,'17','17',4,'image/jpeg',9670,'news/17.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(17,0,'18','18',4,'image/jpeg',9670,'news/18.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(18,0,'19','19',4,'image/jpeg',9670,'news/19.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(19,0,'2','2',4,'image/jpeg',9670,'news/2.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(20,0,'3','3',4,'image/jpeg',9670,'news/3.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(21,0,'4','4',4,'image/jpeg',9670,'news/4.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(22,0,'5','5',4,'image/jpeg',9670,'news/5.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(23,0,'6','6',4,'image/jpeg',9670,'news/6.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(24,0,'7','7',4,'image/jpeg',9670,'news/7.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(25,0,'8','8',4,'image/jpeg',9670,'news/8.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(26,0,'9','9',4,'image/jpeg',9670,'news/9.jpg','[]','2026-07-22 00:26:39','2026-07-22 00:26:39',NULL,'public'),(27,0,'1','1',5,'image/jpeg',9670,'categories/1.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(28,0,'2','2',5,'image/jpeg',9670,'categories/2.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(29,0,'3','3',5,'image/jpeg',9670,'categories/3.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(30,0,'4','4',5,'image/jpeg',9670,'categories/4.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(31,0,'5','5',5,'image/jpeg',9670,'categories/5.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(32,0,'6','6',5,'image/jpeg',9670,'categories/6.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(33,0,'7','7',5,'image/jpeg',9670,'categories/7.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(34,0,'1','1',6,'image/jpeg',9670,'galleries/1.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(35,0,'10','10',6,'image/jpeg',9670,'galleries/10.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(36,0,'2','2',6,'image/jpeg',9670,'galleries/2.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(37,0,'3','3',6,'image/jpeg',9670,'galleries/3.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(38,0,'4','4',6,'image/jpeg',9670,'galleries/4.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(39,0,'5','5',6,'image/jpeg',9670,'galleries/5.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(40,0,'6','6',6,'image/jpeg',9670,'galleries/6.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(41,0,'7','7',6,'image/jpeg',9670,'galleries/7.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(42,0,'8','8',6,'image/jpeg',9670,'galleries/8.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(43,0,'9','9',6,'image/jpeg',9670,'galleries/9.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(44,0,'1','1',7,'image/jpeg',6617,'banners/1.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(45,0,'2','2',7,'image/jpeg',6617,'banners/2.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public'),(46,0,'3','3',7,'image/jpeg',6617,'banners/3.jpg','[]','2026-07-22 00:26:40','2026-07-22 00:26:40',NULL,'public');
/*!40000 ALTER TABLE `media_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_folder_permissions`
--

DROP TABLE IF EXISTS `media_folder_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folder_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `folder_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `permission` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'view',
  `granted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_folder_permissions_folder_id_user_id_unique` (`folder_id`,`user_id`),
  KEY `media_folder_permissions_folder_id_index` (`folder_id`),
  KEY `media_folder_permissions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_folder_permissions`
--

LOCK TABLES `media_folder_permissions` WRITE;
/*!40000 ALTER TABLE `media_folder_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_folder_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_folders`
--

DROP TABLE IF EXISTS `media_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_folders_user_id_index` (`user_id`),
  KEY `media_folders_index` (`parent_id`,`user_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_folders`
--

LOCK TABLES `media_folders` WRITE;
/*!40000 ALTER TABLE `media_folders` DISABLE KEYS */;
INSERT INTO `media_folders` VALUES (2,0,'users',NULL,'users',0,'2026-07-22 00:26:38','2026-07-22 00:26:38',NULL),(3,0,'general',NULL,'general',0,'2026-07-22 00:26:39','2026-07-22 00:26:39',NULL),(4,0,'news',NULL,'news',0,'2026-07-22 00:26:39','2026-07-22 00:26:39',NULL),(5,0,'categories',NULL,'categories',0,'2026-07-22 00:26:39','2026-07-22 00:26:39',NULL),(6,0,'galleries',NULL,'galleries',0,'2026-07-22 00:26:40','2026-07-22 00:26:40',NULL),(7,0,'banners',NULL,'banners',0,'2026-07-22 00:26:40','2026-07-22 00:26:40',NULL);
/*!40000 ALTER TABLE `media_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_settings`
--

DROP TABLE IF EXISTS `media_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `media_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_settings`
--

LOCK TABLES `media_settings` WRITE;
/*!40000 ALTER TABLE `media_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_locations`
--

DROP TABLE IF EXISTS `menu_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_locations_menu_id_created_at_index` (`menu_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_locations`
--

LOCK TABLES `menu_locations` WRITE;
/*!40000 ALTER TABLE `menu_locations` DISABLE KEYS */;
INSERT INTO `menu_locations` VALUES (1,1,'main-menu','2026-07-22 00:26:39','2026-07-22 00:26:39'),(2,2,'main-menu','2026-07-22 00:26:39','2026-07-22 00:26:39'),(3,3,'main-menu','2026-07-22 00:26:39','2026-07-22 00:26:39'),(4,4,'main-menu','2026-07-22 00:26:39','2026-07-22 00:26:39'),(5,5,'main-menu','2026-07-22 00:26:39','2026-07-22 00:26:39'),(6,6,'main-menu','2026-07-22 00:26:39','2026-07-22 00:26:39');
/*!40000 ALTER TABLE `menu_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_nodes`
--

DROP TABLE IF EXISTS `menu_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_nodes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_font` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `has_child` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_nodes_menu_id_index` (`menu_id`),
  KEY `menu_nodes_parent_id_index` (`parent_id`),
  KEY `reference_id` (`reference_id`),
  KEY `reference_type` (`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_nodes`
--

LOCK TABLES `menu_nodes` WRITE;
/*!40000 ALTER TABLE `menu_nodes` DISABLE KEYS */;
INSERT INTO `menu_nodes` VALUES (1,1,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'Home',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(2,1,1,NULL,NULL,'/',NULL,0,'Home default',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(3,1,1,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'Home 2',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(4,1,1,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'Home 3',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(5,1,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'Travel',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(6,1,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'Destination',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(7,1,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Hotels',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(8,1,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'Lifestyle',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(9,1,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'Blog',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(10,1,9,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'Grid layout',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(11,1,9,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'List layout',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(12,1,9,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'Big layout',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(13,1,0,NULL,NULL,'/galleries',NULL,6,'Galleries',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(14,1,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'Contact',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(15,2,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'الرئيسية',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(16,2,15,NULL,NULL,'/',NULL,0,'الرئيسية الافتراضية',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(17,2,15,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'الرئيسية 2',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(18,2,15,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'الرئيسية 3',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(19,2,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'سفر',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(20,2,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'وجهات',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(21,2,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'فنادق',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(22,2,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'نمط الحياة',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(23,2,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'المدونة',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(24,2,23,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'تخطيط شبكي',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(25,2,23,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'تخطيط قائمة',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(26,2,23,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'تخطيط كبير',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(27,2,0,NULL,NULL,'/galleries',NULL,6,'المعارض',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(28,2,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'اتصل بنا',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(29,3,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'Trang chủ',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(30,3,29,NULL,NULL,'/',NULL,0,'Trang chủ mặc định',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(31,3,29,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'Trang chủ 2',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(32,3,29,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'Trang chủ 3',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(33,3,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'Du lịch',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(34,3,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'Điểm đến',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(35,3,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Khách sạn',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(36,3,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'Phong cách sống',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(37,3,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'Blog',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(38,3,37,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'Bố cục lưới',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(39,3,37,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'Bố cục danh sách',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(40,3,37,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'Bố cục lớn',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(41,3,0,NULL,NULL,'/galleries',NULL,6,'Thư viện ảnh',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(42,3,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'Liên hệ',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(43,4,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'Accueil',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(44,4,43,NULL,NULL,'/',NULL,0,'Accueil par défaut',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(45,4,43,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'Accueil 2',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(46,4,43,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'Accueil 3',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(47,4,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'Voyage',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(48,4,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'Destination',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(49,4,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Hôtels',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(50,4,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'Mode de vie',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(51,4,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'Blog',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(52,4,51,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'Grille',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(53,4,51,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'Liste',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(54,4,51,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'Grand format',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(55,4,0,NULL,NULL,'/galleries',NULL,6,'Galeries',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(56,4,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'Contact',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(57,5,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'Beranda',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(58,5,57,NULL,NULL,'/',NULL,0,'Beranda default',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(59,5,57,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'Beranda 2',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(60,5,57,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'Beranda 3',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(61,5,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'Perjalanan',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(62,5,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'Destinasi',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(63,5,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Hotel',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(64,5,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'Gaya Hidup',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(65,5,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'Blog',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(66,5,65,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'Tata letak grid',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(67,5,65,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'Tata letak daftar',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(68,5,65,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'Tata letak besar',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(69,5,0,NULL,NULL,'/galleries',NULL,6,'Galeri',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(70,5,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'Kontak',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(71,6,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'Ana Sayfa',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(72,6,71,NULL,NULL,'/',NULL,0,'Varsayılan Ana Sayfa',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(73,6,71,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'Ana Sayfa 2',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(74,6,71,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'Ana Sayfa 3',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(75,6,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'Seyahat',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(76,6,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'Destinasyon',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(77,6,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Oteller',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(78,6,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'Yaşam Tarzı',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(79,6,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'Blog',NULL,'_self',1,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(80,6,79,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'Izgara düzeni',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(81,6,79,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'Liste düzeni',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(82,6,79,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'Büyük düzen',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(83,6,0,NULL,NULL,'/galleries',NULL,6,'Galeriler',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(84,6,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'İletişim',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(85,7,0,NULL,NULL,'/',NULL,0,'Homepage',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(86,7,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'Contact',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(87,7,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'Blog',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(88,7,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Travel',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(89,7,0,NULL,NULL,'/galleries',NULL,4,'Galleries',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(90,8,0,NULL,NULL,'/',NULL,0,'الصفحة الرئيسية',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(91,8,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'اتصل بنا',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(92,8,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'المدونة',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(93,8,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'سفر',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(94,8,0,NULL,NULL,'/galleries',NULL,4,'المعارض',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(95,9,0,NULL,NULL,'/',NULL,0,'Trang chủ',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(96,9,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'Liên hệ',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(97,9,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'Blog',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(98,9,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Du lịch',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(99,9,0,NULL,NULL,'/galleries',NULL,4,'Thư viện ảnh',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(100,10,0,NULL,NULL,'/',NULL,0,'Accueil',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(101,10,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'Contact',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(102,10,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'Blog',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(103,10,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Voyage',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(104,10,0,NULL,NULL,'/galleries',NULL,4,'Galeries',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(105,11,0,NULL,NULL,'/',NULL,0,'Beranda',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(106,11,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'Kontak',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(107,11,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'Blog',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(108,11,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Perjalanan',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(109,11,0,NULL,NULL,'/galleries',NULL,4,'Galeri',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(110,12,0,NULL,NULL,'/',NULL,0,'Ana Sayfa',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(111,12,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'İletişim',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(112,12,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'Blog',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(113,12,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Seyahat',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39'),(114,12,0,NULL,NULL,'/galleries',NULL,4,'Galeriler',NULL,'_self',0,'2026-07-22 00:26:39','2026-07-22 00:26:39');
/*!40000 ALTER TABLE `menu_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Main menu','main-menu','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(2,'القائمة الرئيسية','main-menu-ar','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(3,'Menu chính','main-menu-vi','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(4,'Menu principal','main-menu-fr','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(5,'Menu Utama','main-menu-id','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(6,'Ana Menü','main-menu-tr','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(7,'Quick links','quick-links','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(8,'روابط سريعة','quick-links-ar','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(9,'Liên kết nhanh','quick-links-vi','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(10,'Liens rapides','quick-links-fr','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(11,'Tautan cepat','quick-links-id','published','2026-07-22 00:26:39','2026-07-22 00:26:39'),(12,'Hızlı bağlantılar','quick-links-tr','published','2026-07-22 00:26:39','2026-07-22 00:26:39');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meta_boxes`
--

DROP TABLE IF EXISTS `meta_boxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meta_boxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_boxes_reference_id_index` (`reference_id`),
  KEY `meta_boxes_ref_idx` (`reference_id`,`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meta_boxes`
--

LOCK TABLES `meta_boxes` WRITE;
/*!40000 ALTER TABLE `meta_boxes` DISABLE KEYS */;
INSERT INTO `meta_boxes` VALUES (1,'bio','[\"Hi, I\\u2019m System Admin, Your Blogging Journey Guide \\ud83d\\udd8b\\ufe0f. Writing, one blog post at a time, to inspire, inform, and ignite your curiosity. Join me as we explore the world through words and embark on a limitless adventure of knowledge and creativity. Let\\u2019s bring your thoughts to life on these digital pages. \\ud83c\\udf1f #BloggingAdventures\"]',1,'Botble\\ACL\\Models\\User','2026-07-22 00:26:38','2026-07-22 00:26:38');
/*!40000 ALTER TABLE `meta_boxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'2013_04_09_032329_create_base_tables',1),(3,'2013_04_09_062329_create_revisions_table',1),(4,'2014_10_12_000000_create_users_table',1),(5,'2014_10_12_100000_create_password_reset_tokens_table',1),(6,'2016_06_10_230148_create_acl_tables',1),(7,'2016_06_14_230857_create_menus_table',1),(8,'2016_06_28_221418_create_pages_table',1),(9,'2016_10_05_074239_create_setting_table',1),(10,'2016_11_28_032840_create_dashboard_widget_tables',1),(11,'2016_12_16_084601_create_widgets_table',1),(12,'2017_05_09_070343_create_media_tables',1),(13,'2017_11_03_070450_create_slug_table',1),(14,'2019_01_05_053554_create_jobs_table',1),(15,'2019_08_19_000000_create_failed_jobs_table',1),(16,'2019_12_14_000001_create_personal_access_tokens_table',1),(17,'2022_04_20_100851_add_index_to_media_table',1),(18,'2022_04_20_101046_add_index_to_menu_table',1),(19,'2022_07_10_034813_move_lang_folder_to_root',1),(20,'2022_08_04_051940_add_missing_column_expires_at',1),(21,'2022_09_01_000001_create_admin_notifications_tables',1),(22,'2022_10_14_024629_drop_column_is_featured',1),(23,'2022_11_18_063357_add_missing_timestamp_in_table_settings',1),(24,'2022_12_02_093615_update_slug_index_columns',1),(25,'2023_01_30_024431_add_alt_to_media_table',1),(26,'2023_02_16_042611_drop_table_password_resets',1),(27,'2023_04_23_005903_add_column_permissions_to_admin_notifications',1),(28,'2023_05_10_075124_drop_column_id_in_role_users_table',1),(29,'2023_08_21_090810_make_page_content_nullable',1),(30,'2023_09_14_021936_update_index_for_slugs_table',1),(31,'2023_12_07_095130_add_color_column_to_media_folders_table',1),(32,'2023_12_17_162208_make_sure_column_color_in_media_folders_nullable',1),(33,'2024_04_04_110758_update_value_column_in_user_meta_table',1),(34,'2024_05_04_030654_improve_social_links',1),(35,'2024_05_12_091229_add_column_visibility_to_table_media_files',1),(36,'2024_07_07_091316_fix_column_url_in_menu_nodes_table',1),(37,'2024_07_12_100000_change_random_hash_for_media',1),(38,'2024_09_30_024515_create_sessions_table',1),(39,'2024_12_01_000000_add_indexes_to_pages_translations_table',1),(40,'2024_12_01_000000_add_key_prefix_index_to_slugs_table',1),(41,'2024_12_19_000001_create_device_tokens_table',1),(42,'2024_12_19_000002_create_push_notifications_table',1),(43,'2024_12_19_000003_create_push_notification_recipients_table',1),(44,'2024_12_30_000001_create_user_settings_table',1),(45,'2025_07_06_030754_add_phone_to_users_table',1),(46,'2025_07_31_add_performance_indexes_to_slugs_table',1),(47,'2025_11_10_000000_cleanup_duplicate_widgets',1),(48,'2026_03_07_153100_add_index_to_meta_boxes_table',1),(49,'2026_03_23_000000_create_media_folder_permissions_table',1),(50,'2026_03_27_085220_add_folder_deleted_name_index_to_media_files_table',1),(51,'2026_04_20_000000_add_sessions_invalidated_at_to_users_table',1),(52,'2020_11_18_150916_ads_create_ads_table',2),(53,'2021_12_02_035301_add_ads_translations_table',2),(54,'2023_04_17_062645_add_open_in_new_tab',2),(55,'2023_11_07_023805_add_tablet_mobile_image',2),(56,'2024_04_01_043317_add_google_adsense_slot_id_to_ads_table',2),(57,'2025_04_21_000000_add_tablet_mobile_image_to_ads_translations_table',2),(58,'2024_04_27_100730_improve_analytics_setting',3),(59,'2023_08_11_060908_create_announcements_table',4),(60,'2025_02_11_153025_add_action_label_to_announcement_translations',4),(61,'2015_06_29_025744_create_audit_history',5),(62,'2023_11_14_033417_change_request_column_in_table_audit_histories',5),(63,'2025_05_05_000001_add_user_type_to_audit_histories_table',5),(64,'2025_11_07_000001_add_actor_type_to_audit_histories_table',5),(65,'2015_06_18_033822_create_blog_table',6),(66,'2021_02_16_092633_remove_default_value_for_author_type',6),(67,'2021_12_03_030600_create_blog_translations',6),(68,'2022_04_19_113923_add_index_to_table_posts',6),(69,'2023_08_29_074620_make_column_author_id_nullable',6),(70,'2024_07_30_091615_fix_order_column_in_categories_table',6),(71,'2024_12_01_000000_add_indexes_to_blog_translations_tables',6),(72,'2025_01_06_033807_add_default_value_for_categories_author_type',6),(73,'2026_05_12_000000_change_description_column_type_in_blog_tables',6),(74,'2016_06_17_091537_create_contacts_table',7),(75,'2023_11_10_080225_migrate_contact_blacklist_email_domains_to_core',7),(76,'2024_03_20_080001_migrate_change_attribute_email_to_nullable_form_contacts_table',7),(77,'2024_03_25_000001_update_captcha_settings_for_contact',7),(78,'2024_04_19_063914_create_custom_fields_table',7),(79,'2024_12_01_000000_add_indexes_to_contact_translations_tables',7),(80,'2024_01_16_050056_create_comments_table',8),(81,'2026_05_20_082500_add_composite_index_to_comments_table',8),(82,'2016_10_13_150201_create_galleries_table',9),(83,'2021_12_03_082953_create_gallery_translations',9),(84,'2022_04_30_034048_create_gallery_meta_translations_table',9),(85,'2023_08_29_075308_make_column_user_id_nullable',9),(86,'2024_12_01_000000_add_indexes_to_gallery_translations_tables',9),(87,'2016_10_03_032336_create_languages_table',10),(88,'2023_09_14_022423_add_index_for_language_table',10),(89,'2021_10_25_021023_fix-priority-load-for-language-advanced',11),(90,'2021_12_03_075608_create_page_translations',11),(91,'2023_07_06_011444_create_slug_translations_table',11),(92,'2024_12_01_000000_add_indexes_to_slugs_translations_table',11),(93,'2017_10_24_154832_create_newsletter_table',12),(94,'2024_03_25_000001_update_captcha_settings_for_newsletter',12),(95,'2016_10_07_193005_create_translations_table',13),(96,'2023_12_12_105220_drop_translations_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'subscribed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pages_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','<div>[about-banner title=\"Hello, I’m &lt;span&gt;Steven&lt;/span&gt;\" subtitle=\"Welcome to my blog\" text_muted=\"Travel Blogger., Content Writer., Food Guides\" image=\"general/featured.png\" newsletter_title=\"Don\'t miss out on the latest news about Travel tips, Hotels review, Food guide...\" image=\"general/featured.png\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"Featured posts\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>',1,NULL,'homepage',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(2,'Home 2','<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>',1,NULL,'homepage',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(3,'Home 3','<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>',1,NULL,'homepage',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(4,'Blog','---',1,NULL,'default',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(5,'Contact','<p>Address: North Link Building, 10 Admiralty Street, 757695 Singapore</p><p>Hotline: 18006268</p><p>Email: contact@botble.com</p><p>[google-map]North Link Building, 10 Admiralty Street, 757695 Singapore[/google-map]</p><p>For the fastest reply, please use the contact form below.</p><p>[contact-form][/contact-form]</p>',1,NULL,'default',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(6,'Cookie Policy','<h3>EU Cookie Consent</h3><p>To use this website we are using Cookies and collecting some Data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.</p><h4>Essential Data</h4><p>The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.</p><p>- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.</p><p>- XSRF-Token Cookie: Laravel automatically generates a CSRF \"token\" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.</p>',1,NULL,'default',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(7,'Blog List layout','<div>[blog-list limit=\"12\"][/blog-list]</div>',1,NULL,'right-sidebar',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(8,'Blog Big layout','<div>[blog-big limit=\"12\"][/blog-big]</div>',1,NULL,'default',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38'),(9,'Blog Grid layout','<div>[blog-big limit=\"12\"][/blog-big]</div>',1,NULL,'right-sidebar',NULL,'published','2026-07-22 00:26:38','2026-07-22 00:26:38');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_translations`
--

DROP TABLE IF EXISTS `pages_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pages_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`pages_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages_translations`
--

LOCK TABLES `pages_translations` WRITE;
/*!40000 ALTER TABLE `pages_translations` DISABLE KEYS */;
INSERT INTO `pages_translations` VALUES ('ar',1,'الرئيسية',NULL,'<div>[about-banner title=\"مرحباً، أنا &lt;span&gt;ستيفن&lt;/span&gt;\" subtitle=\"مرحباً بكم في مدونتي\" text_muted=\"مدوّن سفر., كاتب محتوى., دليل طعام\" image=\"general/featured.png\" newsletter_title=\"لا تفوّت آخر الأخبار حول نصائح السفر، مراجعات الفنادق، دليل الطعام...\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"مقالات مميزة\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"التصنيفات\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('ar',2,'الرئيسية 2',NULL,'<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('ar',3,'الرئيسية 3',NULL,'<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('ar',4,'المدونة',NULL,'---'),('ar',5,'اتصل بنا',NULL,'<p>العنوان: نورث لينك بيلدنج، 10 شارع أدميرالتي، 757695 سنغافورة</p><p>الخط الساخن: 18006268</p><p>البريد الإلكتروني: contact@botble.com</p><p>[google-map]نورث لينك بيلدنج، 10 شارع أدميرالتي، 757695 سنغافورة[/google-map]</p><p>للحصول على أسرع رد، يرجى استخدام نموذج الاتصال أدناه.</p><p>[contact-form][/contact-form]</p>'),('ar',6,'سياسة ملفات تعريف الارتباط',NULL,'<h3>موافقة ملفات تعريف الارتباط في الاتحاد الأوروبي</h3><p>لاستخدام هذا الموقع، نستخدم ملفات تعريف الارتباط ونجمع بعض البيانات. للامتثال للائحة حماية البيانات العامة في الاتحاد الأوروبي، نمنحك خيار السماح لنا باستخدام ملفات تعريف ارتباط معينة وجمع بعض البيانات.</h4><h4>البيانات الأساسية</h4><p>البيانات الأساسية ضرورية لتشغيل الموقع الذي تزوره تقنياً. لا يمكنك إلغاء تنشيطها.</p><p>- ملف تعريف ارتباط الجلسة: يستخدم PHP ملف تعريف ارتباط لتحديد جلسات المستخدم. بدون ملف تعريف الارتباط هذا لن يعمل الموقع.</p><p>- ملف تعريف ارتباط XSRF-Token: يقوم Laravel تلقائياً بإنشاء رمز CSRF لكل جلسة مستخدم نشطة يديرها التطبيق. يُستخدم هذا الرمز للتحقق من أن المستخدم المصادق عليه هو الذي يقوم فعلاً بتقديم الطلبات إلى التطبيق.</p>'),('ar',7,'تخطيط قائمة المدونة',NULL,'<div>[blog-list limit=\"12\"][/blog-list]</div>'),('ar',8,'تخطيط المدونة الكبير',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('ar',9,'تخطيط شبكة المدونة',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('fr',1,'Accueil',NULL,'<div>[about-banner title=\"Bonjour, je suis &lt;span&gt;Steven&lt;/span&gt;\" subtitle=\"Bienvenue sur mon blog\" text_muted=\"Blogueur Voyage., Rédacteur de Contenu., Guide Culinaire\" image=\"general/featured.png\" newsletter_title=\"Ne manquez pas les dernières nouvelles sur les conseils de voyage, les avis sur les hôtels, les guides culinaires...\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"Articles vedettes\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Catégories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('fr',2,'Accueil 2',NULL,'<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('fr',3,'Accueil 3',NULL,'<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('fr',4,'Blog',NULL,'---'),('fr',5,'Contact',NULL,'<p>Adresse : North Link Building, 10 Admiralty Street, 757695 Singapour</p><p>Téléphone : 18006268</p><p>Email : contact@botble.com</p><p>[google-map]North Link Building, 10 Admiralty Street, 757695 Singapour[/google-map]</p><p>Pour une réponse rapide, veuillez utiliser le formulaire de contact ci-dessous.</p><p>[contact-form][/contact-form]</p>'),('fr',6,'Politique de cookies',NULL,'<h3>Consentement aux cookies de l\'UE</h3><p>Pour utiliser ce site, nous utilisons des cookies et collectons certaines données. Pour être conforme au RGPD de l\'UE, nous vous donnons le choix d\'autoriser ou non l\'utilisation de certains cookies et la collecte de certaines données.</p><h4>Données essentielles</h4><p>Les données essentielles sont nécessaires au fonctionnement technique du site que vous visitez. Vous ne pouvez pas les désactiver.</p><p>- Cookie de session : PHP utilise un cookie pour identifier les sessions utilisateur. Sans ce cookie, le site ne fonctionne pas.</p><p>- Cookie XSRF-Token : Laravel génère automatiquement un jeton CSRF pour chaque session utilisateur active gérée par l\'application. Ce jeton est utilisé pour vérifier que l\'utilisateur authentifié est celui qui effectue réellement les requêtes à l\'application.</p>'),('fr',7,'Blog en liste',NULL,'<div>[blog-list limit=\"12\"][/blog-list]</div>'),('fr',8,'Blog grand format',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('fr',9,'Blog en grille',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('id',1,'Beranda',NULL,'<div>[about-banner title=\"Halo, saya &lt;span&gt;Steven&lt;/span&gt;\" subtitle=\"Selamat datang di blog saya\" text_muted=\"Blogger Perjalanan., Penulis Konten., Panduan Makanan\" image=\"general/featured.png\" newsletter_title=\"Jangan lewatkan berita terbaru tentang Tips perjalanan, Ulasan hotel, Panduan makanan...\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"Artikel unggulan\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Kategori\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('id',2,'Beranda 2',NULL,'<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('id',3,'Beranda 3',NULL,'<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('id',4,'Blog',NULL,'---'),('id',5,'Kontak',NULL,'<p>Alamat: North Link Building, 10 Admiralty Street, 757695 Singapura</p><p>Hotline: 18006268</p><p>Email: contact@botble.com</p><p>[google-map]North Link Building, 10 Admiralty Street, 757695 Singapura[/google-map]</p><p>Untuk balasan tercepat, silakan gunakan formulir kontak di bawah ini.</p><p>[contact-form][/contact-form]</p>'),('id',6,'Kebijakan Cookie',NULL,'<h3>Persetujuan Cookie UE</h3><p>Untuk menggunakan situs ini, kami menggunakan Cookie dan mengumpulkan beberapa data. Untuk mematuhi GDPR UE, kami memberi Anda pilihan untuk mengizinkan kami menggunakan Cookie tertentu dan mengumpulkan beberapa data.</p><h4>Data Penting</h4><p>Data Penting diperlukan untuk menjalankan situs yang Anda kunjungi secara teknis. Anda tidak dapat menonaktifkannya.</p><p>- Cookie Sesi: PHP menggunakan Cookie untuk mengidentifikasi sesi pengguna. Tanpa Cookie ini, situs web tidak berfungsi.</p><p>- Cookie XSRF-Token: Laravel secara otomatis menghasilkan token CSRF untuk setiap sesi pengguna aktif yang dikelola oleh aplikasi. Token ini digunakan untuk memverifikasi bahwa pengguna yang terautentikasi adalah yang benar-benar membuat permintaan ke aplikasi.</p>'),('id',7,'Tata letak daftar Blog',NULL,'<div>[blog-list limit=\"12\"][/blog-list]</div>'),('id',8,'Tata letak Blog besar',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('id',9,'Tata letak grid Blog',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('tr',1,'Ana Sayfa',NULL,'<div>[about-banner title=\"Merhaba, ben &lt;span&gt;Steven&lt;/span&gt;\" subtitle=\"Bloguma hoş geldiniz\" text_muted=\"Seyahat Blogcusu., İçerik Yazarı., Yemek Rehberi\" image=\"general/featured.png\" newsletter_title=\"Seyahat ipuçları, Otel değerlendirmeleri, Yemek rehberi hakkında en son haberleri kaçırmayın...\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"Öne çıkan yazılar\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Kategoriler\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('tr',2,'Ana Sayfa 2',NULL,'<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('tr',3,'Ana Sayfa 3',NULL,'<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('tr',4,'Blog',NULL,'---'),('tr',5,'İletişim',NULL,'<p>Adres: North Link Building, 10 Admiralty Street, 757695 Singapur</p><p>Telefon: 18006268</p><p>E-posta: contact@botble.com</p><p>[google-map]North Link Building, 10 Admiralty Street, 757695 Singapur[/google-map]</p><p>En hızlı yanıt için lütfen aşağıdaki iletişim formunu kullanın.</p><p>[contact-form][/contact-form]</p>'),('tr',6,'Çerez Politikası',NULL,'<h3>AB Çerez Onayı</h3><p>Bu web sitesini kullanmak için Çerezler kullanıyor ve bazı veriler topluyoruz. AB GDPR\'ye uyum sağlamak için belirli Çerezleri kullanmamıza ve bazı verileri toplamamıza izin verip vermeyeceğinizi seçmenize olanak tanıyoruz.</p><h4>Temel Veriler</h4><p>Temel Veriler, ziyaret ettiğiniz siteyi teknik olarak çalıştırmak için gereklidir. Bunları devre dışı bırakamazsınız.</p><p>- Oturum Çerezi: PHP, kullanıcı oturumlarını tanımlamak için bir Çerez kullanır. Bu Çerez olmadan web sitesi çalışmaz.</p><p>- XSRF-Token Çerezi: Laravel, uygulama tarafından yönetilen her aktif kullanıcı oturumu için otomatik olarak bir CSRF belirteci oluşturur. Bu belirteç, kimliği doğrulanmış kullanıcının uygulamaya gerçekten isteklerde bulunan kişi olduğunu doğrulamak için kullanılır.</p>'),('tr',7,'Blog liste düzeni',NULL,'<div>[blog-list limit=\"12\"][/blog-list]</div>'),('tr',8,'Blog büyük düzen',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('tr',9,'Blog ızgara düzeni',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('vi',1,'Trang chủ',NULL,'<div>[about-banner title=\"Xin chào, tôi là &lt;span&gt;Steven&lt;/span&gt;\" subtitle=\"Chào mừng đến blog của tôi\" text_muted=\"Blogger Du lịch., Nhà viết Nội dung., Hướng dẫn Ẩm thực\" image=\"general/featured.png\" newsletter_title=\"Đừng bỏ lỡ tin tức mới nhất về Mẹo du lịch, Đánh giá khách sạn, Hướng dẫn ẩm thực...\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"Bài viết nổi bật\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Danh mục\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('vi',2,'Trang chủ 2',NULL,'<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('vi',3,'Trang chủ 3',NULL,'<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>'),('vi',4,'Blog',NULL,'---'),('vi',5,'Liên hệ',NULL,'<p>Địa chỉ: North Link Building, 10 Admiralty Street, 757695 Singapore</p><p>Hotline: 18006268</p><p>Email: contact@botble.com</p><p>[google-map]North Link Building, 10 Admiralty Street, 757695 Singapore[/google-map]</p><p>Để được phản hồi nhanh nhất, vui lòng sử dụng biểu mẫu liên hệ bên dưới.</p><p>[contact-form][/contact-form]</p>'),('vi',6,'Chính sách Cookie',NULL,'<h3>Chấp thuận Cookie EU</h3><p>Để sử dụng trang web này, chúng tôi đang sử dụng Cookie và thu thập một số dữ liệu. Để tuân thủ GDPR của EU, chúng tôi cho phép bạn chọn có cho phép chúng tôi sử dụng một số Cookie nhất định và thu thập một số dữ liệu hay không.</p><h4>Dữ liệu thiết yếu</h4><p>Dữ liệu thiết yếu cần thiết để vận hành trang web bạn đang truy cập về mặt kỹ thuật. Bạn không thể tắt chúng.</p><p>- Cookie phiên: PHP sử dụng Cookie để nhận dạng phiên người dùng. Không có Cookie này, trang web sẽ không hoạt động.</p><p>- Cookie XSRF-Token: Laravel tự động tạo mã CSRF cho mỗi phiên người dùng đang hoạt động được quản lý bởi ứng dụng. Mã này được sử dụng để xác minh rằng người dùng đã xác thực là người thực sự đưa ra yêu cầu đến ứng dụng.</p>'),('vi',7,'Bố cục danh sách Blog',NULL,'<div>[blog-list limit=\"12\"][/blog-list]</div>'),('vi',8,'Bố cục Blog lớn',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>'),('vi',9,'Bố cục lưới Blog',NULL,'<div>[blog-big limit=\"12\"][/blog-big]</div>');
/*!40000 ALTER TABLE `pages_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_categories`
--

DROP TABLE IF EXISTS `post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_categories` (
  `category_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  KEY `post_categories_category_id_index` (`category_id`),
  KEY `post_categories_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_categories`
--

LOCK TABLES `post_categories` WRITE;
/*!40000 ALTER TABLE `post_categories` DISABLE KEYS */;
INSERT INTO `post_categories` VALUES (7,1),(6,1),(1,2),(8,2),(5,3),(6,3),(1,4),(6,4),(2,5),(7,5),(8,6),(6,6),(6,7),(5,7),(8,8),(6,9),(2,9),(6,10),(8,10),(9,11),(1,11),(3,12),(4,12),(6,13),(4,13),(7,14),(2,14),(3,15),(7,15),(7,16),(3,16);
/*!40000 ALTER TABLE `post_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tags`
--

DROP TABLE IF EXISTS `post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_tags` (
  `tag_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  KEY `post_tags_tag_id_index` (`tag_id`),
  KEY `post_tags_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tags`
--

LOCK TABLES `post_tags` WRITE;
/*!40000 ALTER TABLE `post_tags` DISABLE KEYS */;
INSERT INTO `post_tags` VALUES (5,1),(2,1),(5,2),(1,2),(2,3),(3,3),(4,4),(5,4),(3,5),(2,5),(3,6),(2,6),(2,7),(5,7),(4,8),(5,8),(3,8),(1,9),(3,9),(4,9),(3,10),(4,10),(1,10),(4,11),(1,11),(2,11),(3,12),(1,12),(5,13),(4,13),(3,13),(4,14),(2,14),(1,14),(5,15),(4,15),(2,16),(4,16);
/*!40000 ALTER TABLE `post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int unsigned NOT NULL DEFAULT '0',
  `format_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_author_id_index` (`author_id`),
  KEY `posts_author_type_index` (`author_type`),
  KEY `posts_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'The Top 2020 Handbag Trends to Know','Discover the latest trends and insights that are shaping the industry this year.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/16.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/1.jpg',314,'video','2026-07-22 00:26:40','2026-07-22 00:26:40'),(2,'Top Search Engine Optimization Strategies!','An in-depth look at the strategies that successful businesses are using today.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/7.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/16.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/17.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/2.jpg',1557,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(3,'Which Company Would You Choose?','Expert analysis and practical tips to help you stay ahead of the curve.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/7.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/16.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/15.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/3.jpg',1421,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(4,'Used Car Dealer Sales Tricks Exposed','Everything you need to know about making informed decisions in today\'s market.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/6.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/13.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/4.jpg',1695,'video','2026-07-22 00:26:40','2026-07-22 00:26:40'),(5,'20 Ways To Sell Your Product Faster','A comprehensive guide to understanding the key factors driving change.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/3.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/11.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/13.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/5.jpg',1467,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(6,'The Secrets Of Rich And Famous Writers','Learn from industry leaders and apply their proven methods to your own journey.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/6.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/19.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/6.jpg',1030,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(7,'Imagine Losing 20 Pounds In 14 Days!','Insights and recommendations based on thorough research and real-world experience.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/7.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/10.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/7.jpg',216,'video','2026-07-22 00:26:40','2026-07-22 00:26:40'),(8,'Are You Still Using That Slow, Old Typewriter?','The essential information you need to navigate today\'s complex landscape.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/3.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/12.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/11.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/8.jpg',2266,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(9,'A Skin Cream That\'s Proven To Work','Breaking down the most important developments and what they mean for you.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/3.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/11.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/13.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/9.jpg',2443,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(10,'10 Reasons To Start Your Own, Profitable Website!','A deep dive into the topics that matter most to professionals and enthusiasts alike.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/12.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/19.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/15.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/10.jpg',899,'video','2026-07-22 00:26:40','2026-07-22 00:26:40'),(11,'Simple Ways To Reduce Your Unwanted Wrinkles!','Practical advice and actionable strategies for achieving your goals.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/7.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/13.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/14.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/11.jpg',2075,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(12,'Apple iMac with Retina 5K display review','Understanding the fundamentals and advanced concepts in this evolving field.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/3.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/12.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/14.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/12.jpg',2187,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(13,'10,000 Web Site Visitors In One Month:Guaranteed','Key takeaways and lessons learned from recent industry developments.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/2.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/11.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/14.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/17.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/13.jpg',1206,'video','2026-07-22 00:26:40','2026-07-22 00:26:40'),(14,'Unlock The Secrets Of Selling High Ticket Items','A fresh perspective on the challenges and opportunities ahead.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/6.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/16.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/14.jpg',1155,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(15,'4 Expert Tips On How To Choose The Right Men\'s Wallet','Expert insights to help you make the most of emerging trends.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/5.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/12.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/14.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/17.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/15.jpg',846,'default','2026-07-22 00:26:40','2026-07-22 00:26:40'),(16,'Sexy Clutches: How to Buy &amp; Wear a Designer Clutch Bag','Your complete resource for staying informed and making smart choices.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/12.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/19.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/16.jpg',158,'video','2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_translations`
--

DROP TABLE IF EXISTS `posts_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posts_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`posts_id`),
  KEY `idx_posts_trans_posts_id` (`posts_id`),
  KEY `idx_posts_trans_post_lang` (`posts_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_translations`
--

LOCK TABLES `posts_translations` WRITE;
/*!40000 ALTER TABLE `posts_translations` DISABLE KEYS */;
INSERT INTO `posts_translations` VALUES ('ar',1,'أهم صيحات حقائب اليد لعام 2020 التي يجب معرفتها',NULL,NULL),('ar',2,'أفضل استراتيجيات تحسين محركات البحث!',NULL,NULL),('ar',3,'أي شركة ستختار؟',NULL,NULL),('ar',4,'كشف حيل مبيعات تجار السيارات المستعملة',NULL,NULL),('ar',5,'20 طريقة لبيع منتجك بشكل أسرع',NULL,NULL),('ar',6,'أسرار الكتّاب الأثرياء والمشهورين',NULL,NULL),('ar',7,'تخيل خسارة 20 رطلاً في 14 يوماً!',NULL,NULL),('ar',8,'هل لا تزال تستخدم تلك الآلة الكاتبة القديمة البطيئة؟',NULL,NULL),('ar',9,'كريم بشرة أثبت فعاليته',NULL,NULL),('ar',10,'10 أسباب لبدء موقعك الإلكتروني المربح!',NULL,NULL),('ar',11,'طرق بسيطة لتقليل التجاعيد غير المرغوب فيها!',NULL,NULL),('ar',12,'مراجعة آبل آيماك بشاشة ريتينا 5K',NULL,NULL),('ar',13,'10,000 زائر لموقعك في شهر واحد: مضمون',NULL,NULL),('ar',14,'اكتشف أسرار بيع المنتجات عالية القيمة',NULL,NULL),('ar',15,'4 نصائح خبراء لاختيار محفظة الرجال المناسبة',NULL,NULL),('ar',16,'حقائب كلاتش أنيقة: كيف تشتري وترتدي حقيبة كلاتش مصممة',NULL,NULL),('fr',1,'Les principales tendances sacs à main 2020 à connaître',NULL,NULL),('fr',2,'Meilleures stratégies d\'optimisation pour les moteurs de recherche !',NULL,NULL),('fr',3,'Quelle entreprise choisiriez-vous ?',NULL,NULL),('fr',4,'Les astuces de vente des concessionnaires de voitures d\'occasion révélées',NULL,NULL),('fr',5,'20 façons de vendre votre produit plus rapidement',NULL,NULL),('fr',6,'Les secrets des écrivains riches et célèbres',NULL,NULL),('fr',7,'Imaginez perdre 9 kg en 14 jours !',NULL,NULL),('fr',8,'Utilisez-vous encore cette vieille machine à écrire lente ?',NULL,NULL),('fr',9,'Une crème pour la peau dont l\'efficacité est prouvée',NULL,NULL),('fr',10,'10 raisons de créer votre propre site web rentable !',NULL,NULL),('fr',11,'Des moyens simples pour réduire vos rides indésirables !',NULL,NULL),('fr',12,'Test de l\'Apple iMac avec écran Retina 5K',NULL,NULL),('fr',13,'10 000 visiteurs en un mois : garanti',NULL,NULL),('fr',14,'Découvrez les secrets de la vente d\'articles haut de gamme',NULL,NULL),('fr',15,'4 conseils d\'experts pour choisir le bon portefeuille pour homme',NULL,NULL),('fr',16,'Pochettes élégantes : comment acheter et porter une pochette de créateur',NULL,NULL),('id',1,'Tren tas tangan teratas 2020 yang perlu diketahui',NULL,NULL),('id',2,'Strategi optimasi mesin pencari teratas!',NULL,NULL),('id',3,'Perusahaan mana yang akan Anda pilih?',NULL,NULL),('id',4,'Trik penjualan dealer mobil bekas terungkap',NULL,NULL),('id',5,'20 cara menjual produk Anda lebih cepat',NULL,NULL),('id',6,'Rahasia penulis kaya dan terkenal',NULL,NULL),('id',7,'Bayangkan menurunkan 9 kg dalam 14 hari!',NULL,NULL),('id',8,'Apakah Anda masih menggunakan mesin ketik lama yang lambat itu?',NULL,NULL),('id',9,'Krim kulit yang terbukti efektif',NULL,NULL),('id',10,'10 alasan untuk memulai situs web menguntungkan Anda sendiri!',NULL,NULL),('id',11,'Cara sederhana untuk mengurangi kerutan yang tidak diinginkan!',NULL,NULL),('id',12,'Ulasan Apple iMac dengan layar Retina 5K',NULL,NULL),('id',13,'10.000 pengunjung situs web dalam satu bulan: Dijamin',NULL,NULL),('id',14,'Buka rahasia menjual barang bernilai tinggi',NULL,NULL),('id',15,'4 tips ahli cara memilih dompet pria yang tepat',NULL,NULL),('id',16,'Clutch bergaya: Cara membeli dan memakai tas clutch desainer',NULL,NULL),('tr',1,'Bilmeniz gereken 2020\'nin en iyi çanta trendleri',NULL,NULL),('tr',2,'En iyi arama motoru optimizasyon stratejileri!',NULL,NULL),('tr',3,'Hangi şirketi seçerdiniz?',NULL,NULL),('tr',4,'İkinci el araba satıcılarının satış hileleri ifşa edildi',NULL,NULL),('tr',5,'Ürününüzü daha hızlı satmanın 20 yolu',NULL,NULL),('tr',6,'Zengin ve ünlü yazarların sırları',NULL,NULL),('tr',7,'14 günde 9 kilo verdiğinizi hayal edin!',NULL,NULL),('tr',8,'Hâlâ o yavaş, eski daktilo mu kullanıyorsunuz?',NULL,NULL),('tr',9,'İşe yaradığı kanıtlanmış bir cilt kremi',NULL,NULL),('tr',10,'Kendi kârlı web sitenizi kurmanız için 10 neden!',NULL,NULL),('tr',11,'İstenmeyen kırışıklıklarınızı azaltmanın basit yolları!',NULL,NULL),('tr',12,'Retina 5K ekranlı Apple iMac incelemesi',NULL,NULL),('tr',13,'Bir ayda 10.000 web sitesi ziyaretçisi: Garantili',NULL,NULL),('tr',14,'Yüksek değerli ürün satışının sırlarını keşfedin',NULL,NULL),('tr',15,'Doğru erkek cüzdanını seçmek için 4 uzman ipucu',NULL,NULL),('tr',16,'Şık el çantaları: Tasarımcı clutch çanta nasıl alınır ve kullanılır',NULL,NULL),('vi',1,'Những xu hướng túi xách hàng đầu năm 2020 cần biết',NULL,NULL),('vi',2,'Chiến lược tối ưu hóa công cụ tìm kiếm hàng đầu!',NULL,NULL),('vi',3,'Bạn sẽ chọn công ty nào?',NULL,NULL),('vi',4,'Vạch trần chiêu trò bán hàng của đại lý xe cũ',NULL,NULL),('vi',5,'20 cách bán sản phẩm nhanh hơn',NULL,NULL),('vi',6,'Bí mật của những nhà văn giàu có và nổi tiếng',NULL,NULL),('vi',7,'Hãy tưởng tượng giảm 9kg trong 14 ngày!',NULL,NULL),('vi',8,'Bạn vẫn đang sử dụng chiếc máy đánh chữ cũ chậm chạp đó?',NULL,NULL),('vi',9,'Kem dưỡng da đã được chứng minh hiệu quả',NULL,NULL),('vi',10,'10 lý do để bắt đầu trang web sinh lời của riêng bạn!',NULL,NULL),('vi',11,'Cách đơn giản để giảm nếp nhăn không mong muốn!',NULL,NULL),('vi',12,'Đánh giá Apple iMac với màn hình Retina 5K',NULL,NULL),('vi',13,'10.000 lượt truy cập website trong một tháng: Đảm bảo',NULL,NULL),('vi',14,'Mở khóa bí mật bán hàng giá trị cao',NULL,NULL),('vi',15,'4 mẹo chuyên gia để chọn ví nam phù hợp',NULL,NULL),('vi',16,'Clutch sành điệu: Cách mua và sử dụng túi clutch thiết kế',NULL,NULL);
/*!40000 ALTER TABLE `posts_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_notification_recipients`
--

DROP TABLE IF EXISTS `push_notification_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_notification_recipients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `push_notification_id` bigint unsigned NOT NULL,
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `fcm_response` json DEFAULT NULL,
  `error_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pnr_notification_user_index` (`push_notification_id`,`user_type`,`user_id`),
  KEY `pnr_user_status_index` (`user_type`,`user_id`,`status`),
  KEY `pnr_user_read_index` (`user_type`,`user_id`,`read_at`),
  KEY `pnr_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_notification_recipients`
--

LOCK TABLES `push_notification_recipients` WRITE;
/*!40000 ALTER TABLE `push_notification_recipients` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_notification_recipients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_notifications`
--

DROP TABLE IF EXISTS `push_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `target_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_count` int NOT NULL DEFAULT '0',
  `failed_count` int NOT NULL DEFAULT '0',
  `delivered_count` int NOT NULL DEFAULT '0',
  `read_count` int NOT NULL DEFAULT '0',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `push_notifications_type_created_at_index` (`type`,`created_at`),
  KEY `push_notifications_status_scheduled_at_index` (`status`,`scheduled_at`),
  KEY `push_notifications_created_by_index` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_notifications`
--

LOCK TABLES `push_notifications` WRITE;
/*!40000 ALTER TABLE `push_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisions`
--

DROP TABLE IF EXISTS `revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `revisionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisionable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revisions_revisionable_id_revisionable_type_index` (`revisionable_id`,`revisionable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_users` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_users_user_id_index` (`user_id`),
  KEY `role_users_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_users`
--

LOCK TABLES `role_users` WRITE;
/*!40000 ALTER TABLE `role_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`),
  KEY `roles_created_by_index` (`created_by`),
  KEY `roles_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Admin','{\"users.index\":true,\"users.create\":true,\"users.edit\":true,\"users.destroy\":true,\"roles.index\":true,\"roles.create\":true,\"roles.edit\":true,\"roles.destroy\":true,\"core.system\":true,\"core.cms\":true,\"core.manage.license\":true,\"systems.cronjob\":true,\"core.tools\":true,\"tools.data-synchronize\":true,\"media.index\":true,\"files.index\":true,\"files.create\":true,\"files.edit\":true,\"files.trash\":true,\"files.destroy\":true,\"folders.index\":true,\"folders.create\":true,\"folders.edit\":true,\"folders.trash\":true,\"folders.destroy\":true,\"settings.index\":true,\"settings.common\":true,\"settings.options\":true,\"settings.email\":true,\"settings.media\":true,\"settings.admin-appearance\":true,\"settings.cache\":true,\"settings.datatables\":true,\"settings.email.rules\":true,\"settings.phone-number\":true,\"settings.others\":true,\"menus.index\":true,\"menus.create\":true,\"menus.edit\":true,\"menus.destroy\":true,\"optimize.settings\":true,\"pages.index\":true,\"pages.create\":true,\"pages.edit\":true,\"pages.destroy\":true,\"pages.export\":true,\"pages.import\":true,\"plugins.index\":true,\"plugins.edit\":true,\"plugins.remove\":true,\"plugins.marketplace\":true,\"sitemap.settings\":true,\"core.appearance\":true,\"theme.index\":true,\"theme.activate\":true,\"theme.remove\":true,\"theme.options\":true,\"theme.custom-css\":true,\"theme.custom-js\":true,\"theme.custom-html\":true,\"theme.robots-txt\":true,\"settings.website-tracking\":true,\"widgets.index\":true,\"ads.index\":true,\"ads.create\":true,\"ads.edit\":true,\"ads.destroy\":true,\"ads.settings\":true,\"analytics.general\":true,\"analytics.page\":true,\"analytics.browser\":true,\"analytics.referrer\":true,\"analytics.settings\":true,\"announcements.index\":true,\"announcements.create\":true,\"announcements.edit\":true,\"announcements.destroy\":true,\"announcements.settings\":true,\"audit-log.index\":true,\"audit-log.destroy\":true,\"backups.index\":true,\"backups.create\":true,\"backups.restore\":true,\"backups.destroy\":true,\"plugins.blog\":true,\"posts.index\":true,\"posts.create\":true,\"posts.edit\":true,\"posts.destroy\":true,\"categories.index\":true,\"categories.create\":true,\"categories.edit\":true,\"categories.destroy\":true,\"tags.index\":true,\"blog.reports\":true,\"tags.create\":true,\"tags.edit\":true,\"tags.destroy\":true,\"blog.settings\":true,\"posts.export\":true,\"posts.import\":true,\"captcha.settings\":true,\"contacts.index\":true,\"contacts.edit\":true,\"contacts.destroy\":true,\"contact.custom-fields\":true,\"contact.settings\":true,\"fob-comment.index\":true,\"fob-comment.comments.index\":true,\"fob-comment.comments.edit\":true,\"fob-comment.comments.destroy\":true,\"fob-comment.comments.reply\":true,\"fob-comment.settings\":true,\"galleries.index\":true,\"galleries.create\":true,\"galleries.edit\":true,\"galleries.destroy\":true,\"languages.index\":true,\"languages.create\":true,\"languages.edit\":true,\"languages.destroy\":true,\"translations.import\":true,\"translations.export\":true,\"property-translations.import\":true,\"property-translations.export\":true,\"page-translations.export\":true,\"page-translations.import\":true,\"newsletter.index\":true,\"newsletter.destroy\":true,\"newsletter.settings\":true,\"plugins.translation\":true,\"translations.locales\":true,\"translations.theme-translations\":true,\"translations.index\":true,\"theme-translations.export\":true,\"other-translations.export\":true,\"theme-translations.import\":true,\"other-translations.import\":true,\"api.settings\":true,\"api.sanctum-token.index\":true,\"api.sanctum-token.create\":true,\"api.sanctum-token.destroy\":true}','Admin users role',1,1,1,'2026-07-22 00:26:38','2026-07-22 00:26:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'media_random_hash','8e2ec9378174bed0971ed3b03cd19ca8',NULL,'2026-07-22 00:26:40'),(2,'api_enabled','0',NULL,'2026-07-22 00:26:40'),(3,'activated_plugins','[\"language\",\"language-advanced\",\"ads\",\"analytics\",\"announcement\",\"audit-log\",\"backup\",\"blog\",\"captcha\",\"contact\",\"cookie-consent\",\"fob-comment\",\"gallery\",\"newsletter\",\"rss-feed\",\"translation\"]',NULL,'2026-07-22 00:26:40'),(4,'analytics_dashboard_widgets','0','2026-07-22 00:26:37','2026-07-22 00:26:37'),(5,'enable_recaptcha_botble_contact_forms_fronts_contact_form','1','2026-07-22 00:26:38','2026-07-22 00:26:38'),(6,'enable_recaptcha_botble_newsletter_forms_fronts_newsletter_form','1','2026-07-22 00:26:38','2026-07-22 00:26:38'),(7,'theme','stories',NULL,'2026-07-22 00:26:40'),(8,'show_admin_bar','1',NULL,'2026-07-22 00:26:40'),(9,'language_hide_default','1',NULL,'2026-07-22 00:26:40'),(10,'language_switcher_display','dropdown',NULL,'2026-07-22 00:26:40'),(11,'language_display','all',NULL,'2026-07-22 00:26:40'),(12,'language_hide_languages','[]',NULL,'2026-07-22 00:26:40'),(13,'theme-stories-cookie_consent_message','Your experience on this site will be improved by allowing cookies ',NULL,'2026-07-22 00:26:40'),(14,'theme-stories-cookie_consent_learn_more_url','/cookie-policy',NULL,'2026-07-22 00:26:40'),(15,'theme-stories-cookie_consent_learn_more_text','Cookie Policy',NULL,'2026-07-22 00:26:40'),(16,'theme-stories-social_links','[[{\"key\":\"name\",\"value\":\"Facebook\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-facebook\"},{\"key\":\"url\",\"value\":\"https:\\/\\/www.facebook.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#3b5999\"}],[{\"key\":\"name\",\"value\":\"X (Twitter)\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-x\"},{\"key\":\"url\",\"value\":\"https:\\/\\/x.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#000\"}],[{\"key\":\"name\",\"value\":\"linkedin\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-linkedin\"},{\"key\":\"url\",\"value\":\"https:\\/\\/www.linkedin.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#0a66c2\"}]]',NULL,'2026-07-22 00:26:40'),(17,'theme-stories-social_sharing','[[{\"key\":\"social\",\"value\":\"facebook\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-facebook\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#3b5999\"}],[{\"key\":\"social\",\"value\":\"x\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-x\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#000\"}],[{\"key\":\"social\",\"value\":\"linkedin\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-linkedin\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#0a66c2\"}]]',NULL,'2026-07-22 00:26:40'),(18,'theme-stories-site_title','Stories - Laravel Personal Blog Script',NULL,'2026-07-22 00:26:40'),(19,'theme-stories-seo_description','Stories is a clean and minimal Laravel blog script perfect for writers who need to create a personal blog site with simple creative features and effects to make readers feel the pleasure of reading blog posts and articles.',NULL,'2026-07-22 00:26:40'),(20,'theme-stories-copyright','©%Y Stories - Laravel Personal Blog Script',NULL,'2026-07-22 00:26:40'),(21,'theme-stories-designed_by','Designed by AliThemes | All rights reserved.',NULL,'2026-07-22 00:26:40'),(22,'theme-stories-favicon','general/favicon.png',NULL,'2026-07-22 00:26:40'),(23,'theme-stories-site_description','Start writing, no matter what. The water does not flow until the faucet is turned on.',NULL,'2026-07-22 00:26:40'),(24,'theme-stories-address','123 Main Street New York, NY 100012',NULL,'2026-07-22 00:26:40'),(25,'theme-stories-facebook','https://facebook.com',NULL,'2026-07-22 00:26:40'),(26,'theme-stories-twitter','https://twitter.com',NULL,'2026-07-22 00:26:40'),(27,'theme-stories-youtube','https://youtube.com',NULL,'2026-07-22 00:26:40'),(28,'theme-stories-homepage_id','1',NULL,'2026-07-22 00:26:40'),(29,'theme-stories-blog_page_id','4',NULL,'2026-07-22 00:26:40'),(30,'theme-stories-logo','general/logo.png',NULL,'2026-07-22 00:26:40'),(31,'theme-stories-action_button_text','Buy Now',NULL,'2026-07-22 00:26:40'),(32,'theme-stories-action_button_url','https://botble.com/go/stories',NULL,'2026-07-22 00:26:40'),(33,'theme-stories-vi-primary_font','Roboto',NULL,'2026-07-22 00:26:40'),(34,'theme-stories-ar-site_title','ستوريز - مدونة شخصية بلارافيل',NULL,'2026-07-22 00:26:40'),(35,'theme-stories-ar-seo_description','ستوريز هي مدونة لارافيل نظيفة وبسيطة مثالية للكتّاب الذين يحتاجون إلى إنشاء موقع مدونة شخصية بميزات إبداعية بسيطة.',NULL,'2026-07-22 00:26:40'),(36,'theme-stories-ar-copyright','©%Y ستوريز - مدونة شخصية بلارافيل',NULL,'2026-07-22 00:26:40'),(37,'theme-stories-ar-cookie_consent_message','سيتم تحسين تجربتك على هذا الموقع من خلال السماح بملفات تعريف الارتباط',NULL,'2026-07-22 00:26:40'),(38,'theme-stories-ar-cookie_consent_learn_more_text','سياسة ملفات تعريف الارتباط',NULL,'2026-07-22 00:26:40'),(39,'theme-stories-ar-designed_by','تصميم AliThemes | جميع الحقوق محفوظة.',NULL,'2026-07-22 00:26:40'),(40,'theme-stories-ar-site_description','ابدأ بالكتابة، بغض النظر عن أي شيء. الماء لا يتدفق حتى يُفتح الصنبور.',NULL,'2026-07-22 00:26:40'),(41,'theme-stories-ar-action_button_text','اشترِ الآن',NULL,'2026-07-22 00:26:40'),(42,'theme-stories-vi-site_title','Stories - Blog Cá nhân Laravel',NULL,'2026-07-22 00:26:40'),(43,'theme-stories-vi-seo_description','Stories là một blog Laravel sạch và tối giản hoàn hảo cho các nhà văn cần tạo trang blog cá nhân với các tính năng sáng tạo đơn giản.',NULL,'2026-07-22 00:26:40'),(44,'theme-stories-vi-copyright','©%Y Stories - Blog Cá nhân Laravel',NULL,'2026-07-22 00:26:40'),(45,'theme-stories-vi-cookie_consent_message','Trải nghiệm của bạn trên trang web này sẽ được cải thiện bằng cách cho phép cookie',NULL,'2026-07-22 00:26:40'),(46,'theme-stories-vi-cookie_consent_learn_more_text','Chính sách Cookie',NULL,'2026-07-22 00:26:40'),(47,'theme-stories-vi-designed_by','Thiết kế bởi AliThemes | Tất cả các quyền được bảo lưu.',NULL,'2026-07-22 00:26:40'),(48,'theme-stories-vi-site_description','Hãy bắt đầu viết, bất kể điều gì. Nước không chảy cho đến khi vòi được mở.',NULL,'2026-07-22 00:26:40'),(49,'theme-stories-vi-action_button_text','Mua ngay',NULL,'2026-07-22 00:26:40'),(50,'theme-stories-fr-site_title','Stories - Blog Personnel Laravel',NULL,'2026-07-22 00:26:40'),(51,'theme-stories-fr-seo_description','Stories est un blog Laravel épuré et minimaliste parfait pour les écrivains qui souhaitent créer un blog personnel avec des fonctionnalités créatives simples.',NULL,'2026-07-22 00:26:40'),(52,'theme-stories-fr-copyright','©%Y Stories - Blog Personnel Laravel',NULL,'2026-07-22 00:26:40'),(53,'theme-stories-fr-cookie_consent_message','Votre expérience sur ce site sera améliorée en autorisant les cookies',NULL,'2026-07-22 00:26:40'),(54,'theme-stories-fr-cookie_consent_learn_more_text','Politique de cookies',NULL,'2026-07-22 00:26:40'),(55,'theme-stories-fr-designed_by','Conçu par AliThemes | Tous droits réservés.',NULL,'2026-07-22 00:26:40'),(56,'theme-stories-fr-site_description','Commencez à écrire, quoi qu\'il arrive. L\'eau ne coule pas tant que le robinet n\'est pas ouvert.',NULL,'2026-07-22 00:26:40'),(57,'theme-stories-fr-action_button_text','Acheter',NULL,'2026-07-22 00:26:40'),(58,'theme-stories-id-site_title','Stories - Blog Pribadi Laravel',NULL,'2026-07-22 00:26:40'),(59,'theme-stories-id-seo_description','Stories adalah blog Laravel yang bersih dan minimalis sempurna untuk penulis yang ingin membuat situs blog pribadi dengan fitur kreatif sederhana.',NULL,'2026-07-22 00:26:40'),(60,'theme-stories-id-copyright','©%Y Stories - Blog Pribadi Laravel',NULL,'2026-07-22 00:26:40'),(61,'theme-stories-id-cookie_consent_message','Pengalaman Anda di situs ini akan ditingkatkan dengan mengizinkan cookie',NULL,'2026-07-22 00:26:40'),(62,'theme-stories-id-cookie_consent_learn_more_text','Kebijakan Cookie',NULL,'2026-07-22 00:26:40'),(63,'theme-stories-id-designed_by','Dirancang oleh AliThemes | Semua hak dilindungi.',NULL,'2026-07-22 00:26:40'),(64,'theme-stories-id-site_description','Mulailah menulis, apa pun yang terjadi. Air tidak mengalir sampai keran dibuka.',NULL,'2026-07-22 00:26:40'),(65,'theme-stories-id-action_button_text','Beli Sekarang',NULL,'2026-07-22 00:26:40'),(66,'theme-stories-tr-site_title','Stories - Laravel Kişisel Blog',NULL,'2026-07-22 00:26:40'),(67,'theme-stories-tr-seo_description','Stories, basit yaratıcı özelliklerle kişisel blog sitesi oluşturmak isteyen yazarlar için mükemmel, temiz ve minimalist bir Laravel blog scriptidir.',NULL,'2026-07-22 00:26:40'),(68,'theme-stories-tr-copyright','©%Y Stories - Laravel Kişisel Blog',NULL,'2026-07-22 00:26:40'),(69,'theme-stories-tr-cookie_consent_message','Bu sitedeki deneyiminiz çerezlere izin verilerek iyileştirilecektir',NULL,'2026-07-22 00:26:40'),(70,'theme-stories-tr-cookie_consent_learn_more_text','Çerez Politikası',NULL,'2026-07-22 00:26:40'),(71,'theme-stories-tr-designed_by','AliThemes tarafından tasarlandı | Tüm hakları saklıdır.',NULL,'2026-07-22 00:26:40'),(72,'theme-stories-tr-site_description','Ne olursa olsun yazmaya başlayın. Musluk açılmadan su akmaz.',NULL,'2026-07-22 00:26:40'),(73,'theme-stories-tr-action_button_text','Satın Al',NULL,'2026-07-22 00:26:40'),(74,'admin_favicon','general/favicon.png',NULL,'2026-07-22 00:26:40'),(75,'admin_logo','general/logo-white.png',NULL,'2026-07-22 00:26:40'),(76,'announcement_max_width','1110',NULL,NULL),(77,'announcement_text_color','#687385',NULL,NULL),(78,'announcement_background_color','#f8f8f8',NULL,NULL),(79,'announcement_text_alignment','start',NULL,NULL),(80,'announcement_dismissible','1',NULL,NULL),(81,'announcement_font_size','12',NULL,NULL),(82,'announcement_font_size_unit','px',NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slugs`
--

DROP TABLE IF EXISTS `slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slugs_reference_id_index` (`reference_id`),
  KEY `slugs_key_index` (`key`),
  KEY `slugs_prefix_index` (`prefix`),
  KEY `slugs_reference_index` (`reference_id`,`reference_type`),
  KEY `idx_key_prefix` (`key`,`prefix`),
  KEY `idx_slugs_reference` (`reference_type`,`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs`
--

LOCK TABLES `slugs` WRITE;
/*!40000 ALTER TABLE `slugs` DISABLE KEYS */;
INSERT INTO `slugs` VALUES (1,'home',1,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(2,'home-2',2,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(3,'home-3',3,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(4,'blog',4,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(5,'contact',5,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(6,'cookie-policy',6,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(7,'blog-list-layout',7,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(8,'blog-big-layout',8,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(9,'blog-grid-layout',9,'Botble\\Page\\Models\\Page','','2026-07-22 00:26:38','2026-07-22 00:26:38'),(10,'uncategorized',1,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(11,'travel',2,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(12,'guides',3,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(13,'destination',4,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(14,'food',5,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(15,'hotels',6,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(16,'review',7,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(17,'healthy',8,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(18,'lifestyle',9,'Botble\\Blog\\Models\\Category','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(19,'general',1,'Botble\\Blog\\Models\\Tag','tag','2026-07-22 00:26:40','2026-07-22 00:26:40'),(20,'design',2,'Botble\\Blog\\Models\\Tag','tag','2026-07-22 00:26:40','2026-07-22 00:26:40'),(21,'fashion',3,'Botble\\Blog\\Models\\Tag','tag','2026-07-22 00:26:40','2026-07-22 00:26:40'),(22,'branding',4,'Botble\\Blog\\Models\\Tag','tag','2026-07-22 00:26:40','2026-07-22 00:26:40'),(23,'modern',5,'Botble\\Blog\\Models\\Tag','tag','2026-07-22 00:26:40','2026-07-22 00:26:40'),(24,'the-top-2020-handbag-trends-to-know',1,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(25,'top-search-engine-optimization-strategies',2,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(26,'which-company-would-you-choose',3,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(27,'used-car-dealer-sales-tricks-exposed',4,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(28,'20-ways-to-sell-your-product-faster',5,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(29,'the-secrets-of-rich-and-famous-writers',6,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(30,'imagine-losing-20-pounds-in-14-days',7,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(31,'are-you-still-using-that-slow-old-typewriter',8,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(32,'a-skin-cream-thats-proven-to-work',9,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(33,'10-reasons-to-start-your-own-profitable-website',10,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(34,'simple-ways-to-reduce-your-unwanted-wrinkles',11,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(35,'apple-imac-with-retina-5k-display-review',12,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(36,'10000-web-site-visitors-in-one-monthguaranteed',13,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(37,'unlock-the-secrets-of-selling-high-ticket-items',14,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(38,'4-expert-tips-on-how-to-choose-the-right-mens-wallet',15,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(39,'sexy-clutches-how-to-buy-wear-a-designer-clutch-bag',16,'Botble\\Blog\\Models\\Post','','2026-07-22 00:26:40','2026-07-22 00:26:40'),(40,'perfect',1,'Botble\\Gallery\\Models\\Gallery','galleries','2026-07-22 00:26:40','2026-07-22 00:26:40'),(41,'new-day',2,'Botble\\Gallery\\Models\\Gallery','galleries','2026-07-22 00:26:40','2026-07-22 00:26:40'),(42,'happy-day',3,'Botble\\Gallery\\Models\\Gallery','galleries','2026-07-22 00:26:40','2026-07-22 00:26:40'),(43,'nature',4,'Botble\\Gallery\\Models\\Gallery','galleries','2026-07-22 00:26:40','2026-07-22 00:26:40'),(44,'morning',5,'Botble\\Gallery\\Models\\Gallery','galleries','2026-07-22 00:26:40','2026-07-22 00:26:40'),(45,'photography',6,'Botble\\Gallery\\Models\\Gallery','galleries','2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `slugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slugs_translations`
--

DROP TABLE IF EXISTS `slugs_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slugs_id` bigint unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`lang_code`,`slugs_id`),
  KEY `idx_slugid_key_prefix` (`slugs_id`,`key`,`prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs_translations`
--

LOCK TABLES `slugs_translations` WRITE;
/*!40000 ALTER TABLE `slugs_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `slugs_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'General',1,'Botble\\ACL\\Models\\User',NULL,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(2,'Design',1,'Botble\\ACL\\Models\\User',NULL,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(3,'Fashion',1,'Botble\\ACL\\Models\\User',NULL,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(4,'Branding',1,'Botble\\ACL\\Models\\User',NULL,'published','2026-07-22 00:26:40','2026-07-22 00:26:40'),(5,'Modern',1,'Botble\\ACL\\Models\\User',NULL,'published','2026-07-22 00:26:40','2026-07-22 00:26:40');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_translations`
--

DROP TABLE IF EXISTS `tags_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`tags_id`),
  KEY `idx_tags_trans_tags_id` (`tags_id`),
  KEY `idx_tags_trans_tag_lang` (`tags_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags_translations`
--

LOCK TABLES `tags_translations` WRITE;
/*!40000 ALTER TABLE `tags_translations` DISABLE KEYS */;
INSERT INTO `tags_translations` VALUES ('ar',1,'عام',NULL),('ar',2,'تصميم',NULL),('ar',3,'أزياء',NULL),('ar',4,'علامة تجارية',NULL),('ar',5,'حديث',NULL),('fr',1,'Général',NULL),('fr',2,'Design',NULL),('fr',3,'Mode',NULL),('fr',4,'Marque',NULL),('fr',5,'Moderne',NULL),('id',1,'Umum',NULL),('id',2,'Desain',NULL),('id',3,'Fashion',NULL),('id',4,'Branding',NULL),('id',5,'Modern',NULL),('tr',1,'Genel',NULL),('tr',2,'Tasarım',NULL),('tr',3,'Moda',NULL),('tr',4,'Markalaşma',NULL),('tr',5,'Modern',NULL),('vi',1,'Tổng hợp',NULL),('vi',2,'Thiết kế',NULL),('vi',3,'Thời trang',NULL),('vi',4,'Thương hiệu',NULL),('vi',5,'Hiện đại',NULL);
/*!40000 ALTER TABLE `tags_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_meta_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_settings_user_type_user_id_key_unique` (`user_type`,`user_id`,`key`),
  KEY `user_settings_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `user_settings_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_id` bigint unsigned DEFAULT NULL,
  `super_user` tinyint(1) NOT NULL DEFAULT '0',
  `manage_supers` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  `sessions_invalidated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@company.com',NULL,NULL,'$2y$12$wbQW./jRmvdxH4V7dqc4FuFa6TKIa13kTWeHBCAH9w7.MSybeduVe',NULL,'2026-07-22 00:26:38','2026-07-22 00:26:38','System','Admin','admin',2,1,1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sidebar_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '0',
  `data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widgets_unique_index` (`theme`,`sidebar_id`,`widget_id`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` VALUES (1,'CustomMenuWidget','footer_sidebar','stories',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"Quick links\",\"menu_id\":\"quick-links\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(2,'TagsWidget','footer_sidebar','stories',1,'{\"id\":\"TagsWidget\",\"name\":\"Tags\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(3,'NewsletterWidget','footer_sidebar','stories',2,'{\"id\":\"NewsletterWidget\",\"name\":\"Newsletter\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(4,'AboutWidget','primary_sidebar','stories',0,'{\"id\":\"AboutWidget\",\"name\":\"Hello, I\'m Steven\",\"description\":\"Hi, I\'m Steven, a Florida native, who left my career in corporate wealth management six years ago to embark on a summer of soul searching that would change the course of my life forever.\",\"image\":\"general\\/author.jpg\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(5,'PopularPostsWidget','primary_sidebar','stories',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"Most popular\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(6,'GalleriesWidget','primary_sidebar','stories',2,'{\"id\":\"GalleriesWidget\",\"name\":\"Galleries\",\"number_display\":6}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(7,'CustomMenuWidget','footer_sidebar','stories-ar',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"\\u0631\\u0648\\u0627\\u0628\\u0637 \\u0633\\u0631\\u064a\\u0639\\u0629\",\"menu_id\":\"quick-links-ar\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(8,'NewsletterWidget','footer_sidebar','stories-ar',2,'{\"id\":\"NewsletterWidget\",\"name\":\"\\u0627\\u0644\\u0646\\u0634\\u0631\\u0629 \\u0627\\u0644\\u0628\\u0631\\u064a\\u062f\\u064a\\u0629\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(9,'TagsWidget','footer_sidebar','stories-ar',1,'{\"id\":\"TagsWidget\",\"name\":\"\\u0627\\u0644\\u0639\\u0644\\u0627\\u0645\\u0627\\u062a\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(10,'AboutWidget','primary_sidebar','stories-ar',0,'{\"id\":\"AboutWidget\",\"name\":\"\\u0645\\u0631\\u062d\\u0628\\u0627\\u064b\\u060c \\u0623\\u0646\\u0627 \\u0633\\u062a\\u064a\\u0641\\u0646\",\"description\":\"\\u0645\\u0631\\u062d\\u0628\\u0627\\u064b\\u060c \\u0623\\u0646\\u0627 \\u0633\\u062a\\u064a\\u0641\\u0646\\u060c \\u0645\\u0646 \\u0645\\u0648\\u0627\\u0644\\u064a\\u062f \\u0641\\u0644\\u0648\\u0631\\u064a\\u062f\\u0627\\u060c \\u062a\\u0631\\u0643\\u062a \\u0645\\u0633\\u064a\\u0631\\u062a\\u064a \\u0627\\u0644\\u0645\\u0647\\u0646\\u064a\\u0629 \\u0641\\u064a \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062b\\u0631\\u0648\\u0627\\u062a \\u0642\\u0628\\u0644 \\u0633\\u062a \\u0633\\u0646\\u0648\\u0627\\u062a \\u0644\\u0644\\u0634\\u0631\\u0648\\u0639 \\u0641\\u064a \\u0631\\u062d\\u0644\\u0629 \\u0628\\u062d\\u062b \\u0639\\u0646 \\u0627\\u0644\\u0630\\u0627\\u062a \\u063a\\u064a\\u0651\\u0631\\u062a \\u0645\\u062c\\u0631\\u0649 \\u062d\\u064a\\u0627\\u062a\\u064a \\u0625\\u0644\\u0649 \\u0627\\u0644\\u0623\\u0628\\u062f.\",\"image\":\"general\\/author.jpg\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(11,'GalleriesWidget','primary_sidebar','stories-ar',2,'{\"id\":\"GalleriesWidget\",\"name\":\"\\u0627\\u0644\\u0645\\u0639\\u0627\\u0631\\u0636\",\"number_display\":6}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(12,'PopularPostsWidget','primary_sidebar','stories-ar',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"\\u0627\\u0644\\u0623\\u0643\\u062b\\u0631 \\u0634\\u0639\\u0628\\u064a\\u0629\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(13,'CustomMenuWidget','footer_sidebar','stories-vi',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"Li\\u00ean k\\u1ebft nhanh\",\"menu_id\":\"quick-links-vi\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(14,'NewsletterWidget','footer_sidebar','stories-vi',2,'{\"id\":\"NewsletterWidget\",\"name\":\"B\\u1ea3n tin\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(15,'TagsWidget','footer_sidebar','stories-vi',1,'{\"id\":\"TagsWidget\",\"name\":\"Th\\u1ebb\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(16,'AboutWidget','primary_sidebar','stories-vi',0,'{\"id\":\"AboutWidget\",\"name\":\"Xin ch\\u00e0o, t\\u00f4i l\\u00e0 Steven\",\"description\":\"Xin ch\\u00e0o, t\\u00f4i l\\u00e0 Steven, ng\\u01b0\\u1eddi g\\u1ed1c Florida, \\u0111\\u00e3 r\\u1eddi b\\u1ecf s\\u1ef1 nghi\\u1ec7p qu\\u1ea3n l\\u00fd t\\u00e0i s\\u1ea3n doanh nghi\\u1ec7p s\\u00e1u n\\u0103m tr\\u01b0\\u1edbc \\u0111\\u1ec3 b\\u1eaft \\u0111\\u1ea7u h\\u00e0nh tr\\u00ecnh t\\u00ecm ki\\u1ebfm b\\u1ea3n th\\u00e2n \\u0111\\u00e3 thay \\u0111\\u1ed5i cu\\u1ed9c \\u0111\\u1eddi t\\u00f4i m\\u00e3i m\\u00e3i.\",\"image\":\"general\\/author.jpg\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(17,'GalleriesWidget','primary_sidebar','stories-vi',2,'{\"id\":\"GalleriesWidget\",\"name\":\"Th\\u01b0 vi\\u1ec7n \\u1ea3nh\",\"number_display\":6}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(18,'PopularPostsWidget','primary_sidebar','stories-vi',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"Ph\\u1ed5 bi\\u1ebfn nh\\u1ea5t\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(19,'CustomMenuWidget','footer_sidebar','stories-fr',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"Liens rapides\",\"menu_id\":\"quick-links-fr\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(20,'NewsletterWidget','footer_sidebar','stories-fr',2,'{\"id\":\"NewsletterWidget\",\"name\":\"Newsletter\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(21,'TagsWidget','footer_sidebar','stories-fr',1,'{\"id\":\"TagsWidget\",\"name\":\"\\u00c9tiquettes\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(22,'AboutWidget','primary_sidebar','stories-fr',0,'{\"id\":\"AboutWidget\",\"name\":\"Bonjour, je suis Steven\",\"description\":\"Bonjour, je suis Steven, originaire de Floride, j\'ai quitt\\u00e9 ma carri\\u00e8re dans la gestion de patrimoine il y a six ans pour entreprendre une qu\\u00eate de sens qui allait changer le cours de ma vie pour toujours.\",\"image\":\"general\\/author.jpg\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(23,'GalleriesWidget','primary_sidebar','stories-fr',2,'{\"id\":\"GalleriesWidget\",\"name\":\"Galeries\",\"number_display\":6}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(24,'PopularPostsWidget','primary_sidebar','stories-fr',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"Les plus populaires\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(25,'CustomMenuWidget','footer_sidebar','stories-id',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"Tautan cepat\",\"menu_id\":\"quick-links-id\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(26,'NewsletterWidget','footer_sidebar','stories-id',2,'{\"id\":\"NewsletterWidget\",\"name\":\"Buletin\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(27,'TagsWidget','footer_sidebar','stories-id',1,'{\"id\":\"TagsWidget\",\"name\":\"Tag\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(28,'AboutWidget','primary_sidebar','stories-id',0,'{\"id\":\"AboutWidget\",\"name\":\"Halo, saya Steven\",\"description\":\"Halo, saya Steven, asli Florida, yang meninggalkan karir di manajemen kekayaan perusahaan enam tahun lalu untuk memulai perjalanan pencarian jati diri yang mengubah arah hidup saya selamanya.\",\"image\":\"general\\/author.jpg\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(29,'GalleriesWidget','primary_sidebar','stories-id',2,'{\"id\":\"GalleriesWidget\",\"name\":\"Galeri\",\"number_display\":6}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(30,'PopularPostsWidget','primary_sidebar','stories-id',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"Paling populer\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(31,'CustomMenuWidget','footer_sidebar','stories-tr',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"H\\u0131zl\\u0131 ba\\u011flant\\u0131lar\",\"menu_id\":\"quick-links-tr\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(32,'NewsletterWidget','footer_sidebar','stories-tr',2,'{\"id\":\"NewsletterWidget\",\"name\":\"B\\u00fclten\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(33,'TagsWidget','footer_sidebar','stories-tr',1,'{\"id\":\"TagsWidget\",\"name\":\"Etiketler\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(34,'AboutWidget','primary_sidebar','stories-tr',0,'{\"id\":\"AboutWidget\",\"name\":\"Merhaba, ben Steven\",\"description\":\"Merhaba, ben Steven, Floridal\\u0131y\\u0131m, alt\\u0131 y\\u0131l \\u00f6nce kurumsal varl\\u0131k y\\u00f6netimindeki kariyerimi b\\u0131rakarak hayat\\u0131m\\u0131n ak\\u0131\\u015f\\u0131n\\u0131 sonsuza dek de\\u011fi\\u015ftirecek bir kendini ke\\u015ffetme yolculu\\u011funa \\u00e7\\u0131kt\\u0131m.\",\"image\":\"general\\/author.jpg\"}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(35,'GalleriesWidget','primary_sidebar','stories-tr',2,'{\"id\":\"GalleriesWidget\",\"name\":\"Galeriler\",\"number_display\":6}','2026-07-22 00:26:39','2026-07-22 00:26:39'),(36,'PopularPostsWidget','primary_sidebar','stories-tr',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"En pop\\u00fcler\",\"number_display\":5}','2026-07-22 00:26:39','2026-07-22 00:26:39');
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-22 14:26:41
