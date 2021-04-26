var isRuningTeacherQualificationFormAjax = false;

var teachLangs = [];
$(document).ready(function () {
	profileInfoForm();
	$('body').on('click', '.tab-ul-js li a', function () {
		$('.tab-ul-js li').removeClass('is-active');
		$(this).parent('li').addClass('is-active');
	});
	$(document).on('change', '[name^=duration]', selectDuration);
});

(function () {
	var runningAjaxReq = false;
	var dv = '#formBlock-js';
	var paymentInfoDiv = '#paymentInfoDiv';
	var profileInfoFormDiv = '#profileInfoFrmBlock';

	checkRunningAjax = function () {
		if (runningAjaxReq == true) {
			console.log(runningAjaxMsg);
			return;
		}
		runningAjaxReq = true;
	};

	changePasswordForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changePasswordForm'), '', function (t) {
			$(dv).html(t);
		});
	};

	getTeacherProfileProgress = function (showMessage) {
		showMessage = (showMessage) ? showMessage : true;
		if (!userIsTeacher) {
			return;
		}

		fcom.ajax(fcom.makeUrl('Teacher', 'getTeacherProfileProgress'), '', function (data) {

			if (data && data.teacherProfileProgress) {
				if (!data.teacherProfileProgress.isProfileCompleted && showMessage) {
					setTimeout(function () {
						$.systemMessage.close();
						$.mbsmessage.close();
						$.systemMessage(langLbl.teacherProfileIncompleteMsg, 'alert alert--warning');
						setTimeout(function () {
							$.systemMessage.close();
						}, 950);
					}, 600);
				}
				$.each(data.teacherProfileProgress, function (key, value) {
					
					switch (key) {

						case 'isProfileCompleted':
							if(value){
								$('.is-profile-complete-js').removeClass('infobar__media-icon--alert').addClass('infobar__media-icon--tick');
								$('.is-profile-complete-js').html('');
							}else{
								$('.is-profile-complete-js').removeClass('infobar__media-icon--tick').addClass('infobar__media-icon--alert');
								$('.is-profile-complete-js').html('!');
							}
						break;
						
						case 'generalAvailabilityCount':
							value = parseInt(value);
							if (0 >= value) {
								$('.general-availability-js').parent('li').removeClass('is-completed');
								
							} else {
								$('.general-availability-js').parent('li').addClass('is-completed');
							}
							break;
						// case 'userCountryId':
						case 'userProfile':
							
							// case 'userTimeZone':
							value = parseInt(value);
							if (0 >= value) {
								$('.profile-Info-js').parent('li').removeClass('is-completed');
							} else {
								$('.profile-Info-js').parent('li').addClass('is-completed');
							}
							break;
						case 'uqualificationCount':
							value = parseInt(value);
							if (0 >= value) {
								$('.teacher-qualification-js').parent('li').removeClass('is-completed');
							} else {
								$('.teacher-qualification-js').parent('li').addClass('is-completed');
							}
							break;
						case 'teachLangCount':
							value = parseInt(value);
							if (0 >= value) {
								$('.teacher-tech-lang-price-js').parent('li').removeClass('is-completed');
							} else {
								$('.teacher-tech-lang-price-js').parent('li').addClass('is-completed');
							}
							break;
						case 'slanguageCount':
							value = parseInt(value);
							if (0 >= value) {
								$('.teacher-lang-form-js').parent('li').removeClass('is-completed');
							} else {
								$('.teacher-lang-form-js').parent('li').addClass('is-completed');
							}
							break;
						case 'preferenceCount':
							value = parseInt(value);
							if (0 >= value) {
								$('.teacher-preferences-js').parent('li').removeClass('is-completed');
							} else {
								$('.teacher-preferences-js').parent('li').addClass('is-completed');
							}
							break;
						case 'percentage':
							$('.teacher-profile-progress-bar-js').attr("aria-valuenow", value);
							value = value + "%";
							$('.teacher-profile-progress-bar-js').css({ "width": value });
							break;
						case 'totalFilledFields':
							$('.progress__step').removeClass('is-active');
							for (let totalFilledFields = 0; totalFilledFields < value; totalFilledFields++) {
								$('.progress__step').eq(totalFilledFields).addClass('is-active');
							}
							value = data.teacherProfileProgress.totalFilledFields + "/" + data.teacherProfileProgress.totalFields;
							$('.progress-count-js').text(value);
							break;
						
					}
				});
			}

		}, { fOutMode: 'json' });
	}

	changeEmailForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changeEmailForm'), '', function (t) {
			$(dv).html(t);
		});
	};

	bankInfoForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'bankInfoForm'), '', function (t) {
			$(dv).html(t);
		
		});
	};

	setUpBankInfo = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setUpBankInfo'), data, function (t) {
			bankInfoForm();
		});
	};

	paypalEmailAddressForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'paypalEmailAddressForm'), '', function (t) {
			$(dv).html(t);
			$('#innerTabs > li').removeClass('is-active');
			$('#innerTabs > li:nth-child(2)').addClass('is-active');
		});
	};

	setUpPaypalInfo = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setUpPaypalInfo'), data, function (t) {
			paypalEmailAddressForm();
		});
	};

	setUpPassword = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpPassword'), data, function (t) {
			changePasswordForm();
		});
	};

	setUpEmail = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpEmail'), data, function (t) {
			changeEmailForm();
		});
	};

	profileInfoForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'ProfileInfoForm'), '', function (t) {
			$(dv).html(t);
			if (userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	setUpProfileInfo = function (frm, gotoProfileImageForm) {
		if (!$(frm).validate()) {
			$("html, body").animate({ scrollTop: $(".error").eq(0).offset().top - 100 }, "slow");
			return false;
		}
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpProfileInfo'), data, function (t) {
			setTimeout(function () {
				$.systemMessage.close();
			}, 3000);

			if (isCometChatMeetingToolActive) {
				name = frm.user_first_name.value + " " + frm.user_last_name.value;
				userSeoUrl = '';
				if (frm.user_url_name) {
					userSeoUrl = userSeoBaseUrl + frm.user_url_name.value;
				}
				updateCometChatUser(userData.user_id, name, userImage, userSeoUrl);
			}

			if (userIsTeacher) {
				getTeacherProfileProgress();
			}
			
			if(gotoProfileImageForm){
				$('.profile-imag-li').click();
			}
			// else{
			// 	getLangProfileInfoForm(1);
			// }
			return true;
		});
	};

	teacherPreferencesForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherPreferencesForm'), '', function (t) {
			$(dv).html(t);
			if (userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};


	setupTeacherPreferences = function (frm, goToPaymentForm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherPreferences'), data, function (t) {
			//$.mbsmessage.close();
			if(goToPaymentForm) {
				$('.general-availability-js').trigger('click');
			}else if(userIsTeacher){
				getTeacherProfileProgress();
			}
			
			
		});
	};

	teacherLanguagesForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherLanguagesForm'), '', function (t) {
			$(dv).html(t);
			teachLangs = $('[name^=teach_lang_id]').map(function () {
				return this.value
			}).get();

			if (userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	updateCometChatUser = function (userId, name, avatarURL, profileURL) {
		if (!isCometChatMeetingToolActive) {
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

	setupTeacherLanguages = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		var newTeachLangs = $('[name^=teach_lang_id]').map(function () {
			return this.value
		}).get();

		var difference = [];
		jQuery.grep(newTeachLangs, function (el) {
			if (jQuery.inArray(el, teachLangs) == -1) difference.push(el);
		});
		
		if (difference.length <= 0) {
			$.loader.show();
			fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherLanguages'), data, function (t) {
				//$.mbsmessage.close();
				// teacherLanguagesForm();
				$.loader.hide();
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
					action: function () {
						$.loader.show();
						fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherLanguages'), data, function (t) {
							$.loader.hide();
						});
					}
				},
				Quit: {
					text: langLbl.Quit,
					btnClass: 'btn btn--secondary',
					keys: ['enter', 'shift'],
					action: function () {
					}
				}
			}
		});
	};

	setPreferredDashboad = function (id) {
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setPrefferedDashboard', [id]), '', function (res) {
			if (userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	changeProficiency = function (obj, langId) {
		langId = parseInt(langId);
		if(langId <= 0){
			return;
		}
		let value = obj.value;
		slanguageSection = '.slanguage-'+langId;
		slanguageCheckbox = '.slanguage-checkbox-'+langId;
		if(value == ''){
			$(slanguageSection).find('.badge-js').remove();
			$(slanguageSection).removeClass('is-selected');
			$(slanguageCheckbox).prop('checked',false);
		}else{
			$(slanguageSection).addClass('is-selected');
			$(slanguageCheckbox).prop('checked',true);
			$(slanguageSection).find('.badge-js').remove();
			$(slanguageSection).find('.selection__trigger-label').append('<span class="badge color-secondary badge-js  badge--round badge--small margin-0">'+obj.selectedOptions[0].innerHTML+'</span>');
		}
	};


	teacherSettingsForm = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'settingsInfoForm'), '', function (t) {
			$(dv).html(t);
			selectDuration();
			if (userIsTeacher) {
				getTeacherProfileProgress();
			}
		});
	};

	selectDuration = function () {
		$('[name^=duration]').each((i, fld) => {
			if ($(fld).is(':checked')) {
				$('input[name^="utl_single_lesson_amount["][name*="][' + fld.value + ']').closest('.fld_wrapper-js').show();
				$('input[name^="utl_bulk_lesson_amount["][name*="][' + fld.value + ']').closest('.fld_wrapper-js').show();
			} else {
				$('input[name^="utl_single_lesson_amount["][name*="][' + fld.value + ']').closest('.fld_wrapper-js').hide();
				$('input[name^="utl_bulk_lesson_amount["][name*="][' + fld.value + ']').closest('.fld_wrapper-js').hide();

				$('input[name^="utl_single_lesson_amount["][name*="][' + fld.value + ']').val('0.00');
				$('input[name^="utl_bulk_lesson_amount["][name*="][' + fld.value + ']').val('0.00');
			}
		});
	};

	setUpTeacherSettings = function (frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setUpSettings'), data, function (t) {
			teacherSettingsForm();
		});
	};

	teacherQualification = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherQualification'), '', function (t) {
			$(dv).html(t);
			if (userIsTeacher) {
				getTeacherProfileProgress();
			}

		});
	};

	teacherGeneralAvailability = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherGeneralAvailability'), '', function (t) {
			$(dv).html(t);
			if (userIsTeacher) {
				getTeacherProfileProgress();
			}

		});
	};


	deleteLanguageRow = function (id) {
		$.confirm({
			title: langLbl.Confirm,
			content: langLbl.confirmRemove,
			buttons: {
				Proceed: {
					text: langLbl.Proceed,
					btnClass: 'btn btn--primary',
					keys: ['enter', 'shift'],
					action: function () {
						fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteLanguageRow', [id]), '', function (t) {
							teacherLanguagesForm();
						});
					}
				},
				Quit: {
					text: langLbl.Quit,
					btnClass: 'btn btn--secondary',
					keys: ['enter', 'shift'],
					action: function () {
					}
				}
			}
		});
	};

	deleteTeachLanguageRow = function (id) {
		$.confirm({
			title: langLbl.Confirm,
			content: langLbl.confirmRemove,
			buttons: {
				Proceed: {
					text: langLbl.Proceed,
					btnClass: 'btn btn--primary',
					keys: ['enter', 'shift'],
					action: function () {
						fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeachLanguageRow', [id]), '', function (t) {
							teacherLanguagesForm();
						});
					}
				},
				Quit: {
					text: langLbl.Quit,
					btnClass: 'btn btn--secondary',
					keys: ['enter', 'shift'],
					action: function () {
					}
				}
			}
		});
	};

	teacherWeeklySchedule = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherWeeklySchedule'), '', function (t) {
			$(dv).html(t);

		});
	};

	setupTeacherWeeklySchedule = function (frm) {
		$(dv).html(fcom.getLoader());
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherWeeklySchedule'), 'data=' + frm, function (t) {
			teacherWeeklySchedule()
			//$("#w_calendar").fullCalendar("refetchEvents");
		});
	};

	setupTeacherGeneralAvailability = function (frm) {
		$(dv).html(fcom.getLoader());
		fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherGeneralAvailability'), 'data=' + frm, function (t) {
			teacherGeneralAvailability();
			//$("#ga_calendar").fullCalendar("refetchEvents");
		});
	};

	deleteTeacherGeneralAvailability = function (id) {
		if (confirm(langLbl['confirmRemove'])) {
			$('#ga_calendar').fullCalendar('removeEvents', id);
			//  fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherGeneralAvailability',[id]), '' , function(t) {
			// 		if(userIsTeacher) {
			// 		  getTeacherProfileProgress(false);
			// 		}
			// });
		}
	};

	teacherPreferences = function () {
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherPreferences'), '', function (t) {
			$(dv).html(t);

		});
	};

	teacherQualificationForm = function (id) {
		isRuningTeacherQualificationFormAjax = false;
		$.mbsmessage(langLbl.requestProcessing, false, 'alert alert--process');
		fcom.ajax(fcom.makeUrl('Teacher', 'teacherQualificationForm', [id]), '', function (t) {
			$.mbsmessage.close();
			$.systemMessage.close()
			$.facebox(t, 'facebox-medium');
			//teacherQualification();
		});
	};

	setUpTeacherQualification = function (frm) {
		if (!$(frm).validate()) return false;
		if (isRuningTeacherQualificationFormAjax) {
			return false;
		}
		isRuningTeacherQualificationFormAjax = true;
		var dv = $("#frm_fat_id_frmQualification");
		$(frm.btn_submit).attr('disabled', 'disabled');
		var formData = new FormData(frm);
		$.ajax({
			url: fcom.makeUrl('Teacher', 'setUpTeacherQualification'),
			type: 'POST',
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			processData: false,
			beforeSend: function () {
				$.mbsmessage(langLbl.requestProcessing, false, 'alert alert--process');
			},
			success: function (data, textStatus, jqXHR) {
				isRuningTeacherQualificationFormAjax = false;
				$.mbsmessage.close();
				var data = JSON.parse(data);

				if (data.status == 0) {
					isRuningTeacherQualificationFormAjax = false;
					$.mbsmessage(data.msg, true, 'alert alert--danger');
					return false;
				}
				$.mbsmessage(data.msg, true, 'alert alert--success');
				$(frm.btn_submit).removeAttr("disabled");
				teacherQualification();
				$.facebox.close();
				setTimeout(function () {
					$.mbsmessage.close();
				}, 2000);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				isRuningTeacherQualificationFormAjax = false;
				$.mbsmessage.close();
				$.mbsmessage(jqXHR.msg, true, 'alert alert--danger');
				$(frm.btn_submit).removeAttr("disabled");
			}
		});
	};

	deleteTeacherQualification = function (id) {
		if (confirm(langLbl['confirmRemove'])) {
			fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherQualification', [id]), '', function (t) {
				teacherQualification();
				$.facebox.close();
			});
		}
	};

	profileImageForm = function () {
		$(profileInfoFormDiv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'profileImageForm'), '', function (t) {
			$(profileInfoFormDiv).html(t);
		});
	};

	removeProfileImage = function () {
		$.loader.show();
		fcom.ajax(fcom.makeUrl('Account', 'removeProfileImage'), '', function (t) {
			$.loader.hide();
			profileImageForm();
			if (isCometChatMeetingToolActive) {
				name = userData.user_first_name + " " + userData.user_last_name;
				userSeoUrl = userSeoBaseUrl + userData.user_url_name;
				updateCometChatUser(userData.user_id, name, '', userSeoUrl);
			}

		});
	};

	sumbmitProfileImage = function (goToLangForm) {
		$.loader.show();
		$("#frmProfile").ajaxSubmit({
			delegation: true,
			success: function (json) {
				json = $.parseJSON(json);
				$.loader.hide();
				$(document).trigger('close.facebox');
				if (json.status == 1) {
					if (isCometChatMeetingToolActive) {
						name = userData.user_first_name + " " + userData.user_last_name;
						userSeoUrl = userSeoBaseUrl + userData.user_url_name;
						updateCometChatUser(userData.user_id, name, userImage, userSeoUrl);
					}
						
					$.mbsmessage(json.msg, true, 'alert alert--success');
					if(goToLangForm && $('.profile-lang-li').length > 0){
						$('.profile-lang-li').first().click();
					}else{
						profileImageForm();
					}
				} else {
					$.mbsmessage(json.msg, true, 'alert alert--danger');
					return false;
				}
				
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

	var $image;
	cropImage = function (obj) {
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
			rotatable: true,
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

	popupImage = function (input) {
		$.facebox(fcom.getLoader());

		wid = $(window).width();
		if (wid > 767) {
			wid = 500;
		} else {
			wid = 280;
		}

		var defaultform = "#frmProfile";
		$("#avatar-action").val("demo_avatar");
		$(defaultform).ajaxSubmit({
			delegation: true,
			success: function (json) {
				json = $.parseJSON(json);
				if (json.status == 1) {
					$("#avatar-action").val("avatar");
					var fn = "sumbmitProfileImage();";
					$.facebox('<div class="popup__body"><div class="img-container "><img alt="Picture" src="" class="img_responsive" id="new-img" /></div><div class="img-description"><div class="rotator-info">Use Mouse Scroll to Adjust Image</div><div class="-align-center rotator-actions"><a href="javascript:void(0)" class="btn btn--primary btn--sm" title="' + $("#rotate_left").val() + '" data-option="-90" data-method="rotate">' + $("#rotate_left").val() + '</a>&nbsp;<a onclick=' + fn + ' href="javascript:void(0)" class="btn btn--secondary btn--sm">' + $("#update_profile_img").val() + '</a>&nbsp;<a href="javascript:void(0)" class="btn btn--primary btn--sm rotate-right" title="' + $("#rotate_right").val() + '" data-option="90" data-method="rotate">' + $("#rotate_right").val() + '</a></div></div></div>', '');
					$('#new-img').attr('src', json.file);
					$('#new-img').width(wid);
					cropImage($('#new-img'));
				} else {
					$.mbsmessage(json.msg, true, 'alert alert--danger');
					$(document).trigger('close.facebox');
					return false;
					//$.facebox('<div class="popup__body"><div class="img-container marginTop20">'+json.msg+'</div></div>');
				}
			}
		});
	};

	getLangProfileInfoForm = function (id) {
		$(profileInfoFormDiv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'userLangForm', [id]), '', function (t) {
			$(profileInfoFormDiv).html(t);
		});
	};

	setUpProfileLangInfo = function (frm, gotToNextLangForm, goToLangForm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setUpProfileLangInfo'), data, function (t) {
			if(!gotToNextLangForm) {
				if (t.langId > 0) {
					getLangProfileInfoForm(t.langId);
					return;
				}
			}else if(goToLangForm){
				$('.teacher-lang-form-js').trigger('click');
			}
			else if($('.profile-lang-tab.is-active').next('.profile-lang-tab').length > 0){
				$('.profile-lang-tab.is-active').next('.profile-lang-tab').find('a').click();
			}
			
		});
	};

	saveGeneralAvailability = function () {
		var allevents = calendar.getEvents();
		let data = allevents.map(function (e) {
			return {
				start: moment(e.start).format('HH:mm:ss'),
				end: moment(e.end).format('HH:mm:ss'),
				startTime: moment(e.start).format('HH:mm'),
				endTime: moment(e.end).format('HH:mm'),
				day: moment(e.start).format('d'),
				dayStart: moment(e.start).format('d'),
				dayEnd: moment(e.end).format('d'),
				classtype: e.classType,
			};
		});

		data.forEach(element => {

			if ((element.dayStart != element.dayEnd)
				&&
				((element.endTime != '00:00') || (element.startTime == '00:00'))
			) {

				if ((element.dayEnd - element.dayStart == 1)) {

					if ((element.endTime != '00:00') || (element.startTime != '00:00')) {

						let elementClone = $.parseJSON(JSON.stringify(element));
						elementClone.day = parseInt(element.dayEnd);
						elementClone.start = '00:00:00';
						elementClone.startTime = '00:00';
						data[data.length] = elementClone;
					}
				} else {

					for (let index = 0; index < element.dayEnd - element.dayStart; index++) {

						if ((element.endTime == '00:00') && (parseInt(element.dayStart) + index + 1 == element.dayEnd)) {

							continue;
						}
						let elementClone = $.parseJSON(JSON.stringify(element));
						elementClone.day = parseInt(element.dayStart) + index + 1;
						elementClone.start = '00:00:00';
						elementClone.startTime = '00:00';
						if (index + 1 != element.dayEnd - element.dayStart) {
							elementClone.end = '24:00:00';
							elementClone.endTime = '24:00';
						}
						data[data.length] = elementClone;
					}
				}
				element.end = '24:00:00';
				element.endTime = '24:00';
			}
		});
		var json = JSON.stringify(data);

		setupTeacherGeneralAvailability(json);
	};

	setUpWeeklyAvailability = function () {
		var json = JSON.stringify(calendar.getEvents().map(function (e) {

			return {
				start: moment(e.start).format('HH:mm:ss'),
				end: moment(e.end).format('HH:mm:ss'),
				date: moment(e.start).format('YYYY-MM-DD'),
				_id: e.extendedProps._id,
				action: e.extendedProps.action,
				classtype: e.extendedProps.classType,
			};
		}));

		setupTeacherWeeklySchedule(json);
	};

})();
