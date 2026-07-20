<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm pt-24 md:pt-32 pb-8 px-4 relative overflow-hidden" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4,#F7FFFE)">
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-5"
         style="background:rgba(0,168,150,.10);border:1px solid rgba(0,168,150,.25);color:#00A896">
      📈 Resale Value Estimator
    </div>
    <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-3" style="color:#0F172A">What Will Your EV<br>Be Worth?</h1>
    <p class="text-base" style="color:#475569">Real EV depreciation data — estimate your resale value in seconds</p>
  </div>
</section>

<div class="px-4 py-10 lg:py-12" style="background:#F7FFFE">
  <div
    class="mx-auto max-w-lg lg:max-w-4xl"
    x-data="{
      price: 150000,
      years: 3,
      kmUsage: 'average',
      get kmPenalty() {
        if (this.kmUsage === 'high') return 0.02;
        if (this.kmUsage === 'vhigh') return 0.04;
        return 0;
      },
      get baseDepRates() { return [0.20, 0.12, 0.10, 0.08, 0.07, 0.07, 0.07] },
      get resaleValue() {
        let val = this.price;
        for (let i = 0; i < this.years; i++) {
          let dep = this.baseDepRates[i] + this.kmPenalty;
          val = val * (1 - dep);
        }
        return Math.round(val);
      },
      get totalDepPct() { return Math.round((1 - this.resaleValue / this.price) * 100) },
      get retainedPct() { return 100 - this.totalDepPct },
      get costToOwn() { return this.price - this.resaleValue },
      get petrolRetained() { return Math.max(0, this.retainedPct - 5) },
      get batteryWarn() { return this.years > 4 },
      fmtInr(n) { return '₹' + Math.round(n).toLocaleString('en-IN') },
      yearLabel(y) { return y + 'yr' }
    }"
  >
    <!-- Desktop: inputs left, result right. Mobile: stacked. -->
    <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-start">

      <!-- ═══ LEFT: Inputs ═══ -->
      <div class="rounded-3xl shadow-xl p-6 sm:p-8 space-y-6" style="background:#fff;border:1px solid #EAFFF4">
        <h2 class="text-lg font-black" style="color:#0F172A">Your EV</h2>

        <!-- Input 1: On-road price -->
        <div>
          <label class="block text-sm font-semibold mb-2" style="color:#475569">On-road price (₹)</label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-semibold" style="color:#94A3B8">₹</span>
            <input
              type="number" min="50000" max="10000000" step="5000"
              x-model.number="price"
              class="w-full rounded-xl pl-8 pr-4 py-3 text-base font-semibold focus:outline-none focus:ring-2 transition-all"
              style="border:1px solid #d7f5ec;color:#0F172A;--tw-ring-color:#00A896"
            >
          </div>
          <!-- Quick price chips -->
          <div class="flex flex-wrap gap-2 mt-3">
            <template x-for="p in [85000,150000,1500000,2500000]" :key="p">
              <button @click="price=p"
                :style="price==p ? 'background:#00A896;color:#fff;border-color:#00A896' : 'background:#F0FFF9;border-color:#d7f5ec;color:#0F766E'"
                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold cursor-pointer transition-all" style="border:1px solid"
                x-text="p >= 100000 ? '₹' + (p/100000) + 'L' : '₹' + p.toLocaleString('en-IN')"></button>
            </template>
          </div>
        </div>

        <!-- Input 2: Years -->
        <div>
          <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-semibold" style="color:#475569">Years you'll own it</label>
            <span class="text-lg font-bold" style="color:#00A896" x-text="years + ' yr' + (years > 1 ? 's' : '')"></span>
          </div>
          <input
            type="range" min="1" max="7" step="1"
            x-model.number="years"
            class="w-full h-2 rounded-full cursor-pointer" style="accent-color:#00A896"
          >
          <div class="flex justify-between text-xs mt-2 px-0.5" style="color:#94A3B8">
            <span>1yr</span><span>2yr</span><span>3yr</span><span>4yr</span><span>5yr</span><span>6yr</span><span>7yr</span>
          </div>
        </div>

        <!-- Input 3: Annual km driven -->
        <div>
          <label class="block text-sm font-semibold mb-3" style="color:#475569">Annual km driven</label>
          <div class="grid grid-cols-2 gap-2">
            <label :style="kmUsage==='low' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="kmUsage" value="low" class="sr-only">
              <span class="text-xs font-bold" style="color:#0F172A">Low</span>
              <span class="text-xs" style="color:#94A3B8">5,000 km/yr</span>
            </label>
            <label :style="kmUsage==='average' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="kmUsage" value="average" class="sr-only">
              <span class="text-xs font-bold" style="color:#0F172A">Average</span>
              <span class="text-xs" style="color:#94A3B8">10,000 km/yr</span>
            </label>
            <label :style="kmUsage==='high' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="kmUsage" value="high" class="sr-only">
              <span class="text-xs font-bold" style="color:#0F172A">High</span>
              <span class="text-xs" style="color:#94A3B8">15,000 km/yr</span>
            </label>
            <label :style="kmUsage==='vhigh' ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all">
              <input type="radio" x-model="kmUsage" value="vhigh" class="sr-only">
              <span class="text-xs font-bold" style="color:#0F172A">Very High</span>
              <span class="text-xs" style="color:#94A3B8">20,000+ km/yr</span>
            </label>
          </div>
        </div>
      </div>

      <!-- ═══ RIGHT: Result ═══ -->
      <div class="mt-6 lg:mt-0 rounded-3xl shadow-xl p-6 sm:p-8 space-y-5" style="background:#fff;border:1px solid #EAFFF4">

        <!-- Result: Resale value -->
        <div class="text-center">
          <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#94A3B8">Estimated resale value</p>
          <div class="text-4xl font-extrabold" style="color:#007A6E" x-text="fmtInr(resaleValue)"></div>
          <p class="text-sm mt-1" style="color:#64748B">
            <span class="font-semibold" style="color:#334155" x-text="totalDepPct + '%'"></span> depreciation over
            <span x-text="years"></span> year<span x-show="years > 1">s</span>
          </p>
        </div>

        <!-- Value retention bar -->
        <div>
          <div class="flex justify-between text-xs font-semibold mb-1.5" style="color:#475569">
            <span>Value retained</span>
            <span x-text="retainedPct + '%'"></span>
          </div>
          <div class="h-3 rounded-full overflow-hidden" style="background:#EAFFF4">
            <div
              class="h-full rounded-full transition-all duration-500" style="background:#00A896"
              :style="'width: ' + retainedPct + '%'"
            ></div>
          </div>
          <div class="flex justify-between text-xs mt-1" style="color:#94A3B8">
            <span>0%</span><span>100%</span>
          </div>
        </div>

        <!-- Comparison cards -->
        <div class="grid grid-cols-2 gap-3">
          <div class="rounded-2xl p-4 text-center" style="background:#F5FFF7">
            <div class="text-xs font-medium mb-1" style="color:#94A3B8">Cost to own</div>
            <div class="text-lg font-bold" style="color:#0F172A" x-text="fmtInr(costToOwn)"></div>
            <div class="text-xs mt-1" style="color:#94A3B8" x-text="'over ' + years + ' yr' + (years>1?'s':'')"></div>
          </div>
          <div class="rounded-2xl p-4 text-center" style="background:#F0FFF9;border:1px solid #d7f5ec">
            <div class="text-xs font-medium mb-1" style="color:#00A896">vs Petrol retains</div>
            <div class="text-lg font-bold" style="color:#007A6E" x-text="petrolRetained + '%'"></div>
            <div class="text-xs mt-1" style="color:#00A896">EV holds ~5% more</div>
          </div>
        </div>

        <!-- Battery warning -->
        <div x-show="batteryWarn" x-transition class="flex items-start gap-3 rounded-2xl p-4" style="background:#FFFBEB;border:1px solid #FDE68A">
          <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:#D97706" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          <p class="text-xs font-medium" style="color:#92400E">Battery may need health check — get it tested before selling to protect your resale value.</p>
        </div>

        <!-- Pro tip -->
        <div class="flex items-start gap-3 rounded-2xl p-4" style="background:rgba(0,168,150,.06);border:1px solid rgba(0,168,150,.15)">
          <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          <p class="text-xs font-medium" style="color:#007A6E"><strong>Pro tip:</strong> A battery health certificate can increase your EV's resale value by 10–15%.</p>
        </div>

        <!-- CTA -->
        <a href="/tco-calculator" class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl text-white text-sm font-semibold transition-all"
           style="background:linear-gradient(135deg,#00A896,#007A6E)"
           onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 18px rgba(0,168,150,.3)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
          Calculate total ownership cost
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <p class="text-center text-xs" style="color:#94A3B8">Estimates based on average Indian EV market depreciation. Actual resale depends on brand, condition, and demand.</p>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
