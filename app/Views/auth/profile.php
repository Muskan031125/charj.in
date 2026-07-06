<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
$firstName = explode(' ', $user['name'])[0];
$initial   = strtoupper(substr($user['name'], 0, 1));
$memberSince = date('M Y', strtotime($user['created_at'] ?? 'now'));
?>

<!-- HERO BANNER -->
<div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-emerald-500 to-teal-500 pt-20 sm:pt-24 pb-12 sm:pb-20">
  <!-- Decorative blobs -->
  <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
  <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-black/10 blur-3xl pointer-events-none"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>

  <div class="relative max-w-5xl mx-auto px-4">
    <div class="flex items-center justify-between gap-4 flex-wrap">
      <div class="flex items-center gap-5">
        <!-- Avatar -->
        <div class="relative">
          <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/20 backdrop-blur-sm text-white text-3xl font-black ring-4 ring-white/30 shadow-xl">
            <?= $initial ?>
          </div>
          <div class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-green-300 ring-2 ring-white">
            <svg class="w-3 h-3 text-green-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
          </div>
        </div>
        <div>
          <p class="text-white/70 text-sm font-medium mb-0.5">Welcome back</p>
          <h1 class="text-3xl font-black text-white tracking-tight">Hi, <?= esc($firstName) ?>! ⚡</h1>
          <p class="text-white/60 text-sm mt-1">EV enthusiast · Member since <?= $memberSince ?></p>
        </div>
      </div>
      <a href="<?= site_url('logout') ?>"
         class="flex items-center gap-2 rounded-2xl bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white/90 text-sm font-semibold px-4 py-2.5 transition-all border border-white/20">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Log out
      </a>
    </div>

    <!-- Stats row inside hero -->
    <div class="mt-8 grid grid-cols-3 gap-2 sm:gap-3">
      <div class="rounded-2xl bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-4 text-center">
        <div class="text-2xl sm:text-3xl font-black text-white"><?= (int)($viewCount ?? 0) ?></div>
        <div class="text-white/60 text-[10px] sm:text-xs font-semibold uppercase tracking-wide mt-0.5">EVs Viewed</div>
      </div>
      <div class="rounded-2xl bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-4 text-center">
        <div class="text-2xl sm:text-3xl font-black text-white"><?= count($savedVehicles) ?></div>
        <div class="text-white/60 text-[10px] sm:text-xs font-semibold uppercase tracking-wide mt-0.5">Saved EVs</div>
      </div>
      <div class="rounded-2xl bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-4 text-center">
        <div class="text-xl sm:text-2xl font-black text-white">∞</div>
        <div class="text-white/60 text-[10px] sm:text-xs font-semibold uppercase tracking-wide mt-0.5">Tools Access</div>
      </div>
    </div>
  </div>
</div>

<!-- BODY -->
<div class="bg-slate-50 min-h-screen -mt-6 rounded-t-3xl pt-6 sm:pt-8 pb-24 sm:pb-16">
  <div class="max-w-5xl mx-auto px-4">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 flex items-center gap-3 rounded-2xl bg-green-50 border border-green-200 px-5 py-3.5 text-sm font-medium text-green-800">
      <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <?= esc(session()->getFlashdata('success')) ?>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mb-6 sm:mb-8">
      <a href="<?= site_url('vehicles') ?>"
         class="group flex flex-col items-center gap-2 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 p-3 sm:p-5 text-white shadow-lg shadow-green-200 hover:shadow-xl hover:shadow-green-300 hover:-translate-y-0.5 transition-all">
        <span class="text-2xl">⚡</span>
        <span class="text-xs font-bold">Browse EVs</span>
      </a>
      <a href="<?= site_url('compare') ?>"
         class="group flex flex-col items-center gap-2 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-3 sm:p-5 text-white shadow-lg shadow-purple-200 hover:shadow-xl hover:shadow-purple-300 hover:-translate-y-0.5 transition-all">
        <span class="text-2xl">⚖️</span>
        <span class="text-xs font-bold">Compare EVs</span>
      </a>
      <a href="<?= site_url('ev-finder') ?>"
         class="group flex flex-col items-center gap-2 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 p-3 sm:p-5 text-white shadow-lg shadow-amber-200 hover:shadow-xl hover:shadow-amber-300 hover:-translate-y-0.5 transition-all">
        <span class="text-2xl">🎯</span>
        <span class="text-xs font-bold">EV Finder</span>
      </a>
      <a href="<?= site_url('charging-stations') ?>"
         class="group flex flex-col items-center gap-2 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 p-3 sm:p-5 text-white shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 hover:-translate-y-0.5 transition-all">
        <span class="text-2xl">🔌</span>
        <span class="text-xs font-bold">Find Chargers</span>
      </a>
    </div>

    <!-- EV Profile Quiz Result -->
    <?php if (!empty($quizResult)): ?>
    <?php $qa = $quizResult['answers'] ?? []; ?>
    <?php $budgetLabels = ['under1L'=>'Under ₹1L','1to1.5L'=>'₹1–1.5L','1.5to3L'=>'₹1.5–3L','3to8L'=>'₹3–8L','above8L'=>'₹8L+']; ?>
    <?php $typeLabels = ['scooter'=>'Scooter','motorcycle'=>'Motorcycle','hatchback'=>'Hatchback/Sedan','suv'=>'SUV']; ?>
    <?php $usageLabels = ['commute'=>'Daily commute','errands'=>'Errands & shopping','trips'=>'Weekend trips','business'=>'Business/delivery']; ?>
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-green-50 to-teal-50 border border-green-200 p-6">
      <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
          <div class="flex items-center gap-2 mb-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-green-100">
              <span class="text-sm">🎯</span>
            </div>
            <h2 class="text-base font-bold text-slate-900">Your EV Profile</h2>
            <span class="text-xs text-green-700 font-semibold bg-green-100 px-2 py-0.5 rounded-full">From Quiz</span>
          </div>
          <div class="flex flex-wrap gap-2">
            <?php if (!empty($qa['budget'])): ?>
            <span class="inline-flex items-center gap-1.5 bg-white border border-green-200 text-green-800 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              💰 <?= esc($budgetLabels[$qa['budget']] ?? $qa['budget']) ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($qa['type'])): ?>
            <span class="inline-flex items-center gap-1.5 bg-white border border-blue-200 text-blue-800 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              🚗 <?= esc($typeLabels[$qa['type']] ?? $qa['type']) ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($qa['usage'])): ?>
            <span class="inline-flex items-center gap-1.5 bg-white border border-amber-200 text-amber-800 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              📍 <?= esc($usageLabels[$qa['usage']] ?? $qa['usage']) ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($qa['distance'])): ?>
            <?php $distLabels = ['under30'=>'<30 km/day','30to60'=>'30–60 km/day','60to100'=>'60–100 km/day','over100'=>'100+ km/day']; ?>
            <span class="inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              📏 <?= esc($distLabels[$qa['distance']] ?? $qa['distance']) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>
        <a href="<?= site_url('ev-finder') ?>"
           class="shrink-0 inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
          Retake Quiz →
        </a>
      </div>
    </div>
    <?php endif; ?>

    <!-- Saved EVs -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
          </div>
          <h2 class="text-base font-bold text-slate-900">Saved EVs</h2>
          <?php if (!empty($savedVehicles)): ?>
          <span class="text-xs bg-rose-100 text-rose-700 font-bold px-2 py-0.5 rounded-full"><?= count($savedVehicles) ?></span>
          <?php endif; ?>
        </div>
        <a href="<?= site_url('vehicles') ?>" class="text-sm text-green-600 font-semibold hover:text-green-700">Browse more →</a>
      </div>

      <?php if (empty($savedVehicles)): ?>
      <div class="rounded-2xl bg-white border border-dashed border-rose-200 flex flex-col items-center py-12 text-center">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-2xl mb-3">🔖</div>
        <p class="text-sm font-bold text-slate-700 mb-1">No saved EVs yet</p>
        <p class="text-xs text-slate-400 mb-5">Browse EVs and tap the bookmark icon to save them</p>
        <a href="<?= site_url('vehicles') ?>" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
          Browse EVs →
        </a>
      </div>
      <?php else: ?>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($savedVehicles as $v): ?>
        <div class="group relative rounded-2xl bg-white border border-slate-100 hover:border-green-200 hover:shadow-lg hover:shadow-green-50 transition-all overflow-hidden">
          <?php
          $imgSrc = !empty($v['image_url']) ? esc($v['image_url']) : null;
          $fallback = "https://placehold.co/400x220/f0fdf4/16a34a?text=" . urlencode($v['name']);
          ?>
          <div class="aspect-video bg-gradient-to-br from-green-50 to-emerald-50 overflow-hidden">
            <img src="<?= $imgSrc ?? $fallback ?>" onerror="this.onerror=null;this.src='<?= $fallback ?>'"
                 alt="<?= esc($v['name']) ?>" class="w-full h-full object-contain p-3 group-hover:scale-105 transition duration-300">
          </div>
          <div class="p-4">
            <p class="text-xs text-slate-400 mb-0.5 font-medium"><?= esc($v['brand_name'] ?? '') ?></p>
            <h3 class="font-bold text-slate-800 text-sm mb-2"><?= esc($v['name']) ?></h3>
            <?php if (!empty($v['starting_price'])): ?>
            <p class="text-xs text-green-600 font-bold">from ₹<?= number_format($v['starting_price'] / 100000, 2) ?>L</p>
            <?php endif; ?>
            <a href="<?= site_url('vehicles/' . esc($v['slug'] ?? $v['id'])) ?>"
               class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-green-600 hover:text-green-700">
              View details
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
          <form action="<?= site_url('save-vehicle/' . $v['id']) ?>" method="post" class="absolute top-3 right-3">
            <?= csrf_field() ?>
            <button type="submit" title="Remove from saved"
                    class="flex h-7 w-7 items-center justify-center rounded-full bg-white shadow-md text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition text-xs">
              ✕
            </button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Recently Viewed -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-100">
            <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <h2 class="text-base font-bold text-slate-900">Recently Viewed</h2>
        </div>
        <a href="<?= site_url('vehicles') ?>" class="text-sm text-green-600 font-semibold hover:text-green-700">Browse all →</a>
      </div>

      <?php if (empty($recentlyViewed)): ?>
      <div class="rounded-2xl bg-white border border-dashed border-violet-200 flex flex-col items-center py-12 text-center">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-50 text-2xl mb-3">🔍</div>
        <p class="text-sm font-bold text-slate-700 mb-1">No browsing history yet</p>
        <p class="text-xs text-slate-400 mb-5">Start browsing EVs to see your history here</p>
        <a href="<?= site_url('vehicles') ?>" class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
          Browse EVs →
        </a>
      </div>
      <?php else: ?>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($recentlyViewed as $v): ?>
        <a href="<?= site_url('vehicles/' . esc($v['slug'] ?? $v['id'])) ?>"
           class="group rounded-2xl bg-white border border-slate-100 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-50 transition-all overflow-hidden block">
          <?php
          $imgSrc = !empty($v['image_url']) ? esc($v['image_url']) : null;
          $fallback = "https://placehold.co/400x220/f5f3ff/7c3aed?text=" . urlencode($v['name']);
          ?>
          <div class="aspect-video bg-gradient-to-br from-violet-50 to-purple-50 overflow-hidden">
            <img src="<?= $imgSrc ?? $fallback ?>" onerror="this.onerror=null;this.src='<?= $fallback ?>'"
                 alt="<?= esc($v['name']) ?>" class="w-full h-full object-contain p-3 group-hover:scale-105 transition duration-300">
          </div>
          <div class="p-4">
            <p class="text-xs text-slate-400 mb-0.5 font-medium"><?= esc($v['brand_name'] ?? '') ?></p>
            <h3 class="font-bold text-slate-800 text-sm group-hover:text-violet-700 transition-colors"><?= esc($v['name']) ?></h3>
            <?php if (!empty($v['starting_price'])): ?>
            <p class="text-xs text-green-600 font-bold mt-1">from ₹<?= number_format($v['starting_price'] / 100000, 2) ?>L</p>
            <?php endif; ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Tools Grid -->
    <div class="mb-6">
      <div class="flex items-center gap-2 mb-4">
        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100">
          <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h2 class="text-base font-bold text-slate-900">Quick Tools</h2>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <?php
        $tools = [
          ['href'=>'subsidy-calculator',  'icon'=>'🎁', 'label'=>'Subsidy',      'color'=>'from-amber-400 to-orange-400'],
          ['href'=>'charging-cost',        'icon'=>'⚡', 'label'=>'Charge Cost',  'color'=>'from-sky-400 to-blue-500'],
          ['href'=>'can-i-make-it',        'icon'=>'🗺️', 'label'=>'Trip Range',   'color'=>'from-teal-400 to-cyan-500'],
          ['href'=>'ev-emi-calculator',    'icon'=>'📊', 'label'=>'EMI Calc',     'color'=>'from-violet-400 to-purple-500'],
          ['href'=>'resale-estimator',     'icon'=>'📈', 'label'=>'Resale Value', 'color'=>'from-rose-400 to-pink-500'],
          ['href'=>'home-charger-guide',   'icon'=>'🏠', 'label'=>'Home Charger', 'color'=>'from-green-400 to-emerald-500'],
        ];
        foreach ($tools as $t): ?>
        <a href="<?= site_url($t['href']) ?>"
           class="group flex flex-col items-center gap-2 rounded-2xl bg-white border border-slate-100 hover:shadow-lg p-4 text-center transition-all hover:-translate-y-0.5">
          <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br <?= $t['color'] ?> text-white shadow-md text-lg group-hover:scale-110 transition-transform">
            <?= $t['icon'] ?>
          </div>
          <span class="text-xs font-semibold text-slate-600 leading-tight"><?= $t['label'] ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Account Info -->
    <details class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden group">
      <summary class="flex items-center justify-between px-6 py-4 cursor-pointer list-none select-none hover:bg-slate-50 transition-colors">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          <h2 class="text-base font-bold text-slate-900">Account Information</h2>
        </div>
        <svg class="w-4 h-4 text-slate-400 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
      </summary>
      <div class="border-t border-slate-100 px-6 py-5">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="rounded-xl bg-slate-50 px-4 py-3">
            <dt class="text-xs text-slate-400 mb-0.5 font-medium">Name</dt>
            <dd class="text-sm font-bold text-slate-800"><?= esc($user['name']) ?></dd>
          </div>
          <div class="rounded-xl bg-slate-50 px-4 py-3">
            <dt class="text-xs text-slate-400 mb-0.5 font-medium">Email</dt>
            <dd class="text-sm font-bold text-slate-800"><?= esc($user['email']) ?></dd>
          </div>
          <?php if (!empty($user['phone'])): ?>
          <div class="rounded-xl bg-slate-50 px-4 py-3">
            <dt class="text-xs text-slate-400 mb-0.5 font-medium">Phone</dt>
            <dd class="text-sm font-bold text-slate-800"><?= esc($user['phone']) ?></dd>
          </div>
          <?php endif; ?>
          <?php if (!empty($user['city'])): ?>
          <div class="rounded-xl bg-slate-50 px-4 py-3">
            <dt class="text-xs text-slate-400 mb-0.5 font-medium">City</dt>
            <dd class="text-sm font-bold text-slate-800"><?= esc($user['city']) ?></dd>
          </div>
          <?php endif; ?>
          <div class="rounded-xl bg-slate-50 px-4 py-3">
            <dt class="text-xs text-slate-400 mb-0.5 font-medium">Member Since</dt>
            <dd class="text-sm font-bold text-slate-800"><?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?></dd>
          </div>
          <?php if (!empty($user['last_login'])): ?>
          <div class="rounded-xl bg-slate-50 px-4 py-3">
            <dt class="text-xs text-slate-400 mb-0.5 font-medium">Last Login</dt>
            <dd class="text-sm font-bold text-slate-800"><?= date('d M Y, g:i A', strtotime($user['last_login'])) ?></dd>
          </div>
          <?php endif; ?>
        </dl>
        <p class="text-xs text-slate-400 mt-4">To update your details, contact us at <a href="mailto:support@charj.in" class="text-green-600 font-medium">support@charj.in</a></p>
      </div>
    </details>

  </div>
</div>

<?= $this->endSection() ?>
