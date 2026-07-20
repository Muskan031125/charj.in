<?php
namespace App\Controllers;

class AiController extends BaseController
{
    private string $apiKey;
    private string $model = 'claude-haiku-4-5';

    public function __construct()
    {
        $this->apiKey = env('ANTHROPIC_API_KEY', '');
    }

    // POST /ai/ev-recommend  — takes quiz answers, returns AI EV recommendations
    public function evRecommend()
    {
        $body = $this->request->getJSON(true) ?? [];
        $answers = $body['answers'] ?? [];

        // Fetch vehicles from DB to give Claude real data
        $db = \Config\Database::connect();
        $vehicles = $db->query("
            SELECT v.name, v.slug, v.starting_price, v.claimed_range, v.expert_rating,
                   b.name as brand, vc.name as category, v.battery_capacity,
                   v.charging_time, v.top_speed, v.fast_charging_supported
            FROM vehicles v
            LEFT JOIN brands b ON b.id = v.brand_id
            LEFT JOIN vehicle_categories vc ON vc.id = v.category_id
            WHERE v.status = 'published'
            ORDER BY v.expert_rating DESC
        ")->getResultArray();

        $vehicleList = '';
        foreach ($vehicles as $v) {
            $price = $v['starting_price'] >= 100000
                ? '₹' . round($v['starting_price']/100000, 1) . ' L'
                : '₹' . number_format($v['starting_price']);
            $vehicleList .= "- {$v['brand']} {$v['name']} | {$v['category']} | Price: {$price} | Range: {$v['claimed_range']}km | Rating: {$v['expert_rating']}/10 | Slug: {$v['slug']}\n";
        }

        $budgetMap = ['under1L'=>'Under ₹1L','1to1.5L'=>'₹1–1.5L','1.5to3L'=>'₹1.5–3L','3to8L'=>'₹3–8L','above8L'=>'Above ₹8L'];
        $answersText = "Usage: " . ($answers['usage'] ?? 'daily commute') . "\n"
            . "Daily distance: " . ($answers['distance'] ?? 'unknown') . "\n"
            . "Charging: " . ($answers['charging'] ?? 'home') . "\n"
            . "Budget: " . ($budgetMap[$answers['budget'] ?? ''] ?? ($answers['budget'] ?? 'unknown')) . "\n"
            . "Vehicle type: " . ($answers['type'] ?? 'scooter') . "\n"
            . "Fast charging needed: " . ($answers['fastCharge'] ?? 'yes') . "\n"
            . "State: " . ($answers['state'] ?? 'not specified');

        $prompt = "You are an expert EV advisor for India. Based on user requirements, recommend the top 3 EVs from our catalog.\n\n"
            . "USER REQUIREMENTS:\n{$answersText}\n\n"
            . "AVAILABLE EVs IN OUR CATALOG:\n{$vehicleList}\n\n"
            . "Return ONLY valid JSON (no markdown, no explanation) in this exact format:\n"
            . '{"recommendations":[{"slug":"","name":"","brand":"","price":"","range":"km","rating":"","match_score":95,"why":"2-sentence reason why this is perfect for them","badge":"Best Match"},{"slug":"","name":"","brand":"","price":"","range":"km","rating":"","match_score":88,"why":"reason","badge":"Great Value"},{"slug":"","name":"","brand":"","price":"","range":"km","rating":"","match_score":80,"why":"reason","badge":"Also Consider"}],"summary":"1 sentence personalized insight about their EV journey"}';

        $result = $this->callClaude($prompt);
        if (!$result['ok']) {
            return $this->response->setJSON(['success' => false, 'error' => $result['error']]);
        }

        $data = json_decode($result['text'], true);
        if (!$data) {
            // Try to extract JSON from response
            preg_match('/\{.*\}/s', $result['text'], $m);
            $data = $m ? json_decode($m[0], true) : null;
        }

        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    // POST /ai/calculate — on-road price, TCO, EMI calculation with AI explanation
    public function calculate()
    {
        $body = $this->request->getJSON(true) ?? [];
        $vehicleSlug = $body['slug'] ?? '';
        $city = $body['city'] ?? 'Delhi';
        $dailyKm = (int)($body['daily_km'] ?? 40);
        $years = (int)($body['years'] ?? 3);

        $db = \Config\Database::connect();
        $v = $db->query("SELECT v.*, b.name as brand FROM vehicles v LEFT JOIN brands b ON b.id=v.brand_id WHERE v.slug=?", [$vehicleSlug])->getRowArray();

        if (!$v) {
            return $this->response->setJSON(['success' => false, 'error' => 'Vehicle not found']);
        }

        $exShowroom = (int)$v['starting_price'];
        // Standard on-road calculation
        $gst = 5; // 5% GST for EVs
        $rto = round($exShowroom * 0.01); // ~1% RTO for EVs
        $insurance = round($exShowroom * 0.025); // ~2.5%
        $handling = 15000;
        $fastTagSf = 500;
        $onRoad = $exShowroom + $rto + $insurance + $handling + $fastTagSf;

        // EMI (20% down, 36 months, 9% pa)
        $down = round($exShowroom * 0.2);
        $principal = $exShowroom - $down;
        $r = 0.09 / 12;
        $emi = round($principal * $r * pow(1+$r,36) / (pow(1+$r,36)-1));

        // Running cost: EVs ~₹1.5/km vs petrol ~₹8/km
        $annualKm = $dailyKm * 365;
        $evRunningPerYear = round($annualKm * 1.5);
        $petrolRunningPerYear = round($annualKm * 8);
        $savingsPerYear = $petrolRunningPerYear - $evRunningPerYear;
        $totalSavings = $savingsPerYear * $years;

        $prompt = "You are a financial advisor for EV buyers in India. Explain this cost breakdown in simple, friendly terms for buying the {$v['brand']} {$v['name']} in {$city}.\n\n"
            . "COST BREAKDOWN:\n"
            . "Ex-Showroom: ₹" . number_format($exShowroom) . "\n"
            . "RTO (~1% for EV, low because EVs get road tax exemption): ₹" . number_format($rto) . "\n"
            . "Insurance (1st year): ₹" . number_format($insurance) . "\n"
            . "Handling & accessories: ₹" . number_format($handling) . "\n"
            . "FASTag + SmartCard: ₹" . number_format($fastTagSf) . "\n"
            . "ON-ROAD PRICE: ₹" . number_format($onRoad) . "\n\n"
            . "FINANCING: Down payment ₹" . number_format($down) . " + EMI ₹" . number_format($emi) . "/month for 36 months\n\n"
            . "RUNNING COSTS ({$dailyKm}km/day for {$years} years):\n"
            . "EV running cost: ₹" . number_format($evRunningPerYear) . "/year (₹1.5/km)\n"
            . "Equivalent petrol cost: ₹" . number_format($petrolRunningPerYear) . "/year (₹8/km)\n"
            . "Annual savings over petrol: ₹" . number_format($savingsPerYear) . "\n"
            . "Total {$years}-year savings: ₹" . number_format($totalSavings) . "\n\n"
            . "Return ONLY valid JSON:\n"
            . '{"on_road":' . $onRoad . ',"breakdown":{"ex_showroom":' . $exShowroom . ',"rto":' . $rto . ',"insurance":' . $insurance . ',"handling":' . $handling . ',"fastag":' . $fastTagSf . '},"emi":{"monthly":' . $emi . ',"down_payment":' . $down . ',"tenure":36},"savings":{"per_year":' . $savingsPerYear . ',"total_years":' . $years . ',"total":' . $totalSavings . '},"ai_insight":"2-3 sentence friendly insight here","tip":"one smart money-saving tip specific to this vehicle and city"}';

        $result = $this->callClaude($prompt);
        if (!$result['ok']) {
            // Return calculated data even without AI insight
            return $this->response->setJSON(['success' => true, 'data' => [
                'on_road' => $onRoad,
                'breakdown' => ['ex_showroom'=>$exShowroom,'rto'=>$rto,'insurance'=>$insurance,'handling'=>$handling,'fastag'=>$fastTagSf],
                'emi' => ['monthly'=>$emi,'down_payment'=>$down,'tenure'=>36],
                'savings' => ['per_year'=>$savingsPerYear,'total_years'=>$years,'total'=>$totalSavings],
                'ai_insight' => "The {$v['brand']} {$v['name']} is a great EV choice with on-road price of ₹" . number_format($onRoad) . " in {$city}. You'll save ₹" . number_format($savingsPerYear) . "/year over petrol!",
                'tip' => "Check for state subsidies in {$city} — EVs get road tax exemption which already saved you ₹" . number_format(round($exShowroom * 0.08)) . " on RTO!",
            ]]);
        }

        $data = json_decode($result['text'], true);
        if (!$data) {
            preg_match('/\{.*\}/s', $result['text'], $m);
            $data = $m ? json_decode($m[0], true) : null;
        }
        if (!$data) $data = ['on_road'=>$onRoad,'emi'=>['monthly'=>$emi],'ai_insight'=>$result['text'],'tip'=>''];

        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    // POST /ai/chat — general EV Q&A
    public function chat()
    {
        $body = $this->request->getJSON(true) ?? [];
        $question = trim($body['question'] ?? '');
        $context = $body['context'] ?? ''; // e.g. current vehicle name

        if (!$question) {
            return $this->response->setJSON(['success' => false, 'error' => 'No question']);
        }

        $db = \Config\Database::connect();
        $vehicles = $db->query("SELECT v.name, v.slug, v.starting_price, v.claimed_range, b.name as brand, vc.name as category FROM vehicles v LEFT JOIN brands b ON b.id=v.brand_id LEFT JOIN vehicle_categories vc ON vc.id=v.category_id WHERE v.status='published' ORDER BY v.expert_rating DESC LIMIT 20")->getResultArray();
        $catalog = implode(', ', array_map(fn($v) => $v['brand'].' '.$v['name'], $vehicles));

        $systemPrompt = "You are Charj AI, an expert EV assistant for charj.in — India's EV marketplace. You help Indian consumers make smart EV decisions. You know about all EVs available in India including: {$catalog}. Keep answers concise (2-4 sentences max), factual, and India-specific. Always mention prices in Lakhs/Crores as Indians say them. Don't make up specs — if unsure, say so." . ($context ? " The user is currently viewing: {$context}." : '');

        $result = $this->callClaude($question, $systemPrompt);
        if (!$result['ok']) {
            return $this->response->setJSON(['success' => false, 'error' => $result['error']]);
        }

        return $this->response->setJSON(['success' => true, 'answer' => $result['text']]);
    }

    private function callClaude(string $userMessage, string $systemPrompt = 'You are a helpful EV expert for India.'): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_api_key_here') {
            return ['ok' => false, 'error' => 'API key not configured'];
        }

        $payload = json_encode([
            'model'      => $this->model,
            'max_tokens' => 1024,
            'system'     => $systemPrompt,
            'messages'   => [['role' => 'user', 'content' => $userMessage]],
        ]);

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
                'anthropic-version: 2023-06-01',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response || $httpCode !== 200) {
            return ['ok' => false, 'error' => 'Claude API error: ' . $httpCode];
        }

        $json = json_decode($response, true);
        $text = $json['content'][0]['text'] ?? '';
        return ['ok' => true, 'text' => $text];
    }
}
