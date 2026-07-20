<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<div class="hero-sm relative overflow-hidden pt-24 pb-8 px-4" style="background:linear-gradient(160deg,#F0FFF4 0%,#FFFFFF 50%,#EEFFF3 100%);border-bottom:1px solid rgba(0,168,150,.12)">
  <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.07) 1px,transparent 1px);background-size:28px 28px;opacity:.5"></div>
  <div class="relative max-w-7xl mx-auto text-center">
    <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-4" style="background:rgba(0,168,150,.1);border:1.5px solid rgba(0,168,150,.2);color:#00A896">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
      EV News &amp; Insights
    </div>
    <h1 class="text-3xl lg:text-4xl font-black leading-tight mb-3" style="color:#0F172A">EV News &amp; Updates</h1>
    <p class="max-w-2xl mx-auto text-base" style="color:#475569">
      Latest electric vehicle launches, reviews, government policies, subsidies and industry news from India.
    </p>
  </div>
</div>

<!-- Articles grid -->
<div class="mx-auto max-w-7xl px-4 py-8 pb-4 md:pb-0">

    <?php if (!empty($articles)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sr-stagger">
        <?php foreach ($articles as $article): ?>
        <?php
            // Pick a gradient per category for placeholder image
            $gradients = [
                'news'       => 'from-blue-500 to-indigo-600',
                'review'     => 'from-emerald-500 to-teal-600',
                'policy'     => 'from-amber-500 to-orange-600',
                'comparison' => 'from-purple-500 to-pink-600',
                'ev-guide'   => 'from-slate-600 to-slate-800',
                'technology' => 'from-cyan-500 to-blue-600',
            ];
            $catSlug = strtolower(str_replace(' ', '-', $article['category'] ?? 'news'));
            $gradient = $gradients[$catSlug] ?? 'from-emerald-500 to-slate-700';
            $date = !empty($article['published_at'])
                ? date('d M Y', strtotime($article['published_at']))
                : date('d M Y', strtotime($article['created_at']));
        ?>
        <a href="/news/<?= esc($article['slug']) ?>" class="flex flex-col overflow-hidden rounded-2xl transition hover:-translate-y-1 duration-300 card-hover" style="background:#FFFFFF;border:1px solid rgba(0,168,150,.12);box-shadow:0 2px 8px rgba(0,0,0,.04);text-decoration:none">

            <!-- Image placeholder -->
            <div>
                <?php if (!empty($article['featured_image'])): ?>
                    <img src="<?= esc($article['featured_image']) ?>"
                         alt="<?= esc($article['title']) ?>"
                         class="h-44 w-full object-cover">
                <?php else: ?>
                    <div class="flex h-44 w-full items-center justify-center bg-gradient-to-br <?= $gradient ?>">
                        <svg class="h-10 w-10 text-white/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex flex-1 flex-col p-5">
                <!-- Category badge -->
                <?php if (!empty($article['category'])): ?>
                <span class="mb-2 inline-block self-start rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide" style="background:rgba(0,168,150,.08);color:#00A896;border:1px solid rgba(0,168,150,.2)">
                    <?= esc($article['category']) ?>
                </span>
                <?php endif; ?>

                <!-- Title -->
                <h2 class="text-base font-bold leading-snug transition-colors" style="color:#0F172A" onmouseover="this.style.color='#00A896'" onmouseout="this.style.color=''">
                    <?= esc($article['title']) ?>
                </h2>

                <!-- Excerpt (2-line clamp) -->
                <?php if (!empty($article['excerpt'])): ?>
                <p class="mt-2 flex-1 text-sm leading-relaxed"
                   style="color:#64748B;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                    <?= esc($article['excerpt']) ?>
                </p>
                <?php endif; ?>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Meta -->
                <div class="mt-4 flex items-center justify-between pt-3 text-xs" style="border-top:1px solid rgba(0,168,150,.08);color:#94A3B8">
                    <div class="flex items-center gap-1.5">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold" style="background:rgba(0,168,150,.08);color:#00A896">
                            <?= mb_strtoupper(mb_substr($article['author_name'] ?? 'C', 0, 1)) ?>
                        </div>
                        <span><?= esc($article['author_name'] ?? 'Charj.in Team') ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span><?= $date ?></span>
                        <?php $views = $article['views'] ?? $article['view_count'] ?? 0; if (!empty($views)): ?>
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <?= number_format($views) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
    <div class="mt-10 flex justify-center">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="py-12 text-center">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.15)">
            <svg class="h-7 w-7" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="font-semibold" style="color:#475569">No articles yet. Check back soon!</p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


