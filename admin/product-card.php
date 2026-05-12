<?php 
function print_card($src, $categoryName, $productName, $description, $productPrice, $discountedPrice, $stock, $productId){
    global $base_url;
    
    // Determine the actual image source
    // In admin/products.php, $src is passed as "uploads/".$value["file"]
    // Since we are in admin/, this is correct.
    
    $finalPrice = $discountedPrice ?: $productPrice;
    $hasDiscount = ($discountedPrice > 0 && $discountedPrice < $productPrice);
?>
<div class="glass-dark rounded-3xl overflow-hidden border border-white/5 hover:border-gold/30 transition-all duration-500 luxury-shadow group flex flex-col h-full">
    <!-- Image Wrapper -->
    <div class="relative aspect-[4/5] overflow-hidden">
        <img src="<?php echo $src; ?>" alt="<?php echo $productName; ?>" 
             class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110 opacity-80 group-hover:opacity-100">
        
        <!-- Status Badges -->
        <div class="absolute top-4 left-4 flex flex-col gap-2">
            <span class="px-3 py-1 bg-black/40 backdrop-blur-md border border-white/10 rounded-full text-[8px] font-black uppercase tracking-widest text-white">
                <?php echo $categoryName; ?>
            </span>
            <?php if ($hasDiscount): ?>
            <span class="px-3 py-1 bg-gold text-black rounded-full text-[8px] font-black uppercase tracking-widest">
                Special Offer
            </span>
            <?php endif; ?>
        </div>

        <!-- Stock Indicator -->
        <div class="absolute bottom-4 right-4">
            <div class="px-3 py-1 bg-black/60 backdrop-blur-md rounded-lg border border-white/10">
                <p class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">Qty: <span class="<?php echo $stock > 0 ? 'text-green-400' : 'text-red-400'; ?>"><?php echo $stock; ?></span></p>
            </div>
        </div>

        <!-- Overlay Actions -->
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center gap-4 backdrop-blur-sm">
            <a href="index.php?page=editproduct&id=<?php echo $productId; ?>" class="w-12 h-12 bg-white text-black rounded-full flex items-center justify-center hover:bg-gold transition-colors">
                <i class="fas fa-edit"></i>
            </a>
            <a href="delete-product.php?id=<?php echo $productId; ?>" 
               onclick="return confirm('Archive this masterpiece permanently?')"
               class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                <i class="fas fa-trash"></i>
            </a>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-6 flex flex-col flex-grow space-y-4">
        <div class="space-y-1">
            <h3 class="font-serif text-xl text-white truncate"><?php echo $productName; ?></h3>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest line-clamp-1"><?php echo $description; ?></p>
        </div>
        
        <div class="pt-4 border-t border-white/5 flex justify-between items-end mt-auto">
            <div>
                <?php if ($hasDiscount): ?>
                    <p class="text-[10px] line-through text-gray-600 mb-1">$<?php echo number_format($productPrice, 2); ?></p>
                <?php endif; ?>
                <p class="text-xl font-serif text-gold italic">$<?php echo number_format($finalPrice, 2); ?></p>
            </div>
            <div class="flex gap-2">
                <span class="w-2 h-2 rounded-full <?php echo $stock > 0 ? 'bg-green-500' : 'bg-red-500'; ?> animate-pulse"></span>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
