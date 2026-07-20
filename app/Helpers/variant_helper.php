<?php

/**
 * EV variant / trim data.
 *
 * The `vehicles` table stores one row per model with a starting_price..max_price
 * range. Real-world EVs ship in multiple battery/trim variants. Until variants
 * live in their own table, this helper provides curated variant data for popular
 * models so the UI can offer a variant selector. Unknown slugs return [] and the
 * UI degrades gracefully (no selector shown).
 *
 * Each variant: name, battery (kWh), range (km, claimed), price (₹ ex-showroom),
 * fast (bool, DC fast-charge supported).
 */

if (!function_exists('ev_variants')) {
    function ev_variants(string $slug): array
    {
        static $map = null;
        if ($map === null) {
            $map = [
                // ── Tata 4W ──
                'tata-nexon-ev' => [
                    ['name' => 'Medium Range', 'battery' => 30,   'range' => 325, 'price' => 1249000, 'fast' => true],
                    ['name' => 'Long Range',   'battery' => 40.5, 'range' => 465, 'price' => 1699000, 'fast' => true, 'popular' => true],
                ],
                'tata-tiago-ev' => [
                    ['name' => 'Medium Range', 'battery' => 19.2, 'range' => 250, 'price' => 799000,  'fast' => false, 'popular' => true],
                    ['name' => 'Long Range',   'battery' => 24,   'range' => 315, 'price' => 1114000, 'fast' => true],
                ],
                'tata-punch-ev' => [
                    ['name' => 'Medium Range', 'battery' => 25,   'range' => 315, 'price' => 1099000, 'fast' => true],
                    ['name' => 'Long Range',   'battery' => 35,   'range' => 421, 'price' => 1444000, 'fast' => true, 'popular' => true],
                ],
                'tata-curvv-ev' => [
                    ['name' => '45 kWh', 'battery' => 45, 'range' => 502, 'price' => 1749000, 'fast' => true],
                    ['name' => '55 kWh', 'battery' => 55, 'range' => 585, 'price' => 1949000, 'fast' => true, 'popular' => true],
                ],
                // ── MG 4W ──
                'mg-zs-ev' => [
                    ['name' => 'Excite',    'battery' => 50.3, 'range' => 461, 'price' => 1898000, 'fast' => true, 'popular' => true],
                    ['name' => 'Exclusive', 'battery' => 50.3, 'range' => 461, 'price' => 1958000, 'fast' => true],
                ],
                'mg-comet-ev' => [
                    ['name' => 'Pace',  'battery' => 17.3, 'range' => 230, 'price' => 698000, 'fast' => false],
                    ['name' => 'Play',  'battery' => 17.3, 'range' => 230, 'price' => 798000, 'fast' => false, 'popular' => true],
                    ['name' => 'Plush', 'battery' => 17.3, 'range' => 230, 'price' => 998000, 'fast' => false],
                ],
                'mahindra-xuv400' => [
                    ['name' => 'EC (34.5 kWh)', 'battery' => 34.5, 'range' => 375, 'price' => 1549000, 'fast' => true],
                    ['name' => 'EL (39.4 kWh)', 'battery' => 39.4, 'range' => 456, 'price' => 1799000, 'fast' => true, 'popular' => true],
                ],
                // ── Ola 2W ──
                'ola-s1-pro' => [
                    ['name' => '3 kWh', 'battery' => 3, 'range' => 176, 'price' => 134999, 'fast' => true],
                    ['name' => '4 kWh', 'battery' => 4, 'range' => 242, 'price' => 154999, 'fast' => true, 'popular' => true],
                ],
                'ola-s1-air' => [
                    ['name' => '3 kWh', 'battery' => 3, 'range' => 151, 'price' => 104999, 'fast' => false, 'popular' => true],
                    ['name' => '4 kWh', 'battery' => 4, 'range' => 199, 'price' => 124999, 'fast' => false],
                ],
                'ola-s1-x' => [
                    ['name' => '2 kWh', 'battery' => 2, 'range' => 95,  'price' => 79999,  'fast' => false],
                    ['name' => '3 kWh', 'battery' => 3, 'range' => 151, 'price' => 99999,  'fast' => false, 'popular' => true],
                    ['name' => '4 kWh', 'battery' => 4, 'range' => 190, 'price' => 109999, 'fast' => false],
                ],
                // ── Ather 2W ──
                'ather-450x' => [
                    ['name' => '2.9 kWh', 'battery' => 2.9, 'range' => 111, 'price' => 129999, 'fast' => true],
                    ['name' => '3.7 kWh', 'battery' => 3.7, 'range' => 150, 'price' => 146999, 'fast' => true, 'popular' => true],
                ],
                'ather-rizta' => [
                    ['name' => 'S (2.9 kWh)', 'battery' => 2.9, 'range' => 123, 'price' => 109999, 'fast' => true, 'popular' => true],
                    ['name' => 'Z (3.7 kWh)', 'battery' => 3.7, 'range' => 159, 'price' => 144999, 'fast' => true],
                ],
                // ── TVS / Bajaj / Hero 2W ──
                'tvs-iqube' => [
                    ['name' => 'Standard (3.04 kWh)', 'battery' => 3.04, 'range' => 100, 'price' => 123000, 'fast' => false],
                    ['name' => 'ST (5.1 kWh)',        'battery' => 5.1,  'range' => 145, 'price' => 152000, 'fast' => true, 'popular' => true],
                ],
                'bajaj-chetak' => [
                    ['name' => '3201 (3.2 kWh)', 'battery' => 3.2, 'range' => 127, 'price' => 119998, 'fast' => false, 'popular' => true],
                    ['name' => '3501 (3.5 kWh)', 'battery' => 3.5, 'range' => 153, 'price' => 151819, 'fast' => true],
                ],
                'hero-vida-v1' => [
                    ['name' => 'V1 Plus (3.44 kWh)', 'battery' => 3.44, 'range' => 110, 'price' => 114900, 'fast' => false],
                    ['name' => 'V1 Pro (3.94 kWh)',  'battery' => 3.94, 'range' => 165, 'price' => 134900, 'fast' => true, 'popular' => true],
                ],
            ];
        }

        return $map[$slug] ?? [];
    }
}

if (!function_exists('ev_variant_count')) {
    function ev_variant_count(string $slug): int
    {
        return count(ev_variants($slug));
    }
}
