<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$sign = '';
if ($paymentAmount < 0) {
    $val = abs($val);
    $sign = '-';
}

$frm->setFormTagAttribute('class', 'form');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('class', 'form form--normal');
$frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');
$frm->setFormTagAttribute('action', $response['data']['authorization_url']);
$frm->setFormTagAttribute('id', 'paymentForm-js');
$btn = $frm->getField('btn_submit');
if (null != $btn) {
    $btn->setFieldTagAttribute('class', "d-none");
}
?>

<section class="section section--grey section--page -pattern">
    <div class="container container--fixed">
        <div class="page-panel -clearfix">
            <div class="page__panel-narrow">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8 col-md-10">
                        <div class="box -padding-30 -skin">
                            <div class="box__data">
                                <div class="loader"></div>
                                <div class="-align-center">
                                    <h1 class="-color-secondary"><?php echo Label::getLabel('LBL_We\'re redirecting you!!'); ?></h1>
                                    <h4><?php echo Label::getLabel('LBL_Please_wait...'); ?></h4>
                                </div>
                            </div>
                            <div class="message-display">
                                <p class=""><?php echo Label::getLabel('LBL_Payable_Amount', $siteLangId); ?> : <strong><?php echo CommonHelper::displayMoneyFormat($paymentAmount) ?></strong> </p>
                                <p class="">
                                    <?php echo Label::getLabel('LBL_Order_Invoice', $siteLangId); ?>: <strong><?php echo $orderInfo["order_id"];/* displayNotApplicable($orderInfo["order_id"]) */ ?></strong>
                                </p>
                                <?php if (CommonHelper::getCurrencyId() != CommonHelper::getSystemCurrencyId()) { ?>
                                    <p class="-color-secondary"><?php echo CommonHelper::currencyDisclaimer($siteLangId, $paymentAmount); ?></p>
                                <?php } ?>
                            </div>
                            <div class="-align-center">
                                <div class="payment-from">
                                    <?php if (!isset($error)) : ?>
                                        <?php echo  $frm->getFormHtml() ?>
                                    <?php else : ?>
                                        <div class="alert alert--danger">
                                            <?php echo $error ?>
                                            <div>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $("form#paymentForm-js").submit();

    function confirmOrder(frm) {
        var data = fcom.frmData(frm);
        var action = $(frm).attr('action');
        var submitBtn = $("form#paymentForm-js input[type='submit']");
        $.mbsmessage(langLbl.processing, false, 'alert--process alert');
        submitBtn.attr('disabled', 'disabled');
        window.location.href = $("form#paymentForm-js").attr('action');
    }
</script>