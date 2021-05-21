<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

  <?php if( isset($slides) && count($slides) ){	
		$this->includeTemplate( 'home/_partial/homePageSlides.php', array( 'slides' =>$slides, 'siteLangId' => $siteLangId ),false );
	} ?>

<?php $this->includeTemplate( 'home/_partial/secondBlockAftrSlider.php' ); ?>
<?php $this->includeTemplate('home/_partial/languagesWithTeachersCount.php'); ?>
<?php $this->includeTemplate( 'home/_partial/topRatedTeachers.php' ); ?>
<?php  $this->includeTemplate( 'home/_partial/staticBanner.php' ); ?>
<!-- <?php $this->includeTemplate( 'home/_partial/homePageSlidesAboveFooter.php' ); ?> -->

<?php $this->includeTemplate( 'home/_partial/newsLetterFrm.php',array('newsLetterForm' => $newsLetterForm, 'siteLangId' => $siteLangId) ); ?>
