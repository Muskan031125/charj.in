<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Charj.in - India's EV Marketplace
 * Complete Route Configuration for CodeIgniter 4
 *
 * @var RouteCollection $routes
 */

// ============================================================
// ROUTER OPTIONS
// ============================================================
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ============================================================
// PUBLIC ROUTES
// ============================================================

// Home
$routes->get('/', 'HomeController::index');

// -----------------------------------------------------------
// Vehicle Listings & Detail
// -----------------------------------------------------------
$routes->get('vehicles',           'VehicleController::index');
$routes->get('vehicles/(:segment)', 'VehicleController::show/$1');

// Category landing pages (SEO-friendly URLs)
$routes->get('electric-scooters',  'VehicleController::category/electric-scooters');
$routes->get('electric-bikes',     'VehicleController::category/electric-bikes');
$routes->get('electric-cars',      'VehicleController::category/electric-cars');
$routes->get('electric-rickshaws', 'VehicleController::category/electric-rickshaws');
$routes->get('electric-loaders',   'VehicleController::category/electric-loaders');
$routes->get('commercial-ev',      'VehicleController::category/commercial-ev');

// -----------------------------------------------------------
// Brands
// -----------------------------------------------------------
$routes->get('brands',             'BrandController::index');
$routes->get('brands/(:segment)',  'BrandController::show/$1');

// -----------------------------------------------------------
// Compare
// -----------------------------------------------------------
$routes->get('compare',                              'CompareController::index');
$routes->get('compare/(:segment)/vs/(:segment)',     'CompareController::versus/$1/$2');

// -----------------------------------------------------------
// Calculators
// -----------------------------------------------------------
$routes->get('ev-cost-calculator',    'CalculatorController::cost');
$routes->get('ev-savings-calculator', 'CalculatorController::savings');
$routes->get('ev-emi-calculator',     'CalculatorController::emi');

// Calculator API endpoints (JSON responses)
$routes->post('api/calculate/cost',    'CalculatorController::apiCost');
$routes->post('api/calculate/savings', 'CalculatorController::apiSavings');
$routes->post('api/calculate/emi',     'CalculatorController::apiEmi');

// -----------------------------------------------------------
// EV Recommendation Finder
// -----------------------------------------------------------
$routes->get('find-my-ev',          'QuizController::index'); // Redirects to new clean quiz
$routes->post('api/recommendation', 'RecommendationController::recommend');

// -----------------------------------------------------------
// Charging Stations
// -----------------------------------------------------------
$routes->get('charging-stations',             'ChargingController::index');
$routes->get('charging-stations/api',         'ChargingController::api');
$routes->get('charging-stations/(:segment)',  'ChargingController::city/$1');

// -----------------------------------------------------------
// Reviews
// -----------------------------------------------------------
$routes->get('reviews/submit/(:segment)', 'ReviewController::submit/$1');
$routes->post('reviews/store',            'ReviewController::store');

// -----------------------------------------------------------
// EV Dealers
// -----------------------------------------------------------
$routes->get('ev-dealers',            'DealerController::index');
$routes->get('ev-dealers/(:segment)', 'DealerController::city/$1');
$routes->get('dealers/(:segment)',    'DealerController::show/$1');

// -----------------------------------------------------------
// Finance & Insurance
// -----------------------------------------------------------
$routes->get('ev-finance',    'PageController::finance');
$routes->get('ev-insurance',  'PageController::insurance');

// -----------------------------------------------------------
// News & Articles
// -----------------------------------------------------------
$routes->get('news',            'ArticleController::index');
$routes->get('news/(:segment)', 'ArticleController::show/$1');

// -----------------------------------------------------------
// Static Pages
// -----------------------------------------------------------
$routes->get('about',            'PageController::about');
$routes->get('contact',          'PageController::contact');
$routes->get('privacy-policy',   'PageController::privacyPolicy');
$routes->get('terms-of-service', 'PageController::termsOfService');
$routes->get('disclaimer',       'PageController::disclaimer');

// URL aliases / redirects
$routes->get('blog',         function() { return redirect()->to(base_url('news')); });
$routes->get('for-dealers',  function() { return redirect()->to(base_url('ev-dealers')); });
$routes->get('for-brands',   function() { return redirect()->to(base_url('explore')); });
$routes->get('e-rickshaws',  function() { return redirect()->to(base_url('electric-rickshaws')); });

// -----------------------------------------------------------
// Sitemap
// -----------------------------------------------------------
$routes->get('sitemap.xml', 'PageController::sitemap');

// -----------------------------------------------------------
// Micro-Tool Pages
// -----------------------------------------------------------
$routes->get('charging-cost',        'Tools\ChargingCostController::index');
$routes->get('can-i-make-it',        'Tools\TripRangeController::index');
$routes->get('resale-estimator',     'Tools\ResaleController::index');
$routes->get('charger-check',        'Tools\ChargerCheckController::index');

// -----------------------------------------------------------
// New Tool Pages
// -----------------------------------------------------------
$routes->get('ev-tools',             'PageController::toolsHub');
$routes->get('my-evs',               'PageController::myEvs');
$routes->get('on-road-price',        'PageController::onRoadPrice');
$routes->get('used-ev-value',        'PageController::usedEvValue');
$routes->get('trip-planner',         'PageController::tripPlanner');
$routes->get('ev-insurance-calculator','PageController::insuranceCalculator');
$routes->get('ev-sales-trends',      'PageController::evSalesTrends');
$routes->get('ev-waiting-periods',   'PageController::waitingPeriods');
$routes->get('subsidy-calculator',   'PageController::subsidyCalculator');
$routes->get('tco-calculator',       'PageController::tcoCalculator');
$routes->get('home-charger-guide',   'PageController::homeChargerGuide');
$routes->get('used-ev',              'PageController::usedEv');
$routes->get('fleet-calculator',     'PageController::fleetCalculator');
$routes->get('ev-fleet-calculator',  'PageController::fleetCalculator');

// -----------------------------------------------------------
// SEO Landing Pages — Best EV for [Use Case]
// -----------------------------------------------------------
$routes->get('best-evs/(:segment)',                      'PageController::bestEv/$1');
$routes->get('best-electric-scooter-india',              'PageController::bestEv/best-electric-scooter-india');
$routes->get('best-electric-car-india',                  'PageController::bestEv/best-electric-car-india');
$routes->get('best-electric-scooter-under-1-lakh',       'PageController::bestEv/best-electric-scooter-under-1-lakh');

// -----------------------------------------------------------
// EV Glossary (DB-backed with Alpine.js filter)
// -----------------------------------------------------------
$routes->get('ev-glossary', 'GlossaryController::index');

// -----------------------------------------------------------
// SEO Landing Pages — New
// -----------------------------------------------------------
$routes->get('best-electric-scooter',    'PageController::bestElectricScooter');
$routes->get('best-ev-under-1-lakh',     'PageController::bestEvUnder1Lakh');
$routes->get('best-ev-for-daily-commute','PageController::evForDailyCommute');
$routes->get('ev-for-apartment',         'PageController::evForApartment');

// -----------------------------------------------------------
// EV Finder Quiz — /ev-finder redirects to /find-my-ev
// -----------------------------------------------------------
$routes->get('ev-finder', function() { return redirect()->to(base_url('find-my-ev')); });
$routes->post('ev-finder/save', 'QuizController::saveAnswers');

// -----------------------------------------------------------
// AI endpoints (public, rate-limited by design)
// -----------------------------------------------------------
$routes->post('ai/ev-recommend', 'AiController::evRecommend');
$routes->post('ai/calculate',    'AiController::calculate');
$routes->post('ai/chat',         'AiController::chat');

// -----------------------------------------------------------
// Tool Pages
// -----------------------------------------------------------
$routes->get('battery-cost', 'Tools\BatteryCostController::index');

// -----------------------------------------------------------
// -----------------------------------------------------------
// Explore (brand browser)
// -----------------------------------------------------------
$routes->get('explore', 'ExploreController::index');

// -----------------------------------------------------------
// Customer auth routes — disabled (public site, admin only)
// -----------------------------------------------------------
$routes->get('login',    'AuthController::login');
$routes->post('login',   'AuthController::loginPost');
$routes->get('register', function() { return redirect()->to(base_url()); });
$routes->get('logout',   'AuthController::logout');   // logout still clears session
$routes->get('profile',  function() { return redirect()->to(base_url()); });
$routes->post('save-vehicle/(:num)', function() { return redirect()->to(base_url()); });

// -----------------------------------------------------------
// Lead Submission
// -----------------------------------------------------------
$routes->post('lead/submit', 'LeadController::submit');

// -----------------------------------------------------------
// Vehicle Owner Q&A (public submission)
// -----------------------------------------------------------
$routes->post('vehicles/(:segment)/question', 'VehicleController::submitQuestion/$1');

// -----------------------------------------------------------
// Vehicle Search API (for compare/recommendation selectors)
// -----------------------------------------------------------
$routes->get('api/vehicles/search', 'VehicleController::apiSearch');

// ============================================================
// ADMIN ROUTES
// ============================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // Dashboard
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('preview-as-customer', 'DashboardController::previewAsCustomer');
    $routes->get('exit-customer-preview', 'DashboardController::exitCustomerPreview');

    // -----------------------------------------------------------
    // Authentication
    // -----------------------------------------------------------
    $routes->get('login',  'AuthController::login');
    $routes->post('login', 'AuthController::attempt');
    $routes->get('logout', 'AuthController::logout');

    // -----------------------------------------------------------
    // Vehicles
    // -----------------------------------------------------------
    $routes->get('vehicles',                    'VehicleAdminController::index');
    $routes->get('vehicles/create',             'VehicleAdminController::create');
    $routes->post('vehicles/store',             'VehicleAdminController::store');
    $routes->get('vehicles/bulk-import',        'VehicleAdminController::bulkImport');
    $routes->post('vehicles/bulk-import',       'VehicleAdminController::bulkImportProcess');
    $routes->get('vehicles/edit/(:num)',        'VehicleAdminController::edit/$1');
    $routes->post('vehicles/update/(:num)',     'VehicleAdminController::update/$1');
    $routes->get('vehicles/delete/(:num)',      'VehicleAdminController::delete/$1');

    // -----------------------------------------------------------
    // Brands
    // -----------------------------------------------------------
    $routes->get('brands',               'BrandAdminController::index');
    $routes->get('brands/create',        'BrandAdminController::create');
    $routes->post('brands/store',        'BrandAdminController::store');
    $routes->get('brands/edit/(:num)',   'BrandAdminController::edit/$1');
    $routes->post('brands/update/(:num)','BrandAdminController::update/$1');

    // -----------------------------------------------------------
    // Dealers
    // -----------------------------------------------------------
    $routes->get('dealers',               'DealerAdminController::index');
    $routes->get('dealers/create',        'DealerAdminController::create');
    $routes->post('dealers/store',        'DealerAdminController::store');
    $routes->get('dealers/edit/(:num)',   'DealerAdminController::edit/$1');
    $routes->post('dealers/update/(:num)','DealerAdminController::update/$1');

    // -----------------------------------------------------------
    // Leads
    // -----------------------------------------------------------
    $routes->get('leads',                          'LeadAdminController::index');
    $routes->get('leads/(:num)',                   'LeadAdminController::show/$1');
    $routes->post('leads/update-status/(:num)',    'LeadAdminController::updateStatus/$1');
    $routes->post('leads/add-note/(:num)',         'LeadAdminController::addNote/$1');
    $routes->get('export/leads',                   'LeadAdminController::exportCsv');

    // -----------------------------------------------------------
    // Articles
    // -----------------------------------------------------------
    $routes->get('articles',               'ArticleAdminController::index');
    $routes->get('articles/create',        'ArticleAdminController::create');
    $routes->post('articles/store',        'ArticleAdminController::store');
    $routes->get('articles/edit/(:num)',   'ArticleAdminController::edit/$1');
    $routes->post('articles/update/(:num)','ArticleAdminController::update/$1');

    // -----------------------------------------------------------
    // Charging Stations
    // -----------------------------------------------------------
    $routes->get('charging',         'ChargingAdminController::index');
    $routes->get('charging/create',  'ChargingAdminController::create');
    $routes->post('charging/store',  'ChargingAdminController::store');

    // -----------------------------------------------------------
    // Users
    // -----------------------------------------------------------
    $routes->get('users',               'UsersAdminController::index');
    $routes->get('users/(:num)',        'UsersAdminController::show/$1');
    $routes->get('users/delete/(:num)', 'UsersAdminController::delete/$1');

    // -----------------------------------------------------------
    // Reviews
    // -----------------------------------------------------------
    $routes->get('reviews',                'ReviewsAdminController::index');
    $routes->get('reviews/approve/(:num)', 'ReviewsAdminController::approve/$1');
    $routes->get('reviews/reject/(:num)',  'ReviewsAdminController::reject/$1');

    // -----------------------------------------------------------
    // Owner Q&A
    // -----------------------------------------------------------
    $routes->get('qa',                    'OwnerQAAdminController::index');
    $routes->get('qa/approve/(:num)',     'OwnerQAAdminController::approve/$1');
    $routes->post('qa/answer/(:num)',     'OwnerQAAdminController::answer/$1');
    $routes->get('qa/delete/(:num)',      'OwnerQAAdminController::delete/$1');

    // -----------------------------------------------------------
    // Subsidies
    // -----------------------------------------------------------
    $routes->get('subsidies',             'SubsidiesAdminController::index');
    $routes->get('subsidies/create',      'SubsidiesAdminController::create');
    $routes->post('subsidies/store',      'SubsidiesAdminController::store');
    $routes->get('subsidies/delete/(:num)', 'SubsidiesAdminController::delete/$1');

    // -----------------------------------------------------------
    // Settings
    // -----------------------------------------------------------
    $routes->get('settings',      'SettingsAdminController::index');
    $routes->post('settings/save','SettingsAdminController::save');

    // -----------------------------------------------------------
    // Announcements
    // -----------------------------------------------------------
    $routes->get('announcements',                 'AnnouncementAdminController::index');
    $routes->get('announcements/create',          'AnnouncementAdminController::create');
    $routes->post('announcements/store',          'AnnouncementAdminController::store');
    $routes->get('announcements/edit/(:num)',     'AnnouncementAdminController::edit/$1');
    $routes->post('announcements/update/(:num)',  'AnnouncementAdminController::update/$1');
    $routes->get('announcements/delete/(:num)',   'AnnouncementAdminController::delete/$1');

    // -----------------------------------------------------------
    // Events
    // -----------------------------------------------------------
    $routes->get('events',                'EventAdminController::index');
    $routes->get('events/create',         'EventAdminController::create');
    $routes->post('events/store',         'EventAdminController::store');
    $routes->get('events/edit/(:num)',    'EventAdminController::edit/$1');
    $routes->post('events/update/(:num)', 'EventAdminController::update/$1');
    $routes->get('events/delete/(:num)',  'EventAdminController::delete/$1');

    // -----------------------------------------------------------
    // Spare Parts
    // -----------------------------------------------------------
    $routes->get('spare-parts',                'SparePartAdminController::index');
    $routes->get('spare-parts/create',         'SparePartAdminController::create');
    $routes->post('spare-parts/store',         'SparePartAdminController::store');
    $routes->get('spare-parts/edit/(:num)',    'SparePartAdminController::edit/$1');
    $routes->post('spare-parts/update/(:num)', 'SparePartAdminController::update/$1');
    $routes->get('spare-parts/delete/(:num)',  'SparePartAdminController::delete/$1');

    // -----------------------------------------------------------
    // Brands — delete
    // -----------------------------------------------------------
    $routes->get('brands/delete/(:num)', 'BrandAdminController::delete/$1');

    // -----------------------------------------------------------
    // Charging Stations — edit/update/delete
    // -----------------------------------------------------------
    $routes->get('charging/edit/(:num)',    'ChargingAdminController::edit/$1');
    $routes->post('charging/update/(:num)', 'ChargingAdminController::update/$1');
    $routes->get('charging/delete/(:num)',  'ChargingAdminController::delete/$1');

    // -----------------------------------------------------------
    // Dealers — delete + toggle
    // -----------------------------------------------------------
    $routes->get('dealers/delete/(:num)',  'DealerAdminController::delete/$1');
    $routes->post('dealers/delete/(:num)', 'DealerAdminController::delete/$1');
    $routes->get('dealers/toggle/(:num)',  'DealerAdminController::toggle/$1');

    // AI helpers
    $routes->post('ai/suggest-image', 'AiController::suggestImage');
});
