<?php
require 'includes/header.php';
require 'includes/navbar.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if(!$p) { header("Location: index.php"); exit; }
?>
<div class="container mx-auto px-4 py-12 max-w-6xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
        <div class="w-full md:w-1/2 bg-gray-50 p-8 flex items-center justify-center">
            <img src="<?= strpos($p['image'], 'http') === 0 ? $p['image'] : BASE_URL.'/uploads/'.$p['image'] ?>" class="max-w-full h-auto rounded-lg shadow-sm">
        </div>
        <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
            <span class="text-sm font-bold text-indigo-500 uppercase tracking-wider mb-2"><?= htmlspecialchars($p['cat_name']) ?></span>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($p['name']) ?></h1>
            <p class="text-gray-600 mb-8 leading-relaxed"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
            <div class="text-3xl font-bold text-gray-900 mb-6">฿<?= number_format($p['price'], 2) ?></div>
            
            <div class="mb-8">
                <span class="text-sm font-medium text-gray-500">Availability: </span>
                <?php if($p['stock'] > 0): ?>
                    <span class="text-green-600 font-bold"><?= $p['stock'] ?> in stock</span>
                <?php else: ?>
                    <span class="text-red-600 font-bold">Out of stock</span>
                <?php endif; ?>
            </div>

            <?php if($p['stock'] > 0): ?>
            <form action="cart.php" method="POST" class="flex gap-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="number" name="qty" value="1" min="1" max="<?= $p['stock'] ?>" class="w-20 border border-gray-300 rounded-lg px-4 py-3 text-center focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition shadow-sm">Add to Cart</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>