<?php
require_once __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash'] = ['msg' => 'Access Denied!', 'type' => 'error'];
    header("Location: ../login.php"); exit;
}
?>