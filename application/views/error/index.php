<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
 <section class="section section--page">
             <div class="container container--fixed">
                 <div class="row justify-content-center">
                     <div class="col-sm-12 col-lg-8 col-xl-6">
                         
                        <div class="message-display message-display--404">
                            <img class="message-display__media" src="<?php echo CONF_WEBROOT_URL; ?>images/error-404.svg" alt="">
                            <div class="message-display-content">
                            <h2>Error</h2>
                            <h5>Page not found!</h5>
                            </div>
                            <span class="-gap"></span>
                            <hr>
                            <span class="-gap"></span>
                            <a href="<?php echo CommonHelper::generateUrl(''); ?>" class="btn btn--primary btn--large"><?php echo Label::getLabel('MSG_Back_To_Home');?></a>
                        </div>
                         
                     </div>
                 </div>
             </div>
         </section>