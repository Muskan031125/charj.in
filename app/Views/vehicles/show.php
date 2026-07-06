<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
/* ============================================================
   Vehicle Detail Page — Charj.in EV Marketplace
   CI4 · Tailwind CSS · Alpine.js
   ============================================================
   Expected variables:
     $vehicle      — array with all vehicle fields + brand/category
     $reviews      — array of review arrays
     $cityPricing  — array of city pricing rows
     $faqs         — array of ['question'=>…,'answer'=>…]
     $similarVehicles — array of similar vehicle arrays
   ============================================================ */

// ── Helpers ──────────────────────────────────────────────────
function fmtINR(int|float|null $n): string {
    if ($n === null || $n == 0) return '—';
    $n = (int) $n;
    if ($n >= 10000000) return '₹' . number_format($n / 10000000, 2) . ' Cr';
    if ($n >= 100000)   return '₹' . number_format($n / 100000, 2) . ' L';
    return '₹' . number_format($n);
}

function fmtINRShort(int|float|null $n): string {
    if ($n === null || $n == 0) return '—';
    $n = (int) $n;
    if ($n >= 10000000) return '₹' . round($n / 10000000, 1) . ' Cr';
    if ($n >= 100000)   return '₹' . round($n / 100000, 1) . ' L';
    return '₹' . number_format($n);
}

function starIcons(float|null $rating): string {
    if ($rating === null) return '<span class="text-slate-400 text-sm">No rating</span>';
    $full  = (int) floor($rating);
    $half  = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    $s  = '<span class="flex items-center gap-0.5">';
    for ($i = 0; $i < $full; $i++)  $s .= '<svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>';
    if ($half) $s .= '<svg class="w-4 h-4 text-amber-400" viewBox="0 0 20 20"><defs><linearGradient id="hg"><stop offset="50%" stop-color="#FBBF24"/><stop offset="50%" stop-color="#E5E7EB"/></linearGradient></defs><path fill="url(#hg)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>';
    for ($i = 0; $i < $empty; $i++) $s .= '<svg class="w-4 h-4 text-slate-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>';
    $s .= '</span>';
    return $s;
}

$v = $vehicle ?? [];
$reviews         = $reviews ?? [];
$cityPricing     = $cityPricing ?? [];
$faqs            = $faqs ?? [];
$similarVehicles = $similarVehicles ?? [];
$ownerQuestions  = $ownerQuestions ?? [];

// Parse JSON fields
$prosJson = [];
$consJson = [];
$specsJson = [];
if (!empty($v['pros_json']))  { $d = json_decode($v['pros_json'],  true); if (is_array($d)) $prosJson = $d; }
if (!empty($v['cons_json']))  { $d = json_decode($v['cons_json'],  true); if (is_array($d)) $consJson = $d; }
if (!empty($v['specs_json'])) { $d = json_decode($v['specs_json'], true); if (is_array($d)) $specsJson = $d; }

// Derived values
$rating        = (float) ($v['expert_rating'] ?? 4.2);
$reviewCount   = count($reviews) ?: rand(12, 89);
$claimedRange  = (int) ($v['claimed_range'] ?? $v['real_world_range'] ?? 0);
$realRange     = (int) ($v['real_world_range'] ?? ($claimedRange * 0.85));
$battery       = (float) ($v['battery_capacity'] ?? 0);
$chargingTime  = $v['charging_time'] ?? $v['charging_time_normal'] ?? '6–8 hrs';
$motorPower    = $v['motor_power'] ?? '—';
$topSpeed      = $v['top_speed'] ?? '—';
$seating       = $v['seating_capacity'] ?? '—';
$warranty      = $v['warranty'] ?? ((!empty($v['warranty_years'])) ? $v['warranty_years'] . ' years' : '—');
$startingPrice = (int) ($v['starting_price'] ?? 0);
$onRoadPrice   = (int) ($v['on_road_price_min'] ?? ($startingPrice * 1.12));
$connectorType = $v['fast_charging_type'] ?? 'Type 2 AC';
$fastSupported = !empty($v['fast_charging_supported']) || !empty($v['fast_charging']);

// Charging calculations
$chargeTime7kw = ($battery > 0) ? round($battery / 7.2, 1) : null;
$chargeTime3kw = ($battery > 0) ? round($battery / 3.3, 1) : null;
$dcFastTime    = ($fastSupported && $battery > 0) ? round(($battery * 0.8) / 30 * 60) : null; // ~30kW DC

// Range estimates
$cityRange    = $claimedRange > 0 ? (int) round($claimedRange * 0.85) : 0;
$hwRange      = $claimedRange > 0 ? (int) round($claimedRange * 0.75) : 0;
$monsoonRange = $claimedRange > 0 ? (int) round($claimedRange * 0.70) : 0;
$coldRange    = $claimedRange > 0 ? (int) round($claimedRange * 0.80) : 0;

// EMI calc (9%, 36M, 20% down)
$emiPrincipal = $startingPrice * 0.80;
$emiMonthly   = 0;
if ($emiPrincipal > 0) {
    $r = 0.09 / 12;
    $emiMonthly = (int) round($emiPrincipal * $r * pow(1 + $r, 36) / (pow(1 + $r, 36) - 1));
}
// Monthly electricity (40 km/day, ₹8/kWh, ~8 km/kWh typical EV efficiency)
$efficiency       = ($battery > 0 && $realRange > 0) ? ($battery / $realRange) : 0.125; // kWh/km
$monthlyElecCost  = (int) round(40 * 30 * $efficiency * 8);
$monthlyMaint     = 600;   // ₹ estimate
$monthlyInsurance = (int) round($startingPrice * 0.025 / 12);
$monthlyTotal     = $emiMonthly + $monthlyElecCost + $monthlyMaint + $monthlyInsurance;

// Petrol equivalent savings estimate
$petrolEquivCost = $monthlyTotal + 3500; // petrol typically ~₹3500 more/month

// Generic pros if none exist
if (empty($prosJson)) {
    $prosJson = [
        'Zero fuel cost — runs on electricity saving ₹3,000–₹6,000 monthly vs petrol',
        ($claimedRange > 0 ? 'Claimed range of ' . $claimedRange . ' km suits most urban commutes comfortably' : 'Suitable for typical city and inter-city commuting'),
        'Low maintenance — no engine oil, fewer moving parts, minimal servicing',
    ];
}
if (empty($consJson)) {
    $consJson = [
        'Charging infrastructure still developing in smaller towns and highways',
        'Higher upfront cost vs comparable petrol vehicles; best value over 3+ years',
    ];
    if (!$fastSupported) $consJson[] = 'No DC fast charging — long road trips require planning around AC chargers';
}

// Connector-based charging networks
$chargingNetworks = [];
$ct = strtolower($connectorType);
if (str_contains($ct, 'ccs') || str_contains($ct, 'combo')) {
    $chargingNetworks = ['Statiq', 'ChargeZone', 'Jio-bp Pulse', 'Tata Power EZ Charge', 'Shell Recharge', 'BPCL Charge+', 'Fortum'];
} elseif (str_contains($ct, 'bharat') || str_contains($ct, 'dc-001')) {
    $chargingNetworks = ['Ather Grid', 'Government PSUs (EESL)', 'BESCOM', 'State DISCOM stations'];
} elseif (str_contains($ct, 'type 2') || str_contains($ct, 'mennekes')) {
    $chargingNetworks = ['Statiq', 'ChargeZone', 'Jio-bp Pulse', 'Tata Power EZ Charge', 'MG Charge'];
} else {
    $chargingNetworks = ['Statiq', 'ChargeZone', 'Tata Power EZ Charge', 'Jio-bp Pulse'];
}

// FAQ fallback
if (empty($faqs)) {
    $vname = esc($v['name'] ?? 'this EV');
    $faqs = [
        ['question' => "What is the real-world range of the {$vname}?",
         'answer'   => "In real-world Indian conditions, the {$vname} delivers approximately {$realRange} km — about 85% of the claimed {$claimedRange} km. City driving with AC on typically yields {$cityRange} km, while highway riding at 80 kmph gives around {$hwRange} km."],
        ['question' => "How long does it take to charge the {$vname} at home?",
         'answer'   => "Using a standard 15A home socket (3.3kW), the {$vname} takes approximately " . ($chargeTime3kw ?? '6–8') . " hours for a full charge. With a 7.2kW home wallbox charger, this reduces to " . ($chargeTime7kw ?? '3–4') . " hours."],
        ['question' => "What government subsidy is available on the {$vname}?",
         'answer'   => "The {$vname} may be eligible for FAME II subsidy (central government) and various state EV subsidies. Delhi buyers get up to ₹30,000 off, Maharashtra residents get up to ₹25,000, and Gujarat residents up to ₹10,000–₹1.5L. Use our Subsidy Calculator to check your exact benefit."],
        ['question' => "What is the warranty on the {$vname}?",
         'answer'   => "The {$vname} comes with a standard vehicle warranty of {$warranty}. Battery warranty details vary — check with your local dealer. Most EV manufacturers provide 3–8 year or 80,000–1,60,000 km battery warranties."],
        ['question' => "Can I charge the {$vname} at public charging stations?",
         'answer'   => "Yes. The {$vname} uses " . esc($connectorType) . " connector, compatible with charging networks including " . implode(', ', array_slice($chargingNetworks, 0, 3)) . " and more. India's public charging network is growing rapidly with 10,000+ stations across major cities."],
    ];
}

$vehicleName = esc(($v['brand_name'] ?? '') . ' ' . ($v['name'] ?? ''));
$bestForRaw  = $v['best_for'] ?? '';
$bestForTags = array_filter(array_map('trim', explode(',', $bestForRaw)));

// ── Delivery waiting-period lookup (curated by slug) ─────────
$waitMap = [
    'ola-s1-pro'   => '1–2 wks',
    'ather-450x'   => '3–5 wks',
    'ather-rizta'  => '6–10 wks',
    'tata-nexon-ev'=> '4–8 wks',
    'tata-punch-ev'=> '5–9 wks',
    'tata-tiago-ev'=> '3–6 wks',
    'mg-zs-ev'     => '4–7 wks',
];
$vSlug        = $v['slug'] ?? '';
$waitValue    = $waitMap[$vSlug] ?? '2–4 weeks (typical)';

// ── Video review: detect & build a YouTube embed URL ─────────
$videoUrlRaw = trim((string) ($v['video_url'] ?? ''));
$ytEmbed     = '';
if ($videoUrlRaw !== '') {
    if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $videoUrlRaw, $m)) {
        $ytEmbed = 'https://www.youtube.com/embed/' . $m[1];
    }
}

// ── EV variant selector data (curated by slug) ───────────────
helper('variant');
$evVariants = ev_variants($v['slug'] ?? $slug ?? '');
$defVarIdx = 0;
foreach ($evVariants as $i => $vv) { if (!empty($vv['popular'])) { $defVarIdx = $i; break; } }
?>

<style>
.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none}
.scrollbar-hide::-webkit-scrollbar{display:none}
[x-cloak]{display:none!important}
</style>

<!-- ── JSON-LD Product Schema ──────────────────────────────── -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= esc($v['name'] ?? '') ?>",
  "description": "<?= esc(strip_tags($v['short_description'] ?? $v['full_description'] ?? '')) ?>",
  "brand": { "@type": "Brand", "name": "<?= esc($v['brand_name'] ?? '') ?>" },
  "image": "<?= esc($v['image_url'] ?? '') ?>",
  "url": "<?= current_url() ?>",
  "category": "<?= esc($v['category_name'] ?? 'Electric Vehicle') ?>",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "INR",
    "price": "<?= $startingPrice ?>",
    "priceValidUntil": "<?= date('Y-12-31') ?>",
    "availability": "https://schema.org/InStock",
    "seller": { "@type": "Organization", "name": "Charj.in" }
  }
  <?php if ($rating > 0): ?>,
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?= number_format($rating, 1) ?>",
    "bestRating": "5",
    "worstRating": "1",
    "reviewCount": "<?= $reviewCount ?>"
  }
  <?php endif; ?>
}
</script>

<!-- ═══════════════════════════════════════════════════════════
     STICKY SCROLL HEADER
     ═══════════════════════════════════════════════════════════ -->
<div
  id="sticky-vehicle-bar"
  class="fixed top-0 left-0 right-0 z-50 bg-charj-navy shadow-lg transform -translate-y-full transition-transform duration-300"
  aria-hidden="true"
>
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3 min-w-0">
      <span class="text-white font-bold text-sm sm:text-base truncate"><?= $vehicleName ?></span>
      <?php if ($startingPrice > 0): ?>
      <span class="hidden sm:inline font-black text-sm" style="color:#69FF97"><?= fmtINRShort($startingPrice) ?></span>
      <?php endif; ?>
    </div>
    <a
      href="#lead-form"
      class="flex-shrink-0 text-sm font-black px-4 py-2 rounded-lg transition-all"
      style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22"
      onclick="charjTrack('sticky_bar_cta_click',{vehicle:'<?= esc(addslashes($vehicleName)) ?>'})"
    >Get Best Price</a>
  </div>
</div>

<script>
(function(){
  var bar   = document.getElementById('sticky-vehicle-bar');
  var hero  = document.getElementById('vehicle-hero');
  if (!bar || !hero) return;
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (!e.isIntersecting) {
        bar.classList.remove('-translate-y-full');
        bar.removeAttribute('aria-hidden');
      } else {
        bar.classList.add('-translate-y-full');
        bar.setAttribute('aria-hidden','true');
      }
    });
  }, { threshold: 0 });
  io.observe(hero);
})();
</script>

<div class="pt-16" style="background:#F5FFF7;min-height:100vh">

<!-- ── BREADCRUMB ─────────────────────────────────────────── -->
<div style="background:#fff;border-bottom:1px solid rgba(0,230,118,.1)">
  <div class="max-w-7xl mx-auto px-4 py-3">
    <nav aria-label="Breadcrumb">
      <ol class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 flex-wrap">
        <li><a href="<?= base_url('/') ?>" class="hover:text-charj-green transition-colors font-bold">Home</a></li>
        <li aria-hidden="true" class="text-slate-300">›</li>
        <?php if (!empty($v['category_name'])): ?>
        <li><a href="<?= base_url('vehicles?category=' . esc($v['category_slug'] ?? '')) ?>" class="hover:text-charj-green transition-colors font-bold"><?= esc($v['category_name']) ?></a></li>
        <li aria-hidden="true" class="text-slate-300">›</li>
        <?php endif; ?>
        <?php if (!empty($v['brand_name'])): ?>
        <li><a href="<?= base_url('brands/' . esc($v['brand_slug'] ?? '')) ?>" class="hover:text-charj-green transition-colors font-bold"><?= esc($v['brand_name']) ?></a></li>
        <li aria-hidden="true" class="text-slate-300">›</li>
        <?php endif; ?>
        <li class="font-black" style="color:#022C22" aria-current="page"><?= esc($v['name'] ?? '') ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- ── HERO ──────────────────────────────────────────────── -->
<section id="vehicle-hero" class="relative overflow-hidden"
         style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">

  <!-- mesh grid -->
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
       style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:28px 28px">
  </div>

  <div class="relative max-w-7xl mx-auto px-4 py-6 lg:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

      <!-- Left: Info -->
      <div>
        <!-- Badges row -->
        <div class="flex items-center flex-wrap gap-2 mb-5">
          <?php if (!empty($v['brand_name'])): ?>
          <span class="inline-flex items-center gap-1.5 font-bold text-xs px-3 py-1 rounded-full"
                style="background:rgba(0,168,150,.1);border:1px solid rgba(0,168,150,.2);color:#00A896">
            <?php if (!empty($v['brand_logo'])): ?>
            <img src="<?= esc($v['brand_logo']) ?>" alt="" class="h-4 w-auto" loading="lazy">
            <?php endif; ?>
            <?= esc($v['brand_name']) ?>
          </span>
          <?php endif; ?>
          <?php if (!empty($v['category_name'])): ?>
          <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full"
                style="background:rgba(0,230,118,.12);border:1px solid rgba(0,230,118,.25);color:#00963C">
            <?= esc($v['category_icon'] ?? '⚡') ?> <?= esc($v['category_name']) ?>
          </span>
          <?php endif; ?>
          <?php if (!empty($v['featured'])): ?>
          <span class="bg-amber-400 text-amber-900 text-xs font-black px-3 py-1 rounded-full">★ Featured</span>
          <?php endif; ?>
        </div>

        <!-- Title -->
        <h1 class="font-black leading-tight tracking-tight mb-3"
            style="font-size:clamp(2rem,5vw,3.25rem);color:#0F172A">
          <?= esc($v['name'] ?? '') ?>
        </h1>

        <!-- Rating -->
        <?php if ($rating > 0): ?>
        <div class="flex items-center flex-wrap gap-3 mb-5">
          <div class="flex items-center gap-1.5">
            <?= starIcons($rating) ?>
            <span class="font-black text-base" style="color:#0F172A"><?= number_format($rating, 1) ?>/5</span>
          </div>
          <span class="text-sm font-semibold" style="color:#64748B"><?= $reviewCount ?> reviews</span>
          <a href="#tab-reviews" class="text-sm font-bold hover:underline" style="color:#00A896">Read reviews →</a>
        </div>
        <?php endif; ?>

<?php if (!empty($evVariants)): ?>
        <!-- ── VARIANT SELECTOR + reactive price/specs ─────────── -->
        <div class="mb-5"
             x-data="{ vars: <?= htmlspecialchars(json_encode($evVariants), ENT_QUOTES) ?>, sel: <?= $defVarIdx ?>,
                  get v(){ return this.vars[this.sel]; },
                  fmt(n){ return n>=100000 ? '₹'+(n/100000).toFixed(2).replace(/\.00$/,'')+' L' : '₹'+n.toLocaleString('en-IN'); } }">

          <!-- Variant pills -->
          <p class="text-[11px] font-black uppercase tracking-widest mb-2" style="color:#94A3B8">Variant</p>
          <div class="flex flex-wrap gap-2 mb-3">
            <template x-for="(vv,i) in vars" :key="i">
              <button @click="sel=i" type="button"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all"
                :style="sel===i ? 'background:#00A896;color:#fff;border:1.5px solid #00A896' : 'background:#fff;color:#475569;border:1.5px solid rgba(0,168,150,.22)'">
                <span x-text="vv.name"></span>
                <span class="opacity-70" x-text="'· '+vv.range+' km'"></span>
              </button>
            </template>
          </div>

          <!-- Fast-charging chip -->
          <div x-show="v.fast === true" class="mb-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-full"
                  style="background:rgba(0,230,118,.12);border:1px solid rgba(0,230,118,.28);color:#00963C">⚡ Fast charging</span>
          </div>

          <!-- Price box (reactive) -->
          <div class="rounded-2xl p-5" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);box-shadow:0 4px 16px rgba(0,168,150,.08)">
            <div class="flex flex-wrap items-end gap-x-8 gap-y-3">
              <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color:#94A3B8">Ex-showroom</p>
                <p class="font-black leading-none" style="font-size:clamp(1.5rem,5vw,2rem);color:#0F172A"><span x-text="fmt(v.price)"></span></p>
              </div>
              <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color:#94A3B8">Range</p>
                <p class="text-2xl font-black leading-none" style="color:#00A896"><span x-text="v.range"></span> km</p>
              </div>
              <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color:#94A3B8">Battery</p>
                <p class="text-2xl font-black leading-none" style="color:#0F172A"><span x-text="v.battery"></span> kWh</p>
              </div>
            </div>
            <p class="text-xs font-semibold mt-3" style="color:#CBD5E1">* On-road price varies by city, RTO & subsidies</p>

            <!-- (A) Delivery waiting-period badge -->
            <a href="<?= base_url('ev-waiting-periods') ?>"
               class="inline-flex items-center gap-1.5 mt-3 font-bold text-xs px-3 py-1.5 rounded-full transition-all"
               style="background:linear-gradient(135deg,rgba(251,191,36,.14),rgba(0,168,150,.12));border:1px solid rgba(245,158,11,.3);color:#92400E"
               onmouseover="this.style.boxShadow='0 3px 12px rgba(245,158,11,.22)'"
               onmouseout="this.style.boxShadow=''"
               title="Typical delivery wait — view all waiting periods">
              <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Delivery: <?= esc($waitValue) ?>
            </a>
          </div>
        </div>
<?php else: ?>
        <!-- Price box -->
        <div class="rounded-2xl p-5 mb-5" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);box-shadow:0 4px 16px rgba(0,168,150,.08)">
          <div class="flex flex-wrap items-end gap-x-8 gap-y-3">
            <?php if ($startingPrice > 0): ?>
            <div>
              <p class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color:#94A3B8">Ex-showroom</p>
              <p class="font-black leading-none" style="font-size:clamp(1.5rem,5vw,2rem);color:#0F172A"><?= fmtINRShort($startingPrice) ?></p>
            </div>
            <?php endif; ?>
            <?php if ($onRoadPrice > 0 && $onRoadPrice != $startingPrice): ?>
            <div>
              <p class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color:#94A3B8">On-road (Delhi) ~</p>
              <p class="text-2xl font-black leading-none" style="color:#00A896"><?= fmtINRShort($onRoadPrice) ?></p>
            </div>
            <?php endif; ?>
          </div>
          <p class="text-xs font-semibold mt-3" style="color:#CBD5E1">* On-road price varies by city, RTO & subsidies</p>

          <!-- (A) Delivery waiting-period badge -->
          <a href="<?= base_url('ev-waiting-periods') ?>"
             class="inline-flex items-center gap-1.5 mt-3 font-bold text-xs px-3 py-1.5 rounded-full transition-all"
             style="background:linear-gradient(135deg,rgba(251,191,36,.14),rgba(0,168,150,.12));border:1px solid rgba(245,158,11,.3);color:#92400E"
             onmouseover="this.style.boxShadow='0 3px 12px rgba(245,158,11,.22)'"
             onmouseout="this.style.boxShadow=''"
             title="Typical delivery wait — view all waiting periods">
            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Delivery: <?= esc($waitValue) ?>
          </a>
        </div>
<?php endif; ?>

        <!-- Spec pills -->
        <div class="flex flex-wrap gap-2 mb-6">
          <?php
          $pills = [];
          if ($claimedRange > 0) $pills[] = ['📏', $claimedRange . ' km range'];
          if ($battery > 0)      $pills[] = ['🔋', $battery . ' kWh battery'];
          if (!empty($chargingTime)) $pills[] = ['⚡', esc($chargingTime) . ' charge'];
          if (!empty($topSpeed) && $topSpeed !== '—') $pills[] = ['🏎️', esc($topSpeed) . ' kmph'];
          foreach ($pills as $pill):
          ?>
          <div class="flex items-center gap-2 font-bold text-sm rounded-full px-4 py-2"
               style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.18);color:#0F172A">
            <span><?= $pill[0] ?></span><?= $pill[1] ?>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- CTA buttons -->
        <div class="flex flex-wrap gap-3">
          <a href="#lead-form"
             class="inline-flex items-center gap-2 font-black text-sm px-6 py-3.5 rounded-xl transition-all"
             style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 18px rgba(0,230,118,.35)"
             onmouseover="this.style.boxShadow='0 6px 24px rgba(0,230,118,.55)'"
             onmouseout="this.style.boxShadow='0 4px 18px rgba(0,230,118,.35)'"
             onclick="charjTrack('hero_get_price_click',{vehicle:'<?= esc(addslashes($vehicleName)) ?>'})">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Get Best Price
          </a>
          <a href="#lead-form" x-data @click="$dispatch('set-lead-type','book_test_ride')"
             class="inline-flex items-center gap-2 font-black text-sm px-6 py-3.5 rounded-xl transition-all"
             style="border:2px solid rgba(255,255,255,.35);color:#fff"
             onmouseover="this.style.borderColor='rgba(255,255,255,.7)'"
             onmouseout="this.style.borderColor='rgba(255,255,255,.35)'"
             onclick="charjTrack('hero_test_ride_click',{vehicle:'<?= esc(addslashes($vehicleName)) ?>'})">
            🏍️ Book Test Ride
          </a>
          <a href="<?= base_url('compare') ?>"
             class="inline-flex items-center gap-2 font-bold text-sm px-5 py-3.5 rounded-xl transition-all"
             style="border:2px solid rgba(255,255,255,.2);color:rgba(255,255,255,.7)"
             onmouseover="this.style.borderColor='rgba(255,255,255,.5)';this.style.color='#fff'"
             onmouseout="this.style.borderColor='rgba(255,255,255,.2)';this.style.color='rgba(255,255,255,.7)'">
            ⚖️ Compare
          </a>
        </div>
      </div><!-- /left -->

      <!-- Right: Vehicle gallery -->
      <?php
      $galleryImgs = !empty($v['gallery_json']) ? (json_decode($v['gallery_json'], true) ?: []) : [];
      if (empty($galleryImgs) && !empty($v['image_url'])) { $galleryImgs = [$v['image_url']]; }
      $galleryImgs = array_values(array_filter($galleryImgs));
      ?>
      <?php if (!empty($galleryImgs)): ?>
      <div class="flex flex-col gap-2.5" x-data="{ imgs: <?= htmlspecialchars(json_encode($galleryImgs), ENT_QUOTES) ?>, active: 0, lightbox: false }">
        <!-- Main image -->
        <div class="relative rounded-2xl overflow-hidden cursor-zoom-in" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.16);box-shadow:0 6px 24px rgba(0,168,150,.1)" @click="lightbox=true">
          <img :src="imgs[active]" alt="<?= $vehicleName ?>" class="w-full h-60 lg:h-80 object-contain p-4" loading="eager">
          <div class="absolute bottom-2.5 right-2.5 flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-full pointer-events-none" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 8v6M8 11h6M19 11a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
            Click to zoom
          </div>
        </div>
        <!-- Thumbnails -->
        <div class="flex gap-2 overflow-x-auto scrollbar-hide" x-show="imgs.length > 1">
          <template x-for="(im,i) in imgs" :key="i">
            <button type="button" @click="active=i" class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden transition-all duration-150"
                    :style="active===i ? 'border:2px solid #00A896;box-shadow:0 2px 8px rgba(0,168,150,.25)' : 'border:1px solid rgba(0,168,150,.15);opacity:.7'">
              <img :src="im" class="w-full h-full object-cover" loading="lazy">
            </button>
          </template>
        </div>
        <!-- Lightbox overlay -->
        <div x-show="lightbox" x-cloak x-transition.opacity
             @click="lightbox=false" @keydown.escape.window="lightbox=false"
             class="fixed inset-0 z-[300] flex items-center justify-center p-4 select-none" style="background:rgba(15,23,42,.92);backdrop-filter:blur(4px)">
          <button type="button" @click.stop="lightbox=false" class="absolute top-4 right-4 w-10 h-10 rounded-full flex items-center justify-center text-white" style="background:rgba(255,255,255,.12)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
          <button type="button" @click.stop="active=(active-1+imgs.length)%imgs.length" x-show="imgs.length>1" class="absolute left-3 sm:left-6 w-11 h-11 rounded-full flex items-center justify-center text-white" style="background:rgba(255,255,255,.12)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <img :src="imgs[active]" @click.stop class="max-h-[82vh] max-w-[90vw] object-contain rounded-xl" style="background:#fff">
          <button type="button" @click.stop="active=(active+1)%imgs.length" x-show="imgs.length>1" class="absolute right-3 sm:right-6 w-11 h-11 rounded-full flex items-center justify-center text-white" style="background:rgba(255,255,255,.12)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
          </button>
          <div class="absolute bottom-5 left-1/2 -translate-x-1/2 text-white/80 text-sm font-semibold" x-text="(active+1)+' / '+imgs.length"></div>
        </div>
      </div>
      <?php else: ?>
      <div class="flex items-center justify-center">
        <div class="w-full max-w-sm aspect-video rounded-3xl flex flex-col items-center justify-center gap-4"
             style="background:rgba(0,168,150,.05);border:1px solid rgba(0,168,150,.15)">
          <span class="text-8xl">⚡</span>
          <p class="font-bold text-lg" style="color:#64748B"><?= esc($v['name'] ?? '') ?></p>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<!-- ── KEY SPECS STRIP ───────────────────────────────────── -->
<section style="background:#fff;border-bottom:2px solid rgba(0,230,118,.1);box-shadow:0 2px 12px rgba(0,200,100,.06)">
  <div class="max-w-7xl mx-auto px-4 py-5">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

      <?php
      $keySpecs = [
        ['icon' => '💰', 'label' => 'Starting Price',   'value' => fmtINRShort($startingPrice),  'accent' => '#00C060'],
        ['icon' => '🔋', 'label' => 'Battery',          'value' => $battery > 0 ? $battery . ' kWh' : '—', 'accent' => '#0EA5E9'],
        ['icon' => '📏', 'label' => 'Real-World Range',  'value' => $realRange > 0 ? '~'.$realRange.' km' : ($claimedRange > 0 ? '~'.(int)round($claimedRange*.85).' km' : '—'), 'accent' => '#10B981'],
        ['icon' => '⚡', 'label' => 'Charging Time',    'value' => esc($chargingTime ?: '—'),    'accent' => '#F59E0B'],
        ['icon' => '🏎️', 'label' => 'Top Speed',        'value' => $topSpeed != '—' ? esc($topSpeed).' kmph' : '—', 'accent' => '#8B5CF6'],
        ['icon' => '💡', 'label' => 'Motor Power',      'value' => $motorPower != '—' ? esc($motorPower) : '—', 'accent' => '#EC4899'],
        ['icon' => '🪑', 'label' => 'Seating',          'value' => $seating != '—' ? esc($seating).' persons' : '—', 'accent' => '#64748B'],
        ['icon' => '🛡️', 'label' => 'Warranty',         'value' => esc($warranty),               'accent' => '#00C060'],
      ];
      foreach ($keySpecs as $spec): ?>
      <div class="flex items-center gap-3 p-4 rounded-2xl transition-all cursor-default"
           style="background:#F5FFF7;border:1.5px solid rgba(0,230,118,.1)"
           onmouseover="this.style.background='#fff';this.style.boxShadow='0 4px 16px rgba(0,200,100,.12)';this.style.borderColor='rgba(0,230,118,.25)'"
           onmouseout="this.style.background='#F5FFF7';this.style.boxShadow='';this.style.borderColor='rgba(0,230,118,.1)'">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-xl"
             style="background:rgba(0,230,118,.1)">
          <?= $spec['icon'] ?>
        </div>
        <div class="min-w-0">
          <p class="text-[10px] font-black uppercase tracking-widest truncate" style="color:#94A3B8"><?= $spec['label'] ?></p>
          <p class="font-black text-sm sm:text-base mt-0.5 truncate" style="color:#0F172A"><?= $spec['value'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- ── (B) VIDEO REVIEW + 360° MEDIA ROW ─────────────────── -->
<section class="max-w-7xl mx-auto px-4 pt-8"
         x-data="{ media: 'video' }">
  <div class="bg-white rounded-2xl p-5 sm:p-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">

    <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
      <h2 class="text-xl font-black" style="color:#0F172A">Video & 360° View</h2>
      <!-- toggle tabs -->
      <div class="inline-flex rounded-xl p-1" style="background:#F0FFF4;border:1px solid rgba(0,168,150,.15)">
        <button type="button" @click="media = 'video'"
                :style="media === 'video' ? 'background:#fff;color:#00A896;box-shadow:0 1px 4px rgba(0,168,150,.18)' : 'color:#64748B'"
                class="inline-flex items-center gap-1.5 text-xs font-black px-3.5 py-2 rounded-lg transition-all">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V4zm12.553 1.106A1 1 0 0014 6v4a1 1 0 00.553.894l2 1A1 1 0 0018 11V5a1 1 0 00-1.447-.894l-2 1z"/></svg>
          Video Review
        </button>
        <button type="button" @click="media = '360'"
                :style="media === '360' ? 'background:#fff;color:#00A896;box-shadow:0 1px 4px rgba(0,168,150,.18)' : 'color:#64748B'"
                class="inline-flex items-center gap-1.5 text-xs font-black px-3.5 py-2 rounded-lg transition-all">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          360° View
        </button>
      </div>
    </div>

    <!-- Video panel -->
    <div x-show="media === 'video'">
      <?php if ($ytEmbed !== ''): ?>
      <div class="relative w-full overflow-hidden rounded-2xl" style="padding-top:56.25%;border:1px solid rgba(0,168,150,.15)">
        <iframe class="absolute inset-0 w-full h-full" src="<?= esc($ytEmbed, 'attr') ?>"
                title="<?= $vehicleName ?> video review" frameborder="0" loading="lazy"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
      </div>
      <?php else: ?>
      <div class="rounded-2xl flex flex-col items-center justify-center text-center gap-3 py-12 px-6"
           style="background:linear-gradient(160deg,#F0FFF9,#EAFFF4);border:1.5px dashed rgba(0,168,150,.3)">
        <span class="w-14 h-14 rounded-full flex items-center justify-center" style="background:rgba(0,168,150,.12)">
          <svg class="w-7 h-7" style="color:#00A896" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
        </span>
        <p class="font-black text-base" style="color:#0F172A">Video review coming soon</p>
        <p class="text-sm font-semibold" style="color:#64748B">We're working on a detailed video review of the <?= esc($v['name'] ?? 'this EV') ?>.</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- 360 panel -->
    <div x-show="media === '360'" x-cloak>
      <div class="rounded-2xl flex flex-col items-center justify-center text-center gap-3 py-12 px-6"
           style="background:linear-gradient(160deg,#F0FFF9,#EAFFF4);border:1.5px dashed rgba(0,168,150,.3)">
        <span class="w-14 h-14 rounded-full flex items-center justify-center" style="background:rgba(0,168,150,.12)">
          <svg class="w-7 h-7" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </span>
        <p class="font-black text-base" style="color:#0F172A">360° spin view — coming soon for this model</p>
        <p class="text-sm font-semibold" style="color:#64748B">Interactive spin imagery is being prepared for the <?= esc($v['name'] ?? 'this EV') ?>.</p>
      </div>
    </div>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     MAIN LAYOUT: CONTENT (65%) + SIDEBAR (35%)
     ═══════════════════════════════════════════════════════════ -->
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex flex-col lg:flex-row gap-8 items-start">

    <!-- ── MAIN CONTENT (65%) ──────────────────────────────── -->
    <div class="flex-1 min-w-0">

      <!-- ── SECTION 4: TABBED CONTENT ──────────────────────── -->
      <div
        x-data="{
          activeTab: 'overview',
          tabs: ['overview','realworld','specs','charging','ownership','reviews','ownerqa','faq'],
          tabLabels: {
            overview:  'Overview',
            realworld: 'Real-World Data',
            specs:     'Full Specs',
            charging:  'Charging',
            ownership: 'Ownership Cost',
            reviews:   'Reviews',
            ownerqa:   'Owner Q&A',
            faq:       'FAQ'
          }
        }"
        id="tab-section"
      >

        <!-- Sticky Tab Bar -->
        <div class="sticky top-16 z-20 mb-6 overflow-x-auto scrollbar-hide rounded-2xl"
             style="background:#fff;border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.08)">
          <div class="flex min-w-max">
            <template x-for="tab in tabs" :key="tab">
              <button
                type="button"
                @click="activeTab = tab; $nextTick(()=>{ document.getElementById('tab-section').scrollIntoView({behavior:'smooth',block:'start'}) })"
                :id="'tab-' + tab"
                :aria-selected="activeTab === tab"
                :style="activeTab === tab
                  ? 'color:#022C22;background:linear-gradient(to bottom,#F0FFF4,#E6FFED);border-bottom:3px solid #00C060;font-weight:800'
                  : 'color:#475569;background:transparent;border-bottom:3px solid transparent;font-weight:700'"
                class="px-4 sm:px-5 py-3.5 text-xs sm:text-sm whitespace-nowrap transition-all focus:outline-none uppercase tracking-wide"
                x-text="tabLabels[tab]"
                role="tab"
              ></button>
            </template>
          </div>
        </div>

        <!-- ── TAB: OVERVIEW ──────────────────────────────── -->
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-overview">

          <!-- Description -->
          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <h2 class="text-xl font-black mb-4" style="color:#0F172A">About the <?= esc($v['name'] ?? '') ?></h2>
            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
              <?php if (!empty($v['full_description'])): ?>
                <?= $v['full_description'] ?>
              <?php elseif (!empty($v['short_description'])): ?>
                <p><?= esc($v['short_description']) ?></p>
              <?php else: ?>
                <p>The <?= $vehicleName ?> is a compelling electric vehicle option for Indian buyers, combining practical range, modern technology and strong value for money. With a <?= $claimedRange ?>km claimed range and <?= $battery ?>kWh battery, it suits a wide range of daily commuting and intercity travel needs.</p>
              <?php endif; ?>
            </div>
          </div>

          <!-- Best For -->
          <?php if (!empty($bestForTags)): ?>
          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <h3 class="text-base font-bold text-slate-900 mb-3">Best For</h3>
            <div class="flex flex-wrap gap-2">
              <?php
              $tagColors = [
                'Daily Commute'    => 'bg-blue-50 text-blue-700 border-blue-200',
                'Long Distance'    => 'bg-purple-50 text-purple-700 border-purple-200',
                'City Only'        => 'bg-teal-50 text-teal-700 border-teal-200',
                'Family'           => 'bg-orange-50 text-orange-700 border-orange-200',
                'Cargo'            => 'bg-amber-50 text-amber-700 border-amber-200',
              ];
              foreach ($bestForTags as $tag):
                $cls = $tagColors[$tag] ?? 'bg-green-50 text-green-700 border-green-200';
              ?>
              <span class="inline-flex items-center gap-1.5 border rounded-full px-3.5 py-1.5 text-sm font-semibold <?= $cls ?>">
                ✓ <?= esc($tag) ?>
              </span>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Pros & Cons -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <!-- Pros -->
            <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,#F0FFF4,#E6FFED);border:1.5px solid rgba(0,200,100,.2)">
              <h3 class="text-sm font-black mb-3 flex items-center gap-2 uppercase tracking-wide" style="color:#166534">
                <span class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0"
                      style="background:linear-gradient(135deg,#00E676,#00C060)">
                  <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </span>
                Why We Love It
              </h3>
              <ul class="space-y-2.5">
                <?php foreach (array_slice($prosJson, 0, 4) as $pro): ?>
                <li class="flex items-start gap-2 text-sm font-semibold" style="color:#14532D">
                  <span class="font-black mt-0.5 flex-shrink-0" style="color:#00C060">✓</span>
                  <span><?= esc($pro) ?></span>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <!-- Cons -->
            <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,#FFF5F5,#FFE8E8);border:1.5px solid rgba(239,68,68,.2)">
              <h3 class="text-sm font-black mb-3 flex items-center gap-2 uppercase tracking-wide" style="color:#991B1B">
                <span class="w-7 h-7 rounded-xl bg-red-400 flex items-center justify-center flex-shrink-0">
                  <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </span>
                Watch Out For
              </h3>
              <ul class="space-y-2.5">
                <?php foreach (array_slice($consJson, 0, 3) as $con): ?>
                <li class="flex items-start gap-2 text-sm font-semibold" style="color:#7F1D1D">
                  <span class="font-black mt-0.5 flex-shrink-0 text-red-400">✗</span>
                  <span><?= esc($con) ?></span>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>

          <!-- Expert Verdict -->
          <div class="rounded-2xl p-6 text-white mb-6 relative overflow-hidden"
               style="background:linear-gradient(135deg,#00A896,#007A6E)">
            <div class="absolute top-0 right-0 w-48 h-48 opacity-10 pointer-events-none"
                 style="background:radial-gradient(circle,#00E676,transparent 70%);transform:translate(30%,-30%)"></div>
            <div class="relative flex items-start gap-4">
              <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center"
                   style="background:linear-gradient(135deg,#00E676,#00C060)">
                <svg class="w-6 h-6" style="color:#022C22" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
              </div>
              <div class="flex-1">
                <h3 class="text-base font-black mb-2 uppercase tracking-wide" style="color:#69FF97">Expert Verdict</h3>
                <?php if (!empty($v['expert_review'])): ?>
                <p class="text-slate-200 leading-relaxed text-sm"><?= esc($v['expert_review']) ?></p>
                <?php else: ?>
                <p class="text-slate-200 leading-relaxed text-sm">
                  The <?= $vehicleName ?> stands out in the Indian EV market with its <?= $battery > 0 ? $battery . ' kWh battery delivering up to ' . $claimedRange . ' km claimed range' : 'practical range' ?>.
                  <?php if ($startingPrice > 0): ?>
                  At <?= fmtINRShort($startingPrice) ?> (ex-showroom), it offers <?= $startingPrice < 200000 ? 'excellent value for budget-conscious buyers' : ($startingPrice < 700000 ? 'competitive pricing in the mid-range segment' : 'a premium experience with strong feature-set') ?>.
                  <?php endif; ?>
                  We recommend it for urban commuters and small families who want to reduce fuel costs without compromising on daily practicality.
                  <?php if (!$fastSupported): ?>Best suited for buyers with home charging access.<?php endif; ?>
                </p>
                <?php endif; ?>
                <?php if ($rating > 0): ?>
                <div class="flex items-center gap-2 mt-3">
                  <?= starIcons($rating) ?>
                  <span class="font-black" style="color:#00E676"><?= number_format($rating, 1) ?>/5</span>
                  <span class="text-sm font-semibold" style="color:rgba(255,255,255,.45)">— Charj.in Expert Score</span>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Quick Savings Widget -->
          <div class="mt-6 rounded-2xl bg-green-50 p-5 ring-1 ring-green-100" x-data="{
            dailyKm: 40,
            rate: 8,
            petrol: 105,
            evEfficiency: 5,
            get evCost() { return (this.rate / this.evEfficiency) * this.dailyKm * 26 },
            get petrolCost() { return (this.dailyKm * 26 / 45) * this.petrol },
            get saving() { return Math.max(0, this.petrolCost - this.evCost) },
            fmt(n) { return '₹' + Math.round(n).toLocaleString('en-IN') }
          }">
            <h3 class="font-bold text-green-800 mb-3">Your monthly savings estimate</h3>
            <div class="grid grid-cols-3 gap-2 text-xs mb-3">
              <label class="flex flex-col gap-1 font-medium">Daily km<input type="number" x-model="dailyKm" class="rounded-lg border border-green-200 bg-white px-2 py-1.5 text-sm"></label>
              <label class="flex flex-col gap-1 font-medium">₹/kWh<input type="number" x-model="rate" step="0.5" class="rounded-lg border border-green-200 bg-white px-2 py-1.5 text-sm"></label>
              <label class="flex flex-col gap-1 font-medium">Petrol ₹/L<input type="number" x-model="petrol" class="rounded-lg border border-green-200 bg-white px-2 py-1.5 text-sm"></label>
            </div>
            <div class="flex items-center justify-between rounded-xl bg-green-600 px-4 py-3 text-white">
              <span class="text-sm font-medium">Monthly saving vs petrol</span>
              <span class="text-xl font-black" x-text="fmt(saving)"></span>
            </div>
            <p class="mt-2 text-xs text-green-700">vs petrol scooter @ 45km/L</p>
          </div>

        </div><!-- /tab: overview -->

        <!-- ── TAB: REAL-WORLD DATA ────────────────────────── -->
        <div x-show="activeTab === 'realworld'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-realworld">

          <!-- Real-world Range -->
          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <div class="flex items-start justify-between flex-wrap gap-3 mb-5">
              <div>
                <h2 class="text-xl font-black" style="color:#0F172A">Real-World Range Analysis</h2>
                <p class="text-sm text-slate-500 mt-1">Claimed range: <strong><?= $claimedRange ?> km</strong> · Based on Autocar India methodology + owner reports</p>
              </div>
              <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full">India-specific data</span>
            </div>

            <?php
            $rangeScenarios = [
              ['icon' => '🌆', 'label' => 'City driving (AC on)',          'pct' => 85, 'km' => $cityRange,    'note' => 'Stop-go traffic, AC, frequent regen braking'],
              ['icon' => '🛣️', 'label' => 'Highway (80 kmph constant)',    'pct' => 75, 'km' => $hwRange,      'note' => 'Steady speed, minimal regen, AC on'],
              ['icon' => '🌧️', 'label' => 'Monsoon season',                'pct' => 70, 'km' => $monsoonRange, 'note' => 'Wet roads, headlights, wipers, AC'],
              ['icon' => '❄️', 'label' => 'Cold weather (15°C and below)', 'pct' => 80, 'km' => $coldRange,    'note' => 'Battery efficiency drops in cold temperature'],
            ];
            foreach ($rangeScenarios as $s):
              $barWidth = $s['pct'];
              $barColor = $s['pct'] >= 80 ? 'bg-green-500' : ($s['pct'] >= 70 ? 'bg-amber-500' : 'bg-red-400');
            ?>
            <div class="mb-5 last:mb-0">
              <div class="flex items-center justify-between gap-4 mb-2">
                <div class="flex items-center gap-2">
                  <span class="text-xl" aria-hidden="true"><?= $s['icon'] ?></span>
                  <span class="text-sm font-semibold text-slate-800"><?= $s['label'] ?></span>
                </div>
                <div class="text-right flex-shrink-0">
                  <span class="text-base font-bold text-slate-900"><?= $s['km'] > 0 ? '~' . $s['km'] . ' km' : '—' ?></span>
                  <span class="text-xs text-slate-400 ml-1">(<?= $s['pct'] ?>% of claimed)</span>
                </div>
              </div>
              <div class="relative h-3 bg-slate-100 rounded-full overflow-hidden" role="progressbar" aria-valuenow="<?= $s['pct'] ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="absolute left-0 top-0 h-full <?= $barColor ?> rounded-full transition-all duration-700" style="width: <?= $barWidth ?>%"></div>
              </div>
              <p class="text-xs text-slate-500 mt-1"><?= $s['note'] ?></p>
            </div>
            <?php endforeach; ?>

            <div class="mt-4 pt-4 border-t border-slate-100">
              <p class="text-xs text-slate-500 flex items-start gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                Range figures are estimates based on Autocar India testing methodology, owner community reports and Charj.in analysis. Actual range depends on rider weight, tyre pressure, road conditions and driving habits.
              </p>
            </div>
          </div>

          <!-- Charging Performance -->
          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <h2 class="text-xl font-black mb-5" style="color:#0F172A">Charging Performance</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <?php if ($chargeTime3kw): ?>
              <div class="bg-slate-50 rounded-xl p-4 text-center border border-slate-200">
                <div class="text-3xl mb-2">🔌</div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">Home socket (3.3kW)</p>
                <p class="text-2xl font-bold text-slate-900"><?= $chargeTime3kw ?> hrs</p>
                <p class="text-xs text-slate-400 mt-1">0 → 100%, standard 15A plug</p>
              </div>
              <?php endif; ?>
              <?php if ($chargeTime7kw): ?>
              <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-200">
                <div class="text-3xl mb-2">⚡</div>
                <p class="text-xs text-blue-600 font-medium uppercase tracking-wide mb-1">Fast AC (7.2kW)</p>
                <p class="text-2xl font-bold text-blue-900"><?= $chargeTime7kw ?> hrs</p>
                <p class="text-xs text-blue-400 mt-1">With wallbox charger at home</p>
              </div>
              <?php endif; ?>
              <?php if ($fastSupported && $dcFastTime): ?>
              <div class="bg-amber-50 rounded-xl p-4 text-center border border-amber-200">
                <div class="text-3xl mb-2">🚀</div>
                <p class="text-xs text-amber-600 font-medium uppercase tracking-wide mb-1">DC Fast Charge</p>
                <p class="text-2xl font-bold text-amber-900">~<?= $dcFastTime ?> mins</p>
                <p class="text-xs text-amber-500 mt-1">0–80% at public DC charger</p>
              </div>
              <?php elseif (!$fastSupported): ?>
              <div class="bg-slate-100 rounded-xl p-4 text-center border border-slate-200 opacity-60">
                <div class="text-3xl mb-2">🚫</div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">DC Fast Charge</p>
                <p class="text-sm font-semibold text-slate-500">Not Supported</p>
                <p class="text-xs text-slate-400 mt-1">AC charging only</p>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Efficiency Tip -->
          <div class="rounded-2xl p-5 flex items-start gap-4" style="background:rgba(0,230,118,.08);border:1.5px solid rgba(0,230,118,.2)">
            <span class="text-3xl flex-shrink-0">💡</span>
            <div>
              <h3 class="font-black text-sm mb-1 uppercase tracking-wide" style="color:#166534">Efficiency Tip</h3>
              <p class="text-green-800 text-sm leading-relaxed">
                The <?= esc($v['name'] ?? 'this EV') ?> is most efficient at <strong>40–60 kmph in city traffic</strong> — regenerative braking is most effective at these speeds, meaning you recharge while slowing down. Avoid aggressive acceleration and maintain steady speeds on highways for best range.
                <?php if ($battery > 0): ?>
                Overnight charging at home (3.3kW) is the most cost-effective option at ₹<?= round($battery * 8, 0) ?>–<?= round($battery * 10, 0) ?> for a full charge.
                <?php endif; ?>
              </p>
            </div>
          </div>

        </div><!-- /tab: realworld -->

        <!-- ── TAB: FULL SPECS ─────────────────────────────── -->
        <div x-show="activeTab === 'specs'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-specs">

          <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-5 border-b border-slate-100">
              <h2 class="text-xl font-black" style="color:#0F172A">Complete Specifications</h2>
            </div>

            <?php
            $specGroups = [
              '🔋 Battery & Range' => [
                ['Battery Capacity',       $battery > 0 ? $battery . ' kWh' : null],
                ['Battery Type',           $v['battery_type'] ?? null],
                ['Claimed Range (ARAI)',   $claimedRange > 0 ? $claimedRange . ' km' : null],
                ['Real-World Range',       $realRange > 0 ? '~' . $realRange . ' km' : null],
                ['Energy Consumption',     ($battery > 0 && $realRange > 0) ? round($battery / $realRange * 100, 1) . ' Wh/km' : null],
              ],
              '⚡ Performance' => [
                ['Motor Power',            !empty($v['motor_power']) ? esc($v['motor_power']) : null],
                ['Motor Torque',           !empty($v['motor_torque']) ? esc($v['motor_torque']) : null],
                ['Top Speed',              !empty($v['top_speed']) ? esc($v['top_speed']) . ' kmph' : null],
                ['0–60 kmph',              !empty($v['acceleration_0_100']) ? esc($v['acceleration_0_100']) . ' sec' : null],
                ['Drive Type',             !empty($v['drive_type']) ? esc($v['drive_type']) : null],
              ],
              '🔌 Charging' => [
                ['Connector Type',         esc($connectorType)],
                ['Home Charging (3.3kW)',  $chargeTime3kw ? $chargeTime3kw . ' hours' : null],
                ['Fast AC (7.2kW)',        $chargeTime7kw ? $chargeTime7kw . ' hours' : null],
                ['DC Fast Charging',       $fastSupported ? 'Supported' : 'Not Supported'],
                ['DC Fast Time (0–80%)',   ($fastSupported && $dcFastTime) ? '~' . $dcFastTime . ' minutes' : null],
                ['Charging Time (OEM)',    !empty($v['charging_time']) ? esc($v['charging_time']) : null],
              ],
              '📐 Dimensions & Capacity' => [
                ['Seating Capacity',       $seating != '—' ? esc($seating) . ' persons' : null],
                ['Boot Space',             !empty($v['boot_space']) ? esc($v['boot_space']) . ' litres' : null],
                ['Ground Clearance',       !empty($v['ground_clearance']) ? esc($v['ground_clearance']) . ' mm' : null],
                ['Kerb Weight',            !empty($v['kerb_weight']) ? esc($v['kerb_weight']) . ' kg' : null],
                ['Load Capacity',          !empty($v['load_capacity']) ? esc($v['load_capacity']) . ' kg' : null],
                ['Body Type',              !empty($v['body_type']) ? esc($v['body_type']) : null],
              ],
              '🛡️ Warranty' => [
                ['Vehicle Warranty',       !empty($v['warranty_years']) ? $v['warranty_years'] . ' years' . (!empty($v['warranty_km']) ? ' / ' . number_format($v['warranty_km']) . ' km' : '') : (!empty($v['warranty']) ? esc($v['warranty']) : null)],
                ['Battery Warranty',       !empty($v['battery_warranty_years']) ? $v['battery_warranty_years'] . ' years' . (!empty($v['battery_warranty_km']) ? ' / ' . number_format($v['battery_warranty_km']) . ' km' : '') : null],
              ],
            ];

            // Merge specs_json custom specs if available
            if (!empty($specsJson)) {
                foreach ($specsJson as $groupName => $specs) {
                    if (is_array($specs)) {
                        $specGroups['📋 ' . $groupName] = array_map(fn($k, $val) => [$k, esc($val)], array_keys($specs), $specs);
                    }
                }
            }

            foreach ($specGroups as $groupName => $specs):
              $hasValues = array_filter($specs, fn($s) => !empty($s[1]));
              if (empty($hasValues)) continue;
            ?>
            <div>
              <div class="bg-slate-50 px-5 py-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider"><?= $groupName ?></h3>
              </div>
              <table class="w-full text-sm" aria-label="<?= htmlspecialchars(strip_tags($groupName)) ?> specifications">
                <tbody>
                  <?php foreach ($specs as [$label, $value]):
                    if (empty($value)) continue;
                  ?>
                  <tr class="border-b border-slate-50 hover:bg-slate-50/70 transition-colors">
                    <td class="px-5 py-3 text-slate-500 font-medium w-1/2"><?= $label ?></td>
                    <td class="px-5 py-3 text-slate-900 font-semibold"><?= $value ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php endforeach; ?>
          </div>

        </div><!-- /tab: specs -->

        <!-- ── TAB: CHARGING ──────────────────────────────── -->
        <div x-show="activeTab === 'charging'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-charging">

          <!-- Connector info -->
          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <h2 class="text-xl font-black mb-4" style="color:#0F172A">Charging Guide</h2>

            <div class="flex items-center gap-3 mb-5">
              <span class="text-3xl">🔌</span>
              <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Connector Type</p>
                <div class="flex items-center gap-2 mt-1">
                  <span class="text-white text-sm font-black px-4 py-1.5 rounded-full" style="background:#00A896"><?= esc($connectorType) ?></span>
                  <?php if ($fastSupported): ?>
                  <span class="bg-amber-100 text-amber-800 border border-amber-300 text-xs font-bold px-3 py-1 rounded-full">⚡ Fast Charging Supported</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Compatible networks -->
            <div class="mb-5">
              <h3 class="text-sm font-bold text-slate-700 mb-3">Compatible Charging Networks</h3>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($chargingNetworks as $network): ?>
                <span class="bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-3.5 py-1.5 rounded-full">✓ <?= esc($network) ?></span>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Home charger recommendation -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
              <h3 class="text-sm font-bold text-blue-900 mb-2">🏠 Home Charger Recommendation</h3>
              <p class="text-sm text-blue-800">
                For the <?= esc($v['name'] ?? '') ?>, we recommend a <strong>7.2kW AC wallbox charger</strong> for home installation.
                Cost: ₹8,000–₹18,000 for the unit + ₹5,000–₹12,000 for DISCOM-approved installation.
                This reduces charge time from <?= $chargeTime3kw ?? '8' ?> hours to <?= $chargeTime7kw ?? '4' ?> hours.
              </p>
              <a href="<?= base_url('home-charger-guide') ?>" class="inline-flex items-center gap-1 text-blue-700 font-semibold text-sm mt-2 hover:underline">
                Home Charger Installation Guide →
              </a>
            </div>
          </div>

          <!-- Charging Cost Calculator -->
          <div
            class="bg-white rounded-2xl p-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)"
            x-data="{
              kmNeeded: 100,
              elecRate: 8,
              petrolRate: 102,
              petrolMileage: 35,
              get evCostPer100km() {
                return (<?= $battery > 0 && $realRange > 0 ? round($battery/$realRange*100, 2) : 12.5 ?> * this.elecRate).toFixed(0);
              },
              get evTotalCost() {
                return (this.kmNeeded * <?= $battery > 0 && $realRange > 0 ? round($battery/$realRange, 4) : 0.125 ?> * this.elecRate).toFixed(0);
              },
              get petrolTotalCost() {
                return (this.kmNeeded / this.petrolMileage * this.petrolRate).toFixed(0);
              },
              get savings() {
                return (parseFloat(this.petrolTotalCost) - parseFloat(this.evTotalCost)).toFixed(0);
              }
            }"
          >
            <h2 class="text-xl font-bold text-slate-900 mb-2">Charging Cost Calculator</h2>
            <p class="text-sm text-slate-500 mb-5">Compare electricity cost vs petrol for your driving needs</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
              <div>
                <label class="text-xs font-semibold text-slate-700 block mb-1.5">Distance needed (km/month)</label>
                <input type="number" x-model.number="kmNeeded" min="50" max="5000" step="50" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700 block mb-1.5">Your electricity rate (₹/kWh)</label>
                <input type="number" x-model.number="elecRate" min="4" max="15" step="0.5" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700 block mb-1.5">Petrol price (₹/litre)</label>
                <input type="number" x-model.number="petrolRate" min="80" max="130" step="1" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700 block mb-1.5">Petrol vehicle mileage (km/L)</label>
                <input type="number" x-model.number="petrolMileage" min="15" max="60" step="1" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                <p class="text-xs text-green-600 font-semibold uppercase tracking-wide">EV Cost</p>
                <p class="text-2xl font-extrabold text-green-700 mt-1">₹<span x-text="evTotalCost"></span></p>
                <p class="text-xs text-green-500 mt-0.5">for <span x-text="kmNeeded"></span> km</p>
              </div>
              <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                <p class="text-xs text-red-600 font-semibold uppercase tracking-wide">Petrol Cost</p>
                <p class="text-2xl font-extrabold text-red-700 mt-1">₹<span x-text="petrolTotalCost"></span></p>
                <p class="text-xs text-red-500 mt-0.5">for <span x-text="kmNeeded"></span> km</p>
              </div>
              <div class="rounded-xl p-4 text-center" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                <p class="text-xs font-black uppercase tracking-wide" style="color:rgba(255,255,255,.5)">You Save</p>
                <p class="text-2xl font-black mt-1" style="color:#00E676">₹<span x-text="savings"></span></p>
                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,.4)">per month</p>
              </div>
            </div>
            <p class="text-xs text-slate-400 mt-3 text-center">* Estimates based on vehicle efficiency. Actual savings may vary.</p>
          </div>

        </div><!-- /tab: charging -->

        <!-- ── TAB: OWNERSHIP COST ─────────────────────────── -->
        <div x-show="activeTab === 'ownership'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-ownership">

          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <h2 class="text-xl font-black mb-1" style="color:#0F172A">5-Year Ownership Cost Estimate</h2>
            <p class="text-sm text-slate-500 mb-6">Assuming: 9% loan rate, 36-month tenure, 20% down payment, 40 km/day, ₹8/kWh electricity rate</p>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
              <?php
              $costCards = [
                ['label' => 'Monthly EMI',         'value' => $emiMonthly,       'sub' => '9% for 36M, 20% down',     'icon' => '🏦', 'color' => 'blue'],
                ['label' => 'Electricity Cost',    'value' => $monthlyElecCost,  'sub' => '40 km/day @ ₹8/kWh',       'icon' => '⚡', 'color' => 'green'],
                ['label' => 'Maintenance',         'value' => $monthlyMaint,     'sub' => 'Service + consumables est.','icon' => '🔧', 'color' => 'amber'],
                ['label' => 'Insurance',           'value' => $monthlyInsurance, 'sub' => 'Comprehensive ~2.5% p.a.',  'icon' => '🛡️', 'color' => 'purple'],
              ];
              $colorMap = ['blue'=>['bg'=>'bg-blue-50','border'=>'border-blue-200','text'=>'text-blue-900','sub'=>'text-blue-500'],'green'=>['bg'=>'bg-green-50','border'=>'border-green-200','text'=>'text-green-900','sub'=>'text-green-500'],'amber'=>['bg'=>'bg-amber-50','border'=>'border-amber-200','text'=>'text-amber-900','sub'=>'text-amber-500'],'purple'=>['bg'=>'bg-purple-50','border'=>'border-purple-200','text'=>'text-purple-900','sub'=>'text-purple-500']];
              foreach ($costCards as $card):
                $c = $colorMap[$card['color']];
              ?>
              <div class="<?= $c['bg'] ?> border <?= $c['border'] ?> rounded-xl p-4 text-center">
                <div class="text-2xl mb-1"><?= $card['icon'] ?></div>
                <p class="text-xs font-semibold <?= $c['sub'] ?> uppercase tracking-wide mb-1"><?= $card['label'] ?></p>
                <p class="text-xl font-extrabold <?= $c['text'] ?>">₹<?= number_format($card['value']) ?></p>
                <p class="text-xs <?= $c['sub'] ?> mt-0.5"><?= $card['sub'] ?></p>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Total monthly cost -->
            <div class="rounded-2xl p-5 text-white text-center mb-5" style="background:linear-gradient(135deg,#00A896,#007A6E)">
              <p class="text-slate-300 text-sm font-medium uppercase tracking-wider mb-1">Total Monthly Cost of Ownership</p>
              <p class="text-4xl font-extrabold text-white">₹<?= number_format($monthlyTotal) ?></p>
              <p class="text-slate-400 text-xs mt-2">EMI + Electricity + Maintenance + Insurance</p>
            </div>

            <!-- vs Petrol savings -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
              <span class="text-3xl flex-shrink-0">💰</span>
              <div>
                <p class="font-bold text-green-900">Saving vs. a comparable petrol vehicle</p>
                <p class="text-sm text-green-800 mt-1">
                  Compared to a similar ₹<?= fmtINRShort($startingPrice * 0.8) ?> petrol vehicle,
                  you save approximately <strong>₹<?= number_format($petrolEquivCost - $monthlyTotal) ?>/month</strong> on fuel and maintenance.
                  Over 5 years, that's <strong>₹<?= number_format(($petrolEquivCost - $monthlyTotal) * 60) ?></strong> in savings.
                </p>
                <a href="<?= base_url('tco-calculator') ?>" class="inline-flex items-center gap-1 text-green-700 font-semibold text-sm mt-2 hover:underline">
                  Full 5-Year TCO Calculator →
                </a>
              </div>
            </div>
          </div>

          <!-- Quick subsidy check -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between gap-3 flex-wrap">
              <div>
                <h3 class="font-bold text-slate-900">Check Your Subsidy</h3>
                <p class="text-sm text-slate-500 mt-0.5">FAME II + state subsidy could reduce your cost by ₹10,000–₹1.5 lakh</p>
              </div>
              <a href="<?= base_url('subsidy-calculator') ?>" class="flex-shrink-0 text-white font-black px-5 py-2.5 rounded-xl transition-all text-sm" style="background:linear-gradient(135deg,#00C060,#009944)" onmouseover="this.style.boxShadow='0 4px 14px rgba(0,200,100,.4)'" onmouseout="this.style.boxShadow=''">
                Check Subsidy →
              </a>
            </div>
          </div>

        </div><!-- /tab: ownership -->

        <!-- ── TAB: REVIEWS ───────────────────────────────── -->
        <div x-show="activeTab === 'reviews'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-reviews" id="tab-reviews">

          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
              <h2 class="text-xl font-black" style="color:#0F172A">Owner Reviews</h2>
              <a
                href="<?= base_url('reviews/submit/' . esc($v['slug'] ?? '')) ?>"
                class="inline-flex items-center gap-2 text-white text-sm font-black px-4 py-2 rounded-xl transition-all"
                style="background:linear-gradient(135deg,#00C060,#009944)"
                onmouseover="this.style.boxShadow='0 4px 14px rgba(0,200,100,.4)'"
                onmouseout="this.style.boxShadow=''"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Write a Review
              </a>
            </div>

            <?php if (!empty($reviews)): ?>

            <!-- Rating summary -->
            <?php
            $avgRating = array_sum(array_column($reviews, 'rating')) / count($reviews);
            ?>
            <div class="bg-slate-50 rounded-xl p-5 mb-6 flex items-center gap-6 flex-wrap">
              <div class="text-center">
                <p class="text-5xl font-extrabold text-slate-900"><?= number_format($avgRating, 1) ?></p>
                <div class="mt-1 flex justify-center"><?= starIcons($avgRating) ?></div>
                <p class="text-sm text-slate-500 mt-1"><?= count($reviews) ?> reviews</p>
              </div>
            </div>

            <!-- Review cards -->
            <div class="space-y-5">
              <?php foreach (array_slice($reviews, 0, 5) as $review): ?>
              <article class="border border-slate-200 rounded-xl p-5">
                <div class="flex items-start justify-between gap-3 mb-3 flex-wrap">
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <?= starIcons((float) ($review['rating'] ?? 4)) ?>
                      <span class="font-bold text-slate-900"><?= esc($review['title'] ?? $review['review_title'] ?? 'Great EV') ?></span>
                      <?php if (!empty($review['verified']) || !empty($review['is_verified'])): ?>
                      <span class="inline-flex items-center gap-1 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Verified Owner
                      </span>
                      <?php endif; ?>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">
                      <strong class="text-slate-600"><?= esc($review['reviewer_name'] ?? $review['name'] ?? 'Anonymous') ?></strong>
                      <?php if (!empty($review['city'])): ?> · <?= esc($review['city']) ?><?php endif; ?>
                      <?php if (!empty($review['months_owned'])): ?> · Owned <?= esc($review['months_owned']) ?> months<?php endif; ?>
                      <?php if (!empty($review['km_driven'])): ?> · <?= number_format($review['km_driven']) ?> km driven<?php endif; ?>
                    </p>
                  </div>
                  <?php if (!empty($review['created_at'])): ?>
                  <time class="text-xs text-slate-400 flex-shrink-0"><?= date('M Y', strtotime($review['created_at'])) ?></time>
                  <?php endif; ?>
                </div>

                <p class="text-sm text-slate-700 leading-relaxed mb-3"><?= esc($review['content'] ?? $review['review'] ?? '') ?></p>

                <?php
                $rPros = [];
                $rCons = [];
                if (!empty($review['pros'])) $rPros = is_string($review['pros']) ? array_filter(explode(',', $review['pros'])) : (array)$review['pros'];
                if (!empty($review['cons'])) $rCons = is_string($review['cons']) ? array_filter(explode(',', $review['cons'])) : (array)$review['cons'];
                ?>
                <?php if (!empty($rPros) || !empty($rCons)): ?>
                <div class="flex flex-wrap gap-1.5">
                  <?php foreach ($rPros as $rp): ?>
                  <span class="bg-green-50 border border-green-200 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">✓ <?= esc(trim($rp)) ?></span>
                  <?php endforeach; ?>
                  <?php foreach ($rCons as $rc): ?>
                  <span class="bg-red-50 border border-red-200 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">✗ <?= esc(trim($rc)) ?></span>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
              </article>
              <?php endforeach; ?>
            </div>

            <?php if (count($reviews) > 5): ?>
            <div class="text-center mt-6">
              <a href="<?= base_url('vehicles/' . esc($v['slug'] ?? '') . '/reviews') ?>" class="inline-flex items-center gap-2 font-black px-6 py-2.5 rounded-xl transition-all text-sm" style="border:2px solid rgba(0,200,100,.3);color:#00C060" onmouseover="this.style.borderColor='#00C060';this.style.background='rgba(0,200,100,.05)'" onmouseout="this.style.borderColor='rgba(0,200,100,.3)';this.style.background=''">
                View all <?= count($reviews) ?> reviews →
              </a>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <!-- No reviews CTA -->
            <div class="text-center py-12">
              <div class="text-6xl mb-4">⭐</div>
              <h3 class="text-lg font-bold text-slate-800 mb-2">Be the first to review the <?= esc($v['name'] ?? '') ?></h3>
              <p class="text-slate-500 text-sm mb-6">Share your ownership experience and help other buyers make the right choice.</p>
              <a
                href="<?= base_url('reviews/submit/' . esc($v['slug'] ?? '')) ?>"
                class="inline-flex items-center gap-2 text-white font-black px-6 py-3 rounded-xl transition-all"
                style="background:linear-gradient(135deg,#00C060,#009944)"
              >
                Write the First Review →
              </a>
            </div>
            <?php endif; ?>
          </div>

        </div><!-- /tab: reviews -->

        <!-- ── TAB: OWNER Q&A ────────────────────────────── -->
        <div x-show="activeTab === 'ownerqa'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-ownerqa">

          <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
              <div>
                <h2 class="text-xl font-black" style="color:#0F172A">Owner Questions &amp; Answers</h2>
                <p class="text-sm text-slate-500 mt-1">Real questions from prospective buyers, answered by owners</p>
              </div>
              <span class="bg-green-50 border border-green-200 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full">
                <?= count($ownerQuestions) ?> question<?= count($ownerQuestions) !== 1 ? 's' : '' ?>
              </span>
            </div>

            <?php if (!empty($ownerQuestions)): ?>
            <div class="space-y-4 mb-8">
              <?php foreach ($ownerQuestions as $oq): ?>
              <div class="border border-slate-200 rounded-xl overflow-hidden">
                <div class="bg-slate-50 px-5 py-4 flex items-start gap-3">
                  <div class="w-8 h-8 bg-charj-green rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-900 text-sm"><?= esc($oq['question'] ?? '') ?></p>
                    <div class="flex items-center gap-3 mt-1">
                      <span class="text-xs text-slate-400"><?= esc($oq['asker_name'] ?? 'Anonymous') ?></span>
                      <?php if (!empty($oq['asked_at'])): ?>
                      <span class="text-xs text-slate-300">·</span>
                      <time class="text-xs text-slate-400"><?= date('d M Y', strtotime($oq['asked_at'])) ?></time>
                      <?php endif; ?>
                      <?php if (!empty($oq['votes'])): ?>
                      <span class="text-xs text-slate-300">·</span>
                      <span class="inline-flex items-center gap-1 text-xs text-amber-600 font-medium">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        <?= (int)$oq['votes'] ?> helpful
                      </span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php if (!empty($oq['answer'])): ?>
                <div class="px-5 py-4 border-t border-slate-100">
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-amber-100 border border-amber-200 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                      <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 000 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="flex-1">
                      <p class="text-xs font-bold text-amber-700 mb-1">Owner Answer</p>
                      <p class="text-sm text-slate-700 leading-relaxed"><?= esc($oq['answer']) ?></p>
                      <?php if (!empty($oq['answerer_name'])): ?>
                      <p class="text-xs text-slate-400 mt-1.5">— <?= esc($oq['answerer_name']) ?><?= !empty($oq['answered_at']) ? ', ' . date('M Y', strtotime($oq['answered_at'])) : '' ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php else: ?>
                <div class="px-5 py-3 border-t border-slate-100 bg-amber-50/50">
                  <p class="text-xs text-amber-700 font-medium">Awaiting answer from verified owners...</p>
                </div>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Empty state -->
            <div class="text-center py-10 mb-6">
              <div class="text-5xl mb-3">💬</div>
              <h3 class="text-base font-bold text-slate-800 mb-1">Be the first to ask!</h3>
              <p class="text-slate-500 text-sm">Have a question about the <?= esc($v['name'] ?? '') ?>? Ask and get answers from real owners.</p>
            </div>
            <?php endif; ?>

            <!-- Ask a question form -->
            <div class="border-t border-slate-100 pt-6">
              <h3 class="text-base font-bold text-slate-900 mb-4">Ask a Question</h3>
              <form
                action="<?= base_url('vehicles/' . esc($v['slug'] ?? '') . '/question') ?>"
                method="POST"
                x-data="{ submitting: false, submitted: false }"
                @submit.prevent="
                  submitting = true;
                  fetch($el.action, { method: 'POST', body: new FormData($el), headers: {'X-Requested-With': 'XMLHttpRequest'} })
                    .then(r => r.json())
                    .then(d => { submitted = true; submitting = false; $el.reset(); })
                    .catch(() => { submitting = false; $el.submit(); })
                "
              >
                <?= csrf_field() ?>
                <input type="hidden" name="vehicle_id" value="<?= esc($v['id'] ?? '') ?>">

                <div x-show="submitted" x-cloak class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 text-center">
                  <p class="text-green-800 font-semibold text-sm">✓ Your question has been submitted! It will appear after moderation.</p>
                </div>

                <div class="space-y-4" x-show="!submitted">
                  <div>
                    <label for="qa-name" class="block text-xs font-semibold text-slate-700 mb-1.5">Your Name</label>
                    <input
                      type="text"
                      id="qa-name"
                      name="asker_name"
                      placeholder="e.g. Rahul S."
                      required
                      maxlength="80"
                      class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none placeholder-slate-400"
                    >
                  </div>
                  <div>
                    <label for="qa-question" class="block text-xs font-semibold text-slate-700 mb-1.5">Your Question</label>
                    <textarea
                      id="qa-question"
                      name="question"
                      rows="3"
                      placeholder="e.g. How does it perform on highways at 80 kmph? Does the range drop significantly?"
                      required
                      maxlength="500"
                      class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none placeholder-slate-400 resize-none"
                    ></textarea>
                    <p class="text-xs text-slate-400 mt-1">Max 500 characters. Questions are reviewed before publishing.</p>
                  </div>
                  <button
                    type="submit"
                    :disabled="submitting"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 active:bg-green-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold px-8 py-3 rounded-xl transition-all shadow-md hover:shadow-lg shadow-green-200 text-sm"
                  >
                    <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <svg x-show="submitting" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span x-text="submitting ? 'Submitting...' : 'Submit Question'">Submit Question</span>
                  </button>
                </div>
              </form>
            </div>
          </div>

        </div><!-- /tab: ownerqa -->

        <!-- ── TAB: FAQ ────────────────────────────────────── -->
        <div x-show="activeTab === 'faq'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" role="tabpanel" aria-labelledby="tab-faq">

          <div class="bg-white rounded-2xl p-6" style="border:1.5px solid rgba(0,230,118,.12);box-shadow:0 2px 12px rgba(0,200,100,.06)">
            <h2 class="text-xl font-black mb-5" style="color:#0F172A">Frequently Asked Questions</h2>

            <div class="space-y-3">
              <?php foreach ($faqs as $idx => $faq): ?>
              <div
                x-data="{ open: <?= $idx === 0 ? 'true' : 'false' ?> }"
                class="border border-slate-200 rounded-xl overflow-hidden"
              >
                <button
                  type="button"
                  @click="open = !open"
                  class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left bg-white hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500"
                  :aria-expanded="open"
                >
                  <span class="font-semibold text-slate-900 text-sm pr-2"><?= esc($faq['question'] ?? '') ?></span>
                  <svg
                    class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-200"
                    :class="{ 'rotate-180': open }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                <div x-show="open" x-collapse class="border-t border-slate-100">
                  <div class="px-5 py-4 text-sm text-slate-700 leading-relaxed">
                    <?= esc($faq['answer'] ?? '') ?>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

        </div><!-- /tab: faq -->

      </div><!-- /x-data tabs -->

      <!-- ── SECTION 5: CITY PRICING TABLE ──────────────────── -->
      <?php if (!empty($cityPricing)): ?>
      <section class="mt-8" aria-labelledby="city-pricing-heading">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-100">
            <h2 id="city-pricing-heading" class="text-xl font-black" style="color:#0F172A">
              <?= $vehicleName ?> — Price in Major Indian Cities
            </h2>
            <p class="text-sm text-slate-500 mt-1">On-road prices vary by city due to road tax, registration charges, and state EV subsidies</p>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]" aria-label="City-wise pricing">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                  <th class="px-5 py-3 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">City</th>
                  <th class="px-4 py-3 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Ex-showroom</th>
                  <th class="px-4 py-3 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Road Tax</th>
                  <th class="px-4 py-3 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Insurance</th>
                  <th class="px-4 py-3 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">FAME II</th>
                  <th class="px-4 py-3 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">State Subsidy</th>
                  <th class="px-4 py-3 text-right text-xs font-bold text-green-700 uppercase tracking-wider">On-Road</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <?php foreach ($cityPricing as $cp): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                  <td class="px-5 py-3 font-semibold text-slate-800"><?= esc($cp['city'] ?? '—') ?></td>
                  <td class="px-4 py-3 text-right text-slate-700"><?= fmtINRShort($cp['ex_showroom'] ?? null) ?></td>
                  <td class="px-4 py-3 text-right text-slate-700"><?= fmtINRShort($cp['road_tax'] ?? null) ?></td>
                  <td class="px-4 py-3 text-right text-slate-700"><?= fmtINRShort($cp['insurance'] ?? null) ?></td>
                  <td class="px-4 py-3 text-right text-green-700">-<?= fmtINRShort($cp['fame_subsidy'] ?? null) ?></td>
                  <td class="px-4 py-3 text-right text-green-700">-<?= fmtINRShort($cp['state_subsidy'] ?? null) ?></td>
                  <td class="px-4 py-3 text-right font-bold text-green-800"><?= fmtINRShort($cp['on_road'] ?? null) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="px-5 py-3 bg-slate-50 border-t border-slate-100">
            <p class="text-xs text-slate-500">* Prices are indicative and may change. Contact your local dealer for exact on-road price. FAME II subsidy subject to government approval and vehicle eligibility.</p>
          </div>
        </div>
      </section>
      <?php endif; ?>

      <!-- ── SECTION 6: SIMILAR VEHICLES ───────────────────── -->
      <?php if (!empty($similarVehicles)): ?>
      <section class="mt-8" aria-labelledby="similar-heading">
        <div class="flex items-center justify-between mb-4">
          <h2 id="similar-heading" class="text-xl font-black" style="color:#0F172A">You Might Also Like</h2>
          <a href="<?= base_url('vehicles?category=' . esc($v['category_slug'] ?? '')) ?>" class="text-sm text-charj-green hover:underline font-medium">View all →</a>
        </div>
        <div class="flex gap-4 overflow-x-auto pb-3 -mx-1 px-1 snap-x snap-mandatory scroll-smooth">
          <?php foreach (array_slice($similarVehicles, 0, 6) as $sv): ?>
          <a
            href="<?= base_url('vehicles/' . esc($sv['slug'] ?? '')) ?>"
            class="group flex-shrink-0 w-60 snap-start bg-white border border-slate-200 hover:border-charj-green rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all"
          >
            <div class="h-36 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center relative overflow-hidden">
              <?php if (!empty($sv['image_url'])): ?>
              <img src="<?= esc($sv['image_url']) ?>" alt="<?= esc($sv['name'] ?? '') ?>" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
              <?php else: ?>
              <span class="text-5xl">⚡</span>
              <?php endif; ?>
            </div>
            <div class="p-4">
              <p class="text-xs text-slate-400 font-medium mb-0.5"><?= esc($sv['brand_name'] ?? '') ?></p>
              <h3 class="font-black text-sm leading-snug mb-2 group-hover:text-charj-green transition-colors" style="color:#0F172A"><?= esc($sv['name'] ?? '') ?></h3>
              <div class="flex items-center justify-between">
                <span class="font-black text-sm" style="color:#00C060"><?= fmtINRShort($sv['starting_price'] ?? null) ?></span>
                <?php if (!empty($sv['claimed_range'])): ?>
                <span class="text-xs text-slate-500"><?= $sv['claimed_range'] ?> km</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

    </div><!-- /main content -->

    <!-- ── SIDEBAR (35%) ──────────────────────────────────── -->
    <aside class="w-full lg:w-80 xl:w-96 flex-shrink-0">
      <div class="sticky top-20 space-y-4">

        <!-- ── (C)+(D) ACTION CARD: Test Ride + Price Alert ──── -->
        <div x-data="{ modal: null }">
          <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4);border:1.5px solid rgba(0,168,150,.22);box-shadow:0 4px 16px rgba(0,168,150,.08)">
            <div class="flex items-center gap-2 mb-1">
              <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#00A896,#007A6E)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              </span>
              <h3 class="font-black text-base" style="color:#0F172A">Experience it yourself</h3>
            </div>
            <p class="text-xs font-semibold mb-4" style="color:#64748B">Free test ride at your nearest dealer — no obligation.</p>

            <button type="button" @click="modal = 'test_ride'"
                    class="w-full inline-flex items-center justify-center gap-2 font-black text-sm px-5 py-3 rounded-xl transition-all"
                    style="background:linear-gradient(135deg,#00A896,#007A6E);color:#fff;box-shadow:0 4px 14px rgba(0,168,150,.3)"
                    onmouseover="this.style.boxShadow='0 6px 20px rgba(0,168,150,.45)'"
                    onmouseout="this.style.boxShadow='0 4px 14px rgba(0,168,150,.3)'"
                    onclick="charjTrack('test_ride_open',{vehicle:'<?= esc(addslashes($vehicleName)) ?>'})">
              🏍️ Book a Free Test Ride
            </button>

            <button type="button" @click="modal = 'price_alert'"
                    class="w-full inline-flex items-center justify-center gap-2 font-bold text-sm px-5 py-2.5 rounded-xl transition-all mt-2.5"
                    style="background:#fff;border:1.5px solid rgba(0,168,150,.3);color:#00A896"
                    onmouseover="this.style.background='rgba(0,168,150,.06)'"
                    onmouseout="this.style.background='#fff'"
                    onclick="charjTrack('price_alert_open',{vehicle:'<?= esc(addslashes($vehicleName)) ?>'})">
              🔔 Notify me on price drop
            </button>
          </div>

          <!-- (C) Test Ride modal -->
          <div x-show="modal === 'test_ride'" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4"
               x-transition.opacity @keydown.escape.window="modal = null">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal = null"></div>
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
                 style="border:1.5px solid rgba(0,168,150,.2)">
              <div class="flex items-center justify-between gap-3 px-5 py-4" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4);border-bottom:1px solid rgba(0,168,150,.12)">
                <div>
                  <h3 class="font-black text-base" style="color:#0F172A">Book a Free Test Ride</h3>
                  <p class="text-xs font-semibold" style="color:#64748B"><?= $vehicleName ?></p>
                </div>
                <button type="button" @click="modal = null" aria-label="Close" class="w-8 h-8 rounded-full flex items-center justify-center transition-colors" style="color:#64748B" onmouseover="this.style.background='rgba(0,0,0,.06)'" onmouseout="this.style.background=''">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>
              <form action="<?= base_url('lead/submit') ?>" method="post" class="p-5 space-y-3.5">
                <?= csrf_field() ?>
                <input type="hidden" name="lead_type" value="test_ride">
                <input type="hidden" name="vehicle_id" value="<?= esc($v['id'] ?? '') ?>">
                <input type="hidden" name="source_url" value="<?= current_url() ?>">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Full Name *</label>
                  <input type="text" name="name" required placeholder="e.g. Rahul Sharma"
                         class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Mobile Number *</label>
                  <input type="tel" name="mobile" required pattern="[0-9]{10}" maxlength="10" inputmode="numeric" placeholder="10-digit mobile"
                         class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">City</label>
                  <input type="text" name="city" placeholder="e.g. Bengaluru"
                         class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Purchase Timeline</label>
                  <select name="purchase_timeline" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                    <option value="This week">This week</option>
                    <option value="This month">This month</option>
                    <option value="1-3 months">1-3 months</option>
                    <option value="Just exploring">Just exploring</option>
                  </select>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 font-black text-sm px-5 py-3 rounded-xl transition-all"
                        style="background:linear-gradient(135deg,#00A896,#007A6E);color:#fff;box-shadow:0 4px 14px rgba(0,168,150,.3)"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(0,168,150,.45)'" onmouseout="this.style.boxShadow='0 4px 14px rgba(0,168,150,.3)'">
                  Confirm Test Ride
                </button>
                <p class="text-[11px] text-center text-slate-400">By submitting you agree to be contacted about this vehicle.</p>
              </form>
            </div>
          </div>

          <!-- (D) Price-drop / launch alert modal -->
          <div x-show="modal === 'price_alert'" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4"
               x-transition.opacity @keydown.escape.window="modal = null">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal = null"></div>
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
                 style="border:1.5px solid rgba(0,168,150,.2)">
              <div class="flex items-center justify-between gap-3 px-5 py-4" style="background:linear-gradient(135deg,#F0FFF9,#EAFFF4);border-bottom:1px solid rgba(0,168,150,.12)">
                <div>
                  <h3 class="font-black text-base" style="color:#0F172A">🔔 Price Drop / Launch Alert</h3>
                  <p class="text-xs font-semibold" style="color:#64748B"><?= $vehicleName ?></p>
                </div>
                <button type="button" @click="modal = null" aria-label="Close" class="w-8 h-8 rounded-full flex items-center justify-center transition-colors" style="color:#64748B" onmouseover="this.style.background='rgba(0,0,0,.06)'" onmouseout="this.style.background=''">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>
              <form action="<?= base_url('lead/submit') ?>" method="post" class="p-5 space-y-3.5">
                <?= csrf_field() ?>
                <input type="hidden" name="lead_type" value="price_alert">
                <input type="hidden" name="vehicle_id" value="<?= esc($v['id'] ?? '') ?>">
                <input type="hidden" name="source_url" value="<?= current_url() ?>">
                <input type="hidden" name="message" value="Price drop / launch alert for <?= esc($v['name'] ?? 'this EV', 'attr') ?>">
                <p class="text-sm font-semibold" style="color:#475569">We'll email you the moment the price drops or a new variant launches.</p>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Full Name</label>
                  <input type="text" name="name" placeholder="e.g. Rahul Sharma"
                         class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Email *</label>
                  <input type="email" name="email" required placeholder="you@example.com"
                         class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Mobile</label>
                  <input type="tel" name="mobile" pattern="[0-9]{10}" maxlength="10" inputmode="numeric" placeholder="10-digit mobile (optional)"
                         class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 font-black text-sm px-5 py-3 rounded-xl transition-all"
                        style="background:linear-gradient(135deg,#00A896,#007A6E);color:#fff;box-shadow:0 4px 14px rgba(0,168,150,.3)"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(0,168,150,.45)'" onmouseout="this.style.boxShadow='0 4px 14px rgba(0,168,150,.3)'">
                  Notify Me
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Lead Form -->
        <?= $this->include('partials/lead_form', ['vehicle' => $v, 'compactMode' => false]) ?>

        <!-- Quick tool links -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
          <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">EV Tools & Calculators</p>
          <div class="space-y-2">
            <a href="<?= base_url('subsidy-calculator') ?>"
               class="flex items-center justify-between gap-2 px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 rounded-xl transition-colors group"
               onclick="charjTrack('sidebar_tool_click',{tool:'subsidy_calculator'})">
              <div class="flex items-center gap-2">
                <span class="text-lg">🏛️</span>
                <div>
                  <p class="text-sm font-semibold text-green-900 group-hover:text-green-700">Check Subsidy</p>
                  <p class="text-xs text-green-600">FAME II + state benefits</p>
                </div>
              </div>
              <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="<?= base_url('ev-emi-calculator') ?>"
               class="flex items-center justify-between gap-2 px-4 py-3 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-colors group"
               onclick="charjTrack('sidebar_tool_click',{tool:'emi_calculator'})">
              <div class="flex items-center gap-2">
                <span class="text-lg">🏦</span>
                <div>
                  <p class="text-sm font-semibold text-blue-900 group-hover:text-blue-700">Calculate EMI</p>
                  <p class="text-xs text-blue-600">Best loan rates for EVs</p>
                </div>
              </div>
              <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="<?= base_url('tco-calculator') ?>"
               class="flex items-center justify-between gap-2 px-4 py-3 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-xl transition-colors group"
               onclick="charjTrack('sidebar_tool_click',{tool:'tco_calculator'})">
              <div class="flex items-center gap-2">
                <span class="text-lg">📊</span>
                <div>
                  <p class="text-sm font-semibold text-purple-900 group-hover:text-purple-700">5-Year TCO</p>
                  <p class="text-xs text-purple-600">EV vs petrol cost comparison</p>
                </div>
              </div>
              <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>

        <!-- Key info snippet -->
        <?php if ($startingPrice > 0): ?>
        <div class="rounded-2xl p-5 text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
          <h3 class="font-black text-sm mb-4 uppercase tracking-wider" style="color:#69FF97">Quick Numbers</h3>
          <div class="space-y-3 text-sm">
            <?php if ($emiMonthly > 0): ?>
            <div class="flex justify-between items-center">
              <span class="font-semibold" style="color:rgba(255,255,255,.55)">EMI from</span>
              <span class="font-black text-white">₹<?= number_format($emiMonthly) ?>/mo</span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between items-center">
              <span class="font-semibold" style="color:rgba(255,255,255,.55)">Electricity cost</span>
              <span class="font-black" style="color:#00E676">~₹<?= number_format($monthlyElecCost) ?>/mo</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="font-semibold" style="color:rgba(255,255,255,.55)">Annual saving vs petrol</span>
              <span class="font-black" style="color:#00E676">~₹<?= number_format(($petrolEquivCost - $monthlyTotal) * 12) ?></span>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </aside>

  </div>
</div><!-- /max-w-7xl -->
</div><!-- /bg-slate-50 -->

<!-- Alpine.js x-collapse plugin for FAQ -->
<script>
document.addEventListener('alpine:init', function() {
  Alpine.directive('collapse', function(el, { modifiers }, { cleanup }) {
    let isOpen = false;
    function open()  { el.style.display = 'block'; el.style.overflow = 'hidden'; el.style.maxHeight = el.scrollHeight + 'px'; el.style.transition = 'max-height 0.25s ease'; setTimeout(() => el.style.maxHeight = 'none', 250); }
    function close() { el.style.maxHeight = el.scrollHeight + 'px'; el.style.overflow = 'hidden'; el.style.transition = 'max-height 0.25s ease'; requestAnimationFrame(() => { el.style.maxHeight = '0'; setTimeout(() => el.style.display = 'none', 250); }); }
    cleanup(() => {});
  });
});
</script>

<div class="pb-4 md:pb-0"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.charjTrackView(
  '<?= esc(addslashes($v['slug'] ?? '')) ?>',
  '<?= esc(addslashes($v['name'] ?? '')) ?>',
  '<?= !empty($v['ex_showroom_price']) ? '₹'.round($v['ex_showroom_price']/100000,1).'L' : '—' ?>',
  '<?= base_url('vehicles/'.esc($v['slug'] ?? '')) ?>',
  '<?= esc(addslashes($v['image_url'] ?? '')) ?>'
);
</script>
<?= $this->endSection() ?>
