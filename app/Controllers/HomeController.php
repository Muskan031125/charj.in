<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\BrandModel;
use App\Models\ArticleModel;
use App\Models\CategoryModel;

class HomeController extends BaseController
{
    public function index()
    {
        $vehicleModel  = new VehicleModel();
        $brandModel    = new BrandModel();
        $articleModel  = new ArticleModel();
        $categoryModel = new CategoryModel();

        // Featured vehicles — up to 6 published + featured, best rated first
        $featuredVehicles = $vehicleModel
            ->withBrandCategory()
            ->where('vehicles.status', 'published')
            ->where('vehicles.featured', 1)
            ->orderBy('vehicles.expert_rating', 'DESC')
            ->limit(6)
            ->findAll();

        // Popular brands — all published brands with a vehicle count
        $popularBrands = $brandModel
            ->select('brands.*, COUNT(vehicles.id) as vehicle_count')
            ->join('vehicles', 'vehicles.brand_id = brands.id AND vehicles.status = "published"', 'left')
            ->where('brands.status', 'published')
            ->groupBy('brands.id')
            ->orderBy('vehicle_count', 'DESC')
            ->findAll();

        // Latest articles — 4 most recently published
        $latestArticles = $articleModel
            ->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->limit(4)
            ->findAll();

        // Featured / active top-level categories
        $featuredCategories = $categoryModel->getTopLevel();

        // Site-wide stats
        $totalVehicles = $vehicleModel->where('status', 'published')->countAllResults();
        $totalBrands   = $brandModel->where('status', 'published')->countAllResults();

        $stats = [
            'total_vehicles' => $totalVehicles,
            'brand_count'    => $totalBrands,
            'city_count'     => '50+',
            'leads_served'   => '10,000+',
        ];

        // CarDekho-style category rankings — top 6 per segment
        $db = \Config\Database::connect();
        $rankedByCategory = [];
        $categoryGroups = [
            '2-wheeler' => ['label' => '2-Wheelers', 'icon' => '🛵', 'slugs' => ['electric-scooters', 'electric-bikes', 'electric-motorcycles']],
            '3-wheeler' => ['label' => '3-Wheelers', 'icon' => '🛺', 'slugs' => ['electric-rickshaws', 'electric-loaders', 'electric-three-wheelers']],
            '4-wheeler' => ['label' => '4-Wheelers', 'icon' => '🚗', 'slugs' => ['electric-cars', 'electric-suvs', 'electric-hatchbacks', 'electric-sedans']],
        ];
        foreach ($categoryGroups as $key => $group) {
            $slugPlaceholders = implode(',', array_fill(0, count($group['slugs']), '?'));
            $rows = $db->query("
                SELECT v.*, b.name as brand_name, b.logo as brand_logo, vc.name as category_name
                FROM vehicles v
                LEFT JOIN brands b ON b.id = v.brand_id
                LEFT JOIN vehicle_categories vc ON vc.id = v.category_id
                WHERE v.status = 'published'
                  AND (vc.slug IN ({$slugPlaceholders}) OR LOWER(vc.name) LIKE '%scooter%' OR LOWER(vc.name) LIKE '%bike%' OR LOWER(vc.name) LIKE '%car%' OR LOWER(vc.name) LIKE '%rickshaw%' OR LOWER(vc.name) LIKE '%loader%')
                ORDER BY v.expert_rating DESC, v.starting_price ASC
                LIMIT 6
            ", $group['slugs'])->getResultArray();
            // More accurate filter by actual category
            $filtered = $db->query("
                SELECT v.*, b.name as brand_name, b.logo as brand_logo, vc.name as category_name, vc.slug as cat_slug
                FROM vehicles v
                LEFT JOIN brands b ON b.id = v.brand_id
                LEFT JOIN vehicle_categories vc ON vc.id = v.category_id
                WHERE v.status = 'published'
                ORDER BY v.expert_rating DESC, v.starting_price ASC
            ")->getResultArray();
            $catMap = ['2-wheeler' => ['scooter','bike','motorcycle','moped'], '3-wheeler' => ['rickshaw','loader','three','3-wheel'], '4-wheeler' => ['car','suv','sedan','hatchback','mpv','crossover']];
            $keywords = $catMap[$key];
            $rankedByCategory[$key] = array_values(array_filter($filtered, function($v) use ($keywords) {
                $cat = strtolower($v['category_name'] ?? '');
                foreach ($keywords as $kw) { if (str_contains($cat, $kw)) return true; }
                return false;
            }));
            $rankedByCategory[$key] = array_slice($rankedByCategory[$key], 0, 6);
            $rankedByCategory[$key . '_meta'] = $group;
        }

        return $this->render('home/index', [
            'featuredVehicles'   => $featuredVehicles,
            'popularBrands'      => $popularBrands,
            'latestArticles'     => $latestArticles,
            'featuredCategories' => $featuredCategories,
            'stats'              => $stats,
            'rankedByCategory'   => $rankedByCategory,
            'transparentNav'     => false,
            'meta_title'         => 'Charj.in - India\'s EV Decision Engine | Compare Electric Vehicles',
            'meta_description'   => 'India\'s most complete EV platform. Compare electric vehicles, calculate savings, find dealers and charging stations across India.',
        ]);
    }
}
