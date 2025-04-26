-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 26, 2025 lúc 03:05 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

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
(120, 1160.00, 'cancelled', 9, '0336773026', '127A Thạnh Lộc 28', '2025-03-04 07:12:04', 'cash'),
(135, 390.00, 'pending', 14, '0358416259', 'Quận 1', '2025-04-26 00:54:48', 'bank'),
(139, 500.00, 'Pending', 9, '0336773026', '127A Thạnh Lộc 28, phường Thạnh Lộc, Quận 12, Thành phố Hồ Chí Minh', '2025-04-26 02:37:49', 'cash'),
(140, 780.00, 'Pending', 9, '0336773026', '127A Thạnh Lộc 28, phường Thạnh Lộc, Quận 12, Thành phố Hồ Chí Minh', '2025-04-26 02:38:32', 'cash'),
(141, 300.00, 'Pending', 6, '', '', '2025-04-26 02:44:33', 'cash');

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
(111, 120, 75, 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản', 580.00, 'Áo len nam dệt kim cổ tròn dài tay màu sắc tương phản1.png', '2025-04-23 07:12:04', 2, 1),
(134, 135, 3, 'Girlfriend T-shirt', 390.00, 'thuongbangai.jpg', '2025-04-26 00:54:48', 1, 4),
(138, 139, 33, 'Levents® Classic Hoodie', 500.00, 'le.jpg', '2025-04-26 02:37:49', 1, 3),
(139, 140, 3, 'Girlfriend T-shirt', 390.00, 'thuongbangai.jpg', '2025-04-26 02:38:32', 1, 3),
(140, 140, 3, 'Girlfriend T-shirt', 390.00, 'thuongbangai.jpg', '2025-04-26 02:38:32', 1, 2),
(141, 141, 29, 'SSMA KNIT SWEATER - BABY PINK/ BABY BLUE', 300.00, 'baby.jpg', '2025-04-26 02:44:33', 1, 4);

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
(1, 'FLOWER LOGO MESH SHORT IN BLACK', 13, 1, 'Thông tin sản phẩm:\nUnisex – Phù hợp cho cả nam và nữ\n\nForm Oversize – Rộng rãi, thoải mái\n\nChất liệu: 100% vải lưới (Mesh)\n\nNgười mẫu mặc size L\n', 'hoavan.jpg', 'hoavan1.jpg', 'hoavan2.jpg', 'hoavan3.jpg', '100000.00', '390000.00', 'black', 145),
(3, 'Girlfriend T-shirt', 6, 1, '                                            Chi tiết sản phẩm:\r\nThành phần: Bông.\r\n\r\nThoải mái, vừa vặn.\r\n\r\nCác chi tiết được in lụa ở mặt trước và mặt sau.                                        ', 'thuongbangai.jpg', 'thuongbangai1.jpg', 'thuongbangai2.jpg', 'thuongbangai3.jpg', '390000', '0', 'black', 50),
(4, '“WHENEVER” NYLON SHORTS SS24 / WHITE', 13, 2, 'CROPPED FIT\n\nCÓ LỚP LÓT LƯỚI BÊN TRONG\n2 TÚI CÓ ZIP BÊN HÔNG QUẦN\n2 TÚI MẶT SAU QUẦN\nTHÊU TÊN “WHENEVER ATELIER” Ở 2 BÊN ỐNG QUẦN\nCÓ DÂY ĐỂ TĂNG GIẢM KÍCH THƯỚC EO\nNHÃN WHENEVER ATELIER NẰM Ở MẶT TRƯỚC TRUNG TÂM.\n\nCHẤT LIỆU: 100% NYLON\n  làm sao để đoạn này cách dòng ra trên csdl trên xampp', 'whenever.jpg', 'whenever3.jpg', 'whenever1.jpg', 'whenever2.jpg', '290000.00', NULL, 'white', 139),
(5, ' STELLA SHORT V.2 ', 13, 2, 'Taking a deep breath of fresh, cloudy air...\r\n\r\nBộ sưu tập lần này được La Sol chú trọng vào trải nghiệm của chất liệu, hơn quá nửa sản phẩm, chúng tôi sử dụng chất liệu linen cao cấp, đem lại cảm giác mềm mại, êm ái khi sử dụng. Như những áng mây trôi qua những ngày nắng hè, mang lại cho bạn sự thoải mái và cảm giác bay bổng, thư thái, tự do trong tâm hồn.', 'stellashort.jpg', 'stellashort1.jpg', 'stellashort2.jpg', 'stellashort3.jpg', '190000.00', NULL, 'blue', 30),
(7, ' B-STREAM DENIM PANTS', 12, 2, 'Mô tả sản phẩm \r\n\r\n- Quần dáng ống rộng \r\n\r\n- Kĩ thuật đúp lớp, rạch bề mặt và may thủ công tạo hiệu ứng dòng chảy cả 2 mặt trước và sau \r\n\r\n\r\n\r\nChất liệu: denim định lượng vừa \r\n\r\nMàu sắc: đen/ đỏ ', 'b-streamdenim.jpg', 'b-streamdenim1.jpg', 'b-streamdenim2.jpg', 'b-streamdenim3.jpg', '450000.00', NULL, 'black red', 98),
(8, 'CENTER DENIM PANTS', 12, 4, '\r\nTemporarily out of stock', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '29_391c02ad08a949208c349829d7a705ef_master.png', '0.00', '0.00', 'white', 0),
(10, 'Kính SOLIOS NUSON + Charm Kính HOA SEN ĐEN – RHODES', 4, 4, '                                                                                                                                                                                                                                                                                                                                                                                                                                                    order 3-5 days                                                                                                                                                                                                                                                                                                                                                                                        ', 'helio1.jpg', 'helio.jpg', 'helio1.jpg', 'helio1.jpg', '790000', '950000', '0', 71),
(11, 'Nam Quốc Sơn Hà Ring Helios Black Silver', 4, 4, '\r\nTemporarily out of stock', 'nhanhelio.jpg', 'nhanhelio1.jpg', 'nhanhelio.jpg', 'nhanhelio.jpg', '650000.00', '0.00', 'sliver 925', 0),
(12, 'Bandana Shirt Jacket - Black', 10, 2, 'Chi tiết sản phẩm:\r\n• Chất liệu: Polyester.\r\n• Relaxed Fit.\r\n• Hoạ tiết paisley đặc trưng của DirtyCoins được in chuyển nhiệt trên bề mặt vải.\r\n', 'bandana.jpg', 'bandana3.jpg', 'bandana1.jpg', 'bandana2.jpg', '299000.00', NULL, 'black', 49),
(19, '  AA x HP LEATHER VARSITY // RED', 10, 1, 'Chất liệu: Da PVC chuyên dụng riêng Aa Stu. ( kháng nước, chống trầy)\r\nXử lý: Đường may 1 kim và 2 kim , kẹp 2 lớp ( Khaki )\r\nKỹ thuật: + Rã đô và vai đặc trưng Aa \r\n+ Nút bấm dập logo A\r\n+ Thêu xù trực tiếp Logo Aa\r\nTrọng lượng: 1500gr', 'aastu.jpg', 'aastu1.jpg', 'aastu2.jpg', 'aastu3.jpg', '350000.00', '450000.00', 'red', 21),
(20, ' DC X DRAGON BALL Z  ', 10, 1, '• LIMITED EDITION - ONLY 120 PIECES\r\n• Kích thước: M - L - XL\r\n• Chất liệu: Vải dạ & Da PU; chất liệu lót: Polyester.\r\n• Regular Fit.\r\n• Nhãn thương hiệu trang trí ở sườn áo.\r\n• Mặt Trước, mặt sau: Sử dụng chất liệu Khaki làm nền và thêu 2D trực tiếp lên bề mặt.\r\n• Nhãn trang trí BST DC | DBZ may ở thân trước.     ', 'dragonball.jpg', 'dragonball1.jpg', 'dragonball2.jpg', 'dragonball3.jpg', '450000.00', '500000.00', 'black white', 100),
(21, '“WHENEVER” BASIC T-SHIRT / PINK - WHITE - BLACK', 6, 2, 'Brand: Whenever \r\nform áo: Boxy', 'atelier.jpg', 'atelier1.jpg', 'atelier2.jpg', 'atelier3.jpg', '250000.00', '350000.00', 'black-pink-white', 31),
(23, 'Áo thun nhà DC X BRAY', 6, 1, 'Chất liệu : Cotton 2c 100% \r\nCond: New ', 'mate.jpg', 'mate1.jpg', 'mate2.jpg', 'mate3.jpg', '650000.00', NULL, 'white', 99),
(24, 'STELLA DENIM SHIRT ( LIGHT BLUE )/ ( LIGHT WHITE)', 7, 2, '𝐌𝐈𝐍𝐈 𝐂𝐎𝐋𝐋𝐄𝐂𝐓𝐈𝐎𝐍 𝐒𝐔𝐌𝐌𝐄𝐑\'𝟐𝟒\r\n\"𝗼𝗻 𝗮 𝗰𝗹𝗼𝘂𝗱𝘆 𝗱𝗮𝘆\"\r\nTaking a deep breath of fresh, cloudy air...\r\nBộ sưu tập lần này được La Sol chú trọng vào trải nghiệm của chất liệu, hơn quá nửa sản phẩm, chúng tôi sử dụng chất liệu linen cao cấp, đem lại cảm giác mềm mại, êm ái khi sử dụng. Như những áng mây trôi qua những ngày nắng hè, mang lại cho bạn sự thoải mái và cảm giác bay bổng, thư thái, tự do trong tâm hồn.', 'lasol.jpg', 'lasol1.jpg', 'lasol2.jpg', 'lasol3.jpg', '300000.00', NULL, 'white-blue', 98),
(25, 'Shirt-Angelo', 7, 1, 'Áo Sơ Mi Cổ Mở Tay Ngắn Sợi Nhân Tạo Nhanh Khô Biểu Tượng Dáng Rộng BST Thiết Kế Angelo Ver1\r\n1. Kiểu sản phẩm: Áo sơ mi cổ mở, tay ngắn, dáng rộng.\r\n2. Ưu điểm: Co giãn 2 chiều, bề mặt mềm mại, nhanh khô, độ bền cao.\r\n3. Chất liệu: Vải dù 100% polyester.\r\n4. Kỹ thuật: In chuyển nhiệt tạo họa tiết sắc nét, bền màu.\r\n5. Phù hợp với: Những người yêu thích phong cách thoải mái, năng động, trẻ trung.\r\n6. Thuộc Bộ Sưu Tập: Angelo', 'angelo.jpg', 'angelo1.jpg', 'angelo2.jpg', 'angelo3.jpg', '399000.00', '550000', 'blue', 120),
(26, '“WHENEVER” HANDMADE KNIT SWEATER', 8, 4, 'order 10 days', 'knit.jpg', 'knit.jpg', 'knit.jpg', 'knit.jpg', '550000.00', NULL, 'cream-red', 41),
(28, 'Print Cardigan  DirtyCoins ', 8, 4, '                                                      Pre Order                                            ', 'cardi.jpg', 'cardi1.jpg', 'cardi2.jpg', 'cardi3.jpg', '300000.00', NULL, 'cream', 42),
(29, 'SSMA KNIT SWEATER - BABY PINK/ BABY BLUE', 8, 2, '100% ACRYLIC FIBER\r\nOVERSIZE\r\nMADE IN VIET NAM', 'baby.jpg', 'baby1.jpg', 'baby2.jpg', 'baby3.jpg', '300000.00', NULL, 'pink/blue', 195),
(30, 'Letters Monogram Knit Sweater - Black', 9, 4, ' .                              ', 'mono.jpg', 'mono1.jpg', 'mono.jpg', 'mono.jpg', '299000.00', '439000', 'black', 0),
(31, 'RAVEN FUR JACKET', 7, 4, '                                                                                                            Pre Order                                                                                        ', 'reve.jpg', 'reve1.jpg', 'reve.jpg', 'reve.jpg', '390000.00', NULL, '0', 55),
(32, 'Leather Biker Jacket - White ', 10, 2, 'LIMITED EDITION.\r\n• Mặt ngoài: Da PU; lót trong: Polyester.\r\n• Relaxed Fit.\r\n• Logo DirtyCoins được thêu trên ngực áo.\r\n• Mặt sau thêu logo B Ray và may tag da thương hiệu.', 'bike.jpg', 'bike1.jpg', 'bike2.jpg', 'bike3.jpg', '499000.00', '599000.00', 'white', 510),
(33, 'Levents® Classic Hoodie', 9, 1, '+ Áo thun tay dài LEVENTS University Longsleeve/ Cream\r\nCOLOR : CREAM\r\nMATERIAL: 100% COTTON\r\nSản xuất và chịu trách nhiệm: LEVENTS', 'le.jpg', 'le1.jpg', 'le2.jpg', 'le3.jpg', '500000.00', NULL, 'cream', 62),
(34, 'Túi đeo chéo thời trang nam nữ Murphy Bag – Đen', 14, 2, 'Chất liệu: PU\r\nSize:  19 cm *12 cm*5 cm \r\nMÀU : black\r\nĐiểm nổi bật của sản phẩm:\r\nSử dụng vải xốp nylon tùy chỉnh, mềm mại và thoải mái, nhẹ và thích hợp để đi chơi\r\nThiết kế dây đeo vai có thể điều chỉnh, dễ dàng điều chỉnh theo nhu cầu của bạn.     \r\nHàng có sẵn ạ.', 'pu.jpg', 'pu1.jpg', 'pu2.jpg', 'pu3.jpg', '450000.00', NULL, 'black', 50),
(35, 'Checkerboard Print Mini Bowler Bag', 14, 1, 'Chất liệu da PU, được lót dù bên trong.\r\n• Hình in trên bề mặt da áp dụng kĩ thuật in kéo lụa.\r\n• Dây đeo bằng da PU có khóa điều chỉnh độ dài.\r\n• 1 ngăn lớn chính và 1 ngăn phụ phía trong.\r\n• Có kèm một túi đựng thẻ nhỏ cùng màu.\r\n• Kích thước túi: Dài x Rộng x Cao: 25 CM x 11 CM x 15 CM', 'che.jpg', 'che1.jpg', 'che2.jpg', 'che3.jpg', '369000.00', NULL, '0', 120),
(36, 'SPOILED | Shiny Black Croprint Mini Bag', 14, 2, '- Kích thước: 13x17 cm\r\n- Chất liệu: Da nhân tạo, dập nổi\r\n- Màu sắc: Đen\r\n- Đặc điểm: Quai cầm tay & quai đeo vai\r\n- Ngăn chứa: Một ngăn lớn, có khoa nam châm', 'spo.jpg', 'spo1.jpg', 'spo2.jpg', 'spo3.jpg', '239000.00', '439000', '0', 100),
(37, 'SPOILED | Silver Metallic Mini Bag', 14, 2, 'Túi đeo chéo quai cầm SPOILED Da PU Bạc ánh quang M Silver Metallic Top-Handle Bag\r\nThông tin sản phẩm:\r\n- Màu: Bạc ánh quang\r\n- Chất liệu: Da PU\r\n- Kích thước: Medium/ Ngang 37cm x Cao 27cm\r\n- Quai cầm tay & quai đeo vai\r\n- Ngăn chứa: Một miệng lớn có nam châm & một ngăn bao tử (có dây kéo).\r\n- Đi kèm: Dust Bag + Monogram Box', 'lep2.jpg', 'lep3.jpg', 'lep.jpg', 'lep.jpg', '600000.00', NULL, '0', 42),
(38, 'SPOILED | Mini Bag / Matte Cobalt Blue', 14, 2, 'Túi đeo chéo quai cầm SPOILED Da PU Bạc ánh quang M Silver Metallic Top-Handle Bag\r\nThông tin sản phẩm:\r\n- Màu: Bạc ánh quang\r\n- Chất liệu: Da PU\r\n- Kích thước: Medium/ Ngang 37cm x Cao 27cm\r\n- Quai cầm tay & quai đeo vai\r\n- Ngăn chứa: Một miệng lớn có nam châm & một ngăn bao tử (có dây kéo).\r\n- Đi kèm: Dust Bag + Monogram Box', 'mat.jpg', 'mat1.jpg', 'mat2.jpg', 'mat3.jpg', '450000.00', NULL, '0', 56),
(39, 'BAGS - DirtyCoins | VIETNAMESE STREETWEAR BRAND', 14, 1, 'Chất liệu da PU, được lót dù bên trong.\n• Hình in trên bề mặt da áp dụng kĩ thuật in kéo lụa.\n• Dây đeo bằng da PU có khóa điều chỉnh độ dài.\n• 1 ngăn lớn chính và 1 ngăn phụ phía trong.\n• Có kèm một túi đựng thẻ nhỏ cùng màu.\n• Kích thước túi: Dài x Rộng x Cao: 25 CM x 11 CM x 15 CM', 'bow.jpg', 'bow1.jpg', 'bow2.jpg', 'bow3.jpg', '200000.00', NULL, 'cream-blue', 150),
(40, 'LIN SHIRT ', 7, 2, 'ÁO SƠ MI GHÉP\nHYDRA PANTS\nSTYX WRAP SKIRT', 'l.jpg', 'l1.jpg', 'l2.jpg', 'l3.jpg', '350000.00', NULL, '0', 80),
(41, 'SPOILED | Love Insde Round Pouch / Matte Candy Red', 14, 2, 'Túi ví tròn đeo chéo SPOILED Da cao cấp / 4 màu\n_ Màu: Đen, đỏ, nâu bò, kem\n_ Chất liệu: Da nhân tạo cao cấp\n_ Kích thước: 9cm\n_ Một ngăn có dây kéo\n_ Túi ví tròn có móc khóa có thể dùng để trang trí trực tiếp trên túi xách, trang trí đai quần, v.v.', 'led.jpg', 'led1.jpg', 'led2.jpg', 'led3.jpg', '299000.00', NULL, '0', 100),
(42, 'SPOILED | League Maxi Leather Bag / SHINY BLACK', 14, 1, 'Túi ví tròn đeo chéo SPOILED Da cao cấp / 4 màu\n_ Màu: Đen, đỏ, nâu bò, kem\n_ Chất liệu: Da nhân tạo cao cấp\n_ Kích thước: 9cm\n_ Một ngăn có dây kéo\n_ Túi ví tròn có móc khóa có thể dùng để trang trí trực tiếp trên túi xách, trang trí đai quần, v.v.', 'lea.jpg', 'lea1.jpg', 'lea2.jpg', 'lea3.jpg', '550000.00', NULL, '0', 100),
(43, 'Letters Monogram Denim Jersey Shirt - Black', 7, 2, 'Chất liệu: Cotton.\n• Relaxed Fit.\n• Hình in mặt trước áo áp dụng công nghệ in kéo lụa.', 'je.jpg', 'je1.jpg', 'je2.jpg', 'je3.jpg', '260000.00', NULL, '0', 80),
(44, 'P LINEN SHIRT', 7, 2, '𝐌𝐈𝐍𝐈 𝐂𝐎𝐋𝐋𝐄𝐂𝐓𝐈𝐎𝐍 𝐒𝐔𝐌𝐌𝐄𝐑\'𝟐𝟒\r\n\"𝗼𝗻 𝗮 𝗰𝗹𝗼𝘂𝗱𝘆 𝗱𝗮𝘆\"\r\n𝗟𝗢𝗢𝗞 𝟬𝟭: Mang đến sự lựa chọn hoàn hảo cho mùa hè bởi những ưu điểm tuyệt vời của chất liệu linen cao cấp.\r\n𝐏 𝐋𝐈𝐍𝐄𝐍 𝐒𝐇𝐈𝐑𝐓\r\nChất liệu: Linen tưng\r\nMàu sắc: Trắng kem/ Xanh đen/ Muối tiêu', 'p.jpg', 'p1.jpg', 'p2.jpg', 'p3.jpg', '190000.00', NULL, '0', 60),
(45, 'DAWN OVERSHIRT', 7, 2, 'Colour: Blue\nFabric: Denim', 'neel.jpg', 'neel1.jpg', 'neel2.jpg', 'neel3.jpg', '419000.00', '719000', '0', 200),
(46, ' If I Play I Play To Win T-Shirt - Black/White', 6, 2, '• Chất liệu: Cotton.\n• Relaxed Fit.\n• Hình in mặt trước áo áp dụng công nghệ in kéo lụa.', 'pl.jpg', 'pl1.jpg', 'pl2.jpg', 'pl3.jpg', '800000.00', NULL, '0', 120),
(47, 'Heliotrope Helios Silver', 4, 1, 'Chất liệu: Bạc S925\r\nLoại đá sử dụng: Huyết ngọc\r\nThương hiệu: HELIOS\r\nBảo hành: Theo chính sách bảo hành và nhận đánh sáng sản phẩm trọn đời\r\nCâu chuyện từ nhà thiết kế: Đá huyết ngọc là một loại khoáng vật đặc biệt, còn được biết đến với tên gọi Heliotrope.\r\nNgười Hy Lạp xưa tin rằng nó có thể nhuộm đỏ hình ảnh phản chiếu của Mặt Trời khi hoàng hôn hoặc khi đặt dưới nước.\r\nNăng lượng của huyết ngọc mang sự thuần khiết của sinh khí từ Mặt Trời, đại diện cho sự sống cũng như sức mạnh khổng lồ.\r\nChính vì thế, các chiến binh cổ đại thường đeo huyết ngọc như một tấm bùa hộ mệnh, vừa bảo vệ, chữa trị vừa tiếp thêm sự dũng cảm cho họ nơi chiến trận.', 'he.jpg', 'he1.jpg', 'he2.jpg', 'he3.jpg', '300000.00', NULL, '0', 200),
(48, 'DirtyCoins Logo Denim Jacket | Blue Wash', 10, 2, '• Chất liệu: Cotton Denim.\r\n• Relaxed Fit.\r\n• Bề mặt vải dệt jacquard logo pattern.\r\n• Vải có hiệu ứng wash bề mặt.\r\n• Nút kim loại dập logo thương hiệu.\r\n• Nhãn da thương hiệu may ở lai áo thân sau.', 'DirtyCoins Denim Jacket _1.jpeg', 'DirtyCoins Denim Jacket _2.jpeg', 'DirtyCoins Denim Jacket _3.jpeg', 'DirtyCoins Denim Jacket _4.jpeg', '450000.00', '650000.00', '0', 50),
(49, 'University Felt Varsity Jacket - Red', 10, 1, '• Mặt ngoài: Sợi tổng hợp phối da PU; lót trong: Polyester.\r\n• Màu sắc: Đỏ \r\n• Regular Fit.\r\n• Phối đắp mảnh da trên cầu vai.\r\n• Hình thêu logo trên mặt trước và mặt sau lưng áo.', 'fe.jpg', 'fe1.jpg', 'fe2.jpg', 'fe3.jpg', '750000.00', '0.00', '0', 40),
(50, 'BLACK DIAGONAL PLEAT WOOL-VISCOSE TROUSERS', 12, 2, 'Straight Design. Long design. Medium waist. Two side pockets. Loops. Concealed button, hook and zip fastening. Dart detail. Welt pocket on the back.', 'fit.jpg', 'fit1.jpg', 'fit2.jpg', 'fit3.jpg', '450000.00', '0.00', '0', 54),
(51, 'Degrey Double Leather Basic Balo Kem - LBBDK', 20, 2, 'Tặng kèm giấy thơm, tag sản phẩm\r\nKích thước:  40cm x 30cm x 15cm (+-5cm độ phồng khi đựng đồ trong balo)\r\nChất liệu: da simili\r\nHình in sắc nét, Form & size chuẩn hàng hãng chuẩn hàng 1:1\r\nNhiều ngăn lớn & nhỏ siêu tiện dụng', 'de.jpg', 'de1.jpg', 'de2.jpg', 'de3.jpg', '360000.00', '0.00', '0', 90),
(52, 'Dico Wavy Backpack V3', 20, 2, 'Màu: Đen\r\n• Ba lô form cơ bản, ngăn mở bằng khóa kéo.\r\n• Chất liệu mặt ngoài: da PU cao cấp .\r\n• Chất liệu lót: vải poly.\r\n• Lòng trong ba lô có thể đựng vừa laptop 15.6\'\'.\r\n• Đầu khóa kéo có dập logo chữ Y.\r\n• Dây đai đeo vai có dệt chìm logo DirtyCoins.\r\n• Mặt trước ba lô thêu logo DirtyCoins Wavy.\r\n• Kích thước: Ngang x Rộng x Cao: 32 x 13 x 44 cm', 'di.jpg', 'di1.jpg', 'di2.jpg', 'di3.jpg', '449000', '0.00', '0', 100),
(53, 'Letters Monogram Denim Backpack - Black', 20, 2, '• Ba lô form cơ bản, ngăn mở bằng khóa kéo.\r\n• Chất liệu mặt ngoài: Cotton Denim .\r\n• Chất liệu lót: vải poly.\r\n• Lòng trong ba lô có thể đựng vừa laptop 15.6\'\'.\r\n• Đầu khóa kéo có dập logo chữ Y.\r\n• Pattern monogram được dệt trên bề mặt vải thân balo và quai đeo.\r\n• Kích thước: Ngang x Rộng x Cao: 32 x 13 x 44 cm\r\n\r\n', 'mo.jpg', 'mo1.jpg', 'mo2.jpg', 'mo3.jpg', '450000.00', '0.00', '0', 78),
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
(10, 'thu', 'Anh Thư', 'thu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0336773021', 'Quận 5', '2025-04-01', 'Nữ', 1),
(11, '', 'Lê Duy Khang', 'dkhang@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0334126026', 'Quận 8', '2003-10-04', 'Nam', 0),
(12, 'Thu', NULL, 'thu1@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '', '', NULL, NULL, 0),
(13, '', 'Võ Đinh Xuân Hoàng', 'hoang@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0334126416', 'Quận 3', '2003-03-13', 'Nam', 0),
(14, 'Hưng', 'Nguyễn Kế Hưng', 'hung@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0358416259', 'Quận 1', '2002-01-29', 'Nam', 0);

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
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
