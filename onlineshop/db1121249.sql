-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db1121249
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `user_id` mediumint(8) unsigned NOT NULL COMMENT 'ユーザID',
  `item_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `item_num` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '個数',
  PRIMARY KEY (`user_id`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (5,1,1),(5,2,1),(5,3,1),(5,4,1),(5,5,1);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `category_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'カテゴリID',
  `category_name` varchar(100) NOT NULL COMMENT 'カテゴリ名',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'ロールケーキ'),(2,'アイスバー'),(3,'フルーツサンド');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `item_name` varchar(100) NOT NULL COMMENT '商品名',
  `item_exp` text NOT NULL COMMENT '商品説明',
  `item_price` mediumint(8) unsigned DEFAULT NULL COMMENT '商品価格',
  `item_stock` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT '商品在庫',
  `category_id` tinyint(3) unsigned NOT NULL COMMENT '商品カテゴリ',
  `sales_stop_flag` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '販売停止フラグ',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'フルーツロールケーキ','厳選したフルーツを巻き込んだ、代表的なスイーツです。',2300,10,1,0),(2,'ショコラフルーツロールケーキ','ショコラ生地で巻き上げた、カカオ香るロールケーキです。',2400,10,1,0),(3,'バースデーロールケーキ','フルーツロールケーキをもとに、色々なフルーツを可愛くトッピングしました。',3800,10,1,0),(4,'贅沢アイスバー 6本入り','新鮮なフルーツの果肉&果汁をぎっしりアイスバーとして詰め込みました。',3400,10,2,0),(5,'フルーツサンド','厳選された果物をたっぷり詰め込みました。',1100,10,3,0);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '注文ID',
  `user_id` mediumint(8) unsigned NOT NULL COMMENT 'ユーザID',
  `item_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `item_num` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '個数',
  `sales_price` mediumint(8) unsigned NOT NULL COMMENT '販売価格',
  `order_date` datetime NOT NULL COMMENT '注文日',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (20,6,1,2,2300,'2024-01-18 00:00:00'),(21,6,2,1,2400,'2024-01-18 00:00:00'),(22,6,3,3,3800,'2024-01-18 00:00:00'),(23,6,4,2,3400,'2024-01-18 00:00:00'),(24,6,5,1,1100,'2024-01-18 00:00:00'),(25,7,1,1,2300,'2024-01-18 00:00:00');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webapp09`
--

DROP TABLE IF EXISTS `webapp09`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webapp09` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `last_name` varchar(20) NOT NULL COMMENT '姓',
  `first_name` varchar(20) NOT NULL COMMENT '名',
  `postal` varchar(7) NOT NULL COMMENT '郵便番号',
  `address` varchar(255) NOT NULL COMMENT '住所',
  `tel` varchar(12) NOT NULL COMMENT '電話番号',
  `login_id` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `login_pass` varchar(255) NOT NULL COMMENT 'パスワード',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webapp09`
--

LOCK TABLES `webapp09` WRITE;
/*!40000 ALTER TABLE `webapp09` DISABLE KEYS */;
INSERT INTO `webapp09` VALUES (5,'admin','admin','0001111','石川県金沢市1-1-1','11122223333','admin','kanri'),(7,'a','直','0001111','東京','22233334444','a','a');
/*!40000 ALTER TABLE `webapp09` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-18 14:52:17
