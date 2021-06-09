UPDATE `tbl_configurations` SET `conf_val` = '1' WHERE `conf_name` = 'CONF_USE_SSL';
UPDATE `tbl_configurations` SET `conf_val` = '15,30,45,60,90,120' WHERE `conf_name` = 'CONF_PAID_LESSON_DURATION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.12.0.20210426' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

ALTER TABLE `tbl_attached_files` ADD `afile_attribute_title` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `afile_physical_path`;
ALTER TABLE `tbl_attached_files` ADD `afile_attribute_alt` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `afile_attribute_title`;

REPLACE INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES (NULL, 'LBL_Specific_Language_Alter_Tags_Note', '1', 'Image alter message can be language specific . Please upload image for specific language before update alter tags');

ALTER TABLE `tbl_meta_tags_lang` ADD `meta_og_title` VARCHAR(90) NOT NULL AFTER `meta_other_meta_tags`;

ALTER TABLE `tbl_meta_tags_lang` ADD `meta_og_url` VARCHAR(255) NOT NULL AFTER `meta_og_title`;

ALTER TABLE `tbl_meta_tags_lang` ADD `meta_og_description` VARCHAR(300) NOT NULL AFTER `meta_og_url`;


ALTER TABLE `tbl_url_rewrites` ADD `urlrewrite_lang_id` INT(11) NOT NULL DEFAULT '1' AFTER `urlrewrite_custom`;

ALTER TABLE `tbl_url_rewrites` ADD `urlrewrite_http_resp_code` VARCHAR(10) NOT NULL AFTER `urlrewrite_lang_id`;

REPLACE INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES (NULL, 'LBL_Example_Custom_URL_Example', '1', 'Example: If Site URL Will Be http://domainname.com/cms/view/1 And You Want To Rewrite Then Original URL: Cms/view/1 custom URL: My-custom-page Browsing URL : http://domainname.com/my-custom-page');

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES 
('WIZIQ_API_SECRET_KEY', '', '0'), ('WIZIQ_API_ACCESS_KEY', '', '0'), 
('WIZIQ_API_CLASSAPI_URL', '', '1'), ('WIZIQ_API_SERVICE_URL', '', '0');

CREATE TABLE `tbl_wiziq_teachers` (
  `wizteach_user_id` int NOT NULL,
  `wizteach_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `wizteach_email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `wizteach_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tbl_wiziq_teachers`  ADD PRIMARY KEY (`wizteach_user_id`);
ALTER TABLE `tbl_wiziq_teachers`  ADD CONSTRAINT `tbl_wiziq_teachers_ibfk_1` 
	FOREIGN KEY (`wizteach_user_id`) REFERENCES `tbl_users` (`user_id`) 
	ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `tbl_teacher_stats` (
 `testat_user_id` int NOT NULL,
 `testat_ratings` decimal(10,2) NOT NULL,
 `testat_reviewes` int NOT NULL,
 `testat_students` int NOT NULL,
 `testat_lessions` int NOT NULL,
 `testat_minprice` decimal(10,2) NOT NULL,
 `testat_maxprice` decimal(10,2) NOT NULL,
 `testat_preference` int NOT NULL,
 `testat_qualification` int NOT NULL,
 `testat_valid_cred` int NOT NULL,
 `testat_teachlang` int NOT NULL,
 `testat_speaklang` int NOT NULL,
 `testat_gavailability` int NOT NULL,
 `testat_subscription` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tbl_teacher_stats`  ADD PRIMARY KEY (`testat_user_id`);
ALTER TABLE `tbl_teacher_stats`  ADD CONSTRAINT `tbl_teacher_stats_ibfk_1` 
FOREIGN KEY (`testat_user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_DELETE_LESSON_PLAN_CONFIRM_TEXT', 1, 'Are You Sure! By Removing This Lesson Will Also Unlink It From Courses And Scheduled Lessons!');


CREATE TABLE `tbl_extra_pages` (
  `epage_id` int(11) NOT NULL,
  `epage_identifier` varchar(255) NOT NULL,
  `epage_type` tinyint(4) NOT NULL,
  `epage_active` tinyint(1) NOT NULL,
  `epage_default_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_extra_pages`
--
ALTER TABLE `tbl_extra_pages`
  ADD PRIMARY KEY (`epage_id`);

--
-- AUTO_INCREMENT for table `tbl_extra_pages`
--
ALTER TABLE `tbl_extra_pages`
  MODIFY `epage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `tbl_extra_pages_lang` (
  `epagelang_epage_id` int(11) NOT NULL,
  `epagelang_lang_id` int(11) NOT NULL,
  `epage_label` varchar(255) NOT NULL,
  `epage_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_extra_pages_lang`
--
ALTER TABLE `tbl_extra_pages_lang`
  ADD UNIQUE KEY `epagelang_epage_id` (`epagelang_epage_id`,`epagelang_lang_id`);

INSERT INTO `tbl_extra_pages` (`epage_id`, `epage_identifier`, `epage_type`, `epage_active`, `epage_default_content`) VALUES
(1, 'Teacher Profile info bar', 1, 1, '<div class=\"infobar__list-content\">\r\n										<ol>\r\n											<li>Profile needs to be 80% completed</li>\r\n											<li>You have to complete lorem ipsum dolar summit text</li>\r\n											<li>After verify all the details you have to mark availbility in calendar section.</li>\r\n										</ol>\r\n									</div>');

INSERT INTO `tbl_extra_pages_lang` (`epagelang_epage_id`, `epagelang_lang_id`, `epage_label`, `epage_content`) VALUES
(1, 1, 'Teacher Profile info bar', '<div class=\"infobar__list-content\">\r\n										<ol>\r\n											<li>Profile needs to be 80% completed</li>\r\n											<li>You have to complete lorem ipsum dolar summit text</li>\r\n											<li>After verify all the details you have to mark availbility in calendar section.</li>\r\n										</ol>\r\n									</div>'),
(1, 2, 'Teacher Profile info bar', '<div class=\"infobar__list-content\">\r\n										<ol>\r\n											<li>Profile needs to be 80% completed</li>\r\n											<li>You have to complete lorem ipsum dolar summit text</li>\r\n											<li>After verify all the details you have to mark availbility in calendar section.</li>\r\n										</ol>\r\n									</div>');


REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_PROFILE_INFO_HEADING', 1, 'To successfully register your profile as an expert and to you available in search results.'),
('LBL_PROFILE_INFO_HEADING', 2, 'To successfully register your profile as an expert and to you available in search results.');

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_NO_RECORD_FLASH_CARD_TITLE', 1, "You havn't added any <span>Flash-card</span> yet"),
('LBL_NO_RECORD_FLASH_CARD_TITLE', 2, "You havn't added any <span>Flash-card</span> yet"),
('LBL_NO_RECORD_FLASH_CARD_TEXT', 1, 'Click on the button "Add Flash-card" to add it. It will help you during the class'),
('LBL_NO_RECORD_FLASH_CARD_TEXT', 2, 'Click on the button "Add Flash-card" to add it. It will help you during the class');

--
-- Table structure for table `tbl_user_teach_languages`
--

ALTER TABLE `tbl_user_teach_languages` DROP INDEX `language`;

ALTER TABLE `tbl_user_teach_languages` DROP INDEX `utl_slanguage_id`;

ALTER TABLE `tbl_user_teach_languages` DROP COLUMN `utl_single_lesson_amount`;

ALTER TABLE `tbl_user_teach_languages` DROP COLUMN `utl_bulk_lesson_amount`;

ALTER TABLE `tbl_user_teach_languages` DROP COLUMN `utl_booking_slot`;

ALTER TABLE `tbl_user_teach_languages` CHANGE `utl_slanguage_id` `utl_tlanguage_id` INT(11) NOT NULL;

ALTER TABLE `tbl_user_teach_languages` CHANGE `utl_us_user_id` `utl_user_id` INT(11) NOT NULL;

DELETE teachLANG FROM `tbl_user_teach_languages` as teachLANG WHERE teachLANG.`utl_id` NOT IN(SELECT * FROM (SELECT MIN(n.utl_id) FROM `tbl_user_teach_languages` n GROUP BY n.utl_user_id, n.utl_tlanguage_id) X );

--
-- Indexes for table `tbl_user_teach_languages`
--
ALTER TABLE `tbl_user_teach_languages`  ADD UNIQUE KEY `utl_user_id` (`utl_user_id`,`utl_tlanguage_id`);
  
--
-- Table structure for table `tbl_pricing_slabs`
--

CREATE TABLE `tbl_pricing_slabs` (
  `prislab_id` int(11) NOT NULL,
  `prislab_min` int(11) NOT NULL,
  `prislab_max` int(11) NOT NULL,
  `prislab_active` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_pricing_slabs`
--

ALTER TABLE `tbl_pricing_slabs`
  ADD PRIMARY KEY (`prislab_id`);

--
-- AUTO_INCREMENT for table `tbl_pricing_slabs`
--
ALTER TABLE `tbl_pricing_slabs`
  MODIFY `prislab_id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `tbl_user_teach_lang_prices`
--

CREATE TABLE `tbl_user_teach_lang_prices` (
  `ustelgpr_utl_id` int(11) NOT NULL,
  `ustelgpr_slot` int(11) NOT NULL,
  `ustelgpr_price` decimal(10,2) NOT NULL,
  `ustelgpr_min_slab` int(11) NOT NULL,
  `ustelgpr_max_slab` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `tbl_user_teach_lang_prices`
--
ALTER TABLE `tbl_user_teach_lang_prices`
  ADD UNIQUE KEY `ustelgpr_utl_id` (`ustelgpr_utl_id`,`ustelgpr_slot`,`ustelgpr_min_slab`,`ustelgpr_max_slab`);

--
-- AUTO_INCREMENT for dumped tables
--

DROP TABLE `tbl_lesson_packages`, `tbl_lesson_packages_lang`;

REPLACE INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_ENABLE_FREE_TRIAL', 1, 0);

INSERT INTO `tbl_pricing_slabs` (`prislab_id`, `prislab_min`, `prislab_max`, `prislab_active`) VALUES
(1, 1, 4, 1),
(2, 5, 9, 1),
(3, 10, 100, 1);


ALTER TABLE `tbl_teacher_offer_price`  DROP `top_bulk_lesson_price`;

ALTER TABLE `tbl_teacher_offer_price` CHANGE `top_single_lesson_price` `top_percentage` DECIMAL(10,2) NOT NULL;

ALTER TABLE `tbl_order_products` CHANGE `op_slanguage_id` `op_tlanguage_id` INT(11) NOT NULL;

ALTER TABLE `tbl_order_products`  DROP `op_lpackage_lessons`;

-- task_84683_report_an_issue

CREATE TABLE `tbl_reported_issues` (
  `repiss_id` int NOT NULL,
  `repiss_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `repiss_sldetail_id` int NOT NULL,
  `repiss_reported_on` datetime NOT NULL,
  `repiss_reported_by` int NOT NULL,
  `repiss_status` int NOT NULL,
  `repiss_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `repiss_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tbl_reported_issues`  ADD PRIMARY KEY (`repiss_id`);
ALTER TABLE `tbl_reported_issues`  MODIFY `repiss_id` int NOT NULL AUTO_INCREMENT;

CREATE TABLE `tbl_reported_issues_log` (
  `reislo_id` int NOT NULL,
  `reislo_repiss_id` int NOT NULL,
  `reislo_action` int NOT NULL,
  `reislo_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reislo_added_on` datetime NOT NULL,
  `reislo_added_by` int NOT NULL,
  `reislo_added_by_type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tbl_reported_issues_log`  ADD PRIMARY KEY (`reislo_id`),  ADD KEY `reislo_repiss_id` (`reislo_repiss_id`);
ALTER TABLE `tbl_reported_issues_log`  MODIFY `reislo_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_reported_issues_log`  ADD CONSTRAINT `tbl_reported_issues_log_ibfk_1` 
FOREIGN KEY (`reislo_repiss_id`) REFERENCES `tbl_reported_issues` (`repiss_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_REPORT_ISSUE_HOURS_AFTER_COMPLETION', '24', '1');
INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_ESCLATE_ISSUE_HOURS_AFTER_RESOLUTION', '24', '1');

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) 
VALUES (NULL, 'Resolved Issue Transaction Settlements', 'ReportedIssue/resolvedIssueSettlement', '60', '1');

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) 
VALUES (NULL, 'Completed Lesson Transaction Settlements', 'ReportedIssue/completedLessonSettlement', '60', '1');

ALTER TABLE `tbl_scheduled_lesson_details` ADD `sldetail_is_teacher_paid` INT NOT NULL AFTER `sldetail_added_on`;
ALTER TABLE `tbl_scheduled_lessons`  DROP `slesson_is_teacher_paid`;

ALTER TABLE `tbl_group_classes` CHANGE `grpcls_slanguage_id` `grpcls_tlanguage_id` INT(11) NOT NULL;

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.5.20210421' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.6.20210507' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.14.0.20210519' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.15.0.20210520' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';


ALTER TABLE `tbl_reported_issues` ADD `repiss_slesson_id` INT NOT NULL AFTER `repiss_title`;


REPLACE INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('user_password_changed_successfully', 1, 'Password Changed Successfully', 'Password reset successfully {website_name}', '\r\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#519CEA;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#519CEA;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px; display:block;text-align:center;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"display: block;padding:0 20px 40px;text-align:center;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;text-align:center;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #519CEA;\">Congratulations</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;text-align:center;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>\r\n												<p style=\"text-align:center;font-size: 14px;line-height: 20px;color: #676767;\">Your password has been changed successfully.</p>                                                \r\n                                                \r\n												<p style=\"text-align:center;font-size: 14px;line-height: 20px;color: #676767;\">Please find your new credentials</p>                                                \r\n                                                <br />\r\n												<br />\r\n												                                                \r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_email}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Password<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_password}</td>\r\n														</tr>                                     \r\n													</tbody>\r\n												</table> \r\n												<p style=\"text-align:center;font-size: 14px;line-height: 20px;color: #676767;\">Please click on below link to Login to your account.</p>\r\n												<div><br />\r\n													</div><a href=\"{website_url}\" style=\"background:#519CEA; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click to Login</a></td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;text-align:center;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#519CEA;text-align:center;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;text-align:center;\">                                  Be sure to add <a href=\"#\" style=\"color: #3d91e8\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; {current_year}, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_full_name}<br>\r\n{website_name}<br>\r\n{website_url}<br>\r\n{login_link}<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);


REPLACE INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES
(NULL, 'LBL_Change_Password_Description', '1', 'Your password changed By Admin.Please check your email.')
, (NULL, 'Msg_Read_Permission_Denied_%s', '1', 'Read permission denied on %s.')
, (NULL, 'Msg_Write_Permission_Denied_%s', '1', 'Write permission denied on %s.')
, (NULL, 'LBL_Robots_File_Txt', '1', 'robots.txt')
, (NULL, 'LBL_Robots_File_Content', '1', 'robots.txt')
, (NULL, 'LBL_Edit_Robots_File', '1', 'Edit robots.txt')
, (NULL, 'NOTE_Robots_File_Modification', '1', 'Modify this file only if you understand it\'s impact on the website\'s indexing on search engines.')
;

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.16.0.20210601' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';


REPLACE INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES (NULL, 'LBL_Remember_Me', '1', 'Stay logged in');

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_DELETE_LESSON_PLAN_CONFIRM_TEXT', 1, 'Are You Sure! By Removing This Lesson Will Also Unlink It From Courses And Scheduled Lessons!')
,('LBL_%s_Tutors', 1, '%s Tutors')
,('LBL_%s_Tutors', 2, '%s مدرسون')
,('VERB_Issue_Reported', 1, 'reported issue')
,('VERB_Scheduled', 1, 'scheduled')
,('VERB_Rescheduled', 1, 'rescheduled')
,('VERB_Canceled', 1, 'canceled');

Update `tbl_language_labels` SET `label_caption` = 'Master Any Language with Online Tutors' WHERE `label_key` = 'LBL_Slider_Title_Text' AND `label_lang_id` = 1;
Update `tbl_language_labels` SET `label_caption` = 'Learn any language at any time from anywhere.' WHERE `label_key` = 'LBL_Slider_Description_Text' AND `label_lang_id` = 1;
Update `tbl_language_labels` SET `label_caption` = 'Search by Language or Subject' WHERE `label_key` = 'LBL_I_am_learning...' AND `label_lang_id` = 1;
Update `tbl_language_labels` SET `label_caption` = 'What language do you want to learn?' WHERE `label_key` = 'Lbl_What_Language_You_want_to_learn?' AND 'label_lang_id' = 1;


REPLACE INTO `tbl_extra_pages` (`epage_id`,`epage_identifier`,`epage_type`,`epage_active`, `epage_default_content`) VALUES ('2', 'Why Us Block', '2', '1', '    <section class=\"section section--services\">\r\n        <div class=\"container container--narrow\">\r\n            <div class=\"section__head\">\r\n                <h2>We make language learning easy & simpler</h2>\r\n            </div>\r\n\r\n            <div class=\"section__body\">\r\n                <div class=\"row\">\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>Professional Tutors</h3>\r\n                                <p>Choose from over a myriad of professional & experienced teachers to be fluent in any language.</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55_1.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>1-on-1 Live sessions</h3>\r\n                                <p>Connect with your teachers via 1-on-1 live chat sessions and build a deeper understanding of a language.</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55_2.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>Group Classes</h3>\r\n                                <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55_3.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>Convenience & Flexibility</h3>\r\n                                <p>Schedule lessons as per your availability and learn at your own pace with no constraints of time and place.</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>');
REPLACE INTO `tbl_extra_pages_lang` (`epagelang_epage_id`, `epagelang_lang_id`, `epage_label`, `epage_content`) VALUES ('2', '1', 'Why Us Block', '    <section class=\"section section--services\">\r\n        <div class=\"container container--narrow\">\r\n            <div class=\"section__head\">\r\n                <h2>We make language learning easy & simpler</h2>\r\n            </div>\r\n\r\n            <div class=\"section__body\">\r\n                <div class=\"row\">\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>Professional Tutors</h3>\r\n                                <p>Choose from over a myriad of professional & experienced teachers to be fluent in any language.</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55_1.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>1-on-1 Live sessions</h3>\r\n                                <p>Connect with your teachers via 1-on-1 live chat sessions and build a deeper understanding of a language.</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55_2.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>Group Classes</h3>\r\n                                <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <div class=\"col-md-6\">\r\n                        <div class=\"service\">\r\n                            <div class=\"service__media\">\r\n                                <img src=\"images/55x55_3.svg\">\r\n                            </div>\r\n                            <div class=\"service__content\">\r\n                                <h3>Convenience & Flexibility</h3>\r\n                                <p>Schedule lessons as per your availability and learn at your own pace with no constraints of time and place.</p>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>');
REPLACE INTO `tbl_extra_pages` (`epage_id`, `epage_identifier`, `epage_type`, `epage_active`, `epage_default_content`) VALUES ('3', 'Browse tutor section', '3', '1', '    <section class=\"section section--cta\" style=\"background-image:url(images/cta.png);\">\r\n        <div class=\"container container--narrow\">\r\n            <div class=\"cta-content\">\r\n                <h2>Speak any language fluently with the help of professional tutors</h2>\r\n                <button class=\"btn btn--secondary btn--large \">Browse Tutors</button>\r\n            </div>\r\n        </div>\r\n    </section>');
REPLACE INTO `tbl_extra_pages_lang` (`epagelang_epage_id`, `epagelang_lang_id`, `epage_label`, `epage_content`) VALUES ('3', '1', 'Browse Tutor', '    <section class=\"section section--cta\" style=\"background-image:url(images/cta.png);\">\r\n        <div class=\"container container--narrow\">\r\n            <div class=\"cta-content\">\r\n                <h2>Speak any language fluently with the help of professional tutors</h2>\r\n                <button class=\"btn btn--secondary btn--large \">Browse Tutors</button>\r\n            </div>\r\n        </div>\r\n    </section>');
REPLACE INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES (NULL, 'LBL_newsletter_descritption', '1', 'Enter your email and subscribe to receive notifications of new posts by email.');

--
-- Teacher search query update
--  
ALTER TABLE `tbl_teacher_stats` 
    ADD `testat_day1` JSON NULL AFTER `testat_gavailability`, 
    ADD `testat_day2` JSON NULL AFTER `testat_day1`, 
    ADD `testat_day3` JSON NULL AFTER `testat_day2`, 
    ADD `testat_day4` JSON NULL AFTER `testat_day3`, 
    ADD `testat_day5` JSON NULL AFTER `testat_day4`, 
    ADD `testat_day6` JSON NULL AFTER `testat_day5`, 
    ADD `testat_day7` JSON NULL AFTER `testat_day6`;


ALTER TABLE `tbl_teacher_stats`
    DROP `testat_day1`,
    DROP `testat_day2`,
    DROP `testat_day3`,
    DROP `testat_day4`,
    DROP `testat_day5`,
    DROP `testat_day6`,
    DROP `testat_day7`;

ALTER TABLE `tbl_teacher_stats` ADD `testat_timeslots` JSON NOT NULL AFTER `testat_gavailability`;
ALTER TABLE `tbl_teacher_stats` CHANGE `testat_gavailability` `testat_availability` INT NOT NULL;

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_CHECKOUT_SLAB_TITLE', 1, 'You can buy multiple lessons as per you convenience.'),
 ('LBL_CHECKOUT_SLAB_DESCRIPTION', 1, 'Please choose the same by adding the quantity of the lesson. We have following options below.');
REPLACE INTO `tbl_extra_pages` (`epage_id`, `epage_identifier`, `epage_type`, `epage_active`, `epage_default_content`) VALUES (NULL, 'Contact Banner', '4', '1', '<div class=\"intro-head\"><h6 class=\"small-title\">Contact Us</h6><h2>Want to get in touch?<br> We would love to hear from you.</h2></div><div class=\"about-media\"><div class=\"media\"><img src=\"images/contact_hero.png\" alt=\"\"></div></div>');
REPLACE INTO `tbl_extra_pages_lang` (`epagelang_epage_id`, `epagelang_lang_id`, `epage_label`, `epage_content`) VALUES ('4', '1', 'Contact Banner', '<div class=\"intro-head\"><h6 class=\"small-title\">Contact Us</h6><h2>Want to get in touch?<br> We would love to hear from you.</h2></div><div class=\"about-media\"><div class=\"media\"><img src=\"images/contact_hero.png\" alt=\"\"></div></div>');
REPLACE INTO `tbl_extra_pages` (`epage_id`, `epage_identifier`, `epage_type`, `epage_active`, `epage_default_content`) VALUES (5, 'Contact left section', 5, 1, '<div class=\"col-md-5 col-lg-4\"><div class=\"contact-info\"><div class=\"contact-info__row\"><div class=\"contact__icon\"><svg class=\"icon icon--address\"><use xlink:href=\"images/sprite.yo-coach.svg#address\"></use></svg></div><div class=\"contact__content\"><h6>Address</h6><p>Yo-Coach Pvt. Ltd.<br> Plot No. 268, Lorem Ipsum, Industrial Area<br> Sector 82, Mohali, Punjab</p></div></div><div class=\"contact-info__row\"><div class=\"contact__icon\"> <svg class=\"icon icon--mail\"><use xlink:href=\"images/sprite.yo-coach.svg#mail\"></use></svg></div><div class=\"contact__content\"><h6>Email</h6><p>sales@yo-oach.com <br> info@yo-coach.com</p></div></div><div class=\"contact-info__row\"><div class=\"contact__icon\"><svg class=\"icon icon--telephone\"><use xlink:href=\"images/sprite.yo-coach.svg#telephone\"></use></svg></div><div class=\"contact__content\"><h6>Phone no.</h6><p>(+44) 020 7846 0316 <br> (+44) 020 7846 0316 <br> (+44) 020 7846 0316</p></div></div></div></div>');
REPLACE INTO `tbl_extra_pages_lang` (`epagelang_epage_id`, `epagelang_lang_id`, `epage_label`, `epage_content`) VALUES ('5', '1', 'Contact Left Section', '<div class=\"col-md-5 col-lg-4\"><div class=\"contact-info\"><div class=\"contact-info__row\"><div class=\"contact__icon\"><svg class=\"icon icon--address\"><use xlink:href=\"images/sprite.yo-coach.svg#address\"></use></svg></div><div class=\"contact__content\"><h6>Address</h6><p>Yo-Coach Pvt. Ltd.<br> Plot No. 268, Lorem Ipsum, Industrial Area<br> Sector 82, Mohali, Punjab</p></div></div><div class=\"contact-info__row\"><div class=\"contact__icon\"> <svg class=\"icon icon--mail\"><use xlink:href=\"images/sprite.yo-coach.svg#mail\"></use></svg></div><div class=\"contact__content\"><h6>Email</h6><p>sales@yo-oach.com <br> info@yo-coach.com</p></div></div><div class=\"contact-info__row\"><div class=\"contact__icon\"><svg class=\"icon icon--telephone\"><use xlink:href=\"images/sprite.yo-coach.svg#telephone\"></use></svg></div><div class=\"contact__content\"><h6>Phone no.</h6><p>(+44) 020 7846 0316 <br> (+44) 020 7846 0316 <br> (+44) 020 7846 0316</p></div></div></div></div>');

DELETE FROM `tbl_content_pages` WHERE `tbl_content_pages`.`cpage_id` = 5;
DELETE FROM `tbl_content_pages_lang` WHERE `tbl_content_pages_lang`.`cpagelang_cpage_id` = 5 AND `tbl_content_pages_lang`.`cpagelang_lang_id` = 1;
DELETE FROM `tbl_content_pages_lang` WHERE `tbl_content_pages_lang`.`cpagelang_cpage_id` = 5 AND `tbl_content_pages_lang`.`cpagelang_lang_id` = 2;
REPLACE INTO `tbl_content_pages_block_lang` (`cpblocklang_id`, `cpblocklang_lang_id`, `cpblocklang_cpage_id`, `cpblocklang_block_id`, `cpblocklang_text`) VALUES
(1, 1, 1, 1, '<section class=\"section\">        \r\n	<div class=\"container container--narrow\">            \r\n		<div class=\"row\">                \r\n			<div class=\"col-lg-5\">                    \r\n				<div class=\"primary-content\">                        \r\n					<div class=\"main__title\">                            \r\n						<h2>It starts with <br />\r\n							Who We Are.</h2>                        </div>                    </div>                </div>                \r\n			<div class=\"col-lg-7\">                    \r\n				<div class=\"who-we__content\">                        \r\n					<p>We build a organization to help people to learn online. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit</p>                            \r\n                        \r\n					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat. Nam libero tempore, cum soluta nobis est omnis voluptas assumenda\r\n                        </p>                        \r\n					<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Nam libero tempore, cum soluta nobis est</p>                    </div>                </div>            </div>        </div>    </section>    \r\n<section class=\"section section--value\">        \r\n	<div class=\"container container--narrow\">            \r\n		<div class=\"row flex-lg-nowrap\">                \r\n			<div class=\"col-lg-5\">                    \r\n				<div class=\"panel-left\">                        \r\n					<h6 class=\"small-title\">Our Core Values</h6>                        \r\n					<h2>We love clients who<br />\r\n						 understand our values</h2>                        \r\n                        \r\n					<div class=\"slider-nav\">                            \r\n						<button type=\"button\" class=\"prev-slide\" aria-label=\"Previous\"></button>                            \r\n						<button type=\"button\" class=\"next-slide\" aria-label=\"Next\"></button>                        </div>                    </div>                </div>                \r\n			<div class=\"col-lg-12\">                    \r\n				<div class=\"panel-right\">                        \r\n					<div class=\"slider slider--value slider--onehalf slider-onehalf-js\">                            \r\n						<div>                                \r\n							<div class=\"slider__item\">                                    \r\n								<div class=\"slide-box\">                                        \r\n									<div class=\"slide-box__head\">                                            \r\n										<div class=\"count__box\">                                                \r\n											<h2>01</h2>                                            </div>                                            \r\n										<div class=\"slide-box__title\">                                                \r\n											<h5>Duis aute irure dolor in Nam libero tempore, cum soluta nobis est</h5>                                            </div>                                        </div>                                        \r\n									<div class=\"slide-box__body\">                                            \r\n										<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur quia voluptas sit aspernatur aut odit aut fugit dolores eos qui ratione voluptatem sequi nesciunt.</p>                                        </div>                                    </div>                                </div>                            </div>                            \r\n						<div>                                \r\n							<div class=\"slider__item\">                                    \r\n								<div class=\"slide-box\">                                        \r\n									<div class=\"slide-box__head\">                                            \r\n										<div class=\"count__box\">                                                \r\n											<h2>02</h2>                                            </div>                                            \r\n										<div class=\"slide-box__title\">                                                \r\n											<h5>Duis aute irure dolor in Nam libero tempore, cum soluta nobis est</h5>                                            </div>                                        </div>                                        \r\n									<div class=\"slide-box__body\">                                            \r\n										<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur quia voluptas sit aspernatur aut odit aut fugit.</p>                                        </div>                                    </div>                                </div>                            </div>                            \r\n                            \r\n						<div>                                \r\n							<div class=\"slider__item\">                                    \r\n								<div class=\"slide-box\">                                        \r\n									<div class=\"slide-box__head\">                                            \r\n										<div class=\"count__box\">                                                \r\n											<h2>03</h2>                                            </div>                                            \r\n										<div class=\"slide-box__title\">                                                \r\n											<h5>Duis aute irure dolor in Nam libero tempore, cum soluta nobis est</h5>                                            </div>                                        </div>                                        \r\n									<div class=\"slide-box__body\">                                            \r\n										<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur quia voluptas sit aspernatur aut odit aut fugit dolores eos qui ratione voluptatem sequi nesciunt.</p>                                        </div>                                    </div>                                </div>                            </div>                        </div>                    </div>                </div>            </div>        </div>    </section>    \r\n<section class=\"section section--mission\">        \r\n	<div class=\"container container--narrow\">            \r\n		<div class=\"row\">                \r\n			<div class=\"col-md-5 col-lg-5\">                    \r\n				<div class=\"primary-content\">                        \r\n					<h6 class=\"small-title\">Our mission &amp; Vision</h6>                        \r\n					<div class=\"main__title\">                            \r\n						<h2>Get to know about our <br />\r\n							mission and vision</h2>                        </div>                        <a href=\"#\" class=\"btn btn--primary\">Contact Us</a>                    </div>                </div>                \r\n			<div class=\"col-md-7 col-lg-7\">                    \r\n				<div class=\"mission\">                        \r\n					<div class=\"mission__head\">                            \r\n						<div class=\"mission__media\">                                \r\n							<svg class=\"icon icon--target\">\r\n								<use xlink:href=\"/images/sprite.yo-coach.svg#target\"></use></svg>                            </div>                            \r\n						<h4>Our Mission</h4>                        </div>                        \r\n					<div class=\"mission__body\">                            \r\n						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</p>                        </div>                    </div>                    \r\n				<div class=\"mission\">                        \r\n					<div class=\"mission__head\">                            \r\n						<div class=\"mission__media\">                                \r\n							<svg class=\"icon icon--focus\">\r\n								<use xlink:href=\"images/sprite.yo-coach.svg#focus\"></use></svg>                            </div>                            \r\n						<h4>Our Inspiration &amp; Vison</h4>                        </div>                        \r\n					<div class=\"mission__body\">                            \r\n						<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus Temporibus autem.</p>                        </div>                    </div>                    \r\n				<div class=\"mission\">                        \r\n					<div class=\"mission__head\">                            \r\n						<div class=\"mission__media\">                                \r\n							<svg class=\"icon icon--crosshair\">\r\n								<use xlink:href=\"images/sprite.yo-coach.svg#crosshair\"></use></svg>                            </div>                            \r\n						<h4>Our Goal</h4>                        </div>                        \r\n					<div class=\"mission__body\">                            \r\n						<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus Temporibus autem.</p>                        </div>                    </div>                </div>            </div>        </div>    </section>    \r\n<section class=\"section section--team\">        \r\n	<div class=\"container container--narrow\">            \r\n		<div class=\"team\">                \r\n			<div class=\"team__head\">                    \r\n				<div class=\"row\">                        \r\n					<div class=\"col-lg-5\">                            \r\n						<div class=\"primary-content\">                                \r\n							<h6 class=\"small-title\">People</h6>                                \r\n							<div class=\"main__title\">                                    \r\n								<h2>Meet our team  <br />\r\n									 of experts</h2>                                </div>                            </div>                        </div>                        \r\n					<div class=\"col-lg-7\">                            \r\n						<p class=\"team-content\">                                Our highly knowledgeable and experienced team members have a creative, collaborative, and committed nature which enables Yo!Coach to be a highly effective company.\r\n                            </p>                        </div>                    </div>                </div>                \r\n			<div class=\"team__body\">                    \r\n				<div class=\"row\">                        \r\n					<div class=\"col-sm-6 col-lg-4\">                            \r\n						<div class=\"tile\">                                \r\n							<div class=\"tile__head\">                                    \r\n								<div class=\"tile__media \">                                        <img src=\"images/364x364.png\" alt=\"\" />                                    </div>                                </div>                                \r\n							<div class=\"tile__body\">                                    \r\n								<h6>Stephen Fleming</h6>                                    \r\n								<p>CEO &amp; Founder</p>                                </div>                            </div>                        </div>                        \r\n					<div class=\"col-sm-6 col-lg-4\">                            \r\n						<div class=\"tile\">                                \r\n							<div class=\"tile__head\">                                    \r\n								<div class=\"tile__media \">                                        <img src=\"images/364x364_1.png\" alt=\"\" />                                    </div>                                </div>                                \r\n							<div class=\"tile__body\">                                    \r\n								<h6>Nathan Astle</h6>                                    \r\n								<p>Sales/Marketing Head</p>                                </div>                            </div>                        </div>                        \r\n					<div class=\"col-sm-6 col-lg-4\">                            \r\n						<div class=\"tile\">                                \r\n							<div class=\"tile__head\">                                    \r\n								<div class=\"tile__media \">                                        <img src=\"images/364x364_2.png\" alt=\"\" />                                    </div>                                </div>                                \r\n							<div class=\"tile__body\">                                    \r\n								<h6>James Anderson</h6>                                    \r\n								<p>Creative Director</p>                                </div>                            </div>                        </div>                        \r\n					<div class=\"col-sm-6 col-lg-4\">                            \r\n						<div class=\"tile\">                                \r\n							<div class=\"tile__head\">                                    \r\n								<div class=\"tile__media \">                                        <img src=\"images/364x364_3.png\" alt=\"\" />                                    </div>                                </div>                                \r\n							<div class=\"tile__body\">                                    \r\n								<h6>Mark Boucher</h6>                                    \r\n								<p>Tech Lead</p>                                </div>                            </div>                        </div>                        \r\n					<div class=\"col-sm-6 col-lg-4\">                            \r\n						<div class=\"tile\">                                \r\n							<div class=\"tile__head\">                                    \r\n								<div class=\"tile__media \">                                        <img src=\"images/364x364_4.png\" alt=\"\" />                                    </div>                                </div>                                \r\n							<div class=\"tile__body\">                                    \r\n								<h6>Steve Waugh</h6>                                    \r\n								<p>Sales/Marketing Head</p>                                </div>                            </div>                        </div>                        \r\n					<div class=\"col-sm-6 col-lg-4\">                            \r\n						<div class=\"tile\">                                \r\n							<div class=\"tile__head\">                                    \r\n								<div class=\"tile__media \">                                        <img src=\"images/364x364_5.png\" alt=\"\" />                                    </div>                                </div>                                \r\n							<div class=\"tile__body\">                                    \r\n								<h6>Damien Martyn</h6>                                    \r\n								<p>Creative Director</p>                                </div>                            </div>                        </div>                    </div>                </div>            </div>        </div>    </section>    \r\n<section class=\"section section--step\">        \r\n	<div class=\"container container--narrow\">            \r\n		<div class=\"section__head\">                \r\n			<h2>How to start learning with Yo!Coach?</h2>            </div>            \r\n		<div class=\"section__body\">                \r\n			<div class=\"step-wrapper\">                    \r\n				<div class=\"step-container__head\">                        \r\n					<div class=\"step-tabs slider-tabs--js\">                            \r\n						<div>                                \r\n							<button class=\"slider-tabs__action\">                                    <span class=\"slider-tabs__number\">01. </span>                                    <span class=\"slider-tabs__label\">Search</span>                                </button>                            </div>                            \r\n						<div>                                \r\n							<button class=\"slider-tabs__action\">                                    <span class=\"slider-tabs__number\">02. </span>                                    <span class=\"slider-tabs__label\">Book</span>                                </button>                            </div>                            \r\n						<div>                                \r\n							<button class=\"slider-tabs__action\">                                    <span class=\"slider-tabs__number\">03. </span>                                    <span class=\"slider-tabs__label\">Learn</span>                                </button>                            </div>                        </div>                    </div>                    \r\n				<div class=\"step-container__body\">                        \r\n					<div class=\"step-slider step-slider-js\">                            \r\n						<div>                                \r\n							<div class=\"step\">                                    \r\n								<div class=\"row \">                                        \r\n									<div class=\"col-md-6 col-lg-5 col-xl-6\">                                            \r\n										<div class=\"step__inner\">                                                \r\n											<div class=\"step__media\">                                                    <img src=\"images/STEP-1.svg\" alt=\"\" />                                                </div>                                            </div>                                        </div>                                        \r\n									<div class=\"col-md-6 col-lg-7 col-xl-6\">                                            \r\n										<div class=\"step__content\">                                                \r\n											<h3>Search through hundreds of best teachers</h3>                                                \r\n											<p>Go through teachers’ profiles and choose your language tutor. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>                                                \r\n											<div class=\"step__actions\">                                                    <a href=\"#\" class=\"btn btn--primary\">Browse Tutors</a>                                                    <a href=\"#\" class=\"btn-video\">                                                        \r\n													<svg class=\"icon icon--play\">\r\n														<use xlink:href=\"images/sprite.yo-coach.svg#play\"></use></svg>                                                        Watch Video                                                    </a>                                                </div>                                            </div>                                            \r\n                                        </div>                                    </div>                                </div>                            </div>                            \r\n						<div>                                \r\n							<div class=\"step\">                                    \r\n								<div class=\"row \">                                        \r\n									<div class=\"col-md-6 col-lg-5 col-xl-6\">                                            \r\n										<div class=\"step__inner\">                                                \r\n											<div class=\"step__media\">                                                    <img src=\"images/STEP-1.svg\" alt=\"\" />                                                </div>                                            </div>                                        </div>                                        \r\n									<div class=\"col-md-6 col-lg-7 col-xl-6\">                                            \r\n										<div class=\"step__content\">                                                \r\n											<h3>Book lessons with the best teacher for you</h3>                                                \r\n											<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer.</p>                                                \r\n											<div class=\"step__actions\">                                                    <a href=\"#\" class=\"btn btn--primary\">Browse Tutors</a>                                                    <a href=\"#\" class=\"btn-video\">                                                        \r\n													<svg class=\"icon icon--play\">\r\n														<use xlink:href=\"images/sprite.yo-coach.svg#play\"></use></svg>                                                        Watch Video                                                    </a>                                                </div>                                            </div>                                            \r\n                                        </div>                                    </div>                                </div>                            </div>                            \r\n						<div>                                \r\n							<div class=\"step\">                                    \r\n								<div class=\"row \">                                        \r\n									<div class=\"col-md-6 col-lg-5 col-xl-6\">                                            \r\n										<div class=\"step__inner\">                                                \r\n											<div class=\"step__media\">                                                    <img src=\"images/STEP-1.svg\" alt=\"\" />                                                </div>                                            </div>                                        </div>                                        \r\n									<div class=\"col-md-6 col-lg-7 col-xl-6\">                                            \r\n										<div class=\"step__content\">                                                \r\n											<h3>Log in to Rtist and Start learning.</h3>                                                \r\n											<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution.</p>                                                \r\n											<div class=\"step__actions\">                                                    <a href=\"#\" class=\"btn btn--primary\">Browse Tutors</a>                                                    <a href=\"#\" class=\"btn-video\">                                                        \r\n													<svg class=\"icon icon--play\">\r\n														<use xlink:href=\"images/sprite.yo-coach.svg#play\"></use></svg>                                                        Watch Video                                                    </a>                                                </div>                                            </div>                                            \r\n                                        </div>                                    </div>                                </div>                            </div>                        </div>                    </div>                </div>            </div>        </div>    </section>    \r\n<section class=\"section section--achievement\">        \r\n	<div class=\"container container--narrow\">            \r\n		<div class=\"row\">                \r\n			<div class=\"col-md-4\">                    \r\n				<div class=\"box\">                        \r\n					<div class=\"box__head\">                            \r\n						<div class=\"achievement-media\">                                \r\n							<svg class=\"icon icon--translater\">\r\n								<use xlink:href=\"images/sprite.yo-coach.svg#translater\"></use></svg>                            </div>                        </div>                        \r\n					<div class=\"box__body\">                            \r\n						<h3 class=\"achievement-title\">130 +</h3>                            \r\n						<p>Languages Available to Learn</p>                        </div>                    </div>                </div>                \r\n			<div class=\"col-md-4\">                    \r\n				<div class=\"box\">                        \r\n					<div class=\"box__head\">                            \r\n						<div class=\"achievement-media\">                                \r\n							<svg class=\"icon icon--teacher\">\r\n								<use xlink:href=\"images/sprite.yo-coach.svg#teacher\"></use></svg>                            </div>                        </div>                        \r\n					<div class=\"box__body\">                            \r\n						<h3 class=\"achievement-title\">10,000+</h3>                            \r\n						<p>Teachers From 120 Countries</p>                        </div>                    </div>                </div>                \r\n			<div class=\"col-md-4\">                    \r\n				<div class=\"box\">                        \r\n					<div class=\"box__head\">                            \r\n						<div class=\"achievement-media\">                                \r\n							<svg class=\"icon icon--learner\">\r\n								<use xlink:href=\"images/sprite.yo-coach.svg#learner\"></use></svg>                            </div>                        </div>                        \r\n					<div class=\"box__body\">                            \r\n						<h3 class=\"achievement-title\">5,000,000+</h3>                            \r\n						<p>Learners From 180 Countries</p>                        </div>                    </div>                </div>            </div>        </div>    </section>')
