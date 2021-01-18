<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if( $cartData['lpackage_is_free_trial'] == 0 ){ ?>
<div class="box -padding-20" style="margin-bottom:30px;">
    <h3><?php echo Label::getLabel('LBL_Slot_Duration'); ?></h3>
    <p><?php echo Label::getLabel('LBL_Choose_Duration_for_lesson'); ?></p>
    <div class="selection-list">
        <ul>
            <?php foreach($bookingDurations as $lessonDuration){ ?>
            <li class="<?php echo ($cartData['lessonDuration'] == $lessonDuration) ? 'is-active' : ''; ?>">
                <label class="selection">
                    <span class="radio">
                        <input onClick="addToCart('<?php echo $cartData['user_id'] ?>', 2, <?php echo $cartData['languageId'].", '', '', 0, ".$lessonDuration; ?>);" type="radio"  name="lessonDuration" value="<?php echo $lessonDuration; ?>" <?php echo ($cartData['lessonDuration'] == $lessonDuration) ? 'checked="checked"' : ''; ?>><i class="input-helper"></i>
                    </span>
                    <span class="selection__item">
                        <?php echo sprintf(Label::getLabel('LBL_%s_Mins/Lesson'), $lessonDuration); ?> <small class="-float-right"> </small>
                    </span>
                </label>
            </li>
        <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>