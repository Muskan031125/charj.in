<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class ChargingAdminController extends AdminBaseController
{

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    // ---------------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------------

    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;
        $city    = $this->request->getGet('city');
        $status  = $this->request->getGet('status');

        $builder = $this->db->table('charging_stations');

        if (!empty($city)) {
            $builder->where('city', $city);
        }
        if (!empty($status)) {
            $builder->where('status', $status);
        }

        $total    = $builder->countAllResults(false);
        $stations = $builder->orderBy('name', 'ASC')->limit($perPage, $offset)->get()->getResultArray();

        // Cities for filter dropdown
        $cities = $this->db->table('charging_stations')
            ->distinct()->select('city')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/charging/index', [
            'stations'    => $stations,
            'cities'      => array_column($cities, 'city'),
            'cityFilter'  => $city,
            'statusFilter'=> $status,
            'page'        => $page,
            'totalPages'  => ceil($total / $perPage),
            'total'       => $total,
        ]);
    }

    public function create()
    {
        return view('admin/charging/create');
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $name = trim($post['name'] ?? '');
        $city = trim($post['city'] ?? '');

        if (empty($name)) {
            $errors['name'] = 'Station name is required.';
        }
        if (empty($city)) {
            $errors['city'] = 'City is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('charging_stations')->insert([
            'name'           => $name,
            'city'           => $city,
            'state'          => $post['state'] ?? null,
            'address'        => $post['address'] ?? null,
            'latitude'       => !empty($post['latitude']) ? (float) $post['latitude'] : null,
            'longitude'      => !empty($post['longitude']) ? (float) $post['longitude'] : null,
            'connector_types'=> $post['connector_types'] ?? null,
            'total_points'   => !empty($post['total_points']) ? (int) $post['total_points'] : null,
            'operator'       => $post['operator'] ?? null,
            'status'         => $post['status'] ?? 'active',
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        return redirect()->to('/admin/charging')->with('success', 'Charging station added successfully.');
    }

    public function edit(int $id)
    {
        $station = $this->db->table('charging_stations')->where('id', $id)->get()->getRowArray();
        if (!$station) {
            return redirect()->to('/admin/charging')->with('error', 'Charging station not found.');
        }

        return view('admin/charging/edit', ['station' => $station]);
    }

    public function update(int $id)
    {
        $station = $this->db->table('charging_stations')->where('id', $id)->get()->getRowArray();
        if (!$station) {
            return redirect()->to('/admin/charging')->with('error', 'Charging station not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $name = trim($post['name'] ?? '');
        $city = trim($post['city'] ?? '');

        if (empty($name)) {
            $errors['name'] = 'Station name is required.';
        }
        if (empty($city)) {
            $errors['city'] = 'City is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->db->table('charging_stations')->where('id', $id)->update([
            'name'           => $name,
            'city'           => $city,
            'state'          => $post['state'] ?? null,
            'address'        => $post['address'] ?? null,
            'latitude'       => !empty($post['latitude']) ? (float) $post['latitude'] : null,
            'longitude'      => !empty($post['longitude']) ? (float) $post['longitude'] : null,
            'connector_types'=> $post['connector_types'] ?? null,
            'total_points'   => !empty($post['total_points']) ? (int) $post['total_points'] : null,
            'operator'       => $post['operator'] ?? null,
            'status'         => $post['status'] ?? 'active',
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/charging')->with('success', 'Charging station updated successfully.');
    }

    public function delete(int $id)
    {
        $this->db->table('charging_stations')->where('id', $id)->delete();
        return redirect()->to('/admin/charging')->with('success', 'Charging station deleted.');
    }
}
