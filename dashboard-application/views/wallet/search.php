<?php  defined('SYSTEM_INIT') or die('Invalid Usage.');
$user_timezone = MyDate::getUserTimeZone();
$arr_flds = array(
	'utxn_id'	=>	Label::getLabel('LBL_Txn_ID', $siteLangId),
	'utxn_date'	=>	Label::getLabel('LBL_Date', $siteLangId),
	'utxn_credit' =>	Label::getLabel('LBL_Credit', $siteLangId),
	'utxn_debit'	=>	Label::getLabel('LBL_Debit', $siteLangId),
	'balance'	=>	Label::getLabel('LBL_Balance', $siteLangId),
	'utxn_comments'	=>	Label::getLabel('LBL_Comments', $siteLangId),
	'utxn_status'	=>	Label::getLabel('LBL_Status', $siteLangId),
);

$tbl = new HtmlElement('table', array('class'=>'table'));
$th = $tbl->appendElement('thead')->appendElement('tr',array('class' => '-hide-mobile'));
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($arrListing as $sn => $row){
	$sr_no++;
	$tr = $tbl->appendElement('tr',array('class' =>'' ));
	
	foreach ($arr_flds as $key=>$val){
        $width = '';
        if($key=='utxn_comments'){
            $width = '40%';
        }
		$td = $tr->appendElement('td',array('width'=>$width));
		$td->appendElement('span', array('class'=>'td__caption -hide-desktop -show-mobile'), $val, true);
		switch ($key){
			case 'utxn_id':
				$td->appendElement('span', array('class'=>'td__data'), Transaction::formatTransactionNumber($row[$key]), true);
			break;
			case 'utxn_date':
				$utxn_date = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d', $row[$key], true , $user_timezone );
				$td->appendElement('span', array('class'=>'td__data'), $utxn_date, true);
				
			break;
			case 'utxn_status':
            if($row[$key] == Transaction::STATUS_COMPLETED){
                $cls = 'success';
            }else{
                $cls = 'process';
            }
                $statusSpn = '<span class="label label--'.$cls.'">'.$statusArr[$row[$key]].'</span>';
				$td->appendElement('span', array('class'=>'td__data'), $statusSpn, true);
			break;
			case 'utxn_credit':
				$txt = CommonHelper::displayMoneyFormat( $row[$key] );
				$td->appendElement('span', array('class'=>'td__data'), $txt, true);
			break;
			case 'utxn_debit':
				$txt = CommonHelper::displayMoneyFormat( $row[$key] );
				$td->appendElement('span', array('class'=>'td__data'), $txt, true);
			break;
			case 'balance':
				$txt = CommonHelper::displayMoneyFormat( $row[$key] );
				$td->appendElement('span', array('class'=>'td__data'), $txt, true);
			break;
			case 'utxn_comments':
				$td->appendElement('span', array('class'=>'td__data'), Transaction::formatTransactionComments($row[$key]), true);				
			break;
			default:
				$td->appendElement('span', array('class'=>'td__data'), $row[$key], true);								
			break;
		}
	}
}

if (count($arrListing) == 0){
	//$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Label::getLabel('LBL_Unable_to_find_any_record', $siteLangId));
	$this->includeTemplate('_partial/no-record-found.php' , array('siteLangId'=>$siteLangId),false);
}
else{
	echo $tbl->getHtml();
}

$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData ( $postedData, array ('name' => 'frmCreditSrchPaging') );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount, 'callBackJsFunc' => 'goToCreditSearchPage');
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);