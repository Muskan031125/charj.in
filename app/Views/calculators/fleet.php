<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<style>
[x-cloak]{display:none!important}
input[type=range]{-webkit-appearance:none;appearance:none;height:6px;border-radius:9999px;outline:none;cursor:pointer}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#00E676,#00C060);box-shadow:0 2px 8px rgba(0,200,100,.35);cursor:pointer;transition:transform .15s}
input[type=range]::-webkit-slider-thumb:hover{transform:scale(1.15)}
.fleet-card{background:#fff;border:1.5px solid rgba(0,230,118,.12);border-radius:20px;box-shadow:0 2px 14px rgba(0,200,100,.05);transition:transform .25s cubic-bezier(.22,1,.36,1),box-shadow .25s cubic-bezier(.22,1,.36,1),border-color .25s ease}
.fleet-label{font-size:.65rem;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#374151}
.fleet-val{font-weight:900;color:#022C22}
.vtype-btn{border:1.5px solid rgba(0,230,118,.18);border-radius:14px;padding:10px 8px;text-align:left;background:#F5FFF7;transition:all .18s;cursor:pointer;width:100%}
.vtype-btn.active{border-color:#00C060;background:linear-gradient(135deg,rgba(0,230,118,.12),rgba(0,200,100,.06));box-shadow:0 0 0 3px rgba(0,230,118,.12)}
.result-dark{background:linear-gradient(135deg,#00A896,#007A6E);border-radius:20px}
/* Benefit cards get a hover lift — the rest of fleet-card stays static (inputs/results shouldn't jump around) */
.benefit-card{transition:transform .25s cubic-bezier(.22,1,.36,1),box-shadow .25s cubic-bezier(.22,1,.36,1),border-color .25s ease}
.benefit-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,200,100,.12),0 2px 8px rgba(0,0,0,.04);border-color:rgba(0,230,118,.3)}
.benefit-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.375rem;background:linear-gradient(135deg,rgba(0,230,118,.14),rgba(0,200,100,.06));border:1.5px solid rgba(0,230,118,.18)}
.stat-divider{border-left:1px solid rgba(255,255,255,.15)}
@media(max-width:639px){.stat-divider{border-left:none;border-top:1px solid rgba(255,255,255,.15);padding-top:.75rem}}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
.anim-1{animation:fadeUp .5s .06s both}
.anim-2{animation:fadeUp .5s .14s both}
.anim-3{animation:fadeUp .5s .22s both}
</style>

<div x-data="fleetCalc()">

  <!-- ── Hero ── -->
  <section class="hero-sm relative overflow-hidden pt-8 sm:pt-20 md:pt-32 pb-6 sm:pb-10 px-4 text-center"
           style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <!-- glows -->
    <div class="absolute inset-0 pointer-events-none">
      <div style="position:absolute;top:-80px;right:-80px;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(0,168,150,.1),transparent 70%);filter:blur(60px)"></div>
      <div style="position:absolute;bottom:-60px;left:-40px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(0,168,150,.07),transparent 70%);filter:blur(50px)"></div>
    </div>

    <div class="max-w-3xl mx-auto relative">

      <!-- Badge -->
      <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-black uppercase tracking-widest mb-4"
           style="background:rgba(0,168,150,.1);color:#00A896;border:1.5px solid rgba(0,168,150,.2)">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
        EV Fleet ROI Calculator
      </div>

      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight mb-3 leading-tight" style="color:#0F172A">
        Switch Your Fleet to EV — See Exact Savings
      </h1>
      <p class="text-base sm:text-lg mb-6 max-w-xl mx-auto" style="color:#475569">
        Real numbers: fuel savings, break-even period, CO₂ impact and 5-year ROI for your business fleet.
      </p>

      <!-- Live stats strip -->
      <div class="grid grid-cols-3 gap-3 max-w-sm mx-auto">
        <div class="rounded-2xl p-3 text-center" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.15)">
          <div class="text-lg sm:text-2xl font-black" style="color:#00A896" x-text="fmt(r.annualSaving)"></div>
          <div class="text-[10px] mt-0.5" style="color:#64748B">Annual saving</div>
        </div>
        <div class="rounded-2xl p-3 text-center" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.15)">
          <div class="text-lg sm:text-2xl font-black" style="color:#00A896" x-text="r.breakEvenMonths + 'M'"></div>
          <div class="text-[10px] mt-0.5" style="color:#64748B">Break-even</div>
        </div>
        <div class="rounded-2xl p-3 text-center" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.15)">
          <div class="text-lg sm:text-2xl font-black" style="color:#00A896" x-text="r.co2Annual + 'T'"></div>
          <div class="text-[10px] mt-0.5" style="color:#64748B">CO₂ saved/yr</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Calculator Body ── -->
  <div class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-[1fr_380px] gap-6 items-start">

      <!-- ═══ LEFT: Inputs ═══ -->
      <div class="space-y-5">

        <!-- Fleet & Usage -->
        <div class="fleet-card p-5 sm:p-6 anim-1">
          <h2 class="font-black text-base mb-5" style="color:#022C22">Fleet & Usage</h2>

          <div class="space-y-5">

            <!-- Number of vehicles -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="fleet-label">Number of Vehicles</span>
                <span class="fleet-val text-xl" x-text="inputs.numVehicles"></span>
              </div>
              <input type="range" x-model="inputs.numVehicles" min="1" max="500" step="1" @input="calc()"
                class="w-full" style="background:linear-gradient(90deg,#00C060,#A7F3D0)">
              <div class="flex justify-between text-[10px] font-bold mt-1" style="color:#94A3B8"><span>1</span><span>500</span></div>
            </div>

            <!-- Vehicle type -->
            <div>
              <span class="fleet-label block mb-2">Vehicle Type</span>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <template x-for="vt in vehicleTypes" :key="vt.id">
                  <button @click="inputs.vehicleType = vt.id; calc()"
                    :class="inputs.vehicleType === vt.id ? 'vtype-btn active' : 'vtype-btn'">
                    <div class="text-xl mb-1" x-text="vt.icon"></div>
                    <div class="text-xs font-black leading-tight" style="color:#0F172A" x-text="vt.label"></div>
                    <div class="text-[10px] font-medium mt-0.5" style="color:#94A3B8" x-text="vt.sub"></div>
                  </button>
                </template>
              </div>
            </div>

            <!-- Daily km + Working days -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="fleet-label">Daily km/vehicle</span>
                  <span class="font-black text-sm" style="color:#00963C" x-text="inputs.dailyKm + ' km'"></span>
                </div>
                <input type="range" x-model="inputs.dailyKm" min="20" max="300" step="5" @input="calc()"
                  class="w-full" style="background:linear-gradient(90deg,#00C060,#A7F3D0)">
              </div>
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="fleet-label">Working days/mo</span>
                  <span class="font-black text-sm" style="color:#00963C" x-text="inputs.workDays"></span>
                </div>
                <input type="range" x-model="inputs.workDays" min="15" max="30" step="1" @input="calc()"
                  class="w-full" style="background:linear-gradient(90deg,#00C060,#A7F3D0)">
              </div>
            </div>
          </div>
        </div>

        <!-- Petrol vs EV Costs -->
        <div class="fleet-card p-5 sm:p-6 anim-2">
          <h2 class="font-black text-base mb-5" style="color:#022C22">Petrol Fleet vs EV Fleet</h2>

          <div class="grid sm:grid-cols-2 gap-6">

            <!-- Petrol -->
            <div class="space-y-4">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:#F97316"></span>
                <span class="text-xs font-black uppercase tracking-widest" style="color:#EA580C">Current Petrol</span>
              </div>

              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <span class="fleet-label">Fuel cost (₹/litre)</span>
                  <span class="text-xs font-black px-2 py-0.5 rounded-lg" style="background:#FFF7ED;color:#C2410C" x-text="'₹' + inputs.fuelPrice"></span>
                </div>
                <input type="range" x-model="inputs.fuelPrice" min="80" max="140" step="1" @input="calc()"
                  class="w-full" style="background:linear-gradient(90deg,#F97316,#FED7AA)">
              </div>

              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <span class="fleet-label">Mileage (km/litre)</span>
                  <span class="text-xs font-black px-2 py-0.5 rounded-lg" style="background:#FFF7ED;color:#C2410C" x-text="inputs.mileage + ' km'"></span>
                </div>
                <input type="range" x-model="inputs.mileage" min="15" max="60" step="1" @input="calc()"
                  class="w-full" style="background:linear-gradient(90deg,#F97316,#FED7AA)">
              </div>

              <div>
                <label class="fleet-label block mb-1.5">Maintenance/vehicle/yr (₹)</label>
                <input type="number" x-model="inputs.petrolMaintenance" step="1000" @input="calc()"
                  class="w-full rounded-xl px-3 py-2.5 text-sm font-black focus:outline-none"
                  style="border:1.5px solid rgba(249,115,22,.25);color:#0F172A;background:#FFFBF5"
                  onfocus="this.style.borderColor='#F97316'" onblur="this.style.borderColor='rgba(249,115,22,.25)'">
              </div>
            </div>

            <!-- EV -->
            <div class="space-y-4">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:#00C060"></span>
                <span class="text-xs font-black uppercase tracking-widest" style="color:#00963C">EV Fleet</span>
              </div>

              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <span class="fleet-label">Electricity (₹/kWh)</span>
                  <span class="text-xs font-black px-2 py-0.5 rounded-lg" style="background:#F0FFF4;color:#166534" x-text="'₹' + Number(inputs.elecRate).toFixed(1)"></span>
                </div>
                <input type="range" x-model="inputs.elecRate" min="4" max="15" step="0.5" @input="calc()"
                  class="w-full" style="background:linear-gradient(90deg,#00C060,#A7F3D0)">
              </div>

              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <span class="fleet-label">EV efficiency (km/kWh)</span>
                  <span class="text-xs font-black px-2 py-0.5 rounded-lg" style="background:#F0FFF4;color:#166534" x-text="Number(inputs.evEfficiency).toFixed(1)"></span>
                </div>
                <input type="range" x-model="inputs.evEfficiency" min="2" max="12" step="0.5" @input="calc()"
                  class="w-full" style="background:linear-gradient(90deg,#00C060,#A7F3D0)">
              </div>

              <div>
                <label class="fleet-label block mb-1.5">Maintenance/vehicle/yr (₹)</label>
                <input type="number" x-model="inputs.evMaintenance" step="500" @input="calc()"
                  class="w-full rounded-xl px-3 py-2.5 text-sm font-black focus:outline-none"
                  style="border:1.5px solid rgba(0,230,118,.25);color:#0F172A;background:#F5FFF7"
                  onfocus="this.style.borderColor='#00C060'" onblur="this.style.borderColor='rgba(0,230,118,.25)'">
              </div>
            </div>
          </div>
        </div>

        <!-- Payback Inputs -->
        <div class="fleet-card p-5 sm:p-6 anim-3">
          <h2 class="font-black text-base mb-4" style="color:#022C22">Payback Period</h2>

          <div class="grid sm:grid-cols-2 gap-4 mb-5">
            <div>
              <label class="fleet-label block mb-1.5">Extra cost per EV vs ICE (₹)</label>
              <input type="number" x-model="inputs.extraCostPerVehicle" step="5000" @input="calc()"
                class="w-full rounded-xl px-3 py-2.5 font-black text-sm focus:outline-none"
                style="border:1.5px solid rgba(0,230,118,.18);color:#0F172A;background:#F9FAFB"
                onfocus="this.style.borderColor='#00C060'" onblur="this.style.borderColor='rgba(0,230,118,.18)'">
              <p class="text-[10px] font-medium mt-1" style="color:#94A3B8">e.g. EV costs ₹1,20,000 more than petrol equiv.</p>
            </div>
            <div>
              <label class="fleet-label block mb-1.5">FAME II subsidy/vehicle (₹)</label>
              <input type="number" x-model="inputs.fameSubsidy" step="5000" @input="calc()"
                class="w-full rounded-xl px-3 py-2.5 font-black text-sm focus:outline-none"
                style="border:1.5px solid rgba(0,230,118,.18);color:#0F172A;background:#F9FAFB"
                onfocus="this.style.borderColor='#00C060'" onblur="this.style.borderColor='rgba(0,230,118,.18)'">
              <p class="text-[10px] font-medium mt-1" style="color:#94A3B8">₹50K for 3W · ₹1.5L for 4W commercial</p>
            </div>
          </div>

          <!-- Payback result row -->
          <div class="result-dark p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-0 text-white">
            <div>
              <div class="text-[10px] font-black uppercase tracking-widest mb-1" style="color:rgba(255,255,255,.5)">Net extra investment</div>
              <div class="text-xl font-black text-white" x-text="fmt(r.netExtraInvestment)"></div>
            </div>
            <div class="stat-divider sm:pl-5">
              <div class="text-[10px] font-black uppercase tracking-widest mb-1" style="color:rgba(255,255,255,.5)">Break-even</div>
              <div class="text-xl font-black" style="color:#00E676" x-text="r.breakEvenMonths + ' months'"></div>
            </div>
            <div class="stat-divider sm:pl-5">
              <div class="text-[10px] font-black uppercase tracking-widest mb-1" style="color:rgba(255,255,255,.5)">5-year net profit</div>
              <div class="text-xl font-black" style="color:#00E676" x-text="fmt(r.fiveYearNetProfit)"></div>
            </div>
          </div>
        </div>

      </div><!-- /left inputs -->

      <!-- ═══ RIGHT: Results — shown first on mobile via order ═══ -->
      <div class="space-y-5 order-first lg:order-last lg:sticky lg:top-20 lg:self-start">

        <!-- Main savings card -->
        <div class="result-dark p-5 text-white">
          <div class="flex items-center gap-2.5 mb-4">
            <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-base" style="background:rgba(255,255,255,.12)">💰</span>
            <div class="text-[10px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.6)">Fleet Savings Summary</div>
          </div>

          <div class="grid grid-cols-2 gap-2.5 mb-2.5">
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,.08)">
              <div class="text-[9px] font-bold mb-1 truncate" style="color:rgba(255,255,255,.55)">Fuel saving /mo</div>
              <div class="text-base sm:text-xl font-black truncate" style="color:#69FF97" x-text="fmt(r.monthlyFuelSaving)"></div>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,.08)">
              <div class="text-[9px] font-bold mb-1 truncate" style="color:rgba(255,255,255,.55)">Maint. saving /mo</div>
              <div class="text-base sm:text-xl font-black truncate" style="color:#69FF97" x-text="fmt(r.monthlyMaintSaving)"></div>
            </div>
          </div>

          <div class="rounded-2xl p-4 mb-2.5" style="background:#fff">
            <div class="text-[10px] font-black uppercase tracking-widest mb-1" style="color:#007A6E">Total Monthly Saving</div>
            <div class="text-2xl sm:text-3xl font-black" style="color:#022C22" x-text="fmt(r.totalMonthlySaving)"></div>
          </div>

          <div class="grid grid-cols-2 gap-2.5">
            <div class="rounded-xl p-3 text-center" style="background:rgba(255,255,255,.08)">
              <div class="text-[9px] mb-1" style="color:rgba(255,255,255,.5)">Annual</div>
              <div class="font-black text-sm sm:text-base truncate" style="color:#69FF97" x-text="fmt(r.annualSaving)"></div>
            </div>
            <div class="rounded-xl p-3 text-center" style="background:rgba(255,255,255,.08)">
              <div class="text-[9px] mb-1" style="color:rgba(255,255,255,.5)">5-Year</div>
              <div class="font-black text-sm sm:text-base truncate" style="color:#69FF97" x-text="fmt(r.fiveYearSaving)"></div>
            </div>
          </div>
        </div>

        <!-- Per-vehicle breakdown -->
        <div class="fleet-card p-5">
          <h3 class="font-black text-sm mb-3" style="color:#022C22">Per Vehicle Economics</h3>
          <div class="space-y-2.5">
            <div class="flex justify-between items-center">
              <span class="text-xs font-medium" style="color:#64748B">Daily saving/vehicle</span>
              <span class="font-black text-sm" style="color:#00963C" x-text="fmt(r.perVehicleDailySaving)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs font-medium" style="color:#64748B">Monthly saving/vehicle</span>
              <span class="font-black text-sm" style="color:#00963C" x-text="fmt(r.perVehicleMonthlySaving)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs font-medium" style="color:#64748B">Annual saving/vehicle</span>
              <span class="font-black text-sm" style="color:#00963C" x-text="fmt(r.perVehicleAnnualSaving)"></span>
            </div>
            <div class="pt-2.5" style="border-top:1px solid rgba(0,230,118,.12)">
              <div class="flex justify-between items-center">
                <span class="text-xs font-medium" style="color:#64748B">CO₂ reduced/vehicle/yr</span>
                <span class="font-black text-sm" style="color:#00963C" x-text="(r.co2Annual / Number(inputs.numVehicles)).toFixed(2) + ' T'"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Environmental -->
        <div class="result-dark p-5 text-white">
          <h3 class="text-[10px] font-black uppercase tracking-widest mb-4" style="color:rgba(255,255,255,.5)">Environmental Impact / Year</h3>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <span class="text-xl flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,.08)">🌿</span>
              <div>
                <div class="font-black" style="color:#00E676" x-text="r.co2Annual + ' tonnes'"></div>
                <div class="text-[10px]" style="color:rgba(255,255,255,.5)">CO₂ emissions avoided</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xl flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,.08)">🌳</span>
              <div>
                <div class="font-black" style="color:#00E676" x-text="(r.co2Annual * 50).toLocaleString('en-IN')"></div>
                <div class="text-[10px]" style="color:rgba(255,255,255,.5)">Equivalent trees planted</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xl flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,.08)">⛽</span>
              <div>
                <div class="font-black" style="color:#FCD34D" x-text="r.litresSaved.toLocaleString('en-IN') + ' L'"></div>
                <div class="text-[10px]" style="color:rgba(255,255,255,.5)">Petrol not burned</div>
              </div>
            </div>
          </div>
        </div>

        <!-- At a glance callout -->
        <div class="rounded-2xl p-4" style="background:linear-gradient(135deg,rgba(0,230,118,.08),rgba(0,200,100,.04));border:1.5px solid rgba(0,230,118,.2)">
          <p class="text-sm font-bold leading-relaxed" style="color:#022C22"
            x-text="'Your fleet of ' + inputs.numVehicles + ' vehicles saves ' + fmt(r.dailySaving) + ' per day — that\'s ' + fmt(r.annualSaving) + ' flowing directly to your bottom line every year.'"></p>
        </div>

      </div><!-- /right results -->

    </div><!-- /grid -->

    <!-- ══ Benefits ══ -->
    <section class="mt-14">
      <h2 class="text-2xl sm:text-3xl font-black mb-1.5" style="color:#022C22">Why EV Fleet Makes Business Sense</h2>
      <p class="text-sm mb-7" style="color:#64748B">Strategic advantages beyond fuel savings.</p>

      <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
        <?php foreach ([
          ['🏛️','Higher FAME II Subsidy',       'Commercial EV buyers receive ₹50,000–₹1,50,000 per vehicle in subsidies — directly reducing fleet acquisition cost.'],
          ['📊','GST Input Tax Credit',           'Claim 28% GST on EV purchases as input credit, unlike personal buyers, significantly reducing effective cost.'],
          ['🌱','ESG & CSR Benefits',             'A documented green fleet strengthens ESG scores, supports SEBI BRSR reporting, and may qualify for green bonds.'],
          ['⚡','Stable Operating Costs',         'Petrol prices rose 40%+ in 5 years. Electricity costs are regulated and far more predictable for P&L forecasting.'],
          ['🛡️','Improved Safety',               'No fuel tanks, no combustion risk. EV fires are significantly rarer — translating to lower insurance premiums.'],
          ['🔋','Priority Commercial Charging',  'Registered commercial EV fleets get priority at EESL-managed stations and can negotiate dedicated charging with DISCOMs.'],
        ] as [$ico, $title, $desc]): ?>
        <div class="fleet-card benefit-card p-5 sm:p-6">
          <div class="benefit-icon mb-4"><?= $ico ?></div>
          <h3 class="font-black text-sm mb-2" style="color:#022C22"><?= $title ?></h3>
          <p class="text-xs leading-relaxed" style="color:#64748B"><?= $desc ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ══ Lead Form ══ -->
    <section class="mt-12 rounded-3xl p-6 sm:p-10 text-white"
             style="background:linear-gradient(135deg,#00A896,#007A6E)"
             x-data="{ sent: false, form: { company:'', fleetSize:'', vehicleType:'', mobile:'', email:'' } }">

      <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">

        <!-- Left copy -->
        <div>
          <h2 class="text-2xl sm:text-3xl font-black mb-3">Get a Custom Fleet EV Proposal</h2>
          <p class="text-sm mb-5" style="color:rgba(255,255,255,.65)">Our fleet team will analyse your routes, vehicle types and financials to build a detailed ROI report — free.</p>
          <ul class="space-y-2.5 text-sm" style="color:rgba(255,255,255,.7)">
            <?php foreach ([
              'Custom ROI report for your exact fleet composition',
              'EV model recommendations per vehicle category',
              'FAME II application guidance',
              'Charging infrastructure planning',
            ] as $li): ?>
            <li class="flex items-start gap-2">
              <svg class="w-4 h-4 flex-shrink-0 mt-0.5" style="color:#00E676" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              <?= $li ?>
            </li>
            <?php endforeach; ?>
          </ul>
          <div class="mt-5 inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm"
               style="background:rgba(0,230,118,.1);border:1px solid rgba(0,230,118,.25);color:#69FF97">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
            Fleet team responds within <strong class="ml-1">4 business hours</strong>
          </div>
        </div>

        <!-- Right form -->
        <div x-show="!sent">
          <form class="space-y-3" @submit.prevent="sent = true">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <input type="text" x-model="form.company" placeholder="Company name *" required
                class="rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-white/40 focus:outline-none"
                style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)"
                onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.18)'">
              <input type="text" x-model="form.fleetSize" placeholder="Fleet size *" required
                class="rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-white/40 focus:outline-none"
                style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)"
                onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.18)'">
            </div>
            <select x-model="form.vehicleType"
              class="w-full rounded-xl px-4 py-3 text-sm font-bold text-white focus:outline-none"
              style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)"
              onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.18)'">
              <option value="" class="text-slate-800 bg-white">Primary vehicle type *</option>
              <option value="2w_delivery" class="text-slate-800 bg-white">2-Wheeler — Last mile delivery</option>
              <option value="3w_cargo" class="text-slate-800 bg-white">3-Wheeler — Cargo/auto</option>
              <option value="4w_cab" class="text-slate-800 bg-white">4-Wheeler — Cab/corporate</option>
              <option value="lcv" class="text-slate-800 bg-white">LCV — Light commercial van</option>
              <option value="mixed" class="text-slate-800 bg-white">Mixed fleet</option>
            </select>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <input type="tel" x-model="form.mobile" placeholder="Mobile *" required
                pattern="[6-9]\d{9}" maxlength="10" minlength="10" inputmode="numeric"
                @input="form.mobile = $event.target.value = $event.target.value.replace(/\D/g,'').slice(0,10)"
                class="rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-white/40 focus:outline-none"
                style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)"
                onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.18)'">
              <input type="email" x-model="form.email" placeholder="Work email *" required
                class="rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-white/40 focus:outline-none"
                style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)"
                onfocus="this.style.borderColor='#00E676'" onblur="this.style.borderColor='rgba(255,255,255,.18)'">
            </div>
            <button type="submit"
              class="w-full font-black py-3.5 rounded-2xl text-base transition-all"
              style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 16px rgba(0,230,118,.3)"
              onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(0,230,118,.4)'"
              onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(0,230,118,.3)'">
              Get My Fleet ROI Report →
            </button>
            <p class="text-xs text-center" style="color:rgba(255,255,255,.4)">No spam. No sales calls. Just real numbers.</p>
          </form>
        </div>

        <div x-show="sent" x-cloak
             class="rounded-2xl p-8 text-center"
             style="background:rgba(0,230,118,.1);border:1px solid rgba(0,230,118,.25)">
          <div class="text-5xl mb-4">✅</div>
          <h3 class="text-2xl font-black mb-2">Request Received!</h3>
          <p style="color:rgba(255,255,255,.7)">Our fleet specialist will send you a custom EV ROI report within 4 business hours.</p>
        </div>

      </div>
    </section>

  </div><!-- /max-w-6xl -->
</div><!-- /x-data -->
<div class="h-20 md:h-0"></div><!-- bottom spacing for mobile nav -->

<script>
function fleetCalc() {
  return {
    inputs: {
      numVehicles:        50,
      vehicleType:        '2w_delivery',
      dailyKm:            40,
      workDays:           26,
      fuelPrice:          105,
      mileage:            35,
      elecRate:           8,
      evEfficiency:       5,
      petrolMaintenance:  15000,
      evMaintenance:      3000,
      extraCostPerVehicle:120000,
      fameSubsidy:        50000,
    },

    vehicleTypes: [
      { id: '2w_delivery', label: '2W Delivery',   sub: 'Scooter / e-bike', icon: '🛵', fameSubsidy: 10000  },
      { id: '3w_cargo',    label: '3W Auto/Cargo', sub: 'E-rickshaw / L5',  icon: '🛺', fameSubsidy: 50000  },
      { id: '4w_cab',      label: '4W Cab/Corp.',  sub: 'Car / MUV',        icon: '🚗', fameSubsidy: 150000 },
      { id: 'lcv',         label: 'LCV',           sub: 'Light comm. van',  icon: '🚚', fameSubsidy: 150000 },
    ],

    r: {},

    init() { this.calc(); },

    calc() {
      const n           = Number(this.inputs.numVehicles);
      const dailyKm     = Number(this.inputs.dailyKm);
      const workDays    = Number(this.inputs.workDays);
      const fuelPrice   = Number(this.inputs.fuelPrice);
      const mileage     = Number(this.inputs.mileage);
      const elecRate    = Number(this.inputs.elecRate);
      const evEff       = Number(this.inputs.evEfficiency);
      const petrolMaint = Number(this.inputs.petrolMaintenance);
      const evMaint     = Number(this.inputs.evMaintenance);
      const extraCost   = Number(this.inputs.extraCostPerVehicle);
      const fameSub     = Number(this.inputs.fameSubsidy);

      const monthlyKm = dailyKm * workDays;
      const annualKm  = monthlyKm * 12;

      const petrolFuelMonthly = (monthlyKm / mileage) * fuelPrice;
      const evFuelMonthly     = (monthlyKm / evEff) * elecRate;

      const fuelSavingPerVehicleMonthly  = petrolFuelMonthly - evFuelMonthly;
      const maintSavingPerVehicleMonthly = (petrolMaint - evMaint) / 12;
      const totalSavingPerVehicleMonthly = fuelSavingPerVehicleMonthly + maintSavingPerVehicleMonthly;

      const perVehicleDailySaving   = totalSavingPerVehicleMonthly / workDays;
      const perVehicleMonthlySaving = totalSavingPerVehicleMonthly;
      const perVehicleAnnualSaving  = totalSavingPerVehicleMonthly * 12;

      const monthlyFuelSaving  = fuelSavingPerVehicleMonthly * n;
      const monthlyMaintSaving = maintSavingPerVehicleMonthly * n;
      const totalMonthlySaving = totalSavingPerVehicleMonthly * n;
      const annualSaving       = totalMonthlySaving * 12;
      const fiveYearSaving     = annualSaving * 5;
      const dailySaving        = totalMonthlySaving / workDays;

      const netExtraPerVehicle = Math.max(0, extraCost - fameSub);
      const netExtraInvestment = netExtraPerVehicle * n;
      const breakEvenMonths    = totalMonthlySaving > 0 ? Math.ceil(netExtraInvestment / totalMonthlySaving) : 999;
      const fiveYearNetProfit  = fiveYearSaving - netExtraInvestment;

      const litresSaved = Math.round(annualKm * n / mileage);
      const co2Annual   = Number(((annualKm * n * 0.12 - annualKm * n * 0.05) / 1000).toFixed(1));

      this.r = {
        monthlyFuelSaving, monthlyMaintSaving, totalMonthlySaving, annualSaving, fiveYearSaving,
        dailySaving, perVehicleDailySaving, perVehicleMonthlySaving, perVehicleAnnualSaving,
        netExtraInvestment, breakEvenMonths, fiveYearNetProfit, co2Annual, litresSaved
      };
    },

    fmt(n) {
      if (!n && n !== 0) return '₹0';
      const abs = Math.abs(Math.round(n));
      if (abs >= 10000000) return (n < 0 ? '-' : '') + '₹' + (abs / 10000000).toFixed(2) + 'Cr';
      if (abs >= 100000)   return (n < 0 ? '-' : '') + '₹' + (abs / 100000).toFixed(2) + 'L';
      if (abs >= 1000)     return (n < 0 ? '-' : '') + '₹' + (abs / 1000).toFixed(1) + 'K';
      return (n < 0 ? '-' : '') + '₹' + abs.toLocaleString('en-IN');
    }
  }
}
</script>

<?= $this->endSection() ?>
