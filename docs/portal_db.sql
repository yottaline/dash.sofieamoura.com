CREATE TABLE IF NOT EXISTS
  `users` (
    `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_code` VARCHAR(8) NOT NULL,
    `user_name` VARCHAR(120) NOT NULL,
    `user_email` VARCHAR(120) NOT NULL,
    `user_password` VARCHAR(255) NOT NULL,
    `user_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `user_login` DATETIME DEFAULT NULL,
    `user_modified` DATETIME DEFAULT NULL,
    `user_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE (`user_code`),
    UNIQUE (`user_email`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `locations` (
    `location_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `location_name` VARCHAR(30) NOT NULL,
    `location_iso_2` VARCHAR(2) NOT NULL COMMENT 'ISO code alpha-2',
    `location_iso_3` VARCHAR(3) NOT NULL COMMENT 'ISO code alpha-3',
    `location_code` VARCHAR(6) NOT NULL COMMENT 'phone code',
    `location_visible` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`location_id`),
    UNIQUE (`location_iso_2`),
    UNIQUE (`location_iso_3`),
    UNIQUE (`location_code`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `currencies` (
    `currency_id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `currency_name` VARCHAR(120) DEFAULT NULL,
    `currency_code` VARCHAR(3) NOT NULL COMMENT 'LIKE:USD',
    `currency_symbol` VARCHAR(12) NOT NULL COMMENT 'url encoded',
    `currency_visible` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`currency_id`),
    UNIQUE (`currency_code`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
INSERT INTO
  `currencies`
VALUES
  (1, 'Euro', 'EUR', '&euro;', TRUE),
  (2, 'US Doller', 'USD', '&dollar;', TRUE);

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `retailers` (
    `retailer_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `retailer_code` VARCHAR(8) NOT NULL,
    `retailer_fullname` VARCHAR(255) NOT NULL,
    `retailer_email` VARCHAR(120) NOT NULL,
    `retailer_password` VARCHAR(255) NOT NULL,
    `retailer_phone` VARCHAR(24) NOT NULL,
    `retailer_company` VARCHAR(120) NOT NULL,
    `retailer_logo` VARCHAR(64) DEFAULT NULL,
    `retailer_desc` VARCHAR(1024) DEFAULT NULL,
    `retailer_website` VARCHAR(255) DEFAULT NULL,
    `retailer_country` INT UNSIGNED NOT NULL,
    `retailer_province` VARCHAR(120) NOT NULL,
    `retailer_city` VARCHAR(120) NOT NULL,
    `retailer_address` VARCHAR(255) DEFAULT NULL,
    `retailer_billAdd` INT UNSIGNED DEFAULT NULL COMMENT 'DEFAULT BILLING ADDRESS',
    `retailer_shipAdd` INT UNSIGNED DEFAULT NULL COMMENT 'DEFAULT SHIPPING ADDRESS',
    `retailer_currency` TINYINT UNSIGNED NOT NULL DEFAULT '1',
    `retailer_adv_payment` TINYINT NOT NULL DEFAULT '40',
    `retailer_approved` DATETIME DEFAULT NULL,
    `retailer_approved_by` INT UNSIGNED DEFAULT NULL,
    `retailer_blocked` BOOLEAN NOT NULL DEFAULT FALSE,
    `retailer_modified` DATETIME DEFAULT NULL,
    `retailer_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`retailer_id`),
    UNIQUE (`retailer_code`),
    UNIQUE (`retailer_email`),
    UNIQUE (`retailer_phone`),
    FOREIGN KEY (`retailer_country`) REFERENCES `locations` (`location_id`),
    FOREIGN KEY (`retailer_currency`) REFERENCES `currencies` (`currency_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `retailers_addresses` (
    `address_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `address_retailer` INT UNSIGNED NOT NULL,
    `address_type` TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '0:Both, 1:Billing, 2:Shipping',
    `address_country` INT UNSIGNED NOT NULL,
    `address_province` VARCHAR(120) NOT NULL,
    `address_city` VARCHAR(120) NOT NULL,
    `address_zip` VARCHAR(24) NOT NULL,
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) NOT NULL,
    `address_phone` VARCHAR(24) NOT NULL,
    `address_note` VARCHAR(1024) NOT NULL,
    PRIMARY KEY (`address_id`),
    FOREIGN KEY (`address_retailer`) REFERENCES `retailers` (`retailer_id`),
    FOREIGN KEY (`address_country`) REFERENCES `locations` (`location_id`),
    CHECK (`address_type` IN (0, 1, 2))
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `categories` (
    `category_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(64) NOT NULL,
    `category_type` TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '0:All, 1:Babies, 2:Kids, 3:Teens, 4:Adults',
    `category_gender` TINYINT UNSIGNED NOT NULL DEFAULT '1' COMMENT '0:Both, 1:Girl, 2:Boy',
    `category_visible` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`category_id`),
    CHECK (`category_type` BETWEEN 0 AND 4),
    CHECK (`category_gender` BETWEEN 0 AND 2)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `seasons` (
    `season_id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `season_name` VARCHAR(120) NOT NULL,
    `season_code` VARCHAR(12) NOT NULL,
    `season_current` BOOLEAN NOT NULL DEFAULT FALSE,
    `season_adv_payment` TINYINT UNSIGNED NOT NULL DEFAULT '40',
    `season_adv_context` VARCHAR(512) NOT NULL,
    `season_delivery_1` VARCHAR(512) NOT NULL COMMENT 'FOR IN-STOCK ORDERS',
    `season_delivery_2` VARCHAR(512) NOT NULL COMMENT 'FOR PRE-ORDER ORDERS',
    `season_start` DATE NOT NULL,
    `season_end` DATE NOT NULL COMMENT 'DEFAULT ENDS AFTER 2 MTHS',
    `season_lookbook` VARCHAR(120) NOT NULL,
    `season_visible` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`season_id`),
    UNIQUE (`season_code`),
    CHECK (`season_adv_payment` <= 100),
    CHECK (`season_end` > `season_start`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `sizes` (
    `size_id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `size_name` VARCHAR(24) DEFAULT NULL,
    `size_order` TINYINT UNSIGNED NOT NULL,
    `size_visible` TINYINT UNSIGNED NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`size_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `ws_products` (
    `product_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_ref` VARCHAR(24) NOT NULL,
    `product_code` VARCHAR(24) DEFAULT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_desc` VARCHAR(1024) NOT NULL,
    `product_media` BIGINT UNSIGNED DEFAULT NULL COMMENT 'default media',
    `product_season` TINYINT UNSIGNED NOT NULL,
    `product_type` TINYINT UNSIGNED NOT NULL DEFAULT '2' COMMENT '1:Babies, 2:Kids, 3:Teens, 4:Adults',
    `product_gender` TINYINT UNSIGNED NOT NULL DEFAULT '1' COMMENT '0:Both, 1:Girl, 2:Boy',
    `product_category` INT UNSIGNED NOT NULL,
    `product_ordertype` TINYINT UNSIGNED NOT NULL COMMENT '1:IN-STOCK, 2:PRE-ORDER',
    `product_minqty` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'MIN ORDER QUANTITY',
    `product_maxqty` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'MAX ORDER QUANTITY',
    `product_mincolorqty` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'MIN QTY PER COLOR PER SIZE',
    `product_minorder` DECIMAL(9, 2) NOT NULL DEFAULT '0.00',
    `product_discount` TINYINT UNSIGNED NOT NULL DEFAULT '0',
    `product_freeshipping` BOOLEAN NOT NULL DEFAULT FALSE,
    `product_delivery` VARCHAR(120) NOT NULL,
    `product_views` INT UNSIGNED NOT NULL DEFAULT '0',
    `product_order` INT UNSIGNED NOT NULL DEFAULT '0',
    `product_related` VARCHAR(1024) NOT NULL,
    `product_published` BOOLEAN NOT NULL DEFAULT FALSE,
    `product_modified_by` DATETIME DEFAULT NULL,
    `product_modified` DATETIME DEFAULT NULL,
    `product_created_by` INT UNSIGNED NOT NULL,
    `product_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`product_id`),
    UNIQUE (`product_ref`),
    UNIQUE (`product_code`),
    FOREIGN KEY (`product_season`) REFERENCES `seasons` (`season_id`),
    FOREIGN KEY (`product_category`) REFERENCES `categories` (`category_id`),
    FOREIGN KEY (`product_created_by`) REFERENCES `users` (`user_id`),
    CHECK (`product_discount` <= 100),
    CHECK (`product_type` BETWEEN 0 AND 4),
    CHECK (`product_gender` BETWEEN 0 AND 2),
    CHECK (`product_ordertype` IN (1, 2))
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `ws_products_sizes` (
    `prodsize_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `prodsize_product` INT UNSIGNED NOT NULL,
    `prodsize_size` TINYINT UNSIGNED NOT NULL,
    `prodsize_colorid` VARCHAR(8) NOT NULL,
    `prodsize_color` VARCHAR(45) NOT NULL,
    `prodsize_cost` DECIMAL(9, 2) DEFAULT '0.00',
    `prodsize_wsp` DECIMAL(9, 2) DEFAULT '0.00' COMMENT 'Wholesale Price',
    `prodsize_rrp` DECIMAL(9, 2) DEFAULT '0.00' COMMENT 'Recommanded Retail Price',
    `prodsize_qty` INT UNSIGNED NOT NULL DEFAULT '0',
    `prodsize_stock` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'AVAILABLE QUANTITY',
    `prodsize_visible` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`prodsize_id`),
    FOREIGN KEY (`prodsize_product`) REFERENCES `ws_products` (`product_id`),
    FOREIGN KEY (`prodsize_size`) REFERENCES `sizes` (`size_id`),
    CHECK (`prodsize_qty` >= `prodsize_stock`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `products_media` (
    `media_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `media_product` INT UNSIGNED NOT NULL,
    `media_color` VARCHAR(8) NOT NULL DEFAULT '',
    `media_file` VARCHAR(64) DEFAULT NULL,
    `media_type` TINYINT UNSIGNED NOT NULL DEFAULT '1' COMMENT '1:PHOTO, 2:VIDEO',
    `media_order` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    `media_visible` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`media_id`),
    FOREIGN KEY (`media_product`) REFERENCES `ws_products` (`product_id`),
    CHECK (`media_type` IN (1, 2))
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `ws_orders` (
    `order_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_code` VARCHAR(12) NOT NULL,
    `order_season` TINYINT UNSIGNED NOT NULL,
    `order_retailer` INT UNSIGNED NOT NULL,
    `order_tax` DECIMAL(9, 2) NOT NULL DEFAULT '0.00',
    `order_shipping` DECIMAL(9, 2) NOT NULL DEFAULT '0.00',
    `order_subtotal` DECIMAL(9, 2) NOT NULL,
    `order_discount` TINYINT UNSIGNED NOT NULL DEFAULT '0',
    `order_total` DECIMAL(9, 2) NOT NULL,
    `order_currency` TINYINT UNSIGNED NOT NULL,
    `order_type` TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '1:IN-STOCK, 2:PRE-ORDER',
    `order_status` TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '0:DRAFT, 1:CANCELLED, 2:PLACED, 3:CONFIRMED, 4:ADVANCE PAYMENT IS PENDING, 5:BALANCE PAYMENT IS PENDING, 6:SHIPPED',
    `order_note` VARCHAR(1024) DEFAULT NULL,
    `order_bill_country` INT UNSIGNED NOT NULL,
    `order_bill_province` VARCHAR(120) DEFAULT NULL,
    `order_bill_city` VARCHAR(120) NOT NULL,
    `order_bill_zip` VARCHAR(24) NOT NULL,
    `order_bill_line1` VARCHAR(255) NOT NULL,
    `order_bill_line2` VARCHAR(255) NOT NULL,
    `order_bill_phone` VARCHAR(24) NOT NULL,
    `order_ship_country` INT UNSIGNED NOT NULL,
    `order_ship_province` VARCHAR(120) DEFAULT NULL,
    `order_ship_city` VARCHAR(120) NOT NULL,
    `order_ship_zip` VARCHAR(24) NOT NULL,
    `order_ship_line1` VARCHAR(255) NOT NULL,
    `order_ship_line2` VARCHAR(255) NOT NULL,
    `order_ship_phone` VARCHAR(24) NOT NULL,
    `order_proforma` VARCHAR(42) DEFAULT NULL,
    `order_proformatime` DATETIME DEFAULT NULL,
    `order_invoice` VARCHAR(42) DEFAULT NULL,
    `order_invoicetime` DATETIME DEFAULT NULL,
    `order_skip_adv` BOOLEAN NOT NULL DEFAULT FALSE,
    `order_modified` DATETIME DEFAULT NULL,
    `order_placed` DATETIME DEFAULT NULL,
    `order_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`order_id`),
    UNIQUE (`order_code`),
    FOREIGN KEY (`order_season`) REFERENCES `seasons` (`season_id`),
    FOREIGN KEY (`order_retailer`) REFERENCES `retailers` (`retailer_id`),
    FOREIGN KEY (`order_currency`) REFERENCES `currencies` (`currency_id`),
    FOREIGN KEY (`order_bill_country`) REFERENCES `locations` (`location_id`),
    FOREIGN KEY (`order_ship_country`) REFERENCES `locations` (`location_id`),
    CHECK (`order_type` IN (1, 2)),
    CHECK (`order_status` BETWEEN 0 AND 6)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `ws_orders_products` (
    `ordprod_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ordprod_order` INT UNSIGNED NOT NULL,
    `ordprod_product` INT UNSIGNED NOT NULL,
    `ordprod_size` BIGINT UNSIGNED NOT NULL,
    `ordprod_request_qty` SMALLINT UNSIGNED NOT NULL,
    `ordprod_served_qty` SMALLINT UNSIGNED NOT NULL,
    `ordprod_price` DECIMAL(9, 2) NOT NULL,
    `ordprod_subtotal` DECIMAL(9, 2) NOT NULL,
    `ordprod_discount` TINYINT UNSIGNED NOT NULL DEFAULT '0',
    `ordprod_total` DECIMAL(9, 2) NOT NULL,
    PRIMARY KEY (`ordprod_id`),
    FOREIGN KEY (`ordprod_order`) REFERENCES `ws_orders` (`order_id`),
    FOREIGN KEY (`ordprod_product`) REFERENCES `ws_products` (`product_id`),
    FOREIGN KEY (`ordprod_size`) REFERENCES `ws_products_sizes` (`prodsize_id`),
    CHECK (`ordprod_discount` <= 100)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `retailer_wishlist` (
    `wishlist_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `wishlist_retailer` INT UNSIGNED NOT NULL,
    `wishlist_product` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`wishlist_id`),
    FOREIGN KEY (`wishlist_retailer`) REFERENCES `retailers` (`retailer_id`),
    FOREIGN KEY (`wishlist_product`) REFERENCES `ws_products` (`product_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
