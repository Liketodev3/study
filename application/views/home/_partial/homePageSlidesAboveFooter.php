<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
      <?php  if( !empty($testimonials) ){ ?>
        <section class="section">
            <div class="container container--fixed">
                <div class="section-title">
                    <h2><?php echo Label::getLabel('LBL_Testimonials'); ?></h2>
                </div>
                <div class="quote-slider-wrap">
                    <span class="quote__mark"><img src="images/retina/quotes.svg" alt=""></span>
                    <div class="quote-slider">
                    <?php	foreach( $testimonials as $listItem ){ ?>
                        <div class="quote-main">
                            <div class="quote-large">
                                <img src="<?php echo CommonHelper::generateFullUrl('Image','testimonial',array($listItem['testimonial_id'],0)); ?>" alt="">
                            </div>
                            <div class="quote-white">
                                <p><?php echo $listItem['testimonial_text']; ?></p>
                                <span class="quote-footer">
                                    <h5 class="title"><?php echo $listItem['testimonial_user_name']; ?></h5>

                                </span>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <div class="quote-thumbs">
                    <?php	foreach( $testimonials as $listItem ){ ?>
                        <div class="quote-thumb">
                            <img src="<?php echo CommonHelper::generateFullUrl('Image','testimonial',array($listItem['testimonial_id'],0)); ?>">
                        </div>
                    <?php } ?>
                    </div>
                </div>


            </div>
        </section>
      <?php } ?>
