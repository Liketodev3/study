<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<?php
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
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
				<?php if (strtolower($layoutDirection ) == 'rtl') { ?>
				dir : 'rtl',
				isRTL : true,
				<?php } ?>
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
				defaultTimedEventDuration : "<?php echo $bookingSnapDuration; ?>",
				snapDuration : "<?php echo $bookingSnapDuration; ?>",
				timezone: "<?php echo $user_timezone; ?>",
				<?php if( 'free_trial' == $action ){ ?>
				select :function(selectionInfo, successCallback, failureCallback) {
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

				},
			    <?php } ?>
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
