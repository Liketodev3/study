<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>

<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">
					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Manage_Lessons',$adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div>
				<section class="section searchform_filter">
					<div class="sectionhead">
						<h4> <?php echo Label::getLabel('LBL_Search...',$adminLangId); ?></h4>
					</div>
					<div class="sectionbody space togglewrap" style="display:none;">
						<?php
						$searchForm->setFormTagAttribute ( 'onsubmit', 'searchPuchasedLessons(this,1); return(false);');
						$searchForm->setFormTagAttribute ( 'class', 'web_form' );
						$searchForm->developerTags['colClassPrefix'] = 'col-md-';
						$searchForm->developerTags['fld_default_col'] = 6;

						$fld = $searchForm->getField('btn_clear');
						$fld->addFieldTagAttribute('onclick','clearPuchasedLessonSearch()');
						echo  $searchForm->getFormHtml();
							?>
					</div>
				</section>

				<section class="section">
					<div class="sectionbody">
						<div class="tablewrap" >
							<div id="lessonListing">
								<?php echo Label::getLabel('LBL_Processing...',$adminLangId); ?>
							</div>
						</div>
					</div>
				</section>
		</div>
	</div>
</div>
</div>
