<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Dark hero -->
<section style="background:linear-gradient(135deg,#0f172a,#3b1a5f,#0f2a1f)" class="text-white pt-28 pb-16 px-4 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:24px 24px"></div>
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 bg-purple-500/20 border border-purple-400/30 rounded-full px-4 py-1.5 text-purple-300 text-xs font-bold uppercase tracking-widest mb-5">
      📈 Resale Value Estimator
    </div>
    <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-3">What Will Your EV<br>Be Worth?</h1>
    <p class="text-slate-400 text-base">Real EV depreciation data — estimate your resale value in seconds</p>
  </div>
</section>

<div class="bg-slate-50 py-10 px-4 -mt-6">
  <div class="mx-auto max-w-lg bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-8">

    <!-- Tool Card -->
    <div
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
      class="space-y-6"
    >

      <!-- Input 1: On-road price -->
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">On-road price (₹)</label>
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>
          <input
            type="number" min="50000" max="10000000" step="5000"
            x-model.number="price"
            class="w-full rounded-xl border border-slate-200 pl-8 pr-4 py-3 text-slate-800 text-base font-semibold focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
          >
        </div>
        <!-- Quick price chips -->
        <div class="flex flex-wrap gap-2 mt-3">
          <button @click="price=85000" :class="price==85000 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all duration-200">₹85,000</button>
          <button @click="price=150000" :class="price==150000 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all duration-200">₹1.5L</button>
          <button @click="price=1500000" :class="price==1500000 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all duration-200">₹15L</button>
          <button @click="price=2500000" :class="price==2500000 ? 'bg-green-600 text-white ring-green-600' : 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 cursor-pointer transition-all duration-200">₹25L</button>
        </div>
      </div>

      <!-- Input 2: Years -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <label class="text-sm font-semibold text-slate-700">Years you'll own it</label>
          <span class="text-lg font-bold text-green-700" x-text="years + ' yr' + (years > 1 ? 's' : '')"></span>
        </div>
        <input
          type="range" min="1" max="7" step="1"
          x-model.number="years"
          class="w-full h-2 rounded-full accent-green-600 cursor-pointer"
        >
        <div class="flex justify-between text-xs text-slate-400 mt-2 px-0.5">
          <span>1yr</span><span>2yr</span><span>3yr</span><span>4yr</span><span>5yr</span><span>6yr</span><span>7yr</span>
        </div>
      </div>

      <!-- Input 3: Annual km driven -->
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-3">Annual km driven</label>
        <div class="grid grid-cols-2 gap-2">
          <label :class="kmUsage==='low' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all duration-200">
            <input type="radio" x-model="kmUsage" value="low" class="sr-only">
            <span class="text-xs font-bold text-slate-700">Low</span>
            <span class="text-xs text-slate-400">5,000 km/yr</span>
          </label>
          <label :class="kmUsage==='average' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all duration-200">
            <input type="radio" x-model="kmUsage" value="average" class="sr-only">
            <span class="text-xs font-bold text-slate-700">Average</span>
            <span class="text-xs text-slate-400">10,000 km/yr</span>
          </label>
          <label :class="kmUsage==='high' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all duration-200">
            <input type="radio" x-model="kmUsage" value="high" class="sr-only">
            <span class="text-xs font-bold text-slate-700">High</span>
            <span class="text-xs text-slate-400">15,000 km/yr</span>
          </label>
          <label :class="kmUsage==='vhigh' ? 'ring-2 ring-green-500 bg-green-50' : 'ring-1 ring-slate-200 bg-slate-50 hover:bg-slate-100'" class="flex flex-col gap-0.5 p-3 rounded-2xl cursor-pointer transition-all duration-200">
            <input type="radio" x-model="kmUsage" value="vhigh" class="sr-only">
            <span class="text-xs font-bold text-slate-700">Very High</span>
            <span class="text-xs text-slate-400">20,000+ km/yr</span>
          </label>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-slate-100"></div>

      <!-- Result: Resale value -->
      <div class="text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Estimated resale value</p>
        <div class="text-4xl font-extrabold text-green-700" x-text="fmtInr(resaleValue)"></div>
        <p class="text-sm text-slate-500 mt-1">
          <span class="font-semibold text-slate-700" x-text="totalDepPct + '%'"></span> depreciation over
          <span x-text="years"></span> year<span x-show="years > 1">s</span>
        </p>
      </div>

      <!-- Value retention bar -->
      <div>
        <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1.5">
          <span>Value retained</span>
          <span x-text="retainedPct + '%'"></span>
        </div>
        <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
          <div
            class="h-full bg-green-500 rounded-full transition-all duration-500"
            :style="'width: ' + retainedPct + '%'"
          ></div>
        </div>
        <div class="flex justify-between text-xs text-slate-400 mt-1">
          <span>0%</span><span>100%</span>
        </div>
      </div>

      <!-- Comparison cards -->
      <div class="grid grid-cols-2 gap-3">
        <div class="bg-slate-50 rounded-2xl p-4 text-center">
          <div class="text-xs text-slate-500 font-medium mb-1">Cost to own</div>
          <div class="text-lg font-bold text-slate-800" x-text="fmtInr(costToOwn)"></div>
          <div class="text-xs text-slate-400 mt-1" x-text="'over ' + years + ' yr' + (years>1?'s':'')"></div>
        </div>
        <div class="bg-green-50 rounded-2xl p-4 text-center ring-1 ring-green-100">
          <div class="text-xs text-green-600 font-medium mb-1">vs Petrol retains</div>
          <div class="text-lg font-bold text-green-700" x-text="petrolRetained + '%'"></div>
          <div class="text-xs text-green-500 mt-1">EV holds ~5% more</div>
        </div>
      </div>

      <!-- Battery warning -->
      <div x-show="batteryWarn" x-transition class="flex items-start gap-3 bg-amber-50 rounded-2xl p-4 ring-1 ring-amber-200">
        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <p class="text-xs text-amber-700 font-medium">Battery may need health check — get it tested before selling to protect your resale value.</p>
      </div>

      <!-- Pro tip -->
      <div class="flex items-start gap-3 bg-blue-50 rounded-2xl p-4 ring-1 ring-blue-100">
        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        <p class="text-xs text-blue-700 font-medium"><strong>Pro tip:</strong> A battery health certificate can increase your EV's resale value by 10–15%.</p>
      </div>

      <!-- CTA -->
      <a href="/tco-calculator" class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-700 transition-colors duration-200">
        Calculate total ownership cost
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>

    </div>

    <p class="text-center text-xs text-slate-400 mt-4">Estimates based on average Indian EV market depreciation. Actual resale depends on brand, condition, and demand.</p>

  </div>
</div>

<?= $this->endSection() ?>
