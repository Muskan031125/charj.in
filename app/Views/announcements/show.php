<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <!-- Back Link -->
        <a href="<?= base_url('announcements') ?>" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-8">
            ← Back to Announcements
        </a>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-12">
            <?php if ($announcement['banner_image']): ?>
                <img src="<?= base_url($announcement['banner_image']) ?>" alt="<?= esc($announcement['title']) ?>" class="w-full h-96 object-cover">
            <?php else: ?>
                <div class="w-full h-96 bg-gradient-to-r from-blue-400 to-blue-600"></div>
            <?php endif; ?>

            <div class="p-8">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full capitalize mb-4">
                            <?= esc(str_replace('-', ' ', $announcement['type'])) ?>
                        </span>
                        <h1 class="text-4xl font-bold text-slate-900"><?= esc($announcement['title']) ?></h1>
                    </div>
                    <?php if ($announcement['is_pinned']): ?>
                        <div class="text-3xl">📌</div>
                    <?php endif; ?>
                </div>

                <p class="text-sm text-slate-600 mb-8 pb-8 border-b">
                    Published on <?= date('F d, Y \a\t h:i A', strtotime($announcement['published_at'])) ?>
                </p>

                <!-- Content -->
                <div class="prose prose-lg max-w-none mb-8">
                    <?= nl2br(esc($announcement['content'])) ?>
                </div>

                <?php if ($announcement['link_url']): ?>
                    <div class="pt-8 border-t">
                        <a href="<?= esc($announcement['link_url']) ?>" target="_blank" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                            Read Full Story →
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Announcements -->
        <?php if ($relatedAnnouncements): ?>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Other Announcements</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($relatedAnnouncements as $related): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                            <?php if ($related['banner_image']): ?>
                                <img src="<?= base_url($related['banner_image']) ?>" alt="<?= esc($related['title']) ?>" class="w-full h-40 object-cover">
                            <?php else: ?>
                                <div class="w-full h-40 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                            <?php endif; ?>
                            <div class="p-4">
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded capitalize mb-2">
                                    <?= esc(str_replace('-', ' ', $related['type'])) ?>
                                </span>
                                <h4 class="font-semibold text-slate-900 mb-2 line-clamp-2">
                                    <a href="<?= base_url('announcements/' . $related['slug']) ?>" class="hover:text-emerald-600">
                                        <?= esc($related['title']) ?>
                                    </a>
                                </h4>
                                <p class="text-sm text-slate-600">
                                    <?= date('M d, Y', strtotime($related['published_at'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
