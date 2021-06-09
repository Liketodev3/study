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
$this->includeTemplate('teachers/_partial/teacherTopFilters.php',['frmTeacherSrch' => $frmTeacherSrch, 'daysArr' => $daysArr, 'timeSlotArr' => $timeSlotArr, 'keywordlanguage' => $keywordlanguage, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice , 'keyword'=>$keyword, 'spokenLanguage_filter' => $spokenLanguage_filter, 'preferenceFilter_filter'=> $preferenceFilter_filter, 'fromCountry_filter' => $fromCountry_filter, 'gender_filter' => $gender_filter, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'siteLangId' => $siteLangId]);
/* ] */
?>

<section class="section--gray">
<div class="main__body">
	<div class="container container--narrow">
		<div class="listing-cover" id="teachersListingContainer">
	
		</div>
	</div>
</div>

</section>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href);
}
</script>
