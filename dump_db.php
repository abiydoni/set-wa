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
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in $setwaDb:\n";
    foreach ($tables as $t) {
        echo "- $t\n";
        $stmt2 = $pdo->query("DESCRIBE `$t`");
        $cols = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        echo "  " . implode(", ", $cols) . "\n";
    }
} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
