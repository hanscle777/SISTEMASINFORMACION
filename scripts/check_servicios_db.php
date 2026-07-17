<?php
$db = new SQLite3(__DIR__ . '/../database/database.sqlite');
$res = $db->query('SELECT id, nombre, activo FROM servicios');
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    echo $row['id'] . ': ' . $row['nombre'] . ' active=' . $row['activo'] . PHP_EOL;
}
