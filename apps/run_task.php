<?php
/**
 * MASTER EXECUTOR UNTUK SEMUA TASK
 * 
 * Penggunaan via CLI:
 * php apps/run_task.php [task_id]
 */

$taskId = null;

if (isset($_SERVER['argv']) && isset($_SERVER['argv'][1])) {
    $taskId = $_SERVER['argv'][1];
} elseif (isset($argv) && isset($argv[1])) {
    $taskId = $argv[1];
} elseif (isset($_GET['id'])) {
    $taskId = $_GET['id'];
}

if (!$taskId) {
    die("Error: Harus dijalankan via CLI (php run_task.php [ID]) atau berikan parameter ?id=[ID] via URL browser.\n");
}

// 1. Koneksi ke Database Master (Dashboard) dengan membaca .env CodeIgniter
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
    $pdoMaster->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error Master DB: " . $e->getMessage() . "\n");
}

// 2. Ambil Settingan Global (API Endpoint)
$stmt = $pdoMaster->query("SELECT setting_value FROM tb_settings WHERE setting_key='appsbeeUrl'");
$appsbeeUrl = $stmt->fetchColumn();

if (empty($appsbeeUrl)) {
    die("Error: appsbeeUrl belum dikonfigurasi di pengaturan global.\n");
}

// 3. Ambil Detail Task & Aplikasi
$stmtTask = $pdoMaster->prepare("
    SELECT t.*, a.appsbee_api_key, a.db_host, a.db_name, a.db_user, a.db_pass 
    FROM tb_app_tasks t
    JOIN tb_applications a ON t.app_id = a.id
    WHERE t.id = :id
");
$stmtTask->execute([':id' => $taskId]);
$task = $stmtTask->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    die("Error: Task dengan ID $taskId tidak ditemukan.\n");
}

echo "=== Menjalankan Task ID $taskId: {$task['task_name']} ===\n";

$dbHost = $task['db_host'];
$dbName = $task['db_name'];
$dbUser = $task['db_user'];
$dbPass = $task['db_pass'];

$message = "";

if ($task['task_type'] === 'php') {
    // ---------------------------------------------------------
    // MODE 1: CUSTOM PHP SCRIPT ENGINE
    // ---------------------------------------------------------
    $phpScript = trim($task['php_script'] ?? '');
    if (empty($phpScript)) {
        die("Error: PHP Script kosong.\n");
    }
    
    echo "Mode: CUSTOM PHP SCRIPT\n";
    
    // 4. Koneksi ke Target Database (dari pengaturan Aplikasi)
    // Variabel $pdoTarget otomatis tersedia dan bisa digunakan di dalam script PHP kustom
    try {
        $pdoTarget = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass);
        $pdoTarget->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Alias agar kompatibel dengan script lama yang memanggil $pdo
        $pdo = $pdoTarget; 
        echo "Koneksi ke target database ({$dbName}) berhasil. \$pdo siap digunakan.\n";
    } catch (PDOException $e) {
        die("Error Target DB: " . $e->getMessage() . "\n");
    }

    // Buat file temporary
    $tmpFile = tempnam(sys_get_temp_dir(), 'task_') . '.php';
    
    // Pastikan diawali tag php jika belum ada
    if (strpos($phpScript, '<?php') === false) {
        $phpScript = "<?php\n" . $phpScript;
    }
    
    file_put_contents($tmpFile, $phpScript);
    
    $message = '';
    
    // Eksekusi dan tangkap output
    ob_start();
    try {
        include $tmpFile;
    } catch (Throwable $e) {
        echo "Error mengeksekusi PHP Script: " . $e->getMessage() . "\n";
    }
    $output = ob_get_clean();
    
    // Hapus file temporary
    @unlink($tmpFile);
    
    // Script kustom biasanya mengisi variabel $pesan atau $message, atau menge-print langsung.
    if (!empty($pesan)) {
        $message = $pesan;
    } elseif (empty($message) && !empty(trim($output))) {
        $message = trim($output);
    }
    
    if (empty($message)) {
        die("✅ Eksekusi selesai. Tidak ada pesan yang perlu dikirim (variabel \$pesan kosong/tidak ada data).\n");
    }
    
} else {
    // ---------------------------------------------------------
    // MODE 2: SQL QUERY
    // ---------------------------------------------------------
    echo "Mode: SQL QUERY\n";
    
    // 4. Koneksi ke Target Database (dari pengaturan Aplikasi)
    try {
        $pdoTarget = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass);
        $pdoTarget->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Koneksi ke target database ({$dbName}) berhasil.\n";
    } catch (PDOException $e) {
        die("Error Target DB: " . $e->getMessage() . "\n");
    }

    // 5. Jalankan Query Target
    $query = $task['custom_query'];
    if (empty($query)) {
        die("Error: Query SQL kosong.\n");
    }

    try {
        $res = $pdoTarget->query($query);
        $results = $res->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die('Error saat menjalankan Query: ' . $e->getMessage() . "\n");
    }

    if (count($results) == 0) {
        die("✅ Tidak ada data yang ditemukan dari query. Pesan tidak dikirim.\n");
    }

    // 6. Rangkai Pesan (Header + Data + Body Message + Footer)

    // Fungsi pembantu untuk replace tag global
    if (!function_exists('parseGlobalTags')) {
        function parseGlobalTags($text, $results) {
            if (empty($text)) return '';
            $text = str_replace('{tanggal}', date('d M Y'), $text);
            $text = str_replace('{waktu}', date('H:i'), $text);
            $text = str_replace('{jumlah_data}', count($results), $text);
            return $text;
        }
    }

    $header = parseGlobalTags($task['message_template'] ?? '', $results);

    $body = "";
    foreach ($results as $index => $row) {
        if (!empty($task['body_message'])) {
            // Jika ada template body, replace {nama_kolom} dan {no}
            $rowText = $task['body_message'];
            $rowText = str_replace('{no}', $index + 1, $rowText);
            foreach ($row as $col => $val) {
                $rowText = str_ireplace('{' . $col . '}', $val, $rowText);
            }
            $body .= $rowText . "\n\n";
        } else {
            // Fallback default format jika template body kosong
            $rowTextArr = [];
            foreach($row as $col => $val) {
                $rowTextArr[] = "$col: $val";
            }
            $body .= ($index + 1) . ". " . implode(" | ", $rowTextArr) . "\n";
        }
    }

    $footer = parseGlobalTags($task['message_footer'] ?? '', $results);

    // Gabungkan semua
    $messageParts = [];
    if (!empty(trim($header))) $messageParts[] = trim($header);
    if (!empty(trim($body))) $messageParts[] = trim($body);
    if (!empty(trim($footer))) $messageParts[] = trim($footer);

    $message = implode("\n\n", $messageParts);
}

// 7. Pengiriman (Logic dari core_sender sebelumnya)
echo "Mengirim ke WA ID: {$task['wa_id']}...\n";

$appsbeeData = [
    'sessionId' => 'appsbee',
    'number'    => $task['wa_id'],
    'message'   => $message
];

$chAppsbee = curl_init($appsbeeUrl);
curl_setopt($chAppsbee, CURLOPT_POST, true);
curl_setopt($chAppsbee, CURLOPT_POSTFIELDS, json_encode($appsbeeData));
curl_setopt($chAppsbee, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chAppsbee, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $task['appsbee_api_key']
]);
curl_setopt($chAppsbee, CURLOPT_TIMEOUT, 30);
curl_setopt($chAppsbee, CURLOPT_SSL_VERIFYPEER, false);

$appsbeeResult = curl_exec($chAppsbee);
$appsbeeHttpCode = curl_getinfo($chAppsbee, CURLINFO_HTTP_CODE);
curl_close($chAppsbee);

if ($appsbeeHttpCode == 200) {
    echo "✅ Berhasil dikirim ke WhatsApp!\n";
} else {
    echo "❌ Gagal (HTTP $appsbeeHttpCode)\n";
    echo "Response: $appsbeeResult\n";
}
