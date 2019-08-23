<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<form class="form">
<?php
$arr_flds = array(
		'listserial'=>Label::getLabel('LBL_Sr._No',$adminLangId),
		'module'=>Label::getLabel('LBL_Module',$adminLangId),	
		'permission'=>Label::getLabel('LBL_Permissions',$adminLangId),			
	);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($arr_listing as $sn=>$row){
	$sr_no++;
	$tr = $tbl->appendElement('tr');
	
	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;			
			case 'module':
				$td->appendElement('plaintext', array(), $row, true);
			break;
			case 'permission':
				if($canViewAdminPermissions){
					$listing = AdminPrivilege::getPermissionArr();
					$options = '';
					foreach($listing as $key=>$list){
						$selected = '';
						if(isset($userData[$sn]) && !empty($userData[$sn]) && $userData[$sn]['admperm_value'] == $key){
							$selected = 'selected';
						}
						$options.= "<option value=".$key." ".$selected.">".$list."</option>";
					}
					$td->appendElement('plaintext', array(), "<select name='permission' onChange='updatePermission(".$sn.",this.value)'>".$options."</select>",true);
				}
			break;			
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Label::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();
?></form>