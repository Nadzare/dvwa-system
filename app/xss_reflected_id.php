<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// VULNERABLE: Reflected XSS
// User input from URL parameter is directly echoed without escaping
$name = $_GET['name'] ?? '';

// EVASION SUPPORT: Multi-level URL decoding
$decoded_name = $name;
for ($i = 0; $i < 5; $i++) {
    $prev = $decoded_name;
    $decoded_name = urldecode($decoded_name);
    if ($prev === $decoded_name) break;
}

// EVASION SUPPORT: HTML entity decoding
$decoded_name = html_entity_decode($decoded_name, ENT_QUOTES | ENT_HTML5);

// EVASION SUPPORT: Unicode escapes
$decoded_name = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function($m) {
    return mb_convert_encoding(pack('H*', $m[1]), 'UTF-8', 'UTF-16BE');
}, $decoded_name);

$name = $decoded_name;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Arsip Surat - Feedback Sistem</title>
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
        input[type="text"] {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
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
        .greeting {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
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
        <h1>Feedback Sistem</h1>
        <div class="info-box">
            <h2>Kirim Feedback</h2>
            <p>Kirim masukan atau feedback ke admin sistem arsip. Input feedback akan langsung ditampilkan tanpa filter (Reflected XSS).</p>
        </div>
        <form method="GET">
            <div class="form-group">
                <label for="name">Isi Feedback Anda</label>
                <input type="text" id="name" name="name" placeholder="Tulis feedback..." value="<?php echo htmlspecialchars($name); ?>">
            </div>
            <button type="submit">Kirim Feedback</button>
        </form>
        <div class="hint">
            <strong>Petunjuk Eksploitasi:</strong><br>
            • Coba: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code><br>
            • Coba: <code>&lt;img src=x onerror="alert('XSS')"&gt;</code><br>
            • Coba: <code>&lt;svg onload="alert('XSS')"&gt;</code><br>
            • Bagikan link berbahaya: <code>http://localhost:8000/xss_reflected.php?name=&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
        </div>
        <?php if ($name): ?>
            <div class="greeting">
                <p>Feedback Anda: <?php echo $name; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
