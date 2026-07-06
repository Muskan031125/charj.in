<?php
/**
 * Admin â€” Article Create / Edit Form
 * Variables: $article (array, for edit), $isEdit (bool), $errors (array), $page_title
 */
$isEdit     = $isEdit ?? false;
$article    = $article ?? [];
$page_title = $isEdit ? 'Edit Article' : 'Add Article';
$breadcrumbs = [
    'Articles' => '/admin/articles',
    ($isEdit ? 'Edit' : 'Add Article') => '',
];
$formAction = $isEdit
    ? '/admin/articles/update/' . ($article['id'] ?? '')
    : '/admin/articles/store';
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900"><?= $isEdit ? 'Edit Article' : 'Write New Article' ?></h1>
    <p class="mt-0.5 text-sm text-slate-500"><?= $isEdit ? 'Update article content and metadata.' : 'Create a new blog post, guide or news item.' ?></p>
  </div>
  <a href="/admin/articles"
     class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
    </svg>
    Back to Articles
  </a>
</div>

<?php if (!empty($errors) || session()->getFlashdata('errors')): ?>
  <?php $allErrors = array_merge($errors ?? [], session()->getFlashdata('errors') ?? []); ?>
  <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4">
    <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
      <?php foreach ($allErrors as $err): ?>
        <li class="text-sm text-red-600"><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= esc($formAction) ?>" class="space-y-6" x-data="articleForm()">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?>
    <input type="hidden" name="_method" value="PUT">
  <?php endif; ?>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">

      <!-- Core Fields -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Article Content</h2>

        <div class="space-y-5">

          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">
              Title <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title" required
                   x-model="title" @input="generateSlug()"
                   value="<?= esc(old('title', $article['title'] ?? '')) ?>"
                   placeholder="e.g. Best Electric Scooters in India Under â‚¹1 Lakh (2026)"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Slug -->
          <div>
            <label for="slug" class="block text-sm font-medium text-slate-700 mb-1.5">
              Slug <span class="text-xs text-slate-400 font-normal ml-1">Auto-generated from title</span>
            </label>
            <div class="flex items-center rounded-xl border border-slate-200 focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-400/20 overflow-hidden transition">
              <span class="inline-flex items-center bg-slate-50 px-3 py-2.5 text-sm text-slate-400 border-r border-slate-200 shrink-0">charj.in/articles/</span>
              <input type="text" id="slug" name="slug"
                     x-model="slug"
                     value="<?= esc(old('slug', $article['slug'] ?? '')) ?>"
                     placeholder="best-electric-scooters-india"
                     class="flex-1 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none bg-white">
            </div>
          </div>

          <!-- Category & Author -->
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Category</label>
              <input type="text" id="category" name="category"
                     value="<?= esc(old('category', $article['category'] ?? '')) ?>"
                     list="category_suggestions"
                     placeholder="e.g. Buying Guide"
                     class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
              <datalist id="category_suggestions">
                <option value="Buying Guide">
                <option value="EV News">
                <option value="Comparison">
                <option value="Review">
                <option value="Policy & Subsidies">
                <option value="Charging">
                <option value="Technology">
                <option value="Tips & Tricks">
              </datalist>
            </div>
            <div>
              <label for="author_name" class="block text-sm font-medium text-slate-700 mb-1.5">Author Name</label>
              <input type="text" id="author_name" name="author_name"
                     value="<?= esc(old('author_name', $article['author_name'] ?? '')) ?>"
                     placeholder="e.g. Rahul Verma"
                     class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
            </div>
          </div>

          <!-- Excerpt -->
          <div>
            <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-1.5">
              Excerpt
              <span class="ml-2 text-xs text-slate-400 font-normal">Max 200 characters</span>
            </label>
            <textarea id="excerpt" name="excerpt" rows="3" maxlength="200"
                      placeholder="A short summary of the article shown in listing pages and search results..."
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition resize-y"><?= esc(old('excerpt', $article['excerpt'] ?? '')) ?></textarea>
            <p class="mt-1 text-xs text-slate-400">Shown in article cards and Google previews.</p>
          </div>

          <!-- Content -->
          <div>
            <label for="content" class="block text-sm font-medium text-slate-700 mb-1.5">
              Content <span class="text-red-500">*</span>
              <!-- WYSIWYG: Replace this textarea with a rich-text editor (e.g. Quill, TinyMCE, or Editor.js) for production -->
            </label>
            <textarea id="content" name="content" rows="20" required
                      placeholder="Write your article content here. HTML is supported."
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 font-mono placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition resize-y"><?= esc(old('content', $article['content'] ?? '')) ?></textarea>
            <p class="mt-1 text-xs text-slate-400">HTML supported. For a WYSIWYG editor, integrate TinyMCE or Quill targeting #content.</p>
          </div>

          <!-- Featured Image -->
          <div>
            <label for="featured_image_url" class="block text-sm font-medium text-slate-700 mb-1.5">Featured Image URL</label>
            <input type="url" id="featured_image_url" name="featured_image_url"
                   value="<?= esc(old('featured_image_url', $article['featured_image_url'] ?? '')) ?>"
                   placeholder="https://cdn.example.com/images/article-cover.jpg"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
            <p class="mt-1 text-xs text-slate-400">Recommended: 1200Ã—630px, JPEG or WebP</p>
          </div>

          <!-- Tags -->
          <div>
            <label for="tags" class="block text-sm font-medium text-slate-700 mb-1.5">Tags</label>
            <input type="text" id="tags" name="tags"
                   value="<?= esc(old('tags', $article['tags'] ?? '')) ?>"
                   placeholder="e.g. electric scooter, under 1 lakh, 2026"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
            <p class="mt-1 text-xs text-slate-400">Comma-separated tags for internal categorisation</p>
          </div>

        </div>
      </div>

      <!-- SEO -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">SEO Settings</h2>
        <div class="space-y-5">
          <div>
            <label for="seo_title" class="block text-sm font-medium text-slate-700 mb-1.5">SEO Title</label>
            <input type="text" id="seo_title" name="seo_title"
                   value="<?= esc(old('seo_title', $article['seo_title'] ?? '')) ?>"
                   maxlength="70"
                   placeholder="Optimised page title for search engines (50â€“70 chars)"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>
          <div>
            <label for="seo_description" class="block text-sm font-medium text-slate-700 mb-1.5">SEO Description</label>
            <textarea id="seo_description" name="seo_description" rows="3"
                      maxlength="160"
                      placeholder="Meta description for search engines (120â€“160 chars)"
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition resize-y"><?= esc(old('seo_description', $article['seo_description'] ?? '')) ?></textarea>
          </div>
        </div>
      </div>

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">

      <!-- Publish Settings -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Publish Settings</h2>

        <div class="space-y-5">
          <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
            <select id="status" name="status"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
              <?php $statusVal = old('status', $article['status'] ?? 'draft'); ?>
              <option value="draft" <?= $statusVal === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="published" <?= $statusVal === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
          </div>

          <div>
            <label for="published_at" class="block text-sm font-medium text-slate-700 mb-1.5">Published At</label>
            <input type="datetime-local" id="published_at" name="published_at"
                   value="<?= esc(old('published_at', !empty($article['published_at']) ? date('Y-m-d\TH:i', strtotime($article['published_at'])) : date('Y-m-d\TH:i'))) ?>"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
            <p class="mt-1 text-xs text-slate-400">Schedule future publication or backdate.</p>
          </div>
        </div>
      </div>

      <!-- Save -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 space-y-3">
        <button type="submit"
                class="w-full rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-emerald-600 transition-colors">
          <?= $isEdit ? 'Save Changes' : 'Publish Article' ?>
        </button>
        <a href="/admin/articles"
           class="block w-full rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
          Cancel
        </a>
      </div>

      <?php if ($isEdit && !empty($article['id'])): ?>
      <div class="rounded-2xl bg-red-50 border border-red-200 p-5">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Danger Zone</h3>
        <form method="post" action="/admin/articles/delete/<?= esc($article['id']) ?>"
              onsubmit="return confirm('Permanently delete this article?')">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit"
                  class="w-full rounded-xl border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors">
            Delete Article
          </button>
        </form>
      </div>
      <?php endif; ?>

    </div>
  </div>
</form>

<script>
function articleForm() {
    return {
        title: '<?= addslashes(old('title', $article['title'] ?? '')) ?>',
        slug: '<?= addslashes(old('slug', $article['slug'] ?? '')) ?>',
        generateSlug() {
            this.slug = this.title
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    }
}
</script>

<?= $this->endSection() ?>
