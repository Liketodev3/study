<?php 

$frmSrch->setFormTagAttribute('onSubmit','threadListing(this); return false;');
$frmSrch->setFormTagAttribute('class', 'form form--small'); 

$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
$frmSrch->developerTags['fld_default_col'] = 12;

$fld = $frmSrch->getField('keyword');
$fld->setWrapperAttribute('class','col-md-12');

$fld = $frmSrch->getField('is_unread');
$fld->setWrapperAttribute('class','col-md-12');

$submitFld = $frmSrch->getField('btn_submit');
$submitFld->setWrapperAttribute('class','col-md-12'); ?>

         <section class="section section--grey section--page">
             <div class="container container--fixed">
               
               <div class="page-panel -clearfix">
			  <div class="page-panel__left">
	   <!--div class="tab-swticher">
			<a href="dashboard.html" class="btn btn--large is-active">Teacher</a>
			<a href="learner_dashboard.html" class="btn btn--large">Student</a>
		</div-->
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>		
		</div>                  
				<div class="page-panel__right">		
                       <!--message screen start here-->
                       <div class="window">
                           <div class="window__left">
                             
                             <div class="window__search">
                                 <a href="javascript:void(0)" class="window__search-field window__search-field-js">Search...</a>
                                 <div class="window__search-form window__search-form-js">
                                    <a href="javascript:void(0)" class="-link-close window__search-field-js"></a>
									<?php	echo $frmSrch->getFormHtml(); ?>
                                 </div>
                             </div>
                             
                              
                               <div class="scrollbar scrollbar-js">
                                   <div class="msg-list-container" id="threadListing">
                                   </div>
                               </div>
                               
                           </div>
                           
                           <div class="window__right">
                                <div class="message-display message-display--positioned">
                                    <div class="message-display__icon">
                                        <svg viewBox="0 -26 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m256 100c-5.519531 0-10 4.480469-10 10s4.480469 10 10 10 10-4.480469 10-10-4.480469-10-10-10zm0 0"/><path d="m90 280c5.519531 0 10-4.480469 10-10s-4.480469-10-10-10-10 4.480469-10 10 4.480469 10 10 10zm0 0"/><path d="m336 0c-90.027344 0-163.917969 62.070312-169.632812 140.253906-85.738282 4.300782-166.367188 66.125-166.367188 149.746094 0 34.945312 13.828125 68.804688 39 95.632812 4.980469 20.53125-1.066406 42.292969-16.070312 57.296876-2.859376 2.859374-3.714844 7.160156-2.167969 10.898437 1.546875 3.734375 5.191406 6.171875 9.238281 6.171875 28.519531 0 56.003906-11.183594 76.425781-30.890625 19.894531 6.78125 45.851563 10.890625 69.574219 10.890625 90.015625 0 163.898438-62.054688 169.628906-140.222656 20.9375-.929688 42.714844-4.796875 59.945313-10.667969 20.421875 19.707031 47.90625 30.890625 76.425781 30.890625 4.046875 0 7.691406-2.4375 9.238281-6.171875 1.546875-3.738281.691407-8.039063-2.167969-10.898437-15.003906-15.003907-21.050781-36.765626-16.070312-57.296876 25.171875-26.828124 39-60.6875 39-95.632812 0-86.886719-86.839844-150-176-150zm-160 420c-23.601562 0-50.496094-4.632812-68.511719-11.800781-3.859375-1.539063-8.269531-.527344-11.078125 2.539062-12.074218 13.199219-27.773437 22.402344-44.878906 26.632813 9.425781-18.058594 11.832031-39.347656 6.097656-59.519532-.453125-1.589843-1.292968-3.042968-2.445312-4.226562-22.6875-23.367188-35.183594-53.066406-35.183594-83.625 0-70.46875 71.4375-130 156-130 79.851562 0 150 55.527344 150 130 0 71.683594-67.289062 130-150 130zm280.816406-186.375c-1.152344 1.1875-1.992187 2.640625-2.445312 4.226562-5.734375 20.171876-3.328125 41.460938 6.097656 59.519532-17.105469-4.226563-32.804688-13.433594-44.878906-26.632813-2.808594-3.0625-7.21875-4.078125-11.078125-2.539062-15.613281 6.210937-37.886719 10.511719-58.914063 11.550781-2.921875-37.816406-21.785156-73.359375-54.035156-99.75h130.4375c5.523438 0 10-4.476562 10-10s-4.476562-10-10-10h-161.160156c-22.699219-11.554688-48.1875-18.292969-74.421875-19.707031 5.746093-67.164063 70.640625-120.292969 149.582031-120.292969 84.5625 0 156 59.53125 156 130 0 30.558594-12.496094 60.257812-35.183594 83.625zm0 0"/><path d="m256 260h-126c-5.523438 0-10 4.476562-10 10s4.476562 10 10 10h126c5.523438 0 10-4.476562 10-10s-4.476562-10-10-10zm0 0"/><path d="m256 320h-166c-5.523438 0-10 4.476562-10 10s4.476562 10 10 10h166c5.523438 0 10-4.476562 10-10s-4.476562-10-10-10zm0 0"/><path d="m422 100h-126c-5.523438 0-10 4.476562-10 10s4.476562 10 10 10h126c5.523438 0 10-4.476562 10-10s-4.476562-10-10-10zm0 0"/></svg>
                                    </div>

                                    <p class="-color-light">Click on messages to see details.</p>

                                </div>  
                                
                                <div class="message-details-dv">
                                </div>
                           </div>
                           
                       </div>
                       </div>
                       <!--message screen end here-->
                       
                       
               </div>
               
               
             </div>
         </section>		 