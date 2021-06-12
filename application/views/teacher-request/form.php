<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('teacher-request/_partial/header.php', ['siteLangId' => $siteLangId]);

$usrFirstName = $frm->getField('utrvalue_user_first_name');
$usrLastName = $frm->getField('utrvalue_user_last_name');
$usrGender = $frm->getField('utrvalue_user_gender');
$usrPhone = $frm->getField('utrvalue_user_phone');
$usrPhotoId = $frm->getField('user_photo_id');
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
                <div class="page-block__body" id="block--1">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-12 col-lg-10 col-xl-8">
                            <div class="block-content">
                                <div class="block-content__head">
                                    <div class="info__content">
                                        <h5><?php echo Label::getLabel('LBL_Personal_Information', $siteLangId); ?></h5>
                                        <p><?php echo Label::getLabel('LBL_tutor_reg_description', $siteLangId) ?></p>
                                    </div>
                                </div>
                                <?php $frm->getFormHtml(); ?>
                                <div class="block-content__body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label"><?php echo $usrFirstName->getCaption(); ?></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <?php echo $usrFirstName->getHTML(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label"><?php echo $usrLastName->getCaption(); ?></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <?php echo $usrLastName->getHTML(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label"><?php echo $usrGender->getCaption(); ?></label>
                                                </div>

                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <div class="row">
                                                            <?php foreach ($usrGender->options as $id => $name) { ?>
                                                                <div class="col-6 col-md-6">
                                                                    <div class="list-inline">
                                                                        <label><span class="radio"><input <?php echo ($usrGender->value == $id) ? 'checked="checked"' : ''; ?> type="radio" name="<?php echo $usrGender->getName(); ?>" value="<?php echo $id; ?>"><i class="input-helper"></i></span><?php echo $name; ?></label>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label"><?php echo $usrPhone->getCaption(); ?></label>
                                                </div>
                                                <div class="field-wraper phone--number">
                                                    <div class="row no-gutters">
                                                        <div class="col-2 col-md-2">
                                                            <div class="field_cover">
                                                                <?php $usrPhone->getHtml(); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-10 col-md-10">
                                                            <div class="field_cover">
                                                                <input type="text">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label"><?php echo $usrPhotoId->getCaption(); ?> <span><?php echo Label::getLabel('LBL_Allowed_Extension', $siteLangId); ?></span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <?php echo $usrPhotoId->getHtml(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    </form>
                                </div>
                                <div class="block-content__foot">
                                    <button class="btn btn-next"><?php echo Label::getLabel('LBL_Next', $siteLangId); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-block__body" id="block--2" style="display:none;">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-12 col-lg-10 col-xl-8">
                            <div class="block-content">
                                <div class="block-content__head">
                                    <div class="info__content">
                                        <h5><?php echo Label::getLabel('LBL_Profile_media_title',$siteLangId); ?></h5>
                                        <p>Lorem Ipsum is not simply random text.Ipsum to popular belief, Lorem Ipsum is simply dummy text of the printing Contrary to popular belief,</p>
                                    </div>
                                </div>
                                <div class="block-content__body">
                                    <form class="form">
                                        <div class="img-upload">
                                            <div class="img-upload__media">
                                                <div class="avtar avtar--large ratio ratio--1by1">
                                                    <img src="images/220x220.png">
                                                </div>
                                            </div>
                                            <div class="img-upload__content">
                                                <h6>Profile Photo</h6>
                                                <p>(Experts use profile picture to look professional)</p>
                                                <a href="#" class="btn btn--bordered btn--small color-secondary">Upload</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">Introduction Video <span>(Experts use videos to present their skillsets)</span></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <input type="text" placeholder="eg: https://youtu.be/XkGgIjAHFDs">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">Biography <span>(Write About Yourself And Your Qualifications)</span></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <textarea type="text"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="block-content__foot">
                                <button class="btn btn-Back"><?php echo Label::getLabel('LBL_Back',$siteLangId); ?></button>
                                <button class="btn btn-next"><?php echo Label::getLabel('LBL_Next',$siteLangId); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-block__body" id="block--3" style="display:none;">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-12 col-lg-12 col-xl-11">
                            <div class="block-content">
                                <div class="block-content__head">
                                    <div class="info__content">
                                        <h5>Add Languages as you teach and as you speak</h5>
                                        <p>Lorem Ipsum is not simply random text.Ipsum to popular belief, Lorem Ipsum is simply dummy text of the printing Contrary to popular belief,</p>
                                    </div>
                                </div>
                                <div class="block-content__body">
                                    <form class="form">
                                        <div class="form__body">
                                            <div class="colum-layout">
                                                <div class="colum-layout__cell">
                                                    <div class="colum-layout__head">
                                                        <span class="bold-600">Languages I Teach</span>
                                                    </div>
                                                    <div class="colum-layout__body">
                                                        <div class="colum-layout__scroll scrollbar scrollbar-js" tabindex="0" style="width: 431px; padding-right: 10px; outline: none; overflow: hidden;">

                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_1.jpg" alt=""></span>Spanish</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>

                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_2.jpg" alt=""></span>English</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>

                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_3.jpg" alt=""></span>German</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>

                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_4.jpg" alt=""></span>French</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>

                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_5.jpg" alt=""></span>Italian</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_6.jpg" alt=""></span>Arabic</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_1.jpg" alt=""></span>Hindi</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_2.jpg" alt=""></span>Tamil</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="selection">
                                                                <label class="selection__trigger">
                                                                    <input class="selection__trigger-input" type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_3.jpg" alt=""></span>Italian</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="position: absolute; z-index: 1; margin: 0px; padding: 0px; display: none; left: 453px; top: 125px;">
                                                            <div class="enscroll-track scrollbar-track" style="position: relative; height: 450px;"><a href="" class="scrollbar-handle" style="position: absolute; z-index: 1; height: 450px;">
                                                                    <div class="top"></div>
                                                                    <div class="bottom"></div>
                                                                </a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="colum-layout__cell">
                                                    <div class="colum-layout__head">
                                                        <span class="bold-600">Languages I Speak</span>
                                                    </div>
                                                    <div class="colum-layout__body">
                                                        <div class="colum-layout__scroll scrollbar scrollbar-js" tabindex="0" style="width: 513px; padding-right: 10px; outline: none; overflow: hidden;">

                                                            <div class="selection selection--select is-selected">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label">
                                                                            <span class="flag-icon flag-icon--s"><img src="images/flag/flag_1.jpg" alt=""></span> Spanish <span class="badge color-secondary badge--round badge--small margin-0">Upper Beginner</span></span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_2.jpg" alt=""></span>French</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_3.jpg" alt=""></span>German</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>



                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_4.jpg" alt=""></span>italian</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_5.jpg" alt=""></span>italian</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_6.jpg" alt=""></span>Dutch</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target  select__dropdown">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <div class="selection selection--select is-selected">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_4.jpg" alt=""></span>Hindi <span class="badge color-secondary badge--round badge--small margin-0">Upper Beginner</span></span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_2.jpg" alt=""></span>Danish</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_3.jpg" alt=""></span>Greek</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_4.jpg" alt=""></span>Dutch</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target  ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_5.jpg" alt=""></span>Bulgarian</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_6.jpg" alt=""></span>Ewe</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_1.jpg" alt=""></span>Welsh</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_2.jpg" alt=""></span>Czech</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_3.jpg" alt=""></span>Basque</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_4.jpg" alt=""></span>Fulah</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_5.jpg" alt=""></span>Bulgarian</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_6.jpg" alt=""></span>Ewe</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_1.jpg" alt=""></span>Welsh</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_2.jpg" alt=""></span>Czech</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_3.jpg" alt=""></span>Basque</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="selection selection--select">
                                                                <label class="selection__trigger ">
                                                                    <input type="checkbox">
                                                                    <span class="selection__trigger-action">
                                                                        <span class="selection__trigger-label"><span class="flag-icon flag-icon--s"><img src="images/flag/flag_4.jpg" alt=""></span>Fulah</span>
                                                                        <span class="selection__trigger-icon"></span>
                                                                    </span>
                                                                </label>
                                                                <div class="selection__target ">
                                                                    <select class="select__dropdown">
                                                                        <option>Select Proficiency *</option>
                                                                        <option>I don't speak this language</option>
                                                                        <option>Beginner</option>
                                                                        <option>Upper Beginner</option>
                                                                        <option>Intermediate</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="block-content__foot">
                                <button class="btn btn-Back"><?php echo Label::getLabel('LBL_Back',$siteLangId); ?></button>
                                <button class="btn btn-next"><?php echo Label::getLabel('LBL_Next',$siteLangId); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-block__body" id="block--4" style="display:none;">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-12 col-lg-10 col-xl-11">
                            <div class="block-content">
                                <div class="block-content__head">
                                    <div class="info__content">
                                        <h5>Add your resumes & eperiences</h5>
                                        <p>You have to add your resume, its a mandatory to create a profile as a tutor.</p>
                                    </div>
                                </div>
                                <div class="block-content__body">

                                    <div class="message-display message--resume">
                                        <div class="message-display__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="220" height="160" viewBox="0 0 220 160">
                                                <defs>
                                                    <style>
                                                        .a,
                                                        .g {
                                                            fill: none;
                                                        }

                                                        .b,
                                                        .e {
                                                            fill: #fff;
                                                        }

                                                        .c,
                                                        .d,
                                                        .f {
                                                            fill: #ccd0d9;
                                                        }

                                                        .e,
                                                        .f,
                                                        .g {
                                                            stroke: #ccd0d9;
                                                            stroke-width: 2px;
                                                        }

                                                        .h,
                                                        .i {
                                                            stroke: none;
                                                        }

                                                        .i {
                                                            fill: #ccd0d9;
                                                        }
                                                    </style>
                                                </defs>
                                                <g transform="translate(-836 -294)">
                                                    <rect class="a" width="220" height="160" transform="translate(836 294)" />
                                                    <g transform="translate(839.143 320.4)">
                                                        <g transform="translate(129.223 10.823) rotate(30)">
                                                            <g class="b" transform="translate(0 0)">
                                                                <path class="h" d="M 61.37833786010742 84.13814544677734 L 1.649896621704102 83.67002868652344 L 1.007855653762817 1.007922053337097 L 60.73629379272461 1.476034283638 L 61.37833786010742 84.13814544677734 Z" />
                                                                <path class="i" d="M 2.015712738037109 2.015853881835938 L 2.642215728759766 82.67776489257813 L 60.37047958374023 83.13019561767578 L 59.74396514892578 2.468284606933594 L 2.015712738037109 2.015853881835938 M -3.814697265625e-06 -7.62939453125e-06 L 61.72860717773438 0.4837799072265625 L 62.38619613647461 85.14605712890625 L 0.6575698852539063 84.66226959228516 L -3.814697265625e-06 -7.62939453125e-06 Z" />
                                                            </g>
                                                            <g class="c" transform="translate(7.248 7.313)">
                                                                <path class="h" d="M 13.48823738098145 13.61832904815674 L 1.105050206184387 13.52127552032471 L 1.007856011390686 1.007930517196655 L 13.3910436630249 1.104984045028687 L 13.48823738098145 13.61832904815674 Z" />
                                                                <path class="i" d="M 2.015715599060059 2.015860557556152 L 2.097373008728027 12.52902221679688 L 12.48037910461426 12.61039924621582 L 12.39872074127197 2.097237586975098 L 2.015715599060059 2.015860557556152 M -2.86102294921875e-06 0 L 14.38336658477783 0.1127300262451172 L 14.49609661102295 14.62625980377197 L 0.112727165222168 14.51352977752686 L -2.86102294921875e-06 0 Z" />
                                                            </g>
                                                            <path class="d" d="M0,0,26.37.207,26.4,3.835.028,3.628Z" transform="translate(27.035 8.678)" />
                                                            <path class="d" d="M0,0,26.37.207l.014,1.814L.014,1.814Z" transform="translate(27.082 14.725)" />
                                                            <path class="d" d="M0,0,20.376.16l.014,1.814L.014,1.814Z" transform="translate(27.114 18.958)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.459 34.526)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.497 39.364)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.582 50.249)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.619 55.087)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.704 65.972)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.741 70.81)" />
                                                        </g>
                                                        <g transform="translate(23.584 42.106) rotate(-30)">
                                                            <g class="b" transform="translate(0 0)">
                                                                <path class="h" d="M 0.3502781391143799 83.65435791015625 L 0.9923191666603088 0.9922467470169067 L 60.72076034545898 0.5241345763206482 L 60.07871627807617 83.18624114990234 L 0.3502781391143799 83.65435791015625 Z" />
                                                                <path class="i" d="M 59.7129020690918 1.532066345214844 L 1.984638214111328 1.9844970703125 L 1.358135223388672 82.64640808105469 L 59.08638763427734 82.19397735595703 L 59.7129020690918 1.532066345214844 M 61.72861862182617 -0.483795166015625 L 61.07102966308594 84.17848205566406 L -0.6575813293457031 84.66226959228516 L -7.62939453125e-06 -7.62939453125e-06 L 61.72861862182617 -0.483795166015625 Z" />
                                                            </g>
                                                            <g class="c" transform="translate(7.135 7.2)">
                                                                <path class="h" d="M 0.8951289057731628 13.50560188293457 L 0.9923229813575745 0.9922568202018738 L 13.37551021575928 0.8952032923698425 L 13.27831649780273 13.40854835510254 L 0.8951289057731628 13.50560188293457 Z" />
                                                                <path class="i" d="M 12.36765098571777 1.903133392333984 L 1.984645843505859 1.98451042175293 L 1.902988433837891 12.49767208099365 L 12.2859935760498 12.41629505157471 L 12.36765098571777 1.903133392333984 M 14.38336944580078 -0.112727165222168 L 14.27063941955566 14.40080261230469 L -0.1127300262451172 14.5135326385498 L 0 2.86102294921875e-06 L 14.38336944580078 -0.112727165222168 Z" />
                                                            </g>
                                                            <path class="d" d="M0,0,26.37-.207l-.028,3.628-26.37.207Z" transform="translate(26.903 8.255)" />
                                                            <path class="d" d="M0,0,26.37-.207l-.014,1.814-26.37.207Z" transform="translate(26.856 14.302)" />
                                                            <path class="d" d="M0,0,20.376-.16l-.014,1.814-20.376.16Z" transform="translate(26.823 18.535)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.924 34.413)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.886 39.251)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.802 50.136)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.764 54.974)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.68 65.859)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.642 70.697)" />
                                                        </g>
                                                        <g transform="translate(62.453 3.2)">
                                                            <g class="e">
                                                                <rect class="h" width="80.929" height="112" />
                                                                <rect class="a" x="1" y="1" width="78.929" height="110" />
                                                            </g>
                                                            <g class="f" transform="translate(9.429 9.6)">
                                                                <rect class="h" width="18.857" height="19.2" />
                                                                <rect class="a" x="1" y="1" width="16.857" height="17.2" />
                                                            </g>
                                                            <rect class="d" width="34.571" height="4.8" transform="translate(35.357 11.2)" />
                                                            <rect class="d" width="34.571" height="2.4" transform="translate(35.357 19.2)" />
                                                            <rect class="d" width="26.714" height="2.4" transform="translate(35.357 24.8)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 45.6)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 52)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 66.4)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 72.8)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 87.2)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 93.6)" />
                                                        </g>
                                                        <g class="g" transform="translate(156.738 8.509)">
                                                            <circle class="h" cx="5" cy="5" r="5" />
                                                            <circle class="a" cx="5" cy="5" r="4" />
                                                        </g>
                                                        <g class="g" transform="translate(188.167 67.2)">
                                                            <circle class="h" cx="6.286" cy="6.286" r="6.286" />
                                                            <circle class="a" cx="6.286" cy="6.286" r="5.286" />
                                                        </g>
                                                        <g class="g" transform="translate(20.024 72)">
                                                            <circle class="h" cx="3.143" cy="3.143" r="3.143" />
                                                            <circle class="a" cx="3.143" cy="3.143" r="2.143" />
                                                        </g>
                                                        <g transform="translate(25.524 0)">
                                                            <rect class="d" width="2.357" height="16" transform="translate(6.286)" />
                                                            <rect class="d" width="2.4" height="14.929" transform="translate(14.929 7.2) rotate(90)" />
                                                        </g>
                                                        <g transform="translate(160.667 102.4)">
                                                            <rect class="d" width="2.357" height="10.4" transform="translate(3.929)" />
                                                            <rect class="d" width="2.4" height="10.214" transform="translate(10.214 4) rotate(90)" />
                                                        </g>
                                                        <g class="g" transform="translate(6.667 32.218) rotate(45)">
                                                            <rect class="h" width="9.514" height="9.514" />
                                                            <rect class="a" x="1" y="1" width="7.514" height="7.514" />
                                                        </g>
                                                        <g class="g" transform="translate(190.985 17.543) rotate(45)">
                                                            <rect class="h" width="7.929" height="7.929" />
                                                            <rect class="a" x="1" y="1" width="5.929" height="5.929" />
                                                        </g>
                                                        <g class="g" transform="translate(16.881 99.2)">
                                                            <circle class="h" cx="6.286" cy="6.286" r="6.286" />
                                                            <circle class="a" cx="6.286" cy="6.286" r="5.286" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>

                                        <p>You haven't added resume yet.<br> Click the button below to add the same.</p>
                                        <a href="#" class="btn btn--bordered btn--small color-secondary">Add Resume</a>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="field-set margin-bottom-0 accept--field">
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <label>
                                                            <span class="checkbox">
                                                                <input data-field-caption="Remember Me" type="checkbox" name="remember_me" value="1">
                                                                <i class="input-helper"></i>
                                                            </span>
                                                            Accept tutor approval <a href="#">terms & conditions</a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content__foot">
                                <button class="btn btn-Back"><?php echo Label::getLabel('LBL_Back',$siteLangId); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-block__body" id="block--5" style="display:none;">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-12 col-lg-10 col-xl-11">
                            <div class="block-content">
                                <div class="block-content__head d-flex justify-content-center">
                                    <h5>Application Awaiting Approval</h5>
                                </div>
                                <div class="block-content__body">

                                    <div class="message-display message--resume message--confirmetion">
                                        <div class="message-display__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="220" height="160" viewBox="0 0 220 160">
                                                <defs>
                                                    <style>
                                                        .a,
                                                        .g {
                                                            fill: none;
                                                        }

                                                        .b,
                                                        .e,
                                                        .i {
                                                            fill: #fff;
                                                        }

                                                        .c,
                                                        .d,
                                                        .f {
                                                            fill: #ccd0d9;
                                                        }

                                                        .e,
                                                        .f,
                                                        .g {
                                                            stroke: #ccd0d9;
                                                            stroke-width: 2px;
                                                        }

                                                        .h {
                                                            fill: #1dce70;
                                                        }

                                                        .j,
                                                        .k {
                                                            stroke: none;
                                                        }

                                                        .k {
                                                            fill: #ccd0d9;
                                                        }
                                                    </style>
                                                </defs>
                                                <g transform="translate(-836 -294)">
                                                    <rect class="a" width="220" height="160" transform="translate(836 294)" />
                                                    <g transform="translate(839.143 320.4)">
                                                        <g transform="translate(129.223 10.823) rotate(30)">
                                                            <g class="b" transform="translate(0 0)">
                                                                <path class="j" d="M 61.37833786010742 84.13814544677734 L 1.649896621704102 83.67002868652344 L 1.007855653762817 1.007922053337097 L 60.73629379272461 1.476034283638 L 61.37833786010742 84.13814544677734 Z" />
                                                                <path class="k" d="M 2.015712738037109 2.015853881835938 L 2.642215728759766 82.67776489257813 L 60.37047958374023 83.13019561767578 L 59.74396514892578 2.468284606933594 L 2.015712738037109 2.015853881835938 M -3.814697265625e-06 -7.62939453125e-06 L 61.72860717773438 0.4837799072265625 L 62.38619613647461 85.14605712890625 L 0.6575698852539063 84.66226959228516 L -3.814697265625e-06 -7.62939453125e-06 Z" />
                                                            </g>
                                                            <g class="c" transform="translate(7.248 7.313)">
                                                                <path class="j" d="M 13.48823738098145 13.61832904815674 L 1.105050206184387 13.52127552032471 L 1.007856011390686 1.007930517196655 L 13.3910436630249 1.104984045028687 L 13.48823738098145 13.61832904815674 Z" />
                                                                <path class="k" d="M 2.015715599060059 2.015860557556152 L 2.097373008728027 12.52902221679688 L 12.48037910461426 12.61039924621582 L 12.39872074127197 2.097237586975098 L 2.015715599060059 2.015860557556152 M -2.86102294921875e-06 0 L 14.38336658477783 0.1127300262451172 L 14.49609661102295 14.62625980377197 L 0.112727165222168 14.51352977752686 L -2.86102294921875e-06 0 Z" />
                                                            </g>
                                                            <path class="d" d="M0,0,26.37.207,26.4,3.835.028,3.628Z" transform="translate(27.035 8.678)" />
                                                            <path class="d" d="M0,0,26.37.207l.014,1.814L.014,1.814Z" transform="translate(27.082 14.725)" />
                                                            <path class="d" d="M0,0,20.376.16l.014,1.814L.014,1.814Z" transform="translate(27.114 18.958)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.459 34.526)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.497 39.364)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.582 50.249)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.619 55.087)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.704 65.972)" />
                                                            <path class="d" d="M0,0,47.945.376l.014,1.814L.014,1.814Z" transform="translate(7.741 70.81)" />
                                                        </g>
                                                        <g transform="translate(23.584 42.106) rotate(-30)">
                                                            <g class="b" transform="translate(0 0)">
                                                                <path class="j" d="M 0.3502781391143799 83.65435791015625 L 0.9923191666603088 0.9922467470169067 L 60.72076034545898 0.5241345763206482 L 60.07871627807617 83.18624114990234 L 0.3502781391143799 83.65435791015625 Z" />
                                                                <path class="k" d="M 59.7129020690918 1.532066345214844 L 1.984638214111328 1.9844970703125 L 1.358135223388672 82.64640808105469 L 59.08638763427734 82.19397735595703 L 59.7129020690918 1.532066345214844 M 61.72861862182617 -0.483795166015625 L 61.07102966308594 84.17848205566406 L -0.6575813293457031 84.66226959228516 L -7.62939453125e-06 -7.62939453125e-06 L 61.72861862182617 -0.483795166015625 Z" />
                                                            </g>
                                                            <g class="c" transform="translate(7.135 7.2)">
                                                                <path class="j" d="M 0.8951289057731628 13.50560188293457 L 0.9923229813575745 0.9922568202018738 L 13.37551021575928 0.8952032923698425 L 13.27831649780273 13.40854835510254 L 0.8951289057731628 13.50560188293457 Z" />
                                                                <path class="k" d="M 12.36765098571777 1.903133392333984 L 1.984645843505859 1.98451042175293 L 1.902988433837891 12.49767208099365 L 12.2859935760498 12.41629505157471 L 12.36765098571777 1.903133392333984 M 14.38336944580078 -0.112727165222168 L 14.27063941955566 14.40080261230469 L -0.1127300262451172 14.5135326385498 L 0 2.86102294921875e-06 L 14.38336944580078 -0.112727165222168 Z" />
                                                            </g>
                                                            <path class="d" d="M0,0,26.37-.207l-.028,3.628-26.37.207Z" transform="translate(26.903 8.255)" />
                                                            <path class="d" d="M0,0,26.37-.207l-.014,1.814-26.37.207Z" transform="translate(26.856 14.302)" />
                                                            <path class="d" d="M0,0,20.376-.16l-.014,1.814-20.376.16Z" transform="translate(26.823 18.535)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.924 34.413)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.886 39.251)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.802 50.136)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.764 54.974)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.68 65.859)" />
                                                            <path class="d" d="M0,0,47.945-.376,47.93,1.438-.014,1.814Z" transform="translate(6.642 70.697)" />
                                                        </g>
                                                        <g transform="translate(62.453 3.2)">
                                                            <g class="e">
                                                                <rect class="j" width="80.929" height="112" />
                                                                <rect class="a" x="1" y="1" width="78.929" height="110" />
                                                            </g>
                                                            <g class="f" transform="translate(9.429 9.6)">
                                                                <rect class="j" width="18.857" height="19.2" />
                                                                <rect class="a" x="1" y="1" width="16.857" height="17.2" />
                                                            </g>
                                                            <rect class="d" width="34.571" height="4.8" transform="translate(35.357 11.2)" />
                                                            <rect class="d" width="34.571" height="2.4" transform="translate(35.357 19.2)" />
                                                            <rect class="d" width="26.714" height="2.4" transform="translate(35.357 24.8)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 45.6)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 52)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 66.4)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 72.8)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 87.2)" />
                                                            <rect class="d" width="62.857" height="2.4" transform="translate(9.429 93.6)" />
                                                        </g>
                                                        <g class="g" transform="translate(156.738 8.509)">
                                                            <circle class="j" cx="5" cy="5" r="5" />
                                                            <circle class="a" cx="5" cy="5" r="4" />
                                                        </g>
                                                        <g class="g" transform="translate(188.167 67.2)">
                                                            <circle class="j" cx="6.286" cy="6.286" r="6.286" />
                                                            <circle class="a" cx="6.286" cy="6.286" r="5.286" />
                                                        </g>
                                                        <g class="g" transform="translate(20.024 72)">
                                                            <circle class="j" cx="3.143" cy="3.143" r="3.143" />
                                                            <circle class="a" cx="3.143" cy="3.143" r="2.143" />
                                                        </g>
                                                        <g transform="translate(25.524 0)">
                                                            <rect class="d" width="2.357" height="16" transform="translate(6.286)" />
                                                            <rect class="d" width="2.4" height="14.929" transform="translate(14.929 7.2) rotate(90)" />
                                                        </g>
                                                        <g transform="translate(160.667 102.4)">
                                                            <rect class="d" width="2.357" height="10.4" transform="translate(3.929)" />
                                                            <rect class="d" width="2.4" height="10.214" transform="translate(10.214 4) rotate(90)" />
                                                        </g>
                                                        <g class="g" transform="translate(6.667 32.218) rotate(45)">
                                                            <rect class="j" width="9.514" height="9.514" />
                                                            <rect class="a" x="1" y="1" width="7.514" height="7.514" />
                                                        </g>
                                                        <g class="g" transform="translate(190.985 17.543) rotate(45)">
                                                            <rect class="j" width="7.929" height="7.929" />
                                                            <rect class="a" x="1" y="1" width="5.929" height="5.929" />
                                                        </g>
                                                        <g class="g" transform="translate(16.881 99.2)">
                                                            <circle class="j" cx="6.286" cy="6.286" r="6.286" />
                                                            <circle class="a" cx="6.286" cy="6.286" r="5.286" />
                                                        </g>
                                                    </g>
                                                    <g transform="translate(9 -15)">
                                                        <circle class="h" cx="30" cy="30" r="30" transform="translate(948 397)" />
                                                        <g transform="translate(-6.086 816.401) rotate(-45)">
                                                            <rect class="i" width="4" height="10.538" transform="translate(960 412)" />
                                                            <rect class="i" width="4" height="24" transform="translate(983.908 421.396) rotate(90)" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>

                                        <h5>Hello James Anderson</h5>
                                        <p>Thank You For Submitting Your Application</p>
                                        <div class="application-no">
                                            Application Reference: <b>24-1622799988</b>
                                        </div>
                                        <a href="#" class="btn btn--bordered btn--small color-secondary">Visit My Account</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<?php $this->includeTemplate('teacher-request/_partial/footer.php', ['siteLangId' => $siteLangId]); ?>