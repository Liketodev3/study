<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

  <?php if( isset($slides) && count($slides) ){
        $homePageSlides =  FatCache::get('homePageSlides', CONF_HOME_PAGE_CACHE_TIME, '.txt');
        if (!$homePageSlides) {
            $homePageSlides = $this->includeTemplate( 'home/_partial/homePageSlides.php', array( 'slides' =>$slides, 'siteLangId' => $siteLangId ), false, true );
            FatCache::set('homePageSlides', $homePageSlides, '.txt');
        }
        echo $homePageSlides;
	} ?>


<?php
    $homePageHowItWorks =  FatCache::get('homePageHowItWorks', CONF_HOME_PAGE_CACHE_TIME, '.txt');
    if (!$homePageHowItWorks) {
        $homePageHowItWorks = $this->includeTemplate( 'home/_partial/homePageHowItWorks.php',array(), true, true);
        FatCache::set('homePageHowItWorks', $homePageHowItWorks, '.txt');
    }
    echo  $homePageHowItWorks;
    // $this->includeTemplate( 'home/_partial/homePageHowItWorks.php' );
?>
<?php $this->includeTemplate( 'home/_partial/upcomingScheduledLessons.php' ); ?>
<?php $this->includeTemplate( 'home/_partial/homePageSlidesAboveFooter.php' ); ?>

<?php $this->includeTemplate( 'home/_partial/topRatedTeachers.php' ); ?>
<?php $this->includeTemplate( 'home/_partial/newsLetterFrm.php',array('newsLetterForm' => $newsLetterForm, 'siteLangId' => $siteLangId) ); ?>
