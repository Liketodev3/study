<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20">
	<div class="table-scroll">
		<table class="table myTeacherTable">
			<tbody>
				<tr class="-hide-mobile">
					<th><?php echo Label::getLabel('LBL_Teacher'); ?></th>
					<th><?php echo Label::getLabel('LBL_Location'); ?></th>
					<th><?php echo Label::getLabel('LBL_Price_Single/Bulk'); ?></th>
					<th><?php echo Label::getLabel('LBL_Action'); ?></th>
				</tr>
				<?php $i = 1;
				foreach ($favouritesData['Favourites'] as $favourite) {
					$teacherDetailPageUrl = CommonHelper::generateUrl('Teachers', 'profile') . '/' . $favourite['user_url_name'];
				?>
					<tr>
						<td width="35%">
							<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Teacher'); ?></span>
							<span class="td__data">
								<div class="profile-info align-items-center">
									<a title="<?php echo $favourite['user_first_name'] . ' ' . $favourite['user_last_name']; ?>" href="<?php echo $teacherDetailPageUrl; ?>">
										<div class="avtar avtar--normal" data-text="<?php echo CommonHelper::getFirstChar($favourite['user_first_name']); ?>">
											<?php
											if (true == User::isProfilePicUploaded($favourite['uft_teacher_id'])) {
												$img = CommonHelper::generateUrl('Image', 'user', array($favourite['uft_teacher_id']), CONF_WEBROOT_FRONT_URL) . '?' . time();
												echo '<img src="' . $img . '" />';
											}
											?>
										</div>
									</a>

									<div class="profile-info__right">
										<h6><a href="<?php echo $teacherDetailPageUrl; ?>"><?php echo $favourite['user_first_name'] . ' ' . $favourite['user_last_name']; ?></a></h6>

										<div class="my_teacher_langauges">

											<?php
											if ($favourite['teacherTeachLanguageName'] != '' && !empty($favourite['teacherTeachLanguageName'])) {
												$teachLangs = explode(',', $favourite['teacherTeachLanguageName']);
												//print_r( $languages );
												if (count($teachLangs) > 1) {
													$first_array = array_slice($teachLangs, 0, 1);
													$second_array = array_slice($teachLangs, 1, count($teachLangs));
											?>
													<div class="language">
														<p class="my_teacher_lang_heading"><span><?php
																									echo Label::getLabel('LBL_Teaches:'); ?></span></p>
														<?php foreach ($first_array as $teachLang) {  ?>
															<span class="main-language"><?php echo $teachLang; ?></span>
														<?php } ?>
														<ul>
															<li><span class="plus">+</span>
																<div class="more_listing">
																	<ul>
																		<?php foreach ($second_array as $teachLang) {  ?>
																			<li><a><?php echo $teachLang; ?></a></li>
																		<?php } ?>
																	</ul>
																</div>
															</li>
														</ul>
													</div>
													<?php
												} else {
													echo '<div class="language">';
													foreach ($teachLangs as $teachLang) {  ?>
														<span class="main-language"><?php echo $teachLang; ?></span>
											<?php
													}
													echo '</div>';
												}
											}
											?>
										</div>



									</div>
								</div>
							</span>
						</td>

						<td>
							<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Location'); ?></span>
							<span class="td__data"><?php echo $countriesArr[$favourite['user_country_id']]; ?></span>
						</td>
						<td>
							<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Price_Single/Bulk'); ?></span>

							<span class="-display-inline"><?php echo CommonHelper::displayMoneyFormat($favourite['singleLessonAmount']); ?> / <?php echo CommonHelper::displayMoneyFormat($favourite['bulkLessonAmount']); ?></span>


							<span class="td__data">
								<?php if ($favourite['isSetUpOfferPrice']) { ?>
									<span class="inline-icon -display-inline -color-fill">
										<span class="svg-icon" title="<?php echo Label::getLabel('LBL_These_Prices_are_locked'); ?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 520 520">
												<path d="M265,9A130.148,130.148,0,0,0,135,139v92h30V139a100,100,0,0,1,200,0v92h30V139A130.147,130.147,0,0,0,265,9ZM85,231V521H445V231H85ZM280,384.42V446H250V384.42A45,45,0,1,1,280,384.42ZM265,327a15,15,0,1,0,15,15A15.017,15.017,0,0,0,265,327Z" transform="translate(-5 -5)"></path>
											</svg>
										</span>
									</span>
								<?php } ?>
							</span>
						</td>

						<td>
							<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Action'); ?></span>
							<span class="td__data">
								<a href="javascript:void(0)" onclick="toggleTeacherFavorite(<?php echo $favourite['uft_teacher_id']; ?>,this)" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Unfavorite'); ?></a>
							</span>
						</td>
					</tr>
				<?php $i++;
				} ?>

			</tbody>
		</table>
		<?php
		echo FatUtility::createHiddenFormFromData($postedData, array(
			'name' => 'frmOrderSearchPaging'
		));
		$this->includeTemplate('_partial/pagination.php', $favouritesData['pagingArr'], false); ?>
	</div>
	<?php if (count($favouritesData['Favourites']) == 0) {
		$this->includeTemplate('_partial/no-record-found.php');
	} ?>
</div>
<script>

</script>