<?php require 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">
<?php
if(isset($_SESSION['flash'])) {
    $msg = $_SESSION['flash']['msg']; $icon = $_SESSION['flash']['type'] ?? 'success';
    echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: '$icon', title: '$msg', showConfirmButton: false, timer: 1500 }); });</script>";
    unset($_SESSION['flash']);
}
?>
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6 bg-slate-950 font-bold text-xl flex items-center gap-2"><svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Admin Panel</div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="index.php" class="block px-4 py-2.5 rounded-lg hover:bg-slate-800 transition">Dashboard</a>
            <a href="products.php" class="block px-4 py-2.5 rounded-lg hover:bg-slate-800 transition">Manage Products</a>
            <hr class="border-slate-800 my-4">
            <a href="../index.php" class="block px-4 py-2.5 rounded-lg text-indigo-400 hover:bg-slate-800 transition">View Storefront</a>
            <a href="../logout.php" class="block px-4 py-2.5 rounded-lg text-red-400 hover:bg-red-900/30 transition">Logout</a>
        </nav>
    </aside>
    <main class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center md:hidden">
            <div class="font-bold text-xl text-slate-800">Admin Panel</div>
            <a href="../index.php" class="text-indigo-600">Store</a>
        </header>
        <div class="p-6 md:p-10 flex-1 overflow-y-auto">