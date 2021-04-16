-- ALTER TABLE `tbl_teachers_weekly_schedule` ADD `twsch_weekyear` VARCHAR(10) NOT NULL AFTER `twsch_end_time`; 

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


INSERT INTO `tbl_attached_files` (`afile_type`, `afile_record_id`, `afile_record_subid`, `afile_lang_id`, `afile_screen`, `afile_physical_path`, `afile_name`, `afile_display_order`, `afile_downloaded_times`) VALUES
(44, 0, 0, 1, 0, '2020/12/1608545402-2000x9001jpg', '2000x900_1.jpg', 5, 0);


UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>\r\n		<tr>      \r\n   \r\n<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n\r\n		</tr>    \r\n        \r\n\r\n		<tr>      \r\n   \r\n<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n       \r\n	<!--\r\n	header start here\r\n	-->\r\n	          \r\n \r\n       \r\n	<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\"> \r\n           \r\n		<tbody>  \r\n<tr>     \r\n      \r\n	<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>     \r\n      \r\n	<td style=\"text-align:right;padding: 40px;\">         {social_media_icons}\r\n         </td> \r\n  \r\n</tr>          \r\n           \r\n		</tbody>       \r\n	</table>          \r\n       \r\n	<!--\r\n	header end here\r\n	-->\r\n	          \r\n          </td>    \r\n\r\n		</tr>    \r\n        \r\n       \r\n        \r\n\r\n		<tr>      \r\n   \r\n<td>       \r\n	<!--\r\n	page body start here\r\n	-->\r\n	          \r\n \r\n       \r\n	<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n     \r\n           \r\n		<tbody>           \r\n<tr>         \r\n	<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n	<tbody>\r\n		<tr>        \r\n<td style=\"padding:20px 0 60px;\">  <img src=\"icon-account.png\" alt=\"\" />  \r\n	<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>  \r\n	<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher {action} The Lesson!</h2>        </td>    \r\n		</tr>   \r\n\r\n	</tbody>\r\n</table>         </td>     \r\n</tr>     \r\n     \r\n<tr>         \r\n	<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n	<tbody>\r\n		<tr>        \r\n<td style=\"padding:60px 0 70px;\">   \r\n	<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has {action} the lesson ({lesson_name}).<br /><a href=\"{lesson_url}\">Click here</a> to view lesson.<br>\r\n	Reason:   <br />\r\n	{teacher_comment}</td>    \r\n		</tr>   \r\n\r\n	</tbody>\r\n</table>         </td>     \r\n</tr>     \r\n    \r\n   \r\n \r\n		</tbody>       \r\n	</table>          \r\n       \r\n	<!--\r\n	page body end here\r\n	-->\r\n	</td>    \r\n\r\n		</tr>    \r\n        \r\n        \r\n\r\n		<tr>      \r\n   \r\n<td>          \r\n       \r\n	<!--\r\n	page footer start here\r\n	-->\r\n	          \r\n \r\n       \r\n	<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \r\n           \r\n		<tbody>  \r\n<tr>      \r\n	<td style=\"height:30px;\"></td>  \r\n</tr> \r\n  \r\n<tr>     \r\n      \r\n	<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">         \r\n \r\n<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n     \r\n	<tbody>         \r\n		<tr>    \r\n\r\n<td style=\"padding:30px 0; font-size:20px; color:#000;\">        Need more help?<br />\r\n	     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>    </td>\r\n         \r\n		</tr>\r\n\r\n     \r\n	</tbody> \r\n</table>     </td> \r\n  \r\n</tr> \r\n  \r\n<tr>     \r\n      \r\n	<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">         \r\n \r\n<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n     \r\n	<tbody>         \r\n		<tr>    \r\n\r\n<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">        Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n	    <br />\r\n	    &copy; 2018, {website_name}. All Rights Reserved.\r\n   \r\n        </td>\r\n         \r\n		</tr>\r\n\r\n     \r\n	</tbody> \r\n</table>     </td> \r\n  \r\n</tr> \r\n     \r\n  \r\n<tr>     \r\n      \r\n	<td style=\"padding:0; height:50px;\"></td> \r\n  \r\n</tr> \r\n     \r\n \r\n           \r\n		</tbody>       \r\n	</table>          \r\n       \r\n	<!--\r\n	page footer end here\r\n	-->\r\n	          \r\n          </td>    \r\n\r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', `etpl_replacements` = '{lesson_id}\r\n{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n{lesson_url}' WHERE `tbl_email_templates`.`etpl_code` = 'teacher_reschedule_email' AND `tbl_email_templates`.`etpl_lang_id` = 1;


UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>\r\n		<tr>      \r\n   \r\n<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n\r\n		</tr>    \r\n        \r\n\r\n		<tr>      \r\n   \r\n<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n       \r\n	<!--\r\n	header start here\r\n	-->\r\n	          \r\n \r\n       \r\n	<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\"> \r\n           \r\n		<tbody>  \r\n<tr>     \r\n      \r\n	<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>     \r\n      \r\n	<td style=\"text-align:right;padding: 40px;\">         {social_media_icons}\r\n         </td> \r\n  \r\n</tr>          \r\n           \r\n		</tbody>       \r\n	</table>          \r\n       \r\n	<!--\r\n	header end here\r\n	-->\r\n	          \r\n          </td>    \r\n\r\n		</tr>    \r\n        \r\n       \r\n        \r\n\r\n		<tr>      \r\n   \r\n<td>       \r\n	<!--\r\n	page body start here\r\n	-->\r\n	          \r\n \r\n       \r\n	<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n     \r\n           \r\n		<tbody>           \r\n<tr>         \r\n	<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n	<tbody>\r\n		<tr>        \r\n<td style=\"padding:20px 0 60px;\">  <img src=\"icon-account.png\" alt=\"\" />  \r\n	<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>  \r\n	<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher Cancel The Lesson!</h2>        </td>    \r\n		</tr>   \r\n\r\n	</tbody>\r\n</table>         </td>     \r\n</tr>     \r\n     \r\n<tr>         \r\n	<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n	<tbody>\r\n		<tr>        \r\n<td style=\"padding:60px 0 70px;\">   \r\n	<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name} </h3>Teacher ({teacher_name}) has cancelled the lesson ({lesson_name}).<br /><a href="{lesson_url}">Click here</a> to view lesson.<br />\r\n	Reason:   <br />\r\n	{teacher_comment}</td>    \r\n		</tr>   \r\n\r\n	</tbody>\r\n</table>         </td>     \r\n</tr>     \r\n    \r\n   \r\n \r\n		</tbody>       \r\n	</table>          \r\n       \r\n	<!--\r\n	page body end here\r\n	-->\r\n	</td>    \r\n\r\n		</tr>    \r\n        \r\n        \r\n\r\n		<tr>      \r\n   \r\n<td>          \r\n       \r\n	<!--\r\n	page footer start here\r\n	-->\r\n	          \r\n \r\n       \r\n	<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \r\n           \r\n		<tbody>  \r\n<tr>      \r\n	<td style=\"height:30px;\"></td>  \r\n</tr> \r\n  \r\n<tr>     \r\n      \r\n	<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">         \r\n \r\n<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n     \r\n	<tbody>         \r\n		<tr>    \r\n\r\n<td style=\"padding:30px 0; font-size:20px; color:#000;\">        Need more help?<br />\r\n	     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>    </td>\r\n         \r\n		</tr>\r\n\r\n     \r\n	</tbody> \r\n</table>     </td> \r\n  \r\n</tr> \r\n  \r\n<tr>     \r\n      \r\n	<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">         \r\n \r\n<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n     \r\n	<tbody>         \r\n		<tr>    \r\n\r\n<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">        Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n	    <br />\r\n	    &copy; 2018, {website_name}. All Rights Reserved.\r\n   \r\n        </td>\r\n         \r\n		</tr>\r\n\r\n     \r\n	</tbody> \r\n</table>     </td> \r\n  \r\n</tr> \r\n     \r\n  \r\n<tr>     \r\n      \r\n	<td style=\"padding:0; height:50px;\"></td> \r\n  \r\n</tr> \r\n     \r\n \r\n           \r\n		</tbody>       \r\n	</table>          \r\n       \r\n	<!--\r\n	page footer end here\r\n	-->\r\n	          \r\n          </td>    \r\n\r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', `etpl_replacements` = '{lesson_id}\r\n{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n{lesson_url}' WHERE `tbl_email_templates`.`etpl_code` = 'teacher_cancelled_email' AND `tbl_email_templates`.`etpl_lang_id` = 1; 

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_Timezone_:_UTC_%s', 1, 'Timezone : UTC %s');
-- Bug-048752 multi lingual functionality issue

ALTER TABLE `tbl_user_teacher_requests` ADD `utrequest_language_id` INT(11) NOT NULL AFTER `utrequest_user_id`;

ALTER TABLE `tbl_user_teacher_requests` ADD `utrequest_language_code` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `utrequest_language_id`;


UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.7.12.20210206' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION'; 
REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_LESSON_{lesson-id}_CANCELED_BY_{user-full-name}_Comment:{comment}', 1, "Lesson {lesson-id} Canceled By {user-full-name} \n Comment: {comment}");

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.3.20210212' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.2.20210209' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION'; 

REPLACE INTO `tbl_payment_methods` (`pmethod_identifier`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`,`pmethod_type`) VALUES ('Twocheckout', 'Twocheckout', '1', 6, 1);

REPLACE INTO `tbl_payment_methods` (`pmethod_identifier`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`,`pmethod_type`) VALUES ('PayGate payweb-3', 'PayGate', '1', 7, 1);
-- Task-81501 Meta tags management

REPLACE INTO `tbl_payment_methods` (`pmethod_identifier`, `pmethod_type`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`) VALUES ('Paystack', 1, 'Paystack', 1, 10);
REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('HTMLAFTER_PWA_APP_SHORT_NAME', '1', 'Used when there is insufficient space to display the full name of the application. 15 characters or less.'),
('HTMLAFTER_PWA_Description', '1', 'A brief description of what your app is about.'),
('HTMLAFTER_PWA_App_Icon', '1', 'This will be the icon of your app when installed on the phone. Must be a PNG image exactly 192x192 in size.'),
('HTMLAFTER_PWA_Spash_Icon', '1', 'This icon will be displayed on the splash screen of your app on supported devices. Must be a PNG image exactly 512x512 in size.'),
('HTMLAFTER_PWA_Background_color', '1', 'Background color of the splash screen.'),
('HTMLAFTER_PWA_Theme_Color', '1', 'Theme color is used on supported devices to tint the UI elements of the browser and app switcher. When in doubt, use the same color as Background Color.'),
('HTMLAFTER_PWA_Start_Page', '1', 'Specify the page to load when the application is launched from a device.'),
('HTMLAFTER_PWA_Offline_Page', '1', 'Offline page is displayed when the device is offline and the requested page is not already cached.'),
('HTMLAFTER_PWA_orientation', '1', 'Set the orientation of your app on devices. When set to Follow Device Orientation your app will rotate as the device is rotated.'),
('HTMLAFTER_PWA_Display', '1', 'Display mode decides what browser UI is shown when your app is launched. Standalone is default.');


ALTER TABLE `tbl_issue_report_options` ADD `tissueopt_user_type` TINYINT NOT NULL;

ALTER TABLE `tbl_user_settings` ADD `us_site_lang` INT NOT NULL COMMENT 'the language which user preferred to view site content' AFTER `us_is_trial_lesson_enabled`;

ALTER TABLE `tbl_teachers_general_availability` ADD INDEX(`tgavl_user_id`); 

ALTER TABLE `tbl_scheduled_lesson_details` ADD INDEX(`sldetail_slesson_id`); 

ALTER TABLE `tbl_user_teach_languages` DROP INDEX `utl_single_lesson_amount`;

ALTER TABLE `tbl_user_teach_languages` ADD INDEX( `utl_slanguage_id`, `utl_single_lesson_amount`, `utl_bulk_lesson_amount`); 

ALTER TABLE `tbl_teaching_languages` ADD INDEX( `tlanguage_identifier`, `tlanguage_display_order`, `tlanguage_active`); 
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.9.0.20210223' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';





ALTER TABLE `tbl_teachers_weekly_schedule` ADD `twsch_weekyear` VARCHAR(10) NOT NULL AFTER `twsch_end_time`; 

UPDATE `tbl_teachers_weekly_schedule` SET `twsch_weekyear`=DATE_FORMAT(`twsch_date`,'%U-%Y') WHERE twsch_weekyear='';

UPDATE `tbl_configurations` SET `conf_val` = '15,30,45,60,90,120' WHERE `tbl_configurations`.`conf_name` = 'conf_paid_lesson_duration'; 

ALTER TABLE `tbl_user_teach_languages` ADD `utl_booking_slot` INT NOT NULL DEFAULT '60' AFTER `utl_bulk_lesson_amount`; 
ALTER TABLE `tbl_user_teach_languages` DROP INDEX `language`, ADD UNIQUE `language` (`utl_us_user_id`, `utl_slanguage_id`, `utl_booking_slot`) USING BTREE; 

ALTER TABLE `tbl_teacher_offer_price` ADD `top_lesson_duration` INT NOT NULL DEFAULT '60' AFTER `top_bulk_lesson_price`; 

ALTER TABLE `tbl_teacher_offer_price` DROP PRIMARY KEY, ADD PRIMARY KEY (`top_teacher_id`, `top_learner_id`, `top_lesson_duration`); 
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.4.20210219' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'RV-2.1.1' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';

REPLACE INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('new_withdrawal_request_mail_to_admin', 1, 'New Withdrawal Request to admin', 'Withdrawal request on {website_name}', '<table style=\"border-collapse:collapse;width:100%;\">\r\n	<tbody><tr>\r\n			<td><br />\r\n				</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>\r\n		<tr>      \r\n   \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n        \r\n		<tr>      \r\n   \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n       \r\n				<!--\r\n				header start here\r\n				-->\r\n				          \r\n \r\n       \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\"> \r\n           \r\n					<tbody>  \r\n						<tr>     \r\n      \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>     \r\n      \r\n							<td style=\"text-align:right;padding: 40px;\">         {social_media_icons}\r\n         </td> \r\n  \r\n						</tr>          \r\n           \r\n					</tbody>       \r\n				</table>          \r\n       \r\n				<!--\r\n				header end here\r\n				-->\r\n				          \r\n          </td>    \r\n		</tr>    \r\n        \r\n       \r\n        \r\n		<tr>      \r\n   \r\n			<td>       \r\n				<!--\r\n				page body start here\r\n				-->\r\n				          \r\n \r\n       \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n           \r\n					<tbody>           \r\n						<tr>         \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n									<tbody>\r\n										<tr>        \r\n											<td style=\"padding:20px 0 60px;\">  <img src=\"icon-account.png\" alt=\"\" />  \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>  \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Withdrawal Request</h2>        </td>    \r\n										</tr>   \r\n									</tbody>\r\n								</table>         </td>     \r\n						</tr>     \r\n     \r\n						<tr>         \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n								<div><br />\r\n									</div>\r\n								<div>\r\n									<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n										<tbody>\r\n											<tr>        \r\n												<td>   \r\n													<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear Admin</h3>\r\n													<div>{user_first_name} {user_last_name} has sent withdrawal request of {withdrawal_amount} at <a href=\"{website_url}\">{website_name}</a>. Please find the details below:</div>\r\n													<div><br />\r\n														</div>\r\n													<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n														<tbody>\r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">\r\n																	<div>&nbsp;</div>\r\n																	<div><span style=\"color: rgb(38, 50, 56); font-family: Roboto, Arial, sans-serif; font-size: 13px;\">Transaction&nbsp;</span>ID</div></td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_id}</td>\r\n															</tr>    \r\n          \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">User Name<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td> \r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_first_name} {user_last_name}</td>\r\n															</tr> \r\n          \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Request Date</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{request_date}</td>\r\n															</tr> \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Amount<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{withdrawal_amount}</td>\r\n															</tr>      \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Payout type</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{payout_type}</td>\r\n															</tr> \r\n                                                          \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Withdrawal Comment</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{withdrawal_comment}</td>\r\n															</tr>          \r\n														</tbody>\r\n													</table>        </td>    \r\n											</tr>   \r\n										</tbody>\r\n									</table></div>\r\n								<div>\r\n									<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" style=\"color: rgb(153, 153, 153); font-family: Arial; text-align: center;\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding: 20px 0px 60px;\">\r\n													<div>\r\n														<h2 style=\"font-family: Arial; text-align: center; background-color: rgb(255, 255, 255); margin: 8px 0px 0px; padding: 0px; color: rgb(232, 76, 61);\"><span style=\"font-weight: normal; font-size: 24px;\">Payout Details</span></h2></div>\r\n													<div><br />\r\n														</div>\r\n													<div>{other_details}</div></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></div>         </td>     \r\n						</tr>     \r\n    \r\n   \r\n \r\n					</tbody>       \r\n				</table>          \r\n       \r\n				<!--\r\n				page body end here\r\n				-->\r\n				</td>    \r\n		</tr>    \r\n		<tr>      \r\n   \r\n			<td>          \r\n       \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				          \r\n \r\n       \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \r\n           \r\n					<tbody>  \r\n						<tr>      \r\n							<td style=\"height:30px;\">\r\n								<div><span style=\"white-space:pre\"></span></div>\r\n								<div><span style=\"white-space:pre\"></span></div></td>  \r\n						</tr> \r\n  \r\n						<tr>     \r\n      \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">         \r\n \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n									<tbody>         \r\n										<tr>    \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">        Need more help?<br />\r\n												     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>    </td>         \r\n										</tr>     \r\n									</tbody> \r\n								</table>     </td> \r\n  \r\n						</tr> \r\n  \r\n						<tr>     \r\n      \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">  \r\n \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n									<tbody>         \r\n										<tr>    \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">        Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												    <br />\r\n												    &copy; 2018, {website_name}. All Rights Reserved.\r\n   \r\n        </td>         \r\n										</tr>     \r\n									</tbody> \r\n								</table>     </td> \r\n  \r\n						</tr> \r\n     \r\n  \r\n						<tr>     \r\n      \r\n							<td style=\"padding:0; height:50px;\"></td> \r\n  \r\n						</tr> \r\n     \r\n \r\n           \r\n					</tbody>       \r\n				</table>          \r\n       \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				          \r\n          </td>    \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_first_name} User First Name of the email receiver.<br />\r\n{user_last_name} User last Name<br />\r\n{payout_type} - Paypal payout or Bank <br>\r\n{withdrawal_comment} - withdrawal comment <br>{txn_id} - Transaction ID<br/>{withdrawal_amount} - Withdrawal Amount<br>{other_details} - Payout deatils in table view <br>{request_date} - withdrawal request data<br>{website_name} - Name of the website.\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);



REPLACE INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('new_withdrawal_request_mail_to_user', 1, 'New Withdrawal Request to user', 'Withdrawal request on {website_name}', '<table style=\"border-collapse:collapse;width:100%;\">\r\n	<tbody>\r\n		<tr>\r\n			<td><br />\r\n				</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>\r\n		<tr>      \r\n   \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n		</tr>    \r\n        \r\n		<tr>      \r\n   \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n       \r\n				<!--\r\n				header start here\r\n				-->\r\n				          \r\n \r\n       \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\"> \r\n           \r\n					<tbody>  \r\n						<tr>     \r\n      \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>     \r\n      \r\n							<td style=\"text-align:right;padding: 40px;\">         {social_media_icons}\r\n         </td> \r\n  \r\n						</tr>          \r\n           \r\n					</tbody>       \r\n				</table>          \r\n       \r\n				<!--\r\n				header end here\r\n				-->\r\n				          \r\n          </td>    \r\n		</tr>    \r\n        \r\n       \r\n        \r\n		<tr>      \r\n   \r\n			<td>       \r\n				<!--\r\n				page body start here\r\n				-->\r\n				          \r\n \r\n       \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n           \r\n					<tbody>           \r\n						<tr>         \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n									<tbody>\r\n										<tr>        \r\n											<td style=\"padding:20px 0 60px;\">  <img src=\"icon-account.png\" alt=\"\" />  \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>  \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Withdrawal Request</h2>        </td>    \r\n										</tr>   \r\n									</tbody>\r\n								</table>         </td>     \r\n						</tr>     \r\n     \r\n						<tr>         \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n								<div><br />\r\n									</div>\r\n								<div>\r\n									<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n    \r\n										<tbody>\r\n											<tr>        \r\n												<td>   \r\n													<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_first_name} {user_last_name}</h3>\r\n													<div>You have sent withdrawal request of {withdrawal_amount} at <a href=\"{website_url}\">{website_name}</a>. Please find the details below:</div>\r\n													<div><br />\r\n														</div>\r\n													<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n														<tbody>\r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">\r\n																	<div>&nbsp;</div>\r\n																	<div><span style=\"color: rgb(38, 50, 56); font-family: Roboto, Arial, sans-serif; font-size: 13px;\">Transaction&nbsp;</span>ID</div></td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{txn_id}</td>\r\n															</tr>    \r\n          \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Request Date</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{request_date}</td>\r\n															</tr> \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Amount<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{withdrawal_amount}</td>\r\n															</tr>      \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Payout type</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{payout_type}</td>\r\n															</tr> \r\n                                                          \r\n															<tr>\r\n																<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Withdrawal Comment</td>\r\n																<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{withdrawal_comment}</td>\r\n															</tr> \r\n          \r\n														</tbody>\r\n													</table>        </td>    \r\n											</tr>   \r\n										</tbody>\r\n									</table></div>\r\n								<div>\r\n									<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" style=\"color: rgb(153, 153, 153); font-family: Arial; text-align: center;\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding: 20px 0px 60px;\">\r\n													<div>\r\n														<h2 style=\"font-family: Arial; text-align: center; background-color: rgb(255, 255, 255); margin: 8px 0px 0px; padding: 0px; color: rgb(232, 76, 61);\"><span style=\"font-weight: normal; font-size: 24px;\">Payout Details</span></h2></div>\r\n													<div><br />\r\n														</div>\r\n													<div>{other_details}</div></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></div>         </td>     \r\n						</tr>     \r\n    \r\n   \r\n \r\n					</tbody>       \r\n				</table>          \r\n       \r\n				<!--\r\n				page body end here\r\n				-->\r\n				</td>    \r\n		</tr>    \r\n		<tr>      \r\n   \r\n			<td>          \r\n       \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				          \r\n \r\n       \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \r\n           \r\n					<tbody>  \r\n						<tr>      \r\n							<td style=\"height:30px;\">\r\n								<div><span style=\"white-space:pre\"></span></div>\r\n								<div><span style=\"white-space:pre\"></span></div></td>  \r\n						</tr> \r\n  \r\n						<tr>     \r\n      \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">         \r\n \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n									<tbody>         \r\n										<tr>    \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">        Need more help?<br />\r\n												     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>    </td>         \r\n										</tr>     \r\n									</tbody> \r\n								</table>     </td> \r\n  \r\n						</tr> \r\n  \r\n						<tr>     \r\n      \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">  \r\n \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n									<tbody>         \r\n										<tr>    \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">        Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												    <br />\r\n												    &copy; 2018, {website_name}. All Rights Reserved.\r\n   \r\n        </td>         \r\n										</tr>     \r\n									</tbody> \r\n								</table>     </td> \r\n  \r\n						</tr> \r\n     \r\n  \r\n						<tr>     \r\n      \r\n							<td style=\"padding:0; height:50px;\"></td> \r\n  \r\n						</tr> \r\n     \r\n \r\n           \r\n					</tbody>       \r\n				</table>          \r\n       \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				          \r\n          </td>    \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_first_name} First Name of the email receiver.<br />\r\n{user_last_name} Last Name of the email receiver.<br />\r\n{payout_type} - Paypal payout or Bank <br>{txn_id} - Transaction ID<br/>{withdrawal_amount} - Withdrawal Amount<br>{other_details} - Payout deatils in table view <br>{request_date} - withdrawal request data<br>\r\n{withdrawal_comment} - withdrawal comment <br>{website_name} - Name of the website.\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.10.0.20210304' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';


CREATE TABLE `tbl_lesson_status_log` (
  `lesstslog_id` int(11) NOT NULL,
  `lesstslog_slesson_id` int(11) NOT NULL,
  `lesstslog_prev_status` tinyint(1) NOT NULL COMMENT 'defined in model',
  `lesstslog_current_status` int(11) NOT NULL,
  `lesstslog_prev_start_date` date NOT NULL,
  `lesstslog_prev_start_time` time NOT NULL,
  `lesstslog_prev_end_date` date NOT NULL,
  `lesstslog_prev_end_time` time NOT NULL,
  `lesstslog_updated_by_user_id` int(11) NOT NULL,
  `lesstslog_updated_by_user_type` int(11) NOT NULL,
  `lesstslog_comment` text CHARACTER SET utf8 NOT NULL,
  `lesstslog_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tbl_lesson_status_log`
  ADD PRIMARY KEY (`lesstslog_id`);

ALTER TABLE `tbl_lesson_status_log`
  MODIFY `lesstslog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;


ALTER TABLE `tbl_lesson_status_log` ADD `lesstslog_sldetail_id` INT(11) NOT NULL;

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_Sr_no.', 1, 'S/N')
,('LBL_Need_to_be_scheduled', 1, 'Unscheduled')
,('LBL_ST', 1, 'ST')
,('LBL_ET', 1, 'ET')
,('LBL_O-ID', 1, 'O-ID')
;
REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_CLASS_ENDS_IN', 1, 'class <span>ends</span> In');

ALTER TABLE `tbl_meta_tags` ADD `meta_type` TINYINT(1) NOT NULL AFTER `meta_action`;

ALTER TABLE `tbl_meta_tags` CHANGE `meta_record_id` `meta_record_id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

INSERT INTO `tbl_meta_tags` (`meta_id`, `meta_controller`, `meta_action`, `meta_type`, `meta_record_id`, `meta_subrecord_id`, `meta_identifier`, `meta_default`, `meta_advanced`) VALUES (1, '', '', '-1', '0', '0', 'Yo!Coach Live Demo', '0', '0');

INSERT INTO `tbl_meta_tags_lang` (`metalang_meta_id`, `metalang_lang_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_other_meta_tags`) VALUES ('1', '1', 'Yo!Coach Live Demo - Readymade Solution To Build Online Learning & Consultation Marketplace | Yo!Coach', '', 'Check the working and features of Yo!Coach with live demo setup. A FATbit Technologies solution to build an online learning and consultation platform like Verbling , Italki, Preply, and Cambly. ', '');

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.0.20210317' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION'; 

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('htmlAfterField_booking_before_text', 1, 'Only applicable for single lesson class.');

CREATE TABLE `tbl_group_classes_lang` (
  `grpclslang_grpcls_id` int(11) NOT NULL,
  `grpclslang_lang_id` int(11) NOT NULL,
  `grpclslang_grpcls_title` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grpclslang_grpcls_description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
ALTER TABLE `tbl_group_classes_lang`
  ADD PRIMARY KEY (`grpclslang_grpcls_id`,`grpclslang_lang_id`);

DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Calender';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_Invalid_Username', '1', 'Username accepts only letters,numbers,(-),(_)'),
 ('htmlAfterField_LESSON_DURATIONS_TEXT', '1', 'Please notify your tutors in advance before you change the lesson duration, since this can impact the tutor profile listing on the frontend.');

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.1.20210326' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

ALTER TABLE `tbl_teachers_general_availability` ADD `tgavl_end_date` DATE NOT NULL AFTER `tgavl_date`;

UPDATE `tbl_teachers_general_availability` SET `tgavl_end_date` =( CASE WHEN `tgavl_start_time` >= `tgavl_end_time` THEN DATE_ADD(`tgavl_date`, INTERVAL 1 DAY) ELSE `tgavl_date` END) WHERE `tgavl_end_date` = '0000-00-00';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_Invalid_Username', '1', 'Username accepts only letters,numbers,(-),(_) and length between 3 to 35');

-- bug-051797-Delete email templates for message exchange

DELETE FROM `tbl_email_templates` WHERE `tbl_email_templates`.`etpl_code` = 'learner_message_to_teacher_email';

DELETE FROM `tbl_email_templates` WHERE `tbl_email_templates`.`etpl_code` = 'teacher_message_to_learner_email';

DELETE FROM `tbl_email_templates` WHERE `tbl_email_templates`.`etpl_code` = 'blog_comment_status_changed';

DELETE FROM `tbl_email_templates` WHERE `tbl_email_templates`.`etpl_code` = 'giftcard_buyer';

DELETE FROM `tbl_email_templates` WHERE `tbl_email_templates`.`etpl_code` = 'new_teacher_approval_admin';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.2.20210331' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.3.20210331' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.4.20210402' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

ALTER TABLE `tbl_users` ADD `user_phone_code` VARCHAR(6) NOT NULL AFTER `user_last_name`;
ALTER TABLE `tbl_user_teacher_request_values` ADD `utrvalue_user_phone_code` VARCHAR(6) NOT NULL AFTER `utrvalue_user_gender`;
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.5.20210403' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_PHONE_NO_VALIDATION_MSG', 1, 'Please add vaild phone no and length between 4 to 16'),
('LBL_Note:_Enter_Number_of_lessons_in_a_package', 1, 'Note: Enter Number Of Lessons In A Package'),
('LBL_Note:_Enter_Number_of_lessons_in_a_package', 2, 'ملاحظة: أدخل عدد من الدروس في حزمة)');

DELETE FROM `tbl_language_labels` WHERE  `label_key` = "LBL_You_are_not_cancelled_the_order";
DELETE FROM `tbl_language_labels` WHERE  `label_key` = "LBL_You_are_not_cancelled_the_order_because_some_lesson_are_scheduled";
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.6.20210405' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

RENAME TABLE `tbl_url_rewrites` TO `tbl_url_rewrite`;

ALTER TABLE `tbl_url_rewrites` ADD `urlrewrite_http_resp_code` VARCHAR(10) NOT NULL AFTER `urlrewrite_lang_id`;

                  


