<?php

namespace App\Controllers;

use App\Models\LeadModel;
use App\Services\BrevoService;

class LeadController extends BaseController
{
    public function submit()
    {
        $rules = [
            'name'      => 'required|min_length[2]|max_length[120]',
            'mobile'    => 'required|min_length[10]|max_length[15]',
            'email'     => 'permit_empty|valid_email|max_length[150]',
            'lead_type' => 'required|max_length[80]',
        ];

        $isAjax = $this->request->hasHeader('X-Requested-With')
            && strtolower($this->request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($isAjax) {
                return $this->jsonResponse(['success' => false, 'errors' => $errors], 422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Capture all form fields + request metadata
        $payload = [
            'lead_type'         => $this->request->getPost('lead_type'),
            'name'              => $this->request->getPost('name'),
            'email'             => $this->request->getPost('email') ?: null,
            'mobile'            => $this->request->getPost('mobile'),
            'city'              => $this->request->getPost('city') ?: null,
            'state'             => $this->request->getPost('state') ?: null,
            'pincode'           => $this->request->getPost('pincode') ?: null,
            'vehicle_id'        => $this->request->getPost('vehicle_id') ?: null,
            'category_id'       => $this->request->getPost('category_id') ?: null,
            'brand_id'          => $this->request->getPost('brand_id') ?: null,
            'dealer_id'         => $this->request->getPost('dealer_id') ?: null,
            'message'           => $this->request->getPost('message') ?: null,
            'budget'            => $this->request->getPost('budget') ?: null,
            'purchase_timeline' => $this->request->getPost('purchase_timeline') ?: null,
            'finance_required'  => $this->request->getPost('finance_required') ? 1 : 0,
            'charging_required' => $this->request->getPost('charging_required') ? 1 : 0,
            // UTM tracking
            'utm_source'        => $this->request->getPost('utm_source') ?: null,
            'utm_medium'        => $this->request->getPost('utm_medium') ?: null,
            'utm_campaign'      => $this->request->getPost('utm_campaign') ?: null,
            // Request metadata
            'source_url'        => $this->request->getPost('source_url') ?: previous_url(),
            'ip_address'        => $this->request->getIPAddress(),
            'user_agent'        => $this->request->getUserAgent()->getAgentString(),
            'status'            => 'new',
        ];

        $leadModel = new LeadModel();
        $leadId    = $leadModel->insert($payload, true);

        if (!$leadId) {
            log_message('error', 'Lead insert failed: ' . json_encode($leadModel->errors()));
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Unable to save your enquiry. Please try again.',
                ], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Unable to save your enquiry. Please try again.');
        }

        $lead     = array_merge($payload, ['id' => $leadId]);
        $brevo    = new BrevoService();
        $dealerId = $this->request->getPost('dealer_id');

        // 1. Confirmation email to the user (only when email was provided)
        if (!empty($lead['email'])) {
            $brevo->sendLeadConfirmation($lead);
        }

        // 2. Admin alert — always
        $brevo->sendAdminLeadAlert($lead);

        // 3. Dealer alert — if a dealer was specified
        if ($dealerId) {
            try {
                $db     = \Config\Database::connect();
                $dealer = $db->table('dealers')->where('id', $dealerId)->get()->getRowArray();
                if (!empty($dealer['email'])) {
                    $brevo->sendEmail(
                        $dealer['email'],
                        $dealer['name'] ?? 'Dealer',
                        'New Lead from Charj.in - ' . ($lead['name'] ?? ''),
                        "New customer enquiry via Charj.in\n\n"
                            . "Name: {$lead['name']}\n"
                            . "Mobile: {$lead['mobile']}\n"
                            . "Email: " . ($lead['email'] ?? '-') . "\n"
                            . "City: " . ($lead['city'] ?? '-') . "\n"
                            . "Lead Type: {$lead['lead_type']}\n"
                            . "Message: " . ($lead['message'] ?? '-')
                    );
                }
            } catch (\Throwable $e) {
                log_message('warning', 'Dealer alert email failed: ' . $e->getMessage());
            }
        }

        // 4. Add / update Brevo contact list (non-critical)
        if (!empty($lead['email'])) {
            try {
                $this->addToBrevoList($brevo, $lead);
            } catch (\Throwable $e) {
                log_message('warning', 'Brevo contact upsert failed: ' . $e->getMessage());
            }
        }

        $successMessage = 'Thank you, ' . ($lead['name'] ?? '') . '! Your enquiry has been received. Our EV advisor will contact you soon.';

        if ($isAjax) {
            return $this->jsonResponse([
                'success'  => true,
                'message'  => $successMessage,
                'lead_id'  => $leadId,
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Upsert a contact into the configured Brevo list.
     */
    private function addToBrevoList(BrevoService $brevo, array $lead): void
    {
        $apiKey = (string) getenv('BREVO_API_KEY');
        if (!$apiKey || $apiKey === 'replace_with_brevo_api_key') {
            return;
        }

        $listId = (int) (getenv('BREVO_LIST_ID') ?: 0);

        $contactPayload = [
            'email'         => $lead['email'],
            'attributes'    => [
                'FIRSTNAME' => $lead['name'] ?? '',
                'SMS'       => $lead['mobile'] ?? '',
                'CITY'      => $lead['city'] ?? '',
            ],
            'updateEnabled' => true,
        ];

        if ($listId > 0) {
            $contactPayload['listIds'] = [$listId];
        }

        $ch = curl_init('https://api.brevo.com/v3/contacts');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'api-key: ' . $apiKey,
                'content-type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($contactPayload),
            CURLOPT_TIMEOUT    => 8,
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // HTTP 400 means contact already exists — treat as success
        if ($status >= 400 && $status !== 400) {
            log_message('warning', 'Brevo contact upsert HTTP ' . $status . ': ' . $response);
        }
    }
}
