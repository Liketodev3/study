<h2 class="-color-secondary"><?php echo $soldLessons['lessonCount'] . ' ' . Label::getLabel('LBL_Lessons', $siteLangId); ?></h2> <?php
																																	$user_timezone = MyDate::getUserTimeZone();
																																	$systemTimeZone = MyDate::getTimeZone();
																																	if ($soldLessons['fromDate']) {
																																		echo '<div>' . MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $soldLessons['fromDate'], true, $user_timezone)  . ' - </div> <br> <div>' . MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $soldLessons['toDate'], true, $user_timezone) . '</div>';
																																	}
																																	?>