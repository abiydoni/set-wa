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
    
    // Cari App ID (Asumsikan ada aplikasi bernama Logistic atau ambil yang pertama)
    $stmt = $pdo->query("SELECT id, name FROM tb_applications LIMIT 1");
    $app = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$app) {
        die("Error: Tidak ada aplikasi di tb_applications. Buat aplikasi dulu.\n");
    }
    
    $appId = $app['id'];
    $waId = '6281234567890'; // Contoh WA ID

    $phpScript = <<<'PHP'
// Script dijalankan oleh Gateway, koneksi DB otomatis tersedia di objek $pdoTarget / $pdo

// Jangan hapus ini jika Anda menggunakan $pdo di kode Anda:
$pdo = $pdoTarget;

date_default_timezone_set('Asia/Jakarta');
$pesan = '';
$hasAlert = false;
$expiredCount = 0;
$warningCount = 0;

$companyName = 'AppsBeem Logistic';
$notifyDays  = 30;

$today      = date('Y-m-d');
$warningEnd = date('Y-m-d', strtotime("+{$notifyDays} days"));

// Fungsi helper untuk escape markdown Telegram/WA
if (!function_exists('escapeMarkdown')) {
    function escapeMarkdown(string $text): string {
        $chars = ['_', '*', '[', ']', '~', '`', '>', '#', '+', '=', '|', '{', '}', '!'];
        foreach ($chars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        $text = preg_replace('/(?<!\d)-(?!\d)/', '\\-', $text);
        return $text;
    }
}

if (!function_exists('format_expired_line')) {
    function format_expired_line(int $no, array $it, bool $isPast): string
    {
        $name   = $it['name'] ?? '-';
        $stock  = (int) ($it['current_stock'] ?? 0);
        $unit   = $it['unit'] ?? '';
        $exp    = $it['expired_date'] ?? '';
        $expFmt = $exp ? date('d/m/Y', strtotime($exp)) : '-';

        $todayTs = strtotime(date('Y-m-d'));
        $expTs   = $exp ? strtotime($exp) : $todayTs;

        if ($isPast && $exp) {
            $dayLabel = ' (' . (int) floor(($todayTs - $expTs) / 86400) . ' hari lalu)';
        } else {
            $dayLabel = ' (' . ($exp ? (int) floor(($expTs - $todayTs) / 86400) : 0) . ' hari lagi)';
        }

        $line  = "{$no}. *" . escapeMarkdown($name) . "*\n";
        $line .= "   Stok: *" . escapeMarkdown((string)$stock) . "* " . escapeMarkdown($unit) . "\n";
        $line .= "   Exp: *" . escapeMarkdown($expFmt) . "*" . escapeMarkdown($dayLabel) . "\n\n";

        return $line;
    }
}

try {
    $sql = "
        SELECT items.id, items.name, items.unit, 
               item_batches.stock AS current_stock,
               item_batches.expired_date
        FROM item_batches
        INNER JOIN items ON items.id = item_batches.item_id
        WHERE items.is_active = 1
          AND item_batches.stock > 0
          AND item_batches.expired_date IS NOT NULL
          AND item_batches.expired_date <= :warning_end
        ORDER BY item_batches.expired_date ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':warning_end' => $warningEnd]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $itemsExpired = [];
    $itemsWarning = [];

    foreach ($rows as $row) {
        if ($row['expired_date'] < $today) {
            $itemsExpired[] = $row;
        } else {
            $itemsWarning[] = $row;
        }
    }

    $expiredCount = count($itemsExpired);
    $warningCount = count($itemsWarning);
    $hasAlert     = ($expiredCount + $warningCount) > 0;

    if ($hasAlert) {
        $pesan = "⚠️ *ALERT STOK KEDALUWARSA*\n";
        $pesan .= "🏢 *" . escapeMarkdown($companyName) . "*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $pesan .= "📅 " . escapeMarkdown(date('d M Y H:i') . " WIB") . "\n";
        $pesan .= "ℹ️ Peringatan ≤ *" . escapeMarkdown((string)$notifyDays) . "* hari ke depan\n\n";

        if ($expiredCount > 0) {
            $pesan .= "🔴 *SUDAH KEDALUWARSA* (" . escapeMarkdown((string)$expiredCount) . " barang)\n\n";
            $no = 1;
            foreach ($itemsExpired as $it) {
                $pesan .= format_expired_line($no++, $it, true);
            }
            $pesan .= "\n";
        }

        if ($warningCount > 0) {
            $pesan .= "🟠 *HAMPIR KEDALUWARSA* (" . escapeMarkdown((string)$warningCount) . " barang)\n\n";
            $no = 1;
            foreach ($itemsWarning as $it) {
                $pesan .= format_expired_line($no++, $it, false);
            }
            $pesan .= "\n";
        }

        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "📦 Segera cek & mutasi stok di aplikasi Logistic.\n";
        $pesan .= "🔗 logistic.appsbee.my.id\n\n";
        $pesan .= "_Pesan otomatis — System Logistic_";
    }
} catch (PDOException $e) {
    $pesan = "❌ *Error Logistic Notify*\nGagal ambil data: " . escapeMarkdown($e->getMessage());
}

// Gateway otomatis menangkap variabel $pesan untuk dikirim ke WA.
// Jika $pesan kosong, WA tidak akan dikirim.
PHP;

    $stmtInsert = $pdo->prepare("
        INSERT INTO tb_app_tasks 
        (app_id, task_name, wa_id, task_type, php_script, custom_query, message_template, body_message, message_footer) 
        VALUES 
        (:app_id, :task_name, :wa_id, 'php', :php_script, '', '', '', '')
    ");
    
    $stmtInsert->execute([
        ':app_id' => $appId,
        ':task_name' => 'Laporan Expired Harian (Contoh)',
        ':wa_id' => $waId,
        ':php_script' => $phpScript
    ]);
    
    echo "Task berhasil ditambahkan untuk aplikasi '{$app['name']}'!\n";

} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
