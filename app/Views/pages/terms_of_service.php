<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="min-h-screen bg-slate-50">
  <div class="max-w-3xl mx-auto px-4 pt-28 pb-20">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 sm:p-12">
      <h1 class="text-3xl font-black text-slate-900 mb-2">Terms of Service</h1>
      <p class="text-sm text-slate-400 mb-8">Last updated: June 2025</p>

      <div class="space-y-6 text-sm text-slate-700 leading-relaxed">
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">1. Acceptance of Terms</h2>
          <p>By accessing and using Charj.in, you accept and agree to these Terms of Service. If you do not agree, please do not use the site.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">2. Use of the Site</h2>
          <p>Charj.in is an informational platform for EV buyers in India. All content — vehicle specifications, prices, range figures, subsidy information — is provided for reference only and may not be current. Always verify with the manufacturer or dealer before making a purchase decision.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">3. AI-Generated Content</h2>
          <p>Some sections of this site use Claude AI (Anthropic) to generate EV recommendations and cost estimates. These are informational only and not professional financial or automotive advice. Results may vary based on individual circumstances.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">4. Intellectual Property</h2>
          <p>All content on Charj.in — text, designs, tools, and calculators — is the property of Charj.in unless stated otherwise. You may not reproduce or redistribute it without permission.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">5. Limitation of Liability</h2>
          <p>Charj.in is not liable for decisions made based on information on this site. We are not responsible for errors in EV pricing, range, or subsidy data. Use all information at your own discretion.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">6. Changes to Terms</h2>
          <p>We may update these terms from time to time. Continued use of the site after changes constitutes acceptance of the new terms.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">7. Governing Law</h2>
          <p>These terms are governed by the laws of India. Any disputes shall be subject to the jurisdiction of courts in India.</p>
        </section>
      </div>

      <div class="mt-10 pt-6 border-t border-slate-100 flex gap-3">
        <a href="<?= base_url() ?>" class="btn-primary text-sm !py-2.5 !px-5">← Back to Home</a>
        <a href="<?= base_url('privacy-policy') ?>" class="btn-outline text-sm !py-2.5 !px-5">Privacy Policy</a>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
