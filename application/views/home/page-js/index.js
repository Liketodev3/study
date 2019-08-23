$("document").ready(function(){
	$('.caraousel--single-js').slick({
		autoplay: true,
		arrows:false,
		dots:true,
		fade:true,
		rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
		responsive: [{
			breakpoint: 767,
			settings: {
				arrows:false,
				dots:true 
			}
		}]
	});
	$("input[name='language']").autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Teachers', 'teachLanguagesAutoCompleteJson'),
				data: {keyword: request.term, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'], 
							value: item['id'], 
							name: item['name']	
						};
					}));
				},
			});
		},
		'select': function( event, ui) { 
			event.preventDefault();
			$('input[name=\'language\']').val( ui.item.label );
			window.location.href = window.location.href + "teachers/index/" + ui.item.value;
		}
	});	

//Common Carousel
var _carousel = $('.js-carousel');
_carousel.each(function () {

    var _this = $(this),
        _slidesToShow = (_this.data("slides")).toString().split(',');

    //slick common carousel init
    _this.slick({
        slidesToShow: parseInt(_slidesToShow.length > 0 ? _slidesToShow[0] : "3"),
        slidesToScroll: 1,
        arrows: _this.data("arrows"),
        dots: _this.data("dots"),
        infinite: true,
        autoplay: true,
        pauseOnHover: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 1 ? _slidesToShow[1] : "2"))
                }
                           },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 2 ? _slidesToShow[2] : "1"))
                }
                           },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 3 ? _slidesToShow[3] : "1"))
                }
                           }
                         ]
    });

});
//End of Common Carousel    
    
$('.vert-carousel').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    arrow: true,
    vertical: true
});    
    
$('.quote-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    infinite: false,
    fade: false,
    asNavFor: '.quote-thumbs',
    responsive: [
        {

            breakpoint: 992,
            settings: {
                arrows: false
            }
        }
        ]
});
$('.quote-thumbs').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.quote-slider',
    dots: false,
    centerMode: true,
    focusOnSelect: true,
    responsive: [
        {

            breakpoint: 1025,
            settings: {
                slidesToShow: 4,
            }
        },

        {

            breakpoint: 768,
            settings: {
                slidesToShow: 2,
            }
        }
        ]

});    
    
});