<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
$date = !empty($article['published_at'])
    ? date('d F Y', strtotime($article['published_at']))
    : date('d F Y', strtotime($article['created_at']));
$dateISO = !empty($article['published_at'])
    ? date('c', strtotime($article['published_at']))
    : date('c', strtotime($article['created_at']));
$gradients = ['from-emerald-500 to-teal-700', 'from-blue-500 to-indigo-700', 'from-amber-500 to-orange-700', 'from-purple-500 to-pink-700'];
$gradient  = $gradients[crc32($article['slug'] ?? '') % count($gradients)];

// Build JSON-LD schema
$schema = $article['schema_json'] ?? json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'Article',
    'headline'      => $article['title'],
    'description'   => $article['excerpt'] ?? '',
    'datePublished' => $dateISO,
    'dateModified'  => date('c', strtotime($article['updated_at'] ?? $article['created_at'])),
    'author'        => ['@type' => 'Person', 'name' => $article['author_name'] ?? 'Charj.in Team'],
    'publisher'     => [
        '@type' => 'Organization',
        'name'  => 'Charj.in',
        'logo'  => ['@type' => 'ImageObject', 'url' => base_url('assets/logo.png')],
    ],
    'url'           => current_url(),
    'image'         => !empty($article['featured_image']) ? $article['featured_image'] : base_url('assets/og-default.jpg'),
]);
?>

<!-- JSON-LD -->
<script type="application/ld+json"><?= $schema ?></script>

<div class="mx-auto max-w-7xl px-4 py-6 pb-24">

    <!-- Breadcrumb -->
    <nav class="mb-5 flex items-center gap-1.5 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="/news" class="hover:text-slate-900">News</a>
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="max-w-[240px] truncate font-medium text-slate-900"><?= esc($article['title']) ?></span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-3">

        <!-- Article content (left / main) -->
        <div class="lg:col-span-2">

            <!-- Article header -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8">
                <?php if (!empty($article['category'])): ?>
                <span class="inline-block rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-bold uppercase tracking-wide text-emerald-700">
                    <?= esc($article['category']) ?>
                </span>
                <?php endif; ?>

                <h1 class="mt-3 text-2xl font-black leading-tight text-slate-900 md:text-3xl">
                    <?= esc($article['title']) ?>
                </h1>

                <!-- Meta row -->
                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-700">
                            <?= mb_strtoupper(mb_substr($article['author_name'] ?? 'C', 0, 1)) ?>
                        </div>
                        <?= esc($article['author_name'] ?? 'Charj.in Team') ?>
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <time datetime="<?= $dateISO ?>"><?= $date ?></time>
                    </span>
                    <?php if (!empty($article['view_count'])): ?>
                    <span class="flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <?= number_format($article['view_count']) ?> views
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Featured image -->
            <div class="mt-5 overflow-hidden rounded-2xl">
                <?php if (!empty($article['featured_image'])): ?>
                    <img src="<?= esc($article['featured_image']) ?>"
                         alt="<?= esc($article['title']) ?>"
                         class="w-full object-cover" style="max-height:420px">
                <?php else: ?>
                    <div class="flex items-center justify-center bg-gradient-to-br <?= $gradient ?>" style="height:300px">
                        <svg class="h-16 w-16 text-white/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Article body -->
            <div class="mt-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:p-8">
                <div class="prose prose-slate max-w-none
                            prose-headings:font-black prose-headings:text-slate-900
                            prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-xl prose-blockquote:border-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:py-1 prose-blockquote:px-4">
                    <?= $article['content'] ?? '' ?>
                </div>

                <!-- Tags -->
                <?php if (!empty($article['tags'])): ?>
                <div class="mt-8 border-t border-slate-100 pt-5">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">Tags</p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ((is_string($article['tags']) ? explode(',', $article['tags']) : (array)$article['tags']) as $tag): ?>
                        <?php $tag = trim($tag); if (!$tag) continue; ?>
                        <a href="/news?tag=<?= urlencode($tag) ?>"
                           class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">
                            #<?= esc($tag) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Related articles -->
            <?php if (!empty($relatedArticles)): ?>
            <div class="mt-8">
                <h2 class="mb-4 text-xl font-black text-slate-900">Related Articles</h2>
                <div class="grid gap-5 sm:grid-cols-3">
                    <?php foreach ($relatedArticles as $rel): ?>
                    <?php
                        $relDate = !empty($rel['published_at']) ? date('d M Y', strtotime($rel['published_at'])) : '';
                        $relGrad = $gradients[crc32($rel['slug'] ?? '') % count($gradients)];
                    ?>
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                        <a href="/news/<?= esc($rel['slug']) ?>">
                            <?php if (!empty($rel['featured_image'])): ?>
                                <img src="<?= esc($rel['featured_image']) ?>" alt="<?= esc($rel['title']) ?>" class="h-32 w-full object-cover">
                            <?php else: ?>
                                <div class="h-32 w-full bg-gradient-to-br <?= $relGrad ?>"></div>
                            <?php endif; ?>
                        </a>
                        <div class="p-4">
                            <?php if (!empty($rel['category'])): ?>
                                <span class="text-xs font-bold uppercase text-emerald-600"><?= esc($rel['category']) ?></span>
                            <?php endif; ?>
                            <h3 class="mt-1 text-sm font-bold leading-snug text-slate-900">
                                <a href="/news/<?= esc($rel['slug']) ?>" class="hover:text-emerald-600"><?= esc($rel['title']) ?></a>
                            </h3>
                            <p class="mt-1.5 text-xs text-slate-400"><?= $relDate ?></p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /main -->

        <!-- Sticky sidebar with lead form -->
        <div class="lg:col-span-1">
            <div class="sticky top-20">
                <?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
