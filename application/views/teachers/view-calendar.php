<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<?php
$layoutDirection = CommonHelper::getLayoutDirection();
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$bookingSnapDuration = "00:30";
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
?>
<script type="text/javascript">
	var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
	var checkSlotAvailabiltAjaxRun =  false;
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
	var timeInterval;
	var seconds = 2;
	clearInterval(timeInterval);
	timeInterval = setInterval(currentTimer, 1000);
	function currentTimer() {
		  $('body').find(".fc-left h6 span.timer").html(moment('<?php echo $nowDate; ?>').add(seconds,'seconds').format('hh:mm A'));
		  seconds++;
	}
	// getTeacherGeneralAvailability start
	function getTeacherGeneralAvailability(start,end,successCallback) {
		var data = { WeekStart:moment(start).format('YYYY-MM-DD'), WeekEnd:moment(end).format('YYYY-MM-DD') };
		var events = [];
		fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData',[<?php echo $teacher_id; ?>]), data , function(jsonData) {
			var jsonData = JSON.parse(jsonData);
			var generalvalidSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore; ?>' ,'hours');
			$(jsonData).each(function(i,e) {
				if( generalvalidSelectDateTime > moment(e.end) ) {
					return;
				}
				if( generalvalidSelectDateTime.format('YYYY-MM-DD HH:mm:ss') > moment(e.start).format('YYYY-MM-DD HH:mm:ss') ) {
					e.start =  generalvalidSelectDateTime.format('YYYY-MM-DD HH:mm:ss');
				}
				var classType = (e.classType) ?  e.classType : '';
				if( classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>" ){
					var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
				} else if( classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>" ){
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
					classNames: [className],
					editable: false,
					extendedProps : {
						className : className,
						classType : $(this).attr('classType'),
						action: 'fromGeneralAvailability',
						dow:[$(this).attr('day')],
						_id: $(this).attr('_id'),
					},
					rendering:'background',
						selectable: true,
					resourceEditable :
					dow:[$(this).attr('day')]
				  });
			});
		},{async:false});
	}
	// getTeacherGeneralAvailability end

	// getTeacherScheduledLesson start
	function getTeacherScheduledLesson(successCallback) {
		var events = [];
		fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[<?php echo $teacher_id; ?>]), '', function(jsonData) {
			var jsonData = JSON.parse(jsonData);
			$(jsonData).each(function(i,e) {
				events.push({
					title: $(this).attr('title'),
					start: $(this).attr('start'),
					extendedProps : {
						className: $(this).attr('className'),
					},
					classNames: [$(this).attr('className')],
					end: $(this).attr('end'),
					color: "var(--color-secondary)",
					editable: false,
					selectable: false,
				});
			});
				console.log(successCallback(events));
				calendar.addEvent(events);
			// return events;
		},{async:false});
	}
	// getTeacherScheduledLesson end

	$(document).ready(function() {
		$(document).bind('close.facebox', function() {
		$('.tooltipevent').remove();
		});

		// FullCalendar start
		var calendarEl = document.getElementById("d_calendar");
		var calendar = new FullCalendar.Calendar(calendarEl, {
				plugins: ["timeGrid"],
				header: {
					left: 'time',
					center: '',
					right: 'title prev,next today'
				},
				selectable: true,
				dir : '<?php echo strtolower($layoutDirection) ?>',
				unselectAuto: true,
				editable: false,
				nowIndicator:true,
				selectLongPressDelay:50,
				eventLongPressDelay:50,
				longPressDelay:50,
				allDaySlot: false,
				now:'<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>',
				eventOverlap: false,
				slotEventOverlap : false,
				forceEventDuration : true,
				eventLimit: true,
				defaultDate: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
				defaultTimedEventDuration : "<?php echo $bookingSnapDuration; ?>",
				snapDuration : "<?php echo $bookingSnapDuration; ?>",
				slotDuration : "<?php echo $bookingSnapDuration; ?>",
				timezone: "<?php echo $user_timezone; ?>",
				<?php if( 'free_trial' == $action ){ ?>
				select :function( selectionInfo ) {
					console.log(selectionInfo,'check');
					var start =selectionInfo.start;
					var end = selectionInfo.end;
					var jsEvent = selectionInfo.jsEvent;
					var view =  selectionInfo.view;
					if(checkSlotAvailabiltAjaxRun) {
						return false;
					}
					checkSlotAvailabiltAjaxRun = true;
					var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
					var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore;?>' ,'hours').format('YYYY-MM-DD HH:mm:ss');

					if ( selectedDateTime < validSelectDateTime ) {
						if( selectedDateTime > moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm:ss') ) {
							$.systemMessage('<?php echo Label::getLabel('LBL_Teacher_Disable_the_Booking_before') .' '. $teacherBookingBefore .' Hours.' ; ?>','alert alert--danger');
								setTimeout(function() {
									$.systemMessage.close();
								}, 3000);
						}
						$("#loaderCalendar").hide();
						$("body").css( {"cursor": "default","pointer-events": "initial"} );
						calendar.unselect();
						checkSlotAvailabiltAjaxRun = false;
						return false;
					}
					if( moment('<?php echo $nowDate; ?>').diff(moment(start)) >= 0 ) {
						$("#loaderCalendar").hide();
						$("body").css( {"cursor": "default","pointer-events": "initial"} );
						calendar.unselect();
						checkSlotAvailabiltAjaxRun =  false;
						return false;
					}

					if( moment(start).format('YYYY-MM-DD HH:mm:ss') > moment(end).format('YYYY-MM-DD HH:mm:ss') ) {
						$("#loaderCalendar").hide();
						$("body").css( {"cursor": "default","pointer-events": "initial"} );
						calendar.unselect();
						checkSlotAvailabiltAjaxRun =  false;
						return false;
					}

					var duration = moment.duration( moment(end).diff(moment(start)) );
					var minutesDiff = duration.asMinutes();
					var minutes = "<?php echo $bookingMinutesDuration ?>";
					if ( minutesDiff > minutes ) {
						$("#loaderCalendar").hide();
						$("body").css( {"cursor": "default","pointer-events": "initial"} );
						calendar.unselect();
						checkSlotAvailabiltAjaxRun =  false;
						$('.tooltipevent').remove();
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
					newEvent.extendedProps = {
						className : '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>',
						classType : '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>',
						maxTime : "<?php echo $bookingSnapDuration; ?>"
					};
					newEvent.classNames = ['<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>'];
					newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>';
					newEvent.allday = false;
					newEvent.maxTime = "<?php echo $bookingSnapDuration; ?>";
					newEvent.eventOverlap = false;
					newEvent.overlap = false;
					var currentDate = $('#calendar').fullCalendar('getDate');
					var beginOfWeek = moment(start).startOf('week').format('YYYY-MM-DD HH:mm:ss');
					var endOfWeek = moment(start).endOf('week').format('YYYY-MM-DD HH:mm:ss');
					newEvent.weekStart = moment(beginOfWeek).format('YYYY-MM-DD HH:mm:ss');
					newEvent.weekEnd = moment(endOfWeek).format('YYYY-MM-DD HH:mm:ss');
					checkSlotAvailabiltAjax = fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability',[<?php echo $teacher_id; ?>]), newEvent, function(doc) {
						checkSlotAvailabiltAjaxRun = false;
						$("#loaderCalendar").hide();
						$("body").css( {"cursor": "default", "pointer-events": "initial"} );
						var res = JSON.parse(doc);
						if( res.msg == 1 ){
							calendar.addEvent(newEvent);
						}else if (res.msg == 0) {
							$('.tooltipevent').remove();
						}
					});
				},
			    <?php } ?>
					events: function(fetchInfo, successCallback, failureCallback) {
					var events = [];
					var start =fetchInfo.start;
					var end = fetchInfo.end;
					var timezone = fetchInfo.timezone;
					var data = { start:moment(start).format('YYYY-MM-DD HH:mm:ss'), end:moment(end).format('YYYY-MM-DD HH:mm:ss') };
					fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData',[<?php echo $teacher_id; ?>]), data , function(jsonData) {
						var jsonData = JSON.parse(jsonData);
						if(jsonData.length == 0) {
							var data = { WeekStart:moment(start).format('YYYY-MM-DD'), WeekEnd:moment(end).format('YYYY-MM-DD') };
							fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData',[<?php echo $teacher_id; ?>]), data , function(generalJsonData) {
								events.push({
									title: '',
									start: moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
									end: moment('<?php echo $nowDate; ?>'),
									className: 'past_current_day',
									editable: false,
									rendering:'background'
								});

								var generalJsonData = JSON.parse(generalJsonData);
								var generalvalidSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore; ?>' ,'hours');
									console.log(generalJsonData,'generalJsonData');
								$(generalJsonData).each(function(i,e) {
									if( generalvalidSelectDateTime > moment(e.end) ) {
										return;
									}
									if( generalvalidSelectDateTime.format('YYYY-MM-DD HH:mm:ss') > moment(e.start).format('YYYY-MM-DD HH:mm:ss') ) {
										e.start =  generalvalidSelectDateTime.format('YYYY-MM-DD HH:mm:ss');
									}
									var classType = (e.classType) ?  e.classType : '';
									if( classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>" ){
										var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
									} else if( classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>" ){
										var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::UNAVAILABLE]; ?>';
									}

									events.push({
										title: $(this).attr('title'),
										start: $(this).attr('start'),
										end: $(this).attr('end'),
										color: $(this).attr('color'),
										day: $(this).attr('day'),
										allDay : false,
										//_id: $(this).attr('_id'),
										action: 'fromGeneralAvailability',
										classType: $(this).attr('classType'),
										className: className,
										editable: false,
										extendedProps : {
											className : className,
											classType : $(this).attr('classType'),
											action: 'fromGeneralAvailability',
											dow:[$(this).attr('day')],
											_id: $(this).attr('_id'),
										},
										rendering:'background',
										selectable: true,
										dow:[$(this).attr('day')]
									  });
								});
							},{async:false});
						}
						 else {
							events.push({
								title: '',
								start: moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
								end: moment('<?php echo $nowDate; ?>'),
								classNames: ['past_current_day'],
								extendedProps :{
									className : 'past_current_day',
								},
								editable: false,
								rendering:'background'
							});
							var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore; ?>' ,'hours');
							$(jsonData).each(function(i,e) {
								if(  validSelectDateTime > moment(e.end) ) {
									return;
								}
								if( validSelectDateTime.format('YYYY-MM-DD HH:mm:ss') > moment(e.start).format('YYYY-MM-DD HH:mm:ss') ) {
									e.start =  validSelectDateTime.format('YYYY-MM-DD HH:mm:ss');
								}
								var classType = $(this).attr('classType');
								if( classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>" ){
									var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
									if( moment('<?php echo $nowDate; ?>') > moment(e.start) ) {
										var editable = false;
									} else {
										var editable = true;
									}
								}else if( classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>" ){
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
									extendedProps : {
										className : className,
										classType : $(this).attr('classType'),
										action: 'fromWeeklySchedule',
										_id: $(this).attr('_id'),
									},
									classNames: [className],
									rendering:'background',
									editable: editable,
									selectable: editable,
								});
							});
						}
						fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[<?php echo $teacher_id; ?>]), '', function(scheduledLessonData) {
							var scheduledLessonData = JSON.parse(scheduledLessonData);
							$(scheduledLessonData).each(function(i,e) {
								events.push({
									title: $(this).attr('title'),
									start: $(this).attr('start'),
									extendedProps : {
										className: $(this).attr('className'),
									},
									classNames: [$(this).attr('className')],
									end: $(this).attr('end'),
									color: "var(--color-secondary)",
									editable: false,
									selectable: false,
								});
							});
						 },{async:false});
						successCallback(events);
					},{async:false});
				},
				eventRender: function(info) {
					element = $(info.el);
					console.log(info.event.extendedProps._id,'info.event.extendedProps._id',info.event.extendedProps.className);
					if( isNaN(info.event.extendedProps._id) && info.event.extendedProps.className != "sch_data" ){

					element.find(".fc-content").prepend( "<span class='closeon'>X</span>" );
					}
					// else {

					// var eventData = JSON.stringify({"start" :moment( info.event.start).format('YYYY-MM-DD HH:mm:ss'),"end" : moment(info.event.end).format('YYYY-MM-DD HH:mm:ss'),"_id":info.event.extendedProps._id,"classType":info.event.extendedProps.classType,"day":info.event.day});
						// if( info.event.extendedProps.classType != 0  && info.event.extendedProps.className != "sch_data"){
						// 	element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherWeeklySchedule(" + eventData + ");'>X</span>" );
						// }
					// }

					element.find(".closeon").click(function() {
						if(isNaN(info.event.extendedProps._id)){
						    info.event.remove()
						    $('.tooltipevent').remove();
						}
					});

					var eventEnd = moment(info.event.end);
					var NOW = moment();
					if(moment(info.event.end).format('YYYY-MM-DD HH:mm') < moment().format('YYYY-MM-DD HH:mm') && info.event.extendedProps.className != "sch_data"){
						return false;
					}
				},
				eventDrop: function(eventDropInfo) {
					//console.log(event.start.isBefore(moment()));
					if( moment('<?php echo $nowDate; ?>').diff(moment(eventDropInfo.event.start)) >= 0) {
						calendar.refetchEvents();
						return false;
					}
				},
				eventResize: function(event, delta, revertFunc) {
					return false;
				},
				eventMouseout: function(mouseLeaveInfo) {
					$(this).css('z-index', 8);
				},
				eventClick: function(eventClickInfo) {
					calEvent = eventClickInfo.event;
					jsEvent = eventClickInfo.jsEvent;
					var monthName = moment(calEvent.start).format('MMMM');
					var date = monthName+" "+moment(calEvent.start).format('DD, YYYY');
					var start = moment(calEvent.start).format('HH:mm A');
					var end = moment(calEvent.end).format('HH:mm A');
					var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss');
					var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss');
					var price = "<?php echo CommonHelper::displayMoneyFormat( $userRow['us_single_lesson_amount']); ?>";
	                <?php if( 'free_trial' == $action ){ ?>
					var tooltip = '<div class="tooltipevent" style="position:absolute;z-index:10001;"><div class="booking-view"><h3 class="-display-inline"><?php echo $teacher_name?></h3><span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($teacher_country_id, 'DEFAULT') ); ?>" alt=""></span><div class="inline-list"><span class="inline-list__value highlight"><strong>Date</strong> &nbsp; &nbsp; '+date+' at '+start+'-'+end+'</span><?php 		if( 'free_trial' != $action ){?><span class="inline-list__value"><strong>Price</strong> &nbsp; &nbsp;   '+ price +'</span> <?php }?></div></div><div class="-align-center"><a href="javascript:void(0)" onClick="cart.add(&quot;<?php echo $teacher_id; ?>&quot;, <?php echo $lPackageId; ?>, &quot;'+ selectedStartDateTime +'&quot;, &quot;'+ selectedEndDateTime +'&quot;, &quot;'+ '<?php echo $languageId;?>' +'&quot; );" class="btn btn--secondary btn--small btn--wide"><?php echo Label::getLabel('LBL_Book_Lesson'); ?></a></div><a onclick="$(&apos;.tooltipevent&apos;).remove();" href="javascript:void(0);" class="-link-close"></a></div>';
					<?php } ?>
					if( calEvent.extendedProps.className != "sch_data" ){
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
		calendar.render();
		// FullCalendar end
		$(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
		$('.tooltipevent').remove();
		});
		$('body').find(".fc-left").html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'></span></h6>");
	});



</script>
<div id="loaderCalendar" style="display: none;"><div class="loader"></div></div>
<div class="calendar-view">
<?php //if( 'free_trial' != $action ){ ?>
<div class="row">
	<div class="col-sm-6">
		<h4><?php echo $userRow['user_full_name']." ".Label::getLabel('Lbl_Calendar'); ?></h4>
	</div>
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
<?php //} ?>

<span> <?php echo MyDate::displayTimezoneString();?> </span>
<div id='d_calendar'></div>
</div>
