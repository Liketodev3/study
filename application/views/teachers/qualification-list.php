<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$previousQualificationType = 0;
foreach( $qualifications as $qualification ){
?>
<div class="content-repeated-container">
		<div class="content-repeated">
			<div class="row">
				<div class="col-xl-4 col-lg-4 col-sm-4">
					<?php if( $previousQualificationType != $qualification['uqualification_experience_type'] ){ ?>
					<p class="-small-title"><strong><?php echo $qualificationTypeArr[ $qualification['uqualification_experience_type'] ]; ?></strong></p>
					<?php } ?>
				</div>
				<div class="col-xl-8 col-lg-8 col-sm-8">
					<p><strong><?php echo $qualification['uqualification_title']; ?> </strong> <br><?php echo $qualification['uqualification_institute_name']; ?> - <?php echo $qualification['uqualification_institute_address']?> <br><?php echo $qualification['uqualification_start_year'] ?> - <?php echo $qualification['uqualification_end_year'] ?></p>
				</div>
			</div>
		</div>
</div>
<?php 
$previousQualificationType = $qualification['uqualification_experience_type'];
}
	
/* <div class="content-repeated content-repeated--action">
	<div class="row">
		<div class="col-xl-4 col-lg-4 col-sm-4">
		</div>
		<div class="col-xl-8 col-lg-8 col-sm-8 -no-border-bottom">
		   <a href="javascript:void(0)" class="btn btn--small btn--bordered btn--wide btn--arrow">Show More</a>
		</div>
	</div>
</div>
<hr> */ ?>