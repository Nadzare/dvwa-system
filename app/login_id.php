<?php
require_once 'config.php';

$error = '';
$success_msg = '';
$login_attempt = false;
$db_needs_setup = false;
$db_exists = false;

// Cek pesan reset
if (isset($_GET['reset_msg'])) {
    $success_msg = $_GET['reset_msg'];
}

// Cek apakah database/tabel sudah ada
if (!$mysqli) {
    $db_needs_setup = true;
    $error = "Database tidak ditemukan. Silakan setup database terlebih dahulu.";
} else {
    // Cek apakah tabel users sudah ada
    $table_check = @$mysqli->query("SHOW TABLES LIKE 'users'");
    if (!$table_check || $table_check->num_rows === 0) {
        $db_needs_setup = true;
        $error = "Tabel database tidak ditemukan. Silakan setup database terlebih dahulu.";
    } else {
        $db_exists = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$db_needs_setup) {
    $login_attempt = true;
    
    // VULNERABLE: SQL Injection in login form - NO ESCAPING
    // User input is NOT escaped or parameterized
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Error-based SQL Injection
    // Attacker can craft: admin' OR '1'='1
    // Query becomes: SELECT * FROM users WHERE username='admin' OR '1'='1' AND password='...'
    $query = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . md5($password) . "'";
    
    $result = $mysqli->query($query);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard_id.php');
        exit;
    } else {
        $error = "Nama pengguna atau kata sandi salah";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Keuangan - Portal Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background-color: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #1e3c72;
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
        }
        .subtitle {
            text-align: center;
            color: #2a5298;
            font-size: 13px;
            margin-bottom: 25px;
            font-weight: 500;
        }
        .logo {
            text-align: center;
            font-size: 42px;
            margin-bottom: 8px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            box-sizing: border-box;
            background-color: #f5f7fa;
            color: #333;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #2a5298;
            background-color: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(42, 82, 152, 0.3);
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 82, 152, 0.4);
        }
        .error {
            color: #d32f2f;
            margin-bottom: 16px;
            padding: 10px 12px;
            background-color: #ffebee;
            border-radius: 5px;
            border-left: 4px solid #d32f2f;
            font-size: 13px;
        }
        .success {
            color: #388e3c;
            margin-bottom: 16px;
            padding: 10px 12px;
            background-color: #e8f5e9;
            border-radius: 5px;
            border-left: 4px solid #388e3c;
            font-size: 13px;
        }
        .info {
            color: #666;
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            background-color: #f5f7fa;
            border-radius: 5px;
        }
        .warning-badge {
            background-color: #fff3cd;
            color: #856404;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 11px;
            text-align: center;
            margin-bottom: 18px;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">💰</div>
        <h1>Sistem Keuangan</h1>
        <div class="subtitle">Financial Management System</div>
        
        <div class="warning-badge">
            ⚠️ Demo Environment - Vulnerable by Design
        </div>
        
        <?php if ($success_msg): ?>
            <div class="success">
                ✓ <?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($db_needs_setup): ?>
            <div style="background-color: #e3f2fd; border: 1px solid #2196F3; color: #1565c0; padding: 18px; border-radius: 8px; margin-bottom: 20px;">
                <strong>🚀 Inisialisasi Database Diperlukan</strong><br>
                <p style="margin: 12px 0 0 0; font-size: 14px;">Sistem keuangan memerlukan setup database. Klik tombol di bawah untuk membuat skema database secara otomatis.</p>
            </div>
            <a href="setup_database_id.php" style="display: block; width: 100%; padding: 14px; background: linear-gradient(135deg, #4CAF50 0%, #388e3c 100%); color: #fff; text-align: center; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: 600; margin-bottom: 15px; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);">
                📦 Setup Database Keuangan
            </a>
            <div style="text-align: center; color: #888; font-size: 14px; margin-top: 15px;">
                <p>Atau jika database sudah ada:</p>
                <button type="button" onclick="location.reload()" style="background-color: #666; padding: 8px 16px; font-size: 14px;">
                    🔄 Coba Koneksi Lagi
                </button>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">👤 Username / ID Pegawai</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required>
                </div>
                
                <div class="form-group">
                    <label for="password">🔒 Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                </div>
                
                <button type="submit">🔐 Login ke Sistem</button>
            </form>
            
            <div class="info">
                <p style="font-weight: 600; color: #333; margin-bottom: 8px;">Akun Demo:</p>
                <p style="margin: 4px 0;"><strong>Username:</strong> admin</p>
                <p style="margin: 4px 0;"><strong>Password:</strong> admin123</p>
                <p style="margin-top: 12px; font-size: 11px; color: #999;">Role: Financial Administrator</p>
            </div>
            
            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #e1e8ed;">
                <p style="text-align: center; font-size: 12px; color: #666; margin-bottom: 12px; font-weight: 600;">Admin Tools:</p>
                <div style="display: flex; gap: 8px;">
                    <a href="setup_database_id.php" style="flex: 1; padding: 10px; background-color: #4CAF50; color: #fff; text-align: center; text-decoration: none; border-radius: 5px; font-size: 13px; font-weight: 500;">
                        📦 Setup DB
                    </a>
                    <a href="reset_db.php?from=login&lang=id" style="flex: 1; padding: 10px; background-color: #ff9800; color: #fff; text-align: center; text-decoration: none; border-radius: 5px; font-size: 13px; font-weight: 500;">
                        🔄 Reset
                    </a>
                </div>
                <div style="margin-top: 8px;">
                    <a href="reset_database_id.php?from=login" style="display: block; padding: 10px; background-color: #d32f2f; color: #fff; text-align: center; text-decoration: none; border-radius: 5px; font-size: 13px; font-weight: 500;">
                        🗑️ Hapus Database
                    </a>
                </div>
                <p style="color: #999; font-size: 10px; text-align: center; margin-top: 12px; line-height: 1.4;">
                    Setup: Inisialisasi skema | Reset: Hapus data transaksi | Hapus: Bersihkan semua tabel
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
