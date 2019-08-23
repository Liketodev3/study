<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php if( $bibles ){ ?>
	<div class="result-container" >
	<div class="row">
	<?php
	foreach( $bibles as $bible ){
		$viDdata = CommonHelper::getVideoDetail($bible['biblecontent_url']);
		?>
		<div class="col-md-6">
			<div class="box box-list -padding-30 -hover-shadow -transition">
				<h3> <?php echo isset($bible['biblecontentlang_biblecontent_title']) ? $bible['biblecontentlang_biblecontent_title'] : $bible['biblecontent_title']; ?> </h3> <br>
				<div class="iframe-box">
				<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $viDdata['video_id']; ?>"  frameborder="0" allow="encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div>
		</div>
<?php
	}
	?>
	</div></div>
	<!--<div class="load-more -align-center">
		<a href="#" class="btn btn--bordered btn--xlarge">Load More</a>
	</div>-->
	<?php
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
			'name' => 'frmBibleSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	
} else {
		$this->includeTemplate('_partial/no-record-found.php', $pagingArr,false);
	?>

	<?php
	
} ?>