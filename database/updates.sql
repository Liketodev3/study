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

-- task id 74969 - date 16-july TV-1.3.0.20200706

--
-- Table structure for table `lesson_reschedule_log`
--

CREATE TABLE `tbl_lesson_reschedule_log` (
  `lesreschlog_id` int(11) NOT NULL,
  `lesreschlog_slesson_id` int(11) NOT NULL,
  `lesreschlog_reschedule_by` int(11) NOT NULL,
  `lesreschlog_user_type` int(11) NOT NULL,
  `lesreschlog_comment` TEXT NOT NULL,
  `lesreschlog_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lesson_reschedule_log`
--
ALTER TABLE `tbl_lesson_reschedule_log`
  ADD PRIMARY KEY (`lesreschlog_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lesson_reschedule_log`
--
ALTER TABLE `tbl_lesson_reschedule_log`
  MODIFY `lesreschlog_id` int(11) NOT NULL AUTO_INCREMENT;

  -- TV-2.0.0.20200720 , bug 040354, 24-july-2020

ALTER TABLE `tbl_configurations` CHANGE `conf_val` `conf_val` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `tbl_order_products` ADD `op_refund_qty` INT NOT NULL AFTER `op_commission_percentage`, ADD `op_total_refund_amount` DECIMAL(10,2) NOT NULL AFTER `op_refund_qty`;
