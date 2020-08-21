<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if($user_id > 0){
	$fld_credential_username = $frmUser->getField('credential_username');
	$fld_credential_username->setFieldTagAttribute('disabled','disabled');

	$user_email = $frmUser->getField('credential_email');
	$user_email->setFieldTagAttribute('disabled','disabled');	
}

$frmUser->developerTags['colClassPrefix'] = 'col-md-';
$frmUser->developerTags['fld_default_col'] = 12;	

$frmUser->setFormTagAttribute('class', 'web_form form_horizontal');
$frmUser->setFormTagAttribute('onsubmit', 'setupUsers(this); return(false);');

$countryFld = $frmUser->getField('user_country_id');
$countryFld->setFieldTagAttribute('id','user_country_id');
$countryFld->setFieldTagAttribute('onChange','getCountryStates(this.value,'.$stateId.',\'#user_state_id\')');

//$stateFld = $frmUser->getField('user_state_id');
//$stateFld->setFieldTagAttribute('id','user_state_id');

?>
<section class="section">
	<div class="sectionhead">
		<h4><?php echo Label::getLabel('LBL_User_Setup',$adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">      
		<div class="tabs_nav_container responsive flat">
			<div class="tabs_panel_wrap">
				<div class="tabs_panel">
					<?php echo $frmUser->getFormHtml(); ?>
				</div>
			</div>						
		</div>
	</div>						
</section>	
<script >
	$(document).ready(function(){
		getCountryStates($( "#user_country_id" ).val(),<?php echo $stateId ;?>,'#user_state_id');
	});	
</script>