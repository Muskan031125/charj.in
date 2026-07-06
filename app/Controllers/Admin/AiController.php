<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class AiController extends AdminBaseController
{
    /**
     * POST admin/ai/suggest-image
     * Given a vehicle name + brand, returns a ranked list of image URL candidates
     * by probing the CarWale CDN (imgd.aeplcdn.com) search results page.
     */
    public function suggestImage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $body  = json_decode($this->request->getBody(), true);
        $name  = trim($body['name']  ?? '');
        $brand = trim($body['brand'] ?? '');

        if (!$name) {
            return $this->response->setJSON(['error' => 'Vehicle name is required.']);
        }

        $query = strtolower(trim("$brand $name"));

        // ── Strategy 1: known slug → CDN map ──────────────────────────
        $slug = preg_replace('/[^a-z0-9]+/', '-', $query);
        $slug = trim($slug, '-');

        $knownMap = [
            'ather-450x'             => [4 => 'ather-450x-right-front-three-quarter',       3 => 'ather-450x-front-three-quarter-2'],
            'ola-s1-pro'             => [4 => 's1-pro-right-front-three-quarter',            3 => 's1-pro-front-three-quarter-2'],
            'ola-s1-air'             => [4 => 's1-air-right-front-three-quarter'],
            'ather-rizta'            => [4 => 'rizta-right-front-three-quarter'],
            'tvs-iqube'              => [4 => 'iqube-right-front-three-quarter',             3 => 'iqube-right-side-view'],
            'tvs-iqube-s'            => [4 => 'iqube-right-front-three-quarter'],
            'revolt-rv400'           => [4 => 'revolt-rv400-right-front-three-quarter'],
            'tata-nexon-ev'          => [4 => 'nexon-ev-right-front-three-quarter-4',        3 => 'nexon-ev-front-three-quarter-2'],
            'tata-punch-ev'          => [4 => 'punch-ev-right-front-three-quarter-3'],
            'tata-tiago-ev'          => [4 => 'tiago-ev-right-front-three-quarter-5'],
            'mg-windsor-ev'          => [4 => 'windsor-ev-right-front-three-quarter'],
            'mg-zs-ev'               => [4 => 'zs-ev-right-front-three-quarter-6',          3 => 'zs-ev-front-three-quarter-3'],
            'hyundai-creta-electric' => [4 => 'creta-electric-right-front-three-quarter'],
            'mahindra-be-6'          => [4 => 'be-6-right-front-three-quarter'],
            'mahindra-xev-9e'        => [4 => 'xev-9e-right-front-three-quarter'],
            'bajaj-chetak'           => [4 => 'chetak-right-front-three-quarter-5'],
            'bajaj-chetak-premium'   => [4 => 'chetak-right-front-three-quarter-5'],
            'hero-vida-v1'           => [4 => 'vida-v1-right-front-three-quarter-2'],
            'hero-vida-v1-pro'       => [4 => 'vida-v1-right-front-three-quarter-2'],
            'ampere-nexus'           => [4 => 'nexus-right-front-three-quarter-3'],
            'mahindra-treo'          => [4 => 'treo-right-front-three-quarter'],
            'piaggio-ape-e-city'     => [4 => 'ape-e-city-right-front-three-quarter'],
        ];

        // Try slug variants (with brand, without brand)
        $brandSlug = preg_replace('/[^a-z0-9]+/', '-', strtolower($brand));
        $nameSlug  = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
        $tryKeys   = array_unique([$slug, $brandSlug . '-' . $nameSlug, $nameSlug]);

        $cdnBase = 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec';
        $suggestions = [];

        foreach ($tryKeys as $key) {
            if (isset($knownMap[$key])) {
                foreach ($knownMap[$key] as $modelId => $file) {
                    $url = "$cdnBase/{$modelId}00/{$file}.jpeg?isig=0&q=80";
                    // verify the URL is live
                    if ($this->headCheck($url)) {
                        $suggestions[] = [
                            'url'   => $url,
                            'label' => 'Official',
                            'src'   => 'CarWale CDN',
                        ];
                    }
                }
                if (!empty($suggestions)) break;
            }
        }

        // ── Strategy 2: scrape CarWale search page for og:image ───────
        if (empty($suggestions)) {
            $searchUrl = 'https://www.carwale.com/search/?q=' . urlencode($name);
            $html = $this->httpGet($searchUrl);
            if ($html) {
                // Extract og:image candidates
                preg_match_all('/https:\/\/imgd\.aeplcdn\.com[^\s"\']+\.(?:jpeg|jpg|png|webp)[^\s"\']*/', $html, $m);
                $seen = [];
                foreach (array_unique($m[0] ?? []) as $url) {
                    if (count($seen) >= 3) break;
                    $clean = strtok($url, '"');
                    if (!in_array($clean, $seen) && $this->headCheck($clean)) {
                        $seen[] = $clean;
                        $suggestions[] = ['url' => $clean, 'label' => 'CarWale', 'src' => 'CarWale search'];
                    }
                }
            }
        }

        // ── Strategy 3: Zigwheels fallback ────────────────────────────
        if (empty($suggestions)) {
            $searchUrl = 'https://www.zigwheels.com/search?q=' . urlencode($name);
            $html = $this->httpGet($searchUrl);
            if ($html) {
                preg_match_all('/https:\/\/media\.zigwheels\.com[^\s"\']+\.(?:jpeg|jpg|png)[^\s"\']*/', $html, $m);
                foreach (array_unique($m[0] ?? []) as $url) {
                    if (count($suggestions) >= 3) break;
                    $clean = strtok($url, '"');
                    if ($this->headCheck($clean)) {
                        $suggestions[] = ['url' => $clean, 'label' => 'Zigwheels', 'src' => 'Zigwheels'];
                    }
                }
            }
        }

        if (empty($suggestions)) {
            return $this->response->setJSON([
                'error' => "No images found for \"$name\". Paste the image URL manually."
            ]);
        }

        return $this->response->setJSON(['suggestions' => $suggestions]);
    }

    private function headCheck(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 6,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120',
        ]);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return in_array($code, [200, 301, 302]);
    }

    private function httpGet(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120',
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($code === 200 && $body) ? $body : null;
    }
}
