<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>Home EV Charger Installation Guide India 2025 — Cost, Process, Subsidy | Charj.in</title>
<meta name="description" content="Complete guide to installing a home EV charger in India. Costs, DISCOM approval, apartment guide, state-wise process and subsidy information.">
<style>
[x-cloak]{display:none!important}
.guide-card{background:#fff;border-radius:1.25rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.07),0 4px 16px rgba(0,0,0,.04);border:1px solid #f1f5f9;}
.step-line{position:relative;padding-left:3rem;}
.step-line::before{content:'';position:absolute;left:1.1rem;top:2.5rem;bottom:-1rem;width:2px;background:linear-gradient(to bottom,#22c55e,#e2e8f0);}
.step-circle{position:absolute;left:0;top:0;width:2.25rem;height:2.25rem;border-radius:9999px;background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;font-weight:900;font-size:.875rem;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(34,197,94,.35);}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm relative overflow-hidden pt-28 pb-16 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
  <div class="absolute inset-0 opacity-60 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:28px 28px"></div>
  <div class="relative max-w-6xl mx-auto grid lg:grid-cols-2 gap-10 items-center">

    <div class="text-center lg:text-left">
      <div class="hero-badge inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-6"
           style="background:rgba(0,168,150,.1);border:1.5px solid rgba(0,168,150,.2);color:#00A896">
        ⚡ India's Most Comprehensive Guide — 2025
      </div>
      <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-4 leading-tight" style="color:#0F172A">Home EV Charging —<br>Complete India Guide 2025</h1>
      <p class="hero-desc text-lg max-w-xl mx-auto lg:mx-0 mb-8 leading-relaxed" style="color:#475569">
        Everything about installing a home charger: cost, process, DISCOM approval, subsidy, and how to avoid the most common mistakes Indian EV owners make.
      </p>

      <!-- Trust row — CarDekho-style icon + label trio -->
      <div class="flex items-center justify-center lg:justify-start gap-8 sm:gap-10 mb-8 flex-wrap">
        <?php foreach ([
          ['icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Certified Installers'],
          ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                  'label' => 'Free, Fast Quotes'],
          ['icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'label' => 'No Spam Calls'],
        ] as $t): ?>
        <div class="flex flex-col items-center gap-2">
          <div class="w-12 h-12 rounded-full flex items-center justify-center" style="border:1.5px solid rgba(0,168,150,.3);background:rgba(0,168,150,.05)">
            <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $t['icon'] ?>"/></svg>
          </div>
          <span class="text-xs font-bold" style="color:#0F172A"><?= $t['label'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
        <?php foreach (['Charger Types','Cost Estimator','Apartment Guide','DISCOM Approval','FAQ'] as $tab): ?>
        <a href="#<?= strtolower(str_replace([' ','('],['','-'],$tab)) ?>"
           class="rounded-xl px-3 py-1.5 text-xs sm:text-sm font-semibold transition-colors"
           style="background:#FFFFFF;color:#00A896;border:1px solid rgba(0,168,150,.2)"
           onmouseover="this.style.background='rgba(0,168,150,.08)'" onmouseout="this.style.background='#FFFFFF'">
          <?= $tab ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Hero image — real home EV charger installation photo, edges masked to fade into the page instead of a hard rectangle -->
    <div class="relative hidden lg:block">
      <div class="absolute -inset-10 -z-10" style="background:radial-gradient(circle,rgba(0,230,118,.32),rgba(0,230,118,.1) 50%,transparent 72%);filter:blur(10px)"></div>
      <img src="<?= base_url('assets/images/guides/home-charger-hero.jpg') ?>"
           alt="Person plugging in a home EV charger"
           class="w-full h-auto object-cover"
           style="mask-image:radial-gradient(ellipse 78% 78% at center,#000 55%,transparent 100%);-webkit-mask-image:radial-gradient(ellipse 78% 78% at center,#000 55%,transparent 100%)"
           loading="eager" decoding="async">
    </div>
  </div>
</section>

<div class="bg-slate-50 pb-14">
<div class="max-w-5xl mx-auto px-4">

  <!-- Floating overlap card: How It Works + Quick Stats (CarDekho-style trust/flow block) -->
  <div class="relative -mt-10 mb-14">
    <div class="rounded-3xl bg-white p-6 sm:p-8" style="box-shadow:0 12px 40px rgba(0,0,0,.08),0 2px 8px rgba(0,0,0,.04);border:1px solid rgba(0,230,118,.12)">

      <!-- How It Works — 3-step flow with connectors -->
      <div class="flex items-center justify-center gap-1 sm:gap-3 flex-wrap mb-8">
        <?php
        $howSteps = [
          ['icon' => '📋', 'label' => 'Check Your Eligibility'],
          ['icon' => '🧑‍🔧', 'label' => 'Get Certified Installer Quotes'],
          ['icon' => '⚡', 'label' => 'Book &amp; Get Charging'],
        ];
        foreach ($howSteps as $idx => $hs): ?>
        <div class="flex items-center gap-1 sm:gap-3">
          <div class="flex flex-col items-center gap-2 text-center" style="width:112px">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl" style="background:rgba(0,230,118,.08);border:1.5px solid rgba(0,230,118,.25)">
              <?= $hs['icon'] ?>
            </div>
            <span class="text-xs font-bold leading-tight" style="color:#0F172A"><?= $hs['label'] ?></span>
          </div>
          <?php if ($idx < count($howSteps) - 1): ?>
          <svg class="w-5 h-5 flex-shrink-0 hidden sm:block" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="h-px mb-6" style="background:linear-gradient(90deg,transparent,rgba(0,230,118,.18),transparent)"></div>

      <!-- Quick Stats — with icon badges -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php foreach ([
          ['icon'=>'💰','val'=>'₹8K–40K','label'=>'Charger cost'],
          ['icon'=>'🌙','val'=>'6–8 hrs','label'=>'Overnight charge'],
          ['icon'=>'📋','val'=>'3–6 wks','label'=>'DISCOM approval'],
          ['icon'=>'⚡','val'=>'15–25%','label'=>'Electricity tariff benefit'],
        ] as $s): ?>
        <div class="flex items-center gap-3 p-3 rounded-2xl" style="background:#F5FFF7;border:1px solid rgba(0,230,118,.1)">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-lg flex-shrink-0" style="background:rgba(0,230,118,.12)"><?= $s['icon'] ?></div>
          <div class="min-w-0">
            <div class="text-base font-black leading-none" style="color:#0F172A"><?= $s['val'] ?></div>
            <div class="text-[11px] mt-1 font-semibold" style="color:#94A3B8"><?= $s['label'] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Lead capture: Get Free Installer Quotes — one unified card, split 50/50 -->
  <div class="rounded-3xl overflow-hidden mb-14 grid lg:grid-cols-2" style="box-shadow:0 20px 50px rgba(0,0,0,.1),0 2px 8px rgba(0,0,0,.04)">

    <!-- Left half — solid brand panel -->
    <div class="p-8 sm:p-10 flex flex-col justify-center" style="background:linear-gradient(150deg,#00A896,#007A6E)">
      <span class="inline-flex items-center gap-1.5 mb-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide w-fit" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25)">Free &amp; No Obligation</span>
      <h2 class="text-2xl sm:text-3xl font-black mb-3 leading-tight text-white">Get Free Installer Quotes</h2>
      <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,.85)">Tell us your city and vehicle type — our certified installer network will send you 3 competing quotes within 24 hours. Compare prices before you commit.</p>
      <div class="flex flex-col gap-2.5 mt-6">
        <span class="inline-flex items-center gap-2 text-sm font-bold text-white">
          <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          Certified electricians
        </span>
        <span class="inline-flex items-center gap-2 text-sm font-bold text-white">
          <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          3 competing quotes within 24 hrs
        </span>
        <span class="inline-flex items-center gap-2 text-sm font-bold text-white">
          <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          No spam calls
        </span>
      </div>
    </div>

    <!-- Right half — form -->
    <form action="<?= base_url('lead/submit') ?>" method="post" class="p-8 sm:p-10 bg-white flex flex-col justify-center">
      <?= csrf_field() ?>
      <input type="hidden" name="lead_type" value="home_charger_quote">
      <input type="hidden" name="source_url" value="<?= current_url() ?>">
      <div class="grid sm:grid-cols-2 gap-3 mb-3">
        <div>
          <label class="block text-xs font-bold mb-1.5" style="color:#334155">Full Name *</label>
          <input type="text" name="name" required placeholder="e.g. Rahul Sharma"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold mb-1.5" style="color:#334155">Mobile Number *</label>
          <input type="tel" name="mobile" required pattern="[0-9]{10}" maxlength="10" inputmode="numeric" placeholder="10-digit mobile"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
        </div>
      </div>
      <div class="grid sm:grid-cols-2 gap-3 mb-4">
        <div>
          <label class="block text-xs font-bold mb-1.5" style="color:#334155">City</label>
          <input type="text" name="city" placeholder="e.g. Bengaluru"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold mb-1.5" style="color:#334155">Vehicle Type</label>
          <select name="message" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
            <option value="Scooter/Bike charger quote">Electric Scooter / Bike</option>
            <option value="Budget car charger quote">Electric Car (Budget)</option>
            <option value="Premium car charger quote">Electric Car (Premium)</option>
          </select>
        </div>
      </div>
      <button type="submit" class="w-full inline-flex items-center justify-center gap-2 font-black text-sm px-5 py-3.5 rounded-xl transition-all"
              style="background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 6px 20px rgba(0,230,118,.35)">
        Get My Free Quotes →
      </button>
      <p class="text-[11px] text-center mt-2.5" style="color:#94A3B8">By submitting you agree to be contacted about home charger installation.</p>
    </form>
  </div>

<div class="space-y-14">

  <!-- 1. Charger Types -->
  <section id="charger-types">
    <h2 class="text-2xl font-black text-slate-900 mb-2">Types of Home EV Chargers</h2>
    <p class="text-slate-500 mb-6">Three tiers — pick what suits your EV, parking and budget.</p>
    <div class="grid sm:grid-cols-3 gap-4">
      <?php
      $chargerTypes = [
        ['Level 1 — 5 amp Socket','🔌','₹0 – ₹2,000','2–3 kW','Best for scooters & bikes charging overnight.','Pros: No installation cost. Cons: Very slow for cars (30+ hrs).','bg-slate-50 border-slate-200'],
        ['Level 2 — Wallbox (AC)','⚡','₹15,000 – ₹40,000','3.3 – 7.4 kW','Best for electric cars and premium scooters.','Pros: 6–8 hr full charge. Cons: Needs certified electrician install.','bg-green-50 border-green-200'],
        ['DC Fast Charger (Home)','🚀','₹1.5L – ₹5L','15 – 50 kW','Only for fleets or ultra-premium setups.','Pros: 1–2 hr charge. Cons: Very expensive, needs 3-phase connection.','bg-blue-50 border-blue-200'],
      ];
      foreach ($chargerTypes as $ct): ?>
      <div class="guide-card border-2 <?= $ct[6] ?> flex flex-col">
        <div class="text-3xl mb-3"><?= $ct[1] ?></div>
        <h3 class="font-black text-slate-900 text-base mb-1"><?= $ct[0] ?></h3>
        <div class="flex gap-3 mb-3">
          <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded-lg"><?= $ct[2] ?></span>
          <span class="text-xs font-bold text-blue-700 bg-blue-100 px-2 py-1 rounded-lg"><?= $ct[3] ?></span>
        </div>
        <p class="text-sm text-slate-600 mb-2 flex-1"><?= $ct[4] ?></p>
        <p class="text-xs text-slate-400"><?= $ct[5] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 2. Cost Estimator -->
  <section id="cost-estimator" x-data="{ev:'scooter',parking:'own',phase:'single'}">
    <h2 class="text-2xl font-black text-slate-900 mb-2">Cost Estimator</h2>
    <p class="text-slate-500 mb-6">Quick estimate — actual costs vary by electrician and city.</p>
    <div class="guide-card">
      <div class="grid sm:grid-cols-3 gap-4 mb-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-2">Vehicle Type</label>
          <select x-model="ev" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
            <option value="scooter">Electric Scooter / Bike</option>
            <option value="car-budget">Electric Car (Budget)</option>
            <option value="car-premium">Electric Car (Premium)</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-2">Parking Situation</label>
          <select x-model="parking" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
            <option value="own">Own house / villa</option>
            <option value="society">Apartment / society</option>
            <option value="rented">Rented flat</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-2">Connection Type</label>
          <select x-model="phase" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
            <option value="single">Single Phase</option>
            <option value="three">Three Phase</option>
          </select>
        </div>
      </div>
      <!-- Results -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="rounded-xl bg-green-50 border border-green-100 p-4 text-center">
          <div class="text-lg font-black text-green-700"
               x-text="ev==='scooter' ? '₹0–5K' : ev==='car-budget' ? '₹15–25K' : '₹25–40K'"></div>
          <div class="text-xs text-green-600 font-semibold mt-1">Charger Unit</div>
        </div>
        <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 text-center">
          <div class="text-lg font-black text-blue-700"
               x-text="parking==='own' ? '₹2–8K' : parking==='society' ? '₹8–25K' : '₹5–15K'"></div>
          <div class="text-xs text-blue-600 font-semibold mt-1">Wiring / Installation</div>
        </div>
        <div class="rounded-xl bg-amber-50 border border-amber-100 p-4 text-center">
          <div class="text-lg font-black text-amber-700"
               x-text="phase==='three' ? '₹8–15K' : '₹0'"></div>
          <div class="text-xs text-amber-600 font-semibold mt-1">Phase Upgrade</div>
        </div>
        <div class="rounded-xl bg-slate-900 p-4 text-center">
          <div class="text-lg font-black text-white"
               x-text="ev==='scooter' ? '₹3–13K' : ev==='car-budget' ? '₹23–48K' : '₹33–65K'"></div>
          <div class="text-xs text-slate-400 font-semibold mt-1">Total Estimate</div>
        </div>
      </div>
      <p class="text-xs text-slate-400 mt-4">* Estimates only. Get 3 quotes from certified electricians. EV brand installers (Ather, Tata, Ola) often cheaper for their own brand.</p>
    </div>
  </section>

  <!-- 3. Step-by-step process -->
  <section id="apartment-guide">
    <h2 class="text-2xl font-black text-slate-900 mb-2">Step-by-Step Installation Process</h2>
    <p class="text-slate-500 mb-8">Follow these steps whether you're in a villa or an apartment.</p>
    <div class="space-y-6">
      <?php
      $steps = [
        ['Check your meter load capacity','Call your DISCOM to verify you have spare capacity (minimum 5 amps for 2W, 15 amps for cars). Most urban homes already have 15–25A connections.','⚡'],
        ['Choose the right charger','Your EV brand usually provides a basic EVSE free or at cost. Upgrade to a branded wallbox (Tata, Ather, Ola, Ampere) for faster charging and better safety.','🔌'],
        ['Hire a certified electrician','Use an electrician certified by BEE (Bureau of Energy Efficiency) or one recommended by your EV brand. Avoid general household electricians for high-current work.','👷'],
        ['Get DISCOM approval (if needed)','For loads above 5kW or three-phase connection, you\'ll need DISCOM approval. Submit: ID proof, meter details, load enhancement form. Takes 2–6 weeks.','📋'],
        ['Get society NOC (apartments only)','Not legally mandatory if drawing from own meter, but strongly recommended. Write a formal letter to your RWA citing Ministry of Power 2022 guidelines.','🏢'],
        ['Install earthing & safety devices','Your installer must set up: proper earthing, RCCB (residual current circuit breaker), and a dedicated MCB for the charging point. Non-negotiable safety items.','🛡️'],
        ['Test and certify the installation','After installation, get a completion certificate from your electrician. Test charging with your actual vehicle before final payment.','✅'],
      ];
      foreach ($steps as $i => $s): ?>
      <div class="step-line guide-card">
        <div class="step-circle"><?= $i+1 ?></div>
        <h3 class="font-black text-slate-900 text-base mb-1"><?= $s[2] ?> <?= $s[0] ?></h3>
        <p class="text-sm text-slate-600 leading-relaxed"><?= $s[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 4. DISCOM table -->
  <section id="discom-table">
    <h2 class="text-2xl font-black text-slate-900 mb-2">DISCOM Rules by State</h2>
    <p class="text-slate-500 mb-6">EV charging tariffs and key rules from major DISCOMs.</p>
    <div class="guide-card overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100">
            <th class="text-left py-3 px-2 font-bold text-slate-700">State / DISCOM</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">EV Tariff (₹/unit)</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">Load Approval</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">Notes</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <?php
          $discoms = [
            ['Delhi (BSES/TPDDL)','₹4.50','Upto 5 kW: No approval','EV subsidy: ₹30K two-wheeler, ₹1.5L car'],
            ['Maharashtra (MSEDCL)','₹5.20','Above 10 kW: approval','Mandatory submetering for societies'],
            ['Karnataka (BESCOM)','₹5.00','Above 5 kW: form needed','EV-friendly policy, fast approvals'],
            ['Tamil Nadu (TNEB)','₹4.10','Below 10 kW: no approval','Low tariff state, good for high mileage users'],
            ['Telangana (TSSPDCL)','₹5.10','Form B required >5kW','Separate EV meter recommended'],
            ['Gujarat (UGVCL/PGVCL)','₹4.85','Notify DISCOM','FAME-II subsidy avail for 2W'],
            ['Rajasthan (JVVNL)','₹5.50','Load enhancement form','EV policy 2022: road tax exempt'],
            ['UP (DVVNL/PVVNL)','₹5.80','Above 5kW: LOA needed','One-time fixed charges may apply'],
          ];
          foreach ($discoms as $row): ?>
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="py-3 px-2 font-semibold text-slate-900"><?= $row[0] ?></td>
            <td class="py-3 px-2 text-green-700 font-bold"><?= $row[1] ?></td>
            <td class="py-3 px-2 text-slate-600"><?= $row[2] ?></td>
            <td class="py-3 px-2 text-slate-500 text-xs"><?= $row[3] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="text-xs text-slate-400 mt-3 px-2">Data as of 2025. Verify with your local DISCOM for latest rates.</p>
    </div>
  </section>

  <!-- 5. FAQ -->
  <section id="faq" x-data="{open:null}">
    <h2 class="text-2xl font-black text-slate-900 mb-6">Frequently Asked Questions</h2>
    <div class="space-y-3">
      <?php
      $faqs = [
        ['Can I charge an EV with a normal 5-amp socket?','Yes, for two-wheelers — most come with a portable EVSE that plugs into a standard 5A socket. For electric cars, you technically can but it\'s very slow (30+ hours). A 15A or dedicated wallbox is strongly recommended.'],
        ['Will my electricity bill spike if I charge at home?','At ₹5/unit average, charging a 2-wheeler (2kWh) costs ₹10 and a car (30kWh full charge) costs ₹150. Most people charge every 2–3 days, so monthly addition is ₹1,000–2,500 for cars. Far cheaper than petrol.'],
        ['Do I need three-phase connection for home charging?','No for most EVs. Single-phase is enough for up to 7.4kW wallbox, which is sufficient for all EVs except some premium cars with fast on-board chargers. Three-phase only needed for 11kW+ charging.'],
        ['Is there any subsidy for home charger installation?','Delhi: ₹6,000 subsidy for charger purchase. Some states (Gujarat, Maharashtra) have society-level subsidy programs. Check your state EV policy or call your DISCOM. FAME-II subsidises the EV itself, not the charger.'],
        ['Can I install a charger in a rented flat?','Yes, but you need landlord\'s written permission. If you move out, the charger is typically yours to take (portable EVSE) or can be deducted from the security deposit after mutual agreement.'],
        ['What safety equipment is mandatory?','RCCB (30mA residual current), dedicated MCB (miniature circuit breaker) for the EV circuit, proper earthing at the socket/wallbox, and IP65-rated outdoor enclosure if the charger is in an open parking area.'],
      ];
      foreach ($faqs as $i => $faq): ?>
      <div class="guide-card">
        <button @click="open===<?= $i ?> ? open=null : open=<?= $i ?>"
                class="w-full flex items-start justify-between gap-4 text-left">
          <span class="font-bold text-slate-900 text-sm"><?= $faq[0] ?></span>
          <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform mt-0.5"
               :class="open===<?= $i ?> ? 'rotate-180' : ''"
               fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open===<?= $i ?>" x-cloak x-transition class="mt-3 pt-3 border-t border-slate-100 text-sm text-slate-600 leading-relaxed">
          <?= $faq[1] ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CTA -->
  <section class="rounded-3xl bg-gradient-to-br from-slate-900 to-green-900 p-8 text-center text-white">
    <h2 class="text-2xl font-black mb-2">Ready to Go Electric?</h2>
    <p class="text-slate-300 mb-6 text-sm">Find your perfect EV and discover charging stations near you.</p>
    <div class="flex flex-wrap gap-3 justify-center">
      <a href="<?= base_url('find-my-ev') ?>" class="bg-green-500 hover:bg-green-400 text-white font-bold px-6 py-3 rounded-full text-sm transition-colors">Find My EV ⚡</a>
      <a href="<?= base_url('charging-stations') ?>" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-6 py-3 rounded-full text-sm transition-colors">Find Chargers Near Me</a>
    </div>
  </section>

</div>
</div>
</div>

<?= $this->endSection() ?>
