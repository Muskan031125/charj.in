<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php $page_title = 'New Event'; ?>

<div class="mb-5 flex items-center gap-3">
    <a href="/admin/events" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50">← Back</a>
    <h1 class="text-2xl font-black text-slate-900">New EV Event</h1>
</div>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>
<?php if (!empty($errors)): ?>
<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
    <ul class="list-disc pl-4 space-y-1"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="/admin/events/store">
    <?= csrf_field() ?>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        <div class="lg:col-span-2 space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Event Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="<?= esc(old('title')) ?>" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" required
                                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"><?= esc(old('description')) ?></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">City</label>
                            <input type="text" name="city" value="<?= esc(old('city')) ?>"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Organizer</label>
                            <input type="text" name="organizer" value="<?= esc(old('organizer')) ?>"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Venue Address</label>
                        <input type="text" name="venue_address" value="<?= esc(old('venue_address')) ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" value="<?= esc(old('start_date')) ?>" required
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">End Date</label>
                            <input type="date" name="end_date" value="<?= esc(old('end_date')) ?>"
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Registration URL</label>
                        <input type="url" name="registration_url" value="<?= esc(old('registration_url')) ?>" placeholder="https://..."
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Banner Image URL</label>
                        <input type="url" name="banner_image" value="<?= esc(old('banner_image')) ?>" placeholder="https://..."
                               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-bold text-slate-700 uppercase tracking-wide">Settings</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Event Type</label>
                        <select name="event_type" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <?php foreach (['expo','launch','test_drive','webinar','conference','other'] as $t): ?>
                            <option value="<?= $t ?>"><?= ucwords(str_replace('_',' ',$t)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
                        <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600">
                        <label for="is_featured" class="text-sm font-medium text-slate-700">Feature this event</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">
                Create Event
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
