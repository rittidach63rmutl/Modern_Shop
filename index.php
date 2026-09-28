<?php
require 'includes/header.php';
require 'includes/navbar.php';

$search = $_GET['search'] ?? '';
$cat_id = $_GET['category'] ?? '';
$sql = "SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];
if($search) { $sql .= " AND p.name LIKE ?"; $params[] = "%$search%"; }
if($cat_id) { $sql .= " AND p.category_id = ?"; $params[] = $cat_id; }
$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!-- Hero -->
<?php if(!$search && !$cat_id): ?>
<div class="bg-indigo-700 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to ModernShop</h1>
        <p class="text-lg md:text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">Discover the best products at unbeatable prices. Fast shipping and guaranteed quality.</p>
    </div>
</div>
<?php endif; ?>

<div class="container mx-auto px-4 py-8 flex flex-col md:flex-row gap-8">
    <aside class="w-full md:w-1/4">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Categories</h3>
            <ul class="space-y-2">
                <li><a href="index.php" class="block py-1 <?= !$cat_id ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-500' ?>">All Products</a></li>
                <?php foreach($categories as $c): ?>
                <li><a href="index.php?category=<?= $c['id'] ?>" class="block py-1 <?= $cat_id == $c['id'] ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-500' ?>"><?= htmlspecialchars($c['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>
    <main class="w-full md:w-3/4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800"><?= $search ? "Search: ".htmlspecialchars($search) : ($cat_id ? "Category Products" : "Featured Products") ?></h2>
            <span class="text-gray-500 text-sm"><?= count($products) ?> items</span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($products as $p): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col">
                <a href="product_detail.php?id=<?= $p['id'] ?>" class="block relative pt-[100%] bg-gray-100 overflow-hidden">
                    <img src="<?= strpos($p['image'], 'http') === 0 ? $p['image'] : BASE_URL.'/uploads/'.$p['image'] ?>" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition duration-500">
                </a>
                <div class="p-5 flex-col flex flex-1">
                    <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-1"><?= htmlspecialchars($p['cat_name']) ?></span>
                    <a href="product_detail.php?id=<?= $p['id'] ?>" class="text-lg font-bold text-gray-800 mb-2 hover:text-indigo-600 line-clamp-1"><?= htmlspecialchars($p['name']) ?></a>
                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <span class="text-xl font-bold text-gray-900">฿<?= number_format($p['price'], 2) ?></span>
                        <?php if($p['stock'] > 0): ?>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button class="bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition">Add to Cart</button>
                        </form>
                        <?php else: ?>
                        <span class="text-red-500 text-sm font-bold bg-red-50 px-3 py-1.5 rounded-lg">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if(empty($products)): ?>
            <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                <p class="text-gray-500 text-lg">No products found.</p>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php require 'includes/footer.php'; ?>