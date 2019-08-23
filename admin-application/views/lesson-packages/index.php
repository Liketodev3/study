<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>

<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">
					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Manage_Lesson_Packages',$adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div>

				<section class="section">
					<div class="sectionhead">
						<h4><?php echo Label::getLabel('LBL_Lesson_Packages_Listing',$adminLangId); ?></h4>
						<?php 
						if( $canEdit ){
							$ul = new HtmlElement( "ul",array("class"=>"actions actions--centered") );
							$li = $ul->appendElement("li",array('class'=>'droplink'));
							$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Add_Lesson_Package',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
							$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
							$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
							$innerLiAddCat=$innerUl->appendElement('li');            
							$innerLiAddCat->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Label::getLabel('LBL_Add_Lesson_Package',$adminLangId),"onclick"=>"addLessonPackageForm(0,0)"),Label::getLabel('LBL_Add_Lesson_Package',$adminLangId), true);
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