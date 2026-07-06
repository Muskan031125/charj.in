<?php

namespace App\Services;

class BrevoService
{
    private string $apiKey;
    private string $senderEmail;
    private string $senderName;
    private string $adminEmail;

    public function __construct()
    {
        $this->apiKey       = (string) getenv('BREVO_API_KEY');
        $this->senderEmail  = (string) (getenv('BREVO_SENDER_EMAIL') ?: 'hello@charj.in');
        $this->senderName   = (string) (getenv('BREVO_SENDER_NAME')  ?: 'Charj.in');
        $this->adminEmail   = (string) (getenv('ADMIN_EMAIL')         ?: 'admin@charj.in');
    }

    // =========================================================================
    // PUBLIC API
    // =========================================================================

    /**
     * Send a personalised confirmation email to the user who submitted a lead.
     */
    public function sendLeadConfirmation(array $lead): bool
    {
        if (empty($lead['email'])) {
            return false;
        }

        $name        = $lead['name']         ?? 'EV Enthusiast';
        $leadType    = $lead['lead_type']    ?? 'general';
        $vehicleName = $lead['vehicle_name'] ?? ($lead['vehicle'] ?? '');

        [$subject, $headline, $intro, $bullets, $cta] = $this->getLeadTypeContent($leadType, $name, $vehicleName);

        $html = $this->buildLeadConfirmationHtml($name, $headline, $intro, $bullets, $cta);
        $text = $this->buildLeadConfirmationText($name, $headline, $intro, $bullets, $cta);

        return $this->sendEmail($lead['email'], $name, $subject, $html, $text);
    }

    /**
     * Send a formatted alert to the admin when a new lead comes in.
     */
    public function sendAdminLeadAlert(array $lead): bool
    {
        $leadId   = $lead['id']        ?? 'N/A';
        $type     = $lead['lead_type'] ?? 'general';
        $name     = $lead['name']      ?? 'Unknown';
        $mobile   = $lead['mobile']    ?? 'N/A';
        $email    = $lead['email']     ?? 'N/A';
        $city     = $lead['city']      ?? 'N/A';
        $vehicle  = $lead['vehicle_name'] ?? ($lead['vehicle'] ?? 'N/A');
        $message  = $lead['message']   ?? '';
        $budget   = $lead['budget']    ?? 'N/A';
        $source   = $lead['source_page'] ?? 'N/A';
        $utm      = trim(($lead['utm_source'] ?? '') . ' / ' . ($lead['utm_medium'] ?? '') . ' / ' . ($lead['utm_campaign'] ?? ''), ' /');

        $subject = "[Charj Lead #{$leadId}] New {$type} from {$name}";

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;">
<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
  <div style="background:#0a2342;padding:20px 30px;">
    <h2 style="color:#27ae60;margin:0;font-size:22px;">New Lead Alert</h2>
    <p style="color:#ccc;margin:4px 0 0;font-size:13px;">Charj.in Admin Notification</p>
  </div>
  <div style="padding:24px 30px;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
      <tr><td style="padding:8px 0;color:#666;width:160px;"><strong>Lead ID</strong></td><td style="padding:8px 0;color:#222;">#{$leadId}</td></tr>
      <tr style="background:#f9f9f9;"><td style="padding:8px 6px;color:#666;"><strong>Lead Type</strong></td><td style="padding:8px 6px;color:#222;">{$type}</td></tr>
      <tr><td style="padding:8px 0;color:#666;"><strong>Name</strong></td><td style="padding:8px 0;color:#222;">{$name}</td></tr>
      <tr style="background:#f9f9f9;"><td style="padding:8px 6px;color:#666;"><strong>Mobile</strong></td><td style="padding:8px 6px;color:#222;">{$mobile}</td></tr>
      <tr><td style="padding:8px 0;color:#666;"><strong>Email</strong></td><td style="padding:8px 0;color:#222;">{$email}</td></tr>
      <tr style="background:#f9f9f9;"><td style="padding:8px 6px;color:#666;"><strong>City</strong></td><td style="padding:8px 6px;color:#222;">{$city}</td></tr>
      <tr><td style="padding:8px 0;color:#666;"><strong>Vehicle</strong></td><td style="padding:8px 0;color:#222;">{$vehicle}</td></tr>
      <tr style="background:#f9f9f9;"><td style="padding:8px 6px;color:#666;"><strong>Budget</strong></td><td style="padding:8px 6px;color:#222;">{$budget}</td></tr>
      <tr><td style="padding:8px 0;color:#666;"><strong>Source Page</strong></td><td style="padding:8px 0;color:#222;">{$source}</td></tr>
      <tr style="background:#f9f9f9;"><td style="padding:8px 6px;color:#666;"><strong>UTM</strong></td><td style="padding:8px 6px;color:#222;">{$utm}</td></tr>
HTML;

        if ($message) {
            $html .= '<tr><td style="padding:8px 0;color:#666;vertical-align:top;"><strong>Message</strong></td><td style="padding:8px 0;color:#222;">' . nl2br(htmlspecialchars($message)) . '</td></tr>';
        }

        $html .= <<<HTML
    </table>
    <div style="margin-top:24px;text-align:center;">
      <a href="https://charj.in/admin/leads/{$leadId}" style="background:#27ae60;color:#fff;text-decoration:none;padding:12px 28px;border-radius:6px;font-weight:bold;display:inline-block;">View Lead in Admin</a>
    </div>
  </div>
  <div style="background:#f4f4f4;padding:14px 30px;text-align:center;font-size:12px;color:#999;">
    Charj.in &bull; India's EV Marketplace &bull; <a href="https://charj.in" style="color:#27ae60;">charj.in</a>
  </div>
</div>
</body>
</html>
HTML;

        $text = "New Lead Alert - Charj.in\n\n"
            . "Lead ID: #{$leadId}\n"
            . "Type: {$type}\n"
            . "Name: {$name}\n"
            . "Mobile: {$mobile}\n"
            . "Email: {$email}\n"
            . "City: {$city}\n"
            . "Vehicle: {$vehicle}\n"
            . "Budget: {$budget}\n"
            . "Source: {$source}\n"
            . "UTM: {$utm}\n"
            . ($message ? "Message: {$message}\n" : '');

        return $this->sendEmail($this->adminEmail, 'Charj Admin', $subject, $html, $text);
    }

    /**
     * Send a welcome email to a new subscriber / registered user.
     */
    public function sendWelcomeEmail(string $email, string $name): bool
    {
        $subject = "Welcome to Charj.in – India's #1 EV Marketplace";

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;">
<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
  <div style="background:#0a2342;padding:30px;text-align:center;">
    <h1 style="color:#27ae60;margin:0;font-size:28px;">⚡ Charj.in</h1>
    <p style="color:#ccc;margin:6px 0 0;font-size:14px;">India's #1 Electric Vehicle Marketplace</p>
  </div>
  <div style="padding:30px;">
    <h2 style="color:#0a2342;margin-top:0;">Welcome aboard, {$name}! 👋</h2>
    <p style="color:#444;line-height:1.6;">You've just joined thousands of smart Indians making the switch to electric mobility. Here's what you can do on Charj.in:</p>
    <ul style="color:#444;line-height:2;padding-left:20px;">
      <li>Browse <strong>500+ electric vehicles</strong> – scooters, bikes, cars &amp; more</li>
      <li>Compare EVs side-by-side with detailed specs</li>
      <li>Get personalised EV recommendations based on your needs</li>
      <li>Find <strong>charging stations</strong> near you</li>
      <li>Read expert reviews and the latest EV news</li>
    </ul>
    <div style="text-align:center;margin:30px 0;">
      <a href="https://charj.in/ev-finder" style="background:#27ae60;color:#fff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:16px;display:inline-block;">Find My Perfect EV →</a>
    </div>
    <p style="color:#666;font-size:13px;">Have questions? Reply to this email or WhatsApp us – we're always happy to help you go electric!</p>
  </div>
  <div style="background:#0a2342;padding:20px 30px;text-align:center;">
    <p style="color:#ccc;margin:0 0 8px;font-size:13px;">
      <a href="https://charj.in" style="color:#27ae60;">charj.in</a> &nbsp;|&nbsp;
      <a href="https://charj.in/articles" style="color:#ccc;text-decoration:none;">EV News</a> &nbsp;|&nbsp;
      <a href="https://charj.in/charging-stations" style="color:#ccc;text-decoration:none;">Charging Stations</a>
    </p>
    <p style="color:#555;margin:0;font-size:11px;">© 2025 Charj.in &bull; India's EV Marketplace</p>
  </div>
</div>
</body>
</html>
HTML;

        $text = "Welcome to Charj.in, {$name}!\n\n"
            . "You've joined India's #1 Electric Vehicle Marketplace.\n\n"
            . "What you can do:\n"
            . "- Browse 500+ electric vehicles\n"
            . "- Compare EVs side by side\n"
            . "- Get personalised recommendations\n"
            . "- Find charging stations near you\n\n"
            . "Get started: https://charj.in/ev-finder\n\n"
            . "Questions? Just reply to this email.\n\n"
            . "Team Charj.in";

        return $this->sendEmail($email, $name, $subject, $html, $text);
    }

    /**
     * Alert a dealer about a new lead that has been assigned to them.
     */
    public function sendDealerAlert(string $dealerEmail, string $dealerName, array $lead): bool
    {
        $leadId      = $lead['id']           ?? 'N/A';
        $customerName = $lead['name']        ?? 'A customer';
        $mobile      = $lead['mobile']       ?? 'N/A';
        $email       = $lead['email']        ?? 'N/A';
        $city        = $lead['city']         ?? 'N/A';
        $vehicle     = $lead['vehicle_name'] ?? ($lead['vehicle'] ?? 'N/A');
        $message     = $lead['message']      ?? '';
        $budget      = $lead['budget']       ?? 'N/A';
        $timeline    = $lead['purchase_timeline'] ?? 'N/A';

        $subject = "[Charj.in] New Customer Lead – {$customerName} is interested in {$vehicle}";

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;">
<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
  <div style="background:#0a2342;padding:20px 30px;">
    <h2 style="color:#27ae60;margin:0;">New Lead from Charj.in</h2>
    <p style="color:#ccc;margin:4px 0 0;font-size:13px;">A customer is interested in purchasing an EV from you</p>
  </div>
  <div style="padding:24px 30px;">
    <p style="color:#444;">Dear <strong>{$dealerName}</strong>,</p>
    <p style="color:#444;line-height:1.6;">You have a new lead from Charj.in. Please contact the customer at the earliest to assist them with their EV purchase.</p>
    <table style="width:100%;border-collapse:collapse;font-size:14px;margin:16px 0;">
      <tr style="background:#f0f9f4;"><td style="padding:10px;color:#666;width:160px;"><strong>Customer Name</strong></td><td style="padding:10px;color:#222;">{$customerName}</td></tr>
      <tr><td style="padding:10px;color:#666;"><strong>Mobile</strong></td><td style="padding:10px;color:#222;"><strong>{$mobile}</strong></td></tr>
      <tr style="background:#f0f9f4;"><td style="padding:10px;color:#666;"><strong>Email</strong></td><td style="padding:10px;color:#222;">{$email}</td></tr>
      <tr><td style="padding:10px;color:#666;"><strong>City</strong></td><td style="padding:10px;color:#222;">{$city}</td></tr>
      <tr style="background:#f0f9f4;"><td style="padding:10px;color:#666;"><strong>Vehicle Interest</strong></td><td style="padding:10px;color:#222;"><strong>{$vehicle}</strong></td></tr>
      <tr><td style="padding:10px;color:#666;"><strong>Budget</strong></td><td style="padding:10px;color:#222;">{$budget}</td></tr>
      <tr style="background:#f0f9f4;"><td style="padding:10px;color:#666;"><strong>Purchase Timeline</strong></td><td style="padding:10px;color:#222;">{$timeline}</td></tr>
HTML;

        if ($message) {
            $html .= '<tr><td style="padding:10px;color:#666;vertical-align:top;"><strong>Message</strong></td><td style="padding:10px;color:#222;">' . nl2br(htmlspecialchars($message)) . '</td></tr>';
        }

        $html .= <<<HTML
    </table>
    <div style="background:#fff8e1;border-left:4px solid #f39c12;padding:14px 18px;margin:16px 0;border-radius:4px;">
      <p style="margin:0;color:#856404;font-size:13px;"><strong>Tip:</strong> Leads contacted within 30 minutes have a 3x higher conversion rate. Call <strong>{$mobile}</strong> now!</p>
    </div>
  </div>
  <div style="background:#f4f4f4;padding:14px 30px;text-align:center;font-size:12px;color:#999;">
    Lead #{$leadId} &bull; <a href="https://charj.in" style="color:#27ae60;">charj.in</a> &bull; India's EV Marketplace
  </div>
</div>
</body>
</html>
HTML;

        $text = "New Lead from Charj.in\n\n"
            . "Dear {$dealerName},\n\n"
            . "You have a new customer lead.\n\n"
            . "Customer Name: {$customerName}\n"
            . "Mobile: {$mobile}\n"
            . "Email: {$email}\n"
            . "City: {$city}\n"
            . "Vehicle Interest: {$vehicle}\n"
            . "Budget: {$budget}\n"
            . "Purchase Timeline: {$timeline}\n"
            . ($message ? "Message: {$message}\n" : '')
            . "\nPlease contact the customer as soon as possible.\n\n"
            . "Lead ID: #{$leadId}\n"
            . "Charj.in – India's EV Marketplace";

        return $this->sendEmail($dealerEmail, $dealerName, $subject, $html, $text);
    }

    /**
     * Add a contact to a Brevo contact list.
     *
     * @param array  $contact   Keys: email (required), firstName, lastName, phone, city, attributes (assoc array)
     * @param string $listType  'leads' | 'newsletter' | 'dealers' – mapped to list IDs via env
     */
    public function addContactToList(array $contact, string $listType): bool
    {
        if (empty($contact['email'])) {
            return false;
        }

        $listIdMap = [
            'leads'      => (int) (getenv('BREVO_LIST_LEADS')      ?: 0),
            'newsletter' => (int) (getenv('BREVO_LIST_NEWSLETTER') ?: 0),
            'dealers'    => (int) (getenv('BREVO_LIST_DEALERS')    ?: 0),
        ];

        $listId = $listIdMap[$listType] ?? 0;

        $attributes = array_merge([
            'FIRSTNAME' => $contact['firstName'] ?? ($contact['name'] ?? ''),
            'LASTNAME'  => $contact['lastName']  ?? '',
            'SMS'       => $contact['phone']     ?? '',
            'CITY'      => $contact['city']      ?? '',
        ], $contact['attributes'] ?? []);

        $payload = [
            'email'            => $contact['email'],
            'attributes'       => (object) $attributes,
            'updateEnabled'    => true,
        ];

        if ($listId > 0) {
            $payload['listIds'] = [$listId];
        }

        $ch = curl_init('https://api.brevo.com/v3/contacts');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'api-key: ' . $this->apiKey,
                'content-type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT    => 10,
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 201 = created, 204 = updated (no body)
        if ($status < 200 || $status >= 300) {
            log_message('error', "Brevo addContact failed ({$status}): {$response}");
            return false;
        }

        return true;
    }

    /**
     * Core email send via Brevo REST API v3.
     */
    public function sendEmail(string $to, string $toName, string $subject, string $htmlContent, string $textContent = ''): bool
    {
        if (!$this->apiKey || $this->apiKey === 'replace_with_brevo_api_key') {
            log_message('info', "Brevo not configured. Skipped email: {$subject}");
            return false;
        }

        $payload = [
            'sender'      => ['name' => $this->senderName, 'email' => $this->senderEmail],
            'to'          => [['email' => $to, 'name' => $toName]],
            'subject'     => $subject,
            'htmlContent' => $htmlContent,
        ];

        if ($textContent !== '') {
            $payload['textContent'] = $textContent;
        }

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'api-key: ' . $this->apiKey,
                'content-type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT    => 10,
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            log_message('error', "Brevo sendEmail failed ({$status}) to {$to}: {$response}");
            return false;
        }

        log_message('info', "Brevo email sent to {$to}: {$subject}");
        return true;
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Return [subject, headline, intro, bullets[], cta] based on lead_type.
     */
    private function getLeadTypeContent(string $leadType, string $name, string $vehicleName): array
    {
        $firstName = explode(' ', trim($name))[0];

        switch ($leadType) {
            case 'get_best_price':
                $subject  = "We're finding the best price for you – Charj.in";
                $headline = "We're on it, {$firstName}!";
                $intro    = $vehicleName
                    ? "Great choice! We're working to secure the best possible price on the <strong>{$vehicleName}</strong> for you."
                    : "Great choice! We're working to secure the best price on your selected EV.";
                $bullets  = [
                    'Our dealer network is being notified of your enquiry right now',
                    'You will receive 2–3 competing price quotes within 24 hours',
                    'Our EV advisor may call you to understand your exact requirements',
                    'Zero obligation – compare quotes and decide at your own pace',
                ];
                $cta = ['text' => 'View More EVs', 'url' => 'https://charj.in/ev'];
                break;

            case 'ev_recommendation':
                $subject  = "Your personal EV advisor is on the way – Charj.in";
                $headline = "Your perfect EV is just a call away, {$firstName}!";
                $intro    = "Our EV expert has received your details and will reach out shortly to help you find the EV that fits your lifestyle and budget perfectly.";
                $bullets  = [
                    'A dedicated EV advisor will call you within 4 working hours',
                    'They will short-list the top 3 EVs based on your requirements',
                    'You will get a personalised comparison with pros, cons and pricing',
                    'Test drive arrangements can be made at no cost to you',
                ];
                $cta = ['text' => 'Try Our EV Finder', 'url' => 'https://charj.in/ev-finder'];
                break;

            case 'fleet_enquiry':
                $subject  = "Charj.in Fleet Team will contact you shortly";
                $headline = "Your fleet EV enquiry is received, {$firstName}!";
                $intro    = "Our dedicated fleet solutions team will connect with you to discuss bulk pricing, financing options and after-sales support for your fleet.";
                $bullets  = [
                    'Our fleet specialist will call you within 2 business hours',
                    'Exclusive bulk pricing and GST-ready invoicing available',
                    'Custom financing and lease options for 5+ vehicle orders',
                    'Dedicated after-sales and charging infrastructure support',
                ];
                $cta = ['text' => 'Explore Fleet EVs', 'url' => 'https://charj.in/fleet'];
                break;

            case 'charger_installation':
                $subject  = "EV Charger Installation – we'll call you soon";
                $headline = "Home charger installation sorted, {$firstName}!";
                $intro    = "Our certified charging partner will get in touch to assess your home/office setup and provide a customised installation quote.";
                $bullets  = [
                    'Our charging partner will call within 24 hours to book a site visit',
                    'Site survey is completely free of charge',
                    'Installation typically takes 2–4 hours once approved',
                    'All chargers come with a 1-year installation warranty',
                ];
                $cta = ['text' => 'Learn About EV Charging', 'url' => 'https://charj.in/charging-stations'];
                break;

            case 'test_drive':
                $subject  = "Test drive request confirmed – Charj.in";
                $headline = "Your test drive is being arranged, {$firstName}!";
                $intro    = $vehicleName
                    ? "We've received your request for a <strong>{$vehicleName}</strong> test drive and are coordinating with the nearest dealer."
                    : "We've received your test drive request and are coordinating with the nearest dealer.";
                $bullets  = [
                    'The dealer will confirm date and time within 24 hours',
                    'Bring your driving licence on the day of the test drive',
                    'Test drives are completely free and without any obligation',
                    'You can ask the dealer any questions about the vehicle',
                ];
                $cta = ['text' => 'View Vehicle Details', 'url' => 'https://charj.in/ev'];
                break;

            case 'financing':
                $subject  = "EV Finance options ready for you – Charj.in";
                $headline = "Smart financing on your EV, {$firstName}!";
                $intro    = "Our finance team will reach out with the best EMI options, low-interest loans and FAME-II subsidy details for your selected EV.";
                $bullets  = [
                    'Finance expert will call you within 4 hours on a working day',
                    'Loan options from 10+ banks and NBFCs – lowest rates guaranteed',
                    'Down payment as low as 10% with instant approval',
                    'FAME-II and state subsidy benefits will be applied automatically',
                ];
                $cta = ['text' => 'Explore EV Financing', 'url' => 'https://charj.in/financing'];
                break;

            default:
                $subject  = "Thanks for reaching out to Charj.in!";
                $headline = "We've received your enquiry, {$firstName}!";
                $intro    = "Thank you for reaching out to Charj.in – India's #1 EV marketplace. Our team will be in touch shortly.";
                $bullets  = [
                    'Our team will review your enquiry and respond within 24 hours',
                    'Feel free to browse our EV catalogue while you wait',
                    'Use our EV Finder to get personalised recommendations',
                    'Check out the latest EV news and reviews on our blog',
                ];
                $cta = ['text' => 'Explore Charj.in', 'url' => 'https://charj.in'];
        }

        return [$subject, $headline, $intro, $bullets, $cta];
    }

    /**
     * Build the full HTML email for a lead confirmation.
     */
    private function buildLeadConfirmationHtml(string $name, string $headline, string $intro, array $bullets, array $cta): string
    {
        $bulletsHtml = '';
        foreach ($bullets as $bullet) {
            $bulletsHtml .= "<li style=\"margin-bottom:10px;color:#444;\">{$bullet}</li>";
        }

        $ctaText = htmlspecialchars($cta['text']);
        $ctaUrl  = htmlspecialchars($cta['url']);

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$headline}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,Helvetica,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.1);">

          <!-- HEADER -->
          <tr>
            <td style="background:#0a2342;padding:28px 30px;text-align:center;">
              <h1 style="margin:0;color:#27ae60;font-size:30px;letter-spacing:1px;">⚡ Charj.in</h1>
              <p style="margin:6px 0 0;color:#8fb3d0;font-size:13px;">India's #1 Electric Vehicle Marketplace</p>
            </td>
          </tr>

          <!-- HERO BAND -->
          <tr>
            <td style="background:#27ae60;padding:16px 30px;text-align:center;">
              <p style="margin:0;color:#fff;font-size:15px;font-weight:bold;">✅ Enquiry Received &nbsp;|&nbsp; Our team is on it!</p>
            </td>
          </tr>

          <!-- BODY -->
          <tr>
            <td style="padding:32px 30px;">
              <h2 style="color:#0a2342;margin-top:0;font-size:22px;">{$headline}</h2>
              <p style="color:#444;line-height:1.7;font-size:15px;">{$intro}</p>

              <h3 style="color:#0a2342;font-size:16px;margin-bottom:12px;">What happens next:</h3>
              <ul style="padding-left:20px;line-height:1.8;font-size:14px;">
                {$bulletsHtml}
              </ul>

              <div style="text-align:center;margin:32px 0 16px;">
                <a href="{$ctaUrl}" style="background:#27ae60;color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:6px;font-size:16px;font-weight:bold;display:inline-block;">{$ctaText} →</a>
              </div>

              <p style="color:#666;font-size:13px;line-height:1.6;">Have questions? You can reply to this email or WhatsApp us. We're happy to help you make the switch to electric!</p>
            </td>
          </tr>

          <!-- DIVIDER -->
          <tr><td style="padding:0 30px;"><hr style="border:none;border-top:1px solid #eee;"></td></tr>

          <!-- QUICK LINKS -->
          <tr>
            <td style="padding:20px 30px;background:#f9fafb;">
              <p style="margin:0 0 10px;color:#888;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Explore More on Charj.in</p>
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="text-align:center;padding:8px;">
                    <a href="https://charj.in/ev" style="color:#27ae60;text-decoration:none;font-size:13px;font-weight:bold;">🚗 Browse EVs</a>
                  </td>
                  <td style="text-align:center;padding:8px;">
                    <a href="https://charj.in/compare" style="color:#27ae60;text-decoration:none;font-size:13px;font-weight:bold;">⚖️ Compare</a>
                  </td>
                  <td style="text-align:center;padding:8px;">
                    <a href="https://charj.in/charging-stations" style="color:#27ae60;text-decoration:none;font-size:13px;font-weight:bold;">⚡ Charging</a>
                  </td>
                  <td style="text-align:center;padding:8px;">
                    <a href="https://charj.in/articles" style="color:#27ae60;text-decoration:none;font-size:13px;font-weight:bold;">📰 News</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- FOOTER -->
          <tr>
            <td style="background:#0a2342;padding:20px 30px;text-align:center;">
              <p style="margin:0 0 6px;color:#8fb3d0;font-size:12px;">
                <a href="https://charj.in" style="color:#27ae60;text-decoration:none;">charj.in</a> &nbsp;|&nbsp;
                <a href="mailto:hello@charj.in" style="color:#8fb3d0;text-decoration:none;">hello@charj.in</a>
              </p>
              <p style="margin:0;color:#4a6278;font-size:11px;">© 2025 Charj.in &bull; India's EV Marketplace &bull; All rights reserved</p>
              <p style="margin:6px 0 0;color:#4a6278;font-size:11px;">You received this email because you submitted an enquiry on charj.in.</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }

    /**
     * Build a plain-text version of the lead confirmation email.
     */
    private function buildLeadConfirmationText(string $name, string $headline, string $intro, array $bullets, array $cta): string
    {
        $bulletText = '';
        foreach ($bullets as $i => $bullet) {
            $bulletText .= ($i + 1) . ". {$bullet}\n";
        }

        $ctaText = $cta['text'];
        $ctaUrl  = $cta['url'];

        return <<<TEXT
{$headline}

Hi {$name},

{$intro}

What happens next:
{$bulletText}
{$ctaText}: {$ctaUrl}

---
Charj.in – India's #1 Electric Vehicle Marketplace
Website: https://charj.in
Email: hello@charj.in

Browse EVs: https://charj.in/ev
Compare: https://charj.in/compare
Charging Stations: https://charj.in/charging-stations
EV News: https://charj.in/articles

© 2025 Charj.in. All rights reserved.
TEXT;
    }
}
