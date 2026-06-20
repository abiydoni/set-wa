<?php
$envPath = __DIR__ . '/.env';
$setwaHost = 'localhost';
$setwaDb   = 'appsbeem_setwa';
$setwaUser = 'root';
$setwaPass = '';

if (file_exists($envPath)) {
    $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim(trim($val), "\"'");
            
            if ($key === 'database.default.hostname') $setwaHost = $val;
            if ($key === 'database.default.database') $setwaDb = $val;
            if ($key === 'database.default.username') $setwaUser = $val;
            if ($key === 'database.default.password') $setwaPass = $val;
        }
    }
}

try {
    $pdo = new PDO("mysql:host=$setwaHost;dbname=$setwaDb;charset=utf8mb4", $setwaUser, $setwaPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Add column use_default_db if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE tb_applications ADD COLUMN use_default_db TINYINT(1) DEFAULT 0 AFTER appsbee_api_key");
        echo "Column use_default_db added successfully!\n";
    } catch (Exception $e) {
        echo "Column might already exist: " . $e->getMessage() . "\n";
    }

    // Update Application 1 to use_default_db = 1 since it's using the global settings
    $pdo->exec("UPDATE tb_applications SET use_default_db = 1 WHERE id = 1");
    echo "Application 1 set to use global settings.\n";

} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
