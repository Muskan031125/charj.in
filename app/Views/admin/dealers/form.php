<?php
/**
 * Admin â€” Dealer Create / Edit Form
 * Variables: $dealer (array, for edit), $isEdit (bool), $brands (array), $errors (array), $page_title
 */
$isEdit     = $isEdit ?? false;
$dealer     = $dealer ?? [];
$brands     = $brands ?? [];
$page_title = $isEdit ? 'Edit Dealer' : 'Add Dealer';
$breadcrumbs = [
    'Dealers' => '/admin/dealers',
    ($isEdit ? 'Edit' : 'Add Dealer') => '',
];
$formAction = $isEdit
    ? '/admin/dealers/update/' . ($dealer['id'] ?? '')
    : '/admin/dealers/store';

$indianStates = [
    'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana',
    'Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur',
    'Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana',
    'Tripura','Uttar Pradesh','Uttarakhand','West Bengal',
    'Delhi','Chandigarh','Puducherry','Lakshadweep','Dadra & Nagar Haveli','Daman & Diu',
    'Andaman & Nicobar Islands','Ladakh','Jammu & Kashmir',
];
sort($indianStates);
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900"><?= $isEdit ? 'Edit Dealer' : 'Add New Dealer' ?></h1>
    <p class="mt-0.5 text-sm text-slate-500"><?= $isEdit ? 'Update dealer details.' : 'Add a new EV dealer to Charj.in.' ?></p>
  </div>
  <a href="/admin/dealers"
     class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
    </svg>
    Back to Dealers
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
      x-data="dealerForm()">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?>
    <input type="hidden" name="_method" value="PUT">
  <?php endif; ?>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Main Form -->
    <div class="lg:col-span-2 space-y-6">

      <!-- Basic Info -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Dealer Information</h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

          <!-- Name -->
          <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
              Dealer Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" required
                   x-model="name" @input="generateSlug()"
                   value="<?= esc(old('name', $dealer['name'] ?? '')) ?>"
                   placeholder="e.g. Green Wheels EV â€” Koramangala"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Contact Person -->
          <div>
            <label for="contact_person" class="block text-sm font-medium text-slate-700 mb-1.5">Contact Person</label>
            <input type="text" id="contact_person" name="contact_person"
                   value="<?= esc(old('contact_person', $dealer['contact_person'] ?? '')) ?>"
                   placeholder="e.g. Amit Kumar"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Phone -->
          <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
              Phone <span class="text-red-500">*</span>
            </label>
            <input type="tel" id="phone" name="phone" required
                   value="<?= esc(old('phone', $dealer['phone'] ?? '')) ?>"
                   placeholder="e.g. 9876543210"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= esc(old('email', $dealer['email'] ?? '')) ?>"
                   placeholder="dealer@example.com"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Website -->
          <div>
            <label for="website" class="block text-sm font-medium text-slate-700 mb-1.5">Website</label>
            <input type="url" id="website" name="website"
                   value="<?= esc(old('website', $dealer['website'] ?? '')) ?>"
                   placeholder="https://www.dealerwebsite.com"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Address -->
          <div class="sm:col-span-2">
            <label for="address" class="block text-sm font-medium text-slate-700 mb-1.5">Street Address</label>
            <textarea id="address" name="address" rows="2"
                      placeholder="e.g. 12, MG Road, Near Metro Station"
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition resize-y"><?= esc(old('address', $dealer['address'] ?? '')) ?></textarea>
          </div>

          <!-- City -->
          <div>
            <label for="city" class="block text-sm font-medium text-slate-700 mb-1.5">
              City <span class="text-red-500">*</span>
            </label>
            <input type="text" id="city" name="city" required
                   x-model="city" @input="generateSlug()"
                   value="<?= esc(old('city', $dealer['city'] ?? '')) ?>"
                   placeholder="e.g. Bengaluru"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- State -->
          <div>
            <label for="state" class="block text-sm font-medium text-slate-700 mb-1.5">State</label>
            <select id="state" name="state"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
              <option value="">Select State</option>
              <?php $selectedState = old('state', $dealer['state'] ?? ''); ?>
              <?php foreach ($indianStates as $state): ?>
                <option value="<?= esc($state) ?>" <?= $selectedState === $state ? 'selected' : '' ?>><?= esc($state) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Pincode -->
          <div>
            <label for="pincode" class="block text-sm font-medium text-slate-700 mb-1.5">Pincode</label>
            <input type="text" id="pincode" name="pincode" maxlength="6" pattern="[1-9][0-9]{5}"
                   value="<?= esc(old('pincode', $dealer['pincode'] ?? '')) ?>"
                   placeholder="e.g. 560034"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

          <!-- Slug -->
          <div>
            <label for="slug" class="block text-sm font-medium text-slate-700 mb-1.5">
              Slug <span class="text-xs text-slate-400 font-normal ml-1">Auto-generated</span>
            </label>
            <input type="text" id="slug" name="slug"
                   x-model="slug"
                   value="<?= esc(old('slug', $dealer['slug'] ?? '')) ?>"
                   placeholder="green-wheels-bengaluru"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>

        </div>
      </div>

      <!-- Brands Handled -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Brands Handled</h2>
        <?php if (!empty($brands)): ?>
          <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            <?php
            $handledBrands = [];
            if (!empty($dealer['brands_handled'])) {
                $handledBrands = is_array($dealer['brands_handled'])
                    ? $dealer['brands_handled']
                    : array_map('trim', explode(',', $dealer['brands_handled']));
            }
            foreach ($brands as $b):
              $bName = $b['name'] ?? '';
              $checked = in_array($bName, $handledBrands) || in_array($b['id'] ?? '', $handledBrands);
            ?>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="brands_handled[]" value="<?= esc($bName) ?>"
                       <?= $checked ? 'checked' : '' ?>
                       class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-400">
                <span class="text-sm text-slate-700 group-hover:text-slate-900"><?= esc($bName) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div>
            <label for="brands_handled_text" class="block text-sm font-medium text-slate-700 mb-1.5">Brands Handled</label>
            <input type="text" id="brands_handled_text" name="brands_handled_text"
                   value="<?= esc(old('brands_handled_text', is_array($dealer['brands_handled'] ?? null) ? implode(', ', $dealer['brands_handled']) : ($dealer['brands_handled'] ?? ''))) ?>"
                   placeholder="e.g. Tata Motors, Ather Energy, Ola Electric"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
            <p class="mt-1 text-xs text-slate-400">Comma-separated brand names</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Location / Maps -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Location &amp; Maps</h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label for="google_maps_url" class="block text-sm font-medium text-slate-700 mb-1.5">Google Maps URL</label>
            <input type="url" id="google_maps_url" name="google_maps_url"
                   value="<?= esc(old('google_maps_url', $dealer['google_maps_url'] ?? '')) ?>"
                   placeholder="https://maps.google.com/?q=..."
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>
          <div>
            <label for="latitude" class="block text-sm font-medium text-slate-700 mb-1.5">Latitude</label>
            <input type="text" id="latitude" name="latitude"
                   value="<?= esc(old('latitude', $dealer['latitude'] ?? '')) ?>"
                   placeholder="e.g. 12.9716"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition">
          </div>
          <div>
            <label for="longitude" class="block text-sm font-medium text-slate-700 mb-1.5">Longitude</label>
            <input type="text" id="longitude" name="longitude"
                   value="<?= esc(old('longitude', $dealer['longitude'] ?? '')) ?>"
                   placeholder="e.g. 77.5946"
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
              <?php $statusVal = old('status', $dealer['status'] ?? 'inactive'); ?>
              <option value="active" <?= $statusVal === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= $statusVal === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>

          <!-- Verified toggle -->
          <div x-data="{ verified: <?= json_encode(!empty(old('is_verified', $dealer['is_verified'] ?? false))) ?> }">
            <label class="block text-sm font-medium text-slate-700 mb-2">Verified Dealer</label>
            <div class="flex items-center gap-3">
              <button type="button"
                      @click="verified = !verified"
                      :class="verified ? 'bg-emerald-500' : 'bg-slate-200'"
                      class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                <span :class="verified ? 'translate-x-6' : 'translate-x-1'"
                      class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
              </button>
              <input type="hidden" name="is_verified" :value="verified ? '1' : '0'">
              <span class="text-sm text-slate-600" x-text="verified ? 'Verified' : 'Not verified'"></span>
            </div>
            <p class="mt-1.5 text-xs text-slate-400">Verified dealers show a checkmark badge on listings.</p>
          </div>
        </div>
      </div>

      <!-- Save -->
      <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 space-y-3">
        <button type="submit"
                class="w-full rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-emerald-600 transition-colors">
          <?= $isEdit ? 'Save Changes' : 'Create Dealer' ?>
        </button>
        <a href="/admin/dealers"
           class="block w-full rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
          Cancel
        </a>
      </div>

      <?php if ($isEdit && !empty($dealer['id'])): ?>
      <div class="rounded-2xl bg-red-50 border border-red-200 p-5">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Danger Zone</h3>
        <form method="post" action="/admin/dealers/delete/<?= esc($dealer['id']) ?>"
              onsubmit="return confirm('Permanently delete this dealer?')">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit"
                  class="w-full rounded-xl border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors">
            Delete Dealer
          </button>
        </form>
      </div>
      <?php endif; ?>

    </div>
  </div>
</form>

<script>
function dealerForm() {
    return {
        name: '<?= addslashes(old('name', $dealer['name'] ?? '')) ?>',
        city: '<?= addslashes(old('city', $dealer['city'] ?? '')) ?>',
        slug: '<?= addslashes(old('slug', $dealer['slug'] ?? '')) ?>',
        generateSlug() {
            const combined = (this.name + ' ' + this.city).toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
            this.slug = combined;
        }
    }
}
</script>

<?= $this->endSection() ?>
