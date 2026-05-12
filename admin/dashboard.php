<?php
// Fetch some quick stats
$total_products = $conn->query("SELECT COUNT(*) FROM product")->fetchColumn();
$total_users = $conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
// Assuming orders table exists, if not fallback to 0
try {
    $total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
} catch (Exception $e) { $total_orders = 0; }
?>

<div class="space-y-12 animate-fade-in">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-2">
            <h2 class="font-serif text-5xl text-white">System Overview</h2>
            <p class="text-gray-500 text-sm tracking-[0.2em] uppercase font-bold">Atelier Digital Core V2.0</p>
        </div>
        <div class="px-6 py-3 glass-dark rounded-full flex items-center gap-4 border border-gold/20">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-gold">Server Synchronized</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="glass-dark p-8 rounded-3xl space-y-6 hover:border-gold/30 transition-all duration-500 luxury-shadow group">
            <div class="flex justify-between items-start">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center group-hover:bg-gold transition-all duration-500">
                    <i class="fas fa-box text-gold group-hover:text-black"></i>
                </div>
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">+12% Monthly</span>
            </div>
            <div>
                <p class="text-3xl font-serif text-white"><?php echo $total_products; ?></p>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-1">Total Inventory Items</p>
            </div>
        </div>

        <div class="glass-dark p-8 rounded-3xl space-y-6 hover:border-gold/30 transition-all duration-500 luxury-shadow group">
            <div class="flex justify-between items-start">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center group-hover:bg-gold transition-all duration-500">
                    <i class="fas fa-users text-gold group-hover:text-black"></i>
                </div>
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">+5% Growth</span>
            </div>
            <div>
                <p class="text-3xl font-serif text-white"><?php echo $total_users; ?></p>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-1">Registered Clients</p>
            </div>
        </div>

        <div class="glass-dark p-8 rounded-3xl space-y-6 hover:border-gold/30 transition-all duration-500 luxury-shadow group">
            <div class="flex justify-between items-start">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center group-hover:bg-gold transition-all duration-500">
                    <i class="fas fa-chart-line text-gold group-hover:text-black"></i>
                </div>
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Active</span>
            </div>
            <div>
                <p class="text-3xl font-serif text-white"><?php echo $total_orders; ?></p>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-1">Processable Orders</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions or Logs -->
    <div class="glass-dark p-10 rounded-3xl luxury-shadow border border-white/5">
        <div class="flex justify-between items-center mb-10">
            <h3 class="font-serif text-2xl">Quick Actions</h3>
            <a href="index.php?page=products" class="text-[10px] font-black uppercase tracking-widest text-gold hover:text-white transition">View All Products</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="index.php?page=addproduct" class="p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-gold/50 transition-all group flex flex-col items-center text-center gap-4">
                <i class="fas fa-plus text-gold text-xl group-hover:scale-125 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">New Entry</span>
            </a>
            <button class="p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-gold/50 transition-all group flex flex-col items-center text-center gap-4">
                <i class="fas fa-file-export text-gold text-xl group-hover:scale-125 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Export DB</span>
            </button>
            <button class="p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-gold/50 transition-all group flex flex-col items-center text-center gap-4">
                <i class="fas fa-shield-alt text-gold text-xl group-hover:scale-125 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Sys Audit</span>
            </button>
            <a href="../index.php" class="p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-gold/50 transition-all group flex flex-col items-center text-center gap-4">
                <i class="fas fa-eye text-gold text-xl group-hover:scale-125 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Live Site</span>
            </a>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
