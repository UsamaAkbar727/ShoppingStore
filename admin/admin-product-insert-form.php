<?php
include("../configshoppingstore.php");

$message = "";
$messageType = "";

if (isset($_POST["submit"])) {
    $productName = $_POST["productName"];
    $category = $_POST["category"];
    $price = $_POST["price"];
    $discountedPrice = $_POST["discountedPrice"];
    $stock = $_POST["stock"];
    $description = $_POST["description"];
    
    $file = $_FILES["file"];
    $fileName = time() . "_" . $file["name"];
    $tempPath = $file["tmp_name"];
    $uploadPath = "uploads/" . $fileName;

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($tempPath, $uploadPath)) {
        try {
            $stmt = $conn->prepare("INSERT INTO `product` (`category`, `productName`, `price`, `discountedPrice`, `stock`, `description`, `file`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$category, $productName, $price, $discountedPrice, $stock, $description, $fileName])) {
                $message = "Masterpiece successfully archived in the inventory.";
                $messageType = "success";
            }
        } catch (Exception $e) {
            $message = "Registry Error: " . $e->getMessage();
            $messageType = "error";
        }
    } else {
        $message = "Upload Failed: Could not secure the media file.";
        $messageType = "error";
    }
}
?>

<div class="max-w-4xl mx-auto space-y-12">
    <!-- Header -->
    <div class="space-y-2">
        <h2 class="font-serif text-5xl text-white">Add New Item</h2>
        <p class="text-gray-500 text-sm tracking-[0.2em] uppercase font-bold">Register a new piece in the collection</p>
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
                    <input type="text" name="productName" required placeholder="e.g. Silk Velvet Gown"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Category</label>
                    <select name="category" required class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all appearance-none">
                        <option value="Men" class="bg-luxury">Men</option>
                        <option value="Women" class="bg-luxury">Women</option>
                        <option value="Accessories" class="bg-luxury">Accessories</option>
                    </select>
                </div>
            </div>

            <!-- Commercial Info -->
            <div class="space-y-6">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gold font-black border-l-2 border-gold pl-4">Valuation</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Retail Price ($)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Discounted ($)</label>
                        <input type="number" step="0.01" name="discountedPrice" placeholder="Optional"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Initial Stock Level</label>
                    <input type="number" name="stock" required placeholder="10"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all">
                </div>
            </div>
        </div>

        <!-- Media & Narrative -->
        <div class="space-y-6">
            <p class="text-[10px] uppercase tracking-[0.3em] text-gold font-black border-l-2 border-gold pl-4">Media & Narrative</p>
            
            <div class="space-y-2">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Product Narrative (Description)</label>
                <textarea name="description" rows="4" required placeholder="Tell the story of this piece..."
                          class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 text-white focus:outline-none focus:border-gold transition-all"></textarea>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Primary Visual (Image)</label>
                <div class="relative group">
                    <input type="file" name="file" required id="file-upload" class="hidden">
                    <label for="file-upload" class="flex flex-col items-center justify-center w-full h-40 bg-white/5 border-2 border-dashed border-white/10 rounded-2xl cursor-pointer group-hover:border-gold/50 transition-all">
                        <i class="fas fa-cloud-upload-alt text-gray-600 text-3xl mb-4 group-hover:text-gold transition-colors"></i>
                        <span class="text-xs text-gray-500 uppercase tracking-widest font-bold">Click to upload media</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-6">
            <button type="submit" name="submit" class="w-full py-6 bg-gold text-black text-[10px] font-black uppercase tracking-[0.4em] rounded-2xl hover:shadow-[0_0_40px_rgba(197,160,89,0.3)] transition-all">
                Finalize Registry
            </button>
        </div>
    </form>
</div>