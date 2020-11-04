CREATE TABLE `tbl_user_cookie_consent` (
  `usercc_user_id` int(11) NOT NULL,
  `usercc_settings` varchar(255) NOT NULL,
  `usercc_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user_cookie_preferences`
--
ALTER TABLE `tbl_user_cookie_consent`
  ADD PRIMARY KEY (`usercc_user_id`);


REPLACE INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_Cookie_settings_update_successfully', '1', 'Cookie settings updated succesfully.')
, ('LBL_COOKIE_CONSENT_HEADING', '1', 'Cookie Consent')
, ('LBL_STATISTICS_COOKIE_DESCRIPTION_TEXT', '1', 'These cookies allow us to count visits and traffic sources so we can measure and improve the performance of our site. They help us to know which pages are the most and least popular and see how visitors move around the site. All information these cookies collect is aggregated and therefore anonymous. If you do not allow these cookies we will not know when you have visited our site, and will not be able to monitor its performance.')
, ('LBL_PREFERENCES_COOKIE_DESCRIPTION_TEXT', '1', 'These cookies enable the website to provide enhanced functionality and personalisation. If you do not allow these cookies then some or all of these services may not function properly.')
, ('LBL_NECESSARY_COOKIE_DESCRIPTION_TEXT', '1', 'These cookies are necessary for the website to function and cannot be switched off in our systems. They are usually only set in response to actions made by you which amount to a request for services, such as setting your privacy preferences, logging in or filling in forms. You can set your browser to block or alert you about these cookies, but some parts of the site will not then work. These cookies do not store any personally identifiable information.')
;
