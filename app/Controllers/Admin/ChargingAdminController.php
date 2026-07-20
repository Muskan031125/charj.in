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
        return view('admin/charging/form', ['isEdit' => false]);
    }

    /**
     * Maps a submitted charging-station form's POST fields onto the real
     * charging_stations table columns (name, total_ports, charging_speed,
     * pricing_per_kwh, open_24x7, working_hours, verified, ...).
     */
    private function fieldsFromPost(array $post): array
    {
        $connectorTypes = $post['connector_types'] ?? [];
        if (!is_array($connectorTypes)) {
            $connectorTypes = array_filter(array_map('trim', explode(',', (string) $connectorTypes)));
        }

        return [
            'name'            => trim($post['name'] ?? ''),
            'operator'        => $post['operator'] ?? null,
            'address'         => $post['address'] ?? null,
            'city'            => trim($post['city'] ?? ''),
            'state'           => $post['state'] ?? null,
            'pincode'         => $post['pincode'] ?? null,
            'latitude'        => !empty($post['latitude']) ? (float) $post['latitude'] : null,
            'longitude'       => !empty($post['longitude']) ? (float) $post['longitude'] : null,
            'google_maps_url' => $post['google_maps_url'] ?? null,
            'connector_types' => json_encode(array_values($connectorTypes)),
            'total_ports'     => !empty($post['total_ports']) ? (int) $post['total_ports'] : null,
            'charging_speed'  => $post['charging_speed'] ?? 'fast',
            'pricing_per_kwh' => !empty($post['pricing_per_kwh']) ? (float) $post['pricing_per_kwh'] : null,
            'open_24x7'       => !empty($post['open_24x7']) ? 1 : 0,
            'working_hours'   => $post['working_hours'] ?? null,
            'status'          => $post['status'] ?? 'operational',
            'verified'        => !empty($post['is_verified']) ? 1 : 0,
        ];
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

        $now  = date('Y-m-d H:i:s');
        $data = $this->fieldsFromPost($post);
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        $this->db->table('charging_stations')->insert($data);

        return redirect()->to('/admin/charging')->with('success', 'Charging station added successfully.');
    }

    public function edit(int $id)
    {
        $station = $this->db->table('charging_stations')->where('id', $id)->get()->getRowArray();
        if (!$station) {
            return redirect()->to('/admin/charging')->with('error', 'Charging station not found.');
        }

        return view('admin/charging/form', ['station' => $station, 'isEdit' => true]);
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

        $data = $this->fieldsFromPost($post);
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->table('charging_stations')->where('id', $id)->update($data);

        return redirect()->to('/admin/charging')->with('success', 'Charging station updated successfully.');
    }

    public function delete(int $id)
    {
        $this->db->table('charging_stations')->where('id', $id)->delete();
        return redirect()->to('/admin/charging')->with('success', 'Charging station deleted.');
    }
}
