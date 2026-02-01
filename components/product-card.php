
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
function print_card($src,$categoryName,$productName,$description,$productPrice,$discountedPrice,$stock){

    $adddiscountPrice = "<span></span>";

    if($discountedPrice){
        $adddiscountPrice = '<span class="text-lg font-bold text-blue-600">'.$discountedPrice.'</span>';
    }

    echo '<div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 w-full max-w-sm m-4 border border-gray-100">
  <div class="relative">
    <img src='.$src.' alt="Product Image" class="w-full h-64 object-cover" loading="lazy">
    
    <div class="absolute top-3 left-3 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
      '.$categoryName.'
    </div>

    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
      <button class="bg-white text-primary px-4 py-2 rounded-full font-medium hover:bg-primary hover:text-white transition duration-300 shadow-md">
        <i class="fas fa-eye mr-2"></i> Quick View
      </button>
    </div>
  </div>

  <div class="p-4">
    <h2 class="text-lg font-bold text-gray-800 mb-1">'.$productName.'</h2>

    <p class="text-sm text-gray-600 mb-3">'.$description.'</p>

    <div class="flex items-center justify-between mb-2">
      <div>
        '.$adddiscountPrice.'
        <span class="text-sm text-gray-400 line-through ml-2">'.$productPrice.'</span>
      </div>
      <button class="text-gray-400 hover:text-red-500 transition">
        <i class="far fa-heart text-xl"></i>
      </button>
    </div>

    <p class="text-sm text-green-600 font-medium mb-3">In Stock: <span class="font-bold">'.$stock.'</span></p>

    <button class="w-full bg-primary hover:bg-secondary text-white font-semibold py-2 rounded-lg transition duration-300">
      <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
    </button>
  </div>
</div>
';
}
?>