<?php
require_once 'config.php';

// Destroy session
session_destroy();

// Redirect ke halaman login Sistem Keuangan (Indonesian)
header('Location: login_id.php');
exit;
?>
