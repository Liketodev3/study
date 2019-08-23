<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
	'listserial'	=>	Label::getLabel('LBL_Sr._No',$adminLangId),
	'blocation_name'	=>	Label::getLabel('LBL_Title',$adminLangId),
	'blocation_banner_width'	=>	Label::getLabel('LBL_Preffered_Width_(in_pixels)',$adminLangId),
	'blocation_banner_height'	=>	Label::getLabel('LBL_Preffered_Height_(in_pixels)',$adminLangId),
	'blocation_active'	=>	Label::getLabel('LBL_Status',$adminLangId),
	'action'	=>	Label::getLabel('LBL_Action',$adminLangId),
);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive table--hovered'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = $page==1?0:$pageSize*($page-1);
foreach ($arr_listing as $sn=>$row){
	$sr_no++;
	/* $tr = $tbl->appendElement('tr',array('class' => ($row['blocation_active'] != applicationConstants::ACTIVE) ? 'fat-inactive' : '' )); */
	$tr = $tbl->appendElement('tr',array());
	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
			$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'blocation_name':				
			$td->appendElement('plaintext', array(), $row['blocation_name'],true);				
			break;
			case 'blocation_active':				
				$active = "";
				$statusAct='';
				if( $row['blocation_active'] == applicationConstants::YES &&  $canEdit === true ) {
					$active = 'checked';
					$statusAct = 'inactiveStatusBannerLocation(this)';
				}
				
				if( $row['blocation_active'] == applicationConstants::NO &&  $canEdit === true ) {
					$active = '';
					$statusAct = 'activeStatusBannerLocation(this)';
				}
				
					$statusClass = ( $canEdit === false ) ? 'disabled' : '';
				$str='<label class="statustab -txt-uppercase">                 
				<input '.$active.' type="checkbox" id="switch'.$row['blocation_id'].'" value="'.$row['blocation_id'].'" onclick="'.$statusAct.'" class="switch-labels status_'.$row['blocation_id'].'"/>
				<i class="switch-handles '.$statusClass.'"></i></label>';
				$td->appendElement('plaintext', array(), $str,true);
			
			break;
		
			case 'action':
			$ul = $td->appendElement("ul",array("class"=>"actions actions--centered"));
			if( $canEdit ){
				$li = $ul->appendElement("li",array('class'=>'droplink'));


				$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
				$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
				$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
				$innerLiEdit=$innerUl->appendElement('li');

				$innerLiEdit->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>Label::getLabel('LBL_Edit',$adminLangId),"onclick"=>"addBannerLocation(".$row['blocation_id'].")"),Label::getLabel('LBL_Edit',$adminLangId), true);
				$innerLiBanner=$innerUl->appendElement('li');
				$url=CommonHelper::generateUrl('banners','listing',array($row['blocation_id']));
				$innerLiBanner->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>Label::getLabel('LBL_Banners',$adminLangId),'onclick'=>'redirecrt("'.$url.'")'),Label::getLabel('LBL_Banners',$adminLangId), true);		
			}
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
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
	'name' => 'frmBannerSearchPaging'
) );
$pagingArr = array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>