(function() {
	var currentPage = 1;
	var div =  "#lessonListing";
	viewDetail = function(lessonId){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('PurchasedLessons', 'viewDetail', [lessonId]), '', function(t) {
			$.facebox(t,'faceboxWidth');
			});
		});
	};

	updateScheduleStatus = function(id,value){

		if(!confirm("Do you really want to update status?")){return;}
		if(id === null){
			$.mbsmessage('Invalid Request!');
			return false;
		}
		fcom.ajax(fcom.makeUrl('PurchasedLessons','updateStatusSetup'),{"slesson_id":id, "slesson_status" : value},function(json){
			res = $.parseJSON(json);
			if(res.status == "1"){
				  $.mbsmessage( res.msg,true, 'alert alert--success');
			}else{
				  $.mbsmessage( res.msg,true, 'alert alert--danger');
			}
		});
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmPurchaseLessonSearchPaging;
		$(frm.page).val(page);
		searchPuchasedLessons(frm);
	};


	searchPuchasedLessons  = function (form) {
			// currentPage = (page && !page) ? currentPage : page;

			data =  (form) ? fcom.frmData(form) : '';

			$(div).html(fcom.getLoader());

			fcom.ajax(fcom.makeUrl('PurchasedLessons','purchasedLessonsSearch'),data,function(res){
				$(div).html(res);
			});

	};

	 clearPuchasedLessonSearch = function(){
		document.purchasedLessonsSearchForm.reset();
		document.purchasedLessonsSearchForm.slesson_teacher_id.value = '';
		document.purchasedLessonsSearchForm.slesson_learner_id.value = '';
		searchPuchasedLessons(document.purchasedLessonsSearchForm );
	};

})();

$(document).ready(function(){
	searchPuchasedLessons(document.purchasedLessonsSearchForm);

	$('input[name=\'teacher\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'] +'(' + item['username'] + ')', value: item['id'], name: item['username']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='slesson_teacher_id']").val( item['value'] );
			$("input[name='teacher']").val( item['name'] );
		}
	});


	$('input[name=\'learner\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'] +'(' + item['username'] + ')', value: item['id'], name: item['username']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='slesson_learner_id']").val( item['value'] );
			$("input[name='learner']").val( item['name'] );
		}
	});


		$('input[name=\'learner\']').keyup(function(){
			$('input[name=\'order_user_id\']').val('');
		});

		$('input[name=\'teacher\']').keyup(function(){
			$('input[name=\'op_teacher_id\']').val('');
		});

});
