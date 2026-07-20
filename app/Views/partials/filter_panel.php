<?php
$req = $request ?? service('request');
$activeCategory = $req->getGet('category');
$activeBrands   = $req->getGet('brand') ?? [];
if (!is_array($activeBrands)) $activeBrands = [$activeBrands];
?>
<div class="space-y-6">
  <div>
    <h4 class="font-bold text-slate-100 mb-3 text-xs uppercase tracking-widest">Category</h4>
    <?php foreach ($categories as $cat): ?>
    <label class="flex items-center gap-2.5 text-sm py-1.5 cursor-pointer text-slate-400 hover:text-[#1AFFCC] transition-colors">
      <input type="radio" name="category" value="<?= esc($cat['slug']) ?>"
        <?= $activeCategory === $cat['slug'] ? 'checked' : '' ?> class="accent-[#00A896]">
      <?= esc($cat['name']) ?>
    </label>
    <?php endforeach; ?>
  </div>
  <div>
    <h4 class="font-bold text-slate-100 mb-3 text-xs uppercase tracking-widest">Brand</h4>
    <?php foreach ($brands as $brand): ?>
    <label class="flex items-center gap-2.5 text-sm py-1.5 cursor-pointer text-slate-400 hover:text-[#1AFFCC] transition-colors">
      <input type="checkbox" name="brand[]" value="<?= esc($brand['slug']) ?>"
        <?= in_array($brand['slug'], $activeBrands) ? 'checked' : '' ?> class="accent-[#00A896]">
      <?= esc($brand['name']) ?>
    </label>
    <?php endforeach; ?>
  </div>
</div>
