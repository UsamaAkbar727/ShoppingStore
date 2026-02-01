<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: "#3b82f6",
          secondary: "#1e40af",
          accent: "#10b981",
          dark: "#1f2937",
          light: "#f8fafc",
          gradientStart: "#f3f4f6",
          gradientEnd: "#e5e7eb"
        },
        animation: {
          'float': 'float 3s ease-in-out infinite',
          'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          'gradient': 'gradient 8s ease infinite',
        },
        keyframes: {
          float: {
            '0%, 100%': { transform: 'translateY(0)' },
            '50%': { transform: 'translateY(-5px)' },
          },
          gradient: {
            '0%, 100%': { 'background-position': '0% 50%' },
            '50%': { 'background-position': '100% 50%' },
          }
        }
      }
    }
  }
</script>

<head>
    <style>
        body {
            background: linear-gradient(-45deg, #f3f4f6, #e5e7eb, #f9fafb, #f0fdf4);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
        }
        
        .ani {
            animation: appear 0.4s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes appear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .discount-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #ef4444, #f59e0b);
            color: white;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
            z-index: 10;
            animation: float 3s ease-in-out infinite;
        }
        
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        
        .product-card {
            background: white;
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }
        
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-added {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #16a34a;
            border: 1px solid #86efac;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            transition: all 0.3s ease;
        }
        
        .category-badge:hover {
            transform: scale(1.05);
        }
        
        .product-image {
            transition: transform 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
    </style>
</head>

<?php
function print_card_user($imagePath, $category, $name, $description, $price, $discountedPrice, $stock, $id, $iscarted = false)
{
  // Calculate discount percentage
  $discountPercent = round((($price - $discountedPrice) / $price) * 100);
  
  // Determine stock status
  $stockClass = ($stock < 5) ? 'text-red-500 font-medium animate-pulse' : 'text-gray-500';
  $stockText = ($stock < 5) ? 'Only '.$stock.' left' : 'In stock';
  $stockIcon = ($stock < 5) ? 'fa-exclamation-circle' : 'fa-check-circle';
  
  if ($iscarted) {
    $cartdata = '<button class="btn-added w-full mt-auto py-2.5 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Added to Cart
                </button>';
  } else {
    $cartdata = '<a href="?action=cart&id=' . $id . '" class="btn-primary w-full mt-auto py-2.5 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </a>';
  }

  echo '
    <div class="ani product-card flex flex-col h-full border border-gray-100">
        <div class="relative aspect-square overflow-hidden">
            <img src="' . $imagePath . '" alt="' . $name . '" class="product-image w-full h-[300px] object-cover">
            <div class="discount-badge">' . $discountPercent . '% OFF</div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-black/5 to-transparent"></div>
        </div>
        <div class="p-5 flex flex-col flex-grow">
            <div class="flex justify-between items-start mb-3">
                <span class="category-badge text-xs font-medium px-3 py-1 rounded-full">' . $category . '</span>
                <span class="text-xs ' . $stockClass . '"><i class="fas ' . $stockIcon . ' mr-1"></i> ' . $stockText . '</span>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2 hover:text-primary transition-colors">' . $name . '</h3>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">' . $description . '</p>
            
            <div class="mt-auto">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-xl font-bold text-gray-900">Rs. ' . number_format($discountedPrice) . '</span>
                        <span class="ml-2 text-sm line-through text-gray-400">Rs. ' . number_format($price) . '</span>
                    </div>
                    <div class="flex text-amber-400 text-sm">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                
                <div class="flex flex-col gap-3">
                    ' . $cartdata . '
                    <a href="buy-product.php?action=buy&id=' . $id . '" class="btn-accent w-full py-2.5 rounded-lg flex items-center justify-center gap-2">
                        <i class="fas fa-bolt animate-pulse"></i> Buy Now
                    </a>
                </div>
            </div>
        </div>
    </div>';
}
?>