<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if( !empty( $tutorListNavigation ) ){ ?>
		<?php foreach( $tutorListNavigation as $nav ){ ?>
		<li><a href="<?php echo CommonHelper::generateUrl('Teachers','index', array($nav['id'])); ?>"><?php echo sprintf(Label::getLabel('LBL_%s_Tutors'), $nav['name']); ?></a></li>
	<?php } ?>
	<li><a href="<?php echo CommonHelper::generateUrl('Teachers'); ?>"><?php echo Label::getLabel('LBL_All_Tutors'); ?></a></li>	
<?php } ?>