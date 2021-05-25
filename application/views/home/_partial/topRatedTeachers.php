<?php if($topRatedTeachers){ ?>

    <section class="section">
        <div class="container container--narrow">
            <div class="section__head">
                <h2><?php echo Label::getLabel('Lbl_Top_Rated_Teachers'); ?></h2>
            </div>

            <div class="section__body">
                <div class="row">

               
                <?php foreach($topRatedTeachers as $topRatedTeacher){  ?>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        <div class="tile">
                            <div class="tile__head">
                                <div class="tile__media ratio ratio--1by1">
                                    <img src="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image','user', array( $topRatedTeacher['user_id'],'MEDIUM')),  CONF_IMG_CACHE_TIME, '.jpg') ?>" alt="">
                                </div>
                            </div>
                            <div class="tile__body">
                                <a class="tile__title" href="<?php echo CommonHelper::generateUrl('Teachers', 'view',[$topRatedTeacher['user_url_name']]);  ?>"><h4><?php echo $topRatedTeacher['user_first_name'] . ' ' . $topRatedTeacher['user_last_name']; ?></h4></a>
                                <div class="tile__detail">
                                    <div class="info-tag tile__location location">
                                        <div class="ratings__star">
                                            <svg class="icon icon--location"><use xlink:href="images/sprite.yo-coach.svg#location"></use></svg>
                                        </div>
                                        <span class="lacation__name"><?php echo $topRatedTeacher['country_name']; ?></span>
                                    </div>
                                    <div class="info-tag tile__ratings ratings">
                                        <div class="ratings__star">
                                            <svg class="icon icon--rating"><use xlink:href="images/sprite.yo-coach.svg#rating"></use></svg>
                                        </div>
                                        <span class="ratings__value"><?php echo $topRatedTeacher['teacher_rating'] ?? 4.5; ?></span>
                                        <span class="ratings__count"><?php echo '('.$topRatedTeacher['totReviews'].')'; ?></span>
                                    </div>
                                </div>
                                <div class="card__row--action ">
                                    <a href="<?php echo CommonHelper::generateUrl('Teachers', 'view',[$topRatedTeacher['user_url_name']]); ?>" class="btn btn--primary btn--block"><?php echo Label::getLabel('LBL_View_Details',$siteLangId); ?></a>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </section>
       
<?php }
