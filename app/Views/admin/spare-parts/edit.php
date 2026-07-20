<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php $page_title = 'Edit Spare Part'; ?>

<div class="mb-5 flex items-center gap-3">
    <a href="/admin/spare-parts" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50">← Back</a>
    <h1 class="text-2xl font-black text-slate-900">Edit Spare Part</h1>
</div>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>
<?php if (!empty($errors)): ?>
<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
    <ul class="list-disc pl-4 space-y-1"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="/admin/spare-parts/update/<?= $sparePart['id'] ?>">
    <?= csrf_field() ?>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        <div class="lg:col-span-2 space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Part Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Part Name <span class="text-red-500">*</span></label>
                        <input type="text" name="part_name" value="<?= esc(old('part_name', $sparePart['part_name'])) ?>" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Slug</label>
                        <input type="text" name="slug" value="<?= esc(old('slug', $sparePart['slug'])) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none font-mono">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required
                                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"><?= esc(old('description', $sparePart['description'])) ?></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Part Number</label>
                            <input type="text" name="part_number" value="<?= esc(old('part_number', $sparePart['part_number'] ?? '')) ?>"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none font-mono">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Price (₹)</label>
                            <input type="number" name="price" value="<?= esc(old('price', $sparePart['price'] ?? '')) ?>" step="0.01" min="0"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Compatible Models</label>
                        <input type="text" name="compatible_models" value="<?= esc(old('compatible_models', $sparePart['compatible_models'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Image URL</label>
                        <input type="url" name="image_url" value="<?= esc(old('image_url', $sparePart['image_url'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Vendor Info</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Vendor Name</label>
                            <input type="text" name="vendor_name" value="<?= esc(old('vendor_name', $sparePart['vendor_name'] ?? '')) ?>"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Vendor Contact</label>
                            <input type="text" name="vendor_contact" value="<?= esc(old('vendor_contact', $sparePart['vendor_contact'] ?? '')) ?>"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Vendor URL</label>
                        <input type="url" name="vendor_url" value="<?= esc(old('vendor_url', $sparePart['vendor_url'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Settings</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Category</label>
                        <select name="category" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <?php foreach (['battery','charger','motor','controller','tyre','brake','body','suspension','lighting','other'] as $c): ?>
                            <option value="<?= $c ?>" <?= ($sparePart['category'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
                        <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <option value="draft" <?= $sparePart['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= $sparePart['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" name="in_stock" id="in_stock" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600"
                               <?= !empty($sparePart['in_stock']) ? 'checked' : '' ?>>
                        <label for="in_stock" class="text-sm font-medium text-slate-700">In Stock</label>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">Save Changes</button>
                <a href="/admin/spare-parts/delete/<?= $sparePart['id'] ?>" onclick="return confirm('Delete?')"
                   class="rounded-xl border border-red-200 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50">Delete</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
