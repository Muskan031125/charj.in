<?php
$meta_title       = $meta_title       ?? 'Electric Vehicle Brands in India — Charj.in';
$meta_description = $meta_description ?? 'Explore all electric vehicle brands available in India. Find EVs by manufacturer — cars, scooters, bikes and more.';
?>
<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div style="background:#FAFFFE">

<!-- HERO -->
<section style="background:linear-gradient(160deg,#F0FFF4 0%,#FFFFFF 50%,#EEFFF3 100%);border-bottom:1px solid rgba(0,168,150,.12)">
  <div class="hero-sm mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24 md:pt-32 pb-8 sm:pb-10 text-center">

    <!-- Breadcrumb -->
    <nav class="flex items-center justify-center gap-1.5 text-xs font-semibold mb-4" style="color:#94A3B8" aria-label="Breadcrumb">
      <a href="<?= base_url('/') ?>" class="transition-colors font-bold" style="color:#94A3B8" onmouseover="this.style.color='#00C060'" onmouseout="this.style.color='#94A3B8'">Home</a>
      <span aria-hidden="true" style="color:#CBD5E1">›</span>
      <span class="font-black" style="color:#00C060" aria-current="page">Brands</span>
    </nav>

    <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 mb-6"
         style="background:rgba(0,230,118,.08);border:1.5px solid rgba(0,230,118,.22)">
      <span class="w-1.5 h-1.5 rounded-full" style="background:#00C060"></span>
      <span class="text-xs font-bold uppercase tracking-wider" style="color:#00963C">India's EV Marketplace</span>
    </div>

    <h1 class="text-3xl font-black sm:text-4xl lg:text-5xl leading-tight mb-4" style="color:#0F172A">
      EV Brands in India
    </h1>
    <p class="text-base sm:text-lg max-w-2xl mx-auto mb-6" style="color:#475569">
      From homegrown innovators to global giants — explore every brand bringing electric mobility to Indian roads.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-6">
      <?php foreach ([
        [count($brands ?? []).'+ Brands', 'All major EV manufacturers'],
        ['Cars, Scooters & Bikes',         'Every EV category covered'],
        ['Indian & Global',                'Domestic & international brands'],
      ] as [$val, $sub]): ?>
      <div class="flex items-center gap-2 text-sm font-medium" style="color:#64748B">
        <svg class="h-4 w-4 flex-shrink-0" style="color:#00C060" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span><strong style="color:#0F172A"><?= $val ?></strong> — <?= $sub ?></span>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- BRANDS GRID -->
<section class="py-8 md:py-12">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    <?php if (empty($brands)): ?>
      <div class="text-center py-20">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
             style="background:rgba(0,230,118,.06);border:1px solid rgba(0,230,118,.15)">
          <svg class="h-8 w-8" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <p class="font-semibold" style="color:#475569">No brands found yet. Check back soon.</p>
      </div>
    <?php else: ?>

    <div class="grid grid-cols-2 gap-3 sm:gap-5 sm:grid-cols-3 lg:grid-cols-3">
      <?php foreach ($brands as $brand):
        if (($brand['status'] ?? '') !== 'published') continue;
        $initial     = strtoupper(substr($brand['name'] ?? 'B', 0, 1));
        $slug        = $brand['slug'] ?? '';
        $name        = $brand['name'] ?? '';
        $country     = $brand['country_of_origin'] ?? 'India';
        $description = $brand['description'] ?? '';
        $excerpt     = mb_strlen($description) > 80 ? mb_substr($description, 0, 80) . '…' : $description;
        $count       = (int) ($brand['vehicle_count'] ?? 0);
        $rawLogo     = $brand['logo'] ?? $brand['logo_url'] ?? '';
        $logoUrl     = $rawLogo ? (preg_match('#^https?://#', $rawLogo) ? $rawLogo : base_url('assets/images/' . ltrim($rawLogo, '/'))) : '';
      ?>
      <a href="<?= base_url('brands/'.esc($slug)) ?>"
               class="group relative flex flex-col overflow-hidden rounded-2xl transition-all duration-250 brand-dir-card"
               style="background:#FFFFFF;border:1.5px solid rgba(0,230,118,.1);box-shadow:0 1px 5px rgba(0,0,0,.04);text-decoration:none;cursor:pointer"
               onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 28px rgba(0,230,118,.13),0 2px 8px rgba(0,0,0,.05)';this.style.borderColor='rgba(0,230,118,.32)'"
               onmouseleave="this.style.transform='';this.style.boxShadow='0 1px 5px rgba(0,0,0,.04)';this.style.borderColor='rgba(0,230,118,.1)'">

        <!-- Top accent -->
        <div class="h-1 w-full" style="background:linear-gradient(90deg,#00E676,#69FF97)"></div>

        <!-- Logo area -->
        <div class="flex items-center justify-center h-28 sm:h-32"
             style="background:linear-gradient(145deg,#F0FFF4,#E6FFED)">
          <?php if (!empty($logoUrl)): ?>
            <img src="<?= esc($logoUrl) ?>" alt="<?= esc($name) ?> logo"
                 class="h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-110" loading="eager" decoding="async"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="hidden h-14 w-14 items-center justify-center rounded-2xl"
                 style="background:linear-gradient(135deg,rgba(0,230,118,.15),rgba(0,230,118,.06));border:1.5px solid rgba(0,230,118,.25)">
              <span class="text-3xl font-black" style="color:#00963C"><?= esc($initial) ?></span>
            </div>
          <?php else: ?>
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:scale-110"
                 style="background:linear-gradient(135deg,rgba(0,230,118,.15),rgba(0,230,118,.06));border:1.5px solid rgba(0,230,118,.25)">
              <span class="text-3xl font-black" style="color:#00963C"><?= esc($initial) ?></span>
            </div>
          <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="flex flex-1 flex-col p-4 sm:p-5">
          <div class="flex items-start justify-between gap-2 mb-1">
            <h2 class="text-sm font-black leading-tight" style="color:#0F172A"><?= esc($name) ?></h2>
            <?php if ($count > 0): ?>
              <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold"
                    style="background:rgba(0,230,118,.09);color:#00963C;border:1px solid rgba(0,230,118,.2)">
                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
                <?= $count ?> EV<?= $count !== 1 ? 's' : '' ?>
              </span>
            <?php endif; ?>
          </div>

          <p class="text-[10px] font-semibold uppercase tracking-wide mb-2" style="color:#94A3B8"><?= esc($country) ?></p>

          <?php if ($excerpt): ?>
            <p class="text-xs leading-relaxed flex-1 mb-3 line-clamp-2" style="color:#64748B"><?= esc($excerpt) ?></p>
          <?php else: ?>
            <div class="flex-1 mb-3"></div>
          <?php endif; ?>

          <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-lg transition-all duration-150"
             style="color:#022C22;background:linear-gradient(135deg,#00E676,#69FF97);box-shadow:0 2px 8px rgba(0,230,118,.25)"
             onmouseover="this.style.boxShadow='0 4px 14px rgba(0,230,118,.4)'"
             onmouseout="this.style.boxShadow='0 2px 8px rgba(0,230,118,.25)'">
            View EVs
            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </span>
        </div>

      </a>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>
  </div>
</section>

<!-- BOTTOM CTA — compact teal strip -->
<section class="py-8" style="background:linear-gradient(135deg,#00A896,#007A6E);border-top:1px solid rgba(0,168,150,.2)">
  <div class="mx-auto max-w-4xl px-4">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.35)">
          <svg class="w-5 h-5 fill-current" style="color:#fff" viewBox="0 0 24 24">
            <path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/>
          </svg>
        </div>
        <div>
          <div class="text-white font-black text-base">Can't decide which brand?</div>
          <div class="text-xs mt-0.5" style="color:rgba(255,255,255,.5)">Answer 3 questions — get a personalised EV shortlist</div>
        </div>
      </div>
      <a href="<?= base_url('find-my-ev') ?>"
         class="inline-flex items-center gap-2 font-bold px-6 py-2.5 rounded-full text-sm transition-all duration-200 flex-shrink-0"
         style="background:#fff;color:#007A6E;box-shadow:0 4px 14px rgba(0,0,0,.15)"
         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 22px rgba(0,0,0,.2)'"
         onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(0,0,0,.15)'">
        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
        Find My EV
      </a>
    </div>
  </div>
</section>
<div class="pb-4 md:pb-0"></div>
</div>
<?= $this->endSection() ?>

