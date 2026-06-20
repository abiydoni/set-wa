<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\AppTaskModel;

class Applications extends BaseController
{
    protected ApplicationModel $appModel;
    protected AppTaskModel $taskModel;

    public function __construct()
    {
        $this->appModel = new ApplicationModel();
        $this->taskModel = new AppTaskModel();
    }

    public function index()
    {
        $data['title'] = 'Applications | WA Gateway';
        $apps = $this->appModel->findAll();
        
        foreach ($apps as &$app) {
            $app['task_count'] = $this->taskModel->where('app_id', $app['id'])->countAllResults();
        }
        
        $data['apps'] = $apps;
        
        return view('applications/index', $data);
    }

    public function create()
    {
        $settingModel = new \App\Models\SettingModel();
        $settings = $settingModel->findAll();
        $def = [];
        foreach ($settings as $s) {
            $def[$s['setting_key']] = $s['setting_value'];
        }

        $data['title'] = 'Add Application | WA Gateway';
        $data['defaultDb'] = [
            'host' => $def['defaultDbHost'] ?? 'localhost',
            'user' => $def['defaultDbUser'] ?? 'root',
            'pass' => $def['defaultDbPass'] ?? '',
            'name' => $def['defaultDbName'] ?? ''
        ];

        return view('applications/create', $data);
    }

    public function save()
    {
        $post = $this->request->getPost();
        
        $data = [
            'name' => $post['name'],
            'appsbee_api_key' => $post['appsbee_api_key'],
            'db_host' => $post['db_host'] ?? 'localhost',
            'db_user' => $post['db_user'] ?? '',
            'db_pass' => $post['db_pass'] ?? '',
            'db_name' => $post['db_name'] ?? ''
        ];

        $this->appModel->insert($data);

        return redirect()->to(base_url('applications'))->with('success', 'Aplikasi berhasil ditambahkan!');
    }

    public function edit(int|string $id)
    {
        $app = $this->appModel->find($id);
        if (!$app) {
            return redirect()->back()->with('error', 'Aplikasi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Application | WA Gateway',
            'app' => $app
        ];

        return view('applications/edit', $data);
    }

    public function update(int|string $id)
    {
        $app = $this->appModel->find($id);
        if (!$app) {
            return redirect()->back()->with('error', 'Aplikasi tidak ditemukan.');
        }

        $post = $this->request->getPost();

        $data = [
            'name' => $post['name'],
            'appsbee_api_key' => $post['appsbee_api_key'],
            'db_host' => $post['db_host'] ?? 'localhost',
            'db_user' => $post['db_user'] ?? '',
            'db_pass' => $post['db_pass'] ?? '',
            'db_name' => $post['db_name'] ?? ''
        ];

        $this->appModel->update($id, $data);

        return redirect()->to(base_url('applications'))->with('success', 'Aplikasi berhasil diupdate!');
    }

    public function delete(int|string $id)
    {
        $app = $this->appModel->find($id);
        if ($app) {
            $tasksCount = $this->taskModel->where('app_id', $id)->countAllResults();
            if ($tasksCount > 0) {
                return redirect()->to(base_url('applications'))->with('error', lang('App.cannot_delete_app'));
            }

            // Hapus DB
            $this->appModel->delete($id);
            return redirect()->to(base_url('applications'))->with('success', 'Aplikasi berhasil dihapus!');
        }
        return redirect()->to(base_url('applications'))->with('error', 'Aplikasi tidak ditemukan!');
    }

    // ------------------------------------------------------------------------
    // TASKS MANAGEMENT
    // ------------------------------------------------------------------------

    public function tasks(int|string $appId)
    {
        $app = $this->appModel->find($appId);
        if (!$app) {
            return redirect()->to(base_url('applications'))->with('error', 'Aplikasi tidak ditemukan!');
        }

        $data['title'] = 'Manage Tasks - ' . $app['name'];
        $data['app'] = $app;
        $data['tasks'] = $this->taskModel->where('app_id', $appId)->findAll();
        
        return view('applications/tasks', $data);
    }

    public function createTask(int|string $appId)
    {
        $app = $this->appModel->find($appId);
        if (!$app) {
            return redirect()->to(base_url('applications'))->with('error', 'Aplikasi tidak ditemukan!');
        }

        $defaultPhpScript = <<<'PHP'
<?php
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
        $pesan .= "_Pesan otomatis — System Logistic_";
    }
} catch (PDOException $e) {
    $pesan = "❌ *Error Logistic Notify*\nGagal ambil data: " . escapeMarkdown($e->getMessage());
}

// Gateway otomatis menangkap variabel $pesan untuk dikirim ke WA.
// Jika $pesan kosong, WA tidak akan dikirim.
PHP;

        $data['title'] = 'Add Task - ' . $app['name'];
        $data['app'] = $app;
        $data['defaultPhpScript'] = $defaultPhpScript;
        
        return view('applications/create_task', $data);
    }

    public function saveTask(int|string $appId)
    {
        $app = $this->appModel->find($appId);
        if (!$app) {
            return redirect()->to(base_url('applications'))->with('error', 'Aplikasi tidak ditemukan!');
        }

        $post = $this->request->getPost();

        $data = [
            'app_id' => $appId,
            'task_name' => $post['task_name'],
            'wa_id' => $post['wa_id'],
            'task_type' => $post['task_type'] ?? 'sql',
            'php_script' => $post['php_script'] ?? '',
            'custom_query' => $post['custom_query'] ?? '',
            'message_template' => $post['message_template'] ?? '',
            'body_message' => $post['body_message'] ?? '',
            'message_footer' => $post['message_footer'] ?? ''
        ];
        
        $this->taskModel->insert($data);

        return redirect()->to(base_url('applications/tasks/' . $appId))->with('success', 'Task berhasil dibuat!');
    }

    public function editTask(int|string $id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        $app = $this->appModel->find($task['app_id']);

        $data = [
            'title' => 'Edit Task | WA Gateway',
            'app' => $app,
            'task' => $task
        ];

        return view('applications/edit_task', $data);
    }

    public function updateTask(int|string $id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        $post = $this->request->getPost();

        $data = [
            'task_name' => $post['task_name'],
            'wa_id' => $post['wa_id'],
            'task_type' => $post['task_type'] ?? 'sql',
            'php_script' => $post['php_script'] ?? '',
            'custom_query' => $post['custom_query'] ?? '',
            'message_template' => $post['message_template'] ?? '',
            'body_message' => $post['body_message'] ?? '',
            'message_footer' => $post['message_footer'] ?? ''
        ];

        $this->taskModel->update($id, $data);

        return redirect()->to(base_url('applications/tasks/'.$task['app_id']))->with('success', 'Tugas berhasil diupdate!');
    }

    public function runTask(int|string $id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        // Run the task via CLI command using shell_exec
        $command = "php " . escapeshellarg(ROOTPATH . "apps/run_task.php") . " " . escapeshellarg($id);
        $output = shell_exec($command . " 2>&1"); // capture stderr as well

        return redirect()->back()->with('success', "Task dieksekusi. Output:\n" . $output);
    }

    public function testQuery(int|string $appId)
    {
        $app = $this->appModel->find($appId);
        if (!$app) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Aplikasi tidak ditemukan.']);
        }

        $query = $this->request->getPost('query');
        if (empty(trim($query))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Query kosong.']);
        }

        // Jangan izinkan query selain SELECT (Basic security)
        if (!preg_match('/^\s*SELECT/i', $query)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Hanya query SELECT yang diizinkan untuk diuji.']);
        }

        try {
            $dbHost = $app['db_host'];
            $dbName = $app['db_name'];
            $dbUser = $app['db_user'];
            $dbPass = $app['db_pass'];

            $customDb = \Config\Database::connect([
                'DBDriver' => 'MySQLi',
                'hostname' => $dbHost,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'charset'  => 'utf8mb4',
                'DBCollat' => 'utf8mb4_general_ci',
            ]);

            $queryObj = $customDb->query($query);
            $results = $queryObj->getResultArray();

            // Limit preview to 5 rows
            $preview = array_slice($results, 0, 5);

            return $this->response->setJSON([
                'status' => 'success',
                'count' => count($results),
                'preview' => $preview
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function testPhp(int|string $appId)
    {
        $app = $this->appModel->find($appId);
        if (!$app) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Aplikasi tidak ditemukan.']);
        }

        $phpScript = $this->request->getPost('php_script');
        if (empty(trim($phpScript))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'PHP Script kosong.']);
        }

        try {
            $dbHost = $app['db_host'];
            $dbName = $app['db_name'];
            $dbUser = $app['db_user'];
            $dbPass = $app['db_pass'];

            $pdoTarget = new \PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass);
            $pdoTarget->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo = $pdoTarget;

            $tmpFile = tempnam(sys_get_temp_dir(), 'task_test_') . '.php';
            if (strpos($phpScript, '<?php') === false) {
                $phpScript = "<?php\n" . $phpScript;
            }
            file_put_contents($tmpFile, $phpScript);

            $message = '';
            ob_start();
            try {
                include $tmpFile;
            } catch (\Throwable $e) {
                echo "\nError eksekusi: " . $e->getMessage();
            }
            $output = ob_get_clean();
            @unlink($tmpFile);

            if (!empty($pesan)) {
                $message = $pesan;
            } elseif (empty($message) && !empty(trim($output))) {
                $message = trim($output);
            }

            if (empty($message)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Script berhasil dijalankan, namun tidak menghasilkan pesan teks ($pesan kosong).'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Berhasil dijalankan',
                'preview' => $message
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deleteTask(int|string $taskId)
    {
        $task = $this->taskModel->find($taskId);
        if ($task) {
            $this->taskModel->delete($taskId);
            return redirect()->back()->with('success', 'Task berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Task tidak ditemukan!');
    }
}
