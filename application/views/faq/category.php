<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<section class="section section--grey section--page">
 <div class="container container--narrow">
	
		
	 
	 <div class="section__head">
		<div class="d-flex align-items-center justify-content-between">
			<div><h2><?php echo strtoupper(Label::getLabel('LBL_FAQ_Category'));?></h2></div>
			<div><a class="btn btn--primary btn--small" href="<?php echo CommonHelper::generateUrl('Faq'); ?>"><?php echo Label::getLabel('LBL_Go_Back');?></a></div>
		</div>
	</div>
	 
	 
	 <div class="section__body">
		<div class="row justify-content-between">
		   
			<div class="col-xl-4 col-lg-4 col-md-4 -hide-responsive">
				<div class="left__fixed">
					<div id="left__fixed">
						<nav class="nav-vertical">
							<ul>
								
								<li><a href="#category_1" class="scroll"><?php echo Faq::getFaqCategoryArr()[$categoryId]; ?></a></li>
								
							</ul>
						</nav>
					</div>
				</div>
			</div>
			
			<div class="col-xl-8 col-lg-12 col-md-12">
			   
				<div id="category_1" class="box box--faqs">
				   <div class="-padding-30">
					   <div class="box__top">
							<div class="d-xl-flex d-lg-flex d-md-flex justify-content-between align-items-center">
								<h4><?php echo Faq::getFaqCategoryArr()[$categoryId]; ?></h4>
							</div>
						</div>
					</div>

					<div class="box__body">
						<nav class="vertical-links">
							<ul>
								<?php  foreach($data as $val){ ?>
								<li><a href="<?php echo CommonHelper::generateUrl('Faq','View',array($val['faq_id'])); ?>"><?php echo $val['faq_title']; ?></a></li>
								<?php } ?>
							</ul>
						</nav>
						<span class="-gap"></span>
					</div>
				</div>
				<span class="-gap"></span><span class="-gap -hide-mobile"></span>
				

				
			</div>
		</div>
	 </div>
	 
 </div>
</section>

	<script>   
 
     /* for click scroll function */
    $(".scroll").click(function(event){
    event.preventDefault();
    var full_url = this.href;
    var parts = full_url.split("#");
    var trgt = parts[1];
    var target_offset = $("#"+trgt).offset();

    var target_top = target_offset.top-110;
    $('html, body').animate({scrollTop:target_top}, 800);
    });  

    $('.list--vertical-js li a').click(function(){
        $('.list--vertical-js li a').removeClass('is-active');
        $(this).addClass('is-active');
    });
    
     /* for sticky left panel */

  if($(window).width()>1200){
    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('.left__fixed').offset().top -100;
        var sticky_left = $('#left__fixed');
        if((window_top + sticky_left.height()) >= ($('.footer').offset().top - 100)){
            var to_reduce = ((window_top + sticky_left.height()) - ($('.footer').offset().top - 100));
            var set_stick_top = -100 - to_reduce;
            sticky_left.css('top', set_stick_top+'px');
        }else{
            sticky_left.css('top', '110px');
            if (window_top > div_top) {
                $('#left__fixed').addClass('stick');
            } else {
                $('#left__fixed').removeClass('stick');
            }
        }
    }

    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });
}  
    
    
    
</script>
