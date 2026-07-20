<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table            = 'vehicles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'starting_price',
        'max_price',
        'ex_showroom_price',
        'on_road_price_min',
        'on_road_price_max',
        'battery_capacity',
        'battery_type',
        'real_world_range',
        'claimed_range',
        'top_speed',
        'acceleration_0_100',
        'charging_time',
        'charging_time_normal',
        'charging_time_fast',
        'fast_charging',
        'fast_charging_supported',
        'fast_charging_type',
        'motor_power',
        'motor_torque',
        'warranty',
        'warranty_years',
        'warranty_km',
        'battery_warranty_years',
        'battery_warranty_km',
        'seating_capacity',
        'load_capacity',
        'boot_space',
        'ground_clearance',
        'kerb_weight',
        'colors_available',
        'colors_json',
        'features_json',
        'specs_json',
        'pros_json',
        'cons_json',
        'best_for',
        'segment',
        'body_type',
        'drive_type',
        'image_url',
        'gallery_json',
        'brochure_url',
        'video_url',
        'expert_rating',
        'user_rating',
        'expert_review',
        'status',
        'featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // Base builder with brand + category JOINs (returns raw DB builder)
    // -------------------------------------------------------------------------

    public function withBrandCategory(): static
    {
        return $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug, vehicle_categories.icon AS category_icon')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left');
    }

    // -------------------------------------------------------------------------
    // findBySlug with brand + category
    // -------------------------------------------------------------------------

    public function findBySlug(string $slug): ?array
    {
        $result = $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug, vehicle_categories.icon AS category_icon')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->where('vehicles.slug', $slug)
            ->first();

        return $result ?: null;
    }

    // -------------------------------------------------------------------------
    // published() – chainable on the model builder
    // -------------------------------------------------------------------------

    public function published(): static
    {
        return $this->where('vehicles.status', 'published');
    }

    // -------------------------------------------------------------------------
    // featured()
    // -------------------------------------------------------------------------

    public function featured(): static
    {
        return $this->where('vehicles.featured', 1)->where('vehicles.status', 'published');
    }

    // -------------------------------------------------------------------------
    // byCategory – filter by category slug
    // -------------------------------------------------------------------------

    public function byCategory(string $slug): array
    {
        return $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug, vehicle_categories.icon AS category_icon')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->where('vehicle_categories.slug', $slug)
            ->where('vehicles.status', 'published')
            ->orderBy('vehicles.sort_order', 'ASC')
            ->orderBy('vehicles.expert_rating', 'DESC')
            ->findAll();
    }

    // -------------------------------------------------------------------------
    // byBrand – filter by brand slug
    // -------------------------------------------------------------------------

    public function byBrand(string $slug): array
    {
        return $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug, vehicle_categories.icon AS category_icon')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->where('brands.slug', $slug)
            ->where('vehicles.status', 'published')
            ->orderBy('vehicles.sort_order', 'ASC')
            ->orderBy('vehicles.expert_rating', 'DESC')
            ->findAll();
    }

    // -------------------------------------------------------------------------
    // search – LIKE across name, brand name, short_description
    // -------------------------------------------------------------------------

    public function search(string $query): array
    {
        return $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->where('vehicles.status', 'published')
            ->groupStart()
                ->like('vehicles.name', $query)
                ->orLike('brands.name', $query)
                ->orLike('vehicles.short_description', $query)
            ->groupEnd()
            ->orderBy('vehicles.expert_rating', 'DESC')
            ->findAll();
    }

    // -------------------------------------------------------------------------
    // getForCompare – multiple vehicles by IDs with brand + category
    // -------------------------------------------------------------------------

    public function getForCompare(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug, vehicle_categories.icon AS category_icon')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->whereIn('vehicles.id', $ids)
            ->findAll();
    }

    // -------------------------------------------------------------------------
    // getSimilar – same category, exclude current vehicle
    // -------------------------------------------------------------------------

    public function getSimilar(int $vehicleId, int $categoryId, int $limit = 4): array
    {
        return $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->where('vehicles.category_id', $categoryId)
            ->where('vehicles.id !=', $vehicleId)
            ->where('vehicles.status', 'published')
            ->orderBy('vehicles.expert_rating', 'DESC')
            ->findAll($limit);
    }

    // -------------------------------------------------------------------------
    // forRecommendation – complex filter for recommendation engine
    // -------------------------------------------------------------------------

    public function forRecommendation(array $filters): array
    {
        $this->select('vehicles.*, brands.name AS brand_name, brands.slug AS brand_slug, brands.logo AS brand_logo, vehicle_categories.name AS category_name, vehicle_categories.slug AS category_slug, vehicle_categories.icon AS category_icon')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->join('vehicle_categories', 'vehicle_categories.id = vehicles.category_id', 'left')
            ->where('vehicles.status', 'published');

        // Budget filter – include up to 110% of budget so scorer can still penalise
        if (!empty($filters['max_budget']) && (float) $filters['max_budget'] > 0) {
            $upperBound = (float) $filters['max_budget'] * 1.10;
            $this->where('vehicles.starting_price <=', $upperBound);
        }

        // Category IDs filter
        if (!empty($filters['category_ids']) && is_array($filters['category_ids'])) {
            $this->whereIn('vehicles.category_id', $filters['category_ids']);
        }

        // Minimum range – be generous; scorer refines
        if (!empty($filters['min_range']) && (int) $filters['min_range'] > 0) {
            $this->where('vehicles.real_world_range >=', (int) $filters['min_range'] * 0.7);
        }

        // best_for values (OR match across the JSON/text field)
        if (!empty($filters['best_for']) && is_array($filters['best_for'])) {
            $this->groupStart();
            foreach ($filters['best_for'] as $idx => $val) {
                if ($idx === 0) {
                    $this->like('vehicles.best_for', $val);
                } else {
                    $this->orLike('vehicles.best_for', $val);
                }
            }
            $this->groupEnd();
        }

        return $this->orderBy('vehicles.expert_rating', 'DESC')
            ->findAll(10);
    }
}
