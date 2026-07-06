<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$isEdit     = isset($vehicle['id']);
$page_title = $isEdit ? 'Edit Vehicle' : 'Add Vehicle';
$action     = $isEdit ? '/admin/vehicles/update/' . $vehicle['id'] : '/admin/vehicles/store';
$v          = $vehicle ?? [];

function fv(string $key, array $record = [], string $default = ''): string {
    $val = old($key);
    return esc($val !== null ? $val : ($record[$key] ?? $default));
}
function fvSel(string $key, string $val, array $record = []): string {
    $cur = old($key) ?? ($record[$key] ?? '');
    return (string)$cur === (string)$val ? 'selected' : '';
}
function fvChk(string $key, array $record = []): string {
    $val = old($key) ?? ($record[$key] ?? 0);
    return $val ? 'checked' : '';
}
?>

<div class="mb-5 flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-black text-slate-900"><?= $isEdit ? 'Edit Vehicle' : 'Add New Vehicle' ?></h1>
    <?php if ($isEdit): ?>
      <p class="mt-0.5 text-sm text-slate-500"><?= esc($v['name'] ?? '') ?></p>
    <?php endif; ?>
  </div>
  <div class="flex items-center gap-3">
    <a href="/admin/vehicles/bulk-import"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
      </svg>
      Bulk Import
    </a>
    <a href="/admin/vehicles" class="text-sm font-medium text-slate-500 hover:text-slate-900">&#8592; Back to vehicles</a>
  </div>
</div>

<?php
$flashErrors = session()->getFlashdata('errors') ?? [];
$allErrors   = array_merge($errors ?? [], is_array($flashErrors) ? $flashErrors : []);
?>
<?php if (!empty($allErrors)): ?>
  <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-5 py-4">
    <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
      <?php foreach ($allErrors as $err): ?>
        <li class="text-sm text-red-600"><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= $action ?>" id="vehicle-form" enctype="multipart/form-data"
      x-data="vehicleTabs()"
      x-init="init()">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

  <div class="grid gap-6 lg:grid-cols-4">

    <!-- Main area: tabs + panels -->
    <div class="lg:col-span-3 space-y-4">

      <!-- Tab Nav -->
      <div class="flex flex-wrap gap-2">
        <template x-for="(tab, i) in tabs" :key="i">
          <button type="button"
                  @click="active = i"
                  :class="active === i
                    ? 'bg-emerald-600 text-white shadow-sm'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                  class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                  x-text="tab">
          </button>
        </template>
      </div>

      <!-- Tab 1: Basic Info -->
      <div x-show="active === 0" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-4">Basic Info</h2>
        <div class="grid gap-4 sm:grid-cols-2">

          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vehicle Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?= fv('name', $v) ?>" required
                   placeholder="e.g. Ather 450X Gen 3"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"
                   oninput="autoSlug(this.value)">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Brand <span class="text-red-500">*</span></label>
            <select name="brand_id" required
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="">Select brand</option>
              <?php foreach (($brands ?? []) as $brand): ?>
                <option value="<?= $brand['id'] ?>" <?= fvSel('brand_id', $brand['id'], $v) ?>><?= esc($brand['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
            <select name="category_id" required
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="">Select category</option>
              <?php foreach (($categories ?? []) as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= fvSel('category_id', $cat['id'], $v) ?>><?= esc($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug</label>
            <input type="text" name="slug" id="slug-field" value="<?= fv('slug', $v) ?>"
                   placeholder="auto-generated from name"
                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            <p class="mt-1 text-xs text-slate-400">URL: charj.in/ev/<span id="slug-preview"><?= esc($v['slug'] ?? 'vehicle-slug') ?></span></p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="draft"        <?= fvSel('status', 'draft', $v) ?>>Draft</option>
              <option value="published"    <?= fvSel('status', 'published', $v) ?>>Published</option>
              <option value="discontinued" <?= fvSel('status', 'discontinued', $v) ?>>Discontinued</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Segment</label>
            <select name="segment"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="">Select segment</option>
              <?php foreach (['budget', 'mid-range', 'premium', 'luxury'] as $seg): ?>
                <option value="<?= $seg ?>" <?= fvSel('segment', $seg, $v) ?>><?= ucfirst($seg) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Body Type</label>
            <select name="body_type"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="">Select body type</option>
              <?php foreach (['scooter', 'motorcycle', 'hatchback', 'sedan', 'suv', 'mpv', 'rickshaw', 'loader'] as $bt): ?>
                <option value="<?= $bt ?>" <?= fvSel('body_type', $bt, $v) ?>><?= ucfirst($bt) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="flex items-center gap-3 rounded-xl bg-amber-50 p-3">
            <input type="checkbox" name="featured" id="is_featured" value="1" <?= fvChk('featured', $v) ?>
                   class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400">
            <label for="is_featured" class="text-sm font-semibold text-amber-800">&#11088; Featured vehicle</label>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Short Description <span class="font-normal text-slate-400">(max 200 chars)</span></label>
            <textarea name="short_description" rows="2" maxlength="200"
                      placeholder="One-line summary for listing cards"
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('short_description', $v) ?></textarea>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Description</label>
            <textarea name="full_description" rows="6"
                      placeholder="Detailed vehicle description, use case, highlights..."
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('full_description', $v) ?></textarea>
          </div>

        </div>
      </div>

      <!-- Tab 2: Pricing & Range -->
      <div x-show="active === 1" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-4">Pricing &amp; Range</h2>
        <div class="grid gap-4 sm:grid-cols-2">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Starting Price (&#8377;) <span class="text-red-500">*</span></label>
            <input type="number" name="starting_price" value="<?= fv('starting_price', $v) ?>" min="0" step="1000"
                   placeholder="e.g. 150000"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max / Top Variant Price (&#8377;)</label>
            <input type="number" name="max_price" value="<?= fv('max_price', $v) ?>" min="0" step="1000"
                   placeholder="e.g. 200000"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ex-showroom Price (&#8377;)</label>
            <input type="number" name="ex_showroom_price" value="<?= fv('ex_showroom_price', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Claimed Range (km) <span class="text-red-500">*</span></label>
            <input type="number" name="claimed_range" value="<?= fv('claimed_range', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Real World Range (km)</label>
            <input type="number" name="real_world_range" value="<?= fv('real_world_range', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

        </div>
      </div>

      <!-- Tab 3: Battery & Motor -->
      <div x-show="active === 2" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-4">Battery &amp; Motor</h2>
        <div class="grid gap-4 sm:grid-cols-2">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Battery Capacity (kWh)</label>
            <input type="number" name="battery_capacity" value="<?= fv('battery_capacity', $v) ?>" min="0" step="0.01"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Battery Type</label>
            <select name="battery_type"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="">Select type</option>
              <option value="Li-ion" <?= fvSel('battery_type', 'Li-ion', $v) ?>>Li-ion (Lithium Ion)</option>
              <option value="LFP"    <?= fvSel('battery_type', 'LFP', $v) ?>>LFP (Lithium Iron Phosphate)</option>
              <option value="NMC"    <?= fvSel('battery_type', 'NMC', $v) ?>>NMC (Nickel Manganese Cobalt)</option>
              <option value="NCA"    <?= fvSel('battery_type', 'NCA', $v) ?>>NCA (Nickel Cobalt Aluminium)</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Motor Power (kW)</label>
            <input type="number" name="motor_power" value="<?= fv('motor_power', $v) ?>" min="0" step="0.1"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Motor Torque (Nm)</label>
            <input type="number" name="motor_torque" value="<?= fv('motor_torque', $v) ?>" min="0" step="0.1"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Top Speed (km/h)</label>
            <input type="number" name="top_speed" value="<?= fv('top_speed', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">0&#8211;100 km/h (sec)</label>
            <input type="number" name="acceleration_0_100" value="<?= fv('acceleration_0_100', $v) ?>" min="0" step="0.1"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

        </div>
      </div>

      <!-- Tab 4: Charging -->
      <div x-show="active === 3" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-4">Charging</h2>
        <div class="grid gap-4 sm:grid-cols-2">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Normal Charging Time</label>
            <input type="text" name="charging_time_normal" value="<?= fv('charging_time_normal', $v) ?>"
                   placeholder="e.g. 5h 45m (0-80%)"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fast Charging Time</label>
            <input type="text" name="charging_time_fast" value="<?= fv('charging_time_fast', $v) ?>"
                   placeholder="e.g. 1h 30m (10-80%)"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fast Charging Type</label>
            <select name="fast_charging_type"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <option value="">Select connector</option>
              <option value="CCS2"        <?= fvSel('fast_charging_type', 'CCS2', $v) ?>>CCS2 (DC)</option>
              <option value="CHAdeMO"     <?= fvSel('fast_charging_type', 'CHAdeMO', $v) ?>>CHAdeMO (DC)</option>
              <option value="GB/T"        <?= fvSel('fast_charging_type', 'GB/T', $v) ?>>GB/T</option>
              <option value="Proprietary" <?= fvSel('fast_charging_type', 'Proprietary', $v) ?>>Proprietary</option>
              <option value="Type-2"      <?= fvSel('fast_charging_type', 'Type-2', $v) ?>>Type 2 (AC)</option>
            </select>
          </div>

          <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
            <input type="checkbox" name="fast_charging_supported" id="fast_charging_supported" value="1" <?= fvChk('fast_charging_supported', $v) ?>
                   class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            <label for="fast_charging_supported" class="text-sm font-semibold text-slate-700">Fast Charging Supported</label>
          </div>

        </div>
      </div>

      <!-- Tab 5: Dimensions & Warranty -->
      <div x-show="active === 4" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-4">Dimensions &amp; Warranty</h2>
        <div class="grid gap-4 sm:grid-cols-2">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Seating Capacity</label>
            <input type="number" name="seating_capacity" value="<?= fv('seating_capacity', $v) ?>" min="1" max="9"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kerb Weight (kg)</label>
            <input type="number" name="kerb_weight" value="<?= fv('kerb_weight', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ground Clearance (mm)</label>
            <input type="number" name="ground_clearance" value="<?= fv('ground_clearance', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Boot Space (litres)</label>
            <input type="number" name="boot_space" value="<?= fv('boot_space', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vehicle Warranty (years)</label>
            <input type="number" name="warranty_years" value="<?= fv('warranty_years', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vehicle Warranty (km)</label>
            <input type="number" name="warranty_km" value="<?= fv('warranty_km', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Battery Warranty (years)</label>
            <input type="number" name="battery_warranty_years" value="<?= fv('battery_warranty_years', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Battery Warranty (km)</label>
            <input type="number" name="battery_warranty_km" value="<?= fv('battery_warranty_km', $v) ?>" min="0"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

        </div>
      </div>

      <!-- Tab 6: Media & SEO -->
      <div x-show="active === 5" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-4">Media &amp; SEO</h2>
        <div class="space-y-4">

          <div class="grid gap-4 sm:grid-cols-2">
            <div x-data="aiImageSuggest()" x-init="init()">
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vehicle Image</label>
              <!-- Upload file OR paste URL -->
              <div class="rounded-xl border-2 border-dashed border-slate-200 p-3 mb-2 text-center"
                   style="background:#F8FAFC"
                   x-data="{dragging:false}"
                   @dragover.prevent="dragging=true" @dragleave="dragging=false"
                   @drop.prevent="dragging=false;handleDrop($event)"
                   :style="dragging?'border-color:#00A896;background:rgba(0,168,150,.04)':''">
                <input type="file" name="image_file" id="image_file_input" accept="image/*" class="hidden"
                       @change="handleFile($event)">
                <label for="image_file_input" class="cursor-pointer flex flex-col items-center gap-1">
                  <svg class="w-6 h-6" style="color:#94A3B8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M13.5 12h.008v.008H13.5V12zm-1.5 6h9a.75.75 0 00.75-.75V4.5A.75.75 0 0021 3.75H3A.75.75 0 002.25 4.5v12.75c0 .414.336.75.75.75h9z"/></svg>
                  <span class="text-xs font-semibold" style="color:#64748B">Click to upload or drag & drop</span>
                  <span class="text-[10px]" style="color:#94A3B8">JPG, PNG, WebP — max 2MB</span>
                </label>
              </div>
              <div class="flex items-center gap-2 mb-2">
                <div class="flex-1 h-px" style="background:#E2E8F0"></div>
                <span class="text-[10px] font-semibold" style="color:#94A3B8">OR paste URL</span>
                <div class="flex-1 h-px" style="background:#E2E8F0"></div>
              </div>
              <div class="flex gap-2">
                <input type="url" name="image_url" id="image_url_field"
                       x-model="imageUrl"
                       placeholder="https://cdn.example.com/nexon-ev.jpg"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
                <button type="button" @click="suggest()"
                        :disabled="loading"
                        class="flex-shrink-0 flex items-center gap-1.5 rounded-lg bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-3 py-2 transition-colors disabled:opacity-50">
                  <svg x-show="!loading" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                  <svg x-show="loading" x-cloak class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                  <span x-text="loading ? 'Finding…' : 'AI Suggest'"></span>
                </button>
              </div>
              <!-- Live preview -->
              <div x-show="imageUrl" x-cloak class="mt-2 relative">
                <img :src="imageUrl" @error="imgError=true" @load="imgError=false"
                     class="w-full h-36 object-cover rounded-lg border border-slate-200 bg-slate-50">
                <div x-show="imgError" x-cloak
                     class="absolute inset-0 flex items-center justify-center rounded-lg bg-red-50 border border-red-200 text-xs text-red-500">
                  ⚠ Image failed to load — try a different URL
                </div>
              </div>
              <!-- Suggestions carousel -->
              <div x-show="suggestions.length > 0" x-cloak class="mt-2 space-y-1">
                <p class="text-xs text-slate-400 uppercase tracking-wide">AI found <span x-text="suggestions.length"></span> options — click to use:</p>
                <div class="flex flex-wrap gap-2">
                  <template x-for="(s, i) in suggestions" :key="i">
                    <button type="button" @click="imageUrl = s.url"
                            class="group relative overflow-hidden rounded border-2 transition-all"
                            :class="imageUrl === s.url ? 'border-emerald-500' : 'border-slate-200 hover:border-emerald-300'">
                      <img :src="s.url" class="h-16 w-24 object-cover">
                      <span class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-xs font-bold" x-text="s.label"></span>
                    </button>
                  </template>
                </div>
              </div>
              <p x-show="error" x-cloak x-text="error" class="mt-1 text-xs text-red-500"></p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Video URL</label>
              <input type="url" name="video_url" value="<?= fv('video_url', $v) ?>"
                     placeholder="https://youtube.com/watch?v=..."
                     class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta Title</label>
            <input type="text" name="meta_title" value="<?= fv('meta_title', $v) ?>" maxlength="70"
                   placeholder="e.g. Tata Nexon EV Price, Range & Specs 2025 - Charj.in"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            <p class="mt-1 text-xs text-slate-400">50-70 characters recommended</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta Description</label>
            <textarea name="meta_description" rows="3" maxlength="160"
                      placeholder="Tata Nexon EV review: 465km range, 143kW power..."
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('meta_description', $v) ?></textarea>
            <p class="mt-1 text-xs text-slate-400">120-160 characters recommended</p>
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Expert Rating (0-10)</label>
              <input type="number" name="expert_rating" value="<?= fv('expert_rating', $v) ?>" min="0" max="10" step="0.1"
                     class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Best For</label>
              <select name="best_for"
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
                <option value="">Select use case</option>
                <option value="city_commute"  <?= fvSel('best_for', 'city_commute', $v) ?>>City Commute</option>
                <option value="highway"       <?= fvSel('best_for', 'highway', $v) ?>>Highway / Long distance</option>
                <option value="fleet"         <?= fvSel('best_for', 'fleet', $v) ?>>Fleet / Commercial</option>
                <option value="family"        <?= fvSel('best_for', 'family', $v) ?>>Family</option>
                <option value="last_mile"     <?= fvSel('best_for', 'last_mile', $v) ?>>Last Mile Delivery</option>
                <option value="performance"   <?= fvSel('best_for', 'performance', $v) ?>>Performance</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Expert Review</label>
            <textarea name="expert_review" rows="5"
                      placeholder="Write your expert review here..."
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('expert_review', $v) ?></textarea>
          </div>

        </div>
      </div>

      <!-- Tab 7: JSON Specs -->
      <div x-show="active === 6" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-1">JSON Specs</h2>
        <p class="text-xs text-slate-400 mb-4">Enter valid JSON arrays. Example for features: <code class="bg-slate-100 px-1 rounded">["Fast charging","GPS","Cruise control"]</code></p>

        <div class="space-y-5">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Features JSON</label>
            <textarea name="features_json" rows="4"
                      placeholder='["Feature 1","Feature 2","Feature 3"]'
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('features_json', $v) ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pros JSON</label>
            <textarea name="pros_json" rows="3"
                      placeholder='["Long range","Comfortable ride"]'
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('pros_json', $v) ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cons JSON</label>
            <textarea name="cons_json" rows="3"
                      placeholder='["Higher price","Limited service network"]'
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('cons_json', $v) ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Colors JSON</label>
            <textarea name="colors_json" rows="3"
                      placeholder='[{"name":"Pristine White","hex":"#FFFFFF"},{"name":"Daytona Grey","hex":"#808080"}]'
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none"><?= fv('colors_json', $v) ?></textarea>
          </div>

        </div>
      </div>

    </div><!-- /main area -->

    <!-- Sidebar -->
    <div class="space-y-5">

      <!-- Save -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-3">
        <button type="submit"
                class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 text-sm font-bold transition-colors">
          <?= $isEdit ? 'Save Changes' : 'Create Vehicle' ?>
        </button>
        <a href="/admin/vehicles"
           class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
          Cancel
        </a>
      </div>

      <!-- Tips -->
      <div class="bg-slate-50 rounded-xl border border-slate-200 p-4">
        <p class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Tips</p>
        <ul class="space-y-1.5 text-xs text-slate-500">
          <li>&#8226; Slug is auto-generated from name.</li>
          <li>&#8226; Starting Price is required for listings.</li>
          <li>&#8226; Use Tab 7 for JSON arrays (features, pros, cons, colors).</li>
          <li>&#8226; Set status to Published to show on site.</li>
        </ul>
      </div>

      <?php if ($isEdit && !empty($v['id'])): ?>
      <!-- Danger -->
      <div class="bg-red-50 rounded-xl border border-red-200 p-4">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Danger Zone</h3>
        <a href="/admin/vehicles/delete/<?= esc($v['id']) ?>"
           onclick="return confirm('Mark this vehicle as discontinued?')"
           class="block w-full rounded-lg border border-red-300 bg-white px-3 py-2 text-center text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
          Discontinue Vehicle
        </a>
      </div>
      <?php endif; ?>

    </div>

  </div><!-- /grid -->
</form>

<style>[x-cloak]{display:none!important}</style>

<script>
function vehicleTabs() {
    return {
        active: 0,
        tabs: ['Basic Info','Pricing & Range','Battery & Motor','Charging','Dimensions & Warranty','Media & SEO','JSON Specs'],
        init() {}
    };
}
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}
function autoSlug(name) {
    const field = document.getElementById('slug-field');
    const preview = document.getElementById('slug-preview');
    if (field && !field.dataset.edited) {
        const s = slugify(name);
        field.value = s;
        if (preview) preview.textContent = s || 'vehicle-slug';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sf = document.getElementById('slug-field');
    if (sf) {
        sf.addEventListener('input', function() {
            this.dataset.edited = '1';
            const preview = document.getElementById('slug-preview');
            if (preview) preview.textContent = this.value || 'vehicle-slug';
        });
    }
});

function aiImageSuggest() {
    return {
        imageUrl: '<?= addslashes(fv('image_url', $v)) ?>',
        suggestions: [],
        loading: false,
        error: '',
        handleFile(e) {
            var file = e.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { alert('File too large — max 2MB'); return; }
            var reader = new FileReader();
            var self = this;
            reader.onload = function(ev) { self.imageUrl = ev.target.result; };
            reader.readAsDataURL(file);
        },
        handleDrop(e) {
            var file = e.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            document.getElementById('image_file_input').files = e.dataTransfer.files;
            this.handleFile({target: document.getElementById('image_file_input')});
        },
        imgError: false,
        init() {},
        async suggest() {
            const nameField = document.querySelector('[name="name"]');
            const brandSel  = document.querySelector('[name="brand_id"]');
            const name  = nameField ? nameField.value.trim() : '';
            const brand = brandSel  ? brandSel.options[brandSel.selectedIndex]?.text.trim() : '';
            if (!name) { this.error = 'Enter a vehicle name first.'; return; }
            this.loading = true; this.error = ''; this.suggestions = [];
            try {
                const res = await fetch('<?= site_url('admin/ai/suggest-image') ?>', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                    body: JSON.stringify({ name, brand })
                });
                const data = await res.json();
                if (data.suggestions && data.suggestions.length) {
                    this.suggestions = data.suggestions;
                    this.imageUrl = data.suggestions[0].url;
                } else {
                    this.error = data.error || 'No images found. Try entering the URL manually.';
                }
            } catch(e) {
                this.error = 'Request failed. Check your connection.';
            }
            this.loading = false;
        }
    };
}
</script>

<?= $this->endSection() ?>
