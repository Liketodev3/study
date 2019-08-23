$(document).ready(function(){
	searchLessons(document.frmSrch);
})

var dv = '#listItemsLessons';
function searchLessons (frm){
	$(dv).html(fcom.getLoader());
	var data = fcom.frmData(frm);
	fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','search'),data,function(t){
		$(dv).html(t);
	});
};

function viewCalendar (frm){
	var data = fcom.frmData(frm);
	fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewCalendar'),data,function(t){
		$(dv).html(t);
	});
};