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

$('.change-block-js').click(function(e){
	$('.change-block-js').removeClass('is-process');
	var showBlock = $(this).attr('data-blocks-show');
	$(this).addClass('is-process');
	$('.page-block__body').hide();
	$('#block--'+showBlock).show();
})

$('.btn-Back').click(function(e){
	var blockId = parseInt($('.is-process').attr('data-blocks-show'))-1;
	$('.change-block-js').removeClass('is-process');
	$('li[data-blocks-show="'+blockId+'"]').addClass('is-process');
	$('.page-block__body').hide();
	$('#block--'+blockId).show();
});
$('.btn-next').click(function(e){
	var blockId = parseInt($('.is-process').attr('data-blocks-show'))+1;
	$('.change-block-js').removeClass('is-process');
	$('li[data-blocks-show="'+blockId+'"]').addClass('is-process');
	$('.page-block__body').hide();
	$('#block--'+blockId).show();
});

if($(window).width()>1199){
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
		input.addEventListener("countrychange",function() {
			var dial_code = $.trim($('.iti__selected-dial-code').text());
			setPhoneNumberMask();
            $('#user_phone_code').val(dial_code);
        });
      
		var telInput =  window.intlTelInput(input, {
            separateDialCode: true,
			initialCountry: "us",
            utilsScript: siteConstants.webroot+"js/utils.js",
        });


		changeProficiency = function (obj, langId) {
		langId = parseInt(langId);
		if (langId <= 0) {
			return;
		}
		let value = obj.value;
		slanguageSection = '.slanguage-' + langId;
		slanguageCheckbox = '.slanguage-checkbox-' + langId;
		if (value == '') {
			$(slanguageSection).find('.badge-js').remove();
			$(slanguageSection).removeClass('is-selected');
			$(slanguageCheckbox).prop('checked', false);
		} else {
			$(slanguageSection).addClass('is-selected');
			$(slanguageCheckbox).prop('checked', true);
			$(slanguageSection).find('.badge-js').remove();
			$(slanguageSection).find('.selection__trigger-label').append('<span class="badge color-secondary badge-js  badge--round badge--small margin-0">' + obj.selectedOptions[0].innerHTML + '</span>');
		}
	};

</script>
