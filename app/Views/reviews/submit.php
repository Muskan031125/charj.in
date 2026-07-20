<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title) ?></title>
<meta name="description" content="<?= esc($meta_description) ?>">
<style>
[x-cloak]{display:none!important}
.star-btn{cursor:pointer;transition:transform .15s,color .15s;line-height:1}
.star-btn:hover,.star-btn.lit{color:#F59E0B;transform:scale(1.15)}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<div class="hero-sm pt-20 sm:pt-24 md:pt-32 pb-6 sm:pb-8 lg:pb-12 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 50%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.12)">
  <div class="max-w-2xl mx-auto">
    <a href="<?= base_url('vehicles/' . esc($vehicle['slug'])) ?>"
       class="inline-flex items-center gap-1.5 text-xs font-semibold mb-4 hover:opacity-80 transition-opacity"
       style="color:#475569">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to <?= esc($vehicle['name']) ?>
    </a>
    <div class="flex items-center gap-4">
      <?php if (!empty($vehicle['image_url'])): ?>
      <img src="<?= esc($vehicle['image_url']) ?>" alt="" class="w-16 h-16 object-contain rounded-xl flex-shrink-0"
           style="background:#FFFFFF;border:1px solid rgba(0,168,150,.14)">
      <?php endif; ?>
      <div>
        <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#64748B"><?= esc($vehicle['brand_name'] ?? '') ?></p>
        <h1 class="text-xl sm:text-2xl font-black leading-tight" style="color:#0F172A">Review the <?= esc($vehicle['name']) ?></h1>
      </div>
    </div>
  </div>
</div>

<!-- Form -->
<div class="bg-slate-50 min-h-screen py-8 px-4">
<div class="max-w-2xl mx-auto">

  <?php if ($err = session()->getFlashdata('review_error')): ?>
  <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-5 text-red-700 text-sm font-semibold flex items-center gap-2">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <?= esc($err) ?>
  </div>
  <?php endif; ?>

  <form action="<?= base_url('reviews/store') ?>" method="POST"
        x-data="{ rating: <?= (int) old('rating', 0) ?>, hover: 0 }"
        @submit.prevent="if(rating<1){alert('Please select a rating');return} $el.submit()">

    <?= csrf_field() ?>
    <input type="hidden" name="vehicle_id" value="<?= esc($vehicle['id']) ?>">
    <input type="hidden" name="rating" :value="rating">

    <!-- Overall rating -->
    <div class="bg-white rounded-2xl p-5 mb-4 shadow-sm" style="border:1.5px solid rgba(0,230,118,.12)">
      <h2 class="font-black text-slate-900 mb-1">Overall Rating <span class="text-red-500">*</span></h2>
      <p class="text-slate-400 text-xs mb-4">Tap a star</p>
      <div class="flex items-center gap-2">
        <?php for ($i = 1; $i <= 5; $i++): ?>
        <button type="button"
                class="star-btn text-4xl"
                :class="(hover||rating) >= <?= $i ?> ? 'lit' : 'text-slate-200'"
                @mouseenter="hover=<?= $i ?>"
                @mouseleave="hover=0"
                @click="rating=<?= $i ?>">★</button>
        <?php endfor; ?>
        <span class="ml-2 text-sm font-bold text-slate-500" x-show="rating>0" x-text="rating+'/5'"></span>
      </div>
      <p x-show="rating===0" x-cloak class="text-xs text-red-500 mt-2">Select a star rating to continue</p>
    </div>

    <!-- Review text -->
    <div class="bg-white rounded-2xl p-5 mb-4 shadow-sm" style="border:1.5px solid rgba(0,230,118,.12)">
      <h2 class="font-black text-slate-900 mb-4">Your Review</h2>
      <div class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Review Title</label>
          <input type="text" name="title" value="<?= esc(old('title', '')) ?>"
                 placeholder="e.g. Best EV for daily commute in Bengaluru"
                 class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">
            Detailed Review <span class="text-red-500">*</span>
          </label>
          <textarea name="content" rows="5" required minlength="30"
                    placeholder="Share your ownership experience — real-world range, charging at home or on the road, what surprised you, what you'd change..."
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400 resize-none"><?= esc(old('content', '')) ?></textarea>
          <p class="text-xs text-slate-400 mt-1">Minimum 30 characters</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pros</label>
            <textarea name="pros" rows="3"
                      placeholder="Range, build quality, charging speed..."
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400 resize-none"><?= esc(old('pros', '')) ?></textarea>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Cons</label>
            <textarea name="cons" rows="3"
                      placeholder="Service centre distance, boot space..."
                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400 resize-none"><?= esc(old('cons', '')) ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- About you -->
    <div class="bg-white rounded-2xl p-5 mb-4 shadow-sm" style="border:1.5px solid rgba(0,230,118,.12)">
      <h2 class="font-black text-slate-900 mb-4">About You</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">
            Your Name <span class="text-red-500">*</span>
          </label>
          <input type="text" name="reviewer_name" value="<?= esc(old('reviewer_name', '')) ?>"
                 placeholder="Rahul S." required
                 class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">City</label>
          <input type="text" name="reviewer_city" value="<?= esc(old('reviewer_city', '')) ?>"
                 placeholder="Bengaluru"
                 class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Months Owned</label>
          <input type="number" name="ownership_months" value="<?= esc(old('ownership_months', '')) ?>"
                 placeholder="8" min="0" max="120"
                 class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">KM Driven</label>
          <input type="number" name="km_driven" value="<?= esc(old('km_driven', '')) ?>"
                 placeholder="12000" min="0"
                 class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>
      </div>
    </div>

    <!-- Submit -->
    <button type="submit"
            class="w-full py-3.5 rounded-2xl font-black text-base transition-all"
            style="background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 18px rgba(0,230,118,.3)"
            onmouseover="this.style.boxShadow='0 6px 24px rgba(0,230,118,.5)'"
            onmouseout="this.style.boxShadow='0 4px 18px rgba(0,230,118,.3)'">
      Submit Review
    </button>
    <p class="text-center text-xs text-slate-400 mt-3">Reviews go through moderation before appearing publicly.</p>

  </form>
</div>
</div>

<?= $this->endSection() ?>
