<?php
namespace App\Controllers;

class ExploreController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $brands = $db->query("
            SELECT b.*, COUNT(v.id) as ev_count,
                   GROUP_CONCAT(DISTINCT
                       CASE
                           WHEN vc.slug LIKE '%scooter%' OR vc.slug LIKE '%bike%' OR vc.slug LIKE '%cycle%'
                             OR vc.slug LIKE '%2-wheel%' OR vc.slug LIKE '%two-wheel%'
                             OR vc.name LIKE '%scooter%' OR vc.name LIKE '%bike%'
                             OR vc.name LIKE '2 %' OR vc.name LIKE '%2-wheel%' THEN '2-wheeler'
                           WHEN vc.slug LIKE '%rickshaw%' OR vc.slug LIKE '%3-wheel%' OR vc.slug LIKE '%three%'
                             OR vc.name LIKE '%rickshaw%' OR vc.name LIKE '%3-wheel%' OR vc.name LIKE '3 %' THEN '3-wheeler'
                           WHEN vc.slug LIKE '%car%' OR vc.slug LIKE '%4-wheel%' OR vc.slug LIKE '%four%'
                             OR vc.name LIKE '%car%' OR vc.name LIKE '%4-wheel%' OR vc.name LIKE '4 %' THEN '4-wheeler'
                           WHEN vc.slug LIKE '%bus%' OR vc.slug LIKE '%truck%' OR vc.slug LIKE '%loader%'
                             OR vc.slug LIKE '%commercial%'
                             OR vc.name LIKE '%bus%' OR vc.name LIKE '%truck%' OR vc.name LIKE '%commercial%' THEN 'commercial'
                       END
                   ) as filter_types
            FROM brands b
            LEFT JOIN vehicles v ON v.brand_id = b.id AND v.status = 'published'
            LEFT JOIN vehicle_categories vc ON vc.id = v.category_id
            WHERE b.status IN ('active', 'published')
            GROUP BY b.id
            ORDER BY b.featured DESC, ev_count DESC, b.name ASC
        ")->getResultArray();

        return view('explore/index', [
            'brands'     => $brands,
            'meta_title' => 'Explore EVs by Brand — Charj.in',
        ]);
    }
}
