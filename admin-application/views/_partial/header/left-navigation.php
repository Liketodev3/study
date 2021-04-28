<!--left panel start here-->
<span class="leftoverlay"></span>
<aside class="leftside">
	<div class="sidebar_inner">
		<div class="profilewrap">
			<div class="profilecover">
				<figure class="profilepic"><img id="leftmenuimgtag" alt="" src="<?php echo CommonHelper::generateUrl('profile', 'profileImage', array(AdminAuthentication::getLoggedAdminId(), "THUMB", true)) . '?' . time(); ?>" alt=""></figure>
				<span class="profileinfo"><?php echo Label::getLabel('LBL_Welcome', $adminLangId); ?> <?php echo $adminName; ?></span>
			</div>

			<div class="profilelinkswrap">
				<ul class="leftlinks">
					<li class=""><a href="<?php echo CommonHelper::generateUrl('profile'); ?>"><?php echo Label::getLabel('LBL_View_Profile', $adminLangId); ?></a></li>
					<li class=""><a href="<?php echo CommonHelper::generateUrl('profile', 'changePassword'); ?>"><?php echo Label::getLabel('LBL_Change_Password', $adminLangId); ?></a></li>
					<li class=""><a href="<?php echo CommonHelper::generateUrl('profile', 'logout'); ?>"><?php echo Label::getLabel('LBL_Logout', $adminLangId); ?></a></li>
				</ul>
			</div>
		</div>

		<ul class="leftmenu">
			<!--Dashboard-->
			<?php if (
				$objPrivilege->canViewAdminDashboard(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li><a href="<?php echo CommonHelper::generateUrl(); ?>"><?php echo Label::getLabel('LBL_Dashboard', $adminLangId); ?></a></li>
			<?php } ?>

			<?php if (
				$objPrivilege->canViewUsers(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewTeacherApprovalRequests(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewWithdrawRequests(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewTeacherReviews(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Users', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewUsers(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Users'); ?>"><?php echo Label::getLabel('LBL_Users', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewTeacherApprovalRequests(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('TeacherRequests'); ?>"><?php echo Label::getLabel('LBL_Teacher_Approval_Requests', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewWithdrawRequests(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('WithdrawalRequests'); ?>"><?php echo Label::getLabel('LBL_User_Withdrwal_Requests', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewTeacherReviews(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('teacherReviews'); ?>"><?php echo Label::getLabel('LBL_Teacher_Reviews', $adminLangId); ?></a></li>
						<?php } ?>

					</ul>
				</li>

			<?php } ?>

			<?php if ($objPrivilege->canViewGroupClasses(AdminAuthentication::getLoggedAdminId(), true)) { ?>
				<li><a href="<?php echo CommonHelper::generateUrl('GroupClasses'); ?>"><?php echo Label::getLabel('LBL_Group_Classes', $adminLangId); ?></a></li>
			<?php } ?>

			<?php if ($objPrivilege->canViewPurchasedLessons(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewIssuesReported(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewGiftcards(AdminAuthentication::getLoggedAdminId(), true)) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Orders', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewPurchasedLessons(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('PurchasedLessons'); ?>"><?php echo Label::getLabel('LBL_Orders', $adminLangId); ?></a></li>
							<li><a href="<?php echo CommonHelper::generateUrl('PurchasedLessons', 'viewSchedules', ['all']); ?>"><?php echo Label::getLabel('LBL_Order_Lessons', $adminLangId); ?></a></li>
						<?php } ?>
						<?php if ($objPrivilege->canViewGiftcards(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('giftcards'); ?>"><?php echo Label::getLabel('LBL_Gift_Orders', $adminLangId); ?></a></li>
						<?php } ?>
						<?php if ($objPrivilege->canViewIssuesReported(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('IssuesReported'); ?>"><?php echo Label::getLabel('LBL_Manage_Issues_Reported', $adminLangId); ?></a></li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>
			<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Teacher_Preferences', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('preferences', 'index', array(1)); ?>"><?php echo Label::getLabel('LBL_Accents', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('preferences', 'index', array(2)); ?>"><?php echo Label::getLabel('LBL_Teaches_Level', $adminLangId); ?></a></li>
						<?php } ?>


						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('preferences', 'index', array(3)); ?>"><?php echo Label::getLabel('LBL_Learners_Ages', $adminLangId); ?></a></li>
						<?php } ?>


						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('preferences', 'index', array(4)); ?>"><?php echo Label::getLabel('LBL_Lessons_Include', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('preferences', 'index', array(5)); ?>"><?php echo Label::getLabel('LBL_Subjects', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('preferences', 'index', array(6)); ?>"><?php echo Label::getLabel('LBL_Test_preparation', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewSpokenLanguage(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('spokenLanguage'); ?>"><?php echo Label::getLabel('LBL_Spoken_Language', $adminLangId); ?></a></li>
						<?php } ?>

						<?php

						if ($objPrivilege->canViewTeachingLanguage(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('teachingLanguage'); ?>"><?php echo Label::getLabel('LBL_Teaching_Language', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewPreferences(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('issueReportOptions'); ?>"><?php echo Label::getLabel('LBL_Issue_Report_Options', $adminLangId); ?></a></li>
						<?php } ?>



					</ul>
				</li>

			<?php } ?>

			<!--CMS[-->
			<?php if (
				$objPrivilege->canViewContentPages(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewContentBlocks(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewNavigationManagement(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewTimezones(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewCountries(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewStates(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewSocialPlatforms(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewDiscountCoupons(AdminAuthentication::getLoggedAdminId(), true) ||
				//$objPrivilege->canViewCourseCategory(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewSpokenLanguage(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewLessonPackages(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewLanguageLabel(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewBibleContent(AdminAuthentication::getLoggedAdminId(), true)		||
				$objPrivilege->canViewFaq(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewFaqCategory(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Cms', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewContentPages(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('ContentPages'); ?>"><?php echo Label::getLabel('LBL_Content_Pages', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewBibleContent(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('BibleContent'); ?>"><?php echo Label::getLabel('LBL_Bible_Content', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewSlides(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('slides'); ?>"><?php echo Label::getLabel('LBL_Home_Page_Slides_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewLessonPackages(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('lesson-packages'); ?>"><?php echo Label::getLabel('LBL_Lesson_Packages_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php /*if($objPrivilege->canViewCourseCategory(AdminAuthentication::getLoggedAdminId(), true)){?>
					<li><a href="<?php echo CommonHelper::generateUrl('courseCategories'); ?>"><?php echo Label::getLabel('LBL_Course_Category',$adminLangId);?></a></li>
				<?php }*/ ?>

						<?php if ($objPrivilege->canViewBanners(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Banners'); ?>"><?php echo Label::getLabel('LBL_Banners', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewTestimonial(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Testimonials'); ?>"><?php echo Label::getLabel('LBL_Testimonials', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewNavigationManagement(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Navigations'); ?>"><?php echo Label::getLabel('LBL_Navigation_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewTimezones(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Timezones'); ?>"><?php echo Label::getLabel('LBL_Timezones_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewCountries(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Countries'); ?>"><?php echo Label::getLabel('LBL_Countries_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewStates(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('States'); ?>"><?php echo Label::getLabel('LBL_States_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewSocialPlatforms(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('SocialPlatform'); ?>"><?php echo Label::getLabel('LBL_Social_Platforms_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewDiscountCoupons(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('DiscountCoupons'); ?>"><?php echo Label::getLabel('LBL_Discount_Coupons', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewLanguageLabel(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Label'); ?>"><?php echo Label::getLabel('LBL_Language_Label', $adminLangId); ?></a></li>

						<?php } ?>
						<?php if ($objPrivilege->canViewFaq(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('faq'); ?>"><?php echo Label::getLabel('LBL_Manage_FAQs', $adminLangId); ?></a></li>

						<?php } ?>

						<?php if ($objPrivilege->canViewFaqCategory(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('FaqCategories'); ?>"><?php echo Label::getLabel('LBL_Manage_FAQ_Category', $adminLangId); ?></a></li>

						<?php } ?>

					</ul>
				</li>
			<?php } ?>
			<!-- ] -->

			<?php
			if (
				$objPrivilege->canViewBlogPostCategories(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewBlogPosts(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewBlogContributions(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewBlogComments(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Blog', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewBlogPostCategories(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('BlogPostCategories'); ?>"><?php echo Label::getLabel('LBL_Blog_Post_Categories', $adminLangId); ?></a></li>
						<?php }
						if ($objPrivilege->canViewBlogPosts(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('BlogPosts'); ?>"><?php echo Label::getLabel('LBL_Blog_Posts', $adminLangId); ?></a></li>
						<?php }
						if ($objPrivilege->canViewBlogContributions(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('BlogContributions'); ?>"><?php echo Label::getLabel('LBL_Blog_Contributions', $adminLangId); ?> <?php /* if($blogContrCount){ ?><span class='badge'>(<?php echo $blogContrCount; ?>)</span><?php } */ ?></a></li>
						<?php }
						if ($objPrivilege->canViewBlogComments(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('BlogComments'); ?>"><?php echo Label::getLabel('LBL_Blog_Comments', $adminLangId); ?> <?php /*if($blogCommentsCount){ ?><span class='badge'>(<?php echo $blogCommentsCount; ?>)</span><?php }*/ ?></a></li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>

			<!--Settings-->
			<?php if (
				$objPrivilege->canViewGeneralSettings(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewPaymentMethods(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewCurrencyManagement(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewCommissionSettings(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewEmailTemplates(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Settings', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewGeneralSettings(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('configurations'); ?>"><?php echo Label::getLabel('LBL_General_Settings', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewGeneralSettings(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('Pwa'); ?>"><?php echo Label::getLabel('LBL_PWA_Settings', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewPaymentMethods(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('PaymentMethods'); ?>"><?php echo Label::getLabel('LBL_Payment_Methods', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewCommissionSettings(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo FatUtility::generateUrl('Commission'); ?>"><?php echo Label::getLabel('LBL_Commission_Settings', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewCurrencyManagement(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('CurrencyManagement'); ?>"><?php echo Label::getLabel('LBL_Currency_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewEmailTemplates(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('EmailTemplates'); ?>"><?php echo Label::getLabel('LBL_Email_Templates_Management', $adminLangId); ?></a></li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>

			<?php if (
				$objPrivilege->canViewMetaTags(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewUrlRewrites(AdminAuthentication::getLoggedAdminId(), true)
				//|| $objPrivilege->canViewEmailArchives(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li class="haschild"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Misc', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewMetaTags(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('MetaTags'); ?>"><?php echo Label::getLabel('LBL_Meta_Tags_Management', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewUrlRewrites(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('UrlRewriting'); ?>"><?php echo Label::getLabel('LBL_Url_Rewriting', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewImageAttributes(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('ImageAttributes'); ?>"><?php echo Label::getLabel('LBL_Image_Attributes', $adminLangId); ?></a></li>
						<?php } ?>


					</ul>
				</li>
			<?php } ?>

			<!-- Report [ -->
			<?php if (
				$objPrivilege->canViewTopLangReport(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewTeacherPerformanceReport(AdminAuthentication::getLoggedAdminId(), true) ||
				$objPrivilege->canViewCommissionReport(AdminAuthentication::getLoggedAdminId(), true)
			) { ?>
				<li class="haschild">
					<a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Reports', $adminLangId); ?></a>
					<ul>
						<?php if ($objPrivilege->canViewTopLangReport(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('TopLanguagesReport'); ?>"><?php echo Label::getLabel('LBL_Top_Languages', $adminLangId); ?></a></li>
						<?php } ?>

						<?php if ($objPrivilege->canViewTeacherPerformanceReport(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('TeacherPerformanceReport'); ?>"><?php echo Label::getLabel('LBL_Teacher_Performance', $adminLangId); ?></a></li>
						<?php } ?>
						<?php
						if ($objPrivilege->canViewCommissionReport(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('CommissionReport'); ?>"><?php echo Label::getLabel('LBL_Commission_Report', $adminLangId); ?></a></li>
						<?php } ?>
						<?php if ($objPrivilege->canViewLessonStatsReport(AdminAuthentication::getLoggedAdminId(), true)) { ?>
							<li><a href="<?php echo CommonHelper::generateUrl('LessonStats'); ?>"><?php echo Label::getLabel('LBL_Lesson_Stats', $adminLangId); ?></a></li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>

			<!--  ] -->

			<!--Admin Users[-->
			<?php if ($objPrivilege->canViewAdminUsers(AdminAuthentication::getLoggedAdminId(), true) || $objPrivilege->canViewAdminUsers(AdminAuthentication::getLoggedAdminId(), true)) { ?>
				<li><a href="<?php echo CommonHelper::generateUrl('AdminUsers') ?>"><?php echo Label::getLabel('LBL_Manage_Admin_Users', $adminLangId); ?></a>
				</li>
			<?php } ?>
			<!-- ] -->
			<li class="haschild">
				<a href="javascript:void(0);"><?php echo Label::getLabel('LBL_Sitemap', $adminLangId); ?></a>
				<ul>
					<li><a href="<?php echo CommonHelper::generateUrl('Sitemap', 'generate') ?>"><?php echo Label::getLabel('LBL_Update_Sitemap', $adminLangId); ?></a></li>
					<li><a href="<?php echo CommonHelper::generateUrl('custom', 'sitemap', array(), CONF_WEBROOT_FRONT_URL) ?>" target="_blank"><?php echo Label::getLabel('LBL_View_Html', $adminLangId); ?></a></li>
					<li><a href="<?php echo CONF_WEBROOT_FRONT_URL ?>sitemap.xml" target="_blank"><?php echo Label::getLabel('LBL_View_XML', $adminLangId); ?></a></li>
				</ul>
			</li>
		</ul>
	</div>
</aside>
<!--left panel end here-->