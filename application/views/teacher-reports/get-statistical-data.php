<h2 class="-color-secondary"><?php echo CommonHelper::displayMoneyFormat( $earningData['earning'] ); ?></h2> <?php 
$user_timezone = MyDate::getUserTimeZone();
$systemTimeZone = MyDate::getTimeZone();
if($earningData['fromDate']){
	//echo $earningData['fromDate']. ' - ' .$earningData['toDate'];
	echo '<p>' . MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', $earningData['fromDate'], true, $user_timezone )  . ' - ' . MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', $earningData['toDate'], true, $user_timezone ) .'</p>';
} 
?>