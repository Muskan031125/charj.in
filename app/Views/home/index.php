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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ════════════════════════════════════════════════════════
  HERO — Light crystal gradient, premium search
════════════════════════════════════════════════════════ -->
<section class="relative flex flex-col justify-center overflow-hidden" style="background:linear-gradient(160deg,#F0FFF4 0%,#EEFFF3 40%,#F5FFF7 70%,#F0FFF4 100%)">

  <div class="absolute inset-0 hero-mesh opacity-40 pointer-events-none" aria-hidden="true"></div>
  <div class="orb w-[600px] h-[360px] top-0 left-1/2 -translate-x-1/2 -translate-y-1/3" style="background:radial-gradient(ellipse,rgba(0,230,118,.1),transparent 68%)" aria-hidden="true"></div>

  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-[88px] pb-7 text-center">

    <!-- Eyebrow badge -->
    <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 mb-3 animate-fade-in-up stagger-1"
         style="background:rgba(255,255,255,.85);border:1.5px solid rgba(0,168,150,.25);box-shadow:0 2px 8px rgba(0,168,150,.1);backdrop-filter:blur(8px)">
      <span class="neon-dot flex-shrink-0" style="width:6px;height:6px"></span>
      <span class="text-[11px] font-bold tracking-wider uppercase" style="color:#00A896">Dedicated EV Marketplace</span>
    </div>

    <!-- H1 — tighter -->
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black leading-[1.05] tracking-tight mb-2.5 animate-fade-in-up stagger-2" style="color:#0F172A">
      One platform. <span class="hero-gradient-text">Every EV brand.</span>
    </h1>

    <!-- Sub — shorter -->
    <p class="text-sm sm:text-[15px] max-w-lg mx-auto leading-snug mb-4 animate-fade-in-up stagger-3" style="color:#475569">
      Discover, compare and choose the right electric vehicle for India — subsidies, savings and charging, all in one place.
    </p>

    <!-- Search Bar — same but tighter py -->
    <form action="<?= base_url('vehicles') ?>" method="GET"
      x-data="{q:'',sugg:[],show:false,idx:-1,
        async fetch(){if(this.q.length<2){this.sugg=[];return;}
          try{const r=await fetch('/api/vehicles/search?q='+encodeURIComponent(this.q)+'&limit=6');const d=await r.json();this.sugg=d.data||[];}catch(e){this.sugg=[];}
          this.show=this.sugg.length>0;},
        select(v){this.q=v.name;this.show=false;window.location='/vehicles/'+v.slug;},
        kd(e){if(!this.show)return;if(e.key==='ArrowDown'){e.preventDefault();this.idx=Math.min(this.idx+1,this.sugg.length-1);}
          else if(e.key==='ArrowUp'){e.preventDefault();this.idx=Math.max(this.idx-1,-1);}
          else if(e.key==='Enter'&&this.idx>=0){e.preventDefault();this.select(this.sugg[this.idx]);}}
      }"
      @keydown="kd($event)"
      class="relative max-w-xl mx-auto mb-3 animate-fade-in-up stagger-4" role="search"
      @submit.prevent="show=false;if(idx>=0&&sugg[idx]){select(sugg[idx]);}else{$el.submit();}">
  <div class="flex items-center rounded-xl overflow-hidden search-bar"
       style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.2);box-shadow:0 4px 20px rgba(0,0,0,.08)">
    <div class="flex items-center pl-4 pr-2 flex-shrink-0">
      <svg class="w-4 h-4" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
      </svg>
    </div>
    <input type="text" name="q"
           x-model="q"
           @input.debounce.250ms="fetch()"
           @focus="if(sugg.length)show=true"
           @click.away="show=false;idx=-1"
           placeholder="Search EVs, brands..."
           class="flex-1 py-3 text-sm outline-none min-w-0 font-medium"
           style="background:transparent;color:#0F172A;caret-color:#00A896"
           autocomplete="off"
           aria-label="Search EVs">
    <button type="submit" class="m-1.5 px-4 py-2 rounded-lg text-white font-bold text-sm transition-all flex-shrink-0"
            style="background:linear-gradient(135deg,#00A896,#00C9B1)"
            onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform=''">
      Search
    </button>
  </div>
  <!-- Autocomplete dropdown -->
  <div x-show="show" x-cloak
       class="absolute top-full left-0 right-0 mt-1.5 rounded-xl overflow-hidden z-50"
       style="background:#fff;border:1.5px solid rgba(0,168,150,.18);box-shadow:0 8px 32px rgba(0,168,150,.14)">
    <template x-for="(v,i) in sugg" :key="v.slug">
      <button type="button" @click="select(v)"
              class="w-full flex items-center gap-3 px-4 py-2.5 text-left transition-all duration-150"
              :style="i===idx ? 'background:rgba(0,168,150,.08)' : ''"
              onmouseover="this.style.background='rgba(0,168,150,.06)'"
              onmouseout="this.style.background=''">
        <div class="w-10 h-8 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden" style="background:rgba(0,168,150,.08)">
          <img x-show="v.image_url" :src="v.image_url" :alt="v.name" class="w-full h-full object-contain" loading="lazy">
          <svg x-show="!v.image_url" class="w-4 h-4" style="color:#00A896" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
        </div>
        <div class="min-w-0 flex-1">
          <div class="text-sm font-bold leading-snug truncate" style="color:#0F172A" x-text="v.name"></div>
          <div class="text-[10px]" style="color:#94A3B8" x-text="(v.brand_name||'') + (v.starting_price ? ' · ₹'+Math.round(v.starting_price/100000*10)/10+'L' : '')"></div>
        </div>
        <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
      </button>
    </template>
  </div>
</form>

    <!-- CTA buttons — compact -->
    <div class="flex flex-row gap-2.5 justify-center mb-4 animate-fade-in-up stagger-6">
      <a href="<?= base_url('find-my-ev') ?>" onclick="charjTrack('hero_cta_finder',{})"
         class="inline-flex items-center justify-center gap-2 text-white font-bold text-sm px-6 py-2.5 rounded-full transition-all duration-200"
         style="background:linear-gradient(135deg,#00A896,#00BFA5);box-shadow:0 4px 14px rgba(0,168,150,.35)"
         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 22px rgba(0,168,150,.45)'"
         onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(0,168,150,.35)'">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
        Find My EV →
      </a>
      <a href="<?= base_url('compare') ?>"
         class="inline-flex items-center justify-center gap-2 font-semibold text-sm px-6 py-2.5 rounded-full transition-all duration-200"
         style="background:rgba(0,168,150,.08);color:#00A896;border:1.5px solid rgba(0,168,150,.3)"
         onmouseover="this.style.background='#00A896';this.style.color='#fff';this.style.borderColor='#00A896'"
         onmouseout="this.style.background='rgba(0,168,150,.08)';this.style.color='#00A896';this.style.borderColor='rgba(0,168,150,.3)'">
        Compare EVs
      </a>
    </div>

    <!-- Trust strip — compact inline chips -->
    <div class="flex flex-wrap items-center justify-center gap-2 animate-fade-in-up stagger-6">
      <?php
      $heroProof = [
        ['icon'=>'⚡','label'=>'150+ EVs'],
        ['icon'=>'🗺️','label'=>'18 States'],
        ['icon'=>'🛠️','label'=>'25+ Free Tools'],
        ['icon'=>'🎁','label'=>'₹1.5L Subsidy'],
      ];
      foreach ($heroProof as $hp):
      ?>
      <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full"
           style="background:rgba(255,255,255,.8);border:1px solid rgba(0,168,150,.15);backdrop-filter:blur(8px)">
        <span class="text-sm leading-none"><?= $hp['icon'] ?></span>
        <span class="text-xs font-bold" style="color:#0F172A"><?= $hp['label'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</section>


<!-- ════════════════════════════════════════════════════════
  4-COL INTELLIGENCE DASHBOARD
════════════════════════════════════════════════════════ -->
<section class="py-4" style="background:#F7FFFE">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- 4 panel columns -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

      <!-- ── PANEL 1: EV Catalog ── -->
      <div class="rounded-2xl flex flex-col overflow-hidden sr-left" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);box-shadow:0 2px 12px rgba(0,168,150,.06)">
        <div class="flex items-center justify-between px-4 py-2.5" style="background:rgba(0,168,150,.04);border-bottom:1px solid rgba(0,168,150,.09)">
          <div class="flex items-center gap-2">
            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">EV Catalog</span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full text-white" style="background:#00A896">150+</span>
          </div>
          <a href="<?= base_url('vehicles') ?>" class="text-[10px] font-bold" style="color:#00A896">All →</a>
        </div>
        <div class="p-3 flex-1 flex flex-col">
          <div class="grid grid-cols-2 gap-1.5 mb-3">
            <?php foreach ([
              ['🛵','2-Wheelers','80+ EVs',base_url('vehicles?category=electric-scooters')],
              ['🛺','3-Wheelers','25+ EVs',base_url('vehicles?category=electric-rickshaws')],
              ['🚗','4-Wheelers','30+ EVs',base_url('vehicles?category=electric-cars')],
              ['🚛','Commercial','15+ EVs',base_url('vehicles?category=electric-buses')],
            ] as [$em,$n,$c,$u]): ?>
            <a href="<?= $u ?>" class="flex items-center gap-2 p-2 rounded-xl transition-all duration-150"
               style="background:rgba(0,168,150,.05);border:1px solid rgba(0,168,150,.1)"
               onmouseover="this.style.background='rgba(0,168,150,.12)';this.style.borderColor='#00A896'"
               onmouseout="this.style.background='rgba(0,168,150,.05)';this.style.borderColor='rgba(0,168,150,.1)'">
              <span class="text-base"><?= $em ?></span>
              <div>
                <div class="text-[10px] font-black leading-none" style="color:#0F172A"><?= $n ?></div>
                <div class="text-[8px]" style="color:#94A3B8"><?= $c ?></div>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
          <a href="<?= base_url('compare') ?>" class="flex items-center gap-1.5 mt-auto mb-1.5 text-[10px] font-bold px-3 py-1.5 rounded-lg transition-all"
             style="background:rgba(0,168,150,.07);color:#00A896;border:1px solid rgba(0,168,150,.15)">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Compare EVs Side by Side
          </a>
          <a href="<?= base_url('vehicles') ?>" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl font-bold text-xs text-white transition-all"
             style="background:#00A896"
             onmouseover="this.style.background='#007A6E'" onmouseout="this.style.background='#00A896'">
            Browse All 150+ EVs →
          </a>
        </div>
      </div>

      <!-- ── PANEL 2: Charge Near You ── -->
      <div class="rounded-2xl flex flex-col overflow-hidden sr" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);box-shadow:0 2px 12px rgba(0,168,150,.06)"
           x-data="{
             loading:false, located:false, stations:[], err:'',
             locate(){
               if(!navigator.geolocation){this.err='Location not supported';return;}
               this.loading=true; this.err='';
               var self=this;
               navigator.geolocation.getCurrentPosition(function(pos){
                 fetch('/charging-stations/api?lat='+pos.coords.latitude+'&lng='+pos.coords.longitude+'&radius=10&limit=3')
                   .then(function(r){return r.json()})
                   .then(function(d){self.stations=d.data||[];self.located=true;self.loading=false;})
                   .catch(function(){self.err='Could not load stations';self.loading=false;});
               },function(){self.err='Location denied';self.loading=false;});
             }
           }">
        <div class="flex items-center justify-between px-4 py-2.5" style="background:rgba(0,168,150,.04);border-bottom:1px solid rgba(0,168,150,.09)">
          <div class="flex items-center gap-2">
            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">Charging</span>
            <span class="flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full" style="background:rgba(0,200,80,.1);color:#00963C">
              <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Live
            </span>
          </div>
          <a href="<?= base_url('charging-stations') ?>" class="text-[10px] font-bold" style="color:#00A896">Map →</a>
        </div>
        <div class="p-3 flex-1 flex flex-col">
          <!-- Before locating -->
          <div x-show="!located && !loading" class="flex-1 flex flex-col items-center justify-center text-center py-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background:rgba(0,168,150,.08);border:1.5px solid rgba(0,168,150,.15)">
              <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-xs font-semibold mb-1" style="color:#0F172A">Find Chargers Near You</p>
            <p class="text-[10px] mb-3" style="color:#64748B">Public EV charging stations in 5 km radius</p>
            <?php foreach (['Delhi','Mumbai','Bangalore','Hyderabad'] as $city): ?>
            <a href="<?= base_url('charging-stations/'.strtolower($city)) ?>" class="inline-flex text-[9px] font-bold mr-1 mb-1 px-2 py-1 rounded-full"
               style="background:rgba(0,168,150,.07);color:#00A896;border:1px solid rgba(0,168,150,.15)"><?= $city ?></a>
            <?php endforeach; ?>
          </div>
          <!-- Loading -->
          <div x-show="loading" x-cloak class="flex-1 flex items-center justify-center py-6">
            <div class="w-6 h-6 rounded-full border-2 border-t-transparent animate-spin" style="border-color:rgba(0,168,150,.2);border-top-color:#00A896"></div>
          </div>
          <!-- Error -->
          <p x-show="err" x-cloak class="text-xs text-center py-2" style="color:#ef4444" x-text="err"></p>
          <!-- Results -->
          <div x-show="located && stations.length > 0" x-cloak class="flex-1 space-y-1.5">
            <template x-for="st in stations" :key="st.id">
              <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl" style="background:rgba(0,168,150,.04);border:1px solid rgba(0,168,150,.1)">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(0,168,150,.1)">
                  <svg class="w-3 h-3" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-[10px] font-bold truncate" style="color:#0F172A" x-text="st.name"></div>
                  <div class="text-[8px]" style="color:#94A3B8" x-text="(st.power||'') + (st.distance ? ' · '+st.distance+' km' : '')"></div>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0"
                      :style="st.available > 0 ? 'background:rgba(0,168,150,.12);color:#00A896' : 'background:rgba(239,68,68,.08);color:#ef4444'"
                      x-text="st.available > 0 ? st.available+' free' : 'Full'"></span>
              </div>
            </template>
          </div>
          <div x-show="located && stations.length === 0" x-cloak class="flex-1 flex items-center justify-center py-4">
            <p class="text-xs text-center" style="color:#94A3B8">No stations found nearby.<br><a href="<?= base_url('charging-stations') ?>" style="color:#00A896;font-weight:700">Search on map →</a></p>
          </div>
          <a href="<?= base_url('charging-stations') ?>" class="mt-3 w-full flex items-center justify-center gap-1.5 py-2 rounded-xl font-bold text-xs text-white transition-all"
             style="background:#00A896"
             onmouseover="this.style.background='#007A6E'" onmouseout="this.style.background='#00A896'">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            Full Charging Map →
          </a>
        </div>
      </div>

      <!-- ── PANEL 3: Subsidy Spotlight ── -->
      <div class="relative overflow-hidden rounded-2xl flex flex-col overflow-hidden sr" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);box-shadow:0 2px 12px rgba(0,168,150,.06)">
        <div class="flex items-center justify-between px-4 py-2.5" style="background:rgba(0,168,150,.04);border-bottom:1px solid rgba(0,168,150,.09)">
          <div class="flex items-center gap-2">
            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">Subsidies</span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full text-white" style="background:#00A896">LIVE</span>
          </div>
          <a href="<?= base_url('subsidy-calculator') ?>" class="text-[10px] font-bold" style="color:#00A896">Calculate →</a>
        </div>
        <div class="flex-1 px-4 py-3 flex flex-col" style="background:linear-gradient(135deg,#007a6e,#00A896,#00bfa5)">
          <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(circle,rgba(255,255,255,.06) 1px,transparent 1px);background-size:18px 18px"></div>
          <p class="text-sm font-black text-white mb-0.5">Up to <span style="color:#b2f5ea">₹1.5 lakh off</span></p>
          <p class="text-[10px] mb-3" style="color:rgba(255,255,255,.7)">FAME II · State grants · 80EEB tax deduction</p>
          <div class="grid grid-cols-3 gap-1.5 mb-3">
            <?php foreach (['🏙️ Delhi<br>₹1.55L','🌿 Gujarat<br>₹1.7L','🌆 K\'taka<br>₹10K'] as $pill): ?>
            <div class="text-center rounded-xl py-1.5 px-1" style="background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.2)">
              <p class="text-white text-[9px] font-semibold leading-snug"><?= $pill ?></p>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="flex items-start gap-2 rounded-xl px-3 py-2 mb-3" style="background:rgba(255,255,255,.12)">
            <span class="text-xs flex-shrink-0">💡</span>
            <p class="text-[9px] leading-relaxed text-white" style="opacity:.85">Stack FAME + state + 80EEB — most buyers miss all three</p>
          </div>
          <a href="<?= base_url('subsidy-calculator') ?>"
             class="mt-auto flex items-center justify-center gap-1.5 bg-white font-bold text-xs rounded-xl py-2 w-full transition-all"
             style="color:#007a6e"
             onmouseover="this.style.boxShadow='0 4px 14px rgba(0,0,0,.2)'"
             onmouseout="this.style.boxShadow=''">
            Check My Subsidy →
          </a>
        </div>
      </div>

      <!-- ── PANEL 4: Best EVs ── -->
      <?php
      $rankedByCategory = $rankedByCategory ?? [];
      $evFallback = [
        '2-wheeler' => [
          ['name'=>'Ather 450X','slug'=>'ather-450x','brand_name'=>'Ather Energy','starting_price'=>139000,'claimed_range'=>146,'expert_rating'=>8.8],
          ['name'=>'TVS iQube','slug'=>'tvs-iqube','brand_name'=>'TVS Motor','starting_price'=>142750,'claimed_range'=>145,'expert_rating'=>8.5],
          ['name'=>'Ola S1 Pro','slug'=>'ola-s1-pro','brand_name'=>'Ola Electric','starting_price'=>139999,'claimed_range'=>195,'expert_rating'=>8.2],
          ['name'=>'Revolt RV400','slug'=>'revolt-rv400','brand_name'=>'Revolt Motors','starting_price'=>124999,'claimed_range'=>150,'expert_rating'=>7.8],
        ],
        '4-wheeler' => [
          ['name'=>'Tata Nexon EV','slug'=>'tata-nexon-ev','brand_name'=>'Tata Motors','starting_price'=>1449000,'claimed_range'=>465,'expert_rating'=>9.0],
          ['name'=>'Tata Tiago EV','slug'=>'tata-tiago-ev','brand_name'=>'Tata Motors','starting_price'=>849000,'claimed_range'=>315,'expert_rating'=>8.4],
          ['name'=>'MG ZS EV','slug'=>'mg-zs-ev','brand_name'=>'MG Motor','starting_price'=>1898000,'claimed_range'=>461,'expert_rating'=>8.7],
          ['name'=>'MG Comet EV','slug'=>'mg-comet-ev','brand_name'=>'MG Motor','starting_price'=>798000,'claimed_range'=>230,'expert_rating'=>7.9],
        ],
      ];
      ?>
      <div class="rounded-2xl flex flex-col overflow-hidden sr-right" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);box-shadow:0 2px 12px rgba(0,168,150,.06)"
           x-data="{cat:'2-wheeler'}">
        <div class="flex items-center justify-between px-4 py-2.5" style="background:rgba(0,168,150,.04);border-bottom:1px solid rgba(0,168,150,.09)">
          <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">Best EVs</span>
          <div class="flex gap-1">
            <?php foreach (['2-wheeler'=>'🛵','3-wheeler'=>'🛺','4-wheeler'=>'🚗'] as $k=>$em): ?>
            <button @click="cat='<?= $k ?>'"
                    class="text-xs px-1.5 py-0.5 rounded-lg transition-all duration-150"
                    :style="cat==='<?= $k ?>' ? 'background:#00A896;color:#fff' : 'background:rgba(0,168,150,.08);color:#64748B'"><?= $em ?></button>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="flex-1 overflow-y-auto">
          <?php foreach (['2-wheeler','3-wheeler','4-wheeler'] as $catK):
            $evs = !empty($rankedByCategory[$catK]) ? $rankedByCategory[$catK] : ($evFallback[$catK] ?? []);
            if (empty($evs)) $evs = [['name'=>'Coming Soon','slug'=>'#','brand_name'=>'','starting_price'=>0,'claimed_range'=>0,'expert_rating'=>0]];
          ?>
          <div x-show="cat==='<?= $catK ?>'" x-cloak
               x-transition:enter="transition ease-out duration-180"
               x-transition:enter-start="opacity-0 translate-y-1"
               x-transition:enter-end="opacity-100 translate-y-0">
            <?php foreach (array_slice($evs,0,5) as $ri=>$v):
              $price = (int)($v['starting_price']??0);
              $pStr = $price>=100000 ? '₹'.round($price/100000,1).'L' : ($price>0?'₹'.number_format($price):'—');
              $range = (int)($v['claimed_range']??0);
              $rtng = (float)($v['expert_rating']??0);
              $rankColors = ['#F59E0B','#94A3B8','#CD7C3B','#CBD5E1','#E2E8F0'];
            ?>
            <a href="<?= base_url('vehicles/'.($v['slug']??'#')) ?>"
               class="flex items-center gap-2.5 px-3 py-2 transition-all duration-150 <?= $ri<count(array_slice($evs,0,5))-1?'border-b':'' ?>"
               style="<?= $ri<4?'border-color:rgba(0,168,150,.07)':'' ?>"
               onmouseover="this.style.background='rgba(0,168,150,.04)'" onmouseout="this.style.background=''">
              <span class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-black flex-shrink-0 text-white"
                    style="background:<?= $rankColors[$ri] ?? '#E2E8F0' ?>;color:<?= $ri<3?'#fff':'#64748B' ?>"><?= $ri===0?'★':$ri+1 ?></span>
              <?php if (!empty($v['image_url'])): ?>
              <img src="<?= esc($v['image_url']) ?>" alt="<?= esc($v['name']??'') ?>" class="w-8 h-8 object-contain rounded flex-shrink-0" loading="lazy">
              <?php endif; ?>
              <div class="flex-1 min-w-0">
                <div class="text-xs font-bold truncate leading-snug" style="color:#0F172A"><?= esc($v['name']??'') ?></div>
                <div class="text-[9px] truncate" style="color:#94A3B8"><?= esc($v['brand_name']??'') ?><?= $range>0?' · '.$range.'km':'' ?></div>
              </div>
              <div class="flex flex-col items-end gap-0.5 flex-shrink-0">
                <span class="text-[10px] font-black" style="color:#0F172A"><?= $pStr ?></span>
                <?php if ($rtng>0): ?><span class="text-[9px]" style="color:#D97706">★<?= number_format($rtng,1) ?></span><?php endif; ?>
              </div>
            </a>
            <?php endforeach; ?>
            <a href="<?= base_url('vehicles?category='.$catK) ?>"
               class="flex items-center justify-center gap-1 w-full py-2 text-xs font-bold text-white transition-all"
               style="background:#00A896"
               onmouseover="this.style.background='#007A6E'" onmouseout="this.style.background='#00A896'">
              See all <?= ['2-wheeler'=>'🛵 2-Wheelers','3-wheeler'=>'🛺 3-Wheelers','4-wheeler'=>'🚗 4-Wheelers'][$catK] ?> →
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div><!-- /4-col grid -->
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  EVERYTHING YOU NEED — full tool grid (12 compact tiles)
════════════════════════════════════════════════════════ -->
<section class="py-6" style="background:#FFFFFF;border-top:1px solid rgba(0,168,150,.08)">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between mb-4 reveal">
      <div>
        <span class="text-[10px] font-black uppercase tracking-widest" style="color:#00A896">Free Tools</span>
        <h2 class="text-xl font-black leading-tight" style="color:#0F172A">Everything you need to <span class="hero-gradient-text">choose right</span></h2>
        <p class="text-xs mt-0.5" style="color:#64748B">Not a listing site — a decision engine. 25+ free tools.</p>
      </div>
      <a href="<?= base_url('ev-tools') ?>" class="hidden sm:flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full transition-all duration-200"
         style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)"
         onmouseover="this.style.background='#00A896';this.style.color='#fff'" onmouseout="this.style.background='rgba(0,168,150,.1)';this.style.color='#00A896'">
        All Tools
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
    <?php
    $features = [
      ['🛣️','On-Road Price',        'Real price in your state', base_url('on-road-price'),    'rgba(0,168,150,.07)','rgba(0,168,150,.18)'],
      ['🎯','EV Finder Quiz',        '3 matches in 2 min',      base_url('find-my-ev'),       'rgba(0,168,150,.07)','rgba(0,168,150,.18)'],
      ['⚖️','Compare EVs',           'Side-by-side specs',      base_url('compare'),          'rgba(124,58,237,.07)','rgba(124,58,237,.16)'],
      ['🎁','Subsidy Calculator',    'PM E-DRIVE + state',      base_url('subsidy-calculator'),'rgba(0,150,60,.07)','rgba(0,150,60,.16)'],
      ['⚡','Charging Cost/km',       '₹/km vs petrol',          base_url('charging-cost'),    'rgba(14,165,233,.07)','rgba(14,165,233,.16)'],
      ['🗺️','Trip Range Checker',    'Can your EV make it?',    base_url('can-i-make-it'),     'rgba(245,158,11,.07)','rgba(245,158,11,.16)'],
      ['💰','5-Year TCO',            'Real ownership cost',     base_url('tco-calculator'),   'rgba(99,102,241,.07)','rgba(99,102,241,.16)'],
      ['🔌','Charger Compatibility', 'Tata Power? Statiq?',     base_url('charger-check'),    'rgba(0,168,150,.07)','rgba(0,168,150,.18)'],
      ['📈','Resale Estimator',      'Value in 3 years',        base_url('resale-estimator'), 'rgba(139,92,246,.07)','rgba(139,92,246,.16)'],
      ['🔋','Battery Cost Guide',    'Replacement cost',        base_url('battery-cost'),     'rgba(14,165,233,.07)','rgba(14,165,233,.16)'],
      ['🏠','Home Charger Guide',    'Setup cost by city',      base_url('home-charger-guide'),'rgba(245,158,11,.07)','rgba(245,158,11,.16)'],
      ['🚛','Fleet ROI Calculator',  'Savings for your fleet',  base_url('fleet-calculator'), 'rgba(0,150,60,.07)','rgba(0,150,60,.16)'],
    ];
    ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5 sr-stagger">
      <?php foreach ($features as [$icon,$title,$desc,$url,$bg,$bd]): ?>
      <a href="<?= $url ?>"
         onclick="charjTrack('feature_click',{feature:'<?= addslashes(esc($title)) ?>'})"
         class="group flex flex-col items-center text-center p-3 rounded-2xl transition-all duration-200"
         style="background:<?= $bg ?>;border:1px solid <?= $bd ?>"
         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.08)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg mb-2 transition-transform duration-200 group-hover:scale-110"
             style="background:#FFFFFF;border:1px solid <?= $bd ?>"><?= $icon ?></div>
        <h3 class="font-black text-[11px] leading-snug mb-0.5" style="color:#0F172A"><?= esc($title) ?></h3>
        <p class="text-[9px] leading-snug" style="color:#64748B"><?= esc($desc) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  COMPARE EVs — single redirect card
════════════════════════════════════════════════════════ -->
<section class="py-6" style="background:#F7FFFE;border-top:1px solid rgba(0,168,150,.08)">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <a href="<?= base_url('compare') ?>"
       class="group block rounded-2xl overflow-hidden reveal transition-all duration-200"
       style="background:linear-gradient(135deg,#00A896,#007A6E);box-shadow:0 6px 24px rgba(0,168,150,.18)"
       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 14px 36px rgba(0,168,150,.28)'"
       onmouseout="this.style.transform='';this.style.boxShadow='0 6px 24px rgba(0,168,150,.18)'">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 text-center sm:text-left">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25)">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <div>
            <div class="text-lg font-black text-white leading-tight">Compare EVs side-by-side</div>
            <div class="text-xs mt-0.5" style="color:rgba(255,255,255,.8)">Pick any 2–4 EVs &amp; variants — 30+ specs, range, charging &amp; price.</div>
          </div>
        </div>
        <span class="inline-flex items-center gap-2 font-bold text-sm px-6 py-2.5 rounded-full flex-shrink-0 transition-transform group-hover:translate-x-1"
              style="background:#FFFFFF;color:#007A6E">
          Start comparing
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </span>
      </div>
    </a>
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  HOW IT WORKS + SOCIAL PROOF — combined, alternating tint
════════════════════════════════════════════════════════ -->
<section class="py-6" style="background:linear-gradient(180deg,#F0FFF9,#F7FFFE)">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-5 gap-5 items-start">

      <!-- How it works — 3 steps stacked -->
      <div class="lg:col-span-2 sr-left">
        <h2 class="text-xl font-black leading-tight mb-1" style="color:#0F172A">From confused to <span class="gradient-text">confident</span></h2>
        <p class="text-xs mb-4" style="color:#64748B">Three steps. No spam, no pressure.</p>
        <div class="space-y-2.5">
          <?php
          $steps = [
            ['01','🎯','Tell us about yourself','2-min quiz on commute, budget & charging.','#00A896'],
            ['02','📊','See your perfect matches','Ranked EVs with real range, subsidy price & EMI.','#0EA5E9'],
            ['03','🤝','Get the best deal','Connect with verified dealers — on your terms.','#7C3AED'],
          ];
          foreach ($steps as [$n,$em,$t,$d,$c]): ?>
          <div class="flex items-center gap-3 p-3 rounded-2xl transition-all duration-200"
               style="background:#FFFFFF;border:1px solid rgba(0,168,150,.12)"
               onmouseover="this.style.transform='translateX(4px)';this.style.boxShadow='0 6px 18px rgba(0,168,150,.1)'"
               onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="relative flex-shrink-0">
              <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl" style="background:<?= $c ?>12;border:1px solid <?= $c ?>30"><?= $em ?></div>
              <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-lg flex items-center justify-center text-[9px] font-black text-white" style="background:<?= $c ?>"><?= $n ?></span>
            </div>
            <div>
              <div class="font-black text-sm" style="color:#0F172A"><?= $t ?></div>
              <div class="text-[11px] leading-snug" style="color:#64748B"><?= $d ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="<?= base_url('find-my-ev') ?>" class="inline-flex items-center gap-2 mt-3 text-sm font-bold px-5 py-2.5 rounded-full text-white transition-all duration-200"
           style="background:#00A896" onmouseover="this.style.background='#007A6E'" onmouseout="this.style.background='#00A896'">
          Take the quiz →
        </a>
      </div>

      <!-- Testimonials — 3 cards -->
      <div class="lg:col-span-3">
        <div class="flex items-center justify-between mb-3 sr-right">
          <h2 class="text-xl font-black" style="color:#0F172A">Trusted across India</h2>
          <div class="flex items-center gap-1">
            <?php for($s=0;$s<5;$s++): ?><svg class="w-3.5 h-3.5" fill="#F59E0B" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
            <span class="text-[11px] font-bold ml-1" style="color:#64748B">4.8/5</span>
          </div>
        </div>
        <div class="grid sm:grid-cols-3 gap-3 sr-stagger">
          <?php
          $testimonials = [
            ['Rahul M.','Bangalore','R','#7C3AED',"Found the Ather 450X via the quiz. Subsidy calc showed ₹28k savings I'd have missed."],
            ['Priya S.','Mumbai','P','#0EA5E9',"Nexon EV vs ZS EV — the compare tool made it crystal clear. Nexon won on range."],
            ['Amit K.','Delhi','A','#00A896',"Fleet calculator convinced my boss to switch 15 bikes. Saving ₹45k/month on fuel."],
          ];
          foreach ($testimonials as [$nm,$ct,$av,$c,$tx]): ?>
          <div class="flex flex-col p-4 rounded-2xl transition-all duration-200"
               style="background:#FFFFFF;border:1px solid rgba(0,168,150,.1)"
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 28px rgba(0,168,150,.12)'"
               onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="flex gap-0.5 mb-2">
              <?php for($s=0;$s<5;$s++): ?><svg class="w-3 h-3" fill="#F59E0B" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
            </div>
            <p class="text-xs leading-relaxed flex-1 mb-3" style="color:#475569"><?= esc($tx) ?></p>
            <div class="flex items-center gap-2 pt-2.5" style="border-top:1px solid rgba(0,168,150,.08)">
              <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background:<?= $c ?>"><?= $av ?></div>
              <div>
                <div class="text-[11px] font-bold" style="color:#0F172A"><?= $nm ?></div>
                <div class="text-[9px]" style="color:#94A3B8"><?= $ct ?> · Verified buyer</div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  LATEST ARTICLES (conditional)
════════════════════════════════════════════════════════ -->
<?php if (!empty($latestArticles)): ?>
<section class="py-6 reveal" style="background:#F7FFFE">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-end justify-between mb-4">
      <div>
        <h2 class="text-xl font-black" style="color:#0F172A">EV News &amp; Guides</h2>
        <p class="text-xs mt-0.5" style="color:#64748B">Latest launches, reviews & policy updates</p>
      </div>
      <a href="<?= base_url('news') ?>" class="text-xs font-bold transition-colors hidden sm:block" style="color:#00A896">All articles →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sr-stagger">
      <?php foreach (array_slice($latestArticles,0,3) as $article): ?>
      <article class="card group flex flex-col overflow-hidden">
        <?php if (!empty($article['thumbnail_url'])): ?>
        <a href="<?= base_url('news/'.esc($article['slug']??'')) ?>" class="block overflow-hidden flex-shrink-0">
          <img src="<?= esc($article['thumbnail_url']) ?>"
               alt="<?= esc($article['title']??'') ?>"
               class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
        </a>
        <?php else: ?>
        <a href="<?= base_url('news/'.esc($article['slug']??'')) ?>" class="block flex-shrink-0">
          <div class="w-full h-32 flex items-center justify-center text-4xl" style="background:linear-gradient(135deg,#F0FDFA,#E0FFF9)">⚡</div>
        </a>
        <?php endif; ?>
        <div class="flex flex-col flex-1 p-4">
          <?php if (!empty($article['category'])): ?>
          <span class="badge-green text-[10px] mb-2 w-fit"><?= esc($article['category']) ?></span>
          <?php endif; ?>
          <h3 class="font-bold text-base leading-snug line-clamp-2 mb-2 flex-1" style="color:#0F172A">
            <a href="<?= base_url('news/'.esc($article['slug']??'')) ?>" class="transition-colors hover:text-[#00A896]"><?= esc($article['title']??'') ?></a>
          </h3>
          <?php if (!empty($article['excerpt'])): ?>
          <p class="text-sm line-clamp-2 leading-relaxed mb-3" style="color:#64748B"><?= esc($article['excerpt']) ?></p>
          <?php endif; ?>
          <div class="flex items-center gap-2 pt-3 text-xs mt-auto" style="color:#94A3B8;border-top:1px solid rgba(0,168,150,.07)">
            <?php if (!empty($article['published_at'])): ?>
            <time><?= date('d M Y',strtotime($article['published_at'])) ?></time>
            <?php endif; ?>
            <?php if (!empty($article['read_time'])): ?>
            <span>· <?= (int)$article['read_time'] ?> min read</span>
            <?php endif; ?>
            <a href="<?= base_url('news/'.esc($article['slug']??'')) ?>" class="ml-auto font-bold transition-colors" style="color:#00A896">Read →</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="mt-6 text-center sm:hidden">
      <a href="<?= base_url('news') ?>" class="text-sm font-bold" style="color:#00A896">View all articles →</a>
    </div>
  </div>
</section>
<div class="section-divider"></div>
<?php endif; ?>


<!-- ════════════════════════════════════════════════════════
  BRAND PARTNER + CTA DASHBOARD
════════════════════════════════════════════════════════ -->
<style>
@keyframes dashIn { 0%{opacity:0;transform:translateX(-16px)} 100%{opacity:1;transform:none} }
@keyframes pulseGlow { 0%,100%{box-shadow:0 0 0 0 rgba(0,168,150,.3)} 50%{box-shadow:0 0 0 8px rgba(0,168,150,.0)} }
.dash-metric { animation:dashIn .5s cubic-bezier(.22,1,.36,1) both; }
</style>
<section class="py-5 reveal" style="background:linear-gradient(135deg,#00A896 0%,#007A6E 50%,#00BFA5 100%)">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">

      <!-- Left: pitch -->
      <div class="flex items-center gap-3.5 sr-left">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl flex-shrink-0" style="background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.2)">🔋</div>
        <div>
          <div class="text-white font-black text-base leading-tight">Still deciding which EV to buy?</div>
          <div class="text-xs mt-0.5" style="color:rgba(255,255,255,.7)">Get a personalised match in 2 minutes — free, no spam.</div>
        </div>
      </div>

      <!-- Right: dual CTAs -->
      <div class="flex items-center gap-2.5 flex-shrink-0 sr-right">
        <a href="<?= base_url('find-my-ev') ?>"
           onclick="charjTrack('final_cta_click',{button:'finder'})"
           class="inline-flex items-center gap-2 font-bold text-sm px-5 py-2.5 rounded-full transition-all duration-200"
           style="background:#FFFFFF;color:#007A6E;box-shadow:0 4px 16px rgba(0,0,0,.12)"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
          Find My EV
        </a>
        <a href="<?= base_url('for-brands') ?>"
           class="hidden sm:inline-flex items-center gap-1.5 font-bold text-sm px-4 py-2.5 rounded-full transition-all duration-200"
           style="background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.25)"
           onmouseover="this.style.background='rgba(255,255,255,.24)'" onmouseout="this.style.background='rgba(255,255,255,.14)'">
          For Brands
        </a>
      </div>

    </div>
  </div>
</section>

<div class="pb-4 md:pb-0"></div>

<?= $this->endSection() ?>

