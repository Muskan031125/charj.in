<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table            = 'articles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'category_id',
        'author_id',
        'author_name',
        'featured_image',
        'tags',
        'tags_json',
        'read_time_minutes',
        'views',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'meta_keywords',
        'schema_json',
        'featured',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // published() – status='published', ordered by published_at DESC (chainable)
    // -------------------------------------------------------------------------

    public function published(): static
    {
        return $this->where('status', 'published')
            ->orderBy('published_at', 'DESC');
    }

    // -------------------------------------------------------------------------
    // findBySlug
    // -------------------------------------------------------------------------

    public function findBySlug(string $slug): ?array
    {
        $result = $this->where('slug', $slug)->first();
        return $result ?: null;
    }

    // -------------------------------------------------------------------------
    // getLatest – most recent published articles
    // -------------------------------------------------------------------------

    public function getLatest(int $limit = 6): array
    {
        return $this->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->findAll($limit);
    }

    // -------------------------------------------------------------------------
    // incrementViews – atomic increment to avoid race conditions
    // -------------------------------------------------------------------------

    public function incrementViews(int $id): bool
    {
        return $this->db->table('articles')
            ->set('views', 'views + 1', false)
            ->where('id', $id)
            ->update();
    }

    // -------------------------------------------------------------------------
    // byCategory – filter by category slug or category string
    // -------------------------------------------------------------------------

    public function byCategory(string $category): array
    {
        return $this->where('status', 'published')
            ->groupStart()
                ->where('category', $category)
                ->orWhere('category_id', (int) $category)
            ->groupEnd()
            ->orderBy('published_at', 'DESC')
            ->findAll();
    }
}
