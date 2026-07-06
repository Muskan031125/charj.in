<?php
/**
 * Charj.in — DB Exporter
 * Generates a complete SQL file ready to import on Hostinger.
 * Run: http://localhost/Charj/public/export_db.php
 */
$host = 'localhost';
$db   = 'u504377054_charj';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql  = "-- Charj.in Database Export\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Import this on Hostinger phpMyAdmin → u504377054_charj\n\n";
$sql .= "SET NAMES utf8mb4;\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    // DROP + CREATE
    $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
    $createSql = $create['Create Table'] ?? $create[array_key_last($create)];

    $sql .= "-- --------------------------------------------------------\n";
    $sql .= "-- Table: `$table`\n";
    $sql .= "-- --------------------------------------------------------\n\n";
    $sql .= "DROP TABLE IF EXISTS `$table`;\n";
    $sql .= $createSql . ";\n\n";

    // DATA
    $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($rows)) {
        $cols = '`' . implode('`, `', array_keys($rows[0])) . '`';
        $sql .= "INSERT INTO `$table` ($cols) VALUES\n";
        $vals = [];
        foreach ($rows as $row) {
            $escaped = array_map(function($v) use ($pdo) {
                return $v === null ? 'NULL' : $pdo->quote($v);
            }, array_values($row));
            $vals[] = '(' . implode(', ', $escaped) . ')';
        }
        $sql .= implode(",\n", $vals) . ";\n\n";
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

// Stream as downloadable file
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="charj_db_' . date('Ymd_His') . '.sql"');
header('Content-Length: ' . strlen($sql));
echo $sql;
