<?php
/**
 * Admin Ã¢â‚¬â€ Dealers Index
 * Variables: $dealers (array), $cities (array), $pager, $page_title, $selectedCity
 */
$page_title   = 'Dealers';
$breadcrumbs  = ['Dealers' => '/admin/dealers'];
$selectedCity = $selectedCity ?? '';
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900">Dealers</h1>
    <p class="mt-0.5 text-sm text-slate-500">Manage EV dealer partners on Charj.in</p>
  </div>
  <a href="/admin/dealers/create"
     class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Add Dealer
  </a>
</div>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?= esc(session()->getFlashdata('success')) ?>
  </div>
<?php endif; ?>

<!-- Filters -->
<div class="mb-5 flex flex-wrap items-center gap-3">
  <form method="get" action="/admin/dealers" class="flex items-center gap-3 flex-wrap">
    <div class="flex items-center gap-2">
      <label for="city_filter" class="text-sm font-medium text-slate-600 shrink-0">Filter by City:</label>
      <select id="city_filter" name="city" onchange="this.form.submit()"
              class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
        <option value="">All Cities</option>
        <?php foreach ($cities ?? [] as $city): ?>
          <option value="<?= esc($city) ?>" <?= $selectedCity === $city ? 'selected' : '' ?>><?= esc($city) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if ($selectedCity): ?>
      <a href="/admin/dealers" class="text-sm text-slate-400 hover:text-slate-600 transition-colors">
        Clear filter
      </a>
    <?php endif; ?>
  </form>
</div>

<!-- Table -->
<div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-200">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
      <thead>
        <tr class="bg-slate-50">
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ID</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden sm:table-cell">City</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden md:table-cell">State</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden lg:table-cell">Phone</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Verified</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
          <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php if (empty($dealers)): ?>
          <tr>
            <td colspan="8" class="px-5 py-12 text-center text-sm text-slate-400">
              No dealers found. <a href="/admin/dealers/create" class="text-emerald-500 hover:underline">Add the first dealer.</a>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($dealers as $dealer):
            $verified = !empty($dealer['is_verified']);
            $status   = $dealer['status'] ?? 'inactive';
          ?>
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="px-5 py-4 text-sm text-slate-400 font-mono">#<?= esc($dealer['id']) ?></td>

            <td class="px-5 py-4">
              <p class="text-sm font-semibold text-slate-900"><?= esc($dealer['name'] ?? '') ?></p>
              <?php if (!empty($dealer['contact_person'])): ?>
                <p class="text-xs text-slate-400"><?= esc($dealer['contact_person']) ?></p>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-sm text-slate-600 hidden sm:table-cell">
              <?= esc($dealer['city'] ?? 'Ã¢â‚¬â€') ?>
            </td>

            <td class="px-5 py-4 text-sm text-slate-500 hidden md:table-cell">
              <?= esc($dealer['state'] ?? 'Ã¢â‚¬â€') ?>
            </td>

            <td class="px-5 py-4 text-sm text-slate-600 font-mono hidden lg:table-cell">
              <?php if (!empty($dealer['phone'])): ?>
                <a href="tel:+91<?= esc(preg_replace('/\D/', '', $dealer['phone'])) ?>"
                   class="hover:text-emerald-600 transition-colors">
                  <?= esc($dealer['phone']) ?>
                </a>
              <?php else: ?>
                Ã¢â‚¬â€
              <?php endif; ?>
            </td>

            <!-- Verified toggle -->
            <td class="px-5 py-4 text-center">
              <form method="post" action="/admin/dealers/toggle-verified/<?= esc($dealer['id']) ?>">
                <?= csrf_field() ?>
                <button type="submit" title="<?= $verified ? 'Click to unverify' : 'Click to verify' ?>">
                  <?php if ($verified): ?>
                    <svg class="mx-auto h-5 w-5 text-emerald-500 hover:text-red-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                  <?php else: ?>
                    <svg class="mx-auto h-5 w-5 text-slate-300 hover:text-emerald-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                  <?php endif; ?>
                </button>
              </form>
            </td>

            <td class="px-5 py-4 text-center">
              <?php if ($status === 'active'): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                  <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">
                  <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactive
                </span>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="/admin/dealers/edit/<?= esc($dealer['id']) ?>"
                   class="rounded-lg p-1.5 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Edit">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="post" action="/admin/dealers/delete/<?= esc($dealer['id']) ?>"
                      onsubmit="return confirm('Delete dealer \'<?= esc(addslashes($dealer['name'] ?? '')) ?>\'?')">
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
