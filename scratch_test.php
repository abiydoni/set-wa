<?php
$envPath = __DIR__ . '/../.env';
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
    $pdoMaster = new PDO("mysql:host=$setwaHost;dbname=$setwaDb;charset=utf8mb4", $setwaUser, $setwaPass);
    $stmt = $pdoMaster->query("SELECT php_script FROM tb_app_tasks WHERE id = 1");
    echo "--- SCRIPT MULAI ---\n";
    echo $stmt->fetchColumn();
    echo "\n--- SCRIPT SELESAI ---\n";
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
