<?php

namespace App\Controllers\Admin;

class AnnouncementAdminController extends AdminBaseController
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
            $builder = $this->db->table('announcements')->where('slug', $slug);
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

        $builder = $this->db->table('announcements');
        $total    = $builder->countAllResults(false);
        $announcements = $builder->orderBy('is_pinned', 'DESC')->orderBy('published_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        return view('admin/announcements/index', [
            'announcements' => $announcements,
            'page'          => $page,
            'totalPages'    => ceil($total / $perPage),
            'total'         => $total,
        ]);
    }

    public function create()
    {
        return view('admin/announcements/create');
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $title   = trim($post['title'] ?? '');
        $content = trim($post['content'] ?? '');

        if (empty($title)) {
            $errors['title'] = 'Title is required.';
        }
        if (empty($content)) {
            $errors['content'] = 'Content is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug   = $this->generateSlug($title);
        $slug   = $this->uniqueSlug($slug);
        $status = $post['status'] ?? 'draft';

        $now = date('Y-m-d H:i:s');
        $this->db->table('announcements')->insert([
            'title'        => $title,
            'slug'         => $slug,
            'content'      => $content,
            'type'         => $post['type'] ?? 'general',
            'is_pinned'    => $post['is_pinned'] ?? 0,
            'banner_image' => $post['banner_image'] ?? null,
            'link_url'     => $post['link_url'] ?? null,
            'status'       => $status,
            'published_at' => $status === 'published' ? $now : null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        return redirect()->to('/admin/announcements')->with('success', 'Announcement created successfully.');
    }

    public function edit(int $id)
    {
        $announcement = $this->db->table('announcements')->where('id', $id)->get()->getRowArray();
        if (!$announcement) {
            return redirect()->to('/admin/announcements')->with('error', 'Announcement not found.');
        }

        return view('admin/announcements/edit', ['announcement' => $announcement]);
    }

    public function update(int $id)
    {
        $announcement = $this->db->table('announcements')->where('id', $id)->get()->getRowArray();
        if (!$announcement) {
            return redirect()->to('/admin/announcements')->with('error', 'Announcement not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $title   = trim($post['title'] ?? '');
        $content = trim($post['content'] ?? '');

        if (empty($title)) {
            $errors['title'] = 'Title is required.';
        }
        if (empty($content)) {
            $errors['content'] = 'Content is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($title);
        }
        $slug   = $this->uniqueSlug($slug, $id);
        $status = $post['status'] ?? 'draft';
        $now    = date('Y-m-d H:i:s');

        $publishedAt = $announcement['published_at'];
        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = $now;
        }

        $this->db->table('announcements')->where('id', $id)->update([
            'title'        => $title,
            'slug'         => $slug,
            'content'      => $content,
            'type'         => $post['type'] ?? 'general',
            'is_pinned'    => $post['is_pinned'] ?? 0,
            'banner_image' => $post['banner_image'] ?? null,
            'link_url'     => $post['link_url'] ?? null,
            'status'       => $status,
            'published_at' => $publishedAt,
            'updated_at'   => $now,
        ]);

        return redirect()->to('/admin/announcements')->with('success', 'Announcement updated successfully.');
    }

    public function delete(int $id)
    {
        $this->db->table('announcements')->where('id', $id)->delete();
        return redirect()->to('/admin/announcements')->with('success', 'Announcement deleted successfully.');
    }
}
