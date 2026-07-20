<?php
/**
 * Lead Capture Form — Charj.in
 * Cleaner redesign. Keeps all Alpine.js logic.
 *
 * Optional variables:
 *   $vehicle          array   — if set, pre-fills vehicle_id
 *   $formHeading      string  — override card heading
 *   $formSubtitle     string  — override subtitle
 *   $defaultTab       string  — 'price' | 'test_ride' | 'advice'
 *   $compactMode      bool    — hide optional fields
 *   $hideName         bool    — hide the name field (e.g. calculator pages)
 */
$compactMode  = $compactMode  ?? false;
$hideName     = $hideName     ?? false;
$defaultTab   = $defaultTab   ?? 'price';
$formHeading  = $formHeading  ?? 'Get the Best EV Deal';
$formSubtitle = $formSubtitle ?? 'Our EV specialists respond within 24 hours — completely free.';

$vehicleId   = $vehicle['id']   ?? 0;
$vehicleName = $vehicle['name'] ?? '';
$leadType    = $vehicleId ? 'test_drive' : 'general';

$indianCities = [
    'Ahmedabad','Bengaluru','Bhopal','Chandigarh','Chennai','Coimbatore',
    'Delhi','Hyderabad','Indore','Jaipur','Kochi','Kolkata','Lucknow',
    'Mumbai','Nagpur','Noida','Pune','Surat','Vadodara','Visakhapatnam',
];

$timelines = [
    ''                => 'When are you planning to buy?',
    'immediately'     => 'Immediately (this week)',
    'within_7_days'   => 'Within 7 days',
    'within_30_days'  => 'Within 30 days',
    'within_3_months' => 'Within 3 months',
    'researching'     => 'Just researching',
];

$successName = session('lead_name') ?? '';
$isSuccess   = (bool) session('lead_success');
?>

<section
  id="lead-form"
  x-data="{
    activeTab:  '<?= esc($defaultTab) ?>',
    submitted:  <?= $isSuccess ? 'true' : 'false' ?>,
    submitting: false,
    name:       '<?= esc(old('name', $successName)) ?>',
  }"
  class="rounded-2xl bg-white p-6 ring-1 ring-slate-100 shadow-sm"
  aria-label="EV enquiry form"
>

  <!-- Header -->
  <div class="mb-5">
    <h3 class="font-bold text-slate-900 text-base sm:text-lg">
      <?= $vehicleName ? 'Interested in the ' . esc($vehicleName) . '?' : esc($formHeading) ?>
    </h3>
    <p class="text-sm text-slate-500 mt-1"><?= esc($formSubtitle) ?></p>
  </div>

  <!-- Tab switcher -->
  <div class="grid grid-cols-3 gap-1.5 mb-5" role="tablist">
    <button type="button" role="tab"
      @click="activeTab='price'"
      :class="activeTab==='price' ? 'bg-green-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
      class="flex flex-col items-center gap-0.5 px-1.5 sm:px-2 py-2 rounded-xl text-[10px] sm:text-[11px] font-bold transition-all cursor-pointer focus:outline-none min-h-[44px]">
      <span class="text-base" aria-hidden="true">&#127991;&#65039;</span>
      <span>Best Price</span>
    </button>
    <button type="button" role="tab"
      @click="activeTab='test_ride'"
      :class="activeTab==='test_ride' ? 'bg-green-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
      class="flex flex-col items-center gap-0.5 px-1.5 sm:px-2 py-2 rounded-xl text-[10px] sm:text-[11px] font-bold transition-all cursor-pointer focus:outline-none min-h-[44px]">
      <span class="text-base" aria-hidden="true">&#128663;</span>
      <span>Test Ride</span>
    </button>
    <button type="button" role="tab"
      @click="activeTab='advice'"
      :class="activeTab==='advice' ? 'bg-green-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
      class="flex flex-col items-center gap-0.5 px-1.5 sm:px-2 py-2 rounded-xl text-[10px] sm:text-[11px] font-bold transition-all cursor-pointer focus:outline-none min-h-[44px]">
      <span class="text-base" aria-hidden="true">&#128172;</span>
      <span>Advice</span>
    </button>
  </div>

  <!-- Success state -->
  <div x-show="submitted" x-cloak class="py-6 text-center">
    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 ring-4 ring-green-200">
      <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
    <h4 class="font-bold text-slate-900 mb-1">
      Thanks<template x-if="name">, <span x-text="name.split(' ')[0]"></span></template>!
    </h4>
    <p class="text-sm text-slate-500 mb-4">We'll call you within 24 hours with the best options.</p>
    <div class="space-y-2">
      <a href="<?= base_url('subsidy-calculator') ?>"
         class="flex items-center justify-between gap-2 w-full bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
        <span>Check EV subsidy</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
      <a href="<?= base_url('ev-emi-calculator') ?>"
         class="flex items-center justify-between gap-2 w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
        <span>Calculate your EMI</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
    <button type="button" @click="submitted=false"
      class="mt-4 text-xs text-slate-400 hover:text-green-600 transition-colors underline-offset-2 hover:underline">
      Submit another enquiry
    </button>
  </div>

  <!-- Error flash -->
  <?php if (session('errors')): ?>
  <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-3.5">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <div>
      <p class="text-sm font-bold text-red-800">Please fix the following:</p>
      <ul class="mt-1 space-y-0.5 list-disc list-inside">
        <?php foreach ((array) session('errors') as $err): ?>
        <li class="text-sm text-red-700"><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>

  <!-- Form -->
  <form
    id="charj-lead-form"
    method="post"
    action="<?= base_url('lead/submit') ?>"
    x-show="!submitted"
    x-on:submit.prevent="
      submitting = true;
      name = ($el.querySelector('[name=name]') || {}).value || '';
      charjTrack && charjTrack('lead_form_submit', {
        lead_type: activeTab,
        vehicle_id: '<?= esc($vehicleId) ?>',
        page_url: window.location.href
      });
      $el.submit();
    "
    class="space-y-3"
    novalidate
  >
    <?= csrf_field() ?>
    <input type="hidden" name="lead_type"    :value="activeTab">
    <input type="hidden" name="vehicle_id"   value="<?= esc($vehicleId) ?>">
    <input type="hidden" name="utm_source"   id="lf_utm_source"   value="">
    <input type="hidden" name="utm_medium"   id="lf_utm_medium"   value="">
    <input type="hidden" name="utm_campaign" id="lf_utm_campaign" value="">
    <input type="hidden" name="utm_term"     id="lf_utm_term"     value="">
    <input type="hidden" name="utm_content"  id="lf_utm_content"  value="">
    <input type="hidden" name="referrer"     id="lf_referrer"     value="">

    <?php if (!$hideName): ?>
    <input type="text" name="name" placeholder="Your name" required autocomplete="name"
           value="<?= esc(old('name')) ?>"
           oninput="this.value=this.value.replace(/[^a-zA-Z\s\.'`-]/g,'')"
           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all placeholder:text-slate-400">
    <?php endif; ?>

    <div class="relative">
      <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold select-none" aria-hidden="true">+91</span>
      <input type="tel" name="mobile" placeholder="10-digit mobile number" required autocomplete="tel"
             pattern="[6-9][0-9]{9}" maxlength="10" minlength="10" inputmode="numeric"
             value="<?= esc(old('mobile')) ?>"
             oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
             class="w-full rounded-xl border border-slate-200 pl-12 pr-3 py-2.5 sm:pr-4 sm:py-3 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all placeholder:text-slate-400">
    </div>

    <input type="text" name="city" list="lf_city_list" placeholder="Your city" autocomplete="address-level2"
           value="<?= esc(old('city')) ?>"
           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all placeholder:text-slate-400">
    <datalist id="lf_city_list">
      <?php foreach ($indianCities as $city): ?><option value="<?= esc($city) ?>"><?php endforeach; ?>
    </datalist>

    <!-- Tab-specific fields -->
    <div x-show="activeTab==='price'">
      <select name="budget"
        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm text-slate-600 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all bg-white">
        <option value="">Your budget range</option>
        <option value="under_50k">Under &#8377;50,000</option>
        <option value="50k_1l">&#8377;50,000 – &#8377;1 Lakh</option>
        <option value="1l_2l">&#8377;1 Lakh – &#8377;2 Lakh</option>
        <option value="2l_5l">&#8377;2 Lakh – &#8377;5 Lakh</option>
        <option value="5l_10l">&#8377;5 Lakh – &#8377;10 Lakh</option>
        <option value="10l_plus">&#8377;10 Lakh +</option>
      </select>
    </div>

    <div x-show="activeTab==='test_ride'" x-cloak>
      <input type="date" name="preferred_date"
        min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
        value="<?= esc(old('preferred_date')) ?>"
        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all">
    </div>

    <div x-show="activeTab==='advice'" x-cloak class="space-y-3">
      <select name="use_case"
        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm text-slate-600 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all bg-white">
        <option value="">How will you use the EV?</option>
        <option value="personal">Personal / Daily Commute</option>
        <option value="commercial">Commercial / Delivery</option>
        <option value="fleet">Fleet / Multiple Vehicles</option>
        <option value="family">Family Vehicle</option>
        <option value="other">Other</option>
      </select>
      <textarea name="message" rows="2"
        placeholder="Your question (optional)..."
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all resize-none"><?= esc(old('message')) ?></textarea>
    </div>

    <?php if (!$compactMode): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <input type="email" name="email" placeholder="Email (optional)" autocomplete="email"
             value="<?= esc(old('email')) ?>"
             class="rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all placeholder:text-slate-400">
      <select name="purchase_timeline"
        class="rounded-xl border border-slate-200 px-3 py-2.5 sm:px-4 sm:py-3 text-sm text-slate-600 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none transition-all bg-white">
        <?php foreach ($timelines as $val => $lbl): ?>
        <option value="<?= esc($val) ?>" <?= old('purchase_timeline')===$val?'selected':'' ?>><?= esc($lbl) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>

    <label class="flex items-start gap-2.5 cursor-pointer group">
      <input type="checkbox" name="consent" required value="1"
        class="mt-0.5 w-4 h-4 flex-shrink-0 accent-green-500 rounded border-slate-300 cursor-pointer">
      <span class="text-xs text-slate-500 leading-relaxed">
        I agree to be contacted by <strong class="text-slate-700">Charj.in</strong> regarding my enquiry. No spam. No dealer calls without consent.
        <a href="<?= base_url('privacy-policy') ?>" class="text-green-600 hover:underline" target="_blank" rel="noopener">Privacy Policy</a>.
      </span>
    </label>

    <button type="submit"
      :disabled="submitting"
      :class="submitting ? 'opacity-70 cursor-wait' : 'hover:bg-green-700 active:scale-[0.98]'"
      class="btn-primary w-full justify-center py-3 min-h-[44px] transition-all">
      <span x-show="!submitting" class="flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
        </svg>
        <span x-text="{
          price:     'Get Best Price — Free',
          test_ride: 'Book Test Ride — Free',
          advice:    'Get Expert Advice — Free'
        }[activeTab] || 'Submit Enquiry — Free'"></span>
      </span>
      <span x-show="submitting" x-cloak class="flex items-center gap-2">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        Submitting...
      </span>
    </button>

    <div class="flex flex-wrap items-center justify-center gap-3 pt-1 border-t border-slate-100">
      <span class="text-xs text-slate-400">No spam</span>
      <span class="text-slate-200">|</span>
      <span class="text-xs text-slate-400">Response within 24hrs</span>
      <span class="text-slate-200">|</span>
      <span class="text-xs text-slate-400">100% Free</span>
    </div>
  </form>
</section>

<script>
(function () {
  try {
    var p = new URLSearchParams(window.location.search);
    [['utm_source','lf_utm_source'],['utm_medium','lf_utm_medium'],
     ['utm_campaign','lf_utm_campaign'],['utm_term','lf_utm_term'],
     ['utm_content','lf_utm_content']].forEach(function(pair) {
      var el = document.getElementById(pair[1]);
      if (el && p.get(pair[0])) el.value = p.get(pair[0]);
    });
    var ref = document.getElementById('lf_referrer');
    if (ref) ref.value = document.referrer || '';
  } catch(e) {}
})();
</script>
