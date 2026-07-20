<?php
/**
 * One-time brand seeder for Charj.in
 * Run once via browser: http://localhost/Charj/public/seed_brands.php
 * This file deletes itself after running.
 */

$pdo = new PDO('mysql:host=localhost;dbname=u504377054_charj;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$brands = [
    ['name'=>'Ola Electric','slug'=>'ola-electric','country'=>'India','founded_year'=>2017,'headquarters'=>'Bangalore','website'=>'https://olaelectric.com','status'=>'active','featured'=>1],
    ['name'=>'Ather Energy','slug'=>'ather-energy','country'=>'India','founded_year'=>2013,'headquarters'=>'Bangalore','website'=>'https://atherenergy.com','status'=>'active','featured'=>1],
    ['name'=>'TVS Motor','slug'=>'tvs-motor','country'=>'India','founded_year'=>1978,'headquarters'=>'Chennai','website'=>'https://tvsmotor.com','status'=>'active','featured'=>1],
    ['name'=>'Tata Motors','slug'=>'tata-motors','country'=>'India','founded_year'=>1945,'headquarters'=>'Mumbai','website'=>'https://tatamotors.com','status'=>'active','featured'=>1],
    ['name'=>'MG Motor','slug'=>'mg-motor','country'=>'UK','founded_year'=>1924,'headquarters'=>'Gurugram','website'=>'https://mgmotor.co.in','status'=>'active','featured'=>1],
    ['name'=>'Bajaj Auto','slug'=>'bajaj-auto','country'=>'India','founded_year'=>1945,'headquarters'=>'Pune','website'=>'https://bajajauto.com','status'=>'active','featured'=>0],
    ['name'=>'Hero Electric','slug'=>'hero-electric','country'=>'India','founded_year'=>2007,'headquarters'=>'New Delhi','website'=>'https://heroelectric.in','status'=>'active','featured'=>0],
    ['name'=>'Revolt Motors','slug'=>'revolt-motors','country'=>'India','founded_year'=>2019,'headquarters'=>'Gurugram','website'=>'https://revoltmotors.com','status'=>'active','featured'=>0],
    ['name'=>'Hyundai','slug'=>'hyundai','country'=>'South Korea','founded_year'=>1967,'headquarters'=>'Chennai','website'=>'https://hyundai.com/in','status'=>'active','featured'=>1],
    ['name'=>'Mahindra','slug'=>'mahindra','country'=>'India','founded_year'=>1945,'headquarters'=>'Mumbai','website'=>'https://mahindra.com','status'=>'active','featured'=>1],
    ['name'=>'Ampere','slug'=>'ampere','country'=>'India','founded_year'=>2008,'headquarters'=>'Coimbatore','website'=>'https://ampere.live','status'=>'active','featured'=>0],
    ['name'=>'Pure EV','slug'=>'pure-ev','country'=>'India','founded_year'=>2015,'headquarters'=>'Hyderabad','website'=>'https://pureev.in','status'=>'active','featured'=>0],
];

$inserted = 0;
$skipped  = 0;

$stmt = $pdo->prepare("
    INSERT IGNORE INTO brands (name, slug, country, founded_year, headquarters, website, status, featured)
    VALUES (:name, :slug, :country, :founded_year, :headquarters, :website, :status, :featured)
");

foreach ($brands as $brand) {
    $rows = $stmt->execute($brand) ? $stmt->rowCount() : 0;
    if ($rows > 0) {
        $inserted++;
        echo "<p style='color:green'>Inserted: {$brand['name']}</p>\n";
    } else {
        $skipped++;
        echo "<p style='color:gray'>Skipped (already exists): {$brand['name']}</p>\n";
    }
}

echo "<hr><p><strong>Done.</strong> Inserted: {$inserted}, Skipped: {$skipped}</p>\n";

// Self-delete after running
unlink(__FILE__);
echo "<p style='color:orange'>This file has been deleted.</p>\n";
