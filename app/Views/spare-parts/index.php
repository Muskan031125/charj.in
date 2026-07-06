<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 py-16 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Hero Section -->
        <div class="mb-16 text-center">
            <span class="inline-block text-emerald-600 text-xs font-bold uppercase tracking-widest mb-4">Quality Parts & Accessories</span>
            <h1 class="text-5xl sm:text-6xl font-black text-slate-900 mb-4 leading-tight">EV Spare Parts<br><span style="color:#16a34a">& Accessories</span></h1>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">Find authentic spare parts and accessories for your electric vehicle from trusted vendors across India.</p>
        </div>

        <!-- Category Filter -->
        <div class="mb-12 flex flex-wrap gap-3 justify-center">
            <a href="<?= base_url('spare-parts') ?>" class="px-6 py-3 rounded-full font-semibold transition-all duration-200 <?= empty($selectedCategory) ? 'bg-emerald-600 text-white hover:bg-emerald-500' : 'bg-slate-200 border-2 border-emerald-300 text-slate-700 hover:text-slate-900 hover:border-emerald-500' ?>">All Parts</a>
            <?php foreach ($categories as $key => $label): ?>
                <a href="<?= base_url('spare-parts?category=' . $key) ?>" class="px-6 py-3 rounded-full font-semibold transition-all duration-200 <?= ($selectedCategory === $key) ? 'bg-emerald-600 text-white hover:bg-emerald-500' : 'bg-slate-200 border-2 border-emerald-300 text-slate-700 hover:text-slate-900 hover:border-emerald-500' ?>">
                    <?= esc($label) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Parts Grid -->
        <?php if ($spareParts): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                <?php foreach ($spareParts as $part): ?>
                    <a href="<?= base_url('spare-parts/' . $part['slug']) ?>" class="group rounded-2xl overflow-hidden border-2 border-emerald-200 hover:border-emerald-500 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1" style="background:#f5faf9">
                        <?php if ($part['image_url']): ?>
                            <img src="<?= base_url($part['image_url']) ?>" alt="<?= esc($part['part_name']) ?>" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                        <?php else: ?>
                            <div class="w-full h-56 bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                <span class="text-slate-600 text-lg">🔧</span>
                            </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <h3 class="font-bold text-slate-900 mb-2 text-lg">
                                <?= esc($part['part_name']) ?>
                            </h3>
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2"><?= esc($part['description']) ?></p>
                            <?php if ($part['price']): ?>
                                <div class="text-2xl font-black" style="color:#16a34a" mb-3>₹<?= number_format($part['price'], 0) ?></div>
                            <?php endif; ?>
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <?php if ($part['vendor_name']): ?>
                                        <p class="text-slate-600">By <span class="text-slate-800 font-semibold"><?= esc($part['vendor_name']) ?></span></p>
                                    <?php endif; ?>
                                </div>
                                <span class="font-bold <?= $part['in_stock'] ? 'text-emerald-600' : 'text-red-600' ?>">
                                    <?= $part['in_stock'] ? '✓ In Stock' : '✗ Out' ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="mb-16">
                <?= $pager->links() ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <p class="text-slate-600 text-xl">No spare parts found in this category.</p>
                <a href="<?= base_url('spare-parts') ?>" class="inline-block mt-6 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-3 rounded-full transition-all">
                    View All Parts →
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
