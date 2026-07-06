<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$page_title = 'Lead #' . ($lead['id'] ?? '');
$statusColors = [
    'new'       => 'bg-blue-100 text-blue-800 ring-blue-200',
    'contacted' => 'bg-amber-100 text-amber-800 ring-amber-200',
    'qualified' => 'bg-purple-100 text-purple-800 ring-purple-200',
    'converted' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
    'lost'      => 'bg-red-100 text-red-800 ring-red-200',
    'closed'    => 'bg-slate-100 text-slate-700 ring-slate-200',
];
$sc = $statusColors[$lead['status'] ?? 'new'] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
?>

<!-- Header row -->
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3">
        <a href="/admin/leads" class="rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Lead #<?= esc($lead['id']) ?></h1>
            <p class="mt-0.5 text-sm text-slate-500">Received <?= date('d M Y, g:i A', strtotime($lead['created_at'])) ?></p>
        </div>
    </div>
    <span class="inline-block rounded-full px-4 py-1.5 text-sm font-bold ring-1 <?= $sc ?>">
        <?= esc(ucfirst($lead['status'])) ?>
    </span>
</div>

<!-- 2-column layout -->
<div class="grid gap-6 lg:grid-cols-3">

    <!-- LEFT: Lead info -->
    <div class="space-y-5 lg:col-span-2">

        <!-- Contact details -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">Contact Details</h2>
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2">
                <?php
                $fields = [
                    'Name'      => $lead['name'] ?? '—',
                    'Mobile'    => $lead['mobile'] ?? '—',
                    'Email'     => $lead['email'] ?? '—',
                    'City'      => $lead['city'] ?? '—',
                    'Budget'    => $lead['budget'] ?? '—',
                    'Timeline'  => $lead['purchase_timeline'] ?? '—',
                ];
                foreach ($fields as $label => $value):
                ?>
                <div class="flex flex-col">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400"><?= $label ?></dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-900">
                        <?php if ($label === 'Mobile'): ?>
                            <a href="tel:<?= esc($value) ?>" class="text-emerald-600 hover:underline"><?= esc($value) ?></a>
                        <?php elseif ($label === 'Email' && $value !== '—'): ?>
                            <a href="mailto:<?= esc($value) ?>" class="text-emerald-600 hover:underline"><?= esc($value) ?></a>
                        <?php else: ?>
                            <?= esc($value) ?>
                        <?php endif; ?>
                    </dd>
                </div>
                <?php endforeach; ?>
            </dl>
        </div>

        <!-- Enquiry details -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">Enquiry Details</h2>
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Lead Type</dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-900">
                        <?= esc(ucwords(str_replace('_', ' ', $lead['lead_type'] ?? '—'))) ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Lead ID</dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-900">#<?= esc($lead['id']) ?></dd>
                </div>
            </dl>
            <?php if (!empty($lead['message'])): ?>
            <div class="mt-4">
                <dt class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Message / Requirement</dt>
                <dd class="rounded-xl bg-slate-50 p-3 text-sm text-slate-700"><?= nl2br(esc($lead['message'])) ?></dd>
            </div>
            <?php endif; ?>
        </div>

        <!-- Vehicle info -->
        <?php if (!empty($lead['vehicle_id'])): ?>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">Vehicle of Interest</h2>
            <dl class="grid gap-3 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Vehicle</dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-900"><?= esc($lead['vehicle_name'] ?? '—') ?></dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Brand</dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-900"><?= esc($lead['brand_name'] ?? '—') ?></dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Category</dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-900"><?= esc($lead['category_name'] ?? '—') ?></dd>
                </div>
            </dl>
            <div class="mt-3">
                <a href="/admin/vehicles/<?= esc($lead['vehicle_id']) ?>/edit"
                   class="text-xs font-medium text-emerald-600 hover:underline">View vehicle →</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Source / UTM tracking -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">Source &amp; Tracking</h2>
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2">
                <?php
                $trackingFields = [
                    'Source'         => $lead['source'] ?? '—',
                    'UTM Campaign'   => $lead['utm_campaign'] ?? '—',
                    'UTM Medium'     => $lead['utm_medium'] ?? '—',
                    'UTM Source'     => $lead['utm_source'] ?? '—',
                    'UTM Term'       => $lead['utm_term'] ?? '—',
                    'Referrer Page'  => $lead['referrer_page'] ?? '—',
                    'Landing Page'   => $lead['landing_page'] ?? '—',
                    'IP Address'     => $lead['ip_address'] ?? '—',
                ];
                foreach ($trackingFields as $label => $value):
                    if ($value === '—') continue;
                ?>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400"><?= $label ?></dt>
                    <dd class="mt-0.5 text-sm text-slate-700 break-all"><?= esc($value) ?></dd>
                </div>
                <?php endforeach; ?>
                <?php if (collect($trackingFields)->every(fn($v) => $v === '—') ?? false): ?>
                    <p class="text-sm text-slate-400">No tracking data available.</p>
                <?php endif; ?>
            </dl>
        </div>

    </div><!-- /left -->

    <!-- RIGHT: Status + Notes -->
    <div class="space-y-5">

        <!-- Status update form -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">Update Status</h2>
            <form method="post" action="/admin/leads/<?= esc($lead['id']) ?>/status">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PATCH">
                <select name="status"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <?php foreach (['new','contacted','qualified','converted','lost','closed'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($lead['status'] ?? '') === $s ? 'selected' : '' ?>>
                            <?= ucfirst($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit"
                        class="mt-3 w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
                    Save Status
                </button>
            </form>

            <div class="mt-4 border-t border-slate-100 pt-4">
                <p class="mb-1 text-xs font-semibold text-slate-400">Quick actions</p>
                <div class="flex flex-col gap-2">
                    <a href="tel:<?= esc($lead['mobile']) ?>"
                       class="flex items-center gap-2 rounded-lg bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Call <?= esc($lead['mobile']) ?>
                    </a>
                    <?php if (!empty($lead['email'])): ?>
                    <a href="mailto:<?= esc($lead['email']) ?>"
                       class="flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email
                    </a>
                    <?php endif; ?>
                    <a href="https://wa.me/91<?= preg_replace('/\D/', '', $lead['mobile']) ?>" target="_blank" rel="noopener"
                       class="flex items-center gap-2 rounded-lg bg-green-50 px-3 py-2 text-sm font-semibold text-green-700 hover:bg-green-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.121 1.532 5.847L.532 22.5l4.778-1.253A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.817 9.817 0 01-5.005-1.373l-.359-.214-3.722.976.994-3.632-.235-.373A9.818 9.818 0 012.182 12C2.182 6.578 6.578 2.182 12 2.182S21.818 6.578 21.818 12 17.422 21.818 12 21.818z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Add note -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">Add Note</h2>
            <form method="post" action="/admin/leads/<?= esc($lead['id']) ?>/notes">
                <?= csrf_field() ?>
                <textarea name="note" rows="3" required
                          placeholder="Follow-up note, outcome of call, next step..."
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                <button type="submit"
                        class="mt-2 w-full rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-900">
                    Save Note
                </button>
            </form>
        </div>

        <!-- Notes timeline -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                Notes <span class="ml-1 rounded-full bg-slate-100 px-1.5 py-0.5 text-xs"><?= count($notes ?? []) ?></span>
            </h2>

            <?php if (!empty($notes)): ?>
            <div class="space-y-4">
                <?php foreach (($notes ?? []) as $note): ?>
                <div class="relative pl-5">
                    <div class="absolute left-0 top-1.5 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-white"></div>
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-sm text-slate-800"><?= nl2br(esc($note['note'])) ?></p>
                        <p class="mt-1.5 text-xs text-slate-400">
                            <?= esc($note['admin_name'] ?? 'Admin') ?> &middot;
                            <?= date('d M Y, g:i A', strtotime($note['created_at'])) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p class="text-center text-sm text-slate-400">No notes yet. Add one above.</p>
            <?php endif; ?>
        </div>

    </div><!-- /right -->
</div>

<?= $this->endSection() ?>
