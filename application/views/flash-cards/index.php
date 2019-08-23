<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section section--grey section--page">
	 <div class="container container--fixed">
	   <div class="page-panel -clearfix">
			<div class="page-panel__left">
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
			</div>
	   
			<div class="page-panel__right">
			  <div class="page-panel__inner-l">
				 <!--page-head start here-->
				 <div class="page-head">
				   <div class="d-flex justify-content-between align-items-center">
						 <div><h1><?php echo Label::getLabel('LBL_My_Flashcards'); ?></h1></div>
						 <div>
							<a href="javascript:void(0);" onclick="flashCardForm(0);" class="btn btn--secondary btn--small"><?php echo Label::getLabel('LBL_Add_Flashcard'); ?></a>
						   
						 </div>
					 </div>
				 </div>
				 <!--page-head end here-->
				 
				 <!--page filters start here-->
				 <div class="page-filters">
					<?php 
					$frmSrch->setFormTagAttribute ( 'onsubmit', 'searchFlashCards(this); return(false);');
					$frmSrch->setFormTagAttribute ( 'class', 'form form--small' );
					
					$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
					$frmSrch->developerTags['fld_default_col'] = 4;
					
					$fldLanguage = $frmSrch->getField( 'slanguage_id' );
					$fldStatus->developerTags['col'] = 4;
					
					$fldSubmit = $frmSrch->getField( 'btn_submit' );
					$fldSubmit->developerTags['col'] = 4;
					
					$btnReset = $frmSrch->getField( 'btn_reset' );
					//$btnReset->developerTags['col'] = 2;
					//$btnReset->addFieldTagAttribute( 'style', 'margin-left:10px' );
					$btnReset->addFieldTagAttribute('onclick','clearSearch()');
					echo $frmSrch->getFormHtml(); ?>
				 </div>
				 <!--page filters end here-->
			   
				<div class="col-list-container" id="listItems"></div>
			 </div>

			 <div class="page-panel__inner-r" >
				 <div class="box-group" >
					 <div class="box -padding-30 -align-center" id="flashCardReviewSection">
					 </div>
				 </div>
			 </div>  
			 
		   </div>
		</div>
	 </div>
</section>
