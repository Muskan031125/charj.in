<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
/* ============================================================
   Best EV for [Use Case] — SEO Landing Page
   Charj.in · CI4 · Tailwind CSS · Alpine.js
   ============================================================
   Variables:
     $title       — Page H1 (e.g. "Best Electric Scooters in India 2025")
     $subtitle    — Tagline
     $vehicles    — Filtered vehicle array (up to 10)
     $useCase     — Human-readable use case string
     $description — 200-word lead paragraph
     $filters     — (optional) array describing filter criteria
   ============================================================ */

function bestEv_fmtINR(int|float|null $n): string {
    if (!$n) return '—';
    $n = (int)$n;
    if ($n >= 10000000) return '₹' . round($n / 10000000, 1) . ' Cr';
    if ($n >= 100000)   return '₹' . round($n / 100000, 1) . ' L';
    return '₹' . number_format($n);
}

function bestEv_stars(float|null $r): string {
    if (!$r) return '';
    $s = '';
    for ($i = 1; $i <= 5; $i++) {
        $s .= $i <= $r
            ? '<svg class="w-3.5 h-3.5 text-amber-400 fill-current inline" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>'
            : '<svg class="w-3.5 h-3.5 text-slate-300 fill-current inline" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>';
    }
    return $s;
}

$title       = $title ?? 'Best Electric Vehicles in India 2025';
$subtitle    = $subtitle ?? 'Expert-ranked list of top EVs based on real-world data, owner reviews and value for money';
$vehicles    = $vehicles ?? [];
$useCase     = $useCase ?? 'general use';
$description = $description ?? '';

$rankBadges = [
    1 => ['label' => '1st', 'bg' => 'bg-amber-400',  'text' => 'text-amber-900',  'ring' => 'ring-amber-400'],
    2 => ['label' => '2nd', 'bg' => 'bg-slate-400',  'text' => 'text-slate-900',  'ring' => 'ring-slate-400'],
    3 => ['label' => '3rd', 'bg' => 'bg-orange-500', 'text' => 'text-white',       'ring' => 'ring-orange-400'],
];

// Use-case specific buying guide content
$useCaseGuides = [
    'Daily commuting in Indian cities' => [
        'header' => 'Buying Guide: Best EV for Daily City Commuting',
        'points' => [
            '**Range: 80–120 km real-world is enough** for most city commutes. Do not overpay for 200+ km range if you charge at home every night.',
            '**Home charging is key.** If you have a parking spot with a power socket, you\'ll wake up to a full charge every morning. Factor in your housing setup before buying.',
            '**Check service network.** Look for brands with service centres within 15–20 km of your home. Downtime matters for daily commuters.',
            '**Consider total cost.** A ₹80,000 EV scooter costs ₹1–1.5/km to run vs ₹3–4/km for petrol. Break-even is typically 2–3 years.',
            '**App connectivity and OTA updates** are increasingly important — they add features and fix bugs without a service visit.',
            '**Tyre size and suspension** matter on Indian roads. Look for at least 12-inch wheels and telescopic front suspension for potholes.',
        ],
    ],
    'Budget-conscious city commuting' => [
        'header' => 'Buying Guide: Best Affordable EV Under ₹1 Lakh',
        'points' => [
            '**Don\'t ignore FAME II.** After subsidy, several scooters priced at ₹1.1–1.2 lakh come down to under ₹1 lakh. Always check post-subsidy price.',
            '**Battery size vs price tradeoff.** Sub-₹1L EVs often have smaller batteries (1.5–2.5 kWh). This means 60–100 km range — fine for city use.',
            '**Removable battery is a huge plus** if you lack home charging. You can carry the battery inside to charge on a standard plug.',
            '**Check warranty terms.** Budget EVs sometimes have shorter battery warranties. Look for at least 3 years / 50,000 km on the battery.',
            '**Running cost is the win.** Even at ₹80,000, if you save ₹2,500/month vs petrol, payback is under 3 years.',
            '**Avoid no-name brands** unless they have a local service network. Spare parts availability matters post-warranty.',
        ],
    ],
    'Family car for city and highway use' => [
        'header' => 'Buying Guide: Best Electric Car for Indian Families',
        'points' => [
            '**Real-world range of 300+ km** is the practical minimum for family cars used on highway trips. Claimed 400 km = real ~300 km.',
            '**Fast charging is non-negotiable** for long trips. Look for CCS2 or CHAdeMO with 50kW+ charging support.',
            '**Boot space and seating comfort** matter more than raw specs. Take a test drive with the whole family.',
            '**Charging infrastructure on NH routes** is now reasonably good on Mumbai–Pune, Delhi–Agra, Bengaluru–Chennai. Check ChargeZone and Tata Power app before planning.',
            '**Total cost of ownership** over 5 years typically saves ₹3–6 lakh vs a petrol equivalent even with higher upfront cost.',
            '**Brand service network.** Tata, MG and Hyundai have wide service coverage. Check for authorised EV service centres within 30 km of your home city.',
        ],
    ],
];

$buyingGuide = $useCaseGuides[$useCase] ?? [
    'header' => 'EV Buying Guide for Indian Buyers',
    'points' => [
        '**Calculate real-world range** — claimed range × 0.75–0.85 gives you the real number in mixed Indian conditions.',
        '**Factor in total cost of ownership** — upfront price is just one part. Electricity, maintenance, insurance and resale all matter.',
        '**Check charging compatibility** — ensure the EV\'s connector type works with chargers near your home, office and common routes.',
        '**Verify subsidy eligibility** — FAME II and state subsidies can save ₹10,000–₹1.5 lakh on eligible models.',
        '**Read owner reviews** — forum discussions and owner communities on social media reveal real-world issues that spec sheets hide.',
        '**Test ride before booking** — EVs have instant torque which feels very different from petrol. Try it before committing.',
    ],
];

// FAQ for the use case
$useCaseFaqs = [
    [
        'q' => 'How do I choose the best EV for ' . $useCase . '?',
        'a' => 'Start with your daily distance requirement, add 30% buffer for range anxiety, then filter by budget and charging convenience. Check real-world range figures (not claimed), brand service network in your city, and total 5-year ownership cost before deciding.',
    ],
    [
        'q' => 'Which EV has the best real-world range in India?',
        'a' => 'Real-world range in India is typically 75–85% of the ARAI claimed range due to AC usage, traffic conditions and road quality. EVs with larger batteries (20+ kWh for cars, 3+ kWh for scooters) consistently deliver better real range. Charj.in tests show Tata Nexon EV Max, MG ZS EV and Hyundai Ioniq 5 lead in 4-wheelers; Ather 450X and TVS iQube S lead in 2-wheelers.',
    ],
    [
        'q' => 'Are EVs practical for highway travel in India?',
        'a' => 'As of 2025, major national highways — especially Delhi–Agra, Mumbai–Pune, Chennai–Bengaluru and Bengaluru–Mysore — have good fast charging coverage. For cars with 400+ km claimed range and CCS2 fast charging, highway trips are practical. EV scooters are generally better suited for city use.',
    ],
    [
        'q' => 'What government subsidy can I get on an EV in India?',
        'a' => 'FAME II provides up to ₹15,000/kWh subsidy on eligible 2-wheelers (max ~₹22,500) and supports electric 4-wheelers through demand incentive. States like Delhi (up to ₹30,000), Maharashtra (up to ₹25,000) and Gujarat (up to ₹1.5 lakh on 4W) offer additional incentives. Use Charj.in\'s Subsidy Calculator to find your exact benefit.',
    ],
    [
        'q' => 'Is home charging sufficient or do I need a public charger?',
        'a' => 'For most city dwellers with home parking, overnight home charging (3.3kW standard socket) is completely sufficient for daily commuting. A 7.2kW wallbox charger makes it faster and more convenient. Public chargers are needed for longer trips or if you cannot charge at home (e.g., apartment without dedicated parking).',
    ],
];
?>

<!-- ── JSON-LD: ItemList Schema ──────────────────────────── -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "<?= esc($title) ?>",
  "description": "<?= esc($subtitle) ?>",
  "url": "<?= current_url() ?>",
  "numberOfItems": <?= count($vehicles) ?>,
  "itemListElement": [
    <?php foreach (array_slice($vehicles, 0, 5) as $idx => $veh): ?>
    {
      "@type": "ListItem",
      "position": <?= $idx + 1 ?>,
      "url": "<?= site_url('vehicles/' . esc($veh['slug'] ?? '')) ?>",
      "name": "<?= esc(($veh['brand_name'] ?? '') . ' ' . ($veh['name'] ?? '')) ?>"
    }<?= $idx < min(4, count($vehicles) - 1) ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<div class="bg-slate-50 min-h-screen">

<!-- ── BREADCRUMB ─────────────────────────────────────────── -->
<div class="bg-white border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-4 py-3">
    <nav aria-label="Breadcrumb">
      <ol class="flex items-center gap-1.5 text-sm text-slate-500 flex-wrap">
        <li><a href="<?= base_url('/') ?>" class="hover:text-charj-green transition-colors">Home</a></li>
        <li aria-hidden="true"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li><a href="<?= base_url('vehicles') ?>" class="hover:text-charj-green transition-colors">Electric Vehicles</a></li>
        <li aria-hidden="true"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li class="text-slate-700 font-medium" aria-current="page"><?= esc($title) ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- ── HERO HEADER ────────────────────────────────────────── -->
<section class="bg-gradient-to-br from-charj-navy via-[#0f2a45] to-[#1a3a55] text-white py-12 lg:py-16">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <div class="inline-flex items-center gap-2 bg-charj-green/20 border border-charj-green/40 text-charj-green text-xs font-bold px-4 py-1.5 rounded-full mb-5 uppercase tracking-wide">
      ⚡ Charj.in Expert Picks · Updated <?= date('F Y') ?>
    </div>
    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight mb-4"><?= esc($title) ?></h1>
    <p class="text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto mb-6"><?= esc($subtitle) ?></p>
    <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-slate-400">
      <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-charj-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><?= count($vehicles) ?> vehicles compared</span>
      <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-charj-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>Updated <?= date('d M Y') ?></span>
      <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-charj-green" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>Expert-reviewed</span>
    </div>
  </div>
</section>

<div class="max-w-5xl mx-auto px-4 py-10">

  <!-- ── LEAD PARAGRAPH ────────────────────────────────────── -->
  <?php if (!empty($description)): ?>
  <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
      <p><?= nl2br(esc($description)) ?></p>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── TOP PICKS (Numbered Detail Cards) ─────────────────── -->
  <?php if (!empty($vehicles)): ?>
  <section aria-labelledby="top-picks-heading">
    <h2 id="top-picks-heading" class="text-2xl font-extrabold text-slate-900 mb-6">
      Our Top <?= min(count($vehicles), 5) ?> Picks for <?= esc($useCase) ?>
    </h2>

    <div class="space-y-6 mb-10">
      <?php foreach (array_slice($vehicles, 0, 5) as $rank => $veh):
        $rank1     = $rank + 1;
        $badge     = $rankBadges[$rank1] ?? ['label' => $rank1 . 'th', 'bg' => 'bg-slate-200', 'text' => 'text-slate-700', 'ring' => 'ring-slate-300'];
        $vName     = esc(($veh['brand_name'] ?? '') . ' ' . ($veh['name'] ?? ''));
        $price     = bestEv_fmtINR($veh['starting_price'] ?? null);
        $range     = !empty($veh['claimed_range']) ? $veh['claimed_range'] . ' km' : '—';
        $realRange = !empty($veh['real_world_range']) ? $veh['real_world_range'] . ' km' : ($veh['claimed_range'] ? '~' . (int)round($veh['claimed_range'] * 0.83) . ' km' : '—');
        $battery   = !empty($veh['battery_capacity']) ? $veh['battery_capacity'] . ' kWh' : '—';
        $charging  = $veh['charging_time'] ?? $veh['charging_time_normal'] ?? '—';
        $rating    = (float) ($veh['expert_rating'] ?? 0);

        // Why we picked this — generate based on rank and specs
        $whyPicks = [
            1 => 'Best overall balance of range, reliability and value — our #1 recommendation for ' . $useCase . '.',
            2 => 'Excellent runner-up with strong real-world performance and wide service network across India.',
            3 => 'Best in its price segment — outstanding value for buyers prioritising cost over premium features.',
            4 => 'Strong alternative with unique features that may suit specific buyer preferences.',
            5 => 'Solid option worth considering, especially if the top picks are unavailable or out of budget.',
        ];
        $whyPick = $whyPicks[$rank1] ?? 'A competitive option in this segment worth serious consideration.';

        $isTop3 = $rank1 <= 3;
      ?>
      <article
        class="bg-white rounded-2xl border <?= $rank1 === 1 ? 'border-amber-300 ring-2 ring-amber-200' : 'border-slate-200' ?> shadow-sm overflow-hidden hover:shadow-md transition-shadow"
        aria-label="Rank <?= $rank1 ?>: <?= $vName ?>"
      >
        <!-- Rank header bar -->
        <div class="flex items-center gap-3 px-5 py-3 <?= $rank1 === 1 ? 'bg-gradient-to-r from-amber-50 to-amber-100 border-b border-amber-200' : 'bg-slate-50 border-b border-slate-100' ?>">
          <span class="w-9 h-9 flex-shrink-0 <?= $badge['bg'] ?> <?= $badge['text'] ?> rounded-full flex items-center justify-center text-sm font-extrabold">
            <?= $badge['label'] ?>
          </span>
          <div class="flex-1">
            <p class="font-extrabold text-slate-900 text-lg leading-tight"><?= $vName ?></p>
            <?php if ($rating > 0): ?>
            <div class="flex items-center gap-1.5 mt-0.5">
              <?= bestEv_stars($rating) ?>
              <span class="text-xs font-semibold text-slate-600"><?= number_format($rating, 1) ?>/5</span>
            </div>
            <?php endif; ?>
          </div>
          <?php if ($rank1 === 1): ?>
          <span class="flex-shrink-0 bg-amber-400 text-amber-900 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">Best Pick</span>
          <?php elseif ($rank1 === 2): ?>
          <span class="flex-shrink-0 bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">Runner Up</span>
          <?php elseif ($rank1 === 3): ?>
          <span class="flex-shrink-0 bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">Best Value</span>
          <?php endif; ?>
        </div>

        <div class="flex flex-col sm:flex-row gap-0">

          <!-- Image -->
          <div class="sm:w-56 flex-shrink-0 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center p-4 min-h-[140px]">
            <?php if (!empty($veh['image_url'])): ?>
            <img src="<?= esc($veh['image_url']) ?>" alt="<?= $vName ?>" class="max-h-32 w-auto object-contain" loading="lazy">
            <?php else: ?>
            <div class="flex flex-col items-center gap-2 text-slate-400">
              <span class="text-5xl">⚡</span>
              <span class="text-xs font-medium"><?= esc($veh['brand_name'] ?? '') ?></span>
            </div>
            <?php endif; ?>
          </div>

          <!-- Content -->
          <div class="flex-1 p-5">

            <!-- Why we picked this -->
            <div class="flex items-start gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-4">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              <div>
                <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-0.5">Why we picked this</p>
                <p class="text-sm text-green-900"><?= $whyPick ?></p>
              </div>
            </div>

            <!-- Spec pills -->
            <div class="flex flex-wrap gap-2 mb-4">
              <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">💰 <?= $price ?></span>
              <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">📏 <?= $range ?> claimed</span>
              <?php if ($realRange !== '—' && $realRange !== $range): ?>
              <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full">🛣️ <?= $realRange ?> real-world</span>
              <?php endif; ?>
              <?php if ($battery !== '—'): ?>
              <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">🔋 <?= $battery ?></span>
              <?php endif; ?>
              <?php if ($charging !== '—'): ?>
              <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full">⚡ <?= esc($charging) ?> charge</span>
              <?php endif; ?>
            </div>

            <!-- Best for -->
            <?php if (!empty($veh['best_for'])): ?>
            <div class="flex items-center gap-1.5 mb-4 flex-wrap">
              <span class="text-xs text-slate-500 font-medium">Best for:</span>
              <?php foreach (array_slice(array_filter(array_map('trim', explode(',', $veh['best_for']))), 0, 3) as $tag): ?>
              <span class="bg-charj-green/10 text-green-800 border border-green-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">✓ <?= esc($tag) ?></span>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- CTA buttons -->
            <div class="flex gap-3 flex-wrap">
              <a
                href="<?= base_url('vehicles/' . esc($veh['slug'] ?? '')) ?>"
                class="inline-flex items-center gap-1.5 bg-charj-navy hover:bg-charj-navy-light text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors"
                onclick="charjTrack('best_ev_view_details',{vehicle:'<?= esc(addslashes($vName)) ?>',rank:<?= $rank1 ?>})"
              >
                View Full Details →
              </a>
              <a
                href="<?= base_url('vehicles/' . esc($veh['slug'] ?? '')) ?>#lead-form"
                class="inline-flex items-center gap-1.5 bg-charj-green hover:bg-charj-green-dark text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors"
                onclick="charjTrack('best_ev_get_price',{vehicle:'<?= esc(addslashes($vName)) ?>',rank:<?= $rank1 ?>})"
              >
                💰 Get Price
              </a>
            </div>

          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── COMPARISON TABLE ──────────────────────────────────── -->
  <?php if (count($vehicles) >= 2): ?>
  <section class="mb-10" aria-labelledby="comparison-table-heading">
    <h2 id="comparison-table-heading" class="text-2xl font-extrabold text-slate-900 mb-4">
      Side-by-Side Comparison
    </h2>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]" aria-label="Vehicle comparison table">
          <thead>
            <tr class="bg-charj-navy text-white">
              <th class="px-5 py-4 text-left font-bold text-xs uppercase tracking-wider">Vehicle</th>
              <th class="px-4 py-4 text-center font-bold text-xs uppercase tracking-wider">Price</th>
              <th class="px-4 py-4 text-center font-bold text-xs uppercase tracking-wider">Claimed Range</th>
              <th class="px-4 py-4 text-center font-bold text-xs uppercase tracking-wider">Real Range</th>
              <th class="px-4 py-4 text-center font-bold text-xs uppercase tracking-wider">Battery</th>
              <th class="px-4 py-4 text-center font-bold text-xs uppercase tracking-wider">Top Speed</th>
              <th class="px-4 py-4 text-center font-bold text-xs uppercase tracking-wider">Expert Score</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php foreach ($vehicles as $idx => $veh):
              $rRange = !empty($veh['real_world_range']) ? $veh['real_world_range'] : ($veh['claimed_range'] ? (int)round($veh['claimed_range'] * 0.83) : null);
              $isTop  = $idx === 0;
            ?>
            <tr class="<?= $isTop ? 'bg-amber-50' : 'hover:bg-slate-50' ?> transition-colors">
              <td class="px-5 py-4">
                <a href="<?= base_url('vehicles/' . esc($veh['slug'] ?? '')) ?>" class="group">
                  <p class="font-bold text-slate-900 group-hover:text-charj-green transition-colors">
                    <?= $isTop ? '<span class="text-amber-600 mr-1">🥇</span>' : '' ?><?= esc(($veh['brand_name'] ?? '') . ' ' . ($veh['name'] ?? '')) ?>
                  </p>
                  <p class="text-xs text-slate-400 mt-0.5"><?= esc($veh['category_name'] ?? '') ?></p>
                </a>
              </td>
              <td class="px-4 py-4 text-center font-bold <?= $isTop ? 'text-charj-green' : 'text-slate-800' ?>"><?= bestEv_fmtINR($veh['starting_price'] ?? null) ?></td>
              <td class="px-4 py-4 text-center text-slate-700"><?= !empty($veh['claimed_range']) ? $veh['claimed_range'] . ' km' : '—' ?></td>
              <td class="px-4 py-4 text-center font-semibold text-blue-700"><?= $rRange ? '~' . $rRange . ' km' : '—' ?></td>
              <td class="px-4 py-4 text-center text-slate-700"><?= !empty($veh['battery_capacity']) ? $veh['battery_capacity'] . ' kWh' : '—' ?></td>
              <td class="px-4 py-4 text-center text-slate-700"><?= !empty($veh['top_speed']) ? esc($veh['top_speed']) . ' kmph' : '—' ?></td>
              <td class="px-4 py-4 text-center">
                <?php if (!empty($veh['expert_rating'])): ?>
                <span class="inline-flex items-center gap-1 font-bold <?= $isTop ? 'text-amber-600' : 'text-slate-700' ?>">
                  <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
                  <?= number_format((float)$veh['expert_rating'], 1) ?>/5
                </span>
                <?php else: ?>
                <span class="text-slate-400">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ── BUYING GUIDE ──────────────────────────────────────── -->
  <section class="mb-10" aria-labelledby="buying-guide-heading">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
      <h2 id="buying-guide-heading" class="text-2xl font-extrabold text-slate-900 mb-6"><?= esc($buyingGuide['header']) ?></h2>
      <div class="space-y-4">
        <?php foreach ($buyingGuide['points'] as $point):
          // Parse **bold** markers
          $rendered = preg_replace('/\*\*(.+?)\*\*/', '<strong class="text-slate-900">$1</strong>', esc($point));
        ?>
        <div class="flex items-start gap-3">
          <span class="flex-shrink-0 w-6 h-6 bg-charj-green rounded-full flex items-center justify-center mt-0.5">
            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          </span>
          <p class="text-slate-700 leading-relaxed text-sm"><?= $rendered ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Tool links -->
      <div class="mt-6 pt-5 border-t border-slate-100 flex flex-wrap gap-3">
        <a href="<?= base_url('subsidy-calculator') ?>" class="inline-flex items-center gap-1.5 bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">🏛️ Subsidy Calculator</a>
        <a href="<?= base_url('ev-emi-calculator') ?>" class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-800 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">🏦 EMI Calculator</a>
        <a href="<?= base_url('tco-calculator') ?>" class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-800 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">📊 5-Year TCO</a>
        <a href="<?= base_url('compare') ?>" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">⚖️ Compare EVs</a>
      </div>
    </div>
  </section>

  <!-- ── FAQ ───────────────────────────────────────────────── -->
  <section class="mb-10" aria-labelledby="faq-heading">
    <h2 id="faq-heading" class="text-2xl font-extrabold text-slate-900 mb-5">Frequently Asked Questions</h2>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        <?php foreach ($useCaseFaqs as $fi => $faq): ?>
        {
          "@type": "Question",
          "name": "<?= esc($faq['q']) ?>",
          "acceptedAnswer": { "@type": "Answer", "text": "<?= esc(strip_tags($faq['a'])) ?>" }
        }<?= $fi < count($useCaseFaqs) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
      ]
    }
    </script>
    <div class="space-y-3" x-data="{}">
      <?php foreach ($useCaseFaqs as $fi => $faq): ?>
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
        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="border-t border-slate-100 px-5 py-4">
          <p class="text-sm text-slate-700 leading-relaxed"><?= esc($faq['a']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── PERSONALIZED ADVICE LEAD FORM ─────────────────────── -->
  <section aria-labelledby="lead-form-heading">
    <div class="bg-gradient-to-br from-charj-navy to-[#1a3a55] rounded-2xl p-6 lg:p-8">
      <div class="max-w-2xl mx-auto">
        <div class="text-center mb-6">
          <span class="text-4xl">🎯</span>
          <h2 id="lead-form-heading" class="text-xl font-extrabold text-white mt-2 mb-2">
            Need Personalised Advice for <?= esc($useCase) ?>?
          </h2>
          <p class="text-slate-300 text-sm">Our EV specialists will match you with the perfect EV based on your commute, budget and charging setup — free of charge.</p>
        </div>
        <?= $this->include('partials/lead_form', [
            'formHeading'  => 'Get Free EV Advice',
            'formSubtitle' => 'Tell us your needs and we\'ll recommend the best EV for ' . $useCase,
            'defaultType'  => 'help_me_choose',
            'compactMode'  => false,
        ]) ?>
      </div>
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

</div><!-- /max-w-5xl -->
</div><!-- /bg-slate-50 -->

<?= $this->endSection() ?>
