<?php
/**
 * Admin â€” Charging Station Create / Edit Form
 * Variables: $station (array), $isEdit (bool), $errors (array), $page_title
 */
$isEdit     = $isEdit  ?? false;
$station    = $station ?? [];
$page_title = $isEdit ? 'Edit Charging Station' : 'Add Charging Station';
$breadcrumbs = [
    'Charging Stations' => '/admin/charging-stations',
    ($isEdit ? 'Edit' : 'Add Station') => '',
];
$formAction = $isEdit
    ? '/admin/charging-stations/update/' . ($station['id'] ?? '')
    : '/admin/charging-stations/store';

$connectorTypes = ['CCS2', 'Type 2', 'CHAdeMO', 'Bharat AC-001', 'Bharat DC-001', 'Tesla'];

$savedConnectors = old('connector_types', $station['connector_types'] ?? []);
if (is_string($savedConnectors)) {
    $savedConnectors = json_decode($savedConnectors, true) ?? array_map('trim', explode(',', $savedConnectors));
}

$indianStates = [
    'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana',
    'Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur',
    'Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana',
    'Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Chandigarh','Puducherry',
    'Lakshadweep','Dadra & Nagar Haveli','Daman & Diu','Andaman & Nicobar Islands','Ladakh','Jammu & Kashmir',
];
sort($indianStates);
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900"><?= $isEdit ? 'Edit Charging Station' : 'Add Charging Station' ?></h1>
    <p class="mt-0.5 text-sm text-slate-500"><?= $isEdit ? 'Update charging station details.' : 'Add a new EV charging location.' ?></p>
  </div>
  <a href="/admin/charging-stations"
     class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
    </svg>
    Back to Stations
  </a>
</div>

<?php if (!empty($errors) || session()->getFlashdata('errors')): ?>
  <?php $allErrors = array_merge($errors ?? [], session()->getFlashdata('errors') ?? []); ?>
  <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4">
    <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
      <?php foreach ($allErrors as $err): ?>
        <li class="text-sm text-red-600"><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= esc($formAction) ?>" class="space-y-6"
      x-data="{ open24x7: <?= json_encode(!empty(old('open_24x7', $station['open_24x7'] ?? false))) ?>, isVerified: <?= json_encode(!empty(old('is_verified', $station['is_verified'] ?? false))) ?> }">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?>
    <input type="hidden" name="_method" value="PUT">
  <?php endif; ?>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Main Form -->
    <div class="lg:col-span-2 space-y-6">

      <!-- Basic Info -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Station Information</h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

          <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
              Station Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" required
                   value="<?= esc(old('name', $station['name'] ?? '')) ?>"
                   placeholder="e.g. Tata Power EV Charging â€” Phoenix Palladium Mall"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <div>
            <label for="operator" class="block text-sm font-medium text-slate-700 mb-1.5">Operator / Network</label>
            <input type="text" id="operator" name="operator"
                   value="<?= esc(old('operator', $station['operator'] ?? '')) ?>"
                   list="operator_list"
                   placeholder="e.g. Tata Power, Ather Grid, ChargeZone"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
            <datalist id="operator_list">
              <option value="Tata Power EZ Charge">
              <option value="Ather Grid">
              <option value="ChargeZone">
              <option value="BPCL">
              <option value="EESL">
              <option value="Fortum Charge & Drive">
              <option value="Jio-bp Pulse">
              <option value="Kazam">
              <option value="MagentaEV">
              <option value="HPCL">
            </datalist>
          </div>

          <div>
            <label for="pricing_per_kwh" class="block text-sm font-medium text-slate-700 mb-1.5">Pricing per kWh (â‚¹)</label>
            <input type="number" id="pricing_per_kwh" name="pricing_per_kwh" min="0" step="0.01"
                   value="<?= esc(old('pricing_per_kwh', $station['pricing_per_kwh'] ?? '')) ?>"
                   placeholder="e.g. 14.00"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <div class="sm:col-span-2">
            <label for="address" class="block text-sm font-medium text-slate-700 mb-1.5">
              Address <span class="text-red-500">*</span>
            </label>
            <textarea id="address" name="address" rows="2" required
                      placeholder="Full street address including landmark"
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition resize-y"><?= esc(old('address', $station['address'] ?? '')) ?></textarea>
          </div>

          <div>
            <label for="city" class="block text-sm font-medium text-slate-700 mb-1.5">
              City <span class="text-red-500">*</span>
            </label>
            <input type="text" id="city" name="city" required
                   value="<?= esc(old('city', $station['city'] ?? '')) ?>"
                   placeholder="e.g. Mumbai"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <div>
            <label for="state" class="block text-sm font-medium text-slate-700 mb-1.5">State</label>
            <select id="state" name="state"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
              <option value="">Select State</option>
              <?php $selectedState = old('state', $station['state'] ?? ''); ?>
              <?php foreach ($indianStates as $state): ?>
                <option value="<?= esc($state) ?>" <?= $selectedState === $state ? 'selected' : '' ?>><?= esc($state) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label for="pincode" class="block text-sm font-medium text-slate-700 mb-1.5">Pincode</label>
            <input type="text" id="pincode" name="pincode" maxlength="6" pattern="[1-9][0-9]{5}"
                   value="<?= esc(old('pincode', $station['pincode'] ?? '')) ?>"
                   placeholder="e.g. 400001"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

        </div>
      </div>

      <!-- Connector Types -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Connector Types</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
          <?php foreach ($connectorTypes as $connector): ?>
            <label class="flex items-center gap-2.5 cursor-pointer rounded-xl border border-slate-200 px-4 py-3 hover:border-emerald-300 hover:bg-emerald-50/50 transition-colors">
              <input type="checkbox" name="connector_types[]" value="<?= esc($connector) ?>"
                     <?= in_array($connector, $savedConnectors) ? 'checked' : '' ?>
                     class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-400">
              <span class="text-sm font-medium text-slate-700"><?= esc($connector) ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Charging Details -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Charging Details</h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

          <div>
            <label for="total_ports" class="block text-sm font-medium text-slate-700 mb-1.5">Total Charging Ports</label>
            <input type="number" id="total_ports" name="total_ports" min="1" max="500"
                   value="<?= esc(old('total_ports', $station['total_ports'] ?? '')) ?>"
                   placeholder="e.g. 4"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <div>
            <label for="charging_speed" class="block text-sm font-medium text-slate-700 mb-1.5">Charging Speed</label>
            <select id="charging_speed" name="charging_speed"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
              <?php $speedVal = old('charging_speed', $station['charging_speed'] ?? 'fast'); ?>
              <option value="slow"        <?= $speedVal === 'slow'        ? 'selected' : '' ?>>Slow (up to 3.3 kW)</option>
              <option value="fast"        <?= $speedVal === 'fast'        ? 'selected' : '' ?>>Fast (7â€“22 kW)</option>
              <option value="rapid"       <?= $speedVal === 'rapid'       ? 'selected' : '' ?>>Rapid (50â€“150 kW)</option>
              <option value="ultra_rapid" <?= $speedVal === 'ultra_rapid' ? 'selected' : '' ?>>Ultra Rapid (150 kW+)</option>
            </select>
          </div>

          <!-- Open 24x7 toggle -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Open 24x7</label>
            <div class="flex items-center gap-3">
              <button type="button"
                      @click="open24x7 = !open24x7"
                      :class="open24x7 ? 'bg-emerald-500' : 'bg-slate-200'"
                      class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                <span :class="open24x7 ? 'translate-x-6' : 'translate-x-1'"
                      class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
              </button>
              <input type="hidden" name="open_24x7" :value="open24x7 ? '1' : '0'">
              <span class="text-sm text-slate-600" x-text="open24x7 ? 'Open 24 hours' : 'Has working hours'"></span>
            </div>
          </div>

          <div x-show="!open24x7">
            <label for="working_hours" class="block text-sm font-medium text-slate-700 mb-1.5">Working Hours</label>
            <input type="text" id="working_hours" name="working_hours"
                   value="<?= esc(old('working_hours', $station['working_hours'] ?? '')) ?>"
                   placeholder="e.g. Monâ€“Sat: 8 AM â€“ 10 PM"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

        </div>
      </div>

      <!-- Location -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Location &amp; Maps</h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label for="google_maps_url" class="block text-sm font-medium text-slate-700 mb-1.5">Google Maps URL</label>
            <input type="url" id="google_maps_url" name="google_maps_url"
                   value="<?= esc(old('google_maps_url', $station['google_maps_url'] ?? '')) ?>"
                   placeholder="https://maps.google.com/?q=..."
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>
          <div>
            <label for="latitude" class="block text-sm font-medium text-slate-700 mb-1.5">Latitude</label>
            <input type="text" id="latitude" name="latitude"
                   value="<?= esc(old('latitude', $station['latitude'] ?? '')) ?>"
                   placeholder="e.g. 19.0760"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>
          <div>
            <label for="longitude" class="block text-sm font-medium text-slate-700 mb-1.5">Longitude</label>
            <input type="text" id="longitude" name="longitude"
                   value="<?= esc(old('longitude', $station['longitude'] ?? '')) ?>"
                   placeholder="e.g. 72.8777"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>
        </div>
      </div>

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">

      <!-- Settings -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Settings</h2>

        <div class="space-y-5">
          <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
            <select id="status" name="status"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
              <?php $statusVal = old('status', $station['status'] ?? 'operational'); ?>
              <option value="operational"        <?= $statusVal === 'operational'        ? 'selected' : '' ?>>Operational</option>
              <option value="coming_soon"        <?= $statusVal === 'coming_soon'        ? 'selected' : '' ?>>Coming Soon</option>
              <option value="temporarily_closed" <?= $statusVal === 'temporarily_closed' ? 'selected' : '' ?>>Temporarily Closed</option>
            </select>
          </div>

          <!-- Verified toggle -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Verified Station</label>
            <div class="flex items-center gap-3">
              <button type="button"
                      @click="isVerified = !isVerified"
                      :class="isVerified ? 'bg-emerald-500' : 'bg-slate-200'"
                      class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                <span :class="isVerified ? 'translate-x-6' : 'translate-x-1'"
                      class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
              </button>
              <input type="hidden" name="is_verified" :value="isVerified ? '1' : '0'">
              <span class="text-sm text-slate-600" x-text="isVerified ? 'Verified' : 'Unverified'"></span>
            </div>
            <p class="mt-1.5 text-xs text-slate-400">Verified stations show a badge on the map and listings.</p>
          </div>
        </div>
      </div>

      <!-- Save -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 space-y-3">
        <button type="submit"
                class="w-full rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-emerald-600 transition-colors">
          <?= $isEdit ? 'Save Changes' : 'Add Station' ?>
        </button>
        <a href="/admin/charging-stations"
           class="block w-full rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
          Cancel
        </a>
      </div>

      <?php if ($isEdit && !empty($station['id'])): ?>
      <div class="rounded-2xl bg-red-50 border border-red-200 p-5">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Danger Zone</h3>
        <form method="post" action="/admin/charging-stations/delete/<?= esc($station['id']) ?>"
              onsubmit="return confirm('Permanently delete this station?')">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit"
                  class="w-full rounded-xl border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors">
            Delete Station
          </button>
        </form>
      </div>
      <?php endif; ?>

    </div>
  </div>
</form>

<?= $this->endSection() ?>
