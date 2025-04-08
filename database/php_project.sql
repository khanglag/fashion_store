-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 08, 2025 lúc 12:46 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `php_project`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(108) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  `reset_code` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `admin_email`, `admin_password`, `reset_code`, `reset_expiry`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin\r\n', NULL, NULL),
(2, 'LÃª HuÃ¢n', 'huan@gmail.com', '$2y$10$XaiQQaWr13ZnQEdbaxF6b.iDDq7jgcEacH0/M65XiQKPETl4fcUKm', 'cc267fc89f09d7e5cb2e4d4b29963de3', '2024-11-22 15:57:51'),
(4, 'HoÃ ng Trinh', 'trinh@gmail.com', '$2y$10$C5NzoOsAvdeiJIWkjcnmBOHQkNJJ3BFIEcDRbOS0Z4b4eftNZrKvW', NULL, NULL),
(5, 'LÃª HuÃ¢n', 'nguyenlehuan@gmail.com', '$2y$10$56KH3HNzYf70IPEt7gy0TO8BbSoECPwuk35ATiIrMFg/1h3WEpEVC', NULL, NULL),
(6, 'Admin', 'khangminh.do2003@gmail.com', '$2y$10$ghaIgTBrm1V0kY2Maiv09OxRjmOtZ5oVVWDqwDBcKC3x6n.ca8Sdq', NULL, NULL),
(7, 'Nguyá»…n LÃª HuÃ¢n', 'nguyenlehuan0814745869@gmail.com', '$2y$10$PQvrAzfxMzn/9DnxD7mbVOHdBtrXHQZ2Ikrj4RwLW/Y8expFqbTVW', '43ba1e9ce5c891672807a75f992f2aee', '2024-11-22 16:00:55'),
(8, 'mkhang', 'khangdo0107@gmail.com', '$2y$10$o7V0t4KuTBrNEEgDw5GEC.52kk.qpT.BPeSz2SSwGNrBkyWiBYD/e', '0f7e798a72c5249b8a9f3ffdc4b3a149', '2025-04-07 19:28:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'TOPS'),
(3, 'BAGS'),
(4, 'ACCESSORIES'),
(6, 'TOPS/T-SHIRTS'),
(7, 'TOPS/SHIRTS'),
(8, 'TOPS/SWEATERS & CARDIGANS'),
(9, 'TOPS/SWEATSHIRTS & HOODIES'),
(10, 'TOPS/OUTERWEARS'),
(11, 'BOTTOMS'),
(12, 'BOTTOMS/PANTS'),
(13, 'BOTTOMS/SHORTS'),
(14, 'BAGS/MINI BAGS'),
(20, 'BAGS/BIG BAGS');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_cost` decimal(10,2) NOT NULL,
  `order_status` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `order_cost`, `order_status`, `user_id`, `user_phone`, `user_address`, `order_date`) VALUES
(92, 390.00, 'Pending', 5, '0335557720', '163/2 20', '2025-04-07 15:52:36'),
(93, 390.00, 'Pending', 5, '0865674317', 'AP 4 Thanh Duc\r\nBen Luc', '2025-04-07 15:54:06'),
(94, 200.00, 'Pending', 5, '0335557720', '163/2 20', '2025-04-07 16:14:16'),
(95, 200.00, 'Pending', 5, '0335557720', '163/2 20', '2025-04-07 16:15:16'),
(96, 200.00, 'Pending', 5, '0335557720', '163/2 20', '2025-04-07 16:17:30'),
(97, 200.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 16:45:02'),
(98, 500.00, 'Pending', 5, '0865674317', 'AP 4 Thanh Duc\r\nBen Luc', '2025-04-07 16:50:08'),
(99, 828.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 16:56:19'),
(100, 328.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 16:58:51'),
(101, 200.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 17:08:03'),
(102, 500.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 17:14:37'),
(103, 500.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 17:21:42'),
(104, 700.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 17:22:51'),
(105, 500.00, 'Pending', 5, '0876386816', '15, Ấp 4, xóm Đình', '2025-04-07 17:24:11'),
(106, 500.00, 'Pending', 5, '0865674317', 'AP 4 Thanh Duc\r\nBen Luc', '2025-04-07 17:26:41'),
(107, 700.00, 'Pending', 5, '0865674317', 'AP 4 Thanh Duc\r\nBen Luc', '2025-04-07 17:27:09'),
(108, 328.00, 'Pending', 5, '0335557720', '10', '2025-04-07 17:27:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `product_quantity` int(11) NOT NULL,
  `product_size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `product_name`, `product_price`, `product_image`, `user_id`, `order_date`, `product_quantity`, `product_size`) VALUES
(81, 98, 51, 'Degrey Double Leather Basic Balo Kem - LBBDK', 500.00, 'Degrey Double Leather Basic Balo Kem - LBBDK_1_1731297373.jpeg', 5, '2025-04-07 16:50:08', 1, 5),
(82, 101, 50, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS', 200.00, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_1_1731296781.png', 5, '2025-04-07 17:08:03', 1, 1),
(83, 106, 51, 'Degrey Double Leather Basic Balo Kem - LBBDK', 500.00, 'Degrey Double Leather Basic Balo Kem - LBBDK_1_1731297373.jpeg', 5, '2025-04-07 17:26:41', 1, 5),
(84, 107, 51, 'Degrey Double Leather Basic Balo Kem - LBBDK', 500.00, 'Degrey Double Leather Basic Balo Kem - LBBDK_1_1731297373.jpeg', 5, '2025-04-07 17:27:09', 1, 5),
(85, 107, 50, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS', 200.00, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_1_1731296781.png', 5, '2025-04-07 17:27:09', 1, 1),
(86, 108, 53, 'Letters Monogram Denim Backpack - Black', 328.00, 'balo.jpeg', 5, '2025-04-07 17:27:43', 1, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status_products_id` int(11) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_image2` varchar(255) DEFAULT NULL,
  `product_image3` varchar(255) DEFAULT NULL,
  `product_image4` varchar(255) DEFAULT NULL,
  `product_price` varchar(50) DEFAULT NULL,
  `product_price_discount` varchar(50) DEFAULT NULL,
  `product_color` varchar(108) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `status_products_id`, `product_description`, `product_image`, `product_image2`, `product_image3`, `product_image4`, `product_price`, `product_price_discount`, `product_color`, `quantity`) VALUES
(1, 'FLOWER LOGO MESH SHORT IN BLACK', 13, 6, '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ThÃ´ng tin sáº£n pháº©m\r\nUnisex\r\n\r\nOversize\r\n\r\n100% Mesh\r\n\r\nModel wears size L                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', 'hoavan.jpeg', 'hoavan.jpeg', 'hoavan.jpeg', 'hoavan.jpeg', '1200000', '600000', 'black', 10),
(3, 'Thương bạn gái', 6, 6, '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    \r\nChi tiáº¿t sáº£n pháº©m:\r\nâ€¢ ThÃ nh pháº§n: BÃ´ng.\r\nâ€¢ Thoáº£i mÃ¡i vá»«a váº·n.\r\nâ€¢ CÃ¡c chi tiáº¿t Ä‘Æ°á»£c in lá»¥a á»Ÿ máº·t trÆ°á»›c vÃ  máº·t sau.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', '24.png', '24.png', '24.png', '24.png', '390000', '500000', 'black', 0),
(4, 'â€œWHENEVERâ€ NYLON SHORTS SS24 / WHITE', 13, 6, '                                                                                                                                                                                                                                                                                                                                                                          awesome T-Shirt                                                                                                                                                                                                                                                                                                                                    ', 'shorttrang.jpeg', 'shorttrang.jpeg', 'shorttrang.jpeg', 'shorttrang.jpeg', NULL, '550000', 'white', 0),
(5, ' STELLA SHORT V.2 ( CLOUDY )', 13, 6, '                                                                                                                                                                                                                                      awesome T-Shirt                                                                                                                                                                                                            ', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', NULL, '0', 'blue', 0),
(7, ' B-STREAM DENIM PANTS', 12, 6, '                                                                                                                                                                                                    awesome trousers                                                                                                                                                                        ', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', NULL, '0', 'black red', 0),
(8, 'CENTER DENIM PANTS', 12, 6, '                                            háº¿t hÃ ng                                                                        ', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', NULL, '0', 'white', 0),
(10, 'KÃ­nh SOLIOS NUSON + Charm KÃ­nh HOA SEN ÄEN - RHODES ', 4, 6, '                                                                                                                                                                                                                                                                                                                                                                                                                                                    order 3-5 days                                                                                                                                                                                                                                                                                                                                                                                        ', 'KinhHELIOSAEOS-HOASENDEN_8_1200x.png', 'kinh1.jpeg', 'kinh.jpeg', 'kinh1.jpeg', NULL, '2000000', '0', 0),
(11, 'Nam Quá»‘c SÆ¡n HÃ  Ring Helios Black Silver', 4, 6, '                                                                                                                                              order 10 days                                                                                                                            ', 'vietnam.jpeg', 'vietnam.jpeg', 'vietnam.jpeg', 'vietnam.jpeg', NULL, '0', 'sliver 925', 0),
(12, 'Bandana Shirt Jacket - Black', 10, 6, '                                                      awesome T-shirt                                            ', '120.png', '120.png', '120.png', '120.png', NULL, NULL, 'black', 0),
(19, '  AA x HP LEATHER VARSITY // RED', 10, 6, '                                                                                                                                                                                                    Model M83 - 70kg wears: \r\n\r\nAA x HUNGPHAM VARSITY JACKET - L                                                                                                                                                                       ', 'varsity.png', 'varsity.png', 'varsity.png', 'varsity.png', NULL, '1200', 'red', 0),
(20, ' DC X DRAGON BALL Z  ', 10, 6, 'táº¡m háº¿t hÃ ng                  ', 'dc.jpeg', 'dc.jpeg', 'dc.jpeg', 'dc.jpeg', NULL, NULL, 'black white', 0),
(21, 'â€œWHENEVER\" BASIC T-SHIRT/ PINK-WHITE-BLACK', 6, 6, ' Best seller', 'ao.jpeg', 'ao1.jpeg', '73.png', 'ao.jpeg', NULL, NULL, 'Ä‘en-pink-white', 0),
(23, 'Mate T-shirt - White', 6, 6, 'Táº¡m Háº¿t HÃ ng', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', NULL, NULL, 'white', 0),
(24, 'STELLA DENIM SHIRT ( LIGHT BLUE )/ ( LIGHT WHITE)', 7, 6, '                                                                                                            awesome T-Shirt                                                                                        ', 'somi1.jpeg', 'vn-11134207-7r98o-lwf8yh6hryzd44.jpeg', 'somi1.jpeg', 'vn-11134207-7r98o-lwf8yh6hryzd44.jpeg', NULL, NULL, 'white-blue', 0),
(25, 'Shirt-Angelo', 7, 6, '                                                      awesome T-Shirt                                            ', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', NULL, NULL, 'blue', 0),
(26, 'â€œWHENEVERâ€ HANDMADE KNIT SWEATER', 8, 6, 'order 10 days', 'sweater.jpeg', 'sweater.jpeg', 'sweater.jpeg', 'sweater.jpeg', NULL, NULL, 'cream-red', 0),
(28, 'Print Cardigan  DirtyCoins ', 8, 6, '                                                      Pre Order                                            ', 'xanhla.jpeg', 'hong1.jpeg', 'nau.jpeg', 'hong1.jpeg', NULL, NULL, 'cream', 0),
(29, 'SSMA KNIT SWEATER - BABY PINK/ BABY BLUE', 8, 6, '                                                                                                            Pre Order                                                                                        ', 'xanh.jpeg', 'hong.jpeg', 'xanh.jpeg', 'hong.jpeg', NULL, NULL, 'pink/blue', 0),
(30, 'Letters Monogram Knit Sweater - Black', 9, 6, ' .                              ', 'den.jpeg', 'den.jpeg', 'den.jpeg', 'den.jpeg', NULL, NULL, 'black', 0),
(31, 'RAVEN FUR JACKET', 7, 6, '                                                                                                            Pre Order                                                                                        ', 'ProductName1.png', 'ProductName2.png', 'ProductName3.png', 'ProductName4.png', NULL, NULL, '0', 0),
(32, 'Leather Biker Jacket - White ', 10, 6, '                                                                                                                                       Collab With Bray                                            ', 'bray.jpeg', 'ProductName2.jpeg', 'ProductName3.jpeg', 'ProductName4.jpeg', NULL, NULL, 'white', 0),
(33, 'LeventsÂ® Classic Hoodie', 9, 6, '                                                                                                            .                                                                                        ', 'aomautrang.jpeg', 'aomautrang.jpeg', 'lvtrang.jpeg', 'lvtrang.jpeg', NULL, NULL, 'cream', 0),
(34, 'TÃºi Ä‘eo chÃ©o thá»i trang nam ná»¯ Murphy Bag â€“ Äen', 14, 6, '                                                                                                  restock                                                                                    ', '126-2-760x760.png', '126-2-760x760.png', '126-2-760x760.png', '126-2-760x760.png', NULL, NULL, 'black', 0),
(35, 'Checkerboard Print Mini Bowler Bag', 14, 6, '                                                      .                                            ', 'dctui.png', 'dctui.png', 'dctui.png', 'dctui.png', NULL, NULL, '0', 0),
(36, 'SPOILED | Shiny Black Croprint Mini Bag', 14, 6, '                                                      .                                            ', 'tui.jpeg', 'tui.jpeg', 'tui.jpeg', 'tui.jpeg', NULL, NULL, '0', 0),
(37, 'SPOILED | Silver Metallic Mini Bag', 14, 6, '                                                      .                                            ', 'tuibac.jpeg', 'tuibac.jpeg', 'tuibac.jpeg', 'tuibac.jpeg', NULL, NULL, '0', 0),
(38, 'SPOILED | Mini Bag / Matte Cobalt Blue', 14, 6, '                                                      .                                            ', 'tui2.jpeg', 'tui2.jpeg', 'tui2.jpeg', 'tui2.jpeg', NULL, NULL, '0', 0),
(39, 'BAGS - DirtyCoins | VIETNAMESE STREETWEAR BRAND', 14, 6, '                                                                                                            .                                            ThÃ´ng tin sáº£n pháº©m\r\n \r\n\r\nUnisex\r\nFree size\r\n100% Poly                                            ', 'tuidc.jpeg', 'tuidc.jpeg', 'tuidc.jpeg', 'tuidc.jpeg', NULL, NULL, 'cream-blue', 0),
(40, 'LIN SHIRT ( BLACK)', 7, 6, '                                                                                                                                                                                                                                                               ', 'somi2.jpeg', 'somi2.jpeg', 'somi2.jpeg', 'somi2.jpeg', NULL, NULL, '0', 0),
(41, 'SPOILED | Love Insde Round Pouch / Matte Candy Red', 14, 6, '                                                      CÃ²n Máº«u Red                                            ', 'tuitron.jpeg', 'tuitron.jpeg', 'tuitron.jpeg', 'tuitron.jpeg', NULL, NULL, '0', 0),
(42, 'SPOILED | League Maxi Leather Bag / SHINY BLACK', 14, 6, '                                                      color: white                                            ', 'tui1.jpeg', 'tui1.jpeg', 'tui1.jpeg', 'tui1.jpeg', NULL, NULL, '0', 0),
(43, 'Letters Monogram Denim Jersey Shirt - Black', 7, 6, '                                                      .                                            ', 'ProductName1.png', 'ProductName2.png', 'ProductName3.png', 'ProductName4.png', NULL, NULL, '0', 0),
(44, 'P LINEN SHIRT', 7, 6, '                                                                                                            .                                                                                        ', 'somi.jpeg', 'somi3.jpeg', 'somi.jpeg', 'somi3.jpeg', NULL, NULL, '0', 0),
(45, 'DAWN OVERSHIRT', 7, 6, '                                                                                                  .                                                                                    ', 'images (2).jpeg', 'images (2).jpeg', 'images (2).jpeg', 'images (2).jpeg', '200000', '0', '0', 10),
(46, ' If I Play I Play To Win T-Shirt - Black', 6, 6, '                                                                                                            .                                                                                        ', 'images.jpeg', 'images.jpeg', 'images.jpeg', 'images.jpeg', NULL, NULL, '0', 0),
(47, 'Heliotrope Helios Silver', 4, 6, '                                                      TÃªn sáº£n pháº©m: Nháº«n báº¡c S925 Blood Sun Helios Silver Original\r\n                              ', 'nhan.jpeg', 'ProductName2.jpeg', 'ProductName3.jpeg', 'ProductName4.jpeg', NULL, NULL, '0', 0),
(48, 'DirtyCoins Logo Denim Jacket | Blue Wash', 10, 6, 'best seller', 'DirtyCoins Logo Denim Jacket | Blue Wash_1_1731288749.jpeg', 'DirtyCoins Logo Denim Jacket | Blue Wash_2_1731288749.jpeg', 'DirtyCoins Logo Denim Jacket | Blue Wash_3_1731288749.jpeg', 'DirtyCoins Logo Denim Jacket | Blue Wash_4_1731288749.jpeg', NULL, NULL, '0', 0),
(49, 'University Felt Varsity Jacket - Red', 10, 5, '                                                                                        .                                                                                ', 'University Felt Varsity Jacket - Red_1_1731288864.jpeg', 'University Felt Varsity Jacket - Red_2_1731288864.jpeg', 'University Felt Varsity Jacket - Red_3_1731288864.jpeg', 'University Felt Varsity Jacket - Red_4_1731288864.jpeg', '500000', '700000', '0', 100),
(50, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS', 12, 2, '                                            Diagonal Pleat Wool Viscose Trousers provides a fresh, refined look with its unique diagonal pleats and premium wool-viscose blend, offering comfort and breathability. The wide-leg silhouette and high-rise waist enhance the wearer\'s height and physique, creating a balanced, relaxed yet elegant style suitable for everyday wear.\r\n\r\nHook-and-bar and zip closure\r\nRelaxed fit \r\nShell: 20% Wool, 30% Viscose,49% Rayon 1% Elastane, Lining: 100% Cotton. Excluding trims / Machine wash                                        ', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_1_1731296781.png', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_2_1731296781.png', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_3_1731296781.png', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_4_1731296781.png', '200000', '300000', 'Black', 8),
(51, 'Degrey Double Leather Basic Balo Kem - LBBDK', 20, 6, '                                                                                                                                                                                                                            THÃ´ng tin sáº£n pháº©m\r\n- Cháº¥t liá»‡u: Simili\r\n\r\n- Hoáº¡ tiáº¿t: thÃªu trá»±c tiáº¿p\r\n\r\n- Size: 42cm X 30cm X 16cm\r\n\r\n- NgÄƒn chá»‘ng sá»‘c Ä‘á»±ng vá»«a laptop 15.6inch \r\n\r\n- ThÆ°Æ¡ng hiá»‡u: Degrey\r\n\r\n- Sáº£n xuáº¥t: Viá»‡t Nam\r\n\r\n- MÃ u sáº¯c vÃ  há»a tiáº¿t Ä‘Æ°á»£c thiáº¿t káº¿ riÃªng bá»Ÿi team design DEGREY\r\n\r\n \r\n\r\n+ HÆ¯á»šNG DáºªN Báº¢O QUáº¢N Sáº¢N PHáº¨M DEGREY:\r\n\r\n- KhÃ´ng dÃ¹ng hÃ³a cháº¥t táº©y máº¡nh lÃªn sáº£n pháº©m\r\n\r\n- KhÃ´ng dÃ¹ng váº­t dá»¥ng sáº¯c, nhá»n cÃ  trá»±c tiáº¿p lÃªn bá» máº·t Balo\r\n\r\n- KhÃ´ng giáº·t mÃ¡y\r\n\r\n- Sá»­ dá»¥ng bÃ n cháº£i cÃ³ lÃ´ng má»m\r\n\r\n- Tuyá»‡t Ä‘á»‘i khÃ´ng dÃ¹ng bÃ n cháº£i cÆ°á»›c cá»©ng sáº½ gÃ¢y ra cÃ¡c váº¿t xÆ°á»›c trÃªn ná»n váº£i\r\n\r\n \r\n\r\n+ CHÃNH SÃCH Äá»”I Sáº¢N PHáº¨M:\r\n\r\n1.Äiá»u kiá»‡n Ä‘á»•i hÃ ng\r\n\r\n- Báº¡n lÆ°u Ã½ giá»¯ láº¡i hoÃ¡ Ä‘Æ¡n Ä‘á»ƒ Ä‘á»•i hÃ ng trong vÃ²ng 30 ngÃ y.\r\n\r\n- Äá»‘i vá»›i máº·t hÃ ng giáº£m giÃ¡, phá»¥ kiá»‡n cÃ¡ nhÃ¢n (Ã¡o lÃ³t, kháº©u trang, vá»› ...) khÃ´ng nháº­n Ä‘á»•i hÃ ng.\r\n\r\n- Táº¥t cáº£ sáº£n pháº©m Ä‘Ã£ mua sáº½ khÃ´ng Ä‘Æ°á»£c Ä‘á»•i tráº£ láº¡i báº±ng tiá»n máº·t.\r\n\r\n- Báº¡n cÃ³ thá»ƒ Ä‘á»•i size hoáº·c sáº£n pháº©m khÃ¡c trong 30 ngÃ y (LÆ°u Ã½: sáº£n pháº©m chÆ°a qua sá»­ dá»¥ng, cÃ²n tag nhÃ£n vÃ  hÃ³a Ä‘Æ¡n mua hÃ ng.)\r\n\r\n- Báº¡n vui lÃ²ng gá»­i cho chÃºng mÃ¬nh clip Ä‘Ã³ng gÃ³i vÃ  hÃ¬nh áº£nh cá»§a Ä‘Æ¡n hÃ ng Ä‘á»•i tráº£ cá»§a báº¡n, nhÃ¢n viÃªn tÆ° váº¥n sáº½ xÃ¡c nháº­n vÃ  tiáº¿n hÃ nh lÃªn Ä‘Æ¡n Ä‘á»•i tráº£ cho báº¡n.\r\n\r\n \r\n\r\n2. TrÆ°á»ng há»£p khiáº¿u náº¡i\r\n\r\n- Báº¡n pháº£i cÃ³ video unbox hÃ ng\r\n\r\n- Quay video rÃµ nÃ©t 6 máº·t cá»§a gÃ³i hÃ ng\r\n\r\n- Quay rÃµ: TÃªn ngÆ°á»i nháº­n, mÃ£ Ä‘Æ¡n, Ä‘á»‹a chá»‰, sá»‘ Ä‘iá»‡n thoáº¡i.\r\n\r\n- Video khÃ´ng cáº¯t ghÃ©p, chá»‰nh sá»­a\r\n\r\n- Degrey xin khÃ´ng tiáº¿p nháº­n giáº£i quyáº¿t cÃ¡c trÆ°á»ng há»£p khÃ´ng thá»a cÃ¡c Ä‘iá»u kiá»‡n trÃªn.                                                                                                                                                                                                        ', 'Degrey Double Leather Basic Balo Kem - LBBDK_1_1731297373.jpeg', 'Degrey Double Leather Basic Balo Kem - LBBDK_2_1731297373.jpeg', 'Degrey Double Leather Basic Balo Kem - LBBDK_3_1731297373.jpeg', 'Degrey Double Leather Basic Balo Kem - LBBDK_4_1731297373.jpeg', '500000', '700000', '0', 100),
(52, 'Dico Wavy Backpack V3', 20, 2, '                                                                                                                                    Chi tiáº¿t sáº£n pháº©m:\r\nâ€¢ Ba lÃ´ form cÆ¡ báº£n, ngÄƒn má»Ÿ báº±ng khÃ³a kÃ©o.\r\nâ€¢ Cháº¥t liá»‡u máº·t ngoÃ i: da PU cao cáº¥p .\r\nâ€¢ Cháº¥t liá»‡u lÃ³t: váº£i poly.\r\nâ€¢ LÃ²ng trong ba lÃ´ cÃ³ thá»ƒ Ä‘á»±ng vá»«a laptop 15.6\'\'.\r\nâ€¢ Äáº§u khÃ³a kÃ©o cÃ³ dáº­p logo chá»¯ Y.\r\nâ€¢ DÃ¢y Ä‘ai Ä‘eo vai cÃ³ dá»‡t chÃ¬m logo DirtyCoins.\r\nâ€¢ Máº·t trÆ°á»›c ba lÃ´ thÃªu logo DirtyCoins Wavy.\r\nâ€¢ KÃ­ch thÆ°á»›c: Ngang x Rá»™ng x Cao: 32 x 13 x 44 cm                                                                                                                        ', 'Dico Wavy Backpack V3_1_1731297458.jpeg', 'Dico Wavy Backpack V3_2_1731297458.jpeg', 'Dico Wavy Backpack V3_3_1731297458.jpeg', 'Dico Wavy Backpack V3_4_1731297458.jpeg', '200000', '0', '0', 96),
(53, 'Letters Monogram Denim Backpack - Black', 20, 2, '                                                                                                                                                                                          Chi tiáº¿t sáº£n pháº©m:\r\nâ€¢ Ba lÃ´ form cÆ¡ báº£n, ngÄƒn má»Ÿ báº±ng khÃ³a kÃ©o.\r\nâ€¢ Cháº¥t liá»‡u máº·t ngoÃ i: Cotton Denim .\r\nâ€¢ Cháº¥t liá»‡u lÃ³t: váº£i poly.\r\nâ€¢ LÃ²ng trong ba lÃ´ cÃ³ thá»ƒ Ä‘á»±ng vá»«a laptop 15.6\'\'.\r\nâ€¢ Äáº§u khÃ³a kÃ©o cÃ³ dáº­p logo chá»¯ Y.\r\nâ€¢ Pattern monogram Ä‘Æ°á»£c dá»‡t trÃªn bá» máº·t váº£i thÃ¢n balo vÃ  quai Ä‘eo.\r\nâ€¢ KÃ­ch thÆ°á»›c: Ngang x Rá»™ng x Cao: 32 x 13 x 44 cm                                                                                                                                                                    ', 'balo.jpeg', 'Letters Monogram Denim Backpack - Black_2_1731297528.jpeg', 'Letters Monogram Denim Backpack - Black_3_1731297528.jpeg', 'Letters Monogram Denim Backpack - Black_4_1731297528.jpeg', '328000', '0', '0', 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slider`
--

CREATE TABLE `slider` (
  `slider_id` int(11) NOT NULL,
  `slider_name` varchar(100) NOT NULL,
  `slider_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `slider`
--

INSERT INTO `slider` (`slider_id`, `slider_name`, `slider_image`) VALUES
(4, '3', 'banner.jpg'),
(7, 'hihi', 'banner2.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `status_products`
--

CREATE TABLE `status_products` (
  `status_products_id` int(11) NOT NULL,
  `status_products_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `status_products`
--

INSERT INTO `status_products` (`status_products_id`, `status_products_name`) VALUES
(1, 'New Product'),
(2, 'In Stock'),
(5, 'Pre Order'),
(6, 'Sold Out');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(108) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`) VALUES
(5, 'lag', 'khangminh.do2003@gmail.com', '25d55ad283aa400af464c76d713c07ad'),
(6, 'khang', 'khang@gmail.com', '25d55ad283aa400af464c76d713c07ad');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `UX_Constraint` (`admin_email`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`slider_id`);

--
-- Chỉ mục cho bảng `status_products`
--
ALTER TABLE `status_products`
  ADD PRIMARY KEY (`status_products_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UX_Constraint` (`user_email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT cho bảng `slider`
--
ALTER TABLE `slider`
  MODIFY `slider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `status_products`
--
ALTER TABLE `status_products`
  MODIFY `status_products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
