$(function () {
    var dv = '#listItems';
    searchGroupClasses = function (frm) {
        $('.search-filter').hide();
        var data = fcom.frmData(frm);
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('TeacherGroupClasses', 'search'), data, function (t) {
            $(dv).html(t);
        });
    };

    form = function (id) {
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('TeacherGroupClasses', 'form', [id]), '', function (t) {
            $(dv).html(t);
            jQuery('#grpcls_start_datetime,#grpcls_end_datetime').each(function () {
                $(this).datetimepicker({
                    format: 'Y-m-d H:i',
                    step: 15
                });
            });

        });
    };

    removeClass = function (elem, id) {
        if (confirm(langLbl.confirmRemove)) {
            $(elem).closest('tr').remove();
            $(dv).html(fcom.getLoader());
            fcom.ajax(fcom.makeUrl('TeacherGroupClasses', 'removeClass', [id]), '', function (t) {
                searchGroupClasses(document.frmSrch);
            });
        }
    };

    cancelClass = function (id) {
        if (confirm(langLbl.confirmCancel)) {
            $(dv).html(fcom.getLoader());
            fcom.ajax(fcom.makeUrl('TeacherGroupClasses', 'cancelClass', [id]), '', function (t) {
                searchGroupClasses(document.frmSrch);
            });
        }
    };

    setup = function (frm) {
        if (!$(frm).validate()) return false;
        var formData = new FormData(frm);
        $.ajax({
            url: fcom.makeUrl('TeacherGroupClasses', 'setup'),
            type: 'POST',
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            processData: false,
            success: function (data, textStatus, jqXHR) {
                var data = JSON.parse(data);
                if (data.status == 0) {
                    $.mbsmessage(data.msg, true, 'alert alert--danger');
                    return false;
                } else {
                    $.mbsmessage(data.msg, true, 'alert alert--success');
                    if (data.lang_id > 0) {
                        console.log(data.lang_id);
                        editGroupClassLangForm(data.grpcls_id, data.lang_id);
                    }
                }
                setTimeout(function () {
                    $.systemMessage.close();
                }, 2000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $.systemMessage(jqXHR.msg, true);
            }
        });
    };
    clearSearch = function () {
        document.frmSrch.reset();
        searchGroupClasses(document.frmSrch);
    };
    goToSearchPage = function (page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmSearchPaging;
        $(frm.page).val(page);
        searchGroupClasses(frm);
    };
    editGroupClassLangForm = function (groupClassId, langId) {
        fcom.ajax(fcom.makeUrl('TeacherGroupClasses', 'langForm', [groupClassId, langId]), '', function (t) {
            $(dv).html(t);

        });
    };
    setupGroupClassLang = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('TeacherGroupClasses', 'langSetup'), data, function (t) {
            if (t.langId > 0) {
                editGroupClassLangForm(t.grpclsId, t.langId);
                return;
            }
            $(document).trigger('close.facebox');
            searchGroupClasses(document.frmSrch);
        });
    }
    searchGroupClasses(document.frmSrch);
});