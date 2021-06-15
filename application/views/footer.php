<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
</div>
<footer class="footer">
    <section class="section section--footer">
        <div class="container container--narrow">     
            <?php $this->includeTemplate('footer/footerRowOne.php',['siteLangId'=>$siteLangId]);  ?>
            <?php $this->includeTemplate('footer/footerRowSecond.php',['siteLangId'=>$siteLangId]);  ?>
            <?php $this->includeTemplate('footer/footerRowThird.php',['siteLangId'=>$siteLangId]);  ?>
        </div>
    </section>
    <?php $this->includeTemplate('footer/copyRightSection.php');  ?>

</footer>
<div class="loading-wrapper" style="display: none;">
    <div class="loading">
        <div class="inner rotate-one"></div>
        <div class="inner rotate-two"></div>
        <div class="inner rotate-three"></div>
    </div>
</div>
</body>

</html>