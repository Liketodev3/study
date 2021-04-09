$(document).ready(function () {
	listMetaTags('-1');
});

$(document).delegate('.language-js', 'change', function () {
	var langId = $(this).val();
	var metaId = $("input[name='meta_id']").val();
	images(metaId, langId);
});

(function () {
	var runningAjaxReq = false;
	var dv = '#listing';

	goToSearchPage = function (page) {
		if (typeof page == undefined || page == null) {
			page = 1;
		}
		var frm = document.frmMetaTagSearchPaging;
		$(frm.page).val(page);
		searchMetaTag(frm);
	}

	reloadList = function () {
		var frm = document.frmMetaTagSearchPaging;
		searchMetaTag(frm);
	};

	listMetaTags = function (metaType) {
		metaType = metaType || '';

		fcom.ajax(fcom.makeUrl('MetaTags', 'listMetaTags'), { metaType: metaType }, function (res) {
			$('#frmBlock').html(res);
			searchMetaTag(document.frmSearch);
		});
	};

	deleteImage = function (metaId, langId) {
		if (!confirm(langLbl.confirmDeleteImage)) { return; }
		fcom.updateWithAjax(fcom.makeUrl('MetaTags', 'removeImage', [metaId, langId]), '', function (t) {
			images(metaId, langId);
		});
	};

	searchMetaTag = function (form) {
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());

		fcom.ajax(fcom.makeUrl('MetaTags', 'search'), data, function (res) {
			$(dv).html(res);
		});
	};
	addMetaTagForm = function (id, metaType, recordId) {

		$.facebox(function () {
			metaTagForm(id, metaType, recordId);
		});
	};

	metaTagForm = function (id, metaType, recordId) {
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('MetaTags', 'form'), { metaId: id, metaType: metaType, recordId: recordId }, function (t) {
			fcom.updateFaceboxContent(t);
		});

	};
	editMetaTagFormNew = function (id, metaType, recordId) {
		$.facebox(function () { editMetaTagForm(id, metaType, recordId); });
	};


	editMetaTagForm = function (id, metaType, recordId) {
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('MetaTags', 'form'), { metaId: id, metaType: metaType, recordId: recordId }, function (t) {
			fcom.updateFaceboxContent(t);
		});
	};


	setupMetaTag = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('MetaTags', 'setup'), data, function (t) {
			reloadList();
			if (t.langId > 0) {
				editMetaTagLangForm(t.metaId, t.langId, t.metaType);
				return;
			}
			$(document).trigger('close.facebox');
		});
	}

	editMetaTagLangForm = function (metaId, langId, metaType) {
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('MetaTags', 'langForm', [metaId, langId, metaType]), '', function (t) {
			fcom.updateFaceboxContent(t);
			images(metaId, langId);
		});
		//});
	};

	setupLangMetaTag = function (frm, metaType) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('MetaTags', 'langSetup'), data, function (t) {
			reloadList();
			if (t.langId > 0) {
				editMetaTagLangForm(t.metaId, t.langId, metaType);
				return;
			}
			$(document).trigger('close.facebox');
		});
	};

	deleteRecord = function (id) {
		if (!confirm(langLbl.confirmDelete)) { return; }
		data = 'metaId=' + id;
		fcom.updateWithAjax(fcom.makeUrl('MetaTags', 'deleteRecord'), data, function (res) {
			reloadList();
		});
	};

	clearSearch = function () {
		document.frmSearch.reset();
		searchMetaTag(document.frmSearch);
	};

	images = function (metaId, langId) {
		fcom.ajax(fcom.makeUrl('MetaTags', 'images', [metaId, langId]), SITE_ROOT_URL, function (t) {
			$('#image-listing').html(t);
			fcom.resetFaceboxHeight();
		});
	};
})();

$(document).on('click', '.meta-tag', function () {
	var node = this;
	$('#form-upload').remove();
	var metaId = document.frmMetaTagLang.meta_id.value;
	var langId = document.frmMetaTagLang.lang_id.value;
	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />');
	frm = frm.concat('<input type="hidden" name="meta_id" value="' + metaId + '"/>');
	frm = frm.concat('<input type="hidden" name="lang_id" value="' + langId + '"/>');

	$('body').prepend(frm);
	$('#form-upload input[name=\'file\']').trigger('click');
	if (typeof timer != 'undefined') {
		clearInterval(timer);
	}

	timer = setInterval(function () {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			$val = $(node).val();
			$.ajax({
				url: fcom.makeUrl('MetaTags', 'setUpOgImage', [metaId]),
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function () {
					$(node).val('Loading');
				},
				complete: function () {
					$(node).val($val);
				},
				success: function (ans) {
					$('#form-upload').remove();
					images(ans.metaId, langId);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});