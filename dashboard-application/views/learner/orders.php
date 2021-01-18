<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php 

	$frmOrderSrch->developerTags['colClassPrefix'] = 'col-md-';
	$frmOrderSrch->developerTags['fld_default_col'] = 12;

	
	$fld = $frmOrderSrch->getField('keyword');
	$fld->setWrapperAttribute('class','col-md-3');
	$fld->developerTags['col'] = 3;
	
	$fld = $frmOrderSrch->getField('status');
	$fld->setWrapperAttribute('class','col-md-3');
	$fld->developerTags['col'] = 3;
	
	$fld = $frmOrderSrch->getField('date_from');
	$fld->setWrapperAttribute('class','col-md-3');
	$fld->developerTags['col'] = 2;
	
	$fld = $frmOrderSrch->getField('date_to');
	$fld->setWrapperAttribute('class','col-md-3');
	$fld->developerTags['col'] = 2;
	
	$submitBtnFld = $frmOrderSrch->getField('btn_submit');
	$submitBtnFld->setWrapperAttribute('class','col-md-4');
	$submitBtnFld->developerTags['col'] = 4;

	$frmOrderSrch->setFormTagAttribute('class', 'form form--small'); 
	$frmOrderSrch->setFormTagAttribute('onSubmit', 'searchOrders(this); return false;'); 
?>
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
						 <div><h1><?php echo Label::getLabel('LBL_My_Orders'); ?></h1></div>
				 </div>
				 </div>
				 <!--page-head end here-->
				 
				 <!--page filters start here-->
				 <div class="page-filters">
					   <?php echo $frmOrderSrch->getFormHtml(); ?>
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
