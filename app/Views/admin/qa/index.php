<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $page_title = 'Owner Q&A'; ?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Owner Q&amp;A</h1>
        <p class="mt-0.5 text-sm text-slate-500">Moderate user-submitted questions and provide answers</p>
    </div>
</div>

<?php if (empty($questions)): ?>
    <div class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-400">No questions yet. Questions submitted via vehicle pages will appear here.</p>
    </div>
<?php else: ?>
<div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3 text-left">Vehicle</th>
                <th class="px-4 py-3 text-left">Asker</th>
                <th class="px-4 py-3 text-left">Question</th>
                <th class="px-4 py-3 text-left">Answer</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($questions as $q): ?>
            <tr class="<?= $q['is_approved'] ? '' : 'bg-yellow-50' ?> hover:bg-slate-50">
                <td class="px-4 py-3">
                    <a href="/vehicles/<?= esc($q['vehicle_slug']) ?>" target="_blank"
                       class="text-xs font-medium text-emerald-600 hover:underline">
                        <?= esc($q['vehicle_name'] ?? '—') ?>
                    </a>
                </td>
                <td class="px-4 py-3 text-xs text-slate-700"><?= esc($q['name']) ?></td>
                <td class="px-4 py-3 text-xs text-slate-700 max-w-xs">
                    <?= esc(mb_strimwidth($q['question'], 0, 120, '…')) ?>
                </td>
                <td class="px-4 py-3 text-xs text-slate-500 max-w-xs">
                    <?= $q['answer'] ? esc(mb_strimwidth($q['answer'], 0, 80, '…')) : '<span class="italic text-slate-300">No answer yet</span>' ?>
                </td>
                <td class="px-4 py-3">
                    <?php if ($q['is_approved']): ?>
                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">Approved</span>
                    <?php else: ?>
                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">Pending</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-xs text-slate-400">
                    <?= date('d M Y', strtotime($q['created_at'])) ?>
                </td>
                <td class="px-4 py-3">
                    <div class="flex flex-col gap-2">
                        <?php if (!$q['is_approved']): ?>
                        <a href="/admin/qa/approve/<?= $q['id'] ?>"
                           class="rounded-lg bg-emerald-600 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-700 text-center">
                            Approve
                        </a>
                        <?php endif; ?>

                        <!-- Answer form -->
                        <details class="group">
                            <summary class="cursor-pointer rounded-lg bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-700 text-center list-none">
                                <?= $q['answer'] ? 'Edit Answer' : 'Add Answer' ?>
                            </summary>
                            <form method="post" action="/admin/qa/answer/<?= $q['id'] ?>" class="mt-2">
                                <?= csrf_field() ?>
                                <textarea name="answer" rows="3"
                                    class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    placeholder="Type answer..."><?= esc($q['answer'] ?? '') ?></textarea>
                                <button type="submit"
                                    class="mt-1 w-full rounded-lg bg-blue-600 py-1 text-xs font-semibold text-white hover:bg-blue-700">
                                    Save Answer
                                </button>
                            </form>
                        </details>

                        <a href="/admin/qa/delete/<?= $q['id'] ?>"
                           onclick="return confirm('Delete this question?')"
                           class="rounded-lg bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 hover:bg-red-200 text-center">
                            Delete
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
