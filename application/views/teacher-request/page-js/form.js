var teacherQualificationAjax = false;
var setUpTeacherApprovalAjax = false;
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
		if(teacherQualificationAjax) {
			return false;
		}
		teacherQualificationAjax = true;
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
				teacherQualificationAjax = false;
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

	 setUpTeacherApproval = function( frm ){
		if ( !$(frm).validate() ){ 
			$("html, body").animate({ scrollTop:  $(".error").eq(0).offset().top - 100 }, "slow");
			return; 
		}
		if(setUpTeacherApprovalAjax) {
			return false;
		}
		setUpTeacherApprovalAjax = true;
		data =  new FormData(frm);
		console.log(frm.user_profile_pic.files);
		if(frm.user_profile_pic.files.lenght > 0) {

			data.append('user_profile_pic',frm.user_profile_pic.files[0]);
		}
		if(frm.user_photo_id.files.lenght > 0) {
			data.append('user_photo_id',frm.user_photo_id.files[0]);
		}
			data.append('fIsAjax',1);
			data.append('fOutMode','json');
		$.systemMessage(langLbl.processing,'alert--process');
		$.ajax({
			method: "POST",
			url: fcom.makeUrl('TeacherRequest', 'setUpTeacherApproval'),
			processData: false,
			contentType: false,
			data: data,
			// async: false,
			success: function (result) {
				try {
					$.systemMessage.close();
					result = JSON.parse(result);
					if (result.status != 1) {
						setUpTeacherApprovalAjax = false;
						$(document).trigger('close.mbsmessage');
						$.mbsmessage(result.msg,true, 'alert alert--danger');
						return false;
					}
					$.mbsmessage(result.msg,true, 'alert alert--success');
					if( result.redirectUrl ){
						setTimeout(function(){ window.location.href = result.redirectUrl }, 2000);
							return;
					}
				} catch (e) {
					setUpTeacherApprovalAjax = false;
					$.mbsmessage(e,true, 'alert alert--danger');
						return;
				}
				console.log(result);
			}
		});
		// fcom.updateWithAjax(fcom.makeUrl('TeacherRequest', 'setUpTeacherApproval'), fcom.frmData(frm), function(res) {
		// 	return false;
		// 	// if( res.redirectUrl ){
		// 	// 	window.location.href = res.redirectUrl;
		// 	// 	return;
		// 	// }
		// },{contentType: false,processData: false});
	}

})(jQuery);
