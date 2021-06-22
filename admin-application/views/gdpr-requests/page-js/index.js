
$(document).ready(function () {
	searchGdprRequests(document.frmSrch);
});
(function () {
	var currentPage = 1;
	var dv = '#listItems';

	searchGdprRequests = function (frm, page) {
		if (!page) {
			page = currentPage;
		}

		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		fcom.ajax(fcom.makeUrl('GdprRequests', 'search'), data, function (t) {
			$(dv).html(t);
		});
	};

	reloadList = function () {
		searchGdprRequests(document.frmSrch);
	}
	view = function (requestId) {
		$.facebox(function () {
			fcom.ajax(fcom.makeUrl('GdprRequests', 'view'), { id: requestId }, function (t) {
				$.facebox(t, 'faceboxWidth')
			});
		});
	};
	updateStatus = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('GdprRequests', 'updateStatus'), data, function (t) {
			$.facebox.close();
			searchGdprRequests(document.frmSrch);
		});
	};

})();