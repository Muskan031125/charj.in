<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ArticleController extends BaseController
{
    public function index()
    {
        $articleModel = new ArticleModel();

        $articles = $articleModel
            ->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->paginate(12, 'default');

        return $this->render('articles/index', [
            'articles'         => $articles,
            'pager'            => $articleModel->pager,
            'meta_title'       => 'EV News, Reviews & Buying Guides | Charj.in',
            'meta_description' => 'Read the latest electric vehicle news, in-depth reviews, buying guides and EV tips for Indian consumers on Charj.in.',
        ]);
    }

    public function show(string $slug)
    {
        $articleModel = new ArticleModel();

        $article = $articleModel->where('slug', $slug)->where('status', 'published')->first();
        if (!$article) {
            throw PageNotFoundException::forPageNotFound('Article not found');
        }

        // Increment view count (non-critical — silently fail)
        try {
            $db = \Config\Database::connect();
            $db->table('articles')
               ->where('id', $article['id'])
               ->set('views', 'views + 1', false)
               ->update();
        } catch (\Throwable $e) {
            log_message('warning', 'Could not increment article views: ' . $e->getMessage());
        }

        // Related articles — same category_id, exclude current, limit 3
        $relatedArticles = [];
        if (!empty($article['category_id'])) {
            $relatedArticles = $articleModel
                ->where('status', 'published')
                ->where('category_id', $article['category_id'])
                ->where('id !=', $article['id'])
                ->orderBy('published_at', 'DESC')
                ->limit(3)
                ->findAll();
        }
        // Fallback: fill up to 3 with latest articles if same-category didn't yield enough
        if (count($relatedArticles) < 3) {
            $existingIds   = array_merge([(int) $article['id']], array_column($relatedArticles, 'id'));
            $fillCount     = 3 - count($relatedArticles);
            $fillerArticles = $articleModel
                ->where('status', 'published')
                ->whereNotIn('id', $existingIds)
                ->orderBy('published_at', 'DESC')
                ->limit($fillCount)
                ->findAll();
            $relatedArticles = array_merge($relatedArticles, $fillerArticles);
        }

        // JSON-LD structured data (Article schema)
        if (!empty($article['schema_json'])) {
            $schemaJson = $article['schema_json'];
        } else {
            $schemaData = [
                '@context'          => 'https://schema.org',
                '@type'             => 'Article',
                'headline'          => $article['title'],
                'description'       => $article['excerpt'] ?? '',
                'datePublished'     => $article['published_at'] ?? '',
                'dateModified'      => $article['updated_at'] ?? $article['published_at'] ?? '',
                'image'             => !empty($article['featured_image'])
                    ? base_url($article['featured_image'])
                    : base_url('assets/images/charj-og.jpg'),
                'publisher'         => [
                    '@type' => 'Organization',
                    'name'  => 'Charj.in',
                    'logo'  => [
                        '@type' => 'ImageObject',
                        'url'   => base_url('assets/images/logo.png'),
                    ],
                ],
                'url' => current_url(),
            ];
            $schemaJson = json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $metaTitle = ($article['seo_title'] ?? $article['title']) . ' | Charj.in';
        $metaDesc  = $article['seo_description'] ?? $article['excerpt'] ?? substr(strip_tags($article['content'] ?? ''), 0, 155);

        return $this->render('articles/show', [
            'article'          => $article,
            'relatedArticles'  => $relatedArticles,
            'schemaJson'       => $schemaJson,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
