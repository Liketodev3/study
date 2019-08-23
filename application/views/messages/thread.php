<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
?>
    <section class="section section--grey section--page">
		<?php //$this->includeTemplate('_partial/dashboardTop.php'); ?>  
		<div class="container container--fixed">
			<div class="page-panel -clearfix">
			  <div class="page-panel__left">
	   <!--div class="tab-swticher">
			<a href="dashboard.html" class="btn btn--large is-active">Teacher</a>
			<a href="learner_dashboard.html" class="btn btn--large">Student</a>
		</div-->
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>		
		</div>
				<div class="page-panel__right">

      <div class="box -padding-20">
	  <div class="page-head">
				   <div class="d-flex justify-content-between align-items-center">
						 <div><h1><?php echo Label::getLabel('LBL_Messages'); ?></h1></div>
						
					</div>
				 </div>

<div class="bg-box box-padding">                                     
								<?php echo $frmSrch->getFormHtml();?>
								<div id="loadMoreBtnDiv"></div>
								<ul class="media media--details" id="messageListing">
									
								</ul>
								<ul class="media media--details" >  
								   <li>
									   <div class="grid grid--first">
										   <div class="avtar"><img src="<?php echo CommonHelper::generateUrl('Image','user',array($loggedUserId,'thumb',true));?>" alt=""></div>
									   </div>
									   <div class="grid grid--second">
										   <span class="media__title"><?php echo $loggedUserName;?></span>
									   </div>
									   <div class="grid grid--third">
										   <div class="form__cover">
												<?php 
												$frm->setFormTagAttribute('onSubmit','sendMessage(this); return false;');
												$frm->setFormTagAttribute('class', 'form'); 
												$frm->developerTags['colClassPrefix'] = 'col-md-';
												$frm->developerTags['fld_default_col'] = 12;
												echo $frm->getFormHtml(); ?>
										   </div>
									   </div>
								   </li>
							   </ul>
    </div>
    </div>
    </div>

  </section>