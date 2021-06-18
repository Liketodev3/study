<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupStep2(this); return(false);');
$frm->setFormTagAttribute('action', CommonHelper::generateUrl('TeacherRequest', 'setupStep2'));
$profileImageField = $frm->getField('user_profile_image');
$profileImageField->setFieldTagAttribute('class', 'btn btn--bordered btn--small color-secondary');
$usrVideoLink = $frm->getField('utrequest_video_link');
$usrVideoLink->addFieldTagAttribute('onblur', 'validateVideolink(this);');
$usrBio = $frm->getField('utrequest_profile_info');
$profileImageUploaded = User::isProfilePicUploaded($user['user_id']);
?>
<?php $this->includeTemplate('teacher-request/_partial/leftPanel.php', ['siteLangId' => $siteLangId, 'step' => 2]); ?>
<script>
    var useMouseScroll = "<?php echo Label::getLabel('LBL_USE_MOUSE_SCROLL_TO_ADJUST_IMAGE'); ?>";
</script>
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
                        <div class="img-upload">
                            <div class="img-upload__media">
                                <div class="avtar avtar--large" data-title="<?php echo CommonHelper::getFirstChar($user['user_first_name']); ?>">
                                    <?php if ($profileImageUploaded) { ?>
                                        <img id="user-profile-pic--js" src="<?php echo CommonHelper::generateUrl('image', 'user', [$user['user_id'], 'MEDIUM', 1]) . '?' . time(); ?>">
                                    <?php } else { ?>
                                        <img id="user-profile-pic--js" style="display:none;" src="">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="img-upload__content">
                                <h6>
                                    <?php echo $profileImageField->getCaption(); ?>
                                    <?php if (!$profileImageUploaded) { ?><span class="spn_must_field">*</span><?php } ?>
                                </h6>
                                <p><?php echo Label::getLabel('LBL_Profile_Pic_Fld_Desc', $siteLangId); ?></p>
                                <div class="btn-file"><?php echo $profileImageField->getHTML(); ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                        <label class="field_label">
                                            <?php echo $usrVideoLink->getCaption(); ?>
                                            <span><?php echo Label::getLabel('LBL_video_desc', $siteLangId); ?></span>
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
                        <div class="form__actions">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><input type="button" name="back" onclick="getform(1);" value="<?php echo Label::getLabel('LBL_Back', $siteLangId); ?>"></div>
                                <div>
                                    <input type="submit" name="save" value="<?php echo Label::getLabel('LBL_SAVE', $siteLangId); ?>" />
                                    <input type="button" name="next" onclick="setupStep2(document.frmFormStep2, true)" value="<?php echo Label::getLabel('LBL_NEXT', $siteLangId); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
        <?php
        echo $frm->getFieldHtml('update_profile_img');
        echo $frm->getFieldHtml('rotate_left');
        echo $frm->getFieldHtml('rotate_right');
        echo $frm->getFieldHtml('img_data');
        echo $frm->getFieldHtml('action');
        ?>
        </form>
        <?php echo $frm->getExternalJs(); ?>
    </div>
</div>