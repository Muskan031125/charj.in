<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'EV Charging Stations in India | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Find EV charging stations near you across India.') ?>">
<style>
[x-cloak]{display:none!important}
@keyframes fadeInUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
@keyframes gradShift{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}

.hero-bg{background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12);}
.station-card{background:#fff;border-radius:1.25rem;border:1.5px solid #f1f5f9;transition:all .2s ease;box-shadow:0 1px 4px rgba(0,0,0,.05);}
.station-card:hover{border-color:#86efac;box-shadow:0 6px 24px rgba(34,197,94,.12);transform:translateY(-2px);}
.speed-rapid{background:#fee2e2;color:#991b1b;}
.speed-fast{background:#fef3c7;color:#92400e;}
.speed-slow{background:#f0fdf4;color:#166534;}
.city-pill{padding:.375rem 1rem;border-radius:9999px;font-size:.75rem;font-weight:700;cursor:pointer;transition:all .15s;border:1.5px solid #e2e8f0;color:#64748b;background:#fff;white-space:nowrap;}
.city-pill:hover{border-color:rgba(0,168,150,.4);color:#00A896;}
.city-pill.active{background:linear-gradient(135deg,#00A896,#007A6E);color:#fff;border-color:transparent;box-shadow:0 2px 8px rgba(0,168,150,.35);}
.live-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse 1.5s ease-in-out infinite;}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$activeCityFilter = strtolower($_GET['city'] ?? 'all');
if ($activeCityFilter === '') $activeCityFilter = 'all';

$topCities = ['All','Delhi','Mumbai','Bangalore','Pune','Hyderabad','Chennai','Ahmedabad','Kolkata','Jaipur','Noida','Gurgaon','Surat','Lucknow'];

$speedBadge = fn(string $s): array => match(strtolower($s)) {
    'rapid'  => ['speed-rapid','⚡ Rapid DC'],
    'fast'   => ['speed-fast','🔆 Fast AC/DC'],
    default  => ['speed-slow','🔌 Slow AC'],
};

$connColors = [
    'CCS2'    => 'bg-blue-100 text-blue-800',
    'Type 2'  => 'bg-indigo-100 text-indigo-800',
    'CHAdeMO' => 'bg-purple-100 text-purple-800',
    'GB/T'    => 'bg-orange-100 text-orange-800',
    'Type 1'  => 'bg-teal-100 text-teal-800',
];

// City lat/lng for API
$cityCoords = [
    'delhi'=>[28.6139,77.2090],'mumbai'=>[19.0760,72.8777],'bangalore'=>[12.9716,77.5946],
    'bengaluru'=>[12.9716,77.5946],'pune'=>[18.5204,73.8567],'hyderabad'=>[17.3850,78.4867],
    'chennai'=>[13.0827,80.2707],'ahmedabad'=>[23.0225,72.5714],'kolkata'=>[22.5726,88.3639],
    'jaipur'=>[26.9124,75.7873],'noida'=>[28.5355,77.3910],'gurgaon'=>[28.4595,77.0266],
    'gurugram'=>[28.4595,77.0266],'surat'=>[21.1702,72.8311],'lucknow'=>[26.8467,80.9462],
    'chandigarh'=>[30.7333,76.7794],'coimbatore'=>[11.0168,76.9558],'nagpur'=>[21.1458,79.0882],
    'indore'=>[22.7196,75.8577],'kochi'=>[9.9312,76.2673],'vadodara'=>[22.3072,73.1812],
    'visakhapatnam'=>[17.6868,83.2185],'nashik'=>[19.9975,73.7898],'bhopal'=>[23.2599,77.4126],
];
$initLat = $cityCoords[$activeCityFilter][0] ?? 28.6139;
$initLng = $cityCoords[$activeCityFilter][1] ?? 77.2090;
?>

<div x-data="chargingApp()" x-init="init()" class="min-h-screen bg-slate-50">

<!-- ═══ HERO ═══ -->
<section class="hero-sm hero-bg relative overflow-hidden pt-20 pb-10 px-4">
  <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:28px 28px"></div>

  <div class="relative max-w-6xl mx-auto text-center">
    <div class="flex flex-col items-center gap-5">
      <!-- Center content -->
      <div>
        <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-4" style="background:rgba(0,168,150,.1);border:1.5px solid rgba(0,168,150,.2);color:#00A896">
          <div class="live-dot"></div>
          Live Charging Network · India
        </div>
        <h1 class="text-3xl sm:text-4xl font-black leading-tight mb-2" style="color:#0F172A">
          EV Charging Stations Near You
        </h1>
        <p class="text-sm max-w-lg mx-auto" style="color:#475569">
          Real-time charging data across India — fast DC chargers, AC wallboxes, and public charge points.
        </p>
        <!-- Stats -->
        <div class="flex flex-wrap justify-center gap-4 mt-4">
          <div class="flex items-center gap-2">
            <span class="text-2xl font-black" style="color:#00A896" x-text="allStations.length || '<?= count($stations ?? []) ?>'"></span>
            <span class="text-xs" style="color:#64748B">stations found</span>
          </div>
          <div style="color:#CBD5E1">·</div>
          <div class="text-xs" style="color:#64748B" x-text="'Source: ' + dataSource"></div>
        </div>
      </div>

      <!-- Locate button + quick cities — centered -->
      <div class="flex flex-col items-center gap-3">
        <button @click="findNearMe()"
                :disabled="locating"
                class="inline-flex items-center gap-3 rounded-2xl text-white font-bold px-6 py-3.5 text-sm transition-all hover:-translate-y-0.5 disabled:opacity-70"
                style="background:linear-gradient(135deg,#00A896,#007A6E);box-shadow:0 6px 24px rgba(0,168,150,.3)">
          <div x-show="!locating" class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">📍</div>
          <div x-show="locating" x-cloak class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
          </div>
          <div class="text-left">
            <div class="font-black text-sm" x-text="locating ? 'Locating you...' : 'Find Chargers Near Me'"></div>
            <div class="text-xs font-medium opacity-80" x-text="locating ? 'Getting your GPS...' : 'Uses your location · Works instantly'"></div>
          </div>
        </button>
        <p x-show="locErr" x-cloak x-text="locErr" class="text-xs text-center" style="color:#ef4444"></p>
        <div class="flex flex-wrap justify-center gap-2">
          <?php foreach (['Delhi','Mumbai','Bangalore','Pune','Hyderabad','Chennai'] as $qc): ?>
          <button @click="loadCity('<?= strtolower($qc) ?>')"
                  class="text-xs font-semibold transition-colors px-3 py-1.5 rounded-xl"
                  style="color:#64748B;border:1px solid rgba(0,168,150,.2);background:#fff"
                  onmouseover="this.style.background='rgba(0,168,150,.08)';this.style.color='#00A896'"
                  onmouseout="this.style.background='#fff';this.style.color='#64748B'">
            <?= $qc ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CITY FILTER ═══ -->
<div class="sticky top-16 z-30 bg-white border-b border-slate-100 shadow-sm">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex gap-2 py-3 overflow-x-auto scrollbar-hide">
      <?php foreach ($topCities as $tc): ?>
      <?php $slug = strtolower($tc) === 'all' ? 'all' : strtolower($tc); ?>
      <button @click="loadCity('<?= $slug ?>')"
              :class="activeCity==='<?= $slug ?>' ? 'active' : ''"
              class="city-pill flex-shrink-0">
        <?= $tc ?>
      </button>
      <?php endforeach; ?>
      <?php
      $extraCities = array_diff(
        array_map('ucfirst', $cities ?? []),
        array_map('ucfirst', $topCities)
      );
      foreach ($extraCities as $ec): ?>
      <button @click="loadCity('<?= strtolower($ec) ?>')"
              :class="activeCity==='<?= strtolower($ec) ?>' ? 'active' : ''"
              class="city-pill flex-shrink-0">
        <?= esc($ec) ?>
      </button>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ═══ FILTER BAR ═══ -->
<div class="max-w-6xl mx-auto px-4 pt-6 pb-4 flex flex-wrap items-center justify-between gap-3">
  <div class="flex items-center gap-3">
    <span class="text-sm font-black text-slate-900" x-text="filteredStations.length + ' stations'"></span>
    <span class="text-xs text-slate-400" x-text="activeCity === 'all' ? 'across India' : 'in ' + activeCity.charAt(0).toUpperCase() + activeCity.slice(1)"></span>
    <!-- Loading indicator -->
    <div x-show="loading" x-cloak class="flex items-center gap-2 text-xs text-green-600 font-semibold">
      <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
      Fetching live data...
    </div>
  </div>
  <!-- Speed filter -->
  <div class="flex gap-2">
    <?php foreach (['all'=>'All Speeds','rapid'=>'⚡ Rapid','fast'=>'Fast','slow'=>'Slow AC'] as $s=>$l): ?>
    <button @click="speedFilter='<?= $s ?>';applyFilter()"
            :class="speedFilter==='<?= $s ?>' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-400'"
            class="text-xs font-bold px-3 py-1.5 rounded-full transition-all">
      <?= $l ?>
    </button>
    <?php endforeach; ?>
  </div>
</div>

<!-- ═══ STATIONS GRID ═══ -->
<div class="max-w-6xl mx-auto px-4 pb-8">

  <!-- Loading skeleton -->
  <div x-show="loading" x-cloak class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <template x-for="i in 6">
      <div class="station-card p-5 animate-pulse">
        <div class="h-4 bg-slate-100 rounded w-3/4 mb-3"></div>
        <div class="h-3 bg-slate-100 rounded w-1/2 mb-4"></div>
        <div class="h-3 bg-slate-100 rounded w-full mb-2"></div>
        <div class="h-3 bg-slate-100 rounded w-2/3"></div>
      </div>
    </template>
  </div>

  <!-- No results -->
  <div x-show="!loading && filteredStations.length===0" x-cloak class="py-24 text-center">
    <div class="text-5xl mb-4">⚡</div>
    <h3 class="text-lg font-black text-slate-900 mb-2" x-text="speedFilter !== 'all' ? 'No ' + speedFilter + ' chargers found here' : 'No stations found here yet'"></h3>
    <p class="text-slate-500 text-sm mb-4">
      <span x-show="speedFilter !== 'all'">Try removing the speed filter, or </span>try a different city — India's charging network is expanding rapidly.
    </p>
    <div class="flex flex-wrap gap-3 justify-center">
      <button x-show="speedFilter !== 'all'" @click="speedFilter='all';applyFilter()"
              class="bg-slate-800 text-white font-bold px-5 py-2.5 rounded-full text-sm hover:bg-slate-700 transition-colors">
        Remove Speed Filter
      </button>
      <button @click="loadCity('all')" class="bg-green-600 text-white font-bold px-6 py-3 rounded-full text-sm hover:bg-green-700 transition-colors">
        Show All India Stations
      </button>
    </div>
  </div>

  <!-- Stations grid -->
  <div x-show="!loading" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <template x-for="(st, idx) in filteredStations" :key="st.id || idx">
      <div class="station-card p-5 flex flex-col" :style="`animation:fadeInUp .3s ${idx*40}ms both`">
        <!-- Header -->
        <div class="flex items-start justify-between gap-2 mb-2">
          <div class="flex-1 min-w-0">
            <h3 class="font-black text-slate-900 text-sm leading-tight" x-text="st.name"></h3>
            <!-- Show operator only if it differs from the name -->
            <p class="text-xs text-slate-400 mt-0.5 truncate"
               x-show="st.operator && st.operator !== st.name"
               x-text="st.operator"></p>
          </div>
          <span class="flex-shrink-0 text-[10px] font-black px-2.5 py-1 rounded-full whitespace-nowrap"
                :class="st.charging_speed==='rapid'?'speed-rapid':st.charging_speed==='fast'?'speed-fast':'speed-slow'"
                x-text="st.charging_speed==='rapid'?'⚡ Rapid DC':st.charging_speed==='fast'?'🔆 Fast':'🔌 Slow AC'"></span>
        </div>

        <!-- Address — show only when present -->
        <p class="text-xs text-slate-500 leading-relaxed mb-3 flex items-start gap-1.5"
           x-show="(st.address || '') || st.city">
          <svg class="w-3 h-3 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <span x-text="[st.address, st.city].filter(Boolean).join(', ')"></span>
        </p>
        <!-- Coordinates fallback when no address -->
        <p class="text-xs text-slate-400 leading-relaxed mb-3 flex items-start gap-1.5"
           x-show="!st.address && !st.city && st.lat">
          <svg class="w-3 h-3 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          <span x-text="'~' + Number(st.lat).toFixed(4) + ', ' + Number(st.lng).toFixed(4)"></span>
        </p>

        <!-- Connectors -->
        <div class="flex flex-wrap gap-1.5 mb-3" x-show="st.connector_types && st.connector_types.length">
          <template x-for="ct in (st.connector_types||[])" :key="ct">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700" x-text="ct"></span>
          </template>
        </div>

        <!-- Stats -->
        <div class="flex flex-wrap gap-3 text-xs text-slate-600 mb-4">
          <span x-show="st.total_ports" class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span x-text="st.total_ports + ' port' + (st.total_ports!=1?'s':'')"></span>
          </span>
          <span x-show="st.max_kw" class="flex items-center gap-1 text-green-600 font-semibold">
            <span x-text="'Up to ' + st.max_kw + ' kW'"></span>
          </span>
          <span x-show="st.price_per_kwh" class="flex items-center gap-1">
            <span x-text="'₹' + Number(st.price_per_kwh).toFixed(2) + '/kWh'"></span>
          </span>
          <span x-show="st.is_open_24x7" class="bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">24×7</span>
          <!-- Source badge -->
          <span x-show="st.source==='ocm' || st.source==='osm'" class="text-blue-400 text-[9px] font-bold uppercase">Live ●</span>
        </div>

        <div class="flex-1"></div>

        <!-- Directions button — use lat/lng when available for accuracy -->
        <a :href="st.lat && st.lng
              ? 'https://www.google.com/maps/dir/?api=1&destination=' + st.lat + ',' + st.lng
              : 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent((st.name||'') + ' EV charging ' + (st.city||''))"
           target="_blank" rel="noopener"
           class="flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-green-700 text-white font-bold py-2.5 text-sm transition-all duration-150">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          Get Directions
        </a>
      </div>
    </template>
  </div>

  <!-- Add station CTA -->
  <div x-show="!loading" class="mt-12 rounded-3xl overflow-hidden" style="background:linear-gradient(135deg,#0f172a,#052e16)">
    <div class="p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-6 text-white">
      <div>
        <h3 class="text-xl font-black mb-1">Own a charging station?</h3>
        <p class="text-slate-400 text-sm">List your station for free — reach thousands of EV owners searching near you.</p>
      </div>
      <div class="flex gap-3 flex-shrink-0">
        <a href="<?= base_url('find-my-ev') ?>"
           class="flex items-center gap-2 font-bold px-6 py-3 rounded-full text-sm transition-all hover:-translate-y-0.5 text-white"
           style="background:linear-gradient(135deg,#16a34a,#0d9488)">
          Find My EV ⚡
        </a>
        <a href="<?= base_url('home-charger-guide') ?>"
           class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-6 py-3 rounded-full text-sm transition-all">
          Home Charger Guide
        </a>
      </div>
    </div>
  </div>
</div>

</div>

<script>
// DB stations from PHP
const DB_STATIONS = <?= json_encode(array_map(function($s) {
    return [
        'id'             => $s['id'] ?? 0,
        'name'           => $s['name'] ?? '',
        'address'        => $s['address'] ?? '',
        'city'           => strtolower($s['city'] ?? ''),
        'lat'            => (float)($s['latitude'] ?? 0),
        'lng'            => (float)($s['longitude'] ?? 0),
        'operator'       => $s['operator'] ?? '',
        'charging_speed' => $s['charging_speed'] ?? 'slow',
        'max_kw'         => (float)($s['max_power_kw'] ?? 0),
        'connector_types'=> array_filter(explode(',', $s['connector_types'] ?? '')),
        'total_ports'    => (int)($s['total_ports'] ?? 0),
        'is_open_24x7'   => !empty($s['is_open_24x7']),
        'price_per_kwh'  => $s['price_per_kwh'] ?? null,
        'status'         => $s['status'] ?? 'operational',
        'source'         => 'db',
    ];
}, $stations ?? []), JSON_UNESCAPED_UNICODE) ?>;

const INIT_LAT  = <?= $initLat ?>;
const INIT_LNG  = <?= $initLng ?>;
const INIT_CITY = '<?= esc($activeCityFilter) ?>';

const CITY_COORDS = {
  delhi:[28.6139,77.2090], mumbai:[19.0760,72.8777], bangalore:[12.9716,77.5946],
  bengaluru:[12.9716,77.5946], pune:[18.5204,73.8567], hyderabad:[17.3850,78.4867],
  chennai:[13.0827,80.2707], ahmedabad:[23.0225,72.5714], kolkata:[22.5726,88.3639],
  jaipur:[26.9124,75.7873], noida:[28.5355,77.3910], gurgaon:[28.4595,77.0266],
  gurugram:[28.4595,77.0266], surat:[21.1702,72.8311], lucknow:[26.8467,80.9462],
  chandigarh:[30.7333,76.7794], coimbatore:[11.0168,76.9558], nagpur:[21.1458,79.0882],
  indore:[22.7196,75.8577], kochi:[9.9312,76.2673], vadodara:[22.3072,73.1812],
  visakhapatnam:[17.6868,83.2185], nashik:[19.9975,73.7898], bhopal:[23.2599,77.4126],
};

function chargingApp() {
  return {
    allStations: [...DB_STATIONS],
    filteredStations: [...DB_STATIONS],
    activeCity: INIT_CITY,
    speedFilter: 'all',
    loading: false,
    locating: false,
    locErr: '',
    dataSource: DB_STATIONS.length ? 'Charj Database' : 'Loading...',

    async init() {
      if (INIT_CITY && INIT_CITY !== 'all') {
        await this.fetchLive(INIT_LAT, INIT_LNG, INIT_CITY);
      } else {
        // 'all' mode — just show all DB stations, no single-city API call
        this.allStations = [...DB_STATIONS];
        this.dataSource = DB_STATIONS.length ? 'Charj Database' : 'No stations yet';
        this.applyFilter();
      }
    },

    async loadCity(city) {
      this.activeCity = city;
      this.speedFilter = 'all';
      const url = new URL(window.location);
      url.searchParams.set('city', city);
      window.history.pushState({}, '', url);

      if (city === 'all') {
        this.allStations = [...DB_STATIONS];
        this.dataSource = 'Charj Database';
        this.applyFilter();
        return;
      }

      const c = CITY_COORDS[city];
      if (c) {
        await this.fetchLive(c[0], c[1], city);
      } else {
        // City not in coords map — filter DB by city name only
        this.allStations = DB_STATIONS.filter(s => s.city === city.toLowerCase());
        this.dataSource = 'Charj Database';
        this.applyFilter();
      }
    },

    async findNearMe() {
      if (!navigator.geolocation) { this.locErr = 'Geolocation not supported by your browser.'; return; }
      this.locating = true; this.locErr = '';
      navigator.geolocation.getCurrentPosition(
        async pos => {
          const lat = pos.coords.latitude, lng = pos.coords.longitude;
          this.locating = false;
          try {
            const r = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const d = await r.json();
            const city = (d.address.city || d.address.town || d.address.village || '').toLowerCase();
            if (city) this.activeCity = city;
          } catch(e) {}
          await this.fetchLive(lat, lng, this.activeCity);
        },
        err => {
          this.locating = false;
          this.locErr = err.code === 1 ? 'Location denied. Select a city below.' : 'Could not get location.';
        },
        { timeout: 8000 }
      );
    },

    async fetchLive(lat, lng, city) {
      this.loading = true;
      const dbForCity = city && city !== 'all'
        ? DB_STATIONS.filter(s => s.city === city.toLowerCase())
        : DB_STATIONS;

      try {
        const params = new URLSearchParams({ lat, lng, city: city === 'all' ? '' : (city || '') });
        const resp = await fetch(`<?= site_url('charging-stations/api') ?>?${params}`);
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const json = await resp.json();

        if (json.success && json.stations && json.stations.length > 0) {
          // Merge: live API first, then DB stations not already present
          const apiIds = new Set(json.stations.map(s => String(s.id)));
          const dbExtra = dbForCity.filter(s => !apiIds.has(String(s.id)));
          this.allStations = [...json.stations, ...dbExtra];
          this.dataSource = 'OpenChargeMap + Charj Database';
        } else {
          // API returned nothing — use DB. If DB also empty, fall back to all DB so page isn't blank
          this.allStations = dbForCity.length > 0 ? dbForCity : [...DB_STATIONS];
          this.dataSource = dbForCity.length > 0 ? 'Charj Database' : 'Charj Database (all India)';
        }
      } catch(e) {
        this.allStations = dbForCity.length > 0 ? dbForCity : [...DB_STATIONS];
        this.dataSource = dbForCity.length > 0 ? 'Charj Database' : 'Charj Database (all India)';
      }

      this.applyFilter();
      this.loading = false;
    },

    applyFilter() {
      this.filteredStations = this.allStations.filter(s => {
        if (this.speedFilter !== 'all' && s.charging_speed !== this.speedFilter) return false;
        return true;
      });
    },
  }
}
</script>

<?= $this->endSection() ?>


