<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php 
$requestStatus = $teacherRequestRow['utrequest_status'];
$dashboardUrl = CommonHelper::generateUrl('learner','index',[],CONF_WEBROOT_DASHBOARD);
?>
<section class="section section--grey section--page">
    <div class="container container--fixed">
        <div class="page-panel -clearfix">
            <div class="page__panel-narrow">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8 col-md-10">
                        <div class="box -padding-30">
                            <div class="message-display">
							
                                <div class="message-display__icon">
									
									<?php if( $requestStatus == TeacherRequest::STATUS_PENDING ){ ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120">
                                        <path fill="#000" d="M644.96,336.583H656V326H574v10.581h10.808v31.211l24.348,15.764L583.709,398.89l0.115,0.178-0.06.036v31.314H574V441h82V430.418H643.922V399.144L619.244,383.59l25.765-15.526-0.11-.179,0.061-.035V336.583Zm-6.168,66.172v27.663H588.9V401.877l25.148-15.149Zm1.046-37.678-25.547,15.395-24.356-15.363V336.583h49.9v28.494Zm-3.054-2.454V350.9H593.011v11.669l21.252,13.648Z" transform="translate(-555 -323.5)" />
                                        <path class="-color-fill" d="M603.772,422.014l-12.043,1.569v4.48H636.1v-4.48l-10.964-1.3-11.214-4.277Z" transform="translate(-555 -323.5)" />
                                    </svg>
									<?php } else if( $requestStatus == TeacherRequest::STATUS_APPROVED ){
                                                    //$dashboardUrl = CommonHelper::generateUrl('teacher');
                                    ?>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120">
									  <path fill="#000" d="M105.823,52.206a4.4,4.4,0,0,0-4.4,4.393v4.425a43.258,43.258,0,0,1-43.3,43.188H58.092a43.213,43.213,0,1,1,.024-86.426h0.026a43.111,43.111,0,0,1,17.6,3.741A4.395,4.395,0,1,0,79.325,13.5,51.871,51.871,0,0,0,58.147,9H58.116a52,52,0,1,0-.029,104h0.031a52.054,52.054,0,0,0,52.108-51.973V56.6A4.4,4.4,0,0,0,105.823,52.206Z" transform="translate(-0.516 -1)"></path>
									  <path class="-color-fill" d="M113.706,15.075a4.409,4.409,0,0,0-6.226,0L58.117,64.335,46.918,53.16a4.4,4.4,0,0,0-6.226,6.213L55,73.655a4.409,4.409,0,0,0,6.226,0l52.476-52.367A4.386,4.386,0,0,0,113.706,15.075Z" transform="translate(-0.516 -1)"></path>
									</svg>
									<?php } else { ?>
										
									<?php } ?>
									
                                </div>
								
                                <span class="-gap"></span>
								<h1 class="-color-secondary"><?php echo Label::getLabel('LBL_Hello') ,' ', $teacherRequestRow["user_name"]?> </h1>
								<h4> <?php echo Label::getLabel('LBL_Thank_you_for_submitting_your_application')?></h4>
								
								<?php if( $requestStatus == TeacherRequest::STATUS_CANCELLED ){ ?>
								<span class="-gap"></span>
								<h6><?php echo Label::getLabel('LBL_Think_Error_Please_Contact_Us')?></h6>
								<a class="btn btn--secondary" href="<?php echo CommonHelper::generateUrl('TeacherRequest', 'form'); ?>"><?php echo Label::getLabel('LBL_Submit_Revised_Request')?></a>
								<span class="-gap"></span>
								<?php } ?>
								
								<p><?php echo Label::getLabel('LBL_Application_Reference')?>: <strong><?php echo $teacherRequestRow["utrequest_reference"]; ?></strong></p>
                            </div>
							                            <div class="-align-center"><a href="<?php echo $dashboardUrl; ?>" class="btn btn--secondary btn--large"><?php echo Label::getLabel('LBL_Go_to_Dashboard')?></a></div>
                            <hr><span class="-gap"></span>

                            <div class="-align-center">
								<?php if( $requestStatus == TeacherRequest::STATUS_PENDING ){ ?>
                                <h6><?php echo Label::getLabel('LBL_application_awaiting_approval')?></h6>
								
								<?php } else if( $requestStatus == TeacherRequest::STATUS_APPROVED ){ ?>
								<h6><?php echo Label::getLabel('LBL_Your_Application_Approved'); ?></h6>
								
								<?php } else { ?>
								<h6><?php echo Label::getLabel('LBL_Your_Application_Declined')?></h6>
								<?php } ?>
                            </div>
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>