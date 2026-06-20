<?php
$host = 'localhost';
$user = 'appsbeem_admin';
$pass = 'A7by777__';
$db = 'appsbeem_setwa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $settings = [
        ['defaultDbHost', 'localhost', 'Default Database Host (e.g. localhost)'],
        ['defaultDbName', 'appsbeem_', 'Default Database Name Prefix'],
        ['defaultDbUser', 'appsbeem_admin', 'Default Database User'],
        ['defaultDbPass', 'A7by777__', 'Default Database Password']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO tb_settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
    foreach ($settings as $s) {
        $stmt->execute($s);
    }

    echo "Default DB settings seeded.\n";
} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
