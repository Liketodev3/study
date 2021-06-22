<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
	'listserial'	=>	'Sr. No',
	'gdprdatareq_reason' => Label::getLabel('LBL_Reason_For_Erasure', $adminLangId),
	'gdprdatareq_type' => Label::getLabel('LBL_Erasure_Type', $adminLangId),
	'gdprdatareq_user_name' => Label::getLabel('LBL_User_Name', $adminLangId),
	'gdprdatareq_added_on' => Label::getLabel('LBL_Added_On', $adminLangId),
	'gdprdatareq_updated_on' => Label::getLabel('LBL_Updated_On', $adminLangId),
	'gdprdatareq_status' => Label::getLabel('LBL_Status', $adminLangId),
	'action' => Label::getLabel('LBL_Action',$adminLangId),
);

$tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table table--hovered table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}
$sr_no = $page == 1 ? 0 : $pageSize * ($page - 1);
foreach ($gdprRequests as $sn => $row) {
	$userFullName = User::getAttributesById($row['gdprdatareq_user_id'], 'concat(user_first_name, " " ,user_last_name)');
	$sr_no++;
	$tr = $tbl->appendElement('tr');
	if($row['gdprdatareq_type'] == 1){
		$gdprdatareq_type = Label::getLabel('LBL_Truncate_Data',$adminLangId);
	}else if($row['gdprdatareq_type'] == 2){
		$gdprdatareq_type = Label::getLabel('LBL_Anonymize_Data',$adminLangId);
	}else{
		$gdprdatareq_type = Label::getLabel('LBL_Not_Mentioned',$adminLangId);
	}
	foreach ($arr_flds as $key => $val) {
		$td = $tr->appendElement('td');
		switch ($key) {
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
				break;
			case 'gdprdatareq_reason':
				$td->appendElement('plaintext', array(), $row['gdprdatareq_reason']);
				break;
			case 'gdprdatareq_type':
				$td->appendElement('plaintext', array(), $gdprdatareq_type);
				break;
			case 'gdprdatareq_user_name':
				$td->appendElement('plaintext', array(), $userFullName);
				break;
			case 'gdprdatareq_added_on':
				$td->appendElement('plaintext', array(), FatDate::format($row[$key],true));
				break;
			case 'gdprdatareq_updated_on':
				$td->appendElement('plaintext', array(), FatDate::format($row[$key],true));
				break;
			case 'gdprdatareq_status':
				$td->appendElement('plaintext', array(), $gdprStatus[$row[$key]], true);
			break;
			case 'action':
				$ul = $td->appendElement("ul",array("class"=>"actions"));
				if($canEdit){
					$li = $ul->appendElement("li");
					$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>'Edit',"onclick"=>"view(".$row['gdprdatareq_id'].")"),'<i class="ion-eye icon"></i>', true);
				}
			break;
			default:
				$td->appendElement('plaintext', array(), $row[$key]);
				break;
		}
	}
}
if (count($gdprRequests) == 0) {
	$tbl->appendElement('tr')->appendElement('td', array('colspan' => count($arr_flds)), Label::getLabel('LBL_No_Records_Found', $adminLangId));
}
echo $tbl->getHtml();
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array(
	'name' => 'frmSearchPaging'
));
$pagingArr = array('pageCount' => $pageCount, 'page' => $page, 'pageSize' => $pageSize, 'recordCount' => $recordCount, 'adminLangId' => $adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
?>
