-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 23, 2025 lúc 02:12 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

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
(1, 'Cường', 'kecuong71@gmail.com', '$2y$10$fGxqVgIDZiAvYlMq1y1yKekzJBplDc77q9FEpkKazAuhWkIcJlVg2', NULL, NULL),
(10, 'Cường', 'ke@gmail.com', '$2y$10$Jg5qvLXg/sro9CgNwrNegeVkO8GGuwPcE8Nhw7iLiE4wT19rwUJZC', NULL, NULL);

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
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `order_cost`, `order_status`, `user_id`, `user_phone`, `user_address`, `order_date`, `payment_method`) VALUES
(119, 500.00, 'delivered', 9, '0336773026', '127A Thạnh Lộc 28', '2025-04-23 06:07:19', 'bank'),
(120, 1160.00, 'cancelled', 9, '0336773026', '127A Thạnh Lộc 28', '2025-03-04 07:12:04', 'cash');

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
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `product_quantity` int(11) NOT NULL,
  `product_size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `product_name`, `product_price`, `product_image`, `order_date`, `product_quantity`, `product_size`) VALUES
(110, 119, 33, 'Levents® Classic Hoodie', 500.00, 'aomautrang.jpeg', '2025-04-23 06:07:19', 1, 3),
(111, 120, 75, 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản', 580.00, 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản1.png', '2025-04-23 07:12:04', 2, 1);

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
(1, 'FLOWER LOGO MESH SHORT IN BLACK', 13, 1, 'Thông tin sản phẩm:\nUnisex – Phù hợp cho cả nam và nữ\n\nForm Oversize – Rộng rãi, thoải mái\n\nChất liệu: 100% vải lưới (Mesh)\n\nNgười mẫu mặc size L\n', 'hoavan.jpeg', 'hoavan.jpeg', 'hoavan.jpeg', 'hoavan.jpeg', '100000.00', '390000.00', 'black', 147),
(3, 'Girlfriend T-shirt', 6, 1, 'Chi tiết sản phẩm:\r\nThành phần: Bông.\r\n\r\nThoải mái, vừa vặn.\r\n\r\nCác chi tiết được in lụa ở mặt trước và mặt sau.', '24.png', '24.png', '24.png', '24.png', '390000.00', '0.00', 'black', 55),
(4, '“WHENEVER” NYLON SHORTS SS24 / WHITE', 13, 2, '                                                                                                                                                                                                                                                                                                                                                                          awesome T-Shirt                                                                                                                                                                                                                                                                                                                                    ', 'shorttrang.jpeg', 'shorttrang.jpeg', 'shorttrang.jpeg', 'shorttrang.jpeg', '290000.00', NULL, 'white', 140),
(5, ' STELLA SHORT V.2 ( CLOUDY )', 13, 2, '                                                                                                                                                                                                                                      awesome T-Shirt                                                                                                                                                                                                            ', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', 'z5516692087570_2867494ea628f53f7a2bf907fb36c365_56fed6aa6ad547f498f681ba817f0493_master.png', '190000.00', NULL, 'blue', 30),
(7, ' B-STREAM DENIM PANTS', 12, 2, '                                                                                                                                                                                                    awesome trousers                                                                                                                                                                        ', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', 'z5724002540208_f42e9b80441ecc85381283fa4bb523a3_864918661f234d3c866139d1ffcceaaa_master.png', '450000.00', NULL, 'black red', 99),
(8, 'CENTER DENIM PANTS', 12, 4, '\r\nTemporarily out of stock', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '0.00', '0.00', 'white', 0),
(10, 'Kính SOLIOS NUSON + Charm Kính HOA SEN ĐEN – RHODES', 4, 4, '                                                                                                                                                                                                                                                                                                                                                                                                                                                    order 3-5 days                                                                                                                                                                                                                                                                                                                                                                                        ', 'KinhHELIOSAEOS-HOASENDEN_8_1200x.png', 'kinh1.jpeg', 'kinh.jpeg', 'kinh1.jpeg', '790000', '950000', '0', 71),
(11, 'Nam Quốc Sơn Hà Ring Helios Black Silver', 4, 4, '\r\nTemporarily out of stock', 'vietnam.jpeg', 'vietnam.jpeg', 'vietnam.jpeg', 'vietnam.jpeg', '650000.00', '0.00', 'sliver 925', 0),
(12, 'Bandana Shirt Jacket - Black', 10, 2, '                                                      awesome T-shirt                                            ', '120.png', '120.png', '120.png', '120.png', '299000.00', NULL, 'black', 49),
(19, '  AA x HP LEATHER VARSITY // RED', 10, 1, '                                                                                                                                                                                                    Model M83 - 70kg wears: \r\n\r\nAA x HUNGPHAM VARSITY JACKET - L                                                                                                                                                                       ', 'varsity.png', 'varsity.png', 'varsity.png', 'varsity.png', '350000.00', '450000.00', 'red', 21),
(20, ' DC X DRAGON BALL Z  ', 10, 1, '\r\n            ', 'dc.jpeg', 'dc.jpeg', 'dc.jpeg', 'dc.jpeg', '450000.00', '500000.00', 'black white', 100),
(21, '“WHENEVER” BASIC T-SHIRT / PINK - WHITE - BLACK', 6, 2, ' Best seller', 'ao.jpeg', 'ao1.jpeg', '73.png', 'ao.jpeg', '250000.00', '350000.00', 'Ä‘en-pink-white', 33),
(23, 'Mate T-shirt - White', 6, 1, '', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '6873eb1a9d7725ecae0e65a1b4ac0860.jpg', '650000.00', NULL, 'white', 99),
(24, 'STELLA DENIM SHIRT ( LIGHT BLUE )/ ( LIGHT WHITE)', 7, 2, '                                                                                                            awesome T-Shirt                                                                                        ', 'somi1.jpeg', 'vn-11134207-7r98o-lwf8yh6hryzd44.jpeg', 'somi1.jpeg', 'vn-11134207-7r98o-lwf8yh6hryzd44.jpeg', '300000.00', NULL, 'white-blue', 98),
(25, 'Shirt-Angelo', 7, 1, '                                                      awesome T-Shirt                                            ', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '4cbda5a6-d3cb-0301-df10-0018ac672bc0.jpg', '450000.00', '399000.00', 'blue', 120),
(26, '“WHENEVER” HANDMADE KNIT SWEATER', 8, 4, 'order 10 days', 'sweater.jpeg', 'sweater.jpeg', 'sweater.jpeg', 'sweater.jpeg', '550000.00', NULL, 'cream-red', 41),
(28, 'Print Cardigan  DirtyCoins ', 8, 4, '                                                      Pre Order                                            ', 'xanhla.jpeg', 'hong1.jpeg', 'nau.jpeg', 'hong1.jpeg', '300000.00', NULL, 'cream', 42),
(29, 'SSMA KNIT SWEATER - BABY PINK/ BABY BLUE', 8, 2, '                                                                                                            Pre Order                                                                                        ', 'xanh.jpeg', 'hong.jpeg', 'xanh.jpeg', 'hong.jpeg', '300000.00', NULL, 'pink/blue', 200),
(30, 'Letters Monogram Knit Sweater - Black', 9, 4, ' .                              ', 'den.jpeg', 'den.jpeg', 'den.jpeg', 'den.jpeg', '299000.00', '439000', 'black', 0),
(31, 'RAVEN FUR JACKET', 7, 4, '                                                                                                            Pre Order                                                                                        ', 'ProductName1.png', 'ProductName2.png', 'ProductName3.png', 'ProductName4.png', '390000.00', NULL, '0', 55),
(32, 'Leather Biker Jacket - White ', 10, 2, '                                                                                                                                       Collab With Bray                                            ', 'bray.jpeg', 'ProductName2.jpeg', 'ProductName3.jpeg', 'ProductName4.jpeg', '499000.00', '599000.00', 'white', 510),
(33, 'Levents® Classic Hoodie', 9, 1, '                                                                                                            .                                                                                        ', 'aomautrang.jpeg', 'aomautrang.jpeg', 'lvtrang.jpeg', 'lvtrang.jpeg', '500000.00', NULL, 'cream', 83),
(34, 'Túi đeo chéo thời trang nam nữ Murphy Bag – Đen', 14, 2, '                                                                                                  restock                                                                                    ', '126-2-760x760.png', '126-2-760x760.png', '126-2-760x760.png', '126-2-760x760.png', '450000.00', NULL, 'black', 50),
(35, 'Checkerboard Print Mini Bowler Bag', 14, 1, '                                                      .                                            ', 'dctui.png', 'dctui.png', 'dctui.png', 'dctui.png', '369000.00', NULL, '0', 120),
(36, 'SPOILED | Shiny Black Croprint Mini Bag', 14, 2, '                                                      .                                            ', 'tui.jpeg', 'tui.jpeg', 'tui.jpeg', 'tui.jpeg', '239000.00', '439000', '0', 100),
(37, 'SPOILED | Silver Metallic Mini Bag', 14, 2, '                                                      .                                            ', 'tuibac.jpeg', 'tuibac.jpeg', 'tuibac.jpeg', 'tuibac.jpeg', '600000.00', NULL, '0', 42),
(38, 'SPOILED | Mini Bag / Matte Cobalt Blue', 14, 2, '                                                      .                                            ', 'tui2.jpeg', 'tui2.jpeg', 'tui2.jpeg', 'tui2.jpeg', '450000.00', NULL, '0', 56),
(39, 'BAGS - DirtyCoins | VIETNAMESE STREETWEAR BRAND', 14, 1, '                                                                                                            .                                            ThÃ´ng tin sáº£n pháº©m\r\n \r\n\r\nUnisex\r\nFree size\r\n100% Poly                                            ', 'tuidc.jpeg', 'tuidc.jpeg', 'tuidc.jpeg', 'tuidc.jpeg', '200000.00', NULL, 'cream-blue', 150),
(40, 'LIN SHIRT ( BLACK)', 7, 2, '                                                                                                                                                                                                                                                               ', 'somi2.jpeg', 'somi2.jpeg', 'somi2.jpeg', 'somi2.jpeg', '350000.00', NULL, '0', 80),
(41, 'SPOILED | Love Insde Round Pouch / Matte Candy Red', 14, 2, '                                                      CÃ²n Máº«u Red                                            ', 'tuitron.jpeg', 'tuitron.jpeg', 'tuitron.jpeg', 'tuitron.jpeg', '299000.00', NULL, '0', 100),
(42, 'SPOILED | League Maxi Leather Bag / SHINY BLACK', 14, 1, '                                                      color: white                                            ', 'tui1.jpeg', 'tui1.jpeg', 'tui1.jpeg', 'tui1.jpeg', '550000.00', NULL, '0', 100),
(43, 'Letters Monogram Denim Jersey Shirt - Black', 7, 2, '                                                      .                                            ', 'ProductName1.png', 'ProductName2.png', 'ProductName3.png', 'ProductName4.png', '260000.00', NULL, '0', 80),
(44, 'P LINEN SHIRT', 7, 2, '                                                                                                            .                                                                                        ', 'somi.jpeg', 'somi3.jpeg', 'somi.jpeg', 'somi3.jpeg', '190000.00', NULL, '0', 60),
(45, 'DAWN OVERSHIRT', 7, 2, '                                                      .                                            ', 'images (2).jpeg', 'images (2).jpeg', 'images (2).jpeg', 'images (2).jpeg', '419000.00', '7190000', '0', 200),
(46, ' If I Play I Play To Win T-Shirt - Black', 6, 2, '                                                                                                            .                                                                                        ', 'images.jpeg', 'images.jpeg', 'images.jpeg', 'images.jpeg', '800000.00', NULL, '0', 120),
(47, 'Heliotrope Helios Silver', 4, 1, '                                                      TÃªn sáº£n pháº©m: Nháº«n báº¡c S925 Blood Sun Helios Silver Original\r\n                              ', 'nhan.jpeg', 'ProductName2.jpeg', 'ProductName3.jpeg', 'ProductName4.jpeg', '300000.00', NULL, '0', 200),
(48, 'DirtyCoins Logo Denim Jacket | Blue Wash', 10, 2, 'best seller', 'DirtyCoins Denim Jacket _1.jpeg', 'DirtyCoins Denim Jacket _2.jpeg', 'DirtyCoins Denim Jacket _3.jpeg', 'DirtyCoins Denim Jacket _4.jpeg', '450000.00', '650000.00', '0', 50),
(49, 'University Felt Varsity Jacket - Red', 10, 1, '.', 'University Felt Varsity Jacket - Red_1_1731288864.jpeg', 'University Felt Varsity Jacket - Red_2_1731288864.jpeg', 'University Felt Varsity Jacket - Red_3_1731288864.jpeg', 'University Felt Varsity Jacket - Red_4_1731288864.jpeg', '750000.00', '0.00', '0', 40),
(50, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS', 12, 2, 'Diagonal Pleat Wool Viscose Trousers provides a fresh, refined look with its unique diagonal pleats and premium wool-viscose blend, offering comfort and breathability. The wide-leg silhouette and high-rise waist enhance the wearer\'s height and physique, creating a balanced, relaxed yet elegant style suitable for everyday wear.\r\n\r\nHook-and-bar and zip closure\r\nRelaxed fit \r\nShell: 20% Wool, 30% Viscose,49% Rayon 1% Elastane, Lining: 100% Cotton. Excluding trims / Machine wash', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_1_1731296781.png', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_2_1731296781.png', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_3_1731296781.png', 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS_4_1731296781.png', '450000.00', '0.00', '0', 60),
(51, 'Degrey Double Leather Basic Balo Kem - LBBDK', 20, 2, 'Thông tin sản phẩm:\r\nChất liệu: Simili\r\n\r\nHọa tiết: Thêu trực tiếp\r\n\r\nSize: 42cm x 30cm x 16cm\r\n\r\nNgăn chống sốc: Đựng vừa laptop 15.6 inch\r\n\r\nThương hiệu: Degrey\r\n\r\nSản xuất: Việt Nam\r\n\r\nMàu sắc và họa tiết được thiết kế riêng bởi team design DEGREY\r\n\r\nHướng dẫn bảo quản sản phẩm DEGREY:\r\nKhông dùng hóa chất tẩy mạnh lên sản phẩm\r\n\r\nKhông dùng vật dụng sắc, nhọn cà trực tiếp lên bề mặt balo\r\n\r\nKhông giặt máy\r\n\r\nSử dụng bàn chải có lông mềm\r\n\r\nTuyệt đối không dùng bàn chải cước cứng – sẽ gây ra các vết xước trên nền vải\r\n\r\nChính sách đổi sản phẩm:\r\n1. Điều kiện đổi hàng\r\nBạn lưu ý giữ lại hóa đơn để đổi hàng trong vòng 30 ngày\r\n\r\nKhông áp dụng đổi hàng với các mặt hàng giảm giá, phụ kiện cá nhân (áo lót, khẩu trang, vớ,...)\r\n\r\nTất cả sản phẩm đã mua sẽ không được đổi trả bằng tiền mặt\r\n\r\nBạn có thể đổi size hoặc sản phẩm khác trong 30 ngày (Lưu ý: sản phẩm chưa qua sử dụng, còn tag nhãn và hóa đơn mua hàng)\r\n\r\nVui lòng gửi cho chúng mình clip đóng gói và hình ảnh đơn hàng đổi trả của bạn để nhân viên xác nhận và tiến hành đơn đổi trả\r\n\r\n2. Trường hợp khiếu nại\r\nPhải có video unbox hàng\r\n\r\nQuay rõ nét 6 mặt của gói hàng\r\n\r\nQuay rõ: Tên người nhận, mã đơn, địa chỉ, số điện thoại\r\n\r\nVideo không cắt ghép, chỉnh sửa\r\n\r\nDegrey không tiếp nhận giải quyết các trường hợp không thỏa các điều kiện trên', 'Degrey Double Leather Basic Balo Kem - LBBDK_1_1731297373.jpeg', 'Degrey Double Leather Basic Balo Kem - LBBDK_2_1731297373.jpeg', 'Degrey Double Leather Basic Balo Kem - LBBDK_3_1731297373.jpeg', 'Degrey Double Leather Basic Balo Kem - LBBDK_4_1731297373.jpeg', '360000.00', '0.00', '0', 90),
(52, 'Dico Wavy Backpack V3', 20, 2, 'Chi tiết sản phẩm:\nBa lô form cơ bản, ngăn mở bằng khóa kéo\n\nChất liệu mặt ngoài: Da PU cao cấp\n\nChất liệu lót: Vải poly\n\nLòng trong ba lô có thể đựng vừa laptop 15.6\'\'\n\nĐầu khóa kéo có dập logo chữ Y\n\nDây đai đeo vai có dệt chìm logo DirtyCoins\n\nMặt trước ba lô thêu logo DirtyCoins Wavy\n\nKích thước: Ngang x Rộng x Cao: 32 x 13 x 44 cm', 'Dico Wavy Backpack V3_1_1731297458.jpeg', 'Dico Wavy Backpack V3_2_1731297458.jpeg', 'Dico Wavy Backpack V3_3_1731297458.jpeg', 'Dico Wavy Backpack V3_4_1731297458.jpeg', '120000.00', '0.00', '0', 100),
(53, 'Letters Monogram Denim Backpack - Black', 20, 2, 'hi tiết sản phẩm:\r\nBa lô form cơ bản, ngăn mở bằng khóa kéo.\r\n\r\nChất liệu mặt ngoài: Cotton Denim.\r\n\r\nChất liệu lót: vải poly.\r\n\r\nLòng trong ba lô có thể đựng vừa laptop 15.6\'\'.\r\n\r\nĐầu khóa kéo có dập logo chữ Y.\r\n\r\nPattern monogram được dệt trên bề mặt vải thân balo và quai đeo.\r\n\r\nKích thước: Ngang x Rộng x Cao: 32 x 13 x 44 cm', 'balo.jpeg', 'Letters Monogram Denim Backpack - Black_2_1731297528.jpeg', 'Letters Monogram Denim Backpack - Black_3_1731297528.jpeg', 'Letters Monogram Denim Backpack - Black_4_1731297528.jpeg', '450000.00', '0.00', '0', 78),
(75, 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản', 8, 5, '                                                                                        Chất liệu vải bền bỉ và dễ dàng trong việc bảo quản. Vải không nhăn, giúp áo luôn giữ được hình dáng và màu sắc ban đầu sau nhiều lần giặt.\n\nPhân loại màu:2 màu sắc\n\nChất liệu:Len cao cấp\n\nKiểu dáng :Áo len nam cao cấp\n\nChất liệu đảm bảo, mang lại cảm giác thoải mái, dễ chịu, chuẩn form dáng                                                                                                                        ', 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản1.png', 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản2.png', 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản3.png', 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản4.png', '580000', '0', '', 92);

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
(3, 'Sold Out'),
(4, 'Pre Order'),
(5, 'Hidden');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(108) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_dob` date DEFAULT NULL,
  `user_gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `full_name`, `user_email`, `user_password`, `user_phone`, `user_address`, `user_dob`, `user_gender`, `is_locked`) VALUES
(6, 'kecuong71@gmail.com', NULL, 'kecuong71@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '', '', NULL, NULL, 0),
(7, 'ke', 'Cường Nguyễn', 'ke@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0335392759', 'Quận 5', '0000-00-00', 'Nam', 1),
(9, 'khang', 'Đỗ Minh Khang', 'khang@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0336773026', '127A Thạnh Lộc 28, phường Thạnh Lộc, Quận 12, Thành phố Hồ Chí Minh', '2025-04-01', 'Nam', 0),
(10, 'thu', 'Anh Thư', 'thu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0336773021', 'Quận 5', '2025-04-01', 'Nữ', 1);

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
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_status_products` (`status_products_id`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_status_products` FOREIGN KEY (`status_products_id`) REFERENCES `status_products` (`status_products_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
