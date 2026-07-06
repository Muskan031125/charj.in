<?php

namespace App\Controllers;

use App\Models\BrandModel;
use App\Models\VehicleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BrandController extends BaseController
{
    public function index()
    {
        $brandModel = new BrandModel();

        $brands = $brandModel
            ->select('brands.*, COUNT(vehicles.id) as vehicle_count')
            ->join('vehicles', 'vehicles.brand_id = brands.id AND vehicles.status = "published"', 'left')
            ->where('brands.status', 'published')
            ->groupBy('brands.id')
            ->orderBy('brands.name', 'ASC')
            ->findAll();

        return $this->render('brands/index', [
            'brands'           => $brands,
            'meta_title'       => 'Electric Vehicle Brands in India | Charj.in',
            'meta_description' => 'Explore all electric vehicle brands available in India. Browse EVs from Tata, Ather, Ola, Hero, TVS, Bajaj and more.',
        ]);
    }

    public function show(string $slug)
    {
        $brandModel   = new BrandModel();
        $vehicleModel = new VehicleModel();

        $brand = $brandModel->where('slug', $slug)->where('status', 'published')->first();
        if (!$brand) {
            throw PageNotFoundException::forPageNotFound('Brand not found');
        }

        $vehicles = $vehicleModel
            ->withBrandCategory()
            ->where('vehicles.status', 'published')
            ->where('vehicles.brand_id', $brand['id'])
            ->orderBy('vehicles.starting_price', 'ASC')
            ->paginate(12, 'default');

        $metaTitle = ($brand['seo_title'] ?? $brand['name'] . ' Electric Vehicles') . ' in India | Charj.in';
        $metaDesc  = $brand['seo_description']
            ?? 'Explore all ' . $brand['name'] . ' electric vehicles in India. Compare prices, range, battery specs and find dealers near you.';

        return $this->render('brands/show', [
            'brand'            => $brand,
            'vehicles'         => $vehicles,
            'pager'            => $vehicleModel->pager,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
