<?php

namespace App\Models;

use CodeIgniter\Model;

class DealerModel extends Model
{
    protected $table            = 'dealers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'brand_id',
        'name',
        'slug',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'google_maps_url',
        'website',
        'brands_handled',
        'status',
        'verified',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // byCity – case-insensitive city filter
    // -------------------------------------------------------------------------

    public function byCity(string $city): static
    {
        return $this->where('LOWER(city)', strtolower($city), false);
    }

    // -------------------------------------------------------------------------
    // byBrand – filter by brand_id
    // -------------------------------------------------------------------------

    public function byBrand(int $brandId): static
    {
        return $this->where('brand_id', $brandId);
    }

    // -------------------------------------------------------------------------
    // verified – only verified active dealers
    // -------------------------------------------------------------------------

    public function verified(): static
    {
        return $this->where('verified', 1)->where('status', 'active');
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
    // getWithBrand – LEFT JOIN brands
    // -------------------------------------------------------------------------

    public function getWithBrand(): array
    {
        return $this->db->table('dealers d')
            ->select('d.*, b.name AS brand_name, b.slug AS brand_slug, b.logo_url AS brand_logo')
            ->join('brands b', 'b.id = d.brand_id', 'left')
            ->orderBy('d.name', 'ASC')
            ->get()
            ->getResultArray();
    }
}
