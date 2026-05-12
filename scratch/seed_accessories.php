<?php
include("../configshoppingstore.php");
try {
    $accessories = [
        [
            'name' => 'Royal Chronograph Watch',
            'category' => 'Accessories',
            'price' => 850.00,
            'discount' => 799.00,
            'stock' => 15,
            'desc' => 'A precision-engineered timepiece with a stainless steel finish and sapphire glass.',
            'file' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'name' => 'Leather Saffiano Handbag',
            'category' => 'Accessories',
            'price' => 1200.00,
            'discount' => 0,
            'stock' => 8,
            'desc' => 'Italian crafted leather handbag with gold-plated hardware and spacious compartments.',
            'file' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'name' => 'Classic Aviator Sunglasses',
            'category' => 'Accessories',
            'price' => 250.00,
            'discount' => 199.00,
            'stock' => 25,
            'desc' => 'Timeless aviator design with polarized lenses and lightweight titanium frames.',
            'file' => 'https://images.unsplash.com/photo-1511499767350-a1590fdb7ac7?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'name' => 'Signature Oud Perfume',
            'category' => 'Accessories',
            'price' => 350.00,
            'discount' => 0,
            'stock' => 30,
            'desc' => 'An exotic blend of agarwood, saffron, and amber for a lasting luxury scent.',
            'file' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'name' => 'Gilded Link Bracelet',
            'category' => 'Accessories',
            'price' => 450.00,
            'discount' => 399.00,
            'stock' => 12,
            'desc' => '24k gold-plated link bracelet, a statement piece for any evening ensemble.',
            'file' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'name' => 'Premium Suede Belt',
            'category' => 'Accessories',
            'price' => 180.00,
            'discount' => 0,
            'stock' => 20,
            'desc' => 'Hand-stitched suede belt with a brushed silver buckle.',
            'file' => 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?auto=format&fit=crop&w=800&q=80'
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO `product` (`category`, `productName`, `price`, `discountedPrice`, `stock`, `description`, `file`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($accessories as $item) {
        $stmt->execute([
            $item['category'],
            $item['name'],
            $item['price'],
            $item['discount'],
            $item['stock'],
            $item['desc'],
            $item['file']
        ]);
    }
    
    echo "Accessories added successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
