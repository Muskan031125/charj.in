<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadModel extends Model
{
    protected $table            = 'leads';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'lead_type',
        'name',
        'email',
        'mobile',
        'city',
        'state',
        'pincode',
        'vehicle_id',
        'category_id',
        'brand_id',
        'dealer_id',
        'partner_id',
        'source_page',
        'source_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'message',
        'notes',
        'budget',
        'purchase_timeline',
        'finance_required',
        'charging_required',
        'use_case',
        'trade_in',
        'ip_address',
        'user_agent',
        'status',
        'assigned_to',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // getWithVehicle – LEFT JOIN vehicles to pull vehicle name and slug
    // -------------------------------------------------------------------------

    public function getWithVehicle(): array
    {
        return $this->db->table('leads')
            ->select('leads.*, vehicles.name AS vehicle_name, vehicles.slug AS vehicle_slug, vehicles.image_url AS vehicle_image')
            ->join('vehicles', 'vehicles.id = leads.vehicle_id', 'left')
            ->orderBy('leads.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // getNewLeads – status='new', most recent first
    // -------------------------------------------------------------------------

    public function getNewLeads(): array
    {
        return $this->db->table('leads')
            ->select('leads.*, vehicles.name AS vehicle_name, vehicles.slug AS vehicle_slug')
            ->join('vehicles', 'vehicles.id = leads.vehicle_id', 'left')
            ->where('leads.status', 'new')
            ->orderBy('leads.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // getStatsByType – COUNT per lead_type
    // -------------------------------------------------------------------------

    public function getStatsByType(): array
    {
        return $this->db->table('leads')
            ->select('lead_type, COUNT(*) AS total')
            ->groupBy('lead_type')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // getStatsByStatus – COUNT per status
    // -------------------------------------------------------------------------

    public function getStatsByStatus(): array
    {
        return $this->db->table('leads')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // getTodayCount – leads created today (IST = UTC+5:30)
    // -------------------------------------------------------------------------

    public function getTodayCount(): int
    {
        // Use MySQL DATE() with CONVERT_TZ to handle IST
        $result = $this->db->table('leads')
            ->select('COUNT(*) AS cnt')
            ->where("DATE(CONVERT_TZ(created_at, '+00:00', '+05:30')) = CURDATE()", null, false)
            ->get()
            ->getRowArray();

        return (int) ($result['cnt'] ?? 0);
    }

    // -------------------------------------------------------------------------
    // getThisWeekCount – leads created in the current ISO week (IST)
    // -------------------------------------------------------------------------

    public function getThisWeekCount(): int
    {
        $result = $this->db->table('leads')
            ->select('COUNT(*) AS cnt')
            ->where("YEARWEEK(CONVERT_TZ(created_at, '+00:00', '+05:30'), 1) = YEARWEEK(CONVERT_TZ(NOW(), '+00:00', '+05:30'), 1)", null, false)
            ->get()
            ->getRowArray();

        return (int) ($result['cnt'] ?? 0);
    }

    // -------------------------------------------------------------------------
    // getMonthCount – leads created in the current calendar month (IST)
    // -------------------------------------------------------------------------

    public function getMonthCount(): int
    {
        $result = $this->db->table('leads')
            ->select('COUNT(*) AS cnt')
            ->where("MONTH(CONVERT_TZ(created_at, '+00:00', '+05:30')) = MONTH(CONVERT_TZ(NOW(), '+00:00', '+05:30'))", null, false)
            ->where("YEAR(CONVERT_TZ(created_at, '+00:00', '+05:30')) = YEAR(CONVERT_TZ(NOW(), '+00:00', '+05:30'))", null, false)
            ->get()
            ->getRowArray();

        return (int) ($result['cnt'] ?? 0);
    }
}
