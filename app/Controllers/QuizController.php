<?php

namespace App\Controllers;

class QuizController extends BaseController
{
    public function index(): string
    {
        $data = [
            'title'       => 'EV Finder Quiz — Find Your Perfect Electric Vehicle | Charj.in',
            'description' => 'Answer 7 quick questions and get personalised EV recommendations tailored to your budget, usage and location.',
        ];
        return view('quiz/index', $data);
    }

    public function saveAnswers()
    {
        if (!session()->get('user_logged_in')) {
            return $this->response->setJSON(['success' => false]);
        }

        $db = \Config\Database::connect();
        if (!$db->tableExists('user_activity')) {
            return $this->response->setJSON(['success' => false]);
        }

        $body = $this->request->getJSON(true) ?? [];
        $db->table('user_activity')->insert([
            'user_id'    => session()->get('user_id'),
            'action'     => 'quiz_complete',
            'metadata'   => json_encode($body),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true]);
    }
}
