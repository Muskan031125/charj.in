<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class DealerController extends BaseController
{
    public function index()
    {
        $db   = \Config\Database::connect();
        $city = trim((string) $this->request->getGet('city'));

        // Distinct cities that have at least one active dealer
        $citiesResult = $db->table('dealers')
            ->select('city')
            ->where('status', 'active')
            ->where('city IS NOT NULL', null, false)
            ->where('city !=', '')
            ->orderBy('city', 'ASC')
            ->groupBy('city')
            ->get()
            ->getResultArray();
        $cities = array_column($citiesResult, 'city');

        // Base query — dealers (brands_handled stored as JSON in dealers table)
        $query = $db->table('dealers')
            ->select('dealers.*')
            ->where('dealers.status', 'active')
            ->orderBy('dealers.name', 'ASC');

        if ($city !== '') {
            $query->where('dealers.city', $city);
        }

        $dealers = $query->get()->getResultArray();

        $metaTitle = $city
            ? 'EV Dealers in ' . $city . ' | Charj.in'
            : 'Electric Vehicle Dealers in India | Charj.in';
        $metaDesc  = $city
            ? 'Find authorised electric vehicle dealers in ' . $city . '. Compare brands, locations and get dealer quotes.'
            : 'Find authorised electric vehicle dealers across India. Browse by city and brand to get dealer quotes and test drive offers.';

        return $this->render('dealers/index', [
            'dealers'          => $dealers,
            'cities'           => $cities,
            'activeCity'       => $city,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }

    public function city(string $city)
    {
        // Canonical redirect to index with city query param
        return redirect()->to(site_url('ev-dealers') . '?city=' . urlencode($city));
    }

    public function show(string $slug)
    {
        $db = \Config\Database::connect();

        // Dealer with all associated brands
        $dealer = $db->table('dealers')
            ->where('dealers.slug', $slug)
            ->where('dealers.status', 'active')
            ->get()
            ->getRowArray();

        if (!$dealer) {
            throw PageNotFoundException::forPageNotFound('Dealer not found');
        }

        // Brands stocked by this dealer (brands_handled is a JSON array of brand names)
        $dealerBrands = [];
        if (!empty($dealer['brands_handled'])) {
            $brandNames = json_decode($dealer['brands_handled'], true) ?? [];
            if (!empty($brandNames)) {
                $dealerBrands = $db->table('brands')
                    ->whereIn('name', $brandNames)
                    ->where('status', 'published')
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->getResultArray();
            }
        }

        $brandIds = array_column($dealerBrands, 'id');

        // Vehicles available at this dealer (published vehicles of stocked brands)
        $dealerVehicles = [];
        if (!empty($brandIds)) {
            $dealerVehicles = (new \App\Models\VehicleModel())
                ->withBrandCategory()
                ->where('vehicles.status', 'published')
                ->whereIn('vehicles.brand_id', $brandIds)
                ->orderBy('vehicles.starting_price', 'ASC')
                ->findAll();
        }

        $metaTitle = ($dealer['name'] ?? 'EV Dealer') . ' - Authorised EV Dealer in ' . ($dealer['city'] ?? 'India') . ' | Charj.in';
        $metaDesc  = 'Visit ' . ($dealer['name'] ?? 'this dealer') . ' in ' . ($dealer['city'] ?? 'India')
            . ' for electric vehicle test drives, bookings and financing. Get dealer quote on Charj.in.';

        return $this->render('dealers/show', [
            'dealer'           => $dealer,
            'dealerBrands'     => $dealerBrands,
            'dealerVehicles'   => $dealerVehicles,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
