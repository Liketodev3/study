<div class="section-head">
	 <div class="d-flex justify-content-between align-items-center">
		 <div><h4 class="page-heading"><?php echo Label::getLabel('LBL_Experience'); ?></h4></div>
		 <div>
			<a href="javascript:void(0);" onclick="teacherQualificationForm(0);" class="btn btn--secondary btn--small"><?php echo Label::getLabel('LBL_Add_New'); ?></a>
		 </div>
	 </div>
</div>

<div class="row">
	 <div class="col-md-12">
		 <div class="table-scroll">
			 <table class="table">
	 <tr class="-hide-mobile">
		<th><?php echo Label::getLabel('LBL_Resume_Information'); ?></th>
		<th><?php echo Label::getLabel('LBL_Start/End'); ?> </th>
		<th><?php echo Label::getLabel('LBL_Uploaded_Certificate'); ?>  </th>
		<th><?php echo Label::getLabel('LBL_Actions'); ?> </th>
	</tr>
	<?php foreach($qualificationData as $qualificationData){ ?>
	<tr>
		<td width="35%">
			<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Title'); ?></span>
			<span class="td__data">
				<div class="data-group">
					<h6><?php echo $qualificationData['uqualification_title']; ?></h6>
					<p><?php echo Label::getLabel('LBL_Location').' - '.$qualificationData['uqualification_institute_address']; ?></p>
					<p><?php echo Label::getLabel('LBL_Institution').' - '.$qualificationData['uqualification_institute_name']; ?></p>
				</div>
			</span>
		</td>
		
		<td>
			<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Start/End'); ?></span>
			<span class="td__data"><?php echo $qualificationData['uqualification_start_year']; ?> - <?php echo $qualificationData['uqualification_end_year']; ?></span>
		</td>
		
		<td>
			<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Uploaded_Certificate'); ?> </span>
			<?php if(empty($qualificationData['afile_name']))
			{ 
				echo CommonHelper::displayNotApplicable('');
			}else{ ?>
			<a download="<?php echo $qualificationData['afile_name']; ?>" target="_blank" href="<?php echo CommonHelper::generateFullUrl('Teacher','qualificationFile',array(0,$qualificationData['uqualification_id'])) ?>">
			<span class="td__data">
				
				  
				<div class="attachment-file">
				 <span class="inline-icon -display-inline -color-fill">
					 <span class="svg-icon">
					   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 511.998 511.998" style="enable-background:new 0 0 511.998 511.998;" xml:space="preserve">
<g>
<g>
<path d="M464.059,61.565c-63.814-63.814-167.651-63.814-231.467,0L34.181,259.973c-45.578,45.584-45.575,119.754,0.008,165.335
c22.793,22.793,52.723,34.189,82.665,34.186c29.934-0.003,59.878-11.396,82.668-34.186l181.87-181.872
c27.352-27.346,27.355-71.848,0.003-99.204c-27.352-27.349-71.856-27.348-99.202,0.005L163.258,263.168
c-9.131,9.13-9.131,23.935-0.002,33.067c9.133,9.131,23.935,9.131,33.068,0l118.937-118.934
c9.116-9.117,23.951-9.117,33.067-0.003c9.116,9.119,9.116,23.951-0.003,33.068L166.457,392.241
c-27.352,27.348-71.852,27.352-99.202,0.002c-27.349-27.351-27.352-71.853-0.006-99.204L265.659,94.632
c45.583-45.579,119.752-45.581,165.335,0.002c22.082,22.08,34.244,51.439,34.242,82.666c0,31.228-12.16,60.586-34.245,82.668
L232.587,458.379c-9.131,9.131-9.131,23.935,0.002,33.067c4.566,4.566,10.55,6.848,16.533,6.848
c5.983,0,11.968-2.284,16.534-6.848l198.401-198.409c30.916-30.913,47.941-72.015,47.941-115.735
C512.001,133.58,494.975,92.478,464.059,61.565z"/>
</g>
</g>
</svg>
					 </span>
				 </span>
				<?php echo $qualificationData['afile_name']; ?></div>
			</span>
			</a>
				<?php } ?>
		</td>
		<td>
			<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Actions'); ?><?php echo Label::getLabel('LBL_Action'); ?></span>
			<span class="td__data">
				<a href="javascript:void(0);" onclick="teacherQualificationForm('<?php echo $qualificationData['uqualification_id']; ?>');" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Edit'); ?></a>
				<a href="javascript:void(0);" onclick="deleteTeacherQualification('<?php echo $qualificationData['uqualification_id']; ?>');" class="btn btn--small"><?php echo Label::getLabel('LBL_Delete'); ?></a>
			</span>
		</td>
		
	</tr>
	<?php } ?>
  
 </table>
		 </div>
	 </div>
</div>
