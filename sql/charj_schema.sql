-- ============================================================
-- Charj.in - India's EV Marketplace
-- Complete MySQL Schema with Seed Data
-- All prices stored in INR as DECIMAL(12,2)
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+05:30';
SET sql_mode = 'NO_ENGINE_SUBSTITUTION';

-- ============================================================
-- DROP EXISTING TABLES (dependency order)
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS faq;
DROP TABLE IF EXISTS city_pricing;
DROP TABLE IF EXISTS recommendation_sessions;
DROP TABLE IF EXISTS calculator_logs;
DROP TABLE IF EXISTS charging_stations;
DROP TABLE IF EXISTS article_categories;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS lead_notes;
DROP TABLE IF EXISTS leads;
DROP TABLE IF EXISTS dealer_vehicles;
DROP TABLE IF EXISTS dealers;
DROP TABLE IF EXISTS vehicle_images;
DROP TABLE IF EXISTS vehicle_variants;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS vehicle_categories;
DROP TABLE IF EXISTS brands;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- TABLE: users
-- ============================================================
CREATE TABLE `users` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(100) NOT NULL,
    `email`        VARCHAR(150) NOT NULL,
    `password`     VARCHAR(255) NOT NULL,
    `role`         ENUM('admin','editor','dealer') NOT NULL DEFAULT 'editor',
    `status`       ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
    `last_login`   DATETIME NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: brands
-- ============================================================
CREATE TABLE `brands` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`            VARCHAR(100) NOT NULL,
    `slug`            VARCHAR(120) NOT NULL,
    `logo`            VARCHAR(255) NULL,
    `country_of_origin` VARCHAR(60) NOT NULL DEFAULT 'India',
    `description`     TEXT NULL,
    `website`         VARCHAR(255) NULL,
    `status`          ENUM('published','draft') NOT NULL DEFAULT 'draft',
    `featured`        TINYINT(1) NOT NULL DEFAULT 0,
    `seo_title`       VARCHAR(160) NULL,
    `seo_description` VARCHAR(320) NULL,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_brands_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: vehicle_categories
-- ============================================================
CREATE TABLE `vehicle_categories` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`            VARCHAR(100) NOT NULL,
    `slug`            VARCHAR(120) NOT NULL,
    `parent_id`       INT UNSIGNED NULL DEFAULT NULL,
    `icon`            VARCHAR(255) NULL,
    `description`     TEXT NULL,
    `display_order`   SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `status`          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `seo_title`       VARCHAR(160) NULL,
    `seo_description` VARCHAR(320) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_vehicle_categories_slug` (`slug`),
    KEY `idx_vc_parent` (`parent_id`),
    CONSTRAINT `fk_vc_parent` FOREIGN KEY (`parent_id`) REFERENCES `vehicle_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: vehicles
-- ============================================================
CREATE TABLE `vehicles` (
    `id`                        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `brand_id`                  INT UNSIGNED NOT NULL,
    `category_id`               INT UNSIGNED NOT NULL,
    `name`                      VARCHAR(150) NOT NULL,
    `slug`                      VARCHAR(180) NOT NULL,
    `short_description`         VARCHAR(500) NULL,
    `full_description`          LONGTEXT NULL,
    -- Pricing in INR (e.g. 1,39,000 stored as 139000.00)
    `starting_price`            DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'base ex-showroom price INR',
    `max_price`                 DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'top variant ex-showroom INR',
    `ex_showroom_price`         DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'base variant ex-showroom INR',
    `on_road_price_delhi`       DECIMAL(12,2) NULL COMMENT 'on-road price Delhi INR',
    `on_road_price_mumbai`      DECIMAL(12,2) NULL COMMENT 'on-road price Mumbai INR',
    `on_road_price_bangalore`   DECIMAL(12,2) NULL COMMENT 'on-road price Bangalore INR',
    -- Range & Battery
    `claimed_range`             SMALLINT UNSIGNED NULL COMMENT 'km as per ARAI/MIDC certification',
    `real_world_range`          SMALLINT UNSIGNED NULL COMMENT 'estimated real-world km',
    `battery_capacity`          DECIMAL(6,2) NULL COMMENT 'battery capacity in kWh',
    `battery_type`              VARCHAR(60) NULL COMMENT 'e.g. NMC, LFP, NCA',
    `charging_time_ac`          VARCHAR(60) NULL COMMENT 'AC charging time e.g. 5h 30min',
    `charging_time_dc`          VARCHAR(60) NULL COMMENT 'DC fast charging time e.g. 60min to 80%',
    `fast_charging_supported`   TINYINT(1) NOT NULL DEFAULT 0,
    `fast_charging_time`        VARCHAR(60) NULL,
    `charging_connector_type`   ENUM('CCS2','CHAdeMO','Type2','Bharat_AC','Bharat_DC','Proprietary') NULL,
    -- Performance
    `motor_power_kw`            DECIMAL(6,2) NULL COMMENT 'continuous motor power in kW',
    `peak_power_kw`             DECIMAL(6,2) NULL COMMENT 'peak power in kW',
    `torque_nm`                 DECIMAL(6,1) NULL COMMENT 'torque in Nm',
    `top_speed_kmph`            SMALLINT UNSIGNED NULL COMMENT 'top speed in kmph',
    `acceleration_0_60`         DECIMAL(4,1) NULL COMMENT 'seconds to reach 60 kmph from 0',
    -- Dimensions & Capacity
    `seating_capacity`          TINYINT UNSIGNED NULL,
    `load_capacity_kg`          SMALLINT UNSIGNED NULL COMMENT 'payload in kg',
    `boot_space_litres`         SMALLINT UNSIGNED NULL COMMENT 'boot/storage in litres',
    `ground_clearance_mm`       SMALLINT UNSIGNED NULL COMMENT 'ground clearance in mm',
    `wheelbase_mm`              SMALLINT UNSIGNED NULL COMMENT 'wheelbase in mm',
    `weight_kg`                 SMALLINT UNSIGNED NULL COMMENT 'kerb weight in kg',
    -- Protection
    `ip_rating`                 VARCHAR(10) NULL COMMENT 'ingress protection e.g. IP67',
    `water_resistance`          VARCHAR(100) NULL,
    -- Finance
    `emi_starting`              DECIMAL(10,2) NULL COMMENT 'lowest monthly EMI in INR',
    -- Warranty
    `warranty_years`            TINYINT UNSIGNED NULL,
    `warranty_km`               INT UNSIGNED NULL,
    `battery_warranty_years`    TINYINT UNSIGNED NULL,
    `battery_warranty_km`       INT UNSIGNED NULL,
    -- Subsidies
    `fame2_subsidy`             DECIMAL(10,2) NULL COMMENT 'FAME-II subsidy amount INR',
    `state_subsidy_note`        VARCHAR(500) NULL,
    -- Ratings
    `expert_rating`             DECIMAL(3,1) NULL COMMENT 'out of 10',
    `user_rating`               DECIMAL(3,1) NULL COMMENT 'out of 5',
    `review_count`              INT UNSIGNED NOT NULL DEFAULT 0,
    -- Metadata
    `best_for`                  ENUM('daily_commute','long_distance','city_only','cargo','fleet','family') NULL,
    `status`                    ENUM('published','draft','discontinued') NOT NULL DEFAULT 'draft',
    `featured`                  TINYINT(1) NOT NULL DEFAULT 0,
    `launch_year`               YEAR NULL,
    `created_at`                DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_vehicles_slug` (`slug`),
    KEY `idx_v_brand` (`brand_id`),
    KEY `idx_v_category` (`category_id`),
    KEY `idx_v_status` (`status`),
    KEY `idx_v_featured` (`featured`),
    KEY `idx_v_price` (`starting_price`),
    CONSTRAINT `fk_v_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_v_category` FOREIGN KEY (`category_id`) REFERENCES `vehicle_categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: vehicle_variants
-- ============================================================
CREATE TABLE `vehicle_variants` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `vehicle_id`       INT UNSIGNED NOT NULL,
    `name`             VARCHAR(150) NOT NULL,
    `price`            DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'ex-showroom price INR',
    `battery_capacity` DECIMAL(6,2) NULL COMMENT 'kWh',
    `claimed_range`    SMALLINT UNSIGNED NULL COMMENT 'km',
    `color_options`    TEXT NULL COMMENT 'comma-separated color names',
    `status`           ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_vv_vehicle` (`vehicle_id`),
    CONSTRAINT `fk_vv_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: vehicle_images
-- ============================================================
CREATE TABLE `vehicle_images` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `vehicle_id`    INT UNSIGNED NOT NULL,
    `image_url`     VARCHAR(500) NOT NULL,
    `alt_text`      VARCHAR(255) NULL,
    `image_type`    ENUM('main','gallery','color','interior') NOT NULL DEFAULT 'gallery',
    `display_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_vi_vehicle` (`vehicle_id`),
    CONSTRAINT `fk_vi_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: dealers
-- ============================================================
CREATE TABLE `dealers` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `brand_id`        INT UNSIGNED NULL COMMENT 'NULL for multi-brand dealers',
    `name`            VARCHAR(200) NOT NULL,
    `slug`            VARCHAR(220) NOT NULL,
    `contact_person`  VARCHAR(100) NULL,
    `email`           VARCHAR(150) NULL,
    `phone`           VARCHAR(20) NULL,
    `address`         VARCHAR(500) NULL,
    `city`            VARCHAR(100) NOT NULL,
    `state`           VARCHAR(100) NOT NULL,
    `pincode`         CHAR(6) NULL,
    `latitude`        DECIMAL(10,7) NULL,
    `longitude`       DECIMAL(10,7) NULL,
    `google_maps_url` VARCHAR(500) NULL,
    `website`         VARCHAR(255) NULL,
    `brands_handled`  TEXT NULL COMMENT 'JSON array of brand names e.g. ["Ather","Ola","TVS"]',
    `status`          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `verified`        TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_dealers_slug` (`slug`),
    KEY `idx_d_brand` (`brand_id`),
    KEY `idx_d_city` (`city`),
    KEY `idx_d_state` (`state`),
    CONSTRAINT `fk_d_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: dealer_vehicles
-- ============================================================
CREATE TABLE `dealer_vehicles` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `dealer_id`    INT UNSIGNED NOT NULL,
    `vehicle_id`   INT UNSIGNED NOT NULL,
    `stock_status` ENUM('available','on_order','demo_only') NOT NULL DEFAULT 'available',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_dv_dealer_vehicle` (`dealer_id`,`vehicle_id`),
    KEY `idx_dv_vehicle` (`vehicle_id`),
    CONSTRAINT `fk_dv_dealer` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_dv_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: leads
-- ============================================================
CREATE TABLE `leads` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_type`         ENUM('get_best_price','book_test_ride','ev_recommendation','finance_enquiry','charger_installation','fleet_enquiry','dealer_enquiry','insurance_enquiry') NOT NULL DEFAULT 'get_best_price',
    `name`              VARCHAR(100) NOT NULL,
    `email`             VARCHAR(150) NULL,
    `mobile`            VARCHAR(15) NOT NULL,
    `city`              VARCHAR(100) NULL,
    `state`             VARCHAR(100) NULL,
    `pincode`           CHAR(6) NULL,
    `vehicle_id`        INT UNSIGNED NULL,
    `category_id`       INT UNSIGNED NULL,
    `brand_id`          INT UNSIGNED NULL,
    `dealer_id`         INT UNSIGNED NULL,
    `source_page`       VARCHAR(255) NULL,
    `source_url`        VARCHAR(500) NULL,
    `utm_source`        VARCHAR(100) NULL,
    `utm_medium`        VARCHAR(100) NULL,
    `utm_campaign`      VARCHAR(200) NULL,
    `utm_content`       VARCHAR(200) NULL,
    `message`           TEXT NULL,
    `budget`            DECIMAL(12,2) NULL COMMENT 'customer budget INR',
    `purchase_timeline` ENUM('immediately','within_7_days','within_30_days','within_3_months','researching') NULL,
    `use_case`          ENUM('personal','commercial','fleet') NULL,
    `finance_required`  TINYINT(1) NOT NULL DEFAULT 0,
    `charging_required` TINYINT(1) NOT NULL DEFAULT 0,
    `trade_in`          TINYINT(1) NOT NULL DEFAULT 0,
    `status`            ENUM('new','contacted','qualified','converted','lost','spam') NOT NULL DEFAULT 'new',
    `assigned_to`       INT UNSIGNED NULL COMMENT 'users.id of assigned team member',
    `notes`             TEXT NULL,
    `ip_address`        VARCHAR(45) NULL,
    `user_agent`        VARCHAR(500) NULL,
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_l_type` (`lead_type`),
    KEY `idx_l_status` (`status`),
    KEY `idx_l_vehicle` (`vehicle_id`),
    KEY `idx_l_mobile` (`mobile`),
    KEY `idx_l_created` (`created_at`),
    CONSTRAINT `fk_l_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_l_category` FOREIGN KEY (`category_id`) REFERENCES `vehicle_categories` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_l_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_l_dealer` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: lead_notes
-- ============================================================
CREATE TABLE `lead_notes` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_id`    INT UNSIGNED NOT NULL,
    `note`       TEXT NOT NULL,
    `created_by` INT UNSIGNED NULL COMMENT 'users.id',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ln_lead` (`lead_id`),
    CONSTRAINT `fk_ln_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: reviews
-- ============================================================
CREATE TABLE `reviews` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `vehicle_id`        INT UNSIGNED NOT NULL,
    `reviewer_name`     VARCHAR(100) NOT NULL,
    `reviewer_city`     VARCHAR(100) NULL,
    `rating`            DECIMAL(3,1) NOT NULL COMMENT 'rating out of 5',
    `title`             VARCHAR(255) NULL,
    `content`           TEXT NOT NULL,
    `pros`              TEXT NULL,
    `cons`              TEXT NULL,
    `ownership_months`  SMALLINT UNSIGNED NULL,
    `km_driven`         INT UNSIGNED NULL,
    `verified_purchase` TINYINT(1) NOT NULL DEFAULT 0,
    `status`            ENUM('published','pending','rejected') NOT NULL DEFAULT 'pending',
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_r_vehicle` (`vehicle_id`),
    KEY `idx_r_status` (`status`),
    CONSTRAINT `fk_r_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: articles
-- ============================================================
CREATE TABLE `articles` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`           VARCHAR(255) NOT NULL,
    `slug`            VARCHAR(280) NOT NULL,
    `excerpt`         VARCHAR(500) NULL,
    `content`         LONGTEXT NULL,
    `category`        VARCHAR(100) NULL,
    `author_name`     VARCHAR(100) NULL,
    `featured_image`  VARCHAR(500) NULL,
    `status`          ENUM('published','draft') NOT NULL DEFAULT 'draft',
    `published_at`    DATETIME NULL,
    `views`           INT UNSIGNED NOT NULL DEFAULT 0,
    `seo_title`       VARCHAR(160) NULL,
    `seo_description` VARCHAR(320) NULL,
    `schema_json`     TEXT NULL COMMENT 'JSON-LD structured data for SEO',
    `tags`            TEXT NULL COMMENT 'comma-separated tags',
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_articles_slug` (`slug`),
    KEY `idx_a_status` (`status`),
    KEY `idx_a_published` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: charging_stations
-- ============================================================
CREATE TABLE `charging_stations` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(200) NOT NULL,
    `operator`         VARCHAR(150) NULL,
    `address`          VARCHAR(500) NULL,
    `city`             VARCHAR(100) NOT NULL,
    `state`            VARCHAR(100) NOT NULL,
    `pincode`          CHAR(6) NULL,
    `latitude`         DECIMAL(10,7) NULL,
    `longitude`        DECIMAL(10,7) NULL,
    `connector_types`  TEXT NULL COMMENT 'JSON array e.g. ["CCS2","Type2","Bharat_AC"]',
    `total_ports`      TINYINT UNSIGNED NULL,
    `available_ports`  TINYINT UNSIGNED NULL,
    `charging_speed`   ENUM('slow','fast','rapid','ultra_rapid') NOT NULL DEFAULT 'fast',
    `pricing_per_kwh`  DECIMAL(6,2) NULL COMMENT 'INR per kWh',
    `open_24x7`        TINYINT(1) NOT NULL DEFAULT 0,
    `working_hours`    VARCHAR(100) NULL COMMENT 'e.g. 06:00-22:00',
    `amenities`        TEXT NULL COMMENT 'comma-separated amenities e.g. WiFi,Café,Restrooms',
    `status`           ENUM('operational','coming_soon','temporarily_closed') NOT NULL DEFAULT 'operational',
    `verified`         TINYINT(1) NOT NULL DEFAULT 0,
    `google_maps_url`  VARCHAR(500) NULL,
    `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_cs_city` (`city`),
    KEY `idx_cs_state` (`state`),
    KEY `idx_cs_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: calculator_logs
-- ============================================================
CREATE TABLE `calculator_logs` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id`       VARCHAR(64) NULL,
    `calculator_type`  VARCHAR(50) NOT NULL COMMENT 'cost/savings/emi',
    `input_data`       JSON NULL,
    `result_data`      JSON NULL,
    `vehicle_id`       INT UNSIGNED NULL,
    `ip_address`       VARCHAR(45) NULL,
    `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_cl_type` (`calculator_type`),
    KEY `idx_cl_vehicle` (`vehicle_id`),
    KEY `idx_cl_created` (`created_at`),
    CONSTRAINT `fk_cl_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: recommendation_sessions
-- ============================================================
CREATE TABLE `recommendation_sessions` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_token` VARCHAR(64) NOT NULL,
    `inputs`        JSON NULL COMMENT 'user answers to the EV finder quiz',
    `results`       JSON NULL COMMENT 'top 3 vehicle IDs with match scores e.g. [{"id":5,"score":92},...]',
    `lead_id`       INT UNSIGNED NULL,
    `ip_address`    VARCHAR(45) NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_rs_token` (`session_token`),
    KEY `idx_rs_lead` (`lead_id`),
    CONSTRAINT `fk_rs_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: faq
-- ============================================================
CREATE TABLE `faq` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `vehicle_id`    INT UNSIGNED NULL COMMENT 'NULL = global FAQ not tied to a vehicle',
    `question`      VARCHAR(500) NOT NULL,
    `answer`        TEXT NOT NULL,
    `display_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `status`        ENUM('active','inactive') NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`),
    KEY `idx_faq_vehicle` (`vehicle_id`),
    CONSTRAINT `fk_faq_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: city_pricing
-- ============================================================
CREATE TABLE `city_pricing` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `vehicle_id`      INT UNSIGNED NOT NULL,
    `city`            VARCHAR(100) NOT NULL,
    `ex_showroom`     DECIMAL(12,2) NULL COMMENT 'ex-showroom price INR',
    `rto_charges`     DECIMAL(10,2) NULL COMMENT 'RTO/registration charges INR',
    `insurance`       DECIMAL(10,2) NULL COMMENT 'first year insurance INR',
    `tcs`             DECIMAL(10,2) NULL COMMENT 'tax collected at source INR',
    `on_road_price`   DECIMAL(12,2) NULL COMMENT 'total on-road price INR',
    `fame2_subsidy`   DECIMAL(10,2) NULL COMMENT 'FAME-II subsidy applicable INR',
    `state_subsidy`   DECIMAL(10,2) NULL COMMENT 'state EV subsidy INR',
    `effective_price` DECIMAL(12,2) NULL COMMENT 'on-road minus all subsidies INR',
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_cp_vehicle_city` (`vehicle_id`,`city`),
    CONSTRAINT `fk_cp_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: settings
-- ============================================================
CREATE TABLE `settings` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`         VARCHAR(100) NOT NULL,
    `value`       TEXT NULL,
    `group`       VARCHAR(50) NULL DEFAULT 'general',
    `description` VARCHAR(255) NULL,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_settings_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ============================================================
-- SEED DATA
-- ============================================================
-- ============================================================

-- ============================================================
-- Admin user: admin@charj.in / Charj@2024!
-- Password hash below is bcrypt cost=12 for 'Charj@2024!'
-- If this hash does not verify, regenerate with:
--   php -r "echo password_hash('Charj@2024!', PASSWORD_BCRYPT, ['cost'=>12]);"
-- then: UPDATE users SET password='<hash>' WHERE email='admin@charj.in';
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
('Charj Admin', 'admin@charj.in', '$2y$10$rATj/eglpJavIJxCibbjleAeNw1ZyZaR8.Sy4KedNsnG4thDL9gGW', 'admin', 'active', NOW());

-- ============================================================
-- Vehicle Categories
-- ============================================================
INSERT INTO `vehicle_categories` (`id`, `name`, `slug`, `parent_id`, `icon`, `description`, `display_order`, `status`, `seo_title`, `seo_description`) VALUES
(1, 'Electric Scooters', 'electric-scooters', NULL, 'scooter.svg',
 'Best electric scooters in India with price, range, and specifications. Compare Ather 450X, Ola S1 Pro, TVS iQube and more.',
 1, 'active',
 'Electric Scooters in India 2024 - Price, Range, Specs | Charj',
 'Compare best electric scooters in India. Ather 450X, Ola S1 Pro, TVS iQube and more. Check price, range, charging time and get best offers on Charj.in.'),

(2, 'Electric Bikes', 'electric-bikes', NULL, 'bike.svg',
 'Electric motorcycles and bikes available in India. Compare Revolt RV400 and other electric bikes on price, range and performance.',
 2, 'active',
 'Electric Bikes in India 2024 - Price, Range, Specs | Charj',
 'Compare best electric bikes and motorcycles in India. Revolt RV400 and more. Check price, range, and specifications on Charj.in.'),

(3, 'Electric Cars', 'electric-cars', NULL, 'car.svg',
 'Electric cars and SUVs available in India. Compare Tata Nexon EV, MG ZS EV and more on price, range and features.',
 3, 'active',
 'Electric Cars in India 2024 - Price, Range, Specs | Charj',
 'Compare best electric cars in India. Tata Nexon EV, MG ZS EV and more. Check price, range, charging time and get best offers on Charj.in.'),

(4, 'Electric Rickshaws', 'electric-rickshaws', NULL, 'rickshaw.svg',
 'Electric auto-rickshaws and e-rickshaws in India for passenger transport. Best range, low cost and government subsidies available.',
 4, 'active',
 'Electric Rickshaws in India 2024 - Price & Specs | Charj',
 'Best electric rickshaws in India for passenger transport. Compare Mahindra Treo and more with price, range and subsidy details on Charj.in.'),

(5, 'Electric Loaders', 'electric-loaders', NULL, 'loader.svg',
 'Electric cargo loaders and delivery vehicles for last-mile logistics. Compare payload, range and price across brands.',
 5, 'active',
 'Electric Loaders in India 2024 - Price & Specs | Charj',
 'Best electric loaders and cargo three-wheelers in India. Compare Piaggio Ape, Mahindra Treo Zor and more on Charj.in.'),

(6, 'Electric Buses', 'electric-buses', NULL, 'bus.svg',
 'Electric buses for public and private transport in India. FAME-II subsidies available for city bus operators.',
 6, 'active',
 'Electric Buses in India 2024 - Price & Specs | Charj',
 'Electric buses in India for city transport and school use. Compare Olectra, Tata, PMI and more on price and specifications.'),

(7, 'Electric Trucks', 'electric-trucks', NULL, 'truck.svg',
 'Electric trucks and heavy commercial vehicles for long-distance freight and city logistics in India.',
 7, 'active',
 'Electric Trucks in India 2024 - Price & Specs | Charj',
 'Electric trucks and commercial vehicles in India. Compare payload capacity, range and price on Charj.in.'),

(8, 'Electric Cycles', 'electric-cycles', NULL, 'cycle.svg',
 'Electric bicycles and pedal-assist cycles in India. Perfect for short commutes and healthy living.',
 8, 'active',
 'Electric Cycles in India 2024 - Price & Specs | Charj',
 'Best electric cycles and e-bikes in India. Compare price, range and features from Hero, EMotorad, Lectro and more on Charj.in.');

-- ============================================================
-- Brands (6 major Indian EV brands)
-- ============================================================
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `country_of_origin`, `description`, `website`, `status`, `featured`, `seo_title`, `seo_description`) VALUES

(1, 'Ather Energy', 'ather-energy', 'brands/ather-energy.png', 'India',
 'Ather Energy is an Indian electric two-wheeler manufacturer headquartered in Bengaluru, Karnataka. Founded in 2013 by IIT Madras alumni Tarun Mehta and Swapnil Jain, Ather is known for building premium connected electric scooters with smart features, OTA updates and fast-charging infrastructure called Ather Grid.',
 'https://www.atherenergy.com', 'published', 1,
 'Ather Energy Electric Scooters - Price, Range & Specs | Charj',
 'Explore all Ather Energy electric scooters in India. Compare Ather 450X, 450 Plus and more. Check price, range and get best deals on Charj.in.'),

(2, 'Ola Electric', 'ola-electric', 'brands/ola-electric.png', 'India',
 'Ola Electric is a subsidiary of ANI Technologies (Ola). They manufacture electric scooters at their Futurefactory in Krishnagiri, Tamil Nadu - one of the world largest two-wheeler manufacturing facilities. The company went public in August 2024 on Indian stock exchanges.',
 'https://www.olaelectric.com', 'published', 1,
 'Ola Electric Scooters - Price, Range & Specs | Charj',
 'Explore Ola Electric scooters in India. Compare Ola S1 Pro, S1 Air and more. Check price, range and features on Charj.in.'),

(3, 'TVS Motor', 'tvs-motor', 'brands/tvs-motor.png', 'India',
 'TVS Motor Company is one of India largest two-wheeler manufacturers with over 100 years of legacy. Headquartered in Chennai, Tamil Nadu, TVS entered the electric vehicle segment with the iQube electric scooter which has become one of the top-selling EVs in India.',
 'https://www.tvsmotor.com', 'published', 1,
 'TVS Electric Scooters - iQube Price, Range & Specs | Charj',
 'Explore TVS electric vehicles in India. TVS iQube electric scooter price, range, specs and best offers on Charj.in.'),

(4, 'Tata Motors', 'tata-motors', 'brands/tata-motors.png', 'India',
 'Tata Motors is India largest automobile company and part of the Tata Group conglomerate. Headquartered in Mumbai, Tata Motors has been a pioneer in Indian EVs with the Nexon EV becoming India best-selling electric car. They also manufacture commercial EVs under TATA.ev brand.',
 'https://ev.tatamotors.com', 'published', 1,
 'Tata Electric Cars - Nexon EV, Punch EV Price & Specs | Charj',
 'Explore Tata electric cars in India. Tata Nexon EV, Punch EV, Tigor EV price, range, specs and best offers on Charj.in.'),

(5, 'MG Motor', 'mg-motor', 'brands/mg-motor.png', 'United Kingdom',
 'MG Motor India Private Limited is an Indian subsidiary of SAIC Motor Corporation. MG (Morris Garages) markets premium electric vehicles in India with an emphasis on technology and features. The MG ZS EV and MG Comet EV are popular models that offer strong feature sets at competitive price points.',
 'https://www.mgmotor.co.in', 'published', 1,
 'MG Electric Cars - ZS EV, Comet Price & Specs | Charj',
 'Explore MG electric cars in India. MG ZS EV, Comet EV price, range, specs and best offers on Charj.in.'),

(6, 'Revolt Motors', 'revolt-motors', 'brands/revolt-motors.png', 'India',
 'Revolt Motors is an Indian EV startup founded by Rahul Sharma in 2019. Based in Gurgaon, Haryana, Revolt offers subscription-based electric motorcycles with AI-enabled features. The RV400 was India first AI-enabled electric motorcycle offering an artificial exhaust note customization feature via the MyRevolt app.',
 'https://www.revoltmotors.com', 'published', 0,
 'Revolt Electric Bikes - RV400 Price, Range & Specs | Charj',
 'Explore Revolt electric motorcycles in India. Revolt RV400, RV300 price, range, specs and subscription plans on Charj.in.');

-- ============================================================
-- Vehicles (8 seed vehicles with realistic Indian specs)
-- ============================================================
INSERT INTO `vehicles` (
    `id`, `brand_id`, `category_id`, `name`, `slug`, `short_description`,
    `starting_price`, `max_price`, `ex_showroom_price`,
    `on_road_price_delhi`, `on_road_price_mumbai`, `on_road_price_bangalore`,
    `claimed_range`, `real_world_range`, `battery_capacity`, `battery_type`,
    `charging_time_ac`, `charging_time_dc`, `fast_charging_supported`, `fast_charging_time`,
    `charging_connector_type`,
    `motor_power_kw`, `peak_power_kw`, `torque_nm`, `top_speed_kmph`, `acceleration_0_60`,
    `seating_capacity`, `load_capacity_kg`, `boot_space_litres`,
    `ground_clearance_mm`, `wheelbase_mm`, `weight_kg`,
    `ip_rating`,
    `emi_starting`, `warranty_years`, `warranty_km`, `battery_warranty_years`, `battery_warranty_km`,
    `fame2_subsidy`, `state_subsidy_note`,
    `expert_rating`, `user_rating`, `review_count`,
    `best_for`, `status`, `featured`, `launch_year`
) VALUES

-- Vehicle 1: Ather 450X | Starting ₹1,39,000
(1, 1, 1,
 'Ather 450X', 'ather-450x',
 'India smartest electric scooter with 7-inch TFT touchscreen, OTA updates, guide mode and Ather Grid fast-charging network. Best-in-class performance with 0-60 in 3.9 seconds and 90 kmph top speed.',
 139000.00, 164900.00, 139000.00,
 156500.00, 158200.00, 155000.00,
 146, 100, 2.90, 'NMC (Nickel Manganese Cobalt) Lithium-Ion',
 '5h 45min (standard 15A)', NULL, 0, NULL,
 'Proprietary',
 5.40, 6.00, 22.0, 90, 3.9,
 2, NULL, 22,
 165, 1256, 108,
 'IP67',
 3299.00, 3, 30000, 3, 30000,
 NULL, 'Maharashtra: ₹5,000 subsidy. Delhi: Road tax exemption. Gujarat: ₹10,000 subsidy on select variants.',
 8.8, 4.3, 1240,
 'daily_commute', 'published', 1, 2021),

-- Vehicle 2: Ola S1 Pro | Starting ₹1,39,999
(2, 2, 1,
 'Ola S1 Pro', 'ola-s1-pro',
 'Feature-packed electric scooter with 4G connectivity, 7-inch color touchscreen, reverse mode, cruise control and Hyper mode. Built at Ola Futurefactory with pan-India service through 500+ experience centres.',
 139999.00, 139999.00, 139999.00,
 158500.00, 159200.00, 157600.00,
 195, 130, 3.97, 'NMC Lithium-Ion',
 '6h 30min (standard 5A)', NULL, 0, NULL,
 'Proprietary',
 8.50, 11.00, 58.0, 120, 3.0,
 2, NULL, 30,
 165, 1320, 125,
 'IP55',
 3350.00, 3, NULL, 3, NULL,
 NULL, 'Various state subsidies available. Check Ola website for current state-specific offers. Tamil Nadu: ₹15,000.',
 8.2, 4.0, 2850,
 'daily_commute', 'published', 1, 2021),

-- Vehicle 3: TVS iQube | Starting ₹1,42,750
(3, 3, 1,
 'TVS iQube', 'tvs-iqube',
 'TVS iQube S electric scooter with SmartXonnect connectivity, 5-inch TFT display, navigation and excellent build quality backed by TVS 100-year legacy. Wide service network of 500+ touchpoints across India.',
 142750.00, 149950.00, 142750.00,
 162100.00, 163400.00, 161200.00,
 145, 95, 3.04, 'Lithium-Ion',
 '5h 0min (standard 15A)', NULL, 0, NULL,
 'Proprietary',
 4.40, 5.00, 18.5, 78, 4.2,
 2, NULL, 32,
 145, 1272, 118,
 'IP67',
 3400.00, 5, 50000, 5, 50000,
 NULL, 'Tamil Nadu: ₹5,000 subsidy. Gujarat: ₹10,000 subsidy. Maharashtra: ₹5,000 subsidy on TVS iQube.',
 8.5, 4.2, 980,
 'daily_commute', 'published', 1, 2020),

-- Vehicle 4: Revolt RV400 | Starting ₹1,24,999
(4, 6, 2,
 'Revolt RV400', 'revolt-rv400',
 'India first AI-enabled electric motorcycle with customizable artificial exhaust sound, 4G connectivity, geo-fencing and MyRevolt app. Available on EMI-based subscription starting from ₹3,499/month with battery swap option.',
 124999.00, 124999.00, 124999.00,
 142200.00, 143600.00, 141100.00,
 150, 100, 3.24, 'NMC Lithium-Ion',
 '4h 30min (standard 15A)', NULL, 0, NULL,
 'Bharat_DC',
 3.00, NULL, 170.0, 85, NULL,
 2, NULL, NULL,
 185, 1350, 108,
 NULL,
 2999.00, 3, 30000, 3, 30000,
 NULL, 'Delhi: Road tax waiver on electric motorcycles. Maharashtra: Registration fee waiver. Multiple states offer electric 2W subsidy.',
 7.8, 4.0, 560,
 'daily_commute', 'published', 1, 2019),

-- Vehicle 5: Tata Nexon EV | Starting ₹14,49,000
(5, 4, 3,
 'Tata Nexon EV', 'tata-nexon-ev',
 'India best-selling electric car with class-leading 465 km MIDC range, 5-star GNCAP safety rating, ZConnect app with 55+ connected features and comprehensive TATA.ev ecosystem. 7.2 kW home charger bundled.',
 1449000.00, 1997000.00, 1449000.00,
 1598500.00, 1612000.00, 1589000.00,
 465, 320, 30.20, 'NMC Lithium-Ion',
 '8h 45min (7.2 kW AC wallbox)', '56min to 80% (50 kW DC)', 1, '56min to 80% (50 kW CCS2)',
 'CCS2',
 87.00, 100.00, 245.0, 150, 8.9,
 5, NULL, 350,
 190, 2498, 1535,
 'IP67',
 25999.00, 3, 125000, 8, 160000,
 15000.00, 'Maharashtra: ₹2,50,000 subsidy. Gujarat: ₹1,50,000 subsidy. Delhi: Road tax waiver and ₹1,50,000 subsidy. Karnataka: ₹2,00,000 subsidy.',
 9.0, 4.4, 3250,
 'family', 'published', 1, 2020),

-- Vehicle 6: MG ZS EV | Starting ₹18,98,000
(6, 5, 3,
 'MG ZS EV', 'mg-zs-ev',
 'Feature-loaded electric SUV with panoramic sunroof, 360-degree camera, 50.3 kWh battery and access to India largest EV charging network. MG Shield package includes 5-year warranty, 24x7 roadside assistance and connected services.',
 1898000.00, 2348000.00, 1898000.00,
 2090500.00, 2112000.00, 2085000.00,
 461, 340, 50.30, 'NMC Lithium-Ion',
 '8h 30min (7.4 kW AC)', '63min to 80% (50 kW DC)', 1, '63min to 80% (50 kW CCS2)',
 'CCS2',
 105.00, 130.00, 280.0, 175, 8.5,
 5, NULL, 448,
 177, 2585, 1620,
 'IP67',
 35999.00, 5, 150000, 8, 150000,
 NULL, 'Delhi: Road tax waiver. Karnataka: ₹2,00,000 subsidy. Gujarat: ₹1,50,000 subsidy on EVs priced up to ₹25 lakh.',
 8.7, 4.2, 1820,
 'family', 'published', 1, 2020),

-- Vehicle 7: Mahindra Treo | Starting ₹2,95,000
(7, 4, 4,
 'Mahindra Treo', 'mahindra-treo',
 'India most popular electric auto-rickshaw with lithium-ion battery, digital instrument cluster, auto-tipple axle and impressive 170 km range for daily passenger transport. Backed by Mahindra nationwide service network.',
 295000.00, 328000.00, 295000.00,
 320000.00, 318500.00, 315200.00,
 170, 130, 9.43, 'Lithium-Ion',
 '3h 50min (standard AC)', NULL, 0, NULL,
 'Bharat_AC',
 8.00, NULL, 42.0, 55, NULL,
 3, NULL, NULL,
 165, 2050, 535,
 NULL,
 5999.00, 3, 100000, 3, 100000,
 NULL, 'Multiple state subsidies available for e-rickshaw operators under state EV policies. Check local transport department for current rates.',
 8.2, 4.1, 450,
 'fleet', 'published', 0, 2019),

-- Vehicle 8: Piaggio Ape E-City | Starting ₹3,50,000
(8, 4, 5,
 'Piaggio Ape E-City', 'piaggio-ape-e-city',
 'Electric cargo loader from Piaggio Vehicles India with 550 kg payload capacity, 104 km range and low running cost of ₹0.40 per km. Ideal for last-mile delivery, e-commerce logistics and urban cargo transport.',
 350000.00, 380000.00, 350000.00,
 382500.00, 379000.00, 376100.00,
 104, 75, 5.24, 'Lithium-Ion',
 '3h 0min (standard AC)', NULL, 0, NULL,
 'Bharat_AC',
 5.60, NULL, 45.0, 45, NULL,
 1, 550, NULL,
 210, 2050, 480,
 NULL,
 6999.00, 3, 50000, 3, 50000,
 NULL, 'GST benefit on purchase. Multiple state subsidies for cargo EVs. FAME-II for L5 category commercial EVs.',
 7.9, 4.0, 210,
 'cargo', 'published', 0, 2020);

-- ============================================================
-- Vehicle Variants
-- ============================================================
INSERT INTO `vehicle_variants` (`vehicle_id`, `name`, `price`, `battery_capacity`, `claimed_range`, `color_options`, `status`) VALUES
-- Ather 450X | ₹1,39,000 - ₹1,19,000
(1, 'Ather 450X Gen 3 (2.9 kWh)', 139000.00, 2.90, 146, 'Space Grey,Mint,Salt White,Dark,Cosmic Black', 'active'),
(1, 'Ather 450 Plus (2.5 kWh)', 119000.00, 2.50, 112, 'Space Grey,Salt White,Dark', 'active'),
-- Ola S1 Pro | ₹1,39,999 / S1 Air ₹1,09,999
(2, 'Ola S1 Pro (3.97 kWh)', 139999.00, 3.97, 195, 'Jet Black,Neo Mint,Coral Glam,Midnight Blue,Liquid Silver,Porcelain White', 'active'),
(2, 'Ola S1 Air (2.5 kWh)', 109999.00, 2.50, 151, 'Jet Black,Coral Glam,Liquid Silver', 'active'),
-- TVS iQube | ₹1,42,750 / ST ₹1,49,950
(3, 'TVS iQube S (3.04 kWh)', 142750.00, 3.04, 145, 'Starlight Blue,Titanium Grey,Pearl White', 'active'),
(3, 'TVS iQube ST (5.1 kWh)', 149950.00, 5.10, 229, 'Starlight Blue,Titanium Grey,Pearl White', 'active'),
-- Revolt RV400 | ₹1,24,999
(4, 'Revolt RV400', 124999.00, 3.24, 150, 'Canyon Red,Cosmic Black', 'active'),
-- Tata Nexon EV | MR ₹14,49,000 / LR ₹19,97,000
(5, 'Nexon EV Medium Range (30.2 kWh)', 1449000.00, 30.20, 315, 'Intensi-Teal,Flame Red,Daytona Grey,Pristine White,Midnight Black', 'active'),
(5, 'Nexon EV Long Range (40.5 kWh)', 1997000.00, 40.50, 465, 'Intensi-Teal,Flame Red,Daytona Grey,Pristine White,Midnight Black', 'active'),
-- MG ZS EV | Excite ₹18,98,000 / Exclusive ₹23,48,000
(6, 'MG ZS EV Excite (50.3 kWh)', 1898000.00, 50.30, 461, 'Aurora Silver,Starry Black,Candy White,Glaze Red', 'active'),
(6, 'MG ZS EV Exclusive (50.3 kWh)', 2348000.00, 50.30, 461, 'Aurora Silver,Starry Black,Candy White,Glaze Red', 'active'),
-- Mahindra Treo | ₹2,95,000 / Yaari ₹3,28,000
(7, 'Mahindra Treo (Standard)', 295000.00, 9.43, 170, 'Yellow,Green,Blue', 'active'),
(7, 'Mahindra Treo Yaari', 328000.00, 9.43, 170, 'Yellow,Green,Blue', 'active'),
-- Piaggio Ape E-City | ₹3,50,000 / Plus ₹3,80,000
(8, 'Piaggio Ape E-City (Standard)', 350000.00, 5.24, 104, 'Yellow Green,Sky Blue,White', 'active'),
(8, 'Piaggio Ape E-City Plus', 380000.00, 5.24, 104, 'Yellow Green,Sky Blue,White', 'active');

-- ============================================================
-- Vehicle Images
-- ============================================================
INSERT INTO `vehicle_images` (`vehicle_id`, `image_url`, `alt_text`, `image_type`, `display_order`) VALUES
(1, 'vehicles/ather-450x/main.jpg',         'Ather 450X electric scooter Space Grey',       'main',     1),
(1, 'vehicles/ather-450x/gallery-1.jpg',    'Ather 450X side profile view',                 'gallery',  2),
(1, 'vehicles/ather-450x/gallery-2.jpg',    'Ather 450X front 3/4 view',                    'gallery',  3),
(1, 'vehicles/ather-450x/interior-1.jpg',   'Ather 450X 7-inch TFT dashboard display',      'interior', 4),
(2, 'vehicles/ola-s1-pro/main.jpg',         'Ola S1 Pro electric scooter Jet Black',        'main',     1),
(2, 'vehicles/ola-s1-pro/gallery-1.jpg',    'Ola S1 Pro side profile view',                 'gallery',  2),
(2, 'vehicles/ola-s1-pro/interior-1.jpg',   'Ola S1 Pro 7-inch touchscreen dashboard',      'interior', 3),
(3, 'vehicles/tvs-iqube/main.jpg',          'TVS iQube S electric scooter Starlight Blue',  'main',     1),
(3, 'vehicles/tvs-iqube/gallery-1.jpg',     'TVS iQube S side profile view',                'gallery',  2),
(3, 'vehicles/tvs-iqube/interior-1.jpg',    'TVS iQube S SmartXonnect TFT display',         'interior', 3),
(4, 'vehicles/revolt-rv400/main.jpg',       'Revolt RV400 electric motorcycle Canyon Red',  'main',     1),
(4, 'vehicles/revolt-rv400/gallery-1.jpg',  'Revolt RV400 side profile view',               'gallery',  2),
(5, 'vehicles/tata-nexon-ev/main.jpg',      'Tata Nexon EV Intensi-Teal electric car',      'main',     1),
(5, 'vehicles/tata-nexon-ev/gallery-1.jpg', 'Tata Nexon EV front 3/4 view',                 'gallery',  2),
(5, 'vehicles/tata-nexon-ev/gallery-2.jpg', 'Tata Nexon EV rear 3/4 view',                  'gallery',  3),
(5, 'vehicles/tata-nexon-ev/interior-1.jpg','Tata Nexon EV interior with 10.25-inch screen', 'interior', 4),
(6, 'vehicles/mg-zs-ev/main.jpg',           'MG ZS EV Aurora Silver electric SUV',          'main',     1),
(6, 'vehicles/mg-zs-ev/gallery-1.jpg',      'MG ZS EV front 3/4 view',                      'gallery',  2),
(6, 'vehicles/mg-zs-ev/gallery-2.jpg',      'MG ZS EV rear 3/4 view',                       'gallery',  3),
(6, 'vehicles/mg-zs-ev/interior-1.jpg',     'MG ZS EV interior with panoramic sunroof',     'interior', 4),
(7, 'vehicles/mahindra-treo/main.jpg',       'Mahindra Treo electric rickshaw Yellow',       'main',     1),
(7, 'vehicles/mahindra-treo/gallery-1.jpg',  'Mahindra Treo 3/4 front view',                 'gallery',  2),
(8, 'vehicles/piaggio-ape-e-city/main.jpg',  'Piaggio Ape E-City electric loader',           'main',     1),
(8, 'vehicles/piaggio-ape-e-city/gallery-1.jpg', 'Piaggio Ape E-City side view',             'gallery',  2);

-- ============================================================
-- Charging Stations (Delhi, Mumbai, Bangalore)
-- ============================================================
INSERT INTO `charging_stations` (`name`, `operator`, `address`, `city`, `state`, `pincode`, `latitude`, `longitude`, `connector_types`, `total_ports`, `available_ports`, `charging_speed`, `pricing_per_kwh`, `open_24x7`, `working_hours`, `amenities`, `status`, `verified`, `google_maps_url`) VALUES

('Tata Power EV Charging Hub - Connaught Place',
 'Tata Power',
 'Block A, Middle Circle, Connaught Place, New Delhi - 110001',
 'Delhi', 'Delhi', '110001',
 28.6315000, 77.2167000,
 '["CCS2","CHAdeMO","Type2","Bharat_AC","Bharat_DC"]',
 10, 7, 'rapid', 14.00,
 1, NULL,
 'Restrooms,Waiting Lounge,WiFi,Café,Parking',
 'operational', 1,
 'https://maps.google.com/?q=28.6315,77.2167'),

('ChargeZone Fast Charging - BKC Mumbai',
 'ChargeZone',
 'G Block, Bandra Kurla Complex, Bandra East, Mumbai - 400051',
 'Mumbai', 'Maharashtra', '400051',
 19.0596000, 72.8656000,
 '["CCS2","Type2","Bharat_AC","Bharat_DC"]',
 6, 4, 'fast', 18.00,
 0, '07:00-23:00',
 'Parking,Security,Restrooms,Coffee Vending',
 'operational', 1,
 'https://maps.google.com/?q=19.0596,72.8656'),

('BESCOM Public EV Charging - Indiranagar',
 'BESCOM',
 '100 Feet Road, Indiranagar 2nd Stage, Bengaluru - 560038',
 'Bangalore', 'Karnataka', '560038',
 12.9784000, 77.6408000,
 '["CCS2","Bharat_AC","Bharat_DC"]',
 4, 3, 'fast', 12.00,
 0, '06:00-22:00',
 'Parking,Restrooms',
 'operational', 1,
 'https://maps.google.com/?q=12.9784,77.6408');

-- ============================================================
-- FAQ Entries (5 global entries)
-- ============================================================
INSERT INTO `faq` (`vehicle_id`, `question`, `answer`, `display_order`, `status`) VALUES

(NULL,
 'What is the FAME-II subsidy for electric vehicles in India?',
 'The Faster Adoption and Manufacturing of (Hybrid &) Electric Vehicles Phase II (FAME-II) scheme provides direct purchase subsidies for electric vehicles in India. For electric two-wheelers, the subsidy is ₹15,000 per kWh of battery capacity, capped at 40% of the vehicle cost. This is applied directly at the dealership, so you pay the post-subsidy price. For example, a scooter with a 3 kWh battery gets ₹45,000 subsidy. Electric three-wheelers and four-wheelers also receive subsidies under the scheme. The subsidy is available only on vehicles with localised components and registered with the FAME-II portal.',
 1, 'active'),

(NULL,
 'How much does it cost to charge an electric vehicle at home in India?',
 'Home charging in India costs approximately ₹5-8 per kWh depending on your state electricity tariff. For an electric scooter with a 3 kWh battery, a full charge costs ₹15-24 which covers 100-150 km. For an electric car with a 30 kWh battery, a full charge costs ₹150-240. Compare this to petrol: a 30-litre fill at ₹100/litre costs ₹3,000 for approximately 450 km in a petrol car, while the same distance in an EV costs just ₹120-180. EVs cost roughly 80-85% less per km to run compared to petrol vehicles in Indian conditions.',
 2, 'active'),

(NULL,
 'What is the real-world range of electric vehicles versus claimed range?',
 'Real-world range is typically 65-80% of the ARAI or MIDC certified range stated by manufacturers. Several factors reduce range: high speed riding (speeds above 60 kmph significantly drain batteries), air conditioning usage in cars (reduces range by 15-20%), hilly terrain, ambient temperature (batteries perform poorly below 15°C), payload and number of passengers, tyre pressure, and battery age. For example, an EV claiming 465 km MIDC range typically delivers 300-340 km in real Indian city and highway conditions. Always look for real-world range data in user reviews and professional test videos when making a purchase decision.',
 3, 'active'),

(NULL,
 'Can I install a home EV charger in India? What are the requirements and cost?',
 'Yes, installing a home EV charger (EVSE - Electric Vehicle Supply Equipment) is straightforward in India. Requirements include: a dedicated 15A earthed socket for portable chargers (provided free with most EVs), or a dedicated 32A circuit for 7.2 kW wallbox chargers. You need stable three-phase or single-phase power supply and ideally a separate MCB from your distribution board. The portable charger that comes with the EV costs nothing extra and plugs into your existing 15A socket. For a faster dedicated wallbox charger, installation costs ₹8,000-₹25,000 including the charger unit and wiring. Your electricity provider may need to increase your sanctioned load if you plan to charge regularly at high power.',
 4, 'active'),

(NULL,
 'Which electric vehicles qualify for state subsidies in India?',
 'Multiple Indian states offer EV subsidies over and above the central FAME-II subsidy. Key state schemes include: Delhi - up to ₹1,50,000 subsidy on EVs plus road tax and registration fee waiver; Maharashtra - up to ₹2,50,000 on EVs priced up to ₹30 lakh; Gujarat - ₹10,000 for two-wheelers, ₹1,50,000 for four-wheelers; Tamil Nadu - ₹15,000 for electric two-wheelers; Karnataka - ₹2,00,000 for EVs; Rajasthan - waiver on registration fees; Andhra Pradesh - road tax exemption. Subsidies change frequently, so always verify current amounts with your state transport department or the dealer at the time of purchase.',
 5, 'active');

-- ============================================================
-- City Pricing (Tata Nexon EV across 5 cities)
-- ============================================================
INSERT INTO `city_pricing` (`vehicle_id`, `city`, `ex_showroom`, `rto_charges`, `insurance`, `tcs`, `on_road_price`, `fame2_subsidy`, `state_subsidy`, `effective_price`) VALUES
(5, 'Delhi',     1449000.00,     0.00, 85000.00, 0.00, 1534000.00, 15000.00,      0.00, 1519000.00),
(5, 'Mumbai',    1449000.00, 78500.00, 85000.00, 0.00, 1612500.00, 15000.00, 250000.00, 1347500.00),
(5, 'Bangalore', 1449000.00, 72000.00, 85000.00, 0.00, 1606000.00, 15000.00, 200000.00, 1391000.00),
(5, 'Pune',      1449000.00, 76000.00, 85000.00, 0.00, 1610000.00, 15000.00, 250000.00, 1345000.00),
(5, 'Hyderabad', 1449000.00, 80000.00, 85000.00, 0.00, 1614000.00, 15000.00,      0.00, 1599000.00);

-- ============================================================
-- Default Settings
-- ============================================================
INSERT INTO `settings` (`key`, `value`, `group`, `description`) VALUES
('site_name',           'Charj',                                          'general',   'Website display name'),
('site_tagline',        'India''s EV Marketplace',                        'general',   'Website tagline shown in header/footer'),
('site_url',            'https://charj.in',                               'general',   'Primary website URL (no trailing slash)'),
('contact_email',       'hello@charj.in',                                 'general',   'Primary contact email address'),
('support_email',       'support@charj.in',                               'general',   'Customer support email address'),
('contact_phone',       '+91-98765-43210',                                'general',   'Contact phone number'),
('contact_address',     'Bengaluru, Karnataka 560001, India',             'general',   'Office/registered address'),
('meta_title_default',  'Charj - India''s #1 EV Marketplace | Compare Electric Vehicles', 'seo', 'Default page title for SEO'),
('meta_description_default', 'Compare electric vehicles in India. Find best electric scooters, bikes, cars and commercial EVs. Check price, range, specs and get best deals on Charj.in.', 'seo', 'Default meta description'),
('meta_keywords',       'electric vehicles india, ev marketplace, electric scooter price, electric car india, ather 450x, ola s1 pro, tata nexon ev', 'seo', 'Default meta keywords'),
('google_analytics_id', '',                                               'analytics', 'Google Analytics G-XXXXXXXXXX Measurement ID'),
('google_tag_manager',  '',                                               'analytics', 'Google Tag Manager GTM-XXXXXXX container ID'),
('facebook_pixel_id',   '',                                               'analytics', 'Facebook Pixel ID for conversion tracking'),
('leads_notify_email',  'leads@charj.in',                                 'leads',     'Email address to receive new lead notifications'),
('leads_notify_sms',    '',                                               'leads',     'Mobile number to receive new lead SMS alerts'),
('leads_notify_enabled','1',                                              'leads',     '1 = send email on new lead, 0 = disabled'),
('smtp_host',           'smtp.gmail.com',                                 'email',     'SMTP server hostname'),
('smtp_port',           '587',                                            'email',     'SMTP server port (587=TLS, 465=SSL, 25=plain)'),
('smtp_user',           '',                                               'email',     'SMTP authentication username'),
('smtp_pass',           '',                                               'email',     'SMTP authentication password (stored encrypted)'),
('smtp_encryption',     'tls',                                            'email',     'SMTP encryption: tls or ssl'),
('smtp_from_email',     'noreply@charj.in',                              'email',     'From email address for outgoing mail'),
('smtp_from_name',      'Charj',                                          'email',     'From name for outgoing emails'),
('maintenance_mode',    '0',                                              'system',    '1 = site in maintenance mode, 0 = live'),
('per_page_vehicles',   '12',                                             'system',    'Number of vehicles to show per listing page'),
('per_page_articles',   '10',                                             'system',    'Number of articles per page'),
('currency_symbol',     '₹',                                              'system',    'Currency symbol for price display'),
('currency_code',       'INR',                                            'system',    'ISO currency code'),
('date_format',         'd M Y',                                          'system',    'PHP date format for display (e.g. 22 Jun 2024)'),
('map_api_key',         '',                                               'maps',      'Google Maps JavaScript API key for station/dealer maps'),
('social_facebook',     'https://facebook.com/charjin',                  'social',    'Facebook page URL'),
('social_twitter',      'https://twitter.com/charjin',                   'social',    'Twitter/X profile URL'),
('social_instagram',    'https://instagram.com/charjin',                 'social',    'Instagram profile URL'),
('social_youtube',      'https://youtube.com/@charjin',                  'social',    'YouTube channel URL'),
('social_linkedin',     'https://linkedin.com/company/charj',            'social',    'LinkedIn company page URL'),
('whatsapp_number',     '+919876543210',                                  'social',    'WhatsApp business number (digits only with country code)');
