$(document).ready(function () {
    var frm = document.frmMessageSrch;
    threadListing(frm);
    $(".window__search-field-js").click(function () {
        $(".window__search-form-js").toggle();
    });
    if ($(window).width() > 1199) {
        $('.scrollbar-js').enscroll({
            verticalTrackClass: 'scrollbar-track',
            verticalHandleClass: 'scrollbar-handle'
        });
    }
    if (sessionStorage.getItem('threadId') != null) {
        getThread(sessionStorage.getItem('threadId'));
        sessionStorage.removeItem('threadId');
    }

    $(".msg-list__action-js").click(function () {
        $(this).parent().toggleClass("is-active");
        $(".message-details-js").show();
        $("html").addClass("show-message-details"); return false;
    });

    $(".window__search-field-js").click(function () {
        $(".window__search-form-js").toggle();
    });
  
});

var div = '#threadListing';
function threadListing(frm, id) {
    var data = fcom.frmData(frm);
    data = data + "&isactive=" + id;
    fcom.ajax(fcom.makeUrl('Messages', 'search'), data, function (res) {
        $(div).html(res);
    });
    $(".window__search-form-js").hide();
    updateHeaderNotifCount();
}
var msgCntDiv = ".unrdMsgCnt";
function updateHeaderNotifCount() {
    fcom.ajax(fcom.makeUrl('Messages', 'getUnreadCount'), '', function (data) {
        var data = JSON.parse(data);
        $(msgCntDiv).html(data.html);
    });
}

clearSearch = function () {
    document.frmMessageSrch.reset();
    threadListing(document.frmMessageSrch);
};
$(".select-box__value-js").click(function () {
    $(".select-box__target-js").slideToggle();
});

/* FUNCTION FOR SCROLLBAR */
if ($(window).width() > 1199) {
    $('.scrollbar-js').enscroll({
        verticalTrackClass: 'scrollbar-track',
        verticalHandleClass: 'scrollbar-handle'
    });
}

function closethread() {
    $("body .message-details-js").hide();
    $("html").removeClass("show-message-details"); 
}
function getThread(id) {
    dv = ".message-details-js";
    data = "thread_id=" + id;
    fcom.ajax(fcom.makeUrl('Messages', 'messageSearch'), data, function (ans) {
        var data = JSON.parse(ans);
        $(dv).html(data.html).show();
        $("html").addClass("show-message-details"); 
        $( ".chat-room__body" ).scrollTop($( ".chat-room__body" )[0].scrollHeight);
    });
    $('html').addClass('show-message-details');
    threadListing(document.frmMessageSrch, id);
}
function goToLoadPrevious() {

}

function sendMessage(frm) {
    if (!$(frm).validate()) return;
    var data = fcom.frmData(frm);
    var dv = ".message-details-js";
    $(dv).html(fcom.getLoader());
    fcom.ajax(fcom.makeUrl('Messages', 'sendMessage'), data, function (t) {
        var data = JSON.parse(t);
        getThread(data.threadId);
        threadListing(document.frmMessageSrch);
    });
}