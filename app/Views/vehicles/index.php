<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php /* ============================================================
   Vehicle Listing — Charj.in  |  CI4 · Tailwind · Alpine.js
   Variables expected from controller:
     $title         string  — "All EVs in India" or "Electric Scooters in India"
     $subtitle      string  — optional subtitle
     $vehicles      array   — paginated vehicle rows
     $totalVehicles int     — total count before pagination
     $brands        array   — [{id, name, slug, count}]
     $categories    array   — [{id, name, slug, count}]
     $activeFilters array   — currently applied filter keys
     $pager         object  — CI4 Pager instance
   ============================================================ */ ?>

<style>
[x-cloak]{display:none!important}
.filter-chip-active:hover .chip-x{opacity:1}
.range-btn.active,.price-btn.active{background:#00A896!important;color:#fff!important;border-color:#00A896!important}
.peer:checked ~ .range-pill{background:#00A896!important;color:#fff!important;border-color:#00A896!important}
.range-pill:hover{background:rgba(0,230,118,.1)!important;color:#00963C!important;border-color:rgba(0,230,118,.3)!important}

/* Card entrance */
@keyframes cardIn{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
.card-reveal{opacity:0;transform:translateY(18px);transition:opacity .42s ease,transform .42s cubic-bezier(.22,1,.36,1)}
.card-reveal.visible{opacity:1;transform:translateY(0)}

/* Hero entrance */
@keyframes heroFade{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.hero-in-1{animation:heroFade .5s .05s cubic-bezier(.22,1,.36,1) both}
.hero-in-2{animation:heroFade .5s .15s cubic-bezier(.22,1,.36,1) both}
.hero-in-3{animation:heroFade .5s .25s cubic-bezier(.22,1,.36,1) both}
.hero-in-4{animation:heroFade .5s .38s cubic-bezier(.22,1,.36,1) both}

/* Stat badge pop */
@keyframes statPop{from{opacity:0;transform:scale(.82) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
.stat-pop-0{animation:statPop .45s .3s cubic-bezier(.22,1,.36,1) both}
.stat-pop-1{animation:statPop .45s .42s cubic-bezier(.22,1,.36,1) both}
.stat-pop-2{animation:statPop .45s .54s cubic-bezier(.22,1,.36,1) both}

/* Filter chip hover */
.filter-chip{transition:all .15s ease}

/* Hide scrollbar utility */
.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none}
.scrollbar-hide::-webkit-scrollbar{display:none}
</style>

<?php
$hasFilters = !empty($activeFilters) || !empty($_GET['q']) || !empty($_GET['brand'])
           || !empty($_GET['category']) || !empty($_GET['price_min']) || !empty($_GET['price_max'])
           || !empty($_GET['range_min']) || !empty($_GET['sort'] && $_GET['sort'] !== 'relevance');
$selectedBrands  = isset($_GET['brand'])    ? (array) $_GET['brand']    : [];
$selectedCat     = $_GET['category'] ?? '';
$selectedPriceMin= $_GET['price_min'] ?? '';
$selectedPriceMax= $_GET['price_max'] ?? '';
$selectedRange   = (int)($_GET['range_min'] ?? 0);
$selectedSort    = $_GET['sort'] ?? 'relevance';
$searchQ         = $_GET['q']   ?? '';

$priceRanges = [
    ['Under ₹50K',  0,       50000],
    ['₹50K–1L',     50000,   100000],
    ['₹1L–2L',      100000,  200000],
    ['₹2L–5L',      200000,  500000],
    ['₹5L–15L',     500000,  1500000],
    ['₹15L+',       1500000, ''],
];
$rangeOptions = [0 => 'Any', 50 => '50+ km', 100 => '100+ km', 150 => '150+ km', 200 => '200+ km'];

$chips = [];
if ($searchQ) $chips[] = ['label' => '"' . esc($searchQ) . '"', 'remove' => 'q'];
if ($selectedCat) {
    $catName = '';
    foreach (($categories ?? []) as $c) { if (($c['slug'] ?? '') === $selectedCat || ($c['id'] ?? '') == $selectedCat) { $catName = $c['name']; break; } }
    $chips[] = ['label' => ($catName ?: $selectedCat), 'remove' => 'category'];
}
foreach ($selectedBrands as $b) {
    $bName = '';
    foreach (($brands ?? []) as $br) { if (($br['slug'] ?? '') === $b || ($br['id'] ?? '') == $b) { $bName = $br['name']; break; } }
    $chips[] = ['label' => ($bName ?: $b), 'remove' => 'brand', 'val' => $b];
}
if ($selectedPriceMin || $selectedPriceMax) {
    $pLabel = ($selectedPriceMin ? '₹'.number_format((int)$selectedPriceMin/100000,1).'L' : '₹0') . '–' . ($selectedPriceMax ? '₹'.number_format((int)$selectedPriceMax/100000,1).'L' : 'Any');
    $chips[] = ['label' => $pLabel, 'remove' => 'price'];
}
if ($selectedRange > 0) {
    $chips[] = ['label' => $selectedRange . '+ km range', 'remove' => 'range_min'];
}
if ($selectedSort && $selectedSort !== 'relevance') {
    $sortLabels = ['price_low'=>'Price: Low→High','price_high'=>'Price: High→Low','range'=>'Best Range','rating'=>'Top Rated','newest'=>'Newest'];
    $chips[] = ['label' => ($sortLabels[$selectedSort] ?? $selectedSort), 'remove' => 'sort'];
}

function removeParam(string $key, ?string $val = null): string {
    $p = $_GET;
    if ($key === 'price') { unset($p['price_min'], $p['price_max']); }
    elseif ($key === 'brand' && $val !== null) {
        $arr = isset($p['brand']) ? (array)$p['brand'] : [];
        $arr = array_filter($arr, fn($x) => $x !== $val);
        $p['brand'] = array_values($arr);
        if (empty($p['brand'])) unset($p['brand']);
    } else { unset($p[$key]); }
    unset($p['page']);
    return '?' . http_build_query($p);
}
?>

<div
  x-data="vehicleListing()"
  x-init="init()"
  class="min-h-screen"
  style="background:#F7FFFE"
>

<!-- PAGE HERO (compact) -->
<div class="hero-sm relative overflow-hidden pt-24 pb-6 px-4" style="background:linear-gradient(160deg,#F0FFF9,#EAFFF4,#F7FFFE)">
  <!-- Subtle mesh grid -->
  <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.12) 1px,transparent 1px);background-size:28px 28px;opacity:.45"></div>
  <!-- Ambient teal edge glow -->
  <div class="absolute top-0 right-0 w-96 h-56 pointer-events-none" style="background:radial-gradient(ellipse at 80% 20%,rgba(0,168,150,.14),transparent 65%);filter:blur(40px)"></div>

  <div class="relative max-w-7xl mx-auto">
    <nav class="text-xs mb-3 flex flex-wrap items-center gap-1.5 hero-in-1" aria-label="Breadcrumb">
      <a href="<?= base_url('/') ?>" class="text-slate-400 transition-colors" onmouseover="this.style.color='#00A896'" onmouseout="this.style.color=''">Home</a>
      <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span class="text-slate-500"><?= esc($title ?? 'All EVs') ?></span>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
      <div>
        <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-widest mb-3 hero-in-1"
             style="background:#00A896;color:#fff">
          <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
          Charj.in EV Database
        </div>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black leading-tight tracking-tight hero-in-2" style="color:#0F172A">
          <?= esc($title ?? 'All EVs in India') ?>
        </h1>
        <p class="text-sm mt-1.5 font-medium max-w-lg hero-in-3" style="color:#475569">
          <?= esc($subtitle ?? 'Compare prices, range & features across all electric vehicles') ?>
        </p>
      </div>
      <!-- Compact stat badges -->
      <div class="flex flex-wrap gap-2 hero-in-4">
        <div class="rounded-xl px-4 py-2 text-center stat-pop-0" style="background:#fff;border:1px solid rgba(0,168,150,.18);box-shadow:0 2px 10px rgba(0,168,150,.06)">
          <div class="text-lg font-black" style="color:#0F172A"><?= !empty($savedMode) ? count($vehicles ?? []) : (number_format($totalVehicles ?? count($vehicles ?? [])) . '+') ?></div>
          <div class="text-[9px] uppercase tracking-widest font-bold" style="color:#94A3B8"><?= !empty($savedMode) ? 'Saved EVs' : 'EVs Listed' ?></div>
        </div>
        <div class="rounded-xl px-4 py-2 text-center stat-pop-1" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.2)">
          <div class="text-lg font-black" style="color:#00A896">₹59k</div>
          <div class="text-[9px] uppercase tracking-widest font-bold" style="color:#94A3B8">Starting From</div>
        </div>
        <div class="rounded-xl px-4 py-2 text-center stat-pop-2" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.2)">
          <div class="text-lg font-black" style="color:#00A896">700km+</div>
          <div class="text-[9px] uppercase tracking-widest font-bold" style="color:#94A3B8">Best Range</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN LAYOUT -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-6">
  <div class="lg:grid lg:grid-cols-[260px_1fr] lg:gap-6 flex flex-col gap-5">

    <!-- MOBILE TOP BAR -->
    <div class="lg:hidden flex items-center justify-between gap-3 mb-1">
      <button
        @click="filterOpen = true"
        class="flex items-center gap-2 rounded-full px-4 py-2.5 font-semibold text-sm shadow-sm"
        style="background:#00A896;color:#fff"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filters
        <?php if ($hasFilters): ?>
        <span class="rounded-full w-5 h-5 text-xs flex items-center justify-center font-bold" style="background:#fff;color:#00A896">
          <?= count($chips) ?>
        </span>
        <?php endif; ?>
      </button>

    </div>

    <!-- MOBILE FILTER DRAWER -->
    <div x-show="filterOpen" x-cloak class="fixed inset-0 z-50 lg:hidden flex" @keydown.escape.window="filterOpen=false">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
           @click="filterOpen=false"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"></div>
      <div class="relative w-80 max-w-[90vw] h-full flex flex-col shadow-2xl overflow-y-auto"
           style="background:#FFFFFF"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full">
        <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-3.5 shadow-sm"
             style="background:#FFFFFF;border-bottom:1px solid rgba(0,168,150,.1)">
          <h2 class="font-bold text-base" style="color:#0F172A">Filter EVs</h2>
          <button @click="filterOpen=false" class="p-1.5 rounded-lg transition-colors" style="color:#64748B" aria-label="Close filters">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
          <?= view('partials/filter_panel', ['categories' => $categories ?? [], 'brands' => $brands ?? [], 'request' => service('request')]) ?>
        </div>
      </div>
    </div>

    <!-- DESKTOP FILTER SIDEBAR -->
    <aside class="hidden lg:block w-72 flex-shrink-0 self-start sticky top-24" aria-label="Filter panel">
      <div class="rounded-2xl overflow-hidden" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);box-shadow:0 4px 20px rgba(0,168,150,.08),0 1px 4px rgba(0,0,0,.04)">

        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:2px solid rgba(0,168,150,.08);background:linear-gradient(90deg,rgba(0,168,150,.06) 0%,transparent 100%)">
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(0,168,150,.1)">
              <svg class="w-3.5 h-3.5" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
            </div>
            <h2 class="font-extrabold text-sm" style="color:#0F172A">Filters</h2>
          </div>
          <?php if ($hasFilters): ?>
          <a href="<?= base_url('vehicles') ?>" class="text-xs font-bold px-2.5 py-1 rounded-full transition-all" style="color:#EF4444;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15)">
            Clear all
          </a>
          <?php endif; ?>
        </div>

        <form method="GET" action="<?= base_url('vehicles') ?>" x-data="filterForm()" class="divide-y" style="--tw-divide-opacity:1;border-color:rgba(0,168,150,.06)">

          <!-- Search -->
          <div class="px-5 py-4">
            <label for="q_search" class="block text-[11px] font-bold uppercase tracking-widest mb-2.5" style="color:#94A3B8">Search</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5" style="color:#94A3B8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <input
                type="text" id="q_search" name="q"
                value="<?= esc($searchQ) ?>"
                placeholder="Search EV model or brand..."
                class="w-full pl-9 pr-3 py-2 rounded-xl text-sm focus:outline-none"
                style="border:1.5px solid rgba(0,168,150,.15);color:#0F172A;background:#FAFFFE"
                onfocus="this.style.borderColor='#00A896';this.style.boxShadow='0 0 0 3px rgba(0,168,150,.08)'"
                onblur="this.style.borderColor='rgba(0,168,150,.15)';this.style.boxShadow='none'"
              >
            </div>
          </div>

          <!-- Vehicle Type -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-3" style="color:#94A3B8">Vehicle Type</p>
            <div class="space-y-2">
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="radio" name="category" value="" <?= empty($selectedCat)?'checked':'' ?> class="w-4 h-4 accent-[#00A896]">
                <span class="text-sm font-medium transition-colors" style="color:#334155">All EVs</span>
              </label>
              <?php
              $typeOptions = [
                ['Electric Scooters', 'scooter',    'M12 3a2 2 0 012 2v1h3l2 4H5l2-4h3V5a2 2 0 012-2zM5 10v4a1 1 0 001 1h1a3 3 0 006 0h1a1 1 0 001-1v-4H5z'],
                ['Electric Cars',     'car',         'M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0v5a1 1 0 001 1h1m11-1a1 1 0 01-1 1h-1m-9 0a2 2 0 104 0m5 0a2 2 0 104 0'],
                ['Electric Bikes',    'bike',        'M12 2a5 5 0 015 5v3H7V7a5 5 0 015-5zM7 10H5a3 3 0 000 6h2m10 0h2a3 3 0 000-6h-2'],
                ['E-Rickshaws',       'rickshaw',    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['Commercial EVs',    'commercial',  'M8 6h8M8 6v8m0-8H6a2 2 0 00-2 2v6h2m8-8h2a2 2 0 012 2v6h-2m-8 0h8m-8 0H6m8 0h2'],
              ];
              foreach ($typeOptions as [$typeName, $typeSlug, $iconPath]):
                $isSelected = $selectedCat === $typeSlug;
                $typeCount = '';
                foreach (($categories ?? []) as $c) {
                    if (($c['slug'] ?? '') === $typeSlug) { $typeCount = $c['count'] ?? ''; break; }
                }
              ?>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="radio" name="category" value="<?= $typeSlug ?>"
                  <?= $isSelected ? 'checked' : '' ?>
                  class="w-4 h-4 accent-[#00A896]">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="<?= $iconPath ?>"/>
                </svg>
                <span class="text-sm transition-colors flex-1" style="color:#475569"><?= $typeName ?></span>
                <?php if ($typeCount): ?>
                <span class="text-[11px] px-1.5 py-0.5 rounded-full" style="background:rgba(0,168,150,.08);color:#64748B"><?= $typeCount ?></span>
                <?php endif; ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Brand -->
          <?php if (!empty($brands)): ?>
          <div class="px-5 py-4" x-data="{showAll: false}">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-3" style="color:#94A3B8">Brand</p>
            <div class="space-y-2 overflow-hidden" :class="showAll ? '' : 'max-h-44'">
              <?php foreach ($brands as $brand):
                $bSlug = $brand['slug'] ?? $brand['id'] ?? '';
                $checked = in_array($bSlug, $selectedBrands);
              ?>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="brand[]"
                  value="<?= esc($bSlug) ?>"
                  <?= $checked ? 'checked' : '' ?>
                  class="w-4 h-4 accent-[#00A896] rounded">
                <span class="w-6 h-6 rounded flex items-center justify-center text-[11px] font-bold flex-shrink-0"
                      style="background:rgba(0,168,150,.08);color:#00A896">
                  <?= strtoupper(substr($brand['name'], 0, 1)) ?>
                </span>
                <span class="text-sm transition-colors flex-1 leading-tight" style="color:#475569">
                  <?= esc($brand['name']) ?>
                </span>
                <?php if (!empty($brand['count'])): ?>
                <span class="text-[11px]" style="color:#94A3B8">(<?= $brand['count'] ?>)</span>
                <?php endif; ?>
              </label>
              <?php endforeach; ?>
            </div>
            <?php if (count($brands) > 5): ?>
            <button type="button" @click="showAll = !showAll"
              class="mt-2.5 text-xs font-semibold transition-colors" style="color:#00A896">
              <span x-text="showAll ? 'Show less' : 'Show all brands'"></span>
            </button>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- Price Range -->
          <div class="px-5 py-4" x-data="{priceMin: '<?= esc($selectedPriceMin) ?>', priceMax: '<?= esc($selectedPriceMax) ?>'}">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-3" style="color:#94A3B8">Price Range</p>
            <div class="flex flex-wrap gap-1.5 mb-3">
              <?php foreach ($priceRanges as [$label, $min, $max]):
                $isActive = ((string)$selectedPriceMin === (string)$min && (string)$selectedPriceMax === (string)$max);
              ?>
              <button type="button"
                @click="priceMin='<?= $min ?>'; priceMax='<?= $max ?>'; $refs.priceMinI.value='<?= $min ?>'; $refs.priceMaxI.value='<?= $max ?>'; $nextTick(()=>$el.closest('form').submit())"
                class="price-btn text-xs px-2.5 py-1 rounded-lg transition-all <?= $isActive ? 'active' : '' ?>"
                style="background:rgba(0,230,118,.06);border:1px solid rgba(0,230,118,.18);color:#475569">
                <?= $label ?>
              </button>
              <?php endforeach; ?>
            </div>
            <div class="grid grid-cols-2 gap-2">
              <div>
                <label class="text-[10px] mb-1 block" style="color:#94A3B8">Min (₹)</label>
                <div class="relative">
                  <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs font-medium" style="color:#94A3B8">₹</span>
                  <input type="number" name="price_min" x-ref="priceMinI"
                    :value="priceMin" @input="priceMin=$event.target.value"
                    placeholder="0"
                    class="w-full pl-6 pr-2 py-2 rounded-xl text-sm focus:outline-none"
                    style="border:1.5px solid rgba(0,168,150,.15);color:#334155;background:#FAFFFE"
                    onfocus="this.style.borderColor='#00A896'" onblur="this.style.borderColor='rgba(0,168,150,.15)'">
                </div>
              </div>
              <div>
                <label class="text-[10px] mb-1 block" style="color:#94A3B8">Max (₹)</label>
                <div class="relative">
                  <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs font-medium" style="color:#94A3B8">₹</span>
                  <input type="number" name="price_max" x-ref="priceMaxI"
                    :value="priceMax" @input="priceMax=$event.target.value"
                    placeholder="Any"
                    class="w-full pl-6 pr-2 py-2 rounded-xl text-sm focus:outline-none"
                    style="border:1.5px solid rgba(0,168,150,.15);color:#334155;background:#FAFFFE"
                    onfocus="this.style.borderColor='#00A896'" onblur="this.style.borderColor='rgba(0,168,150,.15)'">
                </div>
              </div>
            </div>
          </div>

          <!-- Range -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-3" style="color:#94A3B8">Minimum Range</p>
            <div class="flex flex-wrap gap-1.5">
              <?php foreach ($rangeOptions as $km => $label): ?>
              <label class="cursor-pointer">
                <input type="radio" name="range_min" value="<?= $km ?>"
                  <?= $selectedRange == $km ? 'checked' : '' ?>
                  class="sr-only peer">
                <span class="range-pill peer-checked:text-white inline-block text-xs px-3 py-1.5 rounded-lg transition-all cursor-pointer"
                      style="background:rgba(0,168,150,.06);border:1px solid rgba(0,168,150,.15);color:#475569">
                  <?= $label ?>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Features -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-3" style="color:#94A3B8">Features</p>
            <div class="space-y-2.5">
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="fast_charging" value="1"
                  <?= !empty($_GET['fast_charging']) ? 'checked' : '' ?>
                  class="w-4 h-4 accent-[#00A896] rounded">
                <span class="text-sm transition-colors" style="color:#475569">Fast Charging Available</span>
              </label>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="connected" value="1"
                  <?= !empty($_GET['connected']) ? 'checked' : '' ?>
                  class="w-4 h-4 accent-[#00A896] rounded">
                <span class="text-sm transition-colors" style="color:#475569">Connected Features</span>
              </label>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="fame_eligible" value="1"
                  <?= !empty($_GET['fame_eligible']) ? 'checked' : '' ?>
                  class="w-4 h-4 accent-[#00A896] rounded">
                <span class="text-sm transition-colors" style="color:#475569">FAME II Eligible</span>
              </label>
            </div>
          </div>

          <!-- Sort -->
          <div class="px-5 py-4">
            <label for="sidebar_sort" class="block text-[11px] font-bold uppercase tracking-widest mb-2.5" style="color:#94A3B8">Sort By</label>
            <select id="sidebar_sort" name="sort"
              class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none cursor-pointer appearance-none"
              style="border:1.5px solid rgba(0,168,150,.15);color:#334155;background:#FAFFFE"
              onfocus="this.style.borderColor='#00A896'" onblur="this.style.borderColor='rgba(0,168,150,.15)'">
              <option value="relevance"  <?= $selectedSort==='relevance' ?'selected':'' ?>>Relevance</option>
              <option value="price_low"  <?= $selectedSort==='price_low' ?'selected':'' ?>>Price: Low → High</option>
              <option value="price_high" <?= $selectedSort==='price_high'?'selected':'' ?>>Price: High → Low</option>
              <option value="range"      <?= $selectedSort==='range'     ?'selected':'' ?>>Best Range</option>
              <option value="rating"     <?= $selectedSort==='rating'    ?'selected':'' ?>>Top Rated</option>
              <option value="newest"     <?= $selectedSort==='newest'    ?'selected':'' ?>>Newest First</option>
            </select>
          </div>

          <!-- Actions -->
          <div class="px-5 py-4 space-y-2" style="background:#F7FFFE">
            <button type="submit"
              class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm transition-all"
              style="background:#00A896;color:#fff;box-shadow:0 4px 14px rgba(0,168,150,.3)"
              onmouseover="this.style.boxShadow='0 6px 20px rgba(0,168,150,.45)';this.style.transform='translateY(-1px)'"
              onmouseout="this.style.boxShadow='0 4px 14px rgba(0,168,150,.3)';this.style.transform=''">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
              Apply Filters
            </button>
            <?php if ($hasFilters): ?>
            <a href="<?= base_url('vehicles') ?>"
               class="block w-full text-center font-medium py-2 text-sm transition-colors" style="color:#94A3B8"
               onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#94A3B8'">
              Clear All Filters
            </a>
            <?php endif; ?>
          </div>

        </form>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 min-w-0">

      <?php if (!empty($savedMode)): ?>
      <!-- Saved EVs banner -->
      <div class="flex items-center justify-between gap-3 mb-4 px-4 py-3 rounded-2xl"
           style="background:rgba(239,68,68,.05);border:1.5px solid rgba(239,68,68,.18)">
        <div class="flex items-center gap-2.5">
          <svg class="w-5 h-5 flex-shrink-0" style="color:#ef4444" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span class="text-sm font-semibold" style="color:#0F172A">
            Showing your <strong><?= count($vehicles ?? []) ?></strong> saved EV<?= count($vehicles ?? []) !== 1 ? 's' : '' ?>
          </span>
        </div>
        <a href="<?= base_url('vehicles') ?>"
           class="text-xs font-bold px-3 py-1.5 rounded-full transition-all"
           style="background:#FFFFFF;border:1px solid rgba(0,230,118,.3);color:#00C060"
           onmouseover="this.style.background='rgba(0,230,118,.06)'"
           onmouseout="this.style.background='#FFFFFF'">
          Browse all EVs →
        </a>
      </div>
      <?php endif; ?>

      <!-- Results toolbar (compact, sticky) -->
      <div class="flex flex-wrap items-center gap-2 mb-4 sticky top-20 z-20 rounded-2xl px-3 py-2.5"
           style="background:rgba(247,255,254,.92);backdrop-filter:blur(10px);border:1px solid rgba(0,168,150,.12)">

        <!-- Count -->
        <p class="text-sm font-medium mr-1" style="color:#64748B">
          <?php if (!empty($savedMode)): ?>
          <strong style="color:#0F172A"><?= number_format(count($vehicles ?? [])) ?></strong> saved EV<?= count($vehicles ?? []) !== 1 ? 's' : '' ?>
          <?php else: ?>
          Showing <strong style="color:#0F172A"><?= number_format($totalVehicles ?? count($vehicles ?? [])) ?></strong> EVs
          <?php endif; ?>
        </p>

        <!-- Active chips -->
        <?php foreach ($chips as $chip):
          $removeUrl = isset($chip['val'])
            ? removeParam($chip['remove'], $chip['val'])
            : removeParam($chip['remove']);
        ?>
        <a href="<?= base_url('vehicles') . $removeUrl ?>"
           class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full transition-all"
           style="background:#00A896;border:1px solid #00A896;color:#fff"
           onmouseover="this.style.background='#EF4444';this.style.borderColor='#EF4444'"
           onmouseout="this.style.background='#00A896';this.style.borderColor='#00A896'">
          <?= esc($chip['label']) ?> <span style="opacity:.7">✕</span>
        </a>
        <?php endforeach; ?>

        <!-- Clear All (only when filters active) -->
        <?php if ($hasFilters): ?>
        <a href="<?= base_url('vehicles') ?>"
           class="text-xs font-semibold px-3 py-1.5 rounded-full transition-all"
           style="color:#EF4444;border:1px solid rgba(239,68,68,.25);background:rgba(239,68,68,.04)"
           onmouseover="this.style.background='rgba(239,68,68,.09)'"
           onmouseout="this.style.background='rgba(239,68,68,.04)'">
          Clear All
        </a>
        <?php endif; ?>

        <!-- Sort dropdown (right-aligned) -->
        <form method="GET" class="ml-auto flex-shrink-0">
          <?php foreach ($_GET as $k => $v): if ($k === 'sort') continue; ?>
            <?php if (is_array($v)): foreach ($v as $vi): ?><input type="hidden" name="<?= esc($k) ?>[]" value="<?= esc($vi) ?>"><?php endforeach;
            else: ?><input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>"><?php endif; ?>
          <?php endforeach; ?>
          <select name="sort" onchange="this.form.submit()"
            class="rounded-xl px-3 py-2 text-xs focus:outline-none cursor-pointer appearance-none"
            style="background:#FFFFFF;border:1px solid rgba(0,230,118,.2);color:#374151;padding-right:2rem">
            <option value="relevance"  <?= $selectedSort==='relevance' ?'selected':'' ?>>Sort: Relevance</option>
            <option value="price_low"  <?= $selectedSort==='price_low' ?'selected':'' ?>>Price: Low → High</option>
            <option value="price_high" <?= $selectedSort==='price_high'?'selected':'' ?>>Price: High → Low</option>
            <option value="range"      <?= $selectedSort==='range'     ?'selected':'' ?>>Best Range</option>
            <option value="rating"     <?= $selectedSort==='rating'    ?'selected':'' ?>>Top Rated</option>
            <option value="newest"     <?= $selectedSort==='newest'    ?'selected':'' ?>>Newest</option>
          </select>
        </form>

      </div>

      <!-- Vehicle grid -->
      <?php if (!empty($vehicles)): ?>

      <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sr-stagger">
        <?php foreach ($vehicles as $vehicle): ?>
          <?= view('partials/vehicle_card', ['vehicle' => $vehicle]) ?>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if (!empty($pager) && ($pager->getPageCount('default') ?? 1) > 1): ?>
      <?php
        $curPage  = $pager->getCurrentPage('default') ?? 1;
        $totPages = $pager->getPageCount('default')   ?? 1;
        $prevPage = $curPage > 1 ? $curPage - 1 : null;
        $nextPage = $curPage < $totPages ? $curPage + 1 : null;
        $qp       = $_GET;
      ?>
      <nav class="mt-8 flex flex-col items-center gap-3" aria-label="Pagination">
        <div class="flex items-center gap-1.5 flex-wrap justify-center">

          <?php if ($prevPage): $qp['page'] = $prevPage; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="flex items-center gap-1 px-4 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold transition-all"
             style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);color:#475569"
             onmouseover="this.style.background='#00A896';this.style.color='#fff';this.style.borderColor='#00A896'"
             onmouseout="this.style.background='#FFFFFF';this.style.color='#475569';this.style.borderColor='rgba(0,168,150,.15)'">
            ← Prev
          </a>
          <?php else: ?>
          <span class="flex items-center gap-1 px-4 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold cursor-not-allowed"
                style="background:#F8FAFC;border:1px solid rgba(0,0,0,.05);color:#CBD5E1">
            ← Prev
          </span>
          <?php endif; ?>

          <?php
          $startP = max(1, $curPage - 2);
          $endP   = min($totPages, $curPage + 2);
          if ($startP > 1): $qp['page'] = 1; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all"
             style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);color:#475569"
             onmouseover="this.style.background='#00A896';this.style.color='#fff'" onmouseout="this.style.background='#FFFFFF';this.style.color='#475569'">1</a>
          <?php if ($startP > 2): ?><span class="px-2 text-sm self-center" style="color:#94A3B8">…</span><?php endif; ?>
          <?php endif; ?>

          <?php for ($i = $startP; $i <= $endP; $i++): $qp['page'] = $i; ?>
          <?php if ($i === $curPage): ?>
          <span class="px-3.5 py-2.5 text-white rounded-xl text-sm font-bold shadow-md" style="background:#00A896;box-shadow:0 0 12px rgba(0,168,150,.3)"><?= $i ?></span>
          <?php else: ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all"
             style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);color:#475569"
             onmouseover="this.style.background='#00A896';this.style.color='#fff'" onmouseout="this.style.background='#FFFFFF';this.style.color='#475569'"><?= $i ?></a>
          <?php endif; ?>
          <?php endfor; ?>

          <?php if ($endP < $totPages): ?>
          <?php if ($endP < $totPages - 1): ?><span class="px-2 text-sm self-center" style="color:#94A3B8">…</span><?php endif; ?>
          <?php $qp['page'] = $totPages; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all"
             style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);color:#475569"
             onmouseover="this.style.background='#00A896';this.style.color='#fff'" onmouseout="this.style.background='#FFFFFF';this.style.color='#475569'"><?= $totPages ?></a>
          <?php endif; ?>

          <?php if ($nextPage): $qp['page'] = $nextPage; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="flex items-center gap-1 px-4 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold transition-all"
             style="background:#FFFFFF;border:1px solid rgba(0,168,150,.15);color:#475569"
             onmouseover="this.style.background='#00A896';this.style.color='#fff';this.style.borderColor='#00A896'"
             onmouseout="this.style.background='#FFFFFF';this.style.color='#475569';this.style.borderColor='rgba(0,168,150,.15)'">
            Next →
          </a>
          <?php else: ?>
          <span class="flex items-center gap-1 px-4 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold cursor-not-allowed"
                style="background:#F8FAFC;border:1px solid rgba(0,0,0,.05);color:#CBD5E1">
            Next →
          </span>
          <?php endif; ?>

        </div>
        <p class="text-xs" style="color:#94A3B8">Page <?= $curPage ?> of <?= $totPages ?></p>
      </nav>
      <?php endif; ?>

      <?php else: ?>
      <!-- Empty state -->
      <div class="text-center py-12 px-4 rounded-2xl" style="background:linear-gradient(160deg,#F0FFF9,#F7FFFE);border:1px solid rgba(0,168,150,.14)">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
             style="background:rgba(0,168,150,.1);border:1px solid rgba(0,168,150,.2)">
          <svg class="w-8 h-8" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold mb-1.5" style="color:#0F172A">No EVs Found</h3>
        <p class="mb-5 max-w-sm mx-auto text-sm leading-relaxed" style="color:#64748B">
          No electric vehicles match your current filters. Try broadening your search or clearing some filters.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-6">
          <a href="<?= base_url('vehicles') ?>"
             class="text-white px-6 py-3 rounded-xl font-bold transition-all"
             style="background:#00A896;box-shadow:0 4px 14px rgba(0,168,150,.25)"
             onmouseover="this.style.boxShadow='0 6px 20px rgba(0,168,150,.4)'" onmouseout="this.style.boxShadow='0 4px 14px rgba(0,168,150,.25)'">
            Clear All Filters
          </a>
          <a href="<?= base_url('find-my-ev') ?>"
             class="px-6 py-3 rounded-xl font-bold transition-all"
             style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.2);color:#00A896"
             onmouseover="this.style.background='#F0FDFA'" onmouseout="this.style.background='#FFFFFF'">
            Find My EV →
          </a>
        </div>
        <div class="pt-5" style="border-top:1px solid rgba(0,168,150,.1)">
          <p class="text-xs mb-3 uppercase tracking-wide font-medium" style="color:#94A3B8">Popular searches</p>
          <div class="flex flex-wrap gap-2 justify-center">
            <?php foreach ([
              ['Electric Scooters', 'vehicles?category=scooter'],
              ['Electric Cars',     'vehicles?category=car'],
              ['Electric Bikes',    'vehicles?category=bike'],
              ['Under ₹1 Lakh',    'vehicles?price_max=100000'],
              ['150+ km Range',    'vehicles?range_min=150'],
            ] as [$lbl, $href]): ?>
            <a href="<?= base_url($href) ?>"
               class="px-3 py-1.5 rounded-full text-sm transition-all"
               style="background:rgba(0,168,150,.06);border:1px solid rgba(0,168,150,.15);color:#475569"
               onmouseover="this.style.background='#00A896';this.style.color='#fff'" onmouseout="this.style.background='rgba(0,168,150,.06)';this.style.color='#475569'">
              <?= $lbl ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </main>
  </div>
</div>

<!-- COMPARE BAR -->
<div
  x-show="compareList.length >= 2"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="translate-y-full opacity-0"
  x-transition:enter-end="translate-y-0 opacity-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="translate-y-0 opacity-100"
  x-transition:leave-end="translate-y-full opacity-0"
  class="fixed bottom-0 left-0 right-0 z-40 shadow-2xl"
  style="background:rgba(255,255,255,.97);border-top:2px solid #00A896;backdrop-filter:blur(20px);color:#0F172A"
  role="region"
  aria-label="Compare bar"
>
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3 flex-wrap min-w-0">
      <svg class="w-5 h-5 fill-current hidden sm:block" style="color:#00A896" viewBox="0 0 24 24" aria-hidden="true"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
      <span class="font-bold text-sm whitespace-nowrap" style="color:#0F172A">
        Comparing <span x-text="compareList.length" class="text-base font-black" style="color:#00A896"></span> EVs
      </span>
      <div class="flex items-center gap-2">
        <template x-for="(item, idx) in compareList" :key="item.id">
          <div class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.2);color:#0F172A">
            <span x-text="item.name" class="max-w-[90px] truncate text-xs font-medium"></span>
            <button @click="removeFromCompare(idx)"
              class="ml-0.5 transition-colors flex-shrink-0" style="color:#94A3B8"
              onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#94A3B8'"
              :aria-label="'Remove ' + item.name">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </template>
        <template x-if="compareList.length < 3">
          <div class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs" style="border:1px dashed rgba(0,168,150,.3);color:#94A3B8">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add 3rd
          </div>
        </template>
      </div>
    </div>
    <div class="flex items-center gap-3 flex-shrink-0">
      <button @click="clearCompare()" class="text-xs font-medium transition-colors" style="color:#94A3B8"
        onmouseover="this.style.color='#0F172A'" onmouseout="this.style.color='#94A3B8'">
        Clear
      </button>
      <a :href="compareUrl()"
         class="text-white px-5 py-2.5 rounded-xl font-bold transition-all text-sm whitespace-nowrap"
         style="background:#00A896;box-shadow:0 0 16px rgba(0,168,150,.4)"
         onmouseover="this.style.background='#00bfa5'" onmouseout="this.style.background='#00A896'">
        Compare Now →
      </a>
    </div>
  </div>
</div>

</div><!-- /x-data vehicleListing -->

<script>
// Stagger-reveal cards as they enter viewport
(function(){
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e, i){
      if(e.isIntersecting){
        // slight per-card delay based on position in row
        var idx = Array.from(document.querySelectorAll('.card-reveal')).indexOf(e.target);
        var delay = (idx % 3) * 60; // stagger within each row
        setTimeout(function(){ e.target.classList.add('visible'); }, delay);
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.06, rootMargin: '0px 0px -30px 0px' });
  document.querySelectorAll('.card-reveal').forEach(function(el){ io.observe(el); });
})();

function vehicleListing() {
  return {
    filterOpen: false,
    compareList: [],

    init() {
      try {
        const s = localStorage.getItem('charj_compare');
        if (s) this.compareList = JSON.parse(s);
      } catch(e) { this.compareList = []; }

      window.addEventListener('charj:add-compare', (e) => {
        this.addToCompare(e.detail);
      });
      window.addEventListener('charj:remove-compare', (e) => {
        const idx = this.compareList.findIndex(v => v.id == e.detail.id);
        if (idx > -1) this.removeFromCompare(idx);
      });
    },

    addToCompare(vehicle) {
      if (this.compareList.length >= 3) {
        alert('You can compare up to 3 EVs. Remove one to add another.');
        return false;
      }
      if (!this.compareList.find(v => v.id == vehicle.id)) {
        this.compareList.push(vehicle);
        this.saveCompare();
        return true;
      }
      return false;
    },

    removeFromCompare(idx) {
      this.compareList.splice(idx, 1);
      this.saveCompare();
    },

    clearCompare() {
      this.compareList = [];
      localStorage.removeItem('charj_compare');
    },

    saveCompare() {
      localStorage.setItem('charj_compare', JSON.stringify(this.compareList));
    },

    compareUrl() {
      const ids = this.compareList.map(v => v.slug || v.id).join(',');
      return '<?= base_url("compare") ?>?ids=' + ids;
    }
  }
}

function filterForm() {
  return {};
}
</script>

<div class="pb-4 md:pb-0"></div>

<?= $this->endSection() ?>


