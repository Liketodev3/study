<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$frmTeacherSrch->setFormTagAttribute ( 'onSubmit', 'searchTeachers(this); return(false);' );
echo $frmTeacherSrch->getFormTag();

$frmTeacherSrch->getField( 'teach_language_name')->setFieldTagAttribute('class', 'form__input');
$frmTeacherSrch->getField( 'teach_availability' )->setFieldTagAttribute('class', 'form__input form__input-js');
$frmTeacherSrch->getField( 'teach_availability' )->setFieldTagAttribute('autocomplete', 'off');
$frmTeacherSrch->getField( 'teach_availability' )->setFieldTagAttribute('readonly', 'readonly');
$frmTeacherSrch->getField( 'keyword' )->setFieldTagAttribute('class', 'form__input');
$frmTeacherSrch->getField( 'btnTeacherSrchSubmit' )->setFieldTagAttribute('class', 'form__action');
?>
<section class="section section--search">
    <div class="container container--fixed">
        <div class="row justify-content-between">
            <div class="col-xl-7 col-lg-7 col-md-7">
                <div class="form-filters">
                    <div class="form__element">
                        <span class="svg-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="14.843" viewBox="0 0 14.844 14.843">
							<path d="M251.286,196.714a4.008,4.008,0,1,1,2.826-1.174A3.849,3.849,0,0,1,251.286,196.714Zm8.241,2.625-3.063-3.062a6.116,6.116,0,0,0,1.107-3.563,6.184,6.184,0,0,0-.5-2.442,6.152,6.152,0,0,0-3.348-3.348,6.271,6.271,0,0,0-4.884,0,6.152,6.152,0,0,0-3.348,3.348,6.259,6.259,0,0,0,0,4.884,6.152,6.152,0,0,0,3.348,3.348,6.274,6.274,0,0,0,6-.611l3.063,3.053a1.058,1.058,0,0,0,.8.34,1.143,1.143,0,0,0,.813-1.947h0Z" transform="translate(-245 -186.438)"/>
							</svg>
						</span>
						<?php 
						echo $frmTeacherSrch->getFieldHtml('teach_language_name'); 
						echo $frmTeacherSrch->getFieldHtml( 'teach_language_id' );
						?>
						
                    </div>

                    <div class="form__element form__element-js">
						<span class="svg-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="16" viewBox="0 0 14.844 16">
							<path d="M563.643,153.571h2.571v2.572h-2.571v-2.572Zm3.143,0h2.857v2.572h-2.857v-2.572Zm-3.143-3.428h2.571V153h-2.571v-2.857Zm3.143,0h2.857V153h-2.857v-2.857ZM563.643,147h2.571v2.571h-2.571V147Zm6.571,6.571h2.857v2.572h-2.857v-2.572ZM566.786,147h2.857v2.571h-2.857V147Zm6.857,6.571h2.571v2.572h-2.571v-2.572Zm-3.429-3.428h2.857V153h-2.857v-2.857Zm-3.227-4.656a0.278,0.278,0,0,1-.2.084h-0.572a0.287,0.287,0,0,1-.285-0.285v-2.572a0.287,0.287,0,0,1,.285-0.285h0.572a0.287,0.287,0,0,1,.285.285v2.572A0.278,0.278,0,0,1,566.987,145.487Zm6.656,4.656h2.571V153h-2.571v-2.857ZM570.214,147h2.857v2.571h-2.857V147Zm3.429,0h2.571v2.571h-2.571V147Zm0.2-1.513a0.278,0.278,0,0,1-.2.084h-0.572a0.289,0.289,0,0,1-.285-0.285v-2.572a0.289,0.289,0,0,1,.285-0.285h0.572a0.289,0.289,0,0,1,.286.285v2.572A0.279,0.279,0,0,1,573.844,145.487Zm3.174-1.576a1.1,1.1,0,0,0-.8-0.34h-1.143v-0.857a1.431,1.431,0,0,0-1.428-1.428h-0.572a1.432,1.432,0,0,0-1.428,1.428v0.857h-3.429v-0.857a1.431,1.431,0,0,0-1.428-1.428h-0.572a1.431,1.431,0,0,0-1.428,1.428v0.857h-1.143a1.16,1.16,0,0,0-1.143,1.143v11.429a1.16,1.16,0,0,0,1.143,1.143h12.571a1.16,1.16,0,0,0,1.143-1.143V144.714A1.1,1.1,0,0,0,577.018,143.911Z" transform="translate(-562.5 -141.281)"/>
							</svg>
						</span>
						<?php echo $frmTeacherSrch->getFieldHtml( 'teach_availability' ); ?>
								<div class="form__target -skin -padding-20">
                                     <div class="row">
                                         <div class="col-6">
                                             <p><strong>Days of the Week</strong></p>
                                             <div class="listing listing--vertical">
											 <div class="block__head-trigger-js" style="display:none;">Availibility</div>
												 <div class="block__body-target-js">
												 <ul>
													<?php foreach($daysArr as $dayId => $dayName ){ ?>
													<li>
														<label class="checkbox" id="weekDays_<?php echo $dayId; ?>">
															<input type="checkbox" name="filterWeekDays[]" value="<?php echo $dayId; ?>">
															<i class="input-helper"></i> <?php echo $dayName; ?>
														</label>
													</li>
													<?php } ?>
												</ul>
												</div>
                                            </div>
                                         </div>
                                         <div class="col-6">
                                             <p><strong>Time of Day - 24hrs</strong></p>
                                             <div class="listing listing--vertical">
											 <div class="block__head-trigger-js" style="display:none;">Availibility</div>
												 <div class="block__body-target-js">											 
												<ul>
												 <?php foreach($timeSlotArr as $timeSlotId => $timeSlotName ){ ?>
													<li>
														<label class="checkbox" id="timeSlots_<?php echo $timeSlotId; ?>">
															<input type="checkbox" name="filterTimeSlots[]" value="<?php echo $timeSlotId; ?>">
															<i class="input-helper"></i> <?php echo $timeSlotName; ?>
														</label>
													</li>
													<?php } ?>
												</ul>	
                                            </div>
                                            </div>
                                         </div>
                                     </div>
                                 </div>						
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="form-search">
                    <div class="form__element">
						<?php echo $frmTeacherSrch->getFieldHtml( 'keyword' ); ?>
                        <span class="form__action-wrap">
							<?php 
							echo $frmTeacherSrch->getFieldHtml('page');
							echo $frmTeacherSrch->getFieldHtml( 'btnTeacherSrchSubmit' ); ?>
							<span class="svg-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="14.843" viewBox="0 0 14.844 14.843">
								<path d="M251.286,196.714a4.008,4.008,0,1,1,2.826-1.174A3.849,3.849,0,0,1,251.286,196.714Zm8.241,2.625-3.063-3.062a6.116,6.116,0,0,0,1.107-3.563,6.184,6.184,0,0,0-.5-2.442,6.152,6.152,0,0,0-3.348-3.348,6.271,6.271,0,0,0-4.884,0,6.152,6.152,0,0,0-3.348,3.348,6.259,6.259,0,0,0,0,4.884,6.152,6.152,0,0,0,3.348,3.348,6.274,6.274,0,0,0,6-.611l3.063,3.053a1.058,1.058,0,0,0,.8.34,1.143,1.143,0,0,0,.813-1.947h0Z" transform="translate(-245 -186.438)"/>
								</svg>
							</span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<div class="section__tags">
	<div class="container container--fixed">
		<div class="tag-list">
			<ul id="searched-filters">
				
			</ul>
		</div>
	</div>
</div>

		 
</form>