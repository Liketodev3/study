<?php if($topRatedTeachers){ ?>
       <section class="section -singleTopBorder section-teacher">
            <div class="container container--fixed">
                <div class="section-title">
                    <h2><?php echo Label::getLabel('Lbl_Top_Rated_Teachers'); ?></h2>
                </div>
                <div class="row justify-content-center align-items-center">
                <?php foreach($topRatedTeachers as $topRatedTeacher){
                    $teacherImnage =  FatCache::getCachedUrl(CommonHelper::generateUrl('Image','user', array( $topRatedTeacher['user_id'],'NORMAL',1)),  CONF_IMG_CACHE_TIME, '.jpg');
                ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="border-box">
                            <span class="box-pic">
                                <a href="<?php echo CommonHelper::generateUrl('Teachers', 'profile').'/'. $topRatedTeacher['user_url_name'];?>"><img src="<?php echo $teacherImnage; ?>" alt=""></a>
                            </span>

                            <div class="box-body">
                                <h4 class="title"> <a href="<?php echo CommonHelper::generateUrl('Teachers', 'profile').'/'. $topRatedTeacher['user_url_name'];?>"><?php echo $topRatedTeacher['user_first_name'].' '.$topRatedTeacher['user_last_name']; ?></a></h4>
                                <span class="location"><?php echo $topRatedTeacher['country_name']; ?></span>
                                <div class="rating <?php echo ($topRatedTeacher['teacher_rating']>0)?'':'no-rating'?>">
                                    <i class="svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14.854px" height="14.166px" viewBox="0 0 14.854 14.166" enable-background="new 0 0 14.854 14.166" xml:space="preserve">
                                            <path d="M14.854,5.49c0-0.268-0.286-0.375-0.5-0.41L9.873,4.428L7.864,0.367C7.784,0.197,7.632,0,7.427,0
				C7.222,0,7.07,0.197,6.989,0.367L4.981,4.428L0.5,5.08C0.277,5.115,0,5.223,0,5.49c0,0.16,0.116,0.313,0.223,0.429l3.249,3.159
				l-0.768,4.464c-0.009,0.063-0.018,0.116-0.018,0.179c0,0.232,0.116,0.445,0.375,0.445c0.125,0,0.241-0.043,0.357-0.106l4.008-2.106
				l4.008,2.106c0.107,0.063,0.232,0.106,0.357,0.106c0.259,0,0.366-0.213,0.366-0.445c0-0.063,0-0.116-0.009-0.179l-0.768-4.464
				l3.241-3.159C14.737,5.803,14.854,5.65,14.854,5.49z"></path>
                                        </svg>
                                    </i>
                                    <span class="rate-value"><?php echo $topRatedTeacher['teacher_rating']; ?></span>
                                </div>

                                <ul class="tags">

                                <?php echo CommonHelper::getTeachLangs($topRatedTeacher['utl_slanguage_ids'],true); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="align-center card-more-content">
                <a href="/teachers" class="arrow-link"><?php echo Label::getLabel('LBL_Browse_All_Teachers'); ?></a>
            </div>
        </section>
<?php }?>
