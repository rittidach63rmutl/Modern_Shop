<?php
require 'includes/header.php';
require 'includes/navbar.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>
<div class="container mx-auto px-4 py-10 max-w-5xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Orders</h1>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr><th class="p-4 font-semibold text-gray-600">Order ID</th><th class="p-4 font-semibold text-gray-600">Date</th><th class="p-4 font-semibold text-gray-600">Total</th><th class="p-4 font-semibold text-gray-600">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($orders as $o): ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-medium text-gray-900">#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td class="p-4 text-gray-500"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
                    <td class="p-4 font-bold text-indigo-600">฿<?= number_format($o['total_amount'], 2) ?></td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $o['status']==='completed'?'bg-green-100 text-green-700' : ($o['status']==='pending'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') ?>">
                            <?= ucfirst($o['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($orders)): ?><tr><td colspan="4" class="p-8 text-center text-gray-500">You have no orders yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require 'includes/footer.php'; ?>