<?= $this->extend('layouts/public') ?>
<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'My Saved EVs | Charj.in') ?></title>
<meta name="description" content="<?= esc($meta_description ?? '') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div x-data="myEvs()" x-init="init()" class="min-h-[70vh]" style="background:#F7FFFE">

  <!-- Hero -->
  <div class="hero-sm relative overflow-hidden pt-24 pb-8 px-4" style="background:linear-gradient(160deg,#F0FFF9 0%,#EAFFF4 60%,#F7FFFE 100%);border-bottom:1px solid rgba(0,168,150,.1)">
    <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,168,150,.06) 1px,transparent 1px);background-size:24px 24px"></div>
    <div class="relative max-w-4xl mx-auto">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-3"
               style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.18);color:#ef4444">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            Wishlist
          </div>
          <h1 class="text-2xl sm:text-3xl font-black" style="color:#0F172A">My Saved EVs</h1>
          <p class="text-sm mt-1" style="color:#475569">
            <span x-text="wishlist.length + ' EV' + (wishlist.length !== 1 ? 's' : '') + ' saved'"></span>
            — saved locally on this device
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button x-show="wishlist.length > 0" x-cloak
                  @click="clearAll()"
                  class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl transition-all duration-150"
                  style="background:rgba(239,68,68,.07);color:#ef4444;border:1px solid rgba(239,68,68,.15)"
                  onmouseover="this.style.background='rgba(239,68,68,.12)'" onmouseout="this.style.background='rgba(239,68,68,.07)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Clear All
          </button>
          <a x-show="wishlist.length >= 2" x-cloak
             :href="compareUrl()"
             class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl text-white transition-all duration-150"
             style="background:#00A896"
             onmouseover="this.style.background='#007A6E'" onmouseout="this.style.background='#00A896'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Compare Selected
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Empty state -->
    <div x-show="wishlist.length === 0" class="py-20 text-center">
      <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-5"
           style="background:rgba(239,68,68,.06);border:2px solid rgba(239,68,68,.12)">
        <svg class="w-9 h-9" style="color:#ef4444;opacity:.4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
      </div>
      <h2 class="text-xl font-black mb-2" style="color:#0F172A">No saved EVs yet</h2>
      <p class="text-sm mb-6 max-w-sm mx-auto" style="color:#64748B">Tap the heart icon on any EV card to save it here for later. Compare your favourites side by side.</p>
      <a href="<?= base_url('vehicles') ?>"
         class="inline-flex items-center gap-2 font-bold text-sm px-6 py-3 rounded-full text-white"
         style="background:#00A896">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
        Browse EVs
      </a>
    </div>

    <!-- Saved EVs grid -->
    <div x-show="wishlist.length > 0" x-cloak>

      <!-- Sort/filter bar -->
      <div class="flex items-center justify-between mb-5">
        <p class="text-sm" style="color:#94A3B8">Saved on this device — <span class="font-semibold" style="color:#64748B">tap ♥ to remove</span></p>
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold" style="color:#94A3B8">Sort:</span>
          <button @click="sortBy='name'"
                  class="text-xs font-bold px-3 py-1 rounded-full transition-all"
                  :style="sortBy==='name' ? 'background:#00A896;color:#fff' : 'background:rgba(0,168,150,.08);color:#00A896'"
                  >Name</button>
          <button @click="sortBy='price'"
                  class="text-xs font-bold px-3 py-1 rounded-full transition-all"
                  :style="sortBy==='price' ? 'background:#00A896;color:#fff' : 'background:rgba(0,168,150,.08);color:#00A896'"
                  >Price</button>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <template x-for="ev in sorted" :key="ev.slug">
          <div class="group rounded-2xl overflow-hidden flex flex-col transition-all duration-200"
               style="background:#FFFFFF;border:1px solid rgba(0,230,118,.12);box-shadow:0 2px 8px rgba(0,0,0,.04)"
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,200,100,.1)'"
               onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,0,0,.04)'">
            <!-- Header accent -->
            <div class="h-[3px]" style="background:linear-gradient(90deg,#00E676,#69FF97,rgba(0,230,118,.15))"></div>
            <!-- Image placeholder -->
            <div class="flex items-center justify-center h-28 relative" style="background:linear-gradient(145deg,#ECFDF5,#D1FAE5)">
              <img :src="ev.image" x-show="ev.image" class="w-full h-full object-contain p-2" :alt="ev.name">
              <div class="text-5xl select-none" x-show="!ev.image" x-text="ev.emoji || '⚡'"></div>
              <!-- Remove button -->
              <button @click="remove(ev.slug)"
                      class="absolute top-2 right-2 w-7 h-7 rounded-full flex items-center justify-center transition-all duration-150"
                      style="background:rgba(255,255,255,.9);border:1px solid rgba(239,68,68,.2);color:#ef4444"
                      title="Remove from wishlist">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              </button>
            </div>
            <!-- Info -->
            <div class="p-3 flex flex-col flex-1">
              <div class="text-[9px] font-black uppercase tracking-widest mb-0.5" style="color:#00A896" x-text="ev.brand || ''"></div>
              <div class="font-black text-sm leading-snug mb-2" style="color:#0F172A" x-text="ev.name"></div>
              <div class="mt-auto flex items-center justify-between">
                <div class="text-xs font-black" style="color:#0F172A" x-text="ev.price || '—'"></div>
                <a :href="'/vehicles/' + ev.slug"
                   class="text-xs font-black px-3 py-1.5 rounded-lg text-white transition-all"
                   style="background:#00A896"
                   onmouseover="this.style.background='#007A6E'" onmouseout="this.style.background='#00A896'">
                  View →
                </a>
              </div>
            </div>
          </div>
        </template>
      </div>

      <!-- Compare CTA -->
      <div x-show="wishlist.length >= 2" class="mt-8 text-center">
        <a :href="compareUrl()"
           class="inline-flex items-center gap-2 font-bold px-8 py-3.5 rounded-full text-white transition-all duration-200"
           style="background:linear-gradient(135deg,#00A896,#007A6E);box-shadow:0 6px 20px rgba(0,168,150,.28)"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          Compare All <span x-text="wishlist.length"></span> EVs Side by Side
        </a>
        <p class="text-xs mt-2" style="color:#94A3B8">Compares 30+ spec points including range, battery, charging speed & price</p>
      </div>
    </div>
  </div>

</div>

<script>
function myEvs() {
  return {
    wishlist: [],
    sortBy: 'name',
    init() {
      this.load();
      var self = this;
      document.addEventListener('charj:wishlist-update', function(e) {
        self.wishlist = e.detail || [];
      });
    },
    load() {
      try { this.wishlist = JSON.parse(localStorage.getItem('charj_wl') || '[]'); } catch(e) { this.wishlist = []; }
    },
    get sorted() {
      var list = this.wishlist.slice();
      if (this.sortBy === 'name') return list.sort(function(a,b){ return (a.name||'').localeCompare(b.name||''); });
      if (this.sortBy === 'price') return list.sort(function(a,b){ return (a.priceNum||0) - (b.priceNum||0); });
      return list;
    },
    remove(slug) {
      window.charjToggleWishlist && window.charjToggleWishlist(JSON.stringify(slug), JSON.stringify(''));
      this.load();
    },
    clearAll() {
      if (!confirm('Remove all saved EVs?')) return;
      localStorage.removeItem('charj_wl');
      this.wishlist = [];
      document.dispatchEvent(new CustomEvent('charj:wishlist-update', {detail: []}));
    },
    compareUrl() {
      var slugs = this.wishlist.slice(0,4).map(function(x){ return x.slug; });
      if (slugs.length < 2) return '<?= base_url('compare') ?>';
      return '<?= base_url('compare') ?>?evs=' + slugs.join(',');
    }
  };
}
</script>

<?= $this->endSection() ?>
