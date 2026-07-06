<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="min-h-screen" style="background:#F5FFF7">

  <!-- Hero -->
  <div class="pt-24 pb-8" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
    <div class="mx-auto max-w-3xl px-4 text-center">
      <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold mb-5" style="background:rgba(0,168,150,.1);color:#00A896;border:1px solid rgba(0,168,150,.2)">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
        EMI Calculator
      </span>
      <h1 class="text-2xl sm:text-4xl font-black" style="color:#0F172A">EV Loan EMI Calculator</h1>
      <p class="mt-3 text-base" style="color:#475569">Calculate your exact monthly EMI — adjust price, down payment, rate & tenure in real time.</p>
    </div>
  </div>

  <!-- Content -->
  <div class="mx-auto max-w-6xl px-4 pb-16 -mt-8">
    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">

      <!-- Calculator card -->
      <div x-data="{
        price:  1400000,
        down:   200000,
        rate:   9,
        tenure: 36,
        get loan()     { return Math.max(0, Number(this.price) - Number(this.down)); },
        get r()        { return Number(this.rate) / 12 / 100; },
        get n()        { return Number(this.tenure); },
        get emi()      { const p=this.loan,r=this.r,n=this.n; if(p<=0||r<=0||n<=0) return 0; return (p*r*Math.pow(1+r,n))/(Math.pow(1+r,n)-1); },
        get total()    { return Math.round(this.emi) * this.n; },
        get interest() { return Math.max(0, this.total - this.loan); },
        get prinPct()  { return this.total>0 ? (this.loan/this.total*100).toFixed(1) : 0; },
        fmt(v)         { return '₹' + Math.round(v).toLocaleString('en-IN'); },
        fmtL(v)        { const l=v/100000; return l>=1 ? '₹'+l.toFixed(1)+'L' : '₹'+Math.round(v/1000)+'K'; }
      }" class="rounded-3xl bg-white shadow-sm overflow-hidden" style="border:1px solid rgba(0,200,100,.15)">

        <!-- EMI result bar -->
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background:linear-gradient(135deg,#00E676 0%,#69FF97 100%)">
          <div>
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(2,44,34,.6)">Monthly EMI</p>
            <p class="text-3xl sm:text-4xl font-black mt-0.5" style="color:#022C22" x-text="fmt(emi)">₹28,543</p>
          </div>
          <div class="flex gap-4 sm:gap-6">
            <div class="text-center">
              <p class="text-[11px] font-bold uppercase tracking-wide" style="color:rgba(2,44,34,.55)">Total Interest</p>
              <p class="text-lg font-black mt-0.5" style="color:#022C22" x-text="fmt(interest)"></p>
            </div>
            <div class="w-px" style="background:rgba(2,44,34,.12)"></div>
            <div class="text-center">
              <p class="text-[11px] font-bold uppercase tracking-wide" style="color:rgba(2,44,34,.55)">Total Amount</p>
              <p class="text-lg font-black mt-0.5" style="color:#022C22" x-text="fmt(total)"></p>
            </div>
          </div>
        </div>

        <!-- Breakdown bar -->
        <div class="px-6 py-3" style="background:#F0FFF4;border-bottom:1px solid rgba(0,200,100,.1)">
          <div class="h-3 rounded-full overflow-hidden flex" style="background:#E2E8F0">
            <div class="h-3 rounded-full transition-all duration-500" style="background:#00C060" :style="'width:'+prinPct+'%'"></div>
          </div>
          <div class="flex justify-between mt-2 text-[11px] font-semibold" style="color:#475569">
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#00C060"></span>
              Principal <span class="font-bold" style="color:#022C22" x-text="fmt(loan)"></span>
            </span>
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#F59E0B"></span>
              Interest <span class="font-bold" style="color:#022C22" x-text="fmt(interest)"></span>
            </span>
          </div>
        </div>

        <!-- Inputs -->
        <div class="p-6 space-y-7">

          <!-- Vehicle Price -->
          <div>
            <div class="flex justify-between items-baseline mb-2">
              <label class="text-sm font-bold" style="color:#0F172A">Vehicle Price</label>
              <span class="text-sm font-black px-3 py-1 rounded-lg" style="background:#F0FFF4;color:#00A855" x-text="fmtL(price)"></span>
            </div>
            <input type="range" x-model.number="price" min="100000" max="10000000" step="25000"
              class="w-full h-2 rounded-full appearance-none cursor-pointer" style="accent-color:#00C060">
            <div class="flex justify-between text-[11px] mt-1" style="color:#94A3B8">
              <span>₹1L</span><span>₹1Cr</span>
            </div>
          </div>

          <!-- Down Payment -->
          <div>
            <div class="flex justify-between items-baseline mb-2">
              <label class="text-sm font-bold" style="color:#0F172A">Down Payment</label>
              <span class="text-sm font-black px-3 py-1 rounded-lg" style="background:#F0FFF4;color:#00A855" x-text="fmtL(down)"></span>
            </div>
            <input type="range" x-model.number="down" min="0" :max="price" step="10000"
              class="w-full h-2 rounded-full appearance-none cursor-pointer" style="accent-color:#00C060">
            <p class="text-xs mt-1.5 font-medium" style="color:#64748B">
              Loan amount: <strong style="color:#0F172A" x-text="fmt(loan)"></strong>
            </p>
          </div>

          <!-- Interest Rate -->
          <div>
            <div class="flex justify-between items-baseline mb-2">
              <label class="text-sm font-bold" style="color:#0F172A">Interest Rate (p.a.)</label>
              <span class="text-sm font-black px-3 py-1 rounded-lg" style="background:#F0FFF4;color:#00A855" x-text="rate + '%'"></span>
            </div>
            <input type="range" x-model.number="rate" min="6" max="20" step="0.25"
              class="w-full h-2 rounded-full appearance-none cursor-pointer" style="accent-color:#00C060">
            <div class="flex justify-between text-[11px] mt-1" style="color:#94A3B8">
              <span>6%</span><span>20%</span>
            </div>
          </div>

          <!-- Loan Tenure -->
          <div>
            <div class="flex justify-between items-baseline mb-3">
              <label class="text-sm font-bold" style="color:#0F172A">Loan Tenure</label>
              <span class="text-sm font-black px-3 py-1 rounded-lg" style="background:#F0FFF4;color:#00A855" x-text="tenure + ' months'"></span>
            </div>
            <div class="grid grid-cols-7 gap-1.5">
              <?php foreach ([12, 24, 36, 48, 60, 72, 84] as $m): ?>
              <button type="button" @click="tenure = <?= $m ?>"
                :style="tenure === <?= $m ?> ? 'background:#00C060;color:#fff;border-color:#00C060' : 'background:#fff;color:#475569;border-color:#E2E8F0'"
                class="py-1.5 sm:py-2 rounded-xl text-[10px] sm:text-xs font-bold border-2 transition-all">
                <?= $m ?>M
              </button>
              <?php endforeach; ?>
            </div>
          </div>

        </div>

        <!-- Tips -->
        <div class="mx-6 mb-6 rounded-2xl p-4" style="background:#F0FFF4;border:1px solid rgba(0,200,100,.2)">
          <p class="text-xs font-bold mb-2" style="color:#022C22">EV Finance Tips</p>
          <ul class="space-y-1.5 text-xs" style="color:#166534">
            <li class="flex gap-2"><span style="color:#00C060">✓</span> FAME II subsidy reduces your effective loan amount</li>
            <li class="flex gap-2"><span style="color:#00C060">✓</span> Section 80EEB: deduct up to ₹1.5L on interest paid</li>
            <li class="flex gap-2"><span style="color:#00C060">✓</span> SBI Green Car Loan from 8.5% · HDFC 9% · ICICI 9.5%</li>
            <li class="flex gap-2"><span style="color:#00C060">✓</span> Higher down payment = lower EMI and total interest</li>
          </ul>
        </div>

      </div>

      <!-- Sidebar -->
      <aside class="space-y-4">
        <?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true, 'formHeading' => 'Get the Best Loan Rate', 'formSubtitle' => 'Our EV finance experts find you the lowest rate — free.']) ?>

        <!-- Quick links -->
        <div class="rounded-2xl bg-white p-4 space-y-2" style="border:1px solid rgba(0,200,100,.15)">
          <p class="text-xs font-bold uppercase tracking-wide mb-3" style="color:#64748B">Related Tools</p>
          <a href="<?= base_url('subsidy-calculator') ?>" class="flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors" style="background:#F0FFF4;color:#00A855" onmouseover="this.style.background='#D1FAE5'" onmouseout="this.style.background='#F0FFF4'">
            <span>EV Subsidy Calculator</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
          <a href="<?= base_url('ev-savings-calculator') ?>" class="flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors" style="background:#F8FAFC;color:#475569" onmouseover="this.style.background='#F0FFF4';this.style.color='#00A855'" onmouseout="this.style.background='#F8FAFC';this.style.color='#475569'">
            <span>EV Savings Calculator</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
          <a href="<?= base_url('vehicles') ?>" class="flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors" style="background:#F8FAFC;color:#475569" onmouseover="this.style.background='#F0FFF4';this.style.color='#00A855'" onmouseout="this.style.background='#F8FAFC';this.style.color='#475569'">
            <span>Browse All EVs</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </aside>

    </div>
  </div>
  <div class="pb-4 md:pb-0"></div>
</div>

<?= $this->endSection() ?>
