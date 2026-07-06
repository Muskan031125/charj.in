<?php

namespace App\Controllers;

use App\Services\RecommendationService;

class RecommendationController extends BaseController
{
    public function index()
    {
        return $this->render('recommendation/index', [
            'meta_title'       => 'Find Your Perfect EV - Free Recommendation Quiz | Charj.in',
            'meta_description' => 'Answer a few quick questions and get personalised electric vehicle recommendations based on your budget, usage and preferences.',
        ]);
    }

    public function recommend()
    {
        // Only handle POST/AJAX
        if (!$this->request->is('post')) {
            return $this->jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
        }

        $inputs = $this->request->getPost() ?: [];

        // Basic input sanitisation
        $inputs = array_map(fn($v) => is_string($v) ? trim($v) : $v, $inputs);

        try {
            $service         = new RecommendationService();
            $recommendations = $service->recommend($inputs);
        } catch (\Throwable $e) {
            log_message('error', 'RecommendationService error: ' . $e->getMessage());
            return $this->jsonResponse([
                'success'         => false,
                'message'         => 'Unable to generate recommendations. Please try again.',
                'recommendations' => [],
            ], 500);
        }

        // Persist the quiz session for analytics / retargeting
        $db = \Config\Database::connect();
        $sessionId = null;
        try {
            $db->table('recommendation_sessions')->insert([
                'inputs'           => json_encode($inputs),
                'results'          => json_encode($recommendations),
                'ip_address'       => $this->request->getIPAddress(),
                'user_agent'       => $this->request->getUserAgent()->getAgentString(),
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
            $sessionId = $db->insertID();
        } catch (\Throwable $e) {
            // Non-fatal — log and continue
            log_message('warning', 'Failed to save recommendation session: ' . $e->getMessage());
        }

        return $this->jsonResponse([
            'success'          => true,
            'session_id'       => $sessionId,
            'recommendations'  => $recommendations,
        ]);
    }
}
