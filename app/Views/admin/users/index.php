<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-black text-slate-900">Users</h1>
    <span class="text-sm text-slate-500"><?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?> found</span>
</div>

<!-- Search bar -->
<div class="mb-5">
    <form method="get" action="/admin/users" class="flex gap-3">
        <input
            type="text"
            name="q"
            value="<?= esc($search ?? '') ?>"
            placeholder="Search by name or email…"
            class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
        >
        <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Search</button>
        <?php if ($search): ?>
            <a href="/admin/users" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Users table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left">User</th>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left">Email</th>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left">Phone</th>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left">City</th>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left">Joined</th>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left">Activity</th>
                    <th class="text-xs font-bold uppercase text-slate-500 px-4 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-slate-50">
                    <td class="text-sm text-slate-700 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                <?= esc(strtoupper(mb_substr($u['name'] ?? '?', 0, 1))) ?>
                            </div>
                            <span class="font-semibold text-slate-900"><?= esc($u['name'] ?? '—') ?></span>
                        </div>
                    </td>
                    <td class="text-sm text-slate-700 px-4 py-3"><?= esc($u['email'] ?? '—') ?></td>
                    <td class="text-sm text-slate-700 px-4 py-3"><?= esc($u['phone'] ?? $u['mobile'] ?? '—') ?></td>
                    <td class="text-sm text-slate-700 px-4 py-3"><?= esc($u['city'] ?? '—') ?></td>
                    <td class="text-sm text-slate-500 px-4 py-3"><?= isset($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '—' ?></td>
                    <td class="text-sm text-slate-700 px-4 py-3">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                            <?= (int)($u['activity_count'] ?? 0) ?> actions
                        </span>
                    </td>
                    <td class="text-sm px-4 py-3">
                        <a href="/admin/users/<?= $u['id'] ?>" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">View →</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" class="py-12 text-center text-sm text-slate-400">
                        <?= $search ? 'No users match your search.' : 'No users registered yet.' ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
