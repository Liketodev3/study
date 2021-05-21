<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if( !empty( $header_navigation ) ){ ?>
	<span class="overlay overlay--nav toggle--nav-js is-active"></span>
                        <nav class="menu nav--primary-offset">
                            <ul>
	<?php foreach( $header_navigation as $nav ){ ?>
		<?php if( $nav['pages'] ){
							foreach( $nav['pages'] as $link ){
								$navUrl = CommonHelper::getnavigationUrl($link['nlink_type'], $link['nlink_url'], $link['nlink_cpage_id'] ); ?>
								<li class="menu__item"><a target="<?php echo $link['nlink_target']; ?>" href="<?php echo $navUrl; ?>"><?php echo $link['nlink_caption']; ?></a></li>
							<?php }
						} ?>
		<?php } ?>
					</ul>
					</nav>		
	<?php } ?>
