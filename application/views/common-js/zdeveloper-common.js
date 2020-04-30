function getCountryStates( countryId, stateId, dv ){
	fcom.ajax(fcom.makeUrl('GuestUser','getStates',[countryId,stateId]),'',function(res){
		$(dv).empty();
		$(dv).append(res);
	});
};
function isUserLogged(){
	var isUserLogged = 0;
	$.ajax({
		url: fcom.makeUrl('GuestUser','checkAjaxUserLoggedIn'),
		async: false,
		dataType: 'json',
	}).done(function(ans) {
		isUserLogged = parseInt( ans.isUserLogged );
	});
	return isUserLogged;
}
getStatisticalData = function(type){

	if(type == 1){
		duration = $('#earningMonth').val();
	}
	if(type == 2){
		duration = $('#lessonsMonth').val();
	}
	fcom.ajax(fcom.makeUrl('TeacherReports','getStatisticalData'), 'duration='+duration+'&type='+type , function(res){
		if(type == 1){
			$('#earningContent').html(res);
		}
		if(type == 2){
			$('#lessonsSold').html(res);
		}
	});
}
$(document).ready(function(){
	setUpJsTabs();

	setUpGoToTop();

	setUpStickyHeader();

	toggleNavDropDownForDevices();

	toggleHeaderNavigationForDevices();

	/* toggleFooterLinksForDevices(); */

	toggleHeaderCurrencyLanguageForDevices();

	toggleFooterCurrencyLanguage();
});

(function($){

	var screenHeight = $(window).height() - 100;
    window.onresize = function (event) {
        var screenHeight = $(window).height() - 100;
    };

	$.extend(fcom, {
		getLoader: function(){
			return '<div class="-padding-20"><div class="loader -no-margin-bottom"></div></div>';
		},

		resetFaceboxHeight: function () {
            //$('html').css('overflow', 'hidden');
			$('html').addClass('show-facebox');
            facebocxHeight = screenHeight;
            $('#facebox .content').css('max-height', facebocxHeight - 50 + 'px');
            if ($('#facebox .content').height() + 100 >= screenHeight) {
                //$('#facebox .content').css('overflow-y', 'scroll');
                $('#facebox .content').css('display', 'block');
            } else {
                $('#facebox .content').css('max-height', '');
                $('#facebox .content').css('overflow', '');
            }
        },

		updateFaceboxContent: function (t, cls) {
            if (typeof cls == 'undefined' || cls == 'undefined') {
                cls = '';
            }
            $.facebox(t, cls);
            $.systemMessage.close();
            fcom.resetFaceboxHeight();
        },

		waitAndRedirect: function( redirectUrl ){
			setTimeout(function(){
				window.location.href = redirectUrl;
			}, 3000);
		},
	});


	$(document).bind('reveal.facebox', function () {
        fcom.resetFaceboxHeight();
    });
    $(window).on("orientationchange", function () {
        facebocxHeight = screenHeight;
        $('#facebox .content').css('max-height', facebocxHeight - 50 + 'px');
        if ($('#facebox .content').height() + 100 >= screenHeight) {
            //$('#facebox .content').css('overflow-y', 'scroll');
            $('#facebox .content').css('display', 'block');
        } else {
            $('#facebox .content').css('max-height', '');
            $('#facebox .content').css('overflow', '');
        }
    });
    $(document).bind('loading.facebox', function () {
        //$('#facebox .content').addClass('fbminwidth');
    });
    $(document).bind('beforeReveal.facebox', function () {
        //$('#facebox .content').addClass('scrollbar scrollbar-js fbminwidth');
    });
    $(document).bind('afterClose.facebox', function(){
		$('html').removeClass('show-facebox');
	});

	setUpJsTabs = function(){

		/* upon loading[ */
		$(".tabs-content-js").hide();
		$(".tabs-js li:first").addClass("is-active").show();
		$(".tabs-content-js:first").show();
		/* ] */

	},

	setUpGoToTop = function(){
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('.scroll-top-js').addClass("isvisible");
			} else {
			   $('.scroll-top-js').removeClass("isvisible");
			}
		});

		$(".scroll-top-js").click( function(){
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	},

	setUpStickyHeader = function(){
		if( $(window).width() > 767 ){
			 $(window).scroll(function(){
				body_height = $(".body").position();
				scroll_position = $(window).scrollTop();
				if( body_height.top < scroll_position ){
					$(".header").addClass("is-fixed");
				} else {
					$(".header").removeClass("is-fixed");
				}
			});


		}

	},

	toggleNavDropDownForDevices = function(){
		if( $(window).width() < 1200 ){
			$('.nav__dropdown-trigger-js').click(function(){
				if($(this).hasClass('is-active')){
					$(this).removeClass('is-active');
					$(this).siblings('.nav__dropdown-target-js').slideUp();return false;
				}
				$('.nav__dropdown-trigger-js').removeClass('is-active');
				$(this).addClass("is-active");
				$('.nav__dropdown-target-js').slideUp();
				$(this).siblings('.nav__dropdown-target-js').slideDown();
			});
		}
	},

	toggleHeaderNavigationForDevices = function(){
		$('.toggle--nav-js').click(function() {
			$(this).toggleClass("is-active");
			$('html').toggleClass("show-nav-js");
		});
	},

	jQuery(document).ready(function (e) {
		function t(t) {
			e(t).bind("click", function (t) {
				t.preventDefault();
				e(this).parent().fadeOut()
			})
		}

		$(".cc-cookie-accept-js").click(function () {
		fcom.ajax(fcom.makeUrl('Custom', 'updateUserCookies'), '', function (t) {
				$(".cookie-alert").hide('slow');
				$(".cookie-alert").remove();
		});
});


        //When page loads...
        $(".tabs-content-js").hide(); //Hide all content
        $(".tabs-js li:first").addClass("is-active").show(); //Activate first tab
        $(".tabs-content-js:first").show(); //Show first tab content

        //On Click Event
        $(".tabs-js li").click(function() {
            $(".tabs-js li").removeClass("is-active"); //Remove any "active" class
            $(this).addClass("is-active"); //Add "active" class to selected tab
            $(".tabs-content-js").hide(); //Hide all tab content

            var activeTab = $(this).data("href"); //Find the href attribute value to identify the active tab + content

            $(activeTab).fadeIn(); //Fade in the active ID content
            return true;
        });

        e(".toggle__trigger-js").click(function () {
                var t = e(this).parents(".toggle-group").children(".toggle__target-js").is(":hidden");
                e(".toggle-group .toggle__target-js").hide();
                e(".toggle-group .toggle__trigger-js").removeClass("is-active");
                if (t) {
                    e(this).parents(".toggle-group").children(".toggle__target-js").toggle().parents(".toggle-group").children(".toggle__trigger-js").addClass("is-active")
                }

            });

        $(document.body).on('click', ".toggle__trigger-js", function(){
			var t = e(this).parents(".toggle-group").children(".toggle__target-js").is(":hidden");
			e(".toggle-group .toggle__target-js").hide();
			e(".toggle-group .toggle__trigger-js").removeClass("is-active");
			if (t) {
				e(this).parents(".toggle-group").children(".toggle__target-js").toggle().parents(".toggle-group").children(".toggle__trigger-js").addClass("is-active")
			}
		});
		e(document).bind("click", function (t) {
			var n = e(t.target);
			if (!n.parents().hasClass("toggle-group")) e(".toggle-group .toggle__target-js").hide();
		});
		e(document).bind("click", function (t) {
			var n = e(t.target);
			if (!n.parents().hasClass("toggle-group")) e(".toggle-group .toggle__trigger-js").removeClass("is-active");
		})

		$(".tab-swticher-small a").click(function () {
		$(".tab-swticher-small a").removeClass("is-active");
		$(this).addClass("is-active");

		});
	});

	toggleHeaderCurrencyLanguageForDevices = function(){
		$('.nav__item-settings-js').click(function() {
			$(this).toggleClass("is-active");
			$('html').toggleClass("show-setting-js");
		});
	},

	toggleFooterCurrencyLanguage = function(){
		$(".toggle-footer-lang-currency-js").click(function(){

			var clickedSectionClass = $(this).siblings( ".listing-div-js" ).attr("div-for");

			$(".toggle-footer-lang-currency-js").each(function(){
				if( $(this).siblings( ".listing-div-js" ).attr("div-for") != clickedSectionClass ){
					$(this).siblings(".listing-div-js").hide();
				}
			});

			$(this).siblings(".listing-div-js").slideToggle();
		});
	},

	setSiteDefaultLang = function( langId ){
		fcom.ajax(fcom.makeUrl('Home','setSiteDefaultLang',[langId]),'',function(res){
			document.location.reload();
		});
	},

	setSiteDefaultCurrency = function( currencyId ){
		fcom.ajax(fcom.makeUrl('Home','setSiteDefaultCurrency',[currencyId]),'',function(res){
			document.location.reload();
		});
	},

	signUpFormPopUp = function( signUpType ){
		var data = 'signUpType='+signUpType;
		fcom.updateWithAjax( fcom.makeUrl('GuestUser','signUpFormPopUp'), data,function(ans){
			$.mbsmessage.close();
			if( ans.redirectUrl ){
				window.location.href = ans.redirectUrl;
				return;
			}
			$.facebox(ans.html, '');
		});
	};

	setUpSignUp = function( frm ){
		if ( !$(frm).validate() ){ return; }
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'setUpSignUp'), fcom.frmData(frm), function(res) {
			if( res.redirectUrl ){
				window.location.href = res.redirectUrl;
				return;
			}
		});
	};

	logInFormPopUp = function(){
		$.facebox(function() {
			fcom.ajax( fcom.makeUrl('GuestUser', 'logInFormPopUp', []), '', function(res){
				$.facebox(res, '');
			});
		});
	};

	setUpLogin = function( frm ){
		if ( !$(frm).validate() ){ return; }
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'setUpLogin'), fcom.frmData(frm), function(res) {
			if( res.redirectUrl ){
				window.location.href = res.redirectUrl;
				return;
			}
		});
	};

	resendEmailVerificationLink = function(username){
		if( username == "undefined" || typeof username === "undefined"  ){
			username = '';
		}
		closeMessage();
		fcom.updateWithAjax( fcom.makeUrl('GuestUser','resendEmailVerificationLink',[username]),'',function(ans){
			displayMessage(ans.msg);
		});
	};

	displayMessage = function(msg){
		$.mbsmessage( msg, true, 'alert alert--success' );
	};

	closeMessage = function(){
		$(document).trigger('close.mbsmessage');
	};

	togglePassword = function( e ){
		var passType = $("input[name='user_password']").attr("type");
		if( passType == "text" ){
			$("input[name='user_password']").attr("type", "password");
			$(e).html( $(e).attr("data-show-caption") );
		} else {
			$("input[name='user_password']").attr("type", "text");
			$(e).html( $(e).attr("data-hide-caption") );
		}
	};

	toggleTeacherFavorite = function( teacher_id,el){
		if( isUserLogged() == 0 ){
			logInFormPopUp();
			return false;
		}
		var data = 'teacher_id='+teacher_id;
		$.mbsmessage.close();
		fcom.updateWithAjax(fcom.makeUrl('Learner', 'toggleTeacherFavorite'), data, function(ans) {
			location.reload();
			/*console.log(ans); return false;
			if( ans.status ){
				if( ans.action == 'A' ){
					$(el).addClass("is-active");
					$( "[data-id="+teacher_id+"]").addClass("is-active");
					$("[data-id="+teacher_id+"] span").attr('title', langLbl.RemoveProductFromFavourite);
				} else if( ans.action == 'R' ){
					$( "[data-id="+teacher_id+"]").removeClass("is-active");
					$("[data-id="+teacher_id+"] span").attr('title', langLbl.AddProductToFavourite);
				}
			}*/
		});

	}

	generateThread = function( id ){
		//var data = 'threadId='+id;
		if( isUserLogged() == 0 ){
			logInFormPopUp();
			return false;
		}

		fcom.updateWithAjax( fcom.makeUrl('Messages','initiate/'+id), '',function(ans){
			$.mbsmessage.close();
			if( ans.redirectUrl ){
                if(ans.threadId){
                    sessionStorage.setItem('threadId',ans.threadId);
                }
				window.location.href = ans.redirectUrl;
				return;
			}
			$.facebox(ans.html, '');
		});
	};

	sendMessage = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		var dv = "#frm_fat_id_frmSendMessage";
		$(dv).html(fcom.getLoader());
		fcom.updateWithAjax(fcom.makeUrl('Messages', 'sendMessage'), data, function(t) {
		window.location.href = fcom.makeUrl('Messages');
		});
	};

	// function resendVerificationLink(username){
		// /* if(user==''){
			// return false;
		// } */
		// //$(document).trigger('closeMsg.systemMessage');
		// console.log(username + " is heare");
		// $.systemMessage.close();
		// /* $.mbsmessage(langLbl.processing,false,'alert--process alert');
		// fcom.updateWithAjax( fcom.makeUrl('GuestUser','resendVerification',[username]),'',function(ans){
			// $.mbsmessage(ans.msg, false, 'alert alert--success');
		// }); */
	// }

	closeNavigation = function(){
		$('.subheader .nav__dropdown a').removeClass('is-active');
		$('.subheader .nav__dropdown-target').fadeOut();
	}


})(jQuery);
