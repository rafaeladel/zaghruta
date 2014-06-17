-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- Dumping data for table zaghruta_db.categories: ~14 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `css_class`, `created_at`, `updated_at`, `name_slug`) VALUES
	('1', 'Hotels & Venues', 'iconHotels', '0000-00-00 00:00:00', NULL, 'hotels-venues'),
	('10', 'Furniture', 'iconFurniture', '2014-06-16 11:11:49', '2014-06-16 11:11:50', 'furniture'),
	('11', 'Beauty & Health', 'iconHealth', '2014-06-16 11:12:20', '2014-06-16 11:12:20', 'beauty-health'),
	('12', 'Limousines', 'iconLimousines', '2014-06-16 11:12:43', '2014-06-16 11:12:43', 'limousines'),
	('13', 'Home Appliances', 'iconHAppliances', '2014-06-16 11:13:07', '2014-06-16 11:13:07', 'home-appliances'),
	('14', 'Entertainment', 'iconEntertainment', '2014-06-16 11:13:33', '2014-06-16 11:13:33', 'entertainment'),
	('2', 'Cake & catering', 'iconCatering', '0000-00-00 00:00:00', NULL, 'cake-catering'),
	('3', 'Wedding Planners', 'iconWedding', '0000-00-00 00:00:00', NULL, 'wedding-planners'),
	('4', 'Photography', 'iconPhotography', '2014-06-16 11:08:50', '2014-06-16 11:08:51', 'photography'),
	('5', 'Jewlery', 'iconJewelry', '2014-06-16 11:09:43', '2014-06-16 11:09:43', 'jewlery'),
	('6', 'Honeymoons', 'iconHonymoons', '2014-06-16 11:10:16', '2014-06-16 11:10:16', 'honeymoons'),
	('7', 'Fashion', 'iconFashion', '2014-06-16 11:10:43', '2014-06-16 11:10:43', 'fashion'),
	('8', 'Real Estate', 'iconRealEstate', '2014-06-16 11:11:00', '2014-06-16 11:11:01', 'real-estate'),
	('9', 'Florists', 'iconFlorists', '2014-06-16 11:11:33', '2014-06-16 11:11:33', 'florists');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
