<?php
require 'includes/header.php';
require 'includes/navbar.php';

if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$action = $_POST['action'] ?? '';

if($action === 'add') {
    $id = $_POST['id'] ?? 0;
    $qty = (int)($_POST['qty'] ?? 1);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    if($p && $p['stock'] >= $qty) {
        if(isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$id] = ['name'=>$p['name'], 'price'=>$p['price'], 'qty'=>$qty, 'image'=>$p['image']];
        }
        $_SESSION['flash'] = ['msg' => 'Added to cart', 'type' => 'success'];
    }
    header("Location: cart.php"); exit;
} elseif($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $qty = (int)($_POST['qty'] ?? 1);
    if($qty > 0 && isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] = $qty;
    }
    header("Location: cart.php"); exit;
} elseif($action === 'remove') {
    $id = $_POST['id'] ?? 0;
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php"); exit;
} elseif($action === 'checkout') {
    if(!isset($_SESSION['user_id'])) {
        $_SESSION['flash'] = ['msg' => 'Please login first', 'type' => 'warning'];
        header("Location: login.php"); exit;
    }
    if(!empty($_SESSION['cart'])) {
        try {
            $pdo->beginTransaction();
            $total = 0;
            foreach($_SESSION['cart'] as $item) $total += $item['price'] * $item['qty'];
            
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?,?)");
            $stmt->execute([$_SESSION['user_id'], $total]);
            $oid = $pdo->lastInsertId();
            
            $s1 = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)");
            $s2 = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            foreach($_SESSION['cart'] as $pid => $item) {
                $s1->execute([$oid, $pid, $item['qty'], $item['price']]);
                $s2->execute([$item['qty'], $pid]);
            }
            $pdo->commit();
            $_SESSION['cart'] = [];
            $_SESSION['flash'] = ['msg' => 'Order placed successfully!', 'type' => 'success'];
            header("Location: my_orders.php"); exit;
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = "Checkout failed: ".$e->getMessage();
        }
    }
}
$total = 0;
?>
<div class="container mx-auto px-4 py-10 max-w-5xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Shopping Cart</h1>
    <?php if(!empty($error)): ?><div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6"><?= $error ?></div><?php endif; ?>
    
    <?php if(empty($_SESSION['cart'])): ?>
        <div class="bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="text-gray-500 text-lg mb-6">Your cart is empty</p>
            <a href="index.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg transition">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <?php foreach($_SESSION['cart'] as $id => $item): $itemTotal = $item['price'] * $item['qty']; $total += $itemTotal; ?>
                    <div class="flex items-center p-6 border-b border-gray-50 last:border-0">
                        <img src="<?= strpos($item['image'], 'http') === 0 ? $item['image'] : BASE_URL.'/uploads/'.$item['image'] ?>" class="w-20 h-20 object-cover rounded-lg border border-gray-100">
                        <div class="ml-4 flex-1">
                            <h3 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($item['name']) ?></h3>
                            <div class="text-indigo-600 font-medium">฿<?= number_format($item['price'], 2) ?></div>
                        </div>
                        <div class="flex items-center gap-4">
                            <form method="POST" class="flex items-center bg-gray-50 rounded-lg border border-gray-200">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="w-16 bg-transparent text-center py-2 outline-none">
                                <button class="px-3 text-indigo-600 hover:text-indigo-800 font-medium border-l border-gray-200">Update</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button class="p-2 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="w-full lg:w-1/3">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 pb-4 border-b">Order Summary</h3>
                    <div class="flex justify-between text-gray-600 mb-4"><span>Subtotal</span><span>฿<?= number_format($total, 2) ?></span></div>
                    <div class="flex justify-between text-gray-600 mb-6 pb-6 border-b"><span>Shipping</span><span class="text-green-500 font-medium">Free</span></div>
                    <div class="flex justify-between text-xl font-bold text-gray-900 mb-8"><span>Total</span><span>฿<?= number_format($total, 2) ?></span></div>
                    <form method="POST">
                        <input type="hidden" name="action" value="checkout">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-sm transition text-lg">Proceed to Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require 'includes/footer.php'; ?>