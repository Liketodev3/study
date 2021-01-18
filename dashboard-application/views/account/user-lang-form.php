<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$langFrm->setFormTagAttribute('id', 'profileLangInfoFrm');
$langFrm->setFormTagAttribute('class','form');
$langFrm->setFormTagAttribute('onsubmit', 'setUpProfileLangInfo(this); return(false);');

$langFrm->developerTags['colClassPrefix'] = 'col-md-';
$langFrm->developerTags['fld_default_col'] = 12;
?>
     <div class="tabs-small tabs-offset tabs-scroll-js">
                                    <ul>
                                        <li><a href="javascript:void(0)" onclick="profileInfoForm()" ><?php echo Label::getLabel('LBL_General'); ?></a></li>
                                        			<?php foreach( $languages as $langId => $language ){ ?>
                                                        <li class="<?php echo ($langId == $lang_id)?'is-active':'' ?>"><a href="javascript:void(0)" onclick="getLangProfileInfoForm(<?php echo $langId; ?>)" ><?php echo $language['language_name']; ?></a></li>
                                                    <?php } ?>
                                    </ul>
                                </div>
	 <?php echo $langFrm->getFormHtml();?>