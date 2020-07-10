$(function() {
	var dv = '#listItems';
	var frmFlashCardSrch = document.frmFlashCardSrch;

	viewLessonDetail = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','viewLessonDetail',[lDetailId]),'',function(t){
			$(dv).html(t);
			frmFlashCardSrch = document.frmFlashCardSrch;
			searchFlashCards( frmFlashCardSrch );
		});
	};

	goToFlashCardSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmFlashCardSearchPaging;
		$(frm.page).val(page);
		searchFlashCards(frm);
	};

	scheduleLessonSetup = function(lDetailId,startTime,endTime,date){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'scheduleLessonSetup'), 'lDetailId='+lDetailId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
			$.facebox.close();
			viewLessonDetail();
		});
	};

	/*lessonFeedback = function(lDetailId){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','lessonFeedback',[lDetailId]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	setupLessonFeedback = function(frm){
		if (!$(frm).validate()) return false;
		var data = fcom.frmData(frm);
	 fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setupLessonFeedback'), data , function(t) {
				$.facebox.close();
				viewLessonDetail();
		});
	}*/

	searchFlashCards = function(frm){
		$('#flashCardListing').html(fcom.getLoader());
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','searchFlashCards'),data,function(t){
			$('#flashCardListing').html(t);
		});
	};

	viewFlashCard = function(flashcardId){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','viewFlashCard',[flashcardId]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	flashCardForm = function( lDetailId, flashcardId ){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','flashCardForm'),'flashcardId='+flashcardId + '&lDetailId='+lDetailId,function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	removeFlashcard = function(id){
        $.confirm({
            title: langLbl.Confirm,
            content: langLbl.confirmRemove,
            buttons: {
                Proceed: {
                    text: langLbl.Proceed,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons','removeFlashcard',[id]),'',function(t){
                            searchFlashCards( frmFlashCardSrch );
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

	setupFlashCard = function(frm){
		if (!$(frm).validate()) return false;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setupFlashCard'), data, function(t) {
			searchFlashCards( frmFlashCardSrch );
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

	joinLesson = function(learnerId,teacherId){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'markLearnerJoinTime'), 'lDetailId='+lDetailId , function(t) {
            var ans = $.parseJSON(t);
            if(ans.status){
                joinLessonButtonAction();
                createChatBox();
                // $.mbsmessage( ans.msg,true, 'alert alert--success');
            }
            else{
                checkEveryMinuteStatus();
                $.mbsmessage( ans.msg ,true, 'alert alert--danger');
            }
        });
	};
	endLesson = function (lDetailId) {
        $.confirm({
            title: langLbl.Confirm,
            content: langLbl.endLessonAlert,
            buttons: {
                Proceed: {
                    text: langLbl.Procee,
                    btnClass: 'btn btn--primary',
                    keys: ['enter', 'shift'],
                    action: function(){
                        fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'endLessonSetup'), 'lDetailId='+lDetailId , function(t) {
                                // clearInterval(checkEveryMinuteStatusVar);
                                $('.screen-chat-js').hide();
                                endLessonButtonAction();
                                $.facebox.close();
                                viewLessonDetail();
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
	endLessonSetup = function(lDetailId){
        fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'endLessonSetup'), 'lDetailId='+lDetailId , function(t) {
            clearInterval(checkEveryMinuteStatusVar);
            $('.screen-chat-js').hide();
            endLessonButtonAction();
            $.facebox.close();
            viewLessonDetail();
        });
	};
    
	getListingLessonPlans = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','getListingLessonPlans',[id]),'',function(t){
			viewLessonDetail();
			$.facebox( t,'facebox-medium');
		});
	};

	changeLessonPlan = function(id){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','changeLessonPlan',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	assignLessonPlanToLessons = function(lDetailId,planId){
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'assignLessonPlanToLessons'), 'ltp_slDetailId='+lDetailId+'&ltp_tlpn_id='+planId , function(t) {
				$.facebox.close();
				viewLessonDetail();
		});
	};

	removeAssignedLessonPlan = function(lDetailId){
		if(confirm(langLbl.confirmRemove))
		{
			fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'removeAssignedLessonPlan'), 'ltp_slDetailId='+lDetailId , function(t) {
					$.facebox.close();
					viewLessonDetail();
			});
		}
	};

	viewAssignedLessonPlan = function(lDetailId){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'viewAssignedLessonPlan',[lDetailId]), '', function(t) {
			$.facebox( t,'facebox-medium');
		});
	};

	viewLessonDetail();

});
