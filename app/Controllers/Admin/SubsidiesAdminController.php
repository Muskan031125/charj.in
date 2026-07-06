<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class SubsidiesAdminController extends AdminBaseController
{
    public function index()
    {
        $tableExists = $this->db->tableExists('subsidies');
        $subsidies   = $tableExists
            ? $this->db->table('subsidies')->orderBy('state', 'ASC')->get()->getResultArray()
            : [];
        return view('admin/subsidies/index', [
            'subsidies'  => $subsidies,
            'title'      => 'Subsidies',
            'page_title' => 'Subsidies',
        ]);
    }

    public function create()
    {
        return view('admin/subsidies/form', [
            'subsidy'    => null,
            'title'      => 'Add Subsidy',
            'page_title' => 'Add Subsidy',
        ]);
    }

    public function store()
    {
        if (!$this->db->tableExists('subsidies')) {
            return redirect()->to('/admin/subsidies')->with('error', 'Subsidies table not found.');
        }
        $this->db->table('subsidies')->insert([
            'state'        => $this->request->getPost('state'),
            'vehicle_type' => $this->request->getPost('vehicle_type'),
            'scheme_name'  => $this->request->getPost('scheme_name'),
            'amount'       => (int) $this->request->getPost('amount'),
            'conditions'   => $this->request->getPost('conditions'),
            'valid_until'  => $this->request->getPost('valid_until') ?: null,
            'is_active'    => 1,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/subsidies')->with('success', 'Subsidy added.');
    }

    public function delete(int $id)
    {
        if ($this->db->tableExists('subsidies')) {
            $this->db->table('subsidies')->where('id', $id)->delete();
        }
        return redirect()->to('/admin/subsidies')->with('success', 'Deleted.');
    }
}
