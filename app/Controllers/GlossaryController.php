<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

class GlossaryController extends BaseController
{
    protected BaseConnection $db;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $grouped = [];
        $terms   = [];

        try {
            $terms = $this->db->table('ev_glossary')
                ->orderBy('term', 'ASC')
                ->get()
                ->getResultArray();

            // Group by first letter
            foreach ($terms as $term) {
                $letter = strtoupper($term['term'][0]);
                // Digits go under '#'
                if (is_numeric($letter)) {
                    $letter = '#';
                }
                $grouped[$letter][] = $term;
            }
            ksort($grouped);
        } catch (\Throwable $e) {
            // Table doesn't exist yet or DB error — view will fall back to static terms
            log_message('error', 'GlossaryController: ev_glossary query failed — ' . $e->getMessage());
            $grouped = [];
            $terms   = [];
        }

        return $this->render('pages/ev_glossary_db', [
            'meta_title'       => 'EV Glossary — A-Z Electric Vehicle Terms | Charj.in',
            'meta_description' => 'Complete EV glossary for India — from kWh to FAME II, BMS to V2G. Understand every electric vehicle term before you buy.',
            'grouped'          => $grouped,
            'categories'       => ['battery', 'charging', 'performance', 'finance', 'general', 'policy'],
            'total'            => count($terms),
        ]);
    }
}
