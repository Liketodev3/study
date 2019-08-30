<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>

<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">
					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Manage_Teaching_Language',$adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div>

		<section class="section searchform_filter">
			<div class="sectionhead">
				<h4> <?php echo Label::getLabel('LBL_Search...',$adminLangId); ?></h4>
			</div>
			<div class="sectionbody space togglewrap" style="display:none;">
				<?php 
				$frmSearch->setFormTagAttribute ( 'onsubmit', 'searchTeachingLanguage(this); return(false);');
				$frmSearch->setFormTagAttribute ( 'class', 'web_form' );
				$frmSearch->developerTags['colClassPrefix'] = 'col-md-';					
				$frmSearch->developerTags['fld_default_col'] = 6;
				$btn_clear = $frmSearch->getField('btn_clear');
				$btn_clear->addFieldTagAttribute('onclick', 'clearSearch()');					
				echo  $frmSearch->getFormHtml();
				?>    
			</div>
		</section> 
	
		
		<section class="section">
		<div class="sectionhead">
			<h4><?php echo Label::getLabel('LBL_Teaching_Language_Listing',$adminLangId); ?></h4>
			<?php if($canEdit){

			$ul = new HtmlElement( "ul",array("class"=>"actions actions--centered") );
			            $li = $ul->appendElement("li",array('class'=>'droplink'));
			            $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
			            $innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
			            $innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
			 			$innerLiAddCat=$innerUl->appendElement('li');            
			            $innerLiAddCat->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Label::getLabel('LBL_Add_Teaching_Language',$adminLangId),"onclick"=>"addTeachingLanguageForm(0,0)"),Label::getLabel('LBL_Add_Teaching_Language',$adminLangId), true);
						echo $ul->getHtml();

			/*<a href="javascript:void(0)" class="themebtn btn-default btn-sm" onClick="testimonialForm(0)";><?php echo Label::getLabel('LBL_Add_Testimonial',$adminLangId); ?></a>*/			
			 } ?>
		</div>
		<div class="sectionbody">
			<div class="tablewrap" >
				<div id="listing"> <?php echo Label::getLabel('LBL_Processing...',$adminLangId); ?></div>
			</div> 
		</div>
		
		</section>
			
</div>
</div>
</div>
</div>