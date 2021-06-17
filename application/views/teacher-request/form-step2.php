<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('onsubmit', 'setupStep2(this); return(false);');
$usrVideoLink = $frm->getField('utrequest_video_link');
$usrBio = $frm->getField('utrequest_profile_info');
?>
<?php $this->includeTemplate('teacher-request/_partial/leftPanel.php', ['siteLangId' => $siteLangId, 'step' => 2]); ?>
<div class="page-block__right">
    <div class="page-block__head">
        <div class="head__title">
            <h4><?php echo Label::getLabel('LBL_Tutor_registration', $siteLangId); ?></h4>
        </div>
    </div> 
    <div class="page-block__body">
        <?php echo $frm->getFormTag() ?>
        <div class="row justify-content-center no-gutters">
            <div class="col-md-12 col-lg-10 col-xl-8">
                <div class="block-content">
                    <div class="block-content__head">
                        <div class="info__content">
                            <h5><?php echo Label::getLabel('LBL_Profile_media_title', $siteLangId); ?></h5>
                            <p><?php echo Label::getLabel('LBL_Profile_media_desc', $siteLangId); ?></p>
                        </div>
                    </div>
                    <div class="block-content__body">
                        <form class="form">
                            <div class="img-upload">
                                <div class="img-upload__media">
                                    <div class="avtar avtar--large" data-title="<?php echo CommonHelper::getFirstChar($user['user_first_name']); ?>">
                                        <?php if (User::isProfilePicUploaded($user['user_id'])) { ?>
                                            <img id="user-profile-pic--js" src="<?php echo CommonHelper::generateUrl('image', 'user', [$user['user_id']]); ?>">
                                        <?php } else { ?>
                                            <img id="user-profile-pic--js" style="display:none;" src="">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="img-upload__content">
                                    <p><?php echo Label::getLabel('LBL_Profile_Pic_Fld_Desc', $siteLangId); ?></p>
                                    <a href="javascript:void(0);" id="uploadFileInput--js" class="btn btn--bordered btn--small color-secondary"><?php echo Label::getLabel('LBL_Upload', $siteLangId); ?></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"><?php echo $usrVideoLink->getCaption(); ?><span><?php echo Label::getLabel('LBL_video_desc', $siteLangId); ?></span>
                                                <?php if ($usrVideoLink->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $usrVideoLink->getHTML(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"><?php echo $usrBio->getCaption(); ?> <span><?php echo Label::getLabel('LBL_About_self_Fld_Desc', $siteLangId); ?></span>
                                                <?php if ($usrBio->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $usrBio->getHtml(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="block-content__foot">
                        <button type="button" class="btn btn--bordered color-primary btn-Back" onclick="getform(1);"><?php echo Label::getLabel('LBL_Back', $siteLangId); ?></button>
                        <button type="submit" class="btn btn--primary color-white btn--next"><?php echo Label::getLabel('LBL_Next', $siteLangId); ?></button>
                    </div>
                </div>
            </div>
            </form>
            <?php echo $frm->getExternalJs(); ?>
        </div>          
    </div>