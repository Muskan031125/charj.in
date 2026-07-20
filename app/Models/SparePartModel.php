<?php

namespace App\Models;

use CodeIgniter\Model;

class SparePartModel extends Model
{
    protected $table            = 'spare_parts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'part_name',
        'slug',
        'category',
        'part_number',
        'price',
        'compatible_models',
        'description',
        'image_url',
        'vendor_name',
        'vendor_contact',
        'vendor_url',
        'in_stock',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function published(): static
    {
        return $this->where('status', 'published');
    }

    public function findBySlug(string $slug): ?array
    {
        $result = $this->where('slug', $slug)->first();
        return $result ?: null;
    }

    public function getLatest(int $limit = 6): array
    {
        return $this->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function byCategory(string $category): array
    {
        return $this->where('status', 'published')
            ->where('category', $category)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
