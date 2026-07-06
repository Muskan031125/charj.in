<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="min-h-screen bg-slate-50">

  <!-- Page hero -->
  <div class="hero-sm py-8 pt-28" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="mx-auto max-w-2xl px-4 text-center">
      <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold mb-4" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
        Savings Calculator
      </div>
      <h1 class="text-3xl lg:text-4xl font-black" style="color:#0F172A">EV vs Petrol — 5-Year Cost Comparison</h1>
      <p class="mt-2 text-sm" style="color:#475569">See exactly how much you save by going electric, including EMI, fuel, maintenance and insurance.</p>
    </div>
  </div>

  <!-- Tool content -->
  <div class="mx-auto max-w-5xl px-4 py-10 -mt-6">
    <div class="lg:grid lg:grid-cols-[1fr_360px] lg:gap-8" x-data="{
        /* EV inputs */
        evPrice: 140000,
        evDown: 30000,
        evRate: 9,
        evTenure: 36,
        evEfficiency: 5,
        electricity: 8,
        evMaintain: 5000,
        evInsurance: 8000,
        /* Petrol inputs */
        petrolPrice: 100000,
        petrolDown: 20000,
        petrolRate: 10,
        petrolTenure: 36,
        petrolMileage: 50,
        petrolCostPerL: 105,
        petrolMaintain: 12000,
        petrolInsurance: 10000,
        /* Common */
        dailyKm: 40,
        years: 5,
        get monthlyKm() { return this.dailyKm * 26 },
        /* EV calcs */
        get evLoan() { return Math.max(0, this.evPrice - this.evDown) },
        get evEmi() {
            const p = this.evLoan, r = this.evRate/12/100, n = this.evTenure;
            if (!p || !r) return 0;
            return (p * r * Math.pow(1+r,n)) / (Math.pow(1+r,n)-1);
        },
        get evFuelMonthly() { return (this.monthlyKm / this.evEfficiency) * this.electricity },
        get evTotalMonthly() { return this.evEmi + this.evFuelMonthly + this.evMaintain/12 + this.evInsurance/12 },
        get evTotal5yr() { return this.evDown + (this.evEmi * this.evTenure) + (this.evFuelMonthly * 12 * this.years) + (this.evMaintain + this.evInsurance) * this.years },
        /* Petrol calcs */
        get petrolLoan() { return Math.max(0, this.petrolPrice - this.petrolDown) },
        get petrolEmi() {
            const p = this.petrolLoan, r = this.petrolRate/12/100, n = this.petrolTenure;
            if (!p || !r) return 0;
            return (p * r * Math.pow(1+r,n)) / (Math.pow(1+r,n)-1);
        },
        get petrolFuelMonthly() { return (this.monthlyKm / this.petrolMileage) * this.petrolCostPerL },
        get petrolTotalMonthly() { return this.petrolEmi + this.petrolFuelMonthly + this.petrolMaintain/12 + this.petrolInsurance/12 },
        get petrolTotal5yr() { return this.petrolDown + (this.petrolEmi * this.petrolTenure) + (this.petrolFuelMonthly * 12 * this.years) + (this.petrolMaintain + this.petrolInsurance) * this.years },
        /* Savings */
        get monthlySaving() { return this.petrolTotalMonthly - this.evTotalMonthly },
        get totalSaving5yr() { return this.petrolTotal5yr - this.evTotal5yr },
        get breakEvenMonths() {
            const extraUpfront = (this.evPrice - this.petrolPrice);
            const monthlyDiff = this.petrolTotalMonthly - this.evTotalMonthly;
            if (monthlyDiff <= 0) return 999;
            return Math.ceil(extraUpfront / monthlyDiff);
        },
        fmt(n) { return '₹' + Math.abs(Math.round(n)).toLocaleString('en-IN') }
    }">

        <div class="space-y-6">
            <!-- Usage -->
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="font-bold text-slate-800 mb-4">Your Daily Usage</h2>
                <div class="grid gap-4 md:grid-cols-3">
                    <label class="grid gap-1 text-sm font-semibold">Daily km
                        <input type="number" x-model="dailyKm" min="5" max="300" class="rounded-xl border border-slate-300 px-3 py-2 focus:border-green-500 focus:outline-none">
                    </label>
                    <label class="grid gap-1 text-sm font-semibold">Petrol ₹/litre
                        <input type="number" x-model="petrolCostPerL" min="50" max="200" class="rounded-xl border border-slate-300 px-3 py-2 focus:border-green-500 focus:outline-none">
                    </label>
                    <label class="grid gap-1 text-sm font-semibold">Electricity ₹/kWh
                        <input type="number" x-model="electricity" min="3" max="20" class="rounded-xl border border-slate-300 px-3 py-2 focus:border-green-500 focus:outline-none">
                    </label>
                </div>
            </div>

            <!-- Side-by-side vehicle inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- EV -->
                <div class="rounded-3xl bg-green-50 p-6 ring-1 ring-green-200">
                    <h2 class="font-bold text-green-800 mb-4">⚡ Electric Vehicle</h2>
                    <div class="space-y-3">
                        <label class="grid gap-1 text-sm font-semibold text-green-900">On-road price (₹)
                            <input type="number" x-model="evPrice" class="rounded-xl border border-green-200 bg-white px-3 py-2 focus:border-green-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-green-900">Down payment (₹)
                            <input type="number" x-model="evDown" class="rounded-xl border border-green-200 bg-white px-3 py-2 focus:border-green-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-green-900">Interest rate (% pa)
                            <input type="number" x-model="evRate" step="0.5" class="rounded-xl border border-green-200 bg-white px-3 py-2 focus:border-green-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-green-900">Tenure (months)
                            <input type="number" x-model="evTenure" min="12" max="84" step="12" class="rounded-xl border border-green-200 bg-white px-3 py-2 focus:border-green-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-green-900">EV efficiency (km/kWh)
                            <input type="number" x-model="evEfficiency" step="0.5" class="rounded-xl border border-green-200 bg-white px-3 py-2 focus:border-green-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-green-900">Annual maintenance (₹)
                            <input type="number" x-model="evMaintain" step="500" class="rounded-xl border border-green-200 bg-white px-3 py-2 focus:border-green-500 focus:outline-none">
                        </label>
                    </div>
                </div>
                <!-- Petrol -->
                <div class="rounded-3xl bg-orange-50 p-6 ring-1 ring-orange-200">
                    <h2 class="font-bold text-orange-800 mb-4">⛽ Petrol Vehicle</h2>
                    <div class="space-y-3">
                        <label class="grid gap-1 text-sm font-semibold text-orange-900">On-road price (₹)
                            <input type="number" x-model="petrolPrice" class="rounded-xl border border-orange-200 bg-white px-3 py-2 focus:border-orange-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-orange-900">Down payment (₹)
                            <input type="number" x-model="petrolDown" class="rounded-xl border border-orange-200 bg-white px-3 py-2 focus:border-orange-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-orange-900">Interest rate (% pa)
                            <input type="number" x-model="petrolRate" step="0.5" class="rounded-xl border border-orange-200 bg-white px-3 py-2 focus:border-orange-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-orange-900">Tenure (months)
                            <input type="number" x-model="petrolTenure" min="12" max="84" step="12" class="rounded-xl border border-orange-200 bg-white px-3 py-2 focus:border-orange-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-orange-900">Mileage (km/litre)
                            <input type="number" x-model="petrolMileage" step="1" class="rounded-xl border border-orange-200 bg-white px-3 py-2 focus:border-orange-500 focus:outline-none">
                        </label>
                        <label class="grid gap-1 text-sm font-semibold text-orange-900">Annual maintenance (₹)
                            <input type="number" x-model="petrolMaintain" step="500" class="rounded-xl border border-orange-200 bg-white px-3 py-2 focus:border-orange-500 focus:outline-none">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="font-bold text-slate-800 mb-5">Your 5-Year Cost Comparison</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 font-semibold text-slate-600">Cost Component</th>
                                <th class="text-right py-2 font-semibold text-green-700">⚡ EV</th>
                                <th class="text-right py-2 font-semibold text-orange-700">⛽ Petrol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2">Monthly EMI</td>
                                <td class="text-right text-green-700 font-semibold" x-text="fmt(evEmi)"></td>
                                <td class="text-right text-orange-700 font-semibold" x-text="fmt(petrolEmi)"></td>
                            </tr>
                            <tr>
                                <td class="py-2">Monthly fuel cost</td>
                                <td class="text-right text-green-700" x-text="fmt(evFuelMonthly)"></td>
                                <td class="text-right text-orange-700" x-text="fmt(petrolFuelMonthly)"></td>
                            </tr>
                            <tr>
                                <td class="py-2">Monthly maintenance</td>
                                <td class="text-right text-green-700" x-text="fmt(evMaintain/12)"></td>
                                <td class="text-right text-orange-700" x-text="fmt(petrolMaintain/12)"></td>
                            </tr>
                            <tr class="font-bold border-t-2 border-slate-300">
                                <td class="py-2">Total monthly cost</td>
                                <td class="text-right text-green-700" x-text="fmt(evTotalMonthly)"></td>
                                <td class="text-right text-orange-700" x-text="fmt(petrolTotalMonthly)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Big Savings Number -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl p-4 text-center text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                        <div class="text-xs font-bold uppercase opacity-80">Monthly Saving</div>
                        <div class="mt-1 text-2xl font-black" x-text="fmt(monthlySaving)"></div>
                    </div>
                    <div class="rounded-2xl p-4 text-center text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                        <div class="text-xs font-bold uppercase opacity-80">5-Year Saving</div>
                        <div class="mt-1 text-2xl font-black" x-text="fmt(totalSaving5yr)"></div>
                    </div>
                    <div class="rounded-2xl p-4 text-center text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                        <div class="text-xs font-bold uppercase opacity-80">Break-even</div>
                        <div class="mt-1 text-2xl font-black" x-text="breakEvenMonths < 999 ? breakEvenMonths + ' months' : 'N/A'"></div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="mt-6 lg:mt-0"><?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?></aside>
    </div>
  </div>
  <div class="pb-4 md:pb-0"></div>
</div>
<?= $this->endSection() ?>
