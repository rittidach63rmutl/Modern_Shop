<?php
require 'header.php';
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?? 0,
    'users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn()
];
$recent_orders = $pdo->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5")->fetchAll();
?>
<h1 class="text-3xl font-bold text-slate-800 mb-8">Dashboard Overview</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div class="text-slate-500 font-medium mb-1">Total Revenue</div>
        <div class="text-3xl font-bold text-slate-800">฿<?= number_format($stats['revenue'], 2) ?></div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div class="text-slate-500 font-medium mb-1">Total Orders</div>
        <div class="text-3xl font-bold text-slate-800"><?= $stats['orders'] ?></div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div class="text-slate-500 font-medium mb-1">Products</div>
        <div class="text-3xl font-bold text-slate-800"><?= $stats['products'] ?></div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div class="text-slate-500 font-medium mb-1">Customers</div>
        <div class="text-3xl font-bold text-slate-800"><?= $stats['users'] ?></div>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50"><h3 class="font-bold text-lg text-slate-800">Recent Orders</h3></div>
    <table class="w-full text-left">
        <thead class="text-slate-500 text-sm border-b border-slate-200"><tr><th class="px-6 py-4 font-medium">Order ID</th><th class="px-6 py-4 font-medium">Customer</th><th class="px-6 py-4 font-medium">Amount</th><th class="px-6 py-4 font-medium">Status</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach($recent_orders as $o): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 font-medium text-slate-800">#<?= str_pad($o['id'],5,'0',STR_PAD_LEFT) ?></td>
                <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($o['user_name']) ?></td>
                <td class="px-6 py-4 font-bold text-indigo-600">฿<?= number_format($o['total_amount'],2) ?></td>
                <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold <?= $o['status']==='completed'?'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>"><?= ucfirst($o['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'footer.php'; ?>