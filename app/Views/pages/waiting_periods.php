<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'EV Waiting Periods India | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? '') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div x-data="waitData()" class="pb-14" style="background:#F7FFFE">

  <!-- Hero -->
  <div class="hero-sm relative overflow-hidden pt-24 pb-8 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:26px 26px;opacity:.6"></div>
    <div class="relative max-w-3xl mx-auto text-center">
      <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-3"
           style="background:rgba(0,168,150,.1);border:1.5px solid rgba(0,168,150,.2);color:#00A896">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Delivery Timelines · 2026
      </div>
      <h1 class="text-2xl sm:text-4xl font-black leading-tight mb-2" style="color:#0F172A">
        EV <span class="hero-gradient-text">waiting periods</span>
      </h1>
      <p class="max-w-xl mx-auto text-sm sm:text-base mb-5" style="color:#475569">
        How long before your EV actually arrives. Typical booking-to-delivery windows for popular models across India.
      </p>

      <!-- Stat strip — data-driven trust signal, not just a static table -->
      <div class="flex items-center justify-center gap-2 flex-wrap">
        <div class="rounded-2xl px-5 py-3 text-center" style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.16);box-shadow:0 2px 10px rgba(0,168,150,.08)">
          <div class="text-xl font-black" style="color:#0F172A" x-text="rows.length"></div>
          <div class="text-[10px] font-bold uppercase tracking-wide" style="color:#94A3B8">Models Tracked</div>
        </div>
        <div class="rounded-2xl px-5 py-3 text-center" style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.16);box-shadow:0 2px 10px rgba(0,168,150,.08)">
          <div class="text-xl font-black" style="color:#00963C" x-text="fastest.wait"></div>
          <div class="text-[10px] font-bold uppercase tracking-wide" style="color:#94A3B8">Fastest Delivery</div>
        </div>
        <div class="rounded-2xl px-5 py-3 text-center" style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.16);box-shadow:0 2px 10px rgba(0,168,150,.08)">
          <div class="text-xl font-black" style="color:#ef4444" x-text="slowest.wait"></div>
          <div class="text-[10px] font-bold uppercase tracking-wide" style="color:#94A3B8">Longest Wait</div>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 mt-6">

    <!-- Filter -->
    <div class="flex flex-wrap items-center gap-2 mb-4">
      <div class="relative flex-1 min-w-[180px]">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color:#94A3B8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 8v6M8 11h6M19 11a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
        <input type="text" x-model="q" placeholder="Search model or brand…"
               class="w-full rounded-xl pl-9 pr-3 py-2.5 text-sm outline-none"
               style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.2);color:#0F172A">
      </div>
      <?php foreach (['all'=>'All','2w'=>'🛵 2W','4w'=>'🚗 4W'] as $k=>$lbl): ?>
      <button @click="cat='<?= $k ?>'"
              class="text-xs font-bold px-3.5 py-2.5 rounded-xl transition-all"
              :style="cat==='<?= $k ?>' ? 'background:#00A896;color:#fff;box-shadow:0 3px 10px rgba(0,168,150,.3)' : 'background:#FFFFFF;color:#00A896;border:1px solid rgba(0,168,150,.2)'"><?= $lbl ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Result count -->
    <p class="text-xs font-semibold mb-3" style="color:#94A3B8">Showing <span x-text="filtered.length" class="font-black" style="color:#00A896"></span> of <span x-text="rows.length"></span> models</p>

    <!-- Cards -->
    <div class="grid gap-2.5">
      <template x-for="(ev,i) in filtered" :key="ev.slug">
        <div class="station-row flex items-center gap-3.5 px-3.5 py-3 cursor-pointer"
             style="background:#FFFFFF"
             @click="window.location.href = '/vehicles/' + ev.slug">

          <!-- Thumbnail: real photo if available, otherwise a branded fallback tile -->
          <div class="w-14 h-14 rounded-xl flex-shrink-0 overflow-hidden flex items-center justify-center"
               style="background:linear-gradient(135deg,rgba(0,168,150,.14),rgba(0,230,118,.06))">
            <template x-if="ev.img">
              <img :src="ev.img" :alt="ev.name" class="w-full h-full object-cover" loading="lazy"
                   @error="ev.img = null">
            </template>
            <template x-if="!ev.img">
              <span class="text-2xl" x-text="ev.emoji"></span>
            </template>
          </div>

          <div class="min-w-0 flex-1">
            <div class="text-sm font-black truncate" style="color:#0F172A" x-text="ev.name"></div>
            <div class="text-xs mt-0.5" style="color:#94A3B8" x-text="ev.brand"></div>
          </div>

          <div class="flex-shrink-0 text-center hidden sm:block" style="min-width:80px">
            <span class="text-[10px] font-bold" x-text="ev.demand"
                  :style="ev.weeks<=2 ? 'color:#00963C' : (ev.weeks<=6 ? 'color:#D97706' : 'color:#ef4444')"></span>
          </div>

          <span class="text-xs font-black px-3 py-1.5 rounded-full flex-shrink-0"
                :style="ev.weeks<=2 ? 'background:rgba(0,168,150,.12);color:#00963C' : (ev.weeks<=6 ? 'background:rgba(245,158,11,.12);color:#D97706' : 'background:rgba(239,68,68,.1);color:#ef4444')"
                x-text="ev.wait"></span>

          <a :href="'/vehicles/' + ev.slug" @click.stop class="hidden sm:inline-flex items-center gap-1 text-xs font-black flex-shrink-0" style="color:#00A896">
            View
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </template>
      <div x-show="filtered.length===0" class="rounded-2xl px-4 py-12 text-center text-sm" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);color:#94A3B8">No models match your search.</div>
    </div>

    <!-- Legend + note -->
    <div class="flex flex-wrap items-center gap-4 mt-5 px-4 py-3 rounded-2xl text-[11px] font-semibold" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.12);color:#64748B">
      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background:#00963C"></span>In stock / fast</span>
      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background:#D97706"></span>Moderate wait</span>
      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background:#ef4444"></span>Long wait</span>
    </div>
    <p class="text-[11px] mt-3 flex items-start gap-1.5" style="color:#94A3B8">
      <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
      Indicative waiting periods compiled from dealer feedback and owner reports — actual delivery varies by city, variant, colour and demand. Confirm with your dealer at the time of booking.
    </p>

    <!-- CTA -->
    <div class="mt-5 rounded-2xl p-5 text-center text-white" style="background:linear-gradient(135deg,#00A896,#007A6E)">
      <div class="text-base font-black mb-1">Want one with no waiting?</div>
      <p class="text-xs mb-3" style="color:rgba(255,255,255,.8)">Use the EV Finder to match in-stock models to your budget &amp; needs.</p>
      <a href="<?= base_url('find-my-ev') ?>" class="inline-flex items-center gap-1.5 bg-white font-bold text-xs px-5 py-2.5 rounded-full" style="color:#007A6E">
        Find My EV →
      </a>
    </div>
  </div>
</div>

<script>
function waitData() {
  return {
    q: '', cat: 'all',
    rows: [
      {name:'Ola S1 Pro',brand:'Ola Electric',slug:'ola-s1-pro',type:'2w',emoji:'🛵',wait:'1–2 wks',weeks:2,demand:'High',img:'/assets/images/vehicles/ola-s1-pro/rear-studio.jpg'},
      {name:'Ather 450X',brand:'Ather Energy',slug:'ather-450x',type:'2w',emoji:'🛵',wait:'3–5 wks',weeks:5,demand:'High',img:'/assets/images/vehicles/ather-450x/front-action.jpg'},
      {name:'TVS iQube',brand:'TVS Motor',slug:'tvs-iqube',type:'2w',emoji:'🛵',wait:'2–4 wks',weeks:4,demand:'Steady',img:'/assets/images/vehicles/tvs-iqube/front.jpg'},
      {name:'Bajaj Chetak',brand:'Bajaj Auto',slug:'bajaj-chetak',type:'2w',emoji:'🛵',wait:'2–3 wks',weeks:3,demand:'Steady',img:'/assets/images/vehicles/bajaj-chetak/front-three-quarter.jpg'},
      {name:'Ather Rizta',brand:'Ather Energy',slug:'ather-rizta',type:'2w',emoji:'🛵',wait:'6–10 wks',weeks:10,demand:'Very High',img:'/assets/images/vehicles/ather-rizta/front-three-quarter.jpg'},
      {name:'Ola S1 Air',brand:'Ola Electric',slug:'ola-s1-air',type:'2w',emoji:'🛵',wait:'1–2 wks',weeks:2,demand:'In stock',img:'/assets/images/vehicles/ola-s1-air/front-three-quarter.jpg'},
      {name:'Hero Vida V1',brand:'Hero MotoCorp',slug:'hero-vida-v1',type:'2w',emoji:'🛵',wait:'1–2 wks',weeks:2,demand:'In stock',img:'/assets/images/vehicles/hero-vida-v1/front-three-quarter.jpg'},
      {name:'Tata Nexon EV',brand:'Tata Motors',slug:'tata-nexon-ev',type:'4w',emoji:'🚗',wait:'4–8 wks',weeks:8,demand:'High',img:'/assets/images/vehicles/tata-nexon-ev/front-dark.jpg'},
      {name:'Tata Punch EV',brand:'Tata Motors',slug:'tata-punch-ev',type:'4w',emoji:'🚗',wait:'5–9 wks',weeks:9,demand:'High',img:'/assets/images/vehicles/tata-punch-ev/front-three-quarter.jpg'},
      {name:'Tata Tiago EV',brand:'Tata Motors',slug:'tata-tiago-ev',type:'4w',emoji:'🚗',wait:'3–6 wks',weeks:6,demand:'Steady',img:'/assets/images/vehicles/tata-tiago-ev/front-three-quarter.jpg'},
      {name:'MG ZS EV',brand:'MG Motor',slug:'mg-zs-ev',type:'4w',emoji:'🚗',wait:'4–7 wks',weeks:7,demand:'Steady',img:'/assets/images/vehicles/mg-zs-ev/front-1.jpg'},
      {name:'MG Comet EV',brand:'MG Motor',slug:'mg-comet-ev',type:'4w',emoji:'🚗',wait:'2–4 wks',weeks:4,demand:'In stock',img:'/assets/images/vehicles/mg-comet-ev/front-three-quarter.jpg'},
      {name:'Mahindra XUV400',brand:'Mahindra',slug:'mahindra-xuv400',type:'4w',emoji:'🚗',wait:'3–6 wks',weeks:6,demand:'Steady',img:'/assets/images/vehicles/mahindra-xuv400/front-view.jpg'},
      {name:'Tata Curvv EV',brand:'Tata Motors',slug:'tata-curvv-ev',type:'4w',emoji:'🚗',wait:'8–12 wks',weeks:12,demand:'Very High',img:'/assets/images/vehicles/tata-curvv-ev/front-three-quarter.jpg'},
    ],
    get filtered() {
      var q = this.q.toLowerCase(), c = this.cat;
      return this.rows.filter(function(r){
        var mc = c==='all' || r.type===c;
        var mq = !q || r.name.toLowerCase().includes(q) || r.brand.toLowerCase().includes(q);
        return mc && mq;
      });
    },
    get fastest() {
      return this.rows.reduce(function(a,b){ return a.weeks <= b.weeks ? a : b; });
    },
    get slowest() {
      return this.rows.reduce(function(a,b){ return a.weeks >= b.weeks ? a : b; });
    }
  };
}
</script>

<?= $this->endSection() ?>
