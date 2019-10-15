<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
	'listserial'=> Label::getLabel('LBL_S.No.',$adminLangId),
	'slesson_order_id'=>Label::getLabel('LBL_Order_Id',$adminLangId),
	'issrep_slesson_id'=>Label::getLabel('LBL_Lesson_Id',$adminLangId),
	'issrep_reported_by'=> Label::getLabel('LBL_Reported_By',$adminLangId),	
	'reporter_username'	=> Label::getLabel('LBL_Reporter',$adminLangId),
	//'language'=>Label::getLabel('LBL_Language',$adminLangId),		
	'issrep_status'=>Label::getLabel('LBL_Status',$adminLangId),	
	'action' => Label::getLabel('LBL_Action',$adminLangId),
);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = $page==1 ? 0: $pageSize*($page-1);
foreach ($arr_listing as $sn=>$row){
	
	$sr_no++;
	$tr = $tbl->appendElement('tr', array( ) );
	
	foreach ( $arr_flds as $key => $val ){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'issrep_reported_by':
				$str = User::getUserTypesArr($adminLangId)[$row[$key]];
				$td->appendElement('plaintext', array(), $str, true);
			break;
			case 'issrep_status':
				if($row[$key] == IssuesReported::STATUS_RESOLVED){
					$issueStatusArr = IssuesReported::getStatusArr($adminLangId,true);
				}else{
					$issueStatusArr = IssuesReported::getStatusArr($adminLangId);
				}
				$str = IssuesReported::getStatusArr($adminLangId)[$row[$key]];
				$select = new HtmlElement('select',array('id'=>'user_confirmed_select_'.$row['issrep_id'],'name'=>'order_is_paid','onchange'=>"updateIssueStatus('".$row['issrep_id']."',this.value)"));
				foreach($issueStatusArr as $status_key=>$status_value){
					if($status_key == $row[$key]){
						$select->appendElement('option',array('value'=>$status_key,'selected'=>'selected'), $status_value);
					}
					else{
						$select->appendElement('option',array('value'=>$status_key), $status_value);
					}
				}
				$td->appendHtmlElement($select);				
			break;
			case 'action':
				//$td->appendElement("a",array('href'=>CommonHelper::generateUrl('PurchasedLessons','viewSchedules',array($row['issrep_id'])), 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId)),'View Schedules',true);
				/*$ul = $td->appendElement("ul",array("class"=>"actions"));
				$li = $ul->appendElement("li");
				$li->appendElement('a', array('href'=>'javascript::void(0)', 'class'=>'button small green','title'=>'View Issue Detail','onclick'=>'viewDetail('.$row['issrep_id'].')'),'<i class="ion-eye icon"></i>', true);*/

				$ul = $td->appendElement("ul",array("class"=>"actions actions--centered"));
					$li = $ul->appendElement("li",array('class'=>'droplink'));						
    			    $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
					$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));	
					$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
              		
					$innerLi=$innerUl->appendElement('li');
					$innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId),"onclick"=>"viewDetail(". $row['issrep_slesson_id'] .")"),Label::getLabel('LBL_View',$adminLangId), true);

					$innerLi=$innerUl->appendElement("li");
					$innerLi->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
					'title'=>Label::getLabel('LBL_Transactions',$adminLangId),"onclick"=>"transactions(".$row['issrep_slesson_id'].",".$row['issrep_id'].")"),Label::getLabel('LBL_Transactions',$adminLangId), true);
				
			break;
			default:
				$td->appendElement('plaintext', array(), $row[$key], true);
			break;
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Label::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();
$postedData['page']=$page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmUserSearchPaging'
) );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'pageSize'=>$pageSize,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>