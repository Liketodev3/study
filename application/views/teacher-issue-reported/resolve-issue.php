<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$selectFld = $frm->getField('issues_to_report');
$selectFld->setOptionListTagAttribute('class', 'listing listing--vertical listing--selection isuueOptions');
$selectFld->captionWrapper = ['','<span class="spn_must_field">*</span>'];
$selectFld->fieldWrapper = ['<div class="form__list form__list--check">','<div>'];
// $selectFld2 = $frm->getField('issue_resolve_type');
// $selectFld2->setOptionListTagAttribute('class', 'listing listing--vertical listing--selection');
$selectFld->captionWrapper = ['','<span class="spn_must_field">*</span>'];
$selectFld->fieldWrapper = ['<div class="form__list form__list--check">','<div>'];
$frm->setFormTagAttribute('onsubmit', 'issueResolveSetup(this); return(false);');
?>
<div class="box -padding-20">
	<h4><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></h4>
	<?php //echo $frm->getFormHtml(); ?>
    <?php echo $frm->getFormTag(); ?>
       <div class="row">
          <div class="col-md-12">
             <div class="field-set">
                <div class="caption-wraper"><label class="field_label"><?php echo $frm->getField('issues_users')->getCaption() ?></label></div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <?php //echo $frm->getFieldHtml('issues_users') ?>
                        <?php $can_resolve = false; ?>
                        <ul class="list-accordion">
                            <?php foreach($issRows as $issRow): ?>
                            <?php if($issRow['issrep_status']==IssuesReported::STATUS_OPEN){
                                $can_resolve = true;
                            } ?>
                            <li>
                                <label><span class="checkbox"><input type="checkbox" name="issues_users[]" value="<?php echo $issRow['issrep_id'] ?>" <?php echo $issRow['issrep_status']==IssuesReported::STATUS_OPEN ? 'checked="checked"' : 'disabled="disabled"'; ?>><i class="input-helper"></i></span></label>
                                <div class="iss_accordion"><?php echo $issRow['user_full_name'] ?> <a href="javascript:;" class="-color-primary"><?php echo Label::getLabel("LBL_Details") ?></a></div>
                                <div class="panel">
                                    <div class="box">
                                        <div class="box__head">
                                            <h6><?php echo Label::getLabel('LBL_Reported_Issue_By').' '.$issRow['user_full_name']; ?></h6>
                                        </div>
                                        <div class="box__body -padding-20">
                                            <div class="content-repeated-container">
                                            <?php $user_timezone = MyDate::getUserTimeZone();
                                                $issue_date = MyDate::convertTimeFromSystemToUserTimezone( 'F d, Y H:i A', date($issRow['issrep_added_on']), true , $user_timezone ); 
                                            ?>
                                                <div class="content-repeated">
                                                    <div class="row">
                                                        <div class="col-xl-4 col-lg-4 col-sm-4">
                                                            <p class="-small-title"><strong><?php echo $issue_date; ?></strong>
                                                            </p>
                                                        </div>
                                                        <div class="col-xl-8 col-lg-8 col-sm-8">
                                                            <p><strong><?php
                                                            if ( $issRow['issrep_issues_to_report'] != '' ) {
                                                                $_issues = explode( ',' , $issRow['issrep_issues_to_report'] );
                                                                foreach ( $_issues as $issue ) {
                                                                    echo $issues_options[$issue].' <br />';
                                                                }
                                                            }
                                                            ?> </strong> 
                                                            <?php echo nl2br($issRow['issrep_comment']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="box__head">
                                            <h6><?php echo Label::getLabel('LBL_Reported_Issue_Updates_By_Teacher'); ?></h6>
                                        </div>
                                        <div class="box__body  -padding-20">
                                            <div class="content-repeated-container">
                                            <?php 
                                            $_last_issue_date = $issRow['issrep_updated_on'];
                                            if( $issRow['issrep_updated_on'] == '0000-00-00 00:00:00' ) continue;
                                                $user_timezone = MyDate::getUserTimeZone();
                                                $issue_date = MyDate::convertTimeFromSystemToUserTimezone( 'F d, Y H:i A', date($issRow['issrep_updated_on']), true , $user_timezone );
                                            ?>
                                                <div class="content-repeated">
                                                    <div class="row">
                                                        <div class="col-xl-4 col-lg-4 col-sm-4">
                                                            <p class="-small-title"><strong><?php echo $issue_date; ?></strong>
                                                            </p>
                                                        </div>
                                                        <div class="col-xl-8 col-lg-8 col-sm-8">
                                                            <p><strong><?php
                                                            if ($issRow['issrep_issues_resolve'] != '') {
                                                                $_issues = explode( ',' , $issRow['issrep_issues_resolve'] );
                                                                foreach ($_issues as $issue) {
                                                                    echo $issues_options[$issue].' <br />';
                                                                }
                                                            }
                                                            ?> </strong> 
                                                            <?php echo '<strong>'. Label::getLabel('LBL_Comment_:') .'</strong> '. nl2br($issRow['issrep_resolve_comments']); 
                                                            $resolved_by = $issRow['issrep_issues_resolve_type'];
                                                            if ($resolved_by > 0  && isset($resolve_type_options[$resolved_by])) {
                                                                echo '<br /><strong>'. Label::getLabel('LBL_Resolved_by_:') .'</strong> '. $resolve_type_options[$resolved_by]; 
                                                            } ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    $_time_valid_for_support = date('Y-m-d H:i:s');
                                    if( !empty($issRow) && $issRow['issrep_updated_on'] != '0000-00-00 00:00:00' ) {
                                        $resolve_datetime = $issRow['issrep_updated_on'];
                                        $_time_valid_for_support = date('Y-m-d H:i:s', strtotime('+ 48 Hours', strtotime($resolve_datetime)));
                                    }

                                    if ( strtotime($_time_valid_for_support )  > strtotime(date('Y-m-d H:i:s')) && $issRow['issrep_is_for_admin'] < 1 ) {
                                    ?>
                                        <div class="-padding-20 -no-padding-top">
                                            <p>
                                                <span class="-display-inline"><?php echo Label::getLabel('LBL_Not_Happy_with_solution?'); ?> &nbsp; </span><a href="javascript:void(0);" class="-link-underline -color-secondary" onclick="reportIssueToAdmin('<?php echo $issRow['issrep_id'];?>', '<?php echo $issRow['issrep_slesson_id']; ?>', '<?php echo USER::USER_TYPE_TEACHER?>');"><?php echo Label::getLabel('LBL_Report_Issue_to_Support_Team'); ?></a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
             </div>
          </div>
       </div>
       <?php if($can_resolve===true): ?>
       <div class="row">
          <div class="col-md-12">
             <div class="field-set">
                <div class="caption-wraper"><label class="field_label"><?php echo $frm->getField('issues_to_report')->getCaption() ?><span class="spn_must_field">*</span></label></div>
                <div class="field-wraper">
                   <div class="field_cover">
                    <div class="form__list form__list--check issues-to-resolve">
                        <?php echo $frm->getFieldHtml('issues_to_report') ?>
                    </div>
                   </div>
                </div>
             </div>
             <div class="row">
                <div class="col-md-12">
                   <div class="field-set">
                      <div class="caption-wraper"><label class="field_label"><?php echo $frm->getField('issue_resolve_type')->getCaption() ?><span class="spn_must_field">*</span></label></div>
                      <div class="field-wraper">
                         <div class="field_cover"><?php echo $frm->getFieldHtml('issue_resolve_type') ?></div>
                      </div>
                   </div>
                </div>
             </div>
             <div class="row">
                <div class="col-md-12">
                   <div class="field-set">
                      <div class="caption-wraper"><label class="field_label"><?php echo $frm->getField('issue_reported_msg')->getCaption() ?><span class="spn_must_field">*</span></label></div>
                      <div class="field-wraper">
                         <div class="field_cover"><?php echo $frm->getFieldHtml('issue_reported_msg') ?></div>
                      </div>
                   </div>
                </div>
             </div>
             <div class="row">
                <div class="col-md-12">
                   <div class="field-set">
                      <div class="caption-wraper"><label class="field_label"></label></div>
                      <div class="field-wraper">
                         <div class="field_cover"><?php echo $frm->getFieldHtml('submit') ?></div>
                      </div>
                   </div>
                </div>
             </div>
             <?php echo $frm->getFieldHtml('slesson_id') ?>
          </div>
       </div>
       <?php endif; ?>
    </form>
    <?php echo $frm->getExternalJs(); ?>
</div>

<script>

</script>