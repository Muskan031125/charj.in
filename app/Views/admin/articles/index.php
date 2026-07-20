<?php
/**
 * Admin Ã¢â‚¬â€ Articles Index
 * Variables: $articles (array), $pager, $page_title
 */
$page_title  = 'Articles';
$breadcrumbs = ['Articles' => '/admin/articles'];
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900">Articles</h1>
    <p class="mt-0.5 text-sm text-slate-500">Blog posts, guides and EV news on Charj.in</p>
  </div>
  <a href="/admin/articles/create"
     class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Add Article
  </a>
</div>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?= esc(session()->getFlashdata('success')) ?>
  </div>
<?php endif; ?>

<!-- Table -->
<div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-200">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
      <thead>
        <tr class="bg-slate-50">
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 w-10">ID</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Title</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden md:table-cell">Category</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 hidden sm:table-cell">Views</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden lg:table-cell">Published At</th>
          <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php if (empty($articles)): ?>
          <tr>
            <td colspan="7" class="px-5 py-12 text-center text-sm text-slate-400">
              No articles yet. <a href="/admin/articles/create" class="text-emerald-500 hover:underline">Write the first article.</a>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($articles as $article):
            $status     = $article['status'] ?? 'draft';
            $views      = (int) ($article['views'] ?? 0);
            $pubAt      = $article['published_at'] ?? null;
            $pubDisplay = $pubAt ? date('d M Y', strtotime($pubAt)) : 'Ã¢â‚¬â€';
          ?>
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="px-5 py-4 text-sm text-slate-400 font-mono">#<?= esc($article['id']) ?></td>

            <td class="px-5 py-4">
              <p class="text-sm font-semibold text-slate-900 line-clamp-1"><?= esc($article['title'] ?? '') ?></p>
              <?php if (!empty($article['slug'])): ?>
                <p class="text-xs text-slate-400 font-mono mt-0.5 truncate max-w-xs">/articles/<?= esc($article['slug']) ?></p>
              <?php endif; ?>
              <?php if (!empty($article['author_name'])): ?>
                <p class="text-xs text-slate-400 mt-0.5">by <?= esc($article['author_name']) ?></p>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-sm text-slate-500 hidden md:table-cell">
              <?php if (!empty($article['category'])): ?>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                  <?= esc($article['category']) ?>
                </span>
              <?php else: ?>
                <span class="text-slate-300">Ã¢â‚¬â€</span>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-center">
              <?php if ($status === 'published'): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                  <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Published
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                  <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>Draft
                </span>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-center hidden sm:table-cell">
              <span class="text-sm text-slate-600 font-medium"><?= number_format($views) ?></span>
            </td>

            <td class="px-5 py-4 text-sm text-slate-500 hidden lg:table-cell whitespace-nowrap">
              <?= esc($pubDisplay) ?>
            </td>

            <td class="px-5 py-4">
              <div class="flex items-center justify-end gap-2">
                <?php if ($status === 'published' && !empty($article['slug'])): ?>
                  <a href="/articles/<?= esc($article['slug']) ?>" target="_blank" rel="noopener"
                     class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors" title="View article">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                  </a>
                <?php endif; ?>
                <a href="/admin/articles/edit/<?= esc($article['id']) ?>"
                   class="rounded-lg p-1.5 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Edit">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="post" action="/admin/articles/delete/<?= esc($article['id']) ?>"
                      onsubmit="return confirm('Delete this article? This cannot be undone.')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit"
                          class="rounded-lg p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($pager)): ?>
    <div class="border-t border-slate-200 px-5 py-4">
      <?= $pager->links() ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
