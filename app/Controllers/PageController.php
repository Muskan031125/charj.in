<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ArticleModel;

class PageController extends BaseController
{
    public function about()
    {
        return $this->render('pages/about', [
            'meta_title'       => 'About Charj.in - India\'s EV Decision Engine',
            'meta_description' => 'Charj.in helps Indian buyers find, compare and choose the right electric vehicle. Learn about our mission to accelerate EV adoption in India.',
        ]);
    }

    public function contact()
    {
        return $this->render('pages/contact', [
            'meta_title'       => 'Contact Us - Charj.in',
            'meta_description' => 'Get in touch with the Charj.in team. We help with EV buying queries, dealer partnerships, content partnerships and press enquiries.',
        ]);
    }

    public function privacyPolicy()
    {
        return $this->render('pages/privacy_policy', [
            'meta_title'       => 'Privacy Policy | Charj.in',
            'meta_description' => 'Read the Charj.in privacy policy — how we collect, use and protect your data.',
        ]);
    }

    public function termsOfService()
    {
        return $this->render('pages/terms_of_service', [
            'meta_title'       => 'Terms of Service | Charj.in',
            'meta_description' => 'Terms and conditions for using Charj.in — India\'s EV marketplace.',
        ]);
    }

    public function disclaimer()
    {
        return $this->render('pages/disclaimer', [
            'meta_title'       => 'Disclaimer | Charj.in',
            'meta_description' => 'Disclaimer for information published on Charj.in.',
        ]);
    }

    public function finance()
    {
        return $this->render('pages/finance', [
            'meta_title'       => 'EV Finance Options in India - Loans, EMI & Subsidies | Charj.in',
            'meta_description' => 'Explore EV finance options in India including bank loans, NBFC loans, EMI plans, government subsidies (FAME II, state schemes) and EV insurance bundling.',
            'financeInfo'      => [
                'banks' => [
                    ['name' => 'State Bank of India', 'rate_from' => 8.50, 'max_tenure' => 84, 'note' => 'Green Car Loan for EVs'],
                    ['name' => 'HDFC Bank',           'rate_from' => 8.75, 'max_tenure' => 84, 'note' => 'No foreclosure charges after 12 months'],
                    ['name' => 'ICICI Bank',          'rate_from' => 8.90, 'max_tenure' => 72, 'note' => 'Quick digital approval'],
                    ['name' => 'Axis Bank',           'rate_from' => 9.00, 'max_tenure' => 72, 'note' => 'Available at most EV dealerships'],
                    ['name' => 'Bank of Baroda',      'rate_from' => 8.60, 'max_tenure' => 84, 'note' => 'Baroda Green Vehicle Loan'],
                ],
                'subsidies' => [
                    [
                        'scheme'  => 'FAME II (Central)',
                        'benefit' => 'Up to ₹15,000 per kWh on electric two-wheelers; subsidies for buses and commercial EVs.',
                        'who'     => 'Individual buyers & fleet operators',
                    ],
                    [
                        'scheme'  => 'Delhi EV Policy',
                        'benefit' => 'Subsidy up to ₹30,000 on e-scooters, ₹1,50,000 on e-cars. Road tax & registration fee waiver.',
                        'who'     => 'Delhi residents',
                    ],
                    [
                        'scheme'  => 'Maharashtra EV Policy',
                        'benefit' => 'Early buyer incentive up to ₹25,000 on two-wheelers. Road tax exemption.',
                        'who'     => 'Maharashtra residents',
                    ],
                    [
                        'scheme'  => 'Gujarat EV Policy',
                        'benefit' => 'Subsidy of ₹10,000 on e-two-wheelers and ₹1,50,000 on e-four-wheelers.',
                        'who'     => 'Gujarat residents',
                    ],
                ],
                'tips' => [
                    'Compare total cost of ownership (TCO) over 5 years, not just ex-showroom price.',
                    'Check eligibility for FAME II and state subsidies before booking — subsidy may be deducted at source by the dealer.',
                    'Opt for a longer loan tenure only if it significantly reduces monthly outgo; prepay when possible to reduce interest.',
                    'Some banks offer lower rates if you have your salary account with them — negotiate.',
                    'Bundled EV insurance with OEM warranty can save paperwork and sometimes money.',
                ],
            ],
        ]);
    }

    public function insurance()
    {
        return $this->render('pages/insurance', [
            'meta_title'       => 'EV Insurance in India - Compare Plans & Save | Charj.in',
            'meta_description' => 'Everything you need to know about electric vehicle insurance in India. Compare comprehensive vs third-party, battery coverage, and get the best premium.',
            'insuranceInfo'    => [
                'providers' => [
                    ['name' => 'Tata AIG',      'note' => 'Covers EV battery separately; zero dep add-on available'],
                    ['name' => 'Bajaj Allianz',  'note' => 'Instant online policy; roadside assistance 24x7'],
                    ['name' => 'HDFC ERGO',      'note' => 'EV-specific add-ons including charger coverage'],
                    ['name' => 'New India Assurance', 'note' => 'Trusted PSU insurer; wide network of garages'],
                    ['name' => 'ICICI Lombard',  'note' => 'Cashless repairs at 7,500+ EV-compatible garages'],
                ],
                'tips' => [
                    'Always opt for comprehensive insurance — third-party is the legal minimum but does not cover your own EV.',
                    'Battery replacement coverage is critical — ensure your policy explicitly covers it.',
                    'Zero-depreciation add-on is strongly recommended for EVs given the high replacement cost of parts.',
                    'Check if your insurer has tie-ups with your brand\'s authorised service centres.',
                    'Use the insurer\'s own app to file instant claims and track status.',
                ],
            ],
        ]);
    }

    // =========================================================
    // NEW TOOL & SEO PAGES
    // =========================================================

    public function subsidyCalculator()
    {
        return $this->render('calculators/subsidy', [
            'meta_title'       => 'EV Subsidy Calculator India 2025 - FAME II + State Subsidies | Charj.in',
            'meta_description' => 'Calculate exactly how much EV subsidy you can get from FAME II, state government and tax benefits. Free subsidy calculator for all Indian states.',
        ]);
    }

    public function tcoCalculator()
    {
        return $this->render('calculators/tco', [
            'meta_title'       => 'EV Total Cost of Ownership Calculator - 5 Year Comparison | Charj.in',
            'meta_description' => 'Compare 5-year total cost of owning an EV vs petrol vehicle including EMI, fuel, maintenance, insurance and resale value.',
        ]);
    }

    public function homeChargerGuide()
    {
        return $this->render('pages/home_charger_guide', [
            'meta_title'       => 'Home EV Charger Installation Guide India 2025 - Cost, Process, Subsidy | Charj.in',
            'meta_description' => 'Complete guide to installing home EV charger in India. Costs, DISCOM approval, apartment guide, state-wise process and subsidy information.',
        ]);
    }

    public function usedEv()
    {
        return $this->render('pages/used_ev', [
            'meta_title'       => 'Buy Sell Used Electric Vehicles India - Verified Used EVs | Charj.in',
            'meta_description' => 'Buy or sell used electric vehicles in India. Verified listings with battery health reports, service history and ownership transfer support.',
        ]);
    }

    public function fleetCalculator()
    {
        return $this->render('calculators/fleet', [
            'meta_title'       => 'EV Fleet ROI Calculator India - Business Fleet Savings | Charj.in',
            'meta_description' => 'Calculate exact savings from switching your business fleet to electric vehicles. Fleet EV ROI calculator with FAME II commercial subsidy.',
        ]);
    }

    public function toolsHub()
    {
        return $this->render('pages/tools_hub', [
            'meta_title'       => 'Free EV Tools & Calculators India 2025 — 10+ Tools | Charj.in',
            'meta_description' => 'Free EV tools: subsidy calculator, EMI calculator, TCO comparison, trip range checker, charging cost, resale estimator and more. All in one place.',
        ]);
    }

    public function myEvs()
    {
        return $this->render('pages/my_evs', [
            'meta_title'       => 'My Saved EVs — Wishlist | Charj.in',
            'meta_description' => 'View and manage your saved electric vehicles on Charj.in. Compare your shortlisted EVs side by side.',
        ]);
    }

    public function onRoadPrice()
    {
        return $this->render('calculators/on_road', [
            'meta_title'       => 'EV On-Road Price Calculator India 2026 — State-wise with Subsidy | Charj.in',
            'meta_description' => 'Get the real on-road price of any EV in your state: ex-showroom + road tax + registration + insurance, with PM E-DRIVE and state subsidies auto-deducted.',
        ]);
    }

    public function usedEvValue()
    {
        return $this->render('calculators/used_valuation', [
            'meta_title'       => 'Used EV Valuation Calculator India 2026 — Resale Value with Battery Health | Charj.in',
            'meta_description' => 'Estimate the resale value of any used electric vehicle in India based on age, km driven, battery health and condition. Free instant used-EV valuation.',
        ]);
    }

    public function tripPlanner()
    {
        return $this->render('tools/route_planner', [
            'meta_title'       => 'EV Trip Charging Planner — Plan Charging Stops for Any Route | Charj.in',
            'meta_description' => 'Plan an intercity EV trip: how many charging stops you need, where to stop and estimated charging time based on your EV range and route distance.',
        ]);
    }

    public function insuranceCalculator()
    {
        return $this->render('calculators/insurance_estimator', [
            'meta_title'       => 'EV Insurance Calculator India 2026 — Estimate Annual Premium | Charj.in',
            'meta_description' => 'Estimate your electric vehicle insurance premium: IDV, own-damage, third-party, add-ons and NCB — with the IRDAI EV discount applied. Get free insurer quotes.',
        ]);
    }

    public function evSalesTrends()
    {
        return $this->render('pages/ev_sales_trends', [
            'meta_title'       => 'India EV Sales & Trends 2026 — Monthly Registrations, Top Brands & States | Charj.in',
            'meta_description' => 'India EV sales data and trends: monthly registrations, category split, top-selling brands and leading states for electric vehicle adoption.',
        ]);
    }

    public function waitingPeriods()
    {
        return $this->render('pages/waiting_periods', [
            'meta_title'       => 'EV Waiting Period & Delivery Timeline India 2026 | Charj.in',
            'meta_description' => 'Current waiting periods and delivery timelines for popular electric vehicles in India — know how long before your EV arrives.',
        ]);
    }

    public function bestEv(string $slug = '')
    {
        // Define preset "best EV" pages
        $presets = [
            'best-electric-scooter-india' => [
                'title'         => 'Best Electric Scooters in India 2025',
                'subtitle'      => 'Top rated electric scooters by real-world range, value, reliability and owner satisfaction',
                'category_slug' => 'electric-scooters',
                'description'   => 'Choosing the best electric scooter in India comes down to real-world range, charging convenience, build quality and after-sales service. The Indian electric two-wheeler market has grown rapidly — over 1 million EV scooters were sold in 2023–24 — with options from established players like Ather, TVS, Bajaj, Ola Electric and Hero Electric as well as newer brands like Vida, Simple Energy and Matter. Each brings a different tradeoff between price, range, features and reliability. In this expert-curated list, we have evaluated every major electric scooter sold in India on the basis of real-world range (not just ARAI-claimed numbers), build quality, charging ecosystem, after-sales service network, software and OTA capability, total cost of ownership and verified owner reviews. We update this list quarterly to reflect new launches, price changes and updated ratings. Use this guide to shortlist the right EV scooter for your daily commute, then use our subsidy calculator to find your exact post-subsidy price.',
                'use_case'      => 'Daily commuting in Indian cities',
            ],
            'best-electric-scooter-under-1-lakh' => [
                'title'         => 'Best Electric Scooters Under ₹1 Lakh in India 2025',
                'subtitle'      => 'Top affordable electric scooters with best value for money after FAME II subsidy',
                'category_slug' => 'electric-scooters',
                'max_price'     => 100000,
                'description'   => 'Looking for an electric scooter under ₹1 lakh in India? After FAME II subsidy, there are several excellent options that bring genuine value without cutting corners on safety or reliability. The sub-₹1 lakh EV scooter segment is the fastest growing in India — and for good reason. Fuel cost drops from ₹3–4/km to just ₹0.5–1/km, maintenance is minimal with no engine oil or periodic tuning, and government subsidies make the upfront cost very competitive with petrol scooters. Key things to check in this budget: removable vs fixed battery (removable is better for apartment dwellers), real-world range (60–100 km is realistic at this price), and the brand\'s service network density in your city. We have evaluated every electric scooter available under ₹1 lakh (post-subsidy) based on real owner data, service record, battery reliability and long-term ownership costs.',
                'use_case'      => 'Budget-conscious city commuting',
            ],
            'best-electric-car-india' => [
                'title'         => 'Best Electric Cars in India 2025',
                'subtitle'      => 'Top electric cars ranked by range, features, reliability and total cost of ownership',
                'category_slug' => 'electric-cars',
                'description'   => 'The Indian electric car market has exploded with choices from Tata, MG, Hyundai, Kia, BYD and Maruti — with more arriving from Mercedes, BMW and Volvo in the premium segment. Total EV car sales in India crossed 90,000 units in 2023–24 and are growing 40–50% annually. Choosing the right electric car in India requires balancing real-world range (not just claimed), fast charging availability on your regular routes, total cost of ownership over 5 years, boot space and interior comfort for Indian family needs, and resale value. Tata Motors dominates with over 65% market share, led by the Nexon EV and Tiago EV. But MG ZS EV, Hyundai Ioniq 5, Kia EV6 and the new BYD Atto 3 offer compelling alternatives at different price points. This guide ranks the best electric cars available in India right now — with real-world range data, ownership cost breakdowns and genuine owner feedback to help you make the right decision.',
                'use_case'      => 'Family car for city and highway use',
            ],
        ];

        $preset = $presets[$slug] ?? null;
        if (! $preset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $vehicleModel = new \App\Models\VehicleModel();
        $query        = $vehicleModel->withBrandCategory()->where('vehicles.status', 'published');

        if (! empty($preset['category_slug'])) {
            $query->where('vehicle_categories.slug', $preset['category_slug']);
        }
        if (! empty($preset['max_price'])) {
            $query->where('vehicles.starting_price <=', $preset['max_price']);
        }

        $vehicles = $query->orderBy('vehicles.expert_rating', 'DESC')->limit(10)->findAll();

        return $this->render('pages/best_ev', [
            'title'            => $preset['title'],
            'subtitle'         => $preset['subtitle'],
            'vehicles'         => $vehicles,
            'useCase'          => $preset['use_case'],
            'description'      => $preset['description'],
            'meta_title'       => $preset['title'] . ' | Charj.in',
            'meta_description' => $preset['subtitle'],
        ]);
    }

    // =========================================================
    // SEO LANDING PAGES — Best EV Lists
    // =========================================================

    public function bestElectricScooter()
    {
        $vehicles = (new \App\Models\VehicleModel())
            ->withBrandCategory()
            ->where('vehicle_categories.slug', 'electric-scooters')
            ->where('vehicles.status', 'published')
            ->orderBy('vehicles.claimed_range', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->render('pages/seo_list', [
            'meta_title'       => 'Best Electric Scooters in India 2025 — Top 10 Ranked | Charj.in',
            'meta_description' => 'Compare the best electric scooters in India 2025. Range, price, charging time, real-world reviews. Find your perfect EV scooter.',
            'vehicles'         => $vehicles,
            'heading'          => 'Best Electric Scooters in India 2025',
            'subheading'       => 'Ranked by range, value, and real-world performance',
            'page_slug'        => 'best-electric-scooter',
        ]);
    }

    public function bestEvUnder1Lakh()
    {
        $vehicles = (new \App\Models\VehicleModel())
            ->withBrandCategory()
            ->where('vehicles.ex_showroom_price <=', 100000)
            ->where('vehicles.status', 'published')
            ->orderBy('vehicles.claimed_range', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->render('pages/seo_list', [
            'meta_title'       => 'Best Electric Scooters Under ₹1 Lakh in India 2025 | Charj.in',
            'meta_description' => 'Top electric scooters under ₹1 lakh in India. Compare prices, range, features. Best budget EVs ranked.',
            'vehicles'         => $vehicles,
            'heading'          => 'Best Electric Vehicles Under ₹1 Lakh',
            'subheading'       => 'Affordable EVs with best range and features',
            'page_slug'        => 'best-ev-under-1-lakh',
        ]);
    }

    public function evForDailyCommute()
    {
        $vehicles = (new \App\Models\VehicleModel())
            ->withBrandCategory()
            ->where('vehicles.claimed_range >=', 80)
            ->where('vehicles.status', 'published')
            ->orderBy('vehicles.ex_showroom_price', 'ASC')
            ->limit(10)
            ->findAll();

        return $this->render('pages/seo_list', [
            'meta_title'       => 'Best EV for Daily Commute in India 2025 | Charj.in',
            'meta_description' => 'Best electric vehicles for daily office commute in India. 80km+ real range, affordable price, reliable charging.',
            'vehicles'         => $vehicles,
            'heading'          => 'Best EVs for Daily Commute',
            'subheading'       => 'Reliable EVs with 80km+ range for Indian city commuters',
            'page_slug'        => 'best-ev-for-daily-commute',
        ]);
    }

    public function evForApartment()
    {
        return $this->render('pages/ev_apartment_guide', [
            'meta_title'       => 'EV Charging in Apartments & Societies — Complete Guide India 2025 | Charj.in',
            'meta_description' => 'How to charge EV in apartment. Society NOC, wiring, charger options, BESCOM/MSEDCL rules, cost guide for India.',
        ]);
    }

    public function sitemap()
    {
        $vehicleModel  = new VehicleModel();
        $brandModel    = new BrandModel();
        $categoryModel = new CategoryModel();
        $articleModel  = new ArticleModel();

        $vehicles = $vehicleModel
            ->select('vehicles.slug, vehicles.updated_at')
            ->where('vehicles.status', 'published')
            ->findAll();

        $brands = $brandModel
            ->select('slug, updated_at')
            ->where('status', 'published')
            ->findAll();

        $categories = $categoryModel
            ->select('slug')
            ->where('status', 'published')
            ->findAll();

        $articles = $articleModel
            ->select('slug, published_at, updated_at')
            ->where('status', 'published')
            ->findAll();

        // Static pages
        $staticPages = [
            ['url' => site_url('/'),              'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => site_url('vehicles'),       'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => site_url('brands'),         'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => site_url('compare'),        'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => site_url('articles'),       'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => site_url('dealers'),        'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => site_url('charging'),       'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => site_url('calculators/emi'),      'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => site_url('calculators/savings'),  'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => site_url('calculators/cost'),     'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => site_url('recommend'),      'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => site_url('about'),          'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => site_url('contact'),        'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => site_url('finance'),        'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => site_url('insurance'),      'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        $today = date('Y-m-d');

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        foreach ($staticPages as $page) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($page['url']) . "</loc>\n";
            $xml .= '    <lastmod>' . $today . "</lastmod>\n";
            $xml .= '    <changefreq>' . $page['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $page['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        // Vehicle pages
        foreach ($vehicles as $v) {
            $lastMod = !empty($v['updated_at']) ? date('Y-m-d', strtotime($v['updated_at'])) : $today;
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(site_url('vehicles/' . $v['slug'])) . "</loc>\n";
            $xml .= '    <lastmod>' . $lastMod . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // Brand pages
        foreach ($brands as $b) {
            $lastMod = !empty($b['updated_at']) ? date('Y-m-d', strtotime($b['updated_at'])) : $today;
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(site_url('brands/' . $b['slug'])) . "</loc>\n";
            $xml .= '    <lastmod>' . $lastMod . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Category pages
        foreach ($categories as $c) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(site_url('vehicles/category/' . $c['slug'])) . "</loc>\n";
            $xml .= '    <lastmod>' . $today . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Article pages
        foreach ($articles as $a) {
            $lastMod = !empty($a['updated_at'])
                ? date('Y-m-d', strtotime($a['updated_at']))
                : (!empty($a['published_at']) ? date('Y-m-d', strtotime($a['published_at'])) : $today);
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(site_url('articles/' . $a['slug'])) . "</loc>\n";
            $xml .= '    <lastmod>' . $lastMod . "</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/xml', 'UTF-8')
            ->setBody($xml);
    }
}
