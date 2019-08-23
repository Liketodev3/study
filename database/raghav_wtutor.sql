-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2019 at 09:32 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raghav_wtutor`
--
CREATE DATABASE IF NOT EXISTS `raghav_wtutor` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `raghav_wtutor`;

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `GETBLOGCATCODE`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GETBLOGCATCODE` (`id` INT) RETURNS VARCHAR(255) CHARSET utf8 BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);

				SET catid = id;
				SET code = '';
				WHILE catid > 0  AND LENGTH(code) < 240 DO
					SET code = CONCAT(RIGHT(CONCAT('000000', catid), 6), '_', code);
					SELECT bpcategory_parent INTO catid FROM tbl_blog_post_categories WHERE bpcategory_id = catid;
				END WHILE;
				RETURN code;
			END$$

DROP FUNCTION IF EXISTS `GETBLOGCATORDERCODE`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GETBLOGCATORDERCODE` (`id` INT) RETURNS VARCHAR(500) CHARSET utf8 BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);
				DECLARE myorder INT(11);
				SET catid = id;
				SET code = '';
				set myorder = 0;
				WHILE catid > 0   AND LENGTH(code) < 240 DO
					SELECT bpcategory_parent, bpcategory_display_order  INTO catid, myorder FROM tbl_blog_post_categories WHERE bpcategory_id = catid;
					SET code = CONCAT(RIGHT(CONCAT('000000', myorder), 6), code);
				END WHILE;
				RETURN code;
			END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_abusive_words`
--

DROP TABLE IF EXISTS `tbl_abusive_words`;
CREATE TABLE `tbl_abusive_words` (
  `abusive_id` int(11) NOT NULL,
  `abusive_keyword` varchar(100) NOT NULL,
  `abusive_lang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

DROP TABLE IF EXISTS `tbl_admin`;
CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `admin_password` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `admin_email` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `admin_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `admin_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_username`, `admin_password`, `admin_email`, `admin_name`, `admin_active`) VALUES
(1, 'welcome', '7fb109e60e6295a6787d4ef283d5ccd1', 'pawan.kumar@ablysoft.com', 'We Yak Yak', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_auth_token`
--

DROP TABLE IF EXISTS `tbl_admin_auth_token`;
CREATE TABLE `tbl_admin_auth_token` (
  `admauth_admin_id` int(11) NOT NULL,
  `admauth_token` varchar(32) CHARACTER SET utf8mb4 NOT NULL,
  `admauth_expiry` datetime NOT NULL,
  `admauth_browser` text CHARACTER SET utf8mb4 NOT NULL,
  `admauth_last_access` datetime NOT NULL,
  `admauth_last_ip` varchar(16) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store admin cookies information, Remember Me functionalit';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_password_reset_requests`
--

DROP TABLE IF EXISTS `tbl_admin_password_reset_requests`;
CREATE TABLE `tbl_admin_password_reset_requests` (
  `aprr_admin_id` int(10) NOT NULL,
  `aprr_token` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `aprr_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_permissions`
--

DROP TABLE IF EXISTS `tbl_admin_permissions`;
CREATE TABLE `tbl_admin_permissions` (
  `admperm_admin_id` int(11) NOT NULL,
  `admperm_section_id` int(11) NOT NULL,
  `admperm_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attached_files`
--

DROP TABLE IF EXISTS `tbl_attached_files`;
CREATE TABLE `tbl_attached_files` (
  `afile_id` int(11) NOT NULL,
  `afile_type` int(11) NOT NULL,
  `afile_record_id` int(11) NOT NULL,
  `afile_record_subid` int(11) NOT NULL,
  `afile_lang_id` int(11) NOT NULL,
  `afile_screen` int(11) NOT NULL COMMENT '1=>Desktop,2=>Ipad/Tablet,3=>Mobile',
  `afile_physical_path` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `afile_name` varchar(200) CHARACTER SET utf8mb4 NOT NULL COMMENT 'For display Only',
  `afile_display_order` int(11) NOT NULL,
  `afile_downloaded_times` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_attached_files`
--

INSERT INTO `tbl_attached_files` (`afile_id`, `afile_type`, `afile_record_id`, `afile_record_subid`, `afile_lang_id`, `afile_screen`, `afile_physical_path`, `afile_name`, `afile_display_order`, `afile_downloaded_times`) VALUES
(9, 27, 1, 0, 2, 0, '2018/10/1538641486-1png', '1.png', 1, 0),
(65, 9, 7, 0, 1, 1, '2018/10/1539930692-231png', '23 (1).png', 1, 0),
(69, 9, 3, 0, 1, 2, '2018/10/1539930838-bag3jpeg', 'bag3.jpeg', 1, 0),
(72, 9, 3, 0, 2, 3, '2018/10/1539930881-bag5jpg', 'bag5.jpg', 3, 0),
(75, 9, 8, 0, 0, 1, '2018/10/1539930923-downloadjpg', 'download.jpg', 2, 0),
(78, 9, 5, 0, 2, 3, '2018/10/1539931177-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 1, 0),
(81, 9, 9, 0, 1, 2, '2018/10/1539931429-queryjpg', 'query.jpg', 1, 0),
(82, 9, 10, 0, 1, 3, '2018/10/1539931478-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 1, 0),
(83, 9, 11, 0, 1, 2, '2018/10/1539931532-23png', '23.png', 1, 0),
(84, 26, 2, 0, 0, 0, '2018/10/1539931732-1png', '1.png', 1, 0),
(85, 26, 3, 0, 0, 0, '2018/10/1539931931-231png', '23 (1).png', 1, 0),
(87, 9, 8, 0, 1, 2, '2018/10/1539935107-bag5jpg', 'bag5.jpg', 1, 0),
(88, 9, 8, 0, 2, 3, '2018/10/1539935123-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 3, 0),
(89, 9, 8, 0, 1, 3, '2018/10/1539935139-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 2, 0),
(90, 9, 8, 0, 0, 3, '2018/10/1539935166-23png', '23.png', 3, 0),
(94, 9, 12, 0, 1, 2, '2018/10/1539935244-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 3, 0),
(95, 9, 12, 0, 1, 3, '2018/10/1539935261-1png', '1.png', 4, 0),
(96, 9, 12, 0, 0, 1, '2018/10/1539935378-1png', '1.png', 2, 0),
(97, 9, 13, 0, 1, 2, '2018/10/1539935451-231png', '23 (1).png', 1, 0),
(99, 31, 18, 0, 0, 0, '2018/10/1540205603-Koalajpg', 'Koala.jpg', 1, 0),
(204, 31, 1, 0, 0, 0, '2019/04/1554111834-download1png', 'download (1).png', 1, 0),
(206, 31, 5, 0, 0, 0, '2019/04/1554112060-download2png', 'download (2).png', 1, 0),
(228, 35, 9, 0, 0, 0, '2019/04/1554293177-300pxNorincotype56jpg', '300px-Norinco_type_56.jpg', 0, 0),
(254, 12, 0, 0, 1, 0, '2019/04/1554364227-1png', '1.png', 1, 0),
(255, 6, 0, 0, 1, 0, '2019/04/1554364244-1png', '1.png', 1, 0),
(267, 26, 4, 0, 0, 0, '2019/04/1554545822-Screenshot2favouritehyperlinkissuepng', 'Screenshot_2 favourite hyperlink issue.png', 1, 0),
(270, 7, 4, 0, 0, 1, '2019/04/1554784802-2000x9001jpg', '2000x900_1.jpg', 1, 0),
(271, 7, 3, 0, 0, 1, '2019/04/1554784885-2000x9002jpg', '2000x900_2.jpg', 1, 0),
(290, 37, 0, 0, 1, 0, '2019/04/1554880668-logowhitesvg', 'logo-white.svg', 1, 0),
(292, 23, 2, 0, 0, 0, '2019/04/1554976747-HorrorDevilPicturesWallpaperjpg', 'Horror-Devil-Pictures-Wallpaper.jpg', 1, 0),
(293, 23, 3, 0, 0, 0, '2019/04/1554976789-f222jpgpcadaptivefullmediumjpeg', 'f-22_2.jpg.pc-adaptive.full.medium.jpeg', 1, 0),
(294, 36, 3, 0, 0, 0, '2019/04/1554979125-EnglishPNG', 'English.PNG', 1, 0),
(295, 36, 4, 0, 0, 0, '2019/04/1554980667-dummycredentialtxt', 'dummy credential.txt', 1, 0),
(296, 36, 5, 0, 0, 0, '2019/04/1555048048-dummycredentialtxt', 'dummy credential.txt', 1, 0),
(297, 36, 6, 0, 0, 0, '2019/04/1555048164-bfd96c261df8706d878951c0bd39ca8fjpg', 'bfd96c261df8706d878951c0bd39ca8f.jpg', 1, 0),
(301, 36, 7, 0, 0, 0, '2019/04/1555409401-Screenshot13Theendlessonbuttonshouldbevisiblesousercaneasilyseethebuttonpng', 'Screenshot_13 The end lesson button should be visible so user can easily see the button.png', 1, 0),
(303, 27, 1, 0, 1, 0, '2019/04/1555500067-aboutimagejpg', 'aboutimage.jpg', 1, 0),
(304, 36, 8, 0, 0, 0, '2019/04/1555502653-dummycredentialtxt', 'dummy credential.txt', 1, 0),
(305, 36, 9, 0, 0, 0, '2019/04/1555502786-hatxt', 'ha.txt', 1, 0),
(306, 36, 10, 0, 0, 0, '2019/04/1555503161-24kestercyclojet3starcas24es3j8f02splitcarrieroriginalimaf2utjyhvavtzxjpg', '24k-ester-cyclojet-3-star-cas24es3j8f0-2-split-carrier-original-imaf2utjyhvavtzx.jpg', 1, 0),
(307, 36, 11, 0, 0, 0, '2019/04/1555503201-24kestercyclojet3starcas24es3j8f02splitcarrieroriginalimaf2utjyhvavtzxjpgpng', '24k-ester-cyclojet-3-star-cas24es3j8f0-2-split-carrier-original-imaf2utjyhvavtzx.jpg.png', 1, 0),
(311, 35, 12, 0, 0, 0, '2019/04/1555504660-24kestercyclojet3starcas24es3j8f02splitcarrieroriginalimaf2utjyhvavtzxjpgpng', '24k-ester-cyclojet-3-star-cas24es3j8f0-2-split-carrier-original-imaf2utjyhvavtzx.jpg.png', 0, 0),
(312, 36, 12, 0, 0, 0, '2019/04/1555566654-Screenshot13Theendlessonbuttonshouldbevisiblesousercaneasilyseethebuttonpng', 'Screenshot_13 The end lesson button should be visible so user can easily see the button.png', 1, 0),
(316, 11, 0, 0, 1, 0, '2019/04/1556024702-logopng', 'logo.png', 1, 0),
(321, 27, 6, 0, 1, 0, '2019/05/1557982502-helpjpg', 'help.jpg', 1, 0),
(324, 15, 2, 0, 0, 0, '2019/05/1557993271-helpjpg', 'help.jpg', 1, 0),
(325, 16, 2, 0, 0, 0, '2019/05/1557993276-helpjpg', 'help.jpg', 1, 0),
(347, 24, 0, 0, 1, 0, '2019/05/1558030866-ScreenShot20190516at105728AMpng', 'Screen Shot 2019-05-16 at 10.57.28 AM.png', 1, 0),
(367, 10, 0, 0, 1, 0, '2019/05/1558067667-1915225originaljpg', '1915225_original.jpg', 1, 0),
(370, 23, 4, 0, 0, 0, '2019/05/1558119654-ScreenShot20190516at84350PMpng', 'Screen Shot 2019-05-16 at 8.43.50 PM.png', 1, 0),
(382, 15, 1, 0, 0, 0, '2019/05/1558332628-1915225originaljpg', '1915225_original.jpg', 3, 0),
(383, 16, 1, 0, 0, 0, '2019/05/1558332631-1915225originaljpg', '1915225_original.jpg', 1, 0),
(385, 8, 1, 0, 0, 0, '2019/05/1558332920-imageedit202716316726facebookiconpng', 'imageedit_20_2716316726-facebook-icon.png', 1, 0),
(386, 8, 2, 0, 0, 0, '2019/05/1558332957-download4png', 'download (4).png', 1, 0),
(387, 8, 3, 0, 0, 0, '2019/05/1558332970-4145a85c594cc78c40b7270f1ccb7651png', '4145a85c594cc78c40b7270f1ccb7651.png', 1, 0),
(388, 8, 5, 0, 0, 0, '2019/05/1558332986-images1png', 'images (1).png', 1, 0),
(389, 8, 4, 0, 0, 0, '2019/05/1558333001-download1jpg', 'download (1).jpg', 1, 0),
(390, 31, 2, 0, 0, 0, '2019/05/1558335813-images2png', 'images (2).png', 1, 0),
(391, 31, 6, 0, 0, 0, '2019/05/1558335946-download5png', 'download (5).png', 1, 0),
(401, 27, 7, 0, 1, 0, '2019/05/1558350105-2000x9004jpg', '2000x900_4.jpg', 1, 0),
(415, 27, 4, 0, 1, 0, '2019/05/1558610887-bn5seriesbannerjpg', 'bn_5seriesbanner.jpg', 1, 0),
(428, 9, 3, 0, 0, 0, '2019/05/1558617300-800x5002jpg', '800x500_2.jpg', 3, 0),
(429, 9, 7, 0, 0, 0, '2019/05/1558617328-800x5002jpg', '800x500_2.jpg', 1, 0),
(436, 26, 5, 0, 0, 0, '2019/05/1558932933-800x5003jpg', '800x500_3.jpg', 1, 0),
(437, 26, 1, 0, 0, 0, '2019/05/1558932952-800x5001jpg', '800x500_1.jpg', 1, 0),
(438, 26, 6, 0, 0, 0, '2019/05/1558932988-800x5002jpg', '800x500_2.jpg', 1, 0),
(439, 9, 4, 0, 0, 0, '2019/05/1558934990-800x50001png', '800x500_01.png', 2, 0),
(441, 9, 5, 0, 0, 0, '2019/05/1558935025-800x50002png', '800x500_02.png', 3, 0),
(443, 9, 6, 0, 0, 0, '2019/05/1558935053-800x50003png', '800x500_03.png', 3, 0),
(447, 9, 2, 0, 0, 0, '2019/05/1558962185-500x3901jpg', '500x390_1.jpg', 1, 0),
(449, 9, 1, 0, 0, 0, '2019/05/1559022216-500x3904jpg', '500x390_4.jpg', 1, 0),
(450, 38, 1, 0, 0, 0, '2019/05/1559022226-500x3902jpg', '500x390_2.jpg', 1, 0),
(451, 38, 2, 0, 0, 0, '2019/05/1559022243-1jpg', '1.jpg', 1, 0),
(463, 1, 1, 0, 0, 0, '2019/06/1559376961-500x3903jpg', '500x390_3.jpg', 1, 0),
(464, 2, 1, 0, 0, 0, '2019/06/1559376961-dbupdatesRGVtxt', 'dbupdates-RGV.txt', 1, 0),
(465, 4, 1, 0, 0, 0, '2019/06/1559376961-500x3903jpg', '500x390_3.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banners`
--

DROP TABLE IF EXISTS `tbl_banners`;
CREATE TABLE `tbl_banners` (
  `banner_id` int(11) NOT NULL,
  `banner_blocation_id` int(11) NOT NULL,
  `banner_url` varchar(255) NOT NULL,
  `banner_target` varchar(100) NOT NULL,
  `banner_added_on` datetime NOT NULL,
  `banner_active` tinyint(1) NOT NULL,
  `banner_deleted` tinyint(1) NOT NULL,
  `banner_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_banners`
--

INSERT INTO `tbl_banners` (`banner_id`, `banner_blocation_id`, `banner_url`, `banner_target`, `banner_added_on`, `banner_active`, `banner_deleted`, `banner_display_order`) VALUES
(1, 1, 'https://www.google.co.in', '_self', '0000-00-00 00:00:00', 1, 0, 1234567891),
(2, 1, 'https://www.google.co.in', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(4, 3, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(5, 3, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(6, 3, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(7, 2, 'https://www.google.com', '_self', '0000-00-00 00:00:00', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banners_lang`
--

DROP TABLE IF EXISTS `tbl_banners_lang`;
CREATE TABLE `tbl_banners_lang` (
  `bannerlang_banner_id` int(11) NOT NULL,
  `bannerlang_lang_id` int(11) NOT NULL,
  `banner_title` varchar(255) NOT NULL,
  `banner_description` text NOT NULL,
  `banner_btn_caption` varchar(255) NOT NULL,
  `banner_btn_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_banners_lang`
--

INSERT INTO `tbl_banners_lang` (`bannerlang_banner_id`, `bannerlang_lang_id`, `banner_title`, `banner_description`, `banner_btn_caption`, `banner_btn_url`) VALUES
(1, 1, 'A conversational  curriculum.', 'Conversations are the heart and soul of the WeYakYak experience. We believe the most effective way to learn a language is to speak it with teachers. It’s our mission to connect you with teachers who are a perfect fit for your learning style.', '', ''),
(1, 2, 'Shop Now', '', '', ''),
(2, 1, 'Teachers are better  than software.', 'All WeYakYak language teachers are native speakers who have been selected to join our roster of instructors. You can browse hundreds of WeYakYak teacher profiles and schedule unlimited trial lessons to find the right one for you.', '', ''),
(2, 2, 'Banner', '', '', ''),
(4, 1, 'Browse', 'Browse thousands of teacher', '', ''),
(4, 2, 'Browse', 'Browse thousands of teacher', '', ''),
(5, 1, 'Book', 'Book lessons with the best teachers for your', '', ''),
(5, 2, 'Book', 'Book lessons with the best teachers for your', '', ''),
(6, 1, 'Start', 'Log in to Weyakyak Video and start talking.', '', ''),
(6, 2, 'Start', 'Log in to Weyakyak Video and start talking.', '', ''),
(7, 1, 'Learn your own way.', 'WeYakYak is designed for you to learn whenever and however you want to. Our built-in WeYakYak Video connects you directly to your teacher without needing external apps or tools. We\'ve built a toolbox that will help you get the most out of your lessons.', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner_locations`
--

DROP TABLE IF EXISTS `tbl_banner_locations`;
CREATE TABLE `tbl_banner_locations` (
  `blocation_id` int(11) NOT NULL,
  `blocation_key` varchar(150) NOT NULL,
  `blocation_identifier` varchar(255) NOT NULL,
  `blocation_banner_width` decimal(13,2) NOT NULL,
  `blocation_banner_height` decimal(13,2) NOT NULL,
  `blocation_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_banner_locations`
--

INSERT INTO `tbl_banner_locations` (`blocation_id`, `blocation_key`, `blocation_identifier`, `blocation_banner_width`, `blocation_banner_height`, `blocation_active`) VALUES
(1, 'BLOCK_FIRST_AFTER_HOMESLIDER', 'Block First after Hompage Slider', '470.00', '367.00', 1),
(2, 'BLOCK_SECOND_AFTER_HOMESLIDER', 'Block Second after Hompage Slider', '800.00', '500.00', 1),
(3, 'BLOCK_HOW_IT_WORKS', 'How It Works', '800.00', '500.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner_locations_lang`
--

DROP TABLE IF EXISTS `tbl_banner_locations_lang`;
CREATE TABLE `tbl_banner_locations_lang` (
  `blocationlang_blocation_id` int(11) NOT NULL,
  `blocationlang_lang_id` int(11) NOT NULL,
  `blocation_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_banner_locations_lang`
--

INSERT INTO `tbl_banner_locations_lang` (`blocationlang_blocation_id`, `blocationlang_lang_id`, `blocation_name`) VALUES
(1, 1, 'Block First after Hompage Slider'),
(1, 2, 'Block First after Hompage Slider'),
(2, 1, 'Block Second after Hompage Slider'),
(2, 2, 'Block Second after Hompage Slider'),
(3, 1, 'How It Works'),
(3, 2, 'How It Works');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bible_content`
--

DROP TABLE IF EXISTS `tbl_bible_content`;
CREATE TABLE `tbl_bible_content` (
  `biblecontent_id` int(11) NOT NULL,
  `biblecontent_title` varchar(250) NOT NULL,
  `biblecontent_type` int(11) NOT NULL,
  `biblecontent_url` varchar(250) NOT NULL,
  `biblecontent_display_order` int(11) NOT NULL,
  `biblecontent_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bible_content`
--

INSERT INTO `tbl_bible_content` (`biblecontent_id`, `biblecontent_title`, `biblecontent_type`, `biblecontent_url`, `biblecontent_display_order`, `biblecontent_active`) VALUES
(1, 'Mashoor Gulati', 2, 'https://www.youtube.com/watch?v=1AOlnSeW_wE', 8, 1),
(2, 'youtube', 0, 'Https://www.youtube.com', 3, 1),
(3, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 4, 1),
(4, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 5, 1),
(5, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 6, 1),
(6, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 7, 1),
(7, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 9, 1),
(8, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 10, 1),
(9, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 9, 1),
(10, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 10, 1),
(11, 'Https://www.youtube.com', 0, 'Https://www.youtube.com', 2, 1),
(12, 'gulati', 0, 'https://www.discovery.com', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bible_content_lang`
--

DROP TABLE IF EXISTS `tbl_bible_content_lang`;
CREATE TABLE `tbl_bible_content_lang` (
  `biblecontentlang_biblecontent_id` int(11) NOT NULL,
  `biblecontentlang_lang_id` int(11) NOT NULL,
  `biblecontentlang_biblecontent_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_bible_content_lang`
--

INSERT INTO `tbl_bible_content_lang` (`biblecontentlang_biblecontent_id`, `biblecontentlang_lang_id`, `biblecontentlang_biblecontent_title`) VALUES
(1, 1, 'Mashoor Gulati Englishh'),
(1, 2, 'Mashoor Gulati Arabic'),
(2, 1, 'ENGLISH'),
(2, 2, 'ARABIC');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_contributions`
--

DROP TABLE IF EXISTS `tbl_blog_contributions`;
CREATE TABLE `tbl_blog_contributions` (
  `bcontributions_id` int(11) NOT NULL,
  `bcontributions_author_first_name` varchar(150) NOT NULL,
  `bcontributions_author_last_name` varchar(150) NOT NULL,
  `bcontributions_author_email` varchar(255) NOT NULL,
  `bcontributions_author_phone` varchar(25) NOT NULL,
  `bcontributions_status` tinyint(1) NOT NULL,
  `bcontributions_added_on` datetime NOT NULL,
  `bcontributions_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post`
--

DROP TABLE IF EXISTS `tbl_blog_post`;
CREATE TABLE `tbl_blog_post` (
  `post_id` int(11) NOT NULL,
  `post_identifier` varchar(255) NOT NULL,
  `post_published` tinyint(1) NOT NULL,
  `post_comment_opened` tinyint(1) NOT NULL,
  `post_added_on` datetime NOT NULL,
  `post_published_on` datetime NOT NULL,
  `post_updated_on` datetime NOT NULL,
  `post_view_count` bigint(20) NOT NULL,
  `post_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_blog_post`
--

INSERT INTO `tbl_blog_post` (`post_id`, `post_identifier`, `post_published`, `post_comment_opened`, `post_added_on`, `post_published_on`, `post_updated_on`, `post_view_count`, `post_deleted`) VALUES
(1, 'eCommerce â€“ Past, Present And The Future', 1, 1, '2017-07-19 17:27:25', '2019-04-24 10:12:04', '2019-04-24 10:12:04', 0, 1),
(2, '5 Crucial Steps Entrepreneurs Follow To Start A New Ecommerce Business', 1, 1, '2017-07-19 17:40:29', '2017-07-19 17:40:29', '2017-07-19 17:40:29', 0, 1),
(3, '5 Features That Make YoKart a Seller-Friendly Ecommerce Platform', 1, 1, '2017-07-19 17:56:14', '2019-04-24 10:12:10', '2019-04-24 10:12:10', 0, 1),
(4, 'Blog', 1, 1, '2019-05-17 11:55:27', '2019-05-17 12:04:51', '2019-05-17 12:04:51', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_categories`
--

DROP TABLE IF EXISTS `tbl_blog_post_categories`;
CREATE TABLE `tbl_blog_post_categories` (
  `bpcategory_id` int(11) NOT NULL,
  `bpcategory_identifier` varchar(200) NOT NULL,
  `bpcategory_parent` int(11) NOT NULL,
  `bpcategory_display_order` int(11) NOT NULL,
  `bpcategory_featured` tinyint(1) NOT NULL,
  `bpcategory_active` tinyint(1) NOT NULL,
  `bpcategory_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_blog_post_categories`
--

INSERT INTO `tbl_blog_post_categories` (`bpcategory_id`, `bpcategory_identifier`, `bpcategory_parent`, `bpcategory_display_order`, `bpcategory_featured`, `bpcategory_active`, `bpcategory_deleted`) VALUES
(1, 'YoKart', 0, 2, 1, 1, 1),
(2, 'demo', 0, 1, 1, 1, 1),
(3, 'demo', 0, 3, 1, 1, 1),
(4, 'demo', 0, 4, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_categories_lang`
--

DROP TABLE IF EXISTS `tbl_blog_post_categories_lang`;
CREATE TABLE `tbl_blog_post_categories_lang` (
  `bpcategorylang_bpcategory_id` int(11) NOT NULL,
  `bpcategorylang_lang_id` int(11) NOT NULL,
  `bpcategory_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_blog_post_categories_lang`
--

INSERT INTO `tbl_blog_post_categories_lang` (`bpcategorylang_bpcategory_id`, `bpcategorylang_lang_id`, `bpcategory_name`) VALUES
(1, 1, 'YoKart'),
(1, 2, 'YoKart'),
(2, 1, 'Demo Category'),
(3, 1, 'Demo Category'),
(4, 1, 'Demo Category'),
(4, 2, 'Demo Category');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_comments`
--

DROP TABLE IF EXISTS `tbl_blog_post_comments`;
CREATE TABLE `tbl_blog_post_comments` (
  `bpcomment_id` int(11) NOT NULL,
  `bpcomment_post_id` int(11) NOT NULL,
  `bpcomment_user_id` int(11) NOT NULL,
  `bpcomment_author_name` varchar(150) NOT NULL,
  `bpcomment_author_email` varchar(255) NOT NULL,
  `bpcomment_content` text NOT NULL,
  `bpcomment_approved` tinyint(1) NOT NULL,
  `bpcomment_deleted` tinyint(1) NOT NULL,
  `bpcomment_added_on` datetime NOT NULL,
  `bpcomment_user_ip` varchar(20) NOT NULL,
  `bpcomment_user_agent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_blog_post_comments`
--

INSERT INTO `tbl_blog_post_comments` (`bpcomment_id`, `bpcomment_post_id`, `bpcomment_user_id`, `bpcomment_author_name`, `bpcomment_author_email`, `bpcomment_content`, `bpcomment_approved`, `bpcomment_deleted`, `bpcomment_added_on`, `bpcomment_user_ip`, `bpcomment_user_agent`) VALUES
(1, 1, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'rtytry', 1, 0, '2018-12-28 15:56:37', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(2, 1, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'dgdfg', 1, 0, '2018-12-28 15:58:32', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(3, 1, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'sfsdf', 1, 0, '2018-12-28 16:13:21', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(4, 1, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'fghfgh', 1, 0, '2018-12-28 17:54:06', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(5, 1, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'fghgfh', 1, 0, '2018-12-28 17:54:12', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(6, 1, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'fdfgdfgdfgdgfd', 0, 0, '2018-12-28 17:58:19', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(7, 2, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'fhffghfg', 0, 0, '2018-12-28 18:37:46', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(8, 2, 7, 'gaurav4ssss', 'gaurav4@dummyid.com', 'dfgdfgg dfgfd gfdg dfg dfg dfg df gdf gdf gdf gdf gfdg', 1, 0, '2018-12-28 19:20:12', '192.168.5.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'),
(9, 3, 59, 'devil kumar', 'devil@dummyid.com', '15615645654456', 1, 0, '2019-04-11 15:44:31', '112.196.26.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36'),
(10, 3, 59, 'devil kumar', 'devil@dummyid.com', 'hh', 1, 0, '2019-04-24 10:12:30', '112.196.26.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_lang`
--

DROP TABLE IF EXISTS `tbl_blog_post_lang`;
CREATE TABLE `tbl_blog_post_lang` (
  `postlang_post_id` int(11) NOT NULL,
  `postlang_lang_id` int(11) NOT NULL,
  `post_author_name` varchar(100) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_short_description` text NOT NULL,
  `post_description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_blog_post_lang`
--

INSERT INTO `tbl_blog_post_lang` (`postlang_post_id`, `postlang_lang_id`, `post_author_name`, `post_title`, `post_short_description`, `post_description`) VALUES
(1, 1, 'YoKart Chef', 'eCommerce â€“ Past, Present And The Future', 'Most of the consumers were vary of ecommerce a decade ago, but within a span of few years, it has become a core part of our daily lives. Nowadays most of the people prefer to buy online rather than going out if given a choice. There has been a lot happening in this area. Read this post to find out more about the past, present and what holds in the future of ecommerce.', '<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Let us go back a few years and think what were the top five things we did on the Internet. I guess online shopping will not be on that list. However, nowadays, I do not think there would be many people who would have online shopping missing from their list of activities. It has tremendous appeal, to various ages and classes â€” you can shop at your leisure, anytime, and in your pajamas.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Nowadays the thought of living without \r\n	ecommerce seems unreal, complicated and an inconvenience to many. It wasnâ€™t until only a few decades ago that the idea of \r\n	\r\n		ecommerce &nbsp;had even appeared.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">When It All Started</strong></h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Ecommerce became possible in 1991 when the Internet was opened to commercial use. The \r\n	mid 90s to 2000s saw major advancements in the commercial use of the internet. The largest online retailer in the world Amazon launched in 1995 as an online bookstore. Two decades later Amazon accounted for nearly half of all \r\n	ecommerce growth, highlighting that multivendor stores are in huge demand.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">How Is It Panning Out</strong></h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Ecommerce is touching new heights year after year. If you thought that it has reached its saturation point, few years down the line, you will come across new&nbsp;<a href=\"http://www.yo-kart.com/blog/5-niche-ecommerce-businesses-you-can-start-with-yokart/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">possibilities of \r\n			ecommerce business</strong></a>. If we ask ourselves what was our first encounter with \r\n	ecommerce, hardly anyone would remember. This is because our lives have become so intertwined with it, that we have started to take it for granted.</p>\r\n<h3 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Marketplaces</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">If the past belonged to single store places, the present is all about multi-vendor stores. You name any type of big \r\n	ecommerce store and it will be a marketplace. Consumers prefer to buy from marketplaces rather than single vendor stores, as the former offers better options. This is the reason why most of the entrepreneurs are gunning for launching a multi-vendor store.</p>\r\n<h3 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Mobile</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Back in 2003, when I had my first mobile phone (Nokia 3310), I could just send SMS, and make calls and play snake on it. For me, ecommerce has been one of the biggest things that can happen to mobile devices. Nowadays, you can easily buy things while on the move, without any hassle.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">\"I wonder I could meet David again, who said he would never put his credit card online and certainly never buy anything online. I wonder if he still feels the same way. I doubt thatâ€</em></p>\r\n<h3 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Social Shopping</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">When we talk about social network, all we can think of is communicating with friends and family, sharing photos and videos or looking at funny cat videos. But social networking has grown way bigger than this.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Social shopping has become a norm these days where online stores are using social media as a tool to engage with the audience. In addition to it, these platforms have themselves added ecommerce functionality, where sellers can easily link their shop with the social network. Pinterest, Instagram, Twitter, and Facebook have already started to test buy button on their platform.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">So Whatâ€™s Next?</strong></h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">As already discussed many are of the view that ecommerce has reached its saturation point, but there is a lot of potential that is yet to come to the forefront. Already we are seeing a systematic shift towards new technologies which would drive the future growth story of ecommerce.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Must Read:&nbsp;</em><a href=\"http://www.yo-kart.com/blog/a-progressive-guide-on-how-to-scale-your-ecommerce-startup/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">How to scale your ecommerce startup</em></a></p>\r\n<h3 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Augmented Reality</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Augmented and virtual reality is the place where everyone should be looking at. While in the past ecommerce brought shopping stores onto your desktops and mobiles, VR will bring the whole virtual experience of shopping in a mall on to the table.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Till date, the whole user experience of online shopping is limited to 2-dimensions, but VR aims to add a third dimension to it. Facebook, Amazon, and Google have been actively working on such technologies to bring them to the forefront.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">You could just put on a VR device, sitting on the sofa in your living room and step into virtual reality world and feel like you are moving around a shopping mall.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Another scenario is when you want to buy a table for your house. You would browse through several pictures and choose the one which would not fit in the corner. With the help of AR, you could easily place the table in the living room using the device and see how it looks and fits beforehand.</p>\r\n<h3 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Artificial Intelligence</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">A lot has been talked about AI in the recent past, but still there is huge ambiguity about its role in ecommerce. With growing popularity and&nbsp;<a href=\"http://www.yo-kart.com/blog/grow-your-ecommerce-business-with-the-power-of-big-data-uses-tools-2/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">use of BIG data in eCommerce</a>, AI will in fact change the whole notion of product discovery. Rather than user searching among millions of products, the stores will now automatically deduce the preferences of users and showcase the items more likeable to them. This will lead to a rise in predictive models and curated shopping.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Whatever happens in the next 5-10 years, one thing is for sure that the ecommerce party has not yet ended. In fact the best part is just about to come. But one thing is certain, Ecommerce till date has been driven by convenience. It is all about anywhere, anytime and however the consumer wants it. The key phrase is&nbsp;<strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Instant Gratification</strong>.</p>'),
(1, 2, 'YoKart Chef', 'eCommerce â€“ Past, Present And The Future', 'Most of the consumers were vary of ecommerce a decade ago, but within a span of few years, it has become a core part of our daily lives. Nowadays most of the people prefer to buy online rather than going out if given a choice. There has been a lot happening in this area. Read this post to find out more about the past, present and what holds in the future of ecommerce.', '<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Let us go back a few years and think what were the top five things we did on the Internet. I guess online shopping will not be on that list. However, nowadays, I do not think there would be many people who would have online shopping missing from their list of activities. It has tremendous appeal, to various ages and classes â€” you can shop at your leisure, anytime, and in your pajamas.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Nowadays the thought of living without&nbsp;\r\n	ecommerce&nbsp;seems unreal, complicated and an inconvenience to many. It wasnâ€™t until only a few decades ago that the idea of&nbsp;\r\n	\r\n		ecommerce&nbsp;&nbsp;had&nbsp;even appeared.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">When It All Started</strong></h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Ecommerce&nbsp;became possible in 1991 when the Internet was opened to commercial use. \r\n	Themid 90s&nbsp;to 2000s saw major advancements in the commercial use of the internet. The largest online retailer in the world Amazon launched in 1995 as an online bookstore. Two decades later Amazon accounted for nearly half of all&nbsp;\r\n	ecommerce&nbsp;growth, highlighting that multivendor stores are in huge demand.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">How Is It Panning Out</strong></h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Ecommerce&nbsp;is touching new heights year after year. If you thought that it has reached its saturation point, few years down the line, you will come across new&nbsp;<a href=\"http://www.yo-kart.com/blog/5-niche-ecommerce-businesses-you-can-start-with-yokart/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">possibilities \r\n			ofecommerce&nbsp;business</strong></a>. If we ask ourselves what was our first encounter with&nbsp;\r\n	ecommerce, hardly anyone would remember. This is because our lives have become so intertwined with it, that we have started to take it for granted.</p>\r\n<h3 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal;\">Marketplaces</h3>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">If the past belonged to single store places, the present is all about multi-vendor stores. You name any type of big&nbsp;\r\n	ecommerce&nbsp;store and it will be a marketplace. Consumers prefer to buy from marketplaces rather than single vendor stores, as the former offers better options. This is the reason why most of the entrepreneurs are gunning for launching a multi-vendor store.</p>\r\n<h3 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal;\">Mobile</h3>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Back in 2003, when I had my first mobile phone (Nokia 3310), I could just send SMS, and make calls and play snake on it. For me, \r\n	ecommerce has been one of the biggest things that can happen to mobile devices. Nowadays, you can easily buy things while on the move, without any hassle.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">\"I wonder I could meet David again, who said he would never put his credit card online and certainly never buy anything online. I wonder if he still feels the same way. I doubt thatâ€</em></p>\r\n<h3 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal;\">Social Shopping</h3>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">When we talk about social network, all we can think of is communicating with friends and family, sharing photos and videos or looking at funny cat videos. But social networking has grown way bigger than this.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Social shopping has become a norm these days where online stores are using social media as a tool to engage with the audience. In addition to it, these platforms have themselves added \r\n	ecommerce functionality, where sellers can easily link their shop with the social network. Pinterest, Instagram, Twitter, and Facebook have already started to test buy button on their platform.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">So Whatâ€™s Next?</strong></h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">As already discussed many are of the view that \r\n	ecommerce has reached its saturation point, but there is a lot of \r\n	potential that is yet to come to the forefront. Already we are seeing a systematic shift towards new technologies which would drive the future growth story of \r\n	ecommerce.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Must Read:&nbsp;</em><a href=\"http://www.yo-kart.com/blog/a-progressive-guide-on-how-to-scale-your-ecommerce-startup/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">How to scale your \r\n			ecommerce startup</em></a></p>\r\n<h3 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal;\">Augmented Reality</h3>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Augmented and virtual reality is the place where everyone should be looking at. While in the past \r\n	ecommerce brought shopping stores onto your desktops and mobiles, VR will bring the whole virtual experience of shopping in a mall on to the table.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Till date, the whole user experience of online shopping is limited to 2-dimensions, but VR aims to add a third dimension to it. Facebook, Amazon, and Google have been actively working on such technologies to bring them to the forefront.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">You could just put on a VR device, sitting on the sofa in your living room and step into virtual reality world and feel like you are moving around a shopping mall.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Another scenario is when you want to buy a table for your house. You would browse through several pictures and choose the one which would not fit in the corner. With the help of AR, you could easily place the table in the living room using the device and see how it looks and fits beforehand.</p>\r\n<h3 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 22px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: normal;\">Artificial Intelligence</h3>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">A lot has been talked about AI in the recent past, but \r\n	still there is huge ambiguity about its role in \r\n	ecommerce. With growing popularity and&nbsp;<a href=\"http://www.yo-kart.com/blog/grow-your-ecommerce-business-with-the-power-of-big-data-uses-tools-2/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">use of BIG data in eCommerce</a>, AI \r\n	will in fact change the whole notion of product discovery. Rather than user searching among millions of products, the stores will now automatically deduce the preferences of users and showcase the items more \r\n	likeable to them. This will lead to a rise in predictive models and curated shopping.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Whatever happens in the next 5-10 years, one thing is for sure that the \r\n	ecommerce party has not yet ended. In \r\n	fact the best part is just about to come. But one thing is certain, \r\n	Ecommerce till date has been driven by convenience. It is all about anywhere, anytime and \r\n	however the consumer wants it. The key phrase is&nbsp;<strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Instant Gratification</strong>.</p>');
INSERT INTO `tbl_blog_post_lang` (`postlang_post_id`, `postlang_lang_id`, `post_author_name`, `post_title`, `post_short_description`, `post_description`) VALUES
(2, 1, 'YoKart Chef', '5 Crucial Steps Entrepreneurs Follow To Start A New Ecommerce Business', 'Are you probing how ecommerce companies take their first step towards online selling business? Thereâ€™s a lot that goes behind setting up an ecommerce store but most of the things remain same. Letâ€™s know whatâ€™s common in most entrepreneursâ€™ journey. This post will help you make an informed decision about how to start an ecommerce business.', '<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">There is a constant increase in the number of people buying things online. Digital sales are seen to expand rapidly, with a 23.7% growth rate forecast. According to statistics, \r\n	<g class=\"gr_ gr_65 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"65\" data-gr-id=\"65\">ecommerce</g> will now account for 8.7% of the overall retail spending worldwide.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">The improving statistics are due to an increase in middle class and internet penetration in developing nations. For entrepreneurs, this is the perfect opportunity to start their \r\n	<g class=\"gr_ gr_66 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"66\" data-gr-id=\"66\">ecommerce</g> business. But while doing so, an entrepreneur has to keep the essentials of starting an \r\n	<g class=\"gr_ gr_67 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"67\" data-gr-id=\"67\">ecommerce</g> business in mind.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Choose an Easy to use \r\n	<g class=\"gr_ gr_68 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"68\" data-gr-id=\"68\">Ecommerce</g> Platform</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Out of many reasons, a person opts to shop through an \r\n	<g class=\"gr_ gr_69 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"69\" data-gr-id=\"69\">ecommerce</g> store because of convenience and experience. By ignoring any one of the two, you deprive experience hungry buyers. Designing a good experience is a critical process and plays an important role to improve the efficiency and conversion rate.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">There can be a lot of complications while building an \r\n	<g class=\"gr_ gr_75 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"75\" data-gr-id=\"75\">ecommerce</g> website. To save yourself from the time-consuming process and avoid common pit-falls, consider choosing an \r\n	<g class=\"gr_ gr_76 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"76\" data-gr-id=\"76\">ecommerce</g> platform. Depending on your business plan, budget and scale of operations, you can choose an&nbsp;<a href=\"http://www.yo-kart.com/multivendor-store-demo.html\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">\r\n		<g class=\"gr_ gr_77 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"77\" data-gr-id=\"77\">ecommerce</g> platform</a>&nbsp;thatâ€™s growing continually in terms of user satisfaction and can meet your unique requirements. For instance, if you want to test the market and still avoid spending too much, try an \r\n	<g class=\"gr_ gr_78 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"78\" data-gr-id=\"78\">ecommerce</g> platform like YoKart. Other examples include CS-Cart &amp; Magento.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Know your Competitors and Customers</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Now that you have given a thought to the \r\n	<g class=\"gr_ gr_59 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"59\" data-gr-id=\"59\">ecommerce</g> website, the next step involves knowing your competitors and customers. While doing so, you can start by conducting a keyword based search on Google or any other search engine.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">For example, suppose you want to start an \r\n	<g class=\"gr_ gr_63 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"63\" data-gr-id=\"63\">ecommerce</g> business in New York and want to sell luxury designer clothing for women. Then you can start by searching \"Luxury clothing for women in New Yorkâ€ and \r\n	<g class=\"gr_ gr_74 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"74\" data-gr-id=\"74\">analyse</g> other \r\n	<g class=\"gr_ gr_64 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"64\" data-gr-id=\"64\">ecommerce</g> websites.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Doing \r\n	<g class=\"gr_ gr_60 gr-alert gr_gramm gr_inline_cards gr_run_anim Punctuation only-del replaceWithoutSep\" id=\"60\" data-gr-id=\"60\">so,</g> will help you build strategies to face competitors head on.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">For the next phase, you can \r\n	<g class=\"gr_ gr_71 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"71\" data-gr-id=\"71\">analyse</g> their product reviews and social media followers to see what people are saying about their service/product. This will help you gather the requirements of local audience and forecast product demand as well.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Must read:</strong>&nbsp;<a href=\"http://www.yo-kart.com/blog/how-to-connect-with-vendors-for-ecommerce-store/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">How to Connect with Vendors for Your Multivendor Ecommerce \r\n		<g class=\"gr_ gr_54 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"54\" data-gr-id=\"54\">Store ?</g></a></p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Go Local</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Starting small is essential for an \r\n	<g class=\"gr_ gr_61 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"61\" data-gr-id=\"61\">ecommerce</g> start-up to survive in a world dominated by major players like Amazon and eBay. Localization is one of those key factors that can make or break an \r\n	<g class=\"gr_ gr_62 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"62\" data-gr-id=\"62\">ecommerce</g> business.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">One great example of this is the toy powerhouse â€“ Mattel. In its initial days, the launch of Barbie in China was unsuccessful. A lot of research went in studying local audience to \r\n	<g class=\"gr_ gr_73 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"73\" data-gr-id=\"73\">analyse</g> where the company went wrong.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Offer the right price</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">After you know your customers and competitors, itâ€™s time that you start offering products on your \r\n	<g class=\"gr_ gr_81 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"81\" data-gr-id=\"81\">ecommerce</g> website at prices tuned for your target audience. To offer a competitive price, you have to overcome various operational and other costs to gain market share.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Before finalizing the pricing strategy, consider the different costs, demand &amp; supply, competitorâ€™s pricing, the tax environment, etc. Furthermore, a lot depends on the payment method you will use on an \r\n	<g class=\"gr_ gr_58 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"58\" data-gr-id=\"58\">ecommerce</g> website.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">For example, in certain developing nations, cash on delivery is the primary mode of payment, whereas, in developed nations, credit cards and debit cards are accepted.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 26px;\"></span></p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 26px;\">Sales and Marketing</span></p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">\r\n	<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">While starting an \r\n		<g class=\"gr_ gr_122 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"122\" data-gr-id=\"122\">ecommerce</g> business, marketing plays an important role as it can help your start-up to stand out from the rest of the crowd. During the initial phase, pre-launch marketing does wonders as it enables a new business to generate hype and improve the \r\n		<g class=\"gr_ gr_125 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling\" id=\"125\" data-gr-id=\"125\">presence</g>.</p>\r\n	<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Furthermore, with marketing campaigns and discount offers, you can attract targeted traffic and also improve social media followers. Some well-tried techniques that can help you boost your marketing effort are paid advertising, search engine optimization, content marketing, remarketing, etc.</p>\r\n	<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\"><span style=\"font-weight: bold;\">Must Read:</span>&nbsp;<a href=\"http://www.yo-kart.com/blog/choose-e-commerce-platform-optimal-seo/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">How to choose an eCommerce platform&nbsp;for optimal SEO</a></p>\r\n	<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Besides all tested methods you can use the&nbsp;<a href=\"http://www.yo-kart.com/blog/grow-your-ecommerce-business-with-the-power-of-big-data-uses-tools-2/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">power of big data to grow \r\n			<g class=\"gr_ gr_120 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"120\" data-gr-id=\"120\">ecommerce</g></a>&nbsp;business. The biggies like Amazon have exploited it to increase \r\n		<g class=\"gr_ gr_127 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"127\" data-gr-id=\"127\">their&nbsp; conversion</g>, \r\n		<g class=\"gr_ gr_126 gr-alert gr_gramm gr_inline_cards gr_run_anim Punctuation only-ins replaceWithoutSep\" id=\"126\" data-gr-id=\"126\">sales</g> and customer acquisition rate. Donâ€™t miss it if you are firm on getting \r\n		<g class=\"gr_ gr_129 gr-alert gr_gramm gr_inline_cards gr_run_anim Grammar only-ins doubleReplace replaceWithoutSep\" id=\"129\" data-gr-id=\"129\">crucial</g> edge over competitors.</p>\r\n	<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Conclusion</h2>\r\n	<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Starting an \r\n		<g class=\"gr_ gr_119 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"119\" data-gr-id=\"119\">ecommerce</g> business is a challenge but with the right knowledge, you can achieve success. To stay on the right track, follow the above-mentioned steps and gain significant profit in the process.</p>\r\n	<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\"><a href=\"http://www.yo-kart.com/contact-us.html\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">Send us your queries</a>&nbsp;regarding ecommerce. Our &nbsp;experts can help you with launch and growth issues that stop you from taking any step further.</p><br />\r\n	</p>'),
(2, 2, 'YoKart Chef', '5 Crucial Steps Entrepreneurs Follow To Start A New Ecommerce Business', 'Are you probing how ecommerce companies take their first step towards online selling business? Thereâ€™s a lot that goes behind setting up an ecommerce store but most of the things remain same. Letâ€™s know whatâ€™s common in most entrepreneursâ€™ journey. This post will help you make an informed decision about how to start an ecommerce business.', '<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">There is a constant increase in the number of people buying things online. Digital sales are seen to expand rapidly, with a 23.7% growth rate forecast. According to statistics, \r\n	<g class=\"gr_ gr_78 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"78\" data-gr-id=\"78\">ecommerce</g> will now account for 8.7% of the overall retail spending worldwide.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">The improving statistics are due to an increase in middle class and internet penetration in developing nations. For entrepreneurs, this is the perfect opportunity to start their \r\n	<g class=\"gr_ gr_92 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"92\" data-gr-id=\"92\">ecommerce</g> business. But while doing so, an entrepreneur has to keep the essentials of starting an \r\n	<g class=\"gr_ gr_93 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"93\" data-gr-id=\"93\">ecommerce</g> business in mind.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\">Choose an Easy to use \r\n	<g class=\"gr_ gr_94 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"94\" data-gr-id=\"94\">Ecommerce</g> Platform</h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Out of many reasons, a person opts to shop through an \r\n	<g class=\"gr_ gr_95 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"95\" data-gr-id=\"95\">ecommerce</g> store because of convenience and experience. By ignoring any one of the two, you deprive experience hungry buyers. Designing a good experience is a critical process and plays an important role to improve the efficiency and conversion rate.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">There can be a lot of complications while building an \r\n	<g class=\"gr_ gr_102 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"102\" data-gr-id=\"102\">ecommerce</g> website. To save yourself from the time-consuming process and avoid common pit-falls, consider choosing an \r\n	<g class=\"gr_ gr_103 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"103\" data-gr-id=\"103\">ecommerce</g> platform. Depending on your business plan, budget and scale of operations, you can choose \r\n	<g class=\"gr_ gr_114 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"114\" data-gr-id=\"114\">an&nbsp;</g><a href=\"http://www.yo-kart.com/multivendor-store-demo.html\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">\r\n		<g class=\"gr_ gr_114 gr-alert gr_gramm gr_inline_cards gr_disable_anim_appear Style multiReplace\" id=\"114\" data-gr-id=\"114\">&nbsp;\r\n			<g class=\"gr_ gr_104 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"104\" data-gr-id=\"104\">ecommerce</g></g> platform</a>&nbsp;thatâ€™s growing continually in terms of user satisfaction and can meet your unique requirements. For instance, if you want to test the market and still avoid spending too much, try an \r\n	<g class=\"gr_ gr_105 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"105\" data-gr-id=\"105\">ecommerce</g> platform like YoKart. Other examples include CS-Cart &amp; Magento.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\">Know your Competitors and Customers</h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Now that you have given a thought to the \r\n	<g class=\"gr_ gr_87 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"87\" data-gr-id=\"87\">ecommerce</g> website, the next step involves knowing your competitors and customers. While doing so, you can start by conducting a keyword based search on Google or any other search engine.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">For example, suppose you want to start an \r\n	<g class=\"gr_ gr_82 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"82\" data-gr-id=\"82\">ecommerce</g> business in New York and want to sell luxury designer clothing for women. Then you can start by searching \"Luxury clothing for women in New Yorkâ€ and \r\n	<g class=\"gr_ gr_106 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"106\" data-gr-id=\"106\">analyse</g> other \r\n	<g class=\"gr_ gr_83 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"83\" data-gr-id=\"83\">ecommerce</g> websites.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Doing \r\n	<g class=\"gr_ gr_90 gr-alert gr_gramm gr_inline_cards gr_run_anim Punctuation only-del replaceWithoutSep\" id=\"90\" data-gr-id=\"90\">so,</g> will help you build strategies to face competitors head on.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">For the next phase, you can \r\n	<g class=\"gr_ gr_111 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"111\" data-gr-id=\"111\">analyse</g> their product reviews and social media followers to see what people are saying about their service/product. This will help you gather the requirements of local audience and forecast product demand as well.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Must read:</strong>&nbsp;<a href=\"http://www.yo-kart.com/blog/how-to-connect-with-vendors-for-ecommerce-store/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">How to Connect with Vendors for Your Multivendor Ecommerce \r\n		<g class=\"gr_ gr_71 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"71\" data-gr-id=\"71\">Store ?</g></a></p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\">Go Local</h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Starting small is essential for an \r\n	<g class=\"gr_ gr_79 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"79\" data-gr-id=\"79\">ecommerce</g> start-up to survive in a world dominated by major players like Amazon and eBay. Localization is one of those key factors that can make or break an \r\n	<g class=\"gr_ gr_80 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"80\" data-gr-id=\"80\">ecommerce</g> business.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">One great example of this is the toy powerhouse â€“ Mattel. In its initial days, the launch of Barbie in China was unsuccessful. A lot of research went in studying local audience to \r\n	<g class=\"gr_ gr_101 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"101\" data-gr-id=\"101\">analyse</g> where the company went wrong.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\">Offer the right price</h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">After you know your customers and competitors, itâ€™s time that you start offering products on your \r\n	<g class=\"gr_ gr_81 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"81\" data-gr-id=\"81\">ecommerce</g> website at prices tuned for your target audience. To offer a competitive price, you have to overcome various operational and other costs to gain market share.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Before finalizing the pricing strategy, consider the different costs, demand &amp; supply, competitorâ€™s pricing, the tax environment, etc. Furthermore, a lot depends on the payment method you will use on an \r\n	<g class=\"gr_ gr_84 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"84\" data-gr-id=\"84\">ecommerce</g> website.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">For example, in certain developing nations, cash on delivery is the primary mode of payment, whereas, in developed nations, credit cards and debit cards are accepted.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><span style=\"font-size: 26px;\"></span></p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><span style=\"font-size: 26px;\">Sales and Marketing</span></p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">&nbsp;</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">While starting an \r\n	<g class=\"gr_ gr_89 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"89\" data-gr-id=\"89\">ecommerce</g> business, marketing plays an important role as it can help your start-up to stand out from the rest of the crowd. During the initial phase, pre-launch marketing does wonders as it enables a new business to generate hype and improve the \r\n	<g class=\"gr_ gr_98 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling\" id=\"98\" data-gr-id=\"98\">presence</g>.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Furthermore, with marketing campaigns and discount offers, you can attract targeted traffic and also improve social media followers. Some well-tried techniques that can help you boost your marketing effort are paid advertising, search engine optimization, content marketing, remarketing, etc.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><span style=\"font-weight: bold;\">Must Read:</span>&nbsp;<a href=\"http://www.yo-kart.com/blog/choose-e-commerce-platform-optimal-seo/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">How to choose an eCommerce platform&nbsp;for optimal SEO</a></p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Besides all tested methods you can use the&nbsp;<a href=\"http://www.yo-kart.com/blog/grow-your-ecommerce-business-with-the-power-of-big-data-uses-tools-2/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">power of big data to grow \r\n		<g class=\"gr_ gr_91 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"91\" data-gr-id=\"91\">ecommerce</g></a>&nbsp;business. The biggies like Amazon have exploited it to increase \r\n	<g class=\"gr_ gr_108 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"108\" data-gr-id=\"108\">their&nbsp; conversion</g>, \r\n	<g class=\"gr_ gr_107 gr-alert gr_gramm gr_inline_cards gr_run_anim Punctuation only-ins replaceWithoutSep\" id=\"107\" data-gr-id=\"107\">sales</g> and customer acquisition rate. Donâ€™t miss it if you are firm on getting \r\n	<g class=\"gr_ gr_110 gr-alert gr_gramm gr_inline_cards gr_run_anim Grammar only-ins doubleReplace replaceWithoutSep\" id=\"110\" data-gr-id=\"110\">crucial</g> edge over competitors.</p>\r\n<h2 open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px;\">Conclusion</h2>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\">Starting an \r\n	<g class=\"gr_ gr_75 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"75\" data-gr-id=\"75\">ecommerce</g> business is a challenge but with the right knowledge, you can achieve success. To stay on the right track, follow the above-mentioned steps and gain significant profit in the process.</p>\r\n<p open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px;\"><a href=\"http://www.yo-kart.com/contact-us.html\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">Send us your queries</a>&nbsp;regarding \r\n	<g class=\"gr_ gr_76 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"76\" data-gr-id=\"76\">ecommerce</g>. \r\n	<g class=\"gr_ gr_96 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"96\" data-gr-id=\"96\">Our &nbsp;experts</g> can help you with launch and growth issues that stop you from taking any step further.</p>\r\n<div><br />\r\n	</div>');
INSERT INTO `tbl_blog_post_lang` (`postlang_post_id`, `postlang_lang_id`, `post_author_name`, `post_title`, `post_short_description`, `post_description`) VALUES
(3, 1, 'YoKart Chef', '5 Features That Make YoKart a Seller-Friendly Ecommerce Platform', 'There is a constant increase in the number of people buying things online. Digital sales are seen to expand rapidly, with a 23.7% growth rate forecast. According to statistics, ecommerce will now account for 8.7% of the overall retail spending worldwide.', '<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">There is a constant increase in the number of people buying things online. Digital sales are seen to expand rapidly, with a 23.7% growth rate forecast. According to statistics, \r\n	<g class=\"gr_ gr_59 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"59\" data-gr-id=\"59\">ecommerce</g> will now account for 8.7% of the overall retail spending worldwide.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">The improving statistics are due to an increase in middle class and internet penetration in developing nations. For entrepreneurs, this is the perfect opportunity to start their \r\n	<g class=\"gr_ gr_66 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"66\" data-gr-id=\"66\">ecommerce</g> business. But while doing so, an entrepreneur has to keep the essentials of starting an \r\n	<g class=\"gr_ gr_67 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"67\" data-gr-id=\"67\">ecommerce</g> business in mind.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Choose an Easy to use \r\n	<g class=\"gr_ gr_68 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"68\" data-gr-id=\"68\">Ecommerce</g> Platform</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Out of many reasons, a person opts to shop through an \r\n	<g class=\"gr_ gr_69 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"69\" data-gr-id=\"69\">ecommerce</g> store because of convenience and experience. By ignoring any one of the two, you deprive experience hungry buyers. Designing a good experience is a critical process and plays an important role to improve the efficiency and conversion rate.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">There can be a lot of complications while building an \r\n	<g class=\"gr_ gr_75 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"75\" data-gr-id=\"75\">ecommerce</g> website. To save yourself from the time-consuming process and avoid common pit-falls, consider choosing an \r\n	<g class=\"gr_ gr_76 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"76\" data-gr-id=\"76\">ecommerce</g> platform. Depending on your business plan, budget and scale of operations, you can choose an&nbsp;<a href=\"http://www.yo-kart.com/multivendor-store-demo.html\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">\r\n		<g class=\"gr_ gr_77 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"77\" data-gr-id=\"77\">ecommerce</g> platform</a>&nbsp;thatâ€™s growing continually in terms of user satisfaction and can meet your unique requirements. For instance, if you want to test the market and still avoid spending too much, try an \r\n	<g class=\"gr_ gr_78 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"78\" data-gr-id=\"78\">ecommerce</g> platform like YoKart. Other examples include CS-Cart &amp; Magento.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Know your Competitors and Customers</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Now that you have given a thought to the \r\n	<g class=\"gr_ gr_71 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"71\" data-gr-id=\"71\">ecommerce</g> website, the next step involves knowing your competitors and customers. While doing so, you can start by conducting a keyword based search on Google or any other search engine.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">For example, suppose you want to start an \r\n	<g class=\"gr_ gr_64 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"64\" data-gr-id=\"64\">ecommerce</g> business in New York and want to sell luxury designer clothing for women. Then you can start by searching \"Luxury clothing for women in New Yorkâ€ and \r\n	<g class=\"gr_ gr_74 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"74\" data-gr-id=\"74\">analyse</g> other \r\n	<g class=\"gr_ gr_65 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"65\" data-gr-id=\"65\">ecommerce</g> websites.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Doing \r\n	<g class=\"gr_ gr_57 gr-alert gr_gramm gr_inline_cards gr_run_anim Punctuation only-del replaceWithoutSep\" id=\"57\" data-gr-id=\"57\">so,</g> will help you build strategies to face competitors head on.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">For the next phase, you can \r\n	<g class=\"gr_ gr_72 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"72\" data-gr-id=\"72\">analyse</g> their product reviews and social media followers to see what people are saying about their service/product. This will help you gather the requirements of local audience and forecast product demand as well.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: normal;\">Must read:</strong>&nbsp;<a href=\"http://www.yo-kart.com/blog/how-to-connect-with-vendors-for-ecommerce-store/\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">How to Connect with Vendors for Your Multivendor Ecommerce \r\n		<g class=\"gr_ gr_52 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"52\" data-gr-id=\"52\">Store ?</g></a></p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Go Local</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Starting small is essential for an \r\n	<g class=\"gr_ gr_62 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"62\" data-gr-id=\"62\">ecommerce</g> start-up to survive in a world dominated by major players like Amazon and eBay. Localization is one of those key factors that can make or break an \r\n	<g class=\"gr_ gr_63 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"63\" data-gr-id=\"63\">ecommerce</g> business.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">One great example of this is the toy powerhouse â€“ Mattel. In its initial days, the launch of Barbie in China was unsuccessful. A lot of research went in studying local audience to \r\n	<g class=\"gr_ gr_79 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling multiReplace\" id=\"79\" data-gr-id=\"79\">analyse</g> where the company went wrong.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \"Open Sans\", sans-serif; font-size: 26px; direction: ltr; font-weight: normal; color: rgb(34, 34, 34); clear: both; background-color: rgb(255, 255, 255);\">Offer the right price</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">After you know your customers and competitors, itâ€™s time that you start offering products on your \r\n	<g class=\"gr_ gr_61 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"61\" data-gr-id=\"61\">ecommerce</g> website at prices tuned for your target audience. To offer a competitive price, you have to overcome various operational and other costs to gain market share.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">Before finalizing the pricing strategy, consider the different costs, demand &amp; supply, competitorâ€™s pricing, the tax environment, etc. Furthermore, a lot depends on the payment method you will use on an \r\n	<g class=\"gr_ gr_58 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling ins-del multiReplace\" id=\"58\" data-gr-id=\"58\">ecommerce</g> website.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \"Open Sans\", sans-serif; font-size: 15px; direction: ltr; color: rgb(34, 34, 34); text-align: justify; background-color: rgb(255, 255, 255);\">For example, in certain developing nations, cash on delivery is the primary mode of payment, whereas, in developed nations, credit cards and debit cards are accepted.</p>'),
(3, 2, 'YoKart Chef', '5 Features That Make YoKart a Seller-Friendly Ecommerce Platform', 'There is a constant increase in the number of people buying things online. Digital sales are seen to expand rapidly, with a 23.7% growth rate forecast. According to statistics, ecommerce will now account for 8.7% of the overall retail spending worldwide.', '<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">There is a constant increase in the number of people buying things online. Digital sales are seen to expand rapidly, with a 23.7% growth rate forecast. According to statistics, 	ecommerce will now account for 8.7% of the overall retail spending worldwide.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">The improving statistics are due to an increase in middle class and internet penetration in developing nations. For entrepreneurs, this is the perfect opportunity to start \r\n	<g class=\"gr_ gr_88 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"88\" data-gr-id=\"88\">their 	ecommerce</g> business. But while doing so, an entrepreneur has to keep the essentials of starting \r\n	<g class=\"gr_ gr_89 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"89\" data-gr-id=\"89\">an 	ecommerce</g> business in mind.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Choose an Easy to use 	Ecommerce Platform</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Out of many reasons, a person opts to shop through \r\n	<g class=\"gr_ gr_90 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"90\" data-gr-id=\"90\">an 	ecommerce</g> store because of convenience and experience. By ignoring any one of the two, you deprive experience hungry buyers. Designing a good experience is a critical process and plays an important role to improve the efficiency and conversion rate.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">There can be a lot of complications while building \r\n	<g class=\"gr_ gr_92 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"92\" data-gr-id=\"92\">an 	ecommerce</g> website. To save yourself from the time-consuming process and avoid common pit-falls, consider choosing \r\n	<g class=\"gr_ gr_93 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"93\" data-gr-id=\"93\">an 	ecommerce</g> platform. Depending on your business plan, budget and scale of operations, you can choose \r\n	<g class=\"gr_ gr_94 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"94\" data-gr-id=\"94\">an&nbsp;</g><a href=\"http://www.yo-kart.com/multivendor-store-demo.html\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; line-height: inherit; outline: none; transition: all 0.5s ease-in-out; color: rgb(173, 64, 93);\">\r\n		<g class=\"gr_ gr_94 gr-alert gr_gramm gr_inline_cards gr_disable_anim_appear Style multiReplace\" id=\"94\" data-gr-id=\"94\"> 		ecommerce</g> platform</a>&nbsp;thatâ€™s growing continually in terms of user satisfaction and can meet your unique requirements. For instance, if you want to test the market and still avoid spending too much, try \r\n	<g class=\"gr_ gr_96 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"96\" data-gr-id=\"96\">an 	ecommerce</g> platform like YoKart. Other examples include CS-Cart &amp; Magento.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Know your Competitors and Customers</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Now that you have given a thought to \r\n	<g class=\"gr_ gr_75 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"75\" data-gr-id=\"75\">the 	ecommerce</g> website, the next step involves knowing your competitors and customers. While doing so, you can start by conducting a keyword based search on Google or any other search engine.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">For example, suppose you want to start \r\n	<g class=\"gr_ gr_71 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"71\" data-gr-id=\"71\">an 	ecommerce</g> business in New York and want to sell luxury designer clothing for women. Then you can start by searching \"Luxury clothing for women in New Yorkâ€ \r\n	<g class=\"gr_ gr_73 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"73\" data-gr-id=\"73\">and 	analyse</g> \r\n	<g class=\"gr_ gr_74 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"74\" data-gr-id=\"74\">other 	ecommerce</g> websites.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">\r\n	<g class=\"gr_ gr_58 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"58\" data-gr-id=\"58\">Doing </g>\r\n	<g class=\"gr_ gr_57 gr-alert gr_gramm gr_inline_cards gr_run_anim Punctuation only-del replaceWithoutSep\" id=\"57\" data-gr-id=\"57\">\r\n		<g class=\"gr_ gr_58 gr-alert gr_gramm gr_inline_cards gr_disable_anim_appear Style multiReplace\" id=\"58\" data-gr-id=\"58\">so</g>,</g> will help you build strategies to face competitors head on.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">For the next phase, you \r\n	<g class=\"gr_ gr_83 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"83\" data-gr-id=\"83\">can 	analyse</g> their product reviews and social media followers to see what people are saying about their service/product. This will help you gather the requirements of local audience and forecast product demand as well.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><span style=\"font-size: 26px;\">Go Local</span></p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Starting small is essential for \r\n	<g class=\"gr_ gr_67 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"67\" data-gr-id=\"67\">an 	ecommerce</g> start-up to survive in a world dominated by major players like Amazon and eBay. Localization is one of those key factors that can make or break \r\n	<g class=\"gr_ gr_68 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"68\" data-gr-id=\"68\">an 	ecommerce</g> business.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">One great example of this is the toy powerhouse â€“ Mattel. In its initial days, the launch of Barbie in China was unsuccessful. A lot of research went in studying local audience \r\n	<g class=\"gr_ gr_77 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"77\" data-gr-id=\"77\">to 	analyse</g> where the company went wrong.</p>\r\n<h2 style=\"box-sizing: border-box; margin: 0px; padding: 10px 0px; border: 0px; line-height: 26px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 26px;=\"\" direction:=\"\" ltr;=\"\" font-weight:=\"\" normal;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" clear:=\"\" both;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Offer the right price</h2>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">After you know your customers and competitors, itâ€™s time that you start offering products on \r\n	<g class=\"gr_ gr_85 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"85\" data-gr-id=\"85\">your 	ecommerce</g> website at prices tuned for your target audience. To offer a competitive price, you have to overcome various operational and other costs to gain market share.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Before finalizing the pricing strategy, consider the different costs, demand &amp; supply, competitorâ€™s pricing, the tax environment, etc. Furthermore, a lot depends on the payment method you will use on \r\n	<g class=\"gr_ gr_70 gr-alert gr_gramm gr_inline_cards gr_run_anim Style multiReplace\" id=\"70\" data-gr-id=\"70\">an 	ecommerce</g> website.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px; padding: 0px 0px 10px; border: 0px; line-height: 24px; font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 15px;=\"\" direction:=\"\" ltr;=\"\" color:=\"\" rgb(34,=\"\" 34,=\"\" 34);=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">For example, in certain developing nations, cash on delivery is the primary mode of payment, whereas, in developed nations, credit cards and debit cards are accepted.</p>');
INSERT INTO `tbl_blog_post_lang` (`postlang_post_id`, `postlang_lang_id`, `post_author_name`, `post_title`, `post_short_description`, `post_description`) VALUES
(4, 1, 'Kelly Carroll founder/CEO Weyakyak', 'Be Positive...Not Perfect', 'Teach with a positive motive!', '<div class=\"RichEditor flex flex-1 flex-direction-column RichEditor--readOnly margin-bottom-xl\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative; -webkit-box-flex: 1; flex: 1 1 0%; display: flex; -webkit-box-orient: vertical; -webkit-box-direction: normal; flex-direction: column; color: rgb(79, 84, 87); font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 14.25px;=\"\" letter-spacing:=\"\" -0.448px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" margin-bottom:=\"\" 16px=\"\" !important;\"=\"\">\r\n	<div class=\"DraftEditor-root\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n		<div class=\"DraftEditor-editorContainer\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n			<div aria-describedby=\"placeholder-RichEditor\" class=\"public-DraftEditor-content\" spellcheck=\"false\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); outline: none; user-select: text; white-space: pre-wrap; overflow-wrap: break-word;\">\r\n				<div data-contents=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"ajark-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"ajark-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"ajark-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Teaching a language is not all about being the perfect teacher with the perfect grammar.  It\'s about being passionate about your students and connecting with them through loving what you offer them.  Even the best of the best make grammar mistakes sometimes.  But teachers who are continually moving forward in education because it is their passion will make the best teachers.  There\'s always room for educating your skills to become a better teacher, but if you are not passionate about your teaching, then all your efforts are meaningless.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"82brr-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"82brr-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"82brr-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">I really appreciate teachers who are truly passionate about teaching. The teacher who wants to be an inspiration to others. The teacher who is happy with his/her job at all times. The teacher who every child in the school would love to have. The teacher kids remember for the rest of their lives. Are you that teacher? Read on and learn 11 effective habits of an effective teacher.</span></span></div></div><br />\r\n					\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"c0bj9-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"c0bj9-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span style=\"font-weight: bold;\">1. Enjoys Teaching</span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"7sio6-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Teaching is meant to be a very enjoyable and rewarding career field (although demanding and exhausting at times!). You should only become a teacher if you love people and intend on caring for them with your heart. You cannot expect the your students to have fun if you are not having fun with them!  Make your lessons come alive by making it as interactive and engaging as possible. Let your passion for teaching shine through each and everyday. Enjoy every teaching moment to the fullest.</div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"6a1dv-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"6a1dv-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"6a1dv-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">2. Makes a Difference</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"9k6nt-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"9k6nt-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"9k6nt-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">There is a saying, \"With great power, comes great responsibility\". As a teacher, you need to be aware and remember the great responsibility that comes with your profession. One of your goals ought to be: Make a difference in their lives. How? Make them feel special, safe and secure when they are in your class session. Be the positive influence in their lives. Why? You never know what your students went through before entering your classroom on a particular day or what conditions they are going home to after your class. So, just in case they are not getting enough support from home, at least you will make a difference and provide that to them.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"28lol-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"28lol-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"28lol-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">3. Spreads Positivity</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"djqs3-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"djqs3-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"djqs3-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Bring positive energy into the classroom every single day. You have a beautiful smile so don\'t forget to flash it as much as possible throughout the day. We all face a daily battle or issues in our life, but once you enter that class session, you should leave all of it behind before you sign into your class. Your students deserve more than for you to take your frustration out on them. No matter how you are feeling, how much sleep you\'ve gotten or how frustrated you are, never let that show. Even if you are having a bad day, learn to put on a mask in front of the students and let them think of you as a superhero (it will make your day too)! Be someone who is always positive, happy and smiling. Always remember that positive energy is contagious and it will bring your student back to you over and over again.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"e8vp2-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"e8vp2-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"e8vp2-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">4. Gets Personal</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"ed59-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"ed59-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"ed59-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">This is the fun part and absolutely important for being an effective teacher! Get to know your students and their interests so that you can find ways to connect with them. Don\'t forget to also tell them about yours! Also, it is important to get to know their learning styles so that you can cater to each of them as an individual. </span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"cfscs-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"cfscs-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"cfscs-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">5. Gives 100%</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"85dn6-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"85dn6-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"85dn6-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Whether you are delivering a lesson, practicing conversation or just talking about the day, remember it is all about your student.  Do your job for the love of teaching and not because you feel obligated to do it. Do it for self-growth. Do it to inspire others. Do it so that your students will get the most out of what you are teaching them. Give 100% for yourself, students, and everyone who believes in you. Never give up and try your best - that\'s all that you can do. (That\'s what I tell my students!)</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"f8rl1-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"f8rl1-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"f8rl1-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">6. Stays Organized</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"df50f-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"df50f-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"df50f-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Always be on time in your class sessions and keep a journal handy and jot down your ideas as soon as an inspired idea forms in your mind. Then, make a plan to put those ideas in action.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"1tnee-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"1tnee-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"1tnee-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">7. Is Open-Minded</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"adm5o-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"adm5o-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"adm5o-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">As a teacher, there are going to be times where you will be observed formally or informally (that\'s also why you should give 100% at all times). You are constantly being evaluated and criticized by your peers or other teachers. Instead of feeling bitter when somebody has something to say about your teaching, be open-minded when receiving constructive criticism and form a plan of action. Prove that you are the effective teacher that you want to be. Nobody is perfect and there is always room for improvement. Sometimes, others see what you fail to see.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"d0sdi-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"d0sdi-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"d0sdi-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">8. Has Standards</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"8hoep-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"8hoep-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"8hoep-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Create standards for your students and for yourself. From the beginning, make sure that they know what is acceptable versus what isn\'t.  Are you the teacher who wants your students to try their best? Or are you the teacher who couldn\'t care less? Now remember, you can only expect a lot if you give a lot. As the saying goes, \"Practice what you preach\".</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"ctcgt-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"ctcgt-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"ctcgt-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">9. Finds Inspiration</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"5orl4-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"5orl4-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"5orl4-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">An effective teacher is one who is creative but that doesn\'t mean that you have to create everything from scratch! Find inspiration from as many sources as you can. Whether it comes from books, education, Pinterest, YouTube, Facebook, blogs, or what have you, keep finding it!</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"1qr5-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"1qr5-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"1qr5-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">10. Embraces Change</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"a2le1-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"a2le1-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"a2le1-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">In life, things don\'t always go according to plan. This is particularly true when it comes to teaching. Be flexible and go with the flow when change occurs. An effective teacher does not complain about changes when the work environment changes. They do not feel the need to mention how good they had it at their last place of employment or with their last group of students compared to their current circumstances. Instead of stressing about change, embrace it with both hands and show that you are capable of hitting every curve ball that comes your way!</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"40ktt-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"40ktt-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"40ktt-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-weight: bold;\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">11. Creates Reflection</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"a981m-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"a981m-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"a981m-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">An effective teacher reflects on their teaching to evolve as a teacher. Think about what went well and what you would do differently next time. You need to remember that we all have \"failed\" lessons from time to time. Instead of looking at it as a failure, think about it as a lesson and learn from it. As teachers, your education and learning is ongoing. There is always more to learn and know about in order to strengthen your teaching skills. Keep reflecting on your work and educating yourself on what you find are your \"weaknesses\" as we all have them! The most important part is recognizing them and being able to work on them to improve your teaching skills.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"er9ca-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"er9ca-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"er9ca-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">There are, indeed, several other habits that make an effective teacher but these are the ones that I find most important. Many other character traits can be tied into these ones as well.</span></span></div></div>\r\n					<div class=\"\" data-block=\"true\" data-editor=\"RichEditor\" data-offset-key=\"6bl78-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n						<div data-offset-key=\"6bl78-0-0\" class=\"public-DraftStyleDefault-block public-DraftStyleDefault-ltr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-offset-key=\"6bl78-0-0\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\"><span data-text=\"true\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">Last word:  This is only my perspective on teaching and there is always something positive to be found in every situation but it is up to you to find it. Keep your head up and teach happily for the love of education!</span></span></div></div></div></div></div></div>\r\n	<div class=\"SideToolbar\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); z-index: 2;\">\r\n		<div class=\"draftJsToolbar__wrapper__9NZgg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: absolute; transform: scale(0);\">\r\n			<div style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n				<div class=\"draftJsToolbar__blockType__27Jwn\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 1px solid rgb(221, 221, 221); background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; padding: 5px; margin: 0px; border-radius: 18px; cursor: pointer; height: 36px; width: 36px; line-height: 36px; text-align: center;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path>\r\n						<path d=\"M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z\"></path></svg></div>\r\n				<div class=\"draftJsToolbar__spacer__2Os2z\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); height: 8px; position: absolute; left: 17.9972px; transform: translate(-50%); width: 74px;\">&nbsp;</div>\r\n				<div class=\"draftJsToolbar__popup__GHzbY\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: absolute; left: 17.9972px; transform: translate(-50%) scale(0); width: 74px; border: 1px solid rgb(221, 221, 221); background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border-radius: 2px; box-shadow: rgb(220, 220, 220) 0px 1px 3px 0px; z-index: 3; margin-top: 8px;\">\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">H1</button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">H2</button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n							<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n								<path d=\"M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5S3.17 7.5 4 7.5 5.5 6.83 5.5 6 4.83 4.5 4 4.5zm0 12c-.83 0-1.5.68-1.5 1.5s.68 1.5 1.5 1.5 1.5-.68 1.5-1.5-.67-1.5-1.5-1.5zM7 19h14v-2H7v2zm0-6h14v-2H7v2zm0-8v2h14V5H7z\"></path>\r\n								<path d=\"M0 0h24v24H0V0z\" fill=\"none\"></path></svg></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n							<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n								<path d=\"M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z\"></path>\r\n								<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n							<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n								<path d=\"M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z\"></path>\r\n								<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"><img width=\"20px\" height=\"20px\" alt=\"add_image\" src=\"https://cdn.verbling.com/static/svg/icons8/7a72ddc978d479dd9a1b6155e8d36a04.icons8-add_image.svg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 0px; vertical-align: middle; max-width: inherit; margin-top: -10px; width: 20px; height: 20px;\" /></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"><img width=\"20px\" height=\"20px\" alt=\"youtube_play\" src=\"https://cdn.verbling.com/static/svg/icons8/312a0c0ae97ae39563cf471f7c9d2bf7.icons8-youtube_play.svg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 0px; vertical-align: middle; max-width: inherit; margin-top: -10px; width: 20px; height: 20px;\" /></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"><img width=\"20px\" height=\"20px\" alt=\"tv_show\" src=\"https://cdn.verbling.com/static/svg/icons8/6f1844fa670b01e64d89fa4584a052d4.icons8-tv_show.svg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 0px; vertical-align: middle; max-width: inherit; margin-top: -10px; width: 20px; height: 20px;\" /></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"><img width=\"20px\" height=\"20px\" alt=\"webcam\" src=\"https://cdn.verbling.com/static/svg/icons8/9c2754639c2983025f5d6e088bd7a2fb.icons8-webcam.svg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 0px; vertical-align: middle; max-width: inherit; margin-top: -10px; width: 20px; height: 20px;\" /></button></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<div style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n							<div style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n								<button class=\"draftJsToolbar__button__qi1gf\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"><img width=\"20px\" height=\"20px\" alt=\"lol\" src=\"https://cdn.verbling.com/static/svg/icons8/e2bd4d028a1c3f9aedd9310e76e4ec22.icons8-lol.svg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 0px; vertical-align: middle; max-width: inherit; margin-top: -10px; width: 20px; height: 20px;\" /></button></div></div></div>\r\n					<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n						<button class=\"draftJsToolbar__button__qi1gf\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"><img width=\"20px\" height=\"20px\" alt=\"sheets\" src=\"https://cdn.verbling.com/static/svg/icons8/a78de82e0257f8b0c1396c0c2b3f690f.icons8-sheets.svg\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border: 0px; vertical-align: middle; max-width: inherit; margin-top: -10px; width: 20px; height: 20px;\" /></button></div></div></div></div></div>\r\n	<div class=\"InlineToolbar\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);\">\r\n		<div class=\"draftJsToolbar__toolbar__dNtBH\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); left: 330px; transform: translate(-50%) scale(0); position: absolute; border: 1px solid rgb(221, 221, 221); background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border-radius: 2px; box-shadow: rgb(220, 220, 220) 0px 1px 3px 0px; z-index: 2; visibility: hidden;\">\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M15.6 10.79c.97-.67 1.65-1.77 1.65-2.79 0-2.26-1.75-4-4-4H7v14h7.04c2.09 0 3.71-1.7 3.71-3.79 0-1.52-.86-2.82-2.15-3.42zM10 6.5h3c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-3v-3zm3.5 9H10v-3h3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5z\"></path>\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path>\r\n						<path d=\"M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4z\"></path></svg></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path>\r\n						<path d=\"M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z\"></path></svg></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\"></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5S3.17 7.5 4 7.5 5.5 6.83 5.5 6 4.83 4.5 4 4.5zm0 12c-.83 0-1.5.68-1.5 1.5s.68 1.5 1.5 1.5 1.5-.68 1.5-1.5-.67-1.5-1.5-1.5zM7 19h14v-2H7v2zm0-6h14v-2H7v2zm0-8v2h14V5H7z\"></path>\r\n						<path d=\"M0 0h24v24H0V0z\" fill=\"none\"></path></svg></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z\"></path>\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z\"></path>\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n			<div class=\"draftJsToolbar__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n				<button class=\"draftJsToolbar__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: 21px; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n					<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n						<path d=\"M0 0h24v24H0z\" fill=\"none\"></path>\r\n						<path d=\"M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z\"></path></svg></button></div></div></div>\r\n	<div class=\"draftJsEmojiPlugin__alignmentTool__2mkQr\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); left: 330px; transform: translate(-50%) scale(0); position: absolute; border: 1px solid rgb(221, 221, 221); background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border-radius: 2px; box-shadow: rgb(220, 220, 220) 0px 1px 3px 0px; z-index: 2;\">\r\n		<div class=\"draftJsEmojiPlugin__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n			<button class=\"draftJsEmojiPlugin__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n				<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n					<path d=\"M3,21 L21,21 L21,19 L3,19 L3,21 Z M3,3 L3,5 L21,5 L21,3 L3,3 Z M3,7 L3,17 L17,17 L17,7 L3,7 Z\"></path>\r\n					<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n		<div class=\"draftJsEmojiPlugin__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n			<button class=\"draftJsEmojiPlugin__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n				<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n					<path d=\"M21,15 L15,15 L15,17 L21,17 L21,15 Z M21,7 L15,7 L15,9 L21,9 L21,7 Z M15,13 L21,13 L21,11 L15,11 L15,13 Z M3,21 L21,21 L21,19 L3,19 L3,21 Z M3,3 L3,5 L21,5 L21,3 L3,3 Z M3,7 L3,17 L13,17 L13,7 L3,7 Z\"></path>\r\n					<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n		<div class=\"draftJsEmojiPlugin__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n			<button class=\"draftJsEmojiPlugin__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n				<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n					<path d=\"M3,21 L21,21 L21,19 L3,19 L3,21 Z M3,3 L3,5 L21,5 L21,3 L3,3 Z M5,7 L5,17 L19,17 L19,7 L5,7 Z\"></path>\r\n					<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div>\r\n		<div class=\"draftJsEmojiPlugin__buttonWrapper__1Dmqh\" style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); display: inline-block;\">\r\n			<button class=\"draftJsEmojiPlugin__button__qi1gf\" type=\"button\" style=\"-webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(136, 136, 136); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 18px; line-height: inherit; font-family: inherit; margin: 0px; overflow: visible; cursor: pointer; background: rgb(251, 251, 251); border-width: 0px; border-style: initial; border-color: initial; padding-top: 5px; vertical-align: bottom; height: 34px; width: 36px;\">\r\n				<svg height=\"24\" viewbox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\">\r\n					<path d=\"M9,15 L3,15 L3,17 L9,17 L9,15 Z M9,7 L3,7 L3,9 L9,9 L9,7 Z M3,13 L9,13 L9,11 L3,11 L3,13 Z M3,21 L21,21 L21,19 L3,19 L3,21 Z M3,3 L3,5 L21,5 L21,3 L3,3 Z M11,7 L11,17 L21,17 L21,7 L11,7 Z\"></path>\r\n					<path d=\"M0 0h24v24H0z\" fill=\"none\"></path></svg></button></div></div></div>\r\n<div style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(79, 84, 87); font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 14.25px;=\"\" letter-spacing:=\"\" -0.448px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">December 26, 2018</div>\r\n<div style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(79, 84, 87); font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 14.25px;=\"\" letter-spacing:=\"\" -0.448px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">&nbsp;</div>\r\n<div style=\"box-sizing: border-box; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(79, 84, 87); font-family: \" open=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 14.25px;=\"\" letter-spacing:=\"\" -0.448px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"><br />\r\n	</div>');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_to_category`
--

DROP TABLE IF EXISTS `tbl_blog_post_to_category`;
CREATE TABLE `tbl_blog_post_to_category` (
  `ptc_bpcategory_id` int(11) NOT NULL,
  `ptc_post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_blog_post_to_category`
--

INSERT INTO `tbl_blog_post_to_category` (`ptc_bpcategory_id`, `ptc_post_id`) VALUES
(4, 1),
(4, 2),
(4, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_settings`
--

DROP TABLE IF EXISTS `tbl_commission_settings`;
CREATE TABLE `tbl_commission_settings` (
  `commsetting_id` int(11) NOT NULL,
  `commsetting_user_id` int(11) NOT NULL,
  `commsetting_fees` decimal(10,2) NOT NULL COMMENT 'in %',
  `commsetting_is_mandatory` tinyint(1) NOT NULL,
  `commsetting_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_commission_settings`
--

INSERT INTO `tbl_commission_settings` (`commsetting_id`, `commsetting_user_id`, `commsetting_fees`, `commsetting_is_mandatory`, `commsetting_deleted`) VALUES
(1, 17, '200.00', 0, 1),
(3, 58, '50.00', 0, 0),
(4, 55, '90.00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_setting_history`
--

DROP TABLE IF EXISTS `tbl_commission_setting_history`;
CREATE TABLE `tbl_commission_setting_history` (
  `csh_id` int(11) NOT NULL,
  `csh_commsetting_id` int(11) NOT NULL,
  `csh_commsetting_user_id` int(11) NOT NULL,
  `csh_commsetting_fees` decimal(10,2) NOT NULL COMMENT 'in %',
  `csh_commsetting_is_mandatory` tinyint(1) NOT NULL,
  `csh_commsetting_deleted` tinyint(1) NOT NULL,
  `csh_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_commission_setting_history`
--

INSERT INTO `tbl_commission_setting_history` (`csh_id`, `csh_commsetting_id`, `csh_commsetting_user_id`, `csh_commsetting_fees`, `csh_commsetting_is_mandatory`, `csh_commsetting_deleted`, `csh_added_on`) VALUES
(1, 1, 17, '55.00', 0, 0, '2019-04-08 19:04:36'),
(2, 1, 17, '200.00', 0, 0, '2019-04-08 19:04:47'),
(3, 3, 58, '50.00', 0, 0, '2019-05-03 15:14:38'),
(4, 4, 55, '50.00', 0, 0, '2019-05-03 15:17:37'),
(5, 4, 55, '90.00', 0, 0, '2019-05-04 15:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_configurations`
--

DROP TABLE IF EXISTS `tbl_configurations`;
CREATE TABLE `tbl_configurations` (
  `conf_name` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `conf_val` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `conf_common` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_configurations`
--

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
('admin_logo', '', 0),
('apple_touch_icon', '', 0),
('CONF_ABOUT_US_PAGE', '1', 0),
('conf_activate_separate_signup_form', '1', 0),
('CONF_ADDITIONAL_ALERT_EMAILS', '', 0),
('CONF_ADDRESS_1', '1123 SE 6th St.\r\nBattle Ground, Washington\r\n98604', 0),
('CONF_ADDRESS_2', '', 0),
('CONF_ADMIN_APPROVAL_REGISTRATION', '1', 0),
('conf_admin_approval_supplier_registration', '1', 0),
('conf_admin_default_lang', '1', 0),
('CONF_ADMIN_PAGESIZE', '10', 0),
('CONF_ALLOW_REVIEWS', '1', 0),
('CONF_ALLOW_TEACHER_END_LESSON', '5', 0),
('conf_analytics_access_token', '', 0),
('CONF_ANALYTICS_CLIENT_ID', '', 0),
('CONF_ANALYTICS_ID', '', 0),
('CONF_ANALYTICS_SECRET_KEY', '', 0),
('conf_auto_close_system_messages', '0', 0),
('CONF_AUTO_LOGIN_REGISTRATION', '1', 0),
('CONF_AWEBER_SIGNUP_CODE', '', 0),
('CONF_COMET_CHAT_API_KEY', '50978xedfcf997f213f06970117bcaf0ef3301', 0),
('CONF_COMET_CHAT_APP_ID', '50978', 0),
('CONF_COMMISSION_INCLUDING_TAX', '0', 0),
('CONF_CONTACT_EMAIL', 'weyakyak@dummyid.com', 0),
('CONF_COOKIES_BUTTON_LINK', '', 0),
('CONF_COOKIES_TEXT_1', '', 0),
('CONF_COOKIES_TEXT_2', '', 0),
('CONF_COUNTRY', '6', 0),
('CONF_CURRENCY', '1', 0),
('CONF_DATEPICKER_FORMAT', 'm-d-Y', 0),
('CONF_DATEPICKER_FORMAT_TIME', 'H:i', 0),
('CONF_DATE_FORMAT', 'Y-m-d', 1),
('CONF_DATE_FORMAT_TIME', 'H:i', 1),
('CONF_DEFAULT_ORDER_STATUS', '1', 0),
('CONF_DEFAULT_PAID_ORDER_STATUS', '2', 0),
('CONF_DEFAULT_REVIEW_STATUS', '0', 0),
('CONF_DEFAULT_SITE_LANG', '1', 0),
('CONF_EMAIL_VERIFICATION_REGISTRATION', '1', 0),
('CONF_ENABLE_COOKIES', '1', 0),
('conf_enable_import_export', '0', 0),
('CONF_ENABLE_NEWSLETTER_SUBSCRIPTION', '', 0),
('conf_enable_referrer_module', '1', 0),
('CONF_FACEBOOK_APP_ID', '336124357013860', 0),
('CONF_FACEBOOK_APP_SECRET', '7e7add1e8128b80618b9e117111e5f9f', 0),
('CONF_FROM_EMAIL', 'info@weyakyak.com', 0),
('CONF_FROM_NAME_1', 'WeYakYak', 0),
('CONF_FRONTEND_PAGESIZE', '10', 0),
('CONF_GOOGLEMAP_API_KEY', '', 0),
('CONF_GOOGLEPLUS_CLIENT_ID', '803900613139-gca3mvhv2aij7tem3ntar2qrg9ljneqk.apps.googleusercontent.com', 0),
('CONF_GOOGLEPLUS_CLIENT_SECRET', 'hQTtCl7up23ElU_NBKpHfdIm', 0),
('CONF_GOOGLEPLUS_DEVELOPER_KEY', 'AIzaSyAjNeAgqOYphK-1pJI8dNXulOXwX_inM1E', 0),
('CONF_GOOGLE_PUSH_NOTIFICATION_API_KEY', '', 0),
('conf_items_per_page_catalog', '10', 0),
('CONF_LEARNER_REFUND_PERCENTAGE', '50', 0),
('CONF_MAILCHIMP_KEY', '', 0),
('CONF_MAILCHIMP_LIST_ID', '', 0),
('CONF_MAINTENANCE', '0', 0),
('CONF_MAX_COMMISSION', '15', 0),
('conf_max_teacher_request_attempt', '3', 0),
('CONF_MIN_INTERVAL_WITHDRAW_REQUESTS', '7', 0),
('CONF_MIN_WITHDRAW_LIMIT', '25', 0),
('CONF_NEWSLETTER_SYSTEM', '', 0),
('conf_notify_admin_registration', '1', 0),
('conf_page_size', '10', 0),
('conf_paid_lesson_duration', '60', 0),
('conf_ppc_slides_home_page', '4', 0),
('CONF_PRIVACY_POLICY_PAGE', '3', 0),
('CONF_RECAPTCHA_SECRETKEY', '6Le7uGYUAAAAAHBxngn6XcUkA7bLL6RMCIV5Yz4S', 0),
('CONF_RECAPTCHA_SITEKEY', '6Le7uGYUAAAAALujv-as7XyNiKoDDpUYs0IYvXYt', 0),
('CONF_REPLY_TO_EMAIL', 'info@weyakyak.com', 0),
('CONF_REVIEW_ALERT_EMAIL', '1', 0),
('CONF_SEND_EMAIL', '1', 0),
('CONF_SEND_SMTP_EMAIL', '0', 0),
('CONF_SITE_FAX', '', 0),
('CONF_SITE_OWNER_1', 'Kelly Carroll', 0),
('CONF_SITE_OWNER_2', '', 0),
('CONF_SITE_OWNER_EMAIL', 'info@weyakyak.com', 0),
('CONF_SITE_PHONE', '+ 9239939239923', 0),
('CONF_SITE_TRACKER_CODE', '', 0),
('CONF_SMTP_HOST', '', 0),
('CONF_SMTP_PASSWORD', 'welcome', 0),
('CONF_SMTP_PORT', '', 0),
('CONF_SMTP_SECURE', '', 0),
('CONF_SMTP_USERNAME', 'welcome', 0),
('CONF_TEACHER_NO_OF_LESSON', '1,3,5,10,15,20,25', 0),
('CONF_TERMS_AND_CONDITIONS_PAGE', '2', 0),
('CONF_TIMEZONE', 'Asia/Kolkata', 0),
('conf_time_auto_close_system_messages', '3', 0),
('conf_total_slides_home_page', '4', 0),
('conf_transaction_mode', '', 0),
('conf_trial_lesson_duration', '30', 0),
('CONF_TWITTER_API_KEY', '', 0),
('CONF_TWITTER_API_SECRET', '', 0),
('CONF_TWITTER_USERNAME', 'https://twitter.com/we_yak', 0),
('CONF_USE_SSL', '0', 0),
('CONF_WEBSITE_NAME_1', 'WeYakYak', 0),
('CONF_WEBSITE_NAME_2', '', 0),
('CONF_WELCOME_EMAIL_REGISTRATION', '1', 0),
('conf_yokart_version', 'V8.1', 0),
('email_logo', '', 0),
('favicon', '', 0),
('front_logo', '', 0),
('front_white_logo', '', 0),
('mobile_logo', '', 0),
('payment_page_logo', '', 0),
('social_feed_image', '', 0),
('watermark', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages`
--

DROP TABLE IF EXISTS `tbl_content_pages`;
CREATE TABLE `tbl_content_pages` (
  `cpage_id` int(11) NOT NULL,
  `cpage_identifier` varchar(255) NOT NULL,
  `cpage_layout` tinyint(4) NOT NULL,
  `cpage_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_content_pages`
--

INSERT INTO `tbl_content_pages` (`cpage_id`, `cpage_identifier`, `cpage_layout`, `cpage_deleted`) VALUES
(1, 'About Us1', 1, 0),
(2, 'Terms & Conditions', 2, 0),
(3, 'Privacy Policy', 2, 0),
(4, 'test page', 1, 0),
(5, 'Contact US', 2, 0),
(6, 'Help page', 2, 0),
(7, 'Apply Teach', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages_block_lang`
--

DROP TABLE IF EXISTS `tbl_content_pages_block_lang`;
CREATE TABLE `tbl_content_pages_block_lang` (
  `cpblocklang_id` int(11) NOT NULL,
  `cpblocklang_lang_id` int(11) NOT NULL,
  `cpblocklang_cpage_id` int(11) NOT NULL,
  `cpblocklang_block_id` int(11) NOT NULL,
  `cpblocklang_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_content_pages_block_lang`
--

INSERT INTO `tbl_content_pages_block_lang` (`cpblocklang_id`, `cpblocklang_lang_id`, `cpblocklang_cpage_id`, `cpblocklang_block_id`, `cpblocklang_text`) VALUES
(1, 1, 1, 1, '<section class=\"section section--white section--centered\">             \r\n	<div class=\"container container--narrow container--cms\">                 \r\n                 \r\n		<div class=\"section__body\" style=\"\">                     \r\n			<div class=\"row justify-content-center -align-center\" style=\"\">                         \r\n				<div class=\"col-xl-10 col-lg-12 col-md-12\" style=\"\">                             \r\n					<div class=\"row\" style=\"\">                                 \r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\" style=\"\">                                     \r\n							<div class=\"icon\" style=\"font-size: 16px; text-align: left;\"><img src=\"/public/images/icon_mission.svg\" alt=\"\" /></div>                                     \r\n							<h4 style=\"text-align: left;\"><span style=\"font-size: 21px;\">The Mission</span></h4>\r\n							<div style=\"font-size: 16px;\"><span gloria=\"\" hallelujah\";\"=\"\"><br />\r\n									</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\">For I (weyakyak) have a great sense of obligation to people in both the civilized world and the rest of the world, to the educated and the uneducated alike.</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\">Romans 1:14</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\">&nbsp;</span></div>                                     \r\n							<p style=\"font-size: 16px; text-align: left;\"><span style=\"font-family: Alike; font-size: 16px;\">WeYakYak’s mission is to help people all over the world to connect, communicat and learn in the language of their choice.&nbsp; Our goal is to share cultures and provide online language learning that is fun, engaging and to equip students with the skills to speak clearly and confidently in their chosen langauge of study.&nbsp; Language changes many things for each student and our mission is to ensure quality learning and retention to expand learning knowledge for people. Education changes whole communities and builds the future of our next generation...We want to be a part of that change in a world of languages.</span></p>\r\n							<p style=\"font-size: 16px; text-align: left;\"><br />\r\n								</p>                                 </div>                                 \r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\" style=\"\">                                     \r\n							<div class=\"icon\" style=\"font-size: 16px; text-align: left;\"><img src=\"/public/images/icon_vision.svg\" alt=\"\" /></div>                                     \r\n							<h4 style=\"text-align: left;\"><span style=\"font-size: 21px;\">The Vision</span></h4>\r\n							<div><br />\r\n								</div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\"><span helvetica=\"\" neue\",=\"\" verdana,=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 16px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\"></span></span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\"><span helvetica=\"\" neue\",=\"\" verdana,=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 16px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">Where&nbsp;</span><i style=\"box-sizing: border-box;\" helvetica=\"\" neue\",=\"\" verdana,=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 16px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">there is</i><span helvetica=\"\" neue\",=\"\" verdana,=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 16px;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">&nbsp;no vision, the people perish;</span></span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\">Proverbs 29:18</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span gloria=\"\" hallelujah\";\"=\"\">&nbsp;</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span style=\"font-size: 16px; font-family: \" gloria=\"\" hallelujah\";\"=\"\">Write the vision and make <span style=\"font-style: italic; font-size: 16px;\">it </span>plain on tablets, That he may run who reads it.&nbsp; For the vision <span style=\"font-style: italic; font-size: 16px;\">is</span> yet for an appointed time; But at the end it will speak, and it will not lie.&nbsp; Though it tarries, wait for it; Because it will surely come, It will not tarry.</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span style=\"font-size: 16px; font-family: \" gloria=\"\" hallelujah\";\"=\"\">Habakkuk 2:2</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span style=\"font-family: Alike; font-size: 16px;\">&nbsp;</span></div>\r\n							<div style=\"font-size: 16px; text-align: left;\"><span style=\"font-family: Alike; font-size: 16px;\">The vision came to the Founder and CEO Kelly C. while living in Ecuador.&nbsp; Kelly taught English online and volunteered to help young learners in a Village called Vilcabamba.&nbsp; On February 17th of 2018 Kelly had a vision of creating a platform for teachers and students to offer the freedom to teach and learn online, help teachers to earn money from home and create a safer online global learning community.&nbsp; After two years experiencing South America, it became evident that Weyakyak needs to reach out globally and help those people who are struggling to live in a world that is so demanding.&nbsp; Our mission and vision is to give back to the world in need, help the poor and feed the hungry.&nbsp; Every dollar brought to the platform will be a precious tool to help feed, clothe, house and help families.&nbsp; Teaching and learning with Weyakyak is more than education; It\'s global change for people: Help us be that CHANGE!</span></div>                                     \r\n							<p style=\"font-size: 16px; text-align: left;\"><span style=\"font-family: Alike; font-size: 16px;\">&nbsp;</span></p>                                     \r\n                                 </div>                            </div>                          </div>                     </div>                     \r\n                    <span style=\"font-size: 16px; font-family: Alike;\"><span class=\"-gap\" style=\"font-size: 16px;\"></span><span class=\"-gap\" style=\"font-size: 16px;\"></span><span class=\"-gap\" style=\"font-size: 16px;\"></span>                     </span>\r\n			<div class=\"-align-center section__head\" style=\"\">                         \r\n				<h2 style=\"text-align: left;\"><span style=\"font-size: 21px;\">TEAM&nbsp; WeYakYak</span></h2>                     </div>                     \r\n			<div class=\"row\" style=\"font-size: 16px;\">                         \r\n				<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                             \r\n					<p style=\"text-align: left;\"><br />\r\n						</p></div></div></div></div></section>\r\n<section class=\"section section--grey -align-center\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"section__body\">\r\n			<div class=\"row justify-content-center\">\r\n				<div class=\"col-xl-9 col-lg-12\">\r\n					<div class=\"row\">\r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<h4><br />\r\n								</h4>                                     \r\n							<p><br />\r\n								</p>                                 </div>                             </div>                            \r\n                         </div>                     </div>     \r\n                 </div>                 \r\n             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--white section--hiw\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"section__head -align-center\">                     \r\n			<h2><span style=\"font-size: 24px;\">How Weyakyak works</span></h2>                 </div>                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-between\">                         \r\n				<div class=\"col-lg-4\">                             \r\n					<div class=\"tabs-vertical tabs-js\">                                 \r\n						<ul>                                     \r\n							<li>                                         <a href=\"#tab1\">                                             \r\n									<h3>Browse</h3>                                             \r\n									<p>Browse thousands of teacher<br />\r\n										 profiles and bios.</p>                                         </a>                                     </li>                                     \r\n							<li>                                         <a href=\"#tab2\">                                             \r\n									<h3>Book</h3>                                             \r\n									<p>Book lessons with the best teachers for your<br />\r\n										 schedule, budget, goals, and learning style.</p>                                         </a>                                     </li>                                     \r\n							<li>                                         <a href=\"#tab3\">                                             \r\n									<h3>Start</h3>                                             \r\n									<p>Log in to Weyakyak Video and<br />\r\n										 start talking.</p>                                         </a>                                     </li>                                 \r\n						</ul>                             </div>                         </div>                         \r\n				<div class=\"col-lg-7 col__content\">                             \r\n					<div id=\"tab1\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_01.png\" alt=\"\" /></div>                             </div>                             \r\n					<div id=\"tab2\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_02.png\" alt=\"\" /></div>                             </div>                             \r\n					<div id=\"tab3\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_03.png\" alt=\"\" /></div>                             </div>                             \r\n                         </div>                     </div>                 </div>             </div>         </section>\r\n<section class=\"section section--grey\">             \r\n	<div class=\"container container--narrow -align-center\">                 \r\n		<h2 class=\"-style-bold\">Looking forward to meeting<br />\r\n			  your new students?</h2>                 <span class=\"-gap\"></span>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>'),
(2, 1, 1, 2, ''),
(3, 1, 1, 3, ''),
(4, 1, 1, 4, ''),
(5, 1, 1, 5, ''),
(11, 2, 1, 1, ''),
(12, 2, 1, 2, ''),
(13, 2, 1, 3, ''),
(14, 2, 1, 4, ''),
(15, 2, 1, 5, ''),
(36, 1, 4, 1, ''),
(37, 1, 4, 2, ''),
(38, 1, 4, 3, ''),
(39, 1, 4, 4, ''),
(40, 1, 4, 5, ''),
(41, 2, 4, 1, ''),
(42, 2, 4, 2, ''),
(43, 2, 4, 3, ''),
(44, 2, 4, 4, ''),
(45, 2, 4, 5, ''),
(48, 1, 6, 1, '<div><strong open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong><span open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></div>\r\n<div>&nbsp;</div>\r\n<div><strong open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\" style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong><span open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" text-align:=\"\" justify;=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);\"=\"\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></div>'),
(57, 1, 7, 1, '<section class=\"section section--white section--icons\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"-align-center section__head\">\r\n			<h2>Why Teach on Weyakyak?</h2></div>\r\n		<div class=\"section__body\">\r\n			<div class=\"row justify-content-center\">\r\n				<div class=\"col-xl-9 col-lg-12 col-md-12\">\r\n					<div class=\"row\">\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_1.svg\" alt=\"\" /></div>\r\n							<h4>Earn money</h4>\r\n							<p>Set your own hourly rates and cash out your earnings anytime.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_2.svg\" alt=\"\" /></div>\r\n							<h4>Work anywhere</h4>\r\n							<p>Teach from home or any other convenient location of your choice.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_3.svg\" alt=\"\" /></div>\r\n							<h4>Teach anytime</h4>\r\n							<p>Adjust your personal availability anytime on your calendar.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_3.svg\" alt=\"\" /></div>\r\n							<h4>Safety and security</h4>\r\n							<p>Ensures that you get paid after you teach!</p>\r\n							<p>&nbsp;</p>\r\n							<p><br />\r\n								</p></div></div></div></div></div></div></section>'),
(58, 1, 7, 2, '<section class=\"section section--grey\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"-align-center section__head\">\r\n			<h2>Frequently Asked Questions</h2></div>\r\n		<div class=\"section__body\">\r\n			<div class=\"row justify-content-center\">\r\n				<div class=\"col-xl-9 col-lg-9\">\r\n					<div class=\"accordian-group\">\r\n						<div class=\"accordian accordian-js is-active\">\r\n							<div class=\"accordian__title accordian__title-js\">What is We Yak Yak?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: block;\">\r\n								<p>We Yak Yak is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p></div></div>\r\n						<div class=\"accordian accordian-js\">\r\n							<div class=\"accordian__title accordian__title-js\">Where do I teach?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: none;\">\r\n								<p>You may teach from any location world wide, with high speed internet, a laptop and clear sounding headset.</p></div></div>\r\n						<div class=\"accordian accordian-js\">\r\n							<div class=\"accordian__title accordian__title-js\">How do I apply to teach on WeYakYak?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: none;\">\r\n								<p>Click the link to apply and complete the application.  Upon completion you will be contacted when your profile is accepted.  If you do not hear from us within two weeks, feel free to email us at info@weyakyak.com</p></div></div>\r\n						<div class=\"accordian accordian-js\">\r\n							<div class=\"accordian__title accordian__title-js\">Do I need teaching experience?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: none;\">\r\n								<p>Yes and no... If you are educated with a college degree in any area we will consider your application.  No need to be a expert teacher, but also we do not encourage a novice teacher, individuals looking for hobbies or just something to do.  We want passionate people willing to teach their native language as a foreign language to students around the world.  We encourage teachers to have degree\'s and or certified to teach their native spoken langauge as a foreign language.</p>\r\n								<p>If you do not have a college degree we will accept langauge ESL,TEFL and TESOL certifications.  Foreign languages other than English may submit their language certifications and college degree\'s. </p>\r\n								<p>We want to offer the opportunity for you to teach your language so please apply.  We will will vet each applicant to ensure that we have quality educated Teachers and Tutors.</p></div></div></div></div></div></div></div></section>');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages_lang`
--

DROP TABLE IF EXISTS `tbl_content_pages_lang`;
CREATE TABLE `tbl_content_pages_lang` (
  `cpagelang_cpage_id` int(11) NOT NULL,
  `cpagelang_lang_id` int(11) NOT NULL,
  `cpage_title` varchar(255) NOT NULL,
  `cpage_content` text NOT NULL,
  `cpage_image_title` varchar(255) NOT NULL,
  `cpage_image_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_content_pages_lang`
--

INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(1, 1, 'About Us', '<section class=\"banner banner--main\">             \r\n  \r\n	<div class=\"banner__media\"><img src=\"/public/images/2000x900_5.jpg\" alt=\"\" /></div>             \r\n	<div class=\"banner__content banner__content--centered\">                 \r\n		<h1>About WeYakYak</h1>                 \r\n		<p>What is WeYakYak? The best place to find an Online Language Teahcer</p>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--white section--centered\">             \r\n	<div class=\"container container--narrow container--cms\">                 \r\n                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-center -align-center\">                         \r\n				<div class=\"col-xl-10 col-lg-12 col-md-12\">                             \r\n					<div class=\"row\">                                 \r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                                     \r\n							<div class=\"icon\"><img src=\"/public/images/icon_mission.svg\" alt=\"\" /></div>                                     \r\n							<h4>The Mission</h4>                                     \r\n							<p>WeYakYak’s mission is to empower people all over the world to \r\nbecome fluent in a foreign language. While our core team is based \r\nin San Francisco, the teachers and students who make our \r\nmission possible are  spread across six continents.</p>                                 </div>                                 \r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                                     \r\n							<div class=\"icon\"><img src=\"/public/images/icon_vision.svg\" alt=\"\" /></div>                                     \r\n							<h4>The Vision</h4>                                     \r\n							<p>Adipisicing elit, sed do eiusmod tempor incididunt ut labore \r\net dolore magna aliqua. Ut enim ad minim veniam, quis nostrud \r\nexercitation ullamco laboris nisi ut aliquip ex ea \r\ncommodo consequat. </p>                                     \r\n                                 </div>                            </div>                          </div>                     </div>                     \r\n                    <span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span>                     \r\n			<div class=\"-align-center section__head\">                         \r\n				<h2>The WeYakYak</h2>                     </div>                     \r\n			<div class=\"row\">                         \r\n				<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                             \r\n					<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br />\r\n						<br />\r\n						Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam labore et dolore magnam aliquam quaerat voluptatem.<br />\r\n						<br />\r\n						Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>                         </div>                         \r\n				<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                             \r\n					<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>                             \r\n                             \r\n					<ul>                                 \r\n						<li>Eeaque ipsa quae ab illo inventore veritatis et.</li>                                 \r\n						<li>Quasi architecto beatae vitae dicta sunt explicabo.  </li>                                 \r\n						<li>Eeaque ipsa quae ab illo inventore veritatis et. </li>                                 \r\n						<li> Quasi architecto beatae vitae dicta sunt explicabo. </li>                                 \r\n						<li>Eeaque ipsa quae ab illo inventore veritatis et. </li>                             \r\n					</ul>                             \r\n                         </div>                    </div>                     \r\n                   \r\n                 </div>                 \r\n             </div>         </section>         \r\n      \r\n         \r\n         \r\n         \r\n<section class=\"section section--grey -align-center\">             \r\n	<div class=\"container container--narrow\">                \r\n                 \r\n		<div class=\"section__head\">                     \r\n			<h2>The Team</h2>                 </div>                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-center\">                         \r\n				<div class=\"col-xl-9 col-lg-12\">                            \r\n                             \r\n					<div class=\"row\">                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_2.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Kirstin</h4>                                     \r\n							<p> Customer Executive</p>                                 </div>                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_5.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Cooper</h4>                                     \r\n							<p>Product Design</p>                                 </div>                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_4.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Andrew</h4>                                     \r\n							<p>Marketing</p>                                 </div>                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_3.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Mikael</h4>                                     \r\n							<p>Product Developer</p>                                 </div>                             </div>                            \r\n                         </div>                     </div>     \r\n                 </div>                 \r\n             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--white section--hiw\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"section__head -align-center\">                     \r\n			<h2>How Weyakyak works</h2>                 </div>                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-between\">                         \r\n				<div class=\"col-lg-4\">                             \r\n					<div class=\"tabs-vertical tabs-js\">                                 \r\n						<ul>                                     \r\n							<li>                                         <a href=\"#tab1\">                                             \r\n									<h3>Browse</h3>                                             \r\n									<p>Browse thousands of teacher<br />\r\n										 profiles and bios.</p>                                         </a>                                     </li>                                     \r\n							<li>                                         <a href=\"#tab2\">                                             \r\n									<h3>Book</h3>                                             \r\n									<p>Book lessons with the best teachers for your<br />\r\n										 schedule, budget, goals, and learning style.</p>                                         </a>                                     </li>                                     \r\n							<li>                                         <a href=\"#tab3\">                                             \r\n									<h3>Start</h3>                                             \r\n									<p>Log in to Weyakyak Video and<br />\r\n										 start talking.</p>                                         </a>                                     </li>                                 \r\n						</ul>                             </div>                         </div>                         \r\n				<div class=\"col-lg-7 col__content\">                             \r\n					<div id=\"tab1\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_01.png\" alt=\"\" /></div>                             </div>                             \r\n					<div id=\"tab2\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_02.png\" alt=\"\" /></div>                             </div>                             \r\n					<div id=\"tab3\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_03.png\" alt=\"\" /></div>                             </div>                             \r\n                         </div>                     </div>                 </div>             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--grey\">             \r\n	<div class=\"container container--narrow\">                \r\n                 \r\n                 \r\n		<div class=\"col-xl-8 col-lg-8 col-md-8 address-container\">                                    \r\n			<h3>Contact us</h3>                                    \r\n			<div class=\"address-box\">                                        \r\n				<h6><img src=\"/public/images/icon_contact_1.svg\" alt=\"\" /> Address</h6>                                        \r\n				<p>The Office Group <br />\r\n					91 Wimpole Street <br />\r\n					Marylebone <br />\r\n					London <br />\r\n					W1G 0EF <br />\r\n					United Kingdom</p>                                    </div>                                    \r\n			<div class=\"address-box\">                                        \r\n				<h6><img src=\"/public/images/icon_contact_2.svg\" alt=\"\" /> EMAIL</h6>                                        \r\n				<p><a href=\"mailto:info@fillistudios.com\">info@weyakyak.com</a></p>                                    </div>                                    \r\n			<div class=\"address-box\">                                        \r\n				<h6><img src=\"/public/images/icon_contact_3.svg\" alt=\"\" /> CALL US</h6>                                        \r\n				<p>(+44) 020 7846 0316</p>                                    </div>                                 </div>                            </div>     \r\n                                                                                \r\n                      </section>         \r\n         \r\n         \r\n<section class=\"section section--white\">             \r\n	<div class=\"container container--narrow -align-center\">                 \r\n		<h2 class=\"-style-bold\">Looking forward to meeting<br />\r\n			  your new students?</h2>                 <span class=\"-gap\"></span>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>', 'About Weyakyak', 'Company Overview'),
(1, 2, 'ghjhg', '<div><br />\r\n	</div>\r\n<div>\r\n	<pre class=\"tw-data-text tw-ta tw-text-small\" data-placeholder=\"Translation\" id=\"tw-target-text\" data-fulltext=\"\" dir=\"rtl\" style=\"unicode-bidi: isolate; background-color: rgb(255, 255, 255); border: none; padding: 0px 0.14em 0px 0px; position: relative; margin-top: 0px; margin-bottom: 0px; resize: none; font-family: inherit; overflow: hidden; text-align: right; width: 275px; white-space: pre-wrap; overflow-wrap: break-word; color: rgb(33, 33, 33); height: 120px; font-size: 16px !important; line-height: 24px !important;\"><span tabindex=\"0\" lang=\"ar\">ما هو وياكياك؟ أفضل مكان للعثور على Teahcer اللغة عبر الإنترنت\r\n\r\nابدأ التدريس</span></pre></div>', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(2, 1, 'Terms & Conditions', '<div class=\"container__cms\">\r\n	<p class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size: 14pt; font-family: Times; font-weight: bold;\">Terms and\r\nConditions</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Agreement between\r\nUser and http://www.weyakyak.com</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Welcome to http://www.weyakyak.com. The\r\nhttp://www.weyakyak.com website (the \"Site\") is comprised of various\r\nweb pages operated by WeyakyakLLC (\"Weyakyak\").\r\nhttp://www.weyakyak.com is offered to you conditioned on your acceptance\r\nwithout modification of the terms, conditions, and notices contained herein\r\n(the \"Terms\"). Your use of http://www.weyakyak.com constitutes your\r\nagreement to all such Terms. Please read these terms carefully, and keep a copy\r\nof them for your reference. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">http://www.weyakyak.com is an E-Commerce Site. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak is a teaching platform for teachers and students to\r\nconnect for learning a foreign language. Teachers set their own rates and\r\nschedules and students pay for classes within the site, pick a teacher. Teacher\r\ngets paid weekly for each class completion. Weyakyak pays out 85% of cost to\r\nteachers and keeps 15% to keep the site in operation. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Electronic\r\nCommunications</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Visiting http://www.weyakyak.com or sending emails to\r\nWeyakyak constitutes electronic communications. You consent to receive\r\nelectronic communications and you agree that all agreements, notices,\r\ndisclosures and other communications that we provide to you electronically, via\r\nemail and on the Site, satisfy any legal requirement that such communications\r\nbe in writing. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Your Account</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">If you use this site, you are responsible for maintaining\r\nthe confidentiality of your account and password and for restricting access to\r\nyour computer, and you agree to accept responsibility for all activities that\r\noccur under your account or password. You may not assign or otherwise transfer\r\nyour account to any other person or entity. You acknowledge that Weyakyak is\r\nnot responsible for third party access to your account that results from theft\r\nor misappropriation of your account. Weyakyak and its associates reserve the\r\nright to refuse or cancel service, terminate accounts, or remove or edit\r\ncontent in our sole discretion. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Children Under\r\nThirteen</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak does not knowingly collect, either online or\r\noffline, personal information from persons under the age of thirteen. If you\r\nare under 18, you may use http://www.weyakyak.com only with permission of a\r\nparent or guardian. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Cancellation/Refund\r\nPolicy</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You may cancel your class purchases at any time.\r\nCancelations made after 30 days will receive a 50% refund. All classes\r\npurchased will stay in your weyakyak account for 6 months. After 6 months your\r\nclasses will expire with no refund. Classes may be used with any teacher of\r\nyour choice. You may also cancel your teachers lessons and re-use your purchase\r\nwithin the site with another teacher. For questions please contact\r\ninfo@weyakyak.com \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Links to Third Party\r\nSites/Third Party Services</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">http://www.weyakyak.com may contain links to other websites\r\n(\"Linked Sites\"). The Linked Sites are not under the control of\r\nWeyakyak and Weyakyak is not responsible for the contents of any Linked Site,\r\nincluding without limitation any link contained in a Linked Site, or any\r\nchanges or updates to a Linked Site. Weyakyak is providing these links to you\r\nonly as a convenience, and the inclusion of any link does not imply endorsement\r\nby Weyakyak of the site or any association with its operators. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Certain services made available via http://www.weyakyak.com\r\nare delivered by third party sites and organizations. By using any product,\r\nservice or functionality originating from the http://www.weyakyak.com domain,\r\nyou hereby acknowledge and consent that Weyakyak may share such information and\r\ndata with any third party with whom Weyakyak has a contractual relationship to\r\nprovide the requested product, service or functionality on behalf of\r\nhttp://www.weyakyak.com users and customers. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">No Unlawful or\r\nProhibited Use/Intellectual Property </span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You are granted a non-exclusive, non-transferable, revocable\r\nlicense to access and use http://www.weyakyak.com strictly in accordance with\r\nthese terms of use. As a condition of your use of the Site, you warrant to\r\nWeyakyak that you will not use the Site for any purpose that is unlawful or\r\nprohibited by these Terms. You may not use the Site in any manner which could\r\ndamage, disable, overburden, or impair the Site or interfere with any other\r\nparty\'s use and enjoyment of the Site. You may not obtain or attempt to obtain\r\nany materials or information through any means not intentionally made available\r\nor provided for through the Site. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">All content included as part of the Service, such as text,\r\ngraphics, logos, images, as well as the compilation thereof, and any software\r\nused on the Site, is the property of Weyakyak or its suppliers and protected by\r\ncopyright and other laws that protect intellectual property and proprietary\r\nrights. You agree to observe and abide by all copyright and other proprietary\r\nnotices, legends or other restrictions contained in any such content and will\r\nnot make any changes thereto. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You will not modify, publish, transmit, reverse engineer,\r\nparticipate in the transfer or sale, create derivative works, or in any way\r\nexploit any of the content, in whole or in part, found on the Site. Weyakyak\r\ncontent is not for resale. Your use of the Site does not entitle you to make\r\nany unauthorized use of any protected content, and in particular you will not\r\ndelete or alter any proprietary rights or attribution notices in any content.\r\nYou will use protected content solely for your personal use, and will make no\r\nother use of the content without the express written permission of Weyakyak and\r\nthe copyright owner. You agree that you do not acquire any ownership rights in\r\nany protected content. We do not grant you any licenses, express or implied, to\r\nthe intellectual property of Weyakyak or our licensors except as expressly\r\nauthorized by these Terms. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Use of Communication\r\nServices</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">The Site may contain bulletin board services, chat areas,\r\nnews groups, forums, communities, personal web pages, calendars, and/or other\r\nmessage or communication facilities designed to enable you to communicate with\r\nthe public at large or with a group (collectively, \"Communication Services\").\r\nYou agree to use the Communication Services only to post, send and receive\r\nmessages and material that are proper and related to the particular\r\nCommunication Service. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">By way of example, and not as a limitation, you agree that\r\nwhen using a Communication Service, you will not: defame, abuse, harass, stalk,\r\nthreaten or otherwise violate the legal rights (such as rights of privacy and\r\npublicity) of others; publish, post, upload, distribute or disseminate any\r\ninappropriate, profane, defamatory, infringing, obscene, indecent or unlawful\r\ntopic, name, material or information; upload files that contain software or\r\nother material protected by intellectual property laws (or by rights of privacy\r\nof publicity) unless you own or control the rights thereto or have received all\r\nnecessary consents; upload files that contain viruses, corrupted files, or any\r\nother similar software or programs that may damage the operation of another\'s\r\ncomputer; advertise or offer to sell or buy any goods or services for any\r\nbusiness purpose, unless such Communication Service specifically allows such\r\nmessages; conduct or forward surveys, contests, pyramid schemes or chain\r\nletters; download any file posted by another user of a Communication Service\r\nthat you know, or reasonably should know, cannot be legally distributed in such\r\nmanner; falsify or delete any author attributions, legal or other proper\r\nnotices or proprietary designations or labels of the origin or source of\r\nsoftware or other material contained in a file that is uploaded; restrict or\r\ninhibit any other user from using and enjoying the Communication Services;\r\nviolate any code of conduct or other guidelines which may be applicable for any\r\nparticular Communication Service; harvest or otherwise collect information\r\nabout others, including e-mail addresses, without their consent; violate any\r\napplicable laws or regulations. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak has no obligation to monitor the Communication\r\nServices. However, Weyakyak reserves the right to review materials posted to a\r\nCommunication Service and to remove any materials in its sole discretion.\r\nWeyakyak reserves the right to terminate your access to any or all of the\r\nCommunication Services at any time without notice for any reason whatsoever. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak reserves the right at all times to disclose any\r\ninformation as necessary to satisfy any applicable law, regulation, legal\r\nprocess or governmental request, or to edit, refuse to post or to remove any\r\ninformation or materials, in whole or in part, in Weyakyak\'s sole discretion. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Always use caution when giving out any personally\r\nidentifying information about yourself or your children in any Communication\r\nService. Weyakyak does not control or endorse the content, messages or\r\ninformation found in any Communication Service and, therefore, Weyakyak specifically\r\ndisclaims any liability with regard to the Communication Services and any\r\nactions resulting from your participation in any Communication Service.\r\nManagers and hosts are not authorized Weyakyak spokespersons, and their views\r\ndo not necessarily reflect those of Weyakyak. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Materials uploaded to a Communication Service may be subject\r\nto posted limitations on usage, reproduction and/or dissemination. You are\r\nresponsible for adhering to such limitations if you upload the materials. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Materials Provided to\r\nhttp://www.weyakyak.com or Posted on Any Weyakyak Web Page</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak does not claim ownership of the materials you\r\nprovide to http://www.weyakyak.com (including feedback and suggestions) or\r\npost, upload, input or submit to any Weyakyak Site or our associated services\r\n(collectively \"Submissions\"). However, by posting, uploading,\r\ninputting, providing or submitting your Submission you are granting Weyakyak,\r\nour affiliated companies and necessary sublicensees permission to use your\r\nSubmission in connection with the operation of their Internet businesses\r\nincluding, without limitation, the rights to: copy, distribute, transmit,\r\npublicly display, publicly perform, reproduce, edit, translate and reformat\r\nyour Submission; and to publish your name in connection with your Submission. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">No compensation will be paid with respect to the use of your\r\nSubmission, as provided herein. Weyakyak is under no obligation to post or use\r\nany Submission you may provide and may remove any Submission at any time in\r\nWeyakyak\'s sole discretion. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">By posting, uploading, inputting, providing or submitting\r\nyour Submission you warrant and represent that you own or otherwise control all\r\nof the rights to your Submission as described in this section including,\r\nwithout limitation, all the rights necessary for you to provide, post, upload,\r\ninput or submit the Submissions. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Third Party Accounts</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You will be able to connect your Weyakyak account to third\r\nparty accounts. By connecting your Weyakyak account to your third party\r\naccount, you acknowledge and agree that you are consenting to the continuous\r\nrelease of information about you to others (in accordance with your privacy\r\nsettings on those third party sites). If you do not want information about you\r\nto be shared in this manner, do not use this feature. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">International Users</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">The Service is controlled, operated and administered by\r\nWeyakyak from our offices within the USA. If you access the Service from a\r\nlocation outside the USA, you are responsible for compliance with all local\r\nlaws. You agree that you will not use the Weyakyak Content accessed through\r\nhttp://www.weyakyak.com in any country or in any manner prohibited by any\r\napplicable laws, restrictions or regulations. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Indemnification</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You agree to indemnify, defend and hold harmless Weyakyak,\r\nits officers, directors, employees, agents and third parties, for any losses,\r\ncosts, liabilities and expenses (including reasonable attorney\'s fees) relating\r\nto or arising out of your use of or inability to use the Site or services, any\r\nuser postings made by you, your violation of any terms of this Agreement or\r\nyour violation of any rights of a third party, or your violation of any\r\napplicable laws, rules or regulations. Weyakyak reserves the right, at its own\r\ncost, to assume the exclusive defense and control of any matter otherwise\r\nsubject to indemnification by you, in which event you will fully cooperate with\r\nWeyakyak in asserting any available defenses. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Arbitration</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">In the event the parties are not able to resolve any dispute\r\nbetween them arising out of or concerning these Terms and Conditions, or any\r\nprovisions hereof, whether in contract, tort, or otherwise at law or in equity\r\nfor damages or any other relief, then such dispute shall be resolved only by\r\nfinal and binding arbitration pursuant to the Federal Arbitration Act,\r\nconducted by a single neutral arbitrator and administered by the American\r\nArbitration Association, or a similar arbitration service selected by the\r\nparties, in a location mutually agreed upon by the parties. The arbitrator\'s\r\naward shall be final, and judgment may be entered upon it in any court having\r\njurisdiction. In the event that any legal or equitable action, proceeding or\r\narbitration arises out of or concerns these Terms and Conditions, the\r\nprevailing party shall be entitled to recover its costs and reasonable\r\nattorney\'s fees. The parties agree to arbitrate all disputes and claims in\r\nregards to these Terms and Conditions or any disputes arising as a result of\r\nthese Terms and Conditions, whether directly or indirectly, including Tort\r\nclaims that are a result of these Terms and Conditions. The parties agree that\r\nthe Federal Arbitration Act governs the interpretation and enforcement of this\r\nprovision. The entire dispute, including the scope and enforceability of this\r\narbitration provision shall be determined by the Arbitrator. This arbitration\r\nprovision shall survive the termination of these Terms and Conditions. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Class Action Waiver</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Any arbitration under these Terms and Conditions will take\r\nplace on an individual basis; class arbitrations and\r\nclass/representative/collective actions are not permitted. THE PARTIES AGREE\r\nTHAT A PARTY MAY BRING CLAIMS AGAINST THE OTHER ONLY IN EACH\'S INDIVIDUAL\r\nCAPACITY, AND NOT AS A PLAINTIFF OR CLASS MEMBER IN ANY PUTATIVE CLASS,\r\nCOLLECTIVE AND/ OR REPRESENTATIVE PROCEEDING, SUCH AS IN THE FORM OF A PRIVATE\r\nATTORNEY GENERAL ACTION AGAINST THE OTHER. Further, unless both you and\r\nWeyakyak agree otherwise, the arbitrator may not consolidate more than one\r\nperson\'s claims, and may not otherwise preside over any form of a\r\nrepresentative or class proceeding. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Liability Disclaimer</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">THE INFORMATION, SOFTWARE, PRODUCTS, AND SERVICES INCLUDED\r\nIN OR AVAILABLE THROUGH THE SITE MAY INCLUDE INACCURACIES OR TYPOGRAPHICAL\r\nERRORS. CHANGES ARE PERIODICALLY ADDED TO THE INFORMATION HEREIN. WEYAKYAKLLC\r\nAND/OR ITS SUPPLIERS MAY MAKE IMPROVEMENTS AND/OR CHANGES IN THE SITE AT ANY\r\nTIME. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">WEYAKYAKLLC AND/OR ITS SUPPLIERS MAKE NO REPRESENTATIONS\r\nABOUT THE SUITABILITY, RELIABILITY, AVAILABILITY, TIMELINESS, AND ACCURACY OF\r\nTHE INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED GRAPHICS CONTAINED ON\r\nTHE SITE FOR ANY PURPOSE. TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW,\r\nALL SUCH INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED GRAPHICS ARE\r\nPROVIDED \"AS IS\" WITHOUT WARRANTY OR CONDITION OF ANY KIND.\r\nWEYAKYAKLLC AND/OR ITS SUPPLIERS HEREBY DISCLAIM ALL WARRANTIES AND CONDITIONS\r\nWITH REGARD TO THIS INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED\r\nGRAPHICS, INCLUDING ALL IMPLIED WARRANTIES OR CONDITIONS OF MERCHANTABILITY,\r\nFITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, IN NO\r\nEVENT SHALL WEYAKYAKLLC AND/OR ITS SUPPLIERS BE LIABLE FOR ANY DIRECT,\r\nINDIRECT, PUNITIVE, INCIDENTAL, SPECIAL, CONSEQUENTIAL DAMAGES OR ANY DAMAGES\r\nWHATSOEVER INCLUDING, WITHOUT LIMITATION, DAMAGES FOR LOSS OF USE, DATA OR\r\nPROFITS, ARISING OUT OF OR IN ANY WAY CONNECTED WITH THE USE OR PERFORMANCE OF\r\nTHE SITE, WITH THE DELAY OR INABILITY TO USE THE SITE OR RELATED SERVICES, THE\r\nPROVISION OF OR FAILURE TO PROVIDE SERVICES, OR FOR ANY INFORMATION, SOFTWARE,\r\nPRODUCTS, SERVICES AND RELATED GRAPHICS OBTAINED THROUGH THE SITE, OR OTHERWISE\r\nARISING OUT OF THE USE OF THE SITE, WHETHER BASED ON CONTRACT, TORT,\r\nNEGLIGENCE, STRICT LIABILITY OR OTHERWISE, EVEN IF WEYAKYAKLLC OR ANY OF ITS\r\nSUPPLIERS HAS BEEN ADVISED OF THE POSSIBILITY OF DAMAGES. BECAUSE SOME\r\nSTATES/JURISDICTIONS DO NOT ALLOW THE EXCLUSION OR LIMITATION OF LIABILITY FOR\r\nCONSEQUENTIAL OR INCIDENTAL DAMAGES, THE ABOVE LIMITATION MAY NOT APPLY TO YOU.\r\nIF YOU ARE DISSATISFIED WITH ANY PORTION OF THE SITE, OR WITH ANY OF THESE\r\nTERMS OF USE, YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE USING THE SITE. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Termination/Access\r\nRestriction </span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak reserves the right, in its sole discretion, to\r\nterminate your access to the Site and the related services or any portion\r\nthereof at any time, without notice. To the maximum extent permitted by law,\r\nthis agreement is governed by the laws of the State of Washington and you hereby\r\nconsent to the exclusive jurisdiction and venue of courts in Washington in all\r\ndisputes arising out of or relating to the use of the Site. Use of the Site is\r\nunauthorized in any jurisdiction that does not give effect to all provisions of\r\nthese Terms, including, without limitation, this section. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You agree that no joint venture, partnership, employment, or\r\nagency relationship exists between you and Weyakyak as a result of this\r\nagreement or use of the Site. Weyakyak\'s performance of this agreement is subject\r\nto existing laws and legal process, and nothing contained in this agreement is\r\nin derogation of Weyakyak\'s right to comply with governmental, court and law\r\nenforcement requests or requirements relating to your use of the Site or\r\ninformation provided to or gathered by Weyakyak with respect to such use. If\r\nany part of this agreement is determined to be invalid or unenforceable\r\npursuant to applicable law including, but not limited to, the warranty\r\ndisclaimers and liability limitations set forth above, then the invalid or\r\nunenforceable provision will be deemed superseded by a valid, enforceable\r\nprovision that most closely matches the intent of the original provision and\r\nthe remainder of the agreement shall continue in effect. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Unless otherwise specified herein, this agreement\r\nconstitutes the entire agreement between the user and Weyakyak with respect to\r\nthe Site and it supersedes all prior or contemporaneous communications and\r\nproposals, whether electronic, oral or written, between the user and Weyakyak with\r\nrespect to the Site. A printed version of this agreement and of any notice\r\ngiven in electronic form shall be admissible in judicial or administrative\r\nproceedings based upon or relating to this agreement to the same extent and\r\nsubject to the same conditions as other business documents and records\r\noriginally generated and maintained in printed form. It is the express wish to\r\nthe parties that this agreement and all related documents be written in\r\nEnglish. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Changes to Terms</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak reserves the right, in its sole discretion, to\r\nchange the Terms under which http://www.weyakyak.com is offered. The most\r\ncurrent version of the Terms will supersede all previous versions. Weyakyak\r\nencourages you to periodically review the Terms to stay informed of our updates.\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Contact Us</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Weyakyak welcomes your questions or comments regarding the\r\nTerms: \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">WeyakyakLLC \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">1123 SE 6th St. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Battle Ground, Washington 98604 \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Email Address: \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Kelly@weyakyak.com \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Telephone number: \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">541-980-5595 \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Effective as of May 06, 2019 \r\n		<o:p></o:p></p>\r\n	<p>\r\n		<!--\r\n		[if gte mso 9]><xml>\r\n <o:OfficeDocumentSettings>\r\n  <o:TargetScreenSize>800x600</o:TargetScreenSize>\r\n </o:OfficeDocumentSettings>\r\n</xml><![endif]\r\n		-->\r\n		\r\n		<!--\r\n		[if gte mso 9]><xml>\r\n <w:WordDocument>\r\n  <w:View>Normal</w:View>\r\n  <w:Zoom>0</w:Zoom>\r\n  <w:TrackMoves/>\r\n  <w:TrackFormatting/>\r\n  <w:PunctuationKerning/>\r\n  <w:ValidateAgainstSchemas/>\r\n  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>\r\n  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>\r\n  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>\r\n  <w:DoNotPromoteQF/>\r\n  <w:LidThemeOther>EN-US</w:LidThemeOther>\r\n  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>\r\n  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>\r\n  <w:Compatibility>\r\n   <w:BreakWrappedTables/>\r\n   <w:SnapToGridInCell/>\r\n   <w:WrapTextWithPunct/>\r\n   <w:UseAsianBreakRules/>\r\n   <w:DontGrowAutofit/>\r\n   <w:SplitPgBreakAndParaMark/>\r\n   <w:EnableOpenTypeKerning/>\r\n   <w:DontFlipMirrorIndents/>\r\n   <w:OverrideTableStyleHps/>\r\n  </w:Compatibility>\r\n  <w:DoNotOptimizeForBrowser/>\r\n  <m:mathPr>\r\n   <m:mathFont m:val=\"Cambria Math\"/>\r\n   <m:brkBin m:val=\"before\"/>\r\n   <m:brkBinSub m:val=\"&#45;-\"/>\r\n   <m:smallFrac m:val=\"off\"/>\r\n   <m:dispDef/>\r\n   <m:lMargin m:val=\"0\"/>\r\n   <m:rMargin m:val=\"0\"/>\r\n   <m:defJc m:val=\"centerGroup\"/>\r\n   <m:wrapIndent m:val=\"1440\"/>\r\n   <m:intLim m:val=\"subSup\"/>\r\n   <m:naryLim m:val=\"undOvr\"/>\r\n  </m:mathPr></w:WordDocument>\r\n</xml><![endif]\r\n		-->\r\n		\r\n		<!--\r\n		[if gte mso 9]><xml>\r\n <w:LatentStyles DefLockedState=\"false\" DefUnhideWhenUsed=\"false\"\r\n  DefSemiHidden=\"false\" DefQFormat=\"false\" DefPriority=\"99\"\r\n  LatentStyleCount=\"375\">\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" QFormat=\"true\" Name=\"Normal\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" QFormat=\"true\" Name=\"heading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 9\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"header\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footer\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"35\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"caption\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of figures\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope return\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"line number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"page number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of authorities\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"macro\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"toa heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"10\" QFormat=\"true\" Name=\"Title\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Closing\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Signature\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Default Paragraph Font\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Message Header\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"11\" QFormat=\"true\" Name=\"Subtitle\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Salutation\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Date\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Note Heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Block Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"FollowedHyperlink\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"22\" QFormat=\"true\" Name=\"Strong\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"20\" QFormat=\"true\" Name=\"Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Document Map\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Plain Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"E-mail Signature\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Top of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Bottom of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal (Web)\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Acronym\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Cite\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Code\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Definition\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Keyboard\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Preformatted\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Sample\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Typewriter\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Variable\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Table\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation subject\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"No List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Contemporary\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Elegant\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Professional\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Balloon Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" Name=\"Table Grid\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Theme\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Placeholder Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"1\" QFormat=\"true\" Name=\"No Spacing\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Revision\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"34\" QFormat=\"true\"\r\n   Name=\"List Paragraph\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"29\" QFormat=\"true\" Name=\"Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"30\" QFormat=\"true\"\r\n   Name=\"Intense Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"19\" QFormat=\"true\"\r\n   Name=\"Subtle Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"21\" QFormat=\"true\"\r\n   Name=\"Intense Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"31\" QFormat=\"true\"\r\n   Name=\"Subtle Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"32\" QFormat=\"true\"\r\n   Name=\"Intense Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"33\" QFormat=\"true\" Name=\"Book Title\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"37\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Bibliography\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"TOC Heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"41\" Name=\"Plain Table 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"42\" Name=\"Plain Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"43\" Name=\"Plain Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"44\" Name=\"Plain Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"45\" Name=\"Plain Table 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"40\" Name=\"Grid Table Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"Grid Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"Grid Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"Grid Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"List Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"List Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"List Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Mention\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Smart Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hashtag\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Unresolved Mention\"/>\r\n </w:LatentStyles>\r\n</xml><![endif]\r\n		-->\r\n		\r\n		<style>\r\n		<!--\r\n		/* Font Definitions */\r\n @font-face\r\n	{font-family:\"Cambria Math\";\r\n	panose-1:2 4 5 3 5 4 6 3 2 4;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:roman;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:3 0 0 0 1 0;}\r\n@font-face\r\n	{font-family:Times;\r\n	panose-1:0 0 5 0 0 0 0 2 0 0;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:auto;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:-536870145 1342185562 0 0 415 0;}\r\n /* Style Definitions */\r\n p.MsoNormal, li.MsoNormal, div.MsoNormal\r\n	{mso-style-unhide:no;\r\n	mso-style-qformat:yes;\r\n	mso-style-parent:\"\";\r\n	margin:0in;\r\n	margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:12.0pt;\r\n	font-family:\"Times New Roman\",serif;\r\n	mso-fareast-font-family:\"Times New Roman\";\r\n	mso-fareast-language:RU;}\r\n.MsoChpDefault\r\n	{mso-style-type:export-only;\r\n	mso-default-props:yes;\r\n	font-size:10.0pt;\r\n	mso-ansi-font-size:10.0pt;\r\n	mso-bidi-font-size:10.0pt;}\r\n@page WordSection1\r\n	{size:8.5in 11.0in;\r\n	margin:1.0in 1.0in 1.0in 1.0in;\r\n	mso-header-margin:.5in;\r\n	mso-footer-margin:.5in;\r\n	mso-paper-source:0;}\r\ndiv.WordSection1\r\n	{page:WordSection1;}\r\n		-->\r\n		</style>\r\n		<!--\r\n		[if gte mso 10]>\r\n<style>\r\n /* Style Definitions */\r\n table.MsoNormalTable\r\n	{mso-style-name:\"Table Normal\";\r\n	mso-tstyle-rowband-size:0;\r\n	mso-tstyle-colband-size:0;\r\n	mso-style-noshow:yes;\r\n	mso-style-priority:99;\r\n	mso-style-parent:\"\";\r\n	mso-padding-alt:0in 5.4pt 0in 5.4pt;\r\n	mso-para-margin:0in;\r\n	mso-para-margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:10.0pt;\r\n	font-family:\"Times New Roman\",serif;}\r\n</style>\r\n<![endif]\r\n		-->\r\n		\r\n		<!--\r\n		StartFragment\r\n		-->\r\n		\r\n		<!--\r\n		EndFragment\r\n		-->\r\n		</p>\r\n	<p class=\"MsoNormal\">&nbsp;&nbsp;\r\n		<o:p></o:p></p></div>', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(3, 1, 'Privacy Policy', '<section class=\"section section--page\">             \r\n	<div class=\"container container--narrow\">                 \r\n		<div class=\"section__head\">                     \r\n			<h2>Privacy Policy</h2>                 </div>                \r\n		<div class=\"section__body\">                    \r\n			<div class=\"box -padding-30\">                        \r\n				<div class=\"cms-container\">                          \r\n                        \r\n                            \r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Protecting your private information is our priority. This\r\nStatement of Privacy applies to http://www.weyakyak.com and WeyakyakLLC and\r\ngoverns data collection and usage. For the purposes of this Privacy Policy,\r\nunless otherwise noted, all references to WeyakyakLLC include\r\nhttp://www.weyakyak.com and Weyakyak. The Weyakyak website is a Online language\r\nplatform for teaching and learning foreign languages. site. By using the\r\nWeyakyak website, you consent to the data practices described in this\r\nstatement. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Collection of your\r\nPersonal Information</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">In order to better provide you with products and services\r\noffered on our Site, Weyakyak may collect personally identifiable information,\r\nsuch as your: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; First\r\nand Last Name \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mailing\r\nAddress \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; E-mail\r\nAddress \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phone\r\nNumber \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; credentials\r\nand college degrees, certificates etc. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">If you purchase Weyakyak\'s products and services, we collect\r\nbilling and credit card information. This information is used to complete the\r\npurchase transaction. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Please keep in mind that if you directly disclose personally\r\nidentifiable information or personally sensitive data through Weyakyak\'s public\r\nmessage boards, this information may be collected and used by others. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">We do not collect any\r\npersonal information about you unless you voluntarily provide it to us.\r\nHowever, you may be required to provide certain personal information to us when\r\nyou <span style=\"font-size: 14pt; font-family: Times; font-weight: bold;\">Privacy Policy</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">elect to use certain products or services available on the\r\nSite. These may include: (a) registering for an account on our Site; (b)\r\nentering a sweepstakes or contest sponsored by us or one of our partners; (c)\r\nsigning up for special offers from selected third parties; (d) sending us an\r\nemail message; (e) submitting your credit card or other payment information\r\nwhen ordering and purchasing products and services on our Site. To wit, we will\r\nuse your information for, but not limited to, communicating with you in\r\nrelation to services and/or products you have requested from us. We also may\r\ngather additional personal or non-personal information in the future. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Use of your Personal\r\nInformation </span>\r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak collects and uses your personal information to\r\noperate its website(s) and deliver the services you have requested. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak may also use your personally identifiable information\r\nto inform you of other products or services available from Weyakyak and its\r\naffiliates. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Sharing Information\r\nwith Third Parties</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak does not sell, rent or lease its customer lists to\r\nthird parties. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak may share data with trusted partners to help\r\nperform statistical analysis, send you email or postal mail, provide customer\r\nsupport, or arrange for deliveries. All such third parties are prohibited from\r\nusing your personal information except to provide these services to Weyakyak,\r\nand they are required to maintain the confidentiality of your information. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak may disclose your personal information, without\r\nnotice, if required to do so by law or in the good faith belief that such\r\naction is necessary to: (a) conform to the edicts of the law or comply with\r\nlegal process served on Weyakyak or the site; (b) protect and defend the rights\r\nor property of Weyakyak; and/or (c) act under exigent circumstances to protect\r\nthe personal safety of users of Weyakyak, or the public. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Automatically\r\nCollected Information</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Information about your computer hardware and software may be\r\nautomatically collected by Weyakyak. This information can include: your IP\r\naddress, browser type, domain names, access times and referring website\r\naddresses. This information is used for the operation of the service, to\r\nmaintain quality of the service, and to provide general statistics regarding\r\nuse of the Weyakyak website. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Use of Cookies</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">The Weyakyak website may use \"cookies\" to help you\r\npersonalize your online experience. A cookie is a text file that is placed on\r\nyour hard disk by a web page server. Cookies cannot be used to run programs or\r\ndeliver viruses to your computer. Cookies are uniquely assigned to you, and can\r\nonly be read by a web server in the domain that issued the cookie to you. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">One of the primary purposes of cookies is to provide a\r\nconvenience feature to save you time. The purpose of a cookie is to tell the\r\nWeb server that you have returned to a specific page. For example, if you\r\npersonalize Weyakyak pages, or register with Weyakyak site or services, a\r\ncookie helps Weyakyak to recall your specific information on subsequent visits.\r\nThis simplifies the process of recording your personal information, such as\r\nbilling addresses, shipping addresses, and so on. When you return to the same\r\nWeyakyak website, the information you previously provided can be retrieved, so\r\nyou can easily use the Weyakyak features that you customized. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">You have the ability to accept or decline cookies. Most Web\r\nbrowsers automatically accept cookies, but you can usually modify your browser\r\nsetting to decline cookies if you prefer. If you choose to decline cookies, you\r\nmay not be able to fully experience the interactive features of the Weyakyak\r\nservices or websites you visit. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Links</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">This website contains links to other sites. Please be aware\r\nthat we are not responsible for the content or privacy practices of such other\r\nsites. We encourage our users to be aware when they leave our site and to read\r\nthe privacy statements of any other site that collects personally identifiable\r\ninformation. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Security of your\r\nPersonal Information</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak secures your personal information from unauthorized\r\naccess, use, or disclosure. Weyakyak uses the following methods for this\r\npurpose: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SSL\r\nProtocol \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SSL \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">When personal information (such as a credit card number) is\r\ntransmitted to other websites, it is protected through the use of encryption,\r\nsuch as the Secure Sockets Layer (SSL) protocol. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">We strive to take appropriate security measures to protect\r\nagainst unauthorized access to or alteration of your personal information.\r\nUnfortunately, no data transmission over the Internet or any wireless network\r\ncan be guaranteed to be 100% secure. As a result, while we strive to protect\r\nyour personal information, you acknowledge that: (a) there are security and\r\nprivacy limitations inherent to the Internet which are beyond our control; and\r\n(b) security, integrity, and privacy of any and all information and data\r\nexchanged between you and us through this Site cannot be guaranteed. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Children Under\r\nThirteen</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak does not knowingly collect personally identifiable\r\ninformation from children under the age of thirteen. If you are under the age\r\nof thirteen, you must ask your parent or guardian for permission to use this\r\nwebsite. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Disconnecting your\r\nWeyakyak Account from Third Party Websites</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">You will be able to connect your Weyakyak account to third\r\nparty accounts. BY CONNECTING YOUR WEYAKYAK ACCOUNT TO YOUR THIRD PARTY\r\nACCOUNT, YOU ACKNOWLEDGE AND AGREE THAT YOU ARE CONSENTING TO THE CONTINUOUS\r\nRELEASE OF INFORMATION ABOUT YOU TO OTHERS (IN ACCORDANCE WITH YOUR PRIVACY\r\nSETTINGS ON THOSE THIRD PARTY SITES). IF YOU DO NOT WANT INFORMATION ABOUT YOU,\r\nINCLUDING PERSONALLY IDENTIFYING INFORMATION, TO BE SHARED IN THIS MANNER, DO\r\nNOT USE THIS FEATURE. You may disconnect your account from a third party\r\naccount at any time. Users may learn how to disconnect their accounts from\r\nthird-party websites by visiting their \"My Account\" page. Users may\r\nalso contact us via email or telephone. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">E-mail Communications</span>\r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">From time to time, Weyakyak may contact you via email for\r\nthe purpose of providing announcements, promotional offers, alerts,\r\nconfirmations, surveys, and/or other general communication. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">If you would like to stop receiving marketing or promotional\r\ncommunications via email from Weyakyak, you may opt out of such communications\r\nby Customers may unsubscribe from emails or from the platform by \"replying\r\nSTOP\" or \"clicking on the UNSUBSCRIBE button. Through email request\r\nthey can have their profiles removed. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Changes to this\r\nStatement</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak reserves the right to change this Privacy Policy\r\nfrom time to time. We will notify you about significant changes in the way we\r\ntreat personal information by sending a notice to the primary email address\r\nspecified in your account, by placing a prominent notice on our site, and/or by\r\nupdating any privacy information on this page. Your continued use of the Site\r\nand/or Services available through this Site after such modifications will\r\nconstitute your: (a) acknowledgment of the modified Privacy Policy; and (b)\r\nagreement to abide and be bound by that Policy. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Contact Information</span>\r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Weyakyak welcomes your questions or comments regarding this\r\nStatement of Privacy. If you believe that Weyakyak has not adhered to this\r\nStatement, please contact Weyakyak at: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">WeyakyakLLC \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">1123 SE 6th st. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Battle Ground, Washington 98604 \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Email Address: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">info@weyakyak.com \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Telephone number: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">541-980-5595 \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Effective as of June 10, 2019 \r\n						<o:p></o:p></p>\r\n					<p>\r\n						<!--\r\n						[if gte mso 9]><xml>\r\n <o:OfficeDocumentSettings>\r\n  <o:TargetScreenSize>800x600</o:TargetScreenSize>\r\n </o:OfficeDocumentSettings>\r\n</xml><![endif]\r\n						-->\r\n						\r\n						<!--\r\n						[if gte mso 9]><xml>\r\n <w:WordDocument>\r\n  <w:View>Normal</w:View>\r\n  <w:Zoom>0</w:Zoom>\r\n  <w:TrackMoves/>\r\n  <w:TrackFormatting/>\r\n  <w:PunctuationKerning/>\r\n  <w:ValidateAgainstSchemas/>\r\n  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>\r\n  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>\r\n  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>\r\n  <w:DoNotPromoteQF/>\r\n  <w:LidThemeOther>EN-US</w:LidThemeOther>\r\n  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>\r\n  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>\r\n  <w:Compatibility>\r\n   <w:BreakWrappedTables/>\r\n   <w:SnapToGridInCell/>\r\n   <w:WrapTextWithPunct/>\r\n   <w:UseAsianBreakRules/>\r\n   <w:DontGrowAutofit/>\r\n   <w:SplitPgBreakAndParaMark/>\r\n   <w:EnableOpenTypeKerning/>\r\n   <w:DontFlipMirrorIndents/>\r\n   <w:OverrideTableStyleHps/>\r\n  </w:Compatibility>\r\n  <w:DoNotOptimizeForBrowser/>\r\n  <m:mathPr>\r\n   <m:mathFont m:val=\"Cambria Math\"/>\r\n   <m:brkBin m:val=\"before\"/>\r\n   <m:brkBinSub m:val=\"&#45;-\"/>\r\n   <m:smallFrac m:val=\"off\"/>\r\n   <m:dispDef/>\r\n   <m:lMargin m:val=\"0\"/>\r\n   <m:rMargin m:val=\"0\"/>\r\n   <m:defJc m:val=\"centerGroup\"/>\r\n   <m:wrapIndent m:val=\"1440\"/>\r\n   <m:intLim m:val=\"subSup\"/>\r\n   <m:naryLim m:val=\"undOvr\"/>\r\n  </m:mathPr></w:WordDocument>\r\n</xml><![endif]\r\n						-->\r\n						\r\n						<!--\r\n						[if gte mso 9]><xml>\r\n <w:LatentStyles DefLockedState=\"false\" DefUnhideWhenUsed=\"false\"\r\n  DefSemiHidden=\"false\" DefQFormat=\"false\" DefPriority=\"99\"\r\n  LatentStyleCount=\"375\">\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" QFormat=\"true\" Name=\"Normal\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" QFormat=\"true\" Name=\"heading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 9\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"header\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footer\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"35\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"caption\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of figures\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope return\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"line number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"page number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of authorities\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"macro\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"toa heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"10\" QFormat=\"true\" Name=\"Title\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Closing\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Signature\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Default Paragraph Font\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Message Header\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"11\" QFormat=\"true\" Name=\"Subtitle\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Salutation\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Date\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Note Heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Block Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"FollowedHyperlink\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"22\" QFormat=\"true\" Name=\"Strong\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"20\" QFormat=\"true\" Name=\"Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Document Map\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Plain Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"E-mail Signature\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Top of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Bottom of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal (Web)\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Acronym\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Cite\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Code\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Definition\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Keyboard\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Preformatted\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Sample\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Typewriter\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Variable\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Table\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation subject\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"No List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Contemporary\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Elegant\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Professional\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Balloon Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" Name=\"Table Grid\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Theme\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Placeholder Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"1\" QFormat=\"true\" Name=\"No Spacing\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Revision\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"34\" QFormat=\"true\"\r\n   Name=\"List Paragraph\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"29\" QFormat=\"true\" Name=\"Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"30\" QFormat=\"true\"\r\n   Name=\"Intense Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"19\" QFormat=\"true\"\r\n   Name=\"Subtle Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"21\" QFormat=\"true\"\r\n   Name=\"Intense Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"31\" QFormat=\"true\"\r\n   Name=\"Subtle Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"32\" QFormat=\"true\"\r\n   Name=\"Intense Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"33\" QFormat=\"true\" Name=\"Book Title\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"37\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Bibliography\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"TOC Heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"41\" Name=\"Plain Table 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"42\" Name=\"Plain Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"43\" Name=\"Plain Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"44\" Name=\"Plain Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"45\" Name=\"Plain Table 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"40\" Name=\"Grid Table Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"Grid Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"Grid Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"Grid Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"List Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"List Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"List Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Mention\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Smart Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hashtag\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Unresolved Mention\"/>\r\n </w:LatentStyles>\r\n</xml><![endif]\r\n						-->\r\n						\r\n						<style>\r\n						<!--\r\n						/* Font Definitions */\r\n @font-face\r\n	{font-family:\"Cambria Math\";\r\n	panose-1:2 4 5 3 5 4 6 3 2 4;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:roman;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:3 0 0 0 1 0;}\r\n@font-face\r\n	{font-family:Times;\r\n	panose-1:0 0 5 0 0 0 0 2 0 0;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:auto;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:-536870145 1342185562 0 0 415 0;}\r\n /* Style Definitions */\r\n p.MsoNormal, li.MsoNormal, div.MsoNormal\r\n	{mso-style-unhide:no;\r\n	mso-style-qformat:yes;\r\n	mso-style-parent:\"\";\r\n	margin:0in;\r\n	margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:12.0pt;\r\n	font-family:\"Times New Roman\",serif;\r\n	mso-fareast-font-family:\"Times New Roman\";\r\n	mso-fareast-language:RU;}\r\n.MsoChpDefault\r\n	{mso-style-type:export-only;\r\n	mso-default-props:yes;\r\n	font-size:10.0pt;\r\n	mso-ansi-font-size:10.0pt;\r\n	mso-bidi-font-size:10.0pt;}\r\n@page WordSection1\r\n	{size:8.5in 11.0in;\r\n	margin:1.0in 1.0in 1.0in 1.0in;\r\n	mso-header-margin:.5in;\r\n	mso-footer-margin:.5in;\r\n	mso-paper-source:0;}\r\ndiv.WordSection1\r\n	{page:WordSection1;}\r\n						-->\r\n						</style>\r\n						<!--\r\n						[if gte mso 10]>\r\n<style>\r\n /* Style Definitions */\r\n table.MsoNormalTable\r\n	{mso-style-name:\"Table Normal\";\r\n	mso-tstyle-rowband-size:0;\r\n	mso-tstyle-colband-size:0;\r\n	mso-style-noshow:yes;\r\n	mso-style-priority:99;\r\n	mso-style-parent:\"\";\r\n	mso-padding-alt:0in 5.4pt 0in 5.4pt;\r\n	mso-para-margin:0in;\r\n	mso-para-margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:10.0pt;\r\n	font-family:\"Times New Roman\",serif;}\r\n</style>\r\n<![endif]\r\n						-->\r\n						\r\n						<!--\r\n						StartFragment\r\n						-->\r\n						\r\n						<!--\r\n						EndFragment\r\n						-->\r\n						</p>\r\n					<p class=\"MsoNormal\">&nbsp;&nbsp;\r\n						<o:p></o:p></p>      </div>                    </div>                </div>             </div>         </section>', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(4, 1, 'test page eng', '', 'test page english image', ''),
(4, 2, 'test page arabic', '', 'test page arabic image', ''),
(5, 1, 'Contact US', '<section class=\"banner banner--main\">             \r\n	<div class=\"banner__media\"><img src=\"/public/images/2000x600.jpg\" alt=\"\" /></div>             \r\n	<div class=\"banner__content banner__content--centered\">                 \r\n		<h1>Drop us a line</h1>                 \r\n		<p>Got a question? We\'d love to hear from  you. <br />\r\n			Send us a message and we\'ll respond as soon as possible.</p>             </div>         </section>         \r\n         \r\n<section class=\"section section--white section--offset -align-center\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"row justify-content-center\">                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_1.svg\" alt=\"\" /></div>                         \r\n				<p>Team Weyakyak</p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_2.svg\" alt=\"\" /></div>                         \r\n				<p><a href=\"#\">info@weyakyak.com</a><br />\r\n					</p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_3.svg\" alt=\"\" /></div>                         \r\n				<p>coming soon.&nbsp; Please send email</p>                     </div>                 </div>             </div>         </section>', '', ''),
(5, 2, 'Contact US', '<section class=\"banner banner--main\">             \r\n	<div class=\"banner__media\"><img src=\"/public/images/2000x600.jpg\" alt=\"\" /></div>             \r\n	<div class=\"banner__content banner__content--centered\">                 \r\n		<h1>Drop us a line</h1>                 \r\n		<p>Got a question? We\'d love to hear from  you. <br />\r\n			Send us a message and we\'ll respond as soon as possible.</p>             </div>         </section>         \r\n         \r\n<section class=\"section section--white section--offset -align-center\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"row justify-content-center\">                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_1.svg\" alt=\"\" /></div>                         \r\n				<p>The Office Group <br />\r\n					91 Wimpole Street Marylebone,<br />\r\n					 London W1G 0EF, United Kingdom</p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_2.svg\" alt=\"\" /></div>                         \r\n				<p><a href=\"#\">info@weyakyak.com</a><br />\r\n					                         <a href=\"#\">sales@weyakweyak.com</a></p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_3.svg\" alt=\"\" /></div>                         \r\n				<p>(+44) 020 7846 0316 <br />\r\n					(+44) 020 7846 0316 <br />\r\n					(+44) 020 7846 0316</p>                     </div>                 </div>             </div>         </section>', '', ''),
(6, 1, 'Help', '<div><br />\r\n	</div>', 'Help', 'hgujgkuy'),
(6, 2, 'asdasd', 'asdadsad', '', ''),
(7, 1, 'Apply Teach', '', 'Get paid to help people', 'Earn money teaching your language online from home. Anytime. Anywhere.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countries`
--

DROP TABLE IF EXISTS `tbl_countries`;
CREATE TABLE `tbl_countries` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8mb4 NOT NULL,
  `country_active` tinyint(1) NOT NULL,
  `country_currency_id` int(11) NOT NULL,
  `country_language_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_countries`
--

INSERT INTO `tbl_countries` (`country_id`, `country_code`, `country_active`, `country_currency_id`, `country_language_id`) VALUES
(1, 'df', 0, 0, 0),
(2, 'In', 1, 1, 1),
(5, 'Sr', 1, 0, 0),
(6, 'US', 1, 1, 1),
(7, 'CA', 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countries_lang`
--

DROP TABLE IF EXISTS `tbl_countries_lang`;
CREATE TABLE `tbl_countries_lang` (
  `countrylang_country_id` int(11) NOT NULL,
  `countrylang_lang_id` int(11) NOT NULL,
  `country_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_countries_lang`
--

INSERT INTO `tbl_countries_lang` (`countrylang_country_id`, `countrylang_lang_id`, `country_name`) VALUES
(7, 1, 'Canada'),
(1, 1, 'dfd'),
(2, 1, 'India'),
(5, 1, 'Srilanka'),
(6, 1, 'United States'),
(7, 2, 'Canada'),
(5, 2, 'Srilanka'),
(6, 2, 'United States');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons`
--

DROP TABLE IF EXISTS `tbl_coupons`;
CREATE TABLE `tbl_coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_identifier` varchar(200) NOT NULL,
  `coupon_type` tinyint(4) NOT NULL COMMENT 'Defined in model like discount or free shipping coupon',
  `coupon_code` varchar(50) NOT NULL,
  `coupon_valid_for` int(11) NOT NULL COMMENT 'Defined in Discount Coupon model',
  `coupon_min_order_value` decimal(12,2) NOT NULL,
  `coupon_discount_in_percent` tinyint(1) NOT NULL,
  `coupon_discount_value` decimal(12,2) NOT NULL,
  `coupon_max_discount_value` decimal(12,2) NOT NULL,
  `coupon_start_date` date NOT NULL,
  `coupon_end_date` date NOT NULL,
  `coupon_uses_count` int(11) NOT NULL,
  `coupon_uses_coustomer` int(11) NOT NULL,
  `coupon_active` tinyint(1) NOT NULL,
  `coupon_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_coupons`
--

INSERT INTO `tbl_coupons` (`coupon_id`, `coupon_identifier`, `coupon_type`, `coupon_code`, `coupon_valid_for`, `coupon_min_order_value`, `coupon_discount_in_percent`, `coupon_discount_value`, `coupon_max_discount_value`, `coupon_start_date`, `coupon_end_date`, `coupon_uses_count`, `coupon_uses_coustomer`, `coupon_active`, `coupon_deleted`) VALUES
(1, 'First Cpn', 0, 'FSTCPN', 0, '1.00', 2, '65.00', '0.00', '2019-04-02', '2025-01-29', 1111, 1111, 1, 0),
(2, 'Flat 50', 0, 'FLAT50', 0, '0.00', 2, '100.00', '0.00', '2019-04-05', '2019-05-31', 12222, 1222, 1, 0),
(3, 'Wethebest', 0, 'WEBEST', 0, '250.00', 2, '50.00', '0.00', '2019-05-01', '2019-05-31', 1, 1, 1, 0),
(4, 'flat100', 0, 'FLAt100', 0, '5.00', 2, '100.00', '0.00', '2019-05-21', '2019-05-19', 12222, 1222, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons_history`
--

DROP TABLE IF EXISTS `tbl_coupons_history`;
CREATE TABLE `tbl_coupons_history` (
  `couponhistory_id` int(11) NOT NULL,
  `couponhistory_coupon_id` int(11) NOT NULL,
  `couponhistory_order_id` varchar(15) NOT NULL,
  `couponhistory_user_id` int(11) NOT NULL,
  `couponhistory_amount` double(12,2) NOT NULL,
  `couponhistory_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons_hold`
--

DROP TABLE IF EXISTS `tbl_coupons_hold`;
CREATE TABLE `tbl_coupons_hold` (
  `couponhold_id` int(11) NOT NULL,
  `couponhold_coupon_id` int(11) NOT NULL,
  `couponhold_user_id` int(11) NOT NULL,
  `couponhold_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons_hold_pending_order`
--

DROP TABLE IF EXISTS `tbl_coupons_hold_pending_order`;
CREATE TABLE `tbl_coupons_hold_pending_order` (
  `ochold_order_id` varchar(15) NOT NULL,
  `ochold_coupon_id` int(11) NOT NULL,
  `ochold_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons_lang`
--

DROP TABLE IF EXISTS `tbl_coupons_lang`;
CREATE TABLE `tbl_coupons_lang` (
  `couponlang_coupon_id` int(11) NOT NULL,
  `couponlang_lang_id` int(11) NOT NULL,
  `coupon_title` varchar(255) NOT NULL,
  `coupon_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_courses_categories`
--

DROP TABLE IF EXISTS `tbl_courses_categories`;
CREATE TABLE `tbl_courses_categories` (
  `ccategory_id` int(11) NOT NULL,
  `ccategory_identifier` varchar(255) NOT NULL,
  `ccategory_active` tinyint(1) NOT NULL,
  `ccategory_deleted` tinyint(1) NOT NULL,
  `ccategory_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_courses_categories_lang`
--

DROP TABLE IF EXISTS `tbl_courses_categories_lang`;
CREATE TABLE `tbl_courses_categories_lang` (
  `ccategorylang_ccategory_id` int(11) NOT NULL,
  `ccategorylang_lang_id` int(11) NOT NULL,
  `ccategory_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currencies`
--

DROP TABLE IF EXISTS `tbl_currencies`;
CREATE TABLE `tbl_currencies` (
  `currency_id` int(11) NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `currency_symbol_left` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `currency_symbol_right` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `currency_value` decimal(12,8) NOT NULL,
  `currency_active` tinyint(1) NOT NULL,
  `currency_is_default` tinyint(1) NOT NULL,
  `currency_date_modified` datetime NOT NULL,
  `currency_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_currencies`
--

INSERT INTO `tbl_currencies` (`currency_id`, `currency_code`, `currency_symbol_left`, `currency_symbol_right`, `currency_value`, `currency_active`, `currency_is_default`, `currency_date_modified`, `currency_display_order`) VALUES
(1, 'USD', '$', '', '1.00000000', 1, 1, '2018-09-28 19:02:00', 1),
(2, 'CAD', 'C$', '', '0.76000000', 1, 0, '2018-09-28 19:11:00', 2),
(3, 'Rs.', 'Rs', '', '2.00000000', 0, 0, '2018-10-19 09:54:20', 0),
(4, 'EUR', '€', '', '1.20000000', 1, 0, '2019-05-16 01:07:16', 0),
(5, 'Yen', '¥', '', '0.00910000', 1, 0, '2019-05-20 03:03:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currencies_lang`
--

DROP TABLE IF EXISTS `tbl_currencies_lang`;
CREATE TABLE `tbl_currencies_lang` (
  `currencylang_currency_id` int(11) NOT NULL,
  `currencylang_lang_id` int(11) NOT NULL,
  `currency_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_currencies_lang`
--

INSERT INTO `tbl_currencies_lang` (`currencylang_currency_id`, `currencylang_lang_id`, `currency_name`) VALUES
(1, 1, 'United States Dollar'),
(2, 1, 'Canadian Dollar'),
(3, 1, 'Rupees'),
(4, 1, 'Euro'),
(4, 2, 'Euro'),
(5, 1, 'Yen'),
(5, 2, 'Yen');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_archives`
--

DROP TABLE IF EXISTS `tbl_email_archives`;
CREATE TABLE `tbl_email_archives` (
  `emailarchive_id` int(11) NOT NULL,
  `emailarchive_to_email` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `emailarchive_tpl_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `emailarchive_subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `emailarchive_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `emailarchive_headers` text CHARACTER SET utf8mb4 NOT NULL,
  `emailarchive_sent_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_email_archives`
--

INSERT INTO `tbl_email_archives` (`emailarchive_id`, `emailarchive_to_email`, `emailarchive_tpl_name`, `emailarchive_subject`, `emailarchive_body`, `emailarchive_headers`, `emailarchive_sent_on`) VALUES
(1, 'info@weyakyak.com', 'new_registration_admin', 'New Registration on WeYakYak', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"http://localhost/wtutors\"><img src=\"http://localhost/wtutors/image/email-logo/1\" /></a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\"><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.facebook.com/weyakyak/\" target=\"_blank\" title=\"Facebook\" ><img alt=\"Facebook\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/1\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://twitter.com/we_yak\" target=\"_blank\" title=\"TW\" ><img alt=\"TW\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/2\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.instagram.com/weyakyak/\" target=\"_blank\" title=\"IG\" ><img alt=\"IG\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/3\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.pinterest.com/Weyakyak/pins/\" target=\"_blank\" title=\"P\" ><img alt=\"P\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/4\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.youtube.com/user/thepoodlegirl/featured?view_as=subscriber\" target=\"_blank\" title=\"YT\" ><img alt=\"YT\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/5\"/></a></td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">New Account Created!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin</h3>We have received a new registration on <a href=\"http://localhost/wtutors\">WeYakYak</a>. Please find the details below:\r\n                                  <br />\r\n												<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">teacher teacher</td>\r\n														</tr>  \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">teacher@dummyid.com</td>\r\n														</tr>                                                        \r\n													</tbody>\r\n												</table>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"http://localhost/wtutors/contact\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">info@weyakyak.com</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, WeYakYak. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '\'MIME-Version: 1.0\\r\\nContent-type: text/html; charset=utf-8\\r\\nFrom: WeYakYak<info@weyakyak.com>\\r\\nReply-to: info@weyakyak.com\'', '2019-06-01 11:31:27'),
(2, 'teacher@dummyid.com', 'teacher_request_status_change_learner', 'Your Tutor  Request Approved at WeYakYak', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"http://localhost/wtutors\"><img src=\"http://localhost/wtutors/image/email-logo/1\" /></a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\"><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.facebook.com/weyakyak/\" target=\"_blank\" title=\"Facebook\" ><img alt=\"Facebook\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/1\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://twitter.com/we_yak\" target=\"_blank\" title=\"TW\" ><img alt=\"TW\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/2\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.instagram.com/weyakyak/\" target=\"_blank\" title=\"IG\" ><img alt=\"IG\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/3\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.pinterest.com/Weyakyak/pins/\" target=\"_blank\" title=\"P\" ><img alt=\"P\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/4\"/></a><a style=\"display:inline-block;vertical-align:top; width:26px;height:26px; margin:0 0 0 5px; background:rgba(255,255,255,0.2); border-radius:100%;padding:4px;\" href=\"https://www.youtube.com/user/thepoodlegirl/featured?view_as=subscriber\" target=\"_blank\" title=\"YT\" ><img alt=\"YT\" width=\"24\" style=\"margin:1px auto 0; display:block;\" src = \"http://localhost/wtutors/image/social-platform/5\"/></a></td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Updated</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Tutor Approval Status</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear teacher teacher </h3>Your Tutor approval request has been Approved corresponding to Reference Number - 1-1559376961.<br />\r\n												</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"http://localhost/wtutors/contact\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">info@weyakyak.com</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, WeYakYak. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '\'MIME-Version: 1.0\\r\\nContent-type: text/html; charset=utf-8\\r\\nFrom: WeYakYak<info@weyakyak.com>\\r\\nReply-to: info@weyakyak.com\'', '2019-06-01 13:46:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_templates`
--

DROP TABLE IF EXISTS `tbl_email_templates`;
CREATE TABLE `tbl_email_templates` (
  `etpl_code` varchar(50) NOT NULL,
  `etpl_lang_id` int(11) NOT NULL,
  `etpl_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `etpl_subject` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `etpl_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `etpl_replacements` text CHARACTER SET utf8mb4 NOT NULL,
  `etpl_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_email_templates`
--

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('admin_forgot_password', 1, 'Admin Forgot Password Email', 'Forgot Password Email', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Request Received</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Retrieve Password!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>It seems that you have used forgot password option at <a href=\"{website_url}\">{website_name}</a>. Please click here to below link to change your password.\r\n                                  <br />\r\n												<br />\r\n												                                  <a href=\"{reset_url}\" style=\"background:#8337e6; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click here</a> <br />\r\n												<br />\r\n												                                  Please ignore this email if you did not use the forgot password option\r\n                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{reset_url} URL to reset the password<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1),
('new_registration_admin', 1, 'New Registration - Admin', 'New Registration on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">New Account Created!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin</h3>We have received a new registration on <a href=\"{website_url}\">{website_name}</a>. Please find the details below:\r\n                                  <br />\r\n												<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_full_name}</td>\r\n														</tr>  \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_email}</td>\r\n														</tr>                                                        \r\n													</tbody>\r\n												</table>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{website_name} Name of the website<br />\r\n{user_email} Email Address of the person registered<br />\r\n{user_first_name} First Name of the person registered<br />\r\n{user_last_name} Last Name of the person registered<br />\r\n{user_full_name} Full Name of the person registered<br />\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n', 1),
('user_email_verification', 1, 'Email Confirmation on Registration', 'Email Verification at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Account Verification!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>                                  Congratulations!! Your account has been successfully created at <a href=\"{website_url}\">{website_name}</a>.. \r\nJust follow this link below to confirm your email address.\r\n                                  <br />\r\n												<br />\r\n												                                  <a href=\"{verification_url}\" style=\"background:#8337e6; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Verify Account</a>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_fist_name} First Name of the email receiver.<br> {user_last_name} Last Name of the email receiver.<br> {user_full_name} Name of the email receiver.<br> {website_name} Name of our website<br> {verification_url} Url to verify email<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('welcome_registration', 1, 'Welcome Mail on Registration', 'Welcome to {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Account Created!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Thank you for signing up at <a href=\"{website_url}\">{website_name}</a>.<br />\r\n												<br />\r\n												We are thrilled to have you aboard! You have taken a great first step and we are so excited to connect directly with you.<br />\r\n												<br />\r\n												If you require any assistance in using our site, or have any feedback or suggestions, you can email us at {contact_us_email}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_full_name} Full Name of the signed up user.<br>\r\n{user_first_name} FirstName of the signed up user.<br>\r\n{user_last_name} Last Name of the signed up user.<br>\r\n{contact_us_email} - Contact Us Email Address<br/>\r\n{website_name} Name of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n', 1),
('forgot_password', 1, 'Forgot Password Email', 'Password reset instructions at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Forgot Password!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>It seems that you have used forgot password option at <a href=\"{website_url}\">{website_name}</a>.<br />\r\n												\r\n												<div>Please visit the link given below to reset your password. Please note that the link is valid for next 24 hours only.</div>\r\n												<div><br />\r\n													</div><a href=\"{reset_url}\" style=\"background:#8337e6; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click here</a></td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_first_name} First Name of the email receiver<br> {user_last_name} Last Name of the email receiver<br> {user_full_name} Full Name of the email receiver<br> {website_name} Name of our website<br> {website_url} URL of our website<br> {reset_url} URL to reset the password<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('password_changed_successfully', 1, 'Password Changed Successfully', 'Password Changed Successfully at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Congratulations</h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Changed Password!</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {user_full_name} </strong><br />\r\n													You have successfully changed your password, now you can log in with your new password.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Please click here to below link to Login to your Account.<br />\r\n													<a href=\"{login_link}\" style=\"font-size:15px; color:#ff3a59;\">Click here</a></td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">Weâ€˜re here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_first_name} First Name of the email receiver.<br/>\r\n{user_last_name} Last Name of the email receiver.<br/>\r\n{user_full_name} Full Name of the email receiver.<br/>\r\n{website_name} Name of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1),
('account_credited_debited', 1, 'Credits Received/Debited Email', 'Your account has been {txn_type} on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Account Transaction</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your account has been {txn_type} at <a href=\"{website_url}\">{website_name}</a>. Please find the details below:<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">ID</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_id}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Amount<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_amount}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Comment</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_comments}</td>\r\n														</tr> \r\n													</tbody>\r\n												</table></td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_first_name} First Name of the email receiver.<br />\r\n{user_last_name} Last Name of the email receiver.<br />\r\n{user_full_name} Full Name of the email receiver.<br />\r\n{txn_type} - Credited or Debited<br>{txn_id} - Transaction ID<br/>{txn_amount} - Transaction Amount<br>{txn_comments} - Transaction Comments<br>{website_name} - Name of the website.\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('new_teacher_approval_admin', 1, 'New Tutor Approval Request - Admin', 'New Tutor Approval Request on {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Request Received</h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Seller Approval</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n													We have received a new seller approval request on <a href=\"{website_url}\">{website_name}</a>. Please find the details below:</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">\r\n													<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n														<tbody>\r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Reference Number</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{reference_number}</td>\r\n															</tr>                                                        \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Username<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{username}</td>\r\n															</tr>  \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{email}</td>\r\n															</tr> \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td>\r\n															</tr>                                                        \r\n														</tbody>\r\n													</table></td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">Weâ€˜re here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{website_name} Name of the website<br />\r\n{username} \r\n\r\nUsername of the person registered<br />\r\n{email} Email Address of the person registered<br />\r\n{name} Name of the person sent request<br />\r\n{reference_number} \r\n\r\nReference Number of the request<br />\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n', 1),
('teacher_request_status_change_learner', 1, 'Learners - Tutor Request Status Change', 'Your Tutor  Request {new_request_status} at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Updated</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Tutor Approval Status</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name} </h3>Your Tutor approval request has been {new_request_status} corresponding to Reference Number - {reference_number}.{request_comments}<br />\r\n												</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_first_name} - First Name of the email receiver.<br/> {user_last_name} - Last Name of the email receiver.<br/> {user_full_name} - Full Name of the email receiver.<br/> {new_request_status} New Request Status (Approved/Declined) <br> {reference_number} Reference Number of the request<br> {website_name} Name of our website<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('user_admin_password_changed_successfully', 1, 'User/Admin Password Changed Successfully', 'Password reset successfully {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Congratulations</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your password has been changed successfully.<br />\r\n												\r\n												<div>Please click on below link to Login to your account.</div>\r\n												<div><br />\r\n													</div><a href=\"{website_url}\" style=\"background:#8337e6; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click to Login</a></td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_full_name}<br>\r\n{website_name}<br>\r\n{website_url}<br>\r\n{login_link}<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1),
('failed_login_attempt', 1, 'Failed Login Attempt', 'Failed Login Attempt', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\"><a href=\"{website_url}\"> {website_name} </a></h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Are you facing problem logging in?<br />\r\n												You seem to be facing problem logging in at <a href=\"{website_url}\">{website_name}</a><br />\r\n												Please note that your username and password are both case sensitive.<br />\r\n												You can use forgot password feature if you have lost your password.<br />\r\n												If you were not trying logging in, it might be a hacking attempt.<br />\r\n												Also, you should keep your email password secured.<br />\r\n												</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('learner_cancelled_email', 1, 'Learner Cancelled Lesson Email', 'Learner Cancelled Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Learner {action} The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name} </h3>Learner ({learner_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{learner_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1),
('learner_issue_reported_email', 1, 'Learner Issue Reported Email', 'Learner Issue Reported Email at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Learner {action} The Lesson!</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {teacher_name} </strong><br />\r\n													<a href=\"{website_url}\">{website_name}</a>.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Learner ({learner_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n													</td>\r\n											</tr>\r\n											\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Reason:   <br />{learner_comment}\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch if you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1),
('learner_message_to_teacher_email', 1, 'Learner Message To Teacher Email', 'Learner Message To Teacher at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Learner {action} !</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {teacher_name} </strong><br />\r\n													<a href=\"{website_url}\">{website_name}</a>.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Learner ({learner_name}) has sent you message Below:<br />\r\n													</td>\r\n											</tr>\r\n											\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Message:   <br />{learner_message}\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch if you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{learner_name}\r\n{teacher_name}\r\n{learner_message}\r\n{action}', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('learner_reschedule_email', 1, 'Learner Reschedule Lesson Email', 'Learner Reschedule Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Learner {action} The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name}  </h3>Learner ({learner_name}) has {action} the lesson ({lesson_name}) which was scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{learner_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1),
('learner_schedule_email', 1, 'Learner New Schedule Lesson Details Email', 'Learner New Schedule Lesson Details Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Learner {action} The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name}  </h3>Learner ({learner_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{learner_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1),
('teacher_cancelled_email', 1, 'Teacher Cancelled Lesson Email', 'Teacher Cancelled Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Teacher Cancel The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name} </h3>Teacher ({teacher_name}) has cancelled the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('teacher_issue_reported_email', 1, 'Teacher Issue Reported Email', 'Teacher Issue Reported Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Teacher {action} The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has reported an issue the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('teacher_message_to_learner_email', 1, 'Teacher Message To Learner Email', 'Teacher Message To Learner at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Teacher {action} !</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {learner_name} </strong><br />\r\n													<a href=\"{website_url}\">{website_name}</a>.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Teacher ({teacher_name}) has sent you message Below:<br />\r\n													</td>\r\n											</tr>\r\n											\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Message:   <br />{teacher_message}\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch if you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{learner_name}\r\n{teacher_name}\r\n{teacher_message}\r\n{action}', 1),
('teacher_reschedule_email', 1, 'Teacher Reschedule Lesson Email', 'Teacher Reschedule Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Teacher {action} The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('teacher_scheduled_lesson_email', 1, 'Teacher New Schedule Lesson Details Email', 'Teacher New Schedule Lesson Details at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Teacher {action} The Lesson!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has Scheduled  the lesson ({lesson_name}) again on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('giftcard_recipient', 1, 'Gift Card Email To Recipient', '{sender_name} sent you an {website_name} Gift Card!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Gift Card!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {recipient_name}</h3>{sender_name} sent you a {website_name} gift card.\r\n                                  <br />\r\n												<br />\r\n												                                  Gift card code: <span style=\" border: 3px dotted #ddd;padding: 5px 10px;font-weight: bold\">{giftcard_code}</span>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('giftcard_admin', 1, 'Gift Card Purchased', '{website_name} Gift Card Purchased!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Gift Cards!</h2>          \r\n                                                    {giftcard_codes}\r\n                                                </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear admin,</h3>{buyer_name} have purchased {website_name} gift card.\r\n                                  <br />\r\n												                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('giftcard_buyer', 1, 'Gift Card Order Email To Buyer', '{website_name} Gift Card Order Confirmation!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Gift Cards!</h2>          \r\n                                                    {giftcard_codes}\r\n                                                </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {buyer_name}</h3>thank you for buying {website_name} gift card(s).\r\n                                  <br />\r\n												                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('giftcard_redeem_admin', 1, 'Gift Card Redeemed', '{website_name} Gift Card Redeemed!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Redeemed</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Gift Cards!</h2>          \r\n                                                    {giftcard_codes}\r\n                                                </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear admin,</h3>{giftcard_username} have redeemed {giftcard_code} gift card. Amount received {giftcard_amount}\r\n                                  <br />\r\n												                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('blog_comment_status_changed', 1, 'Blog Comment Status Change - Notification', 'Blog Comment Status Changed at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Changed</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Blog Comment Status</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your Comment (for the post \'{post_title}\' / posted on {posted_on_datetime}) status has been changed to {new_status} at <a href=\"{website_url}\">{website_name}</a>.\r\n\r\n                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('blog_contribution_status_changed', 1, 'Blog Contribution Status Change - Notification', 'Blog Contribution Status Changed at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Changed</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Blog Contribution Status</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your blog contribution (posted on {posted_on_datetime}) status has been changed to {new_status} at <a href=\"{website_url}\">{website_name}</a>.\r\n                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('contact_us', 1, 'Contact-Us', 'Contact Us Form Submitted on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #8337e6;\">Contact Us Data</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin</h3>{name} has submitted the contact us form on <a href=\"{website_url}\">{website_name}</a>.<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email Address<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{email_address}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Phone Number</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone_number}</td>\r\n														</tr> \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Message</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{message}</td>\r\n														</tr>   \r\n													</tbody>\r\n												</table></td>                              \r\n                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('test_email', 1, 'Test Email', 'Test Email', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#8337e6;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                    \r\n							<td style=\"padding:20px 40px;\"><a href=\"{website_url}\">{Company_Logo}</a></td>                    \r\n							<td style=\"text-align:right;padding: 40px;\">{social_media_icons}</td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">\r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Test Mail</h5>                                 \r\n												 </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												This is the test email &nbsp;from&nbsp;&nbsp;<a href=\"{website_url}\">{website_name}</a>.\r\n                                  <br />\r\n												<br />\r\n												                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#8337e6;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #8337e6\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_failed_giftcard_attempts`
--

DROP TABLE IF EXISTS `tbl_failed_giftcard_attempts`;
CREATE TABLE `tbl_failed_giftcard_attempts` (
  `giftcard_attempt_user_id` int(11) NOT NULL,
  `giftcard_attempt_code` varchar(15) CHARACTER SET utf8 NOT NULL,
  `giftcard_attempt_ip` varchar(16) CHARACTER SET utf8 NOT NULL,
  `giftcard_attempt_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_failed_login_attempts`
--

DROP TABLE IF EXISTS `tbl_failed_login_attempts`;
CREATE TABLE `tbl_failed_login_attempts` (
  `attempt_username` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `attempt_ip` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `attempt_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_failed_login_attempts`
--

INSERT INTO `tbl_failed_login_attempts` (`attempt_username`, `attempt_ip`, `attempt_time`) VALUES
('welcome', '73.190.110.64', '2019-05-30 18:57:46'),
('poodlewriter@gmail.com', '73.190.110.64', '2019-05-30 18:58:46'),
('poodlewriter@gmail.com', '73.190.110.64', '2019-05-30 18:58:59'),
('poodlewriter@gmail.com', '73.190.110.64', '2019-05-30 18:59:14'),
('welcome@123', '::1', '2019-06-01 11:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq`
--

DROP TABLE IF EXISTS `tbl_faq`;
CREATE TABLE `tbl_faq` (
  `faq_id` int(11) NOT NULL,
  `faq_identifier` varchar(255) NOT NULL,
  `faq_category` tinyint(1) NOT NULL,
  `faq_active` tinyint(1) NOT NULL,
  `faq_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_faq`
--

INSERT INTO `tbl_faq` (`faq_id`, `faq_identifier`, `faq_category`, `faq_active`, `faq_added_on`) VALUES
(1, 'I am a new teacher. How does WeYakYak work?1', 1, 1, '2018-12-26 19:03:39'),
(2, 'I am a new teacher. How do I start a lesson?2', 1, 1, '2018-12-27 12:29:08'),
(4, 'I am a new teacher. How does WeYakYak work?', 2, 1, '2018-12-27 12:35:38'),
(5, 'I am a new teacher. How does WeYakYak work?', 3, 1, '2018-12-27 12:40:37'),
(6, 'I am a new teacher. How does WeYakYak work?3', 1, 1, '2018-12-27 13:50:55');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq_lang`
--

DROP TABLE IF EXISTS `tbl_faq_lang`;
CREATE TABLE `tbl_faq_lang` (
  `faqlang_faq_id` int(11) NOT NULL,
  `faqlang_lang_id` int(11) NOT NULL,
  `faq_title` varchar(255) NOT NULL,
  `faq_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_faq_lang`
--

INSERT INTO `tbl_faq_lang` (`faqlang_faq_id`, `faqlang_lang_id`, `faq_title`, `faq_description`) VALUES
(1, 1, 'I am a new teacher. How does WeYakYak work?1', 'I am a new teacher. How does WeYakYak work?'),
(2, 1, 'I am a new teacher. How do I start a lesson?2', 'I am a new teacher. How do I start a lesson?2'),
(4, 1, 'I am a new teacher. How does WeYakYak work?', 'I am a new teacher. How does WeYakYak work?'),
(5, 1, 'I am a new teacher. How does WeYakYak work?', 'I am a new teacher. How does WeYakYak work?'),
(6, 1, 'I am a new teacher. How does WeYakYak work?3', 'I am a new teacher. How does WeYakYak work?3');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_flashcards`
--

DROP TABLE IF EXISTS `tbl_flashcards`;
CREATE TABLE `tbl_flashcards` (
  `flashcard_id` int(11) NOT NULL,
  `flashcard_user_id` int(11) NOT NULL COMMENT 'for whom',
  `flashcard_created_by_user_id` int(11) NOT NULL,
  `flashcard_slanguage_id` int(11) NOT NULL,
  `flashcard_title` varchar(255) NOT NULL,
  `flashcard_defination` varchar(255) NOT NULL,
  `flashcard_defination_slanguage_id` int(11) NOT NULL,
  `flashcard_pronunciation` varchar(255) NOT NULL,
  `flashcard_notes` varchar(255) NOT NULL,
  `flashcard_accuracy` tinyint(2) NOT NULL,
  `flashcard_accuracy_added_on` datetime NOT NULL,
  `flashcard_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_giftcard_buyers`
--

DROP TABLE IF EXISTS `tbl_giftcard_buyers`;
CREATE TABLE `tbl_giftcard_buyers` (
  `gcbuyer_op_id` int(10) NOT NULL,
  `gcbuyer_order_id` varchar(15) NOT NULL,
  `gcbuyer_name` varchar(200) NOT NULL,
  `gcbuyer_email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `gcbuyer_phone` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_giftcard_recipients`
--

DROP TABLE IF EXISTS `tbl_giftcard_recipients`;
CREATE TABLE `tbl_giftcard_recipients` (
  `gcrecipient_op_id` int(11) NOT NULL,
  `gcrecipient_email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `gcrecipient_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gift_cards`
--

DROP TABLE IF EXISTS `tbl_gift_cards`;
CREATE TABLE `tbl_gift_cards` (
  `giftcard_id` int(11) NOT NULL,
  `giftcard_op_id` int(11) NOT NULL,
  `giftcard_code` varchar(32) NOT NULL,
  `giftcard_amount` decimal(10,2) NOT NULL,
  `giftcard_recipient_user_id` int(11) NOT NULL COMMENT 'user_id who redeem the giftcard',
  `giftcard_used_date` date NOT NULL,
  `giftcard_expiry_date` date NOT NULL COMMENT 'Gift Card Expiry date',
  `giftcard_utxn_id` varchar(200) NOT NULL,
  `giftcard_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_issues_reported`
--

DROP TABLE IF EXISTS `tbl_issues_reported`;
CREATE TABLE `tbl_issues_reported` (
  `issrep_id` bigint(20) NOT NULL,
  `issrep_slesson_id` int(11) NOT NULL,
  `issrep_comment` varchar(255) NOT NULL,
  `issrep_reported_by` tinyint(4) NOT NULL,
  `issrep_status` tinyint(4) NOT NULL,
  `issrep_added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `issrep_updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_languages`
--

DROP TABLE IF EXISTS `tbl_languages`;
CREATE TABLE `tbl_languages` (
  `language_id` int(11) NOT NULL,
  `language_code` varchar(4) CHARACTER SET utf8mb4 NOT NULL,
  `language_flag` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `language_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `language_active` tinyint(1) NOT NULL DEFAULT '1',
  `language_css` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `language_layout_direction` varchar(3) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_languages`
--

INSERT INTO `tbl_languages` (`language_id`, `language_code`, `language_flag`, `language_name`, `language_active`, `language_css`, `language_layout_direction`) VALUES
(1, 'EN', 'gb.png', 'English', 1, '', 'ltr'),
(2, 'AR', 'ar.png', 'Arabic', 1, '', 'rtl');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language_labels`
--

DROP TABLE IF EXISTS `tbl_language_labels`;
CREATE TABLE `tbl_language_labels` (
  `label_id` int(11) NOT NULL,
  `label_key` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `label_lang_id` int(11) NOT NULL,
  `label_caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_language_labels`
--

INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(1, 'LBL_Username_Or_Email', 1, 'Username Or Email'),
(2, 'LBL_EMAIL_ADDRESS', 1, 'Email Address'),
(3, 'LBL_Password', 1, 'Password'),
(4, 'LBL_Remember_Me', 1, 'Remember Me'),
(5, 'LBL_LOGIN', 1, 'Sign In'),
(6, 'LBL_Do_you_want_to_remove', 1, 'Do You Want To Remove'),
(7, 'LBL_Do_you_want_to_remove_this_option', 1, 'Do You Want To Remove This Option'),
(8, 'LBL_Do_you_want_to_remove_this_shop', 1, 'Do You Want To Remove This Shop'),
(9, 'LBL_Do_you_want_to_remove_this_product', 1, 'Do You Want To Remove This Product'),
(10, 'LBL_Do_you_want_to_remove_this_category', 1, 'Do You Want To Remove This Category'),
(11, 'LBL_Do_you_want_to_reset_settings', 1, 'Do You Want To Reset Settings'),
(12, 'LBL_Do_you_want_to_activate_status', 1, 'Do You Want To Activate Status'),
(13, 'LBL_Do_you_want_to_update', 1, 'Do You Want To Update'),
(14, 'LBL_Do_you_want_to_delete', 1, 'Do You Want To Delete'),
(15, 'LBL_Do_you_want_to_delete_image', 1, 'Do You Want To Delete Image'),
(16, 'LBL_Do_you_want_to_delete_background_image', 1, 'Do You Want To Delete Background Image'),
(17, 'LBL_Do_you_want_to_delete_logo', 1, 'Do You Want To Delete Logo'),
(18, 'LBL_Do_you_want_to_delete_banner', 1, 'Do You Want To Delete Banner'),
(19, 'LBL_Do_you_want_to_delete_icon', 1, 'Do You Want To Delete Icon'),
(20, 'LBL_Do_you_want_to_set_default', 1, 'Do You Want To Set Default'),
(21, 'LBL_Set_as_main_product', 1, 'Set As Main Product'),
(22, 'LBL_Please_Select_any_Plan', 1, 'Please Select Any Plan'),
(23, 'LBL_You_have_already_Bought_this_plan,_Please_choose_some_other_Plan', 1, 'You Have Already Bought This Plan, Please Choose Some Other Plan'),
(24, 'LBL_Invalid_Request!', 1, 'Invalid Request!'),
(25, 'LBL_Please_Wait...', 1, 'Please Wait...'),
(26, 'LBL_Do_you_really_want_to', 1, 'Do You Really Want To'),
(27, 'LBL_the_request', 1, 'The Request'),
(28, 'LBL_Are_you_sure_to_cancel_this_order', 1, 'Are You Sure To Cancel This Order'),
(29, 'LBL_Do_you_want_to_replace_current_content_to_default_content', 1, 'Do You Want To Replace Current Content To Default Content'),
(30, 'LBL_Processing...', 1, 'Processing...'),
(31, 'LBL_Preferred_Dimensions_%s', 1, 'Preferred Dimensions %s'),
(32, 'LBL_Do_you_want_to_restore', 1, 'Do You Want To Restore'),
(33, 'LBL_Msg_Thanks_for_sharing', 1, 'Msg Thanks For Sharing'),
(34, 'VLBL_is_mandatory', 1, 'Is Mandatory'),
(35, 'VLBL_Please_enter_valid_email_ID_for', 1, 'Please Enter Valid Email Id For'),
(36, 'VLBL_Only_characters_are_supported_for', 1, 'Only Characters Are Supported For'),
(37, 'VLBL_Please_enter_integer_value_for', 1, 'Please Enter Integer Value For'),
(38, 'VLBL_Please_enter_numeric_value_for', 1, 'Please Enter Numeric Value For'),
(39, 'VLBL_must_start_with_a_letter_and_can_contain_only_alphanumeric_characters._Length_must_be_between_4_to_20_characters', 1, 'Must Start With A Letter And Can Contain Only Alphanumeric Characters. Length Must Be Between 4 To 20 Characters'),
(40, 'VLBL_Length_Must_be_between_6_to_20_characters', 1, 'Length Must Be Between 6 To 20 Characters'),
(41, 'VLBL_Length_Invalid_value_for', 1, 'Length Invalid Value For'),
(42, 'VLBL_should_not_be_same_as', 1, 'Should Not Be Same As'),
(43, 'VLBL_must_be_same_as', 1, 'Must Be Same As'),
(44, 'VLBL_must_be_greater_than_or_equal_to', 1, 'Must Be Greater Than Or Equal To'),
(45, 'VLBL_must_be_greater_than', 1, 'Must Be Greater Than'),
(46, 'VLBL_must_be_less_than_or_equal_to', 1, 'Must Be Less Than Or Equal To'),
(47, 'VLBL_must_be_less_than', 1, 'Must Be Less Than'),
(48, 'VLBL_Length_of', 1, 'Length Of'),
(49, 'VLBL_Value_of', 1, 'Value Of'),
(50, 'VLBL_must_be_between', 1, 'Must Be Between'),
(51, 'VLBL_and', 1, 'And'),
(52, 'VLBL_Please_select', 1, 'Please Select'),
(53, 'VLBL_to', 1, 'To'),
(54, 'VLBL_options', 1, 'Options'),
(55, 'LBL_Do_you_want_to_restore_database_to_this_record', 1, 'Do You Want To Restore Database To This Record'),
(56, 'LBL_Do_you_want_to_change_request_status', 1, 'Do You Want To Change Request Status'),
(57, 'LBL_Do_you_want_to_truncate_User_Data', 1, 'Do You Want To Truncate User Data'),
(58, 'LBL_Default_Admin_View', 1, 'Default Admin View'),
(59, 'LBL_Sign_In', 1, 'Sign In'),
(60, 'LBL_Username', 1, 'Username'),
(61, 'LBL_Forgot_Password?', 1, 'Forgot Password?'),
(62, 'LBL_Enter_Your_Email_Address', 1, 'Enter Your Email Address'),
(63, 'LBL_Send_Reset_Pasword_Email', 1, 'Send Reset Pasword Email'),
(64, 'LBL_Forgot_Your_Password?', 1, 'Forgot Your Password?'),
(65, 'LBL_Enter_The_E-mail_Address_Associated_With_Your_Account', 1, 'Enter The E-mail Address Associated With Your Account'),
(66, 'LBL_Back_to_Login', 1, 'Back To Login'),
(67, 'MSG_Your_request_to_reset_password_has_already_been_placed_within_last_24_hours._Please_check_your_emails_or_retry_after_24_hours_of_your_previous_request', 1, 'Your Request To Reset Password Has Already Been Placed Within Last 24 Hours. Please Check Your Emails Or Retry After 24 Hours Of Your Previous Request'),
(68, 'ERR_Email_Template_Not_Found', 1, 'Email Template Not Found'),
(69, 'MSG_YOUR_PASSWORD_RESET_INSTRUCTIONS_TO_YOUR_EMAIL', 1, 'Your Password Reset Instructions To Your Email'),
(70, 'MSG_Link_is_invalid_or_expired!', 1, 'Link Is Invalid Or Expired!'),
(71, 'LBL_Reset_Pasword', 1, 'Reset Pasword'),
(72, 'LBL_Reset_Password', 1, 'Reset Password'),
(73, 'MSG_Link_is_invalid_or_expired', 1, 'Link Is Invalid Or Expired'),
(74, 'LBL_Login_Successful', 1, 'Login Successful'),
(75, 'LBL_View_Store', 1, 'View Store'),
(76, 'LBL_Clear_Cache', 1, 'Clear Cache'),
(77, 'LBL_Notifications', 1, 'Notifications'),
(78, 'LBL_View_all', 1, 'View All'),
(79, 'LBL_Select_Language', 1, 'Select Language'),
(80, 'LBL_Update_Sitemap', 1, 'Update Sitemap'),
(81, 'LBL_Logout', 1, 'Logout'),
(82, 'LBL_You_Are_Logged_Out_Successfully', 1, 'You Are Logged Out Successfully'),
(83, 'LBL_Cache_has_been_cleared', 1, 'Cache Has Been Cleared'),
(84, 'LBL_View_Portal', 1, 'View Portal'),
(85, 'Msg_Please_Wait_We_are_redirecting_you...', 1, 'Please Wait We Are Redirecting You...'),
(86, 'LBL_Welcome', 1, 'Welcome'),
(87, 'LBL_View_Profile', 1, 'View Profile'),
(88, 'LBL_Change_Password', 1, 'Change Password'),
(89, 'LBL_Dashboard', 1, 'Dashboard'),
(90, 'LBL_Users', 1, 'Users'),
(91, 'LBL_Cms', 1, 'CMS'),
(92, 'LBL_Content_Pages', 1, 'Content Pages'),
(93, 'LBL_Content_Blocks', 1, 'Content Blocks'),
(94, 'LBL_Navigation_Management', 1, 'Navigation Management'),
(95, 'LBL_Countries_Management', 1, 'Countries Management'),
(96, 'LBL_States_Management', 1, 'States Management'),
(97, 'LBL_Social_Platforms_Management', 1, 'Social Platforms Management'),
(98, 'LBL_Discount_Coupons', 1, 'Discount Coupons'),
(99, 'LBL_Language_Labels', 1, 'Language Labels'),
(100, 'LBL_Settings', 1, 'Settings'),
(101, 'LBL_General_Settings', 1, 'General Settings'),
(102, 'LBL_Payment_Methods', 1, 'Payment Methods'),
(103, 'LBL_Currency_Management', 1, 'Currency Management'),
(104, 'LBL_Email_Templates_Management', 1, 'Email Templates Management'),
(105, 'LBL_Misc', 1, 'Misc'),
(106, 'LBL_Meta_Tags_Management', 1, 'Meta Tags Management'),
(107, 'LBL_Url_Rewriting', 1, 'Url Rewriting'),
(108, 'LBL_Blog', 1, 'Blog'),
(109, 'LBL_Blog_Post_Categories', 1, 'Blog Post Categories'),
(110, 'LBL_Blog_Posts', 1, 'Blog Posts'),
(111, 'LBL_Blog_Contributions', 1, 'Blog Contributions'),
(112, 'LBL_Blog_Comments', 1, 'Blog Comments'),
(113, 'LBL_Manage_Admin_Users', 1, 'Manage Admin Users'),
(114, 'LBL_Order_Sales', 1, 'Order Sales'),
(115, 'LBL_This_Month', 1, 'This Month'),
(116, 'MSG_Invalid_Username_or_Password', 1, 'Invalid Username Or Password'),
(117, 'LBL_Unauthorized_Access', 1, 'Unauthorized Access'),
(118, 'LBL_Record_Added_Successfully', 1, 'Record Added Successfully'),
(119, 'LBL_Record_Updated_Successfully', 1, 'Record Updated Successfully'),
(120, 'LBL_No_Record_Found', 1, 'No Record Found'),
(121, 'LBL_Invalid_Request_Id', 1, 'Invalid Request Id'),
(122, 'LBL_Invalid_Request', 1, 'Invalid Request'),
(123, 'LBL_Record_Deleted_Successfully', 1, 'Record Deleted Successfully'),
(124, 'LBL_Invalid_Action', 1, 'Invalid Action'),
(125, 'LBL_Setup_Successful', 1, 'Setup Successful'),
(126, 'LBL_Export_Successful', 1, 'Export Successful'),
(127, 'LBL_Seller_Approval_Requests', 1, 'Seller Approval Requests'),
(128, 'LBL_Name_Or_Email', 1, 'Name Or Email'),
(129, 'LBL_Does_Not_Matter', 1, 'Does Not Matter'),
(130, 'LBL_Active', 1, 'Active'),
(131, 'LBL_In-active', 1, 'In-active'),
(132, 'LBL_Yes', 1, 'Yes'),
(133, 'LBL_No', 1, 'No'),
(134, 'LBL_Learner', 1, 'Learner'),
(135, 'LBL_Tutor', 1, 'Tutor'),
(136, 'LBL_Active_Users', 1, 'Active Users'),
(137, 'LBL_Email_Verified', 1, 'Email Verified'),
(138, 'LBL_User_Type', 1, 'User Type'),
(139, 'LBL_Reg._Date_From', 1, 'Reg. Date From'),
(140, 'LBL_Reg._Date_To', 1, 'Reg. Date To'),
(141, 'LBL_Search', 1, 'Search'),
(142, 'LBL_Clear_Search', 1, 'Clear Search'),
(143, 'LBL_Manage_Users', 1, 'Manage Users'),
(144, 'LBL_Home', 1, 'Home'),
(145, 'LBL_Search...', 1, 'Search...'),
(146, 'LBL_S.No.', 1, 'S.no.'),
(147, 'LBL_User', 1, 'User'),
(148, 'LBL_Reg._Date', 1, 'Reg. Date'),
(149, 'LBL_Status', 1, 'Status'),
(150, 'LBL_verified', 1, 'Verified'),
(151, 'LBL_Action', 1, 'Action'),
(152, 'LBL_No_Records_Found', 1, 'No Records Found'),
(153, 'LBL_My_Profile', 1, 'My Profile'),
(154, 'LBL_Profile', 1, 'Profile'),
(155, 'LBL_Loading..', 1, 'Loading..'),
(156, 'LBL_Profile_Picture', 1, 'Profile Picture'),
(157, 'LBL_Update_Profile_Picture', 1, 'Update Profile Picture'),
(158, 'LBL_Rotate_Left', 1, 'Rotate Left'),
(159, 'LBL_Rotate_Right', 1, 'Rotate Right'),
(160, 'LBL_Email', 1, 'Email'),
(161, 'LBL_Full_Name', 1, 'Full Name'),
(162, 'LBL_SAVE_CHANGES', 1, 'Save Changes'),
(163, 'LBL_Remove', 1, 'Remove'),
(164, 'LBL_Current_Password', 1, 'Current Password'),
(165, 'LBL_New_Password', 1, 'New Password'),
(166, 'LBL_Confirm_New_Password', 1, 'Confirm New Password'),
(167, 'LBL_Confirm_Password_Not_Matched!', 1, 'Confirm Password Not Matched!'),
(168, 'LBL_Change', 1, 'Change'),
(169, 'LBL_changePassword', 1, 'Changepassword'),
(170, 'LBL_Your_current_Password_mis-matched!', 1, 'Your Current Password Mis-matched!'),
(171, 'LBL_Password_Updated_Successfully', 1, 'Password Updated Successfully'),
(172, 'MSG_Setup_successful', 1, 'Setup Successful'),
(173, 'LBL_Social', 1, 'Social'),
(174, 'LBL_Site_Language', 1, 'Site Language'),
(175, 'LBL_Site_Language', 2, 'Site Language'),
(176, 'LBL_Social', 2, 'Social'),
(177, 'LBL_Tutor_Approval_Requests', 1, 'Tutor Approval Requests'),
(178, 'LBL_Keyword', 1, 'Keyword'),
(179, 'LBL_All', 1, 'All'),
(180, 'LBL_Pending', 1, 'Pending'),
(181, 'LBL_Approved', 1, 'Approved'),
(182, 'LBL_Cancelled', 1, 'Cancelled'),
(183, 'LBL_Date_From', 1, 'Date From'),
(184, 'LBL_Date_To', 1, 'Date To'),
(185, 'LBL_Manage_-_Tutor_Approval_Requests_', 1, 'Manage - Tutor Approval Requests '),
(186, 'LBL_Requests_List', 1, 'Requests List'),
(187, 'LBL_Sr._No', 1, 'Sr. No'),
(188, 'LBL_Reference_Number', 1, 'Reference Number'),
(189, 'LBL_Name', 1, 'Name'),
(190, 'LBL_Username/Email', 1, 'Username/email'),
(191, 'LBL_Requested_On', 1, 'Requested On'),
(192, 'MSG_COULD_NOT_SAVE_FILE', 1, 'Could Not Save File'),
(193, 'MSG_File_uploaded_successfully', 1, 'File Uploaded Successfully'),
(194, 'LBL_Sent_Emails', 1, 'Sent Emails'),
(195, 'LBL_Sent_Emails_List', 1, 'Sent Emails List'),
(196, 'LBL_Sr.', 1, 'Sr.'),
(197, 'LBL_Subject', 1, 'Subject'),
(198, 'LBL_Sent_To', 1, 'Sent To'),
(199, 'LBL_Email_Headers', 1, 'Email Headers'),
(200, 'LBL_Sent_On', 1, 'Sent On'),
(201, 'LBL_View_Details', 1, 'View Details'),
(202, 'LBL_Home_Page_Slides_Management', 1, 'Home Page Slides Management'),
(203, 'LBL_Manage_Home_Page_Slides', 1, 'Manage Home Page Slides'),
(204, 'LBL_Slides', 1, 'Slides'),
(205, 'LBL_Slides_List', 1, 'Slides List'),
(206, 'LBL_Edit', 1, 'Edit'),
(207, 'LBL_Add_New_Slide', 1, 'Add New Slide'),
(208, 'LBL_Title', 1, 'Title'),
(209, 'LBL_URL', 1, 'Url'),
(210, 'LBL_Slide_Identifier', 1, 'Slide Identifier'),
(211, 'LBL_Slide_URL', 1, 'Slide Url'),
(212, 'LBL_Same_Window', 1, 'Same Window'),
(213, 'LBL_New_Window', 1, 'New Window'),
(214, 'LBL_Open_In', 1, 'Open In'),
(215, 'LBL_Slide_Setup', 1, 'Slide Setup'),
(216, 'LBL_General', 1, 'General'),
(217, 'LBL_English', 1, 'English'),
(218, 'LBL_Arabic', 1, 'Arabic'),
(219, 'LBL_Media', 1, 'Media'),
(220, 'LBL_Delete', 1, 'Delete'),
(221, 'LBL_Slide_Title', 1, 'Slide Title'),
(222, 'LBL_Update', 1, 'Update'),
(223, 'LBL_All_Languages', 1, 'All Languages'),
(224, 'LBL_Language', 1, 'Language'),
(225, 'LBL_Desktop', 1, 'Desktop'),
(226, 'LBL_Ipad', 1, 'Ipad'),
(227, 'LBL_Mobile', 1, 'Mobile'),
(228, 'LBL_Display_For', 1, 'Display For'),
(229, 'LBL_Slide_Banner_Image', 1, 'Slide Banner Image'),
(230, 'LBL_Upload_File', 1, 'Upload File'),
(231, 'LBL_Slide_Image_Setup', 1, 'Slide Image Setup'),
(232, 'LBL_Screen', 1, 'Screen'),
(233, 'MSG_Deleted_successfully', 1, 'Deleted Successfully'),
(234, 'LBL_Banners', 1, 'Banners'),
(235, 'LBL_Manage_Banner_Locations', 1, 'Manage Banner Locations'),
(236, 'LBL_Banner_Locations_List', 1, 'Banner Locations List'),
(237, 'Lbl_Banner_Layouts_Instructions', 1, 'Banner Layouts Instructions'),
(238, 'LBL_Preffered_Width_(in_pixels)', 1, 'Preffered Width (in Pixels)'),
(239, 'LBL_Preffered_Height_(in_pixels)', 1, 'Preffered Height (in Pixels)'),
(240, 'LBL_Banner_Location_Identifier', 1, 'Banner Location Identifier'),
(241, 'LBL_Setup', 1, 'Setup'),
(242, 'LBL_Banner_Location_Title', 1, 'Banner Location Title'),
(243, 'LBL_Banner_Setup', 1, 'Banner Setup'),
(244, 'LBL_Manage_Banner', 1, 'Manage Banner'),
(245, 'LBL_Listing', 1, 'Listing'),
(246, 'LBL_Banner', 1, 'Banner'),
(247, 'Home_Page_After_Third_Layout', 1, 'Page After Third Layout'),
(248, 'LBL_Add_New', 1, 'Add New'),
(249, 'LBL_Back', 1, 'Back'),
(250, 'LBL_Processing', 1, 'Processing'),
(251, 'LBL_Promotion', 1, 'Promotion'),
(252, 'LBL_Image', 1, 'Image'),
(253, 'LBL_Target', 1, 'Target'),
(254, 'LBL_Tag_Setups', 1, 'Tag Setups'),
(255, 'LBL_Banner_Title', 1, 'Banner Title'),
(256, 'LBL_Banner_Image', 1, 'Banner Image'),
(257, 'LBL_Preferred_Dimensions_are', 1, 'Preferred Dimensions Are'),
(258, 'LBL_Banner_Location_Setup', 1, 'Banner Location Setup'),
(259, 'Home_Page_After_First_Layout', 1, 'Page After First Layout'),
(260, 'LBL_Banner_Description', 1, 'Banner Description'),
(261, 'LBL_Banner_Button_Caption', 1, 'Banner Button Caption'),
(262, 'LBL_Banner_Button_Link', 1, 'Banner Button Link'),
(263, 'LBL_Testimonials', 1, 'Testimonials'),
(264, 'LBL_Manage_Testimonials', 1, 'Manage Testimonials'),
(265, 'LBL_Testimonials_Listing', 1, 'Testimonials Listing'),
(266, 'LBL_Add_Testimonial', 1, 'Add Testimonial'),
(267, 'LBL_Sr_no.', 1, 'Sr No.'),
(268, 'LBL_Testimonial_Identifier', 1, 'Testimonial Identifier'),
(269, 'LBL_Testimonial_Title', 1, 'Testimonial Title'),
(270, 'LBL_Testimonial_User_Name', 1, 'Testimonial User Name'),
(271, 'LBL_Testimonial_Setup', 1, 'Testimonial Setup'),
(272, 'LBL_Testimonial_Text', 1, 'Testimonial Text'),
(273, 'LBL_Upload_Image', 1, 'Upload Image'),
(274, 'LBL_Preferred_Dimensions', 1, 'Preferred Dimensions'),
(275, 'LBL_Testimonial_Media_setup', 1, 'Testimonial Media Setup'),
(276, 'LBL_Manage_Labels', 1, 'Manage Labels'),
(277, 'LBL_Label', 1, 'Label'),
(278, 'LBL_Language_labels_List', 1, 'Language Labels List'),
(279, 'LBL_Import', 1, 'Import'),
(280, 'LBL_Export', 1, 'Export'),
(281, 'LBL_Key', 1, 'Key'),
(282, 'LBL_Caption', 1, 'Caption'),
(283, 'LBL_Showing', 1, 'Showing'),
(284, 'LBL_to', 1, 'To'),
(285, 'LBL_of', 1, 'Of'),
(286, 'LBL_Entries', 1, 'Entries'),
(288, 'LBL_Export', 2, 'Export'),
(291, 'LBL_Manage_Countries', 1, 'Manage Countries'),
(292, 'LBL_Countries', 1, 'Countries'),
(293, 'LBL_Country_Listing', 1, 'Country Listing'),
(294, 'LBL_Add_Country', 1, 'Add Country'),
(295, 'LBL_Country_Code', 1, 'Country Code'),
(296, 'LBL_Country_Name', 1, 'Country Name'),
(297, 'LBL_Currency', 1, 'Currency'),
(298, 'LBL_Country_Setup', 1, 'Country Setup'),
(299, 'LBL_Updated_Successfully', 1, 'Updated Successfully'),
(300, 'LBL_Request_Processing...', 1, 'Request Processing...'),
(301, 'LBL_is_mandatory', 1, 'Is Mandatory'),
(302, 'LBL_Please_enter_valid_email_ID_for', 1, 'Please Enter Valid Email Id For'),
(303, 'LBL_Sign_Up', 1, 'Sign Up'),
(304, 'MSG_Back_To_Home', 1, 'Back To Home'),
(305, 'LBL_Email_ID', 1, 'Email Id'),
(306, 'MSG_Valid_password', 1, 'Valid Password'),
(307, 'LBL_I_accept_Terms_&_Conditions', 1, 'I Accept Terms & Conditions'),
(308, 'MSG_Terms_Condition_is_mandatory.', 1, 'Terms Condition Is Mandatory.'),
(309, 'LBL_Register', 1, 'Register'),
(310, 'LBL_Sign_in_with_Facebook', 1, 'Sign In With Facebook'),
(311, 'LBL_Sign_in_with_Google+', 1, 'Sign In With Google+'),
(312, 'LBL_Or', 1, 'Or'),
(313, 'LBL_Already_have_an_account?', 1, 'Already Have An Account?'),
(314, 'LBL_Registeration_Successfull', 1, 'Registeration Successfull'),
(315, 'MSG_SUCCESS_USER_SIGNUP_EMAIL_VERIFICATION_PENDING', 1, 'Success User Signup Email Verification Pending'),
(316, 'MSG_Congratulations', 1, 'Congratulations'),
(317, 'LBL_N:', 1, 'N:'),
(318, 'LBL_UN:', 1, 'Un:'),
(319, 'LBL_Email:', 1, 'Email:'),
(320, 'LBL_User_ID:', 1, 'User Id:'),
(321, 'LBL_Inactive', 1, 'Inactive'),
(322, 'LBL_View', 1, 'View'),
(323, 'LBL_Transactions', 1, 'Transactions'),
(324, 'LBL_Log_into_store', 1, 'Log Into Store'),
(325, 'LBL_User_View', 1, 'User View'),
(326, 'LBL_User_Phone', 1, 'User Phone'),
(327, 'LBL_Profile_Info', 1, 'Profile Info'),
(328, 'LBL_User_Date', 1, 'User Date'),
(329, 'LBL_Address', 1, 'Address'),
(330, 'LBL_Adress2', 1, 'Adress2'),
(331, 'LBL_Zip', 1, 'Zip'),
(332, 'LBL_Country', 1, 'Country'),
(333, 'LBL_State', 1, 'State'),
(334, 'LBL_City', 1, 'City'),
(335, 'LBL_Customer_Name', 1, 'Customer Name'),
(336, 'LBL_Date_Of_Birth', 1, 'Date Of Birth'),
(337, 'LBL_Phone', 1, 'Phone'),
(338, 'LBL_User_Setup', 1, 'User Setup'),
(339, 'LBL_Transaction_Pending', 1, 'Transaction Pending'),
(340, 'LBL_Transaction_Completed', 1, 'Transaction Completed'),
(341, 'LBL_User_Transactions', 1, 'User Transactions'),
(342, 'LBL_Transaction_Id', 1, 'Transaction Id'),
(343, 'LBL_Date', 1, 'Date'),
(344, 'LBL_Credit', 1, 'Credit'),
(345, 'LBL_Debit', 1, 'Debit'),
(346, 'LBL_Balance', 1, 'Balance'),
(347, 'LBL_Description', 1, 'Description'),
(348, 'LBL_Manage_States', 1, 'Manage States'),
(349, 'LBL_States', 1, 'States'),
(350, 'LBL_State_Listing', 1, 'State Listing'),
(351, 'LBL_Add_State', 1, 'Add State'),
(352, 'LBL_State_Identifier', 1, 'State Identifier'),
(353, 'LBL_State_Name', 1, 'State Name'),
(354, 'LBL_State_Code', 1, 'State Code'),
(355, 'LBL_Page_Identifier', 1, 'Page Identifier'),
(356, 'LBL_Manage_Content_Pages', 1, 'Manage Content Pages'),
(357, 'LBL_Add_Page', 1, 'Add Page'),
(358, 'Lbl_Layouts_Instructions', 1, 'Layouts Instructions'),
(359, 'LBL_Pages', 1, 'Pages'),
(360, 'LBL_Layout_Type', 1, 'Layout Type'),
(361, 'LBL_Content_Page_Layout1', 1, 'Content Page Layout1'),
(362, 'LBL_Content_Page_Layout2', 1, 'Content Page Layout2'),
(363, 'LBL_Content_Pages_Setup', 1, 'Content Pages Setup'),
(364, 'LBL_Page_Title', 1, 'Page Title'),
(365, 'LBL_Backgroud_Image', 1, 'Backgroud Image'),
(366, 'LBL_Background_Image_Title', 1, 'Background Image Title'),
(367, 'LBL_Background_Image_Description', 1, 'Background Image Description'),
(368, 'LBL_Content_Block_1', 1, 'Content Block 1'),
(369, 'LBL_Content_Block_2', 1, 'Content Block 2'),
(370, 'LBL_Content_Block_3', 1, 'Content Block 3'),
(371, 'LBL_Content_Block_4', 1, 'Content Block 4'),
(372, 'LBL_Content_Block_5', 1, 'Content Block 5'),
(373, 'LBL_This_will_be_displayed_on_your_cms_Page', 1, 'This Will Be Displayed On Your Cms Page'),
(374, 'LBL_Uploaded_Successfully', 1, 'Uploaded Successfully'),
(375, 'LBL_Don\'t_have_an_account?', 1, 'Don\'t Have An Account?'),
(376, 'ERR_INVALID_USERNAME_OR_PASSWORD', 1, 'Invalid Username Or Password'),
(377, 'LBL_Click_Here', 1, 'Click Here'),
(378, 'MSG_Your_Account_verification_is_pending_{clickhere}', 1, 'Your Account Verification Is Pending {clickhere}'),
(379, 'ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED_OR_NOT_ACTIVE', 1, 'Your Account Has Been Deactivated Or Not Active'),
(380, 'MSG_LOGIN_SUCCESSFULL', 1, 'Login Successfull'),
(381, 'LBL_Manage_Content_Blocks', 1, 'Manage Content Blocks'),
(382, 'LBL_Content_Block', 1, 'Content Block'),
(383, 'LBL_Content_Block_Setup', 1, 'Content Block Setup'),
(384, 'LBL_Tutor_Banner_Slogan', 1, 'Tutor Banner Slogan'),
(385, 'LBL_Learner_Banner_Slogan', 1, 'Learner Banner Slogan'),
(386, 'LBL_Page_Content', 1, 'Page Content'),
(387, 'LBL_Manage_Social_Platforms', 1, 'Manage Social Platforms'),
(388, 'LBL_Social_Platform', 1, 'Social Platform'),
(389, 'LBL_Processing....', 1, 'Processing....'),
(390, 'LBL_Social_Platforms_Listing', 1, 'Social Platforms Listing'),
(391, 'LBL_Add_New_Social_Platform', 1, 'Add New Social Platform'),
(392, 'LBL_Identifier', 1, 'Identifier'),
(393, 'LBL_Icon_Type_From_CSS', 1, 'Icon Type From Css'),
(394, 'LBL_Facebook_Icon', 1, 'Facebook Icon'),
(395, 'LBL_Twitter_Icon', 1, 'Twitter Icon'),
(396, 'LBL_Youtube_Icon', 1, 'Youtube Icon'),
(397, 'LBL_Instagram_Icon', 1, 'Instagram Icon'),
(398, 'LBL_Google_Plus_Icon', 1, 'Google Plus Icon'),
(399, 'LBL_Pinterest_Icon', 1, 'Pinterest Icon'),
(400, 'LBL_If_you_have_to_add_a_platform_icon_except_this_select_list', 1, 'If You Have To Add A Platform Icon Except This Select List'),
(401, 'LBL_Social_Platform_Setup', 1, 'Social Platform Setup'),
(402, 'LBL_Social_Platform_Setup_Successful', 1, 'Social Platform Setup Successful'),
(403, 'LBL_Icon_Image', 1, 'Icon Image'),
(404, 'LBL_This_will_be_displayed_in_30x30_on_your_store.', 1, 'This Will Be Displayed In 30x30 On Your Store.'),
(405, 'LBL_Image_Setup', 1, 'Image Setup'),
(406, 'LBL_U', 1, 'U'),
(407, 'LBL_E', 1, 'E'),
(408, 'LBL_Admin_Users', 1, 'Admin Users'),
(409, 'LBL_Admin_User_Listing', 1, 'Admin User Listing'),
(410, 'LBL_Add_Admin_User', 1, 'Add Admin User'),
(411, 'LBL_Admin_User_Setup', 1, 'Admin User Setup'),
(412, 'MSG_ERROR_INVALID_REQUEST', 1, 'Error Invalid Request'),
(413, 'MSG_USER_COULD_NOT_BE_SET', 1, 'User Could Not Be Set'),
(414, 'LBL_Processing...', 2, 'Processing...'),
(415, 'LBL_Request_Processing...', 2, 'Request Processing...'),
(416, 'LBL_is_mandatory', 2, 'Is Mandatory'),
(417, 'LBL_Please_enter_valid_email_ID_for', 2, 'Please Enter Valid Email Id For'),
(418, 'VLBL_Only_characters_are_supported_for', 2, 'Only Characters Are Supported For'),
(419, 'VLBL_Please_enter_integer_value_for', 2, 'Please Enter Integer Value For'),
(420, 'VLBL_Please_enter_numeric_value_for', 2, 'Please Enter Numeric Value For'),
(421, 'VLBL_must_start_with_a_letter_and_can_contain_only_alphanumeric_characters._Length_must_be_between_4_to_20_characters', 2, 'Must Start With A Letter And Can Contain Only Alphanumeric Characters. Length Must Be Between 4 To 20 Characters'),
(422, 'VLBL_Length_Must_be_between_6_to_20_characters', 2, 'Length Must Be Between 6 To 20 Characters'),
(423, 'VLBL_Length_Invalid_value_for', 2, 'Length Invalid Value For'),
(424, 'VLBL_should_not_be_same_as', 2, 'Should Not Be Same As'),
(425, 'VLBL_must_be_same_as', 2, 'Must Be Same As'),
(426, 'VLBL_must_be_greater_than_or_equal_to', 2, 'Must Be Greater Than Or Equal To'),
(427, 'VLBL_must_be_greater_than', 2, 'Must Be Greater Than'),
(428, 'VLBL_must_be_less_than_or_equal_to', 2, 'Must Be Less Than Or Equal To'),
(429, 'VLBL_must_be_less_than', 2, 'Must Be Less Than'),
(430, 'VLBL_Length_of', 2, 'Length Of'),
(431, 'VLBL_Value_of', 2, 'Value Of'),
(432, 'VLBL_must_be_between', 2, 'Must Be Between'),
(433, 'VLBL_and', 2, 'And'),
(434, 'VLBL_Please_select', 2, 'Please Select'),
(435, 'LBL_Logout', 2, 'Logout'),
(436, 'MSG_Back_To_Home', 2, 'Back To Home'),
(437, 'LBL_LOGIN', 2, 'Sign In'),
(438, 'LBL_Sign_Up', 2, 'Sign Up1'),
(439, 'BTN_SUBMIT', 1, 'Submit'),
(440, 'LBL_Forgot_Password', 1, 'Forgot Password'),
(441, 'LBL_Forgot_Password_Msg', 1, 'Forgot Password Msg'),
(442, 'LBL_Sent_Email_Detail', 1, 'Sent Email Detail'),
(443, 'LBL_Template_Name', 1, 'Template Name'),
(444, 'LBL_Headers', 1, 'Headers'),
(445, 'LBL_Content', 1, 'Content'),
(446, 'LBL_Example_password', 1, 'Example Password'),
(447, 'LBL_Reset_Password_Msg', 1, 'Reset Password Msg'),
(448, 'MSG_PASSWORD_CHANGED_SUCCESSFULLY', 1, 'Password Changed Successfully'),
(449, 'MSG_INVALID_RESET_PASSWORD_REQUEST', 1, 'Invalid Reset Password Request'),
(450, 'MSG_General', 1, 'General'),
(451, 'MSG_Local', 1, 'Local'),
(452, 'MSG_Seo', 1, 'Seo'),
(453, 'MSG_Options', 1, 'Options'),
(454, 'MSG_Live_Chat', 1, 'Live Chat'),
(455, 'MSG_Third_Party_API', 1, 'Third Party Api'),
(456, 'MSG_Email', 1, 'Email'),
(457, 'MSG_Media', 1, 'Media'),
(458, 'MSG_Server', 1, 'Server'),
(459, 'LBL_Configurations', 1, 'Configurations'),
(460, 'LBL_Store_Owner_Email', 1, 'Store Owner Email'),
(461, 'LBL_Telephone', 1, 'Telephone'),
(462, 'LBL_Fax', 1, 'Fax'),
(463, 'LBL_About_Us', 1, 'About Us'),
(464, 'LBL_Privacy_Policy_Page', 1, 'Privacy Policy Page'),
(465, 'LBL_Terms_and_Conditions_Page', 1, 'Terms And Conditions Page'),
(466, 'LBL_Cookies_Policies_Page', 1, 'Cookies Policies Page'),
(467, 'LBL_Cookies_Policies', 1, 'Cookies Policies'),
(468, 'LBL_cookies_policies_section_will_be_shown_on_frontend', 1, 'Cookies Policies Section Will Be Shown On Frontend'),
(469, 'LBL_Use_SSL', 1, 'Use Ssl'),
(470, 'LBL_NOTE:_To_use_SSL,_check_with_your_host_if_a_SSL_certificate_is_installed_and_enable_it_from_here.', 1, 'Note: To Use Ssl, Check With Your Host If A Ssl Certificate Is Installed And Enable It From Here.'),
(471, 'LBL_Enable_Maintenance_Mode', 1, 'Enable Maintenance Mode'),
(472, 'LBL_NOTE:_Enable_Maintenance_Mode_Text', 1, 'Note: Enable Maintenance Mode Text'),
(473, 'LBL_Select_Admin_Logo', 1, 'Select Admin Logo'),
(474, 'LBL_Select_Desktop_Logo', 1, 'Select Desktop Logo'),
(475, 'LBL_Select_Email_Template_Logo', 1, 'Select Email Template Logo'),
(476, 'LBL_Select_Website_Favicon', 1, 'Select Website Favicon'),
(477, 'LBL_Select_Social_Feed_Image', 1, 'Select Social Feed Image'),
(478, 'LBL_Select_Payment_Page_Logo', 1, 'Select Payment Page Logo'),
(479, 'LBL_Select_Watermark_Image', 1, 'Select Watermark Image'),
(480, 'LBL_Select_Apple_Touch_Icon', 1, 'Select Apple Touch Icon'),
(481, 'LBL_Select_Mobile_Logo', 1, 'Select Mobile Logo'),
(482, 'LBL_From_Email', 1, 'From Email'),
(483, 'LBL_Reply_to_Email_Address', 1, 'Reply To Email Address'),
(484, 'LBL_Send_Email', 1, 'Send Email'),
(485, 'LBL_This_will_send_Test_Email_to_Site_Owner_Email', 1, 'This Will Send Test Email To Site Owner Email'),
(486, 'LBL_Contact_Email_Address', 1, 'Contact Email Address'),
(487, 'LBL_Send_SMTP_Email', 1, 'Send Smtp Email'),
(488, 'LBL_SMTP_Host', 1, 'Smtp Host'),
(489, 'LBL_SMTP_Port', 1, 'Smtp Port'),
(490, 'LBL_SMTP_Username', 1, 'Smtp Username'),
(491, 'LBL_SMTP_Password', 1, 'Smtp Password'),
(492, 'LBL_SMTP_Secure', 1, 'Smtp Secure'),
(493, 'LBL_tls', 1, 'Tls'),
(494, 'LBL_ssl', 1, 'Ssl'),
(495, 'LBL_Additional_Alert_E-Mails', 1, 'Additional Alert E-mails'),
(496, 'LBL_Any_additional_emails_you_want_to_receive_the_alert_email', 1, 'Any Additional Emails You Want To Receive The Alert Email'),
(497, 'LBL_Facebook_APP_ID', 1, 'Facebook App Id'),
(498, 'LBL_This_is_the_application_ID_used_in_login_and_post.', 1, 'This Is The Application Id Used In Login And Post.'),
(499, 'LBL_Facebook_App_Secret', 1, 'Facebook App Secret'),
(500, 'LBL_This_is_the_Facebook_secret_key_used_for_authentication_and_other_Facebook_related_plugins_support.', 1, 'This Is The Facebook Secret Key Used For Authentication And Other Facebook Related Plugins Support.'),
(501, 'LBL_Twitter_APP_KEY', 1, 'Twitter App Key'),
(502, 'LBL_This_is_the_application_ID_used_in_post.', 1, 'This Is The Application Id Used In Post.'),
(503, 'LBL_Twitter_App_Secret', 1, 'Twitter App Secret'),
(504, 'LBL_This_is_the_Twitter_secret_key_used_for_authentication_and_other_Twitter_related_plugins_support.', 1, 'This Is The Twitter Secret Key Used For Authentication And Other Twitter Related Plugins Support.'),
(505, 'LBL_Google_Plus_Developer_Key', 1, 'Google Plus Developer Key'),
(506, 'LBL_This_is_the_google_plus_developer_key.', 1, 'This Is The Google Plus Developer Key.'),
(507, 'LBL_Google_Plus_Client_ID', 1, 'Google Plus Client Id'),
(508, 'LBL_This_is_the_application_Client_Id_used_to_Login.', 1, 'This Is The Application Client Id Used To Login.'),
(509, 'LBL_Google_Plus_Client_Secret', 1, 'Google Plus Client Secret'),
(510, 'LBL_This_is_the_Google_Plus_id_client_secret_key_used_for_authentication.', 1, 'This Is The Google Plus Id Client Secret Key Used For Authentication.'),
(511, 'LBL_Google_Push_Notification_API_KEY', 1, 'Google Push Notification Api Key'),
(512, 'LBL_This_is_the_api_key_used_in_push_notifications.', 1, 'This Is The Api Key Used In Push Notifications.'),
(513, 'LBL_Google_Map_API', 1, 'Google Map Api'),
(514, 'LBL_Google_Map_API_Key', 1, 'Google Map Api Key'),
(515, 'LBL_This_is_the_Google_map_api_key_used_to_get_user_current_location.', 1, 'This Is The Google Map Api Key Used To Get User Current Location.'),
(516, 'LBL_Newsletter_Subscription', 1, 'Newsletter Subscription'),
(517, 'LBL_Activate_Newsletter_Subscription', 1, 'Activate Newsletter Subscription'),
(518, 'LBL_Email_Marketing_System', 1, 'Email Marketing System'),
(519, 'LBL_Mailchimp', 1, 'Mailchimp'),
(520, 'LBL_Aweber', 1, 'Aweber'),
(521, 'LBL_Please_select_the_system_you_wish_to_use_for_email_marketing.', 1, 'Please Select The System You Wish To Use For Email Marketing.'),
(522, 'LBL_Mailchimp_Key', 1, 'Mailchimp Key'),
(523, 'LBL_This_is_the_Mailchimp\'s_application_key_used_in_subscribe_and_send_newsletters.', 1, 'This Is The Mailchimp\'s Application Key Used In Subscribe And Send Newsletters.'),
(524, 'LBL_Mailchimp_List_ID', 1, 'Mailchimp List Id'),
(525, 'LBL_This_is_the_Mailchimp\'s_subscribers_List_ID.', 1, 'This Is The Mailchimp\'s Subscribers List Id.'),
(526, 'LBL_Aweber_Signup_Form_Code', 1, 'Aweber Signup Form Code'),
(527, 'LBL_Enter_the_newsletter_signup_code_received_from_Aweber', 1, 'Enter The Newsletter Signup Code Received From Aweber'),
(528, 'LBL_Google_Analytics', 1, 'Google Analytics'),
(529, 'LBL_Client_Id', 1, 'Client Id'),
(530, 'LBL_This_is_the_application_Client_Id_used_in_Analytics_dashboard.', 1, 'This Is The Application Client Id Used In Analytics Dashboard.'),
(531, 'LBL_Secret_Key', 1, 'Secret Key'),
(532, 'LBL_This_is_the_application_secret_key_used_in_Analytics_dashboard.', 1, 'This Is The Application Secret Key Used In Analytics Dashboard.'),
(533, 'LBL_Analytics_Id', 1, 'Analytics Id'),
(534, 'LBL_This_is_the_Google_Analytics_ID._Ex._UA-xxxxxxx-xx.', 1, 'This Is The Google Analytics Id. Ex. Ua-xxxxxxx-xx.'),
(535, 'LBL_Google_Recaptcha', 1, 'Google Recaptcha'),
(536, 'LBL_Site_Key', 1, 'Site Key'),
(537, 'LBL_This_is_the_application_Site_key_used_for_Google_Recaptcha.', 1, 'This Is The Application Site Key Used For Google Recaptcha.'),
(538, 'LBL_This_is_the_application_Secret_key_used_for_Google_Recaptcha.', 1, 'This Is The Application Secret Key Used For Google Recaptcha.'),
(539, 'LBL_Activate_Live_Chat', 1, 'Activate Live Chat'),
(540, 'LBL_Activate_3rd_Party_Live_Chat.', 1, 'Activate 3rd Party Live Chat.'),
(541, 'LBL_Live_Chat_Code', 1, 'Live Chat Code'),
(542, 'LBL_This_is_the_live_chat_script/code_provided_by_the_3rd_party_API_for_integration.', 1, 'This Is The Live Chat Script/code Provided By The 3rd Party Api For Integration.'),
(543, 'LBL_Admin', 1, 'Admin'),
(544, 'LBL_Default_Items_Per_Page', 1, 'Default Items Per Page'),
(545, 'LBL_Determines_how_many_items_are_shown_per_page_(user_listing,_categories,_etc)', 1, 'Determines How Many Items Are Shown Per Page (user Listing, Categories, Etc)'),
(546, 'LBL_Account', 1, 'Account'),
(547, 'LBL_Activate_Admin_Approval_After_Registration_(Sign_Up)', 1, 'Activate Admin Approval After Registration (sign Up)'),
(548, 'LBL_On_enabling_this_feature,_admin_need_to_approve_each_user_after_registration_(User_cannot_login_until_admin_approves)', 1, 'On Enabling This Feature, Admin Need To Approve Each User After Registration (user Cannot Login Until Admin Approves)'),
(549, 'LBL_Activate_Email_Verification_After_Registration', 1, 'Activate Email Verification After Registration'),
(550, 'LBL_user_need_to_verify_their_email_address_provided_during_registration', 1, 'User Need To Verify Their Email Address Provided During Registration'),
(551, 'LBL_Activate_Auto_Login_After_Registration', 1, 'Activate Auto Login After Registration'),
(552, 'LBL_On_enabling_this_feature,_users_will_be_automatically_logged-in_after_registration', 1, 'On Enabling This Feature, Users Will Be Automatically Logged-in After Registration'),
(553, 'LBL_Activate_Sending_Welcome_Mail_After_Registration', 1, 'Activate Sending Welcome Mail After Registration'),
(554, 'LBL_On_enabling_this_feature,_users_will_receive_a_welcome_mail_after_registration.', 1, 'On Enabling This Feature, Users Will Receive A Welcome Mail After Registration.'),
(555, 'LBL_Commission', 1, 'Commission'),
(556, 'LBL_Maximum_Site_Commission', 1, 'Maximum Site Commission'),
(557, 'LBL_This_is_maximum_commission/Fees_that_will_be_charged_on_a_particular_product.', 1, 'This Is Maximum Commission/fees That Will Be Charged On A Particular Product.'),
(558, 'LBL_Commission_charged_including_shipping', 1, 'Commission Charged Including Shipping'),
(559, 'LBL_Commission_charged_including_shipping_charges', 1, 'Commission Charged Including Shipping Charges'),
(560, 'LBL_Commission_charged_including_tax', 1, 'Commission Charged Including Tax'),
(561, 'LBL_Commission_charged_including_tax_charges', 1, 'Commission Charged Including Tax Charges'),
(562, 'LBL_Withdrawal', 1, 'Withdrawal'),
(563, 'LBL_Minimum_Withdrawal_Amount', 1, 'Minimum Withdrawal Amount'),
(564, 'LBL_This_is_the_minimum_withdrawable_amount.', 1, 'This Is The Minimum Withdrawable Amount.'),
(565, 'LBL_Minimum_Interval_[Days]', 1, 'Minimum Interval [days]'),
(566, 'LBL_This_is_the_minimum_interval_in_days_between_two_withdrawal_requests.', 1, 'This Is The Minimum Interval In Days Between Two Withdrawal Requests.'),
(567, 'LBL_Twitter_Username', 1, 'Twitter Username'),
(568, 'LBL_This_is_required_for_Twitter_Card_code_SEO_Update', 1, 'This Is Required For Twitter Card Code Seo Update'),
(569, 'LBL_Site_Tracker_Code', 1, 'Site Tracker Code'),
(570, 'LBL_This_is_the_site_tracker_script,_used_to_track_and_analyze_data_about_how_people_are_getting_to_your_website._e.g.,_Google_Analytics.', 1, 'This Is The Site Tracker Script, Used To Track And Analyze Data About How People Are Getting To Your Website. E.g., Google Analytics.'),
(571, 'LBL_Default_Site_Laguage', 1, 'Default Site Laguage'),
(572, 'LBL_Timezone', 1, 'Timezone'),
(573, 'LBL_date_Format', 1, 'Date Format'),
(574, 'LBL_Default_Site_Currency', 1, 'Default Site Currency'),
(575, 'MSG_Uploaded_Successfully', 1, 'Uploaded Successfully'),
(576, 'LBL_Site_Name', 1, 'Site Name'),
(577, 'LBL_Site_Owner', 1, 'Site Owner'),
(578, 'LBL_Cookies_Policies_Text', 1, 'Cookies Policies Text'),
(579, 'LBL_Manage_Currencies', 1, 'Manage Currencies'),
(580, 'LBL_Currency_Listing', 1, 'Currency Listing'),
(581, 'LBL_Add_Currency', 1, 'Add Currency'),
(582, 'LBL_Symbol_Left', 1, 'Symbol Left'),
(583, 'LBL_Symbol_Right', 1, 'Symbol Right'),
(584, 'LBL_Order_Updated_Successfully', 1, 'Order Updated Successfully'),
(585, 'LBL_Currency_code', 1, 'Currency Code'),
(586, 'LBL_Currency_Symbol_Left', 1, 'Currency Symbol Left'),
(587, 'LBL_Currency_Symbol_Right', 1, 'Currency Symbol Right'),
(588, 'LBL_Currency_Conversion_Value', 1, 'Currency Conversion Value'),
(589, 'LBL_This_is_your_default_currency', 1, 'This Is Your Default Currency'),
(590, 'LBL_Currency_Setup', 1, 'Currency Setup'),
(591, 'LBL_Manage_Payment_Methods', 1, 'Manage Payment Methods'),
(592, 'LBL_Payment_Methods_List', 1, 'Payment Methods List'),
(593, 'LBL_Payment_Method', 1, 'Payment Method'),
(594, 'LBL_Publishable_Key', 1, 'Publishable Key'),
(595, 'LBL_Payment_Methods_Settings', 1, 'Payment Methods Settings'),
(596, 'LBL_Gateway_Identifier', 1, 'Gateway Identifier'),
(597, 'LBL_Payment_Method_Setup', 1, 'Payment Method Setup'),
(598, 'LBL_CMS_Page', 1, 'Cms Page'),
(599, 'LBL_Default', 1, 'Default'),
(600, 'LBL_Advanced_Setting', 1, 'Advanced Setting'),
(601, 'LBL_Meta_Tags_Setup', 1, 'Meta Tags Setup'),
(602, 'LBL_Meta_Tags', 1, 'Meta Tags'),
(603, 'LBL_Type', 1, 'Type'),
(604, 'LBL_Manage_Meta_Tags', 1, 'Manage Meta Tags'),
(605, 'LBL_Meta_Tags_Listing', 1, 'Meta Tags Listing'),
(606, 'LBL_Has_Tags_Associated', 1, 'Has Tags Associated'),
(607, 'LBL_Doesn\'t_Matter', 1, 'Doesn\'t Matter'),
(608, 'LBL_Entity_Id', 1, 'Entity Id'),
(609, 'LBL_Meta_Tag_Setup', 1, 'Meta Tag Setup'),
(610, 'LBL_Meta_Title', 1, 'Meta Title'),
(611, 'LBL_Meta_Keywords', 1, 'Meta Keywords'),
(612, 'LBL_Meta_Description', 1, 'Meta Description'),
(613, 'LBL_Other_Meta_Tags', 1, 'Other Meta Tags'),
(614, 'LBL_For_Example:', 1, 'For Example:'),
(615, 'LBL_Add_Meta_Tag', 1, 'Add Meta Tag'),
(616, 'LBL_Manage_Email_Templates', 1, 'Manage Email Templates'),
(617, 'LBL_Email_Templates', 1, 'Email Templates'),
(618, 'LBL_Email_Template_Lists', 1, 'Email Template Lists'),
(619, 'LBL_Manage_Url_Rewriting', 1, 'Manage Url Rewriting'),
(620, 'LBL_Url_List', 1, 'Url List'),
(621, 'LBL_Original', 1, 'Original'),
(622, 'LBL_Custom', 1, 'Custom'),
(623, 'LBL_Original_URL', 1, 'Original Url'),
(624, 'LBL_Custom_URL', 1, 'Custom Url'),
(625, 'LBL_Example:_Custom_URL_Example', 1, 'Example: Custom Url Example'),
(626, 'LBL_Url_Rewrite_Setup', 1, 'Url Rewrite Setup'),
(627, 'LBL_Apply_to_teach', 1, 'Apply To Teach'),
(628, 'LBL_Languages', 1, 'Languages'),
(629, 'LBL_Resume', 1, 'Resume'),
(630, 'LBL_Male', 1, 'Male'),
(631, 'LBL_Female', 1, 'Female'),
(632, 'LBL_What_language_do_you_want_to_teach?', 1, 'What Language Do You Want To Teach?'),
(633, 'LBL_Languages_you_speak', 1, 'Languages You Speak'),
(634, 'LBL_Languages_Proficiency', 1, 'Languages Proficiency'),
(635, 'LBL_Total_Beginner', 1, 'Total Beginner'),
(636, 'LBL_Beginner', 1, 'Beginner'),
(637, 'LBL_Upper_Beginner', 1, 'Upper Beginner'),
(638, 'LBL_Intermediate', 1, 'Intermediate'),
(639, 'LBL_Upper_Intermediate', 1, 'Upper Intermediate'),
(640, 'LBL_Advanced', 1, 'Advanced'),
(641, 'LBL_Upper_Advanced', 1, 'Upper Advanced'),
(642, 'LBL_Native', 1, 'Native'),
(643, 'LBL_Accept_Tutor_Approval_Terms_&_condition', 1, 'Accept Tutor Approval Terms & Condition'),
(644, 'LBL_Add_New_Language', 1, 'Add New Language'),
(645, 'LBL_Add_Item', 1, 'Add Item'),
(646, 'LBL_Tutor_Approval_Form', 1, 'Tutor Approval Form'),
(647, 'MSG_Already_Logged_in,_Please_try_after_reloading_the_page', 1, 'Already Logged In, Please Try After Reloading The Page'),
(648, 'LBL_Do_you_want_to_remove', 2, 'Do You Want To Remove'),
(649, 'LBL_Apply_to_teach', 2, 'Apply To Teach'),
(650, 'LBL_Activate_Tutor_Account', 1, 'Activate Tutor Account'),
(651, 'LBL_Account_Information', 1, 'Account Information'),
(652, 'LBL_General_Info', 1, 'General Info'),
(653, 'LBL_About_Me', 1, 'About Me'),
(654, 'LBL_Select', 1, 'Select'),
(655, 'LBL_Add', 1, 'Add'),
(656, 'LBL_Profile_Image', 1, 'Profile Image'),
(657, 'LBL_Upload', 1, 'Upload'),
(658, 'LBL_Preferred_Dashboard', 1, 'Preferred Dashboard'),
(659, 'LBL_Enable_Trial_Lesson', 1, 'Enable Trial Lesson'),
(660, 'LBL_How_much_notice_do_you_require_before_lessons?', 1, 'How Much Notice Do You Require Before Lessons?'),
(661, 'M_Single_Lesson_Rate', 1, 'Single Lesson Rate'),
(662, 'M_Bulk_Lesson_Rate', 1, 'Bulk Lesson Rate'),
(663, 'M_Introduction_Video_Link', 1, 'Introduction Video Link'),
(664, 'LBL_Select_State', 1, 'Select State'),
(665, 'MSG_Login_attempt_limit_exceeded._Please_try_after_some_time.', 1, 'Login Attempt Limit Exceeded. Please Try After Some Time.'),
(666, 'LBL_Your_session_seems_to_be_expired', 1, 'Your Session Seems To Be Expired'),
(667, 'MSG_Tutor_Approval_Request_Setup_Successful', 1, 'Tutor Approval Request Setup Successful'),
(668, 'LBL_Manage_Tutor_Requests', 1, 'Manage Tutor Requests'),
(669, 'LBL_Tutor_Requests', 1, 'Tutor Requests'),
(670, 'LBL_Change_Status', 1, 'Change Status'),
(671, 'LBL_Reason_for_Cancellation', 1, 'Reason For Cancellation'),
(672, 'LBL_Update_Status', 1, 'Update Status'),
(673, 'LBL_Status_Updated_Successfully', 1, 'Status Updated Successfully'),
(674, 'LBL_Experience_Type', 1, 'Experience Type'),
(675, 'LBL_Education', 1, 'Education'),
(676, 'LBL_Certification', 1, 'Certification'),
(677, 'LBL_Work_Experience', 1, 'Work Experience'),
(678, 'LBL_Eg:_B.A._English', 1, 'Eg: B.a. English'),
(679, 'LBL_Institution', 1, 'Institution'),
(680, 'LBL_Eg:_Oxford_University', 1, 'Eg: Oxford University'),
(681, 'LBL_Location', 1, 'Location'),
(682, 'LBL_Eg:_London', 1, 'Eg: London'),
(683, 'LBL_Eg._Focus_in_Humanist_Literature', 1, 'Eg. Focus In Humanist Literature'),
(684, 'LBL_Start', 1, 'Start'),
(685, 'LBL_Present', 1, 'Present'),
(686, 'LBL_End', 1, 'End'),
(687, 'LBL_Upload_Certificate', 1, 'Upload Certificate'),
(688, 'MSG_Qualification_Setup_Successful', 1, 'Qualification Setup Successful'),
(689, 'MSG_File_deleted_successfully', 1, 'File Deleted Successfully'),
(690, 'MSG_Please_select_a_file', 1, 'Please Select A File'),
(691, 'MSG_Qualification_Removed_Successfuly', 1, 'Qualification Removed Successfuly'),
(692, 'LBL_View_User_Detail', 1, 'View User Detail'),
(693, 'LBL_Content_Pages_Layouts_Instructions', 1, 'Content Pages Layouts Instructions'),
(694, 'LBL_Layout_1', 1, 'Layout 1'),
(695, 'LBL_Layout_2', 1, 'Layout 2'),
(696, 'LBL_Layout_3', 1, 'Layout 3'),
(697, 'LBL_Controller', 1, 'Controller'),
(698, 'LBL_Ex:_If_URL_is', 1, 'Ex: If Url Is'),
(699, 'LBL_then_controller_will_be_', 1, 'Then Controller Will Be '),
(700, 'LBL_then_action_will_be_', 1, 'Then Action Will Be '),
(701, 'LBL_Record_Id', 1, 'Record Id'),
(702, 'LBL_then_record_id_will_be_', 1, 'Then Record Id Will Be '),
(703, 'LBL_Sub_Record_Id', 1, 'Sub Record Id'),
(704, 'LBL_then_sub_record_id_will_be_', 1, 'Then Sub Record Id Will Be '),
(705, 'LBL_Confirm_Password', 1, 'Confirm Password'),
(706, 'LBL_Admin_User_Change_Password', 1, 'Admin User Change Password'),
(707, 'LBL_Permissions', 1, 'Permissions'),
(708, 'MSG_None', 1, 'None'),
(709, 'MSG_Read_Only', 1, 'Read Only'),
(710, 'MSG_Read_and_Write', 1, 'Read And Write'),
(711, 'LBL_Select_permission_for_all_modules', 1, 'Select Permission For All Modules'),
(712, 'LBL_Apply_to_All', 1, 'Apply To All'),
(713, 'LBL_Manage', 1, 'Manage'),
(714, 'LBL_User_Permission', 1, 'User Permission'),
(715, 'MSG_Admin_Dashboard', 1, 'Admin Dashboard'),
(716, 'MSG_Tutor_Approval_Form', 1, 'Tutor Approval Form'),
(717, 'MSG_Tutor_Approval_Requests', 1, 'Tutor Approval Requests'),
(718, 'MSG_Users', 1, 'Users'),
(719, 'MSG_Content_Pages', 1, 'Content Pages'),
(720, 'MSG_Content_Blocks', 1, 'Content Blocks'),
(721, 'MSG_Navigation_Management', 1, 'Navigation Management'),
(722, 'MSG_Countries', 1, 'Countries'),
(723, 'MSG_States', 1, 'States'),
(724, 'MSG_Social_Platform', 1, 'Social Platform'),
(725, 'MSG_Discount_Coupons', 1, 'Discount Coupons'),
(726, 'MSG_Language_Labels', 1, 'Language Labels'),
(727, 'MSG_Home_Page_Slide_Management', 1, 'Home Page Slide Management'),
(728, 'MSG_Banners', 1, 'Banners'),
(729, 'MSG_General_Settings', 1, 'General Settings'),
(730, 'MSG_Payment_Methods', 1, 'Payment Methods'),
(731, 'MSG_Currency_Management', 1, 'Currency Management'),
(732, 'MSG_Email_Templates', 1, 'Email Templates'),
(733, 'MSG_Meta_Tags', 1, 'Meta Tags'),
(734, 'MSG_Url_Rewriting', 1, 'Url Rewriting'),
(735, 'MSG_Admin_Users', 1, 'Admin Users'),
(736, 'MSG_Testimonial', 1, 'Testimonial'),
(737, 'LBL_Module', 1, 'Module'),
(738, 'MSG_Updated_Successfully', 1, 'Updated Successfully'),
(739, 'LBL_From_Name', 1, 'From Name'),
(740, 'LBL_First_Name', 1, 'First Name'),
(741, 'LBL_Last_Name', 1, 'Last Name'),
(742, 'LBL_Gender', 1, 'Gender'),
(743, 'LBL_Phone_Number', 1, 'Phone Number'),
(744, 'LBL_Introduction_Video_Youtube_Link', 1, 'Introduction Video Youtube Link'),
(745, 'LBL_Photo_Id', 1, 'Photo Id'),
(746, 'LBL_Language_Proficiency', 1, 'Language Proficiency'),
(747, 'MSG_SSL_NOT_INSTALLED_FOR_WEBSITE_Try_to_Save_data_without_Enabling_ssl', 1, 'Ssl Not Installed For Website Try To Save Data Without Enabling Ssl'),
(748, 'LBL_Email', 2, 'Email'),
(749, 'LBL_EMAIL_ADDRESS', 2, 'Email Address'),
(750, 'LBL_Password', 2, 'Password'),
(751, 'LBL_Remember_Me', 2, 'Remember Me'),
(752, 'LBL_Sign_in_with_Facebook', 2, 'Sign In With Facebook'),
(753, 'LBL_Sign_in_with_Google+', 2, 'Sign In With Google+'),
(754, 'LBL_Or', 2, 'Or'),
(755, 'LBL_Forgot_Password?', 2, 'Forgot Password?'),
(756, 'LBL_Don\'t_have_an_account?', 2, 'Don\'t Have An Account?'),
(759, 'LBL_Username_or_email', 2, 'Username Or Email'),
(760, 'BTN_SUBMIT', 2, 'Submit'),
(761, 'LBL_Settings', 2, 'Settings'),
(762, 'LBL_Profile', 2, 'Profile'),
(763, 'LBL_Account_Information', 2, 'Account Information'),
(764, 'LBL_General_Info', 2, 'General Info'),
(765, 'LBL_Loading..', 2, 'Loading..'),
(766, 'LBL_Username', 2, 'Username'),
(767, 'LBL_About_Me', 2, 'About Me'),
(768, 'LBL_Country', 2, 'Country'),
(769, 'LBL_Select', 2, 'Select'),
(770, 'LBL_SAVE_CHANGES', 2, 'Save Changes'),
(771, 'LBL_Profile_Picture', 2, 'Profile Picture'),
(772, 'LBL_Update_Profile_Picture', 2, 'Update Profile Picture'),
(773, 'LBL_Rotate_Left', 2, 'Rotate Left'),
(774, 'LBL_Rotate_Right', 2, 'Rotate Right'),
(775, 'LBL_Add', 2, 'Add'),
(776, 'LBL_Edit', 2, 'Edit'),
(777, 'LBL_Profile_Image', 2, 'Profile Image'),
(778, 'LBL_Change', 2, 'Change'),
(779, 'LBL_Remove', 2, 'Remove'),
(780, 'LBL_Full_Name', 2, 'Full Name'),
(781, 'LBL_Email_ID', 2, 'Email Id'),
(782, 'MSG_Valid_password', 2, 'Valid Password'),
(783, 'LBL_I_accept_Terms_&_Conditions', 2, 'I Accept Terms & Conditions'),
(784, 'MSG_Terms_Condition_is_mandatory.', 2, 'Terms Condition Is Mandatory.'),
(785, 'LBL_Register', 2, 'Register'),
(786, 'LBL_Already_have_an_account?', 2, 'Already Have An Account?'),
(787, 'LBL_Sign_In', 2, 'Sign In'),
(788, 'MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', 1, 'Password Must Be Eight Characters Long And Alphanumeric'),
(797, 'LBL_Amount', 1, 'Amount'),
(798, 'LBL_Add_User_Transactions', 1, 'Add User Transactions'),
(799, 'LBL_credited', 1, 'Credited'),
(800, 'LBL_Lessons', 1, 'Lessons'),
(801, 'LBL_Students', 1, 'Students'),
(802, 'LBL_Flashcards', 1, 'Flashcards'),
(803, 'LBL_Wallet', 1, 'Wallet'),
(804, 'LBL_Orders', 1, 'Orders'),
(805, 'LBL_Courses', 1, 'Courses'),
(806, 'LBL_Lesson_Plan', 1, 'Lesson Plan'),
(807, 'LBL_Reports', 1, 'Reports'),
(808, 'LBL_Experience', 1, 'Experience'),
(809, 'LBL_Biography', 1, 'Biography'),
(810, 'LBL_Personal_Information', 1, 'Personal Information'),
(811, 'LBL_Change_Avataar', 1, 'Change Avataar'),
(812, 'M_Are_you_sure!', 1, 'Are You Sure!'),
(813, 'LBL_Qualifications', 1, 'Qualifications'),
(814, 'LBL_Tutor_Request_Detail', 1, 'Tutor Request Detail'),
(815, 'LBL_Institute', 1, 'Institute'),
(816, 'LBL_Request_Information', 1, 'Request Information'),
(817, 'LBL_Profile_Information', 1, 'Profile Information'),
(818, 'LBL_You_Tube_Video_Link', 1, 'You Tube Video Link'),
(819, 'LBL_Teaching_Language', 1, 'Teaching Language'),
(820, 'LBL_Spoken_Language', 1, 'Spoken Language'),
(821, 'LBL_Currency_Name', 1, 'Currency Name'),
(822, 'LBL_Quick_filters', 1, 'Quick Filters'),
(823, 'LBL_My_Orders', 1, 'My Orders'),
(824, 'LBL_My_Downloads', 1, 'My Downloads'),
(825, 'LBL_Order_Cancellation_Requests', 1, 'Order Cancellation Requests'),
(826, 'LBL_Order_Return_Requests', 1, 'Order Return Requests'),
(827, 'LBL_Addresses', 1, 'Addresses'),
(828, 'LBL_My_Addresses', 1, 'My Addresses'),
(829, 'LBL_Rewards', 1, 'Rewards'),
(830, 'LBL_Reward_Points', 1, 'Reward Points'),
(831, 'LBL_Share_and_Earn', 1, 'Share And Earn'),
(832, 'LBL_My_Offers', 1, 'My Offers'),
(833, 'LBL_My_Account', 1, 'My Account'),
(834, 'LBL_Messages', 1, 'Messages'),
(835, 'LBL_My_Credits', 1, 'My Credits'),
(836, 'LBL_Wishlist/Favorites', 1, 'Wishlist/favorites'),
(837, 'LBL_Change_Email', 1, 'Change Email'),
(838, 'LBL_Body', 1, 'Body'),
(839, 'LBL_Replacement_Caption', 1, 'Replacement Caption'),
(840, 'LBL_Replacement_Vars', 1, 'Replacement Vars'),
(841, 'LBL_Email_Template_Setup', 1, 'Email Template Setup'),
(842, 'MSG_That_captcha_was_incorrect', 1, 'That Captcha Was Incorrect'),
(843, 'MSG_Please_Enter_Valid_password', 1, 'Please Enter Valid Password'),
(844, 'LBL_Tutor_Preferences', 1, 'Tutor Preferences'),
(845, 'LBL_Accents', 1, 'Accents'),
(846, 'LBL_Teaches_Level', 1, 'Teaches Level'),
(847, 'LBL_Learners_Ages', 1, 'Learners Ages'),
(848, 'LBL_Lessons_Include', 1, 'Lessons Include'),
(849, 'LBL_Subjects', 1, 'Subjects'),
(850, 'LBL_Test_preparation', 1, 'Test Preparation'),
(851, 'LBL_Skills', 1, 'Skills'),
(852, 'LBL_Resume_Information', 1, 'Resume Information'),
(853, 'LBL_Start/End', 1, 'Start/end'),
(854, 'LBL_Uploaded_Certificate', 1, 'Uploaded Certificate'),
(855, 'LBL_Actions', 1, 'Actions'),
(856, 'LBL_Learner_Ages', 1, 'Learner Ages'),
(857, 'LBL_Test_Preparations', 1, 'Test Preparations'),
(858, 'LBL_Language_that_I\'m_teaching', 1, 'Language That I\'m Teaching'),
(859, 'LBL_Preferences_updated_successfully!', 1, 'Preferences Updated Successfully!'),
(860, 'LBL_Facebook', 1, 'Facebook'),
(861, 'LBL_Google+', 1, 'Google+'),
(862, 'LBL_Show_Password', 1, 'Show Password'),
(863, 'LBL_Hide_Password', 1, 'Hide Password'),
(864, 'LBL_What_Language_I_am_Teaching', 1, 'What Language I Am Teaching'),
(865, 'LBL_Languages_I_Speak', 1, 'Languages I Speak'),
(866, 'LBL_Price', 1, 'Price'),
(867, 'LBL_Payments', 1, 'Payments'),
(868, 'M_Paypal_Email_Address', 1, 'Paypal Email Address'),
(869, 'LBL_Paypal', 1, 'Paypal'),
(870, 'LBL_Bank_Account', 1, 'Bank Account'),
(871, 'M_Bank_Name', 1, 'Bank Name'),
(872, 'M_Beneficiary/Account_Holder_Name', 1, 'Beneficiary/account Holder Name'),
(873, 'M_Bank_Account_Number', 1, 'Bank Account Number'),
(874, 'M_IFSC_Code/Swift_Code', 1, 'Ifsc Code/swift Code'),
(875, 'M_Bank_Address', 1, 'Bank Address'),
(876, 'LBL_Video_Youtube_Link', 1, 'Video Youtube Link'),
(877, 'LBL_Write_about_yourself_and_your_qualifications', 1, 'Write About Yourself And Your Qualifications'),
(878, 'LBL_Introduction', 1, 'Introduction'),
(879, 'LBL_Teacher_Application', 1, 'Teacher Application'),
(880, 'LBL_Thank_you_for_applying_to_teach_on_{website-name}', 1, 'Thank You For Applying To Teach On {website-name}'),
(881, 'LBL_Certificate', 1, 'Certificate'),
(882, 'LBL_Teacher_Approval_Requests', 1, 'Teacher Approval Requests'),
(883, 'LBL_Teacher_Preferences', 1, 'Teacher Preferences'),
(884, 'LBL_Teacher', 1, 'Teacher'),
(885, 'LBL_Availability', 1, 'Availability'),
(886, 'LBL_Availability_updated_successfully!', 1, 'Availability Updated Successfully!'),
(887, 'LBL_Availability_deleted_successfully!', 1, 'Availability Deleted Successfully!'),
(888, 'LBL_Facebook', 2, 'Facebook'),
(889, 'LBL_Google+', 2, 'Google+'),
(890, 'MSG_VERIFICATION_EMAIL_HAS_BEEN_SENT_AGAIN', 1, 'Verification Email Has Been Sent Again'),
(891, 'MSG_EMAIL_VERIFIED_SUCCESFULLY', 1, 'Email Verified Succesfully'),
(892, 'LBL_Preference_Identifier', 1, 'Preference Identifier'),
(893, 'LBL_Manage_Preferences', 1, 'Manage Preferences'),
(894, 'LBL_Preferences', 1, 'Preferences'),
(895, 'LBL_Preferences_Accents__Listing', 1, 'Preferences Accents  Listing'),
(896, 'LBL_Add_Preferences', 1, 'Add Preferences'),
(897, 'LBL_Preference_Title', 1, 'Preference Title'),
(898, 'LBL_Preferences___Listing', 1, 'Preferences   Listing');
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(899, 'LBL_Preferences_Teaches_Level__Listing', 1, 'Preferences Teaches Level  Listing'),
(900, 'LBL_Preferences_Test_Preparations__Listing', 1, 'Preferences Test Preparations  Listing'),
(901, 'LBL_Find_a_Teacher', 2, 'Find A Teacher'),
(902, 'LBL_Teaches:Select_Language', 2, 'Teaches:select Language'),
(903, 'LBL_Availablity', 2, 'Availablity'),
(904, 'LBL_Search_Teacher\'s_Name', 2, 'Search Teacher\'s Name'),
(905, 'LBL_Showing', 2, 'Showing'),
(906, 'LBL_of', 2, 'Of'),
(907, 'LBL_teachers', 2, 'Teachers'),
(908, 'LBL_Sort_By_Popularity', 2, 'Sort By Popularity'),
(909, 'LBL_Sort_By_Price_Low_to_High', 2, 'Sort By Price Low To High'),
(910, 'LBL_Sort_By_Price_High_to_Low', 2, 'Sort By Price High To Low'),
(911, 'LBL_Filters', 2, 'Filters'),
(912, 'LBL_Teacher_Speakes', 2, 'Teacher Speakes'),
(913, 'LBL_From', 2, 'From'),
(914, 'LBL_Gender', 2, 'Gender'),
(915, 'LBL_Male', 2, 'Male'),
(916, 'LBL_Female', 2, 'Female'),
(917, 'LBL_Want_to_be_a_teacher?', 2, 'Want To Be A Teacher?'),
(918, 'LBL_If_you\'re_interested_in_being_a_teacher_on_{sitename},_please_apply_here.', 2, 'If You\'re Interested In Being A Teacher On {sitename}, Please Apply Here.'),
(919, 'LBL_Apply_to_be_a_teacher', 2, 'Apply To Be A Teacher'),
(920, 'LBL_Total_Beginner', 2, 'Total Beginner'),
(921, 'LBL_Beginner', 2, 'Beginner'),
(922, 'LBL_Upper_Beginner', 2, 'Upper Beginner'),
(923, 'LBL_Intermediate', 2, 'Intermediate'),
(924, 'LBL_Upper_Intermediate', 2, 'Upper Intermediate'),
(925, 'LBL_Advanced', 2, 'Advanced'),
(926, 'LBL_Upper_Advanced', 2, 'Upper Advanced'),
(927, 'LBL_Native', 2, 'Native'),
(928, 'LBL_Hourly_Rate', 2, 'Hourly Rate'),
(929, 'LBL_Favorite', 2, 'Favorite'),
(930, 'LBL_Teaches', 2, 'Teaches'),
(931, 'LBL_Lessons', 2, 'Lessons'),
(932, 'LBL_Students', 2, 'Students'),
(933, 'LBL_Speaks', 2, 'Speaks'),
(934, 'LBL_Availability', 2, 'Availability'),
(935, 'LBL_Message', 2, 'Message'),
(936, 'LBL_No_Result_found!!', 2, 'No Result Found!!'),
(937, 'LBL_Find_a_Teacher', 1, 'Find A Teacher'),
(938, 'LBL_Weekly_Schedule', 1, 'Weekly Schedule'),
(939, 'LBL_Marked_Unavailable_successfully!', 1, 'Marked Unavailable Successfully!'),
(940, 'LBL_General_Availability_Note', 1, 'General Availability Note'),
(941, 'LBL_Teaches:Select_Language', 1, 'Teaches:select Language'),
(942, 'LBL_Availablity', 1, 'Availablity'),
(943, 'LBL_Search_Teacher\'s_Name', 1, 'Search Teacher\'s Name'),
(944, 'LBL_teachers', 1, 'Teachers'),
(945, 'LBL_Sort_By_Popularity', 1, 'Sort By Popularity'),
(946, 'LBL_Sort_By_Price_Low_to_High', 1, 'Sort By Price Low To High'),
(947, 'LBL_Sort_By_Price_High_to_Low', 1, 'Sort By Price High To Low'),
(948, 'LBL_Filters', 1, 'Filters'),
(949, 'LBL_Teacher_Speakes', 1, 'Teacher Speakes'),
(950, 'LBL_From', 1, 'From'),
(951, 'LBL_Want_to_be_a_teacher?', 1, 'Want To Be A Teacher?'),
(952, 'LBL_If_you\'re_interested_in_being_a_teacher_on_{sitename},_please_apply_here.', 1, 'If You\'re Interested In Being A Teacher On {sitename}, Please Apply Here.'),
(953, 'LBL_Apply_to_be_a_teacher', 1, 'Apply To Be A Teacher'),
(954, 'LBL_Hourly_Rate', 1, 'Hourly Rate'),
(955, 'LBL_Favorite', 1, 'Favorite'),
(956, 'LBL_Teaches', 1, 'Teaches'),
(957, 'LBL_Speaks', 1, 'Speaks'),
(958, 'LBL_Message', 1, 'Message'),
(959, 'LBL_12_Hours', 1, '12 Hours'),
(960, 'LBL_24_Hours', 1, '24 Hours'),
(961, 'LBL_48_Hours', 1, '48 Hours'),
(962, 'LBL_No_Result_found!!', 1, 'No Result Found!!'),
(963, 'LBL_Manage_Teacher_Requests', 1, 'Manage Teacher Requests'),
(964, 'LBL_Teacher_Requests', 1, 'Teacher Requests'),
(965, 'LBL_Accept_Teacher_Approval_Terms_&_condition', 1, 'Accept Teacher Approval Terms & Condition'),
(966, 'LBL_Tutors', 1, 'Tutors'),
(967, 'LBL_Share', 1, 'Share'),
(968, 'LBL_Share_On', 1, 'Share On'),
(969, 'LBL_Teaches:', 1, 'Teaches:'),
(970, 'LBL_Students:', 1, 'Students:'),
(971, 'LBL_Lessons:', 1, 'Lessons:'),
(972, 'LBL_Teaching_Expertise', 1, 'Teaching Expertise'),
(973, 'LBL_Save', 1, 'Save'),
(974, 'LBL_FREE_Trail', 1, 'Free Trail'),
(975, 'LBL_Book_your_trial_FREE_for_30_Mins_only', 1, 'Book Your Trial Free For 30 Mins Only'),
(976, 'LBL_Book_Free_Trial', 1, 'Book Free Trial'),
(977, 'LBL_Please_login_to_book', 1, 'Please Login To Book'),
(978, 'LBL_Redirecting_in_3_seconds.', 1, 'Redirecting In 3 Seconds.'),
(979, 'LBL_{x}_Minute_Lesson', 1, '{x} Minute Lesson'),
(980, 'LBL_Checkout', 1, 'Checkout'),
(981, 'LBL_Cart', 1, 'Cart'),
(982, 'LBL_Product', 1, 'Product'),
(983, 'LBL_I_accept_to_the', 1, 'I Accept To The'),
(984, 'LBL_TERMS_AND_CONDITION', 1, 'Terms And Condition'),
(985, 'LBL_Lesson_Packages_Management', 1, 'Lesson Packages Management'),
(986, 'LBL_My_Students', 1, 'My Students'),
(987, 'LBL_Search_by_keyword', 1, 'Search By Keyword'),
(988, 'LBL_Price_Single/Bulk', 1, 'Price Single/bulk'),
(989, 'LBL_Scheduled', 1, 'Scheduled'),
(990, 'LBL_Past', 1, 'Past'),
(991, 'LBL_Unscheduled', 1, 'Unscheduled'),
(992, 'LBL_Search_Again', 1, 'Search Again'),
(993, 'LBL_Need_to_be_scheduled', 1, 'Need To Be Scheduled'),
(994, 'LBL_Completed', 1, 'Completed'),
(995, 'LBL_Issue_Reported', 1, 'Issue Reported'),
(996, 'LBL_Upcoming', 1, 'Upcoming'),
(997, 'LBL_My_Lessons', 1, 'My Lessons'),
(998, 'LBL_List', 1, 'List'),
(999, 'LBL_Calender', 1, 'Calender'),
(1000, 'LBL_My_Lesson_Plan', 1, 'My Lesson Plan'),
(1001, 'LBL_Tags', 1, 'Tags'),
(1002, 'LBL_Level', 1, 'Level'),
(1003, 'LBL_Teacher_Courses', 1, 'Teacher Courses'),
(1004, 'LBL_View_Plans', 1, 'View Plans'),
(1005, 'LBL_Teacher_Dashboard', 1, 'Teacher Dashboard'),
(1006, 'LBL_No._Of_Lessons_plans', 1, 'No. Of Lessons Plans'),
(1007, 'LBL_Enter_the_value_with_comma_Separated_used_in_teacher_dashboard_for_My_couses_page_No._Of_Lessons_plans)', 1, 'Enter The Value With Comma Separated Used In Teacher Dashboard For My Couses Page No. Of Lessons Plans)'),
(1008, 'MSG_Session_seems_to_be_expired', 1, 'Session Seems To Be Expired'),
(1009, 'LBl_Course_Category', 1, 'Course Category'),
(1010, 'LBl_No._Of_Lessons', 1, 'No. Of Lessons'),
(1011, 'LBl_Difficulty_Level', 1, 'Difficulty Level'),
(1012, 'LBL_NOTE:_Press_enter_inside_text_box_to_create_tag!', 1, 'Note: Press Enter Inside Text Box To Create Tag!'),
(1013, 'LBl_Course_Image', 1, 'Course Image'),
(1014, 'LBL_Select_Lesson_Plan', 1, 'Select Lesson Plan'),
(1015, 'LBL_Select_Lesson_Plans', 1, 'Select Lesson Plans'),
(1016, 'LBL_Manage_Course_Categories', 1, 'Manage Course Categories'),
(1017, 'LBL_Course_Categories', 1, 'Course Categories'),
(1018, 'LBL_Course_Categories_Listing', 1, 'Course Categories Listing'),
(1019, 'LBL_Add_Course_Category', 1, 'Add Course Category'),
(1020, 'LBL_Course_Category_Identifier', 1, 'Course Category Identifier'),
(1021, 'LBL_Course_Category_Title', 1, 'Course Category Title'),
(1022, 'LBL_Course_Category_Setup', 1, 'Course Category Setup'),
(1023, 'LBL_Add_3_Lesson_Plans!', 1, 'Add 3 Lesson Plans!'),
(1024, 'LBl_Plan_Files', 1, 'Plan Files'),
(1025, 'LBl_Links', 1, 'Links'),
(1026, 'LBL_NOTE:_Press_enter_link_in_new_line_by_pressing_enter!', 1, 'Note: Press Enter Link In New Line By Pressing Enter!'),
(1027, 'LBl_Plan_Banner_Image', 1, 'Plan Banner Image'),
(1028, 'LBL_Lesson_Plan_Saved_Successfully!', 1, 'Lesson Plan Saved Successfully!'),
(1029, 'LBL_Assign', 1, 'Assign'),
(1030, 'LBL_Lesson_Plan_Assigned_Successfully!', 1, 'Lesson Plan Assigned Successfully!'),
(1031, 'LBL_Course_Saved_Successfully!', 1, 'Course Saved Successfully!'),
(1032, 'LBL_N/A', 1, 'N/a'),
(1033, 'LBL_Send_Message', 1, 'Send Message'),
(1034, 'LBL_Message_Sent_Successfully!', 1, 'Message Sent Successfully!'),
(1035, 'LBL_Schedule', 1, 'Schedule'),
(1036, 'LBL_Details', 1, 'Details'),
(1037, 'LBL_Minute', 1, 'Minute'),
(1038, 'LBL_Lesson', 1, 'Lesson'),
(1039, 'LBL_Reschedule', 1, 'Reschedule'),
(1040, 'LBL_Cancel', 1, 'Cancel'),
(1041, 'LBL_Attach_Lesson_Plan', 1, 'Attach Lesson Plan'),
(1042, 'LBL_Request_Reschedule', 1, 'Request Reschedule'),
(1043, 'LBL_Lesson_Cancelled_Successfully!', 1, 'Lesson Cancelled Successfully!'),
(1044, 'LBL_View_Lesson_Plan', 1, 'View Lesson Plan'),
(1045, 'LBL_Change_Plan', 1, 'Change Plan'),
(1046, 'LBL_Remove_Plan', 1, 'Remove Plan'),
(1047, 'LBL_Lesson_Plan_Removed_Successfully!', 1, 'Lesson Plan Removed Successfully!'),
(1048, 'MSG_UNRECOGNISED_FILE', 1, 'Unrecognised File'),
(1049, 'LBL_Preference', 1, 'Preference'),
(1050, 'MSG_Teacher_Approval_Form', 1, 'Teacher Approval Form'),
(1051, 'MSG_Teacher_Approval_Requests', 1, 'Teacher Approval Requests'),
(1052, 'LBL_Cancel_Plan', 1, 'Cancel Plan'),
(1053, 'LBL_My_Flashcards', 1, 'My Flashcards'),
(1054, 'LBL_Add_Flashcard', 1, 'Add Flashcard'),
(1055, 'LBL_Word', 1, 'Word'),
(1056, 'LBL_Definition', 1, 'Definition'),
(1057, 'LBL_Manage_Lesson_Packages', 1, 'Manage Lesson Packages'),
(1058, 'LBL_Lesson_Packages', 1, 'Lesson Packages'),
(1059, 'LBL_Lesson_Packages_Listing', 1, 'Lesson Packages Listing'),
(1060, 'LBL_Add_Lesson_Package', 1, 'Add Lesson Package'),
(1061, 'LBL_Lesson_Package_Identifier', 1, 'Lesson Package Identifier'),
(1062, 'LBL_Lesson_Package_Title', 1, 'Lesson Package Title'),
(1063, 'LBL_Lesson_Package_No', 1, 'Lesson Package No'),
(1064, 'LBL_Lesson_Package_Setup', 1, 'Lesson Package Setup'),
(1065, 'LBL_My_Teachers', 1, 'My Teachers'),
(1066, 'MSG_Teacher_Approval_Request_Setup_Successful', 1, 'Teacher Approval Request Setup Successful'),
(1067, 'LBL_Hello', 1, 'Hello'),
(1068, 'LBL_Thank_you_for_submitting_your_application', 1, 'Thank You For Submitting Your Application'),
(1069, 'LBL_Application_Reference', 1, 'Application Reference'),
(1070, 'LBL_application_awaiting_approval', 1, 'Application Awaiting Approval'),
(1071, 'LBL_Teacher_Request_Detail', 1, 'Teacher Request Detail'),
(1072, 'LBL_Language_Label', 1, 'Language Label'),
(1073, 'LBL_Manage_Blog_Post_Categories', 1, 'Manage Blog Post Categories'),
(1074, 'LBL_Root_categories', 1, 'Root Categories'),
(1075, 'LBL_Add_Blog_Post_Category', 1, 'Add Blog Post Category'),
(1076, 'LBL_Category_Name', 1, 'Category Name'),
(1077, 'LBL_Subcategories', 1, 'Subcategories'),
(1078, 'LBL_Draft', 1, 'Draft'),
(1079, 'LBL_Published', 1, 'Published'),
(1080, 'LBL_Post_Status', 1, 'Post Status'),
(1081, 'LBL_Manage_Blog_Posts', 1, 'Manage Blog Posts'),
(1082, 'LBL_Blog_Post_List', 1, 'Blog Post List'),
(1083, 'LBL_Add_Blog_Post', 1, 'Add Blog Post'),
(1084, 'LBL_Post_Title', 1, 'Post Title'),
(1085, 'LBL_Category', 1, 'Category'),
(1086, 'LBL_Published_Date', 1, 'Published Date'),
(1087, 'LBL_Posted', 1, 'Posted'),
(1088, 'LBL_Rejected', 1, 'Rejected'),
(1089, 'LBL_Contribution_Status', 1, 'Contribution Status'),
(1090, 'LBL_Manage_Blog_Contributions', 1, 'Manage Blog Contributions'),
(1091, 'LBL_Blog_Contribution_List', 1, 'Blog Contribution List'),
(1092, 'LBL_Author_Name', 1, 'Author Name'),
(1093, 'LBL_Author_Email', 1, 'Author Email'),
(1094, 'LBL_Author_Phone', 1, 'Author Phone'),
(1095, 'LBL_Posted_On', 1, 'Posted On'),
(1096, 'LBL_Comment_Status', 1, 'Comment Status'),
(1097, 'LBL_Manage_Blog_Comments', 1, 'Manage Blog Comments'),
(1098, 'LBL_Blog_Comment_List', 1, 'Blog Comment List'),
(1099, 'Lbl_Weyakyak', 1, 'Weyakyak'),
(1100, 'Lbl_Faq', 1, 'Faq'),
(1101, 'Lbl_About', 1, 'About'),
(1102, 'Lbl_Privacy', 1, 'Privacy'),
(1103, 'Lbl_Contact', 1, 'Contact'),
(1104, 'LBL_General_Queries', 1, 'General Queries'),
(1105, 'LBL_Application_/_Requirements', 1, 'Application / Requirements'),
(1106, 'LBL_See_All_3_Articles', 1, 'See All 3 Articles'),
(1107, 'LBL_See_All_1_Articles', 1, 'See All 1 Articles'),
(1108, 'LBL_Go_Back', 1, 'Go Back'),
(1109, 'LBL_Was_this_article_helpful?', 1, 'Was This Article Helpful?'),
(1110, 'LBL_Have_more_questions?', 1, 'Have More Questions?'),
(1111, 'LBL_Submit_a_Request', 1, 'Submit A Request'),
(1112, 'LBL_Other_Articles', 1, 'Other Articles'),
(1113, 'LBL_The_place_where_we_write_some_words', 1, 'The Place Where We Write Some Words'),
(1114, 'LBL_Blog_Search', 1, 'Blog Search'),
(1115, 'Lbl_in', 1, 'In'),
(1116, 'Lbl_on', 1, 'On'),
(1117, 'Lbl_View_Full_Post', 1, 'View Full Post'),
(1118, 'LBL_See_more_at', 1, 'See More At'),
(1119, 'LBL_Blog_Searchs', 1, 'Blog Searchs'),
(1120, 'Lbl_Write_For_Us', 1, 'Write For Us'),
(1121, 'Lbl_We_are_constantly_looking_for_writers_and_contributors_to_help_us_create_great_content_for_our_blog_visitors.', 1, 'We Are Constantly Looking For Writers And Contributors To Help Us Create Great Content For Our Blog Visitors.'),
(1122, 'Lbl_Contribute', 1, 'Contribute'),
(1123, 'Lbl_Categories', 1, 'Categories'),
(1124, 'Lbl_By', 1, 'By'),
(1125, 'Lbl_Comments(%s)', 1, 'Comments(%s)'),
(1126, 'Lbl_Post_your_comments', 1, 'Post Your Comments'),
(1127, 'Lbl_Weyakyak', 2, 'Weyakyak'),
(1128, 'Lbl_Blog', 2, 'Blog'),
(1129, 'Lbl_Faq', 2, 'Faq'),
(1130, 'Lbl_About', 2, 'About'),
(1131, 'Lbl_Privacy', 2, 'Privacy'),
(1132, 'Lbl_Contact', 2, 'Contact'),
(1133, 'ERR_INVALID_USERNAME_OR_PASSWORD', 2, 'Invalid Username Or Password'),
(1134, 'MSG_LOGIN_SUCCESSFULL', 2, 'Login Successfull'),
(1135, 'LBL_Dashboard', 2, 'Dashboard'),
(1136, 'LBL_Flashcards', 2, 'Flashcards'),
(1137, 'LBL_Wallet', 2, 'Wallet'),
(1138, 'LBL_Orders', 2, 'Orders'),
(1139, 'LBL_Reports', 2, 'Reports'),
(1140, 'LBL_General', 2, 'General'),
(1141, 'LBL_First_Name', 2, 'First Name'),
(1142, 'LBL_Last_Name', 2, 'Last Name'),
(1143, 'LBL_Phone', 2, 'Phone'),
(1144, 'LBL_TimeZone', 2, 'Timezone'),
(1145, 'LBL_Biography', 2, 'Biography'),
(1146, 'LBL_Personal_Information', 2, 'Personal Information'),
(1147, 'LBL_Change_Avataar', 2, 'Change Avataar'),
(1148, 'LBL_Upload', 2, 'Upload'),
(1149, 'LBL_Home', 2, 'Home'),
(1150, 'LBL_Tutors', 2, 'Tutors'),
(1151, 'LBL_Share', 2, 'Share'),
(1152, 'LBL_Share_On', 2, 'Share On'),
(1153, 'LBL_Teaches:', 2, 'Teaches:'),
(1154, 'LBL_Students:', 2, 'Students:'),
(1155, 'LBL_Lessons:', 2, 'Lessons:'),
(1156, 'LBL_Accents', 2, 'Accents'),
(1157, 'LBL_Teaches_Level', 2, 'Teaches Level'),
(1158, 'LBL_Learner_Ages', 2, 'Learner Ages'),
(1159, 'LBL_Subjects', 2, 'Subjects'),
(1160, 'LBL_Test_Preparations', 2, 'Test Preparations'),
(1161, 'LBL_Teaching_Expertise', 2, 'Teaching Expertise'),
(1162, 'LBL_Resume', 2, 'Resume'),
(1163, 'LBL_FREE_Trail', 2, 'Free Trail'),
(1164, 'LBL_Book_your_trial_FREE_for_30_Mins_only', 2, 'Book Your Trial Free For 30 Mins Only'),
(1165, 'LBL_Book_Free_Trial', 2, 'Book Free Trial'),
(1166, 'LBL_Education', 2, 'Education'),
(1167, 'LBL_Certification', 2, 'Certification'),
(1168, 'LBL_Work_Experience', 2, 'Work Experience'),
(1169, 'MSG_Please_Enter_Valid_password', 2, 'Please Enter Valid Password'),
(1170, 'LBL_I_accept_to_the', 2, 'I Accept To The'),
(1171, 'LBL_Show_Password', 2, 'Show Password'),
(1172, 'LBL_Hide_Password', 2, 'Hide Password'),
(1173, 'LBL_TERMS_AND_CONDITION', 2, 'Terms And Condition'),
(1174, 'LBL_Comet_chat_Api_Key', 1, 'Comet Chat Api Key'),
(1175, 'LBL_Comet_Chat_App_ID', 1, 'Comet Chat App Id'),
(1176, 'LBL_Learner_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also!', 1, 'Learner Ends The Lesson Do Yoy Want To End It From Your End Also!'),
(1177, 'LBL_Join_Lesson', 1, 'Join Lesson'),
(1178, 'LBL_End_Lesson', 1, 'End Lesson'),
(1179, 'LBL_Info', 1, 'Info'),
(1180, 'LBL_Learner_Details', 1, 'Learner Details'),
(1181, 'LBL_Teacher_Details', 1, 'Teacher Details'),
(1182, 'LBL_Lesson_Details', 1, 'Lesson Details'),
(1183, 'LBL_Search_Flash_Cards...', 1, 'Search Flash Cards...'),
(1184, 'LBL_Teacher_Has_Joined_Now_you_can_also_Join_The_Lesson!', 1, 'Teacher Has Joined Now You Can Also Join The Lesson!'),
(1185, 'LBL_Teacher_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also', 1, 'Teacher Ends The Lesson Do Yoy Want To End It From Your End Also'),
(1186, 'LBL_Duration_assigned_to_this_lesson_is_completed_now_do_you_want_to_continue?', 1, 'Duration Assigned To This Lesson Is Completed Now Do You Want To Continue?'),
(1187, 'LBL_Continue', 1, 'Continue'),
(1188, 'LBL_Time', 1, 'Time'),
(1189, 'LBL_Book_Lesson', 1, 'Book Lesson'),
(1190, 'LBL_Free_Trial', 1, 'Free Trial'),
(1191, 'LBL_Package_Lessons', 1, 'Package Lessons'),
(1192, 'LBL_Note:_Enter_Number_of_lessons_in_a_package', 1, 'Note: Enter Number Of Lessons In A Package (In Hrs)'),
(1193, 'LBL_Date:', 1, 'Date:'),
(1194, 'LBL_Time:', 1, 'Time:'),
(1195, 'LBL_Price:', 1, 'Price:'),
(1196, 'LBL_Confirm_Order', 1, 'Confirm Order'),
(1197, 'LBL_Total', 1, 'Total'),
(1198, 'LBL_Payment', 1, 'Payment'),
(1199, 'LBL_Payment_to_be_made', 1, 'Payment To Be Made'),
(1200, 'LBL_Reserve_a_Session', 1, 'Reserve A Session'),
(1201, 'LBL_Per_Hour', 1, 'Per Hour'),
(1202, 'LBL_Book_Now', 1, 'Book Now'),
(1203, 'LBL_Lessons_Packages', 1, 'Lessons Packages'),
(1204, 'LBL_How_many_lessons_would_you_like_to_purchase?', 1, 'How Many Lessons Would You Like To Purchase?'),
(1205, 'LBL_Pick_a_payment_method.', 1, 'Pick A Payment Method.'),
(1206, 'LBL_Confirm_Payment', 1, 'Confirm Payment'),
(1207, 'LBL_Pay_using_{payment-method-name}', 1, 'Pay Using {payment-method-name}'),
(1208, 'LBL_Net_Payable_:', 1, 'Net Payable :'),
(1209, 'LBL_Gateway_Name', 1, 'Gateway Name'),
(1210, 'LBL_Merchant_Email', 1, 'Merchant Email'),
(1211, 'LBL_Order_Payment_Status_Cancelled', 1, 'Order Payment Status Cancelled'),
(1212, 'LBL_Order_Payment_Status_Pending', 1, 'Order Payment Status Pending'),
(1213, 'LBL_Order_Payment_Status_Paid', 1, 'Order Payment Status Paid'),
(1214, 'LBL_Order_Status_(Initial)', 1, 'Order Status (initial)'),
(1215, 'LBL_Order_Status_(Pending)', 1, 'Order Status (pending)'),
(1216, 'LBL_Order_Status_(Processed)', 1, 'Order Status (processed)'),
(1217, 'LBL_Order_Status_(Completed)', 1, 'Order Status (completed)'),
(1218, 'LBL_Order_Status_(Others)', 1, 'Order Status (others)'),
(1219, 'LBL_Default_Child_Order_Status', 1, 'Default Child Order Status'),
(1220, 'LBL_Default_Child_Paid_Order_Status', 1, 'Default Child Paid Order Status'),
(1221, 'LBL_Set_the_default_child_order_status_when_an_order_is_marked_Paid.', 1, 'Set The Default Child Order Status When An Order Is Marked Paid.'),
(1222, 'MSG_Order_Payment_Gateway_Description_{website-name}_{order-id}', 1, 'Order Payment Gateway Description {website-name} {order-id}'),
(1223, 'LBL_Payable_Amount', 1, 'Payable Amount'),
(1224, 'LBL_Order_Invoice', 1, 'Order Invoice'),
(1225, 'MSG_We_are_redirecting_to_payment_page', 1, 'We Are Redirecting To Payment Page'),
(1226, 'MSG_learner_success_order_{dashboard-url}_{contact-us-page-url}', 1, '<p>Your order has been successfully processed!</p><p>You can view your order history by going to the <a href=\"{dashboard-url}\">my account</a> page .</p><p>Please direct any questions you have to the <a href=\"{contact-us-page-url}\">web portal owner</a>.</p><p>Thanks for choosing us online!'),
(1227, 'LBL_Congratulations', 1, 'Congratulations'),
(1228, 'LBL_Received_Payment', 1, 'Received Payment'),
(1229, 'LBL_Note', 1, 'Note'),
(1230, 'LBL_This_lesson_is_Unscheduled._schedule_it.', 1, 'This Lesson Is Unscheduled. Schedule It.'),
(1232, 'MSG_learner_success_order_{dashboard-url}_{contact-us-page-url}', 2, '<p>Your order has been successfully processed!</p><p>You can view your order history by going to the <a href=\"{dashboard-url}\">my account</a> page .</p><p>Please direct any questions you have to the <a href=\"{contact-us-page-url}\">web portal owner</a>.</p><p>Thanks for choosing us online!'),
(1233, 'MSG_learner_failure_order_{contact-us-page-url}', 1, '<p>There was a problem processing your payment and the order did not complete.</p>\r\n<p>Possible reasons are: </p>\r\n<ul>\r\n  <li>Insufficient funds</li>\r\n  <li>Verification failed</li>\r\n</ul>\r\n<p>Please try to order again using a different payment method.</p>\r\n<p>If the problem persists please <a href=\"{contact-us-page-url}\">contact us</a> with the details of the order you are trying to place.</p>'),
(1235, 'MSG_learner_failure_order_{contact-us-page-url}', 2, '<p>There was a problem processing your payment and the order did not complete.</p>\r\n<p>Possible reasons are: </p>\r\n<ul>\r\n  <li>Insufficient funds</li>\r\n  <li>Verification failed</li>\r\n</ul>\r\n<p>Please try to order again using a different payment method.</p>\r\n<p>If the problem persists please <a href=\"{contact-us-page-url}\">contact us</a> with the details of the order you are trying to place.</p>'),
(1236, 'LBL_-NA-', 1, '-na-'),
(1237, 'LBL_This_lesson_is_Unscheduled._Encourage_your_student_to_schedule_it.', 1, 'This Lesson Is Unscheduled. Encourage Your Student To Schedule It.'),
(1238, 'LBL_These_prices_are_Unlocked', 1, 'These Prices Are Unlocked'),
(1239, 'LBL_Your_Name', 1, 'Your Name'),
(1240, 'LBL_Your_Email', 1, 'Your Email'),
(1241, 'LBL_Your_Phone', 1, 'Your Phone'),
(1242, 'LBL_Your_Message', 1, 'Your Message'),
(1243, 'LBL_Send_us_a_message', 1, 'Send Us A Message'),
(1244, 'LBL_My_Teachers', 2, 'My Teachers'),
(1245, 'LBL_Search', 2, 'Search'),
(1246, 'LBL_Search_by_keyword', 2, 'Search By Keyword'),
(1247, 'LBL_Status', 2, 'Status'),
(1248, 'LBL_Learner', 2, 'Learner'),
(1249, 'LBL_Price_Single/Bulk', 2, 'Price Single/bulk'),
(1250, 'LBL_Scheduled', 2, 'Scheduled'),
(1251, 'LBL_Past', 2, 'Past'),
(1252, 'LBL_Unscheduled', 2, 'Unscheduled'),
(1253, 'LBL_Actions', 2, 'Actions'),
(1254, 'LBL_Search_Again', 2, 'Search Again'),
(1255, 'LBL_Reserve_a_Session', 2, 'Reserve A Session'),
(1256, 'LBL_Per_Hour', 2, 'Per Hour'),
(1257, 'LBL_Book_Now', 2, 'Book Now'),
(1258, 'LBL_Date:', 2, 'Date:'),
(1259, 'LBL_Time:', 2, 'Time:'),
(1260, 'LBL_Price:', 2, 'Price:'),
(1261, 'LBL_Book_Lesson', 2, 'Book Lesson'),
(1262, 'LBL_Redirecting_in_3_seconds.', 2, 'Redirecting In 3 Seconds.'),
(1263, 'LBL_Confirm_Order', 2, 'Confirm Order'),
(1264, 'LBL_Checkout', 2, 'Checkout'),
(1265, 'LBL_Payment', 2, 'Payment'),
(1266, 'LBL_Payment_to_be_made', 2, 'Payment To Be Made'),
(1267, 'LBL_Cart', 2, 'Cart'),
(1268, 'LBL_Product', 2, 'Product'),
(1269, 'LBL_Price', 2, 'Price'),
(1270, 'LBL_Total', 2, 'Total'),
(1271, 'LBL_Lessons_Packages', 2, 'Lessons Packages'),
(1272, 'LBL_How_many_lessons_would_you_like_to_purchase?', 2, 'How Many Lessons Would You Like To Purchase?'),
(1273, 'LBL_Pick_a_payment_method.', 2, 'Pick A Payment Method.'),
(1274, 'LBL_Confirm_Payment', 2, 'Confirm Payment'),
(1275, 'LBL_Pay_using_{payment-method-name}', 2, 'Pay Using {payment-method-name}'),
(1276, 'LBL_Net_Payable_:', 2, 'Net Payable :'),
(1277, 'LBL_Need_to_be_scheduled', 2, 'Need To Be Scheduled'),
(1278, 'LBL_Completed', 2, 'Completed'),
(1279, 'LBL_Cancelled', 2, 'Cancelled'),
(1280, 'LBL_Issue_Reported', 2, 'Issue Reported'),
(1281, 'LBL_Upcoming', 2, 'Upcoming'),
(1282, 'LBL_My_Lessons', 2, 'My Lessons'),
(1283, 'LBL_List', 2, 'List'),
(1284, 'LBL_Calender', 2, 'Calender'),
(1285, 'MSG_EMAIL_VERIFIED_SUCCESFULLY', 2, 'Email Verified Succesfully'),
(1286, 'LBL_Last_Reviewed', 1, 'Last Reviewed'),
(1287, 'LBL_Review_All_Flashcards', 1, 'Review All Flashcards'),
(1288, 'LBL_Teacher_Free_Trial_Booked_for_Order:_{order-id}', 1, 'Teacher Free Trial Booked For Order: {order-id}'),
(1289, 'LBL_User_Wallet', 1, 'User Wallet'),
(1290, 'LBL_Payment_From_User_Wallet', 1, 'Payment From User Wallet'),
(1291, 'LBL_Congratulations', 2, 'Congratulations'),
(1292, 'MSG_Teacher_booking_selection_is_not_yet_been_selected,_Please_try_selecting_the_appropriate_teacher_and_start_booking_lesson.', 2, 'Teacher Booking Selection Is Not Yet Been Selected, Please Try Selecting The Appropriate Teacher And Start Booking Lesson.'),
(1293, 'LBL_Defination', 1, 'Defination'),
(1294, 'LBL_Pronunciation', 1, 'Pronunciation'),
(1295, 'LBL_Notes', 1, 'Notes'),
(1296, 'LBL_Add_New_Flashcard', 1, 'Add New Flashcard'),
(1297, 'LBL_Correct', 1, 'Correct'),
(1298, 'LBL_Upper_Almost', 1, 'Upper Almost'),
(1299, 'LBL_Wrong', 1, 'Wrong'),
(1300, 'LBL_Click_On_Words_To_Flip_It', 1, 'Click On Words To Flip It'),
(1301, 'LBL_Flashcard_Saved_Successfully!', 1, 'Flashcard Saved Successfully!'),
(1302, 'LBL_Review', 1, 'Review'),
(1303, 'LBL_Incorrect_Answers', 1, 'Incorrect Answers'),
(1304, 'LBL_Almost_Correct_Answers', 1, 'Almost Correct Answers'),
(1305, 'LBL_Correct_Answers', 1, 'Correct Answers'),
(1306, 'LBL_Close', 1, 'Close'),
(1307, 'LBL_Restart', 1, 'Restart'),
(1312, 'Btn_Post_Comment', 1, 'Post Comment'),
(1313, 'Lbl_What_do_you_think', 1, 'What Do You Think'),
(1314, 'LBL_General_Queries', 2, 'General Queries'),
(1315, 'LBL_Application_/_Requirements', 2, 'Application / Requirements'),
(1316, 'LBL_Payments', 2, 'Payments'),
(1317, 'LBL_Courses', 2, 'Courses'),
(1318, 'LBL_Lesson_Plan', 2, 'Lesson Plan'),
(1319, 'LBL_See_All_3_Articles', 2, 'See All 3 Articles'),
(1320, 'LBL_See_All_1_Articles', 2, 'See All 1 Articles'),
(1321, 'LBL_Your_Name', 2, 'Your Name'),
(1322, 'LBL_Your_Email', 2, 'Your Email'),
(1323, 'LBL_Your_Phone', 2, 'Your Phone'),
(1324, 'LBL_Your_Message', 2, 'Your Message'),
(1325, 'LBL_Send_us_a_message', 2, 'Send Us A Message'),
(1326, 'MSG_Use_My_Wallet_Credits', 1, 'Use My Wallet Credits'),
(1327, 'LBL_Amount_in_your_wallet', 1, 'Amount In Your Wallet'),
(1328, 'LBL_Remaining_wallet_balance', 1, 'Remaining Wallet Balance'),
(1329, 'LBL_Select_an_option_to_pay_balance', 1, 'Select An Option To Pay Balance'),
(1330, 'LBL_Please_login_to_book', 2, 'Please Login To Book'),
(1331, 'LBL_My_Flashcards', 2, 'My Flashcards'),
(1332, 'LBL_Add_Flashcard', 2, 'Add Flashcard'),
(1333, 'LBL_All_Languages', 2, 'All Languages'),
(1334, 'LBL_Last_Reviewed', 2, 'Last Reviewed'),
(1335, 'LBL_Review_All_Flashcards', 2, 'Review All Flashcards'),
(1336, 'LBL_Word', 2, 'Word'),
(1337, 'LBL_Definition', 2, 'Definition'),
(1338, 'LBL_Preferences_Lessons__Listing', 1, 'Preferences Lessons  Listing'),
(1339, 'LBL_Preferences_Learner_Ages__Listing', 1, 'Preferences Learner Ages  Listing'),
(1340, 'ERR_LOGIN_ATTEMPT_LIMIT_EXCEEDED_PLEASE_TRY_LATER', 1, 'Login Attempt Limit Exceeded Please Try Later'),
(1341, 'LBL_Manage_Navigations', 1, 'Manage Navigations'),
(1342, 'LBL_Navigations', 1, 'Navigations'),
(1343, 'LBL_Navigation_Listing', 1, 'Navigation Listing'),
(1344, 'LBL_navigation_Pages_Listing', 1, 'Navigation Pages Listing'),
(1345, 'LBL_back_To_Navigations', 1, 'Back To Navigations'),
(1346, 'LBL_Add_Navigation_Page', 1, 'Add Navigation Page'),
(1347, 'LBL_Caption_Identifier', 1, 'Caption Identifier'),
(1348, 'LBL_External_Page', 1, 'External Page'),
(1349, 'LBL_Product_Category_Page', 1, 'Product Category Page'),
(1350, 'LBL_Link_Target', 1, 'Link Target'),
(1351, 'LBL_Current_Window', 1, 'Current Window'),
(1352, 'LBL_Blank_Window', 1, 'Blank Window'),
(1353, 'LBL_Login_Protected', 1, 'Login Protected'),
(1354, 'LBL_Both', 1, 'Both'),
(1355, 'LBL_Link_to_CMS_Page', 1, 'Link To Cms Page'),
(1356, 'LBL_Prefix_with_{SITEROOT}_if_u_want_to_generate_system_site_url', 1, 'Prefix With {siteroot} If U Want To Generate System Site Url'),
(1357, 'LBL_etc', 1, 'Etc'),
(1358, 'LBL_Display_Order', 1, 'Display Order'),
(1359, 'LBL_Navigation_Link_Setup', 1, 'Navigation Link Setup'),
(1360, 'LBL_navigation_Setup', 1, 'Navigation Setup'),
(1361, 'LBL_Navigation_Link_Setup_Successful', 1, 'Navigation Link Setup Successful'),
(1362, 'Lbl_Ratings', 2, 'Ratings'),
(1363, 'Lbl_Average', 2, 'Average'),
(1364, 'MSG_Use_My_Wallet_Credits', 2, 'Use My Wallet Credits'),
(1365, 'LBL_Pay_Now', 2, 'Pay Now'),
(1366, 'LBL_Sufficient_balance_in_your_wallet', 2, 'Sufficient Balance In Your Wallet'),
(1367, 'LBL_Amount_in_your_wallet', 2, 'Amount In Your Wallet'),
(1368, 'LBL_Remaining_wallet_balance', 2, 'Remaining Wallet Balance'),
(1369, 'LBL_ORDER_PLACED_{order-id}', 1, 'Order Placed {order-id}'),
(1370, 'LBL_Schedule', 2, 'Schedule'),
(1371, 'LBL_Details', 2, 'Details'),
(1372, 'LBL_View', 2, 'View'),
(1373, 'LBL_Reschedule', 2, 'Reschedule'),
(1374, 'LBL_Cancel', 2, 'Cancel'),
(1375, 'Lbl_Ratings', 1, 'Ratings'),
(1376, 'Lbl_Average', 1, 'Average'),
(1377, 'LBL_Pay_Now', 1, 'Pay Now'),
(1378, 'LBL_Sufficient_balance_in_your_wallet', 1, 'Sufficient Balance In Your Wallet'),
(1379, 'L_debited', 1, 'Debited'),
(1380, 'MSG_Reviews', 1, 'Reviews'),
(1381, 'LBL_Bible_Content', 1, 'Bible Content'),
(1382, 'LBL_Manage_Bible_Content', 1, 'Manage Bible Content'),
(1383, 'LBL_Add_content', 1, 'Add Content'),
(1384, 'LBL_Bible', 1, 'Bible'),
(1385, 'LBL_Manage_Purchased_lessons', 1, 'Manage Purchased Lessons'),
(1386, 'LBL_Purchased_Lessons', 1, 'Purchased Lessons'),
(1387, 'LBL_Order_Id', 1, 'Order Id'),
(1388, 'LBL_View_Lessons', 1, 'View Lessons'),
(1389, 'LBL_Lesson_Ended_Successfully!', 1, 'Lesson Ended Successfully!'),
(1390, 'LBL_This_lesson_is_completed._rate_it.', 1, 'This Lesson Is Completed. Rate It.'),
(1391, 'LBL_Rate_Lesson', 1, 'Rate Lesson'),
(1392, 'LBL_This_lesson_is_completed._Encourage_your_learner_to_rate_it.', 1, 'This Lesson Is Completed. Encourage Your Learner To Rate It.'),
(1393, 'LBL_View_Schedules', 1, 'View Schedules'),
(1394, 'LBL_Session_Date', 1, 'Session Date'),
(1395, 'LBL_Session_Start_Time', 1, 'Session Start Time'),
(1396, 'LBL_Session_End_Time', 1, 'Session End Time'),
(1397, 'LBL_Update_Lesson_Status', 1, 'Update Lesson Status'),
(1398, 'Lbl_Comments', 1, 'Comments'),
(1399, 'MSG_Allowed_Extensions', 1, 'Allowed Extensions'),
(1400, 'Lbl_We_are_constantly_looking_for_writers_and_contributors_to_help_us_create_great_content_for_our_blog_visitors._If_you_can_curate_content_that_you_and_our_visitors_would_love_to_read_and_share,_this_place_is_for_you.', 1, 'We Are Constantly Looking For Writers And Contributors To Help Us Create Great Content For Our Blog Visitors. If You Can Curate Content That You And Our Visitors Would Love To Read And Share, This Place Is For You.'),
(1401, 'Lbl_Blog_Contribution', 1, 'Blog Contribution'),
(1402, 'LBL_View_Lesson_Detail', 1, 'View Lesson Detail'),
(1403, 'LBL_Scheduled_Start_Time', 1, 'Scheduled Start Time'),
(1404, 'LBL_Scheduled_End_Time', 1, 'Scheduled End Time'),
(1405, 'LBL_Lesson_Status', 1, 'Lesson Status'),
(1406, 'LBL_Manage_Issues_Reported', 1, 'Manage Issues Reported'),
(1407, 'LBL_Open', 1, 'Open'),
(1408, 'LBL_In_Progress', 1, 'In Progress'),
(1409, 'LBL_Resolved', 1, 'Resolved'),
(1410, 'LBL_Issue_Status', 1, 'Issue Status'),
(1411, 'LBL_Reported_By', 1, 'Reported By'),
(1412, 'LBL_Issues_Reported', 1, 'Issues Reported'),
(1413, 'LBL_Lesson_Id', 1, 'Lesson Id'),
(1414, 'LBL_Reporter', 1, 'Reporter'),
(1415, 'LBL_Manage_Lessons', 1, 'Manage Lessons'),
(1416, 'LBL_Lesson_Date', 1, 'Lesson Date'),
(1417, 'LBL_Lesson_Start_Time', 1, 'Lesson Start Time'),
(1418, 'LBL_Lesson_Ended_By', 1, 'Lesson Ended By'),
(1419, 'LBL_Lesson_Actual_End_Time', 1, 'Lesson Actual End Time'),
(1420, 'MSG_Clear_Search', 1, 'Clear Search'),
(1421, 'LBL_Manage_Coupons', 1, 'Manage Coupons'),
(1422, 'LBL_Coupons_List', 1, 'Coupons List'),
(1423, 'LBL_Add_New_Coupon', 1, 'Add New Coupon'),
(1424, 'LBL_Coupon_Title', 1, 'Coupon Title'),
(1425, 'LBL_Coupon_Code', 1, 'Coupon Code'),
(1426, 'LBL_Coupon_Discount', 1, 'Coupon Discount'),
(1427, 'LBL_Available', 1, 'Available'),
(1428, 'LBL_Coupon_Identifier', 1, 'Coupon Identifier'),
(1429, 'LBL_Percentage', 1, 'Percentage'),
(1430, 'LBL_Flat', 1, 'Flat'),
(1431, 'LBL_Discount_in', 1, 'Discount In'),
(1432, 'LBL_Discount_Value', 1, 'Discount Value'),
(1433, 'LBL_Min_Order_Value', 1, 'Min Order Value'),
(1434, 'LBL_Max_Discount_Value', 1, 'Max Discount Value'),
(1435, 'LBL_Uses_Per_Coupon', 1, 'Uses Per Coupon'),
(1436, 'LBL_Uses_Per_Customer', 1, 'Uses Per Customer'),
(1437, 'LBL_Coupon_Status', 1, 'Coupon Status'),
(1438, 'LBL_Coupon_Setup', 1, 'Coupon Setup'),
(1439, 'LBL_Enter_Your_code', 1, 'Enter Your Code'),
(1440, 'LBL_Apply', 1, 'Apply'),
(1441, 'LBL_No_Copons_offer_is_available_now.', 1, 'No Copons Offer Is Available Now.'),
(1442, 'LBL_Invalid_Coupon_Code', 1, 'Invalid Coupon Code'),
(1443, 'MSG_Coupon_Setup_Successful.', 1, 'Coupon Setup Successful.'),
(1444, 'LBL_History', 1, 'History'),
(1445, 'LBL_Coupon_Description', 1, 'Coupon Description'),
(1446, 'LBL_Available_Coupons', 1, 'Available Coupons'),
(1447, 'LBL_Click_to_apply_coupon', 1, 'Click To Apply Coupon'),
(1448, 'MSG_cart_discount_coupon_applied', 1, 'Cart Discount Coupon Applied'),
(1449, 'LBL_Discount', 1, 'Discount'),
(1450, 'MSG_cart_discount_coupon_removed', 1, 'Cart Discount Coupon Removed'),
(1451, 'LBL_Payment_Status', 1, 'Payment Status'),
(1452, 'LBL_Select_Payment_Status', 1, 'Select Payment Status'),
(1453, 'LBL_Manage_Giftcards', 1, 'Manage Giftcards'),
(1454, 'LBL_Giftcards', 1, 'Giftcards'),
(1455, 'LBL_Search_in_Order_Id,_Giftcard_Code', 1, 'Search In Order Id, Giftcard Code'),
(1456, 'LBL_Customers_Giftcards_List', 1, 'Customers Giftcards List'),
(1457, 'LBL_Order_Date', 1, 'Order Date'),
(1458, 'LBL_Buy_Giftcard', 1, 'Buy Giftcard'),
(1459, 'LBL_Gift_card', 1, 'Gift Card'),
(1460, 'LBL_Gift_card_Your_Detail', 1, 'Gift Card Your Detail'),
(1461, 'LBL_Giftcard_Amount', 1, 'Giftcard Amount'),
(1462, 'LBL_Meal_Gift_card_Recipient_Detail', 1, 'Meal Gift Card Recipient Detail'),
(1463, 'LBL_Gift_Orders', 1, 'Gift Orders'),
(1464, 'LBL_Signing_up_for_{user-type}', 1, 'Signing Up For {user-type}'),
(1465, 'MSG_ERROR_INVALID_ACCESS', 1, 'Error Invalid Access'),
(1466, 'LBL_Both-Debit/Credit', 1, 'Both-debit/credit'),
(1467, 'LBL_Clear', 1, 'Clear'),
(1468, 'LBL_Enter_amount_to_be_Added_[$]', 1, 'Enter Amount To Be Added [$]'),
(1469, 'LBL_Add_Money_to_account', 1, 'Add Money To Account'),
(1470, 'LBL_Enter_Gift_Card_Code', 1, 'Enter Gift Card Code'),
(1471, 'LBL_Redeem', 1, 'Redeem'),
(1472, 'LBL_From_Date', 1, 'From Date'),
(1473, 'LBL_To_Date', 1, 'To Date'),
(1474, 'LBL_Current_Balance', 1, 'Current Balance'),
(1475, 'LBL_Enter_Giftcard_Code', 1, 'Enter Giftcard Code'),
(1476, 'LBL_Txn_ID', 1, 'Txn Id'),
(1477, 'LBL_User_Withdrwal_Requests', 1, 'User Withdrwal Requests'),
(1478, 'LBL_Bible_Title', 1, 'Bible Title'),
(1479, 'LBL_Bible', 2, 'Bible'),
(1480, 'LBL_Redeem_Giftcard', 1, 'Redeem Giftcard'),
(1481, 'LBL_Amount_to_be_Withdrawn', 1, 'Amount To Be Withdrawn'),
(1482, 'LBL_Current_Wallet_Balance', 1, 'Current Wallet Balance'),
(1483, 'LBL_Bank_Name', 1, 'Bank Name'),
(1484, 'LBL_Account_Holder_Name', 1, 'Account Holder Name'),
(1485, 'LBL_Account_Number', 1, 'Account Number'),
(1486, 'LBL_IFSC_Swift_Code', 1, 'Ifsc Swift Code'),
(1487, 'LBL_Bank_Address', 1, 'Bank Address'),
(1488, 'LBL_Other_Info_Instructions', 1, 'Other Info Instructions'),
(1489, 'LBL_Send_Request', 1, 'Send Request'),
(1490, 'LBL_Request_Withdrawal', 1, 'Request Withdrawal'),
(1491, 'LBL_Send', 1, 'Send'),
(1492, 'LBL_Submit', 1, 'Submit'),
(1493, 'MSG_Message_Submitted_Successfully!', 1, 'Message Submitted Successfully!'),
(1494, 'LBL_Reset', 1, 'Reset'),
(1495, 'LBL_Reviews', 1, 'Reviews'),
(1496, 'LBL_Default_Review_Status', 1, 'Default Review Status'),
(1497, 'LBL_Set_the_default_review_order_status_when_a_new_review_is_placed', 1, 'Set The Default Review Order Status When A New Review Is Placed'),
(1498, 'LBL_Allow_Reviews', 1, 'Allow Reviews'),
(1499, 'LBL_New_Review_Alert_Email', 1, 'New Review Alert Email'),
(1500, 'LBL_Withdrawal_Request_Pending', 1, 'Withdrawal Request Pending'),
(1501, 'LBL_Withdrawal_Request_Completed', 1, 'Withdrawal Request Completed'),
(1502, 'LBL_Withdrawal_Request_Approved', 1, 'Withdrawal Request Approved'),
(1503, 'LBL_Withdrawal_Request_Declined', 1, 'Withdrawal Request Declined'),
(1504, 'LBL_Manage_Withdrawal_Requests', 1, 'Manage Withdrawal Requests'),
(1505, 'LBL_Withdrawal_Requests', 1, 'Withdrawal Requests'),
(1506, 'LBL_ID', 1, 'Id'),
(1507, 'LBL_User_Details', 1, 'User Details'),
(1508, 'LBL_Account_Details', 1, 'Account Details'),
(1509, 'LBL_Order_Amount', 1, 'Order Amount'),
(1510, 'LBL_Order_Status', 1, 'Order Status'),
(1511, 'LBL_Are_you_sure_to_unlock_this_price!', 1, 'Are You Sure To Unlock This Price!'),
(1512, 'LBL_Are_you_sure_to_unlock_this_price!', 2, 'Are You Sure To Unlock This Price!'),
(1513, 'MSG_Please_select_a_Profile_Pic', 1, 'Please Select A Profile Pic'),
(1514, 'LBL_Search_By_Teacher_Name', 1, 'Search By Teacher Name'),
(1515, 'LBL_Unused', 1, 'Unused'),
(1516, 'LBL_Used', 1, 'Used'),
(1517, 'LBL_Giftcards_Purchased', 1, 'Giftcards Purchased'),
(1518, 'LBL_Gift_card_Recipient_Detail', 1, 'Gift Card Recipient Detail'),
(1519, 'LBL_Gift_Card_Code', 1, 'Gift Card Code'),
(1520, 'LBL_Recepient_Details', 1, 'Recepient Details'),
(1522, 'LBL_Preferences_Subjects__Listing', 1, 'Preferences Subjects  Listing'),
(1524, 'LBL_Cms', 2, 'CMS'),
(1527, 'LBL_My_Students', 2, 'My Students'),
(1528, 'LBL_Buy_Giftcard', 2, 'Buy Giftcard'),
(1529, 'LBL_Messages', 2, 'Messages'),
(1530, 'LBL_Teacher', 2, 'Teacher'),
(1531, 'MSG_Setup_successful', 2, 'Setup Successful'),
(1532, 'LBL_Teacher_Reviews', 1, 'Teacher Reviews'),
(1533, 'LBL_Manage_Teacher_Reviews', 1, 'Manage Teacher Reviews'),
(1534, 'LBL_Favourites', 1, 'Favourites'),
(1535, 'MSG_INVALID_FILE_MIME_TYPE', 1, 'Invalid File Mime Type'),
(1536, 'LBL_Set_Up_Flashcard', 1, 'Set Up Flashcard'),
(1537, 'LBL_Apply_Coupon', 1, 'Apply Coupon'),
(1538, 'LBL_Availiblity', 1, 'Availiblity'),
(1539, 'LBL_Sunday', 1, 'Sunday'),
(1540, 'LBL_Monday', 1, 'Monday'),
(1541, 'LBL_Tuesday', 1, 'Tuesday'),
(1542, 'LBL_Wednesday', 1, 'Wednesday'),
(1543, 'LBL_Thursday', 1, 'Thursday'),
(1544, 'LBL_Friday', 1, 'Friday'),
(1545, 'LBL_Saturday', 1, 'Saturday'),
(1546, 'LBL_Teacher_has_been_marked_as_favourite_successfully', 1, 'Teacher Has Been Marked As Favourite Successfully'),
(1547, 'MSG_YOUR_CURRENT_PASSWORD_MIS_MATCHED', 1, 'Your Current Password Mis Matched'),
(1548, 'LBL_Teacher_has_been_removed_from_favourite_list', 1, 'Teacher Has Been Removed From Favourite List'),
(1549, 'LBL_Enter_Amount_To_Be_Added_[{site-currency-symbol}]', 1, 'Enter Amount To Be Added [{site-currency-symbol}]'),
(1550, 'LBL_My_Wallet', 1, 'My Wallet'),
(1551, 'Redeem_Gift_Card', 1, 'Gift Card'),
(1552, 'LBL_Your_Wallet_Balance', 1, 'Your Wallet Balance'),
(1553, 'LBL_Make_Sure_To_Review_Your_Order_Details_Now.', 1, 'Make Sure To Review Your Order Details Now.'),
(1554, 'LBL_Once_you_press_\'Add_Money\'_you\'ll_be_directed_to_payment_page_to_enter_your_payment_information_and_process_the_order', 1, 'Once You Press \'add Money\' You\'ll Be Directed To Payment Page To Enter Your Payment Information And Process The Order'),
(1555, 'LBL_Search_Transactions', 1, 'Search Transactions'),
(1556, 'LBL_These_Prices_are_locked', 1, 'These Prices Are Locked'),
(1557, 'LBL_Unfavorite', 1, 'Unfavorite'),
(1558, 'LBL_View_Order_Detail', 1, 'View Order Detail'),
(1559, 'LBL_Order_Detail', 1, 'Order Detail'),
(1560, 'LBL_Back_To_Giftcards', 1, 'Back To Giftcards'),
(1561, 'LBL_Customer_Order_Detail', 1, 'Customer Order Detail'),
(1562, 'LBL_Order/Invoice_ID', 1, 'Order/invoice Id'),
(1563, 'LBL_Order_Amount_Paid', 1, 'Order Amount Paid'),
(1564, 'LBL_Order_Amount_Pending', 1, 'Order Amount Pending'),
(1565, 'LBL_Giftcard_Details', 1, 'Giftcard Details'),
(1566, 'LBL_Giftcard_Invoice_ID', 1, 'Giftcard Invoice Id'),
(1567, 'LBL_Giftcard_Code', 1, 'Giftcard Code'),
(1568, 'LBL_Buyer_Name', 1, 'Buyer Name'),
(1569, 'LBL_Giftcard_Recipient_Name', 1, 'Giftcard Recipient Name'),
(1570, 'LBL_Giftcard_Exipre_Date', 1, 'Giftcard Exipre Date'),
(1571, 'LBL_Giftcard_Status', 1, 'Giftcard Status'),
(1572, 'LBL_GiftCard_Used_Date', 1, 'Giftcard Used Date'),
(1573, 'LBL_GIFTCARD_UNUSED', 1, 'Giftcard Unused'),
(1574, 'LBL_Order_Total', 1, 'Order Total'),
(1575, 'LBL_Customer_Details', 1, 'Customer Details'),
(1576, 'LBL_Order_Payments', 1, 'Order Payments'),
(1577, 'MSG_Order_Data_Not_Found', 1, 'Order Data Not Found'),
(1578, 'MSG_Redirecting', 1, 'Redirecting'),
(1579, 'LBL_Loaded_Money_to_Wallet', 1, 'Loaded Money To Wallet'),
(1580, 'LBL_FlashCard_Reviewed_Successfully!', 1, 'Flashcard Reviewed Successfully!'),
(1581, 'LBL_Both-Debit/Credit', 2, 'Both-debit/credit'),
(1582, 'LBL_Credit', 2, 'Credit'),
(1583, 'LBL_Debit', 2, 'Debit'),
(1584, 'LBL_Reset', 2, 'Reset'),
(1585, 'LBL_Enter_Amount_To_Be_Added_[{site-currency-symbol}]', 2, 'Enter Amount To Be Added [{site-currency-symbol}]'),
(1586, 'LBL_Add_Money_to_account', 2, 'Add Money To Account'),
(1587, 'LBL_Favourites', 2, 'Favourites'),
(1588, 'LBL_My_Wallet', 2, 'My Wallet'),
(1589, 'LBL_Request_Withdrawal', 2, 'Request Withdrawal'),
(1590, 'Redeem_Gift_Card', 2, 'Gift Card'),
(1591, 'LBL_Your_Wallet_Balance', 2, 'Your Wallet Balance'),
(1592, 'LBL_Make_Sure_To_Review_Your_Order_Details_Now.', 2, 'Make Sure To Review Your Order Details Now.'),
(1593, 'LBL_Once_you_press_\'Add_Money\'_you\'ll_be_directed_to_payment_page_to_enter_your_payment_information_and_process_the_order', 2, 'Once You Press \'add Money\' You\'ll Be Directed To Payment Page To Enter Your Payment Information And Process The Order'),
(1594, 'LBL_Keyword', 2, 'Keyword'),
(1595, 'LBL_From_Date', 2, 'From Date'),
(1596, 'LBL_To_Date', 2, 'To Date'),
(1597, 'LBL_Search_Transactions', 2, 'Search Transactions'),
(1598, 'LBL_Transaction_Pending', 2, 'Transaction Pending'),
(1599, 'LBL_Transaction_Completed', 2, 'Transaction Completed'),
(1600, 'LBL_Txn_ID', 2, 'Txn Id'),
(1601, 'LBL_Date', 2, 'Date'),
(1602, 'LBL_Balance', 2, 'Balance'),
(1603, 'LBL_Comments', 2, 'Comments'),
(1604, 'Label_More', 1, 'More'),
(1605, 'LBL_All_Tutors', 1, 'All Tutors'),
(1606, 'LBL_All_Date_&_Times_are_showing_in_{time-zone-abbr},_Current_Date_&_Time:_{current-date-time}', 1, 'All Date & Times Are Showing In {time-zone-abbr}, Current Date & Time: {current-date-time}'),
(1607, 'Label_More', 2, 'More'),
(1608, 'LBL_All_Tutors', 2, 'All Tutors'),
(1609, 'LBL_All_Date_&_Times_are_showing_in_{time-zone-abbr},_Current_Date_&_Time:_{current-date-time}', 2, 'All Date & Times Are Showing In {time-zone-abbr}, Current Date & Time: {current-date-time}'),
(1610, 'LBL_Request_Processing..', 1, 'Request Processing..'),
(1611, 'MSG_Please_Enter_8_Digit_AlphaNumeric_Password', 1, 'Please Enter 8 Digit Alphanumeric Password'),
(1612, 'LBL_Eg:_user@123', 1, 'Eg: User@123'),
(1613, 'LBL_Mar', 1, 'Mar'),
(1614, 'LBL_Feb', 1, 'Feb'),
(1615, 'LBL_Jan', 1, 'Jan'),
(1616, 'LBL_Dec', 1, 'Dec'),
(1617, 'LBL_Nov', 1, 'Nov'),
(1618, 'LBL_Oct', 1, 'Oct'),
(1619, 'LBL_Commission_Settings', 1, 'Commission Settings'),
(1620, 'LBL_Total_Revenue_from_lessons', 1, 'Total Revenue From Lessons'),
(1621, 'LBL_Total_Earnings_to_Admin', 1, 'Total Earnings To Admin'),
(1622, 'LBL_Total_Users', 1, 'Total Users'),
(1623, 'LBL_Total_lessons', 1, 'Total Lessons'),
(1624, 'LBL_Statistics', 1, 'Statistics'),
(1625, 'LBL_Total_Earning_From_Lessons', 1, 'Total Earning From Lessons'),
(1626, 'LBL_Total_Commisions_From_Lessons', 1, 'Total Commisions From Lessons'),
(1627, 'LBL_Total_Sign_ups', 1, 'Total Sign Ups'),
(1628, 'LBL_Top_Lesson_Languages', 1, 'Top Lesson Languages'),
(1629, 'LBL_Today', 1, 'Today'),
(1630, 'LBL_Weekly', 1, 'Weekly'),
(1631, 'LBL_Monthly', 1, 'Monthly'),
(1632, 'LBL_Yearly', 1, 'Yearly'),
(1633, 'LBL_Log_into_Profile', 1, 'Log Into Profile'),
(1634, 'LBL_Lesson(s)', 1, 'Lesson(s)'),
(1635, 'LBL_Schedule_a_Session', 1, 'Schedule A Session'),
(1636, 'LBL_Schedule_Session', 1, 'Schedule Session'),
(1637, 'LBL_Your_Detail', 1, 'Your Detail'),
(1638, 'LBL_Please_Complete_Profile_to_be_visible_on_teachers_listing_page', 1, 'Please Complete Profile To Be Visible On Teachers Listing Page'),
(1639, 'LBL_Last_Week', 1, 'Last Week'),
(1640, 'LBL_Last_Month', 1, 'Last Month'),
(1641, 'LBL_Last_Year', 1, 'Last Year'),
(1642, 'MSG_VERIFICATION_EMAIL_SENT', 1, 'Verification Email Sent'),
(1643, 'LABEL_TEACHER_REQUEST_APPROVED', 1, 'Teacher Request Approved'),
(1644, 'LABEL_TEACHER_REQUEST_APPROVED_DESCRIPTION', 1, 'Teacher Request Approved Description'),
(1645, 'Label_Notifications', 1, 'Notifications'),
(1646, 'LBL_Your_Application_Approved', 1, 'Your Application Approved'),
(1647, 'LBL_My_Notifications', 1, 'My Notifications'),
(1648, 'LBL_Notification_TITLE', 1, 'Notification Title'),
(1649, 'LBL_Notification_Sent_ON', 1, 'Notification Sent On'),
(1650, 'LBL_NOTE:_Allowed_Certificate_Extentions!', 1, 'Note: Allowed Extension pdf,doc,xls,txt.'),
(1651, 'LBL_My_Reports', 1, 'My Reports'),
(1652, 'LBL_Duration', 1, 'Duration'),
(1653, 'LBL_New_Users_Earnings', 1, 'New Users Earnings'),
(1654, 'LBL_Earnings', 1, 'Earnings'),
(1655, 'LBL_Last_12_Months_Sales', 1, 'Last 12 Months Sales'),
(1656, 'LBL_Month', 1, 'Month'),
(1657, 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson', 1, '{n} Minutes Of {trial-or-paid} Lesson'),
(1658, 'LBL_Trial', 1, 'Trial'),
(1659, 'LBL_Start_Conversation', 1, 'Start Conversation'),
(1660, 'LBL_Please_choose', 1, 'Please Choose'),
(1661, 'LBL_Single_Lesson_Price', 1, 'Single Lesson Price'),
(1662, 'LBL_Bulk_Lesson_Price', 1, 'Bulk Lesson Price'),
(1663, 'LBL_Flashcard', 1, 'Flashcard'),
(1664, 'LBL_Added_On', 1, 'Added On'),
(1665, 'LBL_LessonId:_%s_Payment', 1, 'Lessonid: %s Payment'),
(1666, 'LABEL_WALLET_CREDIT', 1, 'Wallet Credit'),
(1667, 'LBL_LessonId:_%s_Wallet_Credit_Notification', 1, 'Lessonid: %s Wallet Credit Notification'),
(1668, 'LBL_Lesson_Already_Ended_by_Teacher!', 1, 'Lesson Already Ended By Teacher!'),
(1669, 'MSG_Invalid_Giftcard_code', 1, 'Invalid Giftcard Code'),
(1670, 'LBL_Lesson_Ended_On', 1, 'Lesson Ended On'),
(1671, 'LBL_Expired', 1, 'Expired'),
(1672, 'LABEL_WALLET_CREDIT_DESCRIPTION', 1, 'Wallet Credit Description'),
(1673, 'LBL_Manage_Commission_Settings', 1, 'Manage Commission Settings'),
(1674, 'LBL_Commission_Settings_List', 1, 'Commission Settings List'),
(1675, 'LBL_Fees_[%]', 1, 'Fees [%]'),
(1676, 'LBL_Comment', 1, 'Comment'),
(1677, 'LBL_Add_New_Lesson_Plan', 1, 'Add New Lesson Plan'),
(1678, 'LBL_Funds_Withdrawn', 1, 'Funds Withdrawn'),
(1679, 'LBL_Request_ID', 1, 'Request Id'),
(1680, 'MSG_Withdraw_request_placed_successfully', 1, 'Withdraw Request Placed Successfully'),
(1681, 'LBL_N', 1, 'N'),
(1682, 'LBL_A/C_Name', 1, 'A/c Name'),
(1683, 'LBL_A/C_Number', 1, 'A/c Number'),
(1684, 'LBL_IFSC_Code/Swift_Code', 1, 'Ifsc Code/swift Code'),
(1685, 'LBL_Approve', 1, 'Approve'),
(1686, 'LBL_Decline', 1, 'Decline'),
(1687, 'LBL_Files', 1, 'Files'),
(1688, 'LBL_You_already_purchased_free_trial_for_this_teacher', 1, 'You Already Purchased Free Trial For This Teacher'),
(1689, 'LABEL_LESSON_SCHEDULED_BY_LEARNER', 1, 'Lesson Scheduled By Learner'),
(1690, 'LABEL_LESSON_SCHEDULED_BY_LEARNER_DESCRIPTION', 1, 'Lesson Scheduled By Learner Description'),
(1691, 'LBL_Lesson_Scheduled_Successfully', 1, 'Lesson Scheduled Successfully'),
(1692, 'LBL_Lesson_Scheduled_Successfully!', 1, 'Lesson Scheduled Successfully!'),
(1693, 'LBL_Rescheduled', 1, 'Rescheduled'),
(1694, 'LABEL_LESSON_RESCHEDULE_REQUEST_BY_LEARNER', 1, 'Lesson Reschedule Request By Learner'),
(1695, 'LABEL_LESSON_RESCHEDULE_REQUEST_LEARNER_DESCRIPTION', 1, 'Lesson Reschedule Request Learner Description'),
(1696, 'LBL_Lesson_Re-Scheduled_Successfully!', 1, 'Lesson Re-scheduled Successfully!'),
(1697, 'LBL_NOTE:_Allowed_File_types!', 1, 'Note: Allowed File Types!'),
(1698, 'LBL_Lesson_Already_Ended_by_Learner!', 1, 'Lesson Already Ended By Learner!'),
(1702, 'LBL_Previous', 1, 'Previous'),
(1703, 'LBL_Next', 1, 'Next'),
(1704, 'LBL_Access_Denied', 1, 'Access Denied'),
(1705, 'LBL_Availiblity', 2, 'Availiblity'),
(1706, 'LBL_Sunday', 2, 'Sunday'),
(1707, 'LBL_Monday', 2, 'Monday'),
(1708, 'LBL_Tuesday', 2, 'Tuesday'),
(1709, 'LBL_Wednesday', 2, 'Wednesday'),
(1710, 'LBL_Thursday', 2, 'Thursday'),
(1711, 'LBL_Friday', 2, 'Friday'),
(1712, 'LBL_Saturday', 2, 'Saturday'),
(1713, 'LBL_Request_Processing..', 2, 'Request Processing..'),
(1714, 'LBL_Please_enter_the_email_address_registered_on_your_account.', 1, 'Please Enter The Email Address Registered On Your Account.'),
(1715, 'LBL_Reset_Password?', 1, 'Reset Password?'),
(1716, 'LBL_Change_or_reset_your_password.', 1, 'Change Or Reset Your Password.'),
(1717, 'LBL_Comments/Reason', 1, 'Comments/reason'),
(1718, 'LBL_Apr', 1, 'Apr'),
(1719, 'Lbl_Flag', 1, 'Flag'),
(1720, 'LBL_Upload_Flag', 1, 'Upload Flag'),
(1721, 'LBL_Country_Flag_Setup', 1, 'Country Flag Setup'),
(1722, 'MSG_Invalid_Access', 1, 'Invalid Access'),
(1723, 'LBL_Go_to_Dashboard.', 1, 'Go To Dashboard.'),
(1724, 'LBL_Rate_it.', 1, 'Rate It.'),
(1725, 'LBL_Learner_Ending_Lesson_Warning_Message', 1, 'Learner Ending Lesson Warning Message'),
(1726, 'LBL_Accent', 1, 'Accent'),
(1727, 'LBL_Presence', 1, 'Presence'),
(1728, 'LBL_Overall', 1, 'Overall'),
(1729, 'L_Rate', 1, 'Rate'),
(1730, 'LBL_Send_Review', 1, 'Send Review'),
(1731, 'LBL_Lesson_Feedback', 1, 'Lesson Feedback'),
(1732, 'LBL_Go_to_Dashboard', 1, 'Go To Dashboard'),
(1733, 'ERR_INVALID_USERNAME', 1, 'Invalid Username'),
(1734, 'LBL_Coupon_History', 1, 'Coupon History'),
(1735, 'LBL_Customer', 1, 'Customer'),
(1736, 'LBL_Package_Selected_Successfully.', 1, 'Package Selected Successfully.'),
(1737, 'LBL_Confirm_It!', 1, 'Confirm It!'),
(1738, 'LBL_Wallet_Deduction', 1, 'Wallet Deduction'),
(1739, 'MSG_Teacher_booking_selection_is_not_yet_been_selected,_Please_try_selecting_the_appropriate_teacher_and_start_booking_lesson.', 1, 'Teacher Booking Selection Is Not Yet Been Selected, Please Try Selecting The Appropriate Teacher And Start Booking Lesson.'),
(1740, 'LBL_Record_Deleted_Successfully!', 1, 'Record Deleted Successfully!'),
(1741, 'LBL_NOTE:_Allowed_Lesson_File_types!', 1, 'Note: Allowed Lesson File Types!'),
(1742, 'LBL_Schedule_a_Session', 2, 'Schedule A Session'),
(1743, 'LBL_Schedule_Session', 2, 'Schedule Session'),
(1744, 'LBL_NOTE:_Are_you_sure!_By_Removing_this_lesson_will_also_unlink_it_from_courses_and_scheduled_lessons!', 1, 'Note: Are You Sure! By Removing This Lesson Will Also Unlink It From Courses And Scheduled Lessons!'),
(1745, 'LBL_OK', 1, 'Ok'),
(1746, 'LBL_Student_Remove_Successfully!', 1, 'Student Remove Successfully!'),
(1747, 'MSG_Feedback_Submitted_Successfully', 1, 'Feedback Submitted Successfully'),
(1748, 'MSG_Already_submitted_order_feedback', 1, 'Already Submitted Order Feedback'),
(1749, 'LBL_Issue_Reported_SetUp_Successfully!', 1, 'Issue Reported Setup Successfully!'),
(1750, 'LBL_View_Issue_Detail', 1, 'View Issue Detail'),
(1751, 'LBL_Reported_Time', 1, 'Reported Time'),
(1752, 'LBL_View_Lesson_Details', 1, 'View Lesson Details'),
(1753, 'LBL_This_Lesson_has_been_cancelled._Schedule_more_lessons.', 1, 'This Lesson Has Been Cancelled. Schedule More Lessons.'),
(1754, 'LBL_Book_a_Session', 1, 'Book A Session'),
(1755, 'LBL_Book_Session', 1, 'Book Session'),
(1758, 'LABEL_LESSON_RESCHEDULE_REQUEST_BY_TEACHER', 1, 'Lesson Reschedule Request By Teacher'),
(1759, 'LABEL_LESSON_RESCHEDULE_REQUEST_BY_TEACHER_DESCRIPTION', 1, 'Lesson Reschedule Request By Teacher Description'),
(1760, 'LBL_Lesson_Re-schedule_request_sent_successfully!', 1, 'Lesson Re-schedule Request Sent Successfully!'),
(1765, 'LBL_Contact_Us', 1, 'Contact Us'),
(1766, 'LBL_Payment_Failed', 1, 'Payment Failed'),
(1767, 'LBL_Admin_logo_size', 1, 'Admin Logo Size'),
(1768, 'LBL_Desktop_logo_size', 1, 'Desktop Logo Size'),
(1769, 'LBL_Email_Template_logo_size', 1, 'Email Template Logo Size'),
(1770, 'LBL_Social_Media_logo_size', 1, 'Social Media Logo Size'),
(1771, 'LBL_Payment_Page_logo_size', 1, 'Payment Page Logo Size'),
(1772, 'LBL_Watermark_Image_logo_size', 1, 'Watermark Image Logo Size'),
(1773, 'LBL_Apple_Touch_logo_size', 1, 'Apple Touch Logo Size'),
(1774, 'LBL_Mobile_logo_size', 1, 'Mobile Logo Size'),
(1775, 'LBL_Price_Locked_Successfully!', 1, 'Price Locked Successfully!'),
(1776, 'Lbl_On_Date', 1, 'On Date'),
(1777, 'LBL_Lesson_Updated_Successfully!', 1, 'Lesson Updated Successfully!'),
(1778, 'LBL_Lesson_Call_History', 1, 'Lesson Call History'),
(1779, 'LBL_This_lesson_is_completed.', 1, 'This Lesson Is Completed.'),
(1780, 'LBL_Add_Transactions', 1, 'Add Transactions'),
(1781, 'LBL_Giftcard_Redeem_To_Wallet', 1, 'Giftcard Redeem To Wallet');
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(1782, 'MSG_Giftcard_Redeem_successfully', 1, 'Giftcard Redeem Successfully'),
(1783, 'LBL_GIFTCARD_USED', 1, 'Giftcard Used'),
(1785, 'LBL_NOTE:_Allowed_Certificate_Extentions!', 2, 'Note: Allowed Extension pdf,doc,xls,txt.'),
(1788, 'LBL_User_Profile_Image_Dimension', 1, 'User Profile Image Dimension'),
(1789, 'ERR_LINK_IS_INVALID_OR_EXPIRED', 1, 'Link Is Invalid Or Expired'),
(1790, 'MSG_UNRECOGNISED_IMAGE_FILE', 1, 'Unrecognised Image File'),
(1791, 'MSG_INVALID_FILE_EXTENSION', 1, 'Invalid File Extension'),
(1792, 'LBL_Price_Unlocked_Successfully!', 1, 'Price Unlocked Successfully!'),
(1793, 'Product_Detail_Page_Banner', 1, 'Detail Page Banner'),
(1794, 'LBL_Load_Previous', 1, 'Load Previous'),
(1796, 'LBL_GIFTCARD_USED', 2, '<script>alert(\'test1\');</script>'),
(1800, 'LBL_Gift_card', 2, '<script>alert(\'test\');</script>'),
(1801, 'LBL_Seller', 1, 'Teacher'),
(1802, 'LBL_Commission_fees', 1, 'Commission Fees'),
(1803, 'LBL_Commission_Setup', 1, 'Commission Setup'),
(1804, 'LBL_Commission_History', 1, 'Commission History'),
(1805, 'MSG_You_are_already_logged_in', 1, 'You Are Already Logged In'),
(1806, 'LBL_Start_Year', 1, 'Start Year'),
(1807, 'LBL_End_Year', 1, 'End Year'),
(1808, 'LBL_Price::', 1, 'Price::'),
(1809, 'LBL_View_Availability', 1, 'View Availability'),
(1810, 'LBL_Photo_Id_Type', 1, 'Allowed Extension pdf,doc,xls,txt.'),
(1811, 'LBL_Manage_Spoken_Language', 1, 'Manage Spoken Language'),
(1812, 'LBL_Spoken_Language_Listing', 1, 'Spoken Language Listing'),
(1813, 'LBL_Add_Spoken_Language', 1, 'Add Spoken Language'),
(1814, 'LBL_Language_Identifier', 1, 'Language Identifier'),
(1815, 'LBL_Language_Name', 1, 'Language Name'),
(1818, 'LBL_Says:', 1, 'Says:'),
(1819, 'LBL_Title_Language', 1, 'Title Language'),
(1820, 'LBL_Defination_Language', 1, 'Defination Language'),
(1821, 'LBL_Status_Updated_Successfully!', 1, 'Status Updated Successfully!'),
(1822, 'LBL_Category_Identifier', 1, 'Category Identifier'),
(1823, 'LBL_Category_Parent', 1, 'Category Parent'),
(1824, 'LBL_Category_Status', 1, 'Category Status'),
(1825, 'LBL_Featured', 1, 'Featured'),
(1826, 'LBL_Blog_Post_Category_Setup', 1, 'Blog Post Category Setup'),
(1827, 'MSG_Category_Setup_Successful', 1, 'Category Setup Successful'),
(1828, 'LBL_Post_Identifier', 1, 'Post Identifier'),
(1829, 'LBL_SEO_Friendly_URL', 1, 'Seo Friendly Url'),
(1830, 'LBL_Comment_Open', 1, 'Comment Open'),
(1831, 'LBL_Blog_Post_Setup', 1, 'Blog Post Setup'),
(1832, 'LBL_Link_Category', 1, 'Link Category'),
(1833, 'LBL_Post_Images', 1, 'Post Images'),
(1834, 'MSG_Blog_Post_Setup_Successful', 1, 'Blog Post Setup Successful'),
(1835, 'LBL_Link_Blog_Post_To_Categories', 1, 'Link Blog Post To Categories'),
(1836, 'LBL_Post_Author_Name', 1, 'Post Author Name'),
(1837, 'LBL_Short_Description', 1, 'Short Description'),
(1838, 'LBL_Photo(s)', 1, 'Photo(s)'),
(1839, 'LBL_Contribution_Detail', 1, 'Contribution Detail'),
(1840, 'MSG_Image_Uploaded_Successfully', 1, 'Image Uploaded Successfully'),
(1841, 'Lbl_Login_required_to_post_comment', 1, 'Login Required To Post Comment'),
(1842, 'Msg_No_Comments_on_this_blog_post', 1, 'No Comments On This Blog Post'),
(1843, 'Msg_Blog_Comment_Saved_and_awaiting_admin_approval.', 1, 'Blog Comment Saved And Awaiting Admin Approval.'),
(1844, 'LBL_Comment_Details', 1, 'Comment Details'),
(1845, 'LBL_Blog_Post_Title', 1, 'Blog Post Title'),
(1846, 'LBL_User_IP', 1, 'User Ip'),
(1847, 'LBL_User_Agent', 1, 'User Agent'),
(1848, 'Lbl_Contributed_Successfully', 1, 'Contributed Successfully'),
(1849, 'LBL_Attached_File', 1, 'Attached File'),
(1850, 'LBL_Buy_Now', 1, 'Buy Now'),
(1851, 'LBL_Language', 2, 'Language'),
(1852, 'LBL_All', 2, 'All'),
(1853, 'Label_Notifications', 2, 'Notifications'),
(1854, 'LBL_Action', 2, 'Action'),
(1855, 'LBL_Determines_how_many_items_are_shown_per_page_(lesson_listing,_flashcards,_etc)', 1, 'Determines How Many Items Are Shown Per Page (lesson Listing, Flashcards, Etc)'),
(1856, 'LBL_See_all_Students', 1, 'See All Students'),
(1857, 'LBL_LessonId:_%s_Refund_Payment', 1, 'Lessonid: %s Refund Payment'),
(1858, 'LBL_Average_Rating', 1, 'Average Rating'),
(1859, 'LBL_Previous', 2, 'Previous'),
(1860, 'LBL_Next', 2, 'Next'),
(1861, 'LBL_FAQ_Category', 1, 'Faq Category'),
(1862, 'LBL_Deleted_Successfully', 1, 'Deleted Successfully'),
(1863, 'LBL_Teacher_Name', 1, 'Teacher Name'),
(1864, 'LBL_Learner_Name', 1, 'Learner Name'),
(1865, 'LBL_Teacher_Join_Time', 1, 'Teacher Join Time'),
(1866, 'LBL_Teacher_End_Time', 1, 'Teacher End Time'),
(1867, 'LBL_Learner_Join_Time', 1, 'Learner Join Time'),
(1868, 'LBL_Learner_end_Time', 1, 'Learner End Time'),
(1869, 'LBL_Completed_On', 1, 'Completed On'),
(1870, 'MSG_your_message_sent_successfully', 1, 'Your Message Sent Successfully'),
(1871, 'LBL_View_Availability', 2, 'View Availability'),
(1872, 'LBL_Weekly_Schedule', 2, 'Weekly Schedule'),
(1873, 'LBL_Experience', 2, 'Experience'),
(1874, 'LBL_Skills', 2, 'Skills'),
(1875, 'LBL_Languages', 2, 'Languages'),
(1876, 'M_Introduction_Video_Link', 2, 'Introduction Video Link'),
(1877, 'LBL_The_place_where_we_write_some_words', 2, 'The Place Where We Write Some Words'),
(1878, 'LBL_Blog_Search', 2, 'Blog Search'),
(1879, 'Lbl_on', 2, 'On'),
(1880, 'Lbl_View_Full_Post', 2, 'View Full Post'),
(1881, 'LBL_See_more_at', 2, 'See More At'),
(1882, 'LBL_Blog_Searchs', 2, 'Blog Searchs'),
(1883, 'LBL_Go_Back', 2, 'Go Back'),
(1884, 'Lbl_Write_For_Us', 2, 'Write For Us'),
(1885, 'Lbl_We_are_constantly_looking_for_writers_and_contributors_to_help_us_create_great_content_for_our_blog_visitors.', 2, 'We Are Constantly Looking For Writers And Contributors To Help Us Create Great Content For Our Blog Visitors.'),
(1886, 'Lbl_Contribute', 2, 'Contribute'),
(1887, 'Lbl_Categories', 2, 'Categories'),
(1888, 'Lbl_By', 2, 'By'),
(1889, 'Lbl_Comments(%s)', 2, 'Comments(%s)'),
(1890, 'LBL_Says:', 2, 'Says:'),
(1891, 'Lbl_Invalid_Request', 2, 'Invalid Request'),
(1893, 'LBL_Reviews', 2, 'Reviews'),
(1894, 'LBL_Teacher_Join_Time_Marked!', 1, 'Teacher Join Time Marked!'),
(1895, 'LBL_END_LESSON_DURATION', 1, 'End Lesson Duration'),
(1896, 'LBL_Duration_After_Teacher_Can_End_Lesson_(In_Minutes)', 1, 'Duration After Teacher Can End Lesson (in Minutes)'),
(1897, 'LBL_Cannot_End_Lesson_So_Early!', 1, 'Cannot End Lesson So Early!'),
(1898, 'LBL_LEARNER_REFUND_PERCENTAGE', 1, 'Learner Refund Percentage'),
(1899, 'LBL_Refund_to_learner_In_Less_than_24_Hours_(In_Percentage)', 1, 'Refund To Learner In Less Than 24 Hours (in Percentage)'),
(1900, 'LBL_Correct', 2, 'Correct'),
(1901, 'LBL_Upper_Almost', 2, 'Upper Almost'),
(1902, 'LBL_Wrong', 2, 'Wrong'),
(1903, 'LBL_Defination', 2, 'Defination'),
(1904, 'LBL_Click_On_Words_To_Flip_It', 2, 'Click On Words To Flip It'),
(1905, 'LBL_FlashCard_Reviewed_Successfully!', 2, 'Flashcard Reviewed Successfully!'),
(1906, 'LBL_Incorrect_Answers', 2, 'Incorrect Answers'),
(1907, 'LBL_Almost_Correct_Answers', 2, 'Almost Correct Answers'),
(1908, 'LBL_Correct_Answers', 2, 'Correct Answers'),
(1909, 'LBL_Close', 2, 'Close'),
(1910, 'LBL_Restart', 2, 'Restart'),
(1911, 'LBL_Title', 2, 'Title'),
(1912, 'LBL_Title_Language', 2, 'Title Language'),
(1913, 'LBL_Defination_Language', 2, 'Defination Language'),
(1914, 'LBL_Pronunciation', 2, 'Pronunciation'),
(1915, 'LBL_Notes', 2, 'Notes'),
(1916, 'LBL_Save', 2, 'Save'),
(1917, 'LBL_Set_Up_Flashcard', 2, 'Set Up Flashcard'),
(1918, 'LBL_Does_Not_Matter', 2, 'Does Not Matter'),
(1919, 'LBL_Unused', 2, 'Unused'),
(1920, 'LBL_Used', 2, 'Used'),
(1921, 'LBL_Clear', 2, 'Clear'),
(1922, 'LBL_Giftcards_Purchased', 2, 'Giftcards Purchased'),
(1923, 'LBL_Your_Detail', 2, 'Your Detail'),
(1924, 'LBL_Giftcard_Amount', 2, 'Giftcard Amount'),
(1925, 'LBL_Gift_card_Recipient_Detail', 2, 'Gift Card Recipient Detail'),
(1926, 'LBL_Order_id', 2, 'Order Id'),
(1927, 'LBL_Gift_Card_Code', 2, 'Gift Card Code'),
(1928, 'LBL_Amount', 2, 'Amount'),
(1929, 'LBL_Recepient_Details', 2, 'Recepient Details'),
(1933, 'LBL_Refund_to_learner_In_Less_than_24_Hours_(In_Percentage)', 2, 'Refund To Learner In Less Than 24 Hours (in Percentage)'),
(1935, 'LBL_Manage_Labels', 2, 'Manage Labels'),
(1946, 'LBL_Location', 2, 'Location'),
(1947, 'LBL_Unfavorite', 2, 'Unfavorite'),
(1948, 'LBL_Teacher_has_been_removed_from_favourite_list', 2, 'Teacher Has Been Removed From Favourite List'),
(1949, 'MSG_Session_seems_to_be_expired', 2, 'Session Seems To Be Expired'),
(1950, 'LBL_Lesson(s)', 2, 'Lesson(s)'),
(1951, 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson', 2, '{n} Minutes Of {trial-or-paid} Lesson'),
(1952, 'LBL_Rate_Lesson', 2, 'Rate Lesson'),
(1953, 'LBL_Trial', 2, 'Trial'),
(1954, 'LBL_Search_Flash_Cards...', 2, 'Search Flash Cards...'),
(1955, 'LBL_Teacher_Has_Joined_Now_you_can_also_Join_The_Lesson!', 2, 'Teacher Has Joined Now You Can Also Join The Lesson!'),
(1956, 'LBL_Teacher_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also', 2, 'Teacher Ends The Lesson Do Yoy Want To End It From Your End Also'),
(1957, 'LBL_Note', 2, 'Note'),
(1958, 'LBL_This_Lesson_has_been_cancelled._Schedule_more_lessons.', 2, 'This Lesson Has Been Cancelled. Schedule More Lessons.'),
(1959, 'LBL_Join_Lesson', 2, 'Join Lesson'),
(1960, 'LBL_Go_to_Dashboard.', 2, 'Go To Dashboard.'),
(1961, 'LBL_Info', 2, 'Info'),
(1962, 'LBL_Learner_Details', 2, 'Learner Details'),
(1963, 'LBL_Teacher_Details', 2, 'Teacher Details'),
(1964, 'LBL_Lesson_Details', 2, 'Lesson Details'),
(1965, 'LBL_View_Lesson_Plan', 2, 'View Lesson Plan'),
(1966, 'LBL_End_Lesson', 2, 'End Lesson'),
(1967, 'LBL_Add_New', 2, 'Add New'),
(1968, 'LBL_Resume_Information', 2, 'Resume Information'),
(1969, 'LBL_Start/End', 2, 'Start/end'),
(1970, 'LBL_Uploaded_Certificate', 2, 'Uploaded Certificate'),
(1971, 'LBL_Institution', 2, 'Institution'),
(1972, 'LBL_Delete', 2, 'Delete'),
(1973, 'LBL_Today', 2, 'Today'),
(1974, 'LBL_Last_Week', 2, 'Last Week'),
(1975, 'LBL_This_Month', 2, 'This Month'),
(1976, 'LBL_Last_Month', 2, 'Last Month'),
(1977, 'LBL_Last_Year', 2, 'Last Year'),
(1978, 'LBL_View_Profile', 2, 'View Profile'),
(1979, 'LBL_Earnings', 2, 'Earnings'),
(1980, 'LBL_See_all_Students', 2, 'See All Students'),
(1981, 'LBL_Attach_Lesson_Plan', 2, 'Attach Lesson Plan'),
(1982, 'LBL_Submit', 2, 'Submit'),
(1983, 'MSG_Blog_Categories', 1, 'Blog Categories'),
(1984, 'MSG_Blog_Posts', 1, 'Blog Posts'),
(1985, 'MSG_Blog_Contributions', 1, 'Blog Contributions'),
(1986, 'MSG_Blog_Comments', 1, 'Blog Comments'),
(1987, 'MSG_Bible_Content', 1, 'Bible Content'),
(1988, 'MSG_Manage_Purchased_lessons', 1, 'Manage Purchased Lessons'),
(1989, 'MSG_Manage_Issues_Reported', 1, 'Manage Issues Reported'),
(1990, 'MSG_GIFTCARDS', 1, 'Giftcards'),
(1991, 'MSG_Withdraw_Requests', 1, 'Withdraw Requests'),
(1992, 'MSG_Teacher_Reviews', 1, 'Teacher Reviews'),
(1993, 'MSG_Commission', 1, 'Commission'),
(1994, 'MSG_Sales_Report', 1, 'Sales Report'),
(1995, 'LBL_This_lesson_is_completed._rate_it.', 2, 'This Lesson Is Completed. Rate It.'),
(1996, 'LBL_Rate_it.', 2, 'Rate It.'),
(1997, 'MSG_Already_submitted_order_feedback', 2, 'Already Submitted Order Feedback'),
(1998, 'LBL_Lesson', 2, 'Lesson'),
(1999, 'LBL_Accent', 2, 'Accent'),
(2000, 'LBL_Presence', 2, 'Presence'),
(2001, 'LBL_Overall', 2, 'Overall'),
(2002, 'L_Rate', 2, 'Rate'),
(2003, 'LBL_Description', 2, 'Description'),
(2004, 'LBL_Send_Review', 2, 'Send Review'),
(2005, 'LBL_Lesson_Feedback', 2, 'Lesson Feedback'),
(2006, 'MSG_Feedback_Submitted_Successfully', 2, 'Feedback Submitted Successfully'),
(2007, 'LBL_Access_Denied', 2, 'Access Denied'),
(2008, 'LBL_This_lesson_is_completed.', 2, 'This Lesson Is Completed.'),
(2009, 'LBL_My_Notifications', 2, 'My Notifications'),
(2010, 'LBL_Notification_TITLE', 2, 'Notification Title'),
(2011, 'LBL_Notification_Sent_ON', 2, 'Notification Sent On'),
(2012, 'LBL_Notification_Deleted_Successfully!', 1, 'Notification Deleted Successfully!'),
(2013, 'LBL_May', 1, 'May'),
(2014, 'LBL_Sales_Report', 1, 'Sales Report'),
(2015, 'LBL_No._of_Orders', 1, 'No. Of Orders'),
(2016, 'LBL_Order_Net_Amount', 1, 'Order Net Amount'),
(2017, 'LBL_Sales_Earnings', 1, 'Sales Earnings'),
(2018, 'LBL_Language_Code_Identifier', 1, 'Language Code Identifier'),
(2019, 'LBL_Language_Flag', 1, 'Language Flag'),
(2020, 'LBL_Spoken_language_Setup', 1, 'Spoken Language Setup'),
(2021, 'LBL_Commission_fees_[%]', 1, 'Commission Fees [%]'),
(2023, 'LBL_Seller', 2, 'Teacher'),
(2024, 'LBL_Type', 2, 'Type'),
(2025, 'LBL_To', 2, 'To'),
(2026, 'LBL_View_Flashcards', 1, 'View Flashcards'),
(2027, 'MSG_Go_to_my_Lessons', 1, 'Go To My Lessons'),
(2028, 'Lbl_On_Date', 2, 'On Date'),
(2029, 'LBL_Learner_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also!', 2, 'Learner Ends The Lesson Do Yoy Want To End It From Your End Also!'),
(2030, 'LBL_Duration_assigned_to_this_lesson_is_completed_now_do_you_want_to_continue?', 2, 'Duration Assigned To This Lesson Is Completed Now Do You Want To Continue?'),
(2031, 'LBL_Continue', 2, 'Continue'),
(2032, 'LBL_Teacher_Join_Time_Marked!', 2, 'Teacher Join Time Marked!'),
(2033, 'LBL_See_All_Teachers', 1, 'See All Teachers'),
(2034, 'LBL_Lessons_Sold', 1, 'Lessons Sold'),
(2035, 'LBL_3_Articles', 1, '3 Articles'),
(2036, 'LBL_1_Articles', 1, '1 Articles'),
(2038, 'LBL_Note:_Enter_Number_of_lessons_in_a_package', 2, 'Note: Enter Number Of Lessons In A Package (In Hrs)'),
(2039, 'MSG_Unauthorized_Access!', 1, 'Unauthorized Access!'),
(2040, 'MSG_Withdrawal_Request_Minimum_Balance_Less', 1, 'Withdrawal Request Minimum Balance Less'),
(2041, 'LBL_ENTER_CREDIT_CARD_NUMBER', 1, 'Enter Credit Card Number'),
(2042, 'LBL_CARD_HOLDER_NAME', 1, 'Card Holder Name'),
(2043, 'LBL_January', 1, 'January'),
(2044, 'LBL_Februry', 1, 'Februry'),
(2045, 'LBL_March', 1, 'March'),
(2046, 'LBL_April', 1, 'April'),
(2047, 'LBL_June', 1, 'June'),
(2048, 'LBL_July', 1, 'July'),
(2049, 'LBL_August', 1, 'August'),
(2050, 'LBL_September', 1, 'September'),
(2051, 'LBL_October', 1, 'October'),
(2052, 'LBL_November', 1, 'November'),
(2053, 'LBL_December', 1, 'December'),
(2054, 'LBL_EXPIRY_MONTH', 1, 'Expiry Month'),
(2055, 'LBL_EXPIRY_YEAR', 1, 'Expiry Year'),
(2056, 'LBL_CVV_SECURITY_CODE', 1, 'Cvv Security Code'),
(2057, 'LBL_CREDIT_CARD_EXPIRY', 1, 'Credit Card Expiry'),
(2058, 'LBL_Total_Payable', 1, 'Total Payable'),
(2059, 'MSG_Order_Payment_Gateway_Description', 1, 'Order Payment Gateway Description'),
(2060, 'LBL_Login_ID', 1, 'Login Id'),
(2061, 'LBL_Transaction_Key', 1, 'Transaction Key'),
(2062, 'LBL_MD5_Hash', 1, 'Md5 Hash'),
(2063, 'BLOCK_FIRST_AFTER_HOMESLIDER', 1, 'First After Homeslider'),
(2064, 'MSG_Record_Updated_Successfully', 1, 'Record Updated Successfully'),
(2065, 'LBL_Start_Teaching', 1, 'Start Teaching'),
(2066, 'LBL_Manage_Faq', 1, 'Manage Faq'),
(2067, 'LBL_Faq_Listing', 1, 'Faq Listing'),
(2068, 'LBL_Add_Faq', 1, 'Add Faq'),
(2069, 'LBL_Faq_Identifier', 1, 'Faq Identifier'),
(2070, 'LBL_Faq_Title', 1, 'Faq Title'),
(2071, 'LBL_Faq_Setup', 1, 'Faq Setup'),
(2072, 'LBL_Faq_Text', 1, 'Faq Text'),
(2073, 'LBL_Note:_Refund_Would_Be_%s_Percent.', 1, 'Note: Refund Would Be %s Percent.'),
(2075, 'LBL_Photo_Id_Type', 2, 'Allowed Extension pdf,doc,xls,txt.'),
(2076, 'LBL_No_Other_Plan_Avaiable!!', 1, 'No Other Plan Avaiable!!'),
(2079, 'LBL_Paid', 1, 'Paid'),
(2080, 'MSG_Language_Removed_Successfuly!', 1, 'Language Removed Successfuly!'),
(2081, 'MSG_Teacher_Preferences', 1, 'Teacher Preferences'),
(2082, 'MSG_Go_to_Wallet', 1, 'Go To Wallet'),
(2083, 'VLBL_startWithLetterOnlyAlphanumeric', 1, 'Startwithletteronlyalphanumeric'),
(2084, 'LBL_You_have_already_Bought_this_plan', 1, 'You Have Already Bought This Plan'),
(2085, 'LBL_Manage_FAQs', 1, 'Manage Faqs'),
(2086, 'LBL_Google', 1, 'Google'),
(2087, 'LBL_Jun', 1, 'Jun'),
(2088, 'LBL_slider_title', 1, 'Speak like a Local.'),
(2089, 'LBL_slider_description', 1, 'Conquer a language with native teachers you pick to guide you on your language journey.'),
(2091, 'LBL_slider_title', 2, 'Speak like a Local.'),
(2093, 'LBL_slider_description', 2, 'Conquer a language with native teachers you pick to guide you on your language journey.'),
(2094, 'VLBL_startWithLetterOnlyAlphanumeric', 2, 'Startwithletteronlyalphanumeric'),
(2095, 'ERR_ERROR_IN_SENDING_NOTIFICATION_EMAIL_TO_ADMIN', 1, 'Error In Sending Notification Email To Admin'),
(2096, 'MSG_NOTIFICATION_EMAIL_COULD_NOT_BE_SENT', 1, 'Notification Email Could Not Be Sent'),
(2097, 'LBL_Email_Could_Not_Be_Sent', 1, 'Email Could Not Be Sent');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lesson_packages`
--

DROP TABLE IF EXISTS `tbl_lesson_packages`;
CREATE TABLE `tbl_lesson_packages` (
  `lpackage_id` int(11) NOT NULL,
  `lpackage_identifier` varchar(255) NOT NULL,
  `lpackage_lessons` decimal(10,2) NOT NULL,
  `lpackage_added_on` datetime NOT NULL,
  `lpackage_is_free_trial` tinyint(1) NOT NULL,
  `lpackage_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_lesson_packages`
--

INSERT INTO `tbl_lesson_packages` (`lpackage_id`, `lpackage_identifier`, `lpackage_lessons`, `lpackage_added_on`, `lpackage_is_free_trial`, `lpackage_active`) VALUES
(1, '30 Minute Free Trial', '0.50', '2019-01-03 17:50:33', 1, 1),
(2, '1 Lesson', '1.00', '2019-01-03 19:26:35', 0, 1),
(3, '2 Lessons', '2.00', '2019-01-04 11:00:32', 0, 1),
(4, '3 Lessons', '3.00', '2019-01-04 11:00:48', 0, 1),
(5, '5 Lesson Package', '5.00', '2019-05-15 21:35:51', 0, 1),
(6, '10 Lesson Package', '10.00', '2019-05-16 15:03:38', 0, 1),
(7, '20 Lesson Package', '10.00', '2019-05-16 15:05:52', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lesson_packages_lang`
--

DROP TABLE IF EXISTS `tbl_lesson_packages_lang`;
CREATE TABLE `tbl_lesson_packages_lang` (
  `lpackagelang_lpackage_id` int(11) NOT NULL,
  `lpackagelang_lang_id` int(11) NOT NULL,
  `lpackage_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_lesson_packages_lang`
--

INSERT INTO `tbl_lesson_packages_lang` (`lpackagelang_lpackage_id`, `lpackagelang_lang_id`, `lpackage_title`) VALUES
(1, 1, '30 Minute Free Trial'),
(1, 2, '30 Minute Free Trial'),
(2, 1, '1 Lesson'),
(2, 2, '1 Lesson'),
(3, 1, '2 Lessons'),
(3, 2, '2 Lessons'),
(4, 1, '3 Lessons'),
(4, 2, '3 Lessons'),
(5, 1, '5 Lesson'),
(5, 2, '5 Lesson'),
(6, 1, '10 Lessons'),
(7, 1, '10 lessons');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meta_tags`
--

DROP TABLE IF EXISTS `tbl_meta_tags`;
CREATE TABLE `tbl_meta_tags` (
  `meta_id` int(11) NOT NULL,
  `meta_controller` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `meta_action` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `meta_record_id` int(11) NOT NULL,
  `meta_subrecord_id` int(11) NOT NULL,
  `meta_identifier` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `meta_default` tinyint(1) NOT NULL,
  `meta_advanced` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_meta_tags`
--

INSERT INTO `tbl_meta_tags` (`meta_id`, `meta_controller`, `meta_action`, `meta_record_id`, `meta_subrecord_id`, `meta_identifier`, `meta_default`, `meta_advanced`) VALUES
(1, 'Cms', 'view', 1, 0, 'sds', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meta_tags_lang`
--

DROP TABLE IF EXISTS `tbl_meta_tags_lang`;
CREATE TABLE `tbl_meta_tags_lang` (
  `metalang_meta_id` int(11) NOT NULL,
  `metalang_lang_id` int(11) NOT NULL,
  `meta_title` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `meta_keywords` text CHARACTER SET utf8mb4 NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 NOT NULL,
  `meta_other_meta_tags` text CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_meta_tags_lang`
--

INSERT INTO `tbl_meta_tags_lang` (`metalang_meta_id`, `metalang_lang_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_other_meta_tags`) VALUES
(1, 1, 'sd', 'sd', 'sd', '<meta name=\"copyright\" content=\"text\">'),
(1, 2, 'fgdg', 'dfg', 'dfg', '<meta name=\"copyright\" content=\"text\">');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigations`
--

DROP TABLE IF EXISTS `tbl_navigations`;
CREATE TABLE `tbl_navigations` (
  `nav_id` int(11) NOT NULL,
  `nav_identifier` varchar(150) DEFAULT NULL,
  `nav_active` tinyint(1) DEFAULT NULL,
  `nav_is_multilevel` tinyint(1) NOT NULL,
  `nav_type` tinyint(1) NOT NULL,
  `nav_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_navigations`
--

INSERT INTO `tbl_navigations` (`nav_id`, `nav_identifier`, `nav_active`, `nav_is_multilevel`, `nav_type`, `nav_deleted`) VALUES
(1, 'Footer', 1, 0, 1, 0),
(2, 'Header', 1, 0, 2, 0),
(3, 'Header More', 1, 0, 3, 0),
(4, 'footer right', 1, 0, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigations_lang`
--

DROP TABLE IF EXISTS `tbl_navigations_lang`;
CREATE TABLE `tbl_navigations_lang` (
  `navlang_nav_id` int(11) NOT NULL,
  `navlang_lang_id` int(11) NOT NULL,
  `nav_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_navigations_lang`
--

INSERT INTO `tbl_navigations_lang` (`navlang_nav_id`, `navlang_lang_id`, `nav_name`) VALUES
(1, 1, 'Footer'),
(2, 1, 'Header');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigation_links`
--

DROP TABLE IF EXISTS `tbl_navigation_links`;
CREATE TABLE `tbl_navigation_links` (
  `nlink_id` int(11) NOT NULL,
  `nlink_nav_id` int(11) NOT NULL,
  `nlink_cpage_id` int(11) NOT NULL,
  `nlink_category_id` int(11) NOT NULL,
  `nlink_identifier` varchar(200) NOT NULL,
  `nlink_target` varchar(100) NOT NULL,
  `nlink_type` tinyint(4) NOT NULL,
  `nlink_parent_id` int(11) NOT NULL,
  `nlink_login_protected` tinyint(1) NOT NULL,
  `nlink_deleted` tinyint(1) NOT NULL,
  `nlink_url` varchar(100) NOT NULL,
  `nlink_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_navigation_links`
--

INSERT INTO `tbl_navigation_links` (`nlink_id`, `nlink_nav_id`, `nlink_cpage_id`, `nlink_category_id`, `nlink_identifier`, `nlink_target`, `nlink_type`, `nlink_parent_id`, `nlink_login_protected`, `nlink_deleted`, `nlink_url`, `nlink_display_order`) VALUES
(56, 2, 0, 0, 'Find a Teacher', '_self', 2, 0, 0, 0, '{SITEROOT}teachers', 2),
(57, 3, 6, 0, 'Help', '_self', 0, 0, 0, 0, '', 1),
(58, 3, 0, 0, 'Contact Us', '_self', 2, 0, 0, 0, '{SITEROOT}contact', 2),
(59, 3, 1, 0, 'About Us', '_self', 0, 0, 0, 0, '', 3),
(60, 3, 7, 0, 'Apply to teach', '_self', 0, 0, 0, 0, '', 4),
(61, 3, 0, 0, 'Bible', '_self', 2, 0, 0, 0, '{SITEROOT}bible', 5),
(62, 1, 0, 0, 'Blog', '_self', 2, 0, 0, 0, '{SITEROOT}blog', 2),
(63, 1, 3, 0, 'Privacy And Policy', '_self', 0, 0, 0, 0, '', 4),
(64, 4, 1, 0, 'Bew', '_self', 0, 0, 2, 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigation_links_lang`
--

DROP TABLE IF EXISTS `tbl_navigation_links_lang`;
CREATE TABLE `tbl_navigation_links_lang` (
  `nlinklang_nlink_id` int(11) NOT NULL,
  `nlinklang_lang_id` int(11) NOT NULL,
  `nlink_caption` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_navigation_links_lang`
--

INSERT INTO `tbl_navigation_links_lang` (`nlinklang_nlink_id`, `nlinklang_lang_id`, `nlink_caption`) VALUES
(56, 1, 'Find a Teacher'),
(56, 2, 'Find a Teacher'),
(57, 1, 'Help'),
(57, 2, 'Help'),
(58, 1, 'Contact Us'),
(58, 2, 'Contact Us'),
(59, 1, 'About Us'),
(59, 2, 'About Us'),
(60, 1, 'Apply to teach'),
(60, 2, 'Apply to teach'),
(61, 1, 'Bible'),
(61, 2, 'Bible'),
(62, 1, 'Blog'),
(62, 2, 'blog'),
(63, 1, 'Privacy And Policy'),
(63, 2, 'Privacy And Policy'),
(64, 1, 'Aboutt');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

DROP TABLE IF EXISTS `tbl_notifications`;
CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_user_id` int(11) NOT NULL,
  `notification_record_id` varchar(50) NOT NULL,
  `notification_sub_record_id` int(11) NOT NULL,
  `notification_record_type` tinyint(5) NOT NULL,
  `notification_title` varchar(150) NOT NULL,
  `notification_description` varchar(500) NOT NULL,
  `notification_read` tinyint(1) NOT NULL COMMENT '0=>Not Read ,1 =>Read',
  `notification_added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notification_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `notification_user_id`, `notification_record_id`, `notification_sub_record_id`, `notification_record_type`, `notification_title`, `notification_description`, `notification_read`, `notification_added_on`, `notification_deleted`) VALUES
(1, 1, '0', 0, 1, 'Teacher Request Approved', 'Teacher Request Approved Description', 0, '2019-06-01 13:46:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

DROP TABLE IF EXISTS `tbl_orders`;
CREATE TABLE `tbl_orders` (
  `order_id` varchar(15) NOT NULL,
  `order_type` tinyint(4) NOT NULL COMMENT 'lesson,giftcard,walletrecharge',
  `order_user_id` int(11) NOT NULL,
  `order_is_paid` tinyint(1) NOT NULL COMMENT 'defined in order model',
  `order_net_amount` decimal(10,2) NOT NULL,
  `order_is_wallet_selected` tinyint(4) NOT NULL,
  `order_wallet_amount_charge` decimal(10,2) NOT NULL,
  `order_discount_coupon_code` varchar(50) NOT NULL,
  `order_discount_value` decimal(10,2) NOT NULL,
  `order_discount_info` text NOT NULL,
  `order_discount_total` decimal(10,2) NOT NULL,
  `order_language_id` int(11) NOT NULL,
  `order_language_code` varchar(4) NOT NULL,
  `order_currency_id` int(11) NOT NULL,
  `order_currency_code` varchar(10) NOT NULL,
  `order_currency_value` decimal(10,8) NOT NULL,
  `order_pmethod_id` int(11) NOT NULL,
  `order_date_added` datetime NOT NULL,
  `order_date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_payments`
--

DROP TABLE IF EXISTS `tbl_order_payments`;
CREATE TABLE `tbl_order_payments` (
  `opayment_id` bigint(20) NOT NULL,
  `opayment_order_id` varchar(15) NOT NULL,
  `opayment_method` varchar(250) NOT NULL,
  `opayment_gateway_txn_id` varchar(100) NOT NULL,
  `opayment_amount` decimal(10,2) NOT NULL,
  `opayment_comments` text NOT NULL,
  `opayment_gateway_response` text NOT NULL,
  `opayment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_products`
--

DROP TABLE IF EXISTS `tbl_order_products`;
CREATE TABLE `tbl_order_products` (
  `op_id` int(11) NOT NULL,
  `op_order_id` varchar(15) NOT NULL,
  `op_invoice_number` varchar(50) NOT NULL,
  `op_teacher_id` int(11) NOT NULL COMMENT 'user_id from users table',
  `op_lpackage_id` int(11) NOT NULL,
  `op_lpackage_lessons` decimal(10,2) NOT NULL,
  `op_lpackage_is_free_trial` tinyint(1) NOT NULL,
  `op_lesson_duration` decimal(10,0) NOT NULL COMMENT 'In Minutes',
  `op_qty` int(11) NOT NULL,
  `op_unit_price` decimal(10,2) NOT NULL,
  `op_commission_charged` decimal(10,2) NOT NULL,
  `op_commission_percentage` decimal(10,2) NOT NULL,
  `op_orderstatus_id` int(11) NOT NULL COMMENT 'from table tbl_order_statuses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_products_lang`
--

DROP TABLE IF EXISTS `tbl_order_products_lang`;
CREATE TABLE `tbl_order_products_lang` (
  `oplang_op_id` int(11) NOT NULL,
  `oplang_lang_id` int(11) NOT NULL,
  `oplang_order_id` varchar(15) NOT NULL,
  `op_lpackage_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_statuses`
--

DROP TABLE IF EXISTS `tbl_order_statuses`;
CREATE TABLE `tbl_order_statuses` (
  `orderstatus_id` int(11) NOT NULL,
  `orderstatus_identifier` varchar(255) NOT NULL,
  `orderstatus_type` tinyint(4) NOT NULL,
  `orderstatus_priority` int(11) NOT NULL,
  `orderstatus_is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_order_statuses`
--

INSERT INTO `tbl_order_statuses` (`orderstatus_id`, `orderstatus_identifier`, `orderstatus_type`, `orderstatus_priority`, `orderstatus_is_active`) VALUES
(1, 'Payment Pending', 1, 1, 1),
(2, 'Payment Confirmed', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_statuses_lang`
--

DROP TABLE IF EXISTS `tbl_order_statuses_lang`;
CREATE TABLE `tbl_order_statuses_lang` (
  `orderstatuslang_orderstatus_id` int(11) NOT NULL,
  `orderstatuslang_lang_id` int(11) NOT NULL,
  `orderstatus_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_order_statuses_lang`
--

INSERT INTO `tbl_order_statuses_lang` (`orderstatuslang_orderstatus_id`, `orderstatuslang_lang_id`, `orderstatus_name`) VALUES
(1, 1, 'Payment Pending'),
(2, 1, 'Payment Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_status_history`
--

DROP TABLE IF EXISTS `tbl_order_status_history`;
CREATE TABLE `tbl_order_status_history` (
  `oshistory_id` int(11) NOT NULL,
  `oshistory_order_id` varchar(15) NOT NULL,
  `oshistory_op_id` int(11) NOT NULL,
  `oshistory_orderstatus_id` int(11) NOT NULL,
  `oshistory_order_payment_status` int(11) NOT NULL,
  `oshistory_date_added` datetime NOT NULL,
  `oshistory_customer_notified` tinyint(1) NOT NULL,
  `oshistory_tracking_number` varchar(255) NOT NULL,
  `oshistory_comments` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_methods`
--

DROP TABLE IF EXISTS `tbl_payment_methods`;
CREATE TABLE `tbl_payment_methods` (
  `pmethod_id` int(11) NOT NULL,
  `pmethod_identifier` varchar(50) NOT NULL,
  `pmethod_code` varchar(100) NOT NULL,
  `pmethod_active` tinyint(1) NOT NULL,
  `pmethod_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_payment_methods`
--

INSERT INTO `tbl_payment_methods` (`pmethod_id`, `pmethod_identifier`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`) VALUES
(1, 'authorizeaim', 'AuthorizeAim', 1, 2),
(2, 'wzyseller', 'PaypalStandard', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_methods_lang`
--

DROP TABLE IF EXISTS `tbl_payment_methods_lang`;
CREATE TABLE `tbl_payment_methods_lang` (
  `pmethodlang_pmethod_id` int(11) NOT NULL,
  `pmethodlang_lang_id` int(11) NOT NULL,
  `pmethod_name` varchar(200) NOT NULL,
  `pmethod_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_payment_methods_lang`
--

INSERT INTO `tbl_payment_methods_lang` (`pmethodlang_pmethod_id`, `pmethodlang_lang_id`, `pmethod_name`, `pmethod_description`) VALUES
(1, 1, 'Credit Card - Authorize.Net (AIM)', 'Credit Card - Authorize.Net (AIM) -  payment method description will go here.'),
(2, 1, 'PayPal Payments Standard', 'PayPal Payment Gateway Description will go here.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_method_settings`
--

DROP TABLE IF EXISTS `tbl_payment_method_settings`;
CREATE TABLE `tbl_payment_method_settings` (
  `paysetting_pmethod_id` int(11) NOT NULL,
  `paysetting_key` varchar(100) NOT NULL,
  `paysetting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_payment_method_settings`
--

INSERT INTO `tbl_payment_method_settings` (`paysetting_pmethod_id`, `paysetting_key`, `paysetting_value`) VALUES
(1, 'login_id', '2am5nE2Fzf'),
(1, 'md5_hash', 'BIGSECRET'),
(1, 'transaction_key', '539Kst89yW4EfpY5'),
(2, 'merchant_email', 'wzyseller@dummyid.com'),
(2, 'order_status_completed', '1'),
(2, 'order_status_initial', '0'),
(2, 'order_status_others', '0'),
(2, 'order_status_pending', '0'),
(2, 'order_status_processed', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_preferences`
--

DROP TABLE IF EXISTS `tbl_preferences`;
CREATE TABLE `tbl_preferences` (
  `preference_id` int(11) NOT NULL,
  `preference_type` int(11) NOT NULL,
  `preference_identifier` varchar(200) NOT NULL,
  `preference_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_preferences`
--

INSERT INTO `tbl_preferences` (`preference_id`, `preference_type`, `preference_identifier`, `preference_display_order`) VALUES
(4, 2, 'Beginner', 0),
(5, 2, 'Upper Intermediate', 0),
(6, 3, 'Children (4-11yrs)', 0),
(7, 3, 'Adults (18+)', 0),
(8, 4, 'Curriculum', 0),
(9, 4, 'Proficiency Assessment', 0),
(10, 5, 'Academic English', 0),
(11, 5, 'Listening Comprehension', 0),
(12, 6, 'ACT', 0),
(13, 6, 'ICAS', 0),
(14, 1, 'American English', 0),
(15, 1, 'Irish English', 0),
(20, 6, 'GRE', 0),
(21, 2, 'Advanced', 0),
(22, 3, 'Young Adults (12-17yrs)', 0),
(23, 5, 'Conversational Practice', 0),
(24, 5, 'Accent Reduction', 0),
(25, 5, 'Grammar', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_preferences_lang`
--

DROP TABLE IF EXISTS `tbl_preferences_lang`;
CREATE TABLE `tbl_preferences_lang` (
  `preferencelang_preference_id` int(11) NOT NULL,
  `preferencelang_lang_id` int(11) NOT NULL,
  `preference_title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_preferences_lang`
--

INSERT INTO `tbl_preferences_lang` (`preferencelang_preference_id`, `preferencelang_lang_id`, `preference_title`) VALUES
(4, 1, 'Beginner'),
(5, 1, 'Upper Intermediate'),
(6, 1, 'Children (4-11)'),
(7, 1, 'Adults (18+)'),
(8, 1, 'Curriculum'),
(9, 1, 'Proficiency Assessment'),
(10, 1, 'Academic English'),
(11, 1, 'Listening Comprehension'),
(12, 1, 'ACT'),
(13, 1, 'ICAS'),
(14, 1, 'American English'),
(15, 1, 'Irish English'),
(20, 1, 'GRE'),
(20, 2, 'GRE'),
(21, 1, 'Advanced'),
(22, 1, 'Young Adults (12-17yrs)'),
(23, 1, 'Conversational practice'),
(24, 1, 'Accent Reduction'),
(25, 1, 'Misc. Grammar');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scheduled_lessons`
--

DROP TABLE IF EXISTS `tbl_scheduled_lessons`;
CREATE TABLE `tbl_scheduled_lessons` (
  `slesson_id` int(11) NOT NULL,
  `slesson_order_id` varchar(15) NOT NULL,
  `slesson_teacher_id` int(11) NOT NULL,
  `slesson_learner_id` int(11) NOT NULL,
  `slesson_date` date NOT NULL,
  `slesson_start_time` time NOT NULL,
  `slesson_end_time` time NOT NULL,
  `slesson_teacher_join_time` datetime NOT NULL,
  `slesson_learner_join_time` datetime NOT NULL,
  `slesson_teacher_end_time` datetime NOT NULL,
  `slesson_learner_end_time` datetime NOT NULL,
  `slesson_ended_by` tinyint(4) NOT NULL,
  `slesson_ended_on` datetime NOT NULL,
  `slesson_status` tinyint(1) NOT NULL COMMENT 'defined in model',
  `slesson_comments` text NOT NULL,
  `slesson_is_teacher_paid` tinyint(4) NOT NULL,
  `slesson_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scheduled_lessons_to_teachers_lessons_plan`
--

DROP TABLE IF EXISTS `tbl_scheduled_lessons_to_teachers_lessons_plan`;
CREATE TABLE `tbl_scheduled_lessons_to_teachers_lessons_plan` (
  `ltp_slessonid` int(11) NOT NULL,
  `ltp_tlpn_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shared_flashcards`
--

DROP TABLE IF EXISTS `tbl_shared_flashcards`;
CREATE TABLE `tbl_shared_flashcards` (
  `sflashcard_flashcard_id` int(11) NOT NULL,
  `sflashcard_learner_id` int(11) NOT NULL,
  `sflashcard_teacher_id` int(11) NOT NULL,
  `sflashcard_slesson_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slides`
--

DROP TABLE IF EXISTS `tbl_slides`;
CREATE TABLE `tbl_slides` (
  `slide_id` int(11) NOT NULL,
  `slide_identifier` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `slide_type` int(11) NOT NULL,
  `slide_record_id` int(11) NOT NULL,
  `slide_url` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `slide_target` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `slide_active` tinyint(1) NOT NULL,
  `slide_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_slides`
--

INSERT INTO `tbl_slides` (`slide_id`, `slide_identifier`, `slide_type`, `slide_record_id`, `slide_url`, `slide_target`, `slide_active`, `slide_display_order`) VALUES
(3, '1001', 1, 0, 'http://weyakyak.staging.4demo.biz/', '_self', 1, 2),
(4, '103', 1, 0, 'https://weyakyak-testing.staging.4demo.biz', '_blank', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slides_lang`
--

DROP TABLE IF EXISTS `tbl_slides_lang`;
CREATE TABLE `tbl_slides_lang` (
  `slidelang_slide_id` int(11) NOT NULL,
  `slidelang_lang_id` int(11) NOT NULL,
  `slide_title` varchar(200) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_slides_lang`
--

INSERT INTO `tbl_slides_lang` (`slidelang_slide_id`, `slidelang_lang_id`, `slide_title`) VALUES
(3, 1, '1002'),
(3, 2, '1003'),
(4, 1, 'slider103'),
(4, 2, 'slider13110');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_platforms`
--

DROP TABLE IF EXISTS `tbl_social_platforms`;
CREATE TABLE `tbl_social_platforms` (
  `splatform_id` int(11) NOT NULL,
  `splatform_user_id` int(11) NOT NULL,
  `splatform_identifier` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `splatform_url` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `splatform_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_social_platforms`
--

INSERT INTO `tbl_social_platforms` (`splatform_id`, `splatform_user_id`, `splatform_identifier`, `splatform_url`, `splatform_active`) VALUES
(1, 0, 'Facebook', 'https://www.facebook.com/weyakyak/', 1),
(2, 0, 'Twitter', 'https://twitter.com/we_yak', 1),
(3, 0, 'Instagram', 'https://www.instagram.com/weyakyak/', 1),
(4, 0, 'Pintrest', 'https://www.pinterest.com/Weyakyak/pins/', 1),
(5, 0, 'YouTube', 'https://www.youtube.com/user/thepoodlegirl/featured?view_as=subscriber', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_platforms_lang`
--

DROP TABLE IF EXISTS `tbl_social_platforms_lang`;
CREATE TABLE `tbl_social_platforms_lang` (
  `splatformlang_splatform_id` int(11) NOT NULL,
  `splatformlang_lang_id` int(11) NOT NULL,
  `splatform_title` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_social_platforms_lang`
--

INSERT INTO `tbl_social_platforms_lang` (`splatformlang_splatform_id`, `splatformlang_lang_id`, `splatform_title`) VALUES
(1, 1, 'Facebook'),
(1, 2, 'Facebook'),
(2, 1, 'TW'),
(3, 1, 'IG'),
(4, 1, 'P'),
(5, 1, 'YT');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_spoken_languages`
--

DROP TABLE IF EXISTS `tbl_spoken_languages`;
CREATE TABLE `tbl_spoken_languages` (
  `slanguage_id` int(11) NOT NULL,
  `slanguage_code` varchar(4) CHARACTER SET utf8mb4 NOT NULL,
  `slanguage_identifier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slanguage_flag` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `slanguage_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='same table used for teaching languages and spoken languages';

--
-- Dumping data for table `tbl_spoken_languages`
--

INSERT INTO `tbl_spoken_languages` (`slanguage_id`, `slanguage_code`, `slanguage_identifier`, `slanguage_flag`, `slanguage_active`) VALUES
(1, 'EN', 'English', 'gb.png', 1),
(2, 'FR', 'French', 'fr.png', 1),
(3, 'DE', 'German', 'de.png', 1),
(4, 'IT', 'Italian', 'it.png', 1),
(5, 'Arab', 'Arabic', 'ddf', 1),
(7, 'Sp', 'Spanish', 'spain-flag-icon-256.pgn', 1),
(8, 'Ru', 'Russian', 'Ru.png', 1),
(9, 'Ro', 'Romanian', 'Ro.png', 1),
(10, 'MaCh', 'Mandarin(Chinese)', 'Mc.pgn', 1),
(11, 'Prt', 'Portuguese', 'prt.pgn', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_spoken_languages_lang`
--

DROP TABLE IF EXISTS `tbl_spoken_languages_lang`;
CREATE TABLE `tbl_spoken_languages_lang` (
  `slanguagelang_slanguage_id` int(11) NOT NULL,
  `slanguagelang_lang_id` int(11) NOT NULL,
  `slanguage_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_spoken_languages_lang`
--

INSERT INTO `tbl_spoken_languages_lang` (`slanguagelang_slanguage_id`, `slanguagelang_lang_id`, `slanguage_name`) VALUES
(1, 1, 'English'),
(2, 1, 'French'),
(3, 1, 'German'),
(4, 1, 'Italian'),
(5, 1, 'Arabic'),
(5, 2, 'Arabic'),
(7, 1, 'Spanish'),
(8, 1, 'Russian'),
(9, 1, 'Romanian'),
(10, 1, 'Mandarin(Chinese)'),
(11, 1, 'Portuguese');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_states`
--

DROP TABLE IF EXISTS `tbl_states`;
CREATE TABLE `tbl_states` (
  `state_id` int(11) NOT NULL,
  `state_code` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `state_country_id` int(11) NOT NULL,
  `state_identifier` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `state_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_states`
--

INSERT INTO `tbl_states` (`state_id`, `state_code`, `state_country_id`, `state_identifier`, `state_active`) VALUES
(1, 'Kerala', 1, 'Kerala', 1),
(2, 'Hr-001', 2, 'HR', 1),
(3, 'AZ', 6, 'Arizona', 1),
(4, 'CH', 2, 'chandigarh', 0),
(5, 'MAN', 7, 'Manitoba', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_states_lang`
--

DROP TABLE IF EXISTS `tbl_states_lang`;
CREATE TABLE `tbl_states_lang` (
  `statelang_state_id` int(11) NOT NULL,
  `statelang_lang_id` int(11) NOT NULL,
  `state_name` varchar(150) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_states_lang`
--

INSERT INTO `tbl_states_lang` (`statelang_state_id`, `statelang_lang_id`, `state_name`) VALUES
(1, 1, 'Baghlan1'),
(1, 2, 'Baghlan1'),
(2, 1, 'haryana'),
(2, 2, 'haryana'),
(3, 1, 'Arizona'),
(3, 2, 'Arizona'),
(4, 1, 'chandigarh'),
(4, 2, 'chandigarh'),
(5, 1, 'Manitoba'),
(5, 2, 'Manitoba');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers_general_availability`
--

DROP TABLE IF EXISTS `tbl_teachers_general_availability`;
CREATE TABLE `tbl_teachers_general_availability` (
  `tgavl_id` bigint(20) NOT NULL,
  `tgavl_user_id` int(11) NOT NULL,
  `tgavl_day` tinyint(2) NOT NULL,
  `tgavl_start_time` time NOT NULL,
  `tgavl_end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers_lessons_plan`
--

DROP TABLE IF EXISTS `tbl_teachers_lessons_plan`;
CREATE TABLE `tbl_teachers_lessons_plan` (
  `tlpn_id` int(11) NOT NULL,
  `tlpn_user_id` int(11) NOT NULL COMMENT 'Teacher ID',
  `tlpn_title` varchar(255) NOT NULL,
  `tlpn_description` text NOT NULL,
  `tlpn_level` int(11) NOT NULL COMMENT 'beginner, intermediate, advanced, defined in model',
  `tlpn_tags` text NOT NULL,
  `tlpn_links` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers_weekly_schedule`
--

DROP TABLE IF EXISTS `tbl_teachers_weekly_schedule`;
CREATE TABLE `tbl_teachers_weekly_schedule` (
  `twsch_id` bigint(20) NOT NULL,
  `twsch_user_id` int(11) NOT NULL,
  `twsch_date` date NOT NULL,
  `twsch_start_time` time NOT NULL,
  `twsch_end_time` time NOT NULL,
  `twsch_is_available` tinyint(2) NOT NULL COMMENT 'available/unavailable, defined in model'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_courses`
--

DROP TABLE IF EXISTS `tbl_teacher_courses`;
CREATE TABLE `tbl_teacher_courses` (
  `tcourse_id` int(11) NOT NULL,
  `tcourse_user_id` int(11) NOT NULL,
  `tcourse_title` varchar(255) NOT NULL,
  `tcourse_description` text NOT NULL,
  `tcourse_tags` text NOT NULL,
  `tcourse_category` int(11) NOT NULL,
  `tcourse_no_of_lessons` int(11) NOT NULL,
  `tcourse_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_courses_to_teachers_lessons_plan`
--

DROP TABLE IF EXISTS `tbl_teacher_courses_to_teachers_lessons_plan`;
CREATE TABLE `tbl_teacher_courses_to_teachers_lessons_plan` (
  `ctp_tcourse_id` int(11) NOT NULL,
  `ctp_tlpn_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_lesson_rating`
--

DROP TABLE IF EXISTS `tbl_teacher_lesson_rating`;
CREATE TABLE `tbl_teacher_lesson_rating` (
  `tlrating_tlreview_id` int(11) NOT NULL,
  `tlrating_rating_type` int(11) NOT NULL,
  `tlrating_rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_lesson_reviews`
--

DROP TABLE IF EXISTS `tbl_teacher_lesson_reviews`;
CREATE TABLE `tbl_teacher_lesson_reviews` (
  `tlreview_id` int(11) NOT NULL,
  `tlreview_teacher_user_id` int(11) NOT NULL,
  `tlreview_lesson_id` varchar(15) NOT NULL,
  `tlreview_postedby_user_id` int(11) NOT NULL,
  `tlreview_title` varchar(255) NOT NULL,
  `tlreview_description` text NOT NULL,
  `tlreview_posted_on` datetime NOT NULL,
  `tlreview_status` tinyint(4) NOT NULL,
  `tlreview_lang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_offer_price`
--

DROP TABLE IF EXISTS `tbl_teacher_offer_price`;
CREATE TABLE `tbl_teacher_offer_price` (
  `top_teacher_id` int(11) NOT NULL,
  `top_learner_id` int(11) NOT NULL,
  `top_single_lesson_price` decimal(10,2) NOT NULL,
  `top_bulk_lesson_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_testimonials`
--

DROP TABLE IF EXISTS `tbl_testimonials`;
CREATE TABLE `tbl_testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `testimonial_identifier` varchar(150) NOT NULL,
  `testimonial_active` tinyint(1) NOT NULL,
  `testimonial_deleted` tinyint(1) NOT NULL,
  `testimonial_added_on` datetime NOT NULL,
  `testimonial_user_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_testimonials`
--

INSERT INTO `tbl_testimonials` (`testimonial_id`, `testimonial_identifier`, `testimonial_active`, `testimonial_deleted`, `testimonial_added_on`, `testimonial_user_name`) VALUES
(1, 'hgjhg1', 1, 0, '2018-09-29 17:59:51', 'ghj1'),
(2, 'Testimonials 1', 1, 1, '2018-10-19 12:18:42', 'Testimonials 11'),
(3, 'Testimonials 1Testimonials 101', 1, 1, '2018-10-19 12:19:19', 'Testimonials 1112'),
(4, 'test', 0, 1, '2019-04-06 15:46:35', 'test'),
(5, 'abc test', 1, 0, '2019-05-15 22:03:15', 'loremn william');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_testimonials_lang`
--

DROP TABLE IF EXISTS `tbl_testimonials_lang`;
CREATE TABLE `tbl_testimonials_lang` (
  `testimoniallang_testimonial_id` int(11) NOT NULL,
  `testimoniallang_lang_id` int(11) NOT NULL,
  `testimonial_title` varchar(255) NOT NULL,
  `testimonial_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_testimonials_lang`
--

INSERT INTO `tbl_testimonials_lang` (`testimoniallang_testimonial_id`, `testimoniallang_lang_id`, `testimonial_title`, `testimonial_text`) VALUES
(1, 1, 'ghjgh', 'ghj'),
(1, 2, 'ghjhg', 'ghjhg'),
(2, 1, 'Testimonials 1', 'Testimonials 11'),
(2, 2, 'Testimonials 1', 'Testimonials 11'),
(3, 1, 'Testimonials 1112', 'Testimonials 1112'),
(3, 2, 'Testimonials 1112', 'Testimonials 1112'),
(4, 1, 'test', 'test'),
(4, 2, 'Text', 'Text'),
(5, 1, 'lorem', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(5, 2, 'lorem', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_threads`
--

DROP TABLE IF EXISTS `tbl_threads`;
CREATE TABLE `tbl_threads` (
  `thread_id` bigint(20) NOT NULL,
  `thread_start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thread_messages`
--

DROP TABLE IF EXISTS `tbl_thread_messages`;
CREATE TABLE `tbl_thread_messages` (
  `message_id` bigint(20) NOT NULL,
  `message_thread_id` int(11) NOT NULL,
  `message_from` int(11) NOT NULL COMMENT 'user_id',
  `message_to` int(11) NOT NULL COMMENT 'user_id',
  `message_text` text NOT NULL,
  `message_date` datetime NOT NULL,
  `message_is_unread` tinyint(1) NOT NULL DEFAULT '1',
  `message_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thread_users`
--

DROP TABLE IF EXISTS `tbl_thread_users`;
CREATE TABLE `tbl_thread_users` (
  `threaduser_id` bigint(20) NOT NULL,
  `threaduser_thread_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_url_rewrites`
--

DROP TABLE IF EXISTS `tbl_url_rewrites`;
CREATE TABLE `tbl_url_rewrites` (
  `urlrewrite_id` int(11) NOT NULL,
  `urlrewrite_original` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `urlrewrite_custom` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_url_rewrites`
--

INSERT INTO `tbl_url_rewrites` (`urlrewrite_id`, `urlrewrite_original`, `urlrewrite_custom`) VALUES
(1, 'blog/category/2', 'demo-cat'),
(2, 'blog/post-detail/1', 'blog-post-detail-3'),
(3, 'blog/post-detail/3', '5-features'),
(4, 'blog/post-detail/4', 'http:-weyakyak.staging.4demo.biz'),
(5, 'cms/view/7', 'apply-to-teach');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_first_name` varchar(100) NOT NULL,
  `user_last_name` varchar(100) NOT NULL,
  `user_phone` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `user_gender` tinyint(2) NOT NULL,
  `user_dob` date NOT NULL,
  `user_profile_info` text CHARACTER SET utf8mb4 NOT NULL,
  `user_address1` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `user_address2` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `user_zip` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  `user_timezone` varchar(100) NOT NULL,
  `user_country_id` int(11) NOT NULL,
  `user_state_id` int(11) NOT NULL,
  `user_city` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_is_learner` tinyint(1) NOT NULL,
  `user_is_teacher` tinyint(1) NOT NULL,
  `user_facebook_id` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_googleplus_id` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_fb_access_token` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_preferred_dashboard` tinyint(4) NOT NULL,
  `user_registered_initially_for` int(11) NOT NULL COMMENT 'user type defined in user model',
  `user_added_on` datetime NOT NULL,
  `user_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_first_name`, `user_last_name`, `user_phone`, `user_gender`, `user_dob`, `user_profile_info`, `user_address1`, `user_address2`, `user_zip`, `user_timezone`, `user_country_id`, `user_state_id`, `user_city`, `user_is_learner`, `user_is_teacher`, `user_facebook_id`, `user_googleplus_id`, `user_fb_access_token`, `user_preferred_dashboard`, `user_registered_initially_for`, `user_added_on`, `user_deleted`) VALUES
(1, 'teacher', 'teacher', '9696969696', 1, '0000-00-00', '', '', '', '', '', 0, 0, '', 1, 1, '', '', '', 1, 1, '2019-06-01 11:31:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_lang`
--

DROP TABLE IF EXISTS `tbl_users_lang`;
CREATE TABLE `tbl_users_lang` (
  `userlang_user_id` int(11) NOT NULL,
  `userlang_lang_id` int(11) NOT NULL,
  `userlang_user_profile_Info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_auth_token`
--

DROP TABLE IF EXISTS `tbl_user_auth_token`;
CREATE TABLE `tbl_user_auth_token` (
  `uauth_user_id` int(11) NOT NULL,
  `uauth_token` varchar(32) CHARACTER SET utf8mb4 NOT NULL,
  `uauth_fcm_id` varchar(300) CHARACTER SET utf8mb4 NOT NULL,
  `uauth_expiry` datetime NOT NULL,
  `uauth_browser` text CHARACTER SET utf8mb4 NOT NULL,
  `uauth_last_access` datetime NOT NULL,
  `uauth_last_ip` varchar(16) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store cookies information';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_bank_details`
--

DROP TABLE IF EXISTS `tbl_user_bank_details`;
CREATE TABLE `tbl_user_bank_details` (
  `ub_user_id` int(11) NOT NULL,
  `ub_bank_name` varchar(255) NOT NULL,
  `ub_account_holder_name` varchar(255) NOT NULL,
  `ub_account_number` varchar(100) NOT NULL,
  `ub_ifsc_swift_code` varchar(100) NOT NULL,
  `ub_bank_address` text NOT NULL,
  `ub_paypal_email_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_cart`
--

DROP TABLE IF EXISTS `tbl_user_cart`;
CREATE TABLE `tbl_user_cart` (
  `usercart_user_id` varchar(100) NOT NULL,
  `usercart_type` int(11) NOT NULL,
  `usercart_details` mediumtext NOT NULL,
  `usercart_added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_credentials`
--

DROP TABLE IF EXISTS `tbl_user_credentials`;
CREATE TABLE `tbl_user_credentials` (
  `credential_user_id` int(11) NOT NULL,
  `credential_username` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `credential_email` varchar(150) CHARACTER SET utf8mb4 DEFAULT NULL,
  `credential_password` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `credential_active` tinyint(4) NOT NULL,
  `credential_verified` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user_credentials`
--

INSERT INTO `tbl_user_credentials` (`credential_user_id`, `credential_username`, `credential_email`, `credential_password`, `credential_active`, `credential_verified`) VALUES
(1, 'teacher@dummyid.com', 'teacher@dummyid.com', 'f9e7bcecf29ad83e7a783b510881ac9e', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_email_verification`
--

DROP TABLE IF EXISTS `tbl_user_email_verification`;
CREATE TABLE `tbl_user_email_verification` (
  `uev_user_id` int(11) NOT NULL,
  `uev_token` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `uev_email` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_favourite_teachers`
--

DROP TABLE IF EXISTS `tbl_user_favourite_teachers`;
CREATE TABLE `tbl_user_favourite_teachers` (
  `uft_id` int(11) NOT NULL,
  `uft_user_id` int(11) NOT NULL,
  `uft_teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_password_reset_requests`
--

DROP TABLE IF EXISTS `tbl_user_password_reset_requests`;
CREATE TABLE `tbl_user_password_reset_requests` (
  `uprr_user_id` int(11) NOT NULL,
  `uprr_token` varchar(255) NOT NULL,
  `uprr_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_qualifications`
--

DROP TABLE IF EXISTS `tbl_user_qualifications`;
CREATE TABLE `tbl_user_qualifications` (
  `uqualification_id` int(11) NOT NULL,
  `uqualification_user_id` int(11) NOT NULL,
  `uqualification_experience_type` int(11) NOT NULL COMMENT 'defined in model',
  `uqualification_title` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `uqualification_institute_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `uqualification_institute_address` text CHARACTER SET utf8mb4 NOT NULL,
  `uqualification_description` text NOT NULL,
  `uqualification_start_year` int(11) NOT NULL,
  `uqualification_end_year` int(11) NOT NULL,
  `uqualification_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_settings`
--

DROP TABLE IF EXISTS `tbl_user_settings`;
CREATE TABLE `tbl_user_settings` (
  `us_user_id` int(11) NOT NULL,
  `us_is_trial_lesson_enabled` tinyint(2) NOT NULL,
  `us_notice_number` int(11) NOT NULL,
  `us_single_lesson_amount` decimal(10,2) NOT NULL,
  `us_bulk_lesson_amount` decimal(10,2) NOT NULL,
  `us_video_link` varchar(255) NOT NULL,
  `us_teach_slanguage_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user_settings`
--

INSERT INTO `tbl_user_settings` (`us_user_id`, `us_is_trial_lesson_enabled`, `us_notice_number`, `us_single_lesson_amount`, `us_bulk_lesson_amount`, `us_video_link`, `us_teach_slanguage_id`) VALUES
(1, 0, 0, '0.00', '0.00', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_teacher_requests`
--

DROP TABLE IF EXISTS `tbl_user_teacher_requests`;
CREATE TABLE `tbl_user_teacher_requests` (
  `utrequest_id` bigint(20) NOT NULL,
  `utrequest_reference` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `utrequest_user_id` bigint(20) NOT NULL,
  `utrequest_date` datetime NOT NULL,
  `utrequest_status` tinyint(1) NOT NULL,
  `utrequest_comments` text CHARACTER SET utf8mb4 NOT NULL,
  `utrequest_attempts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user_teacher_requests`
--

INSERT INTO `tbl_user_teacher_requests` (`utrequest_id`, `utrequest_reference`, `utrequest_user_id`, `utrequest_date`, `utrequest_status`, `utrequest_comments`, `utrequest_attempts`) VALUES
(1, '1-1559376961', 1, '2019-06-01 13:46:01', 1, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_teacher_request_values`
--

DROP TABLE IF EXISTS `tbl_user_teacher_request_values`;
CREATE TABLE `tbl_user_teacher_request_values` (
  `utrvalue_id` int(11) NOT NULL,
  `utrvalue_utrequest_id` int(11) NOT NULL,
  `utrvalue_user_first_name` varchar(100) NOT NULL,
  `utrvalue_user_last_name` varchar(100) NOT NULL,
  `utrvalue_user_gender` int(11) NOT NULL,
  `utrvalue_user_phone` varchar(50) NOT NULL,
  `utrvalue_user_video_link` varchar(255) NOT NULL,
  `utrvalue_user_profile_info` text NOT NULL,
  `utrvalue_user_teach_slanguage_id` int(11) NOT NULL,
  `utrvalue_user_language_speak` varchar(255) NOT NULL,
  `utrvalue_user_language_speak_proficiency` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user_teacher_request_values`
--

INSERT INTO `tbl_user_teacher_request_values` (`utrvalue_id`, `utrvalue_utrequest_id`, `utrvalue_user_first_name`, `utrvalue_user_last_name`, `utrvalue_user_gender`, `utrvalue_user_phone`, `utrvalue_user_video_link`, `utrvalue_user_profile_info`, `utrvalue_user_teach_slanguage_id`, `utrvalue_user_language_speak`, `utrvalue_user_language_speak_proficiency`) VALUES
(1, 1, 'teacher', 'teacher', 1, '9696969696', '', '', 1, '[\"7\"]', '[\"2\"]');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_timetables`
--

DROP TABLE IF EXISTS `tbl_user_timetables`;
CREATE TABLE `tbl_user_timetables` (
  `utimetable_id` int(11) NOT NULL,
  `utimetable_user_id` int(11) NOT NULL,
  `utimetable_day` int(11) NOT NULL COMMENT 'defined in model',
  `utimetable_start_time` time NOT NULL,
  `utimetable_end_time` time NOT NULL,
  `utimetable_is_available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='teacher availability calendar';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_to_preference`
--

DROP TABLE IF EXISTS `tbl_user_to_preference`;
CREATE TABLE `tbl_user_to_preference` (
  `utpref_user_id` int(11) NOT NULL,
  `utpref_preference_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_to_spoken_languages`
--

DROP TABLE IF EXISTS `tbl_user_to_spoken_languages`;
CREATE TABLE `tbl_user_to_spoken_languages` (
  `utsl_user_id` int(11) NOT NULL,
  `utsl_slanguage_id` int(11) NOT NULL,
  `utsl_proficiency` int(11) NOT NULL COMMENT 'defined in spoken language model'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user_to_spoken_languages`
--

INSERT INTO `tbl_user_to_spoken_languages` (`utsl_user_id`, `utsl_slanguage_id`, `utsl_proficiency`) VALUES
(1, 7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_transactions`
--

DROP TABLE IF EXISTS `tbl_user_transactions`;
CREATE TABLE `tbl_user_transactions` (
  `utxn_id` bigint(20) NOT NULL,
  `utxn_user_id` int(11) NOT NULL,
  `utxn_date` datetime NOT NULL,
  `utxn_credit` decimal(10,2) NOT NULL,
  `utxn_debit` decimal(10,2) NOT NULL,
  `utxn_comments` text NOT NULL,
  `utxn_status` tinyint(1) NOT NULL,
  `utxn_order_id` varchar(15) NOT NULL,
  `utxn_op_id` int(11) NOT NULL,
  `utxn_withdrawal_id` int(11) NOT NULL,
  `utxn_type` int(11) NOT NULL COMMENT 'defined in transactions model',
  `utxn_slesson_id` int(11) NOT NULL COMMENT 'In Case Of Issue Report'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_withdrawal_requests`
--

DROP TABLE IF EXISTS `tbl_user_withdrawal_requests`;
CREATE TABLE `tbl_user_withdrawal_requests` (
  `withdrawal_id` bigint(20) NOT NULL,
  `withdrawal_user_id` int(11) NOT NULL,
  `withdrawal_amount` decimal(10,2) NOT NULL,
  `withdrawal_bank` varchar(255) NOT NULL,
  `withdrawal_account_holder_name` varchar(255) NOT NULL,
  `withdrawal_account_number` varchar(100) NOT NULL,
  `withdrawal_ifc_swift_code` varchar(100) NOT NULL,
  `withdrawal_bank_address` text NOT NULL,
  `withdrawal_comments` text NOT NULL,
  `withdrawal_request_date` datetime NOT NULL,
  `withdrawal_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_abusive_words`
--
ALTER TABLE `tbl_abusive_words`
  ADD PRIMARY KEY (`abusive_id`),
  ADD UNIQUE KEY `abusive_word` (`abusive_keyword`,`abusive_lang_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_admin_auth_token`
--
ALTER TABLE `tbl_admin_auth_token`
  ADD PRIMARY KEY (`admauth_token`),
  ADD KEY `admrm_admin_id` (`admauth_admin_id`);

--
-- Indexes for table `tbl_admin_permissions`
--
ALTER TABLE `tbl_admin_permissions`
  ADD PRIMARY KEY (`admperm_admin_id`,`admperm_section_id`);

--
-- Indexes for table `tbl_attached_files`
--
ALTER TABLE `tbl_attached_files`
  ADD PRIMARY KEY (`afile_id`),
  ADD KEY `afile_type` (`afile_type`,`afile_record_id`,`afile_record_subid`,`afile_lang_id`) USING BTREE;

--
-- Indexes for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `tbl_banners_lang`
--
ALTER TABLE `tbl_banners_lang`
  ADD PRIMARY KEY (`bannerlang_banner_id`,`bannerlang_lang_id`);

--
-- Indexes for table `tbl_banner_locations`
--
ALTER TABLE `tbl_banner_locations`
  ADD PRIMARY KEY (`blocation_id`),
  ADD UNIQUE KEY `blocation_name` (`blocation_key`);

--
-- Indexes for table `tbl_banner_locations_lang`
--
ALTER TABLE `tbl_banner_locations_lang`
  ADD PRIMARY KEY (`blocationlang_blocation_id`,`blocationlang_lang_id`);

--
-- Indexes for table `tbl_bible_content`
--
ALTER TABLE `tbl_bible_content`
  ADD PRIMARY KEY (`biblecontent_id`);

--
-- Indexes for table `tbl_bible_content_lang`
--
ALTER TABLE `tbl_bible_content_lang`
  ADD PRIMARY KEY (`biblecontentlang_biblecontent_id`,`biblecontentlang_lang_id`);

--
-- Indexes for table `tbl_blog_contributions`
--
ALTER TABLE `tbl_blog_contributions`
  ADD PRIMARY KEY (`bcontributions_id`);

--
-- Indexes for table `tbl_blog_post`
--
ALTER TABLE `tbl_blog_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `tbl_blog_post_categories`
--
ALTER TABLE `tbl_blog_post_categories`
  ADD PRIMARY KEY (`bpcategory_id`);

--
-- Indexes for table `tbl_blog_post_categories_lang`
--
ALTER TABLE `tbl_blog_post_categories_lang`
  ADD PRIMARY KEY (`bpcategorylang_bpcategory_id`,`bpcategorylang_lang_id`);

--
-- Indexes for table `tbl_blog_post_comments`
--
ALTER TABLE `tbl_blog_post_comments`
  ADD PRIMARY KEY (`bpcomment_id`);

--
-- Indexes for table `tbl_blog_post_lang`
--
ALTER TABLE `tbl_blog_post_lang`
  ADD PRIMARY KEY (`postlang_post_id`,`postlang_lang_id`);

--
-- Indexes for table `tbl_blog_post_to_category`
--
ALTER TABLE `tbl_blog_post_to_category`
  ADD PRIMARY KEY (`ptc_bpcategory_id`,`ptc_post_id`);

--
-- Indexes for table `tbl_commission_settings`
--
ALTER TABLE `tbl_commission_settings`
  ADD PRIMARY KEY (`commsetting_id`),
  ADD UNIQUE KEY `commsetting_id` (`commsetting_id`),
  ADD UNIQUE KEY `commsetting_user_id` (`commsetting_user_id`) USING BTREE;

--
-- Indexes for table `tbl_commission_setting_history`
--
ALTER TABLE `tbl_commission_setting_history`
  ADD PRIMARY KEY (`csh_id`);

--
-- Indexes for table `tbl_configurations`
--
ALTER TABLE `tbl_configurations`
  ADD PRIMARY KEY (`conf_name`);

--
-- Indexes for table `tbl_content_pages`
--
ALTER TABLE `tbl_content_pages`
  ADD PRIMARY KEY (`cpage_id`);

--
-- Indexes for table `tbl_content_pages_block_lang`
--
ALTER TABLE `tbl_content_pages_block_lang`
  ADD PRIMARY KEY (`cpblocklang_id`),
  ADD UNIQUE KEY `cpblocklang_lang_id` (`cpblocklang_lang_id`,`cpblocklang_cpage_id`,`cpblocklang_block_id`);

--
-- Indexes for table `tbl_content_pages_lang`
--
ALTER TABLE `tbl_content_pages_lang`
  ADD PRIMARY KEY (`cpagelang_cpage_id`,`cpagelang_lang_id`);

--
-- Indexes for table `tbl_countries`
--
ALTER TABLE `tbl_countries`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country_code` (`country_code`);

--
-- Indexes for table `tbl_countries_lang`
--
ALTER TABLE `tbl_countries_lang`
  ADD PRIMARY KEY (`countrylang_country_id`,`countrylang_lang_id`),
  ADD UNIQUE KEY `countrylang_lang_id` (`countrylang_lang_id`,`country_name`);

--
-- Indexes for table `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`);

--
-- Indexes for table `tbl_coupons_history`
--
ALTER TABLE `tbl_coupons_history`
  ADD PRIMARY KEY (`couponhistory_id`);

--
-- Indexes for table `tbl_coupons_hold`
--
ALTER TABLE `tbl_coupons_hold`
  ADD PRIMARY KEY (`couponhold_id`),
  ADD UNIQUE KEY `couponhold_coupon_id` (`couponhold_coupon_id`,`couponhold_user_id`);

--
-- Indexes for table `tbl_coupons_hold_pending_order`
--
ALTER TABLE `tbl_coupons_hold_pending_order`
  ADD PRIMARY KEY (`ochold_order_id`,`ochold_coupon_id`);

--
-- Indexes for table `tbl_coupons_lang`
--
ALTER TABLE `tbl_coupons_lang`
  ADD PRIMARY KEY (`couponlang_coupon_id`,`couponlang_lang_id`);

--
-- Indexes for table `tbl_courses_categories`
--
ALTER TABLE `tbl_courses_categories`
  ADD PRIMARY KEY (`ccategory_id`);

--
-- Indexes for table `tbl_courses_categories_lang`
--
ALTER TABLE `tbl_courses_categories_lang`
  ADD PRIMARY KEY (`ccategorylang_ccategory_id`,`ccategorylang_lang_id`) USING BTREE;

--
-- Indexes for table `tbl_currencies`
--
ALTER TABLE `tbl_currencies`
  ADD PRIMARY KEY (`currency_id`),
  ADD UNIQUE KEY `currency_code` (`currency_code`);

--
-- Indexes for table `tbl_currencies_lang`
--
ALTER TABLE `tbl_currencies_lang`
  ADD PRIMARY KEY (`currencylang_currency_id`,`currencylang_lang_id`);

--
-- Indexes for table `tbl_email_archives`
--
ALTER TABLE `tbl_email_archives`
  ADD PRIMARY KEY (`emailarchive_id`);

--
-- Indexes for table `tbl_email_templates`
--
ALTER TABLE `tbl_email_templates`
  ADD PRIMARY KEY (`etpl_code`,`etpl_lang_id`);

--
-- Indexes for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `tbl_faq_lang`
--
ALTER TABLE `tbl_faq_lang`
  ADD PRIMARY KEY (`faqlang_faq_id`,`faqlang_lang_id`);

--
-- Indexes for table `tbl_flashcards`
--
ALTER TABLE `tbl_flashcards`
  ADD PRIMARY KEY (`flashcard_id`);

--
-- Indexes for table `tbl_giftcard_buyers`
--
ALTER TABLE `tbl_giftcard_buyers`
  ADD UNIQUE KEY `gcbuyer_order_id` (`gcbuyer_order_id`);

--
-- Indexes for table `tbl_giftcard_recipients`
--
ALTER TABLE `tbl_giftcard_recipients`
  ADD UNIQUE KEY `gcrecipient_op_id` (`gcrecipient_op_id`,`gcrecipient_email`);

--
-- Indexes for table `tbl_gift_cards`
--
ALTER TABLE `tbl_gift_cards`
  ADD PRIMARY KEY (`giftcard_id`),
  ADD UNIQUE KEY `giftcard_code` (`giftcard_code`),
  ADD KEY `giftcard_create_for_user_id` (`giftcard_recipient_user_id`),
  ADD KEY `giftcard_op_id` (`giftcard_op_id`);

--
-- Indexes for table `tbl_issues_reported`
--
ALTER TABLE `tbl_issues_reported`
  ADD PRIMARY KEY (`issrep_id`);

--
-- Indexes for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `tbl_language_labels`
--
ALTER TABLE `tbl_language_labels`
  ADD PRIMARY KEY (`label_id`),
  ADD UNIQUE KEY `label_key` (`label_key`,`label_lang_id`);

--
-- Indexes for table `tbl_lesson_packages`
--
ALTER TABLE `tbl_lesson_packages`
  ADD PRIMARY KEY (`lpackage_id`);

--
-- Indexes for table `tbl_lesson_packages_lang`
--
ALTER TABLE `tbl_lesson_packages_lang`
  ADD PRIMARY KEY (`lpackagelang_lpackage_id`,`lpackagelang_lang_id`) USING BTREE;

--
-- Indexes for table `tbl_meta_tags`
--
ALTER TABLE `tbl_meta_tags`
  ADD PRIMARY KEY (`meta_id`),
  ADD UNIQUE KEY `meta_controller` (`meta_controller`,`meta_action`,`meta_record_id`,`meta_subrecord_id`) USING BTREE,
  ADD UNIQUE KEY `meta_identifier` (`meta_identifier`);

--
-- Indexes for table `tbl_meta_tags_lang`
--
ALTER TABLE `tbl_meta_tags_lang`
  ADD PRIMARY KEY (`metalang_meta_id`,`metalang_lang_id`);

--
-- Indexes for table `tbl_navigations`
--
ALTER TABLE `tbl_navigations`
  ADD PRIMARY KEY (`nav_id`);

--
-- Indexes for table `tbl_navigations_lang`
--
ALTER TABLE `tbl_navigations_lang`
  ADD PRIMARY KEY (`navlang_nav_id`,`navlang_lang_id`);

--
-- Indexes for table `tbl_navigation_links`
--
ALTER TABLE `tbl_navigation_links`
  ADD PRIMARY KEY (`nlink_id`);

--
-- Indexes for table `tbl_navigation_links_lang`
--
ALTER TABLE `tbl_navigation_links_lang`
  ADD PRIMARY KEY (`nlinklang_nlink_id`,`nlinklang_lang_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_user_id` (`order_user_id`);

--
-- Indexes for table `tbl_order_payments`
--
ALTER TABLE `tbl_order_payments`
  ADD PRIMARY KEY (`opayment_id`),
  ADD KEY `op_order_id` (`opayment_order_id`),
  ADD KEY `op_gateway_txn_id` (`opayment_gateway_txn_id`);

--
-- Indexes for table `tbl_order_products`
--
ALTER TABLE `tbl_order_products`
  ADD PRIMARY KEY (`op_id`),
  ADD UNIQUE KEY `oitem_invoice_number` (`op_invoice_number`);

--
-- Indexes for table `tbl_order_products_lang`
--
ALTER TABLE `tbl_order_products_lang`
  ADD PRIMARY KEY (`oplang_op_id`,`oplang_lang_id`);

--
-- Indexes for table `tbl_order_statuses`
--
ALTER TABLE `tbl_order_statuses`
  ADD PRIMARY KEY (`orderstatus_id`),
  ADD UNIQUE KEY `orderstatus_identifier` (`orderstatus_identifier`);

--
-- Indexes for table `tbl_order_statuses_lang`
--
ALTER TABLE `tbl_order_statuses_lang`
  ADD PRIMARY KEY (`orderstatuslang_orderstatus_id`,`orderstatuslang_lang_id`);

--
-- Indexes for table `tbl_order_status_history`
--
ALTER TABLE `tbl_order_status_history`
  ADD PRIMARY KEY (`oshistory_id`);

--
-- Indexes for table `tbl_payment_methods`
--
ALTER TABLE `tbl_payment_methods`
  ADD PRIMARY KEY (`pmethod_id`),
  ADD UNIQUE KEY `pmethod_identifier` (`pmethod_identifier`),
  ADD UNIQUE KEY `pmethod_code` (`pmethod_code`);

--
-- Indexes for table `tbl_payment_methods_lang`
--
ALTER TABLE `tbl_payment_methods_lang`
  ADD PRIMARY KEY (`pmethodlang_pmethod_id`,`pmethodlang_lang_id`);

--
-- Indexes for table `tbl_payment_method_settings`
--
ALTER TABLE `tbl_payment_method_settings`
  ADD PRIMARY KEY (`paysetting_pmethod_id`,`paysetting_key`);

--
-- Indexes for table `tbl_preferences`
--
ALTER TABLE `tbl_preferences`
  ADD PRIMARY KEY (`preference_id`);

--
-- Indexes for table `tbl_preferences_lang`
--
ALTER TABLE `tbl_preferences_lang`
  ADD PRIMARY KEY (`preferencelang_preference_id`,`preferencelang_lang_id`);

--
-- Indexes for table `tbl_scheduled_lessons`
--
ALTER TABLE `tbl_scheduled_lessons`
  ADD PRIMARY KEY (`slesson_id`);

--
-- Indexes for table `tbl_scheduled_lessons_to_teachers_lessons_plan`
--
ALTER TABLE `tbl_scheduled_lessons_to_teachers_lessons_plan`
  ADD UNIQUE KEY `ltp_slessonid` (`ltp_slessonid`);

--
-- Indexes for table `tbl_shared_flashcards`
--
ALTER TABLE `tbl_shared_flashcards`
  ADD UNIQUE KEY `sflashcard_flashcard_id` (`sflashcard_flashcard_id`,`sflashcard_learner_id`,`sflashcard_teacher_id`);

--
-- Indexes for table `tbl_slides`
--
ALTER TABLE `tbl_slides`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `tbl_slides_lang`
--
ALTER TABLE `tbl_slides_lang`
  ADD PRIMARY KEY (`slidelang_slide_id`,`slidelang_lang_id`);

--
-- Indexes for table `tbl_social_platforms`
--
ALTER TABLE `tbl_social_platforms`
  ADD PRIMARY KEY (`splatform_id`);

--
-- Indexes for table `tbl_social_platforms_lang`
--
ALTER TABLE `tbl_social_platforms_lang`
  ADD PRIMARY KEY (`splatformlang_splatform_id`,`splatformlang_lang_id`);

--
-- Indexes for table `tbl_spoken_languages`
--
ALTER TABLE `tbl_spoken_languages`
  ADD PRIMARY KEY (`slanguage_id`);

--
-- Indexes for table `tbl_spoken_languages_lang`
--
ALTER TABLE `tbl_spoken_languages_lang`
  ADD PRIMARY KEY (`slanguagelang_slanguage_id`,`slanguagelang_lang_id`);

--
-- Indexes for table `tbl_states`
--
ALTER TABLE `tbl_states`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `state_country_id` (`state_country_id`,`state_identifier`);

--
-- Indexes for table `tbl_states_lang`
--
ALTER TABLE `tbl_states_lang`
  ADD PRIMARY KEY (`statelang_state_id`,`statelang_lang_id`);

--
-- Indexes for table `tbl_teachers_general_availability`
--
ALTER TABLE `tbl_teachers_general_availability`
  ADD PRIMARY KEY (`tgavl_id`);

--
-- Indexes for table `tbl_teachers_lessons_plan`
--
ALTER TABLE `tbl_teachers_lessons_plan`
  ADD PRIMARY KEY (`tlpn_id`);

--
-- Indexes for table `tbl_teachers_weekly_schedule`
--
ALTER TABLE `tbl_teachers_weekly_schedule`
  ADD PRIMARY KEY (`twsch_id`);

--
-- Indexes for table `tbl_teacher_courses`
--
ALTER TABLE `tbl_teacher_courses`
  ADD PRIMARY KEY (`tcourse_id`);

--
-- Indexes for table `tbl_teacher_courses_to_teachers_lessons_plan`
--
ALTER TABLE `tbl_teacher_courses_to_teachers_lessons_plan`
  ADD UNIQUE KEY `ctp_tcourse_id` (`ctp_tcourse_id`,`ctp_tlpn_id`);

--
-- Indexes for table `tbl_teacher_lesson_rating`
--
ALTER TABLE `tbl_teacher_lesson_rating`
  ADD PRIMARY KEY (`tlrating_tlreview_id`,`tlrating_rating_type`,`tlrating_rating`);

--
-- Indexes for table `tbl_teacher_lesson_reviews`
--
ALTER TABLE `tbl_teacher_lesson_reviews`
  ADD PRIMARY KEY (`tlreview_id`),
  ADD UNIQUE KEY `spreview_order_id` (`tlreview_lesson_id`);

--
-- Indexes for table `tbl_teacher_offer_price`
--
ALTER TABLE `tbl_teacher_offer_price`
  ADD PRIMARY KEY (`top_teacher_id`,`top_learner_id`);

--
-- Indexes for table `tbl_testimonials`
--
ALTER TABLE `tbl_testimonials`
  ADD PRIMARY KEY (`testimonial_id`);

--
-- Indexes for table `tbl_testimonials_lang`
--
ALTER TABLE `tbl_testimonials_lang`
  ADD PRIMARY KEY (`testimoniallang_testimonial_id`,`testimoniallang_lang_id`);

--
-- Indexes for table `tbl_threads`
--
ALTER TABLE `tbl_threads`
  ADD PRIMARY KEY (`thread_id`);

--
-- Indexes for table `tbl_thread_messages`
--
ALTER TABLE `tbl_thread_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `tbl_thread_users`
--
ALTER TABLE `tbl_thread_users`
  ADD PRIMARY KEY (`threaduser_id`,`threaduser_thread_id`);

--
-- Indexes for table `tbl_url_rewrites`
--
ALTER TABLE `tbl_url_rewrites`
  ADD PRIMARY KEY (`urlrewrite_id`),
  ADD UNIQUE KEY `url_rewrite_original` (`urlrewrite_original`),
  ADD UNIQUE KEY `url_rewrite_custom` (`urlrewrite_custom`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_users_lang`
--
ALTER TABLE `tbl_users_lang`
  ADD PRIMARY KEY (`userlang_user_id`,`userlang_lang_id`);

--
-- Indexes for table `tbl_user_auth_token`
--
ALTER TABLE `tbl_user_auth_token`
  ADD PRIMARY KEY (`uauth_token`),
  ADD KEY `urm_user_id` (`uauth_user_id`);

--
-- Indexes for table `tbl_user_bank_details`
--
ALTER TABLE `tbl_user_bank_details`
  ADD PRIMARY KEY (`ub_user_id`);

--
-- Indexes for table `tbl_user_cart`
--
ALTER TABLE `tbl_user_cart`
  ADD UNIQUE KEY `usercart_user_id` (`usercart_user_id`,`usercart_type`);

--
-- Indexes for table `tbl_user_credentials`
--
ALTER TABLE `tbl_user_credentials`
  ADD PRIMARY KEY (`credential_user_id`),
  ADD UNIQUE KEY `credential_username` (`credential_email`);

--
-- Indexes for table `tbl_user_email_verification`
--
ALTER TABLE `tbl_user_email_verification`
  ADD UNIQUE KEY `uev_user_id` (`uev_user_id`);

--
-- Indexes for table `tbl_user_favourite_teachers`
--
ALTER TABLE `tbl_user_favourite_teachers`
  ADD PRIMARY KEY (`uft_id`),
  ADD UNIQUE KEY `uft_user_id` (`uft_user_id`,`uft_teacher_id`);

--
-- Indexes for table `tbl_user_qualifications`
--
ALTER TABLE `tbl_user_qualifications`
  ADD PRIMARY KEY (`uqualification_id`),
  ADD KEY `uqualification_user_id` (`uqualification_user_id`);

--
-- Indexes for table `tbl_user_settings`
--
ALTER TABLE `tbl_user_settings`
  ADD PRIMARY KEY (`us_user_id`);

--
-- Indexes for table `tbl_user_teacher_requests`
--
ALTER TABLE `tbl_user_teacher_requests`
  ADD PRIMARY KEY (`utrequest_id`),
  ADD UNIQUE KEY `ututrequest_user_id` (`utrequest_user_id`);

--
-- Indexes for table `tbl_user_teacher_request_values`
--
ALTER TABLE `tbl_user_teacher_request_values`
  ADD PRIMARY KEY (`utrvalue_id`),
  ADD UNIQUE KEY `utrvalue_ututrequest_id` (`utrvalue_utrequest_id`);

--
-- Indexes for table `tbl_user_timetables`
--
ALTER TABLE `tbl_user_timetables`
  ADD PRIMARY KEY (`utimetable_id`),
  ADD UNIQUE KEY `utimetable_user_id` (`utimetable_user_id`,`utimetable_day`);

--
-- Indexes for table `tbl_user_to_preference`
--
ALTER TABLE `tbl_user_to_preference`
  ADD PRIMARY KEY (`utpref_user_id`,`utpref_preference_id`);

--
-- Indexes for table `tbl_user_to_spoken_languages`
--
ALTER TABLE `tbl_user_to_spoken_languages`
  ADD PRIMARY KEY (`utsl_user_id`,`utsl_slanguage_id`);

--
-- Indexes for table `tbl_user_transactions`
--
ALTER TABLE `tbl_user_transactions`
  ADD PRIMARY KEY (`utxn_id`);

--
-- Indexes for table `tbl_user_withdrawal_requests`
--
ALTER TABLE `tbl_user_withdrawal_requests`
  ADD PRIMARY KEY (`withdrawal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_abusive_words`
--
ALTER TABLE `tbl_abusive_words`
  MODIFY `abusive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_attached_files`
--
ALTER TABLE `tbl_attached_files`
  MODIFY `afile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=466;

--
-- AUTO_INCREMENT for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_banner_locations`
--
ALTER TABLE `tbl_banner_locations`
  MODIFY `blocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_bible_content`
--
ALTER TABLE `tbl_bible_content`
  MODIFY `biblecontent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_blog_contributions`
--
ALTER TABLE `tbl_blog_contributions`
  MODIFY `bcontributions_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_blog_post`
--
ALTER TABLE `tbl_blog_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_blog_post_categories`
--
ALTER TABLE `tbl_blog_post_categories`
  MODIFY `bpcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_blog_post_comments`
--
ALTER TABLE `tbl_blog_post_comments`
  MODIFY `bpcomment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_commission_settings`
--
ALTER TABLE `tbl_commission_settings`
  MODIFY `commsetting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_commission_setting_history`
--
ALTER TABLE `tbl_commission_setting_history`
  MODIFY `csh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_content_pages`
--
ALTER TABLE `tbl_content_pages`
  MODIFY `cpage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_content_pages_block_lang`
--
ALTER TABLE `tbl_content_pages_block_lang`
  MODIFY `cpblocklang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tbl_countries`
--
ALTER TABLE `tbl_countries`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_coupons_history`
--
ALTER TABLE `tbl_coupons_history`
  MODIFY `couponhistory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_coupons_hold`
--
ALTER TABLE `tbl_coupons_hold`
  MODIFY `couponhold_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_courses_categories`
--
ALTER TABLE `tbl_courses_categories`
  MODIFY `ccategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_currencies`
--
ALTER TABLE `tbl_currencies`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_email_archives`
--
ALTER TABLE `tbl_email_archives`
  MODIFY `emailarchive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_flashcards`
--
ALTER TABLE `tbl_flashcards`
  MODIFY `flashcard_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_gift_cards`
--
ALTER TABLE `tbl_gift_cards`
  MODIFY `giftcard_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_issues_reported`
--
ALTER TABLE `tbl_issues_reported`
  MODIFY `issrep_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_language_labels`
--
ALTER TABLE `tbl_language_labels`
  MODIFY `label_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2098;

--
-- AUTO_INCREMENT for table `tbl_lesson_packages`
--
ALTER TABLE `tbl_lesson_packages`
  MODIFY `lpackage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_meta_tags`
--
ALTER TABLE `tbl_meta_tags`
  MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_navigations`
--
ALTER TABLE `tbl_navigations`
  MODIFY `nav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_navigation_links`
--
ALTER TABLE `tbl_navigation_links`
  MODIFY `nlink_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_order_payments`
--
ALTER TABLE `tbl_order_payments`
  MODIFY `opayment_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_products`
--
ALTER TABLE `tbl_order_products`
  MODIFY `op_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_statuses`
--
ALTER TABLE `tbl_order_statuses`
  MODIFY `orderstatus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_order_status_history`
--
ALTER TABLE `tbl_order_status_history`
  MODIFY `oshistory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payment_methods`
--
ALTER TABLE `tbl_payment_methods`
  MODIFY `pmethod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_preferences`
--
ALTER TABLE `tbl_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_scheduled_lessons`
--
ALTER TABLE `tbl_scheduled_lessons`
  MODIFY `slesson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_slides`
--
ALTER TABLE `tbl_slides`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_social_platforms`
--
ALTER TABLE `tbl_social_platforms`
  MODIFY `splatform_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_spoken_languages`
--
ALTER TABLE `tbl_spoken_languages`
  MODIFY `slanguage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_states`
--
ALTER TABLE `tbl_states`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_teachers_general_availability`
--
ALTER TABLE `tbl_teachers_general_availability`
  MODIFY `tgavl_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_teachers_lessons_plan`
--
ALTER TABLE `tbl_teachers_lessons_plan`
  MODIFY `tlpn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_teachers_weekly_schedule`
--
ALTER TABLE `tbl_teachers_weekly_schedule`
  MODIFY `twsch_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_teacher_courses`
--
ALTER TABLE `tbl_teacher_courses`
  MODIFY `tcourse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_teacher_lesson_reviews`
--
ALTER TABLE `tbl_teacher_lesson_reviews`
  MODIFY `tlreview_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_testimonials`
--
ALTER TABLE `tbl_testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_threads`
--
ALTER TABLE `tbl_threads`
  MODIFY `thread_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_thread_messages`
--
ALTER TABLE `tbl_thread_messages`
  MODIFY `message_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_url_rewrites`
--
ALTER TABLE `tbl_url_rewrites`
  MODIFY `urlrewrite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user_favourite_teachers`
--
ALTER TABLE `tbl_user_favourite_teachers`
  MODIFY `uft_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_qualifications`
--
ALTER TABLE `tbl_user_qualifications`
  MODIFY `uqualification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_teacher_requests`
--
ALTER TABLE `tbl_user_teacher_requests`
  MODIFY `utrequest_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user_teacher_request_values`
--
ALTER TABLE `tbl_user_teacher_request_values`
  MODIFY `utrvalue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user_timetables`
--
ALTER TABLE `tbl_user_timetables`
  MODIFY `utimetable_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_transactions`
--
ALTER TABLE `tbl_user_transactions`
  MODIFY `utxn_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_withdrawal_requests`
--
ALTER TABLE `tbl_user_withdrawal_requests`
  MODIFY `withdrawal_id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
