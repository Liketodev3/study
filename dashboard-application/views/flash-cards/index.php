<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<!-- [ PAGE ========= -->
<main class="page">
	<div class="container container--fixed">
		<div class="page__head">
			<div class="row align-items-center justify-content-between">
				<div class="col-sm-6">
					<h1><?php echo Label::getLabel('LBL_Manage_Flash_Cards'); ?></h1>
				</div>
				<div class="col-sm-auto">
					<div class="buttons-group d-flex align-items-center">
						<a href="javascript:void(0)" class="btn bg-secondary slide-toggle-js">
							<svg class="icon icon--clock icon--small margin-right-2">
								<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#search'; ?>"></use>
							</svg>
							<?php echo Label::getLabel('LBL_Search'); ?>
						</a>

					</div>

				</div>
			</div>

			<!-- [ FILTERS ========= -->
			<div class="search-filter slide-target-js" style="display: none;">
				<?php
					$frmSrch->setFormTagAttribute('onsubmit', 'searchFlashCards(this); return(false);');
					$frmSrch->setFormTagAttribute('class', 'form form--small');

					$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
					$frmSrch->developerTags['fld_default_col'] = 4;

					$fldLanguage = $frmSrch->getField('slanguage_id');
					$fldStatus->developerTags['col'] = 4;

					$fldSubmit = $frmSrch->getField('btn_submit');
					$fldSubmit->developerTags['col'] = 4;

					$btnReset = $frmSrch->getField('btn_reset');
					$btnReset->addFieldTagAttribute('onclick', 'clearSearch()');
					echo $frmSrch->getFormHtml(); 
				?>
			</div>
			<!-- ] ========= -->
		</div>
		<div class="page__body">
			<!-- [ PAGE PANEL ========= -->
			<div class="page-content">
				<div class="page-panel page-panel--flex page-panel--large min-height-500 margin-top-4">
					<div class="page-panel__small">
						<div class="box-white">
							<div class="flashcard" id="flashCardReviewSection">
							</div>
						</div>
					</div>
					<div class="page-panel__large" id="listItems">
						<div class="table-scroll">
							<table class="table table--styled table--responsive table--aligned-middle">
								<tr class="title-row">

									<th>Word</th>
									<th>Definition</th>

									<th>Action</th>
								</tr>
								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">umpteen (en)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">indefinitely many (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>


								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">comunicarme (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content"> communicate (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">Hacer (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">to do or to make (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">Hacer (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">to do or to make (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">umpteen (en)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">indefinitely many (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>


								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">comunicarme (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content"> communicate (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>


								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">umpteen (en)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">indefinitely many (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>


								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">comunicarme (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content"> communicate (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">Hacer (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">to do or to make (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">Hacer (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">to do or to make (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">umpteen (en)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">indefinitely many (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>


								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">comunicarme (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content"> communicate (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">Hacer (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">to do or to make (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Word: </div>
											<div class="flex-cell__content">Hacer (es)</div>
										</div>

									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Definition: </div>
											<div class="flex-cell__content">to do or to make (en)</div>
										</div>
									</td>
									<td>
										<div class="flex-cell">
											<div class="flex-cell__label">Actions: </div>
											<div class="flex-cell__content">
												<div class="actions-group">
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#edit"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Edit</div>
													</a>
													<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
														<svg class="icon icon--issue icon--small">
															<use xlink:href="images/sprite.yo-coach.svg#trash"></use>
														</svg>
														<div class="tooltip tooltip--top bg-black">Delete</div>
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>

						<div class="table-controls padding-6">
							<div class="pagination pagination--centered pagination--curve">
								<ul>
									<li>
										<button class="is-backward"></button>
									</li>
									<li>
										<button class="is-prev"></button>
									</li>
									<li>
										<button class="is-active">1</button>
									</li>
									<li>
										<button>2</button>
									</li>
									<li>
										<button class="is-disabled">3</button>
									</li>
									<li>
										<button>4</button>
									</li>
									<li>
										<button>5</button>
									</li>
									<li>
										<button class="is-next"></button>
									</li>
									<li>
										<button class="is-forward"></button>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- ] -->
		</div>
		<div class="page__footer align-center">
			<p class="small">Copyright © 2021 Yo!Coach Developed by <a href="#" class="underline color-primary">FATbit Technologies</a> . </p>
		</div>
	</div>
</main>
<!-- ] -->