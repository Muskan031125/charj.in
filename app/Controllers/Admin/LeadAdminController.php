<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class LeadAdminController extends AdminBaseController
{

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    // ---------------------------------------------------------------
    // Shared filter builder
    // ---------------------------------------------------------------

    private function applyFilters(\CodeIgniter\Database\BaseBuilder $builder): void
    {
        $status    = $this->request->getGet('status');
        $leadType  = $this->request->getGet('lead_type');
        $dateFrom  = $this->request->getGet('date_from');
        $dateTo    = $this->request->getGet('date_to');
        $search    = $this->request->getGet('search');

        if (!empty($status)) {
            $builder->where('l.status', $status);
        }
        if (!empty($leadType)) {
            $builder->where('l.lead_type', $leadType);
        }
        if (!empty($dateFrom)) {
            $builder->where('DATE(l.created_at) >=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $builder->where('DATE(l.created_at) <=', $dateTo);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('l.name', $search)
                ->orLike('l.mobile', $search)
                ->orLike('l.email', $search)
                ->groupEnd();
        }
    }

    // ---------------------------------------------------------------
    // Index
    // ---------------------------------------------------------------

    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 30;
        $offset  = ($page - 1) * $perPage;

        $builder = $this->db->table('leads l')
            ->select('l.*, v.name AS vehicle_name')
            ->join('vehicles v', 'v.id = l.vehicle_id', 'left');

        $this->applyFilters($builder);

        $total = $builder->countAllResults(false);
        $leads = $builder->orderBy('l.created_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        $statuses  = ['new', 'contacted', 'qualified', 'converted', 'closed', 'junk'];
        $leadTypes = ['test_drive', 'price_quote', 'dealer_contact', 'brochure', 'general'];

        return view('admin/leads/index', [
            'leads'      => $leads,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => ceil($total / $perPage),
            'statuses'   => $statuses,
            'leadTypes'  => $leadTypes,
            'filters'    => [
                'status'    => $this->request->getGet('status'),
                'lead_type' => $this->request->getGet('lead_type'),
                'date_from' => $this->request->getGet('date_from'),
                'date_to'   => $this->request->getGet('date_to'),
                'search'    => $this->request->getGet('search'),
            ],
        ]);
    }

    // ---------------------------------------------------------------
    // Show
    // ---------------------------------------------------------------

    public function show(int $id)
    {
        $lead = $this->db->table('leads l')
            ->select('l.*, v.name AS vehicle_name, v.slug AS vehicle_slug')
            ->join('vehicles v', 'v.id = l.vehicle_id', 'left')
            ->where('l.id', $id)
            ->get()
            ->getRowArray();

        if (!$lead) {
            return redirect()->to('/admin/leads')->with('error', 'Lead not found.');
        }

        $notes = $this->db->table('lead_notes')
            ->where('lead_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $statuses = ['new', 'contacted', 'qualified', 'converted', 'closed', 'junk'];

        return view('admin/leads/show', [
            'lead'     => $lead,
            'notes'    => $notes,
            'statuses' => $statuses,
        ]);
    }

    // ---------------------------------------------------------------
    // Update Status
    // ---------------------------------------------------------------

    public function updateStatus(int $id)
    {
        $lead = $this->db->table('leads')->where('id', $id)->get()->getRowArray();
        if (!$lead) {
            return redirect()->to('/admin/leads')->with('error', 'Lead not found.');
        }

        $status = $this->request->getPost('status');
        $validStatuses = ['new', 'contacted', 'qualified', 'converted', 'closed', 'junk'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        $this->db->table('leads')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Auto-note
        $adminName = session()->get('admin_name');
        $this->db->table('lead_notes')->insert([
            'lead_id'    => $id,
            'note'       => "{$adminName} changed status to {$status}",
            'created_by' => session()->get('admin_id'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/leads/' . $id)->with('success', 'Status updated to ' . $status . '.');
    }

    // ---------------------------------------------------------------
    // Add Note
    // ---------------------------------------------------------------

    public function addNote(int $id)
    {
        $lead = $this->db->table('leads')->where('id', $id)->get()->getRowArray();
        if (!$lead) {
            return redirect()->to('/admin/leads')->with('error', 'Lead not found.');
        }

        $note = trim($this->request->getPost('note') ?? '');
        if (empty($note)) {
            return redirect()->back()->with('error', 'Note cannot be empty.');
        }

        $this->db->table('lead_notes')->insert([
            'lead_id'    => $id,
            'note'       => $note,
            'created_by' => session()->get('admin_id'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/leads/' . $id)->with('success', 'Note added.');
    }

    // ---------------------------------------------------------------
    // Export CSV
    // ---------------------------------------------------------------

    public function exportCsv()
    {
        $builder = $this->db->table('leads l')
            ->select('l.id, l.name, l.email, l.mobile, l.lead_type, l.status, v.name AS vehicle_name, l.city, l.state, l.message, l.created_at')
            ->join('vehicles v', 'v.id = l.vehicle_id', 'left');

        $this->applyFilters($builder);

        $leads = $builder->orderBy('l.created_at', 'DESC')->get()->getResultArray();

        $filename = 'leads_export_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // BOM for Excel UTF-8 compatibility
        fputs($output, "\xEF\xBB\xBF");

        // Header row
        fputcsv($output, [
            'ID', 'Name', 'Email', 'Mobile', 'Lead Type', 'Status',
            'Vehicle', 'City', 'State', 'Message', 'Created At',
        ]);

        foreach ($leads as $lead) {
            fputcsv($output, [
                $lead['id'],
                $lead['name'],
                $lead['email'],
                $lead['mobile'],
                $lead['lead_type'],
                $lead['status'],
                $lead['vehicle_name'] ?? '',
                $lead['city'] ?? '',
                $lead['state'] ?? '',
                $lead['message'] ?? '',
                $lead['created_at'],
            ]);
        }

        fclose($output);
        exit;
    }
}
