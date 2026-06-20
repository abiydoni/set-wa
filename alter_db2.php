<?php
$host = 'localhost';
$user = 'appsbeem_admin';
$pass = 'A7by777__';
$db = 'appsbeem_setwa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("ALTER TABLE tb_app_tasks DROP COLUMN db_host, DROP COLUMN db_user, DROP COLUMN db_pass, DROP COLUMN db_name");

    echo "Dropped DB columns from tb_app_tasks.\n";
} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
