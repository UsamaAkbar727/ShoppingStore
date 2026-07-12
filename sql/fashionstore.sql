-- Updated FashionStore Database Schema
-- Optimized for Real-time Product Management & E-commerce

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Database: `fashionstore`
-- --------------------------------------------------------
-- CREATE DATABASE IF NOT EXISTS `fashionstore` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE `fashionstore`;

-- --------------------------------------------------------
-- Table structure for table `user`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `isBlock` varchar(30) NOT NULL DEFAULT '0',
  `expire` varchar(200) NOT NULL DEFAULT '',
  `file` varchar(200) NOT NULL DEFAULT 'default.png',
  `role` enum('user', 'admin') DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `product`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` enum('Men', 'Women', 'Objects') NOT NULL,
  `productName` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discountedPrice` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `cart`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `wishlist`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Seeding Default Admin
-- --------------------------------------------------------
INSERT IGNORE INTO `user` (`fullname`, `email`, `password`, `role`) VALUES 
('Store Owner', 'Sultanjutt@gmail.com', '$2y$10$BjpYpoyCnAfSgXjgyEbU7OfIEhAAZ5UU0eaaRvH.1FUz3FQKXBqza', 'admin');

-- --------------------------------------------------------
-- Seeding Default Products
-- --------------------------------------------------------
INSERT IGNORE INTO `product` (`id`, `category`, `productName`, `price`, `discountedPrice`, `stock`, `description`, `file`) VALUES 
(7, 'Men', 'Kids Trousers', 1400.00, 1100.00, 34, 'Comfortable trousers for kids', 'trousers1.jpg'),
(8, 'Women', 'Abaya Black Classic', 3000.00, 2700.00, 9, 'Classic black abaya with modern touch', 'abaya1.jpg'),
(9, 'Women', 'Hijab Set', 100.00, 0.00, 160, 'Soft hijab set in assorted colors', 'hijab1.jpg'),
(14, 'Men', 'jeans', 4.00, 3.00, 43, 'awailable', '17539538091753771151jeans.jpg'),
(15, 'Men', 'Trouser & Pants', 4.00, 3.00, 53, 'trousers new awailable', '17539538751753773804mens-cotton-trousers.webp'),
(16, 'Objects', 'Shirts', 5.00, 4.00, 76, 'awailable', '1753953913tshirt2.jpg'),
(17, 'Objects', 'Hoodie ', 20.00, 3.00, 130, 'Best collection', '175540732406d2063d8dedd48654108417ef558dbd.jpg_400x400q80.jpg_.webp'),
(18, 'Men', 'Winter', 10000.00, 1000.00, 95, 'stock avale', '1778438767_IMG_20220419_191341_261.jpg'),
(19, 'Men', 'T-shirt', 400.00, 10.00, 90, 'its very cool loook', '1778523271_22a9c011c19095809dd2b4ea8f28759a.jpg'),
(20, 'Accessories', 'Royal Chronograph Watch', 850.00, 799.00, 15, 'A precision-engineered timepiece with a stainless steel finish and sapphire glass.', 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=800&q=80'),
(21, 'Accessories', 'Leather Saffiano Handbag', 1200.00, 0.00, 7, 'Italian crafted leather handbag with gold-plated hardware and spacious compartments.', 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'),
(22, 'Accessories', 'Classic Aviator Sunglasses', 250.00, 199.00, 25, 'Timeless aviator design with polarized lenses and lightweight titanium frames.', 'https://images.unsplash.com/photo-1511499767350-a1590fdb7ac7?auto=format&fit=crop&w=800&q=80'),
(23, 'Accessories', 'Signature Oud Perfume', 350.00, 0.00, 30, 'An exotic blend of agarwood, saffron, and amber for a lasting luxury scent.', 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=800&q=80'),
(24, 'Accessories', 'Gilded Link Bracelet', 450.00, 399.00, 11, '24k gold-plated link bracelet, a statement piece for any evening ensemble.', 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=800&q=80'),
(25, 'Accessories', 'Premium Suede Belt', 180.00, 0.00, 18, 'Hand-stitched suede belt with a brushed silver buckle.', 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?auto=format&fit=crop&w=800&q=80'),
(26, 'Men', 'Winter Set', 1100.00, 0.00, 10, 'dummyy', '1778739391_download.jfif');

COMMIT;
