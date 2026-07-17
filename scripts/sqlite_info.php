<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "DB file missing: $dbPath\n";
    exit(1);
}
$db = new PDO('sqlite:' . $dbPath);
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
echo "tables=" . implode(',', $tables) . "\n";
if (in_array('servicios', $tables, true)) {
    $count = $db->query('SELECT COUNT(*) FROM servicios')->fetchColumn();
    echo "servicios_count=$count\n";
} else {
    echo "servicios table missing\n";
}
