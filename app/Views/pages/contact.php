<?php
/**
 * Contact Page
 * Variables: $meta_title, $meta_description, $flash_success
 */
$meta_title       = $meta_title       ?? 'Contact Charj.in — India\'s EV Marketplace';
$meta_description = $meta_description ?? 'Get in touch with Charj.in. Contact us for EV queries, dealer listings, brand partnerships or media enquiries.';
?>
<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<section class="bg-[#0D2137] py-12 md:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
    <h1 class="text-3xl font-extrabold text-white md:text-4xl lg:text-5xl">
      Contact Charj.in
    </h1>
    <p class="mt-4 text-lg text-slate-300 max-w-xl mx-auto">
      Have a question, suggestion or partnership enquiry? We'd love to hear from you.
    </p>
  </div>
</section>

<!-- Main Content -->
<section class="py-12 md:py-16 bg-slate-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    <?php if (!empty($flash_success) || session()->getFlashdata('success')): ?>
      <div class="mb-8 rounded-xl bg-[#22C55E]/10 border border-[#22C55E]/30 px-5 py-4 text-[#16a34a] font-medium flex items-center gap-3">
        <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <?= esc(session()->getFlashdata('success') ?? 'Your message has been sent. We\'ll get back to you within 24 hours.') ?>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-16">

      <!-- Left: Lead / Contact Form -->
      <div>
        <h2 class="text-xl font-bold text-[#0D2137] mb-2">Send Us a Message</h2>
        <p class="text-slate-500 text-sm mb-6">Fill the form below and our team will respond within one business day.</p>

        <?= view('partials/lead_form') ?>
      </div>

      <!-- Right: Contact Info -->
      <div class="space-y-8">

        <!-- Contact Details -->
        <div>
          <h2 class="text-xl font-bold text-[#0D2137] mb-5">Get In Touch</h2>
          <ul class="space-y-5">

            <li class="flex items-start gap-4">
              <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#22C55E]/10 text-[#22C55E]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-[#0D2137]">Email</p>
                <a href="mailto:hello@charj.in" class="text-sm text-[#22C55E] hover:text-[#16a34a] transition-colors">hello@charj.in</a>
                <p class="text-xs text-slate-400 mt-0.5">We reply within 24 hours</p>
              </div>
            </li>

            <li class="flex items-start gap-4">
              <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#22C55E]/10 text-[#22C55E]">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                  <path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.554 4.122 1.524 5.855L.057 23.04a.75.75 0 00.903.903l5.185-1.467A11.944 11.944 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.867 0-3.617-.5-5.13-1.37l-.37-.22-3.08.872.87-3.08-.22-.37A9.964 9.964 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-[#0D2137]">WhatsApp</p>
                <a href="https://wa.me/919876543210" target="_blank" rel="noopener"
                   class="text-sm text-[#22C55E] hover:text-[#16a34a] transition-colors">+91 98765 43210</a>
                <p class="text-xs text-slate-400 mt-0.5">Mon–Sat, 10 AM – 7 PM IST</p>
              </div>
            </li>

            <li class="flex items-start gap-4">
              <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#22C55E]/10 text-[#22C55E]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-[#0D2137]">Office</p>
                <p class="text-sm text-slate-600">Charj.in, 4th Floor, The Startup Hub,<br>Koramangala, Bengaluru — 560034,<br>Karnataka, India</p>
              </div>
            </li>

          </ul>
        </div>

        <!-- For Dealers / Brands -->
        <div class="rounded-2xl bg-[#0D2137] p-6">
          <div class="flex items-start gap-3 mb-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#22C55E]/20">
              <svg class="h-5 w-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div>
              <h3 class="text-base font-bold text-white">For Dealers &amp; Brands</h3>
              <p class="mt-1 text-sm text-slate-300 leading-relaxed">
                List your EVs or dealership on Charj.in and reach thousands of active EV buyers across India every month — completely free to get started.
              </p>
            </div>
          </div>
          <ul class="space-y-2 mb-5">
            <li class="flex items-center gap-2 text-sm text-slate-300">
              <svg class="h-4 w-4 text-[#22C55E] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Free dealer listing with contact details
            </li>
            <li class="flex items-center gap-2 text-sm text-slate-300">
              <svg class="h-4 w-4 text-[#22C55E] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Brand page with full EV lineup
            </li>
            <li class="flex items-center gap-2 text-sm text-slate-300">
              <svg class="h-4 w-4 text-[#22C55E] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Qualified leads from serious EV buyers
            </li>
          </ul>
          <a href="mailto:partners@charj.in"
             class="inline-flex items-center gap-2 rounded-xl bg-[#22C55E] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#16a34a] transition-colors">
            Partner with Us
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
          </a>
        </div>

        <!-- Social Links -->
        <div>
          <h3 class="text-sm font-bold text-[#0D2137] mb-4">Follow Us</h3>
          <div class="flex flex-wrap gap-3">

            <!-- Instagram -->
            <a href="https://instagram.com/charj.in" target="_blank" rel="noopener"
               class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:border-pink-300 hover:text-pink-600 transition-colors">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
              </svg>
              Instagram
            </a>

            <!-- Facebook -->
            <a href="https://facebook.com/charjin" target="_blank" rel="noopener"
               class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:border-blue-300 hover:text-blue-600 transition-colors">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
              Facebook
            </a>

            <!-- LinkedIn -->
            <a href="https://linkedin.com/company/charj-in" target="_blank" rel="noopener"
               class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:border-blue-400 hover:text-blue-700 transition-colors">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              LinkedIn
            </a>

            <!-- YouTube -->
            <a href="https://youtube.com/@charjin" target="_blank" rel="noopener"
               class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:border-red-300 hover:text-red-600 transition-colors">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
              YouTube
            </a>

          </div>
        </div>

      </div>
    </div>

  </div>
</section>

<?= $this->endSection() ?>
