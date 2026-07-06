<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class BrandAdminController extends AdminBaseController
{

    protected function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return null;
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        return trim($slug, '-');
    }

    private function uniqueSlug(string $base, int $excludeId = 0): string
    {
        $slug    = $base;
        $counter = 1;
        while (true) {
            $builder = $this->db->table('brands')->where('slug', $slug);
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

        $builder = $this->db->table('brands b')
            ->select('b.*, COUNT(v.id) AS vehicle_count')
            ->join('vehicles v', 'v.brand_id = b.id AND v.status != \'discontinued\'', 'left')
            ->groupBy('b.id');

        $total  = (int) $this->db->table('brands')->countAllResults();
        $brands = $builder->orderBy('b.name', 'ASC')->limit($perPage, $offset)->get()->getResultArray();

        return view('admin/brands/index', [
            'brands'     => $brands,
            'page'       => $page,
            'totalPages' => ceil($total / $perPage),
            'total'      => $total,
        ]);
    }

    public function create()
    {
        return view('admin/brands/create');
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $name = trim($post['name'] ?? '');
        if (empty($name)) {
            $errors['name'] = 'Brand name is required.';
        }

        $slug = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }
        $slug = $this->uniqueSlug($slug);

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->db->table('brands')->insert([
            'name'              => $name,
            'slug'              => $slug,
            'logo_url'          => $post['logo_url'] ?? null,
            'website'           => $post['website'] ?? null,
            'description'       => $post['description'] ?? null,
            'short_description' => $post['short_description'] ?? null,
            'country'           => $post['country'] ?? null,
            'founded_year'      => !empty($post['founded_year']) ? (int)$post['founded_year'] : null,
            'headquarters'      => $post['headquarters'] ?? null,
            'status'            => $post['status'] ?? 'active',
            'featured'          => !empty($post['featured']) ? 1 : 0,
            'seo_title'         => $post['seo_title'] ?? null,
            'seo_description'   => $post['seo_description'] ?? null,
            'sort_order'        => (int)($post['sort_order'] ?? 0),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/brands')->with('success', 'Brand created successfully.');
    }

    public function edit(int $id)
    {
        $brand = $this->db->table('brands')->where('id', $id)->get()->getRowArray();
        if (!$brand) {
            return redirect()->to('/admin/brands')->with('error', 'Brand not found.');
        }

        return view('admin/brands/edit', ['brand' => $brand]);
    }

    public function update(int $id)
    {
        $brand = $this->db->table('brands')->where('id', $id)->get()->getRowArray();
        if (!$brand) {
            return redirect()->to('/admin/brands')->with('error', 'Brand not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $name = trim($post['name'] ?? '');
        if (empty($name)) {
            $errors['name'] = 'Brand name is required.';
        }

        $slug = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }
        $slug = $this->uniqueSlug($slug, $id);

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->db->table('brands')->where('id', $id)->update([
            'name'              => $name,
            'slug'              => $slug,
            'logo_url'          => $post['logo_url'] ?? null,
            'website'           => $post['website'] ?? null,
            'description'       => $post['description'] ?? null,
            'short_description' => $post['short_description'] ?? null,
            'country'           => $post['country'] ?? null,
            'founded_year'      => !empty($post['founded_year']) ? (int)$post['founded_year'] : null,
            'headquarters'      => $post['headquarters'] ?? null,
            'status'            => $post['status'] ?? 'active',
            'featured'          => !empty($post['featured']) ? 1 : 0,
            'seo_title'         => $post['seo_title'] ?? null,
            'seo_description'   => $post['seo_description'] ?? null,
            'sort_order'        => (int)($post['sort_order'] ?? 0),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/brands')->with('success', 'Brand updated successfully.');
    }

    public function delete(int $id)
    {
        $brand = $this->db->table('brands')->where('id', $id)->get()->getRowArray();
        if (!$brand) {
            return redirect()->to('/admin/brands')->with('error', 'Brand not found.');
        }

        // Block deletion if any published vehicles exist for this brand
        $publishedCount = $this->db->table('vehicles')
            ->where('brand_id', $id)
            ->where('status', 'published')
            ->countAllResults();

        if ($publishedCount > 0) {
            return redirect()->to('/admin/brands')->with(
                'error',
                "Cannot delete brand \"{$brand['name']}\" ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â it has {$publishedCount} published vehicle(s). Discontinue or reassign them first."
            );
        }

        $this->db->table('brands')->where('id', $id)->delete();

        return redirect()->to('/admin/brands')->with('success', 'Brand deleted successfully.');
    }
}
