<?php
$gaId = $gaId ?? '';
$metaPixelId = $metaPixelId ?? '';
$title = $title ?? $meta_title ?? 'Charj.in — India\'s EV Decision Engine';
$meta_desc = $meta_desc ?? $meta_description ?? 'Compare, calculate and choose the perfect EV. FAME II subsidies, TCO calculator, charging guide and more.';
$categories = $categories ?? [];
$transparentNav = $transparentNav ?? false;
?>
<!DOCTYPE html>
<html lang="en-IN" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($meta_desc) ?>">
  <meta name="theme-color" content="#00A896">
  <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">

  <meta property="og:title" content="<?= esc($title) ?>">
  <meta property="og:description" content="<?= esc($meta_desc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= current_url() ?>">
  <meta property="og:image" content="<?= base_url('assets/images/charj-og.jpg') ?>">
  <meta property="og:site_name" content="Charj.in">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= esc($title) ?>">
  <meta name="twitter:description" content="<?= esc($meta_desc) ?>">
  <meta name="twitter:image" content="<?= base_url('assets/images/charj-og.jpg') ?>">

  <?php if (!empty($gscVerification = ($settings['gsc_verification'] ?? ''))): ?>
  <meta name="google-site-verification" content="<?= esc($gscVerification) ?>">
  <?php endif; ?>
  <link rel="canonical" href="<?= current_url() ?>">

  <!-- Inter Font — production grade typography -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..900;1,14..32,300..900&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'] },
          colors: {
            brand:       { DEFAULT: '#00C060', light: '#F0FFF4', mid: '#00E676', dark: '#009944' },
            'charj-green':      '#00C060',
            'charj-green-dark': '#009944',
            'charj-navy':       '#022C22',
            'charj-navy-light': '#0A3D2B',
          }
        }
      }
    }
  </script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* ═══════════════════════════════════════════
       BASE RESET + TYPOGRAPHY
    ═══════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; }
    [x-cloak] { display: none !important; }
    html { font-feature-settings: 'cv02','cv03','cv04','cv11'; }
    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    /* ═══════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════ */
    :root {
      --brand:        #00E676;
      --brand-dark:   #00C060;
      --brand-deep:   #009944;
      --brand-faint:  rgba(0,230,118,.06);
      --brand-subtle: rgba(0,230,118,.12);
      --brand-border: rgba(0,230,118,.18);
      --brand-shadow: rgba(0,200,100,.22);
      --bg:           #F5FFF7;
      --bg-2:         #EEFFF3;
      --bg-card:      #FFFFFF;
      --text:         #0F172A;
      --text-2:       #374151;
      --text-3:       #6B7280;
      --text-4:       #94A3B8;
      --radius-card:  20px;
      --radius-lg:    24px;
      --t:            200ms cubic-bezier(.4,0,.2,1);
    }

    /* Ambient grain overlay */
    body::before {
      content:'';position:fixed;inset:0;z-index:9999;pointer-events:none;user-select:none;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.68' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
      opacity:.016;mix-blend-mode:multiply;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width:5px; height:5px; }
    ::-webkit-scrollbar-track { background:#F0FFF4; }
    ::-webkit-scrollbar-thumb { background:rgba(0,230,118,.3); border-radius:3px; }
    .scrollbar-hide::-webkit-scrollbar { display:none; }
    .scrollbar-hide { -ms-overflow-style:none; scrollbar-width:none; }

    /* ═══════════════════════════════════════════
       KEYFRAMES
    ═══════════════════════════════════════════ */
    @keyframes fadeInUp    { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:none} }
    @keyframes fadeInLeft  { from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:none} }
    @keyframes fadeInRight { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:none} }
    @keyframes scaleIn     { from{opacity:0;transform:scale(.94)} to{opacity:1;transform:scale(1)} }
    @keyframes shimmer     { to{background-position:-200% center} }
    @keyframes floatY      { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
    @keyframes floatY2     { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-16px)} }
    @keyframes gradShift   { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
    @keyframes glowPulse   { 0%,100%{box-shadow:0 0 0 0 rgba(0,168,150,0)} 50%{box-shadow:0 0 0 5px rgba(0,168,150,.15)} }
    @keyframes blink       { 0%,100%{opacity:1} 50%{opacity:0} }
    @keyframes slideUp     { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }
    @keyframes bounceSlow  { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(8px)} }
    @keyframes pageIn      { from{opacity:0;transform:translateY(5px)} to{opacity:1;transform:none} }
    @keyframes rippleOut   { 0%{transform:scale(0);opacity:.5} 100%{transform:scale(4);opacity:0} }
    @keyframes spotlightPop{ 0%{r:0} 100%{r:120} }
    @keyframes borderGlow  { 0%,100%{border-color:rgba(0,168,150,.15)} 50%{border-color:rgba(0,168,150,.4)} }
    @keyframes aurora      { 0%,100%{opacity:.04;transform:scale(1) translate(0,0)} 50%{opacity:.08;transform:scale(1.05) translate(10px,-5px)} }

    /* ═══════════════════════════════════════════
       ANIMATION UTILITY CLASSES
    ═══════════════════════════════════════════ */
    .animate-fade-in-up    { animation:fadeInUp .5s cubic-bezier(.4,0,.2,1) both; }
    .animate-fade-in-left  { animation:fadeInLeft .5s cubic-bezier(.4,0,.2,1) both; }
    .animate-fade-in-right { animation:fadeInRight .5s cubic-bezier(.4,0,.2,1) both; }
    .animate-scale-in      { animation:scaleIn .4s cubic-bezier(.4,0,.2,1) both; }
    .anim-grad             { background-size:200% 200%; animation:gradShift 6s ease infinite; }
    .float-1  { animation:floatY 6s ease-in-out infinite; }
    .float-2  { animation:floatY2 8s ease-in-out infinite; }
    .shimmer  { background:linear-gradient(90deg,#F0FDFA 25%,#E0FFF9 50%,#F0FDFA 75%); background-size:200%; animation:shimmer 1.6s linear infinite; }
    .glow-pulse { animation:glowPulse 3s ease-in-out infinite; }
    .cursor   { display:inline-block; width:2px; height:1em; background:#00A896; margin-left:2px; animation:blink 1s step-end infinite; vertical-align:text-bottom; }
    .aurora   { animation:aurora 10s ease-in-out infinite; }
    .scroll-indicator { animation:bounceSlow 2s ease-in-out infinite; }
    .stagger-1{animation-delay:.05s} .stagger-2{animation-delay:.10s} .stagger-3{animation-delay:.15s}
    .stagger-4{animation-delay:.20s} .stagger-5{animation-delay:.25s} .stagger-6{animation-delay:.30s}

    /* ═══════════════════════════════════════════
       SCROLL REVEAL SYSTEM
    ═══════════════════════════════════════════ */
    .reveal { opacity:0; transform:translateY(28px); transition:opacity .65s cubic-bezier(.22,1,.36,1),transform .65s cubic-bezier(.22,1,.36,1); }
    .reveal.visible { opacity:1; transform:none; }
    .sr       { opacity:0; transform:translateY(28px); transition:opacity .6s cubic-bezier(.22,1,.36,1),transform .6s cubic-bezier(.22,1,.36,1); }
    .sr-left  { opacity:0; transform:translateX(-28px); transition:opacity .6s cubic-bezier(.22,1,.36,1),transform .6s cubic-bezier(.22,1,.36,1); }
    .sr-right { opacity:0; transform:translateX(28px); transition:opacity .6s cubic-bezier(.22,1,.36,1),transform .6s cubic-bezier(.22,1,.36,1); }
    .sr-scale { opacity:0; transform:scale(.92) translateY(16px); transition:opacity .6s cubic-bezier(.22,1,.36,1),transform .6s cubic-bezier(.22,1,.36,1); }
    .sr.sr-visible,.sr-left.sr-visible,.sr-right.sr-visible,.sr-scale.sr-visible { opacity:1; transform:none; }
    /* Stagger grid — each child fans in sequentially */
    .sr-stagger > * { opacity:0; transform:translateY(22px) scale(.97); transition:opacity .45s cubic-bezier(.22,1,.36,1),transform .45s cubic-bezier(.22,1,.36,1); }
    .sr-stagger.sr-visible > * { opacity:1; transform:none; }
    .sr-stagger.sr-visible > *:nth-child(1){transition-delay:.03s}.sr-stagger.sr-visible > *:nth-child(2){transition-delay:.07s}
    .sr-stagger.sr-visible > *:nth-child(3){transition-delay:.11s}.sr-stagger.sr-visible > *:nth-child(4){transition-delay:.15s}
    .sr-stagger.sr-visible > *:nth-child(5){transition-delay:.19s}.sr-stagger.sr-visible > *:nth-child(6){transition-delay:.23s}
    .sr-stagger.sr-visible > *:nth-child(7){transition-delay:.27s}.sr-stagger.sr-visible > *:nth-child(8){transition-delay:.31s}
    .sr-stagger.sr-visible > *:nth-child(9){transition-delay:.35s}.sr-stagger.sr-visible > *:nth-child(10){transition-delay:.39s}
    .sr-stagger.sr-visible > *:nth-child(11){transition-delay:.43s}.sr-stagger.sr-visible > *:nth-child(12){transition-delay:.47s}
    .sr-stagger.sr-visible > *:nth-child(n+13){transition-delay:.50s}
    /* Reveal delay helpers */
    .reveal-d1{transition-delay:.08s!important}.reveal-d2{transition-delay:.16s!important}
    .reveal-d3{transition-delay:.24s!important}.reveal-d4{transition-delay:.32s!important}

    /* ═══════════════════════════════════════════
       PREMIUM CARD SYSTEM
    ═══════════════════════════════════════════ */
    .card {
      position:relative; background:#FFFFFF;
      border:1px solid rgba(0,230,118,.12);
      border-radius:var(--radius-card,20px);
      box-shadow:0 1px 2px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.04);
      transition:transform var(--t),box-shadow var(--t),border-color var(--t);
    }
    .card::before {
      content:''; position:absolute; inset:0; border-radius:inherit;
      background:linear-gradient(135deg,rgba(0,230,118,.04) 0%,transparent 55%);
      opacity:0; transition:opacity var(--t); pointer-events:none;
    }
    .card:hover { border-color:rgba(0,230,118,.3); transform:translateY(-5px); box-shadow:0 0 0 1px rgba(0,230,118,.08),0 16px 48px rgba(0,0,0,.08),0 4px 16px rgba(0,200,100,.06); }
    .card:hover::before { opacity:1; }

    .card-hover {
      position:relative; background:#FFFFFF;
      border:1px solid rgba(0,230,118,.1);
      border-radius:var(--radius-card,20px);
      box-shadow:0 1px 2px rgba(0,0,0,.04),0 4px 12px rgba(0,0,0,.03);
      transition:transform var(--t),box-shadow var(--t),border-color var(--t);
    }
    .card-hover::before {
      content:''; position:absolute; inset:0; border-radius:inherit;
      background:linear-gradient(135deg,rgba(0,230,118,.04) 0%,transparent 55%);
      opacity:0; transition:opacity var(--t); pointer-events:none;
    }
    .card-hover:hover { border-color:rgba(0,230,118,.28); transform:translateY(-4px); box-shadow:0 0 0 1px rgba(0,230,118,.07),0 12px 40px rgba(0,0,0,.08),0 4px 12px rgba(0,200,100,.05); }
    .card-hover:hover::before { opacity:1; }

    .panel-card {
      position:relative; background:#FFFFFF;
      border:1px solid rgba(0,230,118,.12);
      border-radius:var(--radius-lg,24px);
      box-shadow:0 2px 8px rgba(0,0,0,.05),0 8px 24px rgba(0,0,0,.04);
      transition:transform var(--t),box-shadow var(--t),border-color var(--t);
    }
    .panel-card:hover { border-color:rgba(0,230,118,.28); transform:translateY(-3px); box-shadow:0 0 0 1px rgba(0,230,118,.07),0 20px 56px rgba(0,0,0,.09),0 8px 24px rgba(0,200,100,.05); }

    .cat-card {
      position:relative; background:#FFFFFF;
      border:1px solid rgba(0,230,118,.12);
      border-radius:var(--radius-card,20px);
      transition:transform var(--t),box-shadow var(--t),border-color var(--t),background var(--t);
    }
    .cat-card:hover { background:#00E676 !important; border-color:#00E676 !important; transform:translateY(-3px) scale(1.02); box-shadow:0 8px 24px rgba(0,230,118,.3); }
    .cat-card:hover .cat-name,.cat-card:hover .cat-count,.cat-card:hover .cat-emoji-wrap { color:#022C22 !important; }

    .station-row {
      background:#FAFFFE;
      border:1px solid rgba(0,230,118,.1);
      border-radius:14px;
      transition:background var(--t),border-color var(--t),transform var(--t),box-shadow var(--t);
    }
    .station-row:hover { background:rgba(0,230,118,.04) !important; border-color:rgba(0,230,118,.28) !important; transform:translateX(3px); box-shadow:0 4px 12px rgba(0,200,100,.07); }

    .vc-card {
      position:relative; background:#FFFFFF; cursor:pointer;
      border:1px solid rgba(0,230,118,.1);
      border-radius:var(--radius-card,20px);
      box-shadow:0 1px 4px rgba(0,0,0,.04),0 4px 12px rgba(0,0,0,.03);
      transition:transform var(--t),box-shadow var(--t),border-color var(--t);
    }
    .vc-card:hover { border-color:rgba(0,230,118,.28); transform:translateY(-5px); box-shadow:0 0 0 1px rgba(0,230,118,.07),0 20px 48px rgba(0,0,0,.09),0 6px 16px rgba(0,200,100,.06); }

    /* Mouse spotlight */
    .card,.card-hover,.vc-card,.panel-card,.cat-card,.station-row { --cx:50%; --cy:50%; }
    .card::after,.card-hover::after,.vc-card::after,.panel-card::after,.cat-card::after {
      content:''; pointer-events:none; border-radius:inherit;
      position:absolute; inset:0;
      background:radial-gradient(circle 120px at var(--cx,50%) var(--cy,50%),rgba(0,230,118,.06),transparent 70%);
      opacity:0; transition:opacity var(--t);
    }
    .card:hover::after,.card-hover:hover::after,.vc-card:hover::after,.panel-card:hover::after,.cat-card:hover::after { opacity:1; }

    /* Card entrance animation */
    .card-pre-enter { opacity:0; transform:translateY(20px) scale(.97); }
    .card-entered   { opacity:1; transform:none; transition:opacity .5s cubic-bezier(.22,1,.36,1),transform .5s cubic-bezier(.22,1,.36,1); }

    /* ═══════════════════════════════════════════
       GLASS + GRADIENTS
    ═══════════════════════════════════════════ */
    .glass       { background:rgba(255,255,255,.88); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(0,230,118,.14); }
    .glass-green { background:rgba(0,230,118,.05); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border:1px solid rgba(0,230,118,.16); }
    .glass-dark  { background:rgba(4,12,30,.8); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(0,230,118,.15); }

    .gradient-text       { background:linear-gradient(135deg,#00A896,#00E676); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    .gradient-text-green { background:linear-gradient(135deg,#00A896,#00E676,#69FF97); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    .gradient-text-hero  { background:linear-gradient(145deg,#0F172A 0%,#065F46 55%,#00A896 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    .hero-gradient-text  { background:linear-gradient(135deg,#00C060 0%,#00E676 55%,#69FF97 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

    /* ═══════════════════════════════════════════
       BUTTONS
    ═══════════════════════════════════════════ */
    .btn-primary {
      display:inline-flex; align-items:center; gap:8px;
      background:#00E676; color:#022C22;
      padding:11px 24px; border-radius:var(--radius-pill,100px);
      font-weight:700; font-size:.875rem; letter-spacing:.01em;
      transition:all var(--t); text-decoration:none;
      box-shadow:0 4px 14px rgba(0,230,118,.28);
    }
    .btn-primary:hover { background:#00CC66; transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,230,118,.35),0 0 0 4px rgba(0,230,118,.12); }
    .btn-outline {
      display:inline-flex; align-items:center; gap:8px;
      border:1.5px solid rgba(0,230,118,.35); color:#00A896;
      padding:10px 22px; border-radius:var(--radius-pill,100px);
      font-weight:700; font-size:.875rem;
      transition:all var(--t); text-decoration:none; background:transparent;
    }
    .btn-outline:hover { backgrnavbar
    
  ound:rgba(0,230,118,.06); border-color:#00E676; color:#00C060; transform:translateY(-1px); }

    /* Ripple */
    .ripple-btn { position:relative; overflow:hidden; }
    .ripple-btn span.ripple {
      position:absolute; border-radius:50%; background:rgba(255,255,255,.35);
      width:100px; height:100px; margin-top:-50px; margin-left:-50px;
      animation:rippleOut .6s linear; pointer-events:none;
    }

    /* ═══════════════════════════════════════════
       FILTER CHIPS
    ═══════════════════════════════════════════ */
    .filter-chip {
      display:inline-flex; align-items:center; gap:6px;
      padding:6px 14px; border-radius:var(--radius-pill,100px);
      font-size:.8125rem; font-weight:600;
      border:1.5px solid rgba(0,230,118,.18);
      background:#fff; color:#4B5563;
      cursor:pointer; transition:all var(--t);
      white-space:nowrap; user-select:none;
    }
    .filter-chip:hover { border-color:rgba(0,230,118,.4); color:#00A896; background:rgba(0,230,118,.04); transform:translateY(-1px); }
    .filter-chip.active { background:#00E676; color:#022C22; border-color:#00E676; box-shadow:0 4px 14px rgba(0,230,118,.25); }

    /* ═══════════════════════════════════════════
       SEARCH BAR
    ═══════════════════════════════════════════ */
    .search-bar { transition:all var(--t); }
    .search-bar:focus-within {
      box-shadow:0 0 0 3px rgba(0,230,118,.15),0 8px 32px rgba(0,0,0,.08) !important;
      border-color:#00E676 !important;
      transform:translateY(-2px);
    }

    /* ═══════════════════════════════════════════
       SKELETON
    ═══════════════════════════════════════════ */
    .skeleton { background:linear-gradient(90deg,#F0FFF4 25%,#E0FFE9 50%,#F0FFF4 75%); background-size:200%; animation:shimmer 1.5s linear infinite; border-radius:8px; }

    /* ═══════════════════════════════════════════
       NAV DROPDOWN
    ═══════════════════════════════════════════ */
    .nav-dropdown {
      background:rgba(255,255,255,.98); backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px);
      border:1px solid rgba(0,168,150,.14);
      box-shadow:0 8px 32px rgba(0,0,0,.1),0 2px 8px rgba(0,0,0,.06),0 0 0 1px rgba(0,168,150,.06);
      border-radius:16px;
    }
    .nav-link {
      display:inline-flex; align-items:center; gap:5px;
      padding:7px 13px; border-radius:10px;
      font-size:.8125rem; font-weight:600; color:#4B5563;
      letter-spacing:-.01em;
      transition:color .18s,background .18s,transform .18s;
      position:relative;
    }
    .nav-link::after {
      content:''; position:absolute; bottom:3px; left:50%; right:50%;
      height:2px; border-radius:2px; background:#00A896;
      transition:left .2s,right .2s;
    }
    .nav-link:hover { color:#00A896; background:rgba(0,168,150,.06); }
    .nav-link:hover::after { left:13px; right:13px; }
    .nav-link-active { color:#00A896 !important; background:rgba(0,168,150,.08) !important; }
    .nav-link-active::after { left:13px !important; right:13px !important; }

    /* ═══════════════════════════════════════════
       BADGE + MISC
    ═══════════════════════════════════════════ */
    .badge-green { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:var(--radius-pill,100px); font-size:.75rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:rgba(0,230,118,.1); color:#00963C; border:1px solid rgba(0,230,118,.22); }
    .badge-blue  { background:rgba(14,165,233,.1); color:#0EA5E9; border:1px solid rgba(14,165,233,.22); }
    .badge-amber { background:rgba(245,158,11,.1); color:#D97706; border:1px solid rgba(245,158,11,.22); }
    .neon-dot    { width:8px; height:8px; border-radius:50%; background:#00E676; box-shadow:0 0 6px rgba(0,230,118,.5); display:inline-block; }
    .divider-crystal { height:1px; background:linear-gradient(90deg,transparent,rgba(0,230,118,.18),transparent); }
    .stat-item + .stat-item { border-left:1px solid rgba(0,230,118,.1); }
    @media(max-width:767px){ .stat-item:nth-child(even){ border-left:1px solid rgba(0,230,118,.1); } }
    .compare-bar-enter { animation:slideUp .3s cubic-bezier(.22,.68,0,1.2) both; }
    #main-content { animation:pageIn .35s ease-out; }
    .hero-grid {
      background-image: linear-gradient(rgba(0,230,118,.04) 1px,transparent 1px), linear-gradient(90deg,rgba(0,230,118,.04) 1px,transparent 1px);
      background-size: 48px 48px;
    }
    /* Star icon */
    .star-icon { color:#F59E0B; }
    /* Press logo */
    .press-logo { color:#94A3B8; font-weight:700; font-size:.8rem; letter-spacing:.08em; text-transform:uppercase; }
    /* Range/Price buttons */
    .range-btn.active,.price-btn.active { background:#00E676; color:#022C22; font-weight:700; border-color:#00E676; }
    /* Scroll indicator */
    @keyframes bounce-slow { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(8px)} }
    .scroll-indicator { animation:bounce-slow 2s ease-in-out infinite; }
    /* Subsidy pill */
    .subsidy-pill { background:rgba(0,230,118,.1); border:1px solid rgba(0,230,118,.2); backdrop-filter:blur(4px); }
    /* Two-col panel hover */
    .panel-card:hover { box-shadow:0 0 0 1px rgba(0,230,118,.15),0 20px 40px rgba(0,0,0,.08); }
    /* Accent checkbox/radio */
    input[type="checkbox"], input[type="radio"] { accent-color:#00E676; }

    /* ═══════════════════════════════════════════
       MOBILE HERO COMPACTION  ( < 768px only )
       Desktop keeps all its original lg: classes.
    ═══════════════════════════════════════════ */
    @media (max-width: 639px) {
      /* Generic page hero — phones only. Tablets (640px+) use original lg: classes. */
      .hero-sm {
        padding-top:  80px  !important;
        padding-bottom: 18px !important;
      }
      .hero-sm h1 {
        font-size: clamp(1.2rem, 5.5vw, 1.75rem) !important;
        line-height: 1.2 !important;
        margin-bottom: 0.3rem !important;
      }
      .hero-sm .hero-badge {
        margin-bottom: 0.4rem !important;
        font-size: 0.65rem !important;
        padding: 0.2rem 0.6rem !important;
      }
      .hero-sm .hero-desc {
        font-size: 0.78rem !important;
        line-height: 1.4 !important;
        margin-bottom: 0.5rem !important;
        margin-top: 0.25rem !important;
      }
      /* Tab / filter row inside hero */
      .hero-sm .hero-tabs { gap: 0.35rem !important; }
      .hero-sm .hero-tabs > * {
        padding: 0.2rem 0.55rem !important;
        font-size: 0.68rem !important;
      }

      /* ── Vehicle show page ── */
      #vehicle-hero .max-w-7xl {
        padding-top:    12px !important;
        padding-bottom: 12px !important;
      }
      #vehicle-hero h1 {
        font-size: clamp(1.2rem, 5vw, 1.75rem) !important;
        margin-bottom: 0.25rem !important;
      }
      /* Hide right image column on mobile */
      #vehicle-hero .grid > div:last-child { display: none !important; }
      /* Price box */
      #vehicle-hero .rounded-2xl { padding: 0.55rem !important; margin-bottom: 0.35rem !important; }
      /* Spec pills */
      #vehicle-hero .flex.flex-wrap.gap-2 > span {
        padding: 0.2rem 0.5rem !important;
        font-size: 0.65rem !important;
      }
      /* CTA buttons */
      #vehicle-hero .flex.gap-3 > a, #vehicle-hero .flex.gap-3 > button {
        padding: 0.4rem 0.75rem !important;
        font-size: 0.73rem !important;
      }
    }
  </style>

  <!-- Google Analytics -->
  <?php if (!empty($gaId) && $gaId !== 'G-XXXXXXXXXX'): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc($gaId) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','<?= esc($gaId) ?>',{'anonymize_ip':true});window.charjTrack=function(e,p){gtag('event',e,p||{})};</script>
  <?php else: ?>
  <script>window.charjTrack=function(e,p){console.log('[charjTrack]',e,p||{})};</script>
  <?php endif; ?>

  <?php if (!empty($metaPixelId) && $metaPixelId !== 'XXXXXXXXXXXXXXXXXX'): ?>
  <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','<?= esc($metaPixelId) ?>');fbq('track','PageView');</script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= esc($metaPixelId) ?>&ev=PageView&noscript=1"/></noscript>
  <?php endif; ?>

  <script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","name":"Charj.in","url":"<?= base_url() ?>","description":"India's EV Decision Engine","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"<?= base_url('vehicles?q={search_term_string}') ?>"},"query-input":"required name=search_term_string"}}</script>

  <?= $this->renderSection('head') ?>
</head>

<body class="antialiased" style="background:#F5FFF7;color:#0F172A" x-data="{mobileOpen:false}">

<?php if (session()->get('admin_previewing_as_customer')): ?>
<div class="fixed top-0 inset-x-0 z-[300] flex items-center justify-between px-4 py-2 text-white text-sm" style="background:#4338CA;box-shadow:0 2px 8px rgba(67,56,202,.4)">
  <span class="flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    <strong>Admin Preview</strong><span class="hidden sm:inline"> — Viewing as customer</span>
  </span>
  <a href="<?= site_url('admin/exit-customer-preview') ?>" class="flex items-center gap-1.5 bg-white text-indigo-700 font-bold text-xs px-3 py-1.5 rounded-full hover:bg-indigo-50 transition-colors">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Exit Preview
  </a>
</div>
<div class="h-10"></div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════
     CRYSTAL BLUE STICKY NAVBAR
══════════════════════════════════════════════════ -->
<header class="fixed inset-x-0 z-[200]"
        style="top:<?= session()->get('admin_previewing_as_customer') ? '40px' : '0' ?>;background:rgba(255,255,255,.92);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid rgba(0,168,150,.1);box-shadow:0 1px 0 rgba(0,0,0,.04),0 4px 24px rgba(0,0,0,.06)">
  <div class="mx-auto max-w-7xl px-4 sm:px-6">
    <div class="flex h-[62px] items-center justify-between gap-4">

      <!-- Logo -->
      <a href="<?= base_url() ?>" class="flex items-center gap-3 flex-shrink-0 group" aria-label="Charj.in Home">
        <div class="relative transition-all duration-200 group-hover:scale-105 group-hover:rotate-3">
          <svg viewBox="0 0 42 42" class="h-10 w-10" fill="none">
            <rect width="42" height="42" rx="11" fill="#0F172A"/>
            <rect width="42" height="42" rx="11" fill="none" stroke="rgba(0,230,118,.25)" stroke-width="1.5"/>
            <!-- C arc -->
            <path d="M29 13a10 10 0 1 0 0 16" stroke="#00E676" stroke-width="3.2" stroke-linecap="round" fill="none"/>
            <!-- Lightning bolt inside C -->
            <path d="M22 10L16.5 20H21L20 30L26.5 19H22L22 10Z" fill="#00E676"/>
            <!-- Charger plug dot (top-right corner = "J" plug) -->
            <circle cx="32" cy="10.5" r="2.5" fill="#00E676"/>
            <path d="M29.5 12.5L32 10.5" stroke="#00E676" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
          <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full animate-pulse" style="background:#00E676;border:2px solid #fff;box-shadow:0 0 8px rgba(0,230,118,.8)"></span>
        </div>
        <div class="leading-tight">
          <div class="flex items-baseline gap-0">
            <span class="font-black text-[1.15rem] tracking-tight" style="color:#0F172A;letter-spacing:-.03em">charj</span><span class="font-black text-[1.15rem]" style="color:#00A896;letter-spacing:-.02em">.in</span>
          </div>
          <div class="text-[8.5px] font-bold uppercase tracking-[.14em] hidden sm:block" style="color:#00A896">India's EV Decision Engine</div>
        </div>
      </a>

      <!-- Desktop Navigation -->
      <?php $curUri = current_url(true)->getPath(); ?>
      <nav class="hidden md:flex items-center gap-0.5 flex-1 justify-center" aria-label="Main navigation">
        <a href="<?= base_url('explore') ?>" class="nav-link <?= str_contains($curUri,'explore') ? 'nav-link-active' : '' ?>">navigate</a>
        <?php
        $navLinks = [
          ['label'=>'EVs','href'=>base_url('vehicles')],
          ['label'=>'Compare','href'=>base_url('compare')],
          ['label'=>'Calculators','href'=>'#','sub'=>[
            ['label'=>'On-Road Price','href'=>base_url('on-road-price'),'svg'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m3 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H10a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ['label'=>'Savings Calculator','href'=>base_url('tco-calculator'),  'svg'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Subsidy Finder',     'href'=>base_url('subsidy-calculator'),'svg'=>'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7'],
            ['label'=>'EMI Calculator',     'href'=>base_url('ev-emi-calculator'), 'svg'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['label'=>'Insurance Estimator','href'=>base_url('ev-insurance-calculator'),'svg'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['label'=>'Fleet ROI',          'href'=>base_url('fleet-calculator'),  'svg'=>'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM1 5h15M1 9h15m3-4v12m0 0h-3m3 0H18'],
          ]],
          ['label'=>'Tools','href'=>'#','sub'=>[
            ['label'=>'All Tools Hub',         'href'=>base_url('ev-tools'),        'svg'=>'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
            ['label'=>'EV Finder Quiz',        'href'=>base_url('find-my-ev'),      'svg'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
            ['label'=>'Trip Charging Planner','href'=>base_url('trip-planner'),     'svg'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
            ['label'=>'Trip Range Check',      'href'=>base_url('can-i-make-it'),   'svg'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label'=>'Charging Cost',         'href'=>base_url('charging-cost'),   'svg'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label'=>'Used EV Valuation',     'href'=>base_url('used-ev-value'),   'svg'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Charger Compatibility', 'href'=>base_url('charger-check'),   'svg'=>'M17 8l4 4m0 0l-4 4m4-4H3'],
            ['label'=>'Battery Cost',          'href'=>base_url('battery-cost'),    'svg'=>'M3 9h6m-6 4h6m11 1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2h5m5-8V5a2 2 0 10-4 0v3m4 0H9'],
          ]],
          ['label'=>'Charging','href'=>base_url('charging-stations')],
          ['label'=>'News','href'=>base_url('news')],
          ['label'=>'Guides','href'=>'#','sub'=>[
            ['label'=>'EV Waiting Periods','href'=>base_url('ev-waiting-periods'),'svg'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'EV Sales & Trends','href'=>base_url('ev-sales-trends'),'svg'=>'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
            ['label'=>'Home Charger Guide','href'=>base_url('home-charger-guide'),'svg'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label'=>'EV for Apartment', 'href'=>base_url('ev-for-apartment'), 'svg'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['label'=>'Used EV Guide',    'href'=>base_url('used-ev'),          'svg'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
            ['label'=>'EV Glossary',      'href'=>base_url('ev-glossary'),      'svg'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
          ]],
        ];
        foreach ($navLinks as $link):
          $isActive = !empty($link['href']) && $link['href'] !== '#' && str_contains($curUri, parse_url($link['href'], PHP_URL_PATH) ?? '');
          if (!empty($link['sub'])):
        ?>
        <div class="relative" x-data="{open:false,_t:null}" @mouseenter="clearTimeout(_t);open=true" @mouseleave="_t=setTimeout(()=>{open=false},200)">
          <button class="nav-link <?= $isActive ? 'nav-link-active' : '' ?>" :class="open && '!text-[#00A896] !bg-[rgba(0,168,150,.1)]'">
            <?= esc($link['label']) ?>
            <svg class="h-3 w-3 opacity-40 transition-transform duration-200 flex-shrink-0" :class="open?'rotate-180':''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
          </button>
          <!-- transparent bridge covers the gap so mouseleave doesn't fire while moving to dropdown -->
          <div class="absolute top-full left-0 right-0 h-2" x-show="open" x-cloak style="background:transparent"></div>
          <div x-show="open" x-cloak
               x-transition:enter="transition ease-out duration-180" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
               x-transition:leave="transition ease-in duration-120" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
               class="nav-dropdown absolute top-full left-1/2 -translate-x-1/2 mt-2 w-56 p-2 z-50">
            <?php foreach ($link['sub'] as $sub): ?>
            <a href="<?= esc($sub['href']) ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all duration-150 group"
               style="color:#475569"
               onmouseover="this.style.background='rgba(0,168,150,.07)';this.style.color='#00A896';this.querySelector('.sub-arrow').style.opacity='1';this.querySelector('.sub-arrow').style.transform='translateX(3px)'"
               onmouseout="this.style.background='';this.style.color='#475569';this.querySelector('.sub-arrow').style.opacity='0';this.querySelector('.sub-arrow').style.transform=''">
              <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-150" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.12)">
                <svg class="w-3.5 h-3.5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $sub['svg'] ?>"/></svg>
              </span>
              <span class="text-[.8125rem] font-semibold"><?= esc($sub['label']) ?></span>
              <svg class="sub-arrow w-3.5 h-3.5 ml-auto flex-shrink-0 transition-all duration-150" style="opacity:0;color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <a href="<?= esc($link['href']) ?>" class="nav-link <?= $isActive ? 'nav-link-active' : '' ?>"><?= esc($link['label']) ?></a>
        <?php endif; endforeach; ?>
      </nav>

      <!-- CTA + Mobile toggle -->
      <div class="flex items-center gap-2 flex-shrink-0">

        <!-- Wishlist icon — disabled when nothing saved, links to saved EVs page when items exist -->
        <button type="button"
           class="relative hidden sm:flex items-center justify-center w-9 h-9 rounded-xl transition-all duration-200 flex-shrink-0"
           :style="count > 0
             ? 'color:#ef4444;cursor:pointer'
             : 'color:#CBD5E1;cursor:not-allowed;opacity:.5'"
           :title="count > 0 ? 'View ' + count + ' saved EV' + (count===1?'':'s') : 'Save EVs to see them here'"
           @click="if(count > 0){ window.location.href='<?= base_url('my-evs') ?>'; }"
           @mouseover="if(count>0){$el.style.background='rgba(239,68,68,.07)'}"
           @mouseout="$el.style.background='transparent'"
           x-data="{
             count: 0,
             init() {
               var self = this;
               self.count = (window.charjGetWishlist?.() || []).length;
               document.addEventListener('charj:wishlist-update', function(e){ self.count = (e.detail || []).length; });
             }
           }">
          <!-- Filled heart when items saved, outline when not -->
          <svg x-show="count > 0" x-cloak class="w-5 h-5" style="color:#ef4444" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <svg :class="count > 0 ? 'hidden' : ''" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
          <span x-show="count > 0" x-cloak
                class="absolute -top-1 -right-1 min-w-[16px] h-4 px-0.5 rounded-full text-[9px] font-black flex items-center justify-center text-white"
                style="background:#ef4444;border:1.5px solid #fff"
                x-text="count > 9 ? '9+' : count"></span>
        </button>

        <a href="<?= base_url('find-my-ev') ?>" onclick="charjTrack('header_cta_click',{location:'header'})"
           class="hidden sm:inline-flex items-center gap-2 font-bold text-sm px-5 py-2 rounded-full transition-all duration-200 ripple-btn flex-shrink-0"
           style="background:linear-gradient(135deg,#00A896 0%,#00C9B1 100%);color:#fff;box-shadow:0 4px 14px rgba(0,168,150,.32);letter-spacing:-.01em"
           onmouseover="this.style.transform='translateY(-2px) scale(1.03)';this.style.boxShadow='0 8px 22px rgba(0,168,150,.42)'"
           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(0,168,150,.32)'">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
          Find My EV
        </a>
        <button @click="mobileOpen=!mobileOpen"
                class="md:hidden w-10 h-10 rounded-xl flex items-center justify-center transition-colors text-slate-500 hover:bg-[rgba(0,168,150,.08)] hover:text-[#00A896]"
                aria-label="Toggle navigation menu" :aria-expanded="mobileOpen.toString()">
          <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg x-show="mobileOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu -->
  <div x-show="mobileOpen" x-cloak
       x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
       class="md:hidden px-4 py-4"
       style="background:#FFFFFF;border-top:1px solid rgba(0,168,150,.1)"
       @keydown.escape.window="mobileOpen=false">
    <div class="space-y-0.5">
      <?php
      $mobileNavItems = [
        ['href'=>base_url('explore'),'label'=>'Explore Brands'],
        ['href'=>base_url('vehicles'),'label'=>'Browse EVs'],
        ['href'=>base_url('compare'),'label'=>'Compare EVs'],
        ['href'=>base_url('find-my-ev'),'label'=>'Find My EV'],
        ['href'=>base_url('charging-stations'),'label'=>'Charging Stations'],
        ['href'=>base_url('news'),'label'=>'News & Reviews'],
        ['hr'=>true],
        ['href'=>base_url('ev-tools'),'label'=>'All Tools Hub','small'=>true],
        ['href'=>base_url('subsidy-calculator'),'label'=>'Subsidy Calculator','small'=>true],
        ['href'=>base_url('tco-calculator'),'label'=>'Savings Calculator','small'=>true],
        ['href'=>base_url('ev-emi-calculator'),'label'=>'EMI Calculator','small'=>true],
        ['href'=>base_url('charging-cost'),'label'=>'Charging Cost','small'=>true],
        ['href'=>base_url('can-i-make-it'),'label'=>'Trip Range Check','small'=>true],
        ['hr'=>true],
        ['href'=>base_url('my-evs'),'label'=>'My Saved EVs'],
        ['href'=>base_url('ev-glossary'),'label'=>'EV Glossary'],
        ['href'=>base_url('ev-dealers'),'label'=>'Find EV Dealers'],
      ];
      foreach ($mobileNavItems as $item):
        if (!empty($item['hr'])): ?>
        <div class="my-2" style="border-top:1px solid rgba(0,168,150,.08)"></div>
        <?php else: ?>
        <a href="<?= $item['href'] ?>" @click="mobileOpen=false"
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-150 hover:bg-[rgba(0,168,150,.06)] hover:text-[#00A896] <?= !empty($item['small']) ? 'text-sm text-slate-400' : 'font-semibold text-slate-700' ?>">
          <?= esc($item['label']) ?>
        </a>
        <?php endif;
      endforeach; ?>
    </div>
    <div class="mt-4 pt-4" style="border-top:1px solid rgba(0,168,150,.08)">
      <a href="<?= base_url('find-my-ev') ?>" @click="mobileOpen=false"
         onclick="charjTrack('header_cta_click',{location:'mobile_menu'})"
         class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl font-bold text-sm"
         style="background:linear-gradient(135deg,#00E676,#69FF97);color:#022C22;box-shadow:0 4px 14px rgba(0,230,118,.28)">
        Find My Perfect EV →
      </a>
    </div>
  </div>
</header>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="fixed top-20 inset-x-4 z-40 max-w-md mx-auto" x-data="{show:true}" x-show="show" x-transition x-init="setTimeout(()=>show=false,4000)">
  <div class="flex items-center gap-3 rounded-2xl bg-emerald-500 px-5 py-4 text-white shadow-2xl">
    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
    <p class="font-semibold text-sm"><?= esc(session()->getFlashdata('success')) ?></p>
    <button @click="show=false" class="ml-auto opacity-70 hover:opacity-100 transition-opacity" aria-label="Dismiss">✕</button>
  </div>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="fixed top-20 inset-x-4 z-40 max-w-md mx-auto" x-data="{show:true}" x-show="show" x-transition x-init="setTimeout(()=>show=false,5000)">
  <div class="flex items-center gap-3 rounded-2xl bg-red-500 px-5 py-4 text-white shadow-2xl">
    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
    <p class="font-semibold text-sm"><?= esc(session()->getFlashdata('error')) ?></p>
    <button @click="show=false" class="ml-auto opacity-70 hover:opacity-100 transition-opacity" aria-label="Dismiss">✕</button>
  </div>
</div>
<?php endif; ?>

<!-- PAGE CONTENT -->
<main id="main-content">
  <?= $this->renderSection('content') ?>
</main>

<!-- MOBILE BOTTOM NAV -->
<nav class="fixed bottom-0 left-0 right-0 z-40 md:hidden"
     style="background:rgba(255,255,255,.97);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-top:1px solid rgba(0,168,150,.1);box-shadow:0 -4px 24px rgba(0,0,0,.08)"
     aria-label="Mobile bottom navigation">
  <div class="grid grid-cols-5 h-[58px]">
    <a href="<?= base_url() ?>" class="flex flex-col items-center justify-center gap-0.5 transition-colors" style="color:#00A896">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
      <span class="text-[9px] font-semibold">Home</span>
    </a>
    <a href="<?= base_url('vehicles') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#00A896] transition-colors">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
      <span class="text-[9px] font-semibold">EVs</span>
    </a>
    <a href="<?= base_url('compare') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#00A896] transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      <span class="text-[9px] font-semibold">Compare</span>
    </a>
    <a href="<?= base_url('find-my-ev') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#00A896] transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
      <span class="text-[9px] font-semibold">Find EV</span>
    </a>
    <a href="<?= base_url('ev-dealers') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-[#00A896] transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <span class="text-[9px] font-semibold">Dealers</span>
    </a>
  </div>
</nav>

<!-- FOOTER — Compact -->
<style>
.ft-link{font-size:.75rem;color:#64748B;transition:color .13s;padding:2px 0;display:inline-block}
.ft-link:hover{color:#00A896}
</style>
<footer class="pb-20 md:pb-0" style="background:#F0FFF8;border-top:1px solid rgba(0,168,150,.12)" role="contentinfo">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5">
    <div class="flex flex-col sm:flex-row sm:items-start gap-5">

      <!-- Logo + tagline + socials -->
      <div class="flex-shrink-0 sm:w-44">
        <a href="<?= base_url() ?>" class="flex items-center gap-2 mb-2 group">
          <svg viewBox="0 0 36 36" class="h-7 w-7 transition-transform group-hover:scale-105" fill="none">
            <rect width="36" height="36" rx="9" fill="#0F172A"/>
            <path d="M25 11a8 8 0 1 0 0 13" stroke="#00E676" stroke-width="2.8" stroke-linecap="round" fill="none"/>
            <path d="M19 8.5L14.5 17H18.5L17.5 25.5L23 16H19L19 8.5Z" fill="#00E676"/>
          </svg>
          <div>
            <div class="leading-none"><span class="font-black" style="color:#0F172A;font-size:.95rem">charj</span><span class="font-black" style="color:#00A896;font-size:.95rem">.in</span></div>
            <div class="text-[7.5px] uppercase tracking-widest font-semibold" style="color:#00A896">India's EV Decision Engine</div>
          </div>
        </a>
        <p class="text-[10px] leading-relaxed mb-3" style="color:#94A3B8">Free tools to compare, calculate and choose the right EV for India.</p>
        <div class="flex gap-1.5">
          <?php foreach ([
            ['Instagram','M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'],
            ['YouTube','M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
            ['X','M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
            ['LinkedIn','M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
          ] as [$lbl,$path]): ?>
          <a href="#" aria-label="<?= $lbl ?>" class="w-7 h-7 flex items-center justify-center rounded-lg transition-all"
             style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.15);color:#00A896"
             onmouseover="this.style.background='#00A896';this.style.color='#fff'"
             onmouseout="this.style.background='rgba(0,168,150,.08)';this.style.color='#00A896'">
            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="<?= $path ?>"/></svg>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 4 link columns -->
      <div class="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-4">

        <div>
          <p class="text-[9px] font-black uppercase tracking-widest mb-2" style="color:#00A896">Explore</p>
          <?php foreach ([['Scooters','electric-scooters'],['Electric Cars','electric-cars'],['Bikes','electric-bikes'],['E-Rickshaws','electric-rickshaws'],['All EVs','vehicles'],['Compare','compare'],['Brands','brands'],['My Saved EVs','my-evs']] as [$l,$p]): ?>
          <a href="<?= base_url($p) ?>" class="ft-link block"><?= $l ?></a>
          <?php endforeach; ?>
        </div>

        <div>
          <p class="text-[9px] font-black uppercase tracking-widest mb-2" style="color:#00A896">Tools</p>
          <?php foreach ([['All Tools Hub','ev-tools'],['On-Road Price','on-road-price'],['Insurance Est.','ev-insurance-calculator'],['Trip Planner','trip-planner'],['Used EV Value','used-ev-value'],['Subsidy Calc','subsidy-calculator'],['EMI Calculator','ev-emi-calculator'],['EV Finder Quiz','find-my-ev']] as [$l,$p]): ?>
          <a href="<?= base_url($p) ?>" class="ft-link block"><?= $l ?></a>
          <?php endforeach; ?>
        </div>

        <div>
          <p class="text-[9px] font-black uppercase tracking-widest mb-2" style="color:#00A896">Resources</p>
          <?php foreach ([['Charging Stations','charging-stations'],['EV Sales & Trends','ev-sales-trends'],['Waiting Periods','ev-waiting-periods'],['EV Glossary','ev-glossary'],['Home Charger','home-charger-guide'],['Used EV Guide','used-ev'],['News & Reviews','news'],['EV Dealers','ev-dealers']] as [$l,$p]): ?>
          <a href="<?= base_url($p) ?>" class="ft-link block"><?= $l ?></a>
          <?php endforeach; ?>
        </div>

        <div>
          <p class="text-[9px] font-black uppercase tracking-widest mb-2" style="color:#00A896">Company</p>
          <?php foreach ([['About Us','about'],['Contact','contact'],['For Dealers','ev-dealers'],['For Brands','for-brands'],['Privacy Policy','privacy-policy'],['Terms','terms-of-service'],['Disclaimer','disclaimer'],['Sitemap','sitemap.xml']] as [$l,$p]): ?>
          <a href="<?= base_url($p) ?>" class="ft-link block"><?= $l ?></a>
          <?php endforeach; ?>
          <!-- Hidden energy section — coming soon -->
          <p class="text-[9px] font-black uppercase tracking-widest mt-3 mb-1.5" style="color:#CBD5E1">Energy</p>
          <?php foreach ([['Solar Panels','#'],['Home Batteries','#'],['Inverters & UPS','#']] as [$l,$p]): ?>
          <a href="<?= $p ?>" class="ft-link block" style="color:#CBD5E1;font-size:.7rem"><?= $l ?></a>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div style="border-top:1px solid rgba(0,168,150,.1)">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-2.5 flex flex-col sm:flex-row items-center justify-between gap-1 text-[10px]" style="color:#94A3B8">
      <p>&copy; <?= date('Y') ?> Charj.in — India's EV Decision Engine. Made with ⚡ in India.</p>
      <p><strong style="color:#64748B">Prices are indicative.</strong> Verify with dealer before purchase.</p>
    </div>
  </div>
</footer>

<script>
// Scroll-reveal (for .reveal elements) — large rootMargin so elements animate before they're fully visible
const _revealIO = new IntersectionObserver((entries) => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); _revealIO.unobserve(e.target); } });
}, {threshold:0, rootMargin:'120px 0px 0px 0px'});
document.querySelectorAll('.reveal').forEach(el => _revealIO.observe(el));
// Force-reveal anything already in/near viewport on load
setTimeout(function(){
  document.querySelectorAll('.reveal:not(.visible)').forEach(function(el){
    var r=el.getBoundingClientRect(); if(r.top < window.innerHeight + 200) el.classList.add('visible');
  });
},100);
</script>

<!-- Recently Viewed EVs — localStorage widget -->
<div id="rv-widget"
     x-data="recentlyViewedWidget()"
     x-init="init()"
     x-show="items.length > 0"
     x-cloak
     class="fixed bottom-24 right-4 z-30 md:bottom-6 md:right-6 w-56"
     style="filter:drop-shadow(0 8px 24px rgba(0,168,150,.18))">
  <div class="rounded-2xl overflow-hidden" style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.2)">
    <div class="flex items-center justify-between px-3 py-2" style="background:rgba(0,168,150,.07);border-bottom:1px solid rgba(0,168,150,.12)">
      <span class="text-[10px] font-black uppercase tracking-widest" style="color:#00A896">Recently Viewed</span>
      <button @click="open=!open" class="text-xs font-bold px-1.5 rounded" style="color:#94A3B8" x-text="open ? '▲' : '▼'"></button>
    </div>
    <div x-show="open" x-cloak class="py-1.5 max-h-48 overflow-y-auto scrollbar-hide">
      <template x-for="ev in items.slice(0,5)" :key="ev.slug">
        <a :href="ev.url" class="flex items-center gap-2 px-3 py-1.5 transition-all duration-150"
           style="color:#0F172A"
           onmouseover="this.style.background='rgba(0,168,150,.06)'"
           onmouseout="this.style.background=''">
          <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-base overflow-hidden" style="background:rgba(0,168,150,.08)">
            <img :src="ev.image" x-show="ev.image" class="w-full h-full object-contain" :alt="ev.name">
            <span x-show="!ev.image">⚡</span>
          </div>
          <div class="min-w-0">
            <div class="text-xs font-bold truncate leading-snug" x-text="ev.name"></div>
            <div class="text-[9px]" style="color:#94A3B8" x-text="ev.price"></div>
          </div>
        </a>
      </template>
    </div>
  </div>
</div>

<script>
function recentlyViewedWidget() {
  return {
    items: [],
    open: true,
    init() {
      try { this.items = JSON.parse(localStorage.getItem('charj_rv') || '[]'); } catch(e) { this.items = []; }
    }
  };
}
// Call this from vehicle detail pages to track views
window.charjTrackView = function(slug, name, price, url, image) {
  try {
    var rv = JSON.parse(localStorage.getItem('charj_rv') || '[]');
    rv = rv.filter(function(x){ return x.slug !== slug; });
    rv.unshift({ slug: slug, name: name, price: price, url: url, image: image });
    rv = rv.slice(0, 10);
    localStorage.setItem('charj_rv', JSON.stringify(rv));
  } catch(e) {}
};
</script>

<?= $this->renderSection('scripts') ?>

<!-- Global animation engine -->
<script>
(function(){
  /* 1. Scroll-reveal for .sr variants — pre-load 150px before entering viewport */
  var srIO = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('sr-visible'); srIO.unobserve(e.target); }
    });
  },{threshold:0,rootMargin:'150px 0px 0px 0px'});

  function observeSR(){
    document.querySelectorAll('.sr,.sr-left,.sr-right,.sr-scale,.sr-stagger').forEach(function(el){ srIO.observe(el); });
    // Force any elements already visible or near-visible
    setTimeout(function(){
      document.querySelectorAll('.sr:not(.sr-visible),.sr-left:not(.sr-visible),.sr-right:not(.sr-visible),.sr-scale:not(.sr-visible),.sr-stagger:not(.sr-visible)').forEach(function(el){
        var r=el.getBoundingClientRect(); if(r.top < window.innerHeight+150) el.classList.add('sr-visible');
      });
    },120);
  }

  /* 2. Staggered card entrance */
  var enterIO = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){
        var d = parseFloat(e.target.dataset.enterDelay||0);
        setTimeout(function(){ e.target.classList.add('card-entered'); }, d);
        enterIO.unobserve(e.target);
      }
    });
  },{threshold:.06,rootMargin:'0px 0px -24px 0px'});

  function initCards(){
    var SEL = '.card,.card-hover,.vc-card,.panel-card,.cat-card,.station-row';
    document.querySelectorAll(SEL).forEach(function(el){
      el.classList.add('card-pre-enter');
      enterIO.observe(el);
      /* Mouse spotlight */
      el.addEventListener('mousemove',function(e){
        var r = el.getBoundingClientRect();
        el.style.setProperty('--cx',(e.clientX-r.left)+'px');
        el.style.setProperty('--cy',(e.clientY-r.top)+'px');
      });
      el.addEventListener('mouseleave',function(){
        el.style.removeProperty('--cx');
        el.style.removeProperty('--cy');
      });
    });
    /* Stagger delay for grid children */
    document.querySelectorAll('.grid').forEach(function(grid){
      grid.querySelectorAll('.card,.card-hover,.vc-card,.cat-card').forEach(function(c,i){
        if(!c.dataset.enterDelay) c.dataset.enterDelay = i*55;
      });
    });
  }

  /* 3. Ripple on ripple-btn */
  function initRipple(){
    document.querySelectorAll('.ripple-btn').forEach(function(btn){
      btn.addEventListener('click',function(e){
        var r = btn.getBoundingClientRect();
        var span = document.createElement('span');
        span.className = 'ripple';
        span.style.left = (e.clientX-r.left-50)+'px';
        span.style.top  = (e.clientY-r.top-50)+'px';
        btn.appendChild(span);
        setTimeout(function(){ if(span.parentNode) span.remove(); }, 700);
      });
    });
  }

  /* 4. Lazy-image rescue — Chrome won't load loading="lazy" images inside
        opacity:0 / transformed cards (.card-pre-enter, .sr-*). Force-load any
        that are still pending so photos & logos always appear. */
  function fixLazyImages(){
    document.querySelectorAll('img[loading="lazy"]').forEach(function(img){
      if(img.dataset._fixed) return;
      if(!img.complete || img.naturalWidth===0){
        img.dataset._fixed = '1';
        img.loading = 'eager';
        var s = img.getAttribute('src');
        if(s){ img.setAttribute('src',''); img.setAttribute('src', s); }
      }
    });
  }

  function boot(){ observeSR(); initCards(); initRipple();
    setTimeout(fixLazyImages, 300);
    setTimeout(fixLazyImages, 1500);
  }
  window.addEventListener('load', function(){ setTimeout(fixLazyImages, 200); });
  if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded', boot);
  } else { boot(); }
})();
</script>

<!-- ── Compare & Wishlist: localStorage storage layer ──────── -->
<script>
(function(){
  var CK = 'charj_compare_v1', WK = 'charj_wishlist_v1';
  function load(k){ try{ return JSON.parse(localStorage.getItem(k)||'[]'); }catch(e){ return []; } }
  function save(k,v){ try{ localStorage.setItem(k,JSON.stringify(v)); }catch(e){} }

  window.charjGetCompare  = function(){ return load(CK); };
  window.charjGetWishlist = function(){ return load(WK); };
  window.charjSetCompare  = function(v){ save(CK,v); };
  window.charjSetWishlist = function(v){ save(WK,v); };

  window.charjToggleCompare = function(slug, name, price){
    var list = load(CK);
    var idx  = list.findIndex(function(x){ return x.slug === slug; });
    if(idx >= 0){
      list.splice(idx,1);
    } else {
      if(list.length >= 3){
        window.charjToast?.('You can compare up to 3 EVs at a time.','warn'); return;
      }
      list.push({slug:slug, name:name, price:price||0});
    }
    save(CK, list);
    document.dispatchEvent(new CustomEvent('charj:compare-update', {detail: list}));
  };

  window.charjToggleWishlist = function(slug, name, image){
    var list = load(WK);
    var idx  = list.findIndex(function(x){ return x.slug === slug; });
    if(idx >= 0){
      list.splice(idx,1);
      window.charjToast?.('Removed from saved EVs');
    } else {
      list.push({slug:slug, name:name, image:image});
      window.charjToast?.('Saved! View your list anytime.');
    }
    save(WK, list);
    document.dispatchEvent(new CustomEvent('charj:wishlist-update', {detail: list}));
  };
})();
</script>

<!-- ── Toast notification ───────────────────────────────────── -->
<div id="charj-toast-wrap"
     x-data="{
       show: false, msg: '', type: 'info',
       queue: [],
       init() {
         const self = this;
         window.charjToast = function(m, t) { self.push(m, t || 'info'); };
       },
       next() {
         if(this.queue.length === 0){ this.show = false; return; }
         var item = this.queue.shift();
         this.msg  = item.msg;
         this.type = item.type || 'info';
         this.show = true;
         clearTimeout(this._t);
         this._t = setTimeout(() => { this.show = false; setTimeout(() => this.next(), 300); }, 2400);
       },
       push(msg, type) { this.queue.push({msg,type}); if(!this.show) this.next(); }
     }"
     class="fixed bottom-24 md:bottom-6 right-4 z-[300] pointer-events-none">
  <div x-show="show" x-cloak
       x-transition:enter="transition ease-out duration-250"
       x-transition:enter-start="opacity-0 translate-y-2 scale-95"
       x-transition:enter-end="opacity-100 translate-y-0 scale-100"
       x-transition:leave="transition ease-in duration-180"
       x-transition:leave-start="opacity-100 translate-y-0 scale-100"
       x-transition:leave-end="opacity-0 translate-y-2 scale-95"
       class="flex items-center gap-2.5 rounded-2xl px-4 py-3 text-sm font-semibold text-white pointer-events-auto shadow-2xl"
       :style="type==='warn'
         ? 'background:linear-gradient(135deg,#b45309,#d97706)'
         : 'background:linear-gradient(135deg,#040C1E,#071830);border:1px solid rgba(0,168,150,.3)'">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
         :style="type==='warn' ? 'color:#fde68a' : 'color:#1AFFCC'">
      <path x-show="type!=='warn'" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      <path x-show="type==='warn'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <span x-text="msg"></span>
  </div>
</div>

<!-- ── Floating Compare Bar ──────────────────────────────────── -->
<div id="charj-compare-bar"
     x-data="{
       items: [],
       get count(){ return this.items.length; },
       init(){
         this.items = window.charjGetCompare?.() || [];
         document.addEventListener('charj:compare-update', (e) => { this.items = e.detail || []; });
       },
       compareUrl(){
         if(this.items.length >= 2) {
           if(this.items.length === 2) return '<?= base_url('compare') ?>/' + this.items[0].slug + '/vs/' + this.items[1].slug;
           return '<?= base_url('compare') ?>';
         }
         return '<?= base_url('vehicles') ?>';
       },
       clear(){ window.charjSetCompare?.([]); document.dispatchEvent(new CustomEvent('charj:compare-update',{detail:[]})); },
       remove(slug){ window.charjToggleCompare?.(slug,'',0); }
     }"
     x-show="count > 0"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="translate-y-full opacity-0"
     class="fixed bottom-[58px] md:bottom-0 inset-x-0 z-[140]"
     style="background:linear-gradient(135deg,#00A896 0%,#007A6E 60%,#00BFA5 100%);border-top:1px solid rgba(255,255,255,.18);box-shadow:0 -6px 28px rgba(0,168,150,.3)">
  <div class="max-w-5xl mx-auto px-4 py-3 flex items-center gap-3">

    <!-- Label -->
    <span class="text-xs font-black uppercase tracking-widest flex-shrink-0" style="color:#00E676">Compare</span>

    <!-- Selected vehicles -->
    <div class="flex-1 flex items-center gap-2 overflow-x-auto scrollbar-hide min-w-0">
      <template x-for="item in items" :key="item.slug">
        <div class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 flex-shrink-0 transition-all duration-200"
             style="background:rgba(0,230,118,.08);border:1px solid rgba(0,230,118,.3)">
          <span class="text-white text-xs font-bold truncate max-w-[120px]" x-text="item.name"></span>
          <button type="button" @click="remove(item.slug)"
                  class="w-4 h-4 rounded-full flex items-center justify-center flex-shrink-0 ml-0.5 transition-colors"
                  style="color:#6EE7A8"
                  onmouseover="this.style.background='rgba(239,68,68,.2)';this.style.color='#ef4444'"
                  onmouseout="this.style.background='transparent';this.style.color='#6EE7A8'">
            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      </template>

      <!-- Placeholder slot — clickable link to browse more EVs -->
      <template x-if="count < 2">
        <a href="<?= base_url('vehicles') ?>"
           class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 flex-shrink-0 transition-all duration-200"
           style="background:rgba(0,230,118,.04);border:1px dashed rgba(0,230,118,.3);text-decoration:none"
           onmouseover="this.style.background='rgba(0,230,118,.12)'"
           onmouseout="this.style.background='rgba(0,230,118,.04)'">
          <svg class="w-3 h-3 flex-shrink-0" style="color:#00E676" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          <span class="text-[11px] font-bold" style="color:#00E676">add one more</span>
        </a>
      </template>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2 flex-shrink-0">
      <a :href="compareUrl()"
         class="flex items-center gap-1.5 font-black text-xs px-4 py-2 rounded-xl transition-all duration-200"
         :style="count >= 2
           ? 'background:linear-gradient(135deg,#00E676,#00C060);color:#022C22;box-shadow:0 4px 12px rgba(0,230,118,.35);cursor:pointer'
           : 'background:rgba(0,230,118,.15);color:#00E676;border:1px solid rgba(0,230,118,.3);cursor:pointer'">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <span x-text="count >= 2 ? 'Compare Now' : 'Browse EVs'"></span>
      </a>
      <button type="button" @click="clear()"
              class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-200"
              style="background:rgba(0,230,118,.08);color:#6EE7A8"
              onmouseover="this.style.background='rgba(239,68,68,.15)';this.style.color='#ef4444'"
              onmouseout="this.style.background='rgba(0,230,118,.08)';this.style.color='#6EE7A8'"
              title="Clear all">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </button>
    </div>
  </div>
</div>

</body>
</html>
