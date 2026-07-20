<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Used EV Valuation | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Estimate the resale value of your used electric vehicle in India. Factor in age, km driven, battery health and condition to get an indicative price range.') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
[x-cloak]{display:none!important}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.anim-fade-up{animation:fadeUp .4s cubic-bezier(.22,1,.36,1) both}
.delay-1{animation-delay:.06s}.delay-2{animation-delay:.12s}.delay-3{animation-delay:.2s}
.opt-btn{transition:transform .15s ease,box-shadow .15s ease,border-color .15s ease,background .15s ease}
.opt-btn:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(0,168,150,.1)}
.xlink-card{transition:transform .15s ease,box-shadow .15s ease,border-color .15s ease}
.xlink-card:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,168,150,.12)}
input[type=range]{-webkit-appearance:none;appearance:none;height:6px;border-radius:9999px;background:linear-gradient(to right,#00A896 var(--pct,0%),#e2e8f0 var(--pct,0%));outline:none;cursor:pointer}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:18px;height:18px;border-radius:50%;background:#fff;border:2px solid #00A896;box-shadow:0 2px 6px rgba(0,168,150,.3);cursor:pointer;transition:transform .15s}
input[type=range]::-webkit-slider-thumb:hover{transform:scale(1.2)}
input[type=range]::-moz-range-thumb{width:18px;height:18px;border-radius:50%;background:#fff;border:2px solid #00A896;box-shadow:0 2px 6px rgba(0,168,150,.3);cursor:pointer}
</style>

<div x-data="usedEvCalc()" x-init="initRanges()" class="pb-16" style="background:linear-gradient(180deg,#F0FFF9 0%,#F7FFFE 300px)">

  <!-- ── HERO ── -->
  <div class="hero-sm relative overflow-hidden pt-24 pb-8 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.3) 1px,transparent 1px);background-size:24px 24px"></div>
    <div class="absolute top-0 right-0 w-96 h-72 rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(0,168,150,.1),transparent 65%);transform:translate(30%,-30%)"></div>
    <div class="relative max-w-5xl mx-auto">
      <div class="max-w-2xl">
        <div class="anim-fade-up inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-widest mb-4" style="background:rgba(0,168,150,.1);color:#00A896;border:1.5px solid rgba(0,168,150,.2)">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/><path d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z"/></svg>
          Used EV Price Estimator
        </div>
        <h1 class="anim-fade-up delay-1 text-3xl sm:text-4xl lg:text-5xl font-black leading-tight mb-3" style="color:#0F172A">Used EV Valuation</h1>
        <p class="anim-fade-up delay-2 text-sm sm:text-base" style="color:#475569">Wondering what your electric scooter or car is worth today? EVs depreciate faster than petrol vehicles — get an <strong style="color:#0F172A">indicative resale range</strong> factoring in age, kilometres, battery health and condition.</p>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 py-6">
    <div class="lg:grid lg:grid-cols-5 lg:gap-5 lg:items-start">

      <!-- ── INPUTS (3/5) ── -->
      <div class="lg:col-span-3 space-y-4">

        <!-- Original price -->
        <div class="bg-white rounded-2xl p-5 anim-fade-up" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-bold" style="color:#0F172A">Original ex-showroom price</label>
            <span class="text-lg font-black" style="color:#00A896" x-text="fmt(origPrice)"></span>
          </div>
          <input type="range" min="40000" max="3000000" step="1000" x-model.number="origPrice" @input="updateRange($el)" x-ref="priceRange" class="w-full">
          <div class="flex justify-between text-xs mt-1.5" style="color:#94A3B8"><span>₹40K</span><span>₹30L</span></div>
          <div class="mt-3">
            <div class="text-xs font-semibold mb-2" style="color:#64748B">Popular EVs — quick pick</div>
            <div class="flex flex-wrap gap-1.5">
              <template x-for="ev in popularEvs" :key="ev.name">
                <button @click="origPrice=ev.price;$nextTick(()=>updateRange($refs.priceRange))"
                  :style="origPrice==ev.price?'background:#00A896;color:#fff;border-color:#00A896':''"
                  :class="origPrice==ev.price?'':'bg-slate-50 border-slate-200 hover:border-teal-300'"
                  class="opt-btn border rounded-lg px-2.5 py-1.5 text-xs font-bold" style="color:#475569">
                  <span x-text="ev.name"></span>
                </button>
              </template>
            </div>
          </div>
        </div>

        <!-- Vehicle type -->
        <div class="bg-white rounded-2xl p-5 anim-fade-up delay-1" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <label class="text-sm font-bold block mb-3" style="color:#0F172A">Vehicle type</label>
          <div class="grid grid-cols-3 gap-2">
            <template x-for="vt in vehicleTypes" :key="vt.id">
              <button @click="vehicleType=vt.id"
                :style="vehicleType===vt.id?'border-color:#00A896;background:#F0FFF9':'border-color:#e2e8f0'"
                class="opt-btn border-2 rounded-xl py-3 text-center bg-white">
                <div class="text-2xl mb-1" x-text="vt.icon"></div>
                <div class="text-xs font-black" style="color:#0F172A" x-text="vt.label"></div>
              </button>
            </template>
          </div>
        </div>

        <!-- Age + Km -->
        <div class="bg-white rounded-2xl p-5 anim-fade-up delay-2" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <label class="text-sm font-bold block mb-3" style="color:#0F172A">Age (years owned)</label>
          <div class="grid grid-cols-5 gap-2 mb-5">
            <template x-for="a in ages" :key="a.val">
              <button @click="age=a.val"
                :style="age===a.val?'border-color:#00A896;background:#F0FFF9;color:#00A896':'border-color:#e2e8f0;color:#475569'"
                class="opt-btn border-2 rounded-xl py-2.5 text-sm font-black bg-white" x-text="a.label"></button>
            </template>
          </div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold" style="color:#0F172A">Kilometres driven</label>
            <span class="text-sm font-black" style="color:#00A896"><span x-text="Number(kmDriven).toLocaleString('en-IN')"></span> km</span>
          </div>
          <input type="range" min="0" max="80000" step="500" x-model.number="kmDriven" @input="updateRange($el)" x-ref="kmRange" class="w-full">
          <div class="flex justify-between text-xs mt-1.5" style="color:#94A3B8"><span>0</span><span>80,000</span></div>
        </div>

        <!-- Battery health + condition -->
        <div class="bg-white rounded-2xl p-5 anim-fade-up delay-3" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold" style="color:#0F172A">Battery health (State of Charge)</label>
            <span class="text-sm font-black" style="color:#00A896"><span x-text="batteryHealth"></span>%</span>
          </div>
          <input type="range" min="60" max="100" step="1" x-model.number="batteryHealth" @input="updateRange($el)" x-ref="battRange" class="w-full">
          <div class="flex justify-between text-xs mt-1.5 mb-5" style="color:#94A3B8"><span>60%</span><span>100%</span></div>
          <label class="text-sm font-bold block mb-3" style="color:#0F172A">Overall condition</label>
          <div class="grid grid-cols-3 gap-2">
            <template x-for="c in conditions" :key="c.id">
              <button @click="condition=c.id"
                :style="condition===c.id?'border-color:#00A896;background:#F0FFF9;color:#00A896':'border-color:#e2e8f0;color:#475569'"
                class="opt-btn border-2 rounded-xl py-2.5 text-sm font-black bg-white" x-text="c.label"></button>
            </template>
          </div>
        </div>
      </div>

      <!-- ── RESULT (2/5) ── -->
      <div class="lg:col-span-2 mt-4 lg:mt-0 lg:sticky" style="top:80px">

        <!-- Result card -->
        <div class="rounded-2xl p-5 sm:p-6 relative overflow-hidden mb-4" style="background:linear-gradient(150deg,#00A896,#007A6E);box-shadow:0 10px 32px rgba(0,168,150,.22)">
          <div class="absolute top-0 right-0 w-40 h-40 pointer-events-none" style="background:radial-gradient(circle,rgba(0,230,118,.18),transparent 70%);transform:translate(25%,-25%)"></div>
          <div class="relative">
            <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:rgba(0,230,118,.85)">Estimated resale value</div>
            <div class="text-3xl sm:text-4xl font-black text-white leading-tight">
              <span x-text="fmt(low)"></span> <span class="opacity-70 font-bold">–</span> <span x-text="fmt(high)"></span>
            </div>
            <div class="mt-3 inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-bold" style="background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.18)">
              <svg class="w-3.5 h-3.5" style="color:#00E676" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
              <span><span x-text="depreciationPct"></span>% total depreciation</span>
            </div>
            <p class="text-xs mt-3" style="color:rgba(255,255,255,.78)">
              <template x-if="batteryHealth>=85">Battery health is strong, supporting a healthier resale price.</template>
              <template x-if="batteryHealth<85 && batteryHealth>=80">Battery health is moderate — buyers may negotiate on range.</template>
              <template x-if="batteryHealth<80">Battery health below 80% noticeably reduces resale value, as range loss is a top buyer concern.</template>
            </p>
          </div>
        </div>

        <!-- Full breakup -->
        <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <div class="px-5 py-3.5" style="background:linear-gradient(135deg,#F0FFF9,#fff);border-bottom:1px solid rgba(0,168,150,.08)">
            <h3 class="text-sm font-black" style="color:#0F172A">Full valuation breakup</h3>
          </div>
          <div class="px-5 py-3 text-sm">
            <div class="flex justify-between py-2"><span style="color:#64748B">Original price</span><span class="font-bold" style="color:#0F172A" x-text="fmt(origPrice)"></span></div>
            <div class="flex justify-between py-2 border-t border-slate-50"><span style="color:#64748B">Age depreciation (<span x-text="baseDepPct"></span>%)</span><span class="font-bold" style="color:#DC2626" x-text="'− '+fmt(baseDepAmt)"></span></div>
            <div class="flex justify-between py-2 border-t border-slate-50"><span style="color:#64748B">High-km adjustment</span><span class="font-bold" :style="kmAdjAmt>0?'color:#DC2626':'color:#94A3B8'" x-text="kmAdjAmt>0?'− '+fmt(kmAdjAmt):'—'"></span></div>
            <div class="flex justify-between py-2 border-t border-slate-50"><span style="color:#64748B">Battery-health adjustment</span><span class="font-bold" :style="battAdjAmt>0?'color:#DC2626':'color:#94A3B8'" x-text="battAdjAmt>0?'− '+fmt(battAdjAmt):'—'"></span></div>
            <div class="flex justify-between py-2 border-t border-slate-50"><span style="color:#64748B">Condition (<span x-text="condLabel"></span>)</span><span class="font-bold" :style="condFactor>=1?'color:#16A34A':'color:#DC2626'" x-text="(condFactor>1?'+ ':condFactor<1?'− ':'')+condPctLabel"></span></div>
          </div>
          <div class="px-5 py-3.5 flex items-center justify-between" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4);border-top:2px solid rgba(0,168,150,.15)">
            <div class="text-xs font-black uppercase tracking-widest" style="color:#00A896">Estimated range</div>
            <div class="text-base font-black" style="color:#0F172A"><span x-text="fmt(low)"></span> – <span x-text="fmt(high)"></span></div>
          </div>
        </div>

        <!-- Disclaimer -->
        <div class="rounded-xl p-3.5 flex gap-2.5 mt-4" style="background:#F7FFFE;border:1px solid rgba(0,168,150,.18)">
          <svg class="w-4 h-4 flex-shrink-0 mt-0.5" style="color:#00A896" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
          <div class="text-xs" style="color:#475569"><strong style="color:#0F172A">Indicative only.</strong> This estimate is a guide based on typical EV depreciation patterns. Actual resale price depends on model demand, location, service history and warranty status.</div>
        </div>
      </div>
    </div>

    <!-- ── CROSS-LINKS ── -->
    <div class="mt-10">
      <h2 class="text-lg font-black mb-4" style="color:#0F172A">Explore more</h2>
      <div class="grid sm:grid-cols-3 gap-4">
        <a href="<?= base_url('resale-estimator') ?>" class="xlink-card block bg-white rounded-2xl p-5" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <div class="text-2xl mb-2">📊</div>
          <div class="text-sm font-black mb-1" style="color:#0F172A">Resale Estimator</div>
          <div class="text-xs" style="color:#64748B">A deeper, model-specific resale value tool.</div>
        </a>
        <a href="<?= base_url('used-ev') ?>" class="xlink-card block bg-white rounded-2xl p-5" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <div class="text-2xl mb-2">🔍</div>
          <div class="text-sm font-black mb-1" style="color:#0F172A">Browse Used EVs</div>
          <div class="text-xs" style="color:#64748B">Verified pre-owned electric vehicles for sale.</div>
        </a>
        <a href="<?= base_url('vehicles') ?>" class="xlink-card block bg-white rounded-2xl p-5" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,168,150,.1)">
          <div class="text-2xl mb-2">⚡</div>
          <div class="text-sm font-black mb-1" style="color:#0F172A">All EV Models</div>
          <div class="text-xs" style="color:#64748B">Compare specs, range and prices of new EVs.</div>
        </a>
      </div>
    </div>

    <!-- ── CTA ── -->
    <div class="mt-8 rounded-2xl p-6 sm:p-8 relative overflow-hidden text-center" style="background:linear-gradient(150deg,#00A896,#007A6E);box-shadow:0 10px 32px rgba(0,168,150,.22)">
      <div class="absolute top-0 right-0 w-48 h-48 pointer-events-none" style="background:radial-gradient(circle,rgba(0,230,118,.16),transparent 70%);transform:translate(25%,-25%)"></div>
      <div class="relative">
        <h2 class="text-xl sm:text-2xl font-black text-white mb-2">Thinking of selling?</h2>
        <p class="text-sm mb-5" style="color:rgba(255,255,255,.82)">List your EV with Charj.in and reach thousands of verified buyers.</p>
        <a href="<?= base_url('contact') ?>" class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-black text-sm transition-transform hover:scale-[1.03]" style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 18px rgba(0,230,118,.3)">
          List your EV
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
      </div>
    </div>
  </div>
</div>

<script>
function usedEvCalc() {
  return {
    origPrice: 130000,
    vehicleType: '2w',
    age: 1,
    kmDriven: 15000,
    batteryHealth: 90,
    condition: 'good',

    popularEvs: [
      { name:'Ather 450X',    price:139000  },
      { name:'Ola S1 Pro',    price:139999  },
      { name:'TVS iQube',     price:142750  },
      { name:'Tata Tiago EV', price:849000  },
      { name:'Nexon EV',      price:1449000 },
    ],
    vehicleTypes: [
      { id:'2w', label:'2-Wheeler', icon:'🛵' },
      { id:'3w', label:'3-Wheeler', icon:'🛺' },
      { id:'4w', label:'4-Wheeler', icon:'🚗' },
    ],
    ages: [
      { val:1, label:'1' }, { val:2, label:'2' }, { val:3, label:'3' }, { val:4, label:'4' }, { val:5, label:'5+' },
    ],
    conditions: [
      { id:'excellent', label:'Excellent', factor:1.05 },
      { id:'good',      label:'Good',      factor:1.0  },
      { id:'fair',      label:'Fair',      factor:0.9  },
    ],

    initRanges() {
      this.$nextTick(() => {
        ['priceRange','kmRange','battRange'].forEach(r => { const el = this.$refs[r]; if (el) this.updateRange(el); });
      });
    },
    updateRange(el) {
      if (!el) return;
      const pct = ((Number(el.value)-Number(el.min))/(Number(el.max)-Number(el.min))*100).toFixed(1);
      el.style.setProperty('--pct', pct+'%');
    },

    // ── Depreciation engine ──
    get baseDepPct() {
      // % of original value LOST due to age
      const map = { 1:25, 2:38, 3:50, 4:60, 5:70 };
      return map[this.age] ?? 70;
    },
    get baseDepAmt() {
      return Math.round(this.origPrice * this.baseDepPct / 100);
    },
    // value remaining after age depreciation
    get afterAge() {
      return this.origPrice - this.baseDepAmt;
    },
    // high-km penalty: scales up to ~12% of value for km beyond 60k
    get kmPenaltyPct() {
      const km = Number(this.kmDriven);
      if (km <= 60000) return Math.max(0, (km - 30000) / 30000) * 4; // mild up to 60k (0–4%)
      return 4 + Math.min(8, (km - 60000) / 20000 * 8); // 60k+ adds up to ~8 more => ~12% total
    },
    get kmAdjAmt() {
      return Math.round(this.afterAge * this.kmPenaltyPct / 100);
    },
    get afterKm() {
      return this.afterAge - this.kmAdjAmt;
    },
    // battery health: every % below 100 subtracts ~0.4% of value; below 80 a steeper extra hit
    get battPenaltyPct() {
      const soh = Number(this.batteryHealth);
      let pct = (100 - soh) * 0.4;
      if (soh < 80) pct += (80 - soh) * 0.6; // extra penalty below 80
      return pct;
    },
    get battAdjAmt() {
      return Math.round(this.afterKm * this.battPenaltyPct / 100);
    },
    get afterBatt() {
      return this.afterKm - this.battAdjAmt;
    },
    get condObj() {
      return this.conditions.find(c => c.id === this.condition) || this.conditions[1];
    },
    get condFactor() { return this.condObj.factor; },
    get condLabel() { return this.condObj.label; },
    get condPctLabel() {
      const d = Math.round(Math.abs(this.condFactor - 1) * 100);
      return d === 0 ? 'no change' : d + '%';
    },
    get estimate() {
      return Math.max(0, Math.round(this.afterBatt * this.condFactor));
    },
    get low()  { return Math.round(this.estimate * 0.92); },
    get high() { return Math.round(this.estimate * 1.08); },
    get depreciationPct() {
      if (!this.origPrice) return 0;
      return Math.min(99, Math.round((1 - this.estimate / this.origPrice) * 100));
    },

    fmt(n) {
      if (n === null || n === undefined || isNaN(n)) return '—';
      return '₹' + Math.round(n).toLocaleString('en-IN');
    },
  }
}
</script>

<?= $this->endSection() ?>
