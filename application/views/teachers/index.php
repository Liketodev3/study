<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<!--header section [-->
<?php
$minPrice = 0;
$maxPrice = 0;
$keyword = '';
$spokenLanguage_filter = array();
$preferenceFilter_filter = array();
$fromCountry_filter = array();
$gender_filter = array();
$filters  = array();
$keywordlanguage = '';
if ( isset( $_SESSION['search_filters'] ) && !empty( $_SESSION['search_filters'] )) {
	$filters = $_SESSION['search_filters'];

	if ( isset($filters['spokenLanguage']) && !empty( $filters['spokenLanguage'] ) ) {
		$spokenLanguage_filter = explode(',', $filters['spokenLanguage']);
	}

	if ( isset($filters['minPriceRange']) && isset($filters['maxPriceRange']) ) {
		$minPrice =  FatUtility::float($filters['minPriceRange']);
		$maxPrice =  FatUtility::float($filters['maxPriceRange']);
	}

	if ( isset($filters['preferenceFilter']) && !empty( $filters['preferenceFilter'] ) ) {
		$preferenceFilter_filter = explode(',', $filters['preferenceFilter']);
	}

	if ( isset($filters['fromCountry']) && !empty( $filters['fromCountry'] ) ) {
		$fromCountry_filter = explode(',', $filters['fromCountry']);
	}

	if ( isset($filters['gender']) && !empty( $filters['gender'] ) ) {
		$gender_filter = explode(',', $filters['gender']);
	}
	if ( isset($filters['teach_language_name']) && !empty( $filters['teach_language_name'] ) ) {
		$keywordlanguage = $filters['teach_language_name'];
	}
	if ( isset($filters['keyword']) && !empty( $filters['keyword'] ) ) {
		$keyword = $filters['keyword'];
	}

}

/* Teacher Top Filters [ */
$this->includeTemplate('teachers/_partial/teacherTopFilters.php', array('frmTeacherSrch' => $frmTeacherSrch, 'daysArr' => $daysArr, 'timeSlotArr' => $timeSlotArr, 'keywordlanguage' => $keywordlanguage, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice , 'keyword'=>$keyword));
/* ] */
?>

<section class="section--gray">
<div class="main__body">
	<div class="container container--narrow">
		<div class="listing-cover" id="teachersListingContainer">
			<div class="listing__head">
				<div class="listing__title">
					<h4>Found the best <b>264 English</b> teachers for you.</h4>
				</div>
				<div class="listing__shorting">
					<!-- <b>Sort By:</b> -->
					<select name="sort" id="sort">
						
						<option value="volvo">Popularity</option>
						<option value="saab">Lorem</option>
						<option value="opel">Lorem</option>
						<option value="audi">Lorem</option>
					</select>

					<div class="btn--filter">
						<a href="javascript:void(0)" class="btn btn--primary btn--block btn--filters-js">
						<span class="svg-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 402.577 402.577" style="enable-background:new 0 0 402.577 402.577;" xml:space="preserve">
						<g>
						<path d="M400.858,11.427c-3.241-7.421-8.85-11.132-16.854-11.136H18.564c-7.993,0-13.61,3.715-16.846,11.136
						c-3.234,7.801-1.903,14.467,3.999,19.985l140.757,140.753v138.755c0,4.955,1.809,9.232,5.424,12.854l73.085,73.083
						c3.429,3.614,7.71,5.428,12.851,5.428c2.282,0,4.66-0.479,7.135-1.43c7.426-3.238,11.14-8.851,11.14-16.845V172.166L396.861,31.413
						C402.765,25.895,404.093,19.231,400.858,11.427z"></path>
						</g>
						</svg></span>
						Filters</a>
					</div>
				</div>
			</div>
			<div class="listing__body">
				
				<div class="box-wrapper" id="teachersListingContainer">

					<div class="box box-list ">
						<div class="box__primary">
							<div class="list__head">
								<div class="list__media ">
									<div class="avtar avtar--centered ratio ratio--1by1">
										<a href="#"><img src="images/140x140.png" alt=""></a>
									</div>
								</div>
								<div class="list__price">
									<p>$22.00 / hour</p>
								</div>
								<div class="list__action">
									<a href="#" class="btn btn--primary color-white btn--block">Book Now</a>
									<a href="#" class="btn btn--bordered color-primary btn--block">
										<svg class="icon icon--envelope"><use xlink:href="images/sprite.yo-coach.svg#envelope"></use></svg>
										Contact
									</a>
								</div>
							</div>
							<div class="list__body">
								<div class="profile-detail">
									 <div class="profile-detail__head">
										<a href="#" class="tutor-name">
											<h4>Steven A. Knight</h4>
											<div class="flag">
												<img src="images/flag-new/flag-uk.png" alt="">
											</div>
										</a>
										<div class="follow ">
											<a class="is--active" href="#">
												<svg class="icon icon--heart"><use xlink:href="images/sprite.yo-coach.svg#heart"></use></svg>
											</a>
										</div>
									 </div>
									 <div class="profile-detail__body">
										<div class="info-wrapper">
											<div class="info-tag location">
												<svg class="icon icon--location"><use xlink:href="images/sprite.yo-coach.svg#location"></use></svg>
												<span class="lacation__name">Newzeland</span>
											</div>
											<div class="info-tag ratings">
												<svg class="icon icon--rating"><use xlink:href="images/sprite.yo-coach.svg#rating"></use></svg>
												<span class="value">4.5</span>
												<span class="count">(185)</span>
											</div>

											<div class="info-tag list-count">
												<div class="total-count"><span class="value">178</span>Students</div> - <div class="total-count"><span class="value">235</span>Lessons</div>
											</div>
										</div>

										<div class="tutor-info">
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Teaches</h6>
												</div>
												<div class="info__language">
													ENGLISH, FRENCH, GERMAN, ITALIAN, DUTCH
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Also Speaks</h6>
												</div>
												<div class="info__language">
													ENGLISH (Expert), FRENCH (Advanced), GERMAN (Expert)
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>About</h6>
												</div>
											   <p>Hello! My name is Steven. I am happy to help you learn German. My lessons are informative, interesting and friendly. <a href="#">View Profile</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box__secondary">
							<div class="panel-box">
								<div class="panel-box__head">
									<ul>
										<li class="is--active">
											<a class="panel-action" href="#">Availbility</a>
										</li>
										<li>
											<a class="panel-action" href="#">Introduction</a>
										</li>
									</ul>
								</div>
								<div class="panel-box__body">
									<div class="panel-content">
										<div class="custom-calendar">
											<table>
												<thead>
													<tr>
														<th>&nbsp;</th>
														<th>Mon</th>
														<th>tue</th>
														<th>wed</th>
														<th>thu</th>
														<th>fri</th>
														<th>sat</th>
														<th>sun</th> 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-list ">
						<div class="box__primary">
							<div class="list__head">
								<div class="list__media ">
									<div class="avtar avtar--centered ratio ratio--1by1">
										<a href="#"><img src="images/140x140_4.png" alt=""></a>
									</div>
								</div>
								<div class="list__price">
									<p>$24.00 / hour</p>
								</div>
								<div class="list__action">
									<a href="#" class="btn btn--primary color-white btn--block">Book Now</a>
									<a href="#" class="btn btn--bordered color-primary btn--block">
										<svg class="icon icon--envelope"><use xlink:href="images/sprite.yo-coach.svg#envelope"></use></svg>
										Contact
									</a>
								</div>
							</div>
							<div class="list__body">
								<div class="profile-detail">
									 <div class="profile-detail__head">
										<a href="#" class="tutor-name">
											<h4>Kevin Peterson</h4>
											<div class="flag">
												<img src="images/flag-new/flag-uk.png" alt="">
											</div>
										</a>
										<div class="follow ">
											<a href="#">
												<svg class="icon icon--heart"><use xlink:href="images/sprite.yo-coach.svg#heart"></use></svg>
											</a>
										</div>
									 </div>
									 <div class="profile-detail__body">
										<div class="info-wrapper">
											<div class="info-tag location">
												<svg class="icon icon--location"><use xlink:href="images/sprite.yo-coach.svg#location"></use></svg>
												<span class="lacation__name">Newzeland</span>
											</div>
											<div class="info-tag ratings">
												<svg class="icon icon--rating"><use xlink:href="images/sprite.yo-coach.svg#rating"></use></svg>
												<span class="value">4.5</span>
												<span class="count">(185)</span>
											</div>

											<div class="info-tag list-count">
												<div class="total-count"><span class="value">231</span>Students</div> - <div class="total-count"><span class="value">199</span>Lessons</div>
											</div>
										</div>

										<div class="tutor-info">
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Teaches</h6>
												</div>
												<div class="info__language">
													ENGLISH, FRENCH, GERMAN, ITALIAN, DUTCH
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Also Speaks</h6>
												</div>
												<div class="info__language">
													ENGLISH (Expert), FRENCH (Advanced), GERMAN (Expert)
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>About</h6>
												</div>
											   <p>Hello! My name is Steven. I am happy to help you learn German. My lessons are informative, interesting and friendly. <a href="#">View Profile</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box__secondary">
							<div class="panel-box">
								<div class="panel-box__head">
									<ul>
										<li class="is--active">
											<a class="panel-action" href="#">Availbility</a>
										</li>
										<li>
											<a class="panel-action" href="#">Introduction</a>
										</li>
									</ul>
								</div>
								<div class="panel-box__body">
									<div class="panel-content">
										<div class="custom-calendar">
											<table>
												<thead>
													<tr>
														<th>&nbsp;</th>
														<th>Mon</th>
														<th>tue</th>
														<th>wed</th>
														<th>thu</th>
														<th>fri</th>
														<th>sat</th>
														<th>sun</th> 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-list ">
						<div class="box__primary">
							<div class="list__head">
								<div class="list__media ">
									<div class="avtar avtar--centered ratio ratio--1by1">
										<a href="#"><img src="images/140x140_3.png" alt=""></a>
									</div>
								</div>
								<div class="list__price">
									<p>$21.00 / hour</p>
								</div>
								<div class="list__action">
									<a href="#" class="btn btn--primary color-white btn--block">Book Now</a>
									<a href="#" class="btn btn--bordered color-primary btn--block">
										<svg class="icon icon--envelope"><use xlink:href="images/sprite.yo-coach.svg#envelope"></use></svg>
										Contact
									</a>
								</div>
							</div>
							<div class="list__body">
								<div class="profile-detail">
									 <div class="profile-detail__head">
										<a href="#" class="tutor-name">
											<h4>James Anderson</h4>
											<div class="flag">
												<img src="images/flag-new/flag-uk.png" alt="">
											</div>
										</a>
										<div class="follow ">
											<a href="#">
												<svg class="icon icon--heart"><use xlink:href="images/sprite.yo-coach.svg#heart"></use></svg>
											</a>
										</div>
									 </div>
									 <div class="profile-detail__body">
										<div class="info-wrapper">
											<div class="info-tag location">
												<svg class="icon icon--location"><use xlink:href="images/sprite.yo-coach.svg#location"></use></svg>
												<span class="lacation__name">Australia</span>
											</div>
											<div class="info-tag ratings">
												<svg class="icon icon--rating"><use xlink:href="images/sprite.yo-coach.svg#rating"></use></svg>
												<span class="value">4.1</span>
												<span class="count">(214)</span>
											</div>

											<div class="info-tag list-count">
												<div class="total-count"><span class="value">399</span>Students</div> - <div class="total-count"><span class="value">515</span>Lessons</div>
											</div>
										</div>

										<div class="tutor-info">
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Teaches</h6>
												</div>
												<div class="info__language">
													ENGLISH, FRENCH, GERMAN, ITALIAN, DUTCH
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Also Speaks</h6>
												</div>
												<div class="info__language">
													ENGLISH (Expert), FRENCH (Advanced), GERMAN (Expert)
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>About</h6>
												</div>
											   <p>Hello! My name is Steven. I am happy to help you learn German. My lessons are informative, interesting and friendly. <a href="#">View Profile</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box__secondary">
							<div class="panel-box">
								<div class="panel-box__head">
									<ul>
										<li class="is--active">
											<a class="panel-action" href="#">Availbility</a>
										</li>
										<li>
											<a class="panel-action" href="#">Introduction</a>
										</li>
									</ul>
								</div>
								<div class="panel-box__body">
									<div class="panel-video">
										<div class="dummy-video">
											<div class="video-media ratio ratio--16by9">
												<img src="images/video.png" alt="">
											</div>
											<div class="icon-play">

											</div>
										</div>    
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-list ">
						<div class="box__primary">
							<div class="list__head">
								<div class="list__media ">
									<div class="avtar avtar--centered ratio ratio--1by1">
										<a href="#"><img src="images/140x140_2.png" alt=""></a>
									</div>
								</div>
								<div class="list__price">
									<p>$22.00 / hour</p>
								</div>
								<div class="list__action">
									<a href="#" class="btn btn--primary color-white btn--block">Book Now</a>
									<a href="#" class="btn btn--bordered color-primary btn--block">
										<svg class="icon icon--envelope"><use xlink:href="images/sprite.yo-coach.svg#envelope"></use></svg>
										Contact
									</a>
								</div>
							</div>
							<div class="list__body">
								<div class="profile-detail">
									 <div class="profile-detail__head">
										<a href="#" class="tutor-name">
											<h4>Mark Boucher</h4>
											<div class="flag">
												<img src="images/flag-new/flag-sa.png" alt="">
											</div>
										</a>
										<div class="follow ">
											<a href="#">
												<svg class="icon icon--heart"><use xlink:href="images/sprite.yo-coach.svg#heart"></use></svg>
											</a>
										</div>
									 </div>
									 <div class="profile-detail__body">
										<div class="info-wrapper">
											<div class="info-tag location">
												<svg class="icon icon--location"><use xlink:href="images/sprite.yo-coach.svg#location"></use></svg>
												<span class="lacation__name">South Africa</span>
											</div>
											<div class="info-tag ratings">
												<svg class="icon icon--rating"><use xlink:href="images/sprite.yo-coach.svg#rating"></use></svg>
												<span class="value">4.5</span>
												<span class="count">(185)</span>
											</div>

											<div class="info-tag list-count">
												<div class="total-count"><span class="value">178</span>Students</div> - <div class="total-count"><span class="value">235</span>Lessons</div>
											</div>
										</div>

										<div class="tutor-info">
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Teaches</h6>
												</div>
												<div class="info__language">
													ENGLISH, FRENCH, GERMAN, ITALIAN, DUTCH
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Also Speaks</h6>
												</div>
												<div class="info__language">
													ENGLISH (Expert), FRENCH (Advanced), GERMAN (Expert)
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>About</h6>
												</div>
											   <p>Hello! My name is Steven. I am happy to help you learn German. My lessons are informative, interesting and friendly. <a href="#">View Profile</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box__secondary">
							<div class="panel-box">
								<div class="panel-box__head">
									<ul>
										<li class="is--active">
											<a class="panel-action" href="#">Availbility</a>
										</li>
										<li>
											<a class="panel-action" href="#">Introduction</a>
										</li>
									</ul>
								</div>
								<div class="panel-box__body">
									<div class="panel-content">
										<div class="custom-calendar">
											<table>
												<thead>
													<tr>
														<th>&nbsp;</th>
														<th>Mon</th>
														<th>tue</th>
														<th>wed</th>
														<th>thu</th>
														<th>fri</th>
														<th>sat</th>
														<th>sun</th> 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-list ">
						<div class="box__primary">
							<div class="list__head">
								<div class="list__media ">
									<div class="avtar avtar--centered ratio ratio--1by1">
										<a href="#"><img src="images/140x140_1.png" alt=""></a>
									</div>
								</div>
								<div class="list__price">
									<p>$26.00 / hour</p>
								</div>
								<div class="list__action">
									<a href="#" class="btn btn--primary color-white btn--block">Book Now</a>
									<a href="#" class="btn btn--bordered color-primary btn--block">
										<svg class="icon icon--envelope"><use xlink:href="images/sprite.yo-coach.svg#envelope"></use></svg>
										Contact
									</a>
								</div>
							</div>
							<div class="list__body">
								<div class="profile-detail">
									 <div class="profile-detail__head">
										<a href="#" class="tutor-name">
											<h4>Nathan Astle</h4>
											<div class="flag">
												<img src="images/flag-new/flag-uk.png" alt="">
											</div>
										</a>
										<div class="follow ">
											<a href="#">
												<svg class="icon icon--heart"><use xlink:href="images/sprite.yo-coach.svg#heart"></use></svg>
											</a>
										</div>
									 </div>
									 <div class="profile-detail__body">
										<div class="info-wrapper">
											<div class="info-tag location">
												<svg class="icon icon--location"><use xlink:href="images/sprite.yo-coach.svg#location"></use></svg>
												<span class="lacation__name">Newzeland</span>
											</div>
											<div class="info-tag ratings">
												<svg class="icon icon--rating"><use xlink:href="images/sprite.yo-coach.svg#rating"></use></svg>
												<span class="value">4.5</span>
												<span class="count">(185)</span>
											</div>

											<div class="info-tag list-count">
												<div class="total-count"><span class="value">330</span>Students</div> - <div class="total-count"><span class="value">199</span>Lessons</div>
											</div>
										</div>

										<div class="tutor-info">
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Teaches</h6>
												</div>
												<div class="info__language">
													ENGLISH, FRENCH, GERMAN, ITALIAN, DUTCH
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>Also Speaks</h6>
												</div>
												<div class="info__language">
													ENGLISH (Expert), FRENCH (Advanced), GERMAN (Expert)
												</div>
											</div>
											<div class="tutor-info__inner">
												<div class="info__title">
													<h6>About</h6>
												</div>
											   <p>Hello! My name is Steven. I am happy to help you learn German. My lessons are informative, interesting and friendly. <a href="#">View Profile</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box__secondary">
							<div class="panel-box">
								<div class="panel-box__head">
									<ul>
										<li class="is--active">
											<a class="panel-action" href="#">Availbility</a>
										</li>
										<li>
											<a class="panel-action" href="#">Introduction</a>
										</li>
									</ul>
								</div>
								<div class="panel-box__body">
									<div class="panel-content">
										<div class="custom-calendar">
											<table>
												<thead>
													<tr>
														<th>&nbsp;</th>
														<th>Mon</th>
														<th>tue</th>
														<th>wed</th>
														<th>thu</th>
														<th>fri</th>
														<th>sat</th>
														<th>sun</th> 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-80 cal-cell"></div><div class="tooltip tooltip--top bg-black">3 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-40 cal-cell"></div><div class="tooltip tooltip--top bg-black">1 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-100 cal-cell"></div><div class="tooltip tooltip--top bg-black">4 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
													<tr>
														<td><div class="cal-cell">00 - 04</div></td>
														<td><div class="cal-cell"></div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td class="is-hover"><div class="cell-green-60 cal-cell"></div><div class="tooltip tooltip--top bg-black">2 Hrs</div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
														<td><div class="cal-cell"></div></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="show-more">
						<a href="#" class="btn btn--show">Show More</a>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

</section>

<!-- <section class="section section--gray section--listing section--listing-js">
    <div class="container container--narrow">
		<div class="row -clearfix">
            <?php
			/* Left Side Filters Side Bar [ */
			$this->includeTemplate('teachers/_partial/teacherLeftFilters.php', array( 'spokenLanguage_filter' => $spokenLanguage_filter, 'preferenceFilter_filter'=> $preferenceFilter_filter, 'fromCountry_filter' => $fromCountry_filter, 'gender_filter' => $gender_filter, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'siteLangId' => $siteLangId ));
			/* ] */
			?>

            <div class="col-xl-9 col-lg-12 -float-right" id="teachersListingContainer">

            </div>

            <div class="col-xl-3 col-lg-12 -float-left d-block d-xl-none">
                <div class="box box--cta -padding-30 -align-center">
                    <h4 class="-text-bold"><?php echo Label::getLabel('LBL_Want_to_be_a_teacher?'); ?></h4>
                    <p><?php $str = Label::getLabel( 'LBL_If_you\'re_interested_in_being_a_teacher_on_{sitename},_please_apply_here.' );
					 $siteName = FatApp::getConfig( 'CONF_WEBSITE_NAME_'.$siteLangId, FatUtility::VAR_STRING, '' );
					 $str = str_replace( "{sitename}", $siteName, $str );
					 echo $str;
					 ?></p>
                    <a href="javascript:void(0)" onClick="signUpFormPopUp('teacher');" class="btn btn--primary btn--block"><?php echo Label::getLabel('LBL_Apply_to_be_a_teacher'); ?></a>
                </div>
            </div>

        </div>
    </div>
</section> -->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
