<?php
$current_page_sidebar = $_GET['page'] ?? 'dashboard';
?>
<div class="w-72 bg-[#0f0f0f] text-white min-h-screen flex flex-col border-r border-white/5 shadow-2xl">
    <!-- Admin Brand -->
    <div class="p-8 border-b border-white/5">
        <h2 class="font-serif text-2xl tracking-tight text-white flex items-center gap-3">
            <span class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center text-black shadow-[0_0_20px_rgba(197,160,89,0.3)]">
                <i class="fas fa-crown text-sm"></i>
            </span>
            <div class="flex flex-col">
                <span class="text-sm font-black uppercase tracking-widest leading-none">Fashion</span>
                <span class="text-[10px] text-gold uppercase tracking-[0.4em] font-bold mt-1 italic">Atelier Admin</span>
            </div>
        </h2>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow p-6 space-y-2">
        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black mb-6 px-4">Core Management</p>
        
        <a href="index.php?page=dashboard" class="flex items-center gap-4 px-4 py-4 rounded-xl transition-all duration-300 group <?php echo $current_page_sidebar == 'dashboard' ? 'bg-gold text-black shadow-lg' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
            <i class="fas fa-th-large w-5 <?php echo $current_page_sidebar == 'dashboard' ? '' : 'group-hover:text-gold'; ?>"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="index.php?page=products" class="flex items-center gap-4 px-4 py-4 rounded-xl transition-all duration-300 group <?php echo $current_page_sidebar == 'products' ? 'bg-gold text-black shadow-lg' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
            <i class="fas fa-box w-5 <?php echo $current_page_sidebar == 'products' ? '' : 'group-hover:text-gold'; ?>"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Inventory</span>
        </a>

        <a href="index.php?page=addproduct" class="flex items-center gap-4 px-4 py-4 rounded-xl transition-all duration-300 group <?php echo $current_page_sidebar == 'addproduct' ? 'bg-gold text-black shadow-lg' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
            <i class="fas fa-plus-circle w-5 <?php echo $current_page_sidebar == 'addproduct' ? '' : 'group-hover:text-gold'; ?>"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Add Product</span>
        </a>

        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black mb-6 mt-10 px-4">Sales & Users</p>

        <a href="index.php?page=orders" class="flex items-center gap-4 px-4 py-4 rounded-xl transition-all duration-300 group <?php echo $current_page_sidebar == 'orders' ? 'bg-gold text-black shadow-lg' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
            <i class="fas fa-shopping-cart w-5 <?php echo $current_page_sidebar == 'orders' ? '' : 'group-hover:text-gold'; ?>"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Orders</span>
        </a>

        <a href="index.php?page=users" class="flex items-center gap-4 px-4 py-4 rounded-xl transition-all duration-300 group <?php echo $current_page_sidebar == 'users' ? 'bg-gold text-black shadow-lg' : 'hover:bg-white/5 text-gray-400 hover:text-white'; ?>">
            <i class="fas fa-users w-5 <?php echo $current_page_sidebar == 'users' ? '' : 'group-hover:text-gold'; ?>"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Clients</span>
        </a>
    </nav>

    <!-- Footer Action -->
    <div class="p-6 border-t border-white/5">
        <a href="../index.php" class="flex items-center gap-4 px-4 py-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all mb-2">
            <i class="fas fa-external-link-alt w-5"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Main Store</span>
        </a>
        <a href="logout.php" class="flex items-center gap-4 px-4 py-4 rounded-xl text-red-400 hover:text-white hover:bg-red-500/10 transition-all">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Exit Portal</span>
        </a>
    </div>
</div>