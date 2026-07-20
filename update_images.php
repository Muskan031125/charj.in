<?php
// Set up the environment
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/app/');

require SYSTEMPATH . 'autoload.php';

// Initialize CodeIgniter
$app = require APPPATH . 'Config/bootstrap.php';

// Get database
$db = \Config\Database::connect();

// Update vehicle images with Pexels URLs
$updates = [
    'tata-nexon-ev' => 'https://images.pexels.com/photos/11554746/pexels-photo-11554746.jpeg?auto=compress&cs=tinysrgb&w=600',
    'ather-450x' => 'https://images.pexels.com/photos/34800931/pexels-photo-34800931.jpeg?auto=compress&cs=tinysrgb&w=600',
    'mg-zs-ev' => 'https://images.pexels.com/photos/8827006/pexels-photo-8827006.jpeg?auto=compress&cs=tinysrgb&w=600'
];

echo "Updating vehicle images...\n";

foreach ($updates as $slug => $url) {
    $db->table('vehicles')
        ->where('slug', $slug)
        ->update(['image_url' => $url]);
    echo "✅ Updated $slug\n";
}

// Verify
$result = $db->table('vehicles')
    ->whereIn('slug', array_keys($updates))
    ->select('slug, image_url')
    ->get()
    ->getResultArray();

echo "\nDatabase verification:\n";
foreach ($result as $row) {
    echo $row['slug'] . ": " . substr($row['image_url'], 0, 80) . "...\n";
}

echo "\n✅ All vehicle images updated successfully!\n";
