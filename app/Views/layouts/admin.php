<!doctype html>
<html lang="en" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($page_title ?? 'Admin') ?> – Charj Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">

<?php
function adminNavActive(string $prefix): string {
    $path = current_url(true)->getPath();
    return str_starts_with($path, $prefix) ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white';
}
?>

<!-- Mobile overlay -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
     class="fixed inset-0 z-20 bg-black/50 md:hidden"
     x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>

<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-slate-900 transition-transform duration-200 md:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center gap-2.5 border-b border-slate-800 px-5">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
            </svg>
        </div>
        <div>
            <span class="text-base font-black text-white">Charj.in</span>
            <span class="ml-1 text-xs font-normal text-emerald-400">Admin</span>
        </div>
    </div>

    <!-- Nav links -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

        <?php
        $navItems = [
            ['href' => '/admin/dashboard', 'label' => 'Dashboard', 'prefix' => '/admin/dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
            ['href' => '/admin/vehicles', 'label' => 'Vehicles', 'prefix' => '/admin/vehicles', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
            ['href' => '/admin/brands', 'label' => 'Brands', 'prefix' => '/admin/brands', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>'],
            ['href' => '/admin/dealers', 'label' => 'Dealers', 'prefix' => '/admin/dealers', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'],
            ['href' => '/admin/leads', 'label' => 'Leads', 'prefix' => '/admin/leads', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['href' => '/admin/users', 'label' => 'Users', 'prefix' => '/admin/users', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
            ['href' => '/admin/reviews', 'label' => 'Reviews', 'prefix' => '/admin/reviews', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
            ['href' => '/admin/articles', 'label' => 'Articles', 'prefix' => '/admin/articles', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
            ['href' => '/admin/spare-parts', 'label' => 'Spare Parts', 'prefix' => '/admin/spare-parts', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m6 2v-2m-11 15h16a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zm6-11h.01M7 20v-1a6 6 0 1112 0v1M7 20H4a2 2 0 01-2-2v-2a2 2 0 012-2h16a2 2 0 012 2v2a2 2 0 01-2 2h-3"/>'],
            ['href' => '/admin/events', 'label' => 'Events', 'prefix' => '/admin/events', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
            ['href' => '/admin/announcements', 'label' => 'Announcements', 'prefix' => '/admin/announcements', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.961 1.961 0 01-2.051 1.959H5.05C3.291 21.243 2 19.975 2 18.216V5.882c0-1.759 1.291-3.027 3.05-3.027h3.898c1.153.066 2.051 1.191 2.051 2.959zm10.798 0V19.24a1.961 1.961 0 01-2.051 1.959h-3.898c-1.759 0-3.05-1.268-3.05-3.027V5.882c0-1.759 1.291-3.027 3.05-3.027h3.898c1.153.066 2.051 1.191 2.051 2.959z"/>'],
            ['href' => '/admin/charging', 'label' => 'Charging Stations', 'prefix' => '/admin/charging', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>'],
            ['href' => '/admin/qa', 'label' => 'Owner Q&A', 'prefix' => '/admin/qa', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['href' => '/admin/subsidies', 'label' => 'Subsidies', 'prefix' => '/admin/subsidies', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>'],
            ['href' => '/admin/settings', 'label' => 'Settings', 'prefix' => '/admin/settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ];
        foreach ($navItems as $item):
        ?>
        <a href="<?= $item['href'] ?>"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors <?= adminNavActive($item['prefix']) ?>">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <?= $item['icon'] ?>
            </svg>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- Sidebar footer -->
    <div class="shrink-0 border-t border-slate-800 p-3 space-y-0.5">
        <a href="<?= site_url('admin/preview-as-customer') ?>"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-indigo-300 transition-colors hover:bg-slate-800 hover:text-indigo-100">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>View as Customer</span>
        </a>
        <a href="/" target="_blank" rel="noopener"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 transition-colors hover:bg-slate-800 hover:text-white">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span>View Site</span>
        </a>
    </div>
</aside>

<!-- Main wrapper -->
<div class="flex min-h-screen flex-col md:pl-64">

    <!-- Top bar -->
    <header class="sticky top-0 z-10 flex h-16 shrink-0 items-center gap-4 border-b bg-white px-4 shadow-sm md:px-6">
        <button @click="sidebarOpen=!sidebarOpen"
                class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden" aria-label="Toggle sidebar">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Breadcrumb -->
        <nav class="flex flex-1 items-center gap-1 truncate text-sm text-slate-500" aria-label="Breadcrumb">
            <a href="/admin/dashboard" class="shrink-0 hover:text-slate-900">Dashboard</a>
            <?php if (!empty($breadcrumbs)): ?>
                <?php foreach ($breadcrumbs as $label => $url): ?>
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    <?php if ($url): ?>
                        <a href="<?= esc($url) ?>" class="truncate hover:text-slate-900"><?= esc($label) ?></a>
                    <?php else: ?>
                        <span class="truncate font-medium text-slate-900"><?= esc($label) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php elseif (!empty($page_title)): ?>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="truncate font-medium text-slate-900"><?= esc($page_title) ?></span>
            <?php endif; ?>
        </nav>

        <!-- Admin info + logout -->
        <div class="flex shrink-0 items-center gap-3">
            <a href="<?= site_url('admin/preview-as-customer') ?>"
               class="hidden sm:flex items-center gap-1.5 rounded-lg bg-indigo-50 border border-indigo-200 px-3 py-1.5 text-sm font-medium text-indigo-700 transition-colors hover:bg-indigo-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View as Customer
            </a>
            <div class="hidden sm:flex sm:flex-col sm:items-end">
                <span class="text-sm font-semibold text-slate-800"><?= esc(session('admin_name') ?? 'Admin') ?></span>
                <span class="text-xs text-slate-400">Administrator</span>
            </div>
            <a href="/admin/logout"
               class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-red-600">
                Logout
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 md:p-6">

        <?php if (session('success')): ?>
            <div class="mb-5 flex items-start gap-3 rounded-xl bg-emerald-50 p-4 text-emerald-800 ring-1 ring-emerald-200">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium"><?= esc(session('success')) ?></p>
            </div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 p-4 text-red-800 ring-1 ring-red-200">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-sm font-medium"><?= esc(session('error')) ?></p>
            </div>
        <?php endif; ?>

        <?php if (session('errors')): ?>
            <div class="mb-5 rounded-xl bg-red-50 p-4 text-red-800 ring-1 ring-red-200">
                <p class="mb-2 text-sm font-semibold">Please fix the following errors:</p>
                <ul class="list-inside list-disc space-y-1 text-sm">
                    <?php foreach ((array)session('errors') as $field => $msg): ?>
                        <li><?= esc(is_array($msg) ? implode(', ', $msg) : $msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

</body>
</html>
