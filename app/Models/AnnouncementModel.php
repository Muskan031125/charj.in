<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table            = 'announcements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'type',
        'is_pinned',
        'banner_image',
        'link_url',
        'status',
        'published_at',
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
            ->orderBy('published_at', 'DESC')
            ->findAll($limit);
    }

    public function pinnedFirst(): array
    {
        return $this->where('status', 'published')
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->findAll();
    }
}
