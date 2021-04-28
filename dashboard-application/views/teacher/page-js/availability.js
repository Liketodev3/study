(function () {

var dv = '#availability-calendar-js';
teacherGeneralAvailability = function () {
	$(dv).html(fcom.getLoader());
	fcom.ajax(fcom.makeUrl('Teacher', 'teacherGeneralAvailability'), '', function (t) {
		$(dv).html(t);
		getTeacherProfileProgress();
	});
};

setupTeacherWeeklySchedule = function (frm) {
	$(dv).html(fcom.getLoader());
	fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherWeeklySchedule'), 'data=' + frm, function (t) {
		teacherWeeklySchedule()
	});
};

setUpWeeklyAvailability = function () {
	var json = JSON.stringify(calendar.getEvents().map(function (e) {

		return {
			start: moment(e.start).format('HH:mm:ss'),
			end: moment(e.end).format('HH:mm:ss'),
			date: moment(e.start).format('YYYY-MM-DD'),
			_id: e.extendedProps._id,
			action: e.extendedProps.action,
			classtype: e.extendedProps.classType,
		};
	}));

	setupTeacherWeeklySchedule(json);
};


setupTeacherGeneralAvailability = function (frm) {
	$(dv).html(fcom.getLoader());
	fcom.updateWithAjax(fcom.makeUrl('Teacher', 'setupTeacherGeneralAvailability'), 'data=' + frm, function (t) {
		teacherGeneralAvailability();
		//$("#ga_calendar").fullCalendar("refetchEvents");
	});
};

saveGeneralAvailability = function () {
	var allevents = calendar.getEvents();
	let data = allevents.map(function (e) {
		return {
			start: moment(e.start).format('HH:mm:ss'),
			end: moment(e.end).format('HH:mm:ss'),
			startTime: moment(e.start).format('HH:mm'),
			endTime: moment(e.end).format('HH:mm'),
			day: moment(e.start).format('d'),
			dayStart: moment(e.start).format('d'),
			dayEnd: moment(e.end).format('d'),
			classtype: e.classType,
		};
	});

	data.forEach(element => {

		if ((element.dayStart != element.dayEnd)
			&&
			((element.endTime != '00:00') || (element.startTime == '00:00'))
		) {

			if ((element.dayEnd - element.dayStart == 1)) {

				if ((element.endTime != '00:00') || (element.startTime != '00:00')) {

					let elementClone = $.parseJSON(JSON.stringify(element));
					elementClone.day = parseInt(element.dayEnd);
					elementClone.start = '00:00:00';
					elementClone.startTime = '00:00';
					data[data.length] = elementClone;
				}
			} else {

				for (let index = 0; index < element.dayEnd - element.dayStart; index++) {

					if ((element.endTime == '00:00') && (parseInt(element.dayStart) + index + 1 == element.dayEnd)) {

						continue;
					}
					let elementClone = $.parseJSON(JSON.stringify(element));
					elementClone.day = parseInt(element.dayStart) + index + 1;
					elementClone.start = '00:00:00';
					elementClone.startTime = '00:00';
					if (index + 1 != element.dayEnd - element.dayStart) {
						elementClone.end = '24:00:00';
						elementClone.endTime = '24:00';
					}
					data[data.length] = elementClone;
				}
			}
			element.end = '24:00:00';
			element.endTime = '24:00';
		}
	});
	var json = JSON.stringify(data);

	setupTeacherGeneralAvailability(json);
};



deleteTeacherGeneralAvailability = function (id) {
	if (confirm(langLbl['confirmRemove'])) {
		$('#ga_calendar').fullCalendar('removeEvents', id);
		//  fcom.updateWithAjax(fcom.makeUrl('Teacher', 'deleteTeacherGeneralAvailability',[id]), '' , function(t) {
		// 		if(userIsTeacher) {
		// 		  getTeacherProfileProgress(false);
		// 		}
		// });
	}
};

teacherWeeklySchedule = function () {
	$(dv).html(fcom.getLoader());
	fcom.ajax(fcom.makeUrl('Teacher', 'teacherWeeklySchedule'), '', function (t) {
		$(dv).html(t);

	});
};

getTeacherProfileProgress = function () {
	fcom.ajax(fcom.makeUrl('Teacher', 'getTeacherProfileProgress'), '', function (data) {
			$.each(data.teacherProfileProgress, function (key, value) {
				switch (key) {
					case 'isProfileCompleted':
						if(value){
							$('.is-profile-complete-js').removeClass('infobar__media-icon--alert').addClass('infobar__media-icon--tick');
							$('.is-profile-complete-js').html('');
						}else{
							$('.is-profile-complete-js').removeClass('infobar__media-icon--tick').addClass('infobar__media-icon--alert');
							$('.is-profile-complete-js').html('!');
						}
					break;
					case 'totalFilledFields':
						$('.progress__step').removeClass('is-active');
						for (let totalFilledFields = 0; totalFilledFields < value; totalFilledFields++) {
							$('.progress__step').eq(totalFilledFields).addClass('is-active');
						}
						value = data.teacherProfileProgress.totalFilledFields + "/" + data.teacherProfileProgress.totalFields;
						$('.progress-count-js').text(value);
					break;
				}
			});
	}, { fOutMode: 'json' });
}
teacherGeneralAvailability();
})();