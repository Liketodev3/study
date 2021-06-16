<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('teacher-request/_partial/header.php', ['siteLangId' => $siteLangId]);

?>
<section class="page-block min-height-500">
    <div class="container container--narrow">
        <div class="page-block__cover">
            <?php $this->includeTemplate('teacher-request/_partial/leftPanel.php', ['siteLangId' => $siteLangId]); ?>
            <div class="page-block__right">
                <div class="page-block__head">
                    <div class="head__title">
                        <h4><?php echo Label::getLabel('LBL_Tutor_registration', $siteLangId); ?></h4>
                    </div>
                </div> 
                <div class="page-block__body">
                
                </div>          
            </div>
        </div>
    </div>
    </div>
</section>
<div class="d-none">
    <?php $profileImgFrm->setFormTagAttribute('action', CommonHelper::generateUrl('TeacherRequest', 'setUpProfileImage'));
    echo $profileImgFrm->getFormHtml(); ?>
</div>

<?php $this->includeTemplate('teacher-request/_partial/footer.php', ['siteLangId' => $siteLangId]); ?>