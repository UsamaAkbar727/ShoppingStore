<?php
function print_cart_card_user($src, $category, $name, $description, $price, $discountedPrice, $stock, $id)
{
    echo '<div class="bg-white shadow-md rounded-lg overflow-hidden">';
    echo '<img src="' . $src . '" class="w-full h-48 object-cover" alt="' . $name . '">';
    echo '<div class="p-4">';
    echo '<h3 class="text-lg font-semibold text-gray-800">' . $name . '</h3>';
    echo '<p class="text-sm text-gray-600 mb-2">' . $description . '</p>';
    echo '<p class="text-sm text-gray-500 line-through">Rs. ' . $price . '</p>';
    echo '<p class="text-lg font-bold text-blue-600">Rs. ' . $discountedPrice . '</p>';

    // Buttons
    echo '<div class="mt-4 flex flex-col gap-2">';
    echo '<a href="?action=uncart&id=' . $id . '" class="block bg-red-600 hover:bg-red-700 text-white text-center py-2 rounded-md transition">❌ Remove from Cart</a>';
    echo '<a href="buy-product.php?action=buy&id=' . $id . '" class="block bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-md transition">🛒 Proceed to Checkout</a>';
    echo '</div>';

    echo '</div></div>';
}
