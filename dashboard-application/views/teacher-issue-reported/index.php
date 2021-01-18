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
                        <div>
                            <h1><?php echo Label::getLabel('LBL_My_Students'); ?></h1></div>
                    </div>
                </div>
                <!--page-head end here-->

                <!--page filters start here-->
				 <div class="page-filters">
					<?php 
					$frmSrch->setFormTagAttribute ( 'onsubmit', 'searchStudents(this); return(false);');
					$frmSrch->setFormTagAttribute ( 'class', 'form form--small' );
					
					$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
					$frmSrch->developerTags['fld_default_col'] = 5;
					
					$fldStatus = $frmSrch->getField( 'status' );
					$frmSrch->removeField($fldStatus);
					//$fldStatus->developerTags['col'] = 3;
					
					$fldSubmit = $frmSrch->getField( 'btn_submit' );
					$fldSubmit->developerTags['col'] = 4;
					
					$btnReset = $frmSrch->getField( 'btn_reset' );
					//$btnReset->addFieldTagAttribute( 'style', 'margin-left:10px' );
					$btnReset->addFieldTagAttribute('onclick','clearSearch()');
					echo $frmSrch->getFormHtml(); ?>
				 
				 </div>
				 <!--page filters end here-->

                <!--Lessons list view start here-->
                <div class="col-list-group">
                    <!--h6>Today</h6-->
                    <div class="col-list-container" id="listItems">

                    </div>
                </div>
                <!--Lessons list view end here-->
            </div>
            <!--panel right end here-->
        </div>
    </div>
</section>
