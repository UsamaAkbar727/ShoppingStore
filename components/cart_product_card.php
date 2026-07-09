<?php
function print_cart_card_user($src, $category, $name, $description, $price, $discountedPrice, $stock, $id, $qty)
{
    global $base_url;
    $finalPrice = $discountedPrice ?: $price;

    echo '<div class="glass rounded-2xl overflow-hidden group transition-all duration-500 hover:shadow-[0_20px_50px_rgba(197,160,89,0.2)] flex flex-col h-full">';
    echo '  <div class="relative aspect-[4/5] overflow-hidden">';
    echo '    <img src="' . $src . '" class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110" alt="' . $name . '">';
    echo '    <div class="absolute top-4 left-4 bg-black/40 backdrop-blur-md px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest text-white border border-white/10">Qty: ' . $qty . '</div>';
    echo '    <div class="absolute top-4 right-4">';
    echo '      <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest ' . ((int) $stock > 0 ? 'bg-gold/20 text-gold' : 'bg-red-500/20 text-red-500') . ' backdrop-blur-md border border-white/10">';
    echo '        ' . ((int) $stock > 0 ? 'In Archive' : 'Exhausted');
    echo '      </span>';
    echo '    </div>';
    echo '  </div>';
    echo '  <div class="p-8 flex flex-col flex-grow space-y-4">';
    echo '    <div class="space-y-1">';
    echo '      <p class="text-[10px] text-gold uppercase tracking-[0.3em] font-black">' . htmlspecialchars($category) . '</p>';
    echo '      <h3 class="font-serif text-2xl text-white group-hover:text-gold transition-colors">' . htmlspecialchars($name) . '</h3>';
    echo '    </div>';
    echo '    <p class="text-gray-400 text-sm font-light leading-relaxed flex-grow line-clamp-2">' . htmlspecialchars($description) . '</p>';
    echo '    <div class="pt-4 border-t border-white/10 flex justify-between items-end">';
    echo '      <div>';
    echo '        ' . ($price > $finalPrice ? '<p class="text-[10px] text-gray-500 line-through uppercase tracking-widest mb-1">$' . number_format((float) $price, 2) . '</p>' : '') . '
                  <p class="text-2xl font-serif text-white italic">$' . number_format((float) $finalPrice, 2) . '</p>';
    echo '      </div>';
    echo '    </div>';
    echo '    <div class="pt-6 grid grid-cols-2 gap-4">';
    echo '      <button onclick="window.location.href=\'?action=uncart&id=' . $id . '\'" class="py-4 border border-red-500/30 text-red-500 text-[8px] font-black uppercase tracking-widest text-center rounded-xl hover:bg-red-500 hover:text-white transition-all duration-500">';
    echo '        <i class="fas fa-trash-alt mr-2"></i> Remove';
    echo '      </button>';
    echo '      <button onclick="window.location.href=\'' . $base_url . 'checkout.php?product_id=' . $id . '&qty=' . $qty . '\'" class="py-4 bg-gold text-white text-[8px] font-black uppercase tracking-widest text-center rounded-xl hover:shadow-[0_10px_20px_rgba(197,160,89,0.3)] transition-all duration-500 shadow-lg">';
    echo '        <i class="fas fa-shopping-bag mr-2"></i> Acquire';
    echo '      </button>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}
?>