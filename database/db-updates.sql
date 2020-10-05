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

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('MSG_Money_added_to_wallet', 1, '<p>Your order has been successfully processed!</p><p>Please direct any questions you have to the <a href=\"{contact-us-page-url}\">web portal owner</a>.</p><p>Thanks for choosing us online!');


UPDATE `tbl_content_pages_block_lang` SET `cpblocklang_text` = '<section class=\"section section--white section--centered\">\r\n	<div class=\"container container--narrow container--cms\">\r\n		<div class=\"section__body\" style=\"\">\r\n			<!--\r\n			------ First Section -------\r\n			-->\r\n			\r\n			<div class=\"row justify-content-center -align-center\">\r\n				<div class=\"col-xl-10 col-lg-12 col-md-12\">\r\n					<div class=\"row\">\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/public/images/icon_mission.svg\" alt=\"\" /></div>\r\n							<h4>The Mission</h4>\r\n							<p>For I (YoCoach) have a great sense of obligation to people in both the civilized world and the rest of the world, to the educated and the uneducated alike. Romans 1:14</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<div class=\"icon\"><img src=\"/public/images/icon_vision.svg\" alt=\"\" /></div>\r\n							<h4>The Vision</h4>\r\n							<p>Where there is no vision, the people perish; Proverbs 29:18</p>\r\n							<p>Write the vision and make it plain on tablets, That he may run who reads it. For the vision is yet for an appointed time; But at the end it will speak, and it will not lie. Though it tarries, wait for it; Because it will surely come, It will not tarry. Habakkuk 2:2</p></div></div><span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span><span class=\"-gap\"></span>\r\n					<div class=\"-align-center section__head\">\r\n						<h2>The Yocoach</h2></div>\r\n					<div class=\"row\">\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<p style=\"text-align:left;\">YoCoach’s mission is to help people all over the world to connect, communicate and learn in the language of their choice.&nbsp; Our goal is to share cultures and provide online language learning that is fun, engaging and to equip students with the skills to speak clearly and confidently in their chosen language of study.&nbsp; Language changes many things for each student and our mission is to ensure quality learning and retention to expand learning knowledge for people. Education changes whole communities and builds the future of our next generation. We want to be a part of that change in a world of languages.</p></div>\r\n						<div class=\"col-xl-6 col-lg-6 col-md-12 icon-col\">\r\n							<p style=\"text-align:left;\">The vision came to the Founder and CEO Kelly C. while living in Ecuador. Kelly taught English online and volunteered to help young learners in a Village called Vilcabamba. On February 17th of 2018 Kelly had a vision of creating a platform for teachers and students to offer the freedom to teach and learn online, help teachers to earn money from home and create a safer online global learning community. After two years experiencing South America, it became evident that YoCoach needs to reach out globally and help those people who are struggling to live in a world that is so demanding. Our mission and vision is to give back to the world in need, help the poor and feed the hungry. Every dollar brought to the platform will be a precious tool to help feed, clothe, house and help families. Teaching and learning with YoCoach is more than education; It\'s global change for people: Help us be that CHANGE!</p></div></div></div></div>\r\n			<!--\r\n			------------\r\n			-->\r\n			</div></div></section>\r\n<section class=\"section section--grey -align-center\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"section__head\">\r\n			<h2>The Team</h2></div>\r\n		<div class=\"section__body\">\r\n			<div class=\"row justify-content-center\">\r\n				<div class=\"col-xl-9 col-lg-12\">\r\n					<div class=\"row\">\r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_2.jpg\" alt=\"\" /></div>\r\n							<h4>Kirstin</h4>\r\n							<p>Customer Executive</p></div>\r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_5.jpg\" alt=\"\" /></div>\r\n							<h4>Cooper</h4>\r\n							<p>Product Design</p></div>\r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_4.jpg\" alt=\"\" /></div>\r\n							<h4>Andrew</h4>\r\n							<p>Marketing</p></div>\r\n						<div class=\"col-xl-3 col-lg-3 col-md-3 team\">\r\n							<div class=\"team__media\"><img src=\"/public/images/300x300_3.jpg\" alt=\"\" /></div>\r\n							<h4>Mikael</h4>\r\n							<p>Product Developer</p></div></div></div></div></div></div></section>\r\n<section class=\"section section--white section--hiw\">\r\n	<div class=\"container container--narrow\">\r\n		<div class=\"section-title\">\r\n			<h2>How It Works</h2></div>\r\n		<div class=\"row justify-content-between\">\r\n			<div class=\"col-xl-4 col-lg-5 col-md-12 col-sm-12\">\r\n				<div class=\"tabs-vertical tabs-js\">\r\n					<ul>\r\n						<li class=\"is-active\" data-href=\"#tab1\">\r\n							<div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n								<div class=\"tab-info\">\r\n									<h3>Browse</h3> \r\n									<p>Browse through hundreds of teachers.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n						<li class=\"\" data-href=\"#tab2\">\r\n							<div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n								<div class=\"tab-info\">\r\n									<h3>Book</h3> \r\n									<p>Book lessons with the best teacher for you.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n						<li class=\"\" data-href=\"#tab3\">\r\n							<div class=\"tab-wrap\"> <span class=\"counter\"></span> \r\n								<div class=\"tab-info\">\r\n									<h3>Start</h3> \r\n									<p>Log in to YoCoach and start learning.</p> <a href=\"https://www.italki.com/home\" class=\"btn btn--primary\">Find a Teacher</a> </div></div></li>\r\n					</ul></div></div>\r\n			<div class=\"col-xl-7 col-lg-7 col-md-12 col-sm-12 col__content\">\r\n				<div id=\"tab1\" class=\"tabs-content-js\" style=\"display: block;\">\r\n					<div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/4/0/3\" alt=\"\" /></a></div></div>\r\n				<div id=\"tab2\" class=\"tabs-content-js\" style=\"display: none;\">\r\n					<div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/5/0/3\" alt=\"\" /></a></div></div>\r\n				<div id=\"tab3\" class=\"tabs-content-js\" style=\"display: none;\">\r\n					<div class=\"media\"><a href=\"\" target=\"_self\"><img src=\"/image/show-banner/6/0/3\" alt=\"\" /></a></div></div></div></div></div></section>\r\n<section class=\"section section--white\">             \r\n	<div class=\"container container--narrow -align-center\">                 \r\n		<h2 class=\"-style-bold\">Looking forward to meeting<br />\r\n			  your new students?</h2>                 <span class=\"-gap\"></span>                 <a href=\"#\" class=\"btn btn--primary btn--large\">Start Teaching</a>             </div>         </section>' WHERE `tbl_content_pages_block_lang`.`cpblocklang_id` = 1;
