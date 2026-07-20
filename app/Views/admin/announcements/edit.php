<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php $page_title = 'Edit Announcement'; ?>

<div class="mb-5 flex items-center gap-3">
    <a href="/admin/announcements" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50">← Back</a>
    <h1 class="text-2xl font-black text-slate-900">Edit Announcement</h1>
</div>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>
<?php if (!empty($errors)): ?>
<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
    <ul class="list-disc pl-4 space-y-1"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="/admin/announcements/update/<?= $announcement['id'] ?>">
    <?= csrf_field() ?>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        <div class="lg:col-span-2 space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Content</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="<?= esc(old('title', $announcement['title'])) ?>" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Slug</label>
                        <input type="text" name="slug" value="<?= esc(old('slug', $announcement['slug'])) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none font-mono">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Content <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="8" required
                                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"><?= esc(old('content', $announcement['content'])) ?></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Banner Image URL</label>
                        <input type="url" name="banner_image" value="<?= esc(old('banner_image', $announcement['banner_image'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Link URL</label>
                        <input type="url" name="link_url" value="<?= esc(old('link_url', $announcement['link_url'] ?? '')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Settings</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Type</label>
                        <select name="type" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <?php foreach (['general','launch','policy','offer','event','maintenance'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($announcement['type'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
                        <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <option value="draft" <?= $announcement['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= $announcement['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" name="is_pinned" id="is_pinned" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600"
                               <?= !empty($announcement['is_pinned']) ? 'checked' : '' ?>>
                        <label for="is_pinned" class="text-sm font-medium text-slate-700">Pin to top</label>
                    </div>
                    <?php if (!empty($announcement['published_at'])): ?>
                    <p class="text-xs text-slate-400">Published: <?= date('d M Y H:i', strtotime($announcement['published_at'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">Save Changes</button>
                <a href="/admin/announcements/delete/<?= $announcement['id'] ?>" onclick="return confirm('Delete?')"
                   class="rounded-xl border border-red-200 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50">Delete</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
