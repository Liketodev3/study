<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">

					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Manage_Countries',$adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div> 
<!--<div class="row">
	<div class="col-sm-12">--> 
		<h1><?php //echo Label::getLabel('LBL_Manage_Countries',$adminLangId); ?> </h1>	
		<section class="section searchform_filter">
			<div class="sectionhead">
				<h4> <?php echo Label::getLabel('LBL_Search...',$adminLangId); ?></h4>
			</div>
			<div class="sectionbody space togglewrap" style="display:none;">
				<?php 
				$search->setFormTagAttribute ( 'onsubmit', 'searchCountry(this); return(false);');
				$search->setFormTagAttribute ( 'id', 'frmSearch' );
				$search->setFormTagAttribute ( 'class', 'web_form' );
				$search->developerTags['colClassPrefix'] = 'col-md-';					
				$search->developerTags['fld_default_col'] = 6;					

				$search->getField('keyword')->addFieldtagAttribute('class','search-input');
				$search->getField('btn_clear')->addFieldtagAttribute('onclick','clearSearch();');

				echo  $search->getFormHtml();
				?>    
			</div>
		</section> 		
		<section class="section">
			<div class="sectionhead">
				<h4><?php echo Label::getLabel('LBL_Country_Listing',$adminLangId); ?></h4>
				<?php 
					$ul = new HtmlElement( "ul",array("class"=>"actions actions--centered") );
	           	 	$li = $ul->appendElement("li",array('class'=>'droplink'));
					$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
					$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
					$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
				


					if(FatApp::getConfig('CONF_ENABLE_IMPORT_EXPORT',FatUtility::VAR_INT,0)){
							$innerLiExport=$innerUl->appendElement('li');
							$innerLiExport->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Label::getLabel('LBL_Export',$adminLangId),"onclick"=>"addExportForm(".Importexport::TYPE_COUNTRY.")"),Label::getLabel('LBL_Export',$adminLangId), true);
				       

						
					/*<a href="javascript:void(0)" class="themebtn btn-default btn-sm" onClick="exportForm(<?php echo Importexport::TYPE_COUNTRY;?>)";><?php echo Label::getLabel('LBL_Export',$adminLangId); ?></a>*/
					}
					 if(FatApp::getConfig('CONF_ENABLE_IMPORT_EXPORT',FatUtility::VAR_INT,0) && $canEdit){


							$innerLiImport=$innerUl->appendElement('li');
							$innerLiImport->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Label::getLabel('LBL_Import',$adminLangId),"onclick"=>"addImportForm(". Importexport::TYPE_COUNTRY.")"),Label::getLabel('LBL_Import',$adminLangId), true);

						/*<a href="javascript:void(0)" class="themebtn btn-default btn-sm" onClick="import_request_variables(types)Form(<?php echo Importexport::TYPE_COUNTRY;?>)";><?php echo Label::getLabel('LBL_Import',$adminLangId); ?></a>*/
						}
					if($canEdit){
							$innerLiAddCountry=$innerUl->appendElement('li');            
							$innerLiAddCountry->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Label::getLabel('LBL_Add_Country',$adminLangId),"onclick"=>"addCountryForm(0)"),Label::getLabel('LBL_Add_Country',$adminLangId), true);

					 /*
						<a href="javascript:void(0)" class="themebtn btn-default btn-sm" onClick="countryForm(0)";><?php echo Label::getLabel('LBL_Add_Country',$adminLangId); ?></a>		*/	
						} 
						echo $ul->getHtml();
						?>
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