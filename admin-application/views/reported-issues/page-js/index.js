$(document).ready(function () {
    search(document.frmSearch);
    $(document).on('click', function () {
        $('.autoSuggest').empty();
    });
    $('input[name=\'teacher\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: fcom.makeUrl('Users', 'autoCompleteJson'),
                data: {keyword: request, fIsAjax: 1},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {label: item['name'] + '(' + item['username'] + ')', value: item['id'], name: item['username']};
                    }));
                },
            });
        },
        'select': function (item) {
            $("input[name='slesson_teacher_id']").val(item['value']);
            $("input[name='teacher']").val(item['name']);
        }
    });
    $('input[name=\'teacher\']').keyup(function () {
        $('input[name=\'slesson_teacher_id\']').val('');
    });
    $('input[name=\'learner\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: fcom.makeUrl('Users', 'autoCompleteJson'),
                data: {keyword: request, fIsAjax: 1},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {label: item['name'] + '(' + item['username'] + ')', value: item['id'], name: item['username']};
                    }));
                },
            });
        },
        'select': function (item) {
            $("input[name='slesson_learner_id']").val(item['value']);
            $("input[name='learner']").val(item['name']);
        }
    });
    $('input[name=\'learner\']').keyup(function () {
        $('input[name=\'slesson_learner_id\']').val('');
    });
    //redirect user to login page
    $(document).on('click', 'ul.linksvertical li a.redirect--js', function (event) {
        event.stopPropagation();
    });
});
(function () {

    var currentPage = 1;
    goToSearchPage = function (page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmUserSearchPaging;
        $(frm.page).val(page);
        search(frm);
    };

    search = function (form, page) {
        if (!page) {
            page = currentPage;
        }
        currentPage = page;
        $("#issueListing").html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('ReportedIssues', 'search'), fcom.frmData(form), function (res) {
            $("#issueListing").html(res);
        });
    };

    clear = function () {
        document.frmSearch.reset();
        if (document.frmSearch.slesson_teacher_id) {
            document.frmSearch.slesson_teacher_id.value = '';
        }
        if (document.frmSearch.slesson_learner_id) {
            document.frmSearch.slesson_learner_id.value = '';
        }
        search(document.frmSearch);
    };

    view = function (issueId) {
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('ReportedIssues', 'view', [issueId]), '', function (t) {
                $.facebox(t, 'faceboxSmall');
            });
        });
    };

    form = function (issrepId) {
        $.mbsmessage(langLbl.processing, true, 'alert alert--process');
        fcom.ajax(fcom.makeUrl('ReportedIssues', 'form', [issrepId]), '', function (response) {
            $.mbsmessage.close();
            $.facebox(response, 'faceboxWidth');
        });
    };

    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('ReportedIssues', 'setup'), fcom.frmData(frm), function (res) {
            $.mbsmessage.close();
            $.facebox.close();
        });
    };

})();