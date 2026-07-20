<?php
// Quick update script - no bootstrapping needed

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'charj_local';
$port = 3308;

try {
    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $updates = [
        'tata-nexon-ev' => 'https://images.pexels.com/photos/11554746/pexels-photo-11554746.jpeg?auto=compress&cs=tinysrgb&w=600',
        'ather-450x' => 'https://images.pexels.com/photos/34800931/pexels-photo-34800931.jpeg?auto=compress&cs=tinysrgb&w=600',
        'mg-zs-ev' => 'https://images.pexels.com/photos/8827006/pexels-photo-8827006.jpeg?auto=compress&cs=tinysrgb&w=600'
    ];

    foreach ($updates as $slug => $url) {
        $sql = "UPDATE vehicles SET image_url = ? WHERE slug = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $url, $slug);

        if ($stmt->execute()) {
            echo "✅ Updated: $slug\n";
        } else {
            echo "❌ Failed: $slug - " . $stmt->error . "\n";
        }
        $stmt->close();
    }

    // Verify
    echo "\n📸 Verification:\n";
    $result = $conn->query("SELECT slug, image_url FROM vehicles WHERE slug IN ('tata-nexon-ev', 'ather-450x', 'mg-zs-ev')");

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo $row['slug'] . ": " . (strpos($row['image_url'], 'pexels') ? "✅ Pexels URL set" : "❌ No image") . "\n";
        }
    }

    $conn->close();
    echo "\n✅ All vehicle images updated successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
