<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$page_title = 'Leads';
$statusColors = [
    'new'       => 'bg-blue-100 text-blue-800',
    'contacted' => 'bg-amber-100 text-amber-800',
    'qualified' => 'bg-purple-100 text-purple-800',
    'converted' => 'bg-emerald-100 text-emerald-800',
    'lost'      => 'bg-red-100 text-red-800',
    'closed'    => 'bg-slate-100 text-slate-700',
];
$sourceColors = [
    'organic'  => 'bg-teal-100 text-teal-800',
    'google'   => 'bg-blue-100 text-blue-800',
    'facebook' => 'bg-indigo-100 text-indigo-800',
    'direct'   => 'bg-slate-100 text-slate-700',
];
$filters = $filters ?? [];
?>

<!-- Header -->
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Leads</h1>
        <p class="mt-0.5 text-sm text-slate-500">
            Showing <strong><?= number_format($totalCount ?? 0) ?></strong> lead<?= ($totalCount ?? 0) != 1 ? 's' : '' ?>
            <?= !empty($filters) ? '(filtered)' : '' ?>
        </p>
    </div>
    <!-- Export -->
    <a href="/admin/export/leads?<?= http_build_query($filters) ?>"
       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Export CSV
    </a>
</div>

<!-- Filter bar -->
<div class="mb-5 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
    <form method="get" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
                <option value="">All statuses</option>
                <?php foreach (['new','contacted','qualified','converted','lost','closed'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">Lead Type</label>
            <select name="lead_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
                <option value="">All types</option>
                <option value="get_best_price"       <?= ($filters['lead_type'] ?? '') === 'get_best_price'       ? 'selected' : '' ?>>Best Price</option>
                <option value="book_test_ride"        <?= ($filters['lead_type'] ?? '') === 'book_test_ride'        ? 'selected' : '' ?>>Test Ride</option>
                <option value="ev_recommendation"     <?= ($filters['lead_type'] ?? '') === 'ev_recommendation'     ? 'selected' : '' ?>>Recommendation</option>
                <option value="finance_enquiry"       <?= ($filters['lead_type'] ?? '') === 'finance_enquiry'       ? 'selected' : '' ?>>Finance</option>
                <option value="charger_installation"  <?= ($filters['lead_type'] ?? '') === 'charger_installation'  ? 'selected' : '' ?>>Charger Install</option>
                <option value="fleet_enquiry"         <?= ($filters['lead_type'] ?? '') === 'fleet_enquiry'         ? 'selected' : '' ?>>Fleet</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">Date From</label>
            <input type="date" name="date_from" value="<?= esc($filters['date_from'] ?? '') ?>"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">Date To</label>
            <input type="date" name="date_to" value="<?= esc($filters['date_to'] ?? '') ?>"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">Search</label>
            <input type="text" name="q" value="<?= esc($filters['q'] ?? '') ?>"
                   placeholder="Name, mobile, email..."
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Apply</button>
            <a href="/admin/leads" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Clear</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Contact</th>
                    <th class="px-4 py-3 text-left">City</th>
                    <th class="px-4 py-3 text-left">Vehicle</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-left">Source</th>
                    <th class="px-4 py-3 text-left">Created</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach (($leads ?? []) as $lead): ?>
                <tr class="hover:bg-slate-50" x-data="{ statusOpen: false }">
                    <td class="px-4 py-3 text-xs text-slate-400">#<?= esc($lead['id']) ?></td>
                    <td class="px-4 py-3 text-xs text-slate-600">
                        <?= esc(ucwords(str_replace('_', ' ', $lead['lead_type']))) ?>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-900"><?= esc($lead['name']) ?></p>
                        <p class="text-xs text-slate-500"><?= esc($lead['mobile']) ?></p>
                        <?php if (!empty($lead['email'])): ?>
                            <p class="text-xs text-slate-400"><?= esc($lead['email']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-600"><?= esc($lead['city'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-xs text-slate-600">
                        <?= esc($lead['vehicle_name'] ?? '—') ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php $sc = $statusColors[$lead['status']] ?? 'bg-slate-100 text-slate-700'; ?>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $sc ?>">
                            <?= esc(ucfirst($lead['status'])) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs">
                        <?php $src = strtolower($lead['source'] ?? 'direct'); ?>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium <?= $sourceColors[$src] ?? 'bg-slate-100 text-slate-700' ?>">
                            <?= esc(ucfirst($lead['source'] ?? 'direct')) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">
                        <?= date('d M y, H:i', strtotime($lead['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/leads/<?= $lead['id'] ?>"
                               class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                View
                            </a>
                            <!-- Quick status update -->
                            <form method="post" action="/admin/leads/<?= $lead['id'] ?>/status">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="PATCH">
                                <select name="status" onchange="this.form.submit()"
                                        class="rounded-lg border border-slate-200 px-2 py-1.5 text-xs text-slate-700 focus:outline-none">
                                    <?php foreach (['new','contacted','qualified','converted','lost','closed'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($leads)): ?>
                <tr>
                    <td colspan="9" class="py-12 text-center text-sm text-slate-400">
                        No leads found matching your filters.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
    <div class="border-t border-slate-100 px-4 py-3">
        <?= $pager->links('default', 'admin_pager') ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
