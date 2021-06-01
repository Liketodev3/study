<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php if (!empty($flashCards)) { ?>
    <div class="sidebar__scroll">
        <div class="flashcard-cover">
            <div class="flash-card">
                <?php foreach ($flashCards as $flashCard) { ?>
                    <div class="flash-card__item padding-bottom-4">
                        <p><?php echo $flashCard['flashcard_title'] . " (" . $flashCard['wordLanguageCode'] . ")"; ?></p>
                        <h6 class="flash-card-title bold-700 padding-bottom-4">
                            <?php echo $flashCard['flashcard_defination'] . " (" . $flashCard['wordDefLanguageCode'] . ")" ?>
                        </h6>
                        <div class="actions-group">
                            <a href="javascript:void(0);" onclick="viewFlashCard(<?php echo $flashCard['flashcard_id']; ?>);" class="btn btn--equal color-black margin-1 is-hover">
                                <svg class="icon icon--issue icon--small">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#view'; ?>"></use>
                                </svg>
                                <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_View'); ?></div>
                            </a>

                            <?php if ($myteacher == 0) { ?>
                                <a href="javascript:void(0);" onclick="flashCardForm(<?php echo $flashCard['sflashcard_slesson_id'] ?>, <?php echo $flashCard['flashcard_id']; ?>);" class="btn btn--equal color-black margin-1 is-hover">
                                    <svg class="icon icon--issue icon--small">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#edit'; ?>"></use>
                                    </svg>
                                    <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Edit'); ?></div>
                                </a>
                                <?php if ($flashCard['flashcard_created_by_user_id'] == UserAuthentication::getLoggedUserId()) { ?>
                                    <a href="javascript:void(0);" onclick="removeFlashcard(<?php echo $flashCard['flashcard_id']; ?>);" class="btn btn--equal color-black margin-1 is-hover">
                                        <svg class="icon icon--issue icon--small">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#trash'; ?>"></use>
                                        </svg>
                                        <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Delete') ?></div>
                                    </a>
                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="flashcard-cover">
        <div class="message-display">
            <div class="message-display__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="189" height="162.015" viewBox="0 0 189 162.015">
                    <defs>
                        <style>
                            .a {
                                fill: #ccd0d9;
                            }

                            .b {
                                fill: #b1b5c4;
                            }

                            .c {
                                fill: none;
                            }
                        </style>
                    </defs>
                    <g transform="translate(-236.977 -3941)">
                        <path class="a" d="M232.424,85.2a4.367,4.367,0,1,0,4.367,4.367,4.367,4.367,0,0,0-4.367-4.367m2.148,4.3a2.148,2.148,0,1,1-2.148-2.148,2.154,2.154,0,0,1,2.148,2.148" transform="translate(187.261 3917.295)" />
                        <path class="a" d="M12.323,13.968H8.887V10.532a1.432,1.432,0,1,0-2.864,0v3.436H2.587a1.432,1.432,0,1,0,0,2.864H6.1v3.436a1.432,1.432,0,0,0,2.864,0V16.832h3.435A1.436,1.436,0,0,0,13.826,15.4a1.549,1.549,0,0,0-1.5-1.432" transform="translate(262.822 3953.899)" />
                        <path class="a" d="M222.292,7.3h-5.87V1.431a1.432,1.432,0,0,0-2.864,0V7.3h-5.87a1.432,1.432,0,0,0,0,2.864h5.87v5.87a1.432,1.432,0,1,0,2.864,0v-5.87h5.87a1.432,1.432,0,1,0,0-2.864" transform="translate(179.456 3953)" />
                        <path class="b" d="M36.586,167.223l-9.231-16.891a.626.626,0,0,0-.573-.43L20.7,148.184a.694.694,0,0,0-.642.071L7.594,154.91a2.573,2.573,0,0,0-1.288,1.575,2.481,2.481,0,0,0,.214,2l10.092,19.4a2.61,2.61,0,0,0,1.575,1.289,3.075,3.075,0,0,0,.787.143,2.98,2.98,0,0,0,1.145-.215l.143-.072,15.32-8.233a2.528,2.528,0,0,0,1.288-1.575,2.843,2.843,0,0,0-.286-2m-13.6-14.888-1-1.647,2.508.716ZM22.7,154.41h.071a1.025,1.025,0,0,0,.429-.143l3.221-1.933,8.591,15.677a.823.823,0,0,1,.071.716.771.771,0,0,1-.429.5L19.336,177.46a.823.823,0,0,1-.716.072,1.281,1.281,0,0,1-.572-.5l-10.022-19.4a1.028,1.028,0,0,1,.358-1.289l11.381-6.085,2.22,3.722a1.262,1.262,0,0,0,.5.358.262.262,0,0,0,.215.072" transform="translate(248.836 3900.167)" />
                        <path class="b" d="M14.41,164.576a.73.73,0,0,1-.573-.358.6.6,0,0,1,.286-.788l8.161-4.151a.593.593,0,1,1,.5,1.074L14.625,164.5a.261.261,0,0,1-.215.072" transform="translate(246.677 3896.68)" />
                        <path class="b" d="M20.01,174.814a.73.73,0,0,1-.573-.358.6.6,0,0,1,.286-.788l10.022-5.083a.593.593,0,0,1,.5,1.074l-10.022,5.083c0,.072-.072.072-.214.072" transform="translate(245.086 3894.089)" />
                        <path class="b" d="M22.81,179.793a.73.73,0,0,1-.573-.358.6.6,0,0,1,.286-.788l10.451-5.367a.593.593,0,1,1,.5,1.074l-10.452,5.367c-.072,0-.143.072-.215.072" transform="translate(244.536 3892.791)" />
                        <path class="b" d="M16.91,169.505a.73.73,0,0,1-.573-.358.6.6,0,0,1,.286-.787l10.022-5.083a.593.593,0,1,1,.5,1.074l-10.022,5.082a.263.263,0,0,1-.215.072" transform="translate(245.966 3895.573)" />
                        <path class="a" d="M785.43,659.033l-1.846.194-.194-1.846a.88.88,0,1,0-1.75.194l.194,1.846-1.846.194a.947.947,0,0,0-.778.972.881.881,0,0,0,.972.778l1.846-.194.194,1.846a.88.88,0,0,0,1.755-.138.543.543,0,0,0-.006-.058l-.194-1.846,1.846-.194a.945.945,0,0,0,.777-.972.884.884,0,0,0-.972-.778" transform="translate(-542.232 3433.121)" />
                        <path class="a" d="M865.657,663.316l-3.4-1.069,1.069-3.4a1.6,1.6,0,0,0-1.02-2.025l-.048-.016a1.6,1.6,0,0,0-2.024,1.016l-.016.053-1.069,3.4-3.5-1.069a1.6,1.6,0,0,0-2.024,1.016l-.016.053a1.6,1.6,0,0,0,1.015,2.024l.054.016,3.5,1.069-1.069,3.4a1.629,1.629,0,0,0,3.109.972h0l1.069-3.4,3.4,1.069a1.6,1.6,0,0,0,2.024-1.016l.016-.054a1.565,1.565,0,0,0-.946-2c-.041-.015-.082-.027-.123-.039" transform="translate(-543.8 3433.115)" />
                        <path class="a" d="M976.084,703.3l-2.333.292-.292-2.333a1.073,1.073,0,1,0-2.137.194l.292,2.333-2.333.292a1.073,1.073,0,1,0,.194,2.138h0l2.333-.292.292,2.333a1.073,1.073,0,1,0,2.138-.194l-.292-2.333,2.333-.292a1.154,1.154,0,0,0,.972-1.167,1.018,1.018,0,0,0-1.167-.972" transform="translate(-551.274 3360.007)" />
                        <path class="a" d="M929.487,657.308a4.275,4.275,0,1,0,4.275,4.275,4.275,4.275,0,0,0-4.275-4.275m0,6.607a2.364,2.364,0,0,1-2.333-2.333,2.333,2.333,0,1,1,4.667,0,2.364,2.364,0,0,1-2.333,2.333" transform="translate(-559.235 3412.691)" />
                        <rect class="c" width="173.457" height="140.791" transform="translate(250.594 3941)" />
                        <path class="a" d="M101.437,46.4,84.493,109.636a3.071,3.071,0,0,1-3.763,2.173L39.282,100.7A3.076,3.076,0,0,1,37.11,96.94l.447-1.668a1.971,1.971,0,1,1,3.808,1.021l-.222.828L80.91,107.776,97.4,46.22,80.534,41.7a1.971,1.971,0,0,1,1.02-3.808l17.71,4.745a3.076,3.076,0,0,1,2.172,3.763ZM75.125,97.808l-.485,1.812a1.971,1.971,0,1,0,3.808,1.02l.485-1.812a1.971,1.971,0,1,0-3.808-1.02ZM24.614,61.7a7.605,7.605,0,0,1,1.632-.263V21.535a3.076,3.076,0,0,1,3.072-3.072h42.91A3.076,3.076,0,0,1,75.3,21.535V61.443a7.6,7.6,0,0,1,1.632.263,6.8,6.8,0,0,1,4.8,8.315c-.937,3.5-3.931,6.156-6.433,7.972V87a3.076,3.076,0,0,1-3.073,3.073H29.318A3.076,3.076,0,0,1,26.246,87V77.993c-2.5-1.818-5.5-4.474-6.433-7.972a6.791,6.791,0,0,1,4.8-8.315ZM75.3,72.9A8.633,8.633,0,0,0,77.925,69a2.851,2.851,0,0,0-2.013-3.487,3.979,3.979,0,0,0-.611-.118ZM30.188,86.131h41.17V22.405H30.188V86.131ZM23.621,69A8.638,8.638,0,0,0,26.246,72.9v-7.51a3.956,3.956,0,0,0-.612.118A2.849,2.849,0,0,0,23.621,69Zm14.4,34.12-17.381,4.656L4.143,46.221l16.87-4.52a1.971,1.971,0,0,0-1.02-3.808L2.281,42.638A3.075,3.075,0,0,0,.109,46.4l16.944,63.235a3.069,3.069,0,0,0,3.763,2.174l18.221-4.882a1.971,1.971,0,0,0-1.021-3.808Z" transform="translate(284.183 3942.937)" />
                        <path class="a" d="M19.916,13.341H12.067L18.843,1.419A.948.948,0,0,0,18.02,0H6.639a.948.948,0,0,0-.9.648L.05,17.782a.947.947,0,0,0,.9,1.249H8.995L3.871,31.05a.948.948,0,0,0,1.58,1L20.625,14.919a.948.948,0,0,0-.708-1.579Zm0,0" transform="translate(324.524 3983.876)" />
                    </g>
                </svg>
            </div>

            <h5><?php echo Label::getLabel('LBL_NO_RECORD_FLASH_CARD_TITLE'); ?></h5>
            <p><?php echo Label::getLabel('LBL_NO_RECORD_FLASH_CARD_TEXT'); ?></p>
            <a href="javascript:void(0);" onclick="$('.flash-card-add-js').trigger('click');" class="btn bg-primary"><?php echo Label::getLabel('LBL_Add_flash_card'); ?></a>
        </div>
    </div>
<?php } ?>