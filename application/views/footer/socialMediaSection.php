<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if( $rows ){ ?>
<!-- <div class="toggle-group">
	<h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_Social'); ?></h5>
	<div class="toggle__target toggle__target-js">
		<div class="social-links">
			<ul>
			<?php foreach( $rows as $row ){
				$img = AttachedFile::getAttachment( AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $row['splatform_id'] );
				$title = ( $row['splatform_title'] != '' ) ? $row['splatform_title'] : $row['splatform_identifier'];
            ?>
                <li><a title="<?php echo $title; ?>" <?php if($row['splatform_url']!=''){?>target="_blank" <?php }?> href="<?php echo ($row['splatform_url']!='')?$row['splatform_url']:'javascript:void(0)';?>">
                <?php if( $img ){
					echo '<img src = "'.CommonHelper::generateUrl('Image','SocialPlatform',array($row['splatform_id'])).'" alt=""/>';
				} else{ ?>
                    <img src="<?php echo CONF_WEBROOT_URL; ?>images/social_1.svg" alt="">
				<?php } ?>
                </a></li>
			<?php } ?>
			</ul>
		</div>
	</div>
</div> -->


<div class="col-md-3">
                        <div class="footer-group toggle-group">
                            <div class="footer__group-title toggle-trigger-js">
                                <h5><?php echo Label::getLabel('LBL_Social'); ?></h5>
                            </div>
                            <div class="footer__group-content toggle-target-js">
                                <div class="bullet-list">
                                    <ul class="footer_social-links">
									<?php foreach( $rows as $row ){ ?>
										<li>
                                            <a title="<?php echo $title; ?>" <?php if($row['splatform_url']!=''){?>target="_blank" <?php }?> href="<?php echo ($row['splatform_url']!='')?$row['splatform_url']:'javascript:void(0)';?>">
                                                <svg class="icon icon--facebook"><use xlink:href="images/sprite.yo-coach.svg#facebook"></use></svg>
                                                <span><?php echo $row['splatform_identifier']; ?></span>
                                            </a>
                                        </li>
									<?php } ?>


                                        
                                       
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } ?>
