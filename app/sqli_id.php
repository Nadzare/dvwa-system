<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$result = null;
$search_query = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = $_POST['search'] ?? '';
    
    // EVASION SUPPORT: Multi-level URL decoding
    $decoded_query = $search_query;
    for ($i = 0; $i < 5; $i++) {
        $prev = $decoded_query;
        $decoded_query = urldecode($decoded_query);
        if ($prev === $decoded_query) break;
    }
    
    // EVASION SUPPORT: HTML entity decoding
    $decoded_query = html_entity_decode($decoded_query, ENT_QUOTES | ENT_HTML5);
    
    // EVASION SUPPORT: Unicode escapes
    $decoded_query = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function($m) {
        return mb_convert_encoding(pack('H*', $m[1]), 'UTF-8', 'UTF-16BE');
    }, $decoded_query);
    
    // EVASION SUPPORT: Normalize whitespace
    $decoded_query = preg_replace('/[\t\n\r\x00\x0B]+/', ' ', $decoded_query);
    
    // VULNERABLE: SQL Injection - No parameterized queries, NO ESCAPING
    // Attacker can use: 1' OR '1'='1
    // Attacker can use: 1' UNION SELECT 1,2,3,4 -- 
    // Attacker can use: 1' UNION SELECT username, password, 3, created_at FROM users -- 
    // The query is directly concatenated without any sanitization
    $query = "SELECT id, username, email, created_at FROM comments WHERE id = '" . $decoded_query . "'";
    
    $result = $mysqli->query($query);
    
    if (!$result) {
        // Error-based SQLi - returns MySQL errors
        $error = "Kesalahan SQL: " . $mysqli->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Arsip Surat - Pencarian Surat</title>
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
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus {
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
        .error {
            color: #721c24;
            background-color: #f8d7da;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
        .results {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .results h3 {
            color: #1e3c72;
            margin-top: 0;
        }
        .results table {
            width: 100%;
            border-collapse: collapse;
        }
        .results th,
        .results td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        .results th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #1e3c72;
        }
        .results td {
            color: #333;
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
        .hint code {
            background-color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            color: #d63384;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard_id.php" class="back-link">← Kembali ke Dasbor</a>
        <h1>Pencarian Surat</h1>
        <div class="info-box">
            <h2>Cari Surat Berdasarkan Nomor</h2>
            <p>Cari surat masuk/keluar berdasarkan nomor surat. Fitur ini rentan terhadap SQL Injection.</p>
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="search">Nomor Surat</label>
                <input type="text" id="search" name="search" placeholder="Masukkan nomor surat atau payload SQL injection...">
            </div>
            <button type="submit">Cari</button>
        </form>
        <div class="hint">
            <strong>Petunjuk Eksploitasi:</strong><br>
            • Coba: <code>1' OR '1'='1' #</code> - tampilkan semua surat<br>
            • Coba: <code>1' UNION SELECT 1,2,3,4 #</code> - uji kolom UNION<br>
            • Coba: <code>1' UNION SELECT username,password,3,created_at FROM users #</code> - ekstrak data user<br>
            • Coba: <code>1' AND SLEEP(5) #</code> - SQLi blind berbasis waktu (response delay 5 detik)<br>
            • Alternative: Gunakan <code>--+</code> atau <code>#</code> untuk SQL comment
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="results">
                <h3>Hasil Pencarian Surat</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nomor Surat</th>
                            <th>Pengirim</th>
                            <th>Email</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$row['id']); ?></td>
                                <td><?php echo htmlspecialchars((string)$row['username']); ?></td>
                                <td><?php echo htmlspecialchars((string)($row['email'] ?? 'N/A')); ?></td>
                                <td><?php echo htmlspecialchars((string)$row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (!$error && $search_query): ?>
            <div class="error">Tidak ada surat dengan nomor: <?php echo htmlspecialchars($search_query); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
