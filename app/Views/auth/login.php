<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-24">
  <div class="w-full max-w-sm">

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-lg p-8">

      <!-- Logo -->
      <div class="flex justify-center mb-6">
        <a href="<?= base_url() ?>" class="flex items-center gap-2 font-black text-xl">
          <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-600 text-white">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
          </span>
          <span class="text-slate-900">charj<span class="text-green-500">.in</span></span>
        </a>
      </div>

      <h1 class="text-2xl font-bold text-slate-900 text-center mb-1">Welcome back</h1>
      <p class="text-sm text-slate-500 text-center mb-7">Sign in to your Charj.in account</p>

      <form action="<?= site_url('login') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
          <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>"
                 required autocomplete="email"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                 placeholder="you@example.com">
        </div>

        <!-- Password with show/hide -->
        <div class="mb-2" x-data="{show:false}">
          <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
          <div class="relative">
            <input :type="show ? 'text' : 'password'" id="password" name="password"
                   required autocomplete="current-password"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition pr-12"
                   placeholder="••••••••">
            <button type="button" @click="show=!show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
                    :aria-label="show ? 'Hide password' : 'Show password'">
              <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Forgot password -->
        <div class="flex justify-end mb-6">
          <a href="#" class="text-xs text-green-600 hover:text-green-700 font-medium">Forgot password?</a>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full btn-primary justify-center !rounded-xl !py-3 !text-base">
          Sign in
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </button>
      </form>

      <!-- Divider -->
      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
        <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400 font-medium">or</span></div>
      </div>

      <!-- Register link -->
      <a href="<?= site_url('register') ?>"
         class="w-full flex items-center justify-center gap-2 rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-green-500 hover:text-green-700 transition">
        Create a free account
      </a>
    </div>

    <!-- Demo creds -->
    <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200 px-5 py-4">
      <p class="text-xs font-bold text-amber-700 mb-1">Demo credentials</p>
      <p class="text-xs text-amber-600 font-mono">Email: demo@charj.in</p>
      <p class="text-xs text-amber-600 font-mono">Password: Demo@2024!</p>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
