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
            timeFormat: 'hh:mm a',
			timezone: "<?php echo $user_timezone; ?>",
   			select: function (start, end, jsEvent, view ) {
   				if(moment(start).format('d')!=moment(end).format('d') ) {
   					$('#listing_calendar').fullCalendar('unselect');
   					return false;
   				}
   			},
   			eventLimit: true, 
   			
   			events: "<?php echo CommonHelper::generateUrl('LearnerScheduledLessons','calendarJsonData'); ?>",
   			eventRender: function(event, element) {
   				if(isNaN(event._id)){ 
					var imgUrl = fcom.makeUrl('Image','user',[event.lid])+'<?php echo time(); ?>';
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
<span> <?php echo MyDate::displayTimezoneString();?> </span>
<div id='listing_calendar'></div>
</div>
