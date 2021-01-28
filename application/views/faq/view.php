<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<section class="section section--grey section--page">
 <div class="container container--narrow">
	
	 <div class="section__head">
		<div class="d-flex align-items-center justify-content-between">
			<div><h2><?php echo strtoupper(Label::getLabel('LBL_FAQ'));?></h2></div>
			<div><a class="btn btn--primary btn--small" href="<?php echo CommonHelper::generateUrl('Faq'); ?>"><?php echo Label::getLabel('LBL_Go_Back');?></a></div>
		</div>
	</div>
	
		 <div class="section__body">
			<div class="row justify-content-between">
			   
				<div class="col-xl-8 col-lg-8 col-md-12">
					<div class="box box--faqs">
					   <div class="-padding-30">
						   <div class="box__top">
								<p class="-no-margin-bottom"><?php echo $type; ?></p>
								<h4><?php echo $data['faq_title']; ?></h4>
							</div>
						</div>

						<div class="box__body -padding-30">
							<div class="faq_desc container--cms">
								<?php echo CommonHelper::renderHtml($data['faq_description']); ?>
							</div>
						</div>
						
						<!--div class="box__footer -padding-30">
							<div class="row align-items-center">
								<div class="col-xl-6 col-lg-8 col-md-8">
									<span class="-display-inline d-sm-block"><?php echo Label::getLabel('LBL_Was_this_article_helpful?'); ?></span> &nbsp;&nbsp;
									<a href="#" class="faq__action yes -display-inline"><img src="images/yes.svg" alt=""> <?php echo Label::getLabel('LBL_Yes');?></a>
									<a href="#" class="faq__action no -display-inline"><img src="images/no.svg" alt=""> <?php echo Label::getLabel('LBL_No');?></a>
								</div>
								<div class="col-xl-6 col-lg-4 col-md-4">
									<span class="-display-inline"><?php echo Label::getLabel('LBL_Have_more_questions?');?></span> &nbsp;&nbsp;
									<a href="#" class="btn btn--secondary -display-inline" style="vertical-align: middle;"><?php echo Label::getLabel('LBL_Submit_a_Request');?></a>
								</div>
							</div>
						</div>
						<span class="-gap"></span-->
<div class="box__footer -padding-30">
                                    <div class="row align-items-center">
                                        <div class="col-xl-5 col-lg-12 col-md-12 -hide-mobile">
                                         
                                        </div>
										
										<div class="col-xl-7 col-lg-12 col-md-12">
                                           <span class="-display-inline"><?php echo Label::getLabel('LBL_Have_more_questions?');?></span>  &nbsp;&nbsp;
											<a href="<?php echo CommonHelper::generateUrl('Contact'); ?>" class="btn btn--secondary -display-inline " style="vertical-align: middle;"><?php echo Label::getLabel('LBL_Submit_a_Request');?></a>
                                        </div>
                                    </div>
                                </div>					   
					</div>
					<span class="-gap"></span>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-12">
				   <div class="box box--faqs">
					   <div class="-padding-30">
						   <div class="box__top">
								<div class="d-xl-flex d-lg-flex d-md-flex justify-content-between align-items-center"><h4><?php echo Label::getLabel('LBL_Other_Articles');?></h4></div>
							</div>
						</div>

						<div class="box__body">
							<nav class="vertical-links">
								<ul>
									<?php  foreach($dataOther as $val){ ?>
									<li><a href="<?php echo CommonHelper::generateUrl('Faq','View',array($val['faq_id'])); ?>"><?php echo $val['faq_title']; ?></a></li>
									<?php } ?>
									
								</ul>
							</nav>
							<span class="-gap"></span>
						</div>
					</div>
				</div>
			</div>
		 </div>
	</div>
</section>
          
