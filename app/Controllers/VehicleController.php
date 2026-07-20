<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class VehicleController extends BaseController
{
    public function index()
    {
        $vehicleModel  = new VehicleModel();
        $brandModel    = new BrandModel();
        $categoryModel = new CategoryModel();

        // Param names here must match app/Views/vehicles/index.php's filter form/chips exactly
        // (q, brand[], price_min, price_max, range_min) — a prior mismatch silently broke every
        // filter except category/sort, since getGet() on the wrong key just returns null.
        $brandParam = $this->request->getGet('brand');
        $brandSlugs = is_array($brandParam)
            ? array_values(array_filter(array_map('trim', $brandParam)))
            : (($brandParam !== null && $brandParam !== '') ? [trim($brandParam)] : []);
        $category = $this->request->getGet('category');
        $minPrice = $this->request->getGet('price_min');
        $maxPrice = $this->request->getGet('price_max');
        $minRange = $this->request->getGet('range_min');
        $sort     = $this->request->getGet('sort') ?? 'newest';
        $search   = $this->request->getGet('q');
        $savedRaw = $this->request->getGet('saved');

        // Saved EVs mode — filter by comma-separated slugs from client localStorage
        $savedSlugs  = [];
        $savedMode   = false;
        if ($savedRaw !== null && $savedRaw !== '') {
            $savedSlugs = array_values(array_unique(array_filter(array_map('trim', explode(',', $savedRaw)))));
            $savedMode  = !empty($savedSlugs);
        }

        $query = $vehicleModel->withBrandCategory()->where('vehicles.status', 'published');

        if ($savedMode) {
            $query->whereIn('vehicles.slug', $savedSlugs);
        } else {
            if (!empty($brandSlugs)) {
                $query->whereIn('brands.slug', $brandSlugs);
            }
            if ($category) {
                $query->where('vehicle_categories.slug', $category);
            }
            if ($minPrice !== null && $minPrice !== '') {
                $query->where('vehicles.starting_price >=', (int) $minPrice);
            }
            if ($maxPrice !== null && $maxPrice !== '') {
                $query->where('vehicles.starting_price <=', (int) $maxPrice);
            }
            if ($minRange !== null && $minRange !== '') {
                $query->where('vehicles.real_world_range >=', (int) $minRange);
            }
            if ($search) {
                $query->groupStart()
                    ->like('vehicles.name', $search)
                    ->orLike('brands.name', $search)
                    ->orLike('vehicles.short_description', $search)
                    ->groupEnd();
            }
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('vehicles.starting_price', 'ASC');
                break;
            case 'price_desc':
                $query->orderBy('vehicles.starting_price', 'DESC');
                break;
            case 'range_desc':
                $query->orderBy('vehicles.real_world_range', 'DESC');
                break;
            case 'rating_desc':
                $query->orderBy('vehicles.expert_rating', 'DESC');
                break;
            case 'newest':
            default:
                $query->orderBy('vehicles.created_at', 'DESC');
                break;
        }

        $vehicles   = $query->paginate(12, 'default');
        $brands     = $brandModel->where('status', 'published')->orderBy('name', 'ASC')->findAll();
        $categories = $categoryModel->where('status', 'published')->orderBy('name', 'ASC')->findAll();

        return $this->render('vehicles/index', [
            'vehicles'    => $vehicles,
            'pager'       => $vehicleModel->pager,
            'brands'      => $brands,
            'categories'  => $categories,
            'savedMode'   => $savedMode,
            'title'       => $savedMode ? 'Your Saved EVs' : 'All EVs in India',
            'subtitle'    => $savedMode
                ? 'Electric vehicles you\'ve saved — compare, shortlist & decide'
                : 'Compare prices, range & features across all electric vehicles',
            'activeFilters' => compact('brandSlugs', 'category', 'minPrice', 'maxPrice', 'minRange', 'sort', 'search'),
            'meta_title'      => $savedMode
                ? 'Your Saved EVs | Charj.in'
                : 'All Electric Vehicles in India - Prices, Range & Specs | Charj.in',
            'meta_description' => 'Browse and compare all electric vehicles available in India. Filter by brand, price, range and category to find your perfect EV.',
        ]);
    }

    public function category(string $slug)
    {
        $categoryModel = new CategoryModel();
        $vehicleModel  = new VehicleModel();
        $brandModel    = new BrandModel();

        $category = $categoryModel->where('slug', $slug)->where('status', 'published')->first();
        if (!$category) {
            throw PageNotFoundException::forPageNotFound('Category not found');
        }

        // Param names must match app/Views/vehicles/index.php's filter form/chips — see index() above.
        $brandParam = $this->request->getGet('brand');
        $brandSlugs = is_array($brandParam)
            ? array_values(array_filter(array_map('trim', $brandParam)))
            : (($brandParam !== null && $brandParam !== '') ? [trim($brandParam)] : []);
        $minPrice = $this->request->getGet('price_min');
        $maxPrice = $this->request->getGet('price_max');
        $minRange = $this->request->getGet('range_min');
        $sort     = $this->request->getGet('sort') ?? 'newest';
        $search   = $this->request->getGet('q');

        $query = $vehicleModel
            ->withBrandCategory()
            ->where('vehicles.status', 'published')
            ->where('vehicles.category_id', $category['id']);

        if (!empty($brandSlugs)) {
            $query->whereIn('brands.slug', $brandSlugs);
        }
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('vehicles.starting_price >=', (int) $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('vehicles.starting_price <=', (int) $maxPrice);
        }
        if ($minRange !== null && $minRange !== '') {
            $query->where('vehicles.real_world_range >=', (int) $minRange);
        }
        if ($search) {
            $query->groupStart()
                ->like('vehicles.name', $search)
                ->orLike('brands.name', $search)
                ->groupEnd();
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('vehicles.starting_price', 'ASC');
                break;
            case 'price_desc':
                $query->orderBy('vehicles.starting_price', 'DESC');
                break;
            case 'range_desc':
                $query->orderBy('vehicles.real_world_range', 'DESC');
                break;
            case 'rating_desc':
                $query->orderBy('vehicles.expert_rating', 'DESC');
                break;
            case 'newest':
            default:
                $query->orderBy('vehicles.created_at', 'DESC');
                break;
        }

        $vehicles = $query->paginate(12, 'default');
        $brands   = $brandModel->where('status', 'published')->orderBy('name', 'ASC')->findAll();

        $metaTitle = ($category['seo_title'] ?? $category['name']) . ' in India - Price, Range & Specs | Charj.in';
        $metaDesc  = $category['seo_description']
            ?? 'Compare all ' . $category['name'] . ' electric vehicles in India. Find prices, real-world range, battery specs, EMI and dealer offers.';

        return $this->render('vehicles/index', [
            'vehicles'     => $vehicles,
            'pager'        => $vehicleModel->pager,
            'category'     => $category,
            'brands'       => $brands,
            'categories'   => [],
            'activeFilters' => compact('brandSlugs', 'minPrice', 'maxPrice', 'minRange', 'sort', 'search'),
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }

    public function show(string $slug)
    {
        $vehicleModel = new VehicleModel();
        $vehicle      = $vehicleModel->findBySlug($slug);

        if (!$vehicle || $vehicle['status'] !== 'published') {
            throw PageNotFoundException::forPageNotFound('Vehicle not found');
        }

        $db = \Config\Database::connect();

        // Track view for logged-in users
        if (session()->get('user_logged_in') && $db->tableExists('user_activity')) {
            $db->table('user_activity')->insert([
                'user_id'     => session()->get('user_id'),
                'action'      => 'view_vehicle',
                'entity_id'   => $vehicle['id'],
                'entity_name' => ($vehicle['brand_name'] ?? '') . ' ' . $vehicle['name'],
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        // Reviews — published, latest 5
        $reviews = $db->table('reviews')
            ->where('vehicle_id', $vehicle['id'])
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Similar vehicles â€” same category, exclude current, best rated first, limit 4
        $similarVehicles = $vehicleModel
            ->withBrandCategory()
            ->where('vehicles.status', 'published')
            ->where('vehicles.category_id', $vehicle['category_id'])
            ->where('vehicles.id !=', $vehicle['id'])
            ->orderBy('vehicles.expert_rating', 'DESC')
            ->limit(4)
            ->findAll();

        // Dealers — just get dealers in same city or all, limit 6
        $dealers = $db->table('dealers')
            ->select('dealers.*')
            ->where('dealers.status', 'active')
            ->limit(6)
            ->get()
            ->getResultArray();

        // FAQs for this vehicle
        $faqs = $db->table('faq')
            ->where('vehicle_id', $vehicle['id'])
            ->orderBy('display_order', 'ASC')
            ->get()
            ->getResultArray();

        // City-level on-road pricing
        $cityPricing = $db->table('city_pricing')
            ->where('vehicle_id', $vehicle['id'])
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();

        // All photos for this vehicle (main + gallery + interior + color), ordered for display
        $vehicleImages = $db->table('vehicle_images')
            ->where('vehicle_id', $vehicle['id'])
            ->orderBy('display_order', 'ASC')
            ->get()
            ->getResultArray();

        // Owner Q&A — approved questions ordered by votes
        $ownerQuestions = [];
        try {
            $ownerQuestions = $db->table('owner_questions')
                ->where('vehicle_id', $vehicle['id'])
                ->where('is_approved', 1)
                ->orderBy('votes', 'DESC')
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            // Table may not exist yet — silently fall back to empty array
            $ownerQuestions = [];
        }

        // EMI calculation: 9% per annum, 36 months on ex-showroom price
        $principal    = (float) ($vehicle['starting_price'] ?? 0);
        $annualRate   = 9.0;
        $monthlyRate  = $annualRate / 12 / 100;
        $tenureMonths = 36;
        $emi = 0;
        if ($principal > 0 && $monthlyRate > 0) {
            $emi = ($principal * $monthlyRate * pow(1 + $monthlyRate, $tenureMonths))
                 / (pow(1 + $monthlyRate, $tenureMonths) - 1);
        }

        $metaTitle = $vehicle['name'] . ' Price, Range, Specs & Review - Charj.in';
        $metaDesc  = $vehicle['short_description']
            ?: $vehicle['name'] . ' price in India starts at â‚¹' . number_format((int) $vehicle['starting_price'])
               . '. Check real-world range, battery capacity, charging time, EMI options and dealer offers.';

        return $this->render('vehicles/show', [
            'vehicle'         => $vehicle,
            'vehicleImages'   => $vehicleImages,
            'reviews'         => $reviews,
            'similarVehicles' => $similarVehicles,
            'ownerQuestions'  => $ownerQuestions,
            'dealers'         => $dealers,
            'faqs'            => $faqs,
            'cityPricing'     => $cityPricing,
            'emi'             => (int) round($emi),
            'emiRate'         => $annualRate,
            'emiTenure'       => $tenureMonths,
            'meta_title'      => $metaTitle,
            'meta_description' => $metaDesc,
            'meta_keywords'   => $vehicle['name'] . ' price, ' . $vehicle['name'] . ' range, ' . ($vehicle['brand_name'] ?? '') . ' EV India',
        ]);
    }

    public function submitQuestion(string $slug)
    {
        $vehicle = (new VehicleModel())->where('slug', $slug)->first();
        if (!$vehicle) {
            return redirect()->to('/vehicles');
        }

        $name     = $this->request->getPost('name');
        $question = $this->request->getPost('question');
        if (!$name || !$question) {
            return redirect()->back()->with('error', 'Name and question are required.');
        }

        if ($this->db->tableExists('owner_questions')) {
            $this->db->table('owner_questions')->insert([
                'vehicle_id'  => $vehicle['id'],
                'name'        => htmlspecialchars($name),
                'question'    => htmlspecialchars($question),
                'is_approved' => 0,
                'votes'       => 0,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->back()->with('success', 'Your question has been submitted and will appear after moderation.');
    }

    public function apiSearch()
    {
        $q = trim((string) $this->request->getGet('q'));
        if (strlen($q) < 1) {
            return $this->jsonResponse([]);
        }

        $vehicleModel = new VehicleModel();
        $results = $vehicleModel
            ->select('vehicles.id, vehicles.name, vehicles.slug, vehicles.starting_price, vehicles.image_url, brands.name as brand_name')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->where('vehicles.status', 'published')
            ->groupStart()
                ->like('vehicles.name', $q)
                ->orLike('brands.name', $q)
            ->groupEnd()
            ->orderBy('vehicles.name', 'ASC')
            ->limit(10)
            ->findAll();

        return $this->jsonResponse($results);
    }
}
