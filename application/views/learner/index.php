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

                <div class="page-panel__inner-l">

                    <!--page-head start here-->
                    <div class="page-head">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><h1><?php echo Label::getLabel('LBL_Dashboard'); ?></h1></div>
							
							<div>
								<!--<div class="select-box toggle-group">
								<a href="javascript:void(0)" class="select-box__value toggle__trigger-js">
								Calender</a>
								<div class="select-box__target -skin toggle__target-js" style="display: none;">
								<div class="listing listing--vertical">
								<ul>
								<li><a href="dashboard.html">Calender</a></li>
								<li><a href="dashboard_list.html">List</a></li>
								</ul>
								</div>
								</div>
								</div>-->
								
								<!--
								<div class="tab-swticher tab-swticher-small">
									<a href="dashboard_list.html" class="btn btn--large">List</a>
									<a href="dashboard.html" class="btn btn--large is-active">Calnder</a>
								</div> -->
							<div>
								<div class="tab-swticher tab-swticher-small">
									<a href="<?php echo CommonHelper::generateUrl('Learner'); ?>" class="btn btn--large is-active"><?php echo Label::getLabel('LBL_List'); ?></a>
									<a onclick="viewCalendar();" href="javascript:void(0);" class="btn btn--large"><?php echo Label::getLabel('LBL_Calender'); ?></a>
								</div>								 
							</div>								
							</div>
                        </div>
                    </div>
										<div class="col-list-group">
					<div style="display:none">
					<?php 
						$frmSrch->setFormTagAttribute ( 'onsubmit', 'searchLessons(this); return(false);');
						echo $frmSrch->getFormHtml();
					?>
					</div>
					<span class="-gap"></span>
					<!--h6>Today</h6-->
					<div class="col-list-container" id="listItemsLessons">
					</div>
				</div>
                    <!--page-head end here-->
                </div>

				<?php $this->includeTemplate('_partial/dashboardRightNavigation.php'); ?>                
            </div>
            <!--panel right end here-->

        </div>
    </div>
</section>
<div class="gap"></div>