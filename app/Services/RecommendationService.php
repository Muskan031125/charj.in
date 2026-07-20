<?php

namespace App\Services;

use App\Models\VehicleModel;

class RecommendationService
{
    private VehicleModel $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
    }

    // =========================================================================
    // PUBLIC API
    // =========================================================================

    /**
     * Main recommendation method.
     *
     * @param array $inputs {
     *   use_case          : string  personal|commercial|fleet
     *   budget_max        : int     Maximum budget in INR
     *   daily_km          : int     Daily commute in km
     *   priority          : string  range|price|brand|features
     *   charging_type     : string  home|public|both
     *   category_preference: string scooter|bike|car|rickshaw|any
     *   need_financing    : bool
     *   pincode           : string  (optional)
     * }
     *
     * @return array {
     *   vehicles: array   Top 3 vehicles with score and reason
     *   total_matched: int
     *   filters_applied: array
     * }
     */
    public function recommend(array $inputs): array
    {
        // ------------------------------------------------------------------
        // 1. Normalise inputs
        // ------------------------------------------------------------------
        $useCase           = strtolower(trim($inputs['use_case']           ?? 'personal'));
        $budgetMax         = (int)   ($inputs['budget_max']                ?? 0);
        $dailyKm           = (int)   ($inputs['daily_km']                  ?? 0);
        $priority          = strtolower(trim($inputs['priority']           ?? 'range'));
        $chargingType      = strtolower(trim($inputs['charging_type']      ?? 'both'));
        $categoryPref      = strtolower(trim($inputs['category_preference'] ?? 'any'));
        $needFinancing     = (bool)  ($inputs['need_financing']            ?? false);
        $pincode           = trim($inputs['pincode'] ?? '');

        // ------------------------------------------------------------------
        // 2. Build filters for the model query
        // ------------------------------------------------------------------
        $filters = [];

        if ($budgetMax > 0) {
            $filters['max_budget'] = $budgetMax;
        }

        // Minimum range needed = daily_km * 1.5 (so scorer can still refine)
        if ($dailyKm > 0) {
            $filters['min_range'] = (int) ($dailyKm * 0.5); // generous lower bound
        }

        // Category mapping
        if ($categoryPref !== 'any') {
            $categoryIds = $this->resolveCategoryIds($categoryPref);
            if (!empty($categoryIds)) {
                $filters['category_ids'] = $categoryIds;
            }
        }

        // Use-case → best_for hints
        $bestForHints = $this->getBestForHints($useCase, $categoryPref);
        if (!empty($bestForHints)) {
            $filters['best_for'] = $bestForHints;
        }

        // ------------------------------------------------------------------
        // 3. Fetch candidates
        // ------------------------------------------------------------------
        $candidates = $this->vehicleModel->forRecommendation($filters);

        // ------------------------------------------------------------------
        // 4. Score each candidate
        // ------------------------------------------------------------------
        $scored = [];
        foreach ($candidates as $vehicle) {
            $score  = 0;
            $notes  = [];

            // ---- Budget fit (25 pts) ----
            $price = (float) ($vehicle['starting_price'] ?? $vehicle['ex_showroom_price'] ?? 0);
            if ($budgetMax > 0 && $price > 0) {
                if ($price <= $budgetMax) {
                    $score += 25;
                    $notes[] = 'within budget';
                } elseif ($price <= $budgetMax * 1.10) {
                    $score += 15;
                    $notes[] = 'slightly above budget';
                }
                // else 0 pts
            } elseif ($budgetMax === 0) {
                // No budget specified – neutral
                $score += 12;
            }

            // ---- Range fit (25 pts) ----
            $range = (int) ($vehicle['real_world_range'] ?? $vehicle['claimed_range'] ?? 0);
            if ($dailyKm > 0 && $range > 0) {
                if ($range >= $dailyKm * 1.5) {
                    $score += 25;
                    $notes[] = 'excellent range';
                } elseif ($range >= $dailyKm) {
                    $score += 18;
                    $notes[] = 'adequate range';
                } elseif ($range >= $dailyKm * 0.8) {
                    $score += 10;
                    $notes[] = 'borderline range';
                }
                // else 0 pts
            } elseif ($dailyKm === 0) {
                $score += 12;
            }

            // ---- Use-case / best_for match (20 pts) ----
            $bestFor = strtolower($vehicle['best_for'] ?? '');
            $useCaseScore = $this->scoreUseCase($bestFor, $useCase, $categoryPref);
            $score += $useCaseScore;
            if ($useCaseScore >= 20) {
                $notes[] = 'perfect use-case match';
            } elseif ($useCaseScore >= 10) {
                $notes[] = 'good use-case match';
            }

            // ---- Expert rating (15 pts) ----
            $rating = (float) ($vehicle['expert_rating'] ?? $vehicle['user_rating'] ?? 0);
            if ($rating > 0) {
                $ratingScore = (int) round(($rating / 5.0) * 15);
                $score += $ratingScore;
                if ($ratingScore >= 12) {
                    $notes[] = 'highly rated';
                }
            }

            // ---- Fast charging bonus (10 pts) ----
            $fastChargingSupported = !empty($vehicle['fast_charging_supported'])
                || !empty($vehicle['fast_charging']);
            if ($fastChargingSupported) {
                $score += 10;
                if ($chargingType === 'public' || $chargingType === 'both') {
                    $notes[] = 'fast charging supported';
                }
            }

            // ---- Warranty (5 pts) ----
            $warrantyYears = (int) ($vehicle['warranty_years'] ?? 0);
            if ($warrantyYears === 0) {
                // Try to parse from warranty string e.g. "3 years"
                preg_match('/(\d+)\s*year/i', $vehicle['warranty'] ?? '', $m);
                $warrantyYears = (int) ($m[1] ?? 0);
            }
            if ($warrantyYears >= 3) {
                $score += 5;
            } elseif ($warrantyYears >= 2) {
                $score += 3;
            }

            // ---- Priority boost ----
            $score += $this->priorityBoost($vehicle, $priority, $budgetMax, $dailyKm);

            // ---- Build reason string ----
            $reason = $this->buildReason($vehicle, $notes, $score, $budgetMax, $dailyKm, $useCase);

            $scored[] = [
                'vehicle' => $vehicle,
                'score'   => $score,
                'reason'  => $reason,
            ];
        }

        // ------------------------------------------------------------------
        // 5. Sort by score descending
        // ------------------------------------------------------------------
        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        // ------------------------------------------------------------------
        // 6. Return top 3
        // ------------------------------------------------------------------
        $top3 = array_slice($scored, 0, 3);

        return [
            'vehicles'       => $top3,
            'total_matched'  => count($candidates),
            'filters_applied' => [
                'use_case'           => $useCase,
                'budget_max'         => $budgetMax,
                'daily_km'           => $dailyKm,
                'priority'           => $priority,
                'charging_type'      => $chargingType,
                'category_preference' => $categoryPref,
                'need_financing'     => $needFinancing,
                'pincode'            => $pincode,
            ],
        ];
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Map a category preference string to category IDs.
     * Returns empty array if IDs cannot be determined (caller handles gracefully).
     */
    private function resolveCategoryIds(string $categoryPref): array
    {
        // Fetch from DB dynamically using slug
        try {
            $categoryModel = new \App\Models\CategoryModel();
            $categories    = $categoryModel->where('status', 'published')->findAll();

            $ids = [];
            foreach ($categories as $cat) {
                $slug = strtolower($cat['slug'] ?? '');
                $name = strtolower($cat['name'] ?? '');
                if (
                    str_contains($slug, $categoryPref) ||
                    str_contains($name, $categoryPref) ||
                    $slug === $categoryPref
                ) {
                    $ids[] = (int) $cat['id'];
                }
            }
            return $ids;
        } catch (\Throwable $e) {
            log_message('error', 'RecommendationService::resolveCategoryIds – ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Convert use_case + category_preference into best_for hint terms.
     */
    private function getBestForHints(string $useCase, string $categoryPref): array
    {
        $hints = [];

        switch ($useCase) {
            case 'personal':
                $hints = ['personal', 'family', 'commute', 'daily'];
                break;
            case 'commercial':
                $hints = ['commercial', 'delivery', 'business', 'cargo'];
                break;
            case 'fleet':
                $hints = ['fleet', 'corporate', 'commercial', 'business'];
                break;
        }

        if ($categoryPref !== 'any') {
            $hints[] = $categoryPref;
        }

        return $hints;
    }

    /**
     * Score use-case match. Returns 0–20.
     */
    private function scoreUseCase(string $bestFor, string $useCase, string $categoryPref): int
    {
        $score       = 0;
        $useCaseMap  = [
            'personal'   => ['personal', 'family', 'commute', 'daily use', 'daily'],
            'commercial' => ['commercial', 'delivery', 'business', 'cargo', 'logistics'],
            'fleet'      => ['fleet', 'corporate', 'commercial', 'business', 'bulk'],
        ];

        $targets = $useCaseMap[$useCase] ?? [];

        // Exact / partial match in best_for text
        foreach ($targets as $term) {
            if (str_contains($bestFor, $term)) {
                $score = 20;
                break;
            }
        }

        // Partial credit: category name in best_for
        if ($score === 0 && $categoryPref !== 'any' && str_contains($bestFor, $categoryPref)) {
            $score = 10;
        }

        return $score;
    }

    /**
     * Extra points based on the user's stated priority. Returns 0–8.
     */
    private function priorityBoost(array $vehicle, string $priority, int $budgetMax, int $dailyKm): int
    {
        switch ($priority) {
            case 'range':
                $range = (int) ($vehicle['real_world_range'] ?? $vehicle['claimed_range'] ?? 0);
                return $range >= 200 ? 8 : ($range >= 150 ? 5 : ($range >= 100 ? 2 : 0));

            case 'price':
                $price = (float) ($vehicle['starting_price'] ?? 0);
                if ($budgetMax > 0 && $price > 0) {
                    $ratio = $price / $budgetMax;
                    return $ratio <= 0.70 ? 8 : ($ratio <= 0.85 ? 5 : ($ratio <= 1.0 ? 2 : 0));
                }
                return 0;

            case 'brand':
                // Reward higher expert rating as a brand-quality proxy
                $rating = (float) ($vehicle['expert_rating'] ?? 0);
                return $rating >= 4.5 ? 8 : ($rating >= 4.0 ? 5 : ($rating >= 3.5 ? 2 : 0));

            case 'features':
                // Count features in features_json
                $featuresJson = $vehicle['features_json'] ?? '[]';
                $features     = json_decode($featuresJson, true);
                $count        = is_array($features) ? count($features) : 0;
                return $count >= 15 ? 8 : ($count >= 10 ? 5 : ($count >= 5 ? 2 : 0));

            default:
                return 0;
        }
    }

    /**
     * Build a human-readable recommendation reason string.
     */
    private function buildReason(array $vehicle, array $notes, int $score, int $budgetMax, int $dailyKm, string $useCase): string
    {
        $name  = $vehicle['name'] ?? 'This vehicle';
        $brand = $vehicle['brand_name'] ?? '';
        $label = trim("{$brand} {$name}");
        $range = (int) ($vehicle['real_world_range'] ?? $vehicle['claimed_range'] ?? 0);
        $price = (float) ($vehicle['starting_price'] ?? 0);

        $parts = [];

        if (!empty($notes)) {
            $parts[] = ucfirst(implode(', ', $notes));
        }

        if ($range > 0 && $dailyKm > 0) {
            $days = $range > 0 ? floor($range / max($dailyKm, 1)) : 0;
            if ($days >= 2) {
                $parts[] = "can cover ~{$days} days of your commute on a single charge";
            }
        }

        if ($budgetMax > 0 && $price > 0 && $price <= $budgetMax) {
            $saving = $budgetMax - $price;
            if ($saving > 0) {
                $savingFmt = '₹' . number_format($saving);
                $parts[] = "saves you {$savingFmt} vs your budget";
            }
        }

        if (empty($parts)) {
            $parts[] = "a strong all-round choice for {$useCase} use";
        }

        return "{$label} – " . implode('; ', $parts) . '.';
    }
}
