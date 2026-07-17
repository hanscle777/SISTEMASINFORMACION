<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
foreach ($db->query('SELECT id, nombre, activo FROM servicios') as $row) {
    echo $row['id'] . ': ' . $row['nombre'] . ' active=' . $row['activo'] . PHP_EOL;
}
