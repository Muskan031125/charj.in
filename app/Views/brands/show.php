<?php
$brandName  = $brand['name']              ?? 'Brand';
$brandSlug  = $brand['slug']              ?? '';
$country    = $brand['country_of_origin'] ?? 'India';
$desc       = $brand['description']       ?? '';
$website    = $brand['website_url']       ?? '';
$totalCount = (int) ($brand['vehicle_count'] ?? count($vehicles ?? []));

$meta_title       = $meta_title       ?? $brandName . ' Electric Vehicles in India — Charj.in';
$meta_description = $meta_description ?? 'Explore all ' . $brandName . ' electric vehicles available in India. Compare prices, range and features on Charj.in.';
?>
<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div style="background:#F5FFF7;min-height:100vh">

<!-- BREADCRUMB -->
<nav style="background:#FFFFFF;border-bottom:1px solid rgba(0,230,118,.08)" aria-label="Breadcrumb">
  <div class="hero-sm mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 pt-[72px] md:pt-32">
    <ol class="flex items-center gap-1.5 text-sm flex-wrap" style="color:#64748B">
      <li><a href="<?= base_url() ?>" class="transition-colors hover:text-[#00C060]">Home</a></li>
      <li><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></li>
      <li><a href="<?= base_url('brands') ?>" class="transition-colors hover:text-[#00C060]">Brands</a></li>
      <li><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></li>
      <li class="font-semibold" style="color:#0F172A"><?= esc($brandName) ?></li>
    </ol>
  </div>
</nav>

<!-- BRAND HERO -->
<section style="background:#FFFFFF;border-bottom:1px solid rgba(0,230,118,.08)">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 md:py-14">
    <div class="flex flex-col items-start gap-4 md:gap-8 md:flex-row md:items-center">

      <!-- Logo -->
      <div class="flex-shrink-0 flex h-28 w-28 md:h-36 md:w-36 items-center justify-center rounded-3xl shadow-sm"
           style="background:linear-gradient(145deg,#F0FFF4,#E6FFED);border:1.5px solid rgba(0,230,118,.18)">
        <?php
          $rawLogo = $brand['logo'] ?? $brand['logo_url'] ?? '';
          $logoUrl = $rawLogo ? (preg_match('#^https?://#', $rawLogo) ? $rawLogo : base_url('assets/images/' . ltrim($rawLogo, '/'))) : '';
        ?>
        <?php if (!empty($logoUrl)): ?>
          <img src="<?= esc($logoUrl) ?>" alt="<?= esc($brandName) ?> logo"
               class="h-20 w-20 md:h-24 md:w-24 object-contain" loading="eager"
               onerror="this.style.display='none'">
        <?php else: ?>
          <span class="text-5xl font-black" style="color:#00C060"><?= esc(strtoupper(substr($brandName, 0, 1))) ?></span>
        <?php endif; ?>
      </div>

      <!-- Info -->
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-3 mb-3">
          <h1 class="text-3xl font-black md:text-4xl" style="color:#0F172A"><?= esc($brandName) ?></h1>
          <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold"
                style="background:rgba(0,230,118,.08);color:#00C060;border:1px solid rgba(0,230,118,.22)">
            <?= esc($country) ?>
          </span>
        </div>

        <?php if ($desc): ?>
          <p class="leading-relaxed max-w-2xl text-base mb-5" style="color:#475569"><?= esc($desc) ?></p>
        <?php endif; ?>

        <div class="flex flex-wrap items-center gap-5">
          <div class="flex items-center gap-2 text-sm font-semibold" style="color:#334155">
            <svg class="h-5 w-5 flex-shrink-0" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span><strong style="color:#0F172A"><?= $totalCount ?></strong> Electric Vehicle<?= $totalCount !== 1 ? 's' : '' ?></span>
          </div>
          <?php if ($website): ?>
            <a href="<?= esc($website) ?>" target="_blank" rel="noopener noreferrer"
               class="flex items-center gap-1.5 text-sm font-semibold transition-colors" style="color:#00C060">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
              Official Website
            </a>
          <?php endif; ?>
          <a href="<?= base_url('compare?brand='.esc($brandSlug)) ?>"
             class="flex items-center gap-1.5 text-sm font-semibold transition-colors" style="color:#64748B">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Compare brands
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MAIN CONTENT -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 md:py-14">
  <div class="flex flex-col gap-6 lg:flex-row lg:gap-10">

    <!-- Vehicles Grid -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-black md:text-2xl" style="color:#0F172A">
            <?= esc($brandName) ?> Electric Vehicles
          </h2>
          <p class="text-sm mt-0.5" style="color:#64748B"><?= $totalCount ?> model<?= $totalCount !== 1 ? 's' : '' ?> listed</p>
        </div>
        <a href="<?= base_url('vehicles?brand='.esc($brandSlug)) ?>"
           class="text-sm font-bold transition-colors" style="color:#00C060">View all →</a>
      </div>

      <?php if (empty($vehicles)): ?>
        <div class="rounded-2xl py-16 text-center" style="background:#FFFFFF;border:1px solid rgba(0,230,118,.1)">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
               style="background:rgba(0,230,118,.06);border:1px solid rgba(0,230,118,.15)">
            <svg class="h-8 w-8" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <p class="font-semibold" style="color:#475569">No vehicles listed yet for <?= esc($brandName) ?>. Check back soon.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 gap-4 sm:gap-5 sm:grid-cols-2 xl:grid-cols-3">
          <?php foreach ($vehicles as $vehicle): ?>
            <?= view('partials/vehicle_card', ['vehicle' => $vehicle]) ?>
          <?php endforeach; ?>
        </div>

        <?php if (!empty($pager)): ?>
          <div class="mt-10 flex justify-center">
            <?= $pager->links() ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <aside class="w-full lg:w-80 lg:shrink-0">
      <div class="sticky space-y-5" style="top:88px">

        <!-- Lead Form -->
        <div class="rounded-2xl overflow-hidden" style="background:#FFFFFF;border:1px solid rgba(0,230,118,.12);box-shadow:0 2px 8px rgba(0,0,0,.05)">
          <div class="px-5 py-4" style="background:linear-gradient(135deg,#00C060,#009944)">
            <h3 class="text-base font-bold text-white">Interested in <?= esc($brandName) ?> EVs?</h3>
            <p class="mt-1 text-sm" style="color:rgba(255,255,255,.8)">Get price, dealer info and expert guidance — free.</p>
          </div>
          <div class="p-5">
            <form action="<?= base_url('leads/store') ?>" method="post" class="space-y-4">
              <?= csrf_field() ?>
              <input type="hidden" name="source" value="brand_page">
              <input type="hidden" name="brand_name" value="<?= esc($brandName) ?>">

              <div>
                <label for="sidebar_phone" class="block text-xs font-semibold mb-1" style="color:#475569">Phone Number *</label>
                <div class="flex">
                  <span class="inline-flex items-center rounded-l-xl px-3 text-sm font-medium"
                        style="background:#F0FDFA;border:1.5px solid rgba(0,230,118,.18);border-right:0;color:#64748B">+91</span>
                  <input type="tel" id="sidebar_phone" name="phone" required placeholder="98765 43210"
                         maxlength="10" minlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric"
                         class="flex-1 rounded-r-xl px-4 py-2.5 text-sm"
                         style="border:1.5px solid rgba(0,230,118,.18);color:#0F172A;background:#FAFFFE;outline:none"
                         oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
                         onfocus="this.style.borderColor='#00C060';this.style.boxShadow='0 0 0 3px rgba(0,230,118,.1)'"
                         onblur="this.style.borderColor='rgba(0,230,118,.18)';this.style.boxShadow='none'">
                </div>
              </div>

              <div>
                <label for="sidebar_city" class="block text-xs font-semibold mb-1" style="color:#475569">Your City</label>
                <input type="text" id="sidebar_city" name="city" placeholder="e.g. Mumbai"
                       class="w-full rounded-xl px-4 py-2.5 text-sm"
                       style="border:1.5px solid rgba(0,230,118,.18);color:#0F172A;background:#FAFFFE;outline:none"
                       onfocus="this.style.borderColor='#00C060';this.style.boxShadow='0 0 0 3px rgba(0,230,118,.1)'"
                       onblur="this.style.borderColor='rgba(0,230,118,.18)';this.style.boxShadow='none'">
              </div>

              <div>
                <label for="sidebar_interest" class="block text-xs font-semibold mb-1" style="color:#475569">I'm interested in</label>
                <select id="sidebar_interest" name="interest"
                        class="w-full rounded-xl px-4 py-2.5 text-sm appearance-none"
                        style="border:1.5px solid rgba(0,230,118,.18);color:#0F172A;background:#FAFFFE;outline:none">
                  <option value="">Select vehicle type</option>
                  <option value="car">Electric Car</option>
                  <option value="scooter">Electric Scooter</option>
                  <option value="bike">Electric Bike</option>
                  <option value="any">Any / Not sure yet</option>
                </select>
              </div>

              <button type="submit"
                      class="w-full rounded-xl py-3 text-sm font-bold text-white transition-all duration-200"
                      style="background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 4px 14px rgba(0,230,118,.3)"
                      onmouseover="this.style.boxShadow='0 6px 20px rgba(0,230,118,.4)';this.style.transform='translateY(-1px)'"
                      onmouseout="this.style.boxShadow='0 4px 14px rgba(0,230,118,.3)';this.style.transform=''">
                Get Free Dealer Info
              </button>
              <p class="text-center text-xs" style="color:#94A3B8">No spam · 100% free · Your data is safe</p>
            </form>
          </div>
        </div>

        <!-- Quick links -->
        <div class="rounded-2xl p-5" style="background:#FFFFFF;border:1px solid rgba(0,230,118,.1)">
          <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#94A3B8">Quick Links</h4>
          <ul class="space-y-1">
            <?php foreach ([
              ['All '.esc($brandName).' EVs', base_url('vehicles?brand='.esc($brandSlug)), 'M9 5l7 7-7 7'],
              ['Compare with other brands',   base_url('compare?brand='.esc($brandSlug)),  'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
              ['Find '.esc($brandName).' Dealers', base_url('dealers?brand='.esc($brandSlug)), 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
            ] as [$label, $href, $icon]): ?>
            <li>
              <a href="<?= $href ?>"
                 class="flex items-center gap-2.5 py-2 px-3 rounded-xl text-sm font-medium transition-all duration-150"
                 style="color:#475569"
                 onmouseover="this.style.background='rgba(0,230,118,.06)';this.style.color='#00C060'"
                 onmouseout="this.style.background='';this.style.color='#475569'">
                <svg class="h-4 w-4 flex-shrink-0" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="<?= $icon ?>"/>
                </svg>
                <?= $label ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>
    </aside>

  </div>
</div>

<!-- BOTTOM CTA BANNER -->
<section class="py-14" style="background:linear-gradient(160deg,#E6FFED 0%,#EEFFF3 50%,#F0FFF4 100%);border-top:1px solid rgba(0,230,118,.12)">
  <div class="mx-auto max-w-4xl px-4 text-center">
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-6"
         style="background:rgba(0,230,118,.1);border:1px solid rgba(0,230,118,.25)">
      <svg class="w-7 h-7 fill-current" style="color:#00C060" viewBox="0 0 24 24">
        <path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/>
      </svg>
    </div>
    <h2 class="text-2xl font-black md:text-3xl mb-3" style="color:#0F172A">
      Ready to go electric with <?= esc($brandName) ?>?
    </h2>
    <p class="mb-8 max-w-lg mx-auto" style="color:#475569">
      Get the latest price, nearest dealer contacts, and expert buying guidance — completely free.
    </p>
    <div class="flex flex-wrap justify-center gap-4">
      <a href="#sidebar_phone"
         class="inline-flex items-center gap-2 font-bold px-7 py-3.5 rounded-full transition-all duration-200"
         style="background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 6px 20px rgba(0,230,118,.3)"
         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(0,230,118,.4)'"
         onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px rgba(0,230,118,.3)'">
        Get Price &amp; Dealer Info
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
        </svg>
      </a>
      <a href="<?= base_url('compare') ?>"
         class="inline-flex items-center gap-2 font-semibold px-7 py-3.5 rounded-full transition-all duration-200"
         style="border:1.5px solid rgba(0,230,118,.35);color:#00C060;background:rgba(255,255,255,.7)"
         onmouseover="this.style.background='rgba(0,230,118,.08)';this.style.borderColor='#00E676'"
         onmouseout="this.style.background='rgba(255,255,255,.7)';this.style.borderColor='rgba(0,230,118,.35)'">
        Compare EVs
      </a>
    </div>
  </div>
</section>


<div class="pb-4 md:pb-0"></div>
</div>
<?= $this->endSection() ?>
