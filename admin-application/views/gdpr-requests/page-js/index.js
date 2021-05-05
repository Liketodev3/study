$(function() {
    var currentPage = 1;
	var dv = '#listItems';
	searchGdprRequests = function(frm,page){
        if (!page) {
			page = currentPage;
		}
		currentPage = page;
        var data = '';
        if(frm){
            data = fcom.frmData(frm);
        }
        
		fcom.ajax(fcom.makeUrl('GdprRequests','search'),data,function(t){
			$(dv).html(t);
		});
	};

	reloadList = function() {
		// var frm = document.frmReviewSearchPaging;
		searchGdprRequests(document.frmSrch);
	}

	changeStatus = function(requestId){
		$.facebox(function(){
			fcom.ajax(fcom.makeUrl('GdprRequests', 'view', [requestId]), '', function(t){
				$.facebox(t, 'faceboxWidth')
			});
		});
	};

	eraseUserPersonalData = function(data) {
		fcom.ajax(fcom.makeUrl('GdprRequests', 'eraseUserPersonalData'), data, function(t) {
			// $(document).trigger('close.facebox');
			// reloadList();
		});
	};

	updateStatus = function(frm){
		if(!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('GdprRequests', 'updateStatus'), data, function(t) {
			eraseUserPersonalData(data);
		});
	};

	searchGdprRequests(document.frmSrch);
});