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
   function isOverlapping(start,end){
       var array = $("#ga_calendar").fullCalendar('clientEvents');
       for(i in array){
           if (end >= array[i].start && start <= array[i].end){
              return true;
           }
       }
       return false;
   }
   $(document).ready(function() {

   	$("#setUpGABtn").click(function(){
   		var json = JSON.stringify($("#ga_calendar").fullCalendar("clientEvents").map(function(e) {
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

   	$('#ga_calendar').fullCalendar({
   		  header: {
   				left: '',
   				center: '',
   				right: ''
   			},
   			defaultView: 'agendaWeek',
   			selectable: true,
   			editable: true,
   			selectOverlap: false,
   			eventOverlap: false,
   			slotEventOverlap : false,
			selectLongPressDelay:50,
			eventLongPressDelay:50,
			longPressDelay:50,
   			allDaySlot: false,
   			columnHeaderFormat :"ddd",
			timezone: '<?php echo $user_timezone; ?>',
   			select: function (start, end, jsEvent, view ) {
   				if(moment(start).format('d')!=moment(end).format('d') ) {
   					$('#ga_calendar').fullCalendar('unselect');
   					return false;
   				}
   				var newEvent = new Object();
   				newEvent.title = '';
   				newEvent.start = moment(start).format('YYYY-MM-DD')+" "+moment(start).format('HH:mm');
   				newEvent.end = moment(end).format('YYYY-MM-DD')+" "+moment(end).format('HH:mm'),
				newEvent.startTime = moment(start).format('HH:mm:ss');
   				newEvent.endTime = moment(end).format('HH:mm:ss'),
   				newEvent.day = moment(start).format('d'),
   				newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>',
   				newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>',
   				newEvent.allday = 'false';
   				$('#ga_calendar').fullCalendar('renderEvent',newEvent);
   			},
   			eventLimit: true,
   			defaultDate: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
   			events: "<?php echo CommonHelper::generateUrl('Teacher','getTeacherGeneralAvailabilityJsonData'); ?>",
   			eventRender: function(event, element) {
   				if(isNaN(event._id)){
   					element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
   				}
   				else{
   					element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherGeneralAvailability("+event._id+");'>X</span>" );
   				}
               element.find(".closeon").click(function() {
   				if(isNaN(event._id)){
   					$('#ga_calendar').fullCalendar('removeEvents',event._id);
   				}
               });
           },
   	});
	$('body').find(".fc-left").html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'></span></h6>");
   });
</script>
<h6><?php echo Label::getLabel('LBL_General_Availability_Note'); ?></h6>
<br />
<button id='setUpGABtn' class="btn btn--secondary"><?php echo Label::getLabel('LBL_Save'); ?></button>
<span class="-gap"></span>
<div class="calendar-view -no-padding">
<span> <?php echo MyDate::displayTimezoneString();?> </span>
<div id='ga_calendar'></div>
</div>
<script>
var current_day = '<?php echo strtolower(date('D')); ?>';
$(document).ready(function(){
    $(".fc-view-container").find('.fc-'+current_day).addClass('fc-today');
})
</script>
