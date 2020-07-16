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

ALTER TABLE `tbl_user_settings` ADD `us_booking_before` INT(20) NULL DEFAULT NULL AFTER `us_teach_slanguage_id`;
ALTER TABLE `tbl_scheduled_lessons` ADD `slesson_end_date` DATE NOT NULL AFTER `slesson_date`;
ALTER TABLE `tbl_teachers_weekly_schedule` ADD `twsch_end_date` DATE NOT NULL AFTER `twsch_date`;
ALTER TABLE `tbl_teachers_general_availability` ADD `tgavl_date` DATE NULL DEFAULT NULL AFTER `tgavl_end_time`;

ALTER TABLE `tbl_teaching_languages` ADD `tlanguage_display_order` INT(11) NOT NULL AFTER `tlanguage_active`;
ALTER TABLE `tbl_spoken_languages` ADD `slanguage_display_order` INT(11) NOT NULL AFTER `slanguage_active`;

CREATE TABLE `tbl_faq_categories` (
  `faqcat_id` int(11) NOT NULL,
  `faqcat_identifier` varchar(150) NOT NULL,
  `faqcat_active` tinyint(1) NOT NULL,
  `faqcat_type` tinyint(4) NOT NULL,
  `faqcat_deleted` tinyint(1) NOT NULL,
  `faqcat_display_order` int(11) NOT NULL,
  `faqcat_featured` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_faq_categories`
ADD PRIMARY KEY (`faqcat_id`);

ALTER TABLE `tbl_faq_categories`
MODIFY `faqcat_id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `tbl_faq_categories_lang` (
  `faqcatlang_faqcat_id` int(11) NOT NULL,
  `faqcatlang_lang_id` int(11) NOT NULL,
  `faqcat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_faq_categories_lang`
ADD PRIMARY KEY (`faqcatlang_faqcat_id`,`faqcatlang_lang_id`);


--------
--------

CREATE TABLE `tbl_cron_schedules` (
  `cron_id` int(11) NOT NULL,
  `cron_name` varchar(255) NOT NULL,
  `cron_command` varchar(255) NOT NULL,
  `cron_duration` int(11) NOT NULL COMMENT 'Minutes',
  `cron_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_cron_schedules`
  ADD PRIMARY KEY (`cron_id`);
 ALTER TABLE `tbl_cron_schedules`
  MODIFY `cron_id` int(11) NOT NULL AUTO_INCREMENT;

----------

CREATE TABLE `tbl_cron_log` (
  `cronlog_id` int(11) NOT NULL,
  `cronlog_cron_id` int(11) NOT NULL,
  `cronlog_started_at` datetime NOT NULL,
  `cronlog_ended_at` datetime NOT NULL,
  `cronlog_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cron_log`
--
ALTER TABLE `tbl_cron_log`
  ADD PRIMARY KEY (`cronlog_id`),
  ADD KEY `cronlog_cron_id` (`cronlog_cron_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cron_log`
--
ALTER TABLE `tbl_cron_log`
  MODIFY `cronlog_id` int(11) NOT NULL AUTO_INCREMENT;


INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES
(1, 'lesson one day reminder', 'LessonReminder/sendLessonReminder/1', 1440, 1),
(2, 'lesson 30 mints reminder', 'LessonReminder/sendLessonReminder/2', 1, 1);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('coming_up_lesson_reminder', '1', 'Scheduled lesson(s) Reminder', 'Lesson Reminder at {website_name}', '<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f5f5" style="font-family:Arial; color:#333; line-height:26px;"> <tbody> <tr> <td style="background:#e84c3d;padding:30px 0;"></td></tr><tr> <td style="background:#e84c3d;padding:0 0 0;"><!--header start here--> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;border-bottom: 1px solid #eee;"> <tbody> <tr> <td style="padding:20px 40px;"><a href="#" style="display: block;">{Company_Logo}</a></td><td style="text-align:right;padding: 40px;">{social_media_icons}</td></tr></tbody> </table><!--header end here--> </td></tr><tr> <td><!--page body start here--> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"> <tbody> <tr> <td style="background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;"> <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"> <tbody><tr> <td style="padding:20px 0 60px;"> <img src="icon-account.png" alt=""/> <h2 style="margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;">Scheduled Lesson Reminder!</h2> </td></tr></tbody></table> </td></tr><tr> <td style="background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; "> <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"> <tbody><tr> <td style="padding:60px 0 70px;"> <h3 style="margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;">Dear{user_full_name}</h3> You have scheduled lesson(s) on &nbsp;&nbsp;<a href="{website_url}">{website_name}</a><br/></td></tr><tr></tr></tbody></table>{lessons_details}</td></tr></tbody></table> </td></tr></tbody> </table> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"> <tbody> <tr> <td style="height:30px;"></td></tr><tr> <td style="background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;"> <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"> <tbody> <tr> <td style="padding:30px 0; font-size:20px; color:#000;"> Need more help?<br/> <a href="{contact_us_url}" style="color:#e84c3d;">We‘re here, ready to talk</a> </td></tr></tbody> </table> </td></tr><tr> <td style="padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;"> <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"> <tbody> <tr> <td style="padding:20px 0 30px; font-size:13px; color:#999;"> Be sure to add <a href="#" style="color: #e84c3d">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br/> <br/> &copy; 2018,{website_name}. All Rights Reserved. </td></tr></tbody> </table> </td></tr><tr> <td style="padding:0; height:50px;"></td></tr></tbody> </table><!--page footer end here-->', '{user_fist_name} First Name of the email receiver.<br> {user_last_name} Last Name of the email receiver.<br> {user_full_name} Name of the email receiver.<br> {website_name} Name of our website<br> {social_media_icons} <br> {contact_us_url} <br>', '1');


ALTER TABLE `tbl_scheduled_lessons`
ADD `slesson_reminder_one` INT(11) NOT NULL AFTER `slesson_added_on`,
ADD `slesson_reminder_two` INT(11) NOT NULL AFTER `slesson_reminder_one`;


ALTER TABLE `tbl_users` ADD `user_url_name` VARCHAR(150) NULL DEFAULT NULL AFTER `user_id`;
INSERT INTO `tbl_url_rewrites` (`urlrewrite_id`, `urlrewrite_original`, `urlrewrite_custom`) VALUES (NULL, 'teachers/view', 'teachers/urlparameter');


INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('new_message_arrived', '1', 'New Message Arrived', 'New Message Arrived at {website_name}', '<div style="margin:0; padding:0;background: #ecf0f1;"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ecf0f1" style="font-family:Arial; color:#333; line-height:26px;"><tbody><tr><td style="background:#ff3a59;padding:30px 0 10px;"><!--header start here--> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td><a href="{website_url}">{Company_Logo}</a></td><td style="text-align:right;">{social_media_icons}</td></tr></tbody></table><!--header end here--> </td></tr><tr><td style="background:#ff3a59;"><!--page title start here--> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="background:#fff;padding:20px 0 10px; text-align:center;"><h4 style="font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;"></h4><h2 style="margin:0; font-size:34px; padding:0;">{action}!</h2></td></tr></tbody></table><!--page title end here--> </td></tr><tr><td><!--page body start here--> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:20px 0 30px;"><strong style="font-size:18px;color:#333;">Dear{to_user_name}</strong><br/><a href="{website_url}">{website_name}</a>.</td></tr><tr><td style="padding:20px 0 30px;">{from_user_name}has sent you a message:<br/></td></tr><tr><td style="padding:20px 0 30px;">Message: <br/>{message}</td></tr><!--section footer--> <tr><td style="padding:30px 0;border-top:1px solid #ddd; ">Get in touch if you have any questions regarding our Services.<br/>Feel free to contact us 24/7. We are here to help.<br/><br/>All the best,<br/>The{website_name}Team<br/></td></tr><!--section footer--> </tbody></table></td></tr></tbody></table><!--page body end here--> </td></tr><tr><td><!--page footer start here--> <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="height:30px;"></td></tr><tr><td style="background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:30px 0; font-size:20px; color:#000;">Need more help?<br/> <a href="{contact_us_url}" style="color:#ff3a59;">We are here, ready to talk</a></td></tr></tbody></table></td></tr><tr><td style="padding:0; color:#999;vertical-align:top; line-height:20px;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:20px 0 30px; text-align:center; font-size:13px; color:#999;"><br/><br/>{website_name}Inc.<!--if these emails get annoying, please feel free to <a href="#" style="text-decoration:underline; color:#666;">unsubscribe</a>.--></td></tr></tbody></table></td></tr><tr><td style="padding:0; height:50px;"></td></tr></tbody></table><!--page footer end here--> </td></tr></tbody></table></div>', '{to_user_name}<br />{from_user_name}<br />{message}<br />{action}', '1');



INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('teacher_issue_resolved_email', '1', 'Teacher Issue Resolved Email', 'Teacher Issue Resolved Email at {website_name}', '<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f5f5" style="font-family:Arial; color:#333; line-height:26px;"><tbody><tr><td style="background:#e84c3d;padding:30px 0;"></td></tr><tr><td style="background:#e84c3d;padding:0 0 0;"><!--header start here--><table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;border-bottom: 1px solid #eee;"><tbody><tr><td style="padding:20px 40px;"><a href="#" style="display: block;">{Company_Logo}</a></td><td style="text-align:right;padding: 40px;">{social_media_icons}</td></tr></tbody></table><!--header end here--></td></tr><tr><td><!--page body start here--><table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td style="padding:20px 0 60px;"><img src="icon-account.png" alt=""/><h5 style="margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;"></h5><h2 style="margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;">Teacher Resolved The Issue</h2></td></tr></tbody></table></td></tr><tr><td style="background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; "><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td style="padding:60px 0 70px;"><h3 style="margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;">Dear{learner_name}</h3><p style="line-height: 1.5;"> Teacher ({teacher_name}) has resolved the issue with ({lesson_name}) which is scheduled on{lesson_date}{lesson_start_time}-{lesson_end_time}</p><p style="line-height: 1.5; margin-bottom: 0px;"><strong>Teacher Comment:</strong></p><p style="line-height: 1.5; margin-top: 0px;">{teacher_comment}</p><p><strong>Issue Reason By Teacher:</strong><br/>{teacher_issue_reason}</p><p><strong>Resolve Type : </strong>{issue_resolve_type}</p></td></tr></tbody></table></td></tr></tbody></table><!--page body end here--></td></tr><tr><td><!--page footer start here--><table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="height:30px;"></td></tr><tr><td style="background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:30px 0; font-size:20px; color:#000;"> Need more help?<br/><a href="{contact_us_url}" style="color:#e84c3d;">We‘re here, ready to talk</a></td></tr></tbody></table></td></tr><tr><td style="padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:20px 0 30px; font-size:13px; color:#999;"> Be sure to add <a href="#" style="color: #e84c3d">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br/><br/> &copy; 2018,{website_name}. All Rights Reserved. </td></tr></tbody></table></td></tr><tr><td style="padding:0; height:50px;"></td></tr></tbody></table><!--page footer end here--></td></tr></tbody></table>', '{learner_name}<br />{teacher_name}<br />{lesson_name}<br />{lesson_issue_reason}<br />{teacher_issue_reason}<br />{learner_comment}<br />{teacher_comment}<br />{lesson_date}<br />{lesson_start_time}<br />{lesson_end_time}<br />{issue_resolve_type}', '1');


CREATE TABLE `tbl_issue_report_options` (
  `tissueopt_id` int(255) NOT NULL,
  `tissueopt_identifier` varchar(255) NOT NULL,
  `tissueopt_display_order` int(11) NOT NULL,
  `tissueopt_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


ALTER TABLE `tbl_issue_report_options`
  ADD PRIMARY KEY (`tissueopt_id`);

ALTER TABLE `tbl_issue_report_options`
  MODIFY `tissueopt_id` int(255) NOT NULL AUTO_INCREMENT;


CREATE TABLE `tbl_issue_report_options_lang` (
  `tissueoptlang_tissueopt_id` int(11) NOT NULL,
  `tissueoptlang_lang_id` int(11) NOT NULL,
  `tissueoptlang_title` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

ALTER TABLE `tbl_issue_report_options_lang`
  ADD PRIMARY KEY (`tissueoptlang_tissueopt_id`,`tissueoptlang_lang_id`);


ALTER TABLE `tbl_issue_report_options_lang`
  MODIFY `tissueoptlang_tissueopt_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tbl_issues_reported` ADD `issrep_is_for_admin` INT(11) NOT NULL DEFAULT '0' AFTER `issrep_id`;
ALTER TABLE `tbl_issues_reported` ADD `issrep_issues_to_report` VARCHAR(255) NOT NULL AFTER `issrep_slesson_id`;
ALTER TABLE `tbl_issues_reported` ADD `issrep_issues_resolve` VARCHAR(255) NOT NULL AFTER `issrep_status`;
ALTER TABLE `tbl_issues_reported` ADD `issrep_issues_resolve_type` INT(11) NOT NULL AFTER `issrep_issues_resolve`;
ALTER TABLE `tbl_issues_reported` ADD `issrep_resolve_comments` LONGTEXT NOT NULL AFTER `issrep_issues_resolve_type`;

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('admin_new_issue_reported_email', '1', 'Admin Issue Reported Email', 'Admin Issue Reported Email at {website_name}', '<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f5f5" style="font-family:Arial; color:#333; line-height:26px;"><tbody><tr><td style="background:#e84c3d;padding:30px 0;"></td></tr><tr><td style="background:#e84c3d;padding:0 0 0;"><!--header start here--><table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;border-bottom: 1px solid #eee;"><tbody><tr><td style="padding:20px 40px;"><a href="#" style="display: block;">{Company_Logo}</a></td><td style="text-align:right;padding: 40px;">{social_media_icons}</td></tr></tbody></table><!--header end here--></td></tr><tr><td><!--page body start here--><table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td style="padding:20px 0 60px;"><img src="icon-account.png" alt=""/><h5 style="margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;"></h5><h2 style="margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;">New Issue Reported</h2></td></tr></tbody></table></td></tr><tr><td style="background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; "><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td style="padding:60px 0 70px;"><h3 style="margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;">Dear Admin </h3><p style="line-height: 1.5;"> Learner ({lerner_name}) has posted an issue with ({lesson_name}) which is scheduled on{lesson_date}{lesson_start_time}-{lesson_end_time}</p></td></tr></tbody></table></td></tr></tbody></table><!--page body end here--></td></tr><tr><td><!--page footer start here--><table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="height:30px;"></td></tr><tr><td style="background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:30px 0; font-size:20px; color:#000;"> Need more help?<br/><a href="{contact_us_url}" style="color:#e84c3d;">We‘re here, ready to talk</a></td></tr></tbody></table></td></tr><tr><td style="padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td style="padding:20px 0 30px; font-size:13px; color:#999;"> Be sure to add <a href="#" style="color: #e84c3d">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br/><br/> &copy; 2018,{website_name}. All Rights Reserved. </td></tr></tbody></table></td></tr><tr><td style="padding:0; height:50px;"></td></tr></tbody></table><!--page footer end here--></td></tr></tbody></table>', '{learner_name}<br /> {teacher_name}<br /> {lesson_name}<br /> {teacher_comment}<br /> {lesson_date}<br /> {lesson_start_time}<br /> {lesson_end_time}<br /> {action}<br />{escalated_by}', '1');

/* 25-10-2019 */

ALTER TABLE `tbl_issues_reported` ADD `issrep_escalated_by` INT(11) NOT NULL AFTER `issrep_updated_on`;

/* 29-04-2020  for teach listing optimization*/
ALTER TABLE `tbl_user_credentials` ADD INDEX( `credential_active`, `credential_verified`);
ALTER TABLE `tbl_users` ADD INDEX(`user_country_id`, `user_is_teacher`);
ALTER TABLE `tbl_teaching_languages_lang` ADD INDEX( `tlanguage_name`);
ALTER TABLE `tbl_teaching_languages` ADD INDEX( `tlanguage_identifier`);
ALTER TABLE `tbl_user_to_spoken_languages` ADD INDEX( `utsl_slanguage_id`);
ALTER TABLE `tbl_user_teach_languages` ADD INDEX( `utl_single_lesson_amount`, `utl_bulk_lesson_amount`);
ALTER TABLE `tbl_scheduled_lessons` ADD INDEX( `slesson_teacher_id`);
ALTER TABLE `tbl_scheduled_lessons` ADD INDEX( `slesson_status`);
ALTER TABLE `tbl_teacher_lesson_reviews` ADD INDEX( `tlreview_teacher_user_id`, `tlreview_status`);

/* 30-04-2020 cookie message text */
UPDATE `tbl_configurations` SET `conf_val` = 'Cookies Policy Text Will go here...' WHERE `tbl_configurations`.`conf_name` like '%CONF_COOKIES_TEXT%';
UPDATE `tbl_configurations` SET `conf_val` = '3' WHERE `tbl_configurations`.`conf_name` = 'CONF_COOKIES_BUTTON_LINK';
--  also update the database COLLATE
ALTER TABLE  tbl_thread_messages CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `tbl_thread_messages` CHANGE `message_text` `message_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- bug 036840
UPDATE `tbl_language_labels` SET `label_caption` = 'Single Lesson Rate (USD) (Each lesson is one hour)' WHERE `tbl_language_labels`.`label_key` LIKE 'M_Single_Lesson_Rate';
UPDATE `tbl_language_labels` SET `label_caption` = 'Single Lesson Rate When Purchase in Bulk (USD)' WHERE `tbl_language_labels`.`label_key` LIKE 'M_Bulk_Lesson_Rate';

-- bug 036971 11-may-2020
ALTER TABLE `tbl_spoken_languages` ADD `slanguage_display_order` INT NOT NULL AFTER `slanguage_flag`;
ALTER TABLE `tbl_teaching_languages` ADD `tlanguage_display_order` INT NOT NULL AFTER `tlanguage_flag`;

ALTER TABLE `tbl_flashcards` CHANGE `flashcard_notes` `flashcard_notes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

UPDATE `tbl_configurations` SET `conf_val` = 'UTC' WHERE `tbl_configurations`.`conf_name` = 'CONF_TIMEZONE';


-- bug 037666  04-june-2020
ALTER TABLE `tbl_configurations` CHANGE `conf_val` `conf_val` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

-- #037844 -8-june-2020

UPDATE `tbl_configurations` SET `conf_val` = 'Y-m-d' WHERE `tbl_configurations`.`conf_name` = 'CONF_DATEPICKER_FORMAT';
-- #037966 8-june-2020
ALTER TABLE `tbl_user_teacher_requests` DROP INDEX `ututrequest_user_id`;

ALTER TABLE `tbl_user_teacher_requests` ADD `utrequest_status_change_date`  DATETIME NOT NULL AFTER `utrequest_status`;

-- #036446 25-june-2020
UPDATE `tbl_configurations` SET `conf_val` = 'H:i:s' WHERE `tbl_configurations`.`conf_name` = 'CONF_DATE_FORMAT_TIME';

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_YOCOACH_VERSION', 'V1.0', 0);

INSERT INTO `tbl_payment_methods` (`pmethod_id`, `pmethod_identifier`, `pmethod_code`, `pmethod_active`, `pmethod_display_order`) VALUES (NULL, 'Stripe', 'stripe', '1', '1'); 


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
  `grpcls_added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `grpcls_status` int(11) NOT NULL,
  `grpcls_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_group_classes`
--
ALTER TABLE `tbl_group_classes`
  ADD PRIMARY KEY (`grpcls_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_group_classes`
--
ALTER TABLE `tbl_group_classes`
  MODIFY `grpcls_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tbl_scheduled_lessons`
  DROP `slesson_order_id`,
  DROP `slesson_learner_id`,
  DROP `slesson_learner_join_time`,
  DROP `slesson_learner_end_time`;
  
ALTER TABLE `tbl_scheduled_lessons` ADD `slesson_grpcls_id` INT NOT NULL AFTER `slesson_id`; 


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
  `sldetail_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_scheduled_lesson_details`
--
ALTER TABLE `tbl_scheduled_lesson_details`
  ADD PRIMARY KEY (`sldetail_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_scheduled_lesson_details`
--
ALTER TABLE `tbl_scheduled_lesson_details`
  MODIFY `sldetail_id` int(11) NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `tbl_commission_settings` ADD `commsetting_is_grpcls` TINYINT NOT NULL AFTER `commsetting_is_mandatory`; 

ALTER TABLE `tbl_commission_settings` DROP INDEX `commsetting_user_id`, ADD UNIQUE `commsetting_user_id` (`commsetting_user_id`, `commsetting_is_grpcls`) USING BTREE;

ALTER TABLE `tbl_commission_setting_history` ADD `csh_commsetting_is_grpcls` TINYINT NOT NULL AFTER `csh_commsetting_is_mandatory`; 

ALTER TABLE `tbl_order_products` ADD `op_grpcls_id` INT NOT NULL AFTER `op_invoice_number`; 

ALTER TABLE `tbl_user_settings` ADD `us_google_access_token` VARCHAR(255) NOT NULL AFTER `us_booking_before`; 
ALTER TABLE `tbl_user_settings` ADD `us_google_access_token_expiry` DATETIME NOT NULL AFTER `us_google_access_token`;

ALTER TABLE `tbl_scheduled_lessons` ADD `slesson_teacher_google_calendar_id` VARCHAR(255) NOT NULL AFTER `slesson_is_teacher_paid`; 

ALTER TABLE `tbl_scheduled_lesson_details` ADD `sldetail_learner_google_calendar_id` VARCHAR(255) NOT NULL AFTER `sldetail_learner_status`; 
