<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'teacherPreferencesFrm');
$frm->setFormTagAttribute('class','form');
$frm->setFormTagAttribute('onsubmit', 'setupTeacherLanguages(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-sm-';
$frm->developerTags['fld_default_col'] = 4;

//$teach_lang_id = $frm->getField('teach_lang_id[]');
//$teach_lang_id->developerTags['col'] = 10;

$add_more_div_a_tag = $frm->getField('add_more_div_a_tag');
$add_more_div_a_tag->developerTags['col'] = 10;
$add_more_div_a_tag->value = '<label class="field_label -display-block"></label><a class="inline-action addSpokenLang inline-action--add" onclick="addNewLanguageRow();" href="javascript:void(0);">'.Label::getLabel('LBL_ADD_LANGUAGE').'</a><br>';

$add_more_lang = $frm->getField('add_more_lang');
$add_more_lang->developerTags['col'] = 10;
$add_more_lang->value = '<label class="field_label -display-block"></label><a class="inline-action addTeachLang inline-action--add" onclick="addNewTeachLanguageRow();" href="javascript:void(0);">'.Label::getLabel('LBL_ADD_TEACH_LANGUAGE').'</a><br>';


$add_more_div = $frm->getField('add_more_div');
$add_more_div->value = '<div id="add_more_div"></div>';

$teachLangField = $frm->getField('teach_lang_id');
$teachLangFieldValue = $teachLangField->value;

$speakLangField = $frm->getField('utsl_slanguage_id[]');
$speakLangFieldValue = $speakLangField->value;

$proficiencyField = $frm->getField('utsl_proficiency[]');
$proficiencyFieldValue = $proficiencyField->value;
$proficiencyOptions= $proficiencyField->options;

// prx($proficiencyFieldValue);
// pr($proficiencyFieldValue);
/*$utsl_slanguage_id = $frm->getField('utsl_slanguage_id[]');
$utsl_slanguage_id->developerTags['col'] = 5;
$utsl_proficiency = $frm->getField('utsl_proficiency[]');
$utsl_proficiency->developerTags['col'] = 5;*/
?>
<div class="content-panel__head">
	<div class="d-flex align-items-center justify-content-between">
		<div><h5><?php echo Label::getLabel('LBL_Manage_Languages'); ?></h5></div>
		<div></div>
	</div>
</div>

<div class="content-panel__body">
	<div class="form">
		<div class="form__body">
			<div class="colum-layout">
			<div class="colum-layout__cell">
				<div class="colum-layout__head">
					<span class="bold-600"><?php echo $teachLangField->getCaption(); ?></span>
					<?php if($teachLangField->requirement->isRequired()){ ?>
						<span class="spn_must_field">*</span>
					<?php } ?>
				</div>
				<div class="colum-layout__body">
					<div class="colum-layout__scroll scrollbar scrollbar-js">
						<?php foreach ($teachLangField->options as $key => $value) { ?>
							<div class="selection">
								<label class="selection__trigger">
										<input name="<?php echo $teachLangField->getName(); ?>" class="selection__trigger-input"  type="checkbox" <?php echo (in_array($key, $teachLangFieldValue)) ? 'checked' : '';  ?>>
										<span class="selection__trigger-action">
											<span class="selection__trigger-label"><span class="flag-icon flag-icon--s">
												<?php
													$languageFlagImage = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','showLanguageFlagImage',array($key,'SMALL'), CONF_WEBROOT_FRONT_URL),CONF_IMG_CACHE_TIME, '.jpg');
                                                    echo '<img src="'.$languageFlagImage.'" alt="'.$value.'">';
												?>
												
											</span><?php echo $value; ?>
										</span>
										<span class="selection__trigger-icon"></span>
										</span>
								</label>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="colum-layout__cell">
					<div class="colum-layout__head">
						<span class="bold-600"><?php echo $speakLangField->getCaption(); ?></span>
						<?php if($speakLangField->requirement->isRequired()){ ?>
							<span class="spn_must_field">*</span>
						<?php } ?>
					</div>
					<div class="colum-layout__body">

						<div class="colum-layout__scroll scrollbar scrollbar-js">
						<?php foreach ($speakLangField->options as $key => $value) { ?>
							<div class="selection selection--select">
								<label class="selection__trigger ">
									<input type="checkbox" name="<?php echo $speakLangField->getName(); ?>" <?php echo in_array($key, $speakLangFieldValue) ? 'checked' : ''; ?> >
									<span class="selection__trigger-action">
										<span class="selection__trigger-label">
											<span class="flag-icon flag-icon--s">
												<?php
													$languageFlagImage = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','showSpokenLangFlagImage',array($key,'SMALL'), CONF_WEBROOT_FRONT_URL),CONF_IMG_CACHE_TIME, '.jpg');
													echo '<img src="'.$languageFlagImage.'" alt="'.$value.'">';
												?>
											</span> <?php echo $value; ?> 
											<?php if(in_array($key, $speakLangFieldValue)){ ?>
											<span class="badge color-secondary badge--round badge--small margin-0"><?php echo $proficiencyOptions[$proficiencyFieldValue[$key]]; ?></span>
											<?php } ?>
										</span>
										<span class="selection__trigger-icon"></span>
									</span>
								</label>
								<div class="selection__target">
									<?php  ?>
									<select class="select__dropdown">
										<option>Select Proficiency *</option>
										<option>I don't speak this language</option>
										<option>Beginner</option>
										<option>Upper Beginner</option>
										<option>Intermediate</option>
									</select>
								</div>
							</div>
						<?php } ?>
						</div>

					</div>
			</div>
			</div>
		</div>
		<div class="form__actions">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<input type="button" value="Back">
				</div>
				<div>
					<input type="submit" value="Save">
					<input type="button" value="Next">
				</div>
			</div>
		</div>
		</div>
	
</div>

<div class="section-head">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="page-heading"><?php echo Label::getLabel('LBL_Languages'); ?></h5>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo $frm->getFormHtml();?>
    </div>
</div>
<script >
$("document").ready(function(){

	/* FUNCTION FOR SCROLLBAR */
if($(window).width()>1199){
$('.scrollbar-js').enscroll({
    verticalTrackClass: 'scrollbar-track',
    verticalHandleClass: 'scrollbar-handle'
}); 
}

	$("select[name='utsl_slanguage_id[]']").closest(".row").addClass("row--addons spoken_language_row");
	$(".spokenLang").closest(".row").addClass("row--addons spoken_language_row");
	$(".addSpokenLang").closest(".row").addClass("spoken_language_row");
	$(".addTeachLang").closest(".row").addClass("spoken_language_row");
	$("select[name='teach_lang_id[]']").closest(".row").addClass("row--addons teach_language_row");
    var elem = $("select[name='teach_lang_id[]']").closest(".col-sm-5");
    //elem.removeClass("col-sm-5");    
    //elem.addClass("col-sm-10");    
    //$("select[name='teach_lang_id[]']").closest(".row").children(":first").addClass("col-sm-10");    
	
	addNewLanguageRow = function(){
		
		var html = $("select[name='utsl_slanguage_id[]']").closest(".col-sm-4").html();
		var html2 = $("select[name='utsl_proficiency[]']").closest(".col-sm-4").html();
		//var rowStr = '<div class="row row--addons spoken_language_row iterate_div">'+html+'<div class="col-sm-2"><label class="field_label -display-block"></label><a class="inline-action inline-action--minus" onclick="removeLanguageRow(this);" href="javascript:void(0);"><?php echo Label::getLabel('LBL_REMOVE'); ?></a></div></div>';
		var rowStr = '<div class="row row--addons spoken_language_row iterate_div"><div class="col-sm-4">'+html+'</div><div class="col-sm-4">'+html2+'</div><div class="col-sm-4"><label class="field_label -display-block"></label><a class="inline-action inline-action--minus" onclick="removeLanguageRow(this);" href="javascript:void(0);"><?php echo Label::getLabel('LBL_REMOVE'); ?></a></div></div>';
		$(".spoken_language_row:last").after(rowStr);
		$(".utsl_slanguage_id:last").find('option').prop("selected", false);
		$(".utsl_proficiency:last").find('option').prop("selected", false);
		$(".iterate_div div.col-sm-2:nth-last-child(2)").remove();
        $('html, body').animate({
            scrollTop: $("#profileInfoFrmBlock").height()
        }, 1000);        
	};

	addNewTeachLanguageRow = function(){
		
		var html = $("select[name='teach_lang_id[]']").closest(".col-sm-4").html();
		var rowStr = '<div class="row row--addons teach_language_row iterate_div"><div class="col-sm-4">'+html+'</div><div class="col-sm-2"><label class="field_label -display-block"></label><a class="inline-action inline-action--minus" onclick="removeTeachLanguageRow(this);" href="javascript:void(0);"><?php echo Label::getLabel('LBL_REMOVE'); ?></a></div></div>';
		$(".teach_language_row:last").after(rowStr);
		$(".teach_language_row:last").find('option').prop("selected", false);
		$(".iterate_div div.col-sm-2:nth-last-child(2)").remove();
        $('html, body').animate({
            scrollTop: $(".addSpokenLang").offset().top - 300
        }, 1000);        
	};
	
	removeLanguageRow = function( e ){
		$(e).closest(".spoken_language_row").remove();
	};
	removeTeachLanguageRow = function( e ){
		$(e).closest(".teach_language_row").remove();
	};    
});
</script>
