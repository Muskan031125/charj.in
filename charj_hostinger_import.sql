-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: u504377054_charj
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `seo_title` varchar(160) DEFAULT NULL,
  `seo_description` varchar(320) DEFAULT NULL,
  `schema_json` text DEFAULT NULL COMMENT 'JSON-LD structured data for SEO',
  `tags` text DEFAULT NULL COMMENT 'comma-separated tags',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_articles_slug` (`slug`),
  KEY `idx_a_status` (`status`),
  KEY `idx_a_published` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `country_of_origin` varchar(60) NOT NULL DEFAULT 'India',
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `seo_title` varchar(160) DEFAULT NULL,
  `seo_description` varchar(320) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_brands_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Ather Energy','ather-energy','brands/ather-energy.png','India','Ather Energy is an Indian electric two-wheeler manufacturer headquartered in Bengaluru, Karnataka. Founded in 2013 by IIT Madras alumni Tarun Mehta and Swapnil Jain, Ather is known for building premium connected electric scooters with smart features, OTA updates and fast-charging infrastructure called Ather Grid.','https://www.atherenergy.com','published',1,'Ather Energy Electric Scooters - Price, Range & Specs | Charj','Explore all Ather Energy electric scooters in India. Compare Ather 450X, 450 Plus and more. Check price, range and get best deals on Charj.in.','2026-06-22 11:59:31','2026-06-22 11:59:31'),(2,'Ola Electric','ola-electric','brands/ola-electric.png','India','Ola Electric is a subsidiary of ANI Technologies (Ola). They manufacture electric scooters at their Futurefactory in Krishnagiri, Tamil Nadu - one of the world largest two-wheeler manufacturing facilities. The company went public in August 2024 on Indian stock exchanges.','https://www.olaelectric.com','published',1,'Ola Electric Scooters - Price, Range & Specs | Charj','Explore Ola Electric scooters in India. Compare Ola S1 Pro, S1 Air and more. Check price, range and features on Charj.in.','2026-06-22 11:59:31','2026-06-22 11:59:31'),(3,'TVS Motor','tvs-motor','brands/tvs-motor.png','India','TVS Motor Company is one of India largest two-wheeler manufacturers with over 100 years of legacy. Headquartered in Chennai, Tamil Nadu, TVS entered the electric vehicle segment with the iQube electric scooter which has become one of the top-selling EVs in India.','https://www.tvsmotor.com','published',1,'TVS Electric Scooters - iQube Price, Range & Specs | Charj','Explore TVS electric vehicles in India. TVS iQube electric scooter price, range, specs and best offers on Charj.in.','2026-06-22 11:59:31','2026-06-22 11:59:31'),(4,'Tata Motors','tata-motors','brands/tata-motors.png','India','Tata Motors is India largest automobile company and part of the Tata Group conglomerate. Headquartered in Mumbai, Tata Motors has been a pioneer in Indian EVs with the Nexon EV becoming India best-selling electric car. They also manufacture commercial EVs under TATA.ev brand.','https://ev.tatamotors.com','published',1,'Tata Electric Cars - Nexon EV, Punch EV Price & Specs | Charj','Explore Tata electric cars in India. Tata Nexon EV, Punch EV, Tigor EV price, range, specs and best offers on Charj.in.','2026-06-22 11:59:31','2026-06-22 11:59:31'),(5,'MG Motor','mg-motor','brands/mg-motor.png','United Kingdom','MG Motor India Private Limited is an Indian subsidiary of SAIC Motor Corporation. MG (Morris Garages) markets premium electric vehicles in India with an emphasis on technology and features. The MG ZS EV and MG Comet EV are popular models that offer strong feature sets at competitive price points.','https://www.mgmotor.co.in','published',1,'MG Electric Cars - ZS EV, Comet Price & Specs | Charj','Explore MG electric cars in India. MG ZS EV, Comet EV price, range, specs and best offers on Charj.in.','2026-06-22 11:59:31','2026-06-22 11:59:31'),(6,'Revolt Motors','revolt-motors','brands/revolt-motors.png','India','Revolt Motors is an Indian EV startup founded by Rahul Sharma in 2019. Based in Gurgaon, Haryana, Revolt offers subscription-based electric motorcycles with AI-enabled features. The RV400 was India first AI-enabled electric motorcycle offering an artificial exhaust note customization feature via the MyRevolt app.','https://www.revoltmotors.com','published',0,'Revolt Electric Bikes - RV400 Price, Range & Specs | Charj','Explore Revolt electric motorcycles in India. Revolt RV400, RV300 price, range, specs and subscription plans on Charj.in.','2026-06-22 11:59:31','2026-06-22 11:59:31');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calculator_logs`
--

DROP TABLE IF EXISTS `calculator_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calculator_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(64) DEFAULT NULL,
  `calculator_type` varchar(50) NOT NULL COMMENT 'cost/savings/emi',
  `input_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`input_data`)),
  `result_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`result_data`)),
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cl_type` (`calculator_type`),
  KEY `idx_cl_vehicle` (`vehicle_id`),
  KEY `idx_cl_created` (`created_at`),
  CONSTRAINT `fk_cl_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calculator_logs`
--

LOCK TABLES `calculator_logs` WRITE;
/*!40000 ALTER TABLE `calculator_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `calculator_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charging_stations`
--

DROP TABLE IF EXISTS `charging_stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charging_stations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `operator` varchar(150) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` char(6) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `connector_types` text DEFAULT NULL COMMENT 'JSON array e.g. ["CCS2","Type2","Bharat_AC"]',
  `total_ports` tinyint(3) unsigned DEFAULT NULL,
  `available_ports` tinyint(3) unsigned DEFAULT NULL,
  `charging_speed` enum('slow','fast','rapid','ultra_rapid') NOT NULL DEFAULT 'fast',
  `pricing_per_kwh` decimal(6,2) DEFAULT NULL COMMENT 'INR per kWh',
  `open_24x7` tinyint(1) NOT NULL DEFAULT 0,
  `working_hours` varchar(100) DEFAULT NULL COMMENT 'e.g. 06:00-22:00',
  `amenities` text DEFAULT NULL COMMENT 'comma-separated amenities e.g. WiFi,Café,Restrooms',
  `status` enum('operational','coming_soon','temporarily_closed') NOT NULL DEFAULT 'operational',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `google_maps_url` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cs_city` (`city`),
  KEY `idx_cs_state` (`state`),
  KEY `idx_cs_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charging_stations`
--

LOCK TABLES `charging_stations` WRITE;
/*!40000 ALTER TABLE `charging_stations` DISABLE KEYS */;
INSERT INTO `charging_stations` VALUES (1,'Tata Power EV Charging Hub - Connaught Place','Tata Power','Block A, Middle Circle, Connaught Place, New Delhi - 110001','Delhi','Delhi','110001',28.6315000,77.2167000,'[\"CCS2\",\"CHAdeMO\",\"Type2\",\"Bharat_AC\",\"Bharat_DC\"]',10,7,'rapid',14.00,1,NULL,'Restrooms,Waiting Lounge,WiFi,Café,Parking','operational',1,'https://maps.google.com/?q=28.6315,77.2167','2026-06-22 11:59:31','2026-06-22 11:59:31'),(2,'ChargeZone Fast Charging - BKC Mumbai','ChargeZone','G Block, Bandra Kurla Complex, Bandra East, Mumbai - 400051','Mumbai','Maharashtra','400051',19.0596000,72.8656000,'[\"CCS2\",\"Type2\",\"Bharat_AC\",\"Bharat_DC\"]',6,4,'fast',18.00,0,'07:00-23:00','Parking,Security,Restrooms,Coffee Vending','operational',1,'https://maps.google.com/?q=19.0596,72.8656','2026-06-22 11:59:31','2026-06-22 11:59:31'),(3,'BESCOM Public EV Charging - Indiranagar','BESCOM','100 Feet Road, Indiranagar 2nd Stage, Bengaluru - 560038','Bangalore','Karnataka','560038',12.9784000,77.6408000,'[\"CCS2\",\"Bharat_AC\",\"Bharat_DC\"]',4,3,'fast',12.00,0,'06:00-22:00','Parking,Restrooms','operational',1,'https://maps.google.com/?q=12.9784,77.6408','2026-06-22 11:59:31','2026-06-22 11:59:31');
/*!40000 ALTER TABLE `charging_stations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city_pricing`
--

DROP TABLE IF EXISTS `city_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city_pricing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `city` varchar(100) NOT NULL,
  `ex_showroom` decimal(12,2) DEFAULT NULL COMMENT 'ex-showroom price INR',
  `rto_charges` decimal(10,2) DEFAULT NULL COMMENT 'RTO/registration charges INR',
  `insurance` decimal(10,2) DEFAULT NULL COMMENT 'first year insurance INR',
  `tcs` decimal(10,2) DEFAULT NULL COMMENT 'tax collected at source INR',
  `on_road_price` decimal(12,2) DEFAULT NULL COMMENT 'total on-road price INR',
  `fame2_subsidy` decimal(10,2) DEFAULT NULL COMMENT 'FAME-II subsidy applicable INR',
  `state_subsidy` decimal(10,2) DEFAULT NULL COMMENT 'state EV subsidy INR',
  `effective_price` decimal(12,2) DEFAULT NULL COMMENT 'on-road minus all subsidies INR',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cp_vehicle_city` (`vehicle_id`,`city`),
  CONSTRAINT `fk_cp_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city_pricing`
--

LOCK TABLES `city_pricing` WRITE;
/*!40000 ALTER TABLE `city_pricing` DISABLE KEYS */;
INSERT INTO `city_pricing` VALUES (1,5,'Delhi',1449000.00,0.00,85000.00,0.00,1534000.00,15000.00,0.00,1519000.00,'2026-06-22 11:59:31'),(2,5,'Mumbai',1449000.00,78500.00,85000.00,0.00,1612500.00,15000.00,250000.00,1347500.00,'2026-06-22 11:59:31'),(3,5,'Bangalore',1449000.00,72000.00,85000.00,0.00,1606000.00,15000.00,200000.00,1391000.00,'2026-06-22 11:59:31'),(4,5,'Pune',1449000.00,76000.00,85000.00,0.00,1610000.00,15000.00,250000.00,1345000.00,'2026-06-22 11:59:31'),(5,5,'Hyderabad',1449000.00,80000.00,85000.00,0.00,1614000.00,15000.00,0.00,1599000.00,'2026-06-22 11:59:31');
/*!40000 ALTER TABLE `city_pricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dealer_vehicles`
--

DROP TABLE IF EXISTS `dealer_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dealer_vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dealer_id` int(10) unsigned NOT NULL,
  `vehicle_id` int(10) unsigned NOT NULL,
  `stock_status` enum('available','on_order','demo_only') NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dv_dealer_vehicle` (`dealer_id`,`vehicle_id`),
  KEY `idx_dv_vehicle` (`vehicle_id`),
  CONSTRAINT `fk_dv_dealer` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dv_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dealer_vehicles`
--

LOCK TABLES `dealer_vehicles` WRITE;
/*!40000 ALTER TABLE `dealer_vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `dealer_vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dealers`
--

DROP TABLE IF EXISTS `dealers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dealers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned DEFAULT NULL COMMENT 'NULL for multi-brand dealers',
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` char(6) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `google_maps_url` varchar(500) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `brands_handled` text DEFAULT NULL COMMENT 'JSON array of brand names e.g. ["Ather","Ola","TVS"]',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dealers_slug` (`slug`),
  KEY `idx_d_brand` (`brand_id`),
  KEY `idx_d_city` (`city`),
  KEY `idx_d_state` (`state`),
  CONSTRAINT `fk_d_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dealers`
--

LOCK TABLES `dealers` WRITE;
/*!40000 ALTER TABLE `dealers` DISABLE KEYS */;
/*!40000 ALTER TABLE `dealers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ev_glossary`
--

DROP TABLE IF EXISTS `ev_glossary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ev_glossary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `definition` text NOT NULL,
  `category` enum('battery','charging','performance','finance','general','policy') NOT NULL DEFAULT 'general',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ev_glossary`
--

LOCK TABLES `ev_glossary` WRITE;
/*!40000 ALTER TABLE `ev_glossary` DISABLE KEYS */;
INSERT INTO `ev_glossary` VALUES (1,'BMS (Battery Management System)','bms-battery-management-system','System that monitors and manages a battery pack\'s state, including SOC, SOH, temperature, and cell balancing.','battery','2026-06-22 15:09:18'),(2,'SOC (State of Charge)','soc-state-of-charge','Percentage of current charge relative to maximum capacity. 100% SOC = fully charged.','battery','2026-06-22 15:09:18'),(3,'SOH (State of Health)','soh-state-of-health','Measure of a battery\'s capacity compared to when it was new. 80% SOH is typically considered end-of-life.','battery','2026-06-22 15:09:18'),(4,'Range Anxiety','range-anxiety','The fear that an EV\'s battery will run out before reaching the destination or charging point.','general','2026-06-22 15:09:18'),(5,'FAME II','fame-ii','Faster Adoption and Manufacturing of Electric Vehicles Phase II — India\'s central subsidy scheme for EVs.','policy','2026-06-22 15:09:18'),(6,'CHAdeMO','chademo','Japanese DC fast charging standard. Being phased out in India in favor of CCS2.','charging','2026-06-22 15:09:18'),(7,'CCS2 (Combined Charging System 2)','ccs2-combined-charging-system-2','DC fast charging standard used in India for 4-wheelers. Supports up to 350kW.','charging','2026-06-22 15:09:18'),(8,'Type 2','type-2','AC charging standard for EVs. Most home and public AC chargers in India use Type 2 (7.4kW-22kW).','charging','2026-06-22 15:09:18'),(9,'AC Charging','ac-charging','Alternating current charging, typically slower (3.3kW-22kW). Used at home and most public stations.','charging','2026-06-22 15:09:18'),(10,'DC Fast Charging','dc-fast-charging','Direct current charging, much faster (25kW-150kW+). Converts AC to DC externally.','charging','2026-06-22 15:09:18'),(11,'Regenerative Braking','regenerative-braking','System that converts kinetic energy during braking back into electrical energy, increasing range.','performance','2026-06-22 15:09:18'),(12,'Torque','torque','Rotational force. EVs deliver maximum torque instantly (0 RPM), enabling quick acceleration.','performance','2026-06-22 15:09:18'),(13,'kWh (Kilowatt-hour)','kwh-kilowatt-hour','Unit of energy. A 5kWh battery can deliver 5,000 watts for 1 hour. Larger kWh = more range.','battery','2026-06-22 15:09:18'),(14,'kW (Kilowatt)','kw-kilowatt','Unit of power. Determines max speed and acceleration. 1 kW ≈ 1.34 horsepower.','performance','2026-06-22 15:09:18'),(15,'IP Rating','ip-rating','Ingress Protection rating indicating dust and water resistance. IP67 = dustproof + waterproof to 1m.','general','2026-06-22 15:09:18'),(16,'NMC Battery','nmc-battery','Nickel Manganese Cobalt battery chemistry. High energy density, used in premium EVs. Ola S1, Ather.','battery','2026-06-22 15:09:18'),(17,'LFP Battery','lfp-battery','Lithium Iron Phosphate battery. Longer cycle life, safer, lower energy density. Used in Tata EVs.','battery','2026-06-22 15:09:18'),(18,'OBC (On-Board Charger)','obc-on-board-charger','Converts AC from the charging point to DC to charge the battery. Limits max AC charging speed.','charging','2026-06-22 15:09:18'),(19,'V2G (Vehicle to Grid)','v2g-vehicle-to-grid','Technology allowing EVs to discharge power back to the electricity grid. Not yet available in India.','charging','2026-06-22 15:09:18'),(20,'80EEB','80eeb','Indian Income Tax section allowing deduction of up to ₹1.5 lakh on EV loan interest for individuals.','finance','2026-06-22 15:09:18'),(21,'BESCOM/MSEDCL','bescom-msedcl','State electricity distribution companies (Karnataka/Maharashtra). Set EV tariff rates.','charging','2026-06-22 15:09:18'),(22,'Traction Motor','traction-motor','The main motor that drives the wheels in an EV. Types: PMSM (permanent magnet), induction.','performance','2026-06-22 15:09:18'),(23,'Cell-to-Pack (CTP)','cell-to-pack-ctp','Battery design where cells are directly integrated into the pack without modules. Better space efficiency.','battery','2026-06-22 15:09:18'),(24,'ARAI Range','arai-range','Range certified by Automotive Research Association of India. Real-world range is typically 70-85% of ARAI figure.','general','2026-06-22 15:09:18'),(25,'Charging Ecosystem','charging-ecosystem','Network of charging stations, home chargers, and associated services that support EV adoption.','charging','2026-06-22 15:09:18');
/*!40000 ALTER TABLE `ev_glossary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned DEFAULT NULL COMMENT 'NULL = global FAQ not tied to a vehicle',
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `idx_faq_vehicle` (`vehicle_id`),
  CONSTRAINT `fk_faq_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,NULL,'What is the FAME-II subsidy for electric vehicles in India?','The Faster Adoption and Manufacturing of (Hybrid &) Electric Vehicles Phase II (FAME-II) scheme provides direct purchase subsidies for electric vehicles in India. For electric two-wheelers, the subsidy is ₹15,000 per kWh of battery capacity, capped at 40% of the vehicle cost. This is applied directly at the dealership, so you pay the post-subsidy price. For example, a scooter with a 3 kWh battery gets ₹45,000 subsidy. Electric three-wheelers and four-wheelers also receive subsidies under the scheme. The subsidy is available only on vehicles with localised components and registered with the FAME-II portal.',1,'active'),(2,NULL,'How much does it cost to charge an electric vehicle at home in India?','Home charging in India costs approximately ₹5-8 per kWh depending on your state electricity tariff. For an electric scooter with a 3 kWh battery, a full charge costs ₹15-24 which covers 100-150 km. For an electric car with a 30 kWh battery, a full charge costs ₹150-240. Compare this to petrol: a 30-litre fill at ₹100/litre costs ₹3,000 for approximately 450 km in a petrol car, while the same distance in an EV costs just ₹120-180. EVs cost roughly 80-85% less per km to run compared to petrol vehicles in Indian conditions.',2,'active'),(3,NULL,'What is the real-world range of electric vehicles versus claimed range?','Real-world range is typically 65-80% of the ARAI or MIDC certified range stated by manufacturers. Several factors reduce range: high speed riding (speeds above 60 kmph significantly drain batteries), air conditioning usage in cars (reduces range by 15-20%), hilly terrain, ambient temperature (batteries perform poorly below 15°C), payload and number of passengers, tyre pressure, and battery age. For example, an EV claiming 465 km MIDC range typically delivers 300-340 km in real Indian city and highway conditions. Always look for real-world range data in user reviews and professional test videos when making a purchase decision.',3,'active'),(4,NULL,'Can I install a home EV charger in India? What are the requirements and cost?','Yes, installing a home EV charger (EVSE - Electric Vehicle Supply Equipment) is straightforward in India. Requirements include: a dedicated 15A earthed socket for portable chargers (provided free with most EVs), or a dedicated 32A circuit for 7.2 kW wallbox chargers. You need stable three-phase or single-phase power supply and ideally a separate MCB from your distribution board. The portable charger that comes with the EV costs nothing extra and plugs into your existing 15A socket. For a faster dedicated wallbox charger, installation costs ₹8,000-₹25,000 including the charger unit and wiring. Your electricity provider may need to increase your sanctioned load if you plan to charge regularly at high power.',4,'active'),(5,NULL,'Which electric vehicles qualify for state subsidies in India?','Multiple Indian states offer EV subsidies over and above the central FAME-II subsidy. Key state schemes include: Delhi - up to ₹1,50,000 subsidy on EVs plus road tax and registration fee waiver; Maharashtra - up to ₹2,50,000 on EVs priced up to ₹30 lakh; Gujarat - ₹10,000 for two-wheelers, ₹1,50,000 for four-wheelers; Tamil Nadu - ₹15,000 for electric two-wheelers; Karnataka - ₹2,00,000 for EVs; Rajasthan - waiver on registration fees; Andhra Pradesh - road tax exemption. Subsidies change frequently, so always verify current amounts with your state transport department or the dealer at the time of purchase.',5,'active');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_notes`
--

DROP TABLE IF EXISTS `lead_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `note` text NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL COMMENT 'users.id',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ln_lead` (`lead_id`),
  CONSTRAINT `fk_ln_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_notes`
--

LOCK TABLES `lead_notes` WRITE;
/*!40000 ALTER TABLE `lead_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `lead_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_type` enum('get_best_price','book_test_ride','ev_recommendation','finance_enquiry','charger_installation','fleet_enquiry','dealer_enquiry','insurance_enquiry') NOT NULL DEFAULT 'get_best_price',
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(15) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` char(6) DEFAULT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `brand_id` int(10) unsigned DEFAULT NULL,
  `dealer_id` int(10) unsigned DEFAULT NULL,
  `source_page` varchar(255) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `utm_source` varchar(100) DEFAULT NULL,
  `utm_medium` varchar(100) DEFAULT NULL,
  `utm_campaign` varchar(200) DEFAULT NULL,
  `utm_content` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT NULL COMMENT 'customer budget INR',
  `purchase_timeline` enum('immediately','within_7_days','within_30_days','within_3_months','researching') DEFAULT NULL,
  `use_case` enum('personal','commercial','fleet') DEFAULT NULL,
  `finance_required` tinyint(1) NOT NULL DEFAULT 0,
  `charging_required` tinyint(1) NOT NULL DEFAULT 0,
  `trade_in` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('new','contacted','qualified','converted','lost','spam') NOT NULL DEFAULT 'new',
  `assigned_to` int(10) unsigned DEFAULT NULL COMMENT 'users.id of assigned team member',
  `notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_l_type` (`lead_type`),
  KEY `idx_l_status` (`status`),
  KEY `idx_l_vehicle` (`vehicle_id`),
  KEY `idx_l_mobile` (`mobile`),
  KEY `idx_l_created` (`created_at`),
  KEY `fk_l_category` (`category_id`),
  KEY `fk_l_brand` (`brand_id`),
  KEY `fk_l_dealer` (`dealer_id`),
  CONSTRAINT `fk_l_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_l_category` FOREIGN KEY (`category_id`) REFERENCES `vehicle_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_l_dealer` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_l_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owner_questions`
--

DROP TABLE IF EXISTS `owner_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `owner_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `votes` int(11) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner_questions`
--

LOCK TABLES `owner_questions` WRITE;
/*!40000 ALTER TABLE `owner_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `owner_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recommendation_sessions`
--

DROP TABLE IF EXISTS `recommendation_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recommendation_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_token` varchar(64) NOT NULL,
  `inputs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'user answers to the EV finder quiz' CHECK (json_valid(`inputs`)),
  `results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'top 3 vehicle IDs with match scores e.g. [{"id":5,"score":92},...]' CHECK (json_valid(`results`)),
  `lead_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rs_token` (`session_token`),
  KEY `idx_rs_lead` (`lead_id`),
  CONSTRAINT `fk_rs_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recommendation_sessions`
--

LOCK TABLES `recommendation_sessions` WRITE;
/*!40000 ALTER TABLE `recommendation_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `recommendation_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `reviewer_name` varchar(100) NOT NULL,
  `reviewer_city` varchar(100) DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL COMMENT 'rating out of 5',
  `title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `pros` text DEFAULT NULL,
  `cons` text DEFAULT NULL,
  `ownership_months` smallint(5) unsigned DEFAULT NULL,
  `km_driven` int(10) unsigned DEFAULT NULL,
  `verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('published','pending','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_r_vehicle` (`vehicle_id`),
  KEY `idx_r_status` (`status`),
  CONSTRAINT `fk_r_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_settings_key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','Charj','general','Website display name','2026-06-22 11:59:31'),(2,'site_tagline','India\'s EV Marketplace','general','Website tagline shown in header/footer','2026-06-22 11:59:31'),(3,'site_url','https://charj.in','general','Primary website URL (no trailing slash)','2026-06-22 11:59:31'),(4,'contact_email','hello@charj.in','general','Primary contact email address','2026-06-22 11:59:31'),(5,'support_email','support@charj.in','general','Customer support email address','2026-06-22 11:59:31'),(6,'contact_phone','+91-98765-43210','general','Contact phone number','2026-06-22 11:59:31'),(7,'contact_address','Bengaluru, Karnataka 560001, India','general','Office/registered address','2026-06-22 11:59:31'),(8,'meta_title_default','Charj - India\'s #1 EV Marketplace | Compare Electric Vehicles','seo','Default page title for SEO','2026-06-22 11:59:31'),(9,'meta_description_default','Compare electric vehicles in India. Find best electric scooters, bikes, cars and commercial EVs. Check price, range, specs and get best deals on Charj.in.','seo','Default meta description','2026-06-22 11:59:31'),(10,'meta_keywords','electric vehicles india, ev marketplace, electric scooter price, electric car india, ather 450x, ola s1 pro, tata nexon ev','seo','Default meta keywords','2026-06-22 11:59:31'),(11,'google_analytics_id','','analytics','Google Analytics G-XXXXXXXXXX Measurement ID','2026-06-22 11:59:31'),(12,'google_tag_manager','','analytics','Google Tag Manager GTM-XXXXXXX container ID','2026-06-22 11:59:31'),(13,'facebook_pixel_id','','analytics','Facebook Pixel ID for conversion tracking','2026-06-22 11:59:31'),(14,'leads_notify_email','leads@charj.in','leads','Email address to receive new lead notifications','2026-06-22 11:59:31'),(15,'leads_notify_sms','','leads','Mobile number to receive new lead SMS alerts','2026-06-22 11:59:31'),(16,'leads_notify_enabled','1','leads','1 = send email on new lead, 0 = disabled','2026-06-22 11:59:31'),(17,'smtp_host','smtp.gmail.com','email','SMTP server hostname','2026-06-22 11:59:31'),(18,'smtp_port','587','email','SMTP server port (587=TLS, 465=SSL, 25=plain)','2026-06-22 11:59:31'),(19,'smtp_user','','email','SMTP authentication username','2026-06-22 11:59:31'),(20,'smtp_pass','','email','SMTP authentication password (stored encrypted)','2026-06-22 11:59:31'),(21,'smtp_encryption','tls','email','SMTP encryption: tls or ssl','2026-06-22 11:59:31'),(22,'smtp_from_email','noreply@charj.in','email','From email address for outgoing mail','2026-06-22 11:59:31'),(23,'smtp_from_name','Charj','email','From name for outgoing emails','2026-06-22 11:59:31'),(24,'maintenance_mode','0','system','1 = site in maintenance mode, 0 = live','2026-06-22 11:59:31'),(25,'per_page_vehicles','12','system','Number of vehicles to show per listing page','2026-06-22 11:59:31'),(26,'per_page_articles','10','system','Number of articles per page','2026-06-22 11:59:31'),(27,'currency_symbol','₹','system','Currency symbol for price display','2026-06-22 11:59:31'),(28,'currency_code','INR','system','ISO currency code','2026-06-22 11:59:31'),(29,'date_format','d M Y','system','PHP date format for display (e.g. 22 Jun 2024)','2026-06-22 11:59:31'),(30,'map_api_key','','maps','Google Maps JavaScript API key for station/dealer maps','2026-06-22 11:59:31'),(31,'social_facebook','https://facebook.com/charjin','social','Facebook page URL','2026-06-22 11:59:31'),(32,'social_twitter','https://twitter.com/charjin','social','Twitter/X profile URL','2026-06-22 11:59:31'),(33,'social_instagram','https://instagram.com/charjin','social','Instagram profile URL','2026-06-22 11:59:31'),(34,'social_youtube','https://youtube.com/@charjin','social','YouTube channel URL','2026-06-22 11:59:31'),(35,'social_linkedin','https://linkedin.com/company/charj','social','LinkedIn company page URL','2026-06-22 11:59:31'),(36,'whatsapp_number','+919876543210','social','WhatsApp business number (digits only with country code)','2026-06-22 11:59:31');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subsidies`
--

DROP TABLE IF EXISTS `subsidies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subsidies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(100) NOT NULL,
  `vehicle_type` enum('2W','3W','4W') NOT NULL,
  `scheme_name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `conditions` text DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subsidies`
--

LOCK TABLES `subsidies` WRITE;
/*!40000 ALTER TABLE `subsidies` DISABLE KEYS */;
/*!40000 ALTER TABLE `subsidies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL COMMENT 'view_vehicle, compare, calc_savings, calc_subsidy, quiz_complete',
  `entity_id` int(11) DEFAULT NULL COMMENT 'vehicle_id for view_vehicle',
  `entity_name` varchar(200) DEFAULT NULL,
  `metadata` text DEFAULT NULL COMMENT 'JSON — quiz answers, calculator inputs etc.',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_activity`
--

LOCK TABLES `user_activity` WRITE;
/*!40000 ALTER TABLE `user_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer','editor','dealer') NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `saved_vehicles` text DEFAULT NULL,
  `quiz_result` text DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4,'Charj Admin','admin@charj.in','','admin','active','2026-06-22 16:22:46','2026-06-22 16:11:18','2026-06-22 16:22:46','9999999999',NULL,NULL,NULL,0,'$2y$10$HKn7/y.Sh80HZilzs1tHcOnNJ3dv23eusndT6IBbzEqU5mcvLo.FK'),(5,'Test Customer','customer@charj.in','','customer','active','2026-06-22 16:20:30','2026-06-22 16:11:18','2026-06-22 16:20:30','8888888888',NULL,NULL,NULL,0,'$2y$10$gCdBcZ4KSk2stINRLLNNS.rM4lcTcGEaZ09WrSZypc0yhsTUjWeiS');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_categories`
--

DROP TABLE IF EXISTS `vehicle_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `status` enum('published','draft','active','inactive') NOT NULL DEFAULT 'published',
  `seo_title` varchar(160) DEFAULT NULL,
  `seo_description` varchar(320) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_vehicle_categories_slug` (`slug`),
  KEY `idx_vc_parent` (`parent_id`),
  CONSTRAINT `fk_vc_parent` FOREIGN KEY (`parent_id`) REFERENCES `vehicle_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_categories`
--

LOCK TABLES `vehicle_categories` WRITE;
/*!40000 ALTER TABLE `vehicle_categories` DISABLE KEYS */;
INSERT INTO `vehicle_categories` VALUES (1,'Electric Scooters','electric-scooters',NULL,'scooter.svg','Best electric scooters in India with price, range, and specifications. Compare Ather 450X, Ola S1 Pro, TVS iQube and more.',1,'published','Electric Scooters in India 2024 - Price, Range, Specs | Charj','Compare best electric scooters in India. Ather 450X, Ola S1 Pro, TVS iQube and more. Check price, range, charging time and get best offers on Charj.in.'),(2,'Electric Bikes','electric-bikes',NULL,'bike.svg','Electric motorcycles and bikes available in India. Compare Revolt RV400 and other electric bikes on price, range and performance.',2,'published','Electric Bikes in India 2024 - Price, Range, Specs | Charj','Compare best electric bikes and motorcycles in India. Revolt RV400 and more. Check price, range, and specifications on Charj.in.'),(3,'Electric Cars','electric-cars',NULL,'car.svg','Electric cars and SUVs available in India. Compare Tata Nexon EV, MG ZS EV and more on price, range and features.',3,'published','Electric Cars in India 2024 - Price, Range, Specs | Charj','Compare best electric cars in India. Tata Nexon EV, MG ZS EV and more. Check price, range, charging time and get best offers on Charj.in.'),(4,'Electric Rickshaws','electric-rickshaws',NULL,'rickshaw.svg','Electric auto-rickshaws and e-rickshaws in India for passenger transport. Best range, low cost and government subsidies available.',4,'published','Electric Rickshaws in India 2024 - Price & Specs | Charj','Best electric rickshaws in India for passenger transport. Compare Mahindra Treo and more with price, range and subsidy details on Charj.in.'),(5,'Electric Loaders','electric-loaders',NULL,'loader.svg','Electric cargo loaders and delivery vehicles for last-mile logistics. Compare payload, range and price across brands.',5,'published','Electric Loaders in India 2024 - Price & Specs | Charj','Best electric loaders and cargo three-wheelers in India. Compare Piaggio Ape, Mahindra Treo Zor and more on Charj.in.'),(6,'Electric Buses','electric-buses',NULL,'bus.svg','Electric buses for public and private transport in India. FAME-II subsidies available for city bus operators.',6,'published','Electric Buses in India 2024 - Price & Specs | Charj','Electric buses in India for city transport and school use. Compare Olectra, Tata, PMI and more on price and specifications.'),(7,'Electric Trucks','electric-trucks',NULL,'truck.svg','Electric trucks and heavy commercial vehicles for long-distance freight and city logistics in India.',7,'published','Electric Trucks in India 2024 - Price & Specs | Charj','Electric trucks and commercial vehicles in India. Compare payload capacity, range and price on Charj.in.'),(8,'Electric Cycles','electric-cycles',NULL,'cycle.svg','Electric bicycles and pedal-assist cycles in India. Perfect for short commutes and healthy living.',8,'published','Electric Cycles in India 2024 - Price & Specs | Charj','Best electric cycles and e-bikes in India. Compare price, range and features from Hero, EMotorad, Lectro and more on Charj.in.');
/*!40000 ALTER TABLE `vehicle_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_images`
--

DROP TABLE IF EXISTS `vehicle_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `image_type` enum('main','gallery','color','interior') NOT NULL DEFAULT 'gallery',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vi_vehicle` (`vehicle_id`),
  CONSTRAINT `fk_vi_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_images`
--

LOCK TABLES `vehicle_images` WRITE;
/*!40000 ALTER TABLE `vehicle_images` DISABLE KEYS */;
INSERT INTO `vehicle_images` VALUES (1,1,'vehicles/ather-450x/main.jpg','Ather 450X electric scooter Space Grey','main',1,'2026-06-22 11:59:31'),(2,1,'vehicles/ather-450x/gallery-1.jpg','Ather 450X side profile view','gallery',2,'2026-06-22 11:59:31'),(3,1,'vehicles/ather-450x/gallery-2.jpg','Ather 450X front 3/4 view','gallery',3,'2026-06-22 11:59:31'),(4,1,'vehicles/ather-450x/interior-1.jpg','Ather 450X 7-inch TFT dashboard display','interior',4,'2026-06-22 11:59:31'),(5,2,'vehicles/ola-s1-pro/main.jpg','Ola S1 Pro electric scooter Jet Black','main',1,'2026-06-22 11:59:31'),(6,2,'vehicles/ola-s1-pro/gallery-1.jpg','Ola S1 Pro side profile view','gallery',2,'2026-06-22 11:59:31'),(7,2,'vehicles/ola-s1-pro/interior-1.jpg','Ola S1 Pro 7-inch touchscreen dashboard','interior',3,'2026-06-22 11:59:31'),(8,3,'vehicles/tvs-iqube/main.jpg','TVS iQube S electric scooter Starlight Blue','main',1,'2026-06-22 11:59:31'),(9,3,'vehicles/tvs-iqube/gallery-1.jpg','TVS iQube S side profile view','gallery',2,'2026-06-22 11:59:31'),(10,3,'vehicles/tvs-iqube/interior-1.jpg','TVS iQube S SmartXonnect TFT display','interior',3,'2026-06-22 11:59:31'),(11,4,'vehicles/revolt-rv400/main.jpg','Revolt RV400 electric motorcycle Canyon Red','main',1,'2026-06-22 11:59:31'),(12,4,'vehicles/revolt-rv400/gallery-1.jpg','Revolt RV400 side profile view','gallery',2,'2026-06-22 11:59:31'),(13,5,'vehicles/tata-nexon-ev/main.jpg','Tata Nexon EV Intensi-Teal electric car','main',1,'2026-06-22 11:59:31'),(14,5,'vehicles/tata-nexon-ev/gallery-1.jpg','Tata Nexon EV front 3/4 view','gallery',2,'2026-06-22 11:59:31'),(15,5,'vehicles/tata-nexon-ev/gallery-2.jpg','Tata Nexon EV rear 3/4 view','gallery',3,'2026-06-22 11:59:31'),(16,5,'vehicles/tata-nexon-ev/interior-1.jpg','Tata Nexon EV interior with 10.25-inch screen','interior',4,'2026-06-22 11:59:31'),(17,6,'vehicles/mg-zs-ev/main.jpg','MG ZS EV Aurora Silver electric SUV','main',1,'2026-06-22 11:59:31'),(18,6,'vehicles/mg-zs-ev/gallery-1.jpg','MG ZS EV front 3/4 view','gallery',2,'2026-06-22 11:59:31'),(19,6,'vehicles/mg-zs-ev/gallery-2.jpg','MG ZS EV rear 3/4 view','gallery',3,'2026-06-22 11:59:31'),(20,6,'vehicles/mg-zs-ev/interior-1.jpg','MG ZS EV interior with panoramic sunroof','interior',4,'2026-06-22 11:59:31'),(21,7,'vehicles/mahindra-treo/main.jpg','Mahindra Treo electric rickshaw Yellow','main',1,'2026-06-22 11:59:31'),(22,7,'vehicles/mahindra-treo/gallery-1.jpg','Mahindra Treo 3/4 front view','gallery',2,'2026-06-22 11:59:31'),(23,8,'vehicles/piaggio-ape-e-city/main.jpg','Piaggio Ape E-City electric loader','main',1,'2026-06-22 11:59:31'),(24,8,'vehicles/piaggio-ape-e-city/gallery-1.jpg','Piaggio Ape E-City side view','gallery',2,'2026-06-22 11:59:31');
/*!40000 ALTER TABLE `vehicle_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_variants`
--

DROP TABLE IF EXISTS `vehicle_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_variants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'ex-showroom price INR',
  `battery_capacity` decimal(6,2) DEFAULT NULL COMMENT 'kWh',
  `claimed_range` smallint(5) unsigned DEFAULT NULL COMMENT 'km',
  `color_options` text DEFAULT NULL COMMENT 'comma-separated color names',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vv_vehicle` (`vehicle_id`),
  CONSTRAINT `fk_vv_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_variants`
--

LOCK TABLES `vehicle_variants` WRITE;
/*!40000 ALTER TABLE `vehicle_variants` DISABLE KEYS */;
INSERT INTO `vehicle_variants` VALUES (1,1,'Ather 450X Gen 3 (2.9 kWh)',139000.00,2.90,146,'Space Grey,Mint,Salt White,Dark,Cosmic Black','active','2026-06-22 11:59:31'),(2,1,'Ather 450 Plus (2.5 kWh)',119000.00,2.50,112,'Space Grey,Salt White,Dark','active','2026-06-22 11:59:31'),(3,2,'Ola S1 Pro (3.97 kWh)',139999.00,3.97,195,'Jet Black,Neo Mint,Coral Glam,Midnight Blue,Liquid Silver,Porcelain White','active','2026-06-22 11:59:31'),(4,2,'Ola S1 Air (2.5 kWh)',109999.00,2.50,151,'Jet Black,Coral Glam,Liquid Silver','active','2026-06-22 11:59:31'),(5,3,'TVS iQube S (3.04 kWh)',142750.00,3.04,145,'Starlight Blue,Titanium Grey,Pearl White','active','2026-06-22 11:59:31'),(6,3,'TVS iQube ST (5.1 kWh)',149950.00,5.10,229,'Starlight Blue,Titanium Grey,Pearl White','active','2026-06-22 11:59:31'),(7,4,'Revolt RV400',124999.00,3.24,150,'Canyon Red,Cosmic Black','active','2026-06-22 11:59:31'),(8,5,'Nexon EV Medium Range (30.2 kWh)',1449000.00,30.20,315,'Intensi-Teal,Flame Red,Daytona Grey,Pristine White,Midnight Black','active','2026-06-22 11:59:31'),(9,5,'Nexon EV Long Range (40.5 kWh)',1997000.00,40.50,465,'Intensi-Teal,Flame Red,Daytona Grey,Pristine White,Midnight Black','active','2026-06-22 11:59:31'),(10,6,'MG ZS EV Excite (50.3 kWh)',1898000.00,50.30,461,'Aurora Silver,Starry Black,Candy White,Glaze Red','active','2026-06-22 11:59:31'),(11,6,'MG ZS EV Exclusive (50.3 kWh)',2348000.00,50.30,461,'Aurora Silver,Starry Black,Candy White,Glaze Red','active','2026-06-22 11:59:31'),(12,7,'Mahindra Treo (Standard)',295000.00,9.43,170,'Yellow,Green,Blue','active','2026-06-22 11:59:31'),(13,7,'Mahindra Treo Yaari',328000.00,9.43,170,'Yellow,Green,Blue','active','2026-06-22 11:59:31'),(14,8,'Piaggio Ape E-City (Standard)',350000.00,5.24,104,'Yellow Green,Sky Blue,White','active','2026-06-22 11:59:31'),(15,8,'Piaggio Ape E-City Plus',380000.00,5.24,104,'Yellow Green,Sky Blue,White','active','2026-06-22 11:59:31');
/*!40000 ALTER TABLE `vehicle_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `starting_price` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'base ex-showroom price INR',
  `max_price` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'top variant ex-showroom INR',
  `ex_showroom_price` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'base variant ex-showroom INR',
  `on_road_price_delhi` decimal(12,2) DEFAULT NULL COMMENT 'on-road price Delhi INR',
  `on_road_price_mumbai` decimal(12,2) DEFAULT NULL COMMENT 'on-road price Mumbai INR',
  `on_road_price_bangalore` decimal(12,2) DEFAULT NULL COMMENT 'on-road price Bangalore INR',
  `claimed_range` smallint(5) unsigned DEFAULT NULL COMMENT 'km as per ARAI/MIDC certification',
  `real_world_range` smallint(5) unsigned DEFAULT NULL COMMENT 'estimated real-world km',
  `battery_capacity` decimal(6,2) DEFAULT NULL COMMENT 'battery capacity in kWh',
  `battery_type` varchar(60) DEFAULT NULL COMMENT 'e.g. NMC, LFP, NCA',
  `charging_time_ac` varchar(60) DEFAULT NULL COMMENT 'AC charging time e.g. 5h 30min',
  `charging_time_dc` varchar(60) DEFAULT NULL COMMENT 'DC fast charging time e.g. 60min to 80%',
  `fast_charging_supported` tinyint(1) NOT NULL DEFAULT 0,
  `fast_charging_time` varchar(60) DEFAULT NULL,
  `charging_connector_type` enum('CCS2','CHAdeMO','Type2','Bharat_AC','Bharat_DC','Proprietary') DEFAULT NULL,
  `motor_power_kw` decimal(6,2) DEFAULT NULL COMMENT 'continuous motor power in kW',
  `peak_power_kw` decimal(6,2) DEFAULT NULL COMMENT 'peak power in kW',
  `torque_nm` decimal(6,1) DEFAULT NULL COMMENT 'torque in Nm',
  `top_speed_kmph` smallint(5) unsigned DEFAULT NULL COMMENT 'top speed in kmph',
  `acceleration_0_60` decimal(4,1) DEFAULT NULL COMMENT 'seconds to reach 60 kmph from 0',
  `seating_capacity` tinyint(3) unsigned DEFAULT NULL,
  `load_capacity_kg` smallint(5) unsigned DEFAULT NULL COMMENT 'payload in kg',
  `boot_space_litres` smallint(5) unsigned DEFAULT NULL COMMENT 'boot/storage in litres',
  `ground_clearance_mm` smallint(5) unsigned DEFAULT NULL COMMENT 'ground clearance in mm',
  `wheelbase_mm` smallint(5) unsigned DEFAULT NULL COMMENT 'wheelbase in mm',
  `weight_kg` smallint(5) unsigned DEFAULT NULL COMMENT 'kerb weight in kg',
  `ip_rating` varchar(10) DEFAULT NULL COMMENT 'ingress protection e.g. IP67',
  `water_resistance` varchar(100) DEFAULT NULL,
  `emi_starting` decimal(10,2) DEFAULT NULL COMMENT 'lowest monthly EMI in INR',
  `warranty_years` tinyint(3) unsigned DEFAULT NULL,
  `warranty_km` int(10) unsigned DEFAULT NULL,
  `battery_warranty_years` tinyint(3) unsigned DEFAULT NULL,
  `battery_warranty_km` int(10) unsigned DEFAULT NULL,
  `fame2_subsidy` decimal(10,2) DEFAULT NULL COMMENT 'FAME-II subsidy amount INR',
  `state_subsidy_note` varchar(500) DEFAULT NULL,
  `expert_rating` decimal(3,1) DEFAULT NULL COMMENT 'out of 10',
  `user_rating` decimal(3,1) DEFAULT NULL COMMENT 'out of 5',
  `review_count` int(10) unsigned NOT NULL DEFAULT 0,
  `best_for` enum('daily_commute','long_distance','city_only','cargo','fleet','family') DEFAULT NULL,
  `status` enum('published','draft','discontinued') NOT NULL DEFAULT 'draft',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `launch_year` year(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_vehicles_slug` (`slug`),
  KEY `idx_v_brand` (`brand_id`),
  KEY `idx_v_category` (`category_id`),
  KEY `idx_v_status` (`status`),
  KEY `idx_v_featured` (`featured`),
  KEY `idx_v_price` (`starting_price`),
  CONSTRAINT `fk_v_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `fk_v_category` FOREIGN KEY (`category_id`) REFERENCES `vehicle_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,1,1,'Ather 450X','ather-450x','India smartest electric scooter with 7-inch TFT touchscreen, OTA updates, guide mode and Ather Grid fast-charging network. Best-in-class performance with 0-60 in 3.9 seconds and 90 kmph top speed.',NULL,139000.00,164900.00,139000.00,156500.00,158200.00,155000.00,146,100,2.90,'NMC (Nickel Manganese Cobalt) Lithium-Ion','5h 45min (standard 15A)',NULL,0,NULL,'Proprietary',5.40,6.00,22.0,90,3.9,2,NULL,22,165,1256,108,'IP67',NULL,3299.00,3,30000,3,30000,NULL,'Maharashtra: ₹5,000 subsidy. Delhi: Road tax exemption. Gujarat: ₹10,000 subsidy on select variants.',8.8,4.3,1240,'daily_commute','published',1,NULL,2021,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(2,2,1,'Ola S1 Pro','ola-s1-pro','Feature-packed electric scooter with 4G connectivity, 7-inch color touchscreen, reverse mode, cruise control and Hyper mode. Built at Ola Futurefactory with pan-India service through 500+ experience centres.',NULL,139999.00,139999.00,139999.00,158500.00,159200.00,157600.00,195,130,3.97,'NMC Lithium-Ion','6h 30min (standard 5A)',NULL,0,NULL,'Proprietary',8.50,11.00,58.0,120,3.0,2,NULL,30,165,1320,125,'IP55',NULL,3350.00,3,NULL,3,NULL,NULL,'Various state subsidies available. Check Ola website for current state-specific offers. Tamil Nadu: ₹15,000.',8.2,4.0,2850,'daily_commute','published',1,NULL,2021,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(3,3,1,'TVS iQube','tvs-iqube','TVS iQube S electric scooter with SmartXonnect connectivity, 5-inch TFT display, navigation and excellent build quality backed by TVS 100-year legacy. Wide service network of 500+ touchpoints across India.',NULL,142750.00,149950.00,142750.00,162100.00,163400.00,161200.00,145,95,3.04,'Lithium-Ion','5h 0min (standard 15A)',NULL,0,NULL,'Proprietary',4.40,5.00,18.5,78,4.2,2,NULL,32,145,1272,118,'IP67',NULL,3400.00,5,50000,5,50000,NULL,'Tamil Nadu: ₹5,000 subsidy. Gujarat: ₹10,000 subsidy. Maharashtra: ₹5,000 subsidy on TVS iQube.',8.5,4.2,980,'daily_commute','published',1,NULL,2020,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(4,6,2,'Revolt RV400','revolt-rv400','India first AI-enabled electric motorcycle with customizable artificial exhaust sound, 4G connectivity, geo-fencing and MyRevolt app. Available on EMI-based subscription starting from ₹3,499/month with battery swap option.',NULL,124999.00,124999.00,124999.00,142200.00,143600.00,141100.00,150,100,3.24,'NMC Lithium-Ion','4h 30min (standard 15A)',NULL,0,NULL,'Bharat_DC',3.00,NULL,170.0,85,NULL,2,NULL,NULL,185,1350,108,NULL,NULL,2999.00,3,30000,3,30000,NULL,'Delhi: Road tax waiver on electric motorcycles. Maharashtra: Registration fee waiver. Multiple states offer electric 2W subsidy.',7.8,4.0,560,'daily_commute','published',1,NULL,2019,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(5,4,3,'Tata Nexon EV','tata-nexon-ev','India best-selling electric car with class-leading 465 km MIDC range, 5-star GNCAP safety rating, ZConnect app with 55+ connected features and comprehensive TATA.ev ecosystem. 7.2 kW home charger bundled.',NULL,1449000.00,1997000.00,1449000.00,1598500.00,1612000.00,1589000.00,465,320,30.20,'NMC Lithium-Ion','8h 45min (7.2 kW AC wallbox)','56min to 80% (50 kW DC)',1,'56min to 80% (50 kW CCS2)','CCS2',87.00,100.00,245.0,150,8.9,5,NULL,350,190,2498,1535,'IP67',NULL,25999.00,3,125000,8,160000,15000.00,'Maharashtra: ₹2,50,000 subsidy. Gujarat: ₹1,50,000 subsidy. Delhi: Road tax waiver and ₹1,50,000 subsidy. Karnataka: ₹2,00,000 subsidy.',9.0,4.4,3250,'family','published',1,NULL,2020,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(6,5,3,'MG ZS EV','mg-zs-ev','Feature-loaded electric SUV with panoramic sunroof, 360-degree camera, 50.3 kWh battery and access to India largest EV charging network. MG Shield package includes 5-year warranty, 24x7 roadside assistance and connected services.',NULL,1898000.00,2348000.00,1898000.00,2090500.00,2112000.00,2085000.00,461,340,50.30,'NMC Lithium-Ion','8h 30min (7.4 kW AC)','63min to 80% (50 kW DC)',1,'63min to 80% (50 kW CCS2)','CCS2',105.00,130.00,280.0,175,8.5,5,NULL,448,177,2585,1620,'IP67',NULL,35999.00,5,150000,8,150000,NULL,'Delhi: Road tax waiver. Karnataka: ₹2,00,000 subsidy. Gujarat: ₹1,50,000 subsidy on EVs priced up to ₹25 lakh.',8.7,4.2,1820,'family','published',1,NULL,2020,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(7,4,4,'Mahindra Treo','mahindra-treo','India most popular electric auto-rickshaw with lithium-ion battery, digital instrument cluster, auto-tipple axle and impressive 170 km range for daily passenger transport. Backed by Mahindra nationwide service network.',NULL,295000.00,328000.00,295000.00,320000.00,318500.00,315200.00,170,130,9.43,'Lithium-Ion','3h 50min (standard AC)',NULL,0,NULL,'Bharat_AC',8.00,NULL,42.0,55,NULL,3,NULL,NULL,165,2050,535,NULL,NULL,5999.00,3,100000,3,100000,NULL,'Multiple state subsidies available for e-rickshaw operators under state EV policies. Check local transport department for current rates.',8.2,4.1,450,'fleet','published',0,NULL,2019,'2026-06-22 11:59:31','2026-06-22 11:59:31'),(8,4,5,'Piaggio Ape E-City','piaggio-ape-e-city','Electric cargo loader from Piaggio Vehicles India with 550 kg payload capacity, 104 km range and low running cost of ₹0.40 per km. Ideal for last-mile delivery, e-commerce logistics and urban cargo transport.',NULL,350000.00,380000.00,350000.00,382500.00,379000.00,376100.00,104,75,5.24,'Lithium-Ion','3h 0min (standard AC)',NULL,0,NULL,'Bharat_AC',5.60,NULL,45.0,45,NULL,1,550,NULL,210,2050,480,NULL,NULL,6999.00,3,50000,3,50000,NULL,'GST benefit on purchase. Multiple state subsidies for cargo EVs. FAME-II for L5 category commercial EVs.',7.9,4.0,210,'cargo','published',0,NULL,2020,'2026-06-22 11:59:31','2026-06-22 11:59:31');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-22 16:31:27
