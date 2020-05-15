$(function() {
	var dv = '#listItems';
	var frmFlashCardSrch = document.frmFlashCardSrch;

	viewLessonDetail = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewLessonDetail',[lessonId]),'',function(t){
			$(dv).html(t);
			frmFlashCardSrch = document.frmFlashCardSrch;
			searchFlashCards( frmFlashCardSrch );
		});
	};
	markTeacherJoinTime = function(){
        fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'markTeacherJoinTime'), 'lessonId='+lessonId , function(t) {
        });
    }
	goToFlashCardSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmFlashCardSearchPaging;
		$(frm.page).val(page);
		searchFlashCards(frm);
	};

	searchFlashCards = function(frm){
		$('#flashCardListing').html(fcom.getLoader());
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','searchFlashCards'),data,function(t){
			$('#flashCardListing').html(t);
		});
	};

	flashCardForm = function( lessonId, flashcardId ){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','flashCardForm'),'flashcardId='+flashcardId + '&lessonId='+lessonId,function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	viewFlashCard = function(flashcardId){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewFlashCard',[flashcardId]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	removeFlashcard = function(id){
		if(confirm(langLbl.confirmRemove))
		{
			fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons','removeFlashcard',[id]),'',function(t){
				searchFlashCards(frmFlashCardSrch);
			});
		}
	};

	setupFlashCard = function(frm){
		if (!$(frm).validate()) return false;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'setupFlashCard'), data, function(t) {
			searchFlashCards(frmFlashCardSrch);
			$.facebox.close();
		});
	};

	createChatBox = function(){
		var chat_height = '100%';
		var chat_width = '100%';
		$("#cometChatBox").html('<div id="cometchat_embed_synergy_container" style="width:'+chat_width+';height:'+chat_height+';max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;"></div>');
		var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = '//fast.cometondemand.net/'+chat_appid+'x_xchatx_xcorex_xembedcode.js';
		chat_js.onload = function() {
		var chat_iframe = {};chat_iframe.module="synergy";chat_iframe.style="min-height:"+chat_height+";min-width:"+chat_width+";";chat_iframe.width=chat_width.replace('px','');chat_iframe.height=chat_height.replace('px','');chat_iframe.src='//'+chat_appid+'.cometondemand.net/cometchat_embedded.php?guid='+chat_group_id; if(typeof(addEmbedIframe)=="function"){addEmbedIframe(chat_iframe);}
		}
		var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
	};

	createGroup = function(learnerId,teacherId){
		$.ajax({
		  method: "POST",
		 // url: "https://api.cometondemand.net/api/v2/addFriends",
		 // data: { UID:learnerId,friendsUID:teacherId},
		  url: "https://api.cometondemand.net/api/v2/createGroup",
		  data: { GUID:chat_group_id,name:chat_group_id,type:4},
		  beforeSend: function (xhr) {
			xhr.setRequestHeader('api-key', chat_api_key);
			},
		})
		.done(function( msg ) {
			  if(typeof(msg.success) != "undefined" && msg.success !== null)
			  {
				  $.mbsmessage( msg.success.message,true, 'alert alert--success');
				  //createChatBox();
				  //sessionStorage.setItem('cometChatUserExists',learnerId);
			  }
			  else{
				 // $.mbsmessage( msg.failed.message,true, 'alert alert--danger');
			  }
			});
		addCometUsersToGroup(chat_group_id,learnerId,teacherId);
	};

	addCometUsersToGroup = function(chat_group_id,learnerId,teacherId){
		$.ajax({
		  method: "POST",
		  url: "https://api.cometondemand.net/api/v2/addUsersToGroup",
		  data: { GUID:chat_group_id,UIDs:learnerId+','+teacherId},
		  beforeSend: function (xhr) {
			xhr.setRequestHeader('api-key', chat_api_key);
			},
		})
		.done(function( msg ) {
			  if(typeof(msg.success) != "undefined" && msg.success !== null)
			  {
					$.mbsmessage( msg.success.message,true, 'alert alert--success');
					createChatBox();
					sessionStorage.setItem('cometChatUserExists',chat_group_id);
                    markTeacherJoinTime();
			  }
			  else{
					createChatBox();
					sessionStorage.setItem('cometChatUserExists',chat_group_id);
                    markTeacherJoinTime();
					//$.mbsmessage( msg.failed.message,true, 'alert alert--danger');
			  }
			});
	};


	createUserCometChatApi = function(CometJsonData,CometJsonFriendData){
		$(CometJsonData).each(function(i,val){
			$.ajax({
			  method: "POST",
			  url: "https://api.cometondemand.net/api/v2/createUser",
			  data: { UID:val.userId,name:val.fname,avatarURL:val.avatarURL,profileURL:val.profileURL,role:val.role },
			  beforeSend: function (xhr) {
				xhr.setRequestHeader('api-key', chat_api_key);
				},
			})
			.done(function( msg ) {
				  if(typeof(msg.success) != "undefined" && msg.success !== null)
				  {
					  $.mbsmessage( msg.success.message,true, 'alert alert--success');
					  //createGroup(CometJsonFriendData.userId,CometJsonFriendData.friendId);
				  }
				  else{
					  //$.mbsmessage( msg.failed.message,true, 'alert alert--danger');
					  //createGroup(CometJsonFriendData.userId,CometJsonFriendData.friendId);
				  }
				});
			});
			createGroup(CometJsonFriendData.userId,CometJsonFriendData.friendId);
	};

	createUserCometChat = function(CometJsonData,CometJsonFriendData){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','startLessonAuthentication',[CometJsonFriendData.lessonId]),'',function(t){
			if(t == 0){
				$.mbsmessage( "Cannot Start The lesson Now!",true, 'alert alert--danger');
				return false;
			}
			joinLessonButtonAction();
			createUserCometChatApi(CometJsonData,CometJsonFriendData);
		});
	};

	endLesson = function (lessonId) {

                $.confirm({
                    title: langLbl.Confirm,
                    content: langLbl.endLessonAlert,
                    buttons: {
                        Charge: {
                            text: langLbl.chargelearner,
                            btnClass: 'btn btn--primary',
                            keys: ['enter', 'shift'],
                            action: function(){
                                fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'endLessonSetup'), 'lessonId='+lessonId , function(t) {
                                    endLessonButtonAction();
                                    $.facebox.close();
                                    viewLessonDetail();
                                });
                            }
                        },
                         Reschedule: {
                            text: langLbl.Reschedule,
                            btnClass: 'btn btn--primary',
                            keys: ['enter', 'shift'],
                            action: function(){
                                requestReschedule(lessonId);
                                /*fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'endLessonSetup'), 'lessonId='+lessonId , function(t) {
                                    endLessonButtonAction();
                                    $.facebox.close();
                                    viewLessonDetail();
                                });*/
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

	requestReschedule = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','requestReschedule',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	requestRescheduleSetup = function(frm){
	if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'requestRescheduleSetup'), data , function(t) {
			$.facebox.close();
            //endLessonButtonAction();
			location.reload();
		});
	};

	endLessonSetup = function(lessonId){
		//if(confirm(langLbl.confirmRemove)){
			fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'endLessonSetup'), 'lessonId='+lessonId , function(t) {
				endLessonButtonAction();
				$.facebox.close();
				viewLessonDetail();
			});
		//}
	};

	/*viewBookingCalendar = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewBookingCalendar',[id]),'',function(t){
			viewLesson('');
			$.facebox( t,'facebox-medium');
		});
	};*/

	/*cancelLesson = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','cancelLesson',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	cancelLessonSetup = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'cancelLessonSetup'), data , function(t) {
			$.facebox.close();
			viewLessonDetail();
		});
	};*/

	/*issueReported = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','issueReported',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	issueReportedSetup = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'issueReportedSetup'), data , function(t) {
			$.facebox.close();
			viewLessonDetail();
		});
	};*/

	/*requestReschedule = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','requestReschedule',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	requestRescheduleSetup = function(frm){
	if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'requestRescheduleSetup'), data , function(t) {
			$.facebox.close();
			viewLessonDetail();
		});
	};*/

	/*listLessonPlans = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','listLessonPlans',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	changeLessonPlan = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','changeLessonPlan',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	assignLessonPlanToLessons = function( lessonId, planId ){
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'assignLessonPlanToLessons'), 'ltp_slessonid='+lessonId+'&ltp_tlpn_id='+planId , function(t) {
			$.facebox.close();
			viewLessonDetail();
		});
	};

	removeAssignedLessonPlan = function(lessonId){
		if(confirm(langLbl.confirmRemove)){
			fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'removeAssignedLessonPlan'), 'ltp_slessonid='+lessonId , function(t) {
				$.facebox.close();
				viewLessonDetail();
			});
		}
	};

	viewAssignedLessonPlan = function(lessonId){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'viewAssignedLessonPlan',[lessonId]), '', function(t) {
			$.facebox( t,'facebox-medium');
		});
	};

	scheduleLessonSetup = function(lessonId,startTime,endTime,date){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'scheduleLessonSetup'), 'lessonId='+lessonId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
			$.facebox.close();
			viewLessonDetail();
		});
	}*/

	$("input#resetFormLessonListing").click(function(){
		viewLessonDetail();
	});

	viewLessonDetail();
});
