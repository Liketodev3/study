-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2020 at 03:32 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_yocoach`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbl_abusive_words`
--

CREATE TABLE `tbl_abusive_words` (
  `abusive_id` int(11) NOT NULL,
  `abusive_keyword` varchar(100) NOT NULL,
  `abusive_lang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  `admin_email` varchar(150) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_username`, `admin_password`, `admin_email`, `admin_name`, `admin_active`) VALUES
(1, 'welcome', '7fb109e60e6295a6787d4ef283d5ccd1', 'pawan.kumar@ablysoft.com', 'yoCoach', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_auth_token`
--

CREATE TABLE `tbl_admin_auth_token` (
  `admauth_admin_id` int(11) NOT NULL,
  `admauth_token` varchar(32) NOT NULL,
  `admauth_expiry` datetime NOT NULL,
  `admauth_browser` mediumtext NOT NULL,
  `admauth_last_access` datetime NOT NULL,
  `admauth_last_ip` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store admin cookies information, Remember Me functionalit';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_password_reset_requests`
--

CREATE TABLE `tbl_admin_password_reset_requests` (
  `aprr_admin_id` int(10) NOT NULL,
  `aprr_token` varchar(50) NOT NULL,
  `aprr_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_permissions`
--

CREATE TABLE `tbl_admin_permissions` (
  `admperm_admin_id` int(11) NOT NULL,
  `admperm_section_id` int(11) NOT NULL,
  `admperm_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attached_files`
--

CREATE TABLE `tbl_attached_files` (
  `afile_id` int(11) NOT NULL,
  `afile_type` int(11) NOT NULL,
  `afile_record_id` int(11) NOT NULL,
  `afile_record_subid` int(11) NOT NULL,
  `afile_lang_id` int(11) NOT NULL,
  `afile_screen` int(11) NOT NULL COMMENT '1=>Desktop,2=>Ipad/Tablet,3=>Mobile',
  `afile_physical_path` varchar(250) NOT NULL,
  `afile_name` varchar(200) NOT NULL COMMENT 'For display Only',
  `afile_display_order` int(11) NOT NULL,
  `afile_downloaded_times` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_attached_files`
--

INSERT INTO `tbl_attached_files` (`afile_id`, `afile_type`, `afile_record_id`, `afile_record_subid`, `afile_lang_id`, `afile_screen`, `afile_physical_path`, `afile_name`, `afile_display_order`, `afile_downloaded_times`) VALUES
(65, 9, 7, 0, 1, 1, '2018/10/1539930692-231png', '23 (1).png', 1, 0),
(69, 9, 3, 0, 1, 2, '2018/10/1539930838-bag3jpeg', 'bag3.jpeg', 1, 0),
(72, 9, 3, 0, 2, 3, '2018/10/1539930881-bag5jpg', 'bag5.jpg', 3, 0),
(78, 9, 5, 0, 2, 3, '2018/10/1539931177-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 1, 0),
(81, 9, 9, 0, 1, 2, '2018/10/1539931429-queryjpg', 'query.jpg', 1, 0),
(82, 9, 10, 0, 1, 3, '2018/10/1539931478-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 1, 0),
(83, 9, 11, 0, 1, 2, '2018/10/1539931532-23png', '23.png', 1, 0),
(87, 9, 8, 0, 1, 2, '2018/10/1539935107-bag5jpg', 'bag5.jpg', 1, 0),
(88, 9, 8, 0, 2, 3, '2018/10/1539935123-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 3, 0),
(89, 9, 8, 0, 1, 3, '2018/10/1539935139-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 2, 0),
(94, 9, 12, 0, 1, 2, '2018/10/1539935244-61IH3oGeDyLSL1200jpg', '61IH3oGeDyL._SL1200_.jpg', 3, 0),
(95, 9, 12, 0, 1, 3, '2018/10/1539935261-1png', '1.png', 4, 0),
(96, 9, 12, 0, 0, 1, '2018/10/1539935378-1png', '1.png', 2, 0),
(97, 9, 13, 0, 1, 2, '2018/10/1539935451-231png', '23 (1).png', 1, 0),
(294, 36, 3, 0, 0, 0, '2019/04/1554979125-EnglishPNG', 'English.PNG', 1, 0),
(295, 36, 4, 0, 0, 0, '2019/04/1554980667-dummycredentialtxt', 'dummy credential.txt', 1, 0),
(296, 36, 5, 0, 0, 0, '2019/04/1555048048-dummycredentialtxt', 'dummy credential.txt', 1, 0),
(297, 36, 6, 0, 0, 0, '2019/04/1555048164-bfd96c261df8706d878951c0bd39ca8fjpg', 'bfd96c261df8706d878951c0bd39ca8f.jpg', 1, 0),
(301, 36, 7, 0, 0, 0, '2019/04/1555409401-Screenshot13Theendlessonbuttonshouldbevisiblesousercaneasilyseethebuttonpng', 'Screenshot_13 The end lesson button should be visible so user can easily see the button.png', 1, 0),
(304, 36, 8, 0, 0, 0, '2019/04/1555502653-dummycredentialtxt', 'dummy credential.txt', 1, 0),
(305, 36, 9, 0, 0, 0, '2019/04/1555502786-hatxt', 'ha.txt', 1, 0),
(306, 36, 10, 0, 0, 0, '2019/04/1555503161-24kestercyclojet3starcas24es3j8f02splitcarrieroriginalimaf2utjyhvavtzxjpg', '24k-ester-cyclojet-3-star-cas24es3j8f0-2-split-carrier-original-imaf2utjyhvavtzx.jpg', 1, 0),
(307, 36, 11, 0, 0, 0, '2019/04/1555503201-24kestercyclojet3starcas24es3j8f02splitcarrieroriginalimaf2utjyhvavtzxjpgpng', '24k-ester-cyclojet-3-star-cas24es3j8f0-2-split-carrier-original-imaf2utjyhvavtzx.jpg.png', 1, 0),
(312, 36, 12, 0, 0, 0, '2019/04/1555566654-Screenshot13Theendlessonbuttonshouldbevisiblesousercaneasilyseethebuttonpng', 'Screenshot_13 The end lesson button should be visible so user can easily see the button.png', 1, 0),
(321, 27, 6, 0, 1, 0, '2019/05/1557982502-helpjpg', 'help.jpg', 1, 0),
(324, 15, 2, 0, 0, 0, '2019/05/1557993271-helpjpg', 'help.jpg', 1, 0),
(325, 16, 2, 0, 0, 0, '2019/05/1557993276-helpjpg', 'help.jpg', 1, 0),
(382, 15, 1, 0, 0, 0, '2019/05/1558332628-1915225originaljpg', '1915225_original.jpg', 3, 0),
(383, 16, 1, 0, 0, 0, '2019/05/1558332631-1915225originaljpg', '1915225_original.jpg', 1, 0),
(415, 27, 4, 0, 1, 0, '2019/05/1558610887-bn5seriesbannerjpg', 'bn_5seriesbanner.jpg', 1, 0),
(428, 9, 3, 0, 0, 0, '2019/05/1558617300-800x5002jpg', '800x500_2.jpg', 3, 0),
(447, 9, 2, 0, 0, 0, '2019/05/1558962185-500x3901jpg', '500x390_1.jpg', 1, 0),
(449, 9, 1, 0, 0, 0, '2019/05/1559022216-500x3904jpg', '500x390_4.jpg', 1, 0),
(450, 38, 1, 0, 0, 0, '2019/05/1559022226-500x3902jpg', '500x390_2.jpg', 1, 0),
(451, 38, 2, 0, 0, 0, '2019/05/1559022243-1jpg', '1.jpg', 1, 0),
(467, 7, 4, 0, 0, 1, '2019/06/1560940773-1603x6311jpg', '1603x631_1.jpg', 1, 0),
(565, 31, 1, 0, 0, 0, '2019/04/1554111834-download1png', 'download (1).png', 1, 0),
(566, 31, 2, 0, 0, 0, '2019/05/1558335813-images2png', 'images (2).png', 1, 0),
(567, 31, 6, 0, 0, 0, '2019/09/1567580615-uspng', 'us.png', 1, 0),
(568, 31, 8, 0, 0, 0, '2019/09/1567580628-afpng', 'af.png', 1, 0),
(569, 31, 9, 0, 0, 0, '2019/09/1567580643-alpng', 'al.png', 1, 0),
(570, 31, 10, 0, 0, 0, '2019/09/1567580658-dzpng', 'dz.png', 1, 0),
(571, 31, 11, 0, 0, 0, '2019/09/1567580672-anpng', 'an.png', 1, 0),
(572, 31, 13, 0, 0, 0, '2019/09/1567580746-arpng', 'ar.png', 1, 0),
(573, 31, 14, 0, 0, 0, '2019/09/1567580758-ampng', 'am.png', 1, 0),
(574, 31, 15, 0, 0, 0, '2019/09/1567580800-awpng', 'aw.png', 1, 0),
(575, 31, 16, 0, 0, 0, '2019/09/1567580813-shpng', 'sh.png', 1, 0),
(576, 31, 17, 0, 0, 0, '2019/09/1567580825-aupng', 'au.png', 1, 0),
(577, 31, 19, 0, 0, 0, '2019/09/1567580924-azpng', 'az.png', 1, 0),
(578, 31, 20, 0, 0, 0, '2019/09/1567580935-bspng', 'bs.png', 1, 0),
(579, 31, 21, 0, 0, 0, '2019/09/1567580947-bhpng', 'bh.png', 1, 0),
(580, 31, 22, 0, 0, 0, '2019/09/1567580959-bdpng', 'bd.png', 1, 0),
(581, 31, 23, 0, 0, 0, '2019/09/1567580971-bbpng', 'bb.png', 1, 0),
(582, 31, 24, 0, 0, 0, '2019/09/1567580982-bypng', 'by.png', 1, 0),
(583, 31, 26, 0, 0, 0, '2019/09/1567581086-bzpng', 'bz.png', 1, 0),
(584, 31, 28, 0, 0, 0, '2019/09/1567581142-bmpng', 'bm.png', 1, 0),
(585, 31, 29, 0, 0, 0, '2019/09/1567581167-btpng', 'bt.png', 1, 0),
(586, 31, 30, 0, 0, 0, '2019/09/1567581181-bopng', 'bo.png', 1, 0),
(587, 31, 31, 0, 0, 0, '2019/09/1567581195-nepng', 'ne.png', 1, 0),
(588, 31, 32, 0, 0, 0, '2019/09/1567581213-bapng', 'ba.png', 1, 0),
(589, 31, 33, 0, 0, 0, '2019/09/1567581227-bwpng', 'bw.png', 1, 0),
(590, 31, 34, 0, 0, 0, '2019/09/1567581246-brpng', 'br.png', 1, 0),
(591, 31, 36, 0, 0, 0, '2019/09/1567581273-bnpng', 'bn.png', 1, 0),
(592, 31, 37, 0, 0, 0, '2019/09/1567581287-bgpng', 'bg.png', 1, 0),
(593, 31, 39, 0, 0, 0, '2019/09/1567581332-bipng', 'bi.png', 1, 0),
(594, 31, 40, 0, 0, 0, '2019/09/1567581343-cvpng', 'cv.png', 1, 0),
(595, 31, 43, 0, 0, 0, '2019/09/1567581636-capng', 'ca.png', 1, 0),
(596, 31, 45, 0, 0, 0, '2019/09/1567581648-kypng', 'ky.png', 1, 0),
(597, 31, 48, 0, 0, 0, '2019/09/1567581798-nzpng', 'nz.png', 1, 0),
(598, 31, 49, 0, 0, 0, '2019/09/1567581811-clpng', 'cl.png', 1, 0),
(599, 31, 50, 0, 0, 0, '2019/09/1567581822-chpng', 'ch.png', 1, 0),
(600, 31, 51, 0, 0, 0, '2019/09/1567581836-copng', 'co.png', 1, 0),
(601, 31, 53, 0, 0, 0, '2019/09/1567581859-cdpng', 'cd.png', 1, 0),
(602, 31, 59, 0, 0, 0, '2019/09/1567581941-cypng', 'cy.png', 1, 0),
(603, 31, 60, 0, 0, 0, '2019/09/1567581954-czpng', 'cz.png', 1, 0),
(604, 31, 61, 0, 0, 0, '2019/09/1567581966-dkpng', 'dk.png', 1, 0),
(605, 31, 62, 0, 0, 0, '2019/09/1567581978-djpng', 'dj.png', 1, 0),
(606, 31, 65, 0, 0, 0, '2019/09/1567582018-ecpng', 'ec.png', 1, 0),
(607, 31, 66, 0, 0, 0, '2019/09/1567582032-egpng', 'eg.png', 1, 0),
(608, 31, 69, 0, 0, 0, '2019/09/1567582065-erpng', 'er.png', 1, 0),
(609, 31, 75, 0, 0, 0, '2019/09/1567582129-frpng', 'fr.png', 1, 0),
(610, 31, 82, 0, 0, 0, '2019/09/1567582211-gdpng', 'gd.png', 1, 0),
(611, 31, 86, 0, 0, 0, '2019/09/1567582259-gypng', 'gy.png', 1, 0),
(612, 31, 89, 0, 0, 0, '2019/09/1567582300-hupng', 'hu.png', 1, 0),
(613, 31, 91, 0, 0, 0, '2019/09/1567582331-inpng', 'in.png', 1, 0),
(614, 31, 92, 0, 0, 0, '2019/09/1567582378-idpng', 'id.png', 1, 0),
(615, 31, 93, 0, 0, 0, '2019/09/1567582393-irpng', 'ir.png', 1, 0),
(616, 31, 94, 0, 0, 0, '2019/09/1567582412-iqpng', 'iq.png', 1, 0),
(617, 31, 97, 0, 0, 0, '2019/09/1567582457-itpng', 'it.png', 1, 0),
(618, 31, 98, 0, 0, 0, '2019/09/1567582476-jmpng', 'jm.png', 1, 0),
(619, 31, 99, 0, 0, 0, '2019/09/1567582487-jppng', 'jp.png', 1, 0),
(620, 31, 101, 0, 0, 0, '2019/09/1567582531-kzpng', 'kz.png', 1, 0),
(621, 31, 105, 0, 0, 0, '2019/09/1567582633-kwpng', 'kw.png', 1, 0),
(622, 31, 106, 0, 0, 0, '2019/09/1567582650-kgpng', 'kg.png', 1, 0),
(623, 31, 107, 0, 0, 0, '2019/09/1567582666-lspng', 'ls.png', 1, 0),
(624, 31, 108, 0, 0, 0, '2019/09/1567582678-lvpng', 'lv.png', 1, 0),
(625, 31, 109, 0, 0, 0, '2019/09/1567582690-lbpng', 'lb.png', 1, 0),
(626, 31, 111, 0, 0, 0, '2019/09/1567582738-lrpng', 'lr.png', 1, 0),
(627, 31, 117, 0, 0, 0, '2019/09/1567582851-sipng', 'si.png', 1, 0),
(628, 31, 118, 0, 0, 0, '2019/09/1567582891-mvpng', 'mv.png', 1, 0),
(629, 31, 120, 0, 0, 0, '2019/09/1567582983-mtpng', 'mt.png', 1, 0),
(630, 31, 123, 0, 0, 0, '2019/09/1567583020-mupng', 'mu.png', 1, 0),
(631, 31, 124, 0, 0, 0, '2019/09/1567583031-mxpng', 'mx.png', 1, 0),
(632, 31, 134, 0, 0, 0, '2019/09/1567583225-nppng', 'np.png', 1, 0),
(633, 31, 141, 0, 0, 0, '2019/09/1567583391-ompng', 'om.png', 1, 0),
(634, 31, 142, 0, 0, 0, '2019/09/1567583402-pkpng', 'pk.png', 1, 0),
(635, 31, 144, 0, 0, 0, '2019/09/1567583443-pspng', 'ps.png', 1, 0),
(636, 31, 145, 0, 0, 0, '2019/09/1567583454-papng', 'pa.png', 1, 0),
(637, 31, 147, 0, 0, 0, '2019/09/1567583484-pypng', 'py.png', 1, 0),
(638, 31, 151, 0, 0, 0, '2019/09/1567583635-ptpng', 'pt.png', 1, 0),
(639, 31, 152, 0, 0, 0, '2019/09/1567583647-qapng', 'qa.png', 1, 0),
(640, 31, 155, 0, 0, 0, '2019/09/1567583764-rwpng', 'rw.png', 1, 0),
(641, 31, 161, 0, 0, 0, '2019/09/1567583831-stpng', 'st.png', 1, 0),
(642, 31, 165, 0, 0, 0, '2019/09/1567583891-scpng', 'sc.png', 1, 0),
(643, 31, 173, 0, 0, 0, '2019/09/1567584093-kppng', 'kp.png', 1, 0),
(644, 31, 175, 0, 0, 0, '2019/09/1567584183-espng', 'es.png', 1, 0),
(645, 31, 176, 0, 0, 0, '2019/09/1567584211-lkpng', 'lk.png', 1, 0),
(646, 31, 177, 0, 0, 0, '2019/09/1567584236-sdpng', 'sd.png', 1, 0),
(647, 31, 178, 0, 0, 0, '2019/09/1567584313-srpng', 'sr.png', 1, 0),
(648, 31, 179, 0, 0, 0, '2019/09/1567584339-sepng', 'se.png', 1, 0),
(649, 31, 181, 0, 0, 0, '2019/09/1567584413-sypng', 'sy.png', 1, 0),
(650, 31, 182, 0, 0, 0, '2019/09/1567584462-twpng', 'tw.png', 1, 0),
(651, 31, 183, 0, 0, 0, '2019/09/1567584482-tjpng', 'tj.png', 1, 0),
(652, 31, 186, 0, 0, 0, '2019/09/1567584565-tlpng', 'tl.png', 1, 0),
(653, 31, 189, 0, 0, 0, '2019/09/1567584626-ttpng', 'tt.png', 1, 0),
(654, 31, 193, 0, 0, 0, '2019/09/1567584699-tvpng', 'tv.png', 1, 0),
(655, 31, 194, 0, 0, 0, '2019/09/1567584720-ugpng', 'ug.png', 1, 0),
(656, 31, 195, 0, 0, 0, '2019/09/1567584737-uapng', 'ua.png', 1, 0),
(657, 31, 196, 0, 0, 0, '2019/09/1567584764-aepng', 'ae.png', 1, 0),
(658, 31, 197, 0, 0, 0, '2019/09/1567584789-gbpng', 'gb.png', 1, 0),
(659, 31, 198, 0, 0, 0, '2019/09/1567584808-uspng', 'us.png', 1, 0),
(660, 31, 199, 0, 0, 0, '2019/09/1567584830-uypng', 'uy.png', 1, 0),
(661, 31, 202, 0, 0, 0, '2019/09/1567584906-vapng', 'va.png', 1, 0),
(662, 31, 204, 0, 0, 0, '2019/09/1567584952-vnpng', 'vn.png', 1, 0),
(663, 31, 42, 0, 0, 0, '2019/09/1567585104-cmpng', 'cm.png', 1, 0),
(664, 31, 47, 0, 0, 0, '2019/09/1567585172-tdpng', 'td.png', 1, 0),
(665, 31, 57, 0, 0, 0, '2019/09/1567585273-hrpng', 'hr.png', 1, 0),
(666, 31, 58, 0, 0, 0, '2019/09/1567585303-cupng', 'cu.png', 1, 0),
(667, 31, 71, 0, 0, 0, '2019/09/1567585600-szpng', 'sz.png', 1, 0),
(668, 31, 5, 0, 0, 0, '2019/09/1567588829-lkpng', 'lk.png', 1, 0),
(669, 31, 18, 0, 0, 0, '2019/09/1567589069-atpng', 'at.png', 1, 0),
(670, 31, 25, 0, 0, 0, '2019/09/1567589144-bepng', 'be.png', 1, 0),
(671, 31, 27, 0, 0, 0, '2019/09/1567589183-bjpng', 'bj.png', 1, 0),
(672, 31, 35, 0, 0, 0, '2019/09/1567589328-vgpng', 'vg.png', 1, 0),
(673, 31, 38, 0, 0, 0, '2019/09/1567591412-bfpng', 'bf.png', 1, 0),
(674, 31, 41, 0, 0, 0, '2019/09/1567591495-khpng', 'kh.png', 1, 0),
(675, 31, 83, 0, 0, 0, '2019/09/1567591569-gtpng', 'gt.png', 1, 0),
(676, 31, 46, 0, 0, 0, '2019/09/1567591689-cfpng', 'cf.png', 1, 0),
(677, 31, 54, 0, 0, 0, '2019/09/1567591849-cgpng', 'cg.png', 1, 0),
(678, 31, 52, 0, 0, 0, '2019/09/1567591861-kmpng', 'km.png', 1, 0),
(679, 31, 55, 0, 0, 0, '2019/09/1567591886-crpng', 'cr.png', 1, 0),
(680, 31, 56, 0, 0, 0, '2019/09/1567591909-cipng', 'ci.png', 1, 0),
(681, 31, 63, 0, 0, 0, '2019/09/1567592026-dmpng', 'dm.png', 1, 0),
(682, 31, 64, 0, 0, 0, '2019/09/1567592046-dopng', 'do.png', 1, 0),
(683, 31, 158, 0, 0, 0, '2019/09/1567592136-vcpng', 'vc.png', 1, 0),
(684, 31, 67, 0, 0, 0, '2019/09/1567592181-svpng', 'sv.png', 1, 0),
(685, 31, 68, 0, 0, 0, '2019/09/1567592198-gqpng', 'gq.png', 1, 0),
(686, 31, 70, 0, 0, 0, '2019/09/1567592258-eepng', 'ee.png', 1, 0),
(687, 31, 102, 0, 0, 0, '2019/09/1567592356-kepng', 'ke.png', 1, 0),
(688, 31, 206, 0, 0, 0, '2019/09/1567592417-zwpng', 'zw.png', 1, 0),
(689, 31, 205, 0, 0, 0, '2019/09/1567592443-zmpng', 'zm.png', 1, 0),
(690, 31, 203, 0, 0, 0, '2019/09/1567592508-vepng', 've.png', 1, 0),
(691, 31, 201, 0, 0, 0, '2019/09/1567592555-vupng', 'vu.png', 1, 0),
(692, 31, 156, 0, 0, 0, '2019/09/1567595785-knpng', 'kn.png', 1, 0),
(693, 31, 168, 0, 0, 0, '2019/09/1567595825-skpng', 'sk.png', 1, 0),
(694, 31, 72, 0, 0, 0, '2019/09/1567595901-etpng', 'et.png', 1, 0),
(695, 31, 73, 0, 0, 0, '2019/09/1567598365-fjpng', 'fj.png', 1, 0),
(696, 31, 74, 0, 0, 0, '2019/09/1567598386-fipng', 'fi.png', 1, 0),
(697, 31, 76, 0, 0, 0, '2019/09/1567598629-gapng', 'ga.png', 1, 0),
(698, 31, 77, 0, 0, 0, '2019/09/1567598660-gmpng', 'gm.png', 1, 0),
(699, 31, 78, 0, 0, 0, '2019/09/1567598679-gepng', 'ge.png', 1, 0),
(700, 31, 79, 0, 0, 0, '2019/09/1567598695-depng', 'de.png', 1, 0),
(701, 31, 80, 0, 0, 0, '2019/09/1567598720-ghpng', 'gh.png', 1, 0),
(702, 31, 84, 0, 0, 0, '2019/09/1567598983-gnpng', 'gn.png', 1, 0),
(703, 31, 85, 0, 0, 0, '2019/09/1567598993-gwpng', 'gw.png', 1, 0),
(704, 31, 185, 0, 0, 0, '2019/09/1567599305-thpng', 'th.png', 1, 0),
(705, 31, 87, 0, 0, 0, '2019/09/1567599344-htpng', 'ht.png', 1, 0),
(706, 31, 88, 0, 0, 0, '2019/09/1567599620-hnpng', 'hn.png', 1, 0),
(707, 31, 187, 0, 0, 0, '2019/09/1567599722-tgpng', 'tg.png', 1, 0),
(708, 31, 188, 0, 0, 0, '2019/09/1567599744-topng', 'to.png', 1, 0),
(709, 31, 190, 0, 0, 0, '2019/09/1567599772-tnpng', 'tn.png', 1, 0),
(710, 31, 90, 0, 0, 0, '2019/09/1567599803-ispng', 'is.png', 1, 0),
(711, 31, 95, 0, 0, 0, '2019/09/1567599906-iepng', 'ie.png', 1, 0),
(712, 31, 96, 0, 0, 0, '2019/09/1567599930-ilpng', 'il.png', 1, 0),
(713, 31, 100, 0, 0, 0, '2019/09/1567599968-jopng', 'jo.png', 1, 0),
(714, 31, 103, 0, 0, 0, '2019/09/1567600007-kipng', 'ki.png', 1, 0),
(715, 31, 110, 0, 0, 0, '2019/09/1567600174-lspng', 'ls.png', 1, 0),
(716, 31, 112, 0, 0, 0, '2019/09/1567600212-lipng', 'li.png', 1, 0),
(717, 31, 113, 0, 0, 0, '2019/09/1567600241-lupng', 'lu.png', 1, 0),
(718, 31, 122, 0, 0, 0, '2019/09/1567600367-mrpng', 'mr.png', 1, 0),
(719, 31, 128, 0, 0, 0, '2019/09/1567600394-mnpng', 'mn.png', 1, 0),
(720, 31, 114, 0, 0, 0, '2019/09/1567600421-mgpng', 'mg.png', 1, 0),
(721, 31, 115, 0, 0, 0, '2019/09/1567600480-mypng', 'my.png', 1, 0),
(722, 31, 116, 0, 0, 0, '2019/09/1567600508-mwpng', 'mw.png', 1, 0),
(723, 31, 119, 0, 0, 0, '2019/09/1567600547-mlpng', 'ml.png', 1, 0),
(724, 31, 121, 0, 0, 0, '2019/09/1567600576-mhpng', 'mh.png', 1, 0),
(725, 31, 125, 0, 0, 0, '2019/09/1567600626-fmpng', 'fm.png', 1, 0),
(726, 31, 126, 0, 0, 0, '2019/09/1567600664-mdpng', 'md.png', 1, 0),
(727, 31, 127, 0, 0, 0, '2019/09/1567600689-mcpng', 'mc.png', 1, 0),
(728, 31, 130, 0, 0, 0, '2019/09/1567600723-mapng', 'ma.png', 1, 0),
(729, 31, 131, 0, 0, 0, '2019/09/1567600745-mmpng', 'mm.png', 1, 0),
(730, 31, 133, 0, 0, 0, '2019/09/1567600785-nrpng', 'nr.png', 1, 0),
(731, 31, 132, 0, 0, 0, '2019/09/1567600814-napng', 'na.png', 1, 0),
(732, 31, 135, 0, 0, 0, '2019/09/1567600847-nlpng', 'nl.png', 1, 0),
(733, 31, 136, 0, 0, 0, '2019/09/1567600919-nzpng', 'nz.png', 1, 0),
(734, 31, 137, 0, 0, 0, '2019/09/1567600963-nipng', 'ni.png', 1, 0),
(735, 31, 138, 0, 0, 0, '2019/09/1567601035-nepng', 'ne.png', 1, 0),
(736, 31, 192, 0, 0, 0, '2019/09/1567601120-tmpng', 'tm.png', 1, 0),
(737, 31, 139, 0, 0, 0, '2019/09/1567601150-mkpng', 'mk.png', 1, 0),
(738, 31, 140, 0, 0, 0, '2019/09/1567601190-nopng', 'no.png', 1, 0),
(739, 31, 143, 0, 0, 0, '2019/09/1567601233-pwpng', 'pw.png', 1, 0),
(740, 31, 146, 0, 0, 0, '2019/09/1567601380-pgpng', 'pg.png', 1, 0),
(741, 31, 148, 0, 0, 0, '2019/09/1567601416-pepng', 'pe.png', 1, 0),
(742, 31, 149, 0, 0, 0, '2019/09/1567601446-phpng', 'ph.png', 1, 0),
(743, 31, 150, 0, 0, 0, '2019/09/1567601471-plpng', 'pl.png', 1, 0),
(744, 31, 153, 0, 0, 0, '2019/09/1567601510-ropng', 'ro.png', 1, 0),
(745, 31, 154, 0, 0, 0, '2019/09/1567601539-rupng', 'ru.png', 1, 0),
(746, 31, 157, 0, 0, 0, '2019/09/1567601577-lcpng', 'lc.png', 1, 0),
(747, 31, 159, 0, 0, 0, '2019/09/1567601611-wspng', 'ws.png', 1, 0),
(748, 31, 160, 0, 0, 0, '2019/09/1567601640-smpng', 'sm.png', 1, 0),
(749, 31, 166, 0, 0, 0, '2019/09/1567601750-slpng', 'sl.png', 1, 0),
(750, 31, 162, 0, 0, 0, '2019/09/1567601776-sapng', 'sa.png', 1, 0),
(751, 31, 163, 0, 0, 0, '2019/09/1567601849-snpng', 'sn.png', 1, 0),
(752, 31, 167, 0, 0, 0, '2019/09/1567601947-sgpng', 'sg.png', 1, 0),
(753, 31, 170, 0, 0, 0, '2019/09/1567601982-sbpng', 'sb.png', 1, 0),
(754, 31, 171, 0, 0, 0, '2019/09/1567602004-sopng', 'so.png', 1, 0),
(755, 31, 172, 0, 0, 0, '2019/09/1567602038-zapng', 'za.png', 1, 0),
(756, 31, 180, 0, 0, 0, '2019/09/1567602208-chpng', 'ch.png', 1, 0),
(757, 31, 184, 0, 0, 0, '2019/09/1567602280-tzpng', 'tz.png', 1, 0),
(758, 31, 191, 0, 0, 0, '2019/09/1567602361-trpng', 'tr.png', 1, 0),
(759, 31, 200, 0, 0, 0, '2019/09/1567602505-uzpng', 'uz.png', 1, 0),
(797, 8, 1, 0, 0, 0, '2019/09/1568288155-facebookpng', 'facebook.png', 1, 0),
(798, 8, 2, 0, 0, 0, '2019/09/1568288168-twitterpng', 'twitter.png', 1, 0),
(800, 8, 4, 0, 0, 0, '2019/09/1568288294-pinterestpng', 'pinterest.png', 1, 0),
(801, 8, 5, 0, 0, 0, '2019/09/1568288303-youtubepng', 'youtube.png', 1, 0),
(802, 8, 3, 0, 0, 0, '2019/09/1568288458-instagrampng', 'instagram.png', 1, 0),
(827, 14, 0, 0, 1, 0, '2019/09/1568608719-finalpng', 'final.png', 1, 0),
(828, 9, 7, 0, 0, 0, '2019/09/1568700156-whyus75X7102png', 'why-us-75X71_02.png', 1, 0),
(829, 9, 8, 0, 0, 0, '2019/09/1568700184-whyus75X7101png', 'why-us-75X71_01.png', 2, 0),
(830, 9, 9, 0, 0, 0, '2019/09/1568700204-whyus75X7103png', 'why-us-75X71_03.png', 2, 0),
(834, 10, 0, 0, 1, 0, '2019/09/1568810917-logoiconpng', 'logo-icon.png', 1, 0),
(835, 37, 0, 0, 1, 0, '2019/09/1568810928-logoiconwhitepng', 'logo-icon-white.png', 4, 0),
(836, 6, 0, 0, 1, 0, '2019/09/1568810936-logoiconpng', 'logo-icon.png', 1, 0),
(837, 11, 0, 0, 1, 0, '2019/09/1568810947-logoiconpng', 'logo-icon.png', 1, 0),
(864, 10, 0, 0, 2, 0, '2019/09/1568873497-logoiconpng', 'logo-icon.png', 1, 0),
(865, 6, 0, 0, 2, 0, '2019/09/1568873503-logoiconpng', 'logo-icon.png', 1, 0),
(866, 37, 0, 0, 2, 0, '2019/09/1568873511-logoiconwhitepng', 'logo-icon-white.png', 1, 0),
(867, 11, 0, 0, 2, 0, '2019/09/1568873517-logoiconpng', 'logo-icon.png', 1, 0),
(868, 14, 0, 0, 2, 0, '2019/09/1568873526-logoiconpng', 'logo-icon.png', 1, 0),
(869, 12, 0, 0, 2, 0, '2019/09/1568873603-favicon96x96png', 'favicon-96x96.png', 1, 0),
(870, 18, 0, 0, 2, 0, '2019/09/1568873616-appleicon72x72png', 'apple-icon-72x72.png', 1, 0),
(872, 12, 0, 0, 1, 0, '2019/09/1568873647-favicon96x96png', 'favicon-96x96.png', 1, 0),
(880, 7, 5, 0, 0, 1, '2019/09/1568885027-banner3jpg', 'banner-3.jpg', 1, 0),
(881, 7, 5, 0, 1, 1, '2019/09/1568885033-banner3jpg', 'banner-3.jpg', 1, 0),
(882, 7, 5, 0, 2, 1, '2019/09/1568885041-banner3jpg', 'banner-3.jpg', 1, 0),
(893, 7, 8, 0, 0, 1, '2019/09/1568886787-banner6jpg', 'banner-6.jpg', 1, 0),
(894, 7, 8, 0, 1, 1, '2019/09/1568886791-banner6jpg', 'banner-6.jpg', 1, 0),
(895, 7, 8, 0, 2, 1, '2019/09/1568886795-banner6jpg', 'banner-6.jpg', 1, 0),
(896, 7, 8, 0, 2, 2, '2019/09/1568886799-banner6jpg', 'banner-6.jpg', 2, 0),
(897, 7, 8, 0, 2, 3, '2019/09/1568886802-banner6jpg', 'banner-6.jpg', 3, 0),
(898, 7, 7, 0, 0, 1, '2019/09/1568887036-banner5jpg', 'banner-5.jpg', 4, 0),
(899, 7, 7, 0, 1, 1, '2019/09/1568887041-banner5jpg', 'banner-5.jpg', 2, 0),
(900, 7, 7, 0, 2, 1, '2019/09/1568887049-banner5jpg', 'banner-5.jpg', 2, 0),
(901, 7, 7, 0, 0, 2, '2019/09/1568887054-banner5jpg', 'banner-5.jpg', 5, 0),
(902, 7, 7, 0, 0, 3, '2019/09/1568887059-banner5jpg', 'banner-5.jpg', 6, 0),
(918, 9, 4, 0, 0, 0, '2019/09/1569246986-tab1imagejpg', 'tab1-image.jpg', 5, 0),
(919, 9, 5, 0, 0, 0, '2019/09/1569247000-tab2imagejpg', 'tab2-image.jpg', 5, 0),
(920, 9, 6, 0, 0, 0, '2019/09/1569247016-tab3imagejpg', 'tab3-image.jpg', 7, 0),
(921, 27, 1, 0, 1, 0, '2020/07/1596111359-1jpg', '1.jpg', 1, 0),
(922, 27, 1, 0, 2, 0, '2020/07/1596111369-1jpg', '1.jpg', 1, 0),
(923, 27, 7, 0, 1, 0, '2020/07/1596111464-1jpg', '1.jpg', 1, 0),
(924, 27, 7, 0, 2, 0, '2020/07/1596111472-1jpg', '1.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banners`
--

CREATE TABLE `tbl_banners` (
  `banner_id` int(11) NOT NULL,
  `banner_blocation_id` int(11) NOT NULL,
  `banner_url` varchar(255) NOT NULL,
  `banner_target` varchar(100) NOT NULL,
  `banner_added_on` datetime NOT NULL,
  `banner_active` tinyint(1) NOT NULL,
  `banner_deleted` tinyint(1) NOT NULL,
  `banner_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_banners`
--

INSERT INTO `tbl_banners` (`banner_id`, `banner_blocation_id`, `banner_url`, `banner_target`, `banner_added_on`, `banner_active`, `banner_deleted`, `banner_display_order`) VALUES
(1, 1, 'https://www.google.co.in', '_self', '0000-00-00 00:00:00', 1, 0, 1234567891),
(2, 1, 'https://www.google.co.in', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(4, 3, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(5, 3, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(6, 3, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(7, 2, 'https://www.google.com', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(8, 2, '', '_self', '0000-00-00 00:00:00', 1, 0, 0),
(9, 2, '', '_self', '0000-00-00 00:00:00', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banners_lang`
--

CREATE TABLE `tbl_banners_lang` (
  `bannerlang_banner_id` int(11) NOT NULL,
  `bannerlang_lang_id` int(11) NOT NULL,
  `banner_title` varchar(255) NOT NULL,
  `banner_description` text NOT NULL,
  `banner_btn_caption` varchar(255) NOT NULL,
  `banner_btn_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_banners_lang`
--

INSERT INTO `tbl_banners_lang` (`bannerlang_banner_id`, `bannerlang_lang_id`, `banner_title`, `banner_description`, `banner_btn_caption`, `banner_btn_url`) VALUES
(1, 1, 'A conversational  curriculum.', 'Conversations are the heart and soul of the yoCoach experience. We believe the most effective way to learn a language is to speak it with teachers. It’s our mission to connect you with teachers who are a perfect fit for your learning style.', '', ''),
(1, 2, 'Shop Now', '', '', ''),
(2, 1, 'Teachers are better  than software.', 'All yoCoach language teachers are native speakers who have been selected to join our roster of instructors. You can browse hundreds of yoCoach teacher profiles and schedule unlimited trial lessons to find the right one for you.', '', ''),
(2, 2, 'Banner', '', '', ''),
(4, 1, 'Browse', 'Browse thousands of teacher', '', ''),
(4, 2, 'Browse', 'Browse thousands of teacher', '', ''),
(5, 1, 'Book', 'Book lessons with the best teachers for your', '', ''),
(5, 2, 'Book', 'Book lessons with the best teachers for your', '', ''),
(6, 1, 'Start', 'Log in to yoCoach Video and start talking.', '', ''),
(6, 2, 'Start', 'Log in to yoCoach Video and start talking.', '', ''),
(7, 1, 'Online Learn Courses Management', 'Contrary to popular belief, Lorem Ipsum is not simply random text.', '', ''),
(8, 1, 'Teachers are better than software', 'Contrary to popular belief, Lorem Ipsum is not simply random text.', '', ''),
(9, 1, 'A conversational curriculum', 'Contrary to popular belief, Lorem Ipsum is not simply random text.', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner_locations`
--

CREATE TABLE `tbl_banner_locations` (
  `blocation_id` int(11) NOT NULL,
  `blocation_key` varchar(150) NOT NULL,
  `blocation_identifier` varchar(255) NOT NULL,
  `blocation_banner_width` decimal(13,2) NOT NULL,
  `blocation_banner_height` decimal(13,2) NOT NULL,
  `blocation_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_banner_locations`
--

INSERT INTO `tbl_banner_locations` (`blocation_id`, `blocation_key`, `blocation_identifier`, `blocation_banner_width`, `blocation_banner_height`, `blocation_active`) VALUES
(2, 'BLOCK_SECOND_AFTER_HOMESLIDER', 'Block Second after Hompage Slider', '800.00', '500.00', 1),
(3, 'BLOCK_HOW_IT_WORKS', 'How It Works', '800.00', '500.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner_locations_lang`
--

CREATE TABLE `tbl_banner_locations_lang` (
  `blocationlang_blocation_id` int(11) NOT NULL,
  `blocationlang_lang_id` int(11) NOT NULL,
  `blocation_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `tbl_bible_content` (
  `biblecontent_id` int(11) NOT NULL,
  `biblecontent_title` varchar(250) NOT NULL,
  `biblecontent_type` int(11) NOT NULL,
  `biblecontent_url` varchar(250) NOT NULL,
  `biblecontent_display_order` int(11) NOT NULL,
  `biblecontent_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

INSERT INTO `tbl_bible_content` (`biblecontent_id`, `biblecontent_title`, `biblecontent_type`, `biblecontent_url`, `biblecontent_display_order`, `biblecontent_active`) VALUES
(1, 'Start Your Entrepreneurship Journey in E-Learning Business', 0, 'https://www.youtube.com/embed/nGM8mY22eec', 0, 1);


--
-- Table structure for table `tbl_bible_content_lang`
--

CREATE TABLE `tbl_bible_content_lang` (
  `biblecontentlang_biblecontent_id` int(11) NOT NULL,
  `biblecontentlang_lang_id` int(11) NOT NULL,
  `biblecontentlang_biblecontent_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

INSERT INTO `tbl_bible_content_lang` (`biblecontentlang_biblecontent_id`, `biblecontentlang_lang_id`, `biblecontentlang_biblecontent_title`) VALUES
(1, 1, 'Start Your Entrepreneurship Journey in E-Learning Business'),
(1, 2, 'ابدأ رحلتك في ريادة الأعمال في مجال التعلم الإلكتروني');

--
-- Table structure for table `tbl_blog_contributions`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_categories`
--

CREATE TABLE `tbl_blog_post_categories` (
  `bpcategory_id` int(11) NOT NULL,
  `bpcategory_identifier` varchar(200) NOT NULL,
  `bpcategory_parent` int(11) NOT NULL,
  `bpcategory_display_order` int(11) NOT NULL,
  `bpcategory_featured` tinyint(1) NOT NULL,
  `bpcategory_active` tinyint(1) NOT NULL,
  `bpcategory_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_categories_lang`
--

CREATE TABLE `tbl_blog_post_categories_lang` (
  `bpcategorylang_bpcategory_id` int(11) NOT NULL,
  `bpcategorylang_lang_id` int(11) NOT NULL,
  `bpcategory_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_comments`
--

CREATE TABLE `tbl_blog_post_comments` (
  `bpcomment_id` int(11) NOT NULL,
  `bpcomment_post_id` int(11) NOT NULL,
  `bpcomment_user_id` int(11) NOT NULL,
  `bpcomment_author_name` varchar(150) NOT NULL,
  `bpcomment_author_email` varchar(255) NOT NULL,
  `bpcomment_content` mediumtext NOT NULL,
  `bpcomment_approved` tinyint(1) NOT NULL,
  `bpcomment_deleted` tinyint(1) NOT NULL,
  `bpcomment_added_on` datetime NOT NULL,
  `bpcomment_user_ip` varchar(20) NOT NULL,
  `bpcomment_user_agent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_lang`
--

CREATE TABLE `tbl_blog_post_lang` (
  `postlang_post_id` int(11) NOT NULL,
  `postlang_lang_id` int(11) NOT NULL,
  `post_author_name` varchar(100) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_short_description` mediumtext NOT NULL,
  `post_description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blog_post_to_category`
--

CREATE TABLE `tbl_blog_post_to_category` (
  `ptc_bpcategory_id` int(11) NOT NULL,
  `ptc_post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_settings`
--

CREATE TABLE `tbl_commission_settings` (
  `commsetting_id` int(11) NOT NULL,
  `commsetting_user_id` int(11) NOT NULL,
  `commsetting_fees` decimal(10,2) NOT NULL COMMENT 'in %',
  `commsetting_is_mandatory` tinyint(1) NOT NULL,
  `commsetting_is_grpcls` tinyint(4) NOT NULL,
  `commsetting_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_commission_settings`
--

INSERT INTO `tbl_commission_settings` (`commsetting_id`, `commsetting_user_id`, `commsetting_fees`, `commsetting_is_mandatory`, `commsetting_is_grpcls`, `commsetting_deleted`) VALUES
(4, 0, '12.00', 0, 0, 0),
(5, 0, '10.00', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_setting_history`
--

CREATE TABLE `tbl_commission_setting_history` (
  `csh_id` int(11) NOT NULL,
  `csh_commsetting_id` int(11) NOT NULL,
  `csh_commsetting_user_id` int(11) NOT NULL,
  `csh_commsetting_fees` decimal(10,2) NOT NULL COMMENT 'in %',
  `csh_commsetting_is_mandatory` tinyint(1) NOT NULL,
  `csh_commsetting_is_grpcls` tinyint(4) NOT NULL,
  `csh_commsetting_deleted` tinyint(1) NOT NULL,
  `csh_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_commission_setting_history`
--

INSERT INTO `tbl_commission_setting_history` (`csh_id`, `csh_commsetting_id`, `csh_commsetting_user_id`, `csh_commsetting_fees`, `csh_commsetting_is_mandatory`, `csh_commsetting_is_grpcls`, `csh_commsetting_deleted`, `csh_added_on`) VALUES
(9, 4, 0, '12.00', 0, 0, 0, '2019-09-04 12:13:34'),
(10, 5, 0, '10.00', 0, 0, 0, '2020-07-30 12:36:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_configurations`
--

CREATE TABLE `tbl_configurations` (
  `conf_name` varchar(50) NOT NULL,
  `conf_val` mediumtext NOT NULL,
  `conf_common` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_configurations`
--

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
('admin_logo', '', 0),
('apple_touch_icon', '', 0),
('CONF_ABOUT_US_PAGE', '1', 0),
('conf_activate_separate_signup_form', '1', 0),
('CONF_ADDITIONAL_ALERT_EMAILS', '', 0),
('CONF_ADDRESS_1', 'A-712, FATbit Technologies\r\nBestech Business Towers, India', 0),
('CONF_ADDRESS_2', 'A-712, FATbit Technologies\r\nBestech Business Towers, India', 0),
('CONF_ADMIN_APPROVAL_REGISTRATION', '0', 0),
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
('conf_auto_restore_on', '1', 0),
('CONF_AWEBER_SIGNUP_CODE', '', 0),
('conf_class_booking_gap', '60', 0),
('CONF_COMET_CHAT_API_KEY', '', 0),
('CONF_COMET_CHAT_APP_ID', '', 0),
('CONF_COMET_CHAT_AUTH', '', 0),
('CONF_COMMISSION_INCLUDING_TAX', '0', 0),
('CONF_CONTACT_EMAIL', 'yoCoach@dummyid.com', 0),
('CONF_COOKIES_BUTTON_LINK', '3', 0),
('CONF_COOKIES_TEXT_1', 'Cookies Policy Text Will go here...', 0),
('CONF_COOKIES_TEXT_2', 'Cookies Policy Text Will go here...', 0),
('CONF_COUNTRY', '6', 0),
('CONF_CURRENCY', '1', 0),
('CONF_DATEPICKER_FORMAT', 'Y-m-d', 0),
('CONF_DATEPICKER_FORMAT_TIME', 'H:i', 0),
('CONF_DATE_FORMAT', 'Y-m-d', 1),
('CONF_DATE_FORMAT_TIME', 'H:i:s', 1),
('CONF_DEFAULT_ORDER_STATUS', '1', 0),
('CONF_DEFAULT_PAID_ORDER_STATUS', '2', 0),
('CONF_DEFAULT_REVIEW_STATUS', '0', 0),
('CONF_DEFAULT_SITE_LANG', '1', 0),
('CONF_EMAIL_VERIFICATION_REGISTRATION', '1', 0),
('CONF_ENABLE_COOKIES', '1', 0),
('conf_enable_import_export', '0', 0),
('conf_enable_livechat', '', 0),
('CONF_ENABLE_NEWSLETTER_SUBSCRIPTION', '', 0),
('conf_enable_referrer_module', '1', 0),
('CONF_FACEBOOK_APP_ID', '', 0),
('CONF_FACEBOOK_APP_SECRET', '', 0),
('CONF_FROM_EMAIL', 'yocoach@dummyid.com', 0),
('CONF_FROM_NAME_1', 'yoCoach', 0),
('CONF_FRONTEND_PAGESIZE', '10', 0),
('CONF_GOOGLEMAP_API_KEY', '', 0),
('CONF_GOOGLEPLUS_CLIENT_ID', '', 0),
('CONF_GOOGLEPLUS_CLIENT_SECRET', '', 0),
('CONF_GOOGLEPLUS_DEVELOPER_KEY', '', 0),
('CONF_GOOGLE_PUSH_NOTIFICATION_API_KEY', '', 0),
('conf_items_per_page_catalog', '10', 0),
('CONF_LEARNER_REFUND_PERCENTAGE', '50', 0),
('CONF_MAILCHIMP_KEY', '', 0),
('CONF_MAILCHIMP_LIST_ID', '', 0),
('CONF_MAINTENANCE', '0', 0),
('conf_maintenance_text_1', '', 0),
('CONF_MAX_COMMISSION', '15', 0),
('conf_max_teacher_request_attempt', '3', 0),
('CONF_MIN_INTERVAL_WITHDRAW_REQUESTS', '0', 0),
('CONF_MIN_WITHDRAW_LIMIT', '25', 0),
('CONF_NEWSLETTER_SYSTEM', '', 0),
('conf_notify_admin_registration', '1', 0),
('conf_page_size', '10', 0),
('conf_paid_lesson_duration', '60', 0),
('conf_ppc_slides_home_page', '4', 0),
('CONF_PRIVACY_POLICY_PAGE', '3', 0),
('CONF_RECAPTCHA_SECRETKEY', '', 0),
('CONF_RECAPTCHA_SITEKEY', '', 0),
('CONF_REPLY_TO_EMAIL', 'yocoach@dummyid.com', 0),
('CONF_REVIEW_ALERT_EMAIL', '1', 0),
('CONF_SEND_EMAIL', '0', 0),
('CONF_SEND_SMTP_EMAIL', '0', 0),
('CONF_SITE_FAX', '', 0),
('CONF_SITE_OWNER_1', '', 0),
('CONF_SITE_OWNER_2', '', 0),
('CONF_SITE_OWNER_EMAIL', 'yocoach@dummyid.com', 0),
('CONF_SITE_PHONE', '+ 9239939239923', 0),
('CONF_SITE_TRACKER_CODE', '', 0),
('CONF_SMTP_HOST', '', 0),
('CONF_SMTP_PASSWORD', 'welcome', 0),
('CONF_SMTP_PORT', '', 0),
('CONF_SMTP_SECURE', 'tls', 0),
('CONF_SMTP_USERNAME', 'welcome', 0),
('CONF_TEACHER_NO_OF_LESSON', '1,3,5,10,15,20,25', 0),
('CONF_TERMS_AND_CONDITIONS_PAGE', '2', 0),
('CONF_TIMEZONE', 'UTC', 0),
('conf_time_auto_close_system_messages', '3', 0),
('conf_total_slides_home_page', '4', 0),
('CONF_TRANSACTION_MODE', '0', 0),
('conf_trial_lesson_duration', '30', 0),
('CONF_TWITTER_API_KEY', '', 0),
('CONF_TWITTER_API_SECRET', '', 0),
('CONF_TWITTER_USERNAME', '', 0),
('CONF_USE_SSL', '0', 0),
('CONF_WEBSITE_NAME_1', 'Yo!Coach', 0),
('CONF_WEBSITE_NAME_2', '', 0),
('CONF_WELCOME_EMAIL_REGISTRATION', '1', 0),
('CONF_YOCOACH_VERSION', 'V2.0', 0),
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
(4, 'test page', 1, 1),
(5, 'Contact US', 2, 0),
(6, 'Help page', 2, 0),
(7, 'Apply Teach', 1, 0),
(8, 'Test2', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages_block_lang`
--

CREATE TABLE `tbl_content_pages_block_lang` (
  `cpblocklang_id` int(11) NOT NULL,
  `cpblocklang_lang_id` int(11) NOT NULL,
  `cpblocklang_cpage_id` int(11) NOT NULL,
  `cpblocklang_block_id` int(11) NOT NULL,
  `cpblocklang_text` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_content_pages_block_lang`
--

INSERT INTO `tbl_content_pages_block_lang` (`cpblocklang_id`, `cpblocklang_lang_id`, `cpblocklang_cpage_id`, `cpblocklang_block_id`, `cpblocklang_text`) VALUES
(1, 1, 1, 1, '<section class=\"section section--white section--centered\">\r\n    <div class=\"container container--narrow container--cms\">\r\n        <div class=\"section__body\" style=\"\">\r\n            <!--\r\n            ------ First Section -------\r\n            -->\r\n            \r\n            <div class=\"row justify-content-center -align-center\">\r\n                <div class=\"col-xl-10 col-lg-12 col-md-12\">\r\n                    <div class=\"row\">\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <div class=\"icon\"><img src=\"/public/images/icon_mission.svg\" alt=\"\" /></div>\r\n                            <h4>The Mission</h4>\r\n                            <p>Nulla ornare euismod blandit. Quisque metus turpis, sollicitudin eget pellentesque sit amet, sagittis non dui. Sed convallis et nisl eget molestie. Vestibulum quis leo purus. Nunc iaculis placerat enim non tempus. </p></div>\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <div class=\"icon\"><img src=\"/public/images/icon_vision.svg\" alt=\"\" /></div>\r\n                            <h4>The Vision</h4>\r\n                            <p>Nulla ornare euismod blandit. Quisque metus turpis, sollicitudin eget pellentesque sit amet, sagittis non dui. Sed convallis et nisl eget molestie. Vestibulum quis leo purus. Nunc iaculis placerat enim non tempus.</p></div></div><span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span>\r\n                    <div class=\"-align-center section__head\">\r\n                        <h2>The Yocoach</h2></div>\r\n                    <div class=\"row\">\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <p style=\"text-align:left;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin ante vel nisl mollis, vel viverra ipsum molestie. Nullam vehicula eros magna, id pharetra diam fringilla sit amet. Sed id lectus quis diam facilisis mollis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut convallis nec ante id dictum. Nunc rutrum ultrices dolor sit amet dignissim. Donec commodo turpis quis justo interdum egestas. Phasellus vestibulum aliquet neque eget scelerisque. Integer lacus orci, faucibus a nunc sed, rutrum iaculis tortor. Etiam sit amet massa sapien. In tincidunt sem at lorem viverra rutrum. Ut erat metus, mattis vel venenatis at, sodales et libero. Suspendisse consequat congue pretium. Morbi a eros fermentum, maximus neque in, egestas lectus. Nunc aliquam ante erat, vitae efficitur dui aliquam sit amet.</p></div>\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <p style=\"text-align:left;\">Ut lacus nisi, pulvinar eu tortor non, consectetur sollicitudin ante. Etiam maximus, neque a fermentum porta, mauris est consequat quam, eu fermentum quam arcu et nulla. Nullam ipsum turpis, lobortis lobortis cursus a, malesuada id nulla. Proin vitae pellentesque enim. Morbi quis viverra ante. Etiam malesuada, nisi eu hendrerit varius, nunc mi condimentum mi, eget lacinia sapien eros at erat. Aliquam pretium mattis erat, ultricies malesuada mi luctus eget. Aliquam in dignissim mi. Curabitur quam ante, feugiat sit amet commodo eu, tincidunt ut nibh. Ut ultricies velit non nibh lacinia dignissim.</p></div></div></div></div>\r\n            <!--\r\n            ------------\r\n            -->\r\n            </div></div></section>\r\n<section class=\"section section--grey -align-center\">\r\n    <div class=\"container container--narrow\">\r\n        <div class=\"section__head\">\r\n            <h2>The Team</h2></div>\r\n        <div class=\"section__body\">\r\n            <div class=\"row justify-content-center\">\r\n                <div class=\"col-xl-9 col-lg-12\">\r\n                    <div class=\"row\">\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_2.jpg\" alt=\"\" /></div>\r\n                            <h4>Kirstin</h4>\r\n                            <p>Customer Executive</p></div>\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_5.jpg\" alt=\"\" /></div>\r\n                            <h4>Cooper</h4>\r\n                            <p>Product Design</p></div>\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_4.jpg\" alt=\"\" /></div>\r\n                            <h4>Andrew</h4>\r\n                            <p>Marketing</p></div>\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_3.jpg\" alt=\"\" /></div>\r\n                            <h4>Mikael</h4>\r\n                            <p>Product Developer</p></div></div></div></div></div></div></section>\r\n<section class=\"section section--white section--hiw\">\r\n    <div class=\"container container--narrow\">\r\n        <div class=\"section-title\">\r\n            <h2>How It Works</h2></div>\r\n        <div class=\"row justify-content-between\">\r\n            <div class=\"col-xl-4 col-lg-5 col-md-12 col-sm-12\">\r\n                <div class=\"tabs-vertical tabs-js\">\r\n                    <ul>\r\n                        <li class=\"is-active\" data-href=\"#tab1\">\r\n                            <div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n                                <div class=\"tab-info\">\r\n                                    <h3>Browse</h3> \r\n                                    <p>Browse through hundreds of teachers.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n                        <li class=\"\" data-href=\"#tab2\">\r\n                            <div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n                                <div class=\"tab-info\">\r\n                                    <h3>Book</h3> \r\n                                    <p>Book lessons with the best teacher for you.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n                        <li class=\"\" data-href=\"#tab3\">\r\n                            <div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n                                <div class=\"tab-info\">\r\n                                    <h3>Start</h3> \r\n                                    <p>Log in to YoCoach and start learning.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n                    </ul></div></div>\r\n            <div class=\"col-xl-7 col-lg-7 col-md-12 col-sm-12 col__content\">\r\n                <div id=\"tab1\" class=\"tabs-content-js\" style=\"display: block;\">\r\n                    <div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/4/0/3\" alt=\"\" /></a></div></div>\r\n                <div id=\"tab2\" class=\"tabs-content-js\" style=\"display: none;\">\r\n                    <div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/5/0/3\" alt=\"\" /></a></div></div>\r\n                <div id=\"tab3\" class=\"tabs-content-js\" style=\"display: none;\">\r\n                    <div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/6/0/3\" alt=\"\" /></a></div></div></div></div></div></section>\r\n<section class=\"section section--white\">             \r\n    <div class=\"container container--narrow -align-center\">                 \r\n        <h2 class=\"-style-bold\">Looking forward to meeting<br />\r\n              your new students?</h2>                 <span class=\"-gap\"></span>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>'),
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
(57, 1, 7, 1, '<section class=\"section section--white section--icons\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"-align-center section__head\">\r\n			<h2>Why Teach on yoCoach?</h2></div>\r\n		<div class=\"section__body\">\r\n			<div class=\"row justify-content-center\">\r\n				<div class=\"col-xl-9 col-lg-12 col-md-12\">\r\n					<div class=\"row\">\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_1.svg\" alt=\"\" /></div>\r\n							<h4>Earn money</h4>\r\n							<p>Set your own hourly rates and cash out your earnings anytime.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_2.svg\" alt=\"\" /></div>\r\n							<h4>Work anywhere</h4>\r\n							<p>Teach from home or any other convenient location of your choice.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_3.svg\" alt=\"\" /></div>\r\n							<h4>Teach anytime</h4>\r\n							<p>Adjust your personal availability anytime on your calendar.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/images/circle_icon_3.svg\" alt=\"\" /></div>\r\n							<h4>Safety and security</h4>\r\n							<p>Ensures that you get paid after you teach!</p>\r\n							<p>&nbsp;</p>\r\n							<p><br />\r\n								</p></div></div></div></div></div></div></section>'),
(58, 1, 7, 2, '<section class=\"section section--grey\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"-align-center section__head\">\r\n			<h2>Frequently Asked Questions</h2></div>\r\n		<div class=\"section__body\">\r\n			<div class=\"row justify-content-center\">\r\n				<div class=\"col-xl-9 col-lg-9\">\r\n					<div class=\"accordian-group\">\r\n						<div class=\"accordian accordian-js is-active\">\r\n							<div class=\"accordian__title accordian__title-js\">What is yoCoach?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: block;\">\r\n								<p>yoCoach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p></div></div>\r\n						<div class=\"accordian accordian-js\">\r\n							<div class=\"accordian__title accordian__title-js\">Where do I teach?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: none;\">\r\n								<p>You may teach from any location world wide, with high speed internet, a laptop and clear sounding headset.</p></div></div>\r\n						<div class=\"accordian accordian-js\">\r\n							<div class=\"accordian__title accordian__title-js\">How do I apply to teach on yoCoach?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: none;\">\r\n								<p>Click the link to apply and complete the application.  Upon completion you will be contacted when your profile is accepted.  If you do not hear from us within two weeks, feel free to email us at info@yoCoach.com</p></div></div>\r\n						<div class=\"accordian accordian-js\">\r\n							<div class=\"accordian__title accordian__title-js\">Do I need teaching experience?</div>\r\n							<div class=\"accordian__body accordian__body-js\" style=\"display: none;\">\r\n								<p>If you are educated with a college degree in any area we will consider your application.  No need to  be a expert teacher, but also we do not encourage a novice teacher, individuals looking for hobbies or just something to do.  We want passionate people willing to teach their native language as a foreign language to students around the world.  We encourage teachers to have degree\'s and or certified to teach their native spoken langauge as a foreign language.</p>\r\n								<p>If you do not have a college degree we will accept langauge ESL,TEFL and TESOL certifications.  Foreign languages other than English may submit their language certifications and college degree\'s. </p>\r\n								<p>We want to offer the opportunity for you to teach your language so please apply.  We will will vet each applicant to ensure that we have quality educated Teachers and Tutors.</p></div></div></div></div></div></div></div></section>'),
(59, 2, 7, 1, ''),
(60, 2, 7, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages_lang`
--

CREATE TABLE `tbl_content_pages_lang` (
  `cpagelang_cpage_id` int(11) NOT NULL,
  `cpagelang_lang_id` int(11) NOT NULL,
  `cpage_title` varchar(255) NOT NULL,
  `cpage_content` mediumtext NOT NULL,
  `cpage_image_title` varchar(255) NOT NULL,
  `cpage_image_content` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_content_pages_lang`
--

INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(1, 1, 'About Us', '<section class=\"banner banner--main\">             \r\n  \r\n	<div class=\"banner__media\"><img src=\"/public/images/2000x900_5.jpg\" alt=\"\" /></div>             \r\n	<div class=\"banner__content banner__content--centered\">                 \r\n		<h1>About yoCoach</h1>                 \r\n		<p>What is yoCoach? The best place to find an Online Language Teahcer</p>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--white section--centered\">             \r\n	<div class=\"container container--narrow container--cms\">                 \r\n                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-center -align-center\">                         \r\n				<div class=\"col-xl-10 col-lg-12 col-md-12\">                             \r\n					<div class=\"row\">                                 \r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                                     \r\n							<div class=\"icon\"><img src=\"/public/images/icon_mission.svg\" alt=\"\" /></div>                                     \r\n							<h4>The Mission</h4>                                     \r\n							<p>yoCoach’s mission is to empower people all over the world to \r\nbecome fluent in a foreign language. While our core team is based \r\nin San Francisco, the teachers and students who make our \r\nmission possible are  spread across six continents.</p>                                 </div>                                 \r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                                     \r\n							<div class=\"icon\"><img src=\"/public/images/icon_vision.svg\" alt=\"\" /></div>                                     \r\n							<h4>The Vision</h4>                                     \r\n							<p>Adipisicing elit, sed do eiusmod tempor incididunt ut labore \r\net dolore magna aliqua. Ut enim ad minim veniam, quis nostrud \r\nexercitation ullamco laboris nisi ut aliquip ex ea \r\ncommodo consequat. </p>                                     \r\n                                 </div>                            </div>                          </div>                     </div>                     \r\n                    <span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span>                     \r\n			<div class=\"-align-center section__head\">                         \r\n				<h2>The yoCoach</h2>                     </div>                     \r\n			<div class=\"row\">                         \r\n				<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                             \r\n					<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br />\r\n						<br />\r\n						Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam labore et dolore magnam aliquam quaerat voluptatem.<br />\r\n						<br />\r\n						Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>                         </div>                         \r\n				<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">                             \r\n					<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>                             \r\n                             \r\n					<ul>                                 \r\n						<li>Eeaque ipsa quae ab illo inventore veritatis et.</li>                                 \r\n						<li>Quasi architecto beatae vitae dicta sunt explicabo.  </li>                                 \r\n						<li>Eeaque ipsa quae ab illo inventore veritatis et. </li>                                 \r\n						<li> Quasi architecto beatae vitae dicta sunt explicabo. </li>                                 \r\n						<li>Eeaque ipsa quae ab illo inventore veritatis et. </li>                             \r\n					</ul>                             \r\n                         </div>                    </div>                     \r\n                   \r\n                 </div>                 \r\n             </div>         </section>         \r\n      \r\n         \r\n         \r\n         \r\n<section class=\"section section--grey -align-center\">             \r\n	<div class=\"container container--narrow\">                \r\n                 \r\n		<div class=\"section__head\">                     \r\n			<h2>The Team</h2>                 </div>                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-center\">                         \r\n				<div class=\"col-xl-9 col-lg-12\">                            \r\n                             \r\n					<div class=\"row\">                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_2.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Kirstin</h4>                                     \r\n							<p> Customer Executive</p>                                 </div>                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_5.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Cooper</h4>                                     \r\n							<p>Product Design</p>                                 </div>                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_4.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Andrew</h4>                                     \r\n							<p>Marketing</p>                                 </div>                                 \r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">                                     \r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_3.jpg\" alt=\"\" /></div>                                     \r\n							<h4>Mikael</h4>                                     \r\n							<p>Product Developer</p>                                 </div>                             </div>                            \r\n                         </div>                     </div>     \r\n                 </div>                 \r\n             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--white section--hiw\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"section__head -align-center\">                     \r\n			<h2>How yoCoach works</h2>                 </div>                 \r\n		<div class=\"section__body\">                     \r\n			<div class=\"row justify-content-between\">                         \r\n				<div class=\"col-lg-4\">                             \r\n					<div class=\"tabs-vertical tabs-js\">                                 \r\n						<ul>                                     \r\n							<li>                                         <a href=\"#tab1\">                                             \r\n									<h3>Browse</h3>                                             \r\n									<p>Browse thousands of teacher<br />\r\n										 profiles and bios.</p>                                         </a>                                     </li>                                     \r\n							<li>                                         <a href=\"#tab2\">                                             \r\n									<h3>Book</h3>                                             \r\n									<p>Book lessons with the best teachers for your<br />\r\n										 schedule, budget, goals, and learning style.</p>                                         </a>                                     </li>                                     \r\n							<li>                                         <a href=\"#tab3\">                                             \r\n									<h3>Start</h3>                                             \r\n									<p>Log in to yoCoach Video and<br />\r\n										 start talking.</p>                                         </a>                                     </li>                                 \r\n						</ul>                             </div>                         </div>                         \r\n				<div class=\"col-lg-7 col__content\">                             \r\n					<div id=\"tab1\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_01.png\" alt=\"\" /></div>                             </div>                             \r\n					<div id=\"tab2\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_02.png\" alt=\"\" /></div>                             </div>                             \r\n					<div id=\"tab3\" class=\"tabs-content-js\">                                 \r\n						<div class=\"media\"><img src=\"/public/images/800x500_03.png\" alt=\"\" /></div>                             </div>                             \r\n                         </div>                     </div>                 </div>             </div>         </section>         \r\n         \r\n         \r\n<section class=\"section section--grey\">             \r\n	<div class=\"container container--narrow\">                \r\n                 \r\n                 \r\n		<div class=\"col-xl-8 col-lg-8 col-md-8 address-container\">                                    \r\n			<h3>Contact us</h3>                                    \r\n			<div class=\"address-box\">                                        \r\n				<h6><img src=\"/public/images/icon_contact_1.svg\" alt=\"\" /> Address</h6>                                        \r\n				<p>The Office Group <br />\r\n					91 Wimpole Street <br />\r\n					Marylebone <br />\r\n					London <br />\r\n					W1G 0EF <br />\r\n					United Kingdom</p>                                    </div>                                    \r\n			<div class=\"address-box\">                                        \r\n				<h6><img src=\"/public/images/icon_contact_2.svg\" alt=\"\" /> EMAIL</h6>                                        \r\n				<p><a href=\"mailto:info@fillistudios.com\">info@yoCoach.com</a></p>                                    </div>                                    \r\n			<div class=\"address-box\">                                        \r\n				<h6><img src=\"/public/images/icon_contact_3.svg\" alt=\"\" /> CALL US</h6>                                        \r\n				<p>(+44) 020 7846 0316</p>                                    </div>                                 </div>                            </div>     \r\n                                                                                \r\n                      </section>         \r\n         \r\n         \r\n<section class=\"section section--white\">             \r\n	<div class=\"container container--narrow -align-center\">                 \r\n		<h2 class=\"-style-bold\">Looking forward to meeting<br />\r\n			  your new students?</h2>                 <span class=\"-gap\"></span>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>', 'About yoCoach', 'Company Overview'),
(1, 2, 'ghjhg', '<div><br />\r\n	</div>\r\n<div>\r\n	<pre class=\"tw-data-text tw-ta tw-text-small\" data-placeholder=\"Translation\" id=\"tw-target-text\" data-fulltext=\"\" dir=\"rtl\" style=\"unicode-bidi: isolate; background-color: rgb(255, 255, 255); border: none; padding: 0px 0.14em 0px 0px; position: relative; margin-top: 0px; margin-bottom: 0px; resize: none; font-family: inherit; overflow: hidden; text-align: right; width: 275px; white-space: pre-wrap; overflow-wrap: break-word; color: rgb(33, 33, 33); height: 120px; font-size: 16px !important; line-height: 24px !important;\"><span tabindex=\"0\" lang=\"ar\">ما هو وياكياك؟ أفضل مكان للعثور على Teahcer اللغة عبر الإنترنت\r\n\r\nابدأ التدريس</span></pre></div>', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(2, 1, 'Terms & Conditions', '<div class=\"container__cms\">\r\n	<p class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size: 14pt; font-family: Times; font-weight: bold;\">Terms and\r\nConditions</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Agreement between\r\nUser and http://www.yoCoach.com</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Welcome to http://www.yoCoach.com. The\r\nhttp://www.yoCoach.com website (the \"Site\") is comprised of various\r\nweb pages operated by yoCoachLLC (\"yoCoach\").\r\nhttp://www.yoCoach.com is offered to you conditioned on your acceptance\r\nwithout modification of the terms, conditions, and notices contained herein\r\n(the \"Terms\"). Your use of http://www.yoCoach.com constitutes your\r\nagreement to all such Terms. Please read these terms carefully, and keep a copy\r\nof them for your reference. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">http://www.yoCoach.com is an E-Commerce Site. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach is a teaching platform for teachers and students to\r\nconnect for learning a foreign language. Teachers set their own rates and\r\nschedules and students pay for classes within the site, pick a teacher. Teacher\r\ngets paid weekly for each class completion. yoCoach pays out 85% of cost to\r\nteachers and keeps 15% to keep the site in operation. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Electronic\r\nCommunications</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Visiting http://www.yoCoach.com or sending emails to\r\nyoCoach constitutes electronic communications. You consent to receive\r\nelectronic communications and you agree that all agreements, notices,\r\ndisclosures and other communications that we provide to you electronically, via\r\nemail and on the Site, satisfy any legal requirement that such communications\r\nbe in writing. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Your Account</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">If you use this site, you are responsible for maintaining\r\nthe confidentiality of your account and password and for restricting access to\r\nyour computer, and you agree to accept responsibility for all activities that\r\noccur under your account or password. You may not assign or otherwise transfer\r\nyour account to any other person or entity. You acknowledge that yoCoach is\r\nnot responsible for third party access to your account that results from theft\r\nor misappropriation of your account. yoCoach and its associates reserve the\r\nright to refuse or cancel service, terminate accounts, or remove or edit\r\ncontent in our sole discretion. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Children Under\r\nThirteen</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach does not knowingly collect, either online or\r\noffline, personal information from persons under the age of thirteen. If you\r\nare under 18, you may use http://www.yoCoach.com only with permission of a\r\nparent or guardian. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Cancellation/Refund\r\nPolicy</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You may cancel your class purchases at any time.\r\nCancelations made after 30 days will receive a 50% refund. All classes\r\npurchased will stay in your yoCoach account for 6 months. After 6 months your\r\nclasses will expire with no refund. Classes may be used with any teacher of\r\nyour choice. You may also cancel your teachers lessons and re-use your purchase\r\nwithin the site with another teacher. For questions please contact\r\ninfo@yoCoach.com \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Links to Third Party\r\nSites/Third Party Services</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">http://www.yoCoach.com may contain links to other websites\r\n(\"Linked Sites\"). The Linked Sites are not under the control of\r\nyoCoach and yoCoach is not responsible for the contents of any Linked Site,\r\nincluding without limitation any link contained in a Linked Site, or any\r\nchanges or updates to a Linked Site. yoCoach is providing these links to you\r\nonly as a convenience, and the inclusion of any link does not imply endorsement\r\nby yoCoach of the site or any association with its operators. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Certain services made available via http://www.yoCoach.com\r\nare delivered by third party sites and organizations. By using any product,\r\nservice or functionality originating from the http://www.yoCoach.com domain,\r\nyou hereby acknowledge and consent that yoCoach may share such information and\r\ndata with any third party with whom yoCoach has a contractual relationship to\r\nprovide the requested product, service or functionality on behalf of\r\nhttp://www.yoCoach.com users and customers. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">No Unlawful or\r\nProhibited Use/Intellectual Property </span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You are granted a non-exclusive, non-transferable, revocable\r\nlicense to access and use http://www.yoCoach.com strictly in accordance with\r\nthese terms of use. As a condition of your use of the Site, you warrant to\r\nyoCoach that you will not use the Site for any purpose that is unlawful or\r\nprohibited by these Terms. You may not use the Site in any manner which could\r\ndamage, disable, overburden, or impair the Site or interfere with any other\r\nparty\'s use and enjoyment of the Site. You may not obtain or attempt to obtain\r\nany materials or information through any means not intentionally made available\r\nor provided for through the Site. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">All content included as part of the Service, such as text,\r\ngraphics, logos, images, as well as the compilation thereof, and any software\r\nused on the Site, is the property of yoCoach or its suppliers and protected by\r\ncopyright and other laws that protect intellectual property and proprietary\r\nrights. You agree to observe and abide by all copyright and other proprietary\r\nnotices, legends or other restrictions contained in any such content and will\r\nnot make any changes thereto. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You will not modify, publish, transmit, reverse engineer,\r\nparticipate in the transfer or sale, create derivative works, or in any way\r\nexploit any of the content, in whole or in part, found on the Site. yoCoach\r\ncontent is not for resale. Your use of the Site does not entitle you to make\r\nany unauthorized use of any protected content, and in particular you will not\r\ndelete or alter any proprietary rights or attribution notices in any content.\r\nYou will use protected content solely for your personal use, and will make no\r\nother use of the content without the express written permission of yoCoach and\r\nthe copyright owner. You agree that you do not acquire any ownership rights in\r\nany protected content. We do not grant you any licenses, express or implied, to\r\nthe intellectual property of yoCoach or our licensors except as expressly\r\nauthorized by these Terms. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Use of Communication\r\nServices</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">The Site may contain bulletin board services, chat areas,\r\nnews groups, forums, communities, personal web pages, calendars, and/or other\r\nmessage or communication facilities designed to enable you to communicate with\r\nthe public at large or with a group (collectively, \"Communication Services\").\r\nYou agree to use the Communication Services only to post, send and receive\r\nmessages and material that are proper and related to the particular\r\nCommunication Service. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">By way of example, and not as a limitation, you agree that\r\nwhen using a Communication Service, you will not: defame, abuse, harass, stalk,\r\nthreaten or otherwise violate the legal rights (such as rights of privacy and\r\npublicity) of others; publish, post, upload, distribute or disseminate any\r\ninappropriate, profane, defamatory, infringing, obscene, indecent or unlawful\r\ntopic, name, material or information; upload files that contain software or\r\nother material protected by intellectual property laws (or by rights of privacy\r\nof publicity) unless you own or control the rights thereto or have received all\r\nnecessary consents; upload files that contain viruses, corrupted files, or any\r\nother similar software or programs that may damage the operation of another\'s\r\ncomputer; advertise or offer to sell or buy any goods or services for any\r\nbusiness purpose, unless such Communication Service specifically allows such\r\nmessages; conduct or forward surveys, contests, pyramid schemes or chain\r\nletters; download any file posted by another user of a Communication Service\r\nthat you know, or reasonably should know, cannot be legally distributed in such\r\nmanner; falsify or delete any author attributions, legal or other proper\r\nnotices or proprietary designations or labels of the origin or source of\r\nsoftware or other material contained in a file that is uploaded; restrict or\r\ninhibit any other user from using and enjoying the Communication Services;\r\nviolate any code of conduct or other guidelines which may be applicable for any\r\nparticular Communication Service; harvest or otherwise collect information\r\nabout others, including e-mail addresses, without their consent; violate any\r\napplicable laws or regulations. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach has no obligation to monitor the Communication\r\nServices. However, yoCoach reserves the right to review materials posted to a\r\nCommunication Service and to remove any materials in its sole discretion.\r\nyoCoach reserves the right to terminate your access to any or all of the\r\nCommunication Services at any time without notice for any reason whatsoever. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach reserves the right at all times to disclose any\r\ninformation as necessary to satisfy any applicable law, regulation, legal\r\nprocess or governmental request, or to edit, refuse to post or to remove any\r\ninformation or materials, in whole or in part, in yoCoach\'s sole discretion. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Always use caution when giving out any personally\r\nidentifying information about yourself or your children in any Communication\r\nService. yoCoach does not control or endorse the content, messages or\r\ninformation found in any Communication Service and, therefore, yoCoach specifically\r\ndisclaims any liability with regard to the Communication Services and any\r\nactions resulting from your participation in any Communication Service.\r\nManagers and hosts are not authorized yoCoach spokespersons, and their views\r\ndo not necessarily reflect those of yoCoach. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Materials uploaded to a Communication Service may be subject\r\nto posted limitations on usage, reproduction and/or dissemination. You are\r\nresponsible for adhering to such limitations if you upload the materials. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Materials Provided to\r\nhttp://www.yoCoach.com or Posted on Any yoCoach Web Page</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach does not claim ownership of the materials you\r\nprovide to http://www.yoCoach.com (including feedback and suggestions) or\r\npost, upload, input or submit to any yoCoach Site or our associated services\r\n(collectively \"Submissions\"). However, by posting, uploading,\r\ninputting, providing or submitting your Submission you are granting yoCoach,\r\nour affiliated companies and necessary sublicensees permission to use your\r\nSubmission in connection with the operation of their Internet businesses\r\nincluding, without limitation, the rights to: copy, distribute, transmit,\r\npublicly display, publicly perform, reproduce, edit, translate and reformat\r\nyour Submission; and to publish your name in connection with your Submission. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">No compensation will be paid with respect to the use of your\r\nSubmission, as provided herein. yoCoach is under no obligation to post or use\r\nany Submission you may provide and may remove any Submission at any time in\r\nyoCoach\'s sole discretion. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">By posting, uploading, inputting, providing or submitting\r\nyour Submission you warrant and represent that you own or otherwise control all\r\nof the rights to your Submission as described in this section including,\r\nwithout limitation, all the rights necessary for you to provide, post, upload,\r\ninput or submit the Submissions. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Third Party Accounts</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You will be able to connect your yoCoach account to third\r\nparty accounts. By connecting your yoCoach account to your third party\r\naccount, you acknowledge and agree that you are consenting to the continuous\r\nrelease of information about you to others (in accordance with your privacy\r\nsettings on those third party sites). If you do not want information about you\r\nto be shared in this manner, do not use this feature. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">International Users</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">The Service is controlled, operated and administered by\r\nyoCoach from our offices within the USA. If you access the Service from a\r\nlocation outside the USA, you are responsible for compliance with all local\r\nlaws. You agree that you will not use the yoCoach Content accessed through\r\nhttp://www.yoCoach.com in any country or in any manner prohibited by any\r\napplicable laws, restrictions or regulations. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Indemnification</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You agree to indemnify, defend and hold harmless yoCoach,\r\nits officers, directors, employees, agents and third parties, for any losses,\r\ncosts, liabilities and expenses (including reasonable attorney\'s fees) relating\r\nto or arising out of your use of or inability to use the Site or services, any\r\nuser postings made by you, your violation of any terms of this Agreement or\r\nyour violation of any rights of a third party, or your violation of any\r\napplicable laws, rules or regulations. yoCoach reserves the right, at its own\r\ncost, to assume the exclusive defense and control of any matter otherwise\r\nsubject to indemnification by you, in which event you will fully cooperate with\r\nyoCoach in asserting any available defenses. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Arbitration</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">In the event the parties are not able to resolve any dispute\r\nbetween them arising out of or concerning these Terms and Conditions, or any\r\nprovisions hereof, whether in contract, tort, or otherwise at law or in equity\r\nfor damages or any other relief, then such dispute shall be resolved only by\r\nfinal and binding arbitration pursuant to the Federal Arbitration Act,\r\nconducted by a single neutral arbitrator and administered by the American\r\nArbitration Association, or a similar arbitration service selected by the\r\nparties, in a location mutually agreed upon by the parties. The arbitrator\'s\r\naward shall be final, and judgment may be entered upon it in any court having\r\njurisdiction. In the event that any legal or equitable action, proceeding or\r\narbitration arises out of or concerns these Terms and Conditions, the\r\nprevailing party shall be entitled to recover its costs and reasonable\r\nattorney\'s fees. The parties agree to arbitrate all disputes and claims in\r\nregards to these Terms and Conditions or any disputes arising as a result of\r\nthese Terms and Conditions, whether directly or indirectly, including Tort\r\nclaims that are a result of these Terms and Conditions. The parties agree that\r\nthe Federal Arbitration Act governs the interpretation and enforcement of this\r\nprovision. The entire dispute, including the scope and enforceability of this\r\narbitration provision shall be determined by the Arbitrator. This arbitration\r\nprovision shall survive the termination of these Terms and Conditions. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Class Action Waiver</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Any arbitration under these Terms and Conditions will take\r\nplace on an individual basis; class arbitrations and\r\nclass/representative/collective actions are not permitted. THE PARTIES AGREE\r\nTHAT A PARTY MAY BRING CLAIMS AGAINST THE OTHER ONLY IN EACH\'S INDIVIDUAL\r\nCAPACITY, AND NOT AS A PLAINTIFF OR CLASS MEMBER IN ANY PUTATIVE CLASS,\r\nCOLLECTIVE AND/ OR REPRESENTATIVE PROCEEDING, SUCH AS IN THE FORM OF A PRIVATE\r\nATTORNEY GENERAL ACTION AGAINST THE OTHER. Further, unless both you and\r\nyoCoach agree otherwise, the arbitrator may not consolidate more than one\r\nperson\'s claims, and may not otherwise preside over any form of a\r\nrepresentative or class proceeding. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Liability Disclaimer</span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">THE INFORMATION, SOFTWARE, PRODUCTS, AND SERVICES INCLUDED\r\nIN OR AVAILABLE THROUGH THE SITE MAY INCLUDE INACCURACIES OR TYPOGRAPHICAL\r\nERRORS. CHANGES ARE PERIODICALLY ADDED TO THE INFORMATION HEREIN. yoCoachLLC\r\nAND/OR ITS SUPPLIERS MAY MAKE IMPROVEMENTS AND/OR CHANGES IN THE SITE AT ANY\r\nTIME. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoachLLC AND/OR ITS SUPPLIERS MAKE NO REPRESENTATIONS\r\nABOUT THE SUITABILITY, RELIABILITY, AVAILABILITY, TIMELINESS, AND ACCURACY OF\r\nTHE INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED GRAPHICS CONTAINED ON\r\nTHE SITE FOR ANY PURPOSE. TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW,\r\nALL SUCH INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED GRAPHICS ARE\r\nPROVIDED \"AS IS\" WITHOUT WARRANTY OR CONDITION OF ANY KIND.\r\nyoCoachLLC AND/OR ITS SUPPLIERS HEREBY DISCLAIM ALL WARRANTIES AND CONDITIONS\r\nWITH REGARD TO THIS INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED\r\nGRAPHICS, INCLUDING ALL IMPLIED WARRANTIES OR CONDITIONS OF MERCHANTABILITY,\r\nFITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, IN NO\r\nEVENT SHALL yoCoachLLC AND/OR ITS SUPPLIERS BE LIABLE FOR ANY DIRECT,\r\nINDIRECT, PUNITIVE, INCIDENTAL, SPECIAL, CONSEQUENTIAL DAMAGES OR ANY DAMAGES\r\nWHATSOEVER INCLUDING, WITHOUT LIMITATION, DAMAGES FOR LOSS OF USE, DATA OR\r\nPROFITS, ARISING OUT OF OR IN ANY WAY CONNECTED WITH THE USE OR PERFORMANCE OF\r\nTHE SITE, WITH THE DELAY OR INABILITY TO USE THE SITE OR RELATED SERVICES, THE\r\nPROVISION OF OR FAILURE TO PROVIDE SERVICES, OR FOR ANY INFORMATION, SOFTWARE,\r\nPRODUCTS, SERVICES AND RELATED GRAPHICS OBTAINED THROUGH THE SITE, OR OTHERWISE\r\nARISING OUT OF THE USE OF THE SITE, WHETHER BASED ON CONTRACT, TORT,\r\nNEGLIGENCE, STRICT LIABILITY OR OTHERWISE, EVEN IF yoCoachLLC OR ANY OF ITS\r\nSUPPLIERS HAS BEEN ADVISED OF THE POSSIBILITY OF DAMAGES. BECAUSE SOME\r\nSTATES/JURISDICTIONS DO NOT ALLOW THE EXCLUSION OR LIMITATION OF LIABILITY FOR\r\nCONSEQUENTIAL OR INCIDENTAL DAMAGES, THE ABOVE LIMITATION MAY NOT APPLY TO YOU.\r\nIF YOU ARE DISSATISFIED WITH ANY PORTION OF THE SITE, OR WITH ANY OF THESE\r\nTERMS OF USE, YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE USING THE SITE. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Termination/Access\r\nRestriction </span>\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach reserves the right, in its sole discretion, to\r\nterminate your access to the Site and the related services or any portion\r\nthereof at any time, without notice. To the maximum extent permitted by law,\r\nthis agreement is governed by the laws of the State of Washington and you hereby\r\nconsent to the exclusive jurisdiction and venue of courts in Washington in all\r\ndisputes arising out of or relating to the use of the Site. Use of the Site is\r\nunauthorized in any jurisdiction that does not give effect to all provisions of\r\nthese Terms, including, without limitation, this section. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">You agree that no joint venture, partnership, employment, or\r\nagency relationship exists between you and yoCoach as a result of this\r\nagreement or use of the Site. yoCoach\'s performance of this agreement is subject\r\nto existing laws and legal process, and nothing contained in this agreement is\r\nin derogation of yoCoach\'s right to comply with governmental, court and law\r\nenforcement requests or requirements relating to your use of the Site or\r\ninformation provided to or gathered by yoCoach with respect to such use. If\r\nany part of this agreement is determined to be invalid or unenforceable\r\npursuant to applicable law including, but not limited to, the warranty\r\ndisclaimers and liability limitations set forth above, then the invalid or\r\nunenforceable provision will be deemed superseded by a valid, enforceable\r\nprovision that most closely matches the intent of the original provision and\r\nthe remainder of the agreement shall continue in effect. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Unless otherwise specified herein, this agreement\r\nconstitutes the entire agreement between the user and yoCoach with respect to\r\nthe Site and it supersedes all prior or contemporaneous communications and\r\nproposals, whether electronic, oral or written, between the user and yoCoach with\r\nrespect to the Site. A printed version of this agreement and of any notice\r\ngiven in electronic form shall be admissible in judicial or administrative\r\nproceedings based upon or relating to this agreement to the same extent and\r\nsubject to the same conditions as other business documents and records\r\noriginally generated and maintained in printed form. It is the express wish to\r\nthe parties that this agreement and all related documents be written in\r\nEnglish. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Changes to Terms</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach reserves the right, in its sole discretion, to\r\nchange the Terms under which http://www.yoCoach.com is offered. The most\r\ncurrent version of the Terms will supersede all previous versions. yoCoach\r\nencourages you to periodically review the Terms to stay informed of our updates.\r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Contact Us</span> \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoach welcomes your questions or comments regarding the\r\nTerms: \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">yoCoachLLC \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">1123 SE 6th St. \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Battle Ground, Washington 98604 \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Email Address: \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Kelly@yoCoach.com \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Telephone number: \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">541-980-5595 \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">&nbsp; \r\n		<o:p></o:p></p>\r\n	<p class=\"MsoNormal\">Effective as of May 06, 2019 \r\n		<o:p></o:p></p>\r\n	<p>\r\n		<!--\r\n		[if gte mso 9]><xml>\r\n <o:OfficeDocumentSettings>\r\n  <o:TargetScreenSize>800x600</o:TargetScreenSize>\r\n </o:OfficeDocumentSettings>\r\n</xml><![endif]\r\n		-->\r\n		\r\n		<!--\r\n		[if gte mso 9]><xml>\r\n <w:WordDocument>\r\n  <w:View>Normal</w:View>\r\n  <w:Zoom>0</w:Zoom>\r\n  <w:TrackMoves/>\r\n  <w:TrackFormatting/>\r\n  <w:PunctuationKerning/>\r\n  <w:ValidateAgainstSchemas/>\r\n  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>\r\n  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>\r\n  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>\r\n  <w:DoNotPromoteQF/>\r\n  <w:LidThemeOther>EN-US</w:LidThemeOther>\r\n  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>\r\n  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>\r\n  <w:Compatibility>\r\n   <w:BreakWrappedTables/>\r\n   <w:SnapToGridInCell/>\r\n   <w:WrapTextWithPunct/>\r\n   <w:UseAsianBreakRules/>\r\n   <w:DontGrowAutofit/>\r\n   <w:SplitPgBreakAndParaMark/>\r\n   <w:EnableOpenTypeKerning/>\r\n   <w:DontFlipMirrorIndents/>\r\n   <w:OverrideTableStyleHps/>\r\n  </w:Compatibility>\r\n  <w:DoNotOptimizeForBrowser/>\r\n  <m:mathPr>\r\n   <m:mathFont m:val=\"Cambria Math\"/>\r\n   <m:brkBin m:val=\"before\"/>\r\n   <m:brkBinSub m:val=\"&#45;-\"/>\r\n   <m:smallFrac m:val=\"off\"/>\r\n   <m:dispDef/>\r\n   <m:lMargin m:val=\"0\"/>\r\n   <m:rMargin m:val=\"0\"/>\r\n   <m:defJc m:val=\"centerGroup\"/>\r\n   <m:wrapIndent m:val=\"1440\"/>\r\n   <m:intLim m:val=\"subSup\"/>\r\n   <m:naryLim m:val=\"undOvr\"/>\r\n  </m:mathPr></w:WordDocument>\r\n</xml><![endif]\r\n		-->\r\n		\r\n		<!--\r\n		[if gte mso 9]><xml>\r\n <w:LatentStyles DefLockedState=\"false\" DefUnhideWhenUsed=\"false\"\r\n  DefSemiHidden=\"false\" DefQFormat=\"false\" DefPriority=\"99\"\r\n  LatentStyleCount=\"375\">\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" QFormat=\"true\" Name=\"Normal\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" QFormat=\"true\" Name=\"heading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 9\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"header\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footer\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"35\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"caption\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of figures\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope return\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"line number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"page number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of authorities\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"macro\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"toa heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"10\" QFormat=\"true\" Name=\"Title\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Closing\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Signature\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Default Paragraph Font\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Message Header\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"11\" QFormat=\"true\" Name=\"Subtitle\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Salutation\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Date\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Note Heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Block Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"FollowedHyperlink\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"22\" QFormat=\"true\" Name=\"Strong\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"20\" QFormat=\"true\" Name=\"Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Document Map\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Plain Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"E-mail Signature\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Top of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Bottom of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal (Web)\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Acronym\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Cite\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Code\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Definition\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Keyboard\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Preformatted\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Sample\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Typewriter\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Variable\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Table\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation subject\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"No List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Contemporary\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Elegant\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Professional\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Balloon Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" Name=\"Table Grid\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Theme\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Placeholder Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"1\" QFormat=\"true\" Name=\"No Spacing\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Revision\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"34\" QFormat=\"true\"\r\n   Name=\"List Paragraph\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"29\" QFormat=\"true\" Name=\"Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"30\" QFormat=\"true\"\r\n   Name=\"Intense Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"19\" QFormat=\"true\"\r\n   Name=\"Subtle Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"21\" QFormat=\"true\"\r\n   Name=\"Intense Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"31\" QFormat=\"true\"\r\n   Name=\"Subtle Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"32\" QFormat=\"true\"\r\n   Name=\"Intense Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"33\" QFormat=\"true\" Name=\"Book Title\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"37\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Bibliography\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"TOC Heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"41\" Name=\"Plain Table 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"42\" Name=\"Plain Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"43\" Name=\"Plain Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"44\" Name=\"Plain Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"45\" Name=\"Plain Table 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"40\" Name=\"Grid Table Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"Grid Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"Grid Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"Grid Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"List Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"List Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"List Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Mention\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Smart Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hashtag\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Unresolved Mention\"/>\r\n </w:LatentStyles>\r\n</xml><![endif]\r\n		-->\r\n		\r\n		<style>\r\n		<!--\r\n		/* Font Definitions */\r\n @font-face\r\n	{font-family:\"Cambria Math\";\r\n	panose-1:2 4 5 3 5 4 6 3 2 4;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:roman;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:3 0 0 0 1 0;}\r\n@font-face\r\n	{font-family:Times;\r\n	panose-1:0 0 5 0 0 0 0 2 0 0;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:auto;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:-536870145 1342185562 0 0 415 0;}\r\n /* Style Definitions */\r\n p.MsoNormal, li.MsoNormal, div.MsoNormal\r\n	{mso-style-unhide:no;\r\n	mso-style-qformat:yes;\r\n	mso-style-parent:\"\";\r\n	margin:0in;\r\n	margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:12.0pt;\r\n	font-family:\"Times New Roman\",serif;\r\n	mso-fareast-font-family:\"Times New Roman\";\r\n	mso-fareast-language:RU;}\r\n.MsoChpDefault\r\n	{mso-style-type:export-only;\r\n	mso-default-props:yes;\r\n	font-size:10.0pt;\r\n	mso-ansi-font-size:10.0pt;\r\n	mso-bidi-font-size:10.0pt;}\r\n@page WordSection1\r\n	{size:8.5in 11.0in;\r\n	margin:1.0in 1.0in 1.0in 1.0in;\r\n	mso-header-margin:.5in;\r\n	mso-footer-margin:.5in;\r\n	mso-paper-source:0;}\r\ndiv.WordSection1\r\n	{page:WordSection1;}\r\n		-->\r\n		</style>\r\n		<!--\r\n		[if gte mso 10]>\r\n<style>\r\n /* Style Definitions */\r\n table.MsoNormalTable\r\n	{mso-style-name:\"Table Normal\";\r\n	mso-tstyle-rowband-size:0;\r\n	mso-tstyle-colband-size:0;\r\n	mso-style-noshow:yes;\r\n	mso-style-priority:99;\r\n	mso-style-parent:\"\";\r\n	mso-padding-alt:0in 5.4pt 0in 5.4pt;\r\n	mso-para-margin:0in;\r\n	mso-para-margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:10.0pt;\r\n	font-family:\"Times New Roman\",serif;}\r\n</style>\r\n<![endif]\r\n		-->\r\n		\r\n		<!--\r\n		StartFragment\r\n		-->\r\n		\r\n		<!--\r\n		EndFragment\r\n		-->\r\n		</p>\r\n	<p class=\"MsoNormal\">&nbsp;&nbsp;\r\n		<o:p></o:p></p></div>', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(2, 2, 'البنود و الظروف', '', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(3, 1, 'Privacy Policy', '<section class=\"section section--page\">             \r\n	<div class=\"container container--narrow\">                 \r\n		<div class=\"section__head\">                     \r\n			<h2>Privacy Policy</h2>                 </div>                \r\n		<div class=\"section__body\">                    \r\n			<div class=\"box -padding-30\">                        \r\n				<div class=\"cms-container\">                          \r\n                        \r\n                            \r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Protecting your private information is our priority. This\r\nStatement of Privacy applies to http://www.yoCoach.com and yoCoachLLC and\r\ngoverns data collection and usage. For the purposes of this Privacy Policy,\r\nunless otherwise noted, all references to yoCoachLLC include\r\nhttp://www.yoCoach.com and yoCoach. The yoCoach website is a Online language\r\nplatform for teaching and learning foreign languages. site. By using the\r\nyoCoach website, you consent to the data practices described in this\r\nstatement. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Collection of your\r\nPersonal Information</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">In order to better provide you with products and services\r\noffered on our Site, yoCoach may collect personally identifiable information,\r\nsuch as your: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; First\r\nand Last Name \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mailing\r\nAddress \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; E-mail\r\nAddress \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phone\r\nNumber \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; credentials\r\nand college degrees, certificates etc. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">If you purchase yoCoach\'s products and services, we collect\r\nbilling and credit card information. This information is used to complete the\r\npurchase transaction. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Please keep in mind that if you directly disclose personally\r\nidentifiable information or personally sensitive data through yoCoach\'s public\r\nmessage boards, this information may be collected and used by others. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">We do not collect any\r\npersonal information about you unless you voluntarily provide it to us.\r\nHowever, you may be required to provide certain personal information to us when\r\nyou <span style=\"font-size: 14pt; font-family: Times; font-weight: bold;\">Privacy Policy</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">elect to use certain products or services available on the\r\nSite. These may include: (a) registering for an account on our Site; (b)\r\nentering a sweepstakes or contest sponsored by us or one of our partners; (c)\r\nsigning up for special offers from selected third parties; (d) sending us an\r\nemail message; (e) submitting your credit card or other payment information\r\nwhen ordering and purchasing products and services on our Site. To wit, we will\r\nuse your information for, but not limited to, communicating with you in\r\nrelation to services and/or products you have requested from us. We also may\r\ngather additional personal or non-personal information in the future. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Use of your Personal\r\nInformation </span>\r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach collects and uses your personal information to\r\noperate its website(s) and deliver the services you have requested. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach may also use your personally identifiable information\r\nto inform you of other products or services available from yoCoach and its\r\naffiliates. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Sharing Information\r\nwith Third Parties</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach does not sell, rent or lease its customer lists to\r\nthird parties. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach may share data with trusted partners to help\r\nperform statistical analysis, send you email or postal mail, provide customer\r\nsupport, or arrange for deliveries. All such third parties are prohibited from\r\nusing your personal information except to provide these services to yoCoach,\r\nand they are required to maintain the confidentiality of your information. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach may disclose your personal information, without\r\nnotice, if required to do so by law or in the good faith belief that such\r\naction is necessary to: (a) conform to the edicts of the law or comply with\r\nlegal process served on yoCoach or the site; (b) protect and defend the rights\r\nor property of yoCoach; and/or (c) act under exigent circumstances to protect\r\nthe personal safety of users of yoCoach, or the public. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Automatically\r\nCollected Information</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Information about your computer hardware and software may be\r\nautomatically collected by yoCoach. This information can include: your IP\r\naddress, browser type, domain names, access times and referring website\r\naddresses. This information is used for the operation of the service, to\r\nmaintain quality of the service, and to provide general statistics regarding\r\nuse of the yoCoach website. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Use of Cookies</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">The yoCoach website may use \"cookies\" to help you\r\npersonalize your online experience. A cookie is a text file that is placed on\r\nyour hard disk by a web page server. Cookies cannot be used to run programs or\r\ndeliver viruses to your computer. Cookies are uniquely assigned to you, and can\r\nonly be read by a web server in the domain that issued the cookie to you. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">One of the primary purposes of cookies is to provide a\r\nconvenience feature to save you time. The purpose of a cookie is to tell the\r\nWeb server that you have returned to a specific page. For example, if you\r\npersonalize yoCoach pages, or register with yoCoach site or services, a\r\ncookie helps yoCoach to recall your specific information on subsequent visits.\r\nThis simplifies the process of recording your personal information, such as\r\nbilling addresses, shipping addresses, and so on. When you return to the same\r\nyoCoach website, the information you previously provided can be retrieved, so\r\nyou can easily use the yoCoach features that you customized. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">You have the ability to accept or decline cookies. Most Web\r\nbrowsers automatically accept cookies, but you can usually modify your browser\r\nsetting to decline cookies if you prefer. If you choose to decline cookies, you\r\nmay not be able to fully experience the interactive features of the yoCoach\r\nservices or websites you visit. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Links</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">This website contains links to other sites. Please be aware\r\nthat we are not responsible for the content or privacy practices of such other\r\nsites. We encourage our users to be aware when they leave our site and to read\r\nthe privacy statements of any other site that collects personally identifiable\r\ninformation. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Security of your\r\nPersonal Information</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach secures your personal information from unauthorized\r\naccess, use, or disclosure. yoCoach uses the following methods for this\r\npurpose: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SSL\r\nProtocol \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SSL \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">When personal information (such as a credit card number) is\r\ntransmitted to other websites, it is protected through the use of encryption,\r\nsuch as the Secure Sockets Layer (SSL) protocol. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">We strive to take appropriate security measures to protect\r\nagainst unauthorized access to or alteration of your personal information.\r\nUnfortunately, no data transmission over the Internet or any wireless network\r\ncan be guaranteed to be 100% secure. As a result, while we strive to protect\r\nyour personal information, you acknowledge that: (a) there are security and\r\nprivacy limitations inherent to the Internet which are beyond our control; and\r\n(b) security, integrity, and privacy of any and all information and data\r\nexchanged between you and us through this Site cannot be guaranteed. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Children Under\r\nThirteen</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach does not knowingly collect personally identifiable\r\ninformation from children under the age of thirteen. If you are under the age\r\nof thirteen, you must ask your parent or guardian for permission to use this\r\nwebsite. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Disconnecting your\r\nyoCoach Account from Third Party Websites</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">You will be able to connect your yoCoach account to third\r\nparty accounts. BY CONNECTING YOUR yoCoach ACCOUNT TO YOUR THIRD PARTY\r\nACCOUNT, YOU ACKNOWLEDGE AND AGREE THAT YOU ARE CONSENTING TO THE CONTINUOUS\r\nRELEASE OF INFORMATION ABOUT YOU TO OTHERS (IN ACCORDANCE WITH YOUR PRIVACY\r\nSETTINGS ON THOSE THIRD PARTY SITES). IF YOU DO NOT WANT INFORMATION ABOUT YOU,\r\nINCLUDING PERSONALLY IDENTIFYING INFORMATION, TO BE SHARED IN THIS MANNER, DO\r\nNOT USE THIS FEATURE. You may disconnect your account from a third party\r\naccount at any time. Users may learn how to disconnect their accounts from\r\nthird-party websites by visiting their \"My Account\" page. Users may\r\nalso contact us via email or telephone. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">E-mail Communications</span>\r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">From time to time, yoCoach may contact you via email for\r\nthe purpose of providing announcements, promotional offers, alerts,\r\nconfirmations, surveys, and/or other general communication. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">If you would like to stop receiving marketing or promotional\r\ncommunications via email from yoCoach, you may opt out of such communications\r\nby Customers may unsubscribe from emails or from the platform by \"replying\r\nSTOP\" or \"clicking on the UNSUBSCRIBE button. Through email request\r\nthey can have their profiles removed. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Changes to this\r\nStatement</span> \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach reserves the right to change this Privacy Policy\r\nfrom time to time. We will notify you about significant changes in the way we\r\ntreat personal information by sending a notice to the primary email address\r\nspecified in your account, by placing a prominent notice on our site, and/or by\r\nupdating any privacy information on this page. Your continued use of the Site\r\nand/or Services available through this Site after such modifications will\r\nconstitute your: (a) acknowledgment of the modified Privacy Policy; and (b)\r\nagreement to abide and be bound by that Policy. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\"><span style=\"font-weight: bold;\">Contact Information</span>\r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoach welcomes your questions or comments regarding this\r\nStatement of Privacy. If you believe that yoCoach has not adhered to this\r\nStatement, please contact yoCoach at: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">yoCoachLLC \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">1123 SE 6th st. \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Battle Ground, Washington 98604 \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Email Address: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">info@yoCoach.com \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Telephone number: \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">541-980-5595 \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">&nbsp; \r\n						<o:p></o:p></p>\r\n					<p class=\"MsoNormal\">Effective as of June 10, 2019 \r\n						<o:p></o:p></p>\r\n					<p>\r\n						<!--\r\n						[if gte mso 9]><xml>\r\n <o:OfficeDocumentSettings>\r\n  <o:TargetScreenSize>800x600</o:TargetScreenSize>\r\n </o:OfficeDocumentSettings>\r\n</xml><![endif]\r\n						-->\r\n						\r\n						<!--\r\n						[if gte mso 9]><xml>\r\n <w:WordDocument>\r\n  <w:View>Normal</w:View>\r\n  <w:Zoom>0</w:Zoom>\r\n  <w:TrackMoves/>\r\n  <w:TrackFormatting/>\r\n  <w:PunctuationKerning/>\r\n  <w:ValidateAgainstSchemas/>\r\n  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>\r\n  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>\r\n  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>\r\n  <w:DoNotPromoteQF/>\r\n  <w:LidThemeOther>EN-US</w:LidThemeOther>\r\n  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>\r\n  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>\r\n  <w:Compatibility>\r\n   <w:BreakWrappedTables/>\r\n   <w:SnapToGridInCell/>\r\n   <w:WrapTextWithPunct/>\r\n   <w:UseAsianBreakRules/>\r\n   <w:DontGrowAutofit/>\r\n   <w:SplitPgBreakAndParaMark/>\r\n   <w:EnableOpenTypeKerning/>\r\n   <w:DontFlipMirrorIndents/>\r\n   <w:OverrideTableStyleHps/>\r\n  </w:Compatibility>\r\n  <w:DoNotOptimizeForBrowser/>\r\n  <m:mathPr>\r\n   <m:mathFont m:val=\"Cambria Math\"/>\r\n   <m:brkBin m:val=\"before\"/>\r\n   <m:brkBinSub m:val=\"&#45;-\"/>\r\n   <m:smallFrac m:val=\"off\"/>\r\n   <m:dispDef/>\r\n   <m:lMargin m:val=\"0\"/>\r\n   <m:rMargin m:val=\"0\"/>\r\n   <m:defJc m:val=\"centerGroup\"/>\r\n   <m:wrapIndent m:val=\"1440\"/>\r\n   <m:intLim m:val=\"subSup\"/>\r\n   <m:naryLim m:val=\"undOvr\"/>\r\n  </m:mathPr></w:WordDocument>\r\n</xml><![endif]\r\n						-->\r\n						\r\n						<!--\r\n						[if gte mso 9]><xml>\r\n <w:LatentStyles DefLockedState=\"false\" DefUnhideWhenUsed=\"false\"\r\n  DefSemiHidden=\"false\" DefQFormat=\"false\" DefPriority=\"99\"\r\n  LatentStyleCount=\"375\">\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" QFormat=\"true\" Name=\"Normal\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" QFormat=\"true\" Name=\"heading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"9\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"heading 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index 9\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 7\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 8\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"toc 9\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"header\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footer\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"index heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"35\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"caption\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of figures\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"envelope return\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"footnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"line number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"page number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote reference\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"endnote text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"table of authorities\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"macro\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"toa heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Bullet 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Number 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"10\" QFormat=\"true\" Name=\"Title\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Closing\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Signature\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"0\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Default Paragraph Font\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"List Continue 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Message Header\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"11\" QFormat=\"true\" Name=\"Subtitle\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Salutation\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Date\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text First Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Note Heading\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Body Text Indent 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Block Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"FollowedHyperlink\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"22\" QFormat=\"true\" Name=\"Strong\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"20\" QFormat=\"true\" Name=\"Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Document Map\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Plain Text\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"E-mail Signature\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Top of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Bottom of Form\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal (Web)\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Acronym\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Address\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Cite\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Code\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Definition\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Keyboard\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Preformatted\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Sample\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Typewriter\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"HTML Variable\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Normal Table\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"annotation subject\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"No List\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Outline List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Simple 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Classic 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Colorful 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Columns 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Grid 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 4\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 5\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 7\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table List 8\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table 3D effects 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Contemporary\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Elegant\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Professional\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Subtle 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 2\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Web 3\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Balloon Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" Name=\"Table Grid\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Table Theme\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Placeholder Text\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"1\" QFormat=\"true\" Name=\"No Spacing\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" Name=\"Revision\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"34\" QFormat=\"true\"\r\n   Name=\"List Paragraph\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"29\" QFormat=\"true\" Name=\"Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"30\" QFormat=\"true\"\r\n   Name=\"Intense Quote\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"60\" Name=\"Light Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"61\" Name=\"Light List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"62\" Name=\"Light Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"63\" Name=\"Medium Shading 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"64\" Name=\"Medium Shading 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"65\" Name=\"Medium List 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"66\" Name=\"Medium List 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"67\" Name=\"Medium Grid 1 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"68\" Name=\"Medium Grid 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"69\" Name=\"Medium Grid 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"70\" Name=\"Dark List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"71\" Name=\"Colorful Shading Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"72\" Name=\"Colorful List Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"73\" Name=\"Colorful Grid Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"19\" QFormat=\"true\"\r\n   Name=\"Subtle Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"21\" QFormat=\"true\"\r\n   Name=\"Intense Emphasis\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"31\" QFormat=\"true\"\r\n   Name=\"Subtle Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"32\" QFormat=\"true\"\r\n   Name=\"Intense Reference\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"33\" QFormat=\"true\" Name=\"Book Title\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"37\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" Name=\"Bibliography\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"39\" SemiHidden=\"true\"\r\n   UnhideWhenUsed=\"true\" QFormat=\"true\" Name=\"TOC Heading\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"41\" Name=\"Plain Table 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"42\" Name=\"Plain Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"43\" Name=\"Plain Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"44\" Name=\"Plain Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"45\" Name=\"Plain Table 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"40\" Name=\"Grid Table Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"Grid Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"Grid Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"Grid Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"Grid Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"Grid Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"Grid Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"Grid Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"Grid Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"Grid Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"Grid Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\" Name=\"List Table 1 Light\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\" Name=\"List Table 6 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\" Name=\"List Table 7 Colorful\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 1\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 2\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 3\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 4\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 5\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"46\"\r\n   Name=\"List Table 1 Light Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"47\" Name=\"List Table 2 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"48\" Name=\"List Table 3 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"49\" Name=\"List Table 4 Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"50\" Name=\"List Table 5 Dark Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"51\"\r\n   Name=\"List Table 6 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" Priority=\"52\"\r\n   Name=\"List Table 7 Colorful Accent 6\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Mention\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Smart Hyperlink\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Hashtag\"/>\r\n  <w:LsdException Locked=\"false\" SemiHidden=\"true\" UnhideWhenUsed=\"true\"\r\n   Name=\"Unresolved Mention\"/>\r\n </w:LatentStyles>\r\n</xml><![endif]\r\n						-->\r\n						\r\n						<style>\r\n						<!--\r\n						/* Font Definitions */\r\n @font-face\r\n	{font-family:\"Cambria Math\";\r\n	panose-1:2 4 5 3 5 4 6 3 2 4;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:roman;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:3 0 0 0 1 0;}\r\n@font-face\r\n	{font-family:Times;\r\n	panose-1:0 0 5 0 0 0 0 2 0 0;\r\n	mso-font-charset:0;\r\n	mso-generic-font-family:auto;\r\n	mso-font-pitch:variable;\r\n	mso-font-signature:-536870145 1342185562 0 0 415 0;}\r\n /* Style Definitions */\r\n p.MsoNormal, li.MsoNormal, div.MsoNormal\r\n	{mso-style-unhide:no;\r\n	mso-style-qformat:yes;\r\n	mso-style-parent:\"\";\r\n	margin:0in;\r\n	margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:12.0pt;\r\n	font-family:\"Times New Roman\",serif;\r\n	mso-fareast-font-family:\"Times New Roman\";\r\n	mso-fareast-language:RU;}\r\n.MsoChpDefault\r\n	{mso-style-type:export-only;\r\n	mso-default-props:yes;\r\n	font-size:10.0pt;\r\n	mso-ansi-font-size:10.0pt;\r\n	mso-bidi-font-size:10.0pt;}\r\n@page WordSection1\r\n	{size:8.5in 11.0in;\r\n	margin:1.0in 1.0in 1.0in 1.0in;\r\n	mso-header-margin:.5in;\r\n	mso-footer-margin:.5in;\r\n	mso-paper-source:0;}\r\ndiv.WordSection1\r\n	{page:WordSection1;}\r\n						-->\r\n						</style>\r\n						<!--\r\n						[if gte mso 10]>\r\n<style>\r\n /* Style Definitions */\r\n table.MsoNormalTable\r\n	{mso-style-name:\"Table Normal\";\r\n	mso-tstyle-rowband-size:0;\r\n	mso-tstyle-colband-size:0;\r\n	mso-style-noshow:yes;\r\n	mso-style-priority:99;\r\n	mso-style-parent:\"\";\r\n	mso-padding-alt:0in 5.4pt 0in 5.4pt;\r\n	mso-para-margin:0in;\r\n	mso-para-margin-bottom:.0001pt;\r\n	mso-pagination:widow-orphan;\r\n	font-size:10.0pt;\r\n	font-family:\"Times New Roman\",serif;}\r\n</style>\r\n<![endif]\r\n						-->\r\n						\r\n						<!--\r\n						StartFragment\r\n						-->\r\n						\r\n						<!--\r\n						EndFragment\r\n						-->\r\n						</p>\r\n					<p class=\"MsoNormal\">&nbsp;&nbsp;\r\n						<o:p></o:p></p>      </div>                    </div>                </div>             </div>         </section>', '', '');
INSERT INTO `tbl_content_pages_lang` (`cpagelang_cpage_id`, `cpagelang_lang_id`, `cpage_title`, `cpage_content`, `cpage_image_title`, `cpage_image_content`) VALUES
(3, 2, 'سياسة خاصة', '', '', ''),
(4, 1, 'test page eng', '', 'test page english image', ''),
(4, 2, 'test page arabic', '', 'test page arabic image', ''),
(5, 1, 'Contact US', '<section class=\"banner banner--main\">             \r\n	<div class=\"banner__media\"><img src=\"/public/images/2000x600.jpg\" alt=\"\" /></div>             \r\n	<div class=\"banner__content banner__content--centered\">                 \r\n		<h1>Drop us a line</h1>                 \r\n		<p>Got a question? We\'d love to hear from  you. <br />\r\n			Send us a message and we\'ll respond as soon as possible.</p>             </div>         </section>         \r\n         \r\n<section class=\"section section--white section--offset -align-center\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"row justify-content-center\">                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_1.svg\" alt=\"\" /></div>                         \r\n				<p>Team yoCoach</p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_2.svg\" alt=\"\" /></div>                         \r\n				<p><a href=\"#\">info@yoCoach.com</a><br />\r\n					</p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_3.svg\" alt=\"\" /></div>                         \r\n				<p>coming soon.&nbsp; Please send email</p>                     </div>                 </div>             </div>         </section>', '', ''),
(5, 2, 'Contact US', '<section class=\"banner banner--main\">             \r\n	<div class=\"banner__media\"><img src=\"/public/images/2000x600.jpg\" alt=\"\" /></div>             \r\n	<div class=\"banner__content banner__content--centered\">                 \r\n		<h1>Drop us a line</h1>                 \r\n		<p>Got a question? We\'d love to hear from  you. <br />\r\n			Send us a message and we\'ll respond as soon as possible.</p>             </div>         </section>         \r\n         \r\n<section class=\"section section--white section--offset -align-center\">             \r\n	<div class=\"container container--fixed\">                 \r\n		<div class=\"row justify-content-center\">                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_1.svg\" alt=\"\" /></div>                         \r\n				<p>The Office Group <br />\r\n					91 Wimpole Street Marylebone,<br />\r\n					 London W1G 0EF, United Kingdom</p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_2.svg\" alt=\"\" /></div>                         \r\n				<p><a href=\"#\">info@yoCoach.com</a><br />\r\n					                         <a href=\"#\">sales@weyakweyak.com</a></p>                     </div>                     \r\n			<div class=\"col-xl-3 col-lg-4 col-md-4\">                         \r\n				<div class=\"icon\"><img src=\"/public/images/contact_icon_3.svg\" alt=\"\" /></div>                         \r\n				<p>(+44) 020 7846 0316 <br />\r\n					(+44) 020 7846 0316 <br />\r\n					(+44) 020 7846 0316</p>                     </div>                 </div>             </div>         </section>', '', ''),
(6, 1, 'Help', '<div>\r\n	<ol style=\"box-sizing: border-box; margin: 0px 0px 0.692em; padding: 0px 0px 0px 2em; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: Ubuntu, sans-serif; font-size: 16px; vertical-align: baseline; list-style-position: outside; list-style-image: initial; color: rgb(34, 34, 34); background-color: rgb(255, 255, 255);\">\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><a href=\"https://www.drupal.org/docs/develop/documenting-your-project/help-text-standards#description\" rel=\"nofollow\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 120, 190); text-decoration-line: none;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Short description</strong></a>&nbsp;of what the module&nbsp;<em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">does</em>. It is displayed on the&nbsp;<em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Extend</em>&nbsp;or&nbsp;<em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Modules</em>&nbsp;page (in Drupal 8 or 7). It is the only texts users will see if the module is not enabled yet.</li>\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><a href=\"https://www.drupal.org/docs/develop/documenting-your-project/help-text-standards#links\" rel=\"nofollow\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 120, 190); text-decoration-line: none;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Description on links</strong></a>&nbsp;are displayed with the links on the Configuration and Structure pages and invite users to do something.</li>\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><a href=\"https://www.drupal.org/docs/develop/documenting-your-project/help-text-standards#admin\" rel=\"nofollow\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 120, 190); text-decoration-line: none;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Explanations on the administration pages</strong>.</a>&nbsp;Ideally this should not be needed, but if they do they are short and do not duplicate the help page.</li>\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><a href=\"https://www.drupal.org/docs/develop/documenting-your-project/help-text-standards#help\" rel=\"nofollow\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 120, 190); text-decoration-line: none;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Help page</strong></a>&nbsp;displayed by the&nbsp;<a href=\"https://www.drupal.org/documentation/modules/help/\" rel=\"nofollow\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 120, 190); text-decoration-line: none;\">Help module</a>&nbsp;with three sections: What does the module do, what can users do with it, and a link to the online documentation here on drupal.org. This&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; margin: 0px; padding: 0.25em 0.5em; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: Monaco, Consolas, \"Andale Mono\", \"Ubuntu Mono\", monospace; font-size: 0.75em; vertical-align: baseline; white-space: pre-wrap; background: rgb(247, 247, 247); border-radius: 0.25em;\"><span class=\"token function\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 71, 113);\">hook_help</span><span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">(</span><span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">)</span></code>&nbsp;text is in the&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; margin: 0px; padding: 0.25em 0.5em; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: Monaco, Consolas, \"Andale Mono\", \"Ubuntu Mono\", monospace; font-size: 0.75em; vertical-align: baseline; white-space: pre-wrap; background: rgb(247, 247, 247); border-radius: 0.25em;\">my_module<span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">.</span>module</code>&nbsp;file.</li>\r\n	</ol>\r\n	<h2 style=\"box-sizing: border-box; margin: 0.9em 0px 0.45em; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-weight: 300; font-stretch: inherit; line-height: 1.35em; font-family: Ubuntu, sans-serif; font-size: 2em; vertical-align: baseline; -webkit-font-smoothing: antialiased; color: rgb(6, 71, 113); background-color: rgb(255, 255, 255);\"><a id=\"description\" name=\"description\" rel=\"nofollow\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(6, 120, 190);\">1. Short Description</a></h2>\r\n	<p style=\"box-sizing: border-box; margin: 0px 0px 1.385em; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: Ubuntu, sans-serif; font-size: 16px; vertical-align: baseline; color: rgb(34, 34, 34); background-color: rgb(255, 255, 255);\">The short description is set in the&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; margin: 0px; padding: 0.25em 0.5em; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: Monaco, Consolas, \"Andale Mono\", \"Ubuntu Mono\", monospace; font-size: 0.75em; vertical-align: baseline; white-space: pre-wrap; background: rgb(247, 247, 247); border-radius: 0.25em;\">description</code>&nbsp;element of:</p>\r\n	<ul style=\"box-sizing: border-box; margin: 0px 0px 0.692em; padding: 0px 0px 0px 2em; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: Ubuntu, sans-serif; font-size: 16px; vertical-align: baseline; list-style-position: initial; list-style-image: initial; color: rgb(34, 34, 34); background-color: rgb(255, 255, 255);\">\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Drupal 8:&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; margin: 0px; padding: 0.25em 0.5em; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: Monaco, Consolas, \"Andale Mono\", \"Ubuntu Mono\", monospace; font-size: 0.75em; vertical-align: baseline; white-space: pre-wrap; background: rgb(247, 247, 247); border-radius: 0.25em;\">my_module<span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">.</span>info<span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">.</span>yml</code>&nbsp;file</li>\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Drupal 7:&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; margin: 0px; padding: 0.25em 0.5em; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: Monaco, Consolas, \"Andale Mono\", \"Ubuntu Mono\", monospace; font-size: 0.75em; vertical-align: baseline; white-space: pre-wrap; background: rgb(247, 247, 247); border-radius: 0.25em;\">my_module<span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">.</span>info</code>&nbsp;file</li>\r\n	</ul>\r\n	<p style=\"box-sizing: border-box; margin: 0px 0px 1.385em; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: Ubuntu, sans-serif; font-size: 16px; vertical-align: baseline; color: rgb(34, 34, 34); background-color: rgb(255, 255, 255);\">This description is displayed in the list of available modules on the&nbsp;<em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Extend</em>&nbsp;(D8) or&nbsp;<em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Modules</em>&nbsp;(D7) page to tell site builders what the module does, before they enable it.<br style=\"box-sizing: border-box;\" />\r\n		The description starts with a verb and should be short and concise.</p>\r\n	<p style=\"box-sizing: border-box; margin: 0px 0px 1.385em; padding: 0px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: Ubuntu, sans-serif; font-size: 16px; vertical-align: baseline; color: rgb(34, 34, 34); background-color: rgb(255, 255, 255);\"><em style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Examples</em></p>\r\n	<ul style=\"box-sizing: border-box; margin: 0px 0px 0.692em; padding: 0px 0px 0px 2em; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: Ubuntu, sans-serif; font-size: 16px; vertical-align: baseline; list-style-position: initial; list-style-image: initial; color: rgb(34, 34, 34); background-color: rgb(255, 255, 255);\">\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Help</strong>&nbsp;Manages the display of online help.</li>\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Content Translation</strong>&nbsp;Allows users to translate content entities.</li>\r\n		<li style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Datetime</strong>&nbsp;Defines datetime form elements and a datetime field type.<br style=\"box-sizing: border-box;\" />\r\n			<code class=\" language-php\" style=\"box-sizing: border-box; margin: 0px; padding: 0.25em 0.5em; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: Monaco, Consolas, \"Andale Mono\", \"Ubuntu Mono\", monospace; font-size: 0.75em; vertical-align: baseline; white-space: pre-wrap; background: rgb(247, 247, 247); border-radius: 0.25em;\">description<span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">:</span> Defines datetime form elements <span class=\"token keyword keyword-and\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(83, 115, 141);\">and</span> a datetime field type<span class=\"token punctuation\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline; color: rgb(85, 85, 85);\">.</span></code></li>\r\n	</ul></div>', 'Help', 'hgujgkuy'),
(6, 2, 'مساعدة', 'مساعدة', '', ''),
(7, 1, 'Apply Teach', '', 'Get paid to help people', 'Earn money teaching your language online from home. Anytime. Anywhere.'),
(7, 2, 'تطبيق تعليم', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countries`
--

CREATE TABLE `tbl_countries` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `country_active` tinyint(1) NOT NULL,
  `country_currency_id` int(11) NOT NULL,
  `country_language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_countries`
--

INSERT INTO `tbl_countries` (`country_id`, `country_code`, `country_active`, `country_currency_id`, `country_language_id`) VALUES
(5, 'LK', 0, 0, 0),
(6, 'US', 1, 1, 1),
(8, 'AF', 1, 0, 0),
(9, 'AL', 1, 0, 0),
(10, 'DZ', 1, 0, 0),
(11, 'AD', 1, 0, 0),
(12, 'Ea', 1, 0, 0),
(13, 'AR', 1, 0, 0),
(14, 'AM', 1, 0, 0),
(15, 'AW', 1, 0, 0),
(16, 'SH', 1, 0, 0),
(17, 'AU', 1, 0, 0),
(18, 'AT', 1, 0, 0),
(19, 'AZ', 1, 0, 0),
(20, 'BS', 1, 0, 0),
(21, 'BH', 1, 0, 0),
(22, 'BD', 1, 0, 0),
(23, 'BB', 1, 0, 0),
(24, 'BY', 1, 0, 0),
(25, 'BE', 1, 0, 0),
(26, 'BZ', 1, 0, 0),
(27, 'BJ', 1, 0, 0),
(28, 'BM', 1, 0, 0),
(29, 'BT', 1, 0, 0),
(30, 'BO', 1, 0, 0),
(31, 'N-', 1, 0, 0),
(32, 'BA', 1, 0, 0),
(33, 'BW', 1, 0, 0),
(34, 'BR', 1, 0, 0),
(35, 'VG', 1, 0, 0),
(36, 'BN', 1, 0, 0),
(37, 'BG', 1, 0, 0),
(38, 'BF', 1, 0, 0),
(39, 'Bi', 1, 0, 0),
(40, 'CV', 1, 0, 0),
(41, 'KH', 1, 0, 0),
(42, 'CM', 1, 0, 0),
(43, 'CA', 1, 0, 0),
(44, '2U', 1, 0, 0),
(45, 'KY', 1, 0, 0),
(46, 'CF', 1, 0, 0),
(47, 'TD', 1, 0, 0),
(48, 'N0', 1, 0, 0),
(49, 'CL', 1, 0, 0),
(50, 'CN', 1, 0, 0),
(51, 'Co', 1, 0, 0),
(52, 'KM', 1, 0, 0),
(53, 'CD', 1, 0, 0),
(54, 'CG', 1, 0, 0),
(55, 'CR', 1, 0, 0),
(56, 'CI', 1, 0, 0),
(57, 'HR', 1, 0, 0),
(58, 'CU', 1, 0, 0),
(59, 'Cy', 1, 0, 0),
(60, 'CZ', 1, 0, 0),
(61, 'DK', 1, 0, 0),
(62, 'Dj', 1, 0, 0),
(63, 'DM', 1, 0, 0),
(64, 'DO', 1, 0, 0),
(65, 'EC', 1, 0, 0),
(66, 'Eg', 1, 0, 0),
(67, 'SV', 1, 0, 0),
(68, 'GQ', 1, 0, 0),
(69, 'Er', 1, 0, 0),
(70, 'EE', 1, 0, 0),
(71, 'SZ', 1, 0, 0),
(72, 'ET', 1, 0, 0),
(73, 'FJ', 1, 0, 0),
(74, 'FI', 1, 0, 0),
(75, 'Fr', 1, 0, 0),
(76, 'GA', 1, 0, 0),
(77, 'GM', 1, 0, 0),
(78, 'Ge', 1, 0, 0),
(79, 'DE', 1, 0, 0),
(80, 'GH', 1, 0, 0),
(81, 'GR', 1, 0, 0),
(82, 'GD', 1, 0, 0),
(83, 'GT', 1, 0, 0),
(84, 'GN', 1, 0, 0),
(85, 'GW', 1, 0, 0),
(86, 'GY', 1, 0, 0),
(87, 'HT', 1, 0, 0),
(88, 'HN', 1, 0, 0),
(89, 'Hu', 1, 0, 0),
(90, 'IS', 1, 0, 0),
(91, 'IN', 1, 0, 0),
(92, 'ID', 1, 0, 0),
(93, 'IR', 1, 0, 0),
(94, 'IQ', 1, 0, 0),
(95, 'IE', 1, 0, 0),
(96, 'IL', 1, 0, 0),
(97, 'IT', 1, 0, 0),
(98, 'JM', 1, 0, 0),
(99, 'JP', 1, 0, 0),
(100, 'JO', 1, 0, 0),
(101, 'KZ', 1, 0, 0),
(102, 'KE', 1, 0, 0),
(103, 'KI', 1, 0, 0),
(104, 'XK', 1, 0, 0),
(105, 'KW', 1, 0, 0),
(106, 'KG', 1, 0, 0),
(107, 'LL', 1, 0, 0),
(108, 'LV', 1, 0, 0),
(109, 'LB', 1, 0, 0),
(110, 'LS', 1, 0, 0),
(111, 'LR', 1, 0, 0),
(112, 'LI', 1, 0, 0),
(113, 'LU', 1, 0, 0),
(114, 'MG', 1, 0, 0),
(115, 'MY', 1, 0, 0),
(116, 'MW', 1, 0, 0),
(117, 'SI', 1, 0, 0),
(118, 'MV', 1, 0, 0),
(119, 'ML', 1, 0, 0),
(120, 'MT', 1, 0, 0),
(121, 'MH', 1, 0, 0),
(122, 'MR', 1, 0, 0),
(123, 'MU', 1, 0, 0),
(124, 'MX', 1, 0, 0),
(125, 'FM', 1, 0, 0),
(126, 'MD', 1, 0, 0),
(127, 'MC', 1, 0, 0),
(128, 'MN', 1, 0, 0),
(129, 'ME', 1, 0, 0),
(130, 'MA', 1, 0, 0),
(131, 'MM', 1, 0, 0),
(132, 'NA', 1, 0, 0),
(133, 'NR', 1, 0, 0),
(134, 'NP', 1, 0, 0),
(135, 'NL', 1, 0, 0),
(136, 'NZ', 1, 0, 0),
(137, 'NI', 1, 0, 0),
(138, 'NE', 1, 0, 0),
(139, 'MK', 1, 0, 0),
(140, 'NO', 1, 0, 0),
(141, 'OM', 1, 0, 0),
(142, 'PK', 1, 0, 0),
(143, 'PW', 1, 0, 0),
(144, 'P-', 1, 0, 0),
(145, 'PA', 1, 0, 0),
(146, 'PG', 1, 0, 0),
(147, 'PY', 1, 0, 0),
(148, 'PE', 1, 0, 0),
(149, 'PH', 1, 0, 0),
(150, 'PL', 1, 0, 0),
(151, 'PT', 1, 0, 0),
(152, 'QA', 1, 0, 0),
(153, 'RO', 1, 0, 0),
(154, 'RU', 1, 0, 0),
(155, 'RW', 1, 0, 0),
(156, 'KN', 1, 0, 0),
(157, 'LC', 1, 0, 0),
(158, 'VC', 1, 0, 0),
(159, 'WS', 1, 0, 0),
(160, 'SM', 1, 0, 0),
(161, 'ST', 1, 0, 0),
(162, 'SA', 1, 0, 0),
(163, 'SN', 1, 0, 0),
(164, 'RS', 1, 0, 0),
(165, 'SC', 1, 0, 0),
(166, 'SL', 1, 0, 0),
(167, 'SG', 1, 0, 0),
(168, 'SK', 1, 0, 0),
(170, 'SB', 1, 0, 0),
(171, 'SO', 1, 0, 0),
(172, 'ZA', 1, 0, 0),
(173, 'KR', 1, 0, 0),
(174, 'SS', 1, 0, 0),
(175, 'ES', 1, 0, 0),
(176, 'KL', 1, 0, 0),
(177, 'SD', 1, 0, 0),
(178, 'SR', 1, 0, 0),
(179, 'SE', 1, 0, 0),
(180, 'CH', 1, 0, 0),
(181, 'SY', 1, 0, 0),
(182, 'TW', 1, 0, 0),
(183, 'TJ', 1, 0, 0),
(184, 'TZ', 1, 0, 0),
(185, 'TH', 1, 0, 0),
(186, 'TL', 1, 0, 0),
(187, 'TG', 1, 0, 0),
(188, 'TO', 1, 0, 0),
(189, 'TT', 1, 0, 0),
(190, 'TN', 1, 0, 0),
(191, 'TR', 1, 0, 0),
(192, 'TM', 1, 0, 0),
(193, 'TV', 1, 0, 0),
(194, 'UG', 1, 0, 0),
(195, 'UA', 1, 0, 0),
(196, 'AE', 1, 0, 0),
(197, 'GB', 1, 0, 0),
(198, 'Un', 0, 0, 0),
(199, 'UY', 1, 0, 0),
(200, 'Uz', 1, 0, 0),
(201, 'VU', 1, 0, 0),
(202, 'VA', 1, 0, 0),
(203, 'VE', 1, 0, 0),
(204, 'VN', 1, 0, 0),
(205, 'ZM', 1, 0, 0),
(206, 'ZW', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countries_lang`
--

CREATE TABLE `tbl_countries_lang` (
  `countrylang_country_id` int(11) NOT NULL,
  `countrylang_lang_id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_countries_lang`
--

INSERT INTO `tbl_countries_lang` (`countrylang_country_id`, `countrylang_lang_id`, `country_name`) VALUES
(8, 1, 'Afghanistan'),
(9, 1, 'Albania'),
(10, 1, 'Algeria'),
(11, 1, 'Andorra'),
(13, 1, 'Argentian'),
(14, 1, 'Armenia'),
(15, 1, 'Aruba'),
(16, 1, 'Ascension Island'),
(17, 1, 'Australia'),
(18, 1, 'Austria'),
(19, 1, 'Azerbaijan'),
(20, 1, 'Bahamas'),
(21, 1, 'Bahrain'),
(22, 1, 'Bangladesh'),
(23, 1, 'Barbados'),
(24, 1, 'Belarus'),
(25, 1, 'Belgium'),
(26, 1, 'Belize'),
(27, 1, 'Benin'),
(28, 1, 'Bermuda'),
(29, 1, 'Bhutan'),
(30, 1, 'Bolivia'),
(31, 1, 'Bonaire'),
(32, 1, 'Bosnia and Herzegovina'),
(33, 1, 'Botswana'),
(34, 1, 'Brazil'),
(35, 1, 'British Virgin Islands'),
(36, 1, 'Brunei'),
(37, 1, 'Bulgaria'),
(38, 1, 'Burkina Faso'),
(39, 1, 'Burundi'),
(41, 1, 'Cambodia'),
(42, 1, 'Cameroon'),
(43, 1, 'Canada'),
(40, 1, 'Cape Verde'),
(44, 1, 'Caribbean Netherlands'),
(45, 1, 'Cayman Islands'),
(46, 1, 'Central African Republic'),
(47, 1, 'Chad'),
(48, 1, 'Chatham Islands'),
(49, 1, 'Chile'),
(50, 1, 'China'),
(51, 1, 'Colombia'),
(52, 1, 'Comoros'),
(55, 1, 'Costa Rica'),
(56, 1, 'Cote d\'Ivoire'),
(57, 1, 'Croatia'),
(58, 1, 'Cuba'),
(59, 1, 'Cyprus'),
(60, 1, 'Czechia'),
(53, 1, 'Democratic Republic of the Congo'),
(61, 1, 'Denmark'),
(62, 1, 'Djibouti'),
(63, 1, 'Dominica'),
(64, 1, 'Dominican Republic'),
(12, 1, 'East Caribbean'),
(65, 1, 'Ecuador'),
(66, 1, 'Egypt'),
(67, 1, 'El Salvador'),
(68, 1, 'Equatorial Guinea'),
(69, 1, 'Eritrea'),
(70, 1, 'Estonia'),
(71, 1, 'Eswatini (formerly Swaziland)'),
(72, 1, 'Ethiopia'),
(73, 1, 'Fiji'),
(74, 1, 'Finland'),
(75, 1, 'France'),
(76, 1, 'Gabon'),
(77, 1, 'Gambia'),
(78, 1, 'Georgia'),
(79, 1, 'Germany'),
(80, 1, 'Ghana'),
(81, 1, 'Greece'),
(82, 1, 'Grenada'),
(83, 1, 'Guatemala'),
(84, 1, 'Guinea'),
(85, 1, 'Guinea-Bissau'),
(86, 1, 'Guyana'),
(87, 1, 'Haiti'),
(88, 1, 'Honduras'),
(89, 1, 'Hungary'),
(90, 1, 'Iceland'),
(91, 1, 'India'),
(92, 1, 'Indonesia'),
(93, 1, 'Iran'),
(94, 1, 'Iraq'),
(95, 1, 'Ireland'),
(96, 1, 'Israel'),
(97, 1, 'Italy'),
(98, 1, 'Jamaica'),
(99, 1, 'Japan'),
(100, 1, 'Jordan'),
(101, 1, 'Kazakhstan'),
(102, 1, 'Kenya'),
(103, 1, 'Kiribati'),
(104, 1, 'Kosovo'),
(105, 1, 'Kuwait'),
(106, 1, 'Kyrgyzstan'),
(107, 1, 'Laos'),
(108, 1, 'Latvia'),
(109, 1, 'Lebanon'),
(110, 1, 'Lesotho'),
(111, 1, 'LIberia'),
(112, 1, 'Liechtenstein'),
(113, 1, 'Luxembourg'),
(114, 1, 'Madagascar'),
(116, 1, 'Malawi'),
(115, 1, 'Malaysia'),
(118, 1, 'Maldives'),
(119, 1, 'Mali'),
(120, 1, 'Malta'),
(121, 1, 'Marshall Islands'),
(122, 1, 'Mauritania'),
(123, 1, 'Mauritius'),
(124, 1, 'Mexico'),
(125, 1, 'Micronesia'),
(126, 1, 'Moldova'),
(127, 1, 'Monaco'),
(128, 1, 'Mongolia'),
(129, 1, 'Montenegro'),
(130, 1, 'Morocco'),
(131, 1, 'Myanmar (formerly Burma)'),
(132, 1, 'Namibia'),
(133, 1, 'Nauru'),
(134, 1, 'Nepal'),
(135, 1, 'Netherlands'),
(136, 1, 'New Zealand'),
(137, 1, 'Nicaragua'),
(138, 1, 'Niger'),
(139, 1, 'North Macedonia (formerly Macedonia)'),
(140, 1, 'Norway'),
(141, 1, 'Oman'),
(142, 1, 'Pakistan'),
(143, 1, 'Palau'),
(144, 1, 'Palestine'),
(145, 1, 'Panama'),
(146, 1, 'Papua New Guinea'),
(147, 1, 'Paraguay'),
(148, 1, 'Peru'),
(149, 1, 'Philippines'),
(150, 1, 'Poland'),
(151, 1, 'Portugal'),
(152, 1, 'Qatar'),
(54, 1, 'Republic of the Congo'),
(153, 1, 'Romania'),
(154, 1, 'Russia'),
(155, 1, 'Rwanda'),
(156, 1, 'Saint Kitts and Nevis'),
(157, 1, 'Saint Lucia'),
(158, 1, 'Saint Vincent and the Grenadines'),
(159, 1, 'Samoa'),
(160, 1, 'San Marino'),
(161, 1, 'Sao Tome and Principe'),
(162, 1, 'Saudi Arabia'),
(163, 1, 'Senegal'),
(164, 1, 'Serbia'),
(165, 1, 'Seychelles'),
(166, 1, 'Sierra Leone'),
(167, 1, 'Singapore'),
(168, 1, 'Slovakia'),
(117, 1, 'Slovenia'),
(170, 1, 'Solomon Islands'),
(171, 1, 'Somalia'),
(172, 1, 'South Africa'),
(173, 1, 'South Korea'),
(174, 1, 'South Sudan'),
(175, 1, 'Spain'),
(176, 1, 'Sri Lanka'),
(5, 1, 'SriLanka'),
(177, 1, 'Sudan'),
(178, 1, 'Suriname'),
(179, 1, 'Sweden'),
(180, 1, 'Switzerland'),
(181, 1, 'Syria'),
(182, 1, 'Taiwan'),
(183, 1, 'Tajikistan'),
(184, 1, 'Tanzania'),
(185, 1, 'Thailand'),
(186, 1, 'Timor-Leste'),
(187, 1, 'Togo'),
(188, 1, 'Tongo'),
(189, 1, 'Trinidad and Tobago'),
(190, 1, 'Tunisia'),
(191, 1, 'Turkey'),
(192, 1, 'Turkmenistan'),
(193, 1, 'Tuvalu'),
(194, 1, 'Uganda'),
(195, 1, 'Ukraine'),
(196, 1, 'United Arab Emirates'),
(197, 1, 'United Kingdom'),
(6, 1, 'United States'),
(198, 1, 'United States of America'),
(199, 1, 'Uruguay'),
(200, 1, 'Uzbekistan'),
(201, 1, 'Vanuatu'),
(202, 1, 'Vatican City (Holy See)'),
(203, 1, 'Venezuela'),
(204, 1, 'Vietnam'),
(205, 1, 'Zambia'),
(206, 1, 'Zimbabwe'),
(176, 2, 'Sri Lanka'),
(6, 2, 'United States');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons_history`
--

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

CREATE TABLE `tbl_coupons_hold_pending_order` (
  `ochold_order_id` varchar(15) NOT NULL,
  `ochold_coupon_id` int(11) NOT NULL,
  `ochold_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons_lang`
--

CREATE TABLE `tbl_coupons_lang` (
  `couponlang_coupon_id` int(11) NOT NULL,
  `couponlang_lang_id` int(11) NOT NULL,
  `coupon_title` varchar(255) NOT NULL,
  `coupon_description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_courses_categories`
--

CREATE TABLE `tbl_courses_categories` (
  `ccategory_id` int(11) NOT NULL,
  `ccategory_identifier` varchar(255) NOT NULL,
  `ccategory_active` tinyint(1) NOT NULL,
  `ccategory_deleted` tinyint(1) NOT NULL,
  `ccategory_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_courses_categories_lang`
--

CREATE TABLE `tbl_courses_categories_lang` (
  `ccategorylang_ccategory_id` int(11) NOT NULL,
  `ccategorylang_lang_id` int(11) NOT NULL,
  `ccategory_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cron_log`
--

CREATE TABLE `tbl_cron_log` (
  `cronlog_id` int(11) NOT NULL,
  `cronlog_cron_id` int(11) NOT NULL,
  `cronlog_started_at` datetime NOT NULL,
  `cronlog_ended_at` datetime NOT NULL,
  `cronlog_details` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cron_schedules`
--

CREATE TABLE `tbl_cron_schedules` (
  `cron_id` int(11) NOT NULL,
  `cron_name` varchar(255) NOT NULL,
  `cron_command` varchar(255) NOT NULL,
  `cron_duration` int(11) NOT NULL COMMENT 'Minutes',
  `cron_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_cron_schedules`
--

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES
(1, 'lesson one day reminder', 'LessonReminder/sendLessonReminder/1', 1440, 1),
(2, 'lesson 30 mints reminder', 'LessonReminder/sendLessonReminder/2', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currencies`
--

CREATE TABLE `tbl_currencies` (
  `currency_id` int(11) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `currency_symbol_left` varchar(10) NOT NULL,
  `currency_symbol_right` varchar(10) NOT NULL,
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

CREATE TABLE `tbl_currencies_lang` (
  `currencylang_currency_id` int(11) NOT NULL,
  `currencylang_lang_id` int(11) NOT NULL,
  `currency_name` varchar(100) NOT NULL
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

CREATE TABLE `tbl_email_archives` (
  `emailarchive_id` int(11) NOT NULL,
  `emailarchive_to_email` varchar(100) NOT NULL,
  `emailarchive_tpl_name` varchar(255) NOT NULL,
  `emailarchive_subject` text NOT NULL,
  `emailarchive_body` text NOT NULL,
  `emailarchive_headers` mediumtext NOT NULL,
  `emailarchive_sent_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_templates`
--

CREATE TABLE `tbl_email_templates` (
  `etpl_code` varchar(50) NOT NULL,
  `etpl_lang_id` int(11) NOT NULL,
  `etpl_name` varchar(255) NOT NULL,
  `etpl_subject` varchar(255) NOT NULL,
  `etpl_body` text NOT NULL,
  `etpl_replacements` mediumtext NOT NULL,
  `etpl_status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_email_templates`
--

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('admin_forgot_password', 1, 'Admin Forgot Password Email', 'Forgot Password Email', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                  </td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>                    \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Request Received</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Retrieve Password!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>It seems that you have used forgot password option at <a href=\"{website_url}\">{website_name}</a>. Please click here to below link to change your password.\r\n                                  <br />\r\n												<br />\r\n												                                  <a href=\"{reset_url}\" style=\"background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click here</a>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{reset_url} URL to reset the password<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1),
('new_registration_admin', 1, 'New Registration - Admin', 'New Registration on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                  </td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>                    \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">New Account Created!</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin</h3>We have received a new registration on <a href=\"{website_url}\">{website_name}</a>. Please find the details below:\r\n                                  <br />\r\n												<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_full_name}</td>\r\n														</tr>  \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_email}</td>\r\n														</tr>                                                        \r\n													</tbody>\r\n												</table></td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '{website_name} Name of the website<br />\r\n{user_email} Email Address of the person registered<br />\r\n{user_first_name} First Name of the person registered<br />\r\n{user_last_name} Last Name of the person registered<br />\r\n{user_full_name} Full Name of the person registered<br />\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n', 1),
('user_email_verification', 1, 'Email Confirmation on Registration', 'Email Verification at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Account Verification!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>                                  Congratulations!! Your account has been successfully created at <a href=\"{website_url}\">{website_name}</a>.. \r\nJust follow this link below to confirm your email address.\r\n                                  <br />\r\n												<br />\r\n												                                  <a href=\"{verification_url}\" style=\"background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Verify Account</a>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_fist_name} First Name of the email receiver.<br> {user_last_name} Last Name of the email receiver.<br> {user_full_name} Name of the email receiver.<br> {website_name} Name of our website<br> {verification_url} Url to verify email<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('welcome_registration', 1, 'Welcome Mail on Registration', 'Welcome to {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Account Created!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Thank you for signing up at <a href=\"{website_url}\">{website_name}</a>.<br />\r\n												<br />\r\n												We are thrilled to have you aboard! You have taken a great first step and we are so excited to connect directly with you.<br />\r\n												<br />\r\n												If you require any assistance in using our site, or have any feedback or suggestions, you can email us at {contact_us_email}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_full_name} Full Name of the signed up user.<br>\r\n{user_first_name} FirstName of the signed up user.<br>\r\n{user_last_name} Last Name of the signed up user.<br>\r\n{contact_us_email} - Contact Us Email Address<br/>\r\n{website_name} Name of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n', 1),
('forgot_password', 1, 'Forgot Password Email', 'Password reset instructions at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Forgot Password!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>It seems that you have used forgot password option at <a href=\"{website_url}\">{website_name}</a>.<br />\r\n												\r\n												<div>Please visit the link given below to reset your password. Please note that the link is valid for next 24 hours only.</div>\r\n												<div><br />\r\n													</div><a href=\"{reset_url}\" style=\"background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click here</a>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_first_name} First Name of the email receiver<br> {user_last_name} Last Name of the email receiver<br> {user_full_name} Full Name of the email receiver<br> {website_name} Name of our website<br> {website_url} URL of our website<br> {reset_url} URL to reset the password<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('password_changed_successfully', 1, 'Password Changed Successfully', 'Password Changed Successfully at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Congratulations</h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Changed Password!</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {user_full_name} </strong><br />\r\n													You have successfully changed your password, now you can log in with your new password.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Please click here to below link to Login to your Account.<br />\r\n													<a href=\"{login_link}\" style=\"font-size:15px; color:#ff3a59;\">Click here</a></td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">Weâ€˜re here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_first_name} First Name of the email receiver.<br/>\r\n{user_last_name} Last Name of the email receiver.<br/>\r\n{user_full_name} Full Name of the email receiver.<br/>\r\n{website_name} Name of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('account_credited_debited', 1, 'Credits Received/Debited Email', 'Your account has been {txn_type} on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Account Transaction</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your account has been {txn_type} at <a href=\"{website_url}\">{website_name}</a>. Please find the details below:<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">ID</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_id}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Amount<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_amount}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Comment</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_comments}</td>\r\n														</tr> \r\n													</tbody>\r\n												</table>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_first_name} First Name of the email receiver.<br />\r\n{user_last_name} Last Name of the email receiver.<br />\r\n{user_full_name} Full Name of the email receiver.<br />\r\n{txn_type} - Credited or Debited<br>{txn_id} - Transaction ID<br/>{txn_amount} - Transaction Amount<br>{txn_comments} - Transaction Comments<br>{website_name} - Name of the website.\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1),
('new_teacher_approval_admin', 1, 'New Tutor Approval Request - Admin', 'New Tutor Approval Request on {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Request Received</h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Seller Approval</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n													We have received a new seller approval request on <a href=\"{website_url}\">{website_name}</a>. Please find the details below:</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">\r\n													<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n														<tbody>\r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Reference Number</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{reference_number}</td>\r\n															</tr>                                                        \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Username<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{username}</td>\r\n															</tr>  \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{email}</td>\r\n															</tr> \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td>\r\n															</tr>                                                        \r\n														</tbody>\r\n													</table></td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">Weâ€˜re here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{website_name} Name of the website<br />\r\n{username} \r\n\r\nUsername of the person registered<br />\r\n{email} Email Address of the person registered<br />\r\n{name} Name of the person sent request<br />\r\n{reference_number} \r\n\r\nReference Number of the request<br />\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n', 1),
('teacher_request_status_change_learner', 1, 'Learners - Tutor Request Status Change', 'Your Tutor  Request {new_request_status} at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Updated</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Tutor Approval Status</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name} </h3>Your Tutor approval request has been {new_request_status} corresponding to Reference Number - {reference_number}.{request_comments}<br />\r\n												</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_first_name} - First Name of the email receiver.<br/> {user_last_name} - Last Name of the email receiver.<br/> {user_full_name} - Full Name of the email receiver.<br/> {new_request_status} New Request Status (Approved/Declined) <br> {reference_number} Reference Number of the request<br> {website_name} Name of our website<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('user_admin_password_changed_successfully', 1, 'User/Admin Password Changed Successfully', 'Password reset successfully {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Congratulations</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your password has been changed successfully.<br />\r\n												\r\n												<div>Please click on below link to Login to your account.</div>\r\n												<div><br />\r\n													</div><a href=\"{website_url}\" style=\"background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click to Login</a></td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_full_name}<br>\r\n{website_name}<br>\r\n{website_url}<br>\r\n{login_link}<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1),
('failed_login_attempt', 1, 'Failed Login Attempt', 'Failed Login Attempt', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\"><a href=\"{website_url}\"> {website_name} </a></h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Are you facing problem logging in?<br />\r\n												You seem to be facing problem logging in at <a href=\"{website_url}\">{website_name}</a><br />\r\n												Please note that your username and password are both case sensitive.<br />\r\n												You can use forgot password feature if you have lost your password.<br />\r\n												If you were not trying logging in, it might be a hacking attempt.<br />\r\n												Also, you should keep your email password secured.<br />\r\n												                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('learner_cancelled_email', 1, 'Learner Cancelled Lesson Email', 'Learner Cancelled Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Learner {action} The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name} </h3>Learner ({learner_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{learner_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1),
('learner_issue_reported_email', 1, 'Learner Issue Reported Email', 'Learner Issue Reported Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">\n	<tbody>\n		<tr>\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>\n		</tr>\n		<tr>\n			<td style=\"background:#e84c3d;padding:0 0 0;\">\n				<!--\n				header start here\n				-->\n				\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">\n					<tbody>\n						<tr>\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\n                      </td>\n						</tr>\n					</tbody>\n				</table>\n				<!--\n				header end here\n				-->\n				</td>\n		</tr>\n		<tr>\n			<td>\n				<!--\n				page body start here\n				-->\n				\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n					<tbody>\n						<tr>\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n									<tbody>\n										<tr>\n											<td style=\"padding:20px 0 60px;\"><img src=\"icon-account.png\" alt=\"\" />\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Learner Reported The Issue</h2></td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n						<tr>\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n									<tbody>\n										<tr>\n											<td style=\"padding:60px 0 70px;\">\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name}  </h3>\n												<p style=\"line-height: 1.5;\"> Learner ({learner_name}) has reported the issue with ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time} </p>Reason:   <br />\n												{learner_comment}</td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n					</tbody>\n				</table>\n				<!--\n				page body end here\n				-->\n				</td>\n		</tr>\n		<tr>\n			<td>\n				<!--\n				page footer start here\n				-->\n				\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n					<tbody>\n						<tr>\n							<td style=\"height:30px;\"></td>\n						</tr>\n						<tr>\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n									<tbody>\n										<tr>\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\n												<a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n						<tr>\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n									<tbody>\n										<tr>\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\n												<br />\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\n                                      \n                                  </td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n						<tr>\n							<td style=\"padding:0; height:50px;\"></td>\n						</tr>\n					</tbody>\n				</table>\n				<!--\n				page footer end here\n				-->\n				</td>\n		</tr>\n	</tbody>\n</table>', '{learner_name}\n{teacher_name}\n{lesson_name}\n{learner_comment}\n{lesson_date}\n{lesson_start_time}\n{lesson_end_time}\n{action}\n', 1),
('teacher_issue_resolved_email', 1, 'Teacher Issue Resolved Email', 'Teacher Issue Resolved Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">\r\n				<!--\r\n				header start here\r\n				-->\r\n				\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>\r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				header end here\r\n				-->\r\n				</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n				\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:20px 0 60px;\"><img src=\"icon-account.png\" alt=\"\" />\r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher Resolved The Issue</h2></td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:60px 0 70px;\">\r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}  </h3>\r\n												<p style=\"line-height: 1.5;\"> Teacher ({teacher_name}) has resolved the issue with ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time} </p>\r\n												<p style=\"line-height: 1.5; margin-bottom: 0px;\"><strong>Teacher Comment:</strong></p>\r\n												<p style=\"line-height: 1.5; margin-top: 0px;\">{teacher_comment}</p>\r\n												<p><strong>Issue Reason By Teacher:</strong><br />\r\n													{teacher_issue_reason}</p>\r\n												<p><strong>Resolve Type : </strong> {issue_resolve_type}</p></td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page body end here\r\n				-->\r\n				</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<!--\r\n				page footer start here\r\n				-->\r\n				\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												<a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">\r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"padding:0; height:50px;\"></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page footer end here\r\n				-->\r\n				</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('learner_message_to_teacher_email', 1, 'Learner Message To Teacher Email', 'Learner Message To Teacher at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Learner {action} !</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {teacher_name} </strong><br />\r\n													<a href=\"{website_url}\">{website_name}</a>.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Learner ({learner_name}) has sent you message Below:<br />\r\n													</td>\r\n											</tr>\r\n											\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Message:   <br />{learner_message}\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch if you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{learner_name}\r\n{teacher_name}\r\n{learner_message}\r\n{action}', 1),
('learner_reschedule_email', 1, 'Learner Reschedule Lesson Email', 'Learner Reschedule Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Learner {action} The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name}  </h3>Learner ({learner_name}) has {action} the lesson ({lesson_name}) which was scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{learner_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1),
('learner_schedule_email', 1, 'Learner New Schedule Lesson Details Email', 'Learner New Schedule Lesson Details Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Learner {action} The Lesson!</h2>                                     \r\n                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name}  </h3>Learner ({learner_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{learner_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{learner_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('teacher_cancelled_email', 1, 'Teacher Cancelled Lesson Email', 'Teacher Cancelled Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher Cancel The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name} </h3>Teacher ({teacher_name}) has cancelled the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('teacher_issue_reported_email', 1, 'Teacher Issue Reported Email', 'Teacher Issue Reported Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher {action} The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has reported an issue the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('teacher_message_to_learner_email', 1, 'Teacher Message To Learner Email', 'Teacher Message To Learner at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">Teacher {action} !</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {learner_name} </strong><br />\r\n													<a href=\"{website_url}\">{website_name}</a>.</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Teacher ({teacher_name}) has sent you message Below:<br />\r\n													</td>\r\n											</tr>\r\n											\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Message:   <br />{teacher_message}\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch if you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											   \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{learner_name}\r\n{teacher_name}\r\n{teacher_message}\r\n{action}', 1),
('teacher_reschedule_email', 1, 'Teacher Reschedule Lesson Email', 'Teacher Reschedule Lesson Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher {action} The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has {action} the lesson ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('teacher_scheduled_lesson_email', 1, 'Teacher New Schedule Lesson Details Email', 'Teacher New Schedule Lesson Details at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher {action} The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has Scheduled  the lesson ({lesson_name}) again on {lesson_date} {lesson_start_time} - {lesson_end_time}<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}', 1),
('giftcard_recipient', 1, 'Gift Card Email To Recipient', '{sender_name} sent you an {website_name} Gift Card!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Gift Card!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {recipient_name}</h3>{sender_name} sent you a {website_name} gift card.\r\n                                  <br />\r\n												<br />\r\n												                                  Gift card code: <span style=\" border: 3px dotted #ddd;padding: 5px 10px;font-weight: bold\">{giftcard_code}</span>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('giftcard_admin', 1, 'Gift Card Purchased', '{website_name} Gift Card Purchased!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Gift Cards!</h2>                                     {giftcard_codes}\r\n                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear admin,</h3>{buyer_name} have purchased {website_name} gift card.\r\n                                  <br />\r\n												</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '', 1),
('giftcard_buyer', 1, 'Gift Card Order Email To Buyer', '{website_name} Gift Card Order Confirmation!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Congratulations</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Gift Cards!</h2>                                     {giftcard_codes}\r\n                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {buyer_name}</h3>thank you for buying {website_name} gift card(s).\r\n                                  <br />\r\n												</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '', 1),
('giftcard_redeem_admin', 1, 'Gift Card Redeemed', '{website_name} Gift Card Redeemed!', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Redeemed</h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Gift Cards!</h2>                                     {giftcard_codes}\r\n                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear admin,</h3>{giftcard_username} have redeemed {giftcard_code} gift card. Amount received {giftcard_amount}\r\n                                  <br />\r\n												</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '', 1),
('blog_comment_status_changed', 1, 'Blog Comment Status Change - Notification', 'Blog Comment Status Changed at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                  </td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>                    \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Changed</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Blog Comment Status</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your Comment (for the post \'{post_title}\' / posted on {posted_on_datetime}) status has been changed to {new_status} at <a href=\"{website_url}\">{website_name}</a>.\r\n                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('blog_contribution_status_changed', 1, 'Blog Contribution Status Change - Notification', 'Blog Contribution Status Changed at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                  </td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>                    \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 <img src=\"icon-account.png\" alt=\"\" />                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Changed</h5>                                 \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Blog Contribution Status</h2>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>Your blog contribution (posted on {posted_on_datetime}) status has been changed to {new_status} at <a href=\"{website_url}\">{website_name}</a>.\r\n                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n             \r\n            \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('contact_us', 1, 'Contact-Us', 'Contact Us Form Submitted on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Contact Us Data</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin</h3>{name} has submitted the contact us form on <a href=\"{website_url}\">{website_name}</a>.<br />\r\n												\r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email Address<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{email_address}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Phone Number</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone_number}</td>\r\n														</tr> \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Message</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{message}</td>\r\n														</tr>   \r\n													</tbody>\r\n												</table>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '', 1);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('test_email', 1, 'Test Email', 'Test Email', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n    \r\n	<tbody>\r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n    \r\n		<tr>      \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n				<!--\r\n				header start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                  </td>              \r\n						</tr>          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				header end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n   \r\n    \r\n		<tr>      \r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n              \r\n					<tbody>\r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 60px;\">                                 \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">Test Mail</h5>                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:60px 0 70px;\">                                  This is the test email &nbsp;from&nbsp;&nbsp;<a href=\"{website_url}\">{website_name}</a>.\r\n                                  <br />\r\n												                              </td>                          \r\n										</tr>                         \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page body end here\r\n				-->\r\n				      </td>    \r\n		</tr>    \r\n    \r\n    \r\n		<tr>      \r\n			<td>          \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				   \r\n          \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n						<tr>                  \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n									<tbody>\r\n										<tr>                              \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												<br />\r\n												&copy; 2018, {website_name}. All Rights Reserved.\r\n                                  \r\n                              </td>                          \r\n										</tr>                          \r\n                      \r\n									</tbody>\r\n								</table>                  </td>              \r\n						</tr>              \r\n              \r\n						<tr>                  \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n						</tr>              \r\n              \r\n          \r\n					</tbody>\r\n				</table>          \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   \r\n      </td>    \r\n		</tr>    \r\n    \r\n    \r\n    \r\n	</tbody>\r\n</table>', '', 1),
('user_email_change_verification', 1, 'User Email Change Verification Link', 'Email Verification at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \n        \n        \n	<tbody>            \n		<tr>      \n                \n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \n            \n		</tr>    \n        \n            \n		<tr>      \n                \n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \n                    \n				<!--\n				header start here\n				-->\n				                       \n              \n                    \n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \n                        \n					<tbody>                            \n						<tr>                  \n                                \n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \n                                \n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\n                      </td>              \n                            \n						</tr>          \n                        \n					</tbody>                    \n				</table>          \n                    \n				<!--\n				header end here\n				-->\n				                       \n          </td>    \n            \n		</tr>    \n        \n       \n        \n            \n		<tr>      \n                \n			<td>                    \n				<!--\n				page body start here\n				-->\n				                       \n              \n                    \n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \n                  \n                        \n					<tbody>                        \n						<tr>                      \n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \n                              \n									<tbody>\n										<tr>                                  \n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Account Verification!</h2>                                  </td>                              \n										</tr>                             \n                          \n									</tbody>\n								</table>                      </td>                  \n						</tr>                  \n                  \n						<tr>                      \n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \n                              \n									<tbody>\n										<tr>                                  \n											<td style=\"padding:60px 0 70px;\">                                      \n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>                                  Please Verify Your Email to Change Email on&nbsp;&nbsp;<a href=\"{website_url}\">{website_name}</a>.. \nJust follow this link below to confirm your email address.\n                                  <br />\n												<br />\n												                                  <a href=\"{verification_url}\" style=\"background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Verify Account</a>                                  </td>                              \n										</tr>                             \n                          \n									</tbody>\n								</table>                      </td>                  \n						</tr>                  \n                 \n                \n              \n					</tbody>                    \n				</table>          \n                    \n				<!--\n				page body end here\n				-->\n				                          </td>    \n            \n		</tr>    \n        \n        \n            \n		<tr>      \n                \n			<td>          \n                    \n				<!--\n				page footer start here\n				-->\n				                       \n              \n                    \n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \n                        \n					<tbody>                            \n						<tr>                                \n							<td style=\"height:30px;\"></td>                            \n						</tr>              \n                            \n						<tr>                  \n                                \n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \n                                    \n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \n                                        \n									<tbody>                                            \n										<tr>                              \n                                                \n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \n                                            \n										</tr>                          \n                          \n                                        \n									</tbody>                                    \n								</table>                  </td>              \n                            \n						</tr>              \n                            \n						<tr>                  \n                                \n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \n                                    \n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \n                                        \n									<tbody>                                            \n										<tr>                              \n                                                \n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\n												                                                    <br />\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\n                                      \n                                  </td>                          \n                                            \n										</tr>                          \n                          \n                                        \n									</tbody>                                    \n								</table>                  </td>              \n                            \n						</tr>              \n                  \n                            \n						<tr>                  \n                                \n							<td style=\"padding:0; height:50px;\"></td>              \n                            \n						</tr>              \n                  \n              \n                        \n					</tbody>                    \n				</table>          \n                    \n				<!--\n				page footer end here\n				-->\n				                       \n          </td>    \n            \n		</tr>    \n        \n        \n        \n        \n	</tbody>    \n</table>', '{user_fist_name} First Name of the email receiver.<br> {user_last_name} Last Name of the email receiver.<br> {user_full_name} Name of the email receiver.<br> {website_name} Name of our website<br> {verification_url} Url to verify email<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('coming_up_lesson_reminder', 1, 'Scheduled lesson(s) Reminder', 'Lesson Reminder at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \n        \n        \n	<tbody>            \n		<tr>      \n                \n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \n            \n		</tr>    \n        \n            \n		<tr>      \n                \n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \n                    \n				<!--\n				header start here\n				-->\n				                       \n              \n                    \n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \n                        \n					<tbody>                            \n						<tr>                  \n                                \n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \n                                \n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\n                      </td>              \n                            \n						</tr>          \n                        \n					</tbody>                    \n				</table>          \n                    \n				<!--\n				header end here\n				-->\n				                       \n          </td>    \n            \n		</tr>    \n        \n       \n        \n            \n		<tr>      \n                \n			<td>                    \n				<!--\n				page body start here\n				-->\n				                       \n              \n                    \n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \n                  \n                        \n					<tbody>                        \n						<tr>                      \n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \n                              \n									<tbody>\n										<tr>                                  \n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Scheduled Lesson Reminder!</h2>                                  </td>                              \n										</tr>                             \n                          \n									</tbody>\n								</table>                      </td>                  \n						</tr>                  \n                  \n						<tr>                      \n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \n                              \n									<tbody>\n										<tr>                                  \n											<td style=\"padding:60px 0 70px;\">                                      \n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>                                  You have  scheduled lesson(s) on &nbsp;&nbsp;<a href=\"{website_url}\">{website_name}</a><br />\n												</td>                              \n										</tr> \n										<tr>\n										</tr>\n									</tbody>\n								</table>{lessons_details}</td>\n						</tr>                          \n					</tbody>\n				</table>                      </td>                  \n		</tr>                  \n                 \n                \n              \n	</tbody>                    \n</table>          \n<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \n                        \n	<tbody>                            \n		<tr>                                \n			<td style=\"height:30px;\"></td>                            \n		</tr>              \n                            \n		<tr>                  \n                                \n			<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \n                                    \n				<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \n                                        \n					<tbody>                                            \n						<tr>                              \n                                                \n							<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\n								                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \n                                            \n						</tr>                          \n                          \n                                        \n					</tbody>                                    \n				</table>                  </td>              \n                            \n		</tr>              \n                            \n		<tr>                  \n                                \n			<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \n                                    \n				<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \n                                        \n					<tbody>                                            \n						<tr>                              \n                                                \n							<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\n								                                                    <br />\n								                                                    &copy; 2018, {website_name}. All Rights Reserved.\n                                      \n                                  </td>                          \n                                            \n						</tr>                          \n                          \n                                        \n					</tbody>                                    \n				</table>                  </td>              \n                            \n		</tr>              \n                  \n                            \n		<tr>                  \n                                \n			<td style=\"padding:0; height:50px;\"></td>              \n                            \n		</tr>              \n                  \n              \n                        \n	</tbody>                    \n</table>          \n                    \n<!--\npage footer end here\n-->', '{user_fist_name} First Name of the email receiver.<br> {user_last_name} Last Name of the email receiver.<br> {user_full_name} Name of the email receiver.<br> {website_name} Name of our website<br> {social_media_icons} <br> {contact_us_url} <br>', 1),
('new_message_arrived', 1, 'New Message Arrived', 'New Message Arrived at {website_name}', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					 \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					 </td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;\">\r\n					<!--\r\n					page title start here\r\n					-->\r\n					 \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n									<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n									<h2 style=\"margin:0; font-size:34px; padding:0;\">{action}!</h2></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page title end here\r\n					-->\r\n					 </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page body start here\r\n					-->\r\n					 \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {to_user_name}</strong><br />\r\n													</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">{from_user_name} has sent you a message:<br />\r\n													</td>\r\n											</tr>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px;\">Message: <br />\r\n													\r\n													<div>{message}</div>\r\n													<div>&nbsp;</div>\r\n													<div>Login to view message:&nbsp;<a href=\"https://wtutors.4livedemo.com/admin/%7Bwebsite_url%7D\" style=\"font-family: Arial; text-align: center; background-color: rgb(255, 255, 255);\">{website_name}</a></div></td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											 \r\n											<tr>\r\n												<td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch if you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The{website_name}Team<br />\r\n													</td>\r\n											</tr>\r\n											<!--\r\n											section footer\r\n											-->\r\n											 \r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page body end here\r\n					-->\r\n					 </td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					 \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name}Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					 </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{to_user_name}<br />{from_user_name}<br />{message}<br />{action}', 1),
('admin_new_issue_reported_email', 1, 'Admin Issue Reported Email', 'Admin Issue Reported Email at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">\n	<tbody>\n		<tr>\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>\n		</tr>\n		<tr>\n			<td style=\"background:#e84c3d;padding:0 0 0;\">\n				<!--\n				header start here\n				-->\n				\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">\n					<tbody>\n						<tr>\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\n                      </td>\n						</tr>\n					</tbody>\n				</table>\n				<!--\n				header end here\n				-->\n				</td>\n		</tr>\n		<tr>\n			<td>\n				<!--\n				page body start here\n				-->\n				\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n					<tbody>\n						<tr>\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n									<tbody>\n										<tr>\n											<td style=\"padding:20px 0 60px;\"><img src=\"icon-account.png\" alt=\"\" />\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">New Issue Reported</h2></td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n						<tr>\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n									<tbody>\n										<tr>\n											<td style=\"padding:60px 0 70px;\">\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin  </h3>\n												<p style=\"line-height: 1.5;\">{escalated_by} has posted an issue with ({lesson_name}) which is scheduled on {lesson_date} {lesson_start_time} - {lesson_end_time} </p></td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n					</tbody>\n				</table>\n				<!--\n				page body end here\n				-->\n				</td>\n		</tr>\n		<tr>\n			<td>\n				<!--\n				page footer start here\n				-->\n				\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n					<tbody>\n						<tr>\n							<td style=\"height:30px;\"></td>\n						</tr>\n						<tr>\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n									<tbody>\n										<tr>\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\n												<a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n						<tr>\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n									<tbody>\n										<tr>\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\n												<br />\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\n                                      \n                                  </td>\n										</tr>\n									</tbody>\n								</table></td>\n						</tr>\n						<tr>\n							<td style=\"padding:0; height:50px;\"></td>\n						</tr>\n					</tbody>\n				</table>\n				<!--\n				page footer end here\n				-->\n				</td>\n		</tr>\n	</tbody>\n</table>', '{learner_name}<br />\r\n{teacher_name}<br />\r\n{lesson_name}<br />\r\n{teacher_comment}<br />\r\n{lesson_date}<br />\r\n{lesson_start_time}<br />\r\n{lesson_end_time}<br />\r\n{action}<br />', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_failed_giftcard_attempts`
--

CREATE TABLE `tbl_failed_giftcard_attempts` (
  `giftcard_attempt_user_id` int(11) NOT NULL,
  `giftcard_attempt_code` varchar(15) NOT NULL,
  `giftcard_attempt_ip` varchar(16) NOT NULL,
  `giftcard_attempt_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_failed_login_attempts`
--

CREATE TABLE `tbl_failed_login_attempts` (
  `attempt_username` varchar(150) NOT NULL,
  `attempt_ip` varchar(50) NOT NULL,
  `attempt_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq`
--

CREATE TABLE `tbl_faq` (
  `faq_id` int(11) NOT NULL,
  `faq_identifier` varchar(255) NOT NULL,
  `faq_category` tinyint(1) NOT NULL,
  `faq_active` tinyint(1) NOT NULL,
  `faq_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq_categories`
--

CREATE TABLE `tbl_faq_categories` (
  `faqcat_id` int(11) NOT NULL,
  `faqcat_identifier` varchar(150) NOT NULL,
  `faqcat_active` tinyint(1) NOT NULL,
  `faqcat_type` tinyint(4) NOT NULL,
  `faqcat_deleted` tinyint(1) NOT NULL,
  `faqcat_display_order` int(11) NOT NULL,
  `faqcat_featured` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq_categories_lang`
--

CREATE TABLE `tbl_faq_categories_lang` (
  `faqcatlang_faqcat_id` int(11) NOT NULL,
  `faqcatlang_lang_id` int(11) NOT NULL,
  `faqcat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq_lang`
--

CREATE TABLE `tbl_faq_lang` (
  `faqlang_faq_id` int(11) NOT NULL,
  `faqlang_lang_id` int(11) NOT NULL,
  `faq_title` varchar(255) NOT NULL,
  `faq_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_flashcards`
--

CREATE TABLE `tbl_flashcards` (
  `flashcard_id` int(11) NOT NULL,
  `flashcard_user_id` int(11) NOT NULL COMMENT 'for whom',
  `flashcard_created_by_user_id` int(11) NOT NULL,
  `flashcard_slanguage_id` int(11) NOT NULL,
  `flashcard_title` varchar(255) NOT NULL,
  `flashcard_defination` varchar(255) NOT NULL,
  `flashcard_defination_slanguage_id` int(11) NOT NULL,
  `flashcard_pronunciation` varchar(255) NOT NULL,
  `flashcard_notes` text NOT NULL,
  `flashcard_accuracy` tinyint(2) NOT NULL,
  `flashcard_accuracy_added_on` datetime NOT NULL,
  `flashcard_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_giftcard_buyers`
--

CREATE TABLE `tbl_giftcard_buyers` (
  `gcbuyer_op_id` int(10) NOT NULL,
  `gcbuyer_order_id` varchar(15) NOT NULL,
  `gcbuyer_name` varchar(200) NOT NULL,
  `gcbuyer_email` varchar(100) NOT NULL,
  `gcbuyer_phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_giftcard_recipients`
--

CREATE TABLE `tbl_giftcard_recipients` (
  `gcrecipient_op_id` int(11) NOT NULL,
  `gcrecipient_email` varchar(100) NOT NULL,
  `gcrecipient_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gift_cards`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_group_classes`
--

CREATE TABLE `tbl_group_classes` (
  `grpcls_id` int(11) NOT NULL,
  `grpcls_slanguage_id` int(11) NOT NULL,
  `grpcls_title` varchar(255) NOT NULL,
  `grpcls_description` mediumtext NOT NULL,
  `grpcls_teacher_id` int(11) NOT NULL,
  `grpcls_max_learner` int(11) NOT NULL,
  `grpcls_entry_fee` float(10,2) NOT NULL,
  `grpcls_start_datetime` datetime NOT NULL,
  `grpcls_end_datetime` datetime NOT NULL,
  `grpcls_added_on` datetime NOT NULL DEFAULT current_timestamp(),
  `grpcls_status` int(11) NOT NULL,
  `grpcls_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_issues_reported`
--

CREATE TABLE `tbl_issues_reported` (
  `issrep_id` bigint(20) NOT NULL,
  `issrep_is_for_admin` int(11) NOT NULL DEFAULT 0,
  `issrep_slesson_id` int(11) NOT NULL,
  `issrep_reported_by` int(11) NOT NULL,
  `issrep_issues_to_report` varchar(255) NOT NULL,
  `issrep_comment` varchar(255) NOT NULL,
  `issrep_status` tinyint(4) NOT NULL,
  `issrep_issues_resolve` varchar(255) NOT NULL,
  `issrep_issues_resolve_type` int(11) NOT NULL,
  `issrep_resolve_comments` longtext NOT NULL,
  `issrep_added_on` datetime NOT NULL DEFAULT current_timestamp(),
  `issrep_updated_on` datetime NOT NULL,
  `issrep_escalated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_issue_report_options`
--

CREATE TABLE `tbl_issue_report_options` (
  `tissueopt_id` int(255) NOT NULL,
  `tissueopt_identifier` varchar(255) NOT NULL,
  `tissueopt_display_order` int(11) NOT NULL,
  `tissueopt_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_issue_report_options`
--

INSERT INTO `tbl_issue_report_options` (`tissueopt_id`, `tissueopt_identifier`, `tissueopt_display_order`, `tissueopt_active`) VALUES
(1, 'Student was late', 0, 1),
(2, 'Student was absent', 0, 1),
(3, 'Student left early', 0, 1),
(4, 'Teacher was absent', 0, 1),
(5, 'Teacher was late', 0, 1),
(6, 'Teacher left early', 0, 1),
(7, 'Student related technical difficulties', 0, 1),
(8, 'Teacher related technical difficulties', 0, 1),
(9, 'Site related technical difficulties', 0, 1),
(10, 'Lesson status should be Completed', 0, 1),
(11, 'other', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_issue_report_options_lang`
--

CREATE TABLE `tbl_issue_report_options_lang` (
  `tissueoptlang_tissueopt_id` int(11) NOT NULL,
  `tissueoptlang_lang_id` int(11) NOT NULL,
  `tissueoptlang_title` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_issue_report_options_lang`
--

INSERT INTO `tbl_issue_report_options_lang` (`tissueoptlang_tissueopt_id`, `tissueoptlang_lang_id`, `tissueoptlang_title`) VALUES
(1, 1, 'Student was late'),
(1, 2, 'كان الطالب متأخرا'),
(2, 1, 'Student was absent'),
(2, 2, 'Student was absent'),
(3, 1, 'Student left early'),
(3, 2, 'Student left early'),
(4, 1, 'Teacher was absent'),
(4, 2, 'Teacher was absent'),
(5, 1, 'Teacher was late'),
(5, 2, 'Teacher was late'),
(6, 1, 'Teacher left early'),
(6, 2, 'Teacher left early'),
(7, 1, 'Student related technical difficulties'),
(7, 2, 'Student related technical difficulties'),
(8, 1, 'Teacher related technical difficulties'),
(8, 2, 'Teacher related technical difficulties'),
(9, 1, 'Site related technical difficulties'),
(9, 2, 'Site related technical difficulties'),
(10, 1, 'Lesson status should be Completed'),
(10, 2, 'Lesson status should be Completed'),
(11, 1, 'other'),
(11, 2, 'other');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_languages`
--

CREATE TABLE `tbl_languages` (
  `language_id` int(11) NOT NULL,
  `language_code` varchar(4) NOT NULL,
  `language_flag` varchar(100) NOT NULL,
  `language_name` varchar(100) NOT NULL,
  `language_active` tinyint(1) NOT NULL DEFAULT 1,
  `language_css` varchar(10) NOT NULL,
  `language_layout_direction` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_languages`
--

INSERT INTO `tbl_languages` (`language_id`, `language_code`, `language_flag`, `language_name`, `language_active`, `language_css`, `language_layout_direction`) VALUES
(1, 'EN', 'gb.png', 'English', 1, '', 'ltr'),
(2, 'AR', 'ar.png', 'Arabic (عربى)', 1, '', 'rtl');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language_labels`
--

CREATE TABLE `tbl_language_labels` (
  `label_id` int(11) NOT NULL,
  `label_key` varchar(255) NOT NULL,
  `label_lang_id` int(11) NOT NULL,
  `label_caption` text NOT NULL
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
(69, 'MSG_YOUR_PASSWORD_RESET_INSTRUCTIONS_TO_YOUR_EMAIL', 1, 'The password reset instructions has been sent to your registered email address'),
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
(175, 'LBL_Site_Language', 2, 'اختر لغة الموقع'),
(176, 'LBL_Social', 2, 'اجتماعي'),
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
(288, 'LBL_Export', 2, 'تصدير'),
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
(414, 'LBL_Processing...', 2, 'معالجة...'),
(415, 'LBL_Request_Processing...', 2, 'طلب قيد التنفيذ ...'),
(416, 'LBL_is_mandatory', 2, 'إلزامي'),
(417, 'LBL_Please_enter_valid_email_ID_for', 2, 'الرجاء إدخال بريد إلكتروني صالح إيد ل'),
(418, 'VLBL_Only_characters_are_supported_for', 2, 'أحرف فقط معتمدة ل'),
(419, 'VLBL_Please_enter_integer_value_for', 2, 'الرجاء إدخال عدد صحيح القيمة ل'),
(420, 'VLBL_Please_enter_numeric_value_for', 2, 'الرجاء إدخال الرقمية القيمة لل'),
(421, 'VLBL_must_start_with_a_letter_and_can_contain_only_alphanumeric_characters._Length_must_be_between_4_to_20_characters', 2, 'يجب أن تبدأ بحرف ويمكن أن تحتوي أحرف أبجدية رقمية. طول يجب أن يكون بين ٤ إلى ٢٠ حرفا'),
(422, 'VLBL_Length_Must_be_between_6_to_20_characters', 2, 'طول يجب أن يكون بين ٦ إلى٢٠ حرفا'),
(423, 'VLBL_Length_Invalid_value_for', 2, 'طول القيمة لغير صالح'),
(424, 'VLBL_should_not_be_same_as', 2, 'يجب أن لا يكون نفس'),
(425, 'VLBL_must_be_same_as', 2, 'يجب أن يكون نفس'),
(426, 'VLBL_must_be_greater_than_or_equal_to', 2, 'يجب أن أكبر من أو تساوي'),
(427, 'VLBL_must_be_greater_than', 2, 'يجب أن أكبر من'),
(428, 'VLBL_must_be_less_than_or_equal_to', 2, 'يجب أن يكون أقل من أو تساوي'),
(429, 'VLBL_must_be_less_than', 2, 'يجب أن يكون أقل من'),
(430, 'VLBL_Length_of', 2, 'طول'),
(431, 'VLBL_Value_of', 2, 'قيمة ال'),
(432, 'VLBL_must_be_between', 2, 'يجب ان يكون وسطا'),
(433, 'VLBL_and', 2, 'و'),
(434, 'VLBL_Please_select', 2, 'الرجاء الإختيار'),
(435, 'LBL_Logout', 2, 'تسجيل خروج'),
(436, 'MSG_Back_To_Home', 2, 'العودة إلى المنزل'),
(437, 'LBL_LOGIN', 2, 'تسجيل الدخول'),
(438, 'LBL_Sign_Up', 2, 'سجل'),
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
(648, 'LBL_Do_you_want_to_remove', 2, 'هل تريد إزالة'),
(649, 'LBL_Apply_to_teach', 2, 'تطبيق لتعليم'),
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
(661, 'M_Single_Lesson_Rate', 1, 'Single Lesson Rate (USD) (Each lesson is one hour)'),
(662, 'M_Bulk_Lesson_Rate', 1, 'Single Lesson Rate When Purchase in Bulk (USD)'),
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
(748, 'LBL_Email', 2, 'البريد الإلكتروني'),
(749, 'LBL_EMAIL_ADDRESS', 2, 'عنوان البريد الالكترونى'),
(750, 'LBL_Password', 2, 'كلمه السر'),
(751, 'LBL_Remember_Me', 2, 'تذكرنى'),
(752, 'LBL_Sign_in_with_Facebook', 2, 'قم بتسجيل الدخول باستخدام الفيسبوك'),
(753, 'LBL_Sign_in_with_Google+', 2, 'تسجيل الدخول باستخدام + Google'),
(754, 'LBL_Or', 2, 'أو'),
(755, 'LBL_Forgot_Password?', 2, 'هل نسيت كلمة المرور؟'),
(756, 'LBL_Don\'t_have_an_account?', 2, 'لا تملك حسابا؟'),
(759, 'LBL_Username_or_email', 2, 'اسم المستخدم أو البريد الالكتروني'),
(760, 'BTN_SUBMIT', 2, 'إرسال'),
(761, 'LBL_Settings', 2, 'الإعدادات'),
(762, 'LBL_Profile', 2, 'الملف الشخصي'),
(763, 'LBL_Account_Information', 2, 'معلومات الحساب'),
(764, 'LBL_General_Info', 2, 'معلومات عامة'),
(765, 'LBL_Loading..', 2, 'جار التحميل..'),
(766, 'LBL_Username', 2, 'اسم المستخدم'),
(767, 'LBL_About_Me', 2, 'عني'),
(768, 'LBL_Country', 2, 'بلد'),
(769, 'LBL_Select', 2, 'تحديد'),
(770, 'LBL_SAVE_CHANGES', 2, 'حفظ التغييرات'),
(771, 'LBL_Profile_Picture', 2, 'الصوره الشخصيه'),
(772, 'LBL_Update_Profile_Picture', 2, 'صورة الملف تحديث'),
(773, 'LBL_Rotate_Left', 2, 'استدر يسارا'),
(774, 'LBL_Rotate_Right', 2, 'استدارة لليمين'),
(775, 'LBL_Add', 2, 'إضافة'),
(776, 'LBL_Edit', 2, 'تعديل'),
(777, 'LBL_Profile_Image', 2, 'صورة البروفيل'),
(778, 'LBL_Change', 2, 'يتغيرون'),
(779, 'LBL_Remove', 2, 'إزالة'),
(780, 'LBL_Full_Name', 2, 'الاسم الكامل'),
(781, 'LBL_Email_ID', 2, 'عنوان الايميل'),
(782, 'MSG_Valid_password', 2, 'صحيح كلمة المرور'),
(783, 'LBL_I_accept_Terms_&_Conditions', 2, 'I قبول الشروط والأحكام'),
(784, 'MSG_Terms_Condition_is_mandatory.', 2, 'شروط الشرط إلزامي.'),
(785, 'LBL_Register', 2, 'تسجيل'),
(786, 'LBL_Already_have_an_account?', 2, 'هل لديك حساب؟'),
(787, 'LBL_Sign_In', 2, 'تسجيل الدخول'),
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
(842, 'MSG_That_captcha_was_incorrect', 1, 'The entered captcha is incorrect. '),
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
(888, 'LBL_Facebook', 2, 'موقع التواصل الاجتماعي الفيسبوك'),
(889, 'LBL_Google+', 2, 'في + Google'),
(890, 'MSG_VERIFICATION_EMAIL_HAS_BEEN_SENT_AGAIN', 1, 'Verification Email Has Been Sent Again'),
(891, 'MSG_EMAIL_VERIFIED_SUCCESFULLY', 1, 'Email Verified Successfully, please Login to Continue.'),
(892, 'LBL_Preference_Identifier', 1, 'Preference Identifier'),
(893, 'LBL_Manage_Preferences', 1, 'Manage Preferences'),
(894, 'LBL_Preferences', 1, 'Preferences'),
(895, 'LBL_Preferences_Accents__Listing', 1, 'Preferences Accents  Listing'),
(896, 'LBL_Add_Preferences', 1, 'Add Preferences'),
(897, 'LBL_Preference_Title', 1, 'Preference Title');
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(898, 'LBL_Preferences___Listing', 1, 'Preferences   Listing'),
(899, 'LBL_Preferences_Teaches_Level__Listing', 1, 'Preferences Teaches Level  Listing'),
(900, 'LBL_Preferences_Test_Preparations__Listing', 1, 'Preferences Test Preparations  Listing'),
(901, 'LBL_Find_a_Teacher', 2, 'العثور على معلم'),
(902, 'LBL_Teaches:Select_Language', 2, 'يعلم: اختيار اللغة'),
(903, 'LBL_Availablity', 2, 'التوافر'),
(904, 'LBL_Search_Teacher\'s_Name', 2, 'بحث اسم المعلم'),
(905, 'LBL_Showing', 2, 'عرض'),
(906, 'LBL_of', 2, 'من'),
(907, 'LBL_teachers', 2, 'معلمون'),
(908, 'LBL_Sort_By_Popularity', 2, 'الترتيب حسب الشعبية'),
(909, 'LBL_Sort_By_Price_Low_to_High', 2, 'الترتيب حسب السعر الأدنى إلى الأعلى'),
(910, 'LBL_Sort_By_Price_High_to_Low', 2, 'فرز حسب السعر الاعلى الى الادنى'),
(911, 'LBL_Filters', 2, 'مرشحات'),
(912, 'LBL_Teacher_Speakes', 2, 'المعلم سبيكس'),
(913, 'LBL_From', 2, 'من عند'),
(914, 'LBL_Gender', 2, 'جنس'),
(915, 'LBL_Male', 2, 'الذكر'),
(916, 'LBL_Female', 2, 'أنثى'),
(917, 'LBL_Want_to_be_a_teacher?', 2, 'تريد أن تصبح معلمة؟'),
(918, 'LBL_If_you\'re_interested_in_being_a_teacher_on_{sitename},_please_apply_here.', 2, 'إذا كنت ترغب في أن يكون المعلم على {اسم الموقع}، الرجاء تطبيق هنا.'),
(919, 'LBL_Apply_to_be_a_teacher', 2, 'تطبيق ليكون المعلم'),
(920, 'LBL_Total_Beginner', 2, 'إجمالي المبتدئين'),
(921, 'LBL_Beginner', 2, 'مبتدئ'),
(922, 'LBL_Upper_Beginner', 2, 'العليا للمبتدئين'),
(923, 'LBL_Intermediate', 2, 'متوسط'),
(924, 'LBL_Upper_Intermediate', 2, 'وسيط ذو مستوي رفيع'),
(925, 'LBL_Advanced', 2, 'المتقدمة'),
(926, 'LBL_Upper_Advanced', 2, 'العلوي المتقدم'),
(927, 'LBL_Native', 2, 'محلي'),
(928, 'LBL_Hourly_Rate', 2, 'المعدل بالساعة'),
(929, 'LBL_Favorite', 2, 'مفضل'),
(930, 'LBL_Teaches', 2, 'يعلم'),
(931, 'LBL_Lessons', 2, 'الدروس'),
(932, 'LBL_Students', 2, 'الطلاب'),
(933, 'LBL_Speaks', 2, 'يتحدث'),
(934, 'LBL_Availability', 2, 'توفر'),
(935, 'LBL_Message', 2, 'رسالة'),
(936, 'LBL_No_Result_found!!', 2, 'لم يتم العثور على نتائج!!'),
(937, 'LBL_Find_a_Teacher', 1, 'Find A Teacher'),
(938, 'LBL_Weekly_Schedule', 1, 'Weekly Schedule'),
(939, 'LBL_Marked_Unavailable_successfully!', 1, 'Marked Unavailable Successfully!'),
(940, 'LBL_General_Availability_Note', 1, 'General Availability Note'),
(941, 'LBL_Teaches:Select_Language', 1, 'Teaches : select Language'),
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
(995, 'LBL_Issue_Reported', 1, 'Report an Issue'),
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
(1099, 'Lbl_yoCoach', 1, 'Yocoach'),
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
(1127, 'Lbl_yoCoach', 2, 'Yocoach'),
(1128, 'Lbl_Blog', 2, 'مدونة'),
(1129, 'Lbl_Faq', 2, 'التعليمات'),
(1130, 'Lbl_About', 2, 'حول'),
(1131, 'Lbl_Privacy', 2, 'خصوصية'),
(1132, 'Lbl_Contact', 2, 'اتصل'),
(1133, 'ERR_INVALID_USERNAME_OR_PASSWORD', 2, 'خطأ في اسم المستخدم أو كلمة مرور'),
(1134, 'MSG_LOGIN_SUCCESSFULL', 2, 'تسجيل الدخول النجاح'),
(1135, 'LBL_Dashboard', 2, 'لوحة القيادة'),
(1136, 'LBL_Flashcards', 2, 'البطاقات التعليمية'),
(1137, 'LBL_Wallet', 2, 'محفظة نقود'),
(1138, 'LBL_Orders', 2, 'الطلب'),
(1139, 'LBL_Reports', 2, 'تقارير'),
(1140, 'LBL_General', 2, 'جنرال لواء'),
(1141, 'LBL_First_Name', 2, 'الاسم الاول'),
(1142, 'LBL_Last_Name', 2, 'الكنية'),
(1143, 'LBL_Phone', 2, 'هاتف'),
(1144, 'LBL_TimeZone', 2, 'وحدة زمنية'),
(1145, 'LBL_Biography', 2, 'سيرة شخصية'),
(1146, 'LBL_Personal_Information', 2, 'معلومات شخصية'),
(1147, 'LBL_Change_Avataar', 2, 'تغيير Avataar'),
(1148, 'LBL_Upload', 2, 'رفع'),
(1149, 'LBL_Home', 2, 'الصفحة الرئيسية'),
(1150, 'LBL_Tutors', 2, 'الاولياء'),
(1151, 'LBL_Share', 2, 'شارك'),
(1152, 'LBL_Share_On', 2, 'مشاركه فى'),
(1153, 'LBL_Teaches:', 2, 'يعلم:'),
(1154, 'LBL_Students:', 2, 'الطلاب:'),
(1155, 'LBL_Lessons:', 2, 'الدروس:'),
(1156, 'LBL_Accents', 2, 'لهجات'),
(1157, 'LBL_Teaches_Level', 2, 'يعلم مستوى'),
(1158, 'LBL_Learner_Ages', 2, 'المتعلم العصور'),
(1159, 'LBL_Subjects', 2, 'المواضيع'),
(1160, 'LBL_Test_Preparations', 2, 'الاستعدادات اختبار'),
(1161, 'LBL_Teaching_Expertise', 2, 'الخبرة التعليمية'),
(1162, 'LBL_Resume', 2, 'استئنف'),
(1163, 'LBL_FREE_Trail', 2, 'تجربة مجانية'),
(1164, 'LBL_Book_your_trial_FREE_for_30_Mins_only', 2, '                                                               احجز نسختك التجريبية المجانية لمدة ٣٠ دقيقة فقط'),
(1165, 'LBL_Book_Free_Trial', 2, 'كتاب التجريبي المجاني'),
(1166, 'LBL_Education', 2, 'التعليم'),
(1167, 'LBL_Certification', 2, 'شهادة'),
(1168, 'LBL_Work_Experience', 2, 'خبرة في العمل'),
(1169, 'MSG_Please_Enter_Valid_password', 2, 'الرجاء إدخال كلمة المرور صالح'),
(1170, 'LBL_I_accept_to_the', 2, 'I تحمل لو'),
(1171, 'LBL_Show_Password', 2, 'عرض كلمة المرور'),
(1172, 'LBL_Hide_Password', 2, 'اخفاء كلمة المرور'),
(1173, 'LBL_TERMS_AND_CONDITION', 2, 'أحكام وشروط'),
(1174, 'LBL_Comet_chat_Api_Key', 1, 'Comet Chat Api Key'),
(1175, 'LBL_Comet_Chat_App_ID', 1, 'Comet Chat App Id'),
(1176, 'LBL_Learner_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also!', 1, 'Learner Ends The Lesson Do You Want To End It From Your End Also!'),
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
(1232, 'MSG_learner_success_order_{dashboard-url}_{contact-us-page-url}', 2, '<p> تم تجهيز طلبك بنجاح! </ P> <p> ويمكنك عرض التاريخ طلبك عن طريق الذهاب إلى <a href=\"{dashboard-url}\"> صفحتي حساب </A>. </ P > <p> يرجى توجيه أي أسئلة لديك إلى <a href=\"{contact-us-page-url}\"> البوابة الإلكترونية مالك </A>. </ P> <p> وشكرا لاختيارك لنا على الانترنت!'),
(1233, 'MSG_learner_failure_order_{contact-us-page-url}', 1, '<p>There was a problem processing your payment and the order did not complete.</p>\n<p>Possible reasons are: </p>\n<ul>\n  <li>Insufficient funds</li>\n  <li>Verification failed</li>\n</ul>\n<p>Please try to order again using a different payment method.</p>\n<p>If the problem persists please <a href=\"{contact-us-page-url}\">contact us</a> with the details of the order you are trying to place.</p>'),
(1235, 'MSG_learner_failure_order_{contact-us-page-url}', 2, '<p> وكان هناك مشكلة معالجة الدفع الخاص بك والنظام لم يكتمل. </ P>\n<p> والأسباب المحتملة هي: </ P>\n<UL>\n  <li> والأموال غير كافية </ لى>\n  <li> وفشل التأكيد </ لى>\n</ UL>\n<p> يرجى يحاول النظام مرة أخرى باستخدام طريقة دفع أخرى. </ P>\n<p> وإذا كان قائما مشكلة الرجاء الاتصال <a href=\"{contact-us-page-url}\"> لنا </A> بتفاصيل النظام الذي تحاول المكان. </ P>'),
(1236, 'LBL_-NA-', 1, 'NA'),
(1237, 'LBL_This_lesson_is_Unscheduled._Encourage_your_student_to_schedule_it.', 1, 'This Lesson Is Unscheduled. Encourage Your Student To Schedule It.'),
(1238, 'LBL_These_prices_are_Unlocked', 1, 'These Prices Are Unlocked'),
(1239, 'LBL_Your_Name', 1, 'Your Name'),
(1240, 'LBL_Your_Email', 1, 'Your Email'),
(1241, 'LBL_Your_Phone', 1, 'Your Phone'),
(1242, 'LBL_Your_Message', 1, 'Your Message'),
(1243, 'LBL_Send_us_a_message', 1, 'Send Us A Message'),
(1244, 'LBL_My_Teachers', 2, 'أساتذتي'),
(1245, 'LBL_Search', 2, 'بحث'),
(1246, 'LBL_Search_by_keyword', 2, 'البحث بواسطة كلمة'),
(1247, 'LBL_Status', 2, 'الحالة'),
(1248, 'LBL_Learner', 2, 'متعلم'),
(1249, 'LBL_Price_Single/Bulk', 2, 'سعر واحدة / السائبة'),
(1250, 'LBL_Scheduled', 2, 'المقرر'),
(1251, 'LBL_Past', 2, 'الماضي'),
(1252, 'LBL_Unscheduled', 2, 'غير المجدولة'),
(1253, 'LBL_Actions', 2, 'أجراءات'),
(1254, 'LBL_Search_Again', 2, 'ابحث ثانيا'),
(1255, 'LBL_Reserve_a_Session', 2, '                                                                                                        الدورة الاحتياطي'),
(1256, 'LBL_Per_Hour', 2, 'في الساعة'),
(1257, 'LBL_Book_Now', 2, 'احجز الآن'),
(1258, 'LBL_Date:', 2, 'تاريخ:'),
(1259, 'LBL_Time:', 2, 'زمن:'),
(1260, 'LBL_Price:', 2, 'السعر:'),
(1261, 'LBL_Book_Lesson', 2, 'الدرس كتاب'),
(1262, 'LBL_Redirecting_in_3_seconds.', 2, '                                                                                                 إعادة توجيه في ٣ ثوان.'),
(1263, 'LBL_Confirm_Order', 2, 'أكد الطلب'),
(1264, 'LBL_Checkout', 2, 'الدفع'),
(1265, 'LBL_Payment', 2, 'دفع'),
(1266, 'LBL_Payment_to_be_made', 2, 'على أن يتم الدفع'),
(1267, 'LBL_Cart', 2, 'عربة التسوق'),
(1268, 'LBL_Product', 2, 'المنتج'),
(1269, 'LBL_Price', 2, 'السعر'),
(1270, 'LBL_Total', 2, 'مجموع'),
(1271, 'LBL_Lessons_Packages', 2, 'الدروس الحزم'),
(1272, 'LBL_How_many_lessons_would_you_like_to_purchase?', 2, 'كم عدد الدروس تريد أن شراء؟'),
(1273, 'LBL_Pick_a_payment_method.', 2, 'اختيار طريقة الدفع.'),
(1274, 'LBL_Confirm_Payment', 2, 'تأكيد الدفع'),
(1275, 'LBL_Pay_using_{payment-method-name}', 2, 'دفع عن طريق {دفع طريقة اسم}'),
(1276, 'LBL_Net_Payable_:', 2, 'صافي المدفوع :'),
(1277, 'LBL_Need_to_be_scheduled', 2, 'الحاجة لتحديد موعد'),
(1278, 'LBL_Completed', 2, 'منجز'),
(1279, 'LBL_Cancelled', 2, 'ألغيت'),
(1280, 'LBL_Issue_Reported', 2, 'بلغ عن خطأ'),
(1281, 'LBL_Upcoming', 2, 'القادمة'),
(1282, 'LBL_My_Lessons', 2, 'بلدي الدروس'),
(1283, 'LBL_List', 2, 'قائمة'),
(1284, 'LBL_Calender', 2, 'درج في لائحة'),
(1285, 'MSG_EMAIL_VERIFIED_SUCCESFULLY', 2, 'البريد الإلكتروني التحقق بنجاح، يرجى الدخول إلى متابعة.'),
(1286, 'LBL_Last_Reviewed', 1, 'Last Reviewed'),
(1287, 'LBL_Review_All_Flashcards', 1, 'Review All Flashcards'),
(1288, 'LBL_Teacher_Free_Trial_Booked_for_Order:_{order-id}', 1, 'Teacher Free Trial Booked For Order: {order-id}'),
(1289, 'LBL_User_Wallet', 1, 'User Wallet'),
(1290, 'LBL_Payment_From_User_Wallet', 1, 'Payment From User Wallet'),
(1291, 'LBL_Congratulations', 2, 'تهانينا'),
(1292, 'MSG_Teacher_booking_selection_is_not_yet_been_selected,_Please_try_selecting_the_appropriate_teacher_and_start_booking_lesson.', 2, 'المعلم الحجز اختيار ليست بعد تم اختيارها، الرجاء حاول اختيار المناسب المعلم وتبدأ الحجز الدرس.'),
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
(1314, 'LBL_General_Queries', 2, 'استفسارات عامة'),
(1315, 'LBL_Application_/_Requirements', 2, 'متطلبات الاستمارة'),
(1316, 'LBL_Payments', 2, 'المدفوعات'),
(1317, 'LBL_Courses', 2, 'الدورات'),
(1318, 'LBL_Lesson_Plan', 2, 'خطة الدرس'),
(1319, 'LBL_See_All_3_Articles', 2, '                                                                                                   رؤية جميع ٣ المقالات '),
(1320, 'LBL_See_All_1_Articles', 2, '                                                                                                   رؤية جميع المقالات ١'),
(1321, 'LBL_Your_Name', 2, 'اسمك'),
(1322, 'LBL_Your_Email', 2, 'بريدك الالكتروني'),
(1323, 'LBL_Your_Phone', 2, 'هاتفك'),
(1324, 'LBL_Your_Message', 2, 'رسالتك'),
(1325, 'LBL_Send_us_a_message', 2, 'أرسل لنا رسالة'),
(1326, 'MSG_Use_My_Wallet_Credits', 1, 'Use My Wallet Credits'),
(1327, 'LBL_Amount_in_your_wallet', 1, 'Amount In Your Wallet'),
(1328, 'LBL_Remaining_wallet_balance', 1, 'Remaining Wallet Balance'),
(1329, 'LBL_Select_an_option_to_pay_balance', 1, 'Select An Option To Pay Balance'),
(1330, 'LBL_Please_login_to_book', 2, 'الرجاء تسجيل الدخول للكتاب'),
(1331, 'LBL_My_Flashcards', 2, 'بلدي البطاقات التعليمية'),
(1332, 'LBL_Add_Flashcard', 2, 'إضافة بطاقات التعليمية'),
(1333, 'LBL_All_Languages', 2, 'كل اللغات'),
(1334, 'LBL_Last_Reviewed', 2, 'التعليق الأخير'),
(1335, 'LBL_Review_All_Flashcards', 2, 'مشاركة كل البطاقات التعليمية'),
(1336, 'LBL_Word', 2, 'كلمة'),
(1337, 'LBL_Definition', 2, 'تعريف'),
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
(1362, 'Lbl_Ratings', 2, 'تصنيفات'),
(1363, 'Lbl_Average', 2, 'معدل'),
(1364, 'MSG_Use_My_Wallet_Credits', 2, 'قروض المحفظة استخدام بلدي'),
(1365, 'LBL_Pay_Now', 2, 'ادفع الآن'),
(1366, 'LBL_Sufficient_balance_in_your_wallet', 2, 'الرصيد الكافي في محفظتك'),
(1367, 'LBL_Amount_in_your_wallet', 2, 'المبلغ في محفظتك'),
(1368, 'LBL_Remaining_wallet_balance', 2, 'الرصيد المتبقي محفظة'),
(1369, 'LBL_ORDER_PLACED_{order-id}', 1, 'Order Placed {order-id}'),
(1370, 'LBL_Schedule', 2, 'جدول'),
(1371, 'LBL_Details', 2, 'تفاصيل'),
(1372, 'LBL_View', 2, 'رأي'),
(1373, 'LBL_Reschedule', 2, 'إعادة جدولة'),
(1374, 'LBL_Cancel', 2, 'إلغاء'),
(1375, 'Lbl_Ratings', 1, 'Ratings'),
(1376, 'Lbl_Average', 1, 'Average'),
(1377, 'LBL_Pay_Now', 1, 'Pay Now'),
(1378, 'LBL_Sufficient_balance_in_your_wallet', 1, 'Sufficient Balance In Your Wallet'),
(1379, 'L_debited', 1, 'Debited'),
(1380, 'MSG_Reviews', 1, 'Reviews'),
(1381, 'LBL_Bible_Content', 1, 'Video  Content'),
(1382, 'LBL_Manage_Bible_Content', 1, 'Manage Video Content'),
(1383, 'LBL_Add_content', 1, 'Add Content'),
(1384, 'LBL_Bible', 1, 'video'),
(1385, 'LBL_Manage_Purchased_lessons', 1, 'Manage Purchased Lessons'),
(1386, 'LBL_Purchased_Lessons', 1, 'Purchased Lessons'),
(1387, 'LBL_Order_Id', 1, 'Order ID'),
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
(1478, 'LBL_Bible_Title', 1, 'video Title'),
(1479, 'LBL_Bible', 2, 'فيديو'),
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
(1512, 'LBL_Are_you_sure_to_unlock_this_price!', 2, 'هل أنت متأكد من إطلاق هذه الأسعار!'),
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
(1527, 'LBL_My_Students', 2, 'طلابي'),
(1528, 'LBL_Buy_Giftcard', 2, 'يبيع جيفتكارد'),
(1529, 'LBL_Messages', 2, 'رسائل'),
(1530, 'LBL_Teacher', 2, 'مدرس'),
(1531, 'MSG_Setup_successful', 2, 'الإعداد الناجح'),
(1532, 'LBL_Teacher_Reviews', 1, 'Teacher Reviews'),
(1533, 'LBL_Manage_Teacher_Reviews', 1, 'Manage Teacher Reviews'),
(1534, 'LBL_Favourites', 1, 'Favourites'),
(1535, 'MSG_INVALID_FILE_MIME_TYPE', 1, 'Invalid File Mime Type'),
(1536, 'LBL_Set_Up_Flashcard', 1, 'Set Up Flashcard'),
(1537, 'LBL_Apply_Coupon', 1, 'Apply Coupon'),
(1538, 'LBL_Availiblity', 1, 'Availability'),
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
(1581, 'LBL_Both-Debit/Credit', 2, 'كلا، الخصم / الائتمان'),
(1582, 'LBL_Credit', 2, 'ائتمان'),
(1583, 'LBL_Debit', 2, 'مدين'),
(1584, 'LBL_Reset', 2, 'إعادة تعيين'),
(1585, 'LBL_Enter_Amount_To_Be_Added_[{site-currency-symbol}]', 2, 'أدخل المبلغ المطلوب إضافتها [{العملة رمز الموقع}]'),
(1586, 'LBL_Add_Money_to_account', 2, 'إضافة حساب لأموال'),
(1587, 'LBL_Favourites', 2, 'المفضلة'),
(1588, 'LBL_My_Wallet', 2, 'محفظتي'),
(1589, 'LBL_Request_Withdrawal', 2, 'طلب السحب'),
(1590, 'Redeem_Gift_Card', 2, 'كرت هدية'),
(1591, 'LBL_Your_Wallet_Balance', 2, 'رصيد المحفظة'),
(1592, 'LBL_Make_Sure_To_Review_Your_Order_Details_Now.', 2, 'تأكد من مراجعة تفاصيل الطلب الخاص بك الآن.'),
(1593, 'LBL_Once_you_press_\'Add_Money\'_you\'ll_be_directed_to_payment_page_to_enter_your_payment_information_and_process_the_order', 2, 'بمجرد الضغط \"إضافة أموال\" سيتم توجيهك إلى صفحة الدفع لإدخال معلومات الدفع ومعالجة الأمر'),
(1594, 'LBL_Keyword', 2, '                                                                                                          الكلمة الرئيسية     '),
(1595, 'LBL_From_Date', 2, 'من التاريخ'),
(1596, 'LBL_To_Date', 2, 'حتى الآن'),
(1597, 'LBL_Search_Transactions', 2, 'البحث المعاملات'),
(1598, 'LBL_Transaction_Pending', 2, 'عملية الانتظار'),
(1599, 'LBL_Transaction_Completed', 2, 'عملية الانتهاء'),
(1600, 'LBL_Txn_ID', 2, 'TXN رقم'),
(1601, 'LBL_Date', 2, 'تاريخ'),
(1602, 'LBL_Balance', 2, 'توازن'),
(1603, 'LBL_Comments', 2, 'تعليقات'),
(1604, 'Label_More', 1, 'More'),
(1605, 'LBL_All_Tutors', 1, 'All Tutors'),
(1606, 'LBL_All_Date_&_Times_are_showing_in_{time-zone-abbr},_Current_Date_&_Time:_{current-date-time}', 1, 'All Date & Times Are Showing In {time-zone-abbr}, Current Date & Time: {current-date-time}'),
(1607, 'Label_More', 2, 'أكثر'),
(1608, 'LBL_All_Tutors', 2, 'جميع المعلمين'),
(1609, 'LBL_All_Date_&_Times_are_showing_in_{time-zone-abbr},_Current_Date_&_Time:_{current-date-time}', 2, 'جميع الأوقات التاريخ ووتظهر في {الوقت منطقة ابر}، التاريخ الحالي والوقت: {الحالي التاريخ الوقت}'),
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
(1642, 'MSG_VERIFICATION_EMAIL_SENT', 1, 'Verification Email Sent, please check your mailbox and confirm your Email ID to proceed. Kindly check the spam folder as well.'),
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
(1705, 'LBL_Availiblity', 2, 'توفر'),
(1706, 'LBL_Sunday', 2, 'الأحد'),
(1707, 'LBL_Monday', 2, 'الإثنين'),
(1708, 'LBL_Tuesday', 2, 'الثلاثاء'),
(1709, 'LBL_Wednesday', 2, 'الأربعاء'),
(1710, 'LBL_Thursday', 2, 'الخميس'),
(1711, 'LBL_Friday', 2, 'يوم الجمعة'),
(1712, 'LBL_Saturday', 2, 'يوم السبت'),
(1713, 'LBL_Request_Processing..', 2, 'طلب تجهيز ..'),
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
(1742, 'LBL_Schedule_a_Session', 2, 'جلسة عمل جدول'),
(1743, 'LBL_Schedule_Session', 2, 'الدورة الجدول الزمني'),
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
(1774, 'LBL_Mobile_logo_size', 1, 'Mobile Logo Size');
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(1775, 'LBL_Price_Locked_Successfully!', 1, 'Price Locked Successfully!'),
(1776, 'Lbl_On_Date', 1, 'On Date'),
(1777, 'LBL_Lesson_Updated_Successfully!', 1, 'Lesson Updated Successfully!'),
(1778, 'LBL_Lesson_Call_History', 1, 'Lesson Call History'),
(1779, 'LBL_This_lesson_is_completed.', 1, 'This Lesson Is Completed.'),
(1780, 'LBL_Add_Transactions', 1, 'Add Transactions'),
(1781, 'LBL_Giftcard_Redeem_To_Wallet', 1, 'Giftcard Redeem To Wallet'),
(1782, 'MSG_Giftcard_Redeem_successfully', 1, 'Giftcard Redeem Successfully'),
(1783, 'LBL_GIFTCARD_USED', 1, 'Giftcard Used'),
(1785, 'LBL_NOTE:_Allowed_Certificate_Extentions!', 2, 'ملاحظة: سمح تمديد قوات الدفاع الشعبي، DOC و XLS، TXT.'),
(1788, 'LBL_User_Profile_Image_Dimension', 1, 'User Profile Image Dimension'),
(1789, 'ERR_LINK_IS_INVALID_OR_EXPIRED', 1, 'Link Is Invalid Or Expired'),
(1790, 'MSG_UNRECOGNISED_IMAGE_FILE', 1, 'Unrecognised Image File'),
(1791, 'MSG_INVALID_FILE_EXTENSION', 1, 'Invalid File Extension'),
(1792, 'LBL_Price_Unlocked_Successfully!', 1, 'Price Unlocked Successfully!'),
(1793, 'Product_Detail_Page_Banner', 1, 'Detail Page Banner'),
(1794, 'LBL_Load_Previous', 1, 'Load Previous'),
(1796, 'LBL_GIFTCARD_USED', 2, 'جيفتكارد مستعمل'),
(1800, 'LBL_Gift_card', 2, 'كرت هدية'),
(1801, 'LBL_Seller', 1, 'Teacher'),
(1802, 'LBL_Commission_fees', 1, 'Commission Fees'),
(1803, 'LBL_Commission_Setup', 1, 'Commission Setup'),
(1804, 'LBL_Commission_History', 1, 'Commission History'),
(1805, 'MSG_You_are_already_logged_in', 1, 'You have already logged in'),
(1806, 'LBL_Start_Year', 1, 'Start Year'),
(1807, 'LBL_End_Year', 1, 'End Year'),
(1808, 'LBL_Price::', 1, 'Price::'),
(1809, 'LBL_View_Availability', 1, 'View Availability'),
(1810, 'LBL_Photo_Id_Type', 1, 'Allowed Extension jpg,png'),
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
(1851, 'LBL_Language', 2, 'لغة'),
(1852, 'LBL_All', 2, 'الكل'),
(1853, 'Label_Notifications', 2, 'إشعارات'),
(1854, 'LBL_Action', 2, 'عمل'),
(1855, 'LBL_Determines_how_many_items_are_shown_per_page_(lesson_listing,_flashcards,_etc)', 1, 'Determines How Many Items Are Shown Per Page (lesson Listing, Flashcards, Etc)'),
(1856, 'LBL_See_all_Students', 1, 'See All Students'),
(1857, 'LBL_LessonId:_%s_Refund_Payment', 1, 'Lessonid: %s Refund Payment'),
(1858, 'LBL_Average_Rating', 1, 'Average Rating'),
(1859, 'LBL_Previous', 2, 'السابق'),
(1860, 'LBL_Next', 2, 'التالى'),
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
(1871, 'LBL_View_Availability', 2, 'مشاهدة التوفر'),
(1872, 'LBL_Weekly_Schedule', 2, 'الجدول الأسبوعي'),
(1873, 'LBL_Experience', 2, 'تجربة'),
(1874, 'LBL_Skills', 2, 'مهارات'),
(1875, 'LBL_Languages', 2, 'اللغات'),
(1876, 'M_Introduction_Video_Link', 2, 'مقدمة وصلة فيديو'),
(1877, 'LBL_The_place_where_we_write_some_words', 2, 'المكان الذي كنا كتابة بعض الكلمات'),
(1878, 'LBL_Blog_Search', 2, 'بلوق البحث'),
(1879, 'Lbl_on', 2, 'على'),
(1880, 'Lbl_View_Full_Post', 2, 'عرض مشاركة كاملة'),
(1881, 'LBL_See_more_at', 2, 'انظر أكثر في'),
(1882, 'LBL_Blog_Searchs', 2, 'مقالات التفتيش المستمر'),
(1883, 'LBL_Go_Back', 2, 'عد'),
(1884, 'Lbl_Write_For_Us', 2, 'إرسال لبنا'),
(1885, 'Lbl_We_are_constantly_looking_for_writers_and_contributors_to_help_us_create_great_content_for_our_blog_visitors.', 2, 'نحن باستمرار تبحث عن الكتاب والمساهمون لمساعدتنا على خلق عظيم المحتوى لزوارنا البلوغ.'),
(1886, 'Lbl_Contribute', 2, 'مساهمة'),
(1887, 'Lbl_Categories', 2, 'التصنيفات'),
(1888, 'Lbl_By', 2, 'بواسطة'),
(1889, 'Lbl_Comments(%s)', 2, '                                                                                                           تعليقات (٪ s)'),
(1890, 'LBL_Says:', 2, 'يقول:'),
(1891, 'Lbl_Invalid_Request', 2, 'طلب غير صالح'),
(1893, 'LBL_Reviews', 2, 'التعليقات'),
(1894, 'LBL_Teacher_Join_Time_Marked!', 1, 'Teacher Join Time Marked!'),
(1895, 'LBL_END_LESSON_DURATION', 1, 'End Lesson Duration'),
(1896, 'LBL_Duration_After_Teacher_Can_End_Lesson_(In_Minutes)', 1, 'Duration After Teacher Can End Lesson (in Minutes)'),
(1897, 'LBL_Cannot_End_Lesson_So_Early!', 1, 'You cannot end the lesson so early.'),
(1898, 'LBL_LEARNER_REFUND_PERCENTAGE', 1, 'Learner Refund Percentage'),
(1899, 'LBL_Refund_to_learner_In_Less_than_24_Hours_(In_Percentage)', 1, 'Refund To Learner In Less Than 24 Hours (in Percentage)'),
(1900, 'LBL_Correct', 2, 'صيح'),
(1901, 'LBL_Upper_Almost', 2, 'العلوي تقريبا'),
(1902, 'LBL_Wrong', 2, 'خطأ'),
(1903, 'LBL_Defination', 2, '                                                                                                                   تعريف'),
(1904, 'LBL_Click_On_Words_To_Flip_It', 2, 'انقر على الكلمات لفليب'),
(1905, 'LBL_FlashCard_Reviewed_Successfully!', 2, 'التعليق البطاقات التعليمية بنجاح!'),
(1906, 'LBL_Incorrect_Answers', 2, 'أجوبة صحيحة'),
(1907, 'LBL_Almost_Correct_Answers', 2, 'الإجابات الصحيحة تقريبا'),
(1908, 'LBL_Correct_Answers', 2, 'الإجابات الصحيحة'),
(1909, 'LBL_Close', 2, 'قريب'),
(1910, 'LBL_Restart', 2, 'اعادة البدء'),
(1911, 'LBL_Title', 2, 'عنوان'),
(1912, 'LBL_Title_Language', 2, 'العنوان اللغة'),
(1913, 'LBL_Defination_Language', 2, '                                                                                                            لغة التعريف'),
(1914, 'LBL_Pronunciation', 2, 'النطق'),
(1915, 'LBL_Notes', 2, 'ملاحظات'),
(1916, 'LBL_Save', 2, 'حفظ'),
(1917, 'LBL_Set_Up_Flashcard', 2, 'تعيين لأعلى بطاقات التعليمية'),
(1918, 'LBL_Does_Not_Matter', 2, 'لا يهم'),
(1919, 'LBL_Unused', 2, 'غير مستعمل'),
(1920, 'LBL_Used', 2, 'مستخدم'),
(1921, 'LBL_Clear', 2, 'واضح'),
(1922, 'LBL_Giftcards_Purchased', 2, 'تم شراء بطاقات الهدايا'),
(1923, 'LBL_Your_Detail', 2, 'التفاصيل الخاصة بك'),
(1924, 'LBL_Giftcard_Amount', 2, 'جيفتكارد المبلغ'),
(1925, 'LBL_Gift_card_Recipient_Detail', 2, 'بطاقة هدية مستلم التفاصيل'),
(1926, 'LBL_Order_id', 2, 'رقم التعريف الخاص بالطلب'),
(1927, 'LBL_Gift_Card_Code', 2, 'كود بطاقة هدية'),
(1928, 'LBL_Amount', 2, 'كمية'),
(1929, 'LBL_Recepient_Details', 2, 'تفاصيل متلق'),
(1933, 'LBL_Refund_to_learner_In_Less_than_24_Hours_(In_Percentage)', 2, '                                                               برد لالمتعلم في أقل من ٢٤ ساعة (في النسبة المئوية)'),
(1935, 'LBL_Manage_Labels', 2, 'إدارة التصنيفات'),
(1946, 'LBL_Location', 2, 'موقعك'),
(1947, 'LBL_Unfavorite', 2, 'إزالة من المفضلة'),
(1948, 'LBL_Teacher_has_been_removed_from_favourite_list', 2, 'المعلم تمت إزالته من قائمة الإهتمام'),
(1949, 'MSG_Session_seems_to_be_expired', 2, 'الدورة يبدو أن منتهية الصلاحية'),
(1950, 'LBL_Lesson(s)', 2, 'الدرس (ق)'),
(1951, 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson', 2, '{ن} دقائق من {أو المدفوع محاكمة} الدرس'),
(1952, 'LBL_Rate_Lesson', 2, 'معدل الدرس'),
(1953, 'LBL_Trial', 2, 'التجربة'),
(1954, 'LBL_Search_Flash_Cards...', 2, 'بحث بطاقات فلاش ...'),
(1955, 'LBL_Teacher_Has_Joined_Now_you_can_also_Join_The_Lesson!', 2, 'المعلم انضم الآن يمكنك أيضا تاريخ الدرس!'),
(1956, 'LBL_Teacher_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also', 2, 'المعلم ينتهي الدرس هل على أساس سنوي تريد لوضع حد لها من نهاية الخاص بك أيضا'),
(1957, 'LBL_Note', 2, 'ملحوظة'),
(1958, 'LBL_This_Lesson_has_been_cancelled._Schedule_more_lessons.', 2, 'هذا الدرس تم إلغاؤه. جدولة المزيد من الدروس.'),
(1959, 'LBL_Join_Lesson', 2, 'تاريخ الدرس'),
(1960, 'LBL_Go_to_Dashboard.', 2, 'الذهاب إلى لوحة القيادة.'),
(1961, 'LBL_Info', 2, 'معلومات'),
(1962, 'LBL_Learner_Details', 2, 'تفاصيل المتعلم'),
(1963, 'LBL_Teacher_Details', 2, 'تفاصيل المعلم'),
(1964, 'LBL_Lesson_Details', 2, 'تفاصيل الدرس'),
(1965, 'LBL_View_Lesson_Plan', 2, 'عرض خطة الدرس'),
(1966, 'LBL_End_Lesson', 2, 'الدرس نهاية'),
(1967, 'LBL_Add_New', 2, 'اضف جديد'),
(1968, 'LBL_Resume_Information', 2, 'استئناف معلومات'),
(1969, 'LBL_Start/End', 2, 'بداية النهاية'),
(1970, 'LBL_Uploaded_Certificate', 2, 'شهادة حملت'),
(1971, 'LBL_Institution', 2, 'المعهد'),
(1972, 'LBL_Delete', 2, 'حذف'),
(1973, 'LBL_Today', 2, 'اليوم'),
(1974, 'LBL_Last_Week', 2, 'الاسبوع الماضي'),
(1975, 'LBL_This_Month', 2, 'هذا الشهر'),
(1976, 'LBL_Last_Month', 2, 'الشهر الماضي'),
(1977, 'LBL_Last_Year', 2, 'العام الماضي'),
(1978, 'LBL_View_Profile', 2, 'عرض الصفحة الشخصية'),
(1979, 'LBL_Earnings', 2, 'أرباح'),
(1980, 'LBL_See_all_Students', 2, 'انظر جميع الطلاب'),
(1981, 'LBL_Attach_Lesson_Plan', 2, 'إرفاق خطة الدرس'),
(1982, 'LBL_Submit', 2, 'إرسال'),
(1983, 'MSG_Blog_Categories', 1, 'Blog Categories'),
(1984, 'MSG_Blog_Posts', 1, 'Blog Posts'),
(1985, 'MSG_Blog_Contributions', 1, 'Blog Contributions'),
(1986, 'MSG_Blog_Comments', 1, 'Blog Comments'),
(1987, 'MSG_Bible_Content', 1, 'Video Content'),
(1988, 'MSG_Manage_Purchased_lessons', 1, 'Manage Purchased Lessons'),
(1989, 'MSG_Manage_Issues_Reported', 1, 'Manage Issues Reported'),
(1990, 'MSG_GIFTCARDS', 1, 'Giftcards'),
(1991, 'MSG_Withdraw_Requests', 1, 'Withdraw Requests'),
(1992, 'MSG_Teacher_Reviews', 1, 'Teacher Reviews'),
(1993, 'MSG_Commission', 1, 'Commission'),
(1994, 'MSG_Sales_Report', 1, 'Sales Report'),
(1995, 'LBL_This_lesson_is_completed._rate_it.', 2, 'اكتمال هذا الدرس. قيمه.'),
(1996, 'LBL_Rate_it.', 2, 'قيمه.'),
(1997, 'MSG_Already_submitted_order_feedback', 2, 'ارسل بالفعل بالدفع تعليقات'),
(1998, 'LBL_Lesson', 2, 'درس'),
(1999, 'LBL_Accent', 2, 'لهجة'),
(2000, 'LBL_Presence', 2, 'حضور'),
(2001, 'LBL_Overall', 2, 'شاملة'),
(2002, 'L_Rate', 2, 'معدل'),
(2003, 'LBL_Description', 2, 'وصف'),
(2004, 'LBL_Send_Review', 2, 'إرسال مراجعة'),
(2005, 'LBL_Lesson_Feedback', 2, 'الدرس تعليقات'),
(2006, 'MSG_Feedback_Submitted_Successfully', 2, 'ردود الفعل قدمت بنجاح'),
(2007, 'LBL_Access_Denied', 2, 'تم الرفض'),
(2008, 'LBL_This_lesson_is_completed.', 2, 'اكتمال هذا الدرس.'),
(2009, 'LBL_My_Notifications', 2, 'الإشعارات الخاصة بي'),
(2010, 'LBL_Notification_TITLE', 2, 'عنوان الإخطار'),
(2011, 'LBL_Notification_Sent_ON', 2, 'إعلام المرسلة على'),
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
(2023, 'LBL_Seller', 2, 'مدرس'),
(2024, 'LBL_Type', 2, 'نوع'),
(2025, 'LBL_To', 2, 'إلى'),
(2026, 'LBL_View_Flashcards', 1, 'View Flashcards'),
(2027, 'MSG_Go_to_my_Lessons', 1, 'Go To My Lessons'),
(2028, 'Lbl_On_Date', 2, 'في تاريخ'),
(2029, 'LBL_Learner_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also!', 2, 'المتعلم ينتهي الدرس هل على أساس سنوي تريد لوضع حد لها من نهاية الخاص بك أيضا!'),
(2030, 'LBL_Duration_assigned_to_this_lesson_is_completed_now_do_you_want_to_continue?', 2, 'مدة المخصصة لهذا الدرس اكتمال الآن هل تريد المتابعة؟'),
(2031, 'LBL_Continue', 2, 'استمر'),
(2032, 'LBL_Teacher_Join_Time_Marked!', 2, 'مدرس تاريخ وقت المحددة!'),
(2033, 'LBL_See_All_Teachers', 1, 'See All Teachers'),
(2034, 'LBL_Lessons_Sold', 1, 'Lessons Sold'),
(2035, 'LBL_3_Articles', 1, '3 Articles'),
(2036, 'LBL_1_Articles', 1, '1 Articles'),
(2038, 'LBL_Note:_Enter_Number_of_lessons_in_a_package', 2, 'ملاحظة: أدخل عدد من الدروس في حزمة (في ساعة)'),
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
(2075, 'LBL_Photo_Id_Type', 2, 'يسمح تمديد JPG، PNG'),
(2076, 'LBL_No_Other_Plan_Avaiable!!', 1, 'No Other Plan Avaiable!!'),
(2079, 'LBL_Paid', 1, 'Paid'),
(2080, 'MSG_Language_Removed_Successfuly!', 1, 'Language Removed Successfuly!'),
(2081, 'MSG_Teacher_Preferences', 1, 'Teacher Preferences'),
(2082, 'MSG_Go_to_Wallet', 1, 'Go To Wallet'),
(2083, 'VLBL_startWithLetterOnlyAlphanumeric', 1, 'Start with alphanumeric letter only'),
(2084, 'LBL_You_have_already_Bought_this_plan', 1, 'You Have Already Bought This Plan'),
(2085, 'LBL_Manage_FAQs', 1, 'Manage Faqs'),
(2086, 'LBL_Google', 1, 'Google'),
(2087, 'LBL_Jun', 1, 'Jun'),
(2088, 'LBL_slider_title', 1, 'Speak like a Local.'),
(2089, 'LBL_slider_description', 1, 'Conquer a language with native teachers you pick to guide you on your language journey.'),
(2091, 'LBL_slider_title', 2, 'يتكلم وكأنه المحلية.'),
(2093, 'LBL_slider_description', 2, 'قهر لغة مع المعلمين الأصلي الذي تختاره لإرشادك في رحلة لغتك.'),
(2094, 'VLBL_startWithLetterOnlyAlphanumeric', 2, 'بدء بحرف أبجدي فقط'),
(2095, 'ERR_ERROR_IN_SENDING_NOTIFICATION_EMAIL_TO_ADMIN', 1, 'Error In Sending Notification Email To Admin'),
(2096, 'MSG_NOTIFICATION_EMAIL_COULD_NOT_BE_SENT', 1, 'Notification Email Could Not Be Sent'),
(2097, 'LBL_Email_Could_Not_Be_Sent', 1, 'Email Could Not Be Sent'),
(2098, 'Lbl_View_Availibility_(Click_Buy_to_Book)', 1, 'View Availability (click Buy To Book)'),
(2099, 'LBL_Requested_Slot_is_not_available', 1, 'Requested Slot Is Not Available'),
(2100, 'LBL_Activate_Live_Payment_Transaction_Mode', 1, 'Activate Live Payment Transaction Mode'),
(2101, 'LBL_Set_Transaction_Mode_To_Live_Environment', 1, 'Set Transaction Mode To Live Environment'),
(2102, 'MSG_Thankyou_for_Purchase', 1, 'Thankyou For Purchase'),
(2103, 'MSG_Your_Order_has_been_successfully_Placed', 1, 'Your Order Has Been Successfully Placed'),
(2104, 'MSG_You_can_schedule_lessons_here', 1, 'You Can Schedule Lessons Here'),
(2105, 'LBL_Languages_Offered', 1, 'Languages Offered'),
(2106, 'LBL_Choose_Among_Languages_Offered!', 1, 'Choose Among Languages Offered!'),
(2107, 'MSG_Please_Enter_8_Digit_AlphaNumeric_Password', 2, '                                                                             الرجاء إدخال ٨ أرقام أبجدية كلمة المرور'),
(2108, 'LBL_Google', 2, 'جوجل'),
(2109, 'LBL_Testimonials', 2, 'الشهادات - التوصيات'),
(2110, 'LBL_Duration', 2, 'المدة الزمنية'),
(2111, 'LBL_Languages_Offered', 2, 'اللغات المعروضة'),
(2112, 'LBL_Choose_Among_Languages_Offered!', 2, 'اختر من بين اللغات المقدمة!'),
(2113, 'LBL_Apply_Coupon', 2, 'تطبيق القسيمة'),
(2114, 'LBL_Package_Selected_Successfully.', 2, 'حزمة مختارة بنجاح.'),
(2115, 'LBL_Coupon_code', 2, 'رمز الكوبون'),
(2116, 'LBL_Enter_Your_code', 2, 'ادخل رمزك'),
(2117, 'LBL_Apply', 2, 'تطبيق'),
(2118, 'LBL_Available_Coupons', 2, 'كوبونات المتاحة'),
(2119, 'LBL_Click_to_apply_coupon', 2, 'انقر فوق إلى تطبيق القسيمة'),
(2120, 'MSG_cart_discount_coupon_applied', 2, 'سلة الخصم القسيمة التطبيقية'),
(2121, 'LBL_Coupon_Discount', 2, 'قسيمة الخصم'),
(2122, 'MSG_cart_discount_coupon_removed', 2, 'سلة الخصم القسيمة إزالتها'),
(2123, 'LBL_See_All_Teachers', 2, 'رؤية جميع المعلمين'),
(2124, 'LBL_Pending', 2, 'قيد الانتظار'),
(2125, 'LBL_You_already_purchased_free_trial_for_this_teacher', 2, 'يمكنك شراؤها إذا التجريبي المجاني على هذا المعلم'),
(2126, 'MSG_VERIFICATION_EMAIL_SENT', 2, 'التحقق من البريد الإلكتروني المرسلة، يرجى مراجعة صندوق البريد الخاص بك وتأكيد ID البريد الإلكتروني الخاص بك والمضي قدما. يرجى مراجعة مجلد البريد المزعج أيضا.'),
(2127, 'Lbl_View_Availibility_(Click_Buy_to_Book)', 2, 'مشاهدة الإتاحة (انقر لشراء كتاب)'),
(2128, 'MSG_Thankyou_for_Purchase', 2, 'الشكر للشراء'),
(2129, 'MSG_Your_Order_has_been_successfully_Placed', 2, 'النظام الخاص بك وضعت بنجاح'),
(2130, 'MSG_You_can_schedule_lessons_here', 2, 'يمكنك جدولة الدروس هنا'),
(2131, 'MSG_Go_to_my_Lessons', 2, 'العودة إلى بلدي الدروس'),
(2132, 'BLOCK_SECOND_AFTER_HOMESLIDER', 1, 'Second After Homeslider'),
(2133, 'Lbl_What_Language_You_want_to_learn?', 1, 'What Subject Do You Want To Learn?'),
(2134, 'Lbl_We_have_teacher_in_different_languages!', 1, 'We Have Teacher In Different Languages!'),
(2135, 'Lbl_Browse_them_now!', 1, 'Browse Them Now!'),
(2136, 'Lbl_Top_Rated_Teachers', 1, 'Top Rated Teachers'),
(2137, 'LBL_Subscribe', 1, 'Subscribe'),
(2138, 'LBL_Newsletter_is_not_configured_yet,_Please_contact_admin', 1, 'Newsletter Is Not Configured Yet, Please Contact Admin'),
(2139, 'LBL_Banner_Image_(Small)', 1, 'Banner Image (small)'),
(2140, 'LBL_Language_Image', 1, 'Language Image'),
(2141, 'MSG_Invalid_request_Or_Inactive_Record', 1, 'Invalid Request Or Inactive Record'),
(2142, 'LBL_Browse_All_Teachers', 1, 'Browse All Teachers'),
(2143, 'LBL_More_Links', 1, 'More Links'),
(2144, 'LBL_Contact_Info', 1, 'Contact Info'),
(2145, 'LBL_Call_Us', 1, 'Call Us'),
(2146, 'LBL_All_Rights_Reserved', 1, 'All Rights Reserved'),
(2147, 'LBL_Jul', 1, 'Jul'),
(2148, 'LBL_ADD_LANGUAGE', 1, 'Add Language'),
(2149, 'LBL_ADD_TEACH_LANGUAGE', 1, 'Add Teach Language'),
(2150, 'LBL_Slider_Title_Text', 1, 'Learn With Us!'),
(2151, 'LBL_Slider_Description_Text', 1, 'Most people fail to learn a language before they even begin. Not anymore!\nBecause our tutors apply the most appropriate method for a learner’s specific objectives and learning style.'),
(2152, 'LBL_I_am_learning...', 1, 'I am learning...'),
(2153, 'LBL_Get_Started?', 1, 'Get Started'),
(2154, 'LBL_How_it_Works?', 1, 'How it works?'),
(2155, 'LBL_NOTE:_To_use_SSL,_check_with_your_host', 1, 'Note: To Use Ssl, Check With Your Host'),
(2156, 'LBL_Buyer_Email', 1, 'Buyer Email'),
(2157, 'LBL_Buyer_Phone', 1, 'Buyer Phone'),
(2158, 'LBL_Recipient_Name', 1, 'Recipient Name'),
(2159, 'LBL_Recipient_Email', 1, 'Recipient Email'),
(2160, 'LBL_Why_Us?', 1, 'Why Us?'),
(2161, 'LBL_Upcoming_Scheduled_Lessons', 1, 'Upcoming Scheduled Lessons'),
(2162, 'LBL_Start_now_and_turn_Text', 1, 'Book Your Lesson'),
(2163, 'LBL_Get_started_now!', 1, 'Get Started Now!'),
(2164, 'MSG_Go_to_Giftcards_Purchased', 1, 'Go To Giftcards Purchased'),
(2165, 'MSG_Withdrawal_Request_Date', 1, 'Withdrawal Request Date'),
(2166, 'BLOCK_HOW_IT_WORKS', 1, 'How it works?'),
(2167, 'LBL_What_Language_Do_You_Want_To_Teach', 1, 'What Language Do You Want To Teach'),
(2168, 'LBL_Language_Flag_Image', 1, 'Language Flag Image'),
(2169, 'LBL_Language_I_Teach', 1, 'Language I Teach'),
(2170, 'LBL_Language_I_Speak', 1, 'Language I Speak'),
(2171, 'LBL_Comet_Chat_Auth', 1, 'Comet Chat Auth'),
(2172, 'LBL_On_Submit_Price_Needs_To_Set_Again', 1, 'On Submit Price Needs To Set Again'),
(2173, 'LBL_Learner_Join_Time_Marked!', 1, 'Learner Join Time Marked!'),
(2175, 'Lbl_What_Language_You_want_to_learn?', 2, 'ما موضوع هل تريد أن تعرف؟'),
(2176, 'LBL_Aug', 1, 'Aug'),
(2177, 'MSG_USER_NOT_LOGGED', 1, 'User Not Logged'),
(2178, 'MSG_Invalid_Order', 1, 'Invalid Order'),
(2179, 'LBL_Manage_Teaching_Language', 1, 'Manage Teaching Language'),
(2180, 'LBL_Teaching_Language_Listing', 1, 'Teaching Language Listing'),
(2181, 'LBL_Add_Teaching_Language', 1, 'Add Teaching Language'),
(2182, 'LBL_Teaching_language_Setup', 1, 'Teaching Language Setup'),
(2183, 'LBL_Language_To_Teach', 1, 'Language To Teach'),
(2184, 'LBL_On_Submit_Price_Needs_To_Set_Again', 2, 'إرسال على الأسعار يحتاج إلى مجموعة مرة أخرى'),
(2185, 'LBL_More_Links', 2, 'المزيد من الروابط'),
(2186, 'LBL_Contact_Info', 2, 'معلومات الاتصال'),
(2187, 'LBL_Call_Us', 2, 'اتصل بنا'),
(2188, 'LBL_All_Rights_Reserved', 2, 'كل الحقوق محفوظة'),
(2189, 'LBL_Payment_Details_Added_Successfully', 1, 'Payment Details Added Successfully'),
(2190, 'LBL_Password_/_Email', 1, 'Password / Email'),
(2191, 'LBL_Change_Password_or_Email', 1, 'Change Password Or Email'),
(2192, 'LBL_CURRENT_EMAIL', 1, 'Current Email'),
(2193, 'LBL_NEW_EMAIL', 1, 'New Email'),
(2194, 'MSG_INVALID_REQUEST', 1, 'Invalid Request'),
(2195, 'MSG_Email_changed_successfully', 1, 'Email Changed Successfully'),
(2196, 'MSG_Unable_to_process_your_requset', 1, 'Unable To Process Your Request'),
(2197, 'MSG_INVAILD_VERIFICATION_LINK', 1, 'Invaild Verification Link'),
(2198, 'MSG_Email_Updated._Please_Login_again_in_your_profile', 1, 'Email Updated. Please Login Again In Your Profile'),
(2199, 'MSG_Please_verify_your_email', 1, 'Please Verify Your Email'),
(2200, 'LBL_Email_Verification_pending_for_email_change', 1, 'Email Verification Pending For Email Change'),
(2201, 'MSG_Email_Updated._Please_Login_again_in_your_profile_with_new_email', 1, 'Email Updated. Please Login Again In Your Profile With New Email'),
(2202, 'LBL_Booking_Before', 1, 'Booking Before'),
(2203, 'LBL_Teacher_Disable_the_Booking_before', 1, 'Teacher Disable The Booking Before'),
(2204, 'LBL_Top_Languages', 1, 'Top Languages'),
(2205, 'LBL_Manage_FAQ_Category', 1, 'Manage Faq Category'),
(2206, 'LBL_Manage_Faq_Categories', 1, 'Manage Faq Categories'),
(2207, 'LBL_Faq_Categories', 1, 'Faq Categories'),
(2208, 'LBL_Faq_Category_List', 1, 'Faq Category List'),
(2209, 'LBL_Activate', 1, 'Activate'),
(2210, 'LBL_Deactivate', 1, 'Deactivate'),
(2211, 'LBL_Add_category', 1, 'Add Category'),
(2212, 'LBL_Select_all', 1, 'Select All'),
(2213, 'LBL_Faq_Page', 1, 'Faq Page'),
(2214, 'LBL_Faq_Category_Setup', 1, 'Faq Category Setup'),
(2215, 'LBL_Category_Setup_Successful', 1, 'Category Setup Successful'),
(2216, 'LBL_Top_Languages_Report', 1, 'Top Languages Report'),
(2217, 'LBL_No._of_Sold_Lessons', 1, 'No. Of Sold Lessons'),
(2218, 'LBL_Sep', 1, 'Sep'),
(2219, 'LBL_Teacher_Performance', 1, 'Teacher Performance'),
(2220, 'LBL_Teacher_Performance_Report', 1, 'Teacher Performance Report'),
(2221, 'LBL_No._Students', 1, 'No. Students'),
(2222, 'LBL_Rating', 1, 'Rating'),
(2223, 'LBL_Lerner', 1, 'Lerner'),
(2224, 'LBL_Completed_lessons', 1, 'Completed Lessons'),
(2225, 'LBL_Cancelled_lessons', 1, 'Cancelled Lessons'),
(2226, 'LBL_Need_to_be_Schedule_lessons', 1, 'Need To Be Schedule Lessons'),
(2227, 'MSG_We_will_be_back_soon', 1, 'We Will Be Back Soon'),
(2228, 'MSG_Maintenance_Mode_Text', 1, 'Maintenance Mode Text'),
(2229, 'LBL_Trial_Lesson', 1, 'Trial Lesson'),
(2230, 'MSG_Withdrawal_Request_Declined_Amount_Refunded', 1, 'Withdrawal Request Declined Amount Refunded'),
(2231, 'MSG_No_Record_Found', 1, 'No Record Found'),
(2232, 'MSG_Success', 1, 'Success'),
(2234, 'LBL_Slider_Title_Text', 2, 'تعلم معنا!'),
(2236, 'LBL_Slider_Description_Text', 2, 'معظم الناس لا تعلم لغة حتى قبل أن تبدأ. ليس بعد الآن!\nلأن المدرسين لدينا تنطبق على معظم الطريقة المناسبة لتحقيق أهداف محددة المتعلم وأسلوب التعلم.'),
(2237, 'LBL_I_am_learning...', 2, 'انا اتعلم...'),
(2238, 'LBL_Get_Started?', 2, 'البدء'),
(2239, 'LBL_How_it_Works?', 2, 'كيف تعمل؟'),
(2240, 'LBL_Why_Us?', 2, 'لماذا نحن؟'),
(2241, 'Lbl_We_have_teacher_in_different_languages!', 2, 'لدينا المعلم في لغات مختلفة!'),
(2242, 'Lbl_Browse_them_now!', 2, 'تصفحها الآن!'),
(2243, 'LBL_Upcoming_Scheduled_Lessons', 2, 'دروس من المقرر القادمة'),
(2244, 'LBL_Start_now_and_turn_Text', 2, 'احجز درسك'),
(2245, 'LBL_Get_started_now!', 2, 'نبدأ الآن!'),
(2246, 'Lbl_Top_Rated_Teachers', 2, 'الأعلى تقييما المعلمين'),
(2247, 'LBL_Browse_All_Teachers', 2, 'تصفح جميع المعلمين'),
(2248, 'LBL_Manage_Language', 1, 'Manage Language'),
(2249, 'LBL_Languages_Listing', 1, 'Languages Listing'),
(2250, 'LBL_Language_Code', 1, 'Language Code'),
(2251, 'Lbl_SHOW_MORE', 1, 'Show More'),
(2252, 'LBL_Please_Update_Your_Timezone', 1, 'Please Update Your Timezone'),
(2253, 'MSG_New_Message_Arrived', 1, 'New Message Arrived'),
(2254, 'LBL_Issue_To_Report', 1, 'Issue To Report'),
(2255, 'LBL_Issue_Report_Options', 1, 'Issue Report Options'),
(2256, 'LBL_Manage_Issue_Report_Options', 1, 'Manage Issue Report Options'),
(2257, 'LBL_Add_Issue_Reoprt_Option', 1, 'Add Issue Reoprt Option'),
(2258, 'LBL_Add_Option', 1, 'Add Option'),
(2259, 'LBL_Option_Identifier', 1, 'Option Identifier'),
(2260, 'LBL_Issue_Report_Options_Setup', 1, 'Issue Report Options Setup'),
(2261, 'LBL_Unauthorized_Access', 2, 'دخول غير مرخص'),
(2262, 'LBL_Record_Added_Successfully', 2, 'سجل اضاف بنجاح'),
(2263, 'LBL_Record_Updated_Successfully', 2, 'سجل تحديث بنجاح'),
(2264, 'LBL_No_Record_Found', 2, 'لا يوجد سجلات'),
(2265, 'LBL_Invalid_Request_Id', 2, 'رقم طلب غير صالح'),
(2266, 'LBL_Record_Deleted_Successfully', 2, 'سجل محذوفة بنجاح'),
(2267, 'LBL_Invalid_Action', 2, 'عمل غير صالح'),
(2268, 'LBL_Setup_Successful', 2, 'الإعداد الناجح'),
(2269, 'LBL_Export_Successful', 2, 'تصدير الناجح'),
(2270, 'LBL_Do_you_want_to_remove_this_option', 2, 'هل تريد إزالة هذا الخيار'),
(2271, 'LBL_Do_you_want_to_remove_this_shop', 2, 'هل تريد إزالة هذا المحل'),
(2272, 'LBL_Do_you_want_to_remove_this_product', 2, 'هل تريد إزالة هذا المنتج'),
(2273, 'LBL_Do_you_want_to_remove_this_category', 2, 'هل تريد حذف هذا التصنيف'),
(2274, 'LBL_Do_you_want_to_reset_settings', 2, 'هل تريد إعادة تعيين إعدادات'),
(2275, 'LBL_Do_you_want_to_activate_status', 2, 'هل تريد تنشيط الحالة'),
(2276, 'LBL_Do_you_want_to_update', 2, 'هل تريد تحديث'),
(2277, 'LBL_Do_you_want_to_delete', 2, 'هل تريد أن تحذف'),
(2278, 'LBL_Do_you_want_to_delete_image', 2, 'هل تريد حذف صورة'),
(2279, 'LBL_Do_you_want_to_delete_background_image', 2, 'هل تريد حذف صورة الخلفية'),
(2280, 'LBL_Do_you_want_to_delete_logo', 2, 'هل تريد حذف شعار'),
(2281, 'LBL_Do_you_want_to_delete_banner', 2, 'هل تريد حذف راية'),
(2282, 'LBL_Do_you_want_to_delete_icon', 2, 'هل تريد حذف أيقونة'),
(2283, 'LBL_Do_you_want_to_set_default', 2, 'هل تريد تعيين الافتراضي'),
(2284, 'LBL_Set_as_main_product', 2, 'مجموعة والمنتج الرئيسي'),
(2285, 'LBL_Please_Select_any_Plan', 2, 'الرجاء اختيار أي خطة'),
(2286, 'LBL_You_have_already_Bought_this_plan', 2, 'لقد اشترى إذا هذه الخطة'),
(2287, 'LBL_Invalid_Request!', 2, 'طلب غير صالح!'),
(2288, 'LBL_Please_Wait...', 2, 'أرجو الإنتظار...'),
(2289, 'LBL_Do_you_really_want_to', 2, 'هل تريد حقا'),
(2290, 'LBL_the_request', 2, 'الطلب'),
(2291, 'LBL_Are_you_sure_to_cancel_this_order', 2, 'هل أنت متأكد من إلغاء هذا النظام'),
(2292, 'LBL_Do_you_want_to_replace_current_content_to_default_content', 2, 'هل تريد استبدال المحتوى الحالي لالمحتوى الافتراضي'),
(2293, 'LBL_Preferred_Dimensions_%s', 2, 'فضل الأبعاد٪ الصورة'),
(2294, 'LBL_Do_you_want_to_restore', 2, 'هل تريد استعادة'),
(2295, 'LBL_Msg_Thanks_for_sharing', 2, 'جي اس شكرا لتقاسم'),
(2296, 'VLBL_is_mandatory', 2, 'إلزامي'),
(2297, 'VLBL_Please_enter_valid_email_ID_for', 2, 'الرجاء إدخال بريد إلكتروني صالح إيد ل'),
(2298, 'VLBL_to', 2, 'إلى'),
(2299, 'VLBL_options', 2, 'خيارات'),
(2300, 'LBL_Do_you_want_to_restore_database_to_this_record', 2, 'هل تريد استعادة قاعدة البيانات لهذا السجل'),
(2301, 'LBL_Language_Identifier', 2, 'اللغة معرف'),
(2302, 'LBL_Clear_Search', 2, 'مسح البحث'),
(2303, 'LBL_View_Portal', 2, 'عرض البوابة'),
(2304, 'LBL_Clear_Cache', 2, 'مسح ذاكرة التخزين المؤقت'),
(2305, 'LBL_Select_Language', 2, 'اختار اللغة'),
(2306, 'LBL_Welcome', 2, 'أهلا بك'),
(2307, 'LBL_Change_Password', 2, 'غير كلمة السر'),
(2308, 'LBL_Users', 2, 'المستخدمين'),
(2309, 'LBL_Teacher_Approval_Requests', 2, 'المعلم يطلب الموافقة'),
(2310, 'LBL_User_Withdrwal_Requests', 2, '                                                                                                  طلبات سحب المستخدم'),
(2311, 'LBL_Teacher_Reviews', 2, 'التعليقات المعلم'),
(2312, 'LBL_Gift_Orders', 2, 'أوامر هدية'),
(2313, 'LBL_Manage_Issues_Reported', 2, 'إدارة القضايا المبلغ عنها'),
(2314, 'LBL_Teacher_Preferences', 2, 'تفضيلات المعلم'),
(2315, 'LBL_Learners_Ages', 2, 'المتعلمين العصور'),
(2316, 'LBL_Lessons_Include', 2, 'الدروس تشمل'),
(2317, 'LBL_Test_preparation', 2, 'استعداد للامتحان'),
(2318, 'LBL_Spoken_Language', 2, 'اللغة المتحدثة'),
(2319, 'LBL_Teaching_Language', 2, 'تدريس اللغة'),
(2320, 'LBL_Issue_Report_Options', 2, 'إصدار تقرير الخيارات'),
(2321, 'LBL_Content_Pages', 2, 'صفحات المحتوى'),
(2322, 'LBL_Bible_Content', 2, 'محتوى الفيديو'),
(2323, 'LBL_Home_Page_Slides_Management', 2, 'الصفحة الرئيسية لإدارة الشرائح'),
(2324, 'LBL_Lesson_Packages_Management', 2, 'إدارة الحزم الدرس'),
(2325, 'LBL_Banners', 2, 'لافتات'),
(2326, 'LBL_Navigation_Management', 2, 'إدارة الملاحة'),
(2327, 'LBL_Countries_Management', 2, 'إدارة الدول'),
(2328, 'LBL_States_Management', 2, 'إدارة الأمريكية'),
(2329, 'LBL_Social_Platforms_Management', 2, 'إدارة المنشآت الاجتماعية'),
(2330, 'LBL_Discount_Coupons', 2, 'كوبونات خصم'),
(2331, 'LBL_Language_Label', 2, 'تسمية لغة'),
(2332, 'LBL_Manage_FAQs', 2, 'إدارة أسئلة وأجوبة'),
(2333, 'LBL_Manage_FAQ_Category', 2, 'إدارة الأسئلة الفئة'),
(2334, 'LBL_Blog_Post_Categories', 2, 'مقالات بوست التصنيفات'),
(2335, 'LBL_Blog_Posts', 2, 'بلوق المشاركات'),
(2336, 'LBL_Blog_Contributions', 2, 'بلوق الاشتراكات'),
(2337, 'LBL_Blog_Comments', 2, 'بلوق تعليقات'),
(2338, 'LBL_General_Settings', 2, 'الاعدادات العامة'),
(2339, 'LBL_Payment_Methods', 2, 'طرق الدفع'),
(2340, 'LBL_Commission_Settings', 2, 'إعدادات جنة'),
(2341, 'LBL_Currency_Management', 2, 'إدارة العملة'),
(2342, 'LBL_Email_Templates_Management', 2, 'أرسل إدارة قوالب'),
(2343, 'LBL_Misc', 2, 'متفرقات'),
(2344, 'LBL_Meta_Tags_Management', 2, 'ميتا إدارة الكلمات الدلالية'),
(2345, 'LBL_Url_Rewriting', 2, '                                                                                               عُنْوان إنْتَرْنِت كتابة عنوان'),
(2346, 'LBL_Top_Languages', 2, 'الأعلى لغات'),
(2347, 'LBL_Teacher_Performance', 2, 'أداء المعلم'),
(2348, 'LBL_Manage_Admin_Users', 2, 'إدارة الادارية المستخدمين'),
(2349, 'LBL_Manage_Issue_Report_Options', 2, 'Manage Issue Report Options'),
(2350, 'LBL_Search...', 2, 'بحث...'),
(2351, 'LBL_Add_Issue_Reoprt_Option', 2, 'Add Issue Reoprt Option'),
(2352, 'LBL_Add_Option', 2, 'Add Option'),
(2353, 'Msg_Please_Wait_We_are_redirecting_you...', 2, 'يرجى الانتظار نحن هل أنت إعادة توجيه ...'),
(2354, 'LBL_Sr_no.', 2, 'الأب رقم.'),
(2355, 'LBL_Option_Identifier', 2, 'Option Identifier'),
(2356, 'LBL_Issue_Status', 2, 'الحالة القضية'),
(2357, 'LBL_Open', 2, 'افتح'),
(2358, 'LBL_In_Progress', 2, 'في تقدم'),
(2359, 'LBL_Resolved', 2, 'تم الحل'),
(2360, 'LBL_Issue_To_Report', 2, 'Issue To Report'),
(2361, 'LBL_Comment', 2, 'تعليق'),
(2362, 'LBL_Send', 2, 'إرسال'),
(2363, 'LBL_Resolve_Issue', 1, 'Resolve Issue'),
(2364, 'LBL_Reported_Issue_Details', 1, 'Reported Issue Details'),
(2365, 'LBL_Reported_Issue_Reason', 1, 'Reason'),
(2366, 'LBL_Reported_Issue_Comment', 1, 'Comment'),
(2367, 'LBL_Issue_To_Resolve', 1, 'Select One or more Issues that occurred'),
(2369, 'LBL_Issue_To_Resolve', 2, 'Select One or more Issues that occurred'),
(2370, 'LBL_Lesson_Issue_Reported_Successfully!', 1, 'Lesson Issue Reported Successfully!'),
(2371, 'LBL_Lesson_Issue_Updated_Successfully!', 1, 'Lesson Issue Updated Successfully!'),
(2372, 'LBL_How_would_you_like_to_resolve_this?', 1, 'How Would You Like To Resolve This?'),
(2373, 'LBL_Selected_issue(s)', 1, 'Selected Issue(s)'),
(2374, 'LBL_Resolve', 1, 'Resolve'),
(2375, 'LBL_Refund', 1, 'Refund'),
(2376, 'LBL_Declined', 1, 'Declined'),
(2377, 'LBL_Refunded', 1, 'Refunded'),
(2378, 'LBL_Lesson_Issue_Resolved_Successfully!', 1, 'Lesson Issue Resolved Successfully!'),
(2379, 'LBL_Issue_Already_Reported', 1, 'Issue Already Reported'),
(2380, 'LBL_Issue_Details', 1, 'Issue Details'),
(2381, 'LABEL_LESSON_ISSUE_REPORTED_BY_LEARNER', 1, 'Lesson Issue Reported By Learner'),
(2382, 'LABEL_LESSON_ISSUE_REPORTED_BY_LEARNER_DESCRIPTION', 1, 'Lesson Issue Reported By Learner Description'),
(2383, 'LABEL_LESSON_ISSUE_RESOLVED_BY_TEACHER', 1, 'Lesson Issue Resolved By Teacher'),
(2384, 'LABEL_LESSON_RESOLVED_BY_TEACHER_DESCRIPTION', 1, 'Lesson Resolved By Teacher Description'),
(2385, 'LBL_Reported_Issue_Updates_By_Teacher', 1, 'Reported Issue Updates By Teacher'),
(2386, 'LBL_Reported_Issue_Resolve_Comment', 1, 'Comment'),
(2387, 'LBL_Reported_Issue_Resolve_Type', 1, 'Resolve Type'),
(2388, 'LBL_Reported_Issue_By_Learner', 1, 'Reported Issue By Learner'),
(2389, 'LBL_Report_Issue_to_Support_Team', 1, 'Report Issue To Support Team'),
(2390, 'LBL_Not_Happy_with_teacher_solution?', 1, 'Not Happy With Teacher\'s Solution?'),
(2392, 'LBL_Not_Happy_with_teacher_solution?', 2, 'Not Happy With Teacher\'s Solution?'),
(2393, 'LBL_Are_you_sure_want_to_report_the_issue_To_support_team', 1, 'Are You Sure Want To Report The Issue To Support Team'),
(2394, 'LBL_Lesson_Issue_Reported_to_the_Support', 1, 'Lesson Issue Reported To The Support'),
(2395, 'LBL_Lesson_Issue_Reported_to_the_Support!', 1, 'Lesson Issue Reported To The Support!'),
(2396, 'LBL_Teacher_Comment', 1, 'Teacher Comment'),
(2397, 'LBL_Teacher_Resolve_by', 1, 'Teacher Resolve By'),
(2398, 'LBL_Reason_by_Learner', 1, 'Reason By Learner'),
(2399, 'LBL_Reason_by_Teacher', 1, 'Reason By Teacher'),
(2400, 'LBL_This_lesson_is_completed._rate_it', 1, 'This Lesson Is Completed. Rate It'),
(2401, 'LBL_Rate_it', 1, 'Rate It'),
(2402, 'LBL_Report_Issue', 1, 'Report Issue'),
(2403, 'LBL_Click_here_to_report_an_Issue', 1, 'Click Here To Report An Issue'),
(2404, 'LBL_This_lesson_is_Unscheduled._schedule_it', 1, 'This Lesson Is Unscheduled. Schedule It'),
(2405, 'LBL_LessonId:_%s_Payment_Received_(_%s_&percnt;_Refunded_)', 1, 'Lessonid: %s Payment Received ( %s&percnt; Refunded )'),
(2407, 'LBL_LessonId:_%s_Payment_Received_(_%s_&percnt;_Refunded_)', 2, 'Lessonid: %s Payment Received ( %s&percnt; Refunded )'),
(2408, 'LBL_LessonId:_%s_Payment_Received_(_%s&percnt;_Refunded_)', 1, 'Lessonid: %s Payment Received ( %s&percnt; Refunded )'),
(2409, 'LBL_LessonId:_%s_Payment_Received', 1, 'Lessonid: %s Payment Received'),
(2411, 'LBL_Reported_Issue_Reason', 2, 'Reason'),
(2413, 'LBL_Reported_Issue_Comment', 2, 'Comment'),
(2415, 'LBL_Reported_Issue_Resolve_Comment', 2, 'Comment'),
(2417, 'LBL_Reported_Issue_Resolve_Type', 2, 'Resolve Type'),
(2418, 'LBL_Privacy_Policy', 1, 'Privacy Policy'),
(2419, 'MSG_Terms_Condition_and_Privacy_Policy_are_mandatory.', 1, 'Terms Condition And Privacy Policy Are Mandatory.'),
(2420, 'MSG_Terms_and_Condition_and_Privacy_Policy_are_mandatory.', 1, 'Terms And Condition And Privacy Policy Are Mandatory.'),
(2421, 'LBL_Issue_Reported_Status', 1, 'Issue Reported Status'),
(2422, 'LBL_Resolved_by_:', 1, 'Resolved By :'),
(2423, 'LBL_Comment_:', 1, 'Comment :'),
(2424, 'LBL_Issue_Already_Resolved_/_inprogress', 1, 'Issue Already Resolved / Inprogress'),
(2425, 'LBL_Escalated_By', 1, 'Escalated By'),
(2426, 'LBL_Not_Happy_with_solution?', 1, 'Not Happy With Solution?'),
(2427, 'Learn', 1, ''),
(2428, 'from', 1, ''),
(2429, 'LBL_Learn', 1, 'Learn'),
(2430, 'LBL_Tommorrow', 1, 'Tommorrow'),
(2431, 'LBL_3_Articles', 2, '                                                                                                                  ٣ المواد'),
(2432, 'LBL_1_Articles', 2, '                                                                                                                ١ المقالات'),
(2433, 'LABEL_LESSON_STATUS_UPDATED', 1, 'Lesson Status Updated'),
(2434, 'LABEL_LESSON_STATUS_UPDATED_TO_%s', 1, 'Lesson Status Updated To %s'),
(2435, 'LBL_Do_you_want_to_cancel', 1, 'Do You Want To Cancel'),
(2436, 'LBL_Group_Classes', 1, 'Group Classes'),
(2437, 'LBL_Order_Lessons', 1, 'Order Lessons'),
(2438, 'LBL_On_Submit_Price_Needs_To_Be_Set', 1, 'On Submit Price Needs To Be Set'),
(2439, 'LBL_Quit', 1, 'Close'),
(2440, 'LBL_Charge_Learner', 1, 'Charge Learner'),
(2441, 'VLBL_You_have_already_booked_this_slot._Do_you_want_to_continue?', 1, 'You Have Already Booked This Slot. Do You Want To Continue?'),
(2442, 'VLBL_Are_you_sure_to_end_this_Lesson?', 1, 'Are you sure you want to end the lesson?'),
(2443, 'LBL_Proceed', 1, 'Proceed'),
(2444, 'LBL_Confirm', 1, 'Confirm'),
(2445, 'LBL_Are_you_sure_want_to_cancel_this_lesson', 1, 'Are You Sure Want To Cancel This Lesson'),
(2446, 'Lbl_Reschedule_Reason_Is_Requried', 1, 'Reschedule Reason Is Requried'),
(2447, 'LBL_We\'d_love_to_send_you_our_Newsletter', 1, 'We\'d Love To Send You Our Newsletter'),
(2448, 'LBL_Enter_Your_Email', 1, 'Enter Your Email'),
(2449, 'LBL_File_to_be_uploaded:', 1, 'File To Be Uploaded:'),
(2450, 'LBL_Browse_File', 1, 'Browse File'),
(2451, 'LBL_Import_Labels_Instructions', 1, 'Import Labels Instructions'),
(2452, 'LBL_Import_Labels', 1, 'Import Labels'),
(2453, 'VLBL_You_have_already_booked_this_slot._Do_you_want_to_continue?', 2, 'كنت قد حجزت بالفعل هذا فتحة. هل تريد الاستمرار؟'),
(2454, 'VLBL_Are_you_sure_to_end_this_Lesson?', 2, 'هل أنت متأكد أنك تريد إنهاء الدرس؟'),
(2455, 'STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', 1, 'Invalid Payment Gateway Setup Error'),
(2456, 'STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', 2, 'الدفع غير صالح خطأ في إعداد بوابة'),
(2457, 'Product_Detail_Page_Banner', 2, 'التفاصيل صفحة راية'),
(2458, 'Order_Payment_Gateway_Description', 1, 'Payment Gateway Description'),
(2459, 'Order_Payment_Gateway_Description', 2, 'بوابة الدفع الوصف'),
(2460, 'M_Single_Lesson_Rate', 2, 'الدرس واحد معدل (USD) (كل درس ساعة واحدة)'),
(2461, 'M_Paypal_Email_Address', 2, 'عنوان البريد الإلكتروني لبيبال'),
(2462, 'M_IFSC_Code/Swift_Code', 2, 'كود IFSC / الرمز سريع'),
(2463, 'M_Bulk_Lesson_Rate', 2, 'واحد الدرس السعر عند شراء بكميات كبيرة (USD)'),
(2464, 'M_Beneficiary/Account_Holder_Name', 2, 'المستفيد / اسم صاحب الحساب'),
(2465, 'M_Bank_Name', 2, 'اسم البنك'),
(2466, 'M_Bank_Address', 2, 'عنوان البنك'),
(2467, 'M_Bank_Account_Number', 2, 'رقم الحساب المصرفي'),
(2468, 'M_Are_you_sure!', 2, 'هل أنت واثق!'),
(2469, 'MSG_You_are_already_logged_in', 2, 'قمت بتسجيل بالفعل في'),
(2470, 'MSG_Your_request_to_reset_password_has_already_been_placed_within_last_24_hours._Please_check_your_emails_or_retry_after_24_hours_of_your_previous_request', 2, 'لديك طلب لإعادة تعيين كلمة المرور قد تم بالفعل وضع في غضون ٢٤ ساعة الماضية. يرجى التحقق من البريد الإلكتروني الخاص بك أو إعادة المحاولة بعد ٢٤ ساعة من طلبك السابق'),
(2471, 'MSG_YOUR_PASSWORD_RESET_INSTRUCTIONS_TO_YOUR_EMAIL', 2, 'تم إرسال إرشادات إعادة تعيين كلمة المرور إلى عنوان بريدك الإلكتروني المسجل'),
(2472, 'MSG_your_message_sent_successfully', 2, 'رسالتك المرسلة بنجاح'),
(2473, 'MSG_YOUR_CURRENT_PASSWORD_MIS_MATCHED', 2, 'بك ميس كلمة المرور الحالية مطابقة'),
(2474, 'MSG_Your_Account_verification_is_pending_{clickhere}', 2, 'حسابك التحقق هو قيد {} clickhere'),
(2475, 'MSG_Withdraw_request_placed_successfully', 2, 'سحب طلب وضعت بنجاح'),
(2476, 'MSG_Withdraw_Requests', 2, 'سحب الطلبات'),
(2477, 'MSG_Withdrawal_Request_Minimum_Balance_Less', 2, 'سحب طلب الدنيا الرصيد أقل'),
(2478, 'MSG_Withdrawal_Request_Declined_Amount_Refunded', 2, 'سحب طلب امتنع المبلغ المردودة'),
(2479, 'MSG_Withdrawal_Request_Date', 2, 'سحب طلب التسجيل'),
(2480, 'MSG_We_will_be_back_soon', 2, 'سوف نعود قريبا'),
(2481, 'MSG_We_are_redirecting_to_payment_page', 2, 'نحن إعادة توجيه إلى صفحة الدفع'),
(2482, 'MSG_VERIFICATION_EMAIL_HAS_BEEN_SENT_AGAIN', 2, 'التحقق من البريد الإلكتروني قد أرسلت مرة أخرى'),
(2483, 'MSG_USER_NOT_LOGGED', 2, 'المستخدم لم تقم بتسجيل'),
(2484, 'MSG_USER_COULD_NOT_BE_SET', 2, 'العضو تعذر تعيين'),
(2485, 'MSG_Users', 2, 'المستخدمين'),
(2486, 'MSG_Url_Rewriting', 2, 'إعادة كتابة عنوان'),
(2487, 'MSG_Uploaded_Successfully', 2, 'تم تحميلها بنجاح'),
(2488, 'MSG_Updated_Successfully', 2, 'تم التحديث بنجاح'),
(2489, 'MSG_UNRECOGNISED_IMAGE_FILE', 2, 'غير معروف ملف صورة'),
(2490, 'MSG_UNRECOGNISED_FILE', 2, 'الملف غير معروف'),
(2491, 'MSG_Unauthorized_Access!', 2, 'دخول غير مرخص!'),
(2492, 'MSG_Unable_to_process_your_requset', 2, '                                                                                             غير قادر على معالجة طلبك'),
(2493, 'MSG_Tutor_Approval_Request_Setup_Successful', 2, 'المعلم طلب الموافقة الإعداد الناجح'),
(2494, 'MSG_Tutor_Approval_Requests', 2, 'المعلم يطلب الموافقة'),
(2495, 'MSG_Tutor_Approval_Form', 2, 'نموذج الموافقة المعلم'),
(2496, 'MSG_Third_Party_API', 2, 'الطرف الثالث واجهة برمجة التطبيقات'),
(2497, 'MSG_That_captcha_was_incorrect', 2, 'كلمة التحقق أدخلته غير صحيح.'),
(2498, 'MSG_Testimonial', 2, 'شهادة'),
(2499, 'MSG_Terms_and_Condition_and_Privacy_Policy_are_mandatory.', 2, 'أحكام وشروط وسياسة الخصوصية إلزامية.'),
(2500, 'MSG_Teacher_Reviews', 2, 'التعليقات المعلم'),
(2501, 'MSG_Teacher_Preferences', 2, 'تفضيلات المعلم'),
(2502, 'MSG_Teacher_Approval_Request_Setup_Successful', 2, 'الموافقة المعلم طلب الإعداد الناجح'),
(2503, 'MSG_Teacher_Approval_Requests', 2, 'المعلم يطلب الموافقة'),
(2504, 'MSG_Teacher_Approval_Form', 2, 'نموذج الموافقة المعلم'),
(2505, 'MSG_SUCCESS_USER_SIGNUP_EMAIL_VERIFICATION_PENDING', 2, 'العضو نجاح الاشتراك البريد الإلكتروني التحقق من الانتظار'),
(2506, 'MSG_Success', 2, 'نجاح'),
(2507, 'MSG_States', 2, 'تنص على'),
(2508, 'MSG_SSL_NOT_INSTALLED_FOR_WEBSITE_Try_to_Save_data_without_Enabling_ssl', 2, 'SSL غير مثبتة حصول على موقع الويب محاولة لحفظ البيانات دون تمكين SSL'),
(2509, 'MSG_Social_Platform', 2, 'البرنامج الاجتماعي'),
(2510, 'MSG_Server', 2, '                                                                                                               شبكة العميل \n'),
(2511, 'MSG_Seo', 2, '                                                                                                سيو/ محرك البحث الامثل'),
(2512, 'MSG_Sales_Report', 2, 'تقرير المبيعات'),
(2513, 'MSG_Reviews', 2, 'التعليقات'),
(2514, 'MSG_Redirecting', 2, 'إعادة توجيه'),
(2515, 'MSG_Record_Updated_Successfully', 2, 'سجل تحديث بنجاح'),
(2516, 'MSG_Read_Only', 2, 'يقرأ فقط'),
(2517, 'MSG_Read_and_Write', 2, 'اقرا و اكتب'),
(2518, 'MSG_Qualification_Setup_Successful', 2, 'إعداد التأهيل الناجح'),
(2519, 'MSG_Qualification_Removed_Successfuly', 2, 'تصفيات إزالتها بنجاح'),
(2520, 'MSG_Please_verify_your_email', 2, 'يرجى التحقق من البريد الإلكتروني الخاص بك'),
(2521, 'MSG_Please_select_a_Profile_Pic', 2, 'الرجاء اختيار الملف صورة'),
(2522, 'MSG_Please_select_a_file', 2, 'الرجاء تحديد ملف'),
(2523, 'MSG_Payment_Methods', 2, 'طرق الدفع'),
(2524, 'MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', 2, 'كلمة السر يجب أن يكون ثمانية أحرف أبجدية و'),
(2525, 'MSG_PASSWORD_CHANGED_SUCCESSFULLY', 2, 'تم تغيير الرقم السري بنجاح'),
(2526, 'MSG_Order_Payment_Gateway_Description_{website-name}_{order-id}', 2, 'أجل بوابة الدفع الوصف {الموقع اسم} {ترتيب معرف}'),
(2527, 'MSG_Order_Payment_Gateway_Description', 2, 'أجل بوابة الدفع الوصف'),
(2528, 'MSG_Order_Data_Not_Found', 2, 'طلب بيانات لم يتم العثور على'),
(2529, 'MSG_Options', 2, 'خيارات'),
(2530, 'MSG_No_Record_Found', 2, 'لا يوجد سجلات'),
(2531, 'Msg_No_Comments_on_this_blog_post', 2, '                                                                                 لا يوجد تعليقات على هذا مدونة وظيفة'),
(2532, 'MSG_NOTIFICATION_EMAIL_COULD_NOT_BE_SENT', 2, 'إشعار البريد الإلكتروني تعذر إرسال'),
(2533, 'MSG_None', 2, 'لا شيء'),
(2534, 'MSG_New_Message_Arrived', 2, 'رسالة جديدة وصلت'),
(2535, 'MSG_Navigation_Management', 2, 'إدارة الملاحة'),
(2536, 'MSG_Meta_Tags', 2, 'العلامات الفوقية'),
(2537, 'MSG_Message_Submitted_Successfully!', 2, 'رسالة قدمت بنجاح!'),
(2538, 'MSG_Media', 2, 'وسائل الإعلام'),
(2539, 'MSG_Manage_Purchased_lessons', 2, 'إدارة الدروس المشتراة'),
(2540, 'MSG_Manage_Issues_Reported', 2, 'إدارة القضايا المبلغ عنها'),
(2541, 'MSG_Maintenance_Mode_Text', 2, 'النص وضع الصيانة'),
(2542, 'MSG_Login_attempt_limit_exceeded._Please_try_after_some_time.', 2, 'محاولة دخول تجاوز حد. يرجى المحاولة بعد مرور بعض الوقت.'),
(2543, 'MSG_Local', 2, 'محلي'),
(2544, 'MSG_Live_Chat', 2, 'دردشة مباشرة'),
(2545, 'MSG_Link_is_invalid_or_expired!', 2, 'الرابط غير صالح أو منتهي!'),
(2546, 'MSG_Link_is_invalid_or_expired', 2, 'الرابط غير صالح أو منتهي'),
(2547, 'MSG_Language_Removed_Successfuly!', 2, 'اللغة إزالتها بنجاح!'),
(2548, 'MSG_Language_Labels', 2, 'تسميات اللغة'),
(2549, 'MSG_Invalid_Username_or_Password', 2, 'خطأ في اسم المستخدم أو كلمة مرور'),
(2550, 'MSG_INVALID_RESET_PASSWORD_REQUEST', 2, 'صالح طلب إعادة تعيين كلمة المرور'),
(2551, 'MSG_Invalid_request_Or_Inactive_Record', 2, 'طلب غير صالح أو غير فعال سجل'),
(2552, 'MSG_INVALID_REQUEST', 2, 'طلب غير صالح'),
(2553, 'MSG_Invalid_Order', 2, 'غير صالح ترتيب'),
(2554, 'MSG_Invalid_Giftcard_code', 2, 'كود جيفتكارد غير صالح'),
(2555, 'MSG_INVALID_FILE_MIME_TYPE', 2, 'ملف غير صالح نوع مايم'),
(2556, 'MSG_INVALID_FILE_EXTENSION', 2, 'ملحق الملف غير صالح'),
(2557, 'MSG_Invalid_Access', 2, 'دخول غير صالح'),
(2558, 'MSG_INVAILD_VERIFICATION_LINK', 2, '                                                                                                 رابط التأكيد غير صالحة'),
(2559, 'MSG_Image_Uploaded_Successfully', 2, 'صورة محملة بنجاح'),
(2560, 'MSG_Home_Page_Slide_Management', 2, 'إدارة الشريحة الصفحة الرئيسية'),
(2561, 'MSG_Go_to_Wallet', 2, 'الانتقال إلى محفظة'),
(2562, 'MSG_Go_to_Giftcards_Purchased', 2, 'انتقل إلى بطاقات الهدايا التي تم شراؤها'),
(2563, 'MSG_Giftcard_Redeem_successfully', 2, 'جيفتكارد ترويجي بنجاح'),
(2564, 'MSG_GIFTCARDS', 2, 'بطاقات الهدايا'),
(2565, 'MSG_General_Settings', 2, 'الاعدادات العامة'),
(2566, 'MSG_General', 2, 'جنرال لواء'),
(2567, 'MSG_File_uploaded_successfully', 2, 'تم رفع الملف بنجاح'),
(2568, 'MSG_File_deleted_successfully', 2, 'ملف المحذوفة بنجاح'),
(2569, 'MSG_ERROR_INVALID_REQUEST', 2, 'خطأ طلب غير صالح'),
(2570, 'MSG_ERROR_INVALID_ACCESS', 2, 'خطأ غير صالح الوصول'),
(2571, 'MSG_Email_Updated._Please_Login_again_in_your_profile_with_new_email', 2, 'البريد الإلكتروني التحديث. الرجاء الدخول مرة أخرى في الملف الشخصي الخاص بك مع نيو البريد الإلكتروني'),
(2572, 'MSG_Email_Updated._Please_Login_again_in_your_profile', 2, 'البريد الإلكتروني التحديث. الرجاء الدخول مرة أخرى في ملف التعريف الخاص بك'),
(2573, 'MSG_Email_Templates', 2, 'قوالب البريد الإلكتروني'),
(2574, 'MSG_Email_changed_successfully', 2, 'البريد الإلكتروني تغيير بنجاح'),
(2575, 'MSG_Email', 2, 'البريد الإلكتروني'),
(2576, 'MSG_Discount_Coupons', 2, 'كوبونات خصم'),
(2577, 'MSG_Deleted_successfully', 2, 'حذف بنجاح'),
(2578, 'MSG_Currency_Management', 2, 'إدارة العملة'),
(2579, 'MSG_Coupon_Setup_Successful.', 2, 'إعداد قسيمة الناجح.'),
(2580, 'MSG_Countries', 2, 'بلدان'),
(2581, 'MSG_COULD_NOT_SAVE_FILE', 2, 'لا يمكن حفظ الملف'),
(2582, 'MSG_Content_Pages', 2, 'صفحات المحتوى'),
(2583, 'MSG_Content_Blocks', 2, 'كتل المحتوى'),
(2584, 'MSG_Congratulations', 2, 'تهانينا'),
(2585, 'MSG_Commission', 2, 'عمولة'),
(2586, 'MSG_Clear_Search', 2, 'مسح البحث'),
(2587, 'MSG_Category_Setup_Successful', 2, 'إعداد فئة الناجح'),
(2588, 'MSG_Blog_Post_Setup_Successful', 2, 'مقالات إعداد المشاركة الناجحة'),
(2589, 'MSG_Blog_Posts', 2, 'بلوق المشاركات'),
(2590, 'MSG_Blog_Contributions', 2, 'بلوق الاشتراكات'),
(2591, 'Msg_Blog_Comment_Saved_and_awaiting_admin_approval.', 2, 'بلوق تعليق المحفوظة وانتظار المسؤول الموافقة.'),
(2592, 'MSG_Blog_Comments', 2, 'بلوق تعليقات'),
(2593, 'MSG_Blog_Categories', 2, 'بلوق الفئات'),
(2594, 'MSG_Bible_Content', 2, 'محتوى الفيديو'),
(2595, 'MSG_Banners', 2, 'لافتات'),
(2596, 'MSG_Already_Logged_in,_Please_try_after_reloading_the_page', 2, 'بالفعل بتسجيل الدخول، الرجاء حاول بعد إعادة تحميل الصفحة'),
(2597, 'MSG_Allowed_Extensions', 2, 'الامتدادات المسموح بها'),
(2598, 'MSG_Admin_Users', 2, 'المستخدمين مشرف'),
(2599, 'MSG_Admin_Dashboard', 2, 'المشرف وحة'),
(2600, 'L_debited', 2, 'خصم'),
(2601, 'Lesson_Status', 1, 'Status'),
(2602, 'Lesson_Status', 2, 'الحالة'),
(2603, 'LBL_{x}_Minute_Lesson', 2, '{س} الدرس دقيقة'),
(2604, 'LBL_Zip', 2, 'الرمز البريدي'),
(2605, 'LBL_You_Tube_Video_Link', 2, 'أنت الأنبوبة وصلة فيديو'),
(2606, 'LBL_You_have_already_Bought_this_plan,_Please_choose_some_other_Plan', 2, 'لقد اشترى إذا هذه الخطة، الرجاء اختيار بعض خطة أخرى'),
(2607, 'LBL_You_Are_Logged_Out_Successfully', 2, 'تم تسجيل الخروج بنجاح'),
(2608, 'LBL_Youtube_Icon', 2, 'يوتيوب أيقونة'),
(2609, 'LBL_Your_session_seems_to_be_expired', 2, 'الدورة الخاصة بك ويبدو أن منتهية الصلاحية'),
(2610, 'LBL_Your_current_Password_mis-matched!', 2, 'لديك كلمة المرور الحالية ميس مطابقة!'),
(2611, 'LBL_Your_Application_Approved', 2, 'الموافقة على طلبك'),
(2612, 'LBL_Yes', 2, 'نعم'),
(2613, 'LBL_Yearly', 2, 'سنوي');
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(2614, 'LBL_Write_about_yourself_and_your_qualifications', 2, 'اكتب عن نفسك ومؤهلاتك'),
(2615, 'LBL_Withdrawal_Request_Pending', 2, 'سحب طلب قيد الانتظار'),
(2616, 'LBL_Withdrawal_Request_Declined', 2, 'المرفوضة سحب طلب'),
(2617, 'LBL_Withdrawal_Request_Completed', 2, 'اكتمل انسحاب طلب'),
(2618, 'LBL_Withdrawal_Request_Approved', 2, 'وافق سحب طلب'),
(2619, 'LBL_Withdrawal_Requests', 2, 'طلبات الانسحاب'),
(2620, 'LBL_Withdrawal', 2, 'انسحاب'),
(2621, 'LBL_Wishlist/Favorites', 2, 'سلة / المفضلة'),
(2622, 'LBL_What_Language_I_am_Teaching', 2, 'ما اللغة أقوم بتدريس مادة'),
(2623, 'LBL_What_language_do_you_want_to_teach?', 2, 'ما هي اللغة التي ترغب في تدريس؟'),
(2624, 'LBL_What_Language_Do_You_Want_To_Teach', 2, 'ما هي اللغة التي ترغب في تدريس'),
(2625, 'Lbl_What_do_you_think', 2, 'ما رأيك'),
(2626, 'Lbl_We_are_constantly_looking_for_writers_and_contributors_to_help_us_create_great_content_for_our_blog_visitors._If_you_can_curate_content_that_you_and_our_visitors_would_love_to_read_and_share,_this_place_is_for_you.', 2, 'نحن باستمرار تبحث عن الكتاب والمساهمون لمساعدتنا على خلق عظيم المحتوى لزوارنا البلوغ. إذا كنت تستطيع خوري المحتوى الذي وزوارنا أحب أن اقرأ وشارك، هذا المكان بالنسبة لك.'),
(2627, 'LBL_Weekly', 2, 'أسبوعي'),
(2628, 'LBL_Wed', 1, 'Wed'),
(2629, 'LBL_Wed', 2, 'الأربعاء'),
(2630, 'LBL_We\'d_love_to_send_you_our_Newsletter', 2, 'كنا نحب أن نرسل لك نشرتنا الإخبارية'),
(2631, 'LBL_Watermark_Image_logo_size', 2, 'العلامة المائية صورة شعار الحجم'),
(2632, 'LBL_Was_this_article_helpful?', 2, 'هل كان المقال مساعدا؟'),
(2633, 'LBL_Wallet_Deduction', 2, 'محفظة خصم'),
(2634, 'LBL_View_User_Detail', 2, 'عرض التفاصيل العضو'),
(2635, 'LBL_View_Store', 2, 'عرض متجر'),
(2636, 'LBL_View_Schedules', 2, 'عرض جداول'),
(2637, 'LBL_View_Plans', 2, 'خطط مشاهدة'),
(2638, 'LBL_View_Order_Detail', 2, 'عرض ترتيب التفاصيل'),
(2639, 'LBL_View_Lesson_Details', 2, 'تفاصيل عرض الدرس'),
(2640, 'LBL_View_Lesson_Detail', 2, 'عرض التفاصيل الدرس'),
(2641, 'LBL_View_Lessons', 2, 'عرض الدروس'),
(2642, 'LBL_View_Issue_Detail', 2, 'عرض التفاصيل العدد'),
(2643, 'LBL_View_Flashcards', 2, 'عرض البطاقات التعليمية'),
(2644, 'LBL_View_Details', 2, 'عرض التفاصيل'),
(2645, 'LBL_View_Detail', 1, 'View Detail'),
(2646, 'LBL_View_Detail', 2, 'عرض التفاصيل'),
(2647, 'LBL_View_all', 2, 'عرض جميع'),
(2648, 'LBL_Video_Youtube_Link', 2, 'فيديو يوتيوب لينك'),
(2649, 'LBL_Video_Content', 1, 'Video Content'),
(2650, 'LBL_Video_Content', 2, 'محتوى الفيديو'),
(2651, 'LBL_verified', 2, 'تم التحقق'),
(2652, 'LBL_Use_SSL', 2, 'استخدام SSL'),
(2653, 'LBL_Uses_Per_Customer', 2, 'الاستخدامات لكل العملاء'),
(2654, 'LBL_Uses_Per_Coupon', 2, 'الاستخدامات لكل قسيمة'),
(2655, 'LBL_User_Wallet', 2, 'محفظة العضو'),
(2656, 'LBL_User_View', 2, 'عضو معاينة'),
(2657, 'LBL_User_Type', 2, 'نوع المستخدم'),
(2658, 'LBL_User_Transactions', 2, 'المعاملات العضو'),
(2659, 'LBL_User_Setup', 2, 'إعداد المستخدم'),
(2660, 'LBL_User_Profile_Image_Dimension', 2, 'الصورة الشخصية للمستخدم البعد'),
(2661, 'LBL_User_Phone', 2, 'الهاتف المستخدم'),
(2662, 'LBL_User_Permission', 2, 'إذن المستخدم'),
(2663, 'LBL_user_need_to_verify_their_email_address_provided_during_registration', 2, 'تحتاج مستخدم للتحقق منها عنوان البريد الإلكتروني الذي قدم خلال التسجيل'),
(2664, 'LBL_User_IP', 2, 'العضو الملكية الفكرية'),
(2665, 'LBL_User_ID:', 2, 'معرف المستخدم:'),
(2666, 'LBL_User_Details', 2, 'بيانات المستخدم'),
(2667, 'LBL_User_Date', 2, 'التسجيل المستخدم'),
(2668, 'LBL_User_Agent', 2, 'وكيل المستخدم'),
(2669, 'LBL_Username/Email', 2, 'اسم المستخدم / البريد الإلكتروني'),
(2670, 'LBL_User', 2, 'المستعمل'),
(2671, 'LBL_Url_Rewrite_Setup', 2, 'إعداد رابط إعادة كتابة'),
(2672, 'LBL_Url_List', 2, 'قائمة رابط'),
(2673, 'LBL_URL', 2, '                                                                                                محدد موقع الموارد/ url    '),
(2674, 'LBL_Upload_Image', 2, 'تحميل الصور'),
(2675, 'LBL_Upload_Flag', 2, 'تحميل العلم'),
(2676, 'LBL_Upload_File', 2, 'رفع ملف'),
(2677, 'LBL_Upload_Certificate', 2, 'شهادة إيداع'),
(2678, 'LBL_Uploaded_Successfully', 2, 'تم تحميلها بنجاح'),
(2679, 'LBL_Update_Status', 2, 'تحديث الحالة'),
(2680, 'LBL_Update_Sitemap', 2, 'تحديث خريطة الموقع'),
(2681, 'LBL_Update_Lesson_Status', 2, 'حالة التحديث الدرس'),
(2682, 'LBL_Updated_Successfully', 2, 'تم التحديث بنجاح'),
(2683, 'LBL_Update', 2, 'تحديث'),
(2684, 'LBL_UN:', 2, 'الأمم المتحدة:'),
(2685, 'LBL_U', 2, 'U'),
(2686, 'LBL_Twitter_Username', 2, 'اسم مستخدم التويتر'),
(2687, 'LBL_Twitter_Icon', 2, 'تويتر أيقونة'),
(2688, 'LBL_Twitter_App_Secret', 2, 'تويتر التطبيقات السرية'),
(2689, 'LBL_Twitter_APP_KEY', 2, 'تويتر التطبيقات مفتاح'),
(2690, 'LBL_Tutor_Request_Detail', 2, 'المعلم طلب التفاصيل'),
(2691, 'LBL_Tutor_Requests', 2, 'يطلب المعلم'),
(2692, 'LBL_Tutor_Preferences', 2, 'تفضيلات المعلم'),
(2693, 'LBL_Tutor_Banner_Slogan', 2, 'المعلم راية شعار'),
(2694, 'LBL_Tutor_Approval_Requests', 2, 'المعلم يطلب الموافقة'),
(2695, 'LBL_Tutor_Approval_Form', 2, 'نموذج الموافقة المعلم'),
(2696, 'LBL_Tutor', 2, 'مدرس'),
(2697, 'LBL_Tue', 1, 'Tue'),
(2698, 'LBL_Tue', 2, 'الثلاثاء'),
(2699, 'LBL_Trial_Lesson', 2, 'الدرس محاكمة'),
(2700, 'LBL_Transaction_Key', 2, 'مفتاح عملية'),
(2701, 'LBL_Transaction_Id', 2, 'رقم المعاملة'),
(2702, 'LBL_Transactions', 2, 'المعاملات'),
(2703, 'LBL_Total_Users', 2, 'عدد الأعضاء'),
(2704, 'LBL_Total_Sign_ups', 2, 'مجموع يرفع تسجيل'),
(2705, 'LBL_Total_Seats', 1, 'Total Seats'),
(2706, 'LBL_Total_Seats', 2, 'مجموع مقاعد'),
(2707, 'LBL_Total_Revenue_from_lessons', 2, 'إجمالي الإيرادات من الدروس'),
(2708, 'LBL_Total_Payable', 2, 'إجمالي مستحق'),
(2709, 'LBL_Total_lessons', 2, 'عدد الدروس'),
(2710, 'LBL_Total_Earning_From_Lessons', 2, 'إجمالي العائد من الدروس'),
(2711, 'LBL_Total_Earnings_to_Admin', 2, '                                                                                                 إجمالي الأرباح للمشرف'),
(2712, 'LBL_Total_Commisions_From_Lessons', 2, 'إجمالي عمولة من الدروس'),
(2713, 'LBL_Top_Lesson_Languages', 2, 'أعلى الدرس اللغات'),
(2714, 'LBL_Top_Languages_Report', 2, 'أعلى تقرير اللغات'),
(2715, 'LBL_Tommorrow', 2, 'غدا'),
(2716, 'LBL_tls', 2, 'TLS'),
(2717, 'LBL_Time_of_Day_-_24hrs', 1, 'Time Of Day - 24hrs'),
(2718, 'LBL_Time_of_Day_-_24hrs', 2, '                                                                                             الوقت من اليوم - ٢٤ ساعة'),
(2719, 'LBL_Timezone_:', 1, 'Timezone :'),
(2720, 'LBL_Timezone_:', 2, 'وحدة زمنية :'),
(2721, 'LBL_Time', 2, 'زمن'),
(2722, 'LBL_Thu', 1, 'Thu'),
(2723, 'LBL_Thu', 2, 'الخميس'),
(2724, 'LBL_This_will_send_Test_Email_to_Site_Owner_Email', 2, 'هذا سوف ترسل رسالة إلى اختبار مالك الموقع الالكتروني'),
(2725, 'LBL_This_will_be_displayed_on_your_cms_Page', 2, 'هذا وسيتم عرض الخاص بك على صفحة CMS'),
(2726, 'LBL_This_will_be_displayed_in_30x30_on_your_store.', 2, '                                                                        هذا سيتم عرضها في٣٠*٣٠ في مخزن لديك.'),
(2727, 'LBL_This_lesson_is_Unscheduled._schedule_it.', 2, 'هذا الدرس هو غير المجدولة. الجدول الزمني لها.'),
(2728, 'LBL_This_lesson_is_Unscheduled._Encourage_your_student_to_schedule_it.', 2, 'هذا الدرس هو غير المجدولة. تشجيع الطلاب لديك لجدول و.'),
(2729, 'LBL_This_lesson_is_completed._Encourage_your_learner_to_rate_it.', 2, 'اكتمال هذا الدرس. تشجيع المتعلم لديك لقيم.'),
(2730, 'LBL_This_is_your_default_currency', 2, 'هذا هو الافتراضي الخاص بك العملات'),
(2731, 'LBL_This_is_the_Twitter_secret_key_used_for_authentication_and_other_Twitter_related_plugins_support.', 2, 'هذا هو سر تويتر مفتاح تستخدم للحصول على مصادقة وأخرى ذات تويتر الإضافات الدعم.'),
(2732, 'LBL_This_is_the_site_tracker_script,_used_to_track_and_analyze_data_about_how_people_are_getting_to_your_website._e.g.,_Google_Analytics.', 2, 'هذا هو موقع المقتفي السيناريو، تستخدم لتعقب وتحليل البيانات عن كيف الناس الوصول إلى موقع الويب الخاص بك. على سبيل المثال، برنامج Google Analytics.'),
(2733, 'LBL_This_is_the_minimum_withdrawable_amount.', 2, 'هذا هو الحد الأدنى للسحب المبلغ.'),
(2734, 'LBL_This_is_the_minimum_interval_in_days_between_two_withdrawal_requests.', 2, 'هذا هو الحد الأدنى الفاصل الزمني في الأيام بين الطلبين الانسحاب.'),
(2735, 'LBL_This_is_the_Mailchimp\'s_subscribers_List_ID.', 2, 'هذا هو المشتركين وميل تشيمب قائمة رقم.'),
(2736, 'LBL_This_is_the_Mailchimp\'s_application_key_used_in_subscribe_and_send_newsletters.', 2, 'مفتاح التطبيق هذا هو ميل تشيمب والمستخدمة في الاشتراك وإرسال النشرات الإخبارية.'),
(2737, 'LBL_This_is_the_live_chat_script/code_provided_by_the_3rd_party_API_for_integration.', 2, 'هذا هو سيناريو الدردشة / القانون المقدمة من الطرف 3 API لالتكامل.'),
(2738, 'LBL_This_is_the_Google_Plus_id_client_secret_key_used_for_authentication.', 2, 'هذا هو جوجل بلس رقم العميل السري الرئيسية المستخدمة للمصادقة.'),
(2739, 'LBL_This_is_the_google_plus_developer_key.', 2, 'هذا هو جوجل بلس المطور مفتاح.'),
(2740, 'LBL_This_is_the_Google_map_api_key_used_to_get_user_current_location.', 2, 'هذه هي خريطة جوجل مفتاح API المستخدمة للحصول على العضو الموقع الحالي.'),
(2741, 'LBL_This_is_the_Google_Analytics_ID._Ex._UA-xxxxxxx-xx.', 2, 'هذا هو جوجل تحليلات الهوية. السابق. UA-XXXXXXX-س س.'),
(2742, 'LBL_This_is_the_Facebook_secret_key_used_for_authentication_and_other_Facebook_related_plugins_support.', 2, 'هذا هو سر الفيسبوك مفتاح تستخدم للحصول على مصادقة وأخرى الفيسبوك ذات الإضافات الدعم.'),
(2743, 'LBL_This_is_the_application_Site_key_used_for_Google_Recaptcha.', 2, 'هذا هو مفتاح الموقع تطبيق تستخدم لجوجل اختبار Recaptcha.'),
(2744, 'LBL_This_is_the_application_secret_key_used_in_Analytics_dashboard.', 2, 'هذا هو التطبيق السري الرئيسية المستخدمة في لوحة تحليلات.'),
(2745, 'LBL_This_is_the_application_Secret_key_used_for_Google_Recaptcha.', 2, 'هذا هو التطبيق السري الرئيسية المستخدمة لجوجل اختبار Recaptcha.'),
(2746, 'LBL_This_is_the_application_ID_used_in_post.', 2, 'هذا هو التطبيق رقم المستخدمة في المشاركة.'),
(2747, 'LBL_This_is_the_application_ID_used_in_login_and_post.', 2, 'هذا هو التطبيق رقم المستخدمة في الدخول والمشاركة.'),
(2748, 'LBL_This_is_the_application_Client_Id_used_to_Login.', 2, 'هذا هو التطبيق العميل رقم المستخدمة لتسجيل الدخول.'),
(2749, 'LBL_This_is_the_application_Client_Id_used_in_Analytics_dashboard.', 2, 'هذا هو التطبيق العميل رقم المستخدمة في لوحة تحليلات.'),
(2750, 'LBL_This_is_the_api_key_used_in_push_notifications.', 2, 'هذا هو مفتاح API المستخدمة في الإخطارات دفع.'),
(2751, 'LBL_This_is_required_for_Twitter_Card_code_SEO_Update', 2, 'هذا هو المطلوب للحصول على بطاقة تويتر مدونة سيو تحديث'),
(2752, 'LBL_This_is_maximum_commission/Fees_that_will_be_charged_on_a_particular_product.', 2, 'هذه اللجنة الحد الأقصى هو / الرسوم التي سيتم تفرض على وجه الخصوص المنتج.'),
(2753, 'LBL_These_prices_are_Unlocked', 2, 'هذه الأسعار هي مقفلة'),
(2754, 'LBL_These_Prices_are_locked', 2, 'هذه الأسعار يتم تأمين'),
(2755, 'LBL_then_sub_record_id_will_be_', 2, 'ثم الفرعية سجل رقم سيكون'),
(2756, 'LBL_then_record_id_will_be_', 2, 'ثم سجل رقم سيكون'),
(2757, 'LBL_then_controller_will_be_', 2, 'ثم المراقب سيكون'),
(2758, 'LBL_then_action_will_be_', 2, 'ثم العمل سيكون'),
(2759, 'LBL_Thank_you_for_submitting_your_application', 2, 'شكرا لتقديم التطبيق الخاص بك'),
(2760, 'LBL_Thank_you_for_applying_to_teach_on_{website-name}', 2, 'شكرا للتطبيق لتعليم في {الموقع اسم}'),
(2761, 'LBL_Testimonial_User_Name', 2, 'شهادة اسم العضو'),
(2762, 'LBL_Testimonial_Title', 2, 'شهادة عنوان'),
(2763, 'LBL_Testimonial_Text', 2, 'نص شهادة'),
(2764, 'LBL_Testimonial_Setup', 2, 'إعداد شهادة'),
(2765, 'LBL_Testimonial_Media_setup', 2, 'إعداد شهادة وسائل الإعلام'),
(2766, 'LBL_Testimonial_Identifier', 2, 'شهادة معرف'),
(2767, 'LBL_Testimonials_Listing', 2, 'قائمة الشهادات'),
(2768, 'LBL_Terms_and_Conditions_Page', 2, 'الاحكام والشروط الصفحة'),
(2769, 'LBL_Template_Name', 2, 'اسم القالب'),
(2770, 'LBL_Telephone', 2, 'هاتف'),
(2771, 'LBL_Teaching_language_Setup', 2, 'إعداد تدريس اللغة'),
(2772, 'LBL_Teaching_Language_Listing', 2, 'قائمة تعليم اللغة'),
(2773, 'LBL_Teacher_Request_Detail', 2, 'المعلم طلب التفاصيل'),
(2774, 'LBL_Teacher_Requests', 2, 'يطلب المعلم'),
(2775, 'LBL_Teacher_Performance_Report', 2, 'تقرير أداء المعلم'),
(2776, 'LBL_Teacher_Name', 2, 'اسم المعلم'),
(2777, 'LBL_Teacher_Join_Time', 2, 'مدرس تاريخ الوقت'),
(2778, 'LBL_Teacher_has_been_marked_as_favourite_successfully', 2, 'المعلم تم وضع علامة المفضلة بنجاح'),
(2779, 'LBL_Teacher_Free_Trial_Booked_for_Order:_{order-id}', 2, 'المعلم التجريبي المجاني حجز للطلب: {أجل معرف}'),
(2780, 'LBL_Teacher_End_Time', 2, 'المعلم نهاية الوقت'),
(2781, 'LBL_Teacher_Disable_the_Booking_before', 2, 'المعلم تعطيل الحجز قبل'),
(2782, 'LBL_Teacher_Dashboard', 2, 'المعلم لوحة'),
(2783, 'LBL_Teacher_Courses', 2, 'دورات المعلم'),
(2784, 'LBL_Teacher_Application', 2, 'طلب المعلم'),
(2785, 'LBL_Target', 2, 'استهداف'),
(2786, 'LBL_Tag_Setups', 2, 'الاجهزة العلامة'),
(2787, 'LBL_Tags', 2, 'الكلمات'),
(2788, 'LBL_Symbol_Right', 2, 'رمز اليمين'),
(2789, 'LBL_Symbol_Left', 2, 'رمز اليسار'),
(2790, 'LBL_Sun', 1, 'Sun'),
(2791, 'LBL_Sun', 2, 'شمس'),
(2792, 'LBL_Sub_Record_Id', 2, 'سجل الفرعية رقم'),
(2793, 'LBL_Subscribe', 2, 'الإشتراك'),
(2794, 'LBL_Submit_a_Request', 2, 'تقديم طلب'),
(2795, 'LBL_Subject', 2, 'موضوع'),
(2796, 'LBL_Subcategories', 2, 'الفئات الفرعية'),
(2797, 'LBL_Student_Remove_Successfully!', 2, 'طالب إزالة بنجاح!'),
(2798, 'LBL_Store_Owner_Email', 2, 'صاحب متجر البريد الإلكتروني'),
(2799, 'LBL_Status_Updated_Successfully!', 2, 'تحديث الحالة بنجاح!'),
(2800, 'LBL_Status_Updated_Successfully', 2, 'تحديث الحالة بنجاح'),
(2801, 'LBL_Statistics', 2, 'الإحصاء'),
(2802, 'LBL_State_Name', 2, 'اسم الولاية'),
(2803, 'LBL_State_Listing', 2, 'قائمة الدولة'),
(2804, 'LBL_State_Identifier', 2, 'معرف الدولة'),
(2805, 'LBL_State_Code', 2, 'رمز الدولة'),
(2806, 'LBL_States', 2, 'تنص على'),
(2807, 'LBL_State', 2, 'حالة'),
(2808, 'LBL_Start_Year', 2, 'سنة البدء'),
(2809, 'LBl_Start_Time', 1, 'Start Time'),
(2810, 'LBl_Start_Time', 2, 'وقت البدء'),
(2811, 'LBL_Start_Teaching', 2, 'بدء التدريس'),
(2812, 'LBL_Start_Conversation', 2, 'بدء المحادثة'),
(2813, 'LBL_Start_At', 1, 'Start At'),
(2814, 'LBL_Start_At', 2, 'تبدأ في'),
(2815, 'LBL_Starts_In', 1, 'Starts In'),
(2816, 'LBL_Starts_In', 2, 'يبدأ في'),
(2817, 'LBL_Start', 2, 'بداية'),
(2818, 'LBL_ssl', 2, 'خدمة تصميم المواقع'),
(2819, 'LBL_Sr._No', 2, 'الأب رقم'),
(2820, 'LBL_Sr.', 2, 'ريال سعودى.'),
(2821, 'LBL_Spoken_language_Setup', 2, 'إعداد اللغة المنطوقة'),
(2822, 'LBL_Spoken_Language_Listing', 2, 'قائمة اللغة المنطوقة'),
(2823, 'LBL_Spanish', 1, 'Spanish'),
(2824, 'LBL_Spanish', 2, 'الأسبانية'),
(2825, 'LBL_Social_Platform_Setup_Successful', 2, 'إعداد منصة اجتماعية ناجحة'),
(2826, 'LBL_Social_Platform_Setup', 2, 'إعداد البرنامج الاجتماعي'),
(2827, 'LBL_Social_Platforms_Listing', 2, 'المنصات الاجتماعية القائمة'),
(2828, 'LBL_Social_Platform', 2, 'البرنامج الاجتماعي'),
(2829, 'LBL_Social_Media_logo_size', 2, 'وسائل الاعلام الاجتماعية شعار الحجم'),
(2830, 'LBL_SMTP_Username', 2, 'بروتوكول نقل البريد الإلكتروني اسم المستخدم'),
(2831, 'LBL_SMTP_Secure', 2, 'SMTP آمن'),
(2832, 'LBL_SMTP_Port', 2, 'منفذ SMTP'),
(2833, 'LBL_SMTP_Password', 2, 'بروتوكول نقل البريد الإلكتروني كلمة المرور'),
(2834, 'LBL_SMTP_Host', 2, 'بروتوكول نقل البريد الإلكتروني المضيف'),
(2835, 'LBL_Slot_is_already_added_for_1_to_1_class_whichever_first_booked_will_be_booked!', 1, 'Slot Is Already Added For 1 To 1 Class Whichever First Booked Will Be Booked!'),
(2836, 'LBL_Slot_is_already_added_for_1_to_1_class_whichever_first_booked_will_be_booked!', 2, 'يضاف فتحة إذا ل1 إلى 1 من الدرجة الأولى أيهما حجزت سيتم حجز!'),
(2837, 'LBL_Slide_URL', 2, 'الشريحة رابط'),
(2838, 'LBL_Slide_Title', 2, 'عنوان الشريحة'),
(2839, 'LBL_Slide_Setup', 2, 'إعداد الشرائح'),
(2840, 'LBL_Slide_Image_Setup', 2, 'شريحة إعداد الصورة'),
(2841, 'LBL_Slide_Identifier', 2, 'معرف الشرائح'),
(2842, 'LBL_Slide_Banner_Image', 2, 'شريحة راية صورة'),
(2843, 'LBL_Slides_List', 2, 'قائمة الشرائح'),
(2844, 'LBL_Slides', 2, 'الشرائح'),
(2845, 'LBL_Site_Tracker_Code', 2, 'موقع كود المقتفي'),
(2846, 'LBL_Site_Owner', 2, 'مالك الموقع'),
(2847, 'LBL_Site_Name', 2, 'اسم الموقع'),
(2848, 'LBL_Site_Key', 2, 'مفتاح الموقع'),
(2849, 'LBL_Single_Lesson_Price', 2, 'واحد الدرس الأسعار'),
(2850, 'LBL_Signing_up_for_{user-type}', 2, 'الاشتراك في {المستخدم من نوع}'),
(2851, 'Lbl_SHOW_MORE', 2, 'أظهر المزيد'),
(2852, 'LBL_Short_Description', 2, 'وصف قصير'),
(2853, 'LBL_Share_and_Earn', 2, 'مشاركة وكسب'),
(2854, 'LBL_Set_Transaction_Mode_To_Live_Environment', 2, 'مجموعة الوضع عملية لايف البيئة'),
(2855, 'LBL_Set_the_default_review_order_status_when_a_new_review_is_placed', 2, 'تعيين افتراضي مراجعة ترتيب أوضاع عندما يتم وضع نصيحة جديدة'),
(2856, 'LBL_Set_the_default_child_order_status_when_an_order_is_marked_Paid.', 2, 'تعيين افتراضي ترتيب الطفل الحالة عند وضع علامة دفعت النظام.'),
(2857, 'LBL_Setup', 2, 'اقامة'),
(2858, 'LBL_Session_Start_Time', 2, 'جلسة وقت بدء'),
(2859, 'LBL_Session_End_Time', 2, 'جلسة نهاية الوقت'),
(2860, 'LBL_Session_Date', 2, 'تاريخ الجلسة'),
(2861, 'LBL_September', 2, 'سبتمبر'),
(2862, 'LBL_Sep', 2, 'سبتمبر'),
(2863, 'LBL_SEO_Friendly_URL', 2, 'كبار المسئولين الاقتصاديين ودية رابط'),
(2864, 'LBL_Sent_To', 2, 'أرسلت ل'),
(2865, 'LBL_Sent_On', 2, 'أرسلت في'),
(2866, 'LBL_Sent_Email_Detail', 2, 'إرسال البريد الإلكتروني التفاصيل'),
(2867, 'LBL_Sent_Emails_List', 2, 'أرسلت قائمة رسائل البريد الإلكتروني'),
(2868, 'LBL_Sent_Emails', 2, 'رسائل البريد الإلكتروني المرسلة'),
(2869, 'LBL_Send_SMTP_Email', 2, 'إرسال بروتوكول نقل البريد الالكتروني'),
(2870, 'LBL_Send_Reset_Pasword_Email', 2, 'إرسال أعد ضبط كلمة المرور البريد الإلكتروني'),
(2871, 'LBL_Send_Request', 2, 'ارسل طلب'),
(2872, 'LBL_Send_Message', 2, 'إرسال رسالة'),
(2873, 'LBL_Send_Gift_Card', 1, 'Send Gift Card'),
(2874, 'LBL_Send_Gift_Card', 2, 'إرسال بطاقة هدية'),
(2875, 'LBL_Send_Email', 2, 'ارسل بريد الكتروني'),
(2876, 'LBL_Seller_Approval_Requests', 2, 'يطلب البائع الموافقة'),
(2877, 'LBL_Select_Website_Favicon', 2, 'حدد موقع فافيكون'),
(2878, 'LBL_Select_Watermark_Image', 2, 'اختر صورة مائية'),
(2879, 'LBL_Select_State', 2, 'اختر ولايه'),
(2880, 'LBL_Select_Social_Feed_Image', 2, 'اختر تغذية الاجتماعي صورة'),
(2881, 'LBL_Select_permission_for_all_modules', 2, 'حدد إذن لجميع الوحدات'),
(2882, 'LBL_Select_Payment_Status', 2, 'اختر الحالة الدفع'),
(2883, 'LBL_Select_Payment_Page_Logo', 2, 'حدد الدفع ل شعار'),
(2884, 'LBL_Select_Mobile_Logo', 2, 'شعار اختر موبايل'),
(2885, 'LBL_Select_Lesson_Plans', 2, 'خطط الدروس حدد'),
(2886, 'LBL_Select_Lesson_Plan', 2, 'حدد خطة الدرس'),
(2887, 'LBL_Select_Email_Template_Logo', 2, 'حدد بريد إلكتروني قالب الشعار'),
(2888, 'LBL_Select_Desktop_Logo', 2, 'شعار اختر سطح المكتب'),
(2889, 'LBL_Select_Apple_Touch_Icon', 2, 'اختر لمس Apple أيقونة'),
(2890, 'LBL_Select_an_option_to_pay_balance', 2, 'تحديد امكانية الدفع الرصيد'),
(2891, 'LBL_Select_all', 2, 'اختر الكل'),
(2892, 'LBL_Select_Admin_Logo', 2, 'حدد المسؤول شعار'),
(2893, 'LBL_Secret_Key', 2, 'سر مفتاح'),
(2894, 'LBL_Search_in_Order_Id,_Giftcard_Code', 2, 'بحث في ترتيب الهوية، وقانون جيفتكارد'),
(2895, 'LBL_Search_Classes', 1, 'Search Classes'),
(2896, 'LBL_Search_Classes', 2, 'بحث فئات'),
(2897, 'LBL_Search_By_Teacher_Name', 2, 'البحث عن طريق اسم المعلم'),
(2898, 'LBL_Screen', 2, 'شاشة'),
(2899, 'LBL_Schedule_Lesson', 1, 'Schedule Lesson'),
(2900, 'LBL_Schedule_Lesson', 2, 'جدول الدرس'),
(2901, 'LBL_Scheduled_Start_Time', 2, 'من المقرر وقت البدء'),
(2902, 'LBL_Scheduled_End_Time', 2, 'من المقرر نهاية الوقت'),
(2903, 'LBL_Sat', 1, 'Sat'),
(2904, 'LBL_Sat', 2, 'جلس'),
(2905, 'LBL_Same_Window', 2, 'نفس النافذة'),
(2906, 'LBL_Sales_Report', 2, 'تقرير المبيعات'),
(2907, 'LBL_Sales_Earnings', 2, 'أرباح المبيعات'),
(2908, 'LBL_S.No.', 2, '                                                                                                                 رقم سري\n'),
(2909, 'LBL_Root_categories', 2, 'الجذر الفئات'),
(2910, 'LBL_Right_To_Left', 1, 'Right To Left'),
(2911, 'LBL_Right_To_Left', 2, 'من اليمين الى اليسار'),
(2912, 'LBL_Reward_Points', 2, 'نقاط مكافأة'),
(2913, 'LBL_Rewards', 2, 'المكافآت'),
(2914, 'LBL_Review', 2, 'مراجعة'),
(2915, 'LBL_Reset_Pasword', 2, 'أعد ضبط كلمة المرور'),
(2916, 'LBL_Reset_Password_Msg', 2, '                                                                                        إعادة تعيين رسالة كلمة المرور'),
(2917, 'LBL_Reset_Password?', 2, 'إعادة تعيين كلمة المرور؟'),
(2918, 'LBL_Reset_Password', 2, 'إعادة تعيين كلمة المرور'),
(2919, 'Lbl_Reschedule_Reason_Is_Requried', 2, 'السبب جدولة هل ريكارد'),
(2920, 'LBL_Reschedule_Lesson', 1, 'Reschedule Lesson'),
(2921, 'LBL_Reschedule_Lesson', 2, 'الدرس إعادة جدولة'),
(2922, 'LBL_Rescheduled', 2, 'إعادة جدولتها'),
(2923, 'LBL_Request_Reschedule', 2, 'طلب إعادة جدولة'),
(2924, 'LBL_Request_Information', 2, 'طلب معلومات'),
(2925, 'LBL_Request_ID', 2, 'طلب معرف'),
(2926, 'LBL_Requests_List', 2, 'قائمة طلبات'),
(2927, 'LBL_Requested_Slot_is_not_available', 2, 'فتحة المطلوب غير متوفر'),
(2928, 'LBL_Requested_On', 2, 'طلب كود'),
(2929, 'LBL_Reporter', 2, 'مراسل'),
(2930, 'LBL_Reported_Time', 2, 'الوقت وذكرت'),
(2931, 'LBL_Reported_By', 2, 'تم عمل تقرير بواسطة'),
(2932, 'LBL_Reply_to_Email_Address', 2, 'الرد إلى عنوان البريد الإلكتروني'),
(2933, 'LBL_Replacement_Vars', 2, 'استبدال فار'),
(2934, 'LBL_Replacement_Caption', 2, 'التعليق على استبدال'),
(2935, 'LBL_Remove_Plan', 2, 'إزالة الخطة'),
(2936, 'LBL_Rejected', 2, 'مرفوض'),
(2937, 'LBL_Registeration_Successfull', 2, 'سجل للالنجاح'),
(2938, 'LBL_Reg._Date_To', 2, 'ريج. التاريخ إلى'),
(2939, 'LBL_Reg._Date_From', 2, 'ريج. التاريخ من'),
(2940, 'LBL_Reg._Date', 2, 'ريج. تاريخ'),
(2941, 'LBL_Reference_Number', 2, 'رقم المرجع'),
(2942, 'LBL_Redeem_Giftcard', 2, 'تخليص جيفتكارد'),
(2943, 'LBL_Redeem', 2, 'خلص'),
(2944, 'LBL_Record_Id', 2, 'سجل رقم'),
(2945, 'LBL_Record_Deleted_Successfully!', 2, 'سجل محذوفة بنجاح!'),
(2946, 'LBL_Recipient_Name', 2, 'اسم المستلم'),
(2947, 'LBL_Recipient_Email', 2, 'البريد الإلكتروني المستلم'),
(2948, 'LBL_Received_Payment', 2, 'تم استلام المبلغ'),
(2949, 'LBL_Reason_for_Cancellation', 2, 'سبب الالغاء'),
(2950, 'LBL_Read_More', 1, 'Read More'),
(2951, 'LBL_Read_More', 2, 'قراءة المزيد'),
(2952, 'LBL_Rating', 2, 'تقييم'),
(2953, 'LBL_Quit', 2, 'قريب'),
(2954, 'LBL_Quick_filters', 2, 'مرشحات سريعة'),
(2955, 'LBL_Qualifications', 2, 'مؤهلات'),
(2956, 'LBL_Purchased_Lessons', 2, 'الدروس التي تم شراؤها'),
(2957, 'LBL_Published_Date', 2, 'تاريخ النشر'),
(2958, 'LBL_Published', 2, 'نشرت'),
(2959, 'LBL_Publishable_Key', 2, 'للنشر مفتاح'),
(2960, 'LBL_Promotion', 2, 'ترقية وظيفية'),
(2961, 'LBL_Profile_progress', 1, 'Profile Progress'),
(2962, 'LBL_Profile_progress', 2, 'الملف الشخصي التقدم'),
(2963, 'LBL_Profile_Information', 2, 'معلومات الملف الشخصي'),
(2964, 'LBL_Profile_Info', 2, 'معلومات الشخصي'),
(2965, 'LBL_Product_Category_Page', 2, 'فئة المنتج الصفحة'),
(2966, 'LBL_Processing....', 2, 'معالجة....'),
(2967, 'LBL_Processing', 2, 'معالجة'),
(2968, 'LBL_Proceed', 2, 'تقدم'),
(2969, 'LBL_Privacy_Policy_Page', 2, 'الخصوصية الصفحة سياسة'),
(2970, 'LBL_Privacy_Policy', 2, 'سياسة خاصة'),
(2971, 'LBL_Price_Unlocked_Successfully!', 2, 'سعر مقفلة بنجاح!'),
(2972, 'LBL_Price_Locked_Successfully!', 2, 'سعر تأمين بنجاح!'),
(2973, 'LBL_Price::', 2, 'السعر::'),
(2974, 'LBL_Present', 2, 'حاضر'),
(2975, 'LBL_Prefix_with_{SITEROOT}_if_u_want_to_generate_system_site_url', 2, 'بادئة مع {siteroot}إذا كنت تريد إنشاء عنوان URL لموقع النظام'),
(2976, 'LBL_Preffered_Width_(in_pixels)', 2, 'أعطيت الأولوية العرض (في بكسل)'),
(2977, 'LBL_Preffered_Height_(in_pixels)', 2, 'ارتفاع أعطيت الأولوية (في بكسل)'),
(2978, 'LBL_Preferred_Dimensions_are', 2, 'فضل الأبعاد'),
(2979, 'LBL_Preferred_Dimensions', 2, 'الأبعاد المفضل'),
(2980, 'LBL_Preferred_Dashboard', 2, 'فضل لوحة'),
(2981, 'LBL_Preference_Title', 2, 'تفضيل عنوان'),
(2982, 'LBL_Preference_Identifier', 2, 'تفضيل معرف'),
(2983, 'LBL_Preferences___Listing', 2, 'تفضيلات قائمة'),
(2984, 'LBL_Preferences_updated_successfully!', 2, 'تفضيلات التحديث بنجاح!'),
(2985, 'LBL_Preferences_Test_Preparations__Listing', 2, 'تفضيلات الاستعدادات اختبار قائمة'),
(2986, 'LBL_Preferences_Teaches_Level__Listing', 2, 'تفضيلات توعية للقائمة المستوى'),
(2987, 'LBL_Preferences_Subjects__Listing', 2, 'تفضيلات الموضوعات قائمة'),
(2988, 'LBL_Preferences_Lessons__Listing', 2, 'تفضيلات الدروس قائمة'),
(2989, 'LBL_Preferences_Learner_Ages__Listing', 2, 'تفضيلات المتعلم العصور قائمة'),
(2990, 'LBL_Preferences_Accents__Listing', 2, 'تفضيلات لهجات قائمة'),
(2991, 'LBL_Preferences', 2, 'تفضيلات'),
(2992, 'LBL_Preference', 2, 'تفضيل'),
(2993, 'Lbl_Post_your_comments', 2, 'أضف تعليقاتك'),
(2994, 'LBL_Post_Title', 2, 'عنوان المقال'),
(2995, 'LBL_Post_Status', 2, 'وضع آخر'),
(2996, 'LBL_Post_Images', 2, 'الصور المشاركة'),
(2997, 'LBL_Post_Identifier', 2, 'آخر معرف'),
(2998, 'LBL_Post_Author_Name', 2, 'آخر اسم الكاتب'),
(2999, 'LBL_Posted_On', 2, 'نشر على'),
(3000, 'LBL_Posted', 2, 'نشر'),
(3001, 'LBL_Please_Update_Your_Timezone', 2, 'يرجى تحديث التوقيت'),
(3002, 'LBL_Please_select_the_system_you_wish_to_use_for_email_marketing.', 2, 'الرجاء تحديد نظام التي ترغب في استخدامها للتسويق الالكتروني.'),
(3003, 'LBL_Please_enter_the_email_address_registered_on_your_account.', 2, 'يرجى إدخال عنوان البريد الإلكتروني المسجل في الحساب الخاص بك.'),
(3004, 'LBL_Please_Complete_Profile_to_be_visible_on_teachers_listing_page', 2, 'الرجاء الكامل لمحة لتكون مرئية على المعلمين قائمة الصفحة'),
(3005, 'LBL_Please_choose', 2, 'اختر من فضلك'),
(3006, 'LBL_Please_Authenticate_google_to_be_able_to_post_on_google_calendar_%s', 1, 'Please Authenticate Google To Be Able To Post On Google Calendar %s'),
(3007, 'LBL_Please_Authenticate_google_to_be_able_to_post_on_google_calendar_%s', 2, 'يرجى مصادقة جوجل لتكون قادرة على المشاركة في تقويم Google٪ الصورة'),
(3008, 'LBl_Plan_Files', 2, 'الملفات خطة'),
(3009, 'LBl_Plan_Banner_Image', 2, 'خطة راية صورة'),
(3010, 'LBL_Pinterest_Icon', 2, 'بينتيريست أيقونة'),
(3011, 'LBL_Photo_Id', 2, 'الصورة رقم'),
(3012, 'LBL_Photo(s)', 2, 'الصور)'),
(3013, 'LBL_Phone_Number', 2, 'رقم الهاتف'),
(3014, 'LBL_Permissions', 2, 'أذونات'),
(3015, 'LBL_Percentage', 2, 'النسبة المئوية'),
(3016, 'LBL_Paypal', 2, 'باي بال'),
(3017, 'LBL_Payment_Status', 2, 'حالة السداد'),
(3018, 'LBL_Payment_Page_logo_size', 2, 'الدفع ل شعار الحجم'),
(3019, 'LBL_Payment_Method_Setup', 2, 'إعداد طريقة الدفع'),
(3020, 'LBL_Payment_Methods_Settings', 2, 'إعدادات طرق الدفع'),
(3021, 'LBL_Payment_Methods_List', 2, 'الدفع قائمة طرق'),
(3022, 'LBL_Payment_Method', 2, 'طريقة الدفع او السداد'),
(3023, 'LBL_Payment_From_User_Wallet', 2, 'الدفع من محفظة العضو'),
(3024, 'LBL_Payment_Failed', 2, 'عملية الدفع فشلت'),
(3025, 'LBL_Payment_Details_Added_Successfully', 2, 'تفاصيل الدفع واضاف بنجاح'),
(3026, 'LBL_Payable_Amount', 2, 'المبلغ المستحق'),
(3027, 'LBL_Password_Updated_Successfully', 2, 'تم تحديث كلمة السر بنجاح'),
(3028, 'LBL_Password_/_Email', 2, 'كلمه المرور للبريد الالكتروني'),
(3029, 'LBL_Paid', 2, 'دفع'),
(3030, 'LBL_Page_Title', 2, 'عنوان الصفحة'),
(3031, 'LBL_Page_Identifier', 2, 'الصفحة معرف'),
(3032, 'LBL_Page_Content', 2, 'محتوى الصفحة'),
(3033, 'LBL_Pages', 2, 'صفحات'),
(3034, 'LBL_Package_Lessons', 2, 'حزمة الدروس'),
(3035, 'LBL_Other_Meta_Tags', 2, 'غيرها من العلامات الفوقية'),
(3036, 'LBL_Other_Info_Instructions', 2, 'معلومات أخرى تعليمات'),
(3037, 'LBL_Other_Articles', 2, 'مواد أخرى'),
(3038, 'LBL_Original_URL', 2, 'الأصل رابط'),
(3039, 'LBL_Original', 2, 'أصلي'),
(3040, 'LBL_Order_Updated_Successfully', 2, 'أجل التحديث بنجاح'),
(3041, 'LBL_Order_Updated_Successful', 1, 'Order Updated Successful'),
(3042, 'LBL_Order_Updated_Successful', 2, 'أجل التحديث الناجحة'),
(3043, 'LBL_Order_Total', 2, 'الطلب الكلي'),
(3044, 'LBL_Order_Status_(Processed)', 2, 'ترتيب أوضاع (المصنعة)'),
(3045, 'LBL_Order_Status_(Pending)', 2, 'حالة الطلب: في انتظار)'),
(3046, 'LBL_Order_Status_(Others)', 2, 'ترتيب أوضاع (الآخرين)'),
(3047, 'LBL_Order_Status_(Initial)', 2, 'ترتيب أوضاع (الأولي)'),
(3048, 'LBL_Order_Status_(Completed)', 2, 'ترتيب أوضاع (أنجزت)'),
(3049, 'LBL_Order_Status', 2, 'حالة الطلب'),
(3050, 'LBL_Order_Sales', 2, 'ترتيب المبيعات'),
(3051, 'LBL_Order_Return_Requests', 2, 'يطلب النظام عودة'),
(3052, 'LBL_ORDER_PLACED_{order-id}', 2, 'ترتيب وضعها {ترتيب معرف}'),
(3053, 'LBL_Order_Payment_Status_Pending', 2, 'حالة الطلب الدفع الانتظار'),
(3054, 'LBL_Order_Payment_Status_Paid', 2, 'حالة الطلب الدفع المدفوعة'),
(3055, 'LBL_Order_Payment_Status_Cancelled', 2, 'حالة الطلب الدفع الملغاة'),
(3056, 'LBL_Order_Payments', 2, 'أمر دفع'),
(3057, 'LBL_Order_Net_Amount', 2, 'أجل صافي المبلغ'),
(3058, 'LBL_Order_Lessons', 2, 'ترتيب الدروس'),
(3059, 'LBL_Order_Invoice', 2, 'أجل الفاتورة'),
(3060, 'LBL_Order_Detail', 2, 'تفاصيل الطلب'),
(3061, 'LBL_Order_Date', 2, 'تاريخ الطلب'),
(3062, 'LBL_Order_Cancellation_Requests', 2, 'تطلب أجل إلغاء'),
(3063, 'LBL_Order_Amount_Pending', 2, 'ترتيب المبلغ قيد الانتظار'),
(3064, 'LBL_Order_Amount_Paid', 2, 'ترتيب المبلغ المدفوع'),
(3065, 'LBL_Order_Amount', 2, 'كمية الطلب'),
(3066, 'LBL_Order/Invoice_ID', 2, 'النظام / فاتورة رقم'),
(3067, 'LBL_Open_In', 2, 'فتح في'),
(3068, 'LBL_On_Submit_Price_Needs_To_Be_Set', 2, 'على إرسال الأسعار يحتاج إلى مجموعة'),
(3069, 'LBL_On_enabling_this_feature,_users_will_receive_a_welcome_mail_after_registration.', 2, 'على تمكين هذه الميزة، سيتمكن المستخدمون تلقي بريد مرحبا بعد التسجيل.'),
(3070, 'LBL_On_enabling_this_feature,_users_will_be_automatically_logged-in_after_registration', 2, 'على تمكين هذه الميزة، سيتمكن المستخدمون ينبغي تسجيل الدخول للبعد التسجيل تلقائيا'),
(3071, 'LBL_On_enabling_this_feature,_admin_need_to_approve_each_user_after_registration_(User_cannot_login_until_admin_approves)', 2, 'على تمكين هذه الميزة، تحتاج المسؤول للموافقة على كل مستخدم بعد التسجيل (المستخدم لا يمكن تسجيل الدخول حتى يوافق مشرف)'),
(3072, 'LBL_One_to_One_COMMISSION', 1, 'One To One Commission'),
(3073, 'LBL_One_to_One_COMMISSION', 2, 'واحد لجنة واحدة'),
(3074, 'LBL_One_to_One_Class', 1, 'One To One Class'),
(3075, 'LBL_One_to_One_Class', 2, 'واحد لفئة واحدة'),
(3076, 'LBL_OK', 2, 'حسنا'),
(3077, 'LBL_October', 2, 'اكتوبر'),
(3078, 'LBL_Oct', 2, 'أكتوبر'),
(3079, 'LBL_No_Records_Found', 2, 'لا توجد سجلات'),
(3080, 'LBL_No_Other_Plan_Avaiable!!', 2, 'خطة لا أخرى افايابلي !!'),
(3081, 'LBL_No_Copons_offer_is_available_now.', 2, 'لا يوجد عرض قسيمة متاح الآن'),
(3082, 'LBL_November', 2, 'شهر نوفمبر'),
(3083, 'LBL_Nov', 2, 'نوفمبر'),
(3084, 'LBL_Not_Available', 1, 'Not Available'),
(3085, 'LBL_Not_Available', 2, 'غير متاح'),
(3086, 'LBL_Notification_Deleted_Successfully!', 2, 'إخطار المحذوفة بنجاح!'),
(3087, 'LBL_Notifications', 2, 'إشعارات'),
(3088, 'LBL_NOTE:_To_use_SSL,_check_with_your_host_if_a_SSL_certificate_is_installed_and_enable_it_from_here.', 2, 'ملاحظة: لاستخدام SSL، تحقق مع المضيف الخاص بك إذا تم تثبيت شهادة SSL وتمكين من هنا.'),
(3089, 'LBL_NOTE:_To_use_SSL,_check_with_your_host', 2, 'ملاحظة: لاستخدام SSL، تحقق مع المضيف الخاص بك'),
(3090, 'LBL_Note:_Refund_Would_Be_%s_Percent.', 2, 'ملاحظة: استرداد سيكون٪ الصورة في المئة.'),
(3091, 'LBL_NOTE:_Press_enter_link_in_new_line_by_pressing_enter!', 2, 'ملاحظة: اضغط Enter لينك في نيو لاين عن طريق الضغط أدخل!'),
(3092, 'LBL_NOTE:_Press_enter_inside_text_box_to_create_tag!', 2, 'ملاحظة: اضغط مفتاح Enter مربع نص داخل لإنشاء علامة!'),
(3093, 'LBL_NOTE:_Enable_Maintenance_Mode_Text', 2, 'ملاحظة: تمكين وضع الصيانة نص'),
(3094, 'LBL_NOTE:_Are_you_sure!_By_Removing_this_lesson_will_also_unlink_it_from_courses_and_scheduled_lessons!', 2, 'ملاحظة: هل أنت متأكد! عن طريق إزالة هذا الدرس سوف أيضا إلغاء ربط ومن الدورات والدروس مجدولة!'),
(3095, 'LBL_NOTE:_Allowed_Lesson_File_types!', 2, 'ملاحظة: سمح أنواع الدرس الملف!'),
(3096, 'LBL_NOTE:_Allowed_File_types!', 2, 'ملاحظة: سمح أنواع الملفات!'),
(3097, 'LBL_No._Students', 2, 'رقم الطالب'),
(3098, 'LBL_No._of_Sold_Lessons', 2, 'عدد الدروس تباع'),
(3099, 'LBL_No._of_Orders', 2, 'عدد الطلبات'),
(3100, 'LBL_No._Of_Lessons_plans', 2, 'عدد الدروس خطط'),
(3101, 'LBl_No._Of_Lessons', 2, 'عدد الدروس'),
(3102, 'LBL_No', 2, 'لا'),
(3103, 'LBL_New_Window', 2, 'نافذة جديدة'),
(3104, 'LBL_New_Users_Earnings', 2, 'المستخدمون الجدد الأرباح'),
(3105, 'LBL_New_Review_Alert_Email', 2, 'مراجعة تنبيه جديد البريد الإلكتروني'),
(3106, 'LBL_New_Password', 2, 'كلمة سر جديدة'),
(3107, 'LBL_NEW_EMAIL', 2, 'بريد إلكتروني جديد'),
(3108, 'LBL_Newsletter_Subscription', 2, 'الاشتراك في النشرة الإخبارية'),
(3109, 'LBL_Newsletter_is_not_configured_yet,_Please_contact_admin', 2, 'النشرة هو غير مكون ومع ذلك، يرجى الاتصال الادارية'),
(3110, 'LBL_Need_to_be_Schedule_lessons', 2, 'تحتاج إلى أن يكون جدول الدروس'),
(3111, 'LBL_navigation_Setup', 2, 'إعداد الملاحة'),
(3112, 'LBL_navigation_Pages_Listing', 2, 'صفحات الاستكشاف قائمة'),
(3113, 'LBL_Navigation_Listing', 2, 'قائمة الملاحة'),
(3114, 'LBL_Navigation_Link_Setup_Successful', 2, 'الإعداد للملاحة وصله الناجح'),
(3115, 'LBL_Navigation_Link_Setup', 2, 'الإعداد للملاحة وصله'),
(3116, 'LBL_Navigations', 2, 'الملاحة'),
(3117, 'LBL_Name_Or_Email', 2, 'اسم أو البريد الإلكتروني'),
(3118, 'LBL_Name', 2, 'اسم'),
(3119, 'LBL_N:', 2, 'ن:'),
(3120, 'LBL_N/A', 2, 'غير قابل للتطبيق'),
(3121, 'LBL_N', 2, 'ن'),
(3122, 'LBL_My_Reports', 2, 'تقاريري'),
(3123, 'LBL_My_Profile', 2, 'ملفي'),
(3124, 'LBL_My_Orders', 2, 'طلباتي'),
(3125, 'LBL_My_Offers', 2, 'عروضي'),
(3126, 'LBL_My_Lesson_Plan', 2, 'خطتي الدرس'),
(3127, 'LBL_My_Downloads', 2, 'بلدي التنزيلات'),
(3128, 'Lbl_My_Current_Time', 1, 'My Current Time'),
(3129, 'Lbl_My_Current_Time', 2, 'وقتي الحالي'),
(3130, 'LBL_My_Credits', 2, 'بلدي قروض'),
(3131, 'LBL_My_Addresses', 2, 'عناويني'),
(3132, 'LBL_My_Account', 2, 'حسابي'),
(3133, 'LBL_Monthly', 2, 'شهريا'),
(3134, 'LBL_Month', 2, 'شهر'),
(3135, 'LBL_Mon', 1, 'Mon'),
(3136, 'LBL_Mon', 2, 'الإثنين'),
(3137, 'LBL_Module', 2, 'وحدة'),
(3138, 'LBL_Mobile_logo_size', 2, 'شعار المحمول الحجم'),
(3139, 'LBL_Mobile', 2, 'التليفون المحمول'),
(3140, 'LBL_Min_Order_Value', 2, 'طلب الحد الأدنى القيمة'),
(3141, 'LBL_Minute', 2, 'دقيقة'),
(3142, 'LBL_Mins/Lesson', 1, 'Mins/lesson'),
(3143, 'LBL_Mins/Lesson', 2, 'دقيقة / الدرس'),
(3144, 'LBL_Minimum_Withdrawal_Amount', 2, 'الحد الأدنى سحب المبلغ'),
(3145, 'LBL_Minimum_Interval_[Days]', 2, 'الحد الأدنى الفاصل الزمني [أيام]'),
(3146, 'LBL_Meta_Title', 2, 'عنوان الفوقية'),
(3147, 'LBL_Meta_Tag_Setup', 2, 'إعداد العلامة الوصفية'),
(3148, 'LBL_Meta_Tags_Setup', 2, 'العلامات الفوقية الإعداد'),
(3149, 'LBL_Meta_Tags_Listing', 2, 'ميتا الكلمات قائمة'),
(3150, 'LBL_Meta_Tags', 2, 'العلامات الفوقية'),
(3151, 'LBL_Meta_Keywords', 2, 'كلمات دلالية'),
(3152, 'LBL_Meta_Description', 2, 'ميتا الوصف'),
(3153, 'LBL_Message_Sent_Successfully!', 2, 'تم إرسال الرسالة بنجاح!'),
(3154, 'LBL_Merchant_Email', 2, 'التاجر البريد الإلكتروني'),
(3155, 'LBL_Media', 2, 'وسائل الإعلام'),
(3156, 'LBL_Meal_Gift_card_Recipient_Detail', 2, 'وجبة بطاقة هدية مستلم التفاصيل'),
(3157, 'LBL_MD5_Hash', 2, 'تجزئة MD5'),
(3158, 'LBL_May', 2, 'مايو'),
(3159, 'LBL_MAX_TEACHER_REQUEST_ATTEMPT', 1, 'Max Teacher Request Attempt'),
(3160, 'LBL_MAX_TEACHER_REQUEST_ATTEMPT', 2, 'ماكس المعلم طلب محاولة'),
(3161, 'LBl_Max_No._Of_Learners', 1, 'Max No. Of Learners'),
(3162, 'LBl_Max_No._Of_Learners', 2, 'ماكس رقم المتعلمين'),
(3163, 'LBL_Max_Discount_Value', 2, 'ماكس قيمة الخصم'),
(3164, 'LBL_Maximum_Site_Commission', 2, 'لجنة الموقع كحد أقصى'),
(3165, 'LBL_Marked_Unavailable_successfully!', 2, 'تميز غير متوفر بنجاح!'),
(3166, 'LBL_March', 2, 'مارس'),
(3167, 'LBL_Mar', 2, 'مارس'),
(3168, 'LBL_Manage_Withdrawal_Requests', 2, 'إدارة طلبات الانسحاب'),
(3169, 'LBL_Manage_Users', 2, 'ادارة المستخدمين'),
(3170, 'LBL_Manage_Url_Rewriting', 2, 'إدارة إعادة كتابة عنوان'),
(3171, 'LBL_Manage_Tutor_Requests', 2, 'إدارة طلبات المعلم'),
(3172, 'LBL_Manage_Testimonials', 2, 'إدارة الشهادات'),
(3173, 'LBL_Manage_Teaching_Language', 2, 'إدارة تدريس اللغة'),
(3174, 'LBL_Manage_Teacher_Reviews', 2, 'إدارة التعليقات المعلم'),
(3175, 'LBL_Manage_Teacher_Requests', 2, 'إدارة طلبات المعلم'),
(3176, 'LBL_Manage_States', 2, 'إدارة الأمريكية'),
(3177, 'LBL_Manage_Spoken_Language', 2, 'إدارة اللغة المنطوقة'),
(3178, 'LBL_Manage_Social_Platforms', 2, 'إدارة المنشآت الاجتماعية'),
(3179, 'LBL_Manage_Purchased_lessons', 2, 'إدارة الدروس المشتراة'),
(3180, 'LBL_Manage_Preferences', 2, 'إدارة تفضيلات'),
(3181, 'LBL_Manage_Payment_Methods', 2, 'إدارة طرق الدفع'),
(3182, 'LBL_Manage_Navigations', 2, 'إدارة ملاحة'),
(3183, 'LBL_Manage_Meta_Tags', 2, 'إدارة العلامات ميتا'),
(3184, 'LBL_Manage_Lesson_Packages', 2, 'إدارة الحزم الدرس'),
(3185, 'LBL_Manage_Lessons', 2, 'إدارة الدروس'),
(3186, 'LBL_Manage_Language', 2, 'إدارة اللغة'),
(3187, 'LBL_Manage_Home_Page_Slides', 2, 'إدارة الصفحة الرئيسية الشرائح'),
(3188, 'LBL_Manage_Giftcards', 2, 'إدارة بطاقات الهدايا'),
(3189, 'LBL_Manage_Faq_Categories', 2, 'إدارة الأسئلة الفئات'),
(3190, 'LBL_Manage_Faq', 2, 'إدارة الأسئلة'),
(3191, 'LBL_Manage_Email_Templates', 2, 'إدارة قوالب البريد الإلكتروني'),
(3192, 'LBL_Manage_Currencies', 2, 'إدارة العملات'),
(3193, 'LBL_Manage_Course_Categories', 2, 'إدارة ملعب الفئات'),
(3194, 'LBL_Manage_Coupons', 2, 'إدارة كوبونات'),
(3195, 'LBL_Manage_Countries', 2, 'البلدان إدارة'),
(3196, 'LBL_Manage_Content_Pages', 2, 'إدارة صفحات المحتوى'),
(3197, 'LBL_Manage_Content_Blocks', 2, 'إدارة كتل المحتوى'),
(3198, 'LBL_Manage_Commission_Settings', 2, 'إدارة إعدادات جنة'),
(3199, 'LBL_Manage_Blog_Post_Categories', 2, 'إدارة المدونة بوست التصنيفات'),
(3200, 'LBL_Manage_Blog_Posts', 2, 'إدارة مقالات المدونة'),
(3201, 'LBL_Manage_Blog_Contributions', 2, 'إدارة مدونة الاشتراكات'),
(3202, 'LBL_Manage_Blog_Comments', 2, 'إدارة مدونة تعليقات'),
(3203, 'LBL_Manage_Bible_Content', 2, 'إدارة محتوى الفيديو'),
(3204, 'LBL_Manage_Banner_Locations', 2, 'إدارة راية المواقع'),
(3205, 'LBL_Manage_Banner', 2, 'إدارة راية'),
(3206, 'LBL_Manage_-_Tutor_Approval_Requests_', 2, 'إدارة - مدرس يطلب الموافقة'),
(3207, 'LBL_Manage', 2, 'يدير'),
(3208, 'LBL_Mail_not_sent!', 1, 'Mail Not Sent!'),
(3209, 'LBL_Mail_not_sent!', 2, 'بريد غير المرسلة!'),
(3210, 'LBL_Mailchimp_List_ID', 2, 'قائمة ميل تشيمب رقم'),
(3211, 'LBL_Mailchimp_Key', 2, 'ميل تشيمب مفتاح'),
(3212, 'LBL_Mailchimp', 2, 'ميل تشيمب'),
(3213, 'LBL_Log_into_store', 2, 'تسجيل الدخول إلى مخزن'),
(3214, 'LBL_Log_into_Profile', 2, 'تسجيل الدخول إلى الملف الشخصي'),
(3215, 'LBL_Login_Successful', 2, 'نجاح تسجيل الدخول'),
(3216, 'Lbl_Login_required_to_post_comment', 2, 'تسجيل الدخول المطلوب للنشر تعليق'),
(3217, 'LBL_Login_Protected', 2, 'تسجيل الدخول المحمي'),
(3218, 'LBL_Login_ID', 2, 'اسمك'),
(3219, 'LBL_Load_Previous', 2, 'تحميل السابق'),
(3220, 'LBL_Loaded_Money_to_Wallet', 2, 'تحميل محفظة المال ل'),
(3221, 'LBL_Live_Chat_Code', 2, 'يعيش كود الدردشة'),
(3222, 'LBL_Listing', 2, 'قائمة'),
(3223, 'LBL_Link_to_CMS_Page', 2, 'رابط لاتفاقية الأنواع المهاجرة الصفحة'),
(3224, 'LBL_Link_Target', 2, 'رابط الهدف'),
(3225, 'LBL_Link_Category', 2, 'فئة الارتباط'),
(3226, 'LBL_Link_Blog_Post_To_Categories', 2, 'رابط المدونة المشاركة إلى فئات'),
(3227, 'LBl_Links', 2, 'الروابط'),
(3228, 'LBL_Level', 2, 'مستوى'),
(3229, 'LBL_Lesson_Updated_Successfully!', 2, 'الدرس التحديث بنجاح!'),
(3230, 'LBL_Lesson_Status', 2, 'الحالة الدرس'),
(3231, 'LBL_Lesson_Start_Time', 2, 'الدرس وقت بدء'),
(3232, 'LBL_Lesson_Starts_in', 1, 'Lesson Starts In'),
(3233, 'LBL_Lesson_Starts_in', 2, 'الدرس يبدأ في'),
(3234, 'LBL_Lesson_Scheduled_Successfully!', 2, 'الدرس المجدولة بنجاح!'),
(3235, 'LBL_Lesson_Scheduled_Successfully', 2, 'الدرس المقرر بنجاح'),
(3236, 'LBL_Lesson_Re-schedule_request_sent_successfully!', 2, 'الدرس إعادة الجدول الزمني طلب إرسالها بنجاح!'),
(3237, 'LBL_Lesson_Re-Scheduled_Successfully!', 2, 'الدرس إعادة جدولتها بنجاح!'),
(3238, 'LBL_Lesson_Plan_Saved_Successfully!', 2, 'خطة الدرس المحفوظة بنجاح!'),
(3239, 'LBL_Lesson_Plan_Removed_Successfully!', 2, 'خطة الدرس إزالتها بنجاح!'),
(3240, 'LBL_Lesson_Plan_Assigned_Successfully!', 2, 'خطة الدرس تعيين بنجاح!'),
(3241, 'LBL_Lesson_Package_Title', 2, 'الدرس حزمة عنوان'),
(3242, 'LBL_Lesson_Package_Setup', 2, 'الدرس إعداد حزمة'),
(3243, 'LBL_Lesson_Package_No', 2, 'الدرس حزمة لا'),
(3244, 'LBL_Lesson_Package_Identifier', 2, 'الدرس حزمة معرف'),
(3245, 'LBL_Lesson_Packages_Listing', 2, 'الحزم الدرس قائمة'),
(3246, 'LBL_Lesson_Packages', 2, 'الحزم الدرس'),
(3247, 'LBL_Lesson_Id', 2, 'رقم الدرس'),
(3248, 'LBL_Lesson_Ended_Successfully!', 2, 'انتهى الدرس بنجاح!'),
(3249, 'LBL_Lesson_Ended_On', 2, 'الدرس انتهى يوم'),
(3250, 'LBL_Lesson_Ended_By', 2, 'الدرس انتهى'),
(3251, 'LBL_Lesson_Date', 2, 'تاريخ الدرس'),
(3252, 'LBL_Lesson_Cancelled_Successfully!', 2, 'الدرس ألغيت بنجاح!'),
(3253, 'LBL_Lesson_Call_History', 2, 'الدرس سجل المكالمات'),
(3254, 'LBL_Lesson_Already_Ended_by_Teacher!', 2, 'الدرس انتهى بالفعل من قبل المعلم!'),
(3255, 'LBL_Lesson_Already_Ended_by_Learner!', 2, 'الدرس انتهى بالفعل من قبل المتعلم!'),
(3256, 'LBL_Lesson_Actual_End_Time', 2, 'الدرس الفعلي نهاية الوقت'),
(3257, 'LBL_Lessons_Sold', 2, 'الدروس التي تم بيعها'),
(3258, 'LBL_LessonId:_%s_Wallet_Credit_Notification', 2, 'معرف الدرس:٪ ق إعلام محفظة الائتمان'),
(3259, 'LBL_LessonId:_%s_Refund_Payment', 2, 'معرف الدرس:٪ ق برد الدفع'),
(3260, 'LBL_LessonId:_%s_Payment', 2, 'معرف الدرس:٪ ق الدفع'),
(3261, 'LBL_Lerner', 2, 'ليرنر'),
(3262, 'LBL_Left_To_Right', 1, 'Left To Right'),
(3263, 'LBL_Left_To_Right', 2, 'من اليسار إلى اليمين'),
(3264, 'LBL_LEARNER_REFUND_PERCENTAGE', 2, 'المتعلم رد النسبة المئوية'),
(3265, 'LBL_Learner_Name', 2, 'اسم المتعلم'),
(3266, 'LBL_Learner_Join_Time_Marked!', 2, 'المتعلم تاريخ وقت المحددة!'),
(3267, 'LBL_Learner_Join_Time', 2, 'المتعلم تاريخ الوقت'),
(3268, 'LBL_Learner_end_Time', 2, 'المتعلم نهاية الوقت'),
(3269, 'LBL_Learner_Ending_Lesson_Warning_Message', 2, 'المتعلم إنهاء الدرس رسالة تحذير'),
(3270, 'LBL_Learner_Banner_Slogan', 2, 'المتعلم راية شعار'),
(3271, 'LBL_Learn', 2, 'تعلم'),
(3272, 'LBL_Layout_Type', 2, 'تخطيط نوع'),
(3273, 'LBL_Layout_3', 2, '                                                                                                                  تخطيط ٣'),
(3274, 'LBL_Layout_2', 2, '                                                                                                                   تخطيط٢ '),
(3275, 'LBL_Layout_1', 2, '                                                                                                                  تخطيط ١'),
(3276, 'Lbl_Layouts_Instructions', 2, 'تعليمات تخطيطات'),
(3277, 'LBL_Last_12_Months_Sales', 2, '                                                                                               مشاركة ١٢ شهر المبيعات'),
(3278, 'LBL_Language_To_Teach', 2, 'لغة للتدريس'),
(3279, 'LBL_Language_that_I\'m_teaching', 2, 'لغة هذا أنا التدريس'),
(3280, 'LBL_Language_Setup', 1, 'Language Setup'),
(3281, 'LBL_Language_Setup', 2, 'إعداد اللغة'),
(3282, 'LBL_Language_Proficiency', 2, 'إجادة اللغة'),
(3283, 'LBL_Language_Name', 2, 'اسم اللغة'),
(3284, 'LBL_Language_Layout_Direction', 1, 'Language Layout Direction'),
(3285, 'LBL_Language_Layout_Direction', 2, 'اتجاه تخطيط اللغة'),
(3286, 'LBL_Language_labels_List', 2, 'لغة العلامات قائمة'),
(3287, 'LBL_Language_Labels', 2, 'تسميات اللغة'),
(3288, 'LBL_Language_I_Teach', 2, 'اللغة أعلم'),
(3289, 'LBL_Language_I_Speak', 2, 'لغة أتحدث'),
(3290, 'LBL_Language_Image', 2, 'اللغة صورة'),
(3291, 'LBL_Language_Flag_Image', 2, 'علم اللغة صورة'),
(3292, 'LBL_Language_Flag', 2, 'علم اللغة'),
(3293, 'LBL_Language_Code_Identifier', 2, 'اللغة رمز تعريف'),
(3294, 'LBL_Language_Code', 2, 'رمز اللغة'),
(3295, 'LBL_Languages_you_speak', 2, 'اللغات تتكلم'),
(3296, 'LBL_Languages_Proficiency', 2, 'اللغات إجادة'),
(3297, 'LBL_Languages_Listing', 2, 'لغات قائمة'),
(3298, 'LBL_Languages_I_Speak', 2, 'اللغات وأنا أتكلم'),
(3299, 'LBL_Langauge_Setup', 1, 'Langauge Setup'),
(3300, 'LBL_Langauge_Setup', 2, 'إعداد الناطقة'),
(3301, 'LBL_Label', 2, 'ضع الكلمة المناسبة'),
(3302, 'LBL_Key', 2, 'مفتاح'),
(3303, 'LBL_June', 2, '                                                                                                                    حزيران'),
(3304, 'LBL_Jun', 2, '                                                                                                                   حزيران'),
(3305, 'LBL_July', 2, '                                                                                                                      تموز'),
(3306, 'LBL_Jul', 2, '                                                                                                                      تموز'),
(3307, 'LBL_January', 2, 'كانون الثاني'),
(3308, 'LBL_Jan', 2, '                                                                                                              كانون الثاني        '),
(3309, 'LBL_Is_Group_Class', 1, 'Is Group Class'),
(3310, 'LBL_Is_Group_Class', 2, 'هو مجموعة الفئة'),
(3311, 'LBL_Issue_Reported_Status', 2, 'يصدر مركز ذكرت'),
(3312, 'LBL_Issue_Reported_SetUp_Successfully!', 2, 'إصدار الإعداد ذكرت بنجاح!'),
(3313, 'LBL_Issues_Reported', 2, 'القضايا المبلغ عنها'),
(3314, 'LBL_Ipad', 2, 'اى باد'),
(3315, 'LBL_Invalid_Coupon_Code', 2, 'رقم قسيمه غير صالح'),
(3316, 'LBL_Introduction_Video_Youtube_Link', 2, 'مقدمة فيديو يوتيوب رابط'),
(3317, 'LBL_Introduction', 2, 'المقدمة'),
(3318, 'LBL_Institute', 2, 'معهد'),
(3319, 'LBL_Instagram_Icon', 2, 'إينستاجرام أيقونة'),
(3320, 'LBL_Inactive', 2, 'غير نشط'),
(3321, 'LBL_In-active', 2, 'غير نشط'),
(3322, 'Lbl_in', 2, 'في'),
(3323, 'LBL_Import', 2, 'استيراد'),
(3324, 'LBL_Immediate', 1, 'Immediate'),
(3325, 'LBL_Immediate', 2, 'فوري'),
(3326, 'LBL_Image_Setup', 2, 'إعداد صورة'),
(3327, 'LBL_Image', 2, 'صورة'),
(3328, 'LBL_If_you_have_to_add_a_platform_icon_except_this_select_list', 2, 'إذا كان لديك لإضافة منصة أيقونة باستثناء هذه القائمة حدد'),
(3329, 'LBL_IFSC_Swift_Code', 2, 'IFSC رمز السويفت'),
(3330, 'LBL_IFSC_Code/Swift_Code', 2, 'كود IFSC / الرمز سريع'),
(3331, 'LBL_Identifier', 2, 'معرف'),
(3332, 'LBL_ID', 2, 'هوية شخصية'),
(3333, 'LBL_Icon_Type_From_CSS', 2, 'رمز نوع من المغلق'),
(3334, 'LBL_Icon_Image', 2, 'رمز صورة'),
(3335, 'LBL_How_much_notice_do_you_require_before_lessons?', 2, 'كم إشعار هل تحتاج قبل الدروس؟'),
(3336, 'LBL_History', 2, 'التاريخ'),
(3337, 'LBL_Hello', 2, 'مرحبا'),
(3338, 'LBL_Headers', 2, 'رؤوس'),
(3339, 'LBL_Have_more_questions?', 2, 'هل لديك المزيد من الأسئلة؟'),
(3340, 'LBL_Has_Tags_Associated', 2, 'لديه الكلمات أسوشيتد'),
(3341, 'LBL_Group_Class_Saved_Successfully!', 1, 'Group Class Saved Successfully!'),
(3342, 'LBL_Group_Class_Saved_Successfully!', 2, 'المجموعة الدرجة المحفوظة بنجاح!'),
(3343, 'LBL_Group_Classes', 2, 'فئات المجموعة'),
(3344, 'LBL_Group_Class', 1, 'Group Class'),
(3345, 'LBL_Group_Class', 2, 'فئة المجموعة'),
(3346, 'LBL_Go_to_Dashboard', 2, 'الذهاب إلى لوحة القيادة'),
(3347, 'LBL_Google_Recaptcha', 2, 'جوجل اختبار Recaptcha'),
(3348, 'LBL_Google_Push_Notification_API_KEY', 2, 'جوجل دفع الإخطار مفتاح API'),
(3349, 'LBL_Google_Plus_Icon', 2, 'جوجل بلس أيقونة'),
(3350, 'LBL_Google_Plus_Developer_Key', 2, 'جوجل بلس مفتاح المطور'),
(3351, 'LBL_Google_Plus_Client_Secret', 2, 'جوجل بلس سر العميل'),
(3352, 'LBL_Google_Plus_Client_ID', 2, 'جوجل بلس العميل رقم'),
(3353, 'LBL_Google_Map_API_Key', 2, 'خريطة جوجل مفتاح API'),
(3354, 'LBL_Google_Map_API', 2, 'خريطة جوجل واجهة برمجة التطبيقات'),
(3355, 'LBL_Google_Authorization', 1, 'Google Authorization'),
(3356, 'LBL_Google_Authorization', 2, 'تفويض Google'),
(3357, 'LBL_Google_Analytics', 2, 'تحليلات كوكل'),
(3358, 'LBL_Gift_card_Your_Detail', 2, 'هدية بطاقة التفاصيل الخاصة بك'),
(3359, 'LBL_GiftCard_Used_Date', 2, 'جيفتكارد مستعملة التسجيل'),
(3360, 'LBL_GIFTCARD_UNUSED', 2, 'جيفتكارد غير المستخدمة'),
(3361, 'LBL_Giftcard_Status', 2, 'جيفتكارد الحالة'),
(3362, 'LBL_Giftcard_Redeem_To_Wallet', 2, 'جيفتكارد ترويجي لمحفظة'),
(3363, 'LBL_Giftcard_Recipient_Name', 2, 'جيفتكارد اسم المستلم'),
(3364, 'LBL_Giftcard_Invoice_ID', 2, 'جيفتكارد الفاتورة رقم'),
(3365, 'LBL_Giftcard_Exipre_Date', 2, 'تاريخ انتهاء صلاحية بطاقة الهدايا'),
(3366, 'LBL_Giftcard_Details', 2, 'تفاصيل جيفتكارد'),
(3367, 'LBL_Giftcard_Code', 2, 'كود جيفتكارد'),
(3368, 'LBL_Giftcards', 2, 'بطاقات الهدايا'),
(3369, 'LBL_General_Availability_Note', 2, 'التوفر ملاحظة عامة'),
(3370, 'LBL_Gateway_Name', 2, 'اسم بوابة'),
(3371, 'LBL_Gateway_Identifier', 2, 'بوابة معرف'),
(3372, 'LBL_Funds_Withdrawn', 2, 'سحب الأموال'),
(3373, 'LBL_From_Name', 2, 'من الاسم'),
(3374, 'LBL_From_Email', 2, 'من البريد الإلكترونى'),
(3375, 'LBL_Fri', 1, 'Fri'),
(3376, 'LBL_Fri', 2, 'الجمعة'),
(3377, 'LBL_Free_Trial', 2, 'تجربة مجانية'),
(3378, 'LBL_For_Example:', 2, 'فمثلا:'),
(3379, 'LBL_Forgot_Your_Password?', 2, 'نسيت رقمك السري؟'),
(3380, 'LBL_Forgot_Password_Msg', 2, 'نسيت رسالة كلمة المرور'),
(3381, 'LBL_Forgot_Password', 2, 'هل نسيت كلمة المرور'),
(3382, 'LBL_Flat', 2, 'مسطحة'),
(3383, 'LBL_Flashcard_Saved_Successfully!', 2, 'البطاقات التعليمية المحفوظة بنجاح!'),
(3384, 'LBL_Flashcard', 2, 'بطاقة الذاكرة المدمجة'),
(3385, 'Lbl_Flag', 2, 'علم'),
(3386, 'LBL_Files', 2, 'ملفات'),
(3387, 'LBL_Fees_[%]', 2, 'رسوم [٪]'),
(3388, 'LBL_Februry', 2, 'فبراير'),
(3389, 'LBL_February', 1, 'February'),
(3390, 'LBL_February', 2, 'شهر فبراير'),
(3391, 'LBL_Feb', 2, 'فبراير'),
(3392, 'LBL_Featured', 2, 'متميز'),
(3393, 'LBL_Fax', 2, 'فاكس'),
(3394, 'LBL_Faq_Title', 2, 'أسئلة وأجوبة عنوان'),
(3395, 'LBL_Faq_Text', 2, 'نص الأسئلة'),
(3396, 'LBL_Faq_Setup', 2, 'إعداد أسئلة وأجوبة'),
(3397, 'LBL_Faq_Page', 2, 'أسئلة وأجوبة الصفحة'),
(3398, 'LBL_Faq_Listing', 2, 'قائمة الأسئلة'),
(3399, 'LBL_Faq_Identifier', 2, 'أسئلة وأجوبة معرف'),
(3400, 'LBL_Faq_Category_Setup', 2, 'أسئلة وأجوبة الإعداد الفئة'),
(3401, 'LBL_Faq_Category_List', 2, 'أسئلة وأجوبة قائمة الفئات'),
(3402, 'LBL_FAQ_Category', 2, 'أسئلة وأجوبة الفئة'),
(3403, 'LBL_Faq_Categories', 2, 'أسئلة وأجوبة الفئات'),
(3404, 'LBL_Facebook_Icon', 2, 'الفيسبوك أيقونة'),
(3405, 'LBL_Facebook_App_Secret', 2, 'الفيسبوك التطبيقات السرية'),
(3406, 'LBL_Facebook_APP_ID', 2, 'الفيسبوك معرف التطبيق'),
(3407, 'LBL_External_Page', 2, 'الصفحة الخارجية'),
(3408, 'LBL_EXPIRY_YEAR', 2, 'انتهاء السنة'),
(3409, 'LBL_EXPIRY_MONTH', 2, 'شهر انتهاء الصلاحية'),
(3410, 'LBL_Expired', 2, 'منتهية الصلاحية'),
(3411, 'LBL_Experience_Type', 2, 'نوع الخبرة'),
(3412, 'LBL_Example_password', 2, 'مثلا كلمة المرور'),
(3413, 'LBL_Example:_Custom_URL_Example', 2, 'مثال: مخصص رابط سبيل المثال'),
(3414, 'LBL_Ex:_If_URL_is', 2, 'مثال: إذا كان العنوان هو'),
(3415, 'LBL_etc', 2, 'إلخ'),
(3416, 'LBl_Entry_fee', 1, 'Entry Fee'),
(3417, 'LBl_Entry_fee', 2, 'رسم الدخول'),
(3418, 'LBL_Entries', 2, 'مقالات'),
(3419, 'LBL_Entity_Id', 2, 'كيان معرف'),
(3420, 'LBL_Enter_Your_Email_Address', 2, 'أدخل عنوان بريدك الالكتروني'),
(3421, 'LBL_Enter_Your_Email', 2, 'أدخل بريدك الإلكتروني'),
(3422, 'LBL_Enter_the_value_with_comma_Separated_used_in_teacher_dashboard_for_My_couses_page_No._Of_Lessons_plans)', 2, 'أدخل القيمة مع مفصولة بفواصل المستخدمة في لوحة المعلم لكوسيس صفحتي رقم من دروس الخطط)'),
(3423, 'LBL_Enter_the_newsletter_signup_code_received_from_Aweber', 2, 'أدخل رمز الاشتراك في النشرة الإخبارية الواردة من أوبر'),
(3424, 'LBL_Enter_The_E-mail_Address_Associated_With_Your_Account', 2, 'ادخل عنوان البريد الإلكتروني المرتبط بحسابك'),
(3425, 'LBL_Enter_Gift_Card_Code', 2, 'أدخل هدية كود بطاقة'),
(3426, 'LBL_Enter_Giftcard_Code', 2, 'أدخل رمز جيفتكارد'),
(3427, 'LBL_ENTER_CREDIT_CARD_NUMBER', 2, 'أدخل رقم بطاقة الاعتماد'),
(3428, 'LBL_Enter_amount_to_be_Added_[$]', 2, 'أدخل المبلغ المطلوب إضافتها [$]'),
(3429, 'LBL_English', 2, 'الإنجليزية'),
(3430, 'LBL_End_Year', 2, 'نهاية عام'),
(3431, 'LBl_End_Time', 1, 'End Time'),
(3432, 'LBl_End_Time', 2, 'وقت النهاية'),
(3433, 'LBL_END_LESSON_DURATION', 2, 'نهاية الدرس مدة'),
(3434, 'LBL_End_At', 1, 'End At'),
(3435, 'LBL_End_At', 2, 'يغلق عند مستوى'),
(3436, 'LBL_End', 2, 'النهاية'),
(3437, 'LBL_Enable_Trial_Lesson', 2, 'تمكين درس تجريبي'),
(3438, 'LBL_Enable_Maintenance_Mode', 2, 'تمكين وضع الصيانة');
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(3439, 'LBL_Email_Verified', 2, 'البريد الإلكتروني التحقق'),
(3440, 'LBL_Email_Verification_pending_for_email_change', 2, 'البريد الإلكتروني التحقق من الانتظار للحصول على البريد الإلكتروني تغيير'),
(3441, 'LBL_Email_Template_Setup', 2, 'أرسل إعداد قالب'),
(3442, 'LBL_Email_Template_logo_size', 2, 'البريد الإلكتروني قالب شعار الحجم'),
(3443, 'LBL_Email_Template_Lists', 2, 'أرسل قوائم قالب'),
(3444, 'LBL_Email_Templates', 2, 'قوالب البريد الإلكتروني'),
(3445, 'LBL_Email_Marketing_System', 2, 'البريد الإلكتروني نظام التسويق'),
(3446, 'LBL_Email_Headers', 2, 'رؤوس البريد الإلكتروني'),
(3447, 'LBL_Email_Could_Not_Be_Sent', 2, 'تعذر إرسال البريد الإلكتروني'),
(3448, 'LBL_Email:', 2, 'البريد الإلكتروني:'),
(3449, 'LBL_Either_You_or_teacher_not_available_for_this_slot', 1, 'Either You Or Teacher Not Available For This Slot'),
(3450, 'LBL_Either_You_or_teacher_not_available_for_this_slot', 2, 'إما أنت أو المعلم غير متوفر على فتحة'),
(3451, 'LBL_Eg:_user@123', 2, '                                                                                     على سبيل المثال: العضو @123'),
(3452, 'LBL_Eg:_Oxford_University', 2, 'على سبيل المثال: جامعة أكسفورد'),
(3453, 'LBL_Eg:_London', 2, 'على سبيل المثال: لندن'),
(3454, 'LBL_Eg:_B.A._English', 2, 'على سبيل المثال: يسانس الإنجليزية'),
(3455, 'LBL_Eg._Focus_in_Humanist_Literature', 2, 'على سبيل المثال. التركيز في الأدب الإنساني'),
(3456, 'LBL_E', 2, 'E'),
(3457, 'LBL_Duration_After_Teacher_Can_End_Lesson_(In_Minutes)', 2, 'مدة بعد الدرس المعلم يمكن أن ينتهي (في دقائق)'),
(3458, 'LBL_Draft', 2, 'مشروع'),
(3459, 'LBL_Do_you_want_to_truncate_User_Data', 2, 'هل ترغب في اقتطاع بيانات المستخدم'),
(3460, 'LBL_Do_you_want_to_enable_the_slot_again', 1, 'Do You Want To Enable The Slot Again'),
(3461, 'LBL_Do_you_want_to_enable_the_slot_again', 2, 'هل تريد تمكين فتحة مرة أخرى'),
(3462, 'LBL_Do_you_want_to_disable_the_slot', 1, 'Do You Want To Disable The Slot'),
(3463, 'LBL_Do_you_want_to_disable_the_slot', 2, 'هل ترغب في تعطيل فتحة'),
(3464, 'LBL_Do_you_want_to_change_request_status', 2, 'هل تريد طلب تغيير الحالة'),
(3465, 'LBL_Do_you_want_to_cancel', 2, 'هل تريد الالغاء'),
(3466, 'LBL_Doesn\'t_Matter', 2, 'لا يهم'),
(3467, 'LBL_Display_Order', 2, 'ترتيب العرض'),
(3468, 'LBL_Display_For', 2, 'عرض ل'),
(3469, 'LBL_Discount_Value', 2, 'قيمة الخصم'),
(3470, 'LBL_Discount_in', 2, 'خصم في'),
(3471, 'LBL_Discount', 2, 'خصم'),
(3472, 'LBl_Difficulty_Level', 2, 'مستوى الصعوبة'),
(3473, 'LBL_Determines_how_many_items_are_shown_per_page_(user_listing,_categories,_etc)', 2, 'يحدد عدد العناصر المعروضة في كل صفحة (المستخدم قائمة، فئات، الخ)'),
(3474, 'LBL_Determines_how_many_items_are_shown_per_page_(lesson_listing,_flashcards,_etc)', 2, 'يحدد عدد العناصر المعروضة في كل صفحة (الدرس قائمة، البطاقات التعليمية، الخ)'),
(3475, 'LBL_Desktop_logo_size', 2, 'سطح المكتب شعار الحجم'),
(3476, 'LBL_Desktop', 2, 'سطح المكتب'),
(3477, 'LBL_Deleted_Successfully', 2, 'حذف بنجاح'),
(3478, 'LBL_Default_Site_Laguage', 2, '                                                                                                  لغة الموقع الافتراضية'),
(3479, 'LBL_Default_Site_Currency', 2, 'افتراضي الموقع العملات'),
(3480, 'LBL_Default_Review_Status', 2, 'افتراضي حالة المراجعة'),
(3481, 'LBL_Default_Items_Per_Page', 2, 'العناصر الافتراضية لكل صفحة'),
(3482, 'LBL_Default_Child_Paid_Order_Status', 2, 'افتراضي الطفل دفعت ترتيب أوضاع'),
(3483, 'LBL_Default_Child_Order_Status', 2, 'افتراضي ترتيب الطفل الحالة'),
(3484, 'LBL_Default_Admin_View', 2, '                                                                                            عرض المشرف الافتراضي'),
(3485, 'LBL_Default', 2, 'إفتراضي'),
(3486, 'LBL_Decline', 2, 'انخفاض'),
(3487, 'LBL_December', 2, 'ديسمبر'),
(3488, 'LBL_Dec', 2, 'ديسمبر'),
(3489, 'LBL_Deactivate', 2, 'إلغاء تنشيط'),
(3490, 'LBL_Days_of_the_Week', 1, 'Days Of The Week'),
(3491, 'LBL_Days_of_the_Week', 2, 'أيام الأسبوع'),
(3492, 'LBL_Date_To', 2, 'التاريخ إلى'),
(3493, 'LBL_Date_Of_Birth', 2, 'تاريخ الولادة'),
(3494, 'LBL_Date_From', 2, 'التاريخ من'),
(3495, 'LBL_date_Format', 2, 'صيغة التاريخ'),
(3496, 'LBL_CVV_SECURITY_CODE', 2, 'قانون الضمان CVV'),
(3497, 'LBL_Custom_URL', 2, 'رابط مخصص'),
(3498, 'LBL_Customer_Order_Detail', 2, 'العملاء ترتيب التفاصيل'),
(3499, 'LBL_Customer_Name', 2, 'اسم الزبون'),
(3500, 'LBL_Customer_Details', 2, 'تفاصيل العميل'),
(3501, 'LBL_Customers_Giftcards_List', 2, '                                                                                               قائمة بطاقات هدايا العملاء'),
(3502, 'LBL_Customer', 2, 'زبون'),
(3503, 'LBL_Custom', 2, 'مخصص'),
(3504, 'LBL_Current_Window', 2, 'النافذة الحالية'),
(3505, 'LBL_Current_Wallet_Balance', 2, 'رصيد المحفظة الحالي'),
(3506, 'LBL_Current_Password', 2, 'كلمة المرور الحالي'),
(3507, 'LBL_CURRENT_EMAIL', 2, 'الايميل الحالي'),
(3508, 'LBL_Current_Balance', 2, 'الرصيد الحالي'),
(3509, 'LBL_Currency_Symbol_Right', 2, 'رمز العملة اليمين'),
(3510, 'LBL_Currency_Symbol_Left', 2, 'رمز العملة اليسار'),
(3511, 'LBL_Currency_Setup', 2, 'إعداد العملة'),
(3512, 'LBL_Currency_Name', 2, 'اسم العملة'),
(3513, 'LBL_Currency_Listing', 2, 'قائمة العملات'),
(3514, 'LBL_Currency_Conversion_Value', 2, 'عملة قيمة التحويل'),
(3515, 'LBL_Currency_code', 2, 'رمز العملة'),
(3516, 'LBL_Currency', 2, 'عملة'),
(3517, 'LBL_CREDIT_CARD_EXPIRY', 2, 'بطاقة الائتمان انتهاء الصلاحية'),
(3518, 'LBL_credited', 2, 'الفضل'),
(3519, 'LBL_Course_Saved_Successfully!', 2, 'المحفوظة بالطبع بنجاح!'),
(3520, 'LBl_Course_Image', 2, 'صورة بطبيعة الحال'),
(3521, 'LBL_Course_Description', 1, 'Course Description'),
(3522, 'LBL_Course_Description', 2, 'وصف الدورة التدريبية'),
(3523, 'LBL_Course_Category_Title', 2, 'بالطبع تصنيف العنوان'),
(3524, 'LBL_Course_Category_Setup', 2, 'بالطبع الإعداد الفئة'),
(3525, 'LBL_Course_Category_Identifier', 2, 'بالطبع الفئة معرف'),
(3526, 'LBl_Course_Category', 2, 'فئة المساق'),
(3527, 'LBL_Course_Categories_Listing', 2, 'فئات بالطبع قائمة'),
(3528, 'LBL_Course_Categories', 2, 'فئات الدورة'),
(3529, 'LBL_Coupon_Title', 2, 'قسيمة عنوان'),
(3530, 'LBL_Coupon_Status', 2, 'الحالة القسيمة'),
(3531, 'LBL_Coupon_Setup', 2, 'إعداد قسيمة'),
(3532, 'LBL_Coupon_Identifier', 2, 'قسيمة معرف'),
(3533, 'LBL_Coupon_History', 2, 'التاريخ القسيمة'),
(3534, 'LBL_Coupon_Description', 2, 'قسيمة الوصف'),
(3535, 'LBL_Coupons_List', 2, 'قائمة كوبونات'),
(3536, 'LBL_Country_Setup', 2, 'إعداد البلاد'),
(3537, 'LBL_Country_Name', 2, 'اسم الدولة'),
(3538, 'LBL_Country_Listing', 2, 'قائمة البلاد'),
(3539, 'LBL_Country_Flag_Setup', 2, 'الإعداد علم الدولة'),
(3540, 'LBL_Country_Code', 2, 'الرقم الدولي'),
(3541, 'LBL_Countries', 2, 'بلدان'),
(3542, 'LBL_Cookies_Policies_Text', 2, 'الكوكيز سياسات النص'),
(3543, 'LBL_cookies_policies_section_will_be_shown_on_frontend', 2, 'الكوكيز سياسات القسم وسوف يعرض على الواجهة'),
(3544, 'LBL_Cookies_Policies_Page', 2, 'الكوكيز السياسات الصفحة'),
(3545, 'LBL_Cookies_Policies', 2, 'الكوكيز السياسات'),
(3546, 'LBL_Controller', 2, 'مراقب'),
(3547, 'LBL_Contribution_Status', 2, 'الحالة مساهمة'),
(3548, 'LBL_Contribution_Detail', 2, 'مساهمة التفاصيل'),
(3549, 'Lbl_Contributed_Successfully', 2, 'ساهم بنجاح'),
(3550, 'LBL_Content_Page_Layout2', 2, '                                                                                                تخطيط صفحة المحتوى٢'),
(3551, 'LBL_Content_Page_Layout1', 2, '                                                                                                تخطيط صفحة المحتوى١'),
(3552, 'LBL_Content_Pages_Setup', 2, 'إعداد صفحات المحتوى'),
(3553, 'LBL_Content_Pages_Layouts_Instructions', 2, 'محتوى الصفحات تخطيط تعليمات'),
(3554, 'LBL_Content_Block_Setup', 2, 'إعداد بلوك المحتوى'),
(3555, 'LBL_Content_Block_5', 2, '                                                                                                       المحتوى بلوك ٥'),
(3556, 'LBL_Content_Block_4', 2, '                                                                                                       المحتوى بلوك ٤'),
(3557, 'LBL_Content_Block_3', 2, '                                                                                                       المحتوى بلوك ٣'),
(3558, 'LBL_Content_Block_2', 2, '                                                                                                       المحتوى بلوك ٢'),
(3559, 'LBL_Content_Block_1', 2, '                                                                                                        المحتوى بلوك ١'),
(3560, 'LBL_Content_Blocks', 2, 'كتل المحتوى'),
(3561, 'LBL_Content_Block', 2, 'كتلة المحتوى'),
(3562, 'LBL_Content', 2, 'المحتوى'),
(3563, 'LBL_Contact_Us', 2, 'اتصل بنا'),
(3564, 'LBL_Contact_Email_Address', 2, 'الاتصال عنوان البريد الالكتروني'),
(3565, 'LBL_Confirm_Password_Not_Matched!', 2, 'تأكيد كلمة المرور لا تضاهي!'),
(3566, 'LBL_Confirm_Password', 2, 'تأكيد كلمة المرور'),
(3567, 'LBL_Confirm_New_Password', 2, 'تأكيد كلمة المرور الجديدة'),
(3568, 'LBL_Confirm_It!', 2, 'قم بالتأكيد!'),
(3569, 'LBL_Confirm', 2, 'تؤكد'),
(3570, 'LBL_Configurations', 2, 'تكوينات'),
(3571, 'LBL_Completed_On', 2, 'الانتهاء في'),
(3572, 'LBL_Completed_lessons', 2, 'دروس الانتهاء'),
(3573, 'LBL_Commission_Setup', 2, 'إعداد جنة'),
(3574, 'LBL_Commission_Settings_List', 2, 'قائمة إعدادات جنة'),
(3575, 'LBL_Commission_History', 2, 'التاريخ جنة'),
(3576, 'LBL_Commission_fees_[%]', 2, 'رسوم عمولة [٪]'),
(3577, 'LBL_Commission_fees', 2, 'رسوم عمولة'),
(3578, 'LBL_Commission_charged_including_tax_charges', 2, 'لجنة اتهم الرسوم بما في ذلك الضرائب'),
(3579, 'LBL_Commission_charged_including_tax', 2, 'لجنة اتهم بما في ذلك الضرائب'),
(3580, 'LBL_Commission_charged_including_shipping_charges', 2, 'لجنة اتهم الرسوم بما في ذلك شحن'),
(3581, 'LBL_Commission_charged_including_shipping', 2, 'لجنة اتهم بما في ذلك شحن'),
(3582, 'LBL_Commission', 2, 'عمولة'),
(3583, 'LBL_Comment_Status', 2, 'الحالة تعليق'),
(3584, 'LBL_Comment_Open', 2, 'التعليق المفتوحة'),
(3585, 'LBL_Comment_Details', 2, 'تفاصيل تعليق'),
(3586, 'LBL_Comments/Reason', 2, 'تعليقات / السبب'),
(3587, 'LBL_Comet_Chat_Auth', 2, 'المذنب الدردشة أصيل'),
(3588, 'LBL_Comet_Chat_App_ID', 2, 'المذنب الدردشة رقم تعريف التطبيق'),
(3589, 'LBL_Comet_chat_Api_Key', 2, 'المذنب الدردشة مفتاح API'),
(3590, 'LBL_CMS_Page', 2, 'سم الصفحة'),
(3591, 'LBL_Client_Id', 2, 'رقم العميل'),
(3592, 'LBL_Click_Here', 2, 'انقر هنا'),
(3593, 'LBL_Class_Type', 1, 'Class Type'),
(3594, 'LBL_Class_Type', 2, 'فئة نوع'),
(3595, 'LBL_Class_Cancellation_Refund_PERCENTAGE', 1, 'Class Cancellation Refund Percentage'),
(3596, 'LBL_Class_Cancellation_Refund_PERCENTAGE', 2, 'الطبقة إلغاء رد النسبة المئوية'),
(3597, 'LBL_Class_Booking_Time_Span(Minutes)', 1, 'Class Booking Time Span(minutes)'),
(3598, 'LBL_Class_Booking_Time_Span(Minutes)', 2, 'الطبقة الحجز الوقت سبان (دقيقة)'),
(3599, 'LBL_City', 2, 'مدينة'),
(3600, 'LBL_Charge_Learner', 2, 'تهمة المتعلم'),
(3601, 'LBL_Change_Status', 2, 'تغيير الوضع'),
(3602, 'LBL_Change_Plan', 2, 'خطة التغيير'),
(3603, 'LBL_Change_Password_or_Email', 2, 'تغيير كلمة المرور أو البريد الإلكتروني'),
(3604, 'LBL_Change_or_reset_your_password.', 2, 'تغيير أو إعادة تعيين كلمة المرور.'),
(3605, 'LBL_Change_Email', 2, 'تغيير الايميل'),
(3606, 'LBL_changePassword', 2, 'غير كلمة السر'),
(3607, 'LBL_Certificate', 2, 'شهادة'),
(3608, 'LBL_Category_Status', 2, 'الحالة الفئة'),
(3609, 'LBL_Category_Setup_Successful', 2, 'إعداد فئة الناجح'),
(3610, 'LBL_Category_Parent', 2, 'الفئة الرئيسي'),
(3611, 'LBL_Category_Name', 2, 'اسم التصنيف'),
(3612, 'LBL_Category_Identifier', 2, 'الفئة معرف'),
(3613, 'LBL_Category', 2, 'الفئة'),
(3614, 'LBL_CARD_HOLDER_NAME', 2, 'إسم صاحب البطاقة'),
(3615, 'LBL_Caption_Identifier', 2, 'شرح معرف'),
(3616, 'LBL_Caption', 2, 'شرح'),
(3617, 'LBL_Can_not_join_own_classes', 1, 'You cannot join your own classes'),
(3618, 'LBL_Can_not_join_own_classes', 2, 'لا يمكنك الانضمام إلى الفصول الدراسية الخاصة'),
(3619, 'LBL_Cannot_End_Lesson_So_Early!', 2, 'لا يمكنك إنهاء الدرس في وقت مبكر جدا.'),
(3620, 'LBL_Cancel_Plan', 2, 'إلغاء خطة'),
(3621, 'LBL_Cancel_Lesson', 1, 'Cancel Lesson'),
(3622, 'LBL_Cancel_Lesson', 2, 'إلغاء الدرس'),
(3623, 'LBL_Cancelled_lessons', 2, 'دروس إلغاء'),
(3624, 'Lbl_Calendar', 1, 'Calendar'),
(3625, 'Lbl_Calendar', 2, 'التقويم'),
(3626, 'LBL_Cache_has_been_cleared', 2, 'مخبأ تم مسح'),
(3627, 'LBL_By_Price_Low_to_High', 1, 'By Price Low To High'),
(3628, 'LBL_By_Price_Low_to_High', 2, 'حسب السعر الأدنى إلى الأعلى'),
(3629, 'LBL_By_Price_High_to_Low', 1, 'By Price High To Low'),
(3630, 'LBL_By_Price_High_to_Low', 2, 'حسب السعر الاعلى الى الادنى'),
(3631, 'LBL_By_Popularity', 1, 'By Popularity'),
(3632, 'LBL_By_Popularity', 2, 'حسب الأكثر إقبالا'),
(3633, 'LBL_Buy_Now', 2, 'اشتري الآن'),
(3634, 'LBL_Buyer_Phone', 2, 'مشتري الهاتف'),
(3635, 'LBL_Buyer_Name', 2, 'اسم المشتري'),
(3636, 'LBL_Buyer_Email', 2, 'المشتري البريد الإلكتروني'),
(3637, 'LBL_Bulk_Lesson_Price', 2, 'الجزء الأكبر الدرس الأسعار'),
(3638, 'LBL_Both', 2, 'على حد سواء'),
(3639, 'LBL_Book_Session', 2, 'دورة كتاب'),
(3640, 'LBL_Book_a_Session', 2, '                                                                                                              حجز جلسة'),
(3641, 'LBL_Booking_Before', 2, 'الحجز قبل'),
(3642, 'LBL_Booked_Seats', 1, 'Booked Seats'),
(3643, 'LBL_Booked_Seats', 2, 'مقاعد حجز'),
(3644, 'Lbl_Booked', 1, 'Booked'),
(3645, 'Lbl_Booked', 2, 'حجز'),
(3646, 'LBL_Body', 2, '                                                                                                              جزء مركزي'),
(3647, 'LBL_Blog_Post_Title', 2, 'مقالات عنوان المقال'),
(3648, 'LBL_Blog_Post_Setup', 2, 'مقالات إعداد المشاركة'),
(3649, 'LBL_Blog_Post_List', 2, 'قائمة المدونة المشاركة'),
(3650, 'LBL_Blog_Post_Category_Setup', 2, '                                                                                              إعداد فئة مشاركة المدونة'),
(3651, 'LBL_Blog_Contribution_List', 2, 'بلوق قائمة مساهمة'),
(3652, 'Lbl_Blog_Contribution', 2, 'بلوق مساهمة'),
(3653, 'LBL_Blog_Comment_List', 2, 'بلوق قائمة تعليق'),
(3654, 'LBL_Blank_Window', 2, 'نافذة فارغة'),
(3655, 'LBL_Bible_Title', 2, 'عنوان مقطع الفيديو'),
(3656, 'LBL_Banner_Title', 2, 'راية عنوان'),
(3657, 'LBL_Banner_Setup', 2, 'إعداد راية'),
(3658, 'LBL_Banner_Location_Title', 2, 'شعار الموقع عنوان'),
(3659, 'LBL_Banner_Location_Setup', 2, 'إعداد شعار الموقع'),
(3660, 'LBL_Banner_Location_Identifier', 2, 'شعار الموقع معرف'),
(3661, 'LBL_Banner_Locations_List', 2, 'راية قائمة المواقع'),
(3662, 'Lbl_Banner_Layouts_Instructions', 2, 'راية تخطيط تعليمات'),
(3663, 'LBL_Banner_Image_(Small)', 2, 'راية صورة (صغير)'),
(3664, 'LBL_Banner_Image', 2, 'صورة بانر'),
(3665, 'LBL_Banner_Description', 2, 'راية الوصف'),
(3666, 'LBL_Banner_Button_Link', 2, 'راية زر الرابط'),
(3667, 'LBL_Banner_Button_Caption', 2, 'راية زر تسمية توضيحية'),
(3668, 'LBL_Banner', 2, 'راية'),
(3669, 'LBL_Bank_Name', 2, 'اسم البنك'),
(3670, 'LBL_Bank_Address', 2, 'عنوان البنك'),
(3671, 'LBL_Bank_Account', 2, 'حساب البنك'),
(3672, 'LBL_back_To_Navigations', 2, 'الرجوع إلى ملاحة'),
(3673, 'LBL_Back_to_Login', 2, 'العودة إلى تسجيل الدخول'),
(3674, 'LBL_Back_To_Giftcards', 2, '                                                                                                العودة إلى بطاقات الهدايا'),
(3675, 'LBL_Background_Image_Title', 2, 'خلفية الصورة العنوان'),
(3676, 'LBL_Background_Image_Description', 2, 'صورة الخلفية الوصف'),
(3677, 'LBL_Backgroud_Image', 2, '                                                                                                          الصورة الخلفية'),
(3678, 'LBL_Back', 2, 'عودة'),
(3679, 'LBL_Aweber_Signup_Form_Code', 2, 'أوبر الاشتراك التعليمات البرمجية للنموذج'),
(3680, 'LBL_Aweber', 2, 'أوبر'),
(3681, 'LBL_Average_Rating', 2, 'متوسط ​​تقييم'),
(3682, 'LBL_Availibility', 1, 'Availibility'),
(3683, 'LBL_Availibility', 2, 'التوفر'),
(3684, 'LBL_Available_Seats', 1, 'Available Seats'),
(3685, 'LBL_Available_Seats', 2, 'المقاعد المتاحة'),
(3686, 'LBL_Available', 2, 'متاح'),
(3687, 'LBL_Availability_updated_successfully!', 2, 'توفر التحديث بنجاح!'),
(3688, 'LBL_Availability_deleted_successfully!', 2, 'توفر المحذوفة بنجاح!'),
(3689, 'LBL_Author_Phone', 2, 'الكاتب الهاتف'),
(3690, 'LBL_Author_Name', 2, 'اسم المؤلف'),
(3691, 'LBL_Author_Email', 2, 'الكاتب البريد الإلكتروني'),
(3692, 'LBL_August', 2, 'أغسطس'),
(3693, 'LBL_Aug', 2, 'أغسطس'),
(3694, 'LBL_Attached_File', 2, 'الملف المرفق'),
(3695, 'LBL_Assign', 2, 'تعيين'),
(3696, 'LBL_Are_you_sure_want_to_cancel_this_lesson', 2, 'هل أنت متأكد تريد إلغاء هذا الدرس'),
(3697, 'LBL_Arabic', 2, 'عربى'),
(3698, 'LBL_April', 2, 'أبريل'),
(3699, 'LBL_Apr', 2, 'أبريل'),
(3700, 'LBL_Approved', 2, 'وافق'),
(3701, 'LBL_Approve', 2, 'يوافق'),
(3702, 'LBL_Apply_to_All', 2, 'تنطبق على جميع'),
(3703, 'LBL_Application_Reference', 2, 'المرجع التطبيق'),
(3704, 'LBL_application_awaiting_approval', 2, 'تطبيق تنتظر الموافقة'),
(3705, 'LBL_Apple_Touch_logo_size', 2, 'أبل تعمل باللمس شعار الحجم'),
(3706, 'LBL_Any_additional_emails_you_want_to_receive_the_alert_email', 2, 'أي رسائل البريد الإلكتروني الإضافية التي ترغب في الحصول على تنبيه عبر البريد الإلكتروني'),
(3707, 'LBL_Analytics_Id', 2, 'رقم تحليلات'),
(3708, 'LBL_Amount_to_be_Withdrawn', 2, 'المبلغ الى سحب'),
(3709, 'LBL_All_times_listed_are_in_your_selected_{timezone}', 1, 'All Times Listed Are In Your Selected {timezone}'),
(3710, 'LBL_All_times_listed_are_in_your_selected_{timezone}', 2, 'جميع الأوقات المذكورة هي في اخترتها {التوقيت}'),
(3711, 'LBL_Allow_Reviews', 2, 'السماح التعليقات'),
(3712, 'LBL_Advanced_Setting', 2, 'إعدادات متقدمة'),
(3713, 'LBL_Adress2', 2, '                                                                                                                   عنوان٢'),
(3714, 'LBL_Admin_User_Setup', 2, 'إعداد العضو مشرف'),
(3715, 'LBL_Admin_User_Listing', 2, 'مشرف قائمة العضو'),
(3716, 'LBL_Admin_User_Change_Password', 2, 'مشرف العضو تغيير كلمة المرور'),
(3717, 'LBL_Admin_Users', 2, 'المستخدمين مشرف'),
(3718, 'LBL_Admin_logo_size', 2, 'مشرف شعار الحجم'),
(3719, 'LBL_Admin', 2, 'مشرف'),
(3720, 'LBL_Add_User_Transactions', 2, 'إضافة المعاملات العضو'),
(3721, 'LBL_Add_Transactions', 2, 'إضافة المعاملات'),
(3722, 'LBL_Add_Testimonial', 2, 'إضافة التزكية'),
(3723, 'LBL_ADD_TEACH_LANGUAGE', 2, 'إضافة تعليم اللغة'),
(3724, 'LBL_Add_Teaching_Language', 2, 'اضافة تدريس اللغة'),
(3725, 'LBL_Add_State', 2, 'اضافة الدولة'),
(3726, 'LBL_Add_Spoken_Language', 2, 'إضافة اللغة المنطوقة'),
(3727, 'LBL_Add_Preferences', 2, 'تفضيلات إضافة'),
(3728, 'LBL_Add_Page', 2, 'إضافة صفحة'),
(3729, 'LBL_Add_New_Social_Platform', 2, 'إضافة منصة اجتماعي جديد'),
(3730, 'LBL_Add_New_Slide', 2, 'إضافة شريحة جديدة'),
(3731, 'LBL_Add_New_Lesson_Plan', 2, 'إضافة خطة الدرس الجديد'),
(3732, 'LBL_Add_New_Lesson', 1, 'Add New Lesson'),
(3733, 'LBL_Add_New_Lesson', 2, 'إضافة درس جديد'),
(3734, 'LBL_Add_New_Language', 2, 'إضافة لغة جديدة'),
(3735, 'LBL_Add_New_Flashcard', 2, 'إضافة بطاقات التعليمية الجديدة'),
(3736, 'LBL_Add_New_Coupon', 2, 'إضافة قسيمة جديدة'),
(3737, 'LBL_Add_Navigation_Page', 2, 'إضافة صفحة الاستكشاف'),
(3738, 'LBL_Add_Meta_Tag', 2, 'إضافة ميتا الوسم'),
(3739, 'LBL_Add_Lesson_Package', 2, 'إضافة الدرس حزمة'),
(3740, 'LBL_ADD_LANGUAGE', 2, 'إضافة لغة'),
(3741, 'LBL_Add_Item', 2, 'اضافة عنصر'),
(3742, 'LBL_Add_Faq', 2, '                                                                                                         إضافة التعليمات'),
(3743, 'LBL_Add_Currency', 2, 'إضافة العملات'),
(3744, 'LBL_Add_Course_Category', 2, 'إضافة فئة المساق'),
(3745, 'LBL_Add_Country', 2, 'اضافة البلد'),
(3746, 'LBL_Add_content', 2, 'إضافة محتوى'),
(3747, 'LBL_Add_category', 2, 'إضافة فئة'),
(3748, 'LBL_Add_Blog_Post_Category', 2, '                                                                                                    إضافة مشاركة مدونة'),
(3749, 'LBL_Add_Blog_Post', 2, 'إضافة المدونة المشاركة'),
(3750, 'LBL_Add_Admin_User', 2, 'إضافة المسؤول المستخدم'),
(3751, 'LBL_Add_3_Lesson_Plans!', 2, '                                                                                                  إضافة ٣ خطط الدرس!'),
(3752, 'LBL_Addresses', 2, 'عناوين'),
(3753, 'LBL_Address', 2, 'عنوان'),
(3754, 'LBL_Additional_Alert_E-Mails', 2, 'تنبيه رسائل البريد الإلكتروني إضافية'),
(3755, 'LBL_Added_On', 2, 'وأضاف في'),
(3756, 'LBL_Active_Users', 2, 'المستخدمين النشطين'),
(3757, 'LBL_Active', 2, 'نشيط'),
(3758, 'LBL_Activate_Tutor_Account', 2, 'حساب مدرس تنشيط'),
(3759, 'LBL_Activate_Sending_Welcome_Mail_After_Registration', 2, 'تفعيل إرسال بريد مرحبا بعد التسجيل'),
(3760, 'LBL_Activate_Newsletter_Subscription', 2, 'تفعيل الاشتراك في النشرة الإخبارية'),
(3761, 'LBL_Activate_Live_Payment_Transaction_Mode', 2, 'وضع عملية تفعيل لايف الدفع'),
(3762, 'LBL_Activate_Live_Chat', 2, 'تنشيط الدردشة'),
(3763, 'LBL_Activate_Email_Verification_After_Registration', 2, 'تنشيط البريد الإلكتروني التحقق بعد التسجيل'),
(3764, 'LBL_Activate_Auto_Login_After_Registration', 2, 'تنشيط دخول السيارات بعد التسجيل'),
(3765, 'LBL_Activate_Admin_Approval_After_Registration_(Sign_Up)', 2, 'تفعيل المسؤول الموافقة بعد التسجيل (الاشتراك)'),
(3766, 'LBL_Activate_3rd_Party_Live_Chat.', 2, '                                                                                           تنشيط الحزب الثالث الدردشة.'),
(3767, 'LBL_Activate', 2, 'تفعيل'),
(3768, 'LBL_Account_Number', 2, 'رقم حساب'),
(3769, 'LBL_Account_Holder_Name', 2, 'اسم صاحب الحساب'),
(3770, 'LBL_Account_Details', 2, 'تفاصيل الحساب'),
(3771, 'LBL_Account', 2, 'الحساب'),
(3772, 'LBL_Accept_Tutor_Approval_Terms_&_condition', 2, 'استعرض مدرس الموافقة الشروط والأحكام'),
(3773, 'LBL_Accept_Teacher_Approval_Terms_&_condition', 2, 'استعرض المعلم الموافقة الشروط والأحكام'),
(3774, 'LBL_Accept_Cookies', 1, 'Accept Cookies'),
(3775, 'LBL_Accept_Cookies', 2, 'قبول ملفات تعريف الارتباط'),
(3776, 'LBL_About_Us', 2, 'معلومات عنا'),
(3777, 'LBL_A/C_Number', 2, '                                                                                                              رقم حساب'),
(3778, 'LBL_A/C_Name', 2, '                                                                                                             أسم الحساب'),
(3779, 'LBL_48_Hours', 2, '                                                                                                                ٤٨ ساعة'),
(3780, 'LBL_24_Hours', 2, '                                                                                                                 ٢٤ ساعة'),
(3781, 'LBL_12_Hours', 2, '                                                                                                                 ١٢ ساعة'),
(3782, 'LBL_-NA-', 2, '                                                                                                       غير قابل للتطبيق'),
(3783, 'LBL_*_All_Purchases_are_in_{default-currency-code}._Foreign_transaction_fees_might_apply,_according_to_your_bank\'s_policies', 1, '* All Purchases Are In {default-currency-code}. Foreign Transaction Fees Might Apply, According To Your Bank\'s Policies'),
(3784, 'LBL_*_All_Purchases_are_in_{default-currency-code}._Foreign_transaction_fees_might_apply,_according_to_your_bank\'s_policies', 2, '* جميع مشتريات هل في {الافتراضي العملة رمز}. رسوم المعاملات الخارجية قد تنطبق، وفقا لسياسات البنك الخاص بك'),
(3785, 'LABEL_WALLET_CREDIT_DESCRIPTION', 2, 'الوصف محفظة الائتمان'),
(3786, 'LABEL_WALLET_CREDIT', 2, 'محفظة الائتمان'),
(3787, 'LABEL_TEACHER_REQUEST_APPROVED_DESCRIPTION', 2, 'الوصف طلب المعلم وافق'),
(3788, 'LABEL_TEACHER_REQUEST_APPROVED', 2, 'وافق المعلم طلب'),
(3789, 'LABEL_LESSON_SCHEDULED_BY_LEARNER_DESCRIPTION', 2, 'الدرس المقرر من قبل المتعلم الوصف'),
(3790, 'LABEL_LESSON_SCHEDULED_BY_LEARNER', 2, 'الدرس المقرر من قبل المتعلم'),
(3791, 'LABEL_LESSON_RESCHEDULE_REQUEST_LEARNER_DESCRIPTION', 2, 'درس طلب إعادة جدولة المتعلم الوصف'),
(3792, 'LABEL_LESSON_RESCHEDULE_REQUEST_BY_TEACHER_DESCRIPTION', 2, 'درس إعادة جدولة طلب بواسطة المعلم الوصف'),
(3793, 'LABEL_LESSON_RESCHEDULE_REQUEST_BY_TEACHER', 2, 'درس إعادة جدولة طلب بواسطة المعلم'),
(3794, 'LABEL_LESSON_RESCHEDULE_REQUEST_BY_LEARNER', 2, 'درس إعادة جدولة طلب بواسطة المتعلم'),
(3795, 'Home_Page_After_Third_Layout', 2, 'الصفحة بعد تخطيط الثالث'),
(3796, 'Home_Page_After_First_Layout', 2, 'الصفحة بعد تخطيط الأولى'),
(3797, 'ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED_OR_NOT_ACTIVE', 2, 'حسابك تم إلغاء تنشيط أو غير نشط'),
(3798, 'ERR_LOGIN_ATTEMPT_LIMIT_EXCEEDED_PLEASE_TRY_LATER', 2, 'محاولة دخول تجاوز حد الرجاء المحاولة لاحقا'),
(3799, 'ERR_LINK_IS_INVALID_OR_EXPIRED', 2, 'الرابط غير صالح أو منتهي'),
(3800, 'ERR_INVALID_USERNAME', 2, 'غير صالح اسم المستخدم'),
(3801, 'ERR_ERROR_IN_SENDING_NOTIFICATION_EMAIL_TO_ADMIN', 2, '                                                              خطأ في إرسال إشعار بالبريد الإلكتروني إلى المسؤول'),
(3802, 'ERR_Email_Template_Not_Found', 2, 'أرسل قالب لم يتم العثور'),
(3803, 'Btn_Post_Comment', 2, 'أضف تعليقا'),
(3804, 'BLOCK_SECOND_AFTER_HOMESLIDER', 2, 'الثانية بعد Homeslider'),
(3805, 'BLOCK_HOW_IT_WORKS', 2, 'كيف تعمل؟'),
(3806, 'LBL_Labels_data_imported/updated_Successfully', 1, 'Labels Data Imported/updated Successfully'),
(3807, 'LBL_Manage_Group_Classes', 1, 'Manage Group Classes'),
(3808, 'LBL_Group_Classes_List', 1, 'Group Classes List'),
(3809, 'LBL_Class_Title', 1, 'Class Title'),
(3810, 'LBL_Max_Learners', 1, 'Max Learners'),
(3811, 'LBL_GROUP_CLASS_COMMISSION', 1, 'Group Class Commission');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lesson_packages`
--

CREATE TABLE `tbl_lesson_packages` (
  `lpackage_id` int(11) NOT NULL,
  `lpackage_identifier` varchar(255) NOT NULL,
  `lpackage_lessons` decimal(10,2) NOT NULL,
  `lpackage_added_on` datetime NOT NULL,
  `lpackage_is_free_trial` tinyint(1) NOT NULL,
  `lpackage_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(7, '20 Lesson Package', '20.00', '2019-05-16 15:05:52', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lesson_packages_lang`
--

CREATE TABLE `tbl_lesson_packages_lang` (
  `lpackagelang_lpackage_id` int(11) NOT NULL,
  `lpackagelang_lang_id` int(11) NOT NULL,
  `lpackage_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(7, 1, '20 lessons');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lesson_reschedule_log`
--

CREATE TABLE `tbl_lesson_reschedule_log` (
  `lesreschlog_id` int(11) NOT NULL,
  `lesreschlog_slesson_id` int(11) NOT NULL,
  `lesreschlog_reschedule_by` int(11) NOT NULL,
  `lesreschlog_user_type` int(11) NOT NULL,
  `lesreschlog_comment` text NOT NULL,
  `lesreschlog_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meta_tags`
--

CREATE TABLE `tbl_meta_tags` (
  `meta_id` int(11) NOT NULL,
  `meta_controller` varchar(200) NOT NULL,
  `meta_action` varchar(200) NOT NULL,
  `meta_record_id` int(11) NOT NULL,
  `meta_subrecord_id` int(11) NOT NULL,
  `meta_identifier` varchar(200) NOT NULL,
  `meta_default` tinyint(1) NOT NULL,
  `meta_advanced` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_meta_tags`
--

INSERT INTO `tbl_meta_tags` (`meta_id`, `meta_controller`, `meta_action`, `meta_record_id`, `meta_subrecord_id`, `meta_identifier`, `meta_default`, `meta_advanced`) VALUES
(1, 'Cms', 'view', 1, 0, 'About Us', 0, 0);

INSERT INTO `tbl_meta_tags` (`meta_id`, `meta_controller`, `meta_action`, `meta_record_id`, `meta_subrecord_id`, `meta_identifier`, `meta_default`, `meta_advanced`) VALUES (2, 'Home', 'index', '0', '0', 'Home', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meta_tags_lang`
--

CREATE TABLE `tbl_meta_tags_lang` (
  `metalang_meta_id` int(11) NOT NULL,
  `metalang_lang_id` int(11) NOT NULL,
  `meta_title` varchar(200) NOT NULL,
  `meta_keywords` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `meta_other_meta_tags` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_meta_tags_lang`
--

INSERT INTO `tbl_meta_tags_lang` (`metalang_meta_id`, `metalang_lang_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_other_meta_tags`) VALUES
(1, 1, 'About Us', 'About Us', 'About Us', '<meta name=\"copyright\" content=\"text\">'),
(1, 2, 'About Us', 'About Us', 'About Us', '<meta name=\"copyright\" content=\"text\">');

INSERT INTO `tbl_meta_tags_lang` (`metalang_meta_id`, `metalang_lang_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_other_meta_tags`) VALUES ('2', '1', 'Yo!Coach Live Demo - Readymade Solution To Build Online Learning & Consultation Marketplace', 'Yo!Coach Demo Readymade Solution To Build Online Learning & Consultation Marketplace', 'Check the working and features of Yo!Coach with live demo setup. A FATbit Technologies solution to build an online learning and consultation platform like Verbling , Italki.', '');

INSERT INTO `tbl_meta_tags_lang` (`metalang_meta_id`, `metalang_lang_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_other_meta_tags`) VALUES ('2', '2', 'Yo!Coach Live Demo - Readymade Solution To Build Online Learning & Consultation Marketplace', 'Yo!Coach Demo Readymade Solution To Build Online Learning & Consultation Marketplace', 'Check the working and features of Yo!Coach with live demo setup. A FATbit Technologies solution to build an online learning and consultation platform like Verbling , Italki.', '');
-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigations`
--

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
(4, 'footer right', 1, 0, 4, 0),
(5, 'Footer Bottom', 1, 0, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigations_lang`
--

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
(57, 3, 0, 0, 'FAQs', '_self', 2, 0, 0, 0, '{siteroot}faq', 1),
(58, 3, 0, 0, 'Contact Us', '_self', 2, 0, 0, 0, '{SITEROOT}contact', 2),
(59, 3, 1, 0, 'About Us', '_self', 0, 0, 0, 0, '', 3),
(61, 3, 0, 0, 'Video Content', '_self', 2, 0, 0, 0, '{SITEROOT}video-content', 5),
(62, 1, 0, 0, 'Blog', '_self', 2, 0, 0, 0, '{SITEROOT}blog', 4),
(63, 1, 3, 0, 'Privacy And Policy', '_self', 0, 0, 0, 0, '', 3),
(64, 4, 1, 0, 'About', '_self', 0, 0, 2, 0, '', 1),
(68, 2, 0, 0, 'Apply to Teach', '_self', 2, 0, 0, 0, '{SITEROOT}apply-to-teach', 3),
(69, 1, 2, 0, 'Terms and Conditions', '_self', 0, 0, 0, 0, '', 2),
(70, 1, 0, 0, 'Apply to Teach', '_self', 2, 0, 0, 0, '{siteroot}apply-to-teach', 1),
(71, 4, 0, 0, 'FAQs', '_self', 2, 0, 0, 0, '{siteroot}faq', 2),
(72, 4, 0, 0, 'Contact Us', '_self', 2, 0, 0, 0, '{siteroot}contact', 3),
(73, 2, 0, 0, 'Group Classes', '_self', 2, 0, 0, 0, '{SITEROOT}group-classes', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_navigation_links_lang`
--

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
(56, 2, 'ابحث عن مدرس'),
(57, 1, 'FAQs'),
(57, 2, 'Help'),
(58, 1, 'Contact Us'),
(58, 2, 'Contact Us'),
(59, 1, 'About Us'),
(59, 2, 'About Us'),
(61, 1, 'Video Content'),
(61, 2, 'Bible'),
(62, 1, 'Blog'),
(62, 2, 'مدونة'),
(63, 1, 'Privacy And Policy'),
(63, 2, 'الخصوصية والسياسة'),
(64, 1, 'About'),
(68, 1, 'Apply to Teach'),
(68, 2, 'تنطبق على التدريس'),
(69, 1, 'Terms and Conditions'),
(69, 2, 'الأحكام والشروط'),
(70, 1, 'Apply to Teach'),
(70, 2, 'تنطبق على التدريس'),
(71, 1, 'FAQs'),
(71, 2, 'FAQs'),
(72, 1, 'Contact Us'),
(72, 2, 'Contact Us'),
(73, 1, 'Group Classes'),
(73, 2, 'فئات المجموعة');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_user_id` int(11) NOT NULL,
  `notification_record_id` varchar(50) NOT NULL,
  `notification_sub_record_id` int(11) NOT NULL,
  `notification_record_type` tinyint(5) NOT NULL,
  `notification_title` varchar(150) NOT NULL,
  `notification_description` varchar(500) NOT NULL,
  `notification_read` tinyint(1) NOT NULL COMMENT '0=>Not Read ,1 =>Read',
  `notification_added_on` datetime NOT NULL DEFAULT current_timestamp(),
  `notification_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_payments`
--

CREATE TABLE `tbl_order_payments` (
  `opayment_id` bigint(20) NOT NULL,
  `opayment_order_id` varchar(15) NOT NULL,
  `opayment_method` varchar(250) NOT NULL,
  `opayment_gateway_txn_id` varchar(100) NOT NULL,
  `opayment_amount` decimal(10,2) NOT NULL,
  `opayment_comments` text NOT NULL,
  `opayment_gateway_response` text NOT NULL,
  `opayment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_products`
--

CREATE TABLE `tbl_order_products` (
  `op_id` int(11) NOT NULL,
  `op_order_id` varchar(15) NOT NULL,
  `op_invoice_number` varchar(50) NOT NULL,
  `op_grpcls_id` int(11) NOT NULL,
  `op_teacher_id` int(11) NOT NULL COMMENT 'user_id from users table',
  `op_slanguage_id` int(11) NOT NULL,
  `op_lpackage_id` int(11) NOT NULL,
  `op_lpackage_lessons` decimal(10,2) NOT NULL,
  `op_lpackage_is_free_trial` tinyint(1) NOT NULL,
  `op_lesson_duration` decimal(10,0) NOT NULL COMMENT 'In Minutes',
  `op_qty` int(11) NOT NULL,
  `op_unit_price` decimal(10,2) NOT NULL,
  `op_commission_charged` decimal(10,2) NOT NULL,
  `op_commission_percentage` decimal(10,2) NOT NULL,
  `op_refund_qty` int(11) NOT NULL,
  `op_total_refund_amount` decimal(10,2) NOT NULL,
  `op_orderstatus_id` int(11) NOT NULL COMMENT 'from table tbl_order_statuses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_products_lang`
--

CREATE TABLE `tbl_order_products_lang` (
  `oplang_op_id` int(11) NOT NULL,
  `oplang_lang_id` int(11) NOT NULL,
  `oplang_order_id` varchar(15) NOT NULL,
  `op_lpackage_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_statuses`
--

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

CREATE TABLE `tbl_order_status_history` (
  `oshistory_id` int(11) NOT NULL,
  `oshistory_order_id` varchar(15) NOT NULL,
  `oshistory_op_id` int(11) NOT NULL,
  `oshistory_orderstatus_id` int(11) NOT NULL,
  `oshistory_order_payment_status` int(11) NOT NULL,
  `oshistory_date_added` datetime NOT NULL,
  `oshistory_customer_notified` tinyint(1) NOT NULL,
  `oshistory_tracking_number` varchar(255) NOT NULL,
  `oshistory_comments` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_methods`
--

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
(1, 'Credit Card - Authorize.Net (AIM)', 'AuthorizeAim', 1, 2),
(2, 'PayPal Payments Standard', 'PaypalStandard', 1, 1),
(3, 'Stripe', 'stripe', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_methods_lang`
--

CREATE TABLE `tbl_payment_methods_lang` (
  `pmethodlang_pmethod_id` int(11) NOT NULL,
  `pmethodlang_lang_id` int(11) NOT NULL,
  `pmethod_name` varchar(200) NOT NULL,
  `pmethod_description` mediumtext NOT NULL
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

CREATE TABLE `tbl_payment_method_settings` (
  `paysetting_pmethod_id` int(11) NOT NULL,
  `paysetting_key` varchar(100) NOT NULL,
  `paysetting_value` mediumtext NOT NULL
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

CREATE TABLE `tbl_preferences` (
  `preference_id` int(11) NOT NULL,
  `preference_type` int(11) NOT NULL,
  `preference_identifier` varchar(200) NOT NULL,
  `preference_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(25, 5, 'Grammar', 0),
(26, 0, 'Latin', 0),
(27, 6, 'HKDSE', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_preferences_lang`
--

CREATE TABLE `tbl_preferences_lang` (
  `preferencelang_preference_id` int(11) NOT NULL,
  `preferencelang_lang_id` int(11) NOT NULL,
  `preference_title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_preferences_lang`
--

INSERT INTO `tbl_preferences_lang` (`preferencelang_preference_id`, `preferencelang_lang_id`, `preference_title`) VALUES
(4, 1, 'Beginner'),
(4, 2, 'مبتدئ'),
(5, 1, 'Upper Intermediate'),
(5, 2, 'وسيط ذو مستوي رفيع'),
(6, 1, 'Children (4-11)'),
(6, 2, 'أطفال (4-11سنوات)'),
(7, 1, 'Adults (18+)'),
(7, 2, 'بالغون (18+)'),
(8, 1, 'Curriculum'),
(8, 2, 'منهاج دراسي'),
(9, 1, 'Proficiency Assessment'),
(9, 2, 'تقييم الكفاءة'),
(10, 1, 'Academic English'),
(10, 2, 'الانجليزية الأكاديمية'),
(11, 1, 'Listening Comprehension'),
(11, 2, 'الاستماع والفهم'),
(12, 1, 'US ACT'),
(12, 2, 'قانون الولايات المتحدة'),
(13, 1, 'ICAS'),
(14, 1, 'American English'),
(14, 2, 'الإنجليزية الأمريكية'),
(15, 1, 'Irish English'),
(15, 2, 'الإنجليزية الأيرلندية'),
(20, 1, 'GRE'),
(20, 2, 'GRE'),
(21, 1, 'Advanced'),
(21, 2, 'المتقدمة'),
(22, 1, 'Young Adults (12-17yrs)'),
(22, 2, 'الشباب (12-17سنة)'),
(23, 1, 'Conversational practice'),
(23, 2, 'ممارسة المحادثة'),
(24, 1, 'Accent Reduction'),
(24, 2, 'الحد من اللكنة'),
(25, 1, 'Misc. Grammar'),
(25, 2, 'متفرقات. قواعد'),
(26, 1, 'Latin'),
(26, 2, 'Latin'),
(27, 1, 'Hong Kong DSE'),
(27, 2, 'HKDSE');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scheduled_lessons`
--

CREATE TABLE `tbl_scheduled_lessons` (
  `slesson_id` int(11) NOT NULL,
  `slesson_grpcls_id` int(11) NOT NULL,
  `slesson_teacher_id` int(11) NOT NULL,
  `slesson_slanguage_id` int(11) NOT NULL,
  `slesson_date` date NOT NULL,
  `slesson_end_date` date NOT NULL,
  `slesson_start_time` time NOT NULL,
  `slesson_end_time` time NOT NULL,
  `slesson_teacher_join_time` datetime NOT NULL,
  `slesson_teacher_end_time` datetime NOT NULL,
  `slesson_ended_by` tinyint(4) NOT NULL,
  `slesson_ended_on` datetime NOT NULL,
  `slesson_status` tinyint(1) NOT NULL COMMENT 'defined in model',
  `slesson_has_issue` TINYINT(4) NOT NULL,
  `slesson_comments` text NOT NULL,
  `slesson_is_teacher_paid` tinyint(4) NOT NULL,
  `slesson_teacher_google_calendar_id` varchar(255) NOT NULL,
  `slesson_added_on` datetime NOT NULL,
  `slesson_reminder_one` int(11) NOT NULL,
  `slesson_reminder_two` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scheduled_lessons_to_teachers_lessons_plan`
--

CREATE TABLE `tbl_scheduled_lessons_to_teachers_lessons_plan` (
  `ltp_slessonid` int(11) NOT NULL,
  `ltp_tlpn_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scheduled_lesson_details`
--

CREATE TABLE `tbl_scheduled_lesson_details` (
  `sldetail_id` int(11) NOT NULL,
  `sldetail_slesson_id` int(11) NOT NULL,
  `sldetail_learner_id` int(11) NOT NULL,
  `sldetail_order_id` varchar(15) NOT NULL,
  `sldetail_learner_join_time` datetime NOT NULL,
  `sldetail_learner_end_time` datetime NOT NULL,
  `sldetail_learner_status` tinyint(4) NOT NULL,
  `sldetail_learner_google_calendar_id` varchar(255) NOT NULL,
  `sldetail_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shared_flashcards`
--

CREATE TABLE `tbl_shared_flashcards` (
  `sflashcard_flashcard_id` int(11) NOT NULL,
  `sflashcard_learner_id` int(11) NOT NULL,
  `sflashcard_teacher_id` int(11) NOT NULL,
  `sflashcard_slesson_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slides`
--

CREATE TABLE `tbl_slides` (
  `slide_id` int(11) NOT NULL,
  `slide_identifier` varchar(200) NOT NULL,
  `slide_type` int(11) NOT NULL,
  `slide_record_id` int(11) NOT NULL,
  `slide_url` varchar(200) NOT NULL,
  `slide_target` varchar(100) NOT NULL,
  `slide_active` tinyint(1) NOT NULL,
  `slide_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_slides`
--

INSERT INTO `tbl_slides` (`slide_id`, `slide_identifier`, `slide_type`, `slide_record_id`, `slide_url`, `slide_target`, `slide_active`, `slide_display_order`) VALUES
(3, '1001', 1, 0, 'http://yoCoach.staging.4demo.biz/', '_self', 1, 2),
(4, '103', 1, 0, '', '_blank', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slides_lang`
--

CREATE TABLE `tbl_slides_lang` (
  `slidelang_slide_id` int(11) NOT NULL,
  `slidelang_lang_id` int(11) NOT NULL,
  `slide_title` varchar(200) NOT NULL
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

CREATE TABLE `tbl_social_platforms` (
  `splatform_id` int(11) NOT NULL,
  `splatform_user_id` int(11) NOT NULL,
  `splatform_identifier` varchar(255) NOT NULL,
  `splatform_url` varchar(255) NOT NULL,
  `splatform_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_social_platforms`
--

INSERT INTO `tbl_social_platforms` (`splatform_id`, `splatform_user_id`, `splatform_identifier`, `splatform_url`, `splatform_active`) VALUES
(1, 0, 'Facebook', 'https://www.facebook.com/yoCoach/', 1),
(2, 0, 'Twitter', 'https://twitter.com/we_yak', 1),
(3, 0, 'Instagram', 'https://www.instagram.com/yoCoach/', 1),
(4, 0, 'Pintrest', 'https://www.pinterest.com/yoCoach/pins/', 1),
(5, 0, 'YouTube', 'https://www.youtube.com/user/thepoodlegirl/featured?view_as=subscriber', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_platforms_lang`
--

CREATE TABLE `tbl_social_platforms_lang` (
  `splatformlang_splatform_id` int(11) NOT NULL,
  `splatformlang_lang_id` int(11) NOT NULL,
  `splatform_title` varchar(255) NOT NULL
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

CREATE TABLE `tbl_spoken_languages` (
  `slanguage_id` int(11) NOT NULL,
  `slanguage_code` varchar(4) NOT NULL,
  `slanguage_identifier` varchar(100) NOT NULL,
  `slanguage_flag` varchar(100) NOT NULL,
  `slanguage_display_order` int(11) NOT NULL,
  `slanguage_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='same table used for teaching languages and spoken languages';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_spoken_languages_lang`
--

CREATE TABLE `tbl_spoken_languages_lang` (
  `slanguagelang_slanguage_id` int(11) NOT NULL,
  `slanguagelang_lang_id` int(11) NOT NULL,
  `slanguage_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_states`
--

CREATE TABLE `tbl_states` (
  `state_id` int(11) NOT NULL,
  `state_code` varchar(10) NOT NULL,
  `state_country_id` int(11) NOT NULL,
  `state_identifier` varchar(100) NOT NULL,
  `state_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_states`
--

INSERT INTO `tbl_states` (`state_id`, `state_code`, `state_country_id`, `state_identifier`, `state_active`) VALUES
(1, 'Kerala', 1, 'Kerala', 1),
(2, 'Hr-001', 2, 'HR', 0),
(3, 'AZ', 6, 'Arizona', 1),
(4, 'CH', 169, 'Chandigarh', 0),
(5, 'MAN', 7, 'Manitoba', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_states_lang`
--

CREATE TABLE `tbl_states_lang` (
  `statelang_state_id` int(11) NOT NULL,
  `statelang_lang_id` int(11) NOT NULL,
  `state_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_states_lang`
--

INSERT INTO `tbl_states_lang` (`statelang_state_id`, `statelang_lang_id`, `state_name`) VALUES
(1, 1, 'Kerala'),
(1, 2, 'Kerala'),
(2, 1, 'haryana'),
(2, 2, 'haryana'),
(3, 1, 'Arizona'),
(3, 2, 'Arizona'),
(4, 1, 'Chandigarh'),
(4, 2, 'chandigarh'),
(5, 1, 'Manitoba'),
(5, 2, 'Manitoba');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers_general_availability`
--

CREATE TABLE `tbl_teachers_general_availability` (
  `tgavl_id` bigint(20) NOT NULL,
  `tgavl_user_id` int(11) NOT NULL,
  `tgavl_day` tinyint(2) NOT NULL,
  `tgavl_start_time` time NOT NULL,
  `tgavl_end_time` time NOT NULL,
  `tgavl_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers_lessons_plan`
--

CREATE TABLE `tbl_teachers_lessons_plan` (
  `tlpn_id` int(11) NOT NULL,
  `tlpn_user_id` int(11) NOT NULL COMMENT 'Teacher ID',
  `tlpn_title` varchar(255) NOT NULL,
  `tlpn_description` text NOT NULL,
  `tlpn_level` int(11) NOT NULL COMMENT 'beginner, intermediate, advanced, defined in model',
  `tlpn_tags` text NOT NULL,
  `tlpn_links` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers_weekly_schedule`
--

CREATE TABLE `tbl_teachers_weekly_schedule` (
  `twsch_id` bigint(20) NOT NULL,
  `twsch_user_id` int(11) NOT NULL,
  `twsch_date` date NOT NULL,
  `twsch_end_date` date NOT NULL,
  `twsch_start_time` time NOT NULL,
  `twsch_end_time` time NOT NULL,
  `twsch_is_available` tinyint(2) NOT NULL COMMENT 'available/unavailable, defined in model'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_courses`
--

CREATE TABLE `tbl_teacher_courses` (
  `tcourse_id` int(11) NOT NULL,
  `tcourse_user_id` int(11) NOT NULL,
  `tcourse_title` varchar(255) NOT NULL,
  `tcourse_description` text NOT NULL,
  `tcourse_tags` text NOT NULL,
  `tcourse_category` int(11) NOT NULL,
  `tcourse_no_of_lessons` int(11) NOT NULL,
  `tcourse_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_courses_to_teachers_lessons_plan`
--

CREATE TABLE `tbl_teacher_courses_to_teachers_lessons_plan` (
  `ctp_tcourse_id` int(11) NOT NULL,
  `ctp_tlpn_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_lesson_rating`
--

CREATE TABLE `tbl_teacher_lesson_rating` (
  `tlrating_tlreview_id` int(11) NOT NULL,
  `tlrating_rating_type` int(11) NOT NULL,
  `tlrating_rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_lesson_reviews`
--

CREATE TABLE `tbl_teacher_lesson_reviews` (
  `tlreview_id` int(11) NOT NULL,
  `tlreview_teacher_user_id` int(11) NOT NULL,
  `tlreview_lesson_id` varchar(15) NOT NULL,
  `tlreview_postedby_user_id` int(11) NOT NULL,
  `tlreview_title` varchar(255) NOT NULL,
  `tlreview_description` mediumtext NOT NULL,
  `tlreview_posted_on` datetime NOT NULL,
  `tlreview_status` tinyint(4) NOT NULL,
  `tlreview_lang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher_offer_price`
--

CREATE TABLE `tbl_teacher_offer_price` (
  `top_teacher_id` int(11) NOT NULL,
  `top_learner_id` int(11) NOT NULL,
  `top_single_lesson_price` decimal(10,2) NOT NULL,
  `top_bulk_lesson_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teaching_languages`
--

CREATE TABLE `tbl_teaching_languages` (
  `tlanguage_id` int(11) NOT NULL,
  `tlanguage_code` varchar(4) NOT NULL,
  `tlanguage_identifier` varchar(100) NOT NULL,
  `tlanguage_flag` varchar(100) NOT NULL,
  `tlanguage_display_order` int(11) NOT NULL,
  `tlanguage_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='same table used for teaching languages and spoken languages';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teaching_languages_lang`
--

CREATE TABLE `tbl_teaching_languages_lang` (
  `tlanguagelang_tlanguage_id` int(11) NOT NULL,
  `tlanguagelang_lang_id` int(11) NOT NULL,
  `tlanguage_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_testimonials`
--

CREATE TABLE `tbl_testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `testimonial_identifier` varchar(150) NOT NULL,
  `testimonial_active` tinyint(1) NOT NULL,
  `testimonial_deleted` tinyint(1) NOT NULL,
  `testimonial_added_on` datetime NOT NULL,
  `testimonial_user_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_testimonials_lang`
--

CREATE TABLE `tbl_testimonials_lang` (
  `testimoniallang_testimonial_id` int(11) NOT NULL,
  `testimoniallang_lang_id` int(11) NOT NULL,
  `testimonial_title` varchar(255) NOT NULL,
  `testimonial_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_threads`
--

CREATE TABLE `tbl_threads` (
  `thread_id` bigint(20) NOT NULL,
  `thread_start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thread_messages`
--

CREATE TABLE `tbl_thread_messages` (
  `message_id` bigint(20) NOT NULL,
  `message_thread_id` int(11) NOT NULL,
  `message_from` int(11) NOT NULL COMMENT 'user_id',
  `message_to` int(11) NOT NULL COMMENT 'user_id',
  `message_text` text NOT NULL,
  `message_date` datetime NOT NULL,
  `message_is_unread` tinyint(1) NOT NULL DEFAULT 1,
  `message_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thread_users`
--

CREATE TABLE `tbl_thread_users` (
  `threaduser_id` bigint(20) NOT NULL,
  `threaduser_thread_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_url_rewrites`
--

CREATE TABLE `tbl_url_rewrites` (
  `urlrewrite_id` int(11) NOT NULL,
  `urlrewrite_original` varchar(255) NOT NULL,
  `urlrewrite_custom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_url_rewrites`
--

INSERT INTO `tbl_url_rewrites` (`urlrewrite_id`, `urlrewrite_original`, `urlrewrite_custom`) VALUES
(5, 'cms/view/7', 'apply-to-teach'),
(6, 'cms/view/1', 'aboutus'),
(7, 'teachers/view', 'teachers/urlparameter'),
(9, 'bible', 'video-content');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_url_name` varchar(150) DEFAULT NULL,
  `user_first_name` varchar(100) NOT NULL,
  `user_last_name` varchar(100) NOT NULL,
  `user_phone` varchar(50) NOT NULL,
  `user_gender` tinyint(2) NOT NULL,
  `user_dob` date NOT NULL,
  `user_profile_info` mediumtext NOT NULL,
  `user_address1` varchar(250) NOT NULL,
  `user_address2` varchar(250) NOT NULL,
  `user_zip` varchar(20) NOT NULL,
  `user_timezone` varchar(100) NOT NULL,
  `user_country_id` int(11) NOT NULL,
  `user_state_id` int(11) NOT NULL,
  `user_city` varchar(255) NOT NULL,
  `user_is_learner` tinyint(1) NOT NULL,
  `user_is_teacher` tinyint(1) NOT NULL,
  `user_facebook_id` varchar(255) NOT NULL,
  `user_googleplus_id` varchar(255) NOT NULL,
  `user_fb_access_token` varchar(255) NOT NULL,
  `user_preferred_dashboard` tinyint(4) NOT NULL,
  `user_registered_initially_for` int(11) NOT NULL COMMENT 'user type defined in user model',
  `user_added_on` datetime NOT NULL,
  `user_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_lang`
--

CREATE TABLE `tbl_users_lang` (
  `userlang_user_id` int(11) NOT NULL,
  `userlang_lang_id` int(11) NOT NULL,
  `userlang_user_profile_Info` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_auth_token`
--

CREATE TABLE `tbl_user_auth_token` (
  `uauth_user_id` int(11) NOT NULL,
  `uauth_token` varchar(32) NOT NULL,
  `uauth_fcm_id` varchar(300) NOT NULL,
  `uauth_expiry` datetime NOT NULL,
  `uauth_browser` mediumtext NOT NULL,
  `uauth_last_access` datetime NOT NULL,
  `uauth_last_ip` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store cookies information';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_bank_details`
--

CREATE TABLE `tbl_user_bank_details` (
  `ub_user_id` int(11) NOT NULL,
  `ub_bank_name` varchar(255) NOT NULL,
  `ub_account_holder_name` varchar(255) NOT NULL,
  `ub_account_number` varchar(100) NOT NULL,
  `ub_ifsc_swift_code` varchar(100) NOT NULL,
  `ub_bank_address` mediumtext NOT NULL,
  `ub_paypal_email_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_cart`
--

CREATE TABLE `tbl_user_cart` (
  `usercart_user_id` varchar(100) NOT NULL,
  `usercart_type` int(11) NOT NULL,
  `usercart_details` longtext NOT NULL,
  `usercart_added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_credentials`
--

CREATE TABLE `tbl_user_credentials` (
  `credential_user_id` int(11) NOT NULL,
  `credential_username` varchar(255) NOT NULL,
  `credential_email` varchar(150) DEFAULT NULL,
  `credential_password` varchar(100) NOT NULL,
  `credential_active` tinyint(4) NOT NULL,
  `credential_verified` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_email_change_request`
--

CREATE TABLE `tbl_user_email_change_request` (
  `uecreq_id` int(11) NOT NULL,
  `uecreq_user_id` int(255) NOT NULL,
  `uecreq_email` varchar(255) NOT NULL,
  `uecreq_status` int(11) NOT NULL,
  `uecreq_created` datetime NOT NULL,
  `uecreq_updated` datetime NOT NULL,
  `uecreq_expire` datetime NOT NULL,
  `uecreq_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_email_verification`
--

CREATE TABLE `tbl_user_email_verification` (
  `uev_user_id` int(11) NOT NULL,
  `uev_token` varchar(50) NOT NULL,
  `uev_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_favourite_teachers`
--

CREATE TABLE `tbl_user_favourite_teachers` (
  `uft_id` int(11) NOT NULL,
  `uft_user_id` int(11) NOT NULL,
  `uft_teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_password_reset_requests`
--

CREATE TABLE `tbl_user_password_reset_requests` (
  `uprr_user_id` int(11) NOT NULL,
  `uprr_token` varchar(255) NOT NULL,
  `uprr_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_qualifications`
--

CREATE TABLE `tbl_user_qualifications` (
  `uqualification_id` int(11) NOT NULL,
  `uqualification_user_id` int(11) NOT NULL,
  `uqualification_experience_type` int(11) NOT NULL COMMENT 'defined in model',
  `uqualification_title` varchar(255) NOT NULL,
  `uqualification_institute_name` varchar(255) NOT NULL,
  `uqualification_institute_address` mediumtext NOT NULL,
  `uqualification_description` mediumtext NOT NULL,
  `uqualification_start_year` int(11) NOT NULL,
  `uqualification_end_year` int(11) NOT NULL,
  `uqualification_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_settings`
--

CREATE TABLE `tbl_user_settings` (
  `us_user_id` int(11) NOT NULL,
  `us_is_trial_lesson_enabled` tinyint(2) NOT NULL,
  `us_notice_number` int(11) NOT NULL,
  `us_single_lesson_amount` decimal(10,2) NOT NULL,
  `us_bulk_lesson_amount` decimal(10,2) NOT NULL,
  `us_video_link` varchar(255) NOT NULL,
  `us_teach_slanguage_id` int(11) NOT NULL,
  `us_booking_before` int(20) DEFAULT NULL,
  `us_google_access_token` varchar(255) NOT NULL,
  `us_google_access_token_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_teacher_requests`
--

CREATE TABLE `tbl_user_teacher_requests` (
  `utrequest_id` bigint(20) NOT NULL,
  `utrequest_reference` varchar(50) NOT NULL,
  `utrequest_user_id` bigint(20) NOT NULL,
  `utrequest_date` datetime NOT NULL,
  `utrequest_status` tinyint(1) NOT NULL,
  `utrequest_status_change_date` datetime NOT NULL,
  `utrequest_comments` mediumtext NOT NULL,
  `utrequest_attempts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_teacher_request_values`
--

CREATE TABLE `tbl_user_teacher_request_values` (
  `utrvalue_id` int(11) NOT NULL,
  `utrvalue_utrequest_id` int(11) NOT NULL,
  `utrvalue_user_first_name` varchar(100) NOT NULL,
  `utrvalue_user_last_name` varchar(100) NOT NULL,
  `utrvalue_user_gender` int(11) NOT NULL,
  `utrvalue_user_phone` varchar(50) NOT NULL,
  `utrvalue_user_video_link` varchar(255) NOT NULL,
  `utrvalue_user_profile_info` text NOT NULL,
  `utrvalue_user_teach_slanguage_id` varchar(255) NOT NULL,
  `utrvalue_user_language_speak` varchar(255) NOT NULL,
  `utrvalue_user_language_speak_proficiency` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_teach_languages`
--

CREATE TABLE `tbl_user_teach_languages` (
  `utl_id` int(11) NOT NULL,
  `utl_us_user_id` int(11) NOT NULL,
  `utl_slanguage_id` int(11) NOT NULL,
  `utl_single_lesson_amount` decimal(10,2) NOT NULL,
  `utl_bulk_lesson_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_timetables`
--

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

CREATE TABLE `tbl_user_to_preference` (
  `utpref_user_id` int(11) NOT NULL,
  `utpref_preference_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_to_spoken_languages`
--

CREATE TABLE `tbl_user_to_spoken_languages` (
  `utsl_user_id` int(11) NOT NULL,
  `utsl_slanguage_id` int(11) NOT NULL,
  `utsl_proficiency` int(11) NOT NULL COMMENT 'defined in spoken language model'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_transactions`
--

CREATE TABLE `tbl_user_transactions` (
  `utxn_id` bigint(20) NOT NULL,
  `utxn_user_id` int(11) NOT NULL,
  `utxn_date` datetime NOT NULL,
  `utxn_credit` decimal(10,2) NOT NULL,
  `utxn_debit` decimal(10,2) NOT NULL,
  `utxn_comments` mediumtext NOT NULL,
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

CREATE TABLE `tbl_user_withdrawal_requests` (
  `withdrawal_id` bigint(20) NOT NULL,
  `withdrawal_user_id` int(11) NOT NULL,
  `withdrawal_amount` decimal(10,2) NOT NULL,
  `withdrawal_bank` varchar(255) NOT NULL,
  `withdrawal_account_holder_name` varchar(255) NOT NULL,
  `withdrawal_account_number` varchar(100) NOT NULL,
  `withdrawal_ifc_swift_code` varchar(100) NOT NULL,
  `withdrawal_bank_address` mediumtext NOT NULL,
  `withdrawal_comments` mediumtext NOT NULL,
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
  ADD UNIQUE KEY `commsetting_user_id` (`commsetting_user_id`,`commsetting_is_grpcls`) USING BTREE;

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
-- Indexes for table `tbl_cron_log`
--
ALTER TABLE `tbl_cron_log`
  ADD PRIMARY KEY (`cronlog_id`),
  ADD KEY `cronlog_cron_id` (`cronlog_cron_id`);

--
-- Indexes for table `tbl_cron_schedules`
--
ALTER TABLE `tbl_cron_schedules`
  ADD PRIMARY KEY (`cron_id`);

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
-- Indexes for table `tbl_faq_categories`
--
ALTER TABLE `tbl_faq_categories`
  ADD PRIMARY KEY (`faqcat_id`);

--
-- Indexes for table `tbl_faq_categories_lang`
--
ALTER TABLE `tbl_faq_categories_lang`
  ADD PRIMARY KEY (`faqcatlang_faqcat_id`,`faqcatlang_lang_id`);

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
-- Indexes for table `tbl_group_classes`
--
ALTER TABLE `tbl_group_classes`
  ADD PRIMARY KEY (`grpcls_id`);

--
-- Indexes for table `tbl_issues_reported`
--
ALTER TABLE `tbl_issues_reported`
  ADD PRIMARY KEY (`issrep_id`);

--
-- Indexes for table `tbl_issue_report_options`
--
ALTER TABLE `tbl_issue_report_options`
  ADD PRIMARY KEY (`tissueopt_id`);

--
-- Indexes for table `tbl_issue_report_options_lang`
--
ALTER TABLE `tbl_issue_report_options_lang`
  ADD PRIMARY KEY (`tissueoptlang_tissueopt_id`,`tissueoptlang_lang_id`);

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
-- Indexes for table `tbl_lesson_reschedule_log`
--
ALTER TABLE `tbl_lesson_reschedule_log`
  ADD PRIMARY KEY (`lesreschlog_id`);

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
  ADD PRIMARY KEY (`slesson_id`),
  ADD KEY `slesson_teacher_id` (`slesson_teacher_id`),
  ADD KEY `slesson_status` (`slesson_status`);

--
-- Indexes for table `tbl_scheduled_lessons_to_teachers_lessons_plan`
--
ALTER TABLE `tbl_scheduled_lessons_to_teachers_lessons_plan`
  ADD UNIQUE KEY `ltp_slessonid` (`ltp_slessonid`);

--
-- Indexes for table `tbl_scheduled_lesson_details`
--
ALTER TABLE `tbl_scheduled_lesson_details`
  ADD PRIMARY KEY (`sldetail_id`);

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
  ADD UNIQUE KEY `spreview_order_id` (`tlreview_lesson_id`),
  ADD KEY `tlreview_teacher_user_id` (`tlreview_teacher_user_id`,`tlreview_status`);

--
-- Indexes for table `tbl_teacher_offer_price`
--
ALTER TABLE `tbl_teacher_offer_price`
  ADD PRIMARY KEY (`top_teacher_id`,`top_learner_id`);

--
-- Indexes for table `tbl_teaching_languages`
--
ALTER TABLE `tbl_teaching_languages`
  ADD PRIMARY KEY (`tlanguage_id`),
  ADD KEY `tlanguage_identifier` (`tlanguage_identifier`);

--
-- Indexes for table `tbl_teaching_languages_lang`
--
ALTER TABLE `tbl_teaching_languages_lang`
  ADD PRIMARY KEY (`tlanguagelang_tlanguage_id`,`tlanguagelang_lang_id`),
  ADD KEY `tlanguage_name` (`tlanguage_name`);

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
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_country_id` (`user_country_id`,`user_is_teacher`);

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
  ADD UNIQUE KEY `credential_username` (`credential_email`),
  ADD KEY `credential_active` (`credential_active`,`credential_verified`);

--
-- Indexes for table `tbl_user_email_change_request`
--
ALTER TABLE `tbl_user_email_change_request`
  ADD PRIMARY KEY (`uecreq_id`);

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
  ADD PRIMARY KEY (`utrequest_id`);

--
-- Indexes for table `tbl_user_teacher_request_values`
--
ALTER TABLE `tbl_user_teacher_request_values`
  ADD PRIMARY KEY (`utrvalue_id`),
  ADD UNIQUE KEY `utrvalue_ututrequest_id` (`utrvalue_utrequest_id`);

--
-- Indexes for table `tbl_user_teach_languages`
--
ALTER TABLE `tbl_user_teach_languages`
  ADD PRIMARY KEY (`utl_id`),
  ADD UNIQUE KEY `language` (`utl_us_user_id`,`utl_slanguage_id`),
  ADD KEY `utl_single_lesson_amount` (`utl_single_lesson_amount`,`utl_bulk_lesson_amount`);

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
  ADD PRIMARY KEY (`utsl_user_id`,`utsl_slanguage_id`),
  ADD KEY `utsl_slanguage_id` (`utsl_slanguage_id`);

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
  MODIFY `afile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=925;

--
-- AUTO_INCREMENT for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_banner_locations`
--
ALTER TABLE `tbl_banner_locations`
  MODIFY `blocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_bible_content`
--
ALTER TABLE `tbl_bible_content`
  MODIFY `biblecontent_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_blog_contributions`
--
ALTER TABLE `tbl_blog_contributions`
  MODIFY `bcontributions_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_blog_post`
--
ALTER TABLE `tbl_blog_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_blog_post_categories`
--
ALTER TABLE `tbl_blog_post_categories`
  MODIFY `bpcategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_blog_post_comments`
--
ALTER TABLE `tbl_blog_post_comments`
  MODIFY `bpcomment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_commission_settings`
--
ALTER TABLE `tbl_commission_settings`
  MODIFY `commsetting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_commission_setting_history`
--
ALTER TABLE `tbl_commission_setting_history`
  MODIFY `csh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_content_pages`
--
ALTER TABLE `tbl_content_pages`
  MODIFY `cpage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_content_pages_block_lang`
--
ALTER TABLE `tbl_content_pages_block_lang`
  MODIFY `cpblocklang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_countries`
--
ALTER TABLE `tbl_countries`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `tbl_cron_log`
--
ALTER TABLE `tbl_cron_log`
  MODIFY `cronlog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_cron_schedules`
--
ALTER TABLE `tbl_cron_schedules`
  MODIFY `cron_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_currencies`
--
ALTER TABLE `tbl_currencies`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_email_archives`
--
ALTER TABLE `tbl_email_archives`
  MODIFY `emailarchive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_faq_categories`
--
ALTER TABLE `tbl_faq_categories`
  MODIFY `faqcat_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `tbl_group_classes`
--
ALTER TABLE `tbl_group_classes`
  MODIFY `grpcls_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_issues_reported`
--
ALTER TABLE `tbl_issues_reported`
  MODIFY `issrep_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_issue_report_options`
--
ALTER TABLE `tbl_issue_report_options`
  MODIFY `tissueopt_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_issue_report_options_lang`
--
ALTER TABLE `tbl_issue_report_options_lang`
  MODIFY `tissueoptlang_tissueopt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_language_labels`
--
ALTER TABLE `tbl_language_labels`
  MODIFY `label_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3812;

--
-- AUTO_INCREMENT for table `tbl_lesson_packages`
--
ALTER TABLE `tbl_lesson_packages`
  MODIFY `lpackage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_lesson_reschedule_log`
--
ALTER TABLE `tbl_lesson_reschedule_log`
  MODIFY `lesreschlog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_meta_tags`
--
ALTER TABLE `tbl_meta_tags`
  MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_navigations`
--
ALTER TABLE `tbl_navigations`
  MODIFY `nav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_navigation_links`
--
ALTER TABLE `tbl_navigation_links`
  MODIFY `nlink_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `pmethod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_preferences`
--
ALTER TABLE `tbl_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_scheduled_lessons`
--
ALTER TABLE `tbl_scheduled_lessons`
  MODIFY `slesson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_scheduled_lesson_details`
--
ALTER TABLE `tbl_scheduled_lesson_details`
  MODIFY `sldetail_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `slanguage_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `tbl_teaching_languages`
--
ALTER TABLE `tbl_teaching_languages`
  MODIFY `tlanguage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_testimonials`
--
ALTER TABLE `tbl_testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `urlrewrite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_email_change_request`
--
ALTER TABLE `tbl_user_email_change_request`
  MODIFY `uecreq_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `utrequest_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_teacher_request_values`
--
ALTER TABLE `tbl_user_teacher_request_values`
  MODIFY `utrvalue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_teach_languages`
--
ALTER TABLE `tbl_user_teach_languages`
  MODIFY `utl_id` int(11) NOT NULL AUTO_INCREMENT;

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



-- task 72959 / 1-AUG-2020 / RV-2.0

ALTER TABLE `tbl_user_withdrawal_requests` ADD `withdrawal_payment_method_id` INT NOT NULL AFTER `withdrawal_amount`;

-- UPDATE `tbl_user_withdrawal_requests` SET `withdrawal_payment_method_id` = '1' WHERE `tbl_user_withdrawal_requests`.`withdrawal_payment_method_id` = 0;

ALTER TABLE `tbl_user_withdrawal_requests` ADD `withdrawal_paypal_email_id` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `withdrawal_status`;

ALTER TABLE `tbl_user_withdrawal_requests` ADD `withdrawal_response` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `withdrawal_paypal_email_id`;

ALTER TABLE `tbl_payment_methods` ADD `pmethod_type` INT NOT NULL COMMENT 'payment method type (defined in PaymentMethods model)' AFTER `pmethod_identifier`;

UPDATE `tbl_payment_methods` SET `pmethod_type` = '1' WHERE `tbl_payment_methods`.`pmethod_type` = 0;

--
-- Table structure for table `tbl_payment_method_transaction_fee`
--

CREATE TABLE `tbl_payment_method_transaction_fee` (
  `pmtfee_pmethod_id` int(11) NOT NULL,
  `pmtfee_currency_id` int(11) NOT NULL,
  `pmtfee_fee` decimal(12,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `tbl_payment_method_transaction_fee` ADD UNIQUE( `pmtfee_pmethod_id`, `pmtfee_currency_id`);


INSERT INTO `tbl_payment_methods` (`pmethod_id`, `pmethod_identifier`, `pmethod_type`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`) VALUES (NULL, 'Paypal Payout', '2', 'PaypalPayout', '1', '3');

ALTER TABLE `tbl_user_withdrawal_requests` ADD `withdrawal_transaction_fee` DECIMAL(10,4) NOT NULL AFTER `withdrawal_amount`;


INSERT INTO `tbl_payment_methods` (`pmethod_id`, `pmethod_identifier`, `pmethod_type`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`) VALUES (NULL, 'Bank Payout', '2', 'BankPayout', '1', '4');

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_MEETING_TOOL_COMET_CHAT', 1, 0), ('CONF_MEETING_TOOL_LESSONSPACE', 2, 0), ('CONF_ACTIVE_MEETING_TOOL', 2, 0);

--
-- Table structure for table `tbl_lesson_meeting_details`
--

CREATE TABLE `tbl_lesson_meeting_details` (
  `lmeetdetail_id` int(11) NOT NULL,
  `lmeetdetail_slesson_id` int(11) NOT NULL,
  `lmeetdetail_user_id` int(11) NOT NULL,
  `lmeetdetail_key` varchar(255) NOT NULL,
  `lmeetdetail_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_lesson_meeting_details`
--
ALTER TABLE `tbl_lesson_meeting_details`
  ADD PRIMARY KEY (`lmeetdetail_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_lesson_meeting_details`
--
ALTER TABLE `tbl_lesson_meeting_details`
  MODIFY `lmeetdetail_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('MSG_Money_added_to_wallet', 1, '<p>Your order has been successfully processed!</p><p>Please direct any questions you have to the <a href=\"{contact-us-page-url}\">web portal owner</a>.</p><p>Thanks for choosing us online!');

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_TIMEZONE_STRING',1,'UTC');
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_TIMEZONE_STRING',2,'UTC');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('class_book_email_confirmation', 1, 'New Class Booked', 'New Class Booked at {website_name}', '<table style=\"font-family:Arial; color:#333; line-height:26px;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f5f5f5\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table style=\"background: #fff;border-bottom: 1px solid #eee;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                                                          \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Your class is Booked!</h2>                                     \r\n                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}  </h3>Your class ({class_name}) with ({teacher_name}) is booked, which is scheduled on {class_date} {class_start_time} - {class_end_time}<br />\r\n												<br />\r\n												</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{class_name}\r\n{learner_comment}\r\n{class_date}\r\n{class_start_time}\r\n{class_end_time}\r\n{status}\r\n', 1),
('learner_class_book_email', 1, 'Learner New Class Book Email', 'Learner New Class Book Email at {website_name}', '<table style=\"font-family:Arial; color:#333; line-height:26px;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f5f5f5\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table style=\"background: #fff;border-bottom: 1px solid #eee;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                                                          \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Learner {status} The Lesson!</h2>                                     \r\n                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {teacher_name}  </h3>Learner ({learner_name}) has {status} the class ({class_name}) which is scheduled on {class_date} {class_start_time} - {class_end_time}<br />\r\n												<br />\r\n												</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{learner_name}\r\n{teacher_name}\r\n{class_name}\r\n{learner_comment}\r\n{class_date}\r\n{class_start_time}\r\n{class_end_time}\r\n{status}\r\n', 1);


UPDATE `tbl_language_labels` SET `label_caption` = 'Currently, there are no classes found per filtered criteria.' WHERE`label_key` = 'LBL_No_classes_found'; 

UPDATE `tbl_url_rewrites` SET `urlrewrite_custom` = 'teachers/profile/urlparameter' WHERE `tbl_url_rewrites`.`urlrewrite_custom` = 'teachers/urlparameter';

UPDATE `tbl_content_pages_block_lang` SET `cpblocklang_text` = '<section class=\"section section--white section--centered\">\r\n    <div class=\"container container--narrow container--cms\">\r\n        <div class=\"section__body\" style=\"\">\r\n            <!--\r\n            ------ First Section -------\r\n            -->\r\n            \r\n            <div class=\"row justify-content-center -align-center\">\r\n                <div class=\"col-xl-10 col-lg-12 col-md-12\">\r\n                    <div class=\"row\">\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <div class=\"icon\"><img src=\"/public/images/icon_mission.svg\" alt=\"\" /></div>\r\n                            <h4>The Mission</h4>\r\n                            <p>Nulla ornare euismod blandit. Quisque metus turpis, sollicitudin eget pellentesque sit amet, sagittis non dui. Sed convallis et nisl eget molestie. Vestibulum quis leo purus. Nunc iaculis placerat enim non tempus. </p></div>\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <div class=\"icon\"><img src=\"/public/images/icon_vision.svg\" alt=\"\" /></div>\r\n                            <h4>The Vision</h4>\r\n                            <p>Nulla ornare euismod blandit. Quisque metus turpis, sollicitudin eget pellentesque sit amet, sagittis non dui. Sed convallis et nisl eget molestie. Vestibulum quis leo purus. Nunc iaculis placerat enim non tempus.</p></div></div><span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span>\r\n                    <div class=\"-align-center section__head\">\r\n                        <h2>The Yocoach</h2></div>\r\n                    <div class=\"row\">\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <p style=\"text-align:left;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin ante vel nisl mollis, vel viverra ipsum molestie. Nullam vehicula eros magna, id pharetra diam fringilla sit amet. Sed id lectus quis diam facilisis mollis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut convallis nec ante id dictum. Nunc rutrum ultrices dolor sit amet dignissim. Donec commodo turpis quis justo interdum egestas. Phasellus vestibulum aliquet neque eget scelerisque. Integer lacus orci, faucibus a nunc sed, rutrum iaculis tortor. Etiam sit amet massa sapien. In tincidunt sem at lorem viverra rutrum. Ut erat metus, mattis vel venenatis at, sodales et libero. Suspendisse consequat congue pretium. Morbi a eros fermentum, maximus neque in, egestas lectus. Nunc aliquam ante erat, vitae efficitur dui aliquam sit amet.</p></div>\r\n                        <div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n                            <p style=\"text-align:left;\">Ut lacus nisi, pulvinar eu tortor non, consectetur sollicitudin ante. Etiam maximus, neque a fermentum porta, mauris est consequat quam, eu fermentum quam arcu et nulla. Nullam ipsum turpis, lobortis lobortis cursus a, malesuada id nulla. Proin vitae pellentesque enim. Morbi quis viverra ante. Etiam malesuada, nisi eu hendrerit varius, nunc mi condimentum mi, eget lacinia sapien eros at erat. Aliquam pretium mattis erat, ultricies malesuada mi luctus eget. Aliquam in dignissim mi. Curabitur quam ante, feugiat sit amet commodo eu, tincidunt ut nibh. Ut ultricies velit non nibh lacinia dignissim.</p></div></div></div></div>\r\n            <!--\r\n            ------------\r\n            -->\r\n            </div></div></section>\r\n<section class=\"section section--grey -align-center\">\r\n    <div class=\"container container--narrow\">\r\n        <div class=\"section__head\">\r\n            <h2>The Team</h2></div>\r\n        <div class=\"section__body\">\r\n            <div class=\"row justify-content-center\">\r\n                <div class=\"col-xl-9 col-lg-12\">\r\n                    <div class=\"row\">\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_2.jpg\" alt=\"\" /></div>\r\n                            <h4>Kirstin</h4>\r\n                            <p>Customer Executive</p></div>\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_5.jpg\" alt=\"\" /></div>\r\n                            <h4>Cooper</h4>\r\n                            <p>Product Design</p></div>\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_4.jpg\" alt=\"\" /></div>\r\n                            <h4>Andrew</h4>\r\n                            <p>Marketing</p></div>\r\n                        <div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n                            <div class=\"team__media\"><img src=\"/public/images/300x300_3.jpg\" alt=\"\" /></div>\r\n                            <h4>Mikael</h4>\r\n                            <p>Product Developer</p></div></div></div></div></div></div></section>\r\n<section class=\"section section--white section--hiw\">\r\n    <div class=\"container container--narrow\">\r\n        <div class=\"section-title\">\r\n            <h2>How It Works</h2></div>\r\n        <div class=\"row justify-content-between\">\r\n            <div class=\"col-xl-4 col-lg-5 col-md-12 col-sm-12\">\r\n                <div class=\"tabs-vertical tabs-js\">\r\n                    <ul>\r\n                        <li class=\"is-active\" data-href=\"#tab1\">\r\n                            <div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n                                <div class=\"tab-info\">\r\n                                    <h3>Browse</h3> \r\n                                    <p>Browse through hundreds of teachers.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n                        <li class=\"\" data-href=\"#tab2\">\r\n                            <div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n                                <div class=\"tab-info\">\r\n                                    <h3>Book</h3> \r\n                                    <p>Book lessons with the best teacher for you.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n                        <li class=\"\" data-href=\"#tab3\">\r\n                            <div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n                                <div class=\"tab-info\">\r\n                                    <h3>Start</h3> \r\n                                    <p>Log in to YoCoach and start learning.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n                    </ul></div></div>\r\n            <div class=\"col-xl-7 col-lg-7 col-md-12 col-sm-12 col__content\">\r\n                <div id=\"tab1\" class=\"tabs-content-js\" style=\"display: block;\">\r\n                    <div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/4/0/3\" alt=\"\" /></a></div></div>\r\n                <div id=\"tab2\" class=\"tabs-content-js\" style=\"display: none;\">\r\n                    <div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/5/0/3\" alt=\"\" /></a></div></div>\r\n                <div id=\"tab3\" class=\"tabs-content-js\" style=\"display: none;\">\r\n                    <div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/6/0/3\" alt=\"\" /></a></div></div></div></div></div></section>\r\n<section class=\"section section--white\">             \r\n    <div class=\"container container--narrow -align-center\">                 \r\n        <h2 class=\"-style-bold\">Looking forward to meeting<br />\r\n              your new students?</h2>                 <span class=\"-gap\"></span>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>' WHERE `cpblocklang_id` = 1;


-- clean email archives after an interval

/* SET GLOBAL event_scheduler = ON;
CREATE EVENT event_clean_email_archives ON SCHEDULE EVERY 1 MONTH ENABLE
  DO 
  delete from tbl_email_archives
  WHERE `emailarchive_sent_on` < CURRENT_TIMESTAMP - INTERVAL 1 MONTH; */

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_ENABLE_FLASHCARD', 1, 0);


UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.3.1.20201028' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';


REPLACE INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
('CONF_ANALYTICS_ACCESS_TOKEN', '1//0glmaGc5eiieRCgYIARAAGBASNwF-L9IrsvdLLYVTMldzNsxW-KIxbA5oAE4nwGMySOYHYsz8yErmqcXs9hZ3INqw4r3FPYfHe64', 0),
('CONF_ANALYTICS_CLIENT_ID', '726877070901-9m6sct2rbdj7edf9n9ugl0h4bk45f45v.apps.googleusercontent.com', 0),
('CONF_ANALYTICS_ID', 'UA-82212031-1', 0),
('CONF_ANALYTICS_SECRET_KEY', '3HlpqTqwL5RVb96ZM7bS_Kky', 0);

REPLACE INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
('CONF_SITE_TRACKER_CODE', '<!-- Global site tag (gtag.js) - Google Analytics -->\r\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-82212031-1\"></script>\r\n<script>\r\n window.dataLayer = window.dataLayer || [];\r\n function gtag(){dataLayer.push(arguments);}\r\n gtag(\'js\', new Date());\r\n gtag(\'config\', \'UA-82212031-1\');\r\n</script>>', 0);


UPDATE `tbl_social_platforms` SET `splatform_url` = 'https://www.facebook.com/yocoachelearning/' WHERE `splatform_identifier` = 'Facebook';
UPDATE `tbl_social_platforms` SET `splatform_url` = 'https://twitter.com/yo_coach_' WHERE `splatform_identifier` = 'Twitter';
UPDATE `tbl_social_platforms` SET `splatform_url` = 'https://in.pinterest.com/YoCoach_/' WHERE `splatform_identifier` = 'Pinterest';
UPDATE `tbl_social_platforms` SET `splatform_url` = 'https://www.instagram.com/YoCoach_Software/' WHERE `splatform_identifier` = 'Instagram';
UPDATE `tbl_social_platforms` SET `splatform_url` = 'https://www.youtube.com/channel/UCNPly8tAtfBneXv1MfzjD4g' WHERE `splatform_identifier` = 'Youtube';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_COPYRIGHT_TEXT', 1, 'Copyright {YEAR} {PRODUCT} Developed by {OWNER}.');

replace INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('tpl_teacher_request_received', '1', 'New Teacher Request - Admin', 'New Teacher Request on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">    \r\n    <tbody>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:30px 0;\"></td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:0 0 0;\">                \r\n                <!--\r\n                header start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td> \r\n <td style=\"text-align:right;padding: 40px;\">{social_media_icons}\r\n </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                header end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page body start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\"><img src=\"icon-account.png\" alt=\"\" />         \r\n         <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">         </h5>         \r\n         <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\"> Teacher Request</h2>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\">         \r\n         <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\"> Dear Admin</h3>\r\n         <p>{name} wants to become a teacher on <a href=\"{website_url}\">{website_name}</a>.</p>         \r\n         <table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">             \r\n <tbody> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Refernce Number</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{refnum}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Name</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Phone<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Subjects</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{subjects}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Requested On</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{request_date}</td> \r\n     </tr>             \r\n </tbody>         \r\n         </table>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page body end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page footer start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"height:30px;\"></td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more\r\n         help?<br />\r\n      <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">Be sure to add\r\n         <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a>to your\r\n         address book or safe sender list so our emails get to your inbox.<br />\r\n      <br />\r\n      &copy; 2018, {website_name}. All Rights Reserved.\r\n     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0; height:50px;\"></td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page footer end here\r\n                -->\r\n </td>        \r\n        </tr>    \r\n    </tbody>\r\n</table>', '\'{refnum}\' => Request Reference Number\r\n\'{name}\' => Applicant name,\r\n\'{phone}\' => Phone Number,\r\n\'{request_date}\' => Requested On - Datetime,\r\n\'{subjects}\' => Subjects that the application can teach', '1');

replace INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('tpl_teacher_request_received', '2', 'New Teacher Request - Admin', 'New Teacher Request on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">    \r\n    <tbody>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:30px 0;\"></td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:0 0 0;\">                \r\n                <!--\r\n                header start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td> \r\n <td style=\"text-align:right;padding: 40px;\">{social_media_icons}\r\n </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                header end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page body start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\"><img src=\"icon-account.png\" alt=\"\" />         \r\n         <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">         </h5>         \r\n         <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\"> Teacher Request</h2>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\">         \r\n         <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\"> Dear Admin</h3>\r\n         <p>{name} wants to become a teacher on <a href=\"{website_url}\">{website_name}</a>.</p>         \r\n         <table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">             \r\n <tbody> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Refernce Number</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{refnum}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Name</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Phone<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Subjects</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{subjects}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Requested On</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{request_date}</td> \r\n     </tr>             \r\n </tbody>         \r\n         </table>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page body end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page footer start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"height:30px;\"></td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more\r\n         help?<br />\r\n      <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">Be sure to add\r\n         <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a>to your\r\n         address book or safe sender list so our emails get to your inbox.<br />\r\n      <br />\r\n      &copy; 2018, {website_name}. All Rights Reserved.\r\n     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0; height:50px;\"></td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page footer end here\r\n                -->\r\n </td>        \r\n        </tr>    \r\n    </tbody>\r\n</table>', '\'{refnum}\' => Request Reference Number\r\n\'{name}\' => Applicant name,\r\n\'{phone}\' => Phone Number,\r\n\'{request_date}\' => Requested On - Datetime,\r\n\'{subjects}\' => Subjects that the application can teach', '1');



-- task_77655_GDPR_compliance_and_secure_cookies

CREATE TABLE `tbl_user_cookie_consent` (
  `usercc_user_id` int(11) NOT NULL,
  `usercc_settings` varchar(255) NOT NULL,
  `usercc_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `tbl_user_cookie_preferences`
--
ALTER TABLE `tbl_user_cookie_consent`
  ADD PRIMARY KEY (`usercc_user_id`);


REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_Cookie_settings_update_successfully', '1', 'Cookie settings updated succesfully.')
, ('LBL_COOKIE_CONSENT_HEADING', '1', 'Cookie Consent')
, ('LBL_STATISTICS_COOKIE_DESCRIPTION_TEXT', '1', 'These cookies allow us to count visits and traffic sources so we can measure and improve the performance of our site. They help us to know which pages are the most and least popular and see how visitors move around the site. All information these cookies collect is aggregated and therefore anonymous. If you do not allow these cookies we will not know when you have visited our site, and will not be able to monitor its performance.')
, ('LBL_PREFERENCES_COOKIE_DESCRIPTION_TEXT', '1', 'These cookies enable the website to provide enhanced functionality and personalisation. If you do not allow these cookies then some or all of these services may not function properly.')
, ('LBL_NECESSARY_COOKIE_DESCRIPTION_TEXT', '1', 'These cookies are necessary for the website to function and cannot be switched off in our systems. They are usually only set in response to actions made by you which amount to a request for services, such as setting your privacy preferences, logging in or filling in forms. You can set your browser to block or alert you about these cookies, but some parts of the site will not then work. These cookies do not store any personally identifiable information.')
;

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.4.0.20201104' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.4.1.20201105' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';

ALTER TABLE `tbl_user_settings`
  DROP `us_single_lesson_amount`,
  DROP `us_bulk_lesson_amount`;

  REPLACE INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
('CONF_SITE_TRACKER_CODE', '<!-- Global site tag (gtag.js) - Google Analytics -->\r\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-82212031-1\"></script>\r\n<script>\r\n window.dataLayer = window.dataLayer || [];\r\n function gtag(){dataLayer.push(arguments);}\r\n gtag(\'js\', new Date());\r\n gtag(\'config\', \'UA-82212031-1\');\r\n</script>', 0);

UPDATE `tbl_url_rewrites` SET urlrewrite_custom = 'teachers/profile/urlparameter' WHERE `urlrewrite_original` LIKE 'teachers/view';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.5.0.20201118' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.6.20201120' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.7.0.20201120' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.7.1.20201121' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.7.2.20201123' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.7.3.20201124' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';