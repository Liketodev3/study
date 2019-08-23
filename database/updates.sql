CREATE TABLE `tbl_user_teach_languages` (
  `utl_id` int(11) NOT NULL,
  `utl_us_user_id` int(11) NOT NULL,
  `utl_slanguage_id` int(11) NOT NULL,
  `utl_single_lesson_amount` decimal(10,2) NOT NULL,
  `utl_bulk_lesson_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `tbl_user_teach_languages`
--
ALTER TABLE `tbl_user_teach_languages`
  ADD PRIMARY KEY (`utl_id`),
  ADD UNIQUE KEY `language` (`utl_us_user_id`,`utl_slanguage_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user_teach_languages`
--
ALTER TABLE `tbl_user_teach_languages`
  MODIFY `utl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

ALTER TABLE `tbl_scheduled_lessons` ADD `slesson_slanguage_id` INT NOT NULL AFTER `slesson_learner_id`;
ALTER TABLE `tbl_order_products` ADD `op_slanguage_id` INT NOT NULL AFTER `op_teacher_id`;

/* 28-June-2019 RV */

INSERT INTO `tbl_navigations` (`nav_id`, `nav_identifier`, `nav_active`, `nav_is_multilevel`, `nav_type`, `nav_deleted`) VALUES
(5, 'Footer Bottom', 1, 0, 5, 0);

/* 08-July-2019 RV */

ALTER TABLE `tbl_user_teacher_request_values` CHANGE `utrvalue_user_teach_slanguage_id` `utrvalue_user_teach_slanguage_id` VARCHAR(255) NOT NULL;


/* 02-08-2019 tbl_teaching_languages */

CREATE TABLE `tbl_teaching_languages` (
  `tlanguage_id` int(11) NOT NULL,
  `tlanguage_code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tlanguage_identifier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tlanguage_flag` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tlanguage_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='same table used for teaching languages and spoken languages';

--
-- Dumping data for table `tbl_teaching_languages`
--

INSERT INTO `tbl_teaching_languages` (`tlanguage_id`, `tlanguage_code`, `tlanguage_identifier`, `tlanguage_flag`, `tlanguage_active`) VALUES
(1, 'EN', 'English', 'gb.png', 1),
(2, 'FR', 'French', 'fr.png', 0),
(3, 'DE', 'German', 'de.png', 1),
(4, 'IT', 'Italian', 'it.png', 1),
(5, 'Arab', 'Arabic', 'ddf', 1),
(7, 'Sp', 'Spanish', 'spain-flag-icon-256.pgn', 1),
(8, 'Ru', 'Russian', 'Ru.png', 1),
(9, 'Ro', 'Romanian', 'Ro.png', 1),
(10, 'MaCh', 'Mandarin(Chinese)', 'Mc.pgn', 1),
(11, 'Prt', 'Portuguese', 'prt.pgn', 1),
(12, 'Geog', 'Geography', '', 1),
(16, 'Code', 'Language', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_teaching_languages`
--
ALTER TABLE `tbl_teaching_languages`
  ADD PRIMARY KEY (`tlanguage_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_teaching_languages`
--
ALTER TABLE `tbl_teaching_languages`
  MODIFY `tlanguage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
  
  --
-- Table structure for table `tbl_teaching_languages_lang`
--

CREATE TABLE `tbl_teaching_languages_lang` (
  `tlanguagelang_tlanguage_id` int(11) NOT NULL,
  `tlanguagelang_lang_id` int(11) NOT NULL,
  `tlanguage_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_teaching_languages_lang`
--

INSERT INTO `tbl_teaching_languages_lang` (`tlanguagelang_tlanguage_id`, `tlanguagelang_lang_id`, `tlanguage_name`) VALUES
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
(11, 1, 'Portuguese'),
(12, 1, 'Geography'),
(16, 1, 'TestTeaching Language'),
(16, 2, 'Test Teaching Arabic');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_teaching_languages_lang`
--
ALTER TABLE `tbl_teaching_languages_lang`
  ADD PRIMARY KEY (`tlanguagelang_tlanguage_id`,`tlanguagelang_lang_id`);
  
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('user_email_change_verification', '1', 'User Email Change Verification Link', 'Email Verification at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Account Verification!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>                                  Please Verify Your Email to Change Email on&nbsp;&nbsp;<a href=\"{website_url}\">{website_name}</a>.. \r\nJust follow this link below to confirm your email address.\r\n                                  <br />\r\n												<br />\r\n												                                  <a href=\"{verification_url}\" style=\"background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Verify Account</a>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_fist_name} First Name of the email receiver.<br> {user_last_name} Last Name of the email receiver.<br> {user_full_name} Name of the email receiver.<br> {website_name} Name of our website<br> {verification_url} Url to verify email<br> {social_media_icons} <br> {contact_us_url} <br>', '1');

CREATE TABLE `tbl_user_email_change_request` (
  `uecreq_id` int(11) NOT NULL,
  `uecreq_user_id` int(255) NOT NULL,
  `uecreq_email` varchar(255) NOT NULL,
  `uecreq_status` int(11) NOT NULL,
  `uecreq_created` datetime NOT NULL,
  `uecreq_updated` datetime NOT NULL,
  `uecreq_expire` datetime NOT NULL,
  `uecreq_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for table `tbl_user_email_change_request`
--
ALTER TABLE `tbl_user_email_change_request`
  ADD PRIMARY KEY (`uecreq_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user_email_change_request`
--
ALTER TABLE `tbl_user_email_change_request`
  MODIFY `uecreq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
  