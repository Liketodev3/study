<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$nextPage = $page + 1;
if( $nextPage <= $pageCount ){ ?>
	<a id="loadMoreBtn" href="javascript:void(0)" onClick="goToLoadMoreComments(<?php echo $nextPage; ?>);" class="loadmore"><?php echo Label::getLabel('LBL_Load_Previous', $siteLangId); ?></a>
<?php
}
?>
