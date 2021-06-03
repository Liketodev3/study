<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$spokenLanguagesArr = explode(",", $spoken_language_names);
$totalSpokenLanguages = count($spokenLanguagesArr) - 1;
$spokenLanguagesProficiencyArr = explode(",", $spoken_languages_proficiency);

?>
<div class="txt-inline">
	<span class="txt-inline__tag -color-secondary">
		<span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="12" viewBox="0 0 18 12">
		<path d="M850.875,749.945a1.306,1.306,0,0,1-.75,0l-4.484-1.414L845.5,751a1.175,1.175,0,0,0,.641,1,5.4,5.4,0,0,0,1.836.73,11.923,11.923,0,0,0,5.046,0,5.413,5.413,0,0,0,1.836-.73,1.175,1.175,0,0,0,.641-1l-0.141-2.469Zm8.453-4.187-8.75-2.75a0.384,0.384,0,0,0-.156,0l-8.75,2.75a0.256,0.256,0,0,0,0,.484l2.6,0.813a4.841,4.841,0,0,0-.765,2.578,1,1,0,0,0-.055,1.7L843,754.719a0.255,0.255,0,0,0,.25.281h1.5a0.254,0.254,0,0,0,.25-0.281l-0.453-3.383A0.945,0.945,0,0,0,845,750.5a0.92,0.92,0,0,0-.492-0.852,4.992,4.992,0,0,1,.265-1.394,1.907,1.907,0,0,1,.555-0.871l5.094,1.609a0.384,0.384,0,0,0,.156,0l8.75-2.75a0.256,0.256,0,0,0,0-.484h0Z" transform="translate(-841.5 -743)"/>
		</svg>
		</span>
		<?php echo Label::getLabel('LBL_Speaks'); ?>
	</span> &nbsp;&nbsp;
	<?php

	foreach( $spokenLanguagesArr as $index => $spokenLangName ){
		?>
		<span class="txt-inline__tag"><?php echo $spokenLangName; ?><strong> (<?php echo $proficiencyArr[$spokenLanguagesProficiencyArr[$index]]; ?>)</strong></span><?php echo ($index < $totalSpokenLanguages) ? ',' : ''; ?>
		<?php
	}
	?>
</div>
