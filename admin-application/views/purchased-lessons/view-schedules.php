<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>

<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">
					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Manage_Lessons',$adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div>

				<section class="section">
					<div class="sectionhead">
						<h4><?php echo Label::getLabel('LBL_View_Schedules',$adminLangId); ?></h4>
					</div>
					<div class="sectionbody">
						<div class="tablewrap" >
							<div id="listing">

<?php
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
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive table--hovered'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($arr_listing as $sn=>$row){
	/* echo '<pre>';
	print_r( $row );
	echo '</pre>';
	die(); */
	$sr_no++;
	$tr = $tbl->appendElement('tr');

	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'lpackage_active':
					$active = "";
					$statusAct='';
					if($row['lpackage_active'] == applicationConstants::YES &&  $canEdit === true ) {
						$active = 'checked';
						$statusAct = 'inactiveStatus(this)';
					}
					if($row['lpackage_active'] == applicationConstants::NO &&  $canEdit === true ) {
						$active = '';
						$statusAct = 'activeStatus(this)';
					}
					
					$statusClass = ( $canEdit === false ) ? 'disabled' : '';
					$str='<label class="statustab -txt-uppercase">                 
                     <input '.$active.' type="checkbox" id="switch'.$row['lpackage_id'].'" value="'.$row['lpackage_id'].'" onclick="'.$statusAct.'" class="switch-labels status_'.$row['lpackage_id'].'"/>
                    <i class="switch-handles '.$statusClass.'"></i></label>';
					$td->appendElement('plaintext', array(), $str,true);
			break;
			case 'slesson_status':
				$select = new HtmlElement('select',array('id'=>'user_confirmed_select_'.$row['slesson_id'],'name'=>'order_is_paid','onchange'=>"updateScheduleStatus('".$row['slesson_id']."',this.value)"));
				foreach($status_arr as $status_key=>$status_value){
					if($status_key == $row[$key]){
						$select->appendElement('option',array('value'=>$status_key,'selected'=>'selected'), $status_value);
					}
					else{
						$select->appendElement('option',array('value'=>$status_key), $status_value);
					}
				}
				$td->appendHtmlElement($select);
			break;
			case 'lpackage_is_free_trial':
				$td->appendElement('plaintext', array(), applicationConstants::getYesNoArr($adminLangId)[$row[$key]],true);
			break;
			case 'slesson_ended_by':
				$str = '-NA-';
				if($row[$key]){
					$str = User::getUserTypesArr($adminLangId)[$row[$key]];
				}				
				$td->appendElement('plaintext', array(), $str,true);
			break;
			case 'slesson_start_time':
				$td->appendElement('plaintext', array(), date('h:i A',strtotime($row[$key])),true);
			break;
			case 'slesson_ended_on':
				$td->appendElement('plaintext', array(), date('h:i A',strtotime($row[$key])),true);
			break;
			case 'slesson_date':
				$td->appendElement('plaintext', array(), date('l, F d, Y',strtotime($row[$key])),true);
			break;
			case 'slanguage_name':
				$str = $row[$key];
				if( $row['slesson_start_time'] != '00:00:00' && $row['slesson_end_time'] != '0000:00:00' ){
					$to_time = strtotime($row['slesson_start_time']);
					$from_time = strtotime($row['slesson_end_time']);
					$minutes = round(abs($to_time - $from_time) / 60,2);
					$isTrial = ( $minutes == 30 ) ? ' Trial ' : ' ';
					$str.= "<br>".$minutes." Minutes".$isTrial."Lesson";
				} 
				$td->appendElement('plaintext', array(), $str ,true);
			break;
			case 'lpackage_identifier':
				if($row['lpackage_title']!=''){
					$td->appendElement('plaintext', array(), $row['lpackage_title'],true);
					$td->appendElement('br', array());
					$td->appendElement('plaintext', array(), '('.$row[$key].')',true);
				}else{
					$td->appendElement('plaintext', array(), $row[$key],true);
				}
			break;
			case 'action':
				$ul = $td->appendElement("ul",array("class"=>"actions"));
				$li = $ul->appendElement("li");
				$li->appendElement('a', array('href'=>'javascript::void(0)', 'class'=>'button small green','title'=>'View Order Detail','onclick'=>'viewDetail('.$row['slesson_id'].');'),'<i class="ion-eye icon"></i>', true);
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
echo $tbl->getHtml(); ?>							
							
							</div>
						</div> 
					</div>
				</section>
		</div>
	</div>
</div>
</div>