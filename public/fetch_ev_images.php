<?php
/**
 * Charj.in — EV Image Fetcher
 * Run: http://localhost/Charj/public/fetch_ev_images.php
 */
set_time_limit(120);
$pdo = new PDO('mysql:host=localhost;dbname=u504377054_charj;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// aeplcdn.com = CarWale India CDN — reliable, no hotlink block
$imageMap = [
    'ather-450x'             => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/106679/ather-450x-right-front-three-quarter.jpeg?isig=0&q=80',
    'ola-s1-pro'             => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/155815/s1-pro-right-front-three-quarter.jpeg?isig=0&q=80',
    'tvs-iqube'              => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/44560/iqube-right-front-three-quarter.jpeg?isig=0&q=80',
    'revolt-rv400'           => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/41427/revolt-rv400-right-front-three-quarter.jpeg?isig=0&q=80',
    'tata-nexon-ev'          => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/141861/nexon-ev-right-front-three-quarter-4.jpeg?isig=0&q=80',
    'mg-zs-ev'               => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/130583/zs-ev-right-front-three-quarter-6.jpeg?isig=0&q=80',
    'mahindra-treo'          => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/43946/treo-right-front-three-quarter.jpeg?isig=0&q=80',
    'piaggio-ape-e-city'     => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/130601/ape-e-city-right-front-three-quarter.jpeg?isig=0&q=80',
    // extras from seed_vehicles (if they were inserted)
    'ola-s1-air'             => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/155813/s1-air-right-front-three-quarter.jpeg?isig=0&q=80',
    'ather-rizta'            => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/189515/rizta-right-front-three-quarter.jpeg?isig=0&q=80',
    'tvs-iqube-s'            => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/44560/iqube-right-front-three-quarter.jpeg?isig=0&q=80',
    'tata-punch-ev'          => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/160071/punch-ev-right-front-three-quarter-3.jpeg?isig=0&q=80',
    'tata-tiago-ev'          => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/150711/tiago-ev-right-front-three-quarter-5.jpeg?isig=0&q=80',
    'mg-windsor-ev'          => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/229847/windsor-ev-right-front-three-quarter.jpeg?isig=0&q=80',
    'hyundai-creta-electric' => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/230453/creta-electric-right-front-three-quarter.jpeg?isig=0&q=80',
    'mahindra-be-6'          => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/231987/be-6-right-front-three-quarter.jpeg?isig=0&q=80',
    'mahindra-xev-9e'        => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/231989/xev-9e-right-front-three-quarter.jpeg?isig=0&q=80',
    'bajaj-chetak-premium'   => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/130613/chetak-right-front-three-quarter-5.jpeg?isig=0&q=80',
    'hero-vida-v1-pro'       => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/142049/vida-v1-right-front-three-quarter-2.jpeg?isig=0&q=80',
    'ampere-nexus'           => 'https://imgd.aeplcdn.com/1920x1080/n/cw/ec/169261/nexus-right-front-three-quarter-3.jpeg?isig=0&q=80',
];

$rows = $pdo->query("SELECT id, slug, name FROM vehicles")->fetchAll(PDO::FETCH_ASSOC);
$update = $pdo->prepare("UPDATE vehicles SET image_url = ? WHERE id = ?");

$results = [];
foreach ($rows as $v) {
    $url = $imageMap[$v['slug']] ?? null;
    if (!$url) {
        $results[] = ['name'=>$v['name'],'status'=>'no_mapping','url'=>'','code'=>0];
        continue;
    }
    // Verify URL responds
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_NOBODY=>true, CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>8, CURLOPT_FOLLOWLOCATION=>true,
        CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120',
    ]);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (in_array($code, [200,301,302])) {
        $update->execute([$url, $v['id']]);
        $results[] = ['name'=>$v['name'],'status'=>'ok','url'=>$url,'code'=>$code];
    } else {
        $results[] = ['name'=>$v['name'],'status'=>'failed','url'=>$url,'code'=>$code];
    }
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>EV Images</title>
<style>
body{font-family:sans-serif;max-width:900px;margin:40px auto;background:#f1f3f4}
.card{background:#fff;border:1px solid #e4e4e7;padding:2rem}
h1{font-size:.75rem;text-transform:uppercase;letter-spacing:.2em;color:#1B1C25;margin:0 0 1.5rem}
table{width:100%;border-collapse:collapse;font-size:.82rem}
th{font-size:.6rem;text-transform:uppercase;letter-spacing:.15em;color:#999;border-bottom:2px solid #e4e4e7;padding:.4rem 0;text-align:left}
td{padding:.5rem .25rem;border-bottom:1px solid #f4f4f5;vertical-align:middle}
.ok{color:#16a34a;font-weight:600}.failed{color:#dc2626}.no_mapping{color:#aaa}
img{width:90px;height:55px;object-fit:cover;background:#f9f9f9}
a{color:#006044;font-weight:600}
</style></head><body>
<div class="card">
<h1>EV Image Fetcher — Charj.in</h1>
<table>
<thead><tr><th>Vehicle</th><th>Result</th><th>Preview</th></tr></thead>
<tbody>
<?php foreach($results as $r): ?>
<tr>
  <td><?=htmlspecialchars($r['name'])?></td>
  <td class="<?=$r['status']?>">
    <?php if($r['status']==='ok'): ?>✅ Saved (HTTP <?=$r['code']?>)
    <?php elseif($r['status']==='failed'): ?>❌ HTTP <?=$r['code']?> — check URL
    <?php else: ?>— No mapping
    <?php endif;?>
  </td>
  <td><?php if($r['url']): ?><img src="<?=htmlspecialchars($r['url'])?>" onerror="this.style.background='#fee2e2'"><?php endif;?></td>
</tr>
<?php endforeach;?>
</tbody></table>
<p style="margin-top:1.5rem;font-size:.82rem">
  <a href="/Charj/public/index.php/vehicles">View Vehicles →</a>
</p>
</div></body></html>
