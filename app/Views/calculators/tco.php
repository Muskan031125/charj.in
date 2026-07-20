<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div x-data="tcoCalc()" class="bg-white">

  <!-- ── Hero ── -->
  <section class="hero-sm pt-8 sm:pt-20 md:pt-32 pb-8 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="max-w-5xl mx-auto text-center">
      <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-semibold mb-5" style="background:rgba(0,168,150,.1);border:1px solid rgba(0,168,150,.2);color:#00A896">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
        5-Year True Cost Analysis
      </div>
      <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight mb-4" style="color:#0F172A">EV vs Petrol — Total Cost of Ownership</h1>
      <p class="text-base max-w-2xl mx-auto" style="color:#475569">
        Compare the real 5-year cost including EMIs, fuel, maintenance, insurance and resale value. No surprises.
      </p>
    </div>
  </section>

  <div class="max-w-7xl mx-auto px-4 py-10">

    <!-- ── Inputs Grid ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

      <!-- EV Column -->
      <div class="bg-white rounded-3xl shadow-sm ring-1 ring-[#00A896]/30 overflow-hidden">
        <div class="bg-gradient-to-br from-[#00A896] to-[#007A6E] px-6 py-4 flex items-center gap-3">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">⚡</div>
          <div>
            <h2 class="text-white font-black text-lg">Electric Vehicle (EV)</h2>
            <p class="text-[#E0FFFC] text-xs">Your future EV details</p>
          </div>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid gap-4">
            <label class="grid gap-1">
              <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">EV Ex-Showroom Price (₹)</span>
              <input type="number" x-model="ev.price" min="50000" max="10000000" step="10000"
                @input="calc()"
                class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-[#00A896] focus:outline-none focus:ring-1 focus:ring-[#00A896]/30">
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Down Payment (₹)</span>
                <input type="number" x-model="ev.down" min="0" step="10000" @input="calc()"
                  class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-[#00A896] focus:outline-none">
              </label>
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Loan Rate (%)</span>
                <input type="number" x-model="ev.rate" min="5" max="20" step="0.5" @input="calc()"
                  class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-[#00A896] focus:outline-none">
              </label>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Tenure (Months)</span>
                <select x-model="ev.tenure" @change="calc()" class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-[#00A896] focus:outline-none">
                  <option value="24">24 months</option>
                  <option value="36" selected>36 months</option>
                  <option value="48">48 months</option>
                  <option value="60">60 months</option>
                  <option value="84">84 months</option>
                </select>
              </label>
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Resale after 5yr (%)</span>
                <input type="number" x-model="ev.resalePct" min="10" max="80" @input="calc()"
                  class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-[#00A896] focus:outline-none">
              </label>
            </div>
            <div class="rounded-2xl p-4 space-y-3">
              <h3 class="text-xs font-bold uppercase tracking-wide">Operating Costs</h3>
              <label class="grid gap-1">
                <span class="text-xs text-slate-600">Electricity Rate (₹/kWh)</span>
                <div class="flex items-center gap-3">
                  <input type="range" x-model="ev.elecRate" min="4" max="15" step="0.5" @input="calc()" class="flex-1 accent-[#00A896]">
                  <span class="w-12 text-center font-bold rounded-lg font-bold py-1 text-sm" x-text="'₹' + Number(ev.elecRate).toFixed(1)"></span>
                </div>
              </label>
              <label class="grid gap-1">
                <span class="text-xs text-slate-600">EV Efficiency (km/kWh)</span>
                <div class="flex items-center gap-3">
                  <input type="range" x-model="ev.efficiency" min="2" max="12" step="0.5" @input="calc()" class="flex-1 accent-[#00A896]">
                  <span class="w-12 text-center font-bold rounded-lg font-bold py-1 text-sm" x-text="Number(ev.efficiency).toFixed(1)"></span>
                </div>
              </label>
              <div class="grid grid-cols-2 gap-3">
                <label class="grid gap-1">
                  <span class="text-xs text-slate-600">Annual Insurance (₹)</span>
                  <input type="number" x-model="ev.insurance" step="500" @input="calc()"
                    class="rounded-lg border border-[#00A896]/25 px-3 py-2 text-sm font-bold focus:border-[#00A896] focus:outline-none">
                </label>
                <label class="grid gap-1">
                  <span class="text-xs text-slate-600">Annual Maintenance (₹)</span>
                  <input type="number" x-model="ev.maintenance" step="500" @input="calc()"
                    class="rounded-lg border border-[#00A896]/25 px-3 py-2 text-sm font-bold focus:border-[#00A896] focus:outline-none">
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Petrol Column -->
      <div class="bg-white rounded-3xl shadow-sm ring-1 ring-orange-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 flex items-center gap-3">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">⛽</div>
          <div>
            <h2 class="text-white font-black text-lg">Petrol Vehicle (ICE)</h2>
            <p class="text-orange-100 text-xs">Your current/alternative petrol vehicle</p>
          </div>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid gap-4">
            <label class="grid gap-1">
              <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Petrol Vehicle Price (₹)</span>
              <input type="number" x-model="petrol.price" min="50000" max="10000000" step="10000" @input="calc()"
                class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500">
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Down Payment (₹)</span>
                <input type="number" x-model="petrol.down" min="0" step="10000" @input="calc()"
                  class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-orange-500 focus:outline-none">
              </label>
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Loan Rate (%)</span>
                <input type="number" x-model="petrol.rate" min="5" max="20" step="0.5" @input="calc()"
                  class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-orange-500 focus:outline-none">
              </label>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Tenure (Months)</span>
                <select x-model="petrol.tenure" @change="calc()" class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-orange-500 focus:outline-none">
                  <option value="24">24 months</option>
                  <option value="36" selected>36 months</option>
                  <option value="48">48 months</option>
                  <option value="60">60 months</option>
                  <option value="84">84 months</option>
                </select>
              </label>
              <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Resale after 5yr (%)</span>
                <input type="number" x-model="petrol.resalePct" min="10" max="80" @input="calc()"
                  class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-orange-500 focus:outline-none">
              </label>
            </div>
            <div class="bg-orange-50 rounded-2xl p-4 space-y-3">
              <h3 class="text-xs font-bold text-orange-700 uppercase tracking-wide">Operating Costs</h3>
              <label class="grid gap-1">
                <span class="text-xs text-slate-600">Petrol Price (₹/litre)</span>
                <div class="flex items-center gap-3">
                  <input type="range" x-model="petrol.fuelPrice" min="80" max="140" step="1" @input="calc()" class="flex-1 accent-orange-500">
                  <span class="w-14 text-center font-bold text-orange-700 bg-orange-100 rounded-lg py-1 text-sm" x-text="'₹' + petrol.fuelPrice"></span>
                </div>
              </label>
              <label class="grid gap-1">
                <span class="text-xs text-slate-600">Mileage (km/litre)</span>
                <div class="flex items-center gap-3">
                  <input type="range" x-model="petrol.mileage" min="10" max="80" step="1" @input="calc()" class="flex-1 accent-orange-500">
                  <span class="w-14 text-center font-bold text-orange-700 bg-orange-100 rounded-lg py-1 text-sm" x-text="petrol.mileage + ' km/l'"></span>
                </div>
              </label>
              <div class="grid grid-cols-2 gap-3">
                <label class="grid gap-1">
                  <span class="text-xs text-slate-600">Annual Insurance (₹)</span>
                  <input type="number" x-model="petrol.insurance" step="500" @input="calc()"
                    class="rounded-lg border border-orange-200 px-3 py-2 text-sm font-bold focus:border-orange-500 focus:outline-none">
                </label>
                <label class="grid gap-1">
                  <span class="text-xs text-slate-600">Annual Maintenance (₹)</span>
                  <input type="number" x-model="petrol.maintenance" step="500" @input="calc()"
                    class="rounded-lg border border-orange-200 px-3 py-2 text-sm font-bold focus:border-orange-500 focus:outline-none">
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Common Inputs -->
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-6 mb-8">
      <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-4">Usage Pattern</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <label class="grid gap-1">
          <span class="text-xs text-slate-600">Daily km driven</span>
          <div class="flex items-center gap-2">
            <input type="range" x-model="common.dailyKm" min="10" max="200" step="5" @input="calc()" class="flex-1 accent-slate-600">
            <span class="w-16 text-center font-bold text-slate-700 bg-slate-100 rounded-lg py-1 text-sm" x-text="common.dailyKm + ' km'"></span>
          </div>
        </label>
        <label class="grid gap-1">
          <span class="text-xs text-slate-600">Days per month</span>
          <div class="flex items-center gap-2">
            <input type="range" x-model="common.daysPerMonth" min="10" max="30" step="1" @input="calc()" class="flex-1 accent-slate-600">
            <span class="w-12 text-center font-bold text-slate-700 bg-slate-100 rounded-lg py-1 text-sm" x-text="common.daysPerMonth"></span>
          </div>
        </label>
        <div class="col-span-2 flex items-center gap-3 bg-slate-50 rounded-xl p-3">
          <svg class="w-5 h-5 text-slate-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
          <div>
            <div class="text-xs text-slate-500">Annual km</div>
            <div class="font-black text-slate-800" x-text="(Number(common.dailyKm) * Number(common.daysPerMonth) * 12).toLocaleString('en-IN') + ' km/yr'"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Results ── -->
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-green-600 rounded-2xl p-5 text-white">
        <div class="text-xs font-semibold text-[#E0FFFC] uppercase tracking-wide mb-2">5-Year EV Cost</div>
        <div class="text-2xl font-black" x-text="fmt(r.evTotal5yr)"></div>
        <div class="text-green-200 text-xs mt-1">incl. resale deduction</div>
      </div>
      <div class="bg-orange-500 rounded-2xl p-5 text-white">
        <div class="text-xs font-semibold text-orange-100 uppercase tracking-wide mb-2">5-Year Petrol Cost</div>
        <div class="text-2xl font-black" x-text="fmt(r.petrolTotal5yr)"></div>
        <div class="text-orange-100 text-xs mt-1">incl. resale deduction</div>
      </div>
      <div class="rounded-2xl p-5 text-white col-span-2" style="background:linear-gradient(135deg,#00A896,#007A6E)">
        <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mb-2">You Save by Going EV</div>
        <div class="text-4xl font-black text-white" x-text="r.saving5yr >= 0 ? fmt(r.saving5yr) : '-' + fmt(Math.abs(r.saving5yr))"></div>
        <div class="flex flex-wrap gap-4 mt-2">
          <div class="text-white/70 text-xs">Monthly saving: <span class="text-white font-bold" x-text="fmt(r.monthlySaving)"></span></div>
          <div class="text-white/70 text-xs">Break-even: <span class="text-white font-bold" x-text="r.breakEven + ' months'"></span></div>
          <div class="text-white/70 text-xs">CO₂ saved: <span class="text-white font-bold" x-text="r.co2Saved + ' tonnes/yr'"></span></div>
        </div>
      </div>
    </div>

    <!-- Year-by-Year Table -->
    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 overflow-hidden mb-8">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-black text-slate-900">Year-by-Year Comparison</h3>
        <span class="text-xs text-slate-500">All figures in ₹</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[500px] text-sm">
          <thead>
            <tr class="bg-slate-50">
              <th class="text-left px-6 py-3 text-slate-500 font-semibold">Year</th>
              <th class="text-right px-4 py-3 text-green-600 font-semibold">EV EMI</th>
              <th class="text-right px-4 py-3 text-green-600 font-semibold">EV Fuel</th>
              <th class="text-right px-4 py-3 text-green-600 font-semibold">EV Running Total</th>
              <th class="text-right px-4 py-3 text-orange-500 font-semibold">Petrol EMI</th>
              <th class="text-right px-4 py-3 text-orange-500 font-semibold">Petrol Fuel</th>
              <th class="text-right px-4 py-3 text-orange-500 font-semibold">Petrol Running Total</th>
              <th class="text-right px-6 py-3 font-semibold">EV Advantage</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <template x-for="(row, i) in r.yearlyRows" :key="i">
              <tr :class="i % 2 === 0 ? 'bg-white' : 'bg-slate-50/50'">
                <td class="px-6 py-4 font-bold text-slate-700" x-text="'Year ' + row.year"></td>
                <td class="text-right px-4 py-4 text-green-700" x-text="fmtK(row.evEmi)"></td>
                <td class="text-right px-4 py-4 text-green-700" x-text="fmtK(row.evFuel)"></td>
                <td class="text-right px-4 py-4 font-bold text-green-800" x-text="fmtK(row.evCumulative)"></td>
                <td class="text-right px-4 py-4 text-orange-600" x-text="fmtK(row.petrolEmi)"></td>
                <td class="text-right px-4 py-4 text-orange-600" x-text="fmtK(row.petrolFuel)"></td>
                <td class="text-right px-4 py-4 font-bold text-orange-700" x-text="fmtK(row.petrolCumulative)"></td>
                <td class="text-right px-6 py-4 font-black" :class="row.advantage >= 0 ? 'text-green-600' : 'text-red-500'" x-text="(row.advantage >= 0 ? '+' : '') + fmtK(row.advantage)"></td>
              </tr>
            </template>
          </tbody>
          <tfoot>
            <tr class="bg-slate-100 font-black">
              <td class="px-6 py-4 text-slate-700">After Resale</td>
              <td class="px-4 py-4"></td>
              <td class="px-4 py-4"></td>
              <td class="text-right px-4 py-4 text-green-700" x-text="fmt(r.evTotal5yr)"></td>
              <td class="px-4 py-4"></td>
              <td class="px-4 py-4"></td>
              <td class="text-right px-4 py-4 text-orange-600" x-text="fmt(r.petrolTotal5yr)"></td>
              <td class="text-right px-6 py-4 text-green-700" x-text="fmt(r.saving5yr)"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- CSS Bar Chart -->
    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 mb-8">
      <h3 class="font-black text-slate-900 mb-6">Annual Cost Comparison (₹)</h3>
      <div class="space-y-6">
        <template x-for="(row, i) in r.yearlyRows" :key="i">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-slate-700" x-text="'Year ' + row.year"></span>
              <span class="text-xs text-slate-400" x-text="'EV: ' + fmtK(row.evAnnual) + '  |  Petrol: ' + fmtK(row.petrolAnnual)"></span>
            </div>
            <div class="space-y-1.5">
              <div class="flex items-center gap-2">
                <span class="text-xs text-green-600 w-8">EV</span>
                <div class="flex-1 bg-slate-100 rounded-full h-5 overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-green-500 to-green-400 rounded-full flex items-center pl-2 transition-all duration-700"
                    :style="'width: ' + (row.evAnnual / r.maxAnnual * 100).toFixed(1) + '%'">
                    <span class="text-white text-xs font-bold" x-show="row.evAnnual / r.maxAnnual > 0.2" x-text="fmtK(row.evAnnual)"></span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-xs text-orange-500 w-8">ICE</span>
                <div class="flex-1 bg-slate-100 rounded-full h-5 overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-orange-500 to-amber-400 rounded-full flex items-center pl-2 transition-all duration-700"
                    :style="'width: ' + (row.petrolAnnual / r.maxAnnual * 100).toFixed(1) + '%'">
                    <span class="text-white text-xs font-bold" x-show="row.petrolAnnual / r.maxAnnual > 0.2" x-text="fmtK(row.petrolAnnual)"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div class="mt-6 flex gap-4 text-xs text-slate-500">
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-green-500 rounded-full"></span>Electric Vehicle</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-orange-500 rounded-full"></span>Petrol Vehicle</span>
      </div>
    </div>

    <!-- Insight Box -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-[#00A896]/25 rounded-2xl p-6 mb-8">
      <div class="flex gap-4 items-start">
        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
          <svg class="w-6 h-6 text-green-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        </div>
        <div>
          <h3 class="font-black text-green-900 text-lg mb-1">Key Insight</h3>
          <p class="text-green-800 text-sm leading-relaxed" x-text="'After break-even month ' + r.breakEven + ', every single month you are saving ' + fmt(r.monthlySaving) + ' by driving electric. Over 5 years, your fleet of ' + (common.dailyKm * common.daysPerMonth * 12 / 1000).toFixed(0) + 'k annual km journey saves ' + r.co2Saved + ' tonnes of CO₂ — equivalent to planting ' + Math.round(r.co2Saved * 50) + ' trees.'"></p>
        </div>
      </div>
    </div>

    <!-- CO2 & Environmental Impact -->
    <div class="grid md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-2xl p-5 ring-1 ring-slate-200 text-center">
        <div class="text-3xl mb-2">🌳</div>
        <div class="text-2xl font-black text-green-600" x-text="Math.round(r.co2Saved * 50)"></div>
        <div class="text-xs text-slate-500 mt-1">Trees equivalent (annual)</div>
      </div>
      <div class="bg-white rounded-2xl p-5 ring-1 ring-slate-200 text-center">
        <div class="text-3xl mb-2">💨</div>
        <div class="text-2xl font-black text-green-600" x-text="r.co2Saved + ' T'"></div>
        <div class="text-xs text-slate-500 mt-1">CO₂ saved per year</div>
      </div>
      <div class="bg-white rounded-2xl p-5 ring-1 ring-slate-200 text-center">
        <div class="text-3xl mb-2">⛽</div>
        <div class="text-2xl font-black text-orange-500" x-text="r.litresSaved.toLocaleString('en-IN') + ' L'"></div>
        <div class="text-xs text-slate-500 mt-1">Petrol saved per year</div>
      </div>
    </div>

  </div>
  <div class="pb-4 md:pb-0"></div>
</div>

<script>
function tcoCalc() {
  return {
    ev: {
      price: 1400000,
      down: 200000,
      rate: 9,
      tenure: 36,
      elecRate: 8,
      efficiency: 5,
      insurance: 8000,
      maintenance: 3000,
      resalePct: 40
    },
    petrol: {
      price: 1100000,
      down: 150000,
      rate: 10,
      tenure: 36,
      fuelPrice: 105,
      mileage: 45,
      insurance: 12000,
      maintenance: 15000,
      resalePct: 55
    },
    common: {
      dailyKm: 40,
      daysPerMonth: 26
    },
    r: {},

    init() { this.calc(); },

    emi(price, down, rate, tenure) {
      const p = Math.max(0, price - down);
      const r = rate / 12 / 100;
      const n = Number(tenure);
      if (p <= 0 || r <= 0) return p / n;
      return (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
    },

    calc() {
      const annualKm = Number(this.common.dailyKm) * Number(this.common.daysPerMonth) * 12;

      // Monthly EMI
      const evEmi = this.emi(Number(this.ev.price), Number(this.ev.down), Number(this.ev.rate), Number(this.ev.tenure));
      const petrolEmi = this.emi(Number(this.petrol.price), Number(this.petrol.down), Number(this.petrol.rate), Number(this.petrol.tenure));

      // Annual fuel costs
      const evFuelAnnual = (annualKm / Number(this.ev.efficiency)) * Number(this.ev.elecRate);
      const petrolFuelAnnual = (annualKm / Number(this.petrol.mileage)) * Number(this.petrol.fuelPrice);

      // Resale
      const evResale = Number(this.ev.price) * Number(this.ev.resalePct) / 100;
      const petrolResale = Number(this.petrol.price) * Number(this.petrol.resalePct) / 100;

      let evCumulative = 0, petrolCumulative = 0;
      const yearlyRows = [];
      let maxAnnual = 0;

      for (let yr = 1; yr <= 5; yr++) {
        const evEmiAnnual = evEmi * 12 * (yr <= Math.ceil(Number(this.ev.tenure) / 12) ? 1 : 0);
        const petrolEmiAnnual = petrolEmi * 12 * (yr <= Math.ceil(Number(this.petrol.tenure) / 12) ? 1 : 0);
        const evAnnual = evEmiAnnual + evFuelAnnual + Number(this.ev.insurance) + Number(this.ev.maintenance);
        const petrolAnnual = petrolEmiAnnual + petrolFuelAnnual + Number(this.petrol.insurance) + Number(this.petrol.maintenance);
        evCumulative += evAnnual;
        petrolCumulative += petrolAnnual;
        if (evAnnual > maxAnnual) maxAnnual = evAnnual;
        if (petrolAnnual > maxAnnual) maxAnnual = petrolAnnual;
        yearlyRows.push({
          year: yr,
          evEmi: evEmiAnnual,
          evFuel: evFuelAnnual,
          evAnnual,
          evCumulative,
          petrolEmi: petrolEmiAnnual,
          petrolFuel: petrolFuelAnnual,
          petrolAnnual,
          petrolCumulative,
          advantage: petrolCumulative - evCumulative
        });
      }

      const evTotal5yr = evCumulative - evResale;
      const petrolTotal5yr = petrolCumulative - petrolResale;
      const saving5yr = petrolTotal5yr - evTotal5yr;
      const monthlySaving = Math.round((petrolFuelAnnual - evFuelAnnual + Number(this.petrol.maintenance) - Number(this.ev.maintenance) + Number(this.petrol.insurance) - Number(this.ev.insurance)) / 12);

      // Break-even: when cumulative EV cost < petrol cost
      let breakEven = 0;
      let evRunning = Number(this.ev.down) > 0 ? Number(this.ev.down) - Number(this.petrol.down) : 0;
      const evMonthly = evEmi + evFuelAnnual / 12 + (Number(this.ev.insurance) + Number(this.ev.maintenance)) / 12;
      const petrolMonthly = petrolEmi + petrolFuelAnnual / 12 + (Number(this.petrol.insurance) + Number(this.petrol.maintenance)) / 12;
      const monthlyNet = petrolMonthly - evMonthly;
      const extraUpfront = (Number(this.ev.price) - Number(this.ev.down)) > (Number(this.petrol.price) - Number(this.petrol.down))
        ? Math.abs(Number(this.ev.price) - Number(this.petrol.price))
        : 0;
      breakEven = monthlyNet > 0 ? Math.ceil(extraUpfront / monthlyNet) : 0;
      if (breakEven > 60) breakEven = 60;

      const litresSaved = Math.round(annualKm / Number(this.petrol.mileage));
      const co2Saved = Number(((annualKm * 0.12 - annualKm * 0.05) / 1000).toFixed(2));

      this.r = { evTotal5yr, petrolTotal5yr, saving5yr, monthlySaving, breakEven, yearlyRows, maxAnnual, co2Saved, litresSaved };
    },

    fmt(n) { return '₹' + Math.round(n).toLocaleString('en-IN'); },
    fmtK(n) {
      const abs = Math.abs(Math.round(n));
      if (abs >= 100000) return (n < 0 ? '-' : '') + '₹' + (abs / 100000).toFixed(2) + 'L';
      return (n < 0 ? '-' : '') + '₹' + abs.toLocaleString('en-IN');
    }
  }
}
</script>

<?= $this->endSection() ?>

