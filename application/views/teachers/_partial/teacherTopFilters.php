<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$frmTeacherSrch->setFormTagAttribute('onSubmit', 'searchTeachers(this); return(false);');
echo $frmTeacherSrch->getFormTag();

$frmTeacherSrch->getField('teach_language_name')->setFieldTagAttribute('class', 'form__input');
$frmTeacherSrch->getField('teach_availability')->setFieldTagAttribute('class', 'form__input form__input-js');
$pageFld = $frmTeacherSrch->getField('page');
$frmTeacherSrch->getField('teach_availability')->setFieldTagAttribute('autocomplete', 'off');
$frmTeacherSrch->getField('teach_availability')->setFieldTagAttribute('readonly', 'readonly');
$keywordfld =   $frmTeacherSrch->getField('keyword');
$keywordfld->setFieldTagAttribute('class', 'form__input');
$keywordfld->setFieldTagAttribute('id', 'keyword');
$frmTeacherSrch->getField('btnTeacherSrchSubmit')->setFieldTagAttribute('class', 'form__action');
?>

<form>
	<div class="main__head">
		<div class="container container--narrow">
			<div class="filter-wrapper">
				<div class="filter-form">
					<div class="filter__primary">
						<div class="filter-form__inner">
							<div class="filter__head filter__head-trigger filter-trigger-js">
								<svg class="icon icon--language">
									<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#language'; ?>"></use>
								</svg>
								<input class="filter__input filter__input-js" name="teach_language_name" val="<?php echo ($keywordlanguage)?$keywordlanguage:''; ?>" type="text" placeholder="<?php echo ($keywordlanguage)?$keywordlanguage:Label::getLabel('LBL_Language_Placeholder',$siteLangId);?>">
								<input name="teachLangId" val="" type="hidden">
								<?php echo $pageFld->getHtml(); ?>
							</div>
							<div class="filter__body filter__body-target filter-target-js" style="display: none;">
								<div class="dropdown-listing">
									<ul>
										<?php foreach ($teachLangs as $teachLangId => $teachLangName) { ?>
											<li <?php echo ($teachLangName == $keywordlanguage)?'class="is--active"':'' ?>><a href="javascript:void(0)" class="select-teach-lang-js" teachLangId="<?php echo $teachLangId; ?>"><?php echo $teachLangName; ?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
						<div class="filter-form__inner">
							<div class="filter__head filter__head-trigger filter-trigger-js">
								<svg class="icon icon--availbility">
									<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#availability'; ?>"></use>
								</svg>
								<h6><?php echo Label::getLabel("LBL_Availability", $siteLangId) ?></h6>
							</div>
							<div class="filter__body filter__body-target filter-target-js" style="display: none;">
								<div class="dropdown-availbility">
									<div class="availbility-title"><?php echo Label::getLabel('LBL_Days_Of_Week', $siteLangId); ?></div>
									<div class="selection-tabs selection--weeks">
										<?php foreach ($daysArr as $dayId => $dayName) { ?>
											<label class="selection-tabs__label" id="day_<?php echo $dayId; ?>">
												<input type="checkbox" name="filterWeekDays[]" value="<?php echo $dayId; ?>" class="selection-tabs__input">
												<div class="selection-tabs__title"><span class="name"><?php echo $dayName; ?></span></div>
											</label>
										<?php } ?>
									</div>

									<div class="-gap"></div>

									<div class="availbility-title days"><?php echo Label::getLabel('LBL_Time_of_Days', $siteLangId) ?></div>
									<div class="selection-tabs selection--days">
										<?php foreach ($timeSlotArr as $timeSlotId => $timeSlotName) { ?>
											<label class="selection-tabs__label" id="slot_<?php echo $timeSlotId; ?>">
												<input type="checkbox" name="filterTimeSlots[]" value="<?php echo $timeSlotId; ?>" class="selection-tabs__input">
												<div class="selection-tabs__title"><span class="name"><?php echo $timeSlotName; ?></span></div>
											</label>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="filter-form__inner">
							<div class="filter__head filter__head-trigger filter-trigger-js">
								<svg class="icon icon--price-tag">
									<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#price-tag' ?>"></use>
								</svg>
								<h6><?php echo Label::getLabel('LBL_Price', $siteLangId); ?></h6>
							</div>
							<div class="filter__body filter__body-target filter-target-js" style="display: none;">
								<div class="dropdown-price">
									<input type="text" id="price_range" value="<?php echo $minPrice; ?>-<?php echo $maxPrice; ?>" name="price_range" />
									<input type="hidden" value="<?php echo $minPrice; ?>" name="filterDefaultMinValue" id="filterDefaultMinValue" />
									<input type="hidden" value="<?php echo $maxPrice; ?>" name="filterDefaultMaxValue" id="filterDefaultMaxValue" />
									<div class="price-field">
										<div class="input-field">
											<span><?php echo CommonHelper::getCurrencySymbolRight() ? CommonHelper::getCurrencySymbolRight() : CommonHelper::getCurrencySymbolLeft(); ?></span>
											<input type="number" name="priceFilterMinValue" value="<?php echo $priceArr['minPrice']; ?>">
										</div>
										<div class="input-field">
											<span><?php echo CommonHelper::getCurrencySymbolRight() ? CommonHelper::getCurrencySymbolRight() : CommonHelper::getCurrencySymbolLeft(); ?></span>
											<input type="number" name="priceFilterMaxValue" value="<?php echo $priceArr['maxPrice']; ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="filter-form__inner filter--search">
							<div class="filter__head">
								<input type="text" name="keyword" id="keyword" placeholder="<?php echo Label::getLabel('LBL_Search_By_Name_And_Keword'); ?>">
								<svg class="icon icon--search">
									<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#search'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>

					<div class="filter__secondary">
						<span class="overlay overlay--filters btn--filters-js"></span>
						<div class="filter-group">
							<div class="filter-group__inner">
								<div class="filter__head filter__head-trigger filter-trigger-js">
									<h6><?php echo Label::getLabel('LBL_Location', $siteLangId); ?></h6>
								</div>

								<div class="filter__body filter__body-target filter-target-js" style="display: none;">
									<div class="listing-dropdown">
										<ul>
											<?php foreach ($fromArr as $countryId => $countryName) { ?>
												<li>
													<label id="location_<?php echo $countryName['user_country_id']; ?>"><span class="checkbox"><input <?php echo (in_array($countryName['user_country_id'],$fromCountry_filter)? "checked='checked'": "") ?> type="checkbox" name="filterFromCountry[]" value="<?php echo $countryName['user_country_id']; ?>"><i class="input-helper"></i></span><span class="name"><?php echo $countryName['country_name']; ?></span></label>
												</li>
											<?php  } ?>

										</ul>
									</div>
								</div>
							</div>

							<div class="filter-group__inner">
								<div class="filter__head filter__head-trigger filter-trigger-js">
									<h6><?php echo Label::getLabel('LBL_Speaks', $siteLangId); ?></h6>
								</div>
								<div class="filter__body filter__body-target filter-target-js" style="display: none;">
									<div class="listing-dropdown">
										<ul>
											<?php foreach ($spokenLangsArr as $spokenLangId => $spokenLangName) { ?>
												<li>
													<label id="spoken_<?php echo $spokenLangId; ?>""><span class="checkbox"><input <?php echo (in_array($spokenLangId,$spokenLanguage_filter)? "checked='checked'": "") ?> type="checkbox" name="filterSpokenLanguage[]" value="<?php echo $spokenLangId; ?>"><i class="input-helper"></i></span><span class="name"><?php echo $spokenLangName; ?></span></label>
												</li>
											<?php } ?>

										</ul>
									</div>
								</div>
							</div>
							
							<div class="filter-group__inner">
								<div class="filter__head filter__head-trigger filter-trigger-js">
									<h6><?php echo Label::getLabel('LBL_Gender', $siteLangId); ?></h6>
								</div>
								<div class="filter__body filter__body-target filter-target-js" style="display: none;">
									<div class="listing-dropdown">
										<ul>
											<?php foreach ($genderArr as $genderId => $genderName) { ?>
												<li>
													<label id="gender_"<?php echo $genderId; ?>><span class="checkbox"><input <?php echo (in_array($genderId,$gender_filter)? "checked='checked'": "") ?> type="checkbox" name="filterGender[]" value="<?php echo $genderId; ?>"><i class="input-helper"></i></span><span class="name"><?php echo $genderName; ?></span></label>
												</li>
											<?php } ?>

										</ul>
									</div>
								</div>
							</div>

							<?php foreach ($preferenceTypeArr as $key => $preferenceType) {
								if (!isset($allPreferences[$key])) {
									continue;
								} ?>
								<div class="filter-group__inner">
									<div class="filter__head filter__head-trigger filter-trigger-js">
										<h6><?php echo $preferenceType; ?></h6>
									</div>
									<div class="filter__body filter__body-target filter-target-js" style="display: none;">
										<div class="listing-dropdown">
											<ul>
												<?php foreach ($allPreferences[$key] as $preference) { ?>
													<li>
														<label id="prefrence_<?php echo $preference['preference_id']; ?>"><span class="checkbox"><input <?php echo (in_array($preference['preference_id'],$preferenceFilter_filter)? "checked='checked'": "") ?> type="checkbox" name="filterPreferences[]" value="<?php echo $preference['preference_id']; ?>"><i class="input-helper"></i></span><span class="name"><?php echo $preference['preference_titles']; ?></span></label>
													</li>
												<?php } ?>

											</ul>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="filter-tags" <?php echo ($keywordlanguage != '' || ($minPrice != ($priceArr['minPrice']??0)  || $maxPrice != ($priceArr['maxPrice'] ??0))|| $keyword != '') ? 'style="display:block"':'style="display:none"' ?>>
		<div class="container container--narrow">
			<div class="filter-tags-list" id="searched-filters">
				<ul>
					<?php if ($keywordlanguage != '') { ?>
						<li>
							<a href="javascript:void(0);" class="language_keyword tag__clickable " onclick="removeFilterCustom('language_keyword',this)">
								<?php echo Label::getLabel('LBL_Language'); ?> : <?php echo $keywordlanguage; ?> </a>
						</li>
					<?php } ?>
					<?php if ((isset($minPrice) && isset($maxPrice)) && ($minPrice != 0 && $maxPrice != 0) && ($minPrice != $priceArr['minPrice']  || $maxPrice != $priceArr['maxPrice'])) { ?>
						<li>
							<a href="javascript:void(0)" class="price tag__clickable" onclick="removePriceFilterCustom(this, '<?= ($priceArr['minPrice']); ?>', '<?= ($priceArr['maxPrice']); ?>')">
								<?php echo Label::getLabel('LBL_Price'); ?>: <?php echo CommonHelper::getCurrencySymbolRight() ? CommonHelper::getCurrencySymbolRight() : CommonHelper::getCurrencySymbolLeft(); ?><?= CommonHelper::displayMoneyFormat(($minPrice) ?? 0, false, false, false, false, false); ?> - <?php echo CommonHelper::getCurrencySymbolRight() ? CommonHelper::getCurrencySymbolRight() : CommonHelper::getCurrencySymbolLeft(); ?><?= CommonHelper::displayMoneyFormat(($maxPrice) ?? 0, false, false, false, false, false); ?></a>
						</li>
					<?php } ?>
					<?php if ($keyword != '') { ?>
						<li>
							<a href="javascript:void(0);" class="userKeyword tag__clickable " onclick="removeFilterUser('userKeyword',this)">
								<?php echo Label::getLabel('LBL_User'); ?> : <?php echo $keyword; ?> </a>
						</li>
					<?php } ?>

					<li class="clear-filter"><a href="javascript:void(0);" onclick="removeAllFilters();"><?php echo Label::getLabel('LBL_Clear_All_Filters',$siteLangId); ?></a></li>
				</ul>
			</div>
		</div>
	</div>

</form>

</form>
<?php
echo $frmTeacherSrch->getExternalJS();
?>
<script>
	$(document).ready(function() {
		$('.block__head-trigger-js').click(function() {
			if ($(this).hasClass('is-active')) {
				$(this).removeClass('is-active');
				$(this).siblings('.block__body-target-js').slideUp();
				return false;
			}

			$(this).find('.block__head-trigger-js').removeClass('is-active');
			$(this).addClass("is-active");
			$(this).siblings('.block__body-target-js').slideUp();
			$(this).siblings('.block__body-target-js').slideDown();
		});


		$('.scrollbar-js').enscroll({
			verticalTrackClass: 'scrollbar-track',
			verticalHandleClass: 'scrollbar-handle'
		});

		<?php if (isset($priceArr) && $priceArr) { ?>
			var range,
				min = <?php echo $filterDefaultMinValue; ?>,
				max = <?php echo $filterDefaultMaxValue; ?>,
				from,
				to;
			var $from = $('input[name="priceFilterMinValue"]');
			var $to = $('input[name="priceFilterMaxValue"]');
			var $range = $("#price_range");
			var updateValues = function() {
				$from.prop("value", from);
				$to.prop("value", to);
			};
			step = 2;
			if (0 < min && 1 > min) {
				step = 0.02;
			}

			$("#price_range").ionRangeSlider({
				hide_min_max: true,
				hide_from_to: true,
				keyboard: true,
				min: min,
				max: max,
				from: <?php echo $minPrice;  ?>,
				to: <?php echo $maxPrice; ?>,
				step: step,
				type: 'double',
				prettify_enabled: true,
				prettify_separator: ',',
				grid: true,
				prefix: '<?php echo $currencySymbolLeft; ?>',
				postfix: '<?php echo $currencySymbolRight; ?>',
				input_values_separator: '-',
				onFinish: function() {
					var minMaxArr = $("#price_range").val().split('-');
					if (minMaxArr.length == 2) {
						var min = Number(minMaxArr[0]);
						var max = Number(minMaxArr[1]);
						$('input[name="priceFilterMinValue"]').val(min);
						$('input[name="priceFilterMaxValue"]').val(max);
						return addPricefilter();
						//return searchProducts(document.frmProductSearch);
					}

				},
				onChange: function(data) {
					from = data.from;
					to = data.to;
					updateValues();
				}
			});


			range = $range.data("ionRangeSlider");

			var updateRange = function() {
				range.update({
					from: from,
					to: to
				});
				addPricefilter();
			};

			$from.on("change", function() {
				from = $(this).prop("value");
				if (!$.isNumeric(from)) {
					from = 0;
				}
				if (from < min) {
					from = min;
				}
				if (from > max) {
					from = max;
				}

				updateValues();
				updateRange();
			});

			$to.on("change", function() {
				to = $(this).prop("value");
				if (!$.isNumeric(to)) {
					to = 0;
				}
				if (to > max) {
					to = max;
				}
				if (to < min) {
					to = min;
				}
				updateValues();
				updateRange();
			});
		<?php } ?>
	});
</script>
