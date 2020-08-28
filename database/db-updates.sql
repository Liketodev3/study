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
