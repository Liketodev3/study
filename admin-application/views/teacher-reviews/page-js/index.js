$(document).ready(function(){
	searchProductReviews(document.frmSearch);
	
	$('input[name=\'reviewed_by\']').autocomplete({
		'source': function(request, response) {		
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request, user_is_learner: 1, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'] ,	value: item['id']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='reviewed_by_id']").val( item['value'] );
			$("input[name='reviewed_by']").val( item['label'] );
		}
	});
	
	$('input[name=\'reviewed_to\']').autocomplete({
		'source': function(request, response) {		
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request, user_is_teacher: 1, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'] ,	value: item['id']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='teacher_id']").val( item['value'] );
			$("input[name='reviewed_to']").val( item['label'] );
		}
	});
});
(function() {
	var currentPage = 1;
	var runningAjaxReq = false;
	var dv = '#listing';

	goToSearchPage = function(page) {	
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmReviewSearchPaging;		
		$(frm.page).val(page);
		searchProductReviews(frm);
	}

	reloadList = function() {
		var frm = document.frmReviewSearchPaging;
		searchProductReviews(frm);
	}

	searchProductReviews = function(form){		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherReviews','search'),data,function(res){
			$(dv).html(res);
		});
	};
	
	viewReview = function(reviewId){			
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('TeacherReviews', 'view', [reviewId]), '', function(t) {
				$.facebox(t,'faceboxWidth');
			});
		});
	};
	
	updateStatus = function(frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherReviews', 'updateStatus'), data, function(t) {		
			$(document).trigger('close.facebox');		
			reloadList();
			//viewReview(t.spreviewId);
		});
	};
	
	clearSearch = function(){
		document.frmSearch.reset();
		$("input[name='seller_id']").val(0);
		$("input[name='reviewed_by_id']").val(0);
		searchProductReviews(document.frmSearch);
	};	

})();