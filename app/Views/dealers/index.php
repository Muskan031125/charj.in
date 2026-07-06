<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
$activeCityFilter = $_GET['city'] ?? 'all';
$topCities = ['All', 'Delhi', 'Mumbai', 'Bangalore', 'Pune', 'Hyderabad', 'Chennai', 'Ahmedabad', 'Kolkata', 'Jaipur'];
// Merge with any extra cities from $cities passed by controller
$extraCities = array_diff(array_map('ucfirst', $cities ?? []), array_map('strtolower', $topCities));
$allCityTabs = array_merge($topCities, array_values($extraCities));
?>

<!-- Hero -->
<div style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)" class="hero-sm relative overflow-hidden pt-28 pb-10 px-4">
  <div class="absolute top-0 left-1/2 w-96 h-40 opacity-5 blur-3xl rounded-full pointer-events-none -translate-x-1/2" style="background:#00A896"></div>
  <div class="relative max-w-7xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-4" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.3)">
      🏪 Authorised Dealers Network
    </div>
    <h1 class="text-4xl lg:text-5xl font-black leading-tight" style="color:#0F172A">EV Dealers in India</h1>
    <p class="mt-3 max-w-2xl mx-auto text-base" style="color:#475569">
      Find authorised EV dealers near you. Browse by city, compare brands handled, and send a direct enquiry.
    </p>
  </div>
</div>

<!-- City filter tabs -->
<div class="sticky top-[57px] z-10 shadow-sm" style="background:rgba(255,255,255,.95);border-bottom:1px solid rgba(0,168,150,.12);backdrop-filter:blur(12px)">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex gap-1 overflow-x-auto py-3 scrollbar-hide" style="-ms-overflow-style:none;scrollbar-width:none;-webkit-overflow-scrolling:touch">
            <?php foreach ($allCityTabs as $city): ?>
            <?php
                $slug  = strtolower($city) === 'all' ? 'all' : strtolower($city);
                $active = $activeCityFilter === $slug;
            ?>
            <a href="?city=<?= urlencode($slug) ?>"
               class="shrink-0 rounded-full px-4 py-1.5 text-sm font-semibold transition
                      <?= $active ? 'text-white' : 'hover:text-[#00A896]' ?>"
               style="<?= !$active ? 'background:#F7FFFE;border:1px solid rgba(0,168,150,.18);color:#475569' : 'background:#00A896' ?>">
                <?= esc($city) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Results count -->
<div class="mx-auto max-w-7xl px-4 pt-6 pb-2">
    <p class="text-sm" style="color:#64748B">
        <?= number_format(count($dealers ?? [])) ?> dealer<?= count($dealers ?? []) != 1 ? 's' : '' ?>
        <?= $activeCityFilter !== 'all' ? 'in ' . ucfirst($activeCityFilter) : 'across India' ?>
    </p>
</div>

<!-- Dealer cards grid -->
<div class="mx-auto max-w-7xl px-4 pb-20 md:pb-16">
    <?php if (!empty($dealers)): ?>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($dealers as $dealer): ?>
        <div class="flex flex-col rounded-2xl p-5 transition card-hover" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14)">

            <!-- Header -->
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold" style="color:#0F172A"><?= esc($dealer['name']) ?></h2>
                        <?php if (!empty($dealer['is_verified'])): ?>
                            <span title="Verified Dealer"
                                  class="flex h-5 w-5 items-center justify-center rounded-full text-white" style="background:#00A896"
                                  aria-label="Verified">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="mt-0.5 text-xs" style="color:#64748B"><?= esc($dealer['city']) ?>, <?= esc($dealer['state']) ?></p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-black" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.25)">
                    <?= mb_strtoupper(mb_substr($dealer['name'], 0, 2)) ?>
                </div>
            </div>

            <!-- Address -->
            <p class="mt-3 text-xs leading-relaxed" style="color:#475569">
                <svg class="mr-1 inline h-3.5 w-3.5" style="color:#94A3B8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <?= esc($dealer['address']) ?>
            </p>

            <!-- Phone -->
            <?php if (!empty($dealer['phone'])): ?>
            <a href="tel:<?= esc($dealer['phone']) ?>"
               class="mt-2 flex items-center gap-1.5 text-sm font-semibold transition-colors" style="color:#00A896">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <?= esc($dealer['phone']) ?>
            </a>
            <?php endif; ?>

            <!-- Brands handled -->
            <?php if (!empty($dealer['brands'])): ?>
            <div class="mt-3 flex flex-wrap gap-1.5">
                <?php foreach ((array)$dealer['brands'] as $brand): ?>
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold" style="background:#F0FFF9;color:#475569;border:1px solid rgba(0,168,150,.14)">
                        <?= esc($brand) ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Spacer -->
            <div class="flex-1"></div>

            <!-- Action buttons -->
            <div class="mt-4 flex gap-2">
                <a href="/dealers/<?= esc($dealer['slug'] ?? $dealer['id']) ?>"
                   class="flex-1 rounded-xl px-4 py-2.5 text-center text-sm font-semibold transition min-h-[44px] flex items-center justify-center" style="border:1px solid rgba(0,168,150,.25);color:#00A896" onmouseenter="this.style.borderColor='rgba(0,168,150,.55)';this.style.background='#F0FFF9'" onmouseleave="this.style.borderColor='rgba(0,168,150,.25)';this.style.background='transparent'">
                    View Dealer
                </a>
                <a href="#lead-form"
                   onclick="document.querySelector('[name=city]').value='<?= esc($dealer['city']) ?>'"
                   class="flex-1 rounded-xl px-4 py-2.5 text-center text-sm font-semibold text-white transition min-h-[44px] flex items-center justify-center" style="background:#00A896" onmouseenter="this.style.background='#1AFFCC'" onmouseleave="this.style.background='#00A896'">
                    Send Enquiry
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="py-20 text-center">
        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
        </svg>
        <h3 class="mt-4 text-lg font-semibold" style="color:#0F172A">No dealers found</h3>
        <p class="mt-1 text-sm" style="color:#64748B">
            Try a different city or
            <a href="?city=all" class="font-medium hover:underline" style="color:#00A896">view all dealers</a>.
        </p>
    </div>
    <?php endif; ?>

    <!-- Add dealership CTA -->
    <div class="mt-12 rounded-2xl p-8 text-center text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
        <h3 class="text-2xl font-black">Are you an EV dealer?</h3>
        <p class="mx-auto mt-2 max-w-md" style="color:rgba(255,255,255,.85)">
            List your dealership on Charj.in and get direct enquiries from EV buyers in your city. Free to list.
        </p>
        <a href="#lead-form"
           class="mt-5 inline-block rounded-xl px-6 py-3 text-sm font-bold transition" style="background:#FFFFFF;color:#00A896" onmouseenter="this.style.background='#F0FFF9'" onmouseleave="this.style.background='#FFFFFF'">
            Add Your Dealership
        </a>
    </div>
</div>

<!-- Lead form -->
<div class="mx-auto max-w-2xl px-4 pb-20 md:pb-10">
    <?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?>
</div>

<?= $this->endSection() ?>
