<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="table-scroll">
			
			<?php
				if( count($rows) ){
					$arrFlds = array(
						'title'	=>	Label::getLabel('LBL_Resume_Information'),
						'period'	=>	Label::getLabel('LBL_Start/End'),
						'file'	=>	Label::getLabel('LBL_Certificate'),
						'action'	=>	Label::getLabel('LBL_Action'),
					);
					$tbl = new HtmlElement('table', array('class'=>'table'));
					$tbody = $tbl->appendElement('tbody', array('class' => '-hide-mobile') )->appendElement('tr');
					foreach ( $arrFlds as $val ) {
						$e = $tbody->appendElement('th', array(), $val );
					}
					
					//$userId = UserAuthentication::getLoggedUserId();
					foreach ( $rows as $row ){
						
						$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $userId ,$row['uqualification_id'] );
						
						$tr = $tbody->appendElement('tr',array());
						foreach ($arrFlds as $key => $val ){
							$td = $tr->appendElement('td');
							switch ($key){
								case 'title':
									$td->setAttribute( 'width', '35%' );
									$td->appendElement('span', array('class' => 'td__caption -hide-desktop -show-mobile'), Label::getLabel('LBL_Resume_Information'));
									
									$span = $td->appendElement('span', array('class' => 'td__data'));
									$divInsideSpan = $span->appendElement('div', array('class' => 'data-group' ),'' );
									$divInsideSpan->appendElement( 'h6', array(), $row['uqualification_title'] );
									$divInsideSpan->appendElement( 'p', array(), Label::getLabel('LBL_Location').' - '. $row['uqualification_institute_address'] );
									$divInsideSpan->appendElement( 'p', array(), Label::getLabel('LBL_Institution').' - '. $row['uqualification_institute_name'] );
								break;
								
								case 'period':
									$td->appendElement('span', array('class' => 'td__caption -hide-desktop -show-mobile'), Label::getLabel('LBL_Start/End'));
									$span = $td->appendElement('span', array('class' => 'td__data'), $row['uqualification_start_year'].' - '.$row['uqualification_end_year'] );
								break;
								
								case 'file':
									$td->appendElement('span', array('class' => 'td__caption -hide-desktop -show-mobile'), Label::getLabel('LBL_Certificate'));
									$span = $td->appendElement('span', array('class' => 'td__data'), '' );
									
									if( !empty($file_row['afile_name']) ){
										
										$a = $span->appendElement( 'a', array('href' => CommonHelper::generateUrl('TeacherRequest', 'downloadResume', array($file_row['afile_record_subid']) ) ), '' );
										
										$divInsideSpan = $a->appendElement('div', array('class' => 'attachment-file' ),'' );
										
										$spanInside_DivInsideSpan = $divInsideSpan->appendElement( 'div', array('class' => 'inline-icon -display-inline -color-fill' ) );
										$svgSpan = $spanInside_DivInsideSpan->appendElement( 'span', array('class' => 'svg-icon') );
										$svgSpan->appendElement('plaintext', array(), '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.998 511.998" style="enable-background:new 0 0 511.998 511.998;" xml:space="preserve">
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
								C512.001,133.58,494.975,92.478,464.059,61.565z"></path>
						</g>
					</g>
					</svg>', true );
									$divInsideSpan->appendElement( 'plaintext', array(), $file_row['afile_name'] );
								} else {
									//$span->appendElement( 'plaintext', array(), CommonHelper::displayNotApplicable($siteLangId, '' ) );
								}
								break;
								
								case 'action':
									$td->appendElement('span', array('class' => 'td__caption -hide-desktop -show-mobile'), Label::getLabel('LBL_Action'));
									
									$span = $td->appendElement('span', array('class' => 'td__data'), '' );
									
									$span->appendElement( 'a', array('class' => 'btn btn--small btn--secondary', 'href' => 'javascript:void(0)', 'onClick' => 'teacherQualificationForm(' . $row['uqualification_id'] . ')'), Label::getLabel('LBL_Edit') );
									
									$span->appendElement( 'a', array('class' => 'btn btn--small', 'href' => 'javascript:void(0)', 'onClick' => 'return deleteTeacherQualification(' . $row['uqualification_id'] . ');'), Label::getLabel('LBL_Delete') );
									
								break;
							}
						}
					}
					echo $tbl->getHtml();
				}
					
					?>
		</div>
		<span class="-gap"></span>
	</div>
</div>