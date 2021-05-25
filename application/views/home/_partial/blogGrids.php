<?php if ($blogPostsList) {?>
    <section class="section">
        <div class="container container--narrow">
            <div class="section__head d-flex justify-content-between align-items-center">
                <h2>Latest Blogs</h2>
                <a class="view-all" href="#">View All</a>
            </div>
            <div class="section__body">
                <div class="blog-wrapper">
                    <div class="slider slider--onehalf slider-onehalf-js">
                    <?php foreach ($blogPostsList as $postDetail) {?>
                        <div>
                            <div class="slider__item">
                                <div class="blog-card">
                                    <div class="blog__head">
                                        <div class="blog__media ratio ratio--4by3">
                                            <img src="<?php echo CommonHelper::generateFullUrl('Image', 'blogPostFront', [$postDetail['post_id'],$siteLangId,'MEDIUM']) ?>" alt="">
                                        </div>
                                    </div>
                                    <div class="blog__body">
                                        <div class="blog__detail">
                                            <div class="tags-inline__item"><?php echo $postDetail['bpcategory_name']; ?></div>
                                            <div class="blog__title">
                                                <h3><?php echo $postDetail['post_title'] ?></h3>
                                            </div>
                                            <div class="blog__date">
                                                <svg class="icon icon--calendar"><use xlink:href="images/sprite.yo-coach.svg#calendar"></use></svg>
                                                <span><?php echo FatDate::format($postDetail['post_published_on']); ?> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="blog__action"></a>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }
