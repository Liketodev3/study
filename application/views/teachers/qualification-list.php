<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$previousQualificationType = 0;
foreach( $qualifications as $qualification ){
?>

<div class="row row--resume">
<?php if( $previousQualificationType != $qualification['uqualification_experience_type'] ){ ?>
<div class="col-xl-4 col-lg-4 col-sm-4">
	<h4 class="color-dark"><?php echo $qualificationTypeArr[ $qualification['uqualification_experience_type'] ]; ?></h4>
</div>
<?php  }?>
<div class="col-xl-8 col-lg-8 col-sm-8">

	<div class="resume-wrapper">
		<div class="row">
			<div class="col-4 col-sm-4">
				<div class="resume__primary"><b><?php echo $qualification['uqualification_start_year'] ?> - <?php echo $qualification['uqualification_end_year'] ?></b></div>
			</div>
			<div class="col-7 col-sm-7 offset-1">
				<div class="resume__secondary">
					<b><?php echo $qualification['uqualification_title']; ?></b>
					<p><?php echo $qualification['uqualification_institute_name']; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php 
$previousQualificationType = $qualification['uqualification_experience_type'];
}
