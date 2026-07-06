<?php
/**
 * Admin â€” Site Settings
 * Variables: $settings (array key=>value), $page_title
 */
$page_title  = 'Site Settings';
$breadcrumbs = ['Settings' => '/admin/settings'];

// Helper to get setting value safely
$s = function (string $key) use ($settings): string {
    return $settings[$key] ?? '';
};
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900">Site Settings</h1>
    <p class="mt-0.5 text-sm text-slate-500">Configure Charj.in site-wide settings</p>
  </div>
</div>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?= esc(session()->getFlashdata('success')) ?>
  </div>
<?php endif; ?>

<form method="post" action="/admin/settings/save" class="space-y-6" x-data="settingsForm()">
  <?= csrf_field() ?>

  <!-- 1. Site Settings -->
  <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center gap-3 bg-slate-50 border-b border-slate-200 px-6 py-4">
      <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#0D2137]">
        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
        </svg>
      </div>
      <div>
        <h2 class="text-sm font-bold text-slate-900">Site Settings</h2>
        <p class="text-xs text-slate-500">Basic site identity and contact info</p>
      </div>
    </div>
    <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

      <div class="sm:col-span-2">
        <label for="site_name" class="block text-sm font-medium text-slate-700 mb-1.5">Site Name</label>
        <input type="text" id="site_name" name="settings[site_name]"
               value="<?= esc($s('site_name')) ?>"
               placeholder="Charj.in"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

      <div>
        <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-1.5">Contact Email</label>
        <input type="email" id="contact_email" name="settings[contact_email]"
               value="<?= esc($s('contact_email')) ?>"
               placeholder="hello@charj.in"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

      <div>
        <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-1.5">Contact Phone</label>
        <input type="text" id="contact_phone" name="settings[contact_phone]"
               value="<?= esc($s('contact_phone')) ?>"
               placeholder="+91 98765 43210"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

      <div>
        <label for="whatsapp_number" class="block text-sm font-medium text-slate-700 mb-1.5">WhatsApp Number</label>
        <div class="flex">
          <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 px-3 text-sm text-slate-500">+91</span>
          <input type="text" id="whatsapp_number" name="settings[whatsapp_number]"
                 value="<?= esc($s('whatsapp_number')) ?>"
                 placeholder="9876543210"
                 class="flex-1 rounded-r-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
        </div>
        <p class="mt-1 text-xs text-slate-400">10-digit number without country code</p>
      </div>

    </div>
  </div>

  <!-- 2. Analytics -->
  <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center gap-3 bg-slate-50 border-b border-slate-200 px-6 py-4">
      <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600">
        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
      </div>
      <div>
        <h2 class="text-sm font-bold text-slate-900">Analytics</h2>
        <p class="text-xs text-slate-500">Google Analytics and Meta Pixel tracking IDs</p>
      </div>
    </div>
    <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

      <div>
        <label for="ga_id" class="block text-sm font-medium text-slate-700 mb-1.5">Google Analytics ID (GA4)</label>
        <input type="text" id="ga_id" name="settings[ga_id]"
               value="<?= esc($s('ga_id')) ?>"
               placeholder="G-XXXXXXXXXX"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 font-mono placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
        <p class="mt-1 text-xs text-slate-400">Format: G-XXXXXXXXXX</p>
      </div>

      <div>
        <label for="meta_pixel_id" class="block text-sm font-medium text-slate-700 mb-1.5">Meta (Facebook) Pixel ID</label>
        <input type="text" id="meta_pixel_id" name="settings[meta_pixel_id]"
               value="<?= esc($s('meta_pixel_id')) ?>"
               placeholder="1234567890123456"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 font-mono placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
        <p class="mt-1 text-xs text-slate-400">15â€“16 digit Pixel ID from Meta Events Manager</p>
      </div>

    </div>
  </div>

  <!-- 3. Social Media -->
  <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center gap-3 bg-slate-50 border-b border-slate-200 px-6 py-4">
      <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-pink-500">
        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0z"/>
        </svg>
      </div>
      <div>
        <h2 class="text-sm font-bold text-slate-900">Social Media</h2>
        <p class="text-xs text-slate-500">Social media profile URLs for Charj.in</p>
      </div>
    </div>
    <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

      <div>
        <label for="instagram_url" class="block text-sm font-medium text-slate-700 mb-1.5">Instagram URL</label>
        <input type="url" id="instagram_url" name="settings[instagram_url]"
               value="<?= esc($s('instagram_url')) ?>"
               placeholder="https://instagram.com/charj.in"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

      <div>
        <label for="facebook_url" class="block text-sm font-medium text-slate-700 mb-1.5">Facebook URL</label>
        <input type="url" id="facebook_url" name="settings[facebook_url]"
               value="<?= esc($s('facebook_url')) ?>"
               placeholder="https://facebook.com/charjin"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

      <div>
        <label for="youtube_url" class="block text-sm font-medium text-slate-700 mb-1.5">YouTube URL</label>
        <input type="url" id="youtube_url" name="settings[youtube_url]"
               value="<?= esc($s('youtube_url')) ?>"
               placeholder="https://youtube.com/@charjin"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

      <div>
        <label for="linkedin_url" class="block text-sm font-medium text-slate-700 mb-1.5">LinkedIn URL</label>
        <input type="url" id="linkedin_url" name="settings[linkedin_url]"
               value="<?= esc($s('linkedin_url')) ?>"
               placeholder="https://linkedin.com/company/charj-in"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
      </div>

    </div>
  </div>

  <!-- 4. Features -->
  <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center gap-3 bg-slate-50 border-b border-slate-200 px-6 py-4">
      <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500">
        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
      </div>
      <div>
        <h2 class="text-sm font-bold text-slate-900">Feature Flags</h2>
        <p class="text-xs text-slate-500">Enable or disable site features</p>
      </div>
    </div>
    <div class="p-6 space-y-5">

      <?php
      $features = [
          'show_charging_map'   => ['label' => 'Show Charging Station Map',  'desc' => 'Display the interactive EV charging map on the Charging Stations page.'],
          'show_recommendation' => ['label' => 'Show EV Recommendation Tool', 'desc' => 'Enable the personalised EV recommendation quiz for users.'],
          'maintenance_mode'    => ['label' => 'Maintenance Mode',            'desc' => 'Show a maintenance page to all public visitors. Admin access still works.'],
      ];
      foreach ($features as $key => $feat):
        $val = $s($key);
        $enabled = in_array($val, ['1', 'true', 'yes', 'on'], true);
      ?>
      <div class="flex items-start justify-between gap-4 py-4 border-b border-slate-100 last:border-0 last:pb-0"
           x-data="{ enabled: <?= json_encode($enabled) ?> }">
        <div class="flex-1">
          <label class="block text-sm font-semibold text-slate-900 cursor-pointer" @click="enabled = !enabled">
            <?= esc($feat['label']) ?>
            <?php if ($key === 'maintenance_mode'): ?>
              <span class="ml-2 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-600">Caution</span>
            <?php endif; ?>
          </label>
          <p class="mt-0.5 text-xs text-slate-500"><?= esc($feat['desc']) ?></p>
        </div>
        <div class="shrink-0 flex items-center gap-2">
          <button type="button"
                  @click="enabled = !enabled"
                  :class="enabled ? 'bg-emerald-500' : 'bg-slate-200'"
                  class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
            <span :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                  class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
          </button>
          <input type="hidden" name="settings[<?= esc($key) ?>]" :value="enabled ? '1' : '0'">
          <span class="text-xs text-slate-500 w-12" x-text="enabled ? 'On' : 'Off'"></span>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>

  <!-- Save Button -->
  <div class="flex items-center justify-between rounded-2xl bg-white border border-slate-200 shadow-sm px-6 py-4">
    <p class="text-sm text-slate-500">Changes take effect immediately after saving.</p>
    <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-7 py-3 text-sm font-bold text-white shadow hover:bg-emerald-600 transition-colors">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
      Save Settings
    </button>
  </div>

  <!-- Indian Locale Settings -->
  <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center gap-3 bg-slate-50 border-b border-slate-200 px-6 py-4">
      <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-500">
        <span class="text-white text-sm font-black">₹</span>
      </div>
      <div>
        <h2 class="text-sm font-bold text-slate-900">Indian Locale &amp; Regional Settings</h2>
        <p class="text-xs text-slate-500">Date format, currency, timezone and number formatting for India</p>
      </div>
    </div>
    <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

      <div>
        <label for="timezone" class="block text-sm font-medium text-slate-700 mb-1.5">Timezone</label>
        <select id="timezone" name="settings[timezone]"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition bg-white">
          <option value="Asia/Kolkata" <?= $s('timezone') === 'Asia/Kolkata' || $s('timezone') === '' ? 'selected' : '' ?>>Asia/Kolkata (IST, UTC+5:30) ✓ Recommended</option>
          <option value="UTC" <?= $s('timezone') === 'UTC' ? 'selected' : '' ?>>UTC</option>
        </select>
        <p class="mt-1 text-xs text-slate-400">Indian Standard Time (IST) is UTC+5:30</p>
      </div>

      <div>
        <label for="currency" class="block text-sm font-medium text-slate-700 mb-1.5">Currency</label>
        <select id="currency" name="settings[currency]"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition bg-white">
          <option value="INR" <?= $s('currency') === 'INR' || $s('currency') === '' ? 'selected' : '' ?>>INR — Indian Rupee (₹) ✓ Recommended</option>
          <option value="USD" <?= $s('currency') === 'USD' ? 'selected' : '' ?>>USD — US Dollar ($)</option>
        </select>
        <p class="mt-1 text-xs text-slate-400">Prices displayed using Indian numbering (Lakh / Crore)</p>
      </div>

      <div>
        <label for="date_format" class="block text-sm font-medium text-slate-700 mb-1.5">Date Format</label>
        <select id="date_format" name="settings[date_format]"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition bg-white">
          <option value="d/m/Y" <?= $s('date_format') === 'd/m/Y' || $s('date_format') === '' ? 'selected' : '' ?>>DD/MM/YYYY — e.g. 22/06/2026 ✓ Indian Standard</option>
          <option value="d M Y" <?= $s('date_format') === 'd M Y' ? 'selected' : '' ?>>DD Mon YYYY — e.g. 22 Jun 2026</option>
          <option value="Y-m-d" <?= $s('date_format') === 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD — ISO format</option>
          <option value="m/d/Y" <?= $s('date_format') === 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY — US format</option>
        </select>
      </div>

      <div>
        <label for="number_format" class="block text-sm font-medium text-slate-700 mb-1.5">Number Format</label>
        <select id="number_format" name="settings[number_format]"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition bg-white">
          <option value="indian" <?= $s('number_format') === 'indian' || $s('number_format') === '' ? 'selected' : '' ?>>Indian — 1,00,000 (Lakh system) ✓ Recommended</option>
          <option value="international" <?= $s('number_format') === 'international' ? 'selected' : '' ?>>International — 100,000</option>
        </select>
        <p class="mt-1 text-xs text-slate-400">Indian format: 1 Lakh = 1,00,000 · 1 Crore = 1,00,00,000</p>
      </div>

      <div>
        <label for="language" class="block text-sm font-medium text-slate-700 mb-1.5">Default Language</label>
        <select id="language" name="settings[language]"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition bg-white">
          <option value="en-IN" <?= $s('language') === 'en-IN' || $s('language') === '' ? 'selected' : '' ?>>English (India) — en-IN ✓ Recommended</option>
          <option value="en-US" <?= $s('language') === 'en-US' ? 'selected' : '' ?>>English (US) — en-US</option>
          <option value="hi-IN" <?= $s('language') === 'hi-IN' ? 'selected' : '' ?>>हिंदी (Hindi) — hi-IN</option>
        </select>
      </div>

      <div>
        <label for="gsc_verification" class="block text-sm font-medium text-slate-700 mb-1.5">Google Search Console Verification</label>
        <input type="text" id="gsc_verification" name="settings[gsc_verification]"
               value="<?= esc($s('gsc_verification')) ?>"
               placeholder="google-site-verification=XXXXXXXX"
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 font-mono placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
        <p class="mt-1 text-xs text-slate-400">Paste the full meta content value from Google Search Console</p>
      </div>

    </div>
  </div>

</form>

<script>
function settingsForm() {
    return {};
}
</script>

<?= $this->endSection() ?>
