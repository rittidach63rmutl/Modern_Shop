<nav class="bg-white shadow-sm border-b sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="<?= BASE_URL ?>/index.php" class="text-2xl font-bold text-indigo-600 tracking-tight">ModernShop</a>
        <div class="hidden md:flex flex-1 max-w-xl mx-8">
            <form action="<?= BASE_URL ?>/index.php" method="GET" class="w-full flex">
                <input type="text" name="search" placeholder="Search for products..." class="w-full border-y border-l border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-r-md hover:bg-indigo-700 transition">Search</button>
            </form>
        </div>
        <div class="hidden md:flex items-center space-x-6">
            <?php $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>
            <a href="<?= BASE_URL ?>/cart.php" class="text-gray-600 hover:text-indigo-600 relative flex items-center">
                <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Cart <span class="ml-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?= $cart_count ?></span>
            </a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/index.php" class="text-indigo-600 font-semibold hover:text-indigo-800">Admin</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/my_orders.php" class="text-gray-600 hover:text-indigo-600">My Orders</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logout.php" class="text-red-500 hover:text-red-700 font-medium">Logout (<?= htmlspecialchars($_SESSION['name']) ?>)</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login.php" class="text-gray-600 hover:text-indigo-600 font-medium">Login</a>
                <a href="<?= BASE_URL ?>/register.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition">Register</a>
            <?php endif; ?>
        </div>
        <button onclick="toggleMobileMenu()" class="md:hidden text-gray-600 hover:text-indigo-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t px-4 py-4 space-y-4">
        <form action="<?= BASE_URL ?>/index.php" method="GET" class="flex">
            <input type="text" name="search" placeholder="Search..." class="w-full border border-gray-300 rounded-l-md px-3 py-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 rounded-r-md">Go</button>
        </form>
        <a href="<?= BASE_URL ?>/cart.php" class="block text-gray-600 hover:text-indigo-600">Cart (<?= $cart_count ?>)</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin/index.php" class="block text-indigo-600 font-semibold">Admin Panel</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/my_orders.php" class="block text-gray-600 hover:text-indigo-600">My Orders</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/logout.php" class="block text-red-500">Logout (<?= htmlspecialchars($_SESSION['name']) ?>)</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login.php" class="block text-gray-600 hover:text-indigo-600">Login</a>
            <a href="<?= BASE_URL ?>/register.php" class="block text-indigo-600 font-semibold">Register</a>
        <?php endif; ?>
    </div>
</nav>