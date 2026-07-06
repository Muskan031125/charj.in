<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$page_title = 'Dashboard';

// Helper: format numbers
function fmtNum(int $n): string {
    return number_format($n);
}

// Time ago helper
function timeAgo(string $datetime): string {
    $ts  = strtotime($datetime);
    $diff = time() - $ts;
    if ($diff < 60)     return 'just now';
    if ($diff < 3600)   return floor($diff/60) . 'm ago';
    if ($diff < 86400)  return floor($diff/3600) . 'h ago';
    return floor($diff/86400) . 'd ago';
}

$statusColors = [
    'new'        => 'bg-blue-100 text-blue-800',
    'contacted'  => 'bg-amber-100 text-amber-800',
    'qualified'  => 'bg-purple-100 text-purple-800',
    'converted'  => 'bg-emerald-100 text-emerald-800',
    'lost'       => 'bg-red-100 text-red-800',
    'closed'     => 'bg-slate-100 text-slate-700',
];
?>

<!-- Page header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Dashboard</h1>
        <p class="mt-0.5 text-sm text-slate-500"><?= date('l, d F Y') ?></p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= site_url('admin/preview-as-customer') ?>"
           class="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View as Customer
        </a>
        <a href="<?= site_url('admin/leads?status=new') ?>"
           class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
            View New Leads
        </a>
    </div>
</div>

<?php if (!empty($pendingQA) && $pendingQA > 0): ?>
<div class="mb-5 flex items-center justify-between rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200">
    <div class="flex items-center gap-3">
        <svg class="h-5 w-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-medium text-amber-800">
            <span class="font-bold"><?= (int)$pendingQA ?></span> owner question<?= $pendingQA > 1 ? 's' : '' ?> pending moderation.
        </p>
    </div>
    <a href="/admin/qa" class="shrink-0 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600">
        Review Q&amp;A →
    </a>
</div>
<?php endif; ?>

<!-- Primary stats (4 cards) -->
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Leads</p>
        <p class="mt-2 text-4xl font-black text-slate-900"><?= fmtNum($totalLeads ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">All time</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">New Today</p>
        <p class="mt-2 text-4xl font-black text-emerald-600"><?= fmtNum($leadsToday ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">Leads received today</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Vehicles</p>
        <p class="mt-2 text-4xl font-black text-slate-900"><?= fmtNum($totalVehicles ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">Published + draft</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Dealers</p>
        <p class="mt-2 text-4xl font-black text-slate-900"><?= fmtNum($totalDealers ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">Active dealers</p>
    </div>
</div>

<!-- Extra stat cards row -->
<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Brands</p>
        <p class="mt-2 text-4xl font-black text-blue-600"><?= fmtNum($totalBrands ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">All brands</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Users</p>
        <p class="mt-2 text-4xl font-black text-indigo-600"><?= fmtNum($totalUsers ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">Registered users</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending Reviews</p>
        <p class="mt-2 text-4xl font-black text-amber-600"><?= fmtNum($pendingReviews ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">Awaiting moderation</p>
    </div>
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Articles</p>
        <p class="mt-2 text-4xl font-black text-teal-600"><?= fmtNum($totalArticles ?? 0) ?></p>
        <p class="mt-1 text-xs text-slate-400">Published + draft</p>
    </div>
</div>

<!-- Quick Stats row -->
<div class="mt-4">
    <h2 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Quick Stats</h2>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="flex items-center justify-between rounded-xl bg-emerald-50 px-4 py-3 ring-1 ring-emerald-100">
            <span class="text-sm font-medium text-emerald-700">Published Vehicles</span>
            <span class="text-lg font-black text-emerald-900"><?= fmtNum($publishedVehicles ?? 0) ?></span>
        </div>
        <div class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-3 ring-1 ring-amber-100">
            <span class="text-sm font-medium text-amber-700">Draft Vehicles</span>
            <span class="text-lg font-black text-amber-900"><?= fmtNum($draftVehicles ?? 0) ?></span>
        </div>
        <div class="flex items-center justify-between rounded-xl bg-purple-50 px-4 py-3 ring-1 ring-purple-100">
            <span class="text-sm font-medium text-purple-700">Featured EVs</span>
            <span class="text-lg font-black text-purple-900"><?= fmtNum($featuredEVs ?? 0) ?></span>
        </div>
        <div class="flex items-center justify-between rounded-xl bg-blue-50 px-4 py-3 ring-1 ring-blue-100">
            <span class="text-sm font-medium text-blue-700">Active Brands</span>
            <span class="text-lg font-black text-blue-900"><?= fmtNum($activeBrands ?? 0) ?></span>
        </div>
    </div>
</div>

<!-- Secondary stats (3 cards) -->
<div class="mt-4 grid gap-4 sm:grid-cols-3">
    <div class="flex items-center gap-4 rounded-2xl bg-indigo-50 p-4 ring-1 ring-indigo-100">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-indigo-700">This Week</p>
            <p class="text-2xl font-black text-indigo-900"><?= fmtNum($leadsThisWeek ?? 0) ?></p>
        </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl bg-amber-50 p-4 ring-1 ring-amber-100">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-amber-700">This Month</p>
            <p class="text-2xl font-black text-amber-900"><?= fmtNum($leadsThisMonth ?? 0) ?></p>
        </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl bg-teal-50 p-4 ring-1 ring-teal-100">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-600">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-teal-700">Articles</p>
            <p class="text-2xl font-black text-teal-900"><?= fmtNum($totalArticles ?? 0) ?></p>
        </div>
    </div>
</div>

<!-- Lead type chart + Recent leads -->
<div class="mt-6 grid gap-6 lg:grid-cols-5">

    <!-- Lead by type chart (CSS only, no JS library) -->
    <div class="lg:col-span-2">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-base font-bold text-slate-900">Leads by Type</h2>
            <p class="text-xs text-slate-400">All time breakdown</p>

            <div class="mt-4 space-y-3">
                <?php
                $leadsByType = $leadsByType ?? [];
                $totalLeadsChart = array_sum(array_column($leadsByType, 'count'));
                $typeLabels = [
                    'get_best_price'      => 'Best Price',
                    'book_test_ride'      => 'Test Ride',
                    'ev_recommendation'   => 'Recommendation',
                    'finance_enquiry'     => 'Finance',
                    'charger_installation'=> 'Charger Install',
                    'fleet_enquiry'       => 'Fleet/Commercial',
                ];
                $barColors = [
                    'get_best_price'       => 'bg-emerald-500',
                    'book_test_ride'       => 'bg-blue-500',
                    'ev_recommendation'    => 'bg-indigo-500',
                    'finance_enquiry'      => 'bg-amber-500',
                    'charger_installation' => 'bg-teal-500',
                    'fleet_enquiry'        => 'bg-purple-500',
                ];
                foreach ($leadsByType as $row):
                    $pct = $totalLeadsChart > 0 ? round(($row['count'] / $totalLeadsChart) * 100) : 0;
                    $label = $typeLabels[$row['lead_type']] ?? ucwords(str_replace('_', ' ', $row['lead_type']));
                    $color = $barColors[$row['lead_type']] ?? 'bg-slate-400';
                ?>
                <div>
                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-700"><?= esc($label) ?></span>
                        <span class="text-xs font-bold text-slate-900"><?= fmtNum($row['count']) ?> <span class="font-normal text-slate-400">(<?= $pct ?>%)</span></span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-2 rounded-full <?= $color ?> transition-all duration-500" style="width:<?= $pct ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($leadsByType)): ?>
                    <p class="py-4 text-center text-sm text-slate-400">No leads yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent leads table -->
    <div class="lg:col-span-3">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-bold text-slate-900">Recent Leads</h2>
                <a href="/admin/leads" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Type</th>
                            <th class="px-4 py-3 text-left">City</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">When</th>
                            <th class="px-4 py-3 text-left"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach (($recentLeads ?? []) as $lead): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-900"><?= esc($lead['name']) ?></p>
                                <p class="text-xs text-slate-400"><?= esc($lead['mobile']) ?></p>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                <?= esc(ucwords(str_replace('_', ' ', $lead['lead_type']))) ?>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600"><?= esc($lead['city'] ?? '—') ?></td>
                            <td class="px-4 py-3">
                                <?php $sc = $statusColors[$lead['status']] ?? 'bg-slate-100 text-slate-700'; ?>
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?= $sc ?>">
                                    <?= esc(ucfirst($lead['status'])) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400"><?= timeAgo($lead['created_at']) ?></td>
                            <td class="px-4 py-3">
                                <a href="/admin/leads/<?= $lead['id'] ?>" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View →</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentLeads)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-400">No leads yet. Share your Charj.in link to start collecting leads.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="mt-6">
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-bold text-slate-900">Recent Users</h2>
            <a href="/admin/users" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-xs font-bold uppercase text-slate-500">
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">City</th>
                        <th class="px-4 py-3 text-left">Joined</th>
                        <th class="px-4 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach (($recentUsers ?? []) as $u): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                    <?= esc(strtoupper(mb_substr($u['name'] ?? '?', 0, 1))) ?>
                                </div>
                                <span class="font-semibold text-slate-900"><?= esc($u['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600"><?= esc($u['email']) ?></td>
                        <td class="px-4 py-3 text-slate-500"><?= esc($u['city'] ?? '—') ?></td>
                        <td class="px-4 py-3 text-xs text-slate-400"><?= timeAgo($u['created_at']) ?></td>
                        <td class="px-4 py-3">
                            <a href="/admin/users/<?= $u['id'] ?>" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View →</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentUsers)): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-sm text-slate-400">No users registered yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick links -->
<div class="mt-6">
    <h2 class="mb-3 text-base font-bold text-slate-900">Quick Actions</h2>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <a href="/admin/vehicles/create"
           class="flex items-center gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition hover:ring-emerald-400">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Add Vehicle</span>
        </a>
        <a href="/admin/brands/create"
           class="flex items-center gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition hover:ring-emerald-400">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Add Brand</span>
        </a>
        <a href="/admin/dealers/create"
           class="flex items-center gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition hover:ring-emerald-400">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Add Dealer</span>
        </a>
        <a href="/admin/articles/create"
           class="flex items-center gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition hover:ring-emerald-400">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Add Article</span>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
