<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section section--grey section--page">
	 <div class="container container--fixed">

	   <div class="page-panel -clearfix">

			<!--panel left start here-->
			<div class="page-panel__left">
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
			</div>
            <!--panel left end here-->


			<!--panel right start here-->
		   <div class="page-panel__right">

				 <!--page-head start here-->
				 <div class="page-head">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h1><?php echo Label::getLabel('LBL_My_Group_Classes'); ?></h1></div>
                    </div>
				 </div>
				 <!--page-head end here-->

				 <!--page filters start here-->
				 <div class="page-filters">
					<?php echo $frmSrch->getFormHtml(); ?>
				 </div>
				 <!--page filters end here-->

				<!--Lessons list view start here-->
				<div class="col-list-group">
					<!--h6>Today</h6-->
					<div class="col-list-container" id="listItemsLessons">
					</div>
				</div>
				<!--Lessons list view end here-->
			</div>
		   <!--panel right end here-->
		</div>
	 </div>
 </section>
