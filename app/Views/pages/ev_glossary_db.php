<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'EV Glossary — A-Z Electric Vehicle Terms | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Complete EV glossary for India — from kWh to FAME II, BMS to V2G. Understand every electric vehicle term before you buy.') ?>">
<link rel="canonical" href="<?= base_url('ev-glossary') ?>">
<style>
[x-cloak]{display:none!important}

/* Hero gradient animation */
@keyframes gradShift { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
.glossary-hero { background:linear-gradient(-45deg,#0f172a,#1e1b4b,#0f2a45,#1a0533); background-size:400% 400%; animation:gradShift 12s ease infinite; }

/* Term card */
.term-card {
  background:#fff; border-radius:1.25rem; padding:1.4rem 1.5rem;
  border:1.5px solid #f1f5f9; transition:all .2s ease;
  box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.term-card:hover { border-color:#a5b4fc; box-shadow:0 4px 20px rgba(99,102,241,.1); transform:translateY(-1px); }

/* Letter heading */
.letter-badge {
  width:2.75rem; height:2.75rem; border-radius:.875rem;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-weight:900; font-size:1.1rem; flex-shrink:0;
  box-shadow:0 4px 12px rgba(99,102,241,.3);
}

/* Category badge colors */
.cat-battery  { background:#fef3c7; color:#b45309; }
.cat-charging { background:#dbeafe; color:#1d4ed8; }
.cat-performance { background:#dcfce7; color:#166534; }
.cat-policy   { background:#ede9fe; color:#5b21b6; }
.cat-finance  { background:#fce7f3; color:#9d174d; }
.cat-general  { background:#f1f5f9; color:#475569; }

/* Nav letter pill */
.ltr-pill {
  width:2rem; height:2rem; border-radius:.5rem; display:flex; align-items:center; justify-content:center;
  font-size:.7rem; font-weight:900; cursor:pointer; transition:all .15s;
  border:1.5px solid transparent; color:#64748b; background:transparent;
}
.ltr-pill:hover { background:#f1f5f9; color:#4f46e5; }
.ltr-pill.active { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border-color:transparent; box-shadow:0 2px 8px rgba(99,102,241,.4); }

/* Filter pill */
.filter-pill {
  padding:.375rem .875rem; border-radius:9999px; font-size:.72rem; font-weight:700; cursor:pointer;
  transition:all .15s; border:1.5px solid #e2e8f0; color:#64748b; background:#fff;
}
.filter-pill:hover { border-color:#818cf8; color:#4f46e5; }
.filter-pill.active { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border-color:transparent; box-shadow:0 2px 8px rgba(99,102,241,.35); }

/* Fade-in animation for term cards */
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.animate-in { animation:fadeInUp .25s ease forwards; }

/* Search highlight */
mark { background:#fef9c3; color:#854d0e; border-radius:2px; padding:0 2px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$staticTerms = [
  ['term'=>'AC Charging','definition'=>'Alternating Current charging — the standard home/office charging method. Uses the EV\'s on-board charger to convert AC to DC. Speed: 3.3–22 kW. Most wallbox chargers are AC.','category'=>'charging'],
  ['term'=>'ARAI Range','definition'=>'Automotive Research Association of India — the government body that certifies EV range under the MIDC (Modified Indian Driving Cycle). ARAI range is typically 15–25% higher than real-world range.','category'=>'general'],
  ['term'=>'Battery Management System (BMS)','definition'=>'The electronic brain of an EV battery. Monitors cell voltage, temperature and state of charge. Protects against overcharging, deep discharge and thermal runaway. A good BMS is critical for battery longevity.','category'=>'battery'],
  ['term'=>'C-Rate','definition'=>'Charging/discharging rate relative to battery capacity. 1C means the battery charges/discharges fully in 1 hour. Fast DC chargers can charge at 2–4C. High C-rates generate more heat and cause faster degradation if used repeatedly.','category'=>'battery'],
  ['term'=>'CCS2 Connector','definition'=>'The standard DC fast charging connector for most electric cars in India (Tata, MG, Hyundai, Kia). Supports up to 350kW. Tata has committed CCS2 as the Indian EV standard.','category'=>'charging'],
  ['term'=>'CHAdeMO','definition'=>'An older DC fast charging standard developed by Japan (used by Nissan Leaf). Rarely seen in new Indian EVs but some older charging stations still have CHAdeMO ports.','category'=>'charging'],
  ['term'=>'DC Fast Charging (DCFC)','definition'=>'Direct Current fast charging — bypasses the on-board charger to feed DC directly into the battery. Much faster than AC charging. Public fast chargers in India are typically 30–180kW.','category'=>'charging'],
  ['term'=>'FAME II','definition'=>'Faster Adoption and Manufacturing of Electric Vehicles — India\'s central government subsidy scheme. Phase II (2019–2024) provided subsidies for 2W (Rs15K/kWh), 3W, buses and commercial vehicles. Replaced by PM E-DRIVE in 2024.','category'=>'policy'],
  ['term'=>'Ground Clearance','definition'=>'The distance between the lowest point of the vehicle and the ground. Important for India\'s roads — most EVs have 160–200mm ground clearance. Look for 180mm+ if you drive on broken roads.','category'=>'performance'],
  ['term'=>'IP65 Rating','definition'=>'Ingress Protection rating. IP65 = dust-tight + protected against water jets. IP67 = submerged 1m for 30 mins. EV connectors and chargers should be at least IP55 for outdoor use in India\'s monsoon conditions.','category'=>'general'],
  ['term'=>'kWh (kilowatt-hour)','definition'=>'The unit for battery capacity. 1 kWh = running a 1000W appliance for 1 hour. Larger kWh = more range. A 30kWh battery at Rs5/unit costs Rs150 to fully charge. Most EVs deliver 5–8km per kWh.','category'=>'battery'],
  ['term'=>'LFP (Lithium Iron Phosphate)','definition'=>'Battery chemistry used by Tata (Nexon EV), many Chinese EVs, and Tesla Standard Range. More thermally stable, longer cycle life (3,000+ cycles), but lower energy density than NMC. Can charge to 100% daily without damage.','category'=>'battery'],
  ['term'=>'MIDC Cycle','definition'=>'Modified Indian Driving Cycle — the test standard used by ARAI to certify EV range. Simulates Indian urban driving patterns. Real-world range is typically 15–25% lower than MIDC-certified range.','category'=>'performance'],
  ['term'=>'NMC (Nickel Manganese Cobalt)','definition'=>'Premium battery chemistry with higher energy density — giving more range per kg. Used in Ola S1 Pro, Ather, BMW, Hyundai. Slightly more heat-sensitive than LFP. Typically charged to 80–90% for daily use.','category'=>'battery'],
  ['term'=>'OBC (On-Board Charger)','definition'=>'The AC-to-DC converter built into your EV. Limits AC charging speed (e.g., 3.3kW, 7.2kW, 11kW). To get 7.2kW AC charging, both your wallbox and OBC must support it. The bottleneck is always the slower component.','category'=>'charging'],
  ['term'=>'PM E-DRIVE','definition'=>'PM Electric Drive Revolution in Innovative Vehicle Enhancement — Rs10,900 crore scheme (2024–26) that replaced FAME II. Provides subsidy for 2W, 3W, e-buses and ambulances. Managed by the Ministry of Heavy Industries.','category'=>'policy'],
  ['term'=>'Regenerative Braking','definition'=>'The EV converts kinetic energy back to electricity when decelerating or braking. This electricity goes back into the battery. Aggressive regen (one-pedal driving) can recover 10–25% of energy on city routes.','category'=>'performance'],
  ['term'=>'Range Anxiety','definition'=>'The fear of an EV running out of charge before reaching the destination or a charger. Less relevant in Indian cities — most daily commutes are under 50km. Overcome with home charging and trip planning apps.','category'=>'general'],
  ['term'=>'SoC (State of Charge)','definition'=>'Battery percentage — how much charge is currently in the battery. Most EVs recommend keeping SoC between 20–80% for daily use to maximise battery life. Charge to 100% only for long trips.','category'=>'battery'],
  ['term'=>'SOH (State of Health)','definition'=>'Battery health — current capacity as a percentage of original capacity. After 5 years, quality EVs typically retain 85–90% SOH. Critical metric for used EV valuation. Check via OBD scanner or manufacturer app.','category'=>'battery'],
  ['term'=>'Type 2 Connector','definition'=>'Standard AC charging connector for electric cars in India. Used by Tata, MG, Hyundai, Kia, BMW, Mercedes. The 7-pin round connector. Wallbox chargers use Type 2 sockets.','category'=>'charging'],
  ['term'=>'V2G (Vehicle-to-Grid)','definition'=>'Vehicle-to-Grid — technology that lets EVs push electricity back to the grid during peak demand. Not yet mainstream in India but piloted in Delhi and Maharashtra. Can let EV owners earn money by selling power.','category'=>'general'],
  ['term'=>'VAHAN Portal','definition'=>'Ministry of Road Transport\'s vehicle registration portal (vahan.parivahan.gov.in). Used to verify vehicle ownership, check challan history, confirm hypothecation clearance, and validate RC for used EV purchases.','category'=>'policy'],
  ['term'=>'Wallbox Charger','definition'=>'A fixed home/office AC charger (3.3–22kW) mounted on a wall. Faster than a standard socket, safer (dedicated circuit, RCCB protection), and usually smart (app-controlled, scheduling). Cost in India: Rs15,000–Rs40,000 installed.','category'=>'charging'],
  ['term'=>'Wh/km (Efficiency)','definition'=>'Energy efficiency metric for EVs. Lower is better. Most Indian EVs: 80–180Wh/km. A car using 150Wh/km with a 30kWh battery has ~200km real-world range. City driving with regen improves this significantly.','category'=>'performance'],
];

$grouped = $grouped ?? [];
if (empty($grouped)) {
  foreach ($staticTerms as $t) {
    $letter = strtoupper($t['term'][0]);
    if (!ctype_alpha($letter)) $letter = '#';
    $grouped[$letter][] = $t;
  }
  ksort($grouped);
}
$total   = $total ?? array_sum(array_map('count', $grouped));
$letters = array_keys($grouped);

// Build JS data for Alpine
$jsTerms = [];
foreach ($grouped as $letter => $terms) {
  foreach ($terms as $t) {
    $jsTerms[] = [
      'letter'     => $letter,
      'term'       => $t['term'],
      'definition' => $t['definition'] ?? $t['description'] ?? '',
      'category'   => strtolower($t['category'] ?? 'general'),
    ];
  }
}
?>

<div x-data="glossaryApp()" x-init="init()" class="min-h-screen bg-slate-50">

<!-- ═══ HERO ═══ -->
<section class="hero-sm glossary-hero relative overflow-hidden text-white pt-28 pb-20 px-4">
  <div class="absolute inset-0 opacity-10 pointer-events-none"
       style="background-image:radial-gradient(rgba(255,255,255,.6) 1px,transparent 1px);background-size:32px 32px"></div>
  <!-- Glow blobs -->
  <div class="absolute top-1/2 left-1/4 w-72 h-72 bg-indigo-500 opacity-10 blur-3xl rounded-full pointer-events-none"></div>
  <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-purple-500 opacity-10 blur-3xl rounded-full pointer-events-none"></div>

  <div class="relative max-w-3xl mx-auto text-center">
    <div class="hero-badge inline-flex items-center gap-2 bg-indigo-500/25 border border-indigo-400/30 rounded-full px-3 py-1 text-indigo-200 text-xs font-bold uppercase tracking-widest mb-6">
      📖 <?= $total ?>+ EV Terms Explained for India
    </div>
    <h1 class="text-5xl lg:text-6xl font-black tracking-tight mb-4 leading-tight">
      EV <span style="background:linear-gradient(135deg,#818cf8,#c084fc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Glossary</span>
    </h1>
    <p class="hero-desc text-lg text-slate-300 max-w-xl mx-auto mb-7">
      Every electric vehicle term — from kWh to FAME II, BMS to V2G — explained in plain English for Indian buyers.
    </p>

    <!-- Search bar -->
    <div class="relative max-w-lg mx-auto">
      <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <input x-model="searchQ" @input="onSearch()" type="text"
             placeholder="Search terms — kWh, BMS, FAME II, V2G..."
             class="w-full rounded-2xl bg-white/10 backdrop-blur border border-white/20 pl-12 pr-12 py-4 text-white placeholder-white/40 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
      <button x-show="searchQ" @click="searchQ='';onSearch()" x-cloak
              class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/80 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <!-- Search result count -->
    <p class="text-white/40 text-xs mt-3 h-4"
       x-show="searchQ"
       x-text="filteredTerms.length + ' result' + (filteredTerms.length!==1?'s':'') + ' for \'' + searchQ + '\''"></p>
  </div>
</section>

<!-- ═══ STICKY CONTROLS ═══ -->
<div class="sticky top-16 z-40 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm">
  <div class="max-w-5xl mx-auto px-4">

    <!-- Letter nav -->
    <div class="flex gap-1 overflow-x-auto py-3 scrollbar-hide">
      <button @click="setLetter('all')"
              :class="activeLetter==='all' ? 'active' : ''"
              class="ltr-pill text-[.6rem] px-2 w-auto flex-shrink-0">All</button>
      <?php foreach ($letters as $letter): ?>
      <button @click="setLetter('<?= $letter ?>')"
              :class="activeLetter==='<?= $letter ?>' ? 'active' : ''"
              class="ltr-pill flex-shrink-0">
        <?= $letter ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Category filter -->
    <div class="flex gap-2 pb-3 overflow-x-auto scrollbar-hide">
      <?php
      $cats = [
        'all'         => ['All Terms','🔤'],
        'battery'     => ['Battery','🔋'],
        'charging'    => ['Charging','⚡'],
        'performance' => ['Performance','🏎️'],
        'policy'      => ['Policy','📋'],
        'general'     => ['General','💡'],
      ];
      foreach ($cats as $key => $c): ?>
      <button @click="setFilter('<?= $key ?>')"
              :class="activeFilter==='<?= $key ?>' ? 'active' : ''"
              class="filter-pill flex-shrink-0 flex items-center gap-1.5">
        <span><?= $c[1] ?></span> <?= $c[0] ?>
      </button>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<!-- ═══ CONTENT ═══ -->
<div class="max-w-5xl mx-auto px-4 py-10">

  <!-- Results count badge -->
  <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div class="flex items-center gap-2">
      <span class="text-sm font-bold text-slate-900" x-text="filteredTerms.length + ' terms'"></span>
      <span x-show="activeLetter!=='all'" x-cloak class="text-xs bg-indigo-100 text-indigo-700 font-bold px-2.5 py-1 rounded-full" x-text="'Letter: ' + activeLetter"></span>
      <span x-show="activeFilter!=='all'" x-cloak class="text-xs bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full" x-text="activeFilter"></span>
    </div>
    <button @click="setLetter('all');setFilter('all');searchQ='';onSearch()"
            x-show="activeLetter!=='all' || activeFilter!=='all' || searchQ"
            x-cloak
            class="text-xs text-slate-500 hover:text-slate-800 font-semibold transition-colors flex items-center gap-1">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
      Clear filters
    </button>
  </div>

  <!-- No results -->
  <div x-show="filteredTerms.length===0" x-cloak class="text-center py-20">
    <div class="text-5xl mb-4">🔍</div>
    <h3 class="text-lg font-black text-slate-900 mb-2">No terms found</h3>
    <p class="text-slate-500 text-sm mb-4">Try a different search or clear the filter.</p>
    <button @click="setLetter('all');setFilter('all');searchQ='';onSearch()"
            class="bg-indigo-600 text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-indigo-500 transition-colors">
      Show All Terms
    </button>
  </div>

  <!-- Grouped term display -->
  <template x-for="group in groupedFiltered" :key="group.letter">
    <div class="mb-10">
      <!-- Letter heading -->
      <div class="flex items-center gap-3 mb-5">
        <div class="letter-badge" x-text="group.letter"></div>
        <div class="flex-1 h-px bg-slate-200"></div>
        <span class="text-xs text-slate-400 font-semibold" x-text="group.terms.length + (group.terms.length===1?' term':' terms')"></span>
      </div>

      <!-- Term cards grid -->
      <div class="grid sm:grid-cols-2 gap-3">
        <template x-for="(t, i) in group.terms" :key="t.term">
          <div class="term-card animate-in" :style="`animation-delay:${i*40}ms`" x-data="{open:false}">
            <div class="flex items-start justify-between gap-3">
              <button @click="open=!open" class="flex-1 text-left">
                <h3 class="font-black text-slate-900 text-sm leading-snug" x-html="highlight(t.term)"></h3>
              </button>
              <div class="flex items-center gap-2 flex-shrink-0">
                <span class="text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full"
                      :class="`cat-${t.category}`"
                      x-text="t.category"></span>
                <button @click="open=!open" class="text-slate-400 hover:text-indigo-600 transition-colors">
                  <svg class="w-4 h-4 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
              </div>
            </div>
            <!-- Definition - always visible on large cards -->
            <p class="text-sm text-slate-600 mt-2 leading-relaxed" x-show="open || !$el.closest('.sm\\:grid-cols-2')" x-transition x-html="highlight(t.definition)"></p>
            <!-- Show short preview when closed -->
            <p class="text-xs text-slate-400 mt-1.5 line-clamp-2" x-show="!open" x-cloak x-html="highlight(t.definition)"></p>
          </div>
        </template>
      </div>
    </div>
  </template>

  <!-- CTA -->
  <div x-show="filteredTerms.length > 0" class="mt-14 rounded-3xl overflow-hidden" style="background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 50%,#0f0a1e 100%)">
    <div class="p-8 sm:p-12 text-center text-white relative overflow-hidden">
      <div class="absolute inset-0 opacity-5" style="background-image:radial-gradient(rgba(255,255,255,.6) 1px,transparent 1px);background-size:24px 24px"></div>
      <div class="relative">
        <div class="text-4xl mb-4">⚡</div>
        <h2 class="text-2xl sm:text-3xl font-black mb-3">Ready to find your perfect EV?</h2>
        <p class="text-slate-400 mb-8 text-sm max-w-md mx-auto">Now that you speak EV — use our AI-powered finder to match the right electric vehicle to your life.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <a href="<?= base_url('find-my-ev') ?>"
             class="inline-flex items-center justify-center gap-2 font-bold px-7 py-3.5 rounded-full text-sm transition-all duration-200 text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-indigo-500/30"
             style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            Find My EV with AI ⚡
          </a>
          <a href="<?= base_url('vehicles') ?>"
             class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-7 py-3.5 rounded-full text-sm transition-all duration-200 hover:-translate-y-0.5">
            Browse All EVs →
          </a>
          <a href="<?= base_url('compare') ?>"
             class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-7 py-3.5 rounded-full text-sm transition-all duration-200 hover:-translate-y-0.5">
            Compare EVs ⚖️
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
</div>

<script>
const GLOSSARY_TERMS = <?= json_encode($jsTerms, JSON_UNESCAPED_UNICODE) ?>;

function glossaryApp() {
  return {
    allTerms: GLOSSARY_TERMS,
    filteredTerms: [],
    groupedFiltered: [],
    activeLetter: 'all',
    activeFilter: 'all',
    searchQ: '',

    init() { this.apply(); },

    setLetter(l) {
      this.activeLetter = l;
      this.apply();
      if (l !== 'all') {
        this.$nextTick(() => {
          const el = document.getElementById('letter-' + l);
          if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      }
    },

    setFilter(f) {
      this.activeFilter = f;
      this.apply();
    },

    onSearch() { this.activeLetter = 'all'; this.apply(); },

    apply() {
      const q = this.searchQ.toLowerCase().trim();
      const letter = this.activeLetter;
      const cat = this.activeFilter;

      this.filteredTerms = this.allTerms.filter(t => {
        if (letter !== 'all' && t.letter !== letter) return false;
        if (cat !== 'all' && t.category !== cat) return false;
        if (q && !t.term.toLowerCase().includes(q) && !t.definition.toLowerCase().includes(q)) return false;
        return true;
      });

      // Group by letter
      const map = {};
      for (const t of this.filteredTerms) {
        if (!map[t.letter]) map[t.letter] = [];
        map[t.letter].push(t);
      }
      this.groupedFiltered = Object.keys(map).sort().map(l => ({ letter: l, terms: map[l] }));
    },

    highlight(text) {
      if (!this.searchQ.trim()) return text;
      const q = this.searchQ.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      return text.replace(new RegExp(`(${q})`, 'gi'), '<mark>$1</mark>');
    }
  }
}
</script>

<?= $this->endSection() ?>
