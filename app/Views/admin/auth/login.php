<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login – Charj.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4">

<div class="w-full max-w-md">

    <!-- Card -->
    <div class="rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200">

        <!-- Logo -->
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900">
                <svg class="h-8 w-8 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-slate-900">
                Charj<span class="text-emerald-500">.in</span>
                <span class="ml-2 rounded-full bg-slate-100 px-2.5 py-0.5 text-sm font-semibold text-slate-600">Admin</span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">Sign in to manage your EV marketplace</p>
        </div>

        <!-- Error message -->
        <?php if (session('error')): ?>
            <div class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p><?= esc(session('error')) ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="mb-5 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                <?php foreach ($errors as $e): ?>
                    <p><?= esc($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="post" action="/admin/login" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= esc(old('email')) ?>"
                    required
                    autocomplete="email"
                    placeholder="admin@charj.in"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 transition focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 transition focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <button
                type="submit"
                class="mt-2 w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-[0.98]">
                Sign in to Admin
            </button>
        </form>
    </div>

    <!-- Back to site -->
    <p class="mt-6 text-center text-sm text-slate-500">
        <a href="/" class="font-medium text-slate-700 hover:text-emerald-600">
            ← Back to Charj.in
        </a>
    </p>
</div>

</body>
</html>
