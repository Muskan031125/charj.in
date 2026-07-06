<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class ArticleAdminController extends AdminBaseController
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
            $builder = $this->db->table('articles')->where('slug', $slug);
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

        $builder = $this->db->table('articles');
        $total    = $builder->countAllResults(false);
        $articles = $builder->orderBy('created_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        return view('admin/articles/index', [
            'articles'   => $articles,
            'page'       => $page,
            'totalPages' => ceil($total / $perPage),
            'total'      => $total,
        ]);
    }

    public function create()
    {
        return view('admin/articles/create');
    }

    public function store()
    {
        $post   = $this->request->getPost();
        $errors = [];

        $title   = trim($post['title'] ?? '');
        $content = trim($post['content'] ?? '');

        if (empty($title)) {
            $errors['title'] = 'Article title is required.';
        }
        if (empty($content)) {
            $errors['content'] = 'Article content is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug   = $this->generateSlug($title);
        $slug   = $this->uniqueSlug($slug);
        $status = $post['status'] ?? 'draft';

        $now = date('Y-m-d H:i:s');
        $this->db->table('articles')->insert([
            'title'        => $title,
            'slug'         => $slug,
            'content'      => $content,
            'excerpt'      => $post['excerpt'] ?? null,
            'image_url'    => $post['image_url'] ?? null,
            'status'       => $status,
            'author_id'    => session()->get('admin_id'),
            'published_at' => $status === 'published' ? $now : null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        return redirect()->to('/admin/articles')->with('success', 'Article created successfully.');
    }

    public function edit(int $id)
    {
        $article = $this->db->table('articles')->where('id', $id)->get()->getRowArray();
        if (!$article) {
            return redirect()->to('/admin/articles')->with('error', 'Article not found.');
        }

        return view('admin/articles/edit', ['article' => $article]);
    }

    public function update(int $id)
    {
        $article = $this->db->table('articles')->where('id', $id)->get()->getRowArray();
        if (!$article) {
            return redirect()->to('/admin/articles')->with('error', 'Article not found.');
        }

        $post   = $this->request->getPost();
        $errors = [];

        $title   = trim($post['title'] ?? '');
        $content = trim($post['content'] ?? '');

        if (empty($title)) {
            $errors['title'] = 'Article title is required.';
        }
        if (empty($content)) {
            $errors['content'] = 'Article content is required.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $slug   = trim($post['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->generateSlug($title);
        }
        $slug   = $this->uniqueSlug($slug, $id);
        $status = $post['status'] ?? 'draft';
        $now    = date('Y-m-d H:i:s');

        // Set published_at only on first publish
        $publishedAt = $article['published_at'];
        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = $now;
        }

        $this->db->table('articles')->where('id', $id)->update([
            'title'        => $title,
            'slug'         => $slug,
            'content'      => $content,
            'excerpt'      => $post['excerpt'] ?? null,
            'image_url'    => $post['image_url'] ?? null,
            'status'       => $status,
            'published_at' => $publishedAt,
            'updated_at'   => $now,
        ]);

        return redirect()->to('/admin/articles')->with('success', 'Article updated successfully.');
    }
}
