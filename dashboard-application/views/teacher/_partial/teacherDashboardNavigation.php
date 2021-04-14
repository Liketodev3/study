<div class="menu-group">
	<h6 class="heading-6"><?php echo label::getLabel('LBL_Profile'); ?></h6>
	<nav class="menu menu--primary">
		<ul>
			<li class="menu__item <?php echo ($controllerName == "Teacher") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('Teacher'); ?>">
					<svg class="icon icon--dashboard margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#dashboard'; ?>"></use></svg>
					<span><?php echo Label::getLabel('LBL_Dashboard'); ?></span>
				</a>
			</li>
			<li class="menu__item <?php echo ($controllerName == "Account") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('Account', 'ProfileInfo');?>">
					<svg class="icon icon--settings margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#settings'; ?>"></use></svg>
					<span><?php echo Label::getLabel('LBL_Account_Settings'); ?></span>
				</a>
			</li>
			<li class="menu__item">
				<a href="#">
					<svg class="icon icon--calendar margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#calendar'; ?>"></use></svg>
					<span><?php echo Label::getLabel('LBL_Availability_Calendar'); ?></span>
				</a>
			</li>
		</ul>
	</nav>
</div>
<div class="menu-group">
	<h6 class="heading-6"><?php echo Label::getLabel('Lbl_Booking'); ?></h6>
	<nav class="menu menu--primary">
		<ul>
			<li class="menu__item <?php echo ($controllerName == "TeacherScheduledLessons") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('TeacherScheduledLessons'); ?>">
					<svg class="icon icon--lesson margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#lessons'; ?>"></use></svg>
					<span><?php echo Label::getLabel('Lbl_Lessons'); ?></span>
				</a>
			</li>
			<li class="menu__item <?php echo ($controllerName == "TeacherLessonsPlan") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('TeacherLessonsPlan'); ?>">
					<svg class="icon icon--lessons margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#lessons-plan'; ?>"></use></svg>
					<span><?php echo Label::getLabel('Lbl_Lesson_Plan'); ?></span>
				</a>
			</li>
			<li class="menu__item <?php echo ($controllerName == "TeacherGroupClasses") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('TeacherGroupClasses'); ?>">
					<svg class="icon icon--group-classes margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#group-classes'; ?>"></use></svg>
					<span><?php echo Label::getLabel('Lbl_Group_Classes'); ?></span>
				</a>
			</li>
			<li class="menu__item <?php echo ($controllerName == "TeacherStudents") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('TeacherStudents'); ?>">
					<svg class="icon icon--students margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#students'; ?>"></use></svg>
					<span><?php echo Label::getLabel('Lbl_Students'); ?></span>
				</a>
			</li>
		</ul>
	</nav>
</div>
<div class="menu-group">
	<h6 class="heading-6"><?php echo Label::getLabel('Lbl_History'); ?></h6>
	<nav class="menu menu--primary">
		<ul>
			<li class="menu__item <?php echo ($controllerName == "Teacher" && $actionName == "orders") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('Teacher', 'orders'); ?>">
					<svg class="icon icon--orders margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#orders'; ?>"></use></svg>
					<span><?php echo Label::getLabel('Lbl_Orders'); ?></span>
				</a>
			</li>
			<li class="menu__item <?php echo ($controllerName == "Wallet") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('Wallet'); ?>">
					<svg class="icon icon--wallet margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#wallet'; ?>"></use></svg>
					<span><?php echo Label::getLabel('Lbl_Wallet'); ?></span>
					<!-- Wallet <span>($250.00) -->
				</a>
			</li>
		</ul>
	</nav>
</div>

<div class="menu-group">
	<h6 class="heading-6"><?php echo Label::getLabel('Lbl_Others'); ?></h6>
	<nav class="menu menu--primary">
		<ul>
		<?php if (FatApp::getConfig('CONF_ENABLE_FLASHCARD', FatUtility::VAR_BOOLEAN, true)) { ?>	
			<li class="menu__item <?php echo ($controllerName == "FlashCards") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('FlashCards'); ?>">
					<svg class="icon icon--flash-cards margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#flashcards'; ?>"></use></svg>
					<span><?php echo Label::getLabel('LBL_Flash_Cards'); ?></span>
				</a>
			</li>
		<?php } ?>
			<li class="menu__item <?php echo ($controllerName == "Giftcard") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('Giftcard'); ?>">
					<svg class="icon icon--gifts-cards margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#giftcards'; ?>"></use></svg>
					<span><?php echo Label::getLabel('LBL_Gift_Cards'); ?></span>
				</a>
			</li>
			<li class="menu__item <?php echo ($controllerName == "TeacherIssueReported") ? 'is-active' : ''; ?>">
				<a href="<?php echo CommonHelper::generateUrl('TeacherIssueReported'); ?>">
					<svg class="icon icon--issue margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#issue'; ?>"></use></svg>
					<span><?php echo Label::getLabel('LBL_Issue_Reported'); ?></span>
				</a>
			</li>
		</ul>
	</nav>
</div>