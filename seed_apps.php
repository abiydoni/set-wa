<?php
$host = 'localhost';
$user = 'appsbeem_admin';
$pass = 'A7by777__';
$db = 'appsbeem_setwa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM tb_applications");
    if ($stmt->fetchColumn() == 0) {
        $ins = $pdo->prepare("INSERT INTO tb_applications (name, folder_name, db_host, db_user, db_pass, db_name) VALUES (?, ?, ?, ?, ?, ?)");
        $ins->execute(['Logistic App', 'logistic', 'localhost', 'appsbeem_admin', 'A7by777__', 'appsbeem_logistic']);
        $ins->execute(['Jimpitan App', 'jimpitan', 'localhost', 'appsbeem_admin', 'A7by777__', 'appsbeem_jimpitan']);
        echo "Seeded logistic and jimpitan apps.\n";
    } else {
        echo "tb_applications is not empty.\n";
    }
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
