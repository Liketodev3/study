<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
	$contactFrm->setFormTagAttribute('class', 'form form--normal');
	$captchaFld = $contactFrm->getField('htmlNote');
	$captchaFld->htmlBeforeField = '<div class="field-set">
		   <div class="caption-wraper"><label class="field_label"></label></div>
		   <div class="field-wraper">
			   <div class="field_cover">';
	$captchaFld->htmlAfterField = '</div></div></div>';
	
	$contactFrm->setFormTagAttribute('action', CommonHelper::generateUrl('contact', 'contactSubmit')); 
	$contactFrm->developerTags['colClassPrefix'] = 'col-md-';
	$contactFrm->developerTags['fld_default_col'] = 12;
	
?>
<?php echo FatUtility::decodeHtmlEntities( $pageData['cpage_content'] );?>
<div class="section section--grey">
             <div class="container container--fixed">
                 <div class="row justify-content-center">
                     <div class="col-xl-6 col-lg-8 col-md-10">
                            <div class="form-container">
                                <h3 class="-align-center"><?php echo Label::getLabel('LBL_Send_us_a_message');?></h3>
                                <span class="-gap"></span>
                               <?php echo $contactFrm->getFormHtml(); ?>
                            </div>
                         </div>
                 </div>
             </div>
         </div>
      


<script src='https://www.google.com/recaptcha/api.js'></script>