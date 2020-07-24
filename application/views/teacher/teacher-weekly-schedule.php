<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$user_timezone = MyDate::getUserTimeZone();
$nowDate = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
?>
<script>
    var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
    var timeInterval;
	var seconds = 2;
	clearInterval(timeInterval);
	timeInterval = setInterval(currentTimer, 1000);
    function currentTimer() {
      $('body').find(".fc-left h6 span.timer").html(moment('<?php echo $nowDate; ?>').add(seconds,'seconds').format('hh:mm A'));
      seconds++;
    }
   $(document).ready(function() {
	//moment().tz.setDefault("America/New_York");

   	$("#setUpWeeklyAvailability").click(function(){
   		var json = JSON.stringify($("#w_calendar").fullCalendar("clientEvents").map(function(e) {
            console.log(e);
   			return 	{
   				start: moment(e.start).format('HH:mm:ss'),
   				end: moment(e.end).format('HH:mm:ss'),
   				date:moment(e.start).format('YYYY-MM-DD'),
   				_id: e._id,
   				action: e.action,
   				classtype: e.classType,
   			};
   		}));
   		setupTeacherWeeklySchedule(json);
   	});

    deleteTeacherWeeklySchedule  = function(eventData){
        console.log('Hello');
        $.mbsmessage.close();
        confirmMessage = '<?php echo Label::getLabel( 'LBL_Do_you_want_to_disable_the_slot' ); ?>';
        var edata = $("#w_calendar").fullCalendar("clientEvents",eventData._id);
        if(!edata.length) {
            return;
        }
        edata = edata[0];
       // $('#w_calendar').fullCalendar('removeEvents', eventData._id);
       edata.className = ['<?php echo $cssClassArr[TeacherWeeklySchedule::UNAVAILABLE]; ?>'];
       edata.classType = <?php echo TeacherWeeklySchedule::UNAVAILABLE ?>;
       if(typeof eventData.classType !== 'undefined' && eventData.classType == <?php echo TeacherWeeklySchedule::UNAVAILABLE ?>) {
           edata.classType = <?php echo TeacherWeeklySchedule::AVAILABLE ?>;
           edata.className = ['<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>'];
           edata.editable = true;
           confirmMessage = '<?php echo Label::getLabel( 'LBL_Do_you_want_to_enable_the_slot_again' ); ?>';
        }

         if(confirm(confirmMessage)){
            console.log(edata,'edata');
             $("#w_calendar").fullCalendar('updateEvent',edata);
             mergeEvents();
             return;
            // var json = JSON.stringify($("#w_calendar").fullCalendar("clientEvents").map(function(e) {
            // return 	{
            //     start: moment(e.start).format('HH:mm:ss'),
            //     end: moment(e.end).format('HH:mm:ss'),
            //     day: moment(e.start).format('d'),
            //     date: moment(e.start).format('YYYY-MM-DD'),
            //     _id: e._id,
            //     action: e.action,
            //     classtype: e.classType,
            // };
            // }));
            // fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherWeeklySchedule'), 'data='+json, function(t) {
            //     $("#w_calendar").fullCalendar("refetchEvents");
            //     // fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherWeeklySchedule'), 'data='+JSON.stringify(eventData) , function(t) {
            //     // });
            // });
         }
    };

    function mergeEvents() {
        allevents = $("#w_calendar").fullCalendar("clientEvents");
        if(allevents.length == 1) {
            return;
        }
        // debugger;
        $('#loaderCalendar').show();
        $.each(allevents, function( i, eItem )
        {

            if(eItem ===null || typeof eItem == 'undefined')
            {
                return;
            }
            var start =  eItem.start;
            var end =  eItem.end;
            var eventId =  eItem._id;
            var action =  eItem.action;

            if(typeof eItem.classType !== 'undefined' && eItem.classType == <?php  echo TeacherWeeklySchedule::UNAVAILABLE; ?>) {
                return;
            }

            $.each(allevents, function( index, eventitem )
            {
                eventmerge =  false;
                if(eventId == eventitem._id){
                    return;
                }
                if(typeof eItem.classType !== 'undefined' && eventitem.classType == <?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>) {
                    return;
                }
                // if start time of new event (2nd slot) is end time of existing event (1st slot)
                if( moment(start).format('YYYY-MM-DD HH:mm') == moment(eventitem.end).format('YYYY-MM-DD HH:mm') )
                {
                    eventmerge = true;
                    eventitem.end = end;

                }
                // if end time of new event (1st slot) is start time of existing event (2nd slot)
                else if( moment(end).format('YYYY-MM-DD HH:mm') == moment(eventitem.start).format('YYYY-MM-DD HH:mm') )
                {
                    eventmerge = true;
                    // existing event gets start data of new merging event
                    eventitem.start = start;
                }

                if(eventmerge)
                {
                    eventData = eventitem;
                    // find event object in calendar

                    eventId = parseInt(eventId);
                    console.log(eventitem);
                    console.log(eventitem._id,eventId);
                    eventitemId =  eventitem._id;
                    if(eventId && Number.isInteger(eventId)) {
                        eventitem._id = eventId;
                        eventitem.className = eItem.className;
                        eventitem.classType = eItem.classType;
                    }else{
                        eventitemId = eItem._id;
                    }
                    eventitem.action = '';
                    eventitem.classType = <?php echo TeacherWeeklySchedule::AVAILABLE; ?>;
                    eventitem.className = ['<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>'];
                    eventitem.editable = true;
                     $('#w_calendar').fullCalendar('updateEvent', eventitem);
                    $('#w_calendar').fullCalendar('removeEvents', eventitemId);

                    // break each loop
                    return;
                }
            });
        });
            console.log('loaderCalendar hide');
        $('#loaderCalendar').hide();
    }
   	$('#w_calendar').fullCalendar({
   		header: {
   			left: 'time',
   			center: '',
   			right: 'title prev,next today'
   		},
   		defaultView: 'agendaWeek',
   		selectable: true,
   		editable: true,
   		//firstDay:"<?php echo date('N', strtotime(date('Y-m-d')));  ?>",
   		nowIndicator:true,
		now:'<?php echo $nowDate; ?>',
   		selectOverlap: false,
   		eventOverlap: false,
   		slotEventOverlap : false,
   		forceEventDuration : true,
        selectLongPressDelay:50,
        eventLongPressDelay:50,
        longPressDelay:50,
   		defaultTimedEventDuration : "00:30:00",
		timezoneParam: 'timezone',
		timezone: '<?php echo $user_timezone; ?>',
   		allDaySlot: false,
   		select: function (start, end, jsEvent, view ) {

			if(moment('<?php echo $nowDate; ?>').diff(moment(start)) >= 0) {
   				$('#w_calendar').fullCalendar('unselect');
   				return false;
   			}
   			if(moment(start).format('d')!=moment(end).format('d') ) {
   				$('#w_calendar').fullCalendar('unselect');
   				return false;
   			}
   			var newEvent = new Object();
   			newEvent.title = '';
   			newEvent.start = moment(start).format();
   			newEvent.end = moment(end).format(),
   			newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>',
   			newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>',
   			newEvent.date = moment(start).format('YYYY-MM-DD');
   			newEvent.allday = 'false';
   			$('#w_calendar').fullCalendar('renderEvent',newEvent);
            mergeEvents();
   		},
   		eventLimit: true,
   		defaultDate: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
        loading :function( isLoading, view ) {
            if(isLoading == true){
                 $("#loaderCalendar").show();
            }else{
                 $("#loaderCalendar").hide();
            }

        },
   		events: function(start, end, timezone, callback) {
   			$.ajax({
   			  url: "<?php echo CommonHelper::generateUrl('Teacher','getTeacherWeeklyScheduleJsonData'); ?>",
   			  data:{start:moment(start).format('YYYY-MM-DD'),end:moment(end).format('YYYY-MM-DD')},
   			  method:'post',
   				success: function(doc) {
   				if(doc == "[]")
   				{
				data = { WeekStart:moment(start).format('YYYY-MM-DD'), WeekEnd:moment(end).format('YYYY-MM-DD') };

   				$.ajax({
   				url: "<?php echo CommonHelper::generateUrl('Teacher','getTeacherGeneralAvailabilityJsonDataForWeekly'); ?>",
				data : data,
				method : 'POST',
   				success: function(doc) {
   					var doc = JSON.parse(doc);
   					var events = [];
   					// events.push({
   					// 	title: '',
   					// 	start: moment('<?php //echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
   					// 	date: moment('<?php //echo $nowDate; ?>').format('YYYY-MM-DD'),
   					// 	end: moment('<?php //echo $nowDate; ?>'),
   					// 	className: 'past_current_day',
   					// 	editable: false,
   					// 	rendering:'background'
   					// 	});
   					$(doc).each(function(i,e) {
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
   						_id: $(this).attr('_id'),
   						action: 'fromGeneralAvailability',
   						classType: $(this).attr('classType'),
   						className: className,
   						editable: false,
   						//dow:[$(this).attr('day')]
   					  });
   					});
   					callback(events);
   					}
   				});
   				}
   				else{
   				var doc = JSON.parse(doc);
   				var events = [];
   				// events.push({
   				// 		title: '',
   				// 		start: moment('<?php //echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
				// 		date: moment('<?php //echo $nowDate; ?>').format('YYYY-MM-DD'),
   				// 		end: moment('<?php //echo $nowDate; ?>'),
   				// 		className: 'past_current_day',
   				// 		editable: false,
   				// 		rendering:'background'
   				// 		});
   				$(doc).each(function(i,e) {
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
   					_id: $(this).attr('_id'),
   					action: 'fromWeeklySchedule',
   					classType: $(this).attr('classType'),
   					className: className,
   					editable: editable,
   				  });
   				});
   				callback(events);
   				}
   			  }
   			});
   		  },
          eventAfterAllRender : function(view) {
                    mergeEvents();
          },
   		eventRender: function(event, element) {
   			if(isNaN(event._id)){
   				element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
   			}
   			else{
   				var eventData=JSON.stringify({
                        "start" :moment( event.start).format('HH:mm:ss'),"end" : moment(event.end).format('HH:mm:ss'),"_id":event._id,"classType":event.classType,"date":moment( event.start).format('YYYY-MM-DD'),
                        "day": moment(event.start).format('d')
                    });
   				//if(event.classType != <?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>){
                element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherWeeklySchedule("+eventData+");'>X</span>" );
   				//}
   			}
       		element.find(".closeon").click(function() {
       			if(isNaN(event._id)){
       				$('#w_calendar').fullCalendar('removeEvents',event._id);
       			}
       		});
   		var eventEnd = moment(event.end);
   		var NOW = moment('<?php echo $nowDate; ?>');

   		if(moment(event.end).format('YYYY-MM-DD HH:mm') < moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm')){
   			return false;
   		}
   	},
   	eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
   		console.log(event.start.isBefore(moment()));
   		if(moment().diff(moment(event.start)) >= 0) {
   			$("#w_calendar").fullCalendar("refetchEvents");
   			return false;
   		}
   	},
   	eventResize: function(event, delta, revertFunc) {},
   	});
    $('body').find(".fc-left").html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'></span></h6>");
   });

</script>
<button id="setUpWeeklyAvailability" class="btn btn--secondary"><?php echo Label::getLabel( 'LBL_Save' );?></button>
<span class="-gap"></span>

<span class="-gap"></span>

<div class="calendar-view -no-padding">
<div id="loaderCalendar" style="display: none;">
    <div class="loader"></div>
</div>
<span> <?php echo MyDate::displayTimezoneString();?> </span>
<div id='w_calendar'></div>
</div>
