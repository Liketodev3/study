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
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','add',[id]),'',function(t){
			searchLessons('');
			$.facebox( t,'facebox-medium');
		});
	};

	remove = function(id){

        $.confirm({
            title: 'Confirm!',
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: 'Proceed',
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    $(dv).html(fcom.getLoader());
                    fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','remove',[id]),'',function(t){
                    searchLessons('');
                    });
                    }
                },
                Quit: {
                    text: 'Quit',
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }
            }
        });
	};

	removeLesson = function(id){

        $.confirm({
            title: 'Confirm!',
            content: 'Are You Sure! By Removing This Lesson Will Also Unlink It From Courses And Scheduled Lessons!',
            buttons: {
                Proceed: {
                    text: 'Proceed',
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('TeacherLessonsPlan', 'removeLessonSetup'), 'lessonPlanId='+id , function(t) {
                                $.facebox.close();
                                searchLessons('');
                        });
                    }
                },
                Quit: {
                    text: 'Quit',
                    btnClass: 'btn btn--secondary',
                    keys: ['enter', 'shift'],
                    action: function(){
                    }
                }
            }
        });
	};

	removeLessonSetup = function(lessonPlanId){
		fcom.updateWithAjax(fcom.makeUrl('TeacherLessonsPlan', 'removeLessonSetup'), 'lessonPlanId='+lessonPlanId , function(t) {
				$.facebox.close();
				searchLessons('');
		});
	};

	removeFile = function(celement,id){

        $.confirm({
            title: 'Confirm!',
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: 'Proceed',
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','removeFile',[id]),'',function(t){
                            $(celement).parent().remove();
                        });
                    }
                },
                Quit: {
                    text: 'Quit',
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
		$('body').find("#facebox_overlay").html(fcom.getLoader());
		if(isSetupAjaxrun) {return true;}
		isSetupAjaxrun = true;
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
				$('body').find('.facebox-medium,.close').show();
				$.mbsmessage(jqXHR.msg, true,'alert alert--danger');
			}
		},{});
	};

	requestReschedule = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','requestReschedule',[id]),'',function(t){
			searchLessons('');
			$.facebox( t,'facebox-medium');
		});
	};

	requestRescheduleSetup = function(frm){
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherLessonsPlan', 'requestRescheduleSetup'), data , function(t) {
				$.facebox.close();
				searchLessons('');
		});
	};

	$("input#resetFormLessonListing").click(function(){
		searchLessons('');
	});
	searchLessons('');
});
