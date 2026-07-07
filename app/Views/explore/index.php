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
  background: linear-gradient(160deg,#F0FFF4 0%,#E6FFED 40%,#F5FFF7 70%,#EEFFF3 100%);
  background-size: 300% 300%;
  animation: gradShift 14s ease infinite;
}

/* ── brand card ── */
.bc {
  background: #fff;
  border: 1.5px solid rgba(0,230,118,.12);
  border-radius: 1.125rem;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
  box-shadow: 0 1px 5px rgba(0,0,0,.04);
  text-decoration: none;
  cursor: pointer;
}
.bc:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 28px rgba(0,230,118,.13), 0 2px 8px rgba(0,0,0,.05);
  border-color: rgba(0,230,118,.35);
}

/* ── logo zone ── */
.bc-logo {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(145deg,#F0FFF4,#E6FFED);
  border-bottom: 1px solid rgba(0,230,118,.08);
  padding: 1.25rem 0;
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
  padding: .875rem 1rem 1rem;
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
  transition: all .18s ease;
  white-space: nowrap;
  -webkit-tap-highlight-color: transparent;
}
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
  transition: box-shadow .15s ease, transform .15s ease;
}
.bc-btn:hover { box-shadow: 0 4px 14px rgba(0,230,118,.4); transform: translateY(-1px); }

/* ── grid: prevent last odd card from stretching on 2-col ── */
@media (max-width: 639px) {
  .brand-grid { grid-template-columns: repeat(2, 1fr); }
  .brand-grid .bc:last-child:nth-child(odd) { grid-column: 1 / 2; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="background:#F5FFF7;min-height:100vh"
     x-data="{
       filter: 'all',
       search: '',
       setFilter(f) { this.filter = f; }
     }">

<!-- ════ HERO ════ -->
<section class="explore-grad relative overflow-hidden px-4 text-center"
         style="padding-top:clamp(5rem,14vw,8rem);padding-bottom:clamp(2rem,6vw,3.5rem);border-bottom:1px solid rgba(0,230,118,.1)">

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
    <p style="color:#475569;font-size:clamp(.875rem,3vw,1.125rem)">
      From homegrown innovators to global giants — find, compare, choose.
    </p>
  </div>
</section>

<!-- ════ STICKY FILTER BAR ════ -->
<div style="background:rgba(255,255,255,.93);border-bottom:1px solid rgba(0,230,118,.08);backdrop-filter:blur(14px);position:sticky;top:64px;z-index:30">
  <div class="max-w-6xl mx-auto px-4 sm:px-5"
       style="padding-top:.625rem;padding-bottom:.625rem">

    <div class="flex flex-col sm:flex-row gap-2.5 sm:items-center">

      <!-- Search — full width on mobile -->
      <div class="relative w-full sm:w-56 flex-shrink-0">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 pointer-events-none" style="color:#94A3B8"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        <input x-model="search" type="text" placeholder="Search brands…"
               class="w-full pl-9 pr-4 py-2 rounded-xl text-sm focus:outline-none"
               style="background:#FAFFFE;border:1.5px solid rgba(0,230,118,.2);color:#0F172A"
               onfocus="this.style.borderColor='#00E676';this.style.boxShadow='0 0 0 3px rgba(0,230,118,.1)'"
               onblur="this.style.borderColor='rgba(0,230,118,.2)';this.style.boxShadow='none'">
      </div>

      <!-- Filter pills — horizontal scroll on mobile -->
      <div class="flex gap-2 overflow-x-auto" style="-webkit-overflow-scrolling:touch;scrollbar-width:none">
        <?php foreach ([
          ['all',        'All Brands'],
          ['2-wheeler',  '2 Wheelers'],
          ['3-wheeler',  '3 Wheelers'],
          ['4-wheeler',  '4 Wheelers'],
          ['commercial', 'Commercial'],
        ] as [$key, $label]): ?>
        <button @click="setFilter('<?= $key ?>')"
                :class="filter === '<?= $key ?>' ? 'fp-pill active' : 'fp-pill'">
          <?= esc($label) ?>
        </button>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>

<!-- ════ BRAND GRID ════ -->
<div class="max-w-6xl mx-auto px-4 sm:px-5 py-8 sm:py-10">

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

  <!-- Grid: 2 col mobile → 3 col tablet → 4 col desktop -->
  <div class="brand-grid grid gap-3 sm:gap-4 sm:grid-cols-3 lg:grid-cols-4 max-w-full">
    <?php foreach ($brands as $brand):
      $slug        = $brand['slug'] ?? strtolower(str_replace(' ', '-', $brand['name'] ?? ''));
      $name        = $brand['name'] ?? '';
      $evCount     = (int)($brand['ev_count'] ?? $brand['vehicle_count'] ?? 0);
      $firstLetter = strtoupper(substr($name, 0, 1));
      $filterStr   = $brand['filter_types'] ?? 'all';
      $hasLogo     = !empty($brand['logo_url']);
    ?>
    <a href="<?= base_url('brands/' . esc($slug)) ?>"
         x-show="
           (filter === 'all' || '<?= esc($filterStr) ?>'.split(',').includes(filter))
           && (search === '' || '<?= esc(addslashes(strtolower($name))) ?>'.includes(search.toLowerCase()))
         "
         x-cloak
         class="bc">

      <!-- Logo zone -->
      <div class="bc-logo">
        <?php if ($hasLogo): ?>
          <img src="<?= esc($brand['logo_url']) ?>"
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

  <!-- No search results -->
  <p x-show="search !== '' && document.querySelectorAll('.bc[style*=\'display: none\']').length === document.querySelectorAll('.bc').length"
     x-cloak
     class="text-center text-sm mt-10" style="color:#64748B">
    No brands match "<span x-text="search" class="font-semibold" style="color:#00C060"></span>".
    <button @click="search=''" class="font-semibold ml-1 underline" style="color:#00C060">Clear</button>
  </p>

  <?php endif; ?>
</div>

<!-- ════ BOTTOM CTA ════ -->
<section class="py-12 sm:py-14"
         style="background:linear-gradient(160deg,#E6FFED 0%,#EEFFF3 50%,#F0FFF4 100%);border-top:1px solid rgba(0,230,118,.12)">
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
       class="inline-flex items-center gap-2 font-bold rounded-full transition-all duration-200"
       style="padding:.875rem 2rem;background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 6px 20px rgba(0,230,118,.3);font-size:.9375rem"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(0,230,118,.4)'"
       onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px rgba(0,230,118,.3)'">
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

