$(function() {
	var dv = '#listItemsLessons';
	var isSetupAjaxrun = false;
	searchLessons = function(frm){
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','getListing'),data,function(t){
			$(dv).html(t);
		});
	};


	add = function(id){
		$.loader.show();
		fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','add',[id]),'',function(t){
			$.loader.hide();
			$.facebox( t,'facebox-medium');
		});
	};

	removeLesson = function(id){

        $.confirm({
            title: langLbl.Confirm,
            content: langLbl.confirmDeleteLessonPlanText,
            buttons: {
                Proceed: {
                    text: langLbl.Proceed,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('TeacherLessonsPlan', 'removeLessonSetup'), 'lessonPlanId='+id , function(t) {
                                $.facebox.close();
                                searchLessons(document.lessonPlanSerach);
                        });
                    }
                },
                Quit: {
                    text: langLbl.Quit,
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }
            }
        });
	};

	removeFile = function(celement,id){
        $.confirm({
            title: langLbl.Confirm,
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: langLbl.Proceed,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','removeFile',[id]),'',function(t){
                            $(celement).parent().remove();
                        });
                    }
                },
                Quit: {
                    text: langLbl.Quit,
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }
            }
        });
	};

	setup = function(frm){
		if (!$(frm).validate()) return false;
		$('body').find('.facebox-medium,.close').hide();
		if(isSetupAjaxrun) {return true;}
		isSetupAjaxrun = true;
		$.loader.show();
		var formData = new FormData(frm);
		$.ajax({
			url: fcom.makeUrl('TeacherLessonsPlan', 'setup'),
			type: 'POST',
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			processData: false,
			async:false,
			success: function (data, textStatus, jqXHR) {
				$.loader.hide();
					isSetupAjaxrun = false;
					var data=JSON.parse(data);
				if(data.status==0)
				{
					$('body').find('.-padding-20').remove();
					$('body').find('.facebox-medium,.close').show();
					$.mbsmessage(data.msg,true,'alert alert--danger');
					return false;
				}
				$.facebox.close();
				$.mbsmessage(data.msg,true,'alert alert--success');
				searchLessons('');
				setTimeout(function(){
					$.mbsmessage.close();
				},2000);

			},
			error: function (jqXHR, textStatus, errorThrown) {
				isSetupAjaxrun = false;
				$('body').find('.-padding-20').remove();
				$.loader.hide();
				$.mbsmessage(jqXHR.msg, true,'alert alert--danger');
			}
		},{});
	};

	clearSearch = function () {
        document.lessonPlanSerach.reset();
        searchLessons(document.lessonPlanSerach);
    };
	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var form = document.lessonPlanPaginationForm;
		$(form.page).val(page);
		searchLessons(form);
	};
	searchLessons(document.lessonPlanSerach);

});
