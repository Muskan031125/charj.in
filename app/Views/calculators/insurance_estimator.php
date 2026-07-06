<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'EV Insurance Calculator | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Estimate your annual EV insurance premium online. IDV, own-damage, third-party, NCB & add-ons — with the IRDAI EV discount applied. Free Charj.in calculator.') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="min-h-screen" style="background:#F5FFF7">

  <!-- Hero -->
  <div class="hero-sm pt-24 pb-8" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="mx-auto max-w-3xl px-4 text-center">
      <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold mb-5" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.5l7 3v4.75c0 4.27-2.93 8.27-7 9.25-4.07-.98-7-4.98-7-9.25V4.5l7-3zm3.03 6.22a.75.75 0 00-1.06-1.06L9 9.63 8.03 8.66a.75.75 0 10-1.06 1.06l1.5 1.5c.3.3.77.3 1.06 0l3.5-3.5z" clip-rule="evenodd"/></svg>
        EV Insurance Estimator
      </span>
      <h1 class="text-2xl sm:text-4xl font-black" style="color:#0F172A">EV Insurance Premium Estimator</h1>
      <p class="mt-3 text-base" style="color:#475569">Estimate your annual electric-vehicle insurance premium — IDV, own-damage, third-party, NCB & add-ons, with the IRDAI EV discount built in.</p>
    </div>
  </div>

  <!-- Content -->
  <div class="mx-auto max-w-6xl px-4 pb-16 -mt-8" x-data="insCalc()">
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">

      <!-- Inputs card -->
      <div class="rounded-3xl bg-white shadow-sm overflow-hidden" style="border:1px solid rgba(0,200,100,.15)">
        <div class="p-6 space-y-7">

          <!-- Ex-showroom price -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="text-sm font-bold" style="color:#0F172A">Ex-showroom price</label>
              <span class="text-sm font-black" style="color:#00A896" x-text="fmt(price)"></span>
            </div>
            <input type="range" min="40000" max="3000000" step="1000" x-model.number="price" class="w-full" style="accent-color:#00A896">
            <div class="flex justify-between text-[11px] mt-1" style="color:#94A3B8"><span>₹40K</span><span>₹30L</span></div>
            <div class="flex flex-wrap gap-2 mt-3">
              <template x-for="ev in popularEvs" :key="ev.name">
                <button type="button" @click="price = ev.price; type = ev.type"
                  class="px-3 py-1.5 rounded-full text-xs font-semibold transition"
                  :style="price==ev.price ? 'background:#00A896;color:#fff;border:1px solid #00A896' : 'background:#F0FFF9;color:#475569;border:1px solid rgba(0,168,150,.2)'"
                  x-text="ev.name"></button>
              </template>
            </div>
          </div>

          <!-- Vehicle type -->
          <div>
            <label class="text-sm font-bold block mb-2" style="color:#0F172A">Vehicle type</label>
            <div class="grid grid-cols-3 gap-2">
              <template x-for="t in ['2W','3W','4W']" :key="t">
                <button type="button" @click="type = t"
                  class="py-2.5 rounded-xl text-sm font-bold transition"
                  :style="type==t ? 'background:linear-gradient(135deg,#00A896,#00E676);color:#fff;border:1px solid transparent' : 'background:#F7FFFE;color:#475569;border:1px solid rgba(0,168,150,.18)'"
                  x-text="t"></button>
              </template>
            </div>
          </div>

          <!-- Vehicle age -->
          <div>
            <label class="text-sm font-bold block mb-2" style="color:#0F172A">Vehicle age <span class="font-normal" style="color:#94A3B8">(affects IDV)</span></label>
            <div class="grid grid-cols-5 gap-2">
              <template x-for="a in ages" :key="a.k">
                <button type="button" @click="age = a.k"
                  class="py-2.5 rounded-xl text-xs font-bold transition"
                  :style="age==a.k ? 'background:#00A896;color:#fff;border:1px solid #00A896' : 'background:#F7FFFE;color:#475569;border:1px solid rgba(0,168,150,.18)'"
                  x-text="a.label"></button>
              </template>
            </div>
          </div>

          <!-- Policy type -->
          <div>
            <label class="text-sm font-bold block mb-2" style="color:#0F172A">Policy type</label>
            <div class="grid grid-cols-2 gap-2">
              <button type="button" @click="policy='comp'"
                class="py-2.5 rounded-xl text-sm font-bold transition"
                :style="policy=='comp' ? 'background:linear-gradient(135deg,#00A896,#00E676);color:#fff;border:1px solid transparent' : 'background:#F7FFFE;color:#475569;border:1px solid rgba(0,168,150,.18)'">Comprehensive</button>
              <button type="button" @click="policy='tp'"
                class="py-2.5 rounded-xl text-sm font-bold transition"
                :style="policy=='tp' ? 'background:linear-gradient(135deg,#00A896,#00E676);color:#fff;border:1px solid transparent' : 'background:#F7FFFE;color:#475569;border:1px solid rgba(0,168,150,.18)'">Third-Party only</button>
            </div>
          </div>

          <!-- Add-ons (comprehensive only) -->
          <div x-show="policy=='comp'" x-transition>
            <label class="text-sm font-bold block mb-2" style="color:#0F172A">Add-on covers</label>
            <div class="grid sm:grid-cols-2 gap-2">
              <template x-for="ad in addonList" :key="ad.k">
                <label class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl cursor-pointer transition"
                  :style="addons[ad.k] ? 'background:#EAFFF4;border:1px solid rgba(0,168,150,.35)' : 'background:#F7FFFE;border:1px solid rgba(0,168,150,.15)'">
                  <input type="checkbox" x-model="addons[ad.k]" style="accent-color:#00A896;width:16px;height:16px">
                  <span class="text-xs font-semibold" style="color:#475569" x-text="ad.label"></span>
                </label>
              </template>
            </div>
          </div>

          <!-- NCB -->
          <div x-show="policy=='comp'" x-transition>
            <label class="text-sm font-bold block mb-2" style="color:#0F172A">No Claim Bonus (NCB) discount</label>
            <select x-model.number="ncb" class="w-full rounded-xl px-3 py-2.5 text-sm font-semibold" style="background:#F7FFFE;color:#0F172A;border:1px solid rgba(0,168,150,.2)">
              <option :value="0">0% (claimed last year)</option>
              <option :value="20">20% (1 claim-free year)</option>
              <option :value="25">25% (2 years)</option>
              <option :value="35">35% (3 years)</option>
              <option :value="45">45% (4 years)</option>
              <option :value="50">50% (5+ years)</option>
            </select>
          </div>

        </div>
      </div>

      <!-- Result column -->
      <div class="space-y-6">

        <!-- Result card -->
        <div class="rounded-3xl shadow-sm overflow-hidden text-white" style="background:linear-gradient(135deg,#00A896 0%,#00E676 100%)">
          <div class="p-6">
            <p class="text-xs font-semibold uppercase tracking-wide" style="color:rgba(255,255,255,.85)">Estimated annual premium</p>
            <p class="text-4xl font-black mt-1" x-text="fmt(total)"></p>
            <div class="mt-3 flex items-center justify-between text-sm" style="color:rgba(255,255,255,.9)">
              <span>IDV (insured value)</span>
              <span class="font-bold" x-text="fmt(idv)"></span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="background:rgba(255,255,255,.2)">
              <span>EV discount applied</span><span>&#10003;</span>
            </div>
          </div>
        </div>

        <!-- Breakup card -->
        <div class="rounded-3xl bg-white shadow-sm overflow-hidden" style="border:1px solid rgba(0,200,100,.15)">
          <div class="px-6 py-4" style="border-bottom:1px solid rgba(0,168,150,.12)">
            <h3 class="text-sm font-black" style="color:#0F172A">Premium breakup</h3>
          </div>
          <div class="p-6 space-y-2.5 text-sm">
            <div class="flex justify-between"><span style="color:#64748B">IDV (insured value)</span><span class="font-bold" style="color:#0F172A" x-text="fmt(idv)"></span></div>
            <template x-if="policy=='comp'">
              <div class="space-y-2.5">
                <div class="flex justify-between"><span style="color:#64748B">Own-Damage premium</span><span class="font-bold" style="color:#0F172A" x-text="fmt(od)"></span></div>
                <div class="flex justify-between" x-show="ncb>0"><span style="color:#64748B" x-text="'NCB discount ('+ncb+'%)'"></span><span class="font-bold" style="color:#00A896" x-text="'- '+fmt(ncbAmt)"></span></div>
                <div class="flex justify-between"><span style="color:#64748B">Third-Party premium</span><span class="font-bold" style="color:#0F172A" x-text="fmt(tp)"></span></div>
                <template x-for="ad in addonList" :key="ad.k">
                  <div class="flex justify-between" x-show="addons[ad.k]"><span style="color:#64748B" x-text="ad.label"></span><span class="font-bold" style="color:#0F172A" x-text="fmt(addonAmt(ad.k))"></span></div>
                </template>
              </div>
            </template>
            <template x-if="policy=='tp'">
              <div class="flex justify-between"><span style="color:#64748B">Third-Party premium</span><span class="font-bold" style="color:#0F172A" x-text="fmt(tp)"></span></div>
            </template>
            <div class="flex justify-between"><span style="color:#64748B">GST (18%)</span><span class="font-bold" style="color:#0F172A" x-text="fmt(gst)"></span></div>
            <div class="flex justify-between pt-2.5" style="border-top:1px solid rgba(0,168,150,.15)"><span class="font-black" style="color:#0F172A">Total annual premium</span><span class="font-black" style="color:#00A896" x-text="fmt(total)"></span></div>
          </div>
        </div>

      </div>
    </div>

    <!-- Lead capture strip -->
    <div class="mt-8 rounded-3xl overflow-hidden text-white" style="background:linear-gradient(135deg,#00A896 0%,#00C9A7 60%,#00E676 100%)">
      <div class="p-6 sm:p-8 grid gap-6 lg:grid-cols-[1fr_1.4fr] items-center">
        <div>
          <h3 class="text-xl font-black">Get exact quotes from insurers</h3>
          <p class="mt-2 text-sm" style="color:rgba(255,255,255,.9)">This is an indicative estimate. Share your details and our team will fetch real, comparable EV insurance quotes for your city and vehicle.</p>
        </div>
        <form action="<?= base_url('lead/submit') ?>" method="POST" class="grid sm:grid-cols-2 gap-3">
          <?= csrf_field() ?>
          <input type="hidden" name="lead_type" value="insurance_quote">
          <input type="hidden" name="source_url" value="<?= esc(current_url(), 'attr') ?>">
          <input type="text" name="name" placeholder="Your name" class="rounded-xl px-4 py-3 text-sm" style="background:#fff;color:#0F172A;border:none">
          <input type="tel" name="mobile" placeholder="Mobile number" required class="rounded-xl px-4 py-3 text-sm" style="background:#fff;color:#0F172A;border:none">
          <input type="email" name="email" placeholder="Email (optional)" class="rounded-xl px-4 py-3 text-sm sm:col-span-2" style="background:#fff;color:#0F172A;border:none">
          <button type="submit" class="rounded-xl px-4 py-3 text-sm font-black sm:col-span-2" style="background:#0F172A;color:#fff">Get my quotes &rarr;</button>
        </form>
      </div>
    </div>

    <!-- Disclaimer -->
    <p class="mt-6 text-xs leading-relaxed text-center mx-auto max-w-3xl" style="color:#94A3B8">
      Indicative estimate only. Actual premiums vary by insurer, city/RTO, exact IDV, vehicle make/model, claim history and chosen add-ons. The IRDAI-permitted EV concession (~15% on own-damage and third-party) is applied here for illustration. Please confirm final pricing with the insurer before purchase.
    </p>

    <!-- Cross-links -->
    <div class="mt-6 flex flex-wrap justify-center gap-3 text-sm font-semibold">
      <a href="<?= base_url('ev-insurance') ?>" class="px-4 py-2 rounded-full" style="background:#F0FFF9;color:#00A896;border:1px solid rgba(0,168,150,.2)">EV Insurance Guide</a>
      <a href="<?= base_url('ev-finance') ?>" class="px-4 py-2 rounded-full" style="background:#F0FFF9;color:#00A896;border:1px solid rgba(0,168,150,.2)">EV Finance</a>
      <a href="<?= base_url('on-road-price') ?>" class="px-4 py-2 rounded-full" style="background:#F0FFF9;color:#00A896;border:1px solid rgba(0,168,150,.2)">On-Road Price</a>
    </div>

  </div>
</div>

<script>
function insCalc(){
  return {
    price: 130000,
    type: '2W',
    age: 'new',
    policy: 'comp',
    ncb: 0,
    addons: { zerodep:false, rsa:false, engine:false, ncbp:false, consum:false },
    popularEvs: [
      { name:'Ather 450X',  price:139000,  type:'2W' },
      { name:'Ola S1 Pro',  price:139999,  type:'2W' },
      { name:'Tiago EV',    price:849000,  type:'4W' },
      { name:'Nexon EV',    price:1449000, type:'4W' }
    ],
    ages: [
      { k:'new', label:'New' },
      { k:'1',   label:'1yr' },
      { k:'2',   label:'2yr' },
      { k:'3',   label:'3yr' },
      { k:'4',   label:'4yr+' }
    ],
    addonList: [
      { k:'zerodep', label:'Zero Depreciation' },
      { k:'rsa',     label:'Roadside Assistance' },
      { k:'engine',  label:'Engine/Battery Protect' },
      { k:'ncbp',    label:'NCB Protection' },
      { k:'consum',  label:'Consumables' }
    ],
    EV_DISC: 0.85, // ~15% EV concession (IRDAI)

    get depFactor(){
      return ({ 'new':0.95, '1':0.85, '2':0.80, '3':0.70, '4':0.60 })[this.age] || 0.95;
    },
    get idv(){ return Math.round(Number(this.price) * this.depFactor); },
    get odRate(){ return this.type==='4W' ? 0.028 : 0.022; },
    // Own-Damage base premium, with EV discount
    get od(){
      if(this.policy!=='comp') return 0;
      return Math.round(this.idv * this.odRate * this.EV_DISC);
    },
    get ncbAmt(){ return this.policy==='comp' ? Math.round(this.od * (this.ncb/100)) : 0; },
    get odNet(){ return Math.max(0, this.od - this.ncbAmt); },
    // Third-Party premium, flat by type, with EV discount
    get tp(){
      const base = ({ '2W':750, '3W':2500, '4W':2900 })[this.type] || 750;
      return Math.round(base * this.EV_DISC);
    },
    addonAmt(k){
      if(this.policy!=='comp' || !this.addons[k]) return 0;
      switch(k){
        case 'zerodep': return Math.round(this.od * 0.15);
        case 'rsa':     return 300;
        case 'engine':  return Math.round(this.od * 0.08);
        case 'ncbp':    return 500;
        case 'consum':  return 600;
        default:        return 0;
      }
    },
    get addonsTotal(){
      return this.addonList.reduce((s,a)=> s + this.addonAmt(a.k), 0);
    },
    get subtotal(){
      if(this.policy==='tp') return this.tp;
      return this.odNet + this.tp + this.addonsTotal;
    },
    get gst(){ return Math.round(this.subtotal * 0.18); },
    get total(){ return this.subtotal + this.gst; },

    fmt(v){ return '₹' + Math.round(Number(v)||0).toLocaleString('en-IN'); }
  };
}
</script>

<?= $this->endSection() ?>
