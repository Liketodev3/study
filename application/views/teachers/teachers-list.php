<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php if( $teachers ){ ?>
	<div class="result-container" >
	<?php

	foreach( $teachers as $teacher ){
		$teacherUrl = CommonHelper::generateUrl('Teachers').'/'. $teacher['user_url_name'];
		?>
		<div class="box box-list -padding-30 -hover-shadow -transition">
			<div class="box__content">
				<div class="row">
				
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 -align-center">
						<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($teacher['user_first_name']); ?>">
							<?php 
							if( true == User::isProfilePicUploaded( $teacher['user_id'] ) ){
								$img = CommonHelper::generateUrl('Image','User', array( $teacher['user_id'] )); 
								echo '<a href="'.$teacherUrl.'"><img src="'.$img.'" /></a>';
							}
							?>
							
							<?php /* if( $teacher['is_online'] ){ ?>
							<span class="tag-online"></span>
							<?php } */ ?>
						</div>
						<span class="-gap"></span>
						<div class="box__price">
							<?php echo Label::getLabel('LBL_Hourly_Rate'); ?>
							<h6><?php echo CommonHelper::displayMoneyFormat( $teacher['minPrice'] ); ?></h6>
						</div>
					</div>
					
					<div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
						<div class="box-list__head row justify-content-between">
							<div class="col-xl-9 col-lg-9 col-md-8">
								<h3 class="-display-inline"><a href="<?php echo $teacherUrl; ?>"><?php echo $teacher['user_first_name']." ". $teacher['user_last_name']; ?></a></h3>
								
								<?php if( $teacher['user_country_id'] > 0 ){ ?>
								<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($teacher['user_country_id'], 'DEFAULT') ); ?>" alt=""></span>
								<?php } ?>

								<div class="ratings -display-inline">
                                    <span class="ratings__star -display-inline">
                                        <?php //if(round($teacher['teacher_rating'])){ 
                                        for($i=0;$i<round($teacher['teacher_rating']);$i++){ ?>
                                        <img src="<?php echo CONF_WEBROOT_URL; ?>images/star-filled.svg" alt="">
                                        <?php } 
                                        for($i=0;$i< 5-round($teacher['teacher_rating']);$i++){ ?>
                                        <img src="<?php echo CONF_WEBROOT_URL; ?>images/star-empty.svg" alt="">
                                        <?php } //} ?>    
                                    </span>
									<?php if($teacher['totReviews']){ ?><span class="ratings__count -display-inline"><a href="<?php echo $teacherUrl; ?>" class="-link-underline"><?php echo $teacher['totReviews'].' '.Label::getLabel('LBL_Reviews');; ?></a></span>
									<?php } ?>
								</div>
							</div>
							
							<div class="col-auto">
								<a href="javascript:void(0)" onClick="toggleTeacherFavorite(<?php echo $teacher['user_id']; ?>)" class="btn btn--small btn--bordered btn--fav <?php echo($teacher['uft_id'])?'is-active':'';?>">
								<span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="12" viewBox="0 0 14 12">
								<path d="M1313.03,714.438l-4.53,4.367-4.54-4.375a4.123,4.123,0,0,1-1.46-2.774,3.455,3.455,0,0,1,.17-1.117,2.067,2.067,0,0,1,.43-0.769,1.972,1.972,0,0,1,.63-0.465,2.818,2.818,0,0,1,.74-0.242,4.378,4.378,0,0,1,.76-0.063,2.222,2.222,0,0,1,.88.2,3.987,3.987,0,0,1,.86.5,8.351,8.351,0,0,1,.68.563,6.435,6.435,0,0,1,.47.48,0.506,0.506,0,0,0,.76,0,6.435,6.435,0,0,1,.47-0.48,8.351,8.351,0,0,1,.68-0.563,3.987,3.987,0,0,1,.86-0.5,2.222,2.222,0,0,1,.88-0.2,4.378,4.378,0,0,1,.76.063,2.818,2.818,0,0,1,.74.242,1.972,1.972,0,0,1,.63.465,2.067,2.067,0,0,1,.43.769,3.455,3.455,0,0,1,.17,1.117,4.126,4.126,0,0,1-1.47,2.782h0Zm1.48-5.469a3.76,3.76,0,0,0-2.74-.969,3.064,3.064,0,0,0-.99.168,3.963,3.963,0,0,0-.94.453q-0.435.285-.75,0.535c-0.2.167-.4,0.344-0.59,0.532-0.19-.188-0.39-0.365-0.59-0.532s-0.46-.345-0.75-0.535a3.963,3.963,0,0,0-.94-0.453,3.064,3.064,0,0,0-.99-0.168,3.76,3.76,0,0,0-2.74.969,3.586,3.586,0,0,0-.99,2.687,3.479,3.479,0,0,0,.18,1.078,5.7,5.7,0,0,0,.42.946,7.129,7.129,0,0,0,.53.761c0.2,0.248.35,0.418,0.44,0.512a2.683,2.683,0,0,0,.21.2l4.88,4.7a0.48,0.48,0,0,0,.68,0l4.87-4.687a5.122,5.122,0,0,0,1.79-3.516A3.586,3.586,0,0,0,1314.51,708.969Z" transform="translate(-1301.5 -708)"/>
								</svg></span> <?php echo Label::getLabel('LBL_Favorite'); ?>
								</a>
							</div>

						</div>
						
						<div class="box-list__body">
							<div class="grid-group">
								<div class="grid"><span><?php echo Label::getLabel('LBL_Teaches'); ?>: <strong><?php echo CommonHelper::getTeachLangs($teacher['utl_slanguage_ids']); ?></strong></span></div>
								<div class="grid"><span><?php echo Label::getLabel('LBL_From'); ?>: <strong><?php echo $teacher['user_country_name']; ?></strong></span></div>
								<div class="grid"><span><?php echo Label::getLabel('LBL_Lessons'); ?>: <strong><?php echo $teacher['teacherTotLessons']; ?></strong></span></div>
								<div class="grid"><span><?php echo Label::getLabel('LBL_Students'); ?>: <strong><?php echo $teacher['studentIdsCnt']; ?></strong></span></div>
							</div>
							
							
							<?php
							/* Spoken Languages[ */
							$this->includeTemplate('teachers/_partial/spokenLanguages.php', $teacher, false); 
							/* ] */
							?>

							<?php if( !empty($teacher['user_profile_info']) ){ ?>
							<div class="box__description">
								<p><?php  echo nl2br( $teacher['user_profile_info'] ); ?></p>
							</div>
							<?php } else { echo '<p></p>'; } ?>
							
							<a href="javascript:void(0)" onClick="viewCalendar(<?php echo $teacher['user_id']?>,'paid');" class="btn btn--bordered box__action-js">
								<span class="svg-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="16" viewBox="0 0 14.844 16">
								<path d="M563.643,153.571h2.571v2.572h-2.571v-2.572Zm3.143,0h2.857v2.572h-2.857v-2.572Zm-3.143-3.428h2.571V153h-2.571v-2.857Zm3.143,0h2.857V153h-2.857v-2.857ZM563.643,147h2.571v2.571h-2.571V147Zm6.571,6.571h2.857v2.572h-2.857v-2.572ZM566.786,147h2.857v2.571h-2.857V147Zm6.857,6.571h2.571v2.572h-2.571v-2.572Zm-3.429-3.428h2.857V153h-2.857v-2.857Zm-3.227-4.656a0.278,0.278,0,0,1-.2.084h-0.572a0.287,0.287,0,0,1-.285-0.285v-2.572a0.287,0.287,0,0,1,.285-0.285h0.572a0.287,0.287,0,0,1,.285.285v2.572A0.278,0.278,0,0,1,566.987,145.487Zm6.656,4.656h2.571V153h-2.571v-2.857ZM570.214,147h2.857v2.571h-2.857V147Zm3.429,0h2.571v2.571h-2.571V147Zm0.2-1.513a0.278,0.278,0,0,1-.2.084h-0.572a0.289,0.289,0,0,1-.285-0.285v-2.572a0.289,0.289,0,0,1,.285-0.285h0.572a0.289,0.289,0,0,1,.286.285v2.572A0.279,0.279,0,0,1,573.844,145.487Zm3.174-1.576a1.1,1.1,0,0,0-.8-0.34h-1.143v-0.857a1.431,1.431,0,0,0-1.428-1.428h-0.572a1.432,1.432,0,0,0-1.428,1.428v0.857h-3.429v-0.857a1.431,1.431,0,0,0-1.428-1.428h-0.572a1.431,1.431,0,0,0-1.428,1.428v0.857h-1.143a1.16,1.16,0,0,0-1.143,1.143v11.429a1.16,1.16,0,0,0,1.143,1.143h12.571a1.16,1.16,0,0,0,1.143-1.143V144.714A1.1,1.1,0,0,0,577.018,143.911Z" transform="translate(-562.5 -141.281)"/>
								</svg>
								</span><?php echo Label::getLabel('LBL_Availability'); ?></a> &nbsp; &nbsp;

								<!--a href="<?php echo CommonHelper::generateUrl('Messages','initiate', array(CommonHelper::encryptId($teacher['user_id'])))?>" class="btn btn--bordered"-->
								<a href="javascript:void(0)" onClick="generateThread(<?php echo $teacher['user_id']; ?>)" class="btn btn--bordered">
								<span class="svg-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="11.782" viewBox="0 0 15 11.782">
								<path d="M1032.66,878.814q-2.745,1.859-4.17,2.888c-0.31.234-.57,0.417-0.77,0.548a4.846,4.846,0,0,1-.79.4,2.424,2.424,0,0,1-.92.2h-0.02a2.424,2.424,0,0,1-.92-0.2,4.846,4.846,0,0,1-.79-0.4c-0.2-.131-0.46-0.314-0.77-0.548-0.76-.552-2.14-1.515-4.16-2.888a4.562,4.562,0,0,1-.85-0.728v6.646a1.3,1.3,0,0,0,.39.946,1.309,1.309,0,0,0,.95.393h12.32a1.309,1.309,0,0,0,.95-0.393,1.3,1.3,0,0,0,.39-0.946v-6.646a4.545,4.545,0,0,1-.84.728h0Zm0.44-4.135a1.287,1.287,0,0,0-.94-0.393h-12.32a1.189,1.189,0,0,0-.99.435,1.7,1.7,0,0,0-.35,1.088,1.933,1.933,0,0,0,.46,1.143,4.157,4.157,0,0,0,.98.967c0.19,0.133.76,0.531,1.72,1.192s1.68,1.171,2.19,1.528c0.05,0.039.17,0.124,0.35,0.255s0.34,0.238.46,0.318,0.26,0.172.43,0.272a2.493,2.493,0,0,0,.48.226,1.308,1.308,0,0,0,.42.076h0.02a1.308,1.308,0,0,0,.42-0.076,2.493,2.493,0,0,0,.48-0.226c0.17-.1.31-0.191,0.43-0.272s0.27-.187.46-0.318,0.3-.216.35-0.255q0.765-.535,3.92-2.72a3.887,3.887,0,0,0,1.02-1.03,2.238,2.238,0,0,0,.41-1.264A1.267,1.267,0,0,0,1033.1,874.679Z" transform="translate(-1018.5 -874.281)"/>
								</svg>
								</span><?php echo Label::getLabel('LBL_Message'); ?></a>

						</div>
					</div>

				</div>
			</div>
			
			<?php
			/* <div class="box__slip -skin box__slip-js">
				<a href="javascript:void(0)" class="-link-close box__actions-close-js"></a>
				<div class="box__slip-data">
					<div class="box__tabs tabs-js">
						<ul>
							<li class="is-active"><a href="#tab_1">Availability</a></li>
							<li><a href="#tab_2">Video</a></li>
						</ul>
					</div>

					<!--tab_1 start here-->
					<div id="tab_1" class="tabs-content-js">
						<div class="box__calender">
							<ul>
								<li>
									<span class="span time"></span>
									<span class="span days">Sun</span>
									<span class="span days">Mon</span>
									<span class="span days">Tue</span>
									<span class="span days">Wed</span>
									<span class="span days">Thu</span>
									<span class="span days">Fri</span>
									<span class="span days">Sat</span>
								</li>
								<li>
									<span class="span time">Morning</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">5hrs</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">3hrs</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
									<span class="span"><span class="point"></span></span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">2hrs</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
								</li>
								<li>
									<span class="span time">Noon</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">6hrs</span>
									</span>
									</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">4hrs</span>
									</span>
									</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">2hrs</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">1hr</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">3hrs</span>
									</span>
									</span>
								</li>
								<li>
									<span class="span time">Evening</span>
									<span class="span"><span class="point"></span></span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">5hrs</span>
									</span>
									</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">6hrs</span>
									</span>
									</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">3hrs</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
									<span class="span"><span class="point"></span></span>
									<span class="span"><span class="point"></span></span>
								</li>
								<li>
									<span class="span time">Night</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">2hrs</span>
									</span>
									</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">6hrs</span>
									</span>
									</span>
									<span class="span">
										 <span class="point is-available tooltip tooltip--centered">
											  <span class="tooltip__content">3hrs</span>
									</span>
									</span>
									<span class="span"><span class="point"></span></span>
									<span class="span"><span class="point"></span></span>
									<span class="span"><span class="point"></span></span>
									<span class="span"><span class="point"></span></span>
								</li>
							</ul>
						</div>

						<a href="javascript:void(0)" class="btn btn--secondary btn--small btn--wide">Book</a>
					</div>
					<!--tab_1 end here-->

					<!--tab_2 start here-->
					<div id="tab_2" class="tabs-content-js">
						<div class="video">
							
						</div>
					</div>
					<!--tab_2 end here-->

				</div>
			</div> */
			?>
			
		</div>
<?php
	}
	?>
	</div>
	<!--<div class="load-more -align-center">
		<a href="#" class="btn btn--bordered btn--xlarge">Load More</a>
	</div>-->
	<?php
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
			'name' => 'frmTeacherSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	
} else {
	?>
	<div class="box -padding-30" style="margin-bottom: 30px;">
		<div class="message-display">
			<div class="message-display__icon">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
				<path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
				</svg>
			</div>

			<h5><?php echo Label::getLabel('LBL_No_Result_found!!'); ?></h5>
		</div>
	</div>
	<?php
	
} ?>