<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php if( !empty($flashCards) ) { ?>
<div class="-padding-20">
<table class="table">
   <tr class="-hide-mobile">
	  <th><?php echo Label::getLabel('LBL_Word'); ?></th>
	  <th><?php echo Label::getLabel('LBL_Definition'); ?></th>
	  <th></th>
   </tr>
   <?php foreach($flashCards as $flashCard){ ?>
   <tr>
	  <td width="35%">
		 <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Word'); ?></span>
		 <span class="td__data"><?php echo $flashCard['flashcard_title']; echo " (".$flashCard['wordLanguageCode'].")";?></span>
	  </td>
	  
	  <td width="35%">
		 <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Definition'); ?></span>
		 <span class="td__data"><?php echo $flashCard['flashcard_defination'];  echo " (".$flashCard['wordDefLanguageCode'].")"; ?></span>
	  </td>
	  
	  <td>
		 <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Action'); ?></span>
		 <span class="td__data">
         <?php if($myteacher == 0){ ?>
			<a href="javascript:void(0)" onclick="flashCardForm(<?php echo $flashCard['sflashcard_slesson_id'] ?>, <?php echo $flashCard['flashcard_id']; ?>)" class="btn btn--small btn--secondary  btn--action">
			   <span class="svg-icon">
				  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 485.219 485.22" style="enable-background:new 0 0 485.219 485.22;" xml:space="preserve">
					 <g>
						<path d="M467.476,146.438l-21.445,21.455L317.35,39.23l21.445-21.457c23.689-23.692,62.104-23.692,85.795,0l42.886,42.897   C491.133,84.349,491.133,122.748,467.476,146.438z M167.233,403.748c-5.922,5.922-5.922,15.513,0,21.436   c5.925,5.955,15.521,5.955,21.443,0L424.59,189.335l-21.469-21.457L167.233,403.748z M60,296.54c-5.925,5.927-5.925,15.514,0,21.44   c5.922,5.923,15.518,5.923,21.443,0L317.35,82.113L295.914,60.67L60,296.54z M338.767,103.54L102.881,339.421   c-11.845,11.822-11.815,31.041,0,42.886c11.85,11.846,31.038,11.901,42.914-0.032l235.886-235.837L338.767,103.54z    M145.734,446.572c-7.253-7.262-10.749-16.465-12.05-25.948c-3.083,0.476-6.188,0.919-9.36,0.919   c-16.202,0-31.419-6.333-42.881-17.795c-11.462-11.491-17.77-26.687-17.77-42.887c0-2.954,0.443-5.833,0.859-8.703   c-9.803-1.335-18.864-5.629-25.972-12.737c-0.682-0.677-0.917-1.596-1.538-2.338L0,485.216l147.748-36.986   C147.097,447.637,146.36,447.193,145.734,446.572z" fill="#FFFFFF"/>
					 </g>
				  </svg>
			   </span>
			</a>
			<a href="javascript:void(0);" onclick="removeFlashcard(<?php echo $flashCard['flashcard_id']; ?>);" class="btn btn--small btn--action">
			   <span class="svg-icon">
				  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" >
					 <g>
						<g>
						   <path d="M425.298,51.358h-91.455V16.696c0-9.22-7.475-16.696-16.696-16.696H194.854c-9.22,0-16.696,7.475-16.696,16.696v34.662    H86.703c-9.22,0-16.696,7.475-16.696,16.696v51.357c0,9.22,7.475,16.696,16.696,16.696h338.593c9.22,0,16.696-7.475,16.696-16.696    V68.054C441.993,58.832,434.518,51.358,425.298,51.358z M300.45,51.358h-88.9V33.391h88.9V51.358z" fill="#FFFFFF"/>
						</g>
					 </g>
					 <g>
						<g>
						   <path d="M93.192,169.497l13.844,326.516c0.378,8.937,7.735,15.988,16.68,15.988h264.568c8.945,0,16.302-7.051,16.68-15.989    l13.843-326.515H93.192z M205.53,444.105c0,9.22-7.475,16.696-16.696,16.696c-9.22,0-16.696-7.475-16.696-16.696V237.391    c0-9.22,7.475-16.696,16.696-16.696c9.22,0,16.696,7.475,16.696,16.696V444.105z M272.693,444.105    c0,9.22-7.475,16.696-16.696,16.696s-16.696-7.475-16.696-16.696V237.391c0-9.22,7.475-16.696,16.696-16.696    s16.696,7.475,16.696,16.696V444.105z M339.856,444.105c0,9.22-7.475,16.696-16.696,16.696s-16.696-7.475-16.696-16.696V237.391    c0-9.22,7.475-16.696,16.696-16.696s16.696,7.475,16.696,16.696V444.105z" fill="#FFFFFF"/>
						</g>
					 </g>
				  </svg>
			   </span>
			</a>
   <?php } ?>
		 <a href="javascript:void(0);" onclick="viewFlashCard(<?php echo $flashCard['flashcard_id']; ?>)" class="btn btn--small btn--action">
		 <span class="svg-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
	<g>
		<path fill="#FFFFFF" d="M508.177,245.995C503.607,240.897,393.682,121,256,121S8.394,240.897,3.823,245.995c-5.098,5.698-5.098,14.312,0,20.01
			C8.394,271.103,118.32,391,256,391s247.606-119.897,252.177-124.995C513.274,260.307,513.274,251.693,508.177,245.995z M256,361
			c-57.891,0-105-47.109-105-105s47.109-105,105-105s105,47.109,105,105S313.891,361,256,361z"/>
	</g>
</g>
<g>
	<g>
		<path fill="#FFFFFF" d="M271,226c0-15.09,7.491-28.365,18.887-36.53C279.661,184.235,268.255,181,256,181c-41.353,0-75,33.647-75,75
			c0,41.353,33.647,75,75,75c37.024,0,67.668-27.034,73.722-62.358C299.516,278.367,271,255.522,271,226z"/>
	</g>
</g>
</svg></span>
		 </a> </span>
		
	  </td>
   </tr>
   <?php } ?>
</table>
</div>

<?php 
echo FatUtility::createHiddenFormFromData ( $postedData, array (
	'name' => 'frmFlashCardSearchPaging'
) );
$pagingArr['callBackJsFunc'] = 'goToFlashCardSearchPage';
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>


<?php } else { ?>  

<div class="box -padding-30" style="margin-bottom: 30px;">
	<div class="message-display">
		<div class="message-display__icon">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
			<path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
			</svg>
		</div>
		<h5><?php echo Label::getLabel('LBL_No_Result_Found!!'); ?></h5>
	</div>
</div>
<?php } ?> 