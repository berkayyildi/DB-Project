
-- ----------------------------
-- Table structure for Categories
-- ----------------------------
DROP TABLE IF EXISTS `Categories`;
CREATE TABLE `Categories`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of Categories
-- ----------------------------
INSERT INTO `Categories` VALUES (1, 'Meyve, Sebze');
INSERT INTO `Categories` VALUES (2, 'Et, Balık, Kümes');
INSERT INTO `Categories` VALUES (3, 'Süt, Kahvaltılık');
INSERT INTO `Categories` VALUES (4, 'Gıda, Şekerleme');
INSERT INTO `Categories` VALUES (5, 'İçecek');
INSERT INTO `Categories` VALUES (6, 'Deterjan, Temizlik');
INSERT INTO `Categories` VALUES (7, 'Kağıt, Kozmetik');
INSERT INTO `Categories` VALUES (8, 'Bebek, Oyuncak');
INSERT INTO `Categories` VALUES (9, 'Ev, Pet');

-- ----------------------------
-- Table structure for Cities
-- ----------------------------
DROP TABLE IF EXISTS `Cities`;
CREATE TABLE `Cities`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `disctinct_id` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_distinct`(`disctinct_id`) USING BTREE,
  CONSTRAINT `fk_distinct` FOREIGN KEY (`disctinct_id`) REFERENCES `Districts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for Districts
-- ----------------------------
DROP TABLE IF EXISTS `Districts`;
CREATE TABLE `Districts`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `district_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of Districts
-- ----------------------------
INSERT INTO `Districts` VALUES (1, 'Akdeniz Bölgesi');
INSERT INTO `Districts` VALUES (2, 'GüneyDoğu Anadolu');
INSERT INTO `Districts` VALUES (3, 'Ege Bölgesi');
INSERT INTO `Districts` VALUES (4, 'Doğu Anadolu Bölgesi ');
INSERT INTO `Districts` VALUES (5, 'Karadeniz Bölgesi ');
INSERT INTO `Districts` VALUES (6, 'İç Anadolu Bölgesi');
INSERT INTO `Districts` VALUES (7, 'Marmara Bölgesi');

-- ----------------------------
-- Table structure for Customers
-- ----------------------------
DROP TABLE IF EXISTS `Customers`;
CREATE TABLE `Customers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customername` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for Init_Markets
-- ----------------------------
DROP TABLE IF EXISTS `Init_Markets`;
CREATE TABLE `Init_Markets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_market`(`market_id`) USING BTREE,
  INDEX `fk_city`(`city_id`) USING BTREE,
  CONSTRAINT `fk_market` FOREIGN KEY (`market_id`) REFERENCES `Markets` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_city` FOREIGN KEY (`city_id`) REFERENCES `Cities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for Markets
-- ----------------------------
DROP TABLE IF EXISTS `Markets`;
CREATE TABLE `Markets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of Markets
-- ----------------------------
INSERT INTO `Markets` VALUES (1, 'Bim');
INSERT INTO `Markets` VALUES (2, 'A-101');
INSERT INTO `Markets` VALUES (3, 'Şok');
INSERT INTO `Markets` VALUES (4, 'Migros');
INSERT INTO `Markets` VALUES (5, 'Carrefour');
INSERT INTO `Markets` VALUES (6, 'Kipa');
INSERT INTO `Markets` VALUES (7, 'Hakmar');
INSERT INTO `Markets` VALUES (8, 'Tansaş');
INSERT INTO `Markets` VALUES (9, 'Makro Center');
INSERT INTO `Markets` VALUES (10, 'Dia Sa');

-- ----------------------------
-- Table structure for Products
-- ----------------------------
DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price` decimal(10, 2) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_category`(`category_id`) USING BTREE,
  CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for Sales
-- ----------------------------
DROP TABLE IF EXISTS `Sales`;
CREATE TABLE `Sales`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sale_date` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_customers`(`customer_id`) USING BTREE,
  INDEX `fk_salesmans`(`salesman_id`) USING BTREE,
  INDEX `fk_pruducts`(`product_id`) USING BTREE,
  CONSTRAINT `fk_customers` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_pruducts` FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_salesmans` FOREIGN KEY (`salesman_id`) REFERENCES `Salesmans` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for Salesmans
-- ----------------------------
DROP TABLE IF EXISTS `Salesmans`;
CREATE TABLE `Salesmans`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salesmanname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for Init_Works
-- ----------------------------
DROP TABLE IF EXISTS `Init_Works`;
CREATE TABLE `Init_Works`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `market_id` int(11) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_salesman`(`salesman_id`) USING BTREE,
  CONSTRAINT `fk_salesman` FOREIGN KEY (`salesman_id`) REFERENCES `Salesmans` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;