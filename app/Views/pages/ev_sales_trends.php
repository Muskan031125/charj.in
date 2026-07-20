<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'India EV Sales & Trends 2026 | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'India EV sales and trends 2026 — monthly registration trends, category split, top-selling brands and state leaderboard. Illustrative figures compiled from public registration trends.') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php
/* ============================================================
   India EV Sales & Trends — SEO / Authority Data Dashboard
   Charj.in · CI4 · Tailwind · Alpine.js · pure SVG/CSS charts
   NOTE: All figures below are REPRESENTATIVE / ILLUSTRATIVE,
   compiled from public registration trends — NOT official Vahan data.
   ============================================================ */

// ── 1. Top stat cards ──────────────────────────────────────
$statCards = [
    ['label' => 'EVs Sold (FY)',        'value' => '~19.5 Lakh', 'sub' => 'across all categories', 'accent' => '#00A896', 'bg' => '#F0FFF9'],
    ['label' => 'YoY Growth',           'value' => '+27%',       'sub' => 'vs previous fiscal',     'accent' => '#00C060', 'bg' => '#EAFFF4'],
    ['label' => 'EV Penetration',       'value' => '~7.4%',      'sub' => 'of new vehicle sales',   'accent' => '#00A896', 'bg' => '#F0FFF9'],
    ['label' => 'Charging Stations',    'value' => '~25,000',    'sub' => 'public points & growing','accent' => '#00C060', 'bg' => '#EAFFF4'],
];

// ── 2. Monthly 2W EV registration trend (illustrative) ─────
$monthly = [
    ['month' => 'Jan', 'value' => 86000],
    ['month' => 'Feb', 'value' => 91000],
    ['month' => 'Mar', 'value' => 118000],
    ['month' => 'Apr', 'value' => 97000],
    ['month' => 'May', 'value' => 104000],
    ['month' => 'Jun', 'value' => 99000],
    ['month' => 'Jul', 'value' => 112000],
    ['month' => 'Aug', 'value' => 121000],
    ['month' => 'Sep', 'value' => 108000],
    ['month' => 'Oct', 'value' => 129000],
    ['month' => 'Nov', 'value' => 115000],
    ['month' => 'Dec', 'value' => 124000],
];
$maxMonthly = max(array_column($monthly, 'value'));

// ── 3. Sales by category ───────────────────────────────────
$categories = [
    ['name' => '2-Wheelers',  'pct' => 62],
    ['name' => '3-Wheelers',  'pct' => 24],
    ['name' => '4-Wheelers',  'pct' => 11],
    ['name' => 'Commercial',  'pct' => 3],
];

// ── 4. Top-selling brands (illustrative) ───────────────────
$brands = [
    ['brand' => 'Ola Electric', 'units' => '3,40,000', 'share' => 28.0, 'seg' => '2W'],
    ['brand' => 'TVS Motor',    'units' => '2,55,000', 'share' => 21.0, 'seg' => '2W'],
    ['brand' => 'Bajaj Auto',   'units' => '2,10,000', 'share' => 17.3, 'seg' => '2W / 3W'],
    ['brand' => 'Ather Energy', 'units' => '1,55,000', 'share' => 12.8, 'seg' => '2W'],
    ['brand' => 'Hero / Vida',  'units' => '78,000',   'share' => 6.4,  'seg' => '2W'],
    ['brand' => 'Tata Motors',  'units' => '62,000',   'share' => 5.1,  'seg' => '4W'],
];

// ── 5. State leaderboard ───────────────────────────────────
$states = [
    ['state' => 'Maharashtra', 'pct' => 100],
    ['state' => 'Karnataka',   'pct' => 88],
    ['state' => 'Tamil Nadu',  'pct' => 81],
    ['state' => 'Gujarat',     'pct' => 72],
    ['state' => 'Uttar Pradesh','pct' => 66],
    ['state' => 'Delhi',       'pct' => 58],
    ['state' => 'Rajasthan',   'pct' => 49],
];

// ── 6. Closing insight cards ───────────────────────────────
$insights = [
    [
        'title' => '2-Wheelers lead — start here',
        'body'  => 'Over 6 in 10 EVs sold are scooters. If you are new to EVs, a 2-wheeler is the lowest-risk, highest-value entry point.',
        'cta'   => 'Find my perfect EV',
        'href'  => base_url('find-my-ev'),
    ],
    [
        'title' => 'More models than ever',
        'body'  => 'Choice has exploded across every price band. Compare range, charging and on-road price side by side before you commit.',
        'cta'   => 'Browse all EVs',
        'href'  => base_url('vehicles'),
    ],
    [
        'title' => 'Know the real cost',
        'body'  => 'Sticker price is only half the story. Check the full on-road price including RTO, insurance and any state subsidy.',
        'cta'   => 'Calculate on-road price',
        'href'  => base_url('on-road-price'),
    ],
];
?>

<!-- ════════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════ -->
<section class="hero-sm relative overflow-hidden pt-24 pb-8"
         style="background:linear-gradient(165deg,#F0FFF9 0%,#EAFFF4 55%,#F7FFFE 100%)">
  <div class="hero-grid absolute inset-0 opacity-60 pointer-events-none"></div>
  <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
    <span class="hero-badge badge-green">
      <span class="neon-dot"></span> EV Data · 2026
    </span>
    <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight" style="color:#0F172A;letter-spacing:-.03em">
      India EV Sales &amp; Trends
    </h1>
    <p class="hero-desc mt-3 max-w-2xl text-base sm:text-lg" style="color:#475569">
      A snapshot of how India is going electric — monthly registration momentum, category split,
      the brands winning the race and the states leading adoption.
    </p>
  </div>
</section>

<!-- Illustrative-data disclaimer -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 -mt-2">
  <div class="flex items-start gap-3 rounded-2xl px-4 py-3 text-sm"
       style="background:#FFFDF2;border:1px solid rgba(245,158,11,.28);color:#92610A">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="font-medium">
      Illustrative figures compiled from public registration trends. These are representative estimates
      for orientation only — not official Vahan figures or audited sales data.
    </p>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════
     1 · TOP STAT CARDS
════════════════════════════════════════════════════════ -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 mt-8">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <?php foreach ($statCards as $c): ?>
    <div class="card p-5 sm:p-6" style="background:<?= esc($c['bg']) ?>">
      <div class="w-10 h-1.5 rounded-full mb-4" style="background:<?= esc($c['accent']) ?>"></div>
      <div class="text-2xl sm:text-3xl font-black" style="color:#0F172A;letter-spacing:-.02em"><?= esc($c['value']) ?></div>
      <div class="mt-1 text-sm font-bold" style="color:<?= esc($c['accent']) ?>"><?= esc($c['label']) ?></div>
      <div class="mt-0.5 text-xs" style="color:#64748B"><?= esc($c['sub']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     2 · MONTHLY SALES TREND  (CSS bar chart)
════════════════════════════════════════════════════════ -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 mt-10">
  <div class="card p-5 sm:p-7" style="background:#FFFFFF">
    <div class="flex items-end justify-between flex-wrap gap-2 mb-1">
      <h2 class="text-xl sm:text-2xl font-black" style="color:#0F172A;letter-spacing:-.02em">Monthly EV Sales Trend</h2>
      <span class="text-xs font-bold px-2.5 py-1 rounded-full" style="background:rgba(0,168,150,.1);color:#00A896">2-Wheeler registrations · illustrative</span>
    </div>
    <p class="text-sm mb-6" style="color:#64748B">Hover any bar to see the estimated registrations for that month.</p>

    <div class="overflow-x-auto scrollbar-hide -mx-1 px-1">
      <div class="flex items-end gap-2 sm:gap-3" style="min-width:560px;height:240px">
        <?php foreach ($monthly as $m):
          $h = max(6, round(($m['value'] / $maxMonthly) * 100));
          $k = number_format($m['value'] / 1000) . 'k';
        ?>
        <div class="flex-1 flex flex-col items-center justify-end h-full group">
          <span class="mb-2 text-[11px] font-bold opacity-0 group-hover:opacity-100 transition-opacity" style="color:#00A896"><?= esc($k) ?></span>
          <div class="w-full rounded-t-lg transition-all duration-200 group-hover:brightness-110"
               style="height:<?= $h ?>%;background:linear-gradient(180deg,#00E676 0%,#00A896 100%);box-shadow:0 2px 8px rgba(0,200,100,.18)"
               title="<?= esc($m['month']) ?>: ~<?= esc($k) ?> registrations"></div>
          <span class="mt-2 text-[11px] font-semibold" style="color:#64748B"><?= esc($m['month']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     3 + 4 · CATEGORY SPLIT  +  TOP BRANDS
════════════════════════════════════════════════════════ -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 mt-10 grid lg:grid-cols-2 gap-6">

  <!-- Category split (horizontal bars) -->
  <div class="card p-5 sm:p-7" style="background:#FFFFFF">
    <h2 class="text-xl sm:text-2xl font-black mb-1" style="color:#0F172A;letter-spacing:-.02em">Sales by Category</h2>
    <p class="text-sm mb-6" style="color:#64748B">Share of total EV registrations · illustrative.</p>
    <div class="space-y-5">
      <?php foreach ($categories as $i => $cat): ?>
      <div>
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-sm font-bold" style="color:#0F172A"><?= esc($cat['name']) ?></span>
          <span class="text-sm font-black" style="color:#00A896"><?= esc($cat['pct']) ?>%</span>
        </div>
        <div class="w-full h-3 rounded-full overflow-hidden" style="background:#EAFFF4">
          <div class="h-full rounded-full"
               style="width:<?= esc($cat['pct']) ?>%;background:linear-gradient(90deg,#00A896,#00E676)"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Top brands table -->
  <div class="card p-5 sm:p-7" style="background:#FFFFFF">
    <h2 class="text-xl sm:text-2xl font-black mb-1" style="color:#0F172A;letter-spacing:-.02em">Top-Selling Brands</h2>
    <p class="text-sm mb-6" style="color:#64748B">Ranked by estimated EV units · illustrative.</p>
    <div class="divide-y" style="border-color:rgba(0,168,150,.1)">
      <?php foreach ($brands as $rank => $b): $r = $rank + 1; ?>
      <div class="flex items-center gap-3 py-3">
        <span class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-black"
              style="background:linear-gradient(135deg,#00A896,#00E676);color:#022C22"><?= $r ?></span>
        <div class="min-w-0 flex-1">
          <div class="text-sm font-bold truncate" style="color:#0F172A"><?= esc($b['brand']) ?></div>
          <div class="text-xs" style="color:#94A3B8"><?= esc($b['units']) ?> units · <?= esc($b['seg']) ?></div>
        </div>
        <span class="flex-shrink-0 text-sm font-black" style="color:#00A896"><?= esc(number_format($b['share'], 1)) ?>%</span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     5 · STATE LEADERBOARD
════════════════════════════════════════════════════════ -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 mt-10">
  <div class="card p-5 sm:p-7" style="background:#F7FFFE">
    <h2 class="text-xl sm:text-2xl font-black mb-1" style="color:#0F172A;letter-spacing:-.02em">State Adoption Leaderboard</h2>
    <p class="text-sm mb-6" style="color:#64748B">Relative EV adoption index (top state = 100) · illustrative.</p>
    <div class="space-y-4">
      <?php foreach ($states as $i => $s): ?>
      <div class="flex items-center gap-3">
        <span class="flex-shrink-0 w-6 text-sm font-black text-right" style="color:#94A3B8"><?= $i + 1 ?></span>
        <span class="flex-shrink-0 w-28 sm:w-36 text-sm font-bold truncate" style="color:#0F172A"><?= esc($s['state']) ?></span>
        <div class="flex-1 h-3 rounded-full overflow-hidden" style="background:#EAFFF4">
          <div class="h-full rounded-full" style="width:<?= esc($s['pct']) ?>%;background:linear-gradient(90deg,#00A896,#00E676)"></div>
        </div>
        <span class="flex-shrink-0 w-10 text-right text-sm font-black" style="color:#00A896"><?= esc($s['pct']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     6 · CLOSING INSIGHT CARDS
════════════════════════════════════════════════════════ -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 mt-12 mb-16">
  <h2 class="text-xl sm:text-2xl font-black mb-2" style="color:#0F172A;letter-spacing:-.02em">What this means for buyers</h2>
  <p class="text-sm mb-6" style="color:#64748B">Three quick takeaways and where to go next.</p>
  <div class="grid md:grid-cols-3 gap-5">
    <?php foreach ($insights as $card): ?>
    <a href="<?= esc($card['href']) ?>" class="card-hover p-6 flex flex-col" style="background:#FFFFFF;text-decoration:none">
      <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4"
           style="background:linear-gradient(135deg,#00A896,#00E676)">
        <svg class="w-5 h-5" style="color:#022C22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
        </svg>
      </div>
      <h3 class="text-base font-black mb-2" style="color:#0F172A"><?= esc($card['title']) ?></h3>
      <p class="text-sm flex-1 mb-4" style="color:#475569"><?= esc($card['body']) ?></p>
      <span class="inline-flex items-center gap-1.5 text-sm font-bold" style="color:#00A896">
        <?= esc($card['cta']) ?>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </span>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<?= $this->endSection() ?>
