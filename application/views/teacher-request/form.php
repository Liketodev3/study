<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute( 'class', 'form' );
$frm->setFormTagAttribute( 'onsubmit', 'setUpTeacherApproval(this); return false;' );
$frm->developerTags['colClassPrefix'] = 'col-sm-';
$frm->developerTags['fld_default_col'] = 12;

$general_fields_heading = $frm->getField( 'general_fields_heading' );
$general_fields_heading->value = '<h5>' . Label::getLabel('LBL_General') . '</h5>';

$language_fields_heading = $frm->getField( 'language_fields_heading' );
$language_fields_heading->value = '<h5>' . Label::getLabel('LBL_Languages') . '</h5>';

$resume_fields_heading = $frm->getField( 'resume_fields_heading' );
$resume_fields_heading->value = '<div id="resume_fields_heading" class="d-flex justify-content-between align-items-center"><div><h5 style="margin-bottom: 0;">'. Label::getLabel('LBL_Resume') .'<span class="spn_must_field">*</span></h5></div><div><a onclick="teacherQualificationForm(0);" href="javascript:void(0)" class="btn btn--secondary btn--small">' . Label::getLabel('LBL_Add_New') . '</a></div></div>';

$utrvalue_user_language_teach = $frm->getField('utrvalue_user_teach_slanguage_id[]');
$utrvalue_user_language_teach->developerTags['col'] = 10;

$add_anotherteach_language = $frm->getField( 'add_anotherteach_language' );
$add_anotherteach_language->developerTags['col'] = 2;
$add_anotherteach_language->value = '<label class="field_label -display-block"></label><a title="'. Label::getLabel('LBL_Add_New_Language') .'" href="javascript:void(0)" onClick="addNewTeachLanguageRow()" class="inline-action inline-action--add">+</a>';


$utrvalue_user_language_speak = $frm->getField('utrvalue_user_language_speak[]');
$utrvalue_user_language_speak->developerTags['col'] = 5;

$utrvalue_user_language_speak_proficiency = $frm->getField('utrvalue_user_language_speak_proficiency[]');
$utrvalue_user_language_speak_proficiency->developerTags['col'] = 5;

$add_anotherspoken_language = $frm->getField( 'add_anotherspoken_language' );
$add_anotherspoken_language->developerTags['col'] = 2;
$add_anotherspoken_language->value = '<label class="field_label -display-block"></label><a title="'. Label::getLabel('LBL_Add_New_Language') .'" href="javascript:void(0)" onClick="addNewLanguageRow()" class="inline-action inline-action--add">+</a>';

$resume_listing_html = $frm->getField( 'resume_listing_html' );
$resume_listing_html->addWrapperAttribute("id", "resume_listing");

$fldFirstName = $frm->getField( 'utrvalue_user_first_name' );
$fldFirstName->developerTags['col'] = 6;

$fldLastName = $frm->getField( 'utrvalue_user_last_name' );
$fldLastName->developerTags['col'] = 6;

$fldGender = $frm->getField( 'utrvalue_user_gender' );
$fldGender->developerTags['col'] = 6;
$fldGender->setOptionListTagAttribute( 'class', 'list-inline list-inline--onehalf' );

$fldPhone = $frm->getField( 'utrvalue_user_phone' );
$fldPhone->developerTags['col'] = 6;

$fldProfilePic = $frm->getField( 'user_profile_pic' );
$fldProfilePic->developerTags['col'] = 6;

$fldPhotId = $frm->getField( 'user_photo_id' );
$fldPhotId->developerTags['col'] = 6;

$introduction_fields_heading = $frm->getField( 'introduction_fields_heading' );
$introduction_fields_heading->value = '<h5>' . Label::getLabel('LBL_Introduction') . '</h5>';

$about_me_fields_heading = $frm->getField( 'about_me_fields_heading' );
$about_me_fields_heading->value = '<h5>' . Label::getLabel('LBL_Biography') . '</h5>';

$termLink = ' <a target="_blank" class = "-link-underline link-color" href="'.$termsAndConditionsLinkHref.'">'.Label::getLabel('LBL_TERMS_AND_CONDITION').'</a>';
$terms_caption = '<span>'.$termLink.'</span>';
$frm->getField('terms')->changeCaption(Label::getLabel('LBL_Accept_Teacher_Approval'));
$frm->getField('terms')->addWrapperAttribute('class', 'terms_wrap');
$frm->getField('terms')->htmlAfterField = $terms_caption;

//$frm->setFormTagAttribute( 'onsubmit', 'setUpTeacherApproval(this); return false;' );
$frm->setFormTagAttribute( 'action', CommonHelper::generateUrl( 'TeacherRequest', 'setUpTeacherApproval' ) );
?>

<section class="section section--gray section--page">
	<div class="container container--fixed">
		<div class="row justify-content-center">
			<div class="col-sm-10 col-lg-9 col-xl-8">
				<div class="box -skin">
					<div class="box__head -align-center">
						<h1><?php echo Label::getLabel('LBL_Teacher_Application'); ?></h1>
						<p><?php
						$str = Label::getLabel('LBL_Thank_you_for_applying_to_teach_on_{website-name}');
						$str = str_replace( "{website-name}", "<strong>".$websiteName."</strong>", $str );
						echo $str; ?> </p>
					</div>
					<div class="box__body -padding-40"><?php echo $frm->getFormHtml(); ?></div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
$("document").ready(function(){
	$("select[name='utrvalue_user_language_speak_proficiency[]']").closest(".row").addClass("spoken_language_row");
	$("select[name='utrvalue_user_language_speak_proficiency[]']").closest(".row").addClass("row--addons");

	$("select[name='utrvalue_user_teach_slanguage_id[]']").closest(".row").addClass("teach_language_row");
	$("select[name='utrvalue_user_teach_slanguage_id[]']").closest(".row").addClass("row--addons");
	$("#resume_fields_heading").closest('.row').addClass('row--head');

	addNewLanguageRow = function(){

		var rowStr = '<div class="row spoken_language_row row--addons">';

		rowStr += '<div class="col-sm-5"><div class="field-set">';
		rowStr += '<div class="caption-wraper"><label class="field_label"><?php echo Label::getLabel('LBL_Languages_you_speak'); ?><span class="spn_must_field">*</span></label></div>';
		rowStr += '<div class="field-wraper"><div class="field_cover"><?php echo $frm->getFieldHtml( 'utrvalue_user_language_speak[]' ); ?></div></div>';

		rowStr += '</div></div>';

		rowStr += '<div class="col-sm-5"><div class="field-set">';
		rowStr += '<div class="caption-wraper"><label class="field_label"><?php echo Label::getLabel('LBL_Languages_Proficiency'); ?><span class="spn_must_field">*</span></label></div>';

		rowStr += '<div class="field-wraper"><div class="field_cover"><?php echo $frm->getFieldHtml( 'utrvalue_user_language_speak_proficiency[]' ); ?></div></div>';

		rowStr += '</div></div>';

		rowStr += '<div class="col-sm-2"><label class="field_label -display-block"></label>';

		rowStr += '<a title="<?php echo Label::getLabel('LBL_Delete'); ?>" href="javascript:void(0)" class="inline-action inline-action--minus remove-row-js" onClick="removeLanguageRow(this);">-</a></div>';

		rowStr += '</div>';

		$(".spoken_language_row:last").after(rowStr);
	};

	addNewTeachLanguageRow = function(){

		var rowStr = '<div class="row teach_language_row row--addons">';

		rowStr += '<div class="col-sm-10"><div class="field-set">';
		rowStr += '<div class="caption-wraper"><label class="field_label"><?php echo Label::getLabel('LBL_What_Language_Do_You_Want_To_Teach'); ?><span class="spn_must_field">*</span></label></div>';
		rowStr += '<div class="field-wraper"><div class="field_cover"><?php echo $frm->getFieldHtml( 'utrvalue_user_teach_slanguage_id[]' ); ?></div></div>';

		rowStr += '</div></div>';

		rowStr += '<div class="col-sm-2"><label class="field_label -display-block"></label>';

		rowStr += '<a title="<?php echo Label::getLabel('LBL_Delete'); ?>" href="javascript:void(0)" class="inline-action inline-action--minus remove-row-js" onClick="removeTeachLanguageRow(this);">-</a></div>';

		rowStr += '</div>';

		$(".teach_language_row:last").after(rowStr);
	};

	removeLanguageRow = function( e ){
		$(e).closest(".spoken_language_row").remove();
	};
	removeTeachLanguageRow = function( e ){
		$(e).closest(".teach_language_row").remove();
	};
});
</script>
