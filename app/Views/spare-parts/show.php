<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <!-- Back Link -->
        <a href="<?= base_url('spare-parts') ?>" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-8">
            ← Back to Spare Parts
        </a>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Image -->
                <div>
                    <?php if ($sparePart['image_url']): ?>
                        <img src="<?= base_url($sparePart['image_url']) ?>" alt="<?= esc($sparePart['part_name']) ?>" class="w-full rounded-lg">
                    <?php else: ?>
                        <div class="w-full bg-slate-100 rounded-lg flex items-center justify-center h-64">
                            <span class="text-slate-400">No image available</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Details -->
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= esc($sparePart['part_name']) ?></h1>

                    <?php if ($sparePart['price']): ?>
                        <div class="text-3xl font-bold text-emerald-600 mb-6">₹<?= number_format($sparePart['price'], 0) ?></div>
                    <?php endif; ?>

                    <div class="space-y-4 mb-8">
                        <div>
                            <h3 class="font-semibold text-slate-900">Category</h3>
                            <p class="text-slate-600 capitalize"><?= esc($sparePart['category']) ?></p>
                        </div>

                        <?php if ($sparePart['part_number']): ?>
                            <div>
                                <h3 class="font-semibold text-slate-900">Part Number</h3>
                                <p class="text-slate-600"><?= esc($sparePart['part_number']) ?></p>
                            </div>
                        <?php endif; ?>

                        <div>
                            <h3 class="font-semibold text-slate-900">Availability</h3>
                            <p class="<?= $sparePart['in_stock'] ? 'text-emerald-600' : 'text-red-600' ?> font-medium">
                                <?= $sparePart['in_stock'] ? '✓ In Stock' : '✗ Out of Stock' ?>
                            </p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-900">Vendor</h3>
                            <p class="text-slate-600"><?= esc($sparePart['vendor_name'] ?? 'Not specified') ?></p>
                        </div>

                        <?php if ($sparePart['vendor_contact']): ?>
                            <div>
                                <h3 class="font-semibold text-slate-900">Contact</h3>
                                <p class="text-slate-600"><?= esc($sparePart['vendor_contact']) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($sparePart['vendor_url']): ?>
                            <div>
                                <a href="<?= esc($sparePart['vendor_url']) ?>" target="_blank" class="inline-block bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">
                                    Visit Vendor →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Description</h2>
                <div class="prose prose-sm max-w-none text-slate-700">
                    <?= nl2br(esc($sparePart['description'])) ?>
                </div>
            </div>

            <?php if ($sparePart['compatible_models']): ?>
                <div class="mt-8 pt-8 border-t">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">Compatible Models</h2>
                    <p class="text-slate-700"><?= nl2br(esc($sparePart['compatible_models'])) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Related Parts -->
        <?php if ($relatedParts): ?>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Related Spare Parts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($relatedParts as $related): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                            <?php if ($related['image_url']): ?>
                                <img src="<?= base_url($related['image_url']) ?>" alt="<?= esc($related['part_name']) ?>" class="w-full h-40 object-cover">
                            <?php else: ?>
                                <div class="w-full h-40 bg-slate-100"></div>
                            <?php endif; ?>
                            <div class="p-4">
                                <h3 class="font-semibold text-slate-900 mb-2">
                                    <a href="<?= base_url('spare-parts/' . $related['slug']) ?>" class="hover:text-emerald-600">
                                        <?= esc($related['part_name']) ?>
                                    </a>
                                </h3>
                                <?php if ($related['price']): ?>
                                    <p class="text-emerald-600 font-bold">₹<?= number_format($related['price'], 0) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
