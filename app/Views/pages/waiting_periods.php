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
      <p class="max-w-xl mx-auto text-sm sm:text-base" style="color:#475569">
        How long before your EV actually arrives. Typical booking-to-delivery windows for popular models across India.
      </p>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 mt-6">

    <!-- Filter -->
    <div class="flex flex-wrap items-center gap-2 mb-4">
      <input type="text" x-model="q" placeholder="Search model or brand…"
             class="flex-1 min-w-[180px] rounded-xl px-3 py-2 text-sm outline-none"
             style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.2);color:#0F172A">
      <?php foreach (['all'=>'All','2w'=>'🛵 2W','4w'=>'🚗 4W'] as $k=>$lbl): ?>
      <button @click="cat='<?= $k ?>'"
              class="text-xs font-bold px-3 py-2 rounded-xl transition-all"
              :style="cat==='<?= $k ?>' ? 'background:#00A896;color:#fff' : 'background:#FFFFFF;color:#00A896;border:1px solid rgba(0,168,150,.2)'"><?= $lbl ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Table -->
    <div class="rounded-2xl overflow-hidden" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14)">
      <div class="grid grid-cols-12 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest" style="background:rgba(0,168,150,.05);color:#00A896;border-bottom:1px solid rgba(0,168,150,.1)">
        <div class="col-span-6 sm:col-span-5">Model</div>
        <div class="col-span-3 sm:col-span-3 text-center">Waiting</div>
        <div class="hidden sm:block sm:col-span-2 text-center">Demand</div>
        <div class="col-span-3 sm:col-span-2 text-right">Action</div>
      </div>
      <template x-for="(ev,i) in filtered" :key="ev.slug">
        <div class="grid grid-cols-12 items-center px-4 py-3 transition-all duration-150"
             :style="i < filtered.length-1 ? 'border-bottom:1px solid rgba(0,168,150,.07)' : ''"
             onmouseover="this.style.background='rgba(0,168,150,.03)'" onmouseout="this.style.background=''">
          <div class="col-span-6 sm:col-span-5 flex items-center gap-2.5 min-w-0">
            <span class="text-lg flex-shrink-0" x-text="ev.emoji"></span>
            <div class="min-w-0">
              <div class="text-xs font-black truncate" style="color:#0F172A" x-text="ev.name"></div>
              <div class="text-[10px]" style="color:#94A3B8" x-text="ev.brand"></div>
            </div>
          </div>
          <div class="col-span-3 sm:col-span-3 text-center">
            <span class="text-xs font-black px-2 py-1 rounded-full"
                  :style="ev.weeks<=2 ? 'background:rgba(0,168,150,.12);color:#00963C' : (ev.weeks<=6 ? 'background:rgba(245,158,11,.12);color:#D97706' : 'background:rgba(239,68,68,.1);color:#ef4444')"
                  x-text="ev.wait"></span>
          </div>
          <div class="hidden sm:flex sm:col-span-2 justify-center">
            <span class="text-[10px] font-bold" x-text="ev.demand"
                  :style="ev.weeks<=2 ? 'color:#00963C' : (ev.weeks<=6 ? 'color:#D97706' : 'color:#ef4444')"></span>
          </div>
          <div class="col-span-3 sm:col-span-2 text-right">
            <a :href="'/vehicles/' + ev.slug" class="text-[11px] font-black" style="color:#00A896">View →</a>
          </div>
        </div>
      </template>
      <div x-show="filtered.length===0" class="px-4 py-8 text-center text-sm" style="color:#94A3B8">No models match your search.</div>
    </div>

    <!-- Legend + note -->
    <div class="flex flex-wrap items-center gap-3 mt-3 text-[11px]" style="color:#64748B">
      <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#00963C"></span>In stock / fast</span>
      <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#D97706"></span>Moderate wait</span>
      <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#ef4444"></span>Long wait</span>
    </div>
    <p class="text-[11px] mt-3" style="color:#94A3B8">
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
      {name:'Ola S1 Pro',brand:'Ola Electric',slug:'ola-s1-pro',type:'2w',emoji:'🛵',wait:'1–2 wks',weeks:2,demand:'High'},
      {name:'Ather 450X',brand:'Ather Energy',slug:'ather-450x',type:'2w',emoji:'🛵',wait:'3–5 wks',weeks:5,demand:'High'},
      {name:'TVS iQube',brand:'TVS Motor',slug:'tvs-iqube',type:'2w',emoji:'🛵',wait:'2–4 wks',weeks:4,demand:'Steady'},
      {name:'Bajaj Chetak',brand:'Bajaj Auto',slug:'bajaj-chetak',type:'2w',emoji:'🛵',wait:'2–3 wks',weeks:3,demand:'Steady'},
      {name:'Ather Rizta',brand:'Ather Energy',slug:'ather-rizta',type:'2w',emoji:'🛵',wait:'6–10 wks',weeks:10,demand:'Very High'},
      {name:'Ola S1 Air',brand:'Ola Electric',slug:'ola-s1-air',type:'2w',emoji:'🛵',wait:'1–2 wks',weeks:2,demand:'In stock'},
      {name:'Hero Vida V1',brand:'Hero MotoCorp',slug:'hero-vida-v1',type:'2w',emoji:'🛵',wait:'1–2 wks',weeks:2,demand:'In stock'},
      {name:'Tata Nexon EV',brand:'Tata Motors',slug:'tata-nexon-ev',type:'4w',emoji:'🚗',wait:'4–8 wks',weeks:8,demand:'High'},
      {name:'Tata Punch EV',brand:'Tata Motors',slug:'tata-punch-ev',type:'4w',emoji:'🚗',wait:'5–9 wks',weeks:9,demand:'High'},
      {name:'Tata Tiago EV',brand:'Tata Motors',slug:'tata-tiago-ev',type:'4w',emoji:'🚗',wait:'3–6 wks',weeks:6,demand:'Steady'},
      {name:'MG ZS EV',brand:'MG Motor',slug:'mg-zs-ev',type:'4w',emoji:'🚗',wait:'4–7 wks',weeks:7,demand:'Steady'},
      {name:'MG Comet EV',brand:'MG Motor',slug:'mg-comet-ev',type:'4w',emoji:'🚗',wait:'2–4 wks',weeks:4,demand:'In stock'},
      {name:'Mahindra XUV400',brand:'Mahindra',slug:'mahindra-xuv400',type:'4w',emoji:'🚗',wait:'3–6 wks',weeks:6,demand:'Steady'},
      {name:'Tata Curvv EV',brand:'Tata Motors',slug:'tata-curvv-ev',type:'4w',emoji:'🚗',wait:'8–12 wks',weeks:12,demand:'Very High'},
    ],
    get filtered() {
      var q = this.q.toLowerCase(), c = this.cat;
      return this.rows.filter(function(r){
        var mc = c==='all' || r.type===c;
        var mq = !q || r.name.toLowerCase().includes(q) || r.brand.toLowerCase().includes(q);
        return mc && mq;
      });
    }
  };
}
</script>

<?= $this->endSection() ?>
