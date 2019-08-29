<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$user_timezone = MyDate::getUserTimeZone();
?>
<div class="box -padding-20">
	<div class="table-scroll">
	 <table class="table">
		 <tbody><tr class="-hide-mobile">
			
			<th><?php echo Label::getLabel('LBL_Order_Id'); ?></th>
			<th><?php echo Label::getLabel('LBL_Free_trial'); ?></th>
			<th><?php echo Label::getLabel('LBL_Teacher'); ?></th>
			<th><?php echo Label::getLabel('LBL_Order_Amount'); ?></th>			
			<th><?php echo Label::getLabel('LBL_Order_Status'); ?></th>
			<th><?php echo Label::getLabel('LBL_Order_Date'); ?></th>
			<th><?php echo Label::getLabel('LBL_Actions'); ?></th>
		</tr>
		<?php $i=1; foreach($ordersData['Orders'] as $order){ ?>
		
		<tr>
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Order_Id'); ?></span>
                                        <span class="td__data"><?php echo $order['order_id'];  ?></span>
                                    </td>
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Free_trial'); ?></span>
                                        <span class="td__data"><?php $str = $order['op_lpackage_is_free_trial'] ? 'Yes' : 'No'; echo $str;  ?></span>
                                    </td>
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Teacher'); ?></span>
                                        <span class="td__data"><?php echo $order['teacher_name']; ?></span>
                                    </td>
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Order_Amount'); ?></span>
                                        <span class="td__data"><?php echo CommonHelper::displayMoneyFormat($order['order_net_amount']); ?></span>
                                    </td>
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Order_Status'); ?></span>
                                        <?php $spnCls = ($order['order_is_paid'] == Order::ORDER_IS_PAID)?'success':(($order['order_is_paid'] == Order::ORDER_IS_CANCELLED)?'danger':'process'); ?>
                                        <span class="td__data"><span class="label label--<?php echo $spnCls; ?>"><?php echo $statusArr[$order['order_is_paid']]; ?></span></span>
                                    </td>									
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Order_Date'); ?></span>
                                        <span class="td__data"><?php echo  MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', $order['order_date_added'], true , $user_timezone ); ?></span>
                                    </td>									
                                    <td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Actions'); ?></span>
                                        <span class="td__data">
                                            <?php /*<a href="javascript:void(0);" onclick="sendMessageToLearner('<?php echo $teacherCourseData['slesson_learner_id']; ?>');" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Message'); ?></a> */ ?>
                                            <a href="javascript:void(0)" onClick="generateThread(<?php echo $order['op_teacher_id']; ?>)" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Message'); ?></a>                                            
                                        </span>
                                    </td>
                                </tr>
		<?php $i++; } ?>
		
	 </tbody></table>
<?php
 	echo FatUtility::createHiddenFormFromData ( $postedData, array (
			'name' => 'frmOrderSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $ordersData['pagingArr'],false); ?>	 
	 </div>
	 <?php if(count($ordersData['Orders']) == 0) { 
		$this->includeTemplate('_partial/no-record-found.php');
	 } ?>
</div>
<script>

</script>