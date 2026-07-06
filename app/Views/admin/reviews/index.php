<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-black text-slate-900">Reviews</h1>
</div>

<!-- Filter tabs -->
<div class="mb-5 flex gap-1 rounded-xl bg-slate-100 p-1 w-fit">
    <?php
    $tabs = ['pending' => 'Pending', 'published' => 'Approved', 'rejected' => 'Rejected'];
    foreach ($tabs as $key => $label):
        $active = ($status === $key);
    ?>
    <a href="/admin/reviews?status=<?= $key ?>"
       class="rounded-lg px-4 py-2 text-sm font-semibold transition-colors <?= $active ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">
        <?= $label ?>
        <span class="ml-1.5 rounded-full px-1.5 py-0.5 text-xs <?= $active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' ?>">
            <?= (int)($counts[$key] ?? 0) ?>
        </span>
    </a>
    <?php endforeach; ?>
</div>

<!-- Reviews list -->
<?php if (!empty($reviews)): ?>
<div class="space-y-4">
    <?php foreach ($reviews as $r): ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <!-- Vehicle name -->
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">
                    <?= esc($r['vehicle_name'] ?? 'Unknown Vehicle') ?>
                </p>

                <!-- Rating stars -->
                <div class="flex items-center gap-1 mb-2">
                    <?php
                    $rating = (int)($r['rating'] ?? 0);
                    for ($i = 1; $i <= 5; $i++):
                    ?>
                    <svg class="h-4 w-4 <?= $i <= $rating ? 'text-amber-400' : 'text-slate-200' ?>"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?php endfor; ?>
                    <span class="ml-1 text-sm font-semibold text-slate-700"><?= $rating ?>/5</span>
                </div>

                <!-- Review text -->
                <?php if (!empty($r['review_text']) || !empty($r['body']) || !empty($r['content'])): ?>
                <p class="text-sm text-slate-700 leading-relaxed">
                    <?= esc($r['review_text'] ?? $r['body'] ?? $r['content'] ?? '') ?>
                </p>
                <?php endif; ?>

                <!-- Reviewer & date -->
                <div class="mt-3 flex items-center gap-3 text-xs text-slate-400">
                    <span class="font-medium text-slate-600"><?= esc($r['reviewer_name'] ?? $r['name'] ?? 'Anonymous') ?></span>
                    <span>&bull;</span>
                    <span><?= isset($r['created_at']) ? date('d M Y', strtotime($r['created_at'])) : '—' ?></span>
                    <?php if (!empty($r['reviewer_email'] ?? $r['email'])): ?>
                        <span>&bull;</span>
                        <span><?= esc($r['reviewer_email'] ?? $r['email']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex shrink-0 flex-col gap-2">
                <?php if ($status === 'pending' || $status === 'rejected'): ?>
                <a href="/admin/reviews/approve/<?= $r['id'] ?>"
                   class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 text-center">
                    Approve
                </a>
                <?php endif; ?>
                <?php if ($status === 'pending' || $status === 'approved'): ?>
                <a href="/admin/reviews/reject/<?= $r['id'] ?>"
                   class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-100 text-center">
                    Reject
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-slate-200">
    <p class="text-slate-400">No <?= esc($status) ?> reviews found.</p>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
