<?php
include("../configshoppingstore.php");

$productId = $_GET['id'] ?? null;
$message = "";
$messageType = "";

if (!$productId) {
    header("Location: index.php?page=products");
    exit();
}

// Fetch existing data
try {
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    if (!$product) {
        header("Location: index.php?page=products");
        exit();
    }
} catch (Exception $e) {
    die("Database Error");
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $discountedPrice = $_POST['discountedPrice'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $fileName = $product['file'];

    if (!empty($_FILES['productImage']['name'])) {
        $fileName = time() . "_" . $_FILES['productImage']['name'];
        move_uploaded_file($_FILES['productImage']['tmp_name'], "uploads/" . $fileName);
    }

    try {
        $stmt = $conn->prepare("UPDATE product SET category=?, productName=?, price=?, discountedPrice=?, stock=?, description=?, file=? WHERE id = ?");
        $stmt->execute([$category, $productName, $price, $discountedPrice, $stock, $description, $fileName, $productId]);
        $message = "Registry updated successfully.";
        $messageType = "success";
        
        // Refresh product data
        $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
    } catch (Exception $e) {
        $message = "Update Error: " . $e->getMessage();
        $messageType = "error";
    }
}
?>

<div class="max-w-4xl mx-auto space-y-12 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-2">
            <h2 class="font-serif text-5xl text-white">Refine Masterpiece</h2>
            <p class="text-gray-500 text-sm tracking-[0.2em] uppercase font-bold">Editing: <?php echo htmlspecialchars($product['productName']); ?></p>
        </div>
        <a href="index.php?page=products" class="text-[10px] font-black uppercase tracking-widest text-gold border-b border-gold/20 pb-1 hover:text-white hover:border-white transition-all">Back to Inventory</a>
    </div>

    <!-- Feedback Message -->
    <?php if ($message): ?>
        <div class="p-6 rounded-2xl <?php echo $messageType == 'success' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20'; ?> border flex items-center gap-4">
            <i class="fas <?php echo $messageType == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <span class="text-sm font-bold tracking-wide"><?php echo $message; ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="glass-dark p-10 md:p-16 rounded-[40px] luxury-shadow space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Basic Info -->
            <div class="space-y-6">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gold font-black border-l-2 border-gold pl-4">Identification</p>
                
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Product Name</label>
                    <input type="text" name="productName" required value="<?php echo htmlspecialchars($product['productName']); ?>"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Category</label>
                    <select name="category" required class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all appearance-none">
                        <option value="Men" <?php echo $product['category'] == 'Men' ? 'selected' : ''; ?> class="bg-luxury">Men</option>
                        <option value="Women" <?php echo $product['category'] == 'Women' ? 'selected' : ''; ?> class="bg-luxury">Women</option>
                        <option value="Objects" <?php echo $product['category'] == 'Objects' ? 'selected' : ''; ?> class="bg-luxury">Objects</option>
                    </select>
                </div>
            </div>

            <!-- Commercial Info -->
            <div class="space-y-6">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gold font-black border-l-2 border-gold pl-4">Valuation</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Retail Price ($)</label>
                        <input type="number" step="0.01" name="price" required value="<?php echo $product['price']; ?>"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Discounted ($)</label>
                        <input type="number" step="0.01" name="discountedPrice" value="<?php echo $product['discountedPrice']; ?>"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Current Stock Level</label>
                    <input type="number" name="stock" required value="<?php echo $product['stock']; ?>"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                </div>
            </div>
        </div>

        <!-- Media & Narrative -->
        <div class="space-y-6">
            <p class="text-[10px] uppercase tracking-[0.3em] text-gold font-black border-l-2 border-gold pl-4">Media & Narrative</p>
            
            <div class="space-y-2">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Product Narrative</label>
                <textarea name="description" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Update Visual</label>
                    <input type="file" name="productImage" id="file-upload" class="hidden">
                    <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 bg-white/5 border-2 border-dashed border-white/10 rounded-2xl cursor-pointer hover:border-gold/50 transition-all">
                        <i class="fas fa-camera text-gray-600 text-2xl mb-2"></i>
                        <span class="text-[8px] text-gray-500 uppercase tracking-widest font-bold">Replace Current Media</span>
                    </label>
                </div>
                <div class="space-y-2 text-center">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Current Preview</label>
                    <div class="h-32 w-full rounded-2xl overflow-hidden border border-white/10">
                        <img src="uploads/<?php echo $product['file']; ?>" class="w-full h-full object-cover opacity-50">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-6">
            <button type="submit" class="w-full py-6 bg-gold text-black text-[10px] font-black uppercase tracking-[0.4em] rounded-2xl hover:shadow-[0_0_40px_rgba(197,160,89,0.3)] transition-all">
                Save Modifications
            </button>
        </div>
    </form>
</div>