<?php

namespace App\Models;

use CodeIgniter\Model;

class BrandModel extends Model
{
    protected $table            = 'brands';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'name',
        'slug',
        'logo',
        'logo_url',
        'description',
        'short_description',
        'website',
        'founded_year',
        'headquarters',
        'country',
        'status',
        'featured',
        'seo_title',
        'seo_description',
        'meta_keywords',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // getWithVehicleCount – LEFT JOIN vehicles, count published vehicles per brand
    // -------------------------------------------------------------------------

    public function getWithVehicleCount(): array
    {
        return $this->db->table('brands')
            ->select('brands.*, COUNT(v.id) AS vehicle_count')
            ->join('vehicles v', "v.brand_id = brands.id AND v.status = 'published'", 'left')
            ->where('brands.status', 'published')
            ->groupBy('brands.id')
            ->orderBy('brands.sort_order', 'ASC')
            ->orderBy('brands.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // published() – chainable
    // -------------------------------------------------------------------------

    public function published(): static
    {
        return $this->where('brands.status', 'published');
    }

    // -------------------------------------------------------------------------
    // findBySlug
    // -------------------------------------------------------------------------

    public function findBySlug(string $slug): ?array
    {
        $result = $this->where('slug', $slug)->first();
        return $result ?: null;
    }
}
