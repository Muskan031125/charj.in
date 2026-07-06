<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>EV Charger Compatibility Checker — Is Your EV Compatible? | Charj.in</title>
<meta name="description" content="Check which public chargers work with your EV. Home socket, Type 2, CCS2, DC fast charging compatibility for all Indian EVs.">
<style>
[x-cloak]{display:none!important}
@keyframes fadeInUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideIn{from{opacity:0;transform:translateX(-8px)}to{opacity:1;transform:translateX(0)}}
.tool-hero{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#0f2a1f 100%);}
.compat-row{border-radius:.875rem;padding:1rem 1.25rem;transition:all .2s;border:1.5px solid #f1f5f9;background:#fff;}
.compat-row:hover{border-color:#86efac;background:#f0fdf4;}
.compat-yes{color:#15803d;font-weight:800;font-size:.75rem;display:flex;align-items:center;gap:.3rem;}
.compat-no{color:#ef4444;font-weight:800;font-size:.75rem;display:flex;align-items:center;gap:.3rem;}
.compat-na{color:#94a3b8;font-weight:700;font-size:.75rem;display:flex;align-items:center;gap:.3rem;}
.time-chip{font-size:.65rem;color:#6b7280;background:#f8fafc;border:1px solid #e2e8f0;padding:.2rem .6rem;border-radius:9999px;margin-top:.2rem;}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div x-data="{
  selected: '',
  evData: {
    'Ola S1 Pro':       {type:'2W',connectors:['Type 1 AC','Ola Hypercharger (proprietary)'],homeSocket:true,home32A:false,publicAC:false,dcFast:true,ultraFast:false,batteryKwh:3.97,notes:'Ola Hypercharger network only for fast charging. Not CCS2 compatible.'},
    'Ather 450X':       {type:'2W',connectors:['Type 1 AC','Ather Grid','Type 2 AC'],homeSocket:true,home32A:true,publicAC:true,dcFast:false,ultraFast:false,batteryKwh:3.7,notes:'No DC fast charging. Supports Ather Grid and standard Type 2 AC.'},
    'TVS iQube S':      {type:'2W',connectors:['15A socket','Type 2 AC'],homeSocket:true,home32A:true,publicAC:true,dcFast:false,ultraFast:false,batteryKwh:4.56,notes:'Standard AC charging only — home socket and Type 2 wallbox.'},
    'Bajaj Chetak':     {type:'2W',connectors:['15A socket'],homeSocket:true,home32A:false,publicAC:false,dcFast:false,ultraFast:false,batteryKwh:3.0,notes:'Home socket (5A/15A) charging only. No public charging support.'},
    'Hero Vida V1':     {type:'2W',connectors:['Type 2 AC','15A socket'],homeSocket:true,home32A:true,publicAC:true,dcFast:false,ultraFast:false,batteryKwh:3.44,notes:'AC charging only — home socket and Type 2 AC public chargers.'},
    'Tata Nexon EV':    {type:'4W',connectors:['Type 2 AC (7.4kW)','CCS2 DC (50kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:false,batteryKwh:40.5,chargerKw:7.4,dcKw:50,notes:'CCS2 compatible — works on most public fast charging networks in India.'},
    'Tata Tiago EV':    {type:'4W',connectors:['Type 2 AC (3.3kW)','CCS2 DC (25kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:false,batteryKwh:24.0,chargerKw:3.3,dcKw:25,notes:'Slower 3.3kW on-board charger. Best for overnight home charging.'},
    'Tata Punch EV':    {type:'4W',connectors:['Type 2 AC (7.2kW)','CCS2 DC (50kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:false,batteryKwh:35.0,chargerKw:7.2,dcKw:50,notes:'CCS2 for public fast charging — standard ecosystem.'},
    'MG ZS EV':         {type:'4W',connectors:['Type 2 AC (7.4kW)','CCS2 DC (50kW)','CHAdeMO DC'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:false,batteryKwh:50.3,chargerKw:7.4,dcKw:50,notes:'Also supports CHAdeMO — broadest public charging compatibility of any Indian EV.'},
    'Hyundai Ioniq 5':  {type:'4W',connectors:['Type 2 AC (11kW)','CCS2 DC (220kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:true,batteryKwh:72.6,chargerKw:11,dcKw:220,notes:'800V architecture enables 220kW ultra-fast charging — 18 min for 10–80%.'},
    'BYD Atto 3':       {type:'4W',connectors:['Type 2 AC (7kW)','CCS2 DC (60kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:false,batteryKwh:60.5,chargerKw:7.0,dcKw:60,notes:'CCS2 with 60kW DC max — good balance of range and charging speed.'},
    'Mahindra XUV400':  {type:'4W',connectors:['Type 2 AC (7.2kW)','CCS2 DC (50kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:false,batteryKwh:39.4,chargerKw:7.2,dcKw:50,notes:'Standard CCS2 ecosystem — compatible with all major public networks.'},
    'Kia EV6':          {type:'4W',connectors:['Type 2 AC (11kW)','CCS2 DC (230kW)'],homeSocket:false,home32A:true,publicAC:true,dcFast:true,ultraFast:true,batteryKwh:77.4,chargerKw:11,dcKw:230,notes:'800V architecture — fastest charging EV in India. 18 min to 80% at 230kW.'},
  },
  get ev() { return this.selected ? this.evData[this.selected] : null },
  get is2W() { return this.ev && this.ev.type === '2W' },
  timeEst(kw) {
    if (!this.ev) return null;
    let hrs = (this.ev.batteryKwh * 0.8) / kw;
    if (hrs < 1) return Math.round(hrs * 60) + ' min';
    return hrs.toFixed(1) + ' hr';
  },
  evList2W() { return Object.keys(this.evData).filter(k => this.evData[k].type === '2W') },
  evList4W() { return Object.keys(this.evData).filter(k => this.evData[k].type === '4W') }
}" class="min-h-screen bg-slate-50">

<!-- Dark Hero -->
<section class="tool-hero text-white pt-28 pb-16 px-4 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10 pointer-events-none"
       style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:24px 24px"></div>
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 rounded-full px-4 py-1.5 text-blue-300 text-xs font-bold uppercase tracking-widest mb-5">
      🔌 Charger Compatibility Checker
    </div>
    <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-3">
      Is Your EV Compatible<br>with Public Chargers?
    </h1>
    <p class="text-slate-400 text-base max-w-xl mx-auto">
      Check which home and public chargers work with your EV — instant results for all Indian EVs.
    </p>
  </div>
</section>

<!-- Tool Content (light) -->
<div class="max-w-2xl mx-auto px-4 -mt-8 pb-16">

  <!-- EV Selector Card -->
  <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 mb-5" style="animation:fadeInUp .4s ease both">
    <label class="block text-sm font-black text-slate-900 mb-3">Select your EV</label>
    <div class="relative">
      <select x-model="selected"
              class="w-full rounded-2xl border-2 border-slate-200 focus:border-blue-400 px-4 py-3.5 text-slate-900 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 appearance-none bg-white transition-all cursor-pointer">
        <option value="">— Choose your EV —</option>
        <optgroup label="2-Wheelers 🛵">
          <template x-for="name in evList2W()" :key="name">
            <option :value="name" x-text="name"></option>
          </template>
        </optgroup>
        <optgroup label="4-Wheelers 🚗">
          <template x-for="name in evList4W()" :key="name">
            <option :value="name" x-text="name"></option>
          </template>
        </optgroup>
      </select>
      <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
      </div>
    </div>
    <!-- Connector tags -->
    <div x-show="ev" x-transition class="mt-4 flex flex-wrap gap-2">
      <span class="text-xs font-bold text-slate-500 self-center">Connectors:</span>
      <template x-for="c in ev?.connectors" :key="c">
        <span class="text-xs font-semibold bg-slate-900 text-white px-3 py-1.5 rounded-full" x-text="c"></span>
      </template>
    </div>
  </div>

  <!-- Compatibility grid -->
  <div x-show="ev" x-cloak x-transition class="space-y-3 mb-5" style="animation:fadeInUp .3s ease both">
    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wide px-1">Charger Compatibility</h2>

    <?php
    $rows = [
      ['homeSocket', '🏠', 'Home 15A Socket', 'Standard household plug ~1.5kW', 1.5],
      ['home32A',   '🔌', 'Home 32A Wallbox (Type 2)', 'Home charger 7.2–7.4kW', null],
      ['publicAC',  '🏢', 'Public AC (Type 2)', 'Mall / office charger 7–22kW', null],
      ['dcFast',    '⚡', 'DC Fast Charger (CCS2)', 'Highway / public 25–150kW', null],
      ['ultraFast', '⚡⚡', 'Ultra-Fast (150kW+)', 'Premium 800V fast chargers', 150],
    ];
    foreach ($rows as [$field, $icon, $label, $sub, $kw]): ?>
    <div class="compat-row flex items-center justify-between gap-3">
      <div class="flex items-center gap-3 flex-1 min-w-0">
        <span class="text-xl flex-shrink-0"><?= $icon ?></span>
        <div class="min-w-0">
          <div class="text-sm font-bold text-slate-900 truncate"><?= $label ?></div>
          <div class="text-xs text-slate-400"><?= $sub ?></div>
        </div>
      </div>
      <div class="flex flex-col items-end flex-shrink-0">
        <span x-show="ev?.<?= $field ?>" class="compat-yes">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
          Compatible
        </span>
        <span x-show="ev && !ev?.<?= $field ?>" class="compat-no">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
          Not supported
        </span>
        <?php if ($field === 'homeSocket'): ?>
        <span x-show="ev?.homeSocket" class="time-chip" x-text="'~' + timeEst(1.5) + ' full charge'"></span>
        <?php elseif ($field === 'home32A'): ?>
        <span x-show="ev?.home32A" class="time-chip" x-text="'~' + timeEst(ev?.chargerKw || 7.4) + ' full charge'"></span>
        <?php elseif ($field === 'dcFast'): ?>
        <span x-show="ev?.dcFast && ev?.dcKw" class="time-chip" x-text="'~' + timeEst(ev?.dcKw || 50) + ' (20→80%)'"></span>
        <?php elseif ($field === 'ultraFast'): ?>
        <span x-show="ev?.ultraFast" class="time-chip" x-text="'~' + timeEst(150) + ' (10→80%)'"></span>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Notes -->
    <div x-show="ev?.notes" class="rounded-2xl bg-blue-50 border border-blue-100 p-4 flex items-start gap-3">
      <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <p class="text-xs text-blue-800 font-medium leading-relaxed" x-text="ev?.notes"></p>
    </div>
  </div>

  <!-- Empty state -->
  <div x-show="!ev" class="bg-white rounded-3xl border border-slate-100 p-16 text-center shadow-sm" style="animation:fadeInUp .5s ease both">
    <div class="text-5xl mb-4">🔌</div>
    <h3 class="text-lg font-black text-slate-900 mb-2">Select your EV above</h3>
    <p class="text-sm text-slate-400">We'll show exactly which chargers work with it</p>
  </div>

  <!-- CTA -->
  <div class="grid grid-cols-2 gap-3 mt-5">
    <a href="<?= base_url('charging-stations') ?>"
       class="flex items-center justify-center gap-2 rounded-2xl font-bold py-3.5 text-sm text-white transition-all hover:-translate-y-0.5"
       style="background:linear-gradient(135deg,#1d4ed8,#0891b2)">
      ⚡ Find Chargers Near Me
    </a>
    <a href="<?= base_url('home-charger-guide') ?>"
       class="flex items-center justify-center gap-2 rounded-2xl bg-slate-900 text-white font-bold py-3.5 text-sm hover:bg-slate-800 transition-all hover:-translate-y-0.5">
      🏠 Home Charger Guide
    </a>
  </div>

  <p class="text-center text-xs text-slate-400 mt-4">Charging time estimates are 20%→100% (80% SoC). Actual times vary by ambient temperature and charger load.</p>
</div>

</div>

<?= $this->endSection() ?>
