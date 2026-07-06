<?php
/**
 * Admin - Brand Create / Edit Form
 * Variables: $brand (array, for edit), $isEdit (bool), $errors (array), $page_title
 */
$isEdit     = $isEdit ?? false;
$brand      = $brand  ?? [];
$page_title = $isEdit ? 'Edit Brand' : 'Add Brand';
$formAction = $isEdit
    ? '/admin/brands/update/' . ($brand['id'] ?? '')
    : '/admin/brands/store';
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-xl font-bold text-slate-900"><?= $isEdit ? 'Edit Brand' : 'Add New Brand' ?></h1>
    <p class="mt-0.5 text-sm text-slate-500"><?= $isEdit ? 'Update brand details and SEO information.' : 'Create a new EV brand listing on Charj.in.' ?></p>
  </div>
  <a href="/admin/brands"
     class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
    </svg>
    Back to Brands
  </a>
</div>

<?php
$flashErrors = session()->getFlashdata('errors') ?? [];
$allErrors   = array_merge($errors ?? [], is_array($flashErrors) ? $flashErrors : []);
?>
<?php if (!empty($allErrors)): ?>
  <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4">
    <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
      <?php foreach ($allErrors as $err): ?>
        <li class="text-sm text-red-600"><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= esc($formAction) ?>" class="space-y-6" x-data="brandForm()">
  <?= csrf_field() ?>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Main form -->
    <div class="lg:col-span-2 space-y-6">

      <!-- Basic Info -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Brand Information</h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

          <!-- Name -->
          <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
              Brand Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" required
                   x-model="name"
                   @input="generateSlug()"
                   value="<?= esc(old('name', $brand['name'] ?? '')) ?>"
                   placeholder="e.g. Tata Motors"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <!-- Slug -->
          <div class="sm:col-span-2">
            <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1.5">
              Slug <span class="text-xs text-slate-400 font-normal ml-1">Auto-generated, editable</span>
            </label>
            <div class="flex items-center rounded-lg border border-slate-200 focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-100 overflow-hidden">
              <span class="inline-flex items-center bg-slate-50 px-3 py-2 text-sm text-slate-400 border-r border-slate-200 shrink-0">charj.in/brands/</span>
              <input type="text" id="slug" name="slug" required
                     x-model="slug"
                     value="<?= esc(old('slug', $brand['slug'] ?? '')) ?>"
                     placeholder="tata-motors"
                     class="flex-1 px-3 py-2 text-sm outline-none bg-white">
            </div>
          </div>

          <!-- Short Description -->
          <div class="sm:col-span-2">
            <label for="short_description" class="block text-sm font-semibold text-slate-700 mb-1.5">Short Description</label>
            <input type="text" id="short_description" name="short_description"
                   value="<?= esc(old('short_description', $brand['short_description'] ?? '')) ?>"
                   placeholder="One-line summary shown in brand cards"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <!-- Country -->
          <div>
            <label for="country" class="block text-sm font-semibold text-slate-700 mb-1.5">Country of Origin</label>
            <select id="country" name="country"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <?php
              $countries = ['India', 'China', 'USA', 'South Korea', 'Japan', 'Germany', 'UK', 'Netherlands', 'Sweden', 'Other'];
              $selected  = old('country', $brand['country'] ?? 'India');
              foreach ($countries as $c):
              ?>
                <option value="<?= esc($c) ?>" <?= $selected === $c ? 'selected' : '' ?>><?= esc($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Founded Year -->
          <div>
            <label for="founded_year" class="block text-sm font-semibold text-slate-700 mb-1.5">Founded Year</label>
            <input type="number" id="founded_year" name="founded_year" min="1800" max="<?= date('Y') ?>"
                   value="<?= esc(old('founded_year', $brand['founded_year'] ?? '')) ?>"
                   placeholder="e.g. 1945"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <!-- Headquarters -->
          <div>
            <label for="headquarters" class="block text-sm font-semibold text-slate-700 mb-1.5">Headquarters</label>
            <input type="text" id="headquarters" name="headquarters"
                   value="<?= esc(old('headquarters', $brand['headquarters'] ?? '')) ?>"
                   placeholder="e.g. Mumbai, India"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <!-- Website URL -->
          <div>
            <label for="website" class="block text-sm font-semibold text-slate-700 mb-1.5">Official Website URL</label>
            <input type="url" id="website" name="website"
                   value="<?= esc(old('website', $brand['website'] ?? '')) ?>"
                   placeholder="https://www.tatamotors.com"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
          </div>

          <!-- Logo URL -->
          <div class="sm:col-span-2">
            <label for="logo_url" class="block text-sm font-semibold text-slate-700 mb-1.5">Logo URL</label>
            <input type="url" id="logo_url" name="logo_url"
                   value="<?= esc(old('logo_url', $brand['logo_url'] ?? '')) ?>"
                   placeholder="https://cdn.example.com/logos/tata.png"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            <p class="mt-1 text-xs text-slate-400">Paste a direct image URL (PNG/SVG preferred, transparent background)</p>
          </div>

          <!-- Description -->
          <div class="sm:col-span-2">
            <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Full Description</label>
            <textarea id="description" name="description" rows="5"
                      placeholder="Brief description of the brand, its history and EV focus in India..."
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none resize-y"><?= esc(old('description', $brand['description'] ?? '')) ?></textarea>
          </div>

        </div>
      </div>

      <!-- SEO Section -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">SEO Settings</h2>

        <div class="space-y-5">
          <div>
            <label for="seo_title" class="block text-sm font-semibold text-slate-700 mb-1.5">SEO Title</label>
            <input type="text" id="seo_title" name="seo_title"
                   value="<?= esc(old('seo_title', $brand['seo_title'] ?? '')) ?>"
                   placeholder="e.g. Tata Motors Electric Vehicles in India - Charj.in"
                   maxlength="70"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            <p class="mt-1 text-xs text-slate-400">Recommended: 50-70 characters</p>
          </div>

          <div>
            <label for="seo_description" class="block text-sm font-semibold text-slate-700 mb-1.5">SEO Description</label>
            <textarea id="seo_description" name="seo_description" rows="3"
                      placeholder="Explore all Tata Motors electric vehicles in India. Compare prices, range and features on Charj.in."
                      maxlength="160"
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none resize-y"><?= esc(old('seo_description', $brand['seo_description'] ?? '')) ?></textarea>
            <p class="mt-1 text-xs text-slate-400">Recommended: 120-160 characters</p>
          </div>
        </div>
      </div>

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">

      <!-- Status & Settings -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-5 pb-3 border-b border-slate-100">Settings</h2>

        <div class="space-y-5">

          <!-- Status -->
          <div>
            <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
            <select id="status" name="status"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
              <?php $statusVal = old('status', $brand['status'] ?? 'active'); ?>
              <option value="active"    <?= $statusVal === 'active'    ? 'selected' : '' ?>>Active</option>
              <option value="inactive"  <?= $statusVal === 'inactive'  ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>

          <!-- Featured -->
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Featured Brand</label>
            <div class="flex items-center gap-3" x-data="{ featured: <?= json_encode(!empty(old('featured', $brand['featured'] ?? false))) ?> }">
              <button type="button"
                      @click="featured = !featured"
                      :class="featured ? 'bg-emerald-500' : 'bg-slate-200'"
                      class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                <span :class="featured ? 'translate-x-6' : 'translate-x-1'"
                      class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
              </button>
              <input type="hidden" name="featured" :value="featured ? '1' : '0'">
              <span class="text-sm text-slate-600" x-text="featured ? 'Featured on homepage' : 'Not featured'"></span>
            </div>
            <p class="mt-1.5 text-xs text-slate-400">Featured brands appear in the homepage brand showcase.</p>
          </div>

          <!-- Sort Order -->
          <div>
            <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-1.5">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" min="0"
                   value="<?= esc(old('sort_order', $brand['sort_order'] ?? 0)) ?>"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none">
            <p class="mt-1 text-xs text-slate-400">Lower number = appears first</p>
          </div>

        </div>
      </div>

      <!-- Save Buttons -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-3">
        <button type="submit"
                class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 text-sm font-bold transition-colors">
          <?= $isEdit ? 'Save Changes' : 'Create Brand' ?>
        </button>
        <a href="/admin/brands"
           class="block w-full rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
          Cancel
        </a>
      </div>

      <?php if ($isEdit && !empty($brand['id'])): ?>
      <!-- Danger Zone -->
      <div class="rounded-xl bg-red-50 border border-red-200 p-5">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Danger Zone</h3>
        <p class="text-xs text-red-600 mb-3">Deleting this brand cannot be undone.</p>
        <form method="post" action="/admin/brands/delete/<?= esc($brand['id']) ?>"
              onsubmit="return confirm('Permanently delete this brand?')">
          <?= csrf_field() ?>
          <button type="submit"
                  class="w-full rounded-xl border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors">
            Delete Brand
          </button>
        </form>
      </div>
      <?php endif; ?>

    </div>
  </div>
</form>

<script>
function brandForm() {
    return {
        name: '<?= addslashes(old('name', $brand['name'] ?? '')) ?>',
        slug: '<?= addslashes(old('slug', $brand['slug'] ?? '')) ?>',
        generateSlug() {
            if (!this.slug || this.slug === this._lastAuto) {
                this.slug = this.name
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/[\s_]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                this._lastAuto = this.slug;
            }
        },
        _lastAuto: '<?= addslashes(old('slug', $brand['slug'] ?? '')) ?>'
    }
}
</script>

<?= $this->endSection() ?>
