<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>

<section class="section">
	<div class="sectionhead">
		<h4><?php echo Label::getLabel('LBL_Add_Transactions',$adminLangId).": ".$reporterName; ?></h4>
	</div>
	<div class="sectionbody space">      
	  <div class="tabs_nav_container responsive flat">
		<ul class="tabs_nav">
			<li><a href="javascript:void(0)" onclick="transactions(<?php echo $lessonId ?>,<?php echo $issueId ?>);"><?php echo Label::getLabel('LBL_Transactions',$adminLangId); ?></a></li>
			<li><a class="active" href="javascript:void(0)" onclick="addLessonTransaction(<?php echo $lessonId ?>,<?php echo $issueId ?>);"><?php echo Label::getLabel('LBL_Add_New',$adminLangId); ?></a></li>						
		</ul>
		<div class="tabs_panel_wrap">
			<div class="tabs_panel">
				<?php
					$frm->developerTags['colClassPrefix'] = 'col-md-';
					$frm->developerTags['fld_default_col'] = 12;
					$frm->setFormTagAttribute('id', 'addressFrm');
					$frm->setFormTagAttribute('class','web_form form_horizontal');
					$frm->setFormTagAttribute('onsubmit', 'setupLessonTransaction(this); return(false);');
					echo $frm->getFormHtml();
				?>
			</div>
		</div>						
	</div>
	</div>						
</section>