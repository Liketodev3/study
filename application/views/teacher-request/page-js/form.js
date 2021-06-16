var teacherQualificationAjax = false;
var setUpTeacherApprovalAjax = false;
$("document").ready(function () {
	searchTeacherQualification();
	$(document).on('click', '#uploadFileInput--js', function (e) {
		e.preventDefault();
		$('#frmProfileImage [name=user_profile_image]').trigger('click');
	});

	createForm(1);
});


(function ($) {
	teacherQualificationForm = function (uqualification_id) {
		fcom.ajax(fcom.makeUrl('TeacherRequest', 'teacherQualificationForm', []), 'uqualification_id=' + uqualification_id, function (res) {
			$.facebox(res, 'facebox-medium');
	
		});

	};

	createForm = function (step) {
		fcom.ajax(fcom.makeUrl('TeacherRequest', 'createForm'), {step:step}, function (res) {
			$('li[data-blocks-show]').removeClass('is-process');
			$('li[data-blocks-show="'+step+'"]').addClass('is-process');
			$('.page-block__body').html(res);
			if(step==1){
			intTell();
			var dial_code = $.trim($('.iti__selected-dial-code').text());
			$('#user_phone_code').val(dial_code);
			setTimeout(() => {
				setPhoneNumberMask();
			}, 100);
			}else if(step==3){
				if ($(window).width() > 1199) {
					$('.scrollbar-js').enscroll({
						verticalTrackClass: 'scrollbar-track',
						verticalHandleClass: 'scrollbar-handle'
					});
				}
			}

		});
	};

	setPhoneNumberMask = function() {
		let placeholder = $("#user_phone").attr("placeholder");
		if (placeholder) {
			placeholderlength = placeholder.length;
			placeholder = placeholder.replace(/[0-9.]/g, '9');
			$("#user_phone").inputmask({
				"mask": placeholder
			});
		}
	};

	setUpTeacherQualification = function (frm) {
		if (!$(frm).validate()) { return; }
		if (teacherQualificationAjax) {
			return false;
		}
		teacherQualificationAjax = true;
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
			beforeSend: function () {
				$.mbsmessage(langLbl.requestProcessing, false, 'alert alert--process');
			},
			success: function (data, textStatus, jqXHR) {

				teacherQualificationAjax = false;
				$.mbsmessage.close();
				var data = JSON.parse(data);
				if (data.status == 0) {
					$.mbsmessage(data.msg, true, 'alert alert--danger');
				} else {
					$.mbsmessage(data.msg, true, 'alert alert--success');
					reloadQualificationList();
					$(document).trigger('close.facebox');
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				teacherQualificationAjax = false;
				$.mbsmessage.close();
				$.mbsmessage(jqXHR.msg, true, 'alert alert--danger');
				$(frm.btn_submit).attr('disabled', '');
			}
		});
	};

	searchTeacherQualification = function () {
		var dv = $('#block--4');
		$(dv).html(fcom.getLoader());

		fcom.ajax(fcom.makeUrl('TeacherRequest', 'searchTeacherQualification'), '', function (res) {
			$(dv).html(res);
		});
	};

	reloadQualificationList = function () {
		searchTeacherQualification();
	};

	deleteTeacherQualification = function (uqualification_id) {
		if (!confirm(langLbl.confirmRemove)) { return; }

		fcom.updateWithAjax(fcom.makeUrl('TeacherRequest', 'deleteTeacherQualification'), '&uqualification_id=' + uqualification_id, function () {
			reloadQualificationList();
			$(document).trigger('close.facebox');
		});
	};

	setUpTeacherApproval = function (frm) {

		if (!$(frm).validate()) {
			//$("html, body").animate({ scrollTop: $(".error").eq(0).offset().top - 100 }, "slow");
			return;
		}
		if (setUpTeacherApprovalAjax) {
			return false;
		}
		setUpTeacherApprovalAjax = true;
		data = new FormData(frm);

		if (frm.user_photo_id.files.length > 0) {
			data.append('user_photo_id', frm.user_photo_id.files[0]);
		}
		data.append('fIsAjax', 1);
		data.append('fOutMode', 'json');
		$.systemMessage(langLbl.processing, 'alert--process');
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
						$.mbsmessage(result.msg, true, 'alert alert--danger');
						return false;
					}
					$.mbsmessage(result.msg, true, 'alert alert--success');
					if (result.redirectUrl) {
						setTimeout(function () {
							$('.page-block__body').hide();
							$('.change-block-js').removeClass('is-process');
							$('li[data-blocks-show').removeClass('change-block-js')
							$('li[data-blocks-show="5"]').addClass('is-process');
							$('#block--5').show();
						}, 2000);
						return;
					}
				} catch (e) {
					setUpTeacherApprovalAjax = false;
					$.mbsmessage(e, true, 'alert alert--danger');
					return;
				}
			}
		});

	};

	popupImage = function (input) {
		$.facebox(fcom.getLoader());

		wid = $(window).width();
		if (wid > 767) {
			wid = 500;
		} else {
			wid = 280;
		}

		if (0 >= frmProfileImage.user_profile_image.files.length) {
			return false;
		}

		var defaultform = "#frmProfileImage";
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

	changeProficiency = function (obj, langId) {
		langId = parseInt(langId);
		if (langId <= 0) {
			return;
		}
		let value = obj.value;
		slanguageSection = '.slanguage-' + langId;
		slanguageCheckbox = '.slanguage-checkbox-' + langId;
		if (value == '') {
			$(slanguageSection).find('.badge-js').remove();
			$(slanguageSection).removeClass('is-selected');
			$(slanguageCheckbox).prop('checked', false);
		} else {
			$(slanguageSection).addClass('is-selected');
			$(slanguageCheckbox).prop('checked', true);
			$(slanguageSection).find('.badge-js').remove();
			$(slanguageSection).find('.selection__trigger-label').append('<span class="badge color-secondary badge-js  badge--round badge--small margin-0">' + obj.selectedOptions[0].innerHTML + '</span>');
		}
	};

	intTell = function(){
		var countryData = window.intlTelInputGlobals.getCountryData();
		for (var i = 0; i < countryData.length; i++) {
			var country = countryData[i];
			country.name = country.name.replace(/ *\([^)]*\) */g, "");
		}
	
		var input = document.querySelector("#user_phone");
		$("#user_phone").inputmask();
		input.addEventListener("countrychange", function() {
			var dial_code = $.trim($('.iti__selected-dial-code').text());
			setPhoneNumberMask();
			$('#user_phone_code').val(dial_code);
		});
	
		var telInput = window.intlTelInput(input, {
			separateDialCode: true,
			initialCountry: "us",
			utilsScript: siteConstants.webroot + "js/utils.js",
		});
	}

	sumbmitProfileImage = function () {
		$('.loading-wrapper').show();
		$("#frmProfileImage").ajaxSubmit({
			delegation: true,
			success: function (json) {
				json = $.parseJSON(json);
				$('.loading-wrapper').hide();
				$.mbsmessage(json.msg, true, 'alert alert--success');
				$(document).trigger('close.facebox');
				$('.loading-wrapper').hide();
				// $("[name=user_profile_pic]").prop("files",$("[name=user_profile_image]").prop("files"));
				$('#user-profile-pic--js').show();
				$('#user-profile-pic--js').attr('src', json.file);
			}
		});
	};

	var $image;
	cropImage = function (obj) {
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



})(jQuery);
