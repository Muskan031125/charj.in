<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>EV Battery Replacement Cost India 2025 — Warranty & Prices | Charj.in</title>
<meta name="description" content="Find out how much it costs to replace your EV battery in India. Covers all popular EVs with warranty details and cost per kWh.">
<style>[x-cloak]{display:none!important}@keyframes fadeInUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm pt-24 md:pt-32 pb-10 px-4 relative overflow-hidden" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4,#F7FFFE)">
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-5"
         style="background:rgba(0,168,150,.10);border:1px solid rgba(0,168,150,.25);color:#00A896">
      🔋 Battery Replacement Cost Calculator
    </div>
    <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-3" style="color:#0F172A">EV Battery<br>Replacement Cost India</h1>
    <p class="text-base max-w-lg mx-auto" style="color:#475569">Know the worst-case scenario before you buy — full warranty details and cost breakdown.</p>
  </div>
</section>

<div class="px-4 py-10" style="background:#F7FFFE">
<div class="max-w-2xl mx-auto" x-data="batteryCost()" style="animation:fadeInUp .4s ease both">

  <!-- Selector -->
  <div class="bg-white rounded-2xl shadow-sm p-6 mb-6" style="border:1px solid #EAFFF4">
    <label class="block text-sm font-semibold mb-2" style="color:#0F172A">Select your EV</label>
    <select x-model="selectedEv"
            @change="computeResult()"
            class="w-full rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 outline-none appearance-none"
            style="border:1px solid #d7f5ec;background:#F7FFFE;color:#0F172A;--tw-ring-color:rgba(0,168,150,.15)">
      <option value="">Choose a vehicle…</option>
      <option value="Ola S1 Pro">Ola S1 Pro</option>
      <option value="Ather 450X">Ather 450X</option>
      <option value="TVS iQube S">TVS iQube S</option>
      <option value="Bajaj Chetak">Bajaj Chetak</option>
      <option value="Hero Vida V1">Hero Vida V1</option>
      <option value="Tata Nexon EV">Tata Nexon EV</option>
      <option value="Tata Tiago EV">Tata Tiago EV</option>
      <option value="Tata Punch EV">Tata Punch EV</option>
      <option value="MG ZS EV">MG ZS EV</option>
      <option value="Hyundai Ioniq 5">Hyundai Ioniq 5</option>
      <option value="BYD Atto 3">BYD Atto 3</option>
      <option value="Mahindra XUV400">Mahindra XUV400</option>
      <option value="Kia EV6">Kia EV6</option>
    </select>
  </div>

  <!-- Results -->
  <div x-show="result" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">

    <!-- Big cost card -->
    <div class="rounded-2xl p-6 mb-4 text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
      <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:rgba(255,255,255,.65)">Battery replacement cost</p>
      <div class="text-4xl font-black mb-4" x-text="result && fmtINR(result.replaceCost)"></div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <p class="text-xs mb-0.5" style="color:rgba(255,255,255,.6)">Battery type</p>
          <p class="text-sm font-bold" x-text="result && result.type"></p>
        </div>
        <div>
          <p class="text-xs mb-0.5" style="color:rgba(255,255,255,.6)">Capacity</p>
          <p class="text-sm font-bold" x-text="result && result.capacity + ' kWh'"></p>
        </div>
      </div>
    </div>

    <!-- Warranty badge -->
    <div class="flex items-center gap-3 rounded-2xl p-4 mb-4" style="background:#F0FFF9;border:1px solid rgba(0,168,150,.2)">
      <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background:#00A896">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944A11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
      </div>
      <div>
        <p class="text-xs font-semibold" style="color:#00A896">Warranty coverage</p>
        <p class="text-sm font-bold" style="color:#022C22" x-text="result && `Covered for ${result.warrantyYrs} years / ${(result.warrantyKm/1000).toFixed(0)}k km — whichever comes first`"></p>
      </div>
    </div>

    <!-- Cost per kWh -->
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-4" style="border:1px solid #EAFFF4">
      <div class="flex items-start justify-between gap-4 mb-3">
        <div>
          <p class="text-xs mb-0.5" style="color:#94A3B8">Cost per kWh</p>
          <p class="text-2xl font-black" style="color:#0F172A" x-text="result && fmtINR(Math.round(result.replaceCost / result.capacity)) + ' / kWh'"></p>
        </div>
        <template x-if="result && result.type === 'LFP'">
          <span class="flex-shrink-0 text-xs font-bold px-2.5 py-1 rounded-full" style="background:#dbeafe;color:#1d4ed8">LFP — lower cost</span>
        </template>
        <template x-if="result && result.type === 'NMC'">
          <span class="flex-shrink-0 text-xs font-bold px-2.5 py-1 rounded-full" style="background:#ede9fe;color:#6d28d9">NMC battery</span>
        </template>
      </div>
      <p class="text-xs" style="color:#64748B" x-show="result && result.type === 'LFP'">LFP batteries typically cost 15% less to replace and have longer cycle life.</p>
      <p class="text-xs" style="color:#64748B" x-show="result && result.type === 'NMC'">NMC batteries offer higher energy density but cost slightly more to replace.</p>
    </div>

    <!-- Future price -->
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(0,168,150,.06);border:1px solid rgba(0,168,150,.15)">
      <h3 class="font-bold text-sm mb-2" style="color:#007A6E">Battery prices are falling fast</h3>
      <p class="text-sm" style="color:#0F172A">
        Prices are dropping 10–15% per year. By <strong x-text="new Date().getFullYear() + 5"></strong>, this battery may cost just
        <strong x-text="result && fmtINR(Math.round(result.replaceCost * 0.6))"></strong>
        <span class="text-xs ml-1" style="color:#00A896">(~40% cheaper)</span>
      </p>
    </div>

    <!-- Reassurance note -->
    <div class="rounded-2xl p-5 mb-6" style="background:#F0FFF9;border:1px solid rgba(0,168,150,.15)">
      <p class="text-sm font-medium" style="color:#007A6E">
        ✅ Most EVs never need battery replacement within the warranty period if charged correctly.
      </p>
    </div>

    <!-- Tips accordion -->
    <div class="bg-white rounded-2xl shadow-sm divide-y" style="border:1px solid #EAFFF4;--tw-divide-opacity:1;divide-color:#F5FFF7" x-data="{open: null}">
      <div class="p-5">
        <h3 class="font-bold text-sm mb-1" style="color:#0F172A">Tips to extend your battery life</h3>
        <p class="text-xs" style="color:#94A3B8">Follow these to maximise warranty and longevity</p>
      </div>
      <template x-for="(tip, i) in tips" :key="i">
        <div class="overflow-hidden">
          <button @click="open = open === i ? null : i"
                  class="w-full flex items-center justify-between px-5 py-4 text-left transition-colors"
                  onmouseover="this.style.background='rgba(0,168,150,.05)'" onmouseout="this.style.background=''">
            <div class="flex items-center gap-3">
              <span class="text-lg" x-text="tip.icon"></span>
              <span class="text-sm font-medium text-slate-800" x-text="tip.title"></span>
            </div>
            <svg class="w-4 h-4 transition-transform duration-200 flex-shrink-0" style="color:#94A3B8"
                 :class="open === i ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div x-show="open === i"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 -translate-y-1"
               x-transition:enter-end="opacity-100 translate-y-0"
               class="px-5 pb-4">
            <p class="text-sm pl-8" style="color:#475569" x-text="tip.detail"></p>
          </div>
        </div>
      </template>
    </div>

    <!-- CTA -->
    <div class="mt-6 text-center">
      <a href="<?= base_url('find-my-ev') ?>" class="inline-flex items-center gap-2 text-white font-bold px-6 py-3 rounded-xl transition-all text-sm hover:-translate-y-0.5"
         style="background:linear-gradient(135deg,#00A896,#007A6E)">
        Find your ideal EV →
      </a>
    </div>

  </div><!-- /results -->

  <!-- Empty state -->
  <div x-show="!result" class="text-center py-16 rounded-3xl" style="border:1px dashed #d7f5ec;background:#fff">
    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl flex items-center justify-center" style="background:rgba(0,168,150,.1)">
      <svg class="w-7 h-7" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
      </svg>
    </div>
    <p class="text-sm" style="color:#94A3B8">Select an EV above to see battery cost details</p>
  </div>

</div>
</div>

<style>[x-cloak] { display: none !important; }</style>

<script>
function batteryCost() {
  return {
    selectedEv: '',
    result: null,

    batteryData: {
      'Ola S1 Pro':      {capacity: 3.97,  type: 'NMC', replaceCost: 45000,   warrantyYrs: 3, warrantyKm: 40000},
      'Ather 450X':      {capacity: 3.7,   type: 'NMC', replaceCost: 40000,   warrantyYrs: 3, warrantyKm: 30000},
      'TVS iQube S':     {capacity: 4.56,  type: 'NMC', replaceCost: 35000,   warrantyYrs: 5, warrantyKm: 50000},
      'Bajaj Chetak':    {capacity: 3.0,   type: 'NMC', replaceCost: 28000,   warrantyYrs: 3, warrantyKm: 50000},
      'Hero Vida V1':    {capacity: 3.94,  type: 'NMC', replaceCost: 38000,   warrantyYrs: 3, warrantyKm: 50000},
      'Tata Nexon EV':   {capacity: 40.5,  type: 'NMC', replaceCost: 450000,  warrantyYrs: 8, warrantyKm: 160000},
      'Tata Tiago EV':   {capacity: 24,    type: 'LFP', replaceCost: 250000,  warrantyYrs: 8, warrantyKm: 160000},
      'Tata Punch EV':   {capacity: 35,    type: 'NMC', replaceCost: 380000,  warrantyYrs: 8, warrantyKm: 160000},
      'MG ZS EV':        {capacity: 50.3,  type: 'NMC', replaceCost: 520000,  warrantyYrs: 8, warrantyKm: 150000},
      'Hyundai Ioniq 5': {capacity: 72.6,  type: 'NMC', replaceCost: 950000,  warrantyYrs: 10, warrantyKm: 200000},
      'BYD Atto 3':      {capacity: 60.48, type: 'LFP', replaceCost: 600000,  warrantyYrs: 8, warrantyKm: 150000},
      'Mahindra XUV400': {capacity: 39.4,  type: 'NMC', replaceCost: 420000,  warrantyYrs: 8, warrantyKm: 150000},
      'Kia EV6':         {capacity: 77.4,  type: 'NMC', replaceCost: 980000,  warrantyYrs: 7, warrantyKm: 150000},
    },

    tips: [
      {icon: '⚡', title: "Don't charge to 100% daily", detail: "Keep your daily charge limit at 80%. Most EVs have a scheduled charging or limit setting. Full charges should be reserved for long trips."},
      {icon: '🔌', title: 'Limit DC fast charging to 2× per week', detail: "DC fast charging generates heat inside the battery pack. Frequent use accelerates degradation. Use AC home charging for daily top-ups."},
      {icon: '☀️', title: 'Park in shade during summer', detail: "High ambient temperatures degrade battery cells. Where possible, park in covered areas or garages. This is especially important in cities like Delhi or Chennai during peak summer."},
      {icon: '🔧', title: 'Service battery BMS annually', detail: "The Battery Management System (BMS) controls charge/discharge cycles. An annual dealer check ensures it's calibrated correctly and updates firmware that can improve range and longevity."},
    ],

    computeResult() {
      this.result = this.batteryData[this.selectedEv] || null;
    },

    fmtINR(n) {
      if (!n) return '—';
      if (n >= 100000) return '₹' + (n / 100000).toFixed(2) + ' L';
      return '₹' + n.toLocaleString('en-IN');
    },
  }
}
</script>

<?= $this->endSection() ?>
