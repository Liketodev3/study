<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section section--grey section--page">
	 <div class="container container--fixed">
	   
	   <div class="page-panel -clearfix">
	   
			<!--panel left start here-->
			<div class="page-panel__left">
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
			</div>
            <!--panel left end here-->

	   
			<!--panel right start here-->
		   <div class="page-panel__right">
				
				 <!--page-head start here-->
				 <div class="page-head">
				   <div class="d-flex justify-content-between align-items-center">
						 <div><h1><?php echo Label::getLabel('LBL_My_Lesson_Plan'); ?></h1></div>
						 <div><a class="btn btn--secondary btn--small" href="javascript:void(0);" onclick="add(0);" >Add New Lesson</a></div>
						 
				 </div>
				 </div>
				 <!--page-head end here-->
				 
				 <!--page filters start here-->
				 <div class="page-filters">
					   <form onsubmit="searchLessons(this); return false;"  class="form form--small">
							<div class="row">
								<div class="col-md-4">
									<div class="field-set">
									   <div class="caption-wraper">
										   <label class="field_label"><?php echo Label::getLabel('LBL_Search'); ?> </label>
									   </div>
									   <div class="field-wraper">
										   <div class="field_cover"><input placeholder="<?php echo Label::getLabel('LBL_Search_by_keyword'); ?>..." value="<?php echo (!empty($keyword)) ? $keyword : ''; ?>"  name="keyword" type="text"></div>
									   </div>
								   </div>
								</div>
								<div class="col-md-4">
									<div class="field-set">
									   <div class="caption-wraper">
										   <label class="field_label"><?php echo Label::getLabel('LBL_Status'); ?> </label>
									   </div>
									   <div class="field-wraper">
										   <div class="field_cover">
											   <select name="status">
												   <option value=""><?php echo Label::getLabel('LBL_All'); ?></option>
												   <?php foreach($statusArr as $key=>$val){ ?>
												   <option <?php $status = (!empty($status)) ? $status : ''; echo ($key == $status) ? 'selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
												   <?php }?>
											   </select>
										   </div>
									   </div>
								   </div>
								</div>
								<div class="col-md-4">
									<div class="field-set">
									   <div class="caption-wraper">
										   <label class="field_label"></label>
									   </div>
									   <div class="field-wraper">
										   <div class="field_cover">
											   <input type="submit" name="submit" value="<?php echo Label::getLabel('LBL_Search'); ?>">
											   <input type="reset" id="resetFormLessonListing" >
										   </div>
									   </div>
								   </div>
								</div>

							</div>
						</form>
				   </div>
				 <!--page filters end here-->
			   
				<!--Lessons list view start here-->
				<div class="col-list-group">
					<!--h6>Today</h6-->
					<div class="col-list-container" id="listItemsLessons">
						
					</div>
				</div>
				<!--Lessons list view end here-->
			</div>
		   <!--panel right end here-->
		</div>
	 </div>
 </section>
