<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 py-16 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Hero Section -->
        <div class="mb-16 text-center">
            <span class="inline-block text-emerald-600 text-xs font-bold uppercase tracking-widest mb-4">Latest Updates</span>
            <h1 class="text-5xl sm:text-6xl font-black text-slate-900 mb-4 leading-tight">EV News &<br><span style="color:#16a34a">Announcements</span></h1>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">Stay updated with the latest news, policy changes, product launches, and important announcements about electric vehicles in India.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <?php if ($announcements): ?>
                    <div class="space-y-6 mb-16">
                        <?php foreach ($announcements as $announcement): ?>
                            <a href="<?= base_url('announcements/' . $announcement['slug']) ?>" class="group block rounded-2xl overflow-hidden border-2 border-emerald-200 hover:border-emerald-500 transition-all hover:shadow-2xl hover:-translate-y-1" style="background:#f5faf9">
                                <?php if ($announcement['banner_image']): ?>
                                    <img src="<?= base_url($announcement['banner_image']) ?>" alt="<?= esc($announcement['title']) ?>" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php endif; ?>
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="inline-block px-3 py-1 bg-emerald-500/30 text-emerald-300 text-xs font-bold rounded-full capitalize border border-emerald-500/50">
                                            <?= esc(str_replace('-', ' ', $announcement['type'])) ?>
                                        </span>
                                        <?php if ($announcement['is_pinned']): ?>
                                            <span class="text-emerald-400 font-bold text-lg">📌</span>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-emerald-600 transition-colors">
                                        <?= esc($announcement['title']) ?>
                                    </h3>

                                    <p class="text-slate-600 mb-4 line-clamp-2">
                                        <?= esc(strip_tags($announcement['content'])) ?>
                                    </p>

                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-500">
                                            📅 <?= date('M d, Y', strtotime($announcement['published_at'])) ?>
                                        </span>
                                        <span style="color:#16a34a" class="font-bold group-hover:text-emerald-600">
                                            Read More →
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
                        <p class="text-slate-400 text-xl">No announcements at the moment.</p>
                        <p class="text-slate-500 mt-2">Check back soon for updates!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar - Pinned Announcements -->
            <?php if ($pinnedAnnouncements): ?>
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-emerald-50 to-green-50 rounded-2xl p-6 border-2 border-emerald-200 sticky top-24">
                        <h3 class="text-xl font-black text-slate-900 mb-6">⭐ Important<br>Updates</h3>
                        <div class="space-y-4">
                            <?php foreach ($pinnedAnnouncements as $pinned): ?>
                                <a href="<?= base_url('announcements/' . $pinned['slug']) ?>" class="block rounded-lg p-4 border-2 border-emerald-200 hover:border-emerald-500 transition-all hover:bg-emerald-100" style="background:#f5faf9">
                                    <h4 class="font-bold text-slate-900 text-sm mb-2 hover:text-emerald-600">
                                        <?= esc($pinned['title']) ?>
                                    </h4>
                                    <p class="text-xs text-slate-600">
                                        📅 <?= date('M d, Y', strtotime($pinned['published_at'])) ?>
                                    </p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
