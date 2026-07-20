-- ============================================================
-- Charj.in — Additional Tables
-- Run date: 2026-06-22
-- ============================================================

CREATE TABLE IF NOT EXISTS `subsidies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(100) NOT NULL,
  `vehicle_type` enum('2W','3W','4W') NOT NULL,
  `scheme_name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `conditions` text,
  `valid_until` date,
  `source_url` varchar(500),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `owner_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `question` text NOT NULL,
  `answer` text,
  `votes` int(11) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ev_glossary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `definition` text NOT NULL,
  `category` enum('battery','charging','performance','finance','general','policy') NOT NULL DEFAULT 'general',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed ev_glossary with 25 terms
INSERT IGNORE INTO `ev_glossary` (`term`, `slug`, `definition`, `category`) VALUES
('BMS (Battery Management System)', 'bms-battery-management-system', 'System that monitors and manages a battery pack\'s state, including SOC, SOH, temperature, and cell balancing.', 'battery'),
('SOC (State of Charge)', 'soc-state-of-charge', 'Percentage of current charge relative to maximum capacity. 100% SOC = fully charged.', 'battery'),
('SOH (State of Health)', 'soh-state-of-health', 'Measure of a battery\'s capacity compared to when it was new. 80% SOH is typically considered end-of-life.', 'battery'),
('Range Anxiety', 'range-anxiety', 'The fear that an EV\'s battery will run out before reaching the destination or charging point.', 'general'),
('FAME II', 'fame-ii', 'Faster Adoption and Manufacturing of Electric Vehicles Phase II — India\'s central subsidy scheme for EVs.', 'policy'),
('CHAdeMO', 'chademo', 'Japanese DC fast charging standard. Being phased out in India in favor of CCS2.', 'charging'),
('CCS2 (Combined Charging System 2)', 'ccs2-combined-charging-system-2', 'DC fast charging standard used in India for 4-wheelers. Supports up to 350kW.', 'charging'),
('Type 2', 'type-2', 'AC charging standard for EVs. Most home and public AC chargers in India use Type 2 (7.4kW-22kW).', 'charging'),
('AC Charging', 'ac-charging', 'Alternating current charging, typically slower (3.3kW-22kW). Used at home and most public stations.', 'charging'),
('DC Fast Charging', 'dc-fast-charging', 'Direct current charging, much faster (25kW-150kW+). Converts AC to DC externally.', 'charging'),
('Regenerative Braking', 'regenerative-braking', 'System that converts kinetic energy during braking back into electrical energy, increasing range.', 'performance'),
('Torque', 'torque', 'Rotational force. EVs deliver maximum torque instantly (0 RPM), enabling quick acceleration.', 'performance'),
('kWh (Kilowatt-hour)', 'kwh-kilowatt-hour', 'Unit of energy. A 5kWh battery can deliver 5,000 watts for 1 hour. Larger kWh = more range.', 'battery'),
('kW (Kilowatt)', 'kw-kilowatt', 'Unit of power. Determines max speed and acceleration. 1 kW ≈ 1.34 horsepower.', 'performance'),
('IP Rating', 'ip-rating', 'Ingress Protection rating indicating dust and water resistance. IP67 = dustproof + waterproof to 1m.', 'general'),
('NMC Battery', 'nmc-battery', 'Nickel Manganese Cobalt battery chemistry. High energy density, used in premium EVs. Ola S1, Ather.', 'battery'),
('LFP Battery', 'lfp-battery', 'Lithium Iron Phosphate battery. Longer cycle life, safer, lower energy density. Used in Tata EVs.', 'battery'),
('OBC (On-Board Charger)', 'obc-on-board-charger', 'Converts AC from the charging point to DC to charge the battery. Limits max AC charging speed.', 'charging'),
('V2G (Vehicle to Grid)', 'v2g-vehicle-to-grid', 'Technology allowing EVs to discharge power back to the electricity grid. Not yet available in India.', 'charging'),
('80EEB', '80eeb', 'Indian Income Tax section allowing deduction of up to ₹1.5 lakh on EV loan interest for individuals.', 'finance'),
('BESCOM/MSEDCL', 'bescom-msedcl', 'State electricity distribution companies (Karnataka/Maharashtra). Set EV tariff rates.', 'charging'),
('Traction Motor', 'traction-motor', 'The main motor that drives the wheels in an EV. Types: PMSM (permanent magnet), induction.', 'performance'),
('Cell-to-Pack (CTP)', 'cell-to-pack-ctp', 'Battery design where cells are directly integrated into the pack without modules. Better space efficiency.', 'battery'),
('ARAI Range', 'arai-range', 'Range certified by Automotive Research Association of India. Real-world range is typically 70-85% of ARAI figure.', 'general'),
('Charging Ecosystem', 'charging-ecosystem', 'Network of charging stations, home chargers, and associated services that support EV adoption.', 'charging');
