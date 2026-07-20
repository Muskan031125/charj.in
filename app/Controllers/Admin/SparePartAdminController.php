<?php

namespace App\Controllers\Admin;

class SparePartAdminController extends AdminBaseController
{
    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        return trim($slug, '-');
    }

    private function uniqueSlug(string $base, int $excludeId = 0): string
    {
        $slug    = $base;
        $counter = 1;
        while (true) {
            $builder = $this->db->table('spare_parts')->where('slug', $slug);
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

    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $builder = $this->db->table('spare_parts');
        $total    = $builder->countAllResults(false);
        $spareParts = $builder->orderBy('created_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        return view('admin/spare-parts/index', [
            'spareParts' => $spareParts,
            'page'       => $page,
            'totalPages' => ceil($total / $perPage),
            'total'      => $total,
        ]);
    }

    public function create()
    {
        return view('admin/spare-parts/create');
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $partName = trim($post['part_name'] ?? '');
        $description = trim($post['description'] ?? '');

        if (empty($partName)) {
            $errors['part_name'] = 'Part name is required.';
        }
        if (empty($description)) {
            $errors['description'] = 'Description is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug = $this->generateSlug($partName);
        $slug = $this->uniqueSlug($slug);
        $status = $post['status'] ?? 'draft';

        $now = date('Y-m-d H:i:s');
        $this->db->table('spare_parts')->insert([
            'part_name'         => $partName,
            'slug'              => $slug,
            'category'          => $post['category'] ?? 'other',
            'part_number'       => $post['part_number'] ?? null,
            'price'             => !empty($post['price']) ? (float) $post['price'] : null,
            'compatible_models' => $post['compatible_models'] ?? null,
            'description'       => $description,
            'image_url'         => $post['image_url'] ?? null,
            'vendor_name'       => $post['vendor_name'] ?? null,
            'vendor_contact'    => $post['vendor_contact'] ?? null,
            'vendor_url'        => $post['vendor_url'] ?? null,
            'in_stock'          => $post['in_stock'] ?? 1,
            'status'            => $status,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        return redirect()->to('/admin/spare-parts')->with('success', 'Spare part created successfully.');
    }

    public function edit(int $id)
    {
        $sparePart = $this->db->table('spare_parts')->where('id', $id)->get()->getRowArray();
        if (!$sparePart) {
            return redirect()->to('/admin/spare-parts')->with('error', 'Spare part not found.');
        }

        return view('admin/spare-parts/edit', ['sparePart' => $sparePart]);
    }

    public function update(int $id)
    {
        $sparePart = $this->db->table('spare_parts')->where('id', $id)->get()->getRowArray();
        if (!$sparePart) {
            return redirect()->to('/admin/spare-parts')->with('error', 'Spare part not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $partName = trim($post['part_name'] ?? '');
        $description = trim($post['description'] ?? '');

        if (empty($partName)) {
            $errors['part_name'] = 'Part name is required.';
        }
        if (empty($description)) {
            $errors['description'] = 'Description is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($partName);
        }
        $slug   = $this->uniqueSlug($slug, $id);
        $status = $post['status'] ?? 'draft';
        $now    = date('Y-m-d H:i:s');

        $this->db->table('spare_parts')->where('id', $id)->update([
            'part_name'         => $partName,
            'slug'              => $slug,
            'category'          => $post['category'] ?? 'other',
            'part_number'       => $post['part_number'] ?? null,
            'price'             => !empty($post['price']) ? (float) $post['price'] : null,
            'compatible_models' => $post['compatible_models'] ?? null,
            'description'       => $description,
            'image_url'         => $post['image_url'] ?? null,
            'vendor_name'       => $post['vendor_name'] ?? null,
            'vendor_contact'    => $post['vendor_contact'] ?? null,
            'vendor_url'        => $post['vendor_url'] ?? null,
            'in_stock'          => $post['in_stock'] ?? 1,
            'status'            => $status,
            'updated_at'        => $now,
        ]);

        return redirect()->to('/admin/spare-parts')->with('success', 'Spare part updated successfully.');
    }

    public function delete(int $id)
    {
        $this->db->table('spare_parts')->where('id', $id)->delete();
        return redirect()->to('/admin/spare-parts')->with('success', 'Spare part deleted successfully.');
    }
}
