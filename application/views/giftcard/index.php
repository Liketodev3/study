<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$frmSrch->setFormTagAttribute('onSubmit','cartListing(this); return false;');
$frmSrch->setFormTagAttribute('class', 'form form--small');

$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
$frmSrch->developerTags['fld_default_col'] = 4;

$fld = $frmSrch->getField('keyword');
$fld->setWrapperAttribute('class','col-md-4');

$fld = $frmSrch->getField('giftcard_status');
$fld->setWrapperAttribute('class','col-md-4');

$submitFld = $frmSrch->getField('btn_submit');
$submitFld->setWrapperAttribute('class','col-md-4');

$clearFld = $formData->getField('clear');
$clearFld->setFieldTagAttribute('class','col-md-4');
$clearFld->setFieldTagAttribute('id','clear');

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


	  <div class="page-head">
				   <div class="d-flex justify-content-between align-items-center">
						 <div><h1><?php echo Label::getLabel('LBL_Giftcards_Purchased'); ?></h1></div>
						 <div><a href="javascript:void(0);" onclick="$('#addGiftcardFrm').toggle()" class="btn btn--secondary btn--small"><?php echo Label::getLabel('LBL_Send_Gift_Card',$siteLangId); ?></a></div>
					</div>
				 </div>


	  <div class="page-filters">
	  										<?php	echo $frmSrch->getFormHtml(); ?>
      </div>

	   <div id="addGiftcardFrm" style="display:none;">

		<div class="box -padding-20 gift-block">



	   <div class="row">
        <div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12 column">
					<div class="card-image" dd="">
					  <div class="amount gift-amount-large"><span class="giftcardPrice">00</span></div>
					  <div class="plan-text meal">
						<?php echo Label::getLabel('LBL_Gift_card',$siteLangId); ?>
					  </div>
						<div class="brand-logo">
						<img src="<?php echo FatUtility::generateFullUrl('Image','siteWhiteLogo',array($siteLangId), CONF_WEBROOT_FRONT_URL); ?>" alt="">
					  </div>
					</div>
        </div>
        <div class=" col-lg-8 col-md-8 col-sm-8 col-xs-12 column">

          <?php echo $formData->getFormTag(); ?>
          <label><?php echo Label::getLabel('LBL_Your_Detail',$siteLangId); ?>
          </label>
          <div class="Buyerdetail">
            <div class="buyer-div">
              <div class="row">
                <div class=" col-lg-4 col-md-12 col-sm-12 col-xs-12">
					<div class="field-set"><?php echo $formData->getFieldHtml('gcbuyer_name'); ?> </div>
                </div>
				<div class=" col-lg-4 col-md-12 col-sm-12 col-xs-12">
                 <div class="field-set"> <?php echo $formData->getFieldHtml('gcbuyer_email'); ?> </div>
                </div>
				<div class=" col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="field-set">  <?php echo $formData->getFieldHtml('gcbuyer_phone'); ?></div>
                </div>
              </div>


            </div>
          </div>
          <div  class="row">

            <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
			  <label>
                <?php echo Label::getLabel('LBL_Giftcard_Amount',$siteLangId); ?>
              </label>
              <div class="field-set"> <?php echo $formData->getFieldHtml('giftcard_price'); ?></div>
            </div>
          </div>
          <label><?php echo Label::getLabel('LBL_Gift_card_Recipient_Detail',$siteLangId); ?>
          </label>
          <div class="recipientdetail">
            <div class="recipient-div">
              <div  class="row">
                <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 column">
				<div class="field-set"><?php echo $formData->getFieldHtml('gcrecipient_name'); ?></div>
                </div>
                <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 column">
					<div class="field-set"><?php echo $formData->getFieldHtml('gcrecipient_email'); ?>	</div>
                </div>
              </div>
            </div>

          </div>
          <div>
            <fieldset>  <?php echo $formData->getFieldHtml('save'); ?>
            <?php echo $formData->getFieldHtml('clear'); ?>
            <?php echo $formData->getExternalJs(); ?>
</fieldset>
          </div>
        </form>
        </div>

		</div>
      </div>
	  <span class="-gap"></span>
	  </div>


				  <div class="box -padding-20">

                             <div class="table-scroll">
                  <div id="giftcardListing"><?php echo Label::getLabel('LBL_Loading..',$siteLangId); ?></div>
					</div>
					</div>
    </div>
    </div>
    </div>

  </section>
