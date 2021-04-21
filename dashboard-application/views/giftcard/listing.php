<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

	<div class="table-scroll">
	 <table class="table">
		 <tbody><tr class="-hide-mobile">
			
			<th><?php echo Label::getLabel('LBL_Order_id'); ?></th>
			<th><?php echo Label::getLabel('LBL_Gift_Card_Code'); ?></th>
			<th><?php echo Label::getLabel('LBL_Amount'); ?></th>
			<th><?php echo Label::getLabel('LBL_Recepient_Details'); ?></th>
			<th><?php echo Label::getLabel('LBL_Status'); ?></th>
			<!--th><?php echo Label::getLabel('LBL_Actions'); ?></th-->
		</tr>
		<?php $i=1; foreach($giftcardList as $giftcard){ ?>
		
		<tr>
                                    <td width="15%">
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Order_id');?></span>
                                        <span class="td__data"><?php echo $giftcard['order_id']; ?></span>
                                    </td>
                                    <td width="15%">
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Gift_Card_Code');?></span>
                                        <span class="td__data"><?php echo $giftcard['giftcard_code']; ?></span>
                                    </td>
                                    <td width="15%">
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Amount');?></span>
                                        <span class="td__data"><?php echo $giftcard['giftcard_amount']; ?></span>
                                    </td>									
                                    <td width="35%">
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Recepient_Details');?></span>
                                        <span class="td__data"><?php echo "Name: ". $giftcard['recipient_name']."<br><br> Email: ".$giftcard['recipient_email'] ; ?></span>
                                    </td>
                                    <td width="15%">
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Status');?></span>
                                        <?php $spnCls = ($giftcard['giftcard_status'] == Giftcard::GIFTCARD_USED_STATUS)?'default':'primary'; ?>
                                        <span class="td__data"><span class="label label--<?php echo $spnCls; ?>"><?php echo $giftCardStatus[$giftcard['giftcard_status']]; ?></span></span>
                                    </td>									
                                    <!--td>
                                        <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Actions');?></span>
                                        <span class="td__data">
                                            <a href="javascript:void(0);" onclick="sendMessageToTeacher('<?php //echo $teacherCourseData['slesson_teacher_id']; ?>');" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Message');?></a>
                                            <a href="javascript:void(0);" onclick="removeTeachers('<?php //echo $teacherCourseData['slesson_teacher_id']; ?>');" class="btn btn--small"><?php echo Label::getLabel('LBL_Remove');?></a>
                                        </span>
                                    </td-->
                                </tr>
		<?php $i++; } ?>
		
	 </tbody></table>
<?php	 	echo FatUtility::createHiddenFormFromData ( $postedData, array (
			'name' => 'frmSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false); ?>
	 </div>
	 <?php if(count($giftcardList) == 0) { 
		$this->includeTemplate('_partial/no-record-found.php');
	 } ?>
