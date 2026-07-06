<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>Buy & Sell Used Electric Vehicles India — Verified Used EVs | Charj.in</title>
<meta name="description" content="Buy or sell used electric vehicles in India. Battery health reports, service history, no broker fees. Join the waitlist for Charj Used EV Marketplace.">
<style>
[x-cloak]{display:none!important}
.guide-card{background:#fff;border-radius:1.25rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.07),0 4px 16px rgba(0,0,0,.04);border:1px solid #f1f5f9;}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm relative overflow-hidden bg-gradient-to-br from-slate-950 via-green-950 to-emerald-900 text-white pt-28 pb-16 px-4">
  <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:28px 28px"></div>
  <div class="relative max-w-5xl mx-auto text-center">
    <div class="hero-badge inline-flex items-center gap-2 bg-green-500/20 border border-green-500/30 rounded-full px-3 py-1 text-green-300 text-xs font-bold uppercase tracking-widest mb-6">
      🚗 India's Dedicated Used EV Marketplace — Launching Soon
    </div>
    <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-4 leading-tight">Buy or Sell a<br>Used EV in India</h1>
    <p class="hero-desc text-lg text-slate-300 max-w-3xl mx-auto mb-6 leading-relaxed">
      India's first dedicated used EV marketplace is coming. Join the waitlist and get early access to <strong class="text-green-400">battery-health verified listings</strong> — zero broker fees.
    </p>
    <!-- Stats -->
    <div class="flex flex-wrap justify-center gap-2 mb-4 sm:mb-7">
      <?php foreach ([['2,400+','on waitlist'],['100%','battery verified'],['0%','broker fee'],['₹2L–₹25L','price range']] as $s): ?>
      <div class="flex items-center gap-2 bg-white/10 border border-white/10 rounded-full px-4 py-2 text-sm">
        <span class="text-green-400 font-black"><?= $s[0] ?></span>
        <span class="text-slate-300"><?= $s[1] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <!-- Waitlist Form -->
    <div class="max-w-lg mx-auto" x-data="{email:'',submitted:false,loading:false}">
      <div x-show="!submitted" class="flex gap-2">
        <input x-model="email" type="email" placeholder="Enter your email for early access"
               class="flex-1 rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white placeholder-white/50 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
        <button @click="if(email){submitted=true;charjTrack('used_ev_waitlist',{email})}"
                :disabled="!email"
                class="bg-green-500 hover:bg-green-400 disabled:opacity-50 text-white font-bold px-5 py-3 rounded-xl text-sm transition-colors flex-shrink-0">
          Join Waitlist
        </button>
      </div>
      <div x-show="submitted" x-cloak class="bg-green-500/20 border border-green-400/30 rounded-xl px-6 py-4 text-green-300 text-sm font-semibold">
        ✅ You're on the list! We'll notify you when we launch.
      </div>
    </div>
  </div>
</section>

<div class="bg-slate-50 min-h-screen">
<div class="max-w-5xl mx-auto px-4 py-12 space-y-14">

  <!-- Why Used EV -->
  <section>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Why Buy a Used EV?</h2>
    <p class="text-slate-500 mb-6">The economics make even more sense second-hand.</p>
    <div class="grid sm:grid-cols-3 gap-4">
      <?php
      $reasons = [
        ['💰','30–50% Cheaper','A 2-year-old Tata Nexon EV Max that cost ₹19L new can be bought for ₹12–14L. Same range, same safety, same technology.'],
        ['🔋','Battery is Fine','Lithium batteries in modern EVs retain 85–90% capacity after 5 years / 80,000km. Battery degradation fear is largely a myth with quality EVs.'],
        ['📉','Zero Depreciation Shock','You\'re buying after the first owner absorbs the steepest depreciation curve. Your 2nd to 5th year ownership sees much slower value drop.'],
        ['🌱','Greener Choice','Extending a vehicle\'s life reduces overall carbon footprint — all the embedded carbon in manufacturing is already spent.'],
        ['🔧','Known Issues Sorted','First owners often get manufacturing defects fixed under warranty. You get a vehicle where early issues are already resolved.'],
        ['⚡','Low Running Cost','Same ₹1.5–2/km electricity cost. No engine oil. No gearbox. EV mechanicals are simpler and cheaper to maintain than any ICE vehicle.'],
      ];
      foreach ($reasons as $r): ?>
      <div class="guide-card">
        <div class="text-3xl mb-3"><?= $r[0] ?></div>
        <h3 class="font-black text-slate-900 text-sm mb-2"><?= $r[1] ?></h3>
        <p class="text-xs text-slate-600 leading-relaxed"><?= $r[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Checklist -->
  <section x-data="{checked:[]}">
    <h2 class="text-2xl font-black text-slate-900 mb-2">Used EV Buyer's Checklist</h2>
    <p class="text-slate-500 mb-6">Run through this before handing over any money.</p>
    <div class="grid sm:grid-cols-2 gap-3">
      <?php
      $checks = [
        ['Battery Health Report','Run a battery diagnostic via OBD or manufacturer app — should show >80% SOH for vehicles under 5 years.','🔋'],
        ['Charging History','Frequent DC fast charging degrades battery faster. Check if the owner used mostly home/AC charging.','⚡'],
        ['RC Transfer Clarity','Ensure seller has original RC. Confirm no loans pending (check via VAHAN or bank NOC). Ownership transfer takes 2–4 weeks via RTO.','📋'],
        ['Software Version','Check if vehicle firmware is up to date. For Tata, Ola, Ather — check via manufacturer app. Updates often improve range and features.','💻'],
        ['Accident/Flood History','Check for uneven panel gaps, paint overspray, musty smell. Flood-damaged EVs can have serious electrical issues.','🔍'],
        ['Warranty Remaining','Most EVs have 8-year battery warranty. Verify remaining warranty transferability with the brand\'s service centre.','🛡️'],
        ['Tyre Condition','EVs are heavier — tyre wear is faster. Check tread depth and age (sidewall date code). Budget ₹8–15K for new tyres if needed.','🚗'],
        ['Test Drive — All Modes','Test eco, normal and sport modes if available. Check regen braking, AC performance, cabin tech. Verify all charging ports work.','🏁'],
      ];
      foreach ($checks as $i => $c): ?>
      <label class="guide-card flex items-start gap-3 cursor-pointer hover:border-green-200 transition-colors"
             :class="checked.includes(<?= $i ?>) ? 'border-green-400 bg-green-50' : ''">
        <input type="checkbox" class="hidden" @change="checked.includes(<?= $i ?>) ? checked.splice(checked.indexOf(<?= $i ?>),1) : checked.push(<?= $i ?>)">
        <div class="flex-shrink-0 w-5 h-5 rounded-md border-2 mt-0.5 flex items-center justify-center transition-colors"
             :class="checked.includes(<?= $i ?>) ? 'bg-green-500 border-green-500' : 'border-slate-300'">
          <svg x-show="checked.includes(<?= $i ?>)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-bold text-slate-900 text-sm"><?= $c[2] ?> <?= $c[0] ?></div>
          <p class="text-xs text-slate-500 mt-0.5 leading-relaxed"><?= $c[1] ?></p>
        </div>
      </label>
      <?php endforeach; ?>
    </div>
    <div class="mt-4 guide-card bg-green-50 border-green-200 text-center">
      <span class="font-black text-green-700" x-text="checked.length + '/<?= count($checks) ?> checks completed'"></span>
      <span x-show="checked.length===<?= count($checks) ?>" x-cloak class="block text-green-600 text-sm mt-1">🎉 Great! You\'re ready to buy safely.</span>
    </div>
  </section>

  <!-- Price Guide -->
  <section>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Used EV Price Guide — India 2025</h2>
    <p class="text-slate-500 mb-6">Approximate market prices for popular used EVs.</p>
    <div class="guide-card overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100">
            <th class="text-left py-3 px-2 font-bold text-slate-700">Vehicle</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">Age</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">Used Price Range</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">New Price</th>
            <th class="text-left py-3 px-2 font-bold text-slate-700">Saving</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <?php
          $prices = [
            ['Tata Nexon EV','2–3 years','₹11–14L','₹14.74L','~30%'],
            ['Tata Tigor EV','2–3 years','₹7–9L','₹11.99L','~35%'],
            ['MG ZS EV','2–3 years','₹13–16L','₹18.98L','~25%'],
            ['Ola S1 Pro (2022)','2 years','₹75K–95K','₹1.39L','~38%'],
            ['Ather 450X (Gen 3)','1–2 years','₹1–1.2L','₹1.49L','~25%'],
            ['Tata Tiago EV','1–2 years','₹6–8L','₹8.49L','~25%'],
          ];
          foreach ($prices as $p): ?>
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="py-3 px-2 font-semibold text-slate-900"><?= $p[0] ?></td>
            <td class="py-3 px-2 text-slate-500 text-xs"><?= $p[1] ?></td>
            <td class="py-3 px-2 font-black text-green-700"><?= $p[2] ?></td>
            <td class="py-3 px-2 text-slate-400 text-xs line-through"><?= $p[3] ?></td>
            <td class="py-3 px-2"><span class="bg-green-100 text-green-700 font-bold text-xs px-2 py-1 rounded-full"><?= $p[4] ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="text-xs text-slate-400 mt-3 px-2">Prices vary by condition, city, and demand. Always verify with an inspection before purchase.</p>
    </div>
  </section>

  <!-- RC Transfer -->
  <section>
    <h2 class="text-2xl font-black text-slate-900 mb-2">RC Transfer Process</h2>
    <p class="text-slate-500 mb-6">The RC transfer must be completed within 30 days of sale.</p>
    <div class="grid sm:grid-cols-2 gap-4">
      <?php
      $cols = [
        ['Seller Provides','🟡',['Form 29 (Notice of transfer) — signed','Form 30 (Application for transfer) — signed','Original RC book','Valid insurance certificate','PUC certificate (if required by state)','NOC from hypothecation bank (if loan was taken)','Two passport photos']],
        ['Buyer Needs','🟢',['Form 29 and 30 signed','Address proof (Aadhaar, utility bill)','Identity proof','Insurance in buyer\'s name (mandatory before transfer)','Applicable RTO transfer fees (₹300–1,500)','Form 33 if address is different state','New address endorsement on RC if needed']],
      ];
      foreach ($cols as $col): ?>
      <div class="guide-card">
        <div class="font-black text-slate-900 mb-3"><?= $col[0] ?> <?= $col[1] ?></div>
        <ul class="space-y-2">
          <?php foreach ($col[2] as $item): ?>
          <li class="flex items-start gap-2 text-sm text-slate-700">
            <span class="text-green-500 flex-shrink-0 mt-0.5">→</span><?= $item ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CTA -->
  <section class="rounded-3xl bg-gradient-to-br from-slate-900 to-green-900 p-8 text-center text-white">
    <div class="text-4xl mb-4">🚀</div>
    <h2 class="text-2xl font-black mb-2">Charj Used EV Marketplace — Coming Soon</h2>
    <p class="text-slate-300 mb-6 text-sm max-w-lg mx-auto">Every listing will have battery health report, service history, RTO inspection and owner verification. Zero broker fees.</p>
    <div class="flex flex-wrap gap-3 justify-center">
      <a href="<?= base_url('find-my-ev') ?>" class="bg-green-500 hover:bg-green-400 text-white font-bold px-6 py-3 rounded-full text-sm transition-colors">Find New EVs Now ⚡</a>
      <a href="<?= base_url('vehicles') ?>" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-6 py-3 rounded-full text-sm transition-colors">Browse EV Catalog</a>
    </div>
  </section>

</div>
</div>

<?= $this->endSection() ?>
