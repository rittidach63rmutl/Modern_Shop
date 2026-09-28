<?php
require 'header.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?"); $stmt->execute([$id]); $p = $stmt->fetch();
if(!$p) { header("Location: products.php"); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? ''; $cat = $_POST['category_id'] ?? 0;
    $price = $_POST['price'] ?? 0; $stock = $_POST['stock'] ?? 0;
    $desc = $_POST['description'] ?? ''; $img = $p['image'];
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($_FILES['image']['tmp_name']);
        if(in_array($ext, $allowed) && strpos($mime, 'image/') === 0) {
            $img = uniqid().'.'.$ext;
            if(move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/'.$img)) {
                if($p['image'] && file_exists('../uploads/'.$p['image']) && strpos($p['image'], 'http') !== 0) unlink('../uploads/'.$p['image']);
            }
        } else { $error = "Invalid image format."; }
    }
    
    if(empty($error)) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, description=?, image=? WHERE id=?");
        $stmt->execute([$name, $cat, $price, $stock, $desc, $img, $id]);
        $_SESSION['flash'] = ['msg'=>'Product updated', 'type'=>'success'];
        header("Location: products.php"); exit;
    }
}
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<div class="max-w-2xl bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Edit Product</h2>
    <?php if(!empty($error)): ?><div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm"><?= $error ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Name</label><input type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" required class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:border-indigo-500"></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Category</label><select name="category_id" required class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:border-indigo-500"><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $c['id']==$p['category_id']?'selected':'' ?>><?= $c['name'] ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Stock</label><input type="number" name="stock" value="<?= $p['stock'] ?>" required min="0" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:border-indigo-500"></div>
        </div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Price</label><input type="number" name="price" value="<?= $p['price'] ?>" required step="0.01" min="0" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:border-indigo-500"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Description</label><textarea name="description" rows="4" class="w-full border border-slate-300 rounded-lg p-2.5 outline-none focus:border-indigo-500"><?= htmlspecialchars($p['description']) ?></textarea></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Image</label>
        <?php if($p['image']): ?><img src="<?= strpos($p['image'], 'http') === 0 ? $p['image'] : BASE_URL.'/uploads/'.$p['image'] ?>" class="w-20 h-20 rounded mb-2 object-cover border"><?php endif; ?>
        <input type="file" name="image" accept="image/*" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50"><span class="text-xs text-slate-500 mt-1 block">Leave empty to keep current image</span></div>
        <div class="pt-4 flex gap-3"><button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium">Update Product</button><a href="products.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a></div>
    </form>
</div>
<?php require 'footer.php'; ?>