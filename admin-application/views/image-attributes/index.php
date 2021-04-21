<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">
					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Image_Attributes', $adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div>

				<div class="tabs_nav_container vertical">
					<ul class="tabs_nav">
						<?php $itr = 0;
						foreach ($tabsArr as $imageAttributeKey => $imageAttributeVal) {
						?>
							<li><a class="<?php echo ($activeTab == $imageAttributeKey) ? 'active' : '' ?>" href="javascript:void(0)" onClick="listImageAttributes(<?php echo "'$imageAttributeKey'"; ?>)"><?php echo $imageAttributeVal; ?></a></li>
						<?php $itr++;
						} ?>
					</ul>
					<div class="tabs_panel_wrap">
						<div class="tabs_nav_container" id="frmBlock">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>