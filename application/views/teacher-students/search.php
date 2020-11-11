<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20">
	<?php if( !empty( $students ) ){ ?>
    <div class="table-scroll">
        <table class="table">
            <tbody>
                <tr class="-hide-mobile">
                    <th><?php echo Label::getLabel('LBL_Learner'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Lock_(Single/Bulk_Price)'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Scheduled'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Past'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Unscheduled'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Actions'); ?></th>
                </tr>
                <?php 
				foreach( $students as $student ){ ?>

                    <tr>
                        <td width="25%">
                            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Learner'); ?></span>
                            <span class="td__data">
							
								<div class="profile-info align-items-center">
									<div class="avtar avtar--small" data-text="<?php echo CommonHelper::getFirstChar($student['learnerFname']); ?>">
									<?php 
										if( true == User::isProfilePicUploaded( $student['learnerId'] ) ){
											$img = CommonHelper::generateUrl('Image','user', array( $student['learnerId'] )).'?'.time(); 
											echo '<img src="'.$img.'" />';
										}
									?>
									</div>

									<div class="profile-info__right">
										<h6><?php echo $student['learnerFname']; ?></h6>
									</div>
								</div>
							</span>
                        </td>
						
                        <td>
                            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Lock_(Single/Bulk_Price)'); ?></span>

							<span class="td__data">
							<span class="-display-inline"><?php echo CommonHelper::displayMoneyFormat($student['singleLessonAmount']); ?> / <?php echo CommonHelper::displayMoneyFormat($student['bulkLessonAmount']); ?></span>
							<?php if( $student['isSetUpOfferPrice'] ){ ?>
								<a href="javascript:void(0);" onClick="return unlockOfferPrice(<?php echo $student['learnerId'];?>);">
									<span class="inline-icon -display-inline -color-fill">
										<span class="svg-icon" title="<?php echo Label::getLabel('LBL_These_prices_are_locked');?>" >
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 520 520">
											<path d="M265,9A130.148,130.148,0,0,0,135,139v92h30V139a100,100,0,0,1,200,0v92h30V139A130.147,130.147,0,0,0,265,9ZM85,231V521H445V231H85ZM280,384.42V446H250V384.42A45,45,0,1,1,280,384.42ZM265,327a15,15,0,1,0,15,15A15.017,15.017,0,0,0,265,327Z" transform="translate(-5 -5)"></path>
										</svg>
										</span>
									</span>
									</a>
							<?php } else { ?>
								<a href="javascript:void(0);" onclick="offerPriceForm(<?php echo $student['learnerId'];?>);">
									<span class="inline-icon -display-inline">
										<span class="svg-icon" title="<?php echo Label::getLabel('LBL_These_prices_are_Unlocked'); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 520 520">
											  <path d="M85,521V231H365V139A99.972,99.972,0,0,0,182.193,83h-34.5A129.991,129.991,0,0,1,395,139v92h50V521H85ZM265,297a45,45,0,0,0-15,87.42V446h30V384.42A45,45,0,0,0,265,297Zm0,30a15,15,0,1,0,15,15A15.017,15.017,0,0,0,265,327Z" transform="translate(-5 -5)"></path>
											</svg>
										</span>
									</span>
									</a>
							<?php } ?>
							</span>
                        </td>
						
                        <td>
                            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Scheduled'); ?></span>
                            <span class="td__data"><?php echo $student['scheduledLessonCount'];  ?></span>
                        </td>
                        <td>
                            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Past'); ?></span>
                            <span class="td__data"><?php echo $student['pastLessonCount']; ?></span>
                        </td>
                        <td>
                            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Unscheduled'); ?></span>
                            <span class="td__data"><?php echo $student['unScheduledLessonCount']; ?></span>
                        </td>
                        <td>
                            <span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Actions'); ?></span>
                            <span class="td__data">
								<!--a href="<?php echo CommonHelper::generateUrl('Messages','initiate', array(CommonHelper::encryptId($student['learnerId'])));?>" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Message'); ?></a-->
<a href="javascript:void(0)" onClick="generateThread(<?php echo $student['learnerId']; ?>)" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Message');?></a>						 	</span>
                        </td>
                    </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
	
	<?php
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
	'name' => 'frmTeacherStudentsSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	} else { 
		$this->includeTemplate('_partial/no-record-found.php');
	} ?>
</div>