<?php
require_once 'config.php';

// Allow access from login page
$from_login = isset($_GET['from']) && $_GET['from'] === 'login';

if (!$from_login && !isset($_SESSION['user_id'])) {
    header('Location: login_id.php');
    exit;
}

$message = '';
$error = '';

// Handle database reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_reset'])) {
    $confirmation = $_POST['confirmation'] ?? '';
    
    if ($confirmation !== 'RESET DATABASE') {
        $error = 'Konfirmasi salah! Ketik: RESET DATABASE';
    } else {
        // Drop all tables
        $tables_dropped = 0;
        $tables_created = 0;
        
        // Get all tables
        $result = $mysqli->query("SHOW TABLES");
        $tables = [];
        
        if ($result) {
            while ($row = $result->fetch_array()) {
                $tables[] = $row[0];
            }
            
            // Drop each table
            foreach ($tables as $table) {
                if ($mysqli->query("DROP TABLE IF EXISTS `$table`")) {
                    $tables_dropped++;
                }
            }
        }
        
        // Recreate tables
        // Users table
        $create_users = "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($mysqli->query($create_users)) {
            $tables_created++;
            
            // Insert default users
            $default_users = [
                ['admin', md5('admin123'), 'admin@dvwa.local'],
                ['john', md5('john123'), 'john@dvwa.local'],
                ['jane', md5('jane123'), 'jane@dvwa.local'],
                ['bob', md5('bob123'), 'bob@dvwa.local']
            ];
            
            foreach ($default_users as $user) {
                $mysqli->query("INSERT INTO users (username, password, email) VALUES ('{$user[0]}', '{$user[1]}', '{$user[2]}')");
            }
        }
        
        // Comments table
        $create_comments = "CREATE TABLE IF NOT EXISTS comments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100),
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($mysqli->query($create_comments)) {
            $tables_created++;
            
            // Insert sample comments
            $sample_comments = [
                ['admin', 'admin@dvwa.local', 'Surat pertama telah diarsipkan'],
                ['john', 'john@dvwa.local', 'Surat kedua berhasil diterima'],
                ['jane', 'jane@dvwa.local', 'Arsip dokumen penting']
            ];
            
            foreach ($sample_comments as $comment) {
                $mysqli->query("INSERT INTO comments (username, email, content, created_at) VALUES ('{$comment[0]}', '{$comment[1]}', '{$comment[2]}', NOW())");
            }
        }
        
        $message = "✅ Database berhasil direset!\n";
        $message .= "📊 $tables_dropped tabel dihapus\n";
        $message .= "📊 $tables_created tabel dibuat ulang\n";
        $message .= "👤 4 user ditambahkan (admin, john, jane, bob)\n";
        $message .= "💬 3 sample comments ditambahkan";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Database - Sistem Arsip Surat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .back-link {
            background-color: #fff;
            color: #1e3c72;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }
        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255,255,255,0.3);
        }
        h1 {
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .warning-box {
            background-color: #fff3cd;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #ffc107;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            color: #856404;
        }
        .warning-box h2 {
            color: #d39e00;
            margin-top: 0;
        }
        .info-box {
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #2a5298;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            color: #333;
        }
        .info-box h3 {
            color: #1e3c72;
            margin-top: 0;
        }
        .success-box {
            background-color: #d4edda;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #28a745;
            white-space: pre-line;
            color: #155724;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .error-box {
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #dc3545;
            color: #721c24;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #1e3c72;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }
        button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            margin-left: 10px;
        }
        .btn-secondary:hover {
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }
        .table-list {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .table-list h3 {
            margin-top: 0;
            color: #1e3c72;
        }
        .table-item {
            padding: 10px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 6px;
            color: #333;
        }
        .lang-switch {
            float: right;
            margin-top: 10px;
        }
        .lang-switch a {
            background-color: #fff;
            color: #1e3c72;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255,255,255,0.2);
        }
        .lang-switch a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="lang-switch">
            <a href="reset_database.php?from=login">🇬🇧 English</a>
        </div>
        
        <?php if ($from_login): ?>
            <a href="login_id.php" class="back-link">← Kembali ke Login</a>
        <?php else: ?>
            <a href="dashboard_id.php" class="back-link">← Kembali ke Dashboard</a>
        <?php endif; ?>
        
        <h1>🗑️ Reset Database</h1>
        
        <div class="warning-box">
            <h2>⚠️ PERINGATAN!</h2>
            <p><strong>Tindakan ini akan menghapus SEMUA data:</strong></p>
            <ul>
                <li>❌ Semua tabel akan di-DROP</li>
                <li>❌ Semua user (kecuali default) akan hilang</li>
                <li>❌ Semua komentar akan hilang</li>
                <li>❌ Semua data testing XSS/SQLi akan hilang</li>
            </ul>
            <p><strong>Yang akan dibuat ulang:</strong></p>
            <ul>
                <li>✅ Tabel users & comments</li>
                <li>✅ 4 user default (admin, john, jane, bob)</li>
                <li>✅ 3 komentar sample</li>
            </ul>
        </div>
        
        <?php if ($message): ?>
            <div class="success-box">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="table-list">
            <h3>📊 Tabel Saat Ini:</h3>
            <?php
            $result = $mysqli->query("SHOW TABLES");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    echo '<div class="table-item">📁 ' . htmlspecialchars($row[0]) . '</div>';
                }
            } else {
                echo '<p style="color: #999;">Tidak ada tabel</p>';
            }
            ?>
        </div>
        
        <div class="info-box">
            <p><strong>Untuk mengonfirmasi reset database, ketik:</strong></p>
            <p style="font-size: 20px; color: #f00; font-family: monospace; font-weight: bold;">RESET DATABASE</p>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="confirmation">Konfirmasi Reset:</label>
                <input type="text" id="confirmation" name="confirmation" placeholder="Ketik: RESET DATABASE" required autofocus>
            </div>
            
            <button type="submit" name="confirm_reset">🗑️ Reset Database</button>
            <a href="dashboard_id.php" class="btn-secondary" style="text-decoration: none; display: inline-block; padding: 12px 30px; border-radius: 3px;">❌ Batal</a>
        </form>
        
        <div class="info-box" style="margin-top: 30px;">
            <h3>📝 Catatan:</h3>
            <ul>
                <li>Reset ini berguna untuk cleanup setelah testing XSS/SQLi</li>
                <li>Semua payload yang tersimpan akan dihapus</li>
                <li>Database akan kembali ke state awal</li>
                <li>Login credentials default: admin/admin123</li>
            </ul>
        </div>
    </div>
</body>
</html>
