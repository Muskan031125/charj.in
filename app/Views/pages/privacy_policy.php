<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="min-h-screen bg-slate-50">
  <div class="max-w-3xl mx-auto px-4 pt-28 pb-20">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 sm:p-12">
      <h1 class="text-3xl font-black text-slate-900 mb-2">Privacy Policy</h1>
      <p class="text-sm text-slate-400 mb-8">Last updated: June 2025</p>

      <div class="prose prose-slate max-w-none space-y-6 text-sm text-slate-700 leading-relaxed">
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">1. Information We Collect</h2>
          <p>Charj.in collects information you provide directly — such as your name and email if you fill out a contact or lead form. We also collect standard server logs (IP address, browser type, pages visited) and analytics data via Google Analytics.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">2. How We Use Information</h2>
          <p>We use collected information to: respond to enquiries, improve our website content, analyse traffic patterns, and connect you with EV dealers you request. We do not sell your data to third parties.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">3. Cookies</h2>
          <p>We use essential cookies for site functionality and analytics cookies (Google Analytics) to understand how visitors use the site. You can disable cookies in your browser settings — the site will still work.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">4. Third-Party Services</h2>
          <p>We embed data from OpenChargeMap (EV charging stations) and use Anthropic's Claude AI for EV recommendations. These services have their own privacy policies.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">5. Data Security</h2>
          <p>We take reasonable measures to protect your information. However, no internet transmission is 100% secure. Please do not submit sensitive personal or financial data through our forms.</p>
        </section>
        <section>
          <h2 class="text-lg font-black text-slate-900 mb-2">6. Contact</h2>
          <p>For privacy questions, email us at <a href="mailto:hello@charj.in" class="text-green-600 font-semibold">hello@charj.in</a>.</p>
        </section>
      </div>

      <div class="mt-10 pt-6 border-t border-slate-100 flex gap-3">
        <a href="<?= base_url() ?>" class="btn-primary text-sm !py-2.5 !px-5">← Back to Home</a>
        <a href="<?= base_url('terms-of-service') ?>" class="btn-outline text-sm !py-2.5 !px-5">Terms of Service</a>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
