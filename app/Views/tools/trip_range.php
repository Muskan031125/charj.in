<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<section style="background:linear-gradient(135deg,#0f172a,#1a3a55,#0f2a1f)" class="text-white pt-20 sm:pt-24 lg:pt-28 pb-8 sm:pb-12 lg:pb-16 px-4 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:24px 24px"></div>
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 bg-green-500/20 border border-green-500/30 rounded-full px-4 py-1.5 text-green-300 text-xs font-bold uppercase tracking-widest mb-4">
      🗺️ Trip Range Checker
    </div>
    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black leading-tight mb-3">Can Your EV Make the Trip?</h1>
    <p class="text-slate-400 text-sm sm:text-base lg:text-lg">Enter your route distance — we'll tell you if you'll make it on a single charge</p>
  </div>
</section>

<div class="bg-slate-50 min-h-screen px-4 py-8 lg:py-12 -mt-4">
  <div
    class="mx-auto max-w-lg lg:max-w-4xl"
    x-data="{
      distance: '',
      claimedRange: 150,
      style: 'mixed',
      get factor() {
        if (this.style === 'city') return 0.70;
        if (this.style === 'highway') return 0.82;
        return 0.75;
      },
      get factorPct() { return Math.round(this.factor * 100) },
      get realRange() { return Math.round(this.claimedRange * this.factor) },
      get hasResult() { return this.distance !== '' && this.distance > 0 },
      get canMakeIt() { return this.distance <= this.realRange },
      get stopsNeeded() { return Math.ceil((this.distance - this.realRange) / this.realRange) },
      get remainder() { return this.realRange - this.distance },
      get shortfall() { return this.distance - this.realRange },
      setRoute(km) { this.distance = km }
    }"
  >
    <!-- Desktop: 2 columns. Mobile: single column -->
    <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-stretch">

      <!-- ── LEFT: Inputs ── -->
      <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-8 space-y-6 flex flex-col">
        <h2 class="text-lg font-black text-slate-900">Your Trip Details</h2>

        <!-- Input 1: Route distance -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Route distance (km)</label>
          <input
            type="number" min="1" max="2000"
            x-model.number="distance"
            placeholder="e.g. 280 for Delhi to Agra"
            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-800 text-base font-semibold placeholder:font-normal placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
          >
          <!-- Quick route chips -->
          <div class="flex flex-wrap gap-2 mt-3">
            <button @click="setRoute(220)" :class="distance==220 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all">Delhi→Agra <span class="ml-1 opacity-70">220km</span></button>
            <button @click="setRoute(155)" :class="distance==155 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all">Mumbai→Pune <span class="ml-1 opacity-70">155km</span></button>
            <button @click="setRoute(145)" :class="distance==145 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all">Blr→Mysore <span class="ml-1 opacity-70">145km</span></button>
            <button @click="setRoute(160)" :class="distance==160 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all">Chennai→Pondy <span class="ml-1 opacity-70">160km</span></button>
            <button @click="setRoute(140)" :class="distance==140 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all">Hyd→Warangal <span class="ml-1 opacity-70">140km</span></button>
          </div>
        </div>

        <!-- Input 2: Claimed Range -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold text-slate-700">Your EV's claimed range</label>
            <div class="flex items-center gap-1">
              <span class="text-lg font-bold text-green-700" x-text="claimedRange"></span>
              <span class="text-xs text-slate-400">km</span>
            </div>
          </div>
          <input
            type="range" min="40" max="600" step="5"
            x-model.number="claimedRange"
            class="w-full h-2 rounded-full accent-green-600 cursor-pointer"
          >
          <div class="flex justify-between text-xs text-slate-400 mt-1">
            <span>40 km</span><span>600 km</span>
          </div>
        </div>

        <!-- Input 3: Driving style -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-3">Driving style</label>
          <div class="grid grid-cols-3 gap-2">
            <label :class="style==='city' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col items-center gap-1 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="style" value="city" class="sr-only">
              <span class="text-2xl">🏙️</span>
              <span class="text-xs font-semibold text-slate-700">City</span>
              <span class="text-xs text-slate-400">~70% real</span>
            </label>
            <label :class="style==='highway' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col items-center gap-1 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="style" value="highway" class="sr-only">
              <span class="text-2xl">🛣️</span>
              <span class="text-xs font-semibold text-slate-700">Highway</span>
              <span class="text-xs text-slate-400">~82% real</span>
            </label>
            <label :class="style==='mixed' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col items-center gap-1 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="style" value="mixed" class="sr-only">
              <span class="text-2xl">🔀</span>
              <span class="text-xs font-semibold text-slate-700">Mixed</span>
              <span class="text-xs text-slate-400">~75% real</span>
            </label>
          </div>
        </div>

        <!-- CTA (desktop: inside left card) -->
        <a href="<?= base_url('find-my-ev') ?>" class="hidden lg:flex items-center justify-center gap-2 w-full py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-700 transition-colors mt-auto">
          Find the right EV for your range
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

      <!-- ── RIGHT: Result ── -->
      <div class="mt-6 lg:mt-0 lg:flex lg:flex-col">

        <!-- Result when input present -->
        <div x-show="hasResult" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-8 space-y-5 flex-1 flex flex-col justify-center">

          <!-- Big YES/NO -->
          <div class="text-center">
            <div
              :class="canMakeIt ? 'bg-green-50 ring-4 ring-green-100' : 'bg-amber-50 ring-4 ring-amber-100'"
              class="inline-flex flex-col items-center justify-center w-36 h-36 rounded-full"
            >
              <div :class="canMakeIt ? 'text-green-700' : 'text-amber-600'" class="text-5xl font-extrabold" x-text="canMakeIt ? 'YES' : 'NO'"></div>
              <div :class="canMakeIt ? 'text-green-600' : 'text-amber-500'" class="text-xs font-semibold mt-1" x-text="canMakeIt ? 'you can!' : 'charge needed'"></div>
            </div>
          </div>

          <!-- Real range info -->
          <div :class="canMakeIt ? 'bg-green-50 ring-1 ring-green-200' : 'bg-amber-50 ring-1 ring-amber-200'" class="rounded-2xl p-4">
            <p class="text-sm font-semibold text-slate-700 text-center">
              Your EV can realistically do <span :class="canMakeIt ? 'text-green-700' : 'text-amber-700'" class="font-extrabold" x-text="realRange + ' km'"></span>
            </p>
            <p class="text-xs text-slate-500 text-center mt-1" x-text="'(' + factorPct + '% of ' + claimedRange + ' km claimed range in Indian conditions)'"></p>
          </div>

          <!-- Charging stop info -->
          <div x-show="!canMakeIt" class="bg-amber-50 rounded-2xl p-4 text-center ring-1 ring-amber-200">
            <p class="text-sm font-semibold text-amber-800">
              You'll need <span class="font-extrabold" x-text="stopsNeeded"></span> charging stop<span x-show="stopsNeeded > 1">s</span>
            </p>
            <p class="text-xs text-amber-600 mt-1">Charge ~45 mins at a fast charger to continue</p>
          </div>

          <!-- Stats grid -->
          <div class="grid grid-cols-2 gap-3">
            <div x-show="canMakeIt" class="bg-slate-50 rounded-2xl p-4 text-center">
              <div class="text-xs text-slate-500 mb-1">Range remaining after trip</div>
              <div class="text-2xl font-bold text-slate-800" x-text="remainder + ' km'"></div>
            </div>
            <div x-show="!canMakeIt" class="bg-amber-50 rounded-2xl p-4 text-center ring-1 ring-amber-100">
              <div class="text-xs text-amber-600 mb-1">Shortfall</div>
              <div class="text-2xl font-bold text-amber-700" x-text="shortfall + ' km'"></div>
            </div>
            <div class="bg-slate-50 rounded-2xl p-4 text-center">
              <div class="text-xs text-slate-500 mb-1">Real-world efficiency</div>
              <div class="text-2xl font-bold text-slate-800" x-text="factorPct + '%'"></div>
            </div>
          </div>

          <!-- Tip -->
          <div class="flex items-start gap-3 bg-blue-50 rounded-2xl p-4 ring-1 ring-blue-100">
            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-blue-700 font-medium" x-text="'Real-world range is ~' + factorPct + '% of ARAI claimed range in Indian driving conditions.'"></p>
          </div>
        </div>

        <!-- Placeholder when no input -->
        <div x-show="!hasResult" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 text-center text-slate-300 flex flex-col items-center justify-center flex-1 min-h-[280px]">
          <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          <p class="text-sm font-medium text-slate-400">Enter a distance on the left to see your result</p>
          <p class="text-xs text-slate-300 mt-2">Or pick a popular route above</p>
        </div>

        <!-- Mobile CTA -->
        <a href="<?= base_url('find-my-ev') ?>" class="lg:hidden flex items-center justify-center gap-2 w-full mt-4 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-700 transition-colors">
          Find the right EV for your range
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
