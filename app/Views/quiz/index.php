<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title>Find My Perfect EV — AI-Powered EV Finder | Charj.in</title>
<meta name="description" content="Answer 7 quick questions and get AI-powered personalised EV recommendations tailored to your budget, usage and city.">
<style>
[x-cloak]{display:none!important}
.qcard {
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  gap:0.5rem; padding:1.25rem 1rem; border-radius:1.25rem;
  background:rgba(255,255,255,0.07); border:1.5px solid rgba(255,255,255,0.12);
  color:white; font-weight:700; font-size:0.875rem; cursor:pointer;
  transition:all 0.18s; text-align:center; min-height:100px;
}
.qcard:hover { background:rgba(74,222,128,0.15); border-color:rgba(74,222,128,0.6); transform:translateY(-2px); }
.qcard.selected { background:rgba(74,222,128,0.2); border-color:#4ade80; box-shadow:0 0 0 3px rgba(74,222,128,0.2); }
.qcard-icon { font-size:1.75rem; line-height:1; }
.qcard-sub { font-size:0.7rem; color:rgba(255,255,255,0.45); font-weight:500; }
.qrow {
  display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:1rem;
  background:rgba(255,255,255,0.07); border:1.5px solid rgba(255,255,255,0.1);
  color:white; font-weight:600; font-size:0.875rem; cursor:pointer; width:100%;
  transition:all 0.18s; text-align:left;
}
.qrow:hover { background:rgba(74,222,128,0.12); border-color:rgba(74,222,128,0.5); transform:translateX(4px); }
.qrow.selected { background:rgba(74,222,128,0.18); border-color:#4ade80; }
.qrow-badge { min-width:3.5rem; text-align:center; padding:0.3rem 0.4rem; font-size:0.72rem; font-weight:800; color:#4ade80; background:rgba(74,222,128,0.12); border-radius:0.5rem; flex-shrink:0; }
.qrow-title { font-weight:700; font-size:0.875rem; color:white; display:block; }
.qrow-sub { font-size:0.7rem; color:rgba(255,255,255,0.4); display:block; margin-top:0.1rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="relative min-h-screen" x-data="evFinder()" x-init="init()">

  <!-- Background -->
  <div class="fixed inset-0 -z-10 transition-colors duration-700"
       :class="step===8 ? 'bg-slate-50' : 'bg-gradient-to-br from-slate-950 via-green-950 to-teal-900'"></div>
  <div class="fixed inset-0 -z-10 opacity-5 pointer-events-none" x-show="step!==8"
       style="background-image:radial-gradient(rgba(255,255,255,.4) 1px,transparent 1px);background-size:28px 28px"></div>

  <!-- Progress bar -->
  <div class="fixed top-0 inset-x-0 z-50 h-1 bg-white/10">
    <div class="h-full bg-green-400 transition-all duration-500 ease-out" :style="`width:${progress}%`"></div>
  </div>

  <div class="pt-12 pb-16 px-4">

    <!-- Question header (dark mode) -->
    <div class="max-w-xl mx-auto mb-8 flex items-center justify-between" x-show="step!==8">
      <a href="<?= base_url('/') ?>"
         class="flex items-center gap-1.5 text-white/60 hover:text-white text-sm font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
      </a>
      <div class="flex items-center gap-1.5">
        <template x-for="i in 7" :key="i">
          <div class="rounded-full transition-all duration-300"
               :class="i<step ? 'h-2 w-5 bg-green-400' : i===step ? 'h-2 w-8 bg-white' : 'h-2 w-2 bg-white/20'"></div>
        </template>
      </div>
      <span class="text-white/40 text-xs font-mono tabular-nums" x-text="`${step} / 7`"></span>
    </div>

    <div class="max-w-xl mx-auto">

      <!-- Q1: Usage -->
      <div x-show="step===1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 1 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">What's your main<br>use for the EV?</h2>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <button @click="sel('usage','commute')" :class="answers.usage==='commute'?'selected':''" class="qcard"><span class="qcard-icon">🏢</span><span>Daily Commute</span><span class="qcard-sub">Office / college</span></button>
          <button @click="sel('usage','family')" :class="answers.usage==='family'?'selected':''" class="qcard"><span class="qcard-icon">👨‍👩‍👧</span><span>Family Use</span><span class="qcard-sub">School runs, outings</span></button>
          <button @click="sel('usage','trips')" :class="answers.usage==='trips'?'selected':''" class="qcard"><span class="qcard-icon">🏔️</span><span>Weekend Trips</span><span class="qcard-sub">Long drives</span></button>
          <button @click="sel('usage','business')" :class="answers.usage==='business'?'selected':''" class="qcard"><span class="qcard-icon">📦</span><span>Business Use</span><span class="qcard-sub">Delivery / logistics</span></button>
        </div>
      </div>

      <!-- Q2: Distance -->
      <div x-show="step===2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 2 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">Daily travel<br>distance?</h2>
        </div>
        <div class="flex flex-col gap-3">
          <button @click="sel('distance','under30')" :class="answers.distance==='under30'?'selected':''" class="qrow"><span class="qrow-badge">&lt;30</span><div class="flex-1 text-left"><span class="qrow-title">Under 30 km</span><span class="qrow-sub">Short city hops</span></div></button>
          <button @click="sel('distance','30to60')" :class="answers.distance==='30to60'?'selected':''" class="qrow"><span class="qrow-badge">30–60</span><div class="flex-1 text-left"><span class="qrow-title">30 – 60 km</span><span class="qrow-sub">Medium commute</span></div></button>
          <button @click="sel('distance','60to100')" :class="answers.distance==='60to100'?'selected':''" class="qrow"><span class="qrow-badge">60–100</span><div class="flex-1 text-left"><span class="qrow-title">60 – 100 km</span><span class="qrow-sub">Long commute</span></div></button>
          <button @click="sel('distance','over100')" :class="answers.distance==='over100'?'selected':''" class="qrow"><span class="qrow-badge">100+</span><div class="flex-1 text-left"><span class="qrow-title">Over 100 km</span><span class="qrow-sub">High mileage use</span></div></button>
        </div>
        <button @click="step--" class="mt-4 text-white/40 hover:text-white/70 text-sm font-semibold transition-colors">← Back</button>
      </div>

      <!-- Q3: Charging -->
      <div x-show="step===3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 3 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">Where will you<br>charge it?</h2>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <button @click="sel('charging','home')" :class="answers.charging==='home'?'selected':''" class="qcard"><span class="qcard-icon">🏠</span><span>Home Charging</span><span class="qcard-sub">Private parking</span></button>
          <button @click="sel('charging','office')" :class="answers.charging==='office'?'selected':''" class="qcard"><span class="qcard-icon">🏢</span><span>Office / Society</span><span class="qcard-sub">Shared charger</span></button>
          <button @click="sel('charging','public')" :class="answers.charging==='public'?'selected':''" class="qcard"><span class="qcard-icon">⚡</span><span>Public Chargers</span><span class="qcard-sub">On the road</span></button>
          <button @click="sel('charging','mixed')" :class="answers.charging==='mixed'?'selected':''" class="qcard"><span class="qcard-icon">🔀</span><span>Mix of All</span><span class="qcard-sub">Flexible</span></button>
        </div>
        <button @click="step--" class="mt-4 text-white/40 hover:text-white/70 text-sm font-semibold transition-colors">← Back</button>
      </div>

      <!-- Q4: Budget -->
      <div x-show="step===4" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 4 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">What's your<br>budget?</h2>
        </div>
        <div class="flex flex-col gap-3">
          <button @click="sel('budget','under1L')" :class="answers.budget==='under1L'?'selected':''" class="qrow"><span class="qrow-badge">₹&lt;1L</span><div class="flex-1 text-left"><span class="qrow-title">Under ₹1 Lakh</span><span class="qrow-sub">Budget segment</span></div></button>
          <button @click="sel('budget','1to1.5L')" :class="answers.budget==='1to1.5L'?'selected':''" class="qrow"><span class="qrow-badge">₹1–1.5L</span><div class="flex-1 text-left"><span class="qrow-title">₹1 – 1.5 Lakh</span><span class="qrow-sub">Mid-range 2-wheelers</span></div></button>
          <button @click="sel('budget','1.5to3L')" :class="answers.budget==='1.5to3L'?'selected':''" class="qrow"><span class="qrow-badge">₹1.5–3L</span><div class="flex-1 text-left"><span class="qrow-title">₹1.5 – 3 Lakh</span><span class="qrow-sub">Premium 2W / budget 3W</span></div></button>
          <button @click="sel('budget','3to8L')" :class="answers.budget==='3to8L'?'selected':''" class="qrow"><span class="qrow-badge">₹3–8L</span><div class="flex-1 text-left"><span class="qrow-title">₹3 – 8 Lakh</span><span class="qrow-sub">Entry electric cars</span></div></button>
          <button @click="sel('budget','above8L')" :class="answers.budget==='above8L'?'selected':''" class="qrow"><span class="qrow-badge">₹8L+</span><div class="flex-1 text-left"><span class="qrow-title">Above ₹8 Lakh</span><span class="qrow-sub">Premium EVs & SUVs</span></div></button>
        </div>
        <button @click="step--" class="mt-4 text-white/40 hover:text-white/70 text-sm font-semibold transition-colors">← Back</button>
      </div>

      <!-- Q5: Vehicle Type -->
      <div x-show="step===5" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 5 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">Which type of<br>vehicle?</h2>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <button @click="sel('type','scooter')" :class="answers.type==='scooter'?'selected':''" class="qcard"><span class="qcard-icon">🛵</span><span>Electric Scooter</span><span class="qcard-sub">Best for city</span></button>
          <button @click="sel('type','motorcycle')" :class="answers.type==='motorcycle'?'selected':''" class="qcard"><span class="qcard-icon">🏍️</span><span>Electric Bike</span><span class="qcard-sub">Speed & range</span></button>
          <button @click="sel('type','hatchback')" :class="answers.type==='hatchback'?'selected':''" class="qcard"><span class="qcard-icon">🚗</span><span>Hatchback / Sedan</span><span class="qcard-sub">Family car</span></button>
          <button @click="sel('type','suv')" :class="answers.type==='suv'?'selected':''" class="qcard"><span class="qcard-icon">🚙</span><span>SUV / Crossover</span><span class="qcard-sub">Space & comfort</span></button>
        </div>
        <button @click="step--" class="mt-4 text-white/40 hover:text-white/70 text-sm font-semibold transition-colors">← Back</button>
      </div>

      <!-- Q6: Fast Charging -->
      <div x-show="step===6" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 6 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">Is fast charging<br>important to you?</h2>
          <p class="text-white/50 text-sm mt-2">Fast chargers can charge 0–80% in under 1 hour</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <button @click="sel('fastCharge','yes')" :class="answers.fastCharge==='yes'?'selected':''" class="qcard"><span class="qcard-icon">⚡</span><span>Yes, must have</span><span class="qcard-sub">I travel far often</span></button>
          <button @click="sel('fastCharge','nice')" :class="answers.fastCharge==='nice'?'selected':''" class="qcard"><span class="qcard-icon">✅</span><span>Nice to have</span><span class="qcard-sub">Occasional long trips</span></button>
          <button @click="sel('fastCharge','no')" :class="answers.fastCharge==='no'?'selected':''" class="qcard"><span class="qcard-icon">🏠</span><span>Not needed</span><span class="qcard-sub">Always charge at home</span></button>
          <button @click="sel('fastCharge','unsure')" :class="answers.fastCharge==='unsure'?'selected':''" class="qcard"><span class="qcard-icon">🤔</span><span>Not sure</span><span class="qcard-sub">Recommend for me</span></button>
        </div>
        <button @click="step--" class="mt-4 text-white/40 hover:text-white/70 text-sm font-semibold transition-colors">← Back</button>
      </div>

      <!-- Q7: State -->
      <div x-show="step===7" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
          <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-3">Question 7 of 7</p>
          <h2 class="text-3xl font-black text-white leading-tight">Which state<br>are you in?</h2>
          <p class="text-white/50 text-sm mt-2">For subsidy calculations & local pricing</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-80 overflow-y-auto scrollbar-hide">
          <?php
          $states = ['Andhra Pradesh'=>'andhra','Delhi'=>'delhi','Gujarat'=>'gujarat','Haryana'=>'haryana','Karnataka'=>'karnataka','Kerala'=>'kerala','Madhya Pradesh'=>'mp','Maharashtra'=>'maharashtra','Punjab'=>'punjab','Rajasthan'=>'rajasthan','Tamil Nadu'=>'tamil','Telangana'=>'telangana','Uttar Pradesh'=>'up','West Bengal'=>'wb','Other'=>'other'];
          foreach ($states as $label => $val): ?>
          <button @click="sel('state','<?= $val ?>')"
                  :class="answers.state==='<?= $val ?>'?'selected':''"
                  class="qrow !py-2.5 !px-3 text-xs">
            <span class="qrow-title text-xs"><?= $label ?></span>
          </button>
          <?php endforeach; ?>
        </div>
        <button @click="step--" class="mt-4 text-white/40 hover:text-white/70 text-sm font-semibold transition-colors">← Back</button>
      </div>

      <!-- STEP 8: AI Loading + Results -->
      <div x-show="step===8" x-cloak
           x-transition:enter="transition ease-out duration-500"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100">

        <!-- AI Loading state -->
        <div x-show="aiLoading" class="flex flex-col items-center justify-center py-24 text-center">
          <div class="relative mb-8">
            <div class="w-20 h-20 rounded-full bg-green-500/20 flex items-center justify-center">
              <div class="w-12 h-12 rounded-full border-4 border-green-400 border-t-transparent animate-spin"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
              <span class="text-2xl">🤖</span>
            </div>
          </div>
          <h3 class="text-2xl font-black text-white mb-2">Charj AI is thinking...</h3>
          <p class="text-white/50 text-sm max-w-xs">Analysing your requirements against every EV in our database to find your perfect match</p>
          <div class="mt-6 flex gap-2">
            <div class="w-2 h-2 rounded-full bg-green-400 animate-bounce" style="animation-delay:0s"></div>
            <div class="w-2 h-2 rounded-full bg-green-400 animate-bounce" style="animation-delay:.15s"></div>
            <div class="w-2 h-2 rounded-full bg-green-400 animate-bounce" style="animation-delay:.3s"></div>
          </div>
        </div>

        <!-- Results -->
        <div x-show="!aiLoading" class="max-w-2xl mx-auto">

          <!-- AI Summary banner -->
          <div x-show="result.summary" class="mb-6 rounded-2xl bg-gradient-to-r from-green-600 to-teal-600 p-5 text-white shadow-xl">
            <div class="flex items-start gap-3">
              <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 flex-shrink-0 text-xl">🤖</div>
              <div>
                <p class="text-xs font-bold text-green-100 uppercase tracking-widest mb-1">Charj AI Says</p>
                <p class="text-sm font-medium leading-relaxed" x-text="result.summary"></p>
              </div>
            </div>
          </div>

          <!-- Static fallback summary if no AI -->
          <div x-show="!result.summary" class="mb-6 rounded-2xl bg-gradient-to-r from-green-600 to-teal-600 p-5 text-white">
            <p class="text-xs font-bold text-green-100 uppercase tracking-widest mb-1">🎯 Your Matches</p>
            <p class="text-sm font-medium">Based on your answers, here are the best EVs for you in India right now.</p>
          </div>

          <!-- EV Cards -->
          <div class="space-y-3 mb-6">
            <template x-for="(ev, i) in result.evs" :key="i">
              <a :href="`<?= base_url('vehicles/') ?>` + ev.slug"
                 class="group flex gap-4 rounded-2xl bg-white p-4 sm:p-5 shadow-sm border border-slate-100 hover:border-green-300 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 relative overflow-hidden">
                <!-- Rank accent line -->
                <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl"
                     :class="i===0?'bg-gradient-to-b from-amber-400 to-amber-600':i===1?'bg-gradient-to-b from-slate-300 to-slate-500':'bg-gradient-to-b from-green-400 to-green-600'"></div>
                <!-- Rank -->
                <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center font-black text-sm"
                     :class="i===0?'bg-amber-50 text-amber-700 border-2 border-amber-200':i===1?'bg-slate-100 text-slate-600 border-2 border-slate-200':'bg-green-50 text-green-700 border-2 border-green-200'"
                     x-text="'#'+(i+1)"></div>
                <!-- Info -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2 flex-wrap">
                    <div>
                      <span class="font-black text-slate-900 text-base leading-tight" x-text="ev.name"></span>
                      <span class="block text-xs text-slate-400 font-semibold mt-0.5" x-text="ev.brand || ''"></span>
                    </div>
                    <span class="flex-shrink-0 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wide"
                          :class="i===0?'bg-amber-100 text-amber-700':i===1?'bg-slate-100 text-slate-600':'bg-green-100 text-green-700'"
                          x-text="ev.badge || (i===0?'Best Match':i===1?'Great Value':'Also Consider')"></span>
                  </div>
                  <!-- Why AI picked it -->
                  <p x-show="ev.why" class="text-xs text-slate-500 mt-2 leading-relaxed border-l-2 border-slate-100 pl-2" x-text="ev.why"></p>
                  <!-- Stats row -->
                  <div class="flex flex-wrap gap-2 mt-2.5">
                    <span x-show="ev.price" class="text-sm font-black text-slate-900" x-text="ev.price"></span>
                    <span class="text-slate-200">|</span>
                    <span x-show="ev.range" class="text-xs font-semibold text-green-700 bg-green-50 border border-green-100 px-2.5 py-0.5 rounded-full" x-text="'Range: ' + ev.range"></span>
                    <span x-show="ev.rating" class="text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-100 px-2.5 py-0.5 rounded-full" x-text="'⭐ ' + ev.rating + '/10'"></span>
                  </div>
                  <!-- Match score -->
                  <div x-show="ev.match_score" class="mt-2.5 flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                      <div class="h-full rounded-full transition-all duration-700"
                           :class="i===0?'bg-amber-500':i===1?'bg-slate-400':'bg-green-500'"
                           :style="`width:${ev.match_score}%`"></div>
                    </div>
                    <span class="text-xs font-black flex-shrink-0"
                          :class="i===0?'text-amber-600':i===1?'text-slate-500':'text-green-600'"
                          x-text="ev.match_score + '% match'"></span>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-200 group-hover:text-green-500 flex-shrink-0 transition-colors self-center mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
              </a>
            </template>
          </div>

          <!-- On-road price calculator -->
          <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-5 mb-4">
            <h3 class="font-black text-slate-900 mb-1">🧮 On-Road Price Calculator</h3>
            <p class="text-xs text-slate-500 mb-4">Calculate full on-road price with AI breakdown for any EV</p>
            <div class="flex gap-2">
              <select x-model="calcSlug" class="flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400 bg-white">
                <option value="">Select an EV...</option>
                <?php
                $db = \Config\Database::connect();
                $allVehicles = $db->query("SELECT v.slug, v.name, b.name as brand FROM vehicles v LEFT JOIN brands b ON b.id=v.brand_id WHERE v.status='published' ORDER BY b.name, v.name")->getResultArray();
                foreach ($allVehicles as $av): ?>
                <option value="<?= esc($av['slug']) ?>"><?= esc($av['brand'].' '.$av['name']) ?></option>
                <?php endforeach; ?>
              </select>
              <input x-model="calcCity" type="text" placeholder="City" class="w-28 rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
              <button @click="calculateCost()" :disabled="!calcSlug || calcLoading"
                      class="flex-shrink-0 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-colors">
                <span x-show="!calcLoading">Calculate</span>
                <span x-show="calcLoading" x-cloak>...</span>
              </button>
            </div>
            <!-- Result -->
            <div x-show="calcResult" x-cloak class="mt-4 space-y-3">
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <div class="rounded-xl bg-green-50 p-3 text-center">
                  <div class="text-lg font-black text-green-700" x-text="rs(calcResult.on_road||0)"></div>
                  <div class="text-[10px] text-green-600 font-semibold uppercase tracking-wide">On-Road</div>
                </div>
                <div class="rounded-xl bg-slate-50 p-3 text-center">
                  <div class="text-lg font-black text-slate-700" x-text="rs((calcResult.emi||{}).monthly||0)"></div>
                  <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wide">EMI/Month</div>
                </div>
                <div class="rounded-xl bg-blue-50 p-3 text-center">
                  <div class="text-lg font-black text-blue-700" x-text="rs((calcResult.emi||{}).down_payment||0)"></div>
                  <div class="text-[10px] text-blue-600 font-semibold uppercase tracking-wide">Down Payment</div>
                </div>
                <div class="rounded-xl bg-amber-50 p-3 text-center">
                  <div class="text-lg font-black text-amber-700" x-text="rs((calcResult.savings||{}).per_year||0)"></div>
                  <div class="text-[10px] text-amber-600 font-semibold uppercase tracking-wide">Save/Year</div>
                </div>
              </div>
              <!-- AI insight -->
              <div x-show="calcResult.ai_insight" class="rounded-xl bg-gradient-to-r from-green-50 to-teal-50 border border-green-100 p-4">
                <p class="text-xs font-bold text-green-700 mb-1">🤖 AI Insight</p>
                <p class="text-sm text-slate-700 leading-relaxed" x-text="calcResult.ai_insight"></p>
              </div>
              <div x-show="calcResult.tip" class="rounded-xl bg-amber-50 border border-amber-100 p-3">
                <p class="text-xs font-bold text-amber-700 mb-0.5">💡 Money Tip</p>
                <p class="text-xs text-amber-800" x-text="calcResult.tip"></p>
              </div>
            </div>
          </div>

          <!-- Action buttons -->
          <div class="grid grid-cols-2 gap-3 mb-4">
            <a href="<?= base_url('vehicles') ?>"
               class="flex items-center justify-center gap-2 rounded-2xl font-bold py-3.5 text-sm transition-all hover:-translate-y-0.5 text-white"
               style="background:linear-gradient(135deg,#16a34a,#15803d)">
              🚗 Browse All EVs
            </a>
            <a href="<?= base_url('compare') ?>"
               class="flex items-center justify-center gap-2 rounded-2xl bg-slate-900 text-white font-bold py-3.5 text-sm hover:bg-slate-800 transition-all hover:-translate-y-0.5">
              ⚖️ Compare EVs
            </a>
          </div>
          <div class="grid grid-cols-2 gap-3 mb-6">
            <a href="<?= base_url('charging-stations') ?>"
               class="flex items-center justify-center gap-2 rounded-2xl bg-blue-600 text-white font-bold py-3 text-sm hover:bg-blue-700 transition-all">
              ⚡ Find Chargers
            </a>
            <a href="<?= base_url('ev-glossary') ?>"
               class="flex items-center justify-center gap-2 rounded-2xl bg-purple-600 text-white font-bold py-3 text-sm hover:bg-purple-700 transition-all">
              📖 EV Glossary
            </a>
          </div>

          <!-- Restart -->
          <div class="text-center">
            <button @click="restart()"
                    class="inline-flex items-center gap-2 text-slate-500 hover:text-green-700 text-sm font-bold transition-colors border border-slate-200 hover:border-green-400 rounded-full px-5 py-2.5">
              ↩ Retake Quiz
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function evFinder() {
  return {
    step: 1,
    answers: {},
    result: { evs: [], summary: '' },
    aiLoading: false,
    calcSlug: '',
    calcCity: 'Delhi',
    calcLoading: false,
    calcResult: null,
    name: '', phone: '', submitted: false,

    init() {},

    get progress() { return Math.min((this.step - 1) / 7 * 100, 100); },

    sel(q, val) {
      this.answers[q] = val;
      if (this.step < 7) {
        this.step++;
      } else {
        this.getAiRecommendations();
      }
    },

    async getAiRecommendations() {
      this.step = 8;
      this.aiLoading = true;
      this.result = { evs: [], summary: '' };

      try {
        const resp = await fetch('<?= site_url('ai/ev-recommend') ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ answers: this.answers })
        });
        const json = await resp.json();

        if (json.success && json.data) {
          this.result.evs = json.data.recommendations || [];
          this.result.summary = json.data.summary || '';
        } else {
          this.fallbackRecommend();
        }
      } catch (e) {
        this.fallbackRecommend();
      } finally {
        this.aiLoading = false;
        // Save quiz answers (non-blocking)
        fetch('<?= site_url('ev-finder/save') ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ answers: this.answers, result: this.result.evs.map(e => e.name) })
        }).catch(() => {});
      }
    },

    fallbackRecommend() {
      const a = this.answers;
      const is4W = ['hatchback','suv'].includes(a.type);
      const budgetVal = {under1L:100000,'1to1.5L':150000,'1.5to3L':300000,'3to8L':800000,above8L:2000000}[a.budget] || 150000;
      let key = !is4W ? (budgetVal <= 100000 ? '2W-budget' : '2W-mid') : (budgetVal <= 300000 ? '4W-budget' : budgetVal <= 800000 ? '4W-mid' : '4W-premium');
      const recs = {
        '2W-budget': [{name:'Ola S1 Air',brand:'Ola Electric',price:'₹84,999',range:'101km',rating:'7.8',slug:'ola-s1-air',badge:'Best Value',match_score:92,why:'Perfect budget scooter for daily city commute under ₹1L.'},
                     {name:'TVS iQube S',brand:'TVS Motor',price:'₹98,777',range:'100km',rating:'8.5',slug:'tvs-iqube-s',badge:'Top Rated',match_score:87,why:'TVS reliability with great build quality and service network.'}],
        '2W-mid':    [{name:'Ather 450X',brand:'Ather Energy',price:'₹1,49,900',range:'146km',rating:'8.8',slug:'ather-450x',badge:'Best Match',match_score:95,why:'Best-in-class scooter with fast charging, great range and premium build.'},
                     {name:'Ola S1 Pro',brand:'Ola Electric',price:'₹1,39,999',range:'195km',rating:'8.2',slug:'ola-s1-pro',badge:'Best Range',match_score:88,why:'Highest range in segment, great for longer daily commutes.'}],
        '4W-budget': [{name:'Tata Tiago EV',brand:'Tata Motors',price:'₹8.49L',range:'315km',rating:'8.4',slug:'tata-tiago-ev',badge:'Best Value',match_score:93,why:'Most affordable 4-wheeler EV in India with solid range and Tata reliability.'},
                     {name:'MG Comet EV',brand:'MG Motor',price:'₹7.98L',range:'230km',rating:'7.9',slug:'mg-comet-ev',badge:'City EV',match_score:85,why:'Ultra-compact city EV, ideal for tight urban parking and short commutes.'}],
        '4W-mid':    [{name:'Tata Nexon EV',brand:'Tata Motors',price:'₹14.74L',range:'465km',rating:'9.0',slug:'tata-nexon-ev',badge:'Best Match',match_score:96,why:'India\'s best-selling EV SUV with top safety ratings and 465km real range.'},
                     {name:'Tata Punch EV',brand:'Tata Motors',price:'₹10.99L',range:'421km',rating:'8.7',slug:'tata-punch-ev',badge:'Great Value',match_score:89,why:'Compact SUV form with premium range, great for families upgrading to EVs.'}],
        '4W-premium':[{name:'MG ZS EV',brand:'MG Motor',price:'₹18.98L',range:'461km',rating:'8.7',slug:'mg-zs-ev',badge:'Premium Pick',match_score:91,why:'Premium SUV with 461km range, ADAS features and fast 76kW DC charging.'},
                     {name:'Tata Nexon EV',brand:'Tata Motors',price:'₹14.74L',range:'465km',rating:'9.0',slug:'tata-nexon-ev',badge:'Top Rated',match_score:88,why:'India\'s highest-rated EV with proven reliability and best resale value.'}],
      };
      this.result.evs = recs[key] || recs['2W-mid'];
      this.result.summary = 'Based on your preferences, we found the best EVs in India that match your budget, range needs and usage pattern.';
    },

    async calculateCost() {
      if (!this.calcSlug) return;
      this.calcLoading = true;
      this.calcResult = null;
      try {
        const resp = await fetch('<?= site_url('ai/calculate') ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ slug: this.calcSlug, city: this.calcCity || 'Delhi', daily_km: 40, years: 3 })
        });
        const json = await resp.json();
        if (json.success) this.calcResult = json.data;
      } catch (e) {}
      this.calcLoading = false;
    },

    restart() {
      this.step = 1; this.answers = {};
      this.result = { evs: [], summary: '' };
      this.calcResult = null; this.aiLoading = false;
    },

    // Format a number as Indian rupees using Unicode ₹ (U+20B9) to avoid HTML entity issues
    rs(n) {
      const sym = '₹';
      const num = Number(n) || 0;
      if (num >= 10000000) return sym + (num / 10000000).toFixed(1) + ' Cr';
      if (num >= 100000)   return sym + (num / 100000).toFixed(2) + ' L';
      return sym + num.toLocaleString('en-IN');
    }
  }
}
</script>

<?= $this->endSection() ?>

