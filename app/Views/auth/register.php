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

      <h1 class="text-2xl font-bold text-slate-900 text-center mb-1">Create your account</h1>
      <p class="text-sm text-slate-500 text-center mb-7">Join India's EV community — free forever</p>

      <form action="<?= site_url('register') ?>" method="post" novalidate
            x-data="{
              password: '',
              get strength() {
                if (this.password.length < 6) return 0;
                let s = 0;
                if (this.password.length >= 8) s++;
                if (/[A-Z]/.test(this.password)) s++;
                if (/[0-9]/.test(this.password)) s++;
                if (/[^A-Za-z0-9]/.test(this.password)) s++;
                return s;
              },
              get strengthLabel() {
                return ['','Weak','Fair','Good','Strong'][this.strength] || '';
              },
              get strengthColor() {
                return ['','bg-red-400','bg-amber-400','bg-blue-400','bg-green-500'][this.strength] || '';
              }
            }">
        <?= csrf_field() ?>

        <!-- Name -->
        <div class="mb-4">
          <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Full name</label>
          <input type="text" id="name" name="name" value="<?= esc(old('name')) ?>"
                 required autocomplete="name"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                 placeholder="Priya Sharma">
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
          <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>"
                 required autocomplete="email"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                 placeholder="you@example.com">
        </div>

        <!-- Phone -->
        <div class="mb-4">
          <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Mobile number</label>
          <input type="tel" id="phone" name="phone" value="<?= esc(old('phone')) ?>"
                 required autocomplete="tel"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                 placeholder="9876543210">
        </div>

        <!-- City -->
        <div class="mb-4">
          <label for="city" class="block text-sm font-semibold text-slate-700 mb-1.5">City <span class="font-normal text-slate-400">(optional)</span></label>
          <select id="city" name="city"
                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white">
            <option value="">Select your city</option>
            <?php foreach (['Mumbai','Delhi','Bangalore','Hyderabad','Chennai','Pune','Ahmedabad','Kolkata','Jaipur','Surat','Lucknow','Chandigarh','Kochi','Indore','Bhopal','Nagpur','Visakhapatnam','Coimbatore','Other'] as $c): ?>
            <option value="<?= $c ?>" <?= old('city') === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Password -->
        <div class="mb-6" x-data="{show:false}">
          <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
          <div class="relative">
            <input :type="show ? 'text' : 'password'" id="password" name="password"
                   required autocomplete="new-password" minlength="8"
                   x-model="password"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition pr-12"
                   placeholder="Min. 8 characters">
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
          <!-- Strength bar -->
          <div x-show="password.length > 0" x-cloak class="mt-2">
            <div class="flex gap-1 mb-1">
              <template x-for="i in 4">
                <div class="h-1.5 flex-1 rounded-full transition-all duration-300"
                     :class="i <= strength ? strengthColor : 'bg-slate-100'"></div>
              </template>
            </div>
            <p class="text-xs text-slate-500">Password strength: <span class="font-semibold" x-text="strengthLabel"></span></p>
          </div>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full btn-primary justify-center !rounded-xl !py-3 !text-base">
          Create account
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </button>
      </form>

      <!-- Trust text -->
      <p class="mt-5 text-center text-xs text-slate-400 leading-relaxed">
        Free forever. No spam. No dealer calls without your consent.
      </p>

      <!-- Divider -->
      <div class="relative my-5">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
        <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400 font-medium">already have an account?</span></div>
      </div>

      <a href="<?= site_url('login') ?>"
         class="w-full flex items-center justify-center gap-2 rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-green-500 hover:text-green-700 transition">
        Sign in instead
      </a>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
