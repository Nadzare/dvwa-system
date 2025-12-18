<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Keuangan - Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1100px;
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
            color: #fff;
            font-size: 28px;
            font-weight: 600;
        }
        .subtitle {
            color: #b3d4ff;
            font-size: 14px;
            margin-top: 4px;
        }
        .logout-btn {
            background-color: #fff;
            color: #1e3c72;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255,255,255,0.3);
        }
        .labs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .lab-card {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #2a5298;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .lab-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }
        .lab-card-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }
        .lab-card h2 {
            margin: 0 0 12px 0;
            color: #1e3c72;
            font-size: 20px;
            font-weight: 600;
        }
        .lab-card p {
            margin: 0 0 18px 0;
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }
        .lab-card a {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(42, 82, 152, 0.3);
        }
        .lab-card a:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(42, 82, 152, 0.4);
        }
        .welcome {
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            color: #333;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-left: 5px solid #4CAF50;
        }
        .welcome p {
            margin: 0;
            font-size: 15px;
        }
        .welcome strong {
            color: #1e3c72;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <div class="header-icon">💰</div>
                <div>
                    <h1>Sistem Keuangan</h1>
                    <div class="subtitle">Financial Management System - Admin Dashboard</div>
                </div>
            </div>
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>
        <div class="welcome">
            <p>👋 Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! | Role: <strong>Financial Administrator</strong> | Security Level: <strong style="color: #ff9800;"><?php echo strtoupper($_SESSION['security_level'] ?? 'LOW'); ?></strong></p>
        </div>
        <div class="labs">
            <div class="lab-card" style="border-left: 5px solid #9c27b0;">
                <div class="lab-card-icon">🛡️</div>
                <h2>Security Level</h2>
                <p>Atur tingkat keamanan sistem (Low, Medium, High, Impossible). Setiap level memiliki proteksi berbeda pada vulnerability.</p>
                <a href="security_level.php" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);">⚙️ Atur Level</a>
            </div>
            <div class="lab-card">
                <div class="lab-card-icon">🔍</div>
                <h2>Pencarian Transaksi</h2>
                <p>Cari transaksi keuangan berdasarkan nomor invoice atau ID transaksi. Fitur ini rentan terhadap SQL Injection.</p>
                <a href="sqli_id.php">➡️ Cari Transaksi</a>
            </div>
            <div class="lab-card">
                <div class="lab-card-icon">📝</div>
                <h2>Catatan Transaksi</h2>
                <p>Tambahkan catatan/memo pada transaksi keuangan. Catatan dapat dilihat oleh semua finance staff. Rentan terhadap Stored XSS.</p>
                <a href="xss_stored_id.php">➡️ Kelola Catatan</a>
            </div>
            <div class="lab-card">
                <div class="lab-card-icon">📨</div>
                <h2>Laporan Keuangan</h2>
                <p>Submit laporan keuangan ke sistem. Preview laporan sebelum dikirim. Fitur ini rentan terhadap Reflected XSS.</p>
                <a href="xss_reflected_id.php">➡️ Kirim Laporan</a>
            </div>
            <div class="lab-card">
                <div class="lab-card-icon">🔒</div>
                <h2>Ubah PIN Transaksi</h2>
                <p>Ganti PIN untuk otorisasi transaksi keuangan. Fitur ini rentan terhadap serangan CSRF.</p>
                <a href="csrf_id.php">➡️ Ganti PIN</a>
            </div>
            <div class="lab-card" style="border-left: 5px solid #ff5252;">
                <div class="lab-card-icon">🗑️</div>
                <h2>Reset Database</h2>
                <p>Hapus semua data transaksi dan kembalikan database ke state awal. Berguna untuk cleanup setelah testing.</p>
                <a href="reset_database_id.php" style="background: linear-gradient(135deg, #ff5252 0%, #c62828 100%);">⚠️ Reset Database</a>
            </div>
        </div>
    </div>
</body>
</html>
