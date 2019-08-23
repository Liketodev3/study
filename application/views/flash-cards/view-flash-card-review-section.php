<?php defined('SYSTEM_INIT') or die('Invalid Usage.');  ?>
<div class="circle-count">
	<span id="todayReviewedCounts"><?php echo $todayReviewedCounts;  ?></span>/<span id="allFCardCounts"><?php echo $allFCardCounts; ?></span>
</div>

<div>
	<?php if( '' != $lastReviewedOnDate && $lastReviewedOnDate > '1972-01-01' ){ ?>
	<p><?php echo Label::getLabel('LBL_Last_Reviewed'); ?> <br> <?php echo FatDate::format( $lastReviewedOnDate ); ?></p>
	<?php } ?>
	
	<?php /* if( $pendingReviewCounts > 0 ){ */ ?>
	<a href="javascript:void(0);" onclick="reviewFlashCard();" class="btn <?php if($allFCardCounts){ echo 'btn--secondary'; }else{ echo 'btn--disabled';}?> btn--wide"><?php echo Label::getLabel('LBL_View_Flashcards'); ?></a>
	<?php /* } */ ?>
</div>