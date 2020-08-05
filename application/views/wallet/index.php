<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmRechargeWallet->setFormTagAttribute('onSubmit','setUpWalletRecharge(this); return false;');
$frmRechargeWallet->setFormTagAttribute('class', 'form');
$frmRechargeWallet->developerTags['colClassPrefix'] = 'col-md-';
$frmRechargeWallet->developerTags['fld_default_col'] = 12;

?>
<section class="section section--grey section--page">
    <?php //$this->includeTemplate('_partial/dashboardTop.php'); ?>
        <div class="container container--fixed">
            <div class="page-panel -clearfix">
                <div class="page-panel__left">
                    <?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
                </div>

                <div class="page-panel__right">
                    <div class="box__body">
                        <div class="page-head">
                            <div class="d-lg-flex d-md-flex d-sm-flex justify-content-between align-items-center">
                                <div><h1><?php echo Label::getLabel('LBL_My_Wallet'); ?></h1></div>
								<div>
                                    <a href="javascript:void(0)" onclick="withdrwalRequestForm()" class="btn btn--secondary btn--small"><?php echo Label::getLabel('LBL_Request_Withdrawal'); ?></a>
                                    <a href="javascript:void(0)" onclick="redeemGiftcardForm()" class="btn btn--secondary btn--small"><?php echo Label::getLabel('Redeem_Gift_Card'); ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="box -padding-20">
                            <div class="grids-wrap">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="wrap wrap--yellow">
                                            <img src="<?php echo CONF_WEBROOT_URL; ?>images/icon_wallet.svg" alt="" class="icon__wallet">
                                            <h2><?php echo CommonHelper::displayMoneyFormat($userTotalWalletBalance);?></h2>
                                            <p><?php echo Label::getLabel('LBL_Your_Wallet_Balance'); ?></p>
                                            <span class="-gap"></span><span class="-gap"></span>
                                            <p><?php echo Label::getLabel('LBL_Make_Sure_To_Review_Your_Order_Details_Now.'); ?></p>
                                            <p><?php echo Label::getLabel('LBL_Once_you_press_\'Add_Money\'_you\'ll_be_directed_to_payment_page_to_enter_your_payment_information_and_process_the_order'); ?></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="wrap wrap--gray">
                                            <h4><?php $str = Label::getLabel('LBL_Enter_Amount_To_Be_Added_[{site-currency-symbol}]');
											$str = str_replace( "{site-currency-symbol}", CommonHelper::getDefaultCurrencySymbol(), $str );
											//echo $str;
											?></h4>
                                            <span class="-gap"></span>
                                            <?php echo $frmRechargeWallet->getFormHtml(); ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="gap"></div>
                    </div>
                    <!-- ] -->

                    <span class="-gap"></span>
					<?php
					$frmSrch->setFormTagAttribute ( 'onsubmit', 'searchCredits(this); return(false);');
					$frmSrch->setFormTagAttribute ( 'class', 'form form--small' );

					$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
					$frmSrch->developerTags['fld_default_col'] = 3;

					$keyFld = $frmSrch->getField('keyword');
					$keyFld->setFieldTagAttribute('placeholder', Label::getLabel('LBL_Keyword'));

					$keyFld = $frmSrch->getField('debit_credit_type');

					$keyFld = $frmSrch->getField('date_from');
					$keyFld->setFieldTagAttribute('placeholder', Label::getLabel('LBL_From_Date'));

					$keyFld = $frmSrch->getField('date_to');
					$keyFld->setFieldTagAttribute('placeholder', Label::getLabel('LBL_To_Date', $siteLangId));

					$submitBtnFld = $frmSrch->getField('btn_submit');
					$submitBtnFld->developerTags['col'] = 4;

					$btnReset = $frmSrch->getField('btn_reset');
					$btnReset->addFieldTagAttribute('onclick','clearSearch()');
					?>



							<div class="page-head">
								<div class="d-flex justify-content-between align-items-center">
									<div><h4><?php echo Label::getLabel('LBL_Search_Transactions'); ?></h4></div>
									<div>

									</div>
								</div>
							</div>


							<div class="page-filters"><?php echo $frmSrch->getFormHtml(); ?></div>
							<div class="box -padding-20">
							<div class="table-scroll">
                            <div id="creditListing"></div>
							</div>
							</div>
                </div>
            </div>
</section>
<div class="gap"></div>
