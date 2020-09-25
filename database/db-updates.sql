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
