<?php
$host = 'localhost';
$user = 'appsbeem_admin';
$pass = 'A7by777__';
$db = 'appsbeem_setwa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("ALTER TABLE tb_applications DROP COLUMN folder_name");

    echo "Dropped folder_name from tb_applications.\n";
} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
