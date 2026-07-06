<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php $page_title = 'Announcements'; ?>

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Announcements</h1>
        <p class="mt-0.5 text-sm text-slate-500"><?= number_format($total ?? 0) ?> total</p>
    </div>
    <a href="/admin/announcements/create"
       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Announcement
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="mb-4 rounded-xl bg-emerald-50 p-4 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">Type</th>
                <th class="px-4 py-3 text-center">Pinned</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-left">Published</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php if (empty($announcements)): ?>
            <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">No announcements yet. <a href="/admin/announcements/create" class="text-emerald-600 hover:underline">Create one</a>.</td></tr>
            <?php else: foreach ($announcements as $a): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-semibold text-slate-800 max-w-xs truncate">
                    <?= esc($a['title']) ?>
                    <?php if ($a['is_pinned']): ?><span class="ml-2 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">📌 Pinned</span><?php endif; ?>
                </td>
                <td class="px-4 py-3 capitalize text-slate-500"><?= esc($a['type'] ?? 'general') ?></td>
                <td class="px-4 py-3 text-center"><?= $a['is_pinned'] ? '✅' : '—' ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase <?= $a['status'] === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' ?>">
                        <?= esc($a['status']) ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-slate-400 text-xs"><?= $a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '—' ?></td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="/admin/announcements/edit/<?= $a['id'] ?>" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200">Edit</a>
                        <a href="/admin/announcements/delete/<?= $a['id'] ?>" onclick="return confirm('Delete this announcement?')"
                           class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<?php if (($totalPages ?? 1) > 1): ?>
<div class="mt-4 flex items-center justify-between text-sm text-slate-500">
    <span>Page <?= $page ?> of <?= $totalPages ?></span>
    <div class="flex gap-2">
        <?php if ($page > 1): ?><a href="?page=<?= $page-1 ?>" class="rounded-lg border px-3 py-1.5 hover:bg-slate-50">← Prev</a><?php endif; ?>
        <?php if ($page < $totalPages): ?><a href="?page=<?= $page+1 ?>" class="rounded-lg border px-3 py-1.5 hover:bg-slate-50">Next →</a><?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
