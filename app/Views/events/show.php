<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <!-- Back Link -->
        <a href="<?= base_url('events') ?>" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-8">
            ← Back to Events
        </a>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-12">
            <?php if ($event['banner_image']): ?>
                <img src="<?= base_url($event['banner_image']) ?>" alt="<?= esc($event['title']) ?>" class="w-full h-96 object-cover">
            <?php else: ?>
                <div class="w-full h-96 bg-gradient-to-r from-emerald-400 to-blue-500"></div>
            <?php endif; ?>

            <div class="p-8">
                <h1 class="text-4xl font-bold text-slate-900 mb-4"><?= esc($event['title']) ?></h1>

                <!-- Event Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b">
                    <div>
                        <h3 class="font-semibold text-slate-900 mb-4">Event Details</h3>
                        <div class="space-y-3 text-slate-700">
                            <div>
                                <p class="text-sm text-slate-600">Start Date</p>
                                <p class="font-medium"><?= date('F d, Y H:i A', strtotime($event['start_date'])) ?></p>
                            </div>

                            <?php if ($event['end_date']): ?>
                                <div>
                                    <p class="text-sm text-slate-600">End Date</p>
                                    <p class="font-medium"><?= date('F d, Y H:i A', strtotime($event['end_date'])) ?></p>
                                </div>
                            <?php endif; ?>

                            <div>
                                <p class="text-sm text-slate-600">Event Type</p>
                                <p class="font-medium capitalize"><?= esc(str_replace('-', ' ', $event['event_type'])) ?></p>
                            </div>

                            <?php if ($event['organizer']): ?>
                                <div>
                                    <p class="text-sm text-slate-600">Organizer</p>
                                    <p class="font-medium"><?= esc($event['organizer']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-900 mb-4">Location</h3>
                        <div class="space-y-3 text-slate-700">
                            <?php if ($event['city']): ?>
                                <div>
                                    <p class="text-sm text-slate-600">City</p>
                                    <p class="font-medium"><?= esc($event['city']) ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($event['venue_address']): ?>
                                <div>
                                    <p class="text-sm text-slate-600">Venue</p>
                                    <p class="font-medium"><?= esc($event['venue_address']) ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($event['registration_url']): ?>
                                <a href="<?= esc($event['registration_url']) ?>" target="_blank" class="inline-block mt-4 bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 font-medium">
                                    Register Now →
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <h2 class="text-2xl font-bold text-slate-900 mb-4">About This Event</h2>
                <div class="prose prose-sm max-w-none text-slate-700 mb-8">
                    <?= nl2br(esc($event['description'])) ?>
                </div>
            </div>
        </div>

        <!-- Related Events -->
        <?php if ($relatedEvents): ?>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Other Upcoming Events</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($relatedEvents as $related): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-400 to-blue-500 h-32"></div>
                            <div class="p-4">
                                <p class="text-sm text-emerald-600 font-semibold mb-1">
                                    <?= date('M d, Y', strtotime($related['start_date'])) ?>
                                </p>
                                <h4 class="font-semibold text-slate-900 mb-2 line-clamp-2">
                                    <a href="<?= base_url('events/' . $related['slug']) ?>" class="hover:text-emerald-600">
                                        <?= esc($related['title']) ?>
                                    </a>
                                </h4>
                                <?php if ($related['city']): ?>
                                    <p class="text-sm text-slate-600">📍 <?= esc($related['city']) ?></p>
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
