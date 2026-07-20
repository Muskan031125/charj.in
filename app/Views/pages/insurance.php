<?php
/**
 * EV Insurance Guide Page
 * Variables: $meta_title, $meta_description
 */
$meta_title       = $meta_title       ?? 'EV Insurance in India — Compare Plans & Get Free Quotes | Charj.in';
$meta_description = $meta_description ?? 'Everything you need to know about electric vehicle insurance in India. Compare top insurers, understand battery protection add-ons and get free quotes.';
?>
<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Alpine modal state -->
<div x-data="{ quoteModal: false, selectedInsurer: '' }" @keydown.escape.window="quoteModal = false">

<!-- Hero -->
<section class="hero-sm bg-[#0D2137] pt-12 sm:pt-20 md:pt-32 pb-12 md:pb-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
    <div class="inline-flex items-center gap-2 rounded-full bg-[#22C55E]/20 px-4 py-1.5 text-sm font-semibold text-[#22C55E] mb-4 ring-1 ring-[#22C55E]/30">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
      </svg>
      EV Insurance Guide
    </div>
    <h1 class="text-3xl font-extrabold text-white md:text-4xl lg:text-5xl">
      EV Insurance in India
    </h1>
    <p class="mt-4 text-lg text-slate-300 max-w-2xl mx-auto">
      Electric vehicles have unique insurance needs. Understand the differences, compare top insurers, and get free quotes — all in one place.
    </p>
    <div class="mt-6 flex flex-wrap justify-center gap-3">
      <a href="#get-quotes"
         class="inline-flex items-center gap-2 rounded-xl bg-[#22C55E] px-7 py-3.5 text-base font-bold text-white shadow-lg hover:bg-[#16a34a] transition-colors">
        Get Free Quotes
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
        </svg>
      </a>
      <a href="#coverage-types"
         class="inline-flex items-center rounded-xl bg-white/10 px-7 py-3.5 text-base font-semibold text-white ring-1 ring-white/20 hover:bg-white/20 transition-colors">
        Compare Coverage
      </a>
    </div>
  </div>
</section>

<!-- Why EV Insurance Is Different -->
<section class="py-12 md:py-16 bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <h2 class="text-2xl font-bold text-[#0D2137] md:text-3xl">Why EV Insurance Is Different</h2>
      <p class="mt-2 text-slate-500 max-w-xl mx-auto">Electric vehicles have components and risks that traditional petrol/diesel car insurance doesn't fully cover.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

      <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#22C55E]/10 text-[#22C55E] mb-4">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <h3 class="text-base font-bold text-[#0D2137] mb-2">Battery Pack Coverage</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
          The battery is the most expensive part of an EV — often 40–60% of the vehicle cost. Standard insurance may exclude battery degradation, thermal damage or water damage. You need a dedicated <strong>Battery Protection Add-on</strong> to cover this risk.
        </p>
      </div>

      <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#22C55E]/10 text-[#22C55E] mb-4">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
          </svg>
        </div>
        <h3 class="text-base font-bold text-[#0D2137] mb-2">Higher Repair Costs</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
          EV components like electric motors, power electronics and charging systems require specialised technicians. Repair costs are significantly higher than ICE vehicles, and many authorised service centres are still limited to metros. Insurance IDV must reflect actual market value.
        </p>
      </div>

      <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#22C55E]/10 text-[#22C55E] mb-4">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        </div>
        <h3 class="text-base font-bold text-[#0D2137] mb-2">Charging Equipment Risk</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
          Home chargers and portable charging cables can be damaged by power surges, theft or accidents. Some EV-specific policies now include home charger coverage as a bundled add-on — ask your insurer before buying.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- Coverage Types -->
<section id="coverage-types" class="py-12 md:py-16 bg-slate-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <h2 class="text-2xl font-bold text-[#0D2137] md:text-3xl">Coverage Types Explained</h2>
      <p class="mt-2 text-slate-500 max-w-xl mx-auto">Choose the right level of protection for your electric vehicle.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

      <!-- Third Party -->
      <div class="rounded-2xl bg-white border-2 border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-[#0D2137]">Third-Party</h3>
          <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Mandatory</span>
        </div>
        <p class="text-2xl font-black text-[#0D2137] mb-1">Basic</p>
        <p class="text-sm text-slate-500 mb-5">Required by law under Motor Vehicles Act</p>
        <ul class="space-y-3 mb-6">
          <li class="flex items-start gap-2 text-sm text-slate-600">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Bodily injury / death to third party
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-600">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Property damage up to ₹7.5 lakh
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-400">
            <svg class="h-4 w-4 text-slate-300 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            Own damage not covered
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-400">
            <svg class="h-4 w-4 text-slate-300 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            Battery damage not covered
          </li>
        </ul>
        <p class="text-sm font-semibold text-[#0D2137]">Typical Premium: <span class="text-[#22C55E]">₹2,000 – ₹5,000/yr</span></p>
      </div>

      <!-- Comprehensive -->
      <div class="rounded-2xl bg-[#0D2137] border-2 border-[#22C55E] p-6 relative shadow-xl">
        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
          <span class="rounded-full bg-[#22C55E] px-4 py-1 text-xs font-bold text-white shadow">Recommended</span>
        </div>
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-white">Comprehensive</h3>
          <span class="rounded-full bg-[#22C55E]/20 px-3 py-1 text-xs font-semibold text-[#22C55E]">Popular</span>
        </div>
        <p class="text-2xl font-black text-white mb-1">Complete</p>
        <p class="text-sm text-slate-400 mb-5">Full protection for you and your EV</p>
        <ul class="space-y-3 mb-6">
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            All Third-Party cover included
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Own vehicle damage (accident, theft, fire)
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Natural calamities (flood, earthquake)
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Personal accident cover for owner-driver
          </li>
        </ul>
        <p class="text-sm font-semibold text-slate-300">Typical Premium: <span class="text-[#22C55E]">₹8,000 – ₹25,000/yr</span></p>
      </div>

      <!-- Battery Protection Add-on -->
      <div class="rounded-2xl bg-white border-2 border-[#22C55E]/40 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-[#0D2137]">Battery Protect Add-on</h3>
          <span class="rounded-full bg-[#22C55E]/10 px-3 py-1 text-xs font-semibold text-[#22C55E]">EV-Specific</span>
        </div>
        <p class="text-2xl font-black text-[#0D2137] mb-1">Advanced</p>
        <p class="text-sm text-slate-500 mb-5">Add to Comprehensive for complete EV cover</p>
        <ul class="space-y-3 mb-6">
          <li class="flex items-start gap-2 text-sm text-slate-600">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Battery thermal damage / fire
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-600">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Water ingress / short circuit damage
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-600">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Manufacturing defect (post-warranty)
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-600">
            <svg class="h-4 w-4 text-[#22C55E] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Home charger unit damage
          </li>
        </ul>
        <p class="text-sm font-semibold text-[#0D2137]">Add-on Cost: <span class="text-[#22C55E]">₹1,500 – ₹6,000/yr</span></p>
      </div>

    </div>
  </div>
</section>

<!-- Insurance Providers -->
<section class="py-12 md:py-16 bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <h2 class="text-2xl font-bold text-[#0D2137] md:text-3xl">Top EV Insurance Providers in India</h2>
      <p class="mt-2 text-slate-500 max-w-xl mx-auto">Compare India's leading insurance companies for your electric vehicle.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

      <?php
      $insurers = [
          [
              'name'    => 'Acko',
              'feature' => 'India\'s first digital-native auto insurer. Fastest claim settlement, full online process.',
              'badge'   => 'Digital-First',
              'badge_color' => 'bg-violet-100 text-violet-700',
              'logo'    => 'AC',
              'color'   => 'bg-violet-600',
          ],
          [
              'name'    => 'Tata AIG',
              'feature' => 'Dedicated EV battery protection plan. Wide cashless network with 8,500+ garages.',
              'badge'   => 'Battery Cover',
              'badge_color' => 'bg-blue-100 text-blue-700',
              'logo'    => 'TA',
              'color'   => 'bg-blue-700',
          ],
          [
              'name'    => 'HDFC ERGO',
              'feature' => 'Comprehensive EV-specific plan with roadside assistance & zero-depreciation option.',
              'badge'   => 'Zero Dep Available',
              'badge_color' => 'bg-sky-100 text-sky-700',
              'logo'    => 'HE',
              'color'   => 'bg-sky-700',
          ],
          [
              'name'    => 'New India Assurance',
              'feature' => 'Government-backed PSU insurer. Widest India-wide network. Trusted for 100+ years.',
              'badge'   => 'PSU Backed',
              'badge_color' => 'bg-orange-100 text-orange-700',
              'logo'    => 'NI',
              'color'   => 'bg-orange-600',
          ],
          [
              'name'    => 'Bajaj Allianz',
              'feature' => 'Motor OD cover with EV add-ons. 24x7 motor assistance and quick claim turnaround.',
              'badge'   => '24x7 Assist',
              'badge_color' => 'bg-emerald-100 text-emerald-700',
              'logo'    => 'BA',
              'color'   => 'bg-emerald-600',
          ],
          [
              'name'    => 'ICICI Lombard',
              'feature' => 'Instant EV insurance online. InstaSpect for quick claim inspection via mobile app.',
              'badge'   => 'Instant Policy',
              'badge_color' => 'bg-amber-100 text-amber-700',
              'logo'    => 'IL',
              'color'   => 'bg-amber-600',
          ],
      ];
      foreach ($insurers as $ins):
      ?>
      <div class="rounded-2xl bg-white border border-slate-200 p-5 hover:shadow-md hover:border-[#22C55E]/40 transition-all duration-200 flex flex-col">
        <div class="flex items-start gap-4 mb-4">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl <?= $ins['color'] ?> shadow-sm">
            <span class="text-sm font-black text-white"><?= $ins['logo'] ?></span>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-[#0D2137]"><?= esc($ins['name']) ?></h3>
            <span class="inline-block mt-1 rounded-full <?= $ins['badge_color'] ?> px-2.5 py-0.5 text-xs font-semibold"><?= esc($ins['badge']) ?></span>
          </div>
        </div>
        <p class="text-sm text-slate-600 leading-relaxed flex-1"><?= esc($ins['feature']) ?></p>
        <button
          @click="quoteModal = true; selectedInsurer = '<?= esc($ins['name']) ?>'"
          class="mt-4 w-full rounded-xl bg-[#0D2137] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#22C55E] transition-colors">
          Get Quote from <?= esc($ins['name']) ?>
        </button>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- Tips for Lower Premium -->
<section class="py-12 md:py-16 bg-slate-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <h2 class="text-2xl font-bold text-[#0D2137] md:text-3xl">5 Tips to Lower Your EV Insurance Premium</h2>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3 max-w-5xl mx-auto">

      <?php
      $tips = [
          [
              'num'  => '01',
              'title'=> 'Build Your No-Claim Bonus',
              'text' => 'Each claim-free year earns you a No-Claim Bonus (NCB) of up to 50%. Avoid small claims and let your NCB compound — it\'s the single biggest discount available.',
          ],
          [
              'num'  => '02',
              'title'=> 'Choose a Higher Voluntary Deductible',
              'text' => 'Agreeing to pay a higher amount in the event of a claim reduces your annual premium significantly. Suitable if you\'re a safe driver with low accident risk.',
          ],
          [
              'num'  => '03',
              'title'=> 'Install an Approved Anti-Theft Device',
              'text' => 'ARAI-approved GPS trackers and immobilisers can reduce your comprehensive premium. Insurers reward proactive security measures.',
          ],
          [
              'num'  => '04',
              'title'=> 'Bundle Add-ons Smartly',
              'text' => 'Don\'t buy every add-on blindly. Zero-depreciation and battery protection are highly valuable for EVs; roadside assist may already come free with your EV warranty.',
          ],
          [
              'num'  => '05',
              'title'=> 'Compare Online Before Renewing',
              'text' => 'Never auto-renew without comparing. Online portals often offer 10–20% lower premiums than offline agents. Use Charj.in\'s free quote comparison to find the best deal.',
          ],
      ];
      foreach ($tips as $tip):
      ?>
      <div class="rounded-2xl bg-white border border-slate-100 p-5 shadow-sm flex gap-4">
        <span class="text-3xl font-black text-[#22C55E]/30 leading-none pt-0.5 shrink-0"><?= $tip['num'] ?></span>
        <div>
          <h3 class="text-sm font-bold text-[#0D2137] mb-1.5"><?= esc($tip['title']) ?></h3>
          <p class="text-sm text-slate-600 leading-relaxed"><?= esc($tip['text']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- Lead Form Section -->
<section id="get-quotes" class="py-12 md:py-16 bg-[#0D2137]">
  <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
      <h2 class="text-2xl font-bold text-white md:text-3xl">Get EV Insurance Quotes — Free Comparison</h2>
      <p class="mt-3 text-slate-300">Fill the form below and our insurance experts will compare quotes from top insurers and send you the best option within 2 hours.</p>
    </div>

    <div class="rounded-2xl bg-white p-6 md:p-8 shadow-2xl">
      <form action="/leads/store" method="post" class="space-y-5">
        <?= csrf_field() ?>
        <input type="hidden" name="source" value="insurance_page">

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div>
            <label for="ins_phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number *</label>
            <div class="flex">
              <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 px-3 text-sm text-slate-500">+91</span>
              <input type="tel" id="ins_phone" name="phone" required placeholder="98765 43210"
                     maxlength="10" minlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric"
                     oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
                     class="flex-1 rounded-r-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div>
            <label for="ins_email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
            <input type="email" id="ins_email" name="email" placeholder="you@example.com"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
          </div>
          <div>
            <label for="ins_city" class="block text-sm font-medium text-slate-700 mb-1">City</label>
            <input type="text" id="ins_city" name="city" placeholder="e.g. Pune"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
          </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div>
            <label for="ins_vehicle" class="block text-sm font-medium text-slate-700 mb-1">Your EV Model</label>
            <input type="text" id="ins_vehicle" name="vehicle_model" placeholder="e.g. Tata Nexon EV"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
          </div>
          <div>
            <label for="ins_type" class="block text-sm font-medium text-slate-700 mb-1">Coverage Needed</label>
            <select id="ins_type" name="insurance_type"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
              <option value="">Select coverage type</option>
              <option value="third_party">Third-Party Only</option>
              <option value="comprehensive">Comprehensive</option>
              <option value="comprehensive_battery">Comprehensive + Battery Protect</option>
              <option value="not_sure">Not sure — need guidance</option>
            </select>
          </div>
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-[#22C55E] px-5 py-3.5 text-base font-bold text-white shadow-lg hover:bg-[#16a34a] transition-colors">
          Get Free Insurance Quotes Now
        </button>
        <p class="text-center text-xs text-slate-400">No spam. No hidden charges. Your information is 100% secure.</p>
      </form>
    </div>
  </div>
</section>

<!-- Quote Modal (Alpine.js) -->
<div x-show="quoteModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
     x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div @click.outside="quoteModal = false"
       class="w-full max-w-md rounded-2xl bg-white shadow-2xl p-6"
       x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="flex items-center justify-between mb-5">
      <h3 class="text-lg font-bold text-[#0D2137]">Get Quote — <span x-text="selectedInsurer"></span></h3>
      <button @click="quoteModal = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 transition-colors">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form action="/leads/store" method="post" class="space-y-4">
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="insurance_modal">
      <input type="hidden" name="insurance_provider" x-bind:value="selectedInsurer">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Phone *</label>
        <input type="tel" name="phone" required placeholder="10-digit mobile number"
               maxlength="10" minlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric"
               oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">EV Model</label>
        <input type="text" name="vehicle_model" placeholder="e.g. Ather 450X"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
      </div>
      <button type="submit"
              class="w-full rounded-xl bg-[#22C55E] px-5 py-3 text-sm font-bold text-white hover:bg-[#16a34a] transition-colors">
        Request Quote
      </button>
    </form>
  </div>
</div>

</div><!-- end x-data -->

<?= $this->endSection() ?>
