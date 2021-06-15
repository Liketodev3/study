<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>


<footer class="footer">
	<div class="section-copyright">
		<div class="container container--narrow">
			<div class="copyright">
				<p>Copyright © 2021 Yo!Coach. Developed by <a class="copyright-action" href="#"> FATbit Technologies.</a></p>
			</div>
		</div>
	</div>

</footer>

<script>
	$(document).on('click','.change-block-js',function(e) {
		$('.change-block-js').removeClass('is-process');
		var showBlock = $(this).attr('data-blocks-show');
		$(this).addClass('is-process');
		$('.page-block__body').hide();
		$('#block--' + showBlock).show();
	})

	$(document).on('click', '.btn-Back', function() {
		var blockId = parseInt($('.is-process').attr('data-blocks-show')) - 1;
		$('.change-block-js').removeClass('is-process');
		$('li[data-blocks-show="' + blockId + '"]').addClass('is-process');
		$('.page-block__body').hide();
		$('#block--' + blockId).show();
		return false;
	});
	$(document).on('click', '.btn--next', function() {

		//var flag = true
		var blockId = parseInt($('.is-process').attr('data-blocks-show'))+1;
		var elements = $("#block--" + blockId).find('input[data-fatreq]').each(function() {
			var value = $(this).val();
			var validation_rules = JSON.parse($(this).attr('data-fatreq')); $(this).data('fatreq')
			$.each(validation_rules, function(key, val) {
				flag = $.Validation.getRule(key).check(val, value);
			});
		});
		console.log(flag);
		if(!flag){
			alert('validation failed');
			
		}
		// return false;
		$('.change-block-js').removeClass('is-process');
		$('li[data-blocks-show="' + blockId + '"]').addClass('change-block-js');
		$('li[data-blocks-show="' + blockId + '"]').addClass('is-process');
		$('.page-block__body').hide();
		$('#block--' + blockId).show();
		return false;
	});

	if ($(window).width() > 1199) {
		$('.scrollbar-js').enscroll({
			verticalTrackClass: 'scrollbar-track',
			verticalHandleClass: 'scrollbar-handle'
		});
	}

	var countryData = window.intlTelInputGlobals.getCountryData();
	for (var i = 0; i < countryData.length; i++) {
		var country = countryData[i];
		country.name = country.name.replace(/ *\([^)]*\) */g, "");
	}

	var input = document.querySelector("#user_phone");
	$("#user_phone").inputmask();
	input.addEventListener("countrychange", function() {
		var dial_code = $.trim($('.iti__selected-dial-code').text());
		setPhoneNumberMask();
		$('#user_phone_code').val(dial_code);
	});

	var telInput = window.intlTelInput(input, {
		separateDialCode: true,
		initialCountry: "us",
		utilsScript: siteConstants.webroot + "js/utils.js",
	});




	setPhoneNumberMask = function() {
		let placeholder = $("#user_phone").attr("placeholder");
		if (placeholder) {
			placeholderlength = placeholder.length;
			placeholder = placeholder.replace(/[0-9.]/g, '9');
			$("#user_phone").inputmask({
				"mask": placeholder
			});
		}
	};


	$(document).ready(function() {
		var dial_code = $.trim($('.iti__selected-dial-code').text());
		$('#user_phone_code').val(dial_code);
		setTimeout(() => {
			setPhoneNumberMask();
		}, 100);
	});
</script>