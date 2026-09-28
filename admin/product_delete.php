<?php
require 'auth_check.php';
$id = $_GET['id'] ?? 0;
if($id) {
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?"); $stmt->execute([$id]); $p = $stmt->fetch();
    if($p) {
        if($p['image'] && file_exists('../uploads/'.$p['image']) && strpos($p['image'], 'http') !== 0) unlink('../uploads/'.$p['image']);
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        $_SESSION['flash'] = ['msg'=>'Product deleted', 'type'=>'success'];
    }
}
header("Location: products.php"); exit;
?>