<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
    <div class="page-block__left">
	<nav class="steps steps--vertical">
		<ul>
			<li class="steps__item is-process change-block-js" data-blocks-show="1">
				<a href="javascript:void(0)">
					<span class="step__icon"></span>
					<?php echo Label::getLabel('LBL_Personal_Info',$siteLangId); ?>
				</a>
			</li>
			<li class="steps__item" data-blocks-show="2">
				<a href="javascript:void(0)">
					<span class="step__icon"></span>
					<?php echo Label::getLabel('LBL_Profile_Media',$siteLangId); ?>
				</a>
			</li>
			<li class="steps__item" data-blocks-show="3">
				<a href="javascript:void(0)">
					<span class="step__icon"></span>
					<?php echo Label::getLabel('LBL_Languages',$siteLangId); ?>
				</a>
			</li>
			<li class="steps__item" data-blocks-show="4">
				<a href="javascript:void(0)">
					<span class="step__icon"></span>
					<?php echo Label::getLabel('LBL_Resume',$siteLangId); ?>
				</a>
			</li>
			<li class="steps__item" data-blocks-show="5">
				<a href="javascript:void(0)">
					<span class="step__icon"></span>
					<?php echo Label::getLabel('LBL_Confirmation',$siteLangId); ?>
				</a>
			</li>
		</ul>
	</nav>
</div>