<h2 class="-color-secondary"><?php echo CommonHelper::displayMoneyFormat( $earningData['earning'] ); ?></h2> <?php if($earningData['fromDate']){
	echo '<p>'. $earningData['fromDate']. ' - ' .$earningData['toDate'] .'</p>';
} ?>