<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'EV Trip Charging Planner | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Plan charging stops for your intercity EV trip in India. Get the number of fast-charge stops, a visual itinerary and estimated charging time.') ?>">
<style>
[x-cloak]{display:none!important}
@keyframes fadeInUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.trip-crosslink{transition:transform .22s cubic-bezier(.22,1,.36,1),box-shadow .22s cubic-bezier(.22,1,.36,1),border-color .22s ease}
.trip-crosslink:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(0,168,150,.14)}
.trip-icon{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.125rem;background:rgba(0,168,150,.12);border:1.5px solid rgba(0,168,150,.2);margin:0 auto}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm pt-24 md:pt-32 pb-8 px-4 relative overflow-hidden" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4,#F7FFFE)">
  <div class="relative max-w-3xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-4"
         style="background:rgba(0,168,150,.10);border:1px solid rgba(0,168,150,.25);color:#00A896">
      🔌 EV Trip Charging Planner
    </div>
    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black leading-tight mb-3" style="color:#0F172A">Plan Your EV Charging Stops</h1>
    <p class="text-sm sm:text-base lg:text-lg" style="color:#475569">Pick a route, set your range — we'll map out where to charge so you reach your destination stress-free.</p>
  </div>
</section>

<div class="px-4 py-8 lg:py-12" style="background:#F7FFFE">
  <div class="mx-auto max-w-lg lg:max-w-5xl" x-data="routePlanner()" x-cloak style="animation:fadeInUp .4s ease both">

    <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-start">

      <!-- ── LEFT: Inputs ── -->
      <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-8 space-y-6" style="border:1px solid #EAFFF4">
        <h2 class="text-lg font-black" style="color:#0F172A">Your Trip</h2>

        <!-- Input 1: Route -->
        <div>
          <label class="block text-sm font-semibold mb-2" style="color:#475569">Choose your route</label>
          <select x-model="routeKey" @change="onRouteChange()"
            class="w-full rounded-xl px-4 py-3 text-base font-semibold focus:outline-none focus:ring-2 transition-all"
            style="border:1px solid #d7f5ec;color:#0F172A;--tw-ring-color:#00A896">
            <template x-for="(r,i) in routes" :key="i">
              <option :value="i" x-text="r.label"></option>
            </template>
            <option value="custom">Custom distance…</option>
          </select>

          <!-- Custom distance reveal -->
          <div x-show="routeKey === 'custom'" x-transition class="mt-3 rounded-2xl p-4" style="background:#F0FFF9;border:1px solid #d7f5ec">
            <div class="flex items-center justify-between mb-2">
              <label class="text-sm font-semibold" style="color:#475569">Custom distance</label>
              <div class="flex items-center gap-1">
                <span class="text-lg font-bold" style="color:#00A896" x-text="customDistance"></span>
                <span class="text-xs" style="color:#94A3B8">km</span>
              </div>
            </div>
            <input type="range" min="50" max="800" step="5" x-model.number="customDistance"
              class="w-full h-2 rounded-full cursor-pointer" style="accent-color:#00A896">
            <input type="number" min="50" max="800" x-model.number="customDistance"
              class="w-full mt-3 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2"
              style="border:1px solid #d7f5ec;color:#0F172A;--tw-ring-color:#00A896">
          </div>

          <p class="text-xs mt-2" style="color:#94A3B8">Total trip distance: <span class="font-bold" style="color:#00A896" x-text="distance + ' km'"></span></p>
        </div>

        <!-- Input 2: Real-world range -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold" style="color:#475569">EV real-world range</label>
            <div class="flex items-center gap-1">
              <span class="text-lg font-bold" style="color:#00A896" x-text="realRange"></span>
              <span class="text-xs" style="color:#94A3B8">km</span>
            </div>
          </div>
          <input type="range" min="80" max="500" step="5" x-model.number="realRange"
            class="w-full h-2 rounded-full cursor-pointer" style="accent-color:#00A896">
          <div class="flex justify-between text-xs mt-1" style="color:#94A3B8"><span>80 km</span><span>500 km</span></div>
          <div class="flex flex-wrap gap-2 mt-3">
            <template x-for="ev in evPresets" :key="ev.name">
              <button type="button" @click="realRange = ev.km"
                :class="realRange == ev.km ? 'text-white' : ''"
                :style="realRange == ev.km ? 'background:#00A896;border-color:#00A896' : 'background:#F0FFF9;border-color:#d7f5ec;color:#0F766E'"
                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold cursor-pointer transition-all" style="border:1px solid">
                <span x-text="ev.name"></span><span class="ml-1 opacity-70" x-text="ev.km + 'km'"></span>
              </button>
            </template>
          </div>
        </div>

        <!-- Input 3: Starting charge % -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold" style="color:#475569">Starting charge</label>
            <div class="flex items-center gap-1">
              <span class="text-lg font-bold" style="color:#00A896" x-text="startCharge"></span>
              <span class="text-xs" style="color:#94A3B8">%</span>
            </div>
          </div>
          <input type="range" min="50" max="100" step="5" x-model.number="startCharge"
            class="w-full h-2 rounded-full cursor-pointer" style="accent-color:#00A896">
          <div class="flex justify-between text-xs mt-1" style="color:#94A3B8"><span>50%</span><span>100%</span></div>
        </div>

        <!-- Input 4: Buffer -->
        <div>
          <label class="block text-sm font-semibold mb-3" style="color:#475569">Arrive with at least</label>
          <div class="grid grid-cols-3 gap-2">
            <template x-for="b in [10,15,20]" :key="b">
              <label
                :style="minBuffer === b ? 'background:#EAFFF4;box-shadow:0 0 0 2px #00A896 inset' : 'background:#F7FFFE;box-shadow:0 0 0 1px #d7f5ec inset'"
                class="flex flex-col items-center gap-0.5 p-3 rounded-2xl cursor-pointer transition-all">
                <input type="radio" x-model.number="minBuffer" :value="b" class="sr-only">
                <span class="text-lg font-extrabold" style="color:#00A896" x-text="b + '%'"></span>
                <span class="text-xs" style="color:#94A3B8">reserve</span>
              </label>
            </template>
          </div>
          <p class="text-xs mt-2" style="color:#94A3B8">Don't let the battery drop below this on any leg.</p>
        </div>

        <!-- Plan trip button — results only (re)calculate on click, not on every slider drag -->
        <button type="button" @click="calc()"
          class="w-full flex items-center justify-center gap-2 rounded-2xl py-3.5 text-base font-black text-white transition-all"
          style="background:linear-gradient(135deg,#00A896,#007A6E);box-shadow:0 6px 20px rgba(0,168,150,.3)"
          onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(0,168,150,.4)'"
          onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px rgba(0,168,150,.3)'">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          <span x-text="calculated ? 'Recalculate Trip' : 'Plan My Trip'"></span>
        </button>
      </div>

      <!-- ── RIGHT: Result ── -->
      <div class="mt-6 lg:mt-0 space-y-5">

        <!-- Placeholder — shown until "Plan My Trip" is clicked -->
        <div x-show="!calculated" class="rounded-3xl p-10 text-center" style="background:#F0FFF9;border:1.5px dashed #b8ebdd">
          <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:rgba(0,168,150,.12)">
            <svg class="w-7 h-7" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </div>
          <p class="font-black text-base mb-1.5" style="color:#0F172A">Set your trip details</p>
          <p class="text-sm" style="color:#64748B">Choose a route and range on the left, then hit <strong>Plan My Trip</strong> to see your charging stops.</p>
        </div>

        <template x-if="calculated">
        <div x-cloak class="space-y-5">

        <!-- Big teal result card -->
        <div class="rounded-3xl shadow-xl p-6 sm:p-8 text-white" style="background:linear-gradient(150deg,#00A896,#007A6E)">
          <template x-if="result.stops === 0">
            <div class="text-center">
              <div class="text-5xl mb-2">✓</div>
              <div class="text-2xl sm:text-3xl font-black">Doable in one charge</div>
              <p class="text-white/80 text-sm mt-2" x-text="'Your ~' + result.usableFirst + ' km of usable range covers the full ' + result.distance + ' km trip.'"></p>
            </div>
          </template>
          <template x-if="result.stops > 0">
            <div class="text-center">
              <div class="text-5xl sm:text-6xl font-black leading-none" x-text="result.stops"></div>
              <div class="text-lg sm:text-xl font-bold mt-1" x-text="result.stops === 1 ? 'charging stop needed' : 'charging stops needed'"></div>
              <div class="inline-flex items-center gap-2 mt-4 rounded-full px-4 py-1.5 text-sm font-semibold" style="background:rgba(255,255,255,.15)">
                ⏱ <span x-text="'~' + result.chargeTime + ' min total charging'"></span>
              </div>
              <p class="text-white/80 text-xs mt-3">Assumes ~30 min per DC fast-charge stop (charging to ~80%).</p>
            </div>
          </template>
        </div>

        <!-- Itinerary timeline -->
        <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-8" style="border:1px solid #EAFFF4">
          <h3 class="text-base font-black mb-5" style="color:#0F172A">Your journey</h3>
          <div class="relative pl-8">
            <!-- vertical line -->
            <div class="absolute left-[7px] top-2 bottom-2 w-0.5" style="background:#00E676"></div>

            <!-- Start -->
            <div class="relative mb-6">
              <div class="absolute -left-8 top-0.5 w-4 h-4 rounded-full" style="background:#00A896;box-shadow:0 0 0 3px #EAFFF4"></div>
              <div class="text-sm font-bold" style="color:#0F172A" x-text="result.startLabel"></div>
              <div class="text-xs" style="color:#64748B" x-text="'0 km · start at ' + result.startCharge + '%'"></div>
            </div>

            <!-- Charging stops -->
            <template x-for="(km,idx) in result.stopMarks" :key="idx">
              <div class="relative mb-6">
                <div class="absolute -left-8 top-0.5 w-4 h-4 rounded-full" style="background:#00E676;box-shadow:0 0 0 3px #EAFFF4"></div>
                <div class="rounded-2xl p-3" style="background:#F0FFF9;border:1px solid #d7f5ec">
                  <div class="text-sm font-bold" style="color:#00766E" x-text="'Charge Stop ' + (idx+1)"></div>
                  <div class="text-xs mt-0.5" style="color:#475569" x-text="'~' + km + ' km mark · ~30 min DC fast charge'"></div>
                </div>
              </div>
            </template>

            <!-- Destination -->
            <div class="relative">
              <div class="absolute -left-8 top-0.5 w-4 h-4 rounded-full flex items-center justify-center" style="background:#00A896;box-shadow:0 0 0 3px #EAFFF4">
                <span class="text-white text-[8px]">★</span>
              </div>
              <div class="text-sm font-bold" style="color:#0F172A" x-text="result.destLabel"></div>
              <div class="text-xs" style="color:#64748B" x-text="result.distance + ' km · destination'"></div>
            </div>
          </div>

          <!-- Find stations note -->
          <a href="<?= base_url('charging-stations') ?>"
             class="mt-5 flex items-center justify-center gap-2 w-full py-3 rounded-2xl text-sm font-semibold text-white transition-colors"
             style="background:#00A896">
            📍 Find actual stations along the way
          </a>
        </div>

        </div>
        </template>

        <!-- Cross-links -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <a href="<?= base_url('can-i-make-it') ?>" class="trip-crosslink rounded-2xl p-4 text-center" style="background:#EAFFF4;border:1px solid #d7f5ec">
            <div class="trip-icon mb-2">🗺️</div>
            <div class="text-xs font-bold" style="color:#0F766E">Can I make it?</div>
            <div class="text-[11px]" style="color:#64748B">Single-charge range check</div>
          </a>
          <a href="<?= base_url('charging-stations') ?>" class="trip-crosslink rounded-2xl p-4 text-center" style="background:#EAFFF4;border:1px solid #d7f5ec">
            <div class="trip-icon mb-2">🔌</div>
            <div class="text-xs font-bold" style="color:#0F766E">Charging stations</div>
            <div class="text-[11px]" style="color:#64748B">Find chargers near you</div>
          </a>
          <a href="<?= base_url('charging-cost') ?>" class="trip-crosslink rounded-2xl p-4 text-center" style="background:#EAFFF4;border:1px solid #d7f5ec">
            <div class="trip-icon mb-2">💰</div>
            <div class="text-xs font-bold" style="color:#0F766E">Charging cost</div>
            <div class="text-[11px]" style="color:#64748B">Estimate trip charging cost</div>
          </a>
        </div>

        <!-- Disclaimer -->
        <p class="text-xs text-center px-4" style="color:#94A3B8">
          Indicative estimates only. Actual stops depend on real driving conditions, terrain, weather, AC use, charger availability and battery health. Always plan a buffer and verify live station status before you set off.
        </p>
      </div>

    </div>
  </div>
</div>

<script>
function routePlanner() {
  return {
    routes: [
      {label: 'Delhi → Jaipur', km: 280, from: 'Delhi', to: 'Jaipur'},
      {label: 'Delhi → Agra', km: 230, from: 'Delhi', to: 'Agra'},
      {label: 'Mumbai → Pune', km: 150, from: 'Mumbai', to: 'Pune'},
      {label: 'Bangalore → Chennai', km: 350, from: 'Bangalore', to: 'Chennai'},
      {label: 'Bangalore → Mysore', km: 145, from: 'Bangalore', to: 'Mysore'},
      {label: 'Hyderabad → Vijayawada', km: 275, from: 'Hyderabad', to: 'Vijayawada'},
      {label: 'Delhi → Chandigarh', km: 245, from: 'Delhi', to: 'Chandigarh'},
      {label: 'Mumbai → Nashik', km: 165, from: 'Mumbai', to: 'Nashik'},
      {label: 'Pune → Goa', km: 445, from: 'Pune', to: 'Goa'},
      {label: 'Chennai → Pondicherry', km: 160, from: 'Chennai', to: 'Pondicherry'},
    ],
    evPresets: [
      {name: 'Ather 450X', km: 110},
      {name: 'Nexon EV', km: 380},
      {name: 'Tiago EV', km: 250},
      {name: 'MG ZS EV', km: 400},
    ],

    routeKey: 0,
    customDistance: 300,
    realRange: 200,
    startCharge: 90,
    minBuffer: 15,

    // Results only update when calc() runs (the "Plan My Trip" button), not on every
    // slider drag — dragging inputs live-recalculating on each pixel felt twitchy/noisy.
    calculated: false,
    result: {},

    onRouteChange() {
      // no-op; "Total trip distance" echo below uses the live distance getter directly
    },

    // Live echo of the currently-selected distance (input reflection, not a calculated result)
    get distance() {
      if (this.routeKey === 'custom') {
        let d = parseInt(this.customDistance) || 0;
        return Math.min(800, Math.max(50, d));
      }
      return this.routes[this.routeKey].km;
    },

    calc() {
      const distance = this.distance;
      const startLabel = this.routeKey === 'custom' ? 'Start' : this.routes[this.routeKey].from;
      const destLabel  = this.routeKey === 'custom' ? 'Destination' : this.routes[this.routeKey].to;

      // Usable range on the first leg (depart at startCharge, never drop below buffer)
      const usableFirst = Math.round(this.realRange * ((this.startCharge - this.minBuffer) / 100));
      // Usable range on subsequent legs (fast charge tapers, assume ~80%)
      const usableNext = Math.round(this.realRange * ((80 - this.minBuffer) / 100));

      let stops = 0;
      if (distance > usableFirst) {
        const remaining = distance - usableFirst;
        const per = Math.max(1, usableNext); // guard against div by zero
        stops = 1 + Math.floor(Math.max(0, remaining - 0.000001) / per);
      }

      const stopMarks = [];
      if (stops > 0) {
        let pos = usableFirst;
        for (let i = 0; i < stops; i++) {
          stopMarks.push(Math.min(distance, Math.round(pos)));
          pos += usableNext;
        }
      }

      this.result = {
        distance, startLabel, destLabel, usableFirst, usableNext, stops, stopMarks,
        startCharge: this.startCharge,
        chargeTime: stops * 30,
      };
      this.calculated = true;
    },
  }
}
</script>

<?= $this->endSection() ?>
