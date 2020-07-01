<?php
	defined('SYSTEM_INIT') or die('Invalid Usage.');

	$arr_flds = array(
	    'listserial'=>Label::getLabel('LBL_Sr_no.',$adminLangId),
	    'slesson_id'=>Label::getLabel('LBL_Lesson_Id',$adminLangId),
	    'slesson_date'=>Label::getLabel('LBL_Lesson_Date',$adminLangId),
	    'slesson_start_time'=>Label::getLabel('LBL_Lesson_Start_Time', $adminLangId),
	    'slesson_ended_on'=>Label::getLabel('LBL_Lesson_Ended_On',$adminLangId),
	    'slesson_ended_by'=>Label::getLabel('LBL_Lesson_Ended_By',$adminLangId),
	    'teacherTeachLanguageName'=>Label::getLabel('LBL_Language',$adminLangId),
	    'slesson_status'=>Label::getLabel('LBL_Status',$adminLangId),
	    'action' => Label::getLabel('LBL_Action',$adminLangId),
	  );

	$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive'));

	$th = $tbl->appendElement('thead')->appendElement('tr');
	$orderStatus = Order::getPaymentStatusArr($adminLangId);
	$userType = User::getUserTypesArr($adminLangId);
	$yesAndNoArr = applicationConstants::getYesNoArr($adminLangId);
	$statusArr = ScheduledLesson::getStatusArr();
	foreach ($arr_flds as $val) {
		$e = $th->appendElement('th', array(), $val);
	}

	$sr_no = $page == 1 ? 0: $pageSize * ($page-1);
	foreach ($arr_listing as $sn=>$row) {

		$sr_no++;
		$tr = $tbl->appendElement('tr', array( ) );

		foreach ( $arr_flds as $key => $val ){
			$td = $tr->appendElement('td');

			switch ($key) {
				case 'listserial':
					$td->appendElement('plaintext', array(), $sr_no);
				break;
				case 'slesson_date':
				  $date = ($row[$key] == "0000-00-00") ?  Label::getLabel('LBL_N/A') : date('l, F d, Y',strtotime($row[$key]));
				  $td->appendElement('plaintext', array(), $date,true);
				break;
				case 'slesson_ended_on':
		          $endTime = ($row[$key] == "0000-00-00 00:00:00") ?  Label::getLabel('LBL_N/A') : date('h:i A',strtotime($row[$key]));
		          $td->appendElement('plaintext', array(), $endTime,true);
		        break;
				case 'slesson_start_time':
		          $startTime = ($row[$key] == "00:00:00" && $row['slesson_date'] == "0000-00-00") ?  Label::getLabel("LBL_N/A") : date('h:i A',strtotime($row[$key]));
		          $td->appendElement('plaintext', array(), $startTime,true);
		        break;
				case 'slesson_ended_by':
		          $str = (!empty($row[$key])) ? $userType[$row[$key]] : Label::getLabel('LBL_N/A');
		          $td->appendElement('plaintext', array(), $str,true);
		        break;
				case 'lpackage_is_free_trial':
				  $td->appendElement('plaintext', array(), $yesAndNoArr[$row[$key]],true);
				break;
				case 'slesson_status':
					$select = new HtmlElement('select',array('id'=>'user_confirmed_select_'.$row['slesson_id'],'name'=>'order_is_paid','onchange'=>"updateScheduleStatus('".$row['slesson_id']."',this.value)"));
					foreach($statusArr as $status_key => $status_value){
						$option = $select->appendElement('option',array('value'=>$status_key), $status_value);
						  if($status_key == $row[$key]){
							$option->setAttribute('selected','selected');
						  }
					}
					$td->appendHtmlElement($select);
				break;
				case 'action':
				  $ul = $td->appendElement("ul",array("class"=>"actions"));
				  $li = $ul->appendElement("li");
				  $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>'View Order Detail','onclick'=>'viewDetail('.$row['slesson_id'].');'),'<i class="ion-eye icon"></i>', true);
				 break;
				default:
		          $td->appendElement('plaintext', array(), $row[$key],true);
		        break;
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Label::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmPurchaseLessonSearchPaging'
) );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'pageSize'=>$pageSize,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>
