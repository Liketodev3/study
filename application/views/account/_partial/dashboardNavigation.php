<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>	 

<?php
$showSwitcher = isset($doNotShowSwitcher) ? false : true;

$arr = array(
	'showSwitcher' => $showSwitcher,
	'controllerName' => $controllerName,
	'action' => $action 
	);

if( User::canViewTeacherTab() && User::getDashboardActiveTab() == User::USER_TEACHER_DASHBOARD ){ 
	$this->includeTemplate('teacher/_partial/teacherDashboardNavigation.php', $arr );	
} else {
	$this->includeTemplate('learner/_partial/learnerDashboardNavigation.php', $arr );
}
?>
