<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $page_title = 'Subsidies'; ?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Subsidies</h1>
        <p class="mt-0.5 text-sm text-slate-500">Manage state & central EV subsidies</p>
    </div>
    <a href="/admin/subsidies/create"
       class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
        + Add Subsidy
    </a>
</div>

<?php if (empty($subsidies)): ?>
    <div class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-400">No subsidies found. Add one to get started.</p>
        <a href="/admin/subsidies/create" class="mt-3 inline-block text-sm font-medium text-emerald-600 hover:underline">Add your first subsidy →</a>
    </div>
<?php else: ?>
<div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3 text-left">State</th>
                <th class="px-4 py-3 text-left">Vehicle Type</th>
                <th class="px-4 py-3 text-left">Scheme</th>
                <th class="px-4 py-3 text-left">Amount (₹)</th>
                <th class="px-4 py-3 text-left">Valid Until</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($subsidies as $sub): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-semibold text-slate-800"><?= esc($sub['state']) ?></td>
                <td class="px-4 py-3 text-slate-600"><?= esc($sub['vehicle_type'] ?? '—') ?></td>
                <td class="px-4 py-3 text-slate-700"><?= esc($sub['scheme_name'] ?? '—') ?></td>
                <td class="px-4 py-3 text-slate-700">₹<?= number_format((int)($sub['amount'] ?? 0)) ?></td>
                <td class="px-4 py-3 text-xs text-slate-500">
                    <?= $sub['valid_until'] ? date('d M Y', strtotime($sub['valid_until'])) : '—' ?>
                </td>
                <td class="px-4 py-3">
                    <?php if ($sub['is_active']): ?>
                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">Active</span>
                    <?php else: ?>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">Inactive</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <a href="/admin/subsidies/delete/<?= $sub['id'] ?>"
                       onclick="return confirm('Delete this subsidy?')"
                       class="rounded-lg bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 hover:bg-red-200">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
