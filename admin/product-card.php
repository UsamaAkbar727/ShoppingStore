
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: "#3b82f6",
          secondary: "#1e40af"
        }
      }
    }
  }
</script>


<?php 
function print_card($src, $categoryName, $productName, $description, $productPrice, $discountedPrice, $stock, $productId){

    $adddiscountPrice = "<span class='text-lg font-bold text-blue-600'>".$productPrice."</span>";
    $originalPrice = "";

    if($discountedPrice){
        $adddiscountPrice = '<span class="text-lg font-bold text-blue-600">'.$discountedPrice.'</span>';
        $originalPrice = '<span class="text-sm text-gray-400 line-through ml-2">'.$productPrice.'</span>';
    }

    echo '<div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 w-full max-w-sm m-4 border border-gray-100">
  <div class="relative">
    <img src="'.$src.'" alt="Product Image" class="w-full h-64 object-cover" loading="lazy">
    
    <div class="absolute top-3 left-3 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
      '.$categoryName.'
    </div>

    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
      <span class="bg-white text-primary px-4 py-2 rounded-full font-medium transition duration-300 shadow-md">
        <i class="fas fa-cog mr-2"></i> Admin Panel
      </span>
    </div>
  </div>

  <div class="p-4">
    <h2 class="text-lg font-bold text-gray-800 mb-1">'.$productName.'</h2>

    <p class="text-sm text-gray-600 mb-3">'.$description.'</p>

    <div class="flex items-center justify-between mb-2">
      <div>
        '.$adddiscountPrice.'
        '.$originalPrice.'
      </div>
      <span class="text-sm text-green-600 font-medium">Stock: <strong>'.$stock.'</strong></span>
    </div>

    <div class="grid grid-cols-2 gap-3 mt-4">
      <a href="edit-product.php?id='.$productId.'" class="bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg text-center font-semibold transition duration-300">
        <i class="fas fa-edit mr-1"></i> Edit
      </a>
      <a href="delete-product.php?id='.$productId.'" onclick="return confirm(\'Are you sure you want to delete this product?\')" class="bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-center font-semibold transition duration-300">
        <i class="fas fa-trash-alt mr-1"></i> Delete
      </a>
    </div>
  </div>
</div>';
}
?>
