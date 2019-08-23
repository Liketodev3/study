<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script type="text/javascript">

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
            unselectAuto: true,
			editable: false,
			nowIndicator:true,
			eventOverlap: false,
			slotEventOverlap : false,
			defaultTimedEventDuration : "<?php echo $bookingSnapDuration; ?>",
			snapDuration : "<?php echo $bookingSnapDuration; ?>",
			allDaySlot: false,
			timezone: "<?php echo MyDate::getTimeZone(); ?>",
			                <?php if( 'free_trial' == $action ){ ?>
			select: function (start, end, jsEvent, view ) {
				
				$("body").css( {"pointer-events": "none"} );
				$("body").css( {"cursor": "wait"} );
				//==================================//
					var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
					var validSelectDateTime = moment().add('<?php echo $teacherBookingBefore;?>' ,'hours').format('YYYY-MM-DD HH:mm:ss');
					if ( selectedDateTime < validSelectDateTime ) {	
						$("body").css({"cursor": "default"});
						$("body").css({"pointer-events": "initial"});
						if( selectedDateTime > moment().format('YYYY-MM-DD HH:mm:ss') ) {
							$.systemMessage('<?php echo Label::getLabel('LBL_Teacher_Disable_the_Booking_before') .' '. $teacherBookingBefore .' Hours.' ; ?>','alert alert--success');	
								setTimeout(function() {  
									$.systemMessage.close();
								}, 3000);
						}
					
						$('#calendar').fullCalendar('unselect');
						return false;
					}
				//================================//
				
				
				if( getEventsByTime( start, end ).length > 1 ){
					$('#d_calendar').fullCalendar('refetchEvents');
				}
				
				if( moment().diff(moment(start)) >= 0 ) {
					
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					
					$('#d_calendar').fullCalendar('unselect');
					return false;
				}
				
				if( moment(start).format('d') != moment(end).format('d') ) {
					
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					
					$('#d_calendar').fullCalendar('unselect');
					return false;
				}
				
				var duration = moment.duration( moment(end).diff(moment(start)) );
				var minutesDiff = duration.asMinutes();
				var minutes = "<?php echo $bookingMinutesDuration ?>";
				if( minutesDiff > minutes ){
					
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					
					$('#d_calendar').fullCalendar('unselect');
					$('.tooltipevent').remove();
					return false;
				}
				
				var newEvent = new Object();
				newEvent.title = '';
				newEvent.startTime = moment(start).format('HH:mm:ss');
				newEvent.endTime = moment(end).format('HH:mm:ss');
				newEvent.start = moment(end).format('YYYY-MM-DD')+" "+ moment(start).format('HH:mm:ss');
				newEvent.end = moment(end).format('YYYY-MM-DD')+" "+moment(end).format('HH:mm:ss');
				newEvent.date = moment(end).format('YYYY-MM-DD');
				newEvent.day = moment(start).format('d');
				newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
				newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>';
				newEvent.allday = 'false';
				newEvent.maxTime = "<?php echo $bookingSnapDuration; ?>";
				newEvent.eventOverlap = false;
				
				fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability',[<?php echo $teacher_id; ?>]), newEvent, function(doc) {
					
					$("body").css( {"cursor": "default"} );
				    $("body").css( {"pointer-events": "initial"} );
					
					var res = JSON.parse(doc);
					if( res.msg == 1 ){
						$('#d_calendar').fullCalendar('renderEvent',newEvent);
					}
					if( res.msg == 0 ){
						$('.tooltipevent').remove();
					}
				});
			},
                            <?php } ?>
			eventLimit: true,
			defaultDate: '<?php echo date('Y-m-d'); ?>',
			events: function( start, end, timezone, callback ) {
				var data = { start:moment(start).format('YYYY-MM-DD HH:mm:ss'), end:moment(end).format('YYYY-MM-DD HH:mm:ss') };
				
				fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData',[<?php echo $teacher_id; ?>]), data, function(doc) {
					if( doc == "[]" ){
						
						fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData',[<?php echo $teacher_id; ?>]), '', function(doc) {
							var doc = JSON.parse(doc);
							var events = [];
							events.push({
								title: '',
								start: moment().format('YYYY-MM-DD 00:00:00'),
								end: moment(),
								className: 'past_current_day',
								editable: false,
								rendering:'background'
							});
							
							$(doc).each(function(i,e) {
								var classType = $(this).attr('classType');
								if( classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>" ){
									var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
								} else if( classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>" ){
									var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::UNAVAILABLE]; ?>';
								}
								
								events.push({
									title: $(this).attr('title'),
									start: $(this).attr('startW'),
									end: $(this).attr('endW'),
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
						
					} else {
						var doc = JSON.parse(doc);
						var events = [];
						
						events.push({
							title: '',
							start: moment().format('YYYY-MM-DD 00:00:00'),
							end: moment(),
							className: 'past_current_day',
							editable: false,
							rendering:'background'
						});
						
						$(doc).each(function(i,e) {
							var classType = $(this).attr('classType');
							if( classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>" ){
								var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
								if( moment() > moment(e.start) ) {
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
								className: $(this).attr('className'),
								end: $(this).attr('end'),
								color: "var(--color-secondary)",
								editable: false,
								selectable: false,
								});
							});
							
							callback(events);
						});
					}
				});
			},
			
			eventRender: function(event, element) {
				if( isNaN(event._id) && event.className != "sch_data" ){
					element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
				} else {
					var eventData = JSON.stringify({"start" :moment( event.start).format('YYYY-MM-DD HH:mm:ss'),"end" : moment(event.end).format('YYYY-MM-DD HH:mm:ss'),"_id":event._id,"classType":event.classType,"day":event.day});
					
					if( event.classType != 0  && event.className != "sch_data"){
						element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherWeeklySchedule(" + eventData + ");'>X</span>" );
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
				if(moment(event.end).format('YYYY-MM-DD HH:mm') < moment().format('YYYY-MM-DD HH:mm') && event.className != "sch_data"){
					return false;
				}
			},
			
			eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
				//console.log(event.start.isBefore(moment()));
				if( moment().diff(moment(event.start)) >= 0) {
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
				//console.log(event.start.isBefore(moment()));
				if(moment().diff(moment(event.start)) >= 0) {
					$("#d_calendar").fullCalendar("refetchEvents");
					return false;
				}
			},
	
			eventMouseover: function(calEvent, jsEvent) {
				var monthName = moment(calEvent.start).format('MMMM');
				var date = monthName+" "+moment(calEvent.start).format('DD, YYYY');
				var start = moment(calEvent.start).format('HH:mm A');
				var end = moment(calEvent.end).format('HH:mm A');
				var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss');
				var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss');
				var price = "<?php echo CommonHelper::displayMoneyFormat( $userRow['us_single_lesson_amount']); ?>";
                <?php if( 'free_trial' == $action ){ ?>
				//var tooltip = '<div class="tooltipevent" style="position:absolute;z-index:10001;"><a onclick="$(&apos;.tooltipevent&apos;).remove();" href="javascript:void();" class="-link-close"></a> <?php echo Label::getLabel('LBL_Date:')?> ' + date + '  <br /><?php echo Label::getLabel('LBL_Time:')?> ' + start + '-' + end + ' <br /><?php 		if( 'free_trial' != $action ){ echo Label::getLabel('LBL_Price::')?> '+ price +'<br /><?php } ?> <a onClick="cart.add(&quot;<?php echo $teacher_id; ?>&quot;, <?php echo $lPackageId; ?>, &quot;'+ selectedStartDateTime +'&quot;, &quot;'+ selectedEndDateTime +'&quot; );" id="btn" class="btn btn--secondary"><?php echo Label::getLabel('LBL_Book_Lesson'); ?></a></div>';
           var tooltip = '<div class="tooltipevent" style="position:absolute;z-index:10001;"><div class="booking-view"><h3 class="-display-inline"><?php echo $teacher_name?></h3><span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($teacher_country_id, 'DEFAULT') ); ?>" alt=""></span><div class="inline-list"><span class="inline-list__value highlight"><strong>Date</strong> &nbsp; &nbsp; '+date+' at '+start+'-'+end+'</span><?php 		if( 'free_trial' != $action ){?><span class="inline-list__value"><strong>Price</strong> &nbsp; &nbsp;   '+ price +'</span> <?php }?></div></div><div class="-align-center"><a href="javascript:void(0)" onClick="cart.add(&quot;<?php echo $teacher_id; ?>&quot;, <?php echo $lPackageId; ?>, &quot;'+ selectedStartDateTime +'&quot;, &quot;'+ selectedEndDateTime +'&quot;, &quot;'+ '<?php echo $languageId;?>' +'&quot; );" class="btn btn--secondary btn--small btn--wide"><?php echo Label::getLabel('LBL_Book_Lesson'); ?></a></div><a onclick="$(&apos;.tooltipevent&apos;).remove();" href="javascript:void();" class="-link-close"></a></div>';   

           
				<?php } ?>
				if( calEvent.className != "sch_data" ){
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
	});
	
</script>

<div class="calendar-view">
<?php if( 'free_trial' != $action ){ ?>
<h4><?php echo Label::getLabel('Lbl_View_Availibility_(Click_Buy_to_Book)'); ?></h4>
<?php } ?>
<div id='d_calendar'></div>
</div>
