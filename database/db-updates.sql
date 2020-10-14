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