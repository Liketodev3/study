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
	// $(document).on('click','.change-block-js',function(e) {
	// 	$('.change-block-js').removeClass('is-process');
	// 	var showBlock = $(this).attr('data-blocks-show');
	// 	$(this).addClass('is-process');
	// 	$('.page-block__body').hide();
	// 	$('#block--' + showBlock).show();
	// })

	$(document).on('click', '.btn-Back', function() {
		var blockId = parseInt($('.is-process').attr('data-blocks-show')) - 1;
		$('.change-block-js').removeClass('is-process');
		$('li[data-blocks-show="' + blockId + '"]').addClass('is-process');
		$('.page-block__body').hide();
		$('#block--' + blockId).show();
		return false;
	});

</script>