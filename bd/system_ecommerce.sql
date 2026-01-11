-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql
-- Tiempo de generación: 11-01-2026 a las 21:53:56
-- Versión del servidor: 8.0.44
-- Versión de PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `system_ecommerce`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `access_logs`
--

CREATE TABLE `access_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `email_attempted` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` enum('login_success','login_failed','logout','password_reset','mfa_challenge','mfa_success','mfa_failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` json DEFAULT NULL,
  `failure_reason` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `access_logs`
--

INSERT INTO `access_logs` (`id`, `user_id`, `email_attempted`, `action`, `ip_address`, `user_agent`, `location`, `failure_reason`, `created_at`) VALUES
(1, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 02:08:50'),
(2, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 02:09:04'),
(3, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 02:10:04'),
(4, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 02:10:12'),
(5, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Cuenta bloqueada', '2026-01-11 02:10:27'),
(6, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Cuenta bloqueada', '2026-01-11 02:10:35'),
(7, 1, 'admin@syncro.com', 'login_failed', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 02:12:28'),
(8, 1, 'admin@syncro.com', 'login_success', '172.20.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, NULL, '2026-01-11 02:14:14'),
(9, 1, 'admin@syncro.com', 'login_failed', '172.19.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 21:40:14'),
(10, 1, 'admin@syncro.com', 'login_failed', '172.19.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 'Contraseña incorrecta', '2026-01-11 21:40:16'),
(11, 1, 'admin@syncro.com', 'login_success', '172.19.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, NULL, '2026-01-11 21:40:24'),
(12, 1, 'admin@syncro.com', 'login_success', '172.19.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, NULL, '2026-01-11 21:51:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activities`
--

CREATE TABLE `activities` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_type_id` int UNSIGNED NOT NULL,
  `subject` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `related_to_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_to_id` bigint UNSIGNED DEFAULT NULL,
  `secondary_related_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_related_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('not_started','in_progress','completed','waiting','deferred','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'not_started',
  `priority` enum('low','normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `duration_minutes` int UNSIGNED DEFAULT NULL,
  `actual_duration_minutes` int UNSIGNED DEFAULT NULL,
  `is_all_day` tinyint(1) DEFAULT '0',
  `reminder_datetime` datetime DEFAULT NULL,
  `reminder_sent` tinyint(1) DEFAULT '0',
  `recurrence_rule` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurrence_parent_id` bigint UNSIGNED DEFAULT NULL,
  `location` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_type` enum('in_person','phone','video','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outcome` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outcome_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `call_direction` enum('inbound','outbound') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_duration_seconds` int UNSIGNED DEFAULT NULL,
  `call_recording_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_to_id` bigint UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_checklists`
--

CREATE TABLE `activity_checklists` (
  `id` bigint UNSIGNED NOT NULL,
  `activity_id` bigint UNSIGNED NOT NULL,
  `item_text` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_participants`
--

CREATE TABLE `activity_participants` (
  `id` bigint UNSIGNED NOT NULL,
  `activity_id` bigint UNSIGNED NOT NULL,
  `participant_type` enum('user','contact','lead') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `participant_id` bigint UNSIGNED NOT NULL,
  `role` enum('organizer','required','optional','resource') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'required',
  `response_status` enum('pending','accepted','declined','tentative') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `responded_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_timeline`
--

CREATE TABLE `activity_timeline` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `event_type` enum('activity','note','email','call','meeting','stage_change','field_change','document','task_completed','deal_won','deal_lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_subtype` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `is_pinned` tinyint(1) DEFAULT '0',
  `visibility` enum('public','private','internal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'public',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_types`
--

CREATE TABLE `activity_types` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('task','call','meeting','email','note','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_duration_minutes` int UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `entity_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `action` enum('create','update','delete','restore','login','logout','export','import','view') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `changed_fields` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brands`
--

CREATE TABLE `brands` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('email','sms','social','event','webinar','advertising','content','referral','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','scheduled','active','paused','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `objective` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_audience` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(18,2) DEFAULT NULL,
  `actual_cost` decimal(18,2) DEFAULT '0.00',
  `currency_id` int UNSIGNED DEFAULT NULL,
  `expected_revenue` decimal(18,2) DEFAULT NULL,
  `expected_response` decimal(5,2) DEFAULT NULL,
  `parent_campaign_id` bigint UNSIGNED DEFAULT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `sent_count` int UNSIGNED DEFAULT '0',
  `delivered_count` int UNSIGNED DEFAULT '0',
  `opened_count` int UNSIGNED DEFAULT '0',
  `clicked_count` int UNSIGNED DEFAULT '0',
  `bounced_count` int UNSIGNED DEFAULT '0',
  `unsubscribed_count` int UNSIGNED DEFAULT '0',
  `converted_count` int UNSIGNED DEFAULT '0',
  `revenue_generated` decimal(18,2) DEFAULT '0.00',
  `roi` decimal(10,2) DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaign_members`
--

CREATE TABLE `campaign_members` (
  `id` bigint UNSIGNED NOT NULL,
  `campaign_id` bigint UNSIGNED NOT NULL,
  `member_type` enum('contact','lead') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_id` bigint UNSIGNED NOT NULL,
  `status` enum('planned','sent','responded','converted','opted_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'planned',
  `first_responded_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `opted_out_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canned_responses`
--

CREATE TABLE `canned_responses` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortcut` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT '1',
  `actions` json DEFAULT NULL,
  `usage_count` int UNSIGNED DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `is_shared` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` int UNSIGNED NOT NULL,
  `subtotal` decimal(15,2) DEFAULT '0.00',
  `discount_total` decimal(15,2) DEFAULT '0.00',
  `tax_total` decimal(15,2) DEFAULT '0.00',
  `shipping_total` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) DEFAULT '0.00',
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `billing_address_id` bigint UNSIGNED DEFAULT NULL,
  `shipping_address_id` bigint UNSIGNED DEFAULT NULL,
  `shipping_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `line_total` decimal(15,2) GENERATED ALWAYS AS ((((`quantity` * `unit_price`) - `discount_amount`) + `tax_amount`)) STORED,
  `custom_options` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companies`
--

CREATE TABLE `companies` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_id` int UNSIGNED DEFAULT NULL,
  `company_size` enum('1-10','11-50','51-200','201-500','501-1000','1001-5000','5000+') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annual_revenue` decimal(18,2) DEFAULT NULL,
  `revenue_currency_id` int UNSIGNED DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_handle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `billing_address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country_id` int UNSIGNED DEFAULT NULL,
  `shipping_address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country_id` int UNSIGNED DEFAULT NULL,
  `account_type` enum('prospect','customer','partner','vendor','competitor','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'prospect',
  `rating` enum('hot','warm','cold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lifecycle_stage` enum('subscriber','lead','mql','sql','opportunity','customer','evangelist','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'lead',
  `source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `parent_company_id` bigint UNSIGNED DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `score` int DEFAULT '0',
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `companies`
--

INSERT INTO `companies` (`id`, `organization_id`, `uuid`, `name`, `legal_name`, `tax_id`, `industry_id`, `company_size`, `annual_revenue`, `revenue_currency_id`, `website`, `linkedin_url`, `facebook_url`, `twitter_handle`, `phone`, `fax`, `email`, `description`, `billing_address_line_1`, `billing_address_line_2`, `billing_city`, `billing_state`, `billing_postal_code`, `billing_country_id`, `shipping_address_line_1`, `shipping_address_line_2`, `shipping_city`, `shipping_state`, `shipping_postal_code`, `shipping_country_id`, `account_type`, `rating`, `lifecycle_stage`, `source`, `owner_id`, `parent_company_id`, `tags`, `custom_fields`, `score`, `last_activity_at`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '896e7b0a-ee98-11f0-9df9-b275b7b3d1cf', 'Empresa SAC', 'Empresa Sociedad Anónima Cerrada', NULL, NULL, NULL, NULL, NULL, 'https://empresa.com', NULL, NULL, NULL, '+51 1 234 5678', NULL, 'contacto@empresa.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'customer', NULL, 'lead', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(2, 1, '896ec18e-ee98-11f0-9df9-b275b7b3d1cf', 'TechCorp', 'TechCorp Solutions S.A.', NULL, NULL, NULL, NULL, NULL, 'https://techcorp.com', NULL, NULL, NULL, '+51 1 345 6789', NULL, 'info@techcorp.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'prospect', NULL, 'lead', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(3, 1, '896ec4e4-ee98-11f0-9df9-b275b7b3d1cf', 'InnovaTech Perú', 'InnovaTech Perú S.A.C.', NULL, NULL, NULL, NULL, NULL, 'https://innovatech.pe', NULL, NULL, NULL, '+51 1 456 7890', NULL, 'contacto@innovatech.pe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'customer', NULL, 'lead', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consents`
--

CREATE TABLE `consents` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `consent_type_id` int UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_granted` tinyint(1) NOT NULL,
  `source` enum('web_form','api','import','manual','double_opt_in') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `granted_at` timestamp NULL DEFAULT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consent_types`
--

CREATE TABLE `consent_types` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_required` tinyint(1) DEFAULT '0',
  `legal_basis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retention_days` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (concat_ws(_utf8mb4' ',nullif(`prefix`,_utf8mb4''),`first_name`,nullif(`middle_name`,_utf8mb4''),`last_name`,nullif(`suffix`,_utf8mb4''))) STORED,
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `reports_to_id` bigint UNSIGNED DEFAULT NULL,
  `assistant_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assistant_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `linkedin_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_handle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_country_id` int UNSIGNED DEFAULT NULL,
  `other_address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_country_id` int UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lead_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lifecycle_stage` enum('subscriber','lead','mql','sql','opportunity','customer','evangelist','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'lead',
  `status` enum('active','inactive','do_not_contact') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `do_not_call` tinyint(1) DEFAULT '0',
  `do_not_email` tinyint(1) DEFAULT '0',
  `has_opted_out_of_email` tinyint(1) DEFAULT '0',
  `has_opted_out_of_fax` tinyint(1) DEFAULT '0',
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `score` int DEFAULT '0',
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`id`, `organization_id`, `uuid`, `prefix`, `first_name`, `middle_name`, `last_name`, `suffix`, `nickname`, `email`, `secondary_email`, `phone`, `mobile`, `work_phone`, `fax`, `job_title`, `department`, `company_id`, `reports_to_id`, `assistant_name`, `assistant_phone`, `birth_date`, `linkedin_url`, `twitter_handle`, `facebook_url`, `skype`, `mailing_address_line_1`, `mailing_address_line_2`, `mailing_city`, `mailing_state`, `mailing_postal_code`, `mailing_country_id`, `other_address_line_1`, `other_address_line_2`, `other_city`, `other_state`, `other_postal_code`, `other_country_id`, `description`, `lead_source`, `lifecycle_stage`, `status`, `do_not_call`, `do_not_email`, `has_opted_out_of_email`, `has_opted_out_of_fax`, `owner_id`, `tags`, `custom_fields`, `score`, `last_activity_at`, `last_contacted_at`, `customer_id`, `user_id`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '8967be85-ee98-11f0-9df9-b275b7b3d1cf', NULL, 'María', NULL, 'González', NULL, NULL, 'maria@empresa.com', NULL, '+51 987 654 321', '+51 987 654 321', NULL, NULL, 'Gerente de Compras', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lead', 'active', 0, 0, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(2, 1, '89691889-ee98-11f0-9df9-b275b7b3d1cf', NULL, 'Carlos', NULL, 'Mendoza', NULL, NULL, 'carlos.m@gmail.com', NULL, '+51 999 888 777', '+51 999 888 777', NULL, NULL, 'Director Comercial', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lead', 'active', 0, 0, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(3, 1, '89692cb5-ee98-11f0-9df9-b275b7b3d1cf', NULL, 'Ana', NULL, 'Torres', NULL, NULL, 'atorres@techcorp.com', NULL, '+51 912 345 678', '+51 912 345 678', NULL, NULL, 'CEO', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lead', 'active', 0, 0, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(4, 1, '89693b73-ee98-11f0-9df9-b275b7b3d1cf', NULL, 'Roberto', NULL, 'Díaz', NULL, NULL, 'roberto.diaz@mail.com', NULL, '+51 945 678 123', '+51 945 678 123', NULL, NULL, 'Jefe de Operaciones', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lead', 'active', 0, 0, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(5, 1, '89693d35-ee98-11f0-9df9-b275b7b3d1cf', NULL, 'Laura', NULL, 'Vega', NULL, NULL, 'lvega@innovatech.pe', NULL, '+51 978 456 123', '+51 978 456 123', NULL, NULL, 'Gerente de TI', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lead', 'active', 0, 0, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL),
(6, 1, '89693e9a-ee98-11f0-9df9-b275b7b3d1cf', NULL, 'Pedro', NULL, 'Sánchez', NULL, NULL, 'pedro.sanchez@mail.com', NULL, '+51 956 789 012', '+51 956 789 012', NULL, NULL, 'Analista', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lead', 'active', 0, 0, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 02:52:15', '2026-01-11 02:52:15', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_company_relations`
--

CREATE TABLE `contact_company_relations` (
  `contact_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `role` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE `countries` (
  `id` int UNSIGNED NOT NULL,
  `iso_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code_3` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_es` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupons`
--

CREATE TABLE `coupons` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed_cart','fixed_product','free_shipping') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(15,2) NOT NULL,
  `minimum_amount` decimal(15,2) DEFAULT NULL,
  `maximum_discount` decimal(15,2) DEFAULT NULL,
  `usage_limit` int UNSIGNED DEFAULT NULL,
  `usage_limit_per_customer` int UNSIGNED DEFAULT '1',
  `used_count` int UNSIGNED DEFAULT '0',
  `applies_to` enum('all','categories','products','customer_groups') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `applies_to_ids` json DEFAULT NULL,
  `excludes_sale_items` tinyint(1) DEFAULT '0',
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_to` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupon_usage`
--

CREATE TABLE `coupon_usage` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` int UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(15,2) NOT NULL,
  `used_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currencies`
--

CREATE TABLE `currencies` (
  `id` int UNSIGNED NOT NULL,
  `code` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_places` tinyint UNSIGNED DEFAULT '2',
  `exchange_rate` decimal(18,6) DEFAULT '1.000000',
  `is_base` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currency_exchange_history`
--

CREATE TABLE `currency_exchange_history` (
  `id` bigint UNSIGNED NOT NULL,
  `currency_id` int UNSIGNED NOT NULL,
  `exchange_rate` decimal(18,6) NOT NULL,
  `effective_date` date NOT NULL,
  `source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `customer_group_id` int UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female','other','unspecified') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepts_marketing` tinyint(1) DEFAULT '0',
  `marketing_consent_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tags` json DEFAULT NULL,
  `total_orders` int UNSIGNED DEFAULT '0',
  `total_spent` decimal(15,2) DEFAULT '0.00',
  `average_order_value` decimal(15,2) DEFAULT '0.00',
  `last_order_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','blocked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `custom_fields` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `address_type` enum('billing','shipping','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'both',
  `is_default` tinyint(1) DEFAULT '0',
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int UNSIGNED NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT '0.00',
  `price_list_id` int UNSIGNED DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_field_definitions`
--

CREATE TABLE `custom_field_definitions` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_label` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` enum('text','textarea','number','decimal','date','datetime','boolean','select','multiselect','lookup','url','email','phone','formula','file','signature','geolocation') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json DEFAULT NULL,
  `default_value` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `help_text` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validation_rules` json DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT '0',
  `is_unique` tinyint(1) DEFAULT '0',
  `is_searchable` tinyint(1) DEFAULT '0',
  `is_filterable` tinyint(1) DEFAULT '0',
  `is_visible_in_list` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `field_group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` bigint UNSIGNED NOT NULL,
  `field_definition_id` int UNSIGNED NOT NULL,
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `value_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `value_number` decimal(20,6) DEFAULT NULL,
  `value_date` datetime DEFAULT NULL,
  `value_boolean` tinyint(1) DEFAULT NULL,
  `value_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data_requests`
--

CREATE TABLE `data_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `request_type` enum('access','rectification','erasure','portability','restriction','objection') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requester_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requester_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','in_progress','completed','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `due_date` date DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entity_tags`
--

CREATE TABLE `entity_tags` (
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `tag_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `form_submissions`
--

CREATE TABLE `form_submissions` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL,
  `data` json NOT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `lead_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_source` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed` tinyint(1) DEFAULT '0',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inbox_conversations`
--

CREATE TABLE `inbox_conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL DEFAULT '1',
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `channel` enum('email','whatsapp','sms','telegram','webchat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `status` enum('open','pending','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_message_preview` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `assigned_to_id` bigint UNSIGNED DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inbox_conversations`
--

INSERT INTO `inbox_conversations` (`id`, `organization_id`, `uuid`, `contact_id`, `channel`, `status`, `priority`, `subject`, `last_message_preview`, `last_message_at`, `is_read`, `assigned_to_id`, `tags`, `created_at`, `updated_at`) VALUES
(1, 1, '897d3d2e-ee98-11f0-9df9-b275b7b3d1cf', 1, 'email', 'open', 'high', 'Consulta sobre cotización #2847', 'Hola, quisiera saber el estado de mi cotización enviada la semana pasada...', '2025-01-10 14:32:00', 0, NULL, NULL, '2025-01-07 16:45:00', '2026-01-11 03:07:55'),
(2, 1, '897d53d8-ee98-11f0-9df9-b275b7b3d1cf', 2, 'whatsapp', 'open', 'normal', NULL, 'Perfecto, entonces quedamos para el jueves a las 10am', '2025-01-10 13:15:00', 0, NULL, NULL, '2025-01-09 10:00:00', '2026-01-11 03:07:04'),
(3, 1, '897d560f-ee98-11f0-9df9-b275b7b3d1cf', 3, 'email', 'pending', 'normal', 'Re: Propuesta de servicios', 'Gracias por la información. He revisado la propuesta con mi equipo y...', '2025-01-10 11:45:00', 0, NULL, NULL, '2025-01-05 09:30:00', '2026-01-11 03:07:51'),
(4, 1, '897d5700-ee98-11f0-9df9-b275b7b3d1cf', 4, 'sms', 'resolved', 'normal', NULL, 'Confirmado. Nos vemos mañana.', '2025-01-10 10:20:00', 0, NULL, NULL, '2025-01-10 09:00:00', '2026-01-11 03:07:52'),
(5, 1, '897d57b5-ee98-11f0-9df9-b275b7b3d1cf', 5, 'email', 'open', 'high', 'Solicitud de demo del sistema', 'Buenos días, estamos interesados en agendar una demostración de su plataforma CRM...', '2025-01-09 16:30:00', 0, NULL, NULL, '2025-01-09 16:30:00', '2026-01-11 03:07:54'),
(6, 1, '897d584e-ee98-11f0-9df9-b275b7b3d1cf', 6, 'whatsapp', 'pending', 'normal', NULL, 'Hola si tenemos disponibilidad para una llamada', '2026-01-11 02:56:18', 1, NULL, NULL, '2025-01-09 14:10:00', '2026-01-11 03:07:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inbox_messages`
--

CREATE TABLE `inbox_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `direction` enum('inbound','outbound') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_type` enum('contact','user','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint UNSIGNED DEFAULT NULL,
  `sender_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_html` longtext COLLATE utf8mb4_unicode_ci,
  `message_type` enum('text','html','attachment','note') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `external_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID del mensaje en el sistema externo (ej: Message-ID de email)',
  `in_reply_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inbox_messages`
--

INSERT INTO `inbox_messages` (`id`, `conversation_id`, `direction`, `sender_type`, `sender_id`, `sender_name`, `sender_email`, `content`, `content_html`, `message_type`, `external_id`, `in_reply_to`, `attachments`, `metadata`, `is_read`, `read_at`, `created_at`) VALUES
(1, 1, 'inbound', 'contact', 1, 'María González', 'maria@empresa.com', 'Buenas tardes,\n\nPor favor, ¿podrían enviarme una cotización actualizada para los servicios que conversamos en la reunión del lunes?\n\nGracias.', '<p>Buenas tardes,</p><p>Por favor, ¿podrían enviarme una cotización actualizada para los servicios que conversamos en la reunión del lunes?</p><p>Gracias.</p>', 'html', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-07 16:45:00'),
(2, 1, 'outbound', 'user', NULL, 'Fernando', 'fernando@ejemplo.com', 'Estimada María,\n\nAdjunto encontrará la cotización solicitada con los precios actualizados para el Q1 2025.\n\nQuedamos atentos a sus comentarios.\n\nSaludos cordiales', '<p>Estimada María,</p><p>Adjunto encontrará la cotización solicitada con los precios actualizados para el Q1 2025.</p><p>Quedamos atentos a sus comentarios.</p><p>Saludos cordiales</p>', 'html', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-08 10:15:00'),
(3, 1, 'inbound', 'contact', 1, 'María González', 'maria@empresa.com', 'Hola,\n\nQuisiera saber el estado de mi cotización enviada la semana pasada. Necesitamos confirmar el presupuesto antes del viernes para poder proceder con la orden de compra.\n\n¿Podrían indicarme si hay alguna actualización?\n\nSaludos,\nMaría González\nGerente de Compras', '<p>Hola,</p><p>Quisiera saber el estado de mi cotización enviada la semana pasada. Necesitamos confirmar el presupuesto antes del viernes para poder proceder con la orden de compra.</p><p>¿Podrían indicarme si hay alguna actualización?</p><p>Saludos,<br>María González<br>Gerente de Compras</p>', 'html', NULL, NULL, NULL, NULL, 1, '2026-01-11 02:52:17', '2025-01-10 14:32:00'),
(4, 2, 'inbound', 'contact', 2, 'Carlos Mendoza', '+51 999 888 777', 'Hola, ¿cómo están? Necesito agendar una reunión para revisar el proyecto.', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-09 10:00:00'),
(5, 2, 'outbound', 'user', NULL, 'Fernando', 'fernando@ejemplo.com', 'Hola Carlos! Claro, ¿qué día te viene bien? Tengo disponibilidad el jueves y viernes.', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-09 10:30:00'),
(6, 2, 'inbound', 'contact', 2, 'Carlos Mendoza', '+51 999 888 777', 'El jueves me funciona. ¿A las 10am está bien?', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-10 09:45:00'),
(7, 2, 'outbound', 'user', NULL, 'Fernando', 'fernando@ejemplo.com', 'Perfecto! Te envío la invitación al calendario.', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-10 10:00:00'),
(8, 2, 'inbound', 'contact', 2, 'Carlos Mendoza', '+51 999 888 777', 'Perfecto, entonces quedamos para el jueves a las 10am', NULL, 'text', NULL, NULL, NULL, NULL, 1, '2026-01-11 02:52:23', '2025-01-10 13:15:00'),
(9, 3, 'outbound', 'user', NULL, 'Fernando', 'fernando@ejemplo.com', 'Estimada Ana,\n\nLe envío la propuesta de servicios según lo conversado. Incluye los módulos de CRM, facturación y reportes.\n\nQuedo atento.', '<p>Estimada Ana,</p><p>Le envío la propuesta de servicios según lo conversado. Incluye los módulos de CRM, facturación y reportes.</p><p>Quedo atento.</p>', 'html', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-05 09:30:00'),
(10, 3, 'inbound', 'contact', 3, 'Ana Torres', 'atorres@techcorp.com', 'Gracias por la información. He revisado la propuesta con mi equipo y tenemos algunas consultas sobre los tiempos de implementación. ¿Podríamos agendar una llamada esta semana?', '<p>Gracias por la información. He revisado la propuesta con mi equipo y tenemos algunas consultas sobre los tiempos de implementación. ¿Podríamos agendar una llamada esta semana?</p>', 'html', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-10 11:45:00'),
(11, 4, 'outbound', 'user', NULL, 'Fernando', 'fernando@ejemplo.com', 'Hola Roberto, te confirmo la reunión de mañana a las 3pm en nuestras oficinas.', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-10 09:00:00'),
(12, 4, 'inbound', 'contact', 4, 'Roberto Díaz', '+51 945 678 123', 'Confirmado. Nos vemos mañana.', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-10 10:20:00'),
(13, 5, 'inbound', 'contact', 5, 'Laura Vega', 'lvega@innovatech.pe', 'Buenos días,\n\nEstamos interesados en agendar una demostración de su plataforma CRM. Somos una empresa de tecnología con 50 empleados y buscamos una solución integral.\n\n¿Tienen disponibilidad la próxima semana?\n\nSaludos,\nLaura Vega\nGerente de TI\nInnovaTech Perú', '<p>Buenos días,</p><p>Estamos interesados en agendar una demostración de su plataforma CRM. Somos una empresa de tecnología con 50 empleados y buscamos una solución integral.</p><p>¿Tienen disponibilidad la próxima semana?</p><p>Saludos,<br>Laura Vega<br>Gerente de TI<br>InnovaTech Perú</p>', 'html', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-09 16:30:00'),
(14, 6, 'inbound', 'contact', 6, 'Pedro Sánchez', '+51 956 789 012', '¿Tienen disponibilidad para una llamada hoy?', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2025-01-09 14:10:00'),
(15, 6, 'outbound', 'user', NULL, 'Usuario', 'usuario@sistema.com', 'Hola si tenemos disponibilidad para una llamada', NULL, 'text', NULL, NULL, NULL, NULL, 1, NULL, '2026-01-11 02:56:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `industries`
--

CREATE TABLE `industries` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_locations`
--

CREATE TABLE `inventory_locations` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `location_id` int UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `movement_type` enum('purchase','sale','adjustment','transfer_in','transfer_out','return','damage','count') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `quantity_before` int NOT NULL,
  `quantity_after` int NOT NULL,
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `reference_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_stock`
--

CREATE TABLE `inventory_stock` (
  `id` bigint UNSIGNED NOT NULL,
  `location_id` int UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `reserved_quantity` int NOT NULL DEFAULT '0',
  `available_quantity` int GENERATED ALWAYS AS ((`quantity` - `reserved_quantity`)) STORED,
  `reorder_point` int DEFAULT NULL,
  `reorder_quantity` int DEFAULT NULL,
  `last_counted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `journey_enrollments`
--

CREATE TABLE `journey_enrollments` (
  `id` bigint UNSIGNED NOT NULL,
  `journey_id` int UNSIGNED NOT NULL,
  `entity_type` enum('contact','lead') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `current_step_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('active','completed','exited','paused','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  `exited_at` timestamp NULL DEFAULT NULL,
  `exit_reason` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `converted` tinyint(1) DEFAULT '0',
  `converted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `journey_steps`
--

CREATE TABLE `journey_steps` (
  `id` bigint UNSIGNED NOT NULL,
  `journey_id` int UNSIGNED NOT NULL,
  `parent_step_id` bigint UNSIGNED DEFAULT NULL,
  `step_type` enum('email','sms','wait','condition','split','action','goal','exit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` json NOT NULL,
  `position_x` int DEFAULT NULL,
  `position_y` int DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `landing_pages`
--

CREATE TABLE `landing_pages` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `css_styles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `js_scripts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `form_id` int UNSIGNED DEFAULT NULL,
  `campaign_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('draft','published','unpublished') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `view_count` int UNSIGNED DEFAULT '0',
  `conversion_count` int UNSIGNED DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
--

CREATE TABLE `languages` (
  `id` int UNSIGNED NOT NULL,
  `code` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_rtl` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leads`
--

CREATE TABLE `leads` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_id` int UNSIGNED DEFAULT NULL,
  `number_of_employees` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annual_revenue` decimal(18,2) DEFAULT NULL,
  `address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lead_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_source_detail` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campaign_id` bigint UNSIGNED DEFAULT NULL,
  `stage_id` int UNSIGNED DEFAULT NULL,
  `status` enum('new','contacted','qualified','unqualified','converted','junk') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `rating` enum('hot','warm','cold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int DEFAULT '0',
  `is_converted` tinyint(1) DEFAULT '0',
  `converted_at` timestamp NULL DEFAULT NULL,
  `converted_contact_id` bigint UNSIGNED DEFAULT NULL,
  `converted_company_id` bigint UNSIGNED DEFAULT NULL,
  `converted_opportunity_id` bigint UNSIGNED DEFAULT NULL,
  `do_not_call` tinyint(1) DEFAULT '0',
  `do_not_email` tinyint(1) DEFAULT '0',
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `utm_source` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_term` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_content` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landing_page_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marketing_journeys`
--

CREATE TABLE `marketing_journeys` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trigger_type` enum('segment_entry','form_submission','event','date','manual','api') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trigger_config` json DEFAULT NULL,
  `entry_segment_id` int UNSIGNED DEFAULT NULL,
  `status` enum('draft','active','paused','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `goal_type` enum('conversion','engagement','revenue','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goal_config` json DEFAULT NULL,
  `enrolled_count` int UNSIGNED DEFAULT '0',
  `completed_count` int UNSIGNED DEFAULT '0',
  `converted_count` int UNSIGNED DEFAULT '0',
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','subscribed','unsubscribed','bounced','complained') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunities`
--

CREATE TABLE `opportunities` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `lead_id` bigint UNSIGNED DEFAULT NULL,
  `stage_id` int UNSIGNED DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `currency_id` int UNSIGNED DEFAULT NULL,
  `probability` tinyint UNSIGNED DEFAULT '0',
  `expected_revenue` decimal(18,2) GENERATED ALWAYS AS (((`amount` * `probability`) / 100)) STORED,
  `close_date` date DEFAULT NULL,
  `type` enum('new_business','existing_business','renewal','upsell','cross_sell') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'new_business',
  `lead_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campaign_id` bigint UNSIGNED DEFAULT NULL,
  `next_step` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `competitor` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_won` tinyint(1) DEFAULT '0',
  `is_closed` tinyint(1) DEFAULT '0',
  `won_at` timestamp NULL DEFAULT NULL,
  `lost_at` timestamp NULL DEFAULT NULL,
  `loss_reason` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_amount` decimal(18,2) DEFAULT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `score` int DEFAULT '0',
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunity_contacts`
--

CREATE TABLE `opportunity_contacts` (
  `opportunity_id` bigint UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED NOT NULL,
  `role` enum('decision_maker','influencer','evaluator','champion','budget_holder','end_user','blocker','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'other',
  `is_primary` tinyint(1) DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunity_products`
--

CREATE TABLE `opportunity_products` (
  `id` bigint UNSIGNED NOT NULL,
  `opportunity_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `unit_price` decimal(18,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT '0.00',
  `discount_amount` decimal(18,2) DEFAULT '0.00',
  `tax_rate` decimal(5,4) DEFAULT '0.0000',
  `tax_amount` decimal(18,2) DEFAULT '0.00',
  `line_total` decimal(18,2) GENERATED ALWAYS AS ((((`quantity` * `unit_price`) - `discount_amount`) + `tax_amount`)) STORED,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `cart_id` bigint UNSIGNED DEFAULT NULL,
  `currency_id` int UNSIGNED NOT NULL,
  `exchange_rate` decimal(18,6) DEFAULT '1.000000',
  `status` enum('pending','confirmed','processing','shipped','delivered','completed','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_status` enum('pending','authorized','paid','partially_paid','refunded','partially_refunded','failed','voided') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `fulfillment_status` enum('unfulfilled','partial','fulfilled','returned','partially_returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unfulfilled',
  `subtotal` decimal(15,2) NOT NULL,
  `discount_total` decimal(15,2) DEFAULT '0.00',
  `tax_total` decimal(15,2) DEFAULT '0.00',
  `shipping_total` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) DEFAULT '0.00',
  `amount_refunded` decimal(15,2) DEFAULT '0.00',
  `balance_due` decimal(15,2) GENERATED ALWAYS AS (((`grand_total` - `amount_paid`) + `amount_refunded`)) STORED,
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount` decimal(15,2) DEFAULT '0.00',
  `billing_first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_company` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country_id` int UNSIGNED DEFAULT NULL,
  `billing_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_company` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country_id` int UNSIGNED DEFAULT NULL,
  `shipping_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_carrier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('web','mobile','pos','api','phone','import') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `referred_by` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `tax_rate` decimal(5,4) DEFAULT '0.0000',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `line_total` decimal(15,2) NOT NULL,
  `quantity_fulfilled` int UNSIGNED DEFAULT '0',
  `quantity_refunded` int UNSIGNED DEFAULT '0',
  `weight` decimal(10,3) DEFAULT NULL,
  `custom_options` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notify_customer` tinyint(1) DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizations`
--

CREATE TABLE `organizations` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_language_id` int UNSIGNED DEFAULT NULL,
  `default_currency_id` int UNSIGNED DEFAULT NULL,
  `default_timezone_id` int UNSIGNED DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `organizations`
--

INSERT INTO `organizations` (`id`, `uuid`, `name`, `slug`, `logo_url`, `primary_color`, `secondary_color`, `default_language_id`, `default_currency_id`, `default_timezone_id`, `settings`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'CRM Pro', 'holi', 'assets/images/collab.png', '#10b981', '#059669', NULL, NULL, NULL, NULL, 1, '2026-01-01 22:43:57', '2026-01-11 21:43:26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency_id` int UNSIGNED NOT NULL,
  `status` enum('pending','authorized','captured','failed','cancelled','refunded','partially_refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `authorized_at` timestamp NULL DEFAULT NULL,
  `captured_at` timestamp NULL DEFAULT NULL,
  `failed_at` timestamp NULL DEFAULT NULL,
  `failure_reason` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refunded_amount` decimal(15,2) DEFAULT '0.00',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_four` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_brand` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_exp_month` tinyint UNSIGNED DEFAULT NULL,
  `card_exp_year` smallint UNSIGNED DEFAULT NULL,
  `billing_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int UNSIGNED NOT NULL,
  `module` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `action`, `resource`, `description`, `created_at`) VALUES
(1, 'users', 'view', 'all', 'Ver todos los usuarios', '2026-01-11 02:05:26'),
(2, 'users', 'create', 'all', 'Crear usuarios', '2026-01-11 02:05:26'),
(3, 'users', 'update', 'all', 'Actualizar usuarios', '2026-01-11 02:05:26'),
(4, 'users', 'delete', 'all', 'Eliminar usuarios', '2026-01-11 02:05:26'),
(5, 'roles', 'view', 'all', 'Ver roles', '2026-01-11 02:05:26'),
(6, 'roles', 'create', 'all', 'Crear roles', '2026-01-11 02:05:26'),
(7, 'roles', 'update', 'all', 'Actualizar roles', '2026-01-11 02:05:26'),
(8, 'roles', 'delete', 'all', 'Eliminar roles', '2026-01-11 02:05:26'),
(9, 'permissions', 'view', 'all', 'Ver permisos', '2026-01-11 02:05:26'),
(10, 'permissions', 'assign', 'all', 'Asignar permisos', '2026-01-11 02:05:26'),
(11, 'organizations', 'view', 'all', 'Ver organizaciones', '2026-01-11 02:05:26'),
(12, 'organizations', 'update', 'all', 'Actualizar organizaciones', '2026-01-11 02:05:26'),
(13, 'products', 'view', 'all', 'Ver productos', '2026-01-11 02:05:26'),
(14, 'products', 'create', 'all', 'Crear productos', '2026-01-11 02:05:26'),
(15, 'products', 'update', 'all', 'Actualizar productos', '2026-01-11 02:05:26'),
(16, 'products', 'delete', 'all', 'Eliminar productos', '2026-01-11 02:05:26'),
(17, 'orders', 'view', 'all', 'Ver órdenes', '2026-01-11 02:05:26'),
(18, 'orders', 'create', 'all', 'Crear órdenes', '2026-01-11 02:05:26'),
(19, 'orders', 'update', 'all', 'Actualizar órdenes', '2026-01-11 02:05:26'),
(20, 'orders', 'delete', 'all', 'Eliminar órdenes', '2026-01-11 02:05:26'),
(21, 'customers', 'view', 'all', 'Ver clientes', '2026-01-11 02:05:26'),
(22, 'customers', 'create', 'all', 'Crear clientes', '2026-01-11 02:05:26'),
(23, 'customers', 'update', 'all', 'Actualizar clientes', '2026-01-11 02:05:26'),
(24, 'customers', 'delete', 'all', 'Eliminar clientes', '2026-01-11 02:05:26'),
(25, 'reports', 'view', 'all', 'Ver reportes', '2026-01-11 02:05:26'),
(26, 'reports', 'export', 'all', 'Exportar reportes', '2026-01-11 02:05:26'),
(27, 'settings', 'view', 'all', 'Ver configuraciones', '2026-01-11 02:05:26'),
(28, 'settings', 'update', 'all', 'Actualizar configuraciones', '2026-01-11 02:05:26'),
(29, 'audit', 'view', 'all', 'Ver logs de auditoría', '2026-01-11 02:05:26'),
(30, 'system', 'manage', 'all', 'Gestión completa del sistema', '2026-01-11 02:05:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pipeline_stages`
--

CREATE TABLE `pipeline_stages` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `pipeline_type` enum('lead','opportunity') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `probability` tinyint UNSIGNED DEFAULT '0',
  `is_won` tinyint(1) DEFAULT '0',
  `is_lost` tinyint(1) DEFAULT '0',
  `sort_order` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pipeline_stage_history`
--

CREATE TABLE `pipeline_stage_history` (
  `id` bigint UNSIGNED NOT NULL,
  `entity_type` enum('lead','opportunity') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `from_stage_id` int UNSIGNED DEFAULT NULL,
  `to_stage_id` int UNSIGNED DEFAULT NULL,
  `from_probability` tinyint UNSIGNED DEFAULT NULL,
  `to_probability` tinyint UNSIGNED DEFAULT NULL,
  `duration_seconds` int UNSIGNED DEFAULT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_lists`
--

CREATE TABLE `price_lists` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int UNSIGNED NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_list_items`
--

CREATE TABLE `price_list_items` (
  `id` bigint UNSIGNED NOT NULL,
  `price_list_id` int UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `min_quantity` int UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `brand_id` int UNSIGNED DEFAULT NULL,
  `type` enum('simple','variable','bundle','virtual','downloadable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'simple',
  `status` enum('draft','active','inactive','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `visibility` enum('visible','catalog','search','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'visible',
  `base_price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `cost_price` decimal(15,2) DEFAULT NULL,
  `sale_start_at` timestamp NULL DEFAULT NULL,
  `sale_end_at` timestamp NULL DEFAULT NULL,
  `tax_rate_id` int UNSIGNED DEFAULT NULL,
  `is_taxable` tinyint(1) DEFAULT '1',
  `weight` decimal(10,3) DEFAULT NULL,
  `weight_unit` enum('kg','g','lb','oz') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `dimension_unit` enum('cm','m','in','ft') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cm',
  `manage_stock` tinyint(1) DEFAULT '1',
  `stock_quantity` int DEFAULT '0',
  `low_stock_threshold` int DEFAULT '5',
  `stock_status` enum('in_stock','out_of_stock','on_backorder') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'in_stock',
  `allow_backorders` tinyint(1) DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '0',
  `view_count` int UNSIGNED DEFAULT '0',
  `sold_count` int UNSIGNED DEFAULT '0',
  `average_rating` decimal(3,2) DEFAULT '0.00',
  `review_count` int UNSIGNED DEFAULT '0',
  `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('select','multiselect','text','number','boolean','color','size') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_filterable` tinyint(1) DEFAULT '0',
  `is_visible` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_attribute_map`
--

CREATE TABLE `product_attribute_map` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `attribute_id` int UNSIGNED NOT NULL,
  `attribute_value_id` int UNSIGNED DEFAULT NULL,
  `custom_value` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` int UNSIGNED NOT NULL,
  `attribute_id` int UNSIGNED NOT NULL,
  `value` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_hex` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_category_map`
--

CREATE TABLE `product_category_map` (
  `product_id` bigint UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_relations`
--

CREATE TABLE `product_relations` (
  `product_id` bigint UNSIGNED NOT NULL,
  `related_product_id` bigint UNSIGNED NOT NULL,
  `relation_type` enum('related','upsell','cross_sell','accessory') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pros` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_verified_purchase` tinyint(1) DEFAULT '0',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `helpful_count` int UNSIGNED DEFAULT '0',
  `reported_count` int UNSIGNED DEFAULT '0',
  `admin_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `responded_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `cost_price` decimal(15,2) DEFAULT NULL,
  `weight` decimal(10,3) DEFAULT NULL,
  `stock_quantity` int DEFAULT '0',
  `stock_status` enum('in_stock','out_of_stock','on_backorder') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'in_stock',
  `image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_variant_attributes`
--

CREATE TABLE `product_variant_attributes` (
  `variant_id` bigint UNSIGNED NOT NULL,
  `attribute_id` int UNSIGNED NOT NULL,
  `attribute_value_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotes`
--

CREATE TABLE `quotes` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quote_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int UNSIGNED DEFAULT '1',
  `parent_quote_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opportunity_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `template_id` int UNSIGNED DEFAULT NULL,
  `status` enum('draft','pending_review','pending_approval','approved','sent','viewed','accepted','declined','expired','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `currency_id` int UNSIGNED NOT NULL,
  `exchange_rate` decimal(18,6) DEFAULT '1.000000',
  `subtotal` decimal(18,2) DEFAULT '0.00',
  `discount_type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fixed',
  `discount_value` decimal(18,2) DEFAULT '0.00',
  `discount_amount` decimal(18,2) DEFAULT '0.00',
  `tax_amount` decimal(18,2) DEFAULT '0.00',
  `shipping_amount` decimal(18,2) DEFAULT '0.00',
  `adjustment` decimal(18,2) DEFAULT '0.00',
  `grand_total` decimal(18,2) DEFAULT '0.00',
  `billing_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `terms_conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes_to_customer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_terms` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_terms` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `declined_at` timestamp NULL DEFAULT NULL,
  `decline_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signature_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `converted_to_order_id` bigint UNSIGNED DEFAULT NULL,
  `approval_required` tinyint(1) DEFAULT '0',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `pdf_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_approvals`
--

CREATE TABLE `quote_approvals` (
  `id` bigint UNSIGNED NOT NULL,
  `quote_id` bigint UNSIGNED NOT NULL,
  `approver_id` bigint UNSIGNED NOT NULL,
  `approval_order` int UNSIGNED DEFAULT '1',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `decided_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_items`
--

CREATE TABLE `quote_items` (
  `id` bigint UNSIGNED NOT NULL,
  `quote_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(18,2) NOT NULL,
  `discount_type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fixed',
  `discount_value` decimal(18,2) DEFAULT '0.00',
  `discount_amount` decimal(18,2) DEFAULT '0.00',
  `tax_rate_id` int UNSIGNED DEFAULT NULL,
  `tax_rate` decimal(5,4) DEFAULT '0.0000',
  `tax_amount` decimal(18,2) DEFAULT '0.00',
  `line_total` decimal(18,2) NOT NULL,
  `sort_order` int DEFAULT '0',
  `is_optional` tinyint(1) DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_templates`
--

CREATE TABLE `quote_templates` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `header_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `footer_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `terms_conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `css_styles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','processed','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `gateway_refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `processed_by` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refund_items`
--

CREATE TABLE `refund_items` (
  `id` bigint UNSIGNED NOT NULL,
  `refund_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `restock` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) DEFAULT '0',
  `hierarchy_level` int UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `organization_id`, `name`, `slug`, `description`, `is_system`, `hierarchy_level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Administrador', 'administrador', 'Acceso completo al sistema con todos los permisos', 1, 100, '2026-01-11 02:05:26', '2026-01-11 02:05:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int UNSIGNED NOT NULL,
  `permission_id` int UNSIGNED NOT NULL,
  `conditions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `conditions`, `created_at`) VALUES
(1, 1, NULL, '2026-01-11 02:05:26'),
(1, 2, NULL, '2026-01-11 02:05:26'),
(1, 3, NULL, '2026-01-11 02:05:26'),
(1, 4, NULL, '2026-01-11 02:05:26'),
(1, 5, NULL, '2026-01-11 02:05:26'),
(1, 6, NULL, '2026-01-11 02:05:26'),
(1, 7, NULL, '2026-01-11 02:05:26'),
(1, 8, NULL, '2026-01-11 02:05:26'),
(1, 9, NULL, '2026-01-11 02:05:26'),
(1, 10, NULL, '2026-01-11 02:05:26'),
(1, 11, NULL, '2026-01-11 02:05:26'),
(1, 12, NULL, '2026-01-11 02:05:26'),
(1, 13, NULL, '2026-01-11 02:05:26'),
(1, 14, NULL, '2026-01-11 02:05:26'),
(1, 15, NULL, '2026-01-11 02:05:26'),
(1, 16, NULL, '2026-01-11 02:05:26'),
(1, 17, NULL, '2026-01-11 02:05:26'),
(1, 18, NULL, '2026-01-11 02:05:26'),
(1, 19, NULL, '2026-01-11 02:05:26'),
(1, 20, NULL, '2026-01-11 02:05:26'),
(1, 21, NULL, '2026-01-11 02:05:26'),
(1, 22, NULL, '2026-01-11 02:05:26'),
(1, 23, NULL, '2026-01-11 02:05:26'),
(1, 24, NULL, '2026-01-11 02:05:26'),
(1, 25, NULL, '2026-01-11 02:05:26'),
(1, 26, NULL, '2026-01-11 02:05:26'),
(1, 27, NULL, '2026-01-11 02:05:26'),
(1, 28, NULL, '2026-01-11 02:05:26'),
(1, 29, NULL, '2026-01-11 02:05:26'),
(1, 30, NULL, '2026-01-11 02:05:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quote_id` bigint UNSIGNED DEFAULT NULL,
  `opportunity_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('draft','confirmed','processing','partial_shipped','shipped','partial_delivered','delivered','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `payment_status` enum('pending','partial','paid','overdue') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `currency_id` int UNSIGNED NOT NULL,
  `exchange_rate` decimal(18,6) DEFAULT '1.000000',
  `subtotal` decimal(18,2) NOT NULL,
  `discount_amount` decimal(18,2) DEFAULT '0.00',
  `tax_amount` decimal(18,2) DEFAULT '0.00',
  `shipping_amount` decimal(18,2) DEFAULT '0.00',
  `grand_total` decimal(18,2) NOT NULL,
  `amount_paid` decimal(18,2) DEFAULT '0.00',
  `balance_due` decimal(18,2) GENERATED ALWAYS AS ((`grand_total` - `amount_paid`)) STORED,
  `billing_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_date` date DEFAULT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `payment_terms` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_terms` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_order_items`
--

CREATE TABLE `sales_order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `sales_order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(15,4) NOT NULL,
  `quantity_shipped` decimal(15,4) DEFAULT '0.0000',
  `quantity_delivered` decimal(15,4) DEFAULT '0.0000',
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(18,2) NOT NULL,
  `discount_amount` decimal(18,2) DEFAULT '0.00',
  `tax_amount` decimal(18,2) DEFAULT '0.00',
  `line_total` decimal(18,2) NOT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scoring_rules`
--

CREATE TABLE `scoring_rules` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `entity_type` enum('lead','contact','company','opportunity') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `conditions` json NOT NULL,
  `score_value` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `priority` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `segments`
--

CREATE TABLE `segments` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `entity_type` enum('contact','lead','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` json NOT NULL,
  `is_dynamic` tinyint(1) DEFAULT '1',
  `member_count` int UNSIGNED DEFAULT '0',
  `last_computed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `segment_members`
--

CREATE TABLE `segment_members` (
  `segment_id` int UNSIGNED NOT NULL,
  `entity_type` enum('contact','lead','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `location_id` int UNSIGNED DEFAULT NULL,
  `carrier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','label_created','picked_up','in_transit','out_for_delivery','delivered','failed','returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `weight` decimal(10,3) DEFAULT NULL,
  `dimensions` json DEFAULT NULL,
  `shipping_cost` decimal(15,2) DEFAULT NULL,
  `label_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_items`
--

CREATE TABLE `shipment_items` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sla_policies`
--

CREATE TABLE `sla_policies` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `conditions` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `priority` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sla_targets`
--

CREATE TABLE `sla_targets` (
  `id` int UNSIGNED NOT NULL,
  `sla_policy_id` int UNSIGNED NOT NULL,
  `priority` enum('low','normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_response_hours` decimal(6,2) DEFAULT NULL,
  `resolution_hours` decimal(6,2) DEFAULT NULL,
  `business_hours_only` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `store_settings`
--

CREATE TABLE `store_settings` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `store_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_slogan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_dark_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `support_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinterest_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_hours` json DEFAULT NULL,
  `timezone_id` int UNSIGNED DEFAULT NULL,
  `default_currency_id` int UNSIGNED DEFAULT NULL,
  `default_tax_rate_id` int UNSIGNED DEFAULT NULL,
  `prices_include_tax` tinyint(1) DEFAULT '0',
  `tax_calculation_method` enum('unit','line','total') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'line',
  `free_shipping_threshold` decimal(15,2) DEFAULT NULL,
  `default_shipping_origin_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_number_prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ORD-',
  `order_number_suffix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number_length` int UNSIGNED DEFAULT '6',
  `min_order_amount` decimal(15,2) DEFAULT NULL,
  `max_order_amount` decimal(15,2) DEFAULT NULL,
  `low_stock_threshold` int UNSIGNED DEFAULT '5',
  `out_of_stock_threshold` int UNSIGNED DEFAULT '0',
  `allow_backorders` tinyint(1) DEFAULT '0',
  `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_analytics_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_tag_manager_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_pixel_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `privacy_policy_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_conditions_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_policy_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_policy_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_mode` tinyint(1) DEFAULT '0',
  `maintenance_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `enable_reviews` tinyint(1) DEFAULT '1',
  `review_moderation` tinyint(1) DEFAULT '1',
  `enable_wishlist` tinyint(1) DEFAULT '1',
  `enable_compare` tinyint(1) DEFAULT '0',
  `products_per_page` int UNSIGNED DEFAULT '12',
  `top_banner_enabled` tinyint(1) DEFAULT '1',
  `top_banner_text` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_banner_link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_type` enum('contact','company','lead','opportunity','ticket','product','document','all') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` int UNSIGNED NOT NULL,
  `country_id` int UNSIGNED DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(5,4) NOT NULL,
  `is_compound` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `priority` smallint UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teams`
--

CREATE TABLE `teams` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `territory` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team_goals`
--

CREATE TABLE `team_goals` (
  `id` int UNSIGNED NOT NULL,
  `team_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metric_type` enum('revenue','deals','activities','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_value` decimal(15,2) NOT NULL,
  `current_value` decimal(15,2) DEFAULT '0.00',
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `status` enum('not_started','in_progress','achieved','missed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'not_started',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team_members`
--

CREATE TABLE `team_members` (
  `team_id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` enum('member','leader','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'member',
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `theme_settings`
--

CREATE TABLE `theme_settings` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `theme_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `is_active` tinyint(1) DEFAULT '1',
  `color_primary` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#0a0a0a',
  `color_secondary` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#f8f7f4',
  `color_accent` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#b89968',
  `color_text` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#1a1a1a',
  `color_text_light` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#757575',
  `color_border` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#e5e5e5',
  `color_white` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `color_sale` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#c23a3a',
  `color_success` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#28a745',
  `color_warning` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffc107',
  `color_error` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#dc3545',
  `color_info` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#17a2b8',
  `color_primary_hover` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_accent_hover` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_button_text` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `color_link` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_link_hover` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_bg_body` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `color_bg_header` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#fffffffa',
  `color_bg_footer` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#0a0a0a',
  `color_bg_card` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `color_bg_input` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `font_heading_family` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '''Playfair Display'', serif',
  `font_heading_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&display=swap',
  `font_heading_weight_normal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '400',
  `font_heading_weight_bold` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '700',
  `font_body_family` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '''Work Sans'', sans-serif',
  `font_body_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600&display=swap',
  `font_body_weight_light` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '300',
  `font_body_weight_normal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '400',
  `font_body_weight_medium` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '500',
  `font_body_weight_bold` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '600',
  `font_size_base` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '16px',
  `font_size_small` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0.875rem',
  `font_size_large` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.125rem',
  `font_size_h1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '2.5rem',
  `font_size_h2` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '2rem',
  `font_size_h3` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.75rem',
  `font_size_h4` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.5rem',
  `font_size_h5` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.25rem',
  `font_size_h6` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1rem',
  `spacing_unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '8px',
  `spacing_xs` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '4px',
  `spacing_sm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '8px',
  `spacing_md` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '16px',
  `spacing_lg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '24px',
  `spacing_xl` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '32px',
  `spacing_xxl` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '48px',
  `border_radius_sm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '4px',
  `border_radius_md` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '8px',
  `border_radius_lg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '12px',
  `border_radius_full` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '9999px',
  `border_width` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1px',
  `shadow_sm` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0 1px 2px rgba(0,0,0,0.05)',
  `shadow_md` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0 4px 6px rgba(0,0,0,0.1)',
  `shadow_lg` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0 10px 15px rgba(0,0,0,0.1)',
  `shadow_xl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0 20px 25px rgba(0,0,0,0.15)',
  `transition_duration` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0.5s',
  `transition_timing` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cubic-bezier(0.23, 1, 0.32, 1)',
  `header_height` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '96px',
  `header_height_mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '80px',
  `container_max_width` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1600px',
  `sidebar_width` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '280px',
  `breakpoint_xs` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '480px',
  `breakpoint_sm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '640px',
  `breakpoint_md` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '768px',
  `breakpoint_lg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1024px',
  `breakpoint_xl` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1200px',
  `breakpoint_xxl` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1400px',
  `button_padding` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '20px 48px',
  `button_font_size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0.8rem',
  `button_letter_spacing` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0.12em',
  `button_border_radius` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `input_padding` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '12px 16px',
  `input_font_size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0.9rem',
  `input_border_radius` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `card_padding` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '24px',
  `card_border_radius` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `custom_css` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `custom_js` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `custom_variables` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `theme_settings_history`
--

CREATE TABLE `theme_settings_history` (
  `id` bigint UNSIGNED NOT NULL,
  `theme_settings_id` int UNSIGNED NOT NULL,
  `snapshot` json NOT NULL,
  `change_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `requester_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requester_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_id` int UNSIGNED DEFAULT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `status_id` int UNSIGNED DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `type` enum('question','incident','problem','task','feature_request') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'question',
  `channel` enum('email','web','phone','chat','social','api') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `sla_policy_id` int UNSIGNED DEFAULT NULL,
  `sla_first_response_due` datetime DEFAULT NULL,
  `sla_resolution_due` datetime DEFAULT NULL,
  `sla_first_response_at` datetime DEFAULT NULL,
  `sla_first_response_breached` tinyint(1) DEFAULT '0',
  `sla_resolution_breached` tinyint(1) DEFAULT '0',
  `assigned_to_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_team_id` int UNSIGNED DEFAULT NULL,
  `escalated` tinyint(1) DEFAULT '0',
  `escalation_level` int UNSIGNED DEFAULT '0',
  `escalated_at` timestamp NULL DEFAULT NULL,
  `parent_ticket_id` bigint UNSIGNED DEFAULT NULL,
  `related_order_id` bigint UNSIGNED DEFAULT NULL,
  `related_product_id` bigint UNSIGNED DEFAULT NULL,
  `satisfaction_rating` tinyint UNSIGNED DEFAULT NULL,
  `satisfaction_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `satisfaction_rated_at` timestamp NULL DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `first_responded_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `reopened_count` int UNSIGNED DEFAULT '0',
  `reply_count` int UNSIGNED DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `default_queue_id` int UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `parent_comment_id` bigint UNSIGNED DEFAULT NULL,
  `author_type` enum('user','contact','customer','system') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint UNSIGNED DEFAULT NULL,
  `author_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) DEFAULT '1',
  `is_solution` tinyint(1) DEFAULT '0',
  `channel` enum('email','web','phone','chat','social','api','internal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_reply_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_history`
--

CREATE TABLE `ticket_history` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `field_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_queues`
--

CREATE TABLE `ticket_queues` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_priority` enum('low','normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `default_sla_id` int UNSIGNED DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `auto_assign` tinyint(1) DEFAULT '0',
  `assignment_method` enum('round_robin','load_balanced','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'manual',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_queue_members`
--

CREATE TABLE `ticket_queue_members` (
  `queue_id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `max_tickets` int UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_statuses`
--

CREATE TABLE `ticket_statuses` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('new','open','pending','on_hold','solved','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `timezones`
--

CREATE TABLE `timezones` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `offset_hours` decimal(4,2) NOT NULL,
  `uses_dst` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_id` int UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `language_id` int UNSIGNED DEFAULT NULL,
  `timezone_id` int UNSIGNED DEFAULT NULL,
  `date_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Y-m-d',
  `time_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'H:i',
  `mfa_enabled` tinyint(1) DEFAULT '0',
  `mfa_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mfa_recovery_codes` json DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_login_attempts` int UNSIGNED DEFAULT '0',
  `locked_until` timestamp NULL DEFAULT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `must_change_password` tinyint(1) DEFAULT '0',
  `status` enum('active','inactive','suspended','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `uuid`, `organization_id`, `email`, `email_verified_at`, `password_hash`, `first_name`, `last_name`, `display_name`, `avatar_url`, `phone`, `phone_verified_at`, `language_id`, `timezone_id`, `date_format`, `time_format`, `mfa_enabled`, `mfa_secret`, `mfa_recovery_codes`, `last_login_at`, `last_login_ip`, `failed_login_attempts`, `locked_until`, `password_changed_at`, `must_change_password`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'fedf1842-ee91-11f0-9df9-b275b7b3d1cf', 1, 'admin@syncro.com', '2026-01-11 02:05:26', '$2y$10$oWT7X9pgdgpMu3JeGcJgueWcFIvAs/.L9PlAnSW015eplWW3UDzuO', 'Administrador', 'Sistema', 'Admin', NULL, NULL, NULL, NULL, NULL, 'Y-m-d', 'H:i', 0, NULL, NULL, '2026-01-11 21:51:22', '172.19.0.1', 0, NULL, NULL, 0, 'active', '2026-01-11 02:05:26', '2026-01-11 21:51:22', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_permissions`
--

CREATE TABLE `user_permissions` (
  `user_id` bigint UNSIGNED NOT NULL,
  `permission_id` int UNSIGNED NOT NULL,
  `is_granted` tinyint(1) DEFAULT '1',
  `conditions` json DEFAULT NULL,
  `assigned_by` bigint UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `assigned_by` bigint UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_by`, `assigned_at`, `expires_at`) VALUES
(1, 1, NULL, '2026-01-11 02:05:26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_type` enum('desktop','mobile','tablet','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'other',
  `location` json DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `web_forms`
--

CREATE TABLE `web_forms` (
  `id` int UNSIGNED NOT NULL,
  `organization_id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `form_type` enum('contact','lead','newsletter','support','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'lead',
  `fields` json NOT NULL,
  `settings` json DEFAULT NULL,
  `thank_you_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `redirect_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_emails` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campaign_id` bigint UNSIGNED DEFAULT NULL,
  `segment_id` int UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `submission_count` int UNSIGNED DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_date` (`user_id`,`created_at`),
  ADD KEY `idx_ip` (`ip_address`,`created_at`),
  ADD KEY `idx_action` (`action`,`created_at`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indices de la tabla `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `recurrence_parent_id` (`recurrence_parent_id`),
  ADD KEY `completed_by` (`completed_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_related` (`related_to_type`,`related_to_id`),
  ADD KEY `idx_owner` (`owner_id`,`status`),
  ADD KEY `idx_assigned` (`assigned_to_id`,`status`),
  ADD KEY `idx_due` (`organization_id`,`status`,`due_date`),
  ADD KEY `idx_type_status` (`activity_type_id`,`status`),
  ADD KEY `idx_dates` (`start_datetime`,`end_datetime`),
  ADD KEY `idx_reminder` (`reminder_datetime`,`reminder_sent`);
ALTER TABLE `activities` ADD FULLTEXT KEY `ft_search` (`subject`,`description`);

--
-- Indices de la tabla `activity_checklists`
--
ALTER TABLE `activity_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `completed_by` (`completed_by`),
  ADD KEY `idx_activity` (`activity_id`,`sort_order`);

--
-- Indices de la tabla `activity_participants`
--
ALTER TABLE `activity_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_activity_participant` (`activity_id`,`participant_type`,`participant_id`),
  ADD KEY `idx_participant` (`participant_type`,`participant_id`);

--
-- Indices de la tabla `activity_timeline`
--
ALTER TABLE `activity_timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`,`created_at` DESC),
  ADD KEY `idx_org_date` (`organization_id`,`created_at` DESC),
  ADD KEY `idx_type` (`event_type`,`created_at` DESC);

--
-- Indices de la tabla `activity_types`
--
ALTER TABLE `activity_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `idx_category` (`organization_id`,`category`);

--
-- Indices de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_user_date` (`user_id`,`created_at`),
  ADD KEY `idx_org_date` (`organization_id`,`created_at`),
  ADD KEY `idx_action` (`action`,`created_at`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indices de la tabla `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `idx_active` (`organization_id`,`is_active`);

--
-- Indices de la tabla `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `parent_campaign_id` (`parent_campaign_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_org_status` (`organization_id`,`status`),
  ADD KEY `idx_type` (`organization_id`,`type`),
  ADD KEY `idx_dates` (`organization_id`,`start_date`,`end_date`),
  ADD KEY `idx_owner` (`owner_id`);
ALTER TABLE `campaigns` ADD FULLTEXT KEY `ft_search` (`name`,`description`);

--
-- Indices de la tabla `campaign_members`
--
ALTER TABLE `campaign_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_campaign_member` (`campaign_id`,`member_type`,`member_id`),
  ADD KEY `idx_member` (`member_type`,`member_id`),
  ADD KEY `idx_status` (`campaign_id`,`status`);

--
-- Indices de la tabla `canned_responses`
--
ALTER TABLE `canned_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_org_category` (`organization_id`,`category`),
  ADD KEY `idx_shortcut` (`organization_id`,`shortcut`);
ALTER TABLE `canned_responses` ADD FULLTEXT KEY `ft_search` (`name`,`body`);

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `billing_address_id` (`billing_address_id`),
  ADD KEY `shipping_address_id` (`shipping_address_id`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_expires` (`expires_at`),
  ADD KEY `idx_org_date` (`organization_id`,`created_at`);

--
-- Indices de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cart_product_variant` (`cart_id`,`product_id`,`variant_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indices de la tabla `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `revenue_currency_id` (`revenue_currency_id`),
  ADD KEY `billing_country_id` (`billing_country_id`),
  ADD KEY `shipping_country_id` (`shipping_country_id`),
  ADD KEY `parent_company_id` (`parent_company_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_org_type` (`organization_id`,`account_type`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_industry` (`industry_id`),
  ADD KEY `idx_lifecycle` (`organization_id`,`lifecycle_stage`),
  ADD KEY `idx_score` (`organization_id`,`score` DESC),
  ADD KEY `idx_last_activity` (`organization_id`,`last_activity_at` DESC);
ALTER TABLE `companies` ADD FULLTEXT KEY `ft_search` (`name`,`legal_name`,`description`,`website`);

--
-- Indices de la tabla `consents`
--
ALTER TABLE `consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consent_type_id` (`consent_type_id`),
  ADD KEY `idx_contact` (`contact_id`,`consent_type_id`),
  ADD KEY `idx_email` (`email`,`consent_type_id`),
  ADD KEY `idx_org_type` (`organization_id`,`consent_type_id`,`is_granted`);

--
-- Indices de la tabla `consent_types`
--
ALTER TABLE `consent_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`);

--
-- Indices de la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `reports_to_id` (`reports_to_id`),
  ADD KEY `mailing_country_id` (`mailing_country_id`),
  ADD KEY `other_country_id` (`other_country_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_org_status` (`organization_id`,`status`),
  ADD KEY `idx_company` (`company_id`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_lifecycle` (`organization_id`,`lifecycle_stage`),
  ADD KEY `idx_score` (`organization_id`,`score` DESC),
  ADD KEY `idx_last_activity` (`organization_id`,`last_activity_at` DESC);
ALTER TABLE `contacts` ADD FULLTEXT KEY `ft_search` (`first_name`,`last_name`,`email`,`job_title`,`description`);

--
-- Indices de la tabla `contact_company_relations`
--
ALTER TABLE `contact_company_relations`
  ADD PRIMARY KEY (`contact_id`,`company_id`),
  ADD KEY `idx_company` (`company_id`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_iso_code` (`iso_code`),
  ADD UNIQUE KEY `uk_iso_code_3` (`iso_code_3`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indices de la tabla `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_active_dates` (`organization_id`,`is_active`,`valid_from`,`valid_to`);

--
-- Indices de la tabla `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_coupon` (`coupon_id`),
  ADD KEY `idx_customer` (`customer_id`,`coupon_id`);

--
-- Indices de la tabla `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_code` (`code`),
  ADD KEY `idx_active_base` (`is_active`,`is_base`);

--
-- Indices de la tabla `currency_exchange_history`
--
ALTER TABLE `currency_exchange_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_currency_date` (`currency_id`,`effective_date` DESC);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_email` (`organization_id`,`email`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_group` (`customer_group_id`),
  ADD KEY `idx_status` (`organization_id`,`status`),
  ADD KEY `idx_total_spent` (`organization_id`,`total_spent` DESC);
ALTER TABLE `customers` ADD FULLTEXT KEY `ft_search` (`first_name`,`last_name`,`email`,`company_name`);

--
-- Indices de la tabla `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `idx_customer_type` (`customer_id`,`address_type`),
  ADD KEY `idx_default` (`customer_id`,`is_default`);

--
-- Indices de la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `price_list_id` (`price_list_id`);

--
-- Indices de la tabla `custom_field_definitions`
--
ALTER TABLE `custom_field_definitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_entity_field` (`organization_id`,`entity_type`,`field_name`),
  ADD KEY `idx_entity` (`organization_id`,`entity_type`);

--
-- Indices de la tabla `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_field_entity` (`field_definition_id`,`entity_type`,`entity_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_text` (`field_definition_id`,`value_text`(100)),
  ADD KEY `idx_number` (`field_definition_id`,`value_number`),
  ADD KEY `idx_date` (`field_definition_id`,`value_date`);

--
-- Indices de la tabla `data_requests`
--
ALTER TABLE `data_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `idx_org_status` (`organization_id`,`status`),
  ADD KEY `idx_due_date` (`due_date`,`status`);

--
-- Indices de la tabla `entity_tags`
--
ALTER TABLE `entity_tags`
  ADD PRIMARY KEY (`entity_type`,`entity_id`,`tag_id`),
  ADD KEY `idx_tag` (`tag_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`);

--
-- Indices de la tabla `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `idx_form` (`form_id`,`created_at`),
  ADD KEY `idx_processed` (`form_id`,`processed`);

--
-- Indices de la tabla `inbox_conversations`
--
ALTER TABLE `inbox_conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `idx_org_status` (`organization_id`,`status`),
  ADD KEY `idx_contact` (`contact_id`),
  ADD KEY `idx_channel` (`channel`),
  ADD KEY `idx_assigned` (`assigned_to_id`),
  ADD KEY `idx_last_message` (`last_message_at` DESC);

--
-- Indices de la tabla `inbox_messages`
--
ALTER TABLE `inbox_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation` (`conversation_id`,`created_at`),
  ADD KEY `idx_direction` (`direction`),
  ADD KEY `idx_external` (`external_id`);

--
-- Indices de la tabla `industries`
--
ALTER TABLE `industries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_code` (`code`),
  ADD KEY `idx_parent` (`parent_id`);

--
-- Indices de la tabla `inventory_locations`
--
ALTER TABLE `inventory_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`);

--
-- Indices de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_product_date` (`product_id`,`created_at`),
  ADD KEY `idx_location_date` (`location_id`,`created_at`),
  ADD KEY `idx_reference` (`reference_type`,`reference_id`),
  ADD KEY `idx_type_date` (`movement_type`,`created_at`);

--
-- Indices de la tabla `inventory_stock`
--
ALTER TABLE `inventory_stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_location_product_variant` (`location_id`,`product_id`,`variant_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_low_stock` (`location_id`,`available_quantity`);

--
-- Indices de la tabla `journey_enrollments`
--
ALTER TABLE `journey_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_journey_entity` (`journey_id`,`entity_type`,`entity_id`),
  ADD KEY `current_step_id` (`current_step_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_status` (`journey_id`,`status`);

--
-- Indices de la tabla `journey_steps`
--
ALTER TABLE `journey_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_step_id` (`parent_step_id`),
  ADD KEY `idx_journey` (`journey_id`,`sort_order`);

--
-- Indices de la tabla `landing_pages`
--
ALTER TABLE `landing_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `form_id` (`form_id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_status` (`organization_id`,`status`);

--
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_code` (`code`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indices de la tabla `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `industry_id` (`industry_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `converted_contact_id` (`converted_contact_id`),
  ADD KEY `converted_company_id` (`converted_company_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_org_status` (`organization_id`,`status`),
  ADD KEY `idx_stage` (`stage_id`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_score` (`organization_id`,`score` DESC),
  ADD KEY `idx_converted` (`organization_id`,`is_converted`),
  ADD KEY `idx_source` (`organization_id`,`lead_source`),
  ADD KEY `idx_last_activity` (`organization_id`,`last_activity_at` DESC);
ALTER TABLE `leads` ADD FULLTEXT KEY `ft_search` (`first_name`,`last_name`,`email`,`company`,`description`);

--
-- Indices de la tabla `marketing_journeys`
--
ALTER TABLE `marketing_journeys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entry_segment_id` (`entry_segment_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_org_status` (`organization_id`,`status`);

--
-- Indices de la tabla `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_email` (`organization_id`,`email`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_status` (`organization_id`,`status`);

--
-- Indices de la tabla `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `stage_id` (`stage_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_org_stage` (`organization_id`,`stage_id`),
  ADD KEY `idx_company` (`company_id`),
  ADD KEY `idx_contact` (`contact_id`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_close_date` (`organization_id`,`close_date`),
  ADD KEY `idx_amount` (`organization_id`,`amount` DESC),
  ADD KEY `idx_closed` (`organization_id`,`is_closed`,`is_won`),
  ADD KEY `idx_last_activity` (`organization_id`,`last_activity_at` DESC);
ALTER TABLE `opportunities` ADD FULLTEXT KEY `ft_search` (`name`,`description`);

--
-- Indices de la tabla `opportunity_contacts`
--
ALTER TABLE `opportunity_contacts`
  ADD PRIMARY KEY (`opportunity_id`,`contact_id`),
  ADD KEY `idx_contact` (`contact_id`);

--
-- Indices de la tabla `opportunity_products`
--
ALTER TABLE `opportunity_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opportunity` (`opportunity_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_number` (`organization_id`,`order_number`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `billing_country_id` (`billing_country_id`),
  ADD KEY `shipping_country_id` (`shipping_country_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_status` (`organization_id`,`status`),
  ADD KEY `idx_payment_status` (`organization_id`,`payment_status`),
  ADD KEY `idx_fulfillment` (`organization_id`,`fulfillment_status`),
  ADD KEY `idx_dates` (`organization_id`,`created_at`),
  ADD KEY `idx_source` (`organization_id`,`source`,`created_at`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indices de la tabla `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_order_date` (`order_id`,`created_at`);

--
-- Indices de la tabla `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_slug` (`slug`),
  ADD KEY `default_language_id` (`default_language_id`),
  ADD KEY `default_currency_id` (`default_currency_id`),
  ADD KEY `default_timezone_id` (`default_timezone_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_gateway_tx` (`gateway`,`gateway_transaction_id`),
  ADD KEY `idx_status` (`organization_id`,`status`),
  ADD KEY `idx_dates` (`organization_id`,`created_at`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_module_action_resource` (`module`,`action`,`resource`),
  ADD KEY `idx_module` (`module`);

--
-- Indices de la tabla `pipeline_stages`
--
ALTER TABLE `pipeline_stages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_type_code` (`organization_id`,`pipeline_type`,`code`),
  ADD KEY `idx_sort` (`organization_id`,`pipeline_type`,`sort_order`);

--
-- Indices de la tabla `pipeline_stage_history`
--
ALTER TABLE `pipeline_stage_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_stage_id` (`from_stage_id`),
  ADD KEY `to_stage_id` (`to_stage_id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_date` (`changed_at`);

--
-- Indices de la tabla `price_lists`
--
ALTER TABLE `price_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `idx_active_dates` (`organization_id`,`is_active`,`valid_from`,`valid_to`);

--
-- Indices de la tabla `price_list_items`
--
ALTER TABLE `price_list_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_list_product_variant_qty` (`price_list_id`,`product_id`,`variant_id`,`min_quantity`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_sku` (`organization_id`,`sku`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `tax_rate_id` (`tax_rate_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_status_visibility` (`organization_id`,`status`,`visibility`),
  ADD KEY `idx_brand` (`brand_id`),
  ADD KEY `idx_featured` (`organization_id`,`is_featured`,`status`),
  ADD KEY `idx_price` (`organization_id`,`status`,`base_price`),
  ADD KEY `idx_stock` (`organization_id`,`stock_status`,`manage_stock`),
  ADD KEY `idx_dates` (`created_at`,`updated_at`);
ALTER TABLE `products` ADD FULLTEXT KEY `ft_search` (`name`,`short_description`,`description`,`sku`);

--
-- Indices de la tabla `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `idx_filterable` (`organization_id`,`is_filterable`);

--
-- Indices de la tabla `product_attribute_map`
--
ALTER TABLE `product_attribute_map`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_product_attr_val` (`product_id`,`attribute_id`,`attribute_value_id`),
  ADD KEY `attribute_value_id` (`attribute_value_id`),
  ADD KEY `idx_attribute` (`attribute_id`,`attribute_value_id`);

--
-- Indices de la tabla `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_attr_slug` (`attribute_id`,`slug`),
  ADD KEY `idx_attribute` (`attribute_id`,`sort_order`);

--
-- Indices de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `idx_parent` (`parent_id`),
  ADD KEY `idx_active_featured` (`organization_id`,`is_active`,`is_featured`);
ALTER TABLE `product_categories` ADD FULLTEXT KEY `ft_name_desc` (`name`,`description`);

--
-- Indices de la tabla `product_category_map`
--
ALTER TABLE `product_category_map`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_primary` (`product_id`,`is_primary`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_primary` (`product_id`,`is_primary`),
  ADD KEY `idx_variant` (`variant_id`);

--
-- Indices de la tabla `product_relations`
--
ALTER TABLE `product_relations`
  ADD PRIMARY KEY (`product_id`,`related_product_id`,`relation_type`),
  ADD KEY `idx_related` (`related_product_id`);

--
-- Indices de la tabla `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `responded_by` (`responded_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_product_status` (`product_id`,`status`),
  ADD KEY `idx_rating` (`product_id`,`rating`),
  ADD KEY `idx_customer` (`customer_id`);

--
-- Indices de la tabla `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_sku` (`sku`),
  ADD KEY `idx_product_active` (`product_id`,`is_active`),
  ADD KEY `idx_stock` (`stock_status`);

--
-- Indices de la tabla `product_variant_attributes`
--
ALTER TABLE `product_variant_attributes`
  ADD PRIMARY KEY (`variant_id`,`attribute_id`),
  ADD KEY `attribute_value_id` (`attribute_value_id`),
  ADD KEY `idx_attr_val` (`attribute_id`,`attribute_value_id`);

--
-- Indices de la tabla `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_number_version` (`organization_id`,`quote_number`,`version`),
  ADD KEY `parent_quote_id` (`parent_quote_id`),
  ADD KEY `template_id` (`template_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_opportunity` (`opportunity_id`),
  ADD KEY `idx_company` (`company_id`),
  ADD KEY `idx_contact` (`contact_id`),
  ADD KEY `idx_status` (`organization_id`,`status`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_valid` (`organization_id`,`valid_until`,`status`),
  ADD KEY `idx_created` (`organization_id`,`created_at`);

--
-- Indices de la tabla `quote_approvals`
--
ALTER TABLE `quote_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quote_status` (`quote_id`,`status`),
  ADD KEY `idx_approver` (`approver_id`,`status`);

--
-- Indices de la tabla `quote_items`
--
ALTER TABLE `quote_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`),
  ADD KEY `idx_quote` (`quote_id`,`sort_order`);

--
-- Indices de la tabla `quote_templates`
--
ALTER TABLE `quote_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_org_active` (`organization_id`,`is_active`);

--
-- Indices de la tabla `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `processed_by` (`processed_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_status` (`organization_id`,`status`);

--
-- Indices de la tabla `refund_items`
--
ALTER TABLE `refund_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `idx_refund` (`refund_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `idx_hierarchy` (`organization_id`,`hierarchy_level`);

--
-- Indices de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indices de la tabla `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_number` (`organization_id`,`order_number`),
  ADD KEY `quote_id` (`quote_id`),
  ADD KEY `opportunity_id` (`opportunity_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_company` (`company_id`),
  ADD KEY `idx_contact` (`contact_id`),
  ADD KEY `idx_status` (`organization_id`,`status`),
  ADD KEY `idx_payment_status` (`organization_id`,`payment_status`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_dates` (`organization_id`,`order_date`);

--
-- Indices de la tabla `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_order` (`sales_order_id`);

--
-- Indices de la tabla `scoring_rules`
--
ALTER TABLE `scoring_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_org_type` (`organization_id`,`entity_type`,`is_active`);

--
-- Indices de la tabla `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_org_type` (`organization_id`,`entity_type`,`is_active`);

--
-- Indices de la tabla `segment_members`
--
ALTER TABLE `segment_members`
  ADD PRIMARY KEY (`segment_id`,`entity_type`,`entity_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`);

--
-- Indices de la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_tracking` (`carrier`,`tracking_number`),
  ADD KEY `idx_status` (`organization_id`,`status`);

--
-- Indices de la tabla `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `idx_shipment` (`shipment_id`);

--
-- Indices de la tabla `sla_policies`
--
ALTER TABLE `sla_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_org_active` (`organization_id`,`is_active`);

--
-- Indices de la tabla `sla_targets`
--
ALTER TABLE `sla_targets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_sla_priority` (`sla_policy_id`,`priority`);

--
-- Indices de la tabla `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_organization` (`organization_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `timezone_id` (`timezone_id`),
  ADD KEY `default_currency_id` (`default_currency_id`),
  ADD KEY `default_tax_rate_id` (`default_tax_rate_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_slug_type` (`organization_id`,`slug`,`entity_type`),
  ADD KEY `idx_type` (`organization_id`,`entity_type`);

--
-- Indices de la tabla `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_code` (`code`),
  ADD KEY `idx_country_active` (`country_id`,`is_active`);

--
-- Indices de la tabla `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_slug` (`organization_id`,`slug`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `idx_parent` (`parent_id`),
  ADD KEY `idx_active` (`organization_id`,`is_active`);

--
-- Indices de la tabla `team_goals`
--
ALTER TABLE `team_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_team_period` (`team_id`,`period_start`,`period_end`),
  ADD KEY `idx_status` (`status`);

--
-- Indices de la tabla `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`team_id`,`user_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indices de la tabla `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_theme` (`organization_id`,`theme_name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_active` (`organization_id`,`is_active`);

--
-- Indices de la tabla `theme_settings_history`
--
ALTER TABLE `theme_settings_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_theme_date` (`theme_settings_id`,`changed_at` DESC);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_org_number` (`organization_id`,`ticket_number`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `sla_policy_id` (`sla_policy_id`),
  ADD KEY `assigned_team_id` (`assigned_team_id`),
  ADD KEY `parent_ticket_id` (`parent_ticket_id`),
  ADD KEY `related_order_id` (`related_order_id`),
  ADD KEY `related_product_id` (`related_product_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_contact` (`contact_id`),
  ADD KEY `idx_company` (`company_id`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_queue_status` (`queue_id`,`status_id`),
  ADD KEY `idx_assigned` (`assigned_to_id`,`status_id`),
  ADD KEY `idx_priority` (`organization_id`,`priority`,`status_id`),
  ADD KEY `idx_sla_response` (`sla_first_response_due`,`sla_first_response_breached`),
  ADD KEY `idx_sla_resolution` (`sla_resolution_due`,`sla_resolution_breached`),
  ADD KEY `idx_created` (`organization_id`,`created_at`);
ALTER TABLE `tickets` ADD FULLTEXT KEY `ft_search` (`subject`,`description`);

--
-- Indices de la tabla `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `default_queue_id` (`default_queue_id`),
  ADD KEY `idx_parent` (`parent_id`);

--
-- Indices de la tabla `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`),
  ADD KEY `idx_ticket` (`ticket_id`,`created_at`),
  ADD KEY `idx_public` (`ticket_id`,`is_public`),
  ADD KEY `idx_message_id` (`message_id`);
ALTER TABLE `ticket_comments` ADD FULLTEXT KEY `ft_search` (`body`);

--
-- Indices de la tabla `ticket_history`
--
ALTER TABLE `ticket_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_ticket_date` (`ticket_id`,`changed_at`);

--
-- Indices de la tabla `ticket_queues`
--
ALTER TABLE `ticket_queues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `idx_active` (`organization_id`,`is_active`);

--
-- Indices de la tabla `ticket_queue_members`
--
ALTER TABLE `ticket_queue_members`
  ADD PRIMARY KEY (`queue_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_org_code` (`organization_id`,`code`),
  ADD KEY `idx_category` (`organization_id`,`category`);

--
-- Indices de la tabla `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_name` (`name`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_email` (`email`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `timezone_id` (`timezone_id`),
  ADD KEY `idx_org_status` (`organization_id`,`status`),
  ADD KEY `idx_email_status` (`email`,`status`);
ALTER TABLE `users` ADD FULLTEXT KEY `ft_name` (`first_name`,`last_name`,`display_name`);

--
-- Indices de la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `idx_role` (`role_id`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indices de la tabla `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_token` (`token_hash`),
  ADD KEY `idx_user_expires` (`user_id`,`expires_at`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indices de la tabla `web_forms`
--
ALTER TABLE `web_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_uuid` (`uuid`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `segment_id` (`segment_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_org_type` (`organization_id`,`form_type`,`is_active`);

--
-- Indices de la tabla `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_customer_product_variant` (`customer_id`,`product_id`,`variant_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `activity_checklists`
--
ALTER TABLE `activity_checklists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `activity_participants`
--
ALTER TABLE `activity_participants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `activity_timeline`
--
ALTER TABLE `activity_timeline`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `activity_types`
--
ALTER TABLE `activity_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campaign_members`
--
ALTER TABLE `campaign_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `canned_responses`
--
ALTER TABLE `canned_responses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `consents`
--
ALTER TABLE `consents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consent_types`
--
ALTER TABLE `consent_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coupon_usage`
--
ALTER TABLE `coupon_usage`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `currency_exchange_history`
--
ALTER TABLE `currency_exchange_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `custom_field_definitions`
--
ALTER TABLE `custom_field_definitions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `data_requests`
--
ALTER TABLE `data_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `form_submissions`
--
ALTER TABLE `form_submissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inbox_conversations`
--
ALTER TABLE `inbox_conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `inbox_messages`
--
ALTER TABLE `inbox_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `industries`
--
ALTER TABLE `industries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_locations`
--
ALTER TABLE `inventory_locations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_stock`
--
ALTER TABLE `inventory_stock`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `journey_enrollments`
--
ALTER TABLE `journey_enrollments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `journey_steps`
--
ALTER TABLE `journey_steps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `landing_pages`
--
ALTER TABLE `landing_pages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marketing_journeys`
--
ALTER TABLE `marketing_journeys`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opportunity_products`
--
ALTER TABLE `opportunity_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `pipeline_stages`
--
ALTER TABLE `pipeline_stages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pipeline_stage_history`
--
ALTER TABLE `pipeline_stage_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `price_lists`
--
ALTER TABLE `price_lists`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `price_list_items`
--
ALTER TABLE `price_list_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_attribute_map`
--
ALTER TABLE `product_attribute_map`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote_approvals`
--
ALTER TABLE `quote_approvals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote_items`
--
ALTER TABLE `quote_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote_templates`
--
ALTER TABLE `quote_templates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `refund_items`
--
ALTER TABLE `refund_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sales_order_items`
--
ALTER TABLE `sales_order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `scoring_rules`
--
ALTER TABLE `scoring_rules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shipment_items`
--
ALTER TABLE `shipment_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sla_policies`
--
ALTER TABLE `sla_policies`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sla_targets`
--
ALTER TABLE `sla_targets`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `team_goals`
--
ALTER TABLE `team_goals`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `theme_settings`
--
ALTER TABLE `theme_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `theme_settings_history`
--
ALTER TABLE `theme_settings_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_history`
--
ALTER TABLE `ticket_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_queues`
--
ALTER TABLE `ticket_queues`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `web_forms`
--
ALTER TABLE `web_forms`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`activity_type_id`) REFERENCES `activity_types` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `activities_ibfk_3` FOREIGN KEY (`recurrence_parent_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_ibfk_4` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_ibfk_5` FOREIGN KEY (`assigned_to_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_ibfk_6` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_ibfk_7` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_ibfk_8` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `activity_checklists`
--
ALTER TABLE `activity_checklists`
  ADD CONSTRAINT `activity_checklists_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_checklists_ibfk_2` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `activity_participants`
--
ALTER TABLE `activity_participants`
  ADD CONSTRAINT `activity_participants_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `activity_timeline`
--
ALTER TABLE `activity_timeline`
  ADD CONSTRAINT `activity_timeline_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_timeline_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `activity_types`
--
ALTER TABLE `activity_types`
  ADD CONSTRAINT `activity_types_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaigns_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_ibfk_3` FOREIGN KEY (`parent_campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_ibfk_4` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_ibfk_6` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `campaign_members`
--
ALTER TABLE `campaign_members`
  ADD CONSTRAINT `campaign_members_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `canned_responses`
--
ALTER TABLE `canned_responses`
  ADD CONSTRAINT `canned_responses_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `canned_responses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `carts_ibfk_4` FOREIGN KEY (`billing_address_id`) REFERENCES `customer_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_ibfk_5` FOREIGN KEY (`shipping_address_id`) REFERENCES `customer_addresses` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `companies_ibfk_2` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_3` FOREIGN KEY (`revenue_currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_4` FOREIGN KEY (`billing_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_5` FOREIGN KEY (`shipping_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_6` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_7` FOREIGN KEY (`parent_company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_8` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `companies_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `consents`
--
ALTER TABLE `consents`
  ADD CONSTRAINT `consents_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consents_ibfk_2` FOREIGN KEY (`consent_type_id`) REFERENCES `consent_types` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `consent_types`
--
ALTER TABLE `consent_types`
  ADD CONSTRAINT `consent_types_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contacts_ibfk_10` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_3` FOREIGN KEY (`reports_to_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_4` FOREIGN KEY (`mailing_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_5` FOREIGN KEY (`other_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_6` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_7` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_8` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_ibfk_9` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `contact_company_relations`
--
ALTER TABLE `contact_company_relations`
  ADD CONSTRAINT `contact_company_relations_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contact_company_relations_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupons_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `currency_exchange_history`
--
ALTER TABLE `currency_exchange_history`
  ADD CONSTRAINT `currency_exchange_history_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customers_ibfk_3` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_addresses_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD CONSTRAINT `customer_groups_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_groups_ibfk_2` FOREIGN KEY (`price_list_id`) REFERENCES `price_lists` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `custom_field_definitions`
--
ALTER TABLE `custom_field_definitions`
  ADD CONSTRAINT `custom_field_definitions_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD CONSTRAINT `custom_field_values_ibfk_1` FOREIGN KEY (`field_definition_id`) REFERENCES `custom_field_definitions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `data_requests`
--
ALTER TABLE `data_requests`
  ADD CONSTRAINT `data_requests_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_requests_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `entity_tags`
--
ALTER TABLE `entity_tags`
  ADD CONSTRAINT `entity_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD CONSTRAINT `form_submissions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `web_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `form_submissions_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `form_submissions_ibfk_3` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inbox_conversations`
--
ALTER TABLE `inbox_conversations`
  ADD CONSTRAINT `fk_inbox_conv_assigned` FOREIGN KEY (`assigned_to_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_inbox_conv_contact` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_inbox_conv_org` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inbox_messages`
--
ALTER TABLE `inbox_messages`
  ADD CONSTRAINT `fk_inbox_msg_conv` FOREIGN KEY (`conversation_id`) REFERENCES `inbox_conversations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `industries`
--
ALTER TABLE `industries`
  ADD CONSTRAINT `industries_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventory_locations`
--
ALTER TABLE `inventory_locations`
  ADD CONSTRAINT `inventory_locations_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_4` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventory_stock`
--
ALTER TABLE `inventory_stock`
  ADD CONSTRAINT `inventory_stock_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_stock_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_stock_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `journey_enrollments`
--
ALTER TABLE `journey_enrollments`
  ADD CONSTRAINT `journey_enrollments_ibfk_1` FOREIGN KEY (`journey_id`) REFERENCES `marketing_journeys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journey_enrollments_ibfk_2` FOREIGN KEY (`current_step_id`) REFERENCES `journey_steps` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `journey_steps`
--
ALTER TABLE `journey_steps`
  ADD CONSTRAINT `journey_steps_ibfk_1` FOREIGN KEY (`journey_id`) REFERENCES `marketing_journeys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journey_steps_ibfk_2` FOREIGN KEY (`parent_step_id`) REFERENCES `journey_steps` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `landing_pages`
--
ALTER TABLE `landing_pages`
  ADD CONSTRAINT `landing_pages_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `landing_pages_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `web_forms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `landing_pages_ibfk_3` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `landing_pages_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_ibfk_2` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_3` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_4` FOREIGN KEY (`stage_id`) REFERENCES `pipeline_stages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_5` FOREIGN KEY (`converted_contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_6` FOREIGN KEY (`converted_company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_7` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_8` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `marketing_journeys`
--
ALTER TABLE `marketing_journeys`
  ADD CONSTRAINT `marketing_journeys_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marketing_journeys_ibfk_2` FOREIGN KEY (`entry_segment_id`) REFERENCES `segments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `marketing_journeys_ibfk_3` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `marketing_journeys_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD CONSTRAINT `newsletter_subscribers_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `newsletter_subscribers_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `opportunities`
--
ALTER TABLE `opportunities`
  ADD CONSTRAINT `opportunities_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `opportunities_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_3` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_4` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_5` FOREIGN KEY (`stage_id`) REFERENCES `pipeline_stages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_6` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_7` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_8` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `opportunity_contacts`
--
ALTER TABLE `opportunity_contacts`
  ADD CONSTRAINT `opportunity_contacts_ibfk_1` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `opportunity_contacts_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `opportunity_products`
--
ALTER TABLE `opportunity_products`
  ADD CONSTRAINT `opportunity_products_ibfk_1` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `opportunity_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`billing_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_6` FOREIGN KEY (`shipping_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_7` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_8` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_status_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`default_language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `organizations_ibfk_2` FOREIGN KEY (`default_currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `organizations_ibfk_3` FOREIGN KEY (`default_timezone_id`) REFERENCES `timezones` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `pipeline_stages`
--
ALTER TABLE `pipeline_stages`
  ADD CONSTRAINT `pipeline_stages_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pipeline_stage_history`
--
ALTER TABLE `pipeline_stage_history`
  ADD CONSTRAINT `pipeline_stage_history_ibfk_1` FOREIGN KEY (`from_stage_id`) REFERENCES `pipeline_stages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pipeline_stage_history_ibfk_2` FOREIGN KEY (`to_stage_id`) REFERENCES `pipeline_stages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pipeline_stage_history_ibfk_3` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `price_lists`
--
ALTER TABLE `price_lists`
  ADD CONSTRAINT `price_lists_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_lists_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `price_list_items`
--
ALTER TABLE `price_list_items`
  ADD CONSTRAINT `price_list_items_ibfk_1` FOREIGN KEY (`price_list_id`) REFERENCES `price_lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_list_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_list_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_attribute_map`
--
ALTER TABLE `product_attribute_map`
  ADD CONSTRAINT `product_attribute_map_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_map_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_map_ibfk_3` FOREIGN KEY (`attribute_value_id`) REFERENCES `product_attribute_values` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_category_map`
--
ALTER TABLE `product_category_map`
  ADD CONSTRAINT `product_category_map_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_map_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_images_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_relations`
--
ALTER TABLE `product_relations`
  ADD CONSTRAINT `product_relations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_relations_ibfk_2` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reviews_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reviews_ibfk_5` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reviews_ibfk_6` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_variant_attributes`
--
ALTER TABLE `product_variant_attributes`
  ADD CONSTRAINT `product_variant_attributes_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attributes_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attributes_ibfk_3` FOREIGN KEY (`attribute_value_id`) REFERENCES `product_attribute_values` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotes_ibfk_10` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_11` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_2` FOREIGN KEY (`parent_quote_id`) REFERENCES `quotes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_3` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_5` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_6` FOREIGN KEY (`template_id`) REFERENCES `quote_templates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_7` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `quotes_ibfk_8` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_ibfk_9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `quote_approvals`
--
ALTER TABLE `quote_approvals`
  ADD CONSTRAINT `quote_approvals_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_approvals_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `quote_items`
--
ALTER TABLE `quote_items`
  ADD CONSTRAINT `quote_items_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quote_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quote_items_ibfk_4` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `quote_templates`
--
ALTER TABLE `quote_templates`
  ADD CONSTRAINT `quote_templates_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `refunds_ibfk_4` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `refunds_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `refund_items`
--
ALTER TABLE `refund_items`
  ADD CONSTRAINT `refund_items_ibfk_1` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refund_items_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `sales_orders_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_orders_ibfk_2` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_3` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_5` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_6` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `sales_orders_ibfk_7` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_8` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD CONSTRAINT `sales_order_items_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `scoring_rules`
--
ALTER TABLE `scoring_rules`
  ADD CONSTRAINT `scoring_rules_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `segments`
--
ALTER TABLE `segments`
  ADD CONSTRAINT `segments_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `segments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `segment_members`
--
ALTER TABLE `segment_members`
  ADD CONSTRAINT `segment_members_ibfk_1` FOREIGN KEY (`segment_id`) REFERENCES `segments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shipments_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD CONSTRAINT `shipment_items_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipment_items_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sla_policies`
--
ALTER TABLE `sla_policies`
  ADD CONSTRAINT `sla_policies_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sla_targets`
--
ALTER TABLE `sla_targets`
  ADD CONSTRAINT `sla_targets_ibfk_1` FOREIGN KEY (`sla_policy_id`) REFERENCES `sla_policies` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `store_settings`
--
ALTER TABLE `store_settings`
  ADD CONSTRAINT `store_settings_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_settings_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `store_settings_ibfk_3` FOREIGN KEY (`timezone_id`) REFERENCES `timezones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `store_settings_ibfk_4` FOREIGN KEY (`default_currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `store_settings_ibfk_5` FOREIGN KEY (`default_tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `store_settings_ibfk_6` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `store_settings_ibfk_7` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD CONSTRAINT `tax_rates_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teams_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `team_goals`
--
ALTER TABLE `team_goals`
  ADD CONSTRAINT `team_goals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD CONSTRAINT `theme_settings_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `theme_settings_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `theme_settings_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `theme_settings_history`
--
ALTER TABLE `theme_settings_history`
  ADD CONSTRAINT `theme_settings_history_ibfk_1` FOREIGN KEY (`theme_settings_id`) REFERENCES `theme_settings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `theme_settings_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_10` FOREIGN KEY (`assigned_team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_11` FOREIGN KEY (`parent_ticket_id`) REFERENCES `tickets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_12` FOREIGN KEY (`related_order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_13` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_14` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_15` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_5` FOREIGN KEY (`queue_id`) REFERENCES `ticket_queues` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_6` FOREIGN KEY (`category_id`) REFERENCES `ticket_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_7` FOREIGN KEY (`status_id`) REFERENCES `ticket_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_8` FOREIGN KEY (`sla_policy_id`) REFERENCES `sla_policies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_9` FOREIGN KEY (`assigned_to_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD CONSTRAINT `ticket_categories_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_categories_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `ticket_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ticket_categories_ibfk_3` FOREIGN KEY (`default_queue_id`) REFERENCES `ticket_queues` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD CONSTRAINT `ticket_comments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_comments_ibfk_2` FOREIGN KEY (`parent_comment_id`) REFERENCES `ticket_comments` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ticket_history`
--
ALTER TABLE `ticket_history`
  ADD CONSTRAINT `ticket_history_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ticket_queues`
--
ALTER TABLE `ticket_queues`
  ADD CONSTRAINT `ticket_queues_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_queues_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ticket_queue_members`
--
ALTER TABLE `ticket_queue_members`
  ADD CONSTRAINT `ticket_queue_members_ibfk_1` FOREIGN KEY (`queue_id`) REFERENCES `ticket_queues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_queue_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD CONSTRAINT `ticket_statuses_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`timezone_id`) REFERENCES `timezones` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `web_forms`
--
ALTER TABLE `web_forms`
  ADD CONSTRAINT `web_forms_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `web_forms_ibfk_2` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `web_forms_ibfk_3` FOREIGN KEY (`segment_id`) REFERENCES `segments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `web_forms_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
