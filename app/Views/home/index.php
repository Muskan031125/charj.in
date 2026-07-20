<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Charj.in — India\'s EV Decision Engine | Compare, Calculate, Choose') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Compare 150+ EVs, calculate savings & subsidies, find the perfect electric vehicle for India. FAME II calculator, charging guide, free EV quiz.') ?>">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Organization","name":"Charj.in","url":"<?= base_url() ?>","logo":"<?= base_url('assets/images/charj-logo.png') ?>","description":"India's EV Decision Engine","areaServed":"IN"}
</script>
<style>
  /* Hero mesh grid */
  .hero-mesh {
    background-image: radial-gradient(circle at 1px 1px, rgba(0,230,118,.12) 1px, transparent 0);
    background-size: 32px 32px;
  }
  /* Animated gradient orbs */
  .orb { position:absolute; border-radius:50%; filter:blur(60px); pointer-events:none; }
  /* EV card hover image zoom */
  .ev-img-wrap img { transition:transform .35s cubic-bezier(.4,0,.2,1); }
  .ev-img-wrap:hover img { transform:scale(1.06); }
  /* Category card active fill */
  .cat-card:hover .cat-emoji-wrap { background:rgba(255,255,255,.2) !important; border-color:rgba(255,255,255,.3) !important; }
  /* How-it-works connector */
  .hiw-connector { background:linear-gradient(90deg,transparent,rgba(0,168,150,.3),rgba(14,165,233,.2),transparent); height:1px; }
  /* Testimonial quote */
  .quote-mark { font-size:4rem; line-height:1; color:rgba(0,168,150,.12); font-family:Georgia,serif; }
  /* Stat dividers */
  @media(min-width:768px){ .stat-item+.stat-item { border-left:1px solid rgba(0,168,150,.1); } }
  @media(max-width:767px){ .stat-item:nth-child(even){ border-left:1px solid rgba(0,168,150,.1); } }
  /* Section divider */
  .section-divider { height:1px; background:linear-gradient(90deg,transparent,rgba(0,168,150,.15),transparent); }

  /* ── Tesla-style hero: extra tightening for narrow phones ── */
  @media(max-width:380px){
    .hero-h1{ font-size:1.75rem!important; }
    .hero-sub{ font-size:0.9375rem!important; margin-bottom:2rem!important; }
    .hero-cta{ padding:0.75rem 1.5rem!important; font-size:0.9375rem!important; }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ════════════════════════════════════════════════════════
  HERO — Single Image, Minimal Text, Single CTA (Tesla Style)
════════════════════════════════════════════════════════ -->
<section class="relative w-full overflow-hidden" style="height:clamp(420px,60vh,700px);background:#000">

  <!-- Single Hero Image — admin-managed via /admin/settings (Homepage Hero Image), DB-backed with a Pexels fallback -->
  <?php
  $defaultHero = 'https://images.pexels.com/photos/3807517/pexels-photo-3807517.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop';
  $heroSrc     = !empty($heroImage) ? $heroImage : $defaultHero;
  $heroIsPexels = str_contains($heroSrc, 'images.pexels.com');
  ?>
  <?php if ($heroIsPexels): ?>
  <img src="<?= esc($heroSrc) ?>"
       alt="Find Your Perfect EV" class="w-full h-full object-cover" loading="eager" decoding="async"
       srcset="<?= esc(str_replace('w=1920&h=1080', 'w=1280&h=720', $heroSrc)) ?> 1280w,
               <?= esc($heroSrc) ?> 1920w,
               <?= esc(str_replace('w=1920&h=1080', 'w=2560&h=1440', $heroSrc)) ?> 2560w"
       sizes="100vw">
  <?php else: ?>
  <img src="<?= esc($heroSrc) ?>"
       alt="Find Your Perfect EV" class="w-full h-full object-cover" loading="eager" decoding="async">
  <?php endif; ?>

  <!-- Overlay (subtle darkening for text readability) -->
  <div class="absolute inset-0" style="background:linear-gradient(135deg, rgba(0,0,0,0.2), rgba(0,0,0,0.05))"></div>

  <!-- Content: Centered, Minimal, Focused (Tesla Style) -->
  <div class="absolute inset-0 flex flex-col items-center justify-center px-4 z-10">

    <!-- Headline: 1 line max -->
    <h1 class="hero-h1 text-4xl sm:text-5xl lg:text-6xl font-black text-white text-center mb-3 leading-tight"
        style="max-width:800px;text-shadow:0 2px 10px rgba(0,0,0,0.3)">
      Find Your Perfect EV
    </h1>

    <!-- Subheading: 1 line max -->
    <p class="hero-sub text-lg sm:text-xl text-white text-center mb-12 opacity-90"
       style="max-width:600px;text-shadow:0 1px 5px rgba(0,0,0,0.2)">
      Compare, Calculate, Choose
    </p>

    <!-- Single CTA Button (Prominent, Green Gradient) -->
    <a href="<?= base_url('explore') ?>"
       class="hero-cta inline-flex items-center gap-2 px-8 py-4 rounded-full font-bold text-lg transition-all duration-200"
       style="background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 8px 24px rgba(0,230,118,0.3)"
       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 36px rgba(0,230,118,0.4)'"
       onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(0,230,118,0.3)'">
      Explore All EVs
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
      </svg>
    </a>
  </div>

</section>


<!-- ════════════════════════════════════════════════════════
  FEATURED VEHICLES (WHAT PEOPLE ARE CHOOSING)
════════════════════════════════════════════════════════ -->
<section class="py-12" style="background:#F7FFFE">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="text-xl sm:text-2xl font-black text-center mb-8" style="color:#0F172A">What People Are Choosing</h2>

    <!-- Featured Vehicles Slider -->
    <?php
    $featured = array_slice($featuredVehicles ?? [], 0, 3);
    if (empty($featured)) {
      $featured = [
        ['name'=>'Tata Nexon EV','slug'=>'tata-nexon-ev','brand_name'=>'Tata Motors','expert_rating'=>4.8,'starting_price'=>1449000,'claimed_range'=>200],
        ['name'=>'Ather 450X','slug'=>'ather-450x','brand_name'=>'Ather Energy','expert_rating'=>4.9,'starting_price'=>139000,'claimed_range'=>120],
        ['name'=>'MG ZS EV','slug'=>'mg-zs-ev','brand_name'=>'MG Motor','expert_rating'=>4.6,'starting_price'=>1898000,'claimed_range'=>240],
      ];
    }
    ?>

    <div x-data="{ slide: 0, maxSlides: <?= count($featured) ?>,
      next() { this.slide = (this.slide + 1) % this.maxSlides; },
      prev() { this.slide = (this.slide - 1 + this.maxSlides) % this.maxSlides; }
    }"
    class="relative mb-12" @keydown.arrow-right="next()" @keydown.arrow-left="prev()">

      <!-- Slider Container with Blurred Effect -->
      <div class="relative h-auto overflow-hidden">
        <!-- Background blur effect -->
        <div class="absolute inset-0 pointer-events-none" style="background:radial-gradient(circle at center, transparent 0%, rgba(0,168,150,.03) 100%);"></div>

        <!-- Slides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 relative">
          <?php foreach ($featured as $idx => $ev):
            $slug = $ev['slug'] ?? '';
            $name = $ev['name'] ?? '';
            $brand = $ev['brand_name'] ?? '';
            $rating = (float)($ev['expert_rating'] ?? $ev['user_rating'] ?? 4.5);
            $reviews = (int)($ev['review_count'] ?? 0);
            $price = (int)($ev['starting_price'] ?? 0);
            $price_after = (int)($ev['starting_price'] ? $ev['starting_price'] * 0.85 : 0);
            $range = (int)($ev['claimed_range'] ?? $ev['real_world_range'] ?? 150);
            // Get main image from vehicle_images table
            $image_url = '';
            if (!empty($ev['id'])) {
              $db = \Config\Database::connect();
              $mainImg = $db->table('vehicle_images')
                ->select('image_url')
                ->where('vehicle_id', $ev['id'])
                ->where('image_type', 'main')
                ->get()
                ->getRow();
              $image_url = $mainImg ? $mainImg->image_url : '';
            }
            if (empty($image_url)) {
              $image_url = base_url('assets/images/vehicles/'.esc($slug).'.webp');
            } elseif (!preg_match('#^https?://#i', $image_url)) {
              $image_url = base_url(ltrim($image_url, '/'));
            }
          ?>

          <!-- Slide <?= $idx + 1 ?> -->
          <div 
               class="md:col-span-1 md:col-start-<?= $idx + 1 ?>">

            <a href="<?= base_url('vehicles/'.esc($slug)) ?>"
               class="group flex flex-col rounded-2xl overflow-hidden transition-all duration-200 h-full"
               style="background:rgba(255,255,255,.7);backdrop-filter:blur(10px);border:1px solid rgba(0,168,150,.2);box-shadow:0 8px 32px rgba(0,168,150,.1)"
               onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 48px rgba(0,168,150,.15)';this.style.background='rgba(255,255,255,.85)'"
               onmouseout="this.style.transform='';this.style.boxShadow='0 8px 32px rgba(0,168,150,.1)';this.style.background='rgba(255,255,255,.7)'">

              <!-- Image area -->
              <div class="relative h-[160px] flex items-center justify-center flex-shrink-0 overflow-hidden"
                   style="background:linear-gradient(135deg,rgba(0,168,150,.12),rgba(0,230,118,.06))">

                <img id="featured-img-<?= esc($slug) ?>"
                     src="<?= esc($image_url) ?>"
                     alt="<?= esc($name) ?>"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-110"
                     style="transition:transform .5s cubic-bezier(.22,1,.36,1)"
                     loading="lazy"
                     decoding="async"
                     onerror="this.style.display='none';document.getElementById('fallback-<?= esc($slug) ?>').style.display='flex'">

                <div id="fallback-<?= esc($slug) ?>" class="flex-col items-center gap-1 select-none z-10" style="display:none">
                  <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(0,230,118,.18),rgba(0,230,118,.06));border:1.5px solid rgba(0,230,118,.2)">
                    <svg class="w-6 h-6" style="color:#00C060" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <span style="color:rgba(0,200,100,.5);font-size:8px;font-weight:800;letter-spacing:.1em;text-transform:uppercase"><?= esc($brand) ?></span>
                </div>
              </div>

              <!-- Content -->
              <div class="flex flex-col flex-1 p-4">
                <p class="text-[11px] font-bold uppercase tracking-widest mb-1" style="color:#00A896"><?= esc($brand) ?></p>
                <h3 class="text-base font-black mb-3 leading-snug" style="color:#0F172A"><?= esc($name) ?></h3>

                <!-- Rating -->
                <div class="flex items-center gap-2 mb-3">
                  <span style="color:#F59E0B">★<?= number_format($rating, 1) ?></span>
                  <span class="text-xs" style="color:#94A3B8">(<?= number_format($reviews) ?>)</span>
                </div>

                <!-- Price -->
                <div class="mb-3 pb-3" style="border-bottom:1px solid rgba(0,168,150,.1)">
                  <p class="text-[10px]" style="color:#94A3B8">After Subsidy</p>
                  <p class="text-lg font-black" style="color:#0F172A">₹<?= number_format($price_after/100000, 1) ?>L</p>
                </div>

                <!-- Key specs -->
                <div class="space-y-2 text-xs mb-4 flex-1">
                  <p style="color:#475569">⚡ <?= $range ?> km range</p>
                </div>

                <!-- CTA -->
                <button class="w-full py-2 rounded-lg font-bold text-sm transition-all text-white"
                        style="background:#00A896"
                        onmouseover="this.style.background='#007A6E'"
                        onmouseout="this.style.background='#00A896'">
                  View Details →
                </button>
              </div>
            </a>
          </div>

          <?php endforeach; ?>
        </div>
      </div>

      <!-- Navigation Arrows (hidden on mobile) -->
      <button @click="prev()"
              class="hidden md:flex absolute -left-4 top-1/3 -translate-y-1/2 w-11 h-11 rounded-full backdrop-blur-md justify-center items-center transition-all z-10"
              style="background:rgba(255,255,255,.8);border:1.5px solid rgba(0,168,150,.2)"
              onmouseover="this.style.background='rgba(255,255,255,.95)';this.style.transform='scale(1.1)'"
              onmouseout="this.style.background='rgba(255,255,255,.8)';this.style.transform='scale(1)'"
              aria-label="Previous">
        <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <button @click="next()"
              class="hidden md:flex absolute -right-4 top-1/3 -translate-y-1/2 w-11 h-11 rounded-full backdrop-blur-md justify-center items-center transition-all z-10"
              style="background:rgba(255,255,255,.8);border:1.5px solid rgba(0,168,150,.2)"
              onmouseover="this.style.background='rgba(255,255,255,.95)';this.style.transform='scale(1.1)'"
              onmouseout="this.style.background='rgba(255,255,255,.8)';this.style.transform='scale(1)'"
              aria-label="Next">
        <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </button>

      <!-- Slide Indicators (Dots) -->
      <div class="flex justify-center gap-2 mt-6">
        <template x-for="(_, idx) in Array(maxSlides).fill(0)" :key="idx">
          <button @click="slide = idx"
                  :class="slide === idx ? 'w-3 h-3 bg-green-500' : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'"
                  class="rounded-full transition-all"
                  :aria-label="'Go to slide ' + (idx + 1)">
          </button>
        </template>
      </div>

    </div>

  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  FEATURED VISUAL — EV Hero Image
════════════════════════════════════════════════════════ -->
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#000000 0%,#1a1a2e 50%,#16213e 100%)">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="relative rounded-2xl overflow-hidden" style="height:420px;box-shadow:0 24px 60px rgba(0,0,0,.45),0 0 0 1px rgba(0,230,118,.1)">
      <!-- Image — admin-managed via /admin/settings (Homepage Images) -->
      <?php
      $featuredVisualSrc = !empty($featuredVisualImage)
          ? (preg_match('#^https?://#i', $featuredVisualImage) ? $featuredVisualImage : base_url(ltrim($featuredVisualImage, '/')))
          : base_url('assets/images/vehicles/tata-nexon-ev/front-dark.jpg');
      ?>
      <img src="<?= esc($featuredVisualSrc) ?>"
           alt="Premium Electric Vehicle"
           class="absolute inset-0 w-full h-full object-cover"
           loading="lazy" decoding="async"
           style="transform:scale(1.02)">
      <!-- Overlay gradient for text readability -->
      <div class="absolute inset-0" style="background:linear-gradient(90deg,rgba(0,0,0,.82) 0%,rgba(0,0,0,.55) 32%,rgba(0,0,0,.15) 60%,transparent 85%)"></div>
      <div class="absolute inset-0" style="background:linear-gradient(0deg,rgba(0,0,0,.35),transparent 40%)"></div>

      <!-- Text overlay -->
      <div class="absolute inset-0 flex flex-col justify-center pl-6 sm:pl-12 pr-6">
        <span class="inline-flex items-center gap-1.5 mb-4 w-fit px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide" style="background:rgba(0,230,118,.14);border:1px solid rgba(0,230,118,.3);color:#69FF97;letter-spacing:.06em">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1.05a2.5 2.5 0 00-1.5 4.336V13a1 1 0 002 0V8.386A2.5 2.5 0 0011 4.05V3zM6 6a2 2 0 100 4 2 2 0 000-4z"/></svg>
          150+ EVs Tracked
        </span>
        <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-3 max-w-md leading-tight" style="text-shadow:0 2px 12px rgba(0,0,0,.4)">
          Compare, Review &amp;<br>Buy EVs Smarter
        </h3>
        <p class="text-white/80 text-sm sm:text-base max-w-sm mb-6" style="text-shadow:0 1px 6px rgba(0,0,0,.4)">
          Explore India's fastest-growing EV ecosystem with real pricing, real range, real reviews.
        </p>
        <a href="<?= base_url('vehicles') ?>"
           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full font-bold w-fit transition-all duration-200"
           style="background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 8px 24px rgba(0,230,118,.35)"
           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(0,230,118,.45)'"
           onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(0,230,118,.35)'">
          Browse EVs
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  EV BENEFITS — Interactive Slider
════════════════════════════════════════════════════════ -->
<section class="py-12" style="background:#F7FFFE">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="text-xl sm:text-2xl font-black text-center mb-8" style="color:#0F172A">Why Choose EV?</h2>

    <?php
    // Benefit slider images — admin-managed via /admin/settings (Homepage Images)
    $resolveBenefitImg = fn($u, $fallback) => !empty($u) ? (preg_match('#^https?://#i', $u) ? $u : base_url(ltrim($u, '/'))) : $fallback;
    $benefitImg1 = $resolveBenefitImg($benefitImage1 ?? '', 'https://images.pexels.com/photos/9800029/pexels-photo-9800029.jpeg?auto=compress&cs=tinysrgb&w=800');
    $benefitImg2 = $resolveBenefitImg($benefitImage2 ?? '', 'https://images.pexels.com/photos/9800006/pexels-photo-9800006.jpeg?auto=compress&cs=tinysrgb&w=800');
    $benefitImg3 = $resolveBenefitImg($benefitImage3 ?? '', 'https://images.pexels.com/photos/5945806/pexels-photo-5945806.jpeg?auto=compress&cs=tinysrgb&w=800');
    ?>
    <div x-data="{ slide: 0, imgFailed: {},
      benefits: [
        {
          title: 'Save Money Every Month',
          desc: '₹3,000–₹6,000 savings vs petrol every month',
          img: '<?= esc($benefitImg1, 'js') ?>'
        },
        {
          title: 'Zero Emissions',
          desc: 'Clean air for your city and cleaner conscience',
          img: '<?= esc($benefitImg2, 'js') ?>'
        },
        {
          title: 'Silent & Smooth',
          desc: 'No engine noise, pure electric performance',
          img: '<?= esc($benefitImg3, 'js') ?>'
        }
      ],
      next() { this.slide = Math.min(this.slide + 1, this.benefits.length - 1); },
      prev() { this.slide = Math.max(this.slide - 1, 0); }
    }"
    class="relative" @keydown.arrow-right="next()" @keydown.arrow-left="prev()">

      <!-- Main Slider -->
      <div class="grid md:grid-cols-2 gap-8 items-center">

        <!-- Image Slider -->
        <div class="relative rounded-2xl overflow-hidden shadow-xl" style="height:320px;background:linear-gradient(135deg,rgba(0,168,150,.1),rgba(0,230,118,.05))">

          <template x-for="(benefit, idx) in benefits" :key="idx">
            <img x-show="slide === idx && !imgFailed[idx]"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:leave="transition ease-in duration-300"
                 :src="benefit.img"
                 :alt="benefit.title"
                 class="absolute inset-0 w-full h-full object-cover"
                 loading="lazy"
                 @error="imgFailed[idx] = true">
          </template>

          <!-- Fallback overlay — only shown if the image genuinely failed to load -->
          <template x-for="(benefit, idx) in benefits" :key="idx">
            <div x-show="slide === idx && imgFailed[idx]"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:leave="transition ease-in duration-300"
                 class="absolute inset-0 flex flex-col items-center justify-center text-center p-6"
                 style="background:linear-gradient(135deg,rgba(0,168,150,.15),rgba(0,230,118,.08))">
              <p class="text-lg font-bold" style="color:#0F172A" x-text="benefit.title"></p>
            </div>
          </template>

          <!-- Slide Counter -->
          <div class="absolute top-4 right-4 bg-white/90 text-slate-900 px-3 py-1.5 rounded-full text-xs font-bold backdrop-blur-sm">
            <span x-text="slide + 1"></span> / <span x-text="benefits.length"></span>
          </div>

          <!-- Navigation Arrows -->
          <button @click="prev()" :disabled="slide === 0"
                  class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white flex items-center justify-center transition-all z-10"
                  :class="slide === 0 ? 'opacity-30 cursor-not-allowed hover:!bg-white/80' : ''"
                  style="backdrop-filter:blur(4px)"
                  aria-label="Previous">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:#00A896">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>

          <button @click="next()" :disabled="slide === benefits.length - 1"
                  class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white flex items-center justify-center transition-all z-10"
                  :class="slide === benefits.length - 1 ? 'opacity-30 cursor-not-allowed hover:!bg-white/80' : ''"
                  style="backdrop-filter:blur(4px)"
                  aria-label="Next">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:#00A896">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

        <!-- Content -->
        <div class="flex flex-col justify-center">
          <h3 class="text-3xl font-black mb-4" style="color:#0F172A" x-text="benefits[slide].title"></h3>

          <p class="text-lg mb-6" style="color:#475569" x-text="benefits[slide].desc"></p>

          <!-- Benefits bullets -->
          <div class="space-y-3 mb-8">
            <div class="flex gap-3 text-sm" style="color:#0F172A">
              <span style="color:#00A896;font-weight:900">✓</span>
              <span>Lower monthly costs than traditional vehicles</span>
            </div>
            <div class="flex gap-3 text-sm" style="color:#0F172A">
              <span style="color:#00A896;font-weight:900">✓</span>
              <span>Government subsidies available (FAME II)</span>
            </div>
            <div class="flex gap-3 text-sm" style="color:#0F172A">
              <span style="color:#00A896;font-weight:900">✓</span>
              <span>Minimal maintenance required</span>
            </div>
          </div>

          <a href="<?= base_url('find-my-ev') ?>"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-white w-fit transition-all"
             style="background:linear-gradient(135deg,#00A896,#00C9B1)"
             onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform=''">
            Find Your EV →
          </a>
        </div>

      </div>

      <!-- Slide Indicators -->
      <div class="flex justify-center gap-2 mt-8">
        <template x-for="(_, idx) in benefits" :key="idx">
          <button @click="slide = idx"
                  :class="slide === idx ? 'w-3 h-3 bg-green-500' : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'"
                  class="rounded-full transition-all"
                  :aria-label="'Go to slide ' + (idx + 1)">
          </button>
        </template>
      </div>

    </div>

  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  WHY CHARJ (TRUST PROOF)
════════════════════════════════════════════════════════ -->
<section class="py-12" style="background:#FFFFFF">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="text-xl sm:text-2xl font-black text-center mb-8" style="color:#0F172A">Why You Can Trust This</h2>

    <!-- 4 trust signals -->
    <div class="space-y-4">
      <div class="flex gap-4 p-4 rounded-xl" style="background:#F7FFFE;border:1px solid rgba(0,168,150,.1)">
        <div class="text-2xl flex-shrink-0">💰</div>
        <div>
          <p class="font-bold text-sm mb-1" style="color:#0F172A">Real prices (SIAM + OEM data)</p>
          <p class="text-xs" style="color:#64748B">Pricing from SIAM + official OEM APIs + verified dealer quotes. Not estimates. Not guesses.</p>
        </div>
      </div>

      <div class="flex gap-4 p-4 rounded-xl" style="background:#F7FFFE;border:1px solid rgba(0,168,150,.1)">
        <div class="text-2xl flex-shrink-0">🔌</div>
        <div>
          <p class="font-bold text-sm mb-1" style="color:#0F172A">Real charging (150K+ live stations)</p>
          <p class="text-xs" style="color:#64748B">Live data from 150,000+ public charging stations across 18 states. AC, DC fast, home charging.</p>
        </div>
      </div>

      <div class="flex gap-4 p-4 rounded-xl" style="background:#F7FFFE;border:1px solid rgba(0,168,150,.1)">
        <div class="text-2xl flex-shrink-0">⭐</div>
        <div>
          <p class="font-bold text-sm mb-1" style="color:#0F172A">Real reviews (2,347 verified owners)</p>
          <p class="text-xs" style="color:#64748B">Verified reviews from EV owners, filtered by vehicle year, climate, usage pattern. Not curated. Not cherry-picked.</p>
        </div>
      </div>

      <div class="flex gap-4 p-4 rounded-xl" style="background:#F7FFFE;border:1px solid rgba(0,168,150,.1)">
        <div class="text-2xl flex-shrink-0">🎯</div>
        <div>
          <p class="font-bold text-sm mb-1" style="color:#0F172A">Honest comparison (no OEM bias)</p>
          <p class="text-xs" style="color:#64748B">Funded by VC, not by car makers. We show you all brands equally. Subsidy eligibility shown for every vehicle.</p>
        </div>
      </div>
    </div>

  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  WHY CHOOSE EV — Lifestyle Slider Section
════════════════════════════════════════════════════════ -->
<section class="py-12 md:py-16" style="background:linear-gradient(135deg,#EEFFF3 0%,#F0FFF4 100%)">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-8 items-center">

      <!-- Left: Content -->
      <div>
        <p class="text-sm font-bold uppercase tracking-widest mb-3" style="color:#00A896">The EV Lifestyle</p>
        <h2 class="text-3xl md:text-4xl font-black mb-6 leading-tight" style="color:#0F172A">
          Save ₹3,000–₹6,000 Every Month
        </h2>
        <p class="text-base mb-6 leading-relaxed" style="color:#475569">
          Switch to electric and reclaim thousands every month. No more fuel stops, no more rising petrol prices, just pure savings.
        </p>

        <!-- Benefits list -->
        <div class="space-y-4 mb-8">
          <div class="flex gap-3">
            <div class="text-xl flex-shrink-0">⚡</div>
            <div>
              <p class="font-bold text-sm" style="color:#0F172A">8x cheaper than petrol</p>
              <p class="text-xs" style="color:#64748B">Electricity costs ~₹1/km vs ₹5–8/km for petrol</p>
            </div>
          </div>
          <div class="flex gap-3">
            <div class="text-xl flex-shrink-0">🔧</div>
            <div>
              <p class="font-bold text-sm" style="color:#0F172A">80% less maintenance</p>
              <p class="text-xs" style="color:#64748B">No oil changes, no complex engines, fewer moving parts</p>
            </div>
          </div>
          <div class="flex gap-3">
            <div class="text-xl flex-shrink-0">🌱</div>
            <div>
              <p class="font-bold text-sm" style="color:#0F172A">Zero emissions, pure conscience</p>
              <p class="text-xs" style="color:#64748B">Drive guilt-free. Contribute to cleaner air in your city</p>
            </div>
          </div>
        </div>

        <!-- CTA -->
        <a href="<?= base_url('find-my-ev') ?>"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-white transition-all"
           style="background:linear-gradient(135deg,#00A896,#00C9B1)"
           onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform=''">
          Find Your Perfect EV →
        </a>
      </div>

      <!-- Right: Image Slider — images admin-managed via /admin/settings (Homepage Images) -->
      <?php
      $resolveHomeImg = fn($u, $fallback) => !empty($u) ? (preg_match('#^https?://#i', $u) ? $u : base_url(ltrim($u, '/'))) : $fallback;
      $lifestyleImg1 = $resolveHomeImg($lifestyleImage1 ?? '', base_url('assets/images/vehicles/tata-nexon-ev/front-dark.jpg'));
      $lifestyleImg2 = $resolveHomeImg($lifestyleImage2 ?? '', base_url('assets/images/vehicles/ather-450x/front-action.jpg'));
      ?>
      <div x-data="{ slide: 0, slides: [
        { img: '<?= esc($lifestyleImg1, 'js') ?>', title: 'Smart Living', desc: 'Charge at home, save time' },
        { img: '<?= esc($lifestyleImg2, 'js') ?>', title: 'Premium Drives', desc: 'Silent, smooth, powerful' }
      ],
      next() { this.slide = (this.slide + 1) % this.slides.length; },
      prev() { this.slide = (this.slide - 1 + this.slides.length) % this.slides.length; }
      }"
      class="relative" @keydown.arrow-right="next()" @keydown.arrow-left="prev()">

        <!-- Image Container -->
        <div class="relative rounded-2xl overflow-hidden shadow-lg" style="height:360px">
          <template x-for="(s, idx) in slides" :key="idx">
            <img x-show="slide === idx"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:leave="transition ease-in duration-300"
                 :src="s.img"
                 :alt="s.title"
                 class="absolute inset-0 w-full h-full object-cover"
                 loading="lazy">
          </template>

          <!-- Subtle gradient overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

          <!-- Slide Counter -->
          <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1.5 rounded-full text-xs font-bold backdrop-blur-sm">
            <span x-text="slide + 1"></span> / <span x-text="slides.length"></span>
          </div>

          <!-- Navigation Arrows -->
          <button @click="prev()"
                  class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white flex items-center justify-center transition-all z-10 backdrop-blur-sm"
                  aria-label="Previous slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>

          <button @click="next()"
                  class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white flex items-center justify-center transition-all z-10 backdrop-blur-sm"
                  aria-label="Next slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

        <!-- Slide Indicators (Dots) -->
        <div class="flex justify-center gap-2 mt-4">
          <template x-for="(s, idx) in slides" :key="idx">
            <button @click="slide = idx"
                    :class="slide === idx ? 'w-3 h-3 bg-green-500' : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'"
                    class="rounded-full transition-all"
                    :aria-label="'Go to slide ' + (idx + 1)">
            </button>
          </template>
        </div>

      </div>

    </div>
  </div>
</section>

<div class="pb-4 md:pb-0"></div>

<?= $this->endSection() ?>

