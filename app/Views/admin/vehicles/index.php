<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $page_title = 'Vehicles'; ?>

<!-- Page header -->
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Vehicles</h1>
        <p class="mt-0.5 text-sm text-slate-500"><?= number_format($totalCount ?? 0) ?> vehicles total</p>
    </div>
    <a href="/admin/vehicles/create"
       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Add Vehicle
    </a>
</div>

<!-- Search form -->
<div class="mb-5 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
    <form method="get" class="flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label class="mb-1 block text-xs font-semibold text-slate-600">Search</label>
            <input type="text" name="q" value="<?= esc($search ?? '') ?>"
                   placeholder="Vehicle name, brand..."
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none">
                <option value="">All statuses</option>
                <option value="published" <?= ($filterStatus ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft"     <?= ($filterStatus ?? '') === 'draft'     ? 'selected' : '' ?>>Draft</option>
                <option value="discontinued" <?= ($filterStatus ?? '') === 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Search</button>
            <a href="/admin/vehicles" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Clear</a>
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
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Brand</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-right">Price</th>
                    <th class="px-4 py-3 text-right">Range</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Featured</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach (($vehicles ?? []) as $v): ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-xs text-slate-400">#<?= esc($v['id']) ?></td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-900"><?= esc($v['name']) ?></p>
                        <?php if (!empty($v['slug'])): ?>
                            <p class="text-xs text-slate-400">/ev/<?= esc($v['slug']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-700"><?= esc($v['brand_name'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-slate-700"><?= esc($v['category_name'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-right text-slate-700">
                        <?php if (!empty($v['starting_price'])): ?>
                            ₹<?= number_format((float)$v['starting_price'] / 100000, 2) ?> L
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-right text-slate-700">
                        <?= !empty($v['claimed_range']) ? esc($v['claimed_range']) . ' km' : '—' ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php
                        $badge = match($v['status'] ?? 'draft') {
                            'published'    => 'bg-emerald-100 text-emerald-800',
                            'discontinued' => 'bg-red-100 text-red-800',
                            default        => 'bg-amber-100 text-amber-800',
                        };
                        ?>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $badge ?>">
                            <?= esc(ucfirst($v['status'] ?? 'draft')) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if (!empty($v['is_featured'])): ?>
                            <span title="Featured" class="text-lg">⭐</span>
                        <?php else: ?>
                            <span class="text-slate-300">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/vehicles/<?= $v['id'] ?>/edit"
                               class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                                Edit
                            </a>
                            <form method="post" action="/admin/vehicles/<?= $v['id'] ?>/delete"
                                  onsubmit="return confirm('Delete <?= esc(addslashes($v['name'])) ?>? This cannot be undone.')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                        class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($vehicles)): ?>
                <tr>
                    <td colspan="9" class="py-12 text-center text-sm text-slate-400">
                        No vehicles found.
                        <a href="/admin/vehicles/create" class="ml-2 font-medium text-emerald-600">Add your first vehicle →</a>
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
