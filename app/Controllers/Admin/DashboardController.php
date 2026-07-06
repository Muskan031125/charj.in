<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class DashboardController extends AdminBaseController
{
    public function index()
    {
        $db = db_connect();
        $today      = date('Y-m-d');
        $weekStart  = date('Y-m-d', strtotime('monday this week'));
        $monthStart = date('Y-m-01');

        $totalLeads     = $this->db->table('leads')->countAllResults();
        $leadsToday     = $this->db->table('leads')->where('DATE(created_at)', $today)->countAllResults();
        $leadsThisWeek  = $this->db->table('leads')->where('DATE(created_at) >=', $weekStart)->countAllResults();
        $leadsThisMonth = $this->db->table('leads')->where('DATE(created_at) >=', $monthStart)->countAllResults();

        $pendingQA     = $this->db->tableExists('owner_questions')
            ? $this->db->table('owner_questions')->where('is_approved', 0)->countAllResults()
            : 0;

        $totalVehicles    = $this->db->table('vehicles')->where('status !=', 'discontinued')->countAllResults();
        $publishedVehicles= $this->db->table('vehicles')->where('status', 'published')->countAllResults();
        $draftVehicles    = $this->db->table('vehicles')->where('status', 'draft')->countAllResults();
        $featuredEVs      = $this->db->table('vehicles')->where('featured', 1)->countAllResults();
        $totalBrands      = $this->db->table('brands')->countAllResults();
        $activeBrands     = $this->db->table('brands')->where('status', 'active')->countAllResults();
        $totalDealers     = $this->db->table('dealers')->countAllResults();
        $totalArticles    = $this->db->table('articles')->countAllResults();
        $totalUsers       = $this->db->tableExists('users')
            ? $this->db->table('users')->countAllResults()
            : 0;
        $pendingReviews   = $this->db->tableExists('reviews')
            ? $this->db->table('reviews')->where('status', 'pending')->countAllResults()
            : 0;

        $recentLeads = $this->db->table('leads')
            ->select('leads.*, vehicles.name AS vehicle_name')
            ->join('vehicles', 'vehicles.id = leads.vehicle_id', 'left')
            ->orderBy('leads.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $recentUsers = $this->db->tableExists('users')
            ? $this->db->table('users')
                ->select('id, name, email, city, created_at')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray()
            : [];

        $leadsByTypeRaw = $this->db->table('leads')
            ->select('lead_type, COUNT(*) as count')
            ->groupBy('lead_type')
            ->get()
            ->getResultArray();
        $leadsByType = [];
        foreach ($leadsByTypeRaw as $row) {
            $leadsByType[$row['lead_type']] = (int) $row['count'];
        }

        $leadsByStatusRaw = $this->db->table('leads')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        $leadsByStatus = [];
        foreach ($leadsByStatusRaw as $row) {
            $leadsByStatus[$row['status']] = (int) $row['count'];
        }

        return view('admin/dashboard/index', [
            'totalLeads'        => $totalLeads,
            'leadsToday'        => $leadsToday,
            'leadsThisWeek'     => $leadsThisWeek,
            'leadsThisMonth'    => $leadsThisMonth,
            'totalVehicles'     => $totalVehicles,
            'publishedVehicles' => $publishedVehicles,
            'draftVehicles'     => $draftVehicles,
            'featuredEVs'       => $featuredEVs,
            'totalBrands'       => $totalBrands,
            'activeBrands'      => $activeBrands,
            'totalDealers'      => $totalDealers,
            'totalArticles'     => $totalArticles,
            'totalUsers'        => $totalUsers,
            'pendingReviews'    => $pendingReviews,
            'recentLeads'       => $recentLeads,
            'recentUsers'       => $recentUsers,
            'leadsByType'       => $leadsByType,
            'leadsByStatus'     => $leadsByStatus,
            'pendingQA'         => $pendingQA,
        ]);
    }

    public function previewAsCustomer()
    {
        session()->set('admin_previewing_as_customer', true);
        return redirect()->to(site_url('/'));
    }

    public function exitCustomerPreview()
    {
        session()->remove('admin_previewing_as_customer');
        return redirect()->to(site_url('admin/dashboard'));
    }
}
