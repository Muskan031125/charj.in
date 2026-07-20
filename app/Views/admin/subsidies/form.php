<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $page_title = 'Add Subsidy'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-900">Add Subsidy</h1>
    <p class="mt-0.5 text-sm text-slate-500"><a href="/admin/subsidies" class="text-emerald-600 hover:underline">← Back to Subsidies</a></p>
</div>

<div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <form method="post" action="/admin/subsidies/store" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">State <span class="text-red-500">*</span></label>
            <input type="text" name="state" required
                   value="<?= esc(old('state')) ?>"
                   placeholder="e.g. Maharashtra"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Vehicle Type</label>
            <select name="vehicle_type" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">All Types</option>
                <option value="2W">2-Wheeler (Scooter/Bike)</option>
                <option value="3W">3-Wheeler (Auto/Rickshaw)</option>
                <option value="4W">4-Wheeler (Car)</option>
                <option value="Commercial">Commercial</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Scheme Name <span class="text-red-500">*</span></label>
            <input type="text" name="scheme_name" required
                   value="<?= esc(old('scheme_name')) ?>"
                   placeholder="e.g. FAME-II Subsidy"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Subsidy Amount (₹)</label>
            <input type="number" name="amount" min="0"
                   value="<?= esc(old('amount')) ?>"
                   placeholder="e.g. 15000"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Conditions / Notes</label>
            <textarea name="conditions" rows="3"
                      placeholder="e.g. Applicable for first 10,000 EVs registered"
                      class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"><?= esc(old('conditions')) ?></textarea>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Valid Until</label>
            <input type="date" name="valid_until"
                   value="<?= esc(old('valid_until')) ?>"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                Save Subsidy
            </button>
            <a href="/admin/subsidies"
               class="rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
