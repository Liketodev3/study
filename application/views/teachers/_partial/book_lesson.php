<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
if( !empty( $teacher['teachLanguages'] ) ) {
	foreach( $teacher['teachLanguages'] as $key=>$val ) {
		$teacherLanguage = $key;
		break;
	}
} else {
	$teacherLanguage = 1;
}
?>

<div class="box box--cta -padding-30">

	<?php 
	$lessonPackages = $teacher['lessonPackages'];
	if( count($lessonPackages) ){
		$lessonPackage = array_shift( $lessonPackages );
	?>
	<h4 class="-text-bold"><?php echo Label::getLabel("LBL_Reserve_a_Session"); ?></h4>
	<?php /* <div class="select-box toggle-group">
	
		<a href="javascript:void(0)" class="select-box__value toggle__trigger-js">
		<?php echo $lessonPackage['lpackage_title']; ?> <small class="-color-secondary"><?php echo ($lessonPackage['lpackage_lessons']>1)?CommonHelper::displayMoneyFormat( $teacher['us_bulk_lesson_amount'] ): CommonHelper::displayMoneyFormat(  $teacher['us_single_lesson_amount'] ) ; ?> / <?php echo Label::getLabel('LBL_Per_Hour'); ?></small></a>
		
		<?php if( count($lessonPackages) ){
		?>
		<div class="select-box__target -skin toggle__target-js" style="display: none;">
			<div class="listing listing--vertical">
				<ul>
					<?php foreach( $lessonPackages as $lpackage ){ ?>
					<li>
						<a href="javascript:void(0)" onClick="cart.add( '<?php echo $teacher['user_id']; ?>', '<?php echo $lpackage['lpackage_id'] ?>' )">
						<?php echo $lpackage['lpackage_title']; ?> <small class="-color-secondary"><?php echo ($lpackage['lpackage_lessons']>1)?CommonHelper::displayMoneyFormat( $teacher['us_bulk_lesson_amount'] ): CommonHelper::displayMoneyFormat(  $teacher['us_single_lesson_amount'] ) ; ?> / <?php echo Label::getLabel('LBL_Per_Hour'); ?></small>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
		
	</div>*/ ?>
	<span class="-gap-10"></span>
	<?php } ?>

	<div class="box-btn-group">
		<?php if( count($teacher['lessonPackages']) ){ ?>
		<a href="javascript:void(0);" onClick="cart.add( '<?php echo $teacher['user_id']; ?>', '<?php echo $lessonPackage['lpackage_id'] ?>', '','','<?php echo $teacherLanguage; ?>' )" class="btn btn--secondary btn--large btn--block"><?php echo Label::getLabel('LBL_Book_Now') ?></a>
		<?php } ?>
	 
		<a href="javascript:void(0)" onClick="generateThread(<?php echo $teacher['user_id']; ?>)" class="btn btn--gray btn--large btn--block"><span class="svg-icon">
		<svg xmlns="http://www.w3.org/2000/svg" width="15" height="11.782" viewBox="0 0 15 11.782">
		<path d="M1032.66,878.814q-2.745,1.859-4.17,2.888c-0.31.234-.57,0.417-0.77,0.548a4.846,4.846,0,0,1-.79.4,2.424,2.424,0,0,1-.92.2h-0.02a2.424,2.424,0,0,1-.92-0.2,4.846,4.846,0,0,1-.79-0.4c-0.2-.131-0.46-0.314-0.77-0.548-0.76-.552-2.14-1.515-4.16-2.888a4.562,4.562,0,0,1-.85-0.728v6.646a1.3,1.3,0,0,0,.39.946,1.309,1.309,0,0,0,.95.393h12.32a1.309,1.309,0,0,0,.95-0.393,1.3,1.3,0,0,0,.39-0.946v-6.646a4.545,4.545,0,0,1-.84.728h0Zm0.44-4.135a1.287,1.287,0,0,0-.94-0.393h-12.32a1.189,1.189,0,0,0-.99.435,1.7,1.7,0,0,0-.35,1.088,1.933,1.933,0,0,0,.46,1.143,4.157,4.157,0,0,0,.98.967c0.19,0.133.76,0.531,1.72,1.192s1.68,1.171,2.19,1.528c0.05,0.039.17,0.124,0.35,0.255s0.34,0.238.46,0.318,0.26,0.172.43,0.272a2.493,2.493,0,0,0,.48.226,1.308,1.308,0,0,0,.42.076h0.02a1.308,1.308,0,0,0,.42-0.076,2.493,2.493,0,0,0,.48-0.226c0.17-.1.31-0.191,0.43-0.272s0.27-.187.46-0.318,0.3-.216.35-0.255q0.765-.535,3.92-2.72a3.887,3.887,0,0,0,1.02-1.03,2.238,2.238,0,0,0,.41-1.264A1.267,1.267,0,0,0,1033.1,874.679Z" transform="translate(-1018.5 -874.281)"></path>
		</svg>
		</span>Message</a>
	</div>
</div>