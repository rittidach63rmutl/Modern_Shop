<?php
require 'includes/header.php';
require 'includes/navbar.php';
if(isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    if($email && $pass) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['flash'] = ['msg' => 'Welcome back!', 'type' => 'success'];
            header("Location: ".($user['role'] === 'admin' ? 'admin/index.php' : 'index.php')); exit;
        } else { $error = "Invalid email or password."; }
    }
}
?>
<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Login</h2>
        <?php if(!empty($error)): ?><div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm text-center border border-red-100"><?= $error ?></div><?php endif; ?>
        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full border-gray-300 border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full border-gray-300 border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition shadow-sm">Sign In</button>
        </form>
        <div class="text-center mt-6 text-gray-500 text-sm">
            Don't have an account? <a href="register.php" class="text-indigo-600 hover:underline font-medium">Register</a>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>