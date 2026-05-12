<?php
include("./product-card.php");
include("../configshoppingstore.php");

$res_arr = [];
try {
    $data = $conn->prepare("SELECT * FROM `product` ORDER BY id DESC");
    $data->execute();
    $res = $data->fetchAll();
    if ($res) {
        $res_arr = $res;
    }
} catch (\Throwable $th) {
    error_log($th->getMessage());
}
?>

<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-2">
            <h2 class="font-serif text-5xl text-white">Inventory</h2>
            <p class="text-gray-500 text-sm tracking-[0.2em] uppercase font-bold">Manage your digital collection</p>
        </div>
        <a href="index.php?page=addproduct" class="px-8 py-4 bg-gold text-black text-[10px] font-black uppercase tracking-widest rounded-full hover:shadow-[0_0_20px_rgba(197,160,89,0.4)] transition-all flex items-center gap-3 group">
            <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i>
            Add New Item
        </a>
    </div>

    <!-- Products Grid -->
    <?php if (empty($res_arr)): ?>
        <div class="glass-dark p-20 rounded-3xl text-center space-y-6 border border-dashed border-white/10">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-box-open text-gray-600 text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-serif text-2xl text-white">Vault is Empty</h3>
                <p class="text-gray-500 text-sm">No products found in the database. Start by adding your first masterpiece.</p>
            </div>
            <a href="index.php?page=addproduct" class="inline-block text-gold text-[10px] font-black uppercase tracking-widest border-b border-gold pb-1 hover:text-white hover:border-white transition-all">Add First Product</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php
            foreach ($res_arr as $value) {
                // Determine image path - handling both absolute URLs and local files
                $imgPath = (strpos($value["file"], 'http') === 0) 
                    ? $value["file"] 
                    : "uploads/" . $value["file"];
                
                print_card(
                    $imgPath,
                    $value["category"],
                    $value["productName"],
                    $value["description"],
                    $value["price"],
                    $value["discountedPrice"],
                    $value["stock"],
                    $value["id"]
                );
            }
            ?>
        </div>
    <?php endif; ?>
</div>