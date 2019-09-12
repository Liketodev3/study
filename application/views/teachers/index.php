<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<!--header section [-->
<?php 
/* Teacher Top Filters [ */
$this->includeTemplate('teachers/_partial/teacherTopFilters.php', array('frmTeacherSrch' => $frmTeacherSrch, 'daysArr' => $daysArr, 'timeSlotArr' => $timeSlotArr, 'keywordlanguage' => $keywordlanguage, 'teachLagId' => $teachLagId, 'searchLang' => $searchLang ) ); 
/* ] */
?>


<?php 
/* <div class="section__tags">
	<div class="container container--fixed">
		<div class="tag-list">
			<ul>
				<li><a href="#">Clear</a></li>
				<li><a href="#" class="tag__clickable">Learning: English</a></li>
			</ul>
		</div>
	</div>
</div> */
?>
<!-- header section ]-->


<section class="section section--gray section--listing section--listing-js">
    <div class="container container--narrow">

        <div class="row">
            <div class="col-xl-3 col-lg-12"></div>
            <div class="col-xl-9 col-lg-12">
                <p><?php echo Label::getLabel('LBL_Showing'); ?> <span id="start_record">{xx}</span>-<span id="end_record">{xx}</span> <?php echo Label::getLabel('LBL_of'); ?> <span id="total_records">{xx}</span> <?php echo Label::getLabel('LBL_teachers'); ?></p>
            </div>
        </div>

        <div class="row d-block -clearfix">
            <?php 
			/* Left Side Filters Side Bar [ */
			$this->includeTemplate('teachers/_partial/teacherLeftFilters.php'); 
			/* ] */
			?>

            <div class="col-xl-9 col-lg-12 order-2 -float-right" id="teachersListingContainer">

            </div>

            <div class="col-xl-3 col-lg-12 -float-left">
                <div class="box box--cta -padding-30 -align-center">
                    <h4 class="-text-bold"><?php echo Label::getLabel('LBL_Want_to_be_a_teacher?'); ?></h4>
                    <p><?php $str = Label::getLabel( 'LBL_If_you\'re_interested_in_being_a_teacher_on_{sitename},_please_apply_here.' ); 
					 $siteName = FatApp::getConfig( 'CONF_WEBSITE_NAME_'.$siteLangId, FatUtility::VAR_STRING, '' );
					 $str = str_replace( "{sitename}", $siteName, $str );
					 echo $str;
					 ?></p>
                    <a href="javascript:void(0)" onClick="signUpFormPopUp('teacher');" class="btn btn--primary btn--block"><?php echo Label::getLabel('LBL_Apply_to_be_a_teacher'); ?></a>
                </div>
            </div>

        </div>
    </div>
</section>
