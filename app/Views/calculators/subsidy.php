<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<style>
[x-cloak]{display:none!important}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
@keyframes pulseGreen{0%,100%{box-shadow:0 0 0 0 rgba(0,230,118,.4)}50%{box-shadow:0 0 0 8px rgba(0,230,118,0)}}
.anim-fade-up{animation:fadeUp .4s cubic-bezier(.22,1,.36,1) both}
.delay-1{animation-delay:.06s}.delay-2{animation-delay:.12s}.delay-3{animation-delay:.2s}
.step-active-pulse{animation:pulseGreen 2s ease-in-out infinite}
.state-card{transition:transform .15s ease,box-shadow .15s ease,border-color .15s ease}
.state-card:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(0,230,118,.12)}
.state-card.selected{transform:translateY(-2px);box-shadow:0 0 0 2px #00E676,0 6px 18px rgba(0,230,118,.18)}
.vtype-card{transition:transform .15s ease,box-shadow .15s ease}
.vtype-card:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,230,118,.12)}
.vtype-card.selected{transform:translateY(-2px);box-shadow:0 0 0 2px #00E676,0 8px 24px rgba(0,230,118,.18)}
.result-row{transition:background .15s}
.result-row:hover{background:rgba(0,230,118,.03)}
input[type=range]{-webkit-appearance:none;appearance:none;height:6px;border-radius:9999px;background:linear-gradient(to right,#00E676 var(--pct,0%),#e2e8f0 var(--pct,0%));outline:none;cursor:pointer}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:18px;height:18px;border-radius:50%;background:#fff;border:2px solid #00E676;box-shadow:0 2px 6px rgba(0,230,118,.3);cursor:pointer;transition:transform .15s}
input[type=range]::-webkit-slider-thumb:hover{transform:scale(1.2)}
</style>

<div x-data="subsidyCalc()" x-init="initRange()" class="pb-16" style="background:linear-gradient(180deg,#f0fdf4 0%,#f8fafc 280px)">

  <!-- ── HERO ── -->
  <div class="hero-sm relative overflow-hidden pt-28 pb-10 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.3) 1px,transparent 1px);background-size:24px 24px"></div>
    <div class="absolute top-0 right-0 w-96 h-72 rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(0,168,150,.1),transparent 65%);transform:translate(30%,-30%)"></div>
    <div class="absolute bottom-0 left-0 w-64 h-48 rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(0,168,150,.06),transparent 70%);transform:translate(-20%,20%)"></div>

    <div class="relative max-w-6xl mx-auto">
      <!-- Badge + Heading -->
      <div class="max-w-2xl">
        <div class="anim-fade-up inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-widest mb-4" style="background:rgba(0,168,150,.1);color:#00A896;border:1.5px solid rgba(0,168,150,.2)">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
          Updated for 2024–25 Schemes
        </div>
        <h1 class="anim-fade-up delay-1 text-3xl sm:text-4xl lg:text-5xl font-black leading-tight mb-3" style="color:#0F172A">Find Your EV Subsidy</h1>
        <p class="anim-fade-up delay-2 text-sm sm:text-base" style="color:#475569">Most buyers miss <strong style="color:#0F172A">₹15,000–₹1,50,000</strong> in government benefits. Check yours in 2 minutes — free.</p>
      </div>
      <!-- Stats row — always horizontal, flush left -->
      <div class="anim-fade-up delay-3 mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-0 sm:divide-x sm:divide-teal-200 max-w-xl">
        <?php foreach([['₹1.5L','Max state subsidy'],['100%','Road tax exempt'],['₹46,800','80EEB benefit'],['15+','Active states']] as $s): ?>
        <div class="sm:px-5 first:pl-0">
          <div class="text-xl font-black" style="color:#0F172A"><?= $s[0] ?></div>
          <div class="text-xs mt-0.5" style="color:#64748B"><?= $s[1] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ── STEP PROGRESS BAR ── -->
  <div class="sticky z-40 shadow-sm" style="top:64px;background:rgba(255,255,255,.97);backdrop-filter:blur(12px);border-bottom:1px solid rgba(0,230,118,.1)">
    <div class="max-w-6xl mx-auto px-4 py-2.5">
      <div class="flex items-center gap-2">
        <template x-for="(label, i) in ['Select State','Vehicle Type','Vehicle Price','Your Results']" :key="i">
          <div class="flex items-center gap-2 flex-1">
            <div class="flex items-center gap-1.5 min-w-0">
              <div :class="{'text-white step-active-pulse':step===i+1,'text-white':step>i+1,'bg-slate-200 text-slate-400':step<i+1}"
                :style="step>i+1?'background:#00C060':step===i+1?'background:#00A896;border:2px solid #00E676':''"
                class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0 transition-all duration-300">
                <template x-if="step>i+1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></template>
                <template x-if="step<=i+1"><span x-text="i+1"></span></template>
              </div>
              <span :class="step===i+1?'font-bold':'text-slate-400'" :style="step===i+1?'color:#022C22':''" class="text-xs hidden sm:block transition-colors" x-text="label"></span>
            </div>
            <template x-if="i<3">
              <div class="h-0.5 flex-1 rounded-full overflow-hidden bg-slate-200">
                <div :class="step>i+1?'w-full':'w-0'" class="h-full transition-all duration-500 rounded-full" style="background:linear-gradient(90deg,#00E676,#00C060)"></div>
              </div>
            </template>
          </div>
        </template>
      </div>
    </div>
  </div>

  <div class="max-w-6xl mx-auto px-4 py-5 sm:py-6">

    <!-- ══ STEP 1: State ══ -->
    <div x-show="step===1" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="mb-4 anim-fade-up">
        <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest px-2.5 py-1 rounded-full mb-2" style="background:rgba(0,230,118,.1);color:#00963C;border:1px solid rgba(0,230,118,.2)">Step 1 of 4</div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900">Which state are you buying your EV in?</h2>
      </div>

      <!-- Desktop: grid left + detail right -->
      <div class="lg:grid lg:grid-cols-3 lg:gap-6 lg:items-start">
        <!-- State grid (takes 2/3 on desktop) -->
        <div class="lg:col-span-2">
          <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-3">
            <template x-for="s in states" :key="s.id">
              <button @click="selectState(s)"
                :class="selectedState&&selectedState.id===s.id?'selected':''"
                class="state-card border-2 rounded-xl p-3 text-center cursor-pointer relative bg-white"
                :style="selectedState&&selectedState.id===s.id?'border-color:#00E676;background:rgba(0,230,118,.04)':'border-color:#e2e8f0'">
                <div class="text-2xl mb-1" x-text="s.flag"></div>
                <div class="font-bold text-slate-800 text-xs leading-tight" x-text="s.name"></div>
                <div class="mt-1 text-[10px] font-black" :style="s.active?'color:#00963C':'color:#94a3b8'" x-text="s.active?fmt(Math.max(s.subsidy2w,s.subsidy4w)):'—'"></div>
                <div x-show="selectedState&&selectedState.id===s.id" class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center" style="background:#00E676">
                  <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
              </button>
            </template>
          </div>
          <!-- Next button -->
          <div class="hidden lg:block mt-4">
            <button @click="step=2" :disabled="!selectedState"
              :style="selectedState?'background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 18px rgba(0,230,118,.3)':''"
              :class="selectedState?'hover:shadow-lg':'bg-slate-200 text-slate-400 cursor-not-allowed'"
              class="px-8 py-3 rounded-xl font-black text-sm transition-all transform hover:scale-[1.02]">
              Next: Select Vehicle Type →
            </button>
          </div>
        </div>

        <!-- State detail panel (right col on desktop, below on mobile) -->
        <div class="lg:col-span-1 mt-3 lg:mt-0">
          <!-- Placeholder when none selected -->
          <div x-show="!selectedState" class="rounded-2xl p-5 text-center" style="background:rgba(0,230,118,.04);border:2px dashed rgba(0,230,118,.2)">
            <div class="text-3xl mb-2">🗺️</div>
            <div class="text-sm font-bold text-slate-600">Select a state</div>
            <div class="text-xs text-slate-400 mt-1">to see subsidy details</div>
          </div>
          <!-- Detail when selected -->
          <div x-show="selectedState" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
               class="rounded-2xl p-4 relative overflow-hidden" style="background:linear-gradient(135deg,#00A896,#007A6E);border:1px solid rgba(0,230,118,.2);box-shadow:0 6px 24px rgba(0,230,118,.1)">
            <div class="absolute top-0 right-0 w-32 h-32 rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(0,230,118,.1),transparent 70%);transform:translate(20%,-20%)"></div>
            <div class="relative">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <div class="text-green-300/70 text-[10px] font-bold uppercase tracking-widest mb-0.5">Selected State</div>
                  <h3 class="text-base font-black text-white leading-tight" x-text="selectedState?.name"></h3>
                  <p class="text-slate-400 text-[10px] mt-0.5" x-text="selectedState?.scheme"></p>
                </div>
                <span :style="selectedState?.active?'background:rgba(0,230,118,.15);color:#69FF97;border:1px solid rgba(0,230,118,.3)':'background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3)'"
                  class="rounded-full px-2 py-0.5 text-[10px] font-black flex-shrink-0 ml-2" x-text="selectedState?.active?'ACTIVE':'ENDED'"></span>
              </div>
              <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="rounded-xl p-2 text-center" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.08)">
                  <div class="text-[10px] text-slate-400 mb-0.5">2-Wheeler</div>
                  <div class="text-sm font-black" style="color:#00E676" x-text="fmt(selectedState?.subsidy2w??0)"></div>
                </div>
                <div class="rounded-xl p-2 text-center" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.08)">
                  <div class="text-[10px] text-slate-400 mb-0.5">3-Wheeler</div>
                  <div class="text-sm font-black" style="color:#00E676" x-text="fmt(selectedState?.subsidy3w??0)"></div>
                </div>
                <div class="rounded-xl p-2 text-center" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.08)">
                  <div class="text-[10px] text-slate-400 mb-0.5">4-Wheeler</div>
                  <div class="text-sm font-black" style="color:#00E676" x-text="fmt(selectedState?.subsidy4w??0)"></div>
                </div>
                <div class="rounded-xl p-2 text-center" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.08)">
                  <div class="text-[10px] text-slate-400 mb-0.5">Road Tax</div>
                  <div class="text-sm font-black" style="color:#00E676">100% Off</div>
                </div>
              </div>
              <p class="text-[10px] text-slate-500">Verify eligibility with your dealer before purchase.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile next button -->
      <div class="mt-4 text-center lg:hidden">
        <button @click="step=2" :disabled="!selectedState"
          :style="selectedState?'background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 18px rgba(0,230,118,.3)':''"
          :class="selectedState?'':'bg-slate-200 text-slate-400 cursor-not-allowed'"
          class="px-8 py-3 rounded-xl font-black text-sm transition-all transform hover:scale-[1.02]">
          Next: Select Vehicle Type →
        </button>
      </div>
    </div>

    <!-- ══ STEP 2: Vehicle Type ══ -->
    <div x-show="step===2" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="mb-4">
        <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest px-2.5 py-1 rounded-full mb-2" style="background:rgba(0,230,118,.1);color:#00963C;border:1px solid rgba(0,230,118,.2)">Step 2 of 4</div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900">What type of EV are you buying?</h2>
      </div>

      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
        <template x-for="vt in vehicleTypes" :key="vt.id">
          <button @click="vehicleType=vt.id"
            :class="vehicleType===vt.id?'selected':''"
            class="vtype-card border-2 rounded-2xl p-4 text-center cursor-pointer bg-white"
            :style="vehicleType===vt.id?'border-color:#00E676;background:rgba(0,230,118,.04)':'border-color:#e2e8f0'">
            <div class="text-3xl sm:text-4xl mb-2 transition-transform duration-200" :class="vehicleType===vt.id?'scale-110':''" x-text="vt.icon"></div>
            <div class="font-black text-slate-800 text-sm" x-text="vt.label"></div>
            <div class="text-xs text-slate-400 mt-0.5" x-text="vt.sub"></div>
            <div class="mt-3 pt-2.5 border-t border-slate-100">
              <div class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">FAME II</div>
              <div class="font-black text-sm" style="color:#00963C" x-text="fmt(vt.fameSubsidy)"></div>
            </div>
          </button>
        </template>
      </div>

      <div class="flex gap-3 justify-center">
        <button @click="step=1" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-600 font-bold text-sm hover:border-slate-300 hover:bg-slate-50 transition-all">← Back</button>
        <button @click="step=3" :disabled="!vehicleType"
          :style="vehicleType?'background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 14px rgba(0,230,118,.3)':''"
          :class="vehicleType?'':'bg-slate-200 text-slate-400 cursor-not-allowed'"
          class="px-8 py-2.5 rounded-xl font-black text-sm transition-all transform hover:scale-[1.02]">
          Next: Vehicle Price →
        </button>
      </div>
    </div>

    <!-- ══ STEP 3: Vehicle Price ══ -->
    <div x-show="step===3" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="mb-4">
        <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest px-2.5 py-1 rounded-full mb-2" style="background:rgba(0,230,118,.1);color:#00963C;border:1px solid rgba(0,230,118,.2)">Step 3 of 4</div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900">What is the ex-showroom price?</h2>
      </div>

      <!-- Desktop: 2 columns -->
      <div class="lg:grid lg:grid-cols-2 lg:gap-5 mb-5">
        <!-- Slider card -->
        <div class="bg-white rounded-2xl p-5 mb-3 lg:mb-0" style="box-shadow:0 3px 16px rgba(0,0,0,.06);border:1.5px solid rgba(0,230,118,.1)">
          <div class="text-center mb-5">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Ex-Showroom Price</div>
            <div class="text-4xl sm:text-5xl font-black" style="color:#022C22" x-text="fmt(vehiclePrice)"></div>
          </div>
          <label class="block">
            <input type="range" x-model="vehiclePrice" @input="updateRange($el)"
              :min="vehicleType==='2w'?50000:vehicleType==='3w'?100000:500000"
              :max="vehicleType==='2w'?300000:vehicleType==='3w'?600000:5000000"
              :step="vehicleType==='4w'||vehicleType==='commercial'?25000:5000"
              class="w-full" x-ref="rangeSlider">
            <div class="flex justify-between text-xs text-slate-400 mt-1.5">
              <span x-text="vehicleType==='2w'?'₹50K':vehicleType==='3w'?'₹1L':'₹5L'"></span>
              <span x-text="vehicleType==='2w'?'₹3L':vehicleType==='3w'?'₹6L':'₹50L'"></span>
            </div>
          </label>
          <div class="mt-4">
            <div class="text-xs text-slate-400 text-center mb-2 font-semibold">Popular price points</div>
            <div class="flex flex-wrap gap-1.5 justify-center">
              <template x-if="vehicleType==='2w'">
                <template x-for="p in [75000,100000,130000,160000,200000]" :key="p">
                  <button @click="vehiclePrice=p;updateRange($refs.rangeSlider)"
                    :style="vehiclePrice==p?'background:#00A896;color:#fff;border-color:#00A896':''"
                    :class="vehiclePrice==p?'':'bg-slate-50 text-slate-600 border-slate-200 hover:border-green-300'"
                    class="border rounded-lg px-2.5 py-1 text-xs font-bold transition-all" x-text="fmt(p)"></button>
                </template>
              </template>
              <template x-if="vehicleType==='4w'">
                <template x-for="p in [800000,1200000,1500000,2000000,3000000]" :key="p">
                  <button @click="vehiclePrice=p;updateRange($refs.rangeSlider)"
                    :style="vehiclePrice==p?'background:#00A896;color:#fff;border-color:#00A896':''"
                    :class="vehiclePrice==p?'':'bg-slate-50 text-slate-600 border-slate-200 hover:border-green-300'"
                    class="border rounded-lg px-2.5 py-1 text-xs font-bold transition-all" x-text="fmt(p)"></button>
                </template>
              </template>
              <template x-if="vehicleType==='3w'">
                <template x-for="p in [150000,200000,300000,450000]" :key="p">
                  <button @click="vehiclePrice=p;updateRange($refs.rangeSlider)"
                    :style="vehiclePrice==p?'background:#00A896;color:#fff;border-color:#00A896':''"
                    :class="vehiclePrice==p?'':'bg-slate-50 text-slate-600 border-slate-200 hover:border-green-300'"
                    class="border rounded-lg px-2.5 py-1 text-xs font-bold transition-all" x-text="fmt(p)"></button>
                </template>
              </template>
            </div>
          </div>
        </div>

        <!-- Preview stats card -->
        <div class="rounded-2xl p-5 flex flex-col justify-center" style="background:linear-gradient(135deg,#00A896,#007A6E);border:1px solid rgba(0,230,118,.18);box-shadow:0 3px 16px rgba(0,0,0,.15)">
          <div class="text-green-300/70 text-xs font-bold uppercase tracking-widest mb-3">Estimated Benefits</div>
          <div class="space-y-3">
            <div class="flex justify-between items-center py-2.5 px-3 rounded-xl" style="background:rgba(255,255,255,.07)">
              <span class="text-slate-300 text-sm">FAME II Central</span>
              <span class="font-black text-sm" style="color:#00E676" x-text="fmt(vehicleTypes.find(v=>v.id===vehicleType)?.fameSubsidy??0)"></span>
            </div>
            <div class="flex justify-between items-center py-2.5 px-3 rounded-xl" style="background:rgba(255,255,255,.07)">
              <span class="text-slate-300 text-sm">State Subsidy</span>
              <span class="font-black text-sm" :style="selectedState?.active?'color:#00E676':'color:#94a3b8'" x-text="selectedState?.active?fmt(vehicleType==='2w'?selectedState.subsidy2w:vehicleType==='3w'?selectedState.subsidy3w:selectedState.subsidy4w):'—'"></span>
            </div>
            <div class="flex justify-between items-center py-2.5 px-3 rounded-xl" style="background:rgba(255,255,255,.07)">
              <span class="text-slate-300 text-sm">Road Tax (~<span x-text="vehicleType==='4w'?'10':vehicleType==='3w'?'5':'4'"></span>%)</span>
              <span class="font-black text-sm" style="color:#00E676" x-text="fmt(Math.round(vehiclePrice*(vehicleType==='4w'?.10:vehicleType==='3w'?.05:.04)))"></span>
            </div>
            <div class="flex justify-between items-center py-2.5 px-3 rounded-xl" style="background:rgba(255,255,255,.07)">
              <span class="text-slate-300 text-sm">80EEB Tax Benefit</span>
              <span class="font-black text-sm" style="color:#00E676">₹46,800</span>
            </div>
          </div>
          <div class="mt-3 pt-3 border-t border-white/10 flex justify-between items-center">
            <span class="text-green-300 text-xs font-bold">Est. total benefit</span>
            <span class="text-xl font-black text-white" x-text="fmt((vehicleTypes.find(v=>v.id===vehicleType)?.fameSubsidy??0)+(selectedState?.active?(vehicleType==='2w'?selectedState.subsidy2w:vehicleType==='3w'?selectedState.subsidy3w:selectedState.subsidy4w):0)+Math.round(vehiclePrice*(vehicleType==='4w'?.10:vehicleType==='3w'?.05:.04))+46800)"></span>
          </div>
        </div>
      </div>

      <div class="flex gap-3 justify-center">
        <button @click="step=2" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-600 font-bold text-sm hover:border-slate-300 hover:bg-slate-50 transition-all">← Back</button>
        <button @click="step=4;calcResults()"
          style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 14px rgba(0,230,118,.3)"
          class="px-8 py-2.5 rounded-xl font-black text-sm transition-all transform hover:scale-[1.02]">
          Calculate My Subsidy →
        </button>
      </div>
    </div>

    <!-- ══ STEP 4: Results ══ -->
    <div x-show="step===4" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">

      <!-- Result banner -->
      <div class="rounded-2xl p-5 sm:p-6 mb-5 relative overflow-hidden" style="background:linear-gradient(135deg,#00A896 0%,#007A6E 100%);box-shadow:0 8px 32px rgba(0,230,118,.15);border:1px solid rgba(0,230,118,.2)">
        <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.05) 1px,transparent 1px);background-size:20px 20px"></div>
        <div class="absolute top-0 right-0 w-48 h-48 pointer-events-none" style="background:radial-gradient(circle,rgba(0,230,118,.12),transparent 70%);transform:translate(20%,-20%)"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
          <div class="flex-1">
            <div class="text-xs font-bold uppercase tracking-widest text-green-300/70 mb-1">🎉 Total Government Benefit</div>
            <div class="text-4xl sm:text-5xl font-black text-white" x-text="fmt(results.totalBenefit)" style="text-shadow:0 0 30px rgba(0,230,118,.25)"></div>
            <div class="text-slate-300 text-sm mt-1">
              <span class="font-bold text-white" x-text="fmt(vehiclePrice)"></span> EV effectively costs
              <span class="font-black" style="color:#00E676" x-text="fmt(Math.max(0,vehiclePrice-results.totalBenefit))"></span> after benefits
            </div>
          </div>
          <div class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold text-green-200 self-start sm:self-center" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12)">
            <svg class="w-3.5 h-3.5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span x-text="selectedState?.name+' + Central'"></span>
          </div>
        </div>
      </div>

      <!-- Desktop: 2 columns — breakdown left, lead form right -->
      <div class="lg:grid lg:grid-cols-3 lg:gap-5 mb-5">

        <!-- Breakdown (2/3) -->
        <div class="lg:col-span-2 bg-white rounded-2xl overflow-hidden mb-4 lg:mb-0" style="box-shadow:0 3px 16px rgba(0,0,0,.05);border:1.5px solid rgba(0,230,118,.1)">
          <div class="px-5 py-3.5" style="background:linear-gradient(135deg,#f0fdf4,#fff);border-bottom:1px solid rgba(0,230,118,.08)">
            <h3 class="text-base font-black text-slate-900">Subsidy Breakdown</h3>
          </div>
          <div class="divide-y divide-slate-50">
            <!-- FAME II -->
            <div class="result-row px-4 py-3 flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-50">
                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-slate-800 text-sm">FAME II — Central Government</div>
                <div class="h-1 rounded-full bg-slate-100 mt-1.5 overflow-hidden"><div class="h-full rounded-full bg-blue-400" style="width:30%"></div></div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-base font-black" style="color:#00963C" x-text="fmt(results.fameSubsidy)"></div>
                <div class="text-[10px] text-slate-400">direct benefit</div>
              </div>
            </div>
            <!-- State Subsidy -->
            <div class="result-row px-4 py-3 flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-purple-50">
                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-slate-800 text-sm" x-text="selectedState?.name+' State Subsidy'"></div>
                <div class="h-1 rounded-full bg-slate-100 mt-1.5 overflow-hidden"><div class="h-full rounded-full bg-purple-400" :style="'width:'+(results.stateSubsidy>0?Math.min(100,Math.round(results.stateSubsidy/results.totalBenefit*100)):0)+'%'"></div></div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-base font-black" :style="results.stateSubsidy>0?'color:#00963C':'color:#94a3b8'" x-text="results.stateSubsidy>0?fmt(results.stateSubsidy):'—'"></div>
                <div class="text-[10px] mt-0.5" :style="selectedState?.active?'color:#16a34a':'color:#ef4444'" x-text="selectedState?.active?'active scheme':'no scheme'"></div>
              </div>
            </div>
            <!-- Road Tax -->
            <div class="result-row px-4 py-3 flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-orange-50">
                <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-slate-800 text-sm">Road Tax Exemption (100%)</div>
                <div class="h-1 rounded-full bg-slate-100 mt-1.5 overflow-hidden"><div class="h-full rounded-full bg-orange-400" :style="'width:'+Math.min(100,Math.round(results.roadTaxSaving/results.totalBenefit*100))+'%'"></div></div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-base font-black" style="color:#00963C" x-text="fmt(results.roadTaxSaving)"></div>
                <div class="text-[10px] text-slate-400" x-text="'~'+results.roadTaxPct+'% of price'"></div>
              </div>
            </div>
            <!-- 80EEB -->
            <div class="result-row px-4 py-3 flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-teal-50">
                <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-slate-800 text-sm">Section 80EEB Tax Deduction</div>
                <div class="h-1 rounded-full bg-slate-100 mt-1.5 overflow-hidden"><div class="h-full rounded-full bg-teal-400" style="width:18%"></div></div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-base font-black" style="color:#00963C">₹46,800</div>
                <div class="text-[10px] text-slate-400">over loan tenure</div>
              </div>
            </div>
            <!-- Registration -->
            <div class="result-row px-4 py-3 flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-yellow-50">
                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/></svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-slate-800 text-sm">Registration Fee Exemption</div>
                <div class="h-1 rounded-full bg-slate-100 mt-1.5 overflow-hidden"><div class="h-full rounded-full bg-yellow-400" :style="'width:'+Math.min(100,Math.round(results.regFeeWaiver/results.totalBenefit*100))+'%'"></div></div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-base font-black" style="color:#00963C" x-text="fmt(results.regFeeWaiver)"></div>
                <div class="text-[10px] text-slate-400">on registration</div>
              </div>
            </div>
          </div>
          <!-- Total -->
          <div class="px-4 py-3.5 flex items-center justify-between" style="background:linear-gradient(135deg,rgba(0,230,118,.07),rgba(0,200,80,.04));border-top:2px solid rgba(0,230,118,.15)">
            <div>
              <div class="text-xs font-black uppercase tracking-widest" style="color:#00963C">Total Benefit</div>
              <div class="text-[10px] text-slate-400">Excl. 80EEB (claimed via ITR)</div>
            </div>
            <div class="text-xl sm:text-2xl font-black" style="color:#022C22" x-text="fmt(results.totalBenefit)"></div>
          </div>
        </div>

        <!-- Right col: disclaimer + lead form (1/3) -->
        <div class="lg:col-span-1 flex flex-col gap-3">
          <!-- Disclaimer -->
          <div class="rounded-xl p-3.5 flex gap-2.5" style="background:#fffbeb;border:1px solid #fde68a">
            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div class="text-xs text-amber-800"><strong>Note:</strong> Amounts based on 2024–25 government data. Verify eligibility with your dealer before purchase.</div>
          </div>

          <!-- Lead form -->
          <div class="rounded-2xl p-5 flex-1 relative overflow-hidden" style="background:linear-gradient(135deg,#00A896,#007A6E);border:1px solid rgba(0,230,118,.18);box-shadow:0 6px 24px rgba(0,0,0,.15)">
            <div class="absolute top-0 right-0 w-32 h-32 pointer-events-none" style="background:radial-gradient(circle,rgba(0,230,118,.1),transparent 70%);transform:translate(20%,-20%)"></div>
            <div class="relative">
              <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full mb-3 text-green-300" style="background:rgba(0,230,118,.12);border:1px solid rgba(0,230,118,.25)">Free Expert Help</div>
              <h3 class="text-base font-black text-white mb-1">Claim Every Rupee</h3>
              <p class="text-slate-300 text-xs mb-1">Our advisors help you claim all <span class="font-black" style="color:#00E676" x-text="fmt(results.totalBenefit)"></span>. Free consultation.</p>
              <ul class="text-xs text-slate-400 space-y-1 mb-4">
                <li class="flex items-center gap-1.5"><span style="color:#00E676">✓</span> FAME II eligible vehicles</li>
                <li class="flex items-center gap-1.5"><span style="color:#00E676">✓</span> State subsidy application help</li>
                <li class="flex items-center gap-1.5"><span style="color:#00E676">✓</span> 80EEB filing guidance</li>
              </ul>
              <form class="space-y-2" @submit.prevent="submitLead">
                <input type="tel" x-model="lead.mobile" placeholder="Mobile number *" required pattern="[6-9]\d{9}" maxlength="10" inputmode="numeric"
                  @input="lead.mobile=$event.target.value=$event.target.value.replace(/\D/g,'').slice(0,10)"
                  class="w-full rounded-lg px-3 py-2.5 text-[#0F172A] placeholder-slate-500 text-xs font-medium focus:outline-none transition-all"
                  style="background:#FFFFFF;border:1.5px solid rgba(255,255,255,.6)" onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.6)'">
                <input type="text" x-model="lead.city" placeholder="Your city *" required
                  class="w-full rounded-lg px-3 py-2.5 text-[#0F172A] placeholder-slate-500 text-xs font-medium focus:outline-none transition-all"
                  style="background:#FFFFFF;border:1.5px solid rgba(255,255,255,.6)" onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.6)'">
                <button type="submit" :disabled="leadSent"
                  style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22"
                  class="w-full font-black py-2.5 rounded-lg text-sm transition-all transform hover:scale-[1.02] disabled:opacity-60">
                  <span x-show="!leadSent">Get Free Advice →</span>
                  <span x-show="leadSent">✓ We'll call within 24h!</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-wrap gap-3 justify-center">
        <button @click="step=1;selectedState=null;vehicleType=null;vehiclePrice=150000"
          class="flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-600 font-bold text-sm hover:border-slate-300 hover:bg-slate-50 transition-all">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          Recalculate
        </button>
        <button @click="shareResult"
          class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition-all transform hover:scale-[1.02]"
          style="background:linear-gradient(135deg,#3b82f6,#2563eb);box-shadow:0 3px 12px rgba(59,130,246,.25)">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/></svg>
          Share Result
        </button>
      </div>
    </div>

  </div>
</div>

<script>
function subsidyCalc() {
  return {
    step: 1,
    selectedState: null,
    vehicleType: null,
    vehiclePrice: 150000,
    results: {},
    lead: { mobile: '', city: '' },
    leadSent: false,

    states: [
      { id:'delhi',   name:'Delhi',          flag:'🏙️', scheme:'Delhi EV Policy',      subsidy2w:30000,  subsidy3w:30000,  subsidy4w:150000, active:true  },
      { id:'mh',      name:'Maharashtra',    flag:'🌆', scheme:'Mahavikas EV Policy',  subsidy2w:10000,  subsidy3w:25000,  subsidy4w:150000, active:true  },
      { id:'gj',      name:'Gujarat',        flag:'🏭', scheme:'Gujarat EV Policy',    subsidy2w:20000,  subsidy3w:50000,  subsidy4w:150000, active:true  },
      { id:'rj',      name:'Rajasthan',      flag:'🏜️', scheme:'Rajasthan EV Policy',  subsidy2w:2500,   subsidy3w:10000,  subsidy4w:100000, active:true  },
      { id:'ka',      name:'Karnataka',      flag:'🌿', scheme:'Karnataka EV Policy',  subsidy2w:10000,  subsidy3w:30000,  subsidy4w:100000, active:true  },
      { id:'tn',      name:'Tamil Nadu',     flag:'🏛️', scheme:'TN Green Mobility',    subsidy2w:12000,  subsidy3w:30000,  subsidy4w:100000, active:true  },
      { id:'tg',      name:'Telangana',      flag:'🌐', scheme:'Telangana EV Policy',  subsidy2w:10000,  subsidy3w:25000,  subsidy4w:100000, active:true  },
      { id:'up',      name:'Uttar Pradesh',  flag:'🕌', scheme:'UP EV Policy 2022',    subsidy2w:5000,   subsidy3w:12000,  subsidy4w:100000, active:true  },
      { id:'wb',      name:'West Bengal',    flag:'🐯', scheme:'WB EV Policy',         subsidy2w:10000,  subsidy3w:20000,  subsidy4w:100000, active:true  },
      { id:'kl',      name:'Kerala',         flag:'🥥', scheme:'Kerala EV Policy',     subsidy2w:15000,  subsidy3w:30000,  subsidy4w:100000, active:true  },
      { id:'pb',      name:'Punjab',         flag:'🌾', scheme:'Punjab EV Policy',     subsidy2w:10000,  subsidy3w:20000,  subsidy4w:100000, active:true  },
      { id:'mp',      name:'Madhya Pradesh', flag:'🌳', scheme:'MP EV Policy 2021',    subsidy2w:5000,   subsidy3w:12000,  subsidy4w:50000,  active:true  },
      { id:'ap',      name:'Andhra Pradesh', flag:'🌴', scheme:'AP EV Policy',         subsidy2w:5000,   subsidy3w:12000,  subsidy4w:50000,  active:true  },
      { id:'hr',      name:'Haryana',        flag:'🚜', scheme:'Haryana EV Policy',    subsidy2w:15000,  subsidy3w:30000,  subsidy4w:100000, active:true  },
      { id:'other',   name:'Other State',    flag:'🇮🇳', scheme:'Central schemes only', subsidy2w:0,      subsidy3w:0,      subsidy4w:0,      active:false },
    ],

    vehicleTypes: [
      { id:'2w',         label:'2-Wheeler',  sub:'Scooter / Bike',    icon:'🛵', fameSubsidy:10000  },
      { id:'3w',         label:'3-Wheeler',  sub:'Auto / E-Rickshaw', icon:'🛺', fameSubsidy:50000  },
      { id:'4w',         label:'4-Wheeler',  sub:'Car / SUV',         icon:'🚗', fameSubsidy:150000 },
      { id:'commercial', label:'Commercial', sub:'LCV / Delivery',    icon:'🚚', fameSubsidy:150000 },
    ],

    initRange() {
      this.$nextTick(() => { const el = this.$refs.rangeSlider; if (el) this.updateRange(el); });
    },

    updateRange(el) {
      if (!el) return;
      const pct = ((Number(el.value)-Number(el.min))/(Number(el.max)-Number(el.min))*100).toFixed(1);
      el.style.setProperty('--pct', pct+'%');
    },

    selectState(s) { this.selectedState = s; },

    calcResults() {
      const vt = this.vehicleType, state = this.selectedState, price = Number(this.vehiclePrice);
      const fameSubsidy = this.vehicleTypes.find(v=>v.id===vt)?.fameSubsidy ?? 0;
      let stateSubsidy = 0;
      if (state?.active) stateSubsidy = vt==='2w'?state.subsidy2w:vt==='3w'?state.subsidy3w:state.subsidy4w;
      const roadTaxPcts = {'2w':4,'3w':5,'4w':10,'commercial':8};
      const roadTaxPct = roadTaxPcts[vt] ?? 8;
      const roadTaxSaving = Math.round(price * roadTaxPct / 100);
      const regFeeWaiver = vt==='4w'||vt==='commercial'?15000:vt==='3w'?5000:2000;
      this.results = { fameSubsidy, stateSubsidy, roadTaxSaving, roadTaxPct, regFeeWaiver, totalBenefit: fameSubsidy+stateSubsidy+roadTaxSaving+regFeeWaiver };
    },

    fmt(n) {
      if (!n && n!==0) return '—';
      return '₹'+Math.round(n).toLocaleString('en-IN');
    },

    submitLead() {
      if (!this.lead.mobile||!this.lead.city) return;
      this.leadSent = true;
    },

    shareResult() {
      const text = `I found ₹${Math.round(this.results.totalBenefit).toLocaleString('en-IN')} in EV subsidies on Charj.in! Check yours at charj.in/subsidy-calculator`;
      if (navigator.share) navigator.share({title:'My EV Subsidy',text});
      else navigator.clipboard.writeText(text).then(()=>alert('Copied!'));
    }
  }
}
</script>

<?= $this->endSection() ?>
