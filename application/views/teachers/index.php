<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<!--header section [-->
<?php
$minPrice = 0;
$maxPrice = 0;
$keyword = '';
$spokenLanguage_filter = array();
$preferenceFilter_filter = array();
$fromCountry_filter = array();
$gender_filter = array();
$filters  = array();
$keywordlanguage = '';
if ( isset( $_SESSION['search_filters'] ) && !empty( $_SESSION['search_filters'] )) {
	$filters = $_SESSION['search_filters'];

	if ( isset($filters['spokenLanguage']) && !empty( $filters['spokenLanguage'] ) ) {
		$spokenLanguage_filter = explode(',', $filters['spokenLanguage']);
	}

	if ( isset($filters['minPriceRange']) && isset($filters['maxPriceRange']) ) {
		$minPrice =  FatUtility::float($filters['minPriceRange']);
		$maxPrice =  FatUtility::float($filters['maxPriceRange']);
	}

	if ( isset($filters['preferenceFilter']) && !empty( $filters['preferenceFilter'] ) ) {
		$preferenceFilter_filter = explode(',', $filters['preferenceFilter']);
	}

	if ( isset($filters['fromCountry']) && !empty( $filters['fromCountry'] ) ) {
		$fromCountry_filter = explode(',', $filters['fromCountry']);
	}

	if ( isset($filters['gender']) && !empty( $filters['gender'] ) ) {
		$gender_filter = explode(',', $filters['gender']);
	}
	if ( isset($filters['teach_language_name']) && !empty( $filters['teach_language_name'] ) ) {
		$keywordlanguage = $filters['teach_language_name'];
	}
	if ( isset($filters['keyword']) && !empty( $filters['keyword'] ) ) {
		$keyword = $filters['keyword'];
	}

}


/* Teacher Top Filters [ */
$this->includeTemplate('teachers/_partial/teacherTopFilters.php', array('frmTeacherSrch' => $frmTeacherSrch, 'daysArr' => $daysArr, 'timeSlotArr' => $timeSlotArr, 'keywordlanguage' => $keywordlanguage, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice , 'keyword' => $keyword ) );
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
                <p class="d-none"><?php echo Label::getLabel('LBL_Showing'); ?> <span id="start_record">{xx}</span>-<span id="end_record">{xx}</span> <?php echo Label::getLabel('LBL_of'); ?> <span id="total_records">{xx}</span> <?php echo Label::getLabel('LBL_teachers'); ?></p>
            </div>
        </div>

		<div class="row -clearfix">
            <?php
			/* Left Side Filters Side Bar [ */
			$this->includeTemplate('teachers/_partial/teacherLeftFilters.php', array( 'spokenLanguage_filter' => $spokenLanguage_filter, 'preferenceFilter_filter'=> $preferenceFilter_filter, 'fromCountry_filter' => $fromCountry_filter, 'gender_filter' => $gender_filter, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'siteLangId' => $siteLangId ));
			/* ] */
			?>

            <div class="col-xl-9 col-lg-12 -float-right" id="teachersListingContainer">

            </div>

            <div class="col-xl-3 col-lg-12 -float-left d-block d-xl-none">
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
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
