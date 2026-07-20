?<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php /* ============================================================
   EV Running Cost & EMI Calculator – Charj.in EV Marketplace
   CI4 view · Tailwind CSS · Alpine.js
   ============================================================ */ ?>

<style>
[x-cloak]{display:none!important}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:#22C55E;cursor:pointer;border:3px solid #fff;box-shadow:0 0 0 2px #22C55E}
input[type=range]::-webkit-slider-runnable-track{background:linear-gradient(to right,#22C55E var(--pct,50%),#e5e7eb var(--pct,50%));height:6px;border-radius:3px}
.bar-ev    { background: linear-gradient(135deg,#22C55E,#16a34a); }
.bar-petrol{ background: linear-gradient(135deg,#f97316,#ea580c); }
.tab-active{ color:#022C22;border-bottom:3px solid #22C55E;font-weight:700;background:rgba(34,197,94,.06) }
</style>

<div class=�min-h-screen bg-gray-50 pb-4 md:pb-0�>

    <!-- -- Page Header ------------------------------------ -->
    <div class="hero-sm pt-10 sm:pt-20 md:pt-32 pb-10 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
        <div class="max-w-7xl mx-auto">
            <nav class="text-sm mb-3 flex items-center gap-1" style="color:#64748B">
                <a href="<?= base_url('/') ?>" class="hover:text-[#00A896] transition-colors">Home</a>
                <span>›</span>
                <a href="<?= base_url('calculators') ?>" class="hover:text-[#00A896] transition-colors">Calculators</a>
                <span>›</span>
                <span style="color:#0F172A">Cost Calculator</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2" style="color:#0F172A">EV Cost Calculator</h1>
            <p class="text-base" style="color:#475569">Find out how much you save by switching to an electric vehicle</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- ════════════════════════════════════════════════
                 LEFT – CALCULATOR PANEL
            ═════════════════════════════════════════════════ -->
            <div class="flex-1 min-w-0">

                <!-- Tab Switcher -->
                <div x-data="{ activeTab: 'running' }" class="space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Tab nav -->
                        <div class="flex border-b border-gray-100">
                            <button @click="activeTab = 'running'"
                                :class="activeTab === 'running' ? 'tab-active' : 'text-gray-500 hover:text-[#022C22]'"
                                class="flex-1 px-2 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-medium transition-colors">
                                ⚡ Running Cost
                            </button>
                            <button @click="activeTab = 'emi'"
                                :class="activeTab === 'emi' ? 'tab-active' : 'text-gray-500 hover:text-[#022C22]'"
                                class="flex-1 px-2 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-medium transition-colors">
                                🏦 EMI Calculator
                            </button>
                        </div>

                        <!-- ── TAB 1: Running Cost ─────────────── -->
                        <div x-show="activeTab === 'running'"
                             x-data="runningCostCalc()"
                             x-init="calc()"
                             class="p-6">

                            <p class="text-sm text-gray-500 mb-6">
                                Compare your monthly fuel costs: EV electricity vs petrol. Based on Indian averages.
                            </p>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Inputs -->
                                <div class="space-y-5">
                                    <h3 class="font-bold text-[#022C22] text-sm uppercase tracking-wide">Your Usage</h3>

                                    <!-- Daily KM -->
                                    <div>
                                        <div class="flex justify-between mb-1.5">
                                            <label class="text-sm font-medium text-gray-700">Daily Distance</label>
                                            <span class="text-sm font-bold text-[#022C22]" x-text="dailyKm + ' km'"></span>
                                        </div>
                                        <input type="range" x-model.number="dailyKm" @input="calc()" min="5" max="200" step="5"
                                            class="w-full accent-green-500">
                                        <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                            <span>5 km</span><span>200 km</span>
                                        </div>
                                    </div>

                                    <!-- Days per month -->
                                    <div>
                                        <div class="flex justify-between mb-1.5">
                                            <label class="text-sm font-medium text-gray-700">Days per Month</label>
                                            <span class="text-sm font-bold text-[#022C22]" x-text="daysPerMonth + ' days'"></span>
                                        </div>
                                        <input type="range" x-model.number="daysPerMonth" @input="calc()" min="10" max="31" step="1"
                                            class="w-full accent-green-500">
                                        <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                            <span>10</span><span>31</span>
                                        </div>
                                    </div>

                                    <hr class="border-gray-100">
                                    <h3 class="font-bold text-[#022C22] text-sm uppercase tracking-wide">EV Parameters</h3>

                                    <!-- EV efficiency -->
                                    <div>
                                        <div class="flex justify-between mb-1.5">
                                            <label class="text-sm font-medium text-gray-700">EV Efficiency</label>
                                            <span class="text-sm font-bold text-[#022C22]" x-text="evEfficiency + ' km/kWh'"></span>
                                        </div>
                                        <input type="range" x-model.number="evEfficiency" @input="calc()" min="2" max="10" step="0.5"
                                            class="w-full accent-green-500">
                                        <p class="text-xs text-gray-400 mt-0.5">Indian EV average: 4–6 km/kWh</p>
                                    </div>

                                    <!-- Electricity cost -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Electricity Cost (₹/kWh)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">₹</span>
                                            <input type="number" x-model.number="electricityRate" @input="calc()" min="3" max="20" step="0.5"
                                                class="w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00E676] text-sm font-semibold text-[#022C22]">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">Home charging: ₹6–10/kWh. Public: ₹12–18/kWh</p>
                                    </div>

                                    <hr class="border-gray-100">
                                    <h3 class="font-bold text-[#022C22] text-sm uppercase tracking-wide">Petrol Vehicle</h3>

                                    <!-- Petrol cost -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Petrol Price (₹/litre)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">₹</span>
                                            <input type="number" x-model.number="petrolRate" @input="calc()" min="80" max="150" step="1"
                                                class="w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00E676] text-sm font-semibold text-[#022C22]">
                                        </div>
                                    </div>

                                    <!-- Petrol mileage -->
                                    <div>
                                        <div class="flex justify-between mb-1.5">
                                            <label class="text-sm font-medium text-gray-700">Petrol Mileage</label>
                                            <span class="text-sm font-bold text-[#022C22]" x-text="petrolMileage + ' km/L'"></span>
                                        </div>
                                        <input type="range" x-model.number="petrolMileage" @input="calc()" min="10" max="80" step="2"
                                            class="w-full accent-green-500">
                                        <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                            <span>10 km/L</span><span>80 km/L</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Results -->
                                <div class="space-y-4">
                                    <h3 class="font-bold text-[#022C22] text-sm uppercase tracking-wide">Monthly Comparison</h3>

                                    <!-- Per km costs -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center">
                                            <p class="text-xs text-green-600 font-medium mb-1">EV Cost/km</p>
                                            <p class="text-2xl font-black text-[#22C55E]">₹<span x-text="evCostPerKm"></span></p>
                                        </div>
                                        <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 text-center">
                                            <p class="text-xs text-orange-600 font-medium mb-1">Petrol Cost/km</p>
                                            <p class="text-2xl font-black text-orange-500">₹<span x-text="petrolCostPerKm"></span></p>
                                        </div>
                                    </div>

                                    <!-- Monthly costs -->
                                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full bg-[#22C55E] flex-shrink-0"></span>
                                                Monthly EV Cost
                                            </span>
                                            <span class="font-bold text-[#022C22]">₹<span x-text="monthlyEvCost.toLocaleString('en-IN')"></span></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full bg-orange-500 flex-shrink-0"></span>
                                                Monthly Petrol Cost
                                            </span>
                                            <span class="font-bold text-[#022C22]">₹<span x-text="monthlyPetrolCost.toLocaleString('en-IN')"></span></span>
                                        </div>
                                    </div>

                                    <!-- Bar chart -->
                                    <div class="bg-white border border-gray-100 rounded-xl p-4">
                                        <p class="text-xs text-gray-500 font-medium mb-3 uppercase tracking-wide">Visual Comparison</p>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="text-green-700 font-medium">⚡ EV</span>
                                                    <span class="text-green-700 font-bold">₹<span x-text="monthlyEvCost.toLocaleString('en-IN')"></span></span>
                                                </div>
                                                <div class="bg-gray-100 rounded-full h-5 overflow-hidden">
                                                    <div class="bar-ev h-5 rounded-full transition-all duration-700 flex items-center justify-end pr-2"
                                                         :style="'width: ' + Math.round(monthlyEvCost / Math.max(monthlyEvCost, monthlyPetrolCost) * 100) + '%'">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="text-orange-600 font-medium">⛽ Petrol</span>
                                                    <span class="text-orange-600 font-bold">₹<span x-text="monthlyPetrolCost.toLocaleString('en-IN')"></span></span>
                                                </div>
                                                <div class="bg-gray-100 rounded-full h-5 overflow-hidden">
                                                    <div class="bar-petrol h-5 rounded-full transition-all duration-700"
                                                         :style="'width: ' + Math.round(monthlyPetrolCost / Math.max(monthlyEvCost, monthlyPetrolCost) * 100) + '%'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Savings highlight -->
                                    <div class="rounded-2xl p-5 text-white text-center" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                                        <p class="text-xs text-white/70 uppercase tracking-wide mb-1">Monthly Saving with EV</p>
                                        <p class="text-4xl font-black text-white mb-1">
                                            ₹<span x-text="monthlySaving.toLocaleString('en-IN')"></span>
                                        </p>
                                        <p class="text-xs text-white/70 mb-4">vs petrol vehicle</p>
                                        <div class="border-t border-white/20 pt-4">
                                            <p class="text-xs text-white/70 mb-1">Annual Saving</p>
                                            <p class="text-2xl font-bold text-white">
                                                ₹<span x-text="yearlySaving.toLocaleString('en-IN')"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Monthly KM total -->
                                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                                        <p class="text-xs text-blue-600 font-medium">Monthly Distance</p>
                                        <p class="font-bold text-blue-800" x-text="monthlyKm.toLocaleString('en-IN') + ' km'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /running cost tab -->

                        <!-- ── TAB 2: EMI Calculator ────────────── -->
                        <div x-show="activeTab === 'emi'"
                             x-cloak
                             x-data="emiCalcPage()"
                             x-init="calcEmi()"
                             class="p-6">

                            <p class="text-sm text-gray-500 mb-6">
                                Calculate your monthly EMI for financing an electric vehicle in India.
                            </p>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Inputs -->
                                <div class="space-y-5">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Vehicle Price (₹)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm">₹</span>
                                            <input type="number" x-model.number="price" @input="calcEmi()"
                                                class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00E676] text-[#022C22] font-bold">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Down Payment (₹)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm">₹</span>
                                            <input type="number" x-model.number="downPayment" @input="calcEmi()"
                                                class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00E676] text-[#022C22] font-bold">
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                                            <span>Loan Amount:</span>
                                            <span class="font-bold text-[#022C22]">₹<span x-text="Math.max(0,price-downPayment).toLocaleString('en-IN')"></span></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1.5">
                                            <label class="text-sm font-medium text-gray-700">Interest Rate</label>
                                            <span class="text-sm font-bold text-[#022C22]" x-text="rate + '%'"></span>
                                        </div>
                                        <input type="range" x-model.number="rate" @input="calcEmi()" min="6" max="20" step="0.5"
                                            class="w-full accent-green-500">
                                        <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                            <span>6%</span><span>20%</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1.5">
                                            <label class="text-sm font-medium text-gray-700">Loan Tenure</label>
                                            <span class="text-sm font-bold text-[#022C22]" x-text="tenure + ' months'"></span>
                                        </div>
                                        <input type="range" x-model.number="tenure" @input="calcEmi()" min="12" max="84" step="6"
                                            class="w-full accent-green-500">
                                        <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                                            <span>12 mo</span>
                                            <span>36 mo</span>
                                            <span>60 mo</span>
                                            <span>84 mo</span>
                                        </div>
                                        <!-- Preset tenure buttons -->
                                        <div class="flex gap-2 mt-2 flex-wrap">
                                            <?php foreach([12,24,36,48,60,72,84] as $t): ?>
                                            <button type="button" @click="tenure = <?= $t ?>; calcEmi()"
                                                :class="tenure === <?= $t ?> ? 'bg-[#22C55E] text-white border-[#22C55E]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#22C55E]'"
                                                class="border px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors">
                                                <?= $t ?>m
                                            </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Results -->
                                <div class="flex flex-col gap-4">
                                    <!-- EMI display -->
                                    <div class="rounded-2xl p-6 text-white text-center" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                                        <p class="text-xs text-white/70 uppercase tracking-wide mb-1">Monthly EMI</p>
                                        <p class="text-5xl font-black text-white mb-1">
                                            ₹<span x-text="emi.toLocaleString('en-IN')"></span>
                                        </p>
                                        <p class="text-xs text-white/70">for <span x-text="tenure"></span> months @ <span x-text="rate"></span>%</p>
                                    </div>

                                    <!-- Breakdown -->
                                    <div class="bg-white border border-gray-100 rounded-2xl p-5 space-y-3">
                                        <div class="flex justify-between py-2 border-b border-gray-50">
                                            <span class="text-sm text-gray-600">Down Payment</span>
                                            <span class="font-bold text-[#022C22]">₹<span x-text="downPayment.toLocaleString('en-IN')"></span></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-50">
                                            <span class="text-sm text-gray-600">Loan Amount</span>
                                            <span class="font-bold text-[#022C22]">₹<span x-text="Math.max(0,price-downPayment).toLocaleString('en-IN')"></span></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-50">
                                            <span class="text-sm text-gray-600">Total Interest</span>
                                            <span class="font-bold text-orange-500">₹<span x-text="totalInterest.toLocaleString('en-IN')"></span></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-50">
                                            <span class="text-sm text-gray-600">Total EMI Payment</span>
                                            <span class="font-bold text-[#022C22]">₹<span x-text="(emi * tenure).toLocaleString('en-IN')"></span></span>
                                        </div>
                                        <div class="flex justify-between py-2 bg-gray-50 rounded-lg px-2">
                                            <span class="text-sm font-bold text-[#022C22]">Total Cost</span>
                                            <span class="font-black text-[#022C22]">₹<span x-text="totalCost.toLocaleString('en-IN')"></span></span>
                                        </div>
                                    </div>

                                    <!-- Visual bar -->
                                    <div class="bg-white border border-gray-100 rounded-xl p-4">
                                        <p class="text-xs text-gray-500 mb-3 uppercase tracking-wide font-medium">Cost Breakdown</p>
                                        <div class="space-y-2">
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="text-gray-600">Principal</span>
                                                    <span class="font-semibold text-[#022C22]">
                                                        <span x-text="price > 0 ? Math.round((price-downPayment)/price*100) : 0"></span>%
                                                    </span>
                                                </div>
                                                <div class="bg-gray-100 rounded-full h-4 overflow-hidden">
                                                    <div class="h-4 rounded-full transition-all duration-500" style="background:#00A896"
                                                         :style="'width:' + (price > 0 ? Math.round((price-downPayment)/totalCost*100) : 0) + '%'"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="text-gray-600">Interest</span>
                                                    <span class="font-semibold text-orange-500">
                                                        <span x-text="totalCost > 0 ? Math.round(totalInterest/totalCost*100) : 0"></span>%
                                                    </span>
                                                </div>
                                                <div class="bg-gray-100 rounded-full h-4 overflow-hidden">
                                                    <div class="bar-petrol h-4 rounded-full transition-all duration-500"
                                                         :style="'width:' + (totalCost > 0 ? Math.round(totalInterest/totalCost*100) : 0) + '%'"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="<?= base_url('finance') ?>"
                                       class="block w-full text-center bg-[#22C55E] text-white py-3.5 rounded-xl font-bold hover:bg-green-700 transition-colors">
                                        Apply for EV Finance →
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /EMI tab -->

                    </div>

                    <!-- CTA card below calculator -->
                    <div class="rounded-2xl p-6 text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                            <div class="text-4xl flex-shrink-0">💡</div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg mb-1">Ready to Switch to EV?</h3>
                                <p class="text-white/80 text-sm">
                                    Browse 300+ EVs on Charj.in. Compare prices, range, and get the best deal in your city.
                                </p>
                            </div>
                            <div class="flex gap-3 flex-shrink-0 flex-wrap">
                                <a href="<?= base_url('vehicles') ?>"
                                   class="bg-[#22C55E] text-white px-5 py-2.5 rounded-xl font-bold hover:bg-green-700 transition-colors text-sm whitespace-nowrap">
                                    Browse EVs →
                                </a>
                                <a href="<?= base_url('find-my-ev') ?>"
                                   class="bg-white/10 border border-white/20 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-white/20 transition-colors text-sm whitespace-nowrap">
                                    Find My EV
                                </a>
                            </div>
                        </div>
                    </div>

                </div><!-- /x-data activeTab -->
            </div>

            <!-- ════════════════════════════════════════════════
                 RIGHT – LEAD FORM
            ═════════════════════════════════════════════════ -->
            <aside class="lg:w-[340px] xl:w-[360px] flex-shrink-0">
                <div class="sticky top-4 space-y-4">
                    <!-- Lead form -->
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                            <h3 class="font-bold text-lg">Get Personalised Help</h3>
                            <p class="text-white/80 text-xs mt-0.5">Our EV experts will call you back</p>
                        </div>
                        <?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?>
                    </div>

                    <!-- Info cards -->
                    <div class="bg-[#22C55E]/10 border border-[#22C55E]/20 rounded-2xl p-5">
                        <h4 class="font-bold text-[#022C22] mb-3 flex items-center gap-2">
                            <span>🇮🇳</span> India EV Benefits
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start gap-2">
                                <span class="text-[#22C55E] font-bold mt-0.5 flex-shrink-0">✓</span>
                                FAME II subsidy up to ₹1.5 lakh
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#22C55E] font-bold mt-0.5 flex-shrink-0">✓</span>
                                Zero road tax in most states
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#22C55E] font-bold mt-0.5 flex-shrink-0">✓</span>
                                Income tax deduction u/s 80EEB
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#22C55E] font-bold mt-0.5 flex-shrink-0">✓</span>
                                Low maintenance: no oil changes
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#22C55E] font-bold mt-0.5 flex-shrink-0">✓</span>
                                Reduced parking charges in cities
                            </li>
                        </ul>
                    </div>

                    <!-- Charging cost tip -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-5">
                        <h4 class="font-bold text-[#022C22] mb-2 text-sm">💡 Electricity Rate Guide</h4>
                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex justify-between py-1.5 border-b border-gray-50">
                                <span>Home charging (residential)</span>
                                <span class="font-bold text-[#022C22]">₹6–10/kWh</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-gray-50">
                                <span>Public AC charger</span>
                                <span class="font-bold text-[#022C22]">₹12–16/kWh</span>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <span>DC fast charger</span>
                                <span class="font-bold text-[#022C22]">₹15–20/kWh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>

<script>
function runningCostCalc() {
    return {
        dailyKm:         40,
        daysPerMonth:    26,
        evEfficiency:    5,
        electricityRate: 8,
        petrolRate:      105,
        petrolMileage:   40,

        // Computed results
        evCostPerKm:      '0.00',
        petrolCostPerKm:  '0.00',
        monthlyKm:        0,
        monthlyEvCost:    0,
        monthlyPetrolCost:0,
        monthlySaving:    0,
        yearlySaving:     0,

        calc() {
            const evCpk        = this.evEfficiency > 0 ? this.electricityRate / this.evEfficiency : 0;
            const petCpk       = this.petrolMileage > 0 ? this.petrolRate / this.petrolMileage : 0;
            this.evCostPerKm   = evCpk.toFixed(2);
            this.petrolCostPerKm = petCpk.toFixed(2);

            const km            = this.dailyKm * this.daysPerMonth;
            this.monthlyKm      = km;
            this.monthlyEvCost  = Math.round(evCpk * km);
            this.monthlyPetrolCost = Math.round(petCpk * km);
            this.monthlySaving  = Math.max(0, this.monthlyPetrolCost - this.monthlyEvCost);
            this.yearlySaving   = this.monthlySaving * 12;
        }
    }
}

function emiCalcPage() {
    return {
        price:        800000,
        downPayment:  160000,
        rate:         9,
        tenure:       36,
        emi:          0,
        totalInterest:0,
        totalCost:    0,

        calcEmi() {
            const principal = Math.max(0, this.price - this.downPayment);
            const r = (this.rate / 100) / 12;
            const n = this.tenure;
            if (r === 0 || n === 0) {
                this.emi = n > 0 ? Math.round(principal / n) : 0;
            } else {
                this.emi = Math.round(principal * r * Math.pow(1+r,n) / (Math.pow(1+r,n) - 1));
            }
            this.totalInterest = Math.max(0, Math.round(this.emi * n - principal));
            this.totalCost     = Math.round(this.downPayment + principal + this.totalInterest);
        }
    }
}
</script>

<?= $this->endSection() ?>
