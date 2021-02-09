var isRuningTeacherQualificationFormAjax = false;

var teachLangs = [];
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

	getTeacherProfileProgress = function(showMessage){
		showMessage = (showMessage) ? showMessage :  true;
		if(!userIsTeacher) {
			return;
		}

		fcom.ajax(fcom.makeUrl('Teacher', 'getTeacherProfileProgress'), '', function(data) {

			if(data && data.teacherProfileProgress) {
				if(!data.teacherProfileProgress.isProfileCompleted && showMessage){
						setTimeout(function() {
								$.systemMessage.close();
								$.mbsmessage.close();
								$.systemMessage(langLbl.teacherProfileIncompleteMsg, 'alert alert--warning');
								setTimeout(function() {
									$.systemMessage.close();
								}, 950);
						},600);
				}
				$.each(data.teacherProfileProgress,function( key, value ){
						switch (key) {
							case 'generalAvailabilityCount':
							value =  parseInt(value);
								if(0 >= value) {
									$('.general-availability-js').addClass('-color-secondary');
								}else{
									$('.general-availability-js').removeClass('-color-secondary');
								}
							break;
							// case 'userCountryId':
							case 'userProfile':
							// case 'userTimeZone':
							value =  parseInt(value);
								if(0 >= value) {
									$('.profile-Info-js').addClass('-color-secondary');
								}else{
									$('.profile-Info-js').removeClass('-color-secondary');
								}
							break;
							case 'uqualificationCount':
							value =  parseInt(value);
								if(0 >= value) {
									$('.teacher-qualification-js').addClass('-color-secondary');
								}else{
									$('.teacher-qualification-js').removeClass('-color-secondary');
								}
							break;
							case 'teachLangCount':
							value =  parseInt(value);
								if(0 >= value) {
									$('.teacher-tech-lang-price-js').addClass('-color-secondary');
								}else{
									$('.teacher-tech-lang-price-js').removeClass('-color-secondary');
								}
							break;
							case 'slanguageCount':
							value =  parseInt(value);
								if(0 >= value) {
									$('.teacher-lang-form-js').addClass('-color-secondary');
								}else{
									$('.teacher-lang-form-js').removeClass('-color-secondary');
								}
							break;
							case 'preferenceCount':
							value =  parseInt(value);
								if(0 >= value) {
									$('.teacher-preferences-js').addClass('-color-secondary');
								}else{
									$('.teacher-preferences-js').removeClass('-color-secondary');
								}
							break;
							case 'percentage':
								$('.teacher-profile-progress-bar-js').attr("aria-valuenow",value);
									value = value+"%";
									$('.teacher-profile-progress-bar-js').css({"width": value});
							break;
							case 'totalFields':
							case 'totalFilledFields':
									value =  data.teacherProfileProgress.totalFilledFields+"/"+data.teacherProfileProgress.totalFields;
									$('.progress-count-js').text(value);
							break;
						}
				});
			}

		},{fOutMode:'json'});
	}

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
			$('#innerTabs > li:nth-child(1)').addClass('is-active');
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
			$('#innerTabs > li:nth-child(2)').addClass('is-active');
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
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	setUpProfileInfo = function(frm){
		if (!$(frm).validate()) {
			$("html, body").animate({ scrollTop:  $(".error").eq(0).offset().top-100 }, "slow");
			return;
		}
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpProfileInfo'), data, function(t) {
			setTimeout(function() {
				$.systemMessage.close();
			}, 3000);

			if(isCometChatMeetingToolActive) {
				name = frm.user_first_name.value + " "+frm.user_last_name.value;
				userSeoUrl = '';
				if(frm.user_url_name) {
					userSeoUrl = userSeoBaseUrl+frm.user_url_name.value;
				}
				updateCometChatUser(userData.user_id, name, userImage, userSeoUrl);
			}

		if(userIsTeacher) {
			getTeacherProfileProgress();
		}
		getLangProfileInfoForm(1);
			return ;
		});
	};

	teacherPreferencesForm = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherPreferencesForm'), '', function(t) {
			$(dv).html(t);
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};


	setupTeacherPreferences  = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherPreferences'), data, function(t) {
			//$.mbsmessage.close();
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	teacherLanguagesForm = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherLanguagesForm'), '', function(t) {
			$(dv).html(t);
			teachLangs = $('[name^=teach_lang_id]').map(function(){
				return this.value
			}).get();
  
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	updateCometChatUser = function(userId, name, avatarURL, profileURL){
		if(!isCometChatMeetingToolActive) {
			return true;
		}
		var settings = {
			"async": true,
			"crossDomain": true,
			"url": "https://api.cometondemand.net/api/v2/updateUser",
			"method": "POST",
			"headers": {
				"api-key": chat_api_key,
				"content-type": "application/x-www-form-urlencoded",
			},
			"data": {
			"UID": userId,
			"name": name,
			"avatarURL": avatarURL,
			"profileURL": profileURL,
			}
		}

        $.ajax(settings).done(function (response) {
            console.log(response);
        });
	};

	setupTeacherLanguages  = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		var newTeachLangs = $('[name^=teach_lang_id]').map(function(){
			return this.value
		}).get();

		var difference = [];
		jQuery.grep(newTeachLangs, function(el) {
			if (jQuery.inArray(el, teachLangs) == -1) difference.push(el);
		});

		if(difference.length<=0){
			fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherLanguages'), data, function(t) {
				//$.mbsmessage.close();
				teacherLanguagesForm();
			});
			return;
		}
		
		$.confirm({
            title: langLbl.Confirm,
            content: langLbl.languageUpdateAlert,
            buttons: {
                Proceed: {
                    text: langLbl.Proceed,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherLanguages'), data, function(t) {
                            //$.mbsmessage.close();
                            teacherLanguagesForm();
														// $("#teacher-tech-lang-price-js").click();
                        });
                    }
                },
                Quit: {
                    text: langLbl.Quit,
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
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};


	teacherSettingsForm = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','settingsInfoForm'),'',function(t){
			$(dv).html(t);
				if(userIsTeacher) {
				  getTeacherProfileProgress();
				}
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
			$(dv).html(t);
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}

		});
	};

	teacherGeneralAvailability = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher','teacherGeneralAvailability'),'',function(t){
			$(dv).html(t);
			if(userIsTeacher) {
				getTeacherProfileProgress();
			}

		});
	};


	deleteLanguageRow = function(id){
        $.confirm({
            title: langLbl.Confirm,
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: langLbl.Proceed,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteLanguageRow',[id]), '' , function(t) {
                            teacherLanguagesForm();
                        });
                    }
                },
                Quit: {
                    text:  langLbl.Quit,
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
            title: langLbl.Confirm,
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: langLbl.Proceed,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeachLanguageRow',[id]), '' , function(t) {
                            teacherLanguagesForm();
                        });
                    }
                },
                Quit: {
                    text: langLbl.Quit,
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
			 	$('#ga_calendar').fullCalendar('removeEvents',id);
			//  fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherGeneralAvailability',[id]), '' , function(t) {
			// 		if(userIsTeacher) {
			// 		  getTeacherProfileProgress(false);
			// 		}
			// });
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
	isRuningTeacherQualificationFormAjax = false;
		$.mbsmessage(langLbl.requestProcessing, false, 'alert alert--process');
		fcom.ajax(fcom.makeUrl('Teacher','teacherQualificationForm',[id]),'',function(t){
			$.mbsmessage.close();
			$.systemMessage.close()
			$.facebox( t,'facebox-medium');
			//teacherQualification();
		});
	};

	setUpTeacherQualification = function(frm){
		if (!$(frm).validate()) return false;
		if(isRuningTeacherQualificationFormAjax) {
			return false;
		}
			isRuningTeacherQualificationFormAjax = true;
        var dv = $("#frm_fat_id_frmQualification");
        $(frm.btn_submit).attr('disabled','disabled');
		var formData = new FormData(frm);
			$.ajax({
                url: fcom.makeUrl('Teacher', 'setUpTeacherQualification'),
                type: 'POST',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
									processData: false,
									beforeSend: function(){
										$(dv).html(fcom.getLoader());
									},
                success: function (data, textStatus, jqXHR) {

									var data=JSON.parse(data);

									if(data.status==0)
									{
											isRuningTeacherQualificationFormAjax  = false;
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
									isRuningTeacherQualificationFormAjax  = false;
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
		$('.loading-wrapper').show();
		fcom.ajax(fcom.makeUrl('Account','removeProfileImage'),'',function(t){
			$('.loading-wrapper').hide();
			profileInfoForm();

			if(isCometChatMeetingToolActive) {
				name = userData.user_first_name + " "+userData.user_last_name;
				userSeoUrl =  userSeoBaseUrl+userData.user_url_name;
				updateCometChatUser(userData.user_id, name, '', userSeoUrl);
			}
			
		});
	};

	sumbmitProfileImage = function(){
		$('.loading-wrapper').show();
		$("#frmProfile").ajaxSubmit({
			delegation: true,
			success: function(json){
				json = $.parseJSON(json);

				if(isCometChatMeetingToolActive) {
					name = userData.user_first_name + " "+userData.user_last_name;
					userSeoUrl = userSeoBaseUrl+userData.user_url_name;
					updateCometChatUser(userData.user_id, name, userImage , userSeoUrl);
				}
				$('.loading-wrapper').hide();
				profileInfoForm();
				$.mbsmessage(json.msg,true,'alert alert--success');
				$(document).trigger('close.facebox');
				$('.loading-wrapper').hide();
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
			// autoCropArea: 0.4545,
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
					'{"x":' + e.detail.x,
					'"y":' + e.detail.y,
					'"height":' + e.detail.height,
					'"width":' + e.detail.width,
					'"rotate":' + e.detail.rotate + '}'
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
	// $("[name='user_timezone']").select2();
})();
