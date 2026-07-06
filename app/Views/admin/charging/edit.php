<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php $page_title = 'Edit Charging Station'; ?>

<div class="mb-5 flex items-center gap-3">
    <a href="/admin/charging" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50">← Back</a>
    <h1 class="text-2xl font-black text-slate-900">Edit Charging Station</h1>
</div>

<form method="post" action="/admin/charging/update/<?= $station['id'] ?>">
    <?= csrf_field() ?>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="<?= esc(old('name', $station['name'])) ?>" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Operator</label>
                        <input type="text" name="operator" value="<?= esc(old('operator', $station['operator'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city" value="<?= esc(old('city', $station['city'])) ?>" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">State</label>
                        <input type="text" name="state" value="<?= esc(old('state', $station['state'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Address</label>
                    <input type="text" name="address" value="<?= esc(old('address', $station['address'] ?? '')) ?>"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Latitude</label>
                        <input type="number" step="any" name="latitude" value="<?= esc(old('latitude', $station['latitude'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Longitude</label>
                        <input type="number" step="any" name="longitude" value="<?= esc(old('longitude', $station['longitude'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Connector Types</label>
                        <input type="text" name="connector_types" value="<?= esc(old('connector_types', $station['connector_types'] ?? '')) ?>"
                               placeholder="AC, DC, CCS2..."
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Total Points</label>
                        <input type="number" name="total_points" value="<?= esc(old('total_points', $station['total_points'] ?? '')) ?>" min="1"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    <option value="active" <?= ($station['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($station['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="coming_soon" <?= ($station['status'] ?? '') === 'coming_soon' ? 'selected' : '' ?>>Coming Soon</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">Save</button>
                <a href="/admin/charging/delete/<?= $station['id'] ?>" onclick="return confirm('Delete?')"
                   class="rounded-xl border border-red-200 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50">Delete</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
