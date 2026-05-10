<?php
require_once("../auth/session.php");
// check_auth(); // Ensure only admins can access this (logic should be in check_auth or separate)
include("../configshoppingstore.php");

$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $discountedPrice = !empty($_POST['discountedPrice']) ? $_POST['discountedPrice'] : null;
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $imageName = $_FILES['productImage']['name'];
    $imageTmp = $_FILES['productImage']['tmp_name'];
    $uploadDir = './uploads/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $genetratedName = time() . '_' . $imageName;
    $imagePath = $uploadDir . $genetratedName;

    try {
        $stmt = $conn->prepare("INSERT INTO `product`(`category`, `productName`, `price`, `discountedPrice`, `stock`, `description`, `file`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $res = $stmt->execute([$category, $productName, $price, $discountedPrice, $stock, $description, $genetratedName]);

        if ($res) {
            if (move_uploaded_file($imageTmp, $imagePath)) {
                $success = true;
            }
        }
    } catch (\Throwable $th) {
        $error = "Error adding product: " . $th->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curate New Piece | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { serif: ['Playfair Display', 'serif'], sans: ['Inter', 'sans-serif'] },
                    colors: { luxury: '#1a1a1a', gold: '#c5a059', silver: '#f8f9fa' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');
        body { background-color: #0a0a0a; color: #fff; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        input, select, textarea { 
            background: rgba(255,255,255,0.05) !important; 
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: white !important;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #c5a059 !important;
            outline: none;
        }
    </style>
</head>
<body class="font-sans">
    <div class="min-h-screen flex items-center justify-center py-20 px-6">
        <div class="max-w-4xl w-full glass rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-12 border-b border-white/5 flex justify-between items-center">
                <div>
                    <h1 class="font-serif text-4xl text-white">Curate <span class="text-gold italic">New Piece</span></h1>
                    <p class="text-gray-500 text-xs uppercase tracking-widest mt-2">Product Management Portal</p>
                </div>
                <a href="products.php" class="text-xs uppercase tracking-widest text-gray-400 hover:text-gold transition-colors">View All Products</a>
            </div>

            <form class="p-12 space-y-8" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Category</label>
                        <select name="category" required class="w-full px-6 py-4 rounded-xl text-sm">
                            <option value="">Select Category</option>
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                            <option value="Objects">Objects</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Product Name</label>
                        <input type="text" name="productName" required placeholder="e.g. Silk Velvet Gown" class="w-full px-6 py-4 rounded-xl text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Base Price (USD)</label>
                        <input type="number" name="price" step="0.01" required placeholder="0.00" class="w-full px-6 py-4 rounded-xl text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Discounted Price (Optional)</label>
                        <input type="number" name="discountedPrice" step="0.01" placeholder="0.00" class="w-full px-6 py-4 rounded-xl text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Initial Stock</label>
                        <input type="number" name="stock" required placeholder="0" class="w-full px-6 py-4 rounded-xl text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Product Image</label>
                        <input type="file" name="productImage" required class="w-full px-6 py-4 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gold file:text-white hover:file:bg-opacity-80">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest font-black text-gray-500">Description</label>
                    <textarea name="description" rows="4" required placeholder="Enter the story behind this piece..." class="w-full px-6 py-4 rounded-xl text-sm"></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-gold text-white text-[10px] font-black uppercase tracking-[0.5em] rounded-xl hover:shadow-[0_0_40px_rgba(197,160,89,0.3)] transition-all transform hover:-translate-y-1">
                        Add to Collection
                    </button>
                </div>
            </form>

            <?php if ($success): ?>
                <div class="p-6 bg-green-500/10 border-t border-green-500/20 text-green-500 text-center text-xs uppercase tracking-widest font-bold">
                    Piece successfully added to the digital archives.
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="p-6 bg-red-500/10 border-t border-red-500/20 text-red-500 text-center text-xs uppercase tracking-widest font-bold">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>