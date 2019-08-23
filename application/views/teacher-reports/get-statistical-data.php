<h2 class="-color-secondary"><?php echo CommonHelper::displayMoneyFormat( $earningData['earning'] ); ?></h2> <?php if($earningData['fromDate']){
	echo $earningData['fromDate']. ' - ' .$earningData['toDate'];
} ?>