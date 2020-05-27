<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<?php $teacherBookingBefore = (!empty($teacherBookingBefore)) ? $teacherBookingBefore : 0;
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
?>
<script>
	var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
	var timeInterval;
	var seconds = 2;
	var checkSlotAvailabiltAjaxRun = false;
	clearInterval(timeInterval);
	timeInterval = setInterval(currentTimer, 1000);
	function currentTimer() {
		$('body').find(".fc-left h6 span.timer").html(moment('<?php echo $nowDate; ?>').add(seconds,'seconds').format('hh:mm A'));
		seconds++;
	}
	function getEventsByTime( start, stop ) {
	   var json = JSON.stringify($("#d_calendar").fullCalendar("clientEvents").map(function(e) {
			return 	{
			start: moment(e.start).format('YYYY-MM-DD HH:mm:ss'),
			end: moment(e.end).format('YYYY-MM-DD HH:mm:ss'),
			day: moment(e.start).format('d'),
			_id: e._id,
			action: e.action,
			classtype: e.classType,
		};
		}));
	   return json;
	}

	$(document).ready(function() {
		$(document).bind('close.facebox', function() {
			$('.tooltipevent').remove();
		});
		$('#d_calendar').fullCalendar({
			header: {
				left: 'title',
				center: '',
				right: 'prev,next today'
			  },
			defaultView: 'agendaWeek',
			selectable: true,
			<?php if (strtolower($layoutDirection ) == 'rtl') { ?>
			rtl : true,
			isRTL : true,
			<?php } ?>
			editable: false,
			nowIndicator:true,
            now:'<?php echo $nowDate; ?>',
			eventOverlap: false,
			slotEventOverlap : false,
			defaultTimedEventDuration : "01:00:00",
			snapDuration : "<?php echo ($action=="free_trial") ? '0:30:00' : '01:00:00' ?>",
			allDaySlot: false,
			timezone: "<?php echo $user_timezone; ?>",
			select: function (start, end, jsEvent, view ) {
				if(checkSlotAvailabiltAjaxRun) {
					return false;
				}
				checkSlotAvailabiltAjaxRun = true;
				// $("body").css( {"pointer-events": "none"} );
				// $("body").css( {"cursor": "wait"} );
				//
				//==================================//
					var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
					//var duration = '<?php //echo $teacherBookingBefore;?>';
					var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore;?>' ,'hours').format('YYYY-MM-DD HH:mm:ss');

					if( selectedDateTime >=  moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm:ss') ) {
						if ( selectedDateTime < validSelectDateTime ) {
							$("body").css( {"cursor": "default"} );
							$("body").css( {"pointer-events": "initial"} );

							if( selectedDateTime > moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm:ss') ) {

								$.systemMessage('<?php echo Label::getLabel('LBL_Teacher_Disable_the_Booking_before') .' '. $teacherBookingBefore .' Hours.' ; ?>','alert alert--success');
									setTimeout(function() {
										$.systemMessage.close();
									}, 3000);

							}

							$('#calendar').fullCalendar('unselect');
							checkSlotAvailabiltAjaxRun =  false;
							return false;
						}
					}
				//================================//

				if(getEventsByTime( start, end ).length > 1)
				{
					//alert(1);
					$('#d_calendar').fullCalendar('refetchEvents');
				}
				if(moment('<?php echo $nowDate; ?>').diff(moment(start)) >= 0) {
					//alert(2);
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					$('#d_calendar').fullCalendar('unselect');
					checkSlotAvailabiltAjaxRun =  false;
					return false;
				}
				if(moment(start).format('YYYY-MM-DD HH:mm:ss') > moment(end).format('YYYY-MM-DD HH:mm:ss') ) {
					//alert(3);
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					$('#d_calendar').fullCalendar('unselect');
					checkSlotAvailabiltAjaxRun =  false;
					return false;
				}

				var duration = moment.duration(moment(end).diff(moment(start)));
				var minutesDiff = duration.asMinutes();
				var minutes = "<?php echo ($action=="free_trial") ? 30 : 60 ?>";
				if(minutesDiff > minutes)
				{
					//alert(4);
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					$('#d_calendar').fullCalendar('unselect');
					$('.tooltipevent').remove();
					checkSlotAvailabiltAjaxRun =  false;
					return false;

				}

				var newEvent = new Object();
				newEvent.title = '';
				newEvent.startTime = moment(start).format('HH:mm:ss');
				newEvent.endTime = moment(end).format('HH:mm:ss');
				newEvent.start = moment(start).format('YYYY-MM-DD HH:mm:ss');
				newEvent.end = moment(end).format('YYYY-MM-DD HH:mm:ss');
				newEvent.date = moment(start).format('YYYY-MM-DD');
				newEvent.day = moment(start).format('d');
				newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
				newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>';
				newEvent.lessonId = '<?php echo $lessonId; ?>';
				newEvent.allday = 'false';
				newEvent.maxTime = "01:00:00";
				newEvent.eventOverlap = false;

				var currentDate = $('#calendar').fullCalendar('getDate');
				var beginOfWeek = moment(start).startOf('week').format('YYYY-MM-DD HH:mm:ss');
				var endOfWeek = moment(start).endOf('week').format('YYYY-MM-DD HH:mm:ss');

				newEvent.weekStart = moment(beginOfWeek).format('YYYY-MM-DD HH:mm:ss');
				newEvent.weekEnd = moment(endOfWeek).format('YYYY-MM-DD HH:mm:ss');

				fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability',[<?php echo $teacher_id; ?>]), newEvent, function(doc) {
					checkSlotAvailabiltAjaxRun = false;
                   $("body").css( {"cursor": "default"} );
				   $("body").css( {"pointer-events": "initial"} );
				   var res = JSON.parse(doc);
					if ( res.msg == 1 ) {
						$('#d_calendar').fullCalendar('renderEvent',newEvent);
					}
					if ( res.msg == 0 ) {
						$('.tooltipevent').remove();
					}
				});

			},
			eventLimit: true,
			defaultDate: '<?php echo date('Y-m-d'); ?>',
			events: function(start, end, timezone, callback) {
				var data = {start:moment(start).format('YYYY-MM-DD HH:mm:ss'),end:moment(end).format('YYYY-MM-DD HH:mm:ss')};
				fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData',[<?php echo $teacher_id; ?>]), data, function(doc) {
					if(doc == "[]")
					{
						data = { WeekStart:moment(start).format('YYYY-MM-DD'), WeekEnd:moment(end).format('YYYY-MM-DD') };
						fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData',[<?php echo $teacher_id; ?>]), data, function(doc) {
						var doc = JSON.parse(doc);
						var events = [];
						events.push({
							title: '',
							start: moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
							end: moment('<?php echo $nowDate; ?>'),
							className: 'past_current_day testPast',
							editable: false,
							rendering:'background'
							});
							var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore; ?>' ,'hours');
							$(doc).each(function(i,e) {
							if(  validSelectDateTime > moment(e.end) ) {
								return;
							}
							if( validSelectDateTime.format('YYYY-MM-DD HH:mm:ss') > moment(e.start).format('YYYY-MM-DD HH:mm:ss') ) {
								e.start =  validSelectDateTime.format('YYYY-MM-DD HH:mm:ss');
							}
						var classType = $(this).attr('classType');
						if(classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>"){
							var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
						}else if(classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>"){
							var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::UNAVAILABLE]; ?>';
						}
						events.push({
							title: $(this).attr('title'),
							start: $(this).attr('start'),
							end: $(this).attr('end'),
							color: $(this).attr('color'),
							day: $(this).attr('day'),
							_id: $(this).attr('_id'),
							action: 'fromGeneralAvailability',
							classType: $(this).attr('classType'),
							className: className,
							editable: false,
							rendering:'background',
							selectable: true,
							dow:[$(this).attr('day')]
						  });
						});
						fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[<?php echo $teacher_id; ?>]), '', function(doc2) {
							var doc2 = JSON.parse(doc2);
							$(doc2).each(function(i,e) {
								events.push({
									title: $(this).attr('title'),
									start: $(this).attr('start'),
									end: $(this).attr('end'),
									className: $(this).attr('className'),
									color: "var(--color-secondary)",

							  });
							});
							callback(events);
						});
					});
					}
					else{
					var doc = JSON.parse(doc);
					var events = [];
					events.push({
							title: '',
							start: moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
							end: moment('<?php echo $nowDate; ?>'),
							className: 'past_current_day testPast',
							editable: false,
							rendering:'background'
							});
				var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore; ?>' ,'hours');
					$(doc).each(function(i,e) {
						if(  validSelectDateTime > moment(e.end) ) {
							return;
						}

						if( validSelectDateTime.format('YYYY-MM-DD HH:mm:ss') > moment(e.start).format('YYYY-MM-DD HH:mm:ss') ) {
							e.start =  validSelectDateTime.format('YYYY-MM-DD HH:mm:ss');
						}
						var classType = $(this).attr('classType');
						if(classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>"){
							var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
							 if(moment('<?php echo $nowDate; ?>') > moment(e.start)) {
								var editable = false;
							}
							else{
								var editable = true;
							}
						}else if(classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>"){
							var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::UNAVAILABLE]; ?>';
							 var editable = false;
						}
					  events.push({
						title: $(this).attr('title'),
						start: $(this).attr('start'),
						end: $(this).attr('end'),
						color: $(this).attr('color'),
						day: $(this).attr('day'),
						_id: $(this).attr('_id'),
						action: 'fromWeeklySchedule',
						classType: $(this).attr('classType'),
						className: className,
						rendering:'background',
						editable: editable,
						selectable: editable,
					  });
					});
					fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[<?php echo $teacher_id; ?>]), '', function(doc2) {
							var doc2 = JSON.parse(doc2);
							$(doc2).each(function(i,e) {
								events.push({
									title: $(this).attr('title'),
									start: $(this).attr('start'),
									end: $(this).attr('end'),
									className: $(this).attr('className'),
									color: "var(--color-secondary)",

							  });
							});
							callback(events);
						});
					}
				});
			  },
			eventRender: function(event, element) {
				if(isNaN(event._id) && event.className != "sch_data"){
					element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
				}
				else{
					var eventData=JSON.stringify({"start" :moment( event.start).format('YYYY-MM-DD HH:mm:ss'),"end" : moment(event.end).format('YYYY-MM-DD HH:mm:ss'),"_id":event._id,"classType":event.classType,"day":event.day});
					if(event.classType != 0  && event.className != "sch_data"){
						element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherWeeklySchedule("+eventData+");'>X</span>" );
					}
				}
			element.find(".closeon").click(function() {
				if(isNaN(event._id)){
					$('#d_calendar').fullCalendar('removeEvents',event._id);
					$('.tooltipevent').remove();
				}
			});
			var eventEnd = moment(event.end);
			var NOW = moment();
			if(moment(event.end).format('YYYY-MM-DD HH:mm') < moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm') && event.className != "sch_data"){
				return false;
			}
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			console.log(event.start.isBefore(moment('<?php echo $nowDate; ?>')));
			if(moment('<?php echo $nowDate; ?>').diff(moment(event.start)) >= 0) {
			 $("#d_calendar").fullCalendar("refetchEvents");
			 return false;
			}
		},

		eventMouseout: function(calEvent, jsEvent) {
			$(this).css('z-index', 8);
		},
		eventResize: function(event, delta, revertFunc) {
			return false;

		},

	eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
		console.log(event.start.isBefore(moment('<?php echo $nowDate; ?>')));
		if(moment('<?php echo $nowDate; ?>').diff(moment(event.start)) >= 0) {
		 $("#d_calendar").fullCalendar("refetchEvents");
		 return false;
		}

	},
	eventClick: function(calEvent, jsEvent) {

		var newEvent = new Object();
		newEvent.title = '';
		newEvent.startTime = moment(calEvent.start).format('HH:mm:ss');
		newEvent.endTime = moment(calEvent.end).format('HH:mm:ss');
		newEvent.start =moment(calEvent.end).format('YYYY-MM-DD')+" "+ moment(calEvent.start).format('HH:mm:ss');
		newEvent.end = moment(calEvent.end).format('YYYY-MM-DD')+" "+moment(calEvent.end).format('HH:mm:ss');
		newEvent.date = moment(calEvent.end).format('YYYY-MM-DD');
		newEvent.day = moment(calEvent.start).format('d');
		newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
		newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>';
		newEvent.lessonId = '<?php echo $lessonId; ?>';


		var monthName = moment(calEvent.start).format('MMMM');
		var date = monthName+" "+moment(calEvent.start).format('DD, YYYY');
		var start = moment(calEvent.start).format('HH:mm A');
		var end = moment(calEvent.end).format('HH:mm A');
		var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss');
		var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss');
		//var tooltip = '<div class="tooltipevent" style="width:170px;height:70px;background:#ccc;position:absolute;z-index:10001;"><span onclick="$(&apos;.tooltipevent&apos;).remove();" >X</span>Date: '+date+'  <br />Time: '+start+'-'+end+'<a onClick="setUpLessonSchedule(&quot;<?php echo $lessonId; ?>&quot;, &quot;'+ newEvent.startTime +'&quot;, &quot;'+ newEvent.endTime +'&quot;, &quot;'+ newEvent.date +'&quot; );" style="margin-left:35px;" id="btn" class="btn btn--secondary">Confirm It!</a></div>';
            var tooltip = '<div class="tooltipevent" style="position:absolute;z-index:10001;"><div class="booking-view"><h3 class="-display-inline"><?php echo $userRow['user_first_name']; ?></h3><span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($userRow['user_country_id'], 'DEFAULT') ); ?>" alt=""></span><div class="inline-list"><span class="inline-list__value highlight"><strong>Date</strong> &nbsp; &nbsp; '+date+' at '+start+'-'+end+'</span></div></div><div class="-align-center"><a href="javascript:void(0)" onClick="setUpLessonSchedule(&quot;<?php echo $teacher_id; ?>&quot;, &quot;<?php echo $lessonId; ?>&quot;, &quot;'+ newEvent.startTime +'&quot;, &quot;'+ newEvent.endTime +'&quot;, &quot;'+ newEvent.date +'&quot; );" class="btn btn--secondary btn--small btn--wide"><?php echo Label::getLabel('LBL_Confirm_It!'); ?></a></div><a onclick="$(&apos;.tooltipevent&apos;).remove();" href="javascript:void();" class="-link-close"></a></div>';
		if(calEvent.className != "sch_data"){
			$("body").append(tooltip);
			$('.tooltipevent').css('top', jsEvent.pageY - 110);
				$('.tooltipevent').css('left', jsEvent.pageX - 100);
			$(this).mouseover(function(e) {
				$(this).css('z-index', 10000);
				$('.tooltipevent').fadeIn('500');
				$('.tooltipevent').fadeTo('10', 1.9);
			});
		}
	}
	});
	$(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
			$('.tooltipevent').remove();
		});
		$('body').find(".fc-left").html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'></span></h6>");
	});

</script>
<div class="calendar-view">
<div class="row">
<div class="col-sm-6">
	<h4><?php echo $userRow['user_full_name']." ".Label::getLabel('Lbl_Calendar'); ?></h4>
</div>
<div class="col-sm-6">
	<div class="col-sm-6">
		<div class="cal-status">
			<span class="box-hint available-box">&nbsp;</span>
			<p><?php echo Label::getLabel('Lbl_Available'); ?></p>
		</div>
		<div class="cal-status">
			<span class="box-hint booked-box">&nbsp;</span>
			<p><?php echo Label::getLabel('Lbl_Booked'); ?></p>
		</div>
	</div>
</div>
</div>
<span> <?php echo MyDate::displayTimezoneString();?> </span>
<div id='d_calendar'></div>
</div>

<style>
.unavailable {
	background :#000;
}
</style>
