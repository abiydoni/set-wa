<?php
$host = 'localhost';
$user = 'appsbeem_admin';
$pass = 'A7by777__';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS appsbeem_setwa");
    $pdo->exec("USE appsbeem_setwa");

    // Create tb_settings
    $pdo->exec("CREATE TABLE IF NOT EXISTS tb_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT,
        description VARCHAR(255)
    )");

    // Seed initial settings
    $settings = [
        ['appsbeeUrl', 'https://wa-ab.appsbee.my.id/api/send-message', 'URL Endpoint Gateway WA'],
        ['appsbeeApiKey', 'wa-69aa3dbf930020c93f34b83add6374e8', 'API Key Gateway WA'],
        ['groupId', '', 'Group ID / Target WA Number Default'],
        ['manualMessage', 'Pesan percobaan', 'Template Pesan Manual']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO tb_settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
    foreach ($settings as $row) {
        $stmt->execute($row);
    }

    // Create tb_applications
    $pdo->exec("CREATE TABLE IF NOT EXISTS tb_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        folder_name VARCHAR(100) NOT NULL UNIQUE,
        db_host VARCHAR(100) DEFAULT 'localhost',
        db_user VARCHAR(100) NOT NULL,
        db_pass VARCHAR(100) DEFAULT '',
        db_name VARCHAR(100) NOT NULL,
        target_table VARCHAR(100),
        custom_query TEXT,
        message_template TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    echo "Database & tables created successfully.\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
