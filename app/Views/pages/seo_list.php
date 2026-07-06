<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
/* ============================================================
   SEO List Page — Reusable ranked EV list
   Charj.in · CI4 · Tailwind CSS · Alpine.js
   Variables:
     $heading    — H1
     $subheading — Subtitle
     $vehicles   — Array of vehicle rows (up to 10)
     $page_slug  — URL slug for canonical / schema
   ============================================================ */

$vehicles   = $vehicles ?? [];
$heading    = $heading ?? 'Best Electric Vehicles in India 2025';
$subheading = $subheading ?? 'Expert-ranked EVs for Indian buyers';
$page_slug  = $page_slug ?? '';

function seoList_fmtINR(int|float|null $n): string {
    if (!$n) return '—';
    $n = (int)$n;
    if ($n >= 10000000) return '₹' . round($n / 10000000, 1) . ' Cr';
    if ($n >= 100000)   return '₹' . round($n / 100000, 1) . ' L';
    return '₹' . number_format($n);
}

$rankColors = [
    1 => ['ring' => 'ring-2 ring-amber-300', 'badge_bg' => 'bg-amber-400', 'badge_txt' => 'text-amber-900', 'header_bg' => 'bg-amber-50 border-b border-amber-100', 'label' => '#1', 'tag' => 'Best Pick', 'tag_cls' => 'bg-amber-400 text-amber-900'],
    2 => ['ring' => '',                       'badge_bg' => 'bg-slate-400', 'badge_txt' => 'text-white',      'header_bg' => 'bg-slate-50 border-b border-slate-100',  'label' => '#2', 'tag' => 'Runner Up', 'tag_cls' => 'bg-blue-100 text-blue-700'],
    3 => ['ring' => '',                       'badge_bg' => 'bg-orange-500','badge_txt' => 'text-white',      'header_bg' => 'bg-slate-50 border-b border-slate-100',  'label' => '#3', 'tag' => 'Best Value','tag_cls' => 'bg-orange-100 text-orange-700'],
];

// FAQ schema
$faqs = [
    ['q' => 'How are these EVs ranked?', 'a' => 'Our ranking is based on claimed range, ex-showroom price, expert rating, real-world owner feedback, charging time, and after-sales service network coverage in Indian cities. We update rankings quarterly.'],
    ['q' => 'What government subsidy can I get on an EV in India?', 'a' => 'FAME II provides up to ₹15,000/kWh subsidy on eligible 2-wheelers and demand incentives for 4-wheelers. State-level subsidies in Delhi (up to ₹30,000), Maharashtra (up to ₹25,000), and Gujarat (up to ₹1.5 lakh on 4W) may also apply. Use our Subsidy Calculator to find your exact benefit.'],
    ['q' => 'What is real-world range vs claimed range?', 'a' => 'Claimed range is ARAI-certified under idealised lab conditions. Real-world range in Indian traffic with AC usage is typically 70–85% of the claimed figure. A scooter claiming 120 km usually delivers 85–100 km in actual city use.'],
    ['q' => 'Is home charging sufficient for daily use?', 'a' => 'Yes — for most city commuters with home parking, overnight charging via a standard 15A socket (or a dedicated wallbox) is completely sufficient. A 3.3kW home charger adds 25–30 km of range per hour.'],
    ['q' => 'What should I check before buying an EV in India?', 'a' => 'Check: real-world range vs your daily commute distance, home charging feasibility, brand service network in your city, battery warranty terms, post-subsidy price, and total 5-year cost of ownership vs a petrol alternative.'],
];
?>

<!-- JSON-LD: FAQ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php foreach ($faqs as $fi => $faq): ?>
    {
      "@type": "Question",
      "name": "<?= esc($faq['q']) ?>",
      "acceptedAnswer": { "@type": "Answer", "text": "<?= esc($faq['a']) ?>" }
    }<?= $fi < count($faqs) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<!-- JSON-LD: ItemList -->
<?php if (!empty($vehicles)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "<?= esc($heading) ?>",
  "url": "<?= current_url() ?>",
  "numberOfItems": <?= count($vehicles) ?>,
  "itemListElement": [
    <?php foreach ($vehicles as $idx => $veh): ?>
    {
      "@type": "ListItem",
      "position": <?= $idx + 1 ?>,
      "url": "<?= site_url('vehicles/' . esc($veh['slug'] ?? '')) ?>",
      "name": "<?= esc(($veh['brand_name'] ?? '') . ' ' . ($veh['name'] ?? '')) ?>"
    }<?= $idx < count($vehicles) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>
<?php endif; ?>

<div class="bg-slate-50 min-h-screen">

  <!-- Breadcrumb -->
  <div class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
      <nav aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5 text-sm text-slate-500 flex-wrap">
          <li><a href="<?= base_url('/') ?>" class="hover:text-charj-green transition-colors">Home</a></li>
          <li aria-hidden="true"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
          <li><a href="<?= base_url('vehicles') ?>" class="hover:text-charj-green transition-colors">Electric Vehicles</a></li>
          <li aria-hidden="true"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
          <li class="text-slate-700 font-medium" aria-current="page"><?= esc($heading) ?></li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Hero -->
  <section class="bg-gradient-to-br from-charj-navy via-[#0f2a45] to-[#1a3a55] text-white py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
      <div class="inline-flex items-center gap-2 bg-charj-green/20 border border-charj-green/40 text-charj-green text-xs font-bold px-4 py-1.5 rounded-full mb-5 uppercase tracking-wide">
        ⚡ Charj.in Expert Picks · Updated <?= date('F Y') ?>
      </div>
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight mb-4"><?= esc($heading) ?></h1>
      <p class="text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto mb-6"><?= esc($subheading) ?></p>
      <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-slate-400">
        <span class="flex items-center gap-1.5">
          <svg class="w-4 h-4 text-charj-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          <?= count($vehicles) ?> vehicles compared
        </span>
        <span class="flex items-center gap-1.5">
          <svg class="w-4 h-4 text-charj-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
          Updated <?= date('d M Y') ?>
        </span>
        <span class="flex items-center gap-1.5">
          <svg class="w-4 h-4 text-charj-green" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
          Expert-reviewed
        </span>
      </div>
    </div>
  </section>

  <div class="max-w-5xl mx-auto px-4 py-10">

    <!-- Ranked list -->
    <?php if (!empty($vehicles)): ?>
    <section aria-labelledby="ranked-list-heading">
      <h2 id="ranked-list-heading" class="text-2xl font-extrabold text-slate-900 mb-6">
        Top <?= count($vehicles) ?> Picks — <?= date('Y') ?>
      </h2>

      <div class="space-y-5 mb-10">
        <?php foreach ($vehicles as $rank => $veh):
          $rank1    = $rank + 1;
          $rc       = $rankColors[$rank1] ?? ['ring' => '', 'badge_bg' => 'bg-slate-200', 'badge_txt' => 'text-slate-700', 'header_bg' => 'bg-slate-50 border-b border-slate-100', 'label' => '#' . $rank1, 'tag' => '', 'tag_cls' => ''];
          $vName    = esc(($veh['brand_name'] ?? '') . ' ' . ($veh['name'] ?? ''));
          $price    = seoList_fmtINR($veh['ex_showroom_price'] ?? $veh['starting_price'] ?? null);
          $range    = !empty($veh['claimed_range']) ? $veh['claimed_range'] . ' km' : '—';
          $battery  = !empty($veh['battery_capacity']) ? $veh['battery_capacity'] . ' kWh' : '—';
          $charging = $veh['charging_time_fast'] ?? $veh['charging_time'] ?? null;
        ?>
        <article
          class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow <?= $rc['ring'] ?>"
          aria-label="Rank <?= $rank1 ?>: <?= $vName ?>"
        >
          <!-- Rank bar -->
          <div class="flex items-center gap-3 px-5 py-3 <?= $rc['header_bg'] ?>">
            <span class="w-10 h-10 flex-shrink-0 <?= $rc['badge_bg'] ?> <?= $rc['badge_txt'] ?> rounded-full flex items-center justify-center text-base font-extrabold shadow-sm">
              <?= $rc['label'] ?>
            </span>
            <div class="flex-1">
              <p class="font-extrabold text-slate-900 text-lg leading-tight"><?= $vName ?></p>
              <?php if (!empty($veh['category_name'])): ?>
              <p class="text-xs text-slate-400 mt-0.5"><?= esc($veh['category_name']) ?></p>
              <?php endif; ?>
            </div>
            <?php if (!empty($rc['tag'])): ?>
            <span class="flex-shrink-0 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider <?= $rc['tag_cls'] ?>"><?= $rc['tag'] ?></span>
            <?php endif; ?>
          </div>

          <div class="flex flex-col sm:flex-row">
            <!-- Image -->
            <div class="sm:w-48 flex-shrink-0 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center p-4 min-h-[130px]">
              <?php if (!empty($veh['image_url'])): ?>
              <img src="<?= esc($veh['image_url']) ?>" alt="<?= $vName ?>" class="max-h-28 w-auto object-contain" loading="lazy">
              <?php else: ?>
              <div class="flex flex-col items-center gap-1 text-slate-400">
                <span class="text-5xl">⚡</span>
                <span class="text-xs font-medium"><?= esc($veh['brand_name'] ?? '') ?></span>
              </div>
              <?php endif; ?>
            </div>

            <!-- Content -->
            <div class="flex-1 p-5">
              <!-- Spec pills -->
              <div class="flex flex-wrap gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">💰 <?= $price ?></span>
                <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">🛣️ <?= $range ?></span>
                <?php if ($battery !== '—'): ?>
                <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full">🔋 <?= $battery ?></span>
                <?php endif; ?>
                <?php if (!empty($charging)): ?>
                <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">⚡ <?= esc($charging) ?></span>
                <?php endif; ?>
                <?php if (!empty($veh['expert_rating'])): ?>
                <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full">★ <?= number_format((float)$veh['expert_rating'], 1) ?>/5</span>
                <?php endif; ?>
              </div>

              <!-- Short description -->
              <?php if (!empty($veh['short_description'])): ?>
              <p class="text-sm text-slate-600 leading-relaxed mb-4 line-clamp-2"><?= esc($veh['short_description']) ?></p>
              <?php endif; ?>

              <!-- CTAs -->
              <div class="flex gap-3 flex-wrap">
                <a href="<?= base_url('vehicles/' . esc($veh['slug'] ?? '')) ?>"
                   class="inline-flex items-center gap-1.5 bg-charj-navy hover:bg-charj-navy-light text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                  View Details →
                </a>
                <a href="<?= base_url('vehicles/' . esc($veh['slug'] ?? '')) ?>#lead-form"
                   class="inline-flex items-center gap-1.5 bg-charj-green hover:bg-charj-green-dark text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                  💰 Get Price
                </a>
              </div>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- How we ranked these -->
    <section class="mb-10 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8" aria-labelledby="methodology-heading">
      <h2 id="methodology-heading" class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-charj-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        How We Ranked These EVs
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-700">
        <div class="flex items-start gap-2.5">
          <span class="w-6 h-6 bg-charj-green/10 text-charj-green rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
          <div><strong class="text-slate-900">Real-world range</strong> — ARAI × 0.75–0.85 correction for Indian conditions</div>
        </div>
        <div class="flex items-start gap-2.5">
          <span class="w-6 h-6 bg-charj-green/10 text-charj-green rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
          <div><strong class="text-slate-900">Total cost of ownership</strong> — 5-year TCO including electricity, maintenance, insurance</div>
        </div>
        <div class="flex items-start gap-2.5">
          <span class="w-6 h-6 bg-charj-green/10 text-charj-green rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
          <div><strong class="text-slate-900">Charging convenience</strong> — Home charger availability, fast charge time, connector type</div>
        </div>
        <div class="flex items-start gap-2.5">
          <span class="w-6 h-6 bg-charj-green/10 text-charj-green rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">4</span>
          <div><strong class="text-slate-900">Service network</strong> — Number of authorised service centres across Indian cities</div>
        </div>
        <div class="flex items-start gap-2.5">
          <span class="w-6 h-6 bg-charj-green/10 text-charj-green rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">5</span>
          <div><strong class="text-slate-900">Owner reviews</strong> — Verified ownership data from community forums and Charj.in users</div>
        </div>
        <div class="flex items-start gap-2.5">
          <span class="w-6 h-6 bg-charj-green/10 text-charj-green rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">6</span>
          <div><strong class="text-slate-900">Value for money</strong> — Post-FAME II subsidy price vs range and feature set</div>
        </div>
      </div>
      <p class="text-xs text-slate-400 mt-5 pt-4 border-t border-slate-100">Rankings updated quarterly. Last updated: <?= date('F Y') ?>. Prices shown are ex-showroom and may vary by state after subsidies.</p>
    </section>

    <!-- Compare CTA -->
    <section class="mb-10">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white flex flex-col sm:flex-row items-center gap-5">
        <div class="flex-1">
          <h3 class="text-lg font-extrabold mb-1">Want to compare two EVs side by side?</h3>
          <p class="text-blue-100 text-sm">Use our interactive comparison tool to compare any two EVs on range, price, charging speed, specs and ownership cost.</p>
        </div>
        <a href="<?= base_url('compare') ?>" class="flex-shrink-0 bg-white text-blue-700 hover:bg-blue-50 font-bold px-6 py-3 rounded-xl transition-colors text-sm whitespace-nowrap">
          ⚖️ Compare Any Two EVs →
        </a>
      </div>
    </section>

    <!-- FAQ -->
    <section class="mb-10" aria-labelledby="faq-heading">
      <h2 id="faq-heading" class="text-2xl font-extrabold text-slate-900 mb-5">Frequently Asked Questions</h2>
      <div class="space-y-3" x-data="{}">
        <?php foreach ($faqs as $fi => $faq): ?>
        <div x-data="{ open: <?= $fi === 0 ? 'true' : 'false' ?> }" class="bg-white border border-slate-200 rounded-xl overflow-hidden">
          <button
            type="button"
            @click="open = !open"
            class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-charj-green"
            :aria-expanded="open"
          >
            <span class="font-semibold text-slate-900 text-sm"><?= esc($faq['q']) ?></span>
            <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div x-show="open" x-transition class="border-t border-slate-100 px-5 py-4">
            <p class="text-sm text-slate-700 leading-relaxed"><?= esc($faq['a']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Lead form -->
    <section aria-labelledby="lead-form-heading">
      <div class="bg-gradient-to-br from-charj-navy to-[#1a3a55] rounded-2xl p-6 lg:p-8">
        <div class="max-w-2xl mx-auto text-center mb-6">
          <span class="text-4xl">🎯</span>
          <h2 id="lead-form-heading" class="text-xl font-extrabold text-white mt-2 mb-2">Need Personalised EV Advice?</h2>
          <p class="text-slate-300 text-sm">Our EV specialists will match you with the perfect EV based on your commute, budget and charging setup — free of charge.</p>
        </div>
        <?= $this->include('partials/lead_form', [
            'formHeading'  => 'Get Free EV Advice',
            'formSubtitle' => 'Tell us your needs and we\'ll recommend the best EV for you',
            'defaultType'  => 'help_me_choose',
            'compactMode'  => false,
        ]) ?>
      </div>
    </section>

    <?php else: ?>
    <!-- No vehicles state -->
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
      <span class="text-6xl block mb-4">🔍</span>
      <h2 class="text-xl font-bold text-slate-800 mb-2">No EVs found for this category yet</h2>
      <p class="text-slate-500 text-sm mb-6">We're continuously adding more vehicles. Check back soon or browse all EVs.</p>
      <a href="<?= base_url('vehicles') ?>" class="inline-flex items-center gap-2 bg-charj-green hover:bg-charj-green-dark text-white font-bold px-6 py-3 rounded-xl transition-colors">
        Browse All EVs →
      </a>
    </div>
    <?php endif; ?>

  </div>
</div>

<?= $this->endSection() ?>
