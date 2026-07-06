<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php /* ============================================================
   "Find My EV" Quiz Page – Charj.in EV Marketplace
   CI4 view · Tailwind CSS · Alpine.js
   Multi-step quiz, no page reload. AJAX result display.
   ============================================================ */ ?>

<style>
[x-cloak]{ display:none!important }
.step-card { transition: all .35s cubic-bezier(.4,0,.2,1); }
.option-btn { transition: all .2s ease; }
.option-btn.selected,
.option-btn:focus { outline:none; }
.progress-bar { transition: width .5s ease; }
.quiz-enter { animation: fadeSlideIn .35s ease; }
@keyframes fadeSlideIn {
    from { opacity:0; transform: translateY(12px); }
    to   { opacity:1; transform: translateY(0); }
}
.result-card { animation: fadeSlideIn .4s ease both; }
</style>

<div x-data="findMyEv()" x-init="init()" class="min-h-screen bg-gray-50">

    <!-- ── Page Header ─────────────────────────────────────────── -->
    <div class="bg-[#0D2137] text-white py-10 px-4">
        <div class="max-w-4xl mx-auto">
            <nav class="text-sm mb-3 text-gray-400 flex items-center gap-1">
                <a href="<?= base_url('/') ?>" class="hover:text-[#22C55E] transition-colors">Home</a>
                <span>›</span>
                <span class="text-white">Find My EV</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Find Your Perfect EV</h1>
            <p class="text-gray-300 text-base">Answer 5 quick questions and we'll match you with the best electric vehicles for your needs</p>
        </div>
    </div>

    <!-- ── Quiz Container ──────────────────────────────────────── -->
    <div class="max-w-4xl mx-auto px-4 py-8">

        <!-- Results view -->
        <div x-show="showResults" x-cloak class="quiz-enter">
            <!-- Loading state -->
            <div x-show="loading" class="text-center py-20">
                <div class="w-16 h-16 border-4 border-[#22C55E] border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-[#0D2137] font-semibold text-lg">Finding your perfect EVs...</p>
                <p class="text-gray-500 text-sm mt-1">Analysing your requirements</p>
            </div>

            <!-- Results -->
            <div x-show="!loading" x-cloak>
                <div class="text-center mb-8">
                    <div class="inline-flex items-center gap-2 bg-[#22C55E]/10 text-[#22C55E] border border-[#22C55E]/20 rounded-full px-5 py-2 text-sm font-bold mb-4">
                        ✓ Analysis Complete
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-[#0D2137] mb-2">
                        Based on your requirements, here are our top picks
                    </h2>
                    <p class="text-gray-500">Matched to your budget, usage, and charging situation</p>
                </div>

                <!-- Result cards -->
                <div class="grid md:grid-cols-3 gap-5 mb-8">
                    <template x-for="(vehicle, idx) in results" :key="vehicle.id">
                        <div class="result-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all"
                             :style="'animation-delay:' + (idx * 0.1) + 's'">
                            <!-- Match badge -->
                            <div class="relative">
                                <div x-show="vehicle.image" class="bg-gray-50 p-4">
                                    <img :src="vehicle.image" :alt="vehicle.name" class="w-full h-36 object-contain">
                                </div>
                                <div x-show="!vehicle.image" class="bg-gray-100 h-36 flex items-center justify-center text-5xl">🔋</div>
                                <div class="absolute top-3 right-3">
                                    <div :class="idx === 0 ? 'bg-[#22C55E]' : idx === 1 ? 'bg-blue-500' : 'bg-purple-500'"
                                         class="text-white text-xs font-black px-2.5 py-1.5 rounded-full shadow-lg">
                                        <span x-text="vehicle.match_score || (95 - idx*8)"></span>% Match
                                    </div>
                                </div>
                                <template x-if="idx === 0">
                                    <div class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 text-xs font-black px-2 py-1 rounded-full">
                                        🏆 Best Pick
                                    </div>
                                </template>
                            </div>

                            <div class="p-5">
                                <p class="text-xs text-gray-400 mb-0.5" x-text="vehicle.brand_name || ''"></p>
                                <h3 class="font-extrabold text-[#0D2137] text-lg leading-tight mb-1" x-text="vehicle.name"></h3>
                                <p class="text-[#22C55E] font-bold text-lg mb-3">
                                    ₹<span x-text="Number(vehicle.starting_price).toLocaleString('en-IN')"></span>
                                </p>

                                <!-- Key reason -->
                                <div class="bg-green-50 border border-green-100 rounded-xl p-3 mb-4">
                                    <p class="text-xs text-green-700 font-semibold mb-0.5">Why this EV?</p>
                                    <p class="text-xs text-green-600" x-text="vehicle.match_reason || 'Great match for your requirements'"></p>
                                </div>

                                <!-- Quick specs -->
                                <div class="grid grid-cols-2 gap-2 mb-4 text-xs">
                                    <div class="bg-gray-50 rounded-lg p-2 text-center" x-show="vehicle.real_world_range">
                                        <p class="text-gray-400">Range</p>
                                        <p class="font-bold text-[#0D2137]" x-text="vehicle.real_world_range + ' km'"></p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-2 text-center" x-show="vehicle.battery_capacity">
                                        <p class="text-gray-400">Battery</p>
                                        <p class="font-bold text-[#0D2137]" x-text="vehicle.battery_capacity + ' kWh'"></p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <a :href="'/vehicles/' + (vehicle.slug || vehicle.id)"
                                       class="block w-full text-center bg-[#0D2137] text-white py-2.5 rounded-xl font-bold hover:bg-[#091929] transition-colors text-sm">
                                        View Full Details
                                    </a>
                                    <a :href="'/get-price/' + (vehicle.slug || vehicle.id)"
                                       class="block w-full text-center bg-[#22C55E] text-white py-2.5 rounded-xl font-bold hover:bg-green-700 transition-colors text-sm">
                                        Get Best Price →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Restart / Browse -->
                <div class="text-center space-y-3">
                    <p class="text-gray-500 text-sm">Not satisfied? Retake the quiz or browse all EVs</p>
                    <div class="flex flex-wrap gap-3 justify-center">
                        <button @click="restart()"
                            class="bg-white border-2 border-[#0D2137] text-[#0D2137] px-6 py-3 rounded-xl font-bold hover:bg-[#0D2137] hover:text-white transition-colors">
                            ↺ Retake Quiz
                        </button>
                        <a href="<?= base_url('vehicles') ?>"
                           class="bg-[#0D2137] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#091929] transition-colors">
                            Browse All EVs →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz view -->
        <div x-show="!showResults" class="quiz-enter">

            <!-- ── Progress Bar ─────────────────────────────── -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-[#0D2137]">
                        Step <span x-text="currentStep"></span> of <?= 6 ?>
                    </span>
                    <span class="text-sm text-gray-400" x-text="stepTitles[currentStep-1] || ''"></span>
                </div>
                <!-- Steps dot indicators -->
                <div class="flex gap-2 mb-3">
                    <?php for($s=1; $s<=6; $s++): ?>
                    <div :class="currentStep >= <?= $s ?> ? 'bg-[#22C55E]' : 'bg-gray-200'"
                         class="flex-1 h-2 rounded-full transition-all duration-500"></div>
                    <?php endfor; ?>
                </div>
                <div class="flex justify-between">
                    <?php for($s=1; $s<=6; $s++): ?>
                    <div :class="currentStep >= <?= $s ?> ? 'text-[#22C55E] font-bold' : 'text-gray-300'"
                         class="text-xs text-center" style="width:16.66%"><?= $s ?></div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- ── STEP 1: Use case ─────────────────────────── -->
            <div x-show="currentStep === 1" class="step-card">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-extrabold text-[#0D2137] mb-2">Who is this EV for?</h2>
                    <p class="text-gray-500 text-sm mb-6">This helps us understand your primary use case.</p>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <?php
                        $useCases = [
                            ['value'=>'personal_commute', 'icon'=>'🏙', 'label'=>'Personal Daily Commute',    'desc'=>'Office or college, daily city travel'],
                            ['value'=>'personal_family',  'icon'=>'👨‍👩‍👧', 'label'=>'Personal Family Use',       'desc'=>'Weekend trips, school runs, outings'],
                            ['value'=>'business',         'icon'=>'💼', 'label'=>'Business / Commercial',     'desc'=>'Deliveries, cab, business travel'],
                            ['value'=>'fleet',            'icon'=>'🚌', 'label'=>'Fleet (5+ vehicles)',        'desc'=>'Corporate fleet, logistics'],
                        ];
                        foreach($useCases as $uc):
                        ?>
                        <button @click="selectOption('useCase', '<?= $uc['value'] ?>'); nextStep()"
                            :class="answers.useCase === '<?= $uc['value'] ?>' ? 'border-[#22C55E] bg-green-50 shadow-md' : 'border-gray-100 hover:border-[#0D2137] hover:shadow-sm'"
                            class="option-btn border-2 rounded-2xl p-5 text-left transition-all">
                            <span class="text-3xl block mb-2"><?= $uc['icon'] ?></span>
                            <p class="font-bold text-[#0D2137] text-sm mb-0.5"><?= $uc['label'] ?></p>
                            <p class="text-gray-400 text-xs"><?= $uc['desc'] ?></p>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ── STEP 2: Budget ───────────────────────────── -->
            <div x-show="currentStep === 2" x-cloak class="step-card">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-extrabold text-[#0D2137] mb-2">What's your budget?</h2>
                    <p class="text-gray-500 text-sm mb-4">Select the on-road price range you're comfortable with.</p>

                    <!-- Slider -->
                    <div x-data="{ budgetVal: answers.budgetMax || 500000 }" class="mb-6 bg-gray-50 rounded-2xl p-5">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Budget (up to)</span>
                            <span class="text-xl font-black text-[#0D2137]">
                                ₹<span x-text="formatBudget(budgetVal)"></span>
                            </span>
                        </div>
                        <input type="range" x-model.number="budgetVal"
                            @input="answers.budgetMax = budgetVal"
                            min="50000" max="5000000" step="50000"
                            class="w-full accent-green-500 mb-3">
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>₹50K</span><span>₹50L+</span>
                        </div>
                    </div>

                    <!-- Quick budget buttons -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mb-6">
                        <?php
                        $budgets = [
                            ['label'=>'Under ₹1L',  'max'=>100000,  'value'=>'under_1l'],
                            ['label'=>'₹1–2 Lakh',  'max'=>200000,  'value'=>'1_2l'],
                            ['label'=>'₹2–5 Lakh',  'max'=>500000,  'value'=>'2_5l'],
                            ['label'=>'₹5–15 Lakh', 'max'=>1500000, 'value'=>'5_15l'],
                            ['label'=>'Above ₹15L', 'max'=>10000000,'value'=>'above_15l'],
                        ];
                        foreach($budgets as $b):
                        ?>
                        <button @click="selectOption('budget', '<?= $b['value'] ?>'); answers.budgetMax = <?= $b['max'] ?>"
                            :class="answers.budget === '<?= $b['value'] ?>' ? 'border-[#22C55E] bg-green-50 text-[#22C55E] font-black' : 'border-gray-200 text-gray-600 hover:border-[#0D2137] hover:text-[#0D2137]'"
                            class="option-btn border-2 rounded-xl py-3 px-2 text-xs font-semibold text-center transition-all">
                            <?= $b['label'] ?>
                        </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button @click="prevStep()" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm">
                            ← Back
                        </button>
                        <button @click="nextStep()" :disabled="!answers.budget"
                            :class="answers.budget ? 'bg-[#0D2137] text-white hover:bg-[#091929]' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="flex-1 py-3 rounded-xl font-bold transition-colors text-sm">
                            Next →
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── STEP 3: Daily Distance ───────────────────── -->
            <div x-show="currentStep === 3" x-cloak class="step-card">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-extrabold text-[#0D2137] mb-2">How far do you travel daily?</h2>
                    <p class="text-gray-500 text-sm mb-6">
                        This determines the minimum range you need. The EV should comfortably cover your daily trip on a single charge.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-3 mb-6">
                        <?php
                        $distances = [
                            ['value'=>'under_30',  'label'=>'Under 30 km',  'icon'=>'🏘', 'desc'=>'Short city commute','min_range'=>60],
                            ['value'=>'30_60',     'label'=>'30–60 km',     'icon'=>'🏙', 'desc'=>'Medium daily drive','min_range'=>100],
                            ['value'=>'60_100',    'label'=>'60–100 km',    'icon'=>'🛣', 'desc'=>'Long daily commute','min_range'=>150],
                            ['value'=>'above_100', 'label'=>'Above 100 km', 'icon'=>'🌐', 'desc'=>'Highway / outstation','min_range'=>200],
                        ];
                        foreach($distances as $d):
                        ?>
                        <button @click="selectOption('dailyDistance', '<?= $d['value'] ?>'); answers.minRange = <?= $d['min_range'] ?>"
                            :class="answers.dailyDistance === '<?= $d['value'] ?>' ? 'border-[#22C55E] bg-green-50 shadow-md' : 'border-gray-100 hover:border-[#0D2137] hover:shadow-sm'"
                            class="option-btn border-2 rounded-2xl p-5 text-left transition-all">
                            <span class="text-3xl block mb-2"><?= $d['icon'] ?></span>
                            <p class="font-bold text-[#0D2137] text-sm mb-0.5"><?= $d['label'] ?></p>
                            <p class="text-gray-400 text-xs"><?= $d['desc'] ?></p>
                            <span class="text-[11px] text-[#22C55E] font-semibold mt-1 block">
                                Min. range needed: <?= $d['min_range'] ?>+ km
                            </span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex gap-3">
                        <button @click="prevStep()" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm">← Back</button>
                        <button @click="nextStep()" :disabled="!answers.dailyDistance"
                            :class="answers.dailyDistance ? 'bg-[#0D2137] text-white hover:bg-[#091929]' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="flex-1 py-3 rounded-xl font-bold transition-colors text-sm">Next →</button>
                    </div>
                </div>
            </div>

            <!-- ── STEP 4: EV Type ──────────────────────────── -->
            <div x-show="currentStep === 4" x-cloak class="step-card">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-extrabold text-[#0D2137] mb-2">What type of EV are you looking for?</h2>
                    <p class="text-gray-500 text-sm mb-6">Choose the vehicle category that suits your lifestyle.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                        <?php
                        $evTypes = [
                            ['value'=>'scooter',         'icon'=>'🛵', 'label'=>'Electric Scooter', 'desc'=>'City commute, easy parking'],
                            ['value'=>'bike',            'icon'=>'🏍', 'label'=>'Electric Bike',    'desc'=>'Speed, sporty, range'],
                            ['value'=>'car',             'icon'=>'🚗', 'label'=>'Electric Car',     'desc'=>'Family, comfort, highway'],
                            ['value'=>'rickshaw_loader', 'icon'=>'🛺', 'label'=>'Rickshaw/Loader',  'desc'=>'Commercial, last-mile'],
                            ['value'=>'dont_know',       'icon'=>'🤔', 'label'=>'Not Sure Yet',     'desc'=>'Show me all options'],
                        ];
                        foreach($evTypes as $et):
                        ?>
                        <button @click="selectOption('evType', '<?= $et['value'] ?>')"
                            :class="answers.evType === '<?= $et['value'] ?>' ? 'border-[#22C55E] bg-green-50 shadow-md ring-2 ring-[#22C55E]/20' : 'border-gray-100 hover:border-[#0D2137] hover:shadow-sm'"
                            class="option-btn border-2 rounded-2xl p-5 text-center transition-all">
                            <span class="text-4xl block mb-2"><?= $et['icon'] ?></span>
                            <p class="font-bold text-[#0D2137] text-sm mb-0.5"><?= $et['label'] ?></p>
                            <p class="text-gray-400 text-xs"><?= $et['desc'] ?></p>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex gap-3">
                        <button @click="prevStep()" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm">← Back</button>
                        <button @click="nextStep()" :disabled="!answers.evType"
                            :class="answers.evType ? 'bg-[#0D2137] text-white hover:bg-[#091929]' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="flex-1 py-3 rounded-xl font-bold transition-colors text-sm">Next →</button>
                    </div>
                </div>
            </div>

            <!-- ── STEP 5: Charging ─────────────────────────── -->
            <div x-show="currentStep === 5" x-cloak class="step-card">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-extrabold text-[#0D2137] mb-2">What's your charging situation?</h2>
                    <p class="text-gray-500 text-sm mb-6">This helps us recommend EVs with the right charging capability.</p>
                    <div class="grid sm:grid-cols-3 gap-3 mb-6">
                        <?php
                        $chargingOptions = [
                            ['value'=>'home',   'icon'=>'🏠', 'label'=>'Home Charging',           'desc'=>'I have a parking spot & can install a charger at home'],
                            ['value'=>'public', 'icon'=>'🔌', 'label'=>'Public Charging Only',     'desc'=>'I rely on public charging stations near home/office'],
                            ['value'=>'both',   'icon'=>'⚡', 'label'=>'Both Home & Public',       'desc'=>'I have home charging and use public chargers on the go'],
                        ];
                        foreach($chargingOptions as $co):
                        ?>
                        <button @click="selectOption('charging', '<?= $co['value'] ?>')"
                            :class="answers.charging === '<?= $co['value'] ?>' ? 'border-[#22C55E] bg-green-50 shadow-md ring-2 ring-[#22C55E]/20' : 'border-gray-100 hover:border-[#0D2137] hover:shadow-sm'"
                            class="option-btn border-2 rounded-2xl p-5 text-center transition-all">
                            <span class="text-4xl block mb-3"><?= $co['icon'] ?></span>
                            <p class="font-bold text-[#0D2137] text-sm mb-1"><?= $co['label'] ?></p>
                            <p class="text-gray-400 text-xs leading-relaxed"><?= $co['desc'] ?></p>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex gap-3">
                        <button @click="prevStep()" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm">← Back</button>
                        <button @click="nextStep()" :disabled="!answers.charging"
                            :class="answers.charging ? 'bg-[#0D2137] text-white hover:bg-[#091929]' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="flex-1 py-3 rounded-xl font-bold transition-colors text-sm">
                            Next →
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── STEP 6: Contact (Optional) ──────────────── -->
            <div x-show="currentStep === 6" x-cloak class="step-card">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="flex items-start gap-3 mb-6">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#0D2137] mb-1">Get Recommendations on WhatsApp</h2>
                            <p class="text-gray-500 text-sm">
                                Optional — skip if you prefer to see results directly.
                                Your details help us send personalised EV picks and dealer offers.
                            </p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Your Name</label>
                            <input type="text" x-model="contact.name" placeholder="Rahul Sharma"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#22C55E] text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Mobile Number *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">+91</span>
                                <input type="tel" x-model="contact.mobile" placeholder="9876543210"
                                    maxlength="10" minlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric"
                                    @input="contact.mobile = $event.target.value = $event.target.value.replace(/\D/g,'').slice(0,10)"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#22C55E] text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Email (optional)</label>
                            <input type="email" x-model="contact.email" placeholder="rahul@email.com"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#22C55E] text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Your City</label>
                            <input type="text" x-model="contact.city" placeholder="Delhi, Mumbai, Pune..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#22C55E] text-sm">
                        </div>
                    </div>

                    <div class="flex items-start gap-2 mb-6 p-3 bg-green-50 rounded-xl border border-green-100">
                        <span class="text-lg flex-shrink-0">📱</span>
                        <p class="text-xs text-green-700">
                            <strong>Get recommendations sent to your phone.</strong> We'll send your top EV matches on WhatsApp along with dealer offers and test ride booking links in your city.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button @click="prevStep()" class="flex-shrink-0 bg-gray-100 text-gray-600 px-5 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm">
                            ← Back
                        </button>
                        <button @click="submitQuiz()"
                            class="flex-1 bg-[#22C55E] text-white py-3 rounded-xl font-bold hover:bg-green-700 transition-colors text-sm flex items-center justify-center gap-2">
                            <template x-if="!loading">
                                <span>🔍 Find My Perfect EV</span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Analysing...
                                </span>
                            </template>
                        </button>
                        <button @click="submitQuiz(true)"
                            class="flex-shrink-0 border border-gray-200 text-gray-500 px-4 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors text-sm">
                            Skip →
                        </button>
                    </div>
                </div>
            </div>

        </div><!-- /quiz view -->

    </div>
</div>

<script>
function findMyEv() {
    return {
        currentStep:  1,
        totalSteps:   6,
        showResults:  false,
        loading:      false,
        results:      [],
        errors:       {},

        stepTitles: [
            'Your Use Case',
            'Budget',
            'Daily Distance',
            'EV Type',
            'Charging',
            'Contact (Optional)'
        ],

        answers: {
            useCase:       null,
            budget:        null,
            budgetMax:     500000,
            dailyDistance: null,
            minRange:      100,
            evType:        null,
            charging:      null,
        },

        contact: {
            name:   '',
            mobile: '',
            email:  '',
            city:   '',
        },

        init() {
            // Restore from session if possible
            try {
                const s = sessionStorage.getItem('findMyEv_answers');
                if (s) this.answers = { ...this.answers, ...JSON.parse(s) };
            } catch(e) {}
        },

        selectOption(key, value) {
            this.answers[key] = value;
            sessionStorage.setItem('findMyEv_answers', JSON.stringify(this.answers));
        },

        nextStep() {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        async submitQuiz(skipContact = false) {
            this.loading = true;
            const payload = {
                ...this.answers,
                contact: skipContact ? null : this.contact,
            };

            try {
                const res = await fetch('/api/recommendation', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body:    JSON.stringify(payload),
                });
                const data = await res.json();

                if (data.vehicles && data.vehicles.length > 0) {
                    this.results     = data.vehicles.slice(0, 3);
                    this.showResults = true;
                } else {
                    // Fallback: redirect to filtered listing
                    const params = new URLSearchParams();
                    if (this.answers.budgetMax) params.set('price_max', this.answers.budgetMax);
                    if (this.answers.minRange)  params.set('range_min', this.answers.minRange);
                    if (this.answers.evType && this.answers.evType !== 'dont_know')
                        params.set('category', this.answers.evType);
                    window.location = `/vehicles?${params.toString()}`;
                }
            } catch(e) {
                // Graceful fallback on API failure
                const params = new URLSearchParams();
                if (this.answers.budgetMax) params.set('price_max', this.answers.budgetMax);
                if (this.answers.minRange)  params.set('range_min', this.answers.minRange);
                if (this.answers.evType && this.answers.evType !== 'dont_know')
                    params.set('category', this.answers.evType);
                window.location = `/vehicles?${params.toString()}`;
            } finally {
                this.loading = false;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        restart() {
            this.currentStep  = 1;
            this.showResults  = false;
            this.results      = [];
            this.answers      = {
                useCase: null, budget: null, budgetMax: 500000,
                dailyDistance: null, minRange: 100, evType: null, charging: null,
            };
            this.contact = { name:'', mobile:'', email:'', city:'' };
            sessionStorage.removeItem('findMyEv_answers');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        formatBudget(val) {
            if (val >= 10000000) return '50L+';
            if (val >= 100000) return (val/100000).toFixed(val % 100000 === 0 ? 0 : 1) + 'L';
            return (val/1000) + 'K';
        }
    }
}
</script>

<?= $this->endSection() ?>
