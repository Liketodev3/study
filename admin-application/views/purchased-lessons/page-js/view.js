
$(document).ready(function(){
	
	serachScheduledLesson(1);
	collapseContent();
});
var dv = '#lesson-deatils-js';
(function() {

	viewDetail = function(lessonId){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('PurchasedLessons', 'viewDetail', [lessonId]), '', function(t) {
			$.facebox(t,'faceboxWidth');
			});
		});
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmUserSearchPaging;
		$(frm.page).val(page);
		serachScheduledLesson(frm);
	};

	serachScheduledLesson = function(page){
		if (!page) {
			page = 1;
		}
		let data = '';
		data = 'page='+page;
		data += '&sldetail_order_id='+order_id;
		console.log(data);
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('PurchasedLessons','purchasedLessonsSearch'), data,function(res){
			$(dv).html(res);
		});
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}

		serachScheduledLesson(page);
	};

	updatePayment = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('PurchasedLessons', 'updatePayment'), data, function(t) {
			window.location.reload();
		});
	};

	updateScheduleStatus = function(obj, id, value, oldValue){
		// var currentValue = $(obj).val();
		if(!confirm(langLbl.confirmUpdateStatus)){ $(obj).val(oldValue); return;}
		if(id === null){
			$.mbsmessage(langLbl.invalidRequest);
			return false;
		}
		fcom.ajax(fcom.makeUrl('PurchasedLessons','updateStatusSetup'),{"sldetail_id":id, "slesson_status" : value},function(json){
			res = $.parseJSON(json);
			if(res.status == "1"){
				  $.mbsmessage( res.msg,true, 'alert alert--success');
			}else{
					$(obj).val(oldValue);
				  $.mbsmessage( res.msg,true, 'alert alert--danger');
			}
		});
	};
	////////////////////////////////////////
	collapseContent = function(){

		var text = $('.text-overflow'),
		btn = $('.btn-overflow'),
		h = text[0].scrollHeight; 
   
		if(h > 120) {
			btn.addClass('less');
			btn.css('display', 'block');
		}

		btn.click(function(e) 
		{
			e.stopPropagation();
			if (btn.hasClass('less')) {
				btn.removeClass('less');
				btn.addClass('more');
				btn.text('Show less');
		  
				text.animate({'height': h});
			} else {
				btn.addClass('less');
				btn.removeClass('more');
				btn.text('Show more');
				text.animate({'height': '120px'});
			}  

		});
	}

})();


