<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if( !empty( $tutorListNavigation ) ){ ?>
		<?php foreach( $tutorListNavigation as $nav ){ ?>
		<li><a href="<?php echo CommonHelper::generateUrl('Teachers','index', array($nav['id']) ); ?>"><?php echo $nav['name'].' '.Label::getLabel('LBL_Tutors'); ?></a></li>
	<?php } ?>
	<li><a href="<?php echo CommonHelper::generateUrl('Teachers'); ?>"><?php echo Label::getLabel('LBL_All_Tutors'); ?></a></li>	
<?php } ?>