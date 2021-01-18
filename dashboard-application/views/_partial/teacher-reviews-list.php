<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
foreach ($reviewsList as $review) {
?>
<div class="content-repeated-container">
    <div class="content-repeated">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-sm-4">
                <div class="avtar avtar--xsmall" data-text="<?php echo CommonHelper::getFirstChar($review['lname']); ?>">
                    <?php
                    if (true == User::isProfilePicUploaded($review['tlreview_postedby_user_id'])) {
                        $img = CommonHelper::generateUrl('Image', 'user', array($review['tlreview_postedby_user_id'])) . '?' . time();
                        echo '<img src="' . $img . '" />';
                    }
                    ?>
                </div>

                <h6 class="-small-title"><?php echo CommonHelper::displayName($review['lname']); ?></h6>
                <p><?php echo Label::getLabel('Lbl_On_Date'), ' ', FatDate::format($review['tlreview_posted_on']); ?></p>
            </div>
            <div class="col-xl-8 col-lg-8 col-sm-8">

                <p><strong><?php echo $review['lessonLanguage'];?> </strong></p>
                <h5><?php echo $review['tlreview_title']; ?></h5>
            <p>
                <span class='lessText'><?php echo CommonHelper::truncateCharacters($review['tlreview_description'],200,'','',true);?></span><?php if(strlen($review['tlreview_description']) > 200) { ?><span class='lessText' >...</span>
                <span class='moreText moreTextHide' >
                <?php echo nl2br($review['tlreview_description']); ?>
                </span>
                <a class="readMore link--arrow link-color" href="javascript:void(0);">( <?php echo Label::getLabel('Lbl_SHOW_MORE') ; ?> )</a>
                <?php } ?>
            </p>
            </div>
        </div>
    </div>
</div>
<?php }  ?>
<?php echo FatUtility::createHiddenFormFromData ( $postedData, array ('name' => 'frmSearchReviewsPaging') ); ?>

<script>
$(document).ready(function() {
    $('body').on('click', '.readMore', function() {
        $(this).siblings('.moreText').toggleClass('moreTextHide');
        $(this).siblings('.lessText').toggleClass('lessTexthide');
        var $el = $(this);
        $el.text($el.text() == "( Show More )" ? "( Show Less )" : "( Show More )");
    })
});
</script>