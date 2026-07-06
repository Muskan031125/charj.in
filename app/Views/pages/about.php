<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero-sm pt-28 pb-20 text-center relative overflow-hidden anim-grad"
         style="background:linear-gradient(135deg,#030712,#04302e,#0a2e2c,#030712)">
  <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.4) 1px,transparent 1px);background-size:20px 20px"></div>
  <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-40 rounded-full pointer-events-none" style="background:#00A896;opacity:.07;filter:blur(60px)"></div>
  <div class="absolute top-8 right-24 w-2 h-2 rounded-full float-1 pointer-events-none" style="background:#00A896;opacity:.3"></div>
  <div class="absolute bottom-12 left-16 w-1.5 h-1.5 rounded-full float-2 pointer-events-none" style="background:#38bdf8;opacity:.25"></div>

  <div class="mx-auto max-w-5xl px-4 relative">
    <span class="inline-block rounded-full px-3 py-1 text-xs sm:text-sm font-bold mb-2 sm:mb-4 text-cyan-300" style="background:rgba(0,168,150,.12);border:1px solid rgba(0,168,150,.25)">About Charj.in</span>
    <h1 class="mt-2 text-4xl font-black leading-tight md:text-5xl text-white">
      India's EV<br>
      <span class="neon-green">Decision Engine</span>
    </h1>
    <p class="mx-auto mt-2 sm:mt-4 max-w-2xl text-sm sm:text-base lg:text-lg" style="color:#8ba3a3">
      We help individuals, families and businesses cut through the noise and make confident, data-backed decisions when buying or switching to electric vehicles in India.
    </p>
  </div>
</section>

<!-- Mission -->
<section class="py-14" style="background:#0f2125;border-bottom:1px solid rgba(255,255,255,.07)">
  <div class="mx-auto max-w-4xl px-4 text-center sr">
    <h2 class="text-2xl font-black md:text-3xl" style="color:#e6f1f1">Our Mission</h2>
    <p class="mx-auto mt-4 max-w-3xl text-base leading-relaxed" style="color:#8ba3a3">
      Electric mobility is the biggest transition in personal transport since the internal combustion engine. Yet buying an EV in India is still confusing — specifications are misleading, subsidies are complex, and range anxiety is real.
      <strong style="color:#e6f1f1"> Charj.in exists to remove that friction.</strong>
      We aggregate real-world data, connect buyers to the right dealers and experts, and make the EV transition faster and simpler for every Indian.
    </p>
  </div>
</section>

<!-- What we offer -->
<section class="py-14" style="background:#0f2125">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center mb-10 sr">
      <h2 class="text-2xl font-black" style="color:#e6f1f1">What We Offer</h2>
      <p class="mt-2" style="color:#8ba3a3">Six ways Charj.in helps you make smarter EV decisions</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 sr-stagger">
      <?php
      $features = [
        ['icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','iconColor'=>'#1AFFCC','iconBg'=>'rgba(0,168,150,.14)','title'=>'EV Comparison Tool','desc'=>'Compare up to 3 EVs side by side across range, price, specs, charging time and real-world performance.'],
        ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','iconColor'=>'rgba(56,189,248,.8)','iconBg'=>'rgba(56,189,248,.1)','title'=>'Verified Dealer Network','desc'=>'Connect with authorised EV dealers in your city. Get actual on-road prices, not just sticker prices.'],
        ['icon'=>'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z','iconColor'=>'rgba(251,191,36,.8)','iconBg'=>'rgba(251,191,36,.1)','title'=>'Running Cost Calculator','desc'=>'Calculate your actual savings: compare EV vs petrol/diesel running cost based on your city, usage and electricity tariff.'],
        ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','iconColor'=>'rgba(45,212,191,.8)','iconBg'=>'rgba(45,212,191,.1)','title'=>'Charging Station Map','desc'=>'Find public charging stations across major Indian cities. Filter by connector type, speed and availability.'],
        ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','iconColor'=>'rgba(168,85,247,.8)','iconBg'=>'rgba(168,85,247,.1)','title'=>'Finance & Subsidy Guide','desc'=>'Navigate FAME II subsidies, state incentives and EV-specific loans from banks and NBFCs.'],
        ['icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','iconColor'=>'rgba(248,113,113,.8)','iconBg'=>'rgba(248,113,113,.1)','title'=>'EV News & Reviews','desc'=>'In-depth EV reviews, latest launches, government policy updates and expert guides — written for the Indian market.'],
      ];
      foreach ($features as $f): ?>
      <div class="rounded-2xl p-6 transition-all duration-300 card-hover" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl" style="background:<?= $f['iconBg'] ?>;border:1px solid <?= $f['iconBg'] ?>">
          <svg class="h-6 w-6" style="color:<?= $f['iconColor'] ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $f['icon'] ?>"/>
          </svg>
        </div>
        <h3 class="mt-4 text-base font-bold" style="color:#e6f1f1"><?= $f['title'] ?></h3>
        <p class="mt-2 text-sm leading-relaxed" style="color:#8ba3a3"><?= $f['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- How it works -->
<section class="py-14" style="background:#0c1a1d">
  <div class="mx-auto max-w-5xl px-4">
    <div class="text-center mb-10 sr">
      <h2 class="text-2xl font-black" style="color:#e6f1f1">How It Works</h2>
      <p class="mt-2" style="color:#8ba3a3">Three simple steps from interest to ownership</p>
    </div>
    <div class="grid gap-8 md:grid-cols-3 sr-stagger">
      <?php foreach ([
        ['num'=>'1','title'=>'Browse & Compare','desc'=>'Search our database of 200+ EVs. Use filters for budget, range, vehicle type and use case. Compare up to 3 models side by side.','color'=>'#00A896'],
        ['num'=>'2','title'=>'Get Personalised Advice','desc'=>'Submit your requirement. Our EV advisors review your use case and recommend the right vehicles and finance options for you.','color'=>'#38bdf8'],
        ['num'=>'3','title'=>'Connect With Dealers','desc'=>'We connect you with verified dealers in your city. Get real on-road prices, test rides, and post-purchase support.','color'=>'#a78bfa'],
      ] as $step): ?>
      <div class="relative text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl text-2xl font-black text-white shadow-lg"
             style="background:<?= $step['color'] ?>;box-shadow:0 0 20px <?= $step['color'] ?>40">
          <?= $step['num'] ?>
        </div>
        <h3 class="mt-4 text-lg font-bold" style="color:#e6f1f1"><?= $step['title'] ?></h3>
        <p class="mt-2 text-sm leading-relaxed" style="color:#8ba3a3"><?= $step['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Built in India -->
<section class="py-14" style="background:linear-gradient(135deg,#04302e,#0a2e3a)">
  <div class="mx-auto max-w-4xl px-4 text-center">
    <h2 class="text-2xl font-black text-white">Built in India, for India</h2>
    <p class="mx-auto mt-4 max-w-2xl" style="color:#94a3b8">
      Charj.in is a product of a team of EV enthusiasts, automotive industry veterans and technology builders.
      We are headquartered in Bangalore and serve EV buyers across all major Indian cities.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <a href="mailto:hello@charj.in" class="flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white transition" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15)" onmouseenter="this.style.background='rgba(255,255,255,.18)'" onmouseleave="this.style.background='rgba(255,255,255,.1)'">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        hello@charj.in
      </a>
      <a href="https://twitter.com/charjin" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white transition" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15)" onmouseenter="this.style.background='rgba(255,255,255,.18)'" onmouseleave="this.style.background='rgba(255,255,255,.1)'">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        @charjin
      </a>
    </div>
  </div>
</section>

<!-- Lead form -->
<section class="py-14" style="background:#0f2125">
  <div class="mx-auto max-w-2xl px-4">
    <h2 class="mb-6 text-center text-2xl font-black" style="color:#e6f1f1">Talk to an EV Expert</h2>
    <?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?>
  </div>
</section>

<?= $this->endSection() ?>
