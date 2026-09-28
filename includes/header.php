<?php require_once __DIR__ . '/../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen text-gray-800">
<?php
if(isset($_SESSION['flash'])) {
    $msg = $_SESSION['flash']['msg'];
    $icon = $_SESSION['flash']['type'] ?? 'success';
    echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: '$icon', title: '$msg', showConfirmButton: false, timer: 2000 }); });</script>";
    unset($_SESSION['flash']);
}
?>