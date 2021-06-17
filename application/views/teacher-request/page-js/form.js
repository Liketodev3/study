(function ($) {

    getform = function (step) {
        fcom.ajax(fcom.makeUrl('TeacherRequest', 'formStep' + step, []), '', function (res) {
            try {
                var response = JSON.parse(res);
                if (response.sttus == 1) {
                    $.mbsmessage(response.msg, true, 'alert alert--success');
                } else {
                    $.mbsmessage(response.msg, true, 'alert alert--danger');
                }
            } catch (e) {
                $.mbsmessage.close();
                $("#main-container").html(res);
            }
        });
    };

    setupStep1 = function (frm, loadNext = false) {
        if (!$(frm).validate()) {
            return;
        }
        var data = new FormData(frm);
        fcom.ajaxMultipart(fcom.makeUrl('TeacherRequest', 'setupStep1'), data, function (response) {
            var res = JSON.parse(response);
            if (res.status == 1) {
                $.mbsmessage(res.msg, true, 'alert alert--success');
                if (loadNext) {
                    getform(res.step);
                }
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    }

    setupStep2 = function (frm, loadNext = false) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.ajax(fcom.makeUrl('TeacherRequest', 'setupStep2', []), fcom.frmData(frm), function (response) {
            var res = JSON.parse(response);
            if (res.status == 1) {
                $.mbsmessage(res.msg, true, 'alert alert--success');
                if (loadNext) {
                    getform(res.step);
                }
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    }

    setupStep3 = function (frm, loadNext = false) {
        fcom.ajax(fcom.makeUrl('TeacherRequest', 'setupStep3', []), fcom.frmData(frm), function (response) {
            var res = JSON.parse(response);
            if (res.status == 1) {
                $.mbsmessage(res.msg, true, 'alert alert--success');
                if (loadNext) {
                    getform(res.step);
                }
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    }

    setupStep4 = function (frm, loadNext = false) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.ajax(fcom.makeUrl('TeacherRequest', 'setupStep4', []), fcom.frmData(frm), function (response) {
            var res = JSON.parse(response);
            if (res.status == 1) {
                $.mbsmessage(res.msg, true, 'alert alert--success');
                if (loadNext) {
                    getform(res.step);
                }
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    }

    validateVideolink = function (field) {
        $(document.frmFormStep2).validate();
        var url = field.value;
        if (!url && url == '') {
            return false;
        }
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var matches = url.match(regExp);
        if (matches && matches[2].length == 11) {
            valideUrl = "https://www.youtube.com/embed/";
            valideUrl += matches[2];
            $(field).val(valideUrl);
            $(document.frmFormStep2).validate();
            return matches[1];
        }
        $(field).val('');
        return false;
    };

    setPhoneNumberMask = function () {
        let placeholder = $("#utrequest_phone_number").attr("placeholder");
        if (placeholder) {
            placeholder = placeholder.replace(/[0-9.]/g, '9');
            $("#utrequest_phone_number").inputmask({
                "mask": placeholder
            });
        }
    };

    teacherQualificationForm = function (uqualification_id) {
        fcom.ajax(fcom.makeUrl('TeacherRequest', 'teacherQualificationForm', []), 'uqualification_id=' + uqualification_id, function (res) {
            $.facebox(res, 'facebox-medium');
        });

    };

    setUpTeacherQualification = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        var data = new FormData(frm);
        fcom.ajaxMultipart(fcom.makeUrl('TeacherRequest', 'setUpTeacherQualification'), data, function (response) {
            var res = JSON.parse(response);
            if (res.status == 1) {
                $.mbsmessage(res.msg, true, 'alert alert--success');
                searchTeacherQualification();
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    };

    searchTeacherQualification = function () {
        $('#qualification-container').html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('TeacherRequest', 'searchTeacherQualification'), '', function (res) {
            $.facebox.close();
            $('#qualification-container').html(res);
        });
    };

    deleteTeacherQualification = function (uqualification_id) {
        if (!confirm(langLbl.confirmRemove)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('TeacherRequest', 'deleteTeacherQualification'), '&uqualification_id=' + uqualification_id, function () {
            searchTeacherQualification();
            $.facebox.close();
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

    intTell = function () {
        var countryData = window.intlTelInputGlobals.getCountryData();
        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            country.name = country.name.replace(/ *\([^)]*\) */g, "");
        }

        var input = document.querySelector("#utrequest_phone_number");
        $("#utrequest_phone_number").inputmask();
        input.addEventListener("countrychange", function () {
            setPhoneNumberMask();
            $('#utrequest_phone_code').val($.trim($('.iti__selected-dial-code').text()));
        });

        var telInput = window.intlTelInput(input, {
            separateDialCode: true,
            initialCountry: "us",
            utilsScript: siteConstants.webroot + "js/utils.js",
        });
        setPhoneNumberMask();
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
