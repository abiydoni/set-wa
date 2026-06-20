<?php
$host = 'localhost';
$user = 'appsbeem_admin';
$pass = 'A7by777__';
$db = 'appsbeem_setwa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("ALTER TABLE tb_app_tasks ADD COLUMN body_message TEXT DEFAULT NULL AFTER message_template");

    echo "Added body_message to tb_app_tasks.\n";
} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
