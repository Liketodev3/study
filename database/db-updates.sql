ALTER TABLE `tbl_teachers_weekly_schedule` ADD `twsch_weekyear` VARCHAR(10) NOT NULL AFTER `twsch_end_time`; 

--
-- Table structure for table `tbl_timezone`
--

CREATE TABLE `tbl_timezone` (
  `timezone_id` int(11) NOT NULL,
  `timezone_offset` varchar(10) NOT NULL,
  `timezone_identifier` varchar(100) NOT NULL,
  `timezone_name` varchar(100) NOT NULL,
  `timezone_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_timezone`
--

INSERT INTO `tbl_timezone` (`timezone_id`, `timezone_offset`, `timezone_identifier`, `timezone_name`, `timezone_active`) VALUES
(1, '+02:00', 'Africa/Cairo', 'Cairo', 1),
(2, '+01:00', 'Africa/Casablanca', ' Casablanca', 1),
(3, '+02:00', 'Africa/Harare', ' Harare', 1),
(4, '+02:00', 'Africa/Johannesburg', ' Pretoria', 1),
(5, '+01:00', 'Africa/Lagos', ' West Central Africa', 1),
(6, '+00:00', 'Africa/Monrovia', ' Monrovia', 1),
(7, '+03:00', 'Africa/Nairobi', ' Nairobi', 1),
(8, '-03:00', 'America/Argentina/Buenos_Aires', ' Georgetown', 1),
(9, '-05:00', 'America/Bogota', ' Bogota', 1),
(10, '-04:00', 'America/Caracas', ' Caracas', 1),
(11, '-07:00', 'America/Chihuahua', ' Chihuahua', 1),
(12, '-03:00', 'America/Godthab', ' Greenland', 1),
(13, '-04:00', 'America/La_Paz', ' La Paz', 1),
(14, '-05:00', 'America/Lima', ' Lima', 1),
(15, '-08:00', 'America/Los_Angeles', ' Pacific Time (US & Canada)', 1),
(16, '-06:00', 'America/Managua', ' Central America', 1),
(17, '-07:00', 'America/Mazatlan', ' Mazatlan', 1),
(18, '-06:00', 'America/Mexico_City', ' Guadalajara', 1),
(19, '-06:00', 'America/Monterrey', ' Monterrey', 1),
(20, '-02:00', 'America/Noronha', ' Mid-Atlantic', 1),
(21, '-03:00', 'America/Santiago', ' Santiago', 1),
(22, '-02:00', 'America/Sao_Paulo', ' Brasilia', 1),
(23, '-08:00', 'America/Tijuana', ' Tijuana', 1),
(24, '+06:00', 'Asia/Almaty', ' Almaty', 1),
(25, '+03:00', 'Asia/Baghdad', ' Baghdad', 1),
(26, '+04:00', 'Asia/Baku', ' Baku', 1),
(27, '+07:00', 'Asia/Bangkok', ' Bangkok', 1),
(28, '+05:30', 'Asia/Calcutta', ' Sri Jayawardenepura', 1),
(29, '+08:00', 'Asia/Chongqing', ' Chongqing', 1),
(30, '+06:00', 'Asia/Dhaka', ' Astana', 1),
(31, '+08:00', 'Asia/Hong_Kong', ' Hong Kong', 1),
(32, '+08:00', 'Asia/Irkutsk', ' Irkutsk', 1),
(33, '+07:00', 'Asia/Jakarta', ' Jakarta', 1),
(34, '+02:00', 'Asia/Jerusalem', ' Jerusalem', 1),
(35, '+04:30', 'Asia/Kabul', ' Kabul', 1),
(36, '+12:00', 'Asia/Kamchatka', ' Kamchatka', 1),
(37, '+05:00', 'Asia/Karachi', ' Islamabad', 1),
(38, '+05:45', 'Asia/Katmandu', ' Kathmandu', 1),
(39, '+05:30', 'Asia/Kolkata', ' Kolkata', 1),
(40, '+07:00', 'Asia/Krasnoyarsk', ' Krasnoyarsk', 1),
(41, '+08:00', 'Asia/Kuala_Lumpur', ' Kuala Lumpur', 1),
(42, '+03:00', 'Asia/Kuwait', ' Kuwait', 1),
(43, '+11:00', 'Asia/Magadan', ' New Caledonia', 1),
(44, '+04:00', 'Asia/Muscat', ' Muscat', 1),
(45, '+07:00', 'Asia/Novosibirsk', ' Novosibirsk', 1),
(46, '+06:30', 'Asia/Rangoon', ' Rangoon', 1),
(47, '+03:00', 'Asia/Riyadh', ' Riyadh', 1),
(48, '+09:00', 'Asia/Seoul', ' Seoul', 1),
(49, '+08:00', 'Asia/Singapore', ' Singapore', 1),
(50, '+08:00', 'Asia/Taipei', ' Taipei', 1),
(51, '+05:00', 'Asia/Tashkent', ' Tashkent', 1),
(52, '+04:00', 'Asia/Tbilisi', ' Tbilisi', 1),
(53, '+03:30', 'Asia/Tehran', ' Tehran', 1),
(54, '+09:00', 'Asia/Tokyo', ' Sapporo', 1),
(55, '+08:00', 'Asia/Ulan_Bator', ' Ulaan Bataar', 1),
(56, '+06:00', 'Asia/Urumqi', ' Urumqi', 1),
(57, '+10:00', 'Asia/Vladivostok', ' Vladivostok', 1),
(58, '+09:00', 'Asia/Yakutsk', ' Yakutsk', 1),
(59, '+05:00', 'Asia/Yekaterinburg', ' Ekaterinburg', 1),
(60, '+04:00', 'Asia/Yerevan', ' Yerevan', 1),
(61, '-01:00', 'Atlantic/Azores', ' Azores', 1),
(62, '-01:00', 'Atlantic/Cape_Verde', ' Cape Verde Is.', 1),
(63, '+10:30', 'Australia/Adelaide', ' Adelaide', 1),
(64, '+10:00', 'Australia/Brisbane', ' Brisbane', 1),
(65, '+11:00', 'Australia/Canberra', ' Canberra', 1),
(66, '+09:30', 'Australia/Darwin', ' Darwin', 1),
(67, '+11:00', 'Australia/Hobart', ' Hobart', 1),
(68, '+11:00', 'Australia/Melbourne', ' Melbourne', 1),
(69, '+08:00', 'Australia/Perth', ' Perth', 1),
(70, '+11:00', 'Australia/Sydney', ' Sydney', 1),
(71, '-04:00', 'Canada/Atlantic', ' Atlantic Time (Canada)', 1),
(72, '-03:30', 'Canada/Newfoundland', ' Newfoundland', 1),
(73, '-06:00', 'Canada/Saskatchewan', ' Saskatchewan', 1),
(74, '+00:00', 'Etc/Greenwich', ' Greenwich Mean Time : Dublin', 1),
(75, '+01:00', 'Europe/Amsterdam', ' Amsterdam', 1),
(76, '+02:00', 'Europe/Athens', ' Athens', 1),
(77, '+01:00', 'Europe/Belgrade', ' Belgrade', 1),
(78, '+01:00', 'Europe/Berlin', ' Bern', 1),
(79, '+01:00', 'Europe/Bratislava', ' Bratislava', 1),
(80, '+01:00', 'Europe/Brussels', ' Brussels', 1),
(81, '+02:00', 'Europe/Bucharest', ' Bucharest', 1),
(82, '+01:00', 'Europe/Budapest', ' Budapest', 1),
(83, '+01:00', 'Europe/Copenhagen', ' Copenhagen', 1),
(84, '+02:00', 'Europe/Helsinki', ' Kyiv', 1),
(85, '+03:00', 'Europe/Istanbul', ' Istanbul', 1),
(86, '+00:00', 'Europe/Lisbon', ' Lisbon', 1),
(87, '+01:00', 'Europe/Ljubljana', ' Ljubljana', 1),
(88, '+00:00', 'Europe/London', ' London', 1),
(89, '+01:00', 'Europe/Madrid', ' Madrid', 1),
(90, '+03:00', 'Europe/Minsk', ' Minsk', 1),
(91, '+03:00', 'Europe/Moscow', ' Moscow', 1),
(92, '+01:00', 'Europe/Paris', ' Paris', 1),
(93, '+01:00', 'Europe/Prague', ' Prague', 1),
(94, '+02:00', 'Europe/Riga', ' Riga', 1),
(95, '+01:00', 'Europe/Rome', ' Rome', 1),
(96, '+01:00', 'Europe/Sarajevo', ' Sarajevo', 1),
(97, '+01:00', 'Europe/Skopje', ' Skopje', 1),
(98, '+02:00', 'Europe/Sofia', ' Sofia', 1),
(99, '+01:00', 'Europe/Stockholm', ' Stockholm', 1),
(100, '+02:00', 'Europe/Tallinn', ' Tallinn', 1),
(101, '+01:00', 'Europe/Vienna', ' Vienna', 1),
(102, '+02:00', 'Europe/Vilnius', ' Vilnius', 1),
(103, '+04:00', 'Europe/Volgograd', ' Volgograd', 1),
(104, '+01:00', 'Europe/Warsaw', ' Warsaw', 1),
(105, '+01:00', 'Europe/Zagreb', ' Zagreb', 1),
(106, '+13:00', 'Pacific/Auckland', ' Auckland', 1),
(107, '+13:00', 'Pacific/Fiji', ' Marshall Is.', 1),
(108, '+10:00', 'Pacific/Guam', ' Guam', 1),
(109, '-10:00', 'Pacific/Honolulu', ' Hawaii', 1),
(110, '+12:00', 'Pacific/Kwajalein', ' International Date Line West', 1),
(111, '-11:00', 'Pacific/Midway', ' Midway Island', 1),
(112, '+10:00', 'Pacific/Port_Moresby', ' Port Moresby', 1),
(113, '-11:00', 'Pacific/Samoa', ' Samoa', 1),
(114, '+13:00', 'Pacific/Tongatapu', ' Nuku\'alofa', 1),
(115, '-09:00', 'US/Alaska', ' Alaska', 1),
(116, '-07:00', 'US/Arizona', ' Arizona', 1),
(117, '-06:00', 'US/Central', ' Central Time (US & Canada)', 1),
(118, '-05:00', 'US/East-Indiana', ' Indiana (East)', 1),
(119, '-05:00', 'US/Eastern', ' Eastern Time (US & Canada)', 1),
(120, '-07:00', 'US/Mountain', ' Mountain Time (US & Canada)', 1),
(121, '+00:00', 'UTC', ' UTC', 1);

--
-- Indexes for table `tbl_timezone`
--
ALTER TABLE `tbl_timezone`
  ADD PRIMARY KEY (`timezone_id`),
  ADD UNIQUE KEY `timezone_identifier` (`timezone_identifier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_timezone`
--
ALTER TABLE `tbl_timezone`
  MODIFY `timezone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;


--
-- Table structure for table `tbl_timezone_lang`
--

CREATE TABLE `tbl_timezone_lang` (
  `timezonelang_timezone_id` varchar(100) NOT NULL,
  `timezonelang_lang_id` int(11) NOT NULL,
  `timezonelang_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_timezone_lang`
--
ALTER TABLE `tbl_timezone_lang`
  ADD UNIQUE KEY `timezonelang_timezone_id` (`timezonelang_timezone_id`,`timezonelang_lang_id`);


INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_Timezone_:_UTC_%s', 1, 'Timezone : UTC %s');

REPLACE INTO `tbl_payment_methods` (`pmethod_identifier`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`,`pmethod_type`) VALUES ('PayGate payweb-3', 'PayGate', 1, 5, 1);
REPLACE INTO `tbl_payment_methods_lang` (`pmethodlang_pmethod_id`, `pmethodlang_lang_id`, `pmethod_name`, `pmethod_description`) VALUES ('6', '1', 'PayGate payweb-3', 'PayGate payweb-3 Payment Gateway Description will go here.');

REPLACE INTO `tbl_payment_methods` (`pmethod_identifier`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`,`pmethod_type`) VALUES ('Twocheckout', 'Twocheckout', 1, 6, 1);

UPDATE `tbl_configurations` SET `conf_val` = '45,60,90,120' WHERE `tbl_configurations`.`conf_name` = 'conf_paid_lesson_duration'; 

ALTER TABLE `tbl_user_teach_languages` ADD `utl_booking_slot` INT NOT NULL DEFAULT '60' AFTER `utl_bulk_lesson_amount`; 
ALTER TABLE `tbl_user_teach_languages` DROP INDEX `language`, ADD UNIQUE `language` (`utl_us_user_id`, `utl_slanguage_id`, `utl_booking_slot`) USING BTREE; 

ALTER TABLE `tbl_teacher_offer_price` ADD `top_lesson_duration` INT NOT NULL DEFAULT '60' AFTER `top_bulk_lesson_price`; 

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.7.13.20210107' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION'; 