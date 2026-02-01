<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Admin interface for editing existing products">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


</head>

<body style="display: flex;">
    <div>
        <?php
        include("../configshoppingstore.php");
        include("./sidebar.php");
        // include("./admin-product-insert-form.php");

        $queryRun = false;
        if (isset($_GET["id"])) {
            $productId = $_GET['id'];
            try {
                $getdata = $conn->prepare("SELECT id, category, productName, price, discountedPrice, stock, description, file FROM product WHERE id = ?");
                $getdata->execute([$productId]);
                $data =  $getdata->fetchAll();
                // var_dump($data);
                $category = $data[0]["category"];
                $productName = $data[0]["productName"];
                $price = $data[0]["price"];
                $discountedPrice = $data[0]["discountedPrice"];
                $stock = $data[0]["stock"];
                $description = $data[0]["description"];
                $file = $data[0]["file"];
            } catch (\Throwable $th) {
                die;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                $category !== $_POST['category'] ||
                $productName !== $_POST['productName'] ||
                $price !== $_POST['price'] ||
                $discountedPrice !== $_POST['discountedPrice'] ||
                $stock !== $_POST['stock'] ||
                $description !== $_POST['description']
            ) {
                $category = $_POST['category'];
                $productName = $_POST['productName'];
                $price = $_POST['price'];
                $discountedPrice = $_POST['discountedPrice'] ?? null;
                $stock = $_POST['stock'];
                $description = $_POST['description'];
                $queryRun = true;
            }



            if ($_FILES['productImage']['name'] !== "") {
                $imageName = $_FILES['productImage']['name'];
                $imageTmp = $_FILES['productImage']['tmp_name'];
                $uploadDir = './uploads/';
                $file = time() . $imageName;
                $imagePath = $uploadDir . $file;
            }


            try {
                $product = $conn->prepare("UPDATE product SET category='$category',productName='$productName',price='$price',discountedPrice='$discountedPrice',stock='$stock',description='$description',file='$file' WHERE id = '$productId'");
                $res = $product->execute();

                if ($res) {

                    if ($_FILES['productImage']['name']) {
                        move_uploaded_file($imageTmp, $imagePath);
                    }

                    if ($queryRun) {
                        header("Location: index.php?page=products");
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        ?>

    </div>


    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-[#3b82f6] px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Add New Product</h1>
                <p class="text-[#bfdbfe]">Fill all required fields</p>
            </div>

            <form class="p-6 space-y-4" id="productForm" method="POST" enctype="multipart/form-data">
                <div>
                    <?php
                    $category = $category ?? ''; // Prevents undefined variable error
                    $categories = [
                        "tshirts" => "T-Shirts",
                        "shirts" => "Shirts",
                        "jeans" => "Jeans",
                        "trousers" => "Trousers & Pants",
                        "kurta" => "Kurta & Shalwar Kameez",
                        "suits" => "Suits & Blazers",
                        "hoodies" => "Hoodies & Sweatshirts",
                        "abayas" => "Abayas & Hijabs",
                        "tops" => "Tops & Tunics",
                        "kids-clothing" => "Kids' Clothing"
                    ];
                    ?>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="category" name="category" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">

                        <?php foreach ($categories as $value => $label): ?>
                            <option value="<?php echo $value ?>" <?php echo ($category === $value) ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div>
                        <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" id="productName" name="productName" value="<?php echo isset($productName) ?>" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter product name">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $price ?>" required
                                    class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">USD</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                            <input type="number" id="stock" name="stock" min="0" value="<?php echo $stock ?>" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Available quantity">
                        </div>
                    </div>

                    <div>
                        <label for="discountedPrice" class="block text-sm font-medium text-gray-700 mb-1">Discounted Price (optional)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="discountedPrice" name="discountedPrice" min="0" step="0.01" value="<?php echo $discountedPrice ?>"
                                class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">USD</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea id="description" name="description" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Detailed product description"><?php echo isset($description) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Image *</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="productImage" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                        <span>Upload an image</span>
                                        <input name="productImage" type="file">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6">
                        <button type="reset"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Clear Form
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-plus-circle mr-2"></i>Update Product
                        </button>
                    </div>
            </form>
        </div>

        <!-- <?php if ($success): ?> -->
        <div class="max-w-3xl mx-auto mt-4 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Product updated successfully!</h3>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</body>


</body>

</html>