<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20">

	<div class="table-scroll">
	 <table class="table">
		 <tbody><tr class="-hide-mobile">
			<th><?php echo Label::getLabel('LBL_Sr_No.'); ?></th>
			<th><?php echo Label::getLabel('LBL_Title'); ?></th>
			<th><?php echo Label::getLabel('LBL_Image'); ?></th>
			<th><?php echo Label::getLabel('LBL_View_Plans'); ?></th>
			<th><?php echo Label::getLabel('LBL_Description'); ?></th>
			<th><?php echo Label::getLabel('LBL_Tags'); ?></th>
			<th><?php echo Label::getLabel('LBL_Level'); ?></th>
			<th><?php echo Label::getLabel('LBL_Actions'); ?></th>
		</tr>
		<?php $i=1; foreach($teacherCoursesData as $teacherCourseData){ ?>
		<tr>
			<td>
               	<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Sr_No.'); ?></span>
				<span class="td__data"><?php echo $i; ?></span>
			</td>
			<td>
                <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Title'); ?></span>
				<span class="td__data"><?php echo $teacherCourseData['tcourse_title']; ?></span>
			</td>
			<td>
            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Image'); ?></span>
            <?php
			$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_TEACHER_COURSE_IMAGE,$teacherCourseData['tcourse_id'],0);
			if(empty($file_row))
			{ 
				echo CommonHelper::displayNotApplicable('');
			}else{?>
				<span class="td__data"><img src="<?php echo CommonHelper::generateFullUrl('TeacherCourses','teacherCourseImage',array($teacherCourseData['tcourse_id'],'THUMB')).'?'.time();  ?>" /></span>
			<?php } ?>
			</td>
			<td>
            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_View_Plans'); ?></span>
            <span class="td__data">
			<?php if(LessonPlan::countPlans($teacherCourseData['tcourse_id']) > 0 ){ ?>
			<input class="btn btn--primary btn--small" type="button" onclick="viewAssignedPlans('<?php echo $teacherCourseData['tcourse_id'] ;?>')" value="View Plans" />
			<?php } else { echo CommonHelper::displayNotApplicable(''); } ?>
            </span>
			</td>
			<td width="25%">
                <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Description'); ?></span>
				<span class="td__data">
					<?php echo substr($teacherCourseData['tcourse_description'],0,150); ?>
				</span>
			</td>
			<td width="25%">
            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Tags'); ?></span>
				<span class="td__data">
				<ul class="tags-inline">
				<?php $tags =  explode(",",$teacherCourseData['tcourse_tags']);
				if(!empty($tags[0])){
						foreach($tags as $tag){
							echo '<li><a><span>'.$tag.'&nbsp;&nbsp;</a></li>';
						}
				}else{
					echo '<span>'.Label::getLabel('LBL_N/A').'</span>';
				}
					?>
					</ul>
				</span>
			</td>
			<td>
                <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Level'); ?></span>
				<span class="td__data"><?php echo $statusArr[$teacherCourseData['tcourse_level']]; ?></span>
			</td>

			<td>
                <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Actions'); ?></span>
                <span class="td__data">
				<a class="btn btn--secondary btn--small"  href="javascript:void(0);" onclick="add('<?php echo $teacherCourseData['tcourse_id'];  ?>');">Edit</a>
				<a class="btn btn--small"  href="javascript:void(0);" onclick="remove('<?php echo $teacherCourseData['tcourse_id'];  ?>');">Delete</a>
                </span>
			</td>
			
		</tr>
		<?php $i++; } ?>
		
	 </tbody></table>
	 </div>
	 <?php if($countData == 0) { ?>
				<div class="box -padding-30" style="margin-bottom: 30px;">
					<div class="message-display">
						<div class="message-display__icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
	  <path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
	</svg>
						</div>

						<h5><?php echo Label::getLabel('LBL_No_Result_Found!!'); ?></h5>
						<a href="#" class="btn btn--primary btn--wide btn--large"><?php echo Label::getLabel('LBL_Search_Again'); ?></a>
					</div>
				</div>
	<?php } ?>
</div>