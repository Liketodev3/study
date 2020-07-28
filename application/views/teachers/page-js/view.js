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


function getSortedReviews(elm){
	if($(elm).length){
		var sortBy = $(elm).data('sort');
		if(sortBy){
			//document.frmReviewSearch.orderBy.value = $(elm).data('sort');
			$(elm).parent().siblings().removeClass('is-active');
			$(elm).parent().addClass('is-active');
		}
	}
	reviews(document.frmReviewSearch);
}

$("document").ready(function(){

        $('.toggle-dropdown__link-js').each(function(){
            $(this).click(function() {
             $(this).parent('.toggle-dropdown').toggleClass("is-active");
            });
        })

        $('html').click(function(){
            if($('.toggle-dropdown').hasClass('is-active')){
            $('.toggle-dropdown').removeClass('is-active');
            }
        });

        $('.toggle-dropdown').click(function(e){
            e.stopPropagation();
        });



	/* FUNCTION FOR LEFT COLLAPSEABLE LINKS */
	if( $(window).width() < 767 ){
		$('.box__head-trigger-js').click(function(){
			if($(this).hasClass('is-active')){
				$(this).removeClass('is-active');
				$(this).siblings('.box__body-target-js').slideUp();return false;
			}

			$('.box__head-trigger-js').removeClass('is-active');
			$(this).addClass("is-active");
			$('.box__body-target-js').slideUp();
			$(this).siblings('.box__body-target-js').slideDown();
		});
	}

    reviews(document.frmReviewSearch);

});

function viewCalendar( teacherId, action, languageId){
	$.systemMessage(langLbl.requestProcessing,'alert alert--process');
	if( action == 'free_trial' ) {
		if( isUserLogged() == 0 ){
			$.systemMessage.close();
			logInFormPopUp();
			return false;
		}
	}

	fcom.ajax(fcom.makeUrl('Teachers', 'viewCalendar',[teacherId, languageId]), 'action='+action, function(t) {
			$.systemMessage.close();
		$.facebox( t,'facebox-medium');
	});
}

function searchQualifications( user_id ){
	var dv = $('#qualificationsList');
	$(dv).html(fcom.getLoader());

	var data = 'user_id='+user_id;
	fcom.ajax(fcom.makeUrl('Teachers','qualificationList'),data,function(ans){
		$(dv).html( ans );
	});
}

	reviews = function(frm, append){


	var dv = '#itemRatings';
	var currPage = 1;
		if( typeof append == undefined || append == null ){
			append = 0;
		}

		var data = fcom.frmData(frm);
		if( append == 1 ){
			$(dv).prepend(fcom.getLoader());
		} else {
			$(dv).html(fcom.getLoader());
		}

		//
		fcom.updateWithAjax(fcom.makeUrl('Teachers','getTeacherReviews'), data+"teacherId=", function(ans){

			if( ans.status == 1 ){
				$.mbsmessage.close();
			}
			if( ans.totalRecords ){
				$('#reviews-pagination-strip--js').show();
			}
			if( append == 1 ){
				$(dv).find('.loader').remove();
				$(dv).find('form[name="frmSearchReviewsPaging"]').remove();
				$(dv).append(ans.html);

				$('#reviewEndIndex').html(( Number($('#reviewEndIndex').html()) + ans.recordsToDisplay));
			} else {
				$(dv).html(ans.html);
				$('#reviewStartIndex').html(ans.startRecord);
				$('#reviewEndIndex').html(ans.recordsToDisplay);
			}

			//$('#reviewsTotal').html( ans.totalRecords );

			$("#loadMoreReviewsBtnDiv").html( ans.loadMoreBtnHtml );

			$('a.yes').toggleClass("is-active");
		});
	};

	goToLoadMoreReviews = function(page){
		if(typeof page == undefined || page == null){
			page = 1;
		}
		currPage = page;
		var frm = document.frmSearchReviewsPaging;
		$(frm.page).val(page);
		reviews(frm,1);
	};
