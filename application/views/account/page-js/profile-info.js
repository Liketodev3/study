$(document).ready(function(){
	profileInfoForm();
	
	$(".tabs-inline ul li a").on('click',function(){
		$('.tabs-inline ul li').removeClass('is-active');
		$(this).parent('li').addClass('is-active');
	});
	
});

(function() {
	var runningAjaxReq = false;
	var dv = '#profileInfoFrmBlock';
	
	checkRunningAjax = function(){
		if( runningAjaxReq == true ){
			console.log(runningAjaxMsg);
			return;
		}
		runningAjaxReq = true;
	};
	
	changePasswordForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changePasswordForm'), '', function(t) {			
			$(dv).html(t);
		});
	};
	
	changeEmailForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changeEmailForm'), '', function(t) {			
			$(dv).html(t);
		});
	};
	
	bankInfoForm = function(){
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('Teacher','bankInfoForm'),'',function(t){
			$(dv).html(t);
			$('#innerTabs > li').removeClass('is-active');
			$('#innerTabs > li:nth-child(2)').addClass('is-active');
		});
	};
	
	setUpBankInfo = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setUpBankInfo'), data, function(t) {	
			bankInfoForm();						
		});
	};
	
	paypalEmailAddressForm = function(){
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('Teacher','paypalEmailAddressForm'),'',function(t){
			$(dv).html(t);
			$('#innerTabs > li').removeClass('is-active');
			$('#innerTabs > li:nth-child(1)').addClass('is-active');
		});
	};
	
	setUpPaypalInfo = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setUpPaypalInfo'), data, function(t) {	
			paypalEmailAddressForm();						
		});
	};
	
	setUpPassword = function (frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpPassword'), data, function(t) {						
			changePasswordForm();			
		});	
	};
	
	setUpEmail = function (frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpEmail'), data, function(t) {						
			changeEmailForm();			
		});	
	};
	
	profileInfoForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'ProfileInfoForm'), '', function(t) {			
			$(dv).html(t);
		});
	};
	
	setUpProfileInfo = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpProfileInfo'), data, function(t) {					
			//$.mbsmessage.close();			
            getLangProfileInfoForm(1);
			return ;
		});	
	};
	
	teacherPreferencesForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherPreferencesForm'), '', function(t) {			
			$(dv).html(t);
		});
	};
	
	setupTeacherPreferences  = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherPreferences'), data, function(t) {					
			//$.mbsmessage.close();			
		});	
	};
	
	teacherLanguagesForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherLanguagesForm'), '', function(t) {			
			$(dv).html(t);
		});
	};
		
	setupTeacherLanguages  = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);        
        $.confirm({
            title: 'Confirm!',
            content: langLbl.languageUpdateAlert,
            buttons: {
                Proceed: {
                    text: 'Proceed',
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherLanguages'), data, function(t) {					
                            //$.mbsmessage.close();
                            teacherLanguagesForm();
                        });
                    }
                },                        
                Quit: {
                    text: 'Quit',
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }                        
            }
        });        
	};
	
	setPreferredDashboad = function (id){
		fcom.updateWithAjax(fcom.makeUrl('Account','setPrefferedDashboard',[id]),'',function(res){			
		});
	};
	
	
	teacherSettingsForm = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','settingsInfoForm'),'',function(t){
			$(dv).html(t);
		});
	};
	
	setUpTeacherSettings = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setUpSettings'), data, function(t) {	
			teacherSettingsForm();						
		});
	};
	
	teacherQualification = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','teacherQualification'),'',function(t){
			console.log(dv);
			$(dv).html(t);
			
		});
	};
	
	teacherGeneralAvailability = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','teacherGeneralAvailability'),'',function(t){
			$(dv).html(t);
		});
	};
	
	
	deleteLanguageRow = function(id){        
        $.confirm({
            title: 'Confirm!',
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: 'Proceed',
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteLanguageRow',[id]), '' , function(t) {		
                            teacherLanguagesForm();		 
                        });
                    }
                },                        
                Quit: {
                    text: 'Quit',
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }                        
            }
        });
	};

	deleteTeachLanguageRow = function(id){        
        $.confirm({
            title: 'Confirm!',
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: 'Proceed',
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeachLanguageRow',[id]), '' , function(t) {		
                            teacherLanguagesForm();		 
                        });
                    }
                },                        
                Quit: {
                    text: 'Quit',
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }                        
            }
        });
	};
	
	teacherWeeklySchedule = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','teacherWeeklySchedule'),'',function(t){
			console.log(dv);
			$(dv).html(t);
			
		});
	};
	
	setupTeacherWeeklySchedule  = function(frm){
		$(dv).html(fcom.getLoader());        
		 fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherWeeklySchedule'), 'data='+frm, function(t) {			
            teacherWeeklySchedule()         
			//$("#w_calendar").fullCalendar("refetchEvents");
		});	
	};
	
	setupTeacherGeneralAvailability  = function(frm){
		$(dv).html(fcom.getLoader());                
		 fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherGeneralAvailability'), 'data='+frm, function(t) {					
			teacherGeneralAvailability();
            //$("#ga_calendar").fullCalendar("refetchEvents");
		});	
	};
	
	deleteTeacherGeneralAvailability  = function(id){
		 if(confirm(langLbl['confirmRemove'])){
			 fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherGeneralAvailability',[id]), '' , function(t) {		
				$('#ga_calendar').fullCalendar('removeEvents',id);			 
			});	
		 }
	};
	
	deleteTeacherWeeklySchedule  = function(eventData){
		 if(confirm(langLbl['confirmRemove'])){
			var json = JSON.stringify($("#w_calendar").fullCalendar("clientEvents").map(function(e) {
			console.log(e.className);
			return 	{
				start: moment(e.start).format('HH:mm:ss'),
				end: moment(e.end).format('HH:mm:ss'),
				day: moment(e.start).format('d'),
				date: moment(e.end).format('YYYY-MM-DD'),
				_id: e._id,
				action: e.action,
				classtype: e.classType,
			};
			}));
			fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherWeeklySchedule'), 'data='+json, function(t) {					
				fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherWeeklySchedule'), 'data='+JSON.stringify(eventData) , function(t) {		
					$("#w_calendar").fullCalendar("refetchEvents");				
				});
			});
		 }
	};

	teacherPreferences = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','teacherPreferences'),'',function(t){
			console.log(dv);
			$(dv).html(t);
			
		});
	};
	
	teacherQualificationForm = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','teacherQualificationForm',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
			teacherQualification();
		});
	};
	
	setUpTeacherQualification = function(frm){
		if (!$(frm).validate()) return false;	
        
        $(frm.btn_submit).attr('disabled','disabled'); 
		var formData = new FormData(frm);
			$.ajax({
                url: fcom.makeUrl('Teacher', 'setUpTeacherQualification'),
                type: 'POST',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
				processData: false,
                success: function (data, textStatus, jqXHR) {
					var data=JSON.parse(data);
					
					if(data.status==0)
					{
						$.mbsmessage(data.msg,true,'alert alert--danger');
                        $(frm.btn_submit).removeAttr("disabled");                        
						return false;
					}
						$.mbsmessage(data.msg,true,'alert alert--success');
                        $(frm.btn_submit).removeAttr("disabled");                                               
						teacherQualification();
						$.facebox.close();
						setTimeout(function(){
							$.mbsmessage.close();
						},2000);
				},
                error: function (jqXHR, textStatus, errorThrown) {
					$.mbsmessage(jqXHR.msg, true,'alert alert--danger');
                    $(frm.btn_submit).removeAttr("disabled");                                          
				}
            });
	};
	
	deleteTeacherQualification = function(id){
		if(confirm(langLbl['confirmRemove'])){
			fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherQualification',[id]), '', function(t) {					
				teacherQualification();
				$.facebox.close();
			});	
		}
	};
	
	removeProfileImage = function(){
		fcom.ajax(fcom.makeUrl('Account','removeProfileImage'),'',function(t){
			profileInfoForm();
		});
	};
	
	sumbmitProfileImage = function(){
		$("#frmProfile").ajaxSubmit({
			delegation: true,
			success: function(json){
				json = $.parseJSON(json);
				profileInfoForm();
				$(document).trigger('close.facebox');
			}
		});
	};
	
	$(document).on('click', '[data-method]', function () { 
		var data = $(this).data(),
          $target,
          result;
	
      if (data.method) {
        data = $.extend({}, data); // Clone a new one
        if (typeof data.target !== 'undefined') {
          $target = $(data.target);
          if (typeof data.option === 'undefined') {
            try {
              data.option = JSON.parse($target.val());
            } catch (e) {
              console.log(e.message);
            }
          }
        }
        result = $image.cropper(data.method, data.option);
		if (data.method === 'getCroppedCanvas') {
          $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
        }

        if ($.isPlainObject(result) && $target) {
          try {
            $target.val(JSON.stringify(result));
          } catch (e) {
            console.log(e.message);
          }
        }
		
      }
    });
	
	var $image ;
	cropImage = function(obj){
		$image = obj;
		$image.cropper({
			aspectRatio: 1,
			autoCropArea: 0.4545,
			// strict: true,
			guides: false,
			highlight: false,
			dragCrop: false,
			cropBoxMovable: false,
			cropBoxResizable: false,
			rotatable:true,
			responsive: true,
			crop: function (e) {
				var json = [
					'{"x":' + e.x,
					'"y":' + e.y,
					'"height":' + e.height,
					'"width":' + e.width,
					'"rotate":' + e.rotate + '}'
					].join();					
				$("#img_data").val(json);				
			  },
			built: function () {
			$(this).cropper("zoom", 0.5);
		  },		 
		})
	};
	
	popupImage = function(input){
		$.facebox( fcom.getLoader());
		
		wid = $(window).width();
		if(wid > 767){
			wid = 500; 
		}else{
			wid = 280;
		}
		
		var defaultform = "#frmProfile";
		$("#avatar-action").val("demo_avatar");		
		$(defaultform).ajaxSubmit({
			delegation: true,
			success: function(json){
				json = $.parseJSON(json);
				if(json.status == 1){
					$("#avatar-action").val("avatar");
					var fn = "sumbmitProfileImage();";
					
					$.facebox('<div class="popup__body"><div class="img-container "><img alt="Picture" src="" class="img_responsive" id="new-img" /></div><div class="img-description"><div class="rotator-info">Use Mouse Scroll to Adjust Image</div><div class="-align-center rotator-actions"><a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#rotate_left").val()+'" data-option="-90" data-method="rotate">'+$("#rotate_left").val()+'</a>&nbsp;<a onclick='+fn+' href="javascript:void(0)" class="btn btn--secondary btn--sm">'+$("#update_profile_img").val()+'</a>&nbsp;<a href="javascript:void(0)" class="btn btn--primary btn--sm rotate-right" title="'+$("#rotate_right").val()+'" data-option="90" data-method="rotate">'+$("#rotate_right").val()+'</a></div></div></div>','');
					$('#new-img').attr('src', json.file);
					$('#new-img').width(wid);
					cropImage($('#new-img'));
				}else{
                    $.mbsmessage(json.msg,true,'alert alert--danger');
                    $(document).trigger('close.facebox');
                    return false;
					//$.facebox('<div class="popup__body"><div class="img-container marginTop20">'+json.msg+'</div></div>');
				}
			}
		});
	};

    getLangProfileInfoForm = function(id){
        $('#langForm').html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('Account','userLangForm',[id]),'',function(t){
            $('#langForm').html(t);
        });
    };

	setUpProfileLangInfo  = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpProfileLangInfo'), data, function(t) {					
			if (t.langId>0) {
				getLangProfileInfoForm(t.langId);
				return ;
			}
		});	
	};    
})();	

