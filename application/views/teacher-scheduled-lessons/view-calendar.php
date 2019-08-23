<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script>
   function isOverlapping(start,end){
       var array = $("#listing_calendar").fullCalendar('clientEvents');
       for(i in array){
           if (end >= array[i].start && start <= array[i].end){
              return true;
           }
       }
       return false;
   }
   $(document).ready(function() {
   	$("#btn").click(function(){
   		var json = JSON.stringify($("#listing_calendar").fullCalendar("clientEvents").map(function(e) {
   		return 	{
   			start: moment(e.start).format('HH:mm:ss'),
   			end: moment(e.end).format('HH:mm:ss'),
			startTime: moment(e.start).format('HH:mm'),
   			endTime: moment(e.end).format('HH:mm'),
   			day: moment(e.start).format('d'),
   			classtype: e.classType,
   		};
   		}));
   		setupTeacherGeneralAvailability(json);
   	});
   	$('#listing_calendar').fullCalendar({
   		
   			selectable: true,
   			editable: false,
   			selectOverlap: false,
   			eventOverlap: false,
   			slotEventOverlap : false,
   			allDaySlot: false,
   			columnHeaderFormat :"ddd",
			timezone: "<?php echo MyDate::getTimeZone(); ?>",
   			select: function (start, end, jsEvent, view ) {
   				if(moment(start).format('d')!=moment(end).format('d') ) {
   					$('#listing_calendar').fullCalendar('unselect');
   					return false;
   				}
   			},
   			eventLimit: true, 
   			timeFormat: 'hh:mm a',
   			events: "<?php echo CommonHelper::generateUrl('TeacherScheduledLessons','calendarJsonData'); ?>",
   			eventRender: function(event, element) {
   				if(isNaN(event._id)){ 
					element.find(".fc-content").prepend( '	<div class="avtar avtar--xsmall" data-text="'+event.liFname+'">'+event.imgTag+'</div>' );
   				}
   				else{
   				//	element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherGeneralAvailability("+event._id+");'>X</span>" );
   				}
               element.find(".closeon").click(function() {
   				if(isNaN(event._id)){				
   					$('#listing_calendar').fullCalendar('removeEvents',event._id);
   				}
               });
           },
   	});
   });
</script>
<div class="calendar-view">
<div id='listing_calendar'></div>
</div>