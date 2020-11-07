// comet chat rest api v2
// comet chat js v8
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
        fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'markTeacherJoinTime'), 'lessonId='+lessonId, function(t){});
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
		if((typeof flashCardEnabled !== typeof undefined) && !flashCardEnabled){
            return;
        }
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

	var chat_height = '100%';
	var chat_width = '100%';
	createCometChatBox = function(){
			$("#lessonBox").html('<div id="cometchat_embed_synergy_container" style="width:'+chat_width+';height:'+chat_height+';max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;"></div>');
			var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = '//fast.cometondemand.net/'+chat_appid+'x_xchatx_xcorex_xembedcode.js';
			chat_js.onload = function() {
				var chat_iframe = {};chat_iframe.module="synergy";chat_iframe.style="min-height:"+chat_height+";min-width:"+chat_width+";";chat_iframe.width=chat_width.replace('px','');chat_iframe.height=chat_height.replace('px','');chat_iframe.src='//'+chat_appid+'.cometondemand.net/cometchat_embedded.php'+(is_grpcls=='1' ? '?guid='+chat_group_id : ''); if(typeof(addEmbedIframe)=="function"){addEmbedIframe(chat_iframe);}
			}
			var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
			return true;
		}

	createLessonspaceBox = function(){
		
		fcom.ajax(fcom.makeUrl('Lessonspace','launch',[CometJsonFriendData.lessonId, 1]), '',function(result) {

			if(result.status == 0){
				$.mbsmessage( result.msg , true, 'alert alert--danger');
				return false;
			}else if(result.status == 1) {
				let html = '<div id="cometchat_embed_synergy_container" style="width:'+chat_width+';height:'+chat_height+';max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;">';
				html += '<iframe  style="width:100%;height:100%;" src="'+result.url+'" allow="camera; microphone; display-capture" frameborder="0"></iframe>';
				html += '</div>';
				$("#lessonBox").html(html);
				return true;
			}

		},{fOutMode:'json'});

	};

    createZoomBox = function(data){
        var chat_height = '100%';
		var chat_width = '100%';
        
        var meetingConfig = {mn: data.id, name: data.username, pwd: '', role: data.role, email: data.email, lang: 'en-US', signature: data.signature, leaveUrl:fcom.makeUrl('Zoom', 'leave'), china:0};
        
        if (!meetingConfig.mn || !meetingConfig.name) {
            alert("Meeting number or username is empty");
            return false;
        }
        meetingConfig.apiKey = ZOOM_API_KEY;
        var joinUrl = fcom.makeUrl('Zoom', 'Meeting') + '?'+
        testTool.serialize(meetingConfig);
        
        // testTool.createZoomNode("websdk-iframe", joinUrl);
        let html = '<div style="width:'+chat_width+';height:'+chat_height+';max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;">';
        html += '<iframe style="width:100%;height:100%;" src="'+joinUrl+'" allow="camera; microphone; fullscreen;display-capture" frameborder="0"></iframe>';
        html += '</div>';
        $("#lessonBox").html(html);
    };
    
    joinLessonFromApp = function(CometJsonData,CometJsonFriendData){
        joinLesson(CometJsonData, CometJsonFriendData, 1);
    };
    
    createChatBox = function(data, joinFromApp){
		if(isCometChatMeetingToolActive){
            return createCometChatBox();
        }else if(isLessonSpaceMeetingToolActive){
            return createLessonspaceBox();
        }else if(isZoomMettingToolActive){
            if(typeof(joinFromApp)!='undefined' && joinFromApp==1){
                window.location = data.join_url;
                return;
            }
            return createZoomBox(data);
        }else{
            $.systemMessage('Someting went worngs', 'alert alert--danger');
            return false;				
		}
	};

    createGroup = function(){
		$.ajax({
            method: "POST",
            url: "https://api.cometondemand.net/api/v2/createGroup",
            data: { GUID:chat_group_id,name:chat_group_id,type:4},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('api-key', chat_api_key);
			},
		});
	};


    joinLesson = function(CometJsonData,CometJsonFriendData, joinFromApp){

        if(is_grpcls=='1' && activeMeetingTool != lessonspaceMeetingTool){
            createGroup();
        }
        fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','startLessonAuthentication',[CometJsonFriendData.lessonId]),'',function(t){
            var res = JSON.parse(t);
			if(res.status == 0){
				$.mbsmessage( canStartAlertLabel,true, 'alert alert--danger');
				return false;
			}
			joinLessonButtonAction();
            createChatBox(res.data, joinFromApp)
			markTeacherJoinTime();
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
			});
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
        fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'endLessonSetup'), 'lessonId='+lessonId , function(t) {
            endLessonButtonAction();
            $.facebox.close();
            viewLessonDetail();
        });
	};

	$("input#resetFormLessonListing").click(function(){
		viewLessonDetail();
	});

	viewLessonDetail();
});
