<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'EV On-Road Price Calculator | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? '') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
[x-cloak]{display:none!important}
input[type=range]{-webkit-appearance:none;appearance:none;height:6px;border-radius:9999px;background:linear-gradient(to right,#00A896 var(--pct,30%),#e2e8f0 var(--pct,30%));outline:none;cursor:pointer}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:#fff;border:2.5px solid #00A896;box-shadow:0 2px 8px rgba(0,168,150,.35);cursor:pointer;transition:transform .15s}
input[type=range]::-webkit-slider-thumb:hover{transform:scale(1.18)}
.orp-row{display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid rgba(0,168,150,.08)}
@keyframes orpUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.orp-anim{animation:orpUp .35s cubic-bezier(.22,1,.36,1) both}
</style>

<div x-data="onRoadCalc()" x-init="init()" class="pb-14" style="background:linear-gradient(180deg,#F0FFF9,#F7FFFE 320px)">

  <!-- Hero -->
  <div class="hero-sm relative overflow-hidden pt-24 pb-8 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:26px 26px;opacity:.6"></div>
    <div class="relative max-w-3xl mx-auto text-center">
      <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-3"
           style="background:rgba(0,168,150,.1);border:1.5px solid rgba(0,168,150,.2);color:#00A896">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m3 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H10a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        On-Road Price · 2026
      </div>
      <h1 class="text-2xl sm:text-4xl font-black leading-tight mb-2" style="color:#0F172A">
        The <span class="hero-gradient-text">real price</span> of your EV
      </h1>
      <p class="max-w-xl mx-auto text-sm sm:text-base" style="color:#475569">
        Ex-showroom + road tax + registration + insurance — with <strong style="color:#00A896">PM E-DRIVE</strong> &amp; your state subsidy auto-deducted. No surprises at the dealer.
      </p>
    </div>
  </div>

  <!-- Calculator -->
  <div class="max-w-5xl mx-auto px-4 mt-6">
    <div class="grid lg:grid-cols-5 gap-4">

      <!-- ── Inputs ── -->
      <div class="lg:col-span-3 rounded-2xl p-5" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14);box-shadow:0 2px 16px rgba(0,168,150,.06)">

        <!-- Ex-showroom price -->
        <div class="mb-5">
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-sm font-bold" style="color:#0F172A">Ex-showroom price</label>
            <span class="text-base font-black" style="color:#00A896">₹<span x-text="fmt(exPrice)"></span></span>
          </div>
          <input type="range" min="40000" max="3000000" step="5000" x-model.number="exPrice" @input="updTrack($event)">
          <div class="flex justify-between text-[10px] mt-1" style="color:#94A3B8"><span>₹40k</span><span>₹30L</span></div>
          <!-- Quick-pick popular EVs -->
          <div class="flex flex-wrap gap-1.5 mt-3">
            <template x-for="ev in quickEvs" :key="ev.n">
              <button type="button" @click="exPrice=ev.p;vType=ev.t;syncTrack()"
                      class="text-[10px] font-bold px-2.5 py-1 rounded-full transition-all"
                      :style="exPrice===ev.p ? 'background:#00A896;color:#fff' : 'background:rgba(0,168,150,.08);color:#00A896;border:1px solid rgba(0,168,150,.15)'"
                      x-text="ev.n"></button>
            </template>
          </div>
        </div>

        <!-- Vehicle type -->
        <div class="mb-5">
          <label class="text-sm font-bold mb-2 block" style="color:#0F172A">Vehicle type</label>
          <div class="grid grid-cols-3 gap-2">
            <template x-for="t in vTypes" :key="t.k">
              <button type="button" @click="vType=t.k"
                      class="flex flex-col items-center gap-1 py-2.5 rounded-xl transition-all"
                      :style="vType===t.k ? 'background:rgba(0,168,150,.1);border:2px solid #00A896' : 'background:#F7FFFE;border:1.5px solid rgba(0,168,150,.12)'">
                <span class="text-xl" x-text="t.e"></span>
                <span class="text-[11px] font-bold" :style="vType===t.k?'color:#00A896':'color:#475569'" x-text="t.n"></span>
              </button>
            </template>
          </div>
        </div>

        <!-- State -->
        <div>
          <label class="text-sm font-bold mb-2 block" style="color:#0F172A">Your state / UT</label>
          <select x-model="stateKey"
                  class="w-full rounded-xl px-3 py-2.5 text-sm font-semibold outline-none"
                  style="background:#F7FFFE;border:1.5px solid rgba(0,168,150,.2);color:#0F172A">
            <template x-for="s in stateList" :key="s.k">
              <option :value="s.k" x-text="s.n"></option>
            </template>
          </select>
          <p class="text-[11px] mt-2 flex items-start gap-1.5" style="color:#64748B">
            <span class="flex-shrink-0">💡</span>
            <span x-text="stateNote"></span>
          </p>
        </div>
      </div>

      <!-- ── Result ── -->
      <div class="lg:col-span-2 rounded-2xl p-5 flex flex-col text-white" style="background:linear-gradient(150deg,#00A896,#007A6E)">
        <div class="text-[10px] font-black uppercase tracking-widest mb-1" style="color:rgba(255,255,255,.7)">Estimated On-Road Price</div>
        <div class="text-3xl font-black leading-none mb-1 orp-anim" :key="onRoad">₹<span x-text="fmt(onRoad)"></span></div>
        <div class="text-[11px] mb-4" style="color:rgba(255,255,255,.75)">in <span x-text="stateName"></span></div>

        <div class="rounded-xl p-3 mb-3" style="background:rgba(255,255,255,.12)">
          <div class="flex items-center justify-between text-xs mb-1">
            <span style="color:rgba(255,255,255,.85)">You save</span>
            <span class="font-black">₹<span x-text="fmt(totalSubsidy)"></span></span>
          </div>
          <div class="text-[10px]" style="color:rgba(255,255,255,.65)">PM E-DRIVE + state subsidy + tax waivers</div>
        </div>

        <a :href="'<?= base_url('ev-emi-calculator') ?>?amount=' + onRoad"
           class="mt-auto flex items-center justify-center gap-1.5 bg-white font-bold text-xs rounded-xl py-2.5 transition-all"
           style="color:#007A6E"
           onmouseover="this.style.boxShadow='0 4px 14px rgba(0,0,0,.2)'" onmouseout="this.style.boxShadow=''">
          Calculate EMI on this →
        </a>
      </div>
    </div>

    <!-- Breakup -->
    <div class="mt-4 rounded-2xl p-5" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14)">
      <div class="text-sm font-black mb-3" style="color:#0F172A">Full price breakup</div>
      <div class="orp-row"><span class="text-sm" style="color:#475569">Ex-showroom price</span><span class="text-sm font-bold" style="color:#0F172A">₹<span x-text="fmt(exPrice)"></span></span></div>
      <div class="orp-row">
        <span class="text-sm" style="color:#475569">Road tax <span class="text-[10px]" style="color:#94A3B8" x-show="roadTax===0">(EV waiver ✓)</span></span>
        <span class="text-sm font-bold" :style="roadTax===0?'color:#00A896':'color:#0F172A'"><span x-text="roadTax===0?'WAIVED':'+ ₹'+fmt(roadTax)"></span></span>
      </div>
      <div class="orp-row">
        <span class="text-sm" style="color:#475569">Registration <span class="text-[10px]" style="color:#94A3B8" x-show="regFee===0">(waived ✓)</span></span>
        <span class="text-sm font-bold" :style="regFee===0?'color:#00A896':'color:#0F172A'"><span x-text="regFee===0?'WAIVED':'+ ₹'+fmt(regFee)"></span></span>
      </div>
      <div class="orp-row"><span class="text-sm" style="color:#475569">Insurance (1st yr, est.)</span><span class="text-sm font-bold" style="color:#0F172A">+ ₹<span x-text="fmt(insurance)"></span></span></div>
      <div class="orp-row" x-show="fastag>0"><span class="text-sm" style="color:#475569">FASTag + misc</span><span class="text-sm font-bold" style="color:#0F172A">+ ₹<span x-text="fmt(fastag)"></span></span></div>
      <div class="orp-row"><span class="text-sm font-semibold" style="color:#00963C">PM E-DRIVE subsidy</span><span class="text-sm font-bold" style="color:#00963C">– ₹<span x-text="fmt(pmEdrive)"></span></span></div>
      <div class="orp-row" x-show="stateSubsidy>0"><span class="text-sm font-semibold" style="color:#00963C">State subsidy</span><span class="text-sm font-bold" style="color:#00963C">– ₹<span x-text="fmt(stateSubsidy)"></span></span></div>
      <div class="flex items-center justify-between pt-3 mt-1">
        <span class="text-base font-black" style="color:#0F172A">On-road price</span>
        <span class="text-xl font-black" style="color:#00A896">₹<span x-text="fmt(onRoad)"></span></span>
      </div>
    </div>

    <!-- Disclaimer + cross-links -->
    <p class="text-[11px] mt-4 text-center px-4" style="color:#94A3B8">
      Indicative estimate based on 2026 state policies (PM E-DRIVE replaces FAME II). Road tax, registration &amp; subsidies vary by RTO, variant and battery size — always confirm the final figure with your dealer.
    </p>

    <div class="grid sm:grid-cols-3 gap-3 mt-5">
      <?php foreach ([
        ['🎁','Subsidy details','See FAME/PM E-DRIVE + state grants','subsidy-calculator'],
        ['📊','EMI for this EV','Monthly payment & tenure','ev-emi-calculator'],
        ['💰','5-year savings','EV vs petrol total cost','tco-calculator'],
      ] as [$em,$t,$d,$u]): ?>
      <a href="<?= base_url($u) ?>" class="flex items-center gap-3 p-3.5 rounded-2xl transition-all duration-200"
         style="background:#FFFFFF;border:1px solid rgba(0,168,150,.12)"
         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,168,150,.1)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background:rgba(0,168,150,.08)"><?= $em ?></div>
        <div>
          <div class="text-xs font-black" style="color:#0F172A"><?= $t ?></div>
          <div class="text-[10px]" style="color:#64748B"><?= $d ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
function onRoadCalc() {
  return {
    exPrice: 130000,
    vType: '2w',
    stateKey: 'DL',
    vTypes: [
      {k:'2w',e:'🛵',n:'2-Wheeler'},
      {k:'3w',e:'🛺',n:'3-Wheeler'},
      {k:'4w',e:'🚗',n:'4-Wheeler'},
    ],
    quickEvs: [
      {n:'Ather 450X',p:139000,t:'2w'},
      {n:'Ola S1 Pro',p:139999,t:'2w'},
      {n:'TVS iQube',p:142750,t:'2w'},
      {n:'Tata Tiago EV',p:849000,t:'4w'},
      {n:'Nexon EV',p:1449000,t:'4w'},
    ],
    // State data: rt = road-tax % (0 = EV waiver), reg = registration waived?, sub = state subsidy cap by type
    states: {
      DL:{n:'Delhi',rt:0,regWaived:true,sub:{'2w':10000,'3w':30000,'4w':150000},note:'Delhi: 100% road tax & registration waiver for EVs + state subsidy.'},
      MH:{n:'Maharashtra',rt:0,regWaived:true,sub:{'2w':10000,'3w':15000,'4w':150000},note:'Maharashtra: road tax waived; early-bird state subsidy on first buyers.'},
      GJ:{n:'Gujarat',rt:0,regWaived:false,sub:{'2w':20000,'3w':50000,'4w':150000},note:'Gujarat: ₹20k/kWh-based subsidy, among India’s highest for 2W.'},
      KA:{n:'Karnataka',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'Karnataka: 100% road tax exemption for EVs (no extra state cash subsidy).'},
      TN:{n:'Tamil Nadu',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'Tamil Nadu: 100% road tax & registration exemption for EVs.'},
      TS:{n:'Telangana',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'Telangana: road tax & registration fully waived for EVs.'},
      UP:{n:'Uttar Pradesh',rt:0,regWaived:true,sub:{'2w':5000,'3w':12000,'4w':100000},note:'UP: road tax waived + state subsidy under its EV policy.'},
      RJ:{n:'Rajasthan',rt:0,regWaived:false,sub:{'2w':5000,'3w':10000,'4w':0},note:'Rajasthan: SGST-reimbursement style subsidy on 2W/3W.'},
      MP:{n:'Madhya Pradesh',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'MP: road tax exemption for EVs.'},
      WB:{n:'West Bengal',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'West Bengal: road tax waiver for EVs.'},
      KL:{n:'Kerala',rt:5,regWaived:false,sub:{'2w':0,'3w':0,'4w':0},note:'Kerala: reduced road tax (~5%) for EVs.'},
      HR:{n:'Haryana',rt:0,regWaived:true,sub:{'2w':3000,'3w':0,'4w':0},note:'Haryana: road tax waived + limited 2W subsidy.'},
      PB:{n:'Punjab',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'Punjab: road tax exemption for EVs.'},
      AP:{n:'Andhra Pradesh',rt:0,regWaived:true,sub:{'2w':0,'3w':0,'4w':0},note:'Andhra Pradesh: 100% road tax waiver for EVs.'},
      BR:{n:'Bihar',rt:0,regWaived:false,sub:{'2w':0,'3w':0,'4w':0},note:'Bihar: road tax exemption for EVs.'},
      OTH:{n:'Other state',rt:8,regWaived:false,sub:{'2w':0,'3w':0,'4w':0},note:'No EV-specific waiver assumed — standard road tax applied.'},
    },
    get stateList(){ var s=this.states; return Object.keys(s).map(function(k){return {k:k,n:s[k].n};}); },
    get st(){ return this.states[this.stateKey] || this.states.OTH; },
    get stateName(){ return this.st.n; },
    get stateNote(){ return this.st.note; },
    get roadTax(){ return Math.round(this.exPrice * (this.st.rt/100)); },
    get regFee(){
      if (this.st.regWaived) return 0;
      return this.vType==='4w' ? 5000 : (this.vType==='3w' ? 2500 : 600);
    },
    get insurance(){
      var rate = this.vType==='4w' ? 0.035 : 0.05; // 2W comprehensive is a higher % of low value
      return Math.round(this.exPrice * rate);
    },
    get fastag(){ return this.vType==='4w' ? 1000 : 0; },
    get pmEdrive(){
      // PM E-DRIVE: ~₹2,500/kWh, cap ₹5,000 (2W); ₹25k (3W); 4W via state/PSU schemes ~ modest
      if (this.vType==='2w') return Math.min(5000, Math.round(this.exPrice*0.04));
      if (this.vType==='3w') return 25000;
      return 0; // 4W no central cash incentive under PM E-DRIVE
    },
    get stateSubsidy(){ return this.st.sub[this.vType] || 0; },
    get totalSubsidy(){ return this.pmEdrive + this.stateSubsidy + this.roadTaxSaving; },
    get roadTaxSaving(){ // value of waiver vs an 8% baseline, shown as "savings"
      if (this.st.rt < 8) return Math.round(this.exPrice * ((8 - this.st.rt)/100));
      return 0;
    },
    get onRoad(){
      var v = this.exPrice + this.roadTax + this.regFee + this.insurance + this.fastag - this.pmEdrive - this.stateSubsidy;
      return Math.max(0, Math.round(v));
    },
    fmt(n){ return (n||0).toLocaleString('en-IN'); },
    updTrack(e){ var el=e.target; var pct=((el.value-el.min)/(el.max-el.min))*100; el.style.setProperty('--pct',pct+'%'); },
    syncTrack(){ var self=this; this.$nextTick(function(){ var el=self.$el.querySelector('input[type=range]'); if(el){var pct=((el.value-el.min)/(el.max-el.min))*100; el.style.setProperty('--pct',pct+'%');} }); },
    init(){ this.syncTrack(); }
  };
}
</script>

<?= $this->endSection() ?>
