<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CompareController extends BaseController
{
    public function index()
    {
        $vehicleModel = new VehicleModel();

        // Accept comma-separated slugs or IDs
        $param    = trim((string) $this->request->getGet('vehicles'));
        $vehicles = [];

        if ($param !== '') {
            $tokens = array_filter(array_map('trim', explode(',', $param)));
            if (!empty($tokens)) {
                // Detect whether tokens look like IDs (all numeric) or slugs
                $allNumeric = array_reduce($tokens, fn($carry, $t) => $carry && ctype_digit($t), true);

                $query = $vehicleModel
                    ->withBrandCategory()
                    ->where('vehicles.status', 'published');

                if ($allNumeric) {
                    $query->whereIn('vehicles.id', $tokens);
                } else {
                    $query->whereIn('vehicles.slug', $tokens);
                }

                $vehicles = array_slice($query->findAll(), 0, 3);
            }
        }

        // All published vehicles for the selector dropdown (id, name, brand, slug only)
        $allVehicles = $vehicleModel
            ->select('vehicles.id, vehicles.name, vehicles.slug, vehicles.image_url, brands.name as brand_name')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->where('vehicles.status', 'published')
            ->orderBy('brands.name', 'ASC')
            ->orderBy('vehicles.name', 'ASC')
            ->findAll();

        return $this->render('compare/index', [
            'vehicles'         => $vehicles,
            'allVehicles'      => $allVehicles,
            'meta_title'       => 'Compare Electric Vehicles in India | Charj.in',
            'meta_description' => 'Compare electric vehicles side-by-side on price, range, battery, charging time, motor power and more. Make the right EV buying decision.',
        ]);
    }

    public function versus(string $slug1, string $slug2)
    {
        $vehicleModel = new VehicleModel();

        $vehicle1 = $vehicleModel->findBySlug($slug1);
        $vehicle2 = $vehicleModel->findBySlug($slug2);

        if (!$vehicle1 || $vehicle1['status'] !== 'published') {
            throw PageNotFoundException::forPageNotFound('First vehicle not found: ' . $slug1);
        }
        if (!$vehicle2 || $vehicle2['status'] !== 'published') {
            throw PageNotFoundException::forPageNotFound('Second vehicle not found: ' . $slug2);
        }

        $name1 = trim(($vehicle1['brand_name'] ?? '') . ' ' . $vehicle1['name']);
        $name2 = trim(($vehicle2['brand_name'] ?? '') . ' ' . $vehicle2['name']);

        $allVehicles = $vehicleModel
            ->select('vehicles.id, vehicles.name, vehicles.slug, vehicles.image_url, brands.name as brand_name')
            ->join('brands', 'brands.id = vehicles.brand_id', 'left')
            ->where('vehicles.status', 'published')
            ->orderBy('brands.name', 'ASC')
            ->orderBy('vehicles.name', 'ASC')
            ->findAll();

        return $this->render('compare/index', [
            'vehicles'         => [$vehicle1, $vehicle2],
            'allVehicles'      => $allVehicles,
            'meta_title'       => $name1 . ' vs ' . $name2 . ' - Comparison | Charj.in',
            'meta_description' => 'Compare ' . $name1 . ' vs ' . $name2 . ' on price, range, battery, charging and specs. Find out which EV is better for you.',
        ]);
    }
}
