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

--
ALTER TABLE `tbl_group_classes_lang`
  ADD PRIMARY KEY (`grpclslang_grpcls_id`,`grpclslang_lang_id`);

DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Calender';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('htmlAfterField_LESSON_DURATIONS_TEXT', '1', 'Please notify your tutors in advance before you change the lesson duration, since this can impact the tutor profile listing on the frontend.');

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

UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher {action} The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name}</h3>Teacher ({teacher_name}) has {action} the lesson ({lesson_name}).<br /><a href=\"{lesson_url}\">Click here</a> to view lesson.<br>\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', `etpl_replacements` = '{lesson_id}\r\n{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n{lesson_url}' WHERE `tbl_email_templates`.`etpl_code` = 'teacher_reschedule_email' AND `tbl_email_templates`.`etpl_lang_id` = 1;

UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#e84c3d;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"text-align:right;padding: 40px;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\">Teacher Cancel The Lesson!</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {learner_name} </h3>Teacher ({teacher_name}) has cancelled the lesson ({lesson_name}).<br /><a href="{lesson_url}">Click here</a> to view lesson.<br />\r\n												Reason:   <br />\r\n												{teacher_comment}</td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">                                  Be sure to add <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; 2018, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', `etpl_replacements` = '{lesson_id}\r\n{learner_name}\r\n{teacher_name}\r\n{lesson_name}\r\n{teacher_comment}\r\n{lesson_date}\r\n{lesson_start_time}\r\n{lesson_end_time}\r\n{action}\r\n{lesson_url}' WHERE `tbl_email_templates`.`etpl_code` = 'teacher_cancelled_email' AND `tbl_email_templates`.`etpl_lang_id` = 1; 

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.3.20210331' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.4.20210402' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

ALTER TABLE `tbl_users` ADD `user_phone_code` VARCHAR(6) NOT NULL AFTER `user_last_name`;

ALTER TABLE `tbl_user_teacher_request_values` ADD `utrvalue_user_phone_code` VARCHAR(6) NOT NULL AFTER `utrvalue_user_gender`;

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.11.5.20210403' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_PHONE_NO_VALIDATION_MSG', 1, 'Please add vaild phone no and length between 4 to 16'),
('LBL_Note:_Enter_Number_of_lessons_in_a_package', 1, 'Note: Enter Number Of Lessons In A Package'),
('LBL_Note:_Enter_Number_of_lessons_in_a_package', 2, 'ملاحظة: أدخل عدد من الدروس في حزمة)');

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_TRIAL_LESSON_%S_MINS', '1', 'One time, %s minutes');

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.12.1.20210503' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.13.0.20210510' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES 
('LBL_Logged_in_as_a_teacher', 1, 'Logged in as a <span>teacher</span>'),
('LBL_Logged_in_as_a_learner', 1, 'Logged in as a <span>learner</span>'),
('LBL_TEACHER_DASHBOARD_HEADING_{user-first-name}', 1, 'Hello {user-first-name}'),
('LBL_TEACHER_DASHBOARD_INFO_TEXT', 1, 'Please complete your profile as a professional to available in search results'),
('Lbl_To_Sync_with_google_calendar', 1, 'Connect your Google Calendar and synchronize all your Yo!Coach lessons with your personal schedule'),
('LBL_PROFILE_IMAGE_FIELD_INFO_TEXT', 1, 'Experts use profile picture to look professional'),
('LBL_PROFILE_VIDEO_FIELD_INFO', 1, 'Experts use videos to present their skillsets'),
('LBL_VIDEO_LINK_PLACEHOLDER', 1, 'eg: https://youtu.be/XkGgIjAHFDs');


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


-- drop TABLE if EXISTS tbl_reported_issues_logs;
-- drop TABLE if EXISTS tbl_reported_issues_log;
-- drop TABLE if EXISTS tbl_reported_issues;


ALTER TABLE `tbl_reported_issues` ADD `repiss_slesson_id` INT NOT NULL AFTER `repiss_title`;


REPLACE INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('user_password_changed_successfully', 1, 'Password Changed Successfully', 'Password reset successfully {website_name}', '\r\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\"> \r\n        \r\n        \r\n	<tbody>            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#519CEA;padding:30px 0;\"></td>    \r\n            \r\n		</tr>    \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td style=\"background:#519CEA;padding:0 0 0;\">          \r\n                    \r\n				<!--\r\n				header start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:20px 40px; display:block;text-align:center;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td>                  \r\n                                \r\n							<td style=\"display: block;padding:0 20px 40px;text-align:center;\">                      {social_media_icons}\r\n                      </td>              \r\n                            \r\n						</tr>          \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				header end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n       \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>                    \r\n				<!--\r\n				page body start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">             \r\n                  \r\n                        \r\n					<tbody>                        \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:20px 0 60px;text-align:center;\">                                     <img src=\"icon-account.png\" alt=\"\" />                                     \r\n												<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                     \r\n												<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #519CEA;\">Congratulations</h2>                                  </td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                  \r\n						<tr>                      \r\n							<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                          \r\n								<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                              \r\n                              \r\n									<tbody>\r\n										<tr>                                  \r\n											<td style=\"padding:60px 0 70px;text-align:center;\">                                      \r\n												<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\">Dear {user_full_name}</h3>\r\n												<p style=\"text-align:center;font-size: 14px;line-height: 20px;color: #676767;\">Your password has been changed successfully.</p>                                                \r\n                                                \r\n												<p style=\"text-align:center;font-size: 14px;line-height: 20px;color: #676767;\">Please find your new credentials</p>                                                \r\n                                                <br />\r\n												<br />\r\n												                                                \r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email</td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_email}</td>\r\n														</tr>                                                        \r\n														<tr>\r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Password<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>\r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_password}</td>\r\n														</tr>                                     \r\n													</tbody>\r\n												</table> \r\n												<p style=\"text-align:center;font-size: 14px;line-height: 20px;color: #676767;\">Please click on below link to Login to your account.</p>\r\n												<div><br />\r\n													</div><a href=\"{website_url}\" style=\"background:#519CEA; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;\">Click to Login</a></td>                              \r\n										</tr>                             \r\n                          \r\n									</tbody>\r\n								</table>                      </td>                  \r\n						</tr>                  \r\n                 \r\n                \r\n              \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page body end here\r\n				-->\r\n				                          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n            \r\n		<tr>      \r\n                \r\n			<td>          \r\n                    \r\n				<!--\r\n				page footer start here\r\n				-->\r\n				                       \r\n              \r\n                    \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">              \r\n                        \r\n					<tbody>                            \r\n						<tr>                                \r\n							<td style=\"height:30px;\"></td>                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;text-align:center;\">                                  Need more help?<br />\r\n												                                                     <a href=\"{contact_us_url}\" style=\"color:#519CEA;text-align:center;\">We‘re here, ready to talk</a>                              </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\">                      \r\n                                    \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                          \r\n                                        \r\n									<tbody>                                            \r\n										<tr>                              \r\n                                                \r\n											<td style=\"padding:20px 0 30px; font-size:13px; color:#999;text-align:center;\">                                  Be sure to add <a href=\"#\" style=\"color: #3d91e8\">{notifcation_email}</a> to your address book or safe sender list so our emails get to your inbox.<br />\r\n												                                                    <br />\r\n												                                                    &copy; {current_year}, {website_name}. All Rights Reserved.\r\n                                      \r\n                                  </td>                          \r\n                                            \r\n										</tr>                          \r\n                          \r\n                                        \r\n									</tbody>                                    \r\n								</table>                  </td>              \r\n                            \r\n						</tr>              \r\n                  \r\n                            \r\n						<tr>                  \r\n                                \r\n							<td style=\"padding:0; height:50px;\"></td>              \r\n                            \r\n						</tr>              \r\n                  \r\n              \r\n                        \r\n					</tbody>                    \r\n				</table>          \r\n                    \r\n				<!--\r\n				page footer end here\r\n				-->\r\n				                       \r\n          </td>    \r\n            \r\n		</tr>    \r\n        \r\n        \r\n        \r\n        \r\n	</tbody>    \r\n</table>', '{user_full_name}<br>\r\n{website_name}<br>\r\n{website_url}<br>\r\n{login_link}<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);


REPLACE INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`) VALUES (NULL, 'LBL_Change_Password_Description', '1', 'Your password changed By Admin.Please check your email . ');
