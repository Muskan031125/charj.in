<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Free EV Tools & Calculators | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? '') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<div class="hero-sm relative overflow-hidden pt-24 pb-10 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.1)">
  <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:28px 28px;opacity:.6"></div>
  <div class="relative max-w-4xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-4"
         style="background:rgba(0,168,150,.1);border:1.5px solid rgba(0,168,150,.2);color:#00A896">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
      Free Tools & Calculators
    </div>
    <h1 class="text-3xl sm:text-4xl font-black leading-tight mb-3" style="color:#0F172A">
      Every EV tool you need,<br><span style="color:#00A896">all in one place</span>
    </h1>
    <p class="max-w-xl mx-auto text-sm sm:text-base leading-relaxed" style="color:#475569">
      10+ free tools to calculate subsidies, compare costs, check trip range, estimate resale value and find your perfect EV — no sign-up required.
    </p>
    <!-- Stats row -->
    <div class="flex flex-wrap justify-center gap-6 mt-6">
      <?php foreach ([['10+','Free Tools'],['₹0','Cost'],['100%','Accurate'],['18','States Covered']] as [$v,$l]): ?>
      <div class="text-center">
        <div class="font-black text-xl" style="color:#00A896"><?= $v ?></div>
        <div class="text-[10px] font-semibold uppercase tracking-wider" style="color:#94A3B8"><?= $l ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Tools Grid -->
<div class="max-w-6xl mx-auto px-4 py-10">

  <!-- Section: EV Intelligence -->
  <div class="mb-10 sr">
    <div class="flex items-center gap-3 mb-5">
      <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(0,168,150,.1);border:1px solid rgba(0,168,150,.2)">
        <svg class="w-4 h-4" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
      </div>
      <h2 class="text-lg font-black" style="color:#0F172A">EV Intelligence</h2>
      <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(0,168,150,.2),transparent)"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sr-stagger">
      <?php
      $intel = [
        ['🎯','EV Finder Quiz','Answer 7 quick questions and get personalised recommendations for your budget, range needs and lifestyle.','find-my-ev','Most Popular','#00A896'],
        ['🔍','Browse All EVs','Explore 150+ electric vehicles with full specs, owner reviews, subsidy prices and side-by-side comparison.','vehicles','150+ EVs','#0EA5E9'],
        ['⚖️','Compare EVs','Pick any 2-4 EVs and compare 30+ spec points side-by-side — range, battery, charging speed and price.','compare','Side by Side','#8B5CF6'],
      ];
      foreach ($intel as [$em,$t,$d,$url,$badge,$bc]): ?>
      <a href="<?= base_url($url) ?>" class="group card-hover flex flex-col p-5 rounded-2xl">
        <div class="flex items-start justify-between mb-3">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0" style="background:rgba(0,168,150,.08);border:1.5px solid rgba(0,168,150,.12)"><?= $em ?></div>
          <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full text-white" style="background:<?= $bc ?>"><?= $badge ?></span>
        </div>
        <div class="font-black text-base mb-1" style="color:#0F172A"><?= $t ?></div>
        <div class="text-xs leading-relaxed flex-1" style="color:#64748B"><?= $d ?></div>
        <div class="flex items-center gap-1 mt-3 text-xs font-bold" style="color:#00A896">
          Open tool <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Section: Calculators -->
  <div class="mb-10 sr">
    <div class="flex items-center gap-3 mb-5">
      <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.18)">
        <svg class="w-4 h-4" style="color:#6366F1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-3M16 5l3 3m0 0l-8 8m8-8H11"/></svg>
      </div>
      <h2 class="text-lg font-black" style="color:#0F172A">Calculators</h2>
      <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(99,102,241,.2),transparent)"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sr-stagger">
      <?php
      $calcs = [
        ['🛣️','On-Road Price','Real on-road price by state — ex-showroom + RTO + insurance, with PM E-DRIVE & state subsidy auto-deducted.','on-road-price','New','#00A896'],
        ['🎁','Subsidy Calculator','See exactly how much you save with PM E-DRIVE + state government subsidies. Covers all states.','subsidy-calculator','Save ₹1.5L','#00963C'],
        ['💰','Savings vs Petrol','Compare 5-year total cost of EV ownership vs petrol — fuel, maintenance, insurance and resale.','tco-calculator','5-Year View','#6366F1'],
        ['📊','EMI Calculator','Calculate monthly EMI for any EV at any loan amount, rate and tenure. Includes processing fee.','ev-emi-calculator','Instant','#0EA5E9'],
        ['🏢','Fleet ROI Calculator','Calculate exact savings from switching your fleet to electric. FAME II commercial subsidy included.','fleet-calculator','For Business','#D97706'],
        ['⚡','Running Cost','See monthly running cost of your EV vs an equivalent petrol vehicle based on your actual usage.','ev-savings-calculator','Per Month','#00A896'],
        ['💵','Cost Calculator','Full breakdown of EV ownership cost including ex-showroom, on-road, registration and insurance.','ev-cost-calculator','Total Cost','#8B5CF6'],
        ['🛡️','Insurance Estimator','Estimate your annual EV insurance premium — IDV, own-damage, third-party, add-ons & NCB with the EV discount.','ev-insurance-calculator','New','#0EA5E9'],
      ];
      foreach ($calcs as [$em,$t,$d,$url,$badge,$bc]): ?>
      <a href="<?= base_url($url) ?>" class="group card-hover flex flex-col p-5 rounded-2xl">
        <div class="flex items-start justify-between mb-3">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0" style="background:rgba(99,102,241,.07);border:1.5px solid rgba(99,102,241,.12)"><?= $em ?></div>
          <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full text-white" style="background:<?= $bc ?>"><?= $badge ?></span>
        </div>
        <div class="font-black text-base mb-1" style="color:#0F172A"><?= $t ?></div>
        <div class="text-xs leading-relaxed flex-1" style="color:#64748B"><?= $d ?></div>
        <div class="flex items-center gap-1 mt-3 text-xs font-bold" style="color:#6366F1">
          Open calculator <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Section: Trip & Charging Tools -->
  <div class="mb-10 sr">
    <div class="flex items-center gap-3 mb-5">
      <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.18)">
        <svg class="w-4 h-4" style="color:#D97706" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      </div>
      <h2 class="text-lg font-black" style="color:#0F172A">Trip & Charging Tools</h2>
      <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(245,158,11,.2),transparent)"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sr-stagger">
      <?php
      $trip = [
        ['🧭','Trip Charging Planner','Plan charging stops for any intercity route — how many stops, where & how long.','trip-planner'],
        ['🗺️','Trip Range Check','Can your EV complete this trip? Enter distance and charge level to check.','can-i-make-it'],
        ['⚡','Charging Cost','See exactly how much it costs to charge your EV at home vs a public charger.','charging-cost'],
        ['🔌','Charger Compatibility','Check which charger types work with your EV and how fast each charges it.','charger-check'],
        ['🏠','Charging Stations','Live map of all public EV charging stations near you with speed filters.','charging-stations'],
      ];
      foreach ($trip as [$em,$t,$d,$url]): ?>
      <a href="<?= base_url($url) ?>" class="group card-hover flex flex-col p-4 rounded-2xl">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-3" style="background:rgba(245,158,11,.08);border:1.5px solid rgba(245,158,11,.12)"><?= $em ?></div>
        <div class="font-black text-sm mb-1" style="color:#0F172A"><?= $t ?></div>
        <div class="text-xs leading-relaxed flex-1" style="color:#64748B"><?= $d ?></div>
        <div class="flex items-center gap-1 mt-3 text-xs font-bold" style="color:#D97706">
          Open <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Section: Market Tools -->
  <div class="mb-10 sr">
    <div class="flex items-center gap-3 mb-5">
      <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(14,165,233,.08);border:1px solid rgba(14,165,233,.18)">
        <svg class="w-4 h-4" style="color:#0EA5E9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
      </div>
      <h2 class="text-lg font-black" style="color:#0F172A">Market & Resale</h2>
      <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(14,165,233,.2),transparent)"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sr-stagger">
      <?php
      $market = [
        ['💎','Used EV Valuation','Estimate a used EV\'s resale value by age, km, battery health & condition.','used-ev-value'],
        ['📈','Resale Estimator','Estimate your EV\'s future resale value based on age, km driven and brand.','resale-estimator'],
        ['🔋','Battery Replace Cost','Calculate cost of replacing your EV battery pack — brand-wise estimates.','battery-cost'],
        ['📊','EV Sales & Trends','India EV sales data — monthly registrations, top brands and leading states.','ev-sales-trends'],
        ['🛒','Used EV Guide','How to buy a used EV safely. Checklist, battery health, ownership transfer.','used-ev'],
        ['🏦','EV Finance','NBFC & bank loan options for EV purchase. Compare interest rates and EMIs.','ev-finance'],
      ];
      foreach ($market as [$em,$t,$d,$url]): ?>
      <a href="<?= base_url($url) ?>" class="group card-hover flex flex-col p-4 rounded-2xl">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-3" style="background:rgba(14,165,233,.07);border:1.5px solid rgba(14,165,233,.12)"><?= $em ?></div>
        <div class="font-black text-sm mb-1" style="color:#0F172A"><?= $t ?></div>
        <div class="text-xs leading-relaxed flex-1" style="color:#64748B"><?= $d ?></div>
        <div class="flex items-center gap-1 mt-3 text-xs font-bold" style="color:#0EA5E9">
          Open <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Section: Guides -->
  <div class="sr">
    <div class="flex items-center gap-3 mb-5">
      <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(139,92,246,.08);border:1px solid rgba(139,92,246,.18)">
        <svg class="w-4 h-4" style="color:#8B5CF6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
      </div>
      <h2 class="text-lg font-black" style="color:#0F172A">Guides & Resources</h2>
      <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(139,92,246,.2),transparent)"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sr-stagger">
      <?php
      $guides = [
        ['⏱️','EV Waiting Periods','Current booking-to-delivery timelines for popular EV models across India.','ev-waiting-periods'],
        ['🏠','Home Charger Guide','Everything about installing a home EV charger — cost, brands, types, installation process.','home-charger-guide'],
        ['🏢','EV for Apartments','Charging solutions for flat dwellers. Society rules, portable chargers, nearby stations.','ev-for-apartment'],
        ['📖','EV Glossary','200+ EV terms explained in plain English — from BMS to V2G.','ev-glossary'],
        ['📰','EV News','Latest launches, reviews, policy updates and industry news from India\'s EV sector.','news'],
      ];
      foreach ($guides as [$em,$t,$d,$url]): ?>
      <a href="<?= base_url($url) ?>" class="group card-hover flex flex-col p-4 rounded-2xl">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-3" style="background:rgba(139,92,246,.07);border:1.5px solid rgba(139,92,246,.12)"><?= $em ?></div>
        <div class="font-black text-sm mb-1" style="color:#0F172A"><?= $t ?></div>
        <div class="text-xs leading-relaxed flex-1" style="color:#64748B"><?= $d ?></div>
        <div class="flex items-center gap-1 mt-3 text-xs font-bold" style="color:#8B5CF6">
          Read guide <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- Bottom CTA -->
<section class="py-8 px-4 text-center sr" style="background:linear-gradient(135deg,#00A896,#007A6E)">
  <div class="max-w-xl mx-auto">
    <div class="text-2xl font-black text-white mb-2">Not sure which EV to buy?</div>
    <p class="text-sm mb-5" style="color:rgba(255,255,255,.75)">Answer 7 quick questions and get a personalised EV recommendation based on your budget, range and lifestyle.</p>
    <a href="<?= base_url('find-my-ev') ?>" class="inline-flex items-center gap-2 font-bold text-sm px-6 py-3 rounded-full transition-all duration-200"
       style="background:#FFFFFF;color:#007A6E;box-shadow:0 4px 16px rgba(0,0,0,.12)"
       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
      Take the EV Finder Quiz
    </a>
  </div>
</section>

<?= $this->endSection() ?>
