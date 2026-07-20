<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm pt-24 md:pt-32 pb-8 px-4 relative overflow-hidden" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4,#F7FFFE)">
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-4"
         style="background:rgba(0,168,150,.10);border:1px solid rgba(0,168,150,.25);color:#00A896">
      🗺️ Trip Range Checker
    </div>
    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black leading-tight mb-3" style="color:#0F172A">Can Your EV Make the Trip?</h1>
    <p class="text-sm sm:text-base lg:text-lg" style="color:#475569">Enter your route distance — we'll tell you if you'll make it on a single charge</p>
  </div>
</section>

<div class="px-4 py-8 lg:py-12" style="background:#F7FFFE">
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
      <div class="rounded-3xl shadow-xl p-6 sm:p-8 space-y-6 flex flex-col" style="background:#fff;border:1px solid #EAFFF4">
        <h2 class="text-lg font-black" style="color:#0F172A">Your Trip Details</h2>

        <!-- Input 1: Route distance -->
        <div>
          <label class="block text-sm font-semibold mb-2" style="color:#475569">Route distance (km)</label>
          <input
            type="number" min="1" max="2000"
            x-model.number="distance"
            placeholder="e.g. 280 for Delhi to Agra"
            class="w-full rounded-xl px-4 py-3 text-base font-semibold placeholder:font-normal focus:outline-none focus:ring-2 transition-all"
            style="border:1px solid #d7f5ec;color:#0F172A;--tw-ring-color:#00A896"
          >
          <!-- Quick route chips -->
          <div class="flex flex-wrap gap-2 mt-3">
            <template x-for="rt in [{l:'Delhi→Agra',km:220},{l:'Mumbai→Pune',km:155},{l:'Blr→Mysore',km:145},{l:'Chennai→Pondy',km:160},{l:'Hyd→Warangal',km:140}]" :key="rt.km">
              <button @click="setRoute(rt.km)"
                :style="distance==rt.km ? 'background:#00A896;color:#fff;border-color:#00A896' : 'background:#F0FFF9;border-color:#d7f5ec;color:#0F766E'"
                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold cursor-pointer transition-all" style="border:1px solid">
                <span x-text="rt.l"></span><span class="ml-1 opacity-70" x-text="rt.km + 'km'"></span>
              </button>
            </template>
          </div>
        </div>

        <!-- Input 2: Claimed Range -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold" style="color:#475569">Your EV's claimed range</label>
            <div class="flex items-center gap-1">
              <span class="text-lg font-bold" style="color:#00A896" x-text="claimedRange"></span>
              <span class="text-xs" style="color:#94A3B8">km</span>
            </div>
          </div>
          <input
            type="range" min="40" max="600" step="5"
            x-model.number="claimedRange"
            class="w-full h-2 rounded-full cursor-pointer" style="accent-color:#00A896"
          >
          <div class="flex justify-between text-xs mt-1" style="color:#94A3B8">
            <span>40 km</span><span>600 km</span>
          </div>
        </div>

        <!-- Input 3: Driving style -->
        <div>
          <label class="block text-sm font-semibold mb-3" style="color:#475569">Driving style</label>
          <div class="grid grid-cols-3 gap-2">
            <label :style="style==='city' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col items-center gap-1 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="style" value="city" class="sr-only">
              <span class="text-2xl">🏙️</span>
              <span class="text-xs font-semibold" style="color:#0F172A">City</span>
              <span class="text-xs" style="color:#94A3B8">~70% real</span>
            </label>
            <label :style="style==='highway' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col items-center gap-1 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="style" value="highway" class="sr-only">
              <span class="text-2xl">🛣️</span>
              <span class="text-xs font-semibold" style="color:#0F172A">Highway</span>
              <span class="text-xs" style="color:#94A3B8">~82% real</span>
            </label>
            <label :style="style==='mixed' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col items-center gap-1 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="style" value="mixed" class="sr-only">
              <span class="text-2xl">🔀</span>
              <span class="text-xs font-semibold" style="color:#0F172A">Mixed</span>
              <span class="text-xs" style="color:#94A3B8">~75% real</span>
            </label>
          </div>
        </div>

        <!-- CTA (desktop: inside left card) -->
        <a href="<?= base_url('find-my-ev') ?>" class="hidden lg:flex items-center justify-center gap-2 w-full py-3 rounded-2xl text-white text-sm font-semibold transition-all mt-auto"
           style="background:linear-gradient(135deg,#00A896,#007A6E)"
           onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 18px rgba(0,168,150,.3)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
          Find the right EV for your range
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

      <!-- ── RIGHT: Result ── -->
      <div class="mt-6 lg:mt-0 lg:flex lg:flex-col">

        <!-- Result when input present -->
        <div x-show="hasResult" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="rounded-3xl shadow-xl p-6 sm:p-8 space-y-5 flex-1 flex flex-col justify-center" style="background:#fff;border:1px solid #EAFFF4">

          <!-- Big YES/NO -->
          <div class="text-center">
            <div
              :style="canMakeIt ? 'background:#EAFFF4;box-shadow:0 0 0 4px rgba(0,168,150,.12)' : 'background:#FFFBEB;box-shadow:0 0 0 4px rgba(245,158,11,.12)'"
              class="inline-flex flex-col items-center justify-center w-36 h-36 rounded-full"
            >
              <div :style="canMakeIt ? 'color:#007A6E' : 'color:#B45309'" class="text-5xl font-extrabold" x-text="canMakeIt ? 'YES' : 'NO'"></div>
              <div :style="canMakeIt ? 'color:#00A896' : 'color:#D97706'" class="text-xs font-semibold mt-1" x-text="canMakeIt ? 'you can!' : 'charge needed'"></div>
            </div>
          </div>

          <!-- Real range info -->
          <div :style="canMakeIt ? 'background:#F0FFF9;border:1px solid #d7f5ec' : 'background:#FFFBEB;border:1px solid #FDE68A'" class="rounded-2xl p-4">
            <p class="text-sm font-semibold text-center" style="color:#334155">
              Your EV can realistically do <span :style="canMakeIt ? 'color:#007A6E' : 'color:#B45309'" class="font-extrabold" x-text="realRange + ' km'"></span>
            </p>
            <p class="text-xs text-center mt-1" style="color:#64748B" x-text="'(' + factorPct + '% of ' + claimedRange + ' km claimed range in Indian conditions)'"></p>
          </div>

          <!-- Charging stop info -->
          <div x-show="!canMakeIt" class="rounded-2xl p-4 text-center" style="background:#FFFBEB;border:1px solid #FDE68A">
            <p class="text-sm font-semibold" style="color:#92400E">
              You'll need <span class="font-extrabold" x-text="stopsNeeded"></span> charging stop<span x-show="stopsNeeded > 1">s</span>
            </p>
            <p class="text-xs mt-1" style="color:#B45309">Charge ~45 mins at a fast charger to continue</p>
          </div>

          <!-- Stats grid -->
          <div class="grid grid-cols-2 gap-3">
            <div x-show="canMakeIt" class="rounded-2xl p-4 text-center" style="background:#F5FFF7">
              <div class="text-xs mb-1" style="color:#94A3B8">Range remaining after trip</div>
              <div class="text-2xl font-bold" style="color:#0F172A" x-text="remainder + ' km'"></div>
            </div>
            <div x-show="!canMakeIt" class="rounded-2xl p-4 text-center" style="background:#FFFBEB;border:1px solid #FEF3C7">
              <div class="text-xs mb-1" style="color:#D97706">Shortfall</div>
              <div class="text-2xl font-bold" style="color:#B45309" x-text="shortfall + ' km'"></div>
            </div>
            <div class="rounded-2xl p-4 text-center" style="background:#F5FFF7">
              <div class="text-xs mb-1" style="color:#94A3B8">Real-world efficiency</div>
              <div class="text-2xl font-bold" style="color:#0F172A" x-text="factorPct + '%'"></div>
            </div>
          </div>

          <!-- Tip -->
          <div class="flex items-start gap-3 rounded-2xl p-4" style="background:rgba(0,168,150,.06);border:1px solid rgba(0,168,150,.15)">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs font-medium" style="color:#007A6E" x-text="'Real-world range is ~' + factorPct + '% of ARAI claimed range in Indian driving conditions.'"></p>
          </div>
        </div>

        <!-- Placeholder when no input -->
        <div x-show="!hasResult" class="rounded-3xl p-10 text-center flex flex-col items-center justify-center flex-1 min-h-[280px]" style="background:#F0FFF9;border:1.5px dashed #b8ebdd">
          <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:rgba(0,168,150,.12)">
            <svg class="w-7 h-7" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          </div>
          <p class="font-black text-base mb-1.5" style="color:#0F172A">Enter a distance to see your result</p>
          <p class="text-sm" style="color:#64748B">Type a route distance on the left, or pick a popular route above.</p>
        </div>

        <!-- Mobile CTA -->
        <a href="<?= base_url('find-my-ev') ?>" class="lg:hidden flex items-center justify-center gap-2 w-full mt-4 py-3 rounded-2xl text-white text-sm font-semibold transition-all"
           style="background:linear-gradient(135deg,#00A896,#007A6E)">
          Find the right EV for your range
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
