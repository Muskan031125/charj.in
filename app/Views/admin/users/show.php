<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$breadcrumbs = ['Users' => '/admin/users', esc($user['name'] ?? 'User') => null];
?>

<!-- Page header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="/admin/users" class="text-sm text-slate-500 hover:text-emerald-600">&larr; Back to Users</a>
        <h1 class="mt-1 text-2xl font-black text-slate-900"><?= esc($user['name'] ?? 'Unknown User') ?></h1>
    </div>
    <a href="/admin/users/delete/<?= $user['id'] ?>"
       onclick="return confirm('Delete this user? This cannot be undone.')"
       class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100">
        Delete User
    </a>
</div>

<div class="grid gap-6 lg:grid-cols-3">

    <!-- Profile card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex flex-col items-center text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-2xl font-black text-indigo-600">
                    <?= esc(strtoupper(mb_substr($user['name'] ?? '?', 0, 1))) ?>
                </div>
                <h2 class="mt-3 text-lg font-bold text-slate-900"><?= esc($user['name'] ?? '—') ?></h2>
                <p class="text-sm text-slate-500"><?= esc($user['email'] ?? '—') ?></p>
            </div>

            <div class="mt-5 space-y-3 border-t border-slate-100 pt-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Phone</span>
                    <span class="font-medium text-slate-900"><?= esc($user['phone'] ?? $user['mobile'] ?? '—') ?></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">City</span>
                    <span class="font-medium text-slate-900"><?= esc($user['city'] ?? '—') ?></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">State</span>
                    <span class="font-medium text-slate-900"><?= esc($user['state'] ?? '—') ?></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Joined</span>
                    <span class="font-medium text-slate-900"><?= isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '—' ?></span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                <div class="rounded-lg bg-indigo-50 p-3 text-center">
                    <p class="text-xs text-indigo-600 font-semibold">Activities</p>
                    <p class="mt-0.5 text-2xl font-black text-indigo-900"><?= count($activity) ?></p>
                </div>
                <div class="rounded-lg bg-emerald-50 p-3 text-center">
                    <p class="text-xs text-emerald-600 font-semibold">Views</p>
                    <p class="mt-0.5 text-2xl font-black text-emerald-900"><?= (int)$savedCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity timeline -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-bold text-slate-900">Recent Activity</h2>
                <p class="text-xs text-slate-400">Last 20 actions</p>
            </div>

            <?php if (!empty($activity)): ?>
            <ul class="divide-y divide-slate-100">
                <?php foreach ($activity as $act): ?>
                <li class="flex items-start gap-3 px-5 py-3">
                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-700">
                            <span class="font-medium"><?= esc(ucwords(str_replace('_', ' ', $act['action'] ?? ''))) ?></span>
                            <?php if (!empty($act['meta'])): ?>
                                <span class="text-slate-500"> — <?= esc($act['meta']) ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="text-xs text-slate-400"><?= isset($act['created_at']) ? date('d M Y, g:i a', strtotime($act['created_at'])) : '' ?></p>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <div class="py-12 text-center">
                <p class="text-sm text-slate-400">No activity recorded for this user.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
