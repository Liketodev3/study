$(document).ready(function(){
	
	searchTopLangReport(document.frmRescheduledReportSearch);

	$(document).on('click',function(){
		$('.autoSuggest').empty();
	});

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
			$("input[name='op_teacher_id']").val( item['value'] );
			$("input[name='teacher']").val( item['name'] );
		}
	});

		// $('#teacher,#learner').blur(function () {
		// 		$(this).next('.dropdown-menu').hide();
		// });
			$('.menutrigger').click(function () {
					$('.dropdown-menu').hide();
	});

	$('input[name=\'teacher\']').keyup(function(){
		$('input[name=\'op_teacher_id\']').val('');
	});

	$('input[name=\'learner\']').autocomplete({
		'source': function(request, response) {fcom.makeUrl
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
			$("input[name='op_learner_id']").val( item['value'] );
			$("input[name='learner']").val( item['name'] );
		}
	});

	$('input[name=\'learner\']').keyup(function(){
		$('input[name=\'op_learner_id\']').val('');
	});

});
(function() {

	var currentPage = 1;
	var runningAjaxReq = false;
	var dv = '#listing';
	var dvPop = '#listingReport';

	goToSearchPage = function(page) {	
		if(typeof page == undefined || page == null){
			page =1;
		}
		var frm = document.frmRescheduledReportSearchPaging;		
		$(frm.page).val(page);
		searchTopLangReport(frm);
	};
	
	goToNextPage = function(page) {	
		if(typeof page == undefined || page == null){
			page =1;
		}
		var frm = document.frmRescheduledReportSearchPaging;
		$(frm.page).val(page);
		nextReportPage(frm);
	};

	nextReportPage = function(form){
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dvPop).html(fcom.getLoader());
		console.log('dvPop', dvPop)
		fcom.ajax(fcom.makeUrl('RescheduleReport','viewReport'),data,function(res){
			$('.fbminwidth').html(res);
		});
	};

	redirectBack=function(redirecrt){$(document).on('click',function(){
		$('.autoSuggest').empty();
	});

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
			$("input[name='op_teacher_id']").val( item['value'] );
			$("input[name='teacher']").val( item['name'] );
		}
	});

		// $('#teacher,#learner').blur(function () {
		// 		$(this).next('.dropdown-menu').hide();
		// });
			$('.menutrigger').click(function () {
					$('.dropdown-menu').hide();
	});

	$('input[name=\'teacher\']').keyup(function(){
		$('input[name=\'op_teacher_id\']').val('');
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
				},goToNextPage
			});
		},
		'select': function(item) {
			$("input[name='order_user_id']").val( item['value'] );
			$("input[name='learner']").val( item['name'] );
		}
	});

	$('input[name=\'learner\']').keyup(function(){
		$('input[name=\'order_user_id\']').val('');
	});


	var url=	SITE_ROOT_URL +''+redirecrt;
	window.location=url;
	}
	reloadList = function() {
		var frm = document.frmRescheduledReportSearchPaging;
		searchTopLangReport(frm);
	};
	
	searchTopLangReport = function(form){
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('RescheduleReport','search'),data,function(res){
			$(dv).html(res);
		});
	};

	viewReport = function(userId, reportType){
		fcom.displayProcessing();
		$("input[name='report_type']").val( reportType );
		$("input[name='report_user_id']").val( userId );
		var frm = document.frmRescheduledReportSearchPaging;
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		fcom.ajax(fcom.makeUrl('RescheduleReport', 'viewReport'), data, function(t) {
			fcom.updateFaceboxContent(t, 'xlargebox');
		});
		
    };
	
	exportReport = function(userId, reportType){
		document.frmRescheduledReportSearch.action = fcom.makeUrl('RescheduleReport','export', [userId, reportType]);
		document.frmRescheduledReportSearch.submit();		
	}
	
	clearSearch = function(){
		//$("#frm_fat_id_frmRescheduledReportSearch input:hidden").val('').trigger('change');
		document.frmRescheduledReportSearch.op_learner_id.value = '';
		document.frmRescheduledReportSearch.op_teacher_id.value = '';
		document.frmRescheduledReportSearch.reset();
		searchTopLangReport(document.frmRescheduledReportSearch);
	};
})();	