<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>




<section class="section">
	<div class="sectionhead">
		<h4><?php echo Label::getLabel('LBL_Teacher_Request_Detail', $adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">
		<div class="tablewrap">
			<div id="listing">
				
				<?php
					$arr_flds = array(
							'listserial'=>Label::getLabel('LBL_Sr._No',$adminLangId),
							'uqualification_experience_type'=>Label::getLabel('LBL_Type',$adminLangId),			
							'uqualification_title'=>Label::getLabel('LBL_Title',$adminLangId),			
							'uqualification_description'=>Label::getLabel('LBL_Description',$adminLangId),			
							'uqualification_institute_name'=>Label::getLabel('LBL_Institute',$adminLangId),			
						);
					$tbl = new HtmlElement('table', 
					array('width'=>'100%', 'class'=>'table table-responsive'));

					$th = $tbl->appendElement('thead')->appendElement('tr');
					foreach ($arr_flds as $val) {
						$e = $th->appendElement('th', array(), $val);
					}

					$sr_no = 0;
					foreach ($arr_listing as $sn=>$row){ 
						$sr_no++;
						$tr = $tbl->appendElement('tr');

						foreach ($arr_flds as $key=>$val){
							$td = $tr->appendElement('td');
							switch ($key){
								case 'listserial':
									$td->appendElement('plaintext', array(), $sr_no);
								break;
								case 'uqualification_experience_type':
									$td->appendElement('plaintext', array(), UserQualification::getExperienceTypeArr($adminLangId)[$row['uqualification_experience_type']].'<br/>'.$row['uqualification_start_year'] .'-'.$row['uqualification_end_year'],true);
								break;
								
								case 'uqualification_institute_name':
									$td->appendElement('plaintext', array(), $row['uqualification_institute_name'].'<br/>'.$row['uqualification_institute_address'], true);
								break;
								case 'uqualification_description':
									$td->appendElement('plaintext', array(), nl2br($row['uqualification_description']), true);
								break;
								default:
									$td->appendElement('plaintext', array(), $row[$key],true);
								break;
							}
						}
					}
					if (count($arr_listing) == 0){
						$tbl->appendElement('tr')->appendElement('td', array(
						'colspan'=>count($arr_flds)), 
						Label::getLabel('LBL_No_Records_Found',$adminLangId)
						);
					}
					echo $tbl->getHtml();
					?>
				
			</div>
		</div>		
	</div>
</section>