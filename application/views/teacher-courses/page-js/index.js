$(function() {
	var dv = '#listItems';
	searchCourses = function(frm){	
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherCourses','getListing'),data,function(t){
			$(dv).html(t);
		});
	};
	
	add = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherCourses','add',[id]),'',function(t){
			$(dv).html(t);
		}); 
	};
	
	getListingLessonPlans = function(id){	
		fcom.ajax(fcom.makeUrl('TeacherCourses','getListingLessonPlans',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	viewAssignedPlans = function(id){	
		fcom.ajax(fcom.makeUrl('TeacherCourses','viewAssignedPlans',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	assignCoursesToLessonPlan = function(lessonId){
		var str = "course_id="+lessonId;
		$("input[name='selecte_plans[]']:checked").each(function(i,val){
			str += "&selecte_plans[]="+$(val).val();
		});
		var selected_plans = $("input[name='selecte_plans[]']:checked").length;
		var no_of_plans = $('#tcourse_no_of_lessons option:selected').text();
		if(selected_plans != no_of_plans)
		{
			$.mbsmessage("Select Atleast "+no_of_plans+" Plans!",true, 'alert alert--danger');
			return false;
		}
		 fcom.updateWithAjax(fcom.makeUrl('TeacherCourses', 'assignCoursesToLessonPlan'), str , function(t) {		
				$.facebox.close();				
		});	 
	};
	
	
	
	remove = function(id){	
		if(confirm(langLbl.confirmRemove))
		{
			$(dv).html(fcom.getLoader());
			fcom.ajax(fcom.makeUrl('TeacherCourses','remove',[id]),'',function(t){
				searchCourses('');
			}); 
		}
	};
		
	setup = function(frm){
		if (!$(frm).validate()) return false;
		var formData = new FormData(frm); 
		$.ajax({
			url: fcom.makeUrl('TeacherCourses', 'setup'),
			type: 'POST',
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			processData: false,
			success: function (data, textStatus, jqXHR) {
				var data=JSON.parse(data);
				
				if(data.status==0)
				{
					//$.systemMessage(data.msg);
					$.mbsmessage(data.msg,true, 'alert alert--danger');
					return false;
				}
					$.systemMessage(data.msg,false);
					searchCourses('');
					setTimeout(function(){
						$.systemMessage.close();
					},2000);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$.systemMessage(jqXHR.msg, true);
			}
		});
	};
	$("input#resetFormLessonListing").click(function(){
		searchCourses('');
	});
	searchCourses('');
});


