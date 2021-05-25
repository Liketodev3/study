<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
</div><!-- div id=body class=body ends here -->
  <footer class="footer"> 
        <section class="section section--footer">
            <div class="container container--narrow">
            <?php $this->includeTemplate('footer/footerRowOne.php');  ?> 
            <?php $this->includeTemplate('footer/footerRowSecond.php');  ?> 
            <?php $this->includeTemplate('footer/footerRowThird.php');  ?> 
            </div>
        </section>
        <?php $this->includeTemplate('footer/copyRightSection.php');  ?> 

    </footer>
</body>

</html>