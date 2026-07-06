<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>EV Glossary India | Electric Vehicle Terms Explained | Charj.in</title>
<meta name="description" content="Complete EV glossary for India — from kWh to FAME II, BMS to V2G. Understand every electric vehicle term before you buy. Simple, clear explanations.">
<meta name="keywords" content="EV glossary India, electric vehicle terms, kWh meaning, FAME II subsidy, BMS battery, CCS2 connector, real world range EV, what is regen braking">
<meta property="og:title" content="EV Glossary India | Electric Vehicle Terms Explained | Charj.in">
<meta property="og:description" content="From kWh to FAME II — understand every EV term before you buy. India's most complete electric vehicle glossary.">
<link rel="canonical" href="<?= base_url('ev-glossary') ?>">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "DefinedTermSet",
  "name": "EV Glossary — Electric Vehicle Terms Explained",
  "url": "<?= base_url('ev-glossary') ?>",
  "description": "India's most complete glossary of electric vehicle terms, from kWh to FAME II.",
  "publisher": {
    "@type": "Organization",
    "name": "Charj.in",
    "url": "<?= base_url() ?>"
  }
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// ================================================================
// GLOSSARY DATA — 30+ terms, alphabetically grouped
// ================================================================
$terms = [

  // ── B ──────────────────────────────────────────────────────────
  'B' => [
    [
      'id'       => 'bms',
      'term'     => 'BMS (Battery Management System)',
      'def'      => 'A BMS is the electronic system that monitors and manages a rechargeable battery pack in an EV. It tracks each cell\'s voltage, temperature, and state of charge to prevent overcharging, over-discharging, and overheating — all of which can damage the battery.',
      'example'  => 'A good BMS is why you can safely charge your Tata Nexon EV overnight without worrying about battery damage.',
      'related'  => ['SOC', 'SOH', 'Battery Degradation'],
    ],
    [
      'id'       => 'battery-degradation',
      'term'     => 'Battery Degradation',
      'def'      => 'Battery degradation refers to the gradual loss of a battery\'s ability to hold charge over time and through repeated charge-discharge cycles. A battery that once gave 200 km of range may deliver only 170 km after a few years — this reduction is called degradation.',
      'example'  => 'Most EV manufacturers guarantee that the battery will retain at least 70% of its original capacity after 8 years or 1.6 lakh km.',
      'related'  => ['SOH', 'BMS', 'Battery Warranty'],
    ],
  ],

  // ── C ──────────────────────────────────────────────────────────
  'C' => [
    [
      'id'       => 'ccs2',
      'term'     => 'CCS2 (Combined Charging System 2)',
      'def'      => 'CCS2 (also written CCS Type 2) is the DC fast-charging standard adopted by Bharat for electric cars. It combines the AC Type 2 inlet with two additional DC pins in a single connector, allowing both regular AC home charging and DC fast-charging from the same port.',
      'example'  => 'Tata Nexon EV, MG ZS EV, and Hyundai Kona all use CCS2 for DC fast charging at public stations.',
      'related'  => ['DC Fast Charging', 'Type 2 Connector', 'CHAdeMO'],
    ],
    [
      'id'       => 'chademo',
      'term'     => 'CHAdeMO',
      'def'      => 'CHAdeMO is a DC fast-charging standard developed in Japan, used by some older EV models like the Nissan Leaf. It\'s less common in India compared to CCS2, and the number of CHAdeMO charging stations in India is very limited.',
      'example'  => 'If you own a Nissan Leaf in India, you\'ll need to specifically look for CHAdeMO-compatible fast chargers, which are rare.',
      'related'  => ['CCS2', 'DC Fast Charging', 'Type 2 Connector'],
    ],
    [
      'id'       => 'claimed-range',
      'term'     => 'Claimed Range',
      'def'      => 'The claimed range is the official range figure certified under a standardised test cycle (ARAI in India) that a manufacturer advertises for their EV. This figure is typically tested under idealised laboratory conditions and does not reflect real-world driving.',
      'example'  => 'Ola S1 Pro claims 195 km but most riders report around 130–150 km in actual use — the claimed range is tested at low speed on a flat track.',
      'related'  => ['Real-world Range', 'ARAI', 'SOC'],
    ],
  ],

  // ── D ──────────────────────────────────────────────────────────
  'D' => [
    [
      'id'       => 'dc-fast-charging',
      'term'     => 'DC Fast Charging',
      'def'      => 'DC fast charging (also called Level 3 or rapid charging) bypasses the EV\'s onboard AC-to-DC converter and delivers high-power DC electricity directly to the battery. This allows charging times of 30–90 minutes instead of 6–12 hours with AC home chargers.',
      'example'  => 'Tata Tiago EV can charge from 10% to 80% in under 60 minutes using a 25 kW CCS2 fast charger.',
      'related'  => ['CCS2', 'CHAdeMO', 'AC Charging Time'],
    ],
    [
      'id'       => 'discom',
      'term'     => 'DISCOM (Distribution Company)',
      'def'      => 'A DISCOM is a state electricity distribution company responsible for supplying power to consumers. For EV owners, the DISCOM determines your home electricity tariff, which directly affects your per-km fuel cost. Some DISCOMs offer special low-tariff EV charging plans.',
      'example'  => 'BESCOM (Bengaluru), MSEDCL (Maharashtra), and TPDDL (Delhi) are DISCOMs that offer special EV tariffs to residential consumers.',
      'related'  => ['Time-of-use Tariff', 'Smart Charging', 'TCO'],
    ],
  ],

  // ── F ──────────────────────────────────────────────────────────
  'F' => [
    [
      'id'       => 'fame-ii',
      'term'     => 'FAME II (Faster Adoption and Manufacturing of Electric Vehicles)',
      'def'      => 'FAME II is the Government of India\'s flagship scheme to promote EV adoption through purchase subsidies and charging infrastructure development. Under FAME II, eligible electric two-wheelers, three-wheelers, four-wheelers, and buses receive upfront subsidies that reduce the on-road price.',
      'example'  => 'Under FAME II, electric scooters get a subsidy of ₹10,000 per kWh of battery capacity (up to a cap) if they meet localisation requirements. This can reduce prices by ₹20,000–₹50,000.',
      'related'  => ['Subsidy', 'On-road Price', 'TCO'],
    ],
  ],

  // ── I ──────────────────────────────────────────────────────────
  'I' => [
    [
      'id'       => 'ip-rating',
      'term'     => 'IP Rating (Ingress Protection)',
      'def'      => 'An IP rating indicates how well a battery or electrical component is sealed against dust and water. The rating is expressed as IP followed by two digits — the first for dust protection (0-6) and the second for water protection (0-9). Higher numbers mean better protection.',
      'example'  => 'An EV battery with IP67 rating is fully dustproof and can withstand immersion in 1 metre of water for 30 minutes — important for the Indian monsoon.',
      'related'  => ['BMS', 'Battery Degradation'],
    ],
  ],

  // ── K ──────────────────────────────────────────────────────────
  'K' => [
    [
      'id'       => 'kwh',
      'term'     => 'kWh (Kilowatt-hour)',
      'def'      => 'A kilowatt-hour is the unit used to measure an EV battery\'s energy storage capacity. A battery with more kWh can store more energy, generally giving more range. It\'s also the billing unit for electricity — knowing your battery\'s kWh lets you calculate the cost of a full charge.',
      'example'  => 'The Tata Nexon EV has a 30.2 kWh battery. If your electricity costs ₹8/kWh, a full charge costs about ₹242 — enough for roughly 250 km.',
      'related'  => ['Battery Capacity', 'Range', 'TCO'],
    ],
  ],

  // ── L ──────────────────────────────────────────────────────────
  'L' => [
    [
      'id'       => 'lfp-battery',
      'term'     => 'LFP Battery (Lithium Iron Phosphate)',
      'def'      => 'LFP batteries use lithium iron phosphate as the cathode material. They are known for being thermally stable (less fire risk), having a longer cycle life, and tolerating 100% state of charge without accelerated degradation. The trade-off is lower energy density compared to NMC batteries.',
      'example'  => 'Tata Nexon EV and BYD Atto 3 use LFP batteries, which is why they\'re considered safer for home charging overnight at 100%.',
      'related'  => ['NMC Battery', 'SOH', 'Battery Degradation'],
    ],
  ],

  // ── M ──────────────────────────────────────────────────────────
  'M' => [
    [
      'id'       => 'motor-torque',
      'term'     => 'Motor Torque',
      'def'      => 'Torque is the rotational force an electric motor produces, measured in Newton-metres (Nm). Higher torque means faster acceleration from a standstill. EVs deliver full torque instantly (unlike petrol engines that need to rev up), which is why electric vehicles feel so responsive in city traffic.',
      'example'  => 'The Tata Nexon EV produces 245 Nm of torque instantly from 0 rpm, making it feel significantly punchier off the line than a similarly-priced petrol SUV.',
      'related'  => ['Motor Power', 'Peak Power'],
    ],
  ],

  // ── N ──────────────────────────────────────────────────────────
  'N' => [
    [
      'id'       => 'nmc-battery',
      'term'     => 'NMC Battery (Nickel Manganese Cobalt)',
      'def'      => 'NMC batteries use a combination of nickel, manganese, and cobalt in the cathode. They offer higher energy density than LFP batteries (more range per kg), but can be more sensitive to heat and deep discharge. Most premium EVs and performance-oriented electric scooters use NMC chemistry.',
      'example'  => 'Ather 450X, Ola S1 Pro, and most European EVs use NMC batteries, enabling more range in a lighter and smaller pack.',
      'related'  => ['LFP Battery', 'Battery Degradation', 'SOH'],
    ],
    [
      'id'       => 'noc',
      'term'     => 'NOC (No Objection Certificate)',
      'def'      => 'An NOC is a document issued by an RTO (Regional Transport Office) that certifies there are no pending dues, loans, or legal issues against a vehicle. It\'s required when transferring ownership of a vehicle from one state to another or when selling a used EV.',
      'example'  => 'If you buy a second-hand Ather 450 that was originally registered in Tamil Nadu but you live in Maharashtra, the seller must provide an NOC from the Tamil Nadu RTO.',
      'related'  => ['On-road Price'],
    ],
  ],

  // ── O ──────────────────────────────────────────────────────────
  'O' => [
    [
      'id'       => 'on-road-price',
      'term'     => 'On-road Price',
      'def'      => 'The on-road price is the total amount you pay to drive an EV home. It includes the ex-showroom price plus GST, road tax (varies by state), registration charges, insurance, handling charges, and optional accessories. The on-road price can be 8–18% higher than the ex-showroom price depending on your state.',
      'example'  => 'A scooter with an ex-showroom price of ₹1,00,000 could cost ₹1,10,000–₹1,16,000 on-road in Delhi after adding registration, insurance, and other charges.',
      'related'  => ['FAME II', 'Subsidy', 'TCO'],
    ],
    [
      'id'       => 'ota-update',
      'term'     => 'OTA Update (Over-the-Air Update)',
      'def'      => 'An OTA update allows an EV manufacturer to wirelessly update the vehicle\'s software — including battery management, performance settings, features, and UI — without requiring a visit to a service centre. This can improve range, add features, or fix bugs months after purchase.',
      'example'  => 'Ather Energy pushes OTA updates to the 450X that have improved acceleration, added features like party mode, and even increased range through software optimisation.',
      'related'  => ['Connected Features', 'BMS'],
    ],
  ],

  // ── P ──────────────────────────────────────────────────────────
  'P' => [
    [
      'id'       => 'peak-power',
      'term'     => 'Peak Power',
      'def'      => 'Peak power is the maximum power output an electric motor can deliver for a short burst — typically measured in kW or bhp. This determines top speed and rapid acceleration ability. Continuous (rated) power is lower and reflects sustained performance.',
      'example'  => 'The Ola S1 Pro\'s motor has a peak power of 8.5 kW (11.4 bhp), enabling it to reach 90 km/h — though it can only sustain peak output for a few seconds.',
      'related'  => ['Motor Torque', 'Motor Power'],
    ],
  ],

  // ── R ──────────────────────────────────────────────────────────
  'R' => [
    [
      'id'       => 'regen-braking',
      'term'     => 'Regenerative Braking (Regen)',
      'def'      => 'Regenerative braking converts the kinetic energy of a decelerating EV back into electricity and stores it in the battery. When you lift off the accelerator or apply the brake, the motor acts as a generator, extending range by 5–20% in city conditions compared to normal friction braking.',
      'example'  => 'Riding an Ather 450X in heavy Bengaluru traffic with strong regen mode enabled can extend the real-world range by 15–20 km compared to riding without regen.',
      'related'  => ['Real-world Range', 'Motor Torque'],
    ],
    [
      'id'       => 'real-world-range',
      'term'     => 'Real-world Range',
      'def'      => 'Real-world range is the actual distance an EV travels on a full charge under typical riding/driving conditions — accounting for traffic, hills, air conditioning, rider weight, and weather. It is almost always lower than the manufacturer\'s claimed (ARAI) range.',
      'example'  => 'Charj.in tests show that the Ola S1 Pro\'s claimed 195 km range translates to approximately 130–145 km in real Bengaluru city conditions.',
      'related'  => ['Claimed Range', 'Regen Braking', 'SOC'],
    ],
  ],

  // ── S ──────────────────────────────────────────────────────────
  'S' => [
    [
      'id'       => 'soc',
      'term'     => 'SOC (State of Charge)',
      'def'      => 'State of Charge is the current charge level of an EV battery, expressed as a percentage (0% = fully depleted, 100% = fully charged). It\'s what your vehicle\'s display shows as the battery indicator. SOC is managed by the BMS to keep the battery in a healthy operating range.',
      'example'  => 'Charging your EV only to 80% SOC daily (instead of 100%) and not letting it drop below 15% can significantly extend the battery\'s long-term health.',
      'related'  => ['SOH', 'BMS', 'Battery Degradation'],
    ],
    [
      'id'       => 'soh',
      'term'     => 'SOH (State of Health)',
      'def'      => 'State of Health measures the overall health and remaining capacity of an EV battery compared to its original specification when new. A battery with 90% SOH can store 90% of what it could when new. SOH decreases slowly with age and charge cycles.',
      'example'  => 'After 3 years of daily charging, an EV battery might show 92% SOH — meaning it delivers 92% of its original range, which is excellent retention.',
      'related'  => ['SOC', 'Battery Degradation', 'BMS'],
    ],
    [
      'id'       => 'smart-charging',
      'term'     => 'Smart Charging',
      'def'      => 'Smart charging uses software to control when and at what rate an EV charges, based on electricity tariffs, grid demand, or scheduled departure time. It allows EV owners to automatically charge during off-peak hours when electricity is cheapest and the grid is under least stress.',
      'example'  => 'A smart home charger can be set to start charging at 11 PM when night tariffs drop to ₹5/kWh instead of ₹8/kWh — saving ₹60–100 per full charge.',
      'related'  => ['Time-of-use Tariff', 'DISCOM', 'V2G'],
    ],
    [
      'id'       => 'subsidy',
      'term'     => 'Subsidy (EV Subsidy)',
      'def'      => 'An EV subsidy is a government incentive that reduces the purchase price of an electric vehicle. In India, subsidies come from the central government (FAME II) and state governments. They are deducted upfront at the time of purchase, directly reducing the price you pay.',
      'example'  => 'In Gujarat, an electric scooter buyer can benefit from both FAME II central subsidy (up to ₹40,000) and a state subsidy of ₹20,000, reducing a ₹1 lakh scooter to just ₹40,000.',
      'related'  => ['FAME II', 'On-road Price', 'TCO'],
    ],
  ],

  // ── T ──────────────────────────────────────────────────────────
  'T' => [
    [
      'id'       => 'tco',
      'term'     => 'TCO (Total Cost of Ownership)',
      'def'      => 'Total Cost of Ownership accounts for every expense of owning a vehicle over its lifetime — purchase price, fuel/electricity costs, insurance, maintenance, depreciation, and resale value. EVs typically have a higher upfront cost but lower TCO over 5 years compared to petrol vehicles due to lower running costs.',
      'example'  => 'A Tata Nexon EV may cost ₹3 lakh more upfront than a petrol SUV, but saves ₹60,000–₹80,000/year in fuel — making it cheaper over a 5-year TCO horizon.',
      'related'  => ['FAME II', 'Subsidy', 'kWh'],
    ],
    [
      'id'       => 'time-of-use-tariff',
      'term'     => 'Time-of-use (ToU) Tariff',
      'def'      => 'A time-of-use tariff charges different electricity rates at different times of day. Off-peak hours (typically 10 PM–6 AM) have lower rates, while peak hours (evenings) have higher rates. EV owners who charge overnight on ToU tariffs can dramatically reduce their per-km fuel cost.',
      'example'  => 'Under BESCOM\'s EV tariff in Bengaluru, charging at night costs ₹4/kWh instead of the standard ₹7–8/kWh, cutting monthly charging costs nearly in half.',
      'related'  => ['Smart Charging', 'DISCOM', 'TCO'],
    ],
    [
      'id'       => 'type2-connector',
      'term'     => 'Type 2 Connector (IEC 62196)',
      'def'      => 'The Type 2 connector (also called Mennekes) is the standard AC charging connector used for EVs in India and Europe. It supports single-phase (up to 7.4 kW) and three-phase (up to 22 kW) AC charging and is found on most home and public AC chargers.',
      'example'  => 'All Tata, MG, Hyundai, and Kia EVs in India use a Type 2 AC inlet for home charging and a CCS2 combo port for DC fast charging.',
      'related'  => ['CCS2', 'AC Charging Time', 'DC Fast Charging'],
    ],
  ],

  // ── V ──────────────────────────────────────────────────────────
  'V' => [
    [
      'id'       => 'v2g',
      'term'     => 'V2G (Vehicle-to-Grid)',
      'def'      => 'V2G technology allows an EV to discharge electricity back to the power grid when demand is high, effectively turning the vehicle into a mobile energy storage unit. The EV owner can earn credits or reduce electricity bills, while the grid benefits from distributed storage.',
      'example'  => 'A V2G-enabled EV charged cheaply at night could sell electricity back to the grid during peak evening hours at ₹12/kWh — potentially earning the owner ₹100–200 per cycle.',
      'related'  => ['V2H', 'Smart Charging', 'kWh'],
    ],
    [
      'id'       => 'v2h',
      'term'     => 'V2H (Vehicle-to-Home)',
      'def'      => 'V2H allows an EV to power household appliances directly from its battery during a power outage or to shift energy use from expensive peak hours to off-peak charged electricity. It\'s essentially using your EV as a home backup generator.',
      'example'  => 'During a power cut, a V2H-capable EV with a 30 kWh battery could power a typical Indian home\'s essential loads (lights, fans, refrigerator) for 12–15 hours.',
      'related'  => ['V2G', 'Smart Charging'],
    ],
  ],

  // ── W ──────────────────────────────────────────────────────────
  'W' => [
    [
      'id'       => 'warranty',
      'term'     => 'Warranty (EV Warranty)',
      'def'      => 'EV warranties cover two main areas: the vehicle warranty (covering mechanical and electrical components for 2–5 years) and the battery warranty (typically 8 years or 1.6 lakh km in India). The battery warranty usually guarantees a minimum 70% capacity retention over the warranty period.',
      'example'  => 'Tata Motors offers a 3-year vehicle warranty and an 8-year/1.6 lakh km battery warranty on the Nexon EV — among the most comprehensive in India.',
      'related'  => ['Battery Degradation', 'SOH', 'TCO'],
    ],
  ],

  // ── Z ──────────────────────────────────────────────────────────
  'Z' => [
    [
      'id'       => 'zero-emission-vehicle',
      'term'     => 'Zero Emission Vehicle (ZEV)',
      'def'      => 'A Zero Emission Vehicle produces no direct tailpipe emissions during operation. Battery electric vehicles (BEVs) are classified as ZEVs because they run entirely on electricity and emit no exhaust gases. Note: the electricity generation upstream may produce emissions depending on the grid mix.',
      'example'  => 'India\'s coal-heavy grid means an EV today has a lifecycle carbon footprint roughly 30–40% lower than a petrol vehicle — and this improves as the grid gets greener.',
      'related'  => ['TCO', 'FAME II'],
    ],
  ],

];

// Build letter index (only letters that have terms)
$availableLetters = array_keys($terms);
$alphabet = range('A', 'Z');
?>

<div class="min-h-screen bg-slate-50">

  <!-- ════════════════════════════════════════════════════════════
       HEADER
  ═════════════════════════════════════════════════════════════ -->
  <div class="bg-charj-navy text-white py-12 px-4">
    <div class="max-w-4xl mx-auto text-center">
      <nav class="text-sm mb-4 text-slate-400 flex items-center justify-center gap-1.5" aria-label="Breadcrumb">
        <a href="<?= base_url('/') ?>" class="hover:text-charj-green transition-colors">Home</a>
        <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">EV Glossary</span>
      </nav>

      <div class="w-14 h-14 bg-charj-green rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>

      <h1 class="text-3xl md:text-5xl font-extrabold mb-3 leading-tight">
        EV Glossary
      </h1>
      <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
        From <strong class="text-white">kWh</strong> to <strong class="text-white">FAME II</strong> — understand every electric vehicle term before you buy.
      </p>

      <!-- Stats -->
      <div class="flex flex-wrap items-center justify-center gap-6 mt-8">
        <div class="text-center">
          <div class="text-3xl font-extrabold text-charj-green"><?= array_sum(array_map('count', $terms)) ?>+</div>
          <div class="text-xs text-slate-400 mt-0.5">Terms Explained</div>
        </div>
        <div class="w-px h-8 bg-white/10 hidden sm:block"></div>
        <div class="text-center">
          <div class="text-3xl font-extrabold text-charj-green">Plain</div>
          <div class="text-xs text-slate-400 mt-0.5">Simple Language</div>
        </div>
        <div class="w-px h-8 bg-white/10 hidden sm:block"></div>
        <div class="text-center">
          <div class="text-3xl font-extrabold text-charj-green">India</div>
          <div class="text-xs text-slate-400 mt-0.5">Focused Examples</div>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 py-8">

    <!-- ════════════════════════════════════════════════════════
         A–Z NAVIGATION BAR
    ═════════════════════════════════════════════════════════ -->
    <nav class="bg-white rounded-2xl shadow-sm border border-slate-100 px-4 py-4 mb-8 sticky top-20 z-30" aria-label="A-Z glossary navigation">
      <div class="flex flex-wrap justify-center gap-1">
        <?php foreach ($alphabet as $letter): ?>
        <?php if (in_array($letter, $availableLetters)): ?>
        <a href="#letter-<?= $letter ?>"
           class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-bold text-charj-navy hover:bg-charj-green hover:text-white transition-all duration-150 border border-slate-200 hover:border-charj-green shadow-sm">
          <?= $letter ?>
        </a>
        <?php else: ?>
        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium text-slate-300 cursor-not-allowed">
          <?= $letter ?>
        </span>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </nav>

    <!-- ════════════════════════════════════════════════════════
         GLOSSARY TERMS
    ═════════════════════════════════════════════════════════ -->
    <div class="space-y-10">

      <?php foreach ($terms as $letter => $letterTerms): ?>

      <!-- Letter section -->
      <section id="letter-<?= $letter ?>" aria-labelledby="heading-<?= $letter ?>">

        <!-- Letter heading -->
        <div class="flex items-center gap-4 mb-5">
          <div class="w-12 h-12 bg-charj-navy rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
            <span class="text-2xl font-extrabold text-charj-green"><?= $letter ?></span>
          </div>
          <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <!-- Terms in this letter -->
        <div class="space-y-4">
          <?php foreach ($letterTerms as $term): ?>
          <article
            id="<?= esc($term['id']) ?>"
            class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-charj-green/30 transition-all duration-200 overflow-hidden group"
            itemscope itemtype="https://schema.org/DefinedTerm"
          >
            <!-- Term header -->
            <div class="flex items-start gap-3 px-5 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100">
              <div class="w-8 h-8 bg-charj-green/10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:bg-charj-green/20 transition-colors">
                <svg class="w-4 h-4 text-charj-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <h3 id="heading-<?= esc($term['id']) ?>"
                    class="text-base md:text-lg font-extrabold text-charj-navy leading-snug"
                    itemprop="name">
                  <a href="#<?= esc($term['id']) ?>" class="hover:text-charj-green transition-colors">
                    <?= esc($term['term']) ?>
                  </a>
                </h3>
              </div>
              <!-- Anchor copy link -->
              <a href="#<?= esc($term['id']) ?>"
                 class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-charj-green hover:bg-slate-100 transition-colors opacity-0 group-hover:opacity-100"
                 aria-label="Link to <?= esc($term['term']) ?>">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
              </a>
            </div>

            <!-- Term body -->
            <div class="px-5 py-4 space-y-3">
              <!-- Definition -->
              <p class="text-sm text-slate-700 leading-relaxed" itemprop="description">
                <?= esc($term['def']) ?>
              </p>

              <!-- Example -->
              <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                <div class="flex items-start gap-2">
                  <span class="text-amber-500 text-base leading-none mt-0.5 flex-shrink-0" aria-hidden="true">💡</span>
                  <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-amber-600 mb-1">Example</p>
                    <p class="text-sm text-amber-900 leading-relaxed"><?= esc($term['example']) ?></p>
                  </div>
                </div>
              </div>

              <!-- Related terms -->
              <?php if (!empty($term['related'])): ?>
              <div class="flex flex-wrap items-center gap-2 pt-1">
                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Related:</span>
                <?php foreach ($term['related'] as $rel):
                  // Try to find the ID of the related term
                  $relId = strtolower(str_replace([' ', '(', ')', '/'], ['-', '', '', '-'], $rel));
                  $relId = preg_replace('/-+/', '-', $relId);
                  $relId = trim($relId, '-');
                ?>
                <a href="#<?= esc($relId) ?>"
                   class="inline-flex items-center gap-1 bg-slate-100 hover:bg-charj-green hover:text-white text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-lg transition-colors border border-slate-200 hover:border-charj-green">
                  <?= esc($rel) ?>
                </a>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </section>

      <?php endforeach; ?>

    </div>

    <!-- ════════════════════════════════════════════════════════
         BOTTOM CTA
    ═════════════════════════════════════════════════════════ -->
    <div class="mt-12 bg-gradient-to-br from-charj-navy to-charj-navy-light rounded-2xl p-8 text-center text-white">
      <div class="text-4xl mb-3">⚡</div>
      <h2 class="text-2xl font-extrabold mb-2">Ready to find your perfect EV?</h2>
      <p class="text-slate-300 text-sm mb-6 max-w-md mx-auto leading-relaxed">
        Now that you understand the terms, use Charj.in's tools to compare real-world range, calculate savings, and get the best price.
      </p>
      <div class="flex flex-wrap items-center justify-center gap-3">
        <a href="<?= base_url('vehicles') ?>"
           class="inline-flex items-center gap-2 bg-charj-green hover:bg-charj-green-dark text-white px-6 py-3 rounded-xl font-bold transition-colors shadow-md">
          Browse All EVs →
        </a>
        <a href="<?= base_url('compare') ?>"
           class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
          ⚖️ Compare EVs
        </a>
        <a href="<?= base_url('find-my-ev') ?>"
           class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
          🎯 Find My EV
        </a>
      </div>
    </div>

    <!-- Back to top -->
    <div class="text-center mt-8">
      <a href="#" class="inline-flex items-center gap-2 text-sm text-charj-green hover:text-green-700 font-semibold transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        Back to top
      </a>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
