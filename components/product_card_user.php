<?php
function print_card_user($imagePath, $category, $name, $description, $price, $discountedPrice, $stock, $id, $iscarted = false, $iswishlisted = false)
{
    global $base_url;

    $discountPercent = $price > 0 && $discountedPrice < $price ? round((($price - $discountedPrice) / $price) * 100) : 0;
    $finalPrice = $discountedPrice ?: $price;

    $detailsUrl = $base_url . "product-details.php?id=" . $id;

    $cartBtn = $iscarted
        ? '<button class="flex-grow bg-white/10 text-gold py-4 px-4 text-[8px] font-black uppercase tracking-[0.2em] rounded-xl cursor-default backdrop-blur-md border border-white/5">In Archive</button>'
        : '<button onclick="addToCartAjax(' . $id . ')" id="cart-btn-' . $id . '" class="flex-grow bg-white text-luxury py-4 px-4 text-[8px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-gold hover:text-white transition-all duration-500 text-center shadow-lg">Add to Bag</button>';

    $wishlistIcon = $iswishlisted ? 'fas fa-heart text-gold' : 'far fa-heart';
    $wishlistAction = $iswishlisted ? 'unwishlist' : 'wishlist';

    echo '
    <div class="glass rounded-2xl overflow-hidden group transition-all duration-500 hover:shadow-[0_20px_50px_rgba(197,160,89,0.2)] flex flex-col h-full">
        <!-- Product Image -->
        <a href="' . $detailsUrl . '" class="relative overflow-hidden aspect-[4/5] block">
            <img src="' . $imagePath . '" alt="' . $name . '" class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110">
            
            <!-- Wishlist Button -->
            <button onclick="event.preventDefault(); toggleWishlistAjax(' . $id . ', this)" class="absolute top-4 right-4 w-10 h-10 bg-black/20 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-white hover:bg-gold hover:text-white transition-all duration-500 z-10">
                <i class="' . $wishlistIcon . ' text-[12px]"></i>
            </button>

            <!-- Discount Badge -->
            ' . ($discountPercent > 0 ? '<div class="absolute bottom-4 left-4 bg-gold text-white text-[8px] px-3 py-1 font-black tracking-widest uppercase rounded-full backdrop-blur-md shadow-lg">' . $discountPercent . '% Rare</div>' : '') . '
            
            <!-- Stock Badge -->
            <div class="absolute top-4 left-4">
                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest ' . ((int) $stock > 0 ? 'bg-white/10 text-white' : 'bg-red-500/20 text-red-500') . ' backdrop-blur-md border border-white/10">
                    ' . ((int) $stock > 0 ? 'Limited' : 'Exhausted') . '
                </span>
            </div>
        </a>

        <!-- Product Info -->
        <div class="p-8 flex flex-col flex-grow space-y-4">
            <div class="space-y-1">
                <span class="text-[10px] uppercase tracking-[0.3em] text-gold font-black">' . htmlspecialchars($category) . '</span>
                <a href="' . $detailsUrl . '"><h3 class="font-serif text-2xl text-white leading-tight group-hover:text-gold transition-colors duration-500">' . htmlspecialchars($name) . '</h3></a>
            </div>
            
            <p class="text-sm text-gray-400 font-light leading-relaxed flex-grow line-clamp-2">' . htmlspecialchars($description) . '</p>
            
            <div class="pt-4 border-t border-white/10">
                <div class="flex items-end justify-between mb-6">
                    <div>
                        ' . ($price > $finalPrice ? '<p class="text-[10px] line-through text-gray-500 uppercase tracking-widest mb-1">$' . number_format($price, 2) . '</p>' : '') . '
                        <p class="text-2xl font-serif text-white italic">$' . number_format($finalPrice, 2) . '</p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    ' . $cartBtn . '
                    <a href="' . $base_url . 'checkout.php?product_id=' . $id . '&qty=1" class="w-14 h-14 flex items-center justify-center border border-white/10 text-white rounded-xl hover:bg-gold hover:border-gold transition-all duration-500 group/icon shadow-sm" title="Quick Checkout">
                        <i class="fas fa-bolt text-xs group-hover/icon:scale-110 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    ';
}
?>