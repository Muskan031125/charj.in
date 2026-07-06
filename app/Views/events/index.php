<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 py-16 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Hero Section -->
        <div class="mb-16 text-center">
            <span class="inline-block text-emerald-600 text-xs font-bold uppercase tracking-widest mb-4">Industry Events</span>
            <h1 class="text-5xl sm:text-6xl font-black text-slate-900 mb-4 leading-tight">EV Events<br><span style="color:#16a34a">& Expos</span></h1>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">Discover upcoming electric vehicle expos, product launches, test drives, and webinars happening across India.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <?php if ($events): ?>
                    <div class="space-y-5 mb-16">
                        <?php foreach ($events as $event): ?>
                            <a href="<?= base_url('events/' . $event['slug']) ?>" class="group block rounded-2xl p-6 border-2 border-emerald-200 hover:border-emerald-500 transition-all duration-300" style="background:#f5faf9">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                                    <!-- Date -->
                                    <div class="flex flex-col justify-center">
                                        <div class="text-3xl font-black" style="color:#16a34a" leading-none>
                                            <?= date('d', strtotime($event['start_date'])) ?>
                                        </div>
                                        <div class="text-sm text-slate-600 mt-2">
                                            <?= date('M', strtotime($event['start_date'])) ?>
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            <?= date('Y', strtotime($event['start_date'])) ?>
                                        </div>
                                        <?php if ($event['end_date']): ?>
                                            <div class="text-xs text-slate-500 mt-2">
                                                to <?= date('d M', strtotime($event['end_date'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="sm:col-span-3">
                                        <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-emerald-600 transition-colors">
                                            <?= esc($event['title']) ?>
                                        </h3>
                                        <p class="text-slate-600 mb-4 line-clamp-2"><?= esc($event['description']) ?></p>

                                        <div class="flex flex-wrap gap-3 text-sm">
                                            <?php if ($event['city']): ?>
                                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">📍 <?= esc($event['city']) ?></span>
                                            <?php endif; ?>
                                            <span class="capitalize px-3 py-1 rounded-full bg-slate-200 text-slate-700">
                                                🎪 <?= esc(str_replace('-', ' ', $event['event_type'])) ?>
                                            </span>
                                        </div>

                                        <?php if ($event['registration_url']): ?>
                                            <a href="<?= esc($event['registration_url']) ?>" target="_blank" onclick="event.stopPropagation()" class="inline-block mt-4 font-bold text-sm" style="color:#16a34a">
                                                Register Now →
                                            </a>
                                        <?php endif; ?>
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
                        <p class="text-slate-400 text-xl">No upcoming events at the moment.</p>
                        <p class="text-slate-500 mt-2">Check back soon for exciting EV expos and launches!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar - Featured Events -->
            <?php if ($featuredEvents): ?>
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-emerald-50 to-green-50 rounded-2xl p-6 border-2 border-emerald-200 sticky top-24">
                        <h3 class="text-xl font-black text-slate-900 mb-6">🌟 Featured<br>Events</h3>
                        <div class="space-y-4">
                            <?php foreach ($featuredEvents as $featured): ?>
                                <a href="<?= base_url('events/' . $featured['slug']) ?>" class="block rounded-lg p-4 border-2 border-emerald-200 hover:border-emerald-500 transition-all hover:bg-emerald-100" style="background:#f5faf9">
                                    <p class="text-xs font-bold mb-2 uppercase" style="color:#16a34a">
                                        📅 <?= date('M d, Y', strtotime($featured['start_date'])) ?>
                                    </p>
                                    <h4 class="font-bold text-slate-900 text-sm mb-2 hover:text-emerald-600">
                                        <?= esc($featured['title']) ?>
                                    </h4>
                                    <?php if ($featured['city']): ?>
                                        <p class="text-xs text-slate-600">📍 <?= esc($featured['city']) ?></p>
                                    <?php endif; ?>
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
