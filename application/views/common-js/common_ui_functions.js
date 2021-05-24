$(document).ready(function() {

    /* SIDE BAR SCROLL DYNAMIC HEIGHT */ 
    $('.sidebar__body').css('height', 'calc(100% - ' +$('.sidebar__head').innerHeight()+'px');

    $(window).resize(function(){
        $('.sidebar__body').css('height', 'calc(100% - ' +$('.sidebar__head').innerHeight()+'px');
    });

    /* COMMON TOGGLES */ 
    var _body = $('html');
    var _toggle = $('.trigger-js');
    _toggle.each(function(){
    var _this = $(this),
        _target = $(_this.attr('href'));

        _this.on('click', function(e){
            e.preventDefault();
            _target.toggleClass('is-visible');
            _this.toggleClass('is-active');
            _body.toggleClass('is-toggle');
        });
    });


    /* FOR FULL SCREEN TOGGLE */
    var _body = $('html');
    var _toggle = $('.fullview-js');
    _toggle.each(function(){
    var _this = $(this),
        _target = $(_this.attr('href'));

        _this.on('click', function(e){
            e.preventDefault();
            _target.toggleClass('is-visible');
            _this.toggleClass('is-active');
            _body.toggleClass('is-fullview');
        });
    });
    
    /* FOR FOOTER */
    if( $(window).width() < 767 ){
        /* FOR FOOTER TOGGLES */
        $('.toggle-trigger-js').click(function(){
        if($(this).hasClass('is-active')){
            $(this).removeClass('is-active');
            $(this).siblings('.toggle-target-js').slideUp();return false;
        }
        $('.toggle-trigger-js').removeClass('is-active');
        $(this).addClass("is-active");
            $('.toggle-target-js').slideUp();
            $(this).siblings('.toggle-target-js').slideDown();
        });
    }

    /* FOR STICKY HEADER */    
    // Hide Header on on scroll down
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('.header').outerHeight();

    $(window).scroll(function(event){
        didScroll = true;
    });

    setInterval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();
        
        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;
        
        // If they scrolled down and are past the navbar, add class .nav-up.
        // This is necessary so you never see what is "behind" the navbar.
        if (st > lastScrollTop && st > navbarHeight){
            // Scroll Down
            $('.header').removeClass('nav-down').addClass('nav-up');
        } else {
            // Scroll Up
            if(st + $(window).height() < $(document).height()) {
                $('.header').removeClass('nav-up').addClass('nav-down');
            }
        }
        
        lastScrollTop = st;
    }

    $(".settings__trigger-js").click(function () {
        var t = $(this).parents(".toggle-group").children(".settings__target-js").is(":hidden");
        $(".toggle-group .settings__target-js").hide();
        $(".toggle-group .settings__trigger-js").removeClass("is--active");
        if (t) {
        $(this).parents(".toggle-group").children(".settings__target-js").toggle().parents(".toggle-group").children(".settings__trigger-js").addClass("is--active")
        }
    });
    
    $(".toggle--nav-js").click(function () {
      $(this).toggleClass("is-active");
      $('html').toggleClass("show-nav-js");
      $('html').removeClass("show-dashboard-js");
    });


    $('.slideshow-js').slick({
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        arrows: false
      });
      

      $('.slider-onethird-js').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        adaptiveHeight: true,
        dots: false,
        prevArrow: '<button class="slick-prev cursor-hide" aria-label="Previous" type="button">Previous</button>',
        nextArrow: '<button class="slick-next cursor-hide" aria-label="Next" type="button">Next</button>',
        responsive: [
          {
             breakpoint: 1199,
             settings: {
               slidesToShow: 2,
             }
           },
           {
             breakpoint: 1023,
             settings: {
               slidesToShow: 2,
             }
           },
           {
             breakpoint: 767,
             settings: {
               slidesToShow:2,         
             }
           },
        
           {
             breakpoint: 576,
             settings: {
              slidesToShow:1,
             }
           } 
           
       ]
      });

$('.step-slider-js').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  dots: true,
  asNavFor: '.slider-tabs--js'
});

$('.slider-tabs--js').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.step-slider-js',
    dots: true,
    centerMode: true,
    focusOnSelect: true
  });

  $('.slider-quote-js').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay:false,
    adaptiveHeight: true,
    dots: false,
    prevArrow: '<button class="slick-prev cursor-hide" aria-label="Previous" type="button">Previous</button>',
    nextArrow: '<button class="slick-next cursor-hide" aria-label="Next" type="button">Next</button>',
    responsive: [
      {
      breakpoint:1023,
        settings: {
          fade: false,
          infinite: true,
          arrows: true,
            centerMode:true,  
            centerPadding: '15%',
            dots: false,
        }
      } ,
      {
        breakpoint:767,
          settings: {
            fade: false,
            infinite: true,
            dots: false,
            arrows: true,
              centerMode:true,  
              centerPadding: '15%',
   
          }
        } ,
        {
          breakpoint: 576,
          settings: {
            fade: false,
            infinite: true,
            dots: false,
            arrows: true,
              centerMode:true,  
              centerPadding: '5%',
   
          }
        } 
    ]
  });
     
});