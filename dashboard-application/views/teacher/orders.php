<?php 
	defined('SYSTEM_INIT') or die('Invalid Usage.'); 
	$frmOrderSrch->developerTags['colClassPrefix'] = 'col-md-';
	$frmOrderSrch->developerTags['fld_default_col'] = 3;

	
	// $fld = $frmOrderSrch->getField('keyword');
	// $fld->setWrapperAttribute('class','col-md-3');
	// $fld->developerTags['col'] = 3;
	
	// $fld = $frmOrderSrch->getField('status');
	// $fld->setWrapperAttribute('class','col-md-3');
	// $fld->developerTags['col'] = 3;
	
	// $fld = $frmOrderSrch->getField('date_from');
	// $fld->setWrapperAttribute('class','col-md-3');
	// $fld->developerTags['col'] = 2;
	
	// $fld = $frmOrderSrch->getField('date_to');
	// $fld->setWrapperAttribute('class','col-md-3');
	// $fld->developerTags['col'] = 2;
	
	// $submitBtnFld = $frmOrderSrch->getField('btn_submit');
	// $submitBtnFld->setWrapperAttribute('class','col-md-4');
	// $submitBtnFld->developerTags['col'] = 4;	

	
	$frmOrderSrch->setFormTagAttribute('class', 'form form--small'); 
	$frmOrderSrch->setFormTagAttribute('onSubmit', 'searchOrders(this); return false;'); 
?>
<!-- [ PAGE ========= -->
<main class="page">
    <div class="container container--fixed">

        <div class="page__head">
            <div class="row align-items-center justify-content-between">
                <div class="col-sm-6">
                    <h1><?php echo Label::getLabel('LBL_My_Orders'); ?></h1>
                </div>
                <div class="col-sm-auto">
                    <div class="buttons-group d-flex align-items-center">
                        <a href="javascript:void(0)" class="btn bg-secondary slide-toggle-js">
                            <svg class="icon icon--clock icon--small margin-right-2">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#search'; ?>"></use>
                            </svg>
                            <?php echo Label::getLabel('Lbl_Search'); ?>
                        </a>

                    </div>

                </div>
            </div>

            <!-- [ FILTERS ========= -->
            <div class="search-filter slide-target-js" style="display: none;">
                <?php echo $frmOrderSrch->getFormHtml();  ?>
            </div>
            <!-- ] ========= -->

        </div>

        <div class="page__body">
            <!-- [ PAGE PANEL ========= -->
            <div class="page-content" id="listItems">
            </div>
            <!-- ] -->
        </div>
        <div class="page__footer align-center">
            <p class="small">Copyright © 2021 Yo!Coach Developed by <a href="#" class="underline color-primary">FATbit Technologies</a> . </p>
        </div>

    </div>


</main>
<!-- ] -->