<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="row footer--row">
<?php
$this->includeTemplate('footer/supportSection.php',['siteLangId'=>$siteLangId]);
$this->includeTemplate('footer/contactSection.php',['siteLangId'=>$siteLangId]);
$this->includeTemplate('footer/socialMediaSection.php',['siteLangId'=>$siteLangId]);
$this->includeTemplate('footer/langCurSection.php',['siteLangId'=>$siteLangId]);
?>
</div>



