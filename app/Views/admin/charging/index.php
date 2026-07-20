<?php
/**
 * Admin ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Charging Stations Index
 * Variables: $stations (array), $cities (array), $pager, $selectedCity, $selectedStatus
 */
$page_title     = 'Charging Stations';
$breadcrumbs    = ['Charging Stations' => '/admin/charging-stations'];
$selectedCity   = $selectedCity   ?? '';
$selectedStatus = $selectedStatus ?? '';
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Header -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900">Charging Stations</h1>
    <p class="mt-0.5 text-sm text-slate-500">Manage EV charging station listings on Charj.in</p>
  </div>
  <a href="/admin/charging/create"
     class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Add Station
  </a>
</div>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?= esc(session()->getFlashdata('success')) ?>
  </div>
<?php endif; ?>

<!-- Filters -->
<div class="mb-5">
  <form method="get" action="/admin/charging-stations" class="flex flex-wrap items-center gap-3">
    <div class="flex items-center gap-2">
      <label for="city_filter" class="text-sm font-medium text-slate-600 shrink-0">City:</label>
      <select id="city_filter" name="city" onchange="this.form.submit()"
              class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
        <option value="">All Cities</option>
        <?php foreach ($cities ?? [] as $city): ?>
          <option value="<?= esc($city) ?>" <?= $selectedCity === $city ? 'selected' : '' ?>><?= esc($city) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="flex items-center gap-2">
      <label for="status_filter" class="text-sm font-medium text-slate-600 shrink-0">Status:</label>
      <select id="status_filter" name="status" onchange="this.form.submit()"
              class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
        <option value="">All Statuses</option>
        <option value="operational" <?= $selectedStatus === 'operational' ? 'selected' : '' ?>>Operational</option>
        <option value="coming_soon" <?= $selectedStatus === 'coming_soon' ? 'selected' : '' ?>>Coming Soon</option>
        <option value="temporarily_closed" <?= $selectedStatus === 'temporarily_closed' ? 'selected' : '' ?>>Temporarily Closed</option>
      </select>
    </div>
    <?php if ($selectedCity || $selectedStatus): ?>
      <a href="/admin/charging-stations" class="text-sm text-slate-400 hover:text-slate-600 transition-colors">Clear filters</a>
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
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden md:table-cell">Operator</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 hidden sm:table-cell">City</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 hidden lg:table-cell">Speed</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 hidden lg:table-cell">Ports</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
          <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 hidden sm:table-cell">Verified</th>
          <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php if (empty($stations)): ?>
          <tr>
            <td colspan="9" class="px-5 py-12 text-center text-sm text-slate-400">
              No stations found. <a href="/admin/charging/create" class="text-emerald-500 hover:underline">Add the first station.</a>
            </td>
          </tr>
        <?php else: ?>
          <?php
          $speedLabels = [
              'slow'        => ['label' => 'Slow',         'class' => 'bg-slate-100 text-slate-600'],
              'fast'        => ['label' => 'Fast',         'class' => 'bg-blue-100 text-blue-700'],
              'rapid'       => ['label' => 'Rapid',        'class' => 'bg-orange-100 text-orange-700'],
              'ultra_rapid' => ['label' => 'Ultra Rapid',  'class' => 'bg-red-100 text-red-700'],
          ];
          $statusLabels = [
              'operational'         => ['label' => 'Operational',     'class' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
              'coming_soon'         => ['label' => 'Coming Soon',     'class' => 'bg-amber-100 text-amber-700',     'dot' => 'bg-amber-400'],
              'temporarily_closed'  => ['label' => 'Closed',          'class' => 'bg-red-100 text-red-700',         'dot' => 'bg-red-500'],
          ];
          foreach ($stations as $station):
            $speed    = $station['charging_speed'] ?? 'fast';
            $status   = $station['status'] ?? 'operational';
            $verified = !empty($station['is_verified']);
            $ports    = (int) ($station['total_ports'] ?? 0);
            $speedInfo  = $speedLabels[$speed]  ?? ['label' => ucfirst($speed), 'class' => 'bg-slate-100 text-slate-600'];
            $statusInfo = $statusLabels[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-slate-100 text-slate-500', 'dot' => 'bg-slate-400'];
          ?>
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="px-5 py-4 text-sm text-slate-400 font-mono">#<?= esc($station['id']) ?></td>

            <td class="px-5 py-4">
              <p class="text-sm font-semibold text-slate-900"><?= esc($station['name'] ?? '') ?></p>
              <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs"><?= esc($station['address'] ?? '') ?></p>
            </td>

            <td class="px-5 py-4 text-sm text-slate-600 hidden md:table-cell">
              <?= esc($station['operator'] ?? 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â') ?>
            </td>

            <td class="px-5 py-4 text-sm text-slate-600 hidden sm:table-cell">
              <?= esc($station['city'] ?? 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â') ?>
            </td>

            <td class="px-5 py-4 text-center hidden lg:table-cell">
              <span class="inline-flex items-center rounded-full <?= $speedInfo['class'] ?> px-2.5 py-0.5 text-xs font-semibold">
                <?= esc($speedInfo['label']) ?>
              </span>
            </td>

            <td class="px-5 py-4 text-center text-sm font-medium text-slate-700 hidden lg:table-cell">
              <?= $ports > 0 ? $ports : 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â' ?>
            </td>

            <td class="px-5 py-4 text-center">
              <span class="inline-flex items-center gap-1 rounded-full <?= $statusInfo['class'] ?> px-2.5 py-0.5 text-xs font-semibold">
                <span class="h-1.5 w-1.5 rounded-full <?= $statusInfo['dot'] ?>"></span>
                <?= esc($statusInfo['label']) ?>
              </span>
            </td>

            <td class="px-5 py-4 text-center hidden sm:table-cell">
              <?php if ($verified): ?>
                <svg class="mx-auto h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              <?php else: ?>
                <svg class="mx-auto h-5 w-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="/admin/charging/edit/<?= esc($station['id']) ?>"
                   class="rounded-lg p-1.5 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Edit">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="post" action="/admin/charging/delete/<?= esc($station['id']) ?>"
                      onsubmit="return confirm('Delete this charging station?')">
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
