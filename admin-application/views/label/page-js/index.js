$(document).ready(function(){
	searchLabels(document.frmLabelsSearch);
});

(function(){
	var currentPage = 1;
	var runningAjaxReq = false;
	var dv = '#listing';
	
	goToSearchPage = function(page) {	
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmLabelsSrchPaging;		
		$(frm.page).val(page);
		searchLabels(frm);
	};
	
	reloadList = function() {
		var frm = document.frmLabelsSrchPaging;
		searchLabels(frm);
	};
	
	searchLabels = function(frm){
		//if (!$(frm).validate()) return;
		$(dv).html(fcom.getLoader());
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		fcom.ajax(fcom.makeUrl('Label','search'),data,function(res){
			$(dv).html(res);
		});
	};
	
	labelsForm = function(labelId){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Label', 'form', [labelId]), '', function(t) {
				$.facebox(t,'faceboxWidth');
			});
		});
	};
	
	setupLabels = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Label', 'setup'), data, function(t) {						
			reloadList();			
			$(document).trigger('close.facebox');
		});
	};
	
	clearSearch = function(){		
		document.frmLabelsSearch.reset();		
		searchLabels(document.frmLabelsSearch);
	};
	
	exportLabels = function(){
		document.frmLabelsSearch.action = fcom.makeUrl( 'Label', 'export' );
		document.frmLabelsSearch.submit();		
	};
	
	importLabels = function(){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Label', 'importLabelsForm'), '', function(t) {
				$.facebox(t,'faceboxWidth');
			});
		});
	};
	
	submitImportLaeblsUploadForm = function ()
	{
		var data = new FormData(  );
		$inputs = $('#frmImportLabels input[type=text],#frmImportLabels select,#frmImportLabels input[type=hidden]');
		$inputs.each(function() { data.append( this.name,$(this).val());});	
		
		$.each( $('#import_file')[0].files, function(i, file) {
			$('#fileupload_div').html(fcom.getLoader());
			data.append('import_file', file);
			$.ajax({
				url : fcom.makeUrl('Label', 'uploadLabelsImportedFile'),
				type: "POST",
				data : data,
				processData: false,
				contentType: false,
				success: function(t){					
					try {
						var ans = $.parseJSON(t);
						if( ans.status == 1 ){
							fcom.displaySuccessMessage(ans.msg);
							reloadList();
							$(document).trigger('close.facebox');
						} else {
							fcom.displayErrorMessage(ans.msg);
							$('#fileupload_div').html('');
						}						
					}
					catch(exc){
						fcom.displayErrorMessage(t);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert("Error Occured.");
				}
			});
		});		
	};	
})()	