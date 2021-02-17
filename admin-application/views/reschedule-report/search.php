<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 

$arr_flds = array(
	'listserial'=>Label::getLabel('LBL_Sr_no.',$adminLangId),
	'user_name'=>Label::getLabel('LBL_Name',$adminLangId),
	'user_type'=>Label::getLabel('LBL_User_Type',$adminLangId),
	'teacherRescheduledLessons'=>Label::getLabel('LBL_Rescheduled_Lessons',$adminLangId),
	'teacherCancelledLessons'=>Label::getLabel('LBL_Cancelled_Lessons',$adminLangId),
	'action' => Label::getLabel('LBL_Action', $adminLangId)
	//'teacherSchLessons'=>Label::getLabel('LBL_Completed_Lessons',$adminLangId),
	
	//'studentIds'=>Label::getLabel('LBL_No._Students',$adminLangId),
	//'teacher_rating' => Label::getLabel('LBL_Rating', $adminLangId) 
);
	

$tbl = new HtmlElement('table', 
array('width'=>'100%', 'class'=>'table table-responsive table--hovered'));

$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = $page==1?0:$pageSize*($page-1);

foreach ($arr_listing as $sn=>$row){ 
	$sr_no++;
	$tr = $tbl->appendElement('tr');
		
	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'user_name':
				$td->appendElement('plaintext', array(), $row[$key] ,true);
			break;
			case 'user_type':
				if ( $row['user_is_teacher'] == 1 && $row['user_is_learner'] == 1 ) {
					$td->appendElement('plaintext', array(), 'Teacher/Learner' ,true);
				} else if ( $row['user_is_teacher'] == 1 && $row['user_is_learner'] == 0 ) {
					$td->appendElement('plaintext', array(), 'Teacher' ,true);
				} else if ( $row['user_is_teacher'] == 0 && $row['user_is_learner'] == 1 ) {
					$td->appendElement('plaintext', array(), 'Learner' ,true);
				}
			break;
			case 'teacherRescheduledLessons':
				$td->appendElement('plaintext', array(), $row[$key] ,true);
			break;
			case 'teacherCancelledLessons':
				$td->appendElement('plaintext', array(), $row[$key] ,true);
			break;
			// case 'action':
			// 	$queryArray = array( $row['user_id'] );
			// 	$td->appendElement("a",array('href'=>CommonHelper::generateUrl('RescheduleReport','export', $queryArray), 
			// 		'class'=>'button small green',
			// 		'title'=>Label::getLabel('LBL_Export_History',$adminLangId)), 
			// 		Label::getLabel('LBL_Export_History', $adminLangId), true);
			// break;
			// case 'action':
			// 	$td->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green',
			// 	'title'=>Label::getLabel('LBL_Export', $adminLangId),"onclick"=>"exportReport(".$row['user_id'].")"),
			// 	Label::getLabel('LBL_Export', $adminLangId), true);
			// break;
			case 'action':
				$ul = $td->appendElement("ul",array("class"=>"actions actions--centered"));
				$li = $ul->appendElement("li",array('class'=>'droplink'));
				$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green',
					'title'=>Label::getLabel('LBL_Download_Reports',$adminLangId)),
					'<i class="ion-android-more-horizontal icon"></i>', true);
				$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
				$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
				
				// $innerLiBoth=$innerUl->appendElement('li');
				// $innerLiBoth->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
				// 	'title'=>Label::getLabel('LBL_Download_Both_Report',$adminLangId),
				// 	"onclick"=>"exportReport(".$row['user_id'].", ".LessonStatusLog::BOTH_REPORT.")"),
				// 	Label::getLabel('LBL_Both',$adminLangId), true);

				// $innerLiReschedule=$innerUl->appendElement('li');
				// $innerLiReschedule->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
				// 	'title'=>Label::getLabel('LBL_Download_Reschedule_Report',$adminLangId),
				// 	"onclick"=>"exportReport(".$row['user_id'].", ".LessonStatusLog::NOT_CANCELLED_REPORT.")"),
				// 	Label::getLabel('LBL_Reschedule',$adminLangId), true);
				
				// $innerLiCancelled=$innerUl->appendElement('li');
				// $innerLiCancelled->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 
				// 	'title'=>Label::getLabel('LBL_Download_Cancelled_Report',$adminLangId),
				// 	"onclick"=>"exportReport(".$row['user_id'].", ".LessonStatusLog::CANCELLED_REPORT.")"),
				// 	Label::getLabel('LBL_Cancelled',$adminLangId), true);

				$innerLiCancelled=$innerUl->appendElement('li');
				$innerLiCancelled->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 
					'title'=>Label::getLabel('LBL_View_Report',$adminLangId),
					"onclick"=>"viewReport(".$row['user_id'].", ".LessonStatusLog::BOTH_REPORT.")"),
					Label::getLabel('LBL_View_Report',$adminLangId), true);

				$innerLiCancelled=$innerUl->appendElement('li');
				$innerLiCancelled->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 
					'title'=>Label::getLabel('LBL_View_Rescheduled_Report',$adminLangId),
					"onclick"=>"viewReport(".$row['user_id'].", ".LessonStatusLog::NOT_CANCELLED_REPORT.")"),
					Label::getLabel('LBL_View_Rescheduled_Report',$adminLangId), true);

				$innerLiCancelled=$innerUl->appendElement('li');
				$innerLiCancelled->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 
					'title'=>Label::getLabel('LBL_View_Cancelled_Report',$adminLangId),
					"onclick"=>"viewReport(".$row['user_id'].", ".LessonStatusLog::CANCELLED_REPORT.")"),
					Label::getLabel('LBL_View_Cancelled_Report',$adminLangId), true);
			break;
			
			case 'studentIds':
				if ( $row[$key] !='' ) {
					$idsArray = explode(',', $row[$key]);
				} else {
					$idsArray = array();
				}
				$td->appendElement('plaintext', array(), count($idsArray) ,true);
			break;
			
			default:
				$td->appendElement('plaintext', array(), $row[$key], true);
			break;
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array(
	'colspan'=>count($arr_flds)), 
	Label::getLabel('LBL_No_Records_Found',$adminLangId)
	);
}
echo $tbl->getHtml();
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmRescheduledReportSearchPaging'
) );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>