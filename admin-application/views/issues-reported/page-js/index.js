$(document).ready(function () {
    searchIssuesReported(document.frmIssuesReportedSearch);
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
    var transactionUserId = 0;
    var active = 1;
    var inActive = 0;
    goToSearchPage = function (page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmUserSearchPaging;
        $(frm.page).val(page);
        searchIssuesReported(frm);
    };
    searchIssuesReported = function (form, page) {
        if (!page) {
            page = currentPage;
        }
        currentPage = page;
        /*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        /*]*/
        $("#userListing").html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('IssuesReported', 'search'), data, function (res) {
            $("#userListing").html(res);
        });
    };
    clearIssueSearch = function () {
        document.frmIssuesReportedSearch.reset();
        if (document.frmIssuesReportedSearch.slesson_teacher_id) {
            document.frmIssuesReportedSearch.slesson_teacher_id.value = '';
        }
        if (document.frmIssuesReportedSearch.slesson_learner_id) {
            document.frmIssuesReportedSearch.slesson_learner_id.value = '';
        }
        searchIssuesReported(document.frmIssuesReportedSearch);
    };
    viewDetail = function (issueId) {
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('IssuesReported', 'viewDetail', [issueId]), '', function (t) {
                $.facebox(t, 'faceboxSmall');
            });
        });
    };
    updateOrderStatus = function (id, value) {
        if (!confirm("Do you really want to update status?")) {
            return;
        }
        if (id === null) {
            $.mbsmessage('Invalid Request!');
            return false;
        }
        fcom.ajax(fcom.makeUrl('IssuesReported', 'updateOrderStatus'), {"order_id": id, "order_is_paid": value}, function (json) {
            res = $.parseJSON(json);
            if (res.status == "1") {
                $.mbsmessage(res.msg, true, 'alert alert--success');
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    };

    actionForm = function (issrepId) {
        $.mbsmessage(langLbl.processing, true, 'alert alert--process');
        fcom.ajax(fcom.makeUrl('IssuesReported', 'actionForm', [issrepId]), '', function (response) {
            $.mbsmessage.close();
            $.facebox(response, 'faceboxWidth');
        });
    };

    setupAction = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('IssuesReported', 'setupAction'), fcom.frmData(frm), function (res) {
            $.mbsmessage.close();
            $.facebox.close();
        });
    };

    transactions = function (lessonId, issueId) {
        fcom.ajax(fcom.makeUrl('IssuesReported', 'transaction', [lessonId, issueId]), '', function (t) {
            $('#facebox').height($(window).height() - 46).css('overflow-y', 'auto');
            fcom.updateFaceboxContent(t);
        });
    };

    addLessonTransaction = function (lessonId, issueId) {
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('IssuesReported', 'addLessonTransaction', [lessonId, issueId]), '', function (t) {
            fcom.updateFaceboxContent(t);
        });
    };

    setupLessonTransaction = function (frm) {
        if (!$(frm).validate())
            return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('IssuesReported', 'setupLessonTransaction'), data, function (t) {
            if (t.userId > 0) {
                getTransactions(t.slessonId, t.issueId);
            }
        });
    };
    updateIssueStatus = function (id, value) {
        if (!confirm("Do you really want to update status?")) {
            return;
        }
        if (id === null) {
            $.mbsmessage('Invalid Request!');
            return false;
        }
        fcom.ajax(fcom.makeUrl('IssuesReported', 'updateStatus'), {"issue_id": id, "issue_status": value}, function (json) {
            res = $.parseJSON(json);
            if (res.status == "1") {
                $.mbsmessage(res.msg, true, 'alert alert--success');
            } else {
                $.mbsmessage(res.msg, true, 'alert alert--danger');
            }
        });
    };
})();