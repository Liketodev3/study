UPDATE `tbl_configurations` SET `conf_val` = '1' WHERE `conf_name` = 'CONF_USE_SSL';
UPDATE `tbl_configurations` SET `conf_val` = '15,30,45,60,90,120' WHERE `conf_name` = 'CONF_PAID_LESSON_DURATION';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.12.0.20210426' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES ('LBL_TRIAL_LESSON_%S_MINS', '1', 'One time, %s minutes');
UPDATE `tbl_configurations` SET `conf_val` = 'TV-2.12.1.20210503' WHERE `conf_name` = 'CONF_YOCOACH_VERSION';
