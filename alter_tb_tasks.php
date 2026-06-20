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
    
    // Add columns
    try {
        $pdo->exec("ALTER TABLE tb_app_tasks CHANGE COLUMN task_type task_type ENUM('sql', 'php') DEFAULT 'sql'");
        $pdo->exec("ALTER TABLE tb_app_tasks CHANGE COLUMN api_url php_script TEXT NULL");
        echo "Columns task_type enum changed and api_url changed to php_script successfully!\n";
    } catch (Exception $e) {
        echo "Alter error: " . $e->getMessage() . "\n";
    }

} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
