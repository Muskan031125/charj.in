?<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Explore EVs by Brand — Charj.in') ?></title>
<meta name="description" content="Browse every EV brand in India. Find electric scooters, bikes, cars and commercial vehicles from Ola, Ather, Tata, TVS, MG and more.">
<style>
[x-cloak] { display: none !important; }

/* ── animated hero gradient ── */
@keyframes gradShift {
  0%,100% { background-position: 0% 50%; }
  50%      { background-position: 100% 50%; }
}
.explore-grad {
  background: linear-gradient(160deg,#F3FFF6 0%,#ECFFF1 40%,#F7FFF9 70%,#F1FFF5 100%);
  background-size: 300% 300%;
  animation: gradShift 14s ease infinite;
}

/* ── brand card ── */
.bc {
  background: #fff;
  border: 1.5px solid rgba(0,230,118,.1);
  border-radius: 1.25rem;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s cubic-bezier(.22,1,.36,1), border-color .25s cubic-bezier(.22,1,.36,1);
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
  text-decoration: none;
  cursor: pointer;
}
.bc:hover {
  transform: translateY(-7px);
  box-shadow: 0 14px 32px rgba(0,230,118,.16), 0 4px 12px rgba(0,0,0,.06);
  border-color: rgba(0,230,118,.35);
}
@media (hover: none) {
  .bc:active { transform: scale(.97); transition: transform .1s ease; }
}

/* ── logo zone ── */
.bc-logo {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(145deg,#F3FFF6,#ECFFF1);
  border-bottom: 1px solid rgba(0,230,118,.08);
  padding: 1.125rem 0;
}

/* ── fallback letter tile ── */
.bc-letter {
  width: 52px; height: 52px;
  border-radius: .875rem;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.625rem; font-weight: 900;
  color: #00963C;
  background: linear-gradient(135deg,rgba(0,230,118,.18),rgba(0,230,118,.07));
  border: 1.5px solid rgba(0,230,118,.22);
  flex-shrink: 0;
}

/* ── card body ── */
.bc-body {
  padding: 1rem 1rem 1.125rem;
  text-align: center;
  width: 100%;
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* ── filter pill active/inactive ── */
.fp-pill {
  flex-shrink: 0;
  border-radius: 9999px;
  padding: .375rem 1rem;
  font-size: .75rem;
  font-weight: 600;
  border: 1.5px solid rgba(0,230,118,.2);
  background: #fff;
  color: #475569;
  cursor: pointer;
  transition: all .22s cubic-bezier(.22,1,.36,1);
  white-space: nowrap;
  -webkit-tap-highlight-color: transparent;
}
.fp-pill:hover:not(.active) { border-color: rgba(0,230,118,.4); background: rgba(0,230,118,.05); color: #00963C; }
.fp-pill.active {
  background: #00E676;
  color: #022C22;
  border-color: #00E676;
  font-weight: 700;
  box-shadow: 0 2px 10px rgba(0,230,118,.3);
}

/* ── view button ── */
.bc-btn {
  display: inline-flex; align-items: center; gap: .25rem;
  margin-top: .625rem;
  padding: .35rem .875rem;
  border-radius: .625rem;
  font-size: .7rem;
  font-weight: 700;
  color: #022C22;
  background: linear-gradient(135deg,#00E676,#69FF97);
  box-shadow: 0 2px 8px rgba(0,230,118,.22);
  text-decoration: none;
  transition: box-shadow .25s cubic-bezier(.22,1,.36,1), transform .25s cubic-bezier(.22,1,.36,1), filter .25s ease;
}
.bc:hover .bc-btn { box-shadow: 0 4px 14px rgba(0,230,118,.4); transform: translateY(-1px); filter: brightness(1.06); }

/* ── grid: prevent last odd card from stretching on 2-col ── */
@media (max-width: 639px) {
  .brand-grid { grid-template-columns: repeat(2, 1fr); }
  .brand-grid .bc:last-child:nth-child(odd) { grid-column: 1 / 2; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<script>
// Brand data for reactive filter-count logic — kept in a <script> block rather than inline in the
// x-data HTML attribute, since raw JSON contains double quotes that would otherwise prematurely
// terminate a double-quoted x-data="..." attribute and silently break the whole component.
const EXPLORE_BRANDS = <?= json_encode(array_map(fn($b) => [
    'name'        => strtolower($b['name'] ?? ''),
    'filterTypes' => $b['filter_types'] ?? 'all',
], $brands ?? []), JSON_UNESCAPED_UNICODE) ?>;
</script>

<div style="background:#F5FFF7;min-height:100vh"
     x-data="{
       filter: 'all',
       search: '',
       showMobileFilters: false,
       showSuggestions: false,
       brandsData: EXPLORE_BRANDS,
       init() {
         // Keyboard shortcut: Cmd+K / Ctrl+K to focus search
         document.addEventListener('keydown', (e) => {
           if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
             e.preventDefault();
             const searchInput = document.querySelector('[data-explore-search]');
             if (searchInput) searchInput.focus();
           }
         });
       },
       setFilter(f) {
         this.filter = f;
         this.showMobileFilters = false; // Close mobile filters after selection
       },
       clearSearch() { this.search = ''; },
       // Single source of truth for 'how many brand cards are visible right now' — used by the
       // no-results message. Computed from real data instead of querying live DOM (.bc[style*=...]),
       // which was unreliable because it wasn't guaranteed to re-run after the `filter` state changed.
       get visibleCount() {
         const q = this.search.toLowerCase();
         return this.brandsData.filter(b =>
           (this.filter === 'all' || b.filterTypes.split(',').includes(this.filter)) &&
           (q === '' || b.name.includes(q))
         ).length;
       }
     }">

<!-- ════ HERO ════ -->
<section class="hero-sm explore-grad relative overflow-hidden px-4 text-center pt-20 sm:pt-24 md:pt-32"
         style="padding-bottom:clamp(1.5rem,4.5vw,2.75rem);border-bottom:1px solid rgba(0,230,118,.1)">

  <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-56"
         style="background:radial-gradient(ellipse,rgba(0,230,118,.07) 0%,transparent 65%);filter:blur(2px)"></div>
    <div class="absolute top-12 left-10 w-2 h-2 rounded-full" style="background:#00E676;opacity:.2"></div>
    <div class="absolute top-16 right-16 w-1.5 h-1.5 rounded-full" style="background:#69FF97;opacity:.18"></div>
  </div>

  <div class="relative max-w-2xl mx-auto">
    <div class="inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 mb-4"
         style="background:rgba(0,230,118,.08);border:1.5px solid rgba(0,230,118,.22)">
      <svg class="w-3 h-3 flex-shrink-0" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
      </svg>
      <span class="text-[11px] font-bold uppercase tracking-wider" style="color:#00963C">
        <?= count($brands ?? []) ?>+ Brands · India's EV Database
      </span>
    </div>

    <h1 class="font-black tracking-tight leading-tight mb-3"
        style="font-size:clamp(1.5rem,6vw,3rem);color:#0F172A">
      Explore EV <span style="color:#00C060">Brands</span>
    </h1>
    <p style="color:#3F4E63;font-size:clamp(.875rem,3vw,1.125rem);line-height:1.65">
      From homegrown innovators to global giants — find, compare, choose.
    </p>
  </div>
</section>

<!-- ════ ENHANCED SEARCH SECTION ════ -->
<div style="background:#fff;border-bottom:1px solid rgba(0,230,118,.08);padding:1.6rem 1rem">
  <div class="max-w-3xl mx-auto">
    <div class="relative">
      <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 pointer-events-none" style="color:#94A3B8"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
      </svg>
      <input x-model="search"
             data-explore-search
             type="text"
             placeholder="Search brands… (Cmd+K)"
             class="w-full pl-12 pr-12 py-3 rounded-xl text-sm focus:outline-none"
             style="background:#FAFFFE;border:1.5px solid rgba(0,230,118,.2);color:#0F172A;box-shadow:0 1px 3px rgba(0,0,0,.03);transition:border-color .25s ease,box-shadow .25s ease"
             onfocus="this.style.borderColor='#00E676';this.style.boxShadow='0 0 0 4px rgba(0,230,118,.12)'"
             onblur="this.style.borderColor='rgba(0,230,118,.2)';this.style.boxShadow='0 1px 3px rgba(0,0,0,.03)'"
             @keydown.escape="clearSearch()">
      <button x-show="search !== ''" @click="clearSearch()" x-cloak
              class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
              style="opacity:.6">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Result counter -->
    <div class="mt-3 text-xs text-center" style="color:#5B6B80">
      <?php
        $totalBrands = count($brands ?? []);
        $totalVehicles = 0;
        foreach ($brands as $b) {
          $totalVehicles += (int)($b['ev_count'] ?? $b['vehicle_count'] ?? 0);
        }
      ?>
      <span x-show="search === ''">
        Showing <strong style="color:#0F172A"><?= $totalBrands ?></strong> brands ·
        <strong style="color:#0F172A"><?= $totalVehicles ?></strong> vehicles
      </span>
      <span x-show="search !== ''" x-cloak>
        Searching… <strong style="color:#0F172A" x-text="search"></strong>
      </span>
    </div>
  </div>
</div>

<!-- ════ FEATURED & TRENDING HIGHLIGHTS — hidden while actively searching or filtering by type, since showing
     unrelated curated brands (e.g. 4-wheeler brands while "2-Wheelers" is selected) reads as "the filter isn't doing anything" ════ -->
<?php if (!empty($featuredBrands) || !empty($trendingBrands)): ?>
<div class="max-w-6xl mx-auto px-4 sm:px-5 py-10" x-show="search === '' && filter === 'all'" x-cloak>
  <div class="grid gap-6 lg:grid-cols-2">

    <!-- Featured Section -->
    <?php if (!empty($featuredBrands)): ?>
    <div>
      <div class="flex items-center gap-2 mb-4">
        <span style="font-size:1.5rem">⭐</span>
        <h2 class="text-base sm:text-lg font-black" style="color:#0F172A">Featured</h2>
        <span class="text-xs font-bold px-2 py-1 rounded-full" style="background:rgba(0,230,118,.1);color:#00963C">Top Rated</span>
      </div>
      <div class="space-y-2">
        <?php foreach (array_slice($featuredBrands, 0, 3) as $idx => $brand):
          $slug = $brand['slug'] ?? strtolower(str_replace(' ', '-', $brand['name'] ?? ''));
          $name = $brand['name'] ?? '';
          $evCount = (int)($brand['ev_count'] ?? 0);
          $minPrice = (int)($brand['min_price'] ?? 0);
          $maxPrice = (int)($brand['max_price'] ?? 0);
          $firstLetter = strtoupper(substr($name, 0, 1));
          $hasLogo = !empty($brand['logo']);
        ?>
        <a href="<?= base_url('brands/' . esc($slug)) ?>"
           class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all hover:shadow-sm"
           style="border:1px solid rgba(0,230,118,.1)">
          <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,rgba(0,230,118,.18),rgba(0,230,118,.07));border:1.5px solid rgba(0,230,118,.22)">
            <?php if ($hasLogo): ?>
              <img src="<?= esc($brand['logo']) ?>" alt="<?= esc($name) ?>"
                   style="max-height:32px;max-width:32px;object-fit:contain"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
              <span class="font-bold text-base" style="display:none;color:#00963C"><?= esc($firstLetter) ?></span>
            <?php else: ?>
              <span class="font-bold text-base" style="color:#00963C"><?= esc($firstLetter) ?></span>
            <?php endif; ?>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-sm" style="color:#0F172A;margin:0"><?= esc($name) ?></p>
            <p class="text-xs" style="color:#5B6B80;margin:0">
              <?= $evCount ?> EV<?= $evCount !== 1 ? 's' : '' ?>
              <?php if ($minPrice > 0): ?>
                • ₹<?= number_format($minPrice / 100000, 1) ?>L
              <?php endif; ?>
            </p>
          </div>
          <svg class="flex-shrink-0 h-4 w-4" style="color:#00C060" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Trending Section -->
    <?php if (!empty($trendingBrands)): ?>
    <div>
      <div class="flex items-center gap-2 mb-4">
        <span style="font-size:1.5rem">🔥</span>
        <h2 class="text-base sm:text-lg font-black" style="color:#0F172A">Trending</h2>
        <span class="text-xs font-bold px-2 py-1 rounded-full" style="background:rgba(255,107,53,.1);color:#FF6B35">Popular Now</span>
      </div>
      <div class="space-y-2">
        <?php foreach (array_slice($trendingBrands, 0, 3) as $idx => $brand):
          $slug = $brand['slug'] ?? strtolower(str_replace(' ', '-', $brand['name'] ?? ''));
          $name = $brand['name'] ?? '';
          $evCount = (int)($brand['ev_count'] ?? 0);
          $minPrice = (int)($brand['min_price'] ?? 0);
          $maxPrice = (int)($brand['max_price'] ?? 0);
          $firstLetter = strtoupper(substr($name, 0, 1));
          $hasLogo = !empty($brand['logo']);
        ?>
        <a href="<?= base_url('brands/' . esc($slug)) ?>"
           class="flex items-center gap-3 p-3 rounded-lg hover:bg-orange-50 transition-all hover:shadow-sm"
           style="border:1px solid rgba(255,107,53,.1)">
          <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,rgba(255,107,53,.18),rgba(255,107,53,.07));border:1.5px solid rgba(255,107,53,.22)">
            <?php if ($hasLogo): ?>
              <img src="<?= esc($brand['logo']) ?>" alt="<?= esc($name) ?>"
                   style="max-height:32px;max-width:32px;object-fit:contain"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
              <span class="font-bold text-base" style="display:none;color:#FF6B35"><?= esc($firstLetter) ?></span>
            <?php else: ?>
              <span class="font-bold text-base" style="color:#FF6B35"><?= esc($firstLetter) ?></span>
            <?php endif; ?>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-sm" style="color:#0F172A;margin:0"><?= esc($name) ?></p>
            <p class="text-xs" style="color:#5B6B80;margin:0">
              <?= $evCount ?> EV<?= $evCount !== 1 ? 's' : '' ?>
              <?php if ($minPrice > 0): ?>
                • ₹<?= number_format($minPrice / 100000, 1) ?>L
              <?php endif; ?>
            </p>
          </div>
          <svg class="flex-shrink-0 h-4 w-4" style="color:#FF6B35" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>
<?php endif; ?>

<!-- ════ STICKY FILTER BAR ════ -->
<?php
// Count brands by filter type
$filterCounts = ['all' => 0, '2-wheeler' => 0, '3-wheeler' => 0, '4-wheeler' => 0, 'commercial' => 0];
$filterCounts['all'] = count($brands);
foreach ($brands as $b) {
  $types = explode(',', $b['filter_types'] ?? '');
  foreach ($types as $t) {
    $t = trim($t);
    if (isset($filterCounts[$t])) $filterCounts[$t]++;
  }
}
?>
<div class="sticky top-16 md:top-28" style="background:rgba(255,255,255,.93);border-bottom:1px solid rgba(0,230,118,.08);backdrop-filter:blur(14px);z-index:30">
  <div class="max-w-6xl mx-auto px-4 sm:px-5"
       style="padding-top:.625rem;padding-bottom:.625rem">

    <div class="flex flex-col sm:flex-row gap-2.5 sm:items-center">

      <!-- Active search indicator — search itself lives only in the hero box above -->
      <div x-show="search !== ''" x-cloak
           class="flex items-center gap-1.5 flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold"
           style="background:rgba(0,230,118,.08);border:1px solid rgba(0,230,118,.22);color:#00963C">
        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
        <span x-text="'“' + search + '”'"></span>
        <button type="button" @click="clearSearch()" class="ml-0.5" aria-label="Clear search">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Filter pills — desktop, horizontal scroll on mobile -->
      <div class="hidden sm:flex gap-2 overflow-x-auto" style="-webkit-overflow-scrolling:touch;scrollbar-width:none;flex:1">
        <?php foreach ([
          ['all',        'All'],
          ['2-wheeler',  '2-Wheelers'],
          ['3-wheeler',  '3-Wheelers'],
          ['4-wheeler',  '4-Wheelers'],
          ['commercial', 'Commercial'],
        ] as [$key, $label]): ?>
        <button @click="setFilter('<?= $key ?>')"
                :class="filter === '<?= $key ?>' ? 'fp-pill active' : 'fp-pill'"
                title="<?= $filterCounts[$key] ?> brands"
                style="flex-shrink:0">
          <?= esc($label) ?> <span style="opacity:.7;font-size:.7rem">(<?= $filterCounts[$key] ?>)</span>
        </button>
        <?php endforeach; ?>
      </div>

      <!-- Mobile filter dropdown -->
      <div class="sm:hidden w-full relative">
        <button @click="showMobileFilters = !showMobileFilters"
                class="w-full px-3 py-2 rounded-lg text-sm font-600 transition-all flex items-center justify-between"
                style="background:rgba(0,230,118,.08);border:1.5px solid rgba(0,230,118,.2);color:#0F172A">
          <span>Filter by Type</span>
          <svg class="h-4 w-4 transition-transform" :style="showMobileFilters ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div x-show="showMobileFilters" x-cloak
             class="absolute top-full mt-2 left-0 right-0 rounded-lg shadow-lg z-50"
             style="background:#fff;border:1px solid rgba(0,230,118,.15)">
          <?php foreach ([
            ['all',        'All Brands'],
            ['2-wheeler',  '2-Wheelers'],
            ['3-wheeler',  '3-Wheelers'],
            ['4-wheeler',  '4-Wheelers'],
            ['commercial', 'Commercial'],
          ] as [$key, $label]): ?>
          <button @click="setFilter('<?= $key ?>')"
                  :class="filter === '<?= $key ?>' ? 'w-full text-left px-4 py-3 text-sm font-600 border-l-2' : 'w-full text-left px-4 py-3 text-sm font-500 border-l-2'"
                  :style="filter === '<?= $key ?>' ? 'background:rgba(0,230,118,.08);border-color:#00C060;color:#0F172A' : 'border-color:transparent;color:#5B6B80'"
                  style="transition:all .15s ease">
            <?= esc($label) ?> <span style="opacity:.6;font-size:.7rem">(<?= $filterCounts[$key] ?>)</span>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ════ BRAND GRID ════ -->
<div class="max-w-6xl mx-auto px-4 sm:px-5 py-6 sm:py-10">

  <?php if (empty($brands)): ?>
  <div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
         style="background:rgba(0,230,118,.06);border:1px solid rgba(0,230,118,.15)">
      <svg class="w-8 h-8" style="color:#00C060;opacity:.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
      </svg>
    </div>
    <p class="font-semibold" style="color:#475569">No brands yet — check back soon.</p>
  </div>

  <?php else: ?>

  <!-- Grid: 2 col mobile → 3 col tablet → 3 col desktop (Tesla-style density) -->
  <div class="brand-grid grid gap-3 sm:gap-4 sm:grid-cols-3 lg:grid-cols-3 max-w-full">
    <?php foreach ($brands as $brand):
      $slug        = $brand['slug'] ?? strtolower(str_replace(' ', '-', $brand['name'] ?? ''));
      $name        = $brand['name'] ?? '';
      $evCount     = (int)($brand['ev_count'] ?? $brand['vehicle_count'] ?? 0);
      $firstLetter = strtoupper(substr($name, 0, 1));
      $filterStr   = $brand['filter_types'] ?? 'all';
      $hasLogo     = !empty($brand['logo']);
    ?>
    <a href="<?= base_url('brands/' . esc($slug)) ?>"
         x-show="
           (filter === 'all' || '<?= esc($filterStr) ?>'.split(',').includes(filter))
           && (search === '' || '<?= esc(addslashes(strtolower($name))) ?>'.includes(search.toLowerCase()))
         "
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="bc">

      <!-- Logo zone -->
      <div class="bc-logo">
        <?php if ($hasLogo): ?>
          <img src="<?= esc($brand['logo']) ?>"
               alt="<?= esc($name) ?>"
               style="max-height:52px;max-width:80px;object-fit:contain"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
          <div class="bc-letter" style="display:none"><?= esc($firstLetter) ?></div>
        <?php else: ?>
          <div class="bc-letter"><?= esc($firstLetter) ?></div>
        <?php endif; ?>
      </div>

      <!-- Body -->
      <div class="bc-body">
        <p class="font-bold leading-snug" style="font-size:.8125rem;color:#0F172A"><?= esc($name) ?></p>

        <?php if ($evCount > 0): ?>
        <span class="inline-flex items-center gap-1 mt-1.5"
              style="font-size:.625rem;font-weight:700;padding:.2rem .6rem;border-radius:9999px;background:rgba(0,230,118,.09);color:#00963C;border:1px solid rgba(0,230,118,.2)">
          <svg class="fill-current" style="width:.6rem;height:.6rem" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
          <?= $evCount ?> <?= $evCount === 1 ? 'EV' : 'EVs' ?>
        </span>
        <?php endif; ?>

        <?php if (!empty($brand['min_price']) && !empty($brand['max_price'])): ?>
        <div style="font-size:.75rem;color:#5B6B80;margin-top:.75rem;font-weight:500">
          ₹<?= number_format($brand['min_price'] / 100000, 1) ?>L - ₹<?= number_format($brand['max_price'] / 100000, 1) ?>L
        </div>
        <?php endif; ?>

        <span class="bc-btn">
          View EVs
          <svg style="width:.65rem;height:.65rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
          </svg>
        </span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Initially show all (x-cloak hides until Alpine runs) -->
  <noscript>
    <style>.bc { display: flex !important; }</style>
  </noscript>

  <!-- No results — covers search-only, filter-only, and combined search+filter yielding zero matches -->
  <div x-show="visibleCount === 0" x-cloak class="text-center text-sm mt-10 py-10" style="color:#5B6B80">
    <template x-if="search !== ''">
      <p>
        No brands match "<span x-text="search" class="font-semibold" style="color:#00C060"></span>"<span x-show="filter !== 'all'"> in this category</span>.
        <button @click="search='';setFilter('all')" class="font-semibold ml-1 underline" style="color:#00C060">Clear filters</button>
      </p>
    </template>
    <template x-if="search === ''">
      <p>
        No brands in this category yet.
        <button @click="setFilter('all')" class="font-semibold ml-1 underline" style="color:#00C060">Show all brands</button>
      </p>
    </template>
  </div>

  <?php endif; ?>
</div>

<!-- ════ BOTTOM CTA ════ -->
<section class="py-10 sm:py-[3.25rem]"
         style="background:linear-gradient(160deg,#ECFFF1 0%,#F1FFF5 50%,#F3FFF6 100%);border-top:1px solid rgba(0,230,118,.12)">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <div class="w-11 h-11 rounded-2xl flex items-center justify-center mx-auto mb-5"
         style="background:rgba(0,230,118,.12);border:1px solid rgba(0,230,118,.25)">
      <svg class="w-5 h-5 fill-current" style="color:#00C060" viewBox="0 0 24 24">
        <path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/>
      </svg>
    </div>
    <h2 class="font-black mb-3" style="font-size:clamp(1.25rem,4vw,1.75rem);color:#0F172A">Not sure which brand to pick?</h2>
    <p class="mb-7 max-w-sm mx-auto text-sm sm:text-base" style="color:#475569">
      Answer 3 quick questions — get a personalised EV shortlist matched to your budget and city.
    </p>
    <a href="<?= base_url('find-my-ev') ?>"
       class="inline-flex items-center gap-2 font-bold rounded-full transition-all duration-[250ms]"
       style="padding:.875rem 2rem;background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 6px 20px rgba(0,230,118,.3);font-size:.9375rem"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(0,230,118,.4)';this.style.filter='brightness(1.06)'"
       onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px rgba(0,230,118,.3)';this.style.filter=''">
      Find My Perfect EV
      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
      </svg>
    </a>
  </div>
</section>


<div class="pb-4 md:pb-0"></div>
</div>
<?= $this->endSection() ?>

