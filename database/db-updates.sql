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

REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_TRIAL_LESSON_%S_MINS', '1', 'One time, %s minutes');
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.12.1.20210503' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.13.0.20210510' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';

UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.5.20210421' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.8.6.20210507' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
