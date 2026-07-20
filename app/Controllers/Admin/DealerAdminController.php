<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class DealerAdminController extends AdminBaseController
{

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    private function generateSlug(string $name, string $city): string
    {
        $raw  = $name . ' ' . $city;
        $slug = strtolower(trim($raw));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $base    = $slug;
        $counter = 1;
        while (true) {
            $count = $this->db->table('dealers')->where('slug', $slug)->countAllResults();
            if ($count === 0) {
                break;
            }
            $slug = $base . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    private function uniqueSlug(string $base, int $excludeId = 0): string
    {
        $slug    = $base;
        $counter = 1;
        while (true) {
            $builder = $this->db->table('dealers')->where('slug', $slug);
            if ($excludeId > 0) {
                $builder->where('id !=', $excludeId);
            }
            if ($builder->countAllResults() === 0) {
                break;
            }
            $slug = $base . '-' . $counter;
            $counter++;
        }
        return $slug;
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

        $builder = $this->db->table('dealers d')
            ->select('d.*');

        if (!empty($city)) {
            $builder->where('d.city', $city);
        }

        $total   = $builder->countAllResults(false);
        $dealers = $builder->orderBy('d.name', 'ASC')->limit($perPage, $offset)->get()->getResultArray();

        // City list for filter dropdown
        $cities = $this->db->table('dealers')
            ->distinct()->select('city')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/dealers/index', [
            'dealers'    => $dealers,
            'cities'     => array_column($cities, 'city'),
            'cityFilter' => $city,
            'page'       => $page,
            'totalPages' => ceil($total / $perPage),
            'total'      => $total,
        ]);
    }

    public function create()
    {
        $brands = $this->db->table('brands')->orderBy('name', 'ASC')->get()->getResultArray();
        return view('admin/dealers/create', ['brands' => $brands]);
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $name = trim($post['name'] ?? '');
        $city = trim($post['city'] ?? '');
        $phone = trim($post['phone'] ?? '');

        if (empty($name)) {
            $errors['name'] = 'Dealer name is required.';
        }
        if (empty($city)) {
            $errors['city'] = 'City is required.';
        }
        if (empty($phone)) {
            $errors['phone'] = 'Phone number is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $rawSlug = strtolower($name . ' ' . $city);
        $rawSlug = preg_replace('/[^a-z0-9\s\-]/', '', $rawSlug);
        $rawSlug = preg_replace('/[\s\-]+/', '-', $rawSlug);
        $rawSlug = trim($rawSlug, '-');
        $slug    = $this->uniqueSlug($rawSlug);

        $this->db->table('dealers')->insert([
            'name'       => $name,
            'slug'       => $slug,
            'city'       => $city,
            'state'      => $post['state'] ?? null,
            'phone'      => $phone,
            'email'      => $post['email'] ?? null,
            'address'    => $post['address'] ?? null,
            'brand_id'   => !empty($post['brand_id']) ? (int) $post['brand_id'] : null,
            'verified'   => 0,
            'status'     => $post['status'] ?? 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/dealers')->with('success', 'Dealer created successfully.');
    }

    public function edit(int $id)
    {
        $dealer = $this->db->table('dealers')->where('id', $id)->get()->getRowArray();
        if (!$dealer) {
            return redirect()->to('/admin/dealers')->with('error', 'Dealer not found.');
        }

        $brands = $this->db->table('brands')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/dealers/edit', [
            'dealer' => $dealer,
            'brands' => $brands,
        ]);
    }

    public function update(int $id)
    {
        $dealer = $this->db->table('dealers')->where('id', $id)->get()->getRowArray();
        if (!$dealer) {
            return redirect()->to('/admin/dealers')->with('error', 'Dealer not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $name  = trim($post['name'] ?? '');
        $city  = trim($post['city'] ?? '');
        $phone = trim($post['phone'] ?? '');

        if (empty($name)) {
            $errors['name'] = 'Dealer name is required.';
        }
        if (empty($city)) {
            $errors['city'] = 'City is required.';
        }
        if (empty($phone)) {
            $errors['phone'] = 'Phone number is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->db->table('dealers')->where('id', $id)->update([
            'name'       => $name,
            'city'       => $city,
            'state'      => $post['state'] ?? null,
            'phone'      => $phone,
            'email'      => $post['email'] ?? null,
            'address'    => $post['address'] ?? null,
            'brand_id'   => !empty($post['brand_id']) ? (int) $post['brand_id'] : null,
            'status'     => $post['status'] ?? 'active',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/dealers')->with('success', 'Dealer updated successfully.');
    }

    public function toggle(int $id)
    {
        $dealer = $this->db->table('dealers')->where('id', $id)->get()->getRowArray();
        if (!$dealer) {
            return redirect()->to('/admin/dealers')->with('error', 'Dealer not found.');
        }

        $newVerified = $dealer['verified'] ? 0 : 1;
        $this->db->table('dealers')->where('id', $id)->update([
            'verified'   => $newVerified,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $msg = $newVerified ? 'Dealer marked as verified.' : 'Dealer verification removed.';
        return redirect()->to('/admin/dealers')->with('success', $msg);
    }

    public function delete(int $id)
    {
        $this->db->table('dealers')->where('id', $id)->delete();
        return redirect()->to('/admin/dealers')->with('success', 'Dealer deleted.');
    }
}
