<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $frmSrch->setFormTagAttribute('onSubmit', 'search(this); return(false);');
$frmSrch->setFormTagAttribute('class', 'group-class-search');
$frmSrch->getField('btnSrchSubmit')->setFieldTagAttribute('class', 'form__action');
$frmSrch->getField('keyword')->setFieldTagAttribute('class', 'keyword-search');
?>
<!-- [ MAIN BODY ========= -->

<section class="section--gray">


    <div class="main__head">
        <div class="container container--narrow">
            <div class="filter-wrapper filter--group">
                <div class="filter-form">
                    <div class="filter__primary">
                        <div class="filter-form__inner">
                            <div class="filter__head filter__head-trigger filter-trigger-js">
                                <svg class="icon icon--language">
                                    <use xlink:href="images/sprite.yo-coach.svg#language"></use>
                                </svg>
                                <input class="filter__input filter__input-js" type="text" placeholder="English">
                            </div>
                            <div class="filter__body filter__body-target filter-target-js" style="display: none;">
                                <div class="dropdown-listing">
                                    <ul>
                                        <li><a href="#">Engilsh</a></li>
                                        <li class="is--active"><a href="#">Spanish</a></li>
                                        <li><a href="#">German</a></li>
                                        <li><a href="#">French</a></li>
                                        <li><a href="#">Dutch</a></li>
                                        <li><a href="#">Hindi</a></li>
                                        <li><a href="#">Punjabi</a></li>
                                        <li><a href="#">Tamil</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="filter-form__inner filter--search">
                            <div class="filter__head">
                                <input type="text" placeholder="Search By Name and Keyword...">
                                <svg class="icon icon--search">
                                    <use xlink:href="images/sprite.yo-coach.svg#search"></use>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="main__body">
        <div class="container container--narrow" id="listingContainer">
           
        </div>
    </div>

</section>

<!-- ] -->
<section class="section section--search">
    <div class="container container--narrow">
        <div class="row justify-content-between align-items-center">

            <div class="col-xl-4 col-lg-4 col-md-4">
                <h1><?php echo Label::getLabel("LBL_Group_Classes") ?></h1>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 justify-content-end d-flex">
                <div class="form-search form-search--inline">
                    <?php echo $frmSrch->getFormTag(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form__element">
                                <?php echo $frmSrch->getFieldHtml('language'); ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form__element">
                                <?php echo $frmSrch->getFieldHtml('custom_filter'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form__element">
                                <?php echo $frmSrch->getFieldHtml('keyword'); ?>
                                <span class="form__action-wrap">
                                    <?php echo $frmSrch->getFieldHtml('btnSrchSubmit'); ?>
                                    <span class="svg-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="14.843" viewBox="0 0 14.844 14.843">
                                            <path d="M251.286,196.714a4.008,4.008,0,1,1,2.826-1.174A3.849,3.849,0,0,1,251.286,196.714Zm8.241,2.625-3.063-3.062a6.116,6.116,0,0,0,1.107-3.563,6.184,6.184,0,0,0-.5-2.442,6.152,6.152,0,0,0-3.348-3.348,6.271,6.271,0,0,0-4.884,0,6.152,6.152,0,0,0-3.348,3.348,6.259,6.259,0,0,0,0,4.884,6.152,6.152,0,0,0,3.348,3.348,6.274,6.274,0,0,0,6-.611l3.063,3.053a1.058,1.058,0,0,0,.8.34,1.143,1.143,0,0,0,.813-1.947h0Z" transform="translate(-245 -186.438)"></path>
                                        </svg>
                                    </span>
                                </span>
                            </div>

                        </div>
                    </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
    </div>
    <section class="section section--gray section--listing section--listing-js">
        <div class="container container--narrow">

            <div id="listingContainer">
            </div>
        </div>
    </section>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>