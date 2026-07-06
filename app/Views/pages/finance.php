<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<section class="bg-gradient-to-br from-slate-900 to-emerald-900 py-14 text-white">
    <div class="mx-auto max-w-7xl px-4">
        <div class="max-w-2xl">
            <span class="inline-block rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">Finance Guide</span>
            <h1 class="mt-4 text-3xl font-black md:text-4xl">EV Finance in India</h1>
            <p class="mt-3 text-base text-slate-300">
                Everything you need to know about financing your electric vehicle — from bank loans and NBFC options
                to FAME II subsidies and state incentives.
            </p>
            <a href="#lead-form"
               class="mt-6 inline-block rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white transition hover:bg-emerald-400">
                Get Finance Help →
            </a>
        </div>
    </div>
</section>

<div class="mx-auto max-w-7xl px-4 py-12 pb-24">
    <div class="grid gap-10 lg:grid-cols-3">

        <!-- Main content -->
        <div class="space-y-10 lg:col-span-2">

            <!-- Benefits of EV financing -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8">
                <h2 class="text-xl font-black text-slate-900">Why Finance Your EV?</h2>
                <p class="mt-2 text-sm text-slate-600">EV financing often makes more financial sense than paying cash upfront.</p>
                <ul class="mt-5 space-y-3">
                    <?php
                    $benefits = [
                        ['title' => 'Lower interest rates than petrol vehicles', 'desc' => 'Most banks offer EV loans at 0.25–0.5% lower interest rates compared to conventional vehicle loans.'],
                        ['title' => 'FAME II subsidy reduces effective purchase price', 'desc' => 'FAME II subsidies of up to ₹1.5 lakh for 2-wheelers and ₹5 lakh for buses reduce your loan principal.'],
                        ['title' => 'Income tax benefit under Section 80EEB', 'desc' => 'Deduction of up to ₹1.5 lakh per year on interest paid on EV loans for individuals.'],
                        ['title' => 'Longer loan tenures reduce monthly EMI', 'desc' => 'EV loans with 5–7 year tenures make high-range vehicles affordable at modest monthly outgo.'],
                        ['title' => 'Lower running cost offsets EMI', 'desc' => 'With fuel costs 4–6x lower for EVs, the monthly savings often cover a significant portion of your EMI.'],
                        ['title' => 'State subsidies and road tax exemptions', 'desc' => 'Several states offer additional subsidies, waiver of road tax and registration fees for EVs.'],
                    ];
                    foreach ($benefits as $b):
                    ?>
                    <li class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900"><?= $b['title'] ?></p>
                            <p class="text-xs text-slate-500"><?= $b['desc'] ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- How to apply -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8">
                <h2 class="text-xl font-black text-slate-900">How to Apply for EV Loan</h2>
                <div class="mt-6 space-y-5">
                    <?php
                    $steps = [
                        ['num'=>'1','title'=>'Choose your EV and get a dealer quote','desc'=>'Shortlist the EV you want and get an on-road price from an authorised dealer. This becomes the loan amount basis.'],
                        ['num'=>'2','title'=>'Check your loan eligibility','desc'=>'Most banks require 6 months of salary slips or 2 years ITR, a valid driving licence and KYC documents. CIBIL score above 700 gets the best rates.'],
                        ['num'=>'3','title'=>'Compare lenders and get pre-approved','desc'=>'Compare interest rates, processing fees and foreclosure charges across banks and NBFCs. Apply for pre-approval to speed up the purchase.'],
                        ['num'=>'4','title'=>'Submit FAME II subsidy claim via dealer','desc'=>'Your EV dealer handles FAME II subsidy paperwork directly with the government. You get the vehicle at the post-subsidy price.'],
                        ['num'=>'5','title'=>'Loan disbursal and vehicle delivery','desc'=>'Once loan is approved, the bank pays the dealer directly. You take delivery and start your EV journey.'],
                    ];
                    foreach ($steps as $step):
                    ?>
                    <div class="flex gap-4">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-sm font-black text-white">
                            <?= $step['num'] ?>
                        </div>
                        <div class="pt-1">
                            <p class="text-sm font-bold text-slate-900"><?= $step['title'] ?></p>
                            <p class="mt-0.5 text-xs text-slate-500"><?= $step['desc'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Finance partners -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8">
                <h2 class="text-xl font-black text-slate-900">EV Loan Providers</h2>
                <p class="mt-1 text-sm text-slate-500">Banks and NBFCs offering competitive EV financing in India</p>

                <?php
                $financeOptions = $financeOptions ?? [
                    ['name'=>'SBI Green Car Loan','type'=>'Bank','rate'=>'8.65% – 9.45%','max_tenure'=>'7 years','note'=>'Lowest rates for government employees. Special EV scheme.'],
                    ['name'=>'HDFC Bank EV Loan','type'=>'Bank','rate'=>'8.80% – 10.50%','max_tenure'=>'7 years','note'=>'Quick approval, doorstep service, no foreclosure charges after 12 months.'],
                    ['name'=>'ICICI Bank','type'=>'Bank','rate'=>'8.75% – 10.25%','max_tenure'=>'7 years','note'=>'Pre-approved offers for existing customers. EV-specific schemes.'],
                    ['name'=>'Tata Capital','type'=>'NBFC','rate'=>'9.25% – 12.00%','max_tenure'=>'5 years','note'=>'Flexible EMI options. Good for used EVs too.'],
                    ['name'=>'Bajaj Finserv','type'=>'NBFC','rate'=>'9.50% – 14.00%','max_tenure'=>'5 years','note'=>'Minimal documentation. Instant approval via app.'],
                    ['name'=>'Axis Bank','type'=>'Bank','rate'=>'9.00% – 11.50%','max_tenure'=>'7 years','note'=>'Competitive rates, wide dealer tie-ups across India.'],
                ];
                ?>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <?php foreach ($financeOptions as $fo): ?>
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-900"><?= esc($fo['name']) ?></p>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                                    <?= esc($fo['type']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-slate-400">Interest Rate</p>
                                <p class="font-semibold text-slate-800"><?= esc($fo['rate']) ?></p>
                            </div>
                            <div>
                                <p class="text-slate-400">Max Tenure</p>
                                <p class="font-semibold text-slate-800"><?= esc($fo['max_tenure']) ?></p>
                            </div>
                        </div>
                        <?php if (!empty($fo['note'])): ?>
                        <p class="mt-2 text-xs text-slate-500"><?= esc($fo['note']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p class="mt-4 text-xs text-slate-400">* Interest rates are indicative and subject to change. Verify with the lender before applying.</p>
            </div>

            <!-- FAME II -->
            <div class="rounded-2xl bg-emerald-50 p-6 ring-1 ring-emerald-200 md:p-8">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-emerald-900">FAME II Subsidy</h2>
                        <p class="mt-2 text-sm text-emerald-800">
                            The Faster Adoption and Manufacturing of (Hybrid &amp;) Electric Vehicles Phase II (FAME II) scheme
                            provides direct subsidies to EV buyers, reducing the purchase price:
                        </p>
                    </div>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <?php
                    $fameItems = [
                        ['cat'=>'Electric 2-Wheelers','subsidy'=>'Up to ₹22,500','note'=>'On qualifying models. Min 40 km range.'],
                        ['cat'=>'Electric 3-Wheelers','subsidy'=>'Up to ₹50,000','note'=>'Commercial and private vehicles.'],
                        ['cat'=>'Electric 4-Wheelers','subsidy'=>'Up to ₹1,50,000','note'=>'Mostly fleet/commercial currently.'],
                        ['cat'=>'Electric Buses','subsidy'=>'Up to ₹50 lakh','note'=>'State-operated electric city buses.'],
                    ];
                    foreach ($fameItems as $fi):
                    ?>
                    <div class="rounded-xl bg-white p-4">
                        <p class="text-xs font-semibold text-slate-500"><?= esc($fi['cat']) ?></p>
                        <p class="mt-1 text-xl font-black text-emerald-700"><?= esc($fi['subsidy']) ?></p>
                        <p class="mt-0.5 text-xs text-slate-500"><?= esc($fi['note']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- State subsidies table -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8">
                <h2 class="text-xl font-black text-slate-900">State EV Subsidies</h2>
                <p class="mt-1 text-sm text-slate-500">Additional incentives over and above FAME II (indicative, verify with your state transport department)</p>
                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <th class="py-2 pr-4 text-left">State</th>
                                <th class="py-2 pr-4 text-left">2W Subsidy</th>
                                <th class="py-2 pr-4 text-left">4W Subsidy</th>
                                <th class="py-2 text-left">Road Tax</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $stateSubsidies = [
                                ['state'=>'Delhi','s2w'=>'₹5,000/kWh (up to ₹30,000)','s4w'=>'₹10,000/kWh (up to ₹1.5 lakh)','tax'=>'Exempt'],
                                ['state'=>'Maharashtra','s2w'=>'₹10,000 (≤2-wheeler)','s4w'=>'₹2.5 lakh (for first 1 lakh EVs)','tax'=>'Exempt'],
                                ['state'=>'Gujarat','s2w'=>'₹20,000','s4w'=>'₹1.5 lakh','tax'=>'Exempt'],
                                ['state'=>'Rajasthan','s2w'=>'₹10,000','s4w'=>'₹10,000','tax'=>'100% waiver'],
                                ['state'=>'Karnataka','s2w'=>'₹10,000','s4w'=>'₹1 lakh','tax'=>'Exempt'],
                                ['state'=>'Tamil Nadu','s2w'=>'₹10,000','s4w'=>'₹1 lakh','tax'=>'Exempt'],
                                ['state'=>'Telangana','s2w'=>'₹5,000','s4w'=>'₹60,000','tax'=>'Road tax rebate'],
                                ['state'=>'UP','s2w'=>'₹5,000/kWh','s4w'=>'₹1 lakh','tax'=>'100% waiver'],
                            ];
                            foreach ($stateSubsidies as $row):
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pr-4 font-semibold text-slate-900"><?= esc($row['state']) ?></td>
                                <td class="py-3 pr-4 text-slate-700"><?= esc($row['s2w']) ?></td>
                                <td class="py-3 pr-4 text-slate-700"><?= esc($row['s4w']) ?></td>
                                <td class="py-3 text-emerald-700 font-medium"><?= esc($row['tax']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-slate-400">Data is indicative. Policies change frequently. Verify with your state EV policy or transport office.</p>
            </div>

            <!-- EMI Calculator embed -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8" x-data="{
                price: 150000,
                down: 30000,
                rate: 9,
                tenure: 5,
                get loanAmt() { return this.price - this.down; },
                get emi() {
                    const p = this.loanAmt;
                    const r = (this.rate / 100) / 12;
                    const n = this.tenure * 12;
                    if (r === 0) return p / n;
                    return Math.round((p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1));
                },
                get totalPayable() { return this.emi * this.tenure * 12; },
                get totalInterest() { return this.totalPayable - this.loanAmt; }
            }">
                <h2 class="text-xl font-black text-slate-900">EMI Calculator</h2>
                <p class="mt-1 text-sm text-slate-500">Estimate your monthly payment</p>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Vehicle Price (₹)</label>
                        <input type="number" x-model.number="price" min="50000" max="5000000" step="10000"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Down Payment (₹)</label>
                        <input type="number" x-model.number="down" min="0" step="5000"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Interest Rate (%)</label>
                        <input type="number" x-model.number="rate" min="5" max="20" step="0.25"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Loan Tenure (years)</label>
                        <select x-model.number="tenure" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <option value="1">1 year</option>
                            <option value="2">2 years</option>
                            <option value="3">3 years</option>
                            <option value="5" selected>5 years</option>
                            <option value="7">7 years</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-4 rounded-xl bg-emerald-50 p-4 text-center">
                    <div>
                        <p class="text-xs font-semibold text-emerald-700">Loan Amount</p>
                        <p class="mt-1 text-xl font-black text-slate-900">₹<span x-text="loanAmt.toLocaleString('en-IN')"></span></p>
                    </div>
                    <div class="border-x border-emerald-200">
                        <p class="text-xs font-semibold text-emerald-700">Monthly EMI</p>
                        <p class="mt-1 text-2xl font-black text-emerald-700">₹<span x-text="emi.toLocaleString('en-IN')"></span></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-700">Total Interest</p>
                        <p class="mt-1 text-xl font-black text-slate-900">₹<span x-text="totalInterest.toLocaleString('en-IN')"></span></p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">For illustration only. Actual EMI may vary based on lender's calculation method and processing fees.</p>
            </div>

        </div><!-- /main -->

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Lead form -->
            <div id="lead-form">
                <?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?>
            </div>

            <!-- Quick facts -->
            <div class="rounded-2xl bg-slate-900 p-5 text-white">
                <h3 class="font-bold text-white">Section 80EEB</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Individual taxpayers can claim deduction of up to <strong class="text-emerald-400">₹1.5 lakh per year</strong>
                    on interest paid on EV loans under Section 80EEB of Income Tax Act.
                </p>
                <p class="mt-3 text-xs text-slate-400">Applicable for loans sanctioned between 1 April 2019 – 31 March 2023. Consult your CA for eligibility.</p>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
