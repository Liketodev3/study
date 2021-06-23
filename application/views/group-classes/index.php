<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $frmSrch->setFormTagAttribute('onSubmit', 'search(this); return(false);');
$frmSrch->setFormTagAttribute('class', 'group-class-search');
$frmSrch->setFormTagAttribute('id', 'group-class-search');
$frmSrch->getField('btnSrchSubmit')->setFieldTagAttribute('class', 'form__action');
$keywordField = $frmSrch->getField('keyword');
$keywordField->setFieldTagAttribute('class', 'keyword-search');
$keywordField->setFieldTagAttribute('placeholder', Label::getLabel('LBL_Search_By_Name_and_Keyword...'));

$language = $frmSrch->getField('language');
$language->setFieldTagAttribute('class', 'd-none');
$language->setFieldTagAttribute('id', 'language');
?>
<!-- [ MAIN BODY ========= -->

<section class="section--gray">


    <div class="main__head">
        <div class="container container--narrow">
          
                <?php echo $frmSrch->getFormTag(); 
                echo $language->getHTML();
                ?>
                
                    <div class="filter-primary">
                    <div class="filter-row">
                        <div class="filter-colum">
                            <div class="filter">
                                <div class="filter__trigger filter__trigger--arrow filter__trigger--large filter__trigger--outlined filter-trigger-js">
                                    <svg class="icon icon--language">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#language'; ?>"></use>
                                    </svg>
                                    <input class="filter__input filter__input-js" readonly id="teachLang" type="text" name="teachLang" placeholder="<?php echo $language->selectCaption; ?>">
                                </div>
                                <div class="filter__target filter-target-js" style="display: none;">
                                    <div class="dropdown-listing">
                                        <ul>
                                        <li class="is--active"><a href="javascript:void(0);" class="select-teach-lang-js" data-id=""><?php echo $language->selectCaption; ?></a></li>
                                        <?php foreach ($language->options as $key => $value) { ?>
                                            <li><a href="javascript:void(0);" class="select-teach-lang-js" data-id="<?php echo $key; ?>"><?php echo $value; ?></a></li>
                                        <?php  } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                     
                       
                        <div class="filter-colum filter-colum--large">
							<div class="filter">
								<div class="filter__trigger filter__trigger--large filter__trigger--outlined">
									<div class="filter-search">
                                     <?php echo $keywordField->getHTML(); ?>
                                    <svg class="icon icon--search search-group-class-js" >
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#search'; ?>"></use>
                                    </svg>
									</div>
								</div>
							</div>
						</div>
                        </div>
                       

                 
                </div>
                </form>
          
        </div>
    </div>
    <div class="main__body">
        <div class="container container--narrow" id="listingContainer">
        </div>
    </div>

</section>

<!-- ] -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>