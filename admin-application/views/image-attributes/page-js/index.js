$(document).ready(function () {
    listImageAttributes('7');
});
(function () {
    var currentPage = 1;
    var dv = '#listing';
    listImageAttributes = function (imageAttributeType) {
        imageAttributeType = imageAttributeType || '';

        fcom.ajax(fcom.makeUrl('ImageAttributes', 'listImageAttributes'), { imageAttributeType: imageAttributeType }, function (res) {
            $('#frmBlock').html(res);
            searchImageAttributes(document.frmSearch);
        });
    };
    searchImageAttributes = function (form) {
        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        $(dv).html(fcom.getLoader());

        fcom.ajax(fcom.makeUrl('ImageAttributes', 'search'), data, function (res) {
            $(dv).html(res);
        });
    };

    editImageAttributeForm = function (afile_id, recordid, Type) {
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('ImageAttributes', 'form'), { afile_id: afile_id, recordid: recordid, Type: Type }, function (t) {
            fcom.updateFaceboxContent(t);
        });
    };


    goToSearchPage = function (page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmImgAttrPaging;
        $(frm.page).val(page);
        searchUrls(frm);
    };

    reloadList = function () {
        var frm = document.frmImgAttrPaging;
        searchUrls(frm);
    };

    searchUrls = function (form) {
        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('ImageAttributes', 'search'), data, function (res) {
            $(dv).html(res);
            $("#dvForm").hide();
            $("#dvAlert").show();
        });
    };


    setupImageAttr = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('ImageAttributes', 'setup'), data, function (t) {
            $.facebox.close();
        });
    };

    attributeForm = function (record_id) {
        fcom.ajax(fcom.makeUrl('ImageAttributes', 'attributeForm', [record_id, moduleType]), '', function (t) {
            $("#dvForm").html(t).show();
            $("#dvAlert").hide();
        });
    };

    discardForm = function () {
        $("#dvForm").hide();
        $("#dvAlert").show();
    };

    clearSearch = function () {
        document.frmSearch.reset();
        searchUrls(document.frmSearch);
    };

})();

$(document).on('change', '.language-js', function () {
    var langId = $(this).val();
    var recordId = $('#frmImgAttribute input[name=record_id]').val();
    var module = $('#frmImgAttribute input[name=module_type]').val();
    fcom.ajax(fcom.makeUrl('ImageAttributes', 'attributeForm', [recordId, module, langId]), '', function (t) {
        $("#dvForm").html(t);
        $('#frmImgAttribute input[name=lang_id]').val(langId);
    });
});
