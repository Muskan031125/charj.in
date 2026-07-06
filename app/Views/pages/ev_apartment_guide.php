<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>EV Charging in Apartments & Societies — Complete Guide India 2025 | Charj.in</title>
<meta name="description" content="How to charge EV in apartment. Society NOC, wiring, charger options, BESCOM/MSEDCL rules, cost guide for India.">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type":"Question","name":"Can I charge an EV in my apartment?","acceptedAnswer":{"@type":"Answer","text":"Yes. The Electricity Act 2003 and Ministry of Power guidelines (2022) give individual residents the right to draw power for EV charging from their own meter without full RWA approval."}},
    {"@type":"Question","name":"Do I need NOC from housing society?","acceptedAnswer":{"@type":"Answer","text":"Not legally required if drawing from own meter in your designated parking space. However, a written NOC is strongly recommended to avoid disputes."}}
  ]
}
</script>
<style>
[x-cloak]{display:none!important}
.guide-card{background:#fff;border-radius:1.25rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.07),0 4px 16px rgba(0,0,0,.04);border:1px solid #f1f5f9;}
.option-card{border-radius:1rem;padding:1.25rem;border:2px solid #e2e8f0;background:#fff;cursor:pointer;transition:all .18s;}
.option-card:hover,.option-card.active{border-color:#22c55e;background:#f0fdf4;}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm relative overflow-hidden bg-gradient-to-br from-slate-950 via-teal-950 to-green-900 text-white pt-28 pb-16 px-4">
  <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:28px 28px"></div>
  <div class="relative max-w-5xl mx-auto">
    <div class="hero-badge inline-flex items-center gap-2 bg-teal-500/20 border border-teal-400/30 rounded-full px-3 py-1 text-teal-300 text-xs font-bold uppercase tracking-widest mb-6">
      🏢 Apartment EV Charging Guide — India 2025
    </div>
    <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-4 leading-tight">EV Charging in<br>Apartments & Societies</h1>
    <p class="hero-desc text-lg text-slate-300 max-w-3xl mb-7 leading-relaxed">
      The complete guide to charging your EV when you live in a flat — covering your legal rights, society NOC, wiring costs, and the best charger options.
    </p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <?php foreach ([['✅','Legal Right','Ministry of Power 2022'],['💰','₹20–65K','Total install cost'],['🏢','NOC','Recommended not required'],['⚡','7.2kW','Max single-phase speed']] as $s): ?>
      <div class="bg-white/10 backdrop-blur rounded-xl p-3 text-center border border-white/10">
        <div class="text-xl sm:text-2xl mb-0.5"><?= $s[0] ?></div>
        <div class="font-black text-white text-sm"><?= $s[1] ?></div>
        <div class="text-xs text-slate-400 mt-0.5"><?= $s[2] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div class="bg-slate-50 min-h-screen">
<div class="max-w-5xl mx-auto px-4 py-12 space-y-14">

  <!-- Legal Rights -->
  <section>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Your Legal Rights as an EV Owner</h2>
    <p class="text-slate-500 mb-6">You don't need RWA permission to charge from your own meter.</p>
    <div class="grid sm:grid-cols-2 gap-4">
      <div class="guide-card border-l-4 border-green-500">
        <div class="text-green-600 font-black text-sm uppercase tracking-wide mb-2">✅ You CAN Do This</div>
        <ul class="space-y-2 text-sm text-slate-700">
          <?php foreach (['Draw power for EV charging from your own electricity meter','Install a charger in your designated parking spot','Run cable from your flat meter to your parking bay','Refuse RWA demands for \'EV charging fees\' if drawing from own meter','Use a smart charger that auto-adjusts to available load'] as $item): ?>
          <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5 flex-shrink-0">✓</span><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="guide-card border-l-4 border-red-400">
        <div class="text-red-600 font-black text-sm uppercase tracking-wide mb-2">❌ You Need Permission For</div>
        <ul class="space-y-2 text-sm text-slate-700">
          <?php foreach (['Tapping into society\'s common area electricity supply','Installing charger in shared / non-designated parking','Modifying building\'s main electrical panel or wiring','Installing a public-facing charger that others use','Any structural changes to building infrastructure'] as $item): ?>
          <li class="flex items-start gap-2"><span class="text-red-400 mt-0.5 flex-shrink-0">✗</span><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="mt-4 rounded-2xl bg-blue-50 border border-blue-100 p-4">
      <p class="text-sm text-blue-800"><strong>Ministry of Power Circular (Jan 2022):</strong> DISCOMs cannot refuse to provide EV charging load to individual residents. RWAs cannot block residents from installing EV chargers on their own meters.</p>
    </div>
  </section>

  <!-- Charger Options -->
  <section x-data="{selected:'portable'}">
    <h2 class="text-2xl font-black text-slate-900 mb-2">Best Charger Options for Apartments</h2>
    <p class="text-slate-500 mb-6">Compare your options before buying.</p>
    <div class="grid sm:grid-cols-3 gap-4 mb-6">
      <?php
      $options = [
        ['portable','🔌','Portable EVSE','₹3,000–8,000','Best for scooters & 2W. Plug into any 15A socket. Slow but zero install cost.','Speed: 3.3kW | Full charge: 8–10 hrs'],
        ['wallbox','⚡','Smart Wallbox','₹15,000–40,000','Best for car owners. Certified installation, load management, app control.','Speed: 7.2kW | Full charge: 5–7 hrs'],
        ['shared','🏢','Society Shared Charger','₹50K–2L (split)','Best for 4+ EV owners in society. Cost split, managed billing.','Speed: 7–22kW | Managed by vendor'],
      ];
      foreach ($options as $o): ?>
      <button @click="selected='<?= $o[0] ?>'"
              :class="selected==='<?= $o[0] ?>' ? 'border-green-500 bg-green-50' : 'border-slate-200 bg-white'"
              class="option-card text-left w-full">
        <div class="text-3xl mb-3"><?= $o[1] ?></div>
        <div class="font-black text-slate-900 text-sm mb-1"><?= $o[2] ?></div>
        <div class="text-green-700 font-bold text-xs mb-2"><?= $o[3] ?></div>
        <p class="text-xs text-slate-600 mb-2"><?= $o[4] ?></p>
        <div class="text-xs text-slate-400"><?= $o[5] ?></div>
      </button>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Step-by-step for apartments -->
  <section>
    <h2 class="text-2xl font-black text-slate-900 mb-2">How to Get Your Charger Installed</h2>
    <p class="text-slate-500 mb-8">A typical apartment installation takes 2–4 weeks.</p>
    <div class="space-y-4">
      <?php
      $steps = [
        ['1','Write to your RWA','Even though not legally required, send a formal letter requesting NOC. Mention the Ministry of Power 2022 circular. Keep a copy.','2–7 days','📝'],
        ['2','Get an electrical load assessment','Hire a certified electrician to inspect your meter\'s available load capacity. You need at least 5A spare for 2W, 15A for cars.','1 day','⚡'],
        ['3','Apply for DISCOM load increase (if needed)','If current sanctioned load isn\'t enough, apply for enhancement. Submit online via your DISCOM portal.','2–6 weeks','📋'],
        ['4','Plan cable routing','Map the cable path from your meter to parking bay. Underground conduit preferred. Get 3 quotes — costs vary by cable length (₹80–150/metre).','3–5 days','🗺️'],
        ['5','Install charger + safety gear','Certified electrician installs: RCCB, dedicated MCB, earthing, and the charger unit. Get a completion certificate.','1 day','🔧'],
        ['6','Test and document','Run a full charging cycle before paying final bill. Take photos of installation, keep all invoices and certificates.','1 day','✅'],
      ];
      foreach ($steps as $s): ?>
      <div class="guide-card flex gap-4">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-green-600 text-white font-black text-lg flex items-center justify-center"><?= $s[0] ?></div>
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2 flex-wrap">
            <h3 class="font-black text-slate-900 text-sm"><?= $s[4] ?> <?= $s[1] ?></h3>
            <span class="text-xs bg-slate-100 text-slate-600 font-semibold px-2 py-1 rounded-full flex-shrink-0"><?= $s[3] ?></span>
          </div>
          <p class="text-sm text-slate-600 mt-1 leading-relaxed"><?= $s[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- NOC Template -->
  <section>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Sample NOC Request Letter</h2>
    <p class="text-slate-500 mb-4">Copy and customise this for your RWA / society office.</p>
    <div class="guide-card bg-amber-50 border-amber-100">
      <pre class="text-sm text-slate-800 whitespace-pre-wrap font-mono leading-relaxed">To,
The Secretary / President
[Society Name] Residents Welfare Association
[Address]

Subject: Request for NOC — EV Charger Installation at Flat [Your Flat No.]

Dear Sir/Madam,

I am a resident of Flat [No.], [Block], [Society Name]. I own an electric vehicle
([Vehicle Name, Registration No.]) and wish to install a home EV charger in my
designated parking spot [Parking No.].

I propose to draw power from my own electricity meter ([Meter No.]) and bear all
costs of installation, wiring and maintenance. The installation will be done by a
certified electrician and will not draw from common area electricity.

As per Ministry of Power Circular dated 14 January 2022 and the Electricity Act
2003, I am entitled to draw power for EV charging from my own meter without
requiring RWA approval. However, I request an NOC to ensure cooperation on cable
routing through common areas.

Enclosures:
- EV Registration Certificate copy
- Proposed wiring layout
- Electrician's credentials

Thanking you,
[Your Name] | Flat [No.] | [Mobile] | [Date]</pre>
    </div>
  </section>

  <!-- FAQ -->
  <section x-data="{open:null}">
    <h2 class="text-2xl font-black text-slate-900 mb-6">FAQ — Apartment EV Charging</h2>
    <div class="space-y-3">
      <?php
      $faqs = [
        ['My RWA is refusing to grant NOC. What do I do?','Quote the Ministry of Power Circular (January 2022) in writing. If the RWA still refuses, you can file a complaint with your state DISCOM or electricity regulatory commission. The law is on your side as long as you\'re using your own meter.'],
        ['Society wants to charge ₹500/month for EV parking. Is that legal?','RWAs can charge for the use of shared charging infrastructure they install. But they cannot charge extra for your use of your own meter in your own parking spot. If they\'re trying to levy a fee on your personal charging setup, that\'s likely not enforceable.'],
        ['Cable distance from meter to parking is 80 metres. Is that okay?','Yes, but use the correct cable gauge — typically 6mm² or 10mm² copper for runs above 30 metres to avoid voltage drop. Your electrician should calculate this. Cost: ₹80–150/metre for cable + conduit.'],
        ['Can the society cut power to my parking spot?','No. Tampering with your electrical supply without legal authority is an offence under the Electricity Act 2003. Document any threats and notify the DISCOM in writing.'],
        ['I\'m a tenant. Can I still install a charger?','You need your landlord\'s written permission. Most landlords agree since it adds value to the property and you\'re not making structural changes. You take the portable charger with you when you leave.'],
      ];
      foreach ($faqs as $i => $faq): ?>
      <div class="guide-card">
        <button @click="open===<?= $i ?> ? open=null : open=<?= $i ?>" class="w-full flex items-start justify-between gap-4 text-left">
          <span class="font-bold text-slate-900 text-sm"><?= $faq[0] ?></span>
          <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform mt-0.5" :class="open===<?= $i ?>?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open===<?= $i ?>" x-cloak x-transition class="mt-3 pt-3 border-t border-slate-100 text-sm text-slate-600 leading-relaxed"><?= $faq[1] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CTA -->
  <section class="rounded-3xl bg-gradient-to-br from-slate-900 to-teal-900 p-8 text-center text-white">
    <h2 class="text-2xl font-black mb-2">Ready to Make the Switch?</h2>
    <p class="text-slate-300 mb-6 text-sm">Find your perfect EV and locate charging stations near your apartment.</p>
    <div class="flex flex-wrap gap-3 justify-center">
      <a href="<?= base_url('find-my-ev') ?>" class="bg-green-500 hover:bg-green-400 text-white font-bold px-6 py-3 rounded-full text-sm transition-colors">Find My EV ⚡</a>
      <a href="<?= base_url('home-charger-guide') ?>" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-6 py-3 rounded-full text-sm transition-colors">Home Charger Guide</a>
    </div>
  </section>

</div>
</div>

<?= $this->endSection() ?>
