$("document").ready(function(){
	searchTeacherQualification();
});


(function($){
	teacherQualificationForm = function( uqualification_id ){
		$.facebox(function() {
			fcom.ajax( fcom.makeUrl('TeacherRequest', 'teacherQualificationForm', []), 'uqualification_id='+uqualification_id, function(res){
				$.facebox(res, 'facebox-medium');
			});
		});
	};
	
	setUpTeacherQualification = function( frm ){
		if ( !$(frm).validate() ){ return; }
        $(frm.btn_submit).attr('disabled','disabled'); 
		var dv = $("#frm_fat_id_frmQualification");	
			
		var formData = new FormData(frm);
		$.ajax({
			url: fcom.makeUrl('TeacherRequest', 'setUpTeacherQualification'),
			type: 'POST',
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			//dataType: 'json',
			processData: false,
			beforeSend: function(){
				$(dv).html(fcom.getLoader());
			},
			success: function (data, textStatus, jqXHR) {
				var data = JSON.parse(data);
				if(data.status==0){
					$.mbsmessage(data.msg, true, 'alert alert--danger');
                    $(frm.btn_submit).attr('disabled',''); 
				} else {
					$.mbsmessage(data.msg, true, 'alert alert--success');
                    $(frm.btn_submit).attr('disabled',''); 
					reloadQualificationList();
					$(document).trigger('close.facebox');
			   }
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$.mbsmessage(jqXHR.msg, true,'alert alert--danger');
				$(frm.btn_submit).attr('disabled',''); 
			}
		});
	};
	
	searchTeacherQualification = function(){
		var dv = $('#resume_listing');
		$(dv).html( fcom.getLoader() );
		
		fcom.ajax( fcom.makeUrl('TeacherRequest', 'searchTeacherQualification'),'',function(res){
			$(dv).html(res);
		});
	};
	
	reloadQualificationList = function(){
		searchTeacherQualification();
	};
	
	deleteTeacherQualification = function( uqualification_id ){
		if(!confirm(langLbl.confirmRemove)){return;}
		
		fcom.updateWithAjax( fcom.makeUrl('TeacherRequest','deleteTeacherQualification'),'&uqualification_id='+uqualification_id,function(){
			reloadQualificationList();
			$(document).trigger('close.facebox');
		});
	};
	
	/* setUpTeacherApproval = function( frm ){
		if ( !$(frm).validate() ){ return; }
		fcom.updateWithAjax(fcom.makeUrl('TeacherRequest', 'setUpTeacherApproval'), fcom.frmData(frm), function(res) {
			if( res.redirectUrl ){
				window.location.href = res.redirectUrl;
				return;
			}
		});
	} */
	
})(jQuery);
