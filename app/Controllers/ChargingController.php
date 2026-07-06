<?php

namespace App\Controllers;

class ChargingController extends BaseController
{
    public function index()
    {
        $db   = \Config\Database::connect();
        $city = trim((string) $this->request->getGet('city'));
        if (strtolower($city) === 'all') $city = '';

        // Distinct cities with at least one operational charging station
        $citiesResult = $db->table('charging_stations')
            ->select('city')
            ->where('status', 'operational')
            ->where('city IS NOT NULL', null, false)
            ->where('city !=', '')
            ->groupBy('city')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();
        $cities = array_column($citiesResult, 'city');

        $query = $db->table('charging_stations')
            ->where('status', 'operational')
            ->orderBy('name', 'ASC');

        if ($city !== '') {
            $query->where('city', $city);
        }

        $stations = $query->get()->getResultArray();

        $metaTitle = $city
            ? 'EV Charging Stations in ' . $city . ' | Charj.in'
            : 'EV Charging Stations in India - Find Fast & Slow Chargers | Charj.in';
        $metaDesc  = $city
            ? 'Find EV charging stations in ' . $city . '. Locations, charging speed, connectors and operating hours.'
            : 'Find electric vehicle charging stations near you across India. Locate fast chargers, slow chargers and public EV charging points.';

        return $this->render('charging/index', [
            'stations'         => $stations,
            'cities'           => $cities,
            'activeCity'       => $city,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }

    // GET /charging-stations/api?lat=LAT&lng=LNG&city=CITY
    public function api()
    {
        $lat  = (float) $this->request->getGet('lat');
        $lng  = (float) $this->request->getGet('lng');
        $city = trim((string) $this->request->getGet('city'));
        $key  = env('OCM_API_KEY', '');

        // City-to-lat/lng map for India
        $cityCoords = [
            'delhi'         => [28.6139, 77.2090],
            'new delhi'     => [28.6139, 77.2090],
            'mumbai'        => [19.0760, 72.8777],
            'bangalore'     => [12.9716, 77.5946],
            'bengaluru'     => [12.9716, 77.5946],
            'pune'          => [18.5204, 73.8567],
            'hyderabad'     => [17.3850, 78.4867],
            'chennai'       => [13.0827, 80.2707],
            'ahmedabad'     => [23.0225, 72.5714],
            'kolkata'       => [22.5726, 88.3639],
            'jaipur'        => [26.9124, 75.7873],
            'surat'         => [21.1702, 72.8311],
            'lucknow'       => [26.8467, 80.9462],
            'noida'         => [28.5355, 77.3910],
            'gurgaon'       => [28.4595, 77.0266],
            'gurugram'      => [28.4595, 77.0266],
            'chandigarh'    => [30.7333, 76.7794],
            'coimbatore'    => [11.0168, 76.9558],
            'nagpur'        => [21.1458, 79.0882],
            'indore'        => [22.7196, 75.8577],
            'bhopal'        => [23.2599, 77.4126],
            'kochi'         => [9.9312,  76.2673],
            'thiruvananthapuram' => [8.5241, 76.9366],
            'visakhapatnam' => [17.6868, 83.2185],
            'vadodara'      => [22.3072, 73.1812],
            'rajkot'        => [22.3039, 70.8022],
            'nashik'        => [19.9975, 73.7898],
            'agra'          => [27.1767, 78.0081],
            'varanasi'      => [25.3176, 82.9739],
            'bhubaneswar'   => [20.2961, 85.8245],
            'patna'         => [25.5941, 85.1376],
            'raipur'        => [21.2514, 81.6296],
            'dehradun'      => [30.3165, 78.0322],
            'jammu'         => [32.7266, 74.8570],
        ];

        // Resolve lat/lng from city name if not provided
        if ($lat == 0 && $lng == 0 && $city) {
            $coords = $cityCoords[strtolower($city)] ?? null;
            if ($coords) { $lat = $coords[0]; $lng = $coords[1]; }
        }

        if ($lat == 0 && $lng == 0) {
            $lat = 28.6139; $lng = 77.2090; // Default to Delhi
        }

        // If OCM_API_KEY is configured, use OpenChargeMap (premium, needs free registration at openchargemap.io).
        // Otherwise fall back to OpenStreetMap Overpass API (free, no key needed).
        if ($key) {
            $result = $this->fetchFromOCM($lat, $lng, $key);
        } else {
            $result = $this->fetchFromOverpass($lat, $lng);
        }

        return $this->response->setJSON(array_merge($result, ['lat' => $lat, 'lng' => $lng]));
    }

    // ── Overpass (OpenStreetMap) — free, no key required ──────────────
    private function fetchFromOverpass(float $lat, float $lng): array
    {
        $radius = 30000; // 30 km in metres
        $query  = '[out:json][timeout:20];'
                . '(node["amenity"="charging_station"](around:' . $radius . ',' . $lat . ',' . $lng . ');'
                . 'way["amenity"="charging_station"](around:' . $radius . ',' . $lat . ',' . $lng . '););'
                . 'out center 100;';

        $ch = curl_init('https://overpass-api.de/api/interpreter');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => 'data=' . urlencode($query),
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_USERAGENT      => 'CharjIn/1.0 (info@charj.in)',
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $body     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$body || $httpCode !== 200) {
            return ['success' => false, 'stations' => [], 'source' => 'osm_error'];
        }

        $data     = json_decode($body, true) ?? [];
        $elements = $data['elements'] ?? [];
        $stations = [];

        // Well-known operator abbreviations
        $opAbbr = [
            'energy efficiency services limited' => 'EESL',
            'eesl'                               => 'EESL',
            'tata power'                         => 'Tata Power',
            'tata power ez charge'               => 'Tata Power EZ Charge',
            'jio-bp pulse'                       => 'Jio-bp Pulse',
            'jio bp'                             => 'Jio-bp',
            'chargezone'                         => 'ChargeZone',
            'statiq'                             => 'Statiq',
            'ather energy'                       => 'Ather Energy',
            'ather'                              => 'Ather',
            'bescom'                             => 'BESCOM',
            'bpcl'                               => 'BPCL',
            'hpcl'                               => 'HPCL',
            'iocl'                               => 'IOCL',
            'fortum'                             => 'Fortum',
            'shell recharge'                     => 'Shell Recharge',
            'mgcharge'                           => 'MG Charge',
            'mg charge'                          => 'MG Charge',
            'nexcharge'                          => 'NexCharge',
            'relux'                              => 'Relux Electric',
            'volttic'                            => 'Volttic',
        ];

        foreach ($elements as $el) {
            $tags = $el['tags'] ?? [];
            if (($tags['amenity'] ?? '') !== 'charging_station') continue;

            // ── Build a sensible display name ──────────────────────────────
            $osmName    = trim($tags['name'] ?? '');
            $osmBrand   = trim($tags['brand'] ?? '');
            $osmOp      = trim($tags['operator'] ?? '');
            $suburb     = trim($tags['addr:suburb'] ?? $tags['addr:locality'] ?? $tags['addr:quarter'] ?? '');
            $street     = trim($tags['addr:street'] ?? '');

            // Abbreviate known operator names
            $opDisplay = '';
            if ($osmOp) {
                $opKey     = strtolower($osmOp);
                $opDisplay = $opAbbr[$opKey] ?? (strlen($osmOp) <= 30 ? $osmOp : substr($osmOp, 0, 28) . '…');
            }

            // Name priority: explicit name > brand > operator+location > skip
            if ($osmName) {
                $name = $osmName;
            } elseif ($osmBrand) {
                $name = $osmBrand . ($suburb ? ' – ' . $suburb : ($street ? ' on ' . $street : ''));
            } elseif ($opDisplay) {
                $location = $suburb ?: $street;
                $name = $opDisplay . ' EV Charger' . ($location ? ' – ' . $location : '');
            } else {
                // No identifying info at all — skip this station
                continue;
            }

            // ── Build address ──────────────────────────────────────────────
            $addrParts = array_filter([
                trim(($tags['addr:housenumber'] ?? '') . ' ' . ($tags['addr:street'] ?? '')),
                $tags['addr:suburb']   ?? $tags['addr:locality'] ?? '',
                $tags['addr:city']     ?? $tags['addr:town']     ?? '',
            ]);
            $address = implode(', ', $addrParts);
            // If no structured address, fall back to description/note
            if (!$address) {
                $address = trim($tags['description'] ?? $tags['note'] ?? '');
            }

            // Operator display label
            $operatorLabel = $opDisplay ?: ($osmBrand ?: ($osmName ?: 'Public Charger'));

            // ── Connector types ────────────────────────────────────────────
            $connTypes = [];
            $osm2conn  = [
                'socket:type2'         => 'Type 2 AC',
                'socket:type2_combo'   => 'CCS2',
                'socket:ccs'           => 'CCS2',
                'socket:chademo'       => 'CHAdeMO',
                'socket:type1'         => 'Type 1',
                'socket:type1_combo'   => 'CCS1',
                'socket:gbt_dc'        => 'GB/T DC',
                'socket:gbt_ac'        => 'GB/T AC',
                'socket:bharat_ac_001' => 'Bharat AC',
                'socket:bharat_dc_001' => 'Bharat DC',
            ];
            foreach ($osm2conn as $tag => $label) {
                if (!empty($tags[$tag]) && $tags[$tag] !== 'no') {
                    $connTypes[] = $label;
                }
            }

            // ── Power / speed ──────────────────────────────────────────────
            $maxKw = 0;
            foreach (['charging:motorcar:max_power', 'max_power', 'motorcar:output:power'] as $pTag) {
                if (!empty($tags[$pTag])) {
                    $maxKw = (float) preg_replace('/[^0-9.]/', '', $tags[$pTag]);
                    break;
                }
            }
            $speed = 'slow';
            if ($maxKw >= 50)  $speed = 'rapid';
            elseif ($maxKw >= 11) $speed = 'fast';

            // ── Ports ──────────────────────────────────────────────────────
            $ports = max(1, (int) ($tags['capacity'] ?? $tags['charging:count'] ?? 1));

            // ── Coordinates ────────────────────────────────────────────────
            $elLat = (float) ($el['lat'] ?? $el['center']['lat'] ?? $lat);
            $elLng = (float) ($el['lon'] ?? $el['center']['lon'] ?? $lng);

            $stations[] = [
                'id'              => 'osm_' . $el['id'],
                'name'            => $name,
                'address'         => $address,
                'city'            => $tags['addr:city'] ?? $tags['addr:town'] ?? '',
                'lat'             => $elLat,
                'lng'             => $elLng,
                'operator'        => $operatorLabel,
                'charging_speed'  => $speed,
                'max_kw'          => $maxKw,
                'connector_types' => $connTypes,
                'total_ports'     => $ports,
                'is_open_24x7'    => isset($tags['opening_hours']) && str_contains($tags['opening_hours'], '24/7'),
                'price_per_kwh'   => null,
                'status'          => 'operational',
                'source'          => 'osm',
            ];
        }

        return [
            'success'  => true,
            'stations' => $stations,
            'source'   => 'openstreetmap',
        ];
    }

    // ── OpenChargeMap — requires free API key from openchargemap.io ──
    private function fetchFromOCM(float $lat, float $lng, string $key): array
    {
        $apiUrl = 'https://api.openchargemap.io/v3/poi/?' . http_build_query([
            'output'       => 'json',
            'maxresults'   => 100,
            'latitude'     => $lat,
            'longitude'    => $lng,
            'distance'     => 30,
            'distanceunit' => 'KM',
            'countrycode'  => 'IN',
            'statustypeid' => 50,
            'compact'      => 'true',
            'verbose'      => 'false',
            'key'          => $key,
        ]);

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_USERAGENT      => 'CharjIn/1.0 (info@charj.in)',
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $body     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$body || $httpCode !== 200) {
            // OCM failed — fall back to Overpass
            return $this->fetchFromOverpass($lat, $lng);
        }

        $raw      = json_decode($body, true) ?? [];
        $stations = [];

        foreach ($raw as $s) {
            $addr  = $s['AddressInfo'] ?? [];
            $conns = $s['Connections'] ?? [];
            $connTypes = [];
            $maxKw     = 0;
            foreach ($conns as $c) {
                $ct = $c['ConnectionType']['Title'] ?? '';
                if ($ct && !in_array($ct, $connTypes)) $connTypes[] = $ct;
                $kw = (float) ($c['PowerKW'] ?? 0);
                if ($kw > $maxKw) $maxKw = $kw;
            }
            $speed = 'slow';
            if ($maxKw >= 50) $speed = 'rapid';
            elseif ($maxKw >= 11) $speed = 'fast';

            $stations[] = [
                'id'              => $s['ID'] ?? 0,
                'name'            => $addr['Title'] ?? 'EV Charging Station',
                'address'         => trim(($addr['AddressLine1'] ?? '') . ' ' . ($addr['AddressLine2'] ?? '')),
                'city'            => $addr['Town'] ?? $addr['StateOrProvince'] ?? '',
                'lat'             => $addr['Latitude'] ?? $lat,
                'lng'             => $addr['Longitude'] ?? $lng,
                'operator'        => ($s['OperatorInfo']['Title'] ?? '') ?: 'Unknown Operator',
                'charging_speed'  => $speed,
                'max_kw'          => $maxKw,
                'connector_types' => $connTypes,
                'total_ports'     => count($conns),
                'is_open_24x7'    => ($addr['AccessComments'] ?? '') !== '',
                'price_per_kwh'   => null,
                'status'          => 'operational',
                'source'          => 'ocm',
            ];
        }

        return [
            'success'  => true,
            'stations' => $stations,
            'source'   => 'openchargemap',
        ];
    }

    public function city(string $city)
    {
        $db = \Config\Database::connect();

        $stations = $db->table('charging_stations')
            ->where('status', 'operational')
            ->where('city', $city)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        // Distinct cities list for nav
        $citiesResult = $db->table('charging_stations')
            ->select('city')
            ->where('status', 'operational')
            ->where('city IS NOT NULL', null, false)
            ->where('city !=', '')
            ->groupBy('city')
            ->orderBy('city', 'ASC')
            ->get()
            ->getResultArray();
        $cities = array_column($citiesResult, 'city');

        $metaTitle = 'EV Charging Stations in ' . $city . ' | Charj.in';
        $metaDesc  = 'Find all electric vehicle charging stations in ' . $city . '. Get addresses, charging speeds, connector types and operating hours.';

        return $this->render('charging/city', [
            'stations'         => $stations,
            'cities'           => $cities,
            'activeCity'       => $city,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ]);
    }
}
