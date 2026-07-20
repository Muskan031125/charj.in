<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
helper('variant');

if (!function_exists('fmtINR')) {
    function fmtINR(int|float|null $n): string {
        if ($n === null || $n === 0) return '—';
        if ($n >= 10000000) return '₹' . number_format($n / 10000000, 2) . ' Cr';
        if ($n >= 100000)   return '₹' . number_format($n / 100000, 2) . ' L';
        return '₹' . number_format((int)$n);
    }
}
if (!function_exists('calcEMI')) {
    function calcEMI(float $price, float $downPct = 0.20, float $annualRate = 9.0, int $months = 36): float {
        $principal = $price * (1 - $downPct);
        $r = ($annualRate / 12) / 100;
        if ($r == 0) return $principal / $months;
        return round($principal * $r * pow(1 + $r, $months) / (pow(1 + $r, $months) - 1));
    }
}
if (!function_exists('calcMonthlyFuel')) {
    function calcMonthlyFuel(float|null $kWh, float|null $rangeKm): float {
        if (!$kWh || !$rangeKm || $rangeKm == 0) return 0;
        return round(($kWh / $rangeKm) * 40 * 30 * 8);
    }
}
// Turns a relative asset path (e.g. "assets/images/vehicles/x.jpg") into a full URL;
// leaves already-absolute URLs (http/https, e.g. Pexels) untouched.
if (!function_exists('resolveImgUrl')) {
    function resolveImgUrl(string $u): string {
        return preg_match('#^https?://#i', $u) ? $u : base_url(ltrim($u, '/'));
    }
}

function detectWinners(array $vals, string $mode): array {
    $out  = array_fill(0, count($vals), false);
    $nums = [];
    foreach ($vals as $i => $v) {
        $n = preg_replace('/[^0-9.]/', '', (string)($v ?? ''));
        $nums[$i] = $n !== '' ? (float)$n : null;
    }
    $valid = array_filter($nums, fn($x) => $x !== null);
    if (count($valid) < 2) return $out;
    $best = ($mode === 'high') ? max($valid) : min($valid);
    foreach ($nums as $i => $n) {
        if ($n !== null && $n == $best) $out[$i] = true;
    }
    return $out;
}

/**
 * @param string $varKey  When non-empty ('price'|'range'|'battery'), columns that
 *                        have variant data render a reactive Alpine cell that
 *                        reflects the selected variant; others stay static.
 */
function cmpRow(string $label, array $vv, string $field, string $mode = 'text', string $unit = '', bool $formatPrice = false, string $varKey = '', array $colVariants = []): string {
    $count   = count($vv);
    $vals    = array_map(fn($v) => $v[$field] ?? null, $vv);
    $winners = ($mode !== 'text') ? detectWinners($vals, $mode) : array_fill(0, $count, false);
    $html    = '<div class="cmp-row row-reveal" style="grid-template-columns:200px repeat(' . $count . ',1fr)"'
             . ' onmouseenter="this.style.background=\'rgba(0,230,118,.03)\'" onmouseleave="this.style.background=\'\'">';
    $html   .= '<div class="px-4 py-3 flex items-center text-sm font-semibold leading-snug" style="background:rgba(0,230,118,.025);color:#374151;border-right:1px solid rgba(0,230,118,.07)">'
             . htmlspecialchars($label) . '</div>';
    foreach ($vals as $i => $raw) {
        $isW = $winners[$i] ?? false;
        if ($raw === null || $raw === '' || $raw === 0) {
            $display = '—';
        } elseif ($formatPrice) {
            $display = fmtINR((float)$raw);
        } else {
            $display = htmlspecialchars((string)$raw) . ($unit ? ' ' . $unit : '');
        }
        $reactive = $varKey !== '' && !empty($colVariants[$i] ?? []);
        $valAttr  = $reactive
            ? ' x-text="vCell(' . $i . ',\'' . $varKey . '\',' . json_encode($unit) . ')"'
            : '';
        $cellBg = $isW ? ';background:rgba(0,230,118,.08)' : ($i % 2 === 1 ? ';background:rgba(0,168,150,.02)' : '');
        $html .= '<div class="px-4 py-3.5 text-sm text-center font-medium" style="border-left:2px solid rgba(0,168,150,.14)' . $cellBg . '">';
        $html .= $isW
            ? '<span style="color:#00963C;font-weight:800;font-size:.88rem"' . $valAttr . '>' . $display . '</span><span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded-full font-black align-middle best-badge best-pulse" style="background:#00E676;color:#022C22">BEST</span>'
            : '<span style="color:#0F172A;font-weight:600"' . $valAttr . '>' . $display . '</span>';
        $html .= '</div>';
    }
    return $html . '</div>';
}

function cmpSection(string $label, int $count): string {
    return '<div class="cmp-section-label sec-reveal" style="grid-template-columns:200px repeat(' . $count . ',1fr)">'
         . '<div class="col-full" style="gap:10px">'
         . '<span class="text-[12px] font-black uppercase tracking-widest" style="color:#00963C;letter-spacing:.1em">' . $label . '</span>'
         . '</div></div>';
}

$vehicles      = $vehicles ?? [];
$allVehicles   = $allVehicles ?? [];
$count         = count($vehicles);
$hasComparison = $count >= 2;

$priceWinnerIdx = $rangeWinnerIdx = $cityWinnerIdx = null;
$winCounts = array_fill(0, $count, 0);
$metricFields  = [];
$bestIdx       = 0;
$bestVeh       = null;

$monthlyData = [];
foreach ($vehicles as $vv) {
    $emi  = $vv['starting_price'] ? calcEMI((float)$vv['starting_price']) : 0;
    $fuel = calcMonthlyFuel((float)($vv['battery_capacity'] ?? 0), (float)($vv['real_world_range'] ?? $vv['claimed_range'] ?? 0));
    $monthlyData[] = ['emi' => $emi, 'fuel' => $fuel, 'total' => $emi + $fuel];
}

if ($hasComparison) {
    $prices  = array_map(fn($v) => (float)($v['starting_price'] ?? 0), $vehicles);
    $ranges  = array_map(fn($v) => (float)($v['real_world_range'] ?? $v['claimed_range'] ?? 0), $vehicles);
    $weights = array_map(fn($v) => (float)($v['kerb_weight'] ?? 0), $vehicles);

    if (count(array_filter($prices,  fn($x) => $x > 0)) >= 2) $priceWinnerIdx = array_search(min(array_filter($prices,  fn($x)=>$x>0)), $prices);
    if (count(array_filter($ranges,  fn($x) => $x > 0)) >= 2) $rangeWinnerIdx = array_search(max(array_filter($ranges,  fn($x)=>$x>0)), $ranges);
    if (count(array_filter($weights, fn($x) => $x > 0)) >= 2) $cityWinnerIdx  = array_search(min(array_filter($weights, fn($x)=>$x>0)), $weights);
    elseif ($priceWinnerIdx !== null) $cityWinnerIdx = $priceWinnerIdx;

    $metricFields = [
        ['real_world_range','high'],['starting_price','low'],['battery_capacity','high'],
        ['expert_rating','high'],['motor_power','high'],['top_speed','high'],
    ];
    foreach ($metricFields as [$f, $m]) {
        $ww = detectWinners(array_map(fn($v) => $v[$f] ?? null, $vehicles), $m);
        foreach ($ww as $i => $w) { if ($w) $winCounts[$i]++; }
    }
    $totalWins = detectWinners(array_column($monthlyData, 'total'), 'low');
    foreach ($totalWins as $i => $w) { if ($w) $winCounts[$i]++; }

    $bestIdx = array_search(max($winCounts), $winCounts);
    $bestVeh = $vehicles[$bestIdx] ?? null;
}

/* ── Variant data: per-column trims for the live variant selector ── */
$colVariants = [];   // colIdx => [ ['name','battery','range','price','fast','popular'], ... ]
$colSelDef   = [];   // colIdx => default selected index (popular else 0)
foreach ($vehicles as $vi => $vv) {
    $vs = ev_variants($vv['slug'] ?? '');
    $colVariants[$vi] = $vs;
    $def = 0;
    foreach ($vs as $k => $variant) {
        if (!empty($variant['popular'])) { $def = $k; break; }
    }
    $colSelDef[$vi] = $def;
}
$hasAnyVariants = (bool) array_filter($colVariants);
?>

<style>
[x-cloak]{display:none!important}

/* Page wrapper */
.cmp-page-bg{background:#F5FFF7}

/* Comparison table */
/* overflow-anchor:none stops the browser auto-scrolling this container when async-loading
   vehicle photos shift its layout — without it, the sticky spec-label column ends up
   misaligned with data cells on load (looks like overlapping text). */
.cmp-table{overflow-x:auto;-webkit-overflow-scrolling:touch;overflow-anchor:none}
/* translateY (not translateX) for the reveal animation — a horizontal transform on rows
   inside this horizontally-scrollable table was confusing the browser's scroll position
   tracking and auto-scrolling the container, misaligning the sticky spec-label column. */
/* No transform here (opacity-only fade) — a transform on .cmp-row creates a new containing
   block for its sticky-positioned first child (the spec-label column), which breaks
   position:sticky and causes the label to render at the wrong offset, overlapping rows. */
.cmp-row{display:grid;border-bottom:1px solid rgba(0,230,118,.07);opacity:0;transition:opacity .35s ease}
.cmp-row.visible{opacity:1}
.cmp-row:hover{background:rgba(0,230,118,.025) !important}

/* ── MOBILE COMPARE TABLE ─────────────────────────────────── */
@media(max-width:767px){
  /* Scrollable wrapper */
  .cmp-table{
    overflow-x:auto;-webkit-overflow-scrolling:touch;
    box-shadow:inset -10px 0 14px -8px rgba(0,0,0,.07);
  }

  /* ── Grid override: 90px label + auto vehicle columns (min 110px each) ── */
  /* !important overrides PHP-generated inline grid-template-columns */
  .cmp-row,.cmp-section-label,
  #compare-table .grid{
    grid-template-columns:90px repeat(auto-fill,minmax(110px,1fr))!important;
  }
  /* Min-width: 90px label + 3×110px vehicles = 420px — better fit for 375px phones */
  .cmp-table>*,.cmp-row,.cmp-section-label{min-width:420px!important}

  /* ── Sticky spec-label (first column) ── */
  .cmp-row>div:first-child,
  #compare-table .grid>div:first-child{
    position:sticky;left:0;z-index:3;
    background:#fff;
    min-width:90px;max-width:90px;
    padding:6px 6px!important;
    font-size:9.5px!important;
    line-height:1.3!important;
    border-right:1px solid rgba(0,230,118,.14)!important;
    word-break:break-word;
  }
  .cmp-row:hover>div:first-child{background:rgba(0,230,118,.025)!important}
  /* Top-left "Specification" header cell */
  #compare-table .grid>div:first-child{background:linear-gradient(135deg,rgba(0,230,118,.07),rgba(0,230,118,.02))!important;z-index:4}

  /* ── Vehicle data cells: flex-col so value + BEST badge stack cleanly ── */
  .cmp-row>div:not(:first-child){
    padding:6px 4px!important;
    font-size:.78rem!important;
    display:flex!important;
    flex-direction:column!important;
    align-items:center!important;
    justify-content:center!important;
    gap:2px!important;
    min-height:42px!important;
  }

  /* ── BEST badge: centered below value, no left margin ── */
  .best-badge{
    margin-left:0!important;
    display:inline-block!important;
    font-size:7px!important;
    padding:1px 5px!important;
    line-height:1.5!important;
  }

  /* ── Section header ── */
  .cmp-section-label .col-full{
    position:sticky;left:0;z-index:2;
    padding:7px 10px!important;
    font-size:11px!important;
  }

  /* ── Vehicle header card cells ── */
  #compare-table .grid>div:not(:first-child){
    padding:6px 4px!important;
  }
  /* Shrink the EV icon placeholder */
  #compare-table .grid>div:not(:first-child) [style*="height:80px"]{
    height:56px!important;margin-top:8px!important;margin-bottom:8px!important;
  }
  #compare-table .grid>div:not(:first-child) [style*="height:80px"]>div{
    width:44px!important;height:44px!important;font-size:1.1rem!important;
  }
  /* Brand label */
  #compare-table .grid>div:not(:first-child) p:first-child{
    font-size:9px!important;
  }
  /* Vehicle name */
  #compare-table .grid>div:not(:first-child) .font-black.text-base{
    font-size:.7rem!important;line-height:1.25!important;
  }
  /* Price */
  #compare-table .grid>div:not(:first-child) .font-black.text-xl{
    font-size:.85rem!important;margin-bottom:6px!important;
  }
  /* Variant select — narrow column needs tighter padding so "2.9 kWh" etc. don't clip */
  #compare-table .grid>div:not(:first-child) select{
    font-size:9.5px!important;padding-left:6px!important;padding-right:16px!important;
  }
  #compare-table .grid>div:not(:first-child) .relative svg{
    right:3px!important;width:9px!important;height:9px!important;
  }
  /* View Details button */
  #compare-table .grid>div:not(:first-child) a[href]{
    font-size:9px!important;padding:4px 6px!important;
  }
  /* Top Pick banner */
  #compare-table .grid>div:not(:first-child) [style*="background:linear-gradient(90deg,#F59E0B"]{
    font-size:9px!important;padding:3px 4px!important;
  }
}

/* EV Slot — empty */
.ev-slot{
  border:2px dashed rgba(0,230,118,.3);
  border-radius:20px;
  min-height:190px;
  transition:all .2s cubic-bezier(.4,0,.2,1);
  background:linear-gradient(145deg,#F5FFF7 0%,#EEFFF3 100%);
  position:relative;
  overflow:hidden;
}
.ev-slot::before{
  content:'';position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 0%,rgba(0,230,118,.06) 0%,transparent 70%);
  pointer-events:none;
}
.ev-slot:not(.ev-focused):not(.ev-filled):hover{
  border-color:rgba(0,230,118,.5);
  background:linear-gradient(145deg,#EEFFF3 0%,#E6FFED 100%);
  box-shadow:0 4px 18px rgba(0,230,118,.1);
}
.ev-slot.ev-focused{
  border-style:solid;border-color:#00E676;
  box-shadow:0 0 0 3px rgba(0,230,118,.12),0 6px 24px rgba(0,230,118,.1);
  background:#FAFFF7;
}
.ev-slot.ev-filled{
  border-style:solid;border-color:#00E676;border-width:2px;
  background:linear-gradient(160deg,rgba(0,230,118,.06) 0%,rgba(0,230,118,.02) 60%,#fff 100%);
  box-shadow:0 4px 20px rgba(0,230,118,.1);
}

/* Autocomplete */
.ac-drop{position:absolute;top:calc(100% + 8px);left:0;right:0;background:#fff;border:1.5px solid rgba(0,230,118,.22);border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.12);z-index:50;max-height:280px;overflow-y:auto}
.ac-item{display:flex;align-items:center;gap:10px;padding:11px 14px;cursor:pointer;border-bottom:1px solid rgba(0,230,118,.06);transition:background .15s}
.ac-item:last-child{border-bottom:none;border-radius:0 0 20px 20px}
.ac-item:first-child{border-radius:20px 20px 0 0}
.ac-item:hover,.ac-item:focus{background:linear-gradient(90deg,rgba(0,230,118,.07),rgba(0,230,118,.03))}

/* VS connector */
.vs-dot{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 12px rgba(0,230,118,.3);flex-shrink:0;letter-spacing:.04em}

/* Slot icons animation */
@keyframes iconPulse{0%,100%{transform:scale(1) rotate(0deg)}50%{transform:scale(1.08) rotate(-3deg)}}
@keyframes dashSpin{to{stroke-dashoffset:-24}}
.slot-empty-icon{animation:iconPulse 3.5s ease-in-out infinite}

/* Smooth slot transitions */
[x-cloak]{display:none!important}
.slot-transition-enter{animation:scaleIn .28s cubic-bezier(.22,1,.36,1) both}
.slot-transition-leave{animation:scaleIn .2s cubic-bezier(.22,1,.36,1) reverse both}

/* Sticky bar */
/* top offset clears the fixed site header (66px mobile row-1-only, 113px desktop two-row) so this bar
   doesn't render hidden behind it (site header is z-[200], well above this bar's z-50). */
#cmp-sticky{position:fixed;top:66px;left:0;right:0;z-index:50;background:linear-gradient(160deg,#F8FBFF,#EEF6FF);backdrop-filter:blur(16px);border-bottom:2px solid #00E676;box-shadow:0 4px 20px rgba(0,0,0,.06);transform:translateY(-100%);transition:transform .3s ease}
#cmp-sticky.show{transform:translateY(0)}
@media(min-width:768px){ #cmp-sticky{top:113px} }

/* ── Mobile optimizations ── */
@media(max-width:767px){
  /* Hide blobs on mobile (performance) */
  .blob-float-1, .blob-float-2, .blob-float-3{
    display:none!important;
  }

  /* Center white glow: reduce on mobile */
  .absolute[style*="top:50%"]{
    opacity:0.3!important;
  }

  /* Sticky bar: reduce padding on very small screens */
  #cmp-sticky .max-w-7xl{
    padding-left:8px!important;
    padding-right:8px!important;
  }

  /* Sticky bar gap: reduce spacing between vehicles */
  #cmp-sticky .flex.items-center.gap-4{
    gap:8px!important;
  }
}

/* Table section header */
.cmp-section-label{
  display:grid;border-top:2px solid rgba(0,230,118,.1);margin-top:4px;
}
.cmp-section-label .col-full{
  padding:11px 20px;
  background:linear-gradient(90deg,rgba(0,230,118,.08) 0%,rgba(0,230,118,.02) 60%,transparent 100%);
  border-left:4px solid #00E676;
  display:flex;align-items:center;gap:10px;
}

/* ── ANIMATIONS ─────────────────────────────────── */
@keyframes fadeUp{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeDown{from{opacity:0;transform:translateY(-18px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes scaleIn{from{opacity:0;transform:scale(.88)}to{opacity:1;transform:scale(1)}}
@keyframes slideLeft{from{opacity:0;transform:translateX(-22px)}to{opacity:1;transform:translateX(0)}}
@keyframes pulseGreen{0%,100%{box-shadow:0 0 0 0 rgba(0,168,150,.5)}65%{box-shadow:0 0 0 10px rgba(0,168,150,0)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes borderPop{from{transform:scaleX(0);transform-origin:left}to{transform:scaleX(1);transform-origin:left}}
@keyframes rowIn{from{opacity:0;transform:translateX(-12px)}to{opacity:1;transform:translateX(0)}}
@keyframes numberPop{0%{transform:scale(1.5);opacity:0}100%{transform:scale(1);opacity:1}}
@keyframes badgeBounce{0%,100%{transform:translateY(0) scale(1)}35%{transform:translateY(-4px) scale(1.1)}65%{transform:translateY(0) scale(.97)}}
@keyframes revealRow{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
@keyframes accentBar{from{transform:scaleY(0);transform-origin:top}to{transform:scaleY(1);transform-origin:top}}

/* Hero entrance */
.anim-hero-badge{animation:fadeUp .5s cubic-bezier(.22,1,.36,1) both}
.anim-hero-h1{animation:fadeUp .6s .1s cubic-bezier(.22,1,.36,1) both}
.anim-hero-sub{animation:fadeUp .6s .2s cubic-bezier(.22,1,.36,1) both}
.anim-hero-stats{animation:scaleIn .55s .32s cubic-bezier(.22,1,.36,1) both}

/* Slot cards stagger */
.anim-slot-0{animation:scaleIn .5s .06s cubic-bezier(.22,1,.36,1) both}
.anim-slot-1{animation:scaleIn .5s .17s cubic-bezier(.22,1,.36,1) both}
.anim-slot-2{animation:scaleIn .5s .28s cubic-bezier(.22,1,.36,1) both}

/* Best-for cards stagger (applied once in view) */
.anim-bf-0{opacity:0;transform:translateY(22px);transition:opacity .5s .1s cubic-bezier(.22,1,.36,1),transform .5s .1s cubic-bezier(.22,1,.36,1)}
.anim-bf-1{opacity:0;transform:translateY(22px);transition:opacity .5s .22s cubic-bezier(.22,1,.36,1),transform .5s .22s cubic-bezier(.22,1,.36,1)}
.anim-bf-2{opacity:0;transform:translateY(22px);transition:opacity .5s .34s cubic-bezier(.22,1,.36,1),transform .5s .34s cubic-bezier(.22,1,.36,1)}
.anim-bf-0.visible,.anim-bf-1.visible,.anim-bf-2.visible{opacity:1;transform:translateY(0)}

/* Slide-up for winner and banner */
.anim-winner{opacity:0;transform:translateY(24px);transition:opacity .6s cubic-bezier(.22,1,.36,1),transform .6s cubic-bezier(.22,1,.36,1)}
.anim-winner.visible{opacity:1;transform:translateY(0)}
.anim-banner{opacity:0;transform:translateY(20px);transition:opacity .55s .1s cubic-bezier(.22,1,.36,1),transform .55s .1s cubic-bezier(.22,1,.36,1)}
.anim-banner.visible{opacity:1;transform:translateY(0)}

/* Compare button shimmer */
.btn-shimmer{position:relative;overflow:hidden}
.btn-shimmer::after{
  content:'';position:absolute;top:0;left:0;right:0;bottom:0;
  background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.3) 50%,transparent 60%);
  background-size:200% 100%;
  animation:shimmer 2.4s infinite;
}

/* BEST badge */
.best-badge{animation:badgeBounce .55s cubic-bezier(.22,1,.36,1) both}
.best-pulse{animation:pulseGreen 2.2s infinite}

/* Table vehicle header float-in */
.anim-veh-0{animation:fadeDown .5s .06s cubic-bezier(.22,1,.36,1) both}
.anim-veh-1{animation:fadeDown .5s .18s cubic-bezier(.22,1,.36,1) both}
.anim-veh-2{animation:fadeDown .5s .3s cubic-bezier(.22,1,.36,1) both}

/* Scroll-reveal rows */
.row-reveal{opacity:0;transform:translateY(8px);transition:opacity .38s ease,transform .38s cubic-bezier(.22,1,.36,1)}
.row-reveal.visible{opacity:1;transform:none}
/* Section header reveal — translateY, not translateX (same horizontal-scroll-jank reason as .cmp-row above) */
/* Opacity-only — see .cmp-row comment above; .sec-reveal wraps .cmp-section-label, whose
   child .col-full is also position:sticky on mobile, so it has the same constraint. */
.sec-reveal{opacity:0;transition:opacity .4s ease}
.sec-reveal.visible{opacity:1}

/* Slot fill pop */
.slot-pop{animation:scaleIn .3s ease both}

/* Floating blobs in hero */
.blob-float-1{animation:floatY 7s ease-in-out infinite}
.blob-float-2{animation:floatY 9s .8s ease-in-out infinite}
.blob-float-3{animation:floatY 11s 1.4s ease-in-out infinite}

/* Gradient text for hero accent */
.hero-accent{
  background:linear-gradient(130deg,#00C060 0%,#00E676 60%,#69FF97 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
</style>

<!-- STICKY BAR -->
<?php if ($hasComparison): ?>
<div id="cmp-sticky">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4 overflow-x-auto">
    <span class="text-[10px] font-bold uppercase tracking-widest flex-shrink-0 hidden sm:block" style="color:#475569">Comparing</span>
    <?php foreach ($vehicles as $vi => $vv): ?>
    <div class="flex items-center gap-2 flex-shrink-0">
      <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0" style="background:rgba(0,230,118,.08);border:1px solid rgba(0,230,118,.2)">
        <?php $si = $vv['featured_image'] ?? $vv['image_url'] ?? ''; if ($si): ?>
        <img src="<?= esc(resolveImgUrl($si)) ?>" class="w-full h-full object-contain" loading="lazy">
        <?php else: ?><span style="color:#00C060;font-size:1rem">⚡</span><?php endif; ?>
      </div>
      <div>
        <p class="text-[10px] leading-none" style="color:#94A3B8"><?= esc($vv['brand_name'] ?? '') ?></p>
        <p class="text-sm font-bold leading-tight" style="color:#0F172A"><?= esc($vv['name']) ?></p>
      </div>
      <?php if ($vi === $bestIdx): ?><span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-full flex-shrink-0" style="background:#F59E0B;color:#422006">TOP</span><?php endif; ?>
    </div>
    <?php if ($vi < $count - 1): ?><span class="text-xs font-bold flex-shrink-0" style="color:#94A3B8">vs</span><?php endif; ?>
    <?php endforeach; ?>
    <a href="#compare-table" class="ml-auto flex-shrink-0 text-xs font-bold px-3 py-1.5 rounded-lg whitespace-nowrap" style="background:#00E676;color:#022C22">View Specs ↓</a>
  </div>
</div>
<?php endif; ?>

<!-- HERO HEADER -->
<div class="hero-sm relative overflow-hidden pt-16 sm:pt-20 md:pt-32 pb-6 px-4 sm:px-6" style="background:#F7FFFE">

  <!-- Ambient blobs -->
  <div class="absolute pointer-events-none" style="inset:0;z-index:0">
    <!-- top-left large green blob -->
    <div class="blob-float-1" style="position:absolute;top:-80px;left:-80px;width:440px;height:440px;border-radius:50%;background:radial-gradient(circle at 40% 40%,rgba(0,230,118,.18) 0%,rgba(105,255,151,.1) 35%,transparent 72%);filter:blur(60px);opacity:1"></div>
    <!-- top-right green blob -->
    <div class="blob-float-2" style="position:absolute;top:-40px;right:-60px;width:360px;height:300px;border-radius:50%;background:radial-gradient(circle at 60% 35%,rgba(0,168,150,.16) 0%,rgba(0,230,118,.08) 45%,transparent 75%);filter:blur(52px);opacity:1"></div>
    <!-- bottom-left accent -->
    <div class="blob-float-3" style="position:absolute;bottom:-60px;left:8%;width:280px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(0,230,118,.14) 0%,transparent 70%);filter:blur(48px);opacity:1"></div>
    <!-- bottom-right accent -->
    <div class="blob-float-1" style="position:absolute;bottom:-40px;right:6%;width:320px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(0,168,150,.12) 0%,transparent 68%);filter:blur(52px);opacity:1"></div>
    <!-- center white glow — keeps center almost white -->
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:65%;height:150%;background:radial-gradient(ellipse,rgba(255,255,255,.95) 0%,rgba(255,255,255,.6) 35%,transparent 65%);filter:blur(20px)"></div>
    <!-- subtle grain texture -->
    <svg style="position:absolute;inset:0;width:100%;height:100%;opacity:.025" xmlns="http://www.w3.org/2000/svg">
      <filter id="grn"><feTurbulence type="fractalNoise" baseFrequency="0.72" numOctaves="4" stitchTiles="stitch"/><feColorMatrix type="saturate" values="0"/></filter>
      <rect width="100%" height="100%" filter="url(#grn)"/>
    </svg>
  </div>

  <div class="max-w-7xl mx-auto relative text-center" style="z-index:1">

    <!-- Breadcrumb -->
    <nav class="text-xs mb-4 flex items-center justify-center gap-1.5">
      <a href="<?= base_url('/') ?>" style="color:#94A3B8;transition:color .15s" onmouseover="this.style.color='#00A896'" onmouseout="this.style.color='#94A3B8'">Home</a>
      <span style="color:#CBD5E1">›</span>
      <span style="color:#64748B">Compare EVs</span>
    </nav>

    <div class="flex flex-col items-center gap-4">
      <div>
        <!-- Badge -->
        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black mb-4 anim-hero-badge tracking-wider" style="background:rgba(255,255,255,.85);color:#00A896;border:1.5px solid rgba(0,168,150,.25);box-shadow:0 2px 8px rgba(0,168,150,.12);backdrop-filter:blur(8px)">
          ⚡ CHARJ · Compare
        </div>
        <!-- Heading -->
        <h1 class="text-4xl md:text-5xl font-black mb-3 leading-tight tracking-tight anim-hero-h1" style="color:#0F172A">
          Compare Electric Vehicles <span class="hero-accent">in India</span>
        </h1>
        <p class="text-base leading-relaxed max-w-xl mx-auto anim-hero-sub" style="color:#64748B;font-weight:500">Side-by-side specs, real-world range, monthly costs &amp; more — the most detailed EV comparison.</p>
      </div>

      <!-- Stats pill (light version) -->
      <?php if ($hasComparison): ?>
      <div class="inline-flex items-center gap-5 px-5 py-3.5 rounded-2xl flex-shrink-0 anim-hero-stats" style="background:rgba(255,255,255,.8);border:1px solid rgba(0,168,150,.15);box-shadow:0 2px 12px rgba(0,0,0,.06);backdrop-filter:blur(12px)">
        <div class="text-center">
          <div class="text-2xl font-black" style="color:#00A896"><?= $count ?></div>
          <div class="text-[11px] font-medium" style="color:#64748B">EVs selected</div>
        </div>
        <div style="width:1px;height:32px;background:rgba(0,168,150,.15)"></div>
        <div class="text-center">
          <div class="text-2xl font-black" style="color:#00A896"><?= array_sum($winCounts) ?></div>
          <div class="text-[11px] font-medium" style="color:#64748B">metrics</div>
        </div>
        <div style="width:1px;height:32px;background:rgba(0,168,150,.15)"></div>
        <div class="text-center">
          <div class="text-xl font-black">🏆</div>
          <div class="text-[11px] font-semibold truncate max-w-[80px]" style="color:#0F172A"><?= esc($bestVeh['name'] ?? '—') ?></div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6 cmp-page-bg" x-data="compareApp()" x-init="init()">

  <!-- ═══ SELECTOR CARD ═══ -->
  <div class="rounded-2xl overflow-hidden" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.2);box-shadow:0 8px 48px rgba(0,168,150,.12),0 2px 12px rgba(0,0,0,.06)">

    <!-- Card header -->
    <div class="flex items-center justify-between px-6 py-5" style="border-bottom:1px solid rgba(0,168,150,.1);background:linear-gradient(90deg,rgba(0,168,150,.08) 0%,rgba(0,168,150,.02) 60%,transparent 100%)">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(0,168,150,.18),rgba(0,168,150,.08))">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#00A896" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
          <h2 class="font-black text-base" style="color:#0F172A">Compare Electric Vehicles</h2>
          <p class="text-xs font-medium" style="color:#64748B">Add 2 or 3 vehicles to see a detailed side-by-side breakdown</p>
        </div>
      </div>
      <div class="hidden sm:flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full" style="background:rgba(0,168,150,.08);color:#00A896;border:1px solid rgba(0,168,150,.18)">
        <span style="font-size:1rem">⚡</span> Up to 3 EVs
      </div>
    </div>

    <!-- Slots row -->
    <div class="p-6">

      <!-- Slots + VS connectors -->
      <?php $hasTwoFilled = count(array_filter($vehicles ?? [])) >= 2; ?>
      <div class="flex flex-col sm:flex-row items-stretch gap-0 sm:gap-0 mb-6">

        <?php for ($slot = 0; $slot < 3; $slot++):
          $sv = $vehicles[$slot] ?? null;
          $svJson = $sv ? htmlspecialchars(json_encode([
            'id'             => $sv['id'],
            'name'           => ($sv['brand_name'] ?? '') . ' ' . $sv['name'],
            'slug'           => $sv['slug'] ?? $sv['id'],
            'image'          => resolveImgUrl($sv['featured_image'] ?? $sv['image_url'] ?? ''),
            'brand'          => $sv['brand_name'] ?? '',
            'starting_price' => (int)($sv['starting_price'] ?? 0),
          ]), ENT_QUOTES, 'UTF-8') : 'null';
          $isOptional = ($slot === 2);
          // Slot 3 starts collapsed to a compact button when a 2-vehicle comparison is already complete —
          // no need for an equal-sized empty card competing with two filled ones for mobile screen space.
          $startCollapsed = ($isOptional && $hasTwoFilled && !$sv) ? 'true' : 'false';
        ?>

        <?php if ($slot > 0): ?>
        <!-- VS Separator -->
        <div class="flex sm:flex-col items-center justify-center px-2 sm:px-3 py-3 sm:py-0 flex-shrink-0" style="gap:6px">
          <div style="flex:1;height:1px;width:1px;background:linear-gradient(to bottom,transparent,rgba(0,168,150,.2),transparent)" class="hidden sm:block w-px h-full"></div>
          <div class="vs-dot">VS</div>
          <div style="flex:1;background:linear-gradient(to bottom,rgba(0,168,150,.2),transparent)" class="hidden sm:block w-px h-full"></div>
        </div>
        <?php endif; ?>

        <!-- SLOT -->
        <div x-data="slotSearch(<?= $slot ?>, <?= $svJson ?>, <?= $startCollapsed ?>)" class="relative flex-1 anim-slot-<?= $slot ?>">

          <!-- ── FILLED state ── -->
          <div x-show="selected"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="ev-slot ev-filled rounded-2xl relative overflow-hidden"
               style="min-height:210px">

            <!-- Top accent bar -->
            <div class="h-1 w-full" style="background:linear-gradient(90deg,#00A896,#00bfa5,#007a6e)"></div>

            <!-- Remove button -->
            <button @click="clear()"
                    class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center text-xs font-black z-10 transition-all"
                    style="background:#FEE2E2;color:#EF4444;box-shadow:0 2px 6px rgba(239,68,68,.2)"
                    onmouseover="this.style.background='#EF4444';this.style.color='#fff';this.style.transform='scale(1.1)'"
                    onmouseout="this.style.background='#FEE2E2';this.style.color='#EF4444';this.style.transform='scale(1)'">✕</button>

            <!-- Slot number badge -->
            <div class="absolute top-3 left-3 w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-black text-white" style="background:#00A896;box-shadow:0 2px 8px rgba(0,168,150,.4)"><?= $slot + 1 ?></div>

            <!-- EV Content -->
            <div class="p-4 pt-3 flex flex-col items-center text-center gap-1.5">
              <!-- Icon area -->
              <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mt-2" style="background:linear-gradient(135deg,rgba(0,168,150,.12),rgba(0,168,150,.05))">
                <img :src="selected?.image || ''" :alt="selected?.name" x-show="selected?.image"
                     class="w-full h-full object-contain rounded-2xl" loading="lazy"
                     onerror="this.style.display='none'">
                <span x-show="!selected?.image">⚡</span>
              </div>
              <p class="text-[10px] font-bold uppercase tracking-widest mt-1" style="color:#94A3B8" x-text="selected?.brand"></p>
              <p class="text-sm font-black leading-snug px-2" style="color:#0F172A" x-text="selected?.name"></p>
              <p class="text-base font-black" style="color:#00A896" x-text="fmtP(selected?.starting_price)"></p>
              <!-- Check mark -->
              <div class="flex items-center gap-1 text-[11px] font-bold mt-1 px-2.5 py-1 rounded-full" style="background:rgba(0,168,150,.1);color:#00A896">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Added
              </div>
            </div>
          </div>

          <!-- ── COMPACT collapsed state (slot 3 only, when 2 EVs already compared) ── -->
          <button x-show="!selected && collapsed" x-cloak
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100"
                  @click="collapsed=false; $nextTick(()=>$el.closest('.relative').querySelector('input')?.focus())"
                  class="w-full flex items-center justify-center gap-2 rounded-2xl text-sm font-bold transition-all"
                  style="min-height:56px;border:2px dashed rgba(0,168,150,.3);color:#00A896;background:rgba(0,168,150,.03)"
                  onmouseover="this.style.background='rgba(0,168,150,.08)';this.style.borderColor='rgba(0,168,150,.5)'"
                  onmouseout="this.style.background='rgba(0,168,150,.03)';this.style.borderColor='rgba(0,168,150,.3)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add 3rd EV (optional)
          </button>

          <!-- ── EMPTY / SEARCH state ── -->
          <div x-show="!selected && !collapsed"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               :class="focused ? 'ev-focused' : ''"
               class="ev-slot rounded-2xl flex flex-col"
               style="min-height:210px">

            <!-- Slot header -->
            <div class="flex items-center justify-between px-4 pt-4 pb-2">
              <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-black text-white" style="background:<?= $focused ?? false ? '#00A896' : '#00A896' ?>"><?= $slot + 1 ?></span>
                <span class="text-[11px] font-black uppercase tracking-widest" style="color:#374151">EV <?= $slot + 1 ?></span>
              </div>
              <?php if ($isOptional): ?>
              <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:rgba(100,116,139,.08);color:#94A3B8">Optional</span>
              <?php endif; ?>
            </div>

            <!-- Big tap-to-add zone (click focuses input) -->
            <div class="flex-1 flex flex-col items-center justify-center gap-2 py-3 cursor-pointer"
                 x-show="!focused && results.length === 0"
                 @click="$nextTick(()=>$el.closest('.ev-slot').querySelector('input').focus())">
              <!-- Animated icon -->
              <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl slot-empty-icon"
                   style="background:linear-gradient(135deg,rgba(0,168,150,.14),rgba(0,168,150,.06));border:2px dashed rgba(0,168,150,.3);color:#00A896">
                ⚡
              </div>
              <p class="text-sm font-bold" style="color:#374151"><?= $isOptional ? 'Add 3rd EV (optional)' : 'Pick an EV' ?></p>
              <p class="text-[11px]" style="color:#94A3B8">Search 200+ electric vehicles</p>
            </div>

            <!-- Search row (always rendered, just hidden until focused or typing) -->
            <div class="px-4 pb-4 relative" :class="focused || results.length > 0 || query ? '' : 'mt-0'">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" :style="focused ? 'color:#00A896' : 'color:#94A3B8'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text"
                       x-ref="searchInput"
                       x-model="query"
                       @input.debounce.180ms="onInput()"
                       @focus="focused=true; if(!query) loadPop()"
                       @blur="setTimeout(() => { if(!_picking){focused=false;results=[];} _picking=false; }, 200)"
                       placeholder="Search EV name or brand..."
                       class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-medium focus:outline-none transition-all"
                       style="border:1.5px solid rgba(0,168,150,.2);background:#F7FFFE;color:#0F172A"
                       onfocus="this.style.borderColor='#00A896';this.style.boxShadow='0 0 0 3px rgba(0,168,150,.1),0 2px 8px rgba(0,168,150,.08)'"
                       onblur="this.style.borderColor='rgba(0,168,150,.2)';this.style.boxShadow=''">
                <!-- Clear query X -->
                <button x-show="query" @mousedown.prevent="query='';results=[];$refs.searchInput.focus()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold"
                        style="background:#E2E8F0;color:#64748B">×</button>
              </div>

              <!-- Dropdown -->
              <div x-show="results.length > 0" x-cloak class="ac-drop" style="top:calc(100% + 6px)">
                <div x-show="focused && query" class="px-3 pt-2.5 pb-1 text-[10px] font-bold uppercase tracking-widest" style="color:#94A3B8">Results</div>
                <template x-for="item in results" :key="item.id">
                  <div class="ac-item" @mousedown.prevent="_picking=true; pick(item)">
                    <div class="w-10 h-8 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden" style="background:#F0FDFA">
                      <img :src="item.image || ''" :alt="item.name" class="w-full h-full object-contain"
                           onerror="this.style.display='none'" loading="lazy">
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-sm font-bold truncate" style="color:#0F172A" x-text="item.name"></p>
                      <p class="text-xs font-medium truncate" style="color:#94A3B8" x-text="(item.brand||'') + (item.starting_price ? ' · ' + fmtP(item.starting_price) : '')"></p>
                    </div>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(0,168,150,.1)">
                      <svg class="w-3.5 h-3.5" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                  </div>
                </template>
              </div>
            </div>

          </div>
        </div><!-- /slot -->
        <?php endfor; ?>

      </div><!-- /slots flex row -->

      <!-- Progress dots -->
      <div class="flex items-center justify-center gap-2 mb-5">
        <?php for ($slot = 0; $slot < 3; $slot++): ?>
        <div class="w-2 h-2 rounded-full transition-all" id="progress-dot-<?= $slot ?>" style="background:<?= isset($vehicles[$slot]) ? '#00A896' : 'rgba(0,168,150,.2)' ?>"></div>
        <?php endfor; ?>
        <span id="progress-count" class="text-xs font-semibold ml-1" style="color:#94A3B8"><?= count($vehicles) ?>/3 selected</span>
      </div>

      <!-- Action button -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button onclick="doCompare()"
                class="inline-flex items-center gap-2.5 font-black px-9 py-4 rounded-2xl text-white text-sm transition-all btn-shimmer"
                style="background:linear-gradient(135deg,#00A896,#009688);box-shadow:0 5px 0 #007a6e,0 10px 28px rgba(0,168,150,.35);transform:translateY(0);letter-spacing:.01em"
                onmouseover="this.style.boxShadow='0 7px 0 #007a6e,0 14px 36px rgba(0,168,150,.4)';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.boxShadow='0 5px 0 #007a6e,0 10px 28px rgba(0,168,150,.35)';this.style.transform='translateY(0)'"
                onmousedown="this.style.boxShadow='0 1px 0 #007a6e,0 2px 8px rgba(0,168,150,.2)';this.style.transform='translateY(4px)'"
                onmouseup="this.style.boxShadow='0 5px 0 #007a6e,0 10px 28px rgba(0,168,150,.35)';this.style.transform='translateY(-2px)'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          Compare Selected EVs
        </button>
        <a href="<?= base_url('vehicles') ?>"
           class="flex items-center gap-1.5 text-sm font-bold transition-all"
           style="color:#64748B"
           onmouseover="this.style.color='#00A896'" onmouseout="this.style.color='#64748B'">
          Browse all EVs <span style="font-size:1rem">→</span>
        </a>
      </div>
    </div>
  </div><!-- /selector card -->

  <?php if ($hasComparison): ?>

  <!-- Mobile swipe hint -->
  <div class="md:hidden flex items-center justify-center gap-2 mb-3">
    <svg class="w-3.5 h-3.5" style="color:#94A3B8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
    <span class="text-[10px] font-semibold tracking-wide" style="color:#94A3B8">Swipe left/right to compare</span>
    <svg class="w-3.5 h-3.5" style="color:#94A3B8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
  </div>

  <!-- ═══ COMPARISON TABLE ═══ -->
  <!-- No overflow:hidden here — an overflow:hidden ancestor breaks position:sticky children
       (the spec-label column below), which was causing wrapped labels like "Specification"
       and "Battery Capacity" to render at the wrong offset and overlap adjacent rows. -->
  <div id="compare-table" class="rounded-2xl"
       x-data='variantCompare(<?= htmlspecialchars(json_encode(["variants" => $colVariants, "sel" => $colSelDef]), ENT_QUOTES, "UTF-8") ?>)'
       style="background:#FFFFFF;border:1px solid rgba(0,168,150,.18);box-shadow:0 8px 40px rgba(0,168,150,.08),0 2px 12px rgba(0,0,0,.06)">
    <div class="cmp-table">

      <!-- Vehicle header row -->
      <div class="grid" style="grid-template-columns:200px repeat(<?= $count ?>,1fr);border-bottom:2px solid rgba(0,168,150,.1)">
        <!-- Spec label cell -->
        <div class="p-5 flex items-end" style="background:linear-gradient(135deg,rgba(0,168,150,.08),rgba(0,168,150,.03));border-right:1px solid rgba(0,168,150,.12)">
          <span class="text-[10px] font-bold uppercase tracking-widest" style="color:#00A896">Specs</span>
        </div>
        <?php foreach ($vehicles as $vi => $vv):
          $vImg = $vv['featured_image'] ?? $vv['image_url'] ?? '';
          $isTop = ($vi === $bestIdx);
        ?>
        <div class="flex flex-col items-center text-center relative anim-veh-<?= $vi ?>" style="border-left:2px solid rgba(0,168,150,.16);<?= $isTop ? 'background:linear-gradient(180deg,rgba(0,168,150,.06) 0%,#fff 60%)' : ($vi % 2 === 1 ? 'background:rgba(0,168,150,.02)' : 'background:#fff') ?>">
          <?php if ($isTop): ?>
          <div class="w-full py-1.5 flex items-center justify-center gap-1.5" style="background:linear-gradient(90deg,#F59E0B,#FBBF24);border-bottom:1px solid #F59E0B">
            <span class="text-[11px] font-extrabold uppercase tracking-wider" style="color:#040C1E">🏆 Top Pick</span>
          </div>
          <?php endif; ?>
          <!-- Vehicle photo, falls back to icon if none -->
          <div class="w-full flex items-center justify-center my-4" style="height:80px">
            <?php if ($vImg): ?>
            <div class="w-20 h-20 rounded-2xl overflow-hidden flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,rgba(0,168,150,.08),rgba(0,168,150,.03));border:1px solid rgba(0,168,150,.15)">
              <img src="<?= esc(resolveImgUrl($vImg)) ?>" alt="<?= esc($vv['name']) ?>" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
              <div class="w-16 h-16 rounded-2xl items-center justify-center text-3xl flex-shrink-0" style="display:none;background:linear-gradient(135deg,#007a6e,#00A896,#00bfa5);box-shadow:0 4px 16px rgba(0,168,150,.3)">⚡</div>
            </div>
            <?php else: ?>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl" style="background:linear-gradient(135deg,#007a6e,#00A896,#00bfa5);box-shadow:0 4px 16px rgba(0,168,150,.3)">⚡</div>
            <?php endif; ?>
          </div>
          <div class="px-4 pb-5">
            <p class="text-[11px] font-bold mb-0.5 truncate uppercase tracking-wider" style="color:#94A3B8"><?= esc($vv['brand_name'] ?? '') ?></p>
            <p class="font-black text-base leading-snug mb-1.5 truncate" style="color:#0F172A"><?= esc($vv['name']) ?></p>
            <?php if (!empty($colVariants[$vi])): ?>
            <!-- Variant selector (reactive) -->
            <p class="font-black text-xl mb-2" style="color:#00A896" x-text="priceLabel(<?= $vi ?>)"><?= fmtINR($vv['starting_price'] ?? null) ?></p>
            <div class="mb-3 text-left">
              <label class="block text-[9px] font-bold uppercase tracking-widest mb-1" style="color:#94A3B8">Variant</label>
              <div class="relative">
                <select x-model.number="sel[<?= $vi ?>]"
                        class="w-full appearance-none text-xs font-bold rounded-lg pl-2.5 pr-7 py-2 cursor-pointer focus:outline-none transition-all"
                        style="border:1.5px solid rgba(0,168,150,.3);background:#F7FFFE;color:#0F172A"
                        onfocus="this.style.borderColor='#00A896';this.style.boxShadow='0 0 0 3px rgba(0,168,150,.1)'"
                        onblur="this.style.borderColor='rgba(0,168,150,.3)';this.style.boxShadow=''">
                  <template x-for="(vt, k) in variants[<?= $vi ?>]" :key="k">
                    <option :value="k" x-text="vt.name + (vt.popular ? '  ★' : '')"></option>
                  </template>
                </select>
                <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </div>
            </div>
            <?php else: ?>
            <p class="font-black text-xl mb-3" style="color:#00A896"><?= fmtINR($vv['starting_price'] ?? null) ?></p>
            <?php endif; ?>
            <a href="<?= base_url('vehicles/' . esc($vv['slug'] ?? $vv['id'])) ?>"
               class="inline-flex items-center gap-1 text-xs font-bold px-3.5 py-1.5 rounded-lg transition-all"
               style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)"
               onmouseover="this.style.background='#00A896';this.style.color='#fff'"
               onmouseout="this.style.background='rgba(0,168,150,.1)';this.style.color='#00A896'">
              View Details →
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?= cmpSection('💰 Pricing', $count) ?>
      <?php echo cmpRow('Starting Price',$vehicles,'starting_price','low','',true,'price',$colVariants); ?>
      <?php foreach ([['Max Price (Top Trim)','max_price','low','',true],['Ex-showroom Delhi','ex_showroom_delhi','low','',true]] as [$l,$f,$m,$u,$fp]): echo cmpRow($l,$vehicles,$f,$m,$u,$fp); endforeach; ?>

      <?= cmpSection('📏 Range', $count) ?>
      <?php echo cmpRow('Claimed Range (ARAI)',$vehicles,'claimed_range','high','km',false,'range',$colVariants); ?>
      <?php echo cmpRow('Real-World Range',$vehicles,'real_world_range','high','km',false,'range',$colVariants); ?>
      <?php foreach ([['City Range (est.)','city_range','high','km'],['Highway Range (est.)','highway_range','high','km']] as [$l,$f,$m,$u]): echo cmpRow($l,$vehicles,$f,$m,$u); endforeach; ?>

      <?= cmpSection('🔋 Battery', $count) ?>
      <?php echo cmpRow('Battery Capacity',$vehicles,'battery_capacity','high','kWh',false,'battery',$colVariants); ?>
      <?php foreach ([['Battery Type','battery_type','text',''],['Battery Warranty','battery_warranty','text',''],['Charging Connector','connector_type','text','']] as [$l,$f,$m,$u]): echo cmpRow($l,$vehicles,$f,$m,$u); endforeach; ?>

      <?= cmpSection('⚡ Charging', $count) ?>
      <?php foreach ([['AC Charging Time','ac_charging_time','low','hr'],['DC Fast Charging','dc_charging_time','low','min']] as [$l,$f,$m,$u]): echo cmpRow($l,$vehicles,$f,$m,$u); endforeach; ?>
      <?php
      $c2 = $count;
      echo '<div class="cmp-row row-reveal" style="grid-template-columns:200px repeat('.$c2.',1fr)" onmouseenter="this.style.background=\'rgba(0,168,150,.03)\'" onmouseleave="this.style.background=\'\'">';
      echo '<div class="px-4 py-3 flex items-center text-sm font-semibold" style="background:rgba(0,168,150,.025);color:#374151;border-right:1px solid rgba(0,168,150,.08)">Home Charging Cost</div>';
      $hc  = array_map(fn($v) => $v['battery_capacity'] ? round((float)$v['battery_capacity']*8) : null, $vehicles);
      $hcW = detectWinners($hc,'low');
      foreach ($hc as $i=>$h){$isW=$hcW[$i];echo '<div class="px-4 py-3.5 text-sm text-center font-medium" style="border-left:1px solid rgba(0,168,150,.07)'.($isW?';background:rgba(0,168,150,.09)':'').'">';echo $isW?'<span style="color:#00A896;font-weight:800">':'<span style="color:#0F172A;font-weight:600">';echo $h?'₹'.number_format($h).'/full':'—';echo '</span>';if($isW)echo '<span class="ml-1 text-[10px] text-white px-1.5 py-0.5 rounded-full font-black best-badge best-pulse" style="background:#00A896">BEST</span>';echo '</div>';}
      echo '</div>';
      ?>

      <?= cmpSection('🏎 Performance', $count) ?>
      <?php foreach ([['Motor Power','motor_power','high','kW'],['Top Speed','top_speed','high','km/h'],['0–60 kmph','acceleration','low','sec']] as [$l,$f,$m,$u]): echo cmpRow($l,$vehicles,$f,$m,$u); endforeach; ?>

      <?= cmpSection('🛡 Ownership', $count) ?>
      <?php foreach ([['Vehicle Warranty','vehicle_warranty','text',''],['Battery Warranty','battery_warranty','text',''],['Service Centers','service_centers','high','approx.']] as [$l,$f,$m,$u]): echo cmpRow($l,$vehicles,$f,$m,$u); endforeach; ?>

      <?= cmpSection('⭐ Ratings', $count) ?>
      <?php
      $eR = array_map(fn($v) => (float)($v['expert_rating']??0),$vehicles);
      $eW = detectWinners($eR,'high');
      $uR = array_map(fn($v) => (float)($v['user_rating']??0),$vehicles);
      $uW = detectWinners($uR,'high');
      foreach ([['Expert Rating',$eR,$eW],['User Rating',$uR,$uW]] as [$rl,$rArr,$rWin]):
      ?>
      <div class="cmp-row row-reveal" style="grid-template-columns:200px repeat(<?= $count ?>,1fr)" onmouseenter="this.style.background='rgba(0,168,150,.03)'" onmouseleave="this.style.background=''">
        <div class="px-4 py-3 flex items-center text-sm font-semibold" style="background:rgba(0,168,150,.025);color:#374151;border-right:1px solid rgba(0,168,150,.08)"><?= $rl ?></div>
        <?php foreach ($rArr as $i => $r):
          $isW = $rWin[$i];
          $full = (int)floor($r);
          $emp  = 5 - $full - (($r-$full)>=.5?1:0);
        ?>
        <div class="px-4 py-4 text-sm text-center font-medium" style="border-left:1px solid rgba(0,168,150,.07)<?= $isW?';background:rgba(0,168,150,.09)':''?>">
          <?php if ($r > 0): ?>
          <div class="flex items-center justify-center gap-0.5 mb-1">
            <?php for ($s=0;$s<$full;$s++): ?><svg class="w-3.5 h-3.5" style="color:#F59E0B" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
            <?php for ($s=0;$s<$emp;$s++): ?><svg class="w-3.5 h-3.5" style="color:#E2E8F0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
          </div>
          <span class="text-sm font-bold" style="color:<?= $isW?'#00A896':'#0F172A' ?>"><?= number_format($r,1) ?>/5</span>
          <?php if ($isW): ?><span class="mt-1 text-[10px] text-white px-1.5 py-0.5 rounded-full font-black inline-block best-badge best-pulse" style="background:#00A896">BEST</span><?php endif; ?>
          <?php else: ?><span style="color:#94A3B8">—</span><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>

      <?= cmpSection('💼 Monthly Cost (Calculated)', $count) ?>
      <?php
      $emis  = array_column($monthlyData,'emi');
      $fuels = array_column($monthlyData,'fuel');
      $tots  = array_column($monthlyData,'total');
      $emiW  = detectWinners($emis,'low');
      $fuelW = detectWinners($fuels,'low');
      $totW  = detectWinners($tots,'low');

      // EMI row
      echo '<div class="cmp-row row-reveal" style="grid-template-columns:200px repeat('.$count.',1fr)" onmouseenter="this.style.background=\'rgba(0,168,150,.03)\'" onmouseleave="this.style.background=\'\'">';
      echo '<div class="px-4 py-3 flex items-center text-sm font-semibold leading-snug" style="background:rgba(0,168,150,.025);color:#374151;border-right:1px solid rgba(0,168,150,.08)">EMI <span class="text-xs ml-1 font-normal" style="color:#94A3B8">(9% · 36M · 20% down)</span></div>';
      foreach ($emis as $i=>$e){$w=$emiW[$i];echo '<div class="px-4 py-3.5 text-sm text-center font-medium" style="border-left:1px solid rgba(0,168,150,.07)'.($w?';background:rgba(0,168,150,.09)':'').'">';echo $w?'<span style="color:#00A896;font-weight:800">':'<span style="color:#0F172A;font-weight:600">';echo $e?'₹'.number_format($e).'/mo':'—';echo '</span>';if($w)echo '<span class="ml-1 text-[10px] text-white px-1.5 py-0.5 rounded-full font-black best-badge best-pulse" style="background:#00A896">BEST</span>';echo '</div>';}
      echo '</div>';

      // Charging cost row
      echo '<div class="cmp-row row-reveal" style="grid-template-columns:200px repeat('.$count.',1fr)" onmouseenter="this.style.background=\'rgba(0,168,150,.03)\'" onmouseleave="this.style.background=\'\'">';
      echo '<div class="px-4 py-3 flex items-center text-sm font-semibold leading-snug" style="background:rgba(0,168,150,.025);color:#374151;border-right:1px solid rgba(0,168,150,.08)">Est. Charging Cost <span class="text-xs ml-1 font-normal" style="color:#94A3B8">(40km/day)</span></div>';
      foreach ($fuels as $i=>$f){$w=$fuelW[$i];echo '<div class="px-4 py-3.5 text-sm text-center font-medium" style="border-left:1px solid rgba(0,168,150,.07)'.($w?';background:rgba(0,168,150,.09)':'').'">';echo $w?'<span style="color:#00A896;font-weight:800">':'<span style="color:#0F172A;font-weight:600">';echo $f?'₹'.number_format($f).'/mo':'—';echo '</span>';if($w)echo '<span class="ml-1 text-[10px] text-white px-1.5 py-0.5 rounded-full font-black best-badge best-pulse" style="background:#00A896">CHEAPEST</span>';echo '</div>';}
      echo '</div>';

      // Total row (highlighted)
      echo '<div class="cmp-row row-reveal" style="grid-template-columns:200px repeat('.$count.',1fr);background:linear-gradient(90deg,rgba(0,168,150,.07) 0%,rgba(0,168,150,.03) 100%);border-top:2px solid rgba(0,168,150,.18);border-bottom:2px solid rgba(0,168,150,.18)">';
      echo '<div class="px-4 py-4 flex items-center text-sm font-extrabold" style="background:rgba(0,168,150,.1);color:#0F172A;border-right:1px solid rgba(0,168,150,.1)">Est. Total Monthly</div>';
      foreach ($tots as $i=>$t){$w=$totW[$i];echo '<div class="px-4 py-4 text-sm text-center" style="border-left:1px solid rgba(0,168,150,.07)'.($w?';background:rgba(0,168,150,.12)':'').'">';echo $w?'<span style="color:#00A896;font-weight:900;font-size:1rem">':'<span style="color:#0F172A;font-weight:700;font-size:.9rem">';echo $t?'₹'.number_format($t).'/mo':'—';echo '</span>';if($w)echo '<span class="block mt-1 text-[10px] text-white px-2 py-0.5 rounded-full font-black" style="background:#00A896">LOWEST</span>';echo '</div>';}
      echo '</div>';
      ?>

      <!-- Get Price row -->
      <div class="grid" style="grid-template-columns:200px repeat(<?= $count ?>,1fr);background:rgba(0,168,150,.02);border-top:1px solid rgba(0,168,150,.1)">
        <div class="px-4 py-4 flex items-center text-sm font-bold" style="color:#0F172A">Get Best Price</div>
        <?php foreach ($vehicles as $vv): ?>
        <div class="px-4 py-4 flex items-center justify-center" style="border-left:1px solid rgba(0,168,150,.1)">
          <a href="<?= base_url('vehicles/'.esc($vv['slug']??$vv['id'])) ?>#lead-form"
             class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition-all text-white"
             style="background:#00A896"
             onmouseover="this.style.background='#009688'" onmouseout="this.style.background='#00A896'">
            Get Price →
          </a>
        </div>
        <?php endforeach; ?>
      </div>

    </div><!-- /cmp-table scroll wrapper -->
  </div><!-- /compare-table card -->

  <!-- ═══ WINNER CARD ═══ -->
  <?php if ($bestVeh): ?>
  <div class="rounded-2xl p-6 md:p-7 anim-winner" style="background:linear-gradient(135deg,rgba(0,168,150,.1) 0%,rgba(0,230,118,.05) 55%,#FFFFFF 100%);border:1.5px solid rgba(0,168,150,.25);box-shadow:0 8px 40px rgba(0,168,150,.12),0 0 0 1px rgba(0,168,150,.06)">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span class="text-2xl">🏆</span>
          <span class="text-[11px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider" style="background:#F59E0B;color:#422006">Our Recommendation</span>
        </div>
        <h3 class="text-2xl md:text-3xl font-black mb-2 leading-tight" style="color:#0F172A">
          Based on this comparison, <span style="color:#00A896"><?= esc($bestVeh['name']) ?></span> wins overall
        </h3>
        <p class="text-sm leading-relaxed mb-4 font-medium" style="color:#475569">
          Leads on <strong style="color:#0F172A"><?= $winCounts[$bestIdx] ?></strong> out of <strong style="color:#0F172A"><?= count($metricFields) + 1 ?></strong> key metrics including range, value, performance and monthly cost.
        </p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($vehicles as $vi => $vv): ?>
          <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.18)">
            <span class="text-sm font-bold" style="color:#0F172A"><?= esc($vv['name']) ?></span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white" style="background:#00A896"><?= $winCounts[$vi] ?> wins</span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="flex flex-col gap-3 w-full md:w-auto md:min-w-[220px] flex-shrink-0">
        <a href="<?= base_url('vehicles/'.esc($bestVeh['slug']??$bestVeh['id'])) ?>"
           class="flex items-center justify-center gap-2 font-bold px-6 py-3.5 rounded-xl text-white transition-all"
           style="background:#00A896;box-shadow:0 4px 14px rgba(0,168,150,.3)"
           onmouseover="this.style.background='#009688'" onmouseout="this.style.background='#00A896'">
          Get Price — <?= esc($bestVeh['name']) ?> →
        </a>
        <a href="<?= base_url('find-my-ev') ?>"
           class="flex items-center justify-center gap-2 font-semibold px-6 py-3 rounded-xl transition-all"
           style="background:rgba(0,168,150,.08);color:#00A896;border:1px solid rgba(0,168,150,.2)"
           onmouseover="this.style.background='rgba(0,168,150,.14)'" onmouseout="this.style.background='rgba(0,168,150,.08)'">
          🎯 Find My Perfect EV
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- ═══ BEST FOR CARDS ═══ -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <?php
    $bfCards = [
      ['💰','Best for Value',$priceWinnerIdx,'Lowest starting price — best entry point for budget-conscious buyers.',fn($v)=>fmtINR($v['starting_price']??null)],
      ['📏','Best for Range',$rangeWinnerIdx,'Highest real-world range — ideal for long commutes and inter-city travel.',fn($v)=>((int)($v['real_world_range']??$v['claimed_range']??0)).' km range'],
      ['🏙️','Best for City',$cityWinnerIdx,'Lightest and nimble — best for stop-go city traffic.',fn($v)=>!empty($v['kerb_weight'])?(int)$v['kerb_weight'].' kg':'Most agile'],
    ];
    foreach ($bfCards as $bfi => [$ico,$ttl,$idx,$desc,$metric]):
      $bv   = ($idx !== null && isset($vehicles[$idx])) ? $vehicles[$idx] : null;
      $bImg = $bv ? ($bv['featured_image'] ?? $bv['image_url'] ?? '') : '';
    ?>
    <div class="rounded-2xl p-5 transition-all anim-bf-<?= $bfi ?>" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);box-shadow:0 2px 10px rgba(0,0,0,.05);border-top:3px solid rgba(0,168,150,.25)"
         onmouseover="this.style.boxShadow='0 6px 24px rgba(0,168,150,.12)';this.style.transform='translateY(-2px)'"
         onmouseout="this.style.boxShadow='0 2px 10px rgba(0,0,0,.05)';this.style.transform='translateY(0)'">
      <div class="flex items-center gap-2.5 mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl" style="background:linear-gradient(135deg,rgba(0,168,150,.12),rgba(0,168,150,.06))"><?= $ico ?></div>
        <h3 class="font-extrabold text-sm" style="color:#0F172A"><?= $ttl ?></h3>
      </div>
      <?php if ($bv): ?>
      <div class="flex items-center gap-3 mb-3">
        <?php if ($bImg): ?><img src="<?= esc(resolveImgUrl($bImg)) ?>" alt="" class="h-10 w-14 object-contain rounded" loading="lazy"><?php endif; ?>
        <div>
          <p class="font-bold text-sm" style="color:#0F172A"><?= esc($bv['name']) ?></p>
          <p class="text-sm font-bold" style="color:#00A896"><?= $metric($bv) ?></p>
        </div>
      </div>
      <?php else: ?><p class="text-xs mb-3" style="color:#94A3B8">Not enough data to determine.</p><?php endif; ?>
      <p class="text-xs leading-relaxed" style="color:#64748B"><?= $desc ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- NOT SURE BANNER -->
  <div class="rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 anim-banner" style="background:linear-gradient(135deg,rgba(0,168,150,.07) 0%,rgba(0,168,150,.03) 100%);border:1.5px solid rgba(0,168,150,.22);box-shadow:0 2px 12px rgba(0,168,150,.08)">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:#00A896">
        <span class="text-2xl">🎯</span>
      </div>
      <div>
        <p class="font-bold text-base" style="color:#0F172A">Not sure which EV to pick?</p>
        <p class="text-sm" style="color:#64748B">Answer 5 quick questions and get a personalised recommendation — completely free.</p>
      </div>
    </div>
    <a href="<?= base_url('find-my-ev') ?>"
       class="flex-shrink-0 px-6 py-3 rounded-xl font-bold text-white transition-all whitespace-nowrap"
       style="background:#00A896"
       onmouseover="this.style.background='#009688'" onmouseout="this.style.background='#00A896'">
      Find My EV →
    </a>
  </div>

  <?php else: ?>

  <!-- ═══ EMPTY STATE ═══ -->
  <div class="rounded-2xl p-16 text-center" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.12);box-shadow:0 2px 12px rgba(0,0,0,.05)">
    <div class="text-7xl mb-5 select-none">⚖️</div>
    <h3 class="text-2xl font-bold mb-2" style="color:#0F172A">Compare up to 3 EVs Side-by-Side</h3>
    <p class="text-sm leading-relaxed mb-8 max-w-md mx-auto" style="color:#64748B">
      Search and add at least 2 electric vehicles above to see a detailed side-by-side comparison of specs, range, costs and more.
    </p>
    <a href="<?= base_url('vehicles') ?>"
       class="inline-flex items-center gap-2 font-bold px-7 py-3.5 rounded-xl text-white"
       style="background:#00A896;box-shadow:0 4px 14px rgba(0,168,150,.3)">
      ⚡ Browse All EVs
    </a>
  </div>

  <?php endif; ?>

</div><!-- /max-w-7xl -->

<script>
// Embed all vehicles for instant client-side search — no network dependency
const _EVS = <?= json_encode(array_values(array_map(fn($v) => [
  'id'             => (int)($v['id'] ?? 0),
  'name'           => trim(($v['brand_name'] ?? '') . ' ' . ($v['name'] ?? '')),
  'slug'           => $v['slug'] ?? '',
  'image'          => resolveImgUrl($v['image_url'] ?? $v['featured_image'] ?? ''),
  'brand'          => $v['brand_name'] ?? '',
  'starting_price' => (int)($v['starting_price'] ?? 0),
], $allVehicles)), JSON_UNESCAPED_UNICODE) ?>;

// Per-slot Alpine component
function slotSearch(slot, pre, startCollapsed) {
  return {
    slot,
    query    : pre ? pre.name : '',
    results  : [],
    focused  : false,
    selected : pre,
    // Slot 3 only: starts collapsed to a compact "+ Add 3rd EV" button when 2 EVs are already
    // being compared, instead of an equal-weight empty card competing for attention with the
    // filled slots. Expands to the full search card once tapped.
    collapsed: !!startCollapsed,
    _picking : false,   // prevents blur from wiping results before mousedown fires

    onInput() {
      const q = this.query.trim().toLowerCase();
      if (q.length < 1) { this.results = _EVS.slice(0, 7); return; }
      this.results = _EVS.filter(v =>
        v.name.toLowerCase().includes(q) || (v.brand||'').toLowerCase().includes(q)
      ).slice(0, 9);
    },

    loadPop() {
      if (!this.query) this.results = _EVS.slice(0, 7);
    },

    pick(item) {
      this._picking = false;
      this.selected = item;
      this.query    = item.name;
      this.results  = [];
      this.focused  = false;
      (window._cmpS = window._cmpS || {})[this.slot] = item.slug || item.id;
      // Update progress dot
      var dot = document.getElementById('progress-dot-' + this.slot);
      if (dot) dot.style.background = '#00A896';
      updateProgressCount();
    },

    clear() {
      this.selected = null;
      this.query    = '';
      this.results  = [];
      this.focused  = false;
      delete (window._cmpS = window._cmpS || {})[this.slot];
      var dot = document.getElementById('progress-dot-' + this.slot);
      if (dot) dot.style.background = 'rgba(0,168,150,.2)';
      updateProgressCount();
    },

    fmtP(p) {
      if (!p) return '';
      if (p >= 10000000) return '₹' + (p/10000000).toFixed(2) + ' Cr';
      if (p >= 100000)   return '₹' + (p/100000).toFixed(1) + ' L';
      return '₹' + Number(p).toLocaleString('en-IN');
    }
  };
}

function updateProgressCount() {
  var count = Object.keys(window._cmpS || {}).length;
  var el = document.getElementById('progress-count');
  if (el) el.textContent = count + '/3 selected';
}

// Parent Alpine component — seeds slot state from PHP-rendered vehicles
function compareApp() {
  return {
    init() {
      window._cmpS = {};
      <?php foreach ($vehicles as $vi => $vv): ?>
      window._cmpS[<?= $vi ?>] = '<?= esc($vv['slug'] ?? $vv['id']) ?>';
      <?php endforeach; ?>
    }
  };
}

// Variant selector component for the comparison table.
// `data` = { variants: {colIdx:[{name,battery,range,price,fast,popular},...]}, sel: {colIdx:defIdx} }
function variantCompare(data) {
  return {
    variants: data.variants || {},
    sel     : data.sel || {},

    _fmtPrice(p) {
      if (!p) return '—';
      if (p >= 10000000) return '₹' + (p/10000000).toFixed(2) + ' Cr';
      if (p >= 100000)   return '₹' + (p/100000).toFixed(2) + ' L';
      return '₹' + Number(p).toLocaleString('en-IN');
    },

    // current variant for a column (or null)
    _v(col) {
      var arr = this.variants[col];
      if (!arr || !arr.length) return null;
      var i = this.sel[col] ?? 0;
      return arr[i] || arr[0];
    },

    // header price label
    priceLabel(col) {
      var v = this._v(col);
      return v ? this._fmtPrice(v.price) : '';
    },

    // reactive cell text for price/range/battery rows
    vCell(col, key, unit) {
      var v = this._v(col);
      if (!v) return '—';
      if (key === 'price')   return this._fmtPrice(v.price);
      if (key === 'range')   return v.range ? v.range + (unit ? ' ' + unit : '') : '—';
      if (key === 'battery') return (v.battery || v.battery === 0) ? v.battery + (unit ? ' ' + unit : '') : '—';
      return '—';
    }
  };
}

// ── Scroll-reveal IntersectionObserver ──────────────────────
(function(){
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.row-reveal, .sec-reveal, .anim-winner, .anim-banner, .anim-bf-0, .anim-bf-1, .anim-bf-2').forEach(function(el){
    io.observe(el);
  });
})();

// Navigate to comparison URL
function doCompare() {
  const ids = [...new Set(Object.values(window._cmpS || {}))].filter(Boolean).slice(0, 3);
  if (ids.length < 2) {
    alert('Please select at least 2 EVs to compare.');
    return;
  }
  window.location.href = '<?= base_url('compare') ?>?vehicles=' + ids.join(',');
}

// Sticky bar shows when comparison table scrolls out of view
<?php if ($hasComparison): ?>
(function(){
  var bar = document.getElementById('cmp-sticky');
  var tbl = document.getElementById('compare-table');
  if (!bar || !tbl) return;
  new IntersectionObserver(function(ee){
    ee.forEach(function(e){ e.isIntersecting ? bar.classList.remove('show') : bar.classList.add('show'); });
  }, { threshold: 0 }).observe(tbl);
})();
<?php endif; ?>

// Scroll-reveal for table rows
(function(){
  var rows = document.querySelectorAll('.cmp-row');
  if (!rows.length) return;
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(entry, i){
      if (entry.isIntersecting) {
        var delay = (Array.from(rows).indexOf(entry.target) % 6) * 40;
        setTimeout(function(){ entry.target.classList.add('visible'); }, delay);
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
  rows.forEach(function(r){ obs.observe(r); });
})();

// Number counter pop animation for stats pill digits
(function(){
  document.querySelectorAll('.anim-hero-stats .text-2xl, .anim-hero-stats .text-xl').forEach(function(el){
    el.style.animation = 'numberPop .5s .4s ease both';
  });
})();
</script>

<?= $this->endSection() ?>

