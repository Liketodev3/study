<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box box--checkout">
	<div class="box__head">
		<a href="javascript:void(0);" class="btn btn--bordered color-black btn--back">
			<svg class="icon icon--back">
				<use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#back'; ?>"></use>
			</svg>
			<?php echo Label::getLabel('LBL_BACK'); ?>
		</a>
		<h4><?php echo Label::getLabel('LBL_Select_Your_Lesson'); ?></h4>
		<a href="javascript:void(0);" class="btn btn--bordered color-black btn--close">
			<svg class="icon icon--close">
				<use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#close'; ?>"></use>
			</svg>
		</a>
	</div>
	<div class="box__body">
		<div class="checkout-title">
			<p><?php echo Label::getLabel('LBL_CHECKOUT_SLAB_TITLE'); ?></p>
			<p> <?php echo Label::getLabel('LBL_CHECKOUT_SLAB_DESCRIPTION'); ?></p>
		</div>
		<div class="step-nav">
			<ul>
				<li class="step-nav_item is-completed"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_1'); ?></a> <span class="step-icon"></span></li>
				<li class="step-nav_item is-completed"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_2'); ?></a><span class="step-icon"></span></li>
				<li class="step-nav_item is-process"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_3'); ?></a></li>
				<li class="step-nav_item"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_4'); ?></a></li>
			</ul>
		</div>

		<div class="selection-tabs selection--checkout selection--lesson selection--onethird">
			<?php
			foreach ($slabs as $slab) {
				$price =  FatUtility::float($slab['ustelgpr_price']);
				$percentage = CommonHelper::getPercentValue($slab['top_percentage'], $price);
				$price = $price - $percentage;
				$title = Label::getLabel('LBL_{min}_to_{max}_Lesson(s)');
				$title = str_replace(['{min}', '{max}'], [$slab['ustelgpr_min_slab'], $slab['ustelgpr_max_slab']], $title);
			?>
				<label class="selection-tabs__label">
					<input type="radio" class="selection-tabs__input" name="lessonQty-pack">
					<div class="selection-tabs__title">
						<b><?php echo $title; ?></b>
						<span><?php echo CommonHelper::displayMoneyFormat($price); ?>/ <?php echo Label::getLabel('LBL_Per_Lesson'); ?></span>
					</div>
				</label>
			<?php } ?>
		</div>

		<div class="total-price">
			<form>
				<button class="btn btn--count" id="decrease" onclick="decreaseValue()"><?php echo Label::getLabel('LBL_-'); ?></button>
				<!-- <input type="text" id="number" value="0" /> -->
				<input type="text" id="lessonQty" name="lessonQty" min="<?php echo $minValue; ?>" max="<?php echo $maxValue; ?>" value="<?php echo $lessonQty;  ?>">
				<button class="btn btn--count" id="increase" onclick="increaseValue()"><?php echo Label::getLabel('LBL_+'); ?></button>
			</form>
			<p class="slab-price-js"></p>
		</div>
	</div>
	<div class="box-foot">
		<div class="box-foot__left">
			<div class="teacher-profile">
				<div class="teacher__media">
					<div class="avtar avtar-md">
						<img src="<?php echo CommonHelper::generateUrl('Image', 'user', array($teacher['user_id'])) . '?' . time(); ?>" alt="><?php echo $teacher['user_first_name'].' '.$teacher['user_last_name']; ?>">
					</div>
				</div>
				<div class="teacher__name"><?php echo $teacher['user_first_name'].' '.$teacher['user_last_name']; ?></div>
			</div>
			<div class="step-breadcrumb">
				<ul>
					<li><a href="javascript:void(0);"><?php echo $teachLangName; ?></a></li>
					<li><a href="javascript:void(0);"><?php echo sprintf(Label::getLabel('LBL_%s_Mins'), $slot); ?></a></li>
				</ul>
			</div>
		</div>
		<div class="box-foot__right">
			<a href="javascript:void(0);" class="btn btn--primary color-white"><?php echo LabeL::getLabel('LBL_NEXT'); ?></a>
		</div>
	</div>
</div>
<script>
	lessonQty  = parseInt('<?php echo $lessonQty; ?>');
	cart.lessonQty = lessonQty;
	function increaseValue() {
		var value = parseInt(document.getElementById('number').value, 10);
		value = isNaN(value) ? 0 : value;
		value++;
		document.getElementById('number').value = value;
	}

	function decreaseValue() {
		var value = parseInt(document.getElementById('number').value, 10);
		value = isNaN(value) ? 0 : value;
		value < 1 ? value = 1 : '';
		value--;
		document.getElementById('number').value = value;
	}

	$('.btn--close').click(function() {
		$('#facebox').hide();
		$('#facebox_overlay').hide();
	});
</script>