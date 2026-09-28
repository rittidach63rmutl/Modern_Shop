<?php
require 'header.php';
$products = $pdo->query("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
?>
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-slate-800">Products</h1>
    <a href="product_add.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm transition">+ Add Product</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-sm border-b border-slate-200"><tr><th class="px-6 py-4 font-medium">Product</th><th class="px-6 py-4 font-medium">Category</th><th class="px-6 py-4 font-medium">Price</th><th class="px-6 py-4 font-medium">Stock</th><th class="px-6 py-4 font-medium text-right">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            <?php foreach($products as $p): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 flex items-center">
                    <img src="<?= strpos($p['image'], 'http') === 0 ? $p['image'] : BASE_URL.'/uploads/'.$p['image'] ?>" class="w-12 h-12 rounded object-cover border border-slate-200">
                    <span class="ml-4 font-medium text-slate-800"><?= htmlspecialchars($p['name']) ?></span>
                </td>
                <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($p['cat_name']) ?></td>
                <td class="px-6 py-4 font-semibold text-slate-800">฿<?= number_format($p['price'],2) ?></td>
                <td class="px-6 py-4 <?= $p['stock']>0?'text-green-600':'text-red-600 font-bold' ?>"><?= $p['stock'] ?></td>
                <td class="px-6 py-4 text-right space-x-3">
                    <a href="product_edit.php?id=<?= $p['id'] ?>" class="text-blue-500 hover:text-blue-700 font-medium">Edit</a>
                    <a href="product_delete.php?id=<?= $p['id'] ?>" class="text-red-500 hover:text-red-700 font-medium" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'footer.php'; ?>