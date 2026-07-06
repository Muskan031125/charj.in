<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'title',
        'slug',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'city',
        'venue_address',
        'organizer',
        'registration_url',
        'banner_image',
        'is_featured',
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

    public function upcoming(): static
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('status', 'published')
            ->where('start_date >=', $now)
            ->orderBy('start_date', 'ASC');
    }

    public function featured(): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('status', 'published')
            ->where('is_featured', 1)
            ->where('start_date >=', $now)
            ->orderBy('start_date', 'ASC')
            ->findAll(3);
    }
}
