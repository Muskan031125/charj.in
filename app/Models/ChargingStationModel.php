<?php

namespace App\Models;

use CodeIgniter\Model;

class ChargingStationModel extends Model
{
    protected $table            = 'charging_stations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'name',
        'slug',
        'operator',
        'operator_id',
        'address',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'google_maps_url',
        'charger_types',
        'charger_types_json',
        'total_points',
        'available_points',
        'power_output_kw',
        'connector_types',
        'amenities_json',
        'timing',
        'open_24x7',
        'phone',
        'website',
        'price_per_unit',
        'pricing_info',
        'network',
        'status',
        'verified',
        'image_url',
        'meta_title',
        'meta_description',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // operational() – only stations with status='operational' (chainable)
    // -------------------------------------------------------------------------

    public function operational(): static
    {
        return $this->where('status', 'operational');
    }

    // -------------------------------------------------------------------------
    // byCity – case-insensitive city filter (chainable)
    // -------------------------------------------------------------------------

    public function byCity(string $city): static
    {
        return $this->where('LOWER(city)', strtolower($city), false);
    }

    // -------------------------------------------------------------------------
    // getCities – distinct city list (operational stations only)
    // -------------------------------------------------------------------------

    public function getCities(): array
    {
        $rows = $this->db->table('charging_stations')
            ->select('DISTINCT city')
            ->where('status', 'operational')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();

        return array_column($rows, 'city');
    }

    // -------------------------------------------------------------------------
    // nearby – Haversine formula to find stations within radius_km kilometres
    // Returns rows sorted by distance ascending, with a `distance_km` column.
    // -------------------------------------------------------------------------

    public function nearby(float $lat, float $lng, int $radius_km = 10): array
    {
        // Haversine formula expressed as a MySQL expression
        $haversine = "(
            6371 * ACOS(
                COS(RADIANS({$lat})) *
                COS(RADIANS(latitude)) *
                COS(RADIANS(longitude) - RADIANS({$lng})) +
                SIN(RADIANS({$lat})) *
                SIN(RADIANS(latitude))
            )
        )";

        return $this->db->table('charging_stations')
            ->select("charging_stations.*, {$haversine} AS distance_km")
            ->where('status', 'operational')
            ->where('latitude IS NOT NULL', null, false)
            ->where('longitude IS NOT NULL', null, false)
            ->having("distance_km <=", $radius_km)
            ->orderBy('distance_km', 'ASC')
            ->get()
            ->getResultArray();
    }
}
