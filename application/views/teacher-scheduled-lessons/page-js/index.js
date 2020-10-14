$(function() {
	var dv = '#listItemsLessons';
    
    getLessonsByStatus = function(el, lStatus){
        $('.lessons-list-tabs--js li').removeClass('is-active');
        $(el).closest('li').addClass('is-active');
        $('[name=status]').val(lStatus);
        searchLessons(document.frmSrch);
    };
    
    searchAllStatusLessons = function(frm){
        $('.lessons-list-tabs--js li').removeClass('is-active').first().addClass('is-active');
        frm.status.value='';
        searchLessons(frm);
    };
    
	searchLessons = function(frm){
		$(dv).html(fcom.getLoader());
		$('.calender-js').removeClass('is-active');
		$('.list-js').addClass('is-active');
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','search'),data,function(t){
			$(dv).html(t);
		});
	};

	remove = function(elem,id){
		if(confirm(langLbl.confirmRemove))
		{
			$(elem).closest('tr').remove();
			$(dv).html(fcom.getLoader());
			fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','remove',[id]),'',function(t){
				searchLessons(document.frmSrch);
			});
		}
	};

	viewCalendar = function(frm){
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewCalendar'),data,function(t){
			$(dv).html(t);
		});
	};

	clearSearch = function(){
        $('.lessons-list-tabs--js li').removeClass('is-active').first().addClass('is-active');
		document.frmSrch.reset();
		searchLessons( document.frmSrch );
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmSLnsSearchPaging;
		$(frm.page).val(page);
		searchLessons(frm);
	};

	clearSearch();
});
