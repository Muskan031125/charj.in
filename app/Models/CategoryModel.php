<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'vehicle_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'name',
        'slug',
        'parent_id',
        'description',
        'short_description',
        'icon',
        'image_url',
        'status',
        'seo_title',
        'seo_description',
        'meta_keywords',
        'display_order',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // getWithVehicleCount – LEFT JOIN vehicles, count published vehicles per category
    // -------------------------------------------------------------------------

    public function getWithVehicleCount(): array
    {
        return $this->db->table('vehicle_categories vc')
            ->select('vc.*, COUNT(v.id) AS vehicle_count')
            ->join('vehicles v', "v.category_id = vc.id AND v.status = 'published'", 'left')
            ->where('vc.status', 'published')
            ->groupBy('vc.id')
            ->orderBy('vc.display_order', 'ASC')
            ->orderBy('vc.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // active() – published categories (chainable)
    // -------------------------------------------------------------------------

    public function active(): static
    {
        return $this->where('vehicle_categories.status', 'published');
    }

    // -------------------------------------------------------------------------
    // findBySlug
    // -------------------------------------------------------------------------

    public function findBySlug(string $slug): ?array
    {
        $result = $this->where('slug', $slug)->first();
        return $result ?: null;
    }

    // -------------------------------------------------------------------------
    // getTopLevel – top-level categories (parent_id IS NULL), ordered by display_order
    // -------------------------------------------------------------------------

    public function getTopLevel(): array
    {
        return $this->where('parent_id IS NULL', null, false)
            ->where('status', 'published')
            ->orderBy('display_order', 'ASC')
            ->findAll();
    }
}
