$(document).ready(function(){

	searchPuchasedLessons(document.frmPurchasedLessonsSearch);

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
				},
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

	//redirect user to login page
	$(document).on('click','ul.linksvertical li a.redirect--js',function(event){
		event.stopPropagation();
	});

});

(function() {
	var currentPage = 1;
	var transactionUserId = 0;
	var active = 1;
	var inActive = 0;

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmUserSearchPaging;
		$(frm.page).val(page);
		searchPuchasedLessons(frm);
	};

	searchPuchasedLessons = function(form,page){
		if (!page) {
			page = currentPage;
		}
		currentPage = page;
		/*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		/*]*/

		$("#userListing").html(fcom.getLoader());

		fcom.ajax(fcom.makeUrl('PurchasedLessons','search'),data,function(res){
			$("#userListing").html(res);
		});
	};

	clearUserSearch = function(){
		document.frmPurchasedLessonsSearch.reset();
		document.frmPurchasedLessonsSearch.order_user_id.value = '';
		document.frmPurchasedLessonsSearch.op_teacher_id.value = '';
		searchPuchasedLessons( document.frmPurchasedLessonsSearch );
	};

	updateOrderStatus = function(id,value){

		if(!confirm("Do you really want to update status?")){return;}
		if(id === null){
			$.mbsmessage('Invalid Request!');
			return false;
		}
		fcom.ajax(fcom.makeUrl('PurchasedLessons','updateOrderStatus'),{"order_id":id, "order_is_paid" : value},function(json){
			res = $.parseJSON(json);
			if(res.status == "1"){
				  $.mbsmessage( res.msg,true, 'alert alert--success');
					searchPuchasedLessons(document.frmPurchasedLessonsSearch);
			}else{
				  $.mbsmessage( res.msg,true, 'alert alert--danger');
			}
		});
	};

})();
