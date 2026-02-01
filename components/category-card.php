<head>
    <style>
        .ani {
            animation: appear 5s linear;
            animation-timeline: view();
            animation-range: entry 0% cover 30%;
        }

        @keyframes appear {
            from {
                opacity: 0;
                scale: 0.4;
            }

            to {
                opacity: 1;
                scale: 1;
            }
        }
    </style>
</head>
<?php
$categories = [
    ["name" => "Men's Clothing", "image" => "https://picsum.photos/400/300?random=10"],
    ["name" => "Women's Clothing", "image" => "https://picsum.photos/400/300?random=11"],
    ["name" => "Accessories", "image" => "https://picsum.photos/400/300?random=12"],
    ["name" => "Footwear", "image" => "https://picsum.photos/400/300?random=13"]
];

foreach ($categories as $category) {
?>
    <div class="ani group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition duration-300">
        <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" class="w-full h-48 object-cover transition duration-500 group-hover:scale-105" loading="lazy">
        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <h3 class="text-white text-xl font-bold"><?php echo $category['name']; ?></h3>
        </div>
        <a href="/shop?category=<?php echo strtolower(str_replace(' ', '-', $category['name'])); ?>" class="absolute inset-0"></a>
    </div>
<?php } ?>