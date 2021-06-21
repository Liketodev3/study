<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (FatApp::getConfig('CONF_ENABLE_NEWSLETTER_SUBSCRIPTION', FatUtility::VAR_INT, 1)) { ?>
    <div class="col-md-6 col-lg-3">
        <div class="footer-group toggle-group">
            <div class="footer__group-title toggle-trigger-js">
                <h5 class=""><?php echo Label::getLabel('LBL_SignUp_To_NewsLetter', $siteLangId) ?></h5>
            </div>
            <div class="footer__group-content toggle-target-js">
                <p><?php Label::getLabel('LBL_newsletter_descritption', $siteLangId); ?></p>
                <div class="email-field">
                    <input type="text" name="email" placeholder="Enter Email">
                    <svg class="icon icon--envelope">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#envelope'; ?>"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>


<script>
    (function() {
        $(document).on('keypress', 'input[name="email"]', function(e) {
            var email = $('input[name="email"]').val();
            if (e.which == 13 && email) {
                fcom.updateWithAjax(fcom.makeUrl('MyApp', 'setUpNewsLetter'), {email:email}, function(t) {
                    if (t.status) {
                        document.frmNewsLetter.reset();
                    }
                });
            }
        });

    })();
</script>
<?php } ?>