<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

if (isset($slides) && count($slides)) {
    $this->includeTemplate('home/_partial/homePageSlides.php', ['slides' => $slides, 'siteLangId' => $siteLangId], false); //prehook
}

$this->includeTemplate('home/_partial/secondBlockAftrSlider.php');
$this->includeTemplate('home/_partial/languagesWithTeachersCount.php');
$this->includeTemplate('home/_partial/topRatedTeachers.php');
$this->includeTemplate('home/_partial/browseTutor.php');
$this->includeTemplate('home/_partial/upcomingGroupClasses.php');
$this->includeTemplate('home/_partial/testmonials.php');
$this->includeTemplate('home/_partial/homePageHowItWorks.php');
$this->includeTemplate('home/_partial/blogGrids.php');
