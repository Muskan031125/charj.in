<?php
/**
 * Admin Ã¢â‚¬â€ Brands Index
 * Variables: $brands (array), $pager, $page_title
 */
$page_title  = 'Brands';
$breadcrumbs = ['Brands' => '/admin/brands'];
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900">Brands</h1>
    <p class="mt-0.5 text-sm text-slate-500">Manage all EV brands listed on Charj.in</p>
  </div>
  <a href="/admin/brands/create"
     class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Add Brand
  </a>
</div>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?= esc(session()->getFlashdata('success')) ?>
  </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="mb-5 flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
    <svg class="h-5 w-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <?= esc(session()->getFlashdata('error')) ?>
  </div>
<?php endif; ?>

<!-- Table Card -->
<div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-200">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
      <thead>
        <tr class="bg-slate-50">
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ID</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden md:table-cell">Slug</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden sm:table-cell">Country</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Vehicles</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 hidden sm:table-cell">Featured</th>
          <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php if (empty($brands)): ?>
          <tr>
            <td colspan="8" class="px-5 py-12 text-center text-sm text-slate-400">
              No brands found. <a href="/admin/brands/create" class="text-emerald-500 hover:underline">Add the first brand.</a>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($brands as $brand):
            $status    = $brand['status'] ?? 'draft';
            $featured  = !empty($brand['is_featured']);
            $count     = (int) ($brand['vehicle_count'] ?? 0);
          ?>
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="px-5 py-4 text-sm text-slate-400 font-mono">#<?= esc($brand['id']) ?></td>

            <td class="px-5 py-4">
              <div class="flex items-center gap-3">
                <!-- Logo/initial avatar -->
                <?php if (!empty($brand['logo_url'])): ?>
                  <img src="<?= esc($brand['logo_url']) ?>" alt="<?= esc($brand['name']) ?>"
                       class="h-8 w-8 rounded-lg object-contain bg-slate-100 border border-slate-200" loading="lazy">
                <?php else: ?>
                  <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 text-sm font-bold shrink-0">
                    <?= esc(strtoupper(substr($brand['name'] ?? 'B', 0, 1))) ?>
                  </div>
                <?php endif; ?>
                <div>
                  <p class="text-sm font-semibold text-slate-900"><?= esc($brand['name']) ?></p>
                  <p class="text-xs text-slate-400 md:hidden"><?= esc($brand['slug'] ?? '') ?></p>
                </div>
              </div>
            </td>

            <td class="px-5 py-4 text-sm text-slate-500 font-mono hidden md:table-cell">
              <?= esc($brand['slug'] ?? 'Ã¢â‚¬â€') ?>
            </td>

            <td class="px-5 py-4 text-sm text-slate-500 hidden sm:table-cell">
              <?= esc($brand['country_of_origin'] ?? 'Ã¢â‚¬â€') ?>
            </td>

            <td class="px-5 py-4 text-center">
              <span class="inline-flex items-center justify-center rounded-full <?= $count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?> px-2.5 py-0.5 text-xs font-semibold min-w-[2rem]">
                <?= $count ?>
              </span>
            </td>

            <td class="px-5 py-4 text-center">
              <?php if ($status === 'published'): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                  <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                  Published
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">
                  <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                  Draft
                </span>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-center hidden sm:table-cell">
              <?php if ($featured): ?>
                <svg class="mx-auto h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              <?php else: ?>
                <span class="text-slate-300 text-lg">Ã¢â‚¬â€</span>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="/brands/<?= esc($brand['slug'] ?? '') ?>" target="_blank" rel="noopener"
                   class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors" title="View on site">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                  </svg>
                </a>
                <a href="/admin/brands/edit/<?= esc($brand['id']) ?>"
                   class="rounded-lg p-1.5 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Edit">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="post" action="/admin/brands/delete/<?= esc($brand['id']) ?>"
                      onsubmit="return confirm('Delete brand \'<?= esc(addslashes($brand['name'] ?? '')) ?>\'? This cannot be undone.')">
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

  <!-- Pagination -->
  <?php if (!empty($pager)): ?>
    <div class="border-t border-slate-200 px-5 py-4">
      <?= $pager->links() ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
