<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_id.php');
    exit;
}

// Set default security level jika belum ada
if (!isset($_SESSION['security_level'])) {
    $_SESSION['security_level'] = 'low';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['security_level'])) {
    $level = strtolower($_POST['security_level']);
    if (in_array($level, ['low', 'medium', 'high', 'impossible'])) {
        $_SESSION['security_level'] = $level;
        $success_msg = "Security level berhasil diubah ke: " . strtoupper($level);
    }
}

$current_level = $_SESSION['security_level'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Keuangan - Security Level</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 25px 30px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            color: #fff;
        }
        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-icon {
            font-size: 36px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .subtitle {
            color: #b3d4ff;
            font-size: 13px;
            margin-top: 4px;
        }
        .back-btn {
            background-color: #fff;
            color: #1e3c72;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255,255,255,0.3);
        }
        .content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .success {
            background-color: #e8f5e9;
            color: #388e3c;
            padding: 12px 15px;
            border-radius: 5px;
            border-left: 4px solid #388e3c;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            color: #1565c0;
            font-size: 14px;
            line-height: 1.6;
        }
        .level-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .level-card {
            background-color: #f5f7fa;
            border: 3px solid #e1e8ed;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .level-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .level-card.active {
            border-color: #2a5298;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            box-shadow: 0 4px 15px rgba(42, 82, 152, 0.3);
        }
        .level-card input[type="radio"] {
            display: none;
        }
        .level-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .level-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }
        .level-desc {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(42, 82, 152, 0.3);
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 82, 152, 0.4);
        }
        .current-level {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #856404;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <div class="header-icon">🛡️</div>
                <div>
                    <h1>Security Level</h1>
                    <div class="subtitle">Atur tingkat keamanan sistem</div>
                </div>
            </div>
            <a href="dashboard_id.php" class="back-btn">← Kembali</a>
        </div>

        <div class="content">
            <?php if (isset($success_msg)): ?>
                <div class="success">
                    ✓ <?php echo htmlspecialchars($success_msg); ?>
                </div>
            <?php endif; ?>

            <div class="current-level">
                ⚠️ Security Level Saat Ini: <strong><?php echo strtoupper($current_level); ?></strong>
            </div>

            <div class="info-box">
                <strong>ℹ️ Tentang Security Level:</strong><br>
                Sistem ini memiliki 4 tingkat keamanan berbeda untuk simulasi serangan. 
                Pilih level untuk melihat perbedaan proteksi pada setiap vulnerability (SQLi, XSS, CSRF).
            </div>

            <form method="POST">
                <div class="level-grid">
                    <label class="level-card <?php echo $current_level === 'low' ? 'active' : ''; ?>">
                        <input type="radio" name="security_level" value="low" <?php echo $current_level === 'low' ? 'checked' : ''; ?>>
                        <div class="level-icon">🟢</div>
                        <div class="level-name">LOW</div>
                        <div class="level-desc">Tidak ada proteksi. Sangat vulnerable untuk testing.</div>
                    </label>

                    <label class="level-card <?php echo $current_level === 'medium' ? 'active' : ''; ?>">
                        <input type="radio" name="security_level" value="medium" <?php echo $current_level === 'medium' ? 'checked' : ''; ?>>
                        <div class="level-icon">🟡</div>
                        <div class="level-name">MEDIUM</div>
                        <div class="level-desc">Proteksi dasar. Masih bisa di-bypass dengan teknik tertentu.</div>
                    </label>

                    <label class="level-card <?php echo $current_level === 'high' ? 'active' : ''; ?>">
                        <input type="radio" name="security_level" value="high" <?php echo $current_level === 'high' ? 'checked' : ''; ?>>
                        <div class="level-icon">🟠</div>
                        <div class="level-name">HIGH</div>
                        <div class="level-desc">Proteksi tinggi. Lebih sulit untuk exploit.</div>
                    </label>

                    <label class="level-card <?php echo $current_level === 'impossible' ? 'active' : ''; ?>">
                        <input type="radio" name="security_level" value="impossible" <?php echo $current_level === 'impossible' ? 'checked' : ''; ?>>
                        <div class="level-icon">🔴</div>
                        <div class="level-name">IMPOSSIBLE</div>
                        <div class="level-desc">Fully secure. Best practice implementation.</div>
                    </label>
                </div>

                <button type="submit" class="submit-btn">💾 Simpan Pengaturan</button>
            </form>

            <div class="info-box" style="margin-top: 25px;">
                <strong>📋 Perbedaan Level:</strong><br><br>
                <strong>🟢 LOW:</strong> No input validation, no escaping, no CSRF token<br>
                <strong>🟡 MEDIUM:</strong> Basic validation, basic escaping/filtering<br>
                <strong>🟠 HIGH:</strong> Strong validation, prepared statements, output encoding<br>
                <strong>🔴 IMPOSSIBLE:</strong> Parameterized queries, strict validation, CSRF tokens, secure headers
            </div>
        </div>
    </div>

    <script>
        // Auto-submit when clicking card
        document.querySelectorAll('.level-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.level-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
