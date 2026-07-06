<?php

namespace App\Controllers;

use App\Models\SparePartModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class SparePartController extends BaseController
{
    public function index()
    {
        $sparePartModel = new SparePartModel();
        $category = $this->request->getGet('category');

        $query = $sparePartModel->where('status', 'published');

        if (!empty($category)) {
            $query = $query->where('category', $category);
        }

        $spareParts = $query->orderBy('created_at', 'DESC')->paginate(12, 'default');

        $categories = [
            'battery'     => 'Battery',
            'motor'       => 'Motor',
            'charger'     => 'Charger',
            'tyre'        => 'Tyres',
            'brake'       => 'Brakes',
            'body'        => 'Body Parts',
            'electrical'  => 'Electrical',
            'other'       => 'Other',
        ];

        return $this->render('spare-parts/index', [
            'spareParts'  => $spareParts,
            'pager'       => $sparePartModel->pager,
            'categories'  => $categories,
            'selectedCategory' => $category,
            'meta_title'       => 'EV Spare Parts & Accessories | Charj.in',
            'meta_description' => 'Browse quality spare parts and accessories for electric vehicles. Find batteries, motors, chargers, and more from trusted vendors.',
        ]);
    }

    public function show(string $slug)
    {
        $sparePartModel = new SparePartModel();
        $sparePart = $sparePartModel->where('slug', $slug)->where('status', 'published')->first();

        if (!$sparePart) {
            throw PageNotFoundException::forPageNotFound('Spare part not found');
        }

        // Related parts (same category)
        $relatedParts = [];
        if (!empty($sparePart['category'])) {
            $relatedParts = $sparePartModel
                ->where('status', 'published')
                ->where('category', $sparePart['category'])
                ->where('id !=', $sparePart['id'])
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->findAll();
        }

        $metaTitle = $sparePart['part_name'] . ' | Charj.in';
        $metaDesc  = substr(strip_tags($sparePart['description'] ?? ''), 0, 155);

        return $this->render('spare-parts/show', [
            'sparePart'     => $sparePart,
            'relatedParts'  => $relatedParts,
            'meta_title'    => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
