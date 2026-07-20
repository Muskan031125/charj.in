<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'vehicle_id',
        'reviewer_name',
        'reviewer_email',
        'reviewer_city',
        'rating',
        'rating_range',
        'rating_comfort',
        'rating_features',
        'rating_value',
        'rating_service',
        'title',
        'review_text',
        'pros',
        'cons',
        'usage_months',
        'km_driven',
        'ownership_type',
        'verified_purchase',
        'helpful_votes',
        'status',
        'admin_notes',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // published() – chainable
    // -------------------------------------------------------------------------

    public function published(): static
    {
        return $this->where('reviews.status', 'published');
    }

    // -------------------------------------------------------------------------
    // forVehicle – published reviews for a specific vehicle
    // -------------------------------------------------------------------------

    public function forVehicle(int $vehicleId): array
    {
        return $this->where('vehicle_id', $vehicleId)
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // -------------------------------------------------------------------------
    // getAverageRating – AVG(rating) for a vehicle (published reviews only)
    // -------------------------------------------------------------------------

    public function getAverageRating(int $vehicleId): float
    {
        $result = $this->db->table('reviews')
            ->select('AVG(rating) AS avg_rating, COUNT(*) AS total')
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'published')
            ->get()
            ->getRowArray();

        return round((float) ($result['avg_rating'] ?? 0), 1);
    }

    // -------------------------------------------------------------------------
    // withVehicle – LEFT JOIN vehicles
    // -------------------------------------------------------------------------

    public function withVehicle(): array
    {
        return $this->db->table('reviews r')
            ->select('r.*, v.name AS vehicle_name, v.slug AS vehicle_slug, v.image_url AS vehicle_image, b.name AS brand_name')
            ->join('vehicles v', 'v.id = r.vehicle_id', 'left')
            ->join('brands b', 'b.id = v.brand_id', 'left')
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
